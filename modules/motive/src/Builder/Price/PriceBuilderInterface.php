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

namespace Motive\Prestashop\Builder\Price;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Motive\Prestashop\Model\Price;

interface PriceBuilderInterface
{
    /**
     * Constructor of alternative price builder implementation.
     *
     * @param \Context $context
     * @param int $decimals
     */
    public function __construct(\Context $context, $decimals = 6);

    /**
     * Returns the product's prices
     *
     * @param array $productRow
     * @param int $idProductAttribute
     *
     * @return Price|null
     */
    public function get($productRow, $idProductAttribute = null);

    /**
     * Pre-fetch product prices
     *
     * @param int $fromIdProduct (included)
     * @param int $toIdProduct (included)
     */
    public function prefetch($fromIdProduct, $toIdProduct);
}
