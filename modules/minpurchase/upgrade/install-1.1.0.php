<?php
/**
* Minimum and maximum unit quantity to purchase
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate
*  @copyright 2023 idnovate
*  @license   See above
*/

function upgrade_module_1_1_0($module)
{
	Db::getInstance()->execute(
        'ALTER TABLE `'._DB_PREFIX_.'minpurchase_configuration`
        	ADD `minimum_amount` decimal(10,2) NULL DEFAULT "0.000",
            ADD `maximum_amount` decimal(10,2) NULL DEFAULT "0.000",
            ADD `schedule` TEXT NULL,
            ADD `grouped_by` tinyint(1) unsigned NULL;'
    );

    if (version_compare(_PS_VERSION_, '1.7', '>=')) {
        $module->registerHook('displayProductAdditionalInfo');
        $module->registerHook('footer');
    }

    $module->copyOverrideFolder();

    $module->removeOverride('OrderController');
    $module->addOverride('OrderController');
    $module->removeOverride('Cart');
    $module->addOverride('Cart');
    $module->removeOverride('CartController');
    $module->addOverride('CartController');

    return true;
}
