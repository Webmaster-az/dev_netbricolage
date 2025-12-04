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

class SpecificPriceCacheWarmer extends \SpecificPrice
{
    /**
     * Prefetch and fill SpecificPrice::$_couldHaveSpecificPriceCache cache.
     * Only from Prestashop version 1.7.2.2.
     *
     * @param int $fromIdProduct
     * @param int $toIdProduct
     * @param object $restrictions with id_shop, id_currency, id_country, id_group, id_customer fields
     */
    public static function fillCouldHaveSpecificPriceCache($fromIdProduct, $toIdProduct, $restrictions)
    {
        if (version_compare(_PS_VERSION_, '1.7.2.2', '<')) {
            return;
        }

        if (self::$_hasGlobalProductRules === null) {
            $queryHasGlobalRule = 'SELECT 1 FROM `' . _DB_PREFIX_ . 'specific_price` WHERE id_product = 0';
            $row = \Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($queryHasGlobalRule);
            self::$_hasGlobalProductRules = !empty($row);
        }
        if (self::$_hasGlobalProductRules) {
            return;
        }

        $zeroDate = '0000-00-00 00:00:00';
        $now = date('Y-m-d H:i:00');
        $sql = 'SELECT ps.id_product, COUNT(sp.id_product) > 0 AS has_specific_price
            FROM ' . _DB_PREFIX_ . 'product_shop AS ps
            LEFT JOIN ' . _DB_PREFIX_ . 'specific_price AS sp 
              ON sp.id_product = ps.id_product
              AND sp.id_cart = 0
              AND sp.id_shop ' . self::formatIntInQuery(0, $restrictions->id_shop) . '
              AND sp.id_currency ' . self::formatIntInQuery(0, $restrictions->id_currency) . '
              AND sp.id_country ' . self::formatIntInQuery(0, $restrictions->id_country) . '
              AND sp.id_group ' . self::formatIntInQuery(0, $restrictions->id_group) . '
              AND sp.id_customer ' . self::formatIntInQuery(0, $restrictions->id_customer) . "
              AND sp.from_quantity <= 1
              AND (sp.from = '$zeroDate' OR sp.from <= '$now') AND (sp.to = '$zeroDate' OR sp.to <= '$now')
            WHERE ps.id_product BETWEEN $fromIdProduct AND $toIdProduct
              AND ps.id_shop = {$restrictions->id_shop}
              AND ps.active = 1 AND ps.visibility IN ('both', 'search')
            GROUP BY ps.id_product
            ORDER BY ps.id_product ASC
        ";

        $rows = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        self::$_couldHaveSpecificPriceCache = [];
        foreach ($rows as &$row) {
            self::$_couldHaveSpecificPriceCache[$row['id_product']] = (bool) $row['has_specific_price'];
        }
    }

    /**
     * Prefetch and fill SpecificPrice::$_cache_priorities cache.
     *
     * @param int $fromIdProduct
     * @param int $toIdProduct
     * @param object $restrictions with id_shop field
     */
    public static function fillPrioritiesCache($fromIdProduct, $toIdProduct, $restrictions)
    {
        if (!\SpecificPrice::isFeatureActive()) {
            return;
        }

        $sql = 'SELECT ps.id_product, spp.priority
            FROM ' . _DB_PREFIX_ . 'product_shop AS ps
            LEFT JOIN ' . _DB_PREFIX_ . "specific_price_priority AS spp 
              ON spp.id_product = ps.id_product
            WHERE ps.id_product BETWEEN $fromIdProduct AND $toIdProduct
              AND ps.id_shop = {$restrictions->id_shop}
              AND ps.active = 1 AND ps.visibility IN ('both', 'search')
            ORDER BY ps.id_product ASC
        ";

        $rows = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        self::$_cache_priorities = [];
        foreach ($rows as &$row) {
            self::$_cache_priorities[$row['id_product']] = $row['priority'] !== null ? $row['priority'] : false;
        }
    }
}
