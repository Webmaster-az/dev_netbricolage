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

class PPBSMassAssignHelper
{

    /**
     * Copied from Product class
     * Get an id_product_attribute by an id_product and one or more
     * id_attribute.
     *
     * e.g: id_product 8 with id_attribute 4 (size medium) and
     * id_attribute 5 (color blue) returns id_product_attribute 9 which
     * is the dress size medium and color blue.
     *
     * @param int $idProduct
     * @param int|int[] $idAttributes
     * @param bool $findBest
     *
     * @return int
     *
     * @throws PrestaShopException
     */
    public static function getIdProductAttributeByIdAttributes($idProduct, $idAttributes, $findBest = false)
    {
        $idProduct = (int)$idProduct;

        if (!is_array($idAttributes) && is_numeric($idAttributes)) {
            $idAttributes = array((int)$idAttributes);
        }

        if (!is_array($idAttributes) || empty($idAttributes)) {
            throw new PrestaShopException(
                sprintf(
                    'Invalid parameter $idAttributes with value: "%s"',
                    print_r($idAttributes, true)
                )
            );
        }

        $idAttributesImploded = implode(',', array_map('intval', $idAttributes));
        $idProductAttribute = Db::getInstance()->getValue(
            '
            SELECT
                pac.`id_product_attribute`
            FROM
                `' . _DB_PREFIX_ . 'product_attribute_combination` pac
                INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON pa.id_product_attribute = pac.id_product_attribute
            WHERE
                pa.id_product = ' . $idProduct . '
                AND pac.id_attribute IN (' . $idAttributesImploded . ')
            GROUP BY
                pac.`id_product_attribute`
            HAVING
                COUNT(pa.id_product) = ' . count($idAttributes)
        );


        if ($idProductAttribute === false && $findBest) {
            //find the best possible combination
            //first we order $idAttributes by the group position
            $orderred = array();
            $result = Db::getInstance()->executeS(
                '
                SELECT
                    a.`id_attribute`
                FROM
                    `' . _DB_PREFIX_ . 'attribute` a
                    INNER JOIN `' . _DB_PREFIX_ . 'attribute_group` g ON a.`id_attribute_group` = g.`id_attribute_group`
                WHERE
                    a.`id_attribute` IN (' . $idAttributesImploded . ')
                ORDER BY
                    g.`position` ASC'
            );

            foreach ($result as $row) {
                $orderred[] = $row['id_attribute'];
            }

            while ($idProductAttribute === false && count($orderred) > 0) {
                array_pop($orderred);
                $idProductAttribute = Db::getInstance()->getValue(
                    '
                    SELECT
                        pac.`id_product_attribute`
                    FROM
                        `' . _DB_PREFIX_ . 'product_attribute_combination` pac
                        INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON pa.id_product_attribute = pac.id_product_attribute
                    WHERE
                        pa.id_product = ' . (int)$idProduct . '
                        AND pac.id_attribute IN (' . implode(',', array_map('intval', $orderred)) . ')
                    GROUP BY
                        pac.id_product_attribute
                    HAVING
                        COUNT(pa.id_product) = ' . count($orderred)
                );
            }
        }
        return $idProductAttribute;
    }

    /**
     * @param $id_product_old
     * @param $id_product
     */
    public static function duplicateProduct($id_product_old, $id_product, $id_shop)
    {
        $product_old = new Product($id_product_old);
        $combinations_old = $product_old->getAttributeCombinations(Context::getContext()->language->id);

        $combos_old = array();
        foreach ($combinations_old as $combination) {
            $combos_old[$combination['id_product_attribute']][] = $combination['id_attribute'];
        }

        // Map old IPA to new IPA to allow equations to be copied

        $combo_map = array();
        foreach ($combos_old as $key => $combo_old) {
            if (is_array($combo_old)) {
                $find_best = true;
                if (count($combo_old) <= 1) {
                    $find_best = false;
                }
                $find_best = false;
                $combo_map[$key] = PPBSMassAssignHelper::getIdProductAttributeByIdAttributes($id_product, $combo_old, $find_best) . "<br>";
            }
        }

        $ppbs_product_model = new PPBSProduct();
        $ppbs_product_model->getByProduct($id_product_old);

        // PPBS Product
        if (!empty($ppbs_product_model->id)) {
            $ppbs_product_model->id = 0;
            $ppbs_product_model->id_ppbs_product = 0;
            $ppbs_product_model->id_product = $id_product;
            $ppbs_product_model->add();
        }

        // PPBS Area Prices
        $ppbs_area_price_model = new PPBSAreaPrice();
        $area_prices = $ppbs_area_price_model->getCollectionByProduct($id_product_old, $id_shop);
        if (!empty($area_prices)) {
            foreach ($area_prices as $area_price) {
                $ppbs_area_price_new = new PPBSAreaPrice();
                $ppbs_area_price_new->id_product = $id_product;
                $ppbs_area_price_new->id_shop = $area_price['id_shop'];
                $ppbs_area_price_new->area_low = $area_price['area_low'];
                $ppbs_area_price_new->area_high = $area_price['area_high'];
                $ppbs_area_price_new->impact = $area_price['impact'];
                $ppbs_area_price_new->price = $area_price['price'];
                $ppbs_area_price_new->weight = $area_price['weight'];
                $ppbs_area_price_new->add();
            }
        }

        //PPBS Equation
        $ppbs_equation_model = new PPBSEquation();
        $equations = $ppbs_equation_model->getAllByProduct($id_product_old);

        if (!empty($equations)) {
            foreach ($equations as $equation) {
                if (!empty($combo_map[$equation->ipa])) {
                    $ipa_new = $combo_map[$equation->ipa];
                } else {
                    $ipa_new = $equation->ipa;
                }
                $equation_new = new PPBSEquation();
                $equation_new->id_product = $id_product;
                $equation_new->ipa = $ipa_new;
                $equation_new->equation = $equation->equation;
                $equation_new->id_equation_template = $equation->id_equation_template;
                $equation_new->add();
            }
        }

        //PPBS Product Fields
        $ppbs_product_field_model = new PPBSProductField();
        $product_fields = $ppbs_product_field_model->getRawCollectionByProduct($id_product_old);

        if (!empty($product_fields)) {
            foreach ($product_fields as $product_field) {
                $product_field_new = new PPBSProductField();
                $product_field_new->id_product = $id_product;
                $product_field_new->id_ppbs_dimension = $product_field['id_ppbs_dimension'];
                $product_field_new->id_ppbs_unit = $product_field['id_ppbs_unit'];
                $product_field_new->min = $product_field['min'];
                $product_field_new->max = $product_field['max'];
                $product_field_new->default = $product_field['default'];
                $product_field_new->decimals = $product_field['decimals'];
                $product_field_new->input_type = $product_field['input_type'];
                $product_field_new->visible = $product_field['visible'];
                $product_field_new->display_suffix = $product_field['display_suffix'];
                $product_field_new->position = $product_field['position'];
                $product_field_new->add();

                $id_ppbs_product_field = $product_field_new->id;

                $ppbs_product_field_option_model = new PPBSProductFieldOption();
                $product_field_options = $ppbs_product_field_option_model->getFieldOptions($product_field['id_ppbs_product_field']);

                if (!empty($product_field_options) && !empty($id_ppbs_product_field)) {
                    foreach ($product_field_options as $product_field_option) {
                        $ppbs_product_field_option_new = new PPBSProductFieldOption();
                        $ppbs_product_field_option_new->id_ppbs_product_field = $id_ppbs_product_field;
                        $ppbs_product_field_option_new->text = $product_field_option['text'];
                        $ppbs_product_field_option_new->value = $product_field_option['value'];
                        $ppbs_product_field_option_new->position = $product_field_option['position'];
                        $ppbs_product_field_option_new->add();
                    }
                }
            }
        }

        // Unit conversion options
        PPBSProductUnitConversionHelper::deleteByProduct($id_product);
        $product_unit_conversions = PPBSProductUnitConversionHelper::getByProduct($id_product_old);
        if (!empty($product_unit_conversions)) {
            foreach ($product_unit_conversions as $product_unit_conversion) {
                $product_unit_conversion_model = new PPBSProductUnitConversion();
                $product_unit_conversion_model->id_product = (int)$id_product;
                $product_unit_conversion_model->id_ppbs_unit = (int)$product_unit_conversion['id_ppbs_unit'];
                $product_unit_conversion_model->default = (int)$product_unit_conversion['default'];
                $product_unit_conversion_model->position = (int)$product_unit_conversion['position'];
                $product_unit_conversion_model->add();
            }
        }
    }

    /**
     * delete all module settings for a specific product
     * @param $id_product
     */
    public static function deleteAllSettingsByProduct($id_product)
    {
        PPBSAreaPrice::deleteByProduct($id_product);
        PPBSEquation::deleteByProduct($id_product);
        PPBSProduct::deleteByProduct($id_product);
        PPBSStockHelper::deleteByProduct($id_product);

        $product_fields = PPBSProductField::getCollectionByProduct($id_product);
        if (!empty($product_fields)) {
            foreach ($product_fields as $product_field) {
                PPBSProductFieldHelper::delete($product_field['id_ppbs_product_field']);
            }
        }
    }
}
