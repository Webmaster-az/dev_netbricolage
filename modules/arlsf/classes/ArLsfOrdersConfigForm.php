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

class ArLsfOrdersConfigForm extends ArLsfModel
{
    public $enabled;
    public $fake;
    public $real;
    public $posibility;
    public $fake_items;
    public $fake_date;
    public $date_unit;
    public $product;
    public $product_only;
    public $product_times;
    public $product_posibility;
    public $line1;
    public $line2;
    public $line3;
    public $line4;
    public $line5;
    
    public $order_sort;
    public $order_loop;
    public $inactive_products;
    public $in_stock;
    public $available_for_order;
    public $minimum_total;
    public $order_status;
    
    public function rules()
    {
        return array(
            array(
                array(
                    'enabled',
                    'fake',
                    'real',
                    'order_sort',
                    'order_loop',
                    'order_status',
                    'inactive_products',
                    'product',
                    'product_only',
                    'in_stock',
                    'date_unit',
                    'available_for_order'
                ), 'safe'
            ),
            array(
                array(
                    'posibility',
                    'product_posibility'
                ), 'integer', 'params' => array(
                    'min' => 0,
                    'max' => 100
                )
            ),
            array(
                array(
                    'fake_date',
                ), 'integer', 'params' => array(
                    'min' => 1,
                    'max' => 365
                )
            ),
            array(
                array(
                    'fake_items'
                ), 'interval', 'params' => array(
                    'min' => 1,
                    'max' => 100
                )
            ),
            array(
                array('minimum_total', 'product_times'), 'isInt'
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
            'enabled' => $this->l('Enabled', 'ArLsfOrdersConfigForm'),
            'fake' => $this->l('Fake mode', 'ArLsfOrdersConfigForm'),
            'fake_items' => $this->l('Fake order items count', 'ArLsfOrdersConfigForm'),
            'fake_date' => $this->l('Order date not greater than', 'ArLsfOrdersConfigForm'),
            'posibility' => $this->l('Fake popup probability', 'ArLsfOrdersConfigForm'),
            'real' => $this->l('Real mode', 'ArLsfOrdersConfigForm'),
            'order_sort' => $this->l('Order sequence', 'ArLsfOrdersConfigForm'),
            'order_loop' => $this->l('Loop orders', 'ArLsfOrdersConfigForm'),
            'order_status' => $this->l('Show orders with status', 'ArLsfOrdersConfigForm'),
            'inactive_products' => $this->l('Display not active products', 'ArLsfOrdersConfigForm'),
            'in_stock' => $this->l('Only products in stock', 'ArLsfOrdersConfigForm'),
            'line1' => $this->l('Content line 1', 'ArLsfOrdersConfigForm'),
            'line2' => $this->l('Content line 2', 'ArLsfOrdersConfigForm'),
            'line3' => $this->l('Content line 3', 'ArLsfOrdersConfigForm'),
            'line4' => $this->l('Content line 4', 'ArLsfOrdersConfigForm'),
            'line5' => $this->l('Content line 5', 'ArLsfOrdersConfigForm'),
            'minimum_total' => $this->l('Minimum order amount', 'ArLsfOrdersConfigForm'),
            'product' => $this->l('Display popup with current product', 'ArLsfOrdersConfigForm'),
            'product_only' => $this->l('Display popup with current product only', 'ArLsfOrdersConfigForm'),
            'product_times' => $this->l('Display popup with current product X times', 'ArLsfOrdersConfigForm'),
            'date_unit' => '',
            'product_posibility' => $this->l('Current product popup probability', 'ArLsfOrdersConfigForm'),
            'available_for_order' => $this->l('Only products available for order', 'ArLsfAddToCartConfigForm')
        );
    }
    
    public function attributePlaceholders()
    {
        return array(
            'fake_date' => $this->l('Enter 0 to disable order date limit', 'ArLsfOrdersConfigForm'),
        );
    }
    
    public function attributeDefaults()
    {
        return array(
            'enabled' => 1,
            'fake' => 1,
            'real' => 1,
            'in_stock' => 1,
            'available_for_order' => 1,
            'fake_items' => '2-6',
            'fake_date' => 30,
            'date_unit' => 'days',
            'posibility' => 20,
            'product' => 1,
            'product_posibility' => 50,
            'product_times' => 3,
            'order_sort' => 'random',
            'order_loop' => 1,
            'inactive_products' => 1,
            'order_status' => '',
            'line1' => $this->l('{firstname} {lastname} from {city} bought', 'ArLsfOrdersConfigForm'),
            'line2' => $this->l('{product_name} ({total_items} items)', 'ArLsfOrdersConfigForm'),
            'line3' => $this->l('Order total: {order_total}', 'ArLsfOrdersConfigForm'),
            'line4' => $this->l('About {time_ago}', 'ArLsfOrdersConfigForm'),
            'line5' => '',
            'minimum_total' => '0'
        );
    }
    
    public function fieldSuffix()
    {
        $dateUnit = $this->l('Days', 'ArLsfOrdersConfigForm');
        if ($this->date_unit == 'hours') {
            $dateUnit = $this->l('Hours', 'ArLsfOrdersConfigForm');
        }
        return array(
            'posibility' => '%',
            'product_posibility' => '%',
            'fake_date' => $dateUnit
        );
    }
    
    public function attributeHints()
    {
        return array(
            'order_sort' => $this->l('This is apply only to real mode not fake mode.'),
            'order_loop' => $this->l('This option has no sence when Fake mode is on.'),
            'inactive_products' => $this->l('If this option active, order popup will be displayed even if product is not active (link to this product will be removed from popup).', 'ArLsfOrdersConfigForm'),
            'product' => $this->l('If customer opens some product page then popup with this product will be shown.', 'ArLsfOrdersConfigForm'),
            'product_only' => $this->l('Display popup with current product only. No other products will be displayed in popup.', 'ArLsfOrdersConfigForm')
        );
    }
    
    public function attributeDescriptions()
    {
        return array(
            'product_times' => $this->l('Limit popup with current product displays count. Set to 0 to unlimited displays count.', 'ArLsfOrdersConfigForm'),
        );
    }
    
    public function multipleSelects()
    {
        return array(
            'order_status' => true
        );
    }
    
    public function htmlFields()
    {
        return array(
            'fake_date' => $this->module->render('_partials/_date-limit.tpl', array(
                'model' => $this
            )),
            'date_unit' => false
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
            'order_loop' => 'switch',
            'inactive_products' => 'switch',
            'in_stock' => 'switch',
            'order_sort' => 'select',
            'order_status' => 'select',
            'fake_date' => 'html',
            'date_unit' => 'html',
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
            'line5' => true
        );
    }
    
    public function dateUnitSelectOptions()
    {
        return array(
            array(
                'id' => 'days',
                'name' => $this->l('Days', 'ArLsfOrdersConfigForm')
            ),
            array(
                'id' => 'hours',
                'name' => $this->l('Hours', 'ArLsfOrdersConfigForm')
            ),
            array(
                'id' => 'minutes',
                'name' => $this->l('Minutes', 'ArLsfOrdersConfigForm')
            ),
            array(
                'id' => 'seconds',
                'name' => $this->l('Seconds', 'ArLsfOrdersConfigForm')
            )
        );
    }
    
    public function orderStatusSelectOptions()
    {
        $statuses = OrderState::getOrderStates(Context::getContext()->language->id);
        $result = array();
        foreach ($statuses as $state) {
            $result[] = array(
                'id' => $state['id_order_state'],
                'name' => $state['name']
            );
        }
        return $result;
    }
    
    public function orderSortSelectOptions()
    {
        return array(
            array(
                'id' => 'random',
                'name' => $this->l('Random')
            ),
            array(
                'id' => 'asc',
                'name' => $this->l('ASC')
            ),
            array(
                'id' => 'desc',
                'name' => $this->l('DESC')
            ),
        );
    }
    
    public function getFormTitle()
    {
        return $this->l('Orders popup settings', 'ArLsfOrdersConfigForm');
    }
}
