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

class GsnipreviewuseraccountModuleFrontController extends ModuleFrontController
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

        $cookie = Context::getContext()->cookie;

        $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;
        if (!$id_customer)
            Tools::redirect('authentication.php');


        include_once(dirname(__FILE__).'../../../classes/userprofileg.class.php');
        $obj = new userprofileg();

        include_once(dirname(__FILE__).'../../../gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();
        $_data_translate = $obj_gsnipreview->translateCustom();

        $obj_gsnipreview->setSEOUrls();


        include_once(dirname(__FILE__).'../../../classes/gsnipreviewhelp.class.php');
        $obj_gsnipreviewhelp = new gsnipreviewhelp();

        $data_urls = $obj_gsnipreviewhelp->getSEOURLs();
        $my_account = $data_urls['my_account'];


        $info_customer = $obj->getCustomerInfo();
        $avatar_thumb = $info_customer['avatar_thumb'];
        $exist_avatar = $info_customer['exist_avatar'];
        $is_show = $info_customer['is_show'];


        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->context->smarty->tpl_vars['page']->value['meta']['title'] = $_data_translate['meta_title_myaccount'];
            $this->context->smarty->tpl_vars['page']->value['meta']['description'] = $_data_translate['meta_description_myaccount'];
            $this->context->smarty->tpl_vars['page']->value['meta']['keywords'] = $_data_translate['meta_keywords_myaccount'];
        }

        $this->context->smarty->assign('meta_title' , $_data_translate['meta_title_myaccount']);
        $this->context->smarty->assign('meta_description' , $_data_translate['meta_description_myaccount']);
        $this->context->smarty->assign('meta_keywords' , $_data_translate['meta_keywords_myaccount']);

        $this->context->smarty->assign(array(
            $name_module.'avatar_thumb' => $avatar_thumb,
            $name_module.'exist_avatar' => $exist_avatar,
            $name_module.'is_show' => $is_show,
            $name_module.'my_account'=>$my_account,

            $name_module.'ava_msg8'=>$_data_translate['ava_msg8'],
            $name_module.'ava_msg9'=>$_data_translate['ava_msg9'],

        ));






        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->setTemplate('module:' . $name_module . '/views/templates/front/useraccount17.tpl');
        }else {
            $this->setTemplate('useraccount.tpl');
        }


    }
}