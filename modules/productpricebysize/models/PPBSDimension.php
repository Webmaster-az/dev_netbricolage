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

class PPBSDimension extends ObjectModel
{
    /** @var integer Unique ID */
    public $id_ppbs_dimension;

    /** @var string Dimension Name */
    public $name;

    /** @var string Display name */
    public $display_name;

    /** @var string Image */
    public $image;

    /** @var integer display position */
    public $position;

    /**
     * @see ObjectModel::$definition
     */

    public static $definition = array(
        'table' => 'ppbs_dimension',
        'primary' => 'id_ppbs_dimension',
        'multilang' => true,
        'fields' => array(
            'name' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isMessage',
                'size' => 32,
                'required' => true
            ),
            'display_name' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isMessage',
                'size' => 32,
                'required' => true,
                'lang' => true
            ),
            'image' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isMessage',
                'size' => 255,
                'required' => false,
                'lang' => true
            ),
            'position' => array(
                'type' => self::TYPE_INT
            )
        )
    );

    public static function getDimensionDefinition($id_dimension, $id_lang = 1)
    {
        $sql = '
			SELECT
				ppbs_d.id_ppbs_dimension,
				ppbs_d.name,
				ppbs_d.position,
				ppbs_dl.display_name
			FROM `' . _DB_PREFIX_ . 'ppbs_dimension` ppbs_d
			JOIN `' . _DB_PREFIX_ . 'ppbs_dimension_lang` ppbs_dl ON (ppbs_d.id_ppbs_dimension = ppbs_dl.id_ppbs_dimension)
			WHERE ppbs_dl.`id_lang` = ' . (int)$id_lang . '
			AND ppbs_d.id_ppbs_unit = ' . (int)$id_dimension . '
			';
        $unit = Db::getInstance()->executeS($sql);

        if (is_array($unit)) {
            return $unit[0];
        } else {
            return false;
        }
    }

    public static function getDimensions($id_lang = 1)
    {
        $sql = '
			SELECT
				ppbs_d.id_ppbs_dimension,
				ppbs_d.name,
				ppbs_d.position,
				ppbs_dl.display_name
			FROM `' . _DB_PREFIX_ . 'ppbs_dimension` ppbs_d
			JOIN `' . _DB_PREFIX_ . 'ppbs_dimension_lang` ppbs_dl ON (ppbs_d.id_ppbs_dimension = ppbs_dl.id_ppbs_dimension)
			WHERE ppbs_dl.`id_lang` = ' . (int)$id_lang . '
			ORDER BY ppbs_d.position ASC';
        $results = Db::getInstance()->executeS($sql);

        if ($results) {
            return $results;
        } else {
            return false;
        }
    }

    public static function getDimensionsList($id_lang = 1)
    {
        $dimensions_collection = array(); //of TPPBSUnit
        $dimensions = self::getDimensions($id_lang);

        if (is_array($dimensions)) {
            foreach ($dimensions as $row) {
                $dimension = new stdClass();
                $dimension->id_ppbs_unit = $row['id_ppbs_unit'];
                $dimension->display_name = $row['display_name'];
                $dimension->name = $row['name'];
                $dimension->position = $row['position'];
                $dimension->translations = array();
                $dimensions_collection[] = $row;
            }
        }
        return $dimensions_collection;
    }

    public function getByName($name)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('ppbs_dimension');
        $sql->where('name LIKE "' . pSQL($name) . '"');
        $row = Db::getInstance()->getRow($sql);
        $this->hydrate($row);
    }

    /**
     * remove dimension and all relationships
     * @param $id
     */
    public static function deleteFull($id_ppbs_dimension)
    {
        $sql = '
			DELETE pf.*, pfo.*
			FROM ' . _DB_PREFIX_ . 'ppbs_product_field pf
			INNER JOIN ' . _DB_PREFIX_ . 'ppbs_product_field_option pfo ON pfo.id_ppbs_product_field = pf.id_ppbs_product_field
			WHERE pf.id_ppbs_dimension = ' . (int)$id_ppbs_dimension;

        $result = Db::getInstance()->execute($sql);

        if ($result) {
            DB::getInstance()->delete('ppbs_dimension', 'id_ppbs_dimension=' . (int)$id_ppbs_dimension);
            DB::getInstance()->delete('ppbs_dimension_lang', 'id_ppbs_dimension=' . (int)$id_ppbs_dimension);
        }
    }
}
