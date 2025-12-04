<?php
/**
* Easy login as Customer
*
* NOTICE OF LICENSE
*
*    @author    Remy Combe <remy.combe@gmail.com>
*    @copyright 2013-2016 Remy Combe
*    @license   go to addons.prestastore.com (buy one module for one shop).
*    @for    PrestaShop version 1.7
*/

class EasyLogInAsCustomer extends Module
{
    public function __construct()
    {
        $this->ip = false;
        $this->name = 'easyloginascustomer';
        $this->version = '2.8.4';
        $this->confirmUninstall = $this->l('Click OK to delete Easy login as Customer.');
        $this->module_key = 'e956fe6d54938d82125d66264e79ecde';
        $this->need_instance = 1;
        $this->tab = 'front_office_features';
        $this->author = 'R.Combe';
        $this->bootstrap = true;
        $this->displayName = $this->l('Easy login as Customer');
        $this->description = $this->l('Allow you to log in as Customer in Front-Office.');
        $this->controllers = ($this->ip ? array('easyloginascustomer', 'connect') : array('easyloginascustomer'));
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->employee_data_url = '';

        parent::__construct();

        /* if multishop enabled, put more information in url */
        if (Shop::isFeatureActive()
            && isset($this->context->employee->id) && isset($this->context->employee->passwd)) {
            $this->employee_data_url = '&eid='.(int)$this->context->employee->id
                .'&epwd='.Tools::substr($this->context->employee->passwd, 0, 15);
        }
    }

    /**
    * @function install
    */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        Configuration::updateGlobalValue('LOGINASCUSTOMER_VERSION', $this->version); /* version */
        Configuration::updateGlobalValue('LOGINASCUSTOMER_FORCE_SETTINGS', 0);
        Configuration::updateGlobalValue('LOGINASCUSTOMER_SHOW_NAVIGATION', 1);
        Configuration::updateGlobalValue('LOGINASCUSTOMER_NEW_TAB', 1);
        Configuration::updateGlobalValue('LOGINASCUSTOMER_URL_PORT', 80);
        Configuration::updateGlobalValue('LOGINASCUSTOMER_REDIRECT', 0);
        Configuration::updateGlobalValue('LOGINASCUSTOMER_SEARCH_COMPANY', 0);

        /* Create SQL tables */
        Db::getInstance()->Execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'easyloginascustomer_history` (
                `id_easyloginascustomer_history` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_employee` int(10) unsigned NOT NULL,
                `id_customer` int(10) unsigned NOT NULL,
                `date_add` datetime DEFAULT NULL,
                PRIMARY KEY (`id_easyloginascustomer_history`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1'
        );
        Db::getInstance()->Execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'easyloginascustomer_configuration` (
                `id_employee` int(10) unsigned NOT NULL,
                `show_navigation` tinyint(1) NOT NULL,
                `open_new_tab` tinyint(1) NOT NULL,
                `date_add` datetime NOT NULL,
                `date_upd` datetime NOT NULL,
                PRIMARY KEY (`id_employee`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
        );

        /* Hooks */
        $hooks = array(
            'displayAdminCustomers',
            'displayBackOfficeTop',
            'actionAdminControllerSetMedia',
            'displayHeader',
            'displayAdminOrder',
            'displayNav'
        );
        foreach ($hooks as $h) {
            $this->registerHook($h);
        }

        /* Register AdminController */
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminEasyLoginAsCustomer';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'EasyLoginAsCustomer';
        }
        $tab->id_parent = -1;
        $tab->module = $this->name;
        $tab->add();

        /* redirect */
        $u = '&configure=easyloginascustomer&tab_module=front_office_features&module_name=easyloginascustomer&conf=12';
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').$u);
        return true;
    }

    /**
    * @function uninstall
    */
    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        /* Remove SQL tables */
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'easyloginascustomer_history`');
        Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'easyloginascustomer_configuration`');

        Configuration::deleteByName('LOGINASCUSTOMER_FORCE_SETTINGS');
        Configuration::deleteByName('LOGINASCUSTOMER_SHOW_NAVIGATION');
        Configuration::deleteByName('LOGINASCUSTOMER_NEW_TAB');
        Configuration::deleteByName('LOGINASCUSTOMER_URL_PORT');
        Configuration::deleteByName('LOGINASCUSTOMER_REDIRECT');
        Configuration::deleteByName('LOGINASCUSTOMER_SEARCH_COMPANY');

        return true;
    }

    /**
    * @function hookDisplayAdminCustomers
    */
    public function hookDisplayAdminCustomers()
    {
        $id_customer = Tools::getValue('id_customer');
        if ((int)$id_customer > 0
            && $this->isAllowed($this->context->employee->id_profile)) {
            $customer = Db::getInstance()->getRow(
                'SELECT *
                FROM `'._DB_PREFIX_.'customer`
                WHERE `id_customer` = '.(int)$id_customer
            );
            if ($customer) {
                $template = $this->getTemplateVar($customer);
                $this->context->smarty->assign($template);
                return $this->display(__FILE__, 'displayAdminCustomers.tpl');
            }
        }
    }

    /**
    * @function hookDisplayAdminOrder
    */
    public function hookDisplayAdminOrder()
    {
        $id_order = Tools::getValue('id_order');
        if ((int)$id_order > 0
            && $this->isAllowed($this->context->employee->id_profile)) {
            $customer = Db::getInstance()->getRow(
                'SELECT c.*
                FROM `'._DB_PREFIX_.'customer` c
                INNER JOIN `'._DB_PREFIX_.'orders` o
                ON (o.`id_customer` = c.`id_customer` AND o.`id_order` = '.(int)$id_order.')'
            );
            if ($customer) {
                $template = $this->getTemplateVar($customer);
                $this->context->smarty->assign($template);
                return $this->display(__FILE__, 'displayAdminOrders.tpl');
            }
        }
    }

    /**
    * @function hookActionAdminControllerSetMedia
    */
    public function hookActionAdminControllerSetMedia()
    {
        /* only display if no multishop or a shop is selected */
        if (!Shop::isFeatureActive()
            || (Shop::getContext() != Shop::CONTEXT_ALL
            && Shop::getContext() != Shop::CONTEXT_GROUP)) { /* Check if multishop active and shop selected */
            if ($this->isAllowed($this->context->employee->id_profile)) {
                $this->context->controller->addCSS($this->_path.'views/css/loginascustomer_admin.css');
                $this->context->controller->addJS($this->_path.'views/js/admin.js');
            }
        }
    }

    /**
    * @function hookDisplayHeader
    */
    public function hookDisplayHeader()
    {
        if ($this->ip && file_exists(dirname(__FILE__).'/ip.php') && !Tools::getIsset('ajax')) {
            $ip = array(); /* init */
            include(dirname(__FILE__).'/ip.php');
            if (count($ip) > 0 && isset($_SERVER['REMOTE_ADDR'])) {
                if (in_array($_SERVER['REMOTE_ADDR'], $ip)) {
                    $this->context->controller->addJS(
                        __PS_BASE_URI__.'modules/easyloginascustomer/views/js/connect.js'
                    );
                    $this->context->controller->addCSS($this->_path.'views/css/loginascustomer_front.css');
                }
            }
        }
        if ($this->context->cookie->loginascustomer_connected == $this->context->customer->email
            && isset($this->context->cookie->loginascustomer_connected)) {
            $name = $this->context->customer->firstname.' '.$this->context->customer->lastname;
            
            if (Configuration::getGlobalValue('LOGINASCUSTOMER_SEARCH_COMPANY')) {
                if ($this->context->customer->company != '') {
                    $name = $this->context->customer->company.' - '.$name;
                } else {
                    $company = Db::getInstance()->getValue(
                        'SELECT `company`
                        FROM `'._DB_PREFIX_.'address`
                        WHERE `id_customer` = '.(int)(int)$this->context->customer->id.'
                        AND `active` = 1 AND `deleted` = 0 AND `company` IS NOT NULL
                        ORDER BY`date_upd` DESC'
                    );
                    if ($company) {
                        $name = $company.' - '.$name;
                    }
                }
            }
            $smarty = array(
                'loginascustomer_name' => $name,
                'loginascustomer_email' => $this->context->customer->email,
                'loginascustomer_id' => (int)$this->context->customer->id
            );
            $this->context->smarty->assign($smarty);
            $this->context->controller->addCSS($this->_path.'views/css/loginascustomer_front.css');
            return $this->display(__FILE__, 'displayHeader.tpl');
        }
    }

    /**
    * @function hookDisplayNav
    */
    public function hookDisplayNav()
    {
        if ($this->ip && file_exists(dirname(__FILE__).'/ip.php') && !Tools::getIsset('ajax')) {
            $ip = array(); /* init */
            include(dirname(__FILE__).'/ip.php');
            if (count($ip) > 0 && isset($_SERVER['REMOTE_ADDR'])) {
                if (in_array($_SERVER['REMOTE_ADDR'], $ip)) {
                    if (Tools::getIsset('easy_token')
                        && isset($this->context->cookie->easyconnect)
                        && isset($_SERVER['REQUEST_METHOD'])
                        && $_SERVER['REQUEST_METHOD'] == 'POST') { /* if submitted */
                        $token = $this->context->cookie->easyconnect;
                    } else {
                        $token = uniqid(rand(), true);
                    }
                    if (isset($this->context->cookie->easyconnect)) {
                        $this->context->cookie->easyconnect = $token;
                    } else {
                        $this->context->cookie->__set('easyconnect', $token);
                    }
                    if (isset($this->context->cookie->easyconnect_time)) {
                        $this->context->cookie->easyconnect_time = time();
                    } else {
                        $this->context->cookie->__set('easyconnect_time', time());
                    }

                    /* Load history */
                    $history = Db::getInstance()->ExecuteS(
                        'SELECT c.`id_customer`, MAX(l.`date_add`),
                            IF (c.`company` IS NULL,
                                CONCAT(
                                    \'[\',
                                    c.`id_customer`,
                                    \'] \',
                                    c.`firstname`,
                                    \' \',
                                    c.`lastname`,
                                    \' - \',
                                    c.`email`
                                ),
                                CONCAT(
                                    \'[\',
                                    c.`id_customer`,
                                    \'] \',
                                    c.`company`,
                                    \' - \',
                                    c.`firstname`,
                                    \' \',
                                    c.`lastname`,
                                    \' - \',
                                    c.`email`
                                )
                            ) AS `name`
                        FROM `'._DB_PREFIX_.'customer` c
                        INNER JOIN `'._DB_PREFIX_.'easyloginascustomer_history` l ON
                            (l.`id_customer` = c.`id_customer` AND l.`id_employee` = 0)
                        GROUP BY c.`id_customer`
                        ORDER BY MAX(l.`date_add`) DESC
                        LIMIT 20'
                    );
                    $smarty = array(
                        'token' => $token,
                        'easy_url' => $this->context->link->getModuleLink('easyloginascustomer', 'connect'),
                        'easy_history' => $history
                    );
                    $this->context->smarty->assign($smarty);
                    return $this->display(__FILE__, 'displayNav.tpl');
                }
            }
        }
    }

    /**
    * @function hookDisplayBackOfficeTop
    */
    public function hookDisplayBackOfficeTop()
    {
        $shop_is_feature_active = Shop::isFeatureActive(); /* check if multishop enabled */

        /* only display if no multishop or a shop is selected */
        if (!$shop_is_feature_active
            || (Shop::getContext() != Shop::CONTEXT_ALL
            && Shop::getContext() != Shop::CONTEXT_GROUP)) { /* Check if multishop active and shop selected */
            if ($this->isAllowed($this->context->employee->id_profile)) {
                $new_tab = ($this->getConf('LOGINASCUSTOMER_NEW_TAB') ? ' target="_blank"' : '');

                /* Get token with this method because it doesn't work with
                Tools::getAdminTokenLite('AdminEasyLogInAsCustomer'); */
                $subject = $this->context->link->getAdminLink('AdminEasyLoginAsCustomer');
                $pattern = '/token=[0-9A-Za-z]*/'; //$pattern = '/id=[0-9]*/'; if it is only numeric.
                preg_match($pattern, $subject, $matches);
                $token = str_replace('token=', '', $matches[0]);
                //Tools::getAdminTokenLite('AdminEasyLogInAsCustomer');
                $r = 'fc=module&amp;module=easyloginascustomer&amp;controller=easyloginascustomer';
                $loginascustomer_url = $this->context->link->getPageLink('index.php', true, null, $r);
                $smarty = array(
                    'loginascustomer_url' => $this->addUrlPort($loginascustomer_url),
                    'loginascustomer_config_url' => $this->context->link->getAdminLink('AdminModules')
                    .'&configure=easyloginascustomer&tab_module=front_office_features&module_name=easyloginascustomer',
                    'loginascustomer_token' => $token,
                    'loginascustomer_employee_data_url' => $this->employee_data_url,
                    'loginascustomer_new_tab' => $new_tab
                );

                $this->context->smarty->assign($smarty);

                /* Auto load customer for AdminOrders and AdminCustomers */
                $id = false;
                if ($id_customer = Tools::getValue('id_customer')) {
                    $id = (int)Db::getInstance()->getValue(
                        'SELECT `id_customer`
                        FROM `'._DB_PREFIX_.'customer`
                        WHERE `id_customer` = '.(int)$id_customer.'
                        AND `id_shop` = '.(int)$this->context->shop->id
                    );
                } elseif ($id_order = Tools::getValue('id_order')) {
                    $id =(int)Db::getInstance()->getValue(
                        'SELECT c.`id_customer`
                        FROM `'._DB_PREFIX_.'customer` c
                        INNER JOIN `'._DB_PREFIX_.'orders` o
                        ON (o.`id_customer` = c.`id_customer` AND o.`id_order` = '.(int)$id_order.')'
                    );
                }

                /* Get company configuration */
                $company = Configuration::getGlobalValue('LOGINASCUSTOMER_SEARCH_COMPANY');

                /* Load history */
                $history = Db::getInstance()->ExecuteS(
                    'SELECT DISTINCT(c.`id_customer`),
                        c.`email`,
                        c.`firstname`,
                        c.`lastname`,
                        c.`passwd`,
                        c.`id_shop`,
                        c.`secure_key`,
                        c.`company` AS `customer_company`,
                        \'\' AS `company`
                    FROM `'._DB_PREFIX_.'customer` c
                    INNER JOIN `'._DB_PREFIX_.'easyloginascustomer_history` l ON
                        (l.`id_customer` = c.`id_customer`
                        AND l.`id_employee` = '.(int)$this->context->employee->id.')
                    WHERE c.`id_shop` = '.(int)$this->context->shop->id.'
                    '.($id ? ' AND c.`id_customer` != '.(int)$id : '').'
                    ORDER BY l.`date_add` DESC LIMIT 5'
                );

                /* Put email in first position */
                if ($id) {
                    $history = array_merge(
                        Db::getInstance()->ExecuteS(
                            'SELECT c.`id_customer`,
                                c.`email`,
                                c.`firstname`,
                                c.`lastname`,
                                c.`passwd`,
                                c.`id_shop`,
                                c.`secure_key`,
                                c.`company` AS `customer_company`
                            FROM `'._DB_PREFIX_.'customer` c
                            WHERE c.`id_customer` = '.(int)$id.'
                            AND c.`id_shop` = '.(int)$this->context->shop->id
                        ),
                        $history
                    );
                }

                /* Show history */
                if (count($history) > 0) {
                    $r = 'fc=module&amp;module=easyloginascustomer&amp;controller=easyloginascustomer';
                    $url_noshop = $this->context->link->getPageLink('index.php', true, null, $r);
                    foreach ($history as &$i) {
                        /* if multishop enabled, get customer shop url */
                        if ($shop_is_feature_active) {
                            $id_shop = (int)$i['id_shop'];
                            $url = $this->context->link->getPageLink('index.php', true, null, $r, false, $id_shop);
                        } else {
                            $url = $url_noshop;
                        }

                        /* Format URL with port if required */
                        $url = $this->addUrlPort($url);

                        $i['url'] = $url
                            .'&id='.(int)$i['id_customer']
                            .'&email='.$i['email']
                            .'&passwd='.$i['passwd']
                            .'&key='.Tools::substr($i['secure_key'], 0, 10)
                            .'&token='.Tools::getAdminTokenLite('AdminEasyLoginAsCustomer', $this->context)
                            .$this->employee_data_url;

                        if ($company) {
                            if ($i['customer_company'] == '') {
                                $company_address = Db::getInstance()->getValue(
                                    'SELECT `company`
                                    FROM `'._DB_PREFIX_.'address`
                                    WHERE `id_customer` = '.(int)$i['id_customer'].'
                                    AND `company` IS NOT NULL
                                    AND `active` = 1
                                    AND `deleted` = 0
                                    ORDER BY `date_upd` DESC'
                                );
                                if ($company_address) {
                                    $i['company'] = $company_address;
                                }
                            } else {
                                $i['company'] = $i['customer_company'];
                            }
                        }
                    }
                    $this->context->smarty->assign('loginascustomer_history', $history);
                }
                return $this->display(__FILE__, 'displayBackOfficeTop.tpl');
            }
        }
    }

    /**
    * @function getContent
    */
    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('submitLoginAsCustomer')) {
            if ((int)$this->context->employee->id_profile == 1 && Tools::getValue('LOGINASCUSTOMER_FORCE_SETTINGS')) {
                Configuration::updateGlobalValue(
                    'LOGINASCUSTOMER_SHOW_NAVIGATION',
                    (int)Tools::getValue('LOGINASCUSTOMER_SHOW_NAVIGATION')
                );
                Configuration::updateGlobalValue(
                    'LOGINASCUSTOMER_NEW_TAB',
                    (int)Tools::getValue('LOGINASCUSTOMER_NEW_TAB')
                );
            } elseif (!Configuration::getGlobalValue('LOGINASCUSTOMER_FORCE_SETTINGS')) {
                /* Update employee configuration because settings are not forced */
                $exists = Db::getInstance()->getValue(
                    'SELECT COUNT(`id_employee`)
                    FROM `'._DB_PREFIX_.'easyloginascustomer_configuration`
                    WHERE `id_employee` = '.(int)$this->context->employee->id
                );
                if (!$exists) { /* insert */
                    Db::getInstance()->Execute(
                        'INSERT INTO `'._DB_PREFIX_.'easyloginascustomer_configuration` (
                            `id_employee`,
                            `show_navigation`,
                            `open_new_tab`,
                            `date_add`,
                            `date_upd`
                        ) VALUES (
                            '.(int)$this->context->employee->id.',
                            1,
                            1,
                            NOW(),
                            NOW()
                        )'
                    );
                } else { /* update */
                    Db::getInstance()->Execute(
                        'UPDATE `'._DB_PREFIX_.'easyloginascustomer_configuration`
                        SET `show_navigation` = '.(int)Tools::getValue('LOGINASCUSTOMER_SHOW_NAVIGATION').',
                            `open_new_tab`= '.(int)Tools::getValue('LOGINASCUSTOMER_NEW_TAB').',
                            `date_upd` = NOW()
                        WHERE `id_employee` = '.(int)$this->context->employee->id
                    );
                }
            }

            /* Only SuperAdmin group */
            if ((int)$this->context->employee->id_profile == 1) {
                Configuration::updateGlobalValue(
                    'LOGINASCUSTOMER_FORCE_SETTINGS',
                    (int)Tools::getValue('LOGINASCUSTOMER_FORCE_SETTINGS')
                );
                Configuration::updateGlobalValue(
                    'LOGINASCUSTOMER_URL_PORT',
                    (int)Tools::getValue('LOGINASCUSTOMER_URL_PORT')
                );
                Configuration::updateGlobalValue(
                    'LOGINASCUSTOMER_REDIRECT',
                    (int)Tools::getValue('LOGINASCUSTOMER_REDIRECT')
                );
                Configuration::updateGlobalValue(
                    'LOGINASCUSTOMER_SEARCH_COMPANY',
                    (int)Tools::getValue('LOGINASCUSTOMER_SEARCH_COMPANY')
                );

                /* Hooks */
                $hooks = array(
                    'LOGINASCUSTOMER_HOOK_CUSTOMERS' => array('displayAdminCustomers'),
                    'LOGINASCUSTOMER_HOOK_NAV' => array('displayBackOfficeTop', 'actionAdminControllerSetMedia'),
                    'LOGINASCUSTOMER_HOOK_ORDERS' => array('displayAdminOrder')
                );
                foreach ($hooks as $name => $hook) {
                    foreach ($hook as $h) {
                        if ($this->isRegisteredInHook($h) && !Tools::getValue($name)) {
                            $this->unregisterHook(Hook::getIdByName($h));
                        } elseif (!$this->isRegisteredInHook($h) && Tools::getValue($name)) {
                            $this->registerHook($h);
                        }
                    }
                }
            }

            /* Redirect */
            $url = '&configure=easyloginascustomer'
                    .'&tab_module=front_office_features&module_name=easyloginascustomer&conf=6';
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').$url);
        }

        $output .= $this->renderForm(); /* Show form */

        if ((int)$this->context->employee->id_profile == 1) { /* Only for SuperAdmin group */
            $output .= $this->renderHistory();
        }

        return $output;
    }

    public function renderForm()
    {
        /* If normal employee (not superadmin) and configuration is forced, don't display settings */
        if ((int)$this->context->employee->id_profile > 1
            && Configuration::getGlobalValue('LOGINASCUSTOMER_FORCE_SETTINGS')) {
            return '';
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(),
                'submit' => array(
                    'title' => $this->l('Save')
                )
            )
        );

        $helper = new HelperForm();
        $helper->tpl_vars = array(
            'fields_value' => array(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id);

        $show_nav_hint = $this->l('Display the navigation bar in Front-Office when you are logged as a customer.');

        if ((int)$this->context->employee->id_profile == 1
            || !Configuration::getGlobalValue('LOGINASCUSTOMER_FORCE_SETTINGS')) {
            $fields_form['form']['input'] = array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Front-Office: show navigation bar'),
                    'name' => 'LOGINASCUSTOMER_SHOW_NAVIGATION',
                    'is_bool' => true,
                    'hint' => $show_nav_hint,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Back-Office: open window in new tab'),
                    'name' => 'LOGINASCUSTOMER_NEW_TAB',
                    'is_bool' => true,
                    'hint' => $this->l('Open window in new tab when you click on "Login as" link.'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                )
            );

            if (Configuration::getGlobalValue('LOGINASCUSTOMER_FORCE_SETTINGS')) { /* Load from database */
                $helper->tpl_vars['fields_value'] = array(
                    'LOGINASCUSTOMER_SHOW_NAVIGATION' => (Tools::isSubmit('submitLoginAsCustomer')
                        ? (bool)Tools::getValue('LOGINASCUSTOMER_SHOW_NAVIGATION')
                        : Configuration::getGlobalValue('LOGINASCUSTOMER_SHOW_NAVIGATION')),
                    'LOGINASCUSTOMER_NEW_TAB' => (Tools::isSubmit('submitLoginAsCustomer')
                        ? (bool)Tools::getValue('LOGINASCUSTOMER_NEW_TAB')
                        : Configuration::getGlobalValue('LOGINASCUSTOMER_NEW_TAB')));
            } else { /* Load from employee configuration */
                $exists = Db::getInstance()->getValue('
                    SELECT COUNT(`id_employee`)
                    FROM `'._DB_PREFIX_.'easyloginascustomer_configuration`
                    WHERE `id_employee` = '.(int)$this->context->employee->id);
                if (!$exists) { /* check if exists */
                    $conf = array('show_navigation' => 1, 'open_new_tab' => 1);
                } else { /* update */
                    $conf = Db::getInstance()->getRow('
                        SELECT *
                        FROM `'._DB_PREFIX_.'easyloginascustomer_configuration`
                        WHERE `id_employee` = '.(int)$this->context->employee->id);
                }

                /* Load values */
                $helper->tpl_vars['fields_value'] = array(
                    'LOGINASCUSTOMER_SHOW_NAVIGATION' => (bool)$conf['show_navigation'],
                    'LOGINASCUSTOMER_NEW_TAB' => (bool)$conf['open_new_tab']
                );
            }
        }

        /* Descriptions */
        $force_hint = $this->l('If Yes, employees won\'t be able to change the 2 previous settings for themselves.');
        $url_port_hint = $this->l('Port used to login as customer on Front-Office. Default: 80.');
        $url_port_hint .= $this->l('This value is not used when port is 80 (http) or 443 (https).');

        if ((int)$this->context->employee->id_profile == 1) {
            $fields_form['form']['input'] = array_merge($fields_form['form']['input'], array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Force this 2 previous settings for all employees'),
                    'name' => 'LOGINASCUSTOMER_FORCE_SETTINGS',
                    'is_bool' => true,
                    'hint' => $force_hint,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Redirect after login'),
                    'name' => 'LOGINASCUSTOMER_REDIRECT',
                    'is_bool' => true,
                    'hint' => $this->l('Choose where you are redirected after logged as customer'),
                    'options' => array(
                        'query' => array(
                            array(
                                 'id' => 1,
                                 'name' => $this->l('Home')
                            ),
                            array(
                                'id' => 0,
                                'name' => $this->l('My account')
                            ),
                            array(
                                'id' => 2,
                                'name' => $this->l('Checkout')
                            ),
                            array(
                                'id' => 3,
                                'name' => $this->l('Order history')
                            )
                         ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Back-Office: show Easy login as Customer icon in navigation bar'),
                    'name' => 'LOGINASCUSTOMER_HOOK_NAV',
                    'is_bool' => true,
                    'hint' => $this->l('Display an icon at the top of the page.'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Back-Office: show Easy login as Customer panel in AdminOrders page'),
                    'name' => 'LOGINASCUSTOMER_HOOK_ORDERS',
                    'is_bool' => true,
                    'hint' => $this->l('Display link in AdminOrders page to log on as the customer of the order.'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Back-Office: show Easy login as Customer panel in AdminCustomers page'),
                    'name' => 'LOGINASCUSTOMER_HOOK_CUSTOMERS',
                    'is_bool' => true,
                    'hint' => $this->l('Display link in AdminCustomers page to log on as the current customer.'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Front-Office: URL port'),
                    'name' => 'LOGINASCUSTOMER_URL_PORT',
                    'class' => 'fixed-width-xl',
                    'hint' => $url_port_hint
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Search by company name'),
                    'name' => 'LOGINASCUSTOMER_SEARCH_COMPANY',
                    'is_bool' => true,
                    'hint' => $this->l('Search in the company too and display company name in results.'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                )
            ));

            $helper->tpl_vars['fields_value'] = array_merge(
                $helper->tpl_vars['fields_value'],
                array(
                    'LOGINASCUSTOMER_FORCE_SETTINGS' => (Tools::isSubmit('submitLoginAsCustomer')
                        ? (bool)Tools::getValue('LOGINASCUSTOMER_FORCE_SETTINGS')
                        : Configuration::getGlobalValue('LOGINASCUSTOMER_FORCE_SETTINGS')
                    ),
                    'LOGINASCUSTOMER_HOOK_NAV' => (Tools::isSubmit('submitLoginAsCustomer')
                        ? (bool)Tools::getValue('LOGINASCUSTOMER_HOOK_NAV')
                        : (bool)$this->isRegisteredInHook('displayBackOfficeTop')
                    ),
                    'LOGINASCUSTOMER_HOOK_ORDERS' => (Tools::isSubmit('submitLoginAsCustomer')
                        ? (bool)Tools::getValue('LOGINASCUSTOMER_HOOK_ORDERS')
                        : (bool)$this->isRegisteredInHook('displayAdminOrder')
                    ),
                    'LOGINASCUSTOMER_HOOK_CUSTOMERS' => (Tools::isSubmit('submitLoginAsCustomer')
                        ? (bool)Tools::getValue('LOGINASCUSTOMER_HOOK_CUSTOMERS')
                        : (bool)$this->isRegisteredInHook('displayAdminCustomers')
                    ),
                    'LOGINASCUSTOMER_URL_PORT' => (Tools::isSubmit('submitLoginAsCustomer')
                        ? (bool)Tools::getValue('LOGINASCUSTOMER_URL_PORT')
                        : (int)Configuration::getGlobalValue('LOGINASCUSTOMER_URL_PORT')
                    ),
                    'LOGINASCUSTOMER_REDIRECT' => (Tools::isSubmit('submitLoginAsCustomer')
                        ? (int)Tools::getValue('LOGINASCUSTOMER_REDIRECT')
                        : (int)Configuration::getGlobalValue('LOGINASCUSTOMER_REDIRECT')
                    ),
                    'LOGINASCUSTOMER_SEARCH_COMPANY' => (Tools::isSubmit('submitLoginAsCustomer')
                        ? (int)Tools::getValue('LOGINASCUSTOMER_SEARCH_COMPANY')
                        : (int)Configuration::getGlobalValue('LOGINASCUSTOMER_SEARCH_COMPANY')
                    )
                )
            );
        }

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')
            ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitLoginAsCustomer';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        return $helper->generateForm(array($fields_form));
    }

    public function renderHistory()
    {
        $logs = Db::getInstance()->ExecuteS(
            'SELECT c.`id_customer`,
                c.`email`,
                CONCAT(c.`firstname`, \' \', c.`lastname`) AS `name`,
                l.`id_easyloginascustomer_history`,
                l.`date_add`,
                CONCAT(e.`firstname`, \' \', e.`lastname`, \' [\', e.`id_employee`, \']\') AS `employee`
            FROM `'._DB_PREFIX_.'customer` c
            INNER JOIN `'._DB_PREFIX_.'easyloginascustomer_history` l ON (l.`id_customer` = c.`id_customer`)
            LEFT JOIN `'._DB_PREFIX_.'employee` e ON (e.`id_employee` = l.`id_employee` AND l.`id_employee`)
            ORDER BY l.`date_add` DESC'
        );

        $fields_list = array(
            'id_customer' => array(
                'title' => $this->l('ID Customer'),
                'type' => 'text',
            ),
            'name' => array(
                'title' => $this->l('Customer'),
                'type' => 'text',
            ),
            'email' => array(
                'title' => $this->l('Email'),
                'type' => 'text',
            ),
            'employee' => array(
                'title' => $this->l('Employee'),
                'type' => 'text',
            ),
            'date_add' => array(
                'title' => $this->l('Date'),
                'type' => 'datetime'
            )
        );

        $helper = new HelperList();
        $helper->tpl_vars = array(
            'easyloginascustomer_credits' => true,
            'easyloginascustomer_version' => Configuration::getGlobalValue('LOGINASCUSTOMER_VERSION')
        );
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->actions = array('');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->listTotal = count($logs);
        $helper->identifier = 'id_easyloginascustomer_history';
        $helper->title = $this->l('History');
        $helper->table = 'easyloginascustomer_history';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        return $helper->generateList($logs, $fields_list);
    }

    /**
    * @function addUrlPort
    */
    public function addUrlPort($url)
    {
        $port = (int)Configuration::getGlobalValue('LOGINASCUSTOMER_URL_PORT');
        if ((int)$port != 80 && (int)$port > 0 && (int)$port != 443) {
            $elements = parse_url($url);
            $url = $elements['scheme'].'://'.$elements['host'].':'.(int)$port.$elements['path'].'?'.$elements['query'];
        }
        return $url;
    }

    public function getTemplateVar($customer)
    {
        $r = 'fc=module&amp;module=easyloginascustomer&amp;controller=easyloginascustomer'; /* request */
        $url = $this->context->link->getPageLink('index.php', true, null, $r, false, (int)$customer['id_shop'])
            .'&id='.(int)$customer['id_customer']
            .'&email='.$customer['email']
            .'&passwd='.$customer['passwd']
            .'&key='.Tools::substr($customer['secure_key'], 0, 10)
            .'&token='.Tools::getAdminTokenLite('AdminEasyLoginAsCustomer', $this->context)
            .$this->employee_data_url;

        /* Format URL with port if required */
        $url = $this->addUrlPort($url);

        return array(
            'loginascustomer_url' => $url,
            'loginascustomer_name' => $customer['firstname'].' '.$customer['lastname'],
            'loginascustomer_config_url' => $this->context->link->getAdminLink('AdminModules')
            .'&configure=easyloginascustomer&tab_module=front_office_features&module_name=easyloginascustomer',
            'loginascustomer_new_tab' => ($this->getConf('LOGINASCUSTOMER_NEW_TAB') ? ' target="_blank"' : '')
        );
    }

    /**
    * @function getConf
    * return 2 configuration parameters
    */
    public function getConf($param)
    {
        if (Configuration::getGlobalValue('LOGINASCUSTOMER_FORCE_SETTINGS')) {
            return (bool)Configuration::getGlobalValue($param);
        } else {
            $conf = Db::getInstance()->getRow(
                'SELECT *
                FROM `'._DB_PREFIX_.'easyloginascustomer_configuration`
                WHERE `id_employee` = '.(int)$this->context->employee->id
            );
            if ($conf) {
                if ($param == 'LOGINASCUSTOMER_NEW_TAB') {
                    return (bool)$conf['open_new_tab'];
                } else {
                    return (bool)$conf['show_navigation'];
                }
            } else { /* insert conf */
                Db::getInstance()->Execute(
                    'INSERT INTO `'._DB_PREFIX_.'easyloginascustomer_configuration` (
                        `id_employee`,
                        `show_navigation`,
                        `open_new_tab`,
                        `date_add`,
                        `date_upd`
                    ) VALUES (
                        '.(int)$this->context->employee->id.',
                        1,
                        1,
                        NOW(),
                        NOW()
                    )'
                );
            }
        }
        return true; /* default configuration */
    }

    /**
    * @function isAllowed
    */
    public function isAllowed($id_profile)
    {
        if ((int)$id_profile == 1) {
            return true;
        }
        return $this->getPermission('view');
    }
}
