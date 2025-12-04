<?php
/**
* 2012-2018 Areama
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@areama.net so we can send you a copy immediately.
*
*  @author    Areama <contact@areama.net>
*  @copyright 2018 Areama
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Areama
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_4_9($module)
{
    Configuration::updateGlobalValue('AR_F_MIX_NAMES', 1);
    
    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'arlsf_session`
	ADD COLUMN `timestamp` INT UNSIGNED NOT NULL DEFAULT "0" AFTER `id_order`,
	ADD INDEX `timestamp` (`timestamp`);';
    
    Db::getInstance()->execute($sql);
    
    $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'arlsf_session` WHERE `timestamp` = 0';
    Db::getInstance()->execute($sql);
    
    return true;
}
