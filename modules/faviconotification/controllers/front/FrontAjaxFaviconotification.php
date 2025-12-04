<?php
/**
* 2007-2018 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2018 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

class FaviconotificationFrontAjaxFaviconotificationModuleFrontController extends ModuleFrontController
{
    //Redirect to home if trying to access to the front controller without ajax call
    public function initContent()
    {
        if (Tools::getValue('ajax') != true) {
            parent::initContent();
            Tools::redirect('index.php?fc=PageNotFound');
        }
    }

    /**
     * get quantity product in the cart
     */
    public static function displayAjaxUpdateCartQuantity()
    {
        $nbProductCart = 0;
        $context = Context::getContext();
        $nbProductCart = (int)Cart::getNbProducts($context->cart->id);

        die(json_encode($nbProductCart));
    }
}
