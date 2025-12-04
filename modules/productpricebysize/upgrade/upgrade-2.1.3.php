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

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(_PS_MODULE_DIR_ . '/productpricebysize/lib/bootstrap.php');

function upgrade_module_2_1_3($object)
{
    $return = true;
    PPBSInstall::addColumn('ppbs_product_field', 'display_suffix', 'tinyint(3) unsigned DEFAULT 0');
    return $return;
}
