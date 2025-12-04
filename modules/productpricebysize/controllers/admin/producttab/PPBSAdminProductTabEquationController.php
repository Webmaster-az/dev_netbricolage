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

class PPBSAdminProductTabEquationController extends PPBSControllerCore
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
        $ppbs_product = new PPBSProduct();
        $ppbs_product->getByProduct(Tools::getValue('id_product'));

        $combinations = PPBSProductHelper::getCombinations(Tools::getValue('id_product'), Context::getContext()->language->id);

        Context::getContext()->smarty->assign(array(
            'ppbs_product' => $ppbs_product,
            'combinations' => $combinations,
            'id_product' => $this->params['id_product'],
            'module_ajax_url' => $this->module_ajax_url,
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/producttab/equation.tpl');
    }

    /**
     * Update the equation enabled data flag for the product
     */
    public function processEnabled()
    {
        $ppbs_product = new PPBSProduct();
        $ppbs_product->getByProduct(Tools::getValue('id_product'));
        $ppbs_product->id_product = (int)Tools::getValue('id_product');
        $ppbs_product->equation_enabled = (int)Tools::getValue('equation_enabled');
        $ppbs_product->save();
    }

    public function route()
    {
        switch (Tools::getValue('action')) {
            case 'render':
                die($this->render());

            case 'processenabled':
                die($this->processEnabled());
        }
    }
}
