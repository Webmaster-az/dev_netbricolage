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

class ResponsiveMenuDispatchModuleFrontController extends ModuleFrontController
{
    public function __construct()
    {
        // Default controller
        $controller_class = 'CategoryController';

        $query = new DbQuery();
        $query->select('controller, controller_path, params');
        $query->from('responsivemenu_route');
        $query->where('id_category = '.(int) Tools::getValue('id_category'));
        $query->where('id_shop = '.(int) Context::getContext()->shop->id);
        $query->limit('1');

        foreach (Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query) as $row) {

            $controller_class = $row['controller'];
            $params = Tools::jsonDecode($row['params']);
            unset($_GET['fc']);
            unset($_POST['fc']);
            foreach ($params as $row => $value) {
                $_GET[$row] = $value;
                $_POST[$row] = $value;
            }
            if (!empty($row['controller_path'])) {
                require_once _PS_ROOT_DIR_.'/'.$row['controller_path'];
            }
        }

        try {
            // Loading controller
            $controller = Controller::getController($controller_class);

            // Execute hook dispatcher
            if (isset($params_hook_action_dispatcher)) {
                Hook::exec('actionDispatcher', $params_hook_action_dispatcher);
            }

            // Running controller
            $controller->run();

            // Execute hook dispatcher after
            if (isset($params_hook_action_dispatcher)) {
                Hook::exec('actionDispatcherAfter', $params_hook_action_dispatcher);
            }
        } catch (PrestaShopException $e) {
            $e->displayMessage();
        }
        exit; // Stop process
    }

    public function initContent()
    {
    }
}
