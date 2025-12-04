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

class GsnipreviewstorereviewsModuleFrontController extends ModuleFrontController
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
        $cookie = Context::getContext()->cookie;


        $is_storerev = Configuration::get($name_module.'is_storerev');
        if (!$is_storerev)
            Tools::redirect('index.php');

        include_once(dirname(__FILE__).'../../../gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();
        $_data_translate = $obj_gsnipreview->translateItems();



        $_prefix = $obj_gsnipreview->getPrefixShopReviews();

        $obj_gsnipreview->setStarsImagesSetting();

        $obj_gsnipreview->setSEOUrls();

        $obj_gsnipreview->setStoreReviewsSettings();


        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->context->smarty->tpl_vars['page']->value['meta']['title'] = $_data_translate['meta_title_testimonials'];
            $this->context->smarty->tpl_vars['page']->value['meta']['description'] = $_data_translate['meta_description_testimonials'];
            $this->context->smarty->tpl_vars['page']->value['meta']['keywords'] = $_data_translate['meta_keywords_testimonials'];
        }


        $this->context->smarty->assign('meta_title' , $_data_translate['meta_title_testimonials']);
        $this->context->smarty->assign('meta_description' , $_data_translate['meta_description_testimonials']);
        $this->context->smarty->assign('meta_keywords' , $_data_translate['meta_keywords_testimonials']);



        $this->context->smarty->assign(
            array(
                 $name_module.'msg1' => $_data_translate['msg1'],
                 $name_module.'msg2' => $_data_translate['msg2'],
                 $name_module.'msg3' => $_data_translate['msg3'],
                 $name_module.'msg4' => $_data_translate['msg4'],
                 $name_module.'msg5' => $_data_translate['msg5'],
                 $name_module.'msg6' => $_data_translate['msg6'],
                 $name_module.'msg7' => $_data_translate['msg7'],
                 $name_module.'msg8' => $_data_translate['msg8'],
                 $name_module.'msg9' => $_data_translate['msg9'],
            )
        );



        include_once(dirname(__FILE__).'../../../classes/storereviews.class.php');
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
        $this->context->smarty->assign($name_module.'is_search'.$_prefix, $is_search);
        $this->context->smarty->assign($name_module.'search'.$_prefix, $search);



        $_data = $obj_storereviews->getTestimonials(array('start'=>$start,'step'=>$step,'frat'=>$frat,'is_search'=>$is_search,
                                                        'search'=>$search));

//echo "<pre>"; var_dump($_data);exit;

        $paging = $obj_storereviews->PageNav17($start,$_data['count_all_reviews'],$step, array('frat'=>$frat,'is_search'=>$is_search,
                                                                                            'search'=>$search,'prefix'=>$_prefix)
                                            );


        $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;
        $name_customer = '';
        $email_customer = '';
        $avatar = '';
        if($id_customer) {
            $customer_data = $obj_storereviews->getInfoAboutCustomer(array('id_customer' => $id_customer, 'is_full' => 1));
            $name_customer = $customer_data['customer_name'];
            $email_customer = $customer_data['email'];

            $data_avatar = $obj_storereviews->getAvatarForCustomer(array('id_customer' => $id_customer));
            $avatar = $data_avatar['avatar'];
        }


        $is_buy = $obj_storereviews->checkProductBought(array('id_customer'=>$id_customer));



        $this->context->smarty->assign($name_module.'frat'.$_prefix, $frat);

        $data_rating = $obj_storereviews->getCountRatingForItem();
        $this->context->smarty->assign($name_module.'one'.$_prefix, $data_rating['one']);
        $this->context->smarty->assign($name_module.'two'.$_prefix, $data_rating['two']);
        $this->context->smarty->assign($name_module.'three'.$_prefix, $data_rating['three']);
        $this->context->smarty->assign($name_module.'four'.$_prefix, $data_rating['four']);
        $this->context->smarty->assign($name_module.'five'.$_prefix, $data_rating['five']);





        $this->context->smarty->assign(
            array(

                'reviews'.$_prefix => $_data['reviews'],
                'count_all_reviews'.$_prefix => $_data['count_all_reviews'],
                'paging'.$_prefix => $paging,

                $name_module.'page_text' => $_data_translate['page'],

                'shop_name_snippet'.$_prefix=>Configuration::get('PS_SHOP_NAME'),

                $name_module.'name_c'.$_prefix => $name_customer,
                $name_module.'email_c'.$_prefix => $email_customer,
                $name_module.'c_avatar'.$_prefix => $avatar,

                $name_module.'is_buy'.$_prefix => $is_buy,
                $name_module.'id_customer'.$_prefix=>$id_customer
            )
        );





        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->setTemplate('module:' . $name_module . '/views/templates/front/storereviews17.tpl');
        }else {
            $this->setTemplate('storereviews.tpl');
        }


    }
}