<?php
/**
* 2012-2018 Areama
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@areama.net so we can send you a copy immediately.
*
*  @author    Areama <contact@areama.net>
*  @copyright 2018 Areama
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Areama
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once dirname(__FILE__).'/classes/ArLsfGeneralConfigForm.php';
include_once dirname(__FILE__).'/classes/ArLsfOrdersConfigForm.php';
include_once dirname(__FILE__).'/classes/ArLsfFakeConfigForm.php';
include_once dirname(__FILE__).'/classes/ArLsfAddToCartConfigForm.php';
include_once dirname(__FILE__).'/classes/ArLsfVisitorConfigForm.php';

include_once dirname(__FILE__).'/classes/ArLsfStringComposer.php';

class ArLsf extends Module
{
    protected $html = '';
    protected $postErrors = array();
    
    protected $generalConfigModel;
    protected $ordersConfigModel;
    protected $addToCartConfigModel;
    protected $visitorConfigModel;
    protected $fakeConfigModel;
    protected $stringComposer;

    const REMIND_TO_RATE = 259200; // 3 days
    
    public function __construct()
    {
        $this->name = 'arlsf';
        $this->tab = 'front_office_features';
        $this->version = '2.4.9';
        $this->controllers = array('ajax');
        $this->author = 'Areama';
        $this->need_instance = 0;
        $this->bootstrap = true;
        if ($this->is17()) {
            $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        }
        $this->module_key = '7a20e8f40a96c7f6ada409e3654846e9';
        parent::__construct();

        $this->displayName = $this->l('Live Sales Popup');
        $this->description = $this->l('Displays pop-up with sound notification with the latest orders placed on your shop to your visitors. Also it can be displayed when product added to cart.');
        $this->confirmUninstall = $this->l('Are you sure you want to delete all data?');
        
        $this->generalConfigModel = new ArLsfGeneralConfigForm($this, 'ar_lsf_');
        $this->generalConfigModel->loadFromConfig();
        
        $this->ordersConfigModel = new ArLsfOrdersConfigForm($this, 'ar_lsfo_');
        $this->ordersConfigModel->loadFromConfig();
        
        $this->fakeConfigModel = new ArLsfFakeConfigForm($this, 'ar_f_');
        $this->fakeConfigModel->loadFromConfig();
        
        $this->addToCartConfigModel = new ArLsfAddToCartConfigForm($this, 'ar_lsfa_');
        $this->addToCartConfigModel->loadFromConfig();
        
        $this->visitorConfigModel = new ArLsfVisitorConfigForm($this, 'ar_lsfv_');
        $this->visitorConfigModel->loadFromConfig();
        
        $this->stringComposer = new ArLsfStringComposer($this);
    }
    
    public function hookDisplayHeader($params)
    {
        if (!$this->ordersConfigModel->enabled && !$this->addToCartConfigModel->enabled && !$this->visitorConfigModel->enabled) {
            return null;
        }
        
        if (Tools::getValue('content_only')) {
            return null;
        }
        
        if ($this->generalConfigModel->sandbox) {
            $ips = explode("\r\n", $this->generalConfigModel->allowed_ips);
            if (!in_array($this->generalConfigModel->getCurrentIP(), $ips)) {
                return null;
            }
        }
        
        $controllerId = (isset($this->context->controller->php_self) && $this->context->controller->php_self)?
            $this->context->controller->php_self : null;
        if ($this->is15() && $this->context->controller instanceof ProductController) {
            $controllerId = 'product';
        }
        $this->generalConfigModel->pages = explode(',', $this->generalConfigModel->pages);
        if (!in_array($controllerId, $this->generalConfigModel->pages)) {
            return null;
        }
        
        if ($this->generalConfigModel->mobile == false && Context::getContext()->isMobile()) {
            return null;
        }
        
        $productId = 0;
        if ($controllerId == 'product') {
            $productId = Context::getContext()->controller->getProduct()->id;
        }
        
        $this->context->controller->addCSS($this->_path.'views/css/animate.min.css');
        $this->context->controller->addCSS($this->_path.'views/css/styles.css');
        $this->context->controller->addJS($this->_path.'views/js/scripts.js');
        
        
        
        if (Tools::strpos($this->generalConfigModel->delay_between, '-') === false) {
            $this->generalConfigModel->delay_between = array(
                (int)$this->generalConfigModel->delay_between,
                (int)$this->generalConfigModel->delay_between
            );
        } else {
            $this->generalConfigModel->delay_between = explode('-', $this->generalConfigModel->delay_between);
        }
        
        if (Tools::strpos($this->generalConfigModel->delay_first, '-') === false) {
            $this->generalConfigModel->delay_first = array(
                (int)$this->generalConfigModel->delay_first,
                (int)$this->generalConfigModel->delay_first
            );
        } else {
            $this->generalConfigModel->delay_first = explode('-', $this->generalConfigModel->delay_first);
        }
        $last_cart = null;
        if ($this->addToCartConfigModel->enabled) {
            $sql = new DbQuery();
            $sql->from('cart_product');
            $sql->orderBy('date_add DESC');
            if ($row = Db::getInstance()->getRow($sql)) {
                $last_cart = $row['date_add'];
            }
        }
        if (isset($this->generalConfigModel->sound) && $this->generalConfigModel->sound) {
            $this->generalConfigModel->sound = basename($this->generalConfigModel->sound);
        }
        
        $this->smarty->assign(array(
            'ajaxUrl' => $this->getAjaxUrl(),
            'generalConfig' => $this->generalConfigModel,
            'ordersConfig' => $this->ordersConfigModel,
            'addToCartConfig' => $this->addToCartConfigModel,
            'visitorConfig' => $this->visitorConfigModel,
            'fakeConfig' => $this->fakeConfigModel,
            'sessionKey' => md5(uniqid() . rand(0, time())),
            'last_cart' => $last_cart,
            'productId' => $productId,
            'token' => Tools::getToken('arlsf'),
            'path' => $this->getPath(),
            'link' => Context::getContext()->link
        ));
        
        return $this->display(__FILE__, 'head.tpl');
    }
    
    public function getAjaxUrl()
    {
        return Context::getContext()->link->getModuleLink('arlsf', 'ajax');
    }
    
    public function uninstall()
    {
        if (!parent::uninstall() || !$this->clearConfig()) {
            return false;
        }
        return true;
    }
    
    public function install()
    {
        if (!parent::install()
                || !$this->installHook()
                || !$this->installDb()
                || !$this->installDefaults()) {
            return (false);
        }
        return (true);
    }
    
    public function installDb()
    {
        $res = Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'arlsf_session` (
            `id_session` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `session_key` VARCHAR(50) NULL DEFAULT NULL,
            `id_order` INT(10) UNSIGNED NULL DEFAULT NULL,
            `timestamp` INT(10) UNSIGNED NOT NULL DEFAULT "0",
            PRIMARY KEY (`id_session`),
            INDEX `session_key` (`session_key`),
            INDEX `timestamp` (`timestamp`)
        )
        ENGINE=' . _MYSQL_ENGINE_ . ' COLLATE=utf8_general_ci;');
        
        return $res && Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'arlsf_visitor` (
            `id_visitor` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_product` INT(10) UNSIGNED NOT NULL,
            `key` VARCHAR(50) NOT NULL,
            `timestamp` INT(10) UNSIGNED NOT NULL,
            PRIMARY KEY (`id_visitor`),
            INDEX `id_product` (`id_product`),
            INDEX `ip` (`key`),
            INDEX `timestamp` (`timestamp`)
        )
        ENGINE=' . _MYSQL_ENGINE_ . ' COLLATE=utf8_general_ci;');
    }
    
    public function uninstallDb()
    {
        Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'arlsf_session`');
        Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'arlsf_visitor`');
    }
    
    protected function installDefaults()
    {
        foreach ($this->getForms() as $model) {
            $model->loadDefaults();
            $model->saveToConfig(false);
        }
        return true;
    }
    
    protected function installHook()
    {
        return $this->registerHook('displayHeader');
    }


    protected function clearConfig()
    {
        foreach ($this->getForms() as $model) {
            $model->clearConfig();
        }
        return true;
    }
    
    public function getConfig()
    {
        return Configuration::getMultiple($this->getFieldNames());
    }
    
    public function getFieldNames()
    {
        $fieldSet = $this->getNotificationFormFields();
        $fields = array();
        foreach ($fieldSet['form']['input'] as $field) {
            if (in_array($field['type'], array('radio', 'switch', 'text', 'select', 'text', 'color', 'textarea'))) {
                $fields[] = $field['name'];
            }
        }
        return $fields;
    }

    public function displayVisitorPopup($product, $count, $ipa)
    {
        $cover = null;
        $second_image = null;
        $id_lang = Context::getContext()->language->id;
        if ($ipa) {
            $images = Image::getImages($id_lang, $product->id, $ipa);
            if ($images && is_array($images) && isset($images[0])) {
                $cover = $images[0];
                if ($this->generalConfigModel->second_image && isset($images[1])) {
                    $second_image = $images[1]['id_image'];
                }
            }
        }
        if ($cover == null) {
            $cover = Product::getCover($product->id);
        }
        if ($this->generalConfigModel->second_image && empty($second_image)) {
            $second_image = $this->getSecondImage($product, $id_lang);
        }
        
        $l1 = Configuration::get('AR_LSFV_LINE1', $id_lang);
        $l2 = Configuration::get('AR_LSFV_LINE2', $id_lang);
        $l3 = Configuration::get('AR_LSFV_LINE3', $id_lang);
        $l4 = Configuration::get('AR_LSFV_LINE4', $id_lang);
        $l5 = Configuration::get('AR_LSFV_LINE5', $id_lang);
        $line1 = $this->stringComposer->buildVisitorLine($l1, $product, $count, $ipa);
        $line2 = $this->stringComposer->buildVisitorLine($l2, $product, $count, $ipa);
        $line3 = $this->stringComposer->buildVisitorLine($l3, $product, $count, $ipa);
        $line4 = $this->stringComposer->buildVisitorLine($l4, $product, $count, $ipa);
        $line5 = $this->stringComposer->buildVisitorLine($l5, $product, $count, $ipa);
        $this->smarty->assign(array(
            'line1' => $line1,
            'line2' => $line2,
            'line3' => $line3,
            'line4' => $line4,
            'line5' => $line5,
            'generalConfig' => $this->generalConfigModel,
            'product' => $product,
            'secondImage' => $second_image,
            'id_image' => $cover['id_image'],
            'link' => Context::getContext()->link,
            'productUrl' => $this->getProductUrl($product, $ipa)
        ));
        return $this->display(__FILE__, '_partials/notification.tpl');
    }
    
    public function displayCartPopup(Cart $cart, $customer, $product, $address, $combinations, $ipa = null)
    {
        $cover = null;
        $second_image = null;
        $id_lang = Context::getContext()->language->id;
        if ($ipa) {
            $images = Image::getImages($id_lang, $product->id, $ipa);
            if ($images && is_array($images) && isset($images[0])) {
                $cover = $images[0];
                if ($this->generalConfigModel->second_image && isset($images[1])) {
                    $second_image = $images[1]['id_image'];
                }
            }
        }
        if ($cover == null) {
            $cover = Product::getCover($product->id);
        }
        if ($this->generalConfigModel->second_image && empty($second_image)) {
            $second_image = $this->getSecondImage($product, $id_lang);
        }
        
        
        $l1 = Configuration::get('AR_LSFA_LINE1', $id_lang);
        $l2 = Configuration::get('AR_LSFA_LINE2', $id_lang);
        $l3 = Configuration::get('AR_LSFA_LINE3', $id_lang);
        $l4 = Configuration::get('AR_LSFA_LINE4', $id_lang);
        $l5 = Configuration::get('AR_LSFA_LINE5', $id_lang);
        $line1 = $this->stringComposer->buildAddToCartLine($l1, null, 0, $cart, $customer, $address, $product, $combinations, $ipa);
        $line2 = $this->stringComposer->buildAddToCartLine($l2, null, 0, $cart, $customer, $address, $product, $combinations, $ipa);
        $line3 = $this->stringComposer->buildAddToCartLine($l3, null, 0, $cart, $customer, $address, $product, $combinations, $ipa);
        $line4 = $this->stringComposer->buildAddToCartLine($l4, null, 0, $cart, $customer, $address, $product, $combinations, $ipa);
        $line5 = $this->stringComposer->buildAddToCartLine($l5, null, 0, $cart, $customer, $address, $product, $combinations, $ipa);
        $this->smarty->assign(array(
            'line1' => $line1,
            'line2' => $line2,
            'line3' => $line3,
            'line4' => $line4,
            'line5' => $line5,
            'generalConfig' => $this->generalConfigModel,
            
            'cart' => $cart,
            'product' => $product,
            'customer' => $customer,
            'secondImage' => $second_image,
            'id_image' => $cover['id_image'],
            'link' => Context::getContext()->link,
            'productUrl' => $this->getProductUrl($product, $ipa)
        ));
        return $this->display(__FILE__, '_partials/notification.tpl');
    }
    
    public function displayOrderPopup(Order $order, $itemsCount, $customer, $address, $invoiceAddress, $product, $combinations, $ipa = null)
    {
        $cover = null;
        $second_image = null;
        $id_lang = Context::getContext()->language->id;
        if ($ipa) {
            $images = Image::getImages($id_lang, $product->id, $ipa);
            if ($images && is_array($images) && isset($images[0])) {
                $cover = $images[0];
                if ($this->generalConfigModel->second_image && isset($images[1])) {
                    $second_image = $images[1]['id_image'];
                }
            }
        }
        if ($cover == null) {
            $cover = Product::getCover($product->id);
        }
        if ($this->generalConfigModel->second_image && empty($second_image)) {
            $second_image = $this->getSecondImage($product, $id_lang);
        }
        $l1 = Configuration::get('AR_LSFO_LINE1', $id_lang);
        $l2 = Configuration::get('AR_LSFO_LINE2', $id_lang);
        $l3 = Configuration::get('AR_LSFO_LINE3', $id_lang);
        $l4 = Configuration::get('AR_LSFO_LINE4', $id_lang);
        $l5 = Configuration::get('AR_LSFO_LINE5', $id_lang);
        
        $line1 = $this->stringComposer->buildOrderLine($l1, $order, $itemsCount, null, $customer, $address, $invoiceAddress, $product, $combinations, $ipa);
        $line2 = $this->stringComposer->buildOrderLine($l2, $order, $itemsCount, null, $customer, $address, $invoiceAddress, $product, $combinations, $ipa);
        $line3 = $this->stringComposer->buildOrderLine($l3, $order, $itemsCount, null, $customer, $address, $invoiceAddress, $product, $combinations, $ipa);
        $line4 = $this->stringComposer->buildOrderLine($l4, $order, $itemsCount, null, $customer, $address, $invoiceAddress, $product, $combinations, $ipa);
        $line5 = $this->stringComposer->buildOrderLine($l5, $order, $itemsCount, null, $customer, $address, $invoiceAddress, $product, $combinations, $ipa);
        $this->smarty->assign(array(
            'line1' => $line1,
            'line2' => $line2,
            'line3' => $line3,
            'line4' => $line4,
            'line5' => $line5,
            'order' => $order,
            'product' => $product,
            'items' => $itemsCount,
            'customer' => $customer,
            'secondImage' => $second_image,
            'generalConfig' => $this->generalConfigModel,
            'id_image' => $cover['id_image'],
            'link' => Context::getContext()->link,
            'productUrl' => $this->getProductUrl($product, $ipa)
        ));
        return $this->display(__FILE__, '_partials/notification.tpl');
    }
    
    protected function getSecondImage($product, $id_lang)
    {
        $images = $product->getImages($id_lang);
        foreach ($images as $img) {
            if (!$img['cover']) {
                return $img['id_image'];
            }
        }
        return null;
    }


    protected function getProductUrl($product, $ipa = null)
    {
        $url = Context::getContext()->link->getProductLink($product, null, null, null, null, null, $ipa, false, false, true);
        if ($params = Configuration::get('AR_LSF_URL_PARAMS')) {
            if (Tools::strpos($params, '?') === 0) {
                $params = substr_replace($params, '', 0, 1);
            }
            if (Tools::strpos($params, '&') === 0) {
                $params = substr_replace($params, '', 0, 1);
            }
            
            if (Tools::strpos($url, '?') === false) {
                $params = '?' . $params;
            } else {
                $params = '&' . $params;
            }
            if (Tools::strpos($url, '#') !== false) {
                return str_replace('#', $params . '#', $url);
            }
            return $url . $params;
        } else {
            return $url;
        }
    }
    
    protected function delayBetween($value, $field)
    {
        $data = array();
        $message = sprintf($this->l('"%s" must be integer or two integers separated by "-" sign'), $field['label']);
        if (strpos($value, '-') !== false) {
            $data = explode('-', $value);
        } else {
            $data = array($value);
        }
        if (count($data) > 2) {
            $this->postErrors['AR_LSF_DELAY_BETWEEN'] = $message;
        } elseif (count($data) == 1 && !Validate::isInt($data[0])) {
            $this->postErrors['AR_LSF_DELAY_BETWEEN'] = $message;
        }
    }
    
    protected function clearCache()
    {
        $this->_clearCache('head.tpl');
    }
    
    public function getContent()
    {
        $this->context->controller->addCSS($this->_path.'views/css/styles.css');
        if ($this->isSubmit()) {
            if ($this->postValidate()) {
                $this->postProcess();
            }
        }
        $this->reminder();
        $this->html .= $this->renderForm();
        return $this->html;
    }
    
    protected function reminder()
    {
        if (!$installTS = Configuration::get('AR_LSF_INSTALL_TS')) {
            $installTS = time();
            Configuration::updateValue('AR_LSF_INSTALL_TS', $installTS);
        }
        $reminder = Configuration::get('AR_LSF_REMINDER');
        if ($reminder == 0) {
            $reminder = $installTS + self::REMIND_TO_RATE;
        }
        if (!in_array($reminder, array('-1', '-2'))) {
            if (time() > $reminder) {
                $this->smarty->assign(array(
                    'path' => $this->getPath(),
                    'ajaxUrl' => $this->getAjaxUrl()
                ));
                $this->html .= $this->display(__FILE__, 'rate-modal.tpl');
            }
        }
    }
    
    public function isSubmit()
    {
        foreach ($this->getAllowedSubmits() as $submit) {
            if (Tools::isSubmit($submit)) {
                return true;
            }
        }
    }
    
    public function getAllowedSubmits()
    {
        $submits = array();
        foreach ($this->getForms() as $model) {
            $submits[] = get_class($model);
        }
        return $submits;
    }
    
    public function postProcess()
    {
        foreach ($this->getForms() as $model) {
            if (Tools::isSubmit(get_class($model))) {
                $model->populate();
                if ($model->saveToConfig()) {
                    $this->html .= $this->displayConfirmation($this->l('Settings updated'));
                } else {
                    $this->postValidate();
                }
            }
        }
    }
    
    public function postValidate()
    {
        foreach ($this->getForms() as $model) {
            if (Tools::isSubmit(get_class($model))) {
                $model->loadFromConfig();
                $model->populate();
                if (!$model->validate()) {
                    foreach ($model->getErrors() as $errors) {
                        foreach ($errors as $error) {
                            $this->html .= $this->displayError($error);
                        }
                    }
                    return false;
                }
                return true;
            }
        }
    }
    
    public function renderForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='
            .$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        
        $langs = Language::getLanguages(true);
        foreach ($langs as $k => $l) {
            $langs[$k]['is_default'] = (int)($l['id_lang'] == $lang->id);
        }
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $langs,
            'id_language' => $this->context->language->id,
            'path' => $this->getPath(),
        );
        $helper->base_folder =  dirname(__FILE__);
        $helper->base_tpl = '/views/templates/admin/arlsf/helpers/form/form.tpl';
        
        $this->smarty->assign(array(
            'form' => $helper,
            'generalConfig' => array($this->getForm($this->generalConfigModel)),
            'ordersConfig' => array($this->getForm($this->ordersConfigModel)),
            'addToCartConfig' => array($this->getForm($this->addToCartConfigModel)),
            'fakeConfig' => array($this->getForm($this->fakeConfigModel)),
            'visitorConfig' => array($this->getForm($this->visitorConfigModel)),
            'orderLegend' => $this->stringComposer->getOrderTags(),
            'addToCartLegend' => $this->stringComposer->getAddToTags(),
            'visitorLegend' => $this->stringComposer->getVisitorTags(),
            'link' => $this->context->link,
            'path' => $this->getPath(),
            'active_tab' => $this->getActiveTab(),
            'name' => $this->displayName,
            'version' => $this->version
        ));
        return $this->display(__FILE__, 'config.tpl');
    }
    
    public function getActiveTab()
    {
        foreach ($this->getForms() as $model) {
            if (Tools::isSubmit(get_class($model))) {
                return get_class($model);
            }
        }
        return null;
    }
    
    public function getConfigFieldsValues()
    {
        $values = array();
        foreach ($this->getForms() as $model) {
            $model->loadFromConfig();
            $model->populate();
            foreach ($model->getAttributes() as $attr => $value) {
                if ($model->getMultipleSelect($attr) && !is_array($value)) {
                    $values[$model->getConfigAttribueName($attr)] = explode(',', $value);
                } else {
                    $values[$model->getConfigAttribueName($attr)] = $value;
                }
            }
        }
        return $values;
    }
    
    public function getForms()
    {
        return array(
            $this->generalConfigModel,
            $this->ordersConfigModel,
            $this->addToCartConfigModel,
            $this->visitorConfigModel,
            $this->fakeConfigModel
        );
    }
    
    public function getOrdersConfigModel()
    {
        return $this->ordersConfigModel;
    }
    
    public function getAddToCartConfigModel()
    {
        return $this->addToCartConfigModel;
    }
    
    /**
     *
     * @return ArLsfVisitorConfigForm;
     */
    public function getVisitorConfigModel()
    {
        return $this->visitorConfigModel;
    }
    
    public function getFakeConfigModel()
    {
        return $this->fakeConfigModel;
    }
    
    public function getGeneralConfigModel()
    {
        return $this->generalConfigModel;
    }
    
    public function getFormConfigs()
    {
        $configs = array();
        foreach ($this->getForms() as $form) {
            $configs[] = $this->getForm($form);
        }
        return $configs;
    }
    
    public function getForm($model)
    {
        $model->populate();
        $config = $model->getFormHelperConfig();
        return array(
            'form' => array(
                'name' => get_class($model),
                'legend' => array(
                    'title' => $model->getFormTitle(),
                    'icon' => $model->getFormIcon()
                ),
                'input' => $config,
                'submit' => array(
                    'name' => get_class($model),
                    'class' => $this->is15()? 'button' : null,
                    'title' => $this->l('Save'),
                )
            )
        );
    }
    
    
    
    public function is15()
    {
        if ((version_compare(_PS_VERSION_, '1.5.0', '>=') === true)
                && (version_compare(_PS_VERSION_, '1.6.0', '<') === true)) {
            return true;
        }
        return false;
    }
    
    public function is16()
    {
        if ((version_compare(_PS_VERSION_, '1.6.0', '>=') === true)
                && (version_compare(_PS_VERSION_, '1.7.0', '<') === true)) {
            return true;
        }
        return false;
    }
    
    public function is17()
    {
        if ((version_compare(_PS_VERSION_, '1.7.0', '>=') === true)
                && (version_compare(_PS_VERSION_, '1.8.0', '<') === true)) {
            return true;
        }
        return false;
    }
    
    public function getPath()
    {
        return $this->_path;
    }
    
    public function render($template, $params = array())
    {
        $this->smarty->assign($params);
        return $this->display(__FILE__, $template);
    }
}
