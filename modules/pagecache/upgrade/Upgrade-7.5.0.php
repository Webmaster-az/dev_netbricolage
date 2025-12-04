<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 * @author    Jpresta
 * @copyright Jpresta
 * @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

/**
 * @var $module Jprestaspeedpack
 * @return bool
 */
function upgrade_module_7_5_0($module)
{
    $ret = true;

    $module->updateCacheKeyForCountries();

    JprestaUtils::dbExecuteSQL('ALTER TABLE `' . _DB_PREFIX_ . PageCacheDAO::TABLE . '` DROP COLUMN `mask_country`');

    $module->registerHook('actionObjectSpecificPriceDeleteAfter');
    $module->registerHook('actionObjectSpecificPriceRuleAddAfter');
    $module->registerHook('actionObjectSpecificPriceRuleUpdateAfter');
    $module->registerHook('actionObjectSpecificPriceRuleDeleteAfter');
    $module->registerHook('actionObjectCartRuleAddAfter');
    $module->registerHook('actionObjectCartRuleUpdateAfter');
    $module->registerHook('actionObjectCartRuleDeleteAfter');

    return (bool)$ret;
}
