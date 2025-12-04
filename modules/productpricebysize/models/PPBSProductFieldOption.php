<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Musaffar Patel
 * @copyright 2016-2021 Musaffar Patel
 * @license   LICENSE.txt
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class PPBSProductFieldOption extends ObjectModel
{
    /** @var integer Unique ID */
    public $id_option;


    /** @var integer Product Unit ID */
    public $id_ppbs_product_field;

    /** @var string Option text */
    public $text;

    /** @var string Option value */
    public $value;

    /** @var string Option value */
    public $position;

    /**
     * @see ObjectModel::$definition
     */

    public static $definition = array(
        'table' => 'ppbs_product_field_option',
        'primary' => 'id_option',
        'fields' => array(
            'id_ppbs_product_field' => array('type' => self::TYPE_INT),
            'text' => array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'size' => 255, 'required' => true),
            'value' => array('type' => self::TYPE_STRING, 'validate' => 'isMessage', 'size' => 255, 'required' => true),
            'position' => array('type' => self::TYPE_INT)
        )
    );

    public static function deleteAllByProductFieldID($id_ppbs_product_field)
    {
        DB::getInstance()->delete(self::$definition['table'], 'id_ppbs_product_field=' . (int)$id_ppbs_product_field);
    }

    public static function getFieldOptions($id_ppbs_product_field)
    {
        $sql = new DbQuery();
        $sql->select('value, text, position');
        $sql->from(self::$definition['table']);
        $sql->where('id_ppbs_product_field = ' . (int)$id_ppbs_product_field);
        $sql->orderBy('position');
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }
}
