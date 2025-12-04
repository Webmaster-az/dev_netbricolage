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
 * Upgrade front end overrides
 */
function upgrade_module_4_20($module)
{
    $module->registerHook('actionObjectProductDeleteBefore');
    $module->unregisterHook('actionProductDelete');

    if (Tools::version_compare(_PS_VERSION_,'1.6','>')) {
        // Not a big deal if not upgraded
        if (Tools::version_compare(_PS_VERSION_,'1.7','<')) {
            // Only PS1.6
            $module->upgradeOverride('BestSalesController');
            $module->upgradeOverride('CategoryController');
            $module->upgradeOverride('ManufacturerController');
            $module->upgradeOverride('NewProductsController');
            $module->upgradeOverride('PricesDropController');
            $module->upgradeOverride('SupplierController');
        }
        else {
            // Only PS1.7
            $module->upgradeOverride('ProductListingFrontController');
        }
        // PS1.7 and PS1.6
        $module->upgradeOverride('Product');
        $module->upgradeOverride('ProductController');
        $module->upgradeOverride('ContactController');
        $module->upgradeOverride('CmsController');
        $module->upgradeOverride('IndexController');
        $module->upgradeOverride('SitemapController');
    }

    return true;
}
