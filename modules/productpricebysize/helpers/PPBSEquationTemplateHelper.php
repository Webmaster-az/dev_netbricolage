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

class PPBSEquationTemplateHelper
{

    protected static $table_name = 'ppbs_equation_template';

    /**
     * Get array of all equation templates
     * @param $equation_type (price|weight)_
     * @return array|bool|false|mysqli_result|PDOStatement|resource
     * @throws PrestaShopDatabaseException
     */
    public static function getAll($equation_type = '')
    {
        $sql = new DbQuery();
        $sql->select('et.*');
        $sql->from(self::$table_name, 'et');
        $sql->orderBy('name');
        if ($equation_type == 'price' || $equation_type == 'weight') {
            $sql->leftJoin('ppbs_equation', 'e', 'e.id_equation_template = et.id_equation_template AND e.equation_type = "'.pSQL($equation_type).'"');
        }
        $sql->groupBy('id_equation_template');
        $result = Db::getInstance()->executeS($sql);

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * Determine if a equation template name is unique
     * @param $name
     * @return bool
     */
    public static function templateNameIsUnique($name)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(self::$table_name);
        $sql->where('name LIKE "' . pSQL($name) . '"');
        $row = Db::getInstance()->getRow($sql);

        if (empty($row)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get equation information for a product / pro0duct IPA
     * @param $id_product
     * @param $id_product_attribute
     * @param bool $fallback
     * @param $equation_type
     * @return array
     */
    public static function getEquationInfoForProduct($id_product, $id_product_attribute, $fallback = false, $equation_type = '')
    {
        $sql = new DbQuery();
        $sql->select('e.*, et.equation AS equation_template');
        $sql->from('ppbs_equation', 'e');
        $sql->where('e.id_product = ' . (int)$id_product);
        $sql->where('e.ipa = ' . (int)$id_product_attribute);
        if ($equation_type == 'price' || $equation_type == 'weight') {
            $sql->where('e.equation_type = "' . pSQL($equation_type).'"');
        }

        $sql->leftJoin(self::$table_name, 'et', 'e.id_equation_template = et.id_equation_template');
        $row = DB::getInstance()->getRow($sql);

        if (empty($row) && $fallback) {
            $sql = new DbQuery();
            $sql->select('e.*, et.equation AS equation_template');
            $sql->from('ppbs_equation', 'e');
            $sql->where('e.id_product = ' . (int)$id_product);
            $sql->where('e.ipa = 0 ');
            if ($equation_type == 'price' || $equation_type == 'weight') {
                $sql->where('e.equation_type = "' . pSQL($equation_type) . '"');
            }
            $sql->leftJoin(self::$table_name, 'et', 'e.id_equation_template = et.id_equation_template');
            $row = DB::getInstance()->getRow($sql);
        }

        if (empty($row)) {
            return false;
        }

        $return = array(
            'id_equation' => (empty($row['id_equation']) ? 0 : $row['id_equation']),
            'id_equation_template' => (empty($row['id_equation_template']) ? 0 : $row['id_equation_template']),
            'id_product' => (empty($row['id_product']) ? 0 : $row['id_product']),
            'ipa' => (empty($row['ipa']) ? 0 : $row['ipa']),
            'equation' => (empty($row['equation']) ? $row['equation_template'] : $row['equation']),
            'equation_template' => (empty($row['equation_template']) ? 0 : $row['equation_template']),
            'equation_type' => (empty($row['equation_type']) ? 'price' : $row['equation_type'])
        );
        return $return;
    }

    /**
     * Get all equations for a product
     * @param $id_product
     * @param string $equation_type
     * @return array
     * @throws PrestaShopDatabaseException
     */
    public static function getAllEquationInfoForProduct($id_product, $equation_type = '')
    {
        $return = array();
        $sql = new DbQuery();
        $sql->select('e.*, et.equation AS equation_template');
        $sql->from('ppbs_equation', 'e');
        $sql->where('e.id_product = ' . (int)$id_product);

        if ($equation_type != '') {
            $sql->where('e.equation_type = "' . pSQL($equation_type) . '"');
        }

        $sql->leftJoin(self::$table_name, 'et', 'e.id_equation_template = et.id_equation_template');
        $result = DB::getInstance()->executeS($sql);

        foreach ($result as $row) {
            $return[] = array(
                'id_equation' => (empty($row['id_equation']) ? 0 : $row['id_equation']),
                'id_equation_template' => (empty($row['id_equation_template']) ? 0 : $row['id_equation_template']),
                'id_product' => (empty($row['id_product']) ? 0 : $row['id_product']),
                'ipa' => (empty($row['ipa']) ? 0 : $row['ipa']),
                'equation' => (empty($row['equation']) ? '' : $row['equation']),
                'equation_template' => (empty($row['equation_template']) ? 0 : $row['equation_template'])
            );
        }
        return $return;
    }

    /**
     * Delete equation entry for a product
     * @param $id_product
     * @param $id_product_attribute
     * @param $equation_type
     */
    public static function deleteTemplateProduct($id_product, $id_product_attribute, $equation_type)
    {
        DB::getInstance()->delete('ppbs_equation', 'id_product = ' . (int)$id_product . ' AND ipa = ' . (int)$id_product_attribute.' AND equation_type LIKE "' . pSQL($equation_type) . '"');
    }

    /**
     * Delete an an equation template and optionally all associated products
     * @param $id_equation_template
     * @param $delete_product_associations
     */
    public static function delete($id_equation_template, $delete_product_associations = true)
    {
        if ($delete_product_associations) {
            DB::getInstance()->delete('ppbs_equation', 'id_equation_template = ' . (int)$id_equation_template);
        }
        DB::getInstance()->delete('ppbs_equation_template', 'id_equation_template = ' . (int)$id_equation_template);
    }

    /**
     * Assign an equation template to a product + IPA
     * @param $id_product
     * @param $id_product_attribute
     * @param $id_equation_template
     * @param $equation_type
     * @throws PrestaShopException
     */
    public static function assignTemplateToProduct($id_product, $id_product_attribute, $id_equation_template, $equation_type)
    {
        self::deleteTemplateProduct($id_product, $id_product_attribute, $equation_type);
        $ppbs_equation = new PPBSEquation();
        $ppbs_equation->id_product = (int)$id_product;
        $ppbs_equation->ipa = (int)$id_product_attribute;
        $ppbs_equation->equation = '';
        $ppbs_equation->id_equation_template = (int)$id_equation_template;
        $ppbs_equation->equation_type = pSQL($equation_type);
        $ppbs_equation->save();
    }

    /**
     * Save the equation for the selected product and combination (IPA)
     * @param $id_equation_template
     * @param $equation
     * @param name
     */
    public static function saveTemplate($id_equation_template, $equation, $name = '')
    {
        $ppbs_equation_template = new PPBSEquationTemplateModel($id_equation_template);

        if ($name != '') {
            $col_array = array(
                'equation' => pSQL($equation, true),
                'name' => pSQL($name)
            );
        } else {
            $col_array = array(
                'equation' => pSQL($equation, true),
            );
        }

        if ((int)$ppbs_equation_template->id_equation_template == 0) {
            DB::getInstance()->insert('ppbs_equation_template', $col_array);
            return Db::getInstance()->Insert_ID();
        } else {
            DB::getInstance()->update('ppbs_equation_template', $col_array, 'id_equation_template = ' . (int)$id_equation_template);
        }
    }

    public static function getVariableByName(string $name): PPBSEquationVarModel
    {
        $ppbs_equation_var_model = new PPBSEquationVarModel();
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(PPBSEquationVarModel::$definition['table']);
        $sql->where('name LIKE "' .$name.'"');
        $row = DB::getInstance()->getRow($sql);
        if (!empty($row)) {
            $ppbs_equation_var_model->hydrate($row);
        }
        return $ppbs_equation_var_model;
    }

    public static function getVariables(): array
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(PPBSEquationVarModel::$definition['table']);
        $sql->orderBy('name');
        $result = DB::getInstance()->executeS($sql);
        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public static function deleteVariable(string $name)
    {
        $sql= 'UPDATE ' . _DB_PREFIX_ . PPBSEquationTemplateModel::$definition['table']." SET equation = REPLACE(equation, '[".pSQL($name)."]', '[0]')";
        DB::getInstance()->execute($sql);
        $sql = 'UPDATE ' . _DB_PREFIX_ . PPBSEquation::$definition['table'] . " SET equation = REPLACE(equation, '[" . pSQL($name) . "]', '[0]')";
        DB::getInstance()->execute($sql);
        DB::getInstance()->delete(PPBSEquationVarModel::$definition['table'], "name LIKE '$name'");
    }
}
