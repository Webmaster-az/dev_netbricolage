<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 * @author    Jpresta
 * @copyright Jpresta
 * @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

/**
 * @var $module Jprestaspeedpack
 * @return bool
 */
function upgrade_module_7_7_3($module)
{
    $ret = true;
    if (strpos(Configuration::get('pagecache_product_a_mods'), 'posnewproduct') === false) {
        Configuration::updateValue('pagecache_product_a_mods', Configuration::get('pagecache_product_a_mods') . ' posnewproduct', false, null, null);
    }
    return (bool)$ret;
}
