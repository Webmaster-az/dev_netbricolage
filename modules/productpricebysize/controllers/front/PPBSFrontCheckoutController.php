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

class PPBSFrontCheckoutController extends Module
{
    /**
     * Called when an order is placed
     * @param $params
     */
    public function hookActionValidateOrder($params)
    {
        $cart = $params['cart'];
        $order = $params['order'];

        if (empty($order->product_list)) {
            return false;
        }

        foreach ($order->product_list as $product) {
            if ((int)$product['id_customization'] > 0) {
                $ppbs_product = new PPBSProduct($product['id_product']);
                $ppbs_product->loadByProduct($product['id_product']);

                if (!$ppbs_product->stock_enabled) {
                    continue;
                }

                $product_unit_area = PPBSCartHelper::getProductTotalArea($product['id_product'], $product['id_product_attribute'], $product['id_customization'], $cart->id, $cart->id_shop);
                $product_total_area = $product_unit_area * $product['quantity'];
                $current_stock = PPBSStockHelper::getStock($product['id_product'], $product['id_product_attribute'], $cart->id_shop);
                $new_stock = max($current_stock - $product_total_area, 0);
                PPBSStockHelper::updateStock($new_stock, $product['id_product'], $product['id_product_attribute'], $cart->id_shop);
            }
        }
    }

    /**
     * On order status changed
     * @param $params
     */
    public function hookActionOrderStatusPostUpdate($params)
    {
        if (!isset($params['id_order']) || !isset($params['newOrderStatus']->id)) {
            return false;
        }

        if ($params['newOrderStatus']->id != Configuration::get('PS_OS_CANCELED')) {
            return false;
        }

        // Add the stock back
        if (!empty($params['cart'])) {
            $cart = $params['cart'];
        } else {
            $id_order = $params['id_order'];
            $order = new Order($id_order);
            $cart = new Cart($order->id_cart);
        }

        foreach ($cart->getProducts() as $product) {
            if ((int)$product['id_customization'] > 0) {
                $ppbs_product = new PPBSProduct($product['id_product']);
                $ppbs_product->loadByProduct($product['id_product']);

                if (!$ppbs_product->stock_enabled) {
                    continue;
                }

                $product_unit_area = PPBSCartHelper::getProductTotalArea($product['id_product'], $product['id_product_attribute'], $product['id_customization'], $cart->id, $cart->id_shop);
                $product_total_area = $product_unit_area * $product['quantity'];
                $current_stock = PPBSStockHelper::getStock($product['id_product'], $product['id_product_attribute'], $cart->id_shop);
                $new_stock = $current_stock + $product_total_area;
                PPBSStockHelper::updateStock($new_stock, $product['id_product'], $product['id_product_attribute'], $cart->id_shop);
            }
        }
    }
}
