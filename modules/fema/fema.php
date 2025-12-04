<?php
/**
 * 2007-2019 PrestaShop
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
 * @author    FEMA S.A.S. <support.ecommerce@dpd.fr>
 * @copyright 2019 FEMA S.A.S.
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class FEMA extends CarrierModule
{

    public function getOrderShippingCost($params, $shipping_cost)
    {
        if (!$this->context->cart instanceof Cart) {
            $this->context->cart = new Cart((int) $params->id);
        }
        
        return $shipping_cost;
    }

    public function getOrderShippingCostExternal($params)
    {
        return $params;
    }

    public function __construct()
    {
        $this->name = 'fema';
        $this->tab='shipping_logistics';
        $this->version = '1.0.2';
        $this->author = 'Fema';
        $this->module_key = '41c64060327b5afada101ff25bd38850';
        $this->need_instance = 1;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->multishop_context = Shop::CONTEXT_ALL | Shop::CONTEXT_GROUP | Shop::CONTEXT_SHOP;
        $this->multishop_context_group = Shop::CONTEXT_GROUP;

        parent::__construct();

        $this->displayName = $this->l('FEMA');
        $this->description = $this->l('Offer fast and reliable delivery services to your customers');
        $this->confirmUninstall = $this->l('Warning: all the data saved in your database will be deleted. Are you sure you want uninstall this module?');

        if (Configuration::get('FEMA_PARAM') == 0) {
            $this->warning = $this->l('Please proceed to the configuration of the FEMA plugin');
        }
        if (!extension_loaded('soap')) {
            $this->warning = $this->l('Warning! The PHP extension SOAP is not installed on this server. You must activate it in order to use the FEMA plugin');
        }
    }

    public function install()
    {
        // Prevent installation wrong PS version
        // if (_PS_VERSION_ < '1.7') {
        //     $this->_errors[] = $this->l('This version of the FEMA module is only compatible with Prestashop 1.7+.');
        //     return false;
        // }
        if (_PS_VERSION_ < '1.6') {
                $this->_errors[] = $this->l('This version of the FEMA module is only compatible with Prestashop 1.6+.');
                return false;
            }
        if (!parent::install()
        || !$this->installModuleTab('AdminFEMA', 'FEMA Orders', Tab::getIdFromClassName('AdminParentOrders'))
        || !$this->registerHooks()
        || !Configuration::updateValue('FEMA_PARAM', 0)
        || !Configuration::updateValue('FEMA_NOM_EXP', '')
        || !Configuration::updateValue('FEMA_ADDRESS_EXP', '')
        || !Configuration::updateValue('FEMA_ADDRESS2_EXP', '')
        || !Configuration::updateValue('FEMA_CP_EXP', '')
        || !Configuration::updateValue('FEMA_VILLE_EXP', '')
        || !Configuration::updateValue('FEMA_TEL_EXP', '')
        || !Configuration::updateValue('FEMA_EMAIL_EXP', '')
        || !Configuration::updateValue('FEMA_CLASSIC_USERNAME', '')
        || !Configuration::updateValue('FEMA_CLASSIC_PASSWORD', '')
        || !Configuration::updateValue('FEMA_CLASSIC_HEIGHT', '')  
        || !Configuration::updateValue('FEMA_CLASSIC_LENGTH', '')  
        || !Configuration::updateValue('FEMA_CLASSIC_WIDTH', '')
        || !$this->configDB()       
        ) {
            return false;
        }
        return true;
    }

    private function registerHooks()
    {
        if (!$this->registerHook('displayBackOfficeHeader')){
            return false;
        }
        return true;
    }

    public static function uninstallByName($name)
    {
        if (!is_array($name)) {
            $name = array($name);
        }
        $res = true;

        foreach ($name as $n) {
            if (Validate::isModuleName($n)) {
                $res &= Module::getInstanceByName($n)->uninstall();
            }
        }
        return $res;
    }

    public function installConfigDB()
    {
        // Database alteration : stretching the shipping_number field from 32 to 64 chars.
        $sql = 'ALTER TABLE `'._DB_PREFIX_.'orders` CHANGE `shipping_number` `shipping_number` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL';
        Db::getInstance()->Execute($sql);

        $sql_order = 'ALTER TABLE `'._DB_PREFIX_.'orders` 
                                                         ADD COLUMN IF NOT EXISTS `order_waybill` varchar(255) DEFAULT NULL,
                                                         ADD COLUMN IF NOT EXISTS `order_service` varchar(255) DEFAULT NULL,
                                                         ADD COLUMN IF NOT EXISTS `order_weight` DECIMAL(9,3) DEFAULT 0.00,
                                                         ADD COLUMN IF NOT EXISTS `order_volumes` int(10) DEFAULT 1';

        Db::getInstance()->Execute($sql_order);
        

        return true;
    }

    public function configDB()
    {
        
        // Database alteration : stretching the shipping_number field from 32 to 64 chars.
        $sql = 'ALTER TABLE `'._DB_PREFIX_.'orders` CHANGE `shipping_number` `shipping_number` VARCHAR(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL';
        Db::getInstance()->Execute($sql);
        

        //Create table fema_orders
        Db::getInstance()->execute(
            '
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'fema_orders` (
                `id_order` int(10) UNSIGNED DEFAULT NULL,
                `order_waybill` varchar(255) DEFAULT NULL,
                `order_service` varchar(255) DEFAULT NULL,
                `order_weight` DECIMAL(9,3) DEFAULT 0.00,
                `order_volumes` int(10) DEFAULT 1
            )
        '
        );
        

        return true;
    }


    public function uninstalDB(){
        Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_.'fema_orders`');

        return true;

    }


    public function uninstall()
    {
        if (!parent::uninstall()
        || !$this->uninstallModuleTab('AdminFEMA')
        || !$this->uninstalDB()
        || !Configuration::deleteByName('FEMA_NOM_EXP')
        || !Configuration::deleteByName('FEMA_ADDRESS_EXP')
        || !Configuration::deleteByName('FEMA_ADDRESS2_EXP')
        || !Configuration::deleteByName('FEMA_CP_EXP')
        || !Configuration::deleteByName('FEMA_VILLE_EXP')
        || !Configuration::deleteByName('FEMA_TEL_EXP')
        || !Configuration::deleteByName('FEMA_EMAIL_EXP')
        || !Configuration::deleteByName('FEMA_CLASSIC_USERNAME', '')
        || !Configuration::deleteByName('FEMA_CLASSIC_PASSWORD', '')
        || !Configuration::deleteByName('FEMA_CLASSIC_HEIGHT', '')  
        || !Configuration::deleteByName('FEMA_CLASSIC_LENGTH', '')  
        || !Configuration::deleteByName('FEMA_CLASSIC_WIDTH', '')   
        || !Configuration::deleteByName('FEMA_PARAM')) {
            return false;
        }
        return true;
    }

    /* Called in administration -> module -> configure */
    public function getContent()
    {
        $output = '<h2>'.$this->displayName.'</h2>';


        if (Tools::isSubmit('submitRcReferer')) {
            Configuration::updateValue('FEMA_NOM_EXP', Tools::getValue('nom_exp'));
            Configuration::updateValue('FEMA_ADDRESS_EXP', Tools::getValue('address_exp'));
            Configuration::updateValue('FEMA_ADDRESS2_EXP', Tools::getValue('address2_exp'));
            Configuration::updateValue('FEMA_CP_EXP', Tools::getValue('cp_exp'));
            Configuration::updateValue('FEMA_VILLE_EXP', Tools::getValue('ville_exp'));
            Configuration::updateValue('FEMA_TEL_EXP', Tools::getValue('tel_exp'));
            Configuration::updateValue('FEMA_EMAIL_EXP', Tools::getValue('email_exp'));
            Configuration::updateValue('FEMA_CLASSIC_USERNAME', Tools::getValue('classic_username'));
            Configuration::updateValue('FEMA_CLASSIC_PASSWORD', ltrim(Tools::getValue('classic_password'), '0'));
            Configuration::updateValue('FEMA_CLASSIC_LENGTH', Tools::getValue('classic_length'));
            Configuration::updateValue('FEMA_CLASSIC_WIDTH', Tools::getValue('classic_width'));
            Configuration::updateValue('FEMA_CLASSIC_HEIGHT', Tools::getValue('classic_height'));          
            Configuration::updateValue('FEMA_PARAM', 1);

            $output .= '<div class="okmsg">'.$this->l('Settings updated').'</div>';
        }
        return $output.$this->displayForm();
    }

    public function displayForm()
    {
        if (!extension_loaded('soap')) {
            echo '<div class="warnmsg">'.$this->l('Warning! The PHP extension SOAP is not installed on this server. You must activate it in order to use the FEMA plugin').'</div>';
        } else {
            $this->context->smarty->assign(array(
                'nom_exp'                      	=> Tools::getValue('nom_exp', Configuration::get('FEMA_NOM_EXP')),
                'address_exp'                  	=> Tools::getValue('address_exp', Configuration::get('FEMA_ADDRESS_EXP')),
                'address2_exp'                 	=> Tools::getValue('address2_exp', Configuration::get('FEMA_ADDRESS2_EXP')),
                'cp_exp'                       	=> Tools::getValue('cp_exp', Configuration::get('FEMA_CP_EXP')),
                'ville_exp'                    	=> Tools::getValue('ville_exp', Configuration::get('FEMA_VILLE_EXP')),
                'tel_exp'                      	=> Tools::getValue('tel_exp', Configuration::get('FEMA_TEL_EXP')),
                'email_exp'                    	=> Tools::getValue('email_exp', Configuration::get('FEMA_EMAIL_EXP')),
                'classic_username'             	=> Tools::getValue('classic_username', Configuration::get('FEMA_CLASSIC_USERNAME')),
                'classic_password'             	=> Tools::getValue('classic_password', Configuration::get('FEMA_CLASSIC_PASSWORD')),
                'classic_username'             	=> Tools::getValue('classic_username', Configuration::get('FEMA_CLASSIC_USERNAME')),
                'classic_height'             	=> Tools::getValue('classic_height', Configuration::get('FEMA_CLASSIC_HEIGHT')),
                'classic_length'             	=> Tools::getValue('classic_length', Configuration::get('FEMA_CLASSIC_LENGTH')),
                'classic_width'             	=> Tools::getValue('classic_width', Configuration::get('FEMA_CLASSIC_WIDTH')),
                'carriers'                     	=> Carrier::getCarriers($this->context->language->id, false, false, false, null, (defined('ALL_CARRIERS') ? ALL_CARRIERS : null)),
                'etats_factures'               	=> OrderState::getOrderStates((int) $this->context->language->id),
                'ps_version'                   	=> (float) _PS_VERSION_,
                'form_submit_url'              	=> $_SERVER['REQUEST_URI'],
            ));
            return $this->display(__FILE__, 'views/templates/admin/config.tpl');
        }
    }

    

    /* Calls CSS and JS files on header of front-office order pages */
    public function hookDisplayHeader()
    {
        if (!($file = basename(Tools::getValue('controller')))) {
            $file = str_replace('.php', '', basename($_SERVER['SCRIPT_NAME']));
        }
        if ($file == 'order') {
            $this->context->controller->registerStylesheet(
                'module-fema-css',
                '/modules/'.$this->name.'/views/css/front/fema.css',
                array('media' => 'all')
            );
            $this->context->controller->registerJavascript(
                'module-fema-jquery',
                '/js/jquery/jquery-1.11.0.min.js',
                array('position' => 'head', 'priority' => 1)
            );
            $this->context->controller->registerJavascript(
                'module-fema-js',
                '/modules/'.$this->name.'/views/js/front/fema_532.js',
                array('position' => 'bottom', 'priority' => 100)
            );
            $this->context->controller->registerJavascript(
                'module-fema-gmaps',
                'https://maps.googleapis.com/maps/api/js?key='.Configuration::get('FEMA_GOOGLE_API_KEY'),
                array('priority' => 100, 'server' => 'remote')
            );
            $this->context->smarty->assign(array(
                'ps_version'                        => (float) _PS_VERSION_,
                'fema_base_dir'                => __PS_BASE_URI__.'modules/'.$this->name,
                // 'fema_relais_carrier_id'       => (int) Configuration::get('FEMA_RELAIS_CARRIER_ID'),
                // 'fema_predict_carrier_id'      => (int) Configuration::get('FEMA_PREDICT_CARRIER_ID'),
                'fema_cart'                    => $this->context->cart,
                'fema_token'                   => Tools::encrypt('fema/ajax'),
            ));
            return $this->display(__FILE__, 'views/templates/front/header.tpl');
        }
    }


    private function installModuleTab($tab_class, $tab_name, $id_tab_parent)
    {
        $tab = new Tab();

        $languages = Language::getLanguages(false);
        foreach ($languages as $language) {
            $tab->name[$language['id_lang']] = $tab_name;
        }
        $tab->class_name = $tab_class;
        $tab->module = $this->name;
        $tab->id_parent = $id_tab_parent;

        if (!$tab->save()) {
            return false;
        }
        return true;
    }

    private function uninstallModuleTab($tab_class)
    {
        $id_tab = Tab::getIdFromClassName($tab_class);
        if ($id_tab != 0) {
            $tab = new Tab($id_tab);
            $tab->delete();
            return true;
        }
        return false;
    }


}