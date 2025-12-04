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

class PPBSAdminConfigDimensionsController extends PPBSControllerCore
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
        $edit_dimension = array();
        $id_lang = Context::getContext()->language->id;
        $dimensions = PPBSDimension::getDimensions($id_lang);
        $languages = Language::getLanguages();

        if ((int)Tools::getValue('id_ppbs_dimension') > 0) {
            $edit_dimension = new PPBSDimension((int)Tools::getValue('id_ppbs_dimension'));
            foreach ($languages as &$language) {
                $id_lang = $language['id_lang'];
                if (!empty($edit_dimension->image[$id_lang])) {
                    $language['image'] = $edit_dimension->image[$id_lang];
                } else {
                    $language['image'] = '';
                }
            }
        }

        Context::getContext()->smarty->assign(array(
            'module_config_url' => $this->module_config_url,
            'edit_dimension' => $edit_dimension,
            'dimensions' => $dimensions,
            'languages' => $languages,
            'id_lang_default' => Configuration::get('PS_LANG_DEFAULT', null, Context::getContext()->shop->id_shop_group, Context::getContext()->shop->id)
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/config/dimensions.tpl');
    }

    public function processForm()
    {
        $languages = Language::getLanguages(false);

        if (Tools::getValue('id_ppbs_dimension') != '') {
            $dimension = new PPBSDimension(Tools::getValue('id_ppbs_dimension'));
        } else {
            $dimension = new PPBSDimension();
        }

        $dimension->name = pSQL(Tools::getValue('name'));

        foreach ($languages as $key => $language) {
            $dimension->display_name[$language['id_lang']] = pSQL(Tools::getValue('display_name_' . $language['id_lang']));
        }

        foreach (Tools::getAllValues() as $key => $value) {
            if (strpos($key, 'image_') === 0) {
                $arr_tmp = explode('_', $key);
                $id_lang = $arr_tmp[1];
                $dimension->image[$id_lang] = pSQL($value);
            }
        }
        $dimension->position = 0;
        $dimension->save();
    }

    /**
     * Delete dimension
     */
    public function processDelete()
    {
        PPBSDimensionHelper::deleteFull(Tools::getValue('id_ppbs_dimension'));
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
