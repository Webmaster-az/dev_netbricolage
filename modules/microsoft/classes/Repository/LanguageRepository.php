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

class LanguageRepository
{
    private $context;

    public function __construct(\Context $context)
    {
        $this->context = $context;
    }

    public function getIsoById(int $id)
    {
        return \Language::getIsoById($id);
    }

    public function getLanguages($shopId)
    {
        $sql = new \DbQuery();

        $sql->select('l.name, l.iso_code, l.language_code');
        $sql->from('lang', 'l');
        $sql->where('l.active = 1');

        $sql->innerJoin('lang_shop', 'ls', 'ls.id_lang = l.id_lang and ls.id_shop =' . (int) $shopId);

        return \Db::getInstance()->executeS($sql);
    }

    public function getDefaultLanguage($shopId)
    {
        $sql = new \DbQuery();
        $sql->select('value');
        $sql->from('configuration');
        $sql->where('id_shop=' . (int) $shopId . ' and name = "PS_LANG_DEFAULT"');

        $ans = \Db::getInstance()->executeS($sql);

        if (0 == count($ans)) {
            return new \Language(\Configuration::get('PS_LANG_DEFAULT'));
        }

        return new \Language($ans[0]['value']);
    }
}
