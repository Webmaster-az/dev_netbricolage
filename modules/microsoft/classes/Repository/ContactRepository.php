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

class ContactRepository
{
    private $context;

    public function __construct()
    {
    }

    public function getContact()
    {
        $ans['SHOP_NAME'] = $this->getConfig('PS_SHOP_NAME');
        $ans['SHOP_EMAIL'] = $this->getConfig('PS_SHOP_EMAIL');
        $ans['SHOP_DETAILS'] = $this->getConfig('PS_SHOP_DETAILS');
        $ans['SHOP_ADDR1'] = $this->getConfig('PS_SHOP_ADDR1');
        $ans['SHOP_ADDR2'] = $this->getConfig('PS_SHOP_ADDR2');
        $ans['SHOP_CODE'] = $this->getConfig('PS_SHOP_CODE');
        $ans['SHOP_CITY'] = $this->getConfig('PS_SHOP_CITY');
        $ans['SHOP_COUNTRY_ID'] = $this->getConfig('PS_SHOP_COUNTRY_ID');
        $ans['SHOP_COUNTRY'] = $this->getConfig('PS_SHOP_COUNTRY');
        $ans['SHOP_PHONE'] = $this->getConfig('PS_SHOP_PHONE');
        $ans['SHOP_FAX'] = $this->getConfig('PS_SHOP_FAX');
        $ans['SHOP_STATE'] = $this->getConfig('PS_SHOP_STATE');

        return $ans;
    }

    private function getConfig($field)
    {
        return false === \Configuration::get($field) ? null : \Configuration::get($field);
    }
}
