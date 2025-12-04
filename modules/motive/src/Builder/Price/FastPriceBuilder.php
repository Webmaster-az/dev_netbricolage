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

namespace Motive\Prestashop\Builder\Price;

use Motive\Prestashop\Model\Price;

if (!defined('_PS_VERSION_')) {
    exit;
}

class FastPriceBuilder extends BasePriceBuilder
{
    /** @var int */
    protected $id_customer;

    /** @var int */
    protected $id_group;

    /** @var int */
    protected $id_currency;

    /** @var int */
    protected $id_country;

    /** @var int */
    protected $id_state;

    /** @var int */
    protected $zipcode;

    /**
     * FastPriceBuilder constructor.
     *
     * @param \Context $context
     * @param int $decimals
     * @param int $id_customer
     * @param int $id_group
     * @param int $id_address
     * @param int $id_country
     * @param int $id_state
     * @param string $zipcode
     */
    public function __construct(\Context $context, $decimals = 6, $id_customer = 0, $id_group = 0, $id_address = 0, $id_country = 0, $id_state = 0, $zipcode = 0)
    {
        parent::__construct($context, $decimals);

        if ($id_address || !$id_country || !$id_state || !$zipcode) {
            $address = \Address::initialize($id_address, true);
            $id_country = (int) $address->id_country;
            $id_state = (int) $address->id_state;
            $zipcode = $address->postcode;
        }

        if (!$id_customer && \Validate::isLoadedObject($context->customer)) {
            $id_customer = $context->customer->id;
        }

        if ($id_customer) {
            $id_group = \Customer::getDefaultGroupId((int) $id_customer);
        }
        if (!$id_group) {
            $id_group = (int) \Group::getCurrent()->id;
        }

        $this->id_customer = $id_customer;
        $this->id_group = $id_group;
        $this->id_currency = \Validate::isLoadedObject($context->currency) ? (int) $context->currency->id : (int) \Configuration::get('PS_CURRENCY_DEFAULT');
        $this->id_country = $id_country;
        $this->id_state = $id_state;
        $this->zipcode = $zipcode;
    }

    public function compute($productRow, $idProductAttribute = null)
    {
        $idProduct = $productRow['p_id'];
        if (!$idProductAttribute) {
            $idProductAttribute = (int) $productRow['p_default_attribute'];
        }

        $specific_price_output = null;
        $regular = \Product::priceCalculation(
            $this->context->shop->id,
            $idProduct,
            $idProductAttribute,
            $this->id_country,
            $this->id_state,
            $this->zipcode,
            $this->id_currency,
            $this->id_group,
            /* $quantity */ 1,
            $this->useTax,
            $this->decimals,
            /* $only_reduc */ false,
            /* $usereduc */ false,
            /* $with_ecotax */ true,
            $specific_price_output,
            /* $use_group_reduction */ true,
            $this->id_customer
        );
        $on_sale = !$regular ? $regular : \Product::priceCalculation(
            $this->context->shop->id,
            $idProduct,
            $idProductAttribute,
            $this->id_country,
            $this->id_state,
            $this->zipcode,
            $this->id_currency,
            $this->id_group,
            /* $quantity */ 1,
            $this->useTax,
            $this->decimals,
            /* $only_reduc */ false,
            /* $usereduc */ true, /* the only change */
            /* $with_ecotax */ true,
            $specific_price_output,
            /* $use_group_reduction */ true,
            $this->id_customer
        );

        return Price::build($regular, $on_sale);
    }
}
