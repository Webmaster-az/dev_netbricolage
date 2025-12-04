<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 * @author    Jpresta
 * @copyright Jpresta
 * @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

/*
 * Cache management for "WT New Products" by Watertheme
 */
function upgrade_module_4_51()
{
    $ret = true;
    Configuration::updateValue('pagecache_product_a_mods',      Configuration::get('pagecache_product_a_mods')      . ' wtnewproducts', false, null, null);
    return (bool)$ret;
}
