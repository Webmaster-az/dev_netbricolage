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
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_11_0($module)
{
    if (Motive\Prestashop\Config::getLayerIsolated() !== '0') {
        Motive\Prestashop\Config::setLayerIsolated('1');
    }

    if (Motive\Prestashop\Config::getTimeLimit() == '7200') {
        Motive\Prestashop\Config::setTimeLimit('30');
    }

    if ((int) Motive\Prestashop\Config::getFeaturesLimit() <= 0) {
        Motive\Prestashop\Config::setFeaturesLimit('100');
    }

    Configuration::deleteByName('MOTIVE_LAYER');

    Motive\Prestashop\Config::setShopperPrices('1');

    return true;
}
