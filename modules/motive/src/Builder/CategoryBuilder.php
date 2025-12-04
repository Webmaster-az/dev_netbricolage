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
use Motive\Prestashop\Model\Category;

if (!defined('_PS_VERSION_')) {
    exit;
}

class CategoryBuilder extends AdditionalProductData
{
    protected $root;
    protected $categories = [];

    public function __construct(\Context $context)
    {
        parent::__construct($context);
        $this->root = $context->shop->getCategory();

        // Preload categories
        foreach ($this->fetchCategories($context->shop->id, $context->language->id) as $category) {
            $this->categories[$category['id_category']] = $category;
        }
    }

    /**
     * Return category path as array of categories until root one.
     *
     * @param $idCategory
     *
     * @return array
     */
    public function getPathAsArray($idCategory)
    {
        // Category not obtained on CategoryBuilder creation, returning empty array.
        if (empty($this->categories[$idCategory])) {
            return [];
        }

        $category = $this->categories[$idCategory];
        $categories = [];
        if ($idCategory != $this->root && $category['is_root_category'] !== '1') {
            $categories = $this->getPathAsArray($category['id_parent']);
        }
        $categories[] = $category;

        return $categories;
    }

    /**
     * Return category path as string
     *
     * @param $idCategory
     *
     * @return string
     */
    public function getPathAsString($idCategory)
    {
        // The category no longer exists, but it still has products associated with it.
        if (empty($this->categories[$idCategory])) {
            return '';
        }

        $category = &$this->categories[$idCategory];
        if (empty($category['path'])) {
            $category['path'] = $category['id_category'] . Category::SEPARATOR . $category['name'];
            if ($idCategory != $this->root && $category['is_root_category'] !== '1') {
                $parentPath = $this->getPathAsString($category['id_parent']);
                if ($parentPath === '') {
                    // Invalid category, it is not in the category tree
                    unset($this->categories[$idCategory]);

                    return '';
                }
                $category['path'] = $parentPath . Category::TREE_SEPARATOR . $category['path'];
            }
        }

        return $category['path'];
    }

    /**
     * Return raw full category by its id
     *
     * @param $idCategory
     *
     * @return array|null
     */
    public function getCategory($idCategory)
    {
        // The category no longer exists, but it still has products associated with it.
        if (empty($this->categories[$idCategory])) {
            return null;
        }

        return $this->categories[$idCategory];
    }

    /**
     * Returns an array of all categories in PrestaShop for a shop Id and language ID
     *
     * @param int $idShop
     * @param int $idLang
     *
     * @return array
     */
    public function fetchCategories($idShop, $idLang)
    {
        $idShop = (int) $idShop;
        $idLang = (int) $idLang;

        if (\Group::isFeatureActive()) {
            $groups = [
                (int) \Configuration::get('PS_UNIDENTIFIED_GROUP'),
                (int) \Configuration::get('PS_CUSTOMER_GROUP'),
            ];
            $join = ' INNER JOIN `' . _DB_PREFIX_ . 'category_group` cg
                ON c.`id_category` = cg.`id_category`
                    AND cg.`id_group` IN (' . implode(',', $groups) . ')';
            $groupBy = ' GROUP BY c.`id_category`';
        } else {
            $join = '';
            $groupBy = '';
        }

        $sql = 'SELECT c.id_category, c.id_parent, c.is_root_category, cl.name, cl.link_rewrite
                FROM `' . _DB_PREFIX_ . 'category` c
                INNER JOIN `' . _DB_PREFIX_ . 'category_shop` cs
                    ON cs.id_category = c.id_category AND cs.id_shop = ' . $idShop . '
                LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl
                    ON c.`id_category` = cl.`id_category` AND cl.id_shop = cs.id_shop
                ' . $join . '
                RIGHT JOIN `' . _DB_PREFIX_ . 'category` c2
                    ON c2.`id_category` = ' . $this->root . ' AND c.`nleft` >= c2.`nleft` AND c.`nright` <= c2.`nright`
                WHERE `id_lang` = ' . $idLang .
                ' AND ( c.`id_category` = ' . $this->root . ' OR ( c.`active` = 1 AND c.`id_parent` > 0 ))' .
                $groupBy;

        return \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    protected function buildQuery($fromIdProduct, $toIdProduct)
    {
        return '
            SELECT 
                cp.id_product AS id,
                cp.id_category
            FROM ' . _DB_PREFIX_ . 'category_product AS cp
            JOIN ' . _DB_PREFIX_ . "product_shop AS ps
              ON cp.id_product = ps.id_product
              AND ps.id_shop = {$this->context->shop->id}
              AND ps.active = 1
              AND ps.visibility IN ('both', 'search')
            WHERE cp.id_product BETWEEN $fromIdProduct AND $toIdProduct
            ORDER BY cp.id_product ASC
        ";
    }

    /**
     * Returns the product's categories path
     *
     * @param array $rows
     *
     * @return string[] array of categories path
     */
    public function map($rows)
    {
        $productCategories = [];
        foreach ($rows as $row) {
            $path = $this->getPathAsString($row['id_category']);
            if ($path !== '') {
                $productCategories[] = $path;
            }
        }

        return $productCategories;
    }
}
