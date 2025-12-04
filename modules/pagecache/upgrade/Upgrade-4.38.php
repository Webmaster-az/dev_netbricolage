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
 * Upgrade all front controllers
 */
function upgrade_module_4_38($module)
{
    if (Tools::version_compare(_PS_VERSION_, '1.7', '>')) {
        $ret = $module->upgradeOverride('Hook')
            && $module->upgradeOverride('CmsController')
            && $module->upgradeOverride('ContactController')
            && $module->upgradeOverride('IndexController')
            && $module->upgradeOverride('ProductController')
            && $module->upgradeOverride('SitemapController');
    }
    else {
        $ret = $module->upgradeOverride('BestSalesController')
            && $module->upgradeOverride('CategoryController')
            && $module->upgradeOverride('CmsController')
            && $module->upgradeOverride('ContactController')
            && $module->upgradeOverride('IndexController')
            && $module->upgradeOverride('ManufacturerController')
            && $module->upgradeOverride('NewProductsController')
            && $module->upgradeOverride('PricesDropController')
            && $module->upgradeOverride('ProductController')
            && $module->upgradeOverride('SitemapController')
            && $module->upgradeOverride('SupplierController');
    }

    return (bool) $ret;
}
