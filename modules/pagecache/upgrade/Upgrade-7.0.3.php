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
function upgrade_module_7_0_3($module)
{
    $ret = true;

    Configuration::updateValue('pagecache_max_exec_time', (int) ini_get('max_execution_time'));

    return (bool)$ret;
}
