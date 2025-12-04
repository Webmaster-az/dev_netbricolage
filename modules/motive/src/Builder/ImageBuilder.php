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
use Motive\Prestashop\Config;
use Motive\Prestashop\Model\Image;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ImageBuilder extends AdditionalProductData
{
    protected $context;
    protected $imageSize;

    public function __construct(\Context $context)
    {
        parent::__construct($context);
        $this->imageSize = Config::getImageSize();
    }

    protected function buildQuery($fromIdProduct, $toIdProduct)
    {
        return 'SELECT
                i.id_product AS id,
                COALESCE(pai.id_product_attribute, 0) AS id_product_attribute, 
                i.id_image                            AS i_id,
                il.legend                             AS i_legend
            FROM ' . _DB_PREFIX_ . 'image AS i
                JOIN ' . _DB_PREFIX_ . "image_shop AS ish
                  ON ish.id_image = i.id_image
                  AND ish.id_shop = {$this->context->shop->id}
                JOIN " . _DB_PREFIX_ . "product_shop AS ps
                  ON i.id_product = ps.id_product
                  AND ps.id_shop = {$this->context->shop->id}
                  AND ps.active = 1
                  AND ps.visibility IN ('both', 'search')
                LEFT JOIN " . _DB_PREFIX_ . "image_lang AS il
                  ON il.id_image = i.id_image
                  AND il.id_lang = {$this->context->language->id}
                LEFT JOIN " . _DB_PREFIX_ . "product_attribute_image AS pai
                    ON i.id_image = pai.id_image
            WHERE i.id_product BETWEEN $fromIdProduct AND $toIdProduct
            ORDER BY i.id_product ASC, ish.cover DESC, i.position
        ";
    }

    /**
     * Returns the product's images
     *
     * @param array $productRow
     * @param int $idProductAttribute
     * @param string $linkRewrite
     *
     * @return Image[]
     */
    public function get($productRow, $idProductAttribute = 0)
    {
        $idProduct = (int) $productRow['p_id'];
        $idProductAttribute = (int) $idProductAttribute;

        if (empty($this->data[$idProduct])) {
            return [];
        }

        $images = [];
        foreach ($this->data[$idProduct] as $rawImg) {
            $id = $idProduct . '-' . $rawImg['i_id'];
            if (empty($images[$id]) && ($idProductAttribute === 0 || $idProductAttribute === (int) $rawImg['id_product_attribute'])) {
                $url = $this->context->link->getImageLink($productRow['p_link_rewrite'], $id, $this->imageSize);
                $images[$id] = Image::build($url, $rawImg['i_legend']);
            }
        }

        return array_values($images);
    }
}
