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
 * Fix pagecache_depend_on_device_auto length name
 */
function upgrade_module_4_22()
{
    if (Tools::version_compare(_PS_VERSION_, '1.6', '>')) {
        Configuration::updateValue('pagecache_depend_on_device_auto', Configuration::get('pagecache_depends_on_devices_auto', null, null, null, true));
    }
    return true;
}
