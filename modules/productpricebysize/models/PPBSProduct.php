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

class PPBSProduct extends ObjectModel
{
    /** @var integer Unique ID */
    public $id_ppbs_product;

    /** @var integer Product ID */
    public $id_product;

    /** @var integer default unit */
    public $id_ppbs_unit_default;

    /** @var boolean enabled val */
    public $enabled = 0;

    /** @var boolean Front Conversion enabled val */
    public $front_conversion_enabled = 0;

    /** @var string Front Conversion Operator val */
    public $front_conversion_operator = '';

    /** @var boolean Front Conversion Value val */
    public $front_conversion_value = 0;

    /** @var boolean Attribute Price as Area Price val */
    public $attribute_price_as_area_price = 0;

    /** @var float Min Price val */
    public $min_price = 0;

    /** @var float Min Total Area val */
    public $min_total_area = 0;

    /** @var float Prduct Setup Fee val */
    public $setup_fee = 0;

    /** @var Int Equation Enabled val */
    public $equation_enabled = 0;

    /** @var Str Equation val */
    public $equation = '';

    /** @var Int Equation Enabled val */
    public $weight_calculation_enabled = 0;

    /** @var Str Weight calculation */
    public $weight_calculation = '';

    /** @var boolean Stock Management Enabled */
    public $stock_enabled = 0;

    /**
     * @see ObjectModel::$definition
     */

    public static $definition = array(
        'table' => 'ppbs_product',
        'primary' => 'id_ppbs_product',
        'fields' => array(
            'id_product' => array('type' => self::TYPE_INT, 'required' => true),
            'id_ppbs_unit_default' => array('type' => self::TYPE_INT, 'required' => false),
            'enabled' => array('type' => self::TYPE_INT),
            'front_conversion_enabled' => array('type' => self::TYPE_INT),
            'front_conversion_operator' => array('type' => self::TYPE_STRING),
            'front_conversion_value' => array('type' => self::TYPE_INT),
            'attribute_price_as_area_price' => array('type' => self::TYPE_INT),
            'min_price' => array('type' => self::TYPE_FLOAT),
            'min_total_area' => array('type' => self::TYPE_FLOAT),
            'setup_fee' => array('type' => self::TYPE_FLOAT),
            'equation_enabled' => array('type' => self::TYPE_INT),
            'equation' => array('type' => self::TYPE_STRING),
            'weight_calculation_enabled' => array('type' => self::TYPE_INT),
            'weight_calculation' => array('type' => self::TYPE_INT),
            'stock_enabled' => array('type' => self::TYPE_STRING)
        )
    );

    /**
     * Load by Product ID
     * @param $id_product
     */
    public function loadByProduct($id_product)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('ppbs_product');
        $sql->where('id_product = ' . (int)$id_product);
        $row = Db::getInstance()->getRow($sql);
        if (!empty($row)) {
            $this->hydrate($row);
        }
    }

    public function getByProduct($id_product)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('ppbs_product', 'p');
        $sql->where('p.id_product = ' . (int)$id_product);
        $row = Db::getInstance()->getRow($sql);

        if (!empty($row['id_ppbs_product'])) {
            $this->id = $row['id_ppbs_product'];
            $this->id_ppbs_product = $row['id_ppbs_product'];
            $this->id_ppbs_unit_default = $row['id_ppbs_unit_default'];
            $this->id_product = $row['id_product'];
            $this->enabled = $row['enabled'];
            $this->attribute_price_as_area_price = $row['attribute_price_as_area_price'];
            $this->front_conversion_enabled = $row['front_conversion_enabled'];
            $this->front_conversion_operator = $row['front_conversion_operator'];
            $this->front_conversion_value = $row['front_conversion_value'];
            $this->min_price = $row['min_price'];
            $this->min_total_area = $row['min_total_area'];
            $this->setup_fee = $row['setup_fee'];
            $this->equation_enabled = $row['equation_enabled'];
            $this->equation = $row['equation'];
            $this->weight_calculation_enabled = $row['weight_calculation_enabled'];
            $this->weight_calculation = $row['weight_calculation'];
            $this->stock_enabled = $row['stock_enabled'];
        }
    }

    /**
     * creates the product customization entry for the product
     * @param $id_product
     * @param $ppbs_enabled
     * @param $id_shop
     * @throws PrestaShopDatabaseException
     */
    public static function createProductCustomization($id_product, $ppbs_enabled, $id_shop)
    {
        if ($ppbs_enabled) {
            Configuration::updateValue('PS_CUSTOMIZATION_FEATURE_ACTIVE', '1');
        }

        $sql = 'SELECT id_customization_field FROM ' . _DB_PREFIX_ . 'customization_field
					WHERE id_product = ' . (int)$id_product . '
					AND ppbs = 1';
        $row = DB::getInstance()->getRow($sql);
        if (isset($row['id_customization_field'])) {
            $id_customization_field = $row['id_customization_field'];
            $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'customization_field
					WHERE id_customization_field = ' . (int)$id_customization_field;

            DB::getInstance()->execute($sql);

            $id_customization_field = $row['id_customization_field'];
            $sql = 'DELETE FROM ' . _DB_PREFIX_ . 'customization_field_lang
					WHERE id_customization_field = ' . (int)$id_customization_field;
            DB::getInstance()->execute($sql);
        }

        if ($ppbs_enabled) {
            Db::getInstance()->insert('customization_field', array(
                'id_product' => (int)$id_product,
                'type' => 1,
                'required' => 0,
                'ppbs' => 1,
            ));
            $id_customization_field_new = Db::getInstance()->Insert_ID();

            $languages = Language::getLanguages();
            $translations = PPBSTranslation::loadTranslations(Context::getContext()->shop->id);

            foreach ($languages as $language) {
                $dimension = $translations['cart_label'][$language['id_lang']];
                Db::getInstance()->insert('customization_field_lang', array(
                    'id_customization_field' => (int)$id_customization_field_new,
                    'id_lang' => (int)$language['id_lang'],
                    'name' => $dimension
                ));
            }

            Db::getInstance()->update(
                'customized_data',
                array(
                    'index' => (int)$id_customization_field_new
                ),
                '`index`=' . (int)$id_customization_field
            );

            DB::getInstance()->update(
                'product',
                array(
                    'customizable' => '1',
                    'text_fields' => 1
                ),
                'id_product = ' . (int)$id_product
            );

            DB::getInstance()->update(
                'product_shop',
                array(
                    'customizable' => '1',
                    'text_fields' => 1
                ),
                'id_product = ' . (int)$id_product . ' AND id_shop=' . (int)$id_shop
            );
        } else {
            DB::getInstance()->update(
                'product',
                array(
                    'customizable' => '0',
                    'text_fields' => 1
                ),
                'id_product = ' . (int)$id_product
            );
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
