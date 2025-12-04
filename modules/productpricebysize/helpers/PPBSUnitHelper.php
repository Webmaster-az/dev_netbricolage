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

class PPBSUnitHelper
{
    protected static $table_name = 'ppbs_unit';

    /**
     * Delete a unit including all related entities in foreign tables
     * @param $id_ppbs_unit
     */
    public static function deleteFull($id_ppbs_unit)
    {
        $product_fields = PPBSProductFieldHelper::getAllByUnit($id_ppbs_unit);

        foreach ($product_fields as $product_field) {
            PPBSProductFieldHelper::delete($product_field['id_ppbs_product_field']);
        }

        DB::getInstance()->delete(self::$table_name, 'id_ppbs_unit = ' . (int)$id_ppbs_unit);
        DB::getInstance()->delete('ppbs_unit_lang', 'id_ppbs_unit = ' . (int)$id_ppbs_unit);
    }
}
