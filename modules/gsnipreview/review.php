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

$rid = (int)Tools::getValue('rid');

include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');
$obj = new gsnipreviewhelp();

$data = $obj->getOneReview(array('rid'=>$rid));

$is_active = $data['reviews'][0]['is_active'];


if(!$is_active) {
    $data_translate = $objgsnipreview->translateCustom();

    $meta_title = $data_translate['pending_review'];
    $meta_description = $data_translate['pending_review'];
    $meta_keywords = $data_translate['pending_review'];
} else {
    $meta_title = $data['reviews'][0]['title_review'];
    $meta_description = $data['reviews'][0]['text_review'];
    $meta_keywords = $data['reviews'][0]['title_review'];

}

$smarty->assign('meta_title' ,$meta_title);
$smarty->assign('meta_description' , Tools::substr($meta_description,0,155));
$smarty->assign('meta_keywords' , $meta_keywords);


        include_once(dirname(__FILE__).'/../../header.php');


            $id_shop = $data['reviews'][0]['id_shop'];
            $id_lang = $data['reviews'][0]['id_lang'];
            $data_seo_url = $obj->getSEOURLs(array('id_lang'=>$id_lang,'id_shop'=>$id_shop));

            $rev_url = $data_seo_url['rev_url'];
            $all_url = $data_seo_url['rev_all'];

            if(!$rid
                //|| !$is_active
                || empty($data['reviews'][0])
            ){

                Tools::redirect($all_url);
            }






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










            $objgsnipreview->basicSettingsHook();


            if(Configuration::get('PS_REWRITING_SETTINGS')){
                $smarty->assign($module_name.'is_rewrite', 1);
            } else {
                $smarty->assign($module_name.'is_rewrite',0);
            }

            // Smarty display
            $smarty->assign(array(
                $module_name.'reviews_all' => $data['reviews'],
                $module_name.'activestar' => $activestar,
                $module_name.'noactivestar' => $noactivestar,

                $module_name.'rev_url' => $rev_url,

                $module_name.'rvis_on' => $rvis_on,
                $module_name.'ratings_on' => $ratings_on,
                $module_name.'title_on' => $title_on,
                $module_name.'text_on' => $text_on,
                $module_name.'criterions' => $obj->getReviewCriteria(array('id_lang'=>$objgsnipreview->getIdLang(),'id_shop'=>$obj->getIdShop())),
            ));

	        echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/front/review.tpl');

	

	include_once(dirname(__FILE__).'/../../footer.php');
