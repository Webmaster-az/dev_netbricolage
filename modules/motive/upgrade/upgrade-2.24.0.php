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

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_24_0($module)
{
    $savedShopContext = $module->setAllShopContext();
    Config::import([
        'motive_x_url' => Config::DEFINITION['motive_x_url']['default'],
        'interoperability_url' => Config::DEFINITION['interoperability_url']['default'],
        'playboard_url' => Config::DEFINITION['playboard_url']['default'],
        'tagging_base_url' => Config::DEFINITION['tagging_base_url']['default'],
    ], true);
    $module->restoreShopContext($savedShopContext);

    return true;
}
