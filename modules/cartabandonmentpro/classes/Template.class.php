<?php
/**
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

class CartAbandonmentProTemplate extends ObjectModel
{
    public $id_template = null;
    public $model = null;
    private $fields = array();
    private $wich_template = 0;
    private $name = '';

    public function __construct($id_template = null, $model = null, $wich_template = 0)
    {
        $this->id_template = $id_template;
        $this->model = $model;
        $this->wich_template = $wich_template;
    }

    public function save($null_values = false, $autodate = true)
    {
        // Avoid validator unused parameters
        $null_values = $null_values;
        $autodate = $autodate;

        if (!is_null($this->id_template) && $this->id_template > 0) {
            $active = TemplateController::isActive($this->id_template);
            $id_template = $this->id_template;
        } else {
            $active = 1;
            $id_template = 'NULL';
        }
        $query = "REPLACE INTO " . _DB_PREFIX_ . "cartabandonment_template
            VALUES (" . (int)$id_template . ", " . (int) $this->model->getId() . ",
            '" . pSQL($this->name) . "', " . (int)Tools::getValue('language') . ",
            " . (int)Tools::getValue('id_shop') . ", " . (int)$active . ", 1)";

        if (!Db::getInstance()->Execute($query)) {
            return false;
        }

        $this->id_template = Db::getInstance()->Insert_ID();

        $content = $this->model->getContent();

        $this->editContent($content);

        $iso = Language::getIsoById(Tools::getValue('language'));
        CartAbandonmentPro::initDirectory('mails/' . $iso);

        if (!is_writable('../modules/cartabandonmentpro/mails/')) {
            $query = "TRUNCATE " . _DB_PREFIX_ . "cartabandonment_template";
            Db::getInstance()->Execute($query);
            $query = "TRUNCATE " . _DB_PREFIX_ . "cartabandonment_remind_lang";
            Db::getInstance()->Execute($query);
            return false;
        }

        $fp = fopen('../modules/cartabandonmentpro/mails/' . $iso . '/' . $this->id_template . '.html', 'w+');
        fwrite($fp, $content);
        fclose($fp);

        $content = $this->model->getContentEdit($this->wich_template);
        $this->editContent($content, false);

        if (!is_writable('../modules/cartabandonmentpro/tpls/')) {
            $query = "TRUNCATE " . _DB_PREFIX_ . "cartabandonment_template";
            Db::getInstance()->Execute($query);
            $query = "TRUNCATE " . _DB_PREFIX_ . "cartabandonment_remind_lang";
            Db::getInstance()->Execute($query);
            return false;
        }

        // Clean old templates from onChange attributes
        $content = preg_replace('/onchange=\"([^"]*)\"/', '', $content);

        $fp = fopen('../modules/cartabandonmentpro/tpls/' . $this->id_template . '.html', 'w+');
        fwrite($fp, $content);
        fclose($fp);

        return $this->id_template;
    }

    // This function edits the newsletter
    // left column, right column, center column and the colors
    private function editContent(&$content, $save = true)
    {
        $this->editLeftColumn($content, $save);
        $this->editRightColumn($content, $save);
        $this->editCenter($content, $save);
        $this->editColors($content, $save);
        $context = Context::getContext();
        $logo = Configuration::get('PS_LOGO_MAIL');
        if (!$logo || $logo == '' || !file_exists(_PS_IMG_DIR_.$logo)) {
            $logo = Configuration::get('PS_LOGO');
        }
        $logo = $context->shop->getBaseUrl() . 'img/' . $logo;
        $content = str_replace('%logo%', $logo, $content);
    }

    // Replace all content in left column
    private function editLeftColumn(&$content, $save = true)
    {
        if (!$this->model->getLeftColumn()) {
            return false;
        }
        for ($nb = 1; $nb <= $this->model->getTxtsLeft(); $nb++) {
            $value = Tools::getValue('left_' . $this->model->getId() . '_' . $nb . '_' . $this->wich_template);
            $content = str_replace('%left_' . $nb . '%', $value, $content);
            if ($save) {
                $value = Tools::getValue('left_' . $this->model->getId() . '_' . $nb . '_' . $this->wich_template);
                $this->saveColumn('left', $nb, $value);
            }
        }
    }

    // Replace all content in right column
    private function editRightColumn(&$content, $save = true)
    {
        if (!$this->model->getRightColumn()) {
            return false;
        }
        for ($nb = 1; $nb <= $this->model->getTxtsRight(); $nb++) {
            $value = Tools::getValue('right_' . $this->model->getId() . '_' . $nb . '_' . $this->wich_template);
            $content = str_replace('%right_' . $nb . '%', $value, $content);
            if ($save) {
                Tools::getValue('right_' . $this->model->getId() . '_' . $nb . '_' . $this->wich_template);
                $this->saveColumn('right', $nb, $value);
            }
        }
    }

    // Replace all content in center column
    private function editCenter(&$content, $save = true)
    {
        for ($nb = 1; $nb <= $this->model->getTxtsCenter(); $nb++) {
            $value = Tools::getValue('center_' . $this->model->getId() . '_' . $nb . '_' . $this->wich_template);
            $content = str_replace('%center_' . $nb . '%', $value, $content);
            if ($save) {
                $this->saveColumn('center', $nb, Tools::getValue('center_' . $nb . '_' . $this->wich_template));
            }
        }
    }

    // Replace all colors
    private function editColors(&$content, $save = true)
    {
        for ($nb = 1; $nb <= $this->model->getColors(); $nb++) {
            $value = Tools::getValue('color_picker_' . $this->model->getId() . '_' . $nb . '_' . $this->wich_template);
            $content = str_replace('%color_' . $nb . '%', $value, $content);
            if ($save) {
                $value = Tools::getValue('color_picker_' . (int)$this->model->getId() . '_' . (int)$nb . '_' . (int)$this->wich_template);
                Db::getInstance()->Execute("
                    DELETE FROM " . _DB_PREFIX_ . "cartabandonment_template_color
                    WHERE id_template = " . (int) $this->id_template);
                Db::getInstance()->Execute("
                    INSERT INTO " . _DB_PREFIX_ . "cartabandonment_template_color
                    VALUES (NULL, " . (int) $this->id_template . ", " . (int)$nb . ",
                    '" . pSQL($value) . "')");
            }
        }
    }

    // Save One column in database
    private function saveColumn($column, $id_field, $value)
    {
        if (!isset($column) || !isset($id_field) || !isset($value)) {
            return false;
        }
        return Db::getInstance()->Execute("
            INSERT INTO " . _DB_PREFIX_ . "cartabandonment_template_field
            VALUES (NULL, " . (int) $this->id_template . ", " . (int)$id_field . ",
            '" . (int)$value . "', '" . pSQL($column) . "')");
    }

    public static function editTemplate($content, $reminder = null, $id_cart = null, $id_lang = null, $id_shop = null)
    {
        $lang_default = Configuration::get('PS_LANG_DEFAULT');
        if (is_null($id_lang)) {
            $id_lang = $lang_default;
        }
        if (is_null($id_shop)) {
            $id_shop = Context::getContext()->shop->id;
        }

        if (!$id_cart) {
            $id_cart = Db::getInstance()->getValue('
                SELECT id_cart FROM '._DB_PREFIX_.'cart_product
                ORDER BY id_cart DESC');
        }

        $cart = new Cart($id_cart);
        $id_currency = $cart->id_currency;
        $currency = new Currency($id_currency);
        $sign_currency = $currency->sign;
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')) {
          $context = Context::getContext();
          $context->cart = $cart;
        }


        if (!Validate::isLoadedObject($cart)) {
            return false;
        }

        $products = $cart->getProducts(true);

        if (!$products || empty($products)) {
            return false;
        }

        if (strpos($content, '%CART_PRODUCTS%')) {
            foreach ($products as &$product) {
                $product['img'] = CartAbandonmentProTemplate::getImage(
                    $product['id_product'],
                    $product['id_product_attribute'],
                    $id_lang
                );
                $product['link'] = Context::getContext()->link->getProductLink(
                    $product['id_product'],
                    $product['link_rewrite']
                );
                $product['price_with_tax'] = number_format(Product::getPriceStatic($product['id_product'], true, $product['id_product_attribute'], 6, null, false, true, 1, true, null, $id_cart), 2);
                $product['test2'] = Product::getPriceStatic($product['id_product'], 1, $product['id_product_attribute'], 2, null, 0, 0);
                $product['test3'] = Product::getPriceStatic($product['id_product'], 0, $product['id_product_attribute'], 2, null, 0, 0);

            }

            Context::getContext()->smarty->assign(array(
                'products' => $products,
                'sign' => $sign_currency,
            ));

            // Little hack to allow template override
            $module = Module::getInstanceByName('cartabandonmentpro');
            $html = $module->display(getcwd().'/'.$module->name.'.php', 'views/templates/admin/email_products.tpl');

            $content = str_replace('%CART_PRODUCTS%', $html, $content);
        }

        if (is_null($id_cart)) {
            $customer = array(
                'firstname' => 'John',
                'lastname' => 'Doe',
                'id_lang' => $id_lang,
                'gender_name' => 'M.'
            );
        } else {
            $customer = Db::getInstance()->getRow('
                    SELECT c.firstname, c.lastname, c.id_lang, gl.name as gender_name
                    FROM `' . _DB_PREFIX_ . 'cart` ca
                    LEFT JOIN ' . _DB_PREFIX_ . 'customer c ON ca.id_customer = c.id_customer
                    LEFT JOIN ' . _DB_PREFIX_ . 'gender_lang gl ON c.id_gender = gl.id_gender
                    WHERE ca.id_cart = ' . (int)$id_cart . ' AND c.id_lang = ' . (int)$id_lang);
        }

        $shopName = Db::getInstance()->getValue('
            SELECT `name` FROM '._DB_PREFIX_.'shop
            WHERE `id_shop` =  '.(int)$id_shop);

        $content = str_replace('%SHOP_NAME%', $shopName, $content);
        $content = str_replace('%FIRSTNAME%', $customer['firstname'], $content);
        $content = str_replace('%LASTNAME%', $customer['lastname'], $content);
        $content = str_replace('%GENDER%', $customer['gender_name'], $content);

        $params = array(
            'token_cart' => md5(_COOKIE_KEY_.'recover_cart_'.(int)$id_cart),
            'id_cart' => (int)$id_cart,
            'action' => 'redirect'
        );
        if ($reminder) {
            $params['wichRemind'] = (int)$reminder;
        }

        $module_url = Context::getContext()->link->getModuleLink(
            'cartabandonmentpro',
            'FrontCartAbandonment',
            array(),
            true
        ).'?';

        /* REPLACE %SHOP_LINK% + retro compat */
        $params['link'] = 'shop';
        $shop_url = $module_url.http_build_query($params);
        $content = str_replace('%SHOP_LINK%', $shop_url, $content);
        $content = str_replace('%SHOP_LINK_OPEN%', '<a href="'.$shop_url.'" target="_blank">', $content);
        $content = str_replace('%SHOP_LINK_CLOSE%', '</a>', $content);

        /* REPLACE %CART_LINK% + retro compat */
        $params['link'] = 'cart';
        $cart_url = $module_url.http_build_query($params);
        $content = str_replace('%CART_LINK%', $cart_url, $content);
        $content = str_replace('%CART_LINK_OPEN%', '<a href="' . $cart_url . '" target="_blank">', $content);
        $content = str_replace('%CART_LINK_CLOSE%', '</a>', $content);

        /* REPLACE %%UNSUBSCRIBE_LINK%% + retro compat */
        $params['link'] = 'unsubscribe';
        $unsubscribeUrl = $module_url.http_build_query($params);
        $content = str_replace('%UNSUBSCRIBE_LINK%', $unsubscribeUrl, $content);
        $content = str_replace('%UNUBSCRIBE_OPEN%', '<a href="' . $unsubscribeUrl . '" target="_blank">', $content);
        $content = str_replace('%UNUBSCRIBE_CLOSE%', '</a>', $content);
        $content = str_replace('%UNSUBSCRIBE_OPEN%', '<a href="' . $unsubscribeUrl . '" target="_blank">', $content);
        $content = str_replace('%UNSUBSCRIBE_CLOSE%', '</a>', $content);

        /* Add image to track if email is opened, only if it's not a test */
        if (!is_null($id_cart)) {
            $visualizeUrl = Context::getContext()->link->getModuleLink(
                'cartabandonmentpro',
                'FrontCartAbandonment',
                array(),
                true
            );
            $params['action'] = 'visualize';
            unset($params['link']);
            $content = '<img width="1" height="1" src="'.$visualizeUrl.'?'.http_build_query($params).'"> ' . $content;
        }
        return $content;
    }

    public static function editTitleBeforeSending($title, $id_cart = null, $id_lang = 1)
    {
        if (is_null($id_lang)) {
            $id_lang = Configuration::get('PS_LANG_DEFAULT');
        }
        $query = '
                SELECT c.firstname, c.lastname, gl.name as gender_name
                FROM `' . _DB_PREFIX_ . 'cart` ca
                JOIN ' . _DB_PREFIX_ . 'customer c ON ca.id_customer = c.id_customer
                LEFT JOIN ' . _DB_PREFIX_ . 'gender_lang gl ON c.id_gender = gl.id_gender';
        if (is_null($id_cart)) {
            $query .= ' LIMIT 1';
        } else {
            $query .= ' WHERE ca.id_cart = ' . (int) $id_cart;
        }

        $products = Db::getInstance()->ExecuteS($query);

        $title = str_replace('%FIRSTNAME%', $products[0]['firstname'], $title);
        $title = str_replace('%LASTNAME%', $products[0]['lastname'], $title);
        $title = str_replace('%GENDER%', $products[0]['gender_name'], $title);
        return $title;
    }

    public static function editDiscount($voucher, $content, $id_lang, $id_shop)
    {
        $value = false;
        $type = '';
        if ($voucher->reduction_percent > 0) {
            $value = $voucher->reduction_percent;
            $discount_txt = Configuration::get('CARTABAND_DISC_VAL', $id_lang, null, $id_shop);
            $type = "%";
        } elseif ($voucher->reduction_amount > 0) {
            $value = $voucher->reduction_amount;
            $discount_txt = Configuration::get('CARTABAND_DISC_VAL', $id_lang, null, $id_shop);
            $type = Currency::getDefaultCurrency()->sign;
        } else {
            $discount_txt = Configuration::get('CARTABAND_SHIPP_VAL', $id_lang, null, $id_shop);
        }

        $dates = explode(' ', $voucher->date_to);
        $dates = explode('-', $dates[0]);

        $discount_txt = str_replace('%DISCOUNT_VALUE%', $value . ' ' . $type, $discount_txt);
        $discount_txt = str_replace('%DISCOUNT_VALID_DAY%', $dates[2], $discount_txt);
        $discount_txt = str_replace('%DISCOUNT_VALID_MONTH%', $dates[1], $discount_txt);
        $discount_txt = str_replace('%DISCOUNT_VALID_YEAR%', $dates[0], $discount_txt);
        $discount_txt = str_replace('%DISCOUNT_CODE%', $voucher->code, $discount_txt);

        $content = str_replace('%DISCOUNT_TXT%', $discount_txt, $content);

        return $content;
    }

    private static function getImage($id_product, $id_product_attribute, $id_lang)
    {
        $product = new Product((int)$id_product);
        $link = new Link();

        $numblink = 0;

        while (empty($product->link_rewrite[$numblink])) {
            $numblink += 1;
        }

        $link_rewrite = $product->link_rewrite[$numblink];

        $product_link = $link->getProductLink($id_product, $link_rewrite, null, null, null, null, $id_product_attribute) ;
        $id_image = Image::getImages($id_lang, $id_product, $id_product_attribute);
        if (empty($id_image)) {
            $id_image = Image::getCover($id_product);
            $id_image = $id_image['id_image'];
        } else {
            $id_image = $id_image[0]['id_image'];
        }

        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $image_link = $link->getImageLink($link_rewrite, $id_image, ImageType::getFormattedName('small'));
        } else {
            $image_link = $link->getImageLink($link_rewrite, $id_image, ImageType::getFormatedName('small'));
        }

        return $image_link;
    }

    public function setWichTemplate($val)
    {
        $this->wich_template = $val;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
