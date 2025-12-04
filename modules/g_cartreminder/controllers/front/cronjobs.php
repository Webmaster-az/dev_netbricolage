<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 *
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link         http://www.globosoftware.net
 */

require_once(_PS_MODULE_DIR_ . "g_cartreminder/g_cartreminder.php");
include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GcartremindercondreminderModel.php');
include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GcartreminderemailModel.php');
include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GabadoneModel.php');
class G_cartreminderCronjobsModuleFrontController extends ModuleFrontController{
    public function __construct()
    {
        $Modulecall = Module::getInstanceByName('g_cartreminder');
        if (Tools::getValue('token') != sha1(_COOKIE_KEY_ . 'g_cartreminder')) {
            echo $Modulecall->l("invalid url error.");
            die;
        }
        $headings   = array();
        $content    = array();
        $return     = array();
        $link       = new Link();
        $id_shop       = Context::getContext()->shop->id;
        $id_shop_group = (int)Shop::getContextShopGroupID();
        $id_lang       = Context::getContext()->language->id;
        $getshopprotocol       = trim(Tools::getShopProtocol(), '/');
        $notification_carts = array();
        $timenow_old = date("Y-m-d H:i:s", time());
        $timenow  = strtotime($timenow_old);
        $time_cronjob_run = 1;/* 1 hr */
        $fortime  = (int)$timenow - $time_cronjob_run*3600;
        
        $conditionandreminders =GcartreminderemailModel::getconditionandreminder();
        if (!empty($conditionandreminders)) {
            $sendmail = array();
            foreach ($conditionandreminders as $conditionandreminder) {
                $reminder_groups = array();
                $custormmers     = array();
                $datefrom = strtotime($conditionandreminder["datefrom"]);
                $dateto   = strtotime($conditionandreminder["dateto"]);
                if ($conditionandreminder['reminder_group'])
                    $reminder_groups = Tools::jsonDecode($conditionandreminder['reminder_group'], true);
                if ($conditionandreminder['custormmer'])
                    $custormmers = Tools::jsonDecode($conditionandreminder['custormmer'], true);
                $manuals_rules = array(
                    'datefrom'   => $conditionandreminder['datefrom'],
                    'dateto'     => $conditionandreminder['dateto'],
                    'custormmer' => $custormmers,
                );
                /*get cart reminder group*/
                $carts = $Modulecall->getShoppingCartByReminder($manuals_rules, false, false, $reminder_groups, array(), 0, 0, false, true, $id_shop, $id_shop_group);
                $checktimeout  = $this->checktimeexpired($timenow, $datefrom, $dateto);
                if ($checktimeout == true) {
                    $reminders = Tools::jsonDecode($conditionandreminder["reminder"], true);
                    if($carts)
                        foreach ($carts as $cart) {
                            $obj_cart           = new Cart((int)$cart["id_cart"]);
                            $id_lang            = (int)$obj_cart->id_lang;
                            $id_shop            = (int)$obj_cart->id_shop;
                            $id_cart            = (int)$obj_cart->id;
                            $mincartamount      = Tools::jsonDecode($conditionandreminder["mincartamount"], true);
                            $maxcartamount      = Tools::jsonDecode($conditionandreminder["maxcartamount"], true);
                            $total_produc_price = $obj_cart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
                            $dkallgroup         = $this->checkgroupcustomer($cart["id_customer"], $custormmers);
                            $productcart        = $obj_cart->getProducts(true);
                            if (((float)$mincartamount[$obj_cart->id_currency] > 0 && (float)$total_produc_price < (float)$mincartamount[$obj_cart->id_currency]) || ( (float)$maxcartamount[$obj_cart->id_currency] > 0 && (float)$total_produc_price > (float)$maxcartamount[$obj_cart->id_currency])) {
                                continue;
                            }
                            if ($dkallgroup != 0 && !empty($productcart) && $id_lang == $conditionandreminder["id_lang"] && ($id_shop == $conditionandreminder["id_shop"] || $id_shop == 0 || $conditionandreminder["id_shop"] == '' || empty($conditionandreminder["id_shop"]))) {
                                
                                $customerObj = new Customer($obj_cart->id_customer);
                                /*reminder send email*/
                                if(!Validate::isLoadedObject($customerObj)) continue;
                                foreach ($reminders as $reminder) {
                                    if (!isset($sendmail[$id_cart]) || !$sendmail[$id_cart]) {
                                        $timesents  = $this->getcarttimesent($id_cart);
                                        $timeaway   = ((int)$reminder['gday'] * 86400) + ((int)$reminder['ghrs'] * 3600);
                                        $abadontime = $timeaway+ strtotime($cart["date_add"]);
                                        if (!empty($timesents)) {
                                            if ((int)$timesents < (int)$timeaway && $timesents != $timeaway && $fortime < ($timeaway + $timenow) && $fortime <= $abadontime && $abadontime <= $timenow) {
                                                $subject = $this->getsubjectemailtemplate($reminder['id_emailtemplate'], $id_lang);
                                                $subject['subject'] = str_replace(
                                                    array('{customer_firstname}','{customer_lastname}'),
                                                    array($customerObj->firstname,$customerObj->lastname),
                                                    $subject['subject']
                                                );
                                                $code    = $this->updategabandonedcart($obj_cart, $reminder, $conditionandreminder, $total_produc_price);
                                                $this->updatetimesent($id_cart, $timeaway);
                                                $this->updatecartawait($id_cart, $conditionandreminder["id_gconditionandreminder"], $conditionandreminder["rulename"], $code, $subject['name'], $reminder);
                                                $params  = GcartreminderemailModel::showdataarrayemailtxthtmlfile($customerObj, $obj_cart, $subject['subject'], $code);
                                                Mail::Send(
                                                    (int)$id_lang,
                                                    (int)$reminder['id_emailtemplate'] . '_' . (int) $id_shop,
                                                    $subject['subject'],
                                                    $params,
                                                    $customerObj->email,
                                                    $customerObj->firstname . ' ' . $customerObj->lastname,
                                                    null,
                                                    null,
                                                    null,
                                                    null,
                                                    _PS_MODULE_DIR_ . "g_cartreminder/mails/",
                                                    false,
                                                    (int)$id_shop
                                                );
                                                $sendmail[$id_cart] = true;
                                            }
                                        } else {
                                            if ((int)$timesents < (int)$timeaway /*&& $fortime <= $abadontime*/ && $abadontime <= $timenow) {
                                                $subject = $this->getsubjectemailtemplate($reminder['id_emailtemplate'], $id_lang);
                                                $subject['subject'] = str_replace(
                                                    array('{customer_firstname}','{customer_lastname}'),
                                                    array($customerObj->firstname,$customerObj->lastname),
                                                    $subject['subject']
                                                );
                                                $code = $this->updategabandonedcart($obj_cart, $reminder, $conditionandreminder, $total_produc_price);
                                                $this->updatetimesent($id_cart, $timeaway);
                                                $this->updatecartawait($id_cart, $conditionandreminder["id_gconditionandreminder"], $conditionandreminder["rulename"], $code, $subject['name'], $reminder);
                                                $params  = GcartreminderemailModel::showdataarrayemailtxthtmlfile($customerObj, $obj_cart, $subject['subject'], $code);
                                                Mail::Send(
                                                    (int)$id_lang,
                                                    (int)$reminder['id_emailtemplate'] . '_' . (int) $id_shop, 
                                                    $subject['subject'], 
                                                    $params, 
                                                    $customerObj->email, 
                                                    $customerObj->firstname . ' ' . $customerObj->lastname, 
                                                    null, 
                                                    null, 
                                                    null, 
                                                    null, 
                                                    _PS_MODULE_DIR_ . "g_cartreminder/mails/", 
                                                    false, 
                                                    (int)$id_shop
                                                );
                                                $sendmail[$id_cart] = true;
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
        /*notify  send*/
        $id_shop       = Context::getContext()->shop->id;
        $id_shop_group = (int)Shop::getContextShopGroupID();
        $id_lang       = Context::getContext()->language->id;
        $carts = $this->getallcartnotify($id_shop_group, $id_shop);
        if($carts)
            foreach ($carts as $cart) {
                $obj_cart   = new Cart((int)$cart["id_cart"]);
                $id_cart    =  $obj_cart->id;
                $total_item = (int)Cart::getNbProducts((int)$id_cart);
                if ($total_item > 0) {
                    $customerObj = new Customer($obj_cart->id_customer);
                    $customers_firstname = '';$customers_lastname='';
                    if(Validate::isLoadedObject($customerObj)){
                        $customers_firstname = $customerObj->firstname;
                        $customers_lastname  = $customerObj->lastname;
                    };
                    if(!isset($notification_carts[(int)$id_cart])) $notification_carts[(int)$id_cart] = array();
                    $notification_carts[(int)$id_cart] = 
                        array(
                            'total_items'        =>(int)$total_item,
                            'customer_firstname' =>$customers_firstname,
                            'customer_lastname'  =>$customers_lastname,
                            'date_add'           =>$cart["date_add"]
                        );
                }
            }
        if(count($notification_carts) > 0){
            $objectclass = new GnotificationModel(1, null, $id_shop);
            $delay_notifications = array();
            if (Validate::isLoadedObject($objectclass)) {
                    
                $notification = Tools::jsonDecode($objectclass->setting_notification, true);
                if(isset($notification['delay_notification']) && trim($notification['delay_notification']) !=''){
                    $delay_notifications = explode(',',$notification['delay_notification']);
                    if($delay_notifications){
                        foreach($delay_notifications as $key => &$delay_notification){
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
        echo $Modulecall->l('Successfull!');
        die;    
    }
    public function Replacetext($string, $total,$customer_firstname, $customer_lastname) {
        if ($string == '') {
            return '';
        }
        $string = str_replace("{total_items}", $total, $string);
        $string = str_replace("{cart_link_start}", GcartreminderemailModel::getHTML('linkcartstart'), $string);
        $string = str_replace("{cart_link_end}", GcartreminderemailModel::getHTML('linkend'), $string);
        $string = str_replace("{customer_firstname}", $customer_firstname, $string);
        $string = str_replace("{customer_lastname}", $customer_lastname, $string);
        return $string;
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
        if ($reminder['discounttype'] == 1) {
            $price = Tools::displayPrice($cartrules->reduction_amount);
        } elseif ($reminder['discounttype'] == 0) {
            $price = (float)$cartrules->reduction_percent ."%";
        }
        $res = (bool)Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . "gabandoned_cart_await`(`id_reminder`, `namereminder`, `id_cart`, `count`, `time`, `code`, `nameemailtp`)
            VALUES ('" . (int)$id_reminder . "', '" . pSQL($rulename) . "', '" . (int)$id_cart . "', '".(int)$reminder["number"]."', '".pSQL(date('Y-m-d H:i:s'))."', '".pSQL($price)."', '".pSQL($emails)."')");
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
    /** lấy suject theo id lang của cart **/
    public function getsubjectemailtemplate($id_emailtemplate, $id_lang)
    {
        $subject = '';
        $name = '';
        $sql = 'SELECT a.*, b.*
            FROM `' . _DB_PREFIX_ . 'gaddnewemail_template_lang` AS a
            INNER JOIN `' . _DB_PREFIX_ . 'gaddnewemail_template` AS b
            ON a.`id_gaddnewemail_template` = b.`id_gaddnewemail_template`
            WHERE a.`id_gaddnewemail_template` = ' . (int)$id_emailtemplate . '
            AND a.id_lang =' . (int)$id_lang;
        $res = Db::getInstance()->getRow($sql);
        if (!empty($res)) {
            $subject = $res['subjectlang'];
            $name = $res['template_name'];
        }
        return array('subject' => $subject, 'name' => $name);
    }
    /** * update abadonecart * $carts array cart * $reminder object * $conditionandreminders aray * update and insert data **/
    public function updategabandonedcart($obj_cart, $options, $conditionandreminders, $total_produc_price)
    {
        $cartwaits = GcartreminderemailModel::getgabandonedcart((int)$obj_cart->id);
        $reduction_tax  =  0;
        $minimum_amount = 0;
        $dc_value       = 0;
        $counponvalidity= 0;
        $freeshipping = 0;
        $discounttype = 0;
        $id_currency  =  (int)$obj_cart->id_currency;
        if (isset($options['pricerule']) && count($options['pricerule'])) {
            foreach ($options['pricerule'] as $pricerule) {
                if ((float)$pricerule['minprice'][$obj_cart->id_currency] < $total_produc_price && $total_produc_price < (float)$pricerule['maxprice'][$obj_cart->id_currency] ) {
                    $dc_value = (float)$pricerule['discountvalue'];
                    $id_currency    =  $pricerule['reduction_currency'];
                    $reduction_tax  =  $pricerule['reduction_tax'];
                    $minimum_amount = (float)$pricerule['minprice'][$obj_cart->id_currency];
                    $counponvalidity = (int)$options['counponvalidity'];
                    $freeshipping    = (int)$options['freeshipping'];
                    $discounttype    = $options['discounttype'];
                    break;
                }
            }
        }
        if (empty($cartwaits)) {
            $mincartamounts   = Tools::jsonDecode($conditionandreminders["mincartamount"], true);$mincartamounts;
            $code             = GcartreminderemailModel::getCodediscount($obj_cart, $discounttype, $counponvalidity, $freeshipping, $reduction_tax, $minimum_amount, $dc_value, $id_currency);
            $id_gconditionandreminder = Tools::jsonEncode(array($conditionandreminders["id_gconditionandreminder"]));
            /*add new gabandoned_cart*/
            $GabadoneModel = new GabadoneModel();
            $GabadoneModel->id_cart     =  (int)$obj_cart->id;
            $GabadoneModel->id_reminder =  $id_gconditionandreminder;
            $GabadoneModel->status_senmail =  1;
            $GabadoneModel->count =  1;
            $GabadoneModel->code  =  $code;
            $GabadoneModel->id_tempalte =  $conditionandreminders['id_emailtemplate'];
            if ($GabadoneModel->save())
                return $code;
            else
                return $code;
        } else {
            foreach ($cartwaits as $cartwait) {
                $codetrim = trim($cartwait['code']);
                $idcart_rule = CartRule::getIdByCode($codetrim);
                if (!empty($idcart_rule)) {
                    GcartreminderemailModel::deletecartrule($idcart_rule);
                }
                $id               = $cartwait["id_gabandoned_cart"];
                $mincartamounts   = Tools::jsonDecode($conditionandreminders["mincartamount"], true);$mincartamounts;
                $newcode          = GcartreminderemailModel::getCodediscount($obj_cart, $discounttype, $counponvalidity, $freeshipping, $reduction_tax, $minimum_amount, $dc_value, $id_currency);
                $arrayreminders   = Tools::jsonDecode($cartwait["id_reminder"], true);
                if (empty($arrayreminders)) {
                    if (!empty($cartwait["id_reminder"])) {
                        $arrayreminders = array($cartwait["id_reminder"]);
                    } else {
                        $arrayreminders = array();
                    }
                } elseif (!in_array($conditionandreminders["id_gconditionandreminder"], $arrayreminders)) {
                    array_push($arrayreminders, $conditionandreminders["id_gconditionandreminder"]);
                }
                /*update new gabandoned_cart*/
                $arrayreminders = Tools::jsonEncode($arrayreminders);
                $res = (bool)Db::getInstance()->execute("UPDATE `" . _DB_PREFIX_ . "gabandoned_cart`
                SET `id_reminder` = '" . pSQL($arrayreminders) . "', `count` = `count` + 1, `code` = '" . pSQL($newcode) . "'
                WHERE `id_gabandoned_cart` = " . (int)$id);
                $res;
            }
            return $newcode;
        }
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
}
