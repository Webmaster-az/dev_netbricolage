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

class GsnipreviewMyreviewsModuleFrontController extends ModuleFrontController
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
        $this->context->controller->addCSS(__PS_BASE_URI__.'modules/gsnipreview/views/css/font-custom.min.css');

	}

	
	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();
		
		$module_name = 'gsnipreview';


        $cookie = Context::getContext()->cookie;
        $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;

        if (!$id_customer)
            Tools::redirect('authentication.php');

        include_once(dirname(__FILE__).'../../../classes/gsnipreviewhelp.class.php');
        $obj = new gsnipreviewhelp();

        include_once(dirname(__FILE__).'../../../gsnipreview.php');
        $objgsnipreview = new gsnipreview();
        $data_translate = $objgsnipreview->translateCustom();


        $objgsnipreview->settingsHooks();


        $gp = (int)Tools::getValue('gp');
        $step = (int)$obj->getStepForMyReviewsAll();

        $start = (int)(($gp - 1)*$step);
        if($start<0)
            $start = 0;


        $data_my_reviews = $obj->getMyReviews(array('id_customer'=>$id_customer,'start'=>$start));

        $count_reviews = $data_my_reviews['count_all'];


        $id_lang = (int)$cookie->id_lang;
        $data_seo_url = $obj->getSEOURLs(array('id_lang'=>$id_lang));

        $rev_url = $data_seo_url['rev_url'];
        $account_url = $data_seo_url['account_url'];
        $my_account = $data_seo_url['my_account'];




        $paging = $obj->paging17(array('start'=>$start,
                'step'=> $obj->getStepForMyReviewsAll(),
                'count' => $count_reviews,
                'all_my' => 1,
                'product_url' => $account_url,
                'page' => $data_translate['page'],
            )
        );



        /// set reminder status if customer not exists in table gsnipreview_reminder2customer ///
        $is_exists_reminder = $obj->isExists(array('id_customer'=>$id_customer));
        if(!$is_exists_reminder){
            $data = array('id_customer'=>$id_customer,'reminder_status'=>1);
            $obj->updateReminderForCustomer($data);
        }
        /// set reminder status if customer not exists in table gsnipreview_reminder2customer ///

        $reminder_status = $obj->getStatus(array('id_customer'=>$id_customer));


        $is_reminder = Configuration::get($module_name.'reminder');


        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->context->smarty->tpl_vars['page']->value['meta']['title'] = $data_translate['my_reviews_meta_title'];
            $this->context->smarty->tpl_vars['page']->value['meta']['description'] = $data_translate['my_reviews_meta_description'];
            $this->context->smarty->tpl_vars['page']->value['meta']['keywords'] = $data_translate['my_reviews_meta_keywords'];
        }


        $this->context->smarty->assign('meta_title' , $data_translate['my_reviews_meta_title']);
        $this->context->smarty->assign('meta_description' , $data_translate['my_reviews_meta_description']);
        $this->context->smarty->assign('meta_keywords' , $data_translate['my_reviews_meta_keywords']);




        if(version_compare(_PS_VERSION_, '1.5', '<')){
            $this->context->smarty->assign($module_name.'is14' , 1);
        } else {
            $this->context->smarty->assign($module_name.'is14' , 0);
        }


        $this->context->smarty->assign(array(
            $module_name.'my_reviews' => $data_my_reviews['reviews'],
            $module_name.'paging' => $paging,
            $module_name.'page_text' => $data_translate['page'],

            $module_name.'my_a_link'=> $my_account,

            $module_name.'rev_url' => $rev_url,

            $module_name.'is_reminder' => $is_reminder,
            $module_name.'rem_status'=>$reminder_status,

            $module_name.'myr_msg1'=>$data_translate['myr_msg1'],
            $module_name.'myr_msg2'=>$data_translate['myr_msg2'],
            $module_name.'myr_msg3'=>$data_translate['myr_msg3'],
            $module_name.'myr_msg4'=>$data_translate['myr_msg4'],


        ));




        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->setTemplate('module:' . $module_name . '/views/templates/front/my-reviews17.tpl');
        }else {
            $this->setTemplate('my-reviews.tpl');
        }
	}
}