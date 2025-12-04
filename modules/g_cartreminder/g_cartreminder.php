<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 *
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GcartreminderemailModel.php');
include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GpopupModel.php');
include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GpopupbarModel.php');
include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GnotificationModel.php');
class G_cartreminder extends Module
{
    public function __construct()
    {
        $this->name = "g_cartreminder";
        $this->tab  = "advertising_marketing";
        $this->version = "2.0.1";
        $this->author  = "Globo Jsc";
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);
        $this->module_key = '7ffa1e28efabd6f4b3d8930af2412a7b';

        $this->bootstrap = true;
        $this->db = Db::getInstance();
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
            parent::__construct();
        }
        $this->displayName = $this->l('Abandoned Cart Reminder 5 in 1');
        $this->description = $this->l('The module allows you send automatic or manual reminder email to customer who abandoned their cart. You can certainly capture additional sales by the remarketing method.');
        if (version_compare(_PS_VERSION_, '1.7.0.0', '<')){
            parent::__construct();
        }
    }
    public function install()
    {
        if (parent::install() == false
        || !$this->_createTab()
        || !$this->_creattables()
        || !$this->_inserttable()
        || !$this->installConfigdata()
        || !$this->addDataPopup()
        || !$this->registerHook('moduleRoutes')
        || !$this->registerHook('displayHeader')
        || !$this->registerHook('actionAdminControllerSetMedia')
        || !$this->registerHook('displayFooter')) {
            return false;
        }
        $linktab = Tools::getShopProtocol() . Tools::getServerName();
        @copy($linktab._PS_IMG_.Configuration::get('PS_FAVICON'), _PS_MODULE_DIR_.'g_cartreminder/views/img/defaulIcon.png');
        //Tools::redirectAdmin($this->context->link->getAdminLink('AdminGcartreminder'));
        return true;
    }
    public function uninstall()
    {
        if (!parent::uninstall()
            || !$this->_deleteTab()
            || !$this->_deletetable()
            || !$this->removeConfigdata()
            || !$this->unregisterHook('displayHeader')
            || !$this->unregisterHook('displayFooter')
            || !$this->unregisterHook('actionAdminControllerSetMedia')
            || !$this->unregisterHook('moduleRoutes')
            ) {
            return false;
        }
        return true;
    }
    public function addDataPopup(){
        $datas = array(1,2,3);
        foreach($datas as $data){
            $popupSetting = new GpopupModel();
            GpopupModel::addDefauld($popupSetting, $data);
        }
        return true;
    }
    /** creat table **/
    public function _creattables()
    {
        $res = (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gaddnewemail_template` (
                    `id_gaddnewemail_template` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `template_name` text NOT NULL,
                    `subject` text NULL,
                    `sample_email` varchar(255) NULL,
                    `email_html` text NULL,
                    `email_txt`  text NULL,
                    `datetimenow` datetime NULL,
                    PRIMARY KEY (`id_gaddnewemail_template`)
                ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
            ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gaddnewemail_template_lang` (
                    `id_gaddnewemail_template` int(10) unsigned NOT NULL,
                    `id_lang` int(10) unsigned NOT NULL,
                    `subjectlang`    text NULL,
                    `email_htmllang` text NULL,
                    `email_txtlang`  text NULL,
                    PRIMARY KEY (`id_gaddnewemail_template`,`id_lang`)
                ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
            ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gaddnewemail_template_shop` (
                `id_gaddnewemail_template` int(10) unsigned NOT NULL,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_gaddnewemail_template`,`id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gconditionandreminder` (
                `id_gconditionandreminder` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `position` int(10) unsigned NOT NULL DEFAULT \'0\',
                `active`   tinyint(1) unsigned NOT NULL,
                `rulename` text NOT NULL,
                `datefrom` datetime NULL,
                `dateto`   datetime  NULL,
                `coupon`   varchar(10)  NULL,
                `mincartamount` text  NULL,
                `maxcartamount` text  NULL,
                `custormmer` text NULL,
                `reminder`   text NOT null,
                `reminder_group` text null,
                `countreminder` int null,
                `cartrule` text null,
                `validity` int null,
                PRIMARY KEY (`id_gconditionandreminder`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gconditionandreminder_lang` (
                `id_gconditionandreminder` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_gconditionandreminder`,`id_lang`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gconditionandreminder_shop` (
                `id_gconditionandreminder` int(10) unsigned NOT NULL,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_gconditionandreminder`,`id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_cart` (
                `id_gabandoned_cart` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_cart` int NOT NULL,
                `id_reminder` text NULL,
                `count` int(10)  unsigned NOT NULL,
                `status_senmail` tinyint(1) unsigned NOT NULL,
                `data_status`  text null,
                `data_getcode` text null,
                `code` varchar(10)  NULL,
                `id_tempalte` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_gabandoned_cart`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_cart_lang` (
                `id_gabandoned_cart` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_gabandoned_cart`,`id_lang`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_cart_shop` (
                `id_gabandoned_cart` int(10) unsigned NOT NULL,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_gabandoned_cart`,`id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_cart_await` (
                `id_gabandoned_cart_await` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_reminder`  int NULL,
                `namereminder` text NULL,
                `id_cart`  int(10) unsigned NOT NULL,
                `count` int(10) unsigned NOT NULL,
                `time`  datetime NULL,
                `code`  varchar(10)  NULL,
                `nameemailtp` text NULL,
                PRIMARY KEY (`id_gabandoned_cart_await`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_cart_timesent` (
                `id_gabandoned_cart_timesent` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_cart`  int NULL,
                `timesent` int NULL,
                PRIMARY KEY (`id_gabandoned_cart_timesent`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_popup` (
                `id_gabandoned_popup` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `active` tinyint(1) unsigned NOT NULL,
                `day`    int(10) NULL,
                `hrs`    int(10) NULL,
                `maxwidth`  FLOAT NULL,
                `mincart`   text  NULL,
                `display`   int(10) NULL,
                `displayss` tinyint(1) unsigned NOT NULL,
                `sosicalfb` text NULL,
                `sosicaltw` text NULL,
                `sosicalgg` text NULL,
                `colorbackground` text NULL,
                `imgbackground`   text NULL,
                `autocode`      tinyint(1) unsigned NOT NULL,
                `autocodetype`  tinyint(1) NULL,
                `autocodevalue` int(10) NULL,
                `autocodeday`   int(10) NULL,
                `autocodeship`  tinyint(1) NULL,
                `autocodeid_currency`   int(10) NULL,
                `autocodetax`   tinyint(1) NULL,
                `code` text NULL,
                `time` datetime NOT NULL,
                `countdown` int(10) NULL,
                `reset_countdown` tinyint(1) NULL,
                `customcss` text NULL,
                PRIMARY KEY (`id_gabandoned_popup`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_popup_lang` (
                `id_gabandoned_popup` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                `name`    varchar(255)  NULL,
                `html`    text null,
                PRIMARY KEY (`id_gabandoned_popup`,`id_lang`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_popup_shop` (
                `id_gabandoned_popup` int(10) unsigned NOT NULL,
                `id_shop`             int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_gabandoned_popup`,`id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_bar` (
                `id_gabandoned_bar` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `active`   tinyint(1) unsigned NOT NULL,
                `position` tinyint(1) unsigned NOT NULL,
                `delay`  int(10) NULL,
                `textcolor`       text NULL,
                `backgroundcolor` text NULL,
                PRIMARY KEY (`id_gabandoned_bar`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_bar_lang` (
                `id_gabandoned_bar` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                `title`   varchar(255)  NULL,
                PRIMARY KEY (`id_gabandoned_bar`,`id_lang`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_bar_shop` (
                `id_gabandoned_bar` int(10) unsigned NOT NULL,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_gabandoned_bar`,`id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');

        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_notification` (
                `id_gabandoned_notification` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `setting_notification` text NULL,
                `setting_tab`          text NULL,
                PRIMARY KEY (`id_gabandoned_notification`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_notification_lang` (
                `id_gabandoned_notification` int(10) unsigned NOT NULL,
                `id_lang`            int(10) unsigned NOT NULL,
                `title_notification` varchar(255)  NULL,
                `message_notification` varchar(255)  NULL,
                `message_tab` varchar(255)  NULL,
                PRIMARY KEY (`id_gabandoned_notification`,`id_lang`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_notification_shop` (
                `id_gabandoned_notification` int(10) unsigned NOT NULL,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_gabandoned_notification`,`id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'g_PPtime` (
                `id_g_PPtime` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_customer` int NULL,
                `id_cart` int NULL,
                `time` int NULL,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_g_PPtime`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'g_notification_log` (
                `id_g_notification_log` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_cart` int NULL,
                `time` int NULL,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_g_notification_log`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
        $res &= (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'gabandoned_unsubscribe_email` (
                `id_gabandoned_unsubscribe_email` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_customer` int NULL,
                `email` varchar(255) NULL,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_gabandoned_unsubscribe_email`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;
        ');
		$shops = Shop::getContextListShopID();
		foreach ($shops as $shop_id)
		{
		    $shop_group_id = (int)Shop::getGroupFromShop((int)$shop_id, true);
			$res &= (bool)Configuration::updateValue('CONFIGGETCARTDAYS', 15, false, (int)$shop_group_id, (int)$shop_id);
        }
        return $res;
    }
    /** * insert template sample email **/
    public function _inserttable()
    {
        $subtemplates = $this->addtemplatedefault();
        foreach ($subtemplates as $key => $html) {
            $string = trim(preg_replace('/<[^>]*>/', '\n', $html));
            $string = trim($this->converthtmltxt($string), '\n');
            $obj = new GcartreminderemailModel();
            $obj->template_name = 'sample email ' . (int)$key . '';
            $obj->subject = '{customer_firstname}, sample email ' . (int)$key . '';
            $obj->sample_email = '';
            $obj->email_html = '';
            $obj->email_txt = '';
            $obj->datetimenow = ''.date('Y-m-d H:i:s').'';
            $langs = Language::getLanguages(false);
            foreach ($langs as $lang) {
                $obj->subjectlang[$lang['id_lang']] = '{customer_firstname}, sample email ' . (int)$key . '';
                $obj->email_htmllang[$lang['id_lang']] = ''.$html.'';
                $obj->email_txtlang[$lang['id_lang']] = ''.$string.'';
            }
            $obj->save();
        }
        return true;
    }
    /** * deleta table **/
    public function _deletetable()
    {
        $res = (bool)Db::getInstance()->execute('
                DROP TABLE IF EXISTS    `' . _DB_PREFIX_ . 'gaddnewemail_template`,
                                        `' . _DB_PREFIX_ . 'gaddnewemail_template_lang`,
                                        `' . _DB_PREFIX_ . 'gaddnewemail_template_shop`,
                                        `' . _DB_PREFIX_ . 'gconditionandreminder`,
                                        `' . _DB_PREFIX_ . 'gconditionandreminder_lang`,
                                        `' . _DB_PREFIX_ . 'gconditionandreminder_shop`,
                                        `' . _DB_PREFIX_ . 'gabandoned_cart`,
                                        `' . _DB_PREFIX_ . 'gabandoned_cart_lang`,
                                        `' . _DB_PREFIX_ . 'gabandoned_cart_shop`,
                                        `' . _DB_PREFIX_ . 'gabandoned_cart_await`,
                                        `' . _DB_PREFIX_ . 'gabandoned_cart_timesent`,
                                        `' . _DB_PREFIX_ . 'gabandoned_popup`,
                                        `' . _DB_PREFIX_ . 'gabandoned_popup_lang`,
                                        `' . _DB_PREFIX_ . 'gabandoned_popup_shop`,
                                        `' . _DB_PREFIX_ . 'gabandoned_bar`,
                                        `' . _DB_PREFIX_ . 'gabandoned_bar_lang`,
                                        `' . _DB_PREFIX_ . 'gabandoned_bar_shop`,
                                        `' . _DB_PREFIX_ . 'gabandoned_notification`,
                                        `' . _DB_PREFIX_ . 'gabandoned_notification_lang`,
                                        `' . _DB_PREFIX_ . 'gabandoned_notification_shop`,
                                        `' . _DB_PREFIX_ . 'g_PPtime`,
                                        `' . _DB_PREFIX_ . 'gabandoned_unsubscribe_email`,
                                        `' . _DB_PREFIX_ . 'g_notification_log`;
        ');
        return $res;
    }
    /** * install data config. **/
    public function installConfigdata() {
    $res = true;
    $shops = Shop::getContextListShopID();
    foreach ($shops as $shop_id)
    {
        $shop_group_id = (int)Shop::getGroupFromShop((int)$shop_id, true);
        $res &= (bool)Configuration::updateValue('GFROMTIME_SELECT_CHECK', null, false, (int)$shop_group_id, (int)$shop_id);
        $res &= (bool)Configuration::updateValue('GTOTIME_SELECT_CHECK', null, false, (int)$shop_group_id, (int)$shop_id);
        $res &= (bool)Configuration::updateValue('GTEMPLATE_ID', 1, false, (int)$shop_group_id, (int)$shop_id);
        $res &= (bool)Configuration::updateValue('CONFIGGETCARTDAYS', 15, false, (int)$shop_group_id, (int)$shop_id);
        $res &= (bool)Configuration::updateValue('CONFIGGETCARTHRS', null, false, (int)$shop_group_id, (int)$shop_id);
    }
    return (bool)$res;
    }
    public function removeConfigdata(){
        $res  = true;
        $res &= (bool)Configuration::deleteByName('GFROMTIME_SELECT_CHECK');
        $res &= (bool)Configuration::deleteByName('GTOTIME_SELECT_CHECK');
        $res &= (bool)Configuration::deleteByName('GTEMPLATE_ID');
        $res &= (bool)Configuration::deleteByName('CONFIGGETCARTDAYS');
        $res &= (bool)Configuration::deleteByName('CONFIGGETCARTHRS');
        $res &= (bool)Configuration::deleteByName('GCART_MANUALS');
        $res &= (bool)Configuration::deleteByName('GCART_JSREMINDERS');
        $res &= (bool)Configuration::deleteByName('GCART_CONDITIONS');
        $res &= (bool)Configuration::deleteByName('GCART_EXCLUDES');
        return (bool)$res;
    }
    /** * creat tab **/
    private function _createTab()
    {
        $res = true;
        $tabparent = "AdminGcartreminder";
        $id_tabinvoices = Tab::getIdFromClassName("AdminParentOrders");
        $id_parent = Tab::getIdFromClassName($tabparent);
        if (!$id_parent) {
            $tab = new Tab();
            $tab->active = "1";
            $tab->class_name = "AdminGcartreminder";
            $tab->name = array();
            foreach (Language::getLanguages(false) as $lang) {
                $tab->name[$lang["id_lang"]] = $this->l('Abandoned Cart Reminder');
            }
            $tab->id_parent = $id_tabinvoices;
            $tab->module = $this->name;
            $res &= $tab->add();
            $id_parent = $tab->id;
        }
        $subtabs = array(
            array('class' => 'AdminGdashboard', 'name' => $this->l('Dashboard')),
            array('class' => 'AdminGsetting', 'name'   => $this->l('Setting')),
            array('class' => 'AdminGcartremindercondreminder', 'name' => $this->l('Email Reminder')),
            array('class' => 'AdminGpopup', 'name'                    => $this->l('Popup Reminder')),
            array('class' => 'AdminGpopupbar', 'name'                 => $this->l('Popup Bar')),
            array('class' => 'AdminGnotification', 'name'             => $this->l('Browser Notification')),
            array('class' => 'AdminGcartreminderemail', 'name'        => $this->l('Email Templates')),
            array('class' => 'AdminGcartreminderabadonedcart', 'name' => $this->l('Abandoned Cart')),
            array('class' => 'AdminGcartremindermanual', 'name'       => $this->l('Manual')),
            array('class' => 'AdminGcartreminderhelp', 'name'         => $this->l('Help')));
            
        foreach ($subtabs as $subtab) {
            $idtab = Tab::getIdFromClassName($subtab['class']);
            if (!$idtab) {
                $tab = new Tab();
                $tab->active = "0";
                $tab->class_name = $subtab['class'];
                $tab->name = array();
                foreach (Language::getLanguages(false) as $lang) {
                    $tab->name[$lang["id_lang"]] = $subtab['name'];
                }
                $tab->id_parent = $id_tabinvoices;
                $tab->module = $this->name;
                $res &= $tab->add();
            }
        }
        return $res;
    }
    /** * deleta tab **/
    private function _deleteTab()
    {
        $id_tabs = array(
            'AdminGcartreminder',
            'AdminGdashboard',
            'AdminGsetting',
            'AdminGcartreminderemail',
            'AdminGcartremindercondreminder',
            'AdminGpopup',
            'AdminGpopupbar',
            'AdminGnotification',
            'AdminGcartreminderabadonedcart',
            'AdminGcartremindermanual',
            'AdminGcartreminderhelp');
        foreach ($id_tabs as $id_tab) {
            $idtab = Tab::getIdFromClassName($id_tab);
            $tab = new Tab((int)$idtab);
            $parentTabID = $tab->id_parent;
            $tab->delete();
            $tabCount = Tab::getNbTabs((int)$parentTabID);
            if ($tabCount == 0) {
                $parentTab = new Tab((int)$parentTabID);
                $parentTab->delete();
            }
        }
        return true;
    }
    /** * hook header tab. **/
    public function hookdisplayHeader(& $params)
    {
        $this->context->controller->addCSS($this->_path . 'views/css/front/popup/popup.css');
        $this->context->controller->addJS(_MODULE_DIR_.$this->name.'/views/js/front/popup/showpopup.js');
        $this->context->controller->addJS(_MODULE_DIR_.$this->name.'/views/js/front/notification/favico.js');
        $this->context->controller->addJS(_MODULE_DIR_.$this->name.'/views/js/front/notification/jquery.tabalert.js');
        $this->context->controller->addJS(_MODULE_DIR_.$this->name.'/views/js/front/notification/notification.js');
    }
    /** * hook header tab. **/
    public function hookdisplayFooter(& $params)
    {
        $html    = '';
        $link    = new link();
        $id_lang = (int)$this->context->language->id;
        $id_cart = (int)$this->context->cart->id;
        $id_shop = (int)$this->context->shop->id;

        $objnotification = new GnotificationModel(1, $id_lang, $id_shop);
        if ($id_cart > 0 && Validate::isLoadedObject($objnotification)) {
            $gsetting_shownotification = Tools::jsonDecode($objnotification->setting_notification, true);
            $gsetting_tab = Tools::jsonDecode($objnotification->setting_tab, true);
            if (isset($gsetting_shownotification['notification_off']) && $gsetting_shownotification['notification_off'] == 1) {
                if(!$this->context->cart->id)
                  $CartTotal=0;
                else
                  $CartTotal=(int)Cart::getNbProducts((int)$this->context->cart->id);
                $this->context->smarty->assign(
                    array(
                        'g_timenow' => time(),
                        'g_ndatecart'=> ((isset($this->context->cart) && Validate::isLoadedObject($this->context->cart)) ? strtotime($this->context->cart->date_add) : time()) ,
                        'g_delay_notification'=>(isset($gsetting_shownotification['delay_notification']) ? (int)$gsetting_shownotification['delay_notification'] : 0),
                        'g_icon_notification'=>(isset($gsetting_shownotification['img_icon']) ? $this->context->shop->getBaseURL().'modules/g_cartreminder/image/browser/'.$gsetting_shownotification['img_icon'] : ''),
                        'message_tab'          => $objnotification->message_tab,
                        'objnotifications'     => $objnotification,
                        'CartTotal'            => $CartTotal,
                        'TabEnable'            => $gsetting_tab['tabs_for'],
                        'id_cart'=>$id_cart,
                        'g_module_url'=> $this->context->shop->getBaseURL().'modules/g_cartreminder/',
                    ));
                $this->context->controller->addJS(_MODULE_DIR_.$this->name.'/views/js/front/notification/notification.js');
                $html .= $this->display(__FILE__, 'views/templates/front/notification/notification.tpl');
            }
        }

        /* hien fix 28/02/2018 */
        if (!$this->context->cart->id) {
            return $html;
        }
        /* #hien fix 28/02/2018 */

        $carts   = new Cart($id_cart);
        $id_currency     = (int)$this->context->currency->id;
        $Protocol  = trim(Tools::getShopProtocol(), '/');
        $total     = (int)Cart::getNbProducts($id_cart);
        $customers = new Customer($carts->id_customer, $id_lang, $id_shop);
        $timenow   = (int)time();
        if ($total <= 0 && (int)Tools::getValue('idDemo') < 1) {
            return $html;
        } else {
            $this->context->smarty->assign(
                array(
                    'id_cart'=>$id_cart,
                    'g_module_url'  => $this->context->shop->getBaseURL().'modules/g_cartreminder/',
                    'gtotalCart'    => $total,
                    'g_timenow'     => time(),
                    'g_dateaddcart' => strtotime($carts->date_add),
                    'g_url'         => Tools::getShopProtocol() . Tools::getServerName() . __PS_BASE_URI__,
                    'link_cartstart'=> $Protocol .$link->getPageLink('order', null, $carts->id_lang, null, false, $carts->id_shop, true),
                    'link_shopstart'=> $Protocol .$link->getPageLink('order', null, $carts->id_lang, null, false, $carts->id_shop, true),
                )
            );
            $mincart          = $carts->getOrderTotal(true, Cart::ONLY_PRODUCTS);
            if ((int)Tools::getValue('idDemo') >= 1) {
                $popupSetting = new GpopupModel((int)Tools::getValue('idDemo'), $id_lang, $id_shop);
            } else {
                $id_popup     = GpopupModel::getIDMaxcart($mincart, $id_currency);
                $popupSetting = new GpopupModel((int)$id_popup, $id_lang, $id_shop);
            }
            if (Validate::isLoadedObject($popupSetting)) {
                $checkPPtime= $this->getTimedelay((int)$carts->id, $id_shop);
                $showPPtime = false;
                if (empty($checkPPtime)) {
                    $showPPtime = true;
                } elseif($checkPPtime['time'] <= $timenow){
                    $showPPtime = true;
                }
                $version = 'PS16';
                if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
                    $version = 'PS17';
                }
                $time_addcart = (int)strtotime($carts->date_add) + ((int)$popupSetting->day * 60 * 60) + ((int)$popupSetting->hrs  * 60);
                if (((int)$time_addcart <= (int)$timenow && $showPPtime == true)   || (int)Tools::getValue('idDemo') >= 1 ) {
                    $this->context->smarty->assign(
                        array(
                            'popupSetting'  => $popupSetting,
                            'name'          => 'popup',
                            'JSpopupSetting'=> Tools::jsonEncode(GpopupModel::getARRAYPP($popupSetting, Tools::getValue('idDemo'))),
                            'demoPP'        => (int)Tools::getValue('idDemo'),
                            'gid_cart'      => (int)$carts->id,
                            'gtoken'        => sha1(_COOKIE_KEY_.'g_cartreminder'),
                            'version'       => $version,
                        )
                    );
                    $htmlTotext = $this->ReplaceTextTohtml($this->display(__FILE__, 'views/templates/front/popup/popup.tpl'), $carts, $popupSetting, (int)Tools::getValue('idDemo'));
                    $html      .= $htmlTotext;
                }
            }
            $PPbar = new GpopupbarModel(1, $id_lang, $id_shop);
            if (Validate::isLoadedObject($PPbar)) {
                $time_addcartPPbar= (int)strtotime($carts->date_add) + ((int)$PPbar->delay * 60);
                if ($PPbar->active == 1 && $time_addcartPPbar <= $timenow ) {
                    $PPbartitle   = $this->Replacetext($PPbar->title, $total, $customers);
                    $this->context->smarty->assign(
                        array(
                            'PPbar'       => $PPbar,
                            'g_module_url'=> $this->context->shop->getBaseURL().'modules/g_cartreminder/',
                            'name'        => 'PPbar',
                            'PPbartitle'  => $PPbartitle,
                        )
                    );
                    $htmlPPbar= $this->display(__FILE__, 'views/templates/front/popup/popup.tpl');
                    $html    .= $htmlPPbar;
                }
            }
            return $html;
        }
    }
    public function hookModuleRoutes($route = '', $detail = array()) {
		$routes = array();
		$routes['module-g_cartreminder-gcartreminder'] = array(
			'controller' => 'gcartreminder',
			'rule' => 'gcartreminder/g{idDemo}.html',
			'keywords' => array(
				'idDemo' => array('regexp' => '[0-9]+', 'param' => 'idDemo'),
			),
			'params' => array(
				'fc' => 'module',
				'module' => 'g_cartreminder',
			)
		);
		return $routes;
	}
    public function ReplaceTextTohtml($html, $cart, $popupSetting, $demo) {
        if ($html == '') {
            return '';
        }
        $Protocol  = trim(Tools::getShopProtocol(), '/');
        $link      = new Link();
        $customers = new Customer($cart->id_customer);
        $html = str_replace("{customer_firstname}", $customers->firstname, $html);
        $html = str_replace("{customer_lastname}", $customers->lastname, $html);
        $html = str_replace("{cart_product}", $this->htmlProduct($cart->getProducts(true), (int)$cart->id_lang,$cart), $html);
        $html = str_replace("{cart_product_txt}", $this->converthtmltxt($this->htmlProduct($cart->getProducts(true), (int)$cart->id_lang,$cart), false), $html);
        $this->context->smarty->assign(array('name'=>'linkstart', 'link_shopstart' => $Protocol .$link->getPageLink("index", null, $cart->id_lang, null, false, $cart->id_shop, true)));
        $html = str_replace("{shop_link_start}", $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/linksendmail/html.tpl'), $html);
        $this->context->smarty->assign(array('name'=>'linkend'));
        $html = str_replace("{shop_link_end}", $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/linksendmail/html.tpl'), $html);
        $html = str_replace("{shop_link_url}", $Protocol .$link->getPageLink('index', null, $cart->id_lang, null, false, $cart->id_shop, true), $html);
        $this->context->smarty->assign(array('name'=>'linkcartstart', 'link_cartstart' => $Protocol .$link->getPageLink('order', null, $cart->id_lang, null, false, $cart->id_shop, true)));
        $html = str_replace("{cart_link_start}", $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/linksendmail/html.tpl'), $html);
        $this->context->smarty->assign(array('name'=>'linkend'));
        $html = str_replace("{cart_link_end}", $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/linksendmail/html.tpl'), $html);

        $html = str_replace("{cart_url}", $Protocol .$link->getPageLink('order', null, $cart->id_lang, "step=3", false, $cart->id_shop, true), $html);
        $html = str_replace("{cart_url_s1}", $Protocol .$link->getPageLink('order', null, $cart->id_lang, "step=1", false, $cart->id_shop, true), $html);
        $html = str_replace("{cart_url_s2}", $Protocol .$link->getPageLink('order', null, $cart->id_lang, "step=2", false, $cart->id_shop, true), $html);

        $html = str_replace("%7Bcart_url%7D", $Protocol .$link->getPageLink('order', null, $cart->id_lang, "step=3", false, $cart->id_shop, true), $html);
        $html = str_replace("%7Bcart_url_s1%7D", $Protocol .$link->getPageLink('order', null, $cart->id_lang, "step=1", false, $cart->id_shop, true), $html);
        $html = str_replace("%7Bcart_url_s2%7D", $Protocol .$link->getPageLink('order', null, $cart->id_lang, "step=2", false, $cart->id_shop, true), $html);


        $html = str_replace("{total_product}", Cart::getTotalCart($cart->id, true, Cart::BOTH_WITHOUT_SHIPPING), $html);
        $html = str_replace("{total_shipping}", Cart::getTotalCart($cart->id, true, Cart::ONLY_SHIPPING), $html);
        $html = str_replace("{total_price}", Cart::getTotalCart($cart->id, true), $html);
        $this->context->smarty->assign(array('name'=>'facebook', 'popupSetting'=>$popupSetting));
        $html = str_replace("{facebook}", $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/linksendmail/html.tpl'), $html);
        $this->context->smarty->assign(array('name'=>'twitter'));
        $html = str_replace("{twitter}", $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/linksendmail/html.tpl'), $html);
        $this->context->smarty->assign(array('name'=>'google'));
        $html = str_replace("{google}", $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/linksendmail/html.tpl'), $html);
        $this->context->smarty->assign(array('name'=>'countdown'));
        $html = str_replace("{countdown}", $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/linksendmail/html.tpl'), $html);
        $this->context->smarty->assign(array('name'=>'urlimage_product_incart', 'products'=>$cart->getProducts(true)));
        foreach($cart->getProducts(true) as $product) {
            $productObj = new Product((int)$product['id_product'], false, (int)$cart->id_lang, (int)$cart->id_shop);
            $id_img_pr = Product::getCover($productObj->id);
            $img = str_replace('http://', Tools::getShopProtocol(), Context::getContext()->link->getImageLink($productObj->link_rewrite, $id_img_pr['id_image']));
        }
        $html = str_replace("{urlimage_product_incart}", $img, $html);
        if ($popupSetting->autocode != 1) {
            $html      = str_replace("{voucher_code}", $popupSetting->code, $html);
            $idcarrule = CartRule::getIdByCode($popupSetting->code);
            $carrule   = new CartRule((int)$idcarrule);
            $html      = str_replace("{voucher_expirate_date}", $carrule->date_from, $html);
        } else {
            $gentCode  = GpopupModel::gentCode($cart->id, $popupSetting, $demo);
            $html      = str_replace("{voucher_code}", $gentCode['code'], $html);
            $html      = str_replace("{voucher_expirate_date}", $gentCode['date'], $html);
        }
        return $html;
    }
    public function Replacetext($string, $total, $customers) {
        if ($string == '') {
            return '';
        }
        $string = str_replace("{total_items}", $total, $string);
        $string = str_replace("{cart_link_start}", $this->getHTML('linkcartstart'), $string);
        $string = str_replace("{cart_link_end}", $this->getHTML('linkend'), $string);
        $string = str_replace("{customer_firstname}", $customers->firstname, $string);
        $string = str_replace("{customer_lastname}", $customers->lastname, $string);
        return $string;
    }
    /** * minify html **/
    public function addtemplatedefault()
    {
        $name_templates = array ('1'=>'defaulttemplate0', '2'=>'defaulttemplate1', '3'=>'defaulttemplate2', '4'=>'defaulttemplate3', '5'=>'defaulttemplate4');
        $templates = array();
        $linktab = Tools::getShopProtocol() . Tools::getServerName() . __PS_BASE_URI__;
        $this->context->smarty->assign('server_dir', $linktab);
        foreach ($name_templates as $key=>$name_template) {
            $email_temp = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/defaulttemplate/'.$name_template.'.tpl');
            $templates[$key] = $email_temp;//Tools::purifyHTML($email_temp, null, true);
        }
        return $templates;
    }
    /** * convert html ->txt in data **/
    public function converthtmltxt($string, $pp = true)
    {
        if ($pp == true) {
            $output = str_replace(array('\r\n', '\r'), '\n', $string);
            $lines = explode('\n', $output);
            $new_lines = array();
            foreach ($lines as $line) {
                $line = trim($line, '\n');
                $line = trim($line);
                if (!empty($line) && $line != ' ') {
                    $new_lines[] = trim($line);
                }
            }
            $string = trim(implode('\n\n', $new_lines), '\n');
        } else {
            $string = trim(preg_replace('/<[^>]*>/', '\n', $string));
            $output = str_replace(array('\r\n', '\r'), '\n', $string);
            $lines = explode('\n', $output);
            $new_lines = array();
            foreach ($lines as $line) {
                $line = trim($line, '\n');
                $line = trim($line);
                if (!empty($line) && $line != ' ') {
                    $new_lines[] = trim($line);
                }
            }
            $string = trim(implode('<br>', $new_lines), '<br>');
        }
        return $string;
    }
    public function htmlProduct($products, $id_lang,$cart = null)
    {
        $link          = new Link();
        $currency = null;
        if($cart !=null)
            $currency = new Currency($cart->id_currency);
        else $currency = new Currency($this->context->currency->id);
        if($products)
            foreach($products as &$product){
                if(isset($product['total_wt']))
                    $product['total_wt'] = Tools::displayPrice($product['total_wt'], $currency);
                if(isset($product['total']))
                    $product['total'] = Tools::displayPrice($product['total'], $currency);
            }
        $this->context->smarty->assign(array(
            'gproducts' => $products,
            'id_lang'  => $id_lang,
            'protocol' => Tools::getShopProtocol(),
            'links'    => $link,
            'name'     => true
         ));
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/defaulttemplate/product_template/productitem_0.tpl');
    }
    public function getHTML($name){
        $this->context->smarty->assign(array('name'=>$name));
        $html  = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/linksendmail/html.tpl');
        return $html;
    }
    public function getTimedelay($id_cart, $id_shop) {
        return Db::getInstance()->getRow(
            'SELECT P.* FROM `' . _DB_PREFIX_ . 'g_PPtime` P
            WHERE P.`id_cart` = '.(int)$id_cart.' AND P.`id_shop`=' . (int)$id_shop.'
            ORDER BY P.`id_g_PPtime` DESC'
        );
    }
    public function hookActionAdminControllerSetMedia() {
        $controller = Tools::getValue('controller');
        if ($controller == 'AdminGdashboard' 
        || $controller == 'AdminGsetting' 
        || $controller == 'AdminGcartremindercondreminder' 
        || $controller == 'AdminGpopup' 
        || $controller == 'AdminGpopupbar' 
        || $controller == 'AdminGnotification' 
        || $controller == 'AdminGcartreminderemail' 
        || $controller == 'AdminGcartreminderabadonedcart'
        || $controller == 'AdminGcartremindermanual'
        || $controller == 'AdminGcartreminderhelp') {
            $this->context->controller->addJqueryPlugin('colorpicker');
            $this->context->controller->addJS(_PS_MODULE_DIR_.$this->name.'/views/js/admin/d3.v3.min.js');
            $this->context->controller->addJS(_PS_MODULE_DIR_.$this->name.'/views/js/admin/nv.d3.min.js');
            $this->context->controller->addCSS('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
            $this->context->controller->addJS(_PS_MODULE_DIR_.'g_cartreminder/views/js/admin/gcartreminder.js');
            $this->context->controller->addJS(_PS_MODULE_DIR_ . $this->name . '/views/js/admin/activeTab.js');
            $this->context->controller->addCSS(_PS_MODULE_DIR_ . $this->name . '/views/css/admin/gcartreminder.css');
            $this->context->controller->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
            $this->context->controller->addJS(_PS_JS_DIR_.'admin/tinymce.inc.js');
        }
        if($controller == 'AdminGpopup'){
            $this->context->controller->addCSS(_PS_MODULE_DIR_ . $this->name . '/views/css/admin/slideshow/slidershowemail.css');
            $this->context->controller->addCSS(_PS_MODULE_DIR_ . $this->name . '/views/css/admin/slideshow/slick.css');
            $this->context->controller->addCSS(_PS_MODULE_DIR_ . $this->name . '/views/css/admin/slideshow/slick-theme.css');
            $this->context->controller->addJS(_PS_MODULE_DIR_ . $this->name . '/views/js/admin/popup/slick.min.js');
            $this->context->controller->addJS(_PS_MODULE_DIR_ . $this->name . '/views/js/admin/popup/gpopup.js');
        }elseif($controller == 'AdminGnotification'){
            $this->context->controller->addJS(_PS_MODULE_DIR_ . $this->name . '/views/js/admin/browser/gnotification.js');
            $this->context->controller->addCSS(_PS_MODULE_DIR_ . $this->name . '/views/css/admin/browser/gnotication.css');
        }elseif($controller == 'AdminGpopupbar'){
            $this->context->controller->addJS(_PS_MODULE_DIR_ . $this->name . '/views/js/admin/popup/bar.js');
        }elseif($controller == 'AdminGcartreminderemail'){
            $this->context->controller->addCSS(_PS_MODULE_DIR_ . $this->name . '/views/css/admin/slideshow/slick.css');
            $this->context->controller->addCSS(_PS_MODULE_DIR_ . $this->name . '/views/css/admin/slideshow/slick-theme.css');
            $this->context->controller->addCSS(_PS_MODULE_DIR_ . $this->name . '/views/css/admin/slideshow/slidershowemail.css');
            $this->context->controller->addJS(_PS_MODULE_DIR_ . $this->name . '/views/js/admin/popup/slick.min.js');
            $this->context->controller->addJS(_PS_MODULE_DIR_ . $this->name . '/views/js/admin/eemailtemplate/email.js');
        }elseif($controller == 'AdminGcartreminderabadonedcart'){
            $this->context->controller->addJS(_PS_MODULE_DIR_ . $this->name . '/views/js/admin/gabadonecart.js');
        }
        
    }
    /** getCOntent. **/
    public function getContent() {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminGcartreminder'));
    }
    public  function getShoppingCartByReminder($manuals_rules, $count=false, $check=true,$condition_group=array(), $checknotin=array(), $p=1, $n= 20, $limit=true, $auto=false, $id_shop=0, $id_shop_group=0)
    {
        if ($count) {
            $sql = 'SELECT COUNT(distinct a.`id_cart`) as number';
        } else {
            $sql = 'SELECT distinct a.*, CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) `customer`,
            a.`id_cart` total, a.`id_cart` totalproduct,
            a.`id_cart` log,
            abd.`id_reminder` nameabd,
            abd.`count` severaltimes,
            abd.`data_status` datacfigsend,
            abd.`data_getcode` datacfigcode, c.`email` email,
            nl.`nl_total`,
            IF (IFNULL(o.`id_order`, \'' . $this->l('Non ordered') . '\') = \'' . $this->l('Non ordered') . '\', IF(TIME_TO_SEC(TIMEDIFF(\'' . pSQL(date('Y-m-d H:i:00', time())) . '\', a.`date_add`)) > 86400, \'' . $this->l('Abandoned cart') . '\', \'' . $this->l('Non ordered') . '\'), o.id_order) AS status,
            IF(o.id_order, 1, 0) badge_success,
            IF(o.id_order, 0, 1) badge_danger,
            IF(co.id_guest, 1, 0) id_guest,
            IF (IFNULL(o.id_order, "Send Mail") = "Send Mail", IF(a.id_customer , "Send Mail", ""), a.id_cart) AS noneorder';
        }
        $sql .= ' FROM `' . _DB_PREFIX_ . 'cart` AS a 
        LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (c.id_customer = a.id_customer)
        LEFT JOIN `' . _DB_PREFIX_ . 'currency` cu ON (cu.id_currency = a.id_currency)
        LEFT JOIN `' . _DB_PREFIX_ . 'orders` o ON (o.id_cart = a.id_cart)
        LEFT JOIN `' . _DB_PREFIX_ . 'gabandoned_cart` abd ON (abd.id_cart = a.id_cart)
        LEFT JOIN `' . _DB_PREFIX_ . 'cart_product` cartproduct ON (cartproduct.id_cart = a.id_cart)
        LEFT JOIN (SELECT id_cart,COUNT(id_g_notification_log) as nl_total FROM `' . _DB_PREFIX_ . 'g_notification_log` GROUP BY id_cart) nl ON(nl.id_cart = a.id_cart)  
        LEFT JOIN `' . _DB_PREFIX_ . 'connections` co ON (a.id_guest = co.id_guest AND TIME_TO_SEC(TIMEDIFF(\'' . pSQL(date('Y-m-d H:i:00', time())) . '\', co.`date_add`)) < 1800)';
        $sql .= ' WHERE a.`id_customer` != 0 AND a.`id_cart` IN (SELECT `id_cart` FROM `' . _DB_PREFIX_ . 'cart_product`) AND a.`id_cart` NOT IN (SELECT `id_cart` FROM `' . _DB_PREFIX_ . 'orders`)';
        if ($manuals_rules && ($manuals_rules['datefrom'] || $manuals_rules['dateto'])) {
            if (trim($manuals_rules['datefrom']) !='' && trim($manuals_rules['datefrom']) !='0000-00-00 00:00:00') {
                $sql .= ' AND a.`date_add` >= "'.date('Y-m-d H:i:s', strtotime($manuals_rules['datefrom'])).'"';
            }
            if (trim($manuals_rules['dateto']) !='' && trim($manuals_rules['dateto']) !='0000-00-00 00:00:00') {
                $sql .= ' AND a.`date_add` <= "'.date('Y-m-d H:i:s', strtotime($manuals_rules['dateto'])).'"';
            }
        }
        if (isset($manuals_rules['custormmer'])) {
            $custormmer = implode(',',$manuals_rules['custormmer']);
            $sql .= ' AND a.`id_customer` IN (SELECT distinct `id_customer` FROM `' . _DB_PREFIX_ . 'customer_group` WHERE id_group IN ('.pSQL($custormmer).'))';
        }

        if (isset($condition_group) && count($condition_group) && $check) {
            $sql .= ' AND (';
                foreach ($condition_group as $key=>$condition_groups) {
                    if ($key) {
                        $sql .= ' OR'; 
                    }
                    $sql .= ' ( 1'; 
                    if ($condition_groups) {
                        foreach ($condition_groups as $condition_group_rule) {
                            if (isset($condition_group_rule['type'])) {
                                switch ($condition_group_rule['type']) {
                                    case 'cart_products':
                                        if (isset($condition_group_rule['value'])) {
                                            $value = implode(',', $condition_group_rule['value']);
                                            $sql .= ' AND a.`id_cart` IN (SELECT distinct `id_cart` FROM `' . _DB_PREFIX_ . 'cart_product` WHERE `id_product` IN ('.pSQL($value).'))';
                                        }
                                        break;
                                    case 'cart_totalincart':
                                        $value = 0;
                                        if (!isset($condition_group_rule['value'][0])) {
                                            $value = (int)$condition_group_rule['value'][0];
                                        }
                                        $sql .= ' AND  (SELECT COUNT(`id_product`) FROM `' . _DB_PREFIX_ . 'cart_product` WHERE `id_cart`= a.`id_cart`) '.pSQL($condition_group_rule['reminder']).(int)$value.'';
                                        break;
                                    case 'cart_stockproduct':
                                        $value = 0;
                                        if (isset($condition_group_rule['value'][0])) {
                                            $value = (int)$condition_group_rule['value'][0];
                                        }
                                        $sql .= ' AND a.`id_cart` NOT IN (SELECT DISTINCT `id_cart` 
                                        FROM `' . _DB_PREFIX_ . 'cart_product` c_pro
                                        LEFT JOIN `' . _DB_PREFIX_ . 'stock_available` stock ON (c_pro.`id_product` = stock.`id_product` AND c_pro.`id_product_attribute` = stock.`id_product_attribute` AND stock.`quantity` '.pSQL($condition_group_rule['reminder']).(int)$value.')
                                        WHERE stock.`quantity`  IS  NULL 
                                        ORDER BY c_pro.`id_cart` ASC)';
                                        break;
                                    case 'cart_stockproducts':
                                        $value = 0;
                                        if (isset($condition_group_rule['value'][0])) {
                                            $value = (int)$condition_group_rule['value'][0];
                                        }
                                        $sql .= ' AND (SELECT SUM(stock.`quantity`) 
                                        FROM `' . _DB_PREFIX_ . 'cart_product` c_pro
                                        LEFT JOIN `' . _DB_PREFIX_ . 'stock_available` stock ON (c_pro.`id_product` = stock.`id_product` AND c_pro.`id_product_attribute` = stock.`id_product_attribute`)
                                        WHERE stock.`quantity`  IS NOT  NULL 
                                        ORDER BY c_pro.`id_cart` ASC) '.pSQL($condition_group_rule['reminder']).(int)$value.'';
                                        break;
                                    case 'cart_productcat':
                                        if (isset($condition_group_rule['value'])) {
                                            $value = implode(',', $condition_group_rule['value']);
                                            $sql .= ' AND a.`id_cart` IN (SELECT distinct `id_cart` FROM `' . _DB_PREFIX_ . 'cart_product`  c_pro  LEFT JOIN `' . _DB_PREFIX_ . 'category_product` cat ON (c_pro.`id_product` = cat.`id_product`) WHERE cat.`id_category` IN ('.pSQL($value).'))';
                                        }
                                        break;
                                    case 'cart_productsupplier':
                                        if (isset($condition_group_rule['value'])) {
                                            $value = implode(',', $condition_group_rule['value']);
                                            $sql .= ' AND a.`id_cart` IN (SELECT distinct `id_cart` FROM `' . _DB_PREFIX_ . 'cart_product`  c_pro  LEFT JOIN `' . _DB_PREFIX_ . 'product_supplier` spl ON (c_pro.`id_product` = spl.`id_product`) WHERE spl.`id_product_supplier` IN ('.pSQL($value).'))';
                                        }
                                        break;
                                    case 'cart_productman':
                                        if (isset($condition_group_rule['value'])) {
                                            $value = implode(',', $condition_group_rule['value']);
                                            $sql .= ' AND a.`id_cart` IN (SELECT distinct `id_cart` FROM `' . _DB_PREFIX_ . 'cart_product`  c_pro  LEFT JOIN `' . _DB_PREFIX_ . 'product` pr ON (c_pro.`id_product` = pr.`id_product`) LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` man ON (pr.`id_manufacturer` = man.`id_manufacturer`) WHERE man.`id_manufacturer` IN ('.pSQL($value).'))';
                                        }
                                        break;
                                    case 'customer_email':
                                        $value = '';
                                        if (isset($condition_group_rule['value'][0])) {
                                            $value = $condition_group_rule['value'][0];
                                        }
                                        switch ($condition_group_rule['reminder']) {
                                            case '1':
                                                $sql .= 'AND c.`email` LIKE "'.pSQL($value).'"';
                                                break;
                                            case '2':
                                                $sql .= 'AND c.`email` NOT LIKE "'.pSQL($value).'"';
                                                break;
                                            case '3':
                                                $sql .= 'AND c.`email` LIKE \'%'.pSQL($value).'%\'';
                                                break;
                                            case '4':
                                                $sql .= 'AND c.`email` NOT LIKE \'%'.pSQL($value).'%\'';
                                                break;
                                            case '5':
                                                $sql .= 'AND c.`email` LIKE \'%'.pSQL($value).'%\'';
                                                break;
                                            case '6':
                                                $sql .= 'AND c.`email` LIKE \'%'.pSQL($value).'%\'';
                                                break;
                                        }
                                        break;
                                    case 'customer_language':
                                        if (isset($condition_group_rule['value'])) {
                                            $value = implode(',', $condition_group_rule['value']);
                                            $sql .= ' AND a.`id_lang` IN ('.pSQL($value).')';
                                        }
                                        break;
                                    case 'customer_aeg':
                                        $value = '';
                                        if (isset($condition_group_rule['value'][0])) {
                                            $value = strtotime('-'.(int)$condition_group_rule['value'][0].' years');
                                        }
                                        $sql .= ' AND '.(int)$value.pSQL($condition_group_rule['reminder']).' TIME_TO_SEC(c.`birthday`)';
                                        break;
                                    case 'customer_social':
                                        if (isset($condition_group_rule['value'])) {
                                            $value = implode(',', $condition_group_rule['value']);
                                            $sql .= ' AND c.`id_gender` IN ('.pSQL($value).')';
                                        }
                                        break;
                                    case 'customer_newlester':
                                        $value = 0;
                                        if (isset($condition_group_rule['value'][0])) {
                                            $value = (int)$condition_group_rule['value'][0];
                                        }
                                        $sql .= ' AND c.`id_gender` = '.(int)$value.'';
                                        break;
                                    case 'customer_register':
                                        
                                        $value = '';
                                        if (isset($condition_group_rule['value'][0])) {
                                            $value = strtotime('-'.(int)$condition_group_rule['value'][0].' day');
                                        }
                                        $sql .= ' AND '.(int)$value . pSQL($condition_group_rule['reminder']).' TIME_TO_SEC(c.`newsletter_date_add`)';
                                        break;
                                    case 'customer_order':
                                        $value = 0;
                                        if (!isset($condition_group_rule['value'][0])) {
                                            $value = (int)$condition_group_rule['value'][0];
                                        }
                                        $sql .= ' AND (SELECT COUNT(`id_order`) FROM `' . _DB_PREFIX_ . 'orders` WHERE `id_customer`=c.`id_customer`) '.pSQL($condition_group_rule['reminder']).(int)$value .' ';
                                        break;
                                    case 'customer_country':
                                        
                                        if (isset($condition_group_rule['value'])) {
                                            $value = implode(',', $condition_group_rule['value']);
                                            $sql .= ' AND c.`id_customer` IN (SELECT distinct `id_customer` FROM `' . _DB_PREFIX_ . 'address`  dr WHERE dr.`id_customer` = c.`id_customer` AND dr.`id_country` IN ('.pSQL($value).'))';
                                        }
                                        break;
                                }
                            }
                        }
                    }
                    $sql .= ' )';
                }
            $sql .= ' )';
        }
        $sql .= ' AND a.`id_customer` NOT IN (SELECT distinct `id_customer` FROM `' . _DB_PREFIX_ . 'gabandoned_unsubscribe_email` WHERE `id_customer` =  c.`id_customer` AND `email`=c.`email`) ' ;
        if($checknotin && count($checknotin)) {
            $checknotin = implode(',',$checknotin);
            if ($check) {
                if ($checknotin!= '' ) {
                    $sql .= ' AND a.`id_cart` NOT IN ('.pSql($checknotin).') ' ;
                } else {
                    $sql .= ' AND a.`id_cart` NOT IN ("") ' ;
                }
            } else {
                if ($checknotin!= '' ) {
                    $sql .= ' AND a.`id_cart` IN ('.pSql($checknotin).') ' ;
                } else {
                    $sql .= ' AND a.`id_cart` IN ("") ' ;
                }
            }
        }
        if ($auto) {
            $day = Configuration::get('CONFIGGETCARTDAYS', null, $id_shop_group, $id_shop);
            $hr  = Configuration::get('CONFIGGETCARTHRS', null, $id_shop_group, $id_shop);
            $day = $day ? (int)$day : '0';
            $hr  =  $hr ? (int)$hr  : '1';
            $sql .= ' AND a.`date_add` >= "'.date('Y-m-d H:i:s', (time() - ($day * 24 *60 * 60 + $hr *60 * 60))).'"';
        }
        if ($count)
            return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        if ($limit)
            $sql .= ' LIMIT '.(int)(((int)$p - 1) * (int)$n).','.(int)$n;
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql, true, false);
    }
}