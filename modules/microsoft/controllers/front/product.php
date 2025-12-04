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

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PrestaShop\Module\Microsoft\Config\Config;
use PrestaShop\Module\Microsoft\Repository\CurrencyRepository;
use PrestaShop\Module\Microsoft\Repository\LanguageRepository;
use PrestaShop\Module\Microsoft\Repository\ProductRepository;
use PrestaShop\Module\Microsoft\Repository\StoresRepository;

if (!defined('_PS_VERSION_')) {
    exit;
}

class MicrosoftProductModuleFrontController extends ModuleFrontController
{
    public $module;

    public $auth = false;

    public $ssl = false;

    public function postProcess()
    {
        $this->verifyToken();

        $productRepository = $this->module->getService(ProductRepository::class);
        $currencyRepository = $this->module->getService(CurrencyRepository::class);
        $storesRepository = $this->module->getService(StoresRepository::class);
        $languageRepository = $this->module->getService(LanguageRepository::class);

        $res = [];

        switch (Tools::getValue('action')) {
            case 'ProductList':
                $res = $productRepository->getProductList(Tools::getValue('value'));

                break;

            case 'ProductInfoByPag':
                $res = $productRepository->getProductInfoByPag(
                    Tools::getValue('shopId'),
                    Tools::getValue('startId'),
                    Tools::getValue('threshold') ? Tools::getValue('threshold') : 500
                );

                break;

            case 'ProductInfoById':
                $res = $productRepository->getProductInfoById(Tools::getValue('shopId'), Tools::getValue('productId'));

                break;

            case 'Currency':
                $res['currency'] = $currencyRepository->getDefaultCurrencyByShopId(Tools::getValue('shopId'));

                break;

            case 'shopDomain':
                $res['url'] = Tools::getShopDomain(false);

                break;

            case 'DownloadUetTag':
                $this->downloadUetTag(Tools::getValue('shopId'));

                break;

            case 'CheckUetTag':
                $res['Msg'] = $this->checkUetTag(Tools::getValue('shopId'));

                break;

            default:
                $res = [];

                break;
        }

        $this->exitWithResponse($res);
    }

    protected function exitWithResponse(array $response)
    {
        $httpCode = isset($response['httpCode']) ? (int) $response['httpCode'] : 200;

        $this->dieWithResponse($response, $httpCode);
    }

    private function downloadUetTag($shopId)
    {
        try {
            $inputs = json_decode(Tools::file_get_contents('php://input'), true);
            $path = $this->module->getLocalPath() . Config::PS_MICROSOFT_UET_TAG_PATH($shopId);
            $file = fopen($path, 'w');
            fwrite($file, $inputs['script']);
            fclose($file);

            $file = fopen($path, 'r');
            $code = fread($file, filesize($path));
            fclose($file);

            if (!file_exists($path) || 0 == filesize($path)) {
                $this->dieWithResponse(['Msg' => 'Write file failed.'], 500);
            } elseif ($code !== $inputs['script']) {
                $this->dieWithResponse(['Msg' => 'Error'], 500);
            } else {
                $this->dieWithResponse(['Msg' => 'Success'], 200);
            }
        } catch (Exception $e) {
            $this->dieWithResponse(['Msg' => $e->getMessage()], 500);
        }
    }

    private function checkUetTag($shopId)
    {
        $path = $this->module->getLocalPath() . Config::PS_MICROSOFT_UET_TAG_PATH($shopId);
        if (!file_exists($path) || 0 == filesize($path)) {
            return false;
        }

        $md5 = Tools::getValue('md5');
        if ($md5 == md5_file($path)) {
            return true;
        }

        return false;
    }

    private function verifyToken()
    {
        $headers = getallheaders();
        if (!isset($headers['Token']) || false == Tools::getValue('shopId')) {
            $this->dieWithResponse(['Msg' => 'Permission denied.'], 401);
        }

        try {
            $publicKey = Config::PS_MICROSOFT_PUBLIC_KEY(Tools::getValue('shopId'));
            if (false == $publicKey) {
                $this->dieWithResponse(['Msg' => 'Public key not exist.'], 401);
            }

            $publicKey = '-----BEGIN PUBLIC KEY-----' . PHP_EOL . $publicKey . PHP_EOL . '-----END PUBLIC KEY-----';
            $jwt = $headers['Token'];
            $decoded = (array) JWT::decode($jwt, new Key($publicKey, 'RS256'));
            if ((Config::PS_MICROSOFT_ISS != $decoded['iss']) && (Config::PS_MICROSOFT_ISS_SI != $decoded['iss'])) {
                $this->dieWithResponse([], 401);
            }
        } catch (Exception $e) {
            $this->dieWithResponse(['Msg' => $e->getMessage()], 401);
        }
    }

    private function dieWithResponse(array $response, $code)
    {
        $httpStatusText = "HTTP/1.1 {$code}";

        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Content-Type: application/json;charset=utf-8');
        header($httpStatusText);

        echo json_encode($response, JSON_UNESCAPED_SLASHES);

        exit;
    }
}
