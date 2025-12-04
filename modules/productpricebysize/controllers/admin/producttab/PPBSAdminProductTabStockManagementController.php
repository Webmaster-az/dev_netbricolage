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

class PPBSAdminProductTabStockManagementController extends PPBSControllerCore
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

    /**
     * render main
     * @return mixed
     */
    public function render()
    {
        $id_lang = Context::getContext()->language->id;
        $id_product = Tools::getValue('id_product');
        $id_shop = Tools::getValue('id_shop');
        $product = new Product($id_product);
        $combinations = PPBSProductHelper::getCombinations($id_product, $id_lang);
        $ppbs_product = new PPBSProduct();
        $ppbs_product->getByProduct($id_product);
        $product_stock_quantities = PPBSStockHelper::getStockByProduct($id_product, $id_shop);
        $qty_stock = 0;

        if (!empty($product_stock_quantities)) {
            foreach ($product_stock_quantities as $product_stock_quantity) {
                if ($product_stock_quantity['id_product_attribute'] > 0) {
                    if (!empty($combinations[$product_stock_quantity['id_product_attribute']])) {
                        $combinations[$product_stock_quantity['id_product_attribute']]['qty_stock'] = $product_stock_quantity['qty_stock'];
                    }
                } else {
                    $qty_stock = $product_stock_quantity['qty_stock'];
                }
            }
        }

        foreach ($combinations as &$combination) {
            if ($combination['id_image'] > 0) {
                $combination['image_url'] = Context::getContext()->link->getImageLink($product->link_rewrite[$id_lang], $combination['id_image'], ImageType::getFormattedName('medium'));
            } else {
                $combination['image_url'] = '';
            }
            if (empty($combination['qty_stock'])) {
                $combination['qty_stock'] = 0;
            }
        }

        Context::getContext()->smarty->assign(array(
            'id_product' => $id_product,
            'combinations' => $combinations,
            'module_ajax_url' => $this->module_ajax_url,
            'ppbs_product' => $ppbs_product,
            'qty_stock' => $qty_stock
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/producttab/stockmanagement.tpl');
    }

    /**
     * process the form
     */
    public function processForm()
    {
        $id_shop = Tools::getValue('id_shop');
        $id_product = Tools::getValue('id_product');
        $has_combos = false;

        PPBSStockHelper::deleteByProduct($id_product, $id_shop);

        $ppbs_product = new PPBSProduct();
        $ppbs_product->loadByProduct($id_product);

        if (!empty(Tools::getValue('stock_enabled'))) {
            $ppbs_product->stock_enabled = 1;
        } else {
            $ppbs_product->stock_enabled = 0;
        }
        $ppbs_product->save();

        foreach (Tools::getAllValues() as $key => $value) {
            $tmp = explode('_', $key);
            $ipa = 0;
            if (!empty($tmp[2])) {
                if (is_numeric($tmp[2])) {
                    $ipa = $tmp[2];
                    $has_combos = true;
                }
                $ppbs_stock = new PPBSStockModel();
                $ppbs_stock->id_product = (int)$id_product;
                $ppbs_stock->id_product_attribute = (int)$ipa;
                $ppbs_stock->id_shop = (int)$id_shop;
                $ppbs_stock->qty_stock = (float)$value;
                $ppbs_stock->save();
            }
        }

        if (!$has_combos) {
            $ppbs_stock = new PPBSStockModel();
            $ppbs_stock->id_product = (int)$id_product;
            $ppbs_stock->id_product_attribute = 0;
            $ppbs_stock->id_shop = (int)$id_shop;
            $ppbs_stock->qty_stock = (float)Tools::getValue('qty_stock_0');
            $ppbs_stock->save();
        }
    }

    public function route()
    {
        switch (Tools::getValue('action')) {
            case 'processform':
                return($this->processForm());
            default:
                return $this->render();
        }
    }
}
