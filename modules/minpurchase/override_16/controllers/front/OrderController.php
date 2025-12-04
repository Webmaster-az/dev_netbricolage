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

class OrderController extends OrderControllerCore
{
    public function init()
    {
        if (!Module::isEnabled('minpurchase')) {
            return parent::init();
        }

        include_once(_PS_MODULE_DIR_.'minpurchase/minpurchase.php');
        $mod = new MinpurchaseConfiguration();
        $errors = array();
        if ($cart = Context::getContext()->cart) {
            $errors = $mod->checkProductsAvailability($cart->getProducts());
        } else {
            $cart = new Cart(Context::getContext()->cookie->id_cart);
            $errors = $mod->checkProductsAvailability($cart->getProducts());
        }
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $this->errors[] = $error;
            }
        }
        if (!empty($this->errors)) {
            if (Tools::getValue('step')) {
                $params = array('action' => 'show');
                Tools::redirect($this->context->link->getPageLink('order', true, (int)Context::getContext()->language->id, $params));
            }
        }
        return parent::init();
    }
}