<?php
/**
 * 2013-2021 MADEF IT.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@madef.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    MADEF IT <contact@madef.fr>
 *  @copyright 2013-2021 MADEF IT
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class ResponsiveMenuAjaxModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        parent::__construct();
        $this->page_name = 'ajax';
    }

    public function initContent()
    {
        $responsive_menu = new ResponsiveMenu();
        switch (Tools::getValue('action')) {
            case 'cart':
                echo $responsive_menu->ajaxCallCart();
                break;
            case 'search':
                echo $responsive_menu->search();
                break;
            default:
                echo $responsive_menu->ajaxCallMenu();
        }
        exit;
    }
}
