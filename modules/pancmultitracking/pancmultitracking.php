<?php
/**
* 2007-2021 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Pancmultitracking extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'pancmultitracking';
        $this->tab = 'shipping_logistics';
        $this->version = '1.0.0';
        $this->author = 'PANC';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Multi Tracking');
        $this->description = $this->l('Multi Tracking code on order page view admin');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }
  
    public function install()
    {
        return parent::install() &&
            $this->_installSql() &&
            $this->registerHook('displayAdminOrderMain') &&
            $this->registerHook('backOfficeHeader');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    protected function _installSql() {
        $sqlInstall = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'panc_multitracking` (
            `id_panc_multitracking` int(11) NOT NULL AUTO_INCREMENT,
            `order_id` int(11) NOT NULL,
            `date` TEXT NOT NULL,
            `carrier` int(11) NOT NULL,
            `weight` TEXT NOT NULL,
            `tracking` TEXT NOT NULL,
            PRIMARY KEY  (`id_panc_multitracking`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';
 
        $returnSql = Db::getInstance()->execute($sqlInstall);
 
        return $returnSql;
    }
        
    /* Add the CSS & JavaScript files to be loaded in the BO. */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookDisplayAdminOrderMain($params)
    {
        //Display carriers, order id, and TPL file
        $carriers = Carrier::getCarriers($language->id); //Get carriers information
        $id_order = $params['id_order']; //Gets order ID
        $order = new Order((int) $id_order); //Gets order information with the current order id

        //Get customers info
        $customers = Customer::getCustomers($order->id_customer); //Get customers information
        foreach($customers as $customer){
            $customer_id =  $customer['id_customer'];
            $customer_firstname =  $customer['firstname'];
            $customer_lastname =  $customer['lastname'];
            $customer_email =  $customer['email'];
        }

        // Get order reference
        $getreferenceorders = Db::getInstance()->ExecuteS('SELECT * FROM `' . _DB_PREFIX_ . 'orders` WHERE `id_order` = '.$id_order.'');
        foreach ($getreferenceorders as $result) {
            $order_reference = $result['reference'];
        }

        // If form with new info is submited
        if (Tools::isSubmit('submit_requestform'))
        {
            $order_id =  Tools::getValue('order_id');
            $id_panc_multitracking =  Tools::getValue('id_panc_multitracking');
            $date = Tools::getValue('order_tracking_date');
            $carrier = Tools::getValue('order_tracking_carrier');
            $weight = Tools::getValue('order_tracking_weight');
            $tracking = Tools::getValue('order_tracking_number');

            $getcarrierinfos = Db::getInstance()->ExecuteS('SELECT `url` FROM `' . _DB_PREFIX_ . 'carrier` WHERE `id_carrier` = '.$carrier.'');
            foreach ($getcarrierinfos as $getcarrierinfo) {
                $carrier_url = $getcarrierinfo['url'];
            }

            // Inserts data from TPL form into the database
            Db::getInstance()->insert('panc_multitracking', [
                'order_id' => $order_id,
                'date' => $date,
                'carrier' => $carrier,
                'weight' => $weight,
                'tracking' => $tracking,
            ]);

            //Sends email with tracking info
            Mail::Send(
                (int)(Configuration::get('PS_LANG_DEFAULT')), // defaut language id
                'pancmtmail', // email template file to use
                'Here is your order tracking', // email subject
                array(
                    '{firstname}' => $customer_firstname, // Customer firstname
                    '{lastname}' => $customer_lastname, // Customer lastname
                    '{order_name}' => $order_reference, // Order Reference
                    '{followup}' => $carrier_url . $tracking // Order Tracking URL
                ),
                $customer_email, // receiver email address
                NULL, //receiver name
                NULL, //from email address
                NULL,  //from name
                NULL, //file attachment
                NULL, //mode smtp
                _PS_MODULE_DIR_ . '/pancmultitracking/mails' //custom template path
            );
        }

        if (Tools::isSubmit('submit_deleteform'))
        {
            $id_panc_multitracking = Tools::getValue('id_panc_multitracking');

            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'panc_multitracking` WHERE `id_panc_multitracking` = '.$id_panc_multitracking.'');
        }        
        
        if (Tools::isSubmit('submit_updateform')){
            $id_panc_multitracking =  Tools::getValue('id_panc_multitracking');
            $date = Tools::getValue('order_tracking_date');
            $carrier = Tools::getValue('order_tracking_carrier');
            $weight = Tools::getValue('order_tracking_weight');
            $tracking = Tools::getValue('order_tracking_number');

            Db::getInstance()->update('panc_multitracking', array(
                'date' => $date,
                'carrier' =>  $carrier,
                'weight' => $weight,
                'tracking' => $tracking,
            ),  'id_panc_multitracking = '.$id_panc_multitracking );

            //Sends email with tracking info
            Mail::Send(
                (int)(Configuration::get('PS_LANG_DEFAULT')), // defaut language id
                'pancmtmail', // email template file to use
                'Here is your order tracking', // email subject
                array(
                    '{firstname}' => $customer_firstname, // Customer firstname
                    '{lastname}' => $customer_lastname, // Customer lastname
                    '{order_name}' => $order_reference, // Order Reference
                    '{followup}' => $carrier_url . $tracking // Order Tracking URL
                ),
                $customer_email, // receiver email address
                NULL, //receiver name
                NULL, //from email address
                NULL,  //from name
                NULL, //file attachment
                NULL, //mode smtp
                _PS_MODULE_DIR_ . '/pancmultitracking/mails' //custom template path
            );
        }

        $getdbdata = Db::getInstance()->ExecuteS('SELECT * FROM `' . _DB_PREFIX_ . 'panc_multitracking` WHERE `order_id` = '.$id_order.'');

        $this->smarty->assign([
            'carriers' => $carriers,
            'id_order' => $id_order,
            'getdbdata' => $getdbdata,
            'customers' => $customers,
            'token' => Tools::getAdminTokenLite('AdminOrders'),
        ]);
        
        return $this->display(__FILE__, 'views/templates/admin/hooks/displayadminordermain.tpl');

        
    }
}
