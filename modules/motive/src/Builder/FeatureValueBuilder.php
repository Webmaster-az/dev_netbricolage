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
use Motive\Prestashop\Model\FeatureValue;

if (!defined('_PS_VERSION_')) {
    exit;
}

class FeatureValueBuilder extends AdditionalProductData
{
    protected $limit;

    protected $featuresIdsLimit = '';

    public function __construct(\Context $context)
    {
        parent::__construct($context);
        $this->limit = \Feature::isFeatureActive() ? (int) Config::getFeaturesLimit() : 0;

        if ($this->limit > 0) {
            $featureBuilder = new FeatureBuilder($this->context);
            $featuresIds = $featureBuilder->fetchIds();

            // Don't limit if there is no limit (-1) or the number of features is less than the limit.
            if (!empty($featuresIds) && substr_count($featuresIds, ',') + 1 >= $this->limit) {
                $this->featuresIdsLimit = "AND fp.id_feature IN ($featuresIds)";
            }
        }
    }

    public function prefetch($fromIdProduct, $toIdProduct)
    {
        if ($this->limit == 0) {
            return;
        }

        parent::prefetch($fromIdProduct, $toIdProduct);
    }

    protected function buildQuery($fromIdProduct, $toIdProduct)
    {
        return 'SELECT
            fp.id_product AS id,
            fp.id_feature,
            fvl.value
          FROM ' . _DB_PREFIX_ . 'feature_product AS fp
          JOIN ' . _DB_PREFIX_ . "product_shop AS ps
            ON fp.id_product = ps.id_product
            AND ps.id_shop = {$this->context->shop->id}
            AND ps.active = 1
            AND ps.visibility IN ('both', 'search')
          JOIN " . _DB_PREFIX_ . "feature_value_lang AS fvl
            ON fvl.id_feature_value = fp.id_feature_value
            AND fvl.id_lang = {$this->context->language->id}
          WHERE fp.id_product BETWEEN $fromIdProduct AND $toIdProduct
            {$this->featuresIdsLimit}
          ORDER BY fp.id_product ASC
        ";
    }

    /**
     * Converts the row/rows data to the expected output type.
     *
     * @param $rows
     *
     * @return mixed
     */
    protected function map($rows)
    {
        /** @var FeatureValue[] $features */
        $features = [];
        foreach ($rows as $rawFeat) {
            if (empty($rawFeat['value'])) {
                continue;
            }

            $key = FeatureBuilder::getKey($rawFeat['id_feature']);
            if (!isset($features[$key])) {
                $features[$key] = new FeatureValue($key);
            }
            $features[$key]->addValue($rawFeat['value']);
        }

        return array_values($features);
    }
}
