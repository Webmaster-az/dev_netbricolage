<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */

class GabadoneModel extends ObjectModel
{
    public $id_gabandoned_cart;
    public $id_cart;
    public $id_reminder;
    public $count;
    public $status_senmail;
    public $data_status;
    public $data_getcode;
    public $code;
    public $id_tempalte;
    public static $definition = array(
        'table' => 'gabandoned_cart',
        'primary' => 'id_gabandoned_cart',
        'multilang' => true,
        'fields' => array(
            //Fields
            'id_cart'     => array( 'type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_reminder' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'count'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'status_senmail' => array('type' => self::TYPE_INT, 'validate' => 'isBool'),
            'data_status'    => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'data_getcode'   => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'code'           => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'id_tempalte'    => array( 'type' => self::TYPE_INT, 'validate' => 'isUnsignedId'))
        );

    public function __construct($id_gabandoned_cart = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation('gabandoned_cart', array('type' => 'shop'));
        parent::__construct($id_gabandoned_cart, $id_lang, $id_shop);
        return true;
    }
}