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
 * Remove some overrides...
 */
function upgrade_module_5_0_0($module)
{
    if (Tools::version_compare(_PS_VERSION_, '1.7', '>')) {
        $ret = $module->upgradeOverride('Hook')
            && $module->upgradeOverride('FrontController');

        $overridesToRemove = array(
            'CmsController',
            'ContactController',
            'IndexController',
            'ProductController',
            'SitemapController'
        );
    }
    else {
        $ret = $module->upgradeOverride('FrontController');

        $overridesToRemove = array(
            'BestSalesController',
            'CategoryController',
            'CmsController',
            'ContactController',
            'IndexController',
            'ManufacturerController',
            'NewProductsController',
            'PricesDropController',
            'ProductController',
            'SitemapController',
            'SupplierController',
        );
    }

    foreach ($overridesToRemove as $overrideToRemove) {
        $ret = $ret && $module->removeOverride($overrideToRemove);
        $overrideFile = _PS_MODULE_DIR_ . '/' . $module->name . '/override/controllers/front/'.$overrideToRemove.'.php';
        if (file_exists($overrideFile)) {
            JprestaUtils::deleteFile($overrideFile);
        }
    }

    return (bool) $ret;
}
