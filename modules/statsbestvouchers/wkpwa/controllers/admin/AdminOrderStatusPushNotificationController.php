<?php
/**
* 2010-2021 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through LICENSE.txt file inside our module
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright 2010-2021 Webkul IN
* @license LICENSE.txt
*/

class AdminOrderStatusPushNotificationController extends ModuleAdminController
{
    protected $statusesArray = array();

    public function __construct()
    {
        $this->context = Context::getContext();
        $this->bootstrap = true;
        $this->table = 'wk_pwa_push_notification';
        $this->className = 'WkPwaPushNotification';
        $this->identifier = 'id';

        parent::__construct();

        $this->toolbar_title = $this->l('Manage Order Status Notification');

        $this->_select = 'pnl.`title` as `title_name`, pnl.`body` as `body_name`, osl.`name` as `osname`,
		os.`color`';
        $this->_join .= ' JOIN `'._DB_PREFIX_.'wk_pwa_push_notification_lang` pnl
        ON (a.`id` = pnl.`id` AND pnl.`id_lang` =
         '.(int) $this->context->language->id. ')
        JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = a.`order_status`)
        JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state`
        AND osl.`id_lang` = '.(int)$this->context->language->id.')';
        $this->_where = 'AND a.`id_notification_type` = '.(int) WkPwaPushNotification::ORDER_STATUS_NOTIFICATION;
        $statuses = OrderState::getOrderStates((int)$this->context->language->id);
        foreach ($statuses as $status) {
            $this->statusesArray[$status['id_order_state']] = $status['name'];
        }
        $this->fields_list = array(
            'id' => array(
                'title' => $this->l('ID') ,
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ),
            'icon' => array(
                'title' => $this->l('Icon') ,
                'align' => 'center',
                'search' => false,
                'callback' => 'getNotificationIcon',
            ),
            'title_name' => array(
                'title' => $this->l('Title') ,
                'having_filter' => true,
                'filter_key' => 'pnl!title',
            ),
            'body_name' => array(
                'title' => $this->l('Body') ,
                'having_filter' => true,
                'filter_key' => 'pnl!body',
            ),
            'target_url' => array(
                'title' => $this->l('Target URL') ,
            ),
            'osname' => array(
                'title' => $this->l('Order Status'),
                'type' => 'select',
                'color' => 'color',
                'list' => $this->statusesArray,
                'filter_key' => 'os!id_order_state',
                'filter_type' => 'int',
                'order_key' => 'osname'
            ),
            'date_add' => array(
                'title' => $this->l('Date Add'),
                'align' => 'center',
                'type' => 'datetime',
            ),
        );
        $this->list_no_link = 1;

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected items?'),
            ),
        );
    }

    public function renderForm()
    {
        $objPushNotification = new WkPwaPushNotification();
        if ($this->display == 'edit') {
            $idPushNotification = Tools::getValue('id');
            $notificationDetail = $objPushNotification->getCompleteNotificationDetails($idPushNotification);
            $this->context->smarty->assign('notificationDetail', $notificationDetail);
        }

        $defaultVariables = $objPushNotification->getPushNotificationDefaultVariables();
        $defaultVariables['edit'] = ($this->display == 'add') ? 0 : 1;
        $defaultVariables['orderStatus'] = OrderState::getOrderStates($this->context->language->id);
        $defaultVariables['orderStatusExists'] = WkPwaPushNotification::getAssignedOrderStatus();
        $this->context->smarty->assign($defaultVariables);

        $this->fields_form = array(
            'submit' => array(
                'title' => $this->l('Save'),
            ),
        );

        return parent::renderForm();
    }

    public function initToolbar()
    {
        if (empty($this->display)) {
            parent::initToolbar();
            $this->page_header_toolbar_btn['new'] = array(
                'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                'desc' => $this->l('Add Push Notification'),
            );
        }
    }

    public function getNotificationIcon($icon)
    {
        $imgUrl = _MODULE_DIR_.$this->module->name.'/views/img/notificationIcon/'.$icon;
        $this->context->smarty->assign(array('element_type' => 3, 'imgurl' => $imgUrl));
        $image = $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.
            '/views/templates/admin/_partial/html_element.tpl'
        );
        return $image;
    }

    public function processSave()
    {
        $objPushNotification = new WkPwaPushNotification();
        $title = array();
        $body = array();
        $defaultLangId = Configuration::get('PS_LANG_DEFAULT');
        foreach (Language::getLanguages() as $language) {
            $lang = $language['id_lang'];
            if (!Tools::getValue('title_'.$language['id_lang'])) {
                $lang = $defaultLangId;
            }
            $title[$language['id_lang']] = Tools::getValue('title_'.$lang);
            if (!Tools::getValue('body_'.$language['id_lang'])) {
                $lang = $defaultLangId;
            }
            $body[$language['id_lang']] = Tools::getValue('body_'.$lang);
        }

        $fields = array(
            'edit' => trim(Tools::getValue('id')) ? 1 : 0,
            'active' => 1,
            'idPushNotification' => trim(Tools::getValue('id')),
            'idNotificationType' => WkPwaPushNotification::ORDER_STATUS_NOTIFICATION,
            'title' => $title,
            'body' => $body,
            'targetUrl' => trim(Tools::getValue('target_url')),
            'orderStatus' => Tools::getValue('order_status'),
            'notificationIcon' => $_FILES['icon']
        );
        $notification = $objPushNotification->procressPushNotificationFields($fields);

        if (count($notification['errors'])) {
            $this->errors = $notification['errors'];
            if (Tools::getValue('id')) {
                $this->display = 'edit';
            } else {
                $this->display = 'add';
            }
        } else {
            if (Tools::isSubmit('submitAdd'.$this->table.'AndStay')) {
                if (Tools::getValue('id')) {
                    $redirectLink = self::$currentIndex.'&id='.(int) $notification['idPushNotification'];
                    $redirectLink .= '&update'.$this->table.'&conf=4&token='.$this->token;
                    Tools::redirectAdmin($redirectLink);
                } else {
                    $redirectLink = self::$currentIndex.'&id='.(int) $notification['idPushNotification'];
                    $redirectLink .= '&update'.$this->table.'&conf=3&token='.$this->token;
                    Tools::redirectAdmin($redirectLink);
                }
            } else {
                if (Tools::getValue('id')) {
                    Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.$this->token);
                } else {
                    Tools::redirectAdmin(self::$currentIndex.'&conf=3&token='.$this->token);
                }
            }
        }
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->addCSS(_MODULE_DIR_.$this->module->name.'/views/css/admin/push_notification.css');
        $this->addJS(_MODULE_DIR_.$this->module->name.'/views/js/admin/push_notification.js');
    }
}
