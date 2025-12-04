<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

include_once(dirname(__FILE__) . '/../../pagecache.php');

class AdminPageCacheSpeedAnalysisController extends ModuleAdminController
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

        if (Module::isEnabled("pagecache")) {
            $module = Module::getInstanceByName("pagecache");
            $url = Tools::getValue('url', '');
            $check = Tools::getValue('check', '');

            if (Tools::strlen($url) > 0) {

                $stream_context = stream_context_create(
                    array(
                        'http' => array('timeout' => 60, "ignore_errors" => (Tools::strlen($check) > 0)),
                        'ssl' => array(
                            'verify_peer' => false
                        )
                    )
                );

                $startTime = microtime(true);
                $page = Tools::file_get_contents($url, false, $stream_context, 60);
                $endTime = microtime(true);

                if (Tools::strlen($check) > 0) {
                    if ($page !== false && Tools::strlen($page) > 0) {
                        die ($page);
                    }
                    else {
                        die ($module->l('The server cannot read the URL at all'));
                    }
                }

                if ($page !== false && Tools::strlen($page) > 0) {
                    $result = number_format(($endTime - $startTime) * 1000, 0, '', '');
                }
                else {
                    if (strpos($url, 'http://') === 0) {
                        // Try with HTTPs protocol
                        $url = str_replace('http://', 'https://', $url);
                        $startTime = microtime(true);
                        $page = Tools::file_get_contents($url, false, null, 60);
                        $endTime = microtime(true);
                        if ($page !== false && Tools::strlen($page) > 0) {
                            $result = number_format(($endTime - $startTime) * 1000, 0, '', '');
                        }
                        else {
                            header("HTTP/1.0 404 Not Found");
                            $result = $module->l('The server cannot access to the page; this can occur when there is a redirection or if you set up country restrictions.');
                        }
                    }
                    else {
                        header("HTTP/1.0 404 Not Found");
                        $result = $module->l('The server cannot access to the page; this can occur when there is a redirection or if you set up country restrictions.');
                    }
                }
            } else {
                $result = 'URL is empty';
            }
        } else {
            $result = 'The module is not enabled';
        }
        die($result);
    }
}
