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

class PPBSStockModel extends ObjectModel
{
    /** @var integer Unique ID */
    public $id_ppbs_stock;

    /** @var integer Product ID */
    public $id_product;

    /** @var integer IPA ID */
    public $id_product_attribute;

    /** @var Integer Shop ID */
    public $id_shop;

    /** @var float Stock Quantity */
    public $qty_stock;

    /**
     * @see ObjectModel::$definition
     */

    public static $definition = array(
        'table' => 'ppbs_stock',
        'primary' => 'id_ppbs_stock',
        'fields' => array(
            'id_product' => array(
                'type' => self::TYPE_INT,
                'required' => true
            ),
            'id_product_attribute' => array(
                'type' => self::TYPE_INT,
                'required' => true
            ),
            'id_shop' => array(
                'type' => self::TYPE_INT,
                'required' => true
            ),
            'qty_stock' => array(
                'type' => self::TYPE_FLOAT,
                'required' => true
            ),
        )
    );
}
