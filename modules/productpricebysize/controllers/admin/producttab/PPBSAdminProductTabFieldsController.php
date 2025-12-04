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

class PPBSAdminProductTabFieldsController extends PPBSControllerCore
{
    protected $id_shop = 0;

    public function __construct($sibling, $params = array())
    {
        parent::__construct($sibling, $params);
        $this->sibling = $sibling;
        $this->base_url = Tools::getShopProtocol() . Tools::getShopDomain() . __PS_BASE_URI__;

        $product = new Product(Tools::getValue('id_product'));
        if (!empty($product->id_shop_default)) {
            $this->id_shop = $product->id_shop_default;
        } else {
            $this->id_shop = Context::getContext()->shop->id;
        }
    }

    public function renderList()
    {
        $fields = PPBSProductField::getCollectionByProduct($this->params['id_product'], Context::getContext()->language->id);

        Context::getContext()->smarty->assign(array(
            'fields' => $fields,
            'id_product' => $this->params['id_product'],
            'module_ajax_url' => $this->module_ajax_url,
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/producttab/fields.tpl');
    }

    /**
     * Render add field form (for popup)
     */
    public function renderAddForm()
    {
        $dimensions = PPBSDimension::getDimensions(Context::getContext()->language->id);
        $units = PPBSUnit::getUnits();

        if (Tools::getValue('id_ppbs_product_field') != '') {
            $field = new PPBSProductField(Tools::getValue('id_ppbs_product_field'));
        } else {
            $field = new PPBSProductField();
        }

        if (empty($field->decimals) && Tools::getValue('id_ppbs_product_field') == '') {
            $field->decimals = 2;
        }

        // get dropdown options if available
        $field_options = array();
        if ($field->input_type == 'dropdown') {
            $field_options = PPBSProductFieldOption::getFieldOptions(Tools::getValue('id_ppbs_product_field'));
        }

        if (!empty($field)) {
            Context::getContext()->smarty->assign(array('field' => $field));
        }

        Context::getContext()->smarty->assign(array(
            'id_product' => Tools::getValue('id_product'),
            'dimensions' => $dimensions,
            'field_options' => $field_options,
            'units' => $units
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/producttab/fields_add.tpl');
    }

    public function processAddForm()
    {
        $product_field = new PPBSProductField(Tools::getValue('id_ppbs_product_field'));
        $product_field->id_ppbs_unit = (int)Tools::getValue('id_ppbs_unit');
        $product_field->id_ppbs_dimension = (int)Tools::getValue('id_ppbs_dimension');
        $product_field->id_product = (int)Tools::getValue('id_product');
        $product_field->min = (float)Tools::getValue('min');
        $product_field->max = (float)Tools::getValue('max');
        $product_field->default = (float)Tools::getValue('default');
        $product_field->decimals = (int)Tools::getValue('decimals');
        $product_field->visible = (Tools::getIsset('visible') ? (int)Tools::getValue('visible') : 1);
        $product_field->input_type = Tools::getValue('input_type');
        $product_field->ratio = (int)Tools::getValue('ratio');
        $product_field->step = (float)Tools::getValue('step');
        $product_field->display_suffix = (int)Tools::getValue('display_suffix');
        $product_field->save();

        $product_field_options_arr = json_decode(Tools::getValue('ppbs_product_field_options'));

        if (!empty($product_field_options_arr) && !empty($product_field->id)) {
            $position = 0;
            PPBSProductFieldOption::deleteAllByProductFieldID($product_field->id);
            foreach ($product_field_options_arr as $option) {
                $ppbs_product_field_option = new PPBSProductFieldOption();
                $ppbs_product_field_option->id_ppbs_product_field = $product_field->id;
                $ppbs_product_field_option->text = pSQL($option->text);
                $ppbs_product_field_option->value = (float)$option->value;
                $ppbs_product_field_option->position = (float)$position;
                $ppbs_product_field_option->add(false);
                $position++;
            }
        }
    }

    /**
     * Delete a field
     */
    public function processDelete()
    {
        if (Tools::getValue('id_ppbs_product_field') != '') {
            $field = new PPBSProductField(Tools::getValue('id_ppbs_product_field'));
            $field->delete();
            PPBSProductFieldOption::deleteAllByProductFieldID(Tools::getValue('id_ppbs_product_field'));
        }
    }

    /**
     * Update the positions of the fields in the fields list
     */
    public function processPositions()
    {
        $position = 0;
        if (is_array(Tools::getValue('ids_ppbs_product_field'))) {
            foreach (Tools::getValue('ids_ppbs_product_field') as $id_ppbs_product_field) {
                PPBSProductField::updatePosition($id_ppbs_product_field, $position);
                $position++;
            }
        }
    }

    public function route()
    {
        switch (Tools::getValue('action')) {
            case 'renderaddform':
                die($this->renderAddForm());

            case 'processaddform':
                die($this->processAddForm());

            case 'processdelete':
                die($this->processDelete());

            case 'processpositions':
                die($this->processPositions());

            default:
                return $this->renderList();
        }
    }
}
