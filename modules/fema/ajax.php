<?php
/**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    FEMA S.A.S. <support.ecommerce@dpd.fr>
 * @copyright 2019 FEMA S.A.S.
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

require_once(realpath(dirname(__FILE__).'/../../config/config.inc.php'));
require_once(realpath(dirname(__FILE__).'/../../init.php'));
require_once(dirname(__FILE__).'/fema.php');

$params = array(
    'address1'          => Tools::getValue('address'),
    'postcode'          => Tools::getValue('zipcode'),
    'city'              => Tools::getValue('city'),
    'action'            => Tools::getValue('action'),
    'fema_cart_id' => Tools::getValue('fema_cart_id'),
    'fema_token'   => urlencode(Tools::getValue('fema_token')),
);

/* Check security token */
if (Tools::encrypt('fema/ajax')!=Tools::getValue('fema_token')||!Module::isInstalled('fema')) {
    die('Bad token');
}

if (Tools::getValue('action_ajax_fema')) {
    if (Tools::getValue('action_ajax_fema') == 'ajaxUpdatePoints') {
        $result = Module::getInstanceByName('fema')->ajaxUpdatePoints($params);
    }
    if (Tools::getValue('action_ajax_fema') == 'ajaxRegisterGsm') {
        $result = Tools::jsonEncode(Module::getInstanceByName('fema')->ajaxRegisterGsm($params));
    }
    if (Tools::getValue('action_ajax_fema') == 'ajaxRegisterPudo') {
        $result = Tools::jsonEncode(Module::getInstanceByName('fema')->ajaxRegisterPudo($params));
    }
}

echo $result;
