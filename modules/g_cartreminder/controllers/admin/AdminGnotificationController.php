<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */

include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GnotificationModel.php');
class AdminGnotificationController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->context = Context::getContext();
        $this->table = 'gabandoned_notification';
        $this->lang = true;
        $this->className = 'GnotificationModel';
        $this->tpl_form_vars['defaultFormLanguage'] = (int)Configuration::get('PS_LANG_DEFAULT');
        Context::getContext()->smarty->assign($this->tpl_form_vars);
        parent::__construct();
    }
    /**
     * Function used to render the options for this controller
     */
    public function renderOptions()
    {
        $this->html = '';
        $link       = new Link();
        $id_shop = $this->context->shop->id;
        $objectclass = new GnotificationModel(1, null, $id_shop);
        if (Validate::isLoadedObject($objectclass)) {
            $this->tpl_form_vars['notification'] = Tools::jsonDecode($objectclass->setting_notification, true);
            
            /* add new 19-03-2018 */
            if(isset($this->tpl_form_vars['notification']['delay_notification']) && trim($this->tpl_form_vars['notification']['delay_notification']) !=''){
                $delay_notifications = explode(',',$this->tpl_form_vars['notification']['delay_notification']);
                if($delay_notifications){
                    foreach($delay_notifications as $key=> &$delay_notification){
                        if($delay_notification !='')
                            $delay_notification = explode(';',$delay_notification);
                        else unset($delay_notifications[$key]);
                    }
                    if($delay_notifications)
                        $this->tpl_form_vars['notification']['delay_notifications'] = $delay_notifications;
                }
                
            }
            /* add new 19-03-2018 */
            
            
            $this->tpl_form_vars['setting_tab'] = Tools::jsonDecode($objectclass->setting_tab, true);
            foreach (Language::getLanguages(false) as $lang){
                $this->tpl_form_vars['title_notification'][$lang['id_lang']] = $objectclass->title_notification[$lang['id_lang']];
                $this->tpl_form_vars['message_notification'][$lang['id_lang']] = $objectclass->message_notification[$lang['id_lang']];
                $this->tpl_form_vars['message_tab'][$lang['id_lang']] = $objectclass->message_tab[$lang['id_lang']];
            }
        } else {
            $defaultval = $this->defaultval();
            $this->tpl_form_vars['notification'] = $defaultval['1'];
            $this->tpl_form_vars['setting_tab'] = $defaultval['2'];
            foreach (Language::getLanguages(false) as $lang) {
                $this->tpl_form_vars['title_notification'][$lang['id_lang']] = $this->l('hi,');
                $this->tpl_form_vars['message_notification'][$lang['id_lang']] = $this->l('Your order is almost complete. Checkout Now!');
                $this->tpl_form_vars['message_tab'][$lang['id_lang']] = '';
            }
        }
        $controller = Tools::getValue('controller');
        $this->tpl_form_vars['g_domain'] = Tools::getShopDomain(false);
        $this->tpl_form_vars['controller'] = $controller;
        $this->tpl_form_vars['defaultFormLanguage'] = (int)Configuration::get('PS_LANG_DEFAULT');
        $this->tpl_form_vars['g_module_url'] = $this->context->shop->getBaseURL().'modules/g_cartreminder/';
        Context::getContext()->smarty->assign($this->tpl_form_vars);
        $this->fields_options = array(
            'setting' => array(
                'title' => $this->l(' Browser notification'),
                'class' => 'gcarttab-gnotification',
                'fields' => array(
                    array(
                        'type' => 'settingnotification',
                        'tab' => 'gnotification',
                    ),
                    /*array(
                        'type' => 'settingtabnotification',
                        'tab' => 'gtab_notification',
                    ),
                    array(
                        'type' => 'helpsetting_onesignal',
                        'tab' => 'help_gnotification',
                    ),*/
                ),
                /*'submit' => array('title' => $this->l('Save')),*/
            ),
        );
        if ($this->fields_options && is_array($this->fields_options)) {
            $helper = new HelperOptions($this);
            $this->setHelperDisplay($helper);
            $helper->toolbar_scroll = true;
            $helper->title = $this->l('Browser');
            $helper->id = $this->id;
            $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
            $helper->default_form_language = $lang->id;
            $helper->tpl_vars = array(
    			'languages' => $this->context->controller->getLanguages(),
    			'id_language' => $this->context->language->id,
    		);
            if (Tools::getValue('savesuccssetfull') == 1) {
                $this->html .= $this->module->displayConfirmation($this->l('Settings updated.'));
            }
            $this->html .= $this->getHTMLtab($link, 'tabs', $controller);
            $this->html .= $this->getHTMLtab($link, 'start', $controller);
            $this->html .= $helper->generateOptions($this->fields_options);
            $this->html .= $this->getHTMLtab($link, 'end', $controller);
            return $this->html;
        }
    }
    public function postProcess()
	{
        $langs = $this->context->controller->getLanguages();
        $id_shop = $this->context->shop->id;
        $objectclass = new GnotificationModel(1, null, $id_shop);
        $action = Tools::getValue('action');
        // dafaul
        $notificationpush = array();
        $setting_tabpush = array();
        if (isset($action) && $action == 'SaveConfig') {
            $Tailfiles = array('.png', '.jpg');
            $notification = Tools::getValue('notification');
            $setting_tab = Tools::getValue('notificationtab');
            if ($_FILES != 'undefined' && $_FILES['img_icon']['error'] == 0 && $_FILES['img_icon']['size'] != 0) {
                $k = false;
                foreach ($Tailfiles as $Tailfile) {
                    $pos = strpos($_FILES['img_icon']['name'], $Tailfile);
                    if ($pos !== false) {
                        move_uploaded_file($_FILES['img_icon']['tmp_name'], _PS_MODULE_DIR_ . "g_cartreminder/views/img/browser/" . $_FILES['img_icon']['name'] );
                        $k = true;
                    }
                } 
                if ($k != true) {
                    echo $this->l('ERROR: File not in format (.png, .jpg)');
                    die;
                }
            } elseif (Validate::isLoadedObject($objectclass)) {
                if ($objectclass->setting_notification != '') {
                    $notificationpush = Tools::jsonDecode($objectclass->setting_notification, true);
                }
                if ($notificationpush['img_icon'] !='') {
                    if (!is_file(_PS_MODULE_DIR_ .  "g_cartreminder/views/img/browser/" . $notificationpush['img_icon'] )) {
                        echo $this->l('The FILE no longer exists');
                        die;
                    }
                }
            }
            if ($objectclass->setting_notification != '') {
                $notificationpush = Tools::jsonDecode($objectclass->setting_notification, true);
            }
            if ($objectclass->setting_tab != '') {
                $setting_tabpush = Tools::jsonDecode($objectclass->setting_tab, true);
            }
            if ($notificationpush) {
                if ($notification['delay_notification'] != '') {
                    $notificationpush['delay_notification'] = $notification['delay_notification'];
                }
                if (!empty($notification['img_icon'])) {
                    $k = false;
                    foreach ($Tailfiles as $Tailfile) {
                        $pos = strpos($notification['img_icon'], $Tailfile);
                        if ($pos !== false) {
                            $k = true;
                        }
                    }
                    if ($k != true) {
                        echo $this->l('ERROR: File not in format (.png, .jpg)');
                        die;
                    }
                }
                $notificationpush['img_icon'] = $notification['img_icon'];
                $notificationpush['checkouttitle'] = $notification['checkouttitle'];
                if (isset($notification['checkout']) && $notification['checkout'] != 0) {
                    $notificationpush['checkout'] = $notification['checkout'];
                }else{
                    $notificationpush['checkout'] = 0;
                }
            }else{
                $notificationpush = $notification;
                $notificationpush[''] = '';
            }
            if ($setting_tabpush) {
                $setting_tabpush['bg_color'] = $setting_tab['bg_color'];
                $setting_tabpush['fnt_color'] = $setting_tab['fnt_color'];
                $setting_tabpush['delay_tab'] = $setting_tab['delay_tab'];
            }else{
                $setting_tabpush = $setting_tab;
            }
            $objectclass->setting_notification = Tools::jsonEncode($notificationpush);
            $objectclass->setting_tab = Tools::jsonEncode($setting_tabpush);
            foreach ($langs as $lang) {
                $objectclass->title_notification[$lang['id_lang']] = Tools::getValue('title_notification_'.$lang['id_lang']);
                $objectclass->message_notification[$lang['id_lang']] = Tools::getValue('message_notification_'.$lang['id_lang']);
                $objectclass->message_tab[$lang['id_lang']] = Tools::getValue('message_tab_'.$lang['id_lang']);
            }
            if (Validate::isLoadedObject($objectclass)) {
                $objectclass->update();
            } else {
                $objectclass->save();
            }
            echo 'okie';die();
        }
        parent::postProcess();
    }
    public function defaultval() {
        $browser = array (
            'notification_off' => '0',
            'delay_notification' => '30000',
            'img_icon' => 'defaulIcon.png',
            'checkout' => '0',
            'checkouttitle' => '',
            'apponesignal_id' => '',
            'apponesignal_api_id' => '',
            'apponesignal_safari_id' => '',
        );
        $tab = array (
            'tabs_for' => '0',
            'bg_color' => '#000000',
            'fnt_color' => '#ffffff',
            'delay_tab' => '30',
        );
        return array('1'=>$browser,'2'=>$tab);
    }
    
    public function getHTMLtab($link, $name, $controller){
        $version = 'PS16';
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
            $version = 'PS17';
        }
        $dirimg = '../modules/g_cartreminder/views/img';
        $this->context->smarty->assign(array(
            'controller'=> $controller,
            'name'      => $name,
            'link'      => $link,
            'dirimg'    => $dirimg,
            'version'   => $version,
        ));
        $html = $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/tabs/htmlall.tpl");
        return $html;
    }
}
