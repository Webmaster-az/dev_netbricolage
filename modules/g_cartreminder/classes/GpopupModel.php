<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 *
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link         http://www.globosoftware.net
 */

class GpopupModel extends ObjectModel
{
    public $id_gabandoned_popup;
    public $active;
    public $day;
    public $maxwidth;
    public $mincart;
    public $hrs;
    public $display;
    public $displayss;
    public $sosicalfb;
    public $sosicaltw;
    public $sosicalgg;
    public $colorbackground;
    public $imgbackground;
    public $autocode;
    public $autocodetype;
    public $autocodevalue;
    public $autocodeday;
    public $autocodeship;
    public $code;
    public $name;
    public $html;
    public $time;
    public $autocodeid_currency;
    public $autocodetax;
    public $countdown;
    public $reset_countdown;
    public $customcss;
    public static $definition = array(
        'table'   => 'gabandoned_popup',
        'primary' => 'id_gabandoned_popup',
        'multilang' => true,
        'fields'    => array(
            'active'   => array('type' => self::TYPE_INT, 'size' => 1, 'validate' => 'isInt'),
            'day'      => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'hrs'      => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'maxwidth' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'mincart'  => array('type' => self::TYPE_STRING),
            'display'  => array('type' => self::TYPE_INT, 'size' => 10, 'validate' => 'isInt'),
            'displayss'=> array('type' => self::TYPE_INT, 'size' => 1, 'validate' => 'isInt'),
            'sosicalfb'=> array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'sosicaltw'=> array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'sosicalgg'=> array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'colorbackground'=> array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'imgbackground'  => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'autocode'       => array('type' => self::TYPE_INT, 'size' => 1, 'validate' => 'isunsignedInt'),
            'autocodetype'   => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'autocodevalue'  => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'autocodeday' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'autocodeship'=> array('type' => self::TYPE_INT, 'size' => 1, 'validate' => 'isunsignedInt'),
            'code'        => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'time'        => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'countdown' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'autocodeid_currency' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'autocodetax' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'reset_countdown' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'customcss'   => array('type' => self::TYPE_STRING),
            'name' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isString',
                'required' => true),
            'html'     => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'required' => true),
            ),
        );

    public function __construct($id_gabandoned_popup = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation('gabandoned_popup', array('type' => 'shop'));
        parent::__construct($id_gabandoned_popup, $id_lang, $id_shop);
        return true;
    }
    public static function getIDMaxcart($maxcart, $id_currency){
        $sql     = "SELECT * FROM `"._DB_PREFIX_."gabandoned_popup` WHERE `active`=1";
        $datas   = Db::getInstance()->executeS($sql);
        $max     = array();
        foreach ($datas as $data) {
            $mincarts = Tools::jsonDecode($data['mincart'], true);
            if ((float)$mincarts[$id_currency] <= (float)$maxcart) {
                $max[$data['id_gabandoned_popup']] = (float)$mincarts[$id_currency];
            }
        }
        if (!empty($max)) {
            $maxs = array_keys($max, max($max));
            return $maxs[0];
        } else {
            return false;
        }
    }
    
    public static function getHTMLPP($id_lang)
    {
        $sql = 'SELECT *
            FROM `' . _DB_PREFIX_ . 'gabandoned_popup_lang`
            INNER JOIN `' . _DB_PREFIX_ . 'gabandoned_popup`
            ON `' . _DB_PREFIX_ . 'gabandoned_popup_lang`.id_gabandoned_popup = `' . _DB_PREFIX_ . 'gabandoned_popup`.id_gabandoned_popup
            WHERE `' . _DB_PREFIX_ . 'gabandoned_popup_lang`.id_lang ='.(int)$id_lang;
        $res = Db::getInstance()->executeS($sql);
        return $res;
    }
    public static function getARRAYPP($Obj, $demo){
        return array(
                'imgbackground'  =>  $Obj->imgbackground,
                'day'            =>  $Obj->day,
                'hrs'            =>  $Obj->hrs,
                'mincart'        =>  Tools::jsonDecode($Obj->mincart, true),
                'maxwidth'       =>  $Obj->maxwidth,
                'displayss'      =>  $Obj->displayss,
                'display'        =>  $Obj->display,
                'sosicalfb'      =>  $Obj->sosicalfb,
                'sosicaltw'      =>  $Obj->sosicaltw,
                'sosicalgg'      =>  $Obj->sosicalgg,
                'code'           =>  $Obj->code,
                'autocode'       =>  $Obj->autocode,
                'autocodetype'   =>  $Obj->autocodetype,
                'autocodevalue'  =>  $Obj->autocodevalue,
                'autocodeday'    =>  $Obj->autocodeday,
                'autocodeship'   =>  $Obj->autocodeship,
                'id'             =>  $Obj->id,
                'demo'           =>  $demo,
                'autocodeid_currency' =>  $Obj->autocodeid_currency,
                'autocodetax'         =>  $Obj->autocodetax,
                'countdown'           =>  $Obj->countdown,
                'reset_countdown'     =>  $Obj->reset_countdown,
            );
    }
    public static function addDefauld($popupSetting, $key){
        $mincarts = array();
        foreach (Currency::getCurrencies() as $currencys) {
            $mincarts[$currencys['id_currency']]   = 10;
        }
        $popupSetting->active = 0;
        $popupSetting->day    = 0;
        $popupSetting->hrs    = 5;
        $popupSetting->maxwidth  = 800;
        $popupSetting->mincart   = Tools::jsonEncode($mincarts);
        $popupSetting->display   = 1;
        $popupSetting->autocodeid_currency   = Context::getContext()->currency->id;
        $popupSetting->autocodetax   = 0;
        $popupSetting->countdown   = 5;
        $popupSetting->reset_countdown   = 0;
        $popupSetting->colorbackground = '#ffffff';
        $popupSetting->customcss       = '';
        if ($key ==1 ) {
            $popupSetting->displayss = 1;
            $popupSetting->sosicalfb = 'https://www.facebook.com/';
            $popupSetting->sosicaltw = 'ContactGlobo';
            $popupSetting->sosicalgg = 'https://www.google.com.vn/';
            $popupSetting->colorbackground = '#c6eaee';
        } else {
            if ($key == 2) {
                $popupSetting->colorbackground = '#7FCBCC';
                $popupSetting->customcss       = '.gcartcontent-countdown-tiem {color: rgb(0, 0, 0) !important;background-color: #ffeb96 !important;}';
            } else {
                $popupSetting->colorbackground = '#D1CBF1';
                $popupSetting->customcss       = '.gcartcontent-countdown-tiem {color: rgb(0, 0, 0) !important;background-color: rgb(37, 0, 49)!important;}';
            }
            $popupSetting->displayss = 0;
            $popupSetting->sosicalfb = '';
            $popupSetting->sosicaltw = '';
            $popupSetting->sosicalgg = '';
        }
        $popupSetting->imgbackground   = '';
        $popupSetting->autocode        = 0;
        $popupSetting->autocodetype    = 1;
        $popupSetting->autocodevalue   = 0;
        $popupSetting->autocodeday     = 0;
        $popupSetting->autocodeship    = 0;
        $popupSetting->code = Tools::substr(md5(time()), 0, 8);
        $popupSetting->time = date('Y-m-d H:i:s', time());
        $langs = Language::getLanguages(false);
        $name_lang = 'sample popup '.$key;
        Context::getContext()->smarty->assign(array(
            'name'      => 'PP'.$key,
        ));
        foreach ($langs as $lang) {
            $html      = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/defaulttemplate/PPtemplate.tpl");
            $html_lang = $html;
            $popupSetting->name[$lang["id_lang"]] = $name_lang;
            $popupSetting->html[$lang["id_lang"]] = $html_lang;
        }
        $popupSetting->save();
    }
    public static function gentCode($id_cart, $setting, $demo =0)
    {
        $time_now = time();
        $code = Tools::strtoupper(Tools::substr(md5($time_now), 0, 8));
        $cart = new Cart($id_cart);$cart;
        $date_from = date("Y-m-d H:i:s", $time_now);
        if ($demo == 0) {
            $cartrule = new CartRule();
            $lang = Language::getLanguages(false);
            foreach ($lang as $value_lang) {
                $cartrule->name[$value_lang["id_lang"]] = "Cart Popup Code";
            }
            if ($setting->autocodetype == "2") {
                $cartrule->date_from = $date_from;
                $date_create = date_create($date_from);
                $date_create_from = (int)$setting->autocodeday . " days";
                $time_to = $time_now + (int)$setting->autocodeday * 24 * 60 * 60;
                $date_to = date("Y-m-d H:i:s", $time_to);
                $cartrule->date_to = $date_to;
                $cartrule->code    = $code;
                $cartrule->minimum_amount = 0;
                $cartrule->cart_rule_restriction = (int)$setting->autocodetax;
                $cartrule->reduction_amount   = (float)$setting->autocodevalue;
                $cartrule->reduction_currency = (int)$setting->autocodeid_currency;
                $cartrule->free_shipping      = (int)$setting->autocodeship;
                $cartrule->date_add = $date_from;
            } elseif ($setting->autocodetype == "1") {
                $cartrule->date_from = $date_from;
                $date_create = date_create($date_from);
                $date_create_from = (int)$setting->autocodeday . " days";
                date_add($date_create, date_interval_create_from_date_string($date_create_from));
                $time_to = $time_now + (int)$setting->autocodeday * 24 * 60 * 60;
                $date_to = date("Y-m-d H:i:s", $time_to);
                $cartrule->date_to = $date_to;
                $cartrule->code    = $code;
                $cartrule->cart_rule_restriction = (int)$setting->autocodetax;
                $cartrule->reduction_percent = $setting->autocodevalue;
                $cartrule->minimum_amount = 0;
                $cartrule->free_shipping  = (int)$setting->autocodeship;
                $cartrule->date_add       = $date_from;
            } else {
                return "";
            }
            $cartrule->add();
            return array("code"=>$code, 'date'=>$date_to);
        }  else {
            return array("code"=>$code, 'date'=> date("Y-m-d H:i:s", time()));
        }
    }
}
