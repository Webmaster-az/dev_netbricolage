<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Musaffar Patel
 * @copyright 2016-2021 Musaffar Patel
 * @license   LICENSE.txt
 */

class PPBSProductHelper
{
    protected static $_pricesPPBS;

    public static function getCombinations($id_product, $id_lang)
    {
        $product = new Product($id_product);
        $currency = Context::getContext()->currency;
        $groups = array();
        $comb_array = array();
        $default_class = 'highlighted';

        // Query below taken from Product::getAttributeCombinations, shop association was removed
        $groupByIdAttributeGroup = true;
        $sql = 'SELECT pa.*, ag.`id_attribute_group`, ag.`is_color_group`, agl.`name` AS group_name, al.`name` AS attribute_name,
                    a.`id_attribute`
                FROM `' . _DB_PREFIX_ . 'product_attribute` pa
                LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a ON a.`id_attribute` = pac.`id_attribute`
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = ' . (int)$id_lang . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = ' . (int)$id_lang . ')
                WHERE pa.`id_product` = ' . (int)$id_product . '
                GROUP BY pa.`id_product_attribute`' . ($groupByIdAttributeGroup ? ',ag.`id_attribute_group`' : '') . '
                ORDER BY pa.`id_product_attribute`';

        $combinations = DB::getInstance()->executeS($sql);

        if (is_array($combinations)) {
            $combination_images = $product->getCombinationImages($id_lang);
            foreach ($combinations as $k => $combination) {
                $price_to_convert = Tools::convertPrice($combination['price'], $currency);
                $price = Tools::displayPrice($price_to_convert, $currency);

                $comb_array[$combination['id_product_attribute']]['id_product_attribute'] = $combination['id_product_attribute'];
                $comb_array[$combination['id_product_attribute']]['attributes'][] = array($combination['group_name'], $combination['attribute_name'], $combination['id_attribute']);
                $comb_array[$combination['id_product_attribute']]['wholesale_price'] = $combination['wholesale_price'];
                $comb_array[$combination['id_product_attribute']]['price'] = $price;
                $comb_array[$combination['id_product_attribute']]['weight'] = $combination['weight'] . Configuration::get('PS_WEIGHT_UNIT');
                $comb_array[$combination['id_product_attribute']]['unit_impact'] = $combination['unit_price_impact'];
                $comb_array[$combination['id_product_attribute']]['reference'] = $combination['reference'];
                $comb_array[$combination['id_product_attribute']]['ean13'] = $combination['ean13'];
                $comb_array[$combination['id_product_attribute']]['upc'] = $combination['upc'];
                $comb_array[$combination['id_product_attribute']]['id_image'] = isset($combination_images[$combination['id_product_attribute']][0]['id_image']) ? $combination_images[$combination['id_product_attribute']][0]['id_image'] : 0;
                $comb_array[$combination['id_product_attribute']]['available_date'] = strftime($combination['available_date']);
                $comb_array[$combination['id_product_attribute']]['default_on'] = $combination['default_on'];
                if ($combination['is_color_group']) {
                    $groups[$combination['id_attribute_group']] = $combination['group_name'];
                }
            }
        }

        if (isset($comb_array)) {
            foreach ($comb_array as $id_product_attribute => $product_attribute) {
                $list = '';

                /* In order to keep the same attributes order */
                asort($product_attribute['attributes']);

                foreach ($product_attribute['attributes'] as $attribute) {
                    $list .= $attribute[0] . ' - ' . $attribute[1] . ', ';
                }

                $list = rtrim($list, ', ');
                $comb_array[$id_product_attribute]['image'] = $product_attribute['id_image'] ? new Image($product_attribute['id_image']) : false;
                $comb_array[$id_product_attribute]['available_date'] = $product_attribute['available_date'] != 0 ? date('Y-m-d', strtotime($product_attribute['available_date'])) : '0000-00-00';
                $comb_array[$id_product_attribute]['attributes'] = $list;
                $comb_array[$id_product_attribute]['name'] = $list;

                if ($product_attribute['default_on']) {
                    $comb_array[$id_product_attribute]['class'] = $default_class;
                }
            }
        }
        return $comb_array;
    }

    /**
     * Create an array of equations with the IPA as the key
     * @param $ppbs_equations_collection
     * @return array
     */
    public static function createCombinationsLookup($ppbs_equations_collection)
    {
        $new_collection = array();
        foreach ($ppbs_equations_collection as $equation) {
            $new_collection[$equation['ipa']] = $equation;
        }
        return $new_collection;
    }

    /**
     * Get Product info such as price, attribute p[rice based on Product ID and attributes array (group)
     * @param $id_product
     * @param $group
     */
    public static function getProductInfo($id_product, $group, $id_product_attribute = 0, $qty = 1)
    {
        $id_shop = Tools::getValue('id_shop');
        $id_cart = Context::getContext()->cart->id;

        if (!empty($group)) {
            $id_product_attribute = Product::getIdProductAttributeByIdAttributes((int)$id_product, $group);
        }

        // customer group reduction
        $group_reduction = 0;
        if ((int)Context::getContext()->customer->id > 0) {
            $group_reduction = Group::getReduction(Context::getContext()->customer->id);
        }

        $ppbs_product = new PPBSProduct();
        $ppbs_product->getByProduct($id_product);

        $product_obj = new Product($id_product);
        $product = [];
        $product['id_product'] = $id_product;
        $product['id_product_attribute'] = $id_product_attribute;
        $product['out_of_stock'] = $product_obj->out_of_stock;
        $product['id_category_default'] = $product_obj->id_category_default;
        $product['link_rewrite'] = ''; //$product_obj->link_rewrite;
        $product['ean13'] = $product_obj->ean13;
        $product['minimal_quantity'] = $product_obj->minimal_quantity;
        $product['unit_price_ratio'] = $product_obj->unit_price_ratio;
        $product['price_display'] = (int)Product::getTaxCalculationMethod(Context::getContext()->cookie->id_customer);
        $product['quantity_wanted'] = $qty;
        $product['group_reduction'] = $group_reduction;

        // calculate how much stock we have for this product / product combination, take into account existing items in the cart for the same customer
        $product_stock = PPBSStockHelper::getStock($id_product, $id_product_attribute, $id_shop);
        $product_unit_area = PPBSCartHelper::getProductTotalArea($id_product, $id_product_attribute, 0, $id_cart, $id_shop);
        $product['qty_stock'] = max($product_stock - $product_unit_area, 0);

        $product_properties = Product::getProductProperties(Context::getContext()->language->id, $product, null);
        $product['id_product_attribute'] = 0;
        //$product_properties_tmp = Product::getProductProperties(Context::getContext()->language->id, $product, null);
        $base_price = $product_obj->price;
        //$base_price = $product_properties['price_tax_exc'];

        // get original price before customer group diascount
        if ($group_reduction > 0) {
            $base_price = $base_price / ((100 - $group_reduction) / 100);
        }

        $product_properties['base_price_exc_tax'] = $base_price;

        // it's possible some specific prices (Fixed impact) need to override the main attribute price
        if (!empty($product_properties['specific_prices']['id_specific_price'])) {
            $specific_price = $product_properties['specific_prices'];
            if ($specific_price['reduction'] == 0 && $specific_price['price'] > 0) {
                $product_properties['attribute_price'] = 0;
            }
        }

        $product_properties['ppbs'] = $ppbs_product;
        return $product_properties;
    }

    /**
     * Get Attribute price
     * @param $id_product
     * @param $id_shop
     * @param $id_product_attribute
     * @return mixed
     * @throws PrestaShopDatabaseException
     */
    public static function getProductAttributePrice($id_product, $id_shop, $id_product_attribute)
    {
        $cache_id_2 = $id_product . '-' . $id_shop;
        if (!isset(self::$_pricesPPBS[$cache_id_2])) {
            $sql = new DbQuery();
            $sql->select('product_shop.`price`, product_shop.`ecotax`');
            $sql->from('product', 'p');
            $sql->innerJoin('product_shop', 'product_shop', '(product_shop.id_product=p.id_product AND product_shop.id_shop = ' . (int)$id_shop . ')');
            $sql->where('p.`id_product` = ' . (int)$id_product);

            if (Combination::isFeatureActive()) {
                $sql->select('product_attribute_shop.id_product_attribute, product_attribute_shop.`price` AS attribute_price, product_attribute_shop.default_on');
                $sql->leftJoin('product_attribute', 'pa', 'pa.`id_product` = p.`id_product`');
                $sql->leftJoin('product_attribute_shop', 'product_attribute_shop', '(product_attribute_shop.id_product_attribute = pa.id_product_attribute AND product_attribute_shop.id_shop = ' . (int)$id_shop . ')');
            } else {
                $sql->select('0 as id_product_attribute');
            }

            $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

            foreach ($res as $row) {
                $array_tmp = array(
                    'price' => $row['price'],
                    'ecotax' => $row['ecotax'],
                    'attribute_price' => (isset($row['attribute_price']) ? $row['attribute_price'] : null)
                );
                self::$_pricesPPBS[$cache_id_2][(int)$row['id_product_attribute']] = $array_tmp;

                if (isset($row['default_on']) && $row['default_on'] == 1) {
                    self::$_pricesPPBS[$cache_id_2][0] = $array_tmp;
                }
            }
        }
        /*if (!isset(self::$_pricesLevel2[$cache_id_2][(int)$params['id_product_attribute']]))
            return;*/
        $result = self::$_pricesPPBS[$cache_id_2][(int)$id_product_attribute];
        return $result;
    }

    public static function getCartProductUnits($id_product, $id_cart, $ipa, $id_shop = 1, $id_customization = 0)
    {
        $cart_unit_collection = array();

        if ($id_product == '') {
            return array();
        }

        if ($id_cart == '') {
            return array();
        }

        $sql = 'SELECT
					ppbs_dimensions,
					quantity
				FROM ' . _DB_PREFIX_ . 'customized_data cd
				INNER JOIN ' . _DB_PREFIX_ . 'customization c ON cd.id_customization = c.id_customization
				WHERE 
				    c.id_cart = ' . (int)$id_cart . '
				    AND c.id_product = ' . (int)$id_product . '
				    AND c.id_product_attribute = ' . (int)$ipa. '
				    AND ppbs_dimensions <> ""';
        

        if ($id_customization > 0) {
            $sql .= ' AND cd.id_customization = ' . (int)$id_customization;
        }

        $rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (is_array($rows) && count($rows) > 0) {
            return $rows;
        } else {
            return array();
        }
    }


    /**
     * Get area based price based on area
     * @param $id_product
     * @param $total_area
     * @param $id_shop
     * @return bool|TPPBSAreaPrice
     * @throws PrestaShopDatabaseException
     */
    public static function getAreaPriceByArea($id_product, $total_area, $id_shop)
    {
        $sql = 'SELECT
					id_area_price,
					id_product,
					id_shop,
					area_low,
					area_high,
					impact,
					price,
					weight
				FROM ' . _DB_PREFIX_ . 'ppbs_area_price
				WHERE id_product = ' . (int)$id_product . '
				AND id_shop = ' . (int)$id_shop . '
				AND (' . (float)$total_area . ' >= area_low AND ' . (float)$total_area . ' <= area_high)
				';
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if ($result) {
            $areaPrice = new stdClass();
            $areaPrice->areaLow = $result[0]['area_low'];
            $areaPrice->areaHigh = $result[0]['area_high'];
            $areaPrice->impact = $result[0]['impact'];
            $areaPrice->price = $result[0]['price'];
            $areaPrice->weight = $result[0]['weight'];
            $areaPrice->id_product = $result[0]['id_product'];
            return $areaPrice;
        }
        return false;
    }


    /**
     * Convert Are Price Collection prices to a certain currency
     * @param $area_price_collection
     * @param Currency|null $currency_from
     * @param Currency $currency_to
     * @return mixed
     */
    public static function convertAreaPricesToCurrency($area_price_collection, Currency $currency_from = null, Currency $currency_to)
    {
        if (empty($area_price_collection)) {
            return $area_price_collection;
        }
        foreach ($area_price_collection as &$area_price) {
            $area_price->price = PPBSToolsHelper::convertPriceFull($area_price->price, null, $currency_to);
        }
        return $area_price_collection;
    }

    /**
     * format number to correct number of decimals
     * @param $number
     * @return float
     */
    public static function formatPriceAsNumber($number)
    {
        $number = Tools::displayPrice($number);
        $number = preg_replace('/[^0-9-.,]+/', '', $number);
        return $number;
    }

    /**
     * Takes a string and returns a safe price (suitable for php and mysql)
     * @param $number
     * @return float
     */
    public static function parseAsPrice($number)
    {
        $number = str_replace(',', '.', $number);
        return $number;
    }

    /**
     * Get the product attribute weight
     * @param $id_product
     * @param $id_product_attribute
     * @return mixed
     */
    public static function getProductAttributeWeight($id_product, $id_product_attribute)
    {
        $sql = new DbQuery();
        $sql->select('weight');
        $sql->from('product_attribute');
        $sql->where('id_product_attribute = ' . (int)$id_product_attribute);
        $row = Db::getInstance()->getRow($sql);
        return $row['weight'];
    }

    /**
     * format order to appropriate number of decimals
     * @param $price
     * @return float
     */
    public static function formatPrice($price)
    {
        return Tools::ps_round($price, PPBSToolsHelper::getPricePrecision());
    }

    /**
     * Check if a product is using the module and has stock management enabled
     * @param $id_product
     * @return bool
     */
    public static function isStockEnabled($id_product)
    {
        $ppbs_product = new PPBSProduct();
        $ppbs_product->loadByProduct($id_product);

        if ($ppbs_product->enabled && $ppbs_product->stock_enabled) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * determine if a product has PPBS option enabled
     * @param $id_product
     * @return bool
     */
    public static function isPPBSEnabled($id_product)
    {
        $ppbs_product_model = new PPBSProduct();
        $ppbs_product_model->getByProduct($id_product);
        if ((int)$ppbs_product_model->enabled == 1) {
            return true;
        } else {
            return false;
        }
    }
}
