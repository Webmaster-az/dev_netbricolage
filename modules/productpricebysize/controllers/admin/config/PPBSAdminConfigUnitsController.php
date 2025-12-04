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

class PPBSAdminConfigUnitsController extends PPBSControllerCore
{
    protected $sibling;

    public function __construct(&$sibling = null)
    {
        parent::__construct($sibling);
        if ($sibling !== null) {
            $this->sibling = &$sibling;
        }
    }

    public function render()
    {
        $edit_unit = array();
        $languages = Language::getLanguages();

        $unit_model = new PPBSUnit();
        $units = $unit_model->getUnits();

        if ((int)Tools::getValue('id_ppbs_unit') > 0) {
            $edit_unit = new PPBSUnit((int)Tools::getValue('id_ppbs_unit'));
        }

        Context::getContext()->smarty->assign(array(
            'module_config_url' => $this->module_config_url,
            'units' => $units,
            'languages' => $languages,
            'id_lang_default' => Configuration::get('PS_LANG_DEFAULT', null, Context::getContext()->shop->id_shop_group, Context::getContext()->shop->id),
            'edit_unit' => $edit_unit
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/config/units.tpl');
    }

    public function processForm()
    {
        $languages = Language::getLanguages(false);

        if (Tools::getValue('id_ppbs_unit') != '') {
            $unit = new PPBSUnit(Tools::getValue('id_ppbs_unit'));
        } else {
            $unit = new PPBSUnit();
        }

        $unit->name = pSQL(Tools::getValue('name'));

        foreach ($languages as $key => $language) {
            $unit->symbol[$language['id_lang']] = pSQL(Tools::getValue('symbol_' . $language['id_lang']));
        }

        $unit->position = 0;
        $unit->save();
    }

    /**
     * Delete dimension
     */
    public function processDelete()
    {
        PPBSUnitHelper::deleteFull(Tools::getValue('id_ppbs_unit'));
    }

    public function route()
    {
        switch (Tools::getValue('action')) {
            case 'processform':
                die($this->processForm());

            case 'processdelete':
                die($this->processDelete());

            default:
                die($this->render());
        }
    }
}
