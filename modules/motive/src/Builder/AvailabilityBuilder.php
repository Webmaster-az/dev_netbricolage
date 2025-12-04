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

namespace Motive\Prestashop\Builder;

use Motive\Prestashop\Model\Availability;
use Motive\Prestashop\OutOfStockBehaviour;

if (!defined('_PS_VERSION_')) {
    exit;
}

class AvailabilityBuilder
{
    protected $isStockManagementActive;
    protected $defaultOutOfStockBehaviour;
    protected $stockAvailableQuery;

    public function __construct(\Context $context)
    {
        $this->isStockManagementActive = (bool) \Configuration::get('PS_STOCK_MANAGEMENT');
        $this->defaultOutOfStockBehaviour = (int) \Configuration::get('PS_ORDER_OUT_OF_STOCK');

        // If quantities are shared between shops of the group
        $shopGroup = $context->shop->getGroup();
        if ($shopGroup->share_stock) {
            $idShop = 0;
            $idShopGroup = (int) $shopGroup->id;
        } else {
            $idShop = (int) $context->shop->id;
            $idShopGroup = 0;
        }
        $this->stockAvailableQuery = 'LEFT JOIN ' . _DB_PREFIX_ . "stock_available AS sa 
            ON sa.id_product = [id_product]
            AND sa.id_product_attribute = [id_product_attribute]
            AND sa.id_shop_group = $idShopGroup
            AND sa.id_shop = $idShop";
    }

    /**
     * @param string $productJoinOn product id join colum
     * @param string $attributeJoinOn product attribute id join colum
     *
     * @return string sql join
     */
    public function stockAvailableQuery($productJoinOn, $attributeJoinOn = '0')
    {
        $search = ['[id_product]', '[id_product_attribute]'];
        $replace = [$productJoinOn, $attributeJoinOn];

        return str_replace($search, $replace, $this->stockAvailableQuery);
    }

    /**
     * @param int|float $stock
     *
     * @return Availability
     */
    public function buildFrom($stock, $availableForOrder, $outOfStockBehaviour)
    {
        return Availability::build($stock, $this->allowOrder($stock, $availableForOrder, $outOfStockBehaviour));
    }

    /**
     * @param int $stock
     * @param bool $availableForOrder
     * @param int $outOfStockBehaviour
     *                                 - O Deny orders
     *                                 - 1 Allow orders
     *                                 - 2 Use global setting
     *
     * @return bool
     */
    public function allowOrder($stock, $availableForOrder, $outOfStockBehaviour)
    {
        if (!$availableForOrder) {
            return false;
        }

        if ($stock > 0) {
            return true;
        }

        if (!$this->isStockManagementActive) {
            return true;
        }

        if ($outOfStockBehaviour === OutOfStockBehaviour::OUT_OF_STOCK_AVAILABLE
            || (
                $outOfStockBehaviour === OutOfStockBehaviour::OUT_OF_STOCK_DEFAULT
                && $this->defaultOutOfStockBehaviour === OutOfStockBehaviour::OUT_OF_STOCK_AVAILABLE)) {
            return true;
        }

        return false;
    }
}
