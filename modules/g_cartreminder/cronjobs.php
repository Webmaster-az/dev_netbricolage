<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */

include_once('../../config/config.inc.php');
include_once('../../init.php');
require_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/cronjob.php');
require_once(_PS_MODULE_DIR_ . 'g_cartreminder/g_cartreminder.php');

if (Tools::getValue('gettoken') != sha1(_COOKIE_KEY_ . 'g_cartreminder')) {
    $adminmodule = new G_cartreminder();
    echo $adminmodule->l("invalid url error.");
    die;
} else {
    $autorun = new cronjob();
}

