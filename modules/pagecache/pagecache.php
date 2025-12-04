<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once 'classes/PageCacheURLNormalizer.php';
require_once 'classes/JprestaCacheKey.php';
require_once 'classes/JprestaCacheKeySpecifics.php';
require_once 'classes/JprestaCacheWarmerSettings.php';
require_once 'classes/JprestaApi.php';
require_once 'classes/JprestaUtils.php';
require_once 'classes/JprestaUtilsMobileDetect.php';
require_once 'classes/JprestaUtilsTaxManager.php';
require_once 'classes/PageCacheCache.php';
require_once 'classes/PageCacheCacheSimpleFS.php';
require_once 'classes/PageCacheCacheMultiStore.php';
// ULTIMATE
require_once 'classes/PageCacheCacheZipFS.php';
require_once 'classes/PageCacheCacheZipArchive.php';
require_once 'classes/PageCacheCacheMemcache.php';
require_once 'classes/PageCacheCacheMemcached.php';
// ULTIMATE£
require_once 'classes/JprestaCustomer.php';
require_once 'classes/PageCacheDAO.php';
require_once 'vendor/http_build_url.php';

class PageCache extends Module
{

    const PAGECACHE_DIR = 'pagecache';
    const HOOK_TYPE_MODULE = 'm';
    const HOOK_TYPE_WIDGET = 'w';
    const HOOK_TYPE_WIDGET_BLOCK = 'b';
    const PROFILING_MAX_RECORD = 1000;
    const FLUSH_MAX_SECONDS = 30;
    const HTTP_HEADER_CACHE_INFO = 'X-JPresta-Cache-Infos';

    const DEVICE_COMPUTER = 1;
    const DEVICE_TABLET = 2;
    const DEVICE_MOBILE = 3;

    public static $page_cache_start_time = null;
    private $pre_display_html = null;

    /**
     * @var string Reason of the status of the cache used to send HTTP header
     */
    private static $status_reason = '';

    public $jpresta_submodules = array();

    public static $managed_controllers = array(
        'index',
        'category',
        'product',
        'cms',
        'newproducts',
        'bestsales',
        'supplier',
        'manufacturer',
        'contact',
        'pricesdrop',
        'sitemap');

    private static $default_dyn_hooks = array(
        'displayproducttabcontent',
        'displayrightcolumn',
        'displayleftcolumn',
        'displaytop',
        'displaynav',
        'displayproducttab',
        'actionproductoutofstock',
        'displayfooterproduct',
        'displayleftcolumnproduct',
        'displayhome',
        'displayfooter',
        'displaysidebarright',
        'displayrightbar');

    private static $default_dyn_modules = array(
        'blockuserinfo',
        'blockviewed',
        'blockmyaccount',
        'favoriteproducts',
        'blockwishlist',
        'blockviewed_mod',
        'stcompare',
        'ps_shoppingcart',
        'ps_customersignin'
    );

    private static $cookies_to_preserve = array(
        // From Prestashop
        'id_currency' => 'id_currency',
        'id_lang' => 'id_lang',
        'no_mobile' => 'no_mobile',
        'iso_code_country' => 'iso_code_country',
        'detect_language' => 'detect_language',
        // From autolanguagecurrency module
        'autolocation' => 'autolocation',
        'autolocation_isocode' => 'autolocation_isocode',
        'id_currency_by_location' => 'id_currency_by_location',
        'id_language_by_location' => 'id_language_by_location',
        // From stthemeeditor module
        'st_category_columns_nbr' => 'st_category_columns_nbr',
        // From gdprpro module
        'gdpr_conf' => 'gdpr_conf',
        'gdpr_windows_was_opened' => 'gdpr_windows_was_opened',
        // From cookiesplus (Idnovate)
        'psnotice' => 'psnotice',
        'psnoticeexiry' => 'psnoticeexiry',
        'cookiesplus' => 'cookiesplus',
        // From APC poup (Idnovate)
        'apc_popup' => 'apc_popup',
        // From Age Verify module
        'age_verify' => 'age_verify',
        // VAT number validate
        'guest_taxes' => 'guest_taxes',
        // Amazon Pay - Login and Pay with Amazon by patworx
        'customer_firstname' => '',
        'customer_lastname' => '',
        // From hicookie module
        'hiThirdPartyCookies' => 'hiThirdPartyCookies',
    );

    const JPRESTA_PROTO = 'http://';
    const JPRESTA_DOMAIN = 'jpresta';

    public function __construct()
    {
        $this->name = 'pagecache';
        $this->tab = 'administration';
        $this->version = '7.8.3';
        $this->author = 'JPresta.com';
        $this->author_address = '0x7951ec451376e076369022B91cb41B7824898C24';
        $this->module_key = 'e00d068863a4c8a3684e984f80756e61';
        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => '1.7.999.999');
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = 'JPresta - Page Cache Ultimate';
        $this->description = $this->l('Enable full page caching for home, categories, products, CMS and much more pages. Even with page caching you can enable some modules like \'viewed products\' or \'my account\' blocks to load dynamically in ajax. Go from seconds to few milliseconds of loading time!');

        // Check tokens
        $token_enabled = (int)(Configuration::get('PS_TOKEN_ENABLE')) == 1 ? true : false;
        if ($token_enabled) {
            $this->warning = $this->l('You must disable tokens in order for cached pages to do ajax call.');
        }
        // Check for bvkdispatcher module
        if (Module::isInstalled('bvkseodispatcher')) {
            $this->warning = $this->l('Module "SEO Pretty URL Module" (bvkseodispatcher) is not compatible with PageCache because it does not respect Prestashop standards. You have to choose between this module and PageCache.');
        }
        // Check for overrides (after an upgrade it is disabled)
        if (!self::isOverridesEnabled()) {
            $this->warning = $this->l('Overrides are disabled in Performances tab so PageCache is disabled.');
        }
        // Avoid call to Dispatcher::getInstance() without parameters like in Link but don't do it with StoreCommander
        // and some other URLs, don't really know why it does not work on some URLs :-(
        if (strpos($_SERVER['REQUEST_URI'], 'storecommander') === false
            && strpos($_SERVER['REQUEST_URI'], 'catalog/products/combinations') === false) {
            self::getControllerName();
        }
    }

    public function install()
    {
        // Be aware that only the last message in _errors will be displayed.

        // Check PS version compliancy first
        if (method_exists($this, 'checkCompliancy') && !$this->checkCompliancy()) {
            $this->_errors[] = Context::getContext()->getTranslator()->trans('The version of your module is not compliant with your PrestaShop version.', array(), 'Admin.Modules.Notification');
            return false;
        }

        // Be sure the script will end correctly (not sure if it's taken into account)
        set_time_limit(300);

        // Check buggy version 1.6.0.8
        if (Tools::version_compare(_PS_VERSION_,'1.6.0.8','=')) {
            // Check that a fix has been applied
            $moduleClass = Tools::file_get_contents(_PS_CLASS_DIR_ . 'module/Module.php');
            if (substr_count($moduleClass, '#^\s*<\?(?:php)?#') != 4) {
                $this->_errors[] = $this->l('Prestashop 1.6.0.8 has a bug (http://forge.prestashop.com/browse/PSCSX-2500) that must be fixed in order to install PageCache. Please upgrade your shop or apply a patch (replace 4 occurences of "#^\s*<\?(?:php)?\s#" by "#^\s*<\?(?:php)?#" in file ' . _PS_CLASS_DIR_ . 'module/Module.php).');
                return false;
            }
        }

        // Check for similar modules (split string to avoid the build to replace it)
        if ($this->name !== 'jpresta'.'speedpack' && (Module::isInstalled('jpresta'.'speedpack') || file_exists(_PS_MODULE_DIR_ . 'jpresta'.'speedpack'))) {
            $this->_errors[] = $this->l('Before installing this module you must uninstall "Speed Pack" module and delete its directory') . ': ' . _PS_MODULE_DIR_ . 'jpresta'.'speedpack';
            return false;
        }
        if ($this->name !== 'pagecache' && (Module::isInstalled('pagecache') || file_exists(_PS_MODULE_DIR_ . 'pagecache'))) {
            $this->_errors[] = $this->l('Before installing this module you must uninstall "Page Cache Ultimate" module and delete its directory') . ': ' . _PS_MODULE_DIR_ . 'pagecache';
            return false;
        }
        if ($this->name !== 'pagecachestd' && (Module::isInstalled('pagecachestd') || file_exists(_PS_MODULE_DIR_ . 'pagecachestd'))) {
            $this->_errors[] = $this->l('Before installing this module you must uninstall "Page Cache Standard" module and delete its directory') . ': ' . _PS_MODULE_DIR_ . 'pagecachestd';
            return false;
        }

        // Check for bvkdispatcher module
        if (Module::isInstalled('bvkseodispatcher')) {
            $this->_errors[] = $this->l('Module "SEO Pretty URL Module" (bvkseodispatcher) is not compatible with PageCache because it does not respect Prestashop standards. You have to choose between this module and PageCache.');
            return false;
        }

        // Check for expresscache module
        if (Module::isInstalled('expresscache') && file_exists(_PS_MODULE_DIR_ . 'expresscache')) {
            $this->_errors[] = $this->l('Module "Express Cache" (expresscache) cannot be used with Page Cache because you can have only one HTML cache module. In order to install Page Cache you must uninstall Express Cache.');
            return false;
        }

        // Install module
        $install_ok = parent::install();
        if (!$install_ok) {
            foreach (Tools::scandir($this->getLocalPath().'override', 'php', '', true) as $file) {
                $class = basename($file, '.php');
                if (Tools::version_compare(_PS_VERSION_,'1.6','>=')) {
                    if (PrestaShopAutoload::getInstance()->getClassPath($class.'Core')) {
                        $this->removeOverride($class);
                    }
                } else {
                    if (Autoload::getInstance()->getClassPath($class.'Core')) {
                        $this->removeOverride($class);
                    }
                }
            }
            // Retry after uninstalling overrides with our own method
            $install_ok = parent::install();
        }

        if ($install_ok) {
            try {
                PageCacheDAO::createTables();
                $this->_setDefaultConfiguration();
                $this->patchSmartyConfigFront();
                $this->patchSmartyConfigFrontWidgetBlock();

                JprestaUtils::dbCreateIndexIfNotExists(_DB_PREFIX_ . 'specific_price_rule', ['id_country', 'to']);
                JprestaUtils::dbCreateIndexIfNotExists(_DB_PREFIX_ . 'specific_price_rule', ['id_group', 'to']);
                JprestaUtils::dbCreateIndexIfNotExists(_DB_PREFIX_ . 'specific_price', ['id_country', 'to']);
                JprestaUtils::dbCreateIndexIfNotExists(_DB_PREFIX_ . 'specific_price', ['id_group', 'to']);
            }
            catch (PrestaShopException $e) {
                $install_ok = false;
                $this->_errors[] = $e->getMessage() . '. ' . $this->l('Please, contact the support of this module with this error message.');
                try {
                    // An error occured while setting up the module, uninstall it to avoid a bad installation
                    parent::uninstall();
                }
                catch (PrestaShopException $e2) {
                    JprestaUtils::addLog('Cannot uninstall module ' . $this->name . ' after having this error during installation: "' . $e->getMessage() . '"" -> Got this error: ' . $e2->getMessage(), 4);
                }
            }
        }

        return (bool) $install_ok;
    }

    /**
     * Disable / enable Hook and Context override if jprestathemeconfigurator is enabled
     * @return bool
     */
    public function installOverrides() {
        $relPathHook = 'classes/Hook.php';
        $overrideFullPathHook = _PS_MODULE_DIR_ . $this->name . '/override/' . $relPathHook;
        $relPathContext = 'classes/Context.php';
        $overrideFullPathContext = _PS_MODULE_DIR_ . $this->name . '/override/' . $relPathContext;
        if (Module::isEnabled('jprestathemeconfigurator')) {
            if (file_exists($overrideFullPathHook)) {
                rename($overrideFullPathHook, $overrideFullPathHook . '.off');
            }
            if (file_exists($overrideFullPathContext)) {
                rename($overrideFullPathContext, $overrideFullPathContext . '.off');
            }
        }

        $ret = parent::installOverrides();

        if (Module::isEnabled('jprestathemeconfigurator')) {
            if (file_exists($overrideFullPathHook . '.off')) {
                rename($overrideFullPathHook . '.off', $overrideFullPathHook);
            }
            if (file_exists($overrideFullPathContext . '.off')) {
                rename($overrideFullPathContext . '.off', $overrideFullPathContext);
            }
        }

        return $ret;
    }

    public function installTab($adminController, $name = false, $id_parent = -1)
    {
        $isUpdate = true;
        $tab = Tab::getInstanceFromClassName($adminController);
        if (!$tab || !$tab->id) {
            $tab = new Tab();
            $tab->class_name = $adminController;
            $isUpdate = false;
        }
        $tab->active = 1;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            // Translation for modules are cached in a global variable but the local is ignored >:(
            if (is_array($name)) {
                if (array_key_exists($lang['iso_code'], $name)) {
                    $trans = $name[$lang['iso_code']];
                }
                elseif (array_key_exists('en', $name)) {
                    $trans = $name['en'];
                }
            }
            else {
                $trans = $name;
            }
            $tab->name[$lang['id_lang']] = !$trans ? $this->name : $trans;
        }
        $tab->id_parent = $id_parent;
        $tab->module = $this->name;
        if ($isUpdate) {
            return $tab->update();
        }
        else {
            return $tab->add();
        }
    }

    public function uninstallTab($adminController)
    {
        $id_tab = (int)Tab::getIdFromClassName($adminController);
        if ($id_tab) {
            $tab = new Tab($id_tab);
            if (Validate::isLoadedObject($tab)) {
                return ($tab->delete());
            } else {
                $return = false;
            }
        } else {
            $return = true;
        }
        return $return;
    }

    private function uninstallAllTab() {
        $tabs = Tab::getCollectionFromModule($this->name);
        if (JprestaUtils::isIterable($tabs)) {
            foreach ($tabs as $tab) {
                $tab->delete();
            }
            return true;
        }
    }

    public function checkTabAccesses($adminController) {
        try {
            if (Tools::version_compare(_PS_VERSION_,'1.7','>=')) {
                $slug = Access::sluggifyTab(array('class_name' => $adminController), 'READ');
                $granted = Access::isGranted($slug, $this->context->employee->id_profile);
                if (!$granted) {
                    $id_role = JprestaUtils::dbGetValue('SELECT `id_authorization_role` FROM `' . _DB_PREFIX_ . 'authorization_role` WHERE slug = \'' . pSql($slug) . '\'');
                    if ($id_role) {
                        $sql = '
                        INSERT IGNORE INTO `' . _DB_PREFIX_ . 'access` (`id_profile`, `id_authorization_role`)
                        VALUES (' . (int)$this->context->employee->id_profile . ',' . (int)$id_role . ')
                    ';
                        Db::getInstance()->execute($sql);
                    }
                }
            }
            else {
                $id_tab = Tab::getIdFromClassName($adminController);
                $profile = Profile::getProfileAccess($this->context->employee->id_profile, $id_tab);
                if (!$profile['view']) {
                    $sql = 'UPDATE `' . _DB_PREFIX_ . 'access` SET `view`=1, `add`=1, `edit`=1, `delete`=1 WHERE id_profile=' . (int)$this->context->employee->id_profile . ' AND id_tab=' . (int)$id_tab;
                    Db::getInstance()->execute($sql);
                }
            }
        }
        catch (Throwable $e) {
            // ignore
            JprestaUtils::addLog("PageCache | Error in checkTabAccesses(): " . $e->getMessage(), 1);
        }
    }

    /**
     * To be called first
     */
    public static function init() {
        static $initialised = false;
        if (!$initialised) {
            // Avoid doing it multiple times and also recursively
            $initialised = true;

            if (JprestaUtils::getConfigurationAllShop("pagecache_cachekey_usergroups_upd", false)) {
                self::updateCacheKeyForUserGroups();
            }

            if (self::isCacheWarmer()) {
                // Setup the context for cache warmer
                self::setCacheWarmerContext();
            }

            // We must set the country before calling self::preDisplayStats() or getAddressForTaxes() will be called
            // and the country will not be correctly set.
            // This will also set the restrictedCountry variable of the controller
            $country = self::getCountry(Context::getContext());
            if ($country) {
                Context::getContext()->country = $country;
            }
        }
    }

    public function hookDisplayAdminAfterHeader() {
        if (Tools::version_compare(_PS_VERSION_, '1.7', '>')) {
            try {
                $database_version = JprestaUtils::dbGetValue('SELECT version FROM `' . _DB_PREFIX_ . 'module` WHERE name=\'' . pSQL($this->name) . '\'');
                if (Tools::version_compare($this->version, $database_version, '>')) {
                    $smarty = Context::getContext()->smarty;
                    $smarty->assign('jpresta_module_name', $this->displayName);
                    $smarty->assign('jpresta_module_new_version', $this->version);
                    $smarty->assign('jpresta_module_current_version', $database_version);
                    return $this->fetch(_PS_MODULE_DIR_ . $this->name . '/views/templates/admin/need-upgrade.tpl');
                }
            } catch (Throwable $e) {
                // Just ignore
            }
        }
        return '';
    }

    public static function getCache($id_shop = false) {
        $cacheInstance = null;
        if (!$id_shop) {
            $id_shop = Shop::getContextShopID();
        }
        if ($id_shop === null) {
            // Happens in back office when a group of shop is selected. Is used during hooks for cache refreshment.
            $ids_shops = Shop::getShops(true, Shop::getContextShopGroupID(), true);
            $cacheInstance = new PageCacheCacheMultiStore();
            foreach ($ids_shops as $id_shop) {
                $cacheInstance->addCache(self::getCacheInstance($id_shop));
            }
        }
        else {
            $cacheInstance = self::getCacheInstance($id_shop);
        }
        return $cacheInstance;
    }

    private static function getCacheInstance($id_shop)
    {
        static $cacheInstances = array();
        if (array_key_exists($id_shop, $cacheInstances)) {
            return $cacheInstances[$id_shop];
        }

        $cachedir = _PS_CACHE_DIR_ . self::PAGECACHE_DIR . '/' . $id_shop;
        // ULTIMATE
        $type = Configuration::get('pagecache_typecache', null, null, $id_shop);
        if (strcmp('stdzip', $type) === 0 && PageCacheCacheZipFS::isCompatible()) {
            $cacheInstances[$id_shop] = new PageCacheCacheZipFS($cachedir, Configuration::get('pagecache_logs', null, null, $id_shop) > 1);
        }
        else if (strcmp('zip', $type) === 0 && PageCacheCacheZipArchive::isCompatible()) {
            $cacheInstances[$id_shop] = new PageCacheCacheZipArchive($cachedir, Configuration::get('pagecache_logs', null, null, $id_shop) > 1);
        }
        else if (strcmp('memcache', $type) === 0 && PageCacheCacheMemcache::isCompatible()) {
            $cacheInstances[$id_shop] = new PageCacheCacheMemcache(Configuration::get('pagecache_typecache_memcache_host'), (int) Configuration::get('pagecache_typecache_memcache_port'));
        }
        else if (strcmp('memcached', $type) === 0 && PageCacheCacheMemcached::isCompatible()) {
            $cacheInstances[$id_shop] = new PageCacheCacheMemcached(Configuration::get('pagecache_typecache_memcached_host'), (int) Configuration::get('pagecache_typecache_memcached_port'));
        }
        // ULTIMATE£
        if (!array_key_exists($id_shop, $cacheInstances)) {
            $cacheInstances[$id_shop] =  new PageCacheCacheSimpleFS($cachedir, Configuration::get('pagecache_logs', null, null, $id_shop) > 1);
        }
        return $cacheInstances[$id_shop];
    }

    private static function getWidgetBlockTemplate($blockKey) {
        $cachedir = _PS_CACHE_DIR_ . self::PAGECACHE_DIR . '/widget_blocks/';
        return $cachedir . $blockKey . '.tpl';
    }

    public static function setWidgetBlockTemplate($blockKey, $content) {
        $cachedir = _PS_CACHE_DIR_ . self::PAGECACHE_DIR . '/widget_blocks/';
        if (! file_exists($cachedir)) {
            // Creates subdirectory with 777 to be sure it will work
            $grants = 0777;
            if (! @mkdir($cachedir, $grants, true)) {
                $mkdirErrorArray = error_get_last();
                if (! file_exists($cachedir)) {
                    if ($mkdirErrorArray !== null) {
                        JprestaUtils::addLog("PageCache | Cannot create directory " . $cachedir . " with grants $grants: " . $mkdirErrorArray['message'], 4);
                    }
                    else {
                        JprestaUtils::addLog("PageCache | Cannot create directory " . $cachedir . " with grants $grants", 4);
                    }
                }
            }
        }
        $cachefile = $cachedir . $blockKey . '.tpl';
        $write_ok = file_put_contents($cachefile, $content);
        if ($write_ok === false) {
            $mkdirErrorArray = error_get_last();
            if ($mkdirErrorArray !== null) {
                JprestaUtils::addLog("PageCache | Cannot write file $cachefile: " . $mkdirErrorArray['message'], 4);
            }
            else {
                JprestaUtils::addLog("PageCache | Cannot write file $cachefile", 4);
            }
        }
    }

    /**
     * Override Module::updateModuleTranslations()
     */
    public function updateModuleTranslations()
    {
        // Speeds up installation: do nothing because PageCache translation are not in Prestashop language pack
    }

    public function disable($force_all = false) {
        $ret = parent::disable($force_all);
        return (bool) $ret;
    }

    public function enable($force_all = false) {
        $ret = parent::enable($force_all);

        self::updateCacheKeyForCountries();
        self::updateCacheKeyForUserGroups();
        return (bool) $ret;
    }

    public function uninstall()
    {
        try {
            $this->clearCache();
            JprestaCustomer::deleteAllFakeUsers();
        }
        catch (Throwable $e) {
            // Ignore because it's not a big deal if cache is not cleared
        }
        Configuration::deleteByName('pagecache_install_step');
        Configuration::deleteByName('pagecache_always_infosbox');
        Configuration::deleteByName('pagecache_debug');
        Configuration::deleteByName('pagecache_skiplogged');
        Configuration::deleteByName('pagecache_normalize_urls');
        Configuration::deleteByName('pagecache_logs');
        Configuration::deleteByName('pagecache_depend_on_device_auto');
        Configuration::deleteByName('pagecache_depend_on_css_js');
        Configuration::deleteByName('pagecache_tablet_is_mobile');
        Configuration::deleteByName('pagecache_exec_header_hook');
        Configuration::deleteByName('pagecache_stats');
        Configuration::deleteByName('pagecache_profiling');
        Configuration::deleteByName('pagecache_show_stats');
        Configuration::deleteByName('pagecache_groups');
        Configuration::deleteByName('pagecache_seller');
        Configuration::deleteByName('pagecache_ignored_params');
        Configuration::deleteByName('pagecache_dyn_hooks');
        Configuration::deleteByName('pagecache_dyn_widgets');
        Configuration::deleteByName('pagecache_typecache');
        foreach (self::$managed_controllers as $controller) {
            Configuration::deleteByName('pagecache_'.$controller);
            Configuration::deleteByName('pagecache_'.$controller.'_timeout');
            Configuration::deleteByName('pagecache_'.$controller.'_expires');
            Configuration::deleteByName('pagecache_'.$controller.'_u_bl');
            Configuration::deleteByName('pagecache_'.$controller.'_d_bl');
            Configuration::deleteByName('pagecache_'.$controller.'_a_mods');
            Configuration::deleteByName('pagecache_'.$controller.'_u_mods');
            Configuration::deleteByName('pagecache_'.$controller.'_d_mods');
        }
        Configuration::deleteByName('pagecache_product_home_u_bl');
        Configuration::deleteByName('pagecache_product_home_d_bl');
        Configuration::deleteByName('pagecache_product_home_a_mods');
        Configuration::deleteByName('pagecache_product_home_u_mods');
        Configuration::deleteByName('pagecache_product_home_d_mods');
        PageCacheDAO::dropTables();
        $this->uninstallAllTab();

        $ret = parent::uninstall();

        // Clean cache in case of a reset
        Cache::clean('Module::getModuleIdByName_'.pSQL($this->name));

        return (bool) $ret;
    }

    public function isSpeedPack() {
        return $this->name === 'jprestaspeedpack';
    }

    private function _setDefaultConfiguration($id_shop_group = null, $id_shop = null)
    {
        if ($this->isSpeedPack()) {
            $this->installTab('AdminParentSpeedPack', 'JPresta - Speed pack', (int)Tab::getIdFromClassName('AdminAdvancedParameters'));
            $this->installTab('AdminPageCacheConfiguration', 'Page Cache Ultimate', (int)Tab::getIdFromClassName('AdminParentSpeedPack'));
            $this->installTab('AdminJprestaLazyLoadingConfiguration', array(
                'en' => 'Lazy load of images',
                'fr' => 'Chargement différé des images',
                'es' => 'Carga bajo demanda de imágenes'
            ), (int)Tab::getIdFromClassName('AdminParentSpeedPack'));
            $this->installTab('AdminJprestaWebpConfiguration', array(
                'en' => 'Compression of images',
                'fr' => 'Compression des images',
                'es' => 'Compresión de imágenes'
            ), (int)Tab::getIdFromClassName('AdminParentSpeedPack'));
            $this->installTab('AdminJprestaDbOptimizerConfiguration', array(
                'en' => 'Database optimisation',
                'fr' => 'Nettoyage de la base de données',
                'es' => 'Limpieza de la base de datos'
            ), (int)Tab::getIdFromClassName('AdminParentSpeedPack'));
        }
        else {
            if (Tools::version_compare(_PS_VERSION_, '1.6', '>')) {
                $idTab = (int)Tab::getIdFromClassName('AdminAdvancedParameters');
                if (!$idTab) {
                    $idTab = (int)Tab::getIdFromClassName('AdminTools');
                }
                $this->installTab('AdminPageCacheConfiguration', 'JPresta - Page Cache Ultimate', $idTab);
            }
            elseif (Tools::version_compare(_PS_VERSION_, '1.5', '>')) {
                $this->installTab('AdminPageCacheConfiguration', 'JPresta - Page Cache Ultimate', 17);
            }
        }
        $this->installTab('AdminPageCacheMemcachedTest');
        $this->installTab('AdminPageCacheMemcacheTest');
        $this->installTab('AdminPageCacheProfilingDatas');
        $this->installTab('AdminPageCacheSpeedAnalysis');
        $this->installTab('AdminPageCacheDatas');
        // Register hooks
        $this->registerHook('displayAdminAfterHeader');
        if (Tools::version_compare(_PS_VERSION_, '1.7', '>')) {
            $this->registerHook('actionDispatcherBefore');
            $this->registerHook('actionDispatcherAfter');
            $this->registerHook('actionOutputHTMLBefore');
        }
        $this->registerHook('actionDispatcher');
        $this->registerHook('displayHeader');
        if (Tools::version_compare(_PS_VERSION_,'1.6','>')) {
            $hookHeaderId = Hook::getIdByName('Header');
            $this->updatePosition($hookHeaderId, 0, 1);
        }
        $this->registerHook('actionTaxManager');
        if (Tools::version_compare(_PS_VERSION_,'1.6','>')) {
            $hookHeaderId = Hook::getIdByName('actionTaxManager');
            $this->updatePosition($hookHeaderId, 0, 1);
        }
        $this->registerHook('displayMobileHeader');
        $this->registerHook('actionCategoryAdd');
        $this->registerHook('actionCategoryUpdate');
        $this->registerHook('actionCategoryDelete');
        $this->registerHook('actionObjectCmsAddAfter');
        $this->registerHook('actionObjectCmsUpdateAfter');
        $this->registerHook('actionObjectCmsDeleteBefore');
        $this->registerHook('actionObjectStockAvailableUpdateBefore');
        $this->registerHook('actionObjectStockAvailableUpdateAfter');
        $this->registerHook('actionObjectStockAddBefore');
        $this->registerHook('actionObjectStockAddAfter');
        $this->registerHook('actionObjectStockUpdateBefore');
        $this->registerHook('actionObjectStockUpdateAfter');
        $this->registerHook('actionObjectWarehouseProductLocationAddBefore');
        $this->registerHook('actionObjectWarehouseProductLocationAddAfter');
        $this->registerHook('actionObjectWarehouseProductLocationDeleteBefore');
        $this->registerHook('actionObjectWarehouseProductLocationDeleteAfter');
        $this->registerHook('actionObjectManufacturerAddAfter');
        $this->registerHook('actionObjectManufacturerUpdateAfter');
        $this->registerHook('actionObjectManufacturerDeleteBefore');
        $this->registerHook('actionObjectAddressAddAfter');
        $this->registerHook('actionObjectAddressUpdateAfter');
        $this->registerHook('actionObjectAddressDeleteBefore');
        $this->registerHook('actionAttributeSave');
        $this->registerHook('actionAttributeDelete');
        $this->registerHook('actionAttributeGroupDelete');
        $this->registerHook('actionAttributeGroupSave');
        $this->registerHook('actionFeatureSave');
        $this->registerHook('actionFeatureDelete');
        $this->registerHook('actionFeatureValueSave');
        $this->registerHook('actionFeatureValueDelete');
        $this->registerHook('actionProductAdd');
        $this->registerHook('actionObjectProductUpdateBefore');
        $this->registerHook('actionObjectProductUpdateAfter');
        $this->registerHook('actionObjectProductDeleteBefore');
        $this->registerHook('actionObjectCombinationUpdateBefore');
        $this->registerHook('actionObjectCombinationUpdateAfter');
        $this->registerHook('actionObjectCombinationDeleteAfter');
        $this->registerHook('actionHtaccessCreate');
        $this->registerHook('actionObjectShopUrlAddAfter');
        $this->registerHook('actionObjectShopUrlUpdateAfter');
        $this->registerHook('actionObjectShopUrlDeleteAfter');
        $this->registerHook('actionAdminPerformanceControllerAfter');
        // New shop creation
        $this->registerHook('actionShopDataDuplication');
        // Add hook for specific prices
        $this->registerHook('actionObjectSpecificPriceAddAfter');
        $this->registerHook('actionObjectSpecificPriceUpdateAfter');
        $this->registerHook('actionObjectSpecificPriceDeleteBefore');
        $this->registerHook('actionObjectSpecificPriceDeleteAfter');
        // Hook called when images are changed
        $this->registerHook('actionObjectImageAddAfter');
        $this->registerHook('actionObjectImageUpdateAfter');
        $this->registerHook('actionObjectImageDeleteBefore');

        $this->registerHook('actionObjectSpecificPriceRuleAddAfter');
        $this->registerHook('actionObjectSpecificPriceRuleUpdateAfter');
        $this->registerHook('actionObjectSpecificPriceRuleDeleteAfter');

        $this->registerHook('actionObjectGroupAddAfter');
        $this->registerHook('actionObjectGroupUpdateAfter');
        $this->registerHook('actionObjectGroupDeleteAfter');

        // Use backlink heuristic...
        Configuration::updateValue('pagecache_cms_u_bl', true, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_cms_d_bl', true, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_supplier_u_bl', true, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_supplier_d_bl', true, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_manufacturer_u_bl', true, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_manufacturer_d_bl', true, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_product_u_bl', true, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_product_d_bl', true, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_product_home_u_bl', true, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_product_home_d_bl', true, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_category_u_bl', true, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_category_d_bl', true, false, $id_shop_group, $id_shop);

        // Default impacted modules
        Configuration::updateValue('pagecache_category_a_mods', 'blockcategories ps_categorytree iqitcontentcreator', false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_category_u_mods', 'blockcategories ps_categorytree iqitcontentcreator', false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_category_d_mods', 'blockcategories ps_categorytree iqitcontentcreator', false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_supplier_a_mods', 'blocksupplier', false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_supplier_u_mods', 'blocksupplier', false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_supplier_d_mods', 'blocksupplier', false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_manufacturer_a_mods', 'blockmanufacturer', false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_manufacturer_u_mods', 'blockmanufacturer', false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_manufacturer_d_mods', 'blockmanufacturer', false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_product_a_mods', 'blocknewproducts ps_newproducts posnewproduct zonehomeblocks wtnewproducts iqitcontentcreator', false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_product_home_a_mods', 'homefeatured ps_featuredproducts', false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_product_home_u_mods', 'homefeatured ps_featuredproducts', false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_product_home_d_mods', 'homefeatured ps_featuredproducts', false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_cms_a_mods', 'blockcms', false, $id_shop_group, $id_shop);

        // Enable cache on all managed_controllers and timeout = 7 days
        foreach (self::$managed_controllers as $controller) {
            Configuration::updateValue('pagecache_'.$controller, 1, false, $id_shop_group, $id_shop);
            Configuration::updateValue('pagecache_'.$controller.'_timeout', 60 * 24 * 7, false, $id_shop_group, $id_shop);
        }
        // Do not cache contact form by default anymore (anti-spam system)
        Configuration::updateValue('pagecache_contact', 0, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_contact_timeout', 0, false, $id_shop_group, $id_shop);

        // Set default dynamic hooks
        $pagecache_dyn_hooks = '';
        $module_list = Hook::getHookModuleExecList();
        if (JprestaUtils::isIterable($module_list)) {
            foreach ($module_list as $hook_name => $modules) {
                foreach ($modules as $module) {
                    if (in_array($hook_name, self::$default_dyn_hooks) && in_array($module['module'],
                            self::$default_dyn_modules)) {
                        $pagecache_dyn_hooks .= $hook_name . '|' . $module['module'] . ',';
                    } /** Special case: blockcart will be dynamic if ajax is disabled */
                    elseif (in_array($hook_name, self::$default_dyn_hooks) && strcmp($module['module'],
                            'blockcart') == 0) {
                        if (!(int)(Configuration::get('PS_BLOCK_CART_AJAX'))) {
                            $pagecache_dyn_hooks .= $hook_name . '|' . $module['module'] . ',';
                        }
                    }
                }
            }
        }
        Configuration::updateValue('pagecache_dyn_hooks', $pagecache_dyn_hooks, false, $id_shop_group, $id_shop);

        // Set default javascript to execute (empty since autoconf)
        Configuration::updateValue('pagecache_cfgadvancedjs', '', false, $id_shop_group, $id_shop);

        // First install step is 0 (none)
        Configuration::updateValue('pagecache_install_step', 0, false, $id_shop_group, $id_shop);

        // Do not always display infos box by default
        Configuration::updateValue('pagecache_always_infosbox', false, false, $id_shop_group, $id_shop);

        // Not in production by default
        Configuration::updateValue('pagecache_debug', true, false, $id_shop_group, $id_shop);

        // Cache logged in users by default
        Configuration::updateValue('pagecache_skiplogged', false, false, $id_shop_group, $id_shop);

        // Normalize URLs by default
        Configuration::updateValue('pagecache_normalize_urls', true, false, $id_shop_group, $id_shop);

        // Disable logs by default
        Configuration::updateValue('pagecache_logs', false, false, $id_shop_group, $id_shop);

        // Auto detect mobile version
        Configuration::updateValue('pagecache_depend_on_device_auto', true, false, $id_shop_group, $id_shop);

        // Tablet is not considered as mobile by default
        Configuration::updateValue('pagecache_tablet_is_mobile', false, false, $id_shop_group, $id_shop);

        // Do not add CSS and JS version in the cache key by default
        Configuration::updateValue('pagecache_depend_on_css_js', false, false, $id_shop_group, $id_shop);

        // Must we call header hook for dynamic request
        Configuration::updateValue('pagecache_exec_header_hook', false, false, $id_shop_group, $id_shop);

        // Ignore all backlinks before tag /header>
        Configuration::updateValue('pagecache_ignore_before_pattern',
            JprestaUtils::encodeConfiguration('/header>'), $id_shop_group, $id_shop);

        // Ignore faceted searches
        Configuration::updateValue('pagecache_ignore_url_regex',
            JprestaUtils::encodeConfiguration('.*[\?&]q=.*'), $id_shop_group, $id_shop);

        // Disable profiling by default
        Configuration::updateValue('pagecache_profiling', false, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_profiling_min_ms', 100, false, $id_shop_group, $id_shop);
        Configuration::updateValue('pagecache_profiling_max_reached', false, false, $id_shop_group, $id_shop);

        // Enable standard cache system by default
        Configuration::updateValue('pagecache_typecache', 'std', false, $id_shop_group, $id_shop);

        // Default browser cache to 15 minutes
        foreach (self::$managed_controllers as $controller) {
            Configuration::updateValue('pagecache_'.$controller.'_expires', 15, false, $id_shop_group, $id_shop);
        }

        // Default ad tracking parameters
        Configuration::updateValue('pagecache_ignored_params', 'fbclid,gclid,utm_campaign,utm_content,utm_medium,utm_source,utm_term,_openstat,cm_cat,cm_ite,cm_pla,cm_ven,owa_ad,owa_ad_type,owa_campaign,owa_medium,owa_source,pk_campaign,pk_kwd,WT.mc_t,SubmitCurrency,id_currency', false, $id_shop_group, $id_shop);

        // Max execution time for cache warmer
        Configuration::updateValue('pagecache_max_exec_time', (int) ini_get('max_execution_time'), false, $id_shop_group, $id_shop);

        // Disable tokens on front
        Configuration::updateValue('PS_TOKEN_ENABLE', 0, false, $id_shop_group, $id_shop);
    }

    public function patchSmartyConfigFront() {
        if (Tools::version_compare(_PS_VERSION_,'1.7','>')) {
            // This modification has been accepted on github https://github.com/PrestaShop/PrestaShop/pull/8744
            $smartyFrontCongigFile = _PS_CONFIG_DIR_ . '/smartyfront.config.inc.php';
            $str = Tools::file_get_contents($smartyFrontCongigFile);
            if (strpos($str, "\$widget->renderWidget(null, \$params)") !== false) {
                file_put_contents($smartyFrontCongigFile . '.before_' . $this->name , $str);
                $str = str_replace("\$widget->renderWidget(null, \$params)", "Hook::coreRenderWidget(\$widget, isset(\$params['hook']) ? \$params['hook'] : null, \$params)", $str);
                file_put_contents($smartyFrontCongigFile, $str);
            }
            else if (strpos($str, "\$widget->renderWidget(isset(\$params['hook']) ? \$params['hook'] : null, \$params)") !== false) {
                file_put_contents($smartyFrontCongigFile . '.before_' . $this->name , $str);
                $str = str_replace("\$widget->renderWidget(isset(\$params['hook']) ? \$params['hook'] : null, \$params)", "Hook::coreRenderWidget(\$widget, isset(\$params['hook']) ? \$params['hook'] : null, \$params)", $str);
                file_put_contents($smartyFrontCongigFile, $str);
            }
        }
    }

    public function patchSmartyConfigFrontWidgetBlock() {
        if (Tools::version_compare(_PS_VERSION_,'1.7','>')) {
            $smartyFrontCongigFile = _PS_CONFIG_DIR_ . '/smartyfront.config.inc.php';
            $str = Tools::file_get_contents($smartyFrontCongigFile);
            if (strpos($str, "smartyWidgetBlockPageCache") === false) {
                file_put_contents($smartyFrontCongigFile . '.before_' . $this->name . '_widget_block' , $str);
                $str = preg_replace(
                    "/smartyRegisterFunction\s*\(\s*\\\$smarty\s*,\s*'block'\s*,\s*'widget_block'\s*,\s*'smartyWidgetBlock'\s*\)\s*;/",
                    "if (Module::isEnabled('".$this->name."')) {\n\trequire_once _PS_MODULE_DIR_ . '".$this->name."/".$this->name.".php';\n\tsmartyRegisterFunction(\$smarty, 'block', 'widget_block', array('" . get_class($this) . "', 'smartyWidgetBlockPageCache'));\n\t\$smarty->registerFilter('pre', array('" . get_class($this) . "', 'smartyWidgetBlockPageCachePrefilter'));\n} else {\n\tsmartyRegisterFunction(\$smarty, 'block', 'widget_block', 'smartyWidgetBlock');\n}",
                    $str);
            }
            else {
                // Make sure it uses the correct class
                $str = str_replace('\'pagecachestd/pagecachestd.php\'', '\''.$this->name.'/'.$this->name.'.php\'', $str);
                $str = str_replace('\'pagecachestd\'', '\''.$this->name.'\'', $str);
                $str = str_replace('\'PageCacheStd\'', '\''.get_class($this).'\'', $str);
                $str = str_replace('\'pagecache/pagecache.php\'', '\''.$this->name.'/'.$this->name.'.php\'', $str);
                $str = str_replace('\'pagecache\'', '\''.$this->name.'\'', $str);
                $str = str_replace('\'PageCache\'', '\''.get_class($this).'\'', $str);
                $str = str_replace('\'jprestaspeedpack/jprestaspeedpack.php\'', '\''.$this->name.'/'.$this->name.'.php\'', $str);
                $str = str_replace('\'jprestaspeedpack\'', '\''.$this->name.'\'', $str);
                $str = str_replace('\'Jprestaspeedpack\'', '\''.get_class($this).'\'', $str);
            }
            file_put_contents($smartyFrontCongigFile, $str);
            // Now clear the cache to recompile everything
            Tools::clearCompile();
        }
    }

    public function getContent()
    {
        $link = $this->context->link->getAdminLink('AdminPageCacheConfiguration');
        if (Tools::version_compare(_PS_VERSION_, '1.7', '>')) {
            Tools::redirect($link);
        } else {
            // There is a bug in redirect and getAdminLink in PS1.5 and PS1.6 so we do it ourselves
            $path = parse_url($_SERVER['REQUEST_URI'])['path'];
            header('Loc'.'ation: //' . $_SERVER['HTTP_HOST'] . dirname($path) . '/' . $link);
            exit;
        }
    }

    public function hookDisplayHeader() {

        // Forward to sub modules
        foreach ($this->jpresta_submodules as $jpresta_submodule) {
            $jpresta_submodule->displayHeader();
        }

        if (self::canBeCached() || self::isDisplayStats()) {
            // A bug in PS 1.6.0.6 insert jquery multiple times in CCC mode
            $already_inserted = false;
            foreach ($this->context->controller->js_files as $js_uri)
            {
                $already_inserted = $already_inserted || (strstr($js_uri, 'jquery-') !== false) || (strstr($js_uri, 'jquery.js') !== false);
            }
            if (!$already_inserted) {
                $this->context->controller->addJquery();
            }

            $this->context->controller->addJS($this->_path.'views/js/pagecache.js');
            if (self::isDisplayStats()) {
                $this->context->controller->addCSS($this->_path.'views/css/pagecache.css');
            }

            if (Tools::version_compare(_PS_VERSION_,'1.6','<=')) {
                // Make sure pagecache will be the first javascript to be loaded. This avoid
                // other javascript errors to block pagecache treatments. So we place it just after
                // jquery.
                $new_js_files = array();
                $pagecache_js_file = null;
                $jquery_js_files = array();
                foreach ($this->context->controller->js_files as $js_file) {
                    if (strstr($js_file, '/js/jquery/') !== false || strstr($js_file, 'jquery.js') !== false) {
                        $jquery_js_files[] = $js_file;
                    }
                    elseif (empty($pagecache_js_file) && strstr($js_file, 'pagecache.js') !== false) {
                        $pagecache_js_file = $js_file;
                    } else {
                        $new_js_files[] = $js_file;
                    }
                }
                if (!empty($pagecache_js_file)) {
                    array_unshift($new_js_files, $pagecache_js_file);
                }
                $jquery_js_files = array_reverse($jquery_js_files);
                foreach ($jquery_js_files as $jquery_js_file) {
                    array_unshift($new_js_files, $jquery_js_file);
                }
                $this->context->controller->js_files = $new_js_files;
            }

            if (self::canBeCached()) {
                // There is no escape method available to allow to display javascript code
                // so we cannot use a template
                $js = trim(Configuration::get('pagecache_cfgadvancedjs'));
                $dynJs = '<scr' . 'ipt type="text/javascript">
pcRunDynamicModulesJs = function() {
'; // Let the new line here!
                if (!empty($js)) {
                    $dynJs .= $js;
                }
                $dynJs .= '
};</scr' . 'ipt>'; // Let the new line here!

                return $dynJs . $this->display(__FILE__, 'pagecache.tpl');
            }
            else {
                return '';
            }
        }
        elseif (Configuration::get('pagecache_skiplogged') && Context::getContext()->customer->isLogged()) {
            // User want to disable cache for logged in users so we add a random URL parameter
            // to all links to disable previous cache done by browser
            return $this->display(__FILE__, 'pagecache-disablecache.tpl');
        } else {
            return '';
        }
    }

    public function hookdisplayMobileHeader() {
        $this->hookDisplayHeader();
    }

    public function hookActionShopDataDuplication($params) {
        //(int)$params['new_id_shop']
        //(int)$params['old_id_shop']
        $new_id_shop = (int)$params['new_id_shop'];
        $this->_setDefaultConfiguration(Shop::getGroupFromShop($new_id_shop), $new_id_shop);
    }

    public function hookActionOutputHTMLBefore($params) {
        if (self::canBeCached()) {
            // Save the generated HTML into a file and display it => create a cache
            $this->cacheThis($params['html']);
            if (self::isCacheWarmer()) {
                die('Cache refreshed (HTML is not sent to cache-warmer to save bandwidth)');
            }
        }
    }

    public function hookActionDispatcherBefore()
    {
        self::$page_cache_start_time = microtime(true);

        self::init();

        $this->pre_display_html = self::preDisplayStats();
        if (self::displayCacheIfExists()) {
            self::displayStats(true, $this->pre_display_html);
            die();
        }
    }

    public function hookActionDispatcherAfter()
    {
        $this->displayStats(false, $this->pre_display_html);
    }

    public function hookActionDispatcher() {
        if (self::canBeCached())
        {
            // Remove cookie, cart and customer informations to cache
            // a 'standard' page

            Tools::setCookieLanguage($this->context->cookie);

            // Write cookie if needed (language changed, etc.) before we remove it
            $this->context->cookie->write();

            $anonymousCookie = new Cookie($this->name, '', 1);
            $anonymousCookie->id_lang = $this->context->language->id;
            unset($anonymousCookie->detect_language);

            foreach (self::$cookies_to_preserve as $cookie_name => $cookie_value) {
                if (isset($this->context->cookie->{$cookie_name})) {
                    if ($cookie_name === $cookie_value) {
                        $anonymousCookie->{$cookie_name} = $this->context->cookie->{$cookie_name};
                    }
                    else {
                        $anonymousCookie->{$cookie_name} = $cookie_value;
                    }
                }
            }
            // Some cookies are set in header like for autolanguagecurrency module. We need to preserve them and remove
            // the others
            if (method_exists($anonymousCookie, 'getAll')) {
                foreach ($anonymousCookie->getAll() as $anonymousCookieName => $anonymousCookieValue) {
                    if (!array_key_exists($anonymousCookieName, self::$cookies_to_preserve)) {
                        unset($anonymousCookie->{$anonymousCookieName});
                    }
                }
            }

            $anonymousCustomer = self::getOrCreateCustomerWithSameGroups($this->context->customer);
            $addressForTaxes = $this->getAddressForTaxes($this->context);
            $this->context->customer = $anonymousCustomer;
            if ($addressForTaxes) {
                $this->context->customer->geoloc_id_country = $addressForTaxes->id_country;
                $this->context->customer->id_state = $addressForTaxes->id_state;
                $this->context->customer->postcode = $addressForTaxes->postcode;
                // The address of current customer will be used to generate the cache
                // We cheat the memory cache (restricted to the execution of this script) to get the correct address
                // while computing taxes.
                Cache::store('Address::getFirstCustomerAddressId_' . (int)$this->context->customer->id . '-' . (bool)true,
                    $addressForTaxes->id);
                Cache::store('Address::initialize_' . md5((int)$this->context->customer->geoloc_id_country . '-' . (int)$addressForTaxes->id_state . '-' . $addressForTaxes->postcode),
                    $addressForTaxes);
            }
            $this->context->cookie = $anonymousCookie;
            $this->context->cart = new Cart();
            $this->context->cookie->id_customer = $this->context->customer->id;
        }
        else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !headers_sent()) {
                // Be sure that the cache directive is set to improve GTMetrix and PageSpeed Insight score
                header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            }
        }
    }

    public static function getOrCreateCustomerWithSameGroups($customer, $dontCheckLogged = false) {
        if (!$customer) {
            // Just return false or null
            return $customer;
        }
        if (!$dontCheckLogged && !$customer->isLogged()) {
            // The visitor is not logged in
            $id_default_group = (int) Configuration::get('PS_UNIDENTIFIED_GROUP');
            $ids_groups = array($id_default_group);
        }
        else {
            $id_default_group = (int) $customer->id_default_group;
            $ids_groups = Customer::getGroupsStatic($customer->id);
            // Put the default group at the beginning
            foreach ($ids_groups as $arrayKey => $groupId) {
                if ($groupId === $id_default_group) {
                    $ids_groups[$arrayKey] = $ids_groups[0];
                    $ids_groups[0] = $id_default_group;
                }
            }
        }

        $currentCacheKeyUserGroupConf = json_decode(JprestaUtils::getConfigurationByShopId('pagecache_cachekey_usergroups', Shop::getContextShopID(), '{}'), true);
        if (array_key_exists($id_default_group, $currentCacheKeyUserGroupConf) && $currentCacheKeyUserGroupConf[$id_default_group]['specific_cache']) {
            $anonymousKey = 'd' . $id_default_group;
        }
        else {
            $anonymousKey = 'd0';
        }
        $displayKeys = array();
        foreach ($ids_groups as $id_group) {
            if (array_key_exists($id_group, $currentCacheKeyUserGroupConf)) {
                // Default group must be at the beginning (or at least at the same place)
                if (!in_array($currentCacheKeyUserGroupConf[$id_group]['display_key'], $displayKeys)) {
                    $displayKeys[] = $currentCacheKeyUserGroupConf[$id_group]['display_key'];
                }
            }
        }
        $anonymousKey .= '-' . md5(implode('|', $displayKeys));

        $anonymousCustomer = new JprestaCustomer();
        $anonymousCustomer = $anonymousCustomer->getByEmail($anonymousKey . '@fakeemail.com');
        if (!$anonymousCustomer) {
            $anonymousCustomer = new JprestaCustomer();
            $anonymousCustomer->email = $anonymousKey . '@fakeemail.com';
            $anonymousCustomer->active = false;
            $anonymousCustomer->firstname = 'fake-user-for-pagecache';
            $anonymousCustomer->lastname = 'do-not-delete';
            $anonymousCustomer->passwd = 'WhateverSinceItIsInactive0_';
            $anonymousCustomer->id_default_group = $id_default_group;
            $anonymousCustomer->add();
            $anonymousCustomer->updateGroup($ids_groups);
            if (Module::isInstalled('shaim_gdpr')) {
                Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'customer` SET `shaim_gdpr_active` = 1 WHERE `id_customer` = ' . (int)$anonymousCustomer->id . ';');
            }
        }
        if (!$customer->id) {
            $anonymousCustomer->id = null;
        }
        else {
            $anonymousCustomer->id = (int) $anonymousCustomer->id;
        }

        // Remove some informations so they are not visible in cache
        $anonymousCustomer->firstname = '';
        $anonymousCustomer->lastname = '';
        $anonymousCustomer->email = '';

        // Avoid the customer to be considered has banned but we want to keep the customer as disabled in DB
        Cache::store('Customer::isBanned_' . (int) $anonymousCustomer->id, false);

        return $anonymousCustomer;
    }

    /**
     * Create a cache key depending on address used to determine taxes. This cache key can be configured to reduce the
     * number of different value.
     */
    public static function getCountryStateZipcodeForTaxes($context)
    {
        static $current_loc_tax_key = null;
        if ($current_loc_tax_key === null) {
            $current_loc_tax_key = '-/-/-';
            // Taxes are determined by country, state and zipcode of the delivery or invoice address
            // If there is no cart or no address defined in cart then standard localization will be used for taxes
            $addressForTaxes = self::getAddressForTaxes($context);
            if ($addressForTaxes) {
                $cacheKey = '';
                if ((int)$addressForTaxes->id_country > 0) {
                    $country = new Country((int)$addressForTaxes->id_country);
                    $cacheKey .= $country->getFieldByLang('name') . '/';
                }
                else {
                    $cacheKey .= '*/';
                }
                if ((int)$addressForTaxes->id_state > 0) {
                    $state = new State((int)$addressForTaxes->id_state);
                    $cacheKey .= $state->getFieldByLang('name') . '/';
                }
                else {
                    $cacheKey .= '*/';
                }
                if ($addressForTaxes->postcode) {
                    $cacheKey .= $addressForTaxes->postcode;
                }
                else {
                    $cacheKey .= '*';
                }
                // Only set it once it is complete
                $current_loc_tax_key = $cacheKey;
            }
        }
        return $current_loc_tax_key;
    }

    public static function getTaxManagerDetails($context) {
        static $current_tax_manager_details = null;
        if ($current_tax_manager_details === null) {
            $current_tax_manager_details = false;
            $addressForTaxes = self::getAddressForTaxes($context);
            if ($addressForTaxes && (bool) Configuration::get('PS_TAX')) {
                $json = JprestaUtilsTaxManager::toJson($context->shop->id, $addressForTaxes);
                $current_tax_manager_details = PageCacheDAO::getOrCreateDetailsId($json);
            }
        }
        return $current_tax_manager_details;
    }

    protected static function getAddressForTaxes($context) {
        static $current_tax_address = null;
        if ($current_tax_address === null) {
            $current_tax_address = false;
            // Taxes are determined by country, state and zipcode of the delivery or invoice address
            // If there is no cart or no address defined in cart then standard localization will be used for taxes

            if ($context->cookie->jpresta_id_adress_for_taxes) {
                // Set by the Cache-Warmer
                $current_tax_address = Address::initialize($context->cookie->jpresta_id_adress_for_taxes);
            }
            else {
                /* Cart is initialized in FrontController::init which is after first call to this function */
                if ((int)$context->cookie->id_cart) {
                    $cart = new Cart($context->cookie->id_cart);
                    $id_address = $cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
                    /* If address is not set then FrontController::init will set the it with the first address of the customer */
                    if (!isset($id_address) || $id_address == 0) {
                        $id_address = (int)Address::getFirstCustomerAddressId($cart->id_customer);
                    }
                    if ($id_address) {
                        $current_tax_address = Address::initialize($id_address);
                    }
                } else {
                    if ($context->cookie->id_customer) {
                        /* There is no cart but a customer is logged in */
                        $id_address = (int)(Address::getFirstCustomerAddressId($context->cookie->id_customer));
                        if ($id_address) {
                            /* Take his first address */
                            $current_tax_address = Address::initialize($id_address);
                        }
                    }
                }
            }
            if (!$current_tax_address) {
                // As it is done in Product::getPriceStatic when there is no adress given
                $current_tax_address = Address::initialize(null, true);
            }
        }
        return $current_tax_address;
    }

    public static function getGroupsIds($context) {
        if (isset($context->customer) && $context->customer->isLogged()) {
            // Compute groups IDs like in dispatcher hook
            if ((int)$context->customer->id === 0) {
                // Handle it here because it is not in PS1.5
                $groupsIds = array((int)Configuration::get('PS_UNIDENTIFIED_GROUP'));
            }
            else {
                $groupsIds = Customer::getGroupsStatic((int)$context->customer->id);
                // Put the default group at the beginning
                foreach ($groupsIds as $arrayKey => $groupId) {
                    if ($groupId === (int) $context->customer->id_default_group) {
                        $groupsIds[$arrayKey] = $groupsIds[0];
                        $groupsIds[0] = (int) $context->customer->id_default_group;
                    }
                }
            }
        } else {
            $groupsIds = Customer::getGroupsStatic(0);
        }
        return $groupsIds;
    }

    private static function _getDynamicHookInfos($hookName, $module) {
        if (!self::canBeCached()) {
            return false;
        }
        $dyn_hooks = Configuration::get('pagecache_dyn_hooks', '');
        $dyn_hook_part = strstr($dyn_hooks, Tools::strtolower($hookName) . '|' . $module);
        if ($dyn_hook_part !== false) {
            $comma_pos = strpos($dyn_hook_part, ',');
            if ($comma_pos !== false) {
                $dyn_hook_part =  Tools::substr($dyn_hook_part, 0, $comma_pos);
            }
            $dyn_hook_part_array = array_pad(explode('|', $dyn_hook_part), 3, 0);
            $dyn_hook_part = array('empty_box' => $dyn_hook_part_array[2]);
        }
        return $dyn_hook_part;
    }

    private static function _getHookCacheDirectives($moduleName, $hookName) {
        $directives = array('wrapper' => false, 'content' => true);

        // Remove 'hook' prefix
        $hookName = str_replace('hook', '', $hookName);

        $infos = self::_getDynamicHookInfos($hookName, $moduleName);
        if ($infos !== false) {
            $directives['wrapper'] = true;
            $directives['content'] = !$infos['empty_box'];
        }
        return $directives;
    }

    private static function getDynamicWidgetInfos($moduleName, $hookName) {
        if (!self::canBeCached()) {
            return false;
        }
        $dyn_widgets = Configuration::get('pagecache_dyn_widgets', '');
        $dyn_widget_part = strstr($dyn_widgets, Tools::strtolower($moduleName) . '|' . Tools::strtolower($hookName));
        if ($dyn_widget_part === false) {
            // Kept for compatibility reason (before empty box for widget)
            $dyn_widget_part = strstr($dyn_widgets, Tools::strtolower($moduleName) . '|,');
        }
        if ($dyn_widget_part === false) {
            $dyn_widget_part = strstr($dyn_widgets, Tools::strtolower($moduleName) . '||');
        }
        if ($dyn_widget_part !== false) {
            $comma_pos = strpos($dyn_widget_part, ',');
            if ($comma_pos !== false) {
                $dyn_widget_part = Tools::substr($dyn_widget_part, 0, $comma_pos);
            }
            $dyn_widget_part_array = array_pad(explode('|', $dyn_widget_part), 3, 0);
            $dyn_widget_part = array('empty_box' => $dyn_widget_part_array[2]);
        }
        return $dyn_widget_part;
    }

    private static function _getWidgetCacheDirectives($moduleName, $hookName) {
        $directives = array('wrapper' => false, 'content' => true);
        $infos = self::getDynamicWidgetInfos($moduleName, $hookName);
        if ($infos !== false) {
            $directives['wrapper'] = true;
            $directives['content'] = !$infos['empty_box'];
        }
        return $directives;
    }

    public static function canBeCached() {
        // static variable avoid computing the canBeCached multiple times
        static $canBeCached = null;
        if ($canBeCached === null) {
            if (JprestaUtils::isAjax()) {
                $canBeCached = false;
                self::$status_reason = 'ajax';
            }
            elseif (Tools::getValue('fc') == 'module' || defined('_PS_ADMIN_DIR_')) {
                $canBeCached = false;
                self::$status_reason = 'not-front-controller';
            }
            elseif (Tools::getIsset('open') && Module::isEnabled('gsnippetsreviews')) {
                $canBeCached = false;
                self::$status_reason = 'open-gsnippetsreviews';
            }
            else {
                if (!Configuration::get('pagecache_debug') && !Configuration::get('pagecache_always_infosbox') && (Tools::getIsset('dbgpagecache') || Tools::getIsset('delpagecache'))) {
                    // Remove module's parameters in production mode to avoid them to be referenced in search engines
                    $url = self::getCurrentURL();
                    $url = preg_replace('/&?dbgpagecache=[0-1]?/', '', $url);
                    $url = preg_replace('/&?delpagecache=[0-1]?/', '', $url);
                    $url = str_replace('?&', '?', $url);
                    $url = preg_replace('/\?$/', '', $url);
                    header('Status: 301 Moved Permanently', false, 301);
                    Tools::redirect($url);
                }
                $controller = self::getControllerName();

                $canBeCached = self::isGetRequest()
                    && self::isCacheEnabledForController($controller)
                    && !self::isCustomerWithSpecificPricesOrPermissions()
                    && !self::isGoingToBeRedirected()
                    && !self::isRestrictedCountry()
                    && !self::isCustomizedProduct($controller)
                    && !self::isExcludedByRegex()
                    && self::isCacheEnabledOrDebugOn()
                    && self::isTokenDisabled()
                    && self::isOverridesEnabled()
                    && self::isNotLogout()
                    && self::isNotSkipLoggedUsers()
                ;
            }
        }
        return $canBeCached;
    }

    private static function isExcludedByRegex() {
        $regex = JprestaUtils::decodeConfiguration(Configuration::get('pagecache_ignore_url_regex'));
        if ($regex) {
            $ret = preg_match('/' . $regex . '/', self::getCurrentURL());
            if ($ret === 1) {
                self::$status_reason = 'excluded-by-regex';
                return true;
            }
            elseif ($ret === false) {
                JprestaUtils::addLog('PageCache | Error #' . preg_last_error() . ' in the regex "' . $regex . '"', 2);
            }
        }
        return false;
    }

    private static function isGetRequest() {
        $isGet = strcmp(self::getServerValue('REQUEST_METHOD'), 'GET') == 0;
        if (!$isGet) {
            self::$status_reason = 'not-a-get-request';
        }
        return $isGet;
    }

    private static function isCacheEnabledForController($controller) {
        $isEnabled = Configuration::get('pagecache_'.$controller);
        if (!$isEnabled) {
            self::$status_reason = 'disabled-controller';
        }
        return $isEnabled;
    }

    private static function isCacheEnabledOrDebugOn() {
        $isEnabled = !Configuration::get('pagecache_debug') || ((int)Tools::getValue('dbgpagecache', 0) == 1);
        if (!$isEnabled) {
            self::$status_reason = 'test-mode';
        }
        return $isEnabled;
    }

    private static function isTokenDisabled() {
        $isDisabled = (int)(Configuration::get('PS_TOKEN_ENABLE')) != 1;
        if (!$isDisabled) {
            self::$status_reason = 'tokens-enabled';
        }
        return $isDisabled;
    }

    private static function isNotLogout() {
        $isNotLogout = Tools::getValue('logout') === false && Tools::getValue('mylogout') === false;
        if (!$isNotLogout) {
            self::$status_reason = 'logout';
        }
        return $isNotLogout;
    }

    private static function isNotSkipLoggedUsers() {
        $isNotSkipLoggedUsers = !Configuration::get('pagecache_skiplogged') || !Context::getContext()->customer->isLogged();
        if (!$isNotSkipLoggedUsers) {
            self::$status_reason = 'skip-logged-users';
        }
        return $isNotSkipLoggedUsers;
    }

    /**
     * Customization is not a module and therefore cannot be refreshed. The workaround is to disable
     * cache for these products
     * @param string $controller Controller name
     * @return boolean true if current page is a customized product
     */
    private static function isCustomizedProduct($controller) {
        if (strcmp($controller, 'product') != 0 || !Customization::isFeatureActive()) {
            return false;
        }
        if ($id_product = (int)Tools::getValue('id_product')) {
            $customizationFieldCount = (int) JprestaUtils::dbGetValue('
                SELECT COUNT(*)
                FROM `'._DB_PREFIX_.'customization_field`
                WHERE `id_product` = '.(int)$id_product);
            if ($customizationFieldCount > 0) {
                self::$status_reason = 'customized-product';
                return true;
            }
        }
        return false;
    }

    /**
     * Do not cache if status code is not 200
     * @return boolean true if user will be redirected to an other page or if statuts is not 200
     */
    private static function isGoingToBeRedirected() {
        $redirect = self::isNotCode200() || self::isSSLRedirected() || self::isMaintenanceEnabled();
        if (!$redirect && Module::isEnabled('autolanguagecurrency') && Configuration::get('AUTOCURRLANG_ENABLED')) {
            if (class_exists('AutoLanguageCurrency') && method_exists('AutoLanguageCurrency', 'needsGeolocate')) {
                $redirect = AutoLanguageCurrency::needsGeolocate();
            }
            else {
                $context = Context::getContext();
                if (!isset($context->cookie->autolocation) || $context->cookie->autolocation == '0' || !$context->cookie->autolocation) {
                    $redirect = true;
                }
            }
        }
        if ($redirect) {
            self::$status_reason = 'redirect';
        }
        return $redirect;
    }

    private static function isNotCode200() {
        if (function_exists('http_response_code') && !defined('HHVM_VERSION')) {
            $code = http_response_code();
            if (!empty($code)) {
                if (http_response_code() !== 200) {
                    return true;
                }
            }
        }
        return false;
    }

    private static function isSSLRedirected() {
        return (Configuration::get('PS_SSL_ENABLED') && self::getServerValue('REQUEST_METHOD') != 'POST' && Configuration::get('PS_SSL_ENABLED_EVERYWHERE') && !Tools::usingSecureMode());
    }

    public static function isMaintenanceEnabled() {
        if (!(int)Configuration::get('PS_SHOP_ENABLE')) {
            if (!in_array(Tools::getRemoteAddr(), explode(',', Configuration::get('PS_MAINTENANCE_IP')))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Must be called after geolocalisation has been done
     * @return bool true if the visitor is located in a restricted country
     */
    private static function isRestrictedCountry() {
        $restrictedCountry = false;
        $controller_instance = self::getControllerInstance();
        if (method_exists($controller_instance, 'isRestrictedCountry')) {
            $restrictedCountry = $controller_instance->isRestrictedCountry();
            if ($restrictedCountry) {
                self::$status_reason = 'restricted-country';
            }
        }
        return $restrictedCountry;
    }

    /**
     * Cache must be disabled if a customer has a specific price, discount, permissions, etc.
     */
    private static function isCustomerWithSpecificPricesOrPermissions() {
        $context = Context::getContext();
        $id_customer = $context->customer ? $context->customer->id : 0;
        if ($id_customer > 0) {
            $now = date('Y-m-d H:i:00');
            $count_existing = 'SELECT count(*) FROM `'._DB_PREFIX_.'specific_price` WHERE id_customer='.(int)$id_customer.
                ' AND (`from` = \'0000-00-00 00:00:00\' OR \'' . pSQL($now) . '\' >= `from`)' .
                ' AND (`to` = \'0000-00-00 00:00:00\' OR \'' . pSQL($now) . '\' <= `to`)'
            ;
            if ((int) JprestaUtils::dbGetValue($count_existing) > 0) {
                // Current customer has specific prices for him so cache must be disabled
                self::$status_reason = 'customer-specific-prices';
                return true;
            }
        }
        // Compatibility with superuser module by MassonVincent
        if (Module::isEnabled('superuser')) {
            $ips = Configuration::get('superuser_ips');
            $ip = explode(',', $ips);
            if (!defined('_PS_ADMIN_DIR_')
                && (in_array('*', $ip) || in_array(Tools::getRemoteAddr(), $ip) && strstr($_SERVER['REQUEST_URI'],'mentions-legales'))
            ) {
                // Disable cache so Super User module can work
                self::$status_reason = 'superuser';
                return true;
            }
        }
        // Compatibility with atssuperuser module by ATSinfosystem Sotwares
        if (Module::isEnabled('atssuperuser') && Tools::getIsset('superuser')) {
            self::$status_reason = 'atssuperuser';
            return true;
        }
        // Compatibility with groupinc module by Idnovate
        if (Module::isEnabled('groupinc')) {
            $count_rules_with_customers = 'SELECT count(*) FROM `'._DB_PREFIX_.'groupinc_configuration` WHERE customers <> \'\'';
            if ((int) JprestaUtils::dbGetValue($count_rules_with_customers) > 0) {
                // Some rules are specific to some customers
                $count_rules_with_this_customer = 'SELECT count(*) FROM `'._DB_PREFIX_.'groupinc_configuration` WHERE customers = \''.(int)$id_customer.'\' OR customers like \''.(int)$id_customer.';%\' or customers like \'%;'.(int)$id_customer.';%\' or customers like \'%;'.(int)$id_customer.'\'';
                if ((int) JprestaUtils::dbGetValue($count_rules_with_this_customer) > 0) {
                    self::$status_reason = 'groupinc';
                    return true;
                }
            }
        }
        // Compatibility with shaim_gdpr module by Dominik Shaim
        if (Module::isEnabled('shaim_gdpr')) {
            if ((int)Configuration::get('shaim_gdpr_zpetny_souhlas_active') == 1 && $id_customer > 0) {
                $active = Db::getInstance()->executeS('SELECT `shaim_gdpr_active` FROM `' . _DB_PREFIX_ . 'customer` WHERE `id_customer` = ' . (int)$id_customer . ';');
                $active = (isset($active[0]['shaim_gdpr_active']) ? (int)$active[0]['shaim_gdpr_active'] : 0);
                if (!$active) {
                    self::$status_reason = 'shaim_gdpr';
                    return true;
                }
            }
        }
    }

    public static function isCacheWarmer() {
        return isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] === 'JPresta-Cache-Warmer';
    }

    private static function setCacheWarmerContext()
    {
        if (Configuration::get('pagecache_debug')) {
            die('Module is in test mode, warmup ignored.');
        }

        $context = Context::getContext();

        // Currency
        $currencyIsoCode = JprestaUtils::getRequestHeaderValue('jpresta-currency');
        if ($currencyIsoCode) {
            $id_currency = Currency::getIdByIsoCode($currencyIsoCode);
            if ($id_currency) {
                $context->cookie->id_currency = $id_currency;
            }
            else {
                die('Currency ' . $currencyIsoCode . ' not found, currency is not available anymore, warmup ignored.');
            }
        }

        // Country
        $countryIsoCode = JprestaUtils::getRequestHeaderValue('jpresta-country');
        if ($countryIsoCode) {
            if (!Validate::isLanguageIsoCode($countryIsoCode)) {
                die('Country ' . $countryIsoCode . ' not found, warmup ignored.');
            }
            $id_country = Country::getByIso($countryIsoCode);
            if ($id_country) {
                if (Configuration::get('PS_GEOLOCATION_ENABLED')) {
                    // Disable geolocalization
                    $_SERVER['REMOTE_ADDR'] = 'localhost';
                }
                // In case PS detects country from browser language
                $_SERVER['HTTP_ACCEPT_LANGUAGE'] = Tools::strtolower($countryIsoCode);
                // Set country
                $context->cookie->iso_code_country = $countryIsoCode;
                $context->country = new Country($id_country);
            } else {
                die('Country ' . $countryIsoCode . ' not found, country is not available anymore, warmup ignored.');
            }
        }

        // Device: handled in override of Context::getMobileDetect() with JprestaUtilsMobileDetect

        // Customer groups
        $fakeUserEmail = JprestaUtils::getRequestHeaderValue('jpresta-group');
        if ($fakeUserEmail) {
            $customer = new Customer();
            if ($customer->getByEmail($fakeUserEmail)) {
                if ((int) $customer->id_default_group !== (int) Configuration::get('PS_UNIDENTIFIED_GROUP')) {
                    // Set a JprestaCustomer object so the isLogged() method returns true
                    $context->customer = self::getOrCreateCustomerWithSameGroups($customer, true);
                    $context->cookie->id_customer = $customer->id;
                }
            }
            else {
                die('User-group ' . $fakeUserEmail . ' not found, fake user has probably been deleted, warmup ignored.');
            }
        }

        // Taxes: handled in hookTaxManager()

        // Specifics
        $id_specifics = JprestaUtils::getRequestHeaderValue('jpresta-id-specifics');
        if ($id_specifics) {
            $specifics = PageCacheDAO::getDetailsById($id_specifics);
            if ($specifics) {
                $jcks = new JprestaCacheKeySpecifics($specifics);
                self::restoreJprestaCacheKeySpecifics($jcks);
            }
            else {
                die('No specific context found, cache has probably been reset shortly, warmup ignored.');
            }
        }
    }

    /**
     * @param $params array {'address' => address of the customer, 'params' => id_tax_rules_group / type}
     * @return TaxManagerInterface|false
     */
    public function hookTaxManager($params) {
        static $tax_manager = array();
        $id_tax_rules_group = $params['params'];
        if ($id_tax_rules_group && !array_key_exists($id_tax_rules_group, $tax_manager)) {
            $tax_manager[$id_tax_rules_group] = false;
            if (self::isCacheWarmer()) {
                $id_tax_manager = JprestaUtils::getRequestHeaderValue('jpresta-id-tax-manager');
                if ($id_tax_manager) {
                    $taxManagerJson = PageCacheDAO::getDetailsById($id_tax_manager);
                    if ($taxManagerJson) {
                        try {
                            $tax_manager[$id_tax_rules_group] = new JprestaUtilsTaxManager($taxManagerJson, $id_tax_rules_group);
                        }
                        catch (Exception $e) {
                            die('Cannot build the tax manager from context, cache has probably been reset shortly, warmup ignored (Error: ' . $e->getMessage() . ')');
                        }
                    } else {
                        die('No tax manager context found, cache has probably been reset shortly, warmup ignored.');
                    }
                }
            }
        }
        if (!$id_tax_rules_group) {
            return false;
        }
        return $tax_manager[$id_tax_rules_group];
    }

    public static function isOverridesEnabled() {
        $isOverridesEnabled = Tools::version_compare(_PS_VERSION_,'1.6','<') || ((int)(Configuration::get('PS_DISABLE_OVERRIDES')) != 1);
        if (!$isOverridesEnabled) {
            self::$status_reason = 'overrides-disabled';
        }
        return $isOverridesEnabled;
    }

    /**
     * return true if it is available, false otherwise
     */
    public static function displayCacheIfExists() {
        $cache = false;
        $can_be_cached = self::canBeCached();
        if ($can_be_cached) {
            // Before checking cache, lets check cache reffreshment triggers (specific prices)
            PageCacheDAO::triggerReffreshment();

            $controller = self::getControllerName();
            $cache_ttl = 60 * ((int)Configuration::get('pagecache_'.$controller.'_timeout'));
            $jprestaCacheKey = self::getCacheKeyInfos();

            if (Tools::getIsset('delpagecache')) {
                self::getCache()->delete($jprestaCacheKey->toString());
            }

            $cache = self::getCache()->get($jprestaCacheKey->toString(), $cache_ttl);
            if ($cache !== false) {
                if (self::isCacheWarmer()) {
                    // Force cache re-generation if the request is done by the cache warmer and TTL is less than 1 day
                    $ttl = PageCacheDAO::getTtl($jprestaCacheKey, $cache_ttl / 60);
                    if ($ttl < (24 * 60) && !headers_sent()) {
                        header(self::HTTP_HEADER_CACHE_INFO . ': status=on, reason=cache-warmer-regenerate, age=0');
                        return false;
                    }
                    if ($ttl >= (24 * 60)) {
                        die('Cache already warmed up (HTML is not sent to cache-warmer to save bandwidth)');
                    }
                }
                else {
                    PageCacheDAO::incrementCountHit($jprestaCacheKey);
                }
            }

            // Store cache used in a readable cookie (0=no cache; 1=server cache; 2=browser cache)
            if (self::isDisplayStats() && !headers_sent()) {
                $cache_type = 0; // no cache available
                if ($cache !== false) {
                    // Server cache
                    $cache_type = 1;
                }
                if (PHP_VERSION_ID <= 50200) /* PHP version > 5.2.0 */
                    setcookie('pc_type_' . $jprestaCacheKey->toString(), $cache_type, time()+60*60*1, '/', null, 0);
                else
                    setcookie('pc_type_' . $jprestaCacheKey->toString(), $cache_type, time()+60*60*1, '/', null, 0, false);
            }

            // Display the cached HTML if any
            if ($cache !== false) {
                // ULTIMATE
                $offset = 60 * Configuration::get('pagecache_'.$controller.'_expires', 0);
                if ($offset > 0) {
                    if (headers_sent()) {
                        JprestaUtils::addLog("PageCache | Cannot use browser cache because headers have already been sent", 3);
                    }
                    elseif (!PageCacheDAO::hasTriggerIn2H()) {
                        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT');
                        header('Cache-Control: max-age='.$offset.', private');
                        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
                        header_remove('Pragma');
                    }
                }
                // ULTIMATE£
                $stats = PageCacheDAO::getStats($jprestaCacheKey);
                if (!headers_sent()) {
                    header(self::HTTP_HEADER_CACHE_INFO . ': status=on, reason=' . self::$status_reason . ', age=' . $stats['age']);
                }

                echo $cache;
                return true;
            }
            else {
                if (!headers_sent()) {
                    header(self::HTTP_HEADER_CACHE_INFO . ': status=on, reason=' . self::$status_reason . ', age=0');
                }
                return $cache;
            }
        }
        elseif (self::isDisplayStats()) {
            // Cache disabled
            $jprestaCacheKey = self::getCacheKeyInfos();
            if (PHP_VERSION_ID <= 50200) /* PHP version > 5.2.0 */ {
                setcookie('pc_type_' . $jprestaCacheKey->toString(), 3, time() + 60 * 60 * 1, '/', null, 0);
            } else {
                setcookie('pc_type_' . $jprestaCacheKey->toString(), 3, time() + 60 * 60 * 1, '/', null, 0, false);
            }
        }
        if (!$can_be_cached && !headers_sent()) {
            header(self::HTTP_HEADER_CACHE_INFO . ': status=off, reason=' . self::$status_reason);
        }
        return $cache;
    }

    /**
     * Generates a key for the cache depending on URL, currency, user group, country, etc.
     * Return array[0]=hashed key (int), array[1]=cache key infos (array)
     * @return JprestaCacheKey
     */
    public static function getCacheKeyInfos() {
        /**
         * @var JprestaCacheKey
         */
        static $current_cache_key_infos = false;
        if ($current_cache_key_infos === false) {

            $context = Context::getContext();
            $cacheKey = new JprestaCacheKey();

            //
            // URL
            //

            // Normalize the URL
            $normalized_url = self::normalizeUrl(self::getCurrentURL());

            // Remove HTML anchor
            $anchorPos = strpos($normalized_url, '#');
            if ($anchorPos !== FALSE) {
                $normalized_url = Tools::substr($normalized_url, 0, $anchorPos);
            }

            // Strip ignored parameters (tracking data that do not change page content)
            // and sort them
            $ignored_params = explode(',', Configuration::get('pagecache_ignored_params'));
            $ignored_params[] = 'delpagecache';
            $ignored_params[] = 'dbgpagecache';
            $ignored_params[] = 'cfgpagecache';
            $query_string = parse_url($normalized_url, PHP_URL_QUERY);
            $new_query_string = self::filterAndSortParams($query_string, $ignored_params);
            if ($new_query_string) {
                $normalized_url = http_build_url($normalized_url, array("query" => $new_query_string));
            }
            else {
                $normalized_url = http_build_url($normalized_url, array(), HTTP_URL_STRIP_QUERY);
            }
            $cacheKey->add('url', $normalized_url);

            //
            // CURRENCY
            //
            $cacheKey->add('id_currency', self::getCurrencyId($context));

            //
            // LANGUAGE
            //
            $cacheKey->add('id_lang', $context->language->id);

            //
            // CUSTOMER GROUP
            //
            $anonymousCustomer = self::getOrCreateCustomerWithSameGroups($context->customer);
            $cacheKey->add('id_fake_customer', $anonymousCustomer ? $anonymousCustomer->id : null);

            //
            // DEVICE (computer, mobile, tablet)
            //
            if (self::isDependsOnDevice()) {
                if (method_exists($context, 'getDevice')) {
                    if ($context->getDevice() === Context::DEVICE_MOBILE || (Configuration::get('pagecache_tablet_is_mobile') && $context->getDevice() === Context::DEVICE_TABLET)) {
                        $cacheKey->add('id_device', self::DEVICE_MOBILE);
                    }
                    else {
                        $cacheKey->add('id_device', self::DEVICE_COMPUTER);
                    }
                }
                elseif ($context->getMobileDevice() == true) {
                    $cacheKey->add('id_device', self::DEVICE_MOBILE);
                }
                else {
                    $cacheKey->add('id_device', self::DEVICE_COMPUTER);
                }
            }
            else {
                $cacheKey->add('id_device', self::DEVICE_COMPUTER);
            }

            //
            // COUNTRY
            //
            $country = self::getCountry($context);
            if ($country) {
                $currentCacheKeyCountryConf = json_decode(JprestaUtils::getConfigurationByShopId('pagecache_cachekey_countries', Shop::getContextShopID(), '{}'), true);
                if (array_key_exists($country->id, $currentCacheKeyCountryConf)
                    && $currentCacheKeyCountryConf[$country->id]['specific_cache']) {
                    // Only create a specific cache if it is configured like that
                    $cacheKey->add('id_country', $country->id);
                }
                else {
                    // Otherwise set the country key as 'other' (null)
                    $cacheKey->add('id_country', null);
                }
            }
            else {
                // Normally we should not be here because getCountry() will return the default country
                $cacheKey->add('id_country', null);
            }

            //
            // TAXES MANAGER
            //
            $tax_manager_details = self::getTaxManagerDetails($context);
            if ($tax_manager_details) {
                $cacheKey->add('id_tax_manager', $tax_manager_details);
            }

            //
            // RGPD and other specific determinants
            //
            $cacheKey->add('specifics', self::getJprestaCacheKeySpecifics());

            //
            // Other determinants
            //

            // Version of CSS and JS to avoid cache to reference old CSS and JS files
            if (Configuration::get('pagecache_depend_on_css_js')) {
                $cacheKey->add('css_version', Configuration::get('PS_CCCCSS_VERSION'));
                $cacheKey->add('js_version', Configuration::get('PS_CCCJS_VERSION'));
            }

            $current_cache_key_infos = $cacheKey;
        }
        return $current_cache_key_infos;
    }

    /**
     * @param $url string URL of the backlink
     * @return int Cache key as an unsigned integer
     */
    public static function getCacheKeyForBacklink($url)
    {
        // We supposed that URLs into our shop are well formatted

        // Remove HTML anchor
        $anchorPos = strpos($url, '#');
        if ($anchorPos !== FALSE) {
            $url = Tools::substr($url, 0, $anchorPos);
        }

        $jprestaCacheKey = new JprestaCacheKey();
        $jprestaCacheKey->add('url', $url);

        return $jprestaCacheKey->toInt();
    }

    private static function normalizeUrl($url) {
        $normalized_url = html_entity_decode($url);
        $un = new PageCacheURLNormalizer();
        $un->setUrl($normalized_url);
        $normalized_url = $un->normalize();
        return $normalized_url;
    }

    private static function getCookieValue($cookieName, $defaultValue = '') {
        if (array_key_exists($cookieName, $_COOKIE)) {
            // Necessary to avoid errors in Prestashop Addons validator
            foreach ($_COOKIE as $key => $cookieValue) {
                if ($key === $cookieName) {
                    return $cookieValue;
                }
            }
        }
        return $defaultValue;
    }

    /**
     * @param $specifics JprestaCacheKeySpecifics
     */
    private static function restoreJprestaCacheKeySpecifics($specifics) {
        // Restore cookies and sessions datas
        $specifics->restoreCookies();

        // Now restore other specifics behavior for specific modules
        $context = Context::getContext();

        // For gdprpro (2.1.1) module by PrestaChamps
        if (Module::isEnabled('gdprpro')) {
            if (file_exists(_PS_MODULE_DIR_ . 'gdprpro/src/GdprProConfig.php')
                && file_exists(_PS_MODULE_DIR_ . 'gdprpro/src/GdprProCookie.php')) {
                require_once _PS_MODULE_DIR_ . 'gdprpro/src/GdprProConfig.php';
                require_once _PS_MODULE_DIR_ . 'gdprpro/src/GdprProCookie.php';
                if (class_exists('GdprProCookie') && method_exists('GdprProCookie', 'getInstance')) {
                    // The cookie is read before any hook in getHookModuleExecList() so we need to read it again
                    GdprProCookie::getInstance()->content = json_decode($context->cookie->gdpr_conf, true);
                }
            }
        }

        // For webpgenerator module by PrestaChamps
        if (Module::isEnabled('webpgenerator')) {
            if ($specifics->getValue('webpgenerator') === 'acceptwebp') {
                $_SERVER['HTTP_ACCEPT'] = $_SERVER['HTTP_ACCEPT'] . ',image/webp';
            }
        }

        // Handle vat_view (for shop cinelight.eu)
        if ($context->customer && property_exists($context->customer, 'vat_view')) {
            $context->customer->vat_view = $specifics->getValue('vat_view');
        }
    }

    /**
     * @return JprestaCacheKeySpecifics|null
     */
    private static function getJprestaCacheKeySpecifics() {
        $specifics = new JprestaCacheKeySpecifics();
        $context = Context::getContext();

        // For gdprpro (2.1.1) module by PrestaChamps
        if (Module::isEnabled('gdprpro')) {
            $specifics->keepPsCookie('gdpr_conf');
            $specifics->keepCookie('gdpr_windows_was_opened');
        }

        // For generaldataprotectionregulation (2.0.11) module by Active Design
        if (Module::isEnabled('generaldataprotectionregulation')) {
            $specifics->keepCookie('Accepted');
            $specifics->keepCookie('cookiesDenied');
            $specifics->keepCookie('cookiesAccepted');
        }

        // For ageverify module by Musaffar Patel
        if (Module::isEnabled('ageverify')) {
            // session based (each visit) check
            if (Configuration::get('av_display_frequency') == 'each_visit') {
                $specifics->keepOtherPsCookie('age_verify_session');
            } else {
                $specifics->keepPsCookie('age_verify');
            }
        }

        // For ageverifyer module by Simon Agostini
        if (Module::isEnabled('ageverifyer')) {
            // To uncomment if needed (forbidden by Prestashop Addons)
            //session_start();
            $specifics->keepSessionProperty('over18');
        }

        // For kbgdpr module by Knowband
        if (Module::isEnabled('kbgdpr')) {
            $cookie_law_settings = Tools::jsonDecode(Configuration::get('GDPR_COOKIE_LAW_SETTINGS'), true);
            $specifics->keepCookie($cookie_law_settings['cookie_name']);
        }

        // For uecookie module by MyPresta.eu
        if (Module::isEnabled('uecookie')) {
            $specifics->keepCookie('cookie_ue');
        }

        // For idxcookies module by Idnovate
        // TODO Remove the date before saving : "idxcookiesWarningCheck": "{\"accepted\":true,\"banned\":[],\"date\":\"2021-01-20 10:11:58\"}"
        if (Module::isEnabled('idxcookies')) {
            $specifics->keepCookie('idxcookiesWarningCheck');
        }

        // For validatevatnumber module by ActiveDesign
        if (Module::isEnabled('validatevatnumber')) {
            $specifics->keepPsCookie('guest_taxes');
        }

        // For deluxecookies module by innovadeluxe
        if (Module::isEnabled('deluxecookies')) {
            $specifics->keepCookie('deluxecookies');
            $specifics->keepCookie('deluxecookiesWarningCheck');
        }

        // For webpgenerator module by PrestaChamps
        if (Module::isEnabled('webpgenerator') && isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false) {
            $specifics->keepValue('webpgenerator', 'acceptwebp');
        }

        // For tnzcookie (1.6.6) module by Tanzo
        if (Module::isEnabled('tnzcookie')) {
            $specifics->keepOtherPsCookie('TNZCOOKIE_COOKIE');
        }

        // For lgcookieslaw (1.4.1) module by Línea Gráfica
        if (Module::isEnabled('lgcookieslaw')) {
            $specifics->keepCookie(Configuration::get('PS_LGCOOKIES_NAME'));
            if (!isset($_COOKIE[Configuration::get('PS_LGCOOKIES_NAME')])
                && (!isset($_SERVER['HTTP_USER_AGENT']) || !preg_match('/' . str_replace(',', '|', Configuration::get('PS_LGCOOKIES_BOTS')) . '/i', $_SERVER['HTTP_USER_AGENT']))) {
                $specifics->keepValue('lgcookieslaw_bots', 'Bot detected');
            }
            if (Configuration::get('PS_LGCOOKIES_TESTMODE') == 1 && Configuration::get('PS_LGCOOKIES_IPTESTMODE') == $_SERVER['REMOTE_ADDR']) {
                $specifics->keepValue('lgcookieslaw_mode', 'Test mode');
            }
        }

        // For cookiesplus by idnovate
        if (Module::isEnabled('cookiesplus')) {
            $cookiesplus = Module::getInstanceByName('cookiesplus');
            if (Tools::version_compare($cookiesplus->version,'1.3','>=')) {
                if (Configuration::get('C_P_COOKIE')) {
                    $specifics->keepCookie('cookiesplus');
                }
                else {
                    $specifics->keepPsCookie('cookiesplus');
                }
            }
            else {
                if (Configuration::get('C_P_ENABLE')
                    && (!isset($context->cookie->psnotice) || $context->cookie->psnotice != '2')
                    && (!isset($_SERVER['HTTP_USER_AGENT']) || !preg_match('/' . Configuration::get('C_P_BOTS') . '/i',
                            $_SERVER['HTTP_USER_AGENT']))
                    && !in_array(Tools::getRemoteAddr(), explode('|', Configuration::get('C_P_IPS')))) {

                    $specifics->keepPsCookie('psnotice');
                    $specifics->keepValue('cookiesplus', 'withoutcookie');
                } else {
                    $specifics->keepValue('cookiesplus', 'withcookie');
                }
            }
        }

        // For systemina_employeefilter module by Systemina (support@systemina.dk)
        if (Module::isEnabled('systemina_employeefilter')) {
            $cookie = new Cookie('psAdmin', '', (int)Configuration::get('PS_COOKIE_LIFETIME_BO'));
            $employee = new Employee((int)$cookie->id_employee);

            if (Validate::isLoadedObject($employee) && $employee->checkPassword((int)$cookie->id_employee, $cookie->passwd)
                && (!isset($cookie->remote_addr) || $cookie->remote_addr == ip2long(Tools::getRemoteAddr()) || !Configuration::get('PS_COOKIE_CHECKIP'))) {
                $specifics->keepValue('systemina_employeefilter', 'EmployeeLoggedin');
            } else {
                $specifics->keepValue('systemina_employeefilter', 'EmployeeNotLoggedin');
            }
        }

        // For pm_advancedcookiebanner module by Presta-Module
        if (Module::isEnabled('pm_advancedcookiebanner') && class_exists('AcbCookie') && method_exists('AcbCookie', 'getConsentLevel')) {
            $pmCookieContent = self::getCookieValue(AcbCookie::COOKIE_NAME, false);
            $pmConfigMode = Configuration::get('PM_ACB_CONFIG_MODE');
            $pmCmsPage = Tools::getIsset('acb_cms') || (Tools::getIsset('id_cms') && Tools::getValue('id_cms') == Configuration::get('PM_ACB_CMS'));
            $pmGdprMode = Configuration::get('PM_ACB_GDPR_MODE');

            $specifics->keepCookie(AcbCookie::COOKIE_NAME);
            if ($pmGdprMode == 1) {
                $specifics->keepValue('pm_advancedcookiebanner_gdpr', 1);
            }
            if (!$pmConfigMode) {
                if ($pmCookieContent === false && !$pmCmsPage) {
                    $specifics->keepValue('pm_advancedcookiebanner_mode', 1);
                }
                else {
                    $specifics->keepValue('pm_advancedcookiebanner_mode', 0);
                }
            }
            else {
                $maintenance_ips = explode(',', Configuration::get('PS_MAINTENANCE_IP'));
                if (in_array(Tools::getRemoteAddr(), $maintenance_ips)) {
                    $specifics->keepValue('pm_advancedcookiebanner_mode', 1);
                } else {
                    $specifics->keepValue('pm_advancedcookiebanner_mode', 0);
                }
            }
        }

        // For deluxecookies module by innovadeluxe
        if (Module::isEnabled('deluxecookies')) {
            $module = Module::getInstanceByName('deluxecookies');
            $specifics->keepValue('deluxecookies_disabled_mods', $module->getDisabledModules());
            if (self::getCookieValue('deluxecookiesWarningCheck', false)) {
                $specifics->keepValue('deluxecookies_dialog', 1);
            }
        }

        // For webpgenerator module by PrestaChamps
        if (Module::isEnabled('webpgenerator') && isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false) {
            $specifics->keepValue('webp', 1);
        }

        // For ultimateimagetool module by advancedplugins
        if (Module::isEnabled('ultimateimagetool')
            && (int)Configuration::get('uit_use_webp') == 1
            && (int)Configuration::get('uit_use_webp_termination') == 1
            && (isset($_SERVER['HTTP_ACCEPT']) === true)
            && (false !== strpos($_SERVER['HTTP_ACCEPT'], 'image/webp')))
        {
            $specifics->keepValue('webp', 1);
        }

        // For hicookielaw module by hipresta
        if (Module::isEnabled('hicookielaw')) {
            $module = Module::getInstanceByName('hicookielaw');
            if (   method_exists($module, 'isIPWhiteListed') && $module->isIPWhiteListed()
                || method_exists($module, 'isBot') && $module->isBot()) {
                $context->cookie->hiThirdPartyCookies = true;
            }
            $specifics->keepPsCookie('hiThirdPartyCookies');
        }

        // Handle vat_view (for shop cinelight.eu)
        if ($context->customer && property_exists($context->customer, 'vat_view')) {
            $specifics->keepValue('vat_view', $context->customer->vat_view);
        }

        // For iubenda module by iubenda
        if (Module::isEnabled('iubenda')) {
            $module = Module::getInstanceByName('iubenda');
            if (method_exists($module, 'getJPrestaCacheKey')) {
                $specifics->keepValue('iubenda', $module->getJPrestaCacheKey());
            }
        }

        return ($specifics->isEmpty() ? null : $specifics);
    }

    public static function updateCacheKeyForCountries() {
        $allShopIds = Shop::getCompleteListOfShopsID();
        foreach ($allShopIds as $shopId) {
            $currentConf = json_decode(JprestaUtils::getConfigurationByShopId('pagecache_cachekey_countries', $shopId, '{}'), true);
            $checkedCountries = [];

            $countryRows = JprestaUtils::dbSelectRows('SELECT c.id_country
                FROM `' . _DB_PREFIX_ . 'country` c
                LEFT JOIN `' . _DB_PREFIX_ . 'country_shop` cs ON (cs.`id_country`= c.`id_country`)
                WHERE c.active=1 AND id_shop=' . (int) $shopId);
            foreach ($countryRows as $countryRow) {
                if (!array_key_exists((int)$countryRow['id_country'], $currentConf)) {
                    $currentConf[$countryRow['id_country']] = ['specific_cache' => false, 'impact_count' => 0];
                }
                $currentConf[$countryRow['id_country']]['impact_count'] = self::getImpactCountForCountries($countryRow['id_country'], $shopId);
                if ($currentConf[$countryRow['id_country']]['impact_count'] > 0) {
                    // Force a specific cache to be created if any constraint exists
                    $currentConf[$countryRow['id_country']]['specific_cache'] = true;
                }
                $checkedCountries[$countryRow['id_country']] = true;
            }
            // Remove old countries from the configuration
            foreach ($currentConf as $id_country => $val) {
                if (!array_key_exists($id_country, $checkedCountries)) {
                    unset($currentConf[$id_country]);
                }
            }
            JprestaUtils::saveConfigurationByShopId('pagecache_cachekey_countries', json_encode($currentConf), $shopId);
        }
    }

    public static function updateCacheKeyForUserGroups() {
        $allShopIds = Shop::getCompleteListOfShopsID();
        foreach ($allShopIds as $shopId) {
            $currentConf = json_decode(JprestaUtils::getConfigurationByShopId('pagecache_cachekey_usergroups', $shopId, '{}'), true);
            $checkedUserGroups = [];

            $userGroupRows = JprestaUtils::dbSelectRows('SELECT *
                FROM `' . _DB_PREFIX_ . 'group` g
                LEFT JOIN `' . _DB_PREFIX_ . 'group_shop` gs ON (gs.`id_group`= g.`id_group`)
                WHERE gs.id_shop=' . (int) $shopId);
            foreach ($userGroupRows as $userGroupRow) {
                if (!array_key_exists((int)$userGroupRow['id_group'], $currentConf)) {
                    $currentConf[$userGroupRow['id_group']] = ['specific_cache' => false, 'impact_count_as_default' => 0];
                }
                $currentConf[$userGroupRow['id_group']]['impact_count_as_default'] = self::getImpactCountForUserGroupAsDefault($userGroupRow['id_group'], $shopId);

                // The display_key will be used to find similar user group when impact_count_as_default=0
                $currentConf[$userGroupRow['id_group']]['display_key'] = $userGroupRow['price_display_method'] . '|' . $userGroupRow['show_prices'];
                $currentConf[$userGroupRow['id_group']]['display_key'] .= '|' . JprestaUtils::dbGetValue('SELECT MD5(GROUP_CONCAT(id_module SEPARATOR \'|\')) FROM `' . _DB_PREFIX_ . 'module_group` WHERE id_group=' . (int) $userGroupRow['id_group'] . ' GROUP BY id_group');
                $currentConf[$userGroupRow['id_group']]['display_key'] .= '|' . JprestaUtils::dbGetValue('SELECT MD5(GROUP_CONCAT(id_category SEPARATOR \'|\')) FROM `' . _DB_PREFIX_ . 'category_group` WHERE id_group=' . (int) $userGroupRow['id_group'] . ' GROUP BY id_group');
                if ((int) $userGroupRow['id_group'] === (int) Configuration::get('PS_UNIDENTIFIED_GROUP')
                    || (int) $userGroupRow['id_group'] === (int) Configuration::get('PS_GUEST_GROUP')) {
                    $currentConf[$userGroupRow['id_group']]['display_key'] .= '|not_connected';
                }
                else {
                    $currentConf[$userGroupRow['id_group']]['display_key'] .= '|connected';
                }

                if ($currentConf[$userGroupRow['id_group']]['impact_count_as_default'] > 0) {
                    // Force a specific cache to be created if any constraint exists
                    $currentConf[$userGroupRow['id_group']]['specific_cache'] = true;
                }
                $checkedUserGroups[$userGroupRow['id_group']] = true;
            }
            // Remove old user groups from the configuration
            foreach ($currentConf as $id_group => $val) {
                if (!array_key_exists($id_group, $checkedUserGroups)) {
                    unset($currentConf[$id_group]);
                }
            }
            JprestaUtils::saveConfigurationByShopId('pagecache_cachekey_usergroups', json_encode($currentConf), $shopId);
        }
        JprestaUtils::saveConfigurationAllShop('pagecache_cachekey_usergroups_upd', false);
    }

    /**
     * @param int $id_country
     * @param int $id_shop
     * @return int Number of impact that this country has on the specified shop
     */
    private static function getImpactCountForCountries($id_country, $id_shop)
    {
        $andShopIdClause = '';
        if (Shop::isFeatureActive()) {
            $andShopIdClause =  ' AND id_shop=' . (int)$id_shop;
        }
        // Price rules for catalog
        $count = (int) JprestaUtils::dbGetValue('SELECT count(*)
                FROM `' . _DB_PREFIX_ . 'specific_price_rule`
                WHERE id_country=' . (int)$id_country . $andShopIdClause . ' AND (`to` IS NULL OR `to` > CURRENT_TIMESTAMP)');
        // Price rules for a specific product
        // TODO Test with specific price on shop group
        $count += (int) JprestaUtils::dbGetValue('SELECT count(*)
                FROM `' . _DB_PREFIX_ . 'specific_price`
                WHERE id_country=' . (int)$id_country . $andShopIdClause . ' AND (`to` IS NULL OR `to` > CURRENT_TIMESTAMP)');
        // Cart rules => cart rules do not change the price which is displayed so they do not change the cache content, they can be ignored
        return $count;
    }

    /**
     * @param int $id_group
     * @param int $id_shop
     * @return int Number of impact that this user group has on the specified shop
     */
    private static function getImpactCountForUserGroupAsDefault($id_group, $id_shop)
    {
        $count = 0;
        $andShopIdClause = '';
        if (Shop::isFeatureActive()) {
            $andShopIdClause =  ' AND id_shop=' . (int)$id_shop;
        }

        // Discount for the group
        $userGroupRow = JprestaUtils::dbSelectRows('SELECT *
                FROM `' . _DB_PREFIX_ . 'group` g
                LEFT JOIN `' . _DB_PREFIX_ . 'group_shop` gs ON (gs.`id_group`= g.`id_group`)
                WHERE g.id_group=' . (int) $id_group);
        if (count($userGroupRow) > 0 && ((double)$userGroupRow[0]['reduction']) > 0.0) {
            $count++;
        }
        // Discount for the group on categories
        $count += (int) JprestaUtils::dbGetValue('SELECT count(*)
                FROM `' . _DB_PREFIX_ . 'group_reduction`
                WHERE id_group=' . (int)$id_group);
        // Price rules for catalog
        $count += (int) JprestaUtils::dbGetValue('SELECT count(*)
                FROM `' . _DB_PREFIX_ . 'specific_price_rule`
                WHERE id_group=' . (int)$id_group . $andShopIdClause . ' AND (`to` IS NULL OR `to` > CURRENT_TIMESTAMP)');
        // Price rules for a specific product
        // TODO Test with specific price on shop group
        $count += (int) JprestaUtils::dbGetValue('SELECT count(*)
                FROM `' . _DB_PREFIX_ . 'specific_price`
                WHERE id_group=' . (int)$id_group . $andShopIdClause . ' AND (`to` IS NULL OR `to` > CURRENT_TIMESTAMP)');
        // Cart rules => cart rules do not change the price which is displayed so they do not change the cache content, they can be ignored
        return $count;
    }

    public function hookActionObjectSpecificPriceRuleAddAfter() {
        self::updateCacheKeyForCountries();
        self::updateCacheKeyForUserGroups();
    }

    public function hookActionObjectSpecificPriceRuleUpdateAfter() {
        self::updateCacheKeyForCountries();
        self::updateCacheKeyForUserGroups();
    }

    public function hookActionObjectSpecificPriceRuleDeleteAfter() {
        self::updateCacheKeyForCountries();
        self::updateCacheKeyForUserGroups();
    }

    public function hookActionObjectGroupAddAfter() {
        // Some datas are set after the hook is called :-(
        JprestaUtils::saveConfigurationAllShop("pagecache_cachekey_usergroups_upd", true);
    }

    public function hookActionObjectGroupUpdateAfter() {
        // Some datas are set after the hook is called :-(
        JprestaUtils::saveConfigurationAllShop("pagecache_cachekey_usergroups_upd", true);
    }

    public function hookActionObjectGroupDeleteAfter() {
        // Some datas are set after the hook is called :-(
        JprestaUtils::saveConfigurationAllShop("pagecache_cachekey_usergroups_upd", true);
    }

    public function hookActionObjectSpecificPriceDeleteAfter() {
        self::updateCacheKeyForCountries();
        self::updateCacheKeyForUserGroups();
    }

    /**
     * Execute all module hook/widget/widget_block for the dynamic ajax request
     */
    public static function execDynamicHooks($controllerInstance = false)
    {
        $result = array();

        // Execute header hook to get JS definitions
        if ($controllerInstance && $controllerInstance instanceof ProductListingFrontController && Configuration::get('pagecache_exec_header_hook')) {
            Tools::setCookieLanguage(Context::getContext()->cookie);
            Hook::exec('displayHeader');
        }

        $index = 0;
        do {
            $val = Tools::getValue('hk_' . $index);
            if ($val !== false) {
                // Make it safe
                $val = htmlentities($val);

                list($hookId, $hookType, $id_module, $hook_name, $hook_args) = explode('|', $val);
                $moduleInstance = Module::getInstanceById($id_module);
                if ($moduleInstance) {
                    try {
                        // Initialize parameters from ids if any (product, category, etc.)
                        $args = array();
                        if (!empty($hook_args)) {
                            $argvalues = explode('^', $hook_args);
                            if (is_array($argvalues)) {
                                foreach ($argvalues as $argvalue) {
                                    if (strpos($argvalue, '=') !== false) {
                                        list($arg, $value) = explode('=', $argvalue);
                                        if (strcmp('pc_ipa', $arg) === 0) {
                                            $args['product'] = (array)new Product((int)$value);
                                            $args['product']['id_product'] = $value;
                                            $args['product']['quantity'] = Product::getQuantity(
                                                (int)$value,
                                                0,
                                                isset($args['product']['cache_is_pack']) ? $args['product']['cache_is_pack'] : null,
                                                Context::getContext()->cart
                                            );
                                            if (!array_key_exists('id_product_attribute', $args['product']) || $args['product']['id_product_attribute'] === null) {
                                                $args['product']['id_product_attribute'] = Product::getDefaultAttribute($value);
                                            }
                                            $args['product']['quantity_all_versions'] = $args['product']['quantity'];
                                        } else if (strcmp('pc_ip', $arg) === 0) {
                                            $args['product'] = new Product((int)$value);
                                            if (method_exists($args['product'], 'loadStockData')) {
                                                $args['product']->loadStockData();
                                            }
                                        } else if (strcmp('pc_ica', $arg) === 0) {
                                            $args['category'] = (array)new Category((int)$value);
                                            $args['category']['id_category'] = $value;
                                        } else if (strcmp('pc_ic', $arg) === 0) {
                                            $args['category'] = new Category((int)$value);
                                        } else {
                                            $args[$arg] = urldecode($value);
                                        }
                                    }
                                }
                            }
                        }

                        if (strpos(self::HOOK_TYPE_MODULE, $hookType) === 0) {
                            // Display a module hook
                            $hook_name = str_replace('hook', '', $hook_name);
                            $array_return = in_array(Tools::strtolower($hook_name), array('displayproductextracontent'));
                            $result[$hookId] = Hook::exec($hook_name, $args, (int)$id_module, $array_return);

                        } else if (strpos(self::HOOK_TYPE_WIDGET, $hookType) === 0) {
                            // Display a widget tag
                            $result[$hookId] = $moduleInstance->renderWidget($hook_name, $args);

                        } else if (strpos(self::HOOK_TYPE_WIDGET_BLOCK, $hookType) === 0) {
                            // Display a widget_block tag
                            $blockKey = $hook_name;
                            $tpl = self::getWidgetBlockTemplate($blockKey);
                            $scopedVariables = $moduleInstance->getWidgetVariables(null, $args);
                            $smarty = Context::getContext()->smarty;
                            foreach ($scopedVariables as $key => $value) {
                                $smarty->assign($key, $value);
                            }
                            $result[$hookId] = $moduleInstance->fetch($tpl);
                        }
                        if (is_array($result[$hookId])
                            && array_key_exists($moduleInstance->name, $result[$hookId])
                            && is_array($result[$hookId][$moduleInstance->name])
                            && array_key_exists('pec_idx', $args)
                            && array_key_exists($args['pec_idx'], $result[$hookId][$moduleInstance->name])
                            && $result[$hookId][$moduleInstance->name][$args['pec_idx']] instanceof PrestaShop\PrestaShop\Core\Product\ProductExtraContent) {
                            // Handle the hookDisplayProductExtraContent hook
                            $result[$hookId] = $result[$hookId][$moduleInstance->name][$args['pec_idx']]->getContent();
                        }
                        else if (is_array($result[$hookId])
                            && array_key_exists(0, $result[$hookId])
                            && $result[$hookId][0] instanceof PrestaShop\PrestaShop\Core\Product\ProductExtraContent) {
                            // Handle the hookDisplayProductExtraContent hook
                            $result[$hookId] = $result[$hookId][0]->getContent();
                        }
                    }
                    catch (Exception $e) {
                        $result[$hookId] = '<!-- Error during hook (' . $moduleInstance->name . '): '. $e->getMessage() . '-->';
                    }
                }
            }
            $index++;
        } while ($val !== false);
        return $result;
    }

    private static function saveProfiling($moduleInstance, $description, $duration) {
        static $profiling = null;
        if ($profiling === null) {
            $profiling = (bool) Configuration::get('pagecache_profiling');
        }
        static $profilingMaxReached = null;
        if ($profilingMaxReached === null) {
            $profilingMaxReached = (bool) Configuration::get('pagecache_profiling_max_reached');
        }
        static $profilingTriggerMinMs = null;
        if ($profilingTriggerMinMs === null) {
            $profilingTriggerMinMs = (int) Configuration::get('pagecache_profiling_min_ms');
        }
        if ($profiling && !$profilingMaxReached && $duration >= $profilingTriggerMinMs) {
            if (!PageCacheDAO::addProfiling($moduleInstance->id, $description, $duration, self::PROFILING_MAX_RECORD)) {
                Configuration::updateValue('pagecache_profiling_max_reached', true);
            }
        }
    }

    public static function execHook($hookType, $moduleInstance, $hookName, $hookArgs) {
        $returnValue = '';
        if (self::preHook($returnValue, $hookType, $moduleInstance, $hookName, $hookArgs)) {
            Tools::setCookieLanguage(Context::getContext()->cookie);
            $hookValue = false;
            $startExecutionTime = microtime(true);
            if ($hookType === self::HOOK_TYPE_MODULE) {
                $hookValue = $moduleInstance->{$hookName}($hookArgs);
                // Do profiling (if enabled)
                self::saveProfiling($moduleInstance, "$hookName()", (microtime(true) - $startExecutionTime)*1000);
            }
            elseif ($hookType === self::HOOK_TYPE_WIDGET) {
                $hookValue = $moduleInstance->renderWidget($hookName, $hookArgs);
                // Do profiling (if enabled)
                self::saveProfiling($moduleInstance, "renderWidget('$hookName')", (microtime(true) - $startExecutionTime)*1000);
            }

            if (is_array($hookValue) && array_key_exists(0, $hookValue) && $hookValue[0] instanceof PrestaShop\PrestaShop\Core\Product\ProductExtraContent) {
                // Handle the hookDisplayProductExtraContent hook
                if (!is_array($hookArgs)) {
                    $hookArgs = array();
                }
                foreach ($hookValue as $pecKey => $pec) {
                    if ($pec instanceof PrestaShop\PrestaShop\Core\Product\ProductExtraContent) {
                        $extraContent = $pec->getContent();
                        if (is_string($extraContent)) {
                            $newExtraContent = '';
                            $hookArgs['pec_idx'] = $pecKey;
                            if (self::preHook($newExtraContent, $hookType, $moduleInstance, $hookName, $hookArgs)) {
                                $newExtraContent = $newExtraContent . $extraContent;
                            }
                            self::postHook($newExtraContent, $hookType, $moduleInstance, $hookName);
                            $pec->setContent($newExtraContent);
                        }
                    }
                }
                return $hookValue;
            }
            elseif (!is_string($hookValue) && $hookValue !== false && $hookValue !== null) {
                // Handle non string returned values
                return $hookValue;
            }
            else {
                if ($returnValue === '') {
                    $returnValue = $hookValue;
                }
                else {
                    $returnValue .= $hookValue;
                }
            }
        }
        self::postHook($returnValue, $hookType, $moduleInstance, $hookName);
        return $returnValue;
    }

    public static function preHook(&$output, $hookType, $moduleInstance, $hookName, $hookArgs)
    {
        $displayContent = true;
        if (!JprestaUtils::isAjax() && strcasecmp('hookmoduleroutes', $hookName) !== 0) {
            if (strcmp(self::HOOK_TYPE_MODULE, $hookType) === 0) {
                $directives = self::_getHookCacheDirectives($moduleInstance->name, $hookName);
            }
            else {
                $directives = self::_getWidgetCacheDirectives($moduleInstance->name, $hookName);
            }
            if (self::canBeCached() && Configuration::get('pagecache_debug') && (($hookName === null && strcmp(self::HOOK_TYPE_MODULE, $hookType) !== 0) || (stripos($hookName, 'display') !== FALSE && strcmp($hookName, 'displayoverridetemplate') === FALSE))) {
                $output .= '<!-- preHook module=' . $moduleInstance->name . ' hook=' . $hookName. ' type=' . $hookType . ' -->';
            }
            if ($directives['wrapper']) {
                $hookToCall = $hookName;
                if (method_exists('Hook', 'normalizeHookName')) {
                    $hookToCall = Hook::normalizeHookName(str_replace('hook', '', $hookName));
                }
                $output .= '<d' . 'iv id="' . uniqid('dyn') . '" class="dynhook pc_' . $hookName . '_' . $moduleInstance->id . '" data-module="' . $moduleInstance->id . '" data-hook="' . $hookToCall . '" data-hooktype="' . $hookType . '" data-hookargs="';
                foreach ($hookArgs as $hookArgName => $hookArgValue) {
                    if (strcmp('product', $hookArgName) === 0) {
                        if (is_array($hookArgs['product']) || $hookArgs['product'] instanceof ArrayAccess) {
                            $output .= 'pc_ipa=' . $hookArgs['product']['id_product'] . '^';
                        }
                        elseif (is_object($hookArgs['product']) && property_exists($hookArgs['product'], 'id')) {
                            $output .= 'pc_ip=' . $hookArgs['product']->id . '^';
                        }
                        elseif (is_integer($hookArgs['product'])) {
                            $output .= 'pc_ip=' . $hookArgs['product'] . '^';
                        }
                    }
                    if (strcmp('category', $hookArgName) === 0) {
                        if (is_array($hookArgs['category']) || $hookArgs['category'] instanceof ArrayAccess) {
                            $output .= 'pc_ica=' . $hookArgs['category']['id_category'] . '^';
                        }
                        elseif (is_object($hookArgs['category']) && property_exists($hookArgs['category'], 'id')) {
                            $output .= 'pc_ic=' . $hookArgs['category']->id . '^';
                        }
                        elseif (is_integer($hookArgs['category'])) {
                            $output .= 'pc_ic=' . $hookArgs['category'] . '^';
                        }
                    }
                    elseif (is_int($hookArgValue)) {
                        $output .= $hookArgName . '=' . (int) $hookArgValue . '^';
                    }
                    elseif (is_bool($hookArgValue)) {
                        $output .= $hookArgName . '=' . ($hookArgValue ? '0' : '1') . '^';
                    }
                    elseif (is_string($hookArgValue)) {
                        $output .= $hookArgName . '=' . urlencode($hookArgValue) . '^';
                    }
                }
                $output .= '"><d' . 'iv class="loadingempty"></di' . 'v>';
                $displayContent = $directives['content'];
            }
        }
        return $displayContent;
    }

    public static function postHook(&$output, $hookType, $moduleInstance, $hookName)
    {
        if (!JprestaUtils::isAjax() && strcasecmp('hookmoduleroutes', $hookName) !== 0) {
            if (strcmp(self::HOOK_TYPE_MODULE, $hookType) === 0) {
                $directives = self::_getHookCacheDirectives($moduleInstance->name, $hookName);
            }
            else {
                $directives = self::_getWidgetCacheDirectives($moduleInstance->name, $hookName);
            }
            if ($directives['wrapper']) {
                $output .= '</d' . 'iv>';
            }
            if (self::canBeCached() && Configuration::get('pagecache_debug') && (($hookName === null && strcmp(self::HOOK_TYPE_MODULE, $hookType) !== 0) || (stripos($hookName, 'display') !== FALSE && strcmp($hookName, 'displayoverridetemplate') === FALSE))) {
                $output .= '<!-- postHook module=' . $moduleInstance->name . ' hook=' . $hookName. ' type=' . $hookType . ' -->';
            }
        }
    }

    /**
     * Call preHook and postHook for widget_block
     * @param $params Parameters on widget block tag
     * @param $content HTML content of the widget block
     * @param $smarty Smarty instance
     * @return string Modified content of the widget block
     */
    public static function smartyWidgetBlockPageCache($params, $content, &$smarty)
    {
        $output = '';
        if (null === $content) {
            // Function is called twice: at the opening of the block
            // and when it is closed.
            // This is the first call.
            $output = smartyWidgetBlock($params, $content, $smarty);
        } else {
            // Function gets called for the closing tag of the block.
            $html = smartyWidgetBlock($params, $content, $smarty);

            if (array_key_exists('pckey', $params)) {
                $blockKey = $params['pckey'];
                $moduleName = $params['name'];
                $moduleInstance = Module::getInstanceByName($moduleName);
                if (self::preHook($output, self::HOOK_TYPE_WIDGET_BLOCK, $moduleInstance, $blockKey, $params)) {
                    $output .= $html;
                }
                self::postHook($output, self::HOOK_TYPE_WIDGET_BLOCK, $moduleInstance, $blockKey);
            }
            else {
                $output = $html;
            }
        }
        return $output;
    }

    /**
     * Called just before smarty compilation. It adds an attribute 'pckey' to all widget_block tag to extract and
     * save the template block into a file to be able to refresh this part separately (with dynamic ajax request
     * @param $source Smarty template content
     * @param $smarty Smarty instance
     * @return string Modified template content
     */
    public static function smartyWidgetBlockPageCachePrefilter($source, $smarty) {
        $lastOffset = Tools::strpos($source, '{widget_block');
        if ($lastOffset !== false) {
            $moduleInstance = Module::getInstanceByName('pagecache');
            if ($moduleInstance) {
                $modifiedSource = Tools::substr($source, 0, $lastOffset);
                // Find widget_block blocks, add 'key' attribute, store content of the block in cache
                $pattern = '/({widget_block[\s]+name=\"([a-zA-Z0-9_]+)\"[\s]*)}(.*){\/widget_block}/sU';
                $matches = array();
                preg_match($pattern, $source, $matches);
                while (count($matches) > 0) {
                    $hash = crc32($smarty->source->filepath . $matches[0]);
                    $blockKey = sprintf('%u', $hash);
                    $offset = Tools::strpos($source, $matches[0]);
                    $modifiedSource .= Tools::substr($source, $lastOffset, $offset - $lastOffset);
                    $modifiedSource .= $matches[1];
                    $modifiedSource .= ' pckey="' . $blockKey . '"}';
                    $modifiedSource .= $matches[3];
                    $modifiedSource .= '{/widget_block}';
                    $lastOffset = $offset + Tools::strlen($matches[0]);
                    $moduleInstance->setWidgetBlockTemplate($blockKey, $matches[3]);

                    // Next
                    $matches = array();
                    preg_match($pattern, $source, $matches, 0, $lastOffset);
                }
                $modifiedSource .= Tools::substr($source, $lastOffset, Tools::strlen($source));
                return $modifiedSource;
            }
        }
        return $source;
    }

    public static function getJsDef() {
        if (Tools::version_compare(_PS_VERSION_,'1.6','>')) {
            $context = Context::getContext();
            Media::addJsDef(array(
                'isLogged' => (bool)$context->customer->isLogged(),
                'isGuest' => (bool)$context->customer->isGuest(),
                'comparedProductsIds' => $context->smarty->getTemplateVars('compared_products'),
            ));
            $defs = Media::getJsDef();
            $defs['prestashop_pc'] = $defs['prestashop'];
            if ($context->customer->isLogged() && array_key_exists('customer', $defs['prestashop_pc']) && !array_key_exists('id_customer', $defs['prestashop_pc']['customer'])) {
                // For some modules we need the id of current visitor
                $defs['prestashop_pc']['customer']['id_customer'] = $context->customer->id;
            }
            unset($defs['prestashop']);
            unset($defs['baseDir']);
            unset($defs['baseUrl']);
            // Fix for module Revolition Slider
            unset($defs['SdsJsOnLoadActions']);
            return $defs;
        }
        return array();
    }

    public static function getCurrencyId($context) {
        Tools::setCurrency($context->cookie);
        if (!isset($context->cookie->id_currency)) {
            $id_currency = Configuration::get('PS_CURRENCY_DEFAULT');
        } else {
            $id_currency = $context->cookie->id_currency;
        }
        return (int) $id_currency;
    }

    public static function getCountry($context) {
        // static variable avoid computing the country multiple times
        static $current_country = null;
        if ($current_country == null) {
            $current_country = false;
            if (Configuration::get('PS_GEOLOCATION_ENABLED')) {
                // Detect country now to get it right
                $current_country = self::getCountryByGeolocation($context);
            } elseif (Configuration::get('PS_DETECT_COUNTRY')) {
                $has_currency = isset($context->cookie->id_currency) && (int) $context->cookie->id_currency;
                $has_country = isset($context->cookie->iso_code_country) && $context->cookie->iso_code_country;
                $has_address_type = false;

                if ((int) $context->cookie->id_cart && ($cart = new Cart($context->cookie->id_cart)) && Validate::isLoadedObject($cart)) {
                    $has_address_type = isset($cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}) && $cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
                }

                if ((!$has_currency || $has_country) && !$has_address_type) {
                    $id_country = $has_country && Validate::isLanguageIsoCode($context->cookie->iso_code_country) ?
                        (int) Country::getByIso(Tools::strtoupper($context->cookie->iso_code_country)) : (int) Tools::getCountry();

                    try {
                        $country = new Country($id_country, (int)$context->cookie->id_lang);
                        if (validate::isLoadedObject($country)) {
                            $current_country = $country;
                        }
                    }
                    catch (PrestaShopException $e) {
                        // Ignore
                    }
                }
            } elseif ($context->country) {
                $current_country = $context->country;
            }

            // Address of customer, if any, has higher priority
            $current_tax_address = false;
            if ((int)$context->cookie->id_cart) {
                $cart = new Cart($context->cookie->id_cart);
                $id_address = $cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
                /* If address is not set then FrontController::init will set the it with the first address of the customer */
                if ($cart->id_customer && (!isset($id_address) || $id_address == 0)) {
                    $id_address = (int)Address::getFirstCustomerAddressId($cart->id_customer);
                }
                if ($id_address) {
                    $current_tax_address = Address::initialize($id_address);
                }
            } else {
                if ($context->cookie->id_customer) {
                    /* There is no cart but a customer is logged in */
                    $id_address = (int)(Address::getFirstCustomerAddressId($context->cookie->id_customer));
                    if ($id_address) {
                        /* Take his first address */
                        $current_tax_address = Address::initialize($id_address);
                    }
                }
            }
            if ($current_tax_address && $current_tax_address->id_country) {
                $current_country = new Country($current_tax_address->id_country);
            }

            // No country found? Then return default country of the shop.
            if (!$current_country) {
                $current_country = new Country((int)Configuration::get('PS_COUNTRY_DEFAULT'));
            }
        }
        return $current_country;
    }

    private static function getCountryByGeolocation($context)
    {
        $country = null;
        $controller_instance = self::getControllerInstance();
        if ($controller_instance !== false && method_exists($controller_instance, 'geolocationManagementPublic')) {
            if (($newDefault = $controller_instance->geolocationManagementPublic($context->country)) && Validate::isLoadedObject($newDefault)) {
                $country = $newDefault;
            }
        }
        return $country;
    }

    private static function isDependsOnDevice() {
        static $depends_on_devices = null;
        if ($depends_on_devices == null) {
            $val = Configuration::get('pagecache_depend_on_device_auto');
            if ($val) {
                $depends_on_devices = true;
            }
            else {
                $depends_on_devices = false;
            }
        }
        return $depends_on_devices;
    }

    private static function getControllerName() {
        static $controller = false;
        if (!$controller) {
            $request = null;
            if (array_key_exists('request', $GLOBALS) && $GLOBALS['request'] instanceof Symfony\Component\HttpFoundation\Request) {
                $request = $GLOBALS['request'];
            }
            $controller = Dispatcher::getInstance($request)->getController();
        }
        return $controller;
    }

    private static function getControllerInstance() {
        // static variable avoid computing the controller multiple times
        static $controller = null;
        if ($controller == null) {
            $controller = false;
            // Load controllers classes
            $controllers = Dispatcher::getControllers(array(_PS_FRONT_CONTROLLER_DIR_, _PS_OVERRIDE_DIR_.'controllers/front/'));
            $controllers['index'] = 'IndexController';
            // Get controller name
            $controller_name = self::getControllerName();
            if (isset($controllers[Tools::strtolower($controller_name)])) {
                // Create controller instance
                $controller_class = $controllers[Tools::strtolower($controller_name)];
                $context = Context::getContext();
                if ($context->controller) {
                    $controller = $context->controller;
                } else {
                    if (!isset($context->link)) {
                        /* Link should be initialized in the context but sometimes it is not */
                        $https_link = (Tools::usingSecureMode() && Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
                        $context->link = new Link($https_link, $https_link);
                    }
                    $controller = Controller::getController($controller_class);
                }
            }
        }
        return $controller;
    }

    public static function getCurrentURL() {
        $https = self::getServerValue('HTTPS');
        if (!empty($https) && $https !== 'off' || self::getServerValue('SERVER_PORT') == 443) {
            $pageURL = 'https://' . $_SERVER['HTTP_HOST'] . urldecode($_SERVER['REQUEST_URI']);
        }
        else {
            $pageURL = 'http://' . $_SERVER['HTTP_HOST'] . urldecode($_SERVER['REQUEST_URI']);
        }
        return $pageURL;
    }

    public static function filterAndSortParams($query_string, $ignored_params) {
        $new_query_string = '';
        $keyvalues = explode('&', $query_string);
        sort($keyvalues);
        foreach ($keyvalues as $keyvalue) {
            if ($keyvalue !== '') {
                $key = '';
                $value = '';
                $current_key_value = explode('=', $keyvalue);
                if (count($current_key_value) > 0) {
                    $key = Tools::strtolower($current_key_value[0]);
                }
                if (count($current_key_value) > 1) {
                    $value = $current_key_value[1];
                }
                if (!in_array($key, $ignored_params)) {
                    $new_query_string .= '&' . $key . '=' . $value;
                }
            }
        }
        if ($new_query_string !== '') {
            $new_query_string = Tools::substr($new_query_string, 1);
        }
        return $new_query_string;
    }

    public static function cacheThis($html) {

        if (self::isNotCode200()) {
            return;
        }

        // Some old theme are calling smartyOutputContent multiple times
        static $cumulHtml = false;
        if ($cumulHtml === false || Tools::version_compare(_PS_VERSION_,'1.7','>=')) {
            $cumulHtml = $html;
        }
        else {
            $cumulHtml .= $html;
            $html = $cumulHtml;
        }

        // Save the html into the cache
        $controller = self::getControllerName();
        $cache_ttl = 60 * ((int)Configuration::get('pagecache_'.$controller.'_timeout'));
        $jprestaCacheKey = self::getCacheKeyInfos();
        self::getCache()->set($jprestaCacheKey->toString(), $html, $cache_ttl);

        // Parse this file to find all backlinks
        $backlinks = array();
        $shop_url = new ShopUrl(Shop::getContextShopID());
        $base = $shop_url->getURL();
        $links = JprestaUtils::parseLinks($html, $base, self::$managed_controllers, '*PCIGN*', '**PCIGN**', JprestaUtils::decodeConfiguration(Configuration::get('pagecache_ignore_before_pattern')));
        foreach ($links as $link) {
            $linkCacheKey = self::getCacheKeyForBacklink($link);
            $backlinks[$linkCacheKey] = $linkCacheKey;
        }

        // Find all called modules
        $module_ids = array();
        foreach (Hook::$executed_hooks as $hook_name) {
            if (strcmp($hook_name, 'displayHeader') != 0) {
                $module_list = Hook::getHookModuleExecList($hook_name);
                if (JprestaUtils::isIterable($module_list)) {
                    foreach ($module_list as $array) {
                        $module_ids[$array['id_module']] = $array['id_module'];
                    }
                }
            }
        }

        // Insert in database
        $controller = self::getControllerName();
        $id_object = Tools::getValue('id_' . $controller, null);
        PageCacheDAO::insert(
            $jprestaCacheKey,
            $controller,
            Shop::getContextShopID(),
            $id_object,
            $module_ids,
            $backlinks,
            Configuration::get('pagecache_logs'),
            !self::isCacheWarmer());

        // Reduce the cache continuously (remove 2 expired row when it adds one new row)
        PageCacheDAO::deleteCachedPages(PageCacheDAO::getCachedPages(24, 2, null), true);
    }

    public static function preDisplayStats() {
        if (JprestaUtils::isAjax()) {
            // Skip useless work
            return array();
        }

        $infos = array();
        if (self::isDisplayStats()) {
            $context = Context::getContext();
            $currency = new Currency(self::getCurrencyId($context));
            $controller = self::getControllerName();
            if (in_array($controller, self::$managed_controllers)) {
                $country = self::getCountry($context);
                $infos['cacheable'] = self::canBeCached() ? 'true' : 'false';
                $infos['cacheable_reason'] = self::$status_reason;
                $timeoutValue = (int) Configuration::get('pagecache_'.$controller.'_timeout');
                if ($timeoutValue === 0) {
                    $timeoutValue = 'Disabled';
                }
                elseif ($timeoutValue === -1) {
                    $timeoutValue = 'Never';
                }
                else {
                    $timeoutValue = ($timeoutValue / 1440) . ' day(s)';
                }
                $expiresValue = (int) Configuration::get('pagecache_'.$controller.'_expires');
                if ($expiresValue === 0) {
                    $expiresValue = 'Disabled';
                }
                else {
                    $expiresValue = $expiresValue . ' minute(s)';
                }
                $infos['loc_tax'] = self::getCountryStateZipcodeForTaxes($context);
                $infos['timeout_server'] = $timeoutValue;
                $infos['timeout_browser'] = $expiresValue;
                $infos['controller'] = $controller;
                $infos['currency'] = $currency->name;
                if ($country) {
                    $infos['country'] = $country->getFieldByLang('name');
                } else {
                    $infos['country'] = '-';
                }
                $infos['cache_key'] = json_encode(self::getCacheKeyInfos()->compute()->infos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            }
        }
        return $infos;
    }

    public static function displayStats($from_cache, $infos) {
        if (self::isDisplayStats()) {
            $controller = self::getControllerName();
            if (in_array($controller, self::$managed_controllers)) {
                // Prepare datas
                $startTime = self::$page_cache_start_time;
                $infos['speed'] = number_format((microtime(true) - $startTime)*1000, 0, ',', ' ').' ms';
                $context = Context::getContext();
                $infos['groups'] = '';
                $groupsIds = self::getGroupsIds($context);
                foreach ($groupsIds as $arrayKey => $groupId) {
                    if (((int)$groupId) > 0) {
                        $group = new Group($groupId);
                        $infos['groups'] = $infos['groups'] . $group->name[$context->language->id] . ($arrayKey === 0 ? '*' : '') . ', ';
                    }
                }
                $infos['from_cache'] = $from_cache;
                $stats = PageCacheDAO::getStats(self::getCacheKeyInfos());
                if ($stats['hit'] != -1) {
                    $infos['hit'] = $stats['hit'];
                    $infos['missed'] = $stats['missed'];
                    $infos['perfs'] = ($stats['hit']+$stats['missed'] !== 0) ? number_format((100*$stats['hit']/($stats['hit']+$stats['missed'])), 1).'%' : '-';
                } else {
                    $infos['hit'] = '-';
                    $infos['missed'] = '-';
                    $infos['perfs'] = '-';
                }
                $infos['pagehash'] = self::getCacheKeyInfos()->toString();

                $infos['url_on_off'] = http_build_url(self::getCleanURL(), array("query" => 'dbgpagecache='.((int)Tools::getValue('dbgpagecache', 0) == 0 ? 1 : 0)), HTTP_URL_JOIN_QUERY);
                $infos['url_del'] = http_build_url(self::getCleanURL(), array("query" => 'dbgpagecache='.Tools::getValue('dbgpagecache', 0).'&delpagecache=1'), HTTP_URL_JOIN_QUERY);
                $infos['url_reload'] = http_build_url(self::getCleanURL(), array("query" => 'dbgpagecache='.Tools::getValue('dbgpagecache', 1)), HTTP_URL_JOIN_QUERY);
                $infos['url_close'] = self::getCleanURL();
                $infos['dbgpagecache'] = (int)Tools::getValue('dbgpagecache', 0);
                $infos['base_dir'] = _PS_BASE_URL_.__PS_BASE_URI__;

                // Display the box
                $context->smarty->assign($infos);
                $context->smarty->display(_PS_MODULE_DIR_.basename(__FILE__, '.php').'/views/templates/hook/pagecache-infos.tpl');
            }
        }
    }

    public static function getCleanURL($url = null)
    {
        if ($url == null) {
            $url = self::getCurrentURL();
        }
        $new_query = '';
        $query = parse_url($url, PHP_URL_QUERY);
        if ($query != null) {
            $query = html_entity_decode($query);
            $keyvals = explode('&', $query);
            foreach($keyvals as $keyval) {
                $x = explode('=', $keyval);
                if (strcmp($x[0], 'dbgpagecache') != 0 && strcmp($x[0], 'delpagecache') != 0) {
                    $new_query .= '&'.$x[0].'='.(count($x)>1 ? $x[1] : '');
                }
            }
        }
        $un = new PageCacheURLNormalizer();
        $un->setUrl (http_build_url($url, array("query" => $new_query), HTTP_URL_REPLACE));
        return $un->normalize();
    }

    /**
     * @return bool true if the cache was correctly cleared
     */
    public static function clearCache() {
        $startTime = microtime(true);
        $clearOK = true;

        // Delete cache of current shop(s)
        if (Shop::isFeatureActive()) {
            foreach (Shop::getContextListShopID() as $id_shop) {
                $clearOK = $clearOK && self::getCache($id_shop)->flush(self::FLUSH_MAX_SECONDS);
            }
        } else {
            $clearOK = $clearOK && self::getCache()->flush(self::FLUSH_MAX_SECONDS);
        }
        PageCacheDAO::clearAllCache();

        if (Configuration::get('pagecache_logs') > 0) {
            $msg = '';
            $stacks = debug_backtrace();
            for ($i = 0; $i < count($stacks); $i++) {
                if (array_key_exists('file', $stacks[$i])) {
                    $msg .= $stacks[$i]['function'] . '(' . basename($stacks[$i]['file']) . ':' . $stacks[$i]['line'] . ')';
                }
                else {
                    $msg .= $stacks[$i]['function'] . '(?)';
                }
                if ($i + 1 < count($stacks)) {
                    $msg .= ' - ';
                }
            }
            JprestaUtils::addLog("PageCache | clearCache() | $msg = " . number_format(microtime(true) - $startTime, 3) . " second(s)", 1, null, null, null, true);
        }

        // Update database stats
        PageCacheDAO::analyzeTables();

        return $clearOK;
    }

    public function clearCacheAndStats() {
        $clearOK = true;

        // Delete cache and stats of current shop(s)
        if (Shop::isFeatureActive()) {
            foreach (Shop::getContextListShopID() as $id_shop) {
                $clearOK = $clearOK && self::getCache($id_shop)->flush(self::FLUSH_MAX_SECONDS);
            }
            PageCacheDAO::resetCache(Shop::getContextListShopID());
        } else {
            $clearOK = $clearOK && self::getCache()->flush(self::FLUSH_MAX_SECONDS);
            PageCacheDAO::resetCache();
        }

        // Update database stats
        PageCacheDAO::analyzeTables();

        return $clearOK;
    }

    private function _clearCacheModules($event, $action_origin='') {
        $mods = explode(' ', Configuration::get($event.'_mods'));
        foreach ($mods as $mod) {
            $module_name = trim($mod);
            if (Tools::strlen($mod) > 0) {
                PageCacheDAO::clearCacheOfModule($module_name, $action_origin, Configuration::get('pagecache_logs'));
            }
        }
    }

    public function hookActionAttributeDelete($params) {
        $this->hookActionAttributeSave($params);
    }

    public function hookActionAttributeSave($params) {
        if (isset($params['id_attribute'])) {
            // An attribute has been modified, it can be its label, its URL, etc. so all products using it must
            // be refreshed (only the product page)

            $productsIds = Db::getInstance()->executeS('
                SELECT DISTINCT pa.id_product
                FROM '._DB_PREFIX_.'product_attribute pa
                LEFT JOIN '._DB_PREFIX_.'product_attribute_combination pac ON (pac.id_product_attribute = pa.id_product_attribute)
                WHERE pac.id_attribute = '.(int)$params['id_attribute']
            );
            foreach ($productsIds as $productId) {
                $this->onProductUpdate($productId['id_product'], 'modification/deletion of Attribute#' . $params['id_attribute']);
            }
        }
    }

    public function hookActionAttributeGroupDelete($params) {
        $this->hookActionAttributeGroupSave($params);
    }

    public function hookActionAttributeGroupSave($params) {
        if (isset($params['id_attribute_group'])) {
            // An attribute group has been modified, it can be its label, its URL, etc. so all products using it must
            // be refreshed (only the product page)

            $productsIds = Db::getInstance()->executeS('
                SELECT DISTINCT pa.id_product
                FROM '._DB_PREFIX_.'product_attribute pa
                LEFT JOIN '._DB_PREFIX_.'product_attribute_combination pac ON (pac.id_product_attribute = pa.id_product_attribute)
                LEFT JOIN '._DB_PREFIX_.'attribute a ON (a.id_attribute = pac.id_attribute)
                WHERE a.id_attribute_group = '.(int)$params['id_attribute_group']
            );
            foreach ($productsIds as $productId) {
                $this->onProductUpdate($productId['id_product'], 'modification/deletion of AttributeGroup#' . $params['id_attribute_group']);
            }
        }
    }

    public function hookActionFeatureDelete($params) {
        $this->hookActionFeatureSave($params);
    }

    public function hookActionFeatureSave($params) {
        if (isset($params['id_feature'])) {
            // An feature has been modified, it can be its label, etc. so all products using it must
            // be refreshed (only the product page)

            $id_feature = $params['id_feature'];
            $productsIds = Db::getInstance()->executeS('
                SELECT DISTINCT p.id_product
                FROM '._DB_PREFIX_.'product p
                LEFT JOIN '._DB_PREFIX_.'feature_product f ON (f.id_product = p.id_product)
                WHERE f.id_feature = '.(int)$id_feature
            );
            foreach ($productsIds as $productId) {
                $this->onProductUpdate($productId['id_product'], 'modification/deletion of Feature#' . $id_feature);
            }
        }
    }

    public function hookActionFeatureValueDelete($params) {
        $this->hookActionFeatureValueSave($params);
    }

    public function hookActionFeatureValueSave($params) {
        if (isset($params['id_feature_value'])) {
            // An feature value has been modified, it can be its label, etc. so all products using it must
            // be refreshed (only the product page)

            $id_feature_value = $params['id_feature_value'];
            $productsIds = Db::getInstance()->executeS('
                SELECT DISTINCT p.id_product
                FROM '._DB_PREFIX_.'product p
                LEFT JOIN '._DB_PREFIX_.'feature_product fp ON (fp.id_product = p.id_product)
                WHERE fp.id_feature = '.(int)$id_feature_value
            );
            foreach ($productsIds as $productId) {
                $this->onProductUpdate($productId['id_product'], 'modification/deletion of FeatureValue#' . $id_feature_value);
            }
        }
    }

    public function hookActionObjectCmsAddAfter($params) {
        if (isset($params['object'])) {
            PageCacheDAO::clearCacheOfObject('cms', $params['object']->id, false, 'creation of CMS page #' . $params['object']->id, Configuration::get('pagecache_logs'));
        }
        $this->_clearCacheModules('pagecache_cms_a', 'creation of CMS page #' . $params['object']->id);
    }

    public function hookActionObjectCmsUpdateAfter($params) {
        if (isset($params['object'])) {
            PageCacheDAO::clearCacheOfObject('cms', $params['object']->id, Configuration::get('pagecache_cms_u_bl'), 'modification of CMS page #' . $params['object']->id, Configuration::get('pagecache_logs'));
        }
        $this->_clearCacheModules('pagecache_cms_u', 'modification of CMS page #' . $params['object']->id);
    }

    public function hookActionObjectCmsDeleteBefore($params) {
        if (isset($params['object'])) {
            PageCacheDAO::clearCacheOfObject('cms', $params['object']->id, Configuration::get('pagecache_cms_d_bl'), 'deletion of CMS page #' . $params['object']->id, Configuration::get('pagecache_logs'));
        }
        $this->_clearCacheModules('pagecache_cms_d', 'deletion of CMS page #' . $params['object']->id);
    }

    public function hookActionObjectManufacturerAddAfter($params) {
        if (isset($params['object'])) {
            PageCacheDAO::clearCacheOfObject('manufacturer', $params['object']->id, false, 'hookActionObjectManufacturerAddAfter', Configuration::get('pagecache_logs'));
        }
        $this->_clearCacheModules('pagecache_manufacturer_a', 'hookActionObjectManufacturerAddAfter');
    }

    public function hookActionObjectManufacturerUpdateAfter($params) {
        if (isset($params['object'])) {
            PageCacheDAO::clearCacheOfObject('manufacturer', $params['object']->id, Configuration::get('pagecache_manufacturer_u_bl'), 'hookActionObjectManufacturerUpdateAfter', Configuration::get('pagecache_logs'));
        }
        $this->_clearCacheModules('pagecache_manufacturer_u', 'hookActionObjectManufacturerUpdateAfter');
    }

    public function hookActionObjectManufacturerDeleteBefore($params) {
        if (isset($params['object'])) {
            PageCacheDAO::clearCacheOfObject('manufacturer', $params['object']->id, Configuration::get('pagecache_manufacturer_d_bl'), 'hookActionObjectManufacturerDeleteBefore', Configuration::get('pagecache_logs'));
        }
        $this->_clearCacheModules('pagecache_manufacturer_d', 'hookActionObjectManufacturerDeleteBefore');
    }

    private static $lastStockAvailableCanOrder = null;
    private static $lastStockAvailableQuantity = null;

    /**
     *  Called when a warehouse is associated to a product with advanced stock management enabled
     */
    public function hookActionObjectWarehouseProductLocationAddBefore($params) {
        if (isset($params['object'])) {
            $newWarehouseProductLocation = $params['object'];
            $product = new Product($newWarehouseProductLocation->id_product);
            self::$lastStockAvailableCanOrder = $product->checkQty(1);
            self::$lastStockAvailableQuantity = StockManagerFactory::getManager()->getProductRealQuantities($newWarehouseProductLocation->id_product, 0, null, true);
        }
    }

    /**
     *  Called when a new warehouse is associated to a product with advanced stock management enabled
     */
    public function hookActionObjectWarehouseProductLocationAddAfter($params) {
        if (isset($params['object'])) {
            $newWarehouseProductLocation = $params['object'];
            $product = new Product($newWarehouseProductLocation->id_product);
            $newQuantity = StockManagerFactory::getManager()->getProductRealQuantities($newWarehouseProductLocation->id_product, 0, null, true);
            $this->handleStock($product, (int) $newWarehouseProductLocation->id_product_attribute, $newQuantity);
        }
    }

    /**
     *  Called when a warehouse is disassociated to a product with advanced stock management enabled
     */
    public function hookActionObjectWarehouseProductLocationDeleteBefore($params) {
        if (isset($params['object'])) {
            $deletedWarehouseProductLocation = $params['object'];
            $product = new Product($deletedWarehouseProductLocation->id_product);
            self::$lastStockAvailableCanOrder = $product->checkQty(1);
            self::$lastStockAvailableQuantity = StockManagerFactory::getManager()->getProductRealQuantities($deletedWarehouseProductLocation->id_product, 0, null, true);
        }
    }

    /**
     *  Called when a new warehouse is disassociated to a product with advanced stock management enabled
     */
    public function hookActionObjectWarehouseProductLocationDeleteAfter($params) {
        if (isset($params['object'])) {
            $deletedWarehouseProductLocation = $params['object'];
            $product = new Product($deletedWarehouseProductLocation->id_product);
            $newQuantity = StockManagerFactory::getManager()->getProductRealQuantities($deletedWarehouseProductLocation->id_product, 0, null, true);
            $this->handleStock($product, (int) $deletedWarehouseProductLocation->id_product_attribute, $newQuantity);
        }
    }

    /**
     *  Called when a new warehouse is created with advanced stock management enabled
     */
    public function hookActionObjectStockAddBefore($params) {
        if (isset($params['object'])) {
            $newStock = $params['object'];
            $product = new Product($newStock->id_product);
            self::$lastStockAvailableCanOrder = $product->checkQty(1);
            self::$lastStockAvailableQuantity = StockManagerFactory::getManager()->getProductRealQuantities($newStock->id_product, 0, null, true);
        }
    }

    /**
     *  Called when a new warehouse is created with advanced stock management enabled
     */
    public function hookActionObjectStockAddAfter($params) {
        if (isset($params['object'])) {
            $newStock = $params['object'];
            $product = new Product($newStock->id_product);
            $newQuantity = StockManagerFactory::getManager()->getProductRealQuantities($newStock->id_product, 0, null, true);
            $this->handleStock($product, (int) $newStock->id_product_attribute, $newQuantity);
        }
    }

    /**
     *  Called when stock is modified with advanced stock management enabled
     */
    public function hookActionObjectStockUpdateBefore($params) {
        if (isset($params['object'])) {
            $newStock = $params['object'];
            $product = new Product($newStock->id_product);
            self::$lastStockAvailableCanOrder = $product->checkQty(1);
            self::$lastStockAvailableQuantity = StockManagerFactory::getManager()->getProductRealQuantities($newStock->id_product, 0, null, true);
        }
    }

    /**
     *  Called when stock is modified with advanced stock management enabled
     */
    public function hookActionObjectStockUpdateAfter($params) {
        if (isset($params['object'])) {
            $newStock = $params['object'];
            $product = new Product($newStock->id_product);
            $newQuantity = StockManagerFactory::getManager()->getProductRealQuantities($newStock->id_product, 0, null, true);
            $this->handleStock($product, (int) $newStock->id_product_attribute, $newQuantity);
        }
    }

    /**
     *  Called when stock is modified with standard stock management
     */
    public function hookActionObjectStockAvailableUpdateBefore($params) {
        if (isset($params['object'])) {
            $newStock = $params['object'];
            $product = new Product($newStock->id_product);
            self::$lastStockAvailableCanOrder = $product->checkQty(1);
            $currentStockId = StockAvailable::getStockAvailableIdByProductId($newStock->id_product, $newStock->id_product_attribute, ((int) $newStock->id_shop) === 0 ? null : (int) $newStock->id_shop);
            if ($currentStockId) {
                $currentStock = new StockAvailable($currentStockId);
                self::$lastStockAvailableQuantity = $currentStock->quantity;
            }
        }
    }

    /**
     *  Called when stock is modified with standard stock management
     */
    public function hookActionObjectStockAvailableUpdateAfter($params) {
        if (isset($params['object'])) {
            $newStock = $params['object'];
            $product = new Product($newStock->id_product);
            // Clear the cache to get the actual current value
            Cache::clean('StockAvailable::getQuantityAvailableByProduct_' . (int) $product->id . '*');
            $this->handleStock($product, (int) $newStock->id_product_attribute, $newStock->quantity);
        }
    }

    private function handleStock($product, $id_product_attribute, $newQuantity)
    {
        if (self::$lastStockAvailableQuantity !== null && self::$lastStockAvailableCanOrder !== null) {
            $deltaQuantity = $newQuantity - self::$lastStockAvailableQuantity;
            $canOrder = $product->checkQty(1);
            if ($canOrder !== self::$lastStockAvailableCanOrder) {
                if ($canOrder) {
                    // Refresh like a product update
                    $this->onProductUpdate($product, 'Product#' . $product->id . ' is now available for order', false);
                } else {
                    // Refresh like a product deletion
                    $this->onProductDelete($product, 'Product#' . $product->id . ' is no more available for order');
                }
            } else {
                if ($deltaQuantity !== 0) {
                    $lastItemsQuantities = max(1, (int)Configuration::get('PS_LAST_QTIES'));
                    if (Tools::version_compare(_PS_VERSION_, '1.7.3.0', '>=')) {
                        $lastItemsQuantities = max(1, (int)$product->low_stock_threshold, $lastItemsQuantities);
                        if (((int)$id_product_attribute) > 0) {
                            $lastItemsQuantitiesAttribute = (int) JprestaUtils::dbGetValue(
                                'SELECT pa.low_stock_threshold' .
                                ' FROM `' . _DB_PREFIX_ . 'product_attribute` pa' .
                                ' WHERE pa.`id_product` = ' . (int)$product->id . ' AND  pa.`id_product_attribute` = ' . (int)$id_product_attribute
                            );
                            $lastItemsQuantities = max(1, $lastItemsQuantities, $lastItemsQuantitiesAttribute);
                        }
                    }
                    if (self::$lastStockAvailableQuantity > $lastItemsQuantities && $newQuantity > $lastItemsQuantities) {
                        // It was and it is still over the limit of alert so we only refresh the product page
                        // every X sales (by default X = 1)
                        $everyX = max(1, (int)Configuration::get('pagecache_product_refreshEveryX', null, null, null, 1));
                        if ((($newQuantity - $lastItemsQuantities) % $everyX) === 0) {
                            $this->onProductUpdate($product, 'stock update (every X=' . $everyX . ')');
                        }
                    } else {
                        // It is or it was under the limit of alert so we refresh the product page
                        $this->onProductUpdate($product, 'stock update (alert=' . $lastItemsQuantities . ')');
                    }
                }
            }
        }
        self::$lastStockAvailableCanOrder = null;
        self::$lastStockAvailableQuantity = null;
    }

    public function hookActionObjectAddressAddAfter($params) {
        if (isset($params['object']) && !empty($params['object']->id_supplier)) {
            $this->_clearCacheModules('pagecache_supplier_a', 'hookActionObjectAddressAddAfter');
        }
    }

    public function hookActionObjectAddressUpdateAfter($params) {
        if (isset($params['object']) && !empty($params['object']->id_supplier)) {
            PageCacheDAO::clearCacheOfObject('supplier', $params['object']->id_supplier, Configuration::get('pagecache_supplier_u_bl'), 'hookActionObjectAddressUpdateAfter', Configuration::get('pagecache_logs'));
            $this->_clearCacheModules('pagecache_supplier_u', 'hookActionObjectAddressUpdateAfter');
        }
    }

    public function hookActionObjectAddressDeleteBefore($params) {
        if (isset($params['object']) && !empty($params['object']->id_supplier)) {
            PageCacheDAO::clearCacheOfObject('supplier', $params['object']->id_supplier, Configuration::get('pagecache_supplier_d_bl'), 'hookActionObjectAddressDeleteBefore', Configuration::get('pagecache_logs'));
            $this->_clearCacheModules('pagecache_supplier_d', 'hookActionObjectAddressDeleteBefore');
        }
    }

    public function hookActionCategoryAdd($params) {
        if (isset($params['category'])) {
            PageCacheDAO::clearCacheOfObject('category', $params['category']->id, false, 'creation of Category#' . $params['category']->id, Configuration::get('pagecache_logs'));
            $this->_checkRootCategory($params['category']->id, 'a', 'creation of Category#' . $params['category']->id);
        }
        $this->_clearCacheModules('pagecache_category_a', 'creation of Category#' . $params['category']->id);
    }

    public function hookActionCategoryUpdate($params) {
        if (isset($params['category'])) {
            PageCacheDAO::clearCacheOfObject('category', $params['category']->id, Configuration::get('pagecache_category_u_bl'), 'modification of Category#' . $params['category']->id, Configuration::get('pagecache_logs'));
            $this->_checkRootCategory($params['category']->id, 'u', 'modification of Category#' . $params['category']->id);
        }
        $this->_clearCacheModules('pagecache_category_u', 'modification of Category#' . $params['category']->id);
    }

    public function hookActionCategoryDelete($params) {
        if (isset($params['category'])) {
            PageCacheDAO::clearCacheOfObject('category', $params['category']->id, Configuration::get('pagecache_category_d_bl'), 'deletion of Category#' . $params['category']->id, Configuration::get('pagecache_logs'));
            $this->_checkRootCategory($params['category']->id, 'd', 'deletion of Category#' . $params['category']->id);
        }
        $this->_clearCacheModules('pagecache_category_d', 'deletion of Category#' . $params['category']->id);
    }

    public function onProductAdd($product, $logMessage) {
        // New products pages
        PageCacheDAO::clearCacheOfObject('newproducts', null, false, $logMessage, Configuration::get('pagecache_logs'));

        // Categories of the new product
        $categoriesIds = $product->getCategories();
        foreach ($categoriesIds as $categoryId) {
            PageCacheDAO::clearCacheOfObject('category', $categoryId, false, $logMessage, Configuration::get('pagecache_logs'));
            $this->_checkRootCategory($categoryId, 'a', $logMessage);
        }

        // Supplier pages
        PageCacheDAO::clearCacheOfObject('supplier', $product->id_supplier, false, $logMessage, Configuration::get('pagecache_logs'));

        // Manufacturer pages
        PageCacheDAO::clearCacheOfObject('manufacturer', $product->id_manufacturer, false, $logMessage, Configuration::get('pagecache_logs'));

        // Modules attached to this hook
        $this->_clearCacheModules('pagecache_product_a', $logMessage);
    }

    public function onProductUpdate($product, $logMessage, $onlyProductPage = true) {

        if (is_numeric($product)) {
            $productId = $product;
        }
        else {
            $productId = $product->id;
        }

        // Product page
        PageCacheDAO::clearCacheOfObject('product', $productId, !$onlyProductPage, $logMessage, Configuration::get('pagecache_logs'));

        if (!$onlyProductPage) {
            if (is_numeric($product)) {
                $product = new Product($productId);
            }

            // New products pages
            PageCacheDAO::clearCacheOfObject('newproducts', null, false, $logMessage, Configuration::get('pagecache_logs'));

            // Categories of the new product
            $categoriesIds = $product->getCategories();
            foreach ($categoriesIds as $categoryId) {
                PageCacheDAO::clearCacheOfObject('category', $categoryId, false, $logMessage, Configuration::get('pagecache_logs'));
                $this->_checkRootCategory($categoryId, 'a', $logMessage);
            }

            // Supplier pages
            PageCacheDAO::clearCacheOfObject('supplier', $product->id_supplier, false, $logMessage, Configuration::get('pagecache_logs'));

            // Manufacturer pages
            PageCacheDAO::clearCacheOfObject('manufacturer', $product->id_manufacturer, false, $logMessage, Configuration::get('pagecache_logs'));

            // Modules attached to this hook
            $this->_clearCacheModules('pagecache_product_u', $logMessage);
        }
    }

    public function onProductDelete($product, $logMessage) {
        // Product page
        PageCacheDAO::clearCacheOfObject('product', $product->id, Configuration::get('pagecache_product_d_bl'), $logMessage, Configuration::get('pagecache_logs'));

        // Categories of the new product
        $categoriesIds = $product->getCategories();
        foreach ($categoriesIds as $categoryId) {
            PageCacheDAO::clearCacheOfObject('category', $categoryId, false, $logMessage, Configuration::get('pagecache_logs'));
            $this->_checkRootCategory($categoryId, 'd', $logMessage);
        }

        // Supplier pages
        PageCacheDAO::clearCacheOfObject('supplier', $product->id_supplier, false, $logMessage, Configuration::get('pagecache_logs'));

        // Manufacturer pages
        PageCacheDAO::clearCacheOfObject('manufacturer', $product->id_manufacturer, false, $logMessage, Configuration::get('pagecache_logs'));

        // Modules attached to this hook
        $this->_clearCacheModules('pagecache_product_d', $logMessage);
    }

    private static $lastUpdatedProduct = null;

    public function hookActionObjectProductUpdateBefore($params) {
        if (isset($params['object'])) {
            $newProduct = $params['object'];
            // Load the current product from the database and keep it for hookActionObjectProductUpdateAfter
            self::$lastUpdatedProduct = new Product($newProduct->id);
        }
    }

    public function hookActionObjectProductUpdateAfter($params) {
        if (isset($params['object'])) {
            $updatedProduct = $params['object'];
            // Compare with database version because of boolean stored as int, null as empty, integer being formatted, etc.
            $productFromDb = new Product($updatedProduct->id);
            $diffs = JprestaUtils::getObjectDifferences(self::$lastUpdatedProduct, $productFromDb);
            if (Tools::getIsset('out_of_stock')) {
                // Check if out_of_stock has been modified
                $currentStockId = (int)StockAvailable::getStockAvailableIdByProductId((int)$updatedProduct->id);
                if ($currentStockId) {
                    // 'out_of_stock' is not stored in ps_product table but in StockAvailable
                    $currentStock = new StockAvailable($currentStockId);
                    if ($currentStock->out_of_stock != Tools::getValue('out_of_stock')) {
                        $diffs['out_of_stock'] = JprestaUtils::toString($currentStock->out_of_stock) . ' <> ' . JprestaUtils::toString(Tools::getValue('out_of_stock'));
                    }
                }
            }
            self::$lastUpdatedProduct = null;
            if (is_array($diffs) && count($diffs) > 0) {
                if (array_key_exists('active', $diffs)) {
                    if ($updatedProduct->active) {
                        // Product is back, act like a new product
                        $this->onProductAdd($updatedProduct, 'activation of Product#' . $updatedProduct->id);
                    }
                    else {
                        // Product is disabled, act like a deletion
                        $this->onProductDelete($updatedProduct, 'deactivation of Product#' . $updatedProduct->id);
                    }
                }
                else {
                    // The product has been modified, it can be the name, description, price, url, etc. so we need to
                    // refresh all pages where the product is listed or displayed
                    $this->onProductUpdate($updatedProduct, 'modification of Product#' . $updatedProduct->id, false);
                }
            }
        }
    }

    public function hookActionProductAdd($params) {
        if (!isset($params['product']) && isset($params['id_product'])) {
            $params['product'] = new Product($params['id_product']);
        }
        if (isset($params['product'])) {
            $product = $params['product'];
            $this->onProductAdd($product, 'creation of new Product#' . $product->id);
        }
    }

    public function hookActionObjectProductDeleteBefore($params) {
        if (isset($params['object'])) {
            $product = $params['object'];
            $this->onProductDelete($params['object'], 'deletion of Product#' . $product->id);
        }
    }

    private static $lastUpdatedProductCombination = null;

    public function hookActionObjectCombinationUpdateBefore($params) {
        if (isset($params['object'])) {
            $newCombination = $params['object'];
            // Load the current product from the database and keep it for hookActionObjectCombinationUpdateAfter
            self::$lastUpdatedProductCombination = new Combination($newCombination->id);
        }
    }

    public function hookActionObjectCombinationUpdateAfter($params) {
        if (isset($params['object'])) {
            $updatedCombination = $params['object'];
            $combinationFromDb = new Combination($updatedCombination->id);
            $diffs = JprestaUtils::getObjectDifferences(self::$lastUpdatedProductCombination, $combinationFromDb);
            self::$lastUpdatedProductCombination = null;
            if (is_array($diffs) && count($diffs) > 0) {
                // A combination has been modified (impact on price, weight, minimal quantity, etc. so we just need
                // to refresh the product page, no pages that list this product.

                // Product page
                PageCacheDAO::clearCacheOfObject('product', $updatedCombination->id_product, false, 'modification of Combination#' . $updatedCombination->id, Configuration::get('pagecache_logs'));
            }
        }
    }

    public function hookActionObjectCombinationDeleteAfter($params) {
        if (isset($params['object'])) {
            $deletedCombination = $params['object'];

            // A combination has been deleted so we just need
            // to refresh the product page, no pages that list this product.

            // Product page
            PageCacheDAO::clearCacheOfObject('product', $deletedCombination->id_product, false, 'deletion of Combination#' . $deletedCombination->id, Configuration::get('pagecache_logs'));
        }
    }

    public function hookActionObjectSpecificPriceAddAfter($params) {
        if (isset($params['object'])) {
            $sp = $params['object'];
            PageCacheDAO::insertSpecificPrice($sp->id, $sp->id_product, $sp->from, $sp->to);
            $this->onProductUpdate($sp->id_product, 'creation of specific price #' . $sp->id);
        }
        self::updateCacheKeyForCountries();
        self::updateCacheKeyForUserGroups();
    }

    public function hookActionObjectSpecificPriceUpdateAfter($params) {
        if (isset($params['object'])) {
            $sp = $params['object'];
            PageCacheDAO::updateSpecificPrice($sp->id, $sp->id_product, $sp->from, $sp->to);
            $this->onProductUpdate($sp->id_product, 'modification of specific price #' . $sp->id);
        }
        self::updateCacheKeyForCountries();
        self::updateCacheKeyForUserGroups();
    }

    public function hookActionObjectSpecificPriceDeleteBefore($params) {
        if (isset($params['object'])) {
            $sp = $params['object'];
            PageCacheDAO::deleteSpecificPrice($sp->id);
            $this->onProductUpdate($sp->id_product, 'deletion of specific price #' . $sp->id);
        }
    }

    public function hookActionObjectImageAddAfter($params) {
        if (isset($params['object'])) {
            $img = $params['object'];
            $this->onProductUpdate($img->id_product, 'new image');
        }
    }

    public function hookActionObjectImageUpdateAfter($params) {
        if (isset($params['object'])) {
            $img = $params['object'];
            $this->onProductUpdate($img->id_product, 'modification of an image');
        }
    }

    public function hookActionObjectImageDeleteBefore($params) {
        if (isset($params['object'])) {
            $img = $params['object'];
            $this->onProductUpdate($img->id_product, 'deletion of an image');
        }
    }

    private function _checkRootCategory($id_category, $suffix, $origin_action='') {
        if ((bool) JprestaUtils::dbGetValue('SELECT `id_shop` FROM `'._DB_PREFIX_.'shop` WHERE `id_category` = '.(int)$id_category)) {
            $this->_clearCacheModules('pagecache_product_home_'.$suffix, $origin_action);
        }
    }

    public function hookActionHtaccessCreate($params) {
        $this->clearCache();
    }

    public function hookObjectShopUrlAddAfter($params) {
        $this->hookActionHtaccessCreate($params);
    }

    public function hookObjectShopUrlUpdateAfter($params) {
        $this->hookActionHtaccessCreate($params);
    }

    public function hookObjectShopUrlDeleteAfter($params) {
        $this->hookActionHtaccessCreate($params);
    }

    public function hookActionAdminPerformanceControllerAfter($params)
    {
        $this->clearCache();
    }

    public function removeOverride($class_name) {
        static $already_done = array();
        if (array_key_exists($class_name, $already_done)) {
            return true;
        }
        $already_done[$class_name] = true;
        if (Tools::version_compare(_PS_VERSION_,'1.6','<')
            && !file_exists(_PS_MODULE_DIR_ . '/' . $this->name . '/override/controllers/front/'.$class_name.'.php')
            && !file_exists(_PS_MODULE_DIR_ . '/' . $this->name . '/override/classes/'.$class_name.'.php')
            && !file_exists(_PS_MODULE_DIR_ . '/' . $this->name . '/override/classes/controller/'.$class_name.'.php')
            && !file_exists(_PS_MODULE_DIR_ . '/' . $this->name . '/override/classes/module/'.$class_name.'.php')
        ) {
            // In PS 1.5 we cannot remove an override that is not defined in /overrides directory
            // So they stay installed but it's better than an error during upgrade
            return true;
        }
        return parent::removeOverride($class_name);
    }

    public function upgradeOverride($class_name) {
        // Avoid calling this method multiple times (or it will fail)
        static $already_done = array();
        if (array_key_exists($class_name, $already_done)) {
            return true;
        }
        $already_done[$class_name] = true;

        if (!file_exists(_PS_MODULE_DIR_ . '/' . $this->name . '/override/controllers/front/'.$class_name.'.php')
            && !file_exists(_PS_MODULE_DIR_ . '/' . $this->name . '/override/classes/'.$class_name.'.php')
            && !file_exists(_PS_MODULE_DIR_ . '/' . $this->name . '/override/classes/controller/'.$class_name.'.php')
            && !file_exists(_PS_MODULE_DIR_ . '/' . $this->name . '/override/classes/module/'.$class_name.'.php')
        ) {
            // The override does not exist anymore, just ignore it. It can happen in old upgrade file.
            return true;
        }

        $reset_ok = true;
        if (Tools::version_compare(_PS_VERSION_,'1.6','>=')
            || (!class_exists($class_name . 'OverrideOriginal') && (!class_exists($class_name . 'OverrideOriginal_remove')))) {
            $reset_ok = $this->removeOverride($class_name) && $this->addOverride($class_name);
        }
        return $reset_ok;
    }

    /** @return bool true if infos block must be displayed on front end */
    private static function isDisplayStats() {
        if (JprestaUtils::isAjax() || strcmp(self::getServerValue('REQUEST_METHOD'), 'GET') != 0) {
            return false;
        }
        return Configuration::get('pagecache_always_infosbox')
            || (Configuration::get('pagecache_debug') && Tools::getIsset('dbgpagecache'));
    }

    public function getContactUrl() {
        $seller = Configuration::get('pagecache_seller');
        if (isset($seller) && strcmp($seller, 'addons') === 0) {
            // Contact URL
            if (strcmp('fr', Language::getIsoById($this->context->language->id)) == 0) {
                return 'https://addons.prestashop.com/fr/ecrire-au-developpeur?id_product=7939';
            }
            else {
                return 'https://addons.prestashop.com/en/write-to-developper?id_product=7939';
            }
        } else {
            // Contact URL
            if (strcmp('fr', Language::getIsoById($this->context->language->id)) == 0) {
                return self::JPRESTA_PROTO . self::JPRESTA_DOMAIN . '.com/fr/contactez-nous';
            }
            else {
                return self::JPRESTA_PROTO . self::JPRESTA_DOMAIN . '.com/en/contact-us';
            }
        }
    }

    /**
     * Used in case script is run with a command line
     * @param unknown $key Variable name
     * @return string Value of variable or empty string
     */
    public static function getServerValue($key) {
        if (array_key_exists($key, $_SERVER)) {
            return $_SERVER[$key];
        }
        return '';
    }

    /**
     * @deprecated Just needed when upgrading to 4.00, do not remove it
     */
    public static function getCacheFile() {
        return false;
    }

    /**
     * @deprecated Just needed when upgrading to 4.25, do not remove it
     */
    public static function getDynamicHookInfos() {
        return false;
    }

    /**
     * @deprecated Just needed when upgrading to 4.25, do not remove it
     */
    public static function getHookCacheDirectives() {
        return array('wrapper' => false, 'content' => true);
    }

    /**
     * @deprecated Just needed when upgrading to 4.25, do not remove it
     */
    public static function getWidgetCacheDirectives() {
        return array('wrapper' => false, 'content' => true);
    }

    /**
     * @deprecated Just needed when upgrading, do not remove it
     */
    public static function isDynamicHooks() {
        return false;
    }
}
?>
