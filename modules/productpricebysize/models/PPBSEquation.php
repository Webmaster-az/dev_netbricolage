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

class PPBSEquation extends ObjectModel
{
    /** @var integer Unique ID */
    public $id_equation;

    /** @var integer Product ID */
    public $id_product;

    /** @var integer Equation Template ID */
    public $id_equation_template;

    /** @var integer Combination ID */
    public $ipa;

    /** @var string Equation */
    public $equation;

    /** @var string Equation type */
    public $equation_type;


    /**
     * @see ObjectModel::$definition
     */

    public static $definition = array(
        'table' => 'ppbs_equation',
        'primary' => 'id_equation',
        'fields' => array(
            'id_equation_template' => array('type' => self::TYPE_INT, 'required' => true),
            'id_product' => array('type' => self::TYPE_INT, 'required' => true),
            'ipa' => array('type' => self::TYPE_INT, 'required' => true),
            'equation' => array('type' => self::TYPE_STRING),
            'equation_type' => array('type' => self::TYPE_STRING)
        )
    );

    public function getByProduct($id_product, $ipa, $fallback = false)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('ppbs_equation');
        $sql->where('id_product = ' . (int)$id_product);
        $sql->where('ipa= ' . (int)$ipa);
        $row = Db::getInstance()->getRow($sql);

        if ((empty($row)) && $ipa > 0) {
            $sql = new DbQuery();
            $sql->select('*');
            $sql->from('ppbs_equation');
            $sql->where('id_product = ' . (int)$id_product);
            $sql->where('ipa = 0');
            $row = Db::getInstance()->getRow($sql);
        }

        if (!$row) {
            $row = array();
        }

        $this->hydrate($row);
    }

    /**
     * Get equation byu product and product IPA
     * @param $id_product
     * @param $ipa
     */
    public function getByProductIPA($id_product, $ipa)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('ppbs_equation');
        $sql->where('id_product = ' . (int)$id_product);
        $sql->where('ipa= ' . (int)$ipa);
        $row = Db::getInstance()->getRow($sql);

        if (!$row) {
            $row = array();
        }
        $this->hydrate($row);
    }


    public function getAllByProduct($id_product)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('ppbs_equation');
        $sql->where('id_product = ' . (int)$id_product);
        $results = Db::getInstance()->executeS($sql);

        if (!$results) {
            $results = array();
        }

        return $this->hydrateCollection('PPBSEquation', $results);
    }

    /**
     * Save the eqaution
     * @param $id_product
     * @param $id_product_attribute
     * @param $equation
     * @param $equation_type
     * @return bool
     * @throws PrestaShopDatabaseException
     */
    public function saveEquation($id_product, $id_product_attribute, $equation, $equation_type)
    {
        $action = '';
        $this->getByProduct($id_product, $id_product_attribute);

        if ($equation == '') {
            DB::getInstance()->delete('ppbs_equation', 'id_product = ' . (int)$id_product . ' AND ipa = ' . (int)$id_product_attribute.' AND equation_type = "' . $equation_type . '"');
            return true;
        }

        if ((int)$this->id_equation == 0) {
            $action = 'insert';
        } else {
            if ($this->ipa == 0 && $id_product_attribute > 0) {
                $action = 'insert';
            } else {
                $action = 'update';
            }
        }

        switch ($action) {
            case 'insert':
                DB::getInstance()->insert('ppbs_equation', array(
                    'id_product' => (int)$id_product,
                    'ipa' => (int)$id_product_attribute,
                    'equation' => pSQL($equation, true),
                    'equation_type' => pSQL($equation_type, true)
                ));
                break;
            case 'update':
                DB::getInstance()->update('ppbs_equation', array(
                    'equation' => pSQL($equation, true)
                ), 'id_product=' . (int)$id_product . ' AND ipa =' . (int)$id_product_attribute.' AND equation~_type = "'.pSQL($equation_type).'"');
                break;
        }
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
