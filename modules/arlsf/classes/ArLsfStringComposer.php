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

class ArLsfStringComposer
{
    protected $module;
    protected $address;

    public function __construct($module)
    {
        $this->module = $module;
    }

    public function getOrderTags()
    {
        return array(
            '{b}' => $this->l('Open bold tag', 'ArLsfStringComposer'),
            '{bc}' => $this->l('Close bold tag', 'ArLsfStringComposer'),
            '{i}' => $this->l('Open italic tag', 'ArLsfStringComposer'),
            '{ic}' => $this->l('Close italic tag', 'ArLsfStringComposer'),
            '{s}' => $this->l('Open small tag', 'ArLsfStringComposer'),
            '{sc}' => $this->l('Close small tag', 'ArLsfStringComposer'),
            '{country}' => $this->l('Customer country', 'ArLsfStringComposer'),
            '{city}' => $this->l('Customer city', 'ArLsfStringComposer'),
            '{state}' => $this->l('Customer state', 'ArLsfStringComposer'),
            '{firstname}' => $this->l('Customer firstname', 'ArLsfStringComposer'),
            '{lastname}' => $this->l('Customer lastname', 'ArLsfStringComposer'),
            '{f}' => $this->l('Customer firstname first letter', 'ArLsfStringComposer'),
            '{l}' => $this->l('Customer lastname letter', 'ArLsfStringComposer'),
            '{firstname_lastname}' => $this->l('Customer firstname and lastname separated by space', 'ArLsfStringComposer'),
            '{lastname_firstname}' => $this->l('Customer lastname and firstname separated by space', 'ArLsfStringComposer'),
            '{firstname_l}' => $this->l('Customer firstname and first letter of lastname separated by space', 'ArLsfStringComposer'),
            '{lastname_f}' => $this->l('Customer lastname and first letter of firstname separated by space', 'ArLsfStringComposer'),
            
            '{delivery_firstname}' => $this->l('Delivery customer firstname', 'ArLsfStringComposer'),
            '{delivery_lastname}' => $this->l('Delivery customer lastname', 'ArLsfStringComposer'),
            '{delivery_firstname_lastname}' => $this->l('Delivery customer firstname and lastname separated by space', 'ArLsfStringComposer'),
            '{delivery_lastname_firstname}' => $this->l('Delivery customer lastname and firstname separated by space', 'ArLsfStringComposer'),
            '{delivery_firstname_l}' => $this->l('Delivery customer firstname and first letter of lastname separated by space', 'ArLsfStringComposer'),
            '{delivery_lastname_f}' => $this->l('Delivery customer lastname and first letter of firstname separated by space', 'ArLsfStringComposer'),
            
            '{invoice_firstname}' => $this->l('Invoice customer firstname', 'ArLsfStringComposer'),
            '{invoice_lastname}' => $this->l('Invoice customer lastname', 'ArLsfStringComposer'),
            '{invoice_firstname_lastname}' => $this->l('Invoice customer firstname and lastname separated by space', 'ArLsfStringComposer'),
            '{invoice_lastname_firstname}' => $this->l('Invoice customer lastname and firstname separated by space', 'ArLsfStringComposer'),
            '{invoice_firstname_l}' => $this->l('Invoice customer firstname and first letter of lastname separated by space', 'ArLsfStringComposer'),
            '{invoice_lastname_f}' => $this->l('Invoice customer lastname and first letter of firstname separated by space', 'ArLsfStringComposer'),
            
            '{date}' => $this->l('Order date', 'ArLsfStringComposer'),
            '{time_ago}' => $this->l('How long ago an order was made', 'ArLsfStringComposer'),
            '{order_total}' => $this->l('Order total', 'ArLsfStringComposer'),
            '{product_price}' => $this->l('Product price', 'ArLsfStringComposer'),
            '{product_price_no_tax}' => $this->l('Product price without tax', 'ArLsfStringComposer'),
            '{plus_items}' => $this->l('Total items count in the order exclude displayed product', 'ArLsfStringComposer'),
            '{total_items}' => $this->l('Total items count in the order', 'ArLsfStringComposer'),
            '{product_name}' => $this->l('Product name', 'ArLsfStringComposer'),
            '{product_name_with_attrs}' => $this->l('Product name with attributes', 'ArLsfStringComposer'),
        );
    }
    
    public function getAddToTags()
    {
        return array(
            '{b}' => $this->l('Open bold tag', 'ArLsfStringComposer'),
            '{bc}' => $this->l('Close bold tag', 'ArLsfStringComposer'),
            '{i}' => $this->l('Open italic tag', 'ArLsfStringComposer'),
            '{ic}' => $this->l('Close italic tag', 'ArLsfStringComposer'),
            '{s}' => $this->l('Open small tag', 'ArLsfStringComposer'),
            '{sc}' => $this->l('Close small tag', 'ArLsfStringComposer'),
            '{firstname}' => $this->l('Customer firstname', 'ArLsfStringComposer'),
            '{lastname}' => $this->l('Customer lastname', 'ArLsfStringComposer'),
            '{f}' => $this->l('Customer firstname first letter', 'ArLsfStringComposer'),
            '{l}' => $this->l('Customer lastname letter', 'ArLsfStringComposer'),
            '{firstname_lastname}' => $this->l('Customer firstname and lastname separated by space', 'ArLsfStringComposer'),
            '{lastname_firstname}' => $this->l('Customer lastname and firstname separated by space', 'ArLsfStringComposer'),
            '{firstname_l}' => $this->l('Customer firstname and first letter of lastname separated by space', 'ArLsfStringComposer'),
            '{lastname_f}' => $this->l('Customer lastname and first letter of firstname separated by space', 'ArLsfStringComposer'),
            '{date}' => $this->l('Order date', 'ArLsfStringComposer'),
            '{time_ago}' => $this->l('How long ago an order was made', 'ArLsfStringComposer'),
            '{product_price}' => $this->l('Product price', 'ArLsfStringComposer'),
            '{product_price_no_tax}' => $this->l('Product price without tax', 'ArLsfStringComposer'),
            '{product_name}' => $this->l('Product name', 'ArLsfStringComposer'),
            '{product_name_with_attrs}' => $this->l('Product name with attributes', 'ArLsfStringComposer'),
        );
    }
    
    public function getVisitorTags()
    {
        return array(
            '{b}' => $this->l('Open bold tag', 'ArLsfStringComposer'),
            '{bc}' => $this->l('Close bold tag', 'ArLsfStringComposer'),
            '{i}' => $this->l('Open italic tag', 'ArLsfStringComposer'),
            '{ic}' => $this->l('Close italic tag', 'ArLsfStringComposer'),
            '{s}' => $this->l('Open small tag', 'ArLsfStringComposer'),
            '{sc}' => $this->l('Close small tag', 'ArLsfStringComposer'),
            '{visitors}' => $this->l('Active visitors count', 'ArLsfStringComposer'),
            '{product_price}' => $this->l('Product price', 'ArLsfStringComposer'),
            '{product_price_no_tax}' => $this->l('Product price without tax', 'ArLsfStringComposer'),
            '{product_name}' => $this->l('Product name', 'ArLsfStringComposer')
        );
    }
    
    public function isOrderShortTagAllowed($tag)
    {
        $tags = array_keys($this->getOrderTags());
        return in_array($tag, $tags);
    }
    
    public function isAddToCartShortTagAllowed($tag)
    {
        $tags = array_keys($this->getAddToTags());
        return in_array($tag, $tags);
    }
    
    public function isVisitorTagAllowed($tag)
    {
        $tags = array_keys($this->getVisitorTags());
        return in_array($tag, $tags);
    }
    
    public function buildVisitorLine($string, $product, $count, $ipa)
    {
        preg_match_all('/{.*?}/is', $string, $matches);
        
        if (isset($matches[0])) {
            $replaces = array();
            foreach ($matches[0] as $tag) {
                $name = $this->getTagName($tag);
                $methodName = 'tag' . Tools::ucfirst(Tools::toCamelCase($name));
                if ($this->isVisitorTagAllowed($tag) && method_exists($this, $methodName)) {
                    $value = $this->$methodName(null, $count, null, null, null, null, $product, null, $ipa);
                    $replaces[$tag] = $value;
                }
            }
        }
        if ($replaces) {
            return strtr($string, $replaces);
        }
        return $string;
    }
    
    public function buildAddToCartLine($string, $order, $itemsCount, $cart, $customer, $address, $product, $combinations, $ipa)
    {
        preg_match_all('/{.*?}/is', $string, $matches);
        
        if (isset($matches[0])) {
            $replaces = array();
            foreach ($matches[0] as $tag) {
                $name = $this->getTagName($tag);
                $methodName = 'tag' . Tools::ucfirst(Tools::toCamelCase($name));
                if ($this->isAddToCartShortTagAllowed($tag) && method_exists($this, $methodName)) {
                    $value = $this->$methodName($order, $itemsCount, $cart, $customer, $address, null, $product, $combinations, $ipa);
                    $replaces[$tag] = $value;
                }
            }
        }
        if ($replaces) {
            return strtr($string, $replaces);
        }
        return $string;
    }
    
    public function buildOrderLine($string, $order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        preg_match_all('/{.*?}/is', $string, $matches);
        
        if (isset($matches[0])) {
            $replaces = array();
            foreach ($matches[0] as $tag) {
                $name = $this->getTagName($tag);
                $methodName = 'tag' . Tools::ucfirst(Tools::toCamelCase($name));
                if ($this->isOrderShortTagAllowed($tag) && method_exists($this, $methodName)) {
                    $value = $this->$methodName($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa);
                    $replaces[$tag] = $value;
                }
            }
        }
        if ($replaces) {
            return strtr($string, $replaces);
        }
        return $string;
    }
    
    protected function getProductUrl($product, $ipa = null)
    {
        $url = Context::getContext()->link->getProductLink($product, null, null, null, null, null, $ipa, false, false, true);
        if ($params = Configuration::get('AR_LSF_URL_PARAMS')) {
            if (Tools::strpos($params, '?') === 0) {
                $params = substr_replace($params, '', 0, 1);
            }
            if (Tools::strpos($params, '&') === 0) {
                $params = substr_replace($params, '', 0, 1);
            }
            
            if (Tools::strpos($url, '?') === false) {
                $params = '?' . $params;
            } else {
                $params = '&' . $params;
            }
            if (Tools::strpos($url, '#') !== false) {
                return str_replace('#', $params . '#', $url);
            }
            return $url . $params;
        } else {
            return $url;
        }
    }
    
    protected function tagB($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        return '<b>';
    }
    
    protected function tagBc($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        return '</b>';
    }
    
    protected function tagI($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        return '<i>';
    }
    
    protected function tagIc($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        return '</i>';
    }
    
    protected function tagS($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        return '<small>';
    }
    
    protected function tagSc($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        return '</small>';
    }
    
    protected function tagDate($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($order) {
            $date = $order->date_add;
        }
        if ($cart) {
            $date = $cart->date_upd;
        }
        $timeOffset = $this->module->getGeneralConfigModel()->time_offset;
        $now = new DateTime();
        $now->setTimestamp(strtotime(date('Y-m-d H:i:s')));
        $orderDate = new DateTime();
        $orderDate->setTimestamp(strtotime($date) + $timeOffset);
        return $orderDate->format('Y-m-d H:i:s');
    }

    protected function tagProductPrice($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        $currency = new Currency(Context::getContext()->currency->id);
        
        return Tools::displayPrice($product->getPrice(), $currency);
    }
    
    protected function tagProductPriceNoTax($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        $currency = new Currency(Context::getContext()->currency->id);
        
        return Tools::displayPrice($product->getPrice(false), $currency);
    }

    protected function tagTimeAgo($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($order) {
            $date = $order->date_add;
        }
        if ($cart) {
            $date = $cart->date_upd;
        }
        $timeOffset = $this->module->getGeneralConfigModel()->time_offset;
        $now = new DateTime();
        $now->setTimestamp(strtotime(date('Y-m-d H:i:s')));
        $orderDate = new DateTime();
        $orderDate->setTimestamp(strtotime($date) + $timeOffset);
        $diff = $now->diff($orderDate);
        $date = '';
        if ($diff->y) {
            $f1 = sprintf($this->l('%s year ago', 'ArLsfStringComposer'), $diff->y);
            $f2 = sprintf($this->l('%s years ago', 'ArLsfStringComposer'), $diff->y);
            $f5 = sprintf($this->l('%s years ago', 'ArLsfStringComposer'), $diff->y);
            $date = $this->morph($diff->y, $f1, $f2, $f5);
        } elseif ($diff->m) {
            $f1 = sprintf($this->l('%s month ago', 'ArLsfStringComposer'), $diff->m);
            $f2 = sprintf($this->l('%s months ago', 'ArLsfStringComposer'), $diff->m);
            $f5 = sprintf($this->l('%s months ago', 'ArLsfStringComposer'), $diff->m);
            $date = $this->morph($diff->m, $f1, $f2, $f5);
        } elseif ($diff->d) {
            $f1 = sprintf($this->l('%s day ago', 'ArLsfStringComposer'), $diff->d);
            $f2 = sprintf($this->l('%s days ago', 'ArLsfStringComposer'), $diff->d);
            $f5 = sprintf($this->l('%s days ago', 'ArLsfStringComposer'), $diff->d);
            $date = $this->morph($diff->d, $f1, $f2, $f5);
        } elseif ($diff->h) {
            $f1 = sprintf($this->l('%s hour ago', 'ArLsfStringComposer'), $diff->h);
            $f2 = sprintf($this->l('%s hours ago', 'ArLsfStringComposer'), $diff->h);
            $f5 = sprintf($this->l('%s hours ago', 'ArLsfStringComposer'), $diff->h);
            $date = $this->morph($diff->h, $f1, $f2, $f5);
        } elseif ($diff->i) {
            $f1 = sprintf($this->l('%s minute ago', 'ArLsfStringComposer'), $diff->i);
            $f2 = sprintf($this->l('%s minutes ago', 'ArLsfStringComposer'), $diff->i);
            $f5 = sprintf($this->l('%s minutes ago', 'ArLsfStringComposer'), $diff->i);
            $date = $this->morph($diff->i, $f1, $f2, $f5);
        } elseif ($diff->s) {
            $f1 = sprintf($this->l('%s second ago', 'ArLsfStringComposer'), $diff->s);
            $f2 = sprintf($this->l('%s seconds ago', 'ArLsfStringComposer'), $diff->s);
            $f5 = sprintf($this->l('%s seconds ago', 'ArLsfStringComposer'), $diff->s);
            $date = $this->morph($diff->s, $f1, $f2, $f5);
        }
        return $date;
    }
    
    public function morph($n, $f1, $f2, $f5)
    {
        $n = abs((int)$n) % 100;
        if ($n > 10 && $n < 20) {
            return $f5;
        }
        $n = $n % 10;
        if ($n > 1 && $n < 5) {
            return $f2;
        }
        if ($n == 1) {
            return $f1;
        }
        return $f5;
    }
    
    public function tagTotalItems($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($itemsCount) {
            return (int)$itemsCount;
        }
        return null;
    }
    
    public function tagPlusItems($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($itemsCount - 1) {
            return '+' . ($itemsCount - 1);
        }
        return null;
    }
    
    public function tagProductNameWithAttrs($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        $name = $product->name;
        if ($combinations) {
            $attrs = array();
            foreach ($combinations as $combination) {
                $attrs[] = $combination['group_name'] . ': ' . $combination['attribute_name'];
            }
            if ($attrs) {
                $name = $product->name . ' - '  . implode(', ', $attrs);
            }
        }
        
        if ($this->module->getGeneralConfigModel()->link_on_name && $product->active) {
            $url = $this->getProductUrl($product, $ipa);
            return  '<a href="' . $url . '">' . $name . '</a>';
        }
        return $name;
    }
    
    public function tagProductName($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($this->module->getGeneralConfigModel()->link_on_name && $product->active) {
            $url = $this->getProductUrl($product, $ipa);
            return  '<a href="' . $url . '">' . $product->name . '</a>';
        }
        return $product->name;
    }
    
    public function tagVisitors($order, $count, $cart, $customer, $address, $product, $combinations, $ipa)
    {
        return $count;
    }
    
    public function tagOrderTotal($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        $currency = new Currency($order->id_currency);
        return Tools::displayPrice($order->total_paid_tax_incl, $currency);
    }
    
    public function tagFirstnameLastname($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($customer) {
            return trim($customer->firstname . ' ' . $customer->lastname);
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagLastnameFirstname($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($customer) {
            return trim($customer->lastname . ' ' . $customer->firstname);
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagFirstnameL($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($customer) {
            return trim($customer->firstname . ' ' . Tools::substr(Tools::strtoupper($customer->lastname), 0, 1));
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagLastnameF($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($customer) {
            return trim($customer->lastname . ' ' . Tools::substr(Tools::strtoupper($customer->firstname), 0, 1));
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagFirstname($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($customer) {
            return $customer->firstname;
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }

    public function tagDeliveryFirstnameLastname($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($address) {
            return trim($address->firstname . ' ' . $address->lastname);
        }
        return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
    }
    
    public function tagDeliveryLastnameFirstname($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($address) {
            return trim($address->lastname . ' ' . $address->firstname);
        }
        return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
    }
    
    public function tagDeliveryFirstnameL($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($address) {
            return trim($address->firstname . ' ' . Tools::substr(Tools::strtoupper($address->lastname), 0, 1));
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagDeliveryLastnameF($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($address) {
            return trim($address->lastname . ' ' . Tools::substr(Tools::strtoupper($address->firstname), 0, 1));
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagDeliveryFirstname($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($address) {
            return $address->firstname;
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagDeliveryLastname($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($address) {
            return $address->lastname;
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagInvoiceFirstnameLastname($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($invoiceAddress) {
            return trim($invoiceAddress->firstname . ' ' . $invoiceAddress->lastname);
        }
        return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
    }
    
    public function tagInvoiceLastnameFirstname($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($invoiceAddress) {
            return trim($invoiceAddress->lastname . ' ' . $invoiceAddress->firstname);
        }
        return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
    }
    
    public function tagInvoiceFirstnameL($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($invoiceAddress) {
            return trim($invoiceAddress->firstname . ' ' . Tools::substr(Tools::strtoupper($invoiceAddress->lastname), 0, 1));
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagInvoiceLastnameF($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($invoiceAddress) {
            return trim($invoiceAddress->lastname . ' ' . Tools::substr(Tools::strtoupper($invoiceAddress->firstname), 0, 1));
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagInvoiceFirstname($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($invoiceAddress) {
            return $invoiceAddress->firstname;
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagInvoiceLastname($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($invoiceAddress) {
            return $invoiceAddress->lastname;
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagF($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($customer) {
            return Tools::substr(Tools::strtoupper($customer->firstname), 0, 1);
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagL($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($customer) {
            return Tools::substr(Tools::strtoupper($customer->lastname), 0, 1);
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagLastname($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($customer) {
            return $customer->lastname;
        } else {
            return Configuration::get('AR_LSFA_NAME_PLACEHOLDER', Context::getContext()->language->id);
        }
    }
    
    public function tagCountry($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($order instanceof Order) {
            if ($address->id_country) {
                $country = new Country($address->id_country, Context::getContext()->language->id);
                if (Validate::isLoadedObject($country)) {
                    return $country->name;
                }
            }
        }
        return null;
    }
    
    public function tagCity($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($order instanceof Order) {
            return $address->city;
        }
        return null;
    }
    
    public function tagState($order, $itemsCount, $cart, $customer, $address, $invoiceAddress, $product, $combinations, $ipa)
    {
        if ($order instanceof Order) {
            if ($address->id_state) {
                $state = new State($address->id_state);
                if (Validate::isLoadedObject($state)) {
                    return $state->name;
                }
            }
        }
        return null;
    }
    
    protected function l($string, $specific = false)
    {
        return $this->module->l($string, $specific);
    }
    
    public function getTagName($tag)
    {
        $tag = str_replace(array('{', '}'), '', $tag);
        return $tag;
    }
}
