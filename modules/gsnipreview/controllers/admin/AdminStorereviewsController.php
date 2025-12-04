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

require_once(_PS_MODULE_DIR_ . 'gsnipreview/classes/ShopreviewsItems.php');



class AdminStorereviewsController extends ModuleAdminController{



    private $_name_controller = 'AdminStorereviews';

    private $_name_module = 'gsnipreview';

    private  $_id_lang;

    private  $_id_shop;

    private  $_iso_code;

    private $path_img_cloud;



    public function __construct()



    {



        $this->bootstrap = true;

        $this->context = Context::getContext();

        $this->table = 'gsnipreview_storereviews';





        $this->identifier = 'id';

        $this->className = 'ShopreviewsItems';



        $this->lang = false;



        //$this->_default_pagination = 1000;





        //$this->_orderBy = 'order_by';

        $this->_orderWay = 'DESC';







        $this->allow_export = false;

        $this->list_no_link = true;





        $id_lang =  $this->context->cookie->id_lang;

        $this->_id_lang = $id_lang;







        $id_shop =  $this->context->shop->id;



        $this->_id_shop = $id_shop;







        $iso_code = Language::getIsoById($id_lang);

        $this->_iso_code = $iso_code;



        $this->_select .= 'a.id, a.rating, a.email , a.web, a.avatar,

                              IF(LENGTH(a.message)>43,CONCAT(SUBSTRING(a.message,1,43),"..."),SUBSTRING(a.message,1,43)) as message,

                            a.id_lang, a.id_shop, a.date_add, a.active ';



        $this->_select .= ', (SELECT ga2c.avatar_thumb from '._DB_PREFIX_.''.$this->_name_module.'_avatar2customer ga2c

                                                    WHERE ga2c.id_customer = a.id_customer

                    ) as avatar_thumb';



        $this->_select .= ', (SELECT sh.`name`

	            FROM `'._DB_PREFIX_.'shop` sh

	            WHERE sh.`active` = 1 AND sh.deleted = 0 AND sh.`id_shop` = a.id_shop

	            ) as shop_name';



        $this->_select .= ', (SELECT group_concat(l.`iso_code` SEPARATOR \', \')

                    FROM `'._DB_PREFIX_.'lang` l

                    JOIN

                    `'._DB_PREFIX_.'lang_shop` ls

                    ON(l.id_lang = ls.id_lang)

                    WHERE l.`active` = 1 AND ls.id_shop = '.(int)$id_shop.' AND l.`id_lang`

                    IN( select pt_d.id_lang FROM `'._DB_PREFIX_.$this->table.'` pt_d WHERE pt_d.id = a.id)) as lang';





        $this->_where .= 'and a.is_deleted = 0';



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



        ### languages ###

        $data_languages = array();

        $all_languages = Language::getLanguages(true);

        foreach($all_languages as $_language){

            $data_languages[$_language['id_lang']]=$_language['name'];

        }

        ### languages ###





        ## for user URL ##

        $all_laguages = Language::getLanguages(true);

        $is_multilang = 0;

        if(sizeof($all_laguages)>1){

            $is_multilang = 1;

        }

        ## for user URL ##



        ### rating ###

        $data_rating = array();

        for($i=1;$i<=5;$i++){

            $data_rating[$i] = $i;

        }

        ### rating ###





        switch(Configuration::get($this->_name_module.'stylestars')){

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







        if (defined('_PS_HOST_MODE_'))

            $_is_cloud = 1;

        else

            $_is_cloud = 0;





        // for test

        //$_is_cloud = 1;

        // for test



        if($_is_cloud){

            $this->path_img_cloud = 'modules'.DIRECTORY_SEPARATOR.$this->_name_module.DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR;

        } else {

            $this->path_img_cloud = "upload".DIRECTORY_SEPARATOR.$this->_name_module.DIRECTORY_SEPARATOR;



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







            'name' => array(

                'title' => $this->l('Name'),

                'width' => 'auto',

                'search' => true,

                'align' => 'center',

                'is_multilang'=>$is_multilang,

                'base_dir_ssl' => _PS_BASE_URL_SSL_.__PS_BASE_URI__,

                'type_custom' => 'customer_name',

                'is_uprof'=>Configuration::get($this->_name_module.'is_uprof'),

                'user_url'=>$user_url,





            ),

            'email' => array(

                'title' => $this->l('Email'),

                'width' => 'auto',

                'search' => true,

                'align' => 'center',





            ),





            'message' => array(

                'title' => $this->l('Message'),

                'width' => 'auto',

                'search' => true,

                'align' => 'center',





            ),



            'shop_name' => array(

                'title' => $this->l('Shop'),

                'width' => 'auto',

                'type' => 'select',

                'orderby' => FALSE,

                'list' => $data_shops,

                'filter_key' => 'a!id_shop',



            ),



            'lang' => array(

                'title' => $this->l('Language'),

                'width' => 'auto',

                'type' => 'select',

                'orderby' => FALSE,

                'list' => $data_languages,

                'filter_key' => 'a!id_lang',



            ),



            'date_add' => array(

                'title' => $this->l('Date add'),

                'width' => 'auto',

                'search' => false,



            ),







            'active' => array(

                'title' => $this->l('Status'),

                'width' => 40,

                'align' => 'center',

                'type' => 'bool',

                'orderby' => FALSE,

                'type_custom' => 'is_active',

            ),



        );





        if(Configuration::get($this->_name_module.'is_avatar') == 1){



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





        if(Configuration::get($this->_name_module.'is_web') == 1){



            $this->array_push_pos($this->fields_list, 4,

                array(

                    'title' => $this->l('Web'),

                    'width' => 'auto',

                    'search' => true,

                    'align' => 'center',

                    'type_custom' => 'web'



                ),

                'web'

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

        /*if (empty($this->display)) {

            $this->page_header_toolbar_btn['add_item'] = array(

                'href' => self::$currentIndex.'&addtestimonials_item&token='.$this->token,

                'desc' => $this->l('Add new testimonial', null, null, false),

                'icon' => 'process-icon-new'

            );

        }*/



        parent::initPageHeaderToolbar();

    }



    public function initToolbar() {



        parent::initToolbar();



        /*$this->toolbar_btn['add_item'] = array(

                                            'href' => self::$currentIndex.'&add'.$this->_name_module.'&token='.$this->token,

                                            'desc' => $this->l('Add new post', null, null, false),

                                        );*/

        $this->toolbar_btn['new'] = array('href'=>'','desc'=>'');



    }







    public function postProcess()

    {





        require_once(_PS_MODULE_DIR_ . '' . $this->_name_module . '/classes/storereviews.class.php');

        $storereviewshelp_obj = new storereviews();









        if(Tools::isSubmit('update_item')) {

            $id = Tools::getValue('id');

            ## update item ##



            $name = Tools::getValue("name");

            $email = Tools::getValue("email");

            $web = Tools::getValue("web");

            $company = Tools::getValue("company");

            $address = Tools::getValue("address");

            $country = Tools::getValue("country");

            $city = Tools::getValue("city");

            $rating = Tools::getValue("rating");



            $message = Tools::getValue("message");

            $publish = (int)Tools::getValue("publish");



            $date_add = Tools::getValue("time_add");



            $response = Tools::getValue("response");

            $is_noti = Tools::getValue("is_noti");

            $is_show = Tools::getValue("is_show");



            $post_images = Tools::getValue("post_images");



            ## new field add ##

            $id_customer = Tools::getValue("id_customer");



            $data = array('name'=>$name,

                          'email'=>$email,

                          'web' =>$web,

                          'message'=>$message,

                          'publish'=>$publish,

                          'address'=>$address,

                          'company'=>$company,

                          'country'=>$country,

                          'city'=>$city,

                          'rating'=>$rating,

                          'date_add' => $date_add,

                          'id' =>$id,



                          'response'=>$response,

                          'is_noti'=>$is_noti,

                          'is_show'=>$is_show,



                          'post_images' => $post_images,



                          'id_customer' => $id_customer,

                    );







            if(!$rating)

                $this->errors[] = Tools::displayError('Please fill the Rating');

            if(Tools::strlen($name)==0)

                $this->errors[] = Tools::displayError('Please fill the Name');

            if(Tools::strlen($email)==0)

                $this->errors[] = Tools::displayError('Please fill the 	Email');

            if(!preg_match("/[0-9a-z-_]+@[0-9a-z-_^\.]+\.[a-z]{2,4}/i", $email))

                $this->errors[] = Tools::displayError('Please enter a valid email address. For example johndoe@domain.com');

            if(Tools::strlen($message)==0)

                $this->errors[] = Tools::displayError('Please select the Shop');

            if(!$date_add)

                $this->errors[] = Tools::displayError('Please select Date Add');







            if (empty($this->errors)) {



                $storereviewshelp_obj->updateItem($data);



                Tools::redirectAdmin(self::$currentIndex . '&conf=4&token=' . Tools::getAdminTokenLite($this->_name_controller));

            }else{



                $this->display = 'add';

                return FALSE;

            }



            ## update item ##

        } elseif (Tools::isSubmit('submitBulkdelete' . $this->_name_module)) {

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

        } elseif (Tools::isSubmit('delete' . $this->_name_module)) {

            ## delete item ##



            $id = Tools::getValue('id');



            $storereviewshelp_obj->deteleItem(array('id' => $id));



            Tools::redirectAdmin(self::$currentIndex . '&conf=1&token=' . Tools::getAdminTokenLite($this->_name_controller));

            ## delete item ##

        } else {

            return parent::postProcess(true);

        }









    }





    public function setMedia($isNewTheme = false)

    {

        parent::setMedia($isNewTheme);



        $this->context->controller->addJs(__PS_BASE_URI__.'modules/'.$this->_name_module.'/views/js/storereviews-admin.js');



        $this->addJqueryUi(array('ui.core','ui.widget','ui.datepicker'));



        $this->context->controller->addCSS(__PS_BASE_URI__.'modules/'.$this->_name_module.'/views/css/admin.css');



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



        require_once(_PS_MODULE_DIR_ . '' . $this->_name_module . '/classes/storereviews.class.php');

        $storereviewshelp_obj = new storereviews();





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



            $_data = $storereviewshelp_obj->getItem(array('id'=>$id));



            $id_shop = isset($_data['reviews'][0]['id_shop']) ? $_data['reviews'][0]['id_shop'] : 0;

            $shops = Shop::getShops();

            $name_shop = '';

            foreach($shops as $_shop){

                $id_shop_lists = $_shop['id_shop'];

                if($id_shop == $id_shop_lists)

                    $name_shop = $_shop['name'];

            }



            $time_add = isset($_data['reviews'][0]['date_add']) ? $_data['reviews'][0]['date_add'] :'' ;

            $name_lang =  isset($_data['reviews'][0]['name_lang']) ? $_data['reviews'][0]['name_lang'] :'' ;



            $avatar = isset($_data['reviews'][0]['avatar']) ? $_data['reviews'][0]['avatar'] :'' ;





            // is cloud ?? //

            if(defined('_PS_HOST_MODE_')){

                $logo_img_path = '../modules/'.$this->_name_module.'/upload/'.$avatar;

            } else {

                $logo_img_path = '../upload/'.$this->_name_module.'/'.$avatar;

            }

            // is cloud ?? //





            $customer_name = isset($_data['reviews'][0]['customer_name']) ? $_data['reviews'][0]['customer_name'] :'' ;



            $id_customer = isset($_data['reviews'][0]['id_customer']) ? $_data['reviews'][0]['id_customer'] :0 ;

            /*$admin_url_to_customer = 'index.php?'.(version_compare(_PS_VERSION_, '1.5', '>')?'controller':'tab').'=AdminCustomers&id_customer='.$id_customer.

                '&'.(version_compare(_PS_VERSION_, '1.5', '>')?'updatecustomer':'viewcustomer').

                '&token='.Tools::getAdminToken('AdminCustomers'.(int)(Tab::getIdFromClassName('AdminCustomers')).(int)($this->context->employee->id)).'';*/



            $admin_url_to_customer = isset($_data['reviews'][0]['user_url']) ? $_data['reviews'][0]['user_url'].$id_customer :0 ;

            $is_exist_ava = isset($_data['reviews'][0]['is_exist']) ? $_data['reviews'][0]['is_exist'] :0 ;





        } else {



            $id_shop = 0;

            $name_shop = '';

            $avatar = '';

            $logo_img_path = '';

            $time_add = date("Y-m-d H:i:s");

            $name_lang = '';

            $avatar = '';

            $admin_url_to_customer = '';

            $customer_name = '';







        }







        if($id){

            $title_item_form = $this->l('Edit testimonial:');

        }









        $this->fields_form = array(

            'tinymce' => TRUE,

            'legend' => array(

                'title' => $title_item_form,

                //'icon' => 'fa fa-list fa-lg'

            ),

            'input' => array(



                array(

                    'type' => 'id_item',

                    'label' => $this->l('ID:'),



                    'name' => 'id_item',

                    'values'=> $id,



                ),



                array(

                    'type' => 'language_item',

                    'label' => $this->l('Language:'),



                    'name' => 'language_item',

                    'values'=> $name_lang,





                ),



                array(

                    'type' => 'shop_item',

                    'label' => $this->l('Shop:'),



                    'name' => 'shop_item',

                    'values'=> $name_shop,





                ),





                array(

                    'type' => 'text',

                    'label' => $this->l('Rating:'),

                    'name' => 'rating',

                    'id' => 'rating',

                    'lang' => false,

                    'required' => TRUE,



                ),



                array(

                    'type' => 'avatar_custom',

                    'label' => $this->l('Avatar:'),

                    'name' => 'avatar-review',

                    'id' => 'avatar-review',

                    'lang' => false,

                    'required' => false,

                    'value'=>$avatar,

                    'path_img_cloud'=>$this->path_img_cloud,

                    'base_dir_ssl' => _PS_BASE_URL_SSL_.__PS_BASE_URI__,



                    'logo_img'=>$avatar,

                    'logo_img_path' => $logo_img_path,



                    'id_item'=>$id,



                    'desc' => $this->l('Allow formats *.jpg; *.jpeg; *.png; *.gif.'),

                    'is_demo' => $is_demo,

                    'max_upload_info' => ini_get('upload_max_filesize'),



                    'id_customer' => $id_customer,

                    'is_exist_ava' => $is_exist_ava,



                ),



                array(

                    'type' => 'text',

                    'label' => $this->l('Name:'),

                    'name' => 'name',

                    'id' => 'name',

                    'lang' => false,

                    'required' => TRUE,



                ),



                array(

                    'type' => 'text',

                    'label' => $this->l('Email:'),

                    'name' => 'email',

                    'id' => 'email',

                    'lang' => false,

                    'required' => TRUE,



                ),





                array(

                    'type' => 'textarea',

                    'label' => $this->l('Message:'),

                    'name' => 'message',

                    'id' => 'message',

                    'required' => TRUE,

                    'autoload_rte' => FALSE,

                    'lang' => FALSE,

                    'rows' => 8,

                    'cols' => 40,



                ),



                array(

                    'type' => 'textarea',

                    'label' => $this->l('Admin Response:'),

                    'name' => 'response',

                    'id' => 'response',

                    'required' => false,

                    'autoload_rte' => FALSE,

                    'lang' => FALSE,

                    'rows' => 8,

                    'cols' => 40,



                ),



                array(

                    'type' => 'checkbox_custom',

                    'label' => $this->l('Send "Admin Response" notification to the customer:'),

                    'name' => 'is_noti',

                    'values' => array(

                        'value' => 0

                    ),





                ),



                array(

                    'type' => 'switch',

                    'label' => $this->l('Display "Admin response" on the site'),

                    'name' => 'is_show',

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

                ),





                array(

                    'type' => 'item_date',

                    'label' => $this->l('Date Add'),

                    'name' => 'date_on',

                    'time_add' => $time_add,

                    'required' => TRUE,

                ),







                array(

                    'type' => 'switch',

                    'label' => $this->l('Status'),

                    'name' => 'publish',

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

                ),





            ),





        );











        if(Configuration::get($this->_name_module.'is_city') == 1){

            $this->array_push_pos($this->fields_form['input'],7,

                array(

                    'type' => 'text',

                    'label' => $this->l('City:'),

                    'name' => 'city',

                    'id' => 'city',

                    'lang' => false,

                    'required' => FALSE,

                )

            );

        }



        if(Configuration::get($this->_name_module.'is_country') == 1){

            $this->array_push_pos($this->fields_form['input'],7,

                array(

                    'type' => 'text',

                    'label' => $this->l('Country:'),

                    'name' => 'country',

                    'id' => 'country',

                    'lang' => false,

                    'required' => FALSE,

                )

            );

        }



        if(Configuration::get($this->_name_module.'is_addr') == 1){

            $this->array_push_pos($this->fields_form['input'],7,

                array(

                    'type' => 'text',

                    'label' => $this->l('Address:'),

                    'name' => 'address',

                    'id' => 'address',

                    'lang' => false,

                    'required' => FALSE,

                )

            );

        }



        if(Configuration::get($this->_name_module.'is_company') == 1){

            $this->array_push_pos($this->fields_form['input'],7,

                array(

                    'type' => 'text',

                    'label' => $this->l('Company:'),

                    'name' => 'company',

                    'id' => 'company',

                    'lang' => false,

                    'required' => FALSE,

                )

            );

        }



        if(Configuration::get($this->_name_module.'is_web') == 1){

            $this->array_push_pos($this->fields_form['input'],7,

                array(

                    'type' => 'text',

                    'label' => $this->l('Web:'),

                    'name' => 'web',

                    'id' => 'web',

                    'lang' => false,

                    'required' => FALSE,



                )

            );

        }





        if($id_customer && Configuration::get($this->_name_module.'is_uprof') == 1){

            $this->array_push_pos($this->fields_form['input'],7,

                array(

                    'type' => 'customer_url',

                    'label' => $this->l('Customer:'),



                    'name' => 'customer_url',

                    'values'=> $customer_name,

                    'url'=>$admin_url_to_customer,





                )

            );

        }





        $this->fields_form['submit'] = array(

            'title' => ($id)?$this->l('Update'):$this->l('Save'),

        );









        if($id) {



            $this->tpl_form_vars = array(

                'fields_value' => $this->getConfigFieldsValuesForm(array('id'=>$id)),

            );



            $this->submit_action = 'update_item';

        } else {

            $this->submit_action = 'add_item';





        }







        return parent::renderForm();

    }







    public function getConfigFieldsValuesForm($data_in){







        $id = (int)Tools::getValue('id');

        if($id) {

            $id = $data_in['id'];

            require_once(_PS_MODULE_DIR_ . '' . $this->_name_module . '/classes/storereviews.class.php');

            $storereviewshelp_obj = new storereviews();

            $_data = $storereviewshelp_obj->getItem(array('id'=>$id));





            $status = isset($_data['reviews'][0]['active'])?$_data['reviews'][0]['active']:"";

            $message = isset($_data['reviews'][0]['message'])?$_data['reviews'][0]['message']:"";



            $rating = isset($_data['reviews'][0]['rating']) ? $_data['reviews'][0]['rating'] :5 ;

            $name = isset($_data['reviews'][0]['name']) ? $_data['reviews'][0]['name'] :'' ;

            $email = isset($_data['reviews'][0]['email']) ? $_data['reviews'][0]['email'] :@Configuration::get('PS_SHOP_EMAIL') ;

            $web = isset($_data['reviews'][0]['web']) ? $_data['reviews'][0]['web'] :'' ;

            $company = isset($_data['reviews'][0]['company']) ? $_data['reviews'][0]['company'] :'' ;

            $address = isset($_data['reviews'][0]['address']) ? $_data['reviews'][0]['address'] :'' ;

            $country = isset($_data['reviews'][0]['country']) ? $_data['reviews'][0]['country'] :'' ;

            $city = isset($_data['reviews'][0]['city']) ? $_data['reviews'][0]['city'] :'' ;



            $response = isset($_data['reviews'][0]['response']) ? $_data['reviews'][0]['response'] :'' ;

            $is_show = isset($_data['reviews'][0]['is_show']) ? $_data['reviews'][0]['is_show'] :'' ;





            $config_array = array(

                'publish' => $status,

                'message'=>$message,

                'rating' => $rating,

                'name'=>$name,

                'email'=>$email,

                'web'=>$web,

                'company'=>$company,

                'address'=>$address,

                'country'=>$country,

                'city'=>$city,

                'response'=>$response,

                'is_show'=>$is_show,



            );

        } else {

            $config_array = array();

        }

        return $config_array;

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



    public function l($string ,$class = NULL,  $addslashes = false, $htmlentities = true){

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



