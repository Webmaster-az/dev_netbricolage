<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 *
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link         http://www.globosoftware.net
 */

include_once(dirname(__FILE__).'/../../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../../init.php');
require_once(_PS_MODULE_DIR_ . "g_cartreminder/g_cartreminder.php");
class Cronjob
{
    public function __construct()
    {
        if (Tools::getValue('gettoken') != sha1(_COOKIE_KEY_ . 'g_cartreminder')) {
            $adminmodule = new G_cartreminder();
            echo $adminmodule->l("invalid url error.");
            die;
        }
        $headings = array();
        $content  = array();
        $return   = array();
        $link     = new Link();
        $id_shop       = Context::getContext()->shop->id;
        $id_shop_group = (int)Shop::getContextShopGroupID();
        $id_lang       = Context::getContext()->language->id;
        $getshopprotocol       = trim(Tools::getShopProtocol(), '/');
        $conditionandreminders = $this->getconditionandreminder();
        $carts = $this->getallcart($id_shop_group, $id_shop);
        $notification_carts = array();
        $timenow_old = date("Y-m-d H:i:s", time());
        $timenow  = strtotime($timenow_old);
        $time_cronjob_run = 1;/* 1 hr */
        $fortime  = (int)$timenow - $time_cronjob_run*3600; 
        if (!empty($conditionandreminders)) {
            $sendmail = array();
            foreach ($conditionandreminders as $conditionandreminder) {
                $datefrom = strtotime($conditionandreminder["datefrom"]);
                $dateto   = strtotime($conditionandreminder["dateto"]);
                $checktimeout  = $this->checktimeexpired($timenow, $datefrom, $dateto);
                if ($checktimeout == true) {
                    $reminders = Tools::jsonDecode($conditionandreminder["reminder"]);
                    $customergroups = Tools::jsonDecode($conditionandreminder["custormmer"]);
                    if($carts)
                    foreach ($carts as $cart) {
                        $obj_cart = new Cart($cart["id_cart"]);
                        $mincartamount      = Tools::jsonDecode($conditionandreminder["mincartamount"], true);
                        $maxcartamount      = Tools::jsonDecode($conditionandreminder["maxcartamount"], true);
                        $total_produc_price = $obj_cart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
                        $dkallgroup   = $this->checkgroupcustomer($cart["id_customer"], $customergroups);
                        $productcart  = $obj_cart->getProducts(true);
                        if ((float)$total_produc_price < (float)$mincartamount[$cart["id_currency"]]) {
                            continue;
                        }
                        elseif( (float)$total_produc_price > (float)$maxcartamount[$cart["id_currency"]] ) {
                            continue;
                        }
                        if ($dkallgroup != 0 && !empty($productcart) && $cart["id_lang"] == $conditionandreminder["id_lang"] && ($cart["id_shop"] == $conditionandreminder["id_shop"] || $id_shop == 0 || $conditionandreminder["id_shop"] == '' || empty($conditionandreminder["id_shop"]))) {
                            $customers = new Customer($cart["id_customer"]);
                            if(!Validate::isLoadedObject($customers)) continue;
                            foreach ($reminders as $reminder) {
                                if (!isset($sendmail[$cart["id_cart"]]) || !$sendmail[$cart["id_cart"]]) {
                                    $timesents  = $this->getcarttimesent($cart["id_cart"]);
                                    $timeaway   = ((int)$reminder->gday * 86400) + ((int)$reminder->ghrs * 3600);
                                    $abadontime = $timeaway+ strtotime($cart["date_add"]);
                                    if (!empty($timesents)) {
                                        if ((int)$timesents < (int)$timeaway && $timesents != $timeaway && $fortime < ($timeaway + $timenow) && $fortime <= $abadontime && $abadontime <= $timenow) {
                                            $subject = $this->getsubjectemailtemplate($reminder->id_emailtemplate, $cart["id_lang"]);
                                            $subject['subject'] = str_replace(
                                                array('{customer_firstname}','{customer_lastname}'),
                                                array($customers->firstname,$customers->lastname),
                                                $subject['subject']
                                            );
                                            $code    = $this->updategabandonedcart($cart, $reminder, $conditionandreminder);
                                            $this->updatetimesent($cart["id_cart"], $timeaway);
                                            $this->updatecartawait($cart["id_cart"], $conditionandreminder["id_gconditionandreminder"], $conditionandreminder["rulename"], $code, $subject['name'], $reminder);
                                            $datas  = $this->showdataarrayemailtxthtmlfile($customers, $obj_cart, $subject['subject'], $code);
                                            $params = $datas;
                                            Mail::Send(
                                              (int)$cart["id_lang"],
                                              (int)$reminder->id_emailtemplate . '_' . (int) $cart["id_shop"],
                                              $subject['subject'],
                                              $params,
                                              $customers->email,
                                              $customers->firstname . ' ' . $customers->lastname,
                                              null,
                                              null,
                                              null,
                                              null,
                                              _PS_MODULE_DIR_ . "g_cartreminder/mails/",
                                              false,
                                              (int)$cart["id_shop"]
                                            );
                                            $sendmail[$cart["id_cart"]] = true;
                                        }
                                    } else {
                                        if ((int)$timesents < (int)$timeaway && $fortime <= $abadontime && $abadontime <= $timenow) {
                                            $subject = $this->getsubjectemailtemplate($reminder->id_emailtemplate, $cart["id_lang"]);
                                            $subject['subject'] = str_replace(
                                                array('{customer_firstname}','{customer_lastname}'),
                                                array($customers->firstname,$customers->lastname),
                                                $subject['subject']
                                            );
                                            $code    = $this->updategabandonedcart($cart, $reminder, $conditionandreminder);
                                            $this->updatetimesent($cart["id_cart"], $timeaway);
                                            $this->updatecartawait($cart["id_cart"], $conditionandreminder["id_gconditionandreminder"], $conditionandreminder["rulename"], $code, $subject['name'], $reminder);
                                            $datas  = $this->showdataarrayemailtxthtmlfile($customers, $obj_cart, $subject['subject'], $code);
                                            $params = $datas;
                                            Mail::Send((int)$cart["id_lang"], (int)$reminder->id_emailtemplate . '_' . (int) $cart["id_shop"], $subject['subject'], $params, $customers->email, $customers->firstname . ' ' . $customers->lastname, null, null, null, null, _PS_MODULE_DIR_ . "g_cartreminder/mails/", false, (int)$cart["id_shop"]);
                                            $sendmail[$cart["id_cart"]] = true;
                                        }
                                    }
                                } else {
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        $carts = $this->getallcartnotify($id_shop_group, $id_shop);
        if($carts)
            foreach ($carts as $cart) {
                $total_item = (int)Cart::getNbProducts((int)$cart["id_cart"]);
                if ($total_item > 0) {
                    $customers = new Customer($cart["id_customer"]);
                    $customers_firstname = '';$customers_lastname='';
                    if(Validate::isLoadedObject($customers)){
                        $customers_firstname = $customers->firstname;
                        $customers_lastname = $customers->lastname;
                    };
                    if(!isset($notification_carts[(int)$cart["id_cart"]])) $notification_carts[(int)$cart["id_cart"]] = array();
                    $notification_carts[(int)$cart["id_cart"]] = 
                        array(
                            'total_items'=>(int)$total_item,
                            'customer_firstname'=>$customers_firstname,
                            'customer_lastname'=>$customers_lastname,
                            'date_add'=>$cart["date_add"]
                        );
                }
            }
        if(count($notification_carts) > 0){
            $objectclass = new GnotificationModel(1, null, $id_shop);
            $delay_notifications = array();
            $notification = Tools::jsonDecode($objectclass->setting_notification, true);
            if(isset($notification['delay_notification']) && trim($notification['delay_notification']) !=''){
                $delay_notifications = explode(',',$notification['delay_notification']);
                if($delay_notifications){
                    foreach($delay_notifications as $key=> &$delay_notification){
                        if($delay_notification !='')
                            $delay_notification = explode(';',$delay_notification);
                        else unset($delay_notifications[$key]);
                    }
                }
            }
            $base_url = Context::getContext()->shop->getBaseURL().'modules/g_cartreminder/';
            if (Validate::isLoadedObject($objectclass)) {
                $settings = Tools::jsonDecode($objectclass->setting_notification, true);
                if ($settings['notification_off'] == 1) {
                    if($delay_notifications){
                        foreach($delay_notifications as $delay_notification){
                            if(isset($delay_notification[0]) && isset($delay_notification[1])){
                                $timeaway   = ((int)$delay_notification[0] * 86400) + ((int)$delay_notification[1] * 3600);
                                if(count($notification_carts) > 0){
                                    foreach ($notification_carts as $id_cart=>&$cart) {
                                        $timesents  = $this->getnotificationtimesent($id_cart);
                                        $abadontime = $timeaway+ strtotime($cart["date_add"]);   
                                        if (!$timesents || ($timesents + ($time_cronjob_run*3600) <= $timenow)) {                                     
                                            if ($fortime <= $abadontime && $abadontime <= $timenow) {
                                                $default_notification = '';$headings_default_notification = '';
                                                foreach (Language::getLanguages(false) as $lang){
                                                    $headings[$lang['iso_code']] = str_replace(array("\n", "\r"), ' ',strip_tags($this->Replacetext($objectclass->title_notification[$lang['id_lang']], $cart['total_items'], $cart['customer_firstname'], $cart['customer_lastname'])));
                                                    $content[$lang['iso_code']]  = str_replace(array("\n", "\r"), ' ',strip_tags($this->Replacetext($objectclass->message_notification[$lang['id_lang']], $cart['total_items'], $cart['customer_firstname'], $cart['customer_lastname'])));
                                                    if($default_notification == '') {
                                                        $default_notification = $content[$lang['iso_code']];
                                                        $headings_default_notification = $headings[$lang['iso_code']];
                                                    }
                                                }
                                                if(!isset($content['en'])){
                                                    $content['en'] = $default_notification;
                                                    $headings['en'] =  $headings_default_notification;
                                                }
                                                $img    = $base_url.'image/browser/'.$settings['img_icon'];
                                                $url    = $getshopprotocol .$link->getPageLink('order', null, $id_lang, null, false, $id_shop, true);
                                                $app_id = $settings['apponesignal_id'];
                                                $Authorization = $settings['apponesignal_api_id'];
                                                $response      = $this->sendMessage((int)$id_cart,$headings, $content, $img, $url, $app_id, $Authorization );
                                                $return = array();
                                                $return["allresponses"] = $response;
                                                $return = json_encode( $return);
                                                print("\n\nJSON received:\n");
                                                print($return);
                                                print("\n");
                                                unset($notification_carts[(int)$id_cart]);
                                                Db::getInstance()->execute(
                                                    'INSERT INTO `' . _DB_PREFIX_ . 'g_notification_log` (`id_cart`,`time`,`id_shop`) 
                                                     VALUES('.(int)$id_cart.','.pSql($timenow).','.(int)$id_shop.')');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
        }           
    }
    public function Replacetext($string, $total,$customer_firstname, $customer_lastname) {
        if ($string == '') {
            return '';
        }
        $string = str_replace("{total_items}", $total, $string);
        $string = str_replace("{cart_link_start}", $this->getHTML('linkcartstart'), $string);
        $string = str_replace("{cart_link_end}", $this->getHTML('linkend'), $string);
        $string = str_replace("{customer_firstname}", $customer_firstname, $string);
        $string = str_replace("{customer_lastname}", $customer_lastname, $string);
        return $string;
    }
    /** get all cart **/
    public function getallcart($id_shop_group, $id_shop)
    {
        $day = Configuration::get('CONFIGGETCARTDAYS', null, $id_shop_group, $id_shop);
        $hr  = Configuration::get('CONFIGGETCARTHRS', null, $id_shop_group, $id_shop);
        $day = $day ? (int)$day : '0';
        $hr  =  $hr ? (int)$hr  : '1';
        $carts = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT * FROM `' . _DB_PREFIX_ . 'cart` as cart WHERE cart.id_customer > 0  AND cart.date_add >= "'.date('Y-m-d H:i:s', (time() - ($day * 24 *60 * 60 + $hr *60 * 60))).'"
            AND cart.id_cart NOT IN (
                    SELECT `id_cart`
    				FROM `'._DB_PREFIX_.'orders` as ors
    				WHERE ors.id_cart = cart.id_cart AND ors.id_shop = cart.id_shop AND ors.date_add >= "'.date('Y-m-d H:i:s', (time() - ($day * 24 *60 * 60 + $hr *60 * 60))).'")'
        );
        return $carts;
    }
    public function getallcartnotify($id_shop_group, $id_shop)
    {
        $day = Configuration::get('CONFIGGETCARTDAYS', null, $id_shop_group, $id_shop);
        $hr  = Configuration::get('CONFIGGETCARTHRS', null, $id_shop_group, $id_shop);
        $day = $day ? (int)$day : '0';
        $hr  =  $hr ? (int)$hr  : '1';
        $carts = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT * FROM `' . _DB_PREFIX_ . 'cart` as cart 
            WHERE  cart.date_add >= "'.date('Y-m-d H:i:s', (time() - ($day * 24 *60 * 60 + $hr *60 * 60))).'" 
            AND cart.id_cart NOT IN (
                    SELECT `id_cart`
    				FROM `'._DB_PREFIX_.'orders` as ors
    				WHERE ors.id_cart = cart.id_cart AND ors.id_shop = cart.id_shop AND ors.date_add >= "'.date('Y-m-d H:i:s', (time() - ($day * 24 *60 * 60 + $hr *60 * 60))).'")'
        );
        return $carts;
    }
    /** get cart await **/
    public function getcartawait($id_cart, $id_reminder)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT * FROM `' . _DB_PREFIX_ . 'gabandoned_cart_await`
            WHERE `id_cart`= ' . (int)$id_cart .'
            AND `id_reminder` = ' . (int)$id_reminder);
    }
    /** get cart tiem sent **/
    public function getcarttimesent($id_cart)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT `timesent` FROM `' . _DB_PREFIX_ . 'gabandoned_cart_timesent`
            WHERE `id_cart`= ' . (int)$id_cart.' ORDER BY timesent DESC');
    }
    public function getnotificationtimesent($id_cart){
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT `time` FROM `' . _DB_PREFIX_ . 'g_notification_log`
            WHERE `id_cart`= ' . (int)$id_cart.' 
            ORDER BY time DESC
            ');
    }
    /** update and insert abadoned_cart_await **/
    public function updatecartawait($id_cart, $id_reminder, $rulename, $code, $emails, $reminder)
    {
        $idcart_rule = CartRule::getIdByCode($code);
        $cartrules = new CartRule($idcart_rule);
        $price = '';
        if ($reminder->discounttype == 1) {
            $price = Tools::displayPrice($cartrules->reduction_amount);
        } elseif ($reminder->discounttype == 0 && $reminder->discountvalue !='') {
            $price = $reminder->discountvalue ."%";
        }
        $res = (bool)Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . "gabandoned_cart_await`(`id_reminder`, `namereminder`, `id_cart`, `count`, `time`, `code`, `nameemailtp`)
            VALUES ('" . (int)$id_reminder . "', '" . pSQL($rulename) . "', '" . (int)$id_cart . "', '".(int)$reminder->number."', '".pSQL(date('Y-m-d H:i:s'))."', '".pSQL($price)."', '".pSQL($emails)."')");
        return $res;
    }
    /** update time sent **/
    public function updatetimesent($id_cart, $timeaway)
    {
        $cartsenttimes = $this->getcarttimesent($id_cart);
        if (empty($cartsenttimes)) {
            $res = (bool)Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . "gabandoned_cart_timesent`(`id_cart`, `timesent`)
                VALUES ('" . (int)$id_cart . "', '" . (int)$timeaway . "')");
            return $res;
        } else {
            $res = (bool)Db::getInstance()->execute("UPDATE `" . _DB_PREFIX_ . "gabandoned_cart_timesent`
                SET `timesent` = '".pSQL($timeaway)."'
                WHERE `id_cart` = " . (int)$id_cart);
            return $res;
        }
    }
    /** get all condition and reminder **/
    public function getconditionandreminder()
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT * FROM `' . _DB_PREFIX_ . 'gconditionandreminder` o
            LEFT JOIN `' . _DB_PREFIX_ . 'gconditionandreminder_lang` ol
            ON o.id_gconditionandreminder = ol.id_gconditionandreminder
            LEFT JOIN `' . _DB_PREFIX_ . 'gconditionandreminder_shop` os
            ON o.id_gconditionandreminder = os.id_gconditionandreminder
            WHERE o.active = 1
            ORDER BY o.position ASC');
    }
    /** check time reminder **/
    public function checktimeexpired($timenow, $datefrom, $dateto)
    {
        if (!empty($datefrom) || !empty($dateto)) {
            if ((!empty($datefrom) && $timenow <= $datefrom && $datefrom >0) || (!empty($dateto) && $timenow >= $dateto && $dateto >0)) {
                return false;
            }
        }
        return true;
    }
    /** set customer cart **/
    public function checkgroupcustomer($id_customer, $customergroups)
    {
        $groupall = Customer::getGroupsStatic($id_customer);
        $return = 0;
        if (!empty($customergroups)) {
            foreach ($customergroups as $customergroup) {
                if (in_array($customergroup, $groupall)) {
                    $return = 1;
                    break;
                }
            }
        }
        return $return;
    }
    /** datas array email **/
    public function showdataarrayemailtxthtmlfile($customers, $carts, $code)
    {
        $link     = new link;
        $Protocol = trim(Tools::getShopProtocol(), '/');
        $product_cart   = $carts->getProducts(true);
        $currency = new Currency($carts->id_currency);
        if($product_cart)
            foreach($product_cart as &$product){
                if(isset($product['total_wt']))
                    $product['total_wt'] = Tools::displayPrice($product['total_wt'], $currency);
                if(isset($product['total']))
                    $product['total'] = Tools::displayPrice($product['total'], $currency);
            }
        $validity       = $this->fomartvalidity($carts->id_lang, $code);
        $link_shopstart = $Protocol .$link->getPageLink("index", null, $carts->id_lang, null, false, $carts->id_shop, true);
        $link_cartstart = $Protocol .$link->getPageLink('order', null, $carts->id_lang, null, false, $carts->id_shop, true);
        Context::getContext()->smarty->assign(array('link_shopstart'=>$link_shopstart, 'link_cartstart'=>$link_cartstart));
        $id_shop_group = Shop::getGroupFromShop($carts->id_shop, false);
        $google_tracking_id = Configuration::get('GC_EMAIL_TRACKING_ID', null, $id_shop_group, $carts->id_shop);
        $datas = array(
            '{customer_firstname}' => $customers->firstname,
            '{customer_lastname}'  => $customers->lastname,
            '{cart_product}' => $this->tplproduct($product_cart, $link, $carts->id_lang),
            '{cart_product_txt}'=> $this->converthtmltxt($this->tplproduct($product_cart, $link, $carts->id_lang)),
            '{shop_link_start}' => $this->getHTML('linkstart'),
            '{shop_link_end}'   => $this->getHTML('linkend'),
            '{shop_link_url}'   => $Protocol .$link->getPageLink('index', null, $carts->id_lang, null, false, $carts->id_shop, true),
            '{cart_link_start}' => $this->getHTML('linkcartstart'),
            '{cart_link_end}'   => $this->getHTML('linkend'),
            '{cart_url}'        => $Protocol .$link->getPageLink('order', null, $carts->id_lang, "step=3", false, $carts->id_shop, true),
            '{cart_url_s1}'     => $Protocol .$link->getPageLink('order', null, $carts->id_lang, "step=1", false, $carts->id_shop, true),
            '{cart_url_s2}'     => $Protocol .$link->getPageLink('order', null, $carts->id_lang, "step=2", false, $carts->id_shop, true),
            '{voucher_code}'    => $code,
            '{custom_message}'  => '',
            '{voucher_expirate_date}' => $validity,
            '{google_tracking_id}'=>$google_tracking_id
            );
        return $datas;
    }
    /** product in your cart **/
    public function tplproduct($products, $link, $id_lang)
    {
        Context::getContext()->smarty->assign('gproducts', $products);
        Context::getContext()->smarty->assign('links', $link);
        Context::getContext()->smarty->assign('id_lang', $id_lang);
        Context::getContext()->smarty->assign('protocol', Tools::getShopProtocol());
        return Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/defaulttemplate/productitem.tpl');
    }
    /** gen code cart rule **/
    public function getCodediscount($id_cart, $dc_type, $dc_value, $dc_validiti, $minimunamount, $dc_freeship)
    {
        if($dc_value <=0 && ($dc_freeship == 0 || $dc_freeship == '')){
            return '';
        }
        $cartrule = new CartRule();
        $code = Tools::strtoupper(Tools::substr(md5(time()), 0, 8));
        $time_now = time();
        $cart = new Cart($id_cart);
        $date_from = date("Y-m-d H:i:s", $time_now);
        $lang = Language::getLanguages(false);
        foreach ($lang as $value_lang) {
            $cartrule->name[$value_lang["id_lang"]] = "Cart Reminder Code";
        }
        if ($dc_type == "1") {
            $cartrule->date_from = $date_from;
            $date_create = date_create($date_from);
            $date_create_from = (int)$dc_validiti . " days";
            $time_to = $time_now + $dc_validiti * 24 * 60 * 60;
            $date_to = date("Y-m-d H:i:s", $time_to);
            $cartrule->date_to = $date_to;
            $cartrule->code = $code;
            $cartrule->minimum_amount = $minimunamount;
            $cartrule->cart_rule_restriction = "1";
            $cartrule->reduction_amount = (float)$dc_value;
            $cartrule->reduction_currency = $cart->id_currency;
            $cartrule->free_shipping = $dc_freeship;
            $cartrule->date_add = $date_from;
        } elseif ($dc_type == "0") {
            $cartrule->date_from = $date_from;
            $date_create = date_create($date_from);
            $date_create_from = $dc_validiti . " days";
            date_add($date_create, date_interval_create_from_date_string($date_create_from));
            $time_to = $time_now + $dc_validiti * 24 * 60 * 60;
            $date_to = date("Y-m-d H:i:s", $time_to);
            $cartrule->date_to = $date_to;
            $cartrule->code = $code;
            $cartrule->cart_rule_restriction = "1";
            $cartrule->reduction_percent = $dc_value;
            $cartrule->minimum_amount = $minimunamount;
            $cartrule->free_shipping = $dc_freeship;
            $cartrule->date_add = $date_from;
        } else {
            return "";
        }
        $cartrule->add();
        return ($code);
    }
    /** format ngày tháng theo lang của cart **/
    public function fomartvalidity($id_lang, $code)
    {
        $lang = new language($id_lang);
        $idcart_rule = CartRule::getIdByCode($code);
        if (!empty($idcart_rule)) {
            $cartrules = new CartRule($idcart_rule);
            $dateFormatLite = $lang->date_format_lite;
            $date = strtotime($cartrules->date_to);
            return date($dateFormatLite, $date);
        } else {
            return "";
        }
    }
    /** lấy suject theo id lang của cart **/
    public function getsubjectemailtemplate($id_emailtemplate, $id_lang)
    {
        $subject = '';
        $name = '';
        $sql = 'SELECT a.*, b.*
            FROM `' . _DB_PREFIX_ . 'gaddnewemail_template_lang` AS a
            INNER JOIN `' . _DB_PREFIX_ . 'gaddnewemail_template` AS b
            ON a.id_gaddnewemail_template = b.id_gaddnewemail_template
            WHERE a.id_gaddnewemail_template = ' . (int)$id_emailtemplate . '
            AND a.id_lang =' . (int)$id_lang;
        $res = Db::getInstance()->getRow($sql);
        if (!empty($res)) {
            $subject = $res['subjectlang'];
            $name = $res['template_name'];
        }
        return array('subject' => $subject, 'name' => $name);
    }
    /** * update abadonecart * $carts array cart * $reminder object * $conditionandreminders aray * update and insert data **/
    public function updategabandonedcart($carts, $reminder, $conditionandreminders)
    {
        $cartwaits = $this->getgabandonedcart($carts["id_cart"]);
        $id_shop   = (int)Context::getContext()->shop->id;
        if (empty($cartwaits)) {
            $idmax = (int)$this->getidmaxabadonedcart();
            $idmax = $idmax ? $idmax + 1 : 1;
            $mincartamounts   = Tools::jsonDecode($conditionandreminders["mincartamount"], true);
            $code  = $this->getCodediscount($carts["id_cart"], $reminder->discounttype, $reminder->discountvalue, $reminder->counponvalidity, $mincartamounts[$carts["id_currency"]], $reminder->freeshipping);
            $id_gconditionandreminder = Tools::jsonEncode(array($conditionandreminders["id_gconditionandreminder"]));
            $res = (bool)Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . "gabandoned_cart`(`id_reminder`, `id_cart`, `id_tempalte`, `count`, `code`, `status_senmail`)
                VALUES ('" . pSQL($id_gconditionandreminder) . "', '" . (int)$carts["id_cart"] . "', '" . (int)$reminder->id_emailtemplate . "', 1, '" . pSQL($code) . "', 1)");
            foreach (Language::getLanguages(false) as $lang) {
                $res &= (bool)Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . "gabandoned_cart_lang`(`id_gabandoned_cart`,`id_lang`)
                VALUES ('" . (int)$idmax . "', '" . (int)$lang['id_lang'] . "')");
            }
            $res &= (bool)Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . "gabandoned_cart_shop`(`id_gabandoned_cart`, `id_shop`)
                VALUES ('" . (int)$idmax . "','" . (int)$id_shop . "')");
            return $code;
        } else {
            foreach ($cartwaits as $cartwait) {
                $codetrim = trim($cartwait['code']);
                $idcart_rule = CartRule::getIdByCode($codetrim);
                if (!empty($idcart_rule)) {
                    $this->deletecartrule($idcart_rule);
                }
                $id               = $cartwait["id_gabandoned_cart"];
                $mincartamounts   = Tools::jsonDecode($conditionandreminders["mincartamount"], true);
                $newcode = $this->getCodediscount($carts["id_cart"], $reminder->discounttype, $reminder-> discountvalue, $reminder->counponvalidity, $mincartamounts[$carts["id_currency"]], $reminder->freeshipping);
                $arrayreminders = Tools::jsonDecode($cartwait["id_reminder"], true);
                if (empty($arrayreminders)) {
                    if (!empty($cartwait["id_reminder"])) {
                        $arrayreminders = array($cartwait["id_reminder"]);
                    } else {
                        $arrayreminders = array();
                    }
                } elseif (!in_array($conditionandreminders["id_gconditionandreminder"], $arrayreminders)) {
                    array_push($arrayreminders, $conditionandreminders["id_gconditionandreminder"]);
                }
                $arrayreminders = Tools::jsonEncode($arrayreminders);
                $res = (bool)Db::getInstance()->execute("UPDATE `" . _DB_PREFIX_ . "gabandoned_cart`
                SET `id_reminder` = '" . pSQL($arrayreminders) . "', `count` = `count` + 1, `code` = '" . pSQL($newcode) . "'
                WHERE `id_gabandoned_cart` = " . (int)$id);
            }
            return $newcode;
        }
    }
    public function getidmaxabadonedcart()
    {
        return Db::getInstance()->getValue('SELECT `id_gabandoned_cart` FROM `' . _DB_PREFIX_ . 'gabandoned_cart`
        ORDER BY `id_gabandoned_cart` DESC');
    }
    /** select data gabadonecart **/
    public function getgabandonedcart($id_cart)
    {
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'gabandoned_cart`
        WHERE `id_cart` = ' . (int)$id_cart;
        return Db::getInstance()->executeS($sql);
    }
    /** delete cart rule * $id id cart rule **/
    public function deletecartrule($id)
    {
        $r = Db::getInstance()->delete('cart_rule', '`id_cart_rule` = '.(int)$id);
        $r &= Db::getInstance()->delete('cart_rule_shop', '`id_cart_rule` = '.(int)$id);
        $r &= Db::getInstance()->delete('cart_rule_group', '`id_cart_rule` = '.(int)$id);
        $r &= Db::getInstance()->delete('cart_rule_country', '`id_cart_rule` = '.(int)$id);
        $r &= Db::getInstance()->delete('cart_rule_lang', '`id_cart_rule` = '.(int)$id);

        return $r;
    }
    /** convert html - >txt in data **/
    public function converthtmltxt($html)
    {
        $string = trim(preg_replace('/<[^>]*>/', '\n', $html));
        $output = str_replace(array('\r\n', '\r'), '\n', $string);
        $lines = explode('\n', $output);
        $new_lines = array();
        foreach ($lines as $line) {
            $line = trim($line, '\n');
            $line = trim($line);
            if (!empty($line) && $line != ' ') {
                $new_lines[] = trim($line);
            }
        }
        $string = trim(implode('<br><br>', $new_lines), '<br>');
        return $string;
    }
    public function sendMessage($id_cart,$headings, $content, $img, $url, $app_id, $Authorization) {
		$fields = array(
			'app_id' => $app_id,
            'filters' => array(array("field" => "tag", "key" => "id_cart", "relation" => "=", "value" => (int)$id_cart)),
			//'included_segments' => array('All'),
            'headings' => $headings,
            'data' => array("foo" => "bar"),
			'contents' => $content,
            'big_picture'=> $img,
            'adm_big_picture' => $img,
            'chrome_big_picture' => $img,
            'icon' => $img,
            'url' => $url,
		);

        $fields = json_encode($fields);
        //print("\nJSON sent:\n");
        //print($fields);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic '.$Authorization.''));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
    public function getHTML($name){
        Context::getContext()->smarty->assign(array('name'=>$name));
        $html  = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/linksendmail/html.tpl');
        return $html;
    }
}
