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

class GsnipreviewReviewsModuleFrontController extends ModuleFrontController
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
        $this->context->smarty->assign($module_name.'is_search', $is_search);
        $this->context->smarty->assign($module_name.'search', $search);
        $this->context->smarty->assign($module_name.'frat', $frat);


        include_once(dirname(__FILE__).'../../../classes/gsnipreviewhelp.class.php');
        $obj = new gsnipreviewhelp();


		$data = $obj->getAllReviews(array('start'=>$start,'step'=>$step,'frat'=>$frat,'is_search'=>$is_search,'search'=>$search));

		include_once(dirname(__FILE__).'../../../gsnipreview.php');
		$objgsnipreview = new gsnipreview();
		$data_translate = $objgsnipreview->translateCustom();

        $objgsnipreview->settingsHooks();


        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->context->smarty->tpl_vars['page']->value['meta']['title'] = $data_translate['all_reviews_meta_title'];
            $this->context->smarty->tpl_vars['page']->value['meta']['description'] = $data_translate['all_reviews_meta_description'];
            $this->context->smarty->tpl_vars['page']->value['meta']['keywords'] = $data_translate['all_reviews_meta_keywords'];
        }
			

		
		$this->context->smarty->assign('meta_title' , $data_translate['all_reviews_meta_title']);
		$this->context->smarty->assign('meta_description' , $data_translate['all_reviews_meta_description']);
		$this->context->smarty->assign('meta_keywords' , $data_translate['all_reviews_meta_keywords']);

        $cookie = Context::getContext()->cookie;
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




        $avg_rating = $obj->getAvgReview();
        $count_reviews = $obj->getCountReviews();

		// Smarty display
		$this->context->smarty->assign(array(
			$module_name.'reviews_all' => $data['reviews'],

            $module_name . 'allr_url'=> $all,

            $module_name.'count_reviews' => $count_reviews,
            $module_name.'avg_rating'=>$avg_rating['avg_rating'],
            $module_name.'avg_decimal'=>$avg_rating['avg_rating_decimal'],


			$module_name.'paging' => $paging,
            $module_name.'page_text' => $data_translate['page'],
            $module_name.'rev_url' => $rev_url,

			$module_name.'criterions' => $obj->getReviewCriteria(array('id_lang'=>$objgsnipreview->getIdLang(),'id_shop'=>$obj->getIdShop())),
			));



        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->setTemplate('module:' . $module_name . '/views/templates/front/all17.tpl');
        }else {
            $this->setTemplate('all.tpl');
        }
	}
}