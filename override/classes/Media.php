<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */
class Media extends MediaCore
{
    /*
    * module: pagecache
    * date: 2022-07-12 19:28:21
    * version: 7.8.3
    */
    public static function clearCache()
    {
        if (Module::isEnabled('pagecache') && file_exists(_PS_MODULE_DIR_ . 'pagecache/pagecache.php')) {
            foreach (array(_PS_THEME_DIR_ . 'cache') as $dir) {
                if (file_exists($dir) && count(array_diff(scandir($dir), array('..', '.', 'index.php'))) > 0) {
                    PageCache::clearCache();
                    break;
                }
            }
        }
        if (is_callable('parent::clearCache')) {
            parent::clearCache();
        }
    }
}
