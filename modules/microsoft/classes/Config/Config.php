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

namespace PrestaShop\Module\Microsoft\Config;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Config
{
    public const PS_MICROSOFT_API_URL = 'https://picprestashopapp-prod.trafficmanager.net/api/PrestaShopMT/v1/Base/';
    public const PS_MICROSOFT_DOWNLOAD_API_URL = Config::PS_MICROSOFT_API_URL . 'Download';
    public const PS_MICROSOFT_UNINSTALL_API_URL = Config::PS_MICROSOFT_API_URL . 'Uninstall';
    public const PS_MICROSOFT_CONFIG_PUBLIC_KEY = 'PS_MICROSOFT_PUBLIC_KEY';
    public const PS_MICROSOFT_ISS = 'PicPrestaShop';
    public const PS_MICROSOFT_ISS_SI = 'PicPrestaShopSI';

    public static function PS_MICROSOFT_PUBLIC_KEY($shopId)
    {
        $sql = new \DbQuery();
        $sql->select('value');
        $sql->from('configuration');
        $sql->where('id_shop=' . (int) $shopId . ' and name = "' . Config::PS_MICROSOFT_CONFIG_PUBLIC_KEY . '"');

        $ans = \Db::getInstance()->executeS($sql);

        if (0 == count($ans)) {
            $sql2 = new \DbQuery();
            $sql2->select('value');
            $sql2->from('configuration');
            $sql2->where('name = "' . Config::PS_MICROSOFT_CONFIG_PUBLIC_KEY . '"');
            $ans = \Db::getInstance()->executeS($sql2);
        }

        if (0 == count($ans)) {
            return false;
        }

        return $ans[0]['value'];
    }

    public static function PS_MICROSOFT_UET_TAG_PATH($id)
    {
        return 'classes' . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'uet-tag-shop-' . (int) $id . '.js';
    }

    public static function PS_MICROSOFT_PSX_UUID()
    {
        $sql = new \DbQuery();
        $sql->select('value');
        $sql->from('configuration');
        $sql->where('name = "PSX_UUID_V4"');
        $DbAns = \Db::getInstance()->executeS($sql);

        $ans = [];
        for ($i = 0; $i < count($DbAns); ++$i) {
            $ans[$i] = $DbAns[$i]['value'];
        }

        return $ans;
    }
}
