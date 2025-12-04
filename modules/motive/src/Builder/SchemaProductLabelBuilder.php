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
use Motive\Prestashop\Model\SchemaProductLabel;

if (!defined('_PS_VERSION_')) {
    exit;
}

class SchemaProductLabelBuilder
{
    /** @var \Context */
    protected $context;

    /**
     * SchemaProductLabelBuilder constructor.
     *
     * @param \Context $context
     */
    public function __construct(\Context $context)
    {
        $this->context = $context;
    }

    /**
     * Returns list of available product's labels.
     *
     * @return SchemaProductLabel[] array of tags
     */
    public function getAvailableLabels()
    {
        $m = \Module::getInstanceByName('motive');

        // 1.6.1+
        if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            return [
                SchemaProductLabel::build('online-only', $m->l('Online only', 'schemaproductlabelbuilder')),
                SchemaProductLabel::build('on-sale', $m->l('Sale!', 'schemaproductlabelbuilder')),
                SchemaProductLabel::build('discount', $m->l('Reduced price', 'schemaproductlabelbuilder')),
                SchemaProductLabel::build('new', $m->l('New', 'schemaproductlabelbuilder')),
                SchemaProductLabel::build('pack', $m->l('Pack', 'schemaproductlabelbuilder')),
                SchemaProductLabel::build('out-of-stock', $m->l('Out of stock', 'schemaproductlabelbuilder')),
                SchemaProductLabel::build('in-stock', $m->l('In Stock', 'schemaproductlabelbuilder')),
                SchemaProductLabel::build('backorder', $m->l('Backorder', 'schemaproductlabelbuilder')),
            ];
        }

        // 1.7
        $t = $this->context->getTranslator();
        $isOld = version_compare(_PS_VERSION_, '1.7.4.0', '<');
        $inStockText = $this->getConfigText(
            'PS_LABEL_IN_STOCK_PRODUCTS',
            $isOld ? $m->l('In Stock', 'schemaproductlabelbuilder') : $t->trans('In Stock', [], 'Admin.Shopparameters.Feature')
        );
        $backorderText = $this->getConfigText(
            'PS_LABEL_OOS_PRODUCTS_BOA',
            $isOld ? $m->l('Backorder', 'schemaproductlabelbuilder') : $t->trans('Product available for orders', [], 'Admin.Shopparameters.Feature')
        );
        $outOfStockText = $this->getConfigText(
            'PS_LABEL_OOS_PRODUCTS_BOD',
            $isOld ? $m->l('Out of stock', 'schemaproductlabelbuilder') : $t->trans('Out-of-Stock', [], 'Admin.Shopparameters.Feature')
        );

        $labels = [
            SchemaProductLabel::build('online-only', $t->trans('Online only', [], 'Shop.Theme.Catalog')),
            SchemaProductLabel::build('on-sale', $t->trans('On sale!', [], 'Shop.Theme.Catalog')),
            SchemaProductLabel::build('discount', $t->trans('Reduced price', [], 'Shop.Theme.Catalog')),
            SchemaProductLabel::build('new', $t->trans('New', [], 'Shop.Theme.Catalog')),
            SchemaProductLabel::build('pack', $t->trans('Pack', [], 'Shop.Theme.Catalog')),
        ];

        $availabilityLabelsVisibility = Config::getLabelsAvailability();
        if ($availabilityLabelsVisibility !== LabelsAvailabilityConfig::PS) {
            $labels[] = SchemaProductLabel::build('in-stock', $inStockText);
            $labels[] = SchemaProductLabel::build('backorder', $backorderText);
        }

        $showLabelOOSListingPages = (bool) \Configuration::get('PS_SHOW_LABEL_OOS_LISTING_PAGES', null, null, null, $availabilityLabelsVisibility !== LabelsAvailabilityConfig::PS);
        if ($showLabelOOSListingPages) {
            $labels[] = SchemaProductLabel::build('out-of-stock', $outOfStockText);
        }

        return $labels;
    }

    /**
     * Returns the config's text
     *
     * @param string $key - Configuration key
     * @param string $default - Default value if not found or empty
     *
     * @return string the value
     */
    protected function getConfigText($key, $default)
    {
        $text = \Configuration::get($key, $this->context->language->id);

        return empty($text) ? $default : $text;
    }
}
