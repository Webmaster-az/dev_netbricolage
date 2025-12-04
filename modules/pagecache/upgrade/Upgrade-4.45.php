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
 * Cache management for iqitcontentcreator
 */
function upgrade_module_4_45($module)
{
    $ret = true;
    Configuration::updateValue('pagecache_category_a_mods',     Configuration::get('pagecache_category_a_mods')     . ' iqitcontentcreator', false, null, null);
    Configuration::updateValue('pagecache_category_u_mods',     Configuration::get('pagecache_category_u_mods')     . ' iqitcontentcreator', false, null, null);
    Configuration::updateValue('pagecache_category_d_mods',     Configuration::get('pagecache_category_d_mods')     . ' iqitcontentcreator', false, null, null);
    Configuration::updateValue('pagecache_product_a_mods',      Configuration::get('pagecache_product_a_mods')      . ' iqitcontentcreator', false, null, null);
    Configuration::updateValue('pagecache_product_home_a_mods', Configuration::get('pagecache_product_home_a_mods') . ' iqitcontentcreator', false, null, null);
    Configuration::updateValue('pagecache_product_home_u_mods', Configuration::get('pagecache_product_home_u_mods') . ' iqitcontentcreator', false, null, null);
    Configuration::updateValue('pagecache_product_home_d_mods', Configuration::get('pagecache_product_home_d_mods') . ' iqitcontentcreator', false, null, null);
    if (Tools::version_compare(_PS_VERSION_, '1.7', '<')) {
        $ret = $module->upgradeOverride('Hook');
    }
    return (bool)$ret;
}
