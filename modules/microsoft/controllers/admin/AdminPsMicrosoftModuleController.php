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
use PrestaShop\Module\Microsoft\Repository\AttributesRepository;
use PrestaShop\Module\Microsoft\Repository\ContactRepository;
use PrestaShop\Module\Microsoft\Repository\CountryRepository;
use PrestaShop\Module\Microsoft\Repository\CurrencyRepository;
use PrestaShop\Module\Microsoft\Repository\EmployeeRepository;
use PrestaShop\Module\Microsoft\Repository\LanguageRepository;
use PrestaShop\Module\Microsoft\Repository\ProductRepository;
use PrestaShop\Module\Microsoft\Repository\StoresRepository;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;

if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminPsMicrosoftModuleController extends ModuleAdminController
{
    public $module;

    private $currencyRepository;

    private $languageRepository;

    private $contactRepository;

    private $storesRepository;

    private $productRepository;

    private $attributesRepository;

    private $countryRepository;

    private $employeeRepository;

    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = false;
        $this->currencyRepository = $this->module->getService(CurrencyRepository::class);
        $this->languageRepository = $this->module->getService(LanguageRepository::class);
        $this->contactRepository = $this->module->getService(ContactRepository::class);
        $this->storesRepository = $this->module->getService(StoresRepository::class);
        $this->productRepository = $this->module->getService(ProductRepository::class);
        $this->attributesRepository = $this->module->getService(AttributesRepository::class);
        $this->countryRepository = $this->module->getService(CountryRepository::class);
        $this->employeeRepository = $this->module->getService(EmployeeRepository::class);
    }

    public function initContent()
    {
        $this->context->smarty->assign([
            'pathApp' => $this->module->getPathUri() . 'views/js/app.js',
            'chunkVendor' => $this->module->getPathUri() . 'views/js/chunk-vendors.js',
        ]);

        try {
            $psAccountsService = $this->module->getService('ps_accounts.facade')->getPsAccountsService();
            $shopIdPsAccounts = $psAccountsService->getShopUuidV4();
            $tokenPsAccounts = $psAccountsService->getOrRefreshToken();
        } catch (Exception $e) {
            $shopIdPsAccounts = null;
            $tokenPsAccounts = null;
        }

      Media::addJsDef([
        'i18nSettings' => [
            'isoCode' => $this->context->language->iso_code,
            'languageLocale' => $this->context->language->language_code,
        ],
          'contextPsAccounts' => (object) $this->module->getService('ps_accounts.facade')
              ->getPsAccountsPresenter()
              ->present($this->module->name),
          'shopIdPsAccounts' => $shopIdPsAccounts,
          'tokenPsAccounts' => $tokenPsAccounts,
          'shopUrlForMicrosoft' => $this->context->link->getBaseLink($this->context->shop->id),
          'currency' => $this->currencyRepository->getDefaultCurrency(),
          'currencies' => $this->currencyRepository->getCurrencies($this->context->shop->id, $this->context->language->id),
          'language' => $this->languageRepository->getDefaultLanguage($this->context->shop->id),
          'languages' => $this->languageRepository->getLanguages($this->context->shop->id),
          'currencySign' => $this->currencyRepository->getShopCurrencySymbol(),
          'contact' => $this->contactRepository->getContact(),
          'stores' => $this->storesRepository->getStore(),
          'product' => $this->productRepository->getProductSample($this->context->shop->id, 3),
          'attributes' => $this->attributesRepository->getAllAttributes(),
          'defaultCountryCode' => $this->countryRepository->getShopDefaultCountry()['iso_code'],
          'defaultCountry' => $this->countryRepository->getShopDefaultCountry()['name'],
          'employeeName' => $this->employeeRepository->getName(),
          '_token' => $this->getToken(),
          'adminAjaxUrl' => $this->context->link->getAdminLink(
              'AdminAjaxPsMicrosoft',
              true,
              [],
              [
                  'ajax' => 1,
              ]
          ),
      ]);

        $this->content = $this->context->smarty->fetch($this->module->getLocalPath() . '/views/templates/admin/app.tpl');
        parent::initContent();
    }

    protected function getToken()
    {
        $router = SymfonyContainer::getInstance()->get('router');
        $str = $router->generate('admin_module_manage_action', [
            'action' => 'install',
            'module_name' => 'microsoft',
        ]);

        return substr($str, strlen($str) - 44, 44);
    }
}
