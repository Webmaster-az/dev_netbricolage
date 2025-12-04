<?php

use Mypos\IPC\Cart;
use Mypos\IPC\Config;
use Mypos\IPC\Customer;
use Mypos\IPC\Helper;
use Mypos\IPC\IPC_Exception;
use Mypos\IPC\Purchase;
use Mypos\IPC\Refund;
use Mypos\IPC\GetPaymentStatus;
use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_'))
    exit;

class MyposVirtual extends PaymentModule
{
    private $fieldLabels;

    private $fieldTypes = array(
        'sid' => 'text',
        'wallet_number' => 'text',
        'private_key' => 'textarea',
        'public_certificate' => 'textarea',
        'key_index' => 'text',
        'ppr' => 'select-ppr',
        'payment_method' => 'select-payment-method'
    );

    private $testModeConfigurationKey = 'mypos_virtual_test_mode';

    private $testPrefixConfigurationKey = 'mypos_virtual_test_prefix';

    private $submitConfigurationFormKey = 'mypos_virtual_settings_form';

    private $fields = array('sid', 'wallet_number', 'private_key', 'public_certificate', 'key_index', 'ppr', 'payment_method', 'configuration_package');

    private $scopes = array('mypos_virtual_developer', 'mypos_virtual_production');

    public $scope;

    const PAYMENT_METHOD_CARD = 1;
    const PAYMENT_METHOD_IDEAL = 2;
    const PAYMENT_METHOD_ALL = 3;

    const PAYMENT_STATUS_SUCCESS = 1;
    const PAYMENT_STATUS_PENDING = 2;
    const PAYMENT_STATUS_ERROR = 3;
    const PAYMENT_STATUS_EXPIRED = 4;

    public function __construct()
    {
        $this->name = 'myposvirtual';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->author = 'myPOS Europe LTD';
        $this->need_instance = 1;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        parent::__construct();

        $this->displayName = $this->l('myPOS Checkout');
        $this->description = $this->l('myPOS Checkout is an extension especially designed for European-based merchants who need a simple and secure complete checkout solution. Start accepting credit and debit card payments at your online store in few easy steps. By using myPOS Checkout you enjoy a checkout extension backed-up by a comprehensive free merchant account with multiple currencies.');

        $this->fieldLabels = array(
            'sid' => $this->l('Store ID'),
            'wallet_number' => $this->l('Client Number'),
            'private_key' => $this->l('Private Key'),
            'public_certificate' => $this->l('myPOS Public Certificate'),
            'key_index' => $this->l('Key Index'),
            'ppr' => $this->l('Checkout form view'),
            'payment_method' => $this->l('Payment Method'),
        );

        foreach ($this->fieldLabels as $key => $label) {
            $this->fieldLabels[$key] = $this->l($label);
        }

        $this->scope = (bool) Configuration::get($this->testModeConfigurationKey) ? $this->scopes[0] : $this->scopes[1];
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        if (!$this->registerHook('paymentOptions')) {
            return false;
        }

        if (!$this->registerHook('paymentReturn')) {
            return false;
        }

        if (!$this->registerHook('orderConfirmation')) {
            return false;
        }

        if (!$this->registerHook('adminOrder')) {
            return false;
        }

        if (!$this->registerHook('BackOfficeHeader')) {
            return false;
        }

        $result = Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_ . $this->name .'_transaction` (
			`id_mypos_virtual_transaction` int(11) NOT NULL AUTO_INCREMENT,
			`type` enum(\'payment\',\'refund\') NOT NULL,
			`id_shop` int(11) unsigned NOT NULL DEFAULT \'0\',
			`id_customer` int(11) unsigned NOT NULL,
			`id_cart` int(11) unsigned NOT NULL,
			`id_order` int(11) unsigned NOT NULL,
			`id_transaction` varchar(32) NOT NULL,
			`amount` decimal(10,2) NOT NULL,
			`currency` varchar(3) NOT NULL,
			`mode` enum(\'live\',\'test\') NOT NULL,
			`date_add` datetime NOT NULL,
		PRIMARY KEY (`id_mypos_virtual_transaction`), KEY `idx_transaction` (`type`,`id_order`))
		ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

        if (!$result) {
            return false;
        }


        //Init Order State.
        $states = OrderState::getOrderStates((int) $this->context->language->id);
        $id_state = 0;
        foreach ($states as $state) {
            if (in_array($this->trans('Awaiting myPOS payment'), $state)) {
                $id_state = $state['id_order_state'];
                break;
            }
        }

        if ($id_state == 0) {
            $orderState = new OrderState();
            $orderState->send_email = false;
            $orderState->color = '#99cce8';
            $orderState->hidden = false;
            $orderState->delivery = false;
            $orderState->logable = false;
            $orderState->invoice = false;
            $orderState->module_name = $this->name;
            $orderState->name = array();
            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                $orderState->name[$language['id_lang']] = $this->trans('Awaiting myPOS payment');
            }

            if ($orderState->add()) {
                $source = _PS_MODULE_DIR_.'myposvirtual/img/logo.gif';
                $destination = _PS_ROOT_DIR_.'/img/os/'.(int) $orderState->id.'.gif';
                copy($source, $destination);
                $id_state = $orderState->id;
            }
        }

        Configuration::updateValue('awaiting_mypos_order_state_id', (int) $id_state);

        Configuration::updateValue($this->scopes[0] . '_' . 'url', 'https://mypos.com/vmp/checkout-test');
        Configuration::updateValue($this->scopes[1] . '_' . 'url', 'https://mypos.com/vmp/checkout');

	    Configuration::updateValue($this->scopes[0] . '_' . 'ppr', '3');
	    Configuration::updateValue($this->scopes[1] . '_' . 'ppr', '3');

        Configuration::updateValue($this->scopes[0] . '_' . 'payment_method', '1');
        Configuration::updateValue($this->scopes[1] . '_' . 'payment_method', '1');

	    Configuration::updateValue($this->testPrefixConfigurationKey, uniqid() . '_');

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        Configuration::deleteByName($this->testModeConfigurationKey);
        Configuration::deleteByName($this->testPrefixConfigurationKey);

        foreach ($this->scopes as $scope) {
            foreach ($this->fields as $field) {
                Configuration::deleteByName($scope . '_' . $field);
            }

            Configuration::deleteByName($scope . '_url');
        }

        Db::getInstance()->execute('DROP TABLE `'._DB_PREFIX_. $this->name .'_transaction`');

        return true;
    }

    public function getContent()
    {
        $output = '<h2>myPOS Checkout configuration</h2>';

        if (Tools::isSubmit($this->submitConfigurationFormKey)) {
            $this->handleConfigurationForm($output);
        }

        $this->renderConfigurationForm($output);

        return $output;
    }

    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return [];
        }

        $paymentMethod = Configuration::get($this->scope . '_payment_method') ;

        $isValidIdeal = $this->checkIdealCurrency($params['cart']);

        if (!$this->checkCurrency($params['cart']) ||$paymentMethod == self::PAYMENT_METHOD_IDEAL && !$isValidIdeal) {
            return [];
        }

        if ($paymentMethod == self::PAYMENT_METHOD_ALL && $isValidIdeal) {
            $image = '/img/card_schemes_ideal_no_bg.png';
        } else if ($paymentMethod == self::PAYMENT_METHOD_IDEAL) {
            $image = '/img/mypos_ideal_no_bg.png';
        } else {
            $image = '/img/card_schemes_no_bg.png';
        }

        $paymentOption = new PaymentOption();
	    $paymentOption->setAction($this->context->link->getModuleLink($this->name, 'payment', array(), true));
	    $paymentOption->setCallToActionText($this->l('myPOS Checkout'));
	    $paymentOption->setLogo(Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . $image));
	    $paymentOption->setAdditionalInformation('<p>Pay with debit or credit card</p>');

        return [
            $paymentOption,
        ];
    }

    public function checkCurrency($cart)
    {
        $currency_order = new Currency((int)($cart->id_currency));
        $currencies_module = $this->getCurrency((int)$cart->id_currency);
        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }
        return false;
    }

    public function checkIdealCurrency($cart)
    {
        $currency_order = new Currency((int)($cart->id_currency));

        return in_array($currency_order->iso_code, array('EUR'));
    }

    public function hookPaymentReturn($params)
    {
        if (!isset($params['objOrder']) || ($params['objOrder']->module != $this->name)) {
            return false;
        }

        if (Validate::isLoadedObject($params['objOrder']) && isset($params['objOrder']->valid) && isset($params['objOrder']->reference))
        {
            $this->smarty->assign('mypos_virtual_order', array(
                'id' => $params['objOrder']->id,
                'reference' => $params['objOrder']->reference,
                'valid' => $params['objOrder']->valid
            ));

            return $this->display(__FILE__, 'views/templates/hook/order-confirmation.tpl');
        }

        return false;
    }

    public function hookOrderConfirmation($params)
    {
        if (!isset($params['objOrder']) || ($params['objOrder']->module != $this->name)) {
            return false;
        }

        if (Validate::isLoadedObject($params['objOrder']) && isset($params['objOrder']->valid) && isset($params['objOrder']->reference))
        {
            $this->smarty->assign('mypos_virtual_order', array(
                'id' => $params['objOrder']->id,
                'reference' => $params['objOrder']->reference,
                'valid' => $params['objOrder']->valid
            ));

            return $this->display(__FILE__, 'views/templates/hook/order-confirmation.tpl');
        }

        return false;
    }

    public function hookAdminOrder($params)
    {
        $orderId = (int)$_GET['id_order'];

        /* Check if the order was paid with this Addon and display the Transaction details */
        if (Db::getInstance()->getValue("SELECT `module` FROM " . _DB_PREFIX_ . "orders WHERE `id_order` = $orderId") == $this->name)
        {
            require_once __DIR__ . '/sdk/Loader.php';

            $config = $this->getMyposVirtualConfig();
            $shopId = (int)$this->context->shop->id;

            if ($config->getSid() == '' || $config->getWallet() == '' || $config->getPrivateKey() == '' || $config->getAPIPublicKey() == '' || $config->getKeyIndex() == '' || $config->getIpcURL() == '') {
                return;
            }

            /* Retrieve the transaction details */
            $mypos_virtual_transaction_details = $this->getMyposVirtualTransactionDetails($orderId, $shopId);

            /* Get all the refunds previously made (to build a list and determine if another refund is still possible) */
            $mypos_virtual_refund_details = Db::getInstance()->executeS("SELECT `amount`, `date_add`, `currency` FROM " . _DB_PREFIX_ . $this->name . "_transaction WHERE `id_order` = $orderId AND `type` = 'refund' AND `id_shop` = $shopId ORDER BY `date_add` DESC");

            $this->context->smarty->assign(array(
                'mypos_virtual_refund_time_expired' => false,
                'mypos_virtual_transaction_details' => $mypos_virtual_transaction_details,
                'mypos_virtual_refund_details' => $mypos_virtual_refund_details
            ));

            return $this->display(__FILE__, 'views/templates/admin/admin-order.tpl');
        }
    }

    public function hookBackOfficeHeader()
    {
        /* Continue only if we are on the order's details page (Back-office) */
        if (!isset($_GET['vieworder']) || !isset($_GET['id_order']))
            return;

        $orderId = (int)$_GET['id_order'];
        $shopId = (int)$this->context->shop->id;

        /* If the "Refund" button has been clicked, check if we can perform a partial or full refund on this order */
        if (Tools::isSubmit('process_refund') && isset($_POST['refund_amount']) && !empty($_POST['refund_amount']) && isset($_POST['id_transaction']))
        {
            /* Get transaction details and make sure the token is valid */
            $mypos_virtual_transaction_details = $this->getMyposVirtualTransactionDetails($orderId, $shopId);

            if (isset($mypos_virtual_transaction_details['id_transaction']) && $mypos_virtual_transaction_details['id_transaction'] == Tools::getValue('id_transaction'))
            {
                /* Check how much has been refunded already on this order */
                $mypos_virtual_refunded = Db::getInstance()->getValue("SELECT SUM(`amount`) FROM `" . _DB_PREFIX_ . $this->name . "_transaction` WHERE `id_order` = $orderId AND `type` = 'refund' AND `id_shop` = $shopId");

                if ($_POST['refund_amount'] <= number_format($mypos_virtual_transaction_details['amount'] - $mypos_virtual_refunded, 2, '.', ''))
                    $this->_processRefund($orderId, Tools::getValue('id_transaction'), (float)Tools::getValue('refund_amount'), $mypos_virtual_transaction_details);
                else
                {
                    $this->context->smarty->assign('mypos_virtual_refund', 0);
                    $this->context->smarty->assign('mypos_virtual_refund_error', $this->l('You cannot refund more than').' '.Tools::displayPrice($mypos_virtual_transaction_details['amount'] - $mypos_virtual_refunded).' '.$this->l('on this order'));
                }
            }
            else
            {
                $this->context->smarty->assign('mypos_virtual_refund', 0);
                $this->context->smarty->assign('mypos_virtual_refund_error', $this->l('Invalid transaction ID, refund cannot be performed.'));
            }
        } elseif (Tools::isSubmit('process_check_payment')) {
            $this->checkPaymentStatus($orderId);
        }
    }

    public function checkPaymentStatus($orderId)
    {
        require_once __DIR__ . '/sdk/Loader.php';

        $order = new Order($orderId);

        if ($order->getCurrentOrderState()->id != Configuration::get('awaiting_mypos_order_state_id')) {
            return;
        }

        $config = $this->getMyposVirtualConfig();
        $payment_status = new GetPaymentStatus($config);
        $payment_status->setOrderID($order->id_cart);
        $payment_status->setOutputFormat(Mypos\IPC\Defines::COMMUNICATION_FORMAT_JSON);
        if($payment_status->validate()) {
            $response = $payment_status->process();
            $data = $response->getData();
            if (array_key_exists('PaymentStatus', $data)) {
                switch ($data['PaymentStatus']) {
                    case self::PAYMENT_STATUS_SUCCESS:
                        $cart = new \Cart($order->id_cart);

                        if ($cart->getOrderTotal(true) != (float)$data['Amount']) {
                            $this->context->smarty->assign('mypos_virtual_check_payment', 0);
                            $this->context->smarty->assign(
                                'mypos_virtual_check_payment_error',
                                'Invalid amount (Paid: ' . $data['Amount'] .' Expected: ' . $cart->getOrderTotal(true) .')'
                            );
                            return;
                        }
                        if (empty($this->getMyposVirtualTransactionDetails($order->id, $order->id_shop))) {
                            $this->addTransaction('payment', array(
                                'id_shop' => (int)$cart->id_shop,
                                'id_customer' => (int)$cart->id_customer,
                                'id_cart' => (int)$cart->id,
                                'id_order' => $order->id,
                                'id_transaction' => $data['IPC_Trnref'],
                                'amount' => (float)$data['Amount'],
                                'currency' => $data['Currency'],
                            ));
                        }

                        $payment_added = false;
                        foreach ($order->getOrderPayments() as $payment) {
                            if ($payment->transaction_id == $data['IPC_Trnref']) {
                                $payment_added = true;
                                break;
                            }
                        }

                        if ($payment_added) {
                            $order_status = Configuration::get('PS_OS_PAYMENT');
                            $new_history = new OrderHistory();
                            $new_history->id_order = (int)$order->id;
                            $new_history->changeIdOrderState($order_status, $order, true);
                            $new_history->add(true);
                        } elseif (!$order->addOrderPayment((float)$data['Amount'], null, $data['IPC_Trnref'])) {
                            PrestaShopLogger::addLog('PaymentModule::validateOrder - Cannot save Order Payment', 3, null, 'Cart', (int)$cart->id, true);
                            throw new PrestaShopException('Can\'t save Order Payment');
                        } else {
                            $order_status = Configuration::get('PS_OS_PAYMENT');
                            $new_history = new OrderHistory();
                            $new_history->id_order = (int)$order->id;
                            $new_history->changeIdOrderState($order_status, $order, true);
                            $new_history->addWithemail(true);
                        }
                        break;
                    case self::PAYMENT_STATUS_ERROR:
                        $order->setCurrentState(Configuration::get('PS_OS_ERROR'));
                        break;
                    case self::PAYMENT_STATUS_EXPIRED:
                        $order->setCurrentState(Configuration::get('PS_OS_ERROR'));
                        break;
                    default:

                }
            }

        }
    }

    /**
     * Function that handles the refund of an order in the administration
     * @param $orderId
     * @param $id_transaction
     * @param $amount
     * @param $original_transaction
     */
    private function _processRefund($orderId, $id_transaction, $amount, $original_transaction)
    {
        require_once __DIR__ . '/sdk/Loader.php';

        $config = $this->getMyposVirtualConfig();
        $order = new Order($orderId);

        $refund = new Refund($config);
        $refund->setAmount($amount);
        $refund->setCurrency($original_transaction['currency']);
        $refund->setOrderID($order->id_cart);
        $refund->setTrnref($id_transaction);
        $refund->setOutputFormat(Mypos\IPC\Defines::COMMUNICATION_FORMAT_JSON);

        if($refund->validate() && $refund->process()){
            $refund_transaction = $original_transaction;
            $refund_transaction['amount'] = $amount;
            $this->addTransaction('refund', $refund_transaction);
            $this->context->smarty->assign('mypos_virtual_refund', 1);
        }else{
            $this->context->smarty->assign('mypos_virtual_refund', 0);
            $this->context->smarty->assign('mypos_virtual_refund_error', $this->l('There is an unknown error with your refund request.'));
        }
    }

    /**
     * Function that handles the processing of the configuration form.
     * @param $output
     */
    private function handleConfigurationForm(&$output)
    {
        $values = array();

        $values[$this->testModeConfigurationKey] = (bool)Tools::getValue($this->testModeConfigurationKey, 0);

        foreach ($this->scopes as $scope) {
            foreach ($this->fields as $field) {
                $values[$scope . '_' . $field] = Tools::getValue($scope . '_' . $field, '');
            }
        }

        foreach ($values as $key => $value) {
            Configuration::updateValue($key, $value);
        }

        $output .= $this->displayConfirmation($this->l('Settings saved.'));
    }

    /**
     * Function that renders the configuration form.
     * @param $output
     */
    private function renderConfigurationForm(&$output)
    {
        $output .= '<form action="" method="POST">';

        $output .= '<label>' . $this->l('Test mode') . '</label>';
        $output .= '<select name="' . $this->testModeConfigurationKey . '">'
            . '<option value="0">' . $this->l('No') . '</option>'
            . '<option value="1"' . (Configuration::get($this->testModeConfigurationKey) == 1 ? 'selected' : '') . '>' . $this->l('Yes') . '</option>'
            . '</select>';

        $output .= '<br/><label></label>Chooses whether to use myPOS&copy; developer or production environment.';

        $output .= '<br/><br/><label>' . $this->l('Cron URL') . '</label> 
                    <input type="text" value="' . $this->context->link->getModuleLink($this->name, 'cron') . '" style="width: 350px; margin-bottom: 5px;" readonly />';

        foreach ($this->scopes as $scope) {
            $explode = explode('_', $scope);
            $reverse = array_reverse($explode);
            $first = reset($reverse);

            $output .= '<h3>' . ucfirst($first) . ' settings</h3>';
            $output .= '<h4/>' . $this->trans('Easy setup.') . '<h4/>';

            $output .= '
                <label for="' . $scope . '_configuration_package">' . $this->trans('Configuration Package') . '</label>
                <textarea id="' . $scope . '_configuration_package" name="' . $scope . '_configuration_package"
                 style="width: 350px; height: 100px; max-width: 350px; min-width: 350px;">' . Configuration::get($scope . '_configuration_package') . '</textarea><br/>';
            $output .= '<label></label>';
            $output .= $this->trans('Paste in your configuration pack in the field and press configure.') . '<br/>';
            $output .= '<label></label>';
            $output .= $this->trans('This will override your current plug-in settings.') . '<br/>';
            $output .= '<label></label>';
            $output .= '<a href="https://developers.mypos.com/en/doc/online_payments/v1_4/5-store-management" target="_blank"><i class="icon-plus-sign-alt"></i> ' . $this->trans('Generate New Pack') . '</a>';
            $output .= '<br/><br/>';
            $output .= '<input type="submit" name="' . $this->submitConfigurationFormKey . '" value="' . $this->l('Configure') . '" style="margin-left: 256px; width: 100px;">';
            $output .= '<br/><br/>';
            $output .= '<h4/>' . $this->trans('Advanced setup.') . '<h4/>';

            foreach ($this->fields as $field) {
                $this->renderConfigurationFormField($output, $scope, $field);
            }
        }

        $output .= '<input type="submit" name="' . $this->submitConfigurationFormKey . '" value="' . $this->l('Save') . '" style="margin-left: 256px; width: 100px;">';

        $output .= '</form>';
    }

    /**
     * Function that renders a field in the configuration form.
     * @param $output
     * @param $scope
     * @param $field
     */
    private function renderConfigurationFormField(&$output, $scope, $field)
    {
        if (empty($this->fieldTypes[$field])) {
            return;
        }

        $key = $scope . '_' . $field;

        $value = Configuration::get($key);

        $output .= '<label for="' . $key . '">' . $this->fieldLabels[$field] . '</label>';

        switch ($this->fieldTypes[$field]) {
            case 'text':
                $output .= '<input type="text" id="' . $key . '" name="' . $key . '" value="' . $value . '" style="width: 350px; margin-bottom: 5px;">';
                break;
            case 'textarea':
                $output .= '<textarea id="' . $key . '" name="' . $key . '" style="width: 350px; height: 100px; max-width: 350px; min-width: 350px;">' . $value . '</textarea>';
                break;
	        case 'select-ppr':
		        $output .= '<select id="' . $key . '" name="' . $key . '" style="width: 360px; margin-bottom: 5px;">
						<option value="1" ' . ($value == 1 ? 'selected' : '') .  '>Full payment form</option>
						<option value="2" ' . ($value == 2 ? 'selected' : '') .  '>Simplified payment form</option>
						<option value="3" ' . ($value == 3 ? 'selected' : '') .  '>Ultra-simplified payment form</option>
					</select>';
		        break;
            case 'select-payment-method':
                $output .= '<select id="' . $key . '" name="' . $key . '" style="width: 360px; margin-bottom: 5px;">
						<option value="1" ' . ($value == 1 ? 'selected' : '') .  '>Card Payment</option>
						<option value="2" ' . ($value == 2 ? 'selected' : '') .  '>iDeal</option>
						<option value="3" ' . ($value == 3 ? 'selected' : '') .  '>All</option>
					</select>';
                break;
        }

        $output .= '<br/>';
    }

    /**
     * @return Config
     */
    public function getMyposVirtualConfig()
    {
        $config = new Config();

        //mypos.com migration
        $checkoutURL = Configuration::get($this->scope . '_' . 'url');
        if (empty($checkoutURL) || false !== stripos((parse_url($checkoutURL)['host']), 'mypos.eu')) {
            Configuration::updateValue($this->scopes[0] . '_' . 'url', 'https://mypos.com/vmp/checkout-test');
            Configuration::updateValue($this->scopes[1] . '_' . 'url', 'https://mypos.com/vmp/checkout');
        }

        $config->setIpcURL(Configuration::get($this->scope . '_' . 'url'));

        $config->setLang((new Language($this->context->cookie->id_lang))->iso_code);

        $configPackage = Configuration::get($this->scope . '_configuration_package');
        if (!empty($configPackage)) {
            $config->loadConfigurationPackage($configPackage);
        } else {
            $config->setPrivateKey(Configuration::get($this->scope . '_' . $this->fields[2]));
            $config->setAPIPublicKey(Configuration::get($this->scope . '_' . $this->fields[3]));
            $config->setKeyIndex(Configuration::get($this->scope . '_' . $this->fields[4]));
            $config->setSid(Configuration::get($this->scope . '_' . $this->fields[0]));
            $config->setWallet(Configuration::get($this->scope . '_' . $this->fields[1]));
        }

        $config->setVersion('1.4');
	    $config->setSource('sc_prestashop 1.11.3 ' . PHP_VERSION_ID . ' ' . _PS_VERSION_);

        return $config;
    }

    /**
     * @param $customerObject
     * @param $billing_address
     * @param $country
     * @return Customer
     */
    public function getMyposVirtualCustomer($customerObject, $billing_address, $country)
    {
        $customer = new Customer();
        $customer->setFirstName($customerObject->firstname);
        $customer->setLastName($customerObject->lastname);
        $customer->setEmail($customerObject->email);
        $customer->setPhone($billing_address->phone);
        $customer->setCountry($country);
        $customer->setAddress($billing_address->address1);
        $customer->setCity($billing_address->city);
        $customer->setZip($billing_address->postcode);
        return $customer;
    }

    /**
     * @param \Cart $cartObject
     * @return Cart
     */
    public function getMyposVirtualCart(\Cart $cartObject)
    {
        $cart = new \Mypos\IPC\Cart;

        foreach ($cartObject->getProducts() as $product) {
            $cart->add($product['name'], $product['quantity'], number_format($product['price_wt'], 2, '.', ''));
        }

        if (!empty($cartObject->gift)) {
            $giftWrappingPrice = $cartObject->getGiftWrappingPrice();
            $cart->add('Gift Wrapping', 1, number_format($giftWrappingPrice, 2, '.', ''));
        }

        $cart->add('Shipping', 1, number_format($cartObject->getTotalShippingCost(), 2, '.', ''));

        foreach ($cartObject->getCartRules() as $cartRule) {
            $cart->add($cartRule['name'], 1, -number_format($cartRule['value_real'], 2, '.', ''));
        }

        return $cart;
    }

    /**
     * @param $config
     * @param $cartObject
     * @param $currency
     * @param $customer
     * @param $cart
     * @return Purchase
     */
    public function getMyposVirtualPurchase($config, $cartObject, $currency, $customer, $cart)
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $shopDomain = Configuration::get('PS_SSL_ENABLED') ? Tools::getShopDomainSsl(true) : Tools::getShopDomain(true);
            $urlOk = $shopDomain . __PS_BASE_URI__ . 'order-confirmation.php?id_cart=' . (int)$this->context->cart->id .
                '&id_module=' . (int)$this->id . '&key=' . $this->context->customer->secure_key;
        } else {
            $urlOk = $this->context->link->getPageLink('order-confirmation.php', null, null, array(
                'id_cart' => (int)$this->context->cart->id,
                'key' => $this->context->customer->secure_key,
                'id_module' => $this->id),
                true
            );
        }

        $prefix = Configuration::get($this->testModeConfigurationKey) ? Configuration::get($this->testPrefixConfigurationKey) : '';
        $purchase = new Purchase($config);
        $purchase->setUrlCancel($this->context->link->getModuleLink($this->name, 'cancel'));
        $purchase->setUrlOk($urlOk);
        $purchase->setUrlNotify($this->context->link->getModuleLink($this->name, 'notify'));
        $purchase->setOrderID($prefix . $cartObject->id);
        $purchase->setCurrency($currency);
        $purchase->setNote('myPOS Checkout plugin for PrestaShop');
        $purchase->setCustomer($customer);
        $purchase->setCart($cart);
        $purchase->setCardTokenRequest(Purchase::CARD_TOKEN_REQUEST_NONE);
        $purchase->setPaymentParametersRequired(Configuration::get($this->scope . '_' . 'ppr'));
        $purchase->setPaymentMethod(Configuration::get($this->scope . '_' . 'payment_method'));

        return $purchase;
    }

    /**
     * @param $id_order
     * @param $id_transaction
     */
    public function addTransactionId($id_order, $id_transaction)
    {
        if (version_compare(_PS_VERSION_, '1.5', '>='))
        {
            $new_order = new Order((int)$id_order);
            if (Validate::isLoadedObject($new_order))
            {
                $payment = $new_order->getOrderPaymentCollection();
                if (isset($payment[0]))
                {
                    $payment[0]->transaction_id = pSQL($id_transaction);
                    $payment[0]->save();
                }
            }
        }
    }

    /**
     * @param string $type
     * @param $details
     * @return bool
     */
    public function addTransaction($type = 'payment', $details)
    {
        $type = pSQL($type);
        $details['id_shop'] = (int) $details['id_shop'];
        $details['id_customer'] = (int) $details['id_customer'];
        $details['id_cart'] = (int) $details['id_cart'];
        $details['id_order'] = (int) $details['id_order'];
        $details['id_transaction'] = pSQL($details['id_transaction']);
        $details['amount'] = (float) $details['amount'];
        $details['currency'] = pSQL($details['currency']);
        $mode = $this->scope == $this->scopes[0] ? 'test' : 'live';

        $result = Db::getInstance()->execute("INSERT INTO `" ._DB_PREFIX_ . $this->name . "_transaction` (`type`, `id_shop`, `id_customer`, `id_cart`, `id_order`, `id_transaction`, `amount`, `currency`, `mode`, `date_add`) VALUES ('$type', '{$details['id_shop']}', '{$details['id_customer']}', '{$details['id_cart']}', '{$details['id_order']}', '{$details['id_transaction']}', '{$details['amount']}', '{$details['currency']}', '$mode', NOW())");

        return $result;
    }

    /**
     * @param $orderId
     * @param $shopId
     * @return array|bool|null|object
     */
    private function getMyposVirtualTransactionDetails($orderId, $shopId)
    {
        $mypos_virtual_transaction_details = Db::getInstance()->getRow("SELECT * FROM " . _DB_PREFIX_ . $this->name . "_transaction WHERE `id_order` = $orderId AND `type` = 'payment' AND `id_shop` = $shopId");
        return $mypos_virtual_transaction_details;
    }
}