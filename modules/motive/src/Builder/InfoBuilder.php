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

use Motive\Prestashop\Model\CatalogInfo;
use Motive\Prestashop\Model\Info;
use Motive\Prestashop\Model\Platform;
use Motive\Prestashop\MotiveApiController;

if (!defined('_PS_VERSION_')) {
    exit;
}

class InfoBuilder
{
    /**
     * Info builder from options.
     * Returns info about current PrestaShop instance
     *
     * @param \Context $context
     *
     * @return Info
     */
    public static function build(\Context $context)
    {
        $m = \Module::getInstanceByName('motive');
        $info = new Info();
        $info->source = [
            'version' => $m->version,
            'first_install_date' => static::getFirstInstallDate(),
            'platform' => Platform::build(),
            'software' => [
                'name' => 'PrestaShop',
                'version' => _PS_VERSION_,
                'modules' => self::getModuleList(),
            ],
        ];
        $info->options = [
            'default_language' => LanguageBuilder::fromId(\Configuration::get('PS_LANG_DEFAULT')),
            'default_currency' => CurrencyBuilder::fromId(\Configuration::get('PS_CURRENCY_DEFAULT')),
            'shop' => ShopBuilder::fromObject($context->shop),
            'other_shops' => ShopListBuilder::build($context),
            'image' => [
                'logo' => [
                    'url' => \Tools::getShopDomainSsl(true) . _PS_IMG_ . \Configuration::get('PS_LOGO'),
                    'width' => (int) \Configuration::get('SHOP_LOGO_WIDTH'),
                    'height' => (int) \Configuration::get('SHOP_LOGO_HEIGHT'),
                ],
                'settings' => self::getImageSizeList(),
            ],
        ];
        $info->urls = static::getUrls($context);
        $info->metrics = static::getMetrics($context);
        $info->catalogs = static::getCatalogs($context, $info->options['default_currency']);

        return $info;
    }

    /**
     * Create CatalogInfo objects for current shop.
     *
     * @param \Context $context
     * @param $currency
     *
     * @return array|string
     */
    protected static function getCatalogs(\Context $context, $currency)
    {
        $catalogs = [];
        foreach (\Language::getLanguages(true, $context->shop->id) as $lang) {
            $catalogInfo = new CatalogInfo();
            $catalogInfo->id = $lang['id_lang'];
            $catalogInfo->code = $lang['iso_code'];
            $catalogInfo->name = $lang['name'];
            $catalogInfo->locale = empty($lang['locale']) ? $lang['language_code'] : $lang['locale'];
            $catalogInfo->currency = $currency;
            $catalogs[$lang['iso_code']] = $catalogInfo;
        }

        return $catalogs;
    }

    /**
     * Create the URL for the module controller for one or more languages.
     *
     * @param \Context $context
     * @param $name
     * @param $multiLang
     *
     * @return array|string
     */
    protected static function controllerUrl(\Context $context, $name, $multiLang)
    {
        if (!$multiLang) {
            return MotiveApiController::getUrl($name);
        }

        $urls = [];
        foreach (\Language::getLanguages(true, $context->shop->id) as $lang) {
            $urls[$lang['iso_code']] =
            MotiveApiController::getUrl($name, [], $lang['id_lang']);
        }

        return $urls;
    }

    /**
     * Get list of module urls
     *
     * @param \Context $context
     *
     * @return array
     */
    public static function getUrls(\Context $context)
    {
        return [
            'check' => self::controllerUrl($context, 'check', false),
            'config' => self::controllerUrl($context, 'config', false),
            'config-if-unset' => self::controllerUrl($context, 'config-if-unset', false),
            'info' => self::controllerUrl($context, 'info', true),
            'schema' => self::controllerUrl($context, 'schema', true),
            'feed' => self::controllerUrl($context, 'feed', true),
        ];
    }

    /**
     * Get installed modules
     *
     * @return array
     */
    public static function getModuleList()
    {
        $sql = 'SELECT `name`, `version`, `active` FROM ' . _DB_PREFIX_ . 'module ORDER BY `name`';

        return \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    /**
     * Get date of first time Motive was installed.
     *
     * @return string|false
     */
    public static function getFirstInstallDate()
    {
        $sql = 'SELECT MIN(`date_add`) FROM `' . _DB_PREFIX_ . "configuration` WHERE `name` LIKE 'MOTIVE_%'";
        $date = \Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);

        if (empty($date)) {
            return null;
        }

        $dateTime = new \DateTime($date);
        $dateTime->setTimezone(new \DateTimeZone('UTC'));

        return $dateTime->format('c');
    }

    /**
     * Get catalog metrics.
     *
     * @param \Context $context
     *
     * @return array
     */
    public static function getMetrics(\Context $context)
    {
        $searchableProducts = 'SELECT COUNT(*)
            FROM `' . _DB_PREFIX_ . "product_shop`
            WHERE `id_shop` = {$context->shop->id}
              AND `active` = 1
              AND `visibility` IN ('both', 'search')";

        return [
            'searchable_products' => \Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($searchableProducts),
        ];
    }

    /**
     * Get available product image sizes
     *
     * @return array
     */
    public static function getImageSizeList()
    {
        $sql = "SELECT `id_image_type` as 'id', `name`, `width`, `height`
                FROM " . _DB_PREFIX_ . 'image_type
                WHERE `products` = 1
                ';

        return \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }

    /**
     * Get size of tables
     *
     * @return array
     */
    public static function getDbtables()
    {
        $tables = ['attribute%', 'category%', 'feature%', 'image%', 'module%', 'configuration%', 'tag%', 'meta', 'product%', 'shop%', 'stock%', 'supplier%', 'manufacturer%', 'specific_price%', 'accessory', 'cart_rule%', 'cms%', 'customization%', 'group%', 'lang', 'layered%', 'pack', 'zone'];

        $where = [];
        foreach ($tables as $table) {
            $where[] = "Name LIKE '" . _DB_PREFIX_ . $table . "'";
        }

        $sql = 'SHOW TABLE STATUS WHERE ' . implode(' OR ', $where);

        return \Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }
}
