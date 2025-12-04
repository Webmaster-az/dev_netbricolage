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

class userprofileg extends Module {
	
	private $_step;
	private $_http;

    private $_name;
    private $_http_host;
    private $_is_cloud;
    private $_prefix = "r";
    private $_id_shop;
	
	
	public function __construct(){
		parent::__construct();

        $this->_name = "gsnipreview";


		$this->_step = (int)Configuration::get($this->_name.$this->_prefix.'page_shoppers');
		$this->_http = $this->_http();

        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $this->_http_host = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__;
        } else {
            $this->_http_host = _PS_BASE_URL_.__PS_BASE_URI__;
        }

        if(version_compare(_PS_VERSION_, '1.5', '>')){
            $this->_id_shop = Context::getContext()->shop->id;
        } else {
            $this->_id_shop = 0;
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
			require_once(_PS_MODULE_DIR_.'gsnipreview/backward_compatibility/backward.php');
		}
		
		$this->initContext();
	}
	
	private function initContext()
	{
		$this->context = Context::getContext();
	}

    public function getObjectParent(){
        include_once(dirname(__FILE__).'/../gsnipreview.php');
        $obj = new gsnipreview();
        return $obj;
    }


    public function getShoppersList($data = null){
		
		
		$start = $data['start'];
		$step = isset($data['step'])?$data['step']:$this->_step;

		$search = isset($data['search'])?$data['search']:0;
        $is_search = isset($data['is_search'])?$data['is_search']:0;

        $sql_shop = '';
        if($this->_id_shop){
            $sql_shop .= ' AND pc.id_shop = '.(int)$this->_id_shop;
        }

        $sql_condition_search = '';
        if($is_search == 1){
            $sql_condition_search = " AND
                    (
                    LOWER(pc.lastname) LIKE BINARY LOWER('%".pSQL($search)."%')
                      OR
                     LOWER(pc.firstname) LIKE BINARY LOWER('%".pSQL($search)."%')
                    )  ";
        }


		$sql = '
		SELECT pc.*, a2c.avatar_thumb
		FROM `'._DB_PREFIX_.'customer` pc LEFT JOIN `'._DB_PREFIX_.'gsnipreview_avatar2customer` a2c
		on(a2c.id_customer = pc.id_customer) 
		WHERE pc.active = 1 AND pc.deleted = 0 '.$sql_shop.' AND (a2c.`is_show` = 1 OR a2c.`is_show` IS NULL) '.$sql_condition_search.'
		ORDER BY pc.`id_customer` ASC LIMIT '.(int)$start.' ,'.(int)$step.'';


		$customers = Db::getInstance()->ExecuteS($sql);

		$i=0;
		foreach($customers as $_item_customer){
			
			$avatar_thumb = $_item_customer['avatar_thumb'];
			$id_gender = $_item_customer['id_gender'];
			$id_customer = $_item_customer['id_customer'];
			
			// addresses
			$info_addresses = $this->_getAddresses(array('id_customer'=>$id_customer));
			$address_item = end($info_addresses['multipleAddressesFormated']);
			$customers[$i]['country'] = @$address_item['country'];
			
			// user with avatar
			$info_path = $this->getAvatarPath(array('id_gender' => $id_gender,'avatar' => $avatar_thumb, 'id_customer' =>$id_customer,'is_user'=>1 ));


			$customers[$i]['avatar_thumb'] = $info_path['avatar'];
			$customers[$i]['exist_avatar'] = $info_path['is_exist'];
			
			$i++;
		}
		
		$data_count_customers = Db::getInstance()->getRow('
		SELECT COUNT(pc.id_customer) AS "count"
		FROM `'._DB_PREFIX_.'customer` pc LEFT JOIN `'._DB_PREFIX_.'gsnipreview_avatar2customer` a2c
		on(a2c.id_customer = pc.id_customer) 
		WHERE pc.active = 1 AND pc.deleted = 0 '.$sql_shop.' AND (a2c.`is_show` = 1 OR a2c.`is_show` IS NULL) '.$sql_condition_search.'
		');
		

		
		return array('customers' => $customers, 
					'data_count_customers' => $data_count_customers['count'],
					);
	}
	
	
	public function getShoppersBlock($data = null){

        $sql_shop = '';
        if($this->_id_shop){
            $sql_shop .= ' AND pc.id_shop = '.(int)$this->_id_shop;
        }
		
		$start = $data['start'];
		$step = isset($data['step'])?$data['step']:$this->_step;
		
		
		$customers = Db::getInstance()->ExecuteS('
		SELECT pc.*, a2c.avatar_thumb
		FROM `'._DB_PREFIX_.'customer` pc LEFT JOIN `'._DB_PREFIX_.'gsnipreview_avatar2customer` a2c
		on(a2c.id_customer = pc.id_customer) 
		WHERE pc.active = 1 AND pc.deleted = 0 '.$sql_shop.' AND (a2c.`is_show` = 1 OR a2c.`is_show` IS NULL)
		ORDER BY RAND() 
		LIMIT '.(int)$start.' ,'.(int)$step.'');
		$i=0;
		foreach($customers as $_item_customer){
			
			$avatar_thumb = $_item_customer['avatar_thumb'];
			$id_gender = $_item_customer['id_gender'];
            $id_customer = $_item_customer['id_customer'];
			
			// user with avatar
			$info_path = $this->getAvatarPath(array('id_gender' => $id_gender,'avatar' => $avatar_thumb, 'id_customer' =>$id_customer,'is_user'=>1  ));
			
			$customers[$i]['avatar_thumb'] = $info_path['avatar'];
			$customers[$i]['exist_avatar'] = $info_path['is_exist'];
			
			$i++;
		}
		
		
		return array('customers' => $customers
					);
	}
	

	
	public function getShopperInfo($data = null){
		
		$cookie = $this->context->cookie;
		
		$shopper_id = isset($data['shopper_id'])?(int)$data['shopper_id']:0;

        $sql_shop = '';
        if($this->_id_shop){
            $sql_shop .= ' AND pc.id_shop = '.(int)$this->_id_shop;
        }


        $customers = Db::getInstance()->ExecuteS('
		SELECT pc.*, a2c.avatar_thumb
		FROM `'._DB_PREFIX_.'customer` pc LEFT JOIN `'._DB_PREFIX_.'gsnipreview_avatar2customer` a2c
		on(a2c.id_customer = pc.id_customer) 
		WHERE pc.active = 1 AND pc.deleted = 0 '.$sql_shop.' AND (a2c.`is_show` = 1 OR a2c.`is_show` IS NULL)
		 AND pc.id_customer = '.(int)$shopper_id);	
		$i=0;
		foreach($customers as $_item_customer){
			
			$avatar_thumb = $_item_customer['avatar_thumb'];
			$id_gender = $_item_customer['id_gender'];
			$id_customer = $_item_customer['id_customer'];
			
			// addresses
			$info_addresses = $this->_getAddresses(array('id_customer'=>$id_customer));
			$customers[$i]['addresses'] = $info_addresses['multipleAddressesFormated']; 
			$address_item = end($info_addresses['multipleAddressesFormated']);
			$customers[$i]['country'] = @$address_item['country'];
			
			// user with avatar
			$info_path = $this->getAvatarPath(array('id_gender' => $id_gender,'avatar' => $avatar_thumb, 'id_customer' =>$id_customer,'is_user'=>1  ));

			$customers[$i]['avatar_thumb'] = $info_path['avatar'];
			$customers[$i]['exist_avatar'] = $info_path['is_exist'];
			$customers[$i]['gender_txt'] = $info_path['gender_txt'];
			
			
			// load stats for customer
			$customer_obj = new Customer($id_customer);
			$stats = $customer_obj->getStats();
			$stats_tmp = array();
			foreach($stats as $_key_stat => $_item_stat){
				switch($_key_stat) {
					case 'last_visit':
						$_item_stat = ($_item_stat ? @Tools::displayDate($_item_stat, (int)($cookie->id_lang), true) : $this->l('never'));
					break;
					
				}
				$stats_tmp[$_key_stat] = $_item_stat; 
			}
			$customers[$i]['stats'] = $stats;
			
			
			$i++;
		}
		
		
		
		return array('customer' => $customers);
	}
	
	private function _getAddresses($data){
			$cookie = $this->context->cookie;
		
			$id_customer = $data['id_customer'];
			// adresses
			$customer = new Customer($id_customer);
			$customerAddressesDetailed = $customer->getAddresses($cookie->id_lang);
		
			return array('multipleAddressesFormated' => $customerAddressesDetailed);
	}

    public function getAvatarPath($data){


        $avatar_thumb = $data['avatar'];

        $is_exists = 0;
        $gender_txt = '';


        $is_user_functional = isset($data['is_user'])?$data['is_user']:0;

        $id_customer = $data['id_customer'];
        $is_show = 1;

        if($id_customer){
            $customer_data = $this->getCustomerInfo(array('id_customer' => $id_customer));
            $id_gender = $customer_data['id_gender'];

            $query = 'SELECT avatar_thumb, is_show from '._DB_PREFIX_.''.$this->_name.'_avatar2customer
												WHERE id_customer = '.(int)$id_customer;

            $result = Db::getInstance()->ExecuteS($query);


            $avatar = isset($result[0]['avatar_thumb'])?$result[0]['avatar_thumb']:'';
            $is_show = isset($result[0]['is_show'])?$result[0]['is_show']:1;
            if(Tools::strlen($avatar)){
                $avatar_thumb = $avatar;

            }


        } else {
            $id_gender = 0;
        }




        // user with avatar

        $obj = $this->getObjectParent();
        $http = $obj->getHttpost();

        $_data_translate = $obj->translateCustom();
        $male = $_data_translate['male'];
        $female = $_data_translate['female'];


        if(Tools::strlen($avatar_thumb)>0){

            if($this->_is_cloud){
                $path_img_cloud = "modules/".$this->_name."/upload/" . $this->_name . "/avatar/";
            } else {
                $path_img_cloud = "upload/" . $this->_name . "/avatar/";

            }


            $avatar_thumb =  $http.$path_img_cloud.$avatar_thumb;
            $is_exists = 1;

            switch($id_gender){
                case 1:
                    //male
                    $gender_txt =$male;
                    break;
                case 2:
                    //female
                    $gender_txt = $female;
                    break;
            }

        } else {

            if($is_show || $is_user_functional) {
                // user without avatar
                switch ($id_gender) {
                    case 1:
                        //male
                        $avatar_thumb = $http . "modules/" . $this->_name . "/views/img/avatar_m.gif";
                        $gender_txt =$male;
                        break;
                    case 2:
                        //female
                        $avatar_thumb = $http . "modules/" . $this->_name . "/views/img/avatar_w.gif";
                        $gender_txt = $female;
                        break;
                    default:
                        //unknown
                        //$avatar_thumb = $http . "modules/" . $this->_name . "/views/img/avatar_n.gif";
                        $avatar_thumb = $http . "modules/" . $this->_name . "/views/img/avatar_m.gif";
                        break;
                }
            }
        }
        return array('avatar'=> $avatar_thumb, 'is_exist' => $is_exists,'is_show'=>$is_show, 'gender_txt'=>$gender_txt);
    }




    public function saveImageAvatar($data = null){

        $files = $_FILES['avatar-review'];
        $id_customer = $data['id_customer'];

        $show_my_profile = isset($data['show_my_profile'])?$data['show_my_profile']:2;


        $item_id = isset($data['id'])?$data['id']:0;
        $post_images = isset($data['post_images'])?$data['post_images']:'';

        $is_storereviews = isset($data['is_storereviews'])?$data['is_storereviews']:0;



        ############### files ###############################
        if(!empty($files['name']))
        {

            if(!$files['error'])
            {

                include_once(dirname(__FILE__) . '/gsnipreviewhelp.class.php');
                $obj_gsnipreviewhelp = new gsnipreviewhelp();

                if($item_id) {

                    if($is_storereviews){

                        include_once(dirname(__FILE__) . '/storereviews.class.php');
                        $obj_storereviews = new storereviews();

                        $info_post = $obj_storereviews->getItem(array('id' => $item_id));
                        $post_item = $info_post['reviews'][0];
                        $img_post = $post_item['avatar'];

                    } else {
                        $info_post = $obj_gsnipreviewhelp->getItem(array('id' => $item_id));
                        $post_item = $info_post['reviews'][0];
                        $img_post = $post_item['avatar'];
                    }

                } else {
                    $_info = $this->getCustomerInfo();
                    $img_post = isset($_info['avatar_thumb']) ? $_info['avatar_thumb'] : '';
                }


                if(Tools::strlen($img_post)>0){

                    // delete old avatars
                    $name_thumb = explode("/",$img_post);
                    $name_thumb = end($name_thumb);

                    @unlink(dirname(__FILE__).$this->path_img_cloud."avatar".DIRECTORY_SEPARATOR.$name_thumb);


                }


                $type_one = $files['type'];


                srand((double)microtime()*1000000);
                $uniq_name_image = uniqid(rand());
                $type_one = Tools::substr($type_one,6,Tools::strlen($type_one)-6);
                $filename = $uniq_name_image.'.'.$type_one;



                move_uploaded_file($files['tmp_name'], dirname(__FILE__).$this->path_img_cloud."avatar".DIRECTORY_SEPARATOR.$filename);



                $obj_gsnipreviewhelp->copyImage(array('dir_without_ext'=>dirname(__FILE__).$this->path_img_cloud."avatar".DIRECTORY_SEPARATOR.$uniq_name_image,
                        'name'=>dirname(__FILE__).$this->path_img_cloud."avatar".DIRECTORY_SEPARATOR.$filename)
                );


                $this->saveAvatar(array(
                        'avatar' => $uniq_name_image.'.jpg',
                        'id'=>isset($data['id'])?$data['id']:0,
                        'id_customer'=>$id_customer,
                        'show_my_profile' => $show_my_profile,

                        'is_storereviews'=>$is_storereviews,
                    )
                );

                @unlink(dirname(__FILE__).$this->path_img_cloud."avatar".DIRECTORY_SEPARATOR.$uniq_name_image.".".$type_one);


            }

        }else {

            //var_dump($post_images);exit;
            if($post_images != "on"){


                $img_post = '';
                if($show_my_profile != 2) {
                    $_info = $this->getCustomerInfo();
                    $img_post = isset($_info['avatar_thumb']) ? $_info['avatar_thumb'] : '';

                    $img_post = explode("/",$img_post);
                    $img_post = end($img_post);
                }


                $this->saveAvatar(array(
                        'avatar' => "",
                        'id'=>$item_id,
                        'id_customer'=>$id_customer,
                        'show_my_profile' => $show_my_profile,
                        'avatar' =>$img_post,

                        'is_storereviews'=>$is_storereviews,
                    )
                );
            }
        }

    }





    public function saveAvatar($data){
        $avatar = $data['avatar'];
        $id = $data['id'];
        $id_customer = $data['id_customer'];
        $is_storereviews = $data['is_storereviews'];

        $show_my_profile = isset($data['show_my_profile'])?$data['show_my_profile']:0;
        $update_sql_cond = '';
        if($show_my_profile != 2) {
            switch($show_my_profile){
                case 'on':
                    $show_my_profile = 1;
                    break;
                default:
                    $show_my_profile = 0;
                    break;
            }
            $update_sql_cond = ' , is_show = '.(int)$show_my_profile.'';
        } else {
            $show_my_profile = 1;
        }


        if($id_customer){
            // if exist record
            $query = 'SELECT COUNT(*) as count from '._DB_PREFIX_.''.$this->_name.'_avatar2customer
												WHERE id_customer = '.(int)$id_customer;

            $result = Db::getInstance()->GetRow($query);
            $exist_record = $result['count'];

            if($exist_record){
                //update
                $query = 'UPDATE '._DB_PREFIX_.''.$this->_name.'_avatar2customer SET avatar_thumb = "'.pSQL($avatar).'" '.$update_sql_cond.'
                            WHERE id_customer = '.(int)$id_customer;
            } else {
                // insert
                $query = 'INSERT INTO '._DB_PREFIX_.''.$this->_name.'_avatar2customer (id_customer, avatar_thumb,is_show)
                             VALUES ('.(int)$id_customer.', "'.pSQL($avatar).'", '.(int)$show_my_profile.') ';
            }

        } else {

            if($is_storereviews){
                //update
                $query = 'UPDATE ' . _DB_PREFIX_ . 'gsnipreview_storereviews SET avatar =  "' . pSQL($avatar) . '" WHERE id = ' . (int)$id;
            } else {
                //update
                $query = 'UPDATE ' . _DB_PREFIX_ . 'gsnipreview SET avatar =  "' . pSQL($avatar) . '" WHERE id = ' . (int)$id;
            }

        }
        Db::getInstance()->Execute($query);


        if(Tools::strlen($avatar)==0)
            @unlink(dirname(__FILE__).$this->path_img_cloud."avatar".DIRECTORY_SEPARATOR.$avatar);


    }

	
	private function _http(){


        if(version_compare(_PS_VERSION_, '1.6', '>')){
            $http = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__;
        } else {
            $http = _PS_BASE_URL_.__PS_BASE_URI__;
        }


		return $http;
	}




	public function getCustomerInfo(){
		$cookie = $this->context->cookie;
		
		$exist_avatar = 0;
		$is_show = 0;
        $id_gender = 0;
		
		if($cookie->logged){
			$id_customer = $cookie->id_customer;
			
		$sql = 'SELECT * FROM `'._DB_PREFIX_.'gsnipreview_avatar2customer`
		        WHERE `id_customer` = '.(int)$id_customer.'';
    	$result = Db::getInstance()->GetRow($sql);

    	$avatar_thumb = $result['avatar_thumb'];
    	$is_show = isset($result['is_show'])?$result['is_show']:1;


        $info_customer_db = $this->_getInfoCustomerDB(array('id_customer' => $id_customer));
        $id_gender = $info_customer_db['id_gender'];


    	
		// user with avatar
		if(Tools::strlen($avatar_thumb)>0){
			//$avatar_thumb = $this->_http."upload/".$avatar_thumb;

            if ($this->_is_cloud) {
                $path_img_cloud = "modules/" . $this->_name . "/upload/" . $this->_name . "/avatar/";
            } else {
                $path_img_cloud = "upload/" . $this->_name . "/avatar/";

            }


            $avatar_thumb = $this->_http.$path_img_cloud.$avatar_thumb;

			$exist_avatar = 1;
		} else {
			// user without avatar
			$info_customer_db = $this->_getInfoCustomerDB(array('id_customer' => $id_customer));
			switch($info_customer_db['id_gender']){
				case 1:
					//male
					$avatar_thumb = $this->_http."modules/" . $this->_name . "/views/img/avatar_m.gif";
				break;
				case 2:
					//female
					$avatar_thumb = $this->_http."modules/" . $this->_name . "/views/img/avatar_w.gif";
				break;
				default:
					//unknown
					$avatar_thumb = $this->_http."modules/" . $this->_name . "/views/img/avatar_n.gif";
				break;
				
			}
			
		}
		 
    		return array('id_customer'=>$id_customer, 'id_gender'=>$id_gender,
						 'avatar_thumb' => $avatar_thumb, 'exist_avatar' => $exist_avatar, 'is_show' => $is_show);
		} else {
			return array('id_customer'=>0,'avatar' => '','avatar_thumb' => '', 'exist_avatar' => $exist_avatar
						 , 'is_show' => $is_show, 'id_gender'=>$id_gender);
		}
	}
	
	private function _getInfoCustomerDB($data){
		$id_customer = $data['id_customer'];
		$sql = 'SELECT * FROM `'._DB_PREFIX_.'customer` 
		        WHERE `id_customer` = '.(int)$id_customer.'';
    	$result = Db::getInstance()->GetRow($sql);
    	
    	return $result;
	}

    public function deleteAvatar($data){
        $id = $data['id'];
        $id_customer = $data['id_customer'];
        $is_storereviews = isset($data['is_storereviews'])?$data['is_storereviews']:0;

        $img = $data['avatar'];

        $this->saveAvatar(array(
                'avatar' => "",
                'id'=>$id,
                'id_customer' => $id_customer,
                'is_storereviews' => $is_storereviews,
            )
        );

        $img = explode("/",$img);
        $img = end($img);
        @unlink(dirname(__FILE__).$this->path_img_cloud."avatar".DIRECTORY_SEPARATOR.$img);
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


    public function getAvatarForCustomer($data){
        $id_customer = $data['id_customer'];

        $query = 'SELECT avatar_thumb, is_show from '._DB_PREFIX_.''.$this->_name.'_avatar2customer
												WHERE id_customer = '.(int)$id_customer;

        $result = Db::getInstance()->ExecuteS($query);


        $avatar = isset($result[0]['avatar_thumb'])?$result[0]['avatar_thumb']:'';

        if(Tools::strlen($avatar)>0) {

            if ($this->_is_cloud) {
                $path_img_cloud = "modules/" . $this->_name . "/upload/" . $this->_name . "/avatar/";
            } else {
                $path_img_cloud = "upload/" . $this->_name . "/avatar/";

            }

            $obj = $this->getObjectParent();
            $http = $obj->getHttpost();


            $avatar = $http . $path_img_cloud . $avatar;
        }

        return array('avatar'=>$avatar);
    }
}