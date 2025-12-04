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
 * Cache management for new modules name
 */
function upgrade_module_5_1_17()
{
    $ret = true;
    if (Configuration::get('pagecache_exec_header_hook') === false) {
        Configuration::updateValue('pagecache_exec_header_hook', 1, false, null, null);
    }

    return (bool) $ret;
}
