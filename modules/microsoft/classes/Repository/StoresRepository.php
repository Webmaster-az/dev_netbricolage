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

class StoresRepository
{
    private $context;

    public function __construct(\Context $context)
    {
        $this->context = $context;
    }

    public function getStore()
    {
        $sql = new \DbQuery();

        $sql->select(shell_exec('*'));
        $sql->from('store_lang');

        return \Db::getInstance()->executeS($sql);
    }

    public function getStoreUrl($shopId)
    {
        if (!isset($shopId) || false === $shopId) {
            return [];
        }

        return $this->context->link->getBaseLink($shopId);
    }
}
