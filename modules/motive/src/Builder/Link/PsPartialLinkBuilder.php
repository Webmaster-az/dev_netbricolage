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

use Motive\Prestashop\PsPartialProduct;

if (!defined('_PS_VERSION_')) {
    exit;
}
/**
 * Alternative implementation for link builder. PreviousLinkBuilder will build link as we did it
 * before LinkManager implementation.
 */
class PsPartialLinkBuilder implements LinkBuilderInterface
{
    private $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    public function getProductLink($productRow, $idProductAttribute = null)
    {
        $psPartialProduct = new PsPartialProduct($productRow);
        $withIdInAnchor = $idProductAttribute !== null && version_compare(_PS_VERSION_, '1.7.0.0', '<');

        return $this->context->link->getProductLink(
            $psPartialProduct,
            null,
            null,
            null,
            $this->context->language->id,
            $this->context->shop->id,
            $idProductAttribute,
            null,
            null,
            $withIdInAnchor
        );
    }
}
