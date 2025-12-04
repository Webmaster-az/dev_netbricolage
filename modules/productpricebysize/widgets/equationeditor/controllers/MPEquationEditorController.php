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

class MPEquationEditorController extends PPBSControllerCore
{
    private $route = 'mpequationeditorcontroller';

    public function __construct($sibling, $params = array())
    {
        parent::__construct($sibling, $params);
        $this->sibling = $sibling;
        $this->base_url = Tools::getShopProtocol() . Tools::getShopDomain() . __PS_BASE_URI__;
    }

    public function render()
    {
        $fields = PPBSProductField::getCollectionByProduct(Tools::getValue('id_product'), Context::getContext()->language->id);
        $global_variables = PPBSEquationTemplateHelper::getVariables();

        Context::getContext()->smarty->assign(array(
            'fields' => $fields,
            'global_variables' => $global_variables,
            'equation_type' => Tools::getValue('equation_type')
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/widgets/equation_editor.tpl');
    }

    public function getEquationTemplatesList()
    {
        $equation_templates = PPBSEquationTemplateHelper::getAll(Tools::getValue('equation_type'));
        die(json_encode($equation_templates));
    }

    /**
     * Get the equation information for a template
     * @return false|string
     */
    public function getEquation()
    {
        $equation = PPBSEquationTemplateHelper::getEquationInfoForProduct(Tools::getValue('id_product'), Tools::getValue('id_product_attribute'), false, Tools::getValue('equation_type'));
        return json_encode($equation);
    }

    public function processSave()
    {
        $equation_type = Tools::getValue('equation_type');
        $json_return = array(
            'error' => 0,
            'error_element' => '',
            'error_text' => ''
        );

        // Save A new Template
        if (Tools::getValue('name') != '') {
            if (!PPBSEquationTemplateHelper::templateNameIsUnique(Tools::getValue('name'))) {
                $json_return = array(
                    'error' => 1,
                    'error_element' => 'name',
                    'error_text' => $this->sibling->l('equation with this name already exists', $this->route)
                );
                return json_encode($json_return);
            }
            $id_equation_template = PPBSEquationTemplateHelper::saveTemplate(0, Tools::getValue('equation'), Tools::getValue('name'));
            PPBSEquationTemplateHelper::assignTemplateToProduct(Tools::getValue('id_product'), Tools::getValue('id_product_attribute'), $id_equation_template, $equation_type);
            return true;
        }

        // save to existing template and assign to this product
        if ((int)Tools::getValue('id_equation_template') > 0) {
            $id_equation_template = Tools::getValue('id_equation_template');
            PPBSEquationTemplateHelper::saveTemplate($id_equation_template, Tools::getValue('equation'));
            PPBSEquationTemplateHelper::assignTemplateToProduct(Tools::getValue('id_product'), Tools::getValue('id_product_attribute'), $id_equation_template, $equation_type);
            return true;
        }

        // save directly (not as a template)
        if ((int)Tools::getValue('id_equation_template') == 0 && Tools::getValue('name') == '') {
            $ppbs_equation = new PPBSEquation();
            PPBSEquationTemplateHelper::deleteTemplateProduct(Tools::getValue('id_product'), Tools::getValue('id_product_attribute'), $equation_type);
            $ppbs_equation->saveEquation(Tools::getValue('id_product'), Tools::getValue('id_product_attribute'), Tools::getValue('equation'), $equation_type);
            return true;
        }
    }

    /**
     * Remove the equation associated with a product or product combination
     */
    public function processRemove()
    {
        PPBSEquationTemplateHelper::deleteTemplateProduct(Tools::getValue('id_product'), Tools::getValue('id_product_attribute'), Tools::getValue('equation_type'));
    }

    public function route()
    {
        switch (Tools::getValue('action')) {
            case 'processsave':
                die($this->processSave());

            case 'processremove':
                die($this->processRemove());

            case 'render':
                die($this->render());

            case 'getequationtemplateslist':
                die($this->getEquationTemplatesList());

            case 'getequation':
                die($this->getEquation());
        }
    }
}
