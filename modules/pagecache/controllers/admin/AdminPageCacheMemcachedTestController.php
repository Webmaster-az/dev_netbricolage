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

class AdminPageCacheMemcachedTestController extends ModuleAdminController
{
    public $php_self = null;

    public function init()
    {
        parent::init();
    }

    public function initContent()
    {
        header('Access-Control-Allow-Origin: *');

        parent::initContent();

        $host = Tools::getValue('host', '');
        $port = (int)Tools::getValue('port', '');
        $memcached = new PageCacheCacheMemcached($host, $port);
        $isConnected = $memcached->isConnected($host, $port);
        $result = array(
            'host' => $host,
            'port' => $port,
            'status' => $isConnected ? 1 : 0,
            'comments' => $isConnected ? 'Server version ' . $memcached->getVersion() : $memcached->getResultMessage()
        );
        die(json_encode($result));
    }
}
