<?php
/**
 * Multi Accessories Pro | An abstract controller for front end
 *
 * @author    PrestaMonster
 * @copyright PrestaMonster
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class AccessoriesAbstract extends ModuleFrontController
{
    public $errors = array();

    /**
     * Get accessories of list products.
     */
    public function displayAjaxRenderAccessories()
    {
        $params = array();
        $params['id_products'] = Tools::getValue('id_products');
        $params['id_shop'] = $this->context->shop->id;
        echo $this->module->renderAccessories($params);
    }

    /**
     * Get accessories of list products.
     */
    public function displayAjaxIsStockAvailable()
    {
        $success = array(
            'hasError' => false,
            'errors' => $this->errors
        );
        $id_accessory = (int) Tools::getValue('id_accessory');
        $id_product_attribute = (int) Tools::getValue('id_accessory_combination', 0);
        $qty_to_check = (int) Tools::getValue('new_quantity', 0);
        $product = new Product($id_accessory, true, $this->context->language->id);
        $cart_products = $this->context->cart->getProducts();

        if (is_array($cart_products)) {
            foreach ($cart_products as $cart_product) {
                if ((!isset($id_product_attribute) || $cart_product['id_product_attribute'] == $id_product_attribute) &&
                        (isset($id_accessory) && $cart_product['id_product'] == $id_accessory)) {
                    $qty_to_check += $cart_product['cart_quantity'];
                    break;
                }
            }
        }
        // Check product quantity availability
        if ($id_product_attribute) {
            if (!Product::isAvailableWhenOutOfStock($product->out_of_stock) && !Attribute::checkAttributeQty($id_product_attribute, $qty_to_check)) {
                $this->errors[] = Tools::displayError($this->module->i18n['there_isnt_enough_product_in_stock'], !Tools::getValue('ajax'));
            }
        } elseif ($product->hasAttributes()) {
            $minimumQuantity = ($product->out_of_stock == 2) ? !Configuration::get('PS_ORDER_OUT_OF_STOCK') : !$product->out_of_stock;
            $id_product_attribute = Product::getDefaultAttribute($product->id, $minimumQuantity);
            if (!$id_product_attribute) {
                Tools::redirectAdmin($this->context->link->getProductLink($product));
            } elseif (!Product::isAvailableWhenOutOfStock($product->out_of_stock) && !Attribute::checkAttributeQty($id_product_attribute, $qty_to_check)) {
                $this->errors[] = Tools::displayError($this->module->i18n['there_isnt_enough_product_in_stock'], !Tools::getValue('ajax'));
            }
        } elseif (!$product->checkQty($qty_to_check)) {
            $this->errors[] = Tools::displayError($this->module->i18n['there_isnt_enough_product_in_stock'], !Tools::getValue('ajax'));
        }

        if ($this->errors) {
            $success = array(
                'hasError' => true,
                'errors' => $this->errors
            );
        }
        $this->ajaxDie(Tools::jsonEncode($success));
    }
    
    /**
     * Get accessories of list products.
     */
    public function displayAjaxGetProductCombination()
    {
        $params = array();
        $params['id_products'] = Tools::getValue('id_products');
        $params['id_shop'] = $this->context->shop->id;
        echo $this->module->renderAccessories($params);
    }
}
