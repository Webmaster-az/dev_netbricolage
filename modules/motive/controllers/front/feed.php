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

use Motive\Prestashop\Builder\FeedBuilder;
use Motive\Prestashop\Config;
use Motive\Prestashop\MotiveApiController;
use Motive\Prestashop\PriceModifiers;
use Motive\Prestashop\TimeLimit;

if (!defined('_PS_VERSION_')) {
    exit;
}

class MotiveFeedModuleFrontController extends MotiveApiController
{
    /**
     * Handle request with parameters:
     *
     * - from_id:  Product ID (not included) from which the feed is generated.
     * - max_time: Max allowed execution time.
     */
    public function displayAjax()
    {
        $this->storePriceRates();
        $configuredTimeLimit = (int) Config::getTimeLimit();
        $maxTime = (int) Tools::getValue('max_time', $configuredTimeLimit);
        $fromIdProduct = (int) Tools::getValue('from_id', 0);
        $feed = $this->getBuilder()->build($fromIdProduct);
        $jsonOptions = version_compare(PHP_VERSION, '7.2.0', '>=') ? JSON_INVALID_UTF8_IGNORE : JSON_PARTIAL_OUTPUT_ON_ERROR;

        // Non paginated response
        if ($configuredTimeLimit < 0 || $maxTime <= 0) {
            $feed->jsonStream($jsonOptions);

            return;
        }

        // Response paginated by runtime
        $timeLimit = new TimeLimit($maxTime, $maxTime, Tools::isSubmit('debug'));
        $lastId = null;
        $feed->jsonStream(
            $jsonOptions,
            function ($id) use ($timeLimit, &$lastId) {
                $idProduct = (int) $id; // Id can have variant separator '-'
                // Split feed between variants is not supported
                if ($lastId === $idProduct) {
                    return false;
                }
                $lastId = $idProduct;
                if ($timeLimit->remainingTime() > 5) {
                    return false;
                }

                return MotiveApiController::getUrl('feed', ['from_id' => $lastId]);
            }
        );
    }

    /**
     * @return FeedBuilder instance
     */
    public function getBuilder()
    {
        return new FeedBuilder($this->context);
    }

    /**
     * Store the min and max tax rate and other price modifiers applied to current address.
     */
    public function storePriceRates()
    {
        if (empty($_SERVER['HTTP_USER_AGENT']) || stripos($_SERVER['HTTP_USER_AGENT'], 'motive') === false) {
            return;
        }

        $idLang = $this->context->language->id;
        Config::setLastSyncInfo([$idLang => json_encode(PriceModifiers::build())]);
    }
}
