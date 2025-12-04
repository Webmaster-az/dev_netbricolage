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

class PPBSOrderHelper
{

    /**
     * Get details for a product in an order
     * @param $id_order
     * @param $id_product
     * @param $id_product_attribute
     * @param int $id_customization
     * @return array|bool|null|object
     */
    public static function getOrderProduct($id_order, $id_product, $id_product_attribute, $id_customization = 0)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('order_detail');
        $sql->where('product_id = ' . (int)$id_product);
        $sql->where('product_attribute_id = ' . (int)$id_product_attribute);
        $sql->where('id_order = ' . (int)$id_order);
        if ($id_customization > 0) {
            $sql->where('id_customization = ' . (int)$id_customization);
        }
        $row = Db::getInstance()->getRow($sql);
        return $row;
    }

    /**
     * Update the order carrier weight
     * @param $id_order
     * @param $weight
     */
    public static function updateOrderCarrierWeight($id_order, $weight)
    {
        Db::getInstance()->update(
            'order_carrier',
            array(
                'weight' => $weight
            ),
            'id_order = ' . (int)$id_order
        );
    }

    /**
     * Update the order shipping cost
     * @param $id_order
     * @param $id_carrier
     * @param $shipping_cost_tax_excl
     * @param $shipping_cost_tax_incl
     */
    public static function updateOrderCarrierCost($id_order, $id_carrier, $shipping_cost_tax_excl, $shipping_cost_tax_incl)
    {
        Db::getInstance()->update(
            'order_carrier',
            array(
                'shipping_cost_tax_excl' => (float)$shipping_cost_tax_excl,
                'shipping_cost_tax_incl' => (float)$shipping_cost_tax_incl,
            ),
            'id_order = ' . (int)$id_order . ' AND id_carrier = ' . (int)$id_carrier
        );
    }
}
