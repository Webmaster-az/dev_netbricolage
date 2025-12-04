<?php

use Mypos\IPC\Purchase;

class MyposVirtualPaymentModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $cart = $this->context->cart;

        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active) {
            Tools::redirect('index.php?controller=order&step=1');
        }

        $currency = $this->context->currency;

        if (!Validate::isLoadedObject($currency)) {
            return [];
        }

        $customerObject = $this->context->customer;

        if ($customerObject->id != 0) {
            require_once __DIR__ . '/../../sdk/Loader.php';

            $cartObject = $this->context->cart;
            $billing_address = new Address((int)$this->context->cart->id_address_invoice);
            $country = new Country((int)$billing_address->id_country);
            $isoCodes = require __DIR__ . '/../../countries.php';
            $isoCode = $isoCodes[$country->iso_code ? $country->iso_code : 'BG'];
            $config = $this->module->getMyposVirtualConfig();
            $customer = $this->module->getMyposVirtualCustomer($customerObject, $billing_address, $isoCode);
            $cart = $this->module->getMyposVirtualCart($cartObject);
            $purchase = $this->module->getMyposVirtualPurchase($config, $cartObject, $currency->iso_code, $customer, $cart);

            if (in_array($purchase->getPaymentMethod(), [Purchase::PAYMENT_METHOD_IDEAL, Purchase::PAYMENT_METHOD_BOTH])) {
                $cartTotal = $cartObject->getOrderTotal(true);
                $awaiting_state = new OrderState((int)Configuration::get('awaiting_mypos_order_state_id'));

                //create order with status Awaiting Payment
                $this->module->validateOrder(
                    (int)$cartObject->id,
                    $awaiting_state->id,
                    $cartTotal,
                    $this->module->displayName,
                    "",
                    [
                        '{payment}' => $awaiting_state->name[$cartObject->id_lang],
                    ],
                    (int)$currency->id,
                    false,
                    $customerObject->secure_key
                );
            }

            try {
                $purchase->process();
            } catch(IPC_Exception $exception) {
                var_dump($exception->getMessage());die;
            }
        }
    }
}
