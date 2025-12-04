<?php
/**
* This is main class of module. 
* 
* @author    Globo Software Solution JSC <contact@globosoftware.net>
* @copyright 2017 Globo ., Jsc
* @license   please read license in file license.txt
* @link	     http://www.globosoftware.net
*/

if (!defined('_PS_VERSION_'))
    exit;

function upgrade_module_2_0_1($module){
    $module;
    Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'gabandoned_popup` ADD COLUMN `autocodeid_currency` int(10) NULL');
    Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'gabandoned_popup` ADD COLUMN `autocodetax` tinyint(1) NULL');
    Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'gabandoned_popup` ADD COLUMN `countdown` int(10) NULL');
    Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'gabandoned_popup` ADD COLUMN `reset_countdown` tinyint(1) NULL');
    return true;
}

?>