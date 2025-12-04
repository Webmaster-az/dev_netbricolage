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

$_GET['controller'] = 'all'; 
$_GET['fc'] = 'module';
$_GET['module'] = 'gsnipreview';
require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');


$name_module = 'gsnipreview';


if (version_compare(_PS_VERSION_, '1.5', '<')){
	require_once(_PS_MODULE_DIR_.$name_module.'/backward_compatibility/backward.php');
} else{
    $smarty = Context::getContext()->smarty;
    $cookie = Context::getContext()->cookie;
}


$is_storerev = Configuration::get($name_module.'is_storerev');
if (!$is_storerev)
    Tools::redirect('index.php');


$id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;
if (!$id_customer)
    Tools::redirect('authentication.php');


include_once(dirname(__FILE__).'/gsnipreview.php');
$obj_gsnipreview = new gsnipreview();
$_data_translate = $obj_gsnipreview->translateItems();


$smarty->assign('meta_title' , $_data_translate['meta_title_testimonials']);
$smarty->assign('meta_description' , $_data_translate['meta_description_testimonials']);
$smarty->assign('meta_keywords' , $_data_translate['meta_keywords_testimonials']);




if (version_compare(_PS_VERSION_, '1.5', '>') && version_compare(_PS_VERSION_, '1.6', '<')) {
	if (isset(Context::getContext()->controller)) {
		$oController = Context::getContext()->controller;
	}
	else {
		$oController = new FrontController();
		$oController->init();
	}
	// header
		$oController->setMedia();
		@$oController->displayHeader();
	} else {
		if(version_compare(_PS_VERSION_, '1.5', '<'))
			include(dirname(__FILE__).'/../../header.php');
	}




$_prefix = $obj_gsnipreview->getPrefixShopReviews();




include_once(dirname(__FILE__).'/classes/storereviews.class.php');
$obj_storereviews = new storereviews();


include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');
$obj_gsnipreviewhelp = new gsnipreviewhelp();


$id_lang = (int)$cookie->id_lang;
$data_seo_url = $obj_gsnipreviewhelp->getSEOURLs(array('id_lang'=>$id_lang));

$rev_url = $data_seo_url['store_reviews_account_url'];
$my_account = $data_seo_url['my_account'];


$gp = (int)Tools::getValue('p'.$_prefix);
$step = (int)$obj_storereviews->getStepForMyStoreReviews();

$start = (int)(($gp - 1)*$step);
if($start<0)
    $start = 0;

$data_my_reviews = $obj_storereviews->getTestimonials(array('start'=>$start,'step'=>$step,'id_customer' => $id_customer));



$paging = $obj_storereviews->PageNav17($start,$data_my_reviews['count_all_reviews'],$step, array('prefix'=>$_prefix,'user_url'=>$rev_url));


if(version_compare(_PS_VERSION_, '1.5', '<')){
    $smarty->assign($name_module.'is14' , 1);
} else {
    $smarty->assign($name_module.'is14' , 0);
}




$smarty->assign(array(
    $name_module.'my_reviews' => $data_my_reviews['reviews'],
    $name_module.'paging' => $paging,
    $name_module.'page_text' => $_data_translate['page'],

    $name_module.'my_a_link'=> $my_account,

    $name_module.'rev_url' => $rev_url,


));





if(version_compare(_PS_VERSION_, '1.5', '>')){
	
	if(version_compare(_PS_VERSION_, '1.6', '>')){
		
		$obj_front_c = new ModuleFrontController();
		$obj_front_c->module->name = "gsnipreview";
		$obj_front_c->setTemplate('my-storereviews.tpl');
		
		$obj_front_c->setMedia();
		
		$obj_front_c->initHeader();
		
		$obj_front_c->initContent();
		
		$obj_front_c->initFooter();
		
		
		$obj_front_c->display();
		
	} else {
		echo $obj_gsnipreview->renderMyStoreReviews();
	}
} else {
	echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/front/my-storereviews.tpl');
}
	
	
if (version_compare(_PS_VERSION_, '1.5', '>') && version_compare(_PS_VERSION_, '1.6', '<')) {
	if (isset(Context::getContext()->controller)) {
		$oController = Context::getContext()->controller;
	}
	else {
		$oController = new FrontController();
		$oController->init();
	}
	// header
		@$oController->displayFooter();
	} else {
		if(version_compare(_PS_VERSION_, '1.5', '<'))
			include(dirname(__FILE__).'/../../footer.php');
	}