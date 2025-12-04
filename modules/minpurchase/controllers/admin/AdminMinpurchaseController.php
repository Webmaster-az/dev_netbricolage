<?php
/**
* Minimum and maximum unit quantity to purchase
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate
*  @copyright 2023 idnovate
*  @license   See above
*/

class AdminMinpurchaseController extends ModuleAdminController
{
    protected $_defaultOrderBy = 'date_add';
    protected $_defaultOrderWay = 'DESC';
    protected $can_add_conf = true;
    protected $top_elements_in_list = 4;
    protected $orderBy = 'id_product';
    protected $orderWay = 'ASC';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'minpurchase_configuration';
        $this->className = 'MinpurchaseConfiguration';
        $this->tabClassName = 'AdminMinpurchase';
        $this->lang = false;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->_orderWay = $this->_defaultOrderWay;
        $this->taxes_included = (Configuration::get('PS_TAX') == '0' ? false : true);

        parent::__construct();

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );

        $this->context = Context::getContext();

        $this->default_form_language = $this->context->language->id;

        $this->fields_list = array(
            'id_minpurchase_configuration' => array(
                'title' => $this->l('ID'),
                'align' => 'text-center',
                'class' => 'fixed-width-xs'
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'filter_key' => 'a!name'
            ),
            'minimum_quantity' => array(
                'title' => $this->l('Min qty'),
                'align' => 'text-center',
                'filter_key' => 'a!minimum_quantity'
            ),
            'maximum_quantity' => array(
                'title' => $this->l('Max qty'),
                'align' => 'text-center',
                'filter_key' => 'a!maximum_quantity'
            ),
            'max_qty_stock' => array(
                'title' => $this->l('Stock max'),
                'align' => 'text-center',
                'callback' => 'getDynamic',
                'filter_key' => 'a!max_qty_stock'
            ),            
            'multiple_qty' => array(
                'title' => $this->l('Mul. qty'),
                'align' => 'text-center',
                'filter_key' => 'a!multiple_qty'
            ),
            'increment_qty' => array(
                'title' => $this->l('Inc. qty'),
                'align' => 'text-center',
                'filter_key' => 'a!increment_qty'
            ),
            'minimum_amount' => array(
                'title' => $this->l('Min amount'),
                'align' => 'text-center',
                'callback' => 'getAmount',
                'filter_key' => 'a!minimum_amount'
            ),
            'maximum_amount' => array(
                'title' => $this->l('Max amount'),
                'align' => 'text-center',
                'callback' => 'getAmount',
                'filter_key' => 'a!maximum_amount'
            ),
            'date_to' => array(
                'title' => $this->l('Valid'),
                'align' => 'text-center',
                'callback' => 'printValidIcon',
            ),
            'active' => array(
                'title' => $this->l('Enabled'),
                'align' => 'text-center',
                'active' => 'status',
                'type' => 'bool',
                'callback' => 'printActiveIcon'
            ),
        );

        if (Shop::isFeatureActive() && (Shop::getContext() == Shop::CONTEXT_ALL || Shop::getContext() == Shop::CONTEXT_GROUP)) {
            $this->can_add_conf = false;
        }

        if (!Shop::isFeatureActive()) {
            $this->shopLinkType = '';
        } else {
            $this->shopLinkType = 'shop';
        }
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia();
        $this->addJqueryPlugin(array('typewatch', 'fancybox', 'autocomplete'));

        $this->addJqueryUI('ui.button');
        $this->addJqueryUI('ui.sortable');
        $this->addJqueryUI('ui.droppable');
        $_path = _MODULE_DIR_.$this->module->name;

        $this->context->controller->addJS($_path.'/views/js/back.js');
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->context->controller->addCSS($_path.'/views/css/back.css');
            if ($this->display) {
                $this->context->controller->addJS($_path.'/views/js/tabs.js');
            }
        } else {
            $this->context->controller->addCSS($_path.'/views/css/back_15.css');
        }
    }

    public function initContent()
    {
        if ($this->action == 'select_delete') {
            $this->context->smarty->assign(array(
                'delete_form' => true,
                'url_delete' => htmlentities($_SERVER['REQUEST_URI']),
                'boxes' => $this->boxes,
            ));
        }

        if (!$this->can_add_conf && !$this->display) {
            $this->informations[] = $this->l('You have to select a shop if you want to create a new configuration.');
        }

        $module = new Minpurchase();
        if ($this->action != 'new' && !Tools::isSubmit('updateminpurchase_configuration')) {
            if (Tools::isSubmit('submitMinpurchaseModuleGlobalConfig')) {
                $form_values = $this->getGlobalConfigFormValues();
                foreach (array_keys($form_values) as $key) {
                    if ((version_compare(_PS_VERSION_, '1.6', '>=') ? Tools::strpos($key, '[]') > 0 : strpos($key, '[]') > 0)) {
                        $key = Tools::str_replace_once('[]', '', $key);
                        Configuration::updateValue($key, implode(';', Tools::getValue($key)));
                    } else {
                        Configuration::updateValue($key, Tools::getValue($key));
                    }
                }
                $this->content .= $module->displayConfirmation($this->l('Configuration saved successfully.'));
            }
        }

        if (!$this->display) {
            $this->content .= $this->renderGlobalConfigForm();
        }

        parent::initContent();

        if (!$this->display) {
            if (version_compare(_PS_VERSION_, '1.6', '>=')) {
                $this->context->smarty->assign(array(
                    'this_path'                 => $this->module->getPathUri(),
                    'support_id'                => $module->addons_id_product,
                ));

                $available_lang_codes = array('en', 'es', 'fr', 'it', 'de');
                $default_lang_code = 'en';
                $template_iso_suffix = in_array(strtok($this->context->language->language_code, '-'), $available_lang_codes) ? strtok($this->context->language->language_code, '-') : $default_lang_code;
                $this->content .= $this->context->smarty->fetch($this->module->getLocalPath().'/views/templates/admin/company/information_'.$template_iso_suffix.'.tpl');
            }
            $this->context->smarty->assign(array(
                'content' => $this->content,
                'token' => $this->token,
            ));
        }
    }

    public function initToolbar()
    {
        parent::initToolbar();

        if (!$this->can_add_conf) {
            unset($this->toolbar_btn['new']);
        }
    }

    public function initModal()
    {
        parent::initModal();

        $languages = Language::getLanguages(false);
        $translateLinks = array();

        if (version_compare(_PS_VERSION_, '1.7.2.1', '>=')) {
            $module = Module::getInstanceByName($this->module->name);
            $isNewTranslateSystem = $module->isUsingNewTranslationSystem();
            $link = Context::getContext()->link;
            foreach ($languages as $lang) {
                if ($isNewTranslateSystem) {
                    $translateLinks[$lang['iso_code']] = $link->getAdminLink('AdminTranslationSf', true, array(
                        'lang' => $lang['iso_code'],
                        'type' => 'modules',
                        'selected' => $module->name,
                        'locale' => $lang['locale'],
                    ));
                } else {
                    $translateLinks[$lang['iso_code']] = $link->getAdminLink('AdminTranslations', true, array(), array(
                        'type' => 'modules',
                        'module' => $module->name,
                        'lang' => $lang['iso_code'],
                    ));
                }
            }
        }

        $this->context->smarty->assign(array(
            'trad_link' => 'index.php?tab=AdminTranslations&token='.Tools::getAdminTokenLite('AdminTranslations').'&type=modules&module='.$this->module->name.'&lang=',
            'module_languages' => $languages,
            'module_name' => $this->module->name,
            'translateLinks' => $translateLinks,
        ));

        $modal_content = $this->context->smarty->fetch('controllers/modules/modal_translation.tpl');

        $this->modals[] = array(
            'modal_id' => 'moduleTradLangSelect',
            'modal_class' => 'modal-sm',
            'modal_title' => $this->l('Translate this module'),
            'modal_content' => $modal_content
        );
    }

    public function initToolbarTitle()
    {
        parent::initToolbarTitle();

        switch ($this->display) {
            case '':
            case 'list':
                array_pop($this->toolbar_title);
                $this->toolbar_title[] = $this->l('Manage minimum/maximum purchase Configuration');
                break;
            case 'view':
                if (($conf = $this->loadObject(true)) && Validate::isLoadedObject($conf)) {
                    array_pop($this->toolbar_title);
                    $this->toolbar_title[] = sprintf($this->l('Configuration: %s'), $conf->name);
                }
                break;
            case 'add':
            case 'edit':
                array_pop($this->toolbar_title);
                if (($conf = $this->loadObject(true)) && Validate::isLoadedObject($conf)) {
                    $this->toolbar_title[] = sprintf($this->l('Editing Configuration: %s'), $conf->name);
                } else {
                    $this->toolbar_title[] = $this->l('Creating a new minimum/maximum purchase quantity configuration:');
                }
                break;
        }
    }

    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        if (empty($this->display)) {
            $this->page_header_toolbar_btn['desc-module-back'] = array(
                'href' => 'index.php?controller=AdminModules&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back'),
                'icon' => 'process-icon-back'
            );
            $this->page_header_toolbar_btn['desc-module-new'] = array(
                'href' => 'index.php?controller='.$this->tabClassName.'&add'.$this->table.'&token='.Tools::getAdminTokenLite($this->tabClassName),
                'desc' => $this->l('New'),
                'icon' => 'process-icon-new'
            );
            $this->page_header_toolbar_btn['desc-module-reload'] = array(
                'href' => 'index.php?controller='.$this->tabClassName.'&token='.Tools::getAdminTokenLite($this->tabClassName).'&reload=1',
                'desc' => $this->l('Reload'),
                'icon' => 'process-icon-refresh'
            );
            $this->page_header_toolbar_btn['desc-module-translate'] = array(
                'href' => '#',
                'desc' => $this->l('Translate'),
                'modal_target' => '#moduleTradLangSelect',
                'icon' => 'process-icon-flag'
            );
            $this->page_header_toolbar_btn['desc-module-hook'] = array(
                'href' => 'index.php?tab=AdminModulesPositions&token='.Tools::getAdminTokenLite('AdminModulesPositions').'&show_modules='.Module::getModuleIdByName('minpurchase'),
                'desc' => $this->l('Manage hooks'),
                'icon' => 'process-icon-anchor'
            );
        }

        if (!$this->can_add_conf) {
            unset($this->page_header_toolbar_btn['desc-module-new']);
        }
    }

    public function renderList()
    {
        if ((Tools::isSubmit('submitBulkdelete'.$this->table) || Tools::isSubmit('delete'.$this->table)) && $this->tabAccess['delete'] === '1') {
            $this->tpl_list_vars = array(
                'delete_minpurchaseconf' => true,
                'REQUEST_URI' => $_SERVER['REQUEST_URI'],
                'POST' => $_POST
            );
        }
        return parent::renderList();
    }

    public function renderForm()
    {
        if (!($conf = $this->loadObject(true))) {
            return;
        }

        $id_lang = 0;
        $id_shop = 0;
        $categories = array();

        $price_options = $this->getPriceOptions();
        $group_options = $this->getGroupOptions();

        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $id_lang = (int)$this->context->cookie->id_lang;
            $id_shop = (int)$this->context->cookie->id_shop;
            $currencies = Currency::getCurrencies(false, true);
        } else {
            $id_lang = (int)$this->context->language->id;
            $id_shop = (int)$this->context->shop->id;
            if (Shop::isFeatureActive()) {
                $currencies = Currency::getCurrenciesByIdShop($this->context->shop->id);
            } else {
                $currencies = Currency::getCurrencies(false, true);
            }
        }

        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $categories = array_merge($categories, Category::getCategories((int)($this->context->cookie->id_lang), false, false, '', 'ORDER BY cl.`name` ASC'));
        }

        $groups = Group::getGroups($id_lang, true);
        $customers = array();
        $products = array();
        if (Configuration::get('MINPURCHASE_USE_PRODUCTS') || Configuration::get('MINPURCHASE_USE_EXCLUSION')) {
            $products = $this->getProductsLite($id_lang, false, false);
        }

        if (Configuration::get('MINPURCHASE_USE_CUSTOMERS') || Configuration::get('MINPURCHASE_USE_EXCLUSION')) {
            $customers = Customer::getCustomers(true);
        }

        $order_totals = array(
                array(
                    'id' => Cart::ONLY_PRODUCTS,
                    'name' => $this->l('Order total only products')
                ),
                array(
                    'id' => Cart::ONLY_DISCOUNTS,
                    'name' => $this->l('Order total only discounts')
                ),
                array(
                    'id' => Cart::BOTH,
                    'name' => $this->l('Order total products with discounts')
                ),
                array(
                    'id' => Cart::BOTH_WITHOUT_SHIPPING,
                    'name' => $this->l('Order total products with discounts without shipping')
                ),
                array(
                    'id' => Cart::ONLY_SHIPPING,
                    'name' => $this->l('Order total only shipping')
                ),                
                array(
                    'id' => Cart::ONLY_WRAPPING,
                    'name' => $this->l('Order total only wrapping')
                ),
            );

        //$order_totals = array(0, 1, 2);
 
        $countries = Country::getCountries($id_lang);
        $zones = Zone::getZones();
        $manufacturers = Manufacturer::getManufacturers(false, $id_lang, false);
        $suppliers = Supplier::getSuppliers(false, $id_lang, false);
        $languages = Language::getLanguages(false, $id_shop);
        $features = Feature::getFeatures((int)$id_lang);
        $attributeGroups = AttributeGroup::getAttributesGroups((int)$id_lang);

        $array_features = array();

        $periods = $this->getPeriods();
        $states = OrderState::getOrderStates((int)$this->context->language->id);

        $this->multiple_fieldsets = true;
        $this->default_form_language = $this->context->language->id;

        $currencyDefault = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));

        $this->fields_form[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Quantities and limits configuration'),
                'icon' => 'icon-wrench'
            ),
            'input' => array(
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'class' => 't',
                    'col' => '8',
                    'is_bool' => true,
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
                    'desc' => $this->l('Enable or Disable this configuration'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'required' => true,
                    'col' => '8',
                    'desc' => $this->l('Invalid characters:').' !&lt;&gt;,;?=+()@#"°{}_$%:',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Minimum amount'),
                    'name' => 'minimum_amount',
                    'default' => '1',
                    'col' => '8',
                    'desc' => $this->l('Set the minimum amount to apply the control in the total cart'),
                    'prefix' => $currencyDefault->sign,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Maximum amount'),
                    'name' => 'maximum_amount',
                    'default' => '1',
                    'col' => '8',
                    'desc' => $this->l('Set the maximum amount to apply the control in the total cart'),
                    'prefix' => $currencyDefault->sign,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select Order Total type'),
                    'name' => 'order_total_type',
                    'class' => 't',
                    'multiple' => false,
                    'required' => false,
                    'search' => true,
                    'col' => '8',
                    'options' => array(
                        'query' => $order_totals,
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => '',
                            'label' => $this->l('-- Choose order total type --')
                        )
                    ),
                    'desc' => $this->l('Select the Order Total type to get the compare the minimum and maximum amount'),
                ),         
                array(
                    'type' => 'text',
                    'label' => $this->l('Minimum quantity'),
                    'name' => 'minimum_quantity',
                    'default' => '1',
                    'col' => '8',
                    'desc' => $this->l('Set the minimum quantity to purchase this product'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Maximum quantity'),
                    'name' => 'maximum_quantity',
                    'default' => '1',
                    'col' => '8',
                    'desc' => $this->l('Set the maximum quantity to purchase this product'),
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Maximum quantity with current stock'),
                    'name' => 'max_qty_stock',
                    'class' => 't',
                    'col' => '8',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'max_qty_stock_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'max_qty_stock_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'desc' => $this->l('Enable if you want to set the maximum quantity with the current stock (dynamically)'),
                ),                
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Multiple quantities'),
                    'name' => 'multiple',
                    'class' => 't filter_prices_class',
                    'col' => '8',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'multiple_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'multiple_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'desc' => $this->l('Enable multiple quantities'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Multiple quantity'),
                    'name' => 'multiple_qty',
                    'class' => 'toggle_multiple',
                    'default' => '1',
                    'col' => '8',
                    'desc' => $this->l('Set the minimum quantity to purchase this product'),
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Quantity increments'),
                    'name' => 'increment',
                    'class' => 't filter_prices_class',
                    'col' => '8',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'increment_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'increment_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'desc' => $this->l('Enable quantity increments'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Increment qty'),
                    'name' => 'increment_qty',
                    'class' => 'toggle_increment',
                    'default' => '1',
                    'col' => '8',
                    'desc' => $this->l('Set the Increment quantity'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Maximum in last X days'),
                    'name' => 'days',
                    'default' => '1',
                    'col' => '8',
                    'desc' => $this->l('Set days to check the products purchased in last X days to define the maximum to purchase'),
                ),
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Orders from this date'),
                    'name' => 'orders_date_from',
                    'col' => '8',
                    'desc' => $this->l('Date from which the orders are valid'),
                ),
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Orders until this date'),
                    'name' => 'orders_date_to',
                    'col' => '8',
                    'desc' => $this->l('Date to until the orders are valid'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Other options'),
                    'name' => 'orders_period',
                    'required' => false,
                    'col' => '8',
                    'options' => array(
                        'query' => $periods,
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => false,
                            'label' => $this->l('-- Choose --')
                        )
                    ),
                    'desc' => $this->l('Select period'),
                ),
                array(
                    'type' => 'swap-custom',
                    'label' => $this->l('Valid order states'),
                    'name' => 'order_states[]',
                    'class' => 'switch_order_states',
                    'multiple' => true,
                    'required' => false,
                    'search' => true,
                    'col' => '8',
                    'options' => array(
                        'query' => $states,
                        'id' => 'id_order_state',
                        'name' => 'name',
                    ),
                    'desc' => $this->l('Valid order states')
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Grouped by'),
                    'name' => 'grouped_by',
                    'class' => 't',
                    'col' => '8',
                    'options' => array(
                        'query' => $group_options,
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => '',
                            'label' => $this->l('-- Choose --')
                        )
                    ),
                    'desc' => $this->l('Grouped products criteria grouped by condition. Example: 10 units minimum from all units in the same category (5 from product A and 5 from product B'),
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Count combinations separated'),
                    'name' => 'separated',
                    'class' => 't filter_prices_class',
                    'col' => '8',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'separated_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'separated_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'desc' => $this->l('Enable it if you want to count the combinations of the product separated to calculate the minimum and maximum value. If disabled, the minimum and maximum is established to every product and combination'),
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Display text'),
                    'name' => 'show_text',
                    'col' => '8',
                    'class' => 't',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'show_text_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'show_text_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'desc' => $this->l('Enable it if you want to display the Minimum and Maximum text in the product list and display the Maximum in the product page (only will be displayed if the Minimum is greater than 1 and the Maximum is greater than 0'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Priority'),
                    'name' => 'priority',
                    'default' => '1',
                    'col' => '8',
                    'desc' => $this->l('Set priority when 2 or more configurations overlaps. Configuration with less number here will have more priority'),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'type' => 'submit',
            ),
        );


        $this->fields_form[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Product filters'),
                'icon' => 'icon-edit'
            ),
            'input' => array(
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Filter by prices'),
                    'name' => 'filter_prices',
                    'class' => 't filter_prices_class',
                    'col' => '8',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'filter_prices_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'filter_prices_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'desc' => $this->l('Enable or Disable the filter by prices range. For example: Apply the minimum and maximum rule to products with Retail price with taxes between 10€ (minimum) and 50€ (maximum)'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Price from calculate the minimum and maximum'),
                    'name' => 'price_calculate',
                    'col' => '8',
                    'class' => 't toggle_filter_prices',
                    'options' => array(
                        'query' => $price_options,
                        'id' => 'id',
                        'name' => 'name',
                        'default' => array(
                            'value' => '',
                            'label' => $this->l('-- Choose --')
                        )
                    ),
                    'desc' => $this->l('Select the price from calculate the minimum and maximum.'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Products with price from'),
                    'name' => 'min_price',
                    'class' => 't toggle_filter_prices',
                    'col' => '8',
                    'default' => '0',
                    'desc' => $this->l('Enable rule to products with price from'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Products with price until'),
                    'name' => 'max_price',
                    'class' => 'toggle_filter_prices',
                    'col' => '8',
                    'default' => '0',
                    'desc' => $this->l('Enable rule with product prices until'),
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Filter by stock'),
                    'name' => 'filter_stock',
                    'col' => '8',
                    'is_bool' => true,
                    'class' => 't',
                    'values' => array(
                        array(
                            'id' => 'filter_stock_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'filter_stock_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'desc' => $this->l('Enable or Disable the filter by stock range. For example: Apply the minimum and maximum rule to products with stock quantity between 50 and 100'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Products with stock from'),
                    'name' => 'min_stock',
                    'class' => 'toggle_filter_stock',
                    'col' => '8',
                    'default' => '0',
                    'desc' => $this->l('Enable rule to products with stock from'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Products with stock until'),
                    'name' => 'max_stock',
                    'class' => 'toggle_filter_stock',
                    'col' => '8',
                    'default' => '0',
                    'desc' => $this->l('Enable rule to products with stock until'),
                ),
                array(
                    'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                    'label' => $this->l('Filter by weight'),
                    'name' => 'filter_weight',
                    'class' => 't',
                    'col' => '8',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'filter_weight_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'filter_weight_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    ),
                    'desc' => $this->l('Enable or Disable the filter by weight range. For example: Appy the rule to the products with weight between 0 and 6'),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Products with weight from'),
                    'name' => 'min_weight',
                    'class' => 'toggle_filter_weight',
                    'col' => '2',
                    'default' => '0',
                    'desc' => $this->l('Enable rule to products with weight from'),
                    'suffix' => Configuration::get('PS_WEIGHT_UNIT')
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Products with weight until'),
                    'name' => 'max_weight',
                    'class' => 'toggle_filter_weight',
                    'col' => '2',
                    'default' => '0',
                    'desc' => $this->l('Enable rule to products with weight until'),
                    'suffix' => Configuration::get('PS_WEIGHT_UNIT')
                ),                
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'type' => 'submit',
            ),
        );

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $selected_categories = array();
            if ($conf->categories != '') {
                if (@unserialize($conf->categories) !== false) {
                    $selected_categories = unserialize($conf->categories);
                } else {
                    $selected_categories = explode(';', $conf->categories);
                }
            }

            $categories_form_array = array(
               'type'  => 'categories',
                'label' => $this->l('Select Category(s)'),
                'multiple' => true,
                'name'  => 'categories',
                'col' => '8',
                'class' => 'filter_store_class',
                'tree'  => array(
                    'id' => 'id_category',
                    'use_checkbox' => true,
                    'selected_categories' => $selected_categories,
                    ),
                'desc' => $this->l('Select the Category(es) where the rule will be applied. If you don\'t select any value, the rule will be applied to all Categories'),
            );
        } else {
            $categories_form_array = array(
                'type' => 'swap-custom',
                'label' => $this->l('Select Category(s)'),
                'class' => 'switch_categories',
                'name'  => 'categories[]',
                'search' => true,
                'multiple' => true,
                'required' => false,
                'col' => '2',
                'options' => array(
                    'query' => $categories,
                    'id' => 'id_category',
                    'name' => 'name'
                ),
                'desc' => $this->l('Select the Category(es) where the rule will be applied. If you don\'t select any value, the rule will be applied to all Categories'),
            );
        }

        array_push($this->fields_form[1]['form']['input'], $categories_form_array);

        $render_form_end = array();

        if (Configuration::get('MINPURCHASE_USE_PRODUCTS')) {
            $render_form_end[] =
                array(
                    'type' => 'swap-custom',
                    'label' => $this->l('Select Product(s)'),
                    'name' => 'products[]',
                    'class' => 'switch_products',
                    'multiple' => true,
                    'required' => false,
                    'search' => true,
                    'col' => '8',
                    'options' => array(
                        'query' => $products,
                        'id' => 'id_product',
                        'name' => 'name'
                    ),
                    'desc' => $this->l('Select the Product(s) where the rule will be applied. If you don\'t select any value, the rule will be applied to all Products'),
                );
        }

        $render_form_end[] =
            array(
                'type' => 'swap-custom',
                'label' => $this->l('Select Manufacturer(s)'),
                'name' => 'manufacturers[]',
                'class' => 'switch_manufacturers',
                'multiple' => true,
                'required' => false,
                'search' => true,
                'col' => '8',
                'options' => array(
                    'query' => $manufacturers,
                    'id' => 'id_manufacturer',
                    'name' => 'name'
                ),
                'desc' => $this->l('Select the Manufacturer(s) where the rule will be applied. If you don\'t select any value, the rule will be applied to all Manufacturers'),
            );
        $render_form_end[] =
            array(
                'type' => 'swap-custom',
                'label' => $this->l('Select Supplier(s)'),
                'name' => 'suppliers[]',
                'class' => 'switch_suppliers',
                'multiple' => true,
                'required' => false,
                'search' => true,
                'col' => '8',
                'options' => array(
                    'query' => $suppliers,
                    'id' => 'id_supplier',
                    'name' => 'name'
                ),
                'desc' => $this->l('Select the Supplier(s) where the rule will be applied. If you don\'t select any value, the rule will be applied to all Suppliers'),
            );

        if (Configuration::get('MINPURCHASE_USE_FEATURES')) {
            $features = Feature::getFeatures((int)$id_lang);
            $array_features = array();

            foreach ($features as $key => $feature) {
                if ($feature['name']) {
                    $feature_values = FeatureValue::getFeatureValuesWithLang((int)$id_lang, $feature['id_feature']);
                    if (!empty($feature_values)) {
                        $array_features[] = array(
                            'type' => 'swap-custom',
                            'label' => $this->l('Select').' '.$feature['name'],
                            'name' => 'feature_'.$feature['id_feature'].'[]',
                            'multiple' => true,
                            'required' => false,
                            'class' => 'switch_features',
                            'search' => true,
                            'col' => '8',
                            'options' => array(
                                'query' => $feature_values,
                                'id' => 'id_feature_value',
                                'name' => 'value'
                            ),
                            'desc' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? $this->l('Select the ').' '.$feature['name'].' '.$this->l('to apply this configuration') : '',
                        );
                    }
                }
            }

            foreach ($array_features as $f) {
                array_push($render_form_end, $f);
            }
        }

        if (Configuration::get('MINPURCHASE_USE_ATTRIBUTES')) {
            $attributeGroups = AttributeGroup::getAttributesGroups((int)$id_lang);

            $array_attributes = array();
            foreach ($attributeGroups as $key => $attributeGroup) {
                if ($attributeGroup['name']) {
                    if (!empty($attributeGroups)) {
                        $array_attributes[] = array(
                            'type' => 'swap-custom',
                            'label' => $this->l('Select').' '.$attributeGroup['name'],
                            'name' => 'attribute_'.$attributeGroup['id_attribute_group'].'[]',
                            'multiple' => true,
                            'required' => false,
                            'class' => 'switch_attributes',
                            'search' => true,
                            'col' => '8',
                            'options' => array(
                                'query' => AttributeGroup::getAttributes((int)$id_lang, $attributeGroup['id_attribute_group']),
                                'id' => 'id_attribute',
                                'name' => 'name'
                            ),
                            'desc' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? $this->l('Select the ').' '.$attributeGroup['name'].' '.$this->l('to apply this configuration') : '',
                        );
                    }
                }
            }

            foreach ($array_attributes as $a) {
                array_push($render_form_end, $a);
            }
        }

        // add the final part of the form
        foreach ($render_form_end as $f) {
            array_push($this->fields_form[1]['form']['input'], $f);
        }

        $this->fields_form[1]['form']['submit'] = array(
                'title' => $this->l('Save'),
                'type' => 'submit',
        );

        $this->fields_form[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Target filters'),
                'icon' => 'icon-globe'
            ),
            'input' => array(
                array(
                    'type' => 'swap-custom',
                    'label' => $this->l('Select Customer group(s)'),
                    'name' => 'groups[]',
                    'class' => 'switch_groups',
                    'multiple' => true,
                    'required' => false,
                    'search' => true,
                    'col' => '8',
                    'options' => array(
                        'query' => $groups,
                        'id' => 'id_group',
                        'name' => 'name'
                    ),
                    'desc' => $this->l('Select the Customer Group(s) where the rule will be applied. If you don\'t select any value, the rule will be applied to all Groups'),
                ),
                array(
                    'type' => 'swap-custom',
                    'label' => $this->l('Select Currency(es)'),
                    'name' => 'currencies[]',
                    'class' => 'switch_currencies',
                    'multiple' => true,
                    'required' => false,
                    'search' => true,
                    'col' => '8',
                    'options' => array(
                        'query' => $currencies,
                        'id' => 'id_currency',
                        'name' => 'name'
                    ),
                    'desc' => $this->l('Currency(es) to apply this configuration. If you don\'t select any value, the rule will be applied to all Currencies'),
                ),
                array(
                    'type' => 'swap-custom',
                    'label' => $this->l('Select Language(s)'),
                    'name' => 'languages[]',
                    'class' => 'switch_languages',
                    'multiple' => true,
                    'required' => false,
                    'search' => true,
                    'col' => '8',
                    'options' => array(
                        'query' => $languages,
                        'id' => 'id_lang',
                        'name' => 'name'
                    ),
                    'desc' => $this->l('Select the Language(s) where the rule will be applied. If you don\'t select any value, the rule will be applied to all Languages'),
                ),
                array(
                    'type' => 'swap-custom',
                    'label' => $this->l('Select Country(s)'),
                    'name' => 'countries[]',
                    'class' => 'switch_countries',
                    'multiple' => true,
                    'required' => false,
                    'search' => true,
                    'col' => '8',
                    'options' => array(
                        'query' => $countries,
                        'id' => 'id_country',
                        'name' => 'name'
                    ),
                    'desc' => $this->l('Select the Country(s) where the rule will be applied. If you don\'t select any value, the rule will be applied to all Countries'),
                ),
                array(
                    'type' => 'swap-custom',
                    'label' => $this->l('Select Zone(s)'),
                    'name' => 'zones[]',
                    'class' => 'switch_zones',
                    'multiple' => true,
                    'required' => false,
                    'search' => true,
                    'col' => '8',
                    'options' => array(
                        'query' => $zones,
                        'id' => 'id_zone',
                        'name' => 'name'
                    ),
                    'desc' => $this->l('Select the Zone(s) where the rule will be applied. If you don\'t select any value, the rule will be applied to all Zones'),
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'type' => 'submit',
            ),
        );

        if (Configuration::get('MINPURCHASE_USE_CUSTOMERS')) {
            $form_customers[] =
                array(
                    'type' => 'swap-custom',
                    'label' => $this->l('Select Customer(s)'),
                    'name' => 'customers[]',
                    'class' => 'switch_customers',
                    'multiple' => true,
                    'required' => false,
                    'search' => true,
                    'col' => '8',
                    'options' => array(
                        'query' => $customers,
                        'id' => 'id_customer',
                        'name' => 'email'
                    ),
                    'desc' => $this->l('Select the Customer(s) where the rule will be applied. If you don\'t select any value, the rule will be applied to all Customers'),
                );

            foreach ($form_customers as $f) {
                array_unshift($this->fields_form[2]['form']['input'], $f);
            }
        }

        if (Configuration::get('MINPURCHASE_USE_EXCLUSION')) {
            $this->fields_form[]['form'] = array(
                'legend' => array(
                    'title' => $this->l('Excluded products and customers'),
                    'icon' => 'icon-tasks'
                ),
                'input' => array(
                    array(
                        'type' => 'swap-custom',
                        'label' => $this->l('Select Excluded Product(s)'),
                        'name' => 'products_excluded[]',
                        'class' => 'switch_products_excluded',
                        'multiple' => true,
                        'required' => false,
                        'search' => true,
                        'col' => '8',
                        'options' => array(
                            'query' => $products,
                            'id' => 'id_product',
                            'name' => 'name'
                        ),
                        'desc' => $this->l('Select the Product(s) where the rule will not be applied. Only the products selected will be excluded'),
                    ),
                    array(
                        'type' => 'swap-custom',
                        'label' => $this->l('Select Excluded Customer(s)'),
                        'name' => 'customers_excluded[]',
                        'class' => 'switch_customers_excluded',
                        'multiple' => true,
                        'required' => false,
                        'col' => '8',
                        'options' => array(
                            'query' => $customers,
                            'id' => 'id_customer',
                            'name' => 'email'
                        ),
                        'desc' => $this->l('Select the Customer(s) where the rule will not be applied. Only the customers selected will be excluded.'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'type' => 'submit',
                ),
            );
        }

        $this->fields_form[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Dates and schedule'),
                'icon' => 'icon-calendar'
            ),
            'input' => array(
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Date From'),
                    'name' => 'date_from',
                    'col' => '4',
                    'desc' => $this->l('Date from which the rule is valid. You can use hours, minutes and seconds. Example: 2016-10-27 is considered 2016-10-27 00:00:00 and it means that the rule is valid from 2016-10-27 00:00:00'),
                ),
                array(
                    'type' => 'datetime',
                    'label' => $this->l('Date To'),
                    'name' => 'date_to',
                    'col' => '4',
                    'desc' => $this->l('Date to which the rule is valid. You can use hours, minutes and seconds. Example: 2016-10-27 is considered 2016-10-27 00:00:00 and it means that the rule is valid until 2016-10-26 23:59:59'),
                ),
                array(
                    'type' => 'free',
                    'label' => $this->l('Schedule'),
                    'name' => 'schedule',
                    'hint' => $this->l('Select days of week and hours to apply the rule (Click on the box to enable or disable the day and define the time range)')
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'type' => 'submit',
            ),
        );

        //Load db values for select inputs
        if ($conf->id) {
            $this->fields_value = array(
                'customers[]' => explode(';', $conf->customers),
                'customers_excluded[]' => explode(';', $conf->customers_excluded),
                'products[]' => explode(';', $conf->products),
                'products_excluded[]' => explode(';', $conf->products_excluded),
                'groups[]' => explode(';', $conf->groups),
                'zones[]' => explode(';', $conf->zones),
                'countries[]' => explode(';', $conf->countries),
                'manufacturers[]' => explode(';', $conf->manufacturers),
                'suppliers[]' => explode(';', $conf->suppliers),
                'currencies[]' => explode(';', $conf->currencies),
                'languages[]' => explode(';', $conf->languages),
                'order_states[]' => explode(';', $conf->order_states),
            );

            $features_decoded_array = json_decode($conf->features);
            $attributes_decoded_array = json_decode($conf->attributes);

            $i = 0;
            if (!empty($features_decoded_array)) {
                foreach($features_decoded_array as $key => $feature) {
                    $feature_values['feature_'.$key.'[]'] = explode(';',$feature);
                    $i++;
                }

                foreach ($feature_values as $key => $f) {
                    $this->fields_value[$key] = $f;
                }
            }

            $i = 0;
            $attribute_values = array();
            if (!empty($attributes_decoded_array)) {
                foreach($attributes_decoded_array as $key => $attribute) {
                    $attribute_values['attribute_'.$key.'[]'] = explode(';',$attribute);
                    $i++;
                }

                foreach ($attribute_values as $key => $f) {
                    $this->fields_value[$key] = $f;
                }
            }

            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                $this->fields_value['categories[]'] = explode(';', $conf->categories);
            }

            $this->context->smarty->assign(array(
                'schedule' => $conf->schedule,
            ));
        } else {
            //Initialize empty values
            $this->fields_value['customers[]'] = array();
            $this->fields_value['customers_excluded[]'] = array();
            $this->fields_value['groups[]'] = array();
            $this->fields_value['currencies[]'] = array();
            $this->fields_value['products[]'] = array();
            $this->fields_value['products_excluded[]'] = array();
            $this->fields_value['suppliers[]'] = array();
            $this->fields_value['manufacturers[]'] = array();
            $this->fields_value['categories[]'] = array();
            $this->fields_value['attributes[]'] = array();
            $this->fields_value['features[]'] = array();

            $this->context->smarty->assign(array(
                'schedule' => '',
            ));
        }

        $this->fields_value['schedule'] =  $this->context->smarty->fetch($this->module->getLocalPath().'views/templates/admin/schedule.tpl');
        $this->content .= parent::renderForm();
        return;
    }

    protected function renderGlobalConfigForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = new Minpurchase();
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->currentIndex = self::$currentIndex;
        $helper->submit_action = 'submitMinpurchaseModuleGlobalConfig';
        $helper->token = Tools::getAdminTokenLite($this->tabClassName);
        $helper->tpl_vars = array(
            'fields_value' => $this->getGlobalConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getGlobalConfigForm()));
    }

    protected function getGlobalConfigForm()
    {
        $form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Global settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'col' => 5,
                        'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                        'name' => 'MINPURCHASE_USE_PRODUCTS',
                        'label' => $this->l('Use products filter'),
                        'class' => 't',
                        'default_value' => true,
                        'desc' => (version_compare(_PS_VERSION_, '1.6', '<')) ? $this->l('Enable if you want to use the features. Disable if you will not need to create rules by products') : '',
                        'hint' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? $this->l('Enable if you want to use the features. Disable if you will not need to create rules by products') : '',
                        'values' => array(
                            array(
                                'id' => 'MINPURCHASE_USE_PRODUCTS_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'MINPURCHASE_USE_PRODUCTS_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                        'col' => 5,
                        'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                        'name' => 'MINPURCHASE_USE_CUSTOMERS',
                        'label' => $this->l('Use customers filter'),
                        'class' => 't',
                        'default_value' => true,
                        'desc' => (version_compare(_PS_VERSION_, '1.6', '<')) ? $this->l('Enable if you want to use the attributes. Disable if you will not need to create rules by customers') : '',
                        'hint' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? $this->l('Enable if you want to use the attributes. Disable if you will not need to create rules by customers') : '',
                        'values' => array(
                            array(
                                'id' => 'MINPURCHASE_USE_CUSTOMERS_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'MINPURCHASE_USE_CUSTOMERS_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                        'col' => 5,
                        'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                        'name' => 'MINPURCHASE_USE_FEATURES',
                        'label' => $this->l('Use features filter'),
                        'class' => 't',
                        'default_value' => true,
                        'desc' => (version_compare(_PS_VERSION_, '1.6', '<')) ? $this->l('Enable if you want to use the features. Disable if you will not need to create rules by features') : '',
                        'hint' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? $this->l('Enable if you want to use the features. Disable if you will not need to create rules by features') : '',
                        'values' => array(
                            array(
                                'id' => 'MINPURCHASE_USE_FEATURES_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'MINPURCHASE_USE_FEATURES_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                        'col' => 5,
                        'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                        'name' => 'MINPURCHASE_USE_ATTRIBUTES',
                        'label' => $this->l('Use attributes filter'),
                        'class' => 't',
                        'default_value' => true,
                        'desc' => (version_compare(_PS_VERSION_, '1.6', '<')) ? $this->l('Enable if you want to use the attributes. Disable if you will not need to create rules by attributes') : '',
                        'hint' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? $this->l('Enable if you want to use the attributes. Disable if you will not need to create rules by attributes') : '',
                        'values' => array(
                            array(
                                'id' => 'MINPURCHASE_USE_ATTRIBUTES_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'MINPURCHASE_USE_ATTRIBUTES_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                    array(
                        'col' => 5,
                        'type' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'switch' : 'radio',
                        'name' => 'MINPURCHASE_USE_EXCLUSION',
                        'label' => $this->l('Enable exclusion filters (products and customers)'),
                        'class' => 't',
                        'default_value' => true,
                        'desc' => (version_compare(_PS_VERSION_, '1.6', '<')) ? $this->l('Enable if you need to create configuration rules using the exclusions feature. Enabled you will see a new section with products and customers filters to select specific products and/or customers. Useful if you want to create massive rules but exclude specific products and/or customers.') : '',
                        'hint' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? $this->l('Enable if you need to create configuration rules using the exclusions feature. Enabled you will see a new section with products and customers filters to select specific products and/or customers. Useful if you want to create massive rules but exclude specific products and/or customers.') : '',
                        'values' => array(
                            array(
                                'id' => 'MINPURCHASE_USE_EXCLUSION_on',
                                'value' => true,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id' => 'MINPURCHASE_USE_EXCLUSION_off',
                                'value' => false,
                                'label' => $this->l('No')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'type' => 'submit',
                    'class' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? 'btn btn-default pull-right' : 'button big',
                    'name' => 'submitMinpurchaseModuleGlobalConfig',
                ),
            ),
        );

        return $form;
    }

    protected function getGlobalConfigFormValues()
    {
        return array(
            'MINPURCHASE_USE_FEATURES' => Configuration::get('MINPURCHASE_USE_FEATURES'),
            'MINPURCHASE_USE_ATTRIBUTES' => Configuration::get('MINPURCHASE_USE_ATTRIBUTES'),
            'MINPURCHASE_USE_PRODUCTS' => Configuration::get('MINPURCHASE_USE_PRODUCTS'),
            'MINPURCHASE_USE_CUSTOMERS' => Configuration::get('MINPURCHASE_USE_CUSTOMERS'),
            'MINPURCHASE_USE_EXCLUSION' => Configuration::get('MINPURCHASE_USE_EXCLUSION'),
        );
    }

    public function processSave()
    {
        if (Tools::getValue('submitFormAjax')) {
            $this->redirect_after = false;
        }

        if (!$this->_formValidations()) {

            $this->display = 'edit';
            return parent::processSave();
        }

        $_POST['groups'] = (!Tools::getValue('groups')) ? '' : implode(';', Tools::getValue('groups'));
        $_POST['countries'] = (!Tools::getValue('countries')) ? '' : implode(';', Tools::getValue('countries'));
        $_POST['zones'] = (!Tools::getValue('zones')) ? '' : implode(';', Tools::getValue('zones'));
        $_POST['manufacturers'] = (!Tools::getValue('manufacturers')) ? '' : implode(';', Tools::getValue('manufacturers'));
        $_POST['suppliers'] = (!Tools::getValue('suppliers')) ? '' : implode(';', Tools::getValue('suppliers'));
        $_POST['currencies'] = (!Tools::getValue('currencies')) ? '' : implode(';', Tools::getValue('currencies'));
        $_POST['languages'] = (!Tools::getValue('languages')) ? '' : implode(';', Tools::getValue('languages'));
        $_POST['attributes'] = (!Tools::getValue('attributes')) ? '' : implode(';', Tools::getValue('attributes'));
        $_POST['features'] = (!Tools::getValue('features')) ? '' : implode(';', Tools::getValue('features'));
        $_POST['customers'] = (!Tools::getValue('customers')) ? '' : implode(';', Tools::getValue('customers'));
        $_POST['customers_excluded'] = (!Tools::getValue('customers_excluded')) ? '' : implode(';', Tools::getValue('customers_excluded'));
        $_POST['products'] = (!Tools::getValue('products')) ? '' : implode(';', Tools::getValue('products'));
        $_POST['products_excluded'] = (!Tools::getValue('products_excluded')) ? '' : implode(';', Tools::getValue('products_excluded'));
        $_POST['order_states'] = (!Tools::getValue('order_states')) ? '' : implode(';', Tools::getValue('order_states'));

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            if (Tools::isSubmit('categories')) {
                $cats = Tools::getValue('categories');
                $_POST['categories'] = serialize($cats);
            } else {
                $_POST['categories'] = '';
            }
        } else {
            $_POST['categories'] = (!Tools::isSubmit('categories')) ? '' : implode(';', Tools::getValue('categories'));
        }

        if (empty($this->errors)) {
            if (Tools::getValue('multiple') == 1) {
                $_POST['increment'] = 0;
                $_POST['increment_qty'] = 0;
            } else if (Tools::getValue('increment') == 1) {
                $_POST['multiple'] = 0;
                $_POST['multiple_qty'] = 0;
            } else {
                $_POST['increment'] = 0;
                $_POST['increment_qty'] = 0;
                $_POST['multiple'] = 0;
                $_POST['multiple_qty'] = 0;
            }

            if (Tools::getValue('max_qty_stock')) {
                $_POST['maximum_quantity'] = 0;
            }
        }

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->cleanCache();
        }
        return parent::processSave();
    }

    protected function afterAdd($object)
    {
        $id = Tools::getValue('id_minpurchase_configuration');
        $this->afterUpdate($object, $id);
        return true;
    }

    protected function afterUpdate($object, $id = false)
    {
        if ($id) {
            $conf = new MinpurchaseConfiguration((int)$id);
        } else {
            $conf = new MinpurchaseConfiguration((int)$object->id);
        }

        if (Validate::isLoadedObject($conf)) {
            $features = Feature::getFeatures((int)$this->context->cookie->id_lang);
            $attributeGroups = AttributeGroup::getAttributesGroups((int)$this->context->cookie->id_lang);

            $array_features_result = array();
            $array_attributes_result = array();

            foreach ($features as $f) {
                if (Tools::getValue('feature_'.$f['id_feature'])) {
                    $array_features_result[$f['id_feature']] = implode(';', Tools::getValue('feature_'.$f['id_feature']));
                }
            }

            foreach ($attributeGroups as $a) {
                if (Tools::getValue('attribute_'.$a['id_attribute_group'])) {
                    $array_attributes_result[$a['id_attribute_group']] = implode(';', Tools::getValue('attribute_'.$a['id_attribute_group']));
                }
            }

            $conf->features = json_encode($array_features_result);
            $conf->attributes = json_encode($array_attributes_result);
            $conf->save();
        }
        return true;
    }

    /**
     * @param string $token
     * @param int $id
     * @param string $name
     * @return mixed
     */
    public function displayDeleteLink($token = null, $id, $name = null)
    {
        $tpl = $this->createTemplate('helpers/list/list_action_delete.tpl');

        $tpl->assign(array(
            'href' => self::$currentIndex.'&'.$this->identifier.'='.$id.'&delete'.$this->table.'&token='.($token != null ? $token : $this->token),
            'confirm' => $this->l('Delete the selected item?').$name,
            'action' => $this->l('Delete'),
            'id' => $id,
        ));

        return $tpl->fetch();
    }

    public function getPriceOptions()
    {
        $price_options = array($this->l('Wholesale Price without Taxes'), $this->l('Retail Price Without Taxes'), $this->l('Wholesale Price with Taxes'), $this->l('Retail Price with Taxes'));

        $list_price_options = array();
        foreach ($price_options as $key => $mode) {
            $list_price_options[$key]['id'] = $key;
            $list_price_options[$key]['value'] = $key;
            $list_price_options[$key]['name'] = $mode;
        }
        return $list_price_options;
    }

    public function getCustomerGroups($ids_customer_groups)
    {
        if ($ids_customer_groups === '' || $ids_customer_groups === 'all') {
            return $this->l('All');
        }
        $groups = array();
        $groups_array = explode(';', $ids_customer_groups);
        foreach ($groups_array as $key => $group) {
            if ($key == $this->top_elements_in_list) {
                $groups[] = $this->l('...and more');
                break;
            }
            $group = new Group($group, $this->context->language->id);
            $groups[] = $group->name;
        }
        return implode('<br />', $groups);
    }

    public function getCountries($ids_countries)
    {
        if ($ids_countries === '' || $ids_countries === 'all') {
            return $this->l('All');
        }
        $countries = array();
        $countries_array = explode(';', $ids_countries);
        foreach ($countries_array as $key => $country) {
            if ($key == $this->top_elements_in_list) {
                $countries[] = $this->l('...and more');
                break;
            }
            $country = new Country($country, $this->context->language->id);
            $countries[] = $country->name;
        }
        return implode('<br />', $countries);
    }

    public function getZones($ids_zones)
    {
        if ($ids_zones === '' || $ids_zones === 'all') {
            return $this->l('All');
        }
        $zones = array();
        $zones_array = explode(';', $ids_zones);
        foreach ($zones_array as $key => $zone) {
            if ($key == $this->top_elements_in_list) {
                $zones[] = $this->l('...and more');
                break;
            }
            $zone = new Zone($zone, $this->context->language->id);
            $zones[] = $zone->name;
        }
        return implode('<br />', $zones);
    }

    public function getSuppliers($ids_suppliers)
    {
        if ($ids_suppliers === '' || $ids_suppliers === 'all') {
            return $this->l('All');
        }

        $suppliers = array();
        $suppliers_array = explode(';', $ids_suppliers);
        foreach ($suppliers_array as $key => $supplier) {
            if ($key == $this->top_elements_in_list) {
                $suppliers[] = $this->l('...and more');
                break;
            }
            $supplier = new Supplier($supplier);
            $suppliers[] = $supplier->name;
        }
        return implode('<br />', $suppliers);
    }


    public function getCategories($ids_categories)
    {
        if ($ids_categories === '' || $ids_categories === 'all') {
            return $this->l('All');
        }

        $categories = array();

        if (@unserialize($ids_categories) !== false) {
            $categories_array = unserialize($ids_categories);
        } else {
            $categories_array = explode(';', $ids_categories);
        }

        foreach ($categories_array as $key => $category) {
            if ($key == $this->top_elements_in_list) {
                $categories[] = $this->l('...and more');
                break;
            }
            $category = new Category($category, $this->context->language->id);
            $categories[] = $category->name;
        }
        return implode('<br />', $categories);
    }

    public function getCurrencies($ids_currencies)
    {
        if ($ids_currencies === '' || $ids_currencies === 'all') {
            return $this->l('All');
        }
        $currencies = array();
        $currencies_array = explode(';', $ids_currencies);
        foreach ($currencies_array as $key => $currency) {
            if ($key == $this->top_elements_in_list) {
                $currencies[] = $this->l('...and more');
                break;
            }
            $currency = new Currency($currency);
            $currencies[] = $currency->name;
        }
        return implode('<br />', $currencies);
    }

    public function getProducts($ids_products)
    {
        if ($ids_products === '' || $ids_products === 'all') {
            return $this->l('All');
        }
        $products = array();
        $products_array = explode(';', $ids_products);
        foreach ($products_array as $key => $product) {
            if ($key == $this->top_elements_in_list) {
                $products[] = $this->l('...and more');
                break;
            }
            $product = new Product($product, $this->context->language->id);
            $products[] = '['.$product->id.'] - '.$product->name[$this->context->language->id];
        }
        return implode('<br />', $products);
    }

    public function getCustomers($ids_customers)
    {
        if ($ids_customers === '' || $ids_customers === 'all') {
            return $this->l('All');
        }
        $customers = array();
        $customers_array = explode(';', $ids_customers);
        foreach ($customers_array as $key => $customer) {
            if ($key == $this->top_elements_in_list) {
                $customers[] = $this->l('...and more');
                break;
            }
            $customer = new Customer($customer, $this->context->language->id);
            $customers[] = $customer->firstname.' '.$customer->lastname;
        }
        return implode('<br />', $customers);
    }

    private function _createTemplate($tpl_name)
    {
        if ($this->override_folder) {
            if ($this->context->controller instanceof ModuleAdminController) {
                $override_tpl_path = $this->context->controller->getTemplatePath().$tpl_name;
            } elseif ($this->module) {
                $override_tpl_path = _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/'.$tpl_name;
            } else {
                if (file_exists($this->context->smarty->getTemplateDir(1).DIRECTORY_SEPARATOR.$this->override_folder.$this->base_folder.$tpl_name)) {
                    $override_tpl_path = $this->context->smarty->getTemplateDir(1).DIRECTORY_SEPARATOR.$this->override_folder.$this->base_folder.$tpl_name;
                } elseif (file_exists($this->context->smarty->getTemplateDir(0).DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$this->override_folder.$this->base_folder.$tpl_name)) {
                    $override_tpl_path = $this->context->smarty->getTemplateDir(0).'controllers'.DIRECTORY_SEPARATOR.$this->override_folder.$this->base_folder.$tpl_name;
                }
            }
        } else if ($this->module) {
            $override_tpl_path = _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/'.$tpl_name;
        }
        if (isset($override_tpl_path) && file_exists($override_tpl_path)) {
            return $this->context->smarty->createTemplate($override_tpl_path, $this->context->smarty);
        } else {
            return $this->context->smarty->createTemplate($tpl_name, $this->context->smarty);
        }
    }

    private function _formValidations()
    {
        if (trim(Tools::getValue('name')) == '') {
            $this->validateRules();
            $this->errors[] = $this->l('Field Name can not be empty.');
            $this->display = 'edit';
        }
        if ((float)Tools::getValue('date_from') > 0) {
            if (!Validate::isDate(Tools::getValue('date_from'))) {
                $this->errors[] = $this->l('Invalid "Date From" format');
                $this->display = 'edit';
            }
        }

        if ((float)Tools::getValue('date_to') > 0) {
            if (!Validate::isDate(Tools::getValue('date_to'))) {
                $this->errors[] = $this->l('Invalid "Date To" format');
                $this->display = 'edit';
            }
        }

        if (Tools::getValue('multiple') == 1 && Tools::getValue('increment') == 1) {
            if (Tools::getValue('multiple_qty') > 0 && Tools::getValue('increment_qty') > 0) {
                $this->errors[] = $this->l('Is not possible to save multiple quantity and increment quantity at the same time');
            }
        }

        /* check if the maximum quantity is compatible (todo: create function) */
        $max = (int)Tools::getValue('maximum_quantity');
        $min = (int)Tools::getValue('minimum_quantity');
        $mult = (int)Tools::getValue('multiple_qty');
        $incr = (int)Tools::getValue('increment_qty');

        $diff = 0;
        if ($max > 0) {
            if ($mult > 0) {
                $diff = ($max - $min) % $mult;
            } else {
                if ($incr > 0) {
                    $diff = ($max - $min) % $incr;
                }
            }
        }

        if ($diff > 0) {
            $this->errors[] = $this->l('Bad maximum quantity. It must be exact from minimum adding the multiple or the increment');
        }

        if (Tools::getValue('days') > 0 && Tools::getValue('maximum_quantity') == 0) {
            $this->errors[] = $this->l('Define a maximum quantity if you set the days to check this maximum');
            //$this->display = 'edit';
        }

        if (!empty(Tools::getValue('days'))) {
            if (Tools::getValue('orders_date_from') > 0 || Tools::getValue('orders_date_to') > 0 || Tools::getValue('orders_period') > 0 ) {
                $this->errors[] = $this->l('You can only set one maximum period condition.');
            }
        }

        if (count($this->errors)) {
            return false;
        }

        return true;
    }

    public function printValidIcon($value, $conf)
    {
        $today = date("Y-m-d H:i:s");
        $date_title = '';

        if ($conf['date_from'] > $today) {
            $date_title = $this->l("Future rule");
            if ($conf['date_from'] != "0000-00-00 00:00:00") {
                $date_title = $date_title.'. '.$this->l("Begins in:").' '.$conf['date_from'];
            }
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                return '<span class="time-column future-date" title="'.$date_title.'"></span>';
            } else {
                return '<span class="time-column future-date-icon" title="'.$date_title.'"><i class="icon-time"></i></span>';
            }
        }

        if ($conf['date_to'] == "0000-00-00 00:00:00" || $today < $conf['date_to']) {
            $date_title = $this->l("Valid rule");
            if ($conf['date_from'] != "0000-00-00 00:00:00" && $conf['date_to'] != "0000-00-00 00:00:00") {
                $date_title = $date_title.'. '.$this->l("From:").' '.$conf['date_from'].'. '.$this->l("Until:").' '.$conf['date_to'];
            } else if ($conf['date_from'] != "0000-00-00 00:00:00" && $conf['date_to'] == "0000-00-00 00:00:00") {
                $date_title = $date_title.'. '.$this->l("From:").' '.$conf['date_from'].' ('.$this->l("no expires").')';
            } else if ($conf['date_from'] == "0000-00-00 00:00:00" && $conf['date_to'] != "0000-00-00 00:00:00") {
                $date_title = $date_title.'. '.$this->l("Until:").' '.$conf['date_to'];
            } else {
                $date_title = $date_title.' ('.$this->l("no expires").')';
            }

            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                return '<span class="time-column valid-date" title="'.$date_title.'"></span>';
            } else {
                return '<span class="time-column valid-date-icon" title="'.$date_title.'"><i class="icon-time"></i></span>';
            }
        } else {
            $date_title = $this->l("Expired rule");
            if ($conf['date_from'] != "0000-00-00 00:00:00" && $conf['date_to'] != "0000-00-00 00:00:00") {
                $date_title = $date_title.'. '.$this->l("Between:").' '.$conf['date_from'].' '.$this->l("and:").' '.$conf['date_to'];
            } else {
                $date_title = $date_title.'. '.$this->l("From:").' '.$conf['date_to'];
            }
            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                return '<span class="time-column expired-date" title="'.$date_title.'"></span>';
            } else {
                return '<span class="time-column expired-date-icon" title="'.$date_title.'"><i class="icon-time"></i></span>';
            }
        }
    }

    public function printBackofficeIcon($value, $conf)
    {
        return '<a class="list-action-enable '.($value ? 'action-enabled' : 'action-disabled').'" href="index.php?'.htmlspecialchars('tab=AdminMinpurchase&id_minpurchase_configuration='.(int)$conf['id_minpurchase_configuration'].'&changeBackofficeVal&token='.Tools::getAdminTokenLite('AdminMinpurchase')).'">
                '.($value ? '<i class="icon-check"></i>' : '<i class="icon-remove"></i>').
            '</a>';
    }

    protected function getProductsLite($id_lang, $only_active = false, $front = false)
    {
        $sql = 'SELECT p.`id_product`, CONCAT(p.`id_product`, " - ", pl.`name`) as name FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').')
                WHERE pl.`id_lang` = '.(int)$id_lang.
                    ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').
                    ($only_active ? ' AND product_shop.`active` = 1' : '');

        $rq = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        return ($rq);
    }

    protected function getNumProducts($id_lang, $only_active = false, $front = false, Context $context = null)
    {
        if (!$context)
            $context = Context::getContext();

        $sql = 'SELECT count(p.`id_product`) as num_products FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').')
                WHERE pl.`id_lang` = '.(int)$id_lang.
                    ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '').
                    ($only_active ? ' AND product_shop.`active` = 1' : '');

        $rq = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql);
        return ($rq);
    }

    public function getDynamic($value)
    {
        if ($value) {
            return $this->l('Yes');
        } 
        return $this->l('No');
    }
    
    public function getAmount($amount)
    {
        if ($amount > 0) {
            return Tools::displayPrice($amount);
        } 
        return (int)$amount;
    }

    public function getGroupOptions()
    {
        $group_options = array($this->l('No grouped (individually)'), $this->l('Product'), $this->l('Category'), $this->l('Manufacturers'), $this->l('Suppliers'), $this->l('Attributes'));

        $list_group_options = array();
        foreach ($group_options as $key => $mode) {
            $list_group_options[$key]['id'] = $key;
            $list_group_options[$key]['value'] = $key;
            $list_group_options[$key]['name'] = $mode;
        }
        return $list_group_options;
    }


    protected function getPeriods()
    {
        $periods = array(1 => $this->l('Current month'), 2 => $this->l('Current year'));

        $list_periods = array();
        foreach ($periods as $key => $period) {
            $list_periods[$key]['id'] = $key;
            $list_periods[$key]['value'] = $key;
            $list_periods[$key]['name'] = $period;
        }
        return $list_periods;
    }

    protected function cleanCache() {
        /* delete smarty cache to refresh the static blocks */
        Tools::clearSmartyCache();
        Tools::clearXMLCache();
        Media::clearCache();
        if (version_compare(_PS_VERSION_, '1.7', '<') && version_compare(_PS_VERSION_, '1.4', '>')) {
            PrestaShopAutoload::getInstance()->generateIndex();
        } else if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            Tools::generateIndex();
        }
    }
}
