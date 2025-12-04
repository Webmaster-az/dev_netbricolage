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

$is_uprof = Configuration::get($name_module.'is_uprof');
if (!$is_uprof)
    Tools::redirect('index.php');


include_once(dirname(__FILE__).'/gsnipreview.php');
$obj_gsnipreview = new gsnipreview();

$_data_translate = $obj_gsnipreview->translateCustom();
$smarty->assign('meta_title' , $_data_translate['meta_title_shoppers']);
$smarty->assign('meta_description' , $_data_translate['meta_description_shoppers']);
$smarty->assign('meta_keywords' , $_data_translate['meta_keywords_shoppers']);



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


        
        

        

        
        
        $obj_gsnipreview->setSEOUrls();
        
        

        
        $obj_gsnipreview->setSEOUrls();
        
        

        
        
        include_once(dirname(__FILE__).'/classes/userprofileg.class.php');
        $obj = new userprofileg();
        
        
        
        $gp = (int)Tools::getValue('gp');
        $step = (int)Configuration::get($name_module.'rpage_shoppers');
        
        
        $start = (int)(($gp - 1)*$step);
        if($start<0)
            $start = 0;
        
        
        $search = Tools::getValue("search");
        $is_search = 0;
        
        ### search ###
        if(Tools::strlen($search)>0){
            $is_search = 1;
        
        }
        $smarty->assign($name_module.'is_search', $is_search);
        $smarty->assign($name_module.'search', $search);
        $smarty->assign($name_module.'gp', $gp);
        
        
        $info_customers = $obj->getShoppersList(array('start' => $start,'step'=>$step,'is_search'=>$is_search,'search'=>$search));
        
        
        include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');
        $obj_gsnipreviewhelp = new gsnipreviewhelp();
        
        $data_urls = $obj_gsnipreviewhelp->getSEOURLs();
        $users_url = $data_urls['users_url'];
        
        $paging = $obj_gsnipreviewhelp->paging17(array('start'=>$start,
                'step'=> $step,
                'count' => $info_customers['data_count_customers'],
                'all_my' => 1,
                'product_url' => $users_url,
                'page' => $_data_translate['page'],
        
                'is_search'=>$is_search,
                'search'=>$search,
            )
        );
        
        
        $smarty->assign(array(
            $name_module.'customers' => $info_customers['customers'],
            $name_module.'data_count_customers' => $info_customers['data_count_customers'],
            $name_module.'paging' => $paging,
            $name_module.'users_url'=>$users_url,
            $name_module.'page_text' => $_data_translate['page'],
        ));







    if(version_compare(_PS_VERSION_, '1.5', '>')){

        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $obj_front_c = new ModuleFrontController();
            $obj_front_c->module->name = "gsnipreview";
            $obj_front_c->setTemplate('users.tpl');

            $obj_front_c->setMedia();

            $obj_front_c->initHeader();

            $obj_front_c->initContent();

            $obj_front_c->initFooter();


            $obj_front_c->display();

        } else {
            echo $obj_gsnipreview->renderUsers();
        }
    } else {
        echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/front/users.tpl');
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
