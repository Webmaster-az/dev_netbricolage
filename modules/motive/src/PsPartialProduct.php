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

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Class PsPartialProduct
 *
 * Class with a subset of PS Product class properties & methods. Used to avoid extra SQL queries
 * on product & variant link generation.
 */
class PsPartialProduct
{
    public $id;
    public $ean13;
    public $isFullyLoaded = true;
    public $manufacturer_name;
    public $supplier_name;
    public $price;
    public $category;
    public $reference;
    private $productRow;
    private $parentCategories;

    public function __construct($productRow)
    {
        $this->productRow = $productRow;
        $this->id = $productRow['p_id'];
        $this->ean13 = $productRow['p_ean13'];
        $this->manufacturer_name = $productRow['p_brand'];
        $this->supplier_name = $productRow['p_supplier'];
        if (empty($productRow['p_price'])) {
            $this->price = 0;
        } else {
            $this->price = $productRow['p_price']->on_sale ?: $productRow['p_price']->regular;
        }
        $this->reference = $productRow['p_ref'];

        $categoryBuilder = $this->productRow['categoryBuilderInstance'];
        $this->parentCategories = $categoryBuilder->getPathAsArray($productRow['p_id_category_default']);
        $this->category = end($this->parentCategories) ? \Tools::str2url(end($this->parentCategories)['link_rewrite']) : '';
    }

    public function getFieldByLang($field)
    {
        return $this->productRow["p_$field"];
    }

    public function getTags($idLang)
    {
        return implode(', ', $this->productRow['p_tags']);
    }

    public function getParentCategories($idLang)
    {
        return $this->parentCategories;
    }

    public function getAnchor($idProductAttribute, $withIdInAnchor)
    {
        // TODO: This can be improved, getAttributesParams calls db, and we're obtaining all needed data
        // inside VariationBuilder.
        $attributes = \Product::getAttributesParams($this->id, $idProductAttribute);
        $anchor = '#';
        $sep = \Configuration::get('PS_ATTRIBUTE_ANCHOR_SEPARATOR');
        foreach ($attributes as &$a) {
            foreach ($a as &$b) {
                $b = str_replace($sep, '_', \Tools::str2url((string) $b));
            }
            $anchor .= '/' . ($withIdInAnchor && isset($a['id_attribute']) && $a['id_attribute'] ? (int) $a['id_attribute'] . $sep : '') . $a['group'] . $sep . $a['name'];
        }

        return $anchor;
    }
}
