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

class GroupReductionCacheWarmer extends \GroupReduction
{
    /**
     * Prefetch and fill GroupReduction::$reduction_cache cache.
     *
     * @param int $fromIdProduct
     * @param int $toIdProduct
     * @param object $restrictions with id_shop and id_group fields
     */
    public static function fillReductionCache($fromIdProduct, $toIdProduct, $restrictions)
    {
        if (!\Group::isFeatureActive()) {
            return 0;
        }

        $sql = 'SELECT ps.id_product, pgrc.reduction
            FROM ' . _DB_PREFIX_ . 'product_shop AS ps
            LEFT JOIN ' . _DB_PREFIX_ . "product_group_reduction_cache AS pgrc 
              ON pgrc.id_product = ps.id_product
              AND pgrc.id_group = {$restrictions->id_group}
            WHERE ps.id_product BETWEEN $fromIdProduct AND $toIdProduct
              AND ps.id_shop = {$restrictions->id_shop}
              AND ps.active = 1 AND ps.visibility IN ('both', 'search')
            ORDER BY ps.id_product ASC
        ";

        $rows = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        self::$reduction_cache = [];
        foreach ($rows as &$row) {
            self::$reduction_cache[$row['id_product'] . '-' . $restrictions->id_group] = $row['reduction'] !== null ? $row['reduction'] : false;
        }
    }
}
