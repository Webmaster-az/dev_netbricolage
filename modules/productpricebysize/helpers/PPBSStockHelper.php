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

class PPBSStockHelper
{
    /** @var string */
    private static $table = 'ppbs_stock';

    /**
     * Get stock values for all combos belonging to a product
     * @param $id_product
     * @param $id_shop
     * @return array|false|mysqli_result|null|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public static function getStockByProduct($id_product, $id_shop)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(self::$table);
        $sql->where('id_product = ' . (int)$id_product);
        $sql->where('id_shop = ' . (int)$id_shop);
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }

    /**
     * @param $id_product
     * @param $id_product_attribute
     * @param $id_shop
     */
    public static function getStock($id_product, $id_product_attribute, $id_shop)
    {
        $sql = new DbQuery();
        $sql->select('qty_stock');
        $sql->from(self::$table);
        $sql->where('id_product = ' . (int)$id_product);
        $sql->where('id_product_attribute = ' . (int)$id_product_attribute);
        $sql->where('id_shop = ' . (int)$id_shop);
        $value = Db::getInstance()->getValue($sql);
        if (!$value) {
            return false;
        }
        return $value;
    }

    /**
     * Update the stock level for product product variation
     * @param $stock
     * @param $id_product
     * @param $id_product_attribute
     * @param $id_shop
     * @return bool
     */
    public static function updateStock($stock, $id_product, $id_product_attribute, $id_shop)
    {
        DB::getInstance()->update(
            self::$table,
            array(
                'qty_stock' => (float)$stock
            ),
            'id_product=' . (int)$id_product . ' AND id_product_attribute=' . (int)$id_product_attribute . ' AND id_shop=' . (int)$id_shop
        );
        return true;
    }

    /**
     * Delete all entries by product ID
     * @param $id_product
     * @param $id_shop
     */
    public static function deleteByProduct($id_product, $id_shop = 0)
    {
        if ((int)$id_shop > 0) {
            DB::getInstance()->delete(self::$table, 'id_product = ' . (int)$id_product . ' AND id_shop = ' . (int)$id_shop);
        } else {
            DB::getInstance()->delete(self::$table, 'id_product = ' . (int)$id_product);
        }
    }
}
