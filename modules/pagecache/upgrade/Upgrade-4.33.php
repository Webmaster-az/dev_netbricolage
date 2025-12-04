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
 * Clear cache
 */
function upgrade_module_4_33($module)
{
    // Be sure new javascript is taken
    if (method_exists('Media','clearCache')) {
        Media::clearCache();
    }

    // Clear cache because JS will change
    $module->clearCache();

    $ret = $module->upgradeOverride('Hook');

    return (bool) $ret;
}
