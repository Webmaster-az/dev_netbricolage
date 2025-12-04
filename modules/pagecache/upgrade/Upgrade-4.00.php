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
 * Update Dispatcher
 */
function upgrade_module_4_00($module)
{
    // Normalize URLs by default
    Configuration::updateValue('pagecache_normalize_urls', true);

    $module->registerHook('displayBackOfficeHeader');
    $module->registerHook('actionObjectStockAddAfter');
    $module->registerHook('actionObjectStockUpdateAfter');
    $module->registerHook('actionObjectStockDeleteBefore');
    $module->registerHook('actionAdminPerformanceControllerAfter');

    if (Tools::version_compare(_PS_VERSION_,'1.7','>')) {
        $module->registerHook('actionAjaxDieBefore');
    }
    elseif (Tools::version_compare(_PS_VERSION_,'1.6.0.12','>=')) {
        $module->registerHook('actionBeforeAjaxDie');
    }
    if (Tools::version_compare(_PS_VERSION_,'1.6','>')) {
        $hookHeaderId = Hook::getIdByName('Header');
        $module->updatePosition($hookHeaderId, 0, 1);
    }

    foreach (PageCache::$managed_controllers as $controller) {
        if (!Configuration::get('pagecache_'.$controller)) {
            // New way to know if a controller is disabled
            Configuration::updateValue('pagecache_'.$controller.'_timeout', 0);
            Configuration::updateValue('pagecache_'.$controller.'_expires', 0);
        }
    }

    $upgraded_ok = true;
    if (Tools::version_compare(_PS_VERSION_,'1.6.1.0','>=')) {
        $upgraded_ok = $module->upgradeOverride('Dispatcher');

        // Not a big deal if not uninstalled
        $module->removeOverride('BestSalesController');
        $module->removeOverride('CategoryController');
        $module->removeOverride('ManufacturerController');
        $module->removeOverride('NewProductsController');
        $module->removeOverride('PricesDropController');
        $module->removeOverride('SupplierController');
    }

    return (bool) $upgraded_ok;
}
