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

function upgrade_module_2_4_1($module)
{
    $res = Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'arlsf_visitor` (
            `id_visitor` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_product` INT(10) UNSIGNED NOT NULL,
            `key` VARCHAR(50) NOT NULL,
            `timestamp` INT(10) UNSIGNED NOT NULL,
            PRIMARY KEY (`id_visitor`),
            INDEX `id_product` (`id_product`),
            INDEX `ip` (`key`),
            INDEX `timestamp` (`timestamp`)
        )
        ENGINE=' . _MYSQL_ENGINE_ . ' COLLATE=utf8_general_ci;');
    
    $module->getVisitorConfigModel()->loadDefaults();
    $module->getVisitorConfigModel()->saveToConfig(false);
    ConfigurationCore::updateValue('AR_LSF_CLOSE_BTN_BG', '#000000');
    ConfigurationCore::updateValue('AR_LSF_CLOSE_BTN_STYLE', 'round');
    ConfigurationCore::updateValue('AR_LSF_CLOSE_BTN_POSITION', 'inside');
    return $res;
}
