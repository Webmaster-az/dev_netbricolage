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
 * Alternative implementation for link builder. SimpleLinkBuilder only appends needed ids, creating
 * a ugly url. Should work always, and maybe the fastest one, but when accessing ugly url's in PS,
 * a redirection will be made by PS, which degrades performance.
 */
class UnfriendlyLinkBuilder implements LinkBuilderInterface
{
    private $baseUrl;

    public function __construct($context)
    {
        $baseUrl = $context->link->getBaseLink($context->shop->id, null, false);
        $this->baseUrl = "{$baseUrl}index.php?controller=product&idShop={$context->shop->id}&idLang={$context->language->id}";
    }

    public function getProductLink($productRow, $idProductAttribute = '')
    {
        return "$this->baseUrl&id_product={$productRow['p_id']}&id_product_attribute=$idProductAttribute";
    }
}
