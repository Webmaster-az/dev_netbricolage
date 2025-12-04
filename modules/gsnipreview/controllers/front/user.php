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

class GsnipreviewuserModuleFrontController extends ModuleFrontController
{
	
	public function init()
	{
    	parent::init();
	}
	
	public function setMedia()
	{
		parent::setMedia();
        $this->addJqueryPlugin(array('fancybox'));
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

        include_once(dirname(__FILE__).'../../../classes/userprofileg.class.php');
        $obj = new userprofileg();

        $user_id = (int)Tools::getValue('uid');


        include_once(dirname(__FILE__).'../../../classes/gsnipreviewhelp.class.php');
        $obj_gsnipreviewhelp = new gsnipreviewhelp();


        $data_urls = $obj_gsnipreviewhelp->getSEOURLs();
        $users_url = $data_urls['users_url'];

        $rev_url = $data_urls['rev_url'];
        $user_url = $data_urls['user_url'].$user_id;
        $my_account = $data_urls['my_account'];


        if(!$user_id)
            Tools::redirect($users_url);

        $info = $obj->getShopperInfo(array('shopper_id' => $user_id));


        if(sizeof($info['customer'])==0)
            Tools::redirect($users_url);



        $this->context->smarty->assign(array(
            $name_module.'customer' => $info['customer']
        ));

        $_data_translate = $obj_gsnipreview->translateCustom();


        $title = @$info['customer'][0]['firstname']." " .@$info['customer'][0]['lastname']. " ".$_data_translate['profile'];



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



        $this->context->smarty->assign($name_module.'is_search', $is_search);
        $this->context->smarty->assign($name_module.'search', $search);
        $this->context->smarty->assign($name_module.'frat', $frat);
        $this->context->smarty->assign($name_module.'gp', $gp);
        $this->context->smarty->assign($name_module.'isgp', Tools::isSubmit('gp'));




        $data_translate = $obj_gsnipreview->translateCustom();

        include_once(dirname(__FILE__).'../../../classes/gsnipreviewhelp.class.php');
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
                'user_url' => $user_url,
            )
        );

        $this->context->smarty->assign(array(
            $name_module.'user_id' => $user_id,
            $name_module.'user_reviews' => $data_my_reviews['reviews'],

            $name_module.'paging' => $paging,
            $name_module.'page_text' => $data_translate['page'],

            $name_module.'my_a_link'=> $my_account,

            $name_module.'rev_url' => $rev_url,



        ));

        ## product reviews for customer ##


        ## store reviews by customer ##

        $_prefix = $obj_gsnipreview->getPrefixShopReviews();

        $obj_gsnipreview->setStoreReviewsSettings();


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
        $this->context->smarty->assign($name_module.'frat'.$_prefix, $frat);
        $this->context->smarty->assign($name_module.'p'.$_prefix, $p);
        $this->context->smarty->assign($name_module.'isp'.$_prefix, Tools::isSubmit('p'.$_prefix));


        $_data = $obj_storereviews->getTestimonials(array('id_customer'=>$user_id,'start'=>$start,'step'=>$step,'frat'=>$frat,'is_search'=>$is_search,'search'=>$search));



        $paging = $obj_storereviews->PageNav17($start,$_data['count_all_reviews'],$step,
                                            array('frat'=>$frat,'is_search'=>$is_search,'search'=>$search,'prefix'=>$_prefix,'user_url' => $user_url,)
        );



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
                'shop_name_snippet'.$_prefix=>Configuration::get('PS_SHOP_NAME'),

                $name_module.'id_customer'.$_prefix=>$user_id
            )
        );

        ## store reviews by customer ##




        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->context->smarty->tpl_vars['page']->value['meta']['title'] = $title;
            $this->context->smarty->tpl_vars['page']->value['meta']['description'] = $title;
            $this->context->smarty->tpl_vars['page']->value['meta']['keywords'] = $title;
        }

        $this->context->smarty->assign('meta_title' , $title);
        $this->context->smarty->assign('meta_description' , $title);
        $this->context->smarty->assign('meta_keywords' , $title);



        if(version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->setTemplate('module:' . $name_module . '/views/templates/front/user17.tpl');
        }else {
            $this->setTemplate('user.tpl');
        }


    }
}