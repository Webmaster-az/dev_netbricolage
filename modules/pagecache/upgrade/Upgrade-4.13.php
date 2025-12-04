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
 * Upgrade overrides of front controllers
 */
function upgrade_module_4_13($module)
{
    if (Tools::version_compare(_PS_VERSION_,'1.7','>')) {
        $module->upgradeOverride('SitemapController');
        $module->upgradeOverride('ProductController');
        $module->upgradeOverride('IndexController');
        $module->upgradeOverride('ContactController');
        $module->upgradeOverride('CmsController');
        $module->upgradeOverride('ProductListingFrontController');
    }
    return true;
}
