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

class storereviews extends Module{

    private $_width = 85;
    private $_height = 85;
	private $_name;
	private $_http_host;
    private $_is_cloud;

    private $_prefix;

    private $_table_name = 'gsnipreview_storereviews';
	
	public function __construct(){
		$this->_name = "gsnipreview";
		if(version_compare(_PS_VERSION_, '1.6', '>')){
			$this->_http_host = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__; 
		} else {
			$this->_http_host = _PS_BASE_URL_.__PS_BASE_URI__;
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
        } else {
            $this->path_img_cloud = DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR.$this->_name.DIRECTORY_SEPARATOR;

        }



        if (version_compare(_PS_VERSION_, '1.5', '<')){
			require_once(_PS_MODULE_DIR_.$this->_name.'/backward_compatibility/backward.php');
		}
		
		$this->initContext();
	}
	
	private function initContext()
	{
		$this->context = Context::getContext();
	}


    public function getStepForMyStoreReviews(){
        return 5;
    }

	public function saveTestimonial($_data){
		
		$cookie = $this->context->cookie;
		
		$id_lang = (int)($cookie->id_lang);

        $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;

        $post_images = isset($_data['post_images'])?$_data['post_images']:null;

		$name = $_data['name'];
		$email = $_data['email'];
		$web = $_data['web'];
		$text_review =  $_data['text_review'];
		$company = $_data['company'];
	    $address = $_data['address'];
	    $rating = $_data['rating'];
	    $country = $_data['country'];
	    $city = $_data['city'];
		
		$sql = 'INSERT into `'._DB_PREFIX_.''.$this->_table_name.'` SET
							   `name` = \''.pSQL($name).'\',
							   `email` = \''.pSQL($email).'\',
							   `web` = \''.pSQL($web).'\',
							   `message` = \''.pSQL($text_review).'\',
							   `company` = \''.pSQL($company).'\',
							   `address` = \''.pSQL($address).'\',
							   `country` = \''.pSQL($country).'\',
							   `city` = \''.pSQL($city).'\',
							   `id_shop` = \''.(int)($this->getIdShop()).'\',
							   `id_lang` = \''.(int)($id_lang).'\',
							   `rating` = \''.(int)($rating).'\',
							   `id_customer` = \''.(int)($id_customer).'\',
							   `date_add` = NULL
							   ';
		Db::getInstance()->Execute($sql);

        $id_review = Db::getInstance()->Insert_ID();


        $this->saveImageAvatar(array('id'=>$id_review,'id_customer'=>$id_customer,'post_images'=>$post_images,'is_storereviews'=>1));

        include_once(dirname(__FILE__).'/../gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();
        $_prefix = $obj_gsnipreview->getPrefixShopReviews();


        if(Configuration::get($this->_name.'noti'.$_prefix) == 1){
			

		$_data_translate = $obj_gsnipreview->translateItems();


        $subject = Configuration::get($this->_name . 'newtest'.$_prefix.'_' . $id_lang);

        $message = "<span style='color:#333'><strong>".$_data_translate['message'].': </strong></span>'.$text_review;



        if(Configuration::get($this->_name.'is_web') == 1){
             $web = isset($_data['web']) ? $_data['web'] :'' ;
              if(Tools::strlen($web)>0)
                 $message .= "<br/><br/><b>".$_data_translate['web'].": </b>".$web;
        }

        if(Configuration::get($this->_name.'is_company') == 1){
            $company = isset($_data['company']) ? $_data['company'] :'' ;
             if(Tools::strlen($company)>0)
                $message .= "<br/><br/><b>".$_data_translate['company'].": </b>".$company;
        }

        if(Configuration::get($this->_name.'is_addr') == 1){
             $address = isset($_data['address']) ? $_data['address'] :'' ;
             if(Tools::strlen($address)>0)
                $message .= "<br/><br/><b>".$_data_translate['address'].": </b>".$address;
        }

        if(Configuration::get($this->_name.'is_country') == 1){
            $country = isset($_data['country']) ? $_data['country'] :'' ;
            if(Tools::strlen($country)>0)
               $message .= "<br/><br/><b>".$_data_translate['country'].": </b>".$country;
        }

       if(Configuration::get($this->_name.'is_city') == 1){
           $city = isset($_data['city']) ? $_data['city'] :'' ;
           if(Tools::strlen($city)>0)
               $message .= "<br/><br/><b>".$_data_translate['city'].": </b>".$city;
       }

		
		/* Email generation */
		$templateVars = array(
			'{email}' => $email,
			'{name}' => $name,

            '{text}' => $message,

		);
		
			$iso_lng = Language::getIsoById((int)($id_lang));
			
			$dir_mails = _PS_MODULE_DIR_ . '/' . $this->_name . '/' . 'mails/';
			
			if (is_dir($dir_mails . $iso_lng . '/')) {
				$id_lang_current = $id_lang;
			}
			else {
				$id_lang_current = Language::getIdByIso('en');
			}
					
		/* Email sending */
		Mail::Send($id_lang_current, 'testimony', $subject, $templateVars, 
			Configuration::get($this->_name.'mail'.$_prefix), 'Testimonial Form', $email, $name,
			NULL, NULL, dirname(__FILE__).'/../mails/');



           ## send thank you email by customer ##
           $data_thank_you = array('name'=>$name,'email'=>$email,'id_lang'=>$id_lang);
           $this->sendNotificationThankyouTestimonial($data_thank_you);
            ## send thank you email by customer ##


		}
		
		
	}


    public function sendNotificationThankyouTestimonial($data = null){
        $cookie = $this->context->cookie;

        $id_lang = isset($data['id_lang'])?$data['id_lang']:(int)($cookie->id_lang);


        include_once(dirname(__FILE__).'/../gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();


        $_prefix = $obj_gsnipreview->getPrefixShopReviews();


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

        Mail::Send($id_lang_current, 'testimony-thank-you', $subject_thank_you, $templateVars,
            $email, 'Thank you Form', NULL, NULL,
            NULL, NULL, dirname(__FILE__).'/../mails/');



    }

	public function getTestimonials($_data){
		
		$start = $_data['start'];
		$step = $_data['step'];
		$admin = isset($_data['admin'])?$_data['admin']:null;

		
		$cookie = $this->context->cookie;
		
		$id_lang = (int)($cookie->id_lang);
		
		if($admin){
            $sql_admin = '
			SELECT pc.*,
			(SELECT ga2c.avatar_thumb from '._DB_PREFIX_.''.$this->_name.'_avatar2customer ga2c
                                                    WHERE ga2c.id_customer = pc.id_customer
                    ) as avatar_thumb
			FROM `'._DB_PREFIX_.''.$this->_table_name.'` pc
			WHERE pc.`is_deleted` = 0
			ORDER BY pc.`date_add` DESC LIMIT '.(int)($start).' ,'.(int)($step).'';




			$reviews = Db::getInstance()->ExecuteS($sql_admin);


            foreach($reviews as $i => $_item) {
                $id_customer = $_item['id_customer'];

                $avatar = $_item['avatar'];
                $info_path = $this->_getAvatarPath(array('avatar' => $avatar, 'id_customer'=>$id_customer ));
                $reviews[$i]['avatar'] = $info_path['avatar'];
                $reviews[$i]['is_show_ava'] = $info_path['is_show'];

            }


			$data_count_reviews = Db::getInstance()->getRow('
			SELECT COUNT(`id`) AS "count"
			FROM `'._DB_PREFIX_.''.$this->_table_name.'`
			WHERE is_deleted = 0
			');
		}else{

            $frat = isset($_data['frat'])?(int)$_data['frat']:null;
            $sql_rating = '';
            if($frat) {
                if ($frat > 5)
                    $frat = 5;
                $sql_rating = ' rating = '.(int)$frat.' AND ';
            }


            $is_search = isset($_data['is_search'])?$_data['is_search']:0;
            $search = isset($_data['search'])?$_data['search']:'';


            $sql_condition_search = '';
            if($is_search == 1){
                $sql_condition_search = " (
	    		   LOWER(message) LIKE BINARY LOWER('%".pSQL(trim($search))."%')
	    		   OR
	    		   LOWER(response) LIKE BINARY LOWER('%".pSQL(trim($search))."%')
	    		   ) AND ";
            }

            $id_customer = isset($_data['id_customer'])?$_data['id_customer']:0;
            $sql_customer = '';
            if($id_customer){
                $sql_customer = ' id_customer = '.(int)($id_customer).' AND ';
            }

            $sql = '
			SELECT pc.*
			FROM `'._DB_PREFIX_.''.$this->_table_name.'` pc
			WHERE pc.active = 1 AND pc.`is_deleted` = 0 AND '.$sql_rating.' '.$sql_condition_search.' '.$sql_customer.'
			`id_shop` = \''.(int)($this->getIdShop()).'\' AND `id_lang` = \''.(int)($id_lang).'\'
			ORDER BY pc.`date_add` DESC LIMIT '.(int)($start).' ,'.(int)($step).'';



			$reviews = Db::getInstance()->ExecuteS($sql);


            $i=0;
            foreach($reviews as $_item) {
                $id_customer = $_item['id_customer'];
                $is_buy = 0;
                if($id_customer) {
                    $is_buy = $this->checkProductBought(array('id_customer' => $id_customer));
                }
                //$is_buy = 1; //for test
                $reviews[$i]['is_buy'] = $is_buy;


                $avatar = $_item['avatar'];
                $info_path = $this->_getAvatarPath(array('avatar' => $avatar, 'id_customer'=>$id_customer ));
                $reviews[$i]['avatar'] = $info_path['avatar'];
                $reviews[$i]['is_show_ava'] = $info_path['is_show'];

                $i++;
            }

            $sql_count = '
			SELECT COUNT(`id`) AS "count"
			FROM `'._DB_PREFIX_.''.$this->_table_name.'`
			WHERE active = 1 AND is_deleted = 0 AND '.$sql_rating.' '.$sql_condition_search.'  '.$sql_customer.'
			`id_shop` = \''.(int)($this->getIdShop()).'\' AND `id_lang` = \''.(int)($id_lang).'\'
			';

			$data_count_reviews = Db::getInstance()->getRow($sql_count);
		}
		 return array('reviews' => $reviews, 'count_all_reviews' => $data_count_reviews['count'] );
	}




    public function isExistsReviewByCustomer($data){
        $is_customer = $data['id_customer'];

        $data_is_exists = Db::getInstance()->getRow('
			SELECT COUNT(`id`) AS "count"
			FROM `'._DB_PREFIX_.''.$this->_table_name.'`
			WHERE is_deleted = 0 and id_customer = '.(int)$is_customer.'
			');
        return $data_is_exists['count'];
    }
	
	public function getItem($_data){
		$id = $_data['id'];

        $items = Db::getInstance()->ExecuteS('
			SELECT pc.*
			FROM `'._DB_PREFIX_.''.$this->_table_name.'` pc
			WHERE pc.`is_deleted` = 0 AND pc.`id` = '.(int)($id).'');


        $cookie = $this->context->cookie;


        $i=0;
        foreach($items as $_item) {
            $id_lang = ($_item['id_lang'] != 0) ? $_item['id_lang'] : (int)($cookie->id_lang);

            $name_lang = Language::getLanguage((int)($id_lang));
            $items[$i]['name_lang'] = $name_lang['name'];


            $id_customer = isset($_item['id_customer'])?$_item['id_customer']:0;
            $name = '';
            if($id_customer) {
                $customer_data = $this->getInfoAboutCustomer(array('id_customer' => $id_customer,'is_full'=>1));
                $name = $customer_data['customer_name'];
            }
            $items[$i]['customer_name'] = $name;


            ## user functional ###
            $user_url = '';
            if($id_customer) {

                include_once(dirname(__FILE__).'/gsnipreviewhelp.class.php');
                $obj_gsnipreviewhelp = new gsnipreviewhelp();

                $data_seo_url = $obj_gsnipreviewhelp->getSEOURLs(array('id_lang' => $id_lang));
                $user_url = $data_seo_url['user_url'];
            }

            $items[$i]['user_url'] = $user_url;



            include_once(dirname(__FILE__).'/userprofileg.class.php');
            $obj_userprofileg = new userprofileg();

            $avatar = $_item['avatar'];

            $info_path = $obj_userprofileg->getAvatarPath(array('avatar' => $avatar, 'id_customer'=>$id_customer ));

            $items[$i]['avatar'] = $info_path['avatar'];
            $items[$i]['is_exist'] = $info_path['is_exist'];
            $items[$i]['is_show_ava'] = $info_path['is_show'];
            ## user functional ###

        }


	   return array('reviews' => $items);
	}
	
	public function setPublsh($data){
		$id = $data['id'];
		$active = $data['active'];
		
		$sql = 'UPDATE `'._DB_PREFIX_.''.$this->_table_name.'`
	    				SET
				   		active = '.(int)($active).'
				   		WHERE id = '.(int)($id).' 
						   ';
			Db::getInstance()->Execute($sql);
	}
	
	public function deteleItem($data){
		$id = $data['id'];
		$sql = 'UPDATE `'._DB_PREFIX_.''.$this->_table_name.'`
	    						SET
						   		is_deleted = 1
						   		WHERE id = '.(int)($id).''; 
		Db::getInstance()->Execute($sql);
	}
	
	public function getIdShop(){
		$id_shop = 0;
		if(version_compare(_PS_VERSION_, '1.5', '>'))
			$id_shop = Context::getContext()->shop->id;
		return $id_shop;
	} 
	
	public function updateItem($data){
		$name = $data['name'];
		$email = $data['email'];
		$web = $data['web'];
		$message = $data['message'];
		$publish = $data['publish'];
		$id = $data['id'];
		$company = $data['company'];
		$address = $data['address'];
		
		$country = $data['country'];
		$city = $data['city'];

        $response = $data['response'];
        $is_noti = $data['is_noti'];
        $is_show = $data['is_show'];

        $post_images = $data['post_images'];

        $date_add = date('Y-m-d H:i:s',strtotime($data['date_add']));
		
		$rating = $data['rating'];
		if($rating>5) $rating = 5;
		if($rating<0) $rating = 1;
		
		$sql_condition_web = '';
		if(Configuration::get($this->_name.'is_web') == 1){
			$sql_condition_web = '`web` = "'.pSQL($web).'",';
		}
		
		$sql_condition_company = '';
		if(Configuration::get($this->_name.'is_company') == 1){
			$sql_condition_company = '`company` = "'.pSQL($company).'",';
		}
		
		$sql_condition_address = '';
		if(Configuration::get($this->_name.'is_addr') == 1){
			$sql_condition_address = '`address` = "'.pSQL($address).'",';
		}
		
		$sql_condition_country = '';
		if(Configuration::get($this->_name.'is_country') == 1){
			$sql_condition_country = '`country` = "'.pSQL($country).'",';
		}
		
		$sql_condition_city = '';
		if(Configuration::get($this->_name.'is_city') == 1){
			$sql_condition_city = '`city` = "'.pSQL($city).'",';
		}
		
		$sql = 'UPDATE `'._DB_PREFIX_.''.$this->_table_name.'`
	    						SET `name` = "'.pSQL($name).'",
						   			`email` = "'.pSQL($email).'",
						   			`rating` = "'.(int)($rating).'",
						   			'.$sql_condition_web.'
						   			`message` = "'.pSQL($message).'",
						   			`date_add` = "'.pSQL($date_add).'",
						   			`response` = "'.pSQL($response).'",
						   			`is_show` = "'.pSQL($is_show).'",
						   			'.$sql_condition_company.'
									'.$sql_condition_address.'
									'.$sql_condition_country.'
									'.$sql_condition_city.'
									`active` = '.(int)($publish).'			   			 
						   		WHERE id = '.(int)($id).''; 
        Db::getInstance()->Execute($sql);


        include_once(dirname(__FILE__).'/../gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();
        if($obj_gsnipreview->is_demo == 0) {

            //$this->saveImage(array('id' => $id, 'post_images' => $post_images));

            include_once(dirname(__FILE__).'/userprofileg.class.php');
            $obj = new userprofileg();

            $id_customer = isset($data['id_customer'])?$data['id_customer']:0;
            $obj->saveImageAvatar(array('id' => $id, 'post_images' => $post_images,'id_customer'=>$id_customer,'is_storereviews'=>1));
        }

        if($is_noti && Tools::strlen(trim($response))>0){
            // send email
            $this->sendNotificationResponseTestimonial(array('id'=>$id));
        }
			
	}

    public function sendNotificationResponseTestimonial($data = null){


            include_once(dirname(__FILE__).'/../gsnipreview.php');
            $obj_gsnipreview = new gsnipreview();
            $_data_translate = $obj_gsnipreview->translateItems();


            $_prefix = $obj_gsnipreview->getPrefixShopReviews();

            $id = $data['id'];

            $_data_item_tmp = $this->getItem(array('id'=>$id));
            $_data = $_data_item_tmp['reviews'][0];


            $cookie = $this->context->cookie;

            $id_lang = (int)($cookie->id_lang);
            $id_lang = isset($_data['id_lang']) ? $_data['id_lang'] :$id_lang ;

            $name = isset($_data['name']) ? $_data['name'] :'' ;
            $email = isset($_data['email']) ? $_data['email'] :@Configuration::get('PS_SHOP_EMAIL') ;



            $iso_lng = Language::getIsoById((int)($id_lang));



            $data_url = $this->getSEOURLs(array('iso_lng'=>$iso_lng));
            $items_url = $data_url['testimonials_url'];

            $response = isset($_data['response']) ? $_data['response'] :'' ;

            $message = isset($_data['message'])?"<span style='color:#333'><strong>".$_data_translate['message'].': </strong></span>'.$_data['message']:"";


            if(Configuration::get($this->_name.'is_web') == 1){
                $web = isset($_data['web']) ? $_data['web'] :'' ;
                if(Tools::strlen($web)>0)
                    $message .= "<br/><br/><b>".$_data_translate['web'].": </b>".$web;
            }

            if(Configuration::get($this->_name.'is_company') == 1){
                $company = isset($_data['company']) ? $_data['company'] :'' ;
                if(Tools::strlen($company)>0)
                    $message .= "<br/><br/><b>".$_data_translate['company'].": </b>".$company;
            }

            if(Configuration::get($this->_name.'is_addr') == 1){
                $address = isset($_data['address']) ? $_data['address'] :'' ;
                if(Tools::strlen($address)>0)
                    $message .= "<br/><br/><b>".$_data_translate['address'].": </b>".$address;

            }

            if(Configuration::get($this->_name.'is_country') == 1){
                $country = isset($_data['country']) ? $_data['country'] :'' ;
                if(Tools::strlen($country)>0)
                    $message .= "<br/><br/><b>".$_data_translate['country'].": </b>".$country;

            }

            if(Configuration::get($this->_name.'is_city') == 1){
                $city = isset($_data['city']) ? $_data['city'] :'' ;
                if(Tools::strlen($city)>0)
                    $message .= "<br/><br/><b>".$_data_translate['city'].": </b>".$city;

            }


            /* Email generation */
            $templateVars = array(
                '{name}' => $name,
                '{text}' => $message,
                '{response}' => $response,
                '{link}' => $items_url,

            );

            //echo "<pre>"; var_dump($templateVars); exit;

            /* Email sending */




            $dir_mails = _PS_MODULE_DIR_ . '/' . $this->_name . '/' . 'mails/';

            if (is_dir($dir_mails . $iso_lng . '/')) {
                $id_lang_current = $id_lang;
            }
            else {
                $id_lang_current = Language::getIdByIso('en');
            }

            $subject_response = Configuration::get($this->_name . 'resptest'.$_prefix.'_' . $id_lang);



            Mail::Send($id_lang_current, 'response-testim', $subject_response, $templateVars,
                $email, 'Response Form', NULL, NULL,
                NULL, NULL, dirname(__FILE__).'/../mails/');



    }
	
	public function PageNav($start,$count,$step, $_data =null )
	{
		$_admin = isset($_data['admin'])?$_data['admin']:null;

		$res = '';
		$product_count = $count;
		include_once(dirname(__FILE__).'/../gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();
		$_data_translate = $obj_gsnipreview->translateItems();
		$page_translate = $_data_translate['page'];


		$res .= '<div class="pages ">';
		$res .= '<span>'.$page_translate.': </span>';
		$res .= '<span class="nums">';
		
		$start1 = $start;
			for ($start1 = ($start - $step*4 >= 0 ? $start - $step*4 : 0); $start1 < ($start + $step*5 < $product_count ? $start + $step*5 : $product_count); $start1 += $step)
				{
					$par = (int)($start1 / $step) + 1;
					if ($start1 == $start)
						{
						
						$res .= '<b>'. $par .'</b>';
						}
					else
						{
							if($_admin){
								$currentIndex = $_data['currentIndex'];
								$token = $_data['token'];
								$res .= '<a href="'.$currentIndex.'&page='.($start1 ? $start1 : 0).'&configure='.$this->_name.'&token='.$token.'" >'.$par.'</a>';
							}
						}
				}
		
		$res .= '</span>';
		$res .= '</div>';
		
		
		return $res;
	}


    public function PageNav17($start,$count,$step, $_data =null )
    {
        $frat = isset($_data['frat'])?$_data['frat']:null;
        $is_search = isset($_data['is_search'])?$_data['is_search']:null;
        $search = isset($_data['search'])?$_data['search']:null;
        $prefix = isset($_data['prefix'])?$_data['prefix']:'';
        $user_url = isset($_data['user_url'])?$_data['user_url']:null;
        $is_my_storereviews= isset($_data['is_my_storereviews'])?$_data['is_my_storereviews']:0;


        $product_count = $count;

        $data_url = $this->getSEOURLs();


        $full_array = array();

        $start1 = $start;
        for ($start1 = ($start - $step*4 >= 0 ? $start - $step*4 : 0); $start1 < ($start + $step*5 < $product_count ? $start + $step*5 : $product_count); $start1 += $step)
        {
            $par = (int)($start1 / $step) + 1;
            if ($start1 == $start)
            {

                $full_array[] = array('page'=>$par,'is_b'=>1);
            }
            else
            {

                    $frat_question = '';
                    $frat_amp = '';
                    if($frat) {
                        $frat_question = '?frat'.$prefix.'=' . $frat;
                        $frat_amp = '&frat'.$prefix.'=' . $frat;
                    }

                    $search_amp = '';
                    //$search_question = '';
                    if($is_search){
                        $search_amp = '&search'.$prefix.'='.$search;
                        //$search_question = '?search'.$prefix.'='.$search;
                    }

                    if($user_url){
                        $items_url = $user_url;
                    } else {
                        $items_url = $data_url['testimonials_url'];
                    }

                    $amp_or_q = '?';


                    if($user_url && !$is_my_storereviews){
                        $amp_or_q = '&';
                    }


                    if(version_compare(_PS_VERSION_, '1.5', '>')) {


                        if(version_compare(_PS_VERSION_, '1.6', '<')) {

                            $p = ($start1 ? $amp_or_q.'p'.$prefix.'='.$par.$frat_amp.$search_amp : $amp_or_q.'p'.$prefix.'=0'.$frat_question.$search_amp);
                        } else {


                            if($user_url) {

                                $p = ($start1 ? $amp_or_q.'p' . $prefix . '=' . $par . $frat_amp.$search_amp : $amp_or_q.'p'.$prefix.'=0' . $frat_amp . $search_amp);
                            } else {

                                //$p = ($start1 ? '/'.$par : '').$frat_question.((Tools::strlen($frat_question)>0)?$search_amp:$search_question);
                                $p = ($start1 ? '?p' . $prefix . '=' . $par . $frat_amp.$search_amp : '?p'.$prefix.'=0' . $frat_amp . $search_amp);
                            }
                        }


                    } else {



                        $p = ($start1 ? $amp_or_q.'p'.$prefix.'='.$par.$frat_amp : ''.$frat_question.$search_amp);

                    }

                    $full_array[] = array('page'=>$par,'is_b'=>0,'url'=>$items_url . $p,'title'=>$par);

                    //$res .= '<a href="'.$items_url.$p.'" title="'.$par.'">'.$par.'</a>';


            }
        }



        return $full_array;
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
    
	public function createRSSFile($post_title,$post_description,$post_link,$post_pubdate)
	{
		
		
		$returnITEM = "<item>\n";
		# this will return the Title of the Article.
		$returnITEM .= "<title><![CDATA[".$post_title."]]></title>\n";
		# this will return the Description of the Article.
		$returnITEM .= "<description><![CDATA[".$post_description."]]></description>\n";
		# this will return the URL to the post.
		$returnITEM .= "<link>".$post_link."</link>\n";
		
		$returnITEM .= "<pubDate>".$post_pubdate."</pubDate>\n";
		$returnITEM .= "</item>\n";
		return $returnITEM;
	}
	
	public function getItemsForRSS(){
			
			$step = Configuration::get($this->_name.'n_rssitemst');

            $data_url = $this->getSEOURLs();
            $testimonials_url = $data_url['testimonials_url'];
			
			$cookie = $this->context->cookie;
			$current_language = (int)$cookie->id_lang;
			
			/*$all_laguages = Language::getLanguages(true);

			if(sizeof($all_laguages)>1)
				$_iso_lng = Language::getIsoById((int)($current_language))."/";
			else
				$_iso_lng = '';*/
			
			$sql  = '
			SELECT pc.*
			FROM `'._DB_PREFIX_.''.$this->_table_name.'` pc
			WHERE pc.active = 1 AND pc.`is_deleted` = 0 AND
			`id_shop` = \''.(int)($this->getIdShop()).'\' AND `id_lang` = \''.(int)($current_language).'\'
			ORDER BY pc.`date_add` DESC LIMIT '.(int)($step);
			
			$items = Db::getInstance()->ExecuteS($sql);	
			
			foreach($items as $k1=>$_item){
					
		    		if($current_language == $_item['id_lang']){
		    			$items[$k1]['title'] = $_item['name'];
		    			$items[$k1]['seo_description'] = htmlspecialchars(strip_tags($_item['message']));
		    			$items[$k1]['pubdate'] = date('D, d M Y H:i:s +0000',strtotime($_item['date_add']));
		    			
		    			$items[$k1]['page'] = $testimonials_url;

		    			
		    		} 
		    	
				
			}
			
			
			return array('items' => $items);
	}
	
public function getLangISO(){
        $cookie = $this->context->cookie;
        $id_lang = (int)$cookie->id_lang;

        $all_laguages = Language::getLanguages(true);

        if(sizeof($all_laguages)>1)
            $iso_lang = Language::getIsoById((int)($id_lang))."/";
        else
            $iso_lang = '';

        return $iso_lang;
    	
    }
    


    public function getSEOURLs(){

        include_once(dirname(__FILE__).'/gsnipreviewhelp.class.php');
        $obj_gsnipreviewhelp = new gsnipreviewhelp();

        $data_urls = $obj_gsnipreviewhelp->getSEOURLs();
        $testimonials_url = $data_urls['storereviews_url'];
        $my_account = $data_urls['my_account'];


        return array(
            'testimonials_url' => $testimonials_url,'my_account' => $my_account,

        );
    }

    public function getHttpost(){
        if(version_compare(_PS_VERSION_, '1.5', '>')){
            $custom_ssl_var = 0;
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $custom_ssl_var = 1;


            if ($custom_ssl_var == 1)
                $_http_host = _PS_BASE_URL_SSL_.__PS_BASE_URI__;
            else
                $_http_host = _PS_BASE_URL_.__PS_BASE_URI__;

        } else {
            $_http_host = _PS_BASE_URL_.__PS_BASE_URI__;
        }
        return $_http_host;
    }



    public function deleteAvatar($data)
    {


        include_once(dirname(__FILE__) . '/userprofileg.class.php');
        $obj = new userprofileg();

        $id = (int)$data['id'];

        $info_post = $this->getItem(array('id'=>$id));
        $img = $info_post['reviews'][0]['avatar'];
        $data['avatar'] = $img;

        $data['is_storereviews'] = 1;

        $obj->deleteAvatar($data);

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

            if(!$is_full)
                $lastname = Tools::strtoupper(Tools::substr($result['lastname'],0,1));
            else
                $lastname = $result['lastname'];

            $firstname = $result['firstname'];
            $customer_name = $firstname . " " . $lastname;
            $email = $result['email'];


        return array('customer_name' => $customer_name,'email'=>$email);
    }

    public function checkProductBought($data)
    {
        $id_customer = $data['id_customer'];
        if(version_compare(_PS_VERSION_, '1.5', '>')){
            $sql = 'SELECT count(o.id_order) as count FROM ' . _DB_PREFIX_ .'orders as o
					   WHERE o.id_customer = ' . (int)($id_customer) . '
					   AND o.id_shop = '.(int)($this->getIdShop());
        } else {
            $sql = 'SELECT count(o.id_order) as count FROM ' . _DB_PREFIX_ .'orders as o
					   WHERE o.id_customer = ' . (int)($id_customer) . '
					  ';
        }
        $result = Db::getInstance()->ExecuteS($sql);
        return (!empty($result[0]['count'])? 1 : 0);
    }

    public function getAvgReview(){

        $sql_condition = $this->getConditionMultilanguageAndMultiStore(array('and'=>1));

        $sql_cond_customer = '';
        $user_id = (int)Tools::getValue('uid');
        if($user_id){
            $sql_cond_customer = '`id_customer` = '.(int)($user_id).' AND ';
        }

        $result = Db::getInstance()->getRow('
		SELECT ceil(AVG(`rating`)) AS "avg_rating",  round(AVG(`rating`),1) AS "avg_rating_decimal"
		FROM `'._DB_PREFIX_.''.$this->_table_name.'` pc
		WHERE '.$sql_cond_customer.' active = 1 AND is_deleted = 0 AND rating != 0 '.$sql_condition.''
        );

        //var_dumP($result);

        return array('avg_rating'=>(int)$result['avg_rating'],
                     'avg_rating_decimal'=>(isset($result['avg_rating_decimal'])?str_replace(".",",",$result['avg_rating_decimal']):0));
    }

    public function getCountReviews(){

        $sql_condition = $this->getConditionMultilanguageAndMultiStore(array('and'=>1));


        $sql_cond_customer = '';
        $user_id = (int)Tools::getValue('uid');
        if($user_id){
            $sql_cond_customer = '`id_customer` = '.(int)($user_id).' AND ';
        }

        $sql = 'SELECT COUNT(`id`) AS "count"
		FROM `'._DB_PREFIX_.''.$this->_table_name.'` pc
		WHERE '.$sql_cond_customer.' active = 1 AND is_deleted = 0 '.$sql_condition.' ';
        if (($result = Db::getInstance()->getRow($sql)) === false)
            return false;
        return (int)($result['count']);
    }

    public function getCountRatingForItem(){


        $sql_condition = $this->getConditionMultilanguageAndMultiStore(array('and'=>1));

        $sql_cond_customer = '';
        $user_id = (int)Tools::getValue('uid');
        if($user_id){
            $sql_cond_customer = '`id_customer` = '.(int)($user_id).' AND ';
        }


        $data_return = array();
        // one
        $sql = 'select count(*) as count
					   FROM `'._DB_PREFIX_.''.$this->_table_name.'`
					   WHERE '.$sql_cond_customer.'  active = 1 AND is_deleted = 0 AND rating = 1  '.$sql_condition.'
					   ';
        $result = Db::getInstance()->getRow($sql);
        $data_return['one'] = (int)$result['count'];

        // two
        $sql = 'select count(*) as count
					   FROM `'._DB_PREFIX_.''.$this->_table_name.'`
					   WHERE '.$sql_cond_customer.' active = 1 AND is_deleted = 0 AND rating = 2 '.$sql_condition.'
					   ';
        $result = Db::getInstance()->getRow($sql);
        $data_return['two'] = (int)$result['count'];

        // three
        $sql = 'select count(*) as count
					   FROM `'._DB_PREFIX_.''.$this->_table_name.'`
					   WHERE '.$sql_cond_customer.' active = 1 AND is_deleted = 0 AND rating = 3 '.$sql_condition.'
					   ';
        $result = Db::getInstance()->getRow($sql);
        $data_return['three'] = (int)$result['count'];

        // four
        $sql = 'select count(*) as count
					   FROM `'._DB_PREFIX_.''.$this->_table_name.'`
					   WHERE '.$sql_cond_customer.' active = 1 AND is_deleted = 0 AND rating = 4 '.$sql_condition.'
					   ';
        $result = Db::getInstance()->getRow($sql);
        $data_return['four'] = (int)$result['count'];

        // five
        $sql = 'select count(*) as count
					   FROM `'._DB_PREFIX_.''.$this->_table_name.'`
					   WHERE '.$sql_cond_customer.' active = 1 AND is_deleted = 0 AND rating = 5 '.$sql_condition.'
					   ';
        $result = Db::getInstance()->getRow($sql);
        $data_return['five'] = (int)$result['count'];

        return $data_return;

    }

    private function getConditionMultilanguageAndMultiStore($data){

        $and = ($data['and']==1)?'AND':'';

        $id_shop = $this->getIdShop();

        /*if(Configuration::get($this->_name.'rswitch_lng') == 1){
            $cookie = $this->context->cookie;
            $id_lang = (int)($cookie->id_lang);
            $sql_condition = $and.'  id_lang = '.(int)($id_lang).' AND id_shop = '.(int)($id_shop).'';
        } else {
            $sql_condition = $and.'  id_shop = '.(int)($id_shop).'';
        }*/

        $cookie = $this->context->cookie;
        $id_lang = (int)($cookie->id_lang);
        $sql_condition = $and.'  id_lang = '.(int)($id_lang).' AND id_shop = '.(int)($id_shop).'';

        return $sql_condition;
    }


    private function _getAvatarPath($data){


        include_once(dirname(__FILE__).'/userprofileg.class.php');
        $obj = new userprofileg();

        return $obj->getAvatarPath($data);
    }

    public function getAvatarForCustomer($data){
        include_once(dirname(__FILE__).'/userprofileg.class.php');
        $obj = new userprofileg();

        return $obj->getAvatarForCustomer($data);
    }

    public function saveImageAvatar($data = null){
        include_once(dirname(__FILE__).'/userprofileg.class.php');
        $obj = new userprofileg();

        return $obj->saveImageAvatar($data);


    }

}