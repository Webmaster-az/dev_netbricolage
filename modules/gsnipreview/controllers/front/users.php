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

class GsnipreviewusersModuleFrontController extends ModuleFrontController
{
	
	public function init()
	{

		parent::init();
	}
	
	public function setMedia()
	{
		parent::setMedia();
    }

	
	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();


        $name_module = 'gsnipreview';

        $is_uprof = Configuration::get($name_module.'is_uprof');
        if (!$is_uprof)
            Tools::redirect('index.php');

        include_once(dirname(__FILE__).'../../../gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();


        $obj_gsnipreview->setSEOUrls();


        $_data_translate = $obj_gsnipreview->translateCustom();


        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->context->smarty->tpl_vars['page']->value['meta']['title'] = $_data_translate['meta_title_shoppers'];
            $this->context->smarty->tpl_vars['page']->value['meta']['description'] = $_data_translate['meta_description_shoppers'];
            $this->context->smarty->tpl_vars['page']->value['meta']['keywords'] = $_data_translate['meta_keywords_shoppers'];
        }

        $this->context->smarty->assign('meta_title' , $_data_translate['meta_title_shoppers']);
        $this->context->smarty->assign('meta_description' , $_data_translate['meta_description_shoppers']);
        $this->context->smarty->assign('meta_keywords' , $_data_translate['meta_keywords_shoppers']);


        include_once(dirname(__FILE__).'../../../classes/userprofileg.class.php');
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
        $this->context->smarty->assign($name_module.'is_search', $is_search);
        $this->context->smarty->assign($name_module.'search', $search);
        $this->context->smarty->assign($name_module.'gp', $gp);


        $info_customers = $obj->getShoppersList(array('start' => $start,'step'=>$step,'is_search'=>$is_search,'search'=>$search));


        include_once(dirname(__FILE__).'../../../classes/gsnipreviewhelp.class.php');
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


        $this->context->smarty->assign(array(
            $name_module.'customers' => $info_customers['customers'],
            $name_module.'data_count_customers' => $info_customers['data_count_customers'],
            $name_module.'paging' => $paging,
            $name_module.'users_url'=>$users_url,
            $name_module.'page_text' => $_data_translate['page'],
        ));




        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->setTemplate('module:' . $name_module . '/views/templates/front/users17.tpl');
        }else {
            $this->setTemplate('users.tpl');
        }


    }
}