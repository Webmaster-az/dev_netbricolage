<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */

include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GcartreminderemailModel.php');
class AdminGcartreminderemailController extends ModuleAdminController
{
    public function __construct()
    {
        $this->className = 'GcartreminderemailModel';
        $this->table = 'gaddnewemail_template';
        parent::__construct();
        $this->meta_title = $this->l('Email Template');
        $this->displayInformation($this->l('This is list of email templates that you can use to send email to your customer by manually or automatically. By default, we created 5 pre-made templates, you can create your custom template from the templates.'));
        $this->deleted = false;
        $this->explicitSelect = true;
        $this->lang = true;
        $this->context = Context::getContext();
        $this->bootstrap = true;
        $this->_defaultOrderBy = 'id_gaddnewemail_template';
        $this->filter = true;
        if (Shop::isFeatureActive()) {
            Shop::addTableAssociation($this->table, array('type' => 'shop'));
        }
        $this->toolbar_btn['duplicate'] = array(
            'href' => 'submitDupliate',
            'icon' => 'icon icon-duplicate',
            'desc' => $this->l('Duplicate Template'),
        );
        $this->bulk_actions = array('delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'));
        $this->position_identifier = 'id_gaddnewemail_template';
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->fields_list = array(
            'id_gaddnewemail_template' => array(
                'title' => $this->l('ID'),
                'type' => 'int',
                'width' => 'auto'),
            'template_name' => array('title' => $this->l('Name'), 'width' => 'auto'),
            'subjectlang' => array('title' => $this->l('Subject'), 'width' => 'auto'),
            'datetimenow' => array(
                'title' => $this->l('Date'),
                'width' => 'auto',
                'type' => 'datetime',
                'search' => true,
                ),
            );
        
    }

    public function renderList()
    {
        $this->html = '';
        $link       = $this->context->link;
        if (!($this->fields_list && is_array($this->fields_list))) {
            return false;
        }
        $this->getList($this->context->language->id);
        $helper = new HelperList();
        if (!is_array($this->_list)) {
            $this->displayWarning($this->l('Bad SQL query', 'Helper') . '<br />' . htmlspecialchars($this->_list_error));
            return false;
        }
        $this->setHelperDisplay($helper);
        $controller = Tools::getValue('controller');
        $this->html .= $this->getHTMLtab($link, 'tabs', $controller);
        $this->html .= $this->getHTMLtab($link, 'start', $controller);
        $this->html .= $helper->generateList($this->_list, $this->fields_list);
        $this->html .= $this->getHTMLtab($link, 'selecttemplate_email', $controller);
        $this->html .= $this->getHTMLtab($link, 'end', $controller);
        return $this->html;
    }
    public function renderForm()
    {
        $link  = $this->context->link;
        $languages = Language::getLanguages(false);
        $this->fields_value['hideemailhtmls'] = $this->getgconditionandreminder();
        $this->fields_value['url']            = Tools::getShopProtocol() . Tools::getServerName() . __PS_BASE_URI__;
        $id_gaddnewemail_template = (int)Tools::getValue('id_gaddnewemail_template');
        $linktab = Tools::getShopProtocol() . Tools::getServerName() . __PS_BASE_URI__;
        $this->context->smarty->assign('server_dir', $linktab);
        $cartreminderemailObj = new GcartreminderemailModel($id_gaddnewemail_template);
        $this->fields_value['template_name']   = $cartreminderemailObj->template_name ;
        $this->fields_value['subject']         = $cartreminderemailObj->subject;
        $this->fields_value['sample_email']    = $cartreminderemailObj->sample_email;
        $this->fields_value['email_html']      = $cartreminderemailObj->email_html;
        $this->fields_value['email_txt']       = $cartreminderemailObj->email_txt;
        $this->fields_value['datetimenow']     = $cartreminderemailObj->datetimenow;
        $this->fields_value['subjectlang']     = $cartreminderemailObj->subjectlang;
        $this->fields_value['email_htmllang']  = $cartreminderemailObj->email_htmllang;
        $this->fields_value['email_txtlang']   = $cartreminderemailObj->email_txtlang;
        if (!Validate::isLoadedObject($cartreminderemailObj)) {
            $id_template_default = (int)Tools::getValue('templatedefault');
            $html_teplate_email = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/defaulttemplate/defaulttemplate'.$id_template_default.'.tpl');
            foreach ($languages as $lang) {
                $this->fields_value['email_htmllang'][$lang['id_lang']] = $html_teplate_email;
                $this->fields_value['email_txtlang'][$lang['id_lang']]  = $this->converthtmltxt($html_teplate_email);
            }
        }
        $this->fields_form = array(
            'legend' => array('title' => $this->l('ADD NEW'), 'icon' => 'icon-envelope'),
            'input' => array(
                array(
                    'type' => 'email_template',
                    'name' => 'email_template'),
                ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'submitGcartreminderemail',
                'class' => 'btn btn-default pull-right'),);
        if (Shop::isFeatureActive()) {
                $this->fields_form['input'][] = array(
                    'type' => 'shop',
                    'label' => $this->l('Shop association'),
                    'name' => 'checkBoxShopAsso',
                    );
            }
        $this->context->smarty->assign('allid_order', $this->getAllidOrder());
        $controller = Tools::getValue('controller');
        $html  = $this->getHTMLtab($link, 'tabs', $controller);
        $html .= $this->getHTMLtab($link, 'start', $controller);
        $endhtml= $this->getHTMLtab($link, 'end', $controller);
        return $html.parent::renderForm().$endhtml;
    }
    public function getgconditionandreminder()
    {
        $sql = 'SELECT *
            FROM `' . _DB_PREFIX_ . 'gaddnewemail_template_lang`
            INNER JOIN `' . _DB_PREFIX_ . 'gaddnewemail_template`
            ON `' . _DB_PREFIX_ . 'gaddnewemail_template_lang`.id_gaddnewemail_template = `' . _DB_PREFIX_ . 'gaddnewemail_template`.id_gaddnewemail_template';
        $_results = Db::getInstance()->executeS($sql);
        $this->context->smarty->assign('arraylistemails', $_results);
        return $_results;
    }
    public function postProcess()
    {
        $idshop = (int)$this->context->shop->id;
        $shops  = Shop::getContextListShopID();
        $file_email = false;
        $action = Tools::getValue('action');
        if ($action == 'PreView') {
            $id_order = Tools::getValue('id_order');
            $valtpl = Tools::getValue('tpl');
            $emailtest_template = Tools::getValue('emailtest_template');
            $file_email = $this->parseEmailTest($shops, $valtpl);
            if ($file_email == true) {
                $subject_ajax = Tools::getValue('g_subject');
                $link = new link;
                $lang = new language($this->context->language->id);
                $getshopprotocol = trim(Tools::getShopProtocol(), '/');
                $dateFormatLite = $lang->date_format_lite;
                $valt = date($dateFormatLite, time());
                $orders = new Order($id_order);
                $carts = new Cart($orders->id_cart);
                $currency = new Currency($carts->id_currency);
                $products = $carts->getProducts(true);
                $link_shopstart = $getshopprotocol .$link->getPageLink("index", null, $carts->id_lang, null, false, $carts->id_shop, true);
                $link_cartstart = $getshopprotocol .$link->getPageLink('order', null, $carts->id_lang, null, false, $carts->id_shop, true);
                if($products)
                    foreach($products as &$product){
                        if(isset($product['total_wt']))
                            $product['total_wt'] = Tools::displayPrice($product['total_wt'], $currency);
                        if(isset($product['total']))
                            $product['total'] = Tools::displayPrice($product['total'], $currency);
                        $product['price'] = Tools::displayPrice($product['price'], $currency);
                    }
                Context::getContext()->smarty->assign(array(
                    'links'    => $link,
                    'id_lang'  => $this->context->language->id,
                    'gproducts' => $products,
                    'link_shopstart' => $link_shopstart,
                    'protocol'       => Tools::getShopProtocol(),
                    'link_cartstart' => $link_cartstart,
                ));
                $customers = new Customer($carts->id_customer);
                $datas = array(
                    '{customer_firstname}' => $customers->firstname,
                    '{customer_lastname}'  => $customers->lastname,
                    '{cart_product}'       => $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/defaulttemplate/product_template/productitem_0.tpl'),
                    '{cart_product_1}'     => $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/defaulttemplate/product_template/productitem_1.tpl'),
                    '{cart_product_2}'     => $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/defaulttemplate/product_template/productitem_2.tpl'),
                    '{cart_product_txt}'   => $this->converthtmltxt($this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/defaulttemplate/product_template/productitem_0.tpl')),
                    '{cart_product_txt_1}' => $this->converthtmltxt($this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/defaulttemplate/product_template/productitem_1.tpl')),
                    '{cart_product_txt_2}' => $this->converthtmltxt($this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/defaulttemplate/product_template/productitem_2.tpl')),
                    '{shop_link_start}'  => $this->getHTML('linkstart'),
                    '{shop_link_end}'    => $this->getHTML('linkend'),
                    '{shop_link_url}'    => $getshopprotocol .$link->getPageLink('index', null, $carts->id_lang, null, false, $carts->id_shop, true),
                    '{cart_link_start}'  => $this->getHTML('linkcartstart'),
                    '{cart_link_end}'    => $this->getHTML('linkend'),
                    '{link_unsubscribe}' => $this->context->link->getModuleLink('g_cartreminder','getcode',array('token'=>sha1(_COOKIE_KEY_.'g_cartreminder'))).'&id_customer='. $customers->id.'&id_shop='. $carts->id_shop.'&name=Unsubscribe',
                    '{cart_url}'       => $getshopprotocol .$link->getPageLink('order', null, $carts->id_lang, "step=3", false, $carts->id_shop, true),
                    '{cart_url_s1}'    => $getshopprotocol .$link->getPageLink('order', null, $carts->id_lang, "step=1", false, $carts->id_shop, true),
                    '{cart_url_s2}'    => $getshopprotocol .$link->getPageLink('order', null, $carts->id_lang, "step=2", false, $carts->id_shop, true),
                    '{voucher_code}'   => 'AFBC45SA',
                    '{custom_message}' => '',
                    '{voucher_expirate_date}' => $valt,
                    '{google_tracking_id}'=>'',
                    '{shop_email}'   => Configuration::get('PS_SHOP_EMAIL'),
                    '{shop_address}' => Configuration::get('PS_SHOP_ADDR1').' '.Configuration::get('PS_SHOP_ADDR2'),
                    '{shop_phone}'=> Configuration::get('PS_SHOP_PHONE'),
                    '{shop_fax}'  => Configuration::get('PS_SHOP_FAX'),
                    '{shop_name}' => Tools::safeOutput(Configuration::get('PS_SHOP_NAME', null, null, $carts->id_shop)),
                    '{total_cart_excl}' => Cart::getTotalCart($orders->id_cart, false),
                    '{total_cart_incl}' => Cart::getTotalCart($orders->id_cart, true),
                );
                $emails = explode(',', $emailtest_template);                
                foreach ($emails as $email) {
                    if (Validate::isEmail(trim($email))) {
                        Mail::Send((int)$carts->id_lang, 'test_' . (int)$carts->id_shop, $subject_ajax, $datas, $email, $customers->firstname . ' ' . $customers->lastname, null, null, null, null, _PS_MODULE_DIR_ . "g_cartreminder/mails/", false, (int)$carts->id_shop, null, null);
                    }
                }
            }
            echo 'true';die;
        } elseif ($action == 'getEML') {
            $sql = 'SELECT *
                FROM `' . _DB_PREFIX_ . 'gaddnewemail_template_lang`
                INNER JOIN `' . _DB_PREFIX_ . 'gaddnewemail_template`
                ON `' . _DB_PREFIX_ . 'gaddnewemail_template_lang`.id_gaddnewemail_template = `' . _DB_PREFIX_ . 'gaddnewemail_template`.id_gaddnewemail_template
                WHERE `' . _DB_PREFIX_ . 'gaddnewemail_template_lang`.id_lang ='.(int)Tools::getValue('id_lang');
            $html = Db::getInstance()->executeS($sql);
            echo  Tools::jsonEncode(array('res'=>true, 'html'=>$html));die;
        } elseif ($action == 'dupplicape') {
            $item_checkeds = Tools::getValue('item_checkeds');
            if($item_checkeds !=''){
                $item_checkeds = explode(',', $item_checkeds);
                foreach ($item_checkeds as $item_checked) {
                    $GcartreminderemailModel = new GcartreminderemailModel($item_checked, null, null);
                    $GcartreminderemailModelObjNew = $GcartreminderemailModel->duplicateObject();
                    $gcartreminderemail = new GcartreminderemailModel(null, null, $idshop);
                    $data   = $this->getgconditionandreminder();
                    $GcartreminderemailModelObjNew->parseEmail($data, $shops);
                }
            }
            echo Tools::jsonEncode(array(
                'error' => 1,
                'warrning' => $this->l('Duplicate Successfull'),
            ));
            die();
        }
        $return = parent::postProcess();
         if (Tools::isSubmit('submitAddgaddnewemail_template')){
            
               if(is_object($return) && get_class($return) == 'GcartreminderemailModel'){
                    $gcartreminderemail = new GcartreminderemailModel(null, null, $idshop);
                    $data   = $this->getgconditionandreminder();
                    $gcartreminderemail->parseEmail($data, $shops);
               }
         }
    }
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
    public function getAllidOrder()
    {
        return Db::getInstance()->executeS('
			SELECT id_order
			FROM '._DB_PREFIX_.'orders');
    }

    public function parseEmailTest($shops, $html)
    {
        $theme_dir = _PS_THEME_DIR_.'modules/g_cartreminder/mails/';
        $mails_dir = _PS_MODULE_DIR_ . 'g_cartreminder/mails/';
        $module_base = Tools::getShopProtocol() . Tools::getServerName() . __PS_BASE_URI__;
        $languages = Language::getLanguages(false);
        $version = 'PS16';
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
            $version = 'PS17';
        }
        if ($shops && $languages) {
            foreach ($languages as $lang) {
                if (!is_dir($mails_dir . $lang['iso_code'] . '/')) {
                    @mkdir($mails_dir . $lang['iso_code'] . '/', 0755);
                }
                if (!file_exists($mails_dir . $lang['iso_code'] . '/index.php')) {
                    @copy(_PS_MODULE_DIR_ . 'g_cartreminder/index.php', $mails_dir . $lang['iso_code'] . '/index.php');
                }
                $html = str_replace('%7B', '{', $html);
                $html = str_replace('%7D', '}', $html);
                foreach ($shops as $shop_id) {
                    $file = $mails_dir . $lang['iso_code'] . '/test_' . $shop_id . '.html';
                    $file_in_theme = $theme_dir . $lang['iso_code'] . '/test_' . $shop_id . '.html';
                    if (file_exists($file_in_theme))
                        @unlink($file_in_theme);
                    $fields_value = array();
                    $fields_value = array(
                        'isocode_lang' => $lang['iso_code'],
                        'id_shop' => $shop_id,
                        'id_lang' => $lang['id_lang'],
                        'g_url' => $module_base,
                        'check' => '1',
                        'version' => $version,
                        'emailcontentup' => $html);
                    Context::getContext()->smarty->assign($fields_value);
                    $tpl = _PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/emailbase/emailbase.tpl';
                    $emailcontent = Context::getContext()->smarty->fetch($tpl);
                    $handle = fopen($file, 'w+');
                    $emailcontent = $emailcontent;//Tools::purifyHTML($emailcontent, null, true);
                    $emailcontent = str_replace('%7B', '{', $emailcontent);
                    $emailcontent = str_replace('%7D', '}', $emailcontent);
                    fwrite($handle, $emailcontent);
                    fclose($handle);
                    $file = $mails_dir . $lang['iso_code'] . '/test_' . $shop_id . '.txt';
                    $file_in_theme = $theme_dir . $lang['iso_code'] . '/test_' . $shop_id . '.txt';
                    if (file_exists($file_in_theme))
                        @unlink($file_in_theme);
                    $handle = fopen($file, 'w+');
                    fwrite($handle, $this->converthtmltxt($html));
                    fclose($handle);
                }
            }
        }
        return true;
    }
    
    public function getHTMLtab($link, $name, $controller){
        $dirimg = '../modules/g_cartreminder/views/img';
        $version = 'PS16';
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
            $version = 'PS17';
        }
        $this->context->smarty->assign(array(
            'controller'=> $controller,
            'name'      => $name,
            'link'      => $link,
            'dirimg'    => $dirimg,
            'version'   => $version,
        ));
        $html = $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/tabs/htmlall.tpl");
        return $html;
    }
    public function getHTML($name){
        Context::getContext()->smarty->assign(array('name'=>$name));
        $html  = Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/linksendmail/html.tpl');
        return $html;
    }
}
