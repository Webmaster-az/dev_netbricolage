<?php
/**
* Minimum and maximum quantity purchase
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

class Product extends ProductCore
{
    public static function getProductProperties($id_lang, $row, Context $context = null)
    {
        if ($context == null) {
            $context = Context::getContext();
        }
        $row = parent::getProductProperties($id_lang, $row, $context);

        if (Module::isEnabled('minpurchase')) {
            if (!empty($row)) {
                include_once(_PS_MODULE_DIR_.'minpurchase/classes/MinpurchaseConfiguration.php');
                $row = MinpurchaseConfiguration::setProductProperties($row);
            }
        }
        return $row;
    }
}