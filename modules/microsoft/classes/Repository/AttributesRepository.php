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

class AttributesRepository
{
    private $context;

    public function __construct(\Context $context)
    {
        $this->context = $context;
    }

    public function getAllAttributes()
    {
        $sql = new \DbQuery();

        $sql->select('agl.id_attribute_group, agl.name, l.language_code');

        $sql->from('attribute_group_lang', 'agl');

        $sql->leftjoin('lang', 'l', 'agl.id_lang = l.id_lang');

        $attributes = \Db::getInstance()->executeS($sql);

        $attrArray = array();

        foreach ($attributes as $item) {
            $agId = $item['id_attribute_group'];
            if (!isset($attrArray[$agId])) {
                $attrArray[$agId] = array();
            }
            array_push($attrArray[$agId], $item);
        }

        $attributes = \Db::getInstance()->executeS($sql);

        $sql2 = new \DbQuery();

        $sql2->select('fl.id_feature, fl.name, l.language_code');

        $sql2->from('feature_lang', 'fl');

        $sql2->leftjoin('lang', 'l', 'fl.id_lang = l.id_lang');

        $features = \Db::getInstance()->executeS($sql2);

        $featureArray = array();

        foreach ($features as $item) {
            $agId = $item['id_feature'];
            if (!isset($featureArray[$agId])) {
                $featureArray[$agId] = array();
            }
            array_push($featureArray[$agId], $item);
        }

        $ans = array();

        foreach ($attrArray as $item) {
            array_push($ans, $item);
        }

        foreach ($featureArray as $item) {
            array_push($ans, $item);
        }

        return $ans;
    }
}
