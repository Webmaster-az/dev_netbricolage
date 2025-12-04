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

class Product extends ProductCore
{
    public static function priceCalculation($id_shop, $id_product, $id_product_attribute, $id_country, $id_state, $zipcode, $id_currency, $id_group, $quantity, $use_tax, $decimals, $only_reduc, $use_reduc, $with_ecotax, &$specific_price, $use_group_reduction, $id_customer = 0, $use_customer_price = true, $id_cart = 0, $real_quantity = 0, $id_customization = 0)
    {
        include_once(_PS_MODULE_DIR_ . '/productpricebysize/lib/bootstrap.php');
        $pco_price = 0;
        $module = Module::getInstanceByName('productpricebysize');
        $ppbs_front_cart_controller = new PPBSFrontCartController($module);
        $price = parent::priceCalculation(
            $id_shop,
            $id_product,
            $id_product_attribute,
            $id_country,
            $id_state,
            $zipcode,
            $id_currency,
            $id_group,
            $quantity,
            $use_tax,
            $decimals,
            $only_reduc,
            $use_reduc,
            $with_ecotax,
            $specific_price,
            $use_group_reduction,
            $id_customer,
            $use_customer_price,
            $id_cart,
            $real_quantity
        );

        $is_pco_product = 0;
        $is_pbbs_product = 1;
        $is_ppat_product = 0;

        if (Module::isEnabled('productcustomoptions')) {
            include_once(_PS_MODULE_DIR_ . '/productcustomoptions/lib/bootstrap.php');
            $pco_module = \Module::getInstanceByName('productcustomoptions');
            $is_pco_product = \MP\PCO\ProductHelper::isPCOProduct($id_product, $id_shop);
        }

        if (Module::isEnabled('productcustomoptions')) {
            $is_pbbs_product = PPBSProductHelper::isPPBSEnabled($id_product);
        }

        $params = array(
            'price' => $price,
            'quantity' => $quantity,
            'id_product' => $id_product,
            'id_product_attribute' => $id_product_attribute,
            'id_cart' => $id_cart,
            'id_shop' => $id_shop,
            'specific_price' => $specific_price,
            'id_country' => $id_country,
            'id_state' => $id_state,
            'zipcode' => $zipcode,
            'use_tax' => $use_tax,
            'use_reduc' => $use_reduc,
            'id_customization' => $id_customization
        );

        if ($is_pco_product && !$is_pbbs_product) {
            $cart_controller = new \MP\PCO\CartController($module);
            return $cart_controller->priceCalculation($params, true, array(), false);
        } elseif ($is_pbbs_product && $is_pco_product) {
            $cart_controller = new \MP\PCO\CartController($pco_module);
            $pco_params = $params;
            $pco_params['use_tax'] = 0;
            $pco_price = $cart_controller->priceCalculation($pco_params, true, array(), true);
            $params['pco_price_impact'] = $pco_price;
            $price = $ppbs_front_cart_controller->priceCalculation($params);
        } elseif ($is_pbbs_product) {
            $params['pco_price_impact'] = 0;
            $price = $ppbs_front_cart_controller->priceCalculation($params);
        }
        return $price;
    }

    /**
     * Fixes zero product total issue on order confirmation page
     * @param $product
     * @param $customized_datas
     */
    public static function addProductCustomizationPrice(&$product, &$customized_datas)
    {
        include_once(_PS_MODULE_DIR_ . '/productpricebysize/lib/bootstrap.php');
        $controller = Tools::getValue('controller');


        if (empty($product['product_id'])) {
            $id_product = $product['id_product'];
        } else {
            $id_product = $product['product_id'];
        }

        if ($controller == 'AdminOrders' || $controller == 'AdminCarts') {
            return parent::addProductCustomizationPrice($product, $customized_datas);
        }
        if (PPBSProductHelper::isPPBSEnabled($id_product)) {
            return;
        }
    }
}
