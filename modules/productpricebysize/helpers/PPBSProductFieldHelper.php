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

class PPBSProductFieldHelper
{
    protected static $table_name = 'ppbs_product_field';

    /**
     * Get All dimensions with a specific Unit ID
     * @param $id_ppbs_unit
     * @return array|false|mysqli_result|null|PDOStatement|resource
     */
    public static function getAllByUnit($id_ppbs_unit)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(self::$table_name);
        $sql->where('id_ppbs_unit = ' . (int)$id_ppbs_unit);
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }

    /**
     * Get All dimensions with a specific Dimension ID
     * @param $id_ppbs_dimension
     * @return array|false|mysqli_result|null|PDOStatement|resource
     */
    public static function getAllByDimension($id_ppbs_dimension)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(self::$table_name);
        $sql->where('id_ppbs_dimension = '.(int)$id_ppbs_dimension);
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }

    /**
     * Delete field associated with a product
     * @param $id_ppbs_product_field
     */
    public static function delete($id_ppbs_product_field)
    {
        DB::getInstance()->delete('ppbs_product_field_option', 'id_ppbs_product_field = ' . (int)$id_ppbs_product_field);
        DB::getInstance()->delete(self::$table_name, 'id_ppbs_product_field = ' . (int)$id_ppbs_product_field);
    }
}
