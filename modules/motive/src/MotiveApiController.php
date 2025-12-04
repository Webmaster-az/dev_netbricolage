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

namespace Motive\Prestashop;

if (!defined('_PS_VERSION_')) {
    exit;
}

abstract class MotiveApiController extends \ModuleFrontController
{
    /**
     * Initializes endpoint controller: check access, sets class properties, etc.
     *
     * @return void
     *
     * @throws \PrestaShopException
     */
    public function init()
    {
        if (\Tools::isSubmit('debug')) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(0);
            ini_set('display_errors', '0');
        }

        // PrestaShop does not support json responses in case of authentication error so I must check it here.
        if (!$this->checkAccess()) {
            $this->ajaxRenderJson(new ErrorResponse('Unauthorized. Invalid or missed security token.'), 401);

            return;
        }

        // This is necessary to avoid rendering html templates, as we want a json response
        $this->ajax = true;
        $this->content_only = true;

        parent::init();
    }

    /**
     * Check if the controller is available for the current user/visitor.
     *
     * @return bool
     *
     * @see Controller::checkAccess()
     */
    public function checkAccess()
    {
        // Check security token in X-MOTIVE-TOKEN header
        return Config::checkToken();
    }

    /**
     * Handles requests for unknown endpoints
     */
    public function displayAjax()
    {
        $this->ajaxRenderJson(new ErrorResponse('Invalid endpoint'), 400);
    }

    /**
     * Avoids displays maintenance page for this module endpoints if shop is closed.
     */
    protected function displayMaintenancePage()
    {
    }

    /**
     * Output a json response and terminate the current script
     *
     * @param mixed $content - json-serializable data
     * @param int $responseCode Optional HTTP response code
     *
     * @return never
     */
    protected function ajaxRenderJson($content, $responseCode = 200)
    {
        if ($responseCode != 200) {
            http_response_code($responseCode);
        }

        headers_sent() || header('Content-Type:application/json; charset=utf-8');
        $this->ajaxDie(json_encode($content));
    }

    public function getJsonBody()
    {
        return json_decode(\Tools::file_get_contents('php://input'), true);
    }

    public static function getUrl($controller, array $params = [], $idLang = null)
    {
        $link = \Context::getContext()->link;
        if (Config::getForceUnfriendlyUrls()) {
            $params['fc'] = 'module';
            $params['module'] = 'motive';
            $params['controller'] = $controller;
            if ($idLang) {
                $params['id_lang'] = $idLang;
            }

            return $link->getBaseLink(null, null, false) . 'index.php?' . http_build_query($params, '', '&');
        }

        return $link->getModuleLink('motive', $controller, $params, null, $idLang);
    }
}
