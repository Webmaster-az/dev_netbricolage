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

use Motive\Prestashop\Builder\Price\BasePriceBuilder as PriceBuilder;

if (!defined('_PS_VERSION_')) {
    exit;
}

class PriceModifiers
{
    /** @var float */
    public $currency_conversion;

    /** @var float */
    public $group_reduction;

    /** @var float */
    public $min_tax;

    /** @var float */
    public $max_tax;

    /**
     * Get simplified object with the price multipliers that applies the price modifiers.
     *
     * @return array the rate range
     */
    public function toRates()
    {
        $baseRate = $this->group_reduction * $this->currency_conversion;
        // $min and $max are used to increase the price filter precision.
        $min = $this->min_tax * $baseRate;
        $max = $this->max_tax * $baseRate;
        // $static is used to convert the visible product price (to apply current user currency and group reduction).
        // If $max and $min are distinct (there are several tax) then use $baseRate.
        $static = abs($max - $min) >= 0.0001 ? $baseRate : $max;

        return [
            'min' => $min,
            'max' => $max,
            'static' => $static,
        ];
    }

    /**
     * Build with current user price modifiers.
     *
     * @return static
     */
    public static function build()
    {
        $taxRange = PriceBuilder::getTaxRateRange();

        $obj = new static();
        $obj->currency_conversion = PriceBuilder::getCurrencyConversionRate();
        $obj->group_reduction = PriceBuilder::getGroupReduction();
        $obj->min_tax = $taxRange[0];
        $obj->max_tax = $taxRange[1];

        return $obj;
    }

    /**
     * Get current user price modifiers.
     *
     * @return static
     */
    public static function fromJson($json)
    {
        $jsonObj = json_decode($json);

        $obj = new static();
        if (empty($jsonObj)) {
            $obj->currency_conversion = 1;
            $obj->group_reduction = 1;
            $obj->min_tax = 1;
            $obj->max_tax = 1;
        } else {
            $obj->currency_conversion = $jsonObj->currency_conversion;
            $obj->group_reduction = $jsonObj->group_reduction;
            $obj->min_tax = $jsonObj->min_tax;
            $obj->max_tax = $jsonObj->max_tax;
        }

        return $obj;
    }

    /**
     * Get current user price rates to transform prices from last sync.
     *
     * @return array the rate range
     */
    public static function getPriceTransformRates()
    {
        $minRate = 1;
        $maxRate = 1;
        $staticRate = 1;

        if (PriceBuilder::shouldShowPrice()) {
            $context = \Context::getContext();
            $syncRates = static::fromJson(Config::getLastSyncInfo($context->language->id))->toRates();
            $shoppertRates = static::build()->toRates();
            $minRate = $shoppertRates['min'] / $syncRates['min'];
            $maxRate = $shoppertRates['max'] / $syncRates['max'];
            $staticRate = $shoppertRates['static'] / $syncRates['static'];
        }

        return [
            'min' => $minRate,
            'max' => $maxRate,
            'static' => $staticRate,
        ];
    }
}
