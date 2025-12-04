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
 * Fix pagecache_product_refresh_every_x length name and .htaccess for WEBP
 */
function upgrade_module_6_3_13($module)
{
    $ret = true;
    Configuration::updateValue('pagecache_product_refreshEveryX', Configuration::get('pagecache_product_refresh_every_x', null, null, null, 1));

    return (bool) $ret;
}
