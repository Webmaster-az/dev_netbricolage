<?php
/**
* Minimum and maximum unit quantity to purchase
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate
*  @copyright 2023 idnovate
*  @license   See above
*/

if (!defined('_PS_VERSION_'))
    exit;
if (!defined('_CAN_LOAD_FILES_')) {
    exit;
}

include_once(_PS_MODULE_DIR_.'minpurchase/classes/MinpurchaseConfiguration.php');

class MinPurchase extends Module
{
    private $errors = array();
    private $success;

    public function __construct()
    {
        $this->name = 'minpurchase';
        $this->tab = 'front_office_features';
        $this->version = '1.2.2';
        $this->author = 'idnovate';
        $this->module_key = '48f0751607181ae65d999cf1d471683c';
        $this->addons_id_product = '27632';
        $this->module_path = $this->_path;

        parent::__construct();

        $this->displayName = $this->l('Minimum and maximum purchase product quantity');
        $this->description = $this->l('Define the minimum and the maximum units to the products and set the multiple and/or the increment quantities to purchase');

        $this->tabs[] = array(
            'class_name' => 'AdminMinpurchase',
            'name' => $this->l('Admin Minpurchase'),
            'visible' => false
        );

        /* Backward compatibility */
        if (version_compare(_PS_VERSION_, '1.5', '<'))
            require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');
    }

    public function copyOverrideFolder()
    {
        if (!is_writable(_PS_MODULE_DIR_.$this->name)) {
            return false;
        }

        $override_folder_name = "override";

        if (version_compare(_PS_VERSION_, '8', '>=')) {
            $version = '8';
        } elseif (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $version = '17';
        } elseif (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $version = '16';
        } else {
            $version = '15';
        }

        $version_override_folder = _PS_MODULE_DIR_.$this->name.'/'.$override_folder_name.'_'.$version;
        $override_folder = _PS_MODULE_DIR_.$this->name.'/'.$override_folder_name;

        if (file_exists($override_folder) && is_dir($override_folder)) {
            $this->recursiveRmdir($override_folder);
        }

        if (is_dir($version_override_folder)) {
            $this->copyDir($version_override_folder, $override_folder);
        }

        return true;
    }

    protected function copyDir($src, $dst)
    {
        if (is_dir($src)) {
            $dir = opendir($src);
            @mkdir($dst);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($src.'/'.$file)) {
                        $this->copyDir($src.'/'.$file,$dst.'/'.$file);
                    } else {
                        copy($src.'/'.$file,$dst.'/'.$file);
                    }
                }
            }
            closedir($dir);
        }
    }

    protected function recursiveRmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir"){
                        $this->recursiveRmdir($dir."/".$object);
                    }else{
                        unlink($dir."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function reset()
    {
        if (!$this->uninstall(false))
            return false;
        if (!$this->install(false))
            return false;

        return true;
    }

    public function install()
    {
        if (!$this->copyOverrideFolder()) {
            return false;
        }

        if (!parent::install()
            || !$this->initSQL()
            || !$this->installTabs()
            || !$this->registerHook('displayProductPriceBlock')
            || !$this->registerHook('footer')
            || ((version_compare(_PS_VERSION_, '1.7', '>=') && !$this->registerHook('header')) ||
                (version_compare(_PS_VERSION_, '1.7', '>=') && !$this->registerHook('displayProductAdditionalInfo')))) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        return parent::uninstall()
            && $this->uninstallTabs()
            && $this->uninstallSQL();
    }

    public function hookDisplayHeader()
    {
        if ($this->checkRulesExist()) {
            $this->context->controller->addCSS($this->_path.'views/css/front.css');
        }

        if (Context::getContext()->controller->php_self == 'product' || Context::getContext()->controller->php_self == 'category') {
            if ($this->checkRulesExist()) {
                if (Context::getContext()->controller->php_self == 'product') {

                    if (version_compare(_PS_VERSION_, '1.6', '<')) {
                        return $this->display(__FILE__, 'views/templates/hook/combinations15.tpl');
                    } else if (version_compare(_PS_VERSION_, '1.7', '>=')) {
                        $this->context->controller->addJqueryPlugin('fancybox');
                        //return $this->display(__FILE__, 'views/templates/hook/setmin.tpl');
                    } else {
                        $this->context->controller->addJS($this->_path.'views/js/front.js');
                        return $this->display(__FILE__, 'views/templates/hook/combinations.tpl');
                    }
                }
            }
        }
    }

    public function hookDisplayFooter()
    {
        if ($this->checkRulesExist()) {
            $this->context->controller->addCSS($this->_path.'views/css/front.css');
        }

        if (Context::getContext()->controller->php_self == 'product' || Context::getContext()->controller->php_self == 'category') {
            if ($this->checkRulesExist()) {
                if (Context::getContext()->controller->php_self == 'product') {
                    if (version_compare(_PS_VERSION_, '1.6', '<')) {
                        return $this->display(__FILE__, 'views/templates/hook/combinations15.tpl');
                    } else if (version_compare(_PS_VERSION_, '1.7', '>=')) {
                        return $this->display(__FILE__, 'views/templates/hook/setmin.tpl');
                    } else {
                        $this->context->controller->addJS($this->_path.'views/js/front.js');
                        return $this->display(__FILE__, 'views/templates/hook/combinations.tpl');
                    }
                }
            }
        }
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        if ($this->checkRulesExist()) {
            $this->context->controller->addJqueryPlugin('fancybox');
            return $this->display(__FILE__, 'views/templates/hook/combinations17.tpl');
        }
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if (!Module::isEnabled('minpurchase')) {
            return;
        }

        include_once(_PS_MODULE_DIR_.'minpurchase/classes/MinpurchaseConfiguration.php');
        $objConfig = new MinpurchaseConfiguration();

        $id_product = 0;
        $id_product_attribute = 0;

        if (isset($params['product'])) {
            if (is_array($params['product'])) {
                $id_product = $params['product']['id_product'];
                if (isset($params['product']['id_product_attribute'])) {
                    $id_product_attribute = $params['product']['id_product_attribute'];
                } else if (isset($params['product']['cache_default_attribute'])) {
                    $id_product_attribute = $params['product']['cache_default_attribute'];
                }
            } else {
                $id_product = $params['product']->id;
                if (isset($params['product']->id_product_attribute)) {
                    $id_product_attribute = $params['product']->id_product_attribute;
                } else if (isset($params['product']->cache_default_attribute)) {
                    $id_product_attribute = $params['product']->cache_default_attribute;
                }
            }
        }

        if ($id_product) {
            $conf = $objConfig->getConfigurations($id_product, $id_product_attribute);
        }

        if (!empty($conf) && (isset($conf['show_text']) && $conf['show_text'])) {
            $quantityToCheck = 0;
            $id_customer = Context::getContext()->customer->id;
            $maximum_quantity = 0;

            if ($conf['max_qty_stock']) {
                $stock = StockAvailable::getQuantityAvailableByProduct($id_product, $id_product_attribute);
                if ($stock > 0) {
                    $maximum_quantity = $stock;
                }
            } else {
                $maximum_quantity = $conf['maximum_quantity'];
            }

            if ($maximum_quantity > 0 && $id_customer && ($conf['days'] || $conf['orders_date_from'] > 0 || $conf['orders_date_to'] > 0 || $conf['orders_period'])) {
                $orders = MinpurchaseConfiguration::getOrdersIdByDateAndState($conf, $id_customer);
                foreach ($orders as $order) {
                    $o = new Order($order);
                    foreach ($o->getProducts() as $p) {}
                    $quantityToCheck += $p['product_quantity'];
                }
            }
            $maxunits = $maximum_quantity - $quantityToCheck;
            
            if ($maxunits < 0 ) {
                $maxunits = 0;
            }
            if ($quantityToCheck == 0) {
                $maxunits = $maximum_quantity;
            }

            $this->context->smarty->assign(array(
                'text_min' => $this->l('Minimum purchase'),
                'text_max' => $this->l('Maximum purchase'),
                'text_mul' => $this->l('Multiple quantity'),
                'min' => $conf['minimum_quantity'],
                'mul' => $conf['multiple_qty'],
                'max' => $maxunits,
            ));

            if ($params['type'] == 'after_price') {
                if (version_compare(_PS_VERSION_, '1.7', '<')) {
                    return $this->display(__FILE__, 'views/templates/hook/front.tpl');
                } else {
                    return $this->display(__FILE__, 'views/templates/hook/front17.tpl');
                }
            }
        }
    }

    public function getContent()
    {
        $warnings_to_show = '';
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            if (Configuration::get('PS_DISABLE_NON_NATIVE_MODULE')) {
                $warnings_to_show = $warnings_to_show . $this->displayError($this->l('You have to disable the option Disable non native modules at ADVANCED PARAMETERS - PERFORMANCE'));
            }

            if (Configuration::get('PS_DISABLE_OVERRIDES')) {
                $warnings_to_show = $warnings_to_show . $this->displayError($this->l('You have to disable the option Disable all overrides at ADVANCED PARAMETERS - PERFORMANCE'));
            }
        }

        if (!empty($warnings_to_show)) {
            $this->context->smarty->assign(array(
                'performance_link' => $this->context->link->getAdminLink('AdminPerformance'),
            ));
            return $warnings_to_show . $this->display(__FILE__, 'views/templates/admin/admin_warnings.tpl');
        }

        // check if the tab was not created in the installation
        foreach ($this->tabs as $myTab) {
            $id_tab = Tab::getIdFromClassName($myTab['class_name']);
            if (!$id_tab) {
                $this->addTab($myTab);
            }
        }

        return Tools::redirectAdmin('index.php?controller=' . $this->tabs[0]['class_name'] . '&token=' . Tools::getAdminTokenLite($this->tabs[0]['class_name']));
    }

    public function installTabs()
    {
        if (version_compare(_PS_VERSION_, '1.7.1', '>=')) {
            return true;
        }

        foreach ($this->tabs as $myTab) {
            $this->addTab($myTab);
        }
        return true;
    }

    public function addTab($myTab)
    {
        $id_tab = Tab::getIdFromClassName($myTab['class_name']);
        if (!$id_tab) {
            $tab = new Tab();
            $tab->class_name = $myTab['class_name'];
            $tab->module = $this->name;

            if (isset($myTab['parent_class_name'])) {
                $tab->id_parent = Tab::getIdFromClassName($myTab['parent_class_name']);
            } else {
                $tab->id_parent = -1;
            }

            $languages = Language::getLanguages(false);
            foreach ($languages as $lang) {
                $tab->name[$lang['id_lang']] = $myTab['name'];
            }

            $tab->add();
        }
    }

    public function uninstallTabs()
    {
        if (version_compare(_PS_VERSION_, '1.7.1', '>=')) {
            return true;
        }

        foreach ($this->tabs as $myTab) {
            $idTab = Tab::getIdFromClassName($myTab['class_name']);
            if ($idTab) {
                $tab = new Tab($idTab);
                $tab->delete();
            }
        }
        return true;
    }

    protected function initSQL()
    {
        Db::getInstance()->Execute('
            CREATE TABLE IF NOT EXISTS `'.pSQL(_DB_PREFIX_.$this->name).'_configuration` (
                `id_minpurchase_configuration` int(10) unsigned NOT NULL auto_increment,
                `name` VARCHAR(100) NULL,
                `minimum_quantity` int(10) NULL,
                `multiple` tinyint(1) unsigned,
                `multiple_qty` int(10) NULL,
                `increment` tinyint(1) unsigned,
                `increment_qty` int(10) NULL,
                `groups` TEXT NULL,
                `customers` TEXT NULL,
                `products` TEXT NULL,
                `countries` TEXT NULL,
                `zones` TEXT NULL,
                `categories` TEXT NULL,
                `manufacturers` TEXT NULL,
                `suppliers` TEXT NULL,
                `languages` TEXT NULL,
                `currencies` TEXT NULL,
                `features` TEXT NULL,
                `attributes` TEXT NULL,
                `filter_prices` tinyint(1) unsigned,
                `price_calculate` tinyint(1) unsigned,
                `min_price` decimal(10,2) NULL DEFAULT "0.000",
                `max_price` decimal(10,2) NULL DEFAULT "0.000",
                `filter_store` tinyint(1) unsigned,
                `filter_stock` tinyint(1) unsigned,
                `min_stock` int(10) NULL,
                `max_stock` int(10) NULL,
                `active` tinyint(1) unsigned NOT NULL DEFAULT "0",
                `priority` int(1) unsigned DEFAULT "0",
                `id_shop` tinyint(1) unsigned NOT NULL DEFAULT "0",
                `maximum_quantity` int(10) NULL,
                `separated` tinyint(1) unsigned NOT NULL DEFAULT "0",
                `show_text` tinyint(1) unsigned NOT NULL DEFAULT "0",
                `date_from` DATETIME,
                `date_to` DATETIME,
                `date_add` DATETIME,
                `date_upd` DATETIME,
                `minimum_amount` decimal(10,2) NULL DEFAULT "0.000",
                `maximum_amount` decimal(10,2) NULL DEFAULT "0.000",
                `grouped_by` tinyint(1) unsigned NULL,
                `schedule` TEXT NULL,
                `products_excluded` TEXT NULL,
                `customers_excluded` TEXT NULL,
                `days` int(10) unsigned NULL,
                `orders_date_from` DATETIME,
                `orders_date_to` DATETIME,
                `orders_period` tinyint(1) unsigned NULL,
                `order_states` TEXT NULL,
                `max_qty_stock` tinyint(1) unsigned NOT NULL DEFAULT "0",
                `filter_weight` tinyint(1) unsigned,
                `min_weight` decimal(10,3) NULL DEFAULT "0.000",
                `max_weight` decimal(10,3) NULL DEFAULT "0.000",
                `order_total_type` tinyint(1) unsigned,
            PRIMARY KEY (`id_minpurchase_configuration`),
            KEY `id_minpurchase_configuration` (`id_minpurchase_configuration`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;');

        return true;
    }

    protected function uninstallSQL()
    {
        return Db::getInstance()->Execute('DROP TABLE IF EXISTS `'.pSQL(_DB_PREFIX_.$this->name).'_configuration`');
    }

    protected function checkRulesExist()
    {
        $id_shop = Context::getContext()->shop->id;

        $query = '
                SELECT conf.* FROM `'._DB_PREFIX_.'minpurchase_configuration` conf WHERE conf.`id_shop` = '.(int)$id_shop.'
                AND conf.`active` = 1';
        $rules = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        return ($rules == false) ? false : true;
    }

    public function getMessageAvailable($name, $qty, $type, $mode = false, $difference = 0)
    {
        if ($difference < 0) {
            $difference = 0;
        }
        if ($mode != 'grouped') {
            if (version_compare(_PS_VERSION_, '1.7', '>=')) {
                if ($type != 'maxdays') {
                    $messages = array(
                        'min' => sprintf($this->l('The product %1s in your cart has a minimum purchase. You cannot proceed with your order until you add the minimum %2s units.'), $name, $qty),
                        'max' => sprintf($this->l('The product %1s in your cart has a maximum purchase. You cannot proceed with your order until you remove units to the maximum %2s.'), $name, $qty),
                        'minamount' => sprintf($this->l('The product %1s in your cart has a mininum amount to purchase. You cannot proceed with your order until reach %2s'), $name, $qty),
                        'maxamount' => sprintf($this->l('The product %1s in your cart has a maximum amount to purchase. You cannot proceed with your order until decrease to %2s'), $name, $qty),
                        'mult' => sprintf($this->l('The product %1s in your cart has a multiple amount to purchase. You cannot proceed with your order until you add a quantity multiple of %2s'), $name, $qty),
                    );
                } else {
                    //$messages = array('maxdays' => sprintf($this->l('You have purchased the %1s maximum products allowed in the last %2s days.'), $qty, $days));
                    $messages = array('maxdays' => sprintf($this->l('You have purchased the product %1s %2s times in the allowed period, can purchase %3s more.'), $name, $qty, $difference));

                }
            } else {
                $messages = array(
                    'min' => sprintf(Tools::displayError($this->l('The product %1s in your cart has a minimum purchase. You cannot proceed with your order until you add the minimum %2s units.')), $name, $qty),
                    'max' => sprintf(Tools::displayError($this->l('The product %1s in your cart has a maximum purchase. You cannot proceed with your order until you remove units to the maximum %2s.')), $name, $qty),
                    'minamount' => sprintf(Tools::displayError($this->l('The product %1s in your cart has a mininum amount to purchase. You cannot proceed with your order until reach %2s')), $name, $qty),
                    'maxamount' => sprintf(Tools::displayError($this->l('The product %1s in your cart has a maximum amount to purchase. You cannot proceed with your order until decrease to %2s')), $name, $qty),
                    'mult' => sprintf(Tools::displayError($this->l('The product %1s in your cart has a multiple amount to purchase. You cannot proceed with your order until you add a quantity multiple of %2s')), $name, $qty),
                    'maxdays' => sprintf(Tools::displayError($this->l('You have purchased the %1s maximum products allowed in the last %2s days.')), $qty, $days),
                );
            }
        } else {
            if (version_compare(_PS_VERSION_, '1.7', '>=')) {
                $messages = array(
                    'min' => sprintf($this->l('The products %1s in your cart have a minimum purchase. You cannot proceed with your order until total of units of these products are minimum %2s units.'), $name, $qty),
                    'max' => sprintf($this->l('The products %1s in your cart have a maximum purchase. You cannot proceed with your order until you remove units from these products to the maximum %2s units.'), $name, $qty),
                    'minamount' => sprintf($this->l('The products %1s in your cart have a mininum amount to purchase. You cannot proceed with your order until reach %2s'), $name, $qty),
                    'maxamount' => sprintf($this->l('The products %1s in your cart have a maximum amount to purchase. You cannot proceed with your order until decrease to %2s'), $name, $qty),
                    'mult' => sprintf($this->l('The products %1s in your cart has a multiple amount to purchase. You cannot proceed with your order until you add a quantity multiple of %2s'), $name, $qty),
                );
            } else {
                $messages = array(
                    'min' => sprintf(Tools::displayError($this->l('The products %1s in your cart have a minimum purchase. You cannot proceed with your order until total of units of these products are minimum %2s units.')), $name, $qty),
                    'max' => sprintf(Tools::displayError($this->l('The products %1s in your cart have a maximum purchase. You cannot proceed with your order until you remove units from these products to the maximum %2s units.')), $name, $qty),
                    'minamount' => sprintf(Tools::displayError($this->l('The products %1s in your cart have a mininum amount to purchase. You cannot proceed with your order until reach %2s')), $name, $qty),
                    'maxamount' => sprintf(Tools::displayError($this->l('The products %1s in your cart have a maximum amount to purchase. You cannot proceed with your order until decrease to %2s')), $name, $qty),
                    'mult' => sprintf(Tools::displayError($this->l('The products %1s in your cart has a multiple amount to purchase. You cannot proceed with your order until you add a quantity multiple of %2s')), $name, $qty),
                );
            }
        }
        return $messages[$type];
    }

    public function getMaxText($qty = 0, $name = '')
    {
        return sprintf($this->l('This product %1s in your cart has a maximum %2s units'), $name, $qty);
    }
}