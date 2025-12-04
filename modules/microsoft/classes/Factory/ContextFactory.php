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

namespace PrestaShop\Module\Microsoft\Factory;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ContextFactory
{
    public static function getContext()
    {
        return \Context::getContext();
    }

    public static function getLanguage()
    {
        if (null !== \Context::getContext()->language) {
            return \Context::getContext()->language;
        }

        return new \Language((int) \Configuration::get('PS_LANG_DEFAULT'));
    }

    public static function getCurrency()
    {
        if (null !== \Context::getContext()->currency) {
            return \Context::getContext()->currency;
        }

        return new \Currency((int) \Configuration::get('PS_CURRENCY_DEFAULT'));
    }

    public static function getSmarty()
    {
        return \Context::getContext()->smarty;
    }

    public static function getShop()
    {
        return \Context::getContext()->shop;
    }

    public static function getController()
    {
        return \Context::getContext()->controller;
    }

    public static function getCookie()
    {
        return \Context::getContext()->cookie;
    }

    public static function getLink()
    {
        return \Context::getContext()->link;
    }

    public static function getCountry()
    {
        return \Context::getContext()->country;
    }
}
