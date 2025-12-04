<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */
 
class G_cartreminderGetcodeModuleFrontController extends ModuleFrontController
{
    public function run()
    {
        if (Tools::getValue('token') != sha1(_COOKIE_KEY_.'g_cartreminder')) {
            die;
        }
        $name    = Tools::getValue('name');
        $id_cart = Tools::getValue('id_cart');
        if ($name == 'updatePPtime') {
            $cart = new Cart($id_cart);
            $time = time() + ((int)Tools::getValue('day') * 24 * 59 * 60)+ ((int)Tools::getValue('hrs') * 60 * 60);
            $updatePPtime = $this->updatPPtime($cart, $time);
            echo $updatePPtime;die;
        } elseif ($name == 'Unsubscribe') {
            $id_customer = (int)Tools::getValue('id_customer');
            $customerObj = new Customer($id_customer);
            $id_shop = Tools::getValue('id_shop');
            $this->updatUnsubscribe($customerObj, $id_shop);
            Tools::redirect('index.php?controller=index');
        } else {
            $setting = Tools::getValue('setting');
            $code = $this->gentCode($id_cart, $setting);
            echo $code;die;
        }
    }
    public function gentCode($id_cart, $setting)
    {
        $settings = Tools::jsonDecode($setting, true);
        $cartrule = new CartRule();
        $time_now = time();
        $code = Tools::strtoupper(Tools::substr(md5($time_now), 0, 8));
        $cart = new Cart($id_cart);
        $date_from = date("Y-m-d H:i:s", $time_now);
        $lang = Language::getLanguages(false);
        foreach ($lang as $value_lang) {
            $cartrule->name[$value_lang["id_lang"]] = "Cart Popup Code";
        }
        if ($settings['autocode_discount_type'] == "1") {
            $cartrule->date_from = $date_from;
            $date_create = date_create($date_from);
            $date_create_from = (int)$settings['validity_values'] . " days";
            $time_to = $time_now + (int)$settings['validity_values'] * 24 * 60 * 60;
            $date_to = date("Y-m-d H:i:s", $time_to);
            $cartrule->date_to = $date_to;
            $cartrule->code = $code;
            $cartrule->minimum_amount = 0;
            $cartrule->cart_rule_restriction = "1";
            $cartrule->reduction_amount = (float)$settings['discount_value'];
            $cartrule->reduction_currency = $cart->id_currency;
            $cartrule->free_shipping = $settings['autocode_shipping'];
            $cartrule->date_add = $date_from;
        } elseif ($settings['autocode_discount_type'] == "0") {
            $cartrule->date_from = $date_from;
            $date_create = date_create($date_from);
            $date_create_from = (int)$settings['validity_values'] . " days";
            date_add($date_create, date_interval_create_from_date_string($date_create_from));
            $time_to = $time_now + (int)$settings['validity_values'] * 24 * 60 * 60;
            $date_to = date("Y-m-d H:i:s", $time_to);
            $cartrule->date_to = $date_to;
            $cartrule->code = $code;
            $cartrule->cart_rule_restriction = "1";
            $cartrule->reduction_percent = $settings['discount_value'];
            $cartrule->minimum_amount = 0;
            $cartrule->free_shipping = $settings['autocode_shipping'];
            $cartrule->date_add = $date_from;
        } else {
            return "";
        }
        $cartrule->add();
        return $code.'-'.$settings['validity_values'];
    }
    
    public function updatPPtime($cart, $time){
        $maxs   = Db::getInstance()->getRow( 'SELECT P.* FROM `' . _DB_PREFIX_ . 'g_PPtime` P WHERE P.`id_cart` = '.(int)$cart->id.' AND P.`id_shop`=' . (int)$cart->id_shop);
        if (empty($maxs)) {
            $res = (bool)Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . "g_PPtime`(`id_customer`, `id_cart`, `time`, `id_shop`)
            VALUES ('" .(int)$cart->id_customer. "', '" .(int)$cart->id. "', '" .(int)$time. "', '" .(int)$cart->id_shop. "')");
            return $res;
        } else {
            $res = (bool)Db::getInstance()->execute("UPDATE `" . _DB_PREFIX_ . "g_PPtime`
                SET `time` = '".(int)$time."'
                WHERE `id_cart` = " . (int)$cart->id." AND `id_g_PPtime`=".(int)$maxs['id_g_PPtime']);
        }
        return true;
    }
    public function updatUnsubscribe($customerObj, $id_shop){
        $maxs   = Db::getInstance()->getRow( 'SELECT P.* FROM `' . _DB_PREFIX_ . 'gabandoned_unsubscribe_email` P WHERE P.`id_customer` = '.(int)$customerObj->id.' AND P.`email`="'.pSQL($customerObj->email).'" AND P.`id_shop`=' . (int)$id_shop);
        if (empty($maxs)) {
            $res = (bool)Db::getInstance()->execute("INSERT INTO `" . _DB_PREFIX_ . "gabandoned_unsubscribe_email`(`id_customer`, `email`, `id_shop`)
            VALUES ('" .(int)$customerObj->id. "', '" .pSQL($customerObj->email). "', '" .(int)$id_shop. "')");
            return $res;
        }
        return true;
    }
}
