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


include_once(dirname(__FILE__).'/gsnipreview.php');
$obj_gsnipreview = new gsnipreview();
$_data_translate = $obj_gsnipreview->translateItems();

$_prefix = $obj_gsnipreview->getPrefixShopReviews();

$obj_gsnipreview->setStarsImagesSetting();

$obj_gsnipreview->setSEOUrls();

$obj_gsnipreview->setStoreReviewsSettings();


$smarty->assign('meta_title' , $_data_translate['meta_title_testimonials']);
$smarty->assign('meta_description' , $_data_translate['meta_description_testimonials']);
$smarty->assign('meta_keywords' , $_data_translate['meta_keywords_testimonials']);


$smarty->assign(
    array(
        $name_module.'msg1' => $_data_translate['msg1'],
        $name_module.'msg2' => $_data_translate['msg2'],
        $name_module.'msg3' => $_data_translate['msg3'],
        $name_module.'msg4' => $_data_translate['msg4'],
        $name_module.'msg5' => $_data_translate['msg5'],
        $name_module.'msg6' => $_data_translate['msg6'],
        $name_module.'msg7' => $_data_translate['msg7'],
        $name_module.'msg8' => $_data_translate['msg8'],
        $name_module.'msg9' => $_data_translate['msg9'],
    )
);



if (version_compare(_PS_VERSION_, '1.5', '>')  && version_compare(_PS_VERSION_, '1.6', '<')) {
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
			}
			else {
				if(version_compare(_PS_VERSION_, '1.5', '<'))
					include_once(dirname(__FILE__).'/../../header.php');
			}



include_once(dirname(__FILE__).'/classes/storereviews.class.php');
$obj_storereviews = new storereviews();


$step = Configuration::get($name_module.'perpage'.$_prefix);
$p = (int)Tools::getValue('p'.$_prefix);


$start = (int)(($p - 1)*$step);
if($start<0)
    $start = 0;


$frat = Tools::getValue('frat'.$_prefix);


$search = Tools::getValue("search".$_prefix);
$is_search = 0;

### search ###
if(Tools::strlen($search)>0){
    $is_search = 1;

}
$smarty->assign($name_module.'is_search'.$_prefix, $is_search);
$smarty->assign($name_module.'search'.$_prefix, $search);


$_data = $obj_storereviews->getTestimonials(array('start'=>$start,'step'=>$step,'frat'=>$frat,'is_search'=>$is_search,
    'search'=>$search));



$paging = $obj_storereviews->PageNav17($start,$_data['count_all_reviews'],$step, array('frat'=>$frat,'is_search'=>$is_search,
        'search'=>$search,'prefix'=>$_prefix)
);




$id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;
$name_customer = '';
$email_customer = '';
$avatar = '';
if($id_customer) {
    $customer_data = $obj_storereviews->getInfoAboutCustomer(array('id_customer' => $id_customer, 'is_full' => 1));
    $name_customer = $customer_data['customer_name'];
    $email_customer = $customer_data['email'];

    $data_avatar = $obj_storereviews->getAvatarForCustomer(array('id_customer' => $id_customer));
    $avatar = $data_avatar['avatar'];
}



$is_buy = $obj_storereviews->checkProductBought(array('id_customer'=>$id_customer));


$smarty->assign($name_module.'frat'.$_prefix, $frat);

$data_rating = $obj_storereviews->getCountRatingForItem();
$smarty->assign($name_module.'one'.$_prefix, $data_rating['one']);
$smarty->assign($name_module.'two'.$_prefix, $data_rating['two']);
$smarty->assign($name_module.'three'.$_prefix, $data_rating['three']);
$smarty->assign($name_module.'four'.$_prefix, $data_rating['four']);
$smarty->assign($name_module.'five'.$_prefix, $data_rating['five']);


$smarty->assign(array(

                    'reviews'.$_prefix => $_data['reviews'],
                    'count_all_reviews'.$_prefix => $_data['count_all_reviews'],
                    'paging'.$_prefix => $paging,

                    $name_module.'page_text' => $_data_translate['page'],

                    'shop_name_snippet'.$_prefix=>Configuration::get('PS_SHOP_NAME'),

                    $name_module.'name_c'.$_prefix => $name_customer,
                    $name_module.'email_c'.$_prefix => $email_customer,
                    $name_module.'c_avatar'.$_prefix => $avatar,

                    $name_module.'is_buy'.$_prefix => $is_buy,
                    $name_module.'id_customer'.$_prefix=>$id_customer

					  )
				);


				if(version_compare(_PS_VERSION_, '1.5', '>')){
	
	if(version_compare(_PS_VERSION_, '1.6', '>')){
					
		$obj_front_c = new ModuleFrontController();
		$obj_front_c->module->name = 'gsnipreview';
		$obj_front_c->setTemplate('storereviews.tpl');
		
		$obj_front_c->setMedia();
		
		$obj_front_c->initHeader();
		
		$obj_front_c->initContent();
		
		$obj_front_c->initFooter();
		
		
		$obj_front_c->display();
		
	} else {
		echo $obj_gsnipreview->renderTplItems();
	}
} else {
	echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/front/storereviews.tpl');
}

if (version_compare(_PS_VERSION_, '1.5', '>') && version_compare(_PS_VERSION_, '1.6', '<')) {
				if (isset(Context::getContext()->controller)) {
					$oController = Context::getContext()->controller;
				}
				else {
					$oController = new FrontController();
					$oController->init();
				}
				// footer
				@$oController->displayFooter();
			}
			else {
				if(version_compare(_PS_VERSION_, '1.5', '<'))
					include_once(dirname(__FILE__).'/../../footer.php');
			}

?>