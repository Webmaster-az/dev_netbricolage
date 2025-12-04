<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 *
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link         http://www.globosoftware.net
 */

class GpopupbarModel extends ObjectModel
{
    public $id_gabandoned_bar;
    public $active;
    public $position;
    public $delay;
    public $textcolor;
    public $backgroundcolor;
    public $title;
    public static $definition = array(
        'table'   => 'gabandoned_bar',
        'primary' => 'id_gabandoned_bar',
        'multilang' => true,
        'fields'    => array(
            'active'    => array('type' => self::TYPE_INT, 'size' => 1, 'validate' => 'isInt'),
            'position'  => array('type' => self::TYPE_INT, 'size' => 1, 'validate' => 'isInt'),
            'delay'     => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'textcolor' => array('type' => self::TYPE_STRING),
            'backgroundcolor'  => array('type' => self::TYPE_STRING),
            'title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isString',
                'required' => true),
            ),
        );

    public function __construct($id_gabandoned_bar = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation('gabandoned_bar', array('type' => 'shop'));
        parent::__construct($id_gabandoned_bar, $id_lang, $id_shop);
        return true;
    }
}
