<?php
/**
 * An abstract admin controller of the module.
 *
 * @author    PrestaMonster
 * @copyright PrestaMonster
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

require_once dirname(__FILE__) . '/../../classes/HsAccessoriesGroupAbstract.php';
require_once dirname(__FILE__) . '/../../classes/HsAccessoriesGroupProductAbstract.php';
require_once dirname(__FILE__) . '/../../classes/HsMaCartRule.php';
require_once dirname(__FILE__) . '/../../classes/HsMaSearch.php';

class AdminHsMultiAccessoriesGroupAbstract extends ModuleAdminController
{
    public $bootstrap = true;
    protected $position_identifier = 'id_accessory_group';

    /**
     * All javascript files which should be loaded.
     *
     * @var array
     */
    protected $module_media_js = array(
        'admin_product_setting.js',
        'admin_behavior.js',
        'admin_multi_accessories.js',
        'admin_setting_multi_accessories.js',
        'hsma_filter_by_category.js',
        'hsma_display_style.js'
    );

    /**
     * Show result to view.
     *
     * @var type json
     */
    protected $ajax_json = array(
        'success' => false,
        'message' => null,
    );

    /**
     * Start time regenerate image.
     *
     * @var int
     */
    protected $start_time = 0;

    /**
     * Max time regenerate images.
     *
     * @var type
     */
    protected $max_execution_time = 18000;

    public function __construct()
    {
        $this->table = 'accessory_group';
        $this->className = 'HsAccessoriesGroupAbstract';
        $this->lang = true;
        parent::__construct();

        $this->_defaultOrderBy = 'position';

        // check status visit welcome page, if != 1 go to welcome page
        if ((int) Configuration::get($this->module->getKeyWelcomePage()) !== 1 && !$this->ajax) {
            Tools::redirectAdmin($this->context->link->getAdminLink($this->module->class_controller_admin_welcome_page));
        }
        $this->fields_list = array(
            'id_accessory_group' => array(
                'title' => $this->module->i18n['id'],
                'width' => 25,
            ),
            'name' => array(
                'title' => $this->module->i18n['group_name'],
                'width' => 'auto',
                'filter_key' => 'b!name',
            ),
            'active' => array(
                'title' => $this->module->i18n['active'],
                'width' => 40,
                'align' => 'center',
                'active' => 'status',
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'align' => 'center',
                'position' => 'position',
            ),
        );
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->module->i18n['delete_selected_items'],
                'confirm' => $this->module->i18n['delete_selected_items'] . '?',
            ),
        );
        
        if ($this->module->isPrestashop16()) {
            // only support feature add multi accessories for multi product from version 1.6
            $this->fields_options = array(
                'hsma_add_accessories' => array(
                    'title' => $this->module->i18n['add_multi_accessories_for_multi_products'],
                    'fields' => $this->generateFormAddAccessories()
                )
            );
        }
    }
    
    protected function generateFormAddAccessories()
    {
        return array(
            'HSMA_ADD_ACCESSORIES' => array(
                'type' => 'hsma_add_accessores',
                'is_prestashop16' => (int) $this->module->isPrestashop16(),
            ),
        );
    }
    
    /**
     * Render block categories tree
     * @param string $id_block_categories
     * @return html
     */
    protected function getCategoryTree($id_block_categories)
    {
        // Generate category selection tree
        $tree = new HelperTreeCategories($id_block_categories, $this->module->i18n['filter_by_category']);
        $tree->setId($id_block_categories);
        $tree->setUseSearch(true);
        $tree->setAttribute($id_block_categories, true)->setAttribute('base_url', preg_replace('#&id_category=[0-9]*#', '', self::$currentIndex) . '&token=' . $this->token)->setInputName('id-category')->setUseCheckBox(true)->setSelectedCategories(array(0));
        return $tree->setRootCategory((int) Category::getRootCategory()->id)->render();
    }

    /**
     * The last call for any ajax action if method "displayAjax[ActionName]" is not found
     */
    public function displayAjax()
    {
        exit(Tools::jsonEncode($this->ajax_json));
    }

    /**
     * Process save all settings.
     */
    public function postProcess()
    {
        if (Tools::isSubmit('submitSetting')) {
            unset($this->module->configuration_keys['HSMA_SHOW_CUSTOM_QUANTITY']);
            foreach ($this->module->configuration_keys as $config_name => $config_validate) {
                if (!in_array($config_name, $this->module->configuration_lang_keys)) {
                    $config_validate = $config_validate; // fix validator ps not use
                    if (Validate::$config_validate(Tools::getValue($config_name))) {
                        Configuration::updateValue($config_name, Tools::getValue($config_name));
                    }
                }
            }
            $languages = $this->getLanguages();
            $title = array();
            $messages_available_later = array();
            $alert_message = array();
            foreach ($languages as $lang) {
                $title[$lang['id_lang']] = Tools::getValue('HSMA_TITLE_' . $lang['id_lang']);
                $messages_available_later[$lang['id_lang']] = Tools::getValue('HSMA_MESSAGE_AVAILABLE_LATER_' . $lang['id_lang']);
                if (Tools::getValue('HSMA_ALERT_MESSAGE_' . $lang['id_lang'])) {
                    $alert_message[$lang['id_lang']] = Tools::getValue('HSMA_ALERT_MESSAGE_' . $lang['id_lang']);
                }
            }

            Configuration::updateValue('HSMA_TITLE', $title);
            Configuration::updateValue('HSMA_MESSAGE_AVAILABLE_LATER', $messages_available_later);
            if (!empty($alert_message)) {
                Configuration::updateValue('HSMA_ALERT_MESSAGE', $alert_message);
            }

            $this->confirmations[] = $this->_conf[6];
        }
        parent::postProcess();
    }

    /**
     * Render all accesory groups.
     *
     * @return HTML string
     */
    public function renderList()
    {
        $this->addRowAction('edit');
        $this->addRowAction('delete');

        return parent::renderList();
    }

    /**
     * Show form add a group.
     *
     * @return HTML string
     */
    public function renderForm()
    {
        if ($this->display == 'edit') {
            $this->toolbar_title = $this->module->i18n['edit_group'];
        } else {
            $this->toolbar_title = $this->module->i18n['add_a_new_accessory_group'];
        }

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->module->i18n['group'],
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->module->i18n['name'],
                    'name' => 'name',
                    'lang' => true,
                    'size' => 33,
                    'hint' => $this->module->i18n['invalid_characters'] . ' <>;=#{}',
                    'required' => true,
                ),
                array(
                    'type' => 'select',
                    'label' => $this->module->i18n['display_style'],
                    'name' => 'display_style',
                    'hint' => $this->module->i18n['define_how_accessories_look_like_at_product_page'],
                    'options' => array(
                        'query' => $this->module->getDisplayStyles(true),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => $this->module->isPrestashop16() ? 'switch' : 'radio',
                    'label' => $this->module->i18n['active'],
                    'name' => 'active',
                    'required' => false,
                    'class' => !$this->module->isPrestashop16() ? 't' : '',
                    'is_bool' => true,
                    'default_value' => 1,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->module->i18n['enabled'],
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->module->i18n['disabled'],
                        ),
                    ),
                ),
            ),
        );
        $this->fields_form['submit'] = array(
            'title' => $this->module->i18n['save'],
        );

        return parent::renderForm();
    }

    /**
     * Save short name of an accessory.
     */
    public function ajaxProcessSaveName()
    {
        $id_accessory_group_product = (int) Tools::getValue('id_accessory_group_product');
        $this->ajax_json['success'] = false;
        if ($id_accessory_group_product) {
            $accessory = new HsAccessoriesGroupProduct($id_accessory_group_product);
            if (!Validate::isLoadedObject($accessory)) {
                exit(Tools::jsonEncode($this->ajax_json));
            }

            $product_update = Tools::getValue('product_update');
            $names = Tools::getValue('names');
            if (!$product_update) {
                $this->ajax_json['success'] = HsAccessoriesGroupProduct::updateGlobalName($accessory->id_accessory, $names);
            } else {
                $accessory->name = $names;
                $this->ajax_json['success'] = $accessory->update();
            }
        }
        die(Tools::jsonEncode($this->ajax_json));
    }

    /**
     * Create button add new accessory group.
     */
    public function initPageHeaderToolbar()
    {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_accessory_group'] = array(
                'href' => self::$currentIndex . '&addaccessory_group&token=' . $this->token,
                'desc' => $this->module->i18n['add_a_new_accessory_group'],
                'icon' => 'process-icon-new',
            );
        }

        parent::initPageHeaderToolbar();
    }

    /**
     * Set Media file include when controller called.
     */
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addJqueryPlugin('tablednd');
        if (!empty($this->module_media_js) && is_array($this->module_media_js)) {
            $js_files = array();
            foreach ($this->module_media_js as $js_file) {
                $js_file = $this->module->getJsPath() . $js_file;
                $js_files[] = $js_file;
            }
            $this->addJS($js_files);
        }
    }

    /**
     * display list combinations.
     */
    public function displayAjaxAddAccessory()
    {
        $id_group = (int) Tools::getValue('id_group');
        $id_product = (int) Tools::getValue('id_product');
        $colspan = (int) Tools::getValue('colspan');
        $id_main_product = (int) Tools::getValue('id_main_product');
        $product = new HsMaProduct($id_product);
        if (!Validate::isLoadedObject($product)) {
            exit(false);
        }
        $accessory = new HsAccessoriesGroupProduct();
        $accessory->id_accessory = $id_product;
        $accessory->id_product = $id_main_product;
        $accessory->id_accessory_group = $id_group;
        $accessory->id_product_attribute = 0;
        $accessory->default_quantity = (int) $product->minimal_quantity;
        $accessory->min_quantity = (int) $accessory->default_quantity;
        $accessory->name = $product->name;
        $accessory->position = HsAccessoriesGroupProductAbstract::getHighestPosition($id_group, $id_main_product) + 1;
        $accessory->add();

        $image_products = Image::getImages($this->context->language->id, $id_product);
        $image_type = new ImageType((int) Configuration::get('HSMA_ID_IMAGE_TYPE'));
        $product_image_dir = _PS_PROD_IMG_DIR_;

        foreach ($image_products as $image_product) {
            $image = new Image($image_product['id_image']);
            $existing_image = $product_image_dir . $image->getExistingImgPath() . '.jpg';
            if (file_exists($existing_image) && filesize($existing_image)) {
                if (!file_exists($product_image_dir . $image->getExistingImgPath() . '-' . Tools::stripslashes($image_type->name) . '.jpg')) {
                    ImageManager::resize($existing_image, $product_image_dir . $image->getExistingImgPath() . '-' . Tools::stripslashes($image_type->name) . '.jpg', (int) $image_type->width, (int) $image_type->height);
                }
            }
        }

        $id_images = HsMaProduct::getCover($product->id);
        $accessory->image = '';
        if (!empty($id_images)) {
            $accessory->image = str_replace('http://', Tools::getShopProtocol(), $this->context->link->getImageLink($product->link_rewrite[$this->context->language->id], $id_images['id_image'], HsMaImageType::getFormatedNameByPsVersion('small')));
        }

        $accessory->combinations = HsMaProduct::getCombinations($id_product, $this->context->shop->id, $this->context->language->id);
        // @todo: Find this similar block in class HsAccessoriessGroupAbstract::getAccessoriesByGroups()
        // @todo: Move to another place so we can re-use
        foreach ($accessory->combinations as &$combination) {
            if (empty($combination['id_image'])) {
                if (!empty($id_images)) {
                    $combination['id_image'] = $id_images['id_image'];
                }
            }
            $combination['image'] = str_replace('http://', Tools::getShopProtocol(), Context::getContext()->link->getImageLink($product->link_rewrite[$this->context->language->id], $combination['id_image'], HsMaImageType::getFormatedNameByPsVersion('small')));
        }

        $accessory->id_accessory_group_product = $accessory->id;
        $languages = Language::getLanguages(true);
        $meta_language = array();
        foreach ($languages as $lang) {
            $meta_language[] = $lang['iso_code'];
        }
        $specific_price_output = null;
        $accessory->old_price = Product::getPriceStatic($accessory->id_accessory, true, $accessory->id_product_attribute, (int)_PS_PRICE_DISPLAY_PRECISION_, null, false, true, 1, true, null, null, null, $specific_price_output, true, true, $this->context);
        $accessory->cart_rule = HsMaCartRule::getCartRule((array) $accessory, $id_main_product);
        $accessory->final_price = HsAccessoriesGroupAbstract::getFinalPrice($accessory->old_price, $accessory->cart_rule);
        $group = array('id_accessory_group' => $id_group);
        $this->context->smarty->assign(array(
            'id_group' => $id_group,
            'group' => $group,
            'id_product' => $id_product,
            'accessory' => (array) $accessory, // the template expects to accept array instead of object
            'is_prestashop16' => $this->module->isPrestashop16(),
            'default_form_language' => (int) Configuration::get('PS_LANG_DEFAULT'),
            'show_custom_quantity' => (int) Configuration::get('HSMA_SHOW_CUSTOM_QUANTITY'),
            'languages' => $languages,
            'img_path' => $this->module->getImgPath(),
            'buy_together_default' => HsMaProductSetting::getBuyTogetherDefault($id_main_product),
            'colspan' => $colspan,
            'path_theme' => $this->module->isPrestashop17() ? '17/' : '',
        ));
        $data = array();
        $count_accessory = HsAccessoriesGroupAbstract::countAccessories($id_main_product, $id_group);
        $data['is_stock_available'] = (int) $this->isStockAvailable((int) $id_product, (int) $accessory->id_product_attribute, (int) $accessory->default_quantity);
        $data['content'] = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name . '/views/templates/hook/display_accessory_row.tpl');
        $data['xx_items_inside'] = sprintf($count_accessory > 1 ? $this->module->i18n['items_inside'] : $this->module->i18n['item_inside'], $count_accessory);
        $data['count_accessory'] = $count_accessory;
        exit(Tools::jsonEncode($data));
    }

    /**
     * Checking product is out of stock.
     *
     * @param int $id_product
     * @param int $id_product_attribute
     * @param int $quantity             default quantity
     *
     * @return bool
     */
    protected function isStockAvailable($id_product, $id_product_attribute, $quantity)
    {
        $flag = false;
        $stock_status = HsMaProduct::getStockStatus((int) $id_product, (int) $id_product_attribute, $this->context->shop);
        if (!empty($stock_status)) {
            if (Product::isAvailableWhenOutOfStock($stock_status['out_of_stock']) || (!Product::isAvailableWhenOutOfStock($stock_status['out_of_stock']) && $stock_status['quantity'] >= (int) $quantity)) {
                $flag = true;
            }
        }

        return $flag;
    }

    /**
     * Delete accessory.
     */
    public function displayAjaxDeleteAccessory()
    {
        $result = array();
        $result['success'] = false;
        $id_accessory_group_product = (int) Tools::getValue('id_accessory_group_product');
        $colspan = (int) Tools::getValue('colspan');
        if ($id_accessory_group_product) {
            $accessory = new HsAccessoriesGroupProduct($id_accessory_group_product);
            if (Validate::isLoadedObject($accessory)) {
                $this->context->smarty->assign(array(
                    'colspan' => $colspan,
                ));
                $id_product = $accessory->id_product;
                $id_accessory_group = $accessory->id_accessory_group;
                $result['success'] = $accessory->delete();
                if ($result['success']) {
                    // delete cart rule for this accessory
                    $id_cart_rule = HsMaCartRule::getIdCartRuleByAccessoryProduct($accessory->id_accessory, $accessory->id_product);
                    if ($id_cart_rule) {
                        $cart_rule = new HsMaCartRule($id_cart_rule);
                        if (Validate::isLoadedObject($cart_rule)) {
                            $cart_rule->delete();
                        }
                    }
                    $count_accessory = HsAccessoriesGroupAbstract::countAccessories($id_product, $id_accessory_group);
                    $result['xx_items_inside'] = sprintf($count_accessory > 1 ? $this->module->i18n['items_inside'] : $this->module->i18n['item_inside'], $count_accessory);
                    $result['ids_accessory'] = $accessory->id_accessory . ':' . $accessory->id_product_attribute;
                    $result['id_group'] = $accessory->id_accessory_group;
                    $result['count_accessory'] = $count_accessory;
                    $result['content'] = $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name . '/abstract/views/templates/hook/display_no_accessory.tpl');
                }
            }
        }
        die(Tools::jsonEncode($result));
    }

    /**
     * Change product combination.
     */
    public function displayAjaxChangeProductCombination()
    {
        $id_group = (int) Tools::getValue('id_group');
        $id_product = (int) Tools::getValue('id_product');
        $id_main_product = (int) Tools::getValue('id_main_product');
        $id_product_attribute = (int) Tools::getValue('id_product_attribute');
        $id_accessory_group_product = HsAccessoriesGroupProduct::getIdAccessoryGroupProduct($id_product, $id_main_product, $id_group);
        $accessory = new HsAccessoriesGroupProduct($id_accessory_group_product);
        $accessory->id_product_attribute = $id_product_attribute;
        $result = $accessory->update();
        $data = array();
        $data['is_stock_available'] = (int) $this->isStockAvailable((int) $id_product, (int) $accessory->id_product_attribute, (int) $accessory->default_quantity);
        $data['success'] = $result;
        exit(Tools::jsonEncode($data));
    }

    /**
     * Change display style.
     */
    public function displayAjaxChangeDisplayStyle()
    {
        $id_group = (int) Tools::getValue('id_group');
        $display_style = (int) Tools::getValue('display_style');
        if (!$id_group) {
            die(Tools::jsonEncode(false));
        }
        $group = new HsAccessoriesGroupAbstract($id_group);

        if (!Validate::isLoadedObject($group)) {
            die(Tools::jsonEncode(false));
        }
        $group->display_style = $display_style;
        $result = $group->update();
        die(Tools::jsonEncode($result));
    }

    /**
     * Change discount value of accessory product
     */
    public function ajaxProcessChangeDiscountValue()
    {
        $id_accessory_group_product = (int) Tools::getValue('id_accessory_group_product');
        $id_main_product = (int) Tools::getValue('id_main_product');
        $discount_type = (int) Tools::getValue('discount_type', 0);// 0 means '%'
        $discount_value = (float) Tools::getValue('discount_value');

        $accessory = new HsAccessoriesGroupProduct((int) $id_accessory_group_product);
        if (!$id_accessory_group_product || !Validate::isLoadedObject($accessory)) {
            return;
        }

        $product = new Product($id_main_product, false, $this->context->language->id);
        if (!$id_main_product || !Validate::isLoadedObject($product)) {
            return;
        }

        $this->ajax_json['success'] = true;
        $this->deleteExistingCartRules($product, $accessory);
        $final_price = $accessory->getAccessoryPrice();
        if ($discount_value > 0) {
            $cart_rule = new HsMaCartRule();
            $cart_rule->product = $product;
            $cart_rule->accessory = $accessory;
            if (Shop::isFeatureActive()) {
                $cart_rule->id_shops = array_intersect($this->module->getEnabledShops(), $this->context->employee->getAssociatedShops());
            }
            $cart_rule->addCartRule($discount_type, $discount_value, $this->generateCartRuleNames($accessory), $this->getCartRuleDescription());
            if (Validate::isLoadedObject($cart_rule)) {
                $final_price = $this->getDiscountedPrice($accessory, $cart_rule);
            } else {
                $this->ajax_json['success'] = false;
                $this->ajax_json['message'] = $this->module->i18n['can_not_save_cart_rule'];
            }
        }
        $this->ajax_json['final_price'] = Tools::displayPrice($final_price);
    }

    /**
     *
     * @param Product $product main product
     * @param HsAccessoriesGroupProduct $accessory current accessory associated with the main product
     *
     * @return boolean
     */
    protected function deleteExistingCartRules(Product $product, HsAccessoriesGroupProduct $accessory)
    {
        $id_cart_rule = (int) HsMaCartRule::getIdCartRuleByAccessoryProduct($accessory->id_accessory, $product->id);
        $cart_rule = new HsMaCartRule($id_cart_rule);
        $result = true;
        if (Validate::isLoadedObject($cart_rule)) {
            $result &= $cart_rule->delete();
        }
        return $result;
    }
    
    /**
     * get the price after discount of an accessory
     * @param HsAccessoriesGroupProduct $accessory
     * @param HsMaCartRule $cart_rule
     *
     * @return float
     */
    protected function getDiscountedPrice(HsAccessoriesGroupProduct $accessory, HsMaCartRule $cart_rule)
    {
        $base_price = $accessory->getAccessoryPrice();
        if ($cart_rule->reduction_percent) {
            $final_price = $base_price - ($base_price * ($cart_rule->reduction_percent / 100));
        } elseif ($cart_rule->reduction_amount) {
            $final_price = $base_price - $cart_rule->reduction_amount;
        } else {
            $final_price = $base_price;
        }
        return $final_price;
    }
    
    /**
     *
     * @return string
     */
    protected function getCartRuleDescription()
    {
        return $this->module->i18n['only_valid_when_buying_with_main_product'];
    }
    
    /**
     * Generate cart rule names (multiple language) for associated accessory
     * @param HsAccessoriesGroupProduct $accessory
     * <pre>
     * array(
     *  int => string // id_lang => name
     * )
     */
    protected function generateCartRuleNames(HsAccessoriesGroupProduct $accessory)
    {
        $languages = Language::getLanguages(false);
        $names = array();
        foreach ($languages as $lang) {
            $names[$lang['id_lang']] = sprintf($this->module->i18n['discount_for_accessory'], $accessory->name[$lang['id_lang']]);
        }
        return $names;
    }

    /**
     * Change minimum quantity of accessory.
     */
    public function displayAjaxChangeDefaultQuantity()
    {
        $json_data = array();
        $json_data['success'] = false;
        $quantity = (int) Tools::getValue('quantity');
        if ($quantity < 0) {
            $quantity = 0;
        }
        $id_accessory_group_product = (int) Tools::getValue('id_accessory_group_product');

        if (!$id_accessory_group_product) {
            exit(Tools::jsonEncode($json_data));
        }
        $accessory = new HsAccessoriesGroupProduct((int) $id_accessory_group_product);
        if (!Validate::isLoadedObject($accessory)) {
            exit(Tools::jsonEncode($json_data));
        }

        $is_stock_available = (int) $this->isStockAvailable((int) $accessory->id_accessory, (int) $accessory->id_product_attribute, (int) $quantity);
        $json_data['is_stock_available'] = $is_stock_available;
        if ($is_stock_available) {
            $accessory->default_quantity = (int) $quantity;
            $json_data['success'] = $accessory->update();
        }
        exit(Tools::jsonEncode($json_data));
    }

    /**
     * Change required buy product & accessory together.
     */
    public function displayAjaxChangeAccessorySettingBuyTogether()
    {
        $required = (int) Tools::getValue('required');
        $id_accessory_group_product = (int) Tools::getValue('id_accessory_group_product');
        exit(Tools::jsonEncode($this->updateAccessoriesGroupProduct($id_accessory_group_product, $required)));
    }

    /**
     * Update object AccessoriesGroupProduct.
     *
     * @param int $id_accessory_group_product
     * @param int $required
     *
     * @return bool
     */
    protected function updateAccessoriesGroupProduct($id_accessory_group_product, $required)
    {
        if (!$id_accessory_group_product) {
            return false;
        }
        $accessory = new HsAccessoriesGroupProduct((int) $id_accessory_group_product);
        if (!Validate::isLoadedObject($accessory)) {
            return false;
        }
        $accessory->required = (int) $required;

        return $accessory->update();
    }

    /**
     * Process change position of accessories.
     */
    public function ajaxProcessUpdateAccessoryProductPosition()
    {
        if ($this->tabAccess['edit'] === '0') {
            return die(Tools::jsonEncode(array('error' => $this->module->i18n['you_do_not_have_the_right_permission'])));
        }
        $flag = false;
        if ($accessories_positions = Tools::getValue('accessories_positions')) {
            $flag = true;
            $accessories_positions = Tools::stripslashes($accessories_positions);
            $array_accessories_positions = Tools::jsonDecode($accessories_positions, true);
            foreach ($array_accessories_positions as $string_ids => $position) {
                $list_ids = explode('_', $string_ids);
                if (isset($list_ids[1])) {
                    $accessory = new HsAccessoriesGroupProduct((int) $list_ids[1]);
                    $accessory->position = (int) $position;
                    $flag &= $accessory->update();
                }
            }
        }
        if ($flag) {
            $this->ajax_json['success'] = true;
            $this->ajax_json['message'] = $this->module->i18n['update_successful'];
        } else {
            $this->jsonError(Tools::displayError($this->module->i18n['an_error_occurred_while_attempting_to_move_this_accessory']));
        }
    }

    /**
     * Update accessory group.
     */
    public function ajaxProcessupdatePositions()
    {
        $way = (int) Tools::getValue('way');
        $id_accessory_group = (int) Tools::getValue('id');
        $accessory_group_positions = Tools::getValue('accessory_group');
        if (is_array($accessory_group_positions)) {
            foreach ($accessory_group_positions as $position => $accessory_group) {
                $group = explode('_', $accessory_group);
                if ((isset($group[1]) && isset($group[2])) && (int) $group[2] === $id_accessory_group) {
                    $hs_accessories_group = new HsAccessoriesGroupAbstract((int) $group[2]);
                    if (Validate::isLoadedObject($hs_accessories_group)) {
                        if (isset($position) && $hs_accessories_group->updatePosition($way, $position)) {
                            $this->ajax_json['success'] = true;
                        }
                    }
                    break;// only need to detect the changed group, other groups' positions will be updated accordingly in HsAccessoriesGroupAbstract::updatePosition()
                }
            }
        }
        exit(Tools::jsonEncode($this->ajax_json));
    }
    
    /**
     * Change min quantity of accessory.
     */
    public function ajaxProcessChangeMinQuantity()
    {
        $default_quantity = (int) Tools::getValue('default_quantity');
        $min_quantity = (int) Tools::getValue('min_quantity');
        $id_accessory_group_product = (int) Tools::getValue('id_accessory_group_product');
        
        if ($default_quantity < 0 || $min_quantity < 0 || empty($id_accessory_group_product)) {
            $this->ajax_json['message'] = $this->module->i18n['oops_something_goes_wrong'];
            return;
        }
        
        $accessory_group_product = new HsAccessoriesGroupProduct($id_accessory_group_product);
        if (!Validate::isLoadedObject($accessory_group_product)) {
            $this->ajax_json['message'] = $this->module->i18n['invalid_product'];
            return;
        }
        
        $this->ajax_json['is_stock_available'] = (int) $this->isStockAvailable((int) $accessory_group_product->id_accessory, (int) $accessory_group_product->id_product_attribute, (int) $min_quantity);
        if ($this->ajax_json['is_stock_available']) {
            $accessory_group_product->default_quantity = $default_quantity;
            $accessory_group_product->min_quantity = $min_quantity;
            $accessory_group_product->update() ? $this->ajax_json['success'] = true : $this->ajax_json['message'] = $this->module->i18n['oops_cannot_update_accessory'];
        } else {
            $this->ajax_json['message'] = $this->module->i18n['accessory_is_out_of_stock'];
        }
    }
    
    /**
     * Copy accessories form product
     */
    public function ajaxProcessCopyAccessories()
    {
        $from_id_product  = (int)Tools::getValue('from_id_product');
        $to_id_product = (int)Tools::getValue('to_id_product');
        $keep_accessories   = (int)Tools::getValue('keep_accessories');
        
        $from_product = new HsMaProduct($from_id_product);
        if (!Validate::isLoadedObject($from_product)) {
            $this->ajax_json['message'] = $this->module->i18n['invalid_product'];
            return;
        }
        $to_product = new HsMaProduct($to_id_product);
        if (!Validate::isLoadedObject($to_product)) {
            $this->ajax_json['message'] = $this->module->i18n['invalid_product'];
            return;
        }
        if (!$keep_accessories) {
            $to_product->deleteAccessories();
        }
        $this->ajax_json['success'] = HsMaProduct::copyAccessories($from_product, $to_product);
        $this->ajax_json['is_prestashop17'] = $this->module->isPrestashop17();
        if ($this->ajax_json['success']) {
            $this->ajax_json['product_link'] = $this->context->link->getAdminLink('AdminProducts') . '&' . 'updateproduct&id_product=' . (int) $to_id_product . '&key_tab=Module' . Tools::toCamelCase($this->module->name, true) . '&conf=4';
        } else {
            $this->ajax_json['message'] = $this->module->i18n['cannot_copy_accessories'];
        }
    }
    
    /**
     * @see parent::initContent()
     */
    public function initContent()
    {
        $st_hsmultiaccessories = array(
            'url' => $this->module->urls,
            'lang' => $this->module->i18n,
        );
        $this->context->smarty->assign(array(
            'is_prestashop16' => (int) $this->module->isPrestashop16(),
            'st_hsmultiaccessories' => Tools::jsonEncode($st_hsmultiaccessories),
            'category_tree_product' => $this->getCategoryTree('is_category_filter_product'),
            'category_tree_accessory' => $this->getCategoryTree('is_category_filter_accessory'),
            'groups' => HsAccessoriesGroupAbstract::getGroups($this->context->language->id),
            'buy_together_default' => HsMaProductSetting::BUY_TOGETHER_USE_DEFAULT,
            'is_ps17' => (int) $this->module->isPrestashop17(),
            'is_product_page' => false
        ));
        parent::initContent();
    }
    
    /**
     * process filter by categories
     */
    public function ajaxProcessFilterByCategories()
    {
        $this->ajax_json['success'] = false;
        $product_selected_id_categories = Tools::getValue('product_id_categories');
        $accessory_selected_id_categories = Tools::getValue('accessory_id_categories');
        $id_group = Tools::getValue('id_group');
        if (!$product_selected_id_categories || !$accessory_selected_id_categories) {
            exit;
        }
        $product_id_categories = $this->getChildrenCategories($product_selected_id_categories);
        $accessory_id_categories = $this->getChildrenCategories($accessory_selected_id_categories);
        
        if (empty($product_id_categories || empty($accessory_id_categories))) {
            exit;
        }
        $products = array();
        if ($product_id_categories) {
            $products = HsMaSearch::searchProductsByCategories($product_id_categories, $this->context);
        }
        if ($accessory_id_categories) {
            $accessories = HsMaSearch::searchProductsByCategories($accessory_id_categories, $this->context);
        }
        $id_products = $this->getIdProducts($products);
        
        if (!empty($id_products)) {
            $product_accessories = HsAccessoriesGroupAbstract::getAccessoriesByIdGroup($id_group, $id_products, false, (int) $this->context->language->id);
            $group_accessories = array();
            if (!empty($product_accessories)) {
                $group_accessories = $product_accessories[$id_group];
            }
            if (!empty($group_accessories)) {
                foreach ($products as &$product) {
                    foreach ($group_accessories as $accessory) {
                        if ($product['id_product'] == $accessory['id_product']) {
                            $product['accessories'][] = $accessory;
                        }
                    }
                }
            }
        }

        $this->context->smarty->assign(array(
            'groups' => HsAccessoriesGroupAbstract::getGroupById((int)$id_group, (int) $this->context->language->id),
            'show_custom_quantity' => (int) Configuration::get('HSMA_SHOW_CUSTOM_QUANTITY'),
            'default_form_language' => (int) Configuration::get('PS_LANG_DEFAULT'),
            'is_prestashop16' => (int) $this->module->isPrestashop16(),
            'is_ps17' => (int) $this->module->isPrestashop17(),
            'buy_together_default' => HsMaProductSetting::BUY_TOGETHER_USE_DEFAULT,
            'img_path' => $this->module->getImgPath(),
            'products' => $products,
            'accessories' => $accessories,
            'id_lang' => $this->context->language->id,
            'link' => $this->context->link,
            'use_tax' => Configuration::get('PS_TAX') && !Product::getTaxCalculationMethod((int) $this->context->cookie->id_customer),
        ));
        $this->ajax_json['success'] = true;
        $template_path = _PS_MODULE_DIR_ . $this->module->name . '/views/templates/hook/product_list.tpl';
        $this->ajax_json['data']['html'] = $this->context->smarty->fetch($template_path);
    }
    
    /**
     * Get children categories by id parent categories
     * @param array $id_categories
     * @return array
     */
    protected function getChildrenCategories($id_categories)
    {
        $children_id_categories = array();
        foreach ($id_categories as $id_category) {
            $category = new Category($id_category);
            $categories = $category->getAllChildren();
            foreach ($categories as $cat) {
                $children_id_categories[] = $cat->id_category;
            }
        }
        return array_unique(array_merge($children_id_categories, $id_categories));
    }
    
    /**
     * Get id_product from list products
     * @param array $products
     * @return array id_products
     */
    protected function getIdProducts($products)
    {
        $id_products = array();
        if (!empty($products)) {
            foreach ($products as $product) {
                $id_products[] = (int) $product['id_product'];
            }
        }
        return $id_products;
    }
    
    public function displayAjaxAssignAccessories()
    {
        $id_products = explode(',', Tools::getValue('id_products'));
        $id_accessories = explode(',', Tools::getValue('id_accessories'));
        $id_group = Tools::getValue('id_group');
        $this->ajax_json['success'] = false;
        if (empty($id_products) || empty($id_accessories) || !$id_group) {
            exit(Tools::jsonEncode($this->ajax_json));
        }
        $this->ajax_json['content'] = array();
        $this->ajax_json['success'] = true;
        foreach ($id_products as $id_product) {
            $id_accessories_exist = HsAccessoriesGroupProduct::getIdAccessoriesByGroupProduct($id_group, $id_product);
            $id_accessories_without_id_product = array_diff($id_accessories, array($id_product));
            $id_accessories_to_add = !empty($id_accessories_exist) ? array_diff($id_accessories_without_id_product, $id_accessories_exist) : $id_accessories_without_id_product;
            if (!empty($id_accessories_to_add)) {
                $this->ajax_json['content'][$id_product] = $this->proccessAssignAccessories($id_product, $id_accessories_to_add, $id_group);
            }
        }
        exit(Tools::jsonEncode($this->ajax_json));
    }
    
    protected function proccessAssignAccessories($id_main_product, $id_accessories, $id_group)
    {
        foreach ($id_accessories as $id_accessory) {
            $product = new HsMaProduct($id_accessory);
            if (Validate::isLoadedObject($product)) {
                $accessory = new HsAccessoriesGroupProduct();
                $accessory->id_accessory = $id_accessory;
                $accessory->id_product = $id_main_product;
                $accessory->id_accessory_group = $id_group;
                $accessory->id_product_attribute = 0;
                $accessory->default_quantity = (int) $product->minimal_quantity;
                $accessory->min_quantity = (int) $accessory->default_quantity;
                $accessory->name = $product->name;
                $accessory->position = HsAccessoriesGroupProductAbstract::getHighestPosition($id_group, $id_main_product) + 1;
                $accessory->add();

                $image_products = Image::getImages($this->context->language->id, $id_accessory);
                $image_type = new ImageType((int) Configuration::get('HSMA_ID_IMAGE_TYPE'));
                $product_image_dir = _PS_PROD_IMG_DIR_;

                foreach ($image_products as $image_product) {
                    $image = new Image($image_product['id_image']);
                    $existing_image = $product_image_dir . $image->getExistingImgPath() . '.jpg';
                    if (file_exists($existing_image) && filesize($existing_image)) {
                        if (!file_exists($product_image_dir . $image->getExistingImgPath() . '-' . Tools::stripslashes($image_type->name) . '.jpg')) {
                            ImageManager::resize($existing_image, $product_image_dir . $image->getExistingImgPath() . '-' . Tools::stripslashes($image_type->name) . '.jpg', (int) $image_type->width, (int) $image_type->height);
                        }
                    }
                }

                $id_images = HsMaProduct::getCover($product->id);
                $accessory->image = '';
                if (!empty($id_images)) {
                    $accessory->image = str_replace('http://', Tools::getShopProtocol(), $this->context->link->getImageLink($product->link_rewrite[$this->context->language->id], $id_images['id_image'], HsMaImageType::getFormatedNameByPsVersion('small')));
                }

                $accessory->combinations = HsMaProduct::getCombinations($id_accessory, $this->context->shop->id, $this->context->language->id);
                // @todo: Find this similar block in class HsAccessoriessGroupAbstract::getAccessoriesByGroups()
                // @todo: Move to another place so we can re-use
                foreach ($accessory->combinations as &$combination) {
                    if (empty($combination['id_image'])) {
                        if (!empty($id_images)) {
                            $combination['id_image'] = $id_images['id_image'];
                        }
                    }
                    $combination['image'] = str_replace('http://', Tools::getShopProtocol(), Context::getContext()->link->getImageLink($product->link_rewrite[$this->context->language->id], $combination['id_image'], HsMaImageType::getFormatedNameByPsVersion('small')));
                }

                $accessory->id_accessory_group_product = $accessory->id;
                $specific_price_output = null;
                $accessory->old_price = Product::getPriceStatic($accessory->id_accessory, true, $accessory->id_product_attribute, (int)_PS_PRICE_DISPLAY_PRECISION_, null, false, true, 1, true, null, null, null, $specific_price_output, true, true, $this->context);
                $accessory->cart_rule = HsMaCartRule::getCartRule((array) $accessory, $id_main_product);
                $accessory->final_price = HsAccessoriesGroupAbstract::getFinalPrice($accessory->old_price, $accessory->cart_rule);
            }
        }
        $product_accessories = HsAccessoriesGroupAbstract::getAccessoriesByIdGroup($id_group, array($id_main_product), false, (int) $this->context->language->id);
        $group_accessories = array();
        if (!empty($product_accessories)) {
            $group_accessories = $product_accessories[$id_group];
        }

        $this->context->smarty->assign(array(
                'groups' => HsAccessoriesGroupAbstract::getGroupById((int)$id_group, (int) $this->context->language->id),
                'id_main_product' => $id_main_product,
                'product_accessories' => $group_accessories,
                'is_prestashop16' => $this->module->isPrestashop16(),
                'default_form_language' => (int) Configuration::get('PS_LANG_DEFAULT'),
                'show_custom_quantity' => (int) Configuration::get('HSMA_SHOW_CUSTOM_QUANTITY'),
                'id_lang' => $this->context->language->id,
                'img_path' => $this->module->getImgPath(),
                'is_ps17' => (int) $this->module->isPrestashop17(),
                'buy_together_default' => HsMaProductSetting::getBuyTogetherDefault($id_main_product),
            ));
        return $this->context->smarty->fetch(_PS_MODULE_DIR_ . $this->module->name . '/views/templates/hook/setting_display_accessory.tpl');
    }
}
