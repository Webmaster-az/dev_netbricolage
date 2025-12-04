<?php

include_once(_PS_MODULE_DIR_.'cartabandonmentpro/classes/Template.class.php');
include_once(_PS_MODULE_DIR_.'cartabandonmentpro/classes/Model.class.php');
include_once(_PS_MODULE_DIR_.'cartabandonmentpro/controllers/TemplateController.class.php');
require_once(_PS_MODULE_DIR_.'cartabandonmentpro/controllers/ReminderController.class.php');
require_once(_PS_MODULE_DIR_.'cartabandonmentpro/controllers/DiscountsController.class.php');

class cartabandonmentproFrontCartAbandonmentModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        switch (Tools::getValue('action')) {
            case 'cron':
                self::execCron();
                break;

            case 'visualize':
                self::visualize();

            case 'redirect':
                self::redirectLink();

            default:
                die('Not found');
        }
    }

    public static function execCron()
    {
        $id_shop = Tools::getValue('id_shop');
        if (!$id_shop) {
            $id_shop = Tools::getValue('amp;id_shop');
            if (!$id_shop && isset($argv)) {
                $id_shop = $argv[1];
            }
            if (!$id_shop) {
                die('No shop ...');
            }
        }

        $token = Tools::getValue('token');
        if (!$token) {
            $token = Tools::getValue('amp;token');
        }

        if (!$token && isset($argv)) {
            $token = $argv[2];
        }

        $token_bdd = Configuration::get('CARTABAND_TOKEN', null, null, $id_shop);

        if (!$token || !$token_bdd || $token != $token_bdd) {
            die('Invalid token');
        }

        $wich_remind = Tools::getValue('wich_remind');

        if (!$wich_remind) {
            $wich_remind = Tools::getValue('amp;wich_remind');
            if (!$wich_remind && isset($argv)) {
                $wich_remind = $argv[3];
            }
            if (!$wich_remind) {
                die('No remind number ...');
            }
        }

        $query = "SELECT active FROM `"._DB_PREFIX_."cartabandonment_remind_config`
        WHERE wich_remind = " . (int)$wich_remind;
        if (Db::getInstance()->getValue($query) == 0) {
            die;
        }

        $carts = ReminderController::getAbandonedCart($wich_remind, $id_shop);
        $templates = TemplateController::getActiveTemplate($id_shop);
        if (!$templates) {
            die('No active template ...');
        }
        $x = 0;
        $sent = array();
        $first = true;
        $mails = '';

        if (!isset(Context::getContext()->link)) {
            Context::getContext()->link = new Link();
        }

        foreach ($carts as $arr_cart) {
            Context::getContext()->cart = new Cart($arr_cart['id_cart']);

            $iso = Language::getIsoById($arr_cart['id_lang']);
            $id_lang = $arr_cart['id_lang'];

            if (!isset($templates[$arr_cart['id_shop']][$arr_cart['id_lang']][$wich_remind])) {
                $id_lang = Configuration::get('PS_LANG_DEFAULT');
                $iso = Language::getIsoById($id_lang);
            }

            $content = Tools::file_get_contents(_PS_MODULE_DIR_.'cartabandonmentpro/mails/'.$iso.'/'.$templates[$arr_cart['id_shop']][$id_lang][$wich_remind]['id'].'.html');
            $content = CartAbandonmentProTemplate::editTemplate($content, $wich_remind, $arr_cart['id_cart'], $id_lang, $id_shop);

            if (!$content) {
                continue;
            }

            $discounts = DiscountsController::getDiscounts($wich_remind, $id_shop);

            $cart2 = new Cart($arr_cart['id_cart']);
            if (!isset(Context::getContext()->currency->id)) {
                Context::getContext()->currency = new Currency($cart2->id_currency, null, $id_shop);
            }

            $id_address = Address::getFirstCustomerAddressId($arr_cart['id_customer']);
            if ($cart2->id_address_delivery != $id_address) {
                $cart2->id_address_delivery = $id_address;
                $cart2->id_address_invoice = $id_address;
                $cart2->save();
            }

            $total_cart = $cart2->getOrderTotal();
            $i = 0;
            $disc = false;
            $disc_valid = false;
            $type = false;
            $min = false;
            $max = false;
            $value = false;

            if (is_array($discounts) && count($discounts) > 0) {
                foreach ($discounts as $discount) {
                    if ($total_cart >= $discount['min_amount']) {
                        $disc = $i;
                        $disc_valid = $discount['valid_value'];
                        $type = $discount['type'];
                        $min = $discount['min_amount'];
                        $max = $discount['max_amount'];
                        $value = $discount['value'];
                    }
                    $i++;
                }

                if ($value > 0 || $type == 'shipping') {
                    $with_taxes = Configuration::get('CARTABAND_DISCOUNT_WITH_TAXES_'.$wich_remind);
                    $voucher = DiscountsController::createDiscount($arr_cart['id_customer'], $value, $disc_valid, $type, $min, $with_taxes);
                    $content = CartAbandonmentProTemplate::editDiscount($voucher, $content, $id_lang, $id_shop);
                } else {
                    $content = str_replace('%DISCOUNT_TXT%', "", $content);
                }
            } else {
                $content = str_replace('%DISCOUNT_TXT%', "", $content);
            }

            $title = strip_tags(CartAbandonmentProTemplate::editTitleBeforeSending($templates[$arr_cart['id_shop']][$id_lang][$wich_remind]['name'], $arr_cart['id_cart'], $id_lang));

            $fp = fopen(_PS_MODULE_DIR_.'cartabandonmentpro/mails/' . $iso . '/send.html', 'w+');
            fwrite($fp, $content);
            fclose($fp);
            $fp = fopen(_PS_MODULE_DIR_.'cartabandonmentpro/mails/' . $iso . '/send.txt', 'w+');
            $content = preg_replace("/(\s){2,}/", "\r\n\r\n", trim(strip_tags($content)));
            fwrite($fp, $content);
            fclose($fp);

            $mail = Mail::Send($id_lang, 'send', $title, array(), $arr_cart['email'], null, null, null, null, null, _PS_MODULE_DIR_.'cartabandonmentpro/mails/');

            unlink(_PS_MODULE_DIR_.'cartabandonmentpro/mails/' . $iso . '/send.html');
            unlink(_PS_MODULE_DIR_.'cartabandonmentpro/mails/' . $iso . '/send.txt');

            if ($mail) {
                if (!$first) {
                    $mails .= ';';
                }
                $mails .= $arr_cart['email'];
                $first = false;
                $justSent = array('id_customer'=> $arr_cart['id_customer'], 'id_cart'=> $arr_cart['id_cart'], 'firstname' => $arr_cart['firstname'], 'lastname' => $arr_cart['lastname'], 'email' => $arr_cart['email']);
                $sent[] = $justSent;
                Db::getInstance()->Execute("INSERT INTO "._DB_PREFIX_."cartabandonment_remind VALUES (NULL, " . (int) $wich_remind . ", " . (int) $arr_cart['id_cart'] . ", NOW(), 0, 0, 0)");
                $x++;
            }
        }
        unset($justSent, $carts, $content, $title, $templates);
        $str = '<LINK rel=stylesheet type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
        $str .= '<div class="container"><h3>'.$x.' mails have been sent.</h3><br><br>';
        $str .= '<table class="table table-striped"><tr><th>ID CUSTOMER</th><th>ID CART</th><th>FIRSTNAME</th><th>LASTNAME</th><th>EMAIL</th></tr>';
        foreach ($sent as $s) {
            $str .= '<tr><td>'.$s['id_customer'].'</td><td>'.$s['id_cart'].'</td><td>'.$s['firstname'].'</td><td>'.$s['lastname'].'</td><td><a href="mailto:'.$s['email'].'">'.$s['email'].'</a></td></tr>';
        }
        $str .= '</table>
                </div>';
        die($str);
    }

    public static function visualize()
    {
        $wichRemind = Tools::getValue('wichRemind');
        $id_cart = Tools::getValue('id_cart');
        $token = Tools::getValue('token_cart');

        if ($token == md5(_COOKIE_KEY_.'recover_cart_'.$id_cart)) {
            $query = "UPDATE "._DB_PREFIX_."cartabandonment_remind SET visualize = 1 WHERE wich_remind = ".(int)$wichRemind." AND id_cart = ".(int)$id_cart;
            Db::getInstance()->Execute($query);
        }

        header('Content-Type: image/png');
        echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');
    }

    public static function redirectLink()
    {
        $id_cart = Tools::getValue('id_cart');
        $token   = Tools::getValue('token_cart');
        $wichRemind = Tools::getValue('wichRemind');
        $link = Tools::getValue('link');

        if (!$id_cart || !$wichRemind) {
            Tools::redirect(__PS_BASE_URI__.'order.php?step=0');
        }

        if ($token != md5(_COOKIE_KEY_.'recover_cart_'.(int)$id_cart)) {
            Tools::redirect(__PS_BASE_URI__.'order.php?step=0');
        }

        // Get GAnalytics tags if needed
        $params = array();
        if (Tools::getValue('utm_source')) {
            $params['utm_source'] = Tools::getValue('utm_source');
        }
        if (Tools::getValue('utm_medium')) {
            $params['utm_medium'] = Tools::getValue('utm_medium');
        }
        if (Tools::getValue('utm_campaign')) {
            $params['utm_campaign'] = Tools::getValue('utm_campaign');
        }

        switch ($link) {
            case 'cart':
                $query = '
                    SELECT `lastname`, `firstname`, `passwd`, `email`, `id_currency`,
                        `id_cart`, ca.id_customer, cu.secure_key
                    FROM `'._DB_PREFIX_.'customer` `cu`
                    LEFT JOIN `'._DB_PREFIX_.'cart` `ca`
                    ON `ca`.`id_customer` = `cu`.`id_customer` AND `ca`.`secure_key` = `cu`.`secure_key`
                    WHERE `ca`.`id_cart`='.(int)$id_cart;
                $result = DB::getInstance()->getRow($query);

                if (!empty($result)) {
                    $customer = new Customer($result['id_customer']);

                    $context = Context::getContext();
                    $context->cookie->id_cart = $id_cart;
                    $context->cookie->id_customer = (int)$customer->id;
                    $context->cookie->customer_lastname = $customer->lastname;
                    $context->cookie->customer_firstname = $customer->firstname;
                    $context->cookie->logged = 1;
                    $context->cookie->is_guest = $customer->is_guest;
                    $context->cookie->passwd = $customer->passwd;
                    $context->cookie->email = $customer->email;
                    //$this->context = $context;

                    $query = "UPDATE "._DB_PREFIX_."cartabandonment_remind SET click_cart = 1 WHERE wich_remind = ".(int)$wichRemind." AND id_cart = ".(int)$id_cart;
                    Db::getInstance()->Execute($query);
                }
                Tools::redirect(__PS_BASE_URI__.'order.php?step=0&'.http_build_query($params));
            case 'shop':
                if ($id_cart && $wichRemind) {
                    $query = "
                        UPDATE "._DB_PREFIX_."cartabandonment_remind
                        SET click = 1
                        WHERE wich_remind = ".(int)$wichRemind."
                            AND id_cart = ".(int)$id_cart;
                    Db::getInstance()->Execute($query);
                }
                Tools::redirect(__PS_BASE_URI__.'index.php?'.http_build_query($params));
            case 'unsubscribe':
                $id_customer = Db::getInstance()->getValue('
                        SELECT c.id_customer
                        FROM `' . _DB_PREFIX_ . 'cart` ca
                        JOIN ' . _DB_PREFIX_ . 'customer c ON ca.id_customer = c.id_customer
                        WHERE ca.id_cart = ' . (int)$id_cart);

                if (!$id_customer) {
                    die('Error');
                }

                $query = "INSERT INTO "._DB_PREFIX_."cartabandonment_unsubscribe VALUES (".(int) $id_customer.");";
                if (Db::getInstance()->Execute($query)) {
                    die('OK');
                } else {
                    die('Error');
                }

            default:
                Tools::redirect(__PS_BASE_URI__.'order.php?step=0&'.http_build_query($params));
        }
    }
}
