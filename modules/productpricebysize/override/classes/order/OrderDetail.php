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

class OrderDetail extends OrderDetailCore
{

    public function createList(Order $order, Cart $cart, $id_order_state, $product_list, $id_order_invoice = 0, $use_taxes = true, $id_warehouse = 0)
    {
        include_once(_PS_MODULE_DIR_ . '/productpricebysize/lib/bootstrap.php');
        $this->vat_address = new Address((int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
        $this->customer = new Customer((int)$order->id_customer);

        $this->id_order = $order->id;
        $this->outOfStock = false;

        foreach ($product_list as $product) {
            $product['weight'] = PPBSCartHelper::getOrderDetailProductWeight($product, Context::getContext()->cart->id);
            $product['weight_attribute'] = PPBSCartHelper::getOrderDetailProductWeight($product, Context::getContext()->cart->id);
            $this->create($order, $cart, $product, $id_order_state, $id_order_invoice, $use_taxes, $id_warehouse);
        }

        unset($this->vat_address);
        unset($this->customer);
    }
}
