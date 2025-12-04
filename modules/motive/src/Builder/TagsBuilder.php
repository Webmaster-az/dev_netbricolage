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

use Motive\Prestashop\AdditionalProductData;

if (!defined('_PS_VERSION_')) {
    exit;
}

class TagsBuilder extends AdditionalProductData
{
    public function buildQuery($fromIdProduct, $toIdProduct)
    {
        return '
            SELECT
                pt.id_product AS id,
                t.name AS value
            FROM ' . _DB_PREFIX_ . 'product_tag AS pt
            JOIN ' . _DB_PREFIX_ . "tag AS t
              ON t.id_tag = pt.id_tag
              AND t.id_lang = {$this->context->language->id}
            JOIN " . _DB_PREFIX_ . "product_shop AS ps
              ON pt.id_product = ps.id_product
              AND ps.id_shop = {$this->context->shop->id}
              AND ps.active = 1
              AND ps.visibility IN ('both', 'search')
            WHERE pt.id_product BETWEEN $fromIdProduct AND $toIdProduct
            ORDER BY pt.id_product ASC
        ";
    }

    protected function map($rows)
    {
        return array_column($rows, 'value');
    }
}
