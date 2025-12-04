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

class PPBSAdminConfigMainController extends PPBSControllerCore
{
    protected $sibling;

    private $route = 'ppbsadminconfigmaincontroller';

    public function __construct(&$sibling = null)
    {
        parent::__construct($sibling);
        if ($sibling !== null) {
            $this->sibling = &$sibling;
        }
    }

    public function setMedia()
    {
        if (Tools::getValue('controller') == 'AdminModules' && Tools::getValue('configure') == 'productpricebysize') {
            Context::getContext()->controller->addCSS($this->sibling->_path . 'views/css/admin/global.css');
            Context::getContext()->controller->addCSS($this->sibling->_path . 'views/css/lib/tools.css');
            Context::getContext()->controller->addCSS($this->getAdminWebPath() . '/themes/new-theme/public/theme.css');
            Context::getContext()->controller->addCSS($this->sibling->_path . 'views/css/lib/mpproductsearchwidget.css');

            Context::getContext()->controller->addJquery();
            Context::getContext()->controller->addJS(_PS_BO_ALL_THEMES_DIR_ . 'default/js/tree.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/lib/Tools.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/config/PPBSAdminConfigDimensionsController.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/config/PPBSAdminConfigUnitsController.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/config/PPBSAdminConfigTranslationsController.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/config/PPBSAdminAreaPriceSuffixesController.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/config/PPBSAdminConfigEquationTemplatesController.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/config/PPBSAdminConfigGlobalOptionsController.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/admin/config/PPBSAdminConfigMassAssignController.js');
            Context::getContext()->controller->addJS($this->sibling->_path . 'views/js/lib/mpproductsearchwidget.js');
        }
    }

    public function render()
    {
        Context::getContext()->smarty->assign(array(
            'admin_url' => $this->admin_url,
            'module_config_url' => $this->module_config_url,
            'module_ajax_url_ppbs' => $this->module_ajax_url
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/config/main.tpl');
    }

    public function route()
    {
        return $this->render();
    }
}
