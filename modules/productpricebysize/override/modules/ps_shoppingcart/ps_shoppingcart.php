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

use PrestaShop\PrestaShop\Adapter\Cart\CartPresenter;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Ps_ShoppingcartOverride extends Ps_Shoppingcart
{

    private function getCartSummaryURL()
    {
        return $this->context->link->getPageLink(
            'cart',
            null,
            $this->context->language->id,
            array(
                'action' => 'show'
            ),
            false,
            null,
            true
        );
    }

    public function renderModal(Cart $cart, $id_product, $id_product_attribute, $id_customization)
    {
        $data = (new CartPresenter)->present($cart);
        $product = null;
        $data['products'] = array_reverse($data['products']);

        foreach ($data['products'] as $p) {
            if ($p['id_product'] == $id_product && $p['id_product_attribute'] == $id_product_attribute) {
                $product = $p;
                break;
            }
        }

        $this->smarty->assign(array(
            'product' => $product,
            'cart' => $data,
            'cart_url' => $this->getCartSummaryURL(),
        ));

        return $this->fetch('module:ps_shoppingcart/modal.tpl');
    }
}
