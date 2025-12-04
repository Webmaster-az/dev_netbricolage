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

use Motive\Prestashop\Model\Attribute;

if (!defined('_PS_VERSION_')) {
    exit;
}

class AttributeBuilder
{
    /**
     * Returns the available attributes for the selected shop & lang
     *
     * @param int $idShop
     * @param int $idLang
     *
     * @return Attribute[] array of options
     */
    public static function fetchForShop($idShop, $idLang)
    {
        $idShop = (int) $idShop;
        $idLang = (int) $idLang;

        $sql = '
            SELECT
                ag.id_attribute_group AS a_id,
                agl.public_name       AS a_public_name,
                ag.is_color_group     AS a_is_color
            FROM ' . _DB_PREFIX_ . 'attribute_group AS ag
                INNER JOIN ' . _DB_PREFIX_ . "attribute_group_lang AS agl
                    ON (agl.id_attribute_group = ag.id_attribute_group AND agl.id_lang = $idLang)
                INNER JOIN " . _DB_PREFIX_ . "attribute_group_shop AS ags
                    ON (ags.id_attribute_group = ag.id_attribute_group AND ags.id_shop = $idShop)
            ORDER BY a_id
        ";

        $results = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        $attributes = [];
        foreach ($results as $r) {
            if (empty($r['a_id'])) {
                continue;
            }
            $id = self::getKey($r['a_id']);
            $attributes[] = new Attribute($id, $r['a_public_name'], $r['a_is_color'] == '1');
        }

        return $attributes;
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
        return "a$id";
    }
}
