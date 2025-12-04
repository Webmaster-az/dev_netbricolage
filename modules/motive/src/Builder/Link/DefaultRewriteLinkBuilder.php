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

namespace Motive\Prestashop\Builder\Link;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Alternative implementation for link builder. DefaultRewriteLinkBuilder will create a pretty url
 * for products, following the default value in PS for product link rewriting, which is
 * {category:/}{id}{-:id_product_attribute}-{rewrite}{-:ean13}.html
 *
 * Should improve performance in PS 1.7+ with default rewrite value, without drawbacks in link
 * generation. Will not work with variants in PS 1.6.
 */
class DefaultRewriteLinkBuilder implements LinkBuilderInterface
{
    private $baseUrl;

    public function __construct($context)
    {
        $this->baseUrl = $context->link->getBaseLink($context->shop->id, null, false);
        if (\Language::isMultiLanguageActivated($context->shop->id) && (int) \Configuration::get('PS_REWRITING_SETTINGS', null, null, $context->shop->id)) {
            $this->baseUrl = $this->baseUrl . \Language::getIsoById($context->language->id) . '/';
        }
    }

    public function getProductLink($productRow, $idProductAttribute = '')
    {
        $categoryBuilder = $productRow['categoryBuilderInstance'];
        $defaultCategory = $categoryBuilder->getCategory($productRow['p_id_category_default']);
        $defaultCategoryRewrite = $defaultCategory ? \Tools::str2url($defaultCategory['link_rewrite']) : '';
        $attributeQuery = empty($idProductAttribute) ? '' : "-$idProductAttribute";
        $ean13Query = empty($productRow['p_ean13']) ? '' : '-' . $productRow['p_ean13'];
        $link_rewrite = $productRow['p_link_rewrite'];

        return "$this->baseUrl{$defaultCategoryRewrite}/{$productRow['p_id']}{$attributeQuery}-$link_rewrite{$ean13Query}.html";
    }
}
