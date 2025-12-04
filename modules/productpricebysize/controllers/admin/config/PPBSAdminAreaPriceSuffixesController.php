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

class PPBSAdminAreaPriceSuffixesController extends PPBSControllerCore
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
        $edit_object = array();
        $languages = Language::getLanguages();

        $area_price_suffix = new PPBSAreaPriceSuffixModel();
        $collection = $area_price_suffix->getCollection(Context::getContext()->language->id);

        if ((int)Tools::getValue('id_ppbs_areapricesuffix') > 0) {
            $edit_object = new PPBSAreaPriceSuffixModel((int)Tools::getValue('id_ppbs_areapricesuffix'));
        }

        Context::getContext()->smarty->assign(array(
            'module_config_url' => $this->module_config_url,
            'collection' => $collection,
            'languages' => $languages,
            'id_lang_default' => Configuration::get('PS_LANG_DEFAULT', null, Context::getContext()->shop->id_shop_group, Context::getContext()->shop->id),
            'edit_object' => $edit_object
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/config/areapricesuffixes.tpl');
    }

    public function processForm()
    {
        $languages = Language::getLanguages(false);

        if (Tools::getValue('id_ppbs_areapricesuffix') != '') {
            $area_price_suffix = new PPBSAreaPriceSuffixModel((int)Tools::getValue('id_ppbs_areapricesuffix'));
        } else {
            $area_price_suffix = new PPBSAreaPriceSuffixModel();
        }

        $area_price_suffix->name = pSQL(Tools::getValue('name'));

        foreach ($languages as $key => $language) {
            $id_lang = $language['id_lang'];
            $area_price_suffix->text[$id_lang] = pSQL(Tools::getValue('areapricesuffix_text_' . $language['id_lang']));
        }
        $area_price_suffix->save();
    }

    /**
     * Delete dimension
     */
    public function processDelete()
    {
        PPBSAreaPriceSuffixModel::deleteMultiLangEntry(Tools::getValue('id_ppbs_areapricesuffix'));
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
