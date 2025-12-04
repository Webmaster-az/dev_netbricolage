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

class PPBSAdminConfigOptionsController extends PPBSControllerCore
{
    protected $sibling;

    private $route = 'ppbsadminconfigoptionscontroller';

    public function __construct(&$sibling = null)
    {
        parent::__construct($sibling);
        if ($sibling !== null) {
            $this->sibling = &$sibling;
        }
    }

    /**
     * Get the form
     * @return string
     */
    public function getForm()
    {
        $inputs = array();
        $id_shop = Context::getContext()->shop->id;
        $config = new PPBSConfigModel(0, 0, $id_shop);
        $fields_form = array();

        $inputs[] = array(
            'type' => 'switch',
            'label' => $this->sibling->l('Display Total Area on product page?', $this->route),
            'hint' => $this->sibling->l('When enabled, the total area entered by the customer will be displayed on the product page', $this->route),
            'name' => 'display_total_area',
            'values' => array(
                array(
                    'id' => 'display_total_area_on',
                    'value' => 1,
                    'label' => $this->trans('Yes', array(), 'Admin.Global'),
                ),
                array(
                    'id' => 'display_total_area_off',
                    'value' => 0,
                    'label' => $this->trans('No', array(), 'Admin.Global'),
                ),
            )
        );

        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->sibling->l('Global Options', $this->route),
                'icon' => 'icon-cogs'
            ),
            'input' => $inputs,
        );

        $helper = new HelperForm();
        $helper->fields_value['display_total_area'] = $config->getDisplayTotalArea();
        $this->setupHelperConfigForm($helper, $this->route, 'process');
        return $helper->generateForm($fields_form);
    }

    /**
     * process the form
     */
    public function process()
    {
        $config = new PPBSConfigModel(0, 0);
        $config->setDisplayTotalArea((int)Tools::getValue('display_total_area'));
        $config->updateAll();
    }

    public function render()
    {
        Context::getContext()->smarty->assign(array(
            'form' => $this->getForm()
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/config/options.tpl');
    }

    public function route()
    {
        switch (Tools::getValue('action')) {
            case 'process':
                die($this->process());

            default:
                return $this->render();
        }
    }
}
