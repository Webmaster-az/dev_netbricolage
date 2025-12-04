<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Musaffar Patel
 * @copyright 2016-2021 Musaffar Patel
 * @license   LICENSE.txt
 */

use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;

class PPBSFrontProductController extends Module
{

    protected $sibling;

    public function __construct(&$sibling)
    {
        parent::__construct();

        if ($sibling !== null) {
            $this->sibling = &$sibling;
        }
    }

    public function setMedia()
    {
        if ($this->sibling->context->controller->php_self != 'product' && $this->sibling->context->controller->php_self != 'category') {
            return false;
        }
        $this->sibling->context->controller->addJquery();
        $this->sibling->context->controller->addJqueryPlugin('typewatch');
        $this->sibling->context->controller->registerJavascript('ppbs_tools', 'modules/' . $this->sibling->name . '/views/js/lib/Tools.js');
        $this->sibling->context->controller->addJS($this->sibling->_path . 'views/js/front/PPBSFrontProductController.js');
        $this->sibling->context->controller->addCSS($this->sibling->_path . 'views/css/front/product.css');
    }

    public function hookDisplayPPBSWidget(array $params): string
    {
        if (Context::getContext()->controller->php_self != 'product') {
            return false;
        }

        $ppbs_product = new PPBSProduct();
        $ppbs_product->getByProduct(Tools::getValue('id_product'));

        if (empty($ppbs_product->id) || $ppbs_product->enabled == false) {
            return '';
        }

        $link = new \Link();
        $this->sibling->smarty->assign(array(
            'baseDir' => __PS_BASE_URI__,
            'action' => Tools::getValue('action'),
            'pbbs_enabled' => 1,
            'id_currency' => Context::getContext()->currency->id,
            'module_ajax_url' => $link->getModuleLink('productpricebysize', 'ajax', array()),
        ));
        $this->renderWidget();
        return $this->sibling->display($this->sibling->module_file, 'views/templates/front/product_display_ppbs_widget.tpl');
    }


    /**
     * Add script initialisation vars for the PPBS widgt which will be loaded via ajax
     * @param $params
     * @return bool
     */
    public function hookDisplayFooter($params)
    {
        if (Context::getContext()->controller->php_self != 'product') {
            return false;
        }

        $global_variables = array();
        $ppbs_product = new PPBSProduct();
        $ppbs_product->getByProduct(Tools::getValue('id_product'));

        if (empty($ppbs_product->id) || $ppbs_product->enabled == false) {
            return '';
        }

        $link = new \Link();
        $this->sibling->smarty->assign(array(
            'baseDir' => __PS_BASE_URI__,
            'action' => Tools::getValue('action'),
            'pbbs_enabled' => 1,
            'id_currency' => Context::getContext()->currency->id,
            'module_ajax_url' => $link->getModuleLink('productpricebysize', 'ajax', array()),
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/front/product_footer.tpl');
    }

    /**
     * Displ;ay the module on the product page
     * @param $module_file
     * @return string
     */
    public function renderWidget()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_lang = (int)Context::getContext()->language->id;
        $global_variables = array();

        $config = new PPBSConfigModel(0, 0, Context::getContext()->shop->id);
        $translations = array();
        $group_reduction = 0;

        if (!empty(Context::getContext()->customer->id_default_group)) {
            $id_group = Context::getContext()->customer->id_default_group;
            $customer_group = new Group($id_group);
            $group_reduction = $customer_group->reduction;
        }

        $product_fields = PPBSProductField::getCollectionByProduct(Tools::getValue('id_product'), $this->context->language->id, true);

        $ppbs_product = new PPBSProduct();
        $ppbs_product->getByProduct(Tools::getValue('id_product'));
        if (empty($ppbs_product->id) || $ppbs_product->enabled == false) {
            return '';
        }

        // Translations
        //$area_price_suffix = new PPBSAreaPriceSuffixModel($ppbs_product->id_ppbs_areapricesuffix, $this->context->language->id);

        $ppbs_unit_model = new PPBSUnit($ppbs_product->id_ppbs_unit_default, Context::getContext()->language->id);

        if (!empty($ppbs_unit_model->symbol)) {
            $translations['area_price_suffix'] = $ppbs_unit_model->symbol;
        } else {
            $translations['area_price_suffix'] = '';
        }

        $translations_obj = PPBSTranslation::loadTranslations();
        $translations['generic_error'] = $translations_obj['generic_error'][$this->context->language->id];
        $translations['unit_price_suffix'] = $translations_obj['unit_price_suffix'][$this->context->language->id];

        /* Add min max error to the Units collection, and make key the field ID */
        $product_fields_arranged = array();
        foreach ($product_fields as &$field) {
            if ($field['visible'] == 1) {
                $field['error'] = $translations_obj['min_max_error'][$this->context->language->id];
                $field['error'] = str_replace('{min}', $field['min'], $field['error']);
                $field['error'] = str_replace('{max}', $field['max'], $field['error']);
                $field['min'] = $field['min'];
                $field['max'] = $field['max'];

                if ($field['input_type'] == 'dropdown') {
                    $field['options'] = PPBSProductFieldOption::getFieldOptions($field['id_ppbs_product_field']);
                }

                if ($field['input_type'] == 'textbox') {
                    $field['default'] = round($field['default'], $field['decimals']);
                    $field['default'] = sprintf('%0.'.$field['decimals'].'f', $field['default']);
                }

                $unit = new PPBSUnit($field['id_ppbs_unit'], $this->context->language->id);
                if (!empty($unit->id)) {
                    $field['unit'] = $unit;
                }
                $product_fields_arranged[$field['id_ppbs_product_field']] = $field;
            }
        }

        /* Price Adjustments */
        $currency = new Currency(Tools::getValue('id_currency'));
        $areaPriceCollection = PPBSAreaPrice::getAreaPrices((int)Tools::getValue('id_product'), $this->context->shop->id);
        $areaPriceCollection = PPBSProductHelper::convertAreaPricesToCurrency($areaPriceCollection, null, $currency);

        /* Custom equations */
        $ppbs_equations_collection = PPBSEquationTemplateHelper::getAllEquationInfoForProduct(Tools::getValue('id_product'), 'price');

        // Construct Ratio array
        $ppbs_field_ratios = array();
        foreach ($product_fields as $product_field) {
            if ((int)$product_field['ratio'] > 0) {
                $ppbs_field_ratios[] = array(
                    'id_ppbs_product_field' => $product_field['id_ppbs_product_field'],
                    'ratio' => (int)$product_field['ratio']
                );
            }
        }

        $ppbs_options = array(
            'display_total_area' => $config->getDisplayTotalArea()
        );

        $default_unit = [];
        $conversion_options = PPBSProductUnitConversionHelper::getOptionsByProduct($id_product, $id_lang);

        if (!empty($product_fields) && !empty($conversion_options)) {
            $id_ppbs_unit_default = $ppbs_product->id_ppbs_unit_default;
            foreach ($conversion_options as &$conversion_option) {
                if ($conversion_option['id_ppbs_unit'] == $id_ppbs_unit_default) {
                    $conversion_option['default'] = 1;
                }
            }
        } else {
            $default_unit = new PPBSUnit($ppbs_product->id_ppbs_unit_default, $id_lang);
        }

        $ppbs_product->default_unit = $default_unit;

        if ($ppbs_product->equation_enabled) {
            $global_variables = PPBSEquationTemplateHelper::getVariables();
        }

        // for Creative Elements - when CE re-renders module hook, preserve values customer may have entered
        foreach ($product_fields_arranged as &$product_field_arranged) {
            $tmp_key = 'ppbs_field-' . $product_field_arranged['id_ppbs_product_field'];
            if (Tools::getValue($tmp_key) != '') {
                $product_field_arranged['default'] = Tools::getValue($tmp_key);
            }
        }

        $this->sibling->smarty->assign(array(
            'ppbs_options' => $ppbs_options,
            'ppbs_options_json' => json_encode($ppbs_options),
            'ppbs_product' => $ppbs_product,
            'ppbs_product_json' => json_encode($ppbs_product),
            'ppbs_price_adjustments_json' => json_encode($areaPriceCollection),
            'ppbs_equations_collection_json' => json_encode(PPBSProductHelper::createCombinationsLookup($ppbs_equations_collection)),
            'ppbs_global_variables' => json_encode($global_variables),
            'product_fields' => $product_fields_arranged,
            'product_fields_json' => json_encode($product_fields_arranged),
            'conversion_options' => $conversion_options,
            'id_language' => $this->context->language->id,
            'id_shop' => $this->context->shop->id,
            'translations' => $translations,
            'group_reduction' => $group_reduction,
            'ppbs_field_ratios_json' => json_encode($ppbs_field_ratios)
        ));

        return $this->sibling->fetch('module:' . $this->sibling->name . '/views/templates/front/ppbs.tpl');
    }

    /**
     * Get Product Information such as prices, tax etc based on id_product and id_product_attribute
     */
    public function getProductInfo()
    {
        return (json_encode(PPBSProductHelper::getProductInfo(Tools::getValue('id_product'), Tools::getValue('group'), 0, (int)Tools::getValue('quantity'))));
    }

    public function formatPrice()
    {
        $price = PPBSProductHelper::formatPrice(Tools::getValue('price'));
        $priceFormatter = new PriceFormatter();
        return $priceFormatter->convertAndFormat($price);
    }

    /**
     * Add PPBS flag to the product in product lists
     * @param $params
     * @return mixed
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function hookFilterProductSearch($params)
    {
        if (empty($params['searchVariables']['products'])) {
            return $params;
        }

        include_once(_PS_MODULE_DIR_ . '/productpricebysize/lib/bootstrap.php');
        foreach ($params['searchVariables']['products'] as &$product) {
            $ppbs_product_model = new PPBSProduct();
            $ppbs_product_model->getByProduct($product['id_product']);

            $product['ppbs_enabled'] = 0;

            if (Module::isEnabled('ProductPriceBySize')) {
                if (isset($ppbs_product_model->enabled) && $ppbs_product_model->enabled) {
                    $product['ppbs_enabled'] = 1;
                }
            }
        }
        return $params;
    }

    public function route()
    {
        switch (Tools::getValue('action')) {
            case 'renderwidget':
                return $this->renderWidget();

            case 'getproductinfo':
                die($this->getProductInfo());

            case 'formatprice':
                die($this->formatPrice());
        }
    }
}
