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

class GsnipreviewReviewModuleFrontController extends ModuleFrontController
{
	
	public function init()
	{
		$module_name = 'gsnipreview';
		
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
		parent::init();
	}
	
	public function setMedia()
	{
		parent::setMedia();
        $this->addJqueryPlugin(array('fancybox'));
        //$this->addJqueryPlugin(array('thickbox', 'idTabs'));
	}

	
	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();
		
		$module_name = 'gsnipreview';



        $rid = (int)Tools::getValue('rid');

        include_once(dirname(__FILE__).'../../../classes/gsnipreviewhelp.class.php');
        $obj = new gsnipreviewhelp();

        $data = $obj->getOneReview(array('rid'=>$rid));

        $is_active = $data['reviews'][0]['is_active'];

        $id_shop = $data['reviews'][0]['id_shop'];
        $id_lang = $data['reviews'][0]['id_lang'];
        $data_seo_url = $obj->getSEOURLs(array('id_lang'=>$id_lang,'id_shop'=>$id_shop));

        $rev_url = $data_seo_url['rev_url'];
        $all_url = $data_seo_url['rev_all'];


        $active_product = $data['reviews'][0]['active_product'];
        if($active_product == 0){
            Tools::redirect($all_url);
            exit;
        }

        //var_dump(empty($data['reviews'][0]));exit;
        if(!$rid
            //|| !$is_active
            || empty($data['reviews'][0])
            ){

            Tools::redirect($all_url);
        }


        //echo "<pre>"; var_dump($data);

		include_once(dirname(__FILE__).'../../../gsnipreview.php');
		$objgsnipreview = new gsnipreview();

        $objgsnipreview->settingsHooks();

			

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

        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->context->smarty->tpl_vars['page']->value['meta']['title'] = $meta_title;
            $this->context->smarty->tpl_vars['page']->value['meta']['description'] = Tools::substr($meta_description,0,155);
            $this->context->smarty->tpl_vars['page']->value['meta']['keywords'] = $meta_keywords;
        }


		$this->context->smarty->assign('meta_title' ,$meta_title);
		$this->context->smarty->assign('meta_description' , Tools::substr($meta_description,0,155));
		$this->context->smarty->assign('meta_keywords' , $meta_keywords);





        $objgsnipreview->basicSettingsHook();


			
		// Smarty display
		$this->context->smarty->assign(array(
			$module_name.'reviews_all' => $data['reviews'],

		     $module_name.'rev_url' => $rev_url,

			$module_name.'criterions' => $obj->getReviewCriteria(array('id_lang'=>$objgsnipreview->getIdLang(),'id_shop'=>$obj->getIdShop())),
			));



        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->setTemplate('module:' . $module_name . '/views/templates/front/review17.tpl');
        }else {
            $this->setTemplate('review.tpl');
        }
	}
}