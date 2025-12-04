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
 * @throws Exception
 */
function upgrade_module_7_4_4($module)
{
    $ret = true;

    if (Tools::version_compare(_PS_VERSION_, '1.7', '<')) {
        $ret = $ret && $module->addOverride('Context');
        $ret = $ret && $module->upgradeOverride('Dispatcher');
    }
    $ret = $ret && $module->upgradeOverride('FrontController');

    return (bool)$ret;
}
