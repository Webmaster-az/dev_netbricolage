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

use Product;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ProductRepository
{
    private $language;

    private $context;

    public function __construct(\Language $language, \Context $context)
    {
        $this->language = $language;
        $this->context = $context;
    }

    public function getProductSample($shopId = 1, $limit = 0)
    {
        $sql = new \DbQuery();

        $sql->select('p.id_product, p.price, pl.description, pl.description_short, pl.name, pl.link_rewrite, i.id_image');
        $sql->from('product', 'p');
        $sql->innerjoin('product_shop', 'ps', 'ps.id_product = p.id_product and ps.id_shop =' . (int) $shopId);
        $sql->leftjoin('product_lang', 'pl', 'pl.id_product = p.id_product and pl.id_shop = ' . (int) $shopId . ' and pl.id_lang = ' . (int) $this->language->id);
        $sql->leftjoin('image', 'i', 'i.id_product = p.id_product');
        $sql->limit((int) $limit);
        $sql->groupby('p.id_product');

        $ans = \Db::getInstance()->executeS($sql);

        for ($i = 0; $i < count($ans); ++$i) {
            $ans[$i]['image_url'] = $this->context->link->getImageLink($ans[$i]['link_rewrite'], $ans[$i]['id_image']);
        }

        return $ans;
    }

    public function getProductList($shopId, array $options = [])
    {
        $sql = new \DbQuery();

        $sql->select('p.id_product');
        $sql->from('product', 'p');

        $sql->innerJoin('product_shop', 'ps', 'ps.id_product = p.id_product');
        $sql->innerJoin('product_lang', 'pl', 'pl.id_product = ps.id_product AND pl.id_shop = ps.id_shop');

        $sql->where('ps.id_shop = ' . (int) $shopId);
        $sql->where('pl.id_lang = ' . (int) $this->language->id);

        if (isset($options['onlyActive'])) {
            $sql->where('ps.active = 1');
        }

        if (isset($options['orderBy'])) {
            $sql->orderBy(bqsql($options['orderBy']));
        }

        if (isset($options['greater'])) {
            $sql->where('p.id_product >= ' . (int) $options['greater']);
        }

        if (isset($options['limit'], $options['offset'])) {
            $sql->limit($options['limit'], $options['offset']);
        } elseif (isset($options['limit'])) {
            $sql->limit($options['limit']);
        }

        return \Db::getInstance()->executeS($sql);
    }

    public function getProductInfoByPag($shopId, $productStartId, $threshold = 500, array $option = [])
    {
        if (!isset($shopId) || false === $shopId || !isset($productStartId) || false === $productStartId) {
            return [];
        }

        $list = $this->getProductList($shopId, ['limit' => $threshold, 'greater' => $productStartId, 'orderBy' => 'p.id_product']);

        $count = 0;
        $iter = 0;
        $ans = [];

        foreach ($list as $item) {
            $productId = $item['id_product'];
            $ans[$iter]['productId'] = (int) $productId;
            $ans[$iter]['productInfo'] = $this->getProductBaseInfoById($shopId, $productId);
            $ans[$iter]['attributes'] = $this->getAttributes($productId);
            $ans[$iter]['features'] = $this->getFeatures($productId);
            $ans[$iter]['image'] = $this->getImage($shopId, $productId);

            $count = $count + count($ans[$iter]['productInfo']) + count($ans[$iter]['attributes']) + count($ans[$iter]['features']);
            if ($count >= $threshold) {
                break;
            }
            ++$iter;
        }

        return $ans;
    }

    public function getProductInfoById($shopId, $productId, array $option = [])
    {
        if (!isset($productId) || false === $productId || !isset($shopId) || false === $shopId) {
            return [];
        }

        $ans['productInfo'] = $this->getProductBaseInfoById($shopId, $productId);
        $ans['attributes'] = $this->getAttributes($productId);
        $ans['features'] = $this->getFeatures($productId);
        $ans['image'] = $this->getImage($shopId, $productId);

        return $ans;
    }

    public function getAttributes($productId, array $options = [])
    {
        $sql = new \DbQuery();

        $sql->select('pa.*, a.id_attribute, a.color, agl.name as group_name, agl.public_name as group_public_name, al.name as attribute_value');

        $sql->from('product_attribute', 'pa');

        $sql->where('pa.id_product =' . (int) $productId);

        $sql->leftjoin('product_attribute_combination', 'pac', 'pac.id_product_attribute = pa.id_product_attribute');

        $sql->leftjoin('attribute', 'a', 'a.id_attribute = pac.id_attribute');

        $sql->leftjoin('attribute_group_lang', 'agl', 'agl.id_attribute_group = a.id_attribute_group and agl.id_lang = ' . (int) $this->language->id);

        $sql->leftjoin('attribute_lang', 'al', 'al.id_attribute = a.id_attribute and al.id_lang = ' . (int) $this->language->id);

        $sql->orderBy('id_product_attribute');

        return \Db::getInstance()->executeS($sql);
    }

    public function getFeatures($productId, array $options = [])
    {
        $sql = new \DbQuery();

        $sql->select('fl.name, fvl.value');

        $sql->from('feature_product', 'fp');

        $sql->Where('fp.id_product = ' . (int) $productId);

        $sql->leftJoin('feature_lang', 'fl', 'fl.id_feature = fp.id_feature and fl.id_lang = ' . (int) $this->language->id);

        $sql->leftJoin('feature_value_lang', 'fvl', 'fvl.id_feature_value = fp.id_feature_value and fvl.id_lang = ' . (int) $this->language->id);

        return \Db::getInstance()->executeS($sql);
    }

    public function getImage($shopId, $productId)
    {
        // get id_product_attribute related images
        $sql = new \DbQuery();

        $sql->select('pa.id_product_attribute, pl.link_rewrite, pai.id_image');

        $sql->from('product_attribute', 'pa');

        $sql->where('pa.id_product =' . (int) $productId);

        $sql->innerjoin('product_lang', 'pl', 'pl.id_product = ' . (int) $productId . ' and pl.id_shop = ' . (int) $shopId . ' and pl.id_lang = ' . (int) $this->language->id . ' and pl.id_product = pa.id_product');

        $sql->innerjoin('product_attribute_image', 'pai', 'pai.id_product_attribute = pa.id_product_attribute and pai.id_image != 0');

        $sql->orderby('id_product_attribute');

        $ans = \Db::getInstance()->executeS($sql);

        // get id_image related images
        $sql2 = new \DbQuery();

        $sql2->select('0 as id_product_attribute, pl.link_rewrite, i.id_image');

        $sql2->from('image', 'i');

        $sql2->where('i.id_product =' . (int) $productId);

        $sql2->leftjoin('product_lang', 'pl', 'pl.id_product = ' . (int) $productId . ' and pl.id_shop = ' . (int) $shopId . ' and pl.id_lang = ' . (int) $this->language->id . ' and pl.id_product = i.id_product');

        $ans2 = \Db::getInstance()->executeS($sql2);

        $ans = array_merge($ans2, $ans);

        for ($i = 0; $i < count($ans); ++$i) {
            $ans[$i]['url'] = $this->context->link->getImageLink($ans[$i]['link_rewrite'], $ans[$i]['id_image']);
        }

        return $ans;
    }

    private function getProductBaseInfoById($shopId, $productId, array $options = [])
    {
        $sql = new \DbQuery();

        $sql->select('p.*, pl.description, pl.description_short, pl.link_rewrite, pl.name, m.name as manufacturer');

        $sql->from('product', 'p');

        $sql->where('p.id_product = ' . (int) $productId . ' and p.state != 0');

        $sql->leftJoin('product_lang', 'pl', 'pl.id_product = p.id_product and pl.id_lang = ' . (int) $this->language->id . ' and pl.id_shop = ' . (int) $shopId);

        $sql->leftJoin('manufacturer', 'm', 'p.id_manufacturer = m.id_manufacturer');

        $res = \Db::getInstance()->executeS($sql);

        for ($x = 0; $x < count($res); ++$x) {
            $res[$x]['link'] = $this->context->link->getProductLink($res[$x]['id_product'], null, null, null, null, $shopId);
        }

        return $res;
    }
}
