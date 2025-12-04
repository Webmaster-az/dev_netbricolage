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

use Motive\Prestashop\Config;
use Motive\Prestashop\ErrorResponse;
use Motive\Prestashop\MotiveApiController;

if (!defined('_PS_VERSION_')) {
    exit;
}

class MotiveConfigModuleFrontController extends MotiveApiController
{
    /**
     * Handle request
     */
    public function handleRequest($onlyIfUnset = false)
    {
        $method = Tools::strtoupper($_SERVER['REQUEST_METHOD']);
        if ($method === 'GET') {
            $this->ajaxRenderJson(Config::export(Tools::isSubmit('all'), Tools::isSubmit('raw')));

            return;
        }

        if ($method !== 'POST') {
            $this->ajaxRenderJson(new ErrorResponse('Bad request method.'), 400);

            return;
        }

        $data = $this->getJsonBody();
        if (!is_array($data)) {
            $this->ajaxRenderJson(new ErrorResponse('Bad request body.'), 400);

            return;
        }

        $errors = Config::import($data, $onlyIfUnset);
        if (!empty($errors)) {
            $this->ajaxRenderJson(new ErrorResponse($errors), 400);
        }
    }

    /**
     * Default config endpoint
     * Url: /module/motive/config
     */
    public function displayAjax()
    {
        $this->handleRequest(Tools::isSubmit('if-unset'));
    }
}
