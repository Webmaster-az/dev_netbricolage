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

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(_PS_MODULE_DIR_ . '/productpricebysize/lib/bootstrap.php');

class ProductPriceBySize extends Module
{
    public function __construct()
    {
        $this->name = 'productpricebysize';
        $this->tab = 'others';
        $this->version = '2.1.13';
        $this->author = 'Musaffar Patel';
        $this->need_instance = 0;
        $this->module_key = 'a44fdab3786e7699a005cbaacc7f36f2';
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        parent::__construct();
        $this->displayName = $this->l('Product Price by Size');
        $this->description = $this->l('Allow customers to customize products by area and calculate prices and weights dynamically');

        $this->bootstrap = true;
        $this->module_file = __FILE__;

        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
    }

    /*public function test()
    {
        $cart = new Cart();
        $cart->getPackageShippingCost($carrierId, false, null, $order->product_list);
    }*/

    public function setMedia()
    {
        (new PPBSAdminConfigMainController($this))->setMedia();
        (new PPBSAdminProductTabController($this))->setMedia();
        (new PPBSAdminOrderController($this))->setMedia();
        (new PPBSFrontProductController($this))->setMedia();
        (new PPBSFrontCartController($this))->setMedia();
    }

    public function install()
    {
        if (parent::install() == false
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('displayAdminProductsExtra')
            || !$this->registerHook('displayPPBSWidget')
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayFooter')
            || !$this->registerHook('displayFooterProduct')
            || !$this->registerHook('displayProductButtons')
            || !$this->registerHook('actionProductAdd')
            || !$this->registerHook('filterProductSearch')
            || !$this->registerHook('displayCustomization')
            || !$this->registerHook('actionValidateOrder')
            || !$this->registerHook('actionOrderStatusPostUpdate')
            || !$this->installModule()) {
            return false;
        }
        return true;
    }

    public function installModule()
    {
        PPBSInstall::installDb();
        PPBSInstall::installData();
        return true;
    }

    public function uninstall()
    {
        PPBSInstall::uninstall();
        return parent::uninstall();
    }

    public function route()
    {
        switch (Tools::getValue('route')) {
            case 'ppbsadminconfigoptionscontroller':
                $ppbs_admin_config_options_controller = new PPBSAdminConfigOptionsController($this);
                die($ppbs_admin_config_options_controller->route());

            case 'ppbsadminconfigdimensionscontroller':
                $ppbs_admin_config_dimensions_controller = new PPBSAdminConfigDimensionsController($this);
                die($ppbs_admin_config_dimensions_controller->route());

            case 'ppbsadminconfigunitscontroller':
                $ppbs_admin_config_units_controller = new PPBSAdminConfigUnitsController($this);
                die($ppbs_admin_config_units_controller->route());

            case 'ppbsadminareapricesuffixescontroller':
                $ppbs_admin_areapricesuffixes_controller = new PPBSAdminAreaPriceSuffixesController($this);
                die($ppbs_admin_areapricesuffixes_controller->route());

            case 'ppbsadminconfigtranslationscontroller':
                $ppbs_admin_translations_units_controller = new PPBSAdminConfigTranslationsController($this);
                die($ppbs_admin_translations_units_controller->route());

            case 'ppbsadminconfigequationtemplatescontroller':
                $ppbs_admin_equation_templates_controller = new PPBSAdminConfigEquationTemplatesController($this);
                die($ppbs_admin_equation_templates_controller->route());

            case 'ppbsadminconfigmassassigncontroller':
                $ppbs_admin_mass_assign_controller = new PPBSAdminConfigMassAssignController($this);
                die($ppbs_admin_mass_assign_controller->route());

            case 'ppbsfrontproductcontroller':
                $ppbs_front_product_controller = new PPBSFrontProductController($this);
                die($ppbs_front_product_controller->route());

            case 'ppbsfrontcartcontroller':
                $ppbs_front_cart_controller = new PPBSFrontCartController($this);
                die($ppbs_front_cart_controller->route());

            default:
                $ppbs_admin_config_main_controller = new PPBSAdminConfigMainController($this);
                return $ppbs_admin_config_main_controller->route();
        }
    }

    public function getContent()
    {
        return $this->route();
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        $controller_product_tab = new PPBSAdminProductTabController($this, $params);
        return $controller_product_tab->route();
    }


    /** Hooks  */

    public function hookDisplayHeader($params)
    {
        $this->setMedia();
    }

    public function hookDisplayFooter($params)
    {
        $ppbs_front_cart_controller = new PPBSFrontCartController($this);
        return $ppbs_front_cart_controller->hookDisplayFooter($params);
    }

    public function hookBackOfficeHeader($params)
    {
        $this->setMedia();
    }

    public function hookDisplayPPBSWidget($params)
    {
        $ppbs_front_product_controller = new PPBSFrontProductController($this);
        return $ppbs_front_product_controller->hookDisplayPPBSWidget($params);
    }

    public function hookDisplayFooterProduct($params)
    {
        $ppbs_front_product_controller = new PPBSFrontProductController($this);
        return $ppbs_front_product_controller->hookDisplayFooter($params);
    }

    /**
     * use this hook to initialise widget on quick view product modal
     * @param $params
     */
    public function hookDisplayProductButtons($params)
    {
        if (Tools::getValue('action') == 'quickview') {
            return $this->hookDisplayFooterProduct($params);
        }
    }

    public function hookActionProductAdd($params)
    {
        if (!empty($params['request'])) {
            $id_product_old = $params['request']->attributes->get('id');
            $id_product = $params['id_product'];
            if ((int)$id_product != (int)$id_product_old) {
                PPBSMassAssignHelper::duplicateProduct($id_product_old, $id_product, Context::getContext()->shop->id);
            }
        }
    }

    public function hookFilterProductSearch($params)
    {
        $ppbs_front_product_controller = new PPBSFrontProductController($this);
        $params = $ppbs_front_product_controller->hookFilterProductSearch($params);
    }


    /**
     * Called wherever product customization text is displayed to the user
     * @param $params
     * @return string
     */
    public function hookDisplayCustomization($params)
    {
        $return = (new PPBSFrontCartController($this))->hookDisplayCustomization($params);
        $return .= (new PPBSAdminOrderController($this))->hookDisplayCustomization($params);
        return $return;
    }

    /**
     * Back office header hook
     * @param $params
     */
    public function displayBackOfficeHeader($params)
    {
        $this->setMedia();
    }

    /**
     * When orders are displayed do this
     * @param $params
     */
    public function hookDisplayBackOfficeTop($params)
    {
        return (new PPBSAdminOrderController($this))->hookDisplayBackOfficeTop($params);
    }

    /**
     * Called when an order is placed
     * @param $params
     */
    public function hookActionValidateOrder($params)
    {
        return (new PPBSFrontCheckoutController($this))->hookActionValidateOrder($params);
    }

    /**
     * On order status changed
     * @param $params
     */
    public function hookActionOrderStatusPostUpdate($params)
    {
        return (new PPBSFrontCheckoutController($this))->hookActionOrderStatusPostUpdate($params);
    }
}
