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

class PPBSSchema
{
    private $schema = array();

    public function __construct()
    {
        $this->schema = array(
            'ppbs_area_price' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_area_price` (
                    `id_area_price` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `id_shop` int(10) unsigned DEFAULT \'1\',
                    `id_product` int(10) unsigned DEFAULT NULL,
                    `area_low` decimal(10,2) unsigned DEFAULT \'0.00\',
                    `area_high` decimal(10,2) unsigned DEFAULT \'0.00\',
                    `impact` char(6) DEFAULT \'+\',
                    `price` decimal(10,6) unsigned DEFAULT \'0.000000\',
                    `weight` decimal(10,6) unsigned DEFAULT \'0.000000\',
                PRIMARY KEY (`id_area_price`)                
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;            
            '
            ),
            'ppbs_areapricesuffix' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_areapricesuffix` (
                    `id_ppbs_areapricesuffix` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `name` varchar(32) DEFAULT NULL,
                PRIMARY KEY (`id_ppbs_areapricesuffix`)                
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;            
            '
            ),
            'ppbs_areapricesuffix_lang' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_areapricesuffix_lang` (
                    `id_ppbs_areapricesuffix_lang` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `id_ppbs_areapricesuffix` int(10) unsigned NOT NULL,
                    `id_lang` int(10) unsigned NOT NULL,
                    `text` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id_ppbs_areapricesuffix_lang`)                
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;                
            '
            ),
            'ppbs_dimension' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_dimension` (
                    `id_ppbs_dimension` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `name` varchar(32) NOT NULL,                                        
                    `position` int(10) unsigned NOT NULL DEFAULT \'0\',
                PRIMARY KEY (`id_ppbs_dimension`)                
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;                
            '
            ),
            'ppbs_dimension_lang' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_dimension_lang` (
                    `id_ppbs_dimension` int(10) unsigned NOT NULL,
                    `id_lang` int(10) unsigned NOT NULL,
                    `display_name` varchar(128) NOT NULL,
                    `image` varchar(512) NOT NULL,                    
                PRIMARY KEY (`id_ppbs_dimension`,`id_lang`)                		
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;            
            '
            ),
            'ppbs_equation' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_equation` (
					`id_equation` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
					`id_equation_template` mediumint(8) unsigned NOT NULL,
                    `id_product` int(10) unsigned NOT NULL,
                    `ipa` int(10) unsigned NOT NULL,
                    `equation` varchar(512) NOT NULL,
                    `equation_type` varchar(16) NOT NULL,                    
                PRIMARY KEY (`id_equation`)                
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;            
            '
            ),
            'ppbs_equation_template' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_equation_template` (
                    `id_equation_template` int(11) unsigned NOT NULL AUTO_INCREMENT,
                    `name` varchar(128) NOT NULL,
                    `equation` text NOT NULL,                
                PRIMARY KEY (`id_equation_template`)                
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;            
            '
            ),
            'ppbs_equation_var' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_equation_var` (
                    `id_ppbs_equation_var` int(11) unsigned NOT NULL AUTO_INCREMENT,
                    `name` varchar(20) NOT NULL,
                    `value` decimal(8,4) NOT NULL DEFAULT \'0.00\',                
                PRIMARY KEY (`id_ppbs_equation_var`)                
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;            
            '
            ),
            'ppbs_product' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_product` (
                    `id_ppbs_product` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `id_product` int(10) unsigned NOT NULL,
                    `id_ppbs_unit_default` int(10) unsigned NOT NULL DEFAULT \'0\',
                    `enabled` tinyint(3) unsigned NOT NULL,
                    `front_conversion_enabled` tinyint(3) unsigned NOT NULL DEFAULT \'0\',
                    `front_conversion_operator` varchar(3) NOT NULL,
                    `front_conversion_value` decimal(15,2) NOT NULL,
                    `attribute_price_as_area_price` tinyint(3) NOT NULL DEFAULT \'0\',
                    `min_price` decimal(15,2) NOT NULL DEFAULT \'0.00\',
                    `min_total_area` decimal(10,2) NOT NULL DEFAULT \'0.00\',
                    `setup_fee` decimal(15,2) NOT NULL DEFAULT \'0.00\',
                    `equation_enabled` tinyint(3) unsigned NOT NULL DEFAULT \'0\',
                    `equation` varchar(512) NOT NULL,
                    `weight_calculation_enabled` tinyint(3) unsigned NOT NULL DEFAULT \'0\',
                    `weight_calculation` varchar(512) NOT NULL,                    
                    `stock_enabled` tinyint(3) unsigned NOT NULL,                    
                PRIMARY KEY (`id_ppbs_product`)                
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;            
            '
            ),
            'ppbs_product_field' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_product_field` (
                    `id_ppbs_product_field` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `id_ppbs_dimension` int(10) unsigned NOT NULL,
                    `id_product` int(10) unsigned NOT NULL,
                    `id_ppbs_unit` tinyint(3) unsigned NOT NULL DEFAULT \'0\',
                    `min` DECIMAL(10,2) NOT NULL DEFAULT \'0\',
                    `max` DECIMAL(10,2) NOT NULL DEFAULT \'0\',
                    `default` DECIMAL(10,2) NOT NULL DEFAULT \'0\',
                    `ratio` DECIMAL(8,2) unsigned NOT NULL DEFAULT \'0\',
                    `step` DECIMAL(8,2) unsigned NOT NULL DEFAULT \'0\',
                    `input_type` varchar(12) NOT NULL DEFAULT \'textbox\',
                    `visible` tinyint(3) unsigned NOT NULL,
                    `decimals` smallint(5) unsigned NOT NULL DEFAULT \'0\',                    
                    `display_suffix` tinyint(3) unsigned NOT NULL DEFAULT \'0\',
                    `position` smallint(5) unsigned NOT NULL DEFAULT \'0\',
                PRIMARY KEY (`id_ppbs_product_field`)
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;            
            '
            ),
            'ppbs_product_field_option' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_product_field_option` (
                    `id_option` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `id_ppbs_product_field` int(10) unsigned NOT NULL,
                    `text` varchar(255) NOT NULL,
                    `value` varchar(255) NOT NULL,
                    `position` mediumint(8) unsigned NOT NULL DEFAULT \'0\',
                PRIMARY KEY (`id_option`)                
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;            
            '
            ),
            'ppbs_translations' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_translations` (
                    `id_translation` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `id_language` int(10) unsigned NOT NULL,
                    `id_shop` int(10) unsigned NOT NULL,
                    `name` varchar(255) NOT NULL,
                    `text` text NOT NULL,
                PRIMARY KEY (`id_translation`)                
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;            
            '
            ),
            'ppbs_unit' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_unit` (
                    `id_ppbs_unit` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL,
                    `conversion_factor` decimal(8,2) unsigned DEFAULT \'0.00\',                    
                    `position` int(10) unsigned DEFAULT \'0.00\',
                PRIMARY KEY (`id_ppbs_unit`)                
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;            
            '
            ),
            'ppbs_unit_lang' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_unit_lang` (
                    `id_ppbs_unit` int(10) unsigned NOT NULL,
                    `id_lang` smallint(5) unsigned NOT NULL,
                    `symbol` varchar(32) NOT NULL,
                PRIMARY KEY (`id_ppbs_unit`,`id_lang`)                
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;            
            '),
            'ppbs_product_unit_conversion' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_product_unit_conversion` (
                    `id_ppbs_product_unit_conversion` int(10) unsigned NOT NULL AUTO_INCREMENT,
                    `id_product` int(10) unsigned DEFAULT \'0\',
                    `id_ppbs_unit` int(10) unsigned NOT NULL,
                    `default` smallint(3) unsigned DEFAULT \'0\',
                    `position` int(10) unsigned DEFAULT \'0\',
                PRIMARY KEY (`id_ppbs_product_unit_conversion`)
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;
            '),
            'ppbs_stock' => array(
                'ddl' => '
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ppbs_stock` (
                    `id_ppbs_stock` int(11) unsigned NOT NULL AUTO_INCREMENT,
                    `id_product` int(10) unsigned NOT NULL,
                    `id_product_attribute` int(10) unsigned NOT NULL,
                    `id_shop` mediumint(8) unsigned NOT NULL,
                    `qty_stock` decimal(6,2) unsigned NOT NULL,
                PRIMARY KEY (`id_ppbs_stock`)                
                )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;            
            ')
        );
    }

    /**
     * @param $table_name
     */
    public function getTableDDL($table_name)
    {
        return $this->schema[$table_name]['ddl'];
    }
}
