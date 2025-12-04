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

class PPBSAreaPrice extends ObjectModel
{
    /** @var integer Unique ID */
    public $id_area_price;

    /** @var integer Product ID */
    public $id_product;

    /** @var integer Shop ID */
    public $id_shop;

    /** @var float Area_Low val */
    public $area_low;

    /** @var float Area_Low val */
    public $area_high;

    /** @var string Impact val */
    public $impact;

    /** @var float Price val */
    public $price;

    /** @var float Weight val */
    public $weight;

    /**
     * @see ObjectModel::$definition
     */

    public static $definition = array(
        'table' => 'ppbs_area_price',
        'primary' => 'id_area_price',
        'fields' => array(
            'id_product' => array(
                'type' => self::TYPE_INT,
                'required' => true
            ),
            'id_shop' => array(
                'type' => self::TYPE_INT,
                'required' => true
            ),
            'area_low' => array(
                'type' => self::TYPE_FLOAT,
                'required' => true
            ),
            'area_high' => array(
                'type' => self::TYPE_FLOAT,
                'required' => true
            ),
            'impact' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'price' => array(
                'type' => self::TYPE_FLOAT
            ),
            'weight' => array(
                'type' => self::TYPE_FLOAT
            )
        )
    );

    public static function getCollectionByProduct($id_product, $id_shop)
    {
        $sql = new DbQuery();
        $sql->select('
			ap.id_area_price,
			ap.id_product,
			ap.id_shop,
			ap.area_low,
			ap.area_high,
			ap.impact,
			ap.price,
			ap.weight
		');
        $sql->from('ppbs_area_price', 'ap');
        $sql->where('ap.id_product = ' . (int)$id_product);
        $sql->where('ap.id_shop = ' . (int)$id_shop);
        $sql->orderBy('ap.area_low');
        return Db::getInstance()->executeS($sql);
    }

    /**
     * @param $id_product
     * @param int $id_shop
     * @return array|bool
     * @throws PrestaShopDatabaseException
     */
    public static function getAreaPrices($id_product, $id_shop = 1)
    {
        $areaPriceCollection = array();
        $sql = 'SELECT
					id_area_price,
					id_product,
					id_shop,
					area_low,
					area_high,
					impact,
					price
				FROM ' . _DB_PREFIX_ . 'ppbs_area_price
				WHERE id_product = ' . (int)$id_product . '
				AND id_shop = ' . (int)$id_shop . '
				ORDER BY (area_low * area_high)';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        return self::hydrateCollection('PPBSAreaPrice', $result);
    }

    /**
     * Delete entries by product ID
     * @param $id_product
     */
    public static function deleteByProduct($id_product)
    {
        DB::getInstance()->delete(self::$definition['table'], 'id_product = '.(int)$id_product);
    }
}
