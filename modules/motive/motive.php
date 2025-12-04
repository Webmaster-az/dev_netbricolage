<?php
/**
 * (C) 2023 Motive Commerce Search Corp S.L. <info@motive.co>
 *
 * This file is part of Motive Commerce Search.
 *
 * This file is licensed to you under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author Motive (motive.co)
 * @copyright (C) 2023 Motive Commerce Search Corp S.L. <info@motive.co>
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

require_once dirname(__FILE__) . '/vendor/autoload.php';

if (!defined('_PS_VERSION_')) {
    exit;
}

class Motive extends Module
{
    const VERSION = '2.30.2';

    private $isConfigured;

    public function __construct()
    {
        $this->name = 'motive';
        $this->tab = 'search_filter';
        $this->version = '2.30.2';
        $this->author = 'Motive (https://motive.co)';
        $this->module_key = '9ec6d02bae3142aeda55f23cfa546f6a';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6.1',
            'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Motive Commerce Search');
        $this->description = $this->l('Add Motive to your shop for an intuitive & beautiful search.');
        $this->confirmUninstall = $this->l('Are you sure? This will not cancel your account in Motive service');

        if (!$this->isConfigured()) {
            $this->warning = $this->l('The module is not configured.');
        } elseif ($this->hasNewVersion()) {
            $this->warning = $this->l('A new version of the module is available, update now to get full functionality.');
        }
    }

    public function install()
    {
        // Install for all shops
        $savedShopContext = $this->setAllShopContext();

        if (empty(Motive\Prestashop\Config::getToken())) {
            Motive\Prestashop\Config::setToken($this->randomToken());
        }
        if ($this->inVersion('>=', '1.7.0.0')) {
            Motive\Prestashop\Config::setImageSize(ImageType::getFormattedName('home'));
        }

        // Init ENGINE_ID to avoid race condition when is set in parallel.
        foreach (Shop::getShops(false, null, true) as $shopId) {
            $key = Motive\Prestashop\Config::ENGINE_ID;
            if (Configuration::hasKey($key, null, null, (int) $shopId) === false) {
                $value = array_fill_keys(Language::getIDs(false), '');
                Configuration::updateValue($key, $value, false, null, (int) $shopId);
            }
        }

        $result = parent::install()
            && $this->registerHook('displayHeader')
            && $this->registerHook($this->inVersion('>=', '1.7.0.0') ? 'actionCartUpdateQuantityBefore' : 'actionBeforeCartUpdateQty')
            && $this->registerHook('actionFrontControllerSetMedia')
            && $this->registerHook('actionObjectLanguageAddAfter');

        $this->updatePosition(Hook::getIdByName('displayHeader'), false, 1);
        $this->updatePosition(Hook::getIdByName('actionFrontControllerSetMedia'), false, 1);

        // Restore previous shop context
        $this->restoreShopContext($savedShopContext);

        return $result;
    }

    public function installMeta()
    {
        $meta = new Motive\Prestashop\ModuleMeta($this, 'motive-');
        $meta->install('check');
        $meta->install('info');
        $meta->install('config');
        $meta->install('schema');
        $meta->install('feed');

        return true;
    }

    public function uninstall()
    {
        $this->uninstallMeta();

        return parent::uninstall();
    }

    public function uninstallMeta()
    {
        $meta = new Motive\Prestashop\ModuleMeta($this, 'motive-');
        $meta->uninstall('check');
        $meta->uninstall('info');
        $meta->uninstall('config');
        $meta->uninstall('schema');
        $meta->uninstall('feed');

        return true;
    }

    public function setAllShopContext()
    {
        $shopContextType = Shop::getContext();
        if ($shopContextType === Shop::CONTEXT_SHOP) {
            $shopContextId = Shop::getContextShopID();
        } elseif ($shopContextType === Shop::CONTEXT_GROUP) {
            $shopContextId = Shop::getContextShopGroupID();
        } else {
            $shopContextId = null;
        }
        Shop::setContext(Shop::CONTEXT_ALL);

        return [$shopContextType, $shopContextId];
    }

    public function restoreShopContext(array $ctx)
    {
        Shop::setContext($ctx[0], $ctx[1]);
    }

    public function getContent()
    {
        if (Tools::isSubmit('regenerate-token')) {
            $token = $this->randomToken();
            Motive\Prestashop\Config::setToken($token);
            $this->ajaxResponse($token);

            return;
        }

        Media::addJsDef([
            'motive_configUrl' => $this->context->link->getAdminLink('AdminModules', true)
                . "&configure={$this->name}&module_name={$this->name}",
        ]);

        $this->context->smarty->assign([
            'token' => Motive\Prestashop\Config::getToken(),
            'locale' => $this->context->language->iso_code,
            'version' => $this->version,
            'isConfigured' => !empty(Motive\Prestashop\Config::getTriggerSelector()),
            'isEnabled' => !empty(Motive\Prestashop\Config::getEngineId($this->context->language->id)),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    public function ajaxResponse($value)
    {
        exit($value);
    }

    public function hookDisplayHeader(array $params)
    {
        $isConfigured = $this->isConfigured();
        $loadInteroperabilityScript = $this->shouldLoadInteroperabilityScript();
        $loadJsHere = !Motive\Prestashop\Config::getAddJsUsingPrestashopFunctions();
        $renderTpl = false;

        if ($isConfigured) {
            $renderTpl = true;
            $this->context->smarty->assign([
                'motive_x_url' => Motive\Prestashop\Config::getMotiveXUrl(),
            ]);
        }

        if ($loadJsHere) {
            if ($isConfigured) {
                $this->context->smarty->assign([
                    'motive_front' => $this->getFrontScriptUrl(),
                    'motive_config' => json_encode($this->getVariables()),
                ]);
            }

            if ($loadInteroperabilityScript) {
                $renderTpl = true;
                $this->context->smarty->assign('interoperability_js', Motive\Prestashop\Config::getInteroperabilityUrl());
            }
        }

        return $renderTpl ? $this->display(__FILE__, 'header.tpl') : '';
    }

    public function hookActionObjectLanguageAddAfter(array $params)
    {
        $lang = $params['object'];
        Configuration::updateValue(Motive\Prestashop\Config::ENGINE_ID, [$lang->id => '']);
    }

    /**
     * This hook is called only since PrestaShop 1.7.0.0
     */
    public function hookActionCartUpdateQuantityBefore(array $params)
    {
        try {
            Motive\Prestashop\Tagging::addToCart($params);
        } catch (Throwable $t) {
            // Silent Errors for PHP >=7.0
        } catch (Exception $e) {
            // Silent Errors for PHP <=5.6
        }
    }

    /**
     * This hook is called only in PrestaShop 1.6.1 to 1.6.1.24
     * Deprecated since PrestaShop 1.7.0.0
     */
    public function hookActionBeforeCartUpdateQty(array $params)
    {
        $this->hookActionCartUpdateQuantityBefore($params);
    }

    public function hookActionFrontControllerSetMedia($params)
    {
        if (!Motive\Prestashop\Config::getAddJsUsingPrestashopFunctions()) {
            return;
        }

        if ($this->isConfigured()) {
            Media::addJsDef([$this->name => $this->getVariables()]);
            $this->includeScript('Front', $this->getFrontScriptUrl());
        }

        if ($this->shouldLoadInteroperabilityScript()) {
            $this->includeScript('Interop', Motive\Prestashop\Config::getInteroperabilityUrl());
        }
    }

    protected function getFrontScriptUrl()
    {
        $frontLoaderUrl = Motive\Prestashop\Config::getFrontLoaderUrl();
        if (filter_var($frontLoaderUrl, FILTER_VALIDATE_URL)) {
            return $frontLoaderUrl;
        }

        $variant = $this->inVersion('>=', '1.7.0.0') ? '' : '16';

        return $this->getPathUri() . "views/js/front{$variant}.js?v={$this->version}";
    }

    protected function includeScript($id, $url)
    {
        if ($this->inVersion('>=', '1.7.0.0')) {
            $this->context->controller->registerJavascript($this->name . $id, $url, ['server' => 'remote']);
        } else {
            $this->context->controller->addJS($url, false);
        }
    }

    /**
     * Get module front variables
     * In Prestashop 1.7.5 could be hookActionFrontControllerSetVariables
     *
     * @return array
     */
    public function getVariables()
    {
        $language = $this->context->language;

        return [
            'initParams' => [
                'xEngineId' => Motive\Prestashop\Config::getEngineId($language->id),
                'lang' => empty($language->locale) ? $language->language_code : $language->locale,
                'currency' => $this->context->currency->iso_code,
                'triggerSelector' => Motive\Prestashop\Config::getTriggerSelector(),
                'isolated' => (bool) Motive\Prestashop\Config::getLayerIsolated(),
                'cartUrl' => $this->getCartURL(),
                'externalAddToCartTagging' => 'QUERY_PARAM' === Motive\Prestashop\Config::getTaggingAddtocart(),
            ],
            'options' => [
                'showPrices' => Motive\Prestashop\Builder\Price\BasePriceBuilder::shouldShowPrice(),
                'shopperPrices' => (bool) Motive\Prestashop\Config::getShopperPrices(),
                'priceRates' => Motive\Prestashop\PriceModifiers::getPriceTransformRates(),
            ],
            'endpoint' => Motive\Prestashop\MotiveApiController::getUrl('front'),
            'motive_x_url' => Motive\Prestashop\Config::getMotiveXUrl(),
        ];
    }

    /**
     * Get cart URL.
     *
     * @return string
     */
    public function getCartURL()
    {
        $controller = $this->inVersion('>=', '1.7.0.0') ? 'cart' : 'order';

        return $this->context->link->getPageLink($controller, true, null, ['action' => 'show']);
    }

    /**
     * Check if module is configured
     *
     * @return bool
     */
    public function isConfigured()
    {
        if ($this->isConfigured === null) {
            $this->isConfigured = !empty(Motive\Prestashop\Config::getEngineId($this->context->language->id))
                && !empty(Motive\Prestashop\Config::getTriggerSelector());
        }

        return $this->isConfigured;
    }

    /**
     * Check if interoperability script should be loaded.
     *
     * @return bool
     */
    private function shouldLoadInteroperabilityScript()
    {
        // To support old Safari versions, only check if header is defined
        if (array_key_exists('HTTP_SEC_FETCH_DEST', $_SERVER) && $_SERVER['HTTP_SEC_FETCH_DEST'] !== 'iframe') {
            return false;
        }

        if (empty($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] !== Motive\Prestashop\Config::getPlayboardUrl()) {
            return false;
        }

        return true;
    }

    /**
     * Generate random token
     *
     * @return string token
     */
    private function randomToken()
    {
        return Tools::passwdGen(32);
    }

    /**
     * Compare PrestaShop version
     *
     * @param string $operator version_compare operator
     * @param string $version
     *
     * @return bool
     */
    public function inVersion($operator, $version)
    {
        return version_compare(_PS_VERSION_, $version, $operator);
    }

    /**
     * Check if there is a new version of the module
     *
     * @return bool true if there is a new version available
     */
    public function hasNewVersion()
    {
        // Limit check to admin pages.
        if (!$this->context->controller || $this->context->controller->controller_type !== 'admin') {
            return false;
        }
        try {
            $versionInfo = json_decode(file_get_contents('https://assets.motive.co/prestashop/latest/version.json'));

            return version_compare($this->version, $versionInfo->latestVersion, '<');
        } catch (Exception $e) {
            return false;
        }
    }
}
