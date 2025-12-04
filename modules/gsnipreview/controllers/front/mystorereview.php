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

class GsnipreviewmystorereviewModuleFrontController extends ModuleFrontController
{
	
	public function init()
	{
        $name_module = 'gsnipreview';

        $is_storerev = Configuration::get($name_module.'is_storerev');
        if (!$is_storerev)
            Tools::redirect('index.php');



        parent::init();
	}
	
	public function setMedia()
	{
		parent::setMedia();
        //$this->context->controller->addCSS(__PS_BASE_URI__.'modules/gsnipreview/views/css/font-custom.min.css');
    }

	
	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();


        $name_module = 'gsnipreview';
        $cookie = Context::getContext()->cookie;


        $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;
        if (!$id_customer)
            Tools::redirect('authentication.php');



        include_once(dirname(__FILE__).'../../../gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();
        $_data_translate = $obj_gsnipreview->translateItems();

        $_prefix = $obj_gsnipreview->getPrefixShopReviews();

        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->context->smarty->tpl_vars['page']->value['meta']['title'] = $_data_translate['meta_title_testimonials'];
            $this->context->smarty->tpl_vars['page']->value['meta']['description'] = $_data_translate['meta_description_testimonials'];
            $this->context->smarty->tpl_vars['page']->value['meta']['keywords'] = $_data_translate['meta_keywords_testimonials'];
        }

        $this->context->smarty->assign('meta_title' , $_data_translate['meta_title_testimonials']);
        $this->context->smarty->assign('meta_description' , $_data_translate['meta_description_testimonials']);
        $this->context->smarty->assign('meta_keywords' , $_data_translate['meta_keywords_testimonials']);



        include_once(dirname(__FILE__).'../../../classes/storereviews.class.php');
        $obj_storereviews = new storereviews();


        include_once(dirname(__FILE__).'../../../classes/gsnipreviewhelp.class.php');
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



        $paging = $obj_storereviews->PageNav17($start,$data_my_reviews['count_all_reviews'],$step, array('prefix'=>$_prefix,'user_url'=>$rev_url,'is_my_storereviews'=>1));


        if(version_compare(_PS_VERSION_, '1.5', '<')){
            $this->context->smarty->assign($name_module.'is14' , 1);
        } else {
            $this->context->smarty->assign($name_module.'is14' , 0);
        }




        $this->context->smarty->assign(array(
            $name_module.'my_reviews' => $data_my_reviews['reviews'],
            $name_module.'paging' => $paging,
            $name_module.'page_text' => $_data_translate['page'],

            $name_module.'my_a_link'=> $my_account,

            $name_module.'rev_url' => $rev_url,


        ));






        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->setTemplate('module:' . $name_module . '/views/templates/front/my-storereviews17.tpl');
        }else {
            $this->setTemplate('my-storereviews.tpl');
        }


    }
}