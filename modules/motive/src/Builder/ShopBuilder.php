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
use Language as PsLanguage;
use Motive\Prestashop\Model\Currency;
use Motive\Prestashop\Model\Language;
use Motive\Prestashop\Model\Shop;
use Shop as PsShop;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ShopBuilder
{
    /**
     * Shop builder from Prestashop Shop.
     *
     * @param PsShop $shop
     *
     * @return Shop
     */
    public static function fromObject(PsShop $shop)
    {
        return static::fromIdAndName($shop->id, $shop->name);
    }

    /**
     * Shop builder from array.
     *
     * @param array $shop
     *
     * @return Shop
     */
    public static function fromArray(array $shop)
    {
        return static::fromIdAndName(isset($shop['id_shop']) ? $shop['id_shop'] : $shop['id'], $shop['name']);
    }

    /**
     * Shop builder from Prestashop Shop ID and Name.
     *
     * @param int $idShop
     * @param string $name
     *
     * @return Shop
     */
    public static function fromIdAndName($idShop, $name)
    {
        $shop = new Shop();
        $shop->id = $idShop;
        $shop->name = $name;
        $shop->url = self::getShopUrl($idShop);
        $shop->languages = static::getShopActiveLanguages($idShop);
        $shop->currencies = static::getShopActiveCurrencies($idShop);

        return $shop;
    }

    /**
     * Returns the shop url
     *
     * @param int $idShop Shop ID
     *
     * @return string
     */
    public static function getShopUrl($idShop)
    {
        $ssl = (\Configuration::get('PS_SSL_ENABLED') && \Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
        $shop = new PsShop($idShop);
        $base = $ssl ? 'https://' . $shop->domain_ssl : 'http://' . $shop->domain;

        return $base . $shop->getBaseURI();
    }

    /**
     * Returns the available active languages for the selected shop
     *
     * @param int $idShop Shop ID
     *
     * @return Language[] array of active languages
     */
    protected static function getShopActiveLanguages($idShop)
    {
        $languages = PsLanguage::getLanguages(true, $idShop);

        return array_map(['Motive\Prestashop\Builder\LanguageBuilder', 'fromArray'], $languages);
    }

    /**
     * Returns the available active currencies for the selected shop
     *
     * @param int $idShop Shop ID
     *
     * @return Currency[] array of active currencies
     */
    protected static function getShopActiveCurrencies($idShop)
    {
        $currencies = array_filter(PsCurrency::getCurrenciesByIdShop($idShop), function ($currency) {
            return (bool) $currency['active'];
        });

        return array_values(array_map(['Motive\Prestashop\Builder\CurrencyBuilder', 'fromArray'], $currencies));
    }
}
