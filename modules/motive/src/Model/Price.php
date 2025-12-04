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

namespace Motive\Prestashop\Model;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Price
{
    /** @var float */
    public $regular;

    /** @var float */
    public $on_sale;

    /**
     * Returns product price.
     *
     * @param float price without discounts
     * @param float|null price with discounts
     *
     * @return Price Product price
     */
    public static function build($regular, $on_sale = null)
    {
        $price = new Price();
        $price->regular = $regular;
        $price->on_sale = $on_sale === null || static::equal($on_sale, $regular) ? null : $on_sale;

        return $price;
    }

    /**
     * Compare 2 price floats and check if its diff is less than the given $epsilon.
     *
     * @param float price value
     * @param float price value
     *
     * @return bool true if they are "equal"
     */
    public static function equal($a, $b, $epsilon = 0.01)
    {
        return abs($a - $b) < $epsilon;
    }
}
