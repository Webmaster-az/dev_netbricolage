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

class AdminManualPushNotificationController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->bootstrap = true;

        $this->table = 'wk_pwa_push_notification';
        $this->className = 'WkPwaPushNotification';
        $this->identifier = 'id';
        parent::__construct();
    }

    public function initContent()
    {
        if (!$this->display) {
            $this->content .= $this->context->smarty->fetch(
                _PS_MODULE_DIR_.$this->module->name.'/views/templates/admin/_partial/push_notification_progress.tpl'
            );
        }

        parent::initContent();
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

    public function renderList()
    {
        $objPushNotification = new WkPwaPushNotification();
        $customerTypes = $objPushNotification->getCustomerTypes();

        $this->_select = 'a.`id` AS `sendNotification`, pnl.`title` as `title_name`, pnl.`body` as `body_name`,
        IF(a.`push_schedule`,
        IF(a.`push_schedule` < CURDATE(), 1, 0), 0) badge_danger';
        $this->_join .= ' JOIN `'._DB_PREFIX_.'wk_pwa_push_notification_lang` pnl
        ON (a.`id` = pnl.`id` AND pnl.`id_lang` =
         '.(int) $this->context->language->id.')';
        $this->_where = 'AND a.`id_notification_type` = '.(int) WkPwaPushNotification::MANUAL_NOTIFICATION;

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
            'customer_type' => array(
                'title' => $this->l('Target Customer Type'),
                'type' => 'select',
                'align' => 'center',
                'list' => $customerTypes,
                'class' => 'fixed-width-sm',
                'filter_key' => 'a!customer_type',
                'filter_type' => 'int',
                'havingFilter' => true,
                'callback' => 'getCustomerType',
            ),
            'customer_type_value' => array(
                'title' => $this->l('Customer Type value') ,
                'align' => 'center',
                'search' => false,
                'class' => 'fixed-width-xs',
                'callback' => 'getCustomerTypeValue',
            ),
            'push_schedule' => array(
                'title' => $this->l('Scheduled Date'),
                'align' => 'center',
                'type' => 'date',
                'badge_danger' => true,
                'callback' => 'getScheduledDate',
            ),
            'sendNotification' => array(
                'title' => $this->l('Push Notification') ,
                'align' => 'center',
                'search' => false,
                'remove_onclick' => true,
                'callback' => 'sendNotification',
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

        return parent::renderList();
    }

    public function getCustomerType($idCustomerType)
    {
        $objPushNotification = new WkPwaPushNotification();
        $customerTypes = $objPushNotification->getCustomerTypes();
        return $customerTypes[$idCustomerType];
    }

    public function getCustomerTypeValue($idCustomerTypeValue, $row)
    {
        if ($row['customer_type'] == WkPwaPushNotification::CUSTOMER_TYPE_ALL) {
            return '-';
        } elseif ($row['customer_type'] == WkPwaPushNotification::CUSTOMER_TYPE_GROUP) {
            $group = new Group($idCustomerTypeValue, Configuration::get('PS_LANG_DEFAULT'));
            return $group->name;
        } elseif ($row['customer_type'] == WkPwaPushNotification::CUSTOMER_TYPE_PARTICULAR_CUSTOMER) {
            $customer = new Customer($idCustomerTypeValue);
            return $customer->firstname.' '.$customer->lastname;
        }
    }

    public function getScheduledDate($pushScheduleDate)
    {
        if ($pushScheduleDate == WkPwaPushNotification::DEFAULT_DATE_TIME) {
            return '-';
        } else {
            return Tools::displayDate($pushScheduleDate);
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

    public function sendNotification($idPushNotification)
    {
        if (Module::isEnabled('wkpwa')) {
            $this->context->smarty->assign(array('element_type' => 1, 'idPushNotification' => $idPushNotification));
            $sendNotificationBtn = $this->context->smarty->fetch(
                _PS_MODULE_DIR_.$this->module->name.
                '/views/templates/admin/_partial/html_element.tpl'
            );
        } else {
            $this->context->smarty->assign(array('element_type' => 2, 'idPushNotification' => $idPushNotification));
            $sendNotificationBtn = $this->context->smarty->fetch(
                _PS_MODULE_DIR_.$this->module->name.
                '/views/templates/admin/_partial/html_element.tpl'
            );
        }

        return $sendNotificationBtn;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('pushNotificationAction')) {
            $objPushNotification = new WkPwaPushNotification();
            $response = $objPushNotification->sendPushNotification(Tools::getValue('id'));
            if (!$response['success']) {
                $this->errors = $response['message'];
            } else {
                Tools::redirectAdmin(
                    self::$currentIndex.'&token='.$this->token.'&deliveryCount='.$response['notificationDeliveredCount']
                );
            }
        } elseif (Tools::getValue('productPushNotification')) {
            $idProduct = Tools::getValue('idProduct');
            $objPushNotification = new WkPwaPushNotification();

            $notificationDetail = $objPushNotification->getByIdNotificationType(
                WkPwaPushNotification::NEW_PRODUCT_NOTIFICATION
            );
            if ($notificationDetail) {
                $objPushNotification->sendPushNotification($notificationDetail['id'], $idProduct);
            }
            Tools::redirectAdmin(
                $this->context->link->getAdminLink(
                    'AdminProducts',
                    false,
                    array(
                        'id_product' => $idProduct
                    )
                ).'#tab-hooks'
            );
        } elseif (Tools::getIsset('deliveryCount')) {
            $this->context->smarty->assign(
                'conf',
                $this->l('Manual Notification successfully delivered to').' '.(int)Tools::getValue('deliveryCount').' '.
                $this->l('subscriber(s)')
            );
        }
        parent::postProcess();
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
        $this->context->smarty->assign($defaultVariables);

        $this->fields_form = array(
            'submit' => array(
                'title' => $this->l('Save'),
            ),
        );

        return parent::renderForm();
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
            'idPushNotification' => trim(Tools::getValue('id')),
            'idNotificationType' => WkPwaPushNotification::MANUAL_NOTIFICATION,
            'title' => $title,
            'body' => $body,
            'targetUrl' => trim(Tools::getValue('target_url')),
            'notificationIcon' => $_FILES['icon'],
            'customerType' => trim(Tools::getValue('customer_type')),
            'customerTypeIdGroupValue' => trim(Tools::getValue('customer_type_idGroup_value')),
            'customerTypeIdCustomerValue' => trim(Tools::getValue('customer_type_idCustomer_value')),
            'schedulePushSwitch' => trim(Tools::getValue('schedule_push_switch')),
            'pushSchedule' => trim(Tools::getValue('push_schedule')),
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

    public function ajaxProcessGetTotalSubscribers()
    {
        $idElement = Tools::getValue('idElement');
        $idPushNotification = Tools::getValue('idPushNotification');

        $objPushNotificationToken = new WkPwaPushNotificationToken();
        $subscribers = $objPushNotificationToken->getNotificationSubscribers(
            $idPushNotification,
            $idElement
        );

        if ($subscribers) {
            die(json_encode(count($subscribers)));
        }

        die(json_encode(0));
    }

    public function ajaxProcessSendPushNotification()
    {
        $idElement = Tools::getValue('idElement');
        $startIndex = Tools::getValue('startIndex');
        $dataSelectionLimit = Tools::getValue('dataSelectionLimit');
        $idPushNotification = Tools::getValue('idPushNotification');
        $idPushNotificationHistory = Tools::getValue('idPushNotificationHistory');

        $objPushNotification = new WkPwaPushNotification();
        $response = $objPushNotification->sendPushNotification(
            $idPushNotification,
            $idElement,
            $startIndex,
            $dataSelectionLimit,
            $idPushNotificationHistory
        );

        die(json_encode($response));
    }

    public function ajaxProcessCustomerSearch()
    {
        $searchText = Tools::getValue('searchText');
        if (Tools::strlen(trim($searchText))) {
            die(json_encode(WkPwaPushNotificationToken::searchSubscriberByName($searchText)));
        }

        die(false);
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        Media::addJsDef(array(
            'sendNotificationContLink' => $this->context->link->getAdminlink('AdminManualPushNotification'),
            'anotherPushProcessError' => $this->l('Please wait, another push notification process is in progress.'),
            'noSubscriberError' => $this->l('No subscriber exists !!'),
            'successMsgPrefix' => $this->l('Manual Notification successfully delivered to'),
            'successMsgSuffix' => $this->l('subscriber(s)'),
        ));

        $this->addCSS(_MODULE_DIR_.$this->module->name.'/views/css/admin/push_notification.css');
        $this->addJS(_MODULE_DIR_.$this->module->name.'/views/js/admin/push_notification.js');
    }
}
