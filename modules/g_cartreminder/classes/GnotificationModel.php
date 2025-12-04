<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 *
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link         http://www.globosoftware.net
 */
 
class GnotificationModel extends ObjectModel
{
    public $id_gabandoned_notification;
    public $setting_notification;
    public $setting_tab;
    public $title_notification;
    public $message_notification;
    public $message_tab;
    public static $definition = array(
        'table' => 'gabandoned_notification',
        'primary' => 'id_gabandoned_notification',
        'multilang' => true,
        'fields' => array(
            'setting_notification' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'setting_tab' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            //lang.
            'title_notification' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isString'),
            'message_notification' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isString'),
            'message_tab' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isString'),
            ),
        );

    public function __construct($id_gabandoned_notification = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation('gabandoned_notification', array('type' => 'shop'));
        parent::__construct($id_gabandoned_notification, $id_lang, $id_shop);
        return true;
    }
}