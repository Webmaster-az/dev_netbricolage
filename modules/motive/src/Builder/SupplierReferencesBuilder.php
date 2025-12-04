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

class SupplierReferencesBuilder extends AdditionalProductData
{
    public function buildQuery($fromIdProduct, $toIdProduct)
    {
        return "
            SELECT DISTINCT
                CONCAT(psu.id_product, '-', psu.id_product_attribute) AS id,
                psu.product_supplier_reference AS value
            FROM " . _DB_PREFIX_ . 'product_supplier AS psu
            JOIN ' . _DB_PREFIX_ . "product_shop AS ps
              ON psu.id_product = ps.id_product
              AND ps.id_shop = {$this->context->shop->id}
              AND ps.active = 1
              AND ps.visibility IN ('both', 'search')
            WHERE psu.id_product BETWEEN $fromIdProduct AND $toIdProduct
              AND psu.product_supplier_reference != ''
            ORDER BY id ASC
        ";
    }

    /**
     * Returns the product's supplier references
     *
     * @param int $idProduct
     * @param int $idProductAttribute
     *
     * @return string[] array of supplier references
     */
    public function get($idProduct, $idProductAttribute = 0)
    {
        $id = "$idProduct-$idProductAttribute";

        return empty($this->data[$id]) ? [] : array_column($this->data[$id], 'value');
    }
}
