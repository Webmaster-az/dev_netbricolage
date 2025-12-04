<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Musaffar Patel
 * @copyright 2016-2017 Musaffar Patel
 * @license   LICENSE.txt
 */

class ProductPriceBySizeAjaxModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $this->ajax = true;
        // your code here
        parent::initContent();
        $this->route();
    }

    public function route()
    {
        $module = Module::getInstanceByName('productpricebysize');
        if (Tools::getValue('section') != '') {
            switch (Tools::getValue('section')) {
                case 'adminproducttab':
                    die($module->hookDisplayAdminProductsExtra($_POST));

                case 'ppbsadminordercontroller':
                    die((new PPBSAdminOrderController($module))->route());

                case 'mpproductsearchwidgetcontroller':
                    $mp_product_search_widget = new MPProductSearchWidgetController(Tools::getValue('id'), $module);
                    die(json_encode($mp_product_search_widget->route()));

                case 'mpequationeditorcontroller':
                    $equation_editor = new MPEquationEditorController($module);
                    die($equation_editor->route());

                case 'front_ajax':
                    die($module->route());
            }
        }
    }
}
