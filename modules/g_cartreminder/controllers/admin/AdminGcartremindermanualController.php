<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */

include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GcartremindercondreminderModel.php');
include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GcartreminderemailModel.php');
include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GabadoneModel.php');
class AdminGcartremindermanualController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->context = Context::getContext();
        $this->lang    = true;
        $this->tpl_form_vars['defaultFormLanguage'] = (int)Configuration::get('PS_LANG_DEFAULT');
        Context::getContext()->smarty->assign($this->tpl_form_vars);
        parent::__construct();
    }
    public function renderOptions()
    {
        $version = 'PS16';
        $link   = $this->context->link;
        $id_shop_group = (int)Shop::getContextShopGroupID();
        $id_shop       = $this->context->shop->id;
        $id_lang       = $this->context->language->id;
        $Currencies = Currency::getCurrencies();
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
            $version = 'PS17';
        }
        $this->html = '';
        $controller = Tools::getValue('controller');
        $dirimg = '../modules/g_cartreminder/views/img';
        $GCART_MANUALS = Configuration::get('GCART_MANUALS', null, $id_shop_group, (int)$id_shop);
        $GCART_JSREMINDERS = Configuration::get('GCART_JSREMINDERS', null, $id_shop_group, (int)$id_shop);
        $GCART_CONDITIONS  = Configuration::get('GCART_CONDITIONS', null, $id_shop_group, (int)$id_shop);
        $GCART_EXCLUDES    = Configuration::get('GCART_EXCLUDES', null, $id_shop_group, (int)$id_shop);
        $products = $this->searchProduct('',0,'tpl_code');
        $products = Tools::jsonDecode($products, true);
        $cats = $this->customGetNestedCategories($id_shop);
        $suppliers = Supplier::getSuppliers(false, $id_lang);
        $manufacturers = Manufacturer::getManufacturers(false, $id_lang);
        $languages = Language::getLanguages(false);
        $list_genders = array();
        $Genders      = Gender::getGenders((int)$id_lang);
        foreach ($Genders as $key => $gender) {
            /** @var Gender $gender */
            $list_genders[$key]['id_gender']    = $gender->id;
            $list_genders[$key]['name'] = $gender->name;
        }
        $Countrys      = Country::getCountries((int)$id_lang);
        if ($GCART_MANUALS) {
            $GCART_MANUALS = Tools::jsonDecode($GCART_MANUALS, true);
        }
        if ($GCART_JSREMINDERS) {
            $GCART_JSREMINDERS = Tools::jsonDecode($GCART_JSREMINDERS, true);
        }
        if ($GCART_CONDITIONS) {
            $GCART_CONDITIONS = Tools::jsonDecode($GCART_CONDITIONS, true);
        }
        $this->context->smarty->assign(
            array(
                'controller'   => $controller,
                'g_module_url' => $this->context->shop->getBaseURL(true).'modules/g_cartreminder/',
                'gettoken'     => sha1(_COOKIE_KEY_.'g_cartreminder'),
                'name'         => 'tabs',
                'link'         => new Link(),
                'usingSecureMode' =>Tools::usingSecureMode(),
                'dirimg' => $dirimg,
                'version' => $version,
                'Currencies' => $Currencies,
                'currenciedefault'=> $this->context->currency->id,
                'customer_group' =>$this->getcustomer(),
                'emailtemplates' => $this->getemailtempalte(),
                'getemployee'    => GcartreminderemailModel::getemployee(null,$id_shop),
                'trreminder'     => 0,
                'GCART_MANUALS'  => $GCART_MANUALS,
                'GCART_JSREMINDERS' => $GCART_JSREMINDERS,
                'GCART_CONDITIONS'  => $GCART_CONDITIONS,
                'GCART_EXCLUDES'  => $GCART_EXCLUDES,
                'products'      => $products,
                'cats'          => $cats,
                'suppliers'     => $suppliers,
                'manufacturers' => $manufacturers,
                'languages'     => $languages,
                'list_genders'  => $list_genders,
                'Countrys'      => $Countrys,

            )
        );
        $this->fields_options =  array(
            'manual' => array(
                'title' => $this->l('Manual'),
                'fields' => array(
                    array(
                        'type' => 'manual',
                        'name' => 'manual',
                    ),
                ),
            ),
        );
        if ($this->fields_options && is_array($this->fields_options)) {
            $helper = new HelperOptions($this);
            $this->setHelperDisplay($helper);
            $helper->toolbar_scroll = true;
            $helper->table = $this->table;
            $helper->title = $this->l('Manual');
            $helper->id    = $this->id;
            $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
            $helper->default_form_language = $lang->id;
            $helper->tpl_vars = array(
    			'languages'   => $this->context->controller->getLanguages(),
    			'id_language' => $this->context->language->id,
    		);
            if (Tools::getValue('savesuccssetfull') == 1) {
                $this->html .= $this->module->displayConfirmation($this->l('Settings updated.'));
            }
            $this->html .= $this->getHTMLtab($link, 'tabs', $controller);
            $this->html .= $this->getHTMLtab($link, 'start', $controller);
            $this->html .= $helper->generateOptions($this->fields_options);
            $this->html .= $this->getHTMLtab($link, 'end', $controller);
            return $this->html;
        }
    }
    public function getHTMLtab($link, $name, $controller){
        $dirimg = '../modules/g_cartreminder/views/img';
        $version = 'PS16';
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
            $version = 'PS17';
        }
        $this->context->smarty->assign(array(
            'controller'=> $controller,
            'name'      => $name,
            'link'      => $link,
            'dirimg'    => $dirimg,
            'version'   => $version,
        ));
        $html = $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/tabs/htmlall.tpl");
        return $html;
    }
    public function getcustomer()
    {
        $id_shop = Shop::getContextShopID();
        $id_lang = (int)$this->context->language->id;
        $customer_group = Group::getGroups($id_lang, $id_shop);
        return $customer_group;
    }
    public function postProcess()
    {
        $shop_id = (int)$this->context->shop->id;
        $id_lang = (int)$this->context->language->id;
        if (Tools::getValue('action')=="getGroupRiminder") {
            $number = Tools::getValue("number");
            $this->context->smarty->assign(
                array(
                    'number' => (int)$number,
                    'html_name' => 'groups',
                )
            );
            echo(Tools::jsonEncode(
                array(
                    'error' => 0,
                    'html_groups' => $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl"),
                )
            ));
            die;
        }  elseif (Tools::getValue('action')=="getGroupinGroupRiminder") {
            $number = Tools::getValue("number");
            $number_group = Tools::getValue("id_group");
            $this->context->smarty->assign(
                array(
                    'number' => (int)$number,
                    'number_group' => (int)$number_group,
                    'html_name' => 'group',
                )
            );
            echo(Tools::jsonEncode(
                array(
                    'error' => 0,
                    'html_groups' => $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl"),
                )
            ));
            die;

        } elseif (Tools::getValue('action')=="getGroupValueHtml") {
            $number       = Tools::getValue("number");
            $number_group = Tools::getValue("id_group");
            $type         = Tools::getValue("type");
            $html_select  = "";
            $html_val     = "";
            switch ($type) {
                case 'cart_products':
                    $products = $this->searchProduct('',0,'tpl_code');
                    $products = Tools::jsonDecode($products, true);
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                            'products'     => $products
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                case 'cart_totalincart':
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                case 'cart_stockproduct':
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                case 'cart_stockproducts':
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                case 'cart_productcat':
                    $cats = $this->customGetNestedCategories($shop_id);
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                            'cats'         => $cats
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                case 'cart_productsupplier':
                    $suppliers = Supplier::getSuppliers(false, $id_lang);
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                            'suppliers'    => $suppliers
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                case 'cart_productman':
                    $manufacturers = Manufacturer::getManufacturers(false, $id_lang);
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                            'manufacturers'=> $manufacturers
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                /*Customer */
                case 'customer_email':
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                case 'customer_language':
                    $languages = Language::getLanguages(false);
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                            'languages'=> $languages
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                case 'customer_aeg':
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                case 'customer_social':
                    $list_genders = array();
                    $Genders      = Gender::getGenders((int)$id_lang);
                    foreach ($Genders as $key => $gender) {
                        /** @var Gender $gender */
                        $list_genders[$key]['id_gender']    = $gender->id;
                        $list_genders[$key]['name'] = $gender->name;
                    }
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                            'genders'      => $list_genders
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                case 'customer_newlester':
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                case 'customer_register':
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                case 'customer_order':
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                case 'customer_country':
                    $Countrys      = Country::getCountries((int)$id_lang);
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type,
                            'number'       => (int)$number,
                            'number_group' => (int)$number_group,
                            'countrys'      => $Countrys
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => $type."_value",
                        )
                    );
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
                default:
                    $this->context->smarty->assign(
                        array(
                            'html_name'    => 'default',
                        )
                    );
                    $html_select =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    $html_val =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                    break;
            }
            echo(Tools::jsonEncode(
                array(
                    'error'        => 0,
                    'number'       => (int)$number,
                    'number_group' => (int)$number_group,
                    'html_select'  => $html_select,
                    'html_val'     => $html_val,
                )
            ));
            die;
        } elseif (Tools::getValue('action')=="searchProducts") {
            $query = Tools::getValue('query');
            $checkpro= Tools::getValue('checkpro');
            $products = $this->searchProduct($query , 0, '', $checkpro);
        } elseif (Tools::getValue('action')=="addDiscounthtml") {
            $trreminder = Tools::getValue("id");
            $id_group = Tools::getValue("id_group");
            $Currencies = Currency::getCurrencies();
            $this->context->smarty->assign(
                array(
                    'html_name' => "discounthtml",
                    'trreminder'=> $trreminder,
                    'id_group'  => (int)$id_group,
                    'Currencies'=> $Currencies,
                    'currenciedefault'=> $this->context->currency->id,
                )
            );
            $html =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
            echo(Tools::jsonEncode(
                array(
                    'html' => $html,
                )
            ));
            die;
        } elseif (Tools::getValue('action')=="submitOptionsgabandoned_manual") {
            $shops = Shop::getContextListShopID();
            $shop_context  = Shop::getContext();
            $redirectAdmin = $this->context->link->getAdminLink('AdminGcartremindermanual')."&conf=4";
            $shop_groups_list= array();
            $res = true;
            $GCART_MANUALS = Tools::getValue('manuals');
            $GCART_JSREMINDERS = Tools::getValue('jsreminder');
            $GCART_CONDITIONS = Tools::getValue('condition_group');
            $GCART_EXCLUDES = Tools::getValue('cartexcludes');
            if ($GCART_MANUALS) {
                $GCART_MANUALS = Tools::jsonEncode($GCART_MANUALS);
            }
            if ($GCART_JSREMINDERS) {
                $GCART_JSREMINDERS[0]['pricerule'] = GcartremindercondreminderModel::sortKey($GCART_JSREMINDERS[0]['pricerule'], 1);
                $GCART_JSREMINDERS = Tools::jsonEncode($GCART_JSREMINDERS);
            }
            if ($GCART_CONDITIONS) {
                $GCART_CONDITIONS =GcartremindercondreminderModel::sortKey($GCART_CONDITIONS, 2);
                $GCART_CONDITIONS = Tools::jsonEncode($GCART_CONDITIONS);
            }
            /*update config currency default*/
            foreach ($shops as $shop_id)
            {
                $shop_group_id = (int)Shop::getGroupFromShop((int)$shop_id, true);
                if (!in_array($shop_group_id, $shop_groups_list))
                    $shop_groups_list[] = (int)$shop_group_id;
                $res &= Configuration::updateValue('GCART_MANUALS', $GCART_MANUALS, false, (int)$shop_group_id, (int)$shop_id);
                $res &= Configuration::updateValue('GCART_JSREMINDERS', $GCART_JSREMINDERS, false, (int)$shop_group_id, (int)$shop_id);
                $res &= Configuration::updateValue('GCART_CONDITIONS', $GCART_CONDITIONS, false, (int)$shop_group_id, (int)$shop_id);
                $res &= Configuration::updateValue('GCART_EXCLUDES', $GCART_EXCLUDES, false, (int)$shop_group_id, (int)$shop_id);
            }
            /* Update global shop context if needed*/
            switch ($shop_context)
            {
                case Shop::CONTEXT_ALL:
                    $res &= Configuration::updateValue('GCART_MANUALS', $GCART_MANUALS);
                    $res &= Configuration::updateValue('GCART_JSREMINDERS', $GCART_JSREMINDERS);
                    $res &= Configuration::updateValue('GCART_CONDITIONS', $GCART_CONDITIONS);
                    $res &= Configuration::updateValue('GCART_EXCLUDES', $GCART_EXCLUDES);
                    if (count($shop_groups_list))
                    {
                        foreach ($shop_groups_list as $shop_group_id)
                        {
                            $res &= Configuration::updateValue('GCART_MANUALS', $GCART_MANUALS, false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GCART_JSREMINDERS', $GCART_JSREMINDERS, false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GCART_CONDITIONS', $GCART_CONDITIONS, false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GCART_EXCLUDES', $GCART_EXCLUDES, false, (int)$shop_group_id);
                        }
                    }
                    break;
                case Shop::CONTEXT_GROUP:
                    if (count($shop_groups_list))
                    {
                        foreach ($shop_groups_list as $shop_group_id)
                        {
                            $res &= Configuration::updateValue('GCART_MANUALS', $GCART_MANUALS, false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GCART_JSREMINDERS', $GCART_JSREMINDERS, false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GCART_CONDITIONS', $GCART_CONDITIONS, false, (int)$shop_group_id);
                            $res &= Configuration::updateValue('GCART_EXCLUDES', $GCART_EXCLUDES, false, (int)$shop_group_id);
                        }
                    }
                    break;
            }
            /*save*/
            Tools::redirectAdmin($redirectAdmin);
        } elseif (Tools::getValue('action')=="SearchShoppingcart") {
            $manuals_rules = Tools::getValue('manuals');
            $condition_group = Tools::getValue('condition_group');
            $condition_group = GcartremindercondreminderModel::sortKey($condition_group, 2);
            $excludes = explode(',',trim(Tools::getValue('cartexcludes'),','));
            $p = Tools::getValue('page');
            $n = Tools::getValue('numbershow');
            if (!$excludes) {
                $excludes = array();
            }
            $excludes = explode(',',trim(Tools::getValue('cartexcludes'),','));
            $totalall = $this->module->getShoppingCartByReminder($manuals_rules, true, true, $condition_group, $excludes, $p, $n);
            $cart_abadoneds = $this->module->getShoppingCartByReminder($manuals_rules, false, true, $condition_group, $excludes, $p, $n);
            $html = '';
            if ($cart_abadoneds) {
                foreach($cart_abadoneds as $cart_abadoned) {
                    $cart_abadoned['total'] = Cart::getTotalCart((int)$cart_abadoned['id_cart'], true, Cart::BOTH_WITHOUT_SHIPPING);
                    $obj_cart = new Cart((int)$cart_abadoned['id_cart']);
                    $exclude = true;
                    $total_produc_price = $obj_cart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
                    if (((float)$manuals_rules['mincartamount'][$obj_cart->id_currency] > 0 && (float)$total_produc_price < (float)$manuals_rules['mincartamount'][$obj_cart->id_currency]) || ((float)$manuals_rules['maxcartamount'][$obj_cart->id_currency] > 0 && (float)$total_produc_price > (float)$manuals_rules['maxcartamount'][$obj_cart->id_currency])) {
                        $exclude = false;
                    }
                    $this->context->smarty->assign(
                        array(
                            'html_name'     => "html_includeshoppingcart",
                            'cart_abadoned' => $cart_abadoned,
                            'exclude'       => $exclude
                        )
                    );
                    $html .=  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                }
            }
            echo(
                Tools::jsonEncode(
                    array(
                        'page'  => $p,
                        'numberpage'  => (int)Tools::ceilf((int)$totalall / (int)$n) ,
                        'numbershow' => $n,
                        'total' => $totalall,
                        'html'  => $html,
                    )
            ));
            die;
        } elseif (Tools::getValue('action')=="searchCartshoppingExclude") {
            $excludes = explode(',',trim(Tools::getValue('cartexcludes'),','));
            $manuals_rules = Tools::getValue('manuals');
            $p = Tools::getValue('page');
            $n = Tools::getValue('numbershow');
            if (!$excludes) {
                $excludes = array();
            }
            $totalall = $this->module->getShoppingCartByReminder(array(), true, false, array(), $excludes, $p, $n);
            $cart_abadoneds = $this->module->getShoppingCartByReminder(array(), false, false, array(), $excludes, $p, $n);
            $html = '';
            if ($cart_abadoneds) {
                foreach($cart_abadoneds as $cart_abadoned) {
                    $cart_abadoned['total'] = Cart::getTotalCart($cart_abadoned['id_cart'], true, Cart::BOTH_WITHOUT_SHIPPING);
                    $obj_cart = new Cart((int)$cart_abadoned['id_cart']);
                    $exclude = true;
                    $total_produc_price = $obj_cart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
                    if (((float)$manuals_rules['mincartamount'][$obj_cart->id_currency] > 0 && (float)$total_produc_price < (float)$manuals_rules['mincartamount'][$obj_cart->id_currency]) || ((float)$manuals_rules['maxcartamount'][$obj_cart->id_currency] > 0 && (float)$total_produc_price > (float)$manuals_rules['maxcartamount'][$obj_cart->id_currency])) {
                        $exclude = false;
                    }
                    $this->context->smarty->assign(
                        array(
                            'html_name'     => "html_excludeshoppingcart",
                            'cart_abadoned' => $cart_abadoned,
                            'exclude'       => $exclude
                        )
                    );
                    $html .=  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
                }
            }
            echo(
                Tools::jsonEncode(
                    array(
                        'page'  => $p,
                        'numbershow' => $n,
                        'numberpage'  => (int)Tools::ceilf((int)$totalall / (int)$n) ,
                        'total' => $totalall,
                        'html'  => $html,
                    )
            ));
            die;
        } elseif (Tools::getValue('action')=="sendEmailMenual") {
            $manuals_rules = Tools::getValue('manuals');
            $condition_group = Tools::getValue('condition_group');
            $jsreminder      = Tools::getValue('jsreminder');
            $condition_group = GcartremindercondreminderModel::sortKey($condition_group, 2);
            $excludes = explode(',',trim(Tools::getValue('cartexcludes'),','));
            if (!$excludes) {
                $excludes = array();
            }
            $cart_abadoneds = $this->module->getShoppingCartByReminder($manuals_rules, false, true, $condition_group, $excludes, 0, 0, false);
            $html = '';
            if ($cart_abadoneds) {
                foreach($cart_abadoneds as $cart_abadoned) {
                    $obj_cart = new Cart((int)$cart_abadoned['id_cart']);
                    $total_produc_price = $obj_cart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
                    if (((float)$manuals_rules['mincartamount'][$obj_cart->id_currency] > 0 && (float)$total_produc_price < (float)$manuals_rules['mincartamount'][$obj_cart->id_currency]) || ((float)$manuals_rules['maxcartamount'][$obj_cart->id_currency] > 0 && (float)$total_produc_price > (float)$manuals_rules['maxcartamount'][$obj_cart->id_currency])) {
                        continue;
                    }
                    $this->sendEmailMenual($cart_abadoned, $manuals_rules, $jsreminder, $id_lang, $shop_id);
                }
            }
            echo(
                Tools::jsonEncode(
                    array(
                        'warrning'  => $this->l('Send Email Manualy Successfull'),
                    )
            ));
            die;

        }
        parent::postProcess();
    }
    /* send email */
    public function sendEmailMenual($cart_abadoned, $manuals, $options, $id_lang, $id_shop) {
        $code = '';
        $id_lang         = (int)$this->context->language->id;
        $id_cart         = (int)$cart_abadoned['id_cart'];
        $from_email_ajax = $manuals["employee"];
        $subjectmail_ajax   = $manuals["subjectemail"][$id_lang];
        $custommessage_ajax = $manuals["custommessage"][$id_lang];
        $emailtemplate_ajax = $manuals["emailtemplate"];
        /*discount options*/
        $obj_cart        = new Cart($id_cart);
        $customers       = new Customer($obj_cart->id_customer);
        $total_produc_price = $obj_cart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
        
        $reduction_tax =  0;
        $minimum_amount = 0;
        $dc_value = 0;
        $counponvalidity = 0;
        $freeshipping = 0;
        $discounttype = 0;
        $id_currency =  (int)$obj_cart->id_currency;
        if (isset($options['pricerule']) && count($options['pricerule'])) {
            foreach ($options['pricerule'] as $pricerule) {
                if ((float)$pricerule['minprice'][$obj_cart->id_currency] < $total_produc_price && $total_produc_price < (float)$pricerule['maxprice'][$obj_cart->id_currency] ) {
                    $dc_value = (float)$pricerule['discountvalue'];
                    $id_currency    =  $pricerule['reduction_currency'];
                    $reduction_tax  =  $pricerule['reduction_tax'];
                    $minimum_amount = (float)$pricerule['minprice'][$obj_cart->id_currency];
                    $counponvalidity = (int)$options['counponvalidity'];
                    $freeshipping    = (int)$options['freeshipping'];
                    $discounttype    = $options['discounttype'];
                    break;
                }
            }
        }
        if (isset($manuals["sendto"]))
            $bcc = implode(',',$manuals["sendto"]);
        else
            $bcc = '';
        $employees = GcartreminderemailModel::getemployee($from_email_ajax, $id_shop);
        $checkdatas = GcartreminderemailModel::checkdataabadonedcartbyidcart($id_cart);
        if (empty($checkdatas)) {
            $code = GcartreminderemailModel::getCodediscount($obj_cart, $discounttype, $counponvalidity, $freeshipping, $reduction_tax, $minimum_amount, $dc_value, $id_currency);
        } else {
            foreach ($checkdatas as $checkdata) {
                $codetrim = trim($checkdata["code"]);
                $idcart_rule = CartRule::getIdByCode($codetrim);
                if (!empty($idcart_rule)) {
                    GcartreminderemailModel::deletecartrule($idcart_rule);
                    $code = GcartreminderemailModel::getCodediscount($obj_cart, $discounttype, $counponvalidity, $freeshipping, $reduction_tax, $minimum_amount, $dc_value, $id_currency);
                } else {
                    $code = GcartreminderemailModel::getCodediscount($obj_cart, $discounttype, $counponvalidity, $freeshipping, $reduction_tax, $minimum_amount, $dc_value, $id_currency);
                }
            }
        }
        if(Validate::isLoadedObject($customers)){
            $keys   = array('{customer_firstname}', '{customer_lastname}');
            $vals   = array($customers->firstname, $customers->firstname);
            $subjectmail_ajax = str_replace($keys, $vals, $subjectmail_ajax);
        }else return false;
        
        $send   = GcartreminderemailModel::sendmailabadonedcart($obj_cart, $subjectmail_ajax, $custommessage_ajax, $emailtemplate_ajax, $code, $employees, $bcc);
        $emails = GcartreminderemailModel::getsubjectemailtemplate($emailtemplate_ajax, $id_lang);
        GcartreminderemailModel::updatecartawait($id_cart, 0, $code, $emails, $discounttype, $dc_value);
        $data_status = Tools::jsonEncode(array(
            'from' => $from_email_ajax,
            'subject' => $subjectmail_ajax,
            'message' => $custommessage_ajax,
            'id_templateemail' => $emailtemplate_ajax,
            'bcc' => $bcc));
        $data_getcode = Tools::jsonEncode(array(
            'typediscount'  => $discounttype,
            'valuediscount' => $dc_value,
            'validitydiscount' => $counponvalidity,
            'freeship'         => $freeshipping));
        if (!$send) {
            return false;
        } else {
            if (!empty($checkdatas)) {
                foreach ($checkdatas as $checkdata) {
                    $arrayreminders = Tools::jsonDecode($checkdata["id_reminder"], true);
                    if (empty($arrayreminders)) {
                        $arrayreminders = array(0);
                    } elseif (!in_array(0, $arrayreminders)) {
                        array_push($arrayreminders, 0);
                    }
                }
                $arrayreminders = Tools::jsonEncode($arrayreminders);
                $sql = "UPDATE `" . _DB_PREFIX_ . "gabandoned_cart`
                    SET `id_reminder` = '" . pSQL($arrayreminders) . "', `status_senmail` = 1, `data_status` = '" . pSQL($data_status) . "',
                    `data_getcode` = '" . pSQL($data_getcode) . "', `code` = '" . pSQL($code) . "',
                    `count` = `count` + 1 WHERE `id_cart` = " . (int)$id_cart;
                return (bool)Db::getInstance()->execute($sql);
            } else {
                $id_gconditionandreminder = Tools::jsonEncode(array(0));
                /*add new gabandoned_cart*/
                $GabadoneModel = new GabadoneModel();
                $GabadoneModel->id_cart =  (int)$id_cart;
                $GabadoneModel->id_reminder =  $id_gconditionandreminder;
                $GabadoneModel->status_senmail =  1;
                $GabadoneModel->data_status    =  $data_status;
                $GabadoneModel->data_getcode   =  $data_getcode;
                $GabadoneModel->count =  1;
                $GabadoneModel->code  =  $code;
                $GabadoneModel->id_tempalte =  $emailtemplate_ajax;
                return $GabadoneModel->save();
            }
        }
    }
    /*getnest Category*/
    public function customGetNestedCategories( $shop_id, $query ='', $checkcat = '', $root_category = null, $id_lang = false, $active = false, $groups = null, $use_shop_restriction = true, $sql_filter = '', $sql_sort = '')
    {
        $sql_filter;
        $categories = array();
        if (isset($root_category) && !Validate::isInt($root_category)) {
            die(Tools::displayError());
        }
        if (!Validate::isBool($active)) {
            die(Tools::displayError());
        }
        if (isset($groups) && Group::isFeatureActive() && !is_array($groups)) {
            $groups = (array)$groups;
        }
        $image_dir = _PS_CAT_IMG_DIR_;
        $cache_id = 'Category::getNestedCategories_'.md5((int)$shop_id.(int)$root_category.(int)$id_lang.(int)$active.(int)$active
            .(isset($groups) && Group::isFeatureActive() ? implode('', $groups) : ''));
        if (!Cache::isStored($cache_id)) {
            $_groups = implode(',',array_map('intval', explode(',', $groups)));
            $sql = '
                SELECT c.*, cl.*
                FROM `'._DB_PREFIX_.'category` c
                INNER JOIN `'._DB_PREFIX_.'category_shop` category_shop ON (category_shop.`id_category` = c.`id_category` AND category_shop.`id_shop` = "'.(int)$shop_id.'")
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_shop` = "'.(int)$shop_id.'")
                WHERE 1 '.($id_lang ? 'AND cl.`id_lang` = '.(int)$id_lang : '');
                
            if (!$query or $query == '' or  Tools::strlen($query) < 1) {
                $sql .= '';
            } else {
                $sql .= ' AND (cl.`name` LIKE \'%'.pSQL($query).'%\' OR cl.`link_rewrite` LIKE \'%'.pSQL($query).'%\' OR  c.`id_category`='.(int)$query.')';
            }
            $sql .= ''.($active ? ' AND (c.`active` = 1 OR c.`is_root_category` = 1)' : '').'
                '.(isset($groups) && Group::isFeatureActive() ? ' AND cg.`id_group` IN ('.pSQL($_groups).')' : '').'
                '.(isset($checkcat) && $checkcat !="" ? ' AND c.`id_category` NOT IN ('.pSQL($checkcat).')' : '').'
                '.(!$id_lang || (isset($groups) && Group::isFeatureActive()) ? ' GROUP BY c.`id_category`' : '').'
                '.($sql_sort != '' ? pSQL($sql_sort) : ' ORDER BY c.`level_depth` ASC').'
                '.($sql_sort == '' && $use_shop_restriction ? ', category_shop.`position` ASC' : '');
            $result = Db::getInstance()->executeS($sql);
            
            foreach ($result as &$row) {
                $row['id_image'] = Tools::file_exists_cache($image_dir . $row['id_category'] . '.jpg') ? (int) $row['id_category'] : Language::getIsoById($id_lang) . '-default';
                $row['url_image'] = $this->context->link->getImageLink($row['link_rewrite'], $row['id_image'], ImageType::getFormattedName('home'));
                $row['legend'] = 'no picture';
            }
            $categories = $result;
        }
        return $categories;
    }
    /** Product Sreach Ajax **/
    public function searchProduct($query="", $search_number =0, $display="", $checkpro="")
    {
        $search_number;
        $link = $this->context->link;
        $sql = 'SELECT p.`id_product`, pl.`link_rewrite`, p.`reference`, pl.`name`, image_shop.`id_image` id_image,image.`id_image`, il.`legend`, p.`cache_default_attribute`
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_product = p.id_product AND pl.id_lang = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('pl').')
                LEFT JOIN `'._DB_PREFIX_.'image` image ON image.`id_product` = p.`id_product` AND image.`cover`=1
                LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop
                    ON (image_shop.cover=1 AND image_shop.id_shop='.(int)$this->context->shop->id.')
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$this->context->language->id.')
                WHERE';
        if (!$query or $query == '' or  Tools::strlen($query) < 1) {
            $sql .=' p.`active` = 1';
        } else {
            $sql .=' (pl.`name` LIKE \'%'.pSQL($query).'%\' OR p.`reference` LIKE \'%'.pSQL($query).'%\' OR  p.`id_product`='.(int)$query.') AND p.`active` = 1';
        }
        if ($checkpro !="") {
            $sql .=' AND p.`id_product` NOT IN ('.pSQL($checkpro).')';
        }
        $sql .=' GROUP BY p.id_product';
        $items = Db::getInstance()->executeS($sql);
        if ($items) {
            $tmp = array();
            foreach ($items as $item)
            {
                $product = new Product($item['id_product']);
                $img = str_replace('http://', Tools::getShopProtocol(), $this->context->link->getImageLink($item['link_rewrite'], (int)$item['id_image']));
                $url = $link->getProductLink($item['id_product'],null,null,null,null,(int)$this->context->shop->id);
                $price = Tools::disPlayprice($product->getPriceStatic((int)$product->id,true));
                if ($display =="tpl_code") {
                    $tmp[] =array(
                        'name'  => trim($item['name']),
                        'id'    => (int)($item['id_product']),
                        'image' => $img,
                        'price' => $price,
                        'url'   => $url,

                    );
                } else
                    $tmp[] = trim($item['name']).'|'.(int)($item['id_product']).'|'. $img .'|'.$price. '|' . $url ."\n";
            }
            if ($display =="tpl_code") {
                return Tools::jsonEncode($tmp);
            } else {
                echo Tools::jsonEncode($tmp);
                die;
            }
        } else {
            return '';
        }
    }
    public function getemailtempalte()
    {
        $id_lang = (int)$this->context->language->id;
        $sql = 'SELECT *
            FROM `' . _DB_PREFIX_ . 'gaddnewemail_template`
            INNER JOIN `' . _DB_PREFIX_ . 'gaddnewemail_template_lang`
            ON `' . _DB_PREFIX_ . 'gaddnewemail_template`.id_gaddnewemail_template = `' . _DB_PREFIX_ . 'gaddnewemail_template_lang`.id_gaddnewemail_template
            WHERE `id_lang`=' . (int)$id_lang . '
            ';
        return Db::getInstance()->executeS($sql);
    }
}