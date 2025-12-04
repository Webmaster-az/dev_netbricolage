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

class Code
{
    /** @var string Product reference */
    public $reference;

    /** @var string|null Product gtin (barcode) */
    public $gtin;

    /** @var string|null Product mpn (Manufacturer part number) */
    public $mpn;

    /**
     * Code constructor.
     *
     * @param string $reference
     * @param string $gtin
     * @param string $mpn
     */
    public function __construct($reference, $gtin = null, $mpn = null)
    {
        $this->reference = $reference;
        $this->gtin = $gtin;
        $this->mpn = $mpn;
    }

    /**
     * Code builder from multiple codes.
     *
     * @param string $reference
     * @param string|null $ean13
     * @param string|null $isbn
     * @param string|null $upc
     * @param string|null $mpn
     *
     * @return static
     */
    public static function build($reference, $ean13 = null, $isbn = null, $upc = null, $mpn = null)
    {
        if (!empty($ean13)) {
            $gtin = $ean13;
        } elseif (!empty($isbn)) {
            $gtin = $isbn;
        } elseif (!empty($upc)) {
            $gtin = $upc;
        } else {
            $gtin = null;
        }

        return new static($reference, $gtin, $mpn);
    }
}
