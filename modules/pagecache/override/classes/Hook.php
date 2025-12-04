<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

class Hook extends HookCore
{

    public static function coreCallHook($module, $method, $params)
    {
        if (!Module::isEnabled('pagecache') || !file_exists(_PS_MODULE_DIR_ . 'pagecache/pagecache.php')) {
            return parent::coreCallHook($module, $method, $params);
        }
        else {
            require_once _PS_MODULE_DIR_ . 'pagecache/pagecache.php';
            return PageCache::execHook(PageCache::HOOK_TYPE_MODULE, $module, $method, $params);
        }
    }

    public static function coreRenderWidget($module, $hook_name, $params)
    {
        if (!Module::isEnabled('pagecache') || !file_exists(_PS_MODULE_DIR_ . 'pagecache/pagecache.php')) {
            return parent::coreRenderWidget($module, $hook_name, $params);
        }
        else {
            require_once _PS_MODULE_DIR_ . 'pagecache/pagecache.php';
            return PageCache::execHook(PageCache::HOOK_TYPE_WIDGET, $module, $hook_name, $params);
        }
    }
}
