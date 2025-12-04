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

use Motive\Prestashop\Builder\InfoBuilder;
use Motive\Prestashop\MotiveApiController;

if (!defined('_PS_VERSION_')) {
    exit;
}

class MotiveInfoModuleFrontController extends MotiveApiController
{
    /**
     * Handle request
     */
    public function displayAjax()
    {
        $this->ajaxRenderJson(InfoBuilder::build($this->context));
    }

    /**
     * Output urls only
     * Url: /module/motive/info?action=urls
     */
    public function displayAjaxUrls()
    {
        $this->ajaxRenderJson(InfoBuilder::getUrls($this->context));
    }

    /**
     * Output db table sizes
     * Url: /module/motive/info?action=dbtables
     */
    public function displayAjaxDbtables()
    {
        $this->ajaxRenderJson(InfoBuilder::getDbtables());
    }
}
