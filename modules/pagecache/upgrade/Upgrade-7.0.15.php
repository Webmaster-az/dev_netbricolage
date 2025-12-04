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
 * Register to hook displayAdminAfterHeader
 * @var $module Jprestaspeedpack
 * @return bool
 */
function upgrade_module_7_0_15($module)
{
    $ret = true;

    try {
        $module->registerHook('displayAdminAfterHeader');
    }
    catch (Throwable $e) {
        // Ignore because it is not a big deal
    }

    return (bool)$ret;
}
