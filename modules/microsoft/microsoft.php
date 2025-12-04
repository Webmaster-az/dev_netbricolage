<?php
/**
 * Copyright (c) Microsoft Corporation. All rights reserved.
 * Licensed under the AFL License.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 *  https://opensource.org/licenses/AFL-3.0
 *
 * @author    Microsoft Corporation <msftadsappsupport@microsoft.com>
 * @copyright Microsoft Corporation
 * @license    https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */
use PrestaShop\Module\Microsoft\Config\Config;
use PrestaShop\ModuleLibServiceContainer\DependencyInjection\ServiceContainer;

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('_PS_VERSION_')) {
    exit;
}

class Microsoft extends Module
{
    protected $config_form = false;

    private $serviceContainer;

    public function __construct()
    {
        $this->name = 'microsoft';
        $this->tab = 'advertising_marketing';
        $this->version = '1.0.9';
        $this->author = 'Microsoft';
        $this->need_instance = 0;
        $this->module_key = '9af7a9018955709e6b67516e7c743ad6';
        // Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Microsoft');
        $this->description = $this->l('Reach new shoppers on the Microsoft Search Network and Microsoft Audience Network with just a few clicks. The onboarding is simple, allowing you to quickly connect with our shopping offerings to showcase your products using free and paid listings. With free listings, you are automatically able to show on the Microsoft Bing Shopping tab and the Microsoft Start Shopping tab.');

        $this->confirmUninstall = $this->l('');

        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => _PS_VERSION_];

        if (null === $this->serviceContainer) {
            $this->serviceContainer = new ServiceContainer($this->name, $this->getLocalPath());
        }
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update.
     */
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        Configuration::updateValue('PS_MICROSOFT_LIVE_MODE', false);
        $this->getService('ps_accounts.installer')->install();
        $this->curl_post_https(Config::PS_MICROSOFT_DOWNLOAD_API_URL);

        return parent::install()
            && $this->registerHook('header')
            && $this->registerHook('backOfficeHeader')
            && $this->registerHook('displayOrderConfirmation')
            && $this->installTabs();
    }

    public function uninstall()
    {
        Configuration::deleteByName('PS_MICROSOFT_LIVE_MODE');
        $this->curl_post_https(Config::PS_MICROSOFT_UNINSTALL_API_URL, json_encode(['store_domains' => Config::PS_MICROSOFT_PSX_UUID()]));

        return parent::uninstall();
    }

    /**
     * Load the configuration form.
     */
    public function getContent()
    {
        unset($_GET['controller'], $_GET['configure'], $_GET['token'], $_GET['controllerUri']);

        Tools::redirectAdmin($this->context->link->getAdminLink('AdminPsMicrosoftModule') . '&' . http_build_query($_GET));
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        $this->context->controller->addCSS($this->getPathUri() . 'views/css/admin/menu.css');
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    public function hookDisplayOrderConfirmation($params)
    {
        if (null == $params || null == $params['order']) {
            return;
        }

        $order = $params['order'];
        if (Validate::isLoadedObject($order)) {
            $currency = new Currency($order->id_currency);
            $conversion_value_for_ms = ['revenue' => $order->total_paid, 'currency' => $currency->iso_code];
            Media::addJsDef([
                'conversion_value_for_ms' => $conversion_value_for_ms,
            ]);
        }
        $this->context->controller->addJS($this->_path . Config::PS_MICROSOFT_UET_TAG_PATH($this->context->shop->id));
    }

    public function installTabs()
    {
        $installTabCompleted = true;

        foreach ($this->getTTabs() as $tab) {
            $installTabCompleted = $installTabCompleted && $this->installTab(
                $tab['className'],
                $tab['parent'],
                $tab['name'],
                $tab['module'],
                $tab['active'],
                $tab['icon']
            );
        }

        return $installTabCompleted;
    }

    public function installTab($className, $parent, $name, $module, $active, $icon)
    {
        if (Tab::getIdFromClassName($className)) {
            return true;
        }

        $idParent = is_int($parent) ? $parent : Tab::getIdFromClassName($parent);

        $moduleTab = new Tab();
        $moduleTab->class_name = $className;
        $moduleTab->id_parent = $idParent;
        $moduleTab->module = $module;
        $moduleTab->active = $active;
        if (property_exists($moduleTab, 'icon')) {
            $moduleTab->icon = $icon;
        }

        $languages = Language::getLanguages(true);
        foreach ($languages as $language) {
            $moduleTab->name[$language['id_lang']] = $name;
        }

        return $moduleTab->add();
    }

    /**
     * @param string $serviceName
     *
     * @return mixed
     */
    public function getService($serviceName)
    {
        return $this->serviceContainer->getService($serviceName);
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPs_microsoftModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(), // Add values for your inputs
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$this->getConfigForm()]);
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'PS_MICROSOFT_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => [
                            [
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled'),
                            ],
                            [
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                            ],
                        ],
                    ],
                    [
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid email address'),
                        'name' => 'PS_MICROSOFT_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ],
                    [
                        'type' => 'password',
                        'name' => 'PS_MICROSOFT_ACCOUNT_PASSWORD',
                        'label' => $this->l('Password'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return [
            'PS_MICROSOFT_LIVE_MODE' => Configuration::get('PS_MICROSOFT_LIVE_MODE', true),
            'PS_MICROSOFT_ACCOUNT_EMAIL' => Configuration::get('PS_MICROSOFT_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            'PS_MICROSOFT_ACCOUNT_PASSWORD' => Configuration::get('PS_MICROSOFT_ACCOUNT_PASSWORD', null),
        ];
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    protected function curl_post_https($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        $headers = [
            'Content-Type: application/json',
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($curl);
        curl_close($curl);

        return $tmpInfo;
    }

    private function getTTabs()
    {
        return [
            [
                'className' => 'Marketing',
                'parent' => 'IMPROVE',
                'name' => 'Marketing',
                'module' => '',
                'active' => true,
                'icon' => 'campaign',
            ],
            [
                'className' => 'AdminPsMicrosoftModule',
                'parent' => 'Marketing',
                'name' => 'Microsoft',
                'module' => $this->name,
                'active' => true,
                'icon' => '',
            ],
            [
                'className' => 'AdminAjaxPsMicrosoft',
                'parent' => -1,
                'name' => $this->name,
                'module' => $this->name,
                'active' => true,
                'icon' => '',
            ],
        ];
    }
}
