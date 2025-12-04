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
use Motive\Prestashop\Model\AttributeValue;

if (!defined('_PS_VERSION_')) {
    exit;
}

class AttributeValueBuilder extends AdditionalProductData
{
    /** @var string */
    protected $textureBaseUrl = '';

    public function __construct(\Context $context)
    {
        parent::__construct($context);
        $this->textureBaseUrl = rtrim($context->shop->getBaseURL(true), '/') . _THEME_COL_DIR_;
    }

    public function prefetch($fromIdProduct, $toIdProduct)
    {
        if (\Combination::isFeatureActive()) {
            parent::prefetch($fromIdProduct, $toIdProduct);
        }
    }

    protected function buildQuery($fromIdProduct, $toIdProduct)
    {
        return 'SELECT
            pac.id_product_attribute AS id,
            a.id_attribute_group AS a_id,
            a.id_attribute       AS a_id_value,
            al.name              AS a_value,
            ag.is_color_group    AS a_is_color,
            a.color              AS a_color
          FROM ' . _DB_PREFIX_ . 'product_attribute_combination AS pac
          JOIN ' . _DB_PREFIX_ . 'product_attribute AS pa
            ON pac.id_product_attribute = pa.id_product_attribute
          JOIN ' . _DB_PREFIX_ . "product_shop AS ps
            ON pa.id_product = ps.id_product
            AND ps.id_shop = {$this->context->shop->id}
            AND ps.active = 1
            AND ps.visibility IN ('both', 'search')
          JOIN " . _DB_PREFIX_ . 'attribute AS a
            ON a.id_attribute = pac.id_attribute
          JOIN ' . _DB_PREFIX_ . "attribute_lang AS al
            ON al.id_attribute = pac.id_attribute
            AND al.id_lang = {$this->context->language->id}
          JOIN " . _DB_PREFIX_ . 'attribute_group AS ag
            ON ag.id_attribute_group = a.id_attribute_group
          JOIN ' . _DB_PREFIX_ . "attribute_group_shop AS ags
            ON ags.id_attribute_group = ag.id_attribute_group
            AND ags.id_shop = {$this->context->shop->id}
          WHERE pa.id_product BETWEEN $fromIdProduct AND $toIdProduct
        ";
    }

    /**
     * Converts the rows and returns the attributes of a variant
     *
     * @param array $rows
     *
     * @return AttributeValue[] array of attributes
     */
    protected function map($rows)
    {
        /** @var AttributeValue[] $attributes */
        $attributes = [];
        foreach ($rows as $rawAttr) {
            if (empty($rawAttr['a_value'])) {
                continue;
            }

            $isColor = (bool) $rawAttr['a_is_color'];
            if ($isColor && is_readable(_PS_COL_IMG_DIR_ . $rawAttr['a_id_value'] . '.jpg')) {
                $rawAttr['a_color'] = $this->textureBaseUrl . $rawAttr['a_id_value'] . '.jpg';
            }

            $attributes[] = new AttributeValue(
                AttributeBuilder::getKey($rawAttr['a_id']),
                $rawAttr['a_value'],
                $isColor ? (string) $rawAttr['a_color'] : null
            );
        }

        return $attributes;
    }
}
