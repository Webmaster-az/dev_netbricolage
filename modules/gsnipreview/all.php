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

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');

        $module_name = 'gsnipreview';

        if (version_compare(_PS_VERSION_, '1.5', '<')){
            require_once(_PS_MODULE_DIR_.$module_name.'/backward_compatibility/backward.php');
        } else{
            $smarty = Context::getContext()->smarty;
            $cookie = Context::getContext()->cookie;
        }

        $rvis_on = Configuration::get($module_name.'rvis_on');
        $ratings_on = Configuration::get($module_name.'ratings_on');
        $title_on = Configuration::get($module_name.'title_on');
        $text_on = Configuration::get($module_name.'text_on');


        if ($rvis_on == 1){

        } else {
            Tools::redirect('index.php');
        }

        if($ratings_on == 1 || $title_on == 1 || $text_on == 1){

        } else {

            Tools::redirect('index.php');
        }

        include_once(dirname(__FILE__).'/gsnipreview.php');
        $objgsnipreview = new gsnipreview();
        $data_translate = $objgsnipreview->translateCustom();

        $smarty->assign('meta_title' , $data_translate['all_reviews_meta_title']);
        $smarty->assign('meta_description' , $data_translate['all_reviews_meta_description']);
        $smarty->assign('meta_keywords' , $data_translate['all_reviews_meta_keywords']);


		include_once(dirname(__FILE__).'/../../header.php');



            $gp = (int)Tools::getValue('gp');
            $step = (int)Configuration::get($module_name.'revperpageall');

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
            $smarty->assign($module_name.'is_search', $is_search);
            $smarty->assign($module_name.'search', $search);


            include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');
            $obj = new gsnipreviewhelp();



            $smarty->assign($module_name.'frat', $frat);

            $data_rating = $obj->getCountRatingForItem();
            $smarty->assign($module_name.'one', $data_rating['one']);
            $smarty->assign($module_name.'two', $data_rating['two']);
            $smarty->assign($module_name.'three', $data_rating['three']);
            $smarty->assign($module_name.'four', $data_rating['four']);
            $smarty->assign($module_name.'five', $data_rating['five']);



            $data = $obj->getAllReviews(array('start'=>$start,'step'=>$step,'frat'=>$frat,'is_search'=>$is_search,'search'=>$search));




            switch(Configuration::get($module_name.'stylestars')){
                case 'style1':
                    $activestar = 'star-active-yellow.png';
                    $noactivestar = 'star-noactive-yellow.png';
                    break;
                case 'style2':
                    $activestar = 'star-active-green.png';
                    $noactivestar = 'star-noactive-green.png';
                    break;
                case 'style3':
                    $activestar = 'star-active-blue.png';
                    $noactivestar = 'star-noactive-blue.png';
                    break;
                default:
                    $activestar = 'star-active-yellow.png';
                    $noactivestar = 'star-noactive-yellow.png';
                    break;
            }


            $rvis_on = Configuration::get($module_name.'rvis_on');
            $ratings_on = Configuration::get($module_name.'ratings_on');
            $title_on = Configuration::get($module_name.'title_on');
            $text_on = Configuration::get($module_name.'text_on');



            $id_lang = (int)$cookie->id_lang;
            $data_seo_url = $obj->getSEOURLs(array('id_lang'=>$id_lang));

            $rev_url = $data_seo_url['rev_url'];
            $all = $data_seo_url['rev_all'];






            $paging = $obj->paging17(
                array('start'=>$start,
                    'step'=> $step,
                    'count' => $data['count_all'],
                    'product_url' => $all,
                    'page' => $data_translate['page'],
                    'all' => 1,
                    'frat'=>$frat,
                    'is_search'=>$is_search,
                    'search'=>$search,
                )
            );

            $objgsnipreview->basicSettingsHook();


            if((Configuration::get('PS_REWRITING_SETTINGS') || version_compare(_PS_VERSION_, '1.5', '<'))){
                $smarty->assign($module_name.'is_rewrite', 1);
            } else {
                $smarty->assign($module_name.'is_rewrite',0);
            }

            $avg_rating = $obj->getAvgReview();
            $count_reviews = $obj->getCountReviews();


            // Smarty display
            $smarty->assign(array(
                $module_name.'reviews_all' => $data['reviews'],
                $module_name.'activestar' => $activestar,
                $module_name.'noactivestar' => $noactivestar,

                $module_name.'count_reviews' => $count_reviews,
                $module_name.'avg_rating'=>$avg_rating['avg_rating'],
                $module_name.'avg_decimal'=>$avg_rating['avg_rating_decimal'],
                $module_name.'sh_name' => @Configuration::get('PS_SHOP_NAME'),

                $module_name.'paging' => $paging,
                $module_name.'rev_url' => $rev_url,
                $module_name.'page_text' => $data_translate['page'],

                $module_name.'rvis_on' => $rvis_on,
                $module_name.'ratings_on' => $ratings_on,
                $module_name.'title_on' => $title_on,
                $module_name.'text_on' => $text_on,
                $module_name.'criterions' => $obj->getReviewCriteria(array('id_lang'=>$objgsnipreview->getIdLang(),'id_shop'=>$obj->getIdShop())),
            ));



	        echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/front/all.tpl');

	

	include_once(dirname(__FILE__).'/../../footer.php');
