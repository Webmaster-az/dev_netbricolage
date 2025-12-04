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

include_once dirname(__FILE__).'../../../arlsf.php';
include_once dirname(__FILE__).'../../../classes/ArlsfSession.php';
include_once dirname(__FILE__).'../../../classes/ArlsfVisitor.php';

/**
 * @property ArLsf $module
 */
class ArLsfAjaxModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    
    protected function getOrder($id = 0, $ids = array(), $retryIfEmpty = true)
    {
        $stockManagment = (int)Configuration::get('PS_STOCK_MANAGEMENT');
        $sort = Configuration::get('AR_LSFO_ORDER_SORT');
        $loop = Configuration::get('AR_LSFO_ORDER_LOOP');
        $min = Configuration::get('AR_LSFO_MINIMUM_TOTAL');
        $inactiveProducts = Configuration::get('AR_LSFO_INACTIVE_PRODUCTS');
        $statuses = Configuration::get('AR_LSFO_ORDER_STATUS');
        $minDate = null;
        if ($fakeDate = $this->module->getOrdersConfigModel()->fake_date) {
            $fakeDateUnit = $this->module->getOrdersConfigModel()->date_unit;
            switch ($fakeDateUnit) {
                case 'days':
                    $m = 86400;
                    break;
                case 'hours':
                    $m = 3600;
                    break;
                case 'minutes':
                    $m = 60;
                    break;
                case 'seconds':
                    $m = 1;
                    break;
            }
            
            $minDate = date('Y-m-d 00:00:00', time() - ($fakeDate * $m));
        }
        
        $idShop = (int)Context::getContext()->shop->id;
        $q = 'SELECT od.id_order_detail, od.product_attribute_id, od.product_id, o.id_order, o.id_customer, o.total_paid_tax_incl, o.id_address_delivery FROM `'
            . _DB_PREFIX_ . 'order_detail` od '
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'orders` o ON o.id_order = od.id_order'
            . ' INNER JOIN `' . _DB_PREFIX_ . 'product` p ON od.product_id = p.id_product'
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'stock_available` sa ON sa.id_product = od.product_id AND sa.id_product_attribute = od.product_attribute_id';
        $q .= ' WHERE o.id_shop = ' . $idShop;
        if ($min) {
            $q .= ' AND o.total_paid_tax_incl >= ' . (int)$min;
        }
        if ($statuses) {
            $q .= ' AND o.current_state IN (' . pSQL($statuses) . ')';
        }
        if (!$inactiveProducts) {
            $q .= ' AND p.active = 1';
        }
        if ($minDate) {
            $q .= ' AND o.date_add >= "' . pSQL($minDate) . '"';
        }
        if ($stockManagment) {
            $outOfStockAllowed = (int)Configuration::get('PS_ORDER_OUT_OF_STOCK');
            if ($this->module->getOrdersConfigModel()->in_stock) {
                if ($this->module->getOrdersConfigModel()->available_for_order) {
                    if ($outOfStockAllowed) {
                        $q .= ' AND ((p.quantity > 0 OR sa.quantity > 0) OR sa.out_of_stock IN (1,2)) AND p.available_for_order = 1';
                    } else {
                        $q .= ' AND ((p.quantity > 0 OR sa.quantity > 0) OR sa.out_of_stock IN (1)) AND p.available_for_order = 1';
                    }
                } else {
                    $q .= ' AND (p.quantity > 0 OR sa.quantity > 0)';
                }
            } else {
                if ($this->module->getOrdersConfigModel()->available_for_order) {
                    if ($outOfStockAllowed) {
                        $q .= ' AND ((p.quantity > 0 OR sa.quantity > 0) OR sa.out_of_stock IN (1,2)) AND p.available_for_order = 1';
                    } else {
                        $q .= ' AND ((p.quantity > 0 OR sa.quantity > 0) OR sa.out_of_stock IN (1)) AND p.available_for_order = 1';
                    }
                }
            }
        } else {
            if ($this->module->getOrdersConfigModel()->available_for_order) {
                $q .= ' AND p.available_for_order = 1';
            }
        }
        if ($this->module->getOrdersConfigModel()->product_only && Tools::getValue('orderCurrentProduct')) {
            $q .= ' AND od.product_id = ' . ((int)Tools::getValue('cartCurrentProduct'));
        }
        if ($sort == 'asc') {
            $q .= ' AND o.id_order > ' . (int)$id;
            if ($ids) {
                $q .= ' AND o.id_order NOT IN (' . pSQL(implode(',', $ids)) . ')';
            }
            $q .= ' ORDER BY o.id_order ASC';
        } elseif ($sort == 'desc') {
            if ($id) {
                $q .= ' AND o.id_order < ' . (int)$id;
            }
            if ($ids) {
                $q .= ' AND o.id_order NOT IN (' . pSQL(implode(',', $ids)) . ')';
            }
            $q .= ' ORDER BY o.id_order DESC';
        } elseif ($sort == 'random') {
            if ($ids) {
                $q .= ' AND o.id_order NOT IN (' . pSQL(implode(',', $ids)) . ')';
            }
            $q .= ' ORDER BY RAND()';
        }
        
        if ($row = Db::getInstance()->getRow($q)) {
            return array(
                'reset' => false,
                'order' => $row
            );
        }
        if ($retryIfEmpty && $loop && !$this->module->getOrdersConfigModel()->fake) {
            if ($row = $this->getOrder(0, array(), false)) {
                ArlsfSession::deleteAllBySession(Tools::getValue('sessionKey'));
                $row['reset'] = true;
                return $row;
            }
        }
    }

    public function getCart($lastCartItem)
    {
        $idShop = (int)Context::getContext()->shop->id;
        
        $langId = Context::getContext()->language->id;
        $min = Configuration::get('AR_LSFA_MINIMUM_COST');
        $cart_id = Context::getContext()->cart->id;
        $sql = new DbQuery();
        $sql->select('cp.date_add, cp.id_product_attribute, cp.id_address_delivery, cp.id_cart, cp.id_product');
        $sql->from('cart_product', 'cp');
        $sql->leftJoin('cart', 'c', 'c.id_cart = cp.id_cart');
        $where = '';
        if (Context::getContext()->customer->id) {
            $where = 'cp.`date_add` > "' . pSQL($lastCartItem) . '" AND cp.id_shop=' . $idShop . ' AND c.id_customer != ' . (int)Context::getContext()->customer->id;
        } else {
            $where = 'cp.`date_add` > "' . pSQL($lastCartItem) . '" AND cp.id_shop=' . $idShop . ' AND cp.id_cart !=' . (int)$cart_id;
        }
        
        if ($this->module->getAddToCartConfigModel()->product_only && Tools::getValue('cartCurrentProduct')) {
            $where .= ' AND cp.id_product = ' . ((int)Tools::getValue('cartCurrentProduct'));
        }
        $sql->where($where);
        $sql->orderBy('cp.date_add DESC');
        if ($row = Db::getInstance()->getRow($sql)) {
            $cart = new Cart($row['id_cart']);
            $product = new Product($row['id_product'], false, $langId);
            if ($row['id_product_attribute']) {
                $combinations = $product->getAttributeCombinationsById($row['id_product_attribute'], $langId);
            } else {
                $combinations = array();
            }
            if ($cart->id_customer) {
                $customer = new Customer($cart->id_customer);
            } else {
                $customer = null;
            }
            if ($row['id_address_delivery']) {
                $address = new Address($row['id_address_delivery'], $langId);
            } else {
                $address = null;
            }
            if (($min && $product->getPrice() >= $min) || !$min) {
                return array(
                    'loop' => (int)Configuration::get('AR_LSFO_ORDER_LOOP'),
                    'reset' => 0,
                    'id_product' => $product->id,
                    'lastCart' => $row['date_add'],
                    'type' => 'cart',
                    'id_cart' => $cart->id,
                    'row' => $row,
                    'content' => $this->module->displayCartPopup($cart, $customer, $product, $address, $combinations, $row['id_product_attribute'])
                );
            }
        }
        return null;
    }
    
    public function getRandomProduct($id = 0, $minPrice = 0, $cart = false)
    {
        $stockManagment = (int)Configuration::get('PS_STOCK_MANAGEMENT');
        $idShop = (int)Context::getContext()->shop->id;
        $langId = Context::getContext()->language->id;
        
        $sql = new DbQuery();
        $sql->select('p.id_product');
        $where = '';
        if ($minPrice) {
            $where = 'p.active = 1 AND p.price >= ' . (int)$minPrice;
        } else {
            $where = 'p.active = 1';
        }
        if ($stockManagment) {
            $outOfStockAllowed = (int)Configuration::get('PS_ORDER_OUT_OF_STOCK');
            $sql->leftJoin('stock_available', 'sa', 'sa.id_product = p.id_product AND sa.id_product_attribute = p.cache_default_attribute');
            if (($this->module->getOrdersConfigModel()->in_stock && !$cart) || ($this->module->getAddToCartConfigModel()->in_stock && $cart)) {
                if (($this->module->getOrdersConfigModel()->available_for_order && !$cart) || ($this->module->getAddToCartConfigModel()->available_for_order && $cart)) {
                    if ($outOfStockAllowed) {
                        $where .= ' AND ((p.quantity > 0 OR sa.quantity > 0) OR sa.out_of_stock IN (1,2)) AND p.available_for_order = 1';
                    } else {
                        $where .= ' AND ((p.quantity > 0 OR sa.quantity > 0) OR sa.out_of_stock IN (1)) AND p.available_for_order = 1';
                    }
                } else {
                    $where .= ' AND (p.quantity > 0 OR sa.quantity > 0)';
                }
            } else {
                if (($this->module->getOrdersConfigModel()->available_for_order && !$cart) || ($this->module->getAddToCartConfigModel()->available_for_order && $cart)) {
                    if ($outOfStockAllowed) {
                        $where .= ' AND ((p.quantity > 0 OR sa.quantity > 0) OR sa.out_of_stock IN (1,2)) AND p.available_for_order = 1';
                    } else {
                        $where .= ' AND ((p.quantity > 0 OR sa.quantity > 0) OR sa.out_of_stock IN (1)) AND p.available_for_order = 1';
                    }
                }
            }
        } else {
            $where .= ' AND p.available_for_order = 1';
        }
        if ($id) {
            $where .= ' AND p.id_product = ' . (int)$id;
        }
        $sql->where($where . ' AND ps.id_shop = ' . (int)$idShop);
        $sql->from('product', 'p');
        $sql->leftJoin('product_shop', 'ps', 'p.id_product = ps.id_product');
        $sql->orderBy('RAND()');
        
        if ($row = Db::getInstance()->getRow($sql)) {
            $product = new Product($row['id_product'], false, $langId);
            if (Validate::isLoadedObject($product)) {
                return $product;
            }
        }
        
        return null;
    }
    
    public function displayOrderPopup($id_order, $orderCurrentProduct, $orderCurrentProductCounter)
    {
        if ($this->module->getOrdersConfigModel()->product_times && ($orderCurrentProductCounter >= $this->module->getOrdersConfigModel()->product_times)) {
            $orderCurrentProduct = 0;
        }
        
        $displayed = Tools::getValue('displayed');
        $langId = Context::getContext()->language->id;
        $lastCartItem = Tools::getValue('lastCart');
        
        $sessionKey = Tools::getValue('sessionKey');
        
        $timeout = time() - 86400;
        ArlsfSession::deleteOutdated($timeout);
        
        $orders = ArlsfSession::getAllBySession($sessionKey);
        
        $ids = array();
        if ($orders) {
            foreach ($orders as $row) {
                $ids[] = (int)$row['id_order'];
            }
        }
        
        if ($this->module->getOrdersConfigModel()->enabled) {
            if ($this->module->getOrdersConfigModel()->real) {
                if ($row = $this->getOrder($id_order, $ids)) {
                    $order = new Order($row['order']['id_order']);
                    $itemsCount = count($order->getProducts());
                    $product = new Product($row['order']['product_id'], false, $langId);
                    if ($row['order']['product_attribute_id']) {
                        $combinations = $product->getAttributeCombinationsById($row['order']['product_attribute_id'], $langId);
                    } else {
                        $combinations = array();
                    }
                    $customer = new Customer($row['order']['id_customer']);
                    $address = new Address($order->id_address_delivery, Context::getContext()->language->id);
                    $invoiceAddress = new Address($order->id_address_invoice, Context::getContext()->language->id);
                    ArlsfSession::addData(Tools::getValue('sessionKey'), $order->id);
                    die(Tools::jsonEncode(array(
                        'loop' => (int)Configuration::get('AR_LSFO_ORDER_LOOP'),
                        'error' => 0,
                        'reset' => (int)$row['reset'],
                        'order' => $order->id,
                        'id_product' => $product->id,
                        'lastCart' => $lastCartItem,
                        'type' => 'order',
                        'content' => $this->module->displayOrderPopup($order, $itemsCount, $customer, $address, $invoiceAddress, $product, $combinations, $row['order']['product_attribute_id'])
                    )));
                }
            }
            if ($this->module->getOrdersConfigModel()->fake && $this->module->getOrdersConfigModel()->needToDisplay()) {
                $customer = $this->module->getFakeConfigModel()->getCustomer($langId);
                if (!$this->module->getOrdersConfigModel()->needToDisplayCurrentProduct()) {
                    $orderCurrentProduct = 0;
                }
                if ($this->module->getOrdersConfigModel()->product_only && $orderCurrentProduct == 0) {
                    return null;
                }
                if (!$product = $this->getRandomProduct($orderCurrentProduct, $this->module->getOrdersConfigModel()->minimum_total)) {
                    return null;
                }
                $address = $this->module->getFakeConfigModel()->getAddress($langId);
                $address->firstname = $customer->firstname;
                $address->lastname = $customer->lastname;
                $invoiceAddress = $address;
                
                $ipa = $product->getDefaultIdProductAttribute();
                if ($ipa) {
                    $combinations = $product->getAttributeCombinationsById($ipa, $langId);
                } else {
                    $combinations = array();
                }
                $fakeItems = explode('-', $this->module->getOrdersConfigModel()->fake_items);
                $minItems = isset($fakeItems[0])? $fakeItems[0] : 1;
                $maxItems = isset($fakeItems[1])? $fakeItems[1] : $minItems;
                $itemsCount = rand($minItems, $maxItems);
                $fakeDate = $this->module->getOrdersConfigModel()->fake_date? $this->module->getOrdersConfigModel()->fake_date : 30;
                $fakeDateUnit = $this->module->getOrdersConfigModel()->date_unit;
                switch ($fakeDateUnit) {
                    case 'days':
                        $m = 'day';
                        break;
                    case 'hours':
                        $m = 'hour';
                        break;
                    case 'minutes':
                        $m = 'minute';
                        break;
                    case 'seconds':
                        $m = 'second';
                        break;
                }
                $t = rand(1, $fakeDate);
                $order = new Order();
                $order->date_add = date('Y-m-d H:i:s', strtotime('-' . $t . " {$m}"));
                $order->id_currency = Context::getContext()->currency->id;
                $order->total_paid_tax_incl = $this->getFakeOrderTotal($product, $itemsCount);
                
                die(Tools::jsonEncode(array(
                    'loop' => (int)Configuration::get('AR_LSFO_ORDER_LOOP'),
                    'error' => 0,
                    'reset' => 0,
                    'order' => null,
                    'id_product' => $product->id,
                    'lastCart' => $lastCartItem,
                    'type' => 'order',
                    'content' => $this->module->displayOrderPopup($order, $itemsCount, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
                )));
            }
        }
    }
    
    public function displayCartPopup($cartCurrentProduct, $cartCurrentProductCounter)
    {
        if ($this->module->getAddToCartConfigModel()->product_times && ($cartCurrentProductCounter >= $this->module->getAddToCartConfigModel()->product_times)) {
            $cartCurrentProduct = 0;
        }
        
        $lastCartItem = Tools::getValue('lastCart');
        
        $langId = Context::getContext()->language->id;
        if ($lastCartItem && $this->module->getAddToCartConfigModel()->enabled) {
            if ($this->module->getAddToCartConfigModel()->real) {
                if ($cart = $this->getCart($lastCartItem)) {
                    die(Tools::jsonEncode($cart));
                }
            }
            if ($this->module->getAddToCartConfigModel()->fake && $this->module->getAddToCartConfigModel()->needToDisplay()) {
                $customer = $this->module->getFakeConfigModel()->getCustomer($langId, $this->module->getAddToCartConfigModel()->fake_guest);
                if (!$this->module->getAddToCartConfigModel()->needToDisplayCurrentProduct()) {
                    $cartCurrentProduct = 0;
                }
                if ($this->module->getAddToCartConfigModel()->product_only && $cartCurrentProduct == 0) {
                    return null;
                }
                if (!$product = $this->getRandomProduct($cartCurrentProduct, $this->module->getAddToCartConfigModel()->minimum_cost, true)) {
                    return null;
                }
                $ipa = $product->getDefaultIdProductAttribute();
                $cart = new Cart();
                $t = rand(1, 10);
                $cart->date_add = date('Y-m-d H:i:s', strtotime('-' . $t . ' second'));
                $cart->date_upd = date('Y-m-d H:i:s', strtotime('-' . $t . ' second'));
                if ($ipa) {
                    $combinations = $product->getAttributeCombinationsById($ipa, $langId);
                } else {
                    $combinations = array();
                }
                $address = $this->module->getFakeConfigModel()->getAddress($langId);
                die(Tools::jsonEncode(array(
                    'loop' => (int)Configuration::get('AR_LSFO_ORDER_LOOP'),
                    'error' => 0,
                    'reset' => 0,
                    'lastCart' => $lastCartItem,
                    'type' => 'cart',
                    'id_product' => $product->id,
                    'id_cart' => $cart->id,
                    'row' => null,
                    'content' => $this->module->displayCartPopup($cart, $customer, $product, $address, $combinations, $ipa)
                )));
            }
        }
    }
    
    public function isAjax()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && Tools::strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        return  false;
    }
    
    public function initContent()
    {
        // check is ajax request
        if (!$this->isAjax()) {
            die(Tools::jsonEncode(array(
                'error' => 'Wrong request'
            )));
        }
        $action = Tools::getValue('action');
        if ($action == 'rateModal') {
            $result = (int)Tools::getValue('result');
            if ($result > 0) {
                $result = time() + ArLsf::REMIND_TO_RATE;
            }
            Configuration::updateValue('AR_LSF_REMINDER', pSQL($result), false, 0, 0);
            die(Tools::jsonEncode(array(
                'reminder' => Configuration::get('AR_LSF_REMINDER')
            )));
        }
        $id_order = Tools::getValue('id');
        $orderCurrentProduct = Tools::getValue('orderCurrentProduct');
        $cartCurrentProduct = Tools::getValue('cartCurrentProduct');
        $orderCurrentProductCounter = Tools::getValue('orderCurrentProductCounter');
        $cartCurrentProductCounter = Tools::getValue('cartCurrentProductCounter');
        
        $this->displayVisitorsPopup($cartCurrentProduct);
        $this->displayCartPopup($cartCurrentProduct, $cartCurrentProductCounter);
        $this->displayOrderPopup($id_order, $orderCurrentProduct, $orderCurrentProductCounter);
        
        die(Tools::jsonEncode(array(
            'loop' => (int)Configuration::get('AR_LSFO_ORDER_LOOP'),
            'error' => 0,
            'reset' => 0,
            'order' => 0,
            'id_product' => 0,
            'lastCart' => 0,
            'type' => 'order',
            'content' => null,
            'cart_id' => Context::getContext()->cart->id
        )));
    }
    
    public function displayVisitorsPopup($currentProduct)
    {
        $config = $this->module->getVisitorConfigModel();
        $config->loadFromConfig();
        if (Tools::getValue('visitorPopupDisplayed')) {
            return null;
        }
        if (!$currentProduct) {
            return null;
        }
        if (!$config->enabled) {
            return null;
        }
        $key = Tools::getValue('sessionKey');
        $timeout = time() - 120;
        ArlsfVisitor::deleteOutdated($timeout);
        if (!ArlsfVisitor::getCount($key, $currentProduct)) {
            ArlsfVisitor::addData($key, $currentProduct);
        }
        $exclude = array($key);
        $count = ArlsfVisitor::getCount(null, $currentProduct, $exclude);
        $lastCartItem = Tools::getValue('lastCart');
        $product = new Product($currentProduct, false, Context::getContext()->language->id);
        if ($config->in_stock && !Product::getQuantity($product->id)) {
            die(Tools::jsonEncode(array(
                'loop' => (int)Configuration::get('AR_LSFO_ORDER_LOOP'),
                'error' => 0,
                'reset' => 0,
                'lastCart' => $lastCartItem,
                'type' => 'visitor',
                'id_product' => $product->id,
                'row' => null,
                'content' => ''
            )));
        }
        if (($count && $config->real) || ($config->fake && $config->needToDisplay())) {
            $fakeItems = explode('-', $this->module->getVisitorConfigModel()->fake_count);
            $minItems = isset($fakeItems[0])? $fakeItems[0] : 1;
            $maxItems = isset($fakeItems[1])? $fakeItems[1] : $minItems;
            if (empty($count) && $config->real) {
                $count = rand($minItems, $maxItems);
            }
            $ipa = $product->getDefaultIdProductAttribute();
            die(Tools::jsonEncode(array(
                'loop' => (int)Configuration::get('AR_LSFO_ORDER_LOOP'),
                'error' => 0,
                'reset' => 0,
                'lastCart' => $lastCartItem,
                'type' => 'visitor',
                'id_product' => $product->id,
                'row' => null,
                'content' => $this->module->displayVisitorPopup($product, $count, $ipa)
            )));
        }
        die(Tools::jsonEncode(array(
            'loop' => (int)Configuration::get('AR_LSFO_ORDER_LOOP'),
            'error' => 0,
            'reset' => 0,
            'lastCart' => $lastCartItem,
            'type' => 'visitor',
            'id_product' => $product->id,
            'row' => null,
            'content' => ''
        )));
        return null;
    }
    
    protected function getFakeOrderTotal($product, $itemsCount)
    {
        $price = $product->getPrice();
        if ($itemsCount > 1) {
            $price = $price * (100 + rand(0, 20)) / 100;
        }
        return Tools::ps_round($price * $itemsCount, (int)Configuration::get('PS_PRICE_DISPLAY_PRECISION'));
    }
    
    public function getCurrentIP()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '127.0.0.1') {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '127.0.0.1') {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}
