<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

if (!class_exists('JprestaCacheWarmerSettings')) {

    class JprestaCacheWarmerSettings
    {
        public $id_shop;

        public $languages;

        public $currencies;

        public $groups;

        public $countries;

        public $devices;

        public $tax_managers;

        public $specifics;

        /**
         * @var array List of contexts to warmp [language, currency, device, country, specifics]
         */
        public $contexts;

        /**
         * @var array List of controllers name that must be warmed up
         */
        public $controllers;

        public $pages_count;

        /**
         * JprestaCacheWarmerSettings constructor.
         * @param $id_shop
         */
        public function __construct($id_shop)
        {
            $this->id_shop = $id_shop;
        }

        private function init()
        {
            $this->initLanguages();
            $this->initCurrencies();
            $this->initGroups();
            $this->initCountries();
            $this->initDevices();
            $this->initTaxManagers();
            $this->initSpecifics();
            $this->initContexts();
            $this->initControllers();
            $this->pages_count = $this->getPagesCount();
            return $this;
        }

        private function initContexts() {
            if (!is_array($this->contexts) || count($this->contexts) === 0) {
                $this->contexts = array();
            }
            // Remove contexts that have inactive parameter value and also duplicates
            $contextKeys = array();
            foreach ($this->contexts as $index => $context) {
                if (!array_key_exists($context['language'], $this->languages)) {
                    unset($this->contexts[$index]);
                    continue;
                }
                if (!array_key_exists($context['currency'], $this->currencies)) {
                    unset($this->contexts[$index]);
                    continue;
                }
                if (!array_key_exists($context['country'], $this->countries)) {
                    unset($this->contexts[$index]);
                    continue;
                }
                if (!array_key_exists($context['device'], $this->devices)) {
                    unset($this->contexts[$index]);
                    continue;
                }
                if (!array_key_exists($context['group'], $this->groups)) {
                    unset($this->contexts[$index]);
                    continue;
                }
                if (!array_key_exists($context['specifics'], $this->specifics)) {
                    unset($this->contexts[$index]);
                    continue;
                }
                $contextKey = implode('|', [$context['language'], $context['currency'], $context['country'], $context['device'], $context['group'], $context['specifics']]);
                if (array_key_exists($contextKey, $contextKeys)) {
                    unset($this->contexts[$index]);
                }
                else {
                    $contextKeys[$contextKey] = 1;
                }
            }
            if (count($this->contexts) === 0) {
                // Creates default contexts
                $defaultLanguage = null;
                foreach ($this->languages as $language) {
                    if ($language['default']) {
                        $defaultLanguage = $language['value'];
                        break;
                    }
                }
                $defaultCurrency = null;
                foreach ($this->currencies as $currency) {
                    if ($currency['default']) {
                        $defaultCurrency = $currency['value'];
                        break;
                    }
                }
                $defaultGroup = null;
                foreach ($this->groups as $group) {
                    if ($group['default']) {
                        $defaultGroup = $group['value'];
                        break;
                    }
                }

                $defaultCountry = Country::getIsoById((int)Configuration::get('PS_COUNTRY_DEFAULT'));
                if ($this->isCountryOthers($defaultCountry)) {
                    $defaultCountry = 'OTHERS';
                }

                $desktopContext = [
                    'language' => $defaultLanguage,
                    'country' => $defaultCountry,
                    'currency' => $defaultCurrency,
                    'device' => 'desktop',
                    'group' => $defaultGroup,
                    'specifics' => null
                ];
                $this->contexts[] = $desktopContext;
            }
        }

        /**
         * @param $controllers_names string[]
         */
        public function checkControllers($controllers_names)
        {
            foreach ($this->controllers as $controller_name => &$managed_controller) {
                $managed_controller['checked'] = in_array($controller_name, $controllers_names);
            }
        }

        private function initControllers() {
            if (!$this->controllers || !is_array($this->controllers)) {
                $this->controllers = array();
                foreach (PageCache::$managed_controllers as $managed_controller) {
                    $this->controllers[$managed_controller] = ['checked' => true, 'disabled' => false, 'count' => 1];
                }
            }
            $shop = new Shop($this->id_shop);
            foreach ($this->controllers as $controller_name => &$managed_controller) {
                if (!Configuration::get('pagecache_' . $controller_name)) {
                    $managed_controller['checked'] = false;
                    $managed_controller['disabled'] = true;
                }
                else {
                    $managed_controller['disabled'] = false;
                }

                if (in_array($controller_name, ['index', 'newproducts', 'pricesdrop', 'contact', 'sitemap', 'bestsales'])) {
                    $managed_controller['count'] = 1;
                }
                elseif ($controller_name === 'manufacturer') {
                    $sql = 'SELECT COUNT(c.id_manufacturer)
                        FROM `' . _DB_PREFIX_ . 'manufacturer` c' . $shop->addSqlAssociation('manufacturer', 'c') . '
                        WHERE c.`active` = 1';
                    $managed_controller['count'] = (int) JprestaUtils::dbGetValue($sql) + 1;
                }
                elseif ($controller_name === 'supplier') {
                    $sql = 'SELECT COUNT(c.id_supplier)
                        FROM `' . _DB_PREFIX_ . 'supplier` c' . $shop->addSqlAssociation('supplier', 'c') . '
                        WHERE c.`active` = 1';
                    $managed_controller['count'] = (int) JprestaUtils::dbGetValue($sql) + 1;
                }
                elseif ($controller_name === 'product') {
                    // It is an estimation, we should add 1 for each product having at least on declination because
                    // it will generate 1 more URL, substract all customizable products, etc.
                    // But we just want an idea of how many product this shop contains
                    $sql = 'SELECT COUNT(*)
                        FROM `' . _DB_PREFIX_ . 'product` p' . $shop->addSqlAssociation('product', 'p') . '
                        LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa   ON p.id_product=pa.id_product' . '
                        WHERE product_shop.`active` = 1';
                    $managed_controller['count'] = (int) JprestaUtils::dbGetValue($sql);
                }
                elseif ($controller_name === 'category') {
                    $sql = 'SELECT COUNT(c.id_category)
                        FROM `' . _DB_PREFIX_ . 'category` c' . $shop->addSqlAssociation('category', 'c') . '
                        WHERE c.`active` = 1 AND c.is_root_category = 0 AND c.id_parent > 0';
                    $managed_controller['count'] = (int) JprestaUtils::dbGetValue($sql);
                }
                elseif ($controller_name === 'cms') {
                    // CMS
                    $sql = 'SELECT COUNT(c.id_cms)
                        FROM `' . _DB_PREFIX_ . 'cms` c' . $shop->addSqlAssociation('cms', 'c') . '
                        WHERE c.`active` = 1';
                    $managed_controller['count'] = (int) JprestaUtils::dbGetValue($sql);

                    // CMS CATEGORIES
                    $sql = 'SELECT COUNT(c.id_cms_category)
                        FROM `' . _DB_PREFIX_ . 'cms_category` c' . $shop->addSqlAssociation('cms_category', 'c') . '
                        WHERE c.`active` = 1';
                    $managed_controller['count'] += (int) JprestaUtils::dbGetValue($sql);
                }
            }
        }

        /**
         * @return int Number of different pages
         * @throws PrestaShopDatabaseException
         */
        public function getPagesCount()
        {
            $pageCount = 0;
            foreach ($this->controllers as $managed_controller) {
                if ($managed_controller['checked']) {
                    $pageCount += $managed_controller['count'];
                }
            }
            return $pageCount;
        }

        private function initLanguages()
        {
            $this->languages = array();
            foreach (Language::getLanguages(true, $this->id_shop) as $language) {
                $this->languages[$language['iso_code']] = array();
                $this->languages[$language['iso_code']]['label'] = preg_replace('/\s\(.*\)$/', '', $language['name']);
                $this->languages[$language['iso_code']]['value'] = $language['iso_code'];
                $this->languages[$language['iso_code']]['default'] = $language['id_lang'] == Configuration::get('PS_LANG_DEFAULT', null, null, $this->id_shop);
            }
            uasort($this->languages, array('self', 'sortContext'));
        }

        private function initCurrencies()
        {
            $this->currencies = array();
            foreach (Currency::getCurrenciesByIdShop($this->id_shop) as $currency) {
                if ($currency['active']) {
                    $this->currencies[$currency['iso_code']] = array();
                    $this->currencies[$currency['iso_code']]['label'] = $currency['iso_code'];
                    $this->currencies[$currency['iso_code']]['value'] = $currency['iso_code'];
                    $this->currencies[$currency['iso_code']]['default'] = $currency['id_currency'] == Configuration::get('PS_CURRENCY_DEFAULT', null, null, $this->id_shop);
                }
            }
            uasort($this->currencies, array('self', 'sortContext'));
        }

        private function initGroups()
        {
            $this->groups = array();
            $hasDefault = false;

            // Take all fake users already created (don't generate all combinations)
            // Only if cache is enabled for logged in users
            if (!Configuration::get('pagecache_skiplogged')) {
                $anonymous_id_group = (int) Configuration::get('PS_UNIDENTIFIED_GROUP');

                // Force the creation of the fake user for default customer (visitor)
                PageCache::getOrCreateCustomerWithSameGroups(new Customer());

                $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'customer` WHERE `id_shop` =' . (int)$this->id_shop . ' AND `firstname` = \'fake-user-for-pagecache\'';
                $fakeUsers = DB::getInstance()->executeS($sql);
                foreach ($fakeUsers as $fakeUser) {
                    $fakeCustomer = new Customer($fakeUser['id_customer']);
                    $fakeCustomerRecursive = PageCache::getOrCreateCustomerWithSameGroups($fakeCustomer, true);
                    if ($fakeCustomerRecursive->id != $fakeCustomer->id) {
                        // $fakeCustomer is not used anymore
                        continue;
                    }
                    $this->groups[$fakeUser['email']] = array();
                    $this->groups[$fakeUser['email']]['label'] = $this->getGroupName($fakeUser['id_customer'], (int) $fakeUser['id_default_group']);
                    $this->groups[$fakeUser['email']]['value'] = $fakeUser['email'];
                    $this->groups[$fakeUser['email']]['default'] = $anonymous_id_group === (int) $fakeUser['id_default_group'];
                    $hasDefault = $hasDefault || $this->groups[$fakeUser['email']]['default'];
                    $this->groups[$fakeUser['email']]['count'] = $this->getGroupCount($fakeUser['id_customer']);
                }
            }

            if (!$hasDefault) {
                // Add anonymous group
                $this->groups[''] = array();
                $this->groups['']['label'] = 'Default';
                $this->groups['']['value'] = '';
                $this->groups['']['default'] = true;
            }

            uasort($this->groups, array('self', 'sortContext'));
        }

        private function getGroupName($id_customer, $id_default_group)
        {
            $groupList = '';
            $groupIds = Customer::getGroupsStatic($id_customer);
            // Put the default group at the beginning
            foreach ($groupIds as $arrayKey => $groupId) {
                if ($groupId === $id_default_group) {
                    $groupIds[$arrayKey] = $groupIds[0];
                    $groupIds[0] = $id_default_group;
                }
            }
            foreach ($groupIds as $index => $groupId) {
                $group = new Group($groupId);
                if (!empty($groupList)) {
                    $groupList .= ', ';
                }
                if (is_array($group->name)) {
                    if (array_key_exists(Context::getContext()->cookie->id_lang, $group->name)) {
                        $groupList .= $group->name[Context::getContext()->cookie->id_lang];
                    }
                    else {
                        $groupList .= $group->name[0];
                    }
                } else {
                    $groupList .= $group->name;
                }
                if ($index === 0) {
                    $groupList .= '*';
                }
            }
            return $groupList;
        }

        private function getGroupCount($id_customer)
        {
            $groupIds = Customer::getGroupsStatic($id_customer);
            return JprestaUtils::dbGetValue('
			SELECT COUNT(*)
			FROM (SELECT cg.id_customer FROM `' . _DB_PREFIX_ . 'customer_group` cg
			LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (cg.`id_customer` = c.`id_customer`)
			WHERE cg.`id_group` IN (' . pSQL(implode(',', $groupIds)) . ')
			AND c.`deleted` != 1 AND c.`active` = 1
			GROUP BY cg.id_customer
			HAVING SUM(1)=' . (int)count($groupIds) . '
			) subtable');
        }

        private function initCountries()
        {
            $this->countries = array();

            $defaultCountryIso = Country::getIsoById((int)Configuration::get('PS_COUNTRY_DEFAULT'));
            $this->countries[$defaultCountryIso] = array();
            $this->countries[$defaultCountryIso]['label'] = Country::getNameById(Context::getContext()->cookie->id_lang, (int)Configuration::get('PS_COUNTRY_DEFAULT'));
            $this->countries[$defaultCountryIso]['value'] = $defaultCountryIso;
            $this->countries[$defaultCountryIso]['default'] = true;

            $haveOthers = false;
            $currentCacheKeyCountryConf = json_decode(JprestaUtils::getConfigurationByShopId('pagecache_cachekey_countries', Shop::getContextShopID(), '{}'), true);
            foreach ($currentCacheKeyCountryConf as $id_country => $country_conf) {
                if (!$country_conf['specific_cache']) {
                    $haveOthers = true;
                    break;
                }
            }
            if ($haveOthers) {
                $this->countries['OTHERS'] = array();
                $this->countries['OTHERS']['label'] = 'Countries without specific cache';
                $this->countries['OTHERS']['value'] = 'OTHERS';
                $this->countries['OTHERS']['default'] = false;
            }
            foreach ($currentCacheKeyCountryConf as $id_country => &$country_conf) {
                $country = new Country($id_country, Context::getContext()->cookie->id_lang);
                if ($country->iso_code === $defaultCountryIso && !$country_conf['specific_cache']) {
                    // The default country does not have specific cache so it will be included in OTHERS which becomes the default value
                    $this->countries['OTHERS']['label'] = 'Countries without specific cache (' . $this->countries[$defaultCountryIso]['label'] . ', ...)';
                    $this->countries['OTHERS']['value'] = 'OTHERS';
                    unset($this->countries[$country->iso_code]);
                }
                elseif ($country_conf['specific_cache']) {
                    // This country has a specific cache so add to the list
                    $this->countries[$country->iso_code] = array();
                    $this->countries[$country->iso_code]['label'] = $country->name;
                    $this->countries[$country->iso_code]['value'] = $country->iso_code;
                    $this->countries[$country->iso_code]['default'] = false;
                }
                else {
                    // It's not the default country and there is no specific cache so remove it from the list
                    unset($this->countries[$country->iso_code]);
                }
            }
            uasort($this->countries, array('self', 'sortContext'));
        }

        public function isCountryOthers($iso_country) {
            return !array_key_exists($iso_country, $this->countries);
        }

        private function initDevices()
        {
            $this->devices = array();

            if (!Configuration::get('pagecache_depend_on_device_auto')) {
                // Configuration is set to get the same content on mobile and desktop
                $this->devices['desktop'] = array();
                $this->devices['desktop']['label'] = 'Any device';
                $this->devices['desktop']['value'] = 'desktop';
                $this->devices['desktop']['default'] = true;
            }
            else {
                $this->devices['desktop'] = array();
                $this->devices['desktop']['label'] = 'Desktop';
                $this->devices['desktop']['value'] = 'desktop';
                $this->devices['desktop']['default'] = true;

                $this->devices['mobile'] = array();
                $this->devices['mobile']['label'] = 'Mobile';
                $this->devices['mobile']['value'] = 'mobile';
                $this->devices['mobile']['default'] = true;
            }
            uasort($this->devices, array('self', 'sortContext'));
        }

        private function initTaxManagers()
        {
            // Disabled because when warmng up a country, the corresponding tax manager will be used. There is no need
            // to warmup a country with a tax manager that will never apply to it.
            $this->tax_managers = array();
        }

        private function initSpecifics()
        {
            $this->specifics = array();

            $mostUsedSpecificsRows = PageCacheDAO::getMostUsedSpecifics(2);
            foreach ($mostUsedSpecificsRows as $mostUsedSpecificsRow) {
                $this->specifics[$mostUsedSpecificsRow['id_specifics']] = array();
                $this->specifics[$mostUsedSpecificsRow['id_specifics']]['label'] = '#' . $mostUsedSpecificsRow['id_specifics'];
                $this->specifics[$mostUsedSpecificsRow['id_specifics']]['value'] = $mostUsedSpecificsRow['id_specifics'];
                $this->specifics[$mostUsedSpecificsRow['id_specifics']]['default'] = false;
                $this->specifics[$mostUsedSpecificsRow['id_specifics']]['moreinfos'] = $mostUsedSpecificsRow['specifics'];
                $this->specifics[$mostUsedSpecificsRow['id_specifics']]['count'] = $mostUsedSpecificsRow['count'];
            }

            // Add "Default"
            $this->specifics[''] = array();
            $this->specifics['']['label'] = 'Default';
            $this->specifics['']['value'] = '';
            $this->specifics['']['default'] = true;

            uasort($this->specifics, array('self', 'sortContext'));
        }

        public function getContextsToWarmup()
        {
            $contextsToWarmup = array();
            foreach ($this->contexts as $index => $context) {
                $contextsToWarmup[$index] = $context;
                if ($contextsToWarmup[$index]['country'] === 'OTHERS') {
                    $currentCacheKeyCountryConf = json_decode(JprestaUtils::getConfigurationByShopId('pagecache_cachekey_countries', $this->id_shop, '{}'), true);
                    foreach ($currentCacheKeyCountryConf as $id_country => $country_conf) {
                        if (!$country_conf['specific_cache']) {
                            $contextsToWarmup[$index]['country'] = Country::getIsoById($id_country);
                            break;
                        }
                    }
                }
            }
            return $contextsToWarmup;
        }

        /**
         * @return int Number of context to warm-up
         */
        public function getContextCount()
        {
            return count($this->contexts);
        }

        /**
         * @param $id_shop
         * @return JprestaCacheWarmerSettings
         */
        public static function get($id_shop)
        {
            $cws_json = Configuration::get('pagecache_cache_warmer_settings', null, null, $id_shop);
            if (!$cws_json) {
                $cws = new JprestaCacheWarmerSettings($id_shop);
            } else {
                $stdClass = json_decode($cws_json, true);
                $cws = new JprestaCacheWarmerSettings($id_shop);
                foreach ($stdClass as $key => $value) {
                    if ($key != 'id_shop') {
                        $cws->{$key} = $value;
                    }
                }
            }
            return $cws->init();
        }

        public function save()
        {
            Configuration::updateValue('pagecache_cache_warmer_settings', json_encode($this), false, null,
                $this->id_shop);
        }

        public static function sortContext($c1, $c2)
        {
            $c1Count = array_key_exists('count', $c1) ? (int)$c1['count'] : 0;
            $c2Count = array_key_exists('count', $c2) ? (int)$c2['count'] : 0;
            $c1Label = array_key_exists('label', $c1) ? $c1['label'] : '';
            $c2Label = array_key_exists('label', $c2) ? $c2['label'] : '';
            if (array_key_exists('default', $c1) && $c1['default']) {
                return -PHP_INT_MAX;
            }
            if (array_key_exists('default', $c2) && $c2['default']) {
                return PHP_INT_MAX;
            }
            if ($c1Count != $c2Count) {
                return $c2Count - $c1Count;
            }
            return strcasecmp($c1Label, $c2Label);
        }
    }
}
