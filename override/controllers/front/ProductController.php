<?php

/*
 * This file is part of the "Prestashop Clean URLs" module.
 *
 * (c) Faktiva (http://faktiva.com)
 *
 * NOTICE OF LICENSE
 * This source file is subject to the CC BY-SA 4.0 license that is
 * available at the URL https://creativecommons.org/licenses/by-sa/4.0/
 *
 * DISCLAIMER
 * This code is provided as is without any warranty.
 * No promise of being safe or secure
 *
 * @author   Emiliano 'AlberT' Gabrielli <albert@faktiva.com>
 * @license  https://creativecommons.org/licenses/by-sa/4.0/  CC-BY-SA-4.0
 * @source   https://github.com/faktiva/prestashop-clean-urls
 */

class ProductController extends ProductControllerCore
{
    
    public function init()
    {
        if ($product_rewrite = Tools::getValue('product_rewrite')) {
            $url_id_pattern = '/.*?([0-9]+)\-([a-zA-Z0-9-]*)(\.html)?/';
            $lang_id = (int) Context::getContext()->language->id;
            $sql = 'SELECT `id_product`
                FROM `'._DB_PREFIX_.'product_lang`
                WHERE `link_rewrite` = \''.pSQL(str_replace('.html', '', $product_rewrite)).'\' AND `id_lang` = '.$lang_id;
            if (Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP) {
                $sql .= ' AND `id_shop` = '.(int) Shop::getContextShopID();
            }
            $id_product = (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
            if ($id_product > 0) {
                $_GET['id_product'] = $id_product;
            } elseif (preg_match($url_id_pattern, $this->request_uri, $url_parts)) {
                $sql = 'SELECT `id_product`
                    FROM `'._DB_PREFIX_.'product_lang`
                    WHERE `id_product` = \''.pSQL($url_parts[1]).'\' AND `id_lang` = '.$lang_id;
                if (Shop::isFeatureActive() && Shop::getContext() == Shop::CONTEXT_SHOP) {
                    $sql .= ' AND `id_shop` = '.(int) Shop::getContextShopID();
                }
                $id_product = (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
                if ($id_product > 0) {
                    $_GET['id_product'] = $id_product;
                }
            }
        }
        parent::init();
    }
    /*
    * module: minpurchase
    * date: 2023-02-17 17:12:11
    * version: 1.2.2
    */
    public function initContent()
    {
        if (Module::isEnabled('minpurchase')) {
            include_once(_PS_MODULE_DIR_.'minpurchase/classes/MinpurchaseConfiguration.php');
            $this->product = MinpurchaseConfiguration::setProduct($this->product);
        }
        return parent::initContent();
    }
    /*
    * module: minpurchase
    * date: 2023-02-17 17:12:11
    * version: 1.2.2
    */
    protected function assignAttributesGroups($product_for_template = null)
    {
        if (!Module::isEnabled('minpurchase')) {
            return parent::assignAttributesGroups($product_for_template);
        }
        $colors = array();
        $groups = array();
        $this->combinations = array();
        $attributes_groups = $this->product->getAttributesGroups($this->context->language->id);
        if (is_array($attributes_groups) && $attributes_groups) {
            $combination_images = $this->product->getCombinationImages($this->context->language->id);
            $combination_prices_set = array();
            foreach ($attributes_groups as $k => $row) {
                if (isset($row['is_color_group']) && $row['is_color_group'] && (isset($row['attribute_color']) && $row['attribute_color']) || (file_exists(_PS_COL_IMG_DIR_.$row['id_attribute'].'.jpg'))) {
                    $colors[$row['id_attribute']]['value'] = $row['attribute_color'];
                    $colors[$row['id_attribute']]['name'] = $row['attribute_name'];
                    if (!isset($colors[$row['id_attribute']]['attributes_quantity'])) {
                        $colors[$row['id_attribute']]['attributes_quantity'] = 0;
                    }
                    $colors[$row['id_attribute']]['attributes_quantity'] += (int) $row['quantity'];
                }
                if (!isset($groups[$row['id_attribute_group']])) {
                    $groups[$row['id_attribute_group']] = array(
                        'group_name' => $row['group_name'],
                        'name' => $row['public_group_name'],
                        'group_type' => $row['group_type'],
                        'default' => -1,
                    );
                }
                $groups[$row['id_attribute_group']]['attributes'][$row['id_attribute']] = array(
                    'name' => $row['attribute_name'],
                    'html_color_code' => $row['attribute_color'],
                    'texture' => (@filemtime(_PS_COL_IMG_DIR_.$row['id_attribute'].'.jpg')) ? _THEME_COL_DIR_.$row['id_attribute'].'.jpg' : '',
                    'selected' => (isset($product_for_template['attributes'][$row['id_attribute_group']]['id_attribute']) && $product_for_template['attributes'][$row['id_attribute_group']]['id_attribute'] == $row['id_attribute']) ? true : false,
                );
                if ($row['default_on'] && $groups[$row['id_attribute_group']]['default'] == -1) {
                    $groups[$row['id_attribute_group']]['default'] = (int) $row['id_attribute'];
                }
                if (!isset($groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']])) {
                    $groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] = 0;
                }
                $groups[$row['id_attribute_group']]['attributes_quantity'][$row['id_attribute']] += (int) $row['quantity'];
                $this->combinations[$row['id_product_attribute']]['attributes_values'][$row['id_attribute_group']] = $row['attribute_name'];
                $this->combinations[$row['id_product_attribute']]['attributes'][] = (int) $row['id_attribute'];
                $this->combinations[$row['id_product_attribute']]['price'] = (float) $row['price'];
                if (!isset($combination_prices_set[(int) $row['id_product_attribute']])) {
                    $combination_specific_price = null;
                    Product::getPriceStatic((int) $this->product->id, false, $row['id_product_attribute'], 6, null, false, true, 1, false, null, null, null, $combination_specific_price);
                    $combination_prices_set[(int) $row['id_product_attribute']] = true;
                    $this->combinations[$row['id_product_attribute']]['specific_price'] = $combination_specific_price;
                }
                $this->combinations[$row['id_product_attribute']]['ecotax'] = (float) $row['ecotax'];
                $this->combinations[$row['id_product_attribute']]['weight'] = (float) $row['weight'];
                $this->combinations[$row['id_product_attribute']]['quantity'] = (int) $row['quantity'];
                $this->combinations[$row['id_product_attribute']]['reference'] = $row['reference'];
                $this->combinations[$row['id_product_attribute']]['unit_impact'] = $row['unit_price_impact'];
                $this->combinations[$row['id_product_attribute']]['minimal_quantity'] = $row['minimal_quantity'];
                include_once(_PS_MODULE_DIR_.'minpurchase/classes/MinpurchaseConfiguration.php');
                $this->combinations = MinpurchaseConfiguration::setCombinations($this->product->id, $row['id_product_attribute'], $this->combinations);
                if ($row['available_date'] != '0000-00-00' && Validate::isDate($row['available_date'])) {
                    $this->combinations[$row['id_product_attribute']]['available_date'] = $row['available_date'];
                    $this->combinations[$row['id_product_attribute']]['date_formatted'] = Tools::displayDate($row['available_date']);
                } else {
                    $this->combinations[$row['id_product_attribute']]['available_date'] = $this->combinations[$row['id_product_attribute']]['date_formatted'] = '';
                }
                if (!isset($combination_images[$row['id_product_attribute']][0]['id_image'])) {
                    $this->combinations[$row['id_product_attribute']]['id_image'] = -1;
                } else {
                    $this->combinations[$row['id_product_attribute']]['id_image'] = $id_image = (int) $combination_images[$row['id_product_attribute']][0]['id_image'];
                    if ($row['default_on']) {
                        foreach ($this->context->smarty->tpl_vars['product']->value['images'] as $image) {
                            if ($image['cover'] == 1) {
                                $current_cover = $image;
                            }
                        }
                        if (!isset($current_cover)) {
                            $current_cover = array_values($this->context->smarty->tpl_vars['product']->value['images'])[0];
                        }
                        if (is_array($combination_images[$row['id_product_attribute']])) {
                            foreach ($combination_images[$row['id_product_attribute']] as $tmp) {
                                if ($tmp['id_image'] == $current_cover['id_image']) {
                                    $this->combinations[$row['id_product_attribute']]['id_image'] = $id_image = (int) $tmp['id_image'];
                                    break;
                                }
                            }
                        }
                        if ($id_image > 0) {
                            if (isset($this->context->smarty->tpl_vars['images']->value)) {
                                $product_images = $this->context->smarty->tpl_vars['images']->value;
                            }
                            if (isset($product_images) && is_array($product_images) && isset($product_images[$id_image])) {
                                $product_images[$id_image]['cover'] = 1;
                                $this->context->smarty->assign('mainImage', $product_images[$id_image]);
                                if (count($product_images)) {
                                    $this->context->smarty->assign('images', $product_images);
                                }
                            }
                            $cover = $current_cover;
                            if (isset($cover) && is_array($cover) && isset($product_images) && is_array($product_images)) {
                                $product_images[$cover['id_image']]['cover'] = 0;
                                if (isset($product_images[$id_image])) {
                                    $cover = $product_images[$id_image];
                                }
                                $cover['id_image'] = (Configuration::get('PS_LEGACY_IMAGES') ? ($this->product->id.'-'.$id_image) : (int) $id_image);
                                $cover['id_image_only'] = (int) $id_image;
                                $this->context->smarty->assign('cover', $cover);
                            }
                        }
                    }
                }
            }
            $current_selected_attributes = array();
            $count = 0;
            foreach ($groups as &$group) {
                $count++;
                if ($count > 1) {
                    $id_attributes = Db::getInstance()->executeS('SELECT `id_attribute` FROM `'._DB_PREFIX_.'product_attribute_combination` pac2
                        WHERE `id_product_attribute` IN (
                            SELECT pac.`id_product_attribute`
                                FROM `'._DB_PREFIX_.'product_attribute_combination` pac
                                INNER JOIN `'._DB_PREFIX_.'product_attribute` pa ON pa.id_product_attribute = pac.id_product_attribute
                                WHERE id_product = '.$this->product->id.' AND id_attribute IN ('.implode(',', array_map('intval', $current_selected_attributes)).')
                                GROUP BY id_product_attribute
                                HAVING COUNT(id_product) = '.count($current_selected_attributes).'
                        ) AND id_attribute NOT IN ('.implode(',', array_map('intval', $current_selected_attributes)).')');
                    foreach ($id_attributes as $k => $row) {
                        $id_attributes[$k] = (int)$row['id_attribute'];
                    }
                    foreach ($group['attributes'] as $key => $attribute) {
                        if (!in_array((int)$key, $id_attributes)) {
                            unset($group['attributes'][$key]);
                            unset($group['attributes_quantity'][$key]);
                        }
                    }
                }
                $index = 0;
                $current_selected_attribute = 0;
                foreach ($group['attributes'] as $key => $attribute) {
                    if ($index === 0) {
                        $current_selected_attribute = $key;
                    }
                    if ($attribute['selected']) {
                        $current_selected_attribute = $key;
                        break;
                    }
                }
                if ($current_selected_attribute > 0) {
                    $current_selected_attributes[] = $current_selected_attribute;
                }
            }
            if (!Product::isAvailableWhenOutOfStock($this->product->out_of_stock) && Configuration::get('PS_DISP_UNAVAILABLE_ATTR') == 0) {
                foreach ($groups as &$group) {
                    foreach ($group['attributes_quantity'] as $key => &$quantity) {
                        if ($quantity <= 0) {
                            unset($group['attributes'][$key]);
                        }
                    }
                }
                foreach ($colors as $key => $color) {
                    if ($color['attributes_quantity'] <= 0) {
                        unset($colors[$key]);
                    }
                }
            }
            foreach ($this->combinations as $id_product_attribute => $comb) {
                $attribute_list = '';
                foreach ($comb['attributes'] as $id_attribute) {
                    $attribute_list .= '\''.(int) $id_attribute.'\',';
                }
                $attribute_list = rtrim($attribute_list, ',');
                $this->combinations[$id_product_attribute]['list'] = $attribute_list;
            }
            $this->context->smarty->assign(array(
                'groups' => $groups,
                'colors' => (count($colors)) ? $colors : false,
                'combinations' => $this->combinations,
                'combinationImages' => $combination_images,
            ));
        } else {
            $this->context->smarty->assign(array(
                'groups' => array(),
                'colors' => false,
                'combinations' => array(),
                'combinationImages' => array(),
            ));
        }
    }
}
