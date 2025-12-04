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

class PPBSAdminProductTabGeneralController extends PPBSControllerCore
{
    protected $id_shop = 0;

    public function __construct($sibling, $params = array())
    {
        parent::__construct($sibling, $params);
        $this->sibling = $sibling;
        $this->base_url = Tools::getShopProtocol() . Tools::getShopDomain() . __PS_BASE_URI__;

        $product = new Product(Tools::getValue('id_product'));
        if (!empty($product->id_shop_default)) {
            $this->id_shop = $product->id_shop_default;
        } else {
            $this->id_shop = Context::getContext()->shop->id;
        }
    }

    public function setMedia()
    {
    }

    public function render()
    {
        $id_product = Tools::getValue('id_product');
        $ppbs_product = new PPBSProduct();
        $ppbs_product->getByProduct($id_product);
        $units = PPBSUnit::getUnits();

        foreach ($units as &$unit) {
            $product_conversion_unit = PPBSProductUnitConversionHelper::get($unit['id_ppbs_unit'], $id_product);
            if (!empty($product_conversion_unit['id_ppbs_product_unit_conversion'])) {
                $unit['checked'] = 1;
            } else {
                $unit['checked'] = 0;
            }
        }

        Context::getContext()->smarty->assign(array(
            'module_ajax_url' => $this->module_ajax_url,
            'id_product' => $this->params['id_product'],
            'ppbs_product' => $ppbs_product,
            'units' => $units
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/producttab/general.tpl');
    }

    public function processForm()
    {
        $unit_conversions = Tools::getValue('unit_conversions');

        $id_product = (int)Tools::getValue('id_product');
        $ppbs_product = new PPBSProduct();
        $ppbs_product->getByProduct($id_product);
        $ppbs_product->id_product = (int)$id_product;
        $ppbs_product->id_ppbs_unit_default = (int)Tools::getValue('id_ppbs_unit_default');
        $ppbs_product->enabled = (int)Tools::getValue('enabled');
        $ppbs_product->min_price = (float)Tools::getValue('min_price');
        $ppbs_product->min_total_area = (float)Tools::getValue('min_total_area');
        $ppbs_product->setup_fee = (float)Tools::getValue('setup_fee');
        $ppbs_product->attribute_price_as_area_price = (int)Tools::getValue('attribute_price_as_area_price');
        $ppbs_product->front_conversion_enabled = (int)Tools::getValue('front_conversion_enabled');
        $ppbs_product->front_conversion_operator = pSQL(Tools::getValue('front_conversion_operator'));
        $ppbs_product->front_conversion_value = (float)Tools::getValue('front_conversion_value');
        if (!empty($unit_conversions)) {
            $ppbs_product->front_conversion_enabled = 0;
        }
        $ppbs_product->save();

        // Save unit conversion options
        PPBSProductUnitConversionHelper::deleteByProduct($id_product);
        if (!empty($unit_conversions)) {
            foreach ($unit_conversions as $id_ppbs_unit) {
                $ppbs_product_unit_conversion_model = new PPBSProductUnitConversion();
                $ppbs_product_unit_conversion_model->id_product = (int)$id_product;
                $ppbs_product_unit_conversion_model->id_ppbs_unit = (int)$id_ppbs_unit;
                $ppbs_product_unit_conversion_model->save();
            }
        }
    }

    public function route()
    {
        $return = '';

        switch (Tools::getValue('action')) {
            case 'processform':
                die($this->processForm());

            default:
                return $this->render();
        }
    }
}
