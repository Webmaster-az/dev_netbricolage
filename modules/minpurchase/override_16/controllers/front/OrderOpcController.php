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
class OrderOpcController extends OrderOpcControllerCore
{
    public function init()
    {
        parent::init();

        if (Module::isEnabled('minpurchase')) {
            include_once(_PS_MODULE_DIR_.'minpurchase/minpurchase.php');
            $mod = new MinpurchaseConfiguration();
            $errors = array();
            if ($cart = Context::getContext()->cart) {
                $errors = $mod->checkProductsAvailability($this->context->cart->getProducts());
            }
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->errors[] = $error;
                }
            }
        }
    }

    protected function _getPaymentMethods()
    {
        if (Module::isEnabled('minpurchase')) {
            include_once(_PS_MODULE_DIR_.'minpurchase/minpurchase.php');
            $mod = new MinpurchaseConfiguration();
            $errors = array();
            if ($cart = Context::getContext()->cart) {
                $errors = $mod->checkProductsAvailability($this->context->cart->getProducts());
            }
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    return '<p class="warning">'.sprintf(Tools::displayError('Not possible to continue with the payment: %1s'), $error).'</p>';
                }
            }
        }
        return parent::_getPaymentMethods();
    }
}