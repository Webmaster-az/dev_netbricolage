<?php

/**

 * The file is controller. Do not modify the file if you want to upgrade the module in future

 * 

 * @author    Globo Jsc <contact@globosoftware.net>

 * @copyright 2017 Globo., Jsc

 * @license   please read license in file license.txt

 * @link	     http://www.globosoftware.net

 */



include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GcartremindercondreminderModel.php');

include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GcartreminderemailModel.php');

include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GabadoneModel.php');

class AdminGcartreminderabadonedcartController extends ModuleAdminController

{

    public function __construct()

    {

        $this->bootstrap = true;

        $this->table = 'cart';

        $this->className = 'Cart';

        parent::__construct();

        $this->lang = false;

        $this->explicitSelect = true;

        $this->addRowAction('log');

        $this->allow_export = false;

        $this->_orderWay = 'DESC';

        $this->_select = 'CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) `customer`,

        a.id_cart total, a.id_cart totalproduct,

        a.id_cart log,

        abd.id_reminder nameabd,

        abd.count severaltimes,

        abd.data_status datacfigsend,

        abd.data_getcode datacfigcode, c.email email,

        nl.nl_total,

        IF (IFNULL(o.id_order, \'' . $this->l('Non ordered') . '\') = \'' . $this->l('Non ordered') . '\', IF(TIME_TO_SEC(TIMEDIFF(\'' . pSQL(date('Y-m-d H:i:00', time())) . '\', a.`date_add`)) > 86400, \'' . $this->l('Abandoned cart') . '\', \'' . $this->l('Non ordered') . '\'), o.id_order) AS status,

        IF(o.id_order, 1, 0) badge_success,

        IF(o.id_order, 0, 1) badge_danger,

        IF(co.id_guest, 1, 0) id_guest,

        IF (IFNULL(o.id_order, "Send Mail") = "Send Mail", IF(a.id_customer , "Send Mail", ""), a.id_cart) AS noneorder,';

        $this->_join = 'LEFT JOIN ' . _DB_PREFIX_ . 'customer c ON (c.id_customer = a.id_customer)

        LEFT JOIN ' . _DB_PREFIX_ . 'currency cu ON (cu.id_currency = a.id_currency)

        LEFT JOIN ' . _DB_PREFIX_ . 'orders o ON (o.id_cart = a.id_cart)

        LEFT JOIN ' . _DB_PREFIX_ . 'gabandoned_cart abd ON (abd.id_cart = a.id_cart)

        LEFT JOIN (SELECT id_cart,COUNT(id_g_notification_log) as nl_total FROM ' . _DB_PREFIX_ . 'g_notification_log GROUP BY id_cart) nl ON(nl.id_cart = a.id_cart)  

        LEFT JOIN `' . _DB_PREFIX_ . 'connections` co ON (a.id_guest = co.id_guest AND TIME_TO_SEC(TIMEDIFF(\'' . pSQL(date('Y-m-d H:i:00', time())) . '\', co.`date_add`)) < 1800)

        ';

        $this->_where = '

            AND a.id_customer != 0 AND a.id_cart IN (SELECT id_cart FROM ' . _DB_PREFIX_ . 'cart_product)

        ';

        if (Tools::getValue('action') && Tools::getValue('action') == 'filterOnlyAbandonedCarts') {

            $this->_having = 'status = \'' . $this->l('Abandoned cart') . '\'';

        } else {

            $this->_use_found_rows = false;

        }

        $this->fields_list = array(

            'id_cart' => array(

                'title' => $this->l('ID'),

                'align' => 'text-center',

                'remove_onclick' => true,

                'class' => 'fixed-width-xs'),

            'status' => array(

                'title' => $this->l('Order ID'),

                'align' => 'text-center',

                'badge_danger' => true,

                'remove_onclick' => true,

                'havingFilter' => true),

            'customer' => array(

                'title' => $this->l('Customer'),

                'remove_onclick' => true,

                'filter_key' => 'c!lastname'),

            'totalproduct' => array(

                'title' => $this->l('Qty'),

                'callback' => 'getNbProducts',

                'align' => 'text-center',

                'orderby' => false,

                'remove_onclick' => true,

                'search' => false),

            'total' => array(

                'title' => $this->l('Total'),

                'callback' => 'getOrderTotalUsingTaxCalculationMethod',

                'orderby' => false,

                'search' => false,

                'align' => 'text-right',

                'remove_onclick' => true,

                'badge_success' => true),

            'nameabd' => array(

                'title' => $this->l('Condition And Reminder'),

                'align' => 'text-left',

                'remove_onclick' => true,

                'filter_type' => 'text',

                'callback' => 'repaltesnameconditionandreminder',

                'filter_key' => 'abd!id_reminder',

                'search' => false),

            'severaltimes' => array(

                'title' => $this->l('Number of sending reminder'),

                'align' => 'text-left',

                'remove_onclick' => true,

                'filter_key' => 'abd!count'),

            'nl_total' => array(

                'title' => $this->l('Number of sending notification'),

                'align' => 'text-left',

                'remove_onclick' => true,

                'filter_key' => 'nl!nl_total'),

            'date_add' => array(

                'title' => $this->l('Date'),

                'align' => 'text-left',

                'type' => 'datetime',

                'remove_onclick' => true,

                'class' => 'fixed-width-lg',

                'filter_key' => 'a!date_add'

            ),

            'noneorder' => array(

                'title' => $this->l('Send Mail'),

                'align' => 'text-left',

                'remove_onclick' => true,

                'search' => false,

                'callback' => 'getemailteplatesendmail'),

            );

        $this->shopLinkType = 'shop';



        $this->bulk_actions = array(

            'delete' => array(

                'text' => $this->l('Delete selected'),

                'confirm' => $this->l('Delete selected items?'),

                'icon' => 'icon-trash'

            )

        );



        

    }

    public function renderList()

    {

        $this->html = '';

        $link       = new Link();

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

        $helper->tpl_vars = $this->tpl_list_vars;

        $helper->toolbar_btn = array();

        $helper->tpl_delete_link_vars = $this->tpl_delete_link_vars;

        foreach ($this->actions_available as $action) {

            if (!in_array($action, $this->actions) && isset($this->$action) && $this->$action) {

                $this->actions[] = $action;

            }

        }

        $helper->is_cms = $this->is_cms;

        $skip_list = array();

        foreach ($this->_list as $row) {

            if (isset($row['id_order']) && is_numeric($row['id_order'])) {

                $skip_list[] = $row['id_cart'];

            }

        }

        if (array_key_exists('delete', $helper->list_skip_actions)) {

            $helper->list_skip_actions['delete'] = array_merge($helper->list_skip_actions['delete'], (array )$skip_list);

        } else {

            $helper->list_skip_actions['delete'] = (array )$skip_list;

        }

        $controller = Tools::getValue('controller');

        $this->html .= $this->getHTMLtab($link, 'tabs', $controller);

        $this->html .= $this->getHTMLtab($link, 'start', $controller);

        $this->html .= $helper->generateList($this->_list, $this->fields_list);

        $this->html .= $this->getHTMLtab($link, 'end', $controller);

        return $this->html;

    }

    public static function getOrderTotalUsingTaxCalculationMethod($id_cart)

    {

        $context = Context::getContext();

        $context->cart = new Cart($id_cart);

        $context->currency = new Currency((int)$context->cart->id_currency);

        $context->customer = new Customer((int)$context->cart->id_customer);

        return Cart::getTotalCart($id_cart, true, Cart::BOTH_WITHOUT_SHIPPING);

    }

    public function repaltesnameconditionandreminder($ids)

    {

        $reminders = array();

        $name     = '';

        $namesend = '';

        $arrayids = Tools::jsonDecode($ids, true);

        foreach ($arrayids as $arrayid) {

            $reminders[] = $this->getconditionandreminder($arrayid);

            if ($arrayid == 0) {

                $namesend = 'sent manually';

            }

        }

        if (!empty($reminders)) {

            foreach ($reminders as $reminder) {

                $name .= $reminder["0"]["rulename"] . ',';

            }

        }

        $name  = rtrim($name, ",");

        $name .= ','.$namesend;

        return trim($name, ",");

    }

    public function getNbProducts($id_cart)

    {

        return Cart::getNbProducts($id_cart);

    }

    public function getemailteplatesendmail($cart, $arraycart)

    {

        $id_shop = $this->context->shop->id;

        $total = Cart::getNbProducts($arraycart["id_cart"]);

        if ($cart == 'Send Mail' && $total != 0) {

            $this->context->smarty->assign('status_cart', $cart);

            $this->context->smarty->assign('arraycart', $arraycart);

            $this->context->smarty->assign('getemployee', GcartreminderemailModel::getemployee(null, $id_shop));

            $this->context->smarty->assign('getemployees', Tools::jsonEncode(GcartreminderemailModel::getemployee(null, $id_shop)));

            $this->context->smarty->assign('emailtemplates', $this->getemailtempalte());

            $this->context->smarty->assign('Currencies', Currency::getCurrencies());

            $files = "g_cartreminder/views/templates/admin/cartreminderabadoncart/statustsendmail.tpl";

            return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $files);

        } else {

            return;

        }

    }

    public function getemailtempalte()

    {

        $id_lang = (int)$this->context->language->id;

        $sql = 'SELECT *

            FROM `' . _DB_PREFIX_ . 'gaddnewemail_template`

            INNER JOIN `' . _DB_PREFIX_ . 'gaddnewemail_template_lang`

            ON `' . _DB_PREFIX_ . 'gaddnewemail_template`.id_gaddnewemail_template = `' . _DB_PREFIX_ . 'gaddnewemail_template_lang`.id_gaddnewemail_template

            WHERE `id_lang`=' . (int)$id_lang . '

            ';

        return Db::getInstance()->executeS($sql);

    }

    public function getconditionandreminder($id)

    {

        $id_lang = (int)$this->context->language->id;

        if (!empty($id)) {

            $sql = 'SELECT *

                FROM `' . _DB_PREFIX_ . 'gconditionandreminder` AS a

                INNER JOIN `' . _DB_PREFIX_ . 'gconditionandreminder_lang` AS b

                ON a.`id_gconditionandreminder` = b.`id_gconditionandreminder`

                WHERE a.`id_gconditionandreminder` =' . (int)$id . ' AND b.`id_lang` = ' . (int)$id_lang . '

                ';

            return Db::getInstance()->executeS($sql);

        }

    }

    public function ajaxProcessSendmail()

    {

        $code = '';

        $id_shop = (int)$this->context->shop->id;

        $id_lang = (int)$this->context->language->id;

        $id_cart = Tools::getValue("datasend_ajax");

        $from_email_ajax    = Tools::getValue("from_email_ajax");

        $subjectmail_ajax   = Tools::getValue("subjectmail_ajax");

        $custommessage_ajax = Tools::getValue("custommessage_ajax");

        $emailtemplate_ajax = Tools::getValue("emailtemplate_ajax");

        $discount_type_ajax = Tools::getValue("discount_type_ajax");

        $discountval_ajax   = Tools::getValue("discountval_ajax");

        $discountvalidity_ajax = Tools::getValue("discountvalidity_ajax");

        $freeship_ajax         = Tools::getValue("freeship_ajax");

        $reduction_currency_ajax= Tools::getValue("reduction_currency_ajax");$reduction_currency_ajax;

        $reduction_tax_ajax     = Tools::getValue("reduction_tax_ajax");

        $bcc = Tools::getValue("bcc");

        $bcc = trim($bcc, ", ");

        $employees = GcartreminderemailModel::getemployee($from_email_ajax, $id_shop);

        $checkdatas = GcartreminderemailModel::checkdataabadonedcartbyidcart($id_cart);

        $obj_cart = new Cart($id_cart);

        $customers = new Customer($obj_cart->id_customer);

        if (empty($checkdatas)) {

           $code = GcartreminderemailModel::getCodediscount($obj_cart, $discount_type_ajax, $discountvalidity_ajax, $freeship_ajax, $reduction_tax_ajax, 0, $discountval_ajax, $obj_cart->id_currency);

        } else {

            foreach ($checkdatas as $checkdata) {

                $codetrim = trim($checkdata["code"]);

                $idcart_rule = CartRule::getIdByCode($codetrim);

                if (!empty($idcart_rule)) {

                    GcartreminderemailModel::deletecartrule($idcart_rule);

                    $code = GcartreminderemailModel::getCodediscount($obj_cart, $discount_type_ajax, $discountvalidity_ajax, $freeship_ajax, $reduction_tax_ajax, 0, $discountval_ajax, $obj_cart->id_currency);

                } else {

                    $code = GcartreminderemailModel::getCodediscount($obj_cart, $discount_type_ajax, $discountvalidity_ajax, $freeship_ajax, $reduction_tax_ajax, 0, $discountval_ajax, $obj_cart->id_currency);

                }

            }

        }

        if(Validate::isLoadedObject($customers)){

            $keys = array('{customer_firstname}', '{customer_lastname}');

            $vals   = array($customers->firstname, $customers->firstname);

            $subjectmail_ajax = str_replace($keys, $vals, $subjectmail_ajax);

        }else return false;

        

        $send   = GcartreminderemailModel::sendmailabadonedcart($obj_cart, $subjectmail_ajax, $custommessage_ajax, $emailtemplate_ajax, $code, $employees, $bcc);

        $emails = GcartreminderemailModel::getsubjectemailtemplate($emailtemplate_ajax, $id_lang);

        GcartreminderemailModel::updatecartawait($id_cart, 0, $code, $emails, $discount_type_ajax, $discountval_ajax);

        $data_status = Tools::jsonEncode(array(

            'from'    => $from_email_ajax,

            'subject' => $subjectmail_ajax,

            'message' => $custommessage_ajax,

            'id_templateemail' => $emailtemplate_ajax,

            'bcc'              => $bcc));

        $data_getcode = Tools::jsonEncode(array(

            'typediscount'     => $discount_type_ajax,

            'valuediscount'    => $discountval_ajax,

            'validitydiscount' => $discountvalidity_ajax,

            'freeship'         => $freeship_ajax));

        if (!$send) {

            return false;

        } else {

            if (!empty($checkdatas)) {

                foreach ($checkdatas as $checkdata) {

                    $arrayreminders = Tools::jsonDecode($checkdata["id_reminder"], true);

                    if (empty($arrayreminders)) {

                        $arrayreminders = array(0);

                    } elseif (!in_array(0, $arrayreminders)) {

                        array_push($arrayreminders, 0);

                    }

                }

                $arrayreminders = Tools::jsonEncode($arrayreminders);

                $sql = "UPDATE `" . _DB_PREFIX_ . "gabandoned_cart`

                    SET `id_reminder` = '" . pSQL($arrayreminders) . "', `status_senmail` = 1, `data_status` = '" . pSQL($data_status) . "',

                    `data_getcode` = '" . pSQL($data_getcode) . "', `code` = '" . pSQL($code) . "',

                    `count` = `count` + 1 WHERE `id_cart` = " . (int)$id_cart;

                return (bool)Db::getInstance()->execute($sql);

            } else {

                $id_gconditionandreminder = Tools::jsonEncode(array(0));

                /*add new gabandoned_cart*/

                $GabadoneModel = new GabadoneModel();

                $GabadoneModel->id_cart =  (int)$id_cart;

                $GabadoneModel->id_reminder =  $id_gconditionandreminder;

                $GabadoneModel->status_senmail =  1;

                $GabadoneModel->data_status =  $data_status;

                $GabadoneModel->data_getcode =  $data_getcode;

                $GabadoneModel->count =  1;

                $GabadoneModel->code =  $code;

                return $GabadoneModel->save();

            }

        }

    }

    public function checkdataabadonedcartbyidcart($id_cart)
    {
        return Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'gabandoned_cart`
            WHERE `id_cart`=' . (int)$id_cart);
    }

    public function displayLogLink($token, $id)

    {

        $token;

        $logs = $this->getabadoncartawait($id);

        $notificationlogs = $this->getnotificationlog($id);

        $orders = $this->getorderbyidcart($id);

        Context::getContext()->smarty->assign('id', $id);

        Context::getContext()->smarty->assign('logs', $logs);

        Context::getContext()->smarty->assign('orders', $orders);

        Context::getContext()->smarty->assign('notificationlogs', $notificationlogs);

        return Context::getContext()->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/cartreminderabadoncart/log.tpl');

    }

    public function getabadoncartawait($id_cart)

    {

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('

			SELECT * FROM `' . _DB_PREFIX_ . 'gabandoned_cart_await`

            WHERE `id_cart`= ' . (int)$id_cart);

    }

    public function getnotificationlog($id_cart)

    {

        $logs = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('

			SELECT * FROM `' . _DB_PREFIX_ . 'g_notification_log`

            WHERE `id_cart`= ' . (int)$id_cart);

        if($logs)

            foreach($logs as &$log){

                $log['time'] = date('Y-m-d H:i:s',$log['time']);

            }

        return $logs;

    }

    public function getorderbyidcart($id_cart)

    {

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('

			SELECT * FROM `' . _DB_PREFIX_ . 'orders`

            WHERE `id_cart`= ' . (int)$id_cart);

    }

    /**

     * get cart await

     **/

    public function getcartawait($id_cart, $id_reminder)

    {

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('

			SELECT * FROM `' . _DB_PREFIX_ . 'gabandoned_cart_await`

            WHERE `id_cart`= ' . (int)$id_cart .'

            AND FIND_IN_SET(' . (int)$id_reminder . ',`id_reminder`)');

    }

    

    public function getHTMLtab($link, $name, $controller){

        $version = 'PS16';

        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){

            $version = 'PS17';

        }

        $dirimg = '../modules/g_cartreminder/views/img';

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

