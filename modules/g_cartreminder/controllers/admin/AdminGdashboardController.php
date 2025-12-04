<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */

class AdminGdashboardController extends ModuleAdminController
{
    public $html = '';
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'cart';
        $this->className = 'Cart';
        parent::__construct();
        $this->lang = false;
        $this->explicitSelect = true;
        $this->allow_export = false;
        $this->filter = false;
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
            AND a.id_customer != 0 AND a.id_cart IN (SELECT id_cart FROM ' . _DB_PREFIX_ . 'cart_product) AND IF(o.id_order, 0, 1)
        ';
        $this->_default_pagination = 10;
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
                'search' => false,
                'class' => 'fixed-width-xs'),
            'status' => array(
                'title' => $this->l('Order ID'),
                'align' => 'text-center',
                'badge_danger' => true,
                'remove_onclick' => true,
                'search' => false,
                'havingFilter' => true),
            'customer' => array(
                'title' => $this->l('Customer'),
                'remove_onclick' => true,
                'search' => false,
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
                'align' => 'text-center',
                'remove_onclick' => true,
                'badge_success' => true),
            'severaltimes' => array(
                'title' => $this->l('Number of reminders sent'),
                'align' => 'text-center',
                'remove_onclick' => true,
                'search' => false,
                'filter_key' => 'abd!count'),
            'date_add' => array(
                'title' => $this->l('Date'),
                'align' => 'text-left',
                'type' => 'datetime',
                'search' => false,
                'remove_onclick' => true,
                'class' => 'fixed-width-lg',
                'filter_key' => 'a!date_add'
                ),
            );
        $this->shopLinkType = 'shop';
        $this->title = $this->l('abandon cart');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            )
        );
        
    }
    public function initToolbar() {
        parent::initToolbar();
        unset( $this->toolbar_btn['new'] );
    }
    public function getAllView($date_from,$date_to,$granularity = false,$with_format = false){
        if ($granularity == 'day') {
            $sql = 'SELECT LEFT(a.`date_add`, 10) AS date, abd.count severaltimes FROM `'._DB_PREFIX_.'cart` a 
                    LEFT JOIN '._DB_PREFIX_.'customer c ON (c.id_customer = a.id_customer)
                    LEFT JOIN '._DB_PREFIX_.'currency cu ON (cu.id_currency = a.id_currency)
                    LEFT JOIN '._DB_PREFIX_.'orders o ON (o.id_cart = a.id_cart)
                    LEFT JOIN '._DB_PREFIX_.'gabandoned_cart abd ON (abd.id_cart = a.id_cart)
                    LEFT JOIN (SELECT id_cart,COUNT(id_g_notification_log) as nl_total FROM '._DB_PREFIX_.'g_notification_log GROUP BY id_cart) nl ON(nl.id_cart = a.id_cart)  
                    LEFT JOIN `'._DB_PREFIX_.'connections` co ON (a.id_guest = co.id_guest AND TIME_TO_SEC(TIMEDIFF("' . pSQL(date('Y-m-d H:i:00', time())) . '", co.`date_add`)) < 1800)
                    LEFT JOIN `'._DB_PREFIX_.'shop` shop ON a.`id_shop` = shop.`id_shop` WHERE 1 
                        AND a.id_customer != 0 AND a.id_cart IN (SELECT id_cart FROM '._DB_PREFIX_.'cart_product)
                        AND a.id_shop IN (1) AND a.date_add BETWEEN "' . pSQL($date_from) . ' 00:00:00" AND "' . pSQL($date_to) . ' 23:59:59" GROUP BY LEFT(a.`date_add`, 10)';
        } elseif ($granularity == 'month') {
            $sql = 'SELECT LEFT(a.`date_add`, 7) AS date, abd.count severaltimes FROM `'._DB_PREFIX_.'cart` a 
                    LEFT JOIN '._DB_PREFIX_.'customer c ON (c.id_customer = a.id_customer)
                    LEFT JOIN '._DB_PREFIX_.'currency cu ON (cu.id_currency = a.id_currency)
                    LEFT JOIN '._DB_PREFIX_.'orders o ON (o.id_cart = a.id_cart)
                    LEFT JOIN '._DB_PREFIX_.'gabandoned_cart abd ON (abd.id_cart = a.id_cart)
                    LEFT JOIN (SELECT id_cart,COUNT(id_g_notification_log) as nl_total FROM '._DB_PREFIX_.'g_notification_log GROUP BY id_cart) nl ON(nl.id_cart = a.id_cart)  
                    LEFT JOIN `'._DB_PREFIX_.'connections` co ON (a.id_guest = co.id_guest AND TIME_TO_SEC(TIMEDIFF("' . pSQL(date('Y-m-d H:i:00', time())) . '", co.`date_add`)) < 1800)
                    LEFT JOIN `'._DB_PREFIX_.'shop` shop ON a.`id_shop` = shop.`id_shop` WHERE 1 
                        AND a.id_customer != 0 AND a.id_cart IN (SELECT id_cart FROM '._DB_PREFIX_.'cart_product)
                        AND a.id_shop IN (1)';
        }
        $datas = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if(!$with_format){
            return $datas;
        }else {
            if ($granularity != 'month') $granularity = 'day';
            return $this->formatChartData($datas, $date_from, $date_to, $granularity);
        }
    }
    public function formatChartData($datas,$date_from,$date_to,$type = 'day'){
        $_view_values = array();
        foreach($datas as $view){
            $view_time = strtotime($view['date']);
            $_view_values[$view_time] = array(
                'key'=>$view_time,
                'y'=>(int)$view['severaltimes']);
        }
        $viewsdatas = array();
        if($type == 'month'){
            for ($date = strtotime($date_from); $date <= strtotime($date_to); $date = strtotime('+1 month', $date)) {
                $total_sendmail = 0;
                $_view_values = array();
                foreach($datas as $view){
                    if (strtotime($view['date']) == $date) {
                        $total_sendmail += (int)$view['severaltimes'];
                    }
                }
                $_view_values[$date] = array(
                    'key'=>$date,
                    'y'=>(int)$total_sendmail);
                if (isset($_view_values[$date]))
                    $viewsdatas[] = $_view_values[$date];
                else
                    $viewsdatas[] = array(
                        'key' => $date,
                        'y' => 0);
            }
        }else {
            for ($date = strtotime($date_from); $date <= strtotime($date_to); $date = strtotime('+1 day', $date)) {
                if (isset($_view_values[$date]))
                    $viewsdatas[] = $_view_values[$date];
                else
                    $viewsdatas[] = array(
                        'key' => $date,
                        'y' => 0);
            }
        }
        return $viewsdatas;
    }
    public function renderList(){
        $this->html = '';
        $controller = Tools::getValue('controller');
        $link = $this->context->link;
        $parent = parent::renderList();
        $type_sort = 1;
        $sort_type = 'day';
        $date_from = date('Y-m-').'01';
        if (Tools::getValue('type_sort')) {
            $type_sort = Tools::getValue('type_sort');
        }
        if (Tools::getValue('type_sort') == 2) {
            $sort_type = 'month';
            $date_from = date('Y-').'01-01';
        }
        $date_to = date('Y-m-d',strtotime('+1 day', strtotime(date('Y-m-d'))));
        $viewsdatas = $this->getAllView($date_from, $date_to, $sort_type,true);
        $mainchartdatas = array(
            array(
                'values' => $viewsdatas,
                'key' => $this->l('Number of emails sent'),
                'color' => "#ff7f0e"
            ),
        );
        if(version_compare(_PS_VERSION_, '1.6', '>=')){
            Media::addJsDef(array(
                'mainchartdatas' => $mainchartdatas,
                'gchart_date_format' => $this->context->language->date_format_lite
            ));
        }
        $sql = 'SELECT abd.count severaltimes, nl.nl_total FROM `'._DB_PREFIX_.'cart` a 
                LEFT JOIN '._DB_PREFIX_.'customer c ON (c.id_customer = a.id_customer)
                LEFT JOIN '._DB_PREFIX_.'currency cu ON (cu.id_currency = a.id_currency)
                LEFT JOIN '._DB_PREFIX_.'orders o ON (o.id_cart = a.id_cart)
                LEFT JOIN '._DB_PREFIX_.'gabandoned_cart abd ON (abd.id_cart = a.id_cart)
                LEFT JOIN (SELECT id_cart,COUNT(id_g_notification_log) as nl_total FROM '._DB_PREFIX_.'g_notification_log GROUP BY id_cart) nl ON(nl.id_cart = a.id_cart)  
                LEFT JOIN `'._DB_PREFIX_.'connections` co ON (a.id_guest = co.id_guest AND TIME_TO_SEC(TIMEDIFF("' . pSQL(date('Y-m-d H:i:00', time())) . '", co.`date_add`)) < 1800)
                LEFT JOIN `'._DB_PREFIX_.'shop` shop ON a.`id_shop` = shop.`id_shop` WHERE 1 
                    AND a.id_customer != 0 AND a.id_cart IN (SELECT id_cart FROM '._DB_PREFIX_.'cart_product) AND IF(o.id_order, 0, 1)
                    AND a.id_shop IN (1) ORDER BY a.id_cart DESC';
        $listabandon = Db::getInstance()->executes($sql);
        $sql_popup = 'SELECT id_gabandoned_popup FROM `'._DB_PREFIX_.'gabandoned_popup`';
        $countpopup = Db::getInstance()->executes($sql_popup);
        $sql_temp = 'SELECT id_gaddnewemail_template FROM `'._DB_PREFIX_.'gaddnewemail_template`';
        $counttemp = Db::getInstance()->executes($sql_temp);
        $sql_countcondition = 'SELECT id_gconditionandreminder FROM `'._DB_PREFIX_.'gconditionandreminder`';
        $countcondition = Db::getInstance()->executes($sql_countcondition);
        $count_send = 0;
        $count_notifi = 0;
        foreach ($listabandon as $value) {
            if ($value['severaltimes'] == '') {
                $value['severaltimes'] = 0;
            }
            $count_send += (int)$value['severaltimes'];
            if ($value['nl_total']) {
                $count_notifi +=(int)$value['nl_total'];
            }
        }
        $this->context->smarty->assign(
            array(
                'count_abandoncart' => count($listabandon),
                'count_reminder_send' => $count_send,
                'count_notification' => (int)$count_notifi,
                'count_condition' => count($countcondition),
                'count_popup' => count($countpopup),
                'count_emailtemplate' => count($counttemp),
                'url_controller' => $this->context->link->getAdminLink('AdminGdashboard'),
                'type_sort' => $type_sort,
            )
        );
        $this->html .= $this->getHTMLtab($link, 'tabs', $controller);
        $this->html .= $this->getHTMLtab($link, 'start', $controller);
        $this->html .= $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/dashboard/dashboard.tpl");
        $this->html .= $parent;
        $this->html .= $this->getHTMLtab($link, 'end', $controller);
        return $this->html;
    }
    public function repaltesnameconditionandreminder($ids)
    {
        if ($ids && $ids != '') {
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
        }else{
            return '';
        }
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
    public function getHTMLtab($link, $name, $controller){
        $dirimg = '../modules/g_cartreminder/image/';
        $CONFIGGETCARTDAYS = '';
        $CONFIGGETCARTHRS  = '';
        $version = 'PS16';
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
            $version = 'PS17';
        }
        if ($name == 'date_get_cart') {
            $id_shop       = (int)$this->context->shop->id;
            $id_shop_group = (int)Shop::getContextShopGroupID();
            $CONFIGGETCARTDAYS = Configuration::get('CONFIGGETCARTDAYS', null, $id_shop_group, $id_shop);
            $CONFIGGETCARTHRS  = Configuration::get('CONFIGGETCARTHRS', null, $id_shop_group, $id_shop);
        }
        $dirimg = '../modules/g_cartreminder/views/img';
        $this->context->smarty->assign(array(
            'controller'=> $controller,
            'name'      => $name,
            'link'      => $link,
            'dirimg'    => $dirimg,
            'CONFIGGETCARTDAYS' => $CONFIGGETCARTDAYS,
            'CONFIGGETCARTHRS'  => $CONFIGGETCARTHRS,
            'version'   => $version,
        ));
        $html = $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/tabs/htmlall.tpl");
        return $html;
    }
    public function getNbProducts($id_cart)
    {
        return Cart::getNbProducts($id_cart);
    }
    public static function getOrderTotalUsingTaxCalculationMethod($id_cart)
    {
        $context = Context::getContext();
        $context->cart = new Cart($id_cart);
        $context->currency = new Currency((int)$context->cart->id_currency);
        $context->customer = new Customer((int)$context->cart->id_customer);
        return Cart::getTotalCart($id_cart, true, Cart::BOTH_WITHOUT_SHIPPING);
    }
}