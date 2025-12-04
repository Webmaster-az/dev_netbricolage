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

class ArLsfVisitorConfigForm extends ArLsfModel
{
    public $enabled;
    public $fake;
    public $fake_count;
    public $real;
    public $posibility;
    
    public $line1;
    public $line2;
    public $line3;
    public $line4;
    public $line5;
    
    public $in_stock;
    
    public function rules()
    {
        return array(
            array(
                array(
                    'enabled',
                    'fake',
                    'real',
                    'in_stock'
                ), 'safe'
            ),
            array(
                array(
                    'fake_count'
                ), 'interval', 'params' => array(
                    'min' => 1,
                    'max' => 100
                )
            ),
            array(
                array(
                    'posibility',
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
                    'line5'
                ), 'isString'
            )
        );
    }
    
    public function fieldSuffix()
    {
        return array(
            'posibility' => '%',
        );
    }
    
    public function needToDisplay()
    {
        if (rand(0, 100) <= $this->posibility) {
            return true;
        }
        return false;
    }
    
    public function attributeLabels()
    {
        return array(
            'enabled' => $this->l('Enabled', 'ArLsfVisitorConfigForm'),
            'fake' => $this->l('Fake mode', 'ArLsfVisitorConfigForm'),
            'fake_count' => $this->l('Fake visitor count', 'ArLsfVisitorConfigForm'),
            'real' => $this->l('Real mode', 'ArLsfVisitorConfigForm'),
            'posibility' => $this->l('Fake popup probability', 'ArLsfVisitorConfigForm'),
            'line1' => $this->l('Content line 1', 'ArLsfVisitorConfigForm'),
            'line2' => $this->l('Content line 2', 'ArLsfVisitorConfigForm'),
            'line3' => $this->l('Content line 3', 'ArLsfVisitorConfigForm'),
            'line4' => $this->l('Content line 4', 'ArLsfVisitorConfigForm'),
            'line5' => $this->l('Content line 5', 'ArLsfVisitorConfigForm'),
            'in_stock' => $this->l('Only products in stock', 'ArLsfVisitorConfigForm'),
        );
    }
    
    public function attributeDescriptions()
    {
        return array(
            'in_stock' => $this->l('Display popup only if current product has qty > 0', 'ArLsfVisitorConfigForm'),
        );
    }
    
    public function attributeDefaults()
    {
        return array(
            'enabled' => 1,
            'fake' => 1,
            'fake_count' => '2-6',
            'real' => 1,
            'in_stock' => 1,
            'posibility' => 60,
            'line1' => $this->l('{product_name}', 'ArLsfVisitorConfigForm'),
            'line2' => $this->l('This product currently viewed', 'ArLsfVisitorConfigForm'),
            'line3' => $this->l('by {visitors} visitors', 'ArLsfVisitorConfigForm'),
            'line4' => '',
            'line5' => '',
        );
    }
    
    public function attributeTypes()
    {
        return array(
            'enabled' => 'switch',
            'fake' => 'switch',
            'real' => 'switch',
            'in_stock' => 'switch'
        );
    }
    
    public function multiLangFields()
    {
        return array(
            'line1' => true,
            'line2' => true,
            'line3' => true,
            'line4' => true,
            'line5' => true
        );
    }
    
    public function getFormTitle()
    {
        return $this->l('Viewers count popup settings', 'ArLsfOrdersConfigForm');
    }
}
