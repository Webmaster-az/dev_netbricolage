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

class PPBSAdminConfigMassAssignController extends PPBSControllerCore
{
    protected $sibling;

    private $route = 'ppbsadminconfigmassassigncontroller';

    public function __construct(&$sibling = null)
    {
        parent::__construct($sibling);
        if ($sibling !== null) {
            $this->sibling = &$sibling;
        }
    }

    /**
     * Get the form
     * @return string
     */
    public function getForm()
    {
        $inputs = array();
        $id_shop = Context::getContext()->shop->id;
        $config = new PPBSConfigModel(0, 0, $id_shop);
        $fields_form = array();

        $product_search_widget = new MPProductSearchWidgetController('ppbsproducts1', $this->sibling);
        $selected_products = array();

        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->sibling->l('Mass Assign', $this->route),
                'icon' => 'icon-question'
            ),
            'input' => array(
                array(
                    'name' => '',
                    'type' => 'html',
                    'label' => $this->sibling->l('Copy settings from', $this->route),
                    'desc' => $this->sibling->l('Name of product to copy module settings from', $this->route),
                    'class' => 'fixed-width-xl',
                    'required' => true,
                    'html_content' => $product_search_widget->render($selected_products),
                    'size' => 255
                ),
                array(
                    'type' => 'categories',
                    'label' => $this->sibling->l('Product Category', $this->route),
                    'name' => 'category',
                    'tree' => array(
                        'id' => 'category',
                        'use_checkbox' => true,
                        'selected_categories' => array()
                    )
                ),
                array(
                    'type' => 'html',
                    'label' => $this->sibling->l('Products', $this->route),
                    'name' => '',
                    'html_content' => '<div id="category-products"></div>'
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->fields_value['display_total_area'] = $config->getDisplayTotalArea();
        $this->setupHelperConfigForm($helper, $this->route, 'process');
        return $helper->generateForm($fields_form);
    }

    /**
     * process the form
     */
    public function process()
    {
        $config = new PPBSConfigModel(0, 0);
        $config->setDisplayTotalArea((int)Tools::getValue('display_total_area'));
        $config->updateAll();
    }

    public function render()
    {
        Context::getContext()->smarty->assign(array(
            'form' => $this->getForm()
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/config/mass_assign.tpl');
    }

    /**
     * Render products in a category
     */
    public function renderProducts()
    {
        $id_lang = Context::getContext()->language->id;
        $id_product = Tools::getValue('id_product');
        $id_category = (int)Tools::getValue('id_category');
        $category = new Category($id_category);
        $products = $category->getProducts($id_lang, 0, 9999, 'name');

        $products_filtered = array();

        if (!empty($id_product)) {
            foreach ($products as $product) {
                if ($product['id_product'] != $id_product) {
                    $products_filtered[] = $product;
                }
            }
        } else {
            $products_filtered = $products;
        }

        Context::getContext()->smarty->assign(array(
            'products' => $products_filtered
        ));
        return $this->sibling->display($this->sibling->module_file, 'views/templates/admin/config/mass_assign_products.tpl');
    }

    /**
     * process mass assign
     */
    public function processMassAssign()
    {
        $id_product = (int)Tools::getValue('id_product');
        $id_lang = Context::getContext()->language->id;
        $id_shop = Context::getContext()->shop->id;
        $id_category_product = Tools::getValue('id_category_product');
        $categories = Tools::getValue('category');

        $products_destination = array();

        if (empty($id_category_product)) {
            foreach ($categories as $id_category) {
                $category = new Category($id_category);
                $products_destination = $category->getProducts($id_lang, 0, 9999, 'name');
            }
        } else {
            foreach ($id_category_product as $id) {
                $products_destination[] = array('id_product' => $id);
            }
        }

        if (empty($products_destination)) {
            return false;
        }

        foreach ($products_destination as $product) {
            if ($id_product != $product['id_product']) {
                PPBSMassAssignHelper::deleteAllSettingsByProduct($product['id_product']);
                PPBSMassAssignHelper::duplicateProduct($id_product, $product['id_product'], $id_shop);
            }
        }
    }

    public function route()
    {
        switch (Tools::getValue('action')) {
            case 'process':
                die($this->process());

            case 'renderproducts':
                die($this->renderProducts());

            case 'massassign':
                die($this->processMassAssign());

            default:
                return $this->render();
        }
    }
}
