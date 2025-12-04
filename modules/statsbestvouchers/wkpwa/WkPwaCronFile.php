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

include_once(dirname(__FILE__).'/../../config/config.inc.php');
include_once('classes/define.php');
include_once('wkpwa.php');

$objWkPwa = Module::getInstanceByName('wkpwa');
if (Tools::getValue('token') != $objWkPwa->secure_key) {
    //Set error if token mismatched
    if ($errorLog = fopen(_PS_MODULE_DIR_.'/wkpwa/error_log', 'a+')) {
        $now = new DateTime();
        $txt = '['.$now->format('Y-m-d H:i:s').'] : ';
        $txt .= 'Failed to execute cron file:  Token Invalid';
        fwrite($errorLog, $txt."\n");
    }
    fclose($errorLog);
    die('Something went wrong.');
}

if (Configuration::get('WK_PWA_PUSH_NOTIFICATION_ENABLE')) {
    $objPushNotification = new WkPwaPushNotification();

    // Schedule Push Notification
    $scheduledPushNotification = $objPushNotification->getScheduledPushNotification();
    if ($scheduledPushNotification) {
        foreach ($scheduledPushNotification as $notification) {
            $objPushNotification->sendPushNotification($notification['id']);
        }
    }

    // Cart Reminder Notification
    $abandonedCart = $objPushNotification->getAbandonedCart();
    if ($abandonedCart) {
        $notificationDetail = $objPushNotification->getByIdNotificationType(
            WkPwaPushNotification::CART_REMINDER_NOTIFICATION
        );
        if ($notificationDetail) {
            foreach ($abandonedCart as $cart) {
                $cart = new Cart((int)$cart['id_cart']);
                $customer = new Customer($cart->id_customer);
                $currency = new Currency($cart->id_currency);

                $context = Context::getContext();
                $context->cart = $cart;
                $context->currency = $currency;
                $context->customer = $customer;

                $objPushNotification->sendPushNotification($notificationDetail['id'], $cart->id);
            }
        }
    }

    $objPushNotificationHistory = new WkPwaPushNotificationHistory();
    $pushNotificationHistory = $objPushNotificationHistory->getCartReminderNoitifcation();
    if ($pushNotificationHistory) {
        foreach ($pushNotificationHistory as $notificationHistory) {
            $objPushNotification->sendPushNotificationByHistory($notificationHistory['id_push_notification_history']);
        }
    }
    die('OK');
}
