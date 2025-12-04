<?php
/**
 * (C) 2023 Motive Commerce Search Corp S.L. <info@motive.co>
 *
 * This file is part of Motive Commerce Search.
 *
 * This file is licensed to you under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author Motive (motive.co)
 * @copyright (C) 2023 Motive Commerce Search Corp S.L. <info@motive.co>
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Motive\Prestashop;

use Motive\Prestashop\Builder\MetadataBuilder;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Tagging
{
    /**
     * @param $params - hookActionCartUpdateQuantityBefore params
     */
    public static function addToCart(array $params)
    {
        $clickUUID = static::getTaggingClickUUID();
        if (!$clickUUID) {
            return;
        }

        $context = \Context::getContext();
        $engineId = Config::getEngineId($context->language->id);
        if (!$engineId) {
            return;
        }

        $url = rtrim(Config::getTaggingBaseUrl(), '/') . '/products/add-to-cart';
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return;
        }

        $cart = $params['cart'];
        $product = $params['product'];
        $idProduct = $product->id;
        $idVariant = (int) $params['id_product_attribute'];
        $cartProduct = null;
        foreach ($cart->getProducts(false, $idProduct) as $cartProductArray) {
            if ($cartProductArray['id_product_attribute'] == $idVariant) {
                $cartProduct = (object) $cartProductArray;
                break;
            }
        }

        $payload = [
            'clickUUID' => $clickUUID,
            'productId' => (string) $idProduct,
            'name' => $cartProduct->name,
            'reference' => $cartProduct->reference,
            'url' => $context->link->getProductLink(
                $product,
                null,
                null,
                null,
                $context->language->id,
                $context->shop->id,
                $idVariant,
                false,
                false,
                (bool) $idVariant
            ),
            'image' => $context->link->getImageLink(
                $product->link_rewrite,
                $cartProduct->id_image,
                Config::getImageSize()
            ),
            'price' => $cartProduct->price_wt,
            'quantity' => $params['operator'] === 'up' ? +$params['quantity'] : -$params['quantity'],
            'cartId' => $params['cart']->id,
        ];
        if ($idVariant) {
            $payload['variationId'] = (string) $idVariant;
        }

        $headers = [
            'x-api-version: 1',
            'x-engine-id: ' . Config::getEngineId($context->language->id),
        ];

        static::post($url, $payload, $headers);
    }

    /**
     * Performs a POST request
     *
     * @param $url - Request url
     * @param $body - Request body
     * @param $headers - Request $headers
     */
    protected static function post($url, $body, array $headers = [])
    {
        $timeout = Config::getTaggingTimeout();
        $body = json_encode($body);
        $headers = array_merge([
            'Content-Type: application/json',
            'Connection: close',
            'Content-Length: ' . strlen($body),
            'user-agent: ' . MetadataBuilder::getSource(),
        ], $headers);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_CONNECTTIMEOUT_MS => $timeout,
            CURLOPT_TIMEOUT_MS => $timeout,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_MAXREDIRS => 0,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Gets the tagging click id from query param or from referer url query param
     *
     * @return string|null
     */
    protected static function getTaggingClickUUID()
    {
        $queryParam = 'mot_tcid';

        $clickUUID = \Tools::getValue($queryParam);
        if ($clickUUID) {
            return $clickUUID;
        }

        if (empty($_SERVER['HTTP_REFERER'])) {
            return null;
        }

        $queryParamsStr = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
        if ($queryParamsStr === null) {
            return null;
        }

        parse_str($queryParamsStr, $queryParams);
        if (empty($queryParams[$queryParam])) {
            return null;
        }

        return $queryParams[$queryParam];
    }
}
