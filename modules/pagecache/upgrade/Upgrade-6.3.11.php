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
 * Accept cookie for all fake users for module shaim_gdpr
 */
function upgrade_module_6_3_11($module)
{
    $ret = true;
    if (Module::isInstalled('shaim_gdpr')) {
        Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'customer` SET `shaim_gdpr_active` = 1 WHERE `firstname` = \'fake-user-for-pagecache\';');
    }
    return (bool) $ret;
}
