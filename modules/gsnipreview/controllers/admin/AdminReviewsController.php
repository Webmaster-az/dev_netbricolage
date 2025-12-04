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

ob_start();
require_once(_PS_MODULE_DIR_ . 'gsnipreview/classes/GsnipreviewItems.php');

class AdminReviewsController extends ModuleAdminController{

    private $_name_controller = 'AdminReviews';
    private $_name_module = 'gsnipreview';
    private $path_img_cloud;

	public function __construct()

	{

            $this->bootstrap = true;
            $this->context = Context::getContext();
            $this->table = 'gsnipreview';


            $this->identifier = 'id';
            $this->className = 'GsnipreviewItems';


            $this->lang = false;

            $this->_orderBy = 'id';
            $this->_orderWay = 'DESC';


            $this->allow_export = false;

            $this->list_no_link = true;

            $id_lang =  $this->context->cookie->id_lang;
            $this->_id_lang = $id_lang;
            $id_shop =  $this->context->shop->id;
            $this->_id_shop = $id_shop;


            $this->_select .= 'a.id, a.customer_name,a.title_review, a.id_product as id_product_real, a.rating, a.time_add, a.is_abuse, a.id_shop, a.id_lang, a.id_customer ';

            $this->_select .= ', (SELECT ga2c.avatar_thumb from '._DB_PREFIX_.''.$this->_name_module.'_avatar2customer ga2c
                                                    WHERE ga2c.id_customer = a.id_customer
                    ) as avatar_thumb';

            $this->_select .= ', (SELECT pl.`name`
	            FROM `'._DB_PREFIX_.'product` p
	            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).' AND pl.`id_shop` = '.$id_shop.')
	            WHERE p.`active` = 1 AND p.`id_product` = a.id_product
	            ) as id_product';

        $this->_select .= ', (SELECT pl.`link_rewrite`
	            FROM `'._DB_PREFIX_.'product` p
	            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).' AND pl.`id_shop` = '.$id_shop.')
	            WHERE p.`active` = 1 AND p.`id_product` = a.id_product
	            ) as product_link_rewrite';


            $this->_select .= ', (SELECT sh.`name`
	            FROM `'._DB_PREFIX_.'shop` sh
	            WHERE sh.`active` = 1 AND sh.deleted = 0 AND sh.`id_shop` = a.id_shop
	            ) as shop_name';


        $this->_select .= ', (SELECT count(*)
	            FROM `'._DB_PREFIX_.'gsnipreview_review_helpfull` hv
	            WHERE hv.`review_id` = a.id and helpfull = 1
	            ) as helpful_votes ';

        $this->_select .= ', (SELECT group_concat(l.`iso_code` SEPARATOR \', \')
                    FROM `'._DB_PREFIX_.'lang` l
                    JOIN
                    `'._DB_PREFIX_.'lang_shop` ls
                    ON(l.id_lang = ls.id_lang)
                    WHERE l.`active` = 1 AND ls.id_shop = '.(int)$id_shop.' AND l.`id_lang`
                    IN( select pt_d.id_lang FROM `'._DB_PREFIX_.$this->_name_module.'` pt_d WHERE pt_d.id = a.id)) as lang';


            $this->addRowAction('edit');
            $this->addRowAction('delete');
            //$this->addRowAction('view');
            //$this->addRowAction('&nbsp;');


       ### shops ###

                $shops = Shop::getShops();
                $data_shops = array();
                foreach($shops as $_shop){
                    $data_shops[$_shop['id_shop']]= $_shop['name'];
                }
       ### shops ###


        if((Configuration::get('PS_REWRITING_SETTINGS') || version_compare(_PS_VERSION_, '1.5', '<'))){
            $is_rewrite = 1;
        } else {
            $is_rewrite = 0;
        }


        ### rating ###
        $data_rating = array();
        for($i=1;$i<=5;$i++){
            $data_rating[$i] = $i;
        }


        switch(Configuration::get($this->table.'stylestars')){
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

        ### rating ###

        ### languages ###
        $data_languages = array();
        $all_languages = Language::getLanguages(true);
        foreach($all_languages as $_language){
            $data_languages[$_language['id_lang']]=$_language['name'];
        }
        ### languages ###

        if (defined('_PS_HOST_MODE_'))
            $_is_cloud = 1;
        else
            $_is_cloud = 0;


        // for test
        //$_is_cloud = 1;
        // for test

        if($_is_cloud){
            $this->path_img_cloud = 'modules'.DIRECTORY_SEPARATOR.$this->table.DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR;
        } else {
            $this->path_img_cloud = "upload".DIRECTORY_SEPARATOR.$this->table.DIRECTORY_SEPARATOR;

        }


        ## for user URL ##
        $all_laguages = Language::getLanguages(true);
        $is_multilang = 0;
        if(sizeof($all_laguages)>1){
            $is_multilang = 1;
        }
        ## for user URL ##


        require_once(_PS_MODULE_DIR_ . '' . $this->table . '/classes/gsnipreviewhelp.class.php');
        $obj_gsnipreviewhelp = new gsnipreviewhelp();

        $all_products_data = $obj_gsnipreviewhelp->getAllProductsForReviews();

        $all_products = array();
        $all_products_links = array();
        foreach($all_products_data as $value){

            $_product_id = $value['id_product'];
            $_product_info1 = $obj_gsnipreviewhelp->getProduct(array('id'=>$_product_id));

            foreach($_product_info1['product'] as $_item_product1){
                $name_product1 = isset($_item_product1['name'])?Tools::stripslashes($_item_product1['name']):'';
                if(Tools::strlen($name_product1)==0) continue;


                $all_products[$_product_id] = $name_product1;


                $all_lang = $obj_gsnipreviewhelp->getAllLangForReviews();

                //var_dumP($all_lang);exit;

                foreach($all_lang as $id_lang_product) {

                    $link = Context::getContext()->link;
                    $product_url = $link->getProductLink((int)$_product_id, null, null, null, $id_lang_product['id_lang'], null, 0, false);

                    $all_products_links[$id_lang_product['id_lang']][$_product_id] = $product_url;
                }

            }
        }


        require_once(_PS_MODULE_DIR_ . '' . $this->_name_module . '/classes/gsnipreviewhelp.class.php');
        $obj_gsnipreviewhelp = new gsnipreviewhelp();

        $data_seo_url = $obj_gsnipreviewhelp->getSEOURLs();
        $user_url = $data_seo_url['user_url'];


            $this->fields_list = array(
                'id' => array(
                    'title' => $this->l('ID'),
                    'align' => 'center',
                    'search' => true,
                    'orderby' => true,

                ),


                'customer_name' => array(
                    'title' => $this->l('User'),
                    'width' => 'auto',
                    'orderby' => true,
                    'type_custom' => 'customer_name',
                    'is_multilang'=>$is_multilang,
                    'base_dir_ssl' => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
                    'is_uprof'=>Configuration::get($this->_name_module.'is_uprof'),
                    'user_url'=>$user_url,
                ),

                'title_review' => array(
                    'title' => $this->l('Title'),
                    'width' => 'auto',
                    'orderby' => true,
                    'type_custom' => 'title_review',
                    'is_rewrite' => $is_rewrite,

                ),
                'id_product' => array(
                    'title' => $this->l('Product'),
                    'width' => 'auto',
                    'search' => false,
                    'orderby' => false,
                    'type' => 'select', 'list' => $all_products  , 'all_products_links' => $all_products_links,
                    'filter_key' => 'a!id_product',
                    'align' => 'center',
                    'type_custom'=>'id_product',


                ),



                'rating' => array(
                    'title' => $this->l('Rating'),
                    'width' => 'auto',
                    'type' => 'select',
                    'orderby' => FALSE,
                    'list' => $data_rating,
                    'filter_key' => 'a!rating',
                    'type_custom' => 'rating',
                    'activestar'=>$activestar,
                    'noactivestar'=>$noactivestar,

                ),

                'helpful_votes' => array(
                    'title' => $this->l('Votes'),
                    'width' => 'auto',
                    'search' => false,
                    'align' => 'center',
                    'hint' => $this->l('Helpful votes'),

                ),

                'time_add' => array(
                    'title' => $this->l('Date'),
                    'width' => 'auto',
                    'search' => false,
                    'hint' => $this->l('Date add'),

                ),

                'lang' => array(
                    'title' => $this->l('Language'),
                    'width' => 'auto',
                    'type' => 'select',
                    'orderby' => FALSE,
                    'list' => $data_languages,
                    'filter_key' => 'a!id_lang',

                ),

                'shop_name' => array(
                    'title' => $this->l('Shop'),
                    'width' => 'auto',
                    'type' => 'select',
                    'orderby' => FALSE,
                    'list' => $data_shops,
                    'filter_key' => 'a!id_shop',

                ),



                'is_abuse' => array(
                    'title' => $this->l('Abuse'),
                    'width' => 'auto',
                    'type' => 'select',
                    'icon' => array(
                                    0 => array('src' => '../../modules/'.$this->table.'/views/img/ok.gif', 'alt' => $this->l('Review is NOT Abusive'),'value'=>0),
                                    1 => array('src' => '../../modules/'.$this->table.'/views/img/warn2.png', 'alt' => $this->l('Someone send abuse. Click here to view abuse and set review is NOT Abusive'),'value'=>1),
                                    ),
                    'list' => array(0 => $this->l('Review is NOT Abusive'), 1=> $this->l('Review is Abusive')),
                    'filter_key' => 'a!is_abuse',
                    'hint' => $this->l('You can see when someone send abuse'),
                    'orderby' => false,
                    'align' => 'center',
                    'type_custom' => 'is_abuse',
                    'token' => Tools::getAdminToken('AdminCustomers'.((int)(Tab::getIdFromClassName('AdminCustomers'))).(int)(Context::getContext()->employee->id)),


                ),

                'is_changed' => array(
                    'title' => $this->l('Change?'),
                    'width' => 'auto',
                    'type' => 'select',
                    'icon' => array(
                        0 => array('src' => '../../modules/'.$this->table.'/views/img/edit.gif', 'alt' => $this->l('Click here to send suggest user change the review'),'value'=>0),
                        1 => array('src' => '../../modules/'.$this->table.'/views/img/time.gif', 'alt' => $this->l('The changed customer review is pending modification'),'value'=>1),
                        2 => array('src' => '../../modules/'.$this->table.'/views/img/edit_ok.gif', 'alt' => $this->l('The customer has changed his review'),'value'=>2),
                    ),
                    'list' => array(0 => $this->l('Review/content is good'), 1=> $this->l('The customer review is pending modification'), 2=> $this->l('The customer has changed his review')),
                    'filter_key' => 'a!is_changed',
                    'orderby' => false,
                    'align' => 'center',
                    'hint' => $this->l('You can ask the user to change the review with a higher rating, if he write a bad product review with bad ratings'),
                    'type_custom' => 'is_changed',
                    'token' => Tools::getAdminToken('AdminCustomers'.((int)(Tab::getIdFromClassName('AdminCustomers'))).(int)(Context::getContext()->employee->id)),

                ),


                'is_active' => array(
                    'title' => $this->l('Status'),
                    'width' => 40,
                    'align' => 'center',
                    'type' => 'bool',
                    'orderby' => FALSE,
                    'type_custom' => 'is_active',
                ),
            );


            if(Configuration::get($this->_name_module.'is_avatarr') == 1){

                $this->array_push_pos($this->fields_list, 1,
                    array(
                    'title' => $this->l('Avatar'),
                    'width' => 'auto',
                    'search' => false,
                    'align' => 'center',
                    'orderby' => FALSE,
                    'type_custom' => 'avatar',
                    'base_dir_ssl' => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
                    'path_img_cloud'=>$this->path_img_cloud.'avatar'.DIRECTORY_SEPARATOR,

                    ),

                    'avatar'
                );
            }

            $this->bulk_actions = array(
                'delete' => array(
                    'text' => $this->l('Delete selected'),
                    'icon' => 'icon-trash',
                    'confirm' => $this->l('Delete selected items?')
                )
            );



		parent::__construct();
		
	}




    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        $list = parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
        $this->_listsql = false;
        return $list;
    }

    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['add_item'] = array(
                'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                'desc' => $this->l('Add new review', null, null, false),
                'icon' => 'process-icon-new'
            );
        }

        parent::initPageHeaderToolbar();
    }

    public function initToolbar() {

        parent::initToolbar();
        /*$this->toolbar_btn['add_item'] = array(
                                            'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                                            'desc' => $this->l('Add new review', null, null, false),
                                        );
        *///unset($this->toolbar_btn['new']);

    }



    public function postProcess()
    {


        require_once(_PS_MODULE_DIR_ . '' . $this->table . '/classes/gsnipreviewhelp.class.php');
        $gsnipreviewhelp_obj = new gsnipreviewhelp();


        if (Tools::isSubmit('add_item')) {
            ## add item ##
            $title_review = Tools::getValue("title_review");
            $text_review = Tools::getValue("text_review");
            $is_active = (int)Tools::getValue("is_active");
            $time_add = Tools::getValue("time_add");
            $id_lang = Tools::getValue("ids_lang");
            $id_shop = Tools::getValue("ids_shop");
            $id_product = Tools::getValue('inputAccessories');
            $id_customer = Tools::getValue('inputCustomers');


            if (!$id_product)
                $this->errors[] = Tools::displayError('Please select product');

            if (!$id_customer)
                $this->errors[] = Tools::displayError('Please select Customer');

            if(!$text_review)
                 $this->errors[] = Tools::displayError('Please fill the Text');

            if (!$title_review)
                $this->errors[] = Tools::displayError('Please fill the Title');

            if(!$time_add)
                $this->errors[] = Tools::displayError('Please select Date Add');

            ### ratings ###
            $ratings = array();


            $criterions = $gsnipreviewhelp_obj->getReviewCriteria(array('id_lang' => $id_lang, 'id_shop' => $gsnipreviewhelp_obj->getIdShop()));
            if (sizeof($criterions) > 0) {

                foreach ($criterions as $criterion) {
                    $id_criterion = $criterion['id_gsnipreview_review_criterion'];
                    $rating_criterion = Tools::getValue('rat_rel' . $id_criterion);
                    if ($rating_criterion)
                        $ratings[$id_criterion] = $rating_criterion;
                }

            } else {
                if(sizeof($ratings)==0){
                    $ratings[0] = Tools::getValue("rat_rel");
                }
            }



            if (sizeof($ratings) == 0) {
                $this->errors[] = Tools::displayError('Please select Rating');
            }

            ### ratings ###

            if (empty($this->errors)) {



                $data = array(
                    'id_customer' => $id_customer,
                    'title' => $title_review,
                    'text_review' => $text_review,
                    'is_active' => $is_active,
                    'time_add' => $time_add,
                    'id_product' => $id_product,
                    'ratings' => $ratings,
                    'id_shop' => $id_shop,
                    'id_lang' => $id_lang,

                );

                //echo "<pre>"; var_dump($data);exit;


                $gsnipreviewhelp_obj->saveReviewAdmin($data);
                Tools::redirectAdmin(self::$currentIndex . '&conf=3&token=' . Tools::getAdminTokenLite($this->_name_controller));
            } else {
                $this->display = 'add';
                return FALSE;
             }
            ## add item ##

        } elseif(Tools::isSubmit('update_item')) {
                $id = Tools::getValue('id');
                ## update item ##


                $name = Tools::getValue("customer_name");
                $email = Tools::getValue("email");
                $title_review = Tools::getValue("title_review");
                $text_review = Tools::getValue("text_review");
                $is_active = (int)Tools::getValue("is_active");
                $time_add = Tools::getValue("time_add");
                $id_lang = Tools::getValue("id_lang");


                $post_images = Tools::getValue("post_images");
                $id_customer = Tools::getValue("id_customer");

                $admin_response = Tools::getValue("admin_response");
                $is_noti = Tools::getValue("is_noti");
                $is_display_old = Tools::getValue("is_display_old");
                ### ratings ###


                $ratings = array();


                $criterions = $gsnipreviewhelp_obj->getReviewCriteria(array('id_lang' => $id_lang, 'id_shop' => $gsnipreviewhelp_obj->getIdShop()));
                if (sizeof($criterions) > 0) {

                    foreach ($criterions as $criterion) {
                        $id_criterion = $criterion['id_gsnipreview_review_criterion'];
                        $rating_criterion = Tools::getValue('rat_rel' . $id_criterion);
                        if ($rating_criterion)
                            $ratings[$id_criterion] = $rating_criterion;
                    }

                }

                if (sizeof($ratings) == 0) {
                    $ratings[0] = 0;
                }

                $rating_total = (int)Tools::getValue("rat_rel");
                ### ratings ###

                /*if(!$name)
                    $this->errors[] = Tools::displayError('Please fill the Customer Name');

                if(!$email)
                    $this->errors[] = Tools::displayError('Please fill the Email Name');*/

                if(!$text_review && Configuration::get($this->table.'text_on'))
                    $this->errors[] = Tools::displayError('Please fill the Text');

                if (!$title_review && Configuration::get($this->table.'title_on'))
                    $this->errors[] = Tools::displayError('Please fill the Title');

                if(!$time_add)
                    $this->errors[] = Tools::displayError('Please select Date Add');




            if (empty($this->errors)) {
                $data = array('name' => $name,
                    'email' => $email,
                    'title_review' => $title_review,
                    'text_review' => $text_review,
                    'is_active' => $is_active,
                    'time_add' => $time_add,
                    'id' => $id,
                    'ratings' => $ratings,
                    'rating_total' => $rating_total,

                    'is_changed'=>0,

                    'post_images' => $post_images,
                    'id_customer' => $id_customer,

                    'admin_response'=>$admin_response,
                    'is_noti'=>$is_noti,
                    'is_display_old'=>$is_display_old,

                );

                $gsnipreviewhelp_obj->updateReview($data);
                Tools::redirectAdmin(self::$currentIndex . '&conf=4&token=' . Tools::getAdminTokenLite($this->_name_controller));
            }else{

                $this->display = 'add';
                return FALSE;
            }

            ## update item ##
            } elseif (Tools::isSubmit('submitBulkdelete' . $this->table)) {
                ### delete more than one  items ###

                if ($this->tabAccess['delete'] === '1' || $this->tabAccess['delete'] === true) {
                    if (Tools::getValue($this->list_id . 'Box')) {


                        $object = new $this->className();

                        if ($object->deleteSelection(Tools::getValue($this->list_id . 'Box'))) {
                            Tools::redirectAdmin(self::$currentIndex . '&conf=2' . '&token=' . $this->token);
                        }
                        $this->errors[] = Tools::displayError('An error occurred while deleting this selection.');
                    } else {
                        $this->errors[] = Tools::displayError('You must select at least one element to delete.');
                    }
                } else {
                    $this->errors[] = Tools::displayError('You do not have permission to delete this.');
                }
                ### delete more than one  items ###
            } elseif (Tools::isSubmit('delete' . $this->table)) {
                ## delete item ##


                $id = Tools::getValue('id');

                $data = array('id' => $id);


                $gsnipreviewhelp_obj->delete($data);

                Tools::redirectAdmin(self::$currentIndex . '&conf=1&token=' . Tools::getAdminTokenLite($this->_name_controller));
                ## delete item ##
            } else {
               return parent::postProcess(true);
            }




    }


    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->context->controller->addCSS(__PS_BASE_URI__.'js/jquery/plugins/autocomplete/jquery.autocomplete.css');
        $this->context->controller->addJs(__PS_BASE_URI__.'js/jquery/plugins/autocomplete/jquery.autocomplete.js');

        $this->context->controller->addJs(__PS_BASE_URI__.'modules/'.$this->table.'/views/js/gsnipreview-admin.js');


        $this->context->controller->addJs(__PS_BASE_URI__ . 'modules/' . $this->table . '/views/js/r_stars.admin.js');
        $this->addJqueryUi(array('ui.core','ui.widget','ui.datepicker'));

        $this->context->controller->addCSS(__PS_BASE_URI__.'modules/'.$this->table.'/views/css/gsnipreview.css');

        $this->context->controller->addCSS(__PS_BASE_URI__.'modules/'.$this->table.'/views/css/admin.css');

    }


    public function renderForm()
    {
        if (!($this->loadObject(true)))
            return;

        if (Validate::isLoadedObject($this->object)) {
            $this->display = 'update';
        } else {
            $this->display = 'add';
        }


        $id = (int)Tools::getValue('id');

        require_once(_PS_MODULE_DIR_ . ''.$this->table.'/classes/gsnipreviewhelp.class.php');
        $obj_reviewshelp = new gsnipreviewhelp();


        require_once(_PS_MODULE_DIR_ . '' . $this->_name_module . '/gsnipreview.php');
        $gsnipreview = new gsnipreview();
        $is_demo = $gsnipreview->is_demo;
        if($is_demo){
            $is_demo = '<div class="bootstrap">
								<div class="alert alert-warning">
									<button type="button" data-dismiss="alert" class="close">×</button>
									<strong>Warning</strong><br>
                                    Feature disabled on the demo mode
                                    &zwnj;</div>
							</div>';
        } else {
            $is_demo = '';
        }

        if($id) {

            $_data = $obj_reviewshelp->getItem(array('id'=>$id));
            $criterions = isset($_data['reviews'][0]['criterions']) ? $_data['reviews'][0]['criterions'] : array();
            $rating = isset($_data['reviews'][0]['rating']) ? $_data['reviews'][0]['rating'] :0 ;
            $name_lang =  isset($_data['reviews'][0]['name_lang']) ? $_data['reviews'][0]['name_lang'] :'' ;
            $id_lang =  isset($_data['reviews'][0]['name_lang']) ? $_data['reviews'][0]['id_lang'] :'' ;
            $review_url = isset($_data['reviews'][0]['review_url']) ? $_data['reviews'][0]['review_url'] :'' ;
            $ip = isset($_data['reviews'][0]['ip']) ? $_data['reviews'][0]['ip'] :'' ;
            $time_add = isset($_data['reviews'][0]['time_add']) ? $_data['reviews'][0]['time_add'] :'' ;

            $avatar = isset($_data['reviews'][0]['avatar']) ? $_data['reviews'][0]['avatar'] :'' ;

            $is_exist_ava = isset($_data['reviews'][0]['is_exist']) ? $_data['reviews'][0]['is_exist'] :0 ;

            $files = isset($_data['reviews'][0]['files']) ? $_data['reviews'][0]['files'] :array() ;


            $id_product = isset($_data['reviews'][0]['id_product']) ? $_data['reviews'][0]['id_product'] :'' ;
            $link = Context::getContext()->link;
            $product_url = $link->getProductLink((int)$id_product, null, null, null,$id_lang, null, 0, false);

            $_obj_product = new Product($id_product,null,$id_lang);
            $name_product = $_obj_product->name;


            // is cloud ?? //
            if(defined('_PS_HOST_MODE_')){
                $logo_img_path = '../modules/'.$this->_name_module.'/upload/'.$avatar;
            } else {
                $logo_img_path = '../upload/'.$this->_name_module.'/'.$avatar;
            }
            // is cloud ?? //

            $id_customer = isset($_data['reviews'][0]['id_customer']) ? $_data['reviews'][0]['id_customer'] :0 ;

            $admin_url_to_customer = isset($_data['reviews'][0]['user_url']) ? $_data['reviews'][0]['user_url'].$id_customer :0 ;


            $customer_name = isset($_data['reviews'][0]['customer_name_full']) ? $_data['reviews'][0]['customer_name_full'] :$_data['reviews'][0]['customer_name'] ;



        } else {

            $cookie = Context::getContext()->cookie;
            $id_lang = $cookie->id_lang;
            $criterions =  $obj_reviewshelp->getReviewCriteria(array('id_lang'=>$id_lang,'id_shop'=>$obj_reviewshelp->getIdShop()));


            $rating = 0;
            $name_lang = '';
            $review_url = '';
            $ip = '';
            $time_add = date("Y-m-d H:i:s");
            $id_lang = 0;
            $logo_img_path = '';
            $id_customer = 0;
            $files = array();
        }

        switch(Configuration::get($this->table.'stylestars')){
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

        if($id){
            $title_item_form = $this->l('Edit review:');
        } else{
            $title_item_form = $this->l('Add new review:');
        }



        $this->fields_form = array(
            'tinymce' => TRUE,
            'legend' => array(
                'title' => $title_item_form,
                'image' => '../modules/'.$this->table.'/views/img/star-active-yellow.png'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Title:'),
                    'name' => 'title_review',
                    'id' => 'title_review',
                    'lang' => false,
                    'required' => Configuration::get($this->table.'title_on')?TRUE:FALSE,
                    'size' => 5000,
                    'maxlength' => 5000,

                ),

                array(
                    'type' => 'textarea',
                    'label' => $this->l('Text:'),
                    'name' => 'text_review',
                    'id' => 'text_review',
                    'required' => Configuration::get($this->table.'text_on')?TRUE:FALSE,
                    'autoload_rte' => FALSE,
                    'lang' => FALSE,
                    'rows' => 8,
                    'cols' => 40,

                ),

                array(
                    'type' => 'text_rating_custom',
                    'label' => $this->l('Rating:'),
                    'name' => 'rating',
                    'id' => 'rating',
                    'lang' => false,
                    'required' => TRUE,
                    'criterions' => $criterions,
                    'activestar' => $activestar,
                    'noactivestar' => $noactivestar,
                    'base_dir_ssl' => _PS_BASE_URL_SSL_.__PS_BASE_URI__,
                    'rating' => $rating,

                ),
                array(
                    'type' => 'item_date',
                    'label' => $this->l('Date Add:'),
                    'name' => 'date_on',
                    'time_add' => $time_add,
                    'required' => TRUE,
                ),

                array(
                    'type' => 'switch',
                    'label' => $this->l('Status:'),
                    'name' => 'is_active',
                    'required' => TRUE,
                    'class' => 't',
                    'is_bool' => TRUE,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                ),

            ),


        );



        ### add field Where to display, only when add tab ###
        if($id) {


            $this->array_push_pos($this->fields_form['input'],0,
                array(
                    'type' => 'id_item',
                    'label' => $this->l('ID:'),

                    'name' => 'id_item',
                    'values'=> $id,

                )
            );

            $this->array_push_pos($this->fields_form['input'],1,
                array(
                    'type' => 'language_item',
                    'label' => $this->l('Language:'),

                    'name' => 'language_item',
                    'values'=> $name_lang,
                    'id_lang' => $id_lang,

                )
            );

            $this->array_push_pos($this->fields_form['input'],2,
                array(
                    'type' => 'review_url',
                    'label' => $this->l('Review URL:'),

                    'name' => 'review_url',
                    'values'=> $review_url,

                )
            );

            $this->array_push_pos($this->fields_form['input'],3,
                array(
                    'type' => 'review_url',
                    'label' => $this->l('Product:'),

                    'name' => 'review_url',
                    'values'=> $product_url,
                    'name_product' => $name_product,

                )
            );

            $this->array_push_pos($this->fields_form['input'],4,
                array(
                    'type' => 'ip_item',
                    'label' => $this->l('IP:'),

                    'name' => 'ip_item',
                    'values'=> $ip,

                )
            );

            if(Configuration::get($this->_name_module.'is_avatarr') == 1) {
                $this->array_push_pos($this->fields_form['input'], 5,
                    array(
                        'type' => 'avatar_custom',
                        'label' => $this->l('Avatar:'),
                        'name' => 'avatar-review',
                        'id' => 'avatar-review',
                        'lang' => false,
                        'required' => false,
                        'value' => $avatar,
                        'path_img_cloud' => $this->path_img_cloud,
                        'base_dir_ssl' => _PS_BASE_URL_SSL_ . __PS_BASE_URI__,

                        'logo_img' => $avatar,
                        'logo_img_path' => $logo_img_path,

                        'id_item' => $id,

                        'desc' => $this->l('Allow formats *.jpg; *.jpeg; *.png; *.gif.'),
                        'is_demo' => $is_demo,
                        'max_upload_info' => ini_get('upload_max_filesize'),

                        'id_customer' => $id_customer,
                        'is_exist_ava' => $is_exist_ava,

                    )
                );
            }


            $this->array_push_pos($this->fields_form['input'],6,
                array(
                    'type' => 'text',
                    'label' => $this->l('Customer Name:'),
                    'name' => 'customer_name',
                    'id' => 'customer_name',
                    'lang' => false,
                    'required' => false,
                    'size' => 50,
                    'maxlength' => 50,


                )
            );

            $this->array_push_pos($this->fields_form['input'],7,
                array(
                    'type' => 'text',
                    'label' => $this->l('Customer Email:'),
                    'name' => 'email',
                    'id' => 'email',
                    'lang' => false,
                    'required' => false,
                    'size' => 50,
                    'maxlength' => 50,


                )
            );

            if($id_customer && Configuration::get($this->_name_module.'is_uprof') == 1){
                $this->array_push_pos($this->fields_form['input'],8,
                    array(
                        'type' => 'customer_url',
                        'label' => $this->l('Customer:'),

                        'name' => 'customer_url',
                        'values'=> $customer_name,
                        'url'=>$admin_url_to_customer,


                    )
                );
            }


            $this->array_push_pos($this->fields_form['input'],11,
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Shop owner reply:'),
                    'name' => 'admin_response',
                    'id' => 'admin_response',
                    'required' => false,
                    'autoload_rte' => FALSE,
                    'lang' => FALSE,
                    'rows' => 8,
                    'cols' => 40,

                )
            );

            $this->array_push_pos($this->fields_form['input'],12,
                array(
                    'type' => 'checkbox_custom',
                    'label' => $this->l('Send "Shop owner reply" notification to the customer:'),
                    'name' => 'is_noti',
                    'values' => array(
                        'value' => 0
                    ),


                )
            );


            $this->array_push_pos($this->fields_form['input'],13,
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display "Shop owner reply" on the site'),
                    'name' => 'is_display_old',
                    'required' => FALSE,
                    'class' => 't',
                    'is_bool' => TRUE,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                )
            );

            if(Configuration::get($this->_name_module.'is_filesr') == 1) {
                if (count($files) > 0) {
                    $this->array_push_pos($this->fields_form['input'], 14,
                        array(
                            'type' => 'files_custom',
                            'label' => $this->l('Files:'),
                            'name' => 'files-review',
                            'id' => 'files-review',
                            'lang' => false,
                            'required' => false,
                            'value' => $files,

                            'base_dir_ssl' => _PS_BASE_URL_SSL_ . __PS_BASE_URI__,


                            'is_demo' => $is_demo,


                        )
                    );
                }
            }





        } else {
            $this->array_push_pos($this->fields_form['input'],0,
                array(
                    'type' => 'shop_item',
                    'label' => $this->l('Select your shop:'),
                    'required' => TRUE,
                    'name' => 'shop_item',
                    'values'=> Shop::getShops(),

                )
            );

            $this->array_push_pos($this->fields_form['input'],1,
                array(
                    'type' => 'text_custom',
                    'label' => $this->l('Select product:'),
                    'name' => 'selected_products',
                    'id' => 'selected_products',
                    'required' => TRUE,
                    'size' => 50,
                    'maxlength' => 50,
                    'selected_products' => array(),

                )
            );

            $this->array_push_pos($this->fields_form['input'],2,
                array(
                    'type' => 'text_custom_customer',
                    'label' => $this->l('Select customer:'),
                    'name' => 'selected_customers',
                    'id' => 'selected_customers',
                    'required' => TRUE,
                    'size' => 50,
                    'maxlength' => 50,
                    'selected_customers' => array(),
                    'token' => Tools::getAdminToken('AdminCartRules'.((int)(Tab::getIdFromClassName('AdminCartRules'))).(int)(Context::getContext()->employee->id)),

                )
            );

            $this->array_push_pos($this->fields_form['input'],3,
                array(
                    'type' => 'language_item_add',
                    'label' => $this->l('Select customer language:'),
                    'required' => TRUE,
                    'name' => 'language_item_add',
                    'values'=> Language::getLanguages(true),

                )
            );


        }
        ### add field Where to display, only when add tab ###



        $this->fields_form['submit'] = array(
            'title' => ($id)?$this->l('Update'):$this->l('Save'),
        );


        /*$back = Tools::safeOutput(Tools::getValue('back', ''));
        if (empty($back)) {
            $back = self::$currentIndex.'&token='.$this->token;
        }
        if (!Validate::isCleanHtml($back)) {
            die(Tools::displayError());
        }*/



        if($id) {

            $this->tpl_form_vars = array(
                'fields_value' => $this->getConfigFieldsValuesForm(array('id'=>$id)),
                //'back_url' => $back
            );

            $this->submit_action = 'update_item';
        } else {
            $this->submit_action = 'add_item';

        }



        return parent::renderForm();
    }

    private function array_push_pos(&$array,$pos=0,$value,$key='')
    {
        if (!is_array($array)) {return false;}
        else
        {
            if (Tools::strlen($key) == 0) {$key = $pos;}
            $c = count($array);
            $one = array_slice($array,0,$pos);
            $two = array_slice($array,$pos,$c);
            $one[$key] = $value;
            $array = array_merge($one,$two);
            return;
        }
    }

    public function getConfigFieldsValuesForm($data_in){



        $id = (int)Tools::getValue('id');
        if($id) {
            $id = $data_in['id'];
            require_once(_PS_MODULE_DIR_ . '' . $this->table . '/classes/gsnipreviewhelp.class.php');
            $obj_reviewshelp = new gsnipreviewhelp();
            $_data = $obj_reviewshelp->getItem(array('id' => $id));
            $title_review = isset($_data['reviews'][0]['title_review']) ? $_data['reviews'][0]['title_review'] : '';
            $text_review = isset($_data['reviews'][0]['text_review']) ? $_data['reviews'][0]['text_review'] : '';
            $customer_name = isset($_data['reviews'][0]['customer_name']) ? $_data['reviews'][0]['customer_name'] : '';
            $email = isset($_data['reviews'][0]['email']) ? $_data['reviews'][0]['email'] : '';
            $is_active = isset($_data['reviews'][0]['is_active']) ? $_data['reviews'][0]['is_active'] : '';
            $admin_response = isset($_data['reviews'][0]['admin_response']) ? $_data['reviews'][0]['admin_response'] : '';
            $is_display_old = isset($_data['reviews'][0]['is_display_old']) ? $_data['reviews'][0]['is_display_old'] : 0;

            $config_array = array(
                'title_review' => $title_review,
                'text_review' => $text_review,
                'customer_name' => $customer_name,
                'email' => $email,
                'is_active' => $is_active,

                'admin_response'=>$admin_response,
                'is_display_old'=>$is_display_old,
            );
        } else {
            $config_array = array();
        }
        return $config_array;
    }

    public function l($string , $class = NULL, $addslashes = false, $htmlentities = true){
        if(version_compare(_PS_VERSION_, '1.7', '<')) {
            return parent::l($string);
        } else {
            //$class = array();
            //return Context::getContext()->getTranslator()->trans($string, $class, $addslashes, $htmlentities);
            return Translate::getModuleTranslation($this->_name_module, $string, $this->_name_module);
        }
    }
	
}





?>

