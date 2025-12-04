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

use Currency as PsCurrency;
use Motive\Prestashop\Model\Currency;

if (!defined('_PS_VERSION_')) {
    exit;
}

class CurrencyBuilder
{
    /**
     * Currency builder from Prestashop Currency.
     *
     * @param PsCurrency $currencyObj
     *
     * @return Currency
     */
    public static function fromObject(PsCurrency $currencyObj)
    {
        $currency = new Currency();
        $currency->id = $currencyObj->id;
        $currency->name = $currencyObj->name;
        $currency->iso_code = $currencyObj->iso_code;
        $currency->symbol = property_exists($currencyObj, 'symbol') ? $currencyObj->symbol : null;

        return $currency;
    }

    /**
     * Currency builder from array.
     *
     * @param array $currencyArr
     *
     * @return Currency
     */
    public static function fromArray(array $currencyArr)
    {
        $currency = new Currency();
        $currency->id = isset($currencyArr['id_currency']) ? $currencyArr['id_currency'] : $currencyArr['id'];
        $currency->name = $currencyArr['name'];
        $currency->iso_code = $currencyArr['iso_code'];
        $currency->symbol = isset($currencyArr['symbol']) ? $currencyArr['symbol'] : null;

        return $currency;
    }

    /**
     * Currency builder from Prestashop Currency Id.
     *
     * @param int $idCurrency
     *
     * @return Currency
     */
    public static function fromId($idCurrency)
    {
        try {
            $currencyObj = new PsCurrency($idCurrency);
            if (!\Validate::isLoadedObject($currencyObj)) {
                return null;
            }

            return static::fromObject($currencyObj);
        } catch (\Exception $e) {
            return null;
        }
    }
}
