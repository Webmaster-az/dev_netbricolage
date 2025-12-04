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
 * Fix a bug in dynamic refreshment for PS 1.7
 */
function upgrade_module_4_27($module)
{
    $ret = true;
    if (Tools::version_compare(_PS_VERSION_, '1.7', '>')) {
        $ret = $module->upgradeOverride('ProductListingFrontController')
            && $module->upgradeOverride('Hook');
    }
    return (bool) $ret;
}
