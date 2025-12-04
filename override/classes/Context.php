<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 * @author    Jpresta
 * @copyright Jpresta
 * @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */
class Context extends ContextCore
{
    /*
    * module: pagecache
    * date: 2022-07-12 19:28:21
    * version: 7.8.3
    */
    public function getMobileDetect()
    {
        if ($this->mobile_detect === null) {
            if (!Module::isEnabled('pagecache') || !file_exists(_PS_MODULE_DIR_ . 'pagecache/pagecache.php')) {
                return parent::getMobileDetect();
            } else {
                require_once _PS_MODULE_DIR_ . 'pagecache/pagecache.php';
                if ($this->mobile_detect === null) {
                    if (PageCache::isCacheWarmer()) {
                        $this->mobile_detect = new JprestaUtilsMobileDetect();
                    } else {
                        return parent::getMobileDetect();
                    }
                }
            }
        }
        return $this->mobile_detect;
    }
}
