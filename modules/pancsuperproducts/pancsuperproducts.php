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

class Pancsuperproducts extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'pancsuperproducts';
        $this->tab = 'checkout';
        $this->version = '1.0.0';
        $this->author = 'PANC';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Super products');
        $this->description = $this->l('Adds new fields to products');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() &&
            $this->_installSql() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&            
            $this->registerHook('displayAdminProductsQuantitiesStepBottom') &&
            $this->registerHook('displayAdminProductsCombinationBottom') &&
            $this->registerHook('displayAdminProductsPriceStepBottom') &&            
            $this->registerHook('displayAdminProductsMainStepRightColumnBottom') &&            
            $this->registerHook('displayFooterProduct');
    }

    public function uninstall() {
        return parent::uninstall() && 
            $this->_unInstallSql();
    }

    protected function _installSql() {
        $sqlInstall = 'ALTER TABLE ' . _DB_PREFIX_ . 'product' . ' ADD panc_mpnumber INT, ADD panc_warranty INT';
 
        $returnSql = Db::getInstance()->execute($sqlInstall);
 
        return $returnSql;
    }
 
    /* Delete created fields from databasel */
    protected function _unInstallSql() {
       
    }

    /*  Add CSS & JavaScript to FO */
    public function hookHeader($params)
    {
        if($this->context->controller->php_self == 'product'){
            $product = new Product(Tools::getValue('id_product'));
            
            if ($product->panc_mpnumber > 0) {
                $this->context->controller->addJS($this->_path.'/views/js/front.js');
            }
        }
    }

    public function hookBackOfficeHeader()
    {        
        $this->context->controller->addJS($this->_path.'views/js/jsbarcode.js');
    }

    /* Backoffice field */
    public function hookdisplayAdminProductsQuantitiesStepBottom($params)
    {
        $product = new Product($params['id_product']);
        $this->smarty->assign('panc_mpnumber', $product->panc_mpnumber);
        
        return $this->display(__FILE__, 'views/templates/admin/hooks/adminproductsquantitiesstepbottom.tpl');
    }

    /* Backoffice field */
    public function hookdisplayAdminProductsCombinationBottom ($params)
    {
        $product = new Product($params['id_product']);
        $this->smarty->assign('panc_mpnumber', $product->panc_mpnumber);
        
        return $this->display(__FILE__, 'views/templates/admin/hooks/adminproductsquantitiesstepbottom.tpl');
    }

    /* Backoffice field */
    public function hookdisplayAdminProductsPriceStepBottom ($params)
    {
        $product = new Product($params['id_product']);
        
        return $this->display(__FILE__, 'views/templates/admin/hooks/adminproductspricestepbottom.tpl');
    }

    /* Backoffice field */
    public function hookdisplayAdminProductsMainStepRightColumnBottom ($params)
    {
        $product = new Product($params['id_product']);
        $this->smarty->assign(
            array(
                'panc_ean13' => $product->ean13,
                'panc_warranty' => $product->panc_warranty
            )
        );

        return $this->display(__FILE__, 'views/templates/admin/hooks/adminproductsmainsteprightcolumnbottom.tpl');
    }


    /* Define JS variable in FO */
    public function hookdisplayFooterProduct($params)
    {
        $product = new Product(Tools::getValue('id_product'));
        $this->smarty->assign('panc_mpnumber', $product->panc_mpnumber);
        
        if ($product->panc_mpnumber > 0) {
            return $this->display(__FILE__, 'views/templates/front/hooks/displayfooterproduct.tpl');
        }
    }
}