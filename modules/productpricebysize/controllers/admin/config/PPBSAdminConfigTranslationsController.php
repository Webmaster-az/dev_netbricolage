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

class PPBSAdminConfigTranslationsController extends PPBSControllerCore
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
        $languages = $this->sibling->context->controller->getLanguages();
        $translations = PPBSTranslation::loadTranslations(Context::getContext()->shop->id);

        Context::getContext()->smarty->assign(array(
            'module_config_url' => $this->module_config_url,
            'translations' => $translations,
            'languages' => $languages,
            'id_lang_default' => Configuration::get('PS_LANG_DEFAULT', null, Context::getContext()->shop->id_shop_group, Context::getContext()->shop->id)
        ));

        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/config/translations.tpl');
    }

    private function _parseTranslationFromForm($name)
    {
        $languages = $this->sibling->context->controller->getLanguages();
        $translation = array();

        foreach ($languages as $language) {
            if (Tools::getValue($name . '_' . $language['id_lang']) != '') {
                $translation[$name][$language['id_lang']] = Tools::getValue($name . '_' . $language['id_lang']);
            } else {
                $translation[$name][$language['id_lang']] = '';
            }
        }
        return $translation;
    }

    public function processForm()
    {
        $strings = array(
            'min_max_error',
            'generic_error',
            'unit_price_suffix',
            'cart_label'
        );
        foreach ($strings as $string) {
            $translation = $this->_parseTranslationFromForm($string);
            PPBSTranslation::saveTranslation($string, $translation);
        }
    }

    public function route()
    {
        switch (Tools::getValue('action')) {
            case 'processform':
                die($this->processForm());

            default:
                die($this->render());
        }
    }
}
