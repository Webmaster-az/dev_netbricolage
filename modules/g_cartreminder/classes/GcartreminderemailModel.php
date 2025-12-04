<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */

class GcartreminderemailModel extends ObjectModel
{
    public $id_gaddnewemail_template;
    public $template_name;
    public $subject;
    public $sample_email;
    public $email_html;
    public $email_txt;
    public $datetimenow;
    public $subjectlang;
    public $email_htmllang;
    public $email_txtlang;
    public static $definition = array(
        'table' => 'gaddnewemail_template',
        'primary' => 'id_gaddnewemail_template',
        'multilang' => true,
        'fields' => array(
            //Fields
            'template_name' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
                'required' => true),
            'subject' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'sample_email' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'email_html' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
            'email_txt' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'datetimenow' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            //lang ps_lang
            'subjectlang' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isString'),
            'email_htmllang' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                ),
            'email_txtlang' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isString'),
            ),
        );

    public function __construct($id_gaddnewemail_template = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation('gaddnewemail_template', array('type' => 'shop'));
        parent::__construct($id_gaddnewemail_template, $id_lang, $id_shop);
        return true;
    }
    public function add($autodate = true, $nullValues = false)
    {
        $nullValues;
        $this->datetimenow = date('Y-m-d H:i:s');
        $return = parent::add($autodate, true);
        Hook::exec('actionGcartreminderemailModelSave', array('id_gaddnewemail_template' => $this->id));
        return $return;
    }
    public function update($nullValues = false)
    {
        $nullValues;
        $this->datetimenow = date('Y-m-d H:i:s');
        $return = parent::update(true);
        Hook::exec('actionGcartreminderemailModelUpdate', array('id_gaddnewemail_template' => $this->id));
        return $return;
    }

    public function parseEmail($datas, $shops)
    {
        $theme_dir = _PS_THEME_DIR_.'modules/g_cartreminder/mails/';
        $mails_dir = _PS_MODULE_DIR_ . 'g_cartreminder/mails/';
        $module_base = Tools::getShopProtocol() . Tools::getServerName() . __PS_BASE_URI__;
        $languages = Language::getLanguages(false);
        $version = 'PS16';
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
            $version = 'PS17';
        }
        if ($datas && $shops && $languages) {
            foreach ($datas as $data) {
                foreach ($languages as $lang) {
                    if (!is_dir($mails_dir . $lang['iso_code'] . '/')) {
                        @mkdir($mails_dir . $lang['iso_code'] . '/', 0755);
                    }
                    if (!file_exists($mails_dir . $lang['iso_code'] . '/index.php')) {
                        @copy(_PS_MODULE_DIR_ . 'g_cartreminder/index.php', $mails_dir . $lang['iso_code'] . '/index.php');
                    }
                    foreach ($shops as $shop_id) {
                        if ($data['id_lang'] == $lang['id_lang']) {
                            $file = $mails_dir . $lang['iso_code'] . '/' . $data['id_gaddnewemail_template'] . '_' . $shop_id . '.html';
                            $file_in_theme = $theme_dir . $lang['iso_code'] . '/' . $data['id_gaddnewemail_template'] . '_' . $shop_id . '.html';
                            if (file_exists($file_in_theme))
                                @unlink($file_in_theme);
                            $fields_value = array();
                            $data['email_htmllang'] = str_replace('%7B', '{', $data['email_htmllang']);
                            $data['email_htmllang'] = str_replace('%7D', '}', $data['email_htmllang']);
                            $fields_value = array(
                                'isocode_lang' => $lang['iso_code'],
                                'id_shop'   => $shop_id,
                                'id_lang'   => $lang['id_lang'],
                                'subjectup' => $data['subjectlang'],
                                'check' => '0',
                                'g_url' => $module_base,
                                'name'  => 'emailbase',
                                'version'=> $version,
                                'emailcontentup' => $data['email_htmllang']);
                            Context::getContext()->smarty->assign($fields_value);
                            $tpl = _PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/tabs/htmlall.tpl';
                            $emailcontent = Context::getContext()->smarty->fetch($tpl);
                            $handle = fopen($file, 'w+');
                            $emailcontent = $emailcontent;//Tools::purifyHTML($emailcontent,  null, true);
                            $emailcontent = str_replace('%7B', '{', $emailcontent);
                            $emailcontent = str_replace('%7D', '}', $emailcontent);
                            fwrite($handle, $emailcontent);
                            fclose($handle);
                            $file = $mails_dir . $lang['iso_code'] . '/' . $data['id_gaddnewemail_template'] . '_' . $shop_id . '.txt';
                            $file_in_theme = $theme_dir . $lang['iso_code'] . '/' . $data['id_gaddnewemail_template'] . '_' . $shop_id . '.txt';
                            if (file_exists($file_in_theme))
                                @unlink($file_in_theme);
                            $handle = fopen($file, 'w+');
                            fwrite($handle, $data['email_txtlang']);
                            fclose($handle);
                        }
                    }
                }
            }
        }
    }
    public static function getCodediscount($obj_cart, $discounttype, $counponvalidity, $freeshipping, $reduction_tax, $minimum_amount, $dc_value, $id_currency) {
        $obj_cart;
        $cartrule = new CartRule();
        $code = Tools::strtoupper(Tools::substr(md5(time()), 0, 8));
        $time_now = time();
        $date_from = date("Y-m-d H:i:s", $time_now);
        
        $lang = Language::getLanguages(false);
        foreach ($lang as $value_lang) {
            $cartrule->name[$value_lang["id_lang"]] = "Cart Reminder Code";
        }
        if ($discounttype == "1") {
            $cartrule->date_from = $date_from;
            $date_create = date_create($date_from);
            $date_create_from = (int)$counponvalidity . " days";
            $time_to = $time_now + (int)$counponvalidity * 24 * 60 * 60;
            $date_to = date("Y-m-d H:i:s", $time_to);
            $cartrule->date_to = $date_to;
            $cartrule->code = $code;
            $cartrule->minimum_amount = $minimum_amount;
            $cartrule->cart_rule_restriction = "1";
            $cartrule->reduction_amount = (float)$dc_value;
            $cartrule->reduction_currency = (int)$id_currency;
            $cartrule->free_shipping = (int)$freeshipping;
            $cartrule->reduction_tax = $reduction_tax;
            $cartrule->date_add = $date_from;
        } elseif ($discounttype == "0") {
            $cartrule->date_from = $date_from;
            $date_create = date_create($date_from);
            $date_create_from = (int)$counponvalidity . " days";
            date_add($date_create, date_interval_create_from_date_string($date_create_from));
            $time_to = $time_now + (int)$counponvalidity * 24 * 60 * 60;
            $date_to = date("Y-m-d H:i:s", $time_to);
            $cartrule->date_to = $date_to;
            $cartrule->code = $code;
            $cartrule->cart_rule_restriction = "1";
            $cartrule->reduction_percent = (float)$dc_value;
            $cartrule->minimum_amount = $minimum_amount;
            $cartrule->free_shipping = (int)$freeshipping;
            $cartrule->date_add = $date_from;
        } else {
            return "";
        }
        $cartrule->add();
        return ($code);
    }
    public static function getemployee($id_employee = null, $id_shop=0)
    {
        if ($id_employee == null) {
            $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'employee`
             INNER JOIN `' . _DB_PREFIX_ . 'employee_shop`
             ON `' . _DB_PREFIX_ . 'employee`.id_employee = `' . _DB_PREFIX_ . 'employee_shop`.id_employee
             WHERE `active` = 1 AND `id_shop`=' . (int)$id_shop;
            return Db::getInstance()->executeS($sql);
        } else {
            $employees = new Employee((int)$id_employee);
            return $employees;
        }
    }
    public static function checkdataabadonedcartbyidcart($id_cart)
    {
        return Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'gabandoned_cart`
        WHERE `id_cart`=' . (int)$id_cart);
    }
    public static function deletecartrule($id)
    {
        $r = Db::getInstance()->delete('cart_rule', '`id_cart_rule` = '.(int)$id);
        $r &= Db::getInstance()->delete('cart_rule_shop', '`id_cart_rule` = '.(int)$id);
        $r &= Db::getInstance()->delete('cart_rule_group', '`id_cart_rule` = '.(int)$id);
        $r &= Db::getInstance()->delete('cart_rule_country', '`id_cart_rule` = '.(int)$id);
        $r &= Db::getInstance()->delete('cart_rule_lang', '`id_cart_rule` = '.(int)$id);
        return $r;
    }
    public static function sendmailabadonedcart($obj_cart, $subject, $custommessage, $id_template, $code, $employees, $bcc)
    {
        $arraybcc   = array();
        $customers  = new Customer($obj_cart->id_customer);
        if (!empty($bcc)) {
            $bccs = explode(", ",$bcc);
            foreach ($bccs as $id) {
                $emloyees = GcartreminderemailModel::getemployee($id);
                if ($emloyees->id == $id) {
                    $arraybcc[$id] = $emloyees->email;
                }
            }
        }
        array_push($arraybcc, $customers->email);
        $params = GcartreminderemailModel::showdataarrayemailtxthtmlfile($customers, $obj_cart, $custommessage, $code);
        $mail_method = (int)Configuration::get('PS_MAIL_METHOD');
        foreach ($arraybcc as $email) {
            if($mail_method == 1)
                Mail::Send((int)$obj_cart->id_lang, (int)$id_template . '_' . (int)$obj_cart->id_shop, $subject, $params, $email, $customers->firstname . ' ' . $customers->lastname, $employees->email, $employees->firstname . ' ' . $employees->lastname, null, null, _PS_MODULE_DIR_ . "g_cartreminder/mails/", false, (int)$obj_cart->id_shop, null, null); 
            else{
                Mail::Send((int)$obj_cart->id_lang, (int)$id_template . '_' . (int)$obj_cart->id_shop, $subject, $params, $email, $customers->firstname . ' ' . $customers->lastname, null, null, null, null, _PS_MODULE_DIR_ . "g_cartreminder/mails/", false, (int)$obj_cart->id_shop, null, null); 
            }
        }
        return true;
    }
    public static function showdataarrayemailtxthtmlfile($customers, $obj_cart, $custommessage, $code, Context $context = null)
    {
        if (!$context) {
            $context = Context::getContext();
        }
        $link = $context->link;
        $getshopprotocol = trim(Tools::getShopProtocol(), '/');
        $arraycarts      = $obj_cart->getProducts(true);
        $currency        = new Currency($obj_cart->id_currency);
        if($arraycarts)
            foreach($arraycarts as &$product){
                if(isset($product['total_wt']))
                    $product['total_wt'] = Tools::displayPrice($product['total_wt'], $currency);
                if(isset($product['total']))
                    $product['total']    = Tools::displayPrice($product['total'], $currency);
            }
        $validitytime_lang = GcartreminderemailModel::fomartvalidity($obj_cart->id_lang, $code);
        $link_shopstart = $getshopprotocol .$link->getPageLink("index", null, $obj_cart->id_lang, null, false, $obj_cart->id_shop, true);
        $link_cartstart = $getshopprotocol .$link->getPageLink('order', null, $obj_cart->id_lang, null, false, $obj_cart->id_shop, true);
        $context->smarty->assign(
            array(
                'link_shopstart'=> $link_shopstart,
                'link_cartstart'=> $link_cartstart
            )
        );
        $id_shop_group = Shop::getGroupFromShop($obj_cart->id_shop, false);
        $google_tracking_id = Configuration::get('GC_EMAIL_TRACKING_ID', null, $id_shop_group, $obj_cart->id_shop);
        $datas = array(
            '{customer_firstname}' => $customers->firstname,
            '{customer_lastname}'  => $customers->lastname,
            '{cart_product}'       =>  GcartreminderemailModel::tplproduct($arraycarts, $link, $obj_cart->id_lang, 0),
            '{cart_product_1}'     =>  GcartreminderemailModel::tplproduct($arraycarts, $link, $obj_cart->id_lang, 1),
            '{cart_product_2}'     =>  GcartreminderemailModel::tplproduct($arraycarts, $link, $obj_cart->id_lang, 2),
            '{cart_product_txt}'   =>  GcartreminderemailModel::converthtmltxt( GcartreminderemailModel::tplproduct($arraycarts, $link, $obj_cart->id_lang, 0)),
            '{cart_product_txt_1}' =>  GcartreminderemailModel::converthtmltxt( GcartreminderemailModel::tplproduct($arraycarts, $link, $obj_cart->id_lang, 1)),
            '{cart_product_txt_2}' =>  GcartreminderemailModel::converthtmltxt( GcartreminderemailModel::tplproduct($arraycarts, $link, $obj_cart->id_lang, 2)),
            '{shop_link_start}'  =>  GcartreminderemailModel::getHTML('linkstart'),
            '{shop_link_end}'    =>  GcartreminderemailModel::getHTML('linkend'),
            '{shop_link_url}'    => $getshopprotocol .$link->getPageLink('index', null, $obj_cart->id_lang, null, false, $obj_cart->id_shop, true),
            '{cart_link_start}'  =>  GcartreminderemailModel::getHTML('linkcartstart'),
            '{cart_link_end}'    =>  GcartreminderemailModel::getHTML('linkend'),
            '{cart_url}'         => $getshopprotocol .$link->getPageLink('order', null, $obj_cart->id_lang, "step=3", false, $obj_cart->id_shop, true),
            '{cart_url_s1}'      => $getshopprotocol .$link->getPageLink('order', null, $obj_cart->id_lang, "step=1", false, $obj_cart->id_shop, true),
            '{cart_url_s2}'      => $getshopprotocol .$link->getPageLink('order', null, $obj_cart->id_lang, "step=2", false, $obj_cart->id_shop, true),
            '{voucher_code}'     => $code,
            '{custom_message}'   => $custommessage,
            '{voucher_expirate_date}' => $validitytime_lang,
            '{google_tracking_id}'    =>$google_tracking_id,
            '{shop_email}'   => Configuration::get('PS_SHOP_EMAIL'),
            '{shop_address}' => Configuration::get('PS_SHOP_ADDR1').' '.Configuration::get('PS_SHOP_ADDR2'),
            '{shop_phone}'=> Configuration::get('PS_SHOP_PHONE'),
            '{shop_fax}'  => Configuration::get('PS_SHOP_FAX'),
            '{shop_name}' => Tools::safeOutput(Configuration::get('PS_SHOP_NAME', null, null, $obj_cart->id_shop)),
            '{total_cart_excl}' => Cart::getTotalCart($obj_cart->id, false),
            '{total_cart_incl}' => Cart::getTotalCart($obj_cart->id, true),
        );
        return $datas;
    }
    public static function fomartvalidity($id_lang, $code)
    {
        $id = CartRule::getIdByCode($code);
        if (!empty($id)) {
            $cartrule = new CartRule($id);
            $lang = new language($id_lang);
            $dateFormatLite = $lang->date_format_lite;
            $time = strtotime($cartrule->date_to);
            return date($dateFormatLite, $time);
        } else {
            return "";
        }
    }
    public static function tplproduct($products, $link, $id_lang, $id_template, Context $context = null)
    {
        if (!$context) {
            $context = Context::getContext();
        }
        $context->smarty->assign(
            array(
                'gproducts'=> $products,
                'links'    => $link,
                'id_lang'  => $id_lang,
                'protocol' => Tools::getShopProtocol(),
            )
        );
        return $context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/defaulttemplate/product_template/productitem_'.(int)$id_template.'.tpl');
    }
    /**
     * convert html - >txt in data
     **/
    public static function converthtmltxt($html)
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
    public static function getHTML($name){
        Context::getContext()->smarty->assign(array('name'=>$name));
        $html  = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/linksendmail/html.tpl');
        return $html;
    }
    public static function getsubjectemailtemplate($id_emailtemplate, $id_lang)
    {
        $sql = 'SELECT a.*, b.*
            FROM `' . _DB_PREFIX_ . 'gaddnewemail_template_lang` AS a 
            INNER JOIN `' . _DB_PREFIX_ . 'gaddnewemail_template` AS b
            ON a.`id_gaddnewemail_template` = b.`id_gaddnewemail_template`
            WHERE a.`id_gaddnewemail_template` = ' . (int)$id_emailtemplate . '
            AND a.id_lang =' . (int)$id_lang;
        $_results = Db::getInstance()->executeS($sql);
        $name = '';
        if (!empty($_results)) {
            foreach ($_results as $_result) {
                $name = $_result['template_name'];
            }
        }
        return $name;
    }
    public static function updatecartawait($id_cart, $idremnider, $code, $emails, $discounttype, $discountval)
    {
        $idcart_rule = CartRule::getIdByCode($code);
        $cartrules   = new CartRule($idcart_rule);
        $price = '';
        if ($discounttype == 1) {
            $price = Tools::displayPrice($cartrules->reduction_amount);
        } elseif ($discounttype == 0 && $discountval !='') {
            $price = $discountval ."%";
        }
        $res = (bool)Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . "gabandoned_cart_await`(`id_reminder`, `id_cart`, `count`, `time`, `code`, `nameemailtp`)
            VALUES ('". (int)$idremnider ."', '" . (int)$id_cart . "', 0, '".pSQL(date('Y-m-d H:i:s'))."', '".pSQL($price)."', '".pSQL($emails)."')");
        return $res;
    }
    /** get all condition and reminder **/
    public static function getconditionandreminder()
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT * FROM `' . _DB_PREFIX_ . 'gconditionandreminder` o
            LEFT JOIN `' . _DB_PREFIX_ . 'gconditionandreminder_lang` ol
            ON o.`id_gconditionandreminder` = ol.`id_gconditionandreminder`
            LEFT JOIN `' . _DB_PREFIX_ . 'gconditionandreminder_shop` os
            ON o.`id_gconditionandreminder` = os.`id_gconditionandreminder`
            WHERE o.`active` = 1
            ORDER BY o.`position` ASC');
    }
    /** select data gabadonecart **/
    public static function getgabandonedcart($id_cart)
    {
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'gabandoned_cart`
        WHERE `id_cart` = ' . (int)$id_cart;
        return Db::getInstance()->executeS($sql);
    }
}
