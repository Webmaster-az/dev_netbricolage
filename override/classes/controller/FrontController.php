<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */
class FrontController extends FrontControllerCore
{
    /*
    * module: pagecache
    * date: 2022-07-12 19:28:21
    * version: 7.8.3
    */
    protected function displayAjax()
    {
        if (!Tools::getIsset('page_cache_dynamics_mods')) {
            if (is_callable('parent::displayAjax')) {
                return parent::displayAjax();
            }
            else {
                return;
            }
        }
        $this->initHeader();
        $this->assignGeneralPurposeVariables();
        require_once _PS_MODULE_DIR_ . 'pagecache/pagecache.php';
        $result = PageCache::execDynamicHooks($this);
        if (Tools::version_compare(_PS_VERSION_,'1.6','>')) {
            $this->context->smarty->assign(array(
                'js_def' => PageCache::getJsDef($this),
            ));
            $result['js'] = $this->context->smarty->fetch(_PS_ALL_THEMES_DIR_.'javascript.tpl');
        }
        $this->context->cookie->write();
        header('Content-Type: text/html');
        header('Cache-Control: no-cache');
        header('X-Robots-Tag: noindex');
        die(Tools::jsonEncode($result));
    }
    /*
    * module: pagecache
    * date: 2022-07-12 19:28:21
    * version: 7.8.3
    */
    public function isRestrictedCountry()
    {
        return $this->restrictedCountry;
    }
    /*
    * module: pagecache
    * date: 2022-07-12 19:28:21
    * version: 7.8.3
    */
    public function geolocationManagementPublic($default_country)
    {
        $ret = $this->geolocationManagement($default_country);
        if (!$ret) {
            return $default_country;
        }
        return $ret;
    }

    /*
    * module: faktiva_cleanurls
    * date: 2023-01-16 11:51:49
    * version: 1.2.3
    */
    protected function canonicalRedirection($canonical_url = '')
    {
        $_unfiltered_GET = $_GET;
        $_GET = array_filter($_GET, function ($v) {
            return '_rewrite' === substr($v, -8);
        });
        parent::canonicalRedirection($canonical_url);
        $_GET = $_unfiltered_GET;
    }
}