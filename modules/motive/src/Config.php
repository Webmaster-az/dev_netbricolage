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

/**
 * Class Config
 * Enum and util class for all module configs
 *
 * @static setToken(string $randomToken)
 * @static getToken()
 * @static setTriggerSelector(string $selector)
 * @static getTriggerSelector()
 * @static setEngineId(array $engineId)
 * @static getEngineId($id_lang)
 * @static setPlainVariants(string $value)
 * @static getPlainVariants()
 * @static setTimeLimit(string $value)
 * @static getTimeLimit()
 * @static setImageSize(string $value)
 * @static getImageSize()
 * @static setLayerIsolated(string $value)
 * @static getLayerIsolated()
 * @static setFeaturesLimit(int $num)
 * @static getFeaturesLimit()
 * @static setLabelsAvailability(string $value)
 * @static getLabelsAvailability()
 * @static setShopperPrices(string $boolNum)
 * @static getShopperPrices()
 * @static setLastSyncInfo([$id_lang => $value])
 * @static getLastSyncInfo($id_lang)
 * @static setCachedFeaturesIds(string $value)
 * @static getCachedFeaturesIds()
 * @static setMotiveXUrl(string $value)
 * @static getMotiveXUrl()
 * @static setInteroperabilityUrl(string $value)
 * @static getInteroperabilityUrl()
 * @static setPlayboardUrl(string $value)
 * @static getPlayboardUrl()
 * @static setAddJsUsingPrestashopFunctions(string $value)
 * @static getAddJsUsingPrestashopFunctions()
 * @static setForceUnfriendlyUrls(string $value)
 * @static getForceUnfriendlyUrls()
 * @static setProductBatchSize(string $value)
 * @static getProductBatchSize()
 * @static setPerfLinkBuilder(string $value)
 * @static getPerfLinkBuilder()
 * @static setPerfPriceBuilder(string $value)
 * @static getPerfPriceBuilder()
 * @static setTaggingBaseUrl(string $value)
 * @static getTaggingBaseUrl()
 * @static setTaggingTimeout(int $ms)
 * @static getTaggingTimeout()
 * @static setTaggingAddtocart(string $value)
 * @static getTaggingAddtocart()
 * @static setFrontLoaderUrl(string $url)
 * @static getFrontLoaderUrl()
 */
class Config
{
    const PREFIX = 'MOTIVE_';
    const DEFINITION = [
        'token' => [
            'hidden' => true,
            'persistent' => true,
        ],
        'trigger_selector' => [],
        'engine_id' => [
            'lang' => true,
        ],
        'plain_variants' => [
            'default' => 'NONE',
        ],
        'time_limit' => [
            'default' => '30',
        ],
        'image_size' => [
            'default' => 'home_default',
        ],
        'layer_isolated' => [
            'default' => '1',
        ],
        'features_limit' => [
            'default' => '100',
        ],
        'labels_availability' => [
            'default' => LabelsAvailabilityConfig::CUSTOM,
        ],
        'shopper_prices' => [
            'default' => '1',
        ],
        'last_sync_info' => [
            'hidden' => true,
            'lang' => true,
        ],
        'cached_features_ids' => [
            'hidden' => true,
            'default' => '',
        ],
        'motive_x_url' => [
            'default' => 'https://assets.motive.co/motive-x/v2/app.js',
        ],
        'interoperability_url' => [
            'default' => 'https://assets.motive.co/motive-x/v2/interoperability.js',
        ],
        'playboard_url' => [
            'default' => 'https://playboard.motive.co/',
        ],
        'add_js_using_prestashop_functions' => [
            'default' => '1',
        ],
        'force_unfriendly_urls' => [
            'default' => '0',
        ],
        'product_batch_size' => [
            'default' => '1000',
        ],
        'perf_link_builder' => [
            'default' => 'Motive\Prestashop\Builder\Link\PsPartialLinkBuilder',
        ],
        'perf_price_builder' => [
            'default' => 'Motive\Prestashop\Builder\Price\FasterPriceBuilder',
        ],
        'tagging_base_url' => [
            'default' => 'https://tagging-applications-0.api.motive.co',
        ],
        'tagging_timeout' => [
            'default' => '200',
        ],
        'tagging_addtocart' => [
            'default' => 'QUERY_PARAM',
        ],
        'front_loader_url' => [
            'default' => 'https://assets.motive.co/front-loader/prestashop/v1.js',
        ],
    ];

    // Configs Enum
    const TOKEN = 'MOTIVE_TOKEN';
    const TRIGGER_SELECTOR = 'MOTIVE_TRIGGER_SELECTOR';
    const ENGINE_ID = 'MOTIVE_ENGINE_ID';
    const PLAIN_VARIANTS = 'MOTIVE_PLAIN_VARIANTS';
    const TIME_LIMIT = 'MOTIVE_TIME_LIMIT';
    const IMAGE_SIZE = 'MOTIVE_IMAGE_SIZE';
    const LAYER_ISOLATED = 'MOTIVE_LAYER_ISOLATED';
    const FEATURES_LIMIT = 'MOTIVE_FEATURES_LIMIT';
    const LABELS_AVAILABILITY = 'MOTIVE_LABELS_AVAILABILITY';
    const SHOPPER_PRICES = 'MOTIVE_SHOPPER_PRICES';
    const LAST_SYNC_INFO = 'MOTIVE_LAST_SYNC_INFO';
    const CACHED_FEATURES_IDS = 'MOTIVE_CACHED_FEATURES_IDS';
    const MOTIVE_X_URL = 'MOTIVE_MOTIVE_X_URL';
    const INTEROPERABILITY_URL = 'MOTIVE_INTEROPERABILITY_URL';
    const PLAYBOARD_URL = 'MOTIVE_PLAYBOARD_URL';
    const ADD_JS_USING_PRESTASHOP_FUNCTIONS = 'MOTIVE_ADD_JS_USING_PRESTASHOP_FUNCTIONS';
    const FORCE_UNFRIENDLY_URLS = 'MOTIVE_FORCE_UNFRIENDLY_URLS';
    const PRODUCT_BATCH_SIZE = 'MOTIVE_PRODUCT_BATCH_SIZE';
    const PERF_LINK_BUILDER = 'MOTIVE_PERF_LINK_BUILDER';
    const PERF_PRICE_BUILDER = 'MOTIVE_PERF_PRICE_BUILDER';
    const TAGGING_BASE_URL = 'MOTIVE_TAGGING_BASE_URL';
    const TAGGING_TIMEOUT = 'MOTIVE_TAGGING_TIMEOUT';
    const TAGGING_ADDTOCART = 'MOTIVE_TAGGING_ADDTOCART';
    const FRONT_LOADER_URL = 'MOTIVE_FRONT_LOADER_URL';

    /**
     * Uninstall defined configurations
     *
     * @return bool
     */
    public static function uninstall()
    {
        $result = true;
        foreach (array_keys(static::DEFINITION) as $name) {
            if (!static::is($name, 'persistent')) {
                $result = \Configuration::deleteByName(static::name2key($name)) && $result;
            }
        }

        return $result;
    }

    /**
     * Magic method to handle get and set methods over configuration entries.
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public static function __callStatic($method, $args)
    {
        $operation = \Tools::substr($method, 0, 3);
        $name = self::camelToSnake(\Tools::substr($method, 3));
        if (array_key_exists($name, static::DEFINITION)) {
            if ($operation === 'get') {
                $value = static::get($name, static::is($name, 'lang') ? $args[0] : null);

                return static::validate($name, $value);
            } elseif ($operation === 'set') {
                $value = static::validate($name, $args[0]);

                return \Configuration::updateValue(static::name2key($name), $value);
            }
        }

        return null;
    }

    /**
     * Update module configurations with the values in $newConfig
     *
     * @param array $newConfig
     * @param bool $onlyIfUnset - Only set if not already set
     *
     * @return array of errors
     */
    public static function import(array $newConfig, $onlyIfUnset = false)
    {
        $errors = [];
        foreach ($newConfig as $name => $value) {
            try {
                static::importOne($name, $value, $onlyIfUnset);
            } catch (ConfigException $e) {
                $errors[] = $e->getMessage();
            }
        }

        return $errors;
    }

    /**
     * Import single configuration value
     *
     * @param string $name
     * @param string $value
     * @param bool $onlyIfUnset - Only set if not already set
     *
     * @throws ConfigException
     */
    protected static function importOne($name, $value, $onlyIfUnset = false)
    {
        if (!array_key_exists($name, static::DEFINITION)) {
            throw new ConfigException("Invalid configuration name '$name'");
        }

        if (is_array($value) !== static::is($name, 'lang')) {
            throw new ConfigException("Invalid configuration value for '$name'");
        }

        $badIsoCodes = [];
        if (is_array($value)) {
            foreach ($value as $isoCode => $val) {
                if (\Validate::isLanguageIsoCode($isoCode) && ($idLang = \Language::getIdByIso($isoCode))) {
                    $value[$idLang] = $val;
                } else {
                    $badIsoCodes[] = $isoCode;
                }
                unset($value[$isoCode]);
            }
        }

        $key = static::name2key($name);
        if ($onlyIfUnset && static::isConfigSet($key)) {
            return;
        }

        $value = static::validate($name, $value);
        \Configuration::updateValue($key, $value);
        if (!empty($badIsoCodes)) {
            $badIsoCodes = implode(', ', $badIsoCodes);
            throw new ConfigException("Invalid lang(s) '$badIsoCodes' for config '$name'");
        }
    }

    /**
     * Export module configuration to array.
     *
     * @param bool $all include all configs, also hidden ones
     * @param bool $raw export values without validating or setting default
     *
     * @return array
     */
    public static function export($all = false, $raw = false)
    {
        $context = \Context::getContext();
        $result = [];
        foreach (array_keys(static::DEFINITION) as $name) {
            if (!$all && static::is($name, 'hidden')) {
                continue;
            }

            if (!static::is($name, 'lang')) {
                $result[$name] = $raw ? self::get($name, null, true) : static::validate($name, self::get($name));
            } else {
                $values = [];
                foreach (\Language::getLanguages(true, $context->shop->id) as $lang) {
                    $values[$lang['iso_code']] = self::get($name, $lang['id_lang'], $raw);
                }
                $result[$name] = $raw ? $values : static::validate($name, $values);
            }
        }

        return $result;
    }

    /**
     * Get configuration value or its default.
     *
     * @param string $name config name
     * @param int $id_lang config lang
     *
     * @return string
     */
    protected static function get($name, $id_lang = null, $raw = false)
    {
        // Overwrite the config with the query param value if the token is correct.
        $queryParam = "motive-$name" . ($id_lang ? '-' . \Language::getIsoById($id_lang) : '');
        if (\Tools::getIsset($queryParam) && Config::checkToken()) {
            return \Tools::getValue($queryParam);
        }

        $value = \Configuration::get(static::name2key($name), $id_lang);
        if (!$raw && ($value === '' || !is_string($value))) {
            $config = static::DEFINITION[$name];

            return array_key_exists('default', $config) ? $config['default'] : '';
        }

        return $value;
    }

    /**
     * Check if a property of config is true, because empty() does not work on class constants
     *
     * @param $name
     * @param $property
     *
     * @return bool
     */
    protected static function is($name, $property)
    {
        return array_key_exists($name, static::DEFINITION)
            && array_key_exists($property, static::DEFINITION[$name])
            && static::DEFINITION[$name][$property] === true;
    }

    /**
     * Convert Prestashop config Key format to config name format
     *
     * @param $key
     *
     * @return false|string
     */
    protected static function key2name($key)
    {
        return \Tools::substr(\Tools::strtolower($key), \Tools::strlen(static::PREFIX));
    }

    /**
     * Convert config name to Prestashop config Key format
     *
     * @param $name
     *
     * @return string
     */
    protected static function name2key($name)
    {
        return static::PREFIX . \Tools::strtoupper($name);
    }

    /**
     * Converts any "camelCased" into an "snake_case".
     *
     * @param string $str in camelCase
     *
     * @return string in snake_case
     */
    protected static function camelToSnake($str)
    {
        return \Tools::strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $str));
    }

    /**
     * Converts any "snake_case" into an "camelCased".
     *
     * @param string $str in snake_case
     *
     * @return string in camelCased
     */
    protected static function snakeToCamel($str)
    {
        return lcfirst(str_replace('_', '', ucwords($str, '_')));
    }

    /**
     * Check if configuration has value (null, false, '' are unset).
     *
     * @param string $key the config key
     *
     * @return bool if config is set
     */
    public static function isConfigSet($key)
    {
        return (string) \Configuration::get($key) !== '';
    }

    public static function checkToken()
    {
        // Check security token in X-MOTIVE-TOKEN header
        return isset($_SERVER['HTTP_X_MOTIVE_TOKEN'])
            && $_SERVER['HTTP_X_MOTIVE_TOKEN'] === \Configuration::get(Config::TOKEN);
    }

    public static function validate($name, $value)
    {
        $method = static::snakeToCamel("validate_$name");

        return method_exists(static::class, $method)
          ? forward_static_call(__CLASS__ . '::' . $method, $value, static::DEFINITION[$name])
          : $value;
    }

    public static function validateProductBatchSize($value, $config)
    {
        $value = (int) $value;

        return $value > 0 ? $value : (int) $config['default'];
    }

    public static function validatePerfLinkBuilder($value, $config)
    {
        return class_exists($value) ? $value : $config['default'];
    }

    public static function validatePerfPriceBuilder($value, $config)
    {
        return class_exists($value) ? $value : $config['default'];
    }

    public static function validateTaggingTimeout($value, $config)
    {
        $value = (int) $value;

        return $value >= 0 ? $value : (int) $config['default'];
    }
}
