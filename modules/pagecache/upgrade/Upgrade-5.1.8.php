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
function upgrade_module_5_1_8($module)
{
    $ret = true;
    if (strpos(Configuration::get('pagecache_product_a_mods'), 'ps_newproducts') === false) {
        Configuration::updateValue('pagecache_category_a_mods',
            Configuration::get('pagecache_category_a_mods') . ' ps_categorytree', false, null, null);
        Configuration::updateValue('pagecache_category_u_mods',
            Configuration::get('pagecache_category_u_mods') . ' ps_categorytree', false, null, null);
        Configuration::updateValue('pagecache_category_d_mods',
            Configuration::get('pagecache_category_d_mods') . ' ps_categorytree', false, null, null);
        Configuration::updateValue('pagecache_product_a_mods',
            Configuration::get('pagecache_product_a_mods') . ' ps_newproducts', false, null, null);
        Configuration::updateValue('pagecache_product_home_a_mods',
            Configuration::get('pagecache_product_home_a_mods') . ' ps_featuredproducts', false, null, null);
        Configuration::updateValue('pagecache_product_home_u_mods',
            Configuration::get('pagecache_product_home_u_mods') . ' ps_featuredproducts', false, null, null);
        Configuration::updateValue('pagecache_product_home_d_mods',
            Configuration::get('pagecache_product_home_d_mods') . ' ps_featuredproducts', false, null, null);
    }
    $module->installTab('AdminPageCacheMemcachedTest');
    $module->installTab('AdminPageCacheMemcacheTest');
    $module->installTab('AdminPageCacheProfilingDatas');
    $module->installTab('AdminPageCacheSpeedAnalysis');
    JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/controllers/front/memcachetest.php');
    JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/controllers/front/memcachedtest.php');
    JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/controllers/front/pcspeedanalysis.php');
    JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/controllers/front/profilingdatas.php');
    return (bool) $ret;
}
