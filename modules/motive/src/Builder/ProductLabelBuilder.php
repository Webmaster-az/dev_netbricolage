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

use Motive\Prestashop\Config;
use Motive\Prestashop\LabelsAvailabilityConfig;
use Motive\Prestashop\Model\ProductLabel;
use Motive\Prestashop\OutOfStockBehaviour;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ProductLabelBuilder
{
    protected $isPs16;
    protected $context;
    protected $isCatalogMode;
    protected $showLabelOOSListingPages;
    protected $isStockManagementActive;
    protected $defaultOutOfStockBehaviour;
    protected $showPrice;
    protected $availabilityLabelsVisibility;
    protected $isDefaultInStockVisible;
    protected $isDefaultBackorderVisible;

    /**
     * SchemaProductLabelBuilder constructor.
     *
     * @param \Context $context
     */
    public function __construct(\Context $context)
    {
        $this->isPs16 = version_compare(_PS_VERSION_, '1.7.0.0', '<');
        $this->availabilityLabelsVisibility = Config::getLabelsAvailability();

        $this->context = $context;
        $this->isCatalogMode = $this->isPs16 ? (bool) \Configuration::get('PS_CATALOG_MODE') : \Configuration::isCatalogMode();
        $this->showLabelOOSListingPages = (bool) \Configuration::get('PS_SHOW_LABEL_OOS_LISTING_PAGES', null, null, null, $this->availabilityLabelsVisibility !== LabelsAvailabilityConfig::PS);
        $this->isStockManagementActive = (bool) \Configuration::get('PS_STOCK_MANAGEMENT');
        $this->defaultOutOfStockBehaviour = (int) \Configuration::get('PS_ORDER_OUT_OF_STOCK');
        $this->showPrice = $this->shouldShowPrice();
        $this->isDefaultInStockVisible = $this->isPs16 || (
            $this->availabilityLabelsVisibility === LabelsAvailabilityConfig::ALL && (bool) $this->getConfigText('PS_LABEL_IN_STOCK_PRODUCTS')
        );
        $this->isDefaultBackorderVisible = $this->isPs16 || (
            $this->availabilityLabelsVisibility === LabelsAvailabilityConfig::ALL && (bool) $this->getConfigText('PS_LABEL_OOS_PRODUCTS_BOA')
        );
    }

    /**
     * Returns the product's labels
     *
     * @param array $row - product row
     *
     * @return string[] array of tags
     */
    public function getForProduct(array $row)
    {
        $labels = [];

        $show_price = $this->showPrice && (bool) $row['p_show_price'];

        if ($show_price && $row['p_online_only']) {
            $labels[] = ProductLabel::build('online-only');
        }

        if ($show_price && $row['p_on_sale'] && !$this->isCatalogMode) {
            $labels[] = ProductLabel::build('on-sale');
        }

        if (isset($row['p_price']) && $row['p_price']->on_sale !== null && $row['p_price']->regular > 0) {
            $discount = -100 + $row['p_price']->on_sale * 100 / $row['p_price']->regular;
            $text = $this->isPs16 ? round($discount, 0) : \Tools::displayNumber(round($discount, 2));
            $labels[] = ProductLabel::build('discount', $text . '%');
        }

        if ($row['p_is_new']) {
            $labels[] = ProductLabel::build('new');
        }

        if ($row['p_is_pack']) {
            $labels[] = ProductLabel::build('pack');
        }

        if ($this->isStockManagementActive && !$this->isCatalogMode && $row['p_available_for_order']) {
            $label = $this->getAvailabilityLabel($row);
            if ($label !== null) {
                $labels[] = $label;
            }
        }

        return $labels;
    }

    /**
     * @param array $product
     *
     * @return ProductLabel|null
     */
    protected function getAvailabilityLabel(array $row)
    {
        $quantity = empty($row['p_variants'])
            ? $row['p_quantity']
            : max(array_column($row['p_variants'], 'v_quantity'));

        if ($quantity > 0) {
            if ($this->availabilityLabelsVisibility === LabelsAvailabilityConfig::PS) {
                return null;
            }
            if (!empty($row['p_available_now'])) {
                return ProductLabel::build('in-stock', $row['p_available_now']);
            }

            return $this->isDefaultInStockVisible ? ProductLabel::build('in-stock') : null;
        }

        if ($row['p_out_of_stock'] == OutOfStockBehaviour::OUT_OF_STOCK_AVAILABLE || (
            $row['p_out_of_stock'] == OutOfStockBehaviour::OUT_OF_STOCK_DEFAULT
            && $this->defaultOutOfStockBehaviour == OutOfStockBehaviour::OUT_OF_STOCK_AVAILABLE)
        ) {
            if ($this->availabilityLabelsVisibility === LabelsAvailabilityConfig::PS) {
                return null;
            }
            if (!empty($row['p_available_later'])) {
                return ProductLabel::build('backorder', $row['p_available_later']);
            }

            return $this->isDefaultBackorderVisible ? ProductLabel::build('backorder') : null;
        }

        return $this->showLabelOOSListingPages ? ProductLabel::build('out-of-stock') : null;
    }

    /**
     * @return bool
     */
    protected function shouldShowPrice()
    {
        if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            return !$this->isCatalogMode && (!\Group::isFeatureActive() || (bool) \Group::getCurrent()->show_prices);
        }

        // PS_CATALOG_MODE_WITH_PRICES since 1.7.6.0. Between version 1.7.0 and 1.7.5 the prices were displayed.
        return \Configuration::showPrices()
            && (!$this->isCatalogMode || (int) \Configuration::get('PS_CATALOG_MODE_WITH_PRICES', null, null, null, true));
    }

    /**
     * Returns the config's text
     *
     * @param string $key - Configuration key
     *
     * @return string the value
     */
    protected function getConfigText($key, $default = '')
    {
        return \Configuration::get($key, $this->context->language->id, null, null, $default);
    }
}
