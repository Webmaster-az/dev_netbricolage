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
 * Upgrade overrides FrontController.php if needed and modify smartyfront.config.inc.php
 */
function upgrade_module_4_18($module)
{
    if (Configuration::get('PS_GEOLOCATION_ENABLED')) {
        $module->upgradeOverride('FrontController');
    }

    $module->patchSmartyConfigFront();

    return true;
}
