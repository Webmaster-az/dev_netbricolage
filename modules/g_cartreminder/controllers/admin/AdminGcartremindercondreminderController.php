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
class AdminGcartremindercondreminderController extends ModuleAdminController
{
    protected $customergroup_array = array();
    public function __construct()
    {
        $this->className = 'GcartremindercondreminderModel';
        $this->table = 'gconditionandreminder';
        parent::__construct();
        $this->meta_title = $this->l('Email Reminder');
        $this->deleted = false;
        $this->explicitSelect = true;
        $this->lang = true;
        $this->context = Context::getContext();
        $this->bootstrap = true;
        $this->_defaultOrderBy = 'position';
        $this->position_identifier = 'id_gconditionandreminder';
        $this->filter = true;
        $this->_select = 'a.custormmer custormmers,';
        if (Shop::isFeatureActive()) {
            Shop::addTableAssociation($this->table, array('type' => 'shop'));
        }
        $customers = $this->getcustomer();
        foreach ($customers as $customer) {
            $this->statuses_array[$customer['id_group']] = $customer['name'];
        }
        $this->bulk_actions = array('delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'));
        $this->addRowAction('edit');
        $this->addRowAction('delete');
                
        $this->fields_list = array(
            'id_gconditionandreminder' => array(
                'title' => $this->l('ID'),
                'type' => 'int',
                'width' => 'auto'),
            'position' => array(
                'title' => $this->l('Priority'),
                'width' => 'auto',
                'position' => 'position'),
            'rulename' => array('title' => $this->l('Name'), 'width' => 'auto'),
            'mincartamount' => array(
                'title' => $this->l('Min Cart Amount'),
                'width' => 'auto',
                'type' => 'price',
                'callback' => 'convertpricemincart',
                'search' => true,
                ),
            'maxcartamount' => array(
                'title' => $this->l('Max Cart Amount'),
                'width' => 'auto',
                'type' => 'price',
                'callback' => 'convertpricemincart',
                'search' => true,
                ),
            'dateto' => array(
                'title' => $this->l('Date To'),
                'type' => 'datetime',
                'width' => 'auto',
                'callback' => 'converdatetoinunimited',
                'search' => true,
                ),
            'custormmers' => array(
                'title' => $this->l('Customer group'),
                'width' => 'auto',
                'type' => 'select',
                'list' => $this->statuses_array,
                'filter_type' => 'text',
                'filter_key' => 'a!custormmer',
                'callback' => 'validatearraycustomer',
                'search' => true,
                ),
            'active' => array(
                'title' => $this->l('Status'),
                'width' => 'auto',
                'active' => 'status',
                'type' => 'bool',
                'orderby' => false),
            );
        
    }
    public function renderList()
    {
        $this->html = '';
        $link       = $this->context->link;
        $controller = Tools::getValue('controller');
        if (!($this->fields_list && is_array($this->fields_list))) {
            return false;
        }
        $this->getList($this->context->language->id);
        $helper = new HelperList();
        
        if (!is_array($this->_list)) {
            $this->displayWarning($this->l('Bad SQL query', 'Helper') . '<br />' . htmlspecialchars($this->_list_error));
            return false;
        }
        $this->setHelperDisplay($helper);
        $this->html .= $this->getHTMLtab($link, 'tabs', $controller);
        $this->html .= $this->getHTMLtab($link, 'start', $controller);
        $this->html .= $helper->generateList($this->_list, $this->fields_list);
        $this->html .= $this->getHTMLtab($link, 'end', $controller);
        return $this->html;
    }
    public function renderForm()
    {
        $link    = $this->context->link;
        $id_shop = Shop::getContextShopID();
        $id_gconditionandreminder = (int)Tools::getValue('id_gconditionandreminder');
        
        $id_lang = (int)$this->context->language->id;
        $cartreminderObj = new GcartremindercondreminderModel((int)$id_gconditionandreminder, null, (int)$id_shop);
        if (Validate::isLoadedObject($cartreminderObj)) {
            $this->fields_value['datefrom'] = $cartreminderObj->datefrom;
            $this->fields_value['dateto']   = $cartreminderObj->dateto;
            $this->fields_value['mincartamount']  = Tools::jsonDecode($cartreminderObj->mincartamount, true);
            $this->fields_value['maxcartamount']  = Tools::jsonDecode($cartreminderObj->maxcartamount, true);
            $this->fields_value['custormmer']     = $cartreminderObj->custormmer;
            $this->fields_value['reminder_group'] = Tools::jsonDecode($cartreminderObj->reminder_group, true);
            $this->fields_value['reminder']       = $this->srotarrayreminder($cartreminderObj->reminder);
            $this->fields_value['countreminder']  = $cartreminderObj->countreminder;
            $this->fields_value['rulename'] = $cartreminderObj->rulename;
            $this->fields_value['active']   = $cartreminderObj->active;
        } else {
            $this->fields_value['datefrom'] = '';
            $this->fields_value['dateto']   = '';
            $this->fields_value['mincartamount'] = array();
            $this->fields_value['maxcartamount'] = array();
            $this->fields_value['reminder_group']= array();
            $this->fields_value['custormmer']    = Tools::jsonEncode($this->getidcustomer());
            $this->fields_value['reminder']      = '';
            $this->fields_value['countreminder'] = 0;
            $this->fields_value['rulename'] = '';
            $this->fields_value['active']   = 0;
        }
        $this->fields_value['currency'] = $this->context->currency;
        $Currencies = Currency::getCurrencies();
        $this->fields_value['Currencies'] = $Currencies;
        $controller = Tools::getValue('controller');
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
        $this->context->smarty->assign(
            array(
            'array_listemail'=>$this->getemailtemplate((int)$id_lang),
            'customer_group' =>$this->getcustomer(),
            'link'           => $link,
            'products'       => $products,
            'cats'           => $cats,
            'suppliers'      => $suppliers,
            'manufacturers'  => $manufacturers,
            'languages'      => $languages,
            'list_genders'   => $list_genders,
            'Countrys'       => $Countrys
            )
        );
        
        $this->fields_form = array(
            'input' => array(
                array(
                    'type' => 'condrimindertype',
                    'name' => 'condrimindertype'
                    ),
                ),
            );
        if (Shop::isFeatureActive()) {
                $this->fields_form['input'][] = array(
                    'type' => 'shop',
                    'label' => $this->l('Shop association'),
                    'name' => 'checkBoxShopAsso',
                    );
            }
        $html    = $this->getHTMLtab($link, 'tabs', $controller);
        $html   .= $this->getHTMLtab($link, 'start', $controller);
        $endhtml = $this->getHTMLtab($link, 'end', $controller);
        return $html.parent::renderForm().$endhtml;
    }
    public function getemailtemplate($id_lang)
    {
        $sql = 'SELECT *
            FROM `' . _DB_PREFIX_ . 'gaddnewemail_template`
            INNER JOIN `' . _DB_PREFIX_ . 'gaddnewemail_template_lang`
            ON `' . _DB_PREFIX_ .
            'gaddnewemail_template`.id_gaddnewemail_template = `' . _DB_PREFIX_ .
            'gaddnewemail_template_lang`.id_gaddnewemail_template
            WHERE `id_lang`=' . (int)$id_lang . '
            ';
        $results = Db::getInstance()->executeS($sql);
        return $results;
    }
    public function postProcess()
    {
        $shop_id = (int)$this->context->shop->id;
        $id_lang = (int)$this->context->language->id;
        if (Tools::getValue('id_gconditionandreminder')) {
            $id_gconditionandreminder = (int)Tools::getValue('id_gconditionandreminder');
            $reminderObj = null;
            if ($id_gconditionandreminder > 0) {
                $idshop      = (int)$this->context->shop->id;
                $reminderObj = new GcartremindercondreminderModel((int)$id_gconditionandreminder, null, (int)$idshop);
                if (Validate::isLoadedObject($reminderObj)) {
                    $timenows = date('Y-m-d H:i:s');
                    $timenow  = strtotime($timenows);
                    $datefrom = strtotime($reminderObj->datefrom);
                    $dateto   = strtotime($reminderObj->dateto);
                    if (!empty($datefrom) || !empty($dateto)) {
                        if ((!empty($datefrom) && $timenow <= $datefrom && $datefrom > 0) || (!empty($dateto) && $timenow >= $dateto && $dateto > 0)) {
                            $statust = 0;
                            $reminderObj->updatestatus($id_gconditionandreminder, $statust);
                        }
                    }
                }
            } else {
                $reminderObj = new GcartremindercondreminderModel(null, null, (int)$this->context->shop->id);
                $reminderObj->updatestatus(null, 0);
            }
        } elseif (Tools::getValue('action')=="getGroupRiminder") {
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
        } elseif (Tools::getValue('action')=="getGroupinGroupRiminder") {
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
        } elseif (Tools::getValue('action')=="searchCat") {
            $query = Tools::getValue('query');
            $checkcat= Tools::getValue('checkcat');
            $cats = $this->customGetNestedCategories($shop_id, $query , $checkcat);
            echo(Tools::jsonEncode($cats));
            die;
        } elseif (Tools::getValue('action')=="getDiscountHtml") {
            $trreminder = Tools::getValue("trreminder");
            $lengthcol  = Tools::getValue("lengthcol");
            $Currencies = Currency::getCurrencies();
            $this->context->smarty->assign(
                array(
                    'html_name'    => "reminder",
                    'trreminder'   => (int)$trreminder + 1,
                    'lengthcol'    => (int)$lengthcol + 1,
                    'array_listemail' =>$this->getemailtemplate((int)$id_lang),
                    'Currencies'      => $Currencies,
                    'currenciedefault'=> $this->context->currency->id,
                )
            );
            $reminder =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
            $this->context->smarty->assign(
                array(
                    'html_name'    => "id_reminder",
                )
            );
            $id_reminder =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
            $this->context->smarty->assign(
                array(
                    'html_name'    => "prequency",
                )
            );
            $prequency =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
            $this->context->smarty->assign(
                array(
                    'html_name'    => "discount",
                )
            );
            $discount =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
            $this->context->smarty->assign(
                array(
                    'html_name'    => "remove_reminder",
                )
            );
            $remove_reminder =  $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartremindercondreminder/html_reminder_and_group.tpl");
            echo(Tools::jsonEncode(
                array(
                    'id_reminder'  => $id_reminder,
                    'reminder'     => $reminder,
                    'prequency'    => $prequency,
                    'discount'     => $discount,
                    'remove_reminder' => $remove_reminder,
                )
            ));
            die;
        } elseif (Tools::getValue('action')=="addDiscounthtml") {
            $trreminder = Tools::getValue("id");
            $id_group = Tools::getValue("id_group");
            $Currencies = Currency::getCurrencies();
            $this->context->smarty->assign(
                array(
                    'html_name' => "discounthtml",
                    'trreminder'=> $trreminder,
                    'id_group'  => (int)$id_group + 1,
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
        }
        return parent::postProcess();
    }
    public function srotarrayreminder($object)
    {
        $array_reminder = array();
        $reminders = Tools::jsonDecode($object, true);
        if (!empty($reminders)) {
            $reminders = array_values($reminders);
            foreach ($reminders as $keyreminder => $reminder) {
                $array_reminder[$keyreminder + 1] = $reminder;
            }
        }
        return $array_reminder;
    }
    public function ajaxProcessUpdatePositions()
    {
        $idshop = (int)$this->context->shop->id;
        $way = (int)Tools::getValue('way');
        $id_condition = (int)Tools::getValue('id');
        $positions = Tools::getValue('gconditionandreminder');
        $cartreminderObj = new GcartremindercondreminderModel($id_condition, null, (int)$idshop);
        if (is_array($positions)) {
            foreach ($positions as $position => $value) {
                $pos = explode('_', $value);
                if (isset($pos[2]) && (int)$pos[2] === $id_condition) {
                    if (isset($position) && $cartreminderObj->updatePosition($way, $position, $id_condition)) {
                        echo 'ok position ' . (int)$position . ' for id ' . (int)$pos[1] . '\r\n';
                    } else {
                        echo '{"hasError" : true, "errors" : "Can not update id ' . (int)$id_condition .
                            ' to position ' . (int)$position . ' "}';
                    }
                    break;
                }
            }
        }
    }
    public function validatearraycustomer($customergroup)
    {
        $getcustomers = $this->getcustomer();
        $textcustomer = '';
        $return       = '';
        $customers = Tools::jsonDecode($customergroup);
        if (!empty($customers)) {
            foreach ($getcustomers as $grcustomers) {
                if (in_array($grcustomers['id_group'], $customers)) {
                    $textcustomer .= $grcustomers['name'] . ',';
                }
            }
            $return = rtrim($textcustomer, ',');
        }
        return $return;
    }
    public function getcustomer()
    {
        $id_shop = Shop::getContextShopID();
        $id_lang = (int)$this->context->language->id;
        $customer_group = Group::getGroups($id_lang, $id_shop);
        return $customer_group;
    }
    public function getidcustomer()
    {
        $customers = $this->getcustomer();
        $ids = array();
        if (!empty($customers)) {
            foreach ($customers as $customer) {
                $ids[$customer['id_group']] = $customer['id_group'];
            }
        }
        return $ids;
    }
    public function convertpricemincart($price) {
        $id_currency = $this->context->currency->id;
        $prices      = Tools::jsonDecode($price, true);
        return Tools::displayPrice($prices[$id_currency]);
    }
    public function converdatetoinunimited($timeto)
    {
        if (empty($timeto) || strtotime($timeto) < 0 || $timeto == '0000-00-00 00:00:00') {
            return $this->l('Unimited');
        } else {
            return $timeto;
        }
    }
    public function getHTMLtab($link, $name, $controller){
        $CONFIGGETCARTDAYS = '';
        $CONFIGGETCARTHRS  = '';
        if ($name == 'date_get_cart') {
            $id_shop       = (int)$this->context->shop->id;
            $id_shop_group = (int)Shop::getContextShopGroupID();
            $CONFIGGETCARTDAYS = Configuration::get('CONFIGGETCARTDAYS', null, $id_shop_group, $id_shop);
            $CONFIGGETCARTHRS  = Configuration::get('CONFIGGETCARTHRS', null, $id_shop_group, $id_shop);
        }
        $version = 'PS16';
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
            $version = 'PS17';
        }
        $dirimg = '../modules/g_cartreminder/views/img';
        $this->context->smarty->assign(array(
            'controller'=> $controller,
            'name'      => $name,
            'link'      => $link,
            'CONFIGGETCARTDAYS' => $CONFIGGETCARTDAYS,
            'CONFIGGETCARTHRS'  => $CONFIGGETCARTHRS,
            'dirimg' => $dirimg,
            'version' => $version,
        ));
        $html = $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/tabs/htmlall.tpl");
        return $html;
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
}
