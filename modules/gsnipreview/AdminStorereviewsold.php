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


ob_start();
	/*@ini_set('display_errors', 'on');	
	define('_PS_DEBUG_SQL_', true);
	define('_PS_DISPLAY_COMPATIBILITY_WARNING_', true);
	error_reporting(E_ALL|E_STRICT);
	*/

class AdminStorereviewsold extends AdminTab{
	private $_is15;
	public function __construct()
	{
		$this->module = 'gsnipreview';
	
		if(version_compare(_PS_VERSION_, '1.5', '>')){
			$this->multishop_context = Shop::CONTEXT_ALL;
			$this->_is15 = 1;
		} else {
			$this->_is15 = 0;
		}

		parent::__construct();
	}

	public function addJS(){
	}

	public function addCss(){
	}

	public function display()
	{
		echo '<style type="text/css">.warn{display:none!important} #maintab20{display:none!important} </style>';

        $tab = 'AdminStorereviewsold';

		$currentIndex = isset(AdminController::$currentIndex)?AdminController::$currentIndex:'index.php?controller='.$tab;

		// include main class
		require_once(dirname(__FILE__) .  '/gsnipreview.php');

		// instantiate
		$obj_main = new gsnipreview();
		$prefix = $obj_main->getPrefixShopReviews();
		$token = $this->token;
		include_once(dirname(__FILE__).'/classes/storereviews.class.php');
		$obj_storereviews = new storereviews();

       // publish
	   if (Tools::isSubmit("published".$prefix)) {
			if (Validate::isInt(Tools::getValue("id"))){
				$obj_storereviews->setPublsh(array('id'=>Tools::getValue("id"), 'active'=>1));
				Tools::redirectAdmin($currentIndex.'&tab='.$tab.'&configure='.$this->module.'&token='.$token.'');
			} 
		}

		//unpublish
		if (Tools::isSubmit("unpublished".$prefix)) {
			if (Validate::isInt(Tools::getValue("id"))){
					$obj_storereviews->setPublsh(array('id'=>Tools::getValue("id"), 'active'=>0));
					Tools::redirectAdmin($currentIndex.'&tab='.$tab.'&configure='.$this->module.'&token='.$token.'');
			} 
		}
	
    	// delete item
		if (Tools::isSubmit("delete_item".$prefix)) {
			if (Validate::isInt(Tools::getValue("id"))) {
				$obj_storereviews->deteleItem(array('id'=>Tools::getValue("id")));
				Tools::redirectAdmin($currentIndex.'&tab='.$tab.'&configure='.$this->module.'&token='.$token.'');
			}
		}

		if (Tools::isSubmit('submit_item'.$prefix))
        {
        	$name = (Tools::strlen(Tools::getValue("name"))==0?Tools::getValue("name"):Tools::getValue("name"));
        	$email = (Tools::strlen(Tools::getValue("email"))==0?Tools::getValue("email"):Tools::getValue("email"));
        	$web = Tools::getValue("web");
        	$company = Tools::getValue("company");
        	$address = Tools::getValue("address");
        	$country = Tools::getValue("country");
        	$city = Tools::getValue("city");
        	$rating = Tools::getValue("rating");
        	$message = (Tools::strlen(Tools::getValue("message"))==0?Tools::getValue("message"):Tools::getValue("message"));
        	$publish = (int)Tools::getValue("publish");
            $response = Tools::getValue("response");
            $is_noti = Tools::getValue("is_noti");
            $is_show = Tools::getValue("is_show");
            $date_add = Tools::getValue("date_add");
            $post_images = Tools::getValue("post_images");
            $id_customer = Tools::getValue("id_customer");
        	$obj_storereviews->updateItem(array('name'=>$name,
        									   'email'=>$email,
        									   'web' =>$web,
        									   'message'=>$message,
        									   'publish'=>$publish,
        									   'address'=>$address,
        									   'company'=>$company,
							        			'country'=>$country,
							        			'city'=>$city,
							        			'rating'=>$rating,
                                                'date_add' => $date_add,
        									    'id' =>Tools::getValue("id"),
                                                'response'=>$response,
                                                'is_noti'=>$is_noti,
                                                'is_show'=>$is_show,
                                                'post_images' => $post_images,
                                                'id_customer' => $id_customer,
        									   )
        								);
       	
        	Tools::redirectAdmin($currentIndex.'&tab='.$tab.'&configure='.$this->module.'&token='.$token.'');
		}
     
		if (Tools::isSubmit('cancel_item'.$prefix))
        {
        	Tools::redirectAdmin($currentIndex.'&tab='.$tab.'&configure='.$this->module.'&token='.$token.'');
		}

		echo $obj_main->_headercssfiles();
		echo $obj_main->_drawTestImonials(array('currentindex'=>$currentIndex,'controller'=>$tab));
	}
}
?>