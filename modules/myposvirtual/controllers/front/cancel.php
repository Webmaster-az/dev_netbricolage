<?php

use Mypos\IPC\Defines;
use Mypos\IPC\Response;

class MyposVirtualCancelModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        require_once __DIR__ . '/../../sdk/Loader.php';

        $config = $this->module->getMyposVirtualConfig();

        if (Tools::isSubmit('Signature')) {
            $response = Response::getInstance($config, $_POST, Defines::COMMUNICATION_FORMAT_POST);
            $data = $response->getData(CASE_LOWER);

            if (Configuration::get('mypos_virtual_test_mode')) {
                $data['orderid'] = str_replace(Configuration::get('mypos_virtual_test_prefix'), '', $data['orderid']);
            }

            $cart = new Cart($data['orderid']);

            if ($cart->orderExists()) {
                $order = new Order((int)Order::getIdByCartId($cart->id));
                $new_history = new OrderHistory();
                $new_history->id_order = (int)$order->id;
                $new_history->changeIdOrderState(Configuration::get('PS_OS_CANCELED'), $order);
                $new_history->add();
            }

        }

        Tools::redirect($this->context->link->getPageLink('cart'));
    }
}