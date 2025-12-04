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
    protected function processChangeProductInCart()
    {
        if (!Module::isEnabled('minpurchase')) {
            return parent::processChangeProductInCart();
        }
        $mode = (Tools::getIsset('update') && $this->id_product) ? 'update' : 'add';
        include_once(_PS_MODULE_DIR_.'minpurchase/classes/MinpurchaseConfiguration.php');
        $objConfig = new MinpurchaseConfiguration();
        $conf = $objConfig->getConfigurations($this->id_product, $this->id_product_attribute);
        if (empty($conf) || $conf['separated'] || (int)$conf['grouped_by'] > 0) {
            return parent::processChangeProductInCart();
        }
        if ($this->qty == 0) {
            $this->errors[] = Tools::displayError('Null quantity.', !Tools::getValue('ajax'));
        } else if ($conf['minimum_quantity'] > $this->qty) {
        } elseif (!$this->id_product) {
            $this->errors[] = Tools::displayError('Product not found', !Tools::getValue('ajax'));
        }
        $product = new Product($this->id_product, true, $this->context->language->id);
        if (!$product->id || !$product->active || !$product->checkAccess($this->context->cart->id_customer)) {
            $this->errors[] = Tools::displayError('This product is no longer available.', !Tools::getValue('ajax'));
            return;
        }
        $qty_to_check = $this->qty;
        $cart_products = $this->context->cart->getProducts();
        $found = false;

        if (is_array($cart_products)) {
            foreach ($cart_products as $cart_product) {
                if ((!isset($this->id_product_attribute) || $cart_product['id_product_attribute'] == $this->id_product_attribute) &&
                    (isset($this->id_product) && $cart_product['id_product'] == $this->id_product)) {
                    $found = true;
                    $qty_to_check = $cart_product['cart_quantity'];

                    if ($conf['multiple']) {
                        $multipleValue = $conf['multiple_qty'];
                    }

                    if (Tools::getValue('op', 'up') == 'down') {
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
                    } else {
                        if ($conf['maximum_quantity'] > 0) {
                            if ($qty_to_check + $multipleValue > $conf['maximum_quantity']) {
                                $this->errors[] = Tools::displayError($mod->getMaxText($conf['maximum_quantity'], $product->name), !Tools::getValue('ajax'));
                            }
                        }
                        /* up quantity */
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
                $this->errors[] = Tools::displayError('There isn\'t enough product in stock.', !Tools::getValue('ajax'));
            }
        } elseif ($product->hasAttributes()) {
            $minimumQuantity = ($product->out_of_stock == 2) ? !Configuration::get('PS_ORDER_OUT_OF_STOCK') : !$product->out_of_stock;
            $this->id_product_attribute = Product::getDefaultAttribute($product->id, $minimumQuantity);
            if (!$this->id_product_attribute) {
                Tools::redirectAdmin($this->context->link->getProductLink($product));
            } elseif (!Product::isAvailableWhenOutOfStock($product->out_of_stock) && !Attribute::checkAttributeQty($this->id_product_attribute, $qty_to_check)) {
                $this->errors[] = Tools::displayError('There isn\'t enough product in stock.', !Tools::getValue('ajax'));
            }
        } elseif (!$product->checkQty($qty_to_check)) {
            $this->errors[] = Tools::displayError('There isn\'t enough product in stock.', !Tools::getValue('ajax'));
        }
        if (!$this->errors && $mode == 'add') {
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
                $this->errors[] = Tools::displayError('Please fill in all of the required fields, and then save your customizations.', !Tools::getValue('ajax'));
            }
            if (!$this->errors) {
                $cart_rules = $this->context->cart->getCartRules();
                $available_cart_rules = CartRule::getCustomerCartRules($this->context->language->id, (isset($this->context->customer->id) ? $this->context->customer->id : 0), true, true, true, $this->context->cart, false, true);
                $update_quantity = $this->context->cart->updateQty($this->qty, $this->id_product, $this->id_product_attribute, $this->customization_id, Tools::getValue('op', 'up'), $this->id_address_delivery);
                if ($update_quantity < 0) {
                    $minimal_quantity = $conf['minimum_quantity'];
                    $this->errors[] = sprintf(Tools::displayError('You must add %d minimum quantity', !Tools::getValue('ajax')), $minimal_quantity);
                } elseif (!$update_quantity) {
                    include_once(_PS_MODULE_DIR_.'minpurchase/minpurchase.php');
                    $mod = new Minpurchase();
                    //$this->errors[] = Tools::displayError('You already have the maximum quantity available for this product.', !Tools::getValue('ajax'));
                    $this->errors[] = Tools::displayError($mod->getMaxText($conf['maximum_quantity'], $product->name), !Tools::getValue('ajax'));
                } elseif ((int)Tools::getValue('allow_refresh')) {
                    $cart_rules2 = $this->context->cart->getCartRules();
                    if (count($cart_rules2) != count($cart_rules)) {
                        $this->ajax_refresh = true;
                    } elseif (count($cart_rules2)) {
                        $rule_list = array();
                        foreach ($cart_rules2 as $rule) {
                            $rule_list[] = $rule['id_cart_rule'];
                        }
                        foreach ($cart_rules as $rule) {
                            if (!in_array($rule['id_cart_rule'], $rule_list)) {
                                $this->ajax_refresh = true;
                                break;
                            }
                        }
                    } else {
                        $available_cart_rules2 = CartRule::getCustomerCartRules($this->context->language->id, (isset($this->context->customer->id) ? $this->context->customer->id : 0), true, true, true, $this->context->cart, false, true);
                        if (count($available_cart_rules2) != count($available_cart_rules)) {
                            $this->ajax_refresh = true;
                        } elseif (count($available_cart_rules2)) {
                            $rule_list = array();
                            foreach ($available_cart_rules2 as $rule) {
                                $rule_list[] = $rule['id_cart_rule'];
                            }
                            foreach ($cart_rules2 as $rule) {
                                if (!in_array($rule['id_cart_rule'], $rule_list)) {
                                    $this->ajax_refresh = true;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        $removed = CartRule::autoRemoveFromCart();
        CartRule::autoAddToCart();
        if (count($removed) && (int)Tools::getValue('allow_refresh')) {
            $this->ajax_refresh = true;
        }
    }
}