<?php
/**
* 2012-2018 Areama
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@areama.net so we can send you a copy immediately.
*
*  @author    Areama <contact@areama.net>
*  @copyright 2018 Areama
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Areama
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once dirname(__FILE__).'/ArLsfModel.php';

class ArLsfAddToCartConfigForm extends ArLsfModel
{
    public $enabled;
    public $fake;
    public $real;
    public $posibility;
    public $fake_guest;
    
    public $product;
    public $product_only;
    public $product_times;
    public $product_posibility;
    
    public $line1;
    public $line2;
    public $line3;
    public $line4;
    public $line5;
    public $name_placeholder;
    
    public $minimum_cost;
    public $in_stock;
    public $available_for_order;
    
    public function rules()
    {
        return array(
            array(
                array(
                    'enabled',
                    'minimum_cost',
                    'fake',
                    'real',
                    'in_stock',
                    'product',
                    'product_only',
                    'available_for_order'
                ), 'safe'
            ),
            array(
                array('minimum_cost', 'product_times'), 'isInt'
            ),
            array(
                array(
                    'posibility',
                    'fake_guest',
                    'product_posibility'
                ), 'integer', 'params' => array(
                    'min' => 0,
                    'max' => 100
                )
            ),
            array(
                array(
                    'line1',
                    'line2',
                    'line3',
                    'line4',
                    'line5',
                    'name_placeholder'
                ), 'isString'
            )
        );
    }
    
    public function fieldSuffix()
    {
        return array(
            'posibility' => '%',
            'fake_guest' => '%',
            'product_posibility' => '%'
        );
    }
    
    public function needToDisplay()
    {
        if (rand(0, 100) <= $this->posibility) {
            return true;
        }
        return false;
    }
    
    public function needToDisplayCurrentProduct()
    {
        if (rand(0, 100) <= $this->product_posibility) {
            return true;
        }
        return false;
    }
    
    public function attributeLabels()
    {
        return array(
            'enabled' => $this->l('Enabled', 'ArLsfAddToCartConfigForm'),
            'fake' => $this->l('Fake mode', 'ArLsfAddToCartConfigForm'),
            'real' => $this->l('Real mode', 'ArLsfAddToCartConfigForm'),
            'fake_guest' => $this->l('Fake popup guest probability', 'ArLsfAddToCartConfigForm'),
            'posibility' => $this->l('Fake popup probability', 'ArLsfAddToCartConfigForm'),
            'line1' => $this->l('Content line 1', 'ArLsfAddToCartConfigForm'),
            'line2' => $this->l('Content line 2', 'ArLsfAddToCartConfigForm'),
            'line3' => $this->l('Content line 3', 'ArLsfAddToCartConfigForm'),
            'line4' => $this->l('Content line 4', 'ArLsfAddToCartConfigForm'),
            'line5' => $this->l('Content line 5', 'ArLsfAddToCartConfigForm'),
            'name_placeholder' => $this->l('Name placeholder', 'ArLsfAddToCartConfigForm'),
            'minimum_cost' => $this->l('Minimum product cost', 'ArLsfAddToCartConfigForm'),
            'in_stock' => $this->l('Only products in stock', 'ArLsfAddToCartConfigForm'),
            'product' => $this->l('Display popup with current product', 'ArLsfAddToCartConfigForm'),
            'product_only' => $this->l('Display popup with current product only', 'ArLsfAddToCartConfigForm'),
            'product_times' => $this->l('Display popup with current product X times', 'ArLsfAddToCartConfigForm'),
            'product_posibility' => $this->l('Current product popup probability', 'ArLsfAddToCartConfigForm'),
            'available_for_order' => $this->l('Only products available for order', 'ArLsfAddToCartConfigForm')
        );
    }
    
    public function attributeHints()
    {
        return array(
            'product' => $this->l('If customer opens some product page then popup with this product will be shown.', 'ArLsfAddToCartConfigForm'),
            'product_only' => $this->l('Display popup with current product only. No other products will be displayed in popup.', 'ArLsfAddToCartConfigForm')
        );
    }
    
    public function attributeDescriptions()
    {
        return array(
            'name_placeholder' => $this->l('If product added by guest user tags firstname, lastname, firstname_lastname and lastname_firstname will be replaced by this value', 'ArLsfAddToCartConfigForm'),
            'product_times' => $this->l('Limit popup with current product displays count. Set to 0 to unlimited displays count.', 'ArLsfAddToCartConfigForm')
        );
    }
    
    public function attributeDefaults()
    {
        return array(
            'enabled' => Configuration::get('PS_CATALOG_MODE')? 0 : 1,
            'fake' => 1,
            'real' => 1,
            'product' => 1,
            'product_posibility' => 50,
            'product_times' => 3,
            'in_stock' => 1,
            'available_for_order' => 1,
            'fake_guest' => 80,
            'posibility' => 30,
            'line1' => $this->l('Someone add to cart', 'ArLsfAddToCartConfigForm'),
            'line2' => $this->l('{product_name_with_attrs}', 'ArLsfAddToCartConfigForm'),
            'line3' => $this->l('Price: {product_price}', 'ArLsfAddToCartConfigForm'),
            'line4' => $this->l('About {time_ago}', 'ArLsfAddToCartConfigForm'),
            'line5' => '',
            'minimum_cost' => '0',
            'name_placeholder' => $this->l('Someone', 'ArLsfAddToCartConfigForm'),
        );
    }
    
    public function attributeTypes()
    {
        return array(
            'product' => 'switch',
            'product_only' => 'switch',
            'enabled' => 'switch',
            'fake' => 'switch',
            'real' => 'switch',
            'in_stock' => 'switch',
            'available_for_order' => 'switch'
        );
    }
    
    public function multiLangFields()
    {
        return array(
            'line1' => true,
            'line2' => true,
            'line3' => true,
            'line4' => true,
            'line5' => true,
            'name_placeholder' => true
        );
    }
    
    public function getFormTitle()
    {
        return $this->l('Add to cart popup settings', 'ArLsfOrdersConfigForm');
    }
}
