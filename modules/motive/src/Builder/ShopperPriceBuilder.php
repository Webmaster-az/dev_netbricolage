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

namespace Motive\Prestashop\Builder;

use Motive\Prestashop\Builder\Price\BasePriceBuilder as PriceBuilder;
use Motive\Prestashop\Model\XResultPrice;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ShopperPriceBuilder
{
    /** @var \Context */
    protected $context;

    /** @var int */
    protected $decimals;

    /** @var bool */
    protected $useTax;

    /** @var bool */
    protected $showPrice;

    /**
     * SchemaBuilder constructor.
     *
     * @param \context $context
     */
    public function __construct($context)
    {
        $this->context = $context;

        $this->decimals = version_compare(_PS_VERSION_, '1.7.7.0', '>=') ?
            $this->context->getComputingPrecision() :
            (int) \Configuration::get('PS_PRICE_DISPLAY_PRECISION');

        $this->showPrice = PriceBuilder::shouldShowPrice();
        $this->useTax = $this->showPrice && PriceBuilder::isTaxDisplayed();
    }

    /**
     * Encrich products with price field.
     *
     * @param array $data partial of product list
     *
     * @return array same object with prices
     */
    public function enrich(array $products)
    {
        if (!$this->showPrice) {
            // No data
            return [];
        }

        $ids = [];
        foreach ($products as $key => $product) {
            if (empty($product['id']) || (int) $product['id'] <= 0) {
                unset($products[$key]);
                continue;
            }
            $ids[] = (int) $product['id'];
        }

        if (empty($ids)) {
            return [];
        }

        $productsInfo = $this->getProductsInfo($ids);
        $productsInfo = array_combine(array_column($productsInfo, 'id'), $productsInfo);

        foreach ($products as &$product) {
            // If product does not exist
            if (empty($productsInfo[$product['id']])) {
                continue;
            }

            $info = $productsInfo[$product['id']];

            // If not available_for_order or show_price = 0, skip
            if (empty($info['available_for_order']) && empty($info['show_price'])) {
                continue;
            }
            $product['price'] = $this->getProductPrice((int) $product['id']);
            $product['availability'] = [
                'minimal_quantity' => max(1, (int) $info['minimal_quantity']),
            ];
            if (empty($product['variants'])) {
                continue;
            }
            foreach ($product['variants'] as &$variant) {
                if (empty($variant['id'])) {
                    continue;
                }
                $variant['price'] = $this->getProductPrice((int) $product['id'], (int) $variant['id']);
            }
        }

        return $products;
    }

    /**
     * Returns the XResultPrice for a product/variant
     *
     * @param $idProduct
     * @param int|null $idProductAttribute
     *
     * @return XResultPrice
     */
    public function getProductPrice($idProduct, $idProductAttribute = null)
    {
        $regular = \Product::getPriceStatic($idProduct, $this->useTax, $idProductAttribute, $this->decimals, null, false, false);
        $on_sale = !$regular ? $regular : \Product::getPriceStatic($idProduct, $this->useTax, $idProductAttribute, $this->decimals);

        return XResultPrice::build($regular, $on_sale);
    }

    /**
     * Get product info: show_price
     *
     * @return array of product data
     */
    protected function getProductsInfo($ids)
    {
        $ids = implode(',', $ids);
        $sql = '
            SELECT
                ps.id_product AS id,
                ps.show_price,
                ps.available_for_order,
                ps.minimal_quantity
            FROM ' . _DB_PREFIX_ . "product_shop AS ps
            WHERE ps.id_shop = {$this->context->shop->id} AND ps.id_product IN ($ids)
            ORDER BY ps.id_product
        ";

        return empty($ids) ? [] : \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }
}
