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

if (!defined('_PS_VERSION_')) {
    exit;
}

abstract class AdditionalProductData
{
    /** @var \Context */
    protected $context;
    protected $data = [];

    /**
     * ProductData constructor.
     *
     * @param \Context $context
     */
    public function __construct(\Context $context)
    {
        $this->context = $context;
    }

    /**
     * Pre-fetch product data
     *
     * @param int $fromIdProduct (included)
     * @param int $toIdProduct (included)
     */
    public function prefetch($fromIdProduct, $toIdProduct)
    {
        $fromIdProduct = (int) $fromIdProduct;
        $toIdProduct = (int) $toIdProduct;

        $sql = $this->buildQuery($fromIdProduct, $toIdProduct);
        $rows = \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        $this->data = [];
        foreach ($rows as &$row) {
            $this->data[$row['id']][] = &$row;
        }
    }

    /**
     * Returns a SQL query to prefetch data for a range of Id product.
     * The query must have an "id" column
     *
     * @param int $fromIdProduct (included)
     * @param int $toIdProduct (included)
     *
     * @return string The SQL query
     */
    abstract protected function buildQuery($fromIdProduct, $toIdProduct);

    /**
     * Returns the the data for the $id.
     *
     * @param int $id
     *
     * @return mixed The data
     */
    public function get($id)
    {
        return empty($this->data[$id]) ? [] : $this->map($this->data[$id]);
    }

    /**
     * Converts the rows data to the expected output type.
     *
     * @param $rows - rows associated to a single product id
     *
     * @return mixed
     */
    protected function map($rows)
    {
        return $rows;
    }
}
