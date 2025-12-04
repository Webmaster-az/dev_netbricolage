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

use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;

class PPBSFrontCartController extends Module
{
    protected $sibling;

    public function __construct(&$sibling)
    {
        parent::__construct();

        if ($sibling !== null) {
            $this->sibling = &$sibling;
        }
    }

    public function setMedia()
    {
        if (Context::getContext()->controller->php_self == 'cart') {
            $this->sibling->context->controller->addJS($this->sibling->_path . 'views/js/front/PPBSFrontCartController.js');
        }
    }


    /**
     * Calculate the price of the product in the cart
     * @param $params
     * @param array $customization_data
     * @return float
     */
    public function priceCalculation($params, $customization_data = array())
    {
        if (empty(Context::getContext()->customer)) {
            return $params['price'];
        }

        $id_customer = Context::getContext()->customer->id;
        $customer = new Customer($id_customer);
        if (!empty($customization_data)) {
            $customization_data = $customization_data;
        } else {
            $customization_data = PPBSProductHelper::getCartProductUnits(
                $params['id_product'],
                $params['id_cart'],
                $params['id_product_attribute'],
                $params['id_shop'],
                $params['id_customization']
            );
        }
        return PPBSProductHelper::formatPrice(PPBSCartHelper::calculateCustomizationPrice($customization_data, $params, $customer, false, true));
    }

    /**
     * @param $id_product
     * @param $id_shop
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function isPPBSProduct($id_product, $id_shop)
    {
        $ppbs_product = new PPBSProduct();
        $ppbs_product->getByProduct($id_product);

        if (empty($ppbs_product->id) || $ppbs_product->enabled == 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Add PPBS data to the cart
     * @param $id_customization
     * @return bool|int
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function addToCart($id_customization)
    {
        $id_shop = Context::getContext()->shop->id;

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

        if (!isset($this->context->cart->id)) {
            return false;
        }

        $id_cart = $this->context->cart->id;
        $id_product = Tools::getValue('id_product');

        if (Tools::getValue('group') != '') {
            $id_product_attribute = Product::getIdProductAttributeByIdAttributes(Tools::getValue('id_product'), Tools::getValue('group'));
        } else {
            $id_product_attribute = 0;
        }

        if ((int)$id_product_attribute == 0 && (int)Tools::getValue('id_product_attribute') > 0) {
            $id_product_attribute = Tools::getValue('id_product_attribute');
        }

        // This can happen when a product is being added from a module like the homepage carousel
        if ((int)$id_product_attribute == 0) {
            $id_product_attribute = Product::getDefaultAttribute($id_product);
        }

        $quantity = (int)Tools::getValue('qty');
        if ($quantity == 0) {
            $quantity = 1;
        }

        $cart_unit_collection = array();
        $ppbs_dimensions = PPBSDimension::getDimensions($this->context->language->id);
        $ppbs_product_field_collection = PPBSProductField::getCollectionByProduct(Tools::getValue('id_product'), Context::getContext()->language->id, true);

        if (is_array($ppbs_product_field_collection)) {
            foreach ($ppbs_product_field_collection as $field) {
                $ppbs_product_field = new PPBSProductField();
                $ppbs_product_field->loadProductField(Tools::getValue('id_product'), $field['id_ppbs_dimension'], Context::getContext()->shop->id);
                $ppbs_unit = new PPBSUnit($ppbs_product_field->id_ppbs_unit, $this->context->language->id);

                $value = PPBSCartHelper::formatToFloat(Tools::getValue('ppbs_field-' . $field['id_ppbs_product_field'].'-default-unit'));

                if ($value <= 0) {
                    $value = $field['min'];
                }

                $cart_unit = new stdClass();
                $cart_unit->id_ppbs_dimension = $field['id_ppbs_dimension'];
                $cart_unit->display_name = $field['display_name'];
                $cart_unit->value = $value;

                if ($field['display_suffix'] == 1) {
                    $cart_unit->symbol = $ppbs_unit->symbol;
                } else {
                    $cart_unit->symbol = '';
                }

                if ($ppbs_product_field->input_type == 'dropdown') {
                    $dd_option_value = Tools::getValue('ppbs_field-' . $field['id_ppbs_product_field'].'_dd');

                    if (strpos($dd_option_value, ':') > 0) {
                        $dd_option_value = explode(':', $dd_option_value);
                        $cart_unit->value = $dd_option_value[0];
                        $cart_unit->display_value = $dd_option_value[1];
                    }
                }
                $cart_unit_collection[] = $cart_unit;
            }
        }

        if (version_compare(_PS_VERSION_, '1.7.7.0', '<')) {
            if ($id_customization == 0) {
                $id_customization = PPBSCartHelper::addCustomization($id_product, $id_cart, $id_product_attribute, Context::getContext()->cart->id_address_delivery, $cart_unit_collection, $this->sibling->id, $quantity, $this->context->shop->id);
            } else {
                $display_text = PPBSCartHelper::getCustomizationDisplayText($cart_unit_collection);
                PPBSCartHelper::addCustomizedData($id_customization, 0, $display_text, $this->sibling->id, $cart_unit_collection);
            }
        }

        if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
            //$quantity = 0;  // Prestashop will apply the customization quantity after add to cart
            if ($id_customization > 0) {
                $id_customization_field = PPBSCartHelper::getCustomizationField($id_product, $id_shop);
                $display_text = PPBSCartHelper::getCustomizationDisplayText($cart_unit_collection);
                PPBSCartHelper::addCustomizedData($id_customization, $id_customization_field, $display_text, $this->sibling->id, $cart_unit_collection);
            } else {
                $id_customization = PPBSCartHelper::addCustomization($id_product, $id_cart, $id_product_attribute, Context::getContext()->cart->id_address_delivery, $cart_unit_collection, $this->sibling->id, $quantity, $this->context->shop->id);
            }
        }
        return $id_customization;
    }


    /**
     * Add script initialisation vars for the PPBS widgt which will be loaded via ajax
     * @param $params
     * @return bool
     */
    public function hookDisplayFooter($params)
    {
        if (Context::getContext()->controller->php_self != 'cart') {
            return false;
        }

        $link = new \Link();
        $this->sibling->smarty->assign(array(
            'baseDir' => __PS_BASE_URI__,
            'module_ajax_url' => $link->getModuleLink('productpricebysize', 'ajax', array()),
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/front/cart_footer.tpl');
    }

    /**
     * Merge Module customizations with product customizations for presentation in the cart
     * @param $params
     * @return string
     */
    public function hookDisplayCustomization($params)
    {
        return $params['customization']['value'];
    }

    /**
     * Called by overrides/cartcontroller.php to check the stock level
     * @return bool
     */
    public function processChangeProductInCartInStock()
    {
        $id_cart = Context::getContext()->cart->id;
        $id_product = Tools::getValue('id_product');
        $id_product_attribute = Tools::getValue('id_product_attribute');
        $id_customization = Tools::getValue('id_customization');
        $id_shop = Context::getContext()->shop->id;
        $op = Tools::getValue('op');
        $stock_error = false;
        $mode = (Tools::getIsset('update') && $id_product) ? 'update' : 'add';

        if ($mode == 'update' && (int)Tools::getValue('id_customization') > 0) {
            if (PPBSProductHelper::isStockEnabled(Tools::getValue('id_product'))) {
                $quantity = PPBSCartHelper::getProductQty($id_product, $id_product_attribute, $id_customization, $id_cart);
                if ($op == 'up') {
                    $quantity++;
                }

                if ($op == 'down') {
                    $quantity--;
                    if ($quantity < 0) {
                        $quantity = 0;
                    }
                }

                if (Tools::getIsset('qty')) {
                    $quantity = Tools::getValue('qty');
                }

                $qty_stock = PPBSStockHelper::getStock($id_product, $id_product_attribute, $id_shop);
                $product_unit_area = PPBSCartHelper::getProductTotalArea($id_product, $id_product_attribute, $id_customization, $id_cart, $id_shop);
                if (($product_unit_area * $quantity) > $qty_stock) {
                    $stock_error = true;
                }
            }
        }
        return !$stock_error;
    }

    /**
     * Called by overrides/cartcontroller.php when products is added
     * @param $mode
     * @param $id_customization
     * @return bool|int
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function processChangeProductInCartInStockAdd($mode, $id_customization)
    {
        $id_product = Tools::getValue('id_product');
        $id_shop = Context::getContext()->shop->id;
        if ($mode == 'add') {
            $ppbs_front_cart_controller = new PPBSFrontCartController($this->sibling);
            if ($ppbs_front_cart_controller->isPPBSProduct($id_product, $id_shop)) {
                return $ppbs_front_cart_controller->addToCart($id_customization);
            }
        }
    }

    /**
     * Called by overrides/cartcontroller.php when products is added
     * @param $mode
     * @param $id_customization
     * @return bool|int
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function processChangeProductInCartInStockUpdate($mode)
    {
        $id_product = Tools::getValue('id_product');
        $id_product_attribute = Tools::getValue('id_product_attribute');
        $id_customization = Tools::getValue('id_customization');
        $id_cart = Context::getContext()->cart->id;
        $op = Tools::getValue('op');

        if ($mode == 'update') {
            $ppbs_only_customized = PPBSCartHelper::hasPPBSOnlyCustomizedData($id_customization, $id_product, $id_product_attribute, $id_cart);
            if ($ppbs_only_customized == true) {
                if ($op == 'up') {
                    PPBSCartHelper::incrementProductCustomizationQuantity($id_product, $id_product_attribute, $id_customization, $id_cart);
                }
                if ($op == 'down') {
                    PPBSCartHelper::decrementProductCustomizationQuantity($id_product, $id_product_attribute, $id_customization, $id_cart);
                }
            }
        }
    }


    public function route()
    {
        switch (Tools::getValue('action')) {
            case 'deletecustomization':
                die($this->deleteCustomization());
        }
    }
}
