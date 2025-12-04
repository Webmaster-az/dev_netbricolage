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

class Currency
{
    /** @var int|string Currency's ID */
    public $id;
    /** @var string Currency's name */
    public $name;
    /** @var string Currency's ISO 4217 code */
    public $iso_code;
    /** @var string Currency's symbol */
    public $symbol;

    /**
     * Static build method
     *
     * @param int|string $id
     * @param string $name
     * @param string $iso_code
     * @param string $symbol
     *
     * @return Currency
     */
    public static function build($id, $name, $iso_code, $symbol)
    {
        $obj = new static();
        $obj->id = $id;
        $obj->name = $name;
        $obj->iso_code = $iso_code;
        $obj->symbol = $symbol;

        return $obj;
    }
}
