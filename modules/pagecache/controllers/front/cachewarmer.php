<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 * @author    Jpresta
 * @copyright Jpresta
 * @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

include_once(dirname(__FILE__) . '/../../pagecache.php');

class pagecacheCacheWarmerModuleFrontController extends ModuleFrontController
{
    const SEPARATOR = "\t";

    const CONTROLLER_INDEX = 1;
    const CONTROLLER_PRODUCT = 2;
    const CONTROLLER_CATEGORY = 3;
    const CONTROLLER_CMS = 4;
    const CONTROLLER_CMS_CATEGORY = 5;
    const CONTROLLER_SUPPLIER = 6;
    const CONTROLLER_MANUFACTURER = 7;
    const CONTROLLER_CONTACT = 8;
    const CONTROLLER_SITEMAP = 9;
    const CONTROLLER_NEW_PRODUCTS = 10;
    const CONTROLLER_PRICE_DROPS = 11;
    const CONTROLLER_BEST_SALES = 12;

    private $start_time;

    public function __construct()
    {
        parent::__construct();
        $this->start_time = microtime(true);
    }

    public function initContent()
    {
        parent::initContent();

        if (Module::isEnabled("pagecache")) {
            $token = Tools::getValue('token', '');
            $goodToken = JprestaUtils::getSecurityToken();
            if (!$goodToken || strcmp($goodToken, $token) === 0) {
                $action = Tools::getValue('action');
                if ($action && $action === 'GetShopInfos') {
                    if (!Configuration::get('pagecache_debug') || Tools::getIsset('pretty')) {
                        self::processGetShopInfos(Tools::getValue('shopId'));
                    }
                    else {
                        header("HTTP/1.0 503 Module is in test mode");
                        die('Module is in test mode');
                    }
                }
            } else {
                header("HTTP/1.0 403 Bad token");
                die('Bad token ' . $token);
            }
        } else {
            // Cannot be called when module is disabled but...
            header("HTTP/1.0 503 Module not enabled");
            die('Module not enabled');
        }

        header("HTTP/1.0 404 Not found");
        die('Not found');
    }

    private function processGetShopInfos($shopId)
    {

        $shopArray = Shop::getShop((int)$shopId);

        if (!$shopArray) {
            header("HTTP/1.0 404 Shop not found");
            die('Shop not found #' . $shopId);
        }

        $shop = new Shop($shopId);
        $settings = JprestaCacheWarmerSettings::get($shopId);

        ob_end_clean();
        header('Content-Type: text/plain');

        echo $this->module->version . self::SEPARATOR;
        echo _PS_VERSION_ . self::SEPARATOR;
        echo $shop->getBaseURL(true) . self::SEPARATOR;
        echo $settings->getPagesCount() . self::SEPARATOR;
        echo $settings->getContextCount();
        echo "\n";

        if (!$this->getShopUrls($settings)) {
            echo "...\n";
        }
        else {
            // This will inform the cache-warmer that there is no more data to wait.
            echo ".\n";
        }
        die();
    }

    /**
     * @return boolean true if max execution time has been reached
     */
    private function isMaxExecutionTime() {
        static $max_in_seconds = null;
        if ($max_in_seconds === null) {
            $max_in_seconds = 0.8 * (int) Configuration::get('pagecache_max_exec_time');
        }
        if ($max_in_seconds <= 0) {
            return false;
        }
        $spent = microtime(true) - $this->start_time;
        return $spent >= $max_in_seconds;
    }

    /**
     * @param $settings JprestaCacheWarmerSettings
     * @return boolean true if all URLs have been returned, false if the script was too long and all URLs have not been returned
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    private function getShopUrls($settings)
    {
        $link = new Link();
        $shop = new Shop($settings->id_shop);

        foreach ($settings->getContextsToWarmup() as $context) {
            $customerArray = Customer::getCustomersByEmail($context['group']);
            if ($customerArray && count($customerArray) === 1) {
                if ((int) $customerArray[0]['id_default_group'] === (int) Configuration::get('PS_UNIDENTIFIED_GROUP')) {
                    // The Visitor group must not be specified
                    $context['group'] = null;
                }
            }

            //
            // GENERIC PAGES
            //
            if (Configuration::get('pagecache_index') && array_key_exists('index', $settings->controllers) && $settings->controllers['index']['checked']) {
                $this->addPage($settings, $link, 'index', $context);
                if ($this->isMaxExecutionTime()) {
                    return false;
                }
            }
            if (Configuration::get('pagecache_newproducts') && array_key_exists('newproducts', $settings->controllers) && $settings->controllers['newproducts']['checked']) {
                $this->addPage($settings, $link, 'new-products', $context);
                if ($this->isMaxExecutionTime()) {
                    return false;
                }
            }
            if (Configuration::get('pagecache_pricesdrop') && array_key_exists('pricesdrop', $settings->controllers) && $settings->controllers['pricesdrop']['checked']) {
                $this->addPage($settings, $link, 'prices-drop', $context);
                if ($this->isMaxExecutionTime()) {
                    return false;
                }
            }
            if (Configuration::get('pagecache_contact') && array_key_exists('contact', $settings->controllers) && $settings->controllers['contact']['checked']) {
                $this->addPage($settings, $link, 'contact', $context);
                if ($this->isMaxExecutionTime()) {
                    return false;
                }
            }
            if (Configuration::get('pagecache_sitemap') && array_key_exists('sitemap', $settings->controllers) && $settings->controllers['sitemap']['checked']) {
                $this->addPage($settings, $link, 'sitemap', $context);
                if ($this->isMaxExecutionTime()) {
                    return false;
                }
            }
            if ((int)Configuration::get('pagecache_bestsales') && array_key_exists('bestsales', $settings->controllers) && $settings->controllers['bestsales']['checked']) {
                $this->addPage($settings, $link, 'best-sales', $context);
                if ($this->isMaxExecutionTime()) {
                    return false;
                }
            }
            //
            // MANUFACTURERS
            //
            if (Configuration::get('pagecache_manufacturer') && array_key_exists('manufacturer', $settings->controllers) && $settings->controllers['manufacturer']['checked']) {
                // List of manufacturers
                $this->addPage($settings, $link, 'manufacturer', $context);
                if ($this->isMaxExecutionTime()) {
                    return false;
                }
                // Each manufacturers
                $sql = 'SELECT c.id_manufacturer
                    FROM `' . _DB_PREFIX_ . 'manufacturer` c' . $shop->addSqlAssociation('manufacturer', 'c') . '
                    WHERE c.`active` = 1';
                $id_manufacturer_rows = DB::getInstance()->executeS($sql);
                foreach ($id_manufacturer_rows as $id_manufacturer_row) {
                    $this->addManufacturer($settings, $link, $id_manufacturer_row['id_manufacturer'], $context);
                    if ($this->isMaxExecutionTime()) {
                        return false;
                    }
                }
            }
            //
            // SUPPLIERS
            //
            if (Configuration::get('pagecache_supplier') && array_key_exists('supplier', $settings->controllers) && $settings->controllers['supplier']['checked']) {
                // List of suppliers
                $this->addPage($settings, $link, 'supplier', $context);
                if ($this->isMaxExecutionTime()) {
                    return false;
                }
                // Each suppliers
                $sql = 'SELECT c.id_supplier
                    FROM `' . _DB_PREFIX_ . 'supplier` c' . $shop->addSqlAssociation('supplier', 'c') . '
                    WHERE c.`active` = 1';
                $id_supplier_rows = DB::getInstance()->executeS($sql);
                foreach ($id_supplier_rows as $id_supplier_row) {
                    $this->addSupplier($settings, $link, $id_supplier_row['id_supplier'], $context);
                    if ($this->isMaxExecutionTime()) {
                        return false;
                    }
                }
            }
            //
            // PRODUCTS
            //
            if (Configuration::get('pagecache_product') && array_key_exists('product', $settings->controllers) && $settings->controllers['product']['checked']) {
                $sql = 'SELECT p.id_product 
                    FROM `' . _DB_PREFIX_ . 'product` p' . $shop->addSqlAssociation('product', 'p') . '
                    WHERE product_shop.`active` = 1';
                $id_product_rows = DB::getInstance()->executeS($sql);
                foreach ($id_product_rows as $id_product_row) {
                    // Check that product is not customizable
                    $customizationFieldCount = (int) JprestaUtils::dbGetValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'customization_field` WHERE `id_product` = '.(int)$id_product_row['id_product']);
                    if ($customizationFieldCount) {
                        // Skip this product
                        continue;
                    }

                    if (!$this->addProduct($settings, $link, $shop, $id_product_row['id_product'], $context)) {
                        return false;
                    }
                }
            }
            //
            // CATEGORIES
            //
            if (Configuration::get('pagecache_category') && array_key_exists('category', $settings->controllers) && $settings->controllers['category']['checked']) {
                $sql = 'SELECT c.id_category
                    FROM `' . _DB_PREFIX_ . 'category` c' . $shop->addSqlAssociation('category', 'c') . '
                    WHERE c.`active` = 1 AND c.is_root_category = 0 AND c.id_parent > 0';
                $id_category_rows = DB::getInstance()->executeS($sql);
                foreach ($id_category_rows as $id_category_row) {
                    $this->addCategory($settings, $link, $id_category_row['id_category'], $context);
                    if ($this->isMaxExecutionTime()) {
                        return false;
                    }
                }
            }
            //
            // CMS
            //
            if (Configuration::get('pagecache_cms') && array_key_exists('cms', $settings->controllers) && $settings->controllers['cms']['checked']) {
                $sql = 'SELECT c.id_cms
                    FROM `' . _DB_PREFIX_ . 'cms` c' . $shop->addSqlAssociation('cms', 'c') . '
                    WHERE c.`active` = 1';
                $id_cms_rows = DB::getInstance()->executeS($sql);
                foreach ($id_cms_rows as $id_cms_row) {
                    $this->addCMS($settings, $link, $id_cms_row['id_cms'], $context);
                    if ($this->isMaxExecutionTime()) {
                        return false;
                    }
                }

                //
                // CMS CATEGORIES
                //
                $sql = 'SELECT c.id_cms_category
                    FROM `' . _DB_PREFIX_ . 'cms_category` c' . $shop->addSqlAssociation('cms_category', 'c') . '
                    WHERE c.`active` = 1';
                $id_cms_category_rows = DB::getInstance()->executeS($sql);
                foreach ($id_cms_category_rows as $id_cms_category_row) {
                    $this->addCMSCategory($settings, $link, $id_cms_category_row['id_cms_category'], $context);
                    if ($this->isMaxExecutionTime()) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * @param $settings JprestaCacheWarmerSettings
     * @param $link LinkCore
     * @param $controller string
     * @param $context
     */
    private function addPage($settings, $link, $controller, $context) {
        $timeout_minutes = (int)Configuration::get('pagecache_'.str_replace('-', '', $controller).'_timeout');
        $id_lang = (int) Language::getIdByIso($context['language']);
        $url = $link->getPageLink($controller, null, $id_lang, null, false, $settings->id_shop);
        switch ($controller) {
            case 'index':
                $id_controller = self::CONTROLLER_INDEX;
                break;
            case 'new-products':
                $id_controller = self::CONTROLLER_NEW_PRODUCTS;
                break;
            case 'prices-drop':
                $id_controller = self::CONTROLLER_PRICE_DROPS;
                break;
            case 'contact':
                $id_controller = self::CONTROLLER_CONTACT;
                break;
            case 'sitemap':
                $id_controller = self::CONTROLLER_SITEMAP;
                break;
            case 'best-sales':
                $id_controller = self::CONTROLLER_BEST_SALES;
                break;
            default:
                $id_controller = 0;
        }
        $this->addURL($settings, $url, $id_controller, $timeout_minutes, $context['currency'], $context['device'], $context['country'], $context['group'], null, $context['specifics']);
    }

    /**
     * @param $settings JprestaCacheWarmerSettings
     * @param $link LinkCore
     * @param $id
     */
    private function addManufacturer($settings, $link, $id, $context) {
        $timeout_minutes = (int)Configuration::get('pagecache_manufacturer_timeout');
        $id_lang = (int) Language::getIdByIso($context['language']);
        $url = $link->getManufacturerLink((int) $id, null, $id_lang, $settings->id_shop);
        $this->addURL($settings, $url, self::CONTROLLER_MANUFACTURER, $timeout_minutes, $context['currency'], $context['device'], $context['country'], $context['group'], null, $context['specifics']);
    }

    /**
     * @param $settings JprestaCacheWarmerSettings
     * @param $link LinkCore
     * @param $id
     */
    private function addSupplier($settings, $link, $id, $context) {
        $timeout_minutes = (int)Configuration::get('pagecache_supplier_timeout');
        $id_lang = (int) Language::getIdByIso($context['language']);
        $url = $link->getSupplierLink((int) $id, null, $id_lang, $settings->id_shop);
        $this->addURL($settings, $url, self::CONTROLLER_SUPPLIER, $timeout_minutes, $context['currency'], $context['device'], $context['country'], $context['group'], null, $context['specifics']);
    }

    /**
     * @param $settings JprestaCacheWarmerSettings
     * @param $link LinkCore
     * @param $id
     */
    private function addCMS($settings, $link, $id, $context) {
        $timeout_minutes = (int)Configuration::get('pagecache_cms_timeout');
        $id_lang = (int) Language::getIdByIso($context['language']);
        $url = $link->getCMSLink((int) $id, null, null, $id_lang, $settings->id_shop);
        $this->addURL($settings, $url, self::CONTROLLER_CMS, $timeout_minutes, $context['currency'], $context['device'], $context['country'], $context['group'], null, $context['specifics']);
    }

    /**
     * @param $settings JprestaCacheWarmerSettings
     * @param $link LinkCore
     * @param $id
     */
    private function addCMSCategory($settings, $link, $id, $context) {
        $timeout_minutes = (int)Configuration::get('pagecache_cms_timeout');
        $id_lang = (int) Language::getIdByIso($context['language']);
        $url = $link->getCMSCategoryLink((int) $id, null, $id_lang, $settings->id_shop);
        $this->addURL($settings, $url, self::CONTROLLER_CMS_CATEGORY, $timeout_minutes, $context['currency'], $context['device'], $context['country'], $context['group'], null, $context['specifics']);
    }

    /**
     * @param $settings JprestaCacheWarmerSettings
     * @param $link LinkCore
     * @param $id
     */
    private function addCategory($settings, $link, $id, $context) {
        $timeout_minutes = (int)Configuration::get('pagecache_category_timeout');
        $id_lang = (int) Language::getIdByIso($context['language']);
        $url = $link->getCategoryLink((int) $id, null, $id_lang, null, $settings->id_shop);
        $this->addURL($settings, $url, self::CONTROLLER_CATEGORY, $timeout_minutes, $context['currency'], $context['device'], $context['country'], $context['group'], null, $context['specifics']);
    }

    /**
     * @param $settings JprestaCacheWarmerSettings
     * @param $link LinkCore
     * @param $shop ShopCore
     * @param $id_product integer
     * @throws PrestaShopException
     */
    private function addProduct($settings, $link, $shop, $id_product, $context) {

        // Sometimes product combinaisons have the same URL so we need to check it to avoid warming the same URL multiple times
        // I don't do it at the global level to avoid consumming to much memory
        $urls = [];

        $timeout_minutes = (int)Configuration::get('pagecache_product_timeout');
        $id_lang = (int) Language::getIdByIso($context['language']);

        // Gettting the product object here will reduce SQL query count
        $product = new Product((int) $id_product, false, $id_lang, $settings->id_shop);

        // Simple product (even products with combinations have a simple URL)
        $url = $link->getProductLink($product, null, null, null, $id_lang, $settings->id_shop);
        $urls[$url] = true;
        $this->addURL($settings, $url, self::CONTROLLER_PRODUCT, $timeout_minutes, $context['currency'], $context['device'], $context['country'], $context['group'], null, $context['specifics']);
        if ($this->isMaxExecutionTime()) {
            return false;
        }

        // Check if it is a product with combinations
        $sql = 'SELECT pa.id_product_attribute
            FROM `' . _DB_PREFIX_ . 'product_attribute` pa' . $shop->addSqlAssociation('product_attribute', 'pa') . '
            WHERE pa.id_product = ' . (int) $id_product;
        $ipa_rows = DB::getInstance()->executeS($sql);
        if ($ipa_rows && count($ipa_rows) > 0) {
            // Product with combinations
            foreach ($ipa_rows as $ipa_row) {
                // Add URL for all combinations
                $url = $link->getProductLink($product, null, null, null, $id_lang, $settings->id_shop, $ipa_row['id_product_attribute']);
                $url_no_anchor = strtok($url, "#");
                if (!array_key_exists($url_no_anchor, $urls)) {
                    $urls[$url_no_anchor] = true;
                    $this->addURL($settings, $url_no_anchor, self::CONTROLLER_PRODUCT, $timeout_minutes, $context['currency'], $context['device'],$context['country'], $context['group'], null, $context['specifics']);
                }
                if ($this->isMaxExecutionTime()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param $settings JprestaCacheWarmerSettings
     * @param string $url string
     * @param integer $id_controller One of self::CONTROLLER_*
     * @param integer $timeout_minutes Configured timeout
     * @param string $iso_currency A valid currency ISO value or null
     * @param string $device 'desktop' or 'mobile'
     * @param string $iso_country A valid country ISO value or null
     * @param string $group Email of the group or null
     * @param integer $id_tax_manager
     * @param integer $id_specifics
     */
    private function addURL($settings, $url, $id_controller, $timeout_minutes, $iso_currency, $device, $iso_country, $group, $id_tax_manager, $id_specifics) {
        static $baseUrl = null;
        if ($baseUrl === null) {
            $shop = new Shop($settings->id_shop);
            $baseUrl = $shop->getBaseURL(true);
        }

        static $ids_currency = array();
        if ($iso_currency && !array_key_exists($iso_currency, $ids_currency)) {
            $ids_currency[$iso_currency] = Currency::getIdByIsoCode($iso_currency);
        }
        $id_currency = $iso_currency ? $ids_currency[$iso_currency] : null;

        $id_device = PageCache::DEVICE_COMPUTER;
        if ($device && $device === 'mobile') {
            $id_device = PageCache::DEVICE_MOBILE;
        }

        static $ids_country = array();
        if ($iso_country && !array_key_exists($iso_country, $ids_country)) {
            if ($settings->isCountryOthers($iso_country)) {
                $ids_country[$iso_country] = null;
            }
            else {
                $ids_country[$iso_country] = Country::getByIso($iso_country);
            }
        }
        $id_country = $iso_country ? $ids_country[$iso_country] : null;

        static $ids_fake_customer = array();
        if ($group && !array_key_exists($group, $ids_fake_customer)) {
            $customerArray = Customer::getCustomersByEmail($group);
            if ($customerArray && count($customerArray) === 1) {
                $ids_fake_customer[$group] = $customerArray[0]['id_customer'];
            }
            else {
                $ids_fake_customer[$group] = null;
            }
        }
        $id_fake_customer = $group ? $ids_fake_customer[$group] : null;

        static $ids_tax_manager = array();
        if ($id_tax_manager && !array_key_exists($id_tax_manager, $ids_tax_manager)) {
            $details = PageCacheDAO::getDetailsById($id_tax_manager);
            if ($details) {
                $ids_tax_manager[$id_tax_manager] = $id_tax_manager;
            }
            else {
                $ids_tax_manager[$id_tax_manager] = null;
            }
        }
        $id_tax_manager = $id_tax_manager ? $ids_tax_manager[$id_tax_manager] : null;

        $stats = PageCacheDAO::getStatsByContext($url, $id_currency, $id_device, $id_country, $id_fake_customer, $id_tax_manager, $id_specifics);
        if (!$stats) {
            $ttl = 0;
            $priority = 1000;
        }
        else {
            $timeout_minutes_to_use = min($timeout_minutes, 7 * 60 * 24);
            if ($timeout_minutes < 0) {
                // If timeout is defined to infinity, then set it to 7 days
                $timeout_minutes_to_use = 7 * 60 * 24;
            }
            $ttl = $stats['deleted'] ? 0 : max(0, $timeout_minutes_to_use - $stats['max_age_minutes']);
            $priority = $stats['sum_hit'] + $stats['sum_missed'];
        }
        if ($ttl < (24 * 60)) {
            echo self::reduceUrl($baseUrl, $url) . self::SEPARATOR;
            echo $priority . self::SEPARATOR;
            echo $device . self::SEPARATOR;
            echo $iso_currency . self::SEPARATOR;
            echo ($iso_country ? $iso_country : '') . self::SEPARATOR;
            echo ($group ? $group : '') . self::SEPARATOR;
            echo ($id_tax_manager ? $id_tax_manager : '') . self::SEPARATOR;
            echo ($id_specifics ? $id_specifics : '') . self::SEPARATOR;
            echo $id_controller;
            echo "\n";
        }
    }

    private static function reduceUrl($baseUrl, $url) {
        $reducedUrl = str_replace($baseUrl, '', $url);
        return $reducedUrl;
    }
}
