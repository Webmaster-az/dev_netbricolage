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

class PPBSInstall extends PPBSInstallCore
{
    public static function createTableFromSchema($table_name)
    {
        $schema = new PPBSSchema();
        $return = true;
        $return &= Db::getInstance()->execute($schema->getTableDDL($table_name));
        return $return;
    }

    public static function installDb()
    {
        $return = true;

        $return &= self::createTableFromSchema('ppbs_area_price');
        $return &= self::createTableFromSchema('ppbs_areapricesuffix');
        $return &= self::createTableFromSchema('ppbs_areapricesuffix_lang');
        $return &= self::createTableFromSchema('ppbs_dimension');
        $return &= self::createTableFromSchema('ppbs_dimension_lang');
        $return &= self::createTableFromSchema('ppbs_equation');
        $return &= self::createTableFromSchema('ppbs_equation_template');
        $return &= self::createTableFromSchema('ppbs_equation_var');
        $return &= self::createTableFromSchema('ppbs_product');
        $return &= self::createTableFromSchema('ppbs_product_field');
        $return &= self::createTableFromSchema('ppbs_product_field_option');
        $return &= self::createTableFromSchema('ppbs_translations');
        $return &= self::createTableFromSchema('ppbs_unit');
        $return &= self::createTableFromSchema('ppbs_unit_lang');
        $return &= self::createTableFromSchema('ppbs_stock');
        $return &= self::createTableFromSchema('ppbs_product_unit_conversion');

        self::addColumn('cart_product', 'ppbs', 'SMALLINT UNSIGNED DEFAULT 0');
        self::addColumn('customized_data', 'ppbs_dimensions', 'TEXT');
        self::addColumn('customization_field', 'ppbs', 'tinyint(1) UNSIGNED NOT NULL');

        return $return;
    }

    public static function uninstall()
    {
        self::dropTable('ppbs_area_price');
        self::dropTable('ppbs_areapricesuffix');
        self::dropTable('ppbs_areapricesuffix_lang');
        self::dropTable('ppbs_dimension');
        self::dropTable('ppbs_dimension_lang');
        self::dropTable('ppbs_equation');
        self::dropTable('ppbs_equation_template');
        self::dropTable('ppbs_product');
        self::dropTable('ppbs_product_field');
        self::dropTable('ppbs_product_field_option');
        self::dropTable('ppbs_translations');
        self::dropTable('ppbs_unit');
        self::dropTable('ppbs_unit_lang');
        self::dropTable('ppbs_stock');
        self::dropTable('ppbs_product_unit_conversion');
    }

    public static function addColumn($table, $name, $type)
    {
        try {
            $return = Db::getInstance()->execute('ALTER TABLE  `' . _DB_PREFIX_ . bqSQL($table) . '` ADD  `' . bqSQL($name) . '` ' . bqSQL($type));
        } catch (Exception $e) {
            $return = true;
        }
    }

    public static function _addTranslation($name, $text, $id_lang, $id_shop)
    {
        $sql = 'SELECT COUNT(*) AS total_count
				FROM ' . _DB_PREFIX_ . 'ppbs_translations
				WHERE id_shop = ' . (int)$id_shop . '
				AND id_language = ' . (int)$id_lang . '
				AND name LIKE "' . pSQL($name) . '"
				';
        $result = Db::getInstance()->executeS($sql);
        if ($result && $result[0]['total_count'] == 0) {
            Db::getInstance()->insert('ppbs_translations', array(
                'id_shop' => (int)$id_shop,
                'id_language' => (int)$id_lang,
                'name' => pSQL($name),
                'text' => pSQL($text),
            ));
        }
    }

    /**
     * @param $name
     * @param $symbol
     * @param $conversion_factor
     * @param $id_lang
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    private static function _addUnit($name, $symbol, $conversion_factor, $id_lang)
    {
        $ppbs_unit = new PPBSUnit();
        $ppbs_unit->getByName($name);

        if (empty($ppbs_unit->id_ppbs_unit)) {
            $ppbs_unit->name = pSQL($name);
            $ppbs_unit->symbol[$id_lang] = pSQL($symbol);
            $ppbs_unit->conversion_factor = (float)$conversion_factor;
            $ppbs_unit->add(false);
        }
    }

    private static function _addDimension($name, $display_name)
    {
        $ppbs_dimension = new PPBSDimension();
        $languages = Language::getLanguages();

        if (empty($ppbs_dimension->id_ppbs_dimension)) {
            $ppbs_dimension->name = pSQL($name);
            foreach ($languages as $language) {
                $ppbs_dimension->display_name[$language['id_lang']] = pSQL($display_name);
            }
            $ppbs_dimension->add(false);
        }
    }

    public static function installData()
    {
        /* Install global module translations */
        $languages = Language::getLanguages();
        $shops = ShopCore::getCompleteListOfShopsID();
        foreach ($shops as $id_shop) {
            foreach ($languages as $language) {
                self::_addTranslation('min_max_error', '{min} - {max}', $language['id_lang'], $id_shop);
                self::_addTranslation('generic_error', 'check dimensions above', $language['id_lang'], $id_shop);
                self::_addTranslation('unit_price_suffix', 'per m2', $language['id_lang'], $id_shop);
                self::_addTranslation('cart_label', 'dimensions2', $language['id_lang'], $id_shop);
            }
        }

        /* Add the Generic Dimensions */
        self::_addDimension('height', 'Height');
        self::_addDimension('width', 'Width');
        self::_addDimension('depth', 'Depth');

        /* Install sample dimensions */
        foreach ($shops as $id_shop) {
            foreach ($languages as $language) {
                self::_addUnit('millimeter', 'mm', 1, $language['id_lang']);
                self::_addUnit('centimeter', 'cm', 10, $language['id_lang']);
                self::_addUnit('meter', 'm', 1000, $language['id_lang']);
                self::_addUnit('inch', 'inch', 25.40, $language['id_lang']);
                self::_addUnit('foot', 'ft', 304.80, $language['id_lang']);
            }
        }
        return true;
    }
}
