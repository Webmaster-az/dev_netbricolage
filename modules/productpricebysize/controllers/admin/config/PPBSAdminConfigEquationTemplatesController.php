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

class PPBSAdminConfigEquationTemplatesController extends PPBSControllerCore
{
    protected $sibling;

    public function __construct(&$sibling = null)
    {
        parent::__construct($sibling);
        if ($sibling !== null) {
            $this->sibling = &$sibling;
        }
    }

    /**
     * Render the list of equation templates
     * @return mixed
     * @throws PrestaShopDatabaseException
     */
    public function render()
    {
        $equation_templates = PPBSEquationTemplateHelper::getAll();
        Context::getContext()->smarty->assign(array(
            'module_config_url' => $this->module_config_url,
            'equation_templates' => $equation_templates
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/config/equation_templates.tpl');
    }

    public function processDeleteEquationTemplate()
    {
        PPBSEquationTemplateHelper::delete(Tools::getValue('id_equation_template'));
    }

    public function processAddVariable()
    {
        $name = PPBSToolsHelper::safeName(\Tools::getValue('name'));
        $value = (float)Tools::getValue('value');

        if ($name == '') {
            return;
        }

        $ppbs_equation_var_model = PPBSEquationTemplateHelper::getVariableByName($name);
        if (empty($ppbs_equation_var_model->name)) {
            $ppbs_equation_var_model->name = $name;
            $ppbs_equation_var_model->value = $value;
            $ppbs_equation_var_model->add();
        } else {
            $ppbs_equation_var_model->value = $value;
            $ppbs_equation_var_model->save();
        }
    }

    public function processDeleteVariable()
    {
        $name = Tools::getValue('name');
        PPBSEquationTemplateHelper::deleteVariable($name);
    }

    public function renderVariablesList()
    {
        Context::getContext()->smarty->assign(array(
            'variables' => PPBSEquationTemplateHelper::getVariables()
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/config/equation_templates_variables_list.tpl');
    }

    public function route()
    {
        switch (Tools::getValue('action')) {
            case 'processdeleteequationtemplate':
                $this->processDeleteEquationTemplate();
                break;

            case 'processaddvariable':
                $this->processAddVariable();
                break;

            case 'processdeletevariable':
                $this->processDeleteVariable();
                break;

            case 'rendervariableslist':
                die($this->renderVariablesList());

            default:
                die($this->render());
        }
    }
}
