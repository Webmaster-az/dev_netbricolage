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

class CurrencyRepository
{
    /**
     * @var \Currency
     */
    private $currency;

    public function __construct(\Currency $currency)
    {
        $this->currency = $currency;
    }

    public function getDefaultCurrency()
    {
        return $this->currency->iso_code;
    }

    public function getCurrencies($shopId, $languageId)
    {
        $sql = new \DbQuery();

        $sql->select('c.id_currency, c.iso_code, c.numeric_iso_code, c.conversion_rate, cl.name, cl.symbol');

        $sql->from('currency_shop', 'cs');

        $sql->where('cs.id_shop =' . (int) $shopId);

        $sql->innerJoin('currency', 'c', 'c.id_currency = cs.id_currency and c.active = 1');

        $sql->leftJoin('currency_lang', 'cl', 'cl.id_currency = c.id_currency and cl.id_lang =' . (int) $languageId);

        return \Db::getInstance()->executeS($sql);
    }

    public function getDefaultCurrencyByShopId($shopId)
    {
        $sql = new \DbQuery();
        $sql->select('value');
        $sql->from('configuration');
        $sql->where('id_shop=' . (int) $shopId . ' and name = "PS_CURRENCY_DEFAULT"');

        $ans = \Db::getInstance()->executeS($sql);

        if (0 == count($ans)) {
            return new \Currency(\Configuration::get('PS_CURRENCY_DEFAULT'));
        }

        return new \Currency($ans[0]['value']);
    }

    public function getShopCurrencySymbol()
    {
        return $this->currency->symbol;
    }
}
