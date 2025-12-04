<?php
/**
 * mitrocops
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 /*
 * 
 * @author    mitrocops
 * @category seo
 * @package gsnipreview
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */

class gsnipreviewhelp {
	
	private $_name = 'gsnipreview';
    private $_is_cloud;
    private $path_img_cloud;


    private $_width_ava = 85;
    private $_height_ava = 85;

    private $_width_files = 800;
    private $_height_files = 800;
    private $_width_files_small = 100;
    private $_height_files_small = 100;

    private $_id_shop;

    private $_accepted_files = array('png', 'jpg', 'gif','jpeg');
    private $path_img_cloud_site;


    public function __construct(){
		if (version_compare(_PS_VERSION_, '1.5', '<')){
			require_once(_PS_MODULE_DIR_.$this->_name.'/backward_compatibility/backward.php');
		}

        if(version_compare(_PS_VERSION_, '1.5', '>')){
            $this->_id_shop = Context::getContext()->shop->id;


        } else {
            $this->_id_shop = 1;

        }


        if (defined('_PS_HOST_MODE_'))
            $this->_is_cloud = 1;
        else
            $this->_is_cloud = 0;


        // for test
        //$this->_is_cloud = 1;
        // for test


        if($this->_is_cloud){
            $this->path_img_cloud = DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR;
            $this->path_img_cloud_site = "modules/".$this->_name."/upload/";
        } else {
            $this->path_img_cloud = DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR.$this->_name.DIRECTORY_SEPARATOR;
            $this->path_img_cloud_site = "upload/".$this->_name."/";

        }


		
		$this->initContext();
	}


	
	private function initContext()
	{
		$this->context = Context::getContext();
	}
	
	public function getStepForMyReviewsAll(){
		return Configuration::get($this->_name.'revperpagecus');
	}


    private function getConditionMultilanguageAndMultiStore($data){

        $and = ($data['and']==1)?'AND':'';

        $id_shop = $this->getIdShop();

        if(Configuration::get($this->_name.'rswitch_lng') == 1){
            $cookie = $this->context->cookie;
            $id_lang = (int)($cookie->id_lang);
            $sql_condition = $and.'  id_lang = '.(int)($id_lang).' AND id_shop = '.(int)($id_shop).'';
        } else {
            $sql_condition = $and.'  id_shop = '.(int)($id_shop).'';
        }
        return $sql_condition;
    }
	
	public function sendNotificationAddReview($data = null){
		
		if(Configuration::get($this->_name.'noti') == 1){
			$review = $data['text_review'];
			$title = $data['title'];
			$customer_name = $data['customer_name'];
			$product_name = $data['product_name'];
			$product_link = $data['product_link'];
			$rating = $data['rating'];
            $picture = $data['picture'];
			
			$cookie = $this->context->cookie;
			
			/* Email generation */
			$templateVars = array(
				'{title}' => $title,
				'{review}' => Tools::stripslashes($review),
				'{customer_name}' => $customer_name,
				'{product_name}' => $product_name,
				'{product_link}' => $product_link,
				'{rating}' => $rating,
                '{picture}' => $picture,
			);
			
			$id_lang = (int)($cookie->id_lang);	
			
			$iso_lng = Language::getIsoById((int)($id_lang));
			
			$dir_mails = _PS_MODULE_DIR_ . '/' . $this->_name . '/' . 'mails/';
			
			if (is_dir($dir_mails . $iso_lng . '/')) {
				$id_lang_current = $id_lang;
			}
			else {
				$id_lang_current = Language::getIdByIso('en');
			}
			####
			


            $subject_newrev = Configuration::get($this->_name . 'newrevr_' . $id_lang);

			/* Email sending */
			Mail::Send($id_lang_current, 'reviewserg', $subject_newrev, $templateVars,
				Configuration::get($this->_name.'mail'), 'New Review Form', NULL, NULL,
				NULL, NULL, dirname(__FILE__).'/../mails/');
		}
		
	}

    public function sendNotificationThankyouReview($data = null){
        $cookie = $this->context->cookie;

        $id_lang = isset($data['id_lang'])?$data['id_lang']:(int)($cookie->id_lang);


        include_once(dirname(__FILE__).'/../gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();


        $_prefix = $obj_gsnipreview->getPrefixProductReviews();


        $subject_thank_you = Configuration::get($this->_name . 'thankyou'.$_prefix.'_' . $id_lang);

        $name = $data['name'];
        $email = $data['email'];
        /* Email generation */
        $templateVars = array(
            '{name}' => $name,

        );

        /* Email sending */

        $iso_lng = Language::getIsoById((int)($id_lang));

        $dir_mails = _PS_MODULE_DIR_ . '/' . $this->_name . '/' . 'mails/';

        if (is_dir($dir_mails . $iso_lng . '/')) {
            $id_lang_current = $id_lang;
        }
        else {
            $id_lang_current = Language::getIdByIso('en');
        }

        Mail::Send($id_lang_current, 'review-thank-you-'.$_prefix, $subject_thank_you, $templateVars,
            $email, 'Thank you Form', NULL, NULL,
            NULL, NULL, dirname(__FILE__).'/../mails/');



    }
	
	public function getAllReviewsAdmin($data){
		$start = $data['start'];
		$step = Configuration::get($this->_name.'adminrevperpage');
		
		$cookie = $this->context->cookie;
		
		$id_lang = (int)($cookie->id_lang);
		
		
		$reviews = Db::getInstance()->ExecuteS('
		SELECT pc.*,
		(SELECT ga2c.avatar_thumb from '._DB_PREFIX_.''.$this->_name.'_avatar2customer ga2c
                                                    WHERE ga2c.id_customer = pc.id_customer
                    ) as avatar_thumb
		FROM `'._DB_PREFIX_.'gsnipreview` pc WHERE id_shop = '.(int)($this->getIdShop()).'
		ORDER BY pc.`time_add` DESC LIMIT '.(int)($start).' ,'.(int)($step).'');
		
		$reviews_tmp = $reviews;
		
		foreach($reviews_tmp as $k => $_item){
		$product_id = $_item['id_product'];

            $id_customer = $_item['id_customer'];
            $avatar = $_item['avatar'];
            $info_path = $this->_getAvatarPath(array('avatar' => $avatar, 'id_customer'=>$id_customer ));
            $reviews[$k]['avatar'] = $info_path['avatar'];
            $reviews[$k]['is_show_ava'] = $info_path['is_show'];
		
		$product_obj = new Product($product_id);
		$name_product = $product_obj->name[$id_lang];
		$reviews[$k]['product_name'] = $name_product; 
		
		// link to product
			$product_obj = new Product($product_id);
			
			$data_product = $this->_productData(array('product'=>$product_obj));	
			$product_link = $data_product['product_url'];
			$picture = $data_product['image_link'];
			$reviews[$k]['image_link'] = $picture;
			
	    	$reviews[$k]['product_link'] = $product_link;
	    //link to product
            $id_lang = $_item['id_lang'];
            $id_shop = $_item['id_shop'];
            $data_seo_url = $this->getSEOURLs(array('id_lang'=>$id_lang,'id_shop'=>$id_shop));

            $id_review = $_item['id'];

            $rev_url = $data_seo_url['rev_url'];
            $reviews[$k]['review_url'] = $rev_url.((Configuration::get('PS_REWRITING_SETTINGS') || version_compare(_PS_VERSION_, '1.5', '<'))?'?':'&').'rid='.$id_review;


            ## helpfull ##
            $data_helpfull = $this->getHelpfullVotes(array('review_id'=>$id_review));
            $yes = $data_helpfull['yes'];
            $reviews[$k]['helpful_votes'] = $yes;
            ## helpfull ##
		}
		
		$data_count_reviews = Db::getInstance()->getRow('
		SELECT COUNT(`id`) AS "count"
		FROM `'._DB_PREFIX_.'gsnipreview`
		WHERE id_shop = '.(int)($this->getIdShop()).'
		');
		
		
		return array('reviews' => $reviews, 'count_all_reviews' => $data_count_reviews['count'] );
	}
	
	public function getCountReviews($data = null){
        $id_product = isset($data['id_product'])?$data['id_product']:null;

        $sql_cond_product = '';
        if($id_product){
            $sql_cond_product = '`id_product` = '.(int)($id_product).' AND ';
        }


        $sql_cond_customer = '';
        $user_id = (int)Tools::getValue('uid');
        if($user_id){
            $sql_cond_customer = '`id_customer` = '.(int)($user_id).' AND ';
        }


        $sql_condition = $this->getConditionMultilanguageAndMultiStore(array('and'=>0));

        $sql = '
		SELECT COUNT(`id`) AS "count"
                    FROM `'._DB_PREFIX_.'gsnipreview` pc
                    WHERE '.$sql_cond_product.' '.$sql_cond_customer.' '.$sql_condition.' and is_active = 1';
        if (($result = Db::getInstance()->getRow($sql)) === false)
			return false;
		return (int)($result['count']);
	}




	public function getAvgReview($data = null){


        $id_product = isset($data['id_product'])?$data['id_product']:null;

        $sql_cond_product = '';
        if($id_product){
            $sql_cond_product = '`id_product` = '.(int)($id_product).' AND ';
        }


        $sql_cond_customer = '';
        $user_id = (int)Tools::getValue('uid');
        if($user_id){
            $sql_cond_customer = '`id_customer` = '.(int)($user_id).' AND ';
        }




        $sql_condition = $this->getConditionMultilanguageAndMultiStore(array('and'=>1));

        $sql = '
		SELECT ceil(AVG(`rating`)) AS "avg_rating",  round(AVG(`rating`),2) AS "avg_rating_decimal"
		FROM `'._DB_PREFIX_.'gsnipreview` pc
		WHERE '.$sql_cond_product.' '.$sql_cond_customer.' rating != 0 '.$sql_condition.' and is_active = 1';
        $result = Db::getInstance()->getRow($sql);

        //var_dumP($result);
		
		return array('avg_rating'=>(int)$result['avg_rating'],
            'avg_rating_decimal'=>(isset($result['avg_rating_decimal'])?str_replace(".",",",$result['avg_rating_decimal']):0));
	}
	
	
	
	public function getCountRatingForItem(){




        $sql_cond_product = '';
        $id_product = (int)Tools::getValue('id_product');
        if($id_product){
            $sql_cond_product = '`id_product` = '.(int)($id_product).' AND ';
        }



        $sql_cond_customer = '';
        $user_id = (int)Tools::getValue('uid');
        if($user_id){
            $sql_cond_customer = '`id_customer` = '.(int)($user_id).' AND ';
        }

        $sql_condition = $this->getConditionMultilanguageAndMultiStore(array('and'=>0));

		$data_return = array();
		// one
		$sql = 'select count(*) as count 
					   FROM `'._DB_PREFIX_.'gsnipreview` 
					   WHERE '.$sql_cond_product.' '.$sql_cond_customer.' '.$sql_condition.'
					   AND rating = 1';
		$result = Db::getInstance()->getRow($sql);
		$data_return['one'] = (int)$result['count'];
		
		// two
		$sql = 'select count(*) as count 
					   FROM `'._DB_PREFIX_.'gsnipreview` 
					   WHERE '.$sql_cond_product.' '.$sql_cond_customer.' '.$sql_condition.'
					   AND rating = 2';
		$result = Db::getInstance()->getRow($sql);
		$data_return['two'] = (int)$result['count'];
		
		// three
		$sql = 'select count(*) as count 
					   FROM `'._DB_PREFIX_.'gsnipreview` 
					   WHERE '.$sql_cond_product.' '.$sql_cond_customer.' '.$sql_condition.'
					   AND rating = 3';
		$result = Db::getInstance()->getRow($sql);
		$data_return['three'] = (int)$result['count'];
	
		// four
		$sql = 'select count(*) as count 
					   FROM `'._DB_PREFIX_.'gsnipreview` 
					   WHERE '.$sql_cond_product.' '.$sql_cond_customer.' '.$sql_condition.'
					   AND rating = 4';
		$result = Db::getInstance()->getRow($sql);
		$data_return['four'] = (int)$result['count'];
	
		// five
		$sql = 'select count(*) as count 
					   FROM `'._DB_PREFIX_.'gsnipreview` 
					   WHERE '.$sql_cond_product.' '.$sql_cond_customer.' '.$sql_condition.'
					   AND rating = 5';
		$result = Db::getInstance()->getRow($sql);
		$data_return['five'] = (int)$result['count'];
		
		return $data_return;
	
	}
	




    public function getBlockLastReviews($data){
        $step = (int) $data['step'];
        $prefix = isset($data['prefix'])?$data['prefix']:'';

        $cookie = $this->context->cookie;

        $id_lang = (int)($cookie->id_lang);

        $sql_condition = $this->getConditionMultilanguageAndMultiStore(array('and'=>0));

        $sql = '
		SELECT pc.*
		FROM `'._DB_PREFIX_.'gsnipreview` pc
		where '.$sql_condition.'
		ORDER BY pc.`time_add` DESC LIMIT '.(int)($step);

        $reviews = Db::getInstance()->ExecuteS($sql);


        $i=0;
        foreach($reviews as $_item){

            $id_customer = $_item['id_customer'];
            $avatar = $_item['avatar'];
            $info_path = $this->_getAvatarPath(array('avatar' => $avatar, 'id_customer'=>$id_customer ));
            $reviews[$i]['avatar'] = $info_path['avatar'];
            $reviews[$i]['is_show_ava'] = $info_path['is_show'];

            $product_id = $_item['id_product'];

            $is_buy = 0;
            if($id_customer) {
                $is_buy = $this->checkProductBought(array('id_customer' => $id_customer,'id_product'=>$product_id));
            }
            $reviews[$i]['is_buy'] = $is_buy;



            $product_obj = new Product($product_id);

            $name_page = $product_obj->name[$id_lang];


            $data_product = $this->_productData(array('product'=>$product_obj,'block'=>$prefix));
            $product_link = $data_product['product_url'];

            $picture = $data_product['image_link'];

            $reviews[$i]['product_link'] = $product_link;

            $reviews[$i]['product_img'] = $picture;
            $reviews[$i]['product_name'] = $name_page;
            $i++;
        }



        return array('reviews' => $reviews);
    }
	
	public function getHomeLastReviews($data){
		$step = (int) $data['step'];
		$cookie = $this->context->cookie;
		$id_lang = (int)($cookie->id_lang);

        $sql_condition = $this->getConditionMultilanguageAndMultiStore(array('and'=>0));
		
		$sql = '
		SELECT pc.*
		FROM `'._DB_PREFIX_.'gsnipreview` pc
		where '.$sql_condition.'
		ORDER BY pc.`time_add` DESC LIMIT '.(int)($step);
		
		$reviews = Db::getInstance()->ExecuteS($sql);
		
		
		$i=0;
		foreach($reviews as $_item){

            $id_customer = $_item['id_customer'];
            $avatar = $_item['avatar'];
            $info_path = $this->_getAvatarPath(array('avatar' => $avatar, 'id_customer'=>$id_customer ));
            $reviews[$i]['avatar'] = $info_path['avatar'];
            $reviews[$i]['is_show_ava'] = $info_path['is_show'];


			$product_id = $_item['id_product'];

            $is_buy = 0;
            if($id_customer) {
                $is_buy = $this->checkProductBought(array('id_customer' => $id_customer,'id_product'=>$product_id));
            }
            $reviews[$i]['is_buy'] = $is_buy;


            $product_obj = new Product($product_id);
			
			$name_page = $product_obj->name[$id_lang];
					
			$data_product = $this->_productData(array('product'=>$product_obj,'block'=>'home'));
			$product_link = $data_product['product_url'];
			
	    	$reviews[$i]['product_link'] = $product_link;
	    	
	    	$picture = $data_product['image_link'];
	    	
	    	$reviews[$i]['product_img'] = $picture;
	    	$reviews[$i]['product_name'] = $name_page;
	    $i++;
		}
		
		
		
		return array('reviews' => $reviews);
	}



    public function getMyReviews($data){
        $cookie = $this->context->cookie;
        $id_lang = (int)($cookie->id_lang);
        $id_customer = $data['id_customer'];
        $start = $data['start'];

        $frat = isset($data['frat'])?(int)$data['frat']:null;
        $sql_rating = '';
        if($frat) {
            if ($frat > 5)
                $frat = 5;
            $sql_rating = ' AND rating = '.(int)$frat.'  ';
        }


        $is_search = isset($data['is_search'])?$data['is_search']:0;
        $search = isset($data['search'])?$data['search']:'';


        $sql_condition_search = '';
        if($is_search == 1){
            $sql_condition_search = " AND (
	    		   LOWER(title_review) LIKE BINARY LOWER('%".pSQL(trim($search))."%')
	    		   OR
	    		   LOWER(text_review) LIKE BINARY LOWER('%".pSQL(trim($search))."%')
	    		   OR
	    		   LOWER(admin_response) LIKE BINARY LOWER('%".pSQL(trim($search))."%')
	    		   )  ";
        }

        $id_shop = $this->getIdShop();
        $step = isset($data['step'])?$data['step']:$this->getStepForMyReviewsAll();

        $sql = '
		SELECT pc.*
		FROM `'._DB_PREFIX_.'gsnipreview` pc
		where id_shop = '.(int)($this->getIdShop()).' AND id_customer = '.(int)($id_customer).' '.$sql_rating.' '.$sql_condition_search.'
		ORDER BY pc.`time_add` DESC LIMIT '.(int)($start).', '.(int)($step);

        $reviews = Db::getInstance()->ExecuteS($sql);

        $i=0;
        foreach($reviews as $_item){


            $id_customer = $_item['id_customer'];
            $avatar = $_item['avatar'];
            $info_path = $this->_getAvatarPath(array('avatar' => $avatar, 'id_customer'=>$id_customer ));
            $reviews[$i]['avatar'] = $info_path['avatar'];
            $reviews[$i]['is_show_ava'] = $info_path['is_show'];

            $product_id = $_item['id_product'];

            $is_buy = 0;
            if($id_customer) {
                $is_buy = $this->checkProductBought(array('id_customer' => $id_customer,'id_product'=>$product_id));
            }
            $reviews[$i]['is_buy'] = $is_buy;


            $product_id = $_item['id_product'];

            $product_obj = new Product($product_id);

            $name_page = $product_obj->name[$id_lang];

            $data_product = $this->_productData(array('product'=>$product_obj,'block'=>'home'));
            $product_link = $data_product['product_url'];

            $picture = $data_product['image_link'];

            $reviews[$i]['product_link'] = $product_link;

            $reviews[$i]['product_img'] =$picture;
            $reviews[$i]['product_name'] = $name_page;

            $id_customer = $_item['id_customer'];
            $is_bought=0;
            if($id_customer) {
                $is_bought = $this->checkProductBought(array('id_customer'=>$id_customer,'id_product'=>$product_id));
            }
            $reviews[$i]['is_bought'] = $is_bought;

            ## criterions ##
            $id_review = $reviews[$i]['id'];
            $id_lang_product_review = $reviews[$i]['id_lang'];
            $data_criterions = $this->getCriterionsByProductReview(array('id_review'=>$id_review,'id_lang'=>$id_lang_product_review,'id_shop'=>$id_shop));
            $reviews[$i]['criterions'] = $data_criterions;
            ## criterions ##

            ## helpfull ##
            $data_helpfull = $this->getHelpfullVotes(array('review_id'=>$id_review));
            $yes = $data_helpfull['yes'];
            $all = $data_helpfull['all'];
            $reviews[$i]['helpfull_yes'] = $yes;
            $reviews[$i]['helpfull_all'] = $all;
            ## helpfull ##

            $data_files = $this->getFiles2Review(array('id_review'=>$id_review));
            $reviews[$i]['files'] = $data_files;


            $ip = $_item['ip'];
            if(!empty($ip)) {
                $ip = $this->getCityandCountry(array('ip' => $ip));
                $reviews[$i]['ip'] = $ip;
                $reviews[$i]['is_no_ip'] = 1;
            } else {
                $reviews[$i]['is_no_ip'] = 0;
            }


            $i++;
        }


        $result = Db::getInstance()->getRow('
		SELECT COUNT(`id`) AS "count"
		FROM `'._DB_PREFIX_.'gsnipreview` pc
		where id_shop = '.(int)($this->getIdShop()).' AND id_customer = '.(int)($id_customer).' '.$sql_rating.' '.$sql_condition_search.'');

        return array('reviews' => $reviews, 'count_all' => (int)$result['count']);
    }
	
	
	public function getAllReviews($data){
		$step = (int) $data['step'];
		$start = (int) $data['start'];
		$cookie = $this->context->cookie;
		$id_lang = (int)($cookie->id_lang);



        $frat = isset($data['frat'])?(int)$data['frat']:null;
        $sql_rating = '';
        if($frat) {
            if ($frat > 5)
                $frat = 5;
            $sql_rating = ' AND rating = '.(int)$frat.'  ';
        }


        $is_search = isset($data['is_search'])?$data['is_search']:0;
        $search = isset($data['search'])?$data['search']:'';


        $sql_condition_search = '';
        if($is_search == 1){
            $sql_condition_search = " AND (
	    		   LOWER(title_review) LIKE BINARY LOWER('%".pSQL(trim($search))."%')
	    		   OR
	    		   LOWER(text_review) LIKE BINARY LOWER('%".pSQL(trim($search))."%')
	    		   OR
	    		   LOWER(admin_response) LIKE BINARY LOWER('%".pSQL(trim($search))."%')
	    		   )  ";
        }


        $id_shop = $this->getIdShop();
        $sql_condition = $this->getConditionMultilanguageAndMultiStore(array('and'=>0));
		
		$sql = '
		SELECT pc.*
		FROM `'._DB_PREFIX_.'gsnipreview` pc
		where '.$sql_condition.' '.$sql_rating.' '.$sql_condition_search.'
		ORDER BY pc.`time_add`  DESC LIMIT '.(int)($start).', '.(int)($step);


		
		$reviews = Db::getInstance()->ExecuteS($sql);
		
		
		$i=0;
		foreach($reviews as $k=> $_item){

            $id_customer = $_item['id_customer'];
            $avatar = $_item['avatar'];
            $info_path = $this->_getAvatarPath(array('avatar' => $avatar, 'id_customer'=>$id_customer ));
            $reviews[$k]['avatar'] = $info_path['avatar'];
            $reviews[$k]['is_show_ava'] = $info_path['is_show'];

			$product_id = $_item['id_product'];

            $is_buy = 0;
            if($id_customer) {
                $is_buy = $this->checkProductBought(array('id_customer' => $id_customer,'id_product'=>$product_id));
            }
            $reviews[$k]['is_buy'] = $is_buy;


            $product_obj = new Product($product_id);


			$name_page = Tools::stripslashes($product_obj->name[$id_lang]);
				
			$data_product = $this->_productData(array('product'=>$product_obj,'block'=>'home'));
			$product_link = $data_product['product_url'];


            $reviews[$k]['product_link'] = $product_link;
	    	
	    	$picture = $data_product['image_link'];
	    	
	    	$reviews[$k]['product_img'] = $picture;
	    	$reviews[$k]['product_name'] = $name_page;


            $text_review = $reviews[$k]['text_review'];
            if(Tools::strlen($text_review)>150) {
                $text_review = strip_tags($text_review);
            } else {
                $text_review = preg_replace("/(^|[\n ])([\w]*?)([^\"\>]?(ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" target=\"_blank\" >$3</a>", $text_review);
            }
            $text_review= preg_replace("/(^|[\n ])([\w]*?)([^\"\>]?(ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" target=\"_blank\" >$3</a>", $text_review);

            $reviews[$k]['text_review'] = $text_review;

            $admin_response = isset($reviews[$k]['admin_response'])?$reviews[$k]['admin_response']:null;
            $admin_response = preg_replace("/(^|[\n ])([\w]*?)([^\"\>]?(ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" target=\"_blank\" >$3</a>", $admin_response);
            $reviews[$k]['admin_response'] = $admin_response;



            ## criterions ##
            $id_review = $reviews[$k]['id'];
            $id_lang_product_review = $reviews[$k]['id_lang'];
            $data_criterions = $this->getCriterionsByProductReview(array('id_review'=>$id_review,'id_lang'=>$id_lang_product_review,'id_shop'=>$id_shop));
            $reviews[$k]['criterions'] = $data_criterions;
            ## criterions ##

            ## helpfull ##
            $data_helpfull = $this->getHelpfullVotes(array('review_id'=>$id_review));
            $yes = $data_helpfull['yes'];
            $all = $data_helpfull['all'];
            $reviews[$k]['helpfull_yes'] = $yes;
            $reviews[$k]['helpfull_all'] = $all;
            ## helpfull ##


            $data_files = $this->getFiles2Review(array('id_review'=>$id_review));
            $reviews[$k]['files'] = $data_files;


            $data_seo_url = $this->getSEOURLs(array('id_lang'=>$id_lang,'id_shop'=>$id_shop));

            $rev_url = $data_seo_url['rev_url'];
            $reviews[$k]['review_url'] = $rev_url.((Configuration::get('PS_REWRITING_SETTINGS') || version_compare(_PS_VERSION_, '1.5', '<'))?'?':'&').'rid='.$id_review;


            $ip = $_item['ip'];
            if(!empty($ip)) {
                $ip = $this->getCityandCountry(array('ip' => $ip));
                $reviews[$k]['ip'] = $ip;
                $reviews[$k]['is_no_ip'] = 1;
            } else {
                $reviews[$k]['is_no_ip'] = 0;
            }

	    $i++;
		}
		
		
		$result = Db::getInstance()->getRow('
		SELECT COUNT(`id`) AS "count"
		FROM `'._DB_PREFIX_.'gsnipreview` pc
		WHERE '.$sql_condition.' '.$sql_rating.' '.$sql_condition_search.'');
		
		
		return array('reviews' => $reviews, 'count_all' => (int)$result['count']);
	}



    public function getOneReview($data){
        $rid = isset($data['rid'])?$data['rid']:0;
        $cookie = $this->context->cookie;
        $id_lang = (int)($cookie->id_lang);

        $id_shop = $this->getIdShop();
        $sql_condition = $this->getConditionMultilanguageAndMultiStore(array('and'=>0));

        $sql = '
		SELECT pc.*
		FROM `'._DB_PREFIX_.'gsnipreview` pc
		where id = '.(int)$rid.' and '.$sql_condition.'
		';


        $reviews = Db::getInstance()->ExecuteS($sql);


        $i=0;
        foreach($reviews as $k=> $_item){

            $id_customer = $_item['id_customer'];
            $avatar = $_item['avatar'];
            $info_path = $this->_getAvatarPath(array('avatar' => $avatar, 'id_customer'=>$id_customer ));
            $reviews[$i]['avatar'] = $info_path['avatar'];
            $reviews[$i]['is_show_ava'] = $info_path['is_show'];

            $product_id = $_item['id_product'];

            $is_buy = 0;
            if($id_customer) {
                $is_buy = $this->checkProductBought(array('id_customer' => $id_customer,'id_product'=>$product_id));
            }
            $reviews[$i]['is_buy'] = $is_buy;


            $product_obj = new Product($product_id);

            $reviews[$i]['active_product'] = $product_obj->active;


            $name_page = Tools::stripslashes($product_obj->name[$id_lang]);
            $description_short = $product_obj->description_short[$id_lang];

            $data_product = $this->_productData(array('product'=>$product_obj));
            $product_link = $data_product['product_url'];


            $reviews[$i]['product_link'] = $product_link;

            $picture = $data_product['image_link'];

            $reviews[$i]['product_img'] = $picture;
            $reviews[$i]['product_name'] = $name_page;
            $reviews[$i]['description_short'] = strip_tags($description_short);


            $text_review = $reviews[$k]['text_review'];
            $text_review= preg_replace("/(^|[\n ])([\w]*?)([^\"\>]?(ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" target=\"_blank\" >$3</a>", $text_review);
            $reviews[$k]['text_review'] = $text_review;

            $admin_response = isset($reviews[$k]['admin_response'])?$reviews[$k]['admin_response']:null;
            $admin_response = preg_replace("/(^|[\n ])([\w]*?)([^\"\>]?(ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" target=\"_blank\" >$3</a>", $admin_response);
            $reviews[$k]['admin_response'] = $admin_response;

            ## criterions ##
            $id_review = $reviews[$k]['id'];
            $id_lang_product_review = $reviews[$k]['id_lang'];
            $data_criterions = $this->getCriterionsByProductReview(array('id_review'=>$id_review,'id_lang'=>$id_lang_product_review,'id_shop'=>$id_shop));
            $reviews[$k]['criterions'] = $data_criterions;
            ## criterions ##

            ## helpfull ##
            $data_helpfull = $this->getHelpfullVotes(array('review_id'=>$id_review));
            $yes = $data_helpfull['yes'];
            $all = $data_helpfull['all'];
            $reviews[$k]['helpfull_yes'] = $yes;
            $reviews[$k]['helpfull_all'] = $all;
            ## helpfull ##

            $data_files = $this->getFiles2Review(array('id_review'=>$id_review));
            $reviews[$k]['files'] = $data_files;

            $data_seo_url = $this->getSEOURLs(array('id_lang'=>$id_lang,'id_shop'=>$id_shop));

            $rev_url = $data_seo_url['rev_url'];
            $reviews[$k]['review_url'] = $rev_url.((Configuration::get('PS_REWRITING_SETTINGS') || version_compare(_PS_VERSION_, '1.5', '<'))?'?':'&').'rid='.$id_review;


            $ip = $_item['ip'];
            if(!empty($ip)) {
                $ip = $this->getCityandCountry(array('ip' => $ip));
                $reviews[$k]['ip'] = $ip;
                $reviews[$k]['is_no_ip'] = 1;
            } else {
                $reviews[$k]['is_no_ip'] = 0;
            }

            $i++;
        }





        return array('reviews' => $reviews);
    }



    public function getItem($_data){
		$id = $_data['id'];


			$reviews = Db::getInstance()->ExecuteS('
			SELECT pc.*
			FROM `'._DB_PREFIX_.'gsnipreview` pc
			WHERE pc.`id` = '.(int)($id).'');
			
		$cookie = $this->context->cookie;
		$id_lang = (int)($cookie->id_lang);
		
		$i=0;
		foreach($reviews as $_item){

            $id_customer = $_item['id_customer'];
            $id_lang_review = $_item['id_lang'];

            $user_url = '';
            if($id_customer) {
                $data_seo_url = $this->getSEOURLs(array('id_lang' => $id_lang_review));
                $user_url = $data_seo_url['user_url'];
            }

            $reviews[$i]['user_url'] = $user_url;


            $avatar = $_item['avatar'];


            $info_path = $this->_getAvatarPath(array('avatar' => $avatar, 'id_customer'=>$id_customer ));

            $reviews[$i]['avatar'] = $info_path['avatar'];
            $reviews[$i]['is_exist'] = $info_path['is_exist'];
            $reviews[$i]['is_show_ava'] = $info_path['is_show'];

			$product_id = $_item['id_product'];

            $is_buy = 0;
            if($id_customer) {
                $is_buy = $this->checkProductBought(array('id_customer' => $id_customer,'id_product'=>$product_id));
            }
            $reviews[$i]['is_buy'] = $is_buy;


            $rating = $_item['rating'];
			$customer_name = $_item['customer_name'];
			
			$reviews[$i]['rating'] = $rating;
			$reviews[$i]['customer_name'] = $customer_name;





			$product_obj = new Product($product_id);
			
			$name_page = $product_obj->name[$id_lang];
			
			$data_product = $this->_productData(array('product'=>$product_obj));	
			$product_link = $data_product['product_url'];
				
			
	    	$reviews[$i]['product_link'] = $product_link;
	    	
	    	$picture = $data_product['image_link'];
	    	
	    	$reviews[$i]['product_img'] = $picture; 
	    	$reviews[$i]['product_name'] = $name_page;


            ## criterions ##
            $id_review = $reviews[$i]['id'];
            $id_lang_product_review = $reviews[$i]['id_lang'];
            $id_shop = $reviews[$i]['id_shop'];
            $data_criterions = $this->getCriterionsByProductReview(array('id_review'=>$id_review,'id_lang'=>$id_lang_product_review,'id_shop'=>$id_shop));
            $reviews[$i]['criterions'] = $data_criterions;
            ## criterions ##

            $data_files = $this->getFiles2Review(array('id_review'=>$id_review));
            $reviews[$i]['files'] = $data_files;

            ## helpfull ##
            $data_helpfull = $this->getHelpfullVotes(array('review_id'=>$id_review));
            $yes = $data_helpfull['yes'];
            $all = $data_helpfull['all'];
            $reviews[$i]['helpfull_yes'] = $yes;
            $reviews[$i]['helpfull_all'] = $all;
            ## helpfull ##

            $criterions_old= array();
            if($reviews[$i]['rating_old']) {
                $rating_old = $reviews[$i]['rating_old'];
                $criterions_old = unserialize($rating_old);
            }
            $reviews[$i]['criterions_old'] = $criterions_old;


            $id_lang = ($_item['id_lang'] != 0)?$_item['id_lang']:(int)($cookie->id_lang);

            $name_lang = Language::getLanguage((int)($id_lang));
            $reviews[$i]['name_lang'] = $name_lang['name'];




            $data_seo_url = $this->getSEOURLs(array('id_lang'=>$id_lang_product_review,'id_shop'=>$id_shop));

            $rev_url = $data_seo_url['rev_url'];




            $reviews[$i]['review_url'] = $rev_url.((Configuration::get('PS_REWRITING_SETTINGS') || version_compare(_PS_VERSION_, '1.5', '<'))?'?':'&').'rid='.$id_review;

            $url_to_customer  = '';
            $id_customer = isset($_item['id_customer'])?$_item['id_customer']:null;
            $reviews[$i]['id_customer'] = $id_customer;
            if($id_customer) {
                $token = isset($_data['token'])?$_data['token']:'';
                $url_to_customer = 'index.php?'.(version_compare(_PS_VERSION_, '1.5', '>')?'controller':'tab').'=AdminCustomers&id_customer=' . $id_customer . '&updatecustomer&token=' .$token . '';
                $customer_data = $this->getInfoAboutCustomer(array('id_customer'=>$id_customer,'is_full'=>1));
                $name = $customer_data['customer_name'];
                $reviews[$i]['customer_name_full'] = $name;
            }
            $reviews[$i]['url_to_customer'] = $url_to_customer;

            $reviews[$i]['suggest_text']  = Configuration::get($this->_name.'textresem_'.$id_lang);


            $ip = $_item['ip'];
            if(!empty($ip)) {
                $ip_orig = $_item['ip'];
                $ip = $this->getCityandCountry(array('ip' => $ip));

                if($ip == $ip_orig){
                    $reviews[$i]['ip'] = $ip;
                } else {
                    $reviews[$i]['ip'] = $ip_orig . ' (' . $ip . ')';
                }
            } else {

            }



	    $i++;
		}
			
	   return array('reviews' => $reviews);
	}
	
	public function checkProductBought($data)
	{
		$id_customer = $data['id_customer'];
		$id_product = $data['id_product'];
		if(version_compare(_PS_VERSION_, '1.5', '>')){
		$sql = 'SELECT count(o.id_order) as count FROM ' . _DB_PREFIX_ .'orders as o 
					   LEFT JOIN ' . _DB_PREFIX_ . 'order_detail as od ON(o.id_order = od.id_order)
					   WHERE o.id_customer = ' . (int)($id_customer) . ' AND od.product_id = ' . (int)($id_product).'
					   AND o.id_shop = '.(int)($this->getIdShop()).' AND od.id_shop = '.(int)($this->getIdShop());
		} else {
			$sql = 'SELECT count(o.id_order) as count FROM ' . _DB_PREFIX_ .'orders as o 
					   LEFT JOIN ' . _DB_PREFIX_ . 'order_detail as od ON(o.id_order = od.id_order)
					   WHERE o.id_customer = ' . (int)($id_customer) . ' AND od.product_id = ' . (int)($id_product).'
					  ';
		}
		$result = Db::getInstance()->ExecuteS($sql);
		return (!empty($result[0]['count'])? 1 : 0);
	}
	
	public function checkIsUserAlreadyAddReview($data){
		$id_customer = $data['id_customer'];
		$id_product = $data['id_product'];

        $sql_condition = $this->getConditionMultilanguageAndMultiStore(array('and'=>1));
		
		if($id_customer == 0){
			// guest
			$sql = 'select count(*) as count from `'._DB_PREFIX_.'gsnipreview` where
				id_product = '.(int)($id_product).' '.$sql_condition.' AND
				ip = \''.pSQL($_SERVER['REMOTE_ADDR']).'\'';
			
		}  else {
		
		$sql = 'select count(*) as count from `'._DB_PREFIX_.'gsnipreview` where
				id_product = '.(int)($id_product).' and id_customer = '.(int)($id_customer).' '.$sql_condition.'';
		}
		$result = Db::getInstance()->ExecuteS($sql);

		return (!empty($result[0]['count'])? 1 : 0);
	}


	
	public function getIdShop(){
		$id_shop = 0;
		if(version_compare(_PS_VERSION_, '1.5', '>'))
			$id_shop = Context::getContext()->shop->id;
		return $id_shop;
	}



    public function getInfoAboutCustomer($data=null){
		$id_customer = (int) $data['id_customer'];
        $is_full = isset($data['is_full'])?$data['is_full']:0;
		//get info about customer
		if(version_compare(_PS_VERSION_, '1.5', '>')){
		$sql = '
	        	SELECT * FROM `'._DB_PREFIX_.'customer`
		        WHERE `active` = 1 AND `id_customer` = \''.(int)($id_customer).'\'
		        AND `deleted` = 0 AND id_shop = '.(int)($this->getIdShop()).'  '.(defined(_MYSQL_ENGINE_)?"AND `is_guest` = 0":"").'
		        ';
		} else {
		$sql = '
	        	SELECT * FROM `'._DB_PREFIX_.'customer`
		        WHERE `active` = 1 AND `id_customer` = \''.(int)($id_customer).'\'
		        AND `deleted` = 0 '.(defined(_MYSQL_ENGINE_)?"AND `is_guest` = 0":"").'
		        ';
		}
		$result = Db::getInstance()->GetRow($sql);
        //var_dumP($result);exit;
		$email = '';
		if($result){
            if(!$is_full)
		        $lastname = Tools::strtoupper(Tools::substr($result['lastname'],0,1));
            else
                $lastname = $result['lastname'];

		    $firstname = $result['firstname'];
		    $customer_name = $firstname . " " . $lastname;
		    $email = $result['email'];

            $id_gender = $result['id_gender'];


		} else {
			$customer_name = "Guest";
            $id_gender = 0;
		}

		return array('customer_name' => $customer_name,'email'=>$email,'id_gender'=>$id_gender);
	}
	
	public function publish($data){
		$id = $data['id'];
		
		$sql = 'UPDATE `'._DB_PREFIX_.'gsnipreview` 
	    							SET
						   			is_active = 1
						   			WHERE id = '.(int)($id).' 
						   ';
		Db::getInstance()->Execute($sql);
		
		// send email notification
		
		
		### posts API ###
		$data_item = $this->getItem(array('id'=>$id));
		
		$product_name = $data_item['reviews'][0]['product_name'];
		$product_link = $data_item['reviews'][0]['product_link'];
		$picture = $data_item['reviews'][0]['product_img'];
		$rating = $data_item['reviews'][0]['rating'];
		$customer_name = $data_item['reviews'][0]['customer_name'];
        $id_product = $data_item['reviews'][0]['id_product'];
			 
		
    	include_once(dirname(__FILE__).'/postshelp.class.php');
		$postshelp = new postshelp();
		$cookie = $this->context->cookie;
		$id_lang = (int)($cookie->id_lang);
		$data_api = array(
							  		'name'=>$this->_name,
							  		'customer_name' => $customer_name,
							  		'product_name'=>$product_name,
							  		'product_link'=>$product_link,
							  		'rating' => $rating,
							  		'image' => $picture,
							  		'id_lang'=>$id_lang,
							  		);	
		$postshelp->postToAPI(
							  $data_api
							  );
		### posts API	


        if (!empty($data_item['reviews'][0]['id_customer']))
            $customer_name = $data_item['reviews'][0]['customer_name_full'];

        $title = $data_item['reviews'][0]['title_review'];
        $text_review = $data_item['reviews'][0]['text_review'];
        $email = $data_item['reviews'][0]['email'];
        if(!$email) {
            $id_customer = (int)$data_item['reviews'][0]['id_customer'];
            $customer_data = $this->getInfoAboutCustomer(array('id_customer'=>$id_customer));
            $email = $customer_data['email'];
        }
		
		$this->sendNotificationPublish(
                                        array(
                                            'id_product'=>$id_product,
                                            'customer_name' => $customer_name,
                                            'product_name'=>$product_name,
                                            'product_link'=>$product_link,
                                            'title' => $title,
                                            'text_review' => $text_review,
                                            'rating' => $rating,
                                            'email' => $email,
                                            )
                                     );
        $this->_clearSmartyCache();
	}


    public function sendNotificationPublish($data){


        if(Configuration::get($this->_name.'noti') == 1) {

            $cookie = $this->context->cookie;
            $id_lang = (int)($cookie->id_lang);

            $customer_name = $data['customer_name'];
            $product_name = $data['product_name'];
            $product_link = $data['product_link'];
            $title = $data['title'];
            $text_review = $data['text_review'];
            $rating = $data['rating'];
            $email = $data['email'];

            ### product data ###
            $id_product = $data['id_product'];
            $product_obj = new Product($id_product);
            $data_product = $this->_productData(array('product' => $product_obj, 'email' => 1));
            $picture = $data_product['image_link'];
            ### product data ###




            $iso_lng = Language::getIsoById((int)($id_lang));

            $dir_mails = _PS_MODULE_DIR_ . '/' . $this->_name . '/' . 'mails/';

            if (is_dir($dir_mails . $iso_lng . '/')) {
                $id_lang_current = $id_lang;
            } else {
                $id_lang_current = Language::getIdByIso('en');
            }

            /* Email generation */
            $templateVars = array(
                '{customer_name}' => $customer_name,
                '{product_name}' => $product_name,
                '{product_link}' => $product_link,
                '{title}' => $title,
                '{review}' => $text_review,
                '{rating}' => $rating,
                '{publish_text}' => Configuration::get($this->_name.'subpubem_'.$id_lang_current),

                '{picture}' =>$picture,
            );

            /* Email sending */


            if($email) {
                Mail::Send($id_lang_current, 'reviewserg-publish', Configuration::get($this->_name . 'subpubem_' . $id_lang_current), $templateVars,
                    $email, 'Publish Review Form', NULL, NULL,
                    NULL, NULL, dirname(__FILE__) . '/../mails/');
            }

        }
    }


    public function unpublish($data){
		$id = $data['id'];
		$sql = 'UPDATE `'._DB_PREFIX_.'gsnipreview` 
	    							SET
						   			is_active = 0
						   			WHERE id = '.(int)($id).' 
						   ';
		Db::getInstance()->Execute($sql);

        $this->_clearSmartyCache();
	}
	
	public function delete($data){
			$id = $data['id'];
			$sql = 'delete FROM `'._DB_PREFIX_.'gsnipreview` 
	    							WHERE id = '.(int)($id).' 
						   ';
	    	Db::getInstance()->Execute($sql);

            $sql = 'delete FROM `'._DB_PREFIX_.'gsnipreview_review2criterion`
                                                    WHERE id_review = '.(int)($id).'
                                           ';
            Db::getInstance()->Execute($sql);

            $sql = 'delete FROM `'._DB_PREFIX_.'gsnipreview_review_abuse`
                                        WHERE review_id = '.(int)($id).'
                               ';
            Db::getInstance()->Execute($sql);

            $sql = 'delete FROM `'._DB_PREFIX_.'gsnipreview_review_helpfull`
                                            WHERE review_id = '.(int)($id).'
                                   ';
            Db::getInstance()->Execute($sql);

            $sql = 'delete FROM `'._DB_PREFIX_.'gsnipreview_socialshare`
                                                WHERE id_review = '.(int)($id).'
                                       ';
            Db::getInstance()->Execute($sql);

        $this->_clearSmartyCache();
	}


    public function saveReviewAdmin($data){
        $id_product = $data['id_product'];
        $id_customer = $data['id_customer'];

        $customer_data = $this->getInfoAboutCustomer(array('id_customer'=>$id_customer));
        $customer_name = $customer_data['customer_name'];
        $email = $customer_data['email'];

        $title = $data['title'];
        $text_review = $data['text_review'];

        ## rating ##
        $ratings = $data['ratings'];
        $sizeof_rating = sizeof($ratings);
        $rating = 0;
        foreach($ratings as $rating_value){
            $rating = $rating + $rating_value;
        }
        $rating = round($rating/$sizeof_rating);
        ## rating ##

        $is_active = $data['is_active'];

        $id_lang = $data['id_lang'];
        $id_shop = $data['id_shop'];
        $time_add = $data['time_add'];

        //insert review
        $sql = 'INSERT into `'._DB_PREFIX_.'gsnipreview` SET
						   id_product = '.(int)($id_product).',
						   id_customer = '.(int)($id_customer).',
						   title_review = \''.pSQL($title).'\',
						   text_review = \''.pSQL($text_review).'\',
						   customer_name = \''.pSQL($customer_name).'\',
						   email = \''.pSQL($email).'\',
						   rating = '.pSQL($rating).',
						   ip = \''.pSQL($_SERVER['REMOTE_ADDR']).'\',
						   id_lang = \''.(int)($id_lang).'\',
						   id_shop = \''.(int)($id_shop).'\',
						   is_active = '.(int)($is_active).',
						   time_add = "'.pSQL($time_add).'"
						   ';
        Db::getInstance()->Execute($sql);


        $id_review = Db::getInstance()->Insert_ID();

        ### add criterions ###
        foreach($ratings as $id_criterion => $rating_value) {
            if($id_criterion > 0) {
                $sql = 'INSERT into `' . _DB_PREFIX_ . 'gsnipreview_review2criterion` SET
						   id_review = ' . (int)($id_review) . ',
						   id_criterion = ' . (int)($id_criterion) . ',
						   rating = '.(int)$rating_value.'
						   ';
                Db::getInstance()->Execute($sql);
            }
        }
        ### add criterions ###


        $this->_clearSmartyCache();

    }
	
	public function saveReview($data){
		$id_product = $data['id_product'];
		$id_customer = $data['id_customer'];

        $post_images = $data['post_images'];
        $filesrev = $data['filesrev'];
		
		$customer_name = isset($data['name'])?$data['name']:'';
        $email = isset($data['email'])?$data['email']:'';
		if(Tools::strlen($customer_name)==0 && Tools::strlen($email)==0){
		    $customer_data = $this->getInfoAboutCustomer(array('id_customer'=>$id_customer));
		    $customer_name = $customer_data['customer_name'];
            $email = $customer_data['email'];
		} else {
            $customer_data = array();
            $customer_data['email'] = $email;
        }
		
		$title = $data['title'];
		$text_review = $data['text_review'];

        ## rating ##
		$ratings = $data['ratings'];
        $sizeof_rating = sizeof($ratings);
        $rating = 0;
        foreach($ratings as $rating_value){
            $rating = $rating + $rating_value;
        }
		$rating = round($rating/$sizeof_rating);
        ## rating ##


        if(Configuration::get($this->_name.'is_approval')){
			$is_active = 0;
		} else {
			$is_active = 1;
		}

        $id_lang = $data['id_lang'];

		//insert review
		$sql = 'INSERT into `'._DB_PREFIX_.'gsnipreview` SET
						   id_product = '.(int)($id_product).', 
						   id_customer = '.(int)($id_customer).',
						   title_review = \''.pSQL($title).'\',
						   text_review = \''.pSQL($text_review).'\',
						   customer_name = \''.pSQL($customer_name).'\',
						   email = \''.pSQL($email).'\',
						   rating = '.pSQL($rating).',
						   ip = \''.pSQL($_SERVER['REMOTE_ADDR']).'\',
						   id_lang = \''.(int)($id_lang).'\',
						   id_shop = \''.(int)($this->getIdShop()).'\',
						   is_active = '.(int)($is_active).',
						    time_add = "'.pSQL(date("Y-m-d H:i:s")).'"
						   ';
        Db::getInstance()->Execute($sql);


        $id_review = Db::getInstance()->Insert_ID();

        $this->saveFiles2Review(array('id_review'=>$id_review,'id_product'=>$id_product,'filesrev'=>$filesrev,'id_lang'=>$id_lang));


        $this->saveImageAvatar(array('id'=>$id_review,'id_customer'=>$id_customer,'post_images'=>$post_images));

        ### add criterions ###
        foreach($ratings as $id_criterion => $rating_value) {
            if($id_criterion > 0) {
                $sql = 'INSERT into `' . _DB_PREFIX_ . 'gsnipreview_review2criterion` SET
						   id_review = ' . (int)($id_review) . ',
						   id_criterion = ' . (int)($id_criterion) . ',
						   rating = '.(int)$rating_value.'
						   ';
                Db::getInstance()->Execute($sql);
            }
        }
        ### add criterions ###


        ### get product info ####
		$cookie = $this->context->cookie;
		$id_lang = (int)($cookie->id_lang);	
		$product_obj = new Product($id_product);
			
		$product_name = $product_obj->name[$id_lang];
		
		$data_product = $this->_productData(array('product'=>$product_obj,'email'=>1));
		
	    $product_link = $data_product['product_url'];
	    $picture = $data_product['image_link'];
	    	
	    #### get product info ####
	    
		$data_send_notifications = array(	
										 'customer_name'=>$customer_name,
										 'title' => $title,
										 'text_review' => $text_review,
										 'product_name' => $product_name,
										 'product_link' => $product_link,
										 'rating' => $rating,
                                         'picture' => $picture,
										 );
		$this->sendNotificationAddReview($data_send_notifications);



        ## send thank you email by customer ##
        $data_thank_you = array('name'=>$customer_name,'email'=>$email,'id_lang'=>$id_lang);
        $this->sendNotificationThankyouReview($data_thank_you);
        ## send thank you email by customer ##


        ### posts API ###
        include_once(dirname(__FILE__).'/postshelp.class.php');
        $postshelp = new postshelp();

        $data_product_post = $this->_productData(array('product'=>$product_obj));

        $product_link_post = $data_product_post['product_url'];
        $picture_post = $data_product_post['image_link'];

        $postshelp->postToAPI(
            array(
                'name'=>$this->_name,
                'customer_name' => $customer_name,
                'product_name'=>$product_name,
                'product_link'=>$product_link_post,
                'rating' => $rating,
                'image' => $picture_post,
                'id_lang'=>$id_lang,
            )
        );
        ### posts API

		$data_voucher = array();;
		if(Configuration::get($this->_name.'vis_on') == 1){			  
    		$data_voucher = $this->createVoucher(array('customer_id'=>(int)$id_customer));




    			$this->sendNotificationCreatedVoucher(
    													array(
    														  'email_customer'=>$customer_data['email'],
                                                              'data_voucher'=>$data_voucher,
                                                              'id_review' => $id_review,
                                                              )
    												  );
    												  
    	}

        if(Configuration::get($this->_name.'vis_onfb') == 1) {
            $this->sendNotificationVoucherFacebookSuggest(
                                                          array(
                                                                'email_customer'=>$customer_data['email'],
                                                                'id_review' => $id_review,
                                                            )
                                                        );
        }

        $this->_clearSmartyCache();
    	return $data_voucher;
    		
	}


    public function sendNotificationCreatedVoucher($data = null){

        if(Configuration::get($this->_name.'noti') == 1) {
            include_once(dirname(__FILE__) . '/../gsnipreview.php');
            $obj = new gsnipreview();
            $data_translate = $obj->translateCustom();


            $firsttext = $data_translate['firsttext'];

            $tax = $data_translate['tax'];
            if($tax){
                $tax_text = ' ('.$data_translate['tax_included'].') ';
            } else {
                $tax_text = ' ('.$data_translate['tax_excluded'].') ';
            }


            $secondtext = $data_translate['secondtext'];
            $threetext = $data_translate['threetext'];


            $is_facebook = isset($data['is_facebook']) ? $data['is_facebook'] : 0;
            if ($is_facebook) {
                $valuta = $data_translate['valutafb'];
                if($valuta == "%"){
                    $tax_text = "";
                }

                $discountvalue = $data_translate['discountvaluefb'].$tax_text;


                $text_voucher_title = $data_translate['facebook_text_voucher'];
            } else {
                $valuta = $data_translate['valuta'];
                if($valuta == "%"){
                    $tax_text = "";
                }

                $discountvalue = $data_translate['discountvalue'].$tax_text;


                $text_voucher_title = $data_translate['review_text_voucher'];
            }

            $email_customer = $data['email_customer'];
            $voucher_code = $data['data_voucher']['voucher_code'];
            $date_until = $data['data_voucher']['date_until'];

            $id_review = $data['id_review'];
            $review = $this->getItem(array('id' => $id_review));

            $review_data = $review['reviews'][0];

            if (!empty($review_data['id_customer']))
                $customer_name = $review_data['customer_name_full'];
            else
                $customer_name = $review_data['customer_name'];

            $rev_url = $review_data['review_url'];


            $cookie = $this->context->cookie;
            $id_lang = (int)($cookie->id_lang);

            ### product data ###
            $id_product = $review_data['id_product'];

            $product_obj = new Product($id_product);

            $product_name = $product_obj->name[$id_lang];

            $data_product = $this->_productData(array('product' => $product_obj, 'email' => 1));

            $picture = $data_product['image_link'];
            ### product data ###


            /* Email generation */
            $templateVars = array(
                '{firsttext}' => $firsttext,
                '{discountvalue}' => $discountvalue,
                '{secondtext}' => $secondtext,
                '{threetext}' => $threetext,
                '{voucher_code}' => $voucher_code,
                '{date_until}' => $date_until,
                '{picture}' => $picture,
                '{product_name}' => $product_name,
                '{customer_name}' => $customer_name,
                '{rev_url}' => $rev_url,
                '{text_voucher_title}' => $text_voucher_title,
            );


            $iso_lng = Language::getIsoById((int)($id_lang));

            $dir_mails = _PS_MODULE_DIR_ . '/' . $this->_name . '/' . 'mails/';

            if (is_dir($dir_mails . $iso_lng . '/')) {
                $id_lang_current = $id_lang;
            } else {
                $id_lang_current = Language::getIdByIso('en');
            }

            $subject_facebook_voucher = Configuration::get($this->_name . 'facvoucr_' . $id_lang);
            $subject_review_voucher = Configuration::get($this->_name . 'revvoucr_' . $id_lang);

            /* Email sending */
            Mail::Send($id_lang_current, 'voucherserg', (($is_facebook) ? $subject_facebook_voucher : $subject_review_voucher), $templateVars,
                $email_customer, 'Voucher Form', NULL, NULL,
                NULL, NULL, dirname(__FILE__) . '/../mails/');
        }

    }


    public function sendNotificationVoucherFacebookSuggest($data = null){

        if(Configuration::get($this->_name.'noti') == 1) {
            include_once(dirname(__FILE__) . '/../gsnipreview.php');
            $obj = new gsnipreview();
            $data_translate = $obj->translateCustom();

            $email_customer = $data['email_customer'];

            ### review data ###
            $id_review = $data['id_review'];
            $review = $this->getItem(array('id' => $id_review));

            $review_data = $review['reviews'][0];



            if (!empty($review_data['id_customer']))
                $customer_name = $review_data['customer_name_full'];
            else
                $customer_name = $review_data['customer_name'];

            $rev_url = $review_data['review_url'];
            ### review data ###


            $cookie = $this->context->cookie;
            $id_lang = (int)($cookie->id_lang);

            ### product data ###
            $id_product = $review_data['id_product'];

            $product_obj = new Product($id_product);

            $product_name = $product_obj->name[$id_lang];

            $data_product = $this->_productData(array('product' => $product_obj, 'email' => 1));

            $picture = $data_product['image_link'];
            ### product data ###


            ### voucher data ###
            $discountvalue = $data_translate['discountvaluefb'];
            $date_valid = Configuration::get($this->_name . 'sdvvalidfb') . ' ' .
                            $this->number_ending(Configuration::get($this->_name . 'sdvvalidfb'), $data_translate['valid_days'], $data_translate['valid_day'], $data_translate['valid_days']);
            $taxfb = $data_translate['taxfb'];
            if($taxfb){
                $tax_text = ' ('.$data_translate['tax_included'].') ';
            } else {
                $tax_text = ' ('.$data_translate['tax_excluded'].') ';
            }

            $valutafb = $data_translate['valutafb'];
            if($valutafb == "%"){
                $tax_text = "";
            }

            ### voucher data ###

            /* Email generation */
            $templateVars = array(
                '{discountvalue}' => $discountvalue.$tax_text,
                '{date_valid}' => $date_valid,
                '{picture}' => $picture,
                '{product_name}' => $product_name,
                '{customer_name}' => $customer_name,
                '{rev_url}' => $rev_url,
            );


            $iso_lng = Language::getIsoById((int)($id_lang));

            $dir_mails = _PS_MODULE_DIR_ . '/' . $this->_name . '/' . 'mails/';

            if (is_dir($dir_mails . $iso_lng . '/')) {
                $id_lang_current = $id_lang;
            } else {
                $id_lang_current = Language::getIdByIso('en');
            }

            $subject_suggest_voucher = Configuration::get($this->_name . 'sugvoucr_' . $id_lang);
            /* Email sending */
            Mail::Send($id_lang_current, 'voucherserg-suggest', $subject_suggest_voucher, $templateVars,
                $email_customer, 'Voucher suggest Form', NULL, NULL,
                NULL, NULL, dirname(__FILE__) . '/../mails/');

        }
    }
	
	public function addProductRule($cart_rule_id, $qty, $type, array $ids)
	{
		$insert = false;

		// set transaction
		Db::getInstance()->Execute('BEGIN');

		$sql = 'INSERT INTO ' . _DB_PREFIX_ . 'cart_rule_product_rule_group (id_cart_rule, quantity) VALUES('
			. (int)($cart_rule_id) . ', ' . (int)($qty) . ')';

		// only if group rule is added
		if (Db::getInstance()->Execute($sql)) {

			$sql = 'INSERT INTO ' . _DB_PREFIX_ . 'cart_rule_product_rule (id_product_rule_group, type) VALUES('
				. (int)(Db::getInstance()->Insert_ID()) . ', "' . pSQL($type) . '")';

			// only if product rule is added
			if (Db::getInstance()->Execute($sql)) {

				if (!empty($ids)) {
					$insert = true;

					$iLastInsertId = Db::getInstance()->Insert_ID();

					foreach ($ids as $id) {
						$sql = 'INSERT INTO ' . _DB_PREFIX_ . 'cart_rule_product_rule_value (id_product_rule, id_item) VALUES('
							. (int)($iLastInsertId) . ', ' . (int)($id) . ')';

						if (!Db::getInstance()->Execute($sql)) {
							$insert = false;
						}
					}
				}
			}
		}
		// commit or rollback transaction
		$insert = ($insert)? Db::getInstance()->Execute('COMMIT') : Db::getInstance()->Execute('ROLLBACK');

		return $insert;
	}
    
	private function idGuest(){
		$cookie = $this->context->cookie;
		
		
    	$id_guest = (int)$cookie->id_guest;
    	return $id_guest;
	}
	
	public function createVoucher($data){
    	
		$cookie = $this->context->cookie;
		$name_module = $this->_name;
    	$code_module = Configuration::get($name_module.'vouchercode');
    	
    	if(!$data['customer_id']){
    	$id_guest = $this->idGuest();
    	
    	// id_customer
    	$sql_customer = 'SELECT id_customer FROM '._DB_PREFIX_.'guest WHERE id_guest='.(int)($id_guest);
		$uid = (int)Db::getInstance()->getValue($sql_customer);		
		} else {
			$uid = $data['customer_id'];
		}
		
	
    	
    	Db::getInstance()->Execute('BEGIN');
    	
    	$code_v = '';
    	$different = strtotime(date('Y-m-d H:i:s'));
    		
    	$id_currency = null;
    	switch (Configuration::get($this->_name.'discount_type'))
			{
				case 1:
					// percent
					$id_discount_type = 1;
					$id_currency = (int)$cookie->id_currency;
					$value = Configuration::get($this->_name.'percentage_val');
					break;
				case 2:
					// currency
					$id_discount_type = 2;
					$id_currency = (int)$cookie->id_currency;
					$value = Configuration::get('sdamount_'.(int)$id_currency);
				break;
				default:
					$id_discount_type = 2;
					$id_currency = (int)$cookie->id_currency;
					$value = Configuration::get('sdamount_'.(int)$id_currency);
			}
			
			
			
			
			$current_language = (int)$cookie->id_lang;
			
	    	$coupon = (version_compare(_PS_VERSION_, '1.5.0') != -1)? new CartRule() : new Discount();
    		
	    	$gen_pass = Tools::strtoupper(Tools::passwdGen(8));
	    	
	    	if(version_compare(_PS_VERSION_, '1.5', '>')){
		       	foreach (Language::getLanguages() AS $language){
		       		$coupon->name[(int)$language['id_lang']] = $code_module.'-'.$gen_pass;
		       	}
		       	$coupon->description = Configuration::get($name_module.'coupondesc_'.$current_language);
		       	
	    	} else {
	    		
	    		foreach (Language::getLanguages() AS $language){
	    			$coupon->description[(int)$language['id_lang']] = Configuration::get($name_module.'coupondesc_'.(int)$language['id_lang']);
	    		}
	    	}
	    	
	    	$codename = $code_module.'-'.$gen_pass;
	    	$category = explode(",",Configuration::get($name_module.'catbox'));
    		
	    	if (version_compare(_PS_VERSION_, '1.5', '>')) {
				$coupon->code = $codename;
				$type = $id_discount_type == 2? 'reduction_amount' : 'reduction_percent';

				$coupon->$type = ($value);

				$coupon->reduction_currency = (int)($id_currency);
				if(Configuration::get($name_module.'isminamount') == true || 
				   Configuration::get($name_module.'isminamount') == 1){
					$coupon->minimum_amount = (int)(Configuration::get('sdminamount_'.(int)$id_currency));
					$coupon->minimum_amount_currency = (int)($id_currency);
                    $coupon->minimum_amount_tax= (int)Configuration::get($name_module.'tax');
				}
				
				if($id_discount_type == 2)
					$coupon->reduction_tax = (int)Configuration::get($name_module.'tax');


				if (sizeof($category)>0) {
						$coupon->product_restriction = 1;
						
						if($id_discount_type == 1){
							$coupon->reduction_product = -2;
						}
				}
					
					
			} else {
					$coupon->name = $codename;
					$coupon->id_discount_type = $id_discount_type == 2? 2 : 1;

					if (version_compare(_PS_VERSION_ , '1.3.0.4') != -1) {
						$coupon->id_currency = (int)($id_currency);
					}
					
					$coupon->cart_display = 0;
					
					// fo ps 1.3 - 1.4
		    		if(Configuration::get($name_module.'isminamount') == true || 
		    		   Configuration::get($name_module.'isminamount') == 1){
		    		   		if(!$id_currency) $id_currency = 1;
						
							$coupon->minimal = Configuration::get('sdminamount_'.(int)$id_currency);
					}
				}
			
	    	
			// shared data
			$coupon->value = ($value);
			$coupon->id_customer = $uid;
			$coupon->quantity = 1;
			$coupon->quantity_per_user = 1;
			
			// cumulable
			// for ps 1.5.6.0 
			if (version_compare(_PS_VERSION_, '1.5', '>')) 
	        	$coupon->cart_rule_restriction = ((Configuration::get($name_module.'cumulativeother'))==0?1:0);
	        	 
			$coupon->cumulable = (int)(Configuration::get($name_module.'cumulativeother'));

            $coupon->highlight = (int)(Configuration::get($name_module.'highlight'));
			
			$coupon->cumulable_reduction = (int)(Configuration::get($name_module.'cumulativereduc'));
			// cumulable
			
			
			$coupon->active = 1;
			
			$start_date = date('Y-m-d H:i:s');
			$coupon->date_from = $start_date;
				
			$different = strtotime(date('Y-m-d H:i:s')) + Configuration::get($this->_name.'sdvvalid')*24*60*60;
			$end_date = date('Y-m-d H:i:s',$different);
			$coupon->date_to = $end_date;
			
			
			$is_voucher_create = false;
	        if (version_compare(_PS_VERSION_, '1.5', '>')) {
	        	
	        	$is_voucher_create = $coupon->add(true, false);
	        	
	        	if ($is_voucher_create && sizeof($category)>0) 
	        	{
	        		// add a cart rule
					$is_voucher_create = $this->addProductRule($coupon->id, 1, 'categories', $category);
				}
	        } else {
	        	// create voucher and add a cart rule (if exists)
	        	$is_voucher_create = $coupon->add(true, false, (sizeof($category)>0?$category:null));
	        }
	        
			
			if (!$is_voucher_create){
			    Db::getInstance()->Execute('ROLLBACK');
			}
	         
	         $code_v = $codename;
			 
    		
	         
    		Db::getInstance()->Execute('COMMIT');
    		
	      
		
	        return array('voucher_code'=>$code_v,'date_until' => date('d/m/Y H:i:s',$different));
    }


    public function createVoucherSocialShare($data){

        $cookie = $this->context->cookie;
        $name_module = $this->_name;
        $code_module = Configuration::get($name_module.'vouchercodefb');
        $rid = $data['rid'];


        $type_coupon = 1; // facebook
        $is_exists_voucher_for_customer = 1;
        $is_expiried_voucher = 0;
        $is_used_voucher = 0;
        $data_exists_voucher_for_customer = $this->isExistsVoucherForCustomer(array('type'=>$type_coupon));


        if(!$data_exists_voucher_for_customer['is_exist']) {

            $is_exists_voucher_for_customer = 0;

            $id_customer = (int)$cookie->id_customer;

            if (!$id_customer) {
                $id_guest = $this->idGuest();

                // id_customer
                $sql_customer = 'SELECT id_customer FROM ' . _DB_PREFIX_ . 'guest WHERE id_guest=' . (int)($id_guest);
                $uid = (int)Db::getInstance()->getValue($sql_customer);
            } else {
                $uid = $id_customer;
            }


            Db::getInstance()->Execute('BEGIN');

            $code_v = '';
            $different = strtotime(date('Y-m-d H:i:s'));

            $id_currency = null;
            switch (Configuration::get($this->_name . 'discount_typefb')) {
                case 1:
                    // percent
                    $id_discount_type = 1;
                    $id_currency = (int)$cookie->id_currency;
                    $value = Configuration::get($this->_name . 'percentage_valfb');
                    break;
                case 2:
                    // currency
                    $id_discount_type = 2;
                    $id_currency = (int)$cookie->id_currency;
                    $value = Configuration::get('sdamountfb_' . (int)$id_currency);
                    break;
                default:
                    $id_discount_type = 2;
                    $id_currency = (int)$cookie->id_currency;
                    $value = Configuration::get('sdamountfb_' . (int)$id_currency);
            }


            $current_language = (int)$cookie->id_lang;

            $coupon = (version_compare(_PS_VERSION_, '1.5.0') != -1) ? new CartRule() : new Discount();

            $gen_pass = Tools::strtoupper(Tools::passwdGen(8));

            if (version_compare(_PS_VERSION_, '1.5', '>')) {
                foreach (Language::getLanguages() AS $language) {
                    $coupon->name[(int)$language['id_lang']] = $code_module . '-' . $gen_pass;
                }
                $coupon->description = Configuration::get($name_module . 'coupondescfb_' . $current_language);

            } else {

                foreach (Language::getLanguages() AS $language) {
                    $coupon->description[(int)$language['id_lang']] = Configuration::get($name_module . 'coupondescfb_' . (int)$language['id_lang']);
                }
            }

            $codename = $code_module . '-' . $gen_pass;
            $category = explode(",", Configuration::get($name_module . 'catboxfb'));

            if (version_compare(_PS_VERSION_, '1.5', '>')) {
                $coupon->code = $codename;
                $type = $id_discount_type == 2 ? 'reduction_amount' : 'reduction_percent';

                $coupon->$type = ($value);

                $coupon->reduction_currency = (int)($id_currency);
                if (Configuration::get($name_module . 'isminamountfb') == true ||
                    Configuration::get($name_module . 'isminamountfb') == 1
                ) {
                    $coupon->minimum_amount = (int)(Configuration::get('sdminamountfb_' . (int)$id_currency));
                    $coupon->minimum_amount_currency = (int)($id_currency);
                    $coupon->minimum_amount_tax= (int)Configuration::get($name_module.'taxfb');
                }

                if ($id_discount_type == 2)
                    $coupon->reduction_tax = (int)Configuration::get($name_module . 'taxfb');


                if (sizeof($category) > 0) {
                    $coupon->product_restriction = 1;

                    if ($id_discount_type == 1) {
                        $coupon->reduction_product = -2;
                    }
                }


            } else {
                $coupon->name = $codename;
                $coupon->id_discount_type = $id_discount_type == 2 ? 2 : 1;

                if (version_compare(_PS_VERSION_, '1.3.0.4') != -1) {
                    $coupon->id_currency = (int)($id_currency);
                }

                $coupon->cart_display = 0;

                // fo ps 1.3 - 1.4
                if (Configuration::get($name_module . 'isminamountfb') == true ||
                    Configuration::get($name_module . 'isminamountfb') == 1
                ) {
                    if (!$id_currency) $id_currency = 1;

                    $coupon->minimal = Configuration::get('sdminamountfb_' . (int)$id_currency);
                }
            }


            // shared data
            $coupon->value = ($value);
            $coupon->id_customer = $uid;
            $coupon->quantity = 1;
            $coupon->quantity_per_user = 1;

            // cumulable
            // for ps 1.5.6.0
            if (version_compare(_PS_VERSION_, '1.5', '>'))
                $coupon->cart_rule_restriction = ((Configuration::get($name_module . 'cumulativeotherfb')) == 0 ? 1 : 0);

            $coupon->cumulable = (int)(Configuration::get($name_module . 'cumulativeotherfb'));

            $coupon->highlight = (int)(Configuration::get($name_module.'highlightfb'));

            $coupon->cumulable_reduction = (int)(Configuration::get($name_module . 'cumulativereducfb'));
            // cumulable


            $coupon->active = 1;

            $start_date = date('Y-m-d H:i:s');
            $coupon->date_from = $start_date;

            $different = strtotime(date('Y-m-d H:i:s')) + Configuration::get($this->_name . 'sdvvalidfb') * 24 * 60 * 60;
            $end_date = date('Y-m-d H:i:s', $different);
            $coupon->date_to = $end_date;


            $is_voucher_create = false;
            if (version_compare(_PS_VERSION_, '1.5', '>')) {

                $is_voucher_create = $coupon->add(true, false);

                if ($is_voucher_create && sizeof($category) > 0) {
                    // add a cart rule
                    $is_voucher_create = $this->addProductRule($coupon->id, 1, 'categories', $category);
                }
            } else {
                // create voucher and add a cart rule (if exists)
                $is_voucher_create = $coupon->add(true, false, (sizeof($category) > 0 ? $category : null));
            }


            if (!$is_voucher_create) {
                Db::getInstance()->Execute('ROLLBACK');
            }


            // insert into gsnipreview_socialshare
            $id_discount = $coupon->id;
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $id_guest = $data_exists_voucher_for_customer['id_guest'];
            $id_customer = $data_exists_voucher_for_customer['id_customer'];
            $sql = 'INSERT into `' . _DB_PREFIX_ . 'gsnipreview_socialshare` SET
							   `id_discount` = ' . (int)($id_discount) . ',
							   `ip_adress` = "' . pSQL($ip_address) . '",
							   `id_guest` = ' . (int)($id_guest) . ',
							   `id_customer` = ' . (int)($id_customer) . ',
							   `id_review` = ' . (int)($rid) . ',
							   `type` = ' . (int)($type_coupon) . '
							   ';
            Db::getInstance()->Execute($sql);
            // insert into gsnipreview_socialshare
        } else {
            $id_discount = $data_exists_voucher_for_customer['is_exist'];

            $data_expiried = $this->expiriedVoucher($id_discount);
            if($data_expiried['is_expiried'] == 1){
                $is_expiried_voucher = 1;
            }


            $data_used = $this->usedVoucher($id_discount);
            if ($data_used['is_used']) {
                    $is_used_voucher = 1;
             }

                if(version_compare(_PS_VERSION_, '1.5', '>')){
                    $discount = new CartRule($id_discount);
                } else {
                    $discount = new Discount($id_discount);
                }

                if(version_compare(_PS_VERSION_, '1.5', '>')){
                    $codename = $discount->code;
                } else {
                    $codename = $discount->name;
                }
                $different =  strtotime($discount->date_to);
            Db::getInstance()->Execute('ROLLBACK');
        }

        $code_v = $codename;



        Db::getInstance()->Execute('COMMIT');




        return array('voucher_code'=>$code_v,'date_until' => date('d/m/Y H:i:s',$different),'is_exists_voucher_for_customer' => $is_exists_voucher_for_customer,
                    'is_expiried_voucher' => $is_expiried_voucher, 'is_used_voucher' => $is_used_voucher);
    }

    public function isExistsVoucherForCustomer($data = null)
    {
        $type = $data['type'];

        $data_discount = $this->getDiscountData(array('type'=>$type));

        $is_exist = $data_discount['id_discount'];
        $id_guest = $data_discount['id_guest'];
        $id_customer = $data_discount['id_customer'];

        return array('is_exist'=>$is_exist,'id_guest'=>$id_guest,'id_customer'=>$id_customer);
    }

    private function getDiscountData($data = null){
        $cookie = $this->context->cookie;
        $type = $data['type'];
        $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;

        $id_guest = 0;
        if($id_customer){

            // if customer get discount as unregistered user

                $sql = 'SELECT id_discount
						FROM `' . _DB_PREFIX_ . 'gsnipreview_socialshare`
						WHERE `id_customer` = '.(int)$id_customer.' AND `type` = '.pSQL($type).' ';
                $id_discount = Db::getInstance()->getValue($sql);


        } else {
            $id_guest = $this->idGuest();

            // if customer get discount as unregistered user
            $sql = 'SELECT id_discount
					FROM `' . _DB_PREFIX_ . 'gsnipreview_socialshare`
					WHERE `id_guest` != 0
					AND `ip_adress` = "'.pSQL($_SERVER['REMOTE_ADDR']).'" AND `type` = '.(int)$type.'';
            $id_discount = Db::getInstance()->getValue($sql);

            if(!$id_discount){
                $sql = 'SELECT id_discount
						FROM `' . _DB_PREFIX_ . 'gsnipreview_socialshare`
						WHERE `id_guest` = '.(int)$id_guest.'
						AND `id_customer` = '.(int)$id_customer.'
						AND `ip_adress` = "'.pSQL($_SERVER['REMOTE_ADDR']).'" AND `type` = '.pSQL($type).' ';
                $id_discount = Db::getInstance()->getValue($sql);
            }
        }
        //echo $sql;
        return array('id_discount'=>(int)$id_discount,'id_guest'=>(int)$id_guest,'id_customer'=>(int)$id_customer);
    }

    public function isMyReview($data){
        $cookie = $this->context->cookie;
        $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;
        $rid = $data['id_review'];

        if($id_customer){
            $sql = 'SELECT id, email, id_product
						FROM `' . _DB_PREFIX_ . 'gsnipreview`
						WHERE `id_customer` = '.(int)$id_customer.' AND `id` = '.(int)$rid.' and id_shop = '.(int)($this->getIdShop()).'';
            $data = Db::getInstance()->ExecuteS($sql);
        } else {

            $sql = 'SELECT id, email, id_product
						FROM `' . _DB_PREFIX_ . 'gsnipreview`
						WHERE `id_customer` = 0
						AND `ip` = "'.pSQL($_SERVER['REMOTE_ADDR']).'" and id_shop = '.(int)($this->getIdShop()).' AND `id` = '.(int)$rid.'';
            $data = Db::getInstance()->ExecuteS($sql);
        }

        return $data;

    }


    public function expiriedVoucher($id_discount)
    {
        if(version_compare(_PS_VERSION_, '1.5', '>')){
            $discount = new CartRule($id_discount);
        } else {
            $discount = new Discount($id_discount);
        }


        $is_expiried = 1;
        $current_time = strtotime('now');
        if ($current_time >= strtotime($discount->date_from) && $current_time < strtotime($discount->date_to))
            $is_expiried = 0;

        if(version_compare(_PS_VERSION_, '1.5', '>')){
            $code_v = $discount->code;
        } else {
            $code_v = $discount->name;
        }
        $different =  strtotime($discount->date_to);

        return array('is_expiried'=>$is_expiried,'code_v'=>$code_v,'different'=>$different);
    }

    public function usedVoucher($id_discount)
    {
        if(version_compare(_PS_VERSION_, '1.5', '>')){
            $discount = new CartRule($id_discount);
        } else {
            $discount = new Discount($id_discount);
        }


        $is_used = 1;
        if($discount->quantity)
            $is_used = 0;

        if(version_compare(_PS_VERSION_, '1.5', '>')){
            $code_v = $discount->code;
        } else {
            $code_v = $discount->name;
        }
        $different =  strtotime($discount->date_to);

        return array('is_used'=>$is_used,'code_v'=>$code_v,'different'=>$different);
    }




    public function updateReview($data){


        $name = $data['name'];
        $email = $data['email'];
        $title_review = $data['title_review'];
        $text_review = $data['text_review'];
        $is_active = $data['is_active'];
		$id = $data['id'];
        $time_add = $data['time_add'];

        $post_images = $data['post_images'];

        $is_changed = isset($data['is_changed'])?$data['is_changed']:0;

        // get old data
        $data_item = $this->getItem(array('id'=>$id));



        $sql = 'DELETE FROM `'._DB_PREFIX_.'gsnipreview_review2criterion`
						WHERE id_review = '.(int)($id).'
						';
        Db::getInstance()->Execute($sql);


        ## rating ##
        $ratings = $data['ratings'];
        $sizeof_rating = sizeof($ratings);
        $rating = 0;
        foreach($ratings as $rating_value){
            $rating = $rating + $rating_value;
        }
        $rating_new = round($rating/$sizeof_rating);
        if($rating_new == 0){
            $rating_new = $data['rating_total'];
        }
        ## rating ##

        ## update new ratings ###
        foreach($ratings as $id_criterion => $rating_value) {
            if($id_criterion > 0) {
                $sql = 'INSERT into `' . _DB_PREFIX_ . 'gsnipreview_review2criterion` SET
						   id_review = ' . (int)($id) . ',
						   id_criterion = ' . (int)($id_criterion) . ',
						   rating = '.(int)$rating_value.'
						   ';
                Db::getInstance()->Execute($sql);
            }
        }
        ### update new ratings ###

       	$sql = 'UPDATE `'._DB_PREFIX_.'gsnipreview`
	    						SET `title_review` = "'.pSQL($title_review).'",
						   			`text_review` = "'.pSQL($text_review).'",
									`customer_name` = "'.pSQL($name).'",
									`email` = "'.pSQL($email).'",
						   			`rating` = "'.pSQL($rating_new).'",
						   			`time_add` = "'.pSQL($time_add).'",
						   			`is_active` = '.(int)($is_active).'

						   		WHERE id = '.(int)($id).''; 
		Db::getInstance()->Execute($sql);


        // admin response //
        $is_display_old = $data['is_display_old'];
        $admin_response = $data['admin_response'];
        $is_send_again = $data['is_noti'];

        if(Tools::strlen($admin_response)>0)
            $this->setChangedReview(array('review_id'=>$id, 'is_display_old'=>$is_display_old,'admin_response'=>$admin_response,
                                          'is_send_again'=>$is_send_again,'is_changed'=>$is_changed));
        // admin response //


        // save avatar //
        include_once(dirname(__FILE__).'/../gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();
        if($obj_gsnipreview->is_demo == 0) {
            $id_customer = $data['id_customer'];

            $this->saveImageAvatar(array('id' => $id, 'post_images' => $post_images,'id_customer'=>$id_customer));
        }
        // save avatar //


        $is_active_old = $data_item['reviews'][0]['is_active'];

        // review published //
        if($is_active_old == 0 && $is_active == 1) {

            $product_name = $data_item['reviews'][0]['product_name'];
            $product_link = $data_item['reviews'][0]['product_link'];
            $rating = $data_item['reviews'][0]['rating'];

            $id_product = $data_item['reviews'][0]['id_product'];


            if (!empty($data_item['reviews'][0]['id_customer']))
                $customer_name = $data_item['reviews'][0]['customer_name_full'];
            else
                $customer_name = $data_item['reviews'][0]['customer_name'];

            $title = $data_item['reviews'][0]['title_review'];
            $text_review = $data_item['reviews'][0]['text_review'];
            $email = $data_item['reviews'][0]['email'];

            $this->sendNotificationPublish(
                array(
                    'id_product' => $id_product,
                    'customer_name' => $customer_name,
                    'product_name' => $product_name,
                    'product_link' => $product_link,
                    'title' => $title,
                    'text_review' => $text_review,
                    'rating' => $rating,
                    'email' => $email,
                )
            );
            // review published //
        }

        $this->_clearSmartyCache();
			
	}
	
	public function getReviews($data){
		$id_product = $data['id_product'];
		$start = $data['start'];
		$step = (int)Configuration::get($this->_name.'revperpage');

        $cookie = $this->context->cookie;
        $id_lang = (int)($cookie->id_lang);

        $sql_condition = $this->getConditionMultilanguageAndMultiStore(array('and'=>1));
        $id_shop = $this->getIdShop();



        $frat = isset($data['frat'])?(int)$data['frat']:null;
        $sql_rating = '';
        if($frat) {
            if ($frat > 5)
                $frat = 5;
            $sql_rating = ' AND rating = '.(int)$frat.'  ';
        }


        $is_search = isset($data['is_search'])?$data['is_search']:0;
        $search = isset($data['search'])?$data['search']:'';


        $sql_condition_search = '';
        if($is_search == 1){
            $sql_condition_search = " AND (
	    		   LOWER(title_review) LIKE BINARY LOWER('%".pSQL(trim($search))."%')
	    		   OR
	    		   LOWER(text_review) LIKE BINARY LOWER('%".pSQL(trim($search))."%')
	    		   OR
	    		   LOWER(admin_response) LIKE BINARY LOWER('%".pSQL(trim($search))."%')
	    		   )  ";
        }

		$reviews = Db::getInstance()->ExecuteS('
		SELECT pc.*
		FROM `'._DB_PREFIX_.'gsnipreview` pc
		WHERE pc.`id_product` = '.(int)($id_product).'
		'.$sql_condition.'  '.$sql_rating.' '.$sql_condition_search.'
		ORDER BY pc.`time_add` DESC LIMIT '.(int)($start).' ,'.(int)($step).'');
		
		foreach($reviews as $k=>$item_review){

            $id_review = $reviews[$k]['id'];

            $id_customer = $item_review['id_customer'];
            $avatar = $item_review['avatar'];
            $info_path = $this->_getAvatarPath(array('avatar' => $avatar, 'id_customer'=>$id_customer ));
            $reviews[$k]['avatar'] = $info_path['avatar'];
            $reviews[$k]['is_show_ava'] = $info_path['is_show'];

            $is_buy = 0;
            if($id_customer) {
                $is_buy = $this->checkProductBought(array('id_customer' => $id_customer,'id_product'=>$id_product));
            }
            $reviews[$k]['is_buy'] = $is_buy;


            $data_files = $this->getFiles2Review(array('id_review'=>$id_review));
            $reviews[$k]['files'] = $data_files;


			
			$text_review = $item_review['text_review'];
			$text_review= preg_replace("/(^|[\n ])([\w]*?)([^\"\>]?(ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" target=\"_blank\" >$3</a>", $text_review);
			$reviews[$k]['text_review'] = $text_review;

            $admin_response = isset($item_review['admin_response'])?$item_review['admin_response']:null;
            $admin_response = preg_replace("/(^|[\n ])([\w]*?)([^\"\>]?(ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" target=\"_blank\" >$3</a>", $admin_response);
            $reviews[$k]['admin_response'] = $admin_response;

            ## criterions ##

                $id_lang_product_review = $reviews[$k]['id_lang'];
                $data_criterions = $this->getCriterionsByProductReview(array('id_review'=>$id_review,'id_lang'=>$id_lang_product_review,'id_shop'=>$id_shop));
                $reviews[$k]['criterions'] = $data_criterions;
            ## criterions ##

            ## helpfull ##
                $data_helpfull = $this->getHelpfullVotes(array('review_id'=>$id_review));
                $yes = $data_helpfull['yes'];
                $all = $data_helpfull['all'];
                $reviews[$k]['helpfull_yes'] = $yes;
                $reviews[$k]['helpfull_all'] = $all;
            ## helpfull ##


            ## product name for snippets ##
            $product_id = $item_review['id_product'];
            $id_lang = ($item_review['id_lang'])?$item_review['id_lang']:$id_lang;

            $product_obj = new Product($product_id);
            $name_page = Tools::stripslashes($product_obj->name[$id_lang]);

            $reviews[$k]['product_name'] = $name_page;
            $reviews[$k]['date_pub_for_snippets'] = date("Y-m-d",strtotime($item_review['time_add']));
            ## product name for snippets ##

            $ip = $item_review['ip'];
            if(!empty($ip)) {
                $ip = $this->getCityandCountry(array('ip' => $ip));
                $reviews[$k]['ip'] = $ip;
                $reviews[$k]['is_no_ip'] = 1;
            } else {
                $reviews[$k]['is_no_ip'] = 0;
            }

        }
		
		
		
		
		$data_count_reviews = Db::getInstance()->getRow('
		SELECT COUNT(`id`) AS "count"
		FROM `'._DB_PREFIX_.'gsnipreview` 
		WHERE `id_product` = '.(int)($id_product).'
		'.$sql_condition.' '.$sql_rating.' '.$sql_condition_search.'
		');
		
		 return array('reviews' => $reviews, 'count_all_reviews' => $data_count_reviews['count'] );
	}
	
	/*
	  * *  echo $n." ".number_ending($n, "reviews", "review", "reviews"); 
	 */
	public function number_ending($number, $ending0, $ending1, $ending2) {
		$num100 = $number % 100;
		$num10 = $number % 10;
		if ($num100 >= 5 && $num100 <= 20) {
			return $ending0;
		} else if ($num10 == 0) {
			return $ending0;
		} else if ($num10 == 1) {
			return $ending1;
		} else if ($num10 >= 2 && $num10 <= 4) {
			return $ending2;
		} else if ($num10 >= 5 && $num10 <= 9) {
			return $ending0;
		} else {
			return $ending2;
		}
	}
	
	
	public function paging($data)
	{
		$start = $data['start'];
		$count = $data['count'];
		$step = $data['step'];
		$page_text = $data['page'];
		$_admin = isset($data['admin'])?$data['admin']:0;

        $res = '';
		$res .= '<div class="pages">';
		$res .= '<span>'.$page_text.': </span>';
		$res .= '<span class="nums">';
		
		$start1 = $start;
			for ($start1 = ($start - $step*4 >= 0 ? $start - $step*4 : 0); $start1 < ($start + $step*5 < $count ? $start + $step*5 : $count); $start1 += $step)
				{
					$par = (int)($start1 / $step) + 1;

					if ($start1 == $start)
						{
						
						$res .= '<b>'. $par .'</b>';
						}
					else
						{
							if($_admin){
								$currentIndex = $data['currentIndex'];
								$token = $data['token'];
								$res .= '<a href="'.$currentIndex.'&page='.($start1 ? $start1 : 0).$token.'" >'.$par.'</a>';
							}
						}
				}
		
		$res .= '</span>';
		$res .= '</div>';
		
		return $res;
	}

    public function paging17($data)
    {
        $start = $data['start'];
        $count = $data['count'];
        $step = $data['step'];

        $frat = isset($data['frat'])?$data['frat']:null;
        $is_search = isset($data['is_search'])?$data['is_search']:null;
        $search = isset($data['search'])?$data['search']:null;
        $user_url = isset($data['user_url'])?$data['user_url']:null;


        $full_array = array();




        $start1 = $start;
        for ($start1 = ($start - $step*4 >= 0 ? $start - $step*4 : 0); $start1 < ($start + $step*5 < $count ? $start + $step*5 : $count); $start1 += $step)
        {
            $par = (int)($start1 / $step) + 1;

            if ($start1 == $start)
            {
                $full_array[] = array('page'=>$par,'is_b'=>1);


            }
            else
            {

                    $items_url = isset($data['product_url']) ? $data['product_url'] : '';


                    //$frat_question = '';
                    $frat_amp = '';

                    if($frat) {
                        //$frat_question = '?frat=' . $frat;
                        $frat_amp = '&frat=' . $frat;
                    }

                    $search_amp = '';
                    //$search_question = '';
                    if($is_search){
                        $search_amp = '&search='.$search;
                        //$search_question = '?search='.$search;
                    }

                    $amp_or_q = '?';

                    if($user_url){
                        $amp_or_q = '&';
                    }

                    if((
                        Configuration::get('PS_REWRITING_SETTINGS') ||
                        version_compare(_PS_VERSION_, '1.5', '<'))){

                        $gp = ($start1 ? $amp_or_q.'gp='.$par.$frat_amp.$search_amp : $amp_or_q.'gp=0'.$frat_amp.$search_amp);
                    } else {

                        $gp = ($start1 ? '&gp='.$par.$frat_amp.$search_amp : '?gp=0'.$frat_amp.$search_amp);


                    }

                    $full_array[] = array('page'=>$par,'is_b'=>0,'url'=>$items_url . $gp,'title'=>$par);



            }

        }


        return $full_array;
    }
	
	public function createRSSFile($post_title,$post_description,$post_link, $img)
	{
		$returnITEM = "<item>\n";
		# this will return the Title of the Article.
		$returnITEM .= "<title><![CDATA[".$post_title."]]></title>\n";
		# this will return the Description of the Article.
		$returnITEM .= "<description><![CDATA[".((Tools::strlen($img)>0)?"<img src=\"".$img."\" title=\"".$post_title."\" alt=\"thumb\" />":"").$post_description."]]></description>\n";
		# this will return the URL to the post.
		$returnITEM .= "<link>".str_replace('&','&amp;', $post_link)."</link>\n";
		$returnITEM .= "</item>\n";
		return $returnITEM;
	}
	
	public function getItemsForRSS(){
			
			$step = Configuration::get($this->_name.'number_rssitems');
			
			$sql = '
			SELECT pc.*
			FROM `'._DB_PREFIX_.'gsnipreview` pc
			where id_shop = '.(int)($this->getIdShop()).' and pc.is_active = 1
			ORDER BY pc.`time_add` DESC LIMIT 0, '.(int)($step);
			
			$reviews = Db::getInstance()->ExecuteS($sql);
			
			
			$i=0;
			foreach($reviews as $_item){
				$product_id = $_item['id_product'];
				
				$product_obj = new Product($product_id);
				
				$data_product = $this->_productData(array('product'=>$product_obj));	
			    $product_link = $data_product['product_url'];
			    $picture = $data_product['image_link'];
				
				$reviews[$i]['page'] = $product_link;
				
				
		    	$reviews[$i]['title'] = $reviews[$i]['title_review'];
		    	$reviews[$i]['seo_description'] = strip_tags($reviews[$i]['text_review']);
		    	
		    	
			    $reviews[$i]['img'] = $picture; 
				### image ####
		    	
		    $i++;
			}
		
			return array('items' => $reviews);
	}
	
	
	public function getfacebooklocale()
	{
        $locales = array();

        if (($xml=simplexml_load_file(_PS_MODULE_DIR_ . $this->_name."/lib/facebook_locales.xml")) === false)
            return $locales;

        for ($i=0;$i<sizeof($xml);$i++)
        {
            $locale = $xml->locale[$i]->codes->code->standard->representation;
            $locales[]= $locale;
        }

        return $locales;
	}
	
 	public function getfacebooklib($id_lang){
    	
    	$lang = new Language((int)$id_lang);
		
    	$lng_code = isset($lang->language_code)?$lang->language_code:$lang->iso_code;
    	if(strstr($lng_code, '-')){
			$res = explode('-', $lng_code);
			$language_iso = Tools::strtolower($res[0]).'_'.Tools::strtoupper($res[1]);
			$rss_language_iso = Tools::strtolower($res[0]);
		} else {
			$language_iso = Tools::strtolower($lng_code).'_'.Tools::strtoupper($lng_code);
			$rss_language_iso = $lng_code;
		}
			
			
		if (!in_array($language_iso, $this->getfacebooklocale()))
			$language_iso = "en_US";
		
		if (Configuration::get('PS_SSL_ENABLED') == 1)
			$url = "https://";
		else
			$url = "http://";
		
		
		
		return array('url'=>$url . 'connect.facebook.net/'.$language_iso.'/all.js#xfbml=1',
					  'lng_iso' => $language_iso, 'rss_language_iso' => $rss_language_iso);
    }
    
    
private function _productData($data){
		
		$product = $data['product'];
		if(is_object($product) && !empty($product->id)){
		$cookie = $this->context->cookie;
		$id_lang = (int)($cookie->id_lang);	
		
			/* Product URL */
			if (version_compare(_PS_VERSION_, '1.5', '>'))
				$link = Context::getContext()->link;
			else
				$link = new Link();
				
			$category = new Category((int)($product->id_category_default), $id_lang);

            if (version_compare(_PS_VERSION_, '1.5.5', '>=')) {
                   $product_url = $link->getProductLink((int)$product->id, null, null, null, 
                    									 $id_lang, null, 0, false);
             }
             elseif (version_compare(_PS_VERSION_, '1.5', '>')) {
               if (Configuration::get('PS_REWRITING_SETTINGS')) {
                     $product_url = $link->getProductLink((int)$product->id, null, null, null, 
                     									 $id_lang, null, 0, true);
               }
                else {
                    $product_url = $link->getProductLink((int)$product->id, null, null, null, 
                     									 $id_lang, null, 0, false);
                 }
            }
            else {
            	  $product_url = $link->getProductLink((int)$product->id, @$product->link_rewrite[$id_lang],
                 									 $category->link_rewrite, $product->ean13, $id_lang);
            }
            
            
			if (version_compare(_PS_VERSION_, '1.5', '>'))
				$link = Context::getContext()->link;
			else
				$link = new Link();

			/* Image */
			$image = Image::getCover((int)($product->id));

			if ($image)
			{
                $block = isset($data['block'])?$data['block']:'';


                $available_types = ImageType::getImagesTypes('products');
                switch($block){
                    case 'home':
                        $type_img = Configuration::get($this->_name.'blocklr_home_im');

                    break;
                    case 'cat':
                    case 'man':
                    case 'prod':
                    case 'oth':
                    case 'chook':
                        $type_img = Configuration::get($this->_name.'blocklr_'.$block.'_im');
                    break;
                    default;

                        foreach ($available_types as $type){
                            $width = $type['width'];
                            if($width>400){
                                $type_img = $type['name'];
                                break;
                            }
                        }
                    break;
                }

                $email = isset($data['email'])?$data['email']:0;
                if($email){
                    $type_img = Configuration::get($this->_name.'img_size_em');
                }


				
				$image_link = $link->getImageLink(@$product->link_rewrite[$id_lang], (int)($product->id).'-'.(int)($image['id_image']),$type_img);
				/* version 1.4 */
				if (strpos($image_link, 'http://') === FALSE && strpos($image_link, 'https://') === FALSE && version_compare(_PS_VERSION_, '1.4', '<'))
				{
					$image_link = 'http://'.$_SERVER['HTTP_HOST'].$image_link;
				}
			}
			else
			{
				$image_link = false;
				
			}
         }else {
			$image_link= false;
			$product_url = false;
		}    
            return array('product_url'=>$product_url,'image_link'=>$image_link);
	}

    public function getCriterionsByProductReview($data){
        $id_review = $data['id_review'];
        $id_lang = $data['id_lang'];
        $id_shop = $data['id_shop'];
        $sql = '
			SELECT grcl.id_gsnipreview_review_criterion, grcl.name, g2c.rating, grcl.description
			FROM `'._DB_PREFIX_.'gsnipreview_review2criterion` g2c
			left join  '._DB_PREFIX_.'gsnipreview_review_criterion grc
			on(g2c.id_criterion = grc.id_gsnipreview_review_criterion)
			left join '._DB_PREFIX_.'gsnipreview_review_criterion_lang grcl
			on(grcl.id_gsnipreview_review_criterion = grc.id_gsnipreview_review_criterion)
            where g2c.id_review = '.(int)$id_review.' AND grcl.id_lang = '.(int)$id_lang.'
            AND FIND_IN_SET('.(int)($id_shop).',grc.id_shop)
			ORDER BY grcl.`name` ASC
			';
        return Db::getInstance()->ExecuteS($sql);
    }

    public function getReviewCriteriaItems($_data = null){
        $start = isset($_data['start'])?$_data['start']:0;
        $step = $_data['step'];

        $sql = '
			SELECT pc.*
			FROM `'._DB_PREFIX_.'gsnipreview_review_criterion` pc
			ORDER BY pc.`id_gsnipreview_review_criterion` DESC
			LIMIT '.(int)($start).' ,'.(int)($step).'';
        $items = Db::getInstance()->ExecuteS($sql);


        foreach($items as $k => $_item){

            $items_data = Db::getInstance()->ExecuteS('
				SELECT pc.*
				FROM `'._DB_PREFIX_.'gsnipreview_review_criterion_lang` pc
				WHERE pc.id_gsnipreview_review_criterion = '.(int)($_item['id_gsnipreview_review_criterion']).'
				');

            $cookie = $this->context->cookie;
            $defaultLanguage =  $cookie->id_lang;

            $tmp_title = '';
            // languages
            $languages_tmp_array = array();


            foreach ($items_data as $item_data){
                $languages_tmp_array[] = $item_data['id_lang'];

                $title = isset($item_data['name'])?$item_data['name']:'';
                if(Tools::strlen($tmp_title)==0){
                    if(Tools::strlen($title)>0)
                        $tmp_title = $title;
                }


                if($defaultLanguage == $item_data['id_lang']){
                    $items[$k]['name'] = $item_data['name'];
                }
            }

            // languages
            $items[$k]['ids_lng'] = $languages_tmp_array;


            $name_criteria = isset($items[$k]['name'])?$items[$k]['name']:'';
            if(Tools::strlen($name_criteria)==0)
                $items[$k]['name'] = $tmp_title;

        }

        $data_count = Db::getInstance()->getRow('
			SELECT COUNT(`id_gsnipreview_review_criterion`) AS "count"
			FROM `'._DB_PREFIX_.'gsnipreview_review_criterion`');

        return array('items' => $items, 'count_all' => $data_count['count']);
    }

    public function getReviewCriteriaItem($_data = null){
        $id= (int)$_data['id'];

        $sql = '
			SELECT pc.*
			FROM `'._DB_PREFIX_.'gsnipreview_review_criterion` pc
			WHERE id_gsnipreview_review_criterion = '.(int)($id).'';

        $items = Db::getInstance()->ExecuteS($sql);

        foreach($items as $_item){
            $sql_data = '
				SELECT pc.*
				FROM `'._DB_PREFIX_.'gsnipreview_review_criterion_lang` pc
				WHERE pc.id_gsnipreview_review_criterion = '.(int)($_item['id_gsnipreview_review_criterion']).'';

            $items_data = Db::getInstance()->ExecuteS($sql_data);

            foreach ($items_data as $item_data){
                $items['data'][$item_data['id_lang']]['description'] = $item_data['description'];
                $items['data'][$item_data['id_lang']]['name'] = $item_data['name'];

            }

        }


        return array('item' => $items);
    }


    public  function getReviewCriteria($data)
    {
        $id_lang = (int)$data['id_lang'];
        $id_shop = (int)$data['id_shop'];

        $sql = '
			SELECT pc.id_gsnipreview_review_criterion, pcl.name, pcl.description
			FROM `'._DB_PREFIX_.'gsnipreview_review_criterion` pc
			    LEFT JOIN `'._DB_PREFIX_.'gsnipreview_review_criterion_lang` pcl
			    on(pcl.id_gsnipreview_review_criterion = pc.id_gsnipreview_review_criterion)
			WHERE FIND_IN_SET('.(int)($id_shop).',pc.id_shop)  AND pc.active = 1 AND pcl.id_lang = '.(int)$id_lang.'';

        $items = Db::getInstance()->executeS($sql);
        foreach($items as $k=>$item){

            $description = isset($item['description'])?$item['description']:'';
            $description = str_replace("\n","<br/>", $description);


            $items[$k]['description'] = $description;
        }

        return $items;
    }

    public function saveReviewCriteriaItem($data){

        $active = $data['active'];

        $ids_shops = implode(",",$data['cat_shop_association']);



        $sql = 'INSERT into `'._DB_PREFIX_.'gsnipreview_review_criterion` SET
							   `active` = \''.(int)($active).'\',
							   `id_shop` = \''.pSQL($ids_shops).'\'
							   ';
        Db::getInstance()->Execute($sql);

        $id_block = Db::getInstance()->Insert_ID();

        foreach($data['data_content_lang'] as $language => $item){

            $description = $item['description'];
            $name = $item['name'];
            $sql = 'INSERT into `'._DB_PREFIX_.'gsnipreview_review_criterion_lang` SET
							   `id_lang` = \''.(int)($language).'\',
							   `name` = "'.pSQL($name,true).'",
							   `description` = "'.pSQL($description,true).'",
							   `id_gsnipreview_review_criterion` = \''.(int)($id_block).'\'
							   ';

            Db::getInstance()->Execute($sql);

        }

        return $id_block;

    }


    public function updateReviewCriteriaItem($data){

        $active = $data['active'];
        $ids_shops = implode(",",$data['cat_shop_association']);
        $id = $data['id'];

        // update
        $sql = 'UPDATE `'._DB_PREFIX_.'gsnipreview_review_criterion` SET
					     `active` = \''.(int)($active).'\',
					     `id_shop` = \''.pSQL($ids_shops).'\'
					    WHERE id_gsnipreview_review_criterion = '.(int)($id).'';
        Db::getInstance()->Execute($sql);


        $sql = 'DELETE FROM `'._DB_PREFIX_.'gsnipreview_review_criterion_lang`
						WHERE id_gsnipreview_review_criterion = '.(int)($id).'
						';
        Db::getInstance()->Execute($sql);


        foreach($data['data_content_lang'] as $language => $item){

            $description = $item['description'];
            $name = $item['name'];

            $sql = 'INSERT into `'._DB_PREFIX_.'gsnipreview_review_criterion_lang` SET
							   `id_lang` = \''.(int)($language).'\',
							   `description` = "'.pSQL($description, true).'",
							   `name` = "'.pSQL($name, true).'",
							   `id_gsnipreview_review_criterion` = \''.(int)($id).'\'
							   ';
            Db::getInstance()->Execute($sql);

        }

    }


    public function deleteReviewCriteriaItem($data){

        $id = $data['id'];


        $sql = 'DELETE FROM `'._DB_PREFIX_.'gsnipreview_review_criterion`
					   WHERE id_gsnipreview_review_criterion ='.(int)($id).'';
        Db::getInstance()->Execute($sql);


        $sql = 'DELETE FROM `'._DB_PREFIX_.'gsnipreview_review_criterion_lang`
					   WHERE id_gsnipreview_review_criterion ='.(int)($id).'
					   ';
        Db::getInstance()->Execute($sql);

        $sql = 'DELETE FROM `'._DB_PREFIX_.'gsnipreview_review2criterion`
					   WHERE id_criterion ='.(int)($id).'
					   ';
        Db::getInstance()->Execute($sql);

    }


    public function getReviewForAbuse($data)
    {
        $review_id = (int)$data['review_id'];

        $sql = '
			SELECT pc.id as id_review, pc.title_review, pc.text_review, gra.name as abuse_name, gra.email, gra.text_abuse, gra.is_customer,gra.id_customer,
			    pc.customer_name, pc.id_product, pc.id_lang, pc.id_shop
			FROM `'._DB_PREFIX_.'gsnipreview` pc left join `'._DB_PREFIX_.'gsnipreview_review_abuse` gra on(gra.review_id = pc.id)
			WHERE pc.id = '.(int)$review_id.'';


        $reviews = Db::getInstance()->ExecuteS($sql);

        $cookie = $this->context->cookie;



        $i=0;
        foreach($reviews as $_item){
            $product_id = $_item['id_product'];

            $product_obj = new Product($product_id);

            $id_lang = ($_item['id_lang'] != 0)?$_item['id_lang']:(int)($cookie->id_lang);
            $name_page = $product_obj->name[$id_lang];


            $data_product = $this->_productData(array('product'=>$product_obj));
            $product_link = $data_product['product_url'];

            $picture = $data_product['image_link'];

            $reviews[$i]['product_link'] = $product_link;

            $reviews[$i]['product_img'] = $picture;
            $reviews[$i]['product_name'] = $name_page;


            $data_seo_url = $this->getSEOURLs(array('id_lang'=>$id_lang,'id_shop'=>$_item['id_shop']));
            $rev_url = $data_seo_url['rev_url'];

            $reviews[$i]['review_url'] = $rev_url.((Configuration::get('PS_REWRITING_SETTINGS') || version_compare(_PS_VERSION_, '1.5', '<'))?'?':'&').'rid='.$review_id;

            $url_to_customer  = '';
            $is_customer = $_item['is_customer'];
            $id_customer = $_item['id_customer'];
            if($is_customer) {
                $url_to_customer = 'index.php?'.(version_compare(_PS_VERSION_, '1.5', '>')?'controller':'tab').'=AdminCustomers&id_customer=' . $id_customer . '&updatecustomer&token=' .(isset($data['token'])?$data['token']:'') . '';
                $customer_data = $this->getInfoAboutCustomer(array('id_customer'=>$id_customer,'is_full'=>1));
                $name = $customer_data['customer_name'];
                $reviews[$i]['customer_name'] = $name;
            }
            $reviews[$i]['url_to_customer'] = $url_to_customer;


            $i++;
        }

        return $reviews[0];
    }


    /* abuse functional */

    public function isAbuseExists($data)
    {
        $review_id = (int)$data['review_id'];


        $sql = '
			SELECT is_abuse
			FROM `'._DB_PREFIX_.'gsnipreview` pc
			WHERE pc.id = '.(int)$review_id.'';

        return Db::getInstance()->getRow($sql);
    }

    public function setReviewIsNotAbusive($data){
        $review_id = $data['review_id'];
        $sql = 'UPDATE `'._DB_PREFIX_.'gsnipreview` SET
						   is_abuse = 0
						   where id = '.(int)($review_id).'

						   ';
        Db::getInstance()->Execute($sql);

        $sql = 'DELETE FROM `'._DB_PREFIX_.'gsnipreview_review_abuse` where review_id = '.(int)($review_id).'';
        Db::getInstance()->Execute($sql);
    }

    public function saveAbuse($data){

        $review_id = $data['review_id'];
        $id_customer = (int)$data['id_customer'];
        $id_customer_orig = $id_customer;
        //$id_guest = $data['id_guest'];
        $text = $data['text'];

        $is_customer = 0;

        $name = $data['name'];
        $email = $data['email'];

        if($id_customer){
                $customer_data = $this->getInfoAboutCustomer(array('id_customer'=>$id_customer));
                $name = $customer_data['customer_name'];
                $email = $customer_data['email'];
                $is_customer = 1;
        }

        $sql_cond = ' ,
                          `name` = \''.pSQL($name).'\',
                          `email` = \''.pSQL($email).'\'
                        ';




        //insert abuse
        $sql = 'INSERT into `'._DB_PREFIX_.'gsnipreview_review_abuse` SET
						   review_id = '.(int)($review_id).',
						   id_customer = '.(int)($id_customer_orig).',
						   text_abuse = \''.pSQL($text).'\',
						   is_customer = \''.(int)$is_customer.'\'
						   '.$sql_cond.'
						   ';
        Db::getInstance()->Execute($sql);

        $sql = 'UPDATE `'._DB_PREFIX_.'gsnipreview` SET
						   is_abuse = 1
						   where id = '.(int)($review_id).'

						   ';
        Db::getInstance()->Execute($sql);



       ### send notification ###
       include_once(dirname(__FILE__).'/../gsnipreview.php');
       $obj = new gsnipreview();
       $data_translate = $obj->translateCustom();
       if($id_customer_orig){
            $customer_data = $this->getInfoAboutCustomer(array('id_customer'=>$id_customer_orig,'is_full'=>1));
           //var_dump($customer_data);
            $name = $customer_data['customer_name'];
            $email = $customer_data['email'];
            $text_customer = $data_translate['reg_customer'].' '.$name.' ('.$email.')';
        } else {
           $name = $data['name'];
           $email = $data['email'];
           $text_customer = $data_translate['no_reg_customer'].' '.$name.' ('.$email.')';
       }

        //var_dump($id_customer_orig);exit;

        $data_review = $this->getItem(array('id'=>$review_id));

        $id_lang = $data_review['reviews'][0]['id_lang'];
        $id_shop = $data_review['reviews'][0]['id_shop'];


        $data_seo_url = $this->getSEOURLs(array('id_lang'=>$id_lang,'id_shop'=>$id_shop));

        $rev_url = $data_seo_url['rev_url'];



         $link_review = $rev_url.((Configuration::get('PS_REWRITING_SETTINGS') || version_compare(_PS_VERSION_, '1.5', '<'))?'?':'&').'rid='.$review_id;

        $data_review = $this->getReviewForAbuse(array('review_id'=>$review_id));



        $review_data = '<b>'.$data_translate['title'].' :</b> '.$data_review['title_review'].
                       '<br/><br/>'.
                       '<b>'.$data_translate['review'].' :</b> '.$data_review['text_review'];
        $id_product = $data_review['id_product'];

        $data_send_notifications = array(
            'text_customer'=>$text_customer,
            'text' => $text,
            'link_review' => $link_review,
            'data' => $review_data,
            'id_product' => $id_product,

        );
        $this->sendNotificationAbuse($data_send_notifications);
        ### send notification ###



    }

    public function sendNotificationAbuse($data){


            if(Configuration::get($this->_name.'noti') == 1) {




                $cookie = $this->context->cookie;
                $id_lang = (int)($cookie->id_lang);

                $review_data = $data['data'];
                $customer = $data['text_customer'];
                $link_review = $data['link_review'];
                $abuse_text = $data['text'];

                ### product data ###
                $id_product = $data['id_product'];
                $product_obj = new Product($id_product);
                $data_product = $this->_productData(array('product' => $product_obj, 'email' => 1));
                $picture = $data_product['image_link'];
                ### product data ###

                /* Email generation */
                $templateVars = array(
                    '{review_data}' => $review_data,
                    '{customer}' => $customer,
                    '{rev_url}' => $link_review,
                    '{abuse_text}' => $abuse_text,
                    '{picture}' =>$picture,
                    );


                $iso_lng = Language::getIsoById((int)($id_lang));

                $dir_mails = _PS_MODULE_DIR_ . '/' . $this->_name . '/' . 'mails/';

                if (is_dir($dir_mails . $iso_lng . '/')) {
                    $id_lang_current = $id_lang;
                } else {
                    $id_lang_current = Language::getIdByIso('en');
                }

                $subject_abuse_title_email = Configuration::get($this->_name . 'abuserevr_' . $id_lang);

                /* Email sending */
                Mail::Send($id_lang_current, 'abuseserg', $subject_abuse_title_email, $templateVars,
                    Configuration::get($this->_name.'mail'), 'Abuse Form', NULL, NULL,
                    NULL, NULL, dirname(__FILE__) . '/../mails/');

            }
        }



    /* abuse functional */



    /* helpfull functional */

    public function isHelpfullExists($data){
        $review_id = (int)$data['review_id'];
        $id_customer = $data['id_customer'];

        $sql_cond = '';
        if(!$id_customer){
            $sql_cond = ' AND pc.ip= "'.pSQL($_SERVER['REMOTE_ADDR']).'" ';
        } else {
            $sql_cond = ' AND id_customer='.(int)$id_customer.'';
        }


        $sql = '
			SELECT count(*) as count
			FROM `'._DB_PREFIX_.'gsnipreview_review_helpfull` pc
			WHERE pc.review_id = '.(int)$review_id.' '.$sql_cond;
       // echo $sql; exit;

        return Db::getInstance()->getRow($sql);
    }

    public function saveHelpfullVote($data){
        $review_id = (int)$data['review_id'];
        $id_customer = (int)$data['id_customer'];
        $id_guest = (int)$data['id_guest'];
        $helpfull = (int)$data['helpfull'];



        $sql = 'INSERT into `'._DB_PREFIX_.'gsnipreview_review_helpfull`  SET
						   review_id = '.(int)($review_id).',
						   id_customer = '.(int)($id_customer).',
						   is_guest = '.(int)($id_guest).',
						   ip= "'.pSQL($_SERVER['REMOTE_ADDR']).'",
						   helpfull = '.(int)($helpfull).'
						   ';
        Db::getInstance()->Execute($sql);
    }

    public function getHelpfullVotes($data){
        $review_id = $data['review_id'];

        $sql = '
			SELECT count(*) as count
			FROM `'._DB_PREFIX_.'gsnipreview_review_helpfull` pc
			WHERE pc.review_id = '.(int)$review_id.' and helpfull = 1';

        $data_yes = Db::getInstance()->getRow($sql);

        $sql = '
			SELECT count(*) as count
			FROM `'._DB_PREFIX_.'gsnipreview_review_helpfull` pc
			WHERE pc.review_id = '.(int)$review_id.'';

        $data_all = Db::getInstance()->getRow($sql);

        return array('yes'=>$data_yes['count'],'all'=>$data_all['count']);

    }

    /* helpfull functional */


    /* suggestion to customer change review functional */
    public function setChangedReview($data){


        $review_id = $data['review_id'];
        $is_display_old = $data['is_display_old'];
        $admin_response= $data['admin_response'];
        $is_send_again = (int)$data['is_send_again'];
        $is_changed = isset($data['is_changed'])?$data['is_changed']:1;

        $sql = '
			SELECT is_count_sending_suggestion
			FROM `'._DB_PREFIX_.'gsnipreview` pc
			WHERE pc.id = '.(int)$review_id.'';

        $count_sending_suggestion = Db::getInstance()->getRow($sql);
        $count_sending_suggestion = $count_sending_suggestion['is_count_sending_suggestion'];
        $is_count_sending_suggestion = $count_sending_suggestion + 1;


        $sql = 'UPDATE `'._DB_PREFIX_.'gsnipreview` SET
						   is_display_old = '.(int)$is_display_old.',
						   admin_response = "'.pSQL($admin_response).'",
						   is_changed = '.(int)$is_changed.',
						   is_count_sending_suggestion = '.(int)$is_count_sending_suggestion.'
						   where id = '.(int)($review_id).'

						   ';
        Db::getInstance()->Execute($sql);


        if($count_sending_suggestion == 0 || $is_send_again) {
            $data_send_notifications = array(
                                            'admin_response'=>$admin_response,
                                            'review_id' => $review_id
                                            );
            $this->sendNotificationAboutSuggestToCustomer($data_send_notifications);
        }
        ### send notification ###



    }

    public function sendNotificationAboutSuggestToCustomer($data){


        if(Configuration::get($this->_name.'noti') == 1) {

            $id = $data['review_id'];
            $admin_response = $data['admin_response'];


            $data_item = $this->getItem(array('id'=>$id));

            $product_name = $data_item['reviews'][0]['product_name'];
            $rating = $data_item['reviews'][0]['rating'];
            $customer_name = $data_item['reviews'][0]['customer_name'];
            $id_product = $data_item['reviews'][0]['id_product'];


            if (!empty($data_item['reviews'][0]['id_customer']))
                $customer_name = $data_item['reviews'][0]['customer_name_full'];

            $title = $data_item['reviews'][0]['title_review'];
            $text_review = $data_item['reviews'][0]['text_review'];

            $email = $data_item['reviews'][0]['email'];
            $review_url = $data_item['reviews'][0]['review_url'];

            $cookie = $this->context->cookie;
            $id_lang = (int)($cookie->id_lang);



            ### product data ###
            $product_obj = new Product($id_product);
            $data_product = $this->_productData(array('product' => $product_obj, 'email' => 1));
            $picture = $data_product['image_link'];
            ### product data ###

            $link = new Link();
            if(version_compare(_PS_VERSION_, '1.5', '<')) {
                $my_account = "my-account.php";
            }else {
                $my_account = $link->getPageLink("my-account", true, $id_lang);
            }


            /* Email generation */
            $templateVars = array(
                '{title}' => $title,
                '{review}' => $text_review,
                '{customer}' => $customer_name,
                '{rev_url}' => $review_url,
                '{picture}' =>$picture,
                '{rating}' => $rating,
                '{product_name}' => $product_name,
                '{admin_response}' => $admin_response,
                '{my_account}' => $my_account,
            );


            $iso_lng = Language::getIsoById((int)($id_lang));

            $dir_mails = _PS_MODULE_DIR_ . '/' . $this->_name . '/' . 'mails/';

            if (is_dir($dir_mails . $iso_lng . '/')) {
                $id_lang_current = $id_lang;
            } else {
                $id_lang_current = Language::getIdByIso('en');
            }

            if(!$email) {
                $id_customer = (int)$data_item['reviews'][0]['id_customer'];
                $customer_data = $this->getInfoAboutCustomer(array('id_customer'=>$id_customer));
                $email = $customer_data['email'];
            }
                /* Email sending */
                Mail::Send($id_lang_current, 'reviewserg-suggest-to-change', Configuration::get($this->_name . 'subresem_' . $id_lang_current), $templateVars,
                    $email, 'Suggest to change review Form', NULL, NULL,
                    NULL, NULL, dirname(__FILE__) . '/../mails/');


        }
    }


    public function setChangedReviewFromCustomer($data){
        $review_id = $data['review_id'];


        $old_data_review = $this->getItem(array('id' => $review_id));
        $old_data_review = $old_data_review['reviews'][0];

        $rating_old_total = $old_data_review['rating'];

        $title_review_old = $old_data_review['title_review'];
        $text_review_old = $old_data_review['text_review'];
        $rating_old = serialize($old_data_review['criterions']);

        $sql = 'UPDATE `'._DB_PREFIX_.'gsnipreview` SET
						   title_review_old = "'.pSQL($title_review_old).'",
						   text_review_old = "'.pSQL($text_review_old).'",
						   rating_old = "'.pSQL($rating_old).'",
						   is_changed = 2
						   where id = '.(int)($review_id).'

						   ';
        Db::getInstance()->Execute($sql);




        $sql = 'DELETE FROM `'._DB_PREFIX_.'gsnipreview_review2criterion`
						WHERE id_review = '.(int)($review_id).'
						';
        Db::getInstance()->Execute($sql);


        $title_review_new = $data['title_review'];
        $text_review_new = $data['text_review'];


        ## rating ##
        $ratings = $data['ratings'];
        $sizeof_rating = sizeof($ratings);
        $rating = 0;
        foreach($ratings as $rating_value){
            $rating = $rating + $rating_value;
        }
        $rating_new = round($rating/$sizeof_rating);
        ## rating ##

        ## update new ratings ###
        foreach($ratings as $id_criterion => $rating_value) {
            if($id_criterion > 0) {
                $sql = 'INSERT into `' . _DB_PREFIX_ . 'gsnipreview_review2criterion` SET
						   id_review = ' . (int)($review_id) . ',
						   id_criterion = ' . (int)($id_criterion) . ',
						   rating = '.(int)$rating_value.'
						   ';
                Db::getInstance()->Execute($sql);
            }
        }
        ### update new ratings ###

        $sql = 'UPDATE `'._DB_PREFIX_.'gsnipreview` SET
						   title_review = "'.pSQL($title_review_new).'",
						   text_review = "'.pSQL($text_review_new).'",
						   rating = "'.pSQL($rating_new).'",
                           is_active = 0
						   where id = '.(int)($review_id).'

						   ';
        Db::getInstance()->Execute($sql);


        $data_send_notifications = array(
            'rating_old' => $rating_old_total,
            'title_review_old' => $title_review_old,
            'text_review_old' => $text_review_old,
            'title_review_new' => $title_review_new,
            'text_review_new' => $text_review_new,
            'rating_new' => $rating_new,
            'review_id' => $review_id
        );
        $this->sendNotificationAboutChangedReviewToCustomer($data_send_notifications);



    }

    public function sendNotificationAboutChangedReviewToCustomer($data){
        if(Configuration::get($this->_name.'noti') == 1) {

            /*include_once(dirname(__FILE__) . '/../gsnipreview.php');
            $obj = new gsnipreview();
            $data_translate = $obj->translateCustom();*/

            $id = $data['review_id'];


            $data_item = $this->getItem(array('id'=>$id));

            $product_name = $data_item['reviews'][0]['product_name'];
            $customer_name = $data_item['reviews'][0]['customer_name'];
            $id_product = $data_item['reviews'][0]['id_product'];


            if (!empty($data_item['reviews'][0]['id_customer']))
                $customer_name = $data_item['reviews'][0]['customer_name_full'];

            $review_url = $data_item['reviews'][0]['review_url'];

            $rating_old = $data['rating_old'];
            $title_review_old = $data['title_review_old'];
            $text_review_old = $data['text_review_old'];

            $title_review_new = $data['title_review_new'];
            $text_review_new = $data['text_review_new'];
            $rating_new = $data['rating_new'];

            $cookie = $this->context->cookie;
            $id_lang = (int)($cookie->id_lang);



            ### product data ###
            $product_obj = new Product($id_product);
            $data_product = $this->_productData(array('product' => $product_obj, 'email' => 1));
            $picture = $data_product['image_link'];
            $product_link = $data_product['product_url'];
            ### product data ###




            /* Email generation */
            $templateVars = array(
                '{title_review_old}' => $title_review_old,
                '{text_review_old}' => $text_review_old,
                '{rating_old}' => $rating_old,

                '{title_review_new}' => $title_review_new,
                '{text_review_new}' => $text_review_new,
                '{rating_new}' => $rating_new,

                '{customer}' => $customer_name,
                '{rev_url}' => $review_url,
                '{picture}' =>$picture,
                '{product_name}' => $product_name,
                '{product_link}' => $product_link,
            );


            $iso_lng = Language::getIsoById((int)($id_lang));

            $dir_mails = _PS_MODULE_DIR_ . '/' . $this->_name . '/' . 'mails/';

            if (is_dir($dir_mails . $iso_lng . '/')) {
                $id_lang_current = $id_lang;
            } else {
                $id_lang_current = Language::getIdByIso('en');
            }

            $subject_customer_change_review = Configuration::get($this->_name . 'modrevr_' . $id_lang);

            /* Email sending */
            Mail::Send($id_lang_current, 'reviewserg-customer-change-review', $subject_customer_change_review, $templateVars,
                Configuration::get($this->_name.'mail'), 'Customer change own review Form', NULL, NULL,
                NULL, NULL, dirname(__FILE__) . '/../mails/');

        }
    }

    /* suggestion to customer change review functional */


    /* reminder status */
    public function updateReminderForCustomer($data){
        $id_customer = $data['id_customer'];
        $reminder_status = $data['reminder_status'];

        $id_shop = $this->getIdShop();

        $is_exists = $this->isExists(array('id_customer'=>$id_customer));
        if($is_exists){

            // update
            $sql = 'UPDATE `'._DB_PREFIX_.'gsnipreview_reminder2customer` SET
						   status = '.(int)$reminder_status.'
						   WHERE id_customer = '.(int)$id_customer.' and id_shop = '.(int)$id_shop;

        } else {
            // insert
            $sql = 'INSERT into `'._DB_PREFIX_.'gsnipreview_reminder2customer` SET
						   id_customer = '.(int)($id_customer).',
						   id_shop = '.(int)($id_shop).',
						   status = \''.(int)$reminder_status.'\'
						   ';
        }

        Db::getInstance()->Execute($sql);



    }

    public function isExists($data){
    $id_customer = $data['id_customer'];
    $id_shop = $this->getIdShop();
    $sql = '
			SELECT count(*) as count
			FROM `'._DB_PREFIX_.'gsnipreview_reminder2customer` pc
			WHERE pc.id_customer = '.(int)$id_customer.' and pc.id_shop = '.(int)$id_shop;

    $is_exists = Db::getInstance()->getRow($sql);
    return $is_exists['count'];
    }

    public function getStatus($data){
        $id_customer = $data['id_customer'];
        $id_shop = $this->getIdShop();
        $sql = '
			SELECT status
			FROM `'._DB_PREFIX_.'gsnipreview_reminder2customer` pc
			WHERE pc.id_customer = '.(int)$id_customer.' and pc.id_shop = '.(int)$id_shop;

        $is_exists = Db::getInstance()->getRow($sql);
        return $is_exists['status'];
    }

    /* reminder status */


    /* snippets badges */
    public function badges($data)
    {
        $id_supplier = $data['id_supplier'];
        $id_category = $data['id_category'];
        $id_manufacturer = $data['id_manufacturer'];

        $sql_condition = $this->getConditionMultilanguageAndMultiStore(array('and'=>1));

        $sql = 'SELECT IFNULL(ROUND(AVG(gr.rating), 1),0) as total_rating,
                       count(gr.id) as total_reviews
                       FROM ' . _DB_PREFIX_ . 'gsnipreview gr';



        if (!empty($id_category)) {
            $sql .= ' INNER JOIN ' . _DB_PREFIX_ . 'category_product c ON (c.id_product = gr.id_product  AND c.id_category = ' . pSQL($id_category) . ')';
        }

        if (!empty($id_supplier)) {
            $sql .= ' INNER JOIN ' . _DB_PREFIX_ . 'product p ON (p.id_product = gr.id_product AND p.id_supplier = ' . pSQL($id_supplier) . ')';
        }

        if (!empty($id_manufacturer)) {
            $sql .= ' INNER JOIN ' . _DB_PREFIX_ . 'product p ON (p.id_product = gr.id_product AND p.id_manufacturer = ' . pSQL($id_manufacturer) . ')';
        }

        $sql .= ' WHERE  gr.is_active = 1 '.$sql_condition;

        $result = Db::getInstance()->ExecuteS($sql);

        //echo "<pre>";var_dump($result);exit;

        return (!empty($result[0])? str_replace(".",",",$result[0]) : 0);

    }
    /* snippets badges */


    public function getCityandCountry($data){
        $ip = $data['ip'];
        if (file_exists(dirname(__FILE__).'/../../../tools/geoip/geoipcity.inc'))
        {
            include_once(dirname(__FILE__).'/../../../tools/geoip/geoipcity.inc');
            //if(defined('_PS_GEOIP_CITY_FILE_')) {
            $city_data_file = 'GeoLiteCity.dat';
            if (file_exists(_PS_GEOIP_DIR_ . $city_data_file)) {

                if (function_exists('geoip_open')) {
                    //$gi = geoip_open(realpath(_PS_GEOIP_DIR_ . $city_data_file), GEOIP_STANDARD);

                    $gi = call_user_func_array('geoip_open', array(realpath(_PS_GEOIP_DIR_ . $city_data_file),GEOIP_STANDARD));

                    if (function_exists('geoip_record_by_addr')) {
                        //$record = geoip_record_by_addr($gi, $ip);
                        $record = call_user_func_array('geoip_record_by_addr', array($gi, $ip));

                    }
                }

                if (is_object($record)) {

                    $ip = (isset($record->city)?$record->city. ', ':'')  . $record->country_name;
                }
            }
            //}
        }

        return $ip;
    }

    public function isURLRewriting(){
        $_is_rewriting_settings = 0;
        if(Configuration::get('PS_REWRITING_SETTINGS')){
            $_is_rewriting_settings = 1;
        }
        return $_is_rewriting_settings;
    }

    public function getLangISO($data=null){
        $cookie = $this->context->cookie;
        $id_lang = isset($data['id_lang'])?(int)$data['id_lang']:(int)$cookie->id_lang;

        if($this->_id_shop) {
            $all_laguages = Language::getLanguages(true,$this->_id_shop);
        } else {
            $all_laguages = Language::getLanguages(true);
        }


        if($this->isURLRewriting() && sizeof($all_laguages)>1)
            $iso_lang = Language::getIsoById((int)($id_lang))."/";
        else
            $iso_lang = '';

        return $iso_lang;

    }

    public function getSEOURLs($data = null){
        $cookie = $this->context->cookie;
        $id_lang = isset($data['id_lang'])?(int)$data['id_lang']:(int)$cookie->id_lang;

        include_once(dirname(__FILE__) . '/../gsnipreview.php');
        $obj = new gsnipreview();

        /*$iso_code = $this->getLangISO(array('id_lang'=>$id_lang));

        $all_laguages = Language::getLanguages(true);
        if(sizeof($all_laguages)<2){
            $iso_code = '';
        }*/



        $link = new Link();
        if(version_compare(_PS_VERSION_, '1.5', '<')) {

            $my_account = "my-account.php";
            $rev_url = $obj->getHttpost()."modules/".$this->_name."/review.php";
            $rev_all = $obj->getHttpost()."modules/".$this->_name."/all.php";
            $account_url = $obj->getHttpost()."modules/".$this->_name."/my-reviews.php";


            $users_url = $obj->getHttpost() . 'modules/' . $this->_name . '/users.php';
            $user_url = $obj->getHttpost() . 'modules/' . $this->_name . '/user.php?uid=';

                $useraccount_url = $obj->getHttpost() . 'modules/' . $this->_name . '/useraccount.php';


            $storereviews_url = $obj->getHttpost() . 'modules/' . $this->_name . '/storereviews-form.php';
            $store_reviews_account_url = $obj->getHttpost() . 'modules/' . $this->_name . '/my-storereviews.php';

        }else {
            $id_shop = isset($data['id_shop'])?$data['id_shop']:$this->getIdShop();

            $is_ssl = false;
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $is_ssl = true;

            $my_account = $link->getPageLink("my-account", true, $id_lang);
            $rev_url = $link->getModuleLink("gsnipreview", 'review',  array(), $is_ssl, $id_lang);
            $rev_all = $link->getModuleLink("gsnipreview", 'reviews',  array(), $is_ssl, $id_lang, $id_shop);
            $account_url = $link->getModuleLink("gsnipreview", 'myreviews',  array(), $is_ssl, $id_lang);


            $users_url = $link->getModuleLink("gsnipreview", 'users',  array(), $is_ssl, $id_lang);
            $user_url = $link->getModuleLink("gsnipreview", 'user?uid=',  array(), $is_ssl, $id_lang);
            $useraccount_url = $link->getModuleLink("gsnipreview", 'useraccount',  array(), $is_ssl, $id_lang);

            $storereviews_url= $link->getModuleLink("gsnipreview", 'storereviews',  array(), $is_ssl, $id_lang);
            $store_reviews_account_url= $link->getModuleLink("gsnipreview", 'mystorereview',  array(), $is_ssl, $id_lang);

        }



        return array('my_account' => $my_account, 'rev_url'=>$rev_url, 'rev_all'=>$rev_all, 'account_url'=>$account_url,
                    'users_url'=>$users_url, 'user_url'=>$user_url,'useraccount_url'=>$useraccount_url,
                    'storereviews_url'=>$storereviews_url,'store_reviews_account_url'=>$store_reviews_account_url);
    }


    public function generateGoogleReviews(){

        include_once(dirname(__FILE__) . '/../gsnipreview.php');
        $obj = new gsnipreview();
        $data_translate = $obj->translateCustom();

        $filename = dirname(__FILE__).$this->path_img_cloud."reviews.xml";

        $shop_name = Configuration::get('PS_SHOP_NAME');
        if(version_compare(_PS_VERSION_, '1.6', '>')){
            $_http_host = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__;
        } else {
            $_http_host = _PS_BASE_URL_.__PS_BASE_URI__;
        }

        $new_sitemap = '<?xml version="1.0" encoding="UTF-8"?>
                            <feed xmlns:vc="http://www.w3.org/2007/XMLSchema-versioning"
                             xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                             xsi:noNamespaceSchemaLocation="http://www.google.com/shopping/reviews/schema/product/2.1/product_reviews.xsd">
                                <aggregator>
                                    <name>'.$shop_name.' '.$data_translate['google_reviews_title'].'</name>
                                </aggregator>
                                <publisher>
                                    <name>'.$shop_name.'</name>
                                    <favicon>'.$_http_host.'img/logo.jpg</favicon>
                                </publisher>
                                <reviews>
                                </reviews>

                            </feed>';

        file_put_contents($filename,$new_sitemap);


        $xml = simplexml_load_file($filename);

        unset($xml->url);

        $sxe = new SimpleXMLElement($xml->asXML());


        $data = $this->getAllReviews(array('start'=>0,
                                          'step'=>1000000
                                            ));
        if(!empty($data['reviews'])) {

            foreach ($data['reviews'] as $review) {

                $review_node = $sxe->reviews->addChild('review');

                ## name ##
                $reviewer = $review_node->addChild('reviewer');
                $name = pSQL($review['customer_name']);
                $reviewer->addChild('name', $name);
                ## name ##

                ## date ###
                $time_add = date(DATE_ATOM,strtotime($review['time_add']));
                $review_node->addChild('review_timestamp',$time_add);
                ## date ###

                ## content ##
                $content = pSQL($review['text_review']);
                $review_node->addChild('content',$content);
                ## content ##

                ## ratings ##
                $ratings = $review_node->addChild('ratings');
                $rating = $review['rating'];
                $overall = $ratings->addChild('overall', $rating);
                $overall->addAttribute('min', 1);
                $overall->addAttribute('max', 5);
                ## ratings ##

                ## url ##
                $review_url = $review['review_url'];
                $review_url = str_replace('&', '&amp;', $review_url);
                $url = $review_node->addChild('review_url', $review_url);
                $url->addAttribute('type', 'singleton');
                ## url ##


                ## products ##
                $products = $review_node->addChild('products');
                $product = $products->addChild('product');
                $product_url = $review['product_link'];

                $product->addChild('product_url', $product_url);
                ## products ##
            }

        }




        $sxe->asXML($filename);

    }


    private function _clearSmartyCache(){
        if(version_compare(_PS_VERSION_, '1.6', '>')) {
            $smarty = Context::getContext()->smarty;
            //Tools::clearSmartyCache();
            $smarty->clearAllCache();
        }
    }


    ## avatar functional ##

    public function saveImageAvatar($data = null){
        include_once(dirname(__FILE__).'/userprofileg.class.php');
        $obj = new userprofileg();

        return $obj->saveImageAvatar($data);


    }

    public function deleteAvatar($data){
        include_once(dirname(__FILE__).'/userprofileg.class.php');
        $obj = new userprofileg();


        $id = (int)$data['id'];
        $info_post = $this->getItem(array('id'=>$id));
        $img = $info_post['reviews'][0]['avatar'];
        $data['avatar'] = $img;

        return $obj->deleteAvatar($data);
    }


    public function saveAvatar($data){
        include_once(dirname(__FILE__).'/userprofileg.class.php');
        $obj = new userprofileg();

        return $obj->saveAvatar($data);


    }

    public function getAvatarForCustomer($data){
        include_once(dirname(__FILE__).'/userprofileg.class.php');
        $obj = new userprofileg();

        return $obj->getAvatarForCustomer($data);
    }

    private function _getAvatarPath($data){


        include_once(dirname(__FILE__).'/userprofileg.class.php');
        $obj = new userprofileg();

        return $obj->getAvatarPath($data);
    }

    ## avatar functional ##


    public function copyImage($data){

        $filename = $data['name'];
        $dir_without_ext = $data['dir_without_ext'];

        $is_height_width = 0;
        if(isset($data['width']) && isset($data['height'])){
            $is_height_width = 1;
        }


        $width = isset($data['width'])?$data['width']:$this->_width_ava;
        $height = isset($data['height'])?$data['height']:$this->_height_ava;

        $width_orig_custom = $width;
        $height_orig_custom = $height;

        if (!$width){ $width = 85;}
        if (!$height){ $height = 85;}
        // Content type
        $size_img = getimagesize($filename);
        // Get new dimensions
        list($width_orig, $height_orig) = getimagesize($filename);
        $ratio_orig = $width_orig/$height_orig;

        if($width_orig>$height_orig){
            $height =  $width/$ratio_orig;
        }else{
            $width = $height*$ratio_orig;
        }
        if($width_orig<$width){
            $width = $width_orig;
            $height = $height_orig;
        }

        $image_p = imagecreatetruecolor($width, $height);
        $bgcolor=ImageColorAllocate($image_p, 255, 255, 255);
        //
        imageFill($image_p, 5, 5, $bgcolor);

        if ($size_img[2]==2){ $image = imagecreatefromjpeg($filename);}
        else if ($size_img[2]==1){  $image = imagecreatefromgif($filename);}
        else if ($size_img[2]==3) { $image = imagecreatefrompng($filename); }

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        // Output

        if ($is_height_width)
            $users_img = $dir_without_ext.'-'.$width_orig_custom.'x'.$height_orig_custom.'.jpg';
        else
            $users_img = $dir_without_ext.'.jpg';

        if ($size_img[2]==2)  imagejpeg($image_p, $users_img, 100);
        else if ($size_img[2]==1)  imagejpeg($image_p, $users_img, 100);
        else if ($size_img[2]==3)  imagejpeg($image_p, $users_img, 100);
        imageDestroy($image_p);
        imageDestroy($image);
        //unlink($filename);

    }



    public function fileErrorMsg($data){
        $error = $data['error'];

        switch($error)
        {
            case '1':
                $error_text = 'The size of the uploaded file exceeds the '.ini_get('upload_max_filesize').'b';
                break;
            case '2':
                $error_text = 'The size of  the uploaded file exceeds the specified parameter  MAX_FILE_SIZE in HTML form.';
                break;
            case '3':
                $error_text = 'Loaded only a portion of the file';
                break;
            case '4':
                $error_text = 'The file was not loaded (in the form user pointed the wrong path  to the file). ';
                break;
            case '6':
                $error_text = 'Invalid  temporary directory.';
                break;
            case '7':
                $error_text = 'Error writing file to disk';
                break;
            case '8':
                $error_text = 'File download aborted';
                break;
            case '999':
            default:
                $error_text = 'Unknown error code!';
                break;
        }

        return $error_text;
    }

    private function _eraseTmpDirectoryFromOldFiles($data){
        $path = $data['path'];

        $prev_cwd = getcwd();

        @chdir($path);
        $all_files = glob("*");

        $now  = date('Y-m-d H:i:s');
        $now = strtotime($now);


        foreach ($all_files as $filename) {


            $time_modified = filemtime($path.$filename);
            $time_modified = $time_modified+(86400*3); // delete files old than 3 days

            if($now > $time_modified){
                unlink($path.$filename); // delete old files
            }

        }


        @chdir($prev_cwd);



    }

    public function uploadTmpFile($data){
        $files = $data['files'];

        $message = '';
        $status = '';
        $name_file = '';
        $size_file = '';
        $is_error = 0;

        $dir_name_to_upload = dirname(__FILE__).$this->path_img_cloud.'tmp'.DIRECTORY_SEPARATOR;

        // delete old files , clear directory tmp from spam :)
        $this->_eraseTmpDirectoryFromOldFiles(array('path'=>$dir_name_to_upload));
        // delete old files , clear directory tmp from spam :)

        $allowed = $this->_accepted_files;

        if (isset($files) && $files['error'][0] == 0) {


            $extension = pathinfo($files['name'][0], PATHINFO_EXTENSION);

            if (!in_array(Tools::strtolower($extension), $allowed)) {
                $message = 'Wrong file format, please try again!';
                $is_error = 1;
                $status = 'error';
            }


            if (!is_dir($dir_name_to_upload) && $is_error = 0) {
                $message = 'Wrong directory: ' . $dir_name_to_upload . ', please try again!';
                $is_error = 1;
                $status = 'error';

            }

            if ($is_error == 0) {
                move_uploaded_file($files['tmp_name'][0], $dir_name_to_upload . $files['name'][0]);

                $size_file = filesize($dir_name_to_upload . $files['name'][0]);
                $name_file = $files['name'][0];

            }

        } else {

            $message = $this->fileErrorMsg(array('error' => $files['error'][0]));
            $is_error = 1;
            $status = 'error';
        }

        return array('is_error'=>$is_error,'status'=>$status,'message'=>$message, 'size_file'=>$size_file,'name_file'=>$name_file);
    }

    public function saveFiles2Review($data){

        $filesrev = $data['filesrev'];

        if(count($filesrev)>0 && !empty($filesrev)){

            $id_review = $data['id_review'];
            $id_product = $data['id_product'];
            $id_lang = $data['id_lang'];
            foreach($filesrev as $file){
                $this->saveFile2Review(array('id_review'=>$id_review,'id_product'=>$id_product,'file'=>$file,'id_lang'=>$id_lang));
            }
        }

    }


    public function saveFile2Review($data = null){

        $id_product = $data['id_product'];
        $id_review = $data['id_review'];
        $file_name = $data['file'];
        $id_lang = $data['id_lang'];

        $product = new Product($id_product,false,$id_lang);
        $id_category_default = (int)$product->id_category_default;

        $old_file_location = dirname(__FILE__).$this->path_img_cloud.'tmp'.DIRECTORY_SEPARATOR;



        // create folders //

        $prev_cwd = getcwd();

        $dir_name_to_upload_file = dirname(__FILE__).$this->path_img_cloud.'files'.DIRECTORY_SEPARATOR;
        @chdir($dir_name_to_upload_file);

        ## create dolder based on id_category ##
        $module_dir_files_category = $dir_name_to_upload_file.$id_category_default.DIRECTORY_SEPARATOR;
        @mkdir($module_dir_files_category, 0777);
        @chdir($module_dir_files_category);

        ## create dolder based on id_product ##
        $module_dir_files_category_product = $dir_name_to_upload_file.$id_category_default.DIRECTORY_SEPARATOR.$id_product.DIRECTORY_SEPARATOR;
        @mkdir($module_dir_files_category_product, 0777);
        @chdir($module_dir_files_category_product);

        $module_dir_files_category_product_review = $dir_name_to_upload_file.$id_category_default.DIRECTORY_SEPARATOR.$id_product.DIRECTORY_SEPARATOR.$id_review.DIRECTORY_SEPARATOR;
        @mkdir($module_dir_files_category_product_review, 0777);
        @chdir($module_dir_files_category_product_review);

        @chdir($prev_cwd);

        // create folders //


        srand((double)microtime()*1000000);
        $uniq_name_image = uniqid(rand());
        $uniq_name_image = $id_review.'-'.$uniq_name_image;



        if(!file_exists(($old_file_location.$file_name))) return;

        // copy from "tmp" folder to "files" folder
        copy($old_file_location.$file_name,$module_dir_files_category_product_review.$file_name);


        $this->copyImage(
            array(
                'dir_without_ext'=>$module_dir_files_category_product_review.$uniq_name_image,
                'name'=>$module_dir_files_category_product_review.$file_name,
                'width'=>$this->_width_files,
                'height'=>$this->_height_files,
            )
        );

        $this->saveFile2ReviewInDB(array(

                'full_path' => $this->path_img_cloud_site.'files/'.$id_category_default.'/'.$id_product.'/'.$id_review.'/'.
                                $uniq_name_image.'-'.$this->_width_files.'x'.$this->_height_files.'.jpg',

                'id_review'=>$id_review,
            )
        );

        //delete old files
        unlink($old_file_location.$file_name);
        unlink($module_dir_files_category_product_review.$file_name);


    }

    public function saveFile2ReviewInDB($data){
        $full_path = $data['full_path'];
        $id_review = $data['id_review'];

        $sql = 'INSERT into `' . _DB_PREFIX_ . 'gsnipreview_files2review` SET
						   id_review = ' . (int)($id_review) . ',
						   full_path = "' . pSQL($full_path) . '"
						   ';
        Db::getInstance()->Execute($sql);
    }


    public function getFiles2Review($data){
        $id_review = $data['id_review'];
        $sql = '
			SELECT pc.id_gsnipreview_files2review as id , pc.full_path
			FROM `'._DB_PREFIX_.'gsnipreview_files2review` pc
            WHERE pc.id_review = '.(int)$id_review.'
			ORDER BY pc.`id_gsnipreview_files2review` DESC

			';
        $items = Db::getInstance()->ExecuteS($sql);


        ## resize image ##
        foreach($items as $_k => $_item){
            $full_path = $_item['full_path'];

            $small_image_tmp = explode("/",$full_path);
            $small_image_tmp = end($small_image_tmp);


            $new_small_file_name_path = current(explode(".",$small_image_tmp));

            $new_small_file_name = $new_small_file_name_path."-small-".$this->_width_files_small."x".$this->_height_files_small.".jpg";

            $module_dir_files_category_product_review = str_replace($small_image_tmp,"",$full_path);

            $pre_path = $module_dir_files_category_product_review;



            $path_to_imageon_the_site = $module_dir_files_category_product_review.$new_small_file_name;

            $files_structure_path = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;
            $module_dir_files_category_product_review = $files_structure_path.$module_dir_files_category_product_review;


            $path_to_small_img = $module_dir_files_category_product_review.$new_small_file_name;

            if(!file_exists(($path_to_small_img))) {

                $this->copyImage(
                    array(
                        'dir_without_ext' => $module_dir_files_category_product_review.$new_small_file_name_path."-small",
                        'name' => $files_structure_path.$pre_path.$small_image_tmp,
                        'width' => $this->_width_files_small,
                        'height' => $this->_height_files_small,
                    )
                );
            }


            $items[$_k]['small_path']=$path_to_imageon_the_site;
        }
        ## resize image ##

        return $items;
    }

    public function deleteTmpFile($data){
        $name = $data['name'];
        $old_file_location = dirname(__FILE__).$this->path_img_cloud.'tmp'.DIRECTORY_SEPARATOR;
        unlink($old_file_location.$name);
    }

    public function deleteFile($data){
        $id = $data['id'];
        $sql = '
                SELECT pc.full_path FROM `'._DB_PREFIX_.'gsnipreview_files2review` pc WHERE pc.id_gsnipreview_files2review = '.(int)$id.'
                ';

        $item = Db::getInstance()->ExecuteS($sql);
        $full_path = $item[0]['full_path'];

        $path = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.$full_path;
        unlink($path);


        $sql_delete = '
                DELETE FROM `'._DB_PREFIX_.'gsnipreview_files2review` WHERE id_gsnipreview_files2review = '.(int)$id.'
                ';
        Db::getInstance()->Execute($sql_delete);


    }


    public function isExistsReviewByCustomer($data){
        $is_customer = $data['id_customer'];
        //$ids_products = (Tools::strlen($data['id_product']) > 0)?$data['id_product']:array(0);
        $ids_products = !empty($data['id_product'])?$data['id_product']:array(0);

        $sql = '
			SELECT COUNT(`id`) AS "count"
			FROM `'._DB_PREFIX_.'gsnipreview`
			WHERE id_customer = '.(int)$is_customer.' and id_product IN('.implode(",", array_map('pSQL',$ids_products)).')
			';



        $data_is_exists = Db::getInstance()->getRow($sql);
        return $data_is_exists['count'];
    }


    public function getAllProductsForReviews(){
        $all_products = Db::getInstance()->ExecuteS('
				SELECT DISTINCT `id_product`
				FROM `'._DB_PREFIX_.'gsnipreview` pc
				');


        return $all_products;
    }

    public function getAllLangForReviews(){
        $all_lang = Db::getInstance()->ExecuteS('
				SELECT DISTINCT `id_lang`
				FROM `'._DB_PREFIX_.'gsnipreview` pc
				');


        return $all_lang;
    }


    public function getProduct($data){

        $id = (int) $data['id'];
        $id_shop = isset($data['id_shop'])?(int) $data['id_shop']:0;
        $cookie = $this->context->cookie;

        $id_lang = (int)($cookie->id_lang);
        $result = Db::getInstance()->ExecuteS('
	            SELECT p.id_product, pl.`link_rewrite`, pl.`name`
	            FROM `'._DB_PREFIX_.'product` p
	            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')
	            WHERE p.`active` = 1 AND p.`id_product` = '.(int)($id));

        $data_all = array();
        foreach($result as $products){

            $id_product= isset($products['id_product'])?$products['id_product']:'';
            $link_rewrite= isset($products['link_rewrite'])?$products['link_rewrite']:'';
            $_category= isset($products['category'])?$products['category']:'';
            $_category = htmlspecialchars($_category);
            //$_ean13= isset($products['ean13'])?$products['ean13']:'';
            $link = new Link();
            $_url = $link->getProductLink($id_product,
                $link_rewrite,
                $_category,
                null,
                $id_lang,
                $id_shop
            );


            $_name = isset($products['name'])?$products['name']:'';
            $_name = addslashes($_name);
            $_url = isset($_url)?$_url:'';

            $data_all[] = array('link' => $_url, 'name' => $_name);

        }



        return array('product' => $data_all);
    }
}