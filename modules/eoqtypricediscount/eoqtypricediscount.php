<?php
/**
*  2017-2020 Profileo
*
*  @author    Profileo <contact@profileo.com>
*  @copyright 2017-2020 Profileo
*  @license   Profileo
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Eoqtypricediscount extends Module
{
    public function __construct()
    {
        $this->name = 'eoqtypricediscount';
        $this->tab = 'pricing_promotion';
        $this->version = '1.0.9';
        $this->author = 'Profileo';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->module_key = 'b050257e0eb622baacb4506035e792f9';


        parent::__construct();

        $this->displayName = $this->l('Quantity discounts - extended display');
        $this->description = $this->l('Display quantity discount on product list and home page');
    }

    public function install()
    {
        return parent::install() &&
            $this->registerHook('displayProductPriceBlock') &&
            $this->registerHook('header') &&
            Configuration::updateValue('EO_QTY_PRICE_DC', true) &&
            Configuration::updateValue('EO_QTY_PRICE_DC_HOME', true) &&
            Configuration::updateValue('EO_QTY_PRICE_DC_CAT', true);
    }

    public function getContent()
    {
        include dirname(__FILE__).'/lib/cross_selling_addons/CrossSellingHelper.php';

        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addJS($this->_path.'views/js/iframeResizer.min.js');
        $html = '';
        if (Tools::isSubmit('btnSubmit')) {
            foreach ($this->getConfigFieldsValues() as $key => $value) {
                Configuration::updateValue($key, $value);
            }
            $html .= $this->displayConfirmation($this->l('Configuration saved'));
        }

        return CrossSellingHelper::getHeader($this, dirname(__FILE__)) . $html.$this->renderForm().
        CrossSellingHelper::getFooter($this, __FILE__);
    }

    public function renderIframe($type = 'top')
    {
        return '<iframe src="'.$this->getProfileoBannerUrl($type)
        .'" frameborder="0" class="eo_banner" style="width: 100%;margin: 15px 0;"></iframe>';
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l("Configuration"),
                    'icon' => 'icon-cog'
                ),
                'input' => array(
                    array(
                        'type' => version_compare(_PS_VERSION_, '1.6', '>') ? 'switch' : 'radio' ,
                        'is_bool' => true,
                        'class' => 't',
                        'label' => $this->l('Display product price'),
                        'name' => 'EO_QTY_PRICE_DC',
                        'desc' => $this->l('Show Lowest Price ( From: )'),
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
                        'type' => version_compare(_PS_VERSION_, '1.6', '>') ? 'switch' : 'radio' ,
                        'is_bool' => true,
                        'class' => 't',
                        'label' => $this->l('Display on home page'),
                        'name' => 'EO_QTY_PRICE_DC_HOME',
                        'desc' => $this->l('Please clear cache after changing this configuration'),
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
                        'type' => version_compare(_PS_VERSION_, '1.6', '>') ? 'switch' : 'radio' ,
                        'is_bool' => true,
                        'class' => 't',
                        'label' => $this->l('Display on category page'),
                        'name' => 'EO_QTY_PRICE_DC_CAT',
                        'desc' => $this->l('Please clear cache after changing this configuration'),
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
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $bo_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->allow_employee_form_lang = $bo_form_lang ? $bo_form_lang : 0;
        $this->fields_form = array();
        $helper->id = (int)Tools::getValue('id_match');
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.
            $this->name.'&tab_module='.
            $this->tab.'&module_name='.
            $this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $discount = (int)Tools::getValue('EO_QTY_PRICE_DC', Configuration::get('EO_QTY_PRICE_DC'));
        $home = (int)Tools::getValue('EO_QTY_PRICE_DC_HOME', Configuration::get('EO_QTY_PRICE_DC_HOME'));
        $cat = (int)Tools::getValue('EO_QTY_PRICE_DC_CAT', Configuration::get('EO_QTY_PRICE_DC_CAT'));

        return array(
            'EO_QTY_PRICE_DC' => $discount,
            'EO_QTY_PRICE_DC_HOME' => $home,
            'EO_QTY_PRICE_DC_CAT' => $cat,
        );
    }

    public function hookHeader()
    {
        if (!Configuration::get('EO_QTY_PRICE_DC')) {
            return;
        }

        $this->context->controller->addCSS($this->_path.'views/css/eoqtypricediscount.css');
        $this->context->controller->addJS($this->_path.'views/js/script.js');
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if ($params['type'] != 'unit_price') {
            return;
        }

        if (!Configuration::get('EO_QTY_PRICE_DC_HOME') &&
                get_class($this->context->controller) == 'IndexController') {
            return;
        }


        if (!Configuration::get('EO_QTY_PRICE_DC_CAT') &&
                get_class($this->context->controller) == 'CategoryController') {
            return;
        }

        $module = Tools::getValue('module');
        
        if (!in_array(get_class($this->context->controller), array(
            'PricesDropController',
            'CategoryController',
            'IndexController',
            'ManufacturerController',
            'ProductController',
            'SearchController',
            'FrontController')) && $module != 'pm_advancedsearch4') {
            return;
        }

        if (get_class($this->context->controller) != 'ProductController') {
            $product = new Product((int) $params['product']['id_product']);
        } else {
            $product = new Product((int) Tools::getValue('id_product'));
        }
        
        if (!Validate::isLoadedObject($product)) {
            return;
        }

        // Get default id_product_attribute
        $id_product_attribute_default = Product::getDefaultAttribute($product->id, 0, false);

        $quantity_discounts = SpecificPrice::getQuantityDiscounts(
            (int)$product->id,
            (int)$this->context->shop->id,
            (int)$this->context->currency->id,
            (int)$this->context->country->id,
            (int)$this->context->customer->id_default_group,
            null,
            true,
            (int)$this->context->customer->id
        );

        if (empty($quantity_discounts)) {
            return;
        }

        //Remove duplicate entries
        foreach ($quantity_discounts as $key => $value) {
            if (empty($value['id_product_attribute']) || $value['id_product_attribute'] == 0) {
                continue;
            }

            if ($value['id_product_attribute'] !=  $id_product_attribute_default) {
                unset($quantity_discounts[$key]);
            }
        }

        $priceDisplay = Product::getTaxCalculationMethod((int)$this->context->cookie->id_customer);

        if ($priceDisplay == 1) {
            $product_price = $product->getPrice(false);
        } else {
            $product_price = $product->getPrice(true);
        }

        $addr = new Address((int)$this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
        $tax = (float)$product->getTaxesRate($addr);


        if ($priceDisplay == 1) {
            $tax_value = $this->l('tax excl.');
        } else {
            $tax_value = $this->l('tax incl.');
        }

        $this->context->smarty->assign(array(
                'quantity_discounts' => $this->formatQuantityDiscounts(
                    $quantity_discounts,
                    $product_price,
                    (float)$tax
                ),
                'productPrice' => $product_price,
                'from_eo' => $this->l('From :'),
                'tax_value' => $tax_value,
                'eohover' => version_compare(_PS_VERSION_, '1.7.0.0', '>=') ? ' eohover' : ''
                ));

        return $this->display(dirname(__FILE__), 'hook.tpl');
    }

    protected function formatQuantityDiscounts($spe_prices, $price, $tax_rate, $ecotax_amount = 0)
    {
        // Tax configuration for the group
        $group_tax_calc_method = (int)Product::$_taxCalculationMethod; // 0 = TTC = PS_TAX_INC, 1 = HT = PS_TAX_EXC
        // Tax configuration for the shop (in AdminTaxes)
        $shop_tax_calc_method = (int)Configuration::get('PS_TAX'); // 1 = enabled, 0 = disabled
        $taxInc = $group_tax_calc_method === PS_TAX_INC && $shop_tax_calc_method;

        foreach ($spe_prices as $key => &$row) {
            $row['quantity'] = &$row['from_quantity'];
            if ($row['price'] >= 0) {
                // Product price overrided
                $cur_price = (!$taxInc ? $row['price'] : $row['price'] * (1 + $tax_rate / 100))
                    + (float)$ecotax_amount;
                if ($row['reduction_type'] == 'amount') {
                    $cur_price -= ($taxInc ? $row['reduction'] : $row['reduction']
                        / (1 + $tax_rate / 100));
                } else {
                    $cur_price *= 1 - $row['reduction'];
                }
                $row['real_value'] = $price - $cur_price;
            } else {
                if ($row['reduction_type'] == 'amount') {
                    // The discount can be with or without tax, (reduction_tax = 0 = Without tax)
                    $discountTaxExc = (int)$row['reduction_tax'] === 0;
                    $row['real_value'] = !$taxInc || $discountTaxExc ? $row['reduction'] : $row['reduction']
                        / (1 + $tax_rate / 100);
                } else {
                    $row['real_value'] = $row['reduction'] * 100;
                }
            }
            $row['nextQuantity'] = (isset($spe_prices[$key + 1]) ? (int)$spe_prices[$key + 1]['from_quantity'] : - 1);
        }

        return $spe_prices;
    }

    protected function getProfileoBannerUrl($type = 'top')
    {
        $base_url = 'https://addonsmodules.tools.profileo.com';

        $params = array(
            'mv' => $this->version,
            'psv' => _PS_VERSION_,
            'iso_lang' => $this->context->language->iso_code,
            );

        if ($type == 'bottom') {
            return $base_url.'/banner_bottom_'.$this->name.'.html?'.http_build_query($params);
        } else {
            return $base_url.'/banner_top_'.$this->name.'.html?'.http_build_query($params);
        }
    }
}
