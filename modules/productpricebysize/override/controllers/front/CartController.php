<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Musaffar Patel
 * @copyright 2016-2021 Musaffar Patel
 * @license   LICENSE.txt
 */

class CartController extends CartControllerCore
{
    public function init()
    {
        parent::init();
        if (Tools::getValue('action') === 'show') {
            $id_cart = Context::getContext()->cart->id;
            $id_shop = Context::getContext()->shop->id;
            include_once(_PS_MODULE_DIR_ . '/productpricebysize/lib/bootstrap.php');
            $oos_products = PPBSCartHelper::getCartOOSProducts($id_cart, $id_shop);

            if (!empty($oos_products)) {
                $product_names = array();
                foreach ($oos_products as $oos_product) {
                    $product_names[] = $oos_product['name'];
                }
                $product_names = array_unique($product_names);
                $product_names = implode(',', $product_names);

                $this->errors[] = $this->trans(
                    'The item %product% in your cart is no longer available in this quantity.',
                    array('%product%' => $product_names),
                    'Shop.Notifications.Error'
                );
            }
        }
    }

    /**
     * Create customization before adding to cart, so id_customization can be assigned to cart product
     */
    protected function processChangeProductInCart()
    {
        include_once(_PS_MODULE_DIR_ . '/productpricebysize/lib/bootstrap.php');
        $module = Module::getInstanceByName('productpricebysize');
        $id_product = Tools::getValue('id_product');
        $id_lang = Context::getContext()->language->id;
        $id_shop = Context::getContext()->shop->id;
        $mode = (Tools::getIsset('update') && $this->id_product) ? 'update' : 'add';
        $ErrorKey = ('update' === $mode) ? 'updateOperationError' : 'errors';
        $product = new Product($id_product, false, $id_lang);
        $ppbs_cart_controller = new PPBSFrontCartController($module);

        if (!$ppbs_cart_controller->processChangeProductInCartInStock()) {
            $this->{$ErrorKey}[] = $this->trans(
                'The item %product% in your cart is no longer available in this quantity.',
                array('%product%' => $product->name),
                'Shop.Notifications.Error'
            );
            return false;
        }
        if ($mode == 'add') {
            if (Module::isEnabled('productcustomoptions')) {
                include_once(_PS_MODULE_DIR_ . '/productcustomoptions/lib/bootstrap.php');
                if (\MP\PCO\ProductHelper::isPCOProduct($id_product, $id_shop)) {
                    $module_pco = Module::getInstanceByName('productcustomoptions');
                    $pco_cart_controller = new \MP\PCO\CartController($module_pco);
                    if (PPBSProductHelper::isPPBSEnabled($id_product)) {
                        $this->customization_id = $ppbs_cart_controller->processChangeProductInCartInStockAdd($mode, $this->customization_id);
                        $pco_cart_controller->addToCart($this->customization_id);
                    } else {
                        $this->customization_id = $pco_cart_controller->processChangeProductInCart($mode, $this->customization_id);                        
                    }
                } else {
                    $this->customization_id = $ppbs_cart_controller->processChangeProductInCartInStockAdd($mode, $this->customization_id);                    
                }
            } else {
                $this->customization_id = $ppbs_cart_controller->processChangeProductInCartInStockAdd($mode, $this->customization_id);
            }
        }

        if (version_compare(_PS_VERSION_, '1.7.7.0', '<')) {
            if ($mode == 'update') {
                $ppbs_cart_controller->processChangeProductInCartInStockUpdate($mode);
            }
        }
        parent::processChangeProductInCart();
    }

    /**
     * if no other PPBS customizations exist, then only Prestashop customization exists which needs to be deleted
     */
    protected function processDeleteProductInCart()
    {
        parent::processDeleteProductInCart();
    }
}
