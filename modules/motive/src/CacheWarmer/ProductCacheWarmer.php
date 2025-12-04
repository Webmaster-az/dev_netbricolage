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

namespace Motive\Prestashop\CacheWarmer;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ProductCacheWarmer extends \Product
{
    /**
     * Prefetch and fill Product::$_pricesLevel2 cache.
     *
     * @param int $fromIdProduct
     * @param int $toIdProduct
     * @param object $restrictions with id_shop and id_group fields
     */
    public static function fillPricesLevel2Cache($fromIdProduct, $toIdProduct, $restrictions)
    {
        $sql = 'SELECT
                ps.id_product,
                ps.price,
                ps.ecotax,
                pas.id_product_attribute,
                pas.price AS attribute_price,
                pas.default_on
            FROM ' . _DB_PREFIX_ . 'product_shop AS ps
            LEFT JOIN ' . _DB_PREFIX_ . "product_attribute_shop AS pas 
                ON pas.id_product = ps.id_product
                AND pas.id_shop = {$restrictions->id_shop}
            WHERE ps.id_product BETWEEN $fromIdProduct AND $toIdProduct
                AND ps.id_shop = {$restrictions->id_shop}
                AND ps.active = 1 AND ps.visibility IN ('both', 'search')
            ORDER BY ps.id_product ASC
        ";

        $rows = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        self::$_pricesLevel2 = [];
        foreach ($rows as &$row) {
            $cache_id_2 = $row['id_product'] . '-' . $restrictions->id_shop;
            self::$_pricesLevel2[$cache_id_2][(int) $row['id_product_attribute']] = [
                'price' => $row['price'],
                'ecotax' => $row['ecotax'],
                'attribute_price' => $row['attribute_price'],
            ];

            if ($row['default_on']) {
                self::$_pricesLevel2[$cache_id_2][0] = self::$_pricesLevel2[$cache_id_2][(int) $row['id_product_attribute']];
            }
        }
    }

    /**
     * Cache the product_id_tax_rules_group to avoid additional query by each product.
     *
     * @param array $productRow Product identifier
     * @param object $restrictions with id_shop field
     */
    public static function cacheProductIdTaxRulesGroup($productRow, $restrictions)
    {
        $key = 'product_id_tax_rules_group_' . (int) $productRow['p_id'] . '_' . (int) $restrictions->id_shop;
        \Cache::store($key, (int) $productRow['p_id_tax_rules_group']);
    }
}
