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
 * @copyright 2016-2021 Musaffar Patel
 * @license   LICENSE.txt
 */

class PPBSAdminProductTabController extends PPBSControllerCore
{
    protected $id_shop = 0;

    public function __construct($sibling, $params = array())
    {
        parent::__construct($sibling, $params);
        $this->sibling = $sibling;
        $this->base_url = Tools::getShopProtocol() . Tools::getShopDomain() . __PS_BASE_URI__;

        $product = new Product(Tools::getValue('id_product'));
        if (!empty($product->id_shop_default)) {
            $this->id_shop = $product->id_shop_default;
        } else {
            $this->id_shop = Context::getContext()->shop->id;
        }
    }

    public function setMedia()
    {
        if (Tools::getValue('controller') == 'AdminProducts') {
            Context::getContext()->controller->addCSS($this->sibling->_path . 'views/css/lib/tools.css');
            Context::getContext()->controller->addCSS($this->sibling->_path . 'views/css/lib/popup.css');
            Context::getContext()->controller->addCSS($this->sibling->_path . 'views/css/admin/global.css');

            Context::getContext()->controller->addJquery();
            Context::getContext()->controller->addJqueryPlugin('tablednd');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/lib/Tools.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/lib/Popup.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/widgets/equation_editor.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/producttab/PPBSAdminProductTabGeneralController.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/producttab/PPBSAdminProductTabFieldsController.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/producttab/PPBSAdminProductTabAreaPricesController.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/producttab/PPBSAdminProductTabEquationController.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/producttab/PPBSAdminProductTabStockManagementController.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/producttab/PPBSAdminProductTabWeightCalculationsController.js');
        }
    }

    public function render()
    {
        Context::getContext()->smarty->assign(array(
            'module_ajax_url' => $this->module_ajax_url,
            'id_product' => $this->params['id_product'],
            'id_shop' => $this->id_shop
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/producttab/main.tpl');
    }

    public function route()
    {
        $return = '';

        switch (Tools::getValue('route')) {
            case 'ppbsadminproducttabgeneralcontroller':
                $ppbs_admin_producttab_general_controller = new PPBSAdminProductTabGeneralController($this->sibling, $this->params);
                return $ppbs_admin_producttab_general_controller->route();

            case 'ppbsadminproducttabfieldscontroller':
                $ppbs_admin_producttab_fields_controller = new PPBSAdminProductTabFieldsController($this->sibling, $this->params);
                return $ppbs_admin_producttab_fields_controller->route();

            case 'ppbsadminproducttabareapricescontroller':
                $ppbs_admin_producttab_areaprices_controller = new PPBSAdminProductTabAreaPricesController($this->sibling, $this->params);
                return $ppbs_admin_producttab_areaprices_controller->route();

            case 'ppbsadminproducttabequationcontroller':
                $ppbs_admin_producttab_equation_controller = new PPBSAdminProductTabEquationController($this->sibling, $this->params);
                return $ppbs_admin_producttab_equation_controller->route();

            case 'ppbsadminproducttabstockmanagementcontroller':
                return (new PPBSAdminProductTabStockManagementController($this->sibling, $this->params))->route();

            case 'ppbsadminproducttabweightcalculationscontroller':
                return (new PPBSAdminProductTabWeightCalculationsController($this->sibling, $this->params))->route();

            default:
                return $this->render();
        }
    }
}
