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
 * Cache management for new modules name
 */
function upgrade_module_4_42()
{
    $ret = true;
    Configuration::updateValue('pagecache_category_a_mods',     Configuration::get('pagecache_category_a_mods') .     ' ps_categorytree', false, null, null);
    Configuration::updateValue('pagecache_category_u_mods',     Configuration::get('pagecache_category_u_mods') .     ' ps_categorytree', false, null, null);
    Configuration::updateValue('pagecache_category_d_mods',     Configuration::get('pagecache_category_d_mods') .     ' ps_categorytree', false, null, null);
    Configuration::updateValue('pagecache_product_a_mods',      Configuration::get('pagecache_product_a_mods') .      ' ps_newproducts', false, null, null);
    Configuration::updateValue('pagecache_product_home_a_mods', Configuration::get('pagecache_product_home_a_mods') . ' ps_featuredproducts', false, null, null);
    Configuration::updateValue('pagecache_product_home_u_mods', Configuration::get('pagecache_product_home_u_mods') . ' ps_featuredproducts', false, null, null);
    Configuration::updateValue('pagecache_product_home_d_mods', Configuration::get('pagecache_product_home_d_mods') . ' ps_featuredproducts', false, null, null);

    return (bool) $ret;
}
