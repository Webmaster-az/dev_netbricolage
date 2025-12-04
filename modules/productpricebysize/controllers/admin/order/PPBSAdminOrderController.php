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

class PPBSAdminOrderController extends PPBSControllerCore
{
    protected $sibling;

    public function __construct(&$sibling, $params = array())
    {
        parent::__construct($sibling, $params);
        $this->sibling = $sibling;
    }

    /**
     * Set Media
     */
    public function setMedia()
    {
        if (Tools::getValue('controller') != 'AdminOrders' || (int)Tools::getValue('id_order') == 0) {
            return false;
        }

        Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/lib/Tools.js');
        Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/lib/Popup.js');
        Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/order/PPBSAdminOrderController.js');
    }

    public function hookDisplayCustomization($params)
    {
        return false;
        $id_product = $params['customization']['id_product'];
        $id_product_attribute = $params['customization']['id_product_attribute'];
        $id_customization = $params['customization']['id_customization'];
        $id_address_delivery = $params['customization']['id_address_delivery'];
        $id_order = Tools::getValue('id_order');

        if (Tools::getValue('controller') != 'AdminOrders' || (int)Tools::getValue('id_order') == 0) {
            return false;
        }

        $order_product = PPBSOrderHelper::getOrderProduct($id_order, $id_product, $id_product_attribute, $id_customization);

        if (empty($order_product['id_order_detail'])) {
            return false;
        }

        Context::getContext()->smarty->assign(array(
            'id_product' => $id_product,
            'id_product_attribute' => $id_product_attribute,
            'id_customization' => $id_customization,
            'id_address_delivery' => $id_address_delivery,
            'id_order' => $id_order,
            'quantity' => $order_product['product_quantity']
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/order/customization.tpl');
    }

    /**
     * Displayed at the top of admin pages
     * @param $params
     */
    public function hookDisplayBackOfficeTop($params)
    {
        if (Tools::getValue('controller') != 'AdminOrders' || (int)Tools::getValue('id_order') == 0) {
            return false;
        }

        Context::getContext()->smarty->assign(array(
            'module_ajax_url' => $this->module_ajax_url,
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/order/backofficetop.tpl');
    }

    /**
     * render the edit customization panel
     */
    public function renderEditCustomization()
    {
        $id_product = Tools::getValue('id_product');
        $id_product_attribute = Tools::getValue('id_product_attribute');
        $id_customization = Tools::getValue('id_customization');
        $id_address_delivery = Tools::getValue('id_address_delivery');
        $id_order = (int)Tools::getValue('id_order');
        $order = new Order($id_order);

        $customization_data = PPBSProductHelper::getCartProductUnits(
            $id_product,
            $order->id_cart,
            $id_product_attribute,
            $order->id_shop,
            $id_customization
        );

        if (empty($customization_data)) {
            return false;
        }

        $order_product = PPBSOrderHelper::getOrderProduct($id_order, $id_product, $id_product_attribute, $id_customization);
        $fields = json_decode($customization_data[0]['ppbs_dimensions']);

        Context::getContext()->smarty->assign(array(
            'module_ajax_url' => $this->module_ajax_url,
            'id_product' => $id_product,
            'id_product_attribute' => $id_product_attribute,
            'id_customization' => $id_customization,
            'id_address_delivery' => $id_address_delivery,
            'id_order' => $id_order,
            'quantity' => $order_product['product_quantity'],
            'fields' => $fields
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/order/customization_edit.tpl');
    }

    /**
     * Calculate the price
     */
    public function processGetPrice()
    {
        $customization_data = array();
        $params = array();

        $customization_data[] = array(
            'ppbs_dimensions' => json_encode(Tools::getValue('fields')),
            'quantity' => 1
        );

        $order = new Order(Tools::getValue('id_order'));
        $address = new Address($order->id_address_delivery);
        $customer = new Customer($order->id_customer);

        $params['price'] = 0;
        $params['quantity'] = 1;
        $params['id_product'] = Tools::getValue('id_product');
        $params['id_product_attribute'] = Tools::getValue('id_product_attribute');
        $params['id_cart'] = $order->id_cart;
        $params['id_shop'] = $order->id_shop;
        $params['specific_price'] = array();
        $params['id_country'] = $address->id_country;
        $params['id_state'] = $address->id_state;
        $params['zipcode'] = $address->postcode;
        $params['use_tax'] = 0;
        $params['id_customization'] = Tools::getValue('id_customization');

        $price_ex_tax = PPBSCartHelper::calculateCustomizationPrice($customization_data, $params, $customer, true, false);
        $params['use_tax'] = 1;
        $price_inc_tax = PPBSCartHelper::calculateCustomizationPrice($customization_data, $params, $customer, true, false);
        $price_ex_tax = PPBSProductHelper::formatPriceAsNumber($price_ex_tax);
        $price_inc_tax = PPBSProductHelper::formatPriceAsNumber($price_inc_tax);

        $price = array(
            'price_ex_tax' => $price_ex_tax,
            'price_inc_tax' => $price_inc_tax
        );
        return json_encode($price);
    }

    /**
     * Process the edit of the customization
     */
    public function processEditCustomization()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_product_attribute = (int)Tools::getValue('id_product_attribute');
        $id_customization = (int)Tools::getValue('id_customization');
        $id_address_delivery = (int)Tools::getValue('id_address_delivery');
        $id_order = (int)Tools::getValue('id_order');
        $fields = json_decode(json_encode(Tools::getValue('fields')));
        $unit_product_price_excl = (float)PPBSProductHelper::parseAsPrice(Tools::getValue('unit_product_price_excl'));
        $product = new Product($id_product, true);
        $customization_data = array();

        $product_info = array(
            'id_product' => $id_product,
            'id_product_attribute' => $id_product_attribute,
            'id_customization' => $id_customization,
            'weight' => $product->weight,
            'weight_attribute' => PPBSProductHelper::getProductAttributeWeight($id_product, $id_product_attribute),
        );

        $order = new Order($id_order);
        $cart = new Cart($order->id_cart);
        $order_product = PPBSOrderHelper::getOrderProduct($order->id, $id_product, $id_product_attribute, $id_customization);

        $display_text = PPBSCartHelper::getCustomizationDisplayText($fields);
        PPBSCartHelper::updateCustomizedData($id_customization, $display_text, $fields);

        $order->total_products = (float)$cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, null, $order->id_carrier);
        $order->total_products_wt = (float)$cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, null, $order->id_carrier);
        $order->total_discounts_tax_excl = (float)abs($cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS, null, $order->id_carrier));
        $order->total_discounts_tax_incl = (float)abs($cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, null, $order->id_carrier));
        $order->total_discounts = $order->total_discounts_tax_incl;
        $order->total_paid_tax_excl = (float)Tools::ps_round((float)$cart->getOrderTotal(false, Cart::BOTH, null, $order->id_carrier), PPBSToolsHelper::getPricePrecision());
        $order->total_paid_tax_incl = (float)Tools::ps_round((float)$cart->getOrderTotal(true, Cart::BOTH, null, $order->id_carrier), PPBSToolsHelper::getPricePrecision());
        $order->total_paid = $order->total_paid_tax_incl;

        $customization_data[] = array(
            'ppbs_dimensions' => json_encode(Tools::getValue('fields')),
            'quantity' => 1
        );

        $address = new Address($order->id_address_delivery);

        $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$id_product, null));
        $product_tax_calculator = $tax_manager->getTaxCalculator();
        $unit_product_price_incl = PPBSCartHelper::_addTax($product_tax_calculator, true, $unit_product_price_excl);

        $order_detail = new OrderDetail($order_product['id_order_detail']);
        $order_detail->unit_price_tax_excl = $unit_product_price_excl;
        $order_detail->unit_price_tax_incl = $unit_product_price_incl;
        $order_detail->total_price_tax_excl = $unit_product_price_excl * $order_product['product_quantity'];
        $order_detail->total_price_tax_incl = $unit_product_price_incl * $order_product['product_quantity'];
        $order_detail->product_weight = PPBSCartHelper::getOrderDetailProductWeight($product_info, $order->id_cart);

        $order_detail->save();
        $order->save();

        // update weight
        PPBSOrderHelper::updateOrderCarrierWeight($order->id, $order->getTotalWeight());

        // Update shipping cost
        $shipping_cost_tax_excl = $cart->getPackageShippingCost($order->id_carrier, false, null, $cart->getProducts());
        $shipping_cost_tax_incl = $cart->getPackageShippingCost($order->id_carrier, true, null, $cart->getProducts());
        PPBSOrderHelper::updateOrderCarrierCost($order->id, $order->id_carrier, $shipping_cost_tax_excl, $shipping_cost_tax_incl);
    }

    public function route()
    {
        switch (Tools::getValue('action')) {
            case 'rendereditcustomization':
                die($this->renderEditCustomization());

            case 'processgetprice':
                die($this->processGetPrice());

            case 'processeditcustomization':
                die($this->processEditCustomization());
        }
    }
}
