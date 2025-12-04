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

class PPBSProductField extends ObjectModel
{
    /** @var integer Unique ID */
    public $id_ppbs_product_field;

    /** @var integer Dimension ID */
    public $id_ppbs_dimension;

    /** @var integer Dimension ID */
    public $id_ppbs_unit;

    /** @var integer Product ID */
    public $id_product;

    /** @var Float Min val */
    public $min;

    /** @var Float Max val */
    public $max;

    /** @var string default value/text */
    public $default;

    /** @var integer Ratio Scale Value */
    public $ratio;

    /** @var integer Step increment Value */
    public $step;

    /** @var string Input type */
    public $input_type;

    /** @var string Position */
    public $decimals;

    /** @var integer DisplaySuffix */
    public $display_suffix;

    /** @var string Position */
    public $position;

    /** @var integer Visible */
    public $visible;

    /**
     * @see ObjectModel::$definition
     */

    public static $definition = array(
        'table' => 'ppbs_product_field',
        'primary' => 'id_ppbs_product_field',
        'fields' => array(
            'id_ppbs_dimension' => array('type' => self::TYPE_INT),
            'id_ppbs_unit' => array(
                'type' => self::TYPE_INT,
            ),
            'id_ppbs_unit' => array(
                'type' => self::TYPE_INT,
            ),
            'id_product' => array(
                'type' => self::TYPE_INT,
            ),
            'visible' => array(
                'type' => self::TYPE_INT,
            ),
            'min' => array(
                'type' => self::TYPE_FLOAT,
            ),
            'max' => array(
                'type' => self::TYPE_FLOAT,
            ),
            'default' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isMessage',
                'size' => 255,
                'required' => true
            ),
            'ratio' => array('type' => self::TYPE_FLOAT),
            'step' => array('type' => self::TYPE_FLOAT),
            'decimals' => array('type' => self::TYPE_INT),
            'display_suffix' => array('type' => self::TYPE_INT),
            'position' => array('type' => self::TYPE_INT),
            'input_type' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isMessage',
                'size' => 255,
                'required' => true
            )
        )
    );

    public static function getCollectionByProduct($id_product, $id_lang = 1, $visible_only = 0)
    {
        $sql = new DbQuery();
        $sql->select('
			pd.name AS dimension_name,
			pu.name AS unit_name,
			pf.id_ppbs_product_field,
			pf.id_ppbs_dimension,
			pf.id_ppbs_unit,
			pf.input_type,
			pf.visible,
			pf.default,
			pf.ratio,
			pf.step,
			pf.decimals,
			pf.display_suffix,
			pf.min,
			pf.max,
			pdl.display_name,
			pdl.image
		');
        $sql->from('ppbs_product_field', 'pf');
        $sql->innerJoin('ppbs_dimension', 'pd', 'pd.id_ppbs_dimension = pf.id_ppbs_dimension');
        $sql->innerJoin('ppbs_dimension_lang', 'pdl', 'pf.id_ppbs_dimension = pdl.id_ppbs_dimension AND pdl.id_lang=' . (int)$id_lang);
        $sql->innerJoin('ppbs_unit', 'pu', 'pu.id_ppbs_unit = pf.id_ppbs_unit');
        $sql->where('pf.id_product = ' . (int)$id_product);
        if ($visible_only) {
            $sql->where('pf.visible = 1');
        }
        $sql->orderBy('pf.position');
        return Db::getInstance()->executeS($sql);
    }

    /**
     * @param $id_product
     * @return array|false|mysqli_result|null|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public static function getRawCollectionByProduct($id_product)
    {
        $sql = new DbQuery();
        $sql->select('pf.*');
        $sql->from('ppbs_product_field', 'pf');
        $sql->where('pf.id_product = ' . (int)$id_product);
        $sql->orderBy('pf.position');
        return Db::getInstance()->executeS($sql);
    }

    public function loadProductField($id_product, $id_ppbs_dimension, $id_shop)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('ppbs_product_field', 'pf');
        $sql->where('pf.id_product = ' . (int)$id_product);
        $sql->where('pf.id_ppbs_dimension = ' . (int)$id_ppbs_dimension);
        $row = Db::getInstance()->getRow($sql);
        $this->hydrate($row);
    }

    public static function updatePosition($id_ppbs_product_field, $position)
    {
        DB::getInstance()->update('ppbs_product_field', array(
            'position' => (int)$position
        ), 'id_ppbs_product_field =' . (int)$id_ppbs_product_field);
    }

    /**
     * Delete entries by product ID
     * @param $id_product
     */
    public static function deleteByProduct($id_product)
    {
        DB::getInstance()->delete(self::$definition['table'], 'id_product = ' . (int)$id_product);
    }
}
