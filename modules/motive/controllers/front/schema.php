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

use Motive\Prestashop\Builder\SchemaBuilder;
use Motive\Prestashop\MotiveApiController;

if (!defined('_PS_VERSION_')) {
    exit;
}

class MotiveSchemaModuleFrontController extends MotiveApiController
{
    /**
     * Handle request with parameters:
     *
     * - shop:     Shop's ID or Shop's name
     * - language: Language's ID or Language's ISO code, like "es" or "en"
     * - currency: Currency's ID or Currency's ISO code, like "EUR" or "GBP"
     */
    public function displayAjax()
    {
        $this->ajaxRenderJson($this->getBuilder()->build());
    }

    /**
     * @return SchemaBuilder instance
     */
    public function getBuilder()
    {
        return new SchemaBuilder($this->context);
    }
}
