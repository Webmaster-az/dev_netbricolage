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
function upgrade_module_7_7_0($module)
{
    $ret = true;

    $module->registerHook('actionObjectGroupAddAfter');
    $module->registerHook('actionObjectGroupUpdateAfter');
    $module->registerHook('actionObjectGroupDeleteAfter');
    $module->unregisterHook('actionObjectCartRuleAddAfter');
    $module->unregisterHook('actionObjectCartRuleUpdateAfter');
    $module->unregisterHook('actionObjectCartRuleDeleteAfter');

    PageCache::updateCacheKeyForCountries();
    PageCache::updateCacheKeyForUserGroups();
    JprestaCustomer::deleteAllFakeUsers();

    return (bool)$ret;
}
