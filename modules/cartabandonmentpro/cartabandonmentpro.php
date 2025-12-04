<?php
/**
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class CartAbandonmentPro extends Module
{
    protected static $lang_cache;

    public function __construct()
    {
        $this->name                     = 'cartabandonmentpro';
        $this->tab                      = 'advertising_marketing';
        $this->version                  = '1.8.8';
        $this->author                   = 'PrestaShop';
        $this->module_key               = '011df651e7ac1913166469984d0cf519';
        $this->author_address           = '0x64aa3c1e4034d07015f639b0e171b0d7b27d01aa';
        $this->need_instance            = 0;
        $this->dependencies             = array();
        $this->bootstrap                = true;
        parent::__construct();

        $this->displayName              = $this->l('Cart Abandonment Pro');
        $this->description              = $this->l('Send an automatic mail to customers that abandoned their shopping cart.');

        $this->confirmUninstall         = $this->l('Are you sure you want to uninstall?');

        $this->css_path                 = $this->_path.'views/css/';
        $this->js_path                  = $this->_path.'views/js/';
        $this->controllers              = array('FrontCartAbandonment');

        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $this->getLang();
        }
    }

    public function install()
    {
        $id_shop = $this->context->shop->id;
        Configuration::updateValue('CART_MAXREMINDER', 7, false, null, $id_shop);
        Configuration::updateValue('CART_MAXREMINDER_WHAT', 'days', false, null, $id_shop);
        Configuration::updateValue('CARTABAND_DISCOUNT_WITH_TAXES_1', true, false, null, $id_shop);
        Configuration::updateValue('CARTABAND_DISCOUNT_WITH_TAXES_2', true, false, null, $id_shop);
        Configuration::updateValue('CARTABAND_DISCOUNT_WITH_TAXES_3', true, false, null, $id_shop);

        $token = uniqid(rand(), true);
        foreach (Shop::getShops() as $shop) {
            Configuration::set('CAB_NEWS', 1, null, $shop['id_shop']);
            Configuration::updateValue('CARTABAND_TOKEN', $token, false, null, $shop['id_shop']);
        }
        Configuration::updateValue('CARTABAND_TOKEN', $token, false, null, $id_shop);

        $tokenClient = uniqid(rand(), true);
        foreach (Shop::getShops() as $shop) {
            Configuration::updateValue('CARTABAND_TOKEN_CLIENT', $tokenClient, false, null, $shop['id_shop']);
        }
        Configuration::updateValue('CARTABAND_TOKEN_CLIENT', $tokenClient, false, null, $id_shop);

        Configuration::updateValue('CARTABAND_DISCOUNT', 0, false, null, $id_shop);
        self::initDirectory('tpls', '0777');
        self::initDirectory('mails', '0777');


        if (!$this->installDB()) {
            $this->_errors[] = Tools::displayError('Can\'t create tables, please contact your hosting provider');
            return false;
        }

        return parent::install()
            && $this->installTab('AdminCartAbandonment')
            && $this->registerHook('displayBackOfficeHeader')
            && $this->registerHook('actionAdminCartsListingFieldsModifier');
    }

    private function installDB()
    {
        $queries = array();
        $queries[0] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_template` (
                  `id_template` int(11) NOT NULL AUTO_INCREMENT,
                  `id_model` int(11) NOT NULL,
                  `name` varchar(100) NOT NULL,
                  `id_lang` int(11),
                  `id_shop` int(11),
                  `active` int(11),
                  `order` int(11),
                  PRIMARY KEY (`id_template`)
                  );";
        $queries[1] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_template_field` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `id_template` int(11) NOT NULL,
                      `id_field` int(11) NOT NULL,
                      `value` longtext NOT NULL,
                      `column` varchar(10) NOT NULL,
                      PRIMARY KEY (`id`)
                    );";
        $queries[2] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_template_color` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `id_template` int(11) NOT NULL,
                  `id_color` int(11) NOT NULL,
                  `value` varchar(15) NOT NULL,
                  PRIMARY KEY (`id`)
                );";
        $queries[3] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_remind_config` (
                  `wich_remind` int(11) NOT NULL,
                  `days` int(11) NOT NULL,
                  `hours` int(11) NOT NULL,
                  `active` int(11) NOT NULL,
                  `id_shop` int(11) NOT NULL,
                  PRIMARY KEY (`wich_remind`)
                );";
        $queries[4] = "INSERT INTO `"._DB_PREFIX_."cartabandonment_remind_config` VALUES (1, 0, 2, 1, 0);";
        $queries[5] = "INSERT INTO `"._DB_PREFIX_."cartabandonment_remind_config` VALUES (2, 2, 0, 0, 0);";
        $queries[6] = "INSERT INTO `"._DB_PREFIX_."cartabandonment_remind_config` VALUES (3, 5, 0, 0, 0);";

        $queries[7] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_remind` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `wich_remind` int(11) NOT NULL,
                  `id_cart` int(11) NOT NULL,
                  `send_date` date NOT NULL,
                  PRIMARY KEY (`id`)
                );";
        $queries[8] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_remind_lang` (
                  `wich_remind` int(11) NOT NULL,
                  `id_lang` int(11) NOT NULL,
                  `id_template` int(11) NOT NULL,
                  `tpl_same` int(11) NOT NULL,
                  `id_shop` int(11) NOT NULL,
                  PRIMARY KEY (`wich_remind`,`id_lang`,`id_template`)
                );";
        $queries[9] = "ALTER TABLE `"._DB_PREFIX_."cartabandonment_remind` ADD `visualize` INT NOT NULL DEFAULT '0',
                ADD `click` INT NOT NULL DEFAULT '0';";
        $queries[10] = "ALTER TABLE `"._DB_PREFIX_."cartabandonment_remind` ADD `click_cart` INT NOT NULL DEFAULT '0';";
        $queries[11] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_unsubscribe` (
                `id_customer` int(11) NOT NULL,
                PRIMARY KEY (`id_customer`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $queries[12] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonmentpro_cartrule` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `type` varchar(10) NOT NULL,
                      `value` float NOT NULL,
                      `date_start` date DEFAULT NULL,
                      `date_end` date DEFAULT NULL,
                      `valid` varchar(5) NOT NULL,
                      `valid_value` int(11) DEFAULT NULL,
                      `valid_date` date DEFAULT NULL,
                      `min_amount` float DEFAULT NULL,
                      `max_amount` int(11) DEFAULT NULL,
                      `id_template` int(11) NOT NULL,
                      `tranche` int(11) NOT NULL DEFAULT '0',
                      `id_shop` int(11) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ";
        $queries[13] = "ALTER TABLE  `"._DB_PREFIX_."cartabandonment_template` CHANGE  `name`  `name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL";
        foreach ($queries as $query) {
            if (!Db::getInstance()->Execute($query)) {
                return false;
            }
        }
        return true;
    }

    public function uninstall()
    {
        $query  = "DROP TABLE IF EXISTS `"._DB_PREFIX_."cartabandonment_template`;";
        $query2 = "DROP TABLE IF EXISTS `"._DB_PREFIX_."cartabandonment_template_field`;";
        $query3 = "DROP TABLE IF EXISTS `"._DB_PREFIX_."cartabandonment_template_color`;";
        $query4 = "DROP TABLE IF EXISTS `"._DB_PREFIX_."cartabandonment_conf`;";
        $query4 = "DROP TABLE IF EXISTS `"._DB_PREFIX_."cartabandonment_remind`;";
        $query5 = "DROP TABLE IF EXISTS `"._DB_PREFIX_."cartabandonment_remind_lang`;";
        $query6 = "DROP TABLE IF EXISTS `"._DB_PREFIX_."cartabandonment_remind_config`;";
        $query7 = "DROP TABLE IF EXISTS `"._DB_PREFIX_."cartabandonmentpro_cartrule`;";

        return Db::getInstance()->Execute($query)
            && Db::getInstance()->Execute($query2)
            && Db::getInstance()->Execute($query3)
            && Db::getInstance()->Execute($query4)
            && Db::getInstance()->Execute($query5)
            && Db::getInstance()->Execute($query6)
            && Db::getInstance()->Execute($query7)
            && $this->uninstallTab('AdminCartAbandonment')
            && parent::uninstall();
    }

     /**
    * Loads asset resources
    */
    public function loadAsset()
    {
        $css_compatibility = $js_compatibility = array();

        // Load CSS
        $css = array(
            $this->css_path.'fix.css',
            $this->css_path.'views/css/reset.css',
            $this->css_path.'cartabandonmentpro.css',
            $this->css_path.'faq.css',
        );
        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $css_compatibility = array(
                $this->css_path.'bootstrap.min.css',
                $this->css_path.'bootstrap.extend.css',
                $this->css_path.'bootstrap-responsive.min.css'
            );
            $css = array_merge($css_compatibility, $css);
            $fa = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css';
            $this->context->controller->css_files[$fa] = 'all';
        }
        $this->context->controller->addCSS($css, 'all');

        // Load JS
        $js = array(
            _PS_JS_DIR_.'tiny_mce/tiny_mce.js',
            _PS_JS_DIR_.'tinymce.inc.js',
            _PS_JS_DIR_.'admin/tinymce.inc.js',
            $this->js_path.'jscolor.js',
            $this->js_path.'faq.js',
        );
        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $js_compatibility = array(
                $this->js_path.'bootstrap.min.js'
            );
            $js = array_merge($js_compatibility, $js);
        }

        $js[] = $this->js_path.$this->name.'.js';

        $this->context->controller->addJS($js);

        // Clean memory
        unset($js, $css, $js_compatibility, $css_compatibility);
    }

    // Override the default KPI on abandoned carts
    public function hookDisplayBackOfficeHeader($params)
    {
        if (Tools::getValue('ajax') && Tools::getValue('action') == 'getKpi' && Tools::getValue('kpi') == 'abandoned_cart') {
            require_once dirname(__FILE__).'/controllers/ReminderController.class.php';

            $value = count(ReminderController::getAbandonedCart(1, $this->context->shop->id));
            die(Tools::jsonEncode(array('value' => $value)));
        }
    }

    // Change default Abandoned cart behavior from PrestaShop to match settings in the module
    public function hookActionAdminCartsListingFieldsModifier($params)
    {
        require_once dirname(__FILE__).'/controllers/ReminderController.class.php';

        $reminder = ReminderController::getReminders(1);
        if (!$reminder[0]['active']) {
            return;
        }

        $time = $reminder[0]['days']*86400 + $reminder[0]['hours']*3600;

        $max_time = Configuration::get('CART_MAXREMINDER', null, null, Context::getContext()->shop->id) * 86400;

        $id_order_column = version_compare(_PS_VERSION_, '1.6.1.0', '>=') ? 'status' : 'id_order';

        $params['select'] = 'CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) `customer`, a.id_cart total, ca.name carrier, IF (IFNULL(o.id_order, \''.$this->l('Non ordered').'\') = \''.$this->l('Non ordered').'\', IF(a.id_customer AND TIME_TO_SEC(TIMEDIFF(\''.pSQL(date('Y-m-d H:i:00', time())).'\', a.`date_upd`)) > '.pSQL($time).' AND TIME_TO_SEC(TIMEDIFF(\''.pSQL(date('Y-m-d H:i:00', time())).'\', a.`date_upd`)) < '.pSQL($max_time).', \''.$this->l('Abandoned cart').'\', \''.$this->l('Non ordered').'\'), o.id_order) AS '.pSQL($id_order_column).', IF(o.id_order, 1, 0) badge_success, IF(o.id_order, 0, 1) badge_danger, IF(co.id_guest, 1, 0) id_guest';
    }

    public function verifyAdminTabExists()
    {
        if (!Db::getInstance()->executes('SELECT * FROM '._DB_PREFIX_.'tab WHERE class_name = "AdminCartAbandonment"')) {
            $this->installTab('AdminCartAbandonment');
        }
    }

    public function getContent()
    {
        $this->verifyAdminTabExists();
        $this->unregisterHook('actionCronJob');
        require_once dirname(__FILE__).'/classes/Model.class.php';
        require_once dirname(__FILE__).'/classes/Template.class.php';
        require_once dirname(__FILE__).'/controllers/TemplateController.class.php';
        require_once dirname(__FILE__).'/controllers/ReminderController.class.php';
        require_once dirname(__FILE__).'/controllers/StatsController.class.php';
        require_once dirname(__FILE__).'/controllers/DiscountsController.class.php';

        // Save template
        if (Tools::getValue('edit') == 1) {
            $reminderController = new ReminderController();
            $reminderController->edit();
        }

        $this->loadAsset();

        $this->handleDiscounts();
        $this->viewEdit();
        $this->initVars();
        $this->initStats();

        include_once('classes/APIFAQClass.php');
        $api = new APIFAQ();
        $api_json = Tools::jsonDecode($api->getData($this));
        $this->context->smarty->assign('apifaq', $api_json->categories);

        return $this->display(__FILE__, 'views/templates/admin/configuration.tpl');
    }

    private function handleDiscounts()
    {
        $id_shop = $this->context->shop->id;
        $discounts_tab = 0;
        $template_disc = 1;
        $discounts_save = 0;
        Configuration::updateValue('CARTABAND_DISCOUNT', 0, null, null, $id_shop);
        if (Tools::isSubmit('discounts_template')) {
            $template_disc = Tools::getValue('discounts_template');
            $discounts_tab = 1;
        }

        if (Tools::isSubmit('discounts_form_submit')) {
            if (Tools::getValue('discounts_active') == 1) {
                if (Tools::getValue('discounts_different_val') == 0) {
                    $query = 'DELETE FROM '._DB_PREFIX_."cartabandonmentpro_cartrule WHERE id_template = ".(int)Tools::getValue('discounts_template')." AND id_shop = ".(int)$id_shop.";";
                    Db::getInstance()->Execute($query);
                    $query = 'INSERT INTO '._DB_PREFIX_.'cartabandonmentpro_cartrule VALUE (NULL, "'.pSQL(Tools::getValue('discounts_type')).'", "'.(int)Tools::getValue('discounts_value').'", NULL, NULL, "'.(int)Tools::getValue('discounts_validity').'", "'.(int)Tools::getValue('discounts_validity_days').'", NULL, '.(int)Tools::getValue('discounts_min').',
                    '.(int)Tools::getValue('discounts_max').',
                    '.(int)Tools::getValue('discounts_template').',
                    0,'.(int)$id_shop.');';
                    Db::getInstance()->Execute($query);
                }
                else {
                    $query = 'DELETE FROM '._DB_PREFIX_."cartabandonmentpro_cartrule WHERE id_template = ".(int)Tools::getValue('discounts_template')." AND id_shop = ".(int)$id_shop.";";
                    Db::getInstance()->Execute($query);

                    $tranches = Tools::getValue('discounts_tranche');

                    for ($x = 1; $x <= 3; $x++) {
                        $query = 'INSERT INTO '._DB_PREFIX_.'cartabandonmentpro_cartrule VALUE (NULL, "'.pSQL(Tools::getValue('discounts_type_'.$x)).'", "'.(int)Tools::getValue('discounts_value_'.$x).'", NULL, NULL, "time", "'.(int)Tools::getValue('discounts_validity_days_'.$x).'", NULL, '.(int)Tools::getValue('discounts_min_'.$x).', '.(int)Tools::getValue('discounts_max_'.$x).', '.(int)Tools::getValue('discounts_template').', '.$x.','.(int)$id_shop.');';
                        Db::getInstance()->Execute($query);
                        if ($tranches == $x) {
                            break;
                        }
                    }
                }
                Configuration::updateValue('CARTABAND_DISCOUNT', 1, null, null, $id_shop);
            } else {
                $query = 'DELETE FROM '._DB_PREFIX_."cartabandonmentpro_cartrule WHERE id_template = ".(int)Tools::getValue('discounts_template')." AND id_shop = ".(int)$id_shop.";";
                Db::getInstance()->Execute($query);
            }
            $discounts_tab = 1;
            $discounts_save = 1;
            Configuration::updateValue('CARTABAND_TRANCHE', Tools::getValue('discounts_tranche'), null, null, $id_shop);
            Configuration::updateValue('CARTABAND_DISCOUNT_'.$template_disc, Tools::getValue('discounts_active_val'), null, null, $id_shop);
            Configuration::updateValue('CARTABAND_DIF_DISC_'.$template_disc, Tools::getValue('discounts_different_val2'), null, null, $id_shop);
            Configuration::updateValue('CARTABAND_DISCOUNT_WITH_TAXES_'.$template_disc, Tools::getValue('discount_taxes'), null, null, $id_shop);
            DiscountsController::saveDiscountsTxt($id_shop);
        }

        $query = 'SELECT * FROM '._DB_PREFIX_.'cartabandonmentpro_cartrule WHERE id_template = '.(int)$template_disc.' AND id_shop = '.(int)$id_shop.' ORDER BY tranche;';
        $discounts = Db::getInstance()->ExecuteS($query);
        if (empty($discounts)) {
            $discounts[0]['type'] = 'currency';
            $discounts[1]['type'] = 'currency';
            $discounts[2]['type'] = 'currency';
        } elseif (count($discounts) < 2) {
            $discounts[1]['type'] = 'currency';
            $discounts[2]['type'] = 'currency';
        } elseif (count($discounts) < 3) {
            $discounts[2]['type'] = 'currency';
        }

        $tranches = Configuration::get('CARTABAND_TRANCHE');
        if (!$tranches) {
            $tranches = 1;
        }

        $errors = array();
        $errors['val'] = $this->l('Discount value is not correct.');
        $errors['valid'] = $this->l('Discount validity is not correct.');

        $errors['value_1'] = $this->l('Discount value for range 1 is not correct.');
        $errors['valid_1'] = $this->l('Discount validity for range 1 is not correct.');
        $errors['min_1'] = $this->l('Minimum value for range 1 is not correct.');

        $errors['value_2'] = $this->l('Discount value for range 2 is not correct.');
        $errors['valid_2'] = $this->l('Discount validity for range 2 is not correct.');
        $errors['min_2'] = $this->l('Minimum value for range 2 is not correct.');

        $errors['value_3'] = $this->l('Discount value for range 3 is not correct.');
        $errors['valid_3'] = $this->l('Discount validity for range 3 is not correct.');
        $errors['min_3'] = $this->l('Minimum value for range 3 is not correct.');

        $this->context->smarty->assign('errors', $errors);
        $this->context->smarty->assign('tranches', $tranches);
        $this->context->smarty->assign('template_disc', $template_disc);
        $this->context->smarty->assign('discounts2', $discounts);
        $this->context->smarty->assign('discounts_tab', $discounts_tab);
        $this->context->smarty->assign('currency', Currency::getDefaultCurrency()->sign);
        $this->context->smarty->assign('discountsActive', Configuration::get('CARTABAND_DISCOUNT_'.$template_disc));
        $this->context->smarty->assign('discountsDif', Configuration::get('CARTABAND_DIF_DISC_'.$template_disc));
        $this->context->smarty->assign('discount_with_taxes', Configuration::get('CARTABAND_DISCOUNT_WITH_TAXES_'.$template_disc));
        $this->context->smarty->assign('discounts_tranche', $tranches);
        $this->context->smarty->assign('discounts_save', $discounts_save);
    }

    private function initStats()
    {
        $this->context->smarty->assign('carts1', ReminderController::getAbandonedCart(1, $this->context->shop->id));
        $this->context->smarty->assign('carts2', ReminderController::getAbandonedCart(2, $this->context->shop->id));
        $this->context->smarty->assign('carts3', ReminderController::getAbandonedCart(3, $this->context->shop->id));
        $this->context->smarty->assign('reminded_carts', ReminderController::getLastCartsReminded());
        $this->context->smarty->assign('stats', StatsController::getStatsForReminder());
        $this->context->smarty->assign('unsubscribe', StatsController::getUnsubscribe());
    }

    /* initRight is use to verify the right of a directory */

    public static function initRight($path, $right = '0777')
    {
        $prems = Tools::substr(sprintf('%o', fileperms(dirname(__FILE__).'/'.$path)), -4);
        if ($prems != $right) {
            @chmod(dirname(__FILE__).'/'.$path, octdec((int)$right));
        }
    }

    /* initDirectory is use to check if the directory exist and permit to change the right */

    public static function initDirectory($path, $right = '0777')
    {
        if (!is_dir(dirname(__FILE__).'/'.$path)) {
            mkdir(dirname(__FILE__).'/'.$path);
            $fp = fopen(dirname(__FILE__).'/'.$path.'/index.php', 'w+');
            fwrite($fp, "<?php die();");
            fclose($fp);
        }

        self::initRight($path, $right);
    }

    private function initVars()
    {
        $this->context->smarty->assign('token', Configuration::get('CARTABAND_TOKEN', null, $this->context->shop->id_shop_group, $this->context->shop->id));

        $languages = Language::getLanguages(true, $this->context->shop->id);
        if (empty($languages)) {
            $languages = Language::getLanguages(true);
        }

        $id_lang = Tools::getValue('id_lang');
        $id_lang_doc = $this->context->employee->id_lang;

        if (!$id_lang) {
            $id_lang = Configuration::get('PS_LANG_DEFAULT');
        }

        $this->context->smarty->assign('languages', $languages);
        $this->context->smarty->assign('lang_default', Configuration::get('PS_LANG_DEFAULT', null, $this->context->shop->id_shop_group, $this->context->shop->id));
        $logo = Configuration::get('PS_LOGO_MAIL');
        if (!$logo || $logo == '' || !file_exists(_PS_IMG_DIR_.$logo)) {
            $logo = Configuration::get('PS_LOGO');
        }
        $this->context->smarty->assign('logo', $this->context->shop->domain.__PS_BASE_URI__.'img/'.$logo);
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $this->context->smarty->assign('uri', $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $this->context->smarty->assign('url', $protocol . $_SERVER['HTTP_HOST'] . __PS_BASE_URI__);
        $this->context->smarty->assign('domain', $protocol . $_SERVER['HTTP_HOST']);

        $this->context->smarty->assign('dirname', dirname(__FILE__));

        $this->initReminders();

        foreach ($languages as $language) {
            $this->context->smarty->assign('discount_val_text_'.$language['id_lang'], Configuration::get('CARTABAND_DISC_VAL', $language['id_lang'], null, $this->context->shop->id));
            $this->context->smarty->assign('discount_shipping_text_'.$language['id_lang'], Configuration::get('CARTABAND_SHIPP_VAL', $language['id_lang'], null, $this->context->shop->id));
        }

        $this->context->smarty->assign('templates', TemplateController::getAllTemplates($this->context->shop->id, $id_lang));
        $this->context->smarty->assign('id_shop', $this->context->shop->id);
        $this->context->smarty->assign('id_lang', $id_lang);
        $this->context->smarty->assign('language', $id_lang);
        $this->context->smarty->assign('iso_lang', Language::getIsoById($id_lang));
        $this->context->smarty->assign('iso_lang_doc', Language::getIsoById($id_lang_doc));
        $this->context->smarty->assign('lang_select', self::$lang_cache);
        $this->context->smarty->assign('token_send', Configuration::get('CARTABAND_TOKEN', null, $this->context->shop->id_shop_group, $this->context->shop->id));

        $this->context->smarty->assign('template_name_1', TemplateController::getTemplateName(1));
        $this->context->smarty->assign('template_name_2', TemplateController::getTemplateName(2));
        $this->context->smarty->assign('template_name_3', TemplateController::getTemplateName(3));

        $conf = Tools::getValue('cartabandonment_conf');
        if (!isset($conf)) {
            $conf = 0;
        }
        $this->context->smarty->assign('conf', $conf);

        $discounts = Tools::getValue('cartabandonment_discount');
        if (!isset($discounts)) {
            $discounts = 0;
        }
        $this->context->smarty->assign('discounts', $discounts);

        $this->initEdit($id_lang);

        if (Tools::getValue('justEdited') == 1) {
            $edit = 1;
        } else {
            $edit = 0;
        }

        $iso_lang = Language::getIsoById($id_lang);
        $iso_tiny_mce = (Tools::file_exists_cache(_PS_ROOT_DIR_.'/js/tiny_mce/langs/'.$iso_lang.'.js') ? $iso_lang : 'en');
        $ad = __PS_BASE_URI__.basename(_PS_ADMIN_DIR_);

        $url = $this->context->link->getModuleLink($this->name, 'FrontCartAbandonment', array(), true);
        if (Configuration::get('PS_REWRITING_SETTINGS') == '1')
            $url .= '?';
        else
            $url .= '&';

        $this->context->smarty->assign(array(
            'admin_ca_url'   => Context::getContext()->link->getAdminLink('AdminCartAbandonment'),
            'front_ca_url'   => $url,
            'base_url'       => Tools::getHttpHost(true).Tools::substr(__PS_BASE_URI__, 0, -1),
            'ps_version'     => (bool)version_compare(_PS_VERSION_, '1.6', '>'),
            'edit'           => $edit,
            'isWritable'     => (int)(is_writable('../modules/cartabandonmentpro/mails/') && is_writable('../modules/cartabandonmentpro/tpls/')),
            'module_version' => $this->version,
            'newsletter'     => Configuration::get('CAB_NEWS', null, $this->context->shop->id_shop_group, $this->context->shop->id),
            'iso_tiny_mce'   => $iso_tiny_mce,
            'ad'             => $ad,
        ));
    }

    // Get reminders
    private function initReminders()
    {
        $reminder = ReminderController::getReminders(1);
        $this->context->smarty->assign('first_reminder_days', $reminder[0]['days']);
        $this->context->smarty->assign('first_reminder_hours', $reminder[0]['hours']);
        $this->context->smarty->assign('first_reminder_active', $reminder[0]['active']);

        $reminder = ReminderController::getReminders(2);
        $this->context->smarty->assign('second_reminder_days', $reminder[0]['days']);
        $this->context->smarty->assign('second_reminder_hours', $reminder[0]['hours']);
        $this->context->smarty->assign('second_reminder_active', $reminder[0]['active']);

        $reminder = ReminderController::getReminders(3);
        $this->context->smarty->assign('third_reminder_days', $reminder[0]['days']);
        $this->context->smarty->assign('third_reminder_hours', $reminder[0]['hours']);
        $this->context->smarty->assign('third_reminder_active', $reminder[0]['active']);

        $this->context->smarty->assign('max_reminder', Configuration::get('CART_MAXREMINDER', null, null, Context::getContext()->shop->id));
        $this->context->smarty->assign('max_reminder_what', Configuration::get('CART_MAXREMINDER_WHAT', null, null, Context::getContext()->shop->id));
    }

    // Edit a template
    private function viewEdit()
    {
        if (Tools::getValue('viewedit') == 1) {
            $this->context->smarty->assign('viewedit', 1);
            $this->context->smarty->assign('edittpl', Tools::getValue('tpl'));
            $this->context->smarty->assign('viewedit', 1);

            $editor = TemplateController::getEditor(Tools::getValue('tpl'));
            $this->context->smarty->assign('modelFile', '../../../model/' . $editor[0]['id_model'] . '_form_edit.tpl');

            $this->context->smarty->assign('tplDetails', $editor);
            $this->context->smarty->assign('tplColors', TemplateController::getEditorColors(Tools::getValue('tpl')));
            $this->context->smarty->assign('tplFields', TemplateController::getEditorFields(Tools::getValue('tpl')));
        } else {
            $this->context->smarty->assign('viewedit', 0);
        }
    }

    private function initEdit($id_lang)
    {
        $reminders  = ReminderController::getRemindersByLanguage($id_lang, $this->context->shop->id);
        if (!$reminders) {
            $this->context->smarty->assign('editor', 0);
            return false;
        }
        $this->context->smarty->assign('editor', 1);

        if ($reminders[0]['tpl_same']) {
            $this->context->smarty->assign('id_tpl_same', $reminders[0]['id_template']);
        }

        $x = 1;
        foreach ($reminders as $reminder) {
            $template_id    = $reminder['id_template'];
            $model_id       = TemplateController::getModelByTemplate($template_id);
            $this->context->smarty->assign('template_file_' . $x, $template_id . '.html');
            $content = Tools::file_get_contents(dirname(__FILE__).'/tpls/'.$template_id.'.html');

            // Clean old templates from onChange attributes
            $content = preg_replace('/onchange=\"([^"]*)\"/', '', $content);
            if (!Validate::isCleanHtml($content, true)) {
                $content = '';
            }

            if (preg_match_all('/%(DISCOUNT_VALUE|DISCOUNT_CODE|DISCOUNT_VALID_DAY|DISCOUNT_VALID_MONTH|DISCOUNT_VALID_YEAR)%/', $content, $matches)) {
                $this->context->smarty->assign('discount_tags_alert', implode(', ', $matches[0]));
            }

            $this->context->smarty->assign('template_content_' . $x, $content);
            $this->context->smarty->assign('edit_template_id' . $x, $template_id);
            $this->context->smarty->assign('edit_model_id' . $x, $model_id);
            $x++;
        }
    }

    private function getLang()
    {
        if (self::$lang_cache == null && !is_array(self::$lang_cache)) {
            self::$lang_cache = array();
            if ($languages = Language::getLanguages()) {
                foreach ($languages as $row) {
                    $exprow = explode(' (', $row['name']);
                    $subtitle = (isset($exprow[1]) ? trim(Tools::substr($exprow[1], 0, -1)) : '');
                    self::$lang_cache[$row['iso_code']] = array(
                                'title' => trim($exprow[0]),
                                'subtitle' => $subtitle
                        );
                }
                // Clean memory
                unset($row, $exprow, $result, $subtitle, $languages);
            }
        }
    }

    public static function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
    /**
    * Install Tab
    * @return boolean
    */
    private function installTab($class)
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $class;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Cart Abandonment';
        }
        unset($lang);
        $tab->id_parent = -1;
        $tab->module = $this->name;
        return $tab->add();
    }

    /**
    * Uninstall Tab
    * @return boolean
    */
    private function uninstallTab($class)
    {
        $id_tab = (int)Tab::getIdFromClassName($class);
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        } else {
            return false;
        }
    }
}
