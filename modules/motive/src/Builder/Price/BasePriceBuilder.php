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

use Motive\Prestashop\Model\Price;

if (!defined('_PS_VERSION_')) {
    exit;
}

class BasePriceBuilder implements PriceBuilderInterface
{
    /** @var \Context */
    protected $context;

    /** @var int */
    protected $decimals;

    /** @var bool */
    protected $useTax;

    /** @var bool */
    protected $showPrice;

    /**
     * BasePriceBuilder constructor.
     *
     * @param \Context $context
     * @param int $decimals
     */
    public function __construct(\Context $context, $decimals = 6)
    {
        $this->context = $context;
        $this->decimals = $decimals;
        $this->showPrice = static::shouldShowPrice();
        $this->useTax = $this->showPrice && static::isTaxDisplayed();
    }

    /**
     * Get tax display value for default Group.
     *
     * @return bool If show taxes
     */
    public static function isTaxDisplayed()
    {
        return !\Tax::excludeTaxeOption() && (!\Group::isFeatureActive() || !\Group::getCurrent()->price_display_method);
    }

    /**
     * Returns the product's prices
     *
     * @param array $productRow
     * @param int $idProductAttribute
     *
     * @return Price|null
     */
    public function get($productRow, $idProductAttribute = null)
    {
        if (!$this->showPrice || !$productRow['p_show_price']) {
            return null;
        }

        return $this->compute($productRow, $idProductAttribute);
    }

    /**
     * Compute the product's prices
     *
     * @param int $idProduct
     * @param int|null $idProductAttribute
     *
     * @return Price|null
     */
    protected function compute($productRow, $idProductAttribute = null)
    {
        return Price::build((float) $productRow['p_base_price']);
    }

    /**
     * Pre-fetch product prices
     *
     * @param int $fromIdProduct (included)
     * @param int $toIdProduct (included)
     */
    public function prefetch($fromIdProduct, $toIdProduct)
    {
        // Nothing to prefetch
    }

    /**
     * Get display price configuration.
     *
     * @return bool if display price
     */
    public static function shouldShowPrice()
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            return !\Configuration::get('PS_CATALOG_MODE')
                && (!\Group::isFeatureActive() || (bool) \Group::getCurrent()->show_prices);
        }

        // PS_CATALOG_MODE_WITH_PRICES since 1.7.6.0. Between version 1.7.0 and 1.7.5 the prices were displayed.
        return \Configuration::showPrices()
            && (!\Configuration::isCatalogMode()
                || (int) \Configuration::get('PS_CATALOG_MODE_WITH_PRICES', null, null, null, true));
    }

    /**
     * Get tax rates range.
     *
     * @param $id_address Address to compute tax
     *
     * @return array with the min and max tax rates
     */
    public static function getTaxRateRange($id_address = null)
    {
        if (!static::isTaxDisplayed()) {
            return [1, 1];
        }

        $taxRuleGroups = \TaxRulesGroup::getTaxRulesGroups(true);
        if (empty($taxRuleGroups)) {
            return [1, 1];
        }

        $context = \Context::getContext();
        if (!$id_address
            && \Validate::isLoadedObject($context->cart)
            && $context->cart->{\Configuration::get('PS_TAX_ADDRESS_TYPE')} != null) {
            $id_address = $context->cart->{\Configuration::get('PS_TAX_ADDRESS_TYPE')};
        }
        $address = \Address::initialize($id_address, true);

        $min = 100;
        $max = 0;
        foreach ($taxRuleGroups as $taxRuleGroup) {
            $tax_manager = \TaxManagerFactory::getManager($address, $taxRuleGroup['id_tax_rules_group']);
            $product_tax_calculator = $tax_manager->getTaxCalculator();
            $rate = $product_tax_calculator->addTaxes(1);

            if ($rate > $max) {
                $max = $rate;
            }

            if ($rate < $min) {
                $min = $rate;
            }
        }

        return [$min, $max];
    }

    /**
     * Get current group reduction.
     *
     * @return float the reduction rate
     */
    public static function getGroupReduction()
    {
        return \Group::isFeatureActive() ? (100.0 - (float) \Group::getCurrent()->reduction) / 100.0 : 1;
    }

    /**
     * Get current currency conversion rate.
     *
     * @return float the currency conversion rate
     */
    public static function getCurrencyConversionRate()
    {
        $context = \Context::getContext();

        return (float) $context->currency->conversion_rate;
    }
}
