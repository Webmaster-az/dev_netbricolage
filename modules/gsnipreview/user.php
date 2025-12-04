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

$user_id = (int)Tools::getValue('uid');

include_once(dirname(__FILE__).'/gsnipreview.php');
$obj_gsnipreview = new gsnipreview();

$_data_translate = $obj_gsnipreview->translateCustom();





include_once(dirname(__FILE__).'/classes/userprofileg.class.php');
$obj = new userprofileg();

$info = $obj->getShopperInfo(array('shopper_id' => $user_id));

$title = @$info['customer'][0]['firstname']." " .@$info['customer'][0]['lastname']. " ".$_data_translate['profile'];

$smarty->assign('meta_title' , $title);
$smarty->assign('meta_description' , $title);
$smarty->assign('meta_keywords' , $title);


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




        include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');
        $obj_gsnipreviewhelp = new gsnipreviewhelp();


        $data_urls = $obj_gsnipreviewhelp->getSEOURLs();
        $users_url = $data_urls['users_url'];

        $rev_url = $data_urls['rev_url'];
        $user_url = $data_urls['user_url'].$user_id;
        $my_account = $data_urls['my_account'];

if(!$user_id)
    Tools::redirect($users_url);



        if(sizeof($info['customer'])==0)
            Tools::redirect($users_url);



        $smarty->assign(array(
            $name_module.'customer' => $info['customer']
        ));





        $obj_gsnipreview->setSEOUrls();
        $obj_gsnipreview->settingsHooks();

        ## product reviews for customer ##

        $gp = (int)Tools::getValue('gp');
        $step = (int)Configuration::get($name_module.'revperpage');

        $start = (int)(($gp - 1)*$step);
        if($start<0)
            $start = 0;


        $frat = Tools::getValue('frat');

        $search = Tools::getValue("search");
        $is_search = 0;

        ### search ###
        if(Tools::strlen($search)>0){
            $is_search = 1;

        }



        $smarty->assign($name_module.'is_search', $is_search);
        $smarty->assign($name_module.'search', $search);
        $smarty->assign($name_module.'frat', $frat);
        $smarty->assign($name_module.'gp', $gp);
        $smarty->assign($name_module.'isgp', Tools::isSubmit('gp'));




        $data_translate = $obj_gsnipreview->translateCustom();

        include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');
        $obj_gsnipreviewhelp = new gsnipreviewhelp();
        $data_my_reviews = $obj_gsnipreviewhelp->getMyReviews(array('id_customer'=>$user_id,'start'=>$start,'step'=>$step,'frat'=>$frat,'is_search'=>$is_search,'search'=>$search));

        $count_reviews = $data_my_reviews['count_all'];



        $paging = $obj_gsnipreviewhelp->paging17(array('start'=>$start,
                'step'=> $step,
                'count' => $count_reviews,
                'all_my' => 1,
                'product_url' => $user_url,
                'page' => $data_translate['page'],
                'frat'=>$frat,
                'is_search'=>$is_search,
                'search'=>$search,
            )
        );

        $smarty->assign(array(
            $name_module.'user_id' => $user_id,
            $name_module.'user_reviews' => $data_my_reviews['reviews'],

            $name_module.'paging' => $paging,

            $name_module.'my_a_link'=> $my_account,

            $name_module.'rev_url' => $rev_url,

            $name_module.'page_text' => $data_translate['page'],



        ));

        ## product reviews for customer ##


        ## store reviews by customer ##

        $_prefix = $obj_gsnipreview->getPrefixShopReviews();

        $obj_gsnipreview->setStoreReviewsSettings();


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
        $smarty->assign($name_module.'frat'.$_prefix, $frat);
        $smarty->assign($name_module.'p'.$_prefix, $p);
        $smarty->assign($name_module.'isp'.$_prefix, Tools::isSubmit('p'.$_prefix));


        $_data = $obj_storereviews->getTestimonials(array('id_customer'=>$user_id,'start'=>$start,'step'=>$step,'frat'=>$frat,'is_search'=>$is_search,'search'=>$search));



        $paging = $obj_storereviews->PageNav17($start,$_data['count_all_reviews'],$step,
            array('frat'=>$frat,'is_search'=>$is_search,'search'=>$search,'prefix'=>$_prefix,'user_url' => $user_url,)
        );



        $smarty->assign($name_module.'frat'.$_prefix, $frat);

        $data_rating = $obj_storereviews->getCountRatingForItem();
        $smarty->assign($name_module.'one'.$_prefix, $data_rating['one']);
        $smarty->assign($name_module.'two'.$_prefix, $data_rating['two']);
        $smarty->assign($name_module.'three'.$_prefix, $data_rating['three']);
        $smarty->assign($name_module.'four'.$_prefix, $data_rating['four']);
        $smarty->assign($name_module.'five'.$_prefix, $data_rating['five']);


        $smarty->assign(
            array(

                'reviews'.$_prefix => $_data['reviews'],
                'count_all_reviews'.$_prefix => $_data['count_all_reviews'],
                'paging'.$_prefix => $paging,
                'shop_name_snippet'.$_prefix=>Configuration::get('PS_SHOP_NAME'),

                $name_module.'id_customer'.$_prefix=>$user_id
            )
        );

        ## store reviews by customer ##















if(version_compare(_PS_VERSION_, '1.5', '>')){

        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $obj_front_c = new ModuleFrontController();
            $obj_front_c->module->name = "gsnipreview";
            $obj_front_c->setTemplate('user.tpl');

            $obj_front_c->setMedia();

            $obj_front_c->initHeader();

            $obj_front_c->initContent();

            $obj_front_c->initFooter();


            $obj_front_c->display();

        } else {
            echo $obj_gsnipreview->renderUser();
        }
    } else {
        echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/front/user.tpl');
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
