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

class WkPwaPushNotificationType extends ObjectModel
{
    public $id_notification_type;
    public $name;
    public $active;
    public $date_add;
    public $date_upd;

    public static $definition = array(
        'table' => 'wk_pwa_push_notification_type',
        'primary' => 'id_notification_type',
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'required' => true),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => true),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat', 'required' => true),
        ),
    );

    public static function isNotificationTypeActive($idNotificationType)
    {
        $objPushNotificationType = new WkPwaPushNotificationType($idNotificationType);
        return (int)$objPushNotificationType->active;
    }
}
