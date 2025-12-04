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
		}


include_once(dirname(__FILE__).'/gsnipreview.php');
$obj_gsnipreview = new gsnipreview();
$_data_translate = $obj_gsnipreview->translateCustom();


$smarty->assign('meta_title' , $_data_translate['meta_title_myaccount']);
$smarty->assign('meta_description' , $_data_translate['meta_description_myaccount']);
$smarty->assign('meta_keywords' , $_data_translate['meta_keywords_myaccount']);


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




        $is_uprof = Configuration::get($name_module.'is_uprof');

        if (!$is_uprof)
            Tools::redirect('index.php');

        $cookie = Context::getContext()->cookie;
        $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;
        if (!$id_customer)
            Tools::redirect('authentication.php');


        include_once(dirname(__FILE__).'/classes/userprofileg.class.php');
        $obj = new userprofileg();


        $obj_gsnipreview->setSEOUrls();


        include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');
        $obj_gsnipreviewhelp = new gsnipreviewhelp();

        $data_urls = $obj_gsnipreviewhelp->getSEOURLs();
        $my_account = $data_urls['my_account'];


        $info_customer = $obj->getCustomerInfo();
        $avatar_thumb = $info_customer['avatar_thumb'];
        $exist_avatar = $info_customer['exist_avatar'];
        $is_show = $info_customer['is_show'];





        $smarty->assign(array(
            $name_module.'avatar_thumb' => $avatar_thumb,
            $name_module.'exist_avatar' => $exist_avatar,
            $name_module.'is_show' => $is_show,
            $name_module.'my_account'=>$my_account,

            $name_module.'ava_msg8'=>$_data_translate['ava_msg8'],
            $name_module.'ava_msg9'=>$_data_translate['ava_msg9'],

        ));







    if(version_compare(_PS_VERSION_, '1.5', '>')){

        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $obj_front_c = new ModuleFrontController();
            $obj_front_c->module->name = "gsnipreview";
            $obj_front_c->setTemplate('useraccount.tpl');

            $obj_front_c->setMedia();

            $obj_front_c->initHeader();

            $obj_front_c->initContent();

            $obj_front_c->initFooter();


            $obj_front_c->display();

        } else {
            echo $obj_gsnipreview->renderUserAccount();
        }
    } else {
        echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/front/useraccount.tpl');
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
