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

namespace PrestaShop\Module\Microsoft\Repository;

if (!defined('_PS_VERSION_')) {
    exit;
}

class CountryRepository
{
    private $context;

    private $defaultCountry;

    public function __construct(\Context $context)
    {
        $this->context = $context;
    }

    public function getShopDefaultCountry()
    {
        return [
            'name' => \Country::getNameById($this->context->language->id, \Configuration::get('PS_COUNTRY_DEFAULT')),
            'iso_code' => \Country::getIsoById(\Configuration::get('PS_COUNTRY_DEFAULT')),
        ];
    }
}
