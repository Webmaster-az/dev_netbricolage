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

use Motive\Prestashop\CacheWarmer\GroupReductionCacheWarmer;
use Motive\Prestashop\CacheWarmer\ProductCacheWarmer;
use Motive\Prestashop\CacheWarmer\SpecificPriceCacheWarmer;

if (!defined('_PS_VERSION_')) {
    exit;
}

class FasterPriceBuilder extends FastPriceBuilder
{
    /** @var object */
    protected $restrictions;

    public function __construct(\Context $context, $decimals = 6, $id_customer = 0, $id_group = 0, $id_address = 0, $id_country = 0, $id_state = 0, $zipcode = 0)
    {
        parent::__construct($context, $decimals, $id_customer, $id_group, $id_address, $id_country, $id_state, $zipcode);

        $this->restrictions = (object) [
            'id_shop' => $context->shop->id,
            'id_currency' => $this->id_currency,
            'id_country' => $this->id_country,
            'id_group' => $this->id_group,
            'id_customer' => $this->id_customer,
        ];
    }

    public function compute($productRow, $idProductAttribute = null)
    {
        ProductCacheWarmer::cacheProductIdTaxRulesGroup($productRow, $this->restrictions);

        return parent::compute($productRow, $idProductAttribute);
    }

    public function prefetch($fromIdProduct, $toIdProduct)
    {
        ProductCacheWarmer::fillPricesLevel2Cache($fromIdProduct, $toIdProduct, $this->restrictions);
        SpecificPriceCacheWarmer::fillCouldHaveSpecificPriceCache($fromIdProduct, $toIdProduct, $this->restrictions);
        SpecificPriceCacheWarmer::fillPrioritiesCache($fromIdProduct, $toIdProduct, $this->restrictions);
        GroupReductionCacheWarmer::fillReductionCache($fromIdProduct, $toIdProduct, $this->restrictions);
    }
}
