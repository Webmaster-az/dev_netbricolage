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

function upgrade_module_1_2_0($module){
    $module;
    $module->unregisterHook('displayBackOfficeHeader');
    Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'gconditionandreminder` ADD COLUMN `maxcartamount` text NULL');
    $module->registerHook('actionAdminControllerSetMedia');
    $subtabs = array(
        array('class' => 'AdminGdashboard', 'name' => $module->l('Dashboard')),
        array('class' => 'AdminGsetting', 'name' => $module->l('Setting')),
    );
    $id_tabinvoices = Tab::getIdFromClassName("AdminParentOrders");
    foreach ($subtabs as $subtab) {
        $idtab = Tab::getIdFromClassName($subtab['class']);
        if (!$idtab) {
            $tab = new Tab();
            $tab->active = 0;
            $tab->class_name = $subtab['class'];
            $tab->name = array();
            foreach (Language::getLanguages(false) as $lang) {
                $tab->name[$lang["id_lang"]] = $subtab['name'];
            }
            $tab->id_parent = $id_tabinvoices;
            $tab->module = $module->name;
            $tab->add();
        }
    }
    return true;
}

?>