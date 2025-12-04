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

use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;

class PPBSCartHelper
{
    protected static $_cache;

    private static function doEncoding($matches)
    {
        return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UTF-16');
    }

    public static function rawJsonEncode($input)
    {
        return preg_replace_callback(
            '/\\\\u([0-9a-zA-Z]{4})/',
            array('PPBSCartHelper', 'doEncoding'),
            json_encode($input)
        );
    }

    /**
     * Get customizaed data row by id_customization
     * @param $id_customization
     * @param $id_product
     * @param $id_product_attribute
     * @param $id_cart
     */
    public static function hasPPBSOnlyCustomizedData($id_customization, $id_product, $id_product_attribute, $id_cart)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('customized_data', 'cd');
        $sql->innerJoin('customization', 'c', 'cd.id_customization = c.id_customization');
        $sql->where('c.id_product = ' . (int)$id_product);
        $sql->where('c.id_product_attribute = ' . (int)$id_product_attribute);
        $sql->where('c.id_cart = ' . (int)$id_cart);
        $sql->where('cd.id_customization = ' . (int)$id_customization);
        $result = DB::getInstance()->executeS($sql);

        $ppbs_only = true;
        if (!empty($result)) {
            foreach ($result as $row) {
                if (empty($row['ppbs_dimensions']) && $row['index'] > 0) {
                    $ppbs_only = false;
                    break;
                }
            }
        }
        return $ppbs_only;
    }

    public static function getIDCustomizationField($id_product)
    {
        $sql = 'SELECT id_customization_field
				FROM ' . _DB_PREFIX_ . 'customization_field
				WHERE id_product = ' . (int)$id_product . '
				AND ppbs = 1';
        $row = DB::getInstance()->getRow($sql);
        if (isset($row['id_customization_field'])) {
            return $row['id_customization_field'];
        } else {
            return 0;
        }
    }

    /**]
     * Get the measuerments display string for the dimensions ion the cart
     * @param $cart_unit_collection
     */
    public static function getCustomizationDisplayText($cart_unit_collection)
    {
        $value = '';
        if (is_array($cart_unit_collection)) {
            foreach ($cart_unit_collection as $cart_unit) {
                if (!empty($cart_unit->display_value)) {
                    $value .= $cart_unit->display_name . ' : ' . $cart_unit->display_value . ' ' . $cart_unit->symbol . '. ';
                } else {
                    $value .= $cart_unit->display_name . ' : ' . $cart_unit->value . ' ' . $cart_unit->symbol . '. ';
                }
            }
        }
        return $value;
    }

    /**
     * Get customization field for the sample and create one if it does not exist
     * @param $id_product
     * @param $id_shop
     * @throws PrestaShopDatabaseException
     */
    public static function getCustomizationField($id_product, $id_shop)
    {
        $module = Module::getInstanceByName('productpricebysize');
        $languages = Language::getLanguages(false);
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('customization_field', 'cf');
        $sql->innerJoin('customization_field_lang', 'cfl', 'cfl.id_customization_field = cf.id_customization_field');
        $sql->where('id_product = ' . (int)$id_product);
        $sql->where('ppbs = 1');
        $sql->groupBy('cf.id_customization_field');
        $row = DB::getInstance()->getRow($sql);

        if (empty($row)) {
            DB::getInstance()->insert('customization_field', array(
                'id_product' => (int)$id_product,
                'type' => 1,
                'required' => 0,
                'is_module' => 1,
                'is_deleted' => 0,
                'ppbs' => 1
            ));
            $id_customization_field = Db::getInstance()->Insert_ID();
            foreach ($languages as $language) {
                DB::getInstance()->insert('customization_field_lang', array(
                    'id_customization_field' => (int)$id_customization_field,
                    'id_lang' => (int)$language['id_lang'],
                    'id_shop' => (int)$id_shop,
                    //'name' => pSQL(PPBSTranslationHelper::translate('dimensions', $language['iso_code'], '', false))
                    'name' => $module->l('dimensions', 'ppbscarthelper', $language['iso_code'])
                ));
            }
        } else {
            $id_customization_field = $row['id_customization_field'];
            foreach ($languages as $language) {
                DB::getInstance()->update('customization_field_lang', array(
                    'name' => pSQL($module->l('dimensions', 'ppbscarthelper', $language['iso_code']))
                ), 'id_customization_field = ' . (int)$id_customization_field);
            }
        }
        return $id_customization_field;
    }


    /**
     * Create customization for the cart with dimensions
     * @param $id_product
     * @param $id_cart
     * @param $ipa
     * @param $id_address_delivery
     * @param $cart_unit_collection
     * @param $quantity
     * @param int $id_shop
     * @param string $awp_vars
     * @return bool
     * @throws PrestaShopDatabaseException
     */
    public static function addCustomization($id_product, $id_cart, $ipa, $id_address_delivery, $cart_unit_collection, $id_module, $quantity, $id_shop = 1, $awp_vars = '')
    {
        if (!$cart_unit_collection) {
            return false;
        }

        if ($id_product == '') {
            return false;
        }

        if ($id_cart == '') {
            return false;
        }

        if ($quantity == 0) {
            $quantity = 1;
        }

        if (version_compare(_PS_VERSION_, '1.7.7.0', '<')) {
            $quantity = 0;
        }
        $quantity = 0;

        Db::getInstance()->insert('customization', array(
            'id_product_attribute' => (int)$ipa,
            'id_cart' => (int)$id_cart,
            'id_product' => (int)$id_product,
            'id_address_delivery' => (int)$id_address_delivery,
            'quantity' => (int)$quantity,
            'in_cart' => 1
        ));

        $id_customization = Db::getInstance()->Insert_ID();
        $value = self::getCustomizationDisplayText($cart_unit_collection);
        $id_customization_field = self::getCustomizationField($id_product, $id_shop);

        self::addCustomizedData($id_customization, $id_customization_field, $value, $id_module, $cart_unit_collection);

        Db::getInstance()->update(
            'cart_product',
            array(
                'quantity' => 1,
                'ppbs' => 1
            ),
            'id_cart=' . (int)$id_cart . '
			AND id_product = ' . (int)$id_product . '
			AND id_product_attribute = ' . (int)$ipa . '
			AND id_shop = ' . (int)$id_shop . '
			AND id_customization = ' . (int)$id_customization
        );
        return $id_customization;
    }

    /**
     * Add customized data entry
     * @param $id_customization
     * @param $index
     * @param $id_module
     * @param $cart_unit_collection
     */
    public static function addCustomizedData($id_customization, $index, $value, $id_module, $cart_unit_collection)
    {
        Db::getInstance()->insert(
            'customized_data',
            array(
                'id_customization' => (int)$id_customization,
                'type' => 1,
                'index' => (int)$index,
                'value' => pSQL($value),
                'ppbs_dimensions' => pSQL(self::rawJsonEncode($cart_unit_collection), true),
                'id_module' => (int)$id_module
            )
        );
    }

    /**
     * Update customized data entry
     * @param $id_customization
     * @param $index
     * @param $id_module
     * @param $cart_unit_collection
     */
    public static function updateCustomizedData($id_customization, $value, $cart_unit_collection)
    {
        Db::getInstance()->update(
            'customized_data',
            array(
                'value' => pSQL($value),
                'ppbs_dimensions' => pSQL(self::rawJsonEncode($cart_unit_collection), true)
            ),
            'id_customization = ' . (int)$id_customization
        );
    }


    public static function getAllCustomizationsByProduct($id_product, $id_product_attribute, $id_cart, $id_lang, $id_customization = 0)
    {
        $sql = new DbQuery();
        $sql->select('cd.id_module, cfl.name, cd.value, cd.type');
        $sql->from('customization', 'c');
        $sql->innerJoin('customized_data', 'cd', 'cd.id_customization = c.id_customization');
        $sql->leftJoin('customization_field_lang', 'cfl', 'cd.index = cfl.id_customization_field AND cfl.id_lang = ' . (int)$id_lang);
        $sql->where('c.id_product = ' . (int)$id_product);
        $sql->where('c.id_product_attribute = ' . (int)$id_product_attribute . ' OR (c.id_product_attribute = 0 AND c.id_product_attribute <> ' . (int)$id_product_attribute . ')');

        if ((int)$id_cart > 0) {
            $sql->where('c.id_cart = ' . (int)$id_cart);
        }


        if ($id_customization > 0) {
            $sql->where('cd.id_customization = ' . (int)$id_customization);
        }

        $result = Db::getInstance()->executeS($sql);
        return $result;
    }

    /**
     * Removes extra formatting from a numeric string and returns a float
     * @param $value
     */
    public static function formatToFloat($number)
    {
        if (substr_count($number, ',') == 1) {
            return (float)str_replace(',', '.', $number);
        }
        return (float)$number;
    }

    /**
     * Get the Multiplying total of all dimensions associated with a pro0duct in the cart
     * @param $id_product
     * @param $id_product_attribute
     * @param $id_customization
     * @param $id_cart
     * @param $id_shop
     * @return float|int
     */
    public static function getProductTotalArea($id_product, $id_product_attribute, $id_customization, $id_cart, $id_shop)
    {
        $cart_unit_collection = PPBSProductHelper::getCartProductUnits(
            $id_product,
            $id_cart,
            $id_product_attribute,
            $id_shop,
            $id_customization
        );

        if (empty($cart_unit_collection)) {
            return 0;
        }

        if ($id_customization > 0) {
            $total_area = 1;
            $cart_units = json_decode($cart_unit_collection[0]['ppbs_dimensions']);
            foreach ($cart_units as $cart_unit) {
                $total_area = $total_area * $cart_unit->value;
            }
        } else {
            $total_area = 0;
            foreach ($cart_unit_collection as $cart_unit) {
                $cart_units = json_decode($cart_unit['ppbs_dimensions']);
                $item_total_area = 1;
                foreach ($cart_units as $ppbs_dimensions) {
                    $item_total_area = $item_total_area * $ppbs_dimensions->value;
                }
                $total_area = $total_area + $item_total_area;
            }
        }
        return $total_area;
    }

    /**
     * Get total weight of taking into account pro0ducts with dimensions
     * @param $products
     * @param $id_cart
     * @return int
     */
    public static function getTotalWeight($products, $id_cart, $with_qty = false)
    {
        $total_weight = 0;
        $cart = new Cart($id_cart);

        foreach ($products as $product) {
            $id_product = $product['id_product'];
            $id_product_attribute = $product['id_product_attribute'];
            $id_customization = $product['id_customization'];

            $ppbs_product = new PPBSProduct();
            $ppbs_product->getByProduct($id_product);

            $math_params = array();
            $product_weight = $product['weight'];
            $product_area = self::getProductTotalArea($product['id_product'], $product['id_product_attribute'], $product['id_customization'], $id_cart, $cart->id_shop);
            $math_params['total_area'] = $product_area;
            $math_params['product_weight'] = $product_weight;

            $equation = PPBSEquationTemplateHelper::getEquationInfoForProduct($id_product, $id_product_attribute, true, 'weight');

            if ($ppbs_product->weight_calculation_enabled && !empty($equation['equation'])) {
                $cart_unit_collection = PPBSProductHelper::getCartProductUnits(
                    $id_product,
                    $id_cart,
                    $id_product_attribute,
                    $cart->id_shop,
                    $id_customization
                );

                foreach ($cart_unit_collection as $cart_units) {
                    $cart_units = json_decode($cart_units['ppbs_dimensions']);
                    foreach ($cart_units as $cart_unit) {
                        $ppbs_dimension = new PPBSDimension($cart_unit->id_ppbs_dimension);
                        $math_params[$ppbs_dimension->name] = $cart_unit->value;
                    }
                }
                $total_weight += PPBSMathEval::computeEquation($equation['equation'], $math_params);
            } else {
                if ($product_area > 0) {
                    $total_weight += $product_weight * $product_area;
                } else {
                    $total_weight += $product_weight;
                }
            }
            if ($with_qty) {
                $total_weight *= $product['quantity'];
            }
        }
        return $total_weight;
    }

    /**
     * Increment Product customization quantity by 1
     * @param $id_product
     * @param $id_product_attribute
     * @param $id_customization
     * @param $id_cart
     */
    public static function incrementProductCustomizationQuantity($id_product, $id_product_attribute, $id_customization, $id_cart)
    {
        $sql = 'UPDATE ' . _DB_PREFIX_ . 'customization SET quantity = quantity + 1 WHERE id_cart = ' . (int)$id_cart . ' AND id_product = ' . (int)$id_product . ' AND id_product_attribute = ' . (int)$id_product_attribute . ' AND id_customization = ' . (int)$id_customization;
        DB::getInstance()->execute($sql);
    }

    /**
     * Decrement Product customization quantity by 1
     * @param $id_product
     * @param $id_product_attribute
     * @param $id_customization
     * @param $id_cart
     */
    public static function decrementProductCustomizationQuantity($id_product, $id_product_attribute, $id_customization, $id_cart)
    {
        $sql = 'UPDATE ' . _DB_PREFIX_ . 'customization SET quantity = quantity - 1 WHERE quantity > 1 AND id_cart = ' . (int)$id_cart . ' AND id_product = ' . (int)$id_product . ' AND id_product_attribute = ' . (int)$id_product_attribute . ' AND id_customization = ' . (int)$id_customization;
        DB::getInstance()->execute($sql);
    }

    /**
     * @param $product
     * @param $id_cart
     * @return float|int
     */
    public static function getOrderDetailProductWeight($product, $id_cart)
    {
        $cart = new Cart($id_cart);
        $id_product = $product['id_product'];
        $id_product_attribute = $product['id_product_attribute'];
        $id_customization = $product['id_customization'];
        $math_params = array();

        if ((int)$product['id_product_attribute'] > 0 && $product['weight_attribute'] > 0) {
            $product_weight = $product['weight_attribute'];
        } else {
            $product_weight = $product['weight'];
        }

        $ppbs_product = new PPBSProduct();
        $ppbs_product->getByProduct($id_product);

        $math_params['total_area'] = self::getProductTotalArea($product['id_product'], $product['id_product_attribute'], $product['id_customization'], $id_cart, $cart->id_shop);
        $math_params['product_weight'] = $product_weight;
        $equation = PPBSEquationTemplateHelper::getEquationInfoForProduct($id_product, $id_product_attribute, true, 'weight');

        $weight = 0;
        if ($ppbs_product->weight_calculation_enabled && !empty($equation['equation'])) {
            $cart_unit_collection = PPBSProductHelper::getCartProductUnits(
                $id_product,
                $id_cart,
                $id_product_attribute,
                $cart->id_shop,
                $id_customization
            );

            foreach ($cart_unit_collection as $cart_units) {
                $cart_units = json_decode($cart_units['ppbs_dimensions']);
                foreach ($cart_units as $cart_unit) {
                    $ppbs_dimension = new PPBSDimension($cart_unit->id_ppbs_dimension);
                    $math_params[$ppbs_dimension->name] = $cart_unit->value;
                }
            }
            $weight = (PPBSMathEval::computeEquation($equation['equation'], $math_params)) * $product['cart_quantity'];
        }
        return $weight;
    }

    /**
     * @param $value
     * @param $precision
     * @return float|int
     */
    private static function _roundUp($value, $precision)
    {
        $pow = pow(10, $precision);
        return (ceil($pow * $value) + ceil($pow * $value - ceil($pow * $value))) / $pow;
    }

    /**
     * @param $number
     * @return mixed
     */
    private static function _removeNumberFormatting($number)
    {
        if (substr_count($number, ',') == 1) {
            return str_replace(',', '.', $number);
        }
        return $number;
    }

    /**
     * @param $price
     * @param $amount
     * @param $type
     * @return float|int
     */
    private static function _applyDiscount($price, $amount, $type)
    {
        if ($type == 'percentage') {
            $price = $price - ($price * ($amount / 100));
        }

        if ($type == 'amount') {
            $price = $price - $amount;
        }
        return $price;
    }

    /**
     * Get Total Area
     * @param $cart_units
     * @param $ppbs_product
     * @return float|int|mixed
     */
    private static function getTotalArea($cart_units, $ppbs_product)
    {
        $multiplier = 1;
        $total_area = 0;
        $unit_value = 0;

        foreach ($cart_units as $key => $unit) {
            $unit->value = self::_removeNumberFormatting($unit->value);
            $unit_value = $unit->value;

            if ($ppbs_product->front_conversion_enabled == 1) {
                switch ($ppbs_product->front_conversion_operator) {
                    case '/':
                        if ($ppbs_product->front_conversion_value == 0) {
                            $ppbs_product->front_conversion_value = 1;
                        }
                        $unit_value = $unit_value / $ppbs_product->front_conversion_value;
                        break;
                    case '*':
                        if ($ppbs_product->front_conversion_value == 0) {
                            $ppbs_product->front_conversion_value = 1;
                        }
                        $unit_value = $unit_value * $ppbs_product->front_conversion_value;
                        break;
                }
            } else {
                $unit_value = $unit->value;
            }

            if ((float)$unit_value > 0) {
                $total_area = $total_area * $unit_value;
                $multiplier = $multiplier * $unit_value;
            }
            if ($total_area == 0) {
                $total_area = $unit_value;
            }
        }
        return $total_area;
    }

    /**
     * Get Price based on area
     * @param $id_product
     * @param $total_area
     * @param $adjustedPrice
     * @param $group_reduction
     * @param $specific_price
     * @param $product_tax_calculator
     * @param $params
     * @param $is_customization_line
     * @return float
     */
    private static function getAreaPrice($id_product, $total_area, $adjustedPrice, $group_reduction, $product_tax_calculator, $params, $is_customization_line)
    {
        if ($total_area > 0) {
            $total_area = self::_roundUp($total_area, 2);
            $areaPrice = PPBSProductHelper::getAreaPriceByArea((int)$id_product, $total_area, Context::getContext()->shop->id);

            if ($areaPrice) {
                if ($areaPrice->impact == '-') {
                    if (!empty($params['use_tax'])) {
                        $adjustedPrice = $adjustedPrice - self::_addTax($product_tax_calculator, 1, $areaPrice->price, $is_customization_line);
                    } else {
                        $adjustedPrice = $adjustedPrice - $areaPrice->price;
                    }
                } else {
                    if ($areaPrice->impact == '+') {
                        $adjustedPrice = $adjustedPrice + (float)$areaPrice->price;
                    }
                    if ($areaPrice->impact == '*') {
                        $adjustedPrice = $adjustedPrice * (float)$areaPrice->price;
                    }
                    if ($areaPrice->impact == '=' || $areaPrice->impact == '~') {
                        $adjustedPrice = (float)$areaPrice->price;
                    }

                    if (isset($params['specific_price'])) {
                        $specific_price = $params['specific_price'];
                        if (isset($specific_price['reduction']) && isset($specific_price['reduction_type'])) {
                            $adjustedPrice = self::_applyDiscount($adjustedPrice, $specific_price['reduction'], $specific_price['reduction_type']);
                        }
                    }
                }
            }
        }
        return $adjustedPrice;
    }


    /**
     * Apply specific price
     * @param $original_price
     * @param $params
     * @param $product_tax_calculator
     * @return float|int
     */
    private static function applySpecificPrice($original_price, $params, $product_tax_calculator, $is_attribute = true)
    {
        $new_price = $original_price;
        if (isset($params['specific_price']['price'])) {
            $specific_price = $params['specific_price'];
            $price = $specific_price['price'];
            if (isset($specific_price['reduction']) && isset($specific_price['reduction_type'])) {
                $reduction = $specific_price['reduction'];

                if ($specific_price['reduction_type'] == 'amount') {
                    if ($specific_price['reduction_tax'] == 1) {
                        $reduction = $product_tax_calculator->removeTaxes($specific_price['reduction']);
                    } else {
                        $reduction = $specific_price['reduction'];
                    }
                }

                if ($reduction == 0 && $price > 0) {
                    $new_price = $price;
                    if ($is_attribute) {
                        $new_price = 0;
                    }
                } else {
                    if ($specific_price['reduction_type'] == 'percentage' && $reduction < 1) {
                        $reduction = $reduction * 100;
                    } elseif ($specific_price['reduction_type'] == 'amount') {
                    }
                    $new_price = self::_applyDiscount($original_price, $reduction, $specific_price['reduction_type']);
                }
            }
        }
        return $new_price;
    }

    public static function _addTax($taxManager, $use_tax, $price, $is_cusomization_line = 0)
    {
        if ($use_tax) {
            return $taxManager->addTaxes($price);
        } else {
            return $price;
        }
    }

    public static function calculateCustomizationPrice($customization_data, $params, $customer = null, $is_customization_line = false, $use_cache = false)
    {
        static $address = null;
        static $context = null;

        $group_reduction = 0;
        $id_shop = (empty($params['id_shop']) ? Context::getContext()->shop->id : $params['id_shop']);
        $id_product = $params['id_product'];
        $id_product_attribute = $params['id_product_attribute'];
        $id_cart = (int)Context::getContext()->cart->id;
        $id_customization = (int)$params['id_customization'];
        $line_quantity = $params['quantity'];
        $specific_price = $params['specific_price'];
        $cart_unit_collection = $customization_data;

        $cache_id = 'PPBSFrontCartController::calculateCustomizationPrice_' . $id_product . '-' . $id_product_attribute . '-' . $id_customization . '-' . $id_cart . '-' . (int)$params['use_tax'];

        if ($use_cache) {
            if (Cache::isStored($cache_id)) {
                return Tools::ps_round(Cache::retrieve($cache_id), 6);
            }
        }

        if (empty($customer)) {
            Context::getContext()->customer;
        }

        if (!empty($customer->id_default_group)) {
            $id_group = $customer->id_default_group;
            $customer_group = new Group($id_group);
            $group_reduction = $customer_group->reduction;
        }

        /* set up tax calculator */
        if ($address === null) {
            $address = new Address();
        }
        $address->id_country = $params['id_country'];
        $address->id_state = $params['id_state'];
        $address->postcode = $params['zipcode'];

        $tax_manager = TaxManagerFactory::getManager($address, Product::getIdTaxRulesGroupByIdProduct((int)$id_product, $context));
        $product_tax_calculator = $tax_manager->getTaxCalculator();

        $total_area = 0;
        $product = new Product($id_product, null, null, $id_shop);
        $price_base = $product->price;

        $attribute_price = PPBSProductHelper::getProductAttributePrice($id_product, $id_shop, $id_product_attribute);
        $attribute_price = (float)$attribute_price['attribute_price'];

        $ppbs_product = new PPBSProduct();

        $ppbs_product->getByProduct($id_product);
        $equation = PPBSEquationTemplateHelper::getEquationInfoForProduct($id_product, $id_product_attribute, true, 'price');

        if (empty($ppbs_product->id) || $ppbs_product->enabled == 0) {
            return $params['price'];
        }
        $unit_total = 0.00;

        if (empty($cart_unit_collection)) {
            return $params['price'];
        }

        /* Custom Calculation */
        if ($ppbs_product->equation_enabled && !empty($equation['equation'])) {
            $running_total = 0;
            foreach ($cart_unit_collection as $cart_units) {
                $cart_units = json_decode($cart_units['ppbs_dimensions']);
                $cart_units_tmp = $cart_units;

                if (!is_array($cart_units_tmp) || empty($cart_units_tmp)) {
                    continue;
                }

                $total_area = self::getTotalArea($cart_units_tmp, $ppbs_product);
                $area_price = self::getAreaPrice($id_product, $total_area, $product->price + $attribute_price, $group_reduction, $product_tax_calculator, array('use_tax' => 0), $is_customization_line);

                if ($total_area < $ppbs_product->min_total_area) {
                    $total_area = $ppbs_product->min_total_area;
                }

                $math_params = array();
                $global_variables = array();
                $math_params['product_price'] = $price_base + $attribute_price;
                $math_params['base_price'] = $price_base;
                $math_params['attribute_price'] = $attribute_price;
                $math_params['quantity'] = $line_quantity;
                $math_params['area_price'] = $area_price;
                $math_params['total_area'] = $total_area;
                $math_params['pco_price_impact'] = $params['pco_price_impact'];

                $global_variables = PPBSEquationTemplateHelper::getVariables();
                if (!empty($global_variables)) {
                    foreach ($global_variables as $global_variable) {
                        $math_params[$global_variable['name']] = (float)$global_variable['value'];
                    }
                }

                foreach ($cart_units as $cart_unit) {
                    $ppbs_dimension = new PPBSDimension($cart_unit->id_ppbs_dimension);
                    $math_params[$ppbs_dimension->name] = $cart_unit->value;
                }

                $unit_total = PPBSMathEval::computeEquation($equation['equation'], $math_params);

                if ($group_reduction > 0) {
                    $unit_total = self::_applyDiscount($unit_total, $group_reduction, 'percentage');
                }
                $unit_total = self::applySpecificPrice($unit_total, $params, $product_tax_calculator, false);
                $running_total += $unit_total;
            }
            $running_total = $running_total + $ppbs_product->setup_fee;

            // Impose min price if set
            if ($running_total < $product_tax_calculator->addTaxes($ppbs_product->min_price, $is_customization_line)) {
                $total = self::_addTax($product_tax_calculator, $params['use_tax'], $ppbs_product->min_price, $is_customization_line);
            } else {
                $total = self::_addTax($product_tax_calculator, $params['use_tax'], $running_total, $is_customization_line);
            }

            Cache::store($cache_id, $total);
            return Tools::ps_round($total, 6);
        }

        /* normal linear calculation */
        foreach ($cart_unit_collection as $cart_units) {
            $cart_units = json_decode($cart_units['ppbs_dimensions']);
            $multiplier = 1;

            if (is_array($cart_units)) {
                $total_area = self::getTotalArea($cart_units, $ppbs_product);
                $total_area_qty = $total_area * $params['quantity'];

                if ($total_area < $ppbs_product->min_total_area && $ppbs_product->min_total_area) {
                    $total_area = $ppbs_product->min_total_area;
                }

                $multiplier = $total_area;

                $price_base = self::applySpecificPrice($price_base, $params, $product_tax_calculator, false);
                $attribute_price = self::applySpecificPrice($attribute_price, $params, $product_tax_calculator, true);

                /* use base price to calculate area, and add attributes price to that total */
                if (!$ppbs_product->attribute_price_as_area_price) {
                    $adjustedPrice = $price_base;
                } else {
                    $adjustedPrice = $price_base + $attribute_price;
                }

                /* Adjust price based on area */
                if ($total_area_qty > 0) {
                    $total_area_qty = self::_roundUp($total_area_qty, 2);
                    $areaPrice = PPBSProductHelper::getAreaPriceByArea((int)$id_product, $total_area_qty, (int)$id_shop);

                    if ($areaPrice) {
                        if ($areaPrice->impact == '-') {
                            //$adjustedPrice = $adjustedPrice - self::_addTax($product_tax_calculator, !$params['use_tax'], $areaPrice->price, $is_customization_line);
                            $adjustedPrice = $adjustedPrice - $areaPrice->price;
                        } else {
                            if ($areaPrice->impact == '+') {
                                $adjustedPrice = $adjustedPrice + (float)$areaPrice->price;
                            }

                            if ($areaPrice->impact == '*') {
                                $adjustedPrice = $adjustedPrice * (float)$areaPrice->price;
                            }

                            if ($areaPrice->impact == '=') {
                                $adjustedPrice = (float)$areaPrice->price;
                            }

                            if ($areaPrice->impact == '~') {
                                $adjustedPrice = (float)$areaPrice->price;
                            }
                            $adjustedPrice = self::applySpecificPrice($adjustedPrice, $params, $product_tax_calculator);
                        }
                    }
                }
                if (isset($areaPrice->impact) && $areaPrice->impact == '=') {
                    $unit_total += ($adjustedPrice + $attribute_price);
                } else {
                    if (!$ppbs_product->attribute_price_as_area_price) {
                        $unit_total += (($adjustedPrice * $multiplier) + $attribute_price);
                    } else {
                        /*if (!empty($areaPrice)) {
                            $adjustedPrice = $adjustedPrice + $attribute_price;
                        }*/
                        $customisation_total = ($adjustedPrice * $multiplier);
                        if ($customisation_total < $ppbs_product->min_price) {
                            $unit_total += $ppbs_product->min_price;
                        } else {
                            $unit_total += ($adjustedPrice * $multiplier);
                        }
                    }
                }
            }
        }

        $priceFormatter = new PriceFormatter();
        $unit_total = $priceFormatter->convertAmount((float)$unit_total);

        if ($group_reduction > 0) {
            $unit_total = self::_applyDiscount($unit_total, $group_reduction, 'percentage');
        }

        // Add setup fees set
        if ($ppbs_product->setup_fee > 0) {
            $unit_total = $unit_total + $ppbs_product->setup_fee;
        }

        // Impose min price if set
        if ($unit_total < $ppbs_product->min_price) {
            $unit_total = $ppbs_product->min_price;
            $unit_total = self::_addTax($product_tax_calculator, $params['use_tax'], $unit_total);
        } else {
            $unit_total = self::_addTax($product_tax_calculator, $params['use_tax'], $unit_total);
        }

        Cache::store($cache_id, $unit_total);
        return Tools::ps_round($unit_total, 6);
    }

    /**
     * Get the current quantity of a product line in the cart
     * @param $id_product
     * @param $id_product_attribute
     * @param $id_customization
     * @param $id_cart
     * @return int
     */
    public static function getProductQty($id_product, $id_product_attribute, $id_customization, $id_cart)
    {
        $sql = new DbQuery();
        $sql->select('quantity');
        $sql->from('cart_product');
        $sql->where('id_product = ' . (int)$id_product);
        $sql->where('id_product_attribute = ' . (int)$id_product_attribute);
        $sql->where('id_customization = ' . (int)$id_customization);
        $sql->where('id_cart = ' . (int)$id_cart);
        $qty = Db::getInstance()->getValue($sql);

        if (!empty($qty)) {
            return $qty;
        } else {
            return 0;
        }
    }

    /**
     * Determine if a line item in the cart is a product price by size product
     * @param $id_cart
     * @param $id_product
     * @param $id_product_attribute
     * @param $id_customization
     */
    public static function isCartProductPPBS($id_cart, $id_product, $id_product_attribute, $id_customization)
    {
        if ($id_customization == 0) {
            return false;
        }

        $cache_id = 'isCartProductPPBS::' . (int)$id_product . '-' . (int)$id_product_attribute . '-' . (int)$id_customization . '-' . (int)$id_cart;
        if (!isset(self::$_cache[$cache_id])) {
            $sql = new DbQuery();
            $sql->select('ppbs_dimensions');
            $sql->from('customized_data', 'cd');
            $sql->innerJoin('customization', 'c', 'cd.id_customization = c.id_customization');
            $sql->where('c.id_cart = ' . (int)$id_cart);
            $sql->where('c.id_product = ' . (int)$id_product);

            if ($id_product_attribute > 0) {
                $sql->where('c.id_product_attribute = ' . (int)$id_product_attribute);
            }
            $sql->where('cd.id_customization = ' . (int)$id_customization);
            $row = Db::getInstance()->getRow($sql);

            if (empty($row['ppbs_dimensions'])) {
                self::$_cache[$cache_id] = '';
                return false;
            } else {
                self::$_cache[$cache_id] = $row['ppbs_dimensions'];
                return true;
            }
        } else {
            if (empty(self::$_cache[$cache_id])) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * get a list of products not in stock (stock by area) which have stock management enabled
     * @param $id_cart
     * @param $id_shop
     * @return array
     */
    public static function getCartOOSProducts($id_cart, $id_shop)
    {
        $oos_products = array();
        $cart = Context::getContext()->cart;
        $products = $cart->getProducts();
        foreach ($products as $product) {
            $ppbs_product = new PPBSProduct();
            $ppbs_product->loadByProduct($product['id_product']);
            if ($ppbs_product->stock_enabled) {
                $qty_stock = PPBSStockHelper::getStock($product['id_product'], $product['id_product_attribute'], $id_shop);
                $product_area = $product['cart_quantity'] * PPBSCartHelper::getProductTotalArea($product['id_product'], $product['id_product_attribute'], $product['id_customization'], $id_cart, $id_shop);
                if ($product_area > $qty_stock) {
                    $oos_products[] = $product;
                }
            }
        }
        return $oos_products;
    }
}
