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

class CartController extends CartControllerCore
{

    public function init()
    {
        parent::init();
        if (Module::isEnabled('minpurchase')) {
            if (empty($_POST)) {
                include_once(_PS_MODULE_DIR_.'minpurchase/minpurchase.php');
                $mod = new MinpurchaseConfiguration();
                $errors = $mod->checkProductsAvailability($this->context->cart->getProducts());
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        $this->errors[] = $error;
                    }
                }
                if (!empty($this->errors)) {
                    $params = array('action' => 'show');
                    $this->canonicalRedirection($this->context->link->getPageLink('cart', true, (int)Context::getContext()->language->id, $params));
                }
            }
        }
    }

    protected function processChangeProductInCart()
    {
        if (!Module::isEnabled('minpurchase')) {
            return parent::processChangeProductInCart();
        }
        include_once(_PS_MODULE_DIR_.'minpurchase/classes/MinpurchaseConfiguration.php');
        $objConfig = new MinpurchaseConfiguration();
        $conf = $objConfig->getConfigurations($this->id_product, $this->id_product_attribute);
        if (empty($conf) || $conf['separated'] || (int)$conf['grouped_by'] > 0) {
            return parent::processChangeProductInCart();
        }
        $mode = (Tools::getIsset('update') && $this->id_product) ? 'update' : 'add';
        if (Tools::getIsset('group')) {
            $this->id_product_attribute = (int)Product::getIdProductAttributesByIdAttributes($this->id_product, Tools::getValue('group'));
        }
        if ($this->qty == 0) {
            $this->errors[] = $this->trans('Null quantity.', array(), 'Shop.Notifications.Error');
        } elseif (!$this->id_product) {
            $this->errors[] = $this->trans('Product not found', array(), 'Shop.Notifications.Error');
        }
        $product = new Product($this->id_product, true, $this->context->language->id);
        if (!$product->id || !$product->active || !$product->checkAccess($this->context->cart->id_customer)) {
            $this->errors[] = $this->trans('This product is no longer available.', array(), 'Shop.Notifications.Error');
            return;
        }
        if (!$this->id_product_attribute && $product->hasAttributes()) {
            $minimum_quantity = ($product->out_of_stock == 2) ? !Configuration::get('PS_ORDER_OUT_OF_STOCK') : !$product->out_of_stock;
            $this->id_product_attribute = Product::getDefaultAttribute($product->id, $minimum_quantity);
            if (!$this->id_product_attribute) {
                Tools::redirectAdmin($this->context->link->getProductLink($product));
            }
        }
        $qty_to_check = $this->qty;
        $cart_products = $this->context->cart->getProducts();
        $multipleValue = 1;
        $found = false;
        if (is_array($cart_products)) {
            foreach ($cart_products as $cart_product) {
                if ($this->productInCartMatchesCriteria($cart_product)) {
                    $found = true;
                    $qty_to_check = $cart_product['cart_quantity'];
                    if ($conf['multiple']) {
                        $multipleValue = $conf['multiple_qty'];
                    }
                    if (Tools::getValue('op', 'up') == 'down') {
                        if ($this->qty <= $conf['minimum_quantity']) {
                            $this->qty = $conf['minimum_quantity'];
                        } else {
                            $provisionalQty = (int)$qty_to_check - (int)$this->qty + 1;
                            if ($qty_to_check <= $conf['minimum_quantity']) {
                                $this->qty = 1;
                                $qty_to_check = 0;
                            } else {
                                if ($conf['increment'] && $conf['increment_qty'] > 0 && $this->qty == 1) {
                                    $this->qty = $conf['increment_qty'];
                                }
                                if ($provisionalQty <= $conf['minimum_quantity']) {
                                    $this->qty = $conf['minimum_quantity'];
                                } else {
                                    if ($multipleValue > 1) {
                                        $this->qty = $qty_to_check - $objConfig->previousMultiple($provisionalQty, $multipleValue, $conf['minimum_quantity']);
                                    }
                                }
                            }
                        }
                    } else {
                        if ($conf['maximum_quantity'] > 0) {
                            if ($qty_to_check + $multipleValue > $conf['maximum_quantity']) {
                                $this->errors[] = $this->trans('You already have the maximum quantity available for this product.', array(), 'Shop.Notifications.Error') .' '.$this->trans('Quantity', array(), 'Admin.Global').' = '.$conf['maximum_quantity'];
                            }
                        }                        
                        $provisionalQty = (int)$qty_to_check + (int)$this->qty;
                        if ((int)$conf['maximum_quantity'] > 0 && $provisionalQty >= $conf['maximum_quantity']) {
                            $qty_to_check = (int)$conf['maximum_quantity'] - (int)$this->qty;
                            $this->qty = (int)$conf['maximum_quantity'] - (int)$cart_product['cart_quantity'];
                            if ($this->qty <= 0) {
                                $this->qty = 1;
                            }
                        } else {
                            if ($conf['increment'] && $conf['increment_qty'] > 0) {
                                if ($provisionalQty % $conf['increment_qty'] != 0) {
                                    $this->qty = $objConfig->nextIncrement($qty_to_check + $this->qty, $conf['increment_qty'], $conf['minimum_quantity']) - $qty_to_check;
                                }
                            }
                            if ($multipleValue > 1) {
                                if ($provisionalQty % $multipleValue != 0) {
                                    $this->qty = $objConfig->nextMultiple($provisionalQty, $multipleValue) - $qty_to_check;
                                }
                            }
                        }
                    }
                    if (Tools::getValue('op', 'up') == 'down') {
                        $qty_to_check -= $this->qty;
                    } else {
                        $qty_to_check += $this->qty;
                    }
                    break;
                } else {
                    if ($conf['maximum_quantity'] > 0 && $conf['maximum_quantity'] < $this->qty) {
                        $this->qty = (int)$conf['maximum_quantity'];
                    }
                }
            }
        }
        if ($qty_to_check < 0) {
            $this->errors[] = $this->trans('There are the minimum products', array(), 'Shop.Notifications.Error');
        }
        if ($conf['maximum_quantity'] > 0) {
            $maximum_quantity = $conf['maximum_quantity'];
        }

        if (empty($cart_products)) {
            if ($conf['maximum_quantity'] > 0 && $conf['maximum_quantity'] < $this->qty) {
                $this->qty = (int)$conf['maximum_quantity'];
            }
            if ($conf['minimum_quantity'] > 0 && $conf['minimum_quantity'] > $this->qty) {
                $this->qty = (int)$conf['minimum_quantity'];
            }
        }
        if ($this->id_product_attribute) {
            if (!Product::isAvailableWhenOutOfStock($product->out_of_stock) && !Attribute::checkAttributeQty($this->id_product_attribute, $qty_to_check)) {
                $this->errors[] = $this->trans('There are not enough products in stock', array(), 'Shop.Notifications.Error');
            }
        } elseif (!$product->checkQty($qty_to_check)) {
            $this->errors[] = $this->trans('There are not enough products in stock', array(), 'Shop.Notifications.Error');
        }
        if (!$this->errors) {
            if (!$this->context->cart->id) {
                if (Context::getContext()->cookie->id_guest) {
                    $guest = new Guest(Context::getContext()->cookie->id_guest);
                    $this->context->cart->mobile_theme = $guest->mobile_theme;
                }
                $this->context->cart->add();
                if ($this->context->cart->id) {
                    $this->context->cookie->id_cart = (int)$this->context->cart->id;
                }
            }
            if (!$product->hasAllRequiredCustomizableFields() && !$this->customization_id) {
                $this->errors[] = $this->trans('Please fill in all of the required fields, and then save your customizations.', array(), 'Shop.Notifications.Error');
            }
            if (!$this->errors) {
                $cart_rules = $this->context->cart->getCartRules();
                $available_cart_rules = CartRule::getCustomerCartRules($this->context->language->id, (isset($this->context->customer->id) ? $this->context->customer->id : 0), true, true, true, $this->context->cart, false, true);
                $update_quantity = $this->context->cart->updateQty($this->qty, $this->id_product, $this->id_product_attribute, $this->customization_id, Tools::getValue('op', 'up'), $this->id_address_delivery);
                if ($update_quantity < 0) {
                    $minimal_quantity = ($this->id_product_attribute) ? Attribute::getAttributeMinimalQty($this->id_product_attribute) : $product->minimal_quantity;
                    $this->errors[] = $this->trans('You must add %d minimum quantity', array($minimal_quantity), 'Shop.Notifications.Error');
                } elseif (!$update_quantity) {
                    $this->errors[] = $this->trans('You already have the maximum quantity available for this product.', array(), 'Shop.Notifications.Error') .' '.$this->trans('Quantity', array(), 'Admin.Global').' = '.$maximum_quantity;
                }
            }
        }
        $removed = CartRule::autoRemoveFromCart();
        CartRule::autoAddToCart();
    }
}