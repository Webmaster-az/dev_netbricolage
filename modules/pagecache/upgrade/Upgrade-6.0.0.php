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
 * - Add an entry in Prestashop menu for the configuration of the module
 * - Remove obsolete overrides and upgrade the others
 * - hook to new hooks
 */
function upgrade_module_6_0_0($module)
{
    $ret = true;
    if (Tools::version_compare(_PS_VERSION_, '1.7', '>')) {
        $module->installTab('AdminPageCacheConfiguration', 'JPresta - Page Cache Ultimate',
            (int)Tab::getIdFromClassName('AdminAdvancedParameters'));
        $module->registerHook('actionClearCache');
        $module->registerHook('actionDispatcherBefore');
        $module->registerHook('actionDispatcherAfter');
        $module->registerHook('actionOutputHTMLBefore');
        $module->upgradeOverride('FrontController');
        JprestaUtils::replaceInFile(_PS_OVERRIDE_DIR_ . '/classes/controller/FrontController.php', 'function smartyOutputContent(', 'function smartyOutputContent_canBeDeleted(');
        JprestaUtils::replaceInFile(_PS_OVERRIDE_DIR_ . '/classes/controller/FrontController.php', 'function getCurrentCustomerGroups(', 'function getCurrentCustomerGroups_canBeDeleted(');
        $module->removeOverride('Media');
        $module->removeOverride('Dispatcher');
        $module->removeOverride('Module');
        $module->removeOverride('Product');
        $module->removeOverride('Category');
        $module->removeOverride('Customer');
        $module->removeOverride('Group');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Media.php');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Dispatcher.php');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Product.php');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Category.php');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Customer.php');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Group.php');
    }
    else if (Tools::version_compare(_PS_VERSION_, '1.6', '>')) {
        $module->installTab('AdminPageCacheConfiguration', 'JPresta - Page Cache Ultimate',
            (int)Tab::getIdFromClassName('AdminTools'));
        $module->upgradeOverride('Dispatcher');
        $module->removeOverride('Product');
        $module->removeOverride('Category');
        $module->removeOverride('Customer');
        $module->removeOverride('Group');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Product.php');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Category.php');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Customer.php');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Group.php');
    }
    else if (Tools::version_compare(_PS_VERSION_, '1.5', '>')) {
        $module->installTab('AdminPageCacheConfiguration', 'JPresta - Page Cache Ultimate',
            (int)Tab::getIdFromClassName('AdminTools'));
        $module->upgradeOverride('Dispatcher');
        $module->removeOverride('Product');
        $module->removeOverride('Category');
        $module->removeOverride('Customer');
        $module->removeOverride('Group');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Product.php');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Category.php');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Customer.php');
        JprestaUtils::deleteFile(_PS_MODULE_DIR_ . '/' . $module->name . '/override/classes/Group.php');
    }

    return (bool) $ret;
}
