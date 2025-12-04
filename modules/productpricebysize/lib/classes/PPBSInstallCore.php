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

class PPBSInstallCore
{
    protected static function dropTable($table_name)
    {
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . $table_name . '`';
        DB::getInstance()->execute($sql);
    }
}
