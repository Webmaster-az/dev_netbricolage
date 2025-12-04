<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */

class AdminGsettingController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
    }
    public function renderOptions()
    {
        $this->html = '';
        $link = $this->context->link;
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
            
            $this->tpl_form_vars['notificationtab'] = Tools::jsonDecode($objectclass->setting_tab, true);
            
        } else {
            $defaultval = $this->defaultval();
            $this->tpl_form_vars['notification'] = $defaultval['1'];
            $this->tpl_form_vars['notificationtab'] = $defaultval['2'];
            foreach (Language::getLanguages(false) as $lang) {
                $this->tpl_form_vars['title_notification'][$lang['id_lang']] = $this->l('hi,');
                $this->tpl_form_vars['message_notification'][$lang['id_lang']] = $this->l('Your order is almost complete. Checkout Now!');
                $this->tpl_form_vars['message_tab'][$lang['id_lang']] = '';
            }
        }
        if (Configuration::get('GC_EMAIL_TRACKING_ID')) {
            $this->tpl_form_vars['GC_EMAIL_TRACKING_ID'] = Configuration::get('GC_EMAIL_TRACKING_ID');
        }else{
            $this->tpl_form_vars['GC_EMAIL_TRACKING_ID'] = '';
        }
        $controller = Tools::getValue('controller');
        $this->tpl_form_vars['g_domain'] = Tools::getShopDomain(false);
        $this->tpl_form_vars['controller'] = $controller;
        $this->tpl_form_vars['defaultFormLanguage'] = (int)Configuration::get('PS_LANG_DEFAULT');
        $this->tpl_form_vars['g_module_url'] = $this->context->shop->getBaseURL().'modules/g_cartreminder/';
        Context::getContext()->smarty->assign($this->tpl_form_vars);
        $linktohelp = $this->context->link->getAdminLink('AdminGnotification');
        $this->context->smarty->assign(
            array(
                'controller'   => $controller,
                'g_module_url' => $this->context->shop->getBaseURL(true).'modules/g_cartreminder/',
                'link'         => new Link(),
                'usingSecureMode' =>Tools::usingSecureMode(),
                'linktohelp'   => $linktohelp,
            )
        );
        $this->fields_options = array(
            'setting' => array(
                'title' => $this->l('Setting Tab'),
                'class' => 'gcarttab-setting',
                'fields' => array(
                    array(
                        'type' => 'settingnotification',
                        'name' => '',
                    ),
                    /*
                    array(
                        'type' => 'listtab',
                        'name' => '',
                    ),
                    array(
                        'type' => 'date_get_cart',
                        'name' => '',
                    ),
                    array(
                        'type' => 'settingnotification',
                        'name' => '',
                    ),*/
                ),
                /*'submit' => array('title' => $this->l('Save')),*/
            ),
        );
        if ($this->fields_options && is_array($this->fields_options)) {
            $helper = new HelperOptions($this);
            $this->setHelperDisplay($helper);
            $helper->toolbar_scroll = true;
            $helper->title = $this->l('Setting');
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
            $this->html .= $this->getHTMLtab($link, 'date_get_cart', $controller);
            $this->html .= $helper->generateOptions($this->fields_options);
            $this->html .= $this->getHTMLtab($link, 'end', $controller);
            return $this->html;
        }
        return $this->html;
    }
    public function getHTMLtab($link, $name, $controller){
        $dirimg = '../modules/g_cartreminder/views/img';
        $CONFIGGETCARTDAYS = '';
        $CONFIGGETCARTHRS  = '';
        if ($name == 'date_get_cart') {
            $id_shop       = (int)$this->context->shop->id;
            $id_shop_group = (int)Shop::getContextShopGroupID();
            $CONFIGGETCARTDAYS = Configuration::get('CONFIGGETCARTDAYS', null, $id_shop_group, $id_shop);
            $CONFIGGETCARTHRS  = Configuration::get('CONFIGGETCARTHRS', null, $id_shop_group, $id_shop);
        }
        $version = 'PS16';
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
            $version = 'PS17';
        }
        $this->context->smarty->assign(array(
            'controller'=> $controller,
            'name'      => $name,
            'link'      => $link,
            'dirimg'    => $dirimg,
            'CONFIGGETCARTDAYS' => $CONFIGGETCARTDAYS,
            'CONFIGGETCARTHRS'  => $CONFIGGETCARTHRS,
            'version'   => $version,
        ));
        $html = $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/tabs/htmlall.tpl");
        return $html;
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
    public function postProcess(){
    	if (Tools::getValue('action') == 'SaveconfigTime') {
            $idshop = (int)$this->context->shop->id;
            $CONFIGGETCARTDAYS = Tools::getValue('CONFIGGETCARTDAYS', true);
            $CONFIGGETCARTHRS  = Tools::getValue('CONFIGGETCARTHRS', true);
            if ((int)$CONFIGGETCARTDAYS == 0 && (int)$CONFIGGETCARTHRS == 0) {
                if ((int)$CONFIGGETCARTDAYS == 0) {
                    echo Tools::jsonEncode(array('error'=> $this->l('ERROR: DAYS is empty'))); die;
                } else {
                    echo Tools::jsonEncode(array('error'=> $this->l('ERROR: HRS is empty'))); die;
                }
            } else {
                $res   = true;
        		$shops = Shop::getContextListShopID();
        		foreach ($shops as $shop_id)
        		{
        			$shop_group_id = (int)Shop::getGroupFromShop((int)$shop_id, true);
                    $res &= (bool)Configuration::updateValue('CONFIGGETCARTDAYS', (int)$CONFIGGETCARTDAYS, false, (int)$shop_group_id, (int)$shop_id);
                    $res &= (bool)Configuration::updateValue('CONFIGGETCARTHRS', (int)$CONFIGGETCARTHRS, false, (int)$shop_group_id, (int)$shop_id);
                }
                if ($res == true) {
                   echo Tools::jsonEncode(array('error'=> 'true', 'update'=>$this->l('Update Succesfull'))); die; 
                }
            }
                
        }elseif(Tools::isSubmit('submitOptionsconfiguration')){
            
            $idshop = (int)$this->context->shop->id;
            $CONFIGGETCARTDAYS = Tools::getValue('CONFIGGETCARTDAYS', true);
            $CONFIGGETCARTHRS  = Tools::getValue('CONFIGGETCARTHRS', true);
            if ((int)$CONFIGGETCARTDAYS == 0 && (int)$CONFIGGETCARTHRS == 0) {
                if ((int)$CONFIGGETCARTDAYS == 0) {
                    $this->error[] = $this->l('ERROR: DAYS is empty');
                } else {
                    $this->error[] = $this->l('ERROR: HRS is empty');
                }
            } else {
                $res   = true;
        		$shops = Shop::getContextListShopID();
        		foreach ($shops as $shop_id)
        		{
        			$shop_group_id = (int)Shop::getGroupFromShop((int)$shop_id, true);
                    $res &= (bool)Configuration::updateValue('CONFIGGETCARTDAYS', (int)$CONFIGGETCARTDAYS, false, (int)$shop_group_id, (int)$shop_id);
                    $res &= (bool)Configuration::updateValue('CONFIGGETCARTHRS', (int)$CONFIGGETCARTHRS, false, (int)$shop_group_id, (int)$shop_id);
                }
            }
            
            $objectclass = new GnotificationModel(1, null, $idshop);
            $notification = array();
            $notificationtab = array();
            $data_old = Tools::jsonDecode($objectclass->setting_notification,true);
            $data_tab_old = Tools::jsonDecode($objectclass->setting_tab,true);
            
            $notification = Tools::getValue('notification');
            if (isset($data_old) && $data_old) {
                $notification['delay_notification'] = $data_old['delay_notification'];
                $notification['img_icon'] = $data_old['img_icon'];
                $notification['checkouttitle'] = $data_old['checkouttitle'];
                $notification['checkout'] = '';
            }else{
                $notification['delay_notification'] = '';
                $notification['img_icon'] = '';
                $notification['checkouttitle'] = '';
                $notification['checkout'] = 0;
            }
            $notificationtab = Tools::getValue('notificationtab');
            if (isset($data_tab_old) && $data_tab_old) {
                $notificationtab['bg_color'] = $data_tab_old['bg_color'];
                $notificationtab['fnt_color'] = $data_tab_old['fnt_color'];
                $notificationtab['delay_tab'] = 0;
            }else{
                $notificationtab['bg_color'] = '';
                $notificationtab['fnt_color'] = '';
                $notificationtab['delay_tab'] = 0;
            }
            $objectclass->setting_notification = Tools::jsonEncode($notification);
            $objectclass->setting_tab = Tools::jsonEncode($notificationtab);
            if (Validate::isLoadedObject($objectclass) && $objectclass->id != 0) {
                $res &= $objectclass->update();
            } else {
                $res &= $objectclass->save();
            }
            
            $shop_groups_list = array();
			$shops = Shop::getContextListShopID();
            $shop_context = Shop::getContext();
			foreach ($shops as $shop_id)
			{
				$shop_group_id = (int)Shop::getGroupFromShop((int)$shop_id, true);
				if (!in_array($shop_group_id, $shop_groups_list))
					$shop_groups_list[] = (int)$shop_group_id;
				$res = Configuration::updateValue('GC_EMAIL_TRACKING_ID', Tools::getValue('GC_EMAIL_TRACKING_ID'), false, (int)$shop_group_id, (int)$shop_id);
            }
			/* Update global shop context if needed*/
			switch ($shop_context)
			{
				case Shop::CONTEXT_ALL:
					$res = Configuration::updateValue('GC_EMAIL_TRACKING_ID', Tools::getValue('GC_EMAIL_TRACKING_ID'));
                    if (count($shop_groups_list))
					{
						foreach ($shop_groups_list as $shop_group_id)
						{
							$res = Configuration::updateValue('GC_EMAIL_TRACKING_ID', Tools::getValue('GC_EMAIL_TRACKING_ID'), false, (int)$shop_group_id);
						}
					}
					break;
				case Shop::CONTEXT_GROUP:
					if (count($shop_groups_list))
					{
						foreach ($shop_groups_list as $shop_group_id)
						{
							$res = Configuration::updateValue('GC_EMAIL_TRACKING_ID', Tools::getValue('GC_EMAIL_TRACKING_ID'), false, (int)$shop_group_id);
						}
					}
					break;
			}
            if (!$res)
				$this->errors[] = $this->l('The configuration could not be updated.');
                
            if ($res == true) {
               Tools::redirectAdmin($this->context->link->getAdminLink('AdminGsetting').'&conf=4');
            }
        }
    }
}