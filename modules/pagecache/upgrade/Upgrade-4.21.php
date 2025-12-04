<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

/*
 * Set pagecache_depend_on_device_auto to true
 */
function upgrade_module_4_21($module)
{
    Configuration::updateValue('pagecache_depend_on_device_auto', true);
    return (bool) $module->addOverride('Media');
}
