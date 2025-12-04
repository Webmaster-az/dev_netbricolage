<?php
/**
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Faviconotification extends Module
{
    protected $js_path = null;
    protected $path = null;
    protected static $conf_fields = array(
        'BACKGROUND_COLOR_FAVICON',
        'TEXT_COLOR_FAVICON',
        );

    public function __construct()
    {
        $this->name = 'faviconotification';
        $this->tab = 'front_office_features';
        $this->module_key = 'ac757babcc6c721672d9408e929b21da';
        $this->author_address = '0x64aa3c1e4034d07015f639b0e171b0d7b27d01aa';
        $this->version = '1.0.7';
        $this->author = 'Prestashop';
        $this->bootstrap = true;
        $this->logo_path = $this->_path.'logo.png';

        $link = new Link();
        $this->front_controller = $link->getModuleLink($this->name, 'FrontAjaxFaviconotification');

        parent::__construct();

        $this->displayName = $this->l('Browser tab notification');
        $this->description =
        $this->l('Decrease the number of you store\'s abandoned carts by adding a notification that will appear directly in the customer\'s browser.');
        $this->path = dirname(__FILE__) . '/';
        $this->js_path = $this->_path . 'views/js/';
    }

    public function install()
    {
        Configuration::updateValue('BACKGROUND_COLOR_FAVICON', '#ff0000');
        Configuration::updateValue('TEXT_COLOR_FAVICON', '#ffffff');
        if (parent::install() && $this->registerHook('header') == false) {
            return false;
        }
        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $this->registerHook('ActionFrontControllerSetMedia');
        }
        return true;
    }

    public function uninstall()
    {
        foreach (Faviconotification::$conf_fields as $field) {
            Configuration::deleteByName($field);
        }
        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $this->unregisterHook('ActionFrontControllerSetMedia');
        }
        return parent::uninstall() && $this->unregisterHook('header');
    }

    public function getContent()
    {
        $output ='';
        if (((bool)Tools::isSubmit('submitFavConf')) === true) {
            $output .= $this->postProcess();
        }
        return $output.$this->renderForm();
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'color',
                        'label' => $this->l('Background Color'),
                        'name' => 'BACKGROUND_COLOR_FAVICON',
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->l('Text Color'),
                        'name' => 'TEXT_COLOR_FAVICON',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save')
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        // $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
        Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitFavConf';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).
        '&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => array(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        foreach (Faviconotification::$conf_fields as $field) {
            $helper->tpl_vars['fields_value'][$field] = Tools::getValue($field, Configuration::get($field));
        }
        return $helper->generateForm(array($fields_form));
    }

    protected function postProcess()
    {
        // $languages = Language::getLanguages(false);
        $fields = array();

        $bg_color = Tools::getValue('BACKGROUND_COLOR_FAVICON');
        if (!empty($bg_color)) {
            $fields['BACKGROUND_COLOR_FAVICON'] =
            Tools::getValue('BACKGROUND_COLOR_FAVICON', Configuration::get('BACKGROUND_COLOR_FAVICON'));
        } else {
            $fields['BACKGROUND_COLOR_FAVICON'] = '#ff0000';
        }

        $txt_color = Tools::getValue('TEXT_COLOR_FAVICON');
        if (!empty($txt_color)) {
            $fields['TEXT_COLOR_FAVICON'] =
            Tools::getValue('TEXT_COLOR_FAVICON', Configuration::get('TEXT_COLOR_FAVICON'));
        } else {
            $fields['TEXT_COLOR_FAVICON'] = '#ffffff';
        }

        Configuration::updateValue('BACKGROUND_COLOR_FAVICON', $fields['BACKGROUND_COLOR_FAVICON']);
        Configuration::updateValue('TEXT_COLOR_FAVICON', $fields['TEXT_COLOR_FAVICON']);

        return $this->displayConfirmation($this->l('The settings have been updated.'));
    }

    public function hookHeader($params)
    {
        $this->loadAsset();

        $nbProductCart = 0;
        if (!empty($this->context->cart->id)) {
            $nbProductCart = (int)Cart::getNbProducts($this->context->cart->id);
        }
        $this->context->smarty->assign(array(
            'fav_front_controller' => $this->front_controller,
            'nbProductCart' => $nbProductCart,
            'BgColor' =>  Configuration::get('BACKGROUND_COLOR_FAVICON'),
            'TxtColor' =>  Configuration::get('TEXT_COLOR_FAVICON'),
        ));

        return $this->display(__FILE__, '/views/templates/hook/faviconotification.tpl');
    }

    public function loadAsset()
    {
        if (version_compare(_PS_VERSION_, '1.7', '<')) {
            // Load JS
            $jss = array(
                $this->js_path.'favico.js',
                $this->js_path.'faviconotification16.js',
            );

            $this->context->controller->addJS($jss);
        }
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerJavascript(
            'favico',
            'modules/'.$this->name.'/views/js/favico.js'
        );

        $this->context->controller->registerJavascript(
            'favicon17',
            'modules/'.$this->name.'/views/js/faviconotification17.js'
        );
    }
}
