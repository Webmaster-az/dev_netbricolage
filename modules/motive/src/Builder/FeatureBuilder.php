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
use Motive\Prestashop\Model\Feature;

if (!defined('_PS_VERSION_')) {
    exit;
}

class FeatureBuilder
{
    /** @var \Context */
    protected $context;

    protected $limit;

    public function __construct(\Context $context)
    {
        $this->context = $context;
        $this->limit = (int) Config::getFeaturesLimit();
    }

    /**
     * Return the top features
     *
     * @return Feature[] array of features
     */
    public function fetch()
    {
        $features = [];

        // Cache Ids
        $rows = $this->getRows();
        Config::setCachedFeaturesIds(implode(',', array_column($rows, 'f_id')));

        foreach ($rows as $r) {
            $features[] = new Feature(self::getKey($r['f_id']), $r['f_name']);
        }

        return $features;
    }

    /**
     * Return the top features Ids
     *
     * @return string of csv features IDs
     */
    public function fetchIds()
    {
        // Retrieve cached Ids
        $ids = Config::getCachedFeaturesIds();
        if (!empty($ids)) {
            return $ids;
        }

        $rows = $this->getRows();

        return implode(',', array_column($rows, 'f_id'));
    }

    /**
     * Get rows
     *
     * @return array of features
     */
    protected function getRows()
    {
        if ($this->limit === 0) {
            return [];
        }

        $idShop = (int) $this->context->shop->id;
        $idLang = (int) $this->context->language->id;

        $sqlLimit = $this->limit > 0 ? "LIMIT {$this->limit}" : '';

        $sql = '
            SELECT
                f.id_feature AS f_id,
                fl.name      AS f_name
            FROM ' . _DB_PREFIX_ . 'feature AS f
                INNER JOIN ' . _DB_PREFIX_ . "feature_lang AS fl
                    ON (fl.id_feature = f.id_feature AND fl.id_lang = $idLang)
                INNER JOIN " . _DB_PREFIX_ . "feature_shop AS fs
                    ON (fs.id_feature = f.id_feature AND fs.id_shop = $idShop)
                INNER JOIN " . _DB_PREFIX_ . "feature_product AS fp
                    ON f.id_feature = fp.id_feature
            WHERE f.id_feature > 0
            GROUP BY f.id_feature, fl.name
            ORDER BY COUNT(fp.id_product) DESC
            $sqlLimit
        ";

        return \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    /**
     * Creates an unique identifier
     *
     * @param string $id entity id
     *
     * @return string
     */
    public static function getKey($id)
    {
        return "f$id";
    }
}
