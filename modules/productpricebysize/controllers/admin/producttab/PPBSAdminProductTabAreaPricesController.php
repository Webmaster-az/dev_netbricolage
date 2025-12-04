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

class PPBSAdminProductTabAreaPricesController extends PPBSControllerCore
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

    public function render()
    {
        $area_prices = PPBSAreaPrice::getCollectionByProduct(Tools::getValue('id_product'), $this->id_shop);

        Context::getContext()->smarty->assign(array(
            'area_prices' => $area_prices,
            'id_product' => Tools::getValue('id_product'),
            'module_ajax_url' => $this->module_ajax_url,
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/producttab/areaprices.tpl');
    }

    public function renderAddForm()
    {
        if (Tools::getValue('id_area_price') != '') {
            $area_price = new PPBSAreaPrice(Tools::getValue('id_area_price'));
        } else {
            $area_price = new PPBSAreaPrice();
        }

        if (!empty($area_price)) {
            Context::getContext()->smarty->assign(array('area_price' => $area_price));
        }

        Context::getContext()->smarty->assign(array(
            'id_product' => Tools::getValue('id_product')
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/producttab/areaprices_add.tpl');
    }

    public function processAddForm()
    {
        $area_range = new PPBSAreaPrice(Tools::getValue('id_area_price'));
        $area_range->id_product = (int)Tools::getValue('id_product');
        $area_range->id_shop = $this->id_shop;
        $area_range->area_low = (float)Tools::getValue('area_low');
        $area_range->area_high = (float)Tools::getValue('area_high');
        $area_range->price = (float)Tools::getValue('price');
        $area_range->weight = (float)Tools::getValue('weight');
        $area_range->impact = pSQL(Tools::getValue('impact'));
        $area_range->save();
    }

    public function processDelete()
    {
        if (Tools::getValue('id_area_price') != '') {
            $field = new PPBSAreaPrice(Tools::getValue('id_area_price'));
            $field->delete();
        }
    }

    public function route()
    {
        switch (Tools::getValue('action')) {
            case 'renderaddform':
                die($this->renderAddForm());

            case 'processaddform':
                die($this->processAddForm());

            case 'processdelete':
                die($this->processDelete());

            default:
                return $this->render();
        }
    }
}
