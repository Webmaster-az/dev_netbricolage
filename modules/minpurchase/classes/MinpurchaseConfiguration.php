<?php
/**
* Minimum and maximum unit quantity to purchase
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate
*  @copyright 2023 idnovate
*  @license   See above
*/

class MinpurchaseConfiguration extends ObjectModel
{
    public $id_minpurchase_configuration;
    public $name;
    public $minimum_quantity;
    public $maximum_quantity;
    public $groups;
    public $customers;
    public $countries;
    public $zones;
    public $categories;
    public $products;
    public $manufacturers;
    public $suppliers;
    public $currencies;
    public $languages;
    public $active = true;
    public $priority;
    public $id_shop;
    public $date_add;
    public $date_upd;
    public $date_from;
    public $date_to;
    public $features;
    public $attributes;
    public $price_calculate;
    public $min_price;
    public $max_price;
    public $filter_prices;
    public $filter_stock;
    public $filter_store;
    public $alternative_text;
    public $min_stock;
    public $max_stock;
    public $multiple;
    public $multiple_qty;
    public $increment;
    public $increment_qty;
    public $separated;
    public $show_text;
    public $minimum_amount;
    public $maximum_amount;
    public $grouped_by;
    public $schedule;
    public $products_excluded;
    public $customers_excluded;
    public $days;
    public $orders_date_from;
    public $orders_date_to;
    public $orders_period;
    public $order_states;
    public $max_qty_stock;

    public $filter_weight;
    public $min_weight;
    public $max_weight;    

    public $order_total_type;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'minpurchase_configuration',
        'primary' => 'id_minpurchase_configuration',
        'multilang' => false,
        'fields' => array(
            'name' =>               array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 100),
            'multiple' =>           array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'increment' =>          array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'minimum_quantity' =>   array('type' => self::TYPE_INT, 'copy_post' => false),
            'maximum_quantity' =>   array('type' => self::TYPE_INT, 'copy_post' => false),
            'multiple_qty' =>       array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'increment_qty' =>      array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'groups' =>             array('type' => self::TYPE_STRING),
            'countries' =>          array('type' => self::TYPE_STRING),
            'products' =>           array('type' => self::TYPE_STRING),
            'customers' =>          array('type' => self::TYPE_STRING),
            'zones' =>              array('type' => self::TYPE_STRING),
            'categories' =>         array('type' => self::TYPE_STRING),
            'manufacturers' =>      array('type' => self::TYPE_STRING),
            'currencies' =>         array('type' => self::TYPE_STRING),
            'languages' =>          array('type' => self::TYPE_STRING),
            'suppliers' =>          array('type' => self::TYPE_STRING),
            'features' =>           array('type' => self::TYPE_STRING),
            'attributes' =>         array('type' => self::TYPE_STRING),
            'active' =>             array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'priority' =>           array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'id_shop' =>            array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'date_add' =>           array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'copy_post' => false),
            'date_upd' =>           array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'copy_post' => false),
            'date_from' =>          array('type' => self::TYPE_DATE, 'copy_post' => false),
            'date_to' =>            array('type' => self::TYPE_DATE, 'copy_post' => false),
            'price_calculate' =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'filter_prices' =>      array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'filter_store' =>       array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'min_price' =>          array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'max_price' =>          array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'filter_stock' =>       array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'min_stock' =>          array('type' => self::TYPE_INT, 'copy_post' => false),
            'max_stock' =>          array('type' => self::TYPE_INT, 'copy_post' => false),
            'separated' =>          array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'show_text' =>          array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false),
            'minimum_amount' =>     array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'maximum_amount' =>     array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'grouped_by' =>         array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'schedule' =>           array('type' => self::TYPE_STRING),
            'products_excluded' =>  array('type' => self::TYPE_STRING),
            'customers_excluded' => array('type' => self::TYPE_STRING),
            'days' =>               array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'orders_date_from' =>   array('type' => self::TYPE_DATE, 'copy_post' => false),
            'orders_date_to' =>     array('type' => self::TYPE_DATE, 'copy_post' => false),
            'orders_period' =>      array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'order_states' =>       array('type' => self::TYPE_STRING),
            'max_qty_stock' =>      array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'min_weight' =>         array('type' => self::TYPE_FLOAT, 'copy_post' => false),
            'max_weight' =>         array('type' => self::TYPE_FLOAT, 'copy_post' => false),
            'filter_weight' =>      array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
            'order_total_type' =>      array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'copy_post' => false),
        ),
    );

    public function __construct($id = null, $id_lang = null)
    {
        parent::__construct($id, $id_lang);
    }

    public function add($autodate = true, $null_values = true)
    {
        $this->id_shop = ($this->id_shop) ? $this->id_shop : Context::getContext()->shop->id;
        $success = parent::add($autodate, $null_values);
        return $success;
    }

    public function toggleStatus()
    {
        parent::toggleStatus();
        return Db::getInstance()->execute('
        UPDATE `'._DB_PREFIX_.bqSQL($this->def['table']).'`
        SET `date_upd` = NOW()
        WHERE `'.bqSQL($this->def['primary']).'` = '.(int)$this->id);
    }

    public function delete()
    {
        if (parent::delete()) {
            return $this->deleteImage();
        }
    }

    public static function getConfigurations($id_product = 0, $id_product_attribute = 0, $grouped = false)
    {
        $context = Context::getContext();
        if (isset($context->controller) && in_array($context->controller->controller_type, array('admin'))) {
            return false;
        }

        $id_shop = $context->shop->id;
        $id_lang = $context->language->id;
        $id_customer = $context->customer->id;
        $id_currency = $context->cookie->id_currency;

        $id_country = 0;
        $id_state = 0;
        if (isset($context->cart->id_address_delivery) && $context->cart->id_address_delivery != 0) {
            $id_address_delivery = $context->cart->id_address_delivery;
            $address = new Address($id_address_delivery);
            $id_country = $address->id_country;
            $id_state = $address->id_state;
        }

        if ($id_country == 0) {
            $id_country = $context->country->id;
        }

        $cache_key = 'Minpurchase::getConfigurations_'.(int)$id_shop.'_'.(int)$id_product.'_'.(int)$id_customer.'_'.$id_country.'_'.$id_state.'_'.$id_currency.'_'.(int)$id_lang.'_'.$id_product_attribute;

        if (Cache::isStored($cache_key)) {
            return Cache::retrieve($cache_key);
        }

        $query = '';
        $today = date("Y-m-d H:i:s");

        $query = '
                    SELECT c.* FROM `'._DB_PREFIX_.'minpurchase_configuration` c WHERE c.`id_shop` = '.(int)$id_shop.'
                    AND c.`active` = 1';

        $datefilters = ' AND (date_from <= "'.$today. '" OR date_from = "0000-00-00 00:00:00") AND (date_to >= "'.$today.'" OR date_to = "0000-00-00 00:00:00")';

        $query = $query.$datefilters;

        $customer_groups = array();
        if (isset($context->customer)) {
            $id_customer = $context->customer->id;
            $customer_groups = Customer::getGroupsStatic($id_customer);
        } else {
            $id_customer = 0;
        }

        if ($id_customer) {
            $sql_customers = ' AND (c.customers = "" OR FIND_IN_SET("'.$id_customer.'", REPLACE(c.customers, ";", ",")) > 0)';
            $query = $query.$sql_customers;
        }

        /*$sql_groups = ' AND (c.groups = "" ';
        foreach ($customer_groups as $cgroup) {
            $sql_groups = $sql_groups. ' OR FIND_IN_SET('.$cgroup.', REPLACE(c.groups, ";", ",")) > 0';
        }
        $sql_groups = $sql_groups.')';
        $query = $query.$sql_groups;*/

        /*$query = $query.' AND (c.currencies = "" OR FIND_IN_SET('.$id_currency.', REPLACE(c.currencies, ";", ",")) > 0)';
        $query = $query.' AND (c.languages = "" OR FIND_IN_SET('.$id_lang.', REPLACE(c.languages, ";", ",")) > 0)';
        $query = $query.' AND (c.countries = "" OR FIND_IN_SET('.$id_country.', REPLACE(c.countries, ";", ",")) > 0) ';*/


        $query .= ' ORDER BY c.`priority`, c.`id_minpurchase_configuration` ASC';

        $configurations = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        if ($configurations === false) {
            return false;
        }

        $categories = Product::getProductCategories($id_product);
        $product = new Product($id_product);
        $product_attribute_combinations = $product->getAttributeCombinationsById($id_product_attribute, $id_lang);
        $id_manufacturer = $product->id_manufacturer;
        $id_supplier = $product->id_supplier;
        $zone = 0;
        if ($id_state > 0) {
            $zone = State::getIdZone($id_state);
        } else if ($id_country != null && $id_country > 0) {
            $zone = Country::getIdZone($id_country);
        }

        $configs_result = array();
        foreach ($configurations as $conf) {
            if (!MinpurchaseConfiguration::checkExceptions($conf, $id_product, $id_customer)) {
                continue;
            }

            if (!MinpurchaseConfiguration::isShowableBySchedule($conf)) {
                continue;
            }

            if (!MinpurchaseConfiguration::checkStockPrice($conf, $id_product, $id_product_attribute)) {
                continue;
            }

            if ($conf['features'] == 'all' || empty($conf['features'])) {
                $conf['features'] = '';
            }
            if ($conf['attributes'] == 'all' || empty($conf['attributes'])) {
                $conf['attributes'] = '';
            }

            if ($conf['attributes'] == '' && $conf['features'] == '' && $conf['currencies'] == '' && $conf['languages'] == '' && $conf['groups'] == '' && $conf['products'] == '' && $conf['customers'] == '' && $conf['countries'] == '' && $conf['zones'] == '' && $conf['categories'] == '' && $conf['manufacturers'] == '' && $conf['suppliers'] == '') {
                if ($grouped) {
                    $configs_result[] = $conf;
                } else {
                    return $conf;
                }
            }

            $filter_features = false;
            $array_features_selected = json_decode($conf['features'], true);
            $product_features = Product::getFeaturesStatic((int)$id_product);

            $flag_features = 0;
            if (!empty($array_features_selected) && count($array_features_selected) > 0) {
                foreach ($product_features as $pf) {
                    if (isset($array_features_selected[$pf['id_feature']])) {
                        $array_f = explode(";", $array_features_selected[$pf['id_feature']]);
                        if (in_array($pf['id_feature_value'], $array_f)) {
                            $flag_features++;
                            continue;
                        }
                    }
                }
            } else {
                $filter_features = true;
            }

            if ($flag_features > 0 && $flag_features == count($array_features_selected)) {
                $filter_features = true;
            }

            if ($id_product_attribute == 0 && ($conf['attributes'] != "[]" || empty($conf['attributes']))) {
                continue;
            }

            $filter_attributes = false;
            if ($id_product_attribute == 0 && empty($conf['attributes'])) {
                $filter_attributes = true;
            } else if (Module::isEnabled('attributewizardpro')) {
                $array_attributes_selected = json_decode($conf['attributes'], true);
                foreach ($products as $key => $prod) {
                    if (!empty($prod['instructions_id']) && $prod['id_product_attribute'] == $id_product_attribute) {
                        $array_instructions = explode(',', $prod['instructions_id']);
                        $result = array_intersect($array_instructions, $array_attributes_selected);

                        if (count($result) > 0) {
                            $filter_attributes = true;
                        }
                    }
                }
            } else {
                $array_attributes_selected = json_decode($conf['attributes'], true);
                $counter = 0;
                if (!empty($array_attributes_selected)) {
                    foreach ($product_attribute_combinations as $key => $prod_attr_comb) {
                        if (isset($array_attributes_selected[(int)$prod_attr_comb['id_attribute_group']])) {
                            $array_a = explode(";", $array_attributes_selected[(int)$prod_attr_comb['id_attribute_group']]);
                            if (in_array((int)$prod_attr_comb['id_attribute'], $array_a)) {
                                $counter++;
                            }
                        }
                    }
                    if ($counter == count($array_attributes_selected)) {
                        $filter_attributes = true;
                    }
                } else {
                    $filter_attributes = true;
                }
            }

            $filter_currencies = true;
            if ($conf['currencies'] !== '') {
                $currencies_array = explode(';', $conf['currencies']);
                if (!in_array($id_currency, $currencies_array)) {
                    $filter_currencies = false;
                }
            }
            $filter_languages = true;
            if ($conf['languages'] !== '') {
                $languages_array = explode(';', $conf['languages']);
                if (!in_array($id_lang, $languages_array)) {
                    $filter_languages = false;
                }
            }

            $filter_groups = true;
            $filter_customers = true;
            if ($conf['groups'] !== '' && $conf['customers'] == '') {
                $groups_array = explode(';', $conf['groups']);
                foreach ($customer_groups as $group) {
                    if (!in_array($group, $groups_array)) {
                        $filter_groups = false;
                    } else {
                        $filter_groups = true;
                        break;
                    }
                }
                if (!$filter_groups) {
                    $filter_customers = false;
                }
            } else if ($conf['groups'] == '' && $conf['customers'] !== '') {
                $customers_array = explode(';', $conf['customers']);
                if (!in_array($id_customer, $customers_array)) {
                    $filter_customers = false;
                }
            } else if ($conf['groups'] !== '' && $conf['customers'] !== '') {
                $groups_array = explode(';', $conf['groups']);
                foreach ($customer_groups as $group) {
                    if (!in_array($group, $groups_array)) {
                        $filter_groups = false;
                    } else {
                        $filter_groups = true;
                    }
                }
                if (!$filter_groups) {
                    $customers_array = explode(';', $conf['customers']);
                    if (!in_array($id_customer, $customers_array)) {
                        $filter_customers = false;
                    } else {
                        $filter_customers = true;
                    }
                } else {
                    $customers_array = explode(';', $conf['customers']);
                    if (!in_array($id_customer, $customers_array)) {
                        $filter_customers = false;
                    }
                }
            }
            $filter_countries = true;
            if ($conf['countries'] !== '') {
                $countries_array = explode(';', $conf['countries']);

                if (!in_array($id_country, $countries_array)) {
                    $filter_countries = false;
                }
            }

            $filter_zones = true;
            if ($conf['zones'] !== '') {
                $zones_array = explode(';', $conf['zones']);
                if (!in_array($zone, $zones_array)) {
                    $filter_zones = false;
                }
            }

            $filter_categories = true;
            $filter_products = true;

            if ($id_product > 0) {
                if (@unserialize($conf['categories']) !== false) {
                    $categories_array = unserialize($conf['categories']);
                } else {
                    $categories_array = explode(';', $conf['categories']);
                }

                if ($conf['categories'] !== '' && $conf['products'] == '') {
                    foreach ($categories as $category) {
                        if (in_array($category, $categories_array)) {
                            $filter_categories = true;
                            $filter_products = true;
                            break;
                        } else {
                            $filter_categories = false;
                        }
                    }
                    if (!$filter_categories) {
                        $filter_products = false;
                    }
                } else if ($conf['categories'] == '' && $conf['products'] !== '') {
                    $products_array = explode(';', $conf['products']);
                    if (!in_array($id_product, $products_array)) {
                        $filter_products = false;
                        $filter_categories = true;
                    }
                } else if ($conf['categories'] !== '' && $conf['products'] !== '') {
                    foreach ($categories as $category) {
                        if (!in_array($category, $categories_array)) {
                            $filter_categories = false;
                        } else {
                            $filter_categories = true;
                            break;
                        }
                    }
                    if (!$filter_categories) {
                        $products_array = explode(';', $conf['products']);
                        if (!in_array($id_product, $products_array)) {
                            $filter_products = false;
                        } else {
                            $filter_products = true;
                        }
                    } else {
                        $products_array = explode(';', $conf['products']);
                        if (!in_array($id_product, $products_array)) {
                            $filter_products = false;
                        }
                    }
                }
            }

            $filter_manufacturers = true;
            if ($conf['manufacturers'] !== '') {
                $manufacturers_array = explode(';', $conf['manufacturers']);
                if (!in_array($id_manufacturer, $manufacturers_array)) {
                    $filter_manufacturers = false;
                }
            }
            $filter_suppliers = true;
            if ($conf['suppliers'] !== '') {
                $suppliers_array = explode(';', $conf['suppliers']);
                if (!in_array($id_supplier, $suppliers_array)) {
                    $filter_suppliers = false;
                }
            }

/*$logger = new FileLogger(0);
$logger->setFilename(_PS_ROOT_DIR_.'/log/debug.log');
$logger->logDebug("idproduct: ".print_r($id_product, true));
$logger->logDebug("conf name: ".print_r($conf['name'], true));
$logger->logDebug("filter_groups: ".print_r($filter_groups, true));
$logger->logDebug("filter_customers: ".print_r($filter_customers, true));
$logger->logDebug("filter_countries: ".print_r($filter_countries, true));
$logger->logDebug("filter_zones: ".print_r($filter_zones, true));
$logger->logDebug("filter_categories: ".print_r($filter_categories, true));
$logger->logDebug("filter_products: ".print_r($filter_products, true));
$logger->logDebug("filter_manufacturers: ".print_r($filter_manufacturers, true));
$logger->logDebug("filter_suppliers: ".print_r($filter_suppliers, true));
$logger->logDebug("filter_attributes: ".print_r($filter_attributes, true));
$logger->logDebug("filter_features: ".print_r($filter_features, true));
$logger->logDebug("filter_currencies: ".print_r($filter_currencies, true));
$logger->logDebug("filter_languages: ".print_r($filter_languages, true));*/

            if ($filter_currencies && $filter_languages && $filter_attributes && $filter_features && $filter_groups && $filter_customers
                && $filter_countries && $filter_zones && $filter_categories && $filter_products && $filter_manufacturers && $filter_suppliers) {
                if ($grouped) {
                    $configs_result[] = $conf;
                } else {
                    return $conf;
                }
            }
        }

        Cache::store($cache_key, $configs_result);

        if (count($configs_result) > 0) {
            return $configs_result;
        } else {
            return false;
        }
    }


    public function nextIncrement($value, $increment, $minimum)
    {
        if ($increment == $minimum) {
            return MinpurchaseConfiguration::nextMultiple($value, $increment);
        } else {
            $modValue = $value % $increment;
            if ($modValue == 0) {
                return $value + $increment;
            } else {
                    return ($value - $modValue) + $increment;
            }
        }
    }

    public function previousIncrement($value, $increment, $minimum)
    {
        if ($value == $minimum) {
            return $minimum;
        }

        $difference = $value - $minimum;
        $modTempValue = $difference % $increment;

        if ($modTempValue == 0) {
            return $value;
        }
        return $value - $modTempValue;
    }


    public function nextMultiple($value, $multiple) {
        if ($value % $multiple == 0) {
            return $value + $multiple;
        }
        return ceil($value / $multiple) * $multiple;
    }

    public function previousMultiple($value, $multiple, $minimum_quantity) {
        if ($value % $multiple == 0) {
            $retValue = $value - $multiple;
        } else {
            $retValue = floor($value / $multiple) * $multiple;
        }

        if ($retValue < $minimum_quantity) {
            return $minimum_quantity;
        }
        return $retValue;
    }

    public function isValidProduct($conf, $id_product = false, $id_product_attribute = false)
    {
        if (!$id_product) {
            return false;
        }

        $prod = new Product($id_product);

        $id_lang = Context::getContext()->cookie->id_lang;

        if ($conf['filter_stock']) {
            if (!$prod->hasAttributes()) {
                $stock = Product::getQuantity($id_product);
            } else if ($conf['attributes'] != '') {
                $stock = StockAvailable::getQuantityAvailableByProduct($id_product, $id_product_attribute);
            } else {
                $stock = Product::getQuantity($id_product);
            }

            if ($stock < $conf['min_stock'] || $stock > $conf['max_stock']) {
                return false;
            }
        }

        if ($conf['filter_prices']) {
            if ($conf['price_calculate'] == 0) {
                $price = $prod->wholesale_price;
            }
            if ($conf['price_calculate'] == 1) {
                $price = Product::getPriceStatic($id_product, false, null, 6, false, false, true);
            }
            if ($conf['price_calculate'] == 2) {
                $price = $prod->wholesale_price;
            }
            if ($conf['price_calculate'] == 3) {
                $price = Product::getPriceStatic($id_product, true, null, 6, false, false, true);
            }

            if ($price < $conf['min_price'] || $price > $conf['max_price']) {
                return false;
            }
        }

        if ($conf['attributes'] == '' && $conf['features'] == '' && $conf['products'] == '' && $conf['categories'] == '' && $conf['manufacturers'] == '' && $conf['suppliers'] == '') {
            return true;
        }

        $array_features_selected = json_decode($conf['features'], true);
        $flag_features = 0;
        if (!empty($array_features_selected)) {
            $product_features = Product::getFeaturesStatic((int)$id_product);
            foreach ($product_features as $pf) {
                if (isset($array_features_selected[$pf['id_feature']])) {
                    $array_f = explode(";", $array_features_selected[$pf['id_feature']]);
                    if (in_array($pf['id_feature_value'], $array_f)) {
                        $flag_features++;
                        continue;
                    }
                }
            }
            if ($flag_features == 0) {
                return false;
            }
        }

        if ($id_product_attribute > 0) {
            $array_attributes_selected = json_decode($conf['attributes'], true);
            $product_attribute_combinations = $prod->getAttributeCombinationsById($id_product_attribute, $id_lang);
            if (!empty($array_attributes_selected) && (count($array_attributes_selected) > 0)) {
                foreach ($product_attribute_combinations as $prod_attr_comb) {
                    if (isset($array_attributes_selected[$prod_attr_comb['id_attribute_group']])) {
                        $array_a = explode(";", $array_attributes_selected[$prod_attr_comb['id_attribute_group']]);
                        if (!in_array($prod_attr_comb['id_attribute'], $array_a)) {
                            return false;
                        }
                    }
                }
            }
        }

        if ($id_product > 0) {
            if ($conf['products']) {
                if (!in_array($id_product, explode(';', $conf['products']))) {
                    return false;
                }
            }
            if (@unserialize($conf['categories']) !== false) {
                $categories_array = unserialize($conf['categories']);
            } else {
                $categories_array = explode(';', $conf['categories']);
            }

            if ($conf['categories'] !== '' && $conf['products'] == '') {
                $categories = Product::getProductCategories($id_product);
                foreach ($categories as $category) {
                    if (!in_array($category, $categories_array)) {
                        return false;
                    }
                }
            } else if ($conf['categories'] == '' && $conf['products'] !== '') {
                $products_array = explode(';', $conf['products']);
                if (!in_array($id_product, $products_array)) {
                    return false;
                }
            } else if ($conf['categories'] !== '' && $conf['products'] !== '') {
                $categories = Product::getProductCategories($id_product);
                foreach ($categories as $category) {
                    if (!in_array($category, $categories_array)) {
                        $filter_categories = false;
                    } else {
                        $filter_categories = true;
                        break;
                    }
                }

                if (!$filter_categories) {
                    $products_array = explode(';', $conf['products']);
                    if (!in_array($id_product, $products_array)) {
                        return false;
                    }
                }
            }
        }

        $filter_manufacturers = true;
        if ($conf['manufacturers'] !== '') {
            $id_manufacturer = $prod->id_manufacturer;
            $manufacturers_array = explode(';', $conf['manufacturers']);
            if (!in_array($id_manufacturer, $manufacturers_array)) {
                return false;
            }
        }

        $filter_suppliers = true;
        if ($conf['suppliers'] !== '') {
            $suppliers = ProductSupplier::getSupplierCollection($id_product);
            $suppliers_array = explode(';', $conf['suppliers']);
            if (!in_array($id_supplier, $suppliers_array)) {
                return false;
            }
        }

        return true;
    }

    public static function checkStockPriceWeight($conf, $id_product = 0, $id_product_attribute = 0)
    {
        $product = new Product($id_product);
        if ($conf['filter_stock']) {
            if (!$product->hasAttributes()) {
                $stock = Product::getQuantity($id_product);
            } else if ($conf['attributes'] != '') {
                $stock = StockAvailable::getQuantityAvailableByProduct($id_product, $id_product_attribute);
            } else {
                $stock = Product::getQuantity($id_product);
            }

            if (($conf['max_stock'] > 0 && $conf['min_stock'] > 0) || ($conf['max_stock'] > 0 && $conf['min_stock'] <= 0)) {
                if ((int)$stock < $conf['min_stock'] || (int)$stock > $conf['max_stock']) {
                    return false;
                }
            } else if ($conf['max_stock'] <= 0 && $conf['min_stock'] <= 0) {
                if ((int)$stock > 0 || ($stock > $conf['max_stock'] && $stock < $conf['min_stock'])) {
                    return false;
                } else if ($conf['hide_price_status']) {
                    $out_of_stock = Product::isAvailableWhenOutOfStock((int)StockAvailable::outOfStock($id_product));
                    if ($out_of_stock) {
                        return false;
                    }
                } else if ($conf['max_stock'] == 0 && $conf['min_stock'] == 0) {
                    if ($stock != 0) {
                        return false;
                    }
                }
            }
        }

        if ($conf['filter_weight']) {
            $weight = $product->weight;
            if ($product->hasAttributes()) {
                $combination = new Combination($id_product_attribute);
                $weight += $combination->weight;
            }

            if ($weight < $conf['min_weight'] || ($conf['max_weight'] > 0 && $weight > $conf['max_weight'])) {
                return false;
            }
        }

        if ($conf['filter_prices']) {
            $price = Product::getPriceStatic((int)$id_product, false, 0, 6, null, false, false, 1, false, Context::getContext()->customer->id);
            $price_withtax = Product::getPriceStatic((int)$id_product, true, 0, 6, null, false, false, 1, false, Context::getContext()->customer->id);

            if ($conf['price_calculate'] == 0) {
                $price_to_compare = $product->wholesale_price;
            } else if ($conf['price_calculate'] == 1) {
                $price_to_compare = $price;
            } else if ($conf['price_calculate'] == 2) {
                $price_to_compare = $product->wholesale_price;
            } else if ($conf['price_calculate'] == 3) {
                $price_to_compare = $price_withtax;
            }

            $context = Context::getContext();
            if (isset($context->controller) && !in_array($context->controller->controller_type, array('admin'))) {
                if (is_string(Context::getContext()->currency)) {
                    $currencyConvert = new Currency(Context::getContext()->currency);
                } else {
                    $currencyConvert = Context::getContext()->currency;
                }

                if ((float)$conf['min_price'] > 0) {
                    $threshold_min = Tools::convertPriceFull((float)$conf['min_price'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), $currencyConvert);
                } else {
                    $threshold_min = 0;
                }
                if ((float)$conf['max_price'] > 0) {
                    $threshold_max = Tools::convertPriceFull((float)$conf['max_price'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), $currencyConvert);
                } else {
                    $threshold_max = 0;
                }
                if ((float)$price_to_compare > 0) {
                    $price_to_compare = Tools::convertPriceFull((float)$price_to_compare, new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), $currencyConvert);
                }
            } else {
                $threshold_max = (float)$conf['min_price'];
                $threshold_min = (float)$conf['max_price'];
            }

            if ((float)$threshold_max == 0 && (float)$threshold_min == 0 && (float)$price_to_compare == 0) {
                return true;
            } else if ((float)$threshold_max == 0 && (float)$threshold_min == 0 && (float)$price_to_compare != 0) {
                return false;
            } else if ((float)$threshold_max != 0 && (float)$threshold_min == 0 && (float)$price_to_compare > (float)$threshold_max) {
                return false;
            } else if ((float)$threshold_max == 0 && (float)$threshold_min != 0 && (float)$price_to_compare < (float)$threshold_min) {
                return false;
            } else if ((float)$threshold_max != 0 && (float)$threshold_min != 0 && ((float)$price_to_compare < (float)$threshold_min || (float)$price_to_compare > (float)$threshold_max)) {
                return false;
            }
        }
        return true;
    }


    public static function checkProductsAvailability($products = null)
    {
        if (Tools::getValue('delete')) {
            return false;
        }

        /* configuration grouped_by: 0-> non grouped / 1-> product / 2-> category / 3-> manufacturer / 4-> supplier */
        //var_dump($products);
        $errors = array();
        if (!empty($products)) {
            foreach ($products as $p) {
                $configs = MinpurchaseConfiguration::getConfigurations($p['id_product'], $p['id_product_attribute'], true);

                if (!empty($configs)) {
                    foreach ($configs as $config) {
                        switch ($config['grouped_by']) {
                            case 0: // non grouped
                                $result = MinpurchaseConfiguration::checkProductUngrouped($p, $products, $config);
                                if (!empty($result)) {
                                    $errors[] = $result;
                                }
                                break;
                            case 1:
                                $result = MinpurchaseConfiguration::checkProductsGrouped($products, $config, $p['id_product'], $p['id_product_attribute']);
                                if (!empty($result)) {
                                    $errors[] = $result;
                                }
                                break;
                            case 2:
                                $result = MinpurchaseConfiguration::checkCategoriesGrouped($products, $config, $p['id_product'], $p['id_product_attribute']);
                                if (!empty($result)) {
                                    $errors[] = $result;
                                }
                                break;
                            case 3:
                                $result = MinpurchaseConfiguration::checkManufacturersGrouped($products, $config, $p['id_product'], $p['id_product_attribute']);
                                if (!empty($result)) {
                                    $errors[] = $result;
                                }
                                break;
                            case 4:
                                $result = MinpurchaseConfiguration::checkSuppliersGrouped($products, $config, $p['id_product'], $p['id_product_attribute']);
                                if (!empty($result)) {
                                    $errors[] = $result;
                                }
                                break;
                            case 5:
                                $result = MinpurchaseConfiguration::checkAttributesGrouped($products, $config, $p['id_product'], $p['id_product_attribute']);
                                if (!empty($result)) {
                                    $errors[] = $result;
                                }
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }

        if (!empty($errors) && is_array($errors)) {
            return array_filter(array_unique($errors));
        } else {
            return array();
        }
    }

    protected static function checkProductUngrouped($product = null, $products = null, $configuration)
    {

        $errors = array();
        $quantityToCheck = 0;
        $priceToCheck = 0;
        $name = '';

        if (Context::getContext()->customer->id) {
            $id_customer = Context::getContext()->customer->id;
        } else {
            $id_customer = 0;
        }

        $maximum_quantity = 0;

        if ($configuration['max_qty_stock']) {
            $stock = StockAvailable::getQuantityAvailableByProduct($product['id_product'], $product['id_product_attribute']);
            if ($stock > 0) {
                $maximum_quantity = $stock;
            }
        } else {
            $maximum_quantity = $configuration['maximum_quantity'];
        }


        if ($maximum_quantity > 0 && $id_customer && ($configuration['days'] || $configuration['orders_date_from'] > 0 || $configuration['orders_date_to'] > 0 || $configuration['orders_period'] > 0)) {
            //$orders = Order::getCustomerOrders($id_customer);
            $orders = MinpurchaseConfiguration::getOrdersIdByDateAndState($configuration, $id_customer);
            
            if ($configuration['products'] !== '') {
                $products_array = explode(';', $configuration['products']);
            } else {
                $products_array = array();
            }
            $flagOrder = false;
            foreach ($orders as $order) {
                $o = new Order($order);
                foreach ($o->getProducts() as $p) {
                    
                    if (!empty($products_array)) {
                        if ($configuration['separated']) {
                            if (in_array($p['id_product'], $products_array) && $p['id_product'] == $product['id_product'] && $p['product_attribute_id'] == $product['id_product_attribute']) {
                                $quantityToCheck += $p['product_quantity'];
                                $name = $p['product_name'] . ", ";
                                $flagOrder = true;
                                break;
                            }
                        } elseif (in_array($p['id_product'], $products_array) && $p['id_product'] == $product['id_product']) {
                            $quantityToCheck += $p['product_quantity'];
                            $name = $p['product_name'] . ", ";
                            break;
                        }
                    } else if ($p['id_product'] == $product['id_product']) {
                        $quantityToCheck += $p['product_quantity'];
                        $flagOrder = true;
                    }
                }
            }

            if ($maximum_quantity > 0 && $quantityToCheck + $product['cart_quantity'] > $maximum_quantity) {
                $mod = new Minpurchase();
                if ($flagOrder) {
                    if (empty($name)) {
                        $name = $product['name'];
                    }
                    $errors = $mod->getMessageAvailable($name, $quantityToCheck, 'maxdays', false, (int)$maximum_quantity - $quantityToCheck);
                } else {
                    $errors = $mod->getMessageAvailable($product['name'], $maximum_quantity, 'max', false, (int)$maximum_quantity - $quantityToCheck);
                }
                return $errors;
            }
        }

        if ($configuration['separated']) {
            foreach ($products as $prodToCount) {
                if ($prodToCount['id_product'] == $product['id_product']) {
                    if (strcmp($name, $prodToCount['name'] . ", ") !== 0) {
                        $name .= $prodToCount['name'] . ", ";
                    }
                    $quantityToCheck += $prodToCount['cart_quantity'];
                    if ((float)$configuration['minimum_amount'] > 0 || (float)$configuration['maximum_amount'] > 0) {
                        $priceToCheck += Product::getPriceStatic((int)$prodToCount['id_product'], true, $prodToCount['id_product_attribute'], 6, false, false) * $prodToCount['cart_quantity'];
                    }
                }
            }
        } else {
            $quantityToCheck += $product['cart_quantity'];
            $name = $product['name'];
        }

        if (is_string(Context::getContext()->currency)) {
            $currencyConvert = new Currency(Context::getContext()->currency);
        } else {
            $currencyConvert = Context::getContext()->currency;
        }

        $configuration['minimum_amount'] = Tools::convertPriceFull((float)$configuration['minimum_amount'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), $currencyConvert);
        $configuration['maximum_amount'] = Tools::convertPriceFull((float)$configuration['maximum_amount'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), $currencyConvert);

        if ($quantityToCheck < $configuration['minimum_quantity']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['minimum_quantity'], 'min');
        } else if ($maximum_quantity > 0 && $quantityToCheck > $maximum_quantity) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $maximum_quantity, 'max');
        } else if ($configuration['multiple_qty'] > 0 && $quantityToCheck % $configuration['multiple_qty'] != 0) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['multiple_qty'], 'mult');
        } else if ((float)$configuration['minimum_amount'] > 0 && (float)$priceToCheck < (float)$configuration['minimum_amount']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, Tools::displayPrice($configuration['minimum_amount'], $currencyConvert), 'minamount', 'grouped');
        } else if ((float)$configuration['maximum_amount'] > 0 && (float)$priceToCheck > (float)$configuration['maximum_amount']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, Tools::displayPrice($configuration['maximum_amount'], $currencyConvert), 'maxamount', 'grouped');
        }

        return $errors;
    }

    protected static function checkAttributesGrouped($products, $configuration, $id_product, $id_product_attribute)
    {
        if ($configuration['attributes']) {
            $array_attributes_selected = json_decode($configuration['attributes'], true);
        }

        $quantityToCheck = 0;
        $priceToCheck = 0;
        $name = '';
        if (!empty($array_attributes_selected) && $id_product_attribute > 0) {
            foreach ($products as $p) {
                $product = new Product($p['id_product']);
                $product_attribute_combinations = $product->getAttributeCombinationsById($p['id_product_attribute'], $id_lang);
                foreach ($product_attribute_combinations as $key => $prod_attr_comb) {
                    if (isset($array_attributes_selected[(int)$prod_attr_comb['id_attribute_group']])) {
                        $array_a = explode(";", $array_attributes_selected[(int)$prod_attr_comb['id_attribute_group']]);
                        if (in_array((int)$prod_attr_comb['id_attribute'], $array_a) && $prod_attr_comb['id_product'] == $id_product) {
                            $quantityToCheck += (int)$p['cart_quantity'];
                            $name = $p['name'];
                            if ((float)$configuration['minimum_amount'] > 0 || (float)$configuration['maximum_amount'] > 0) {
                                $priceToCheck += Product::getPriceStatic((int)$p['id_product'], true, $p['id_product_attribute'], 6, null, false, false, (int)$p['cart_quantity'], false);
                            }
                            break;
                        }
                    }
                }
            }
        }

        $errors = array();
        if ($quantityToCheck < $configuration['minimum_quantity']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['minimum_quantity'], 'min', 'grouped');
        } else if ($configuration['maximum_quantity'] > 0 && $quantityToCheck > $configuration['maximum_quantity']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['maximum_quantity'], 'max', 'grouped');
        } else if ($configuration['multiple_qty'] > 0 && $quantityToCheck % $configuration['multiple_qty'] != 0) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['multiple_qty'], 'mult', 'grouped');
        } else if ((float)$priceToCheck < (float)$configuration['minimum_amount']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, Tools::displayPrice($configuration['minimum_amount']), 'minamount', 'grouped');
        } else if ((float)$configuration['maximum_amount'] > 0 && (float)$priceToCheck > (float)$configuration['maximum_amount']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['maximum_amount'], 'maxamount', 'grouped');
        }
        return $errors;
    }

    protected static function checkProductsGrouped($products, $configuration, $id_product, $id_product_attribute)
    {

        if ($configuration['products'] !== '') {
            $products_array = explode(';', $configuration['products']);
        } else {
            $products_array = array();
        }

        $quantityToCheck = 0;
        $priceToCheck = 0;
        $name = '';
        $cart = new Cart(Context::getContext()->cookie->id_cart);
        $cart_rules = $cart->getCartRules();


        if (!empty($products)) {
            foreach ($products as $p) {
                $found_in_cart = false;
                if (!empty($products_array)) {
                    if (in_array($p['id_product'], $products_array)) {
                        $found_in_cart = true;
                    }
                }
                $gift_prod = false;
                foreach ($cart_rules as $cart_rule) {
                    if ($cart_rule['gift_product'] && $p['id_product'] == $cart_rule['gift_product']) {
                        $gift_prod = true;
                        break;
                    }
                }

                if ($gift_prod) {
                    continue;
                }

                if ((float)$configuration['minimum_amount'] > 0 || (float)$configuration['maximum_amount'] > 0) {
                    //$price = Product::getPriceStatic((int)$id_product, false, 0, 6, null, false, false, 1, false);
                    $priceToCheck += Product::getPriceStatic((int)$p['id_product'], true, 0, 6, null, false, false, (int)$p['cart_quantity'], false);
                }

                if (($p['id_product'] == $id_product && $p['id_product_attribute'] == $id_product_attribute) || $found_in_cart) {
                    $quantityToCheck += (int)$p['cart_quantity'];
                    $name .= $p['name'] . ", ";
                }

            }
        }

        $maximum_quantity = 0;

        if ($configuration['max_qty_stock']) {
            $stock = StockAvailable::getQuantityAvailableByProduct($product['id_product'], $product['id_product_attribute']);
            if ($stock > 0) {
                $maximum_quantity = $stock;
            }
        } else {
            $maximum_quantity = $configuration['maximum_quantity'];
        }

        if (Context::getContext()->customer->id) {
            $id_customer = Context::getContext()->customer->id;
        } else {
            $id_customer = 0;
        }

        if ($maximum_quantity > 0 && $id_customer && ($configuration['days'] || $configuration['orders_date_from'] > 0 || $configuration['orders_date_to'] > 0 || $configuration['orders_period'] > 0)) {
            //$orders = Order::getCustomerOrders($id_customer);
            $orders = MinpurchaseConfiguration::getOrdersIdByDateAndState($configuration, $id_customer);
            
            if ($configuration['products'] !== '') {
                $products_array = explode(';', $configuration['products']);
            } else {
                $products_array = array();
            }

            if (!empty($products)) {
                foreach ($products as $product) {
                    $flagOrder = false;
                    foreach ($orders as $order) {
                        $o = new Order($order);
                        foreach ($o->getProducts() as $p) {
                            $flagOrder = true;
                            if (!empty($products_array)) {
                                if ($configuration['separated']) {
                                    if (in_array($p['id_product'], $products_array) && $p['id_product'] == $product['id_product'] && $p['product_attribute_id'] == $product['id_product_attribute']) {
                                        $quantityToCheck += $p['product_quantity'];
                                        return $mod->getMessageAvailable($product['name'], $maximum_quantity, 'max', false, (int)$maximum_quantity - $quantityToCheck);
                                    }
                                } else if (in_array($p['id_product'], $products_array) && $p['id_product'] == $product['id_product']) {
                                    $quantityToCheck += $p['product_quantity'];
                                    $quantityToCheck += $p['product_quantity'];
                                    return $mod->getMessageAvailable($product['name'], $maximum_quantity, 'max', false, (int)$maximum_quantity - $quantityToCheck);
                                } else if ($p['id_product'] == $product['id_product']) {
                                    $quantityToCheck += $p['product_quantity'];
                                }
                            }
                        }
                    
                        if ($maximum_quantity > 0 && $quantityToCheck + $product['cart_quantity'] > $maximum_quantity) {
                            $mod = new Minpurchase();
                            if ($flagOrder) {
                                if (empty($name)) {
                                    $name = $product['name'];
                                }
                                $errors = $mod->getMessageAvailable($name, $quantityToCheck, 'maxdays', false, (int)$maximum_quantity - $quantityToCheck);
                            } else {
                                $errors = $mod->getMessageAvailable($product['name'], $maximum_quantity, 'max', false, (int)$maximum_quantity - $quantityToCheck);
                            }
                            return $errors;
                        }
                    }
                }
            }
        }

        if (is_string(Context::getContext()->currency)) {
            $currencyConvert = new Currency(Context::getContext()->currency);
        } else {
            $currencyConvert = Context::getContext()->currency;
        }

        $configuration['minimum_amount'] = Tools::convertPriceFull((float)$configuration['minimum_amount'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), $currencyConvert);
        $configuration['maximum_amount'] = Tools::convertPriceFull((float)$configuration['maximum_amount'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), $currencyConvert);

        $errors = array();
        if ($quantityToCheck < $configuration['minimum_quantity']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['minimum_quantity'], 'min');
        } else if ($maximum_quantity > 0 && $quantityToCheck > $maximum_quantity) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $maximum_quantity, 'max');
        } else if ($configuration['multiple_qty'] > 0 && $quantityToCheck % $configuration['multiple_qty'] != 0) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['multiple_qty'], 'mult');
        } else if ((float)$configuration['minimum_amount'] > 0 && (float)$priceToCheck < (float)$configuration['minimum_amount']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, Tools::displayPrice($configuration['minimum_amount'], $currencyConvert), 'minamount', 'grouped');
        } else if ((float)$configuration['maximum_amount'] > 0 && (float)$priceToCheck > (float)$configuration['maximum_amount']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, Tools::displayPrice($configuration['maximum_amount'], $currencyConvert), 'maxamount', 'grouped');
        }
        return $errors;
    }

    protected static function checkCategoriesGrouped($products, $configuration, $id_product, $id_product_attribute)
    {
        $errors = array();
        $categories_array = array();
        if ($configuration['categories'] !== '') {
            if (@unserialize($configuration['categories']) !== false) {
                $categories_array = unserialize($configuration['categories']);
            } else {
                $categories_array = explode(';', $configuration['categories']);
            }
        }

        $quantityToCheck = 0;
        $priceToCheck = 0;
        $name = "";
        foreach ($products as $p) {
            $found_in_category = false;
            if (!empty($categories_array)) {
                $categories = Product::getProductCategories($p['id_product']);
                foreach ($categories as $category) {
                    if (in_array($category, $categories_array)) {
                        $found_in_category = true;
                        break;
                    }
                }
                if (!$found_in_category) {
                    continue;
                }
            } else {
                $found_in_category = true;
            }

            if (($p['id_product'] == $id_product && $p['id_product_attribute'] == $id_product_attribute) || $found_in_category) {
                $quantityToCheck += (int)$p['cart_quantity'];
                $name .= $p['name'] . ", ";
                if (!empty($configuration['categories'])) {
                    if ((float)$configuration['minimum_amount'] > 0 || (float)$configuration['maximum_amount'] > 0) {
                        $priceToCheck += Product::getPriceStatic((int)$p['id_product'], true, $p['id_product_attribute'], 6, false, false) * $p['cart_quantity'];
                    }
                }
            }
        }

        if (empty($configuration['categories']) && ((float)$configuration['minimum_amount'] > 0 || (float)$configuration['maximum_amount'] > 0)) {
            $priceToCheck = Context::getContext()->cart->getOrderTotal(true, $configuration['order_total_type']);
        }

        if (is_string(Context::getContext()->currency)) {
            $currencyConvert = new Currency(Context::getContext()->currency);
        } else {
            $currencyConvert = Context::getContext()->currency;
        }

        $configuration['minimum_amount'] = Tools::convertPriceFull((float)$configuration['minimum_amount'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), $currencyConvert);
        $configuration['maximum_amount'] = Tools::convertPriceFull((float)$configuration['maximum_amount'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), $currencyConvert);

        if ($quantityToCheck < $configuration['minimum_quantity']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['minimum_quantity'], 'min', 'grouped');
        } else if ($configuration['maximum_quantity'] > 0 && $quantityToCheck > $configuration['maximum_quantity']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['maximum_quantity'], 'max', 'grouped');
        } else if ($configuration['multiple_qty'] > 0 && $quantityToCheck % $configuration['multiple_qty'] != 0) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['multiple_qty'], 'mult', 'grouped');
        } else if ((float)$priceToCheck < (float)$configuration['minimum_amount']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, Tools::displayPrice($configuration['minimum_amount'], $currencyConvert), 'minamount', 'grouped');
        } else if ((float)$configuration['maximum_amount'] > 0 && (float)$priceToCheck > (float)$configuration['maximum_amount']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, Tools::displayPrice($configuration['maximum_amount'], $currencyConvert), 'maxamount', 'grouped');
        }
        return $errors;
    }

    protected static function checkManufacturersGrouped($products, $configuration, $id_product, $id_product_attribute)
    {
        if ($configuration['manufacturers'] !== '') {
            $manufacturers_array = explode(';', $configuration['manufacturers']);
        } else {
            $manufacturers = Manufacturer::getManufacturers(false, Context::getContext()->language->id, false);
            if (!empty($manufacturers)) {
                foreach ($manufacturers as $manufacturer) {
                    $manufacturers_array[] = $manufacturer['id_manufacturer'];
                }
            }
        }

        $quantityToCheck = 0;
        $priceToCheck = 0;
        $name = "";
        foreach ($products as $p) {
            $same_manufacturer = false;
            if (!empty($manufacturers_array)) {
                if (in_array($p['id_manufacturer'], $manufacturers_array)) {
                    $same_manufacturer = true;
                }
            }

            if (($p['id_product'] == $id_product && $p['id_product_attribute'] == $id_product_attribute) || $same_manufacturer) {
                $quantityToCheck += (int)$p['cart_quantity'];
                $name .= $p['name'] . ", ";
                if ((float)$configuration['minimum_amount'] > 0 || (float)$configuration['maximum_amount'] > 0) {
                    $priceToCheck += Product::getPriceStatic((int)$p['id_product'], true, $p['id_product_attribute'], 6, false, false) * $p['cart_quantity'];
                }
            }
        }

        if (is_string(Context::getContext()->currency)) {
            $currencyConvert = new Currency(Context::getContext()->currency);
        } else {
            $currencyConvert = Context::getContext()->currency;
        }

        $configuration['minimum_amount'] = Tools::convertPriceFull((float)$configuration['minimum_amount'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), $currencyConvert);
        $configuration['maximum_amount'] = Tools::convertPriceFull((float)$configuration['maximum_amount'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), $currencyConvert);

        if ($quantityToCheck < $configuration['minimum_quantity']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['minimum_quantity'], 'min', 'grouped');
        } else if ($configuration['maximum_quantity'] > 0 && $quantityToCheck > $configuration['maximum_quantity']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['maximum_quantity'], 'max', 'grouped');
        } else if ($configuration['multiple_qty'] > 0 && $quantityToCheck % $configuration['multiple_qty'] != 0) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['multiple_qty'], 'mult');
        } else if ((float)$priceToCheck < (float)$configuration['minimum_amount']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, Tools::displayPrice($configuration['minimum_amount'], $currencyConvert), 'minamount', 'grouped');
        } else if ((float)$configuration['maximum_amount'] > 0 && (float)$priceToCheck > (float)$configuration['maximum_amount']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, Tools::displayPrice($configuration['maximum_amount'], $currencyConvert), 'maxamount', 'grouped');
        }
        return $errors;
    }

    protected static function checkSuppliersGrouped($products, $configuration, $id_product, $id_product_attribute)
    {
        if ($configuration['suppliers'] !== '') {
            $suppliers_array = explode(';', $configuration['suppliers']);
        } else {
            $suppliers_array = Supplier::getSuppliers(false, Context::getContext()->language->id, false);
        }
        $quantityToCheck = 0;
        $priceToCheck = 0;
        $name = "";
        foreach ($products as $p) {
            $same_supplier = false;
            if (!empty($suppliers_array)) {
                if (in_array($p['id_supplier'], $suppliers_array)) {
                    $same_supplier = true;
                }
            }

            if (($p['id_product'] == $id_product && $p['id_product_attribute'] == $id_product_attribute) || $same_supplier) {
                $quantityToCheck += (int)$p['cart_quantity'];
                $name .= $p['name'] . ", ";
                if ((float)$configuration['minimum_amount'] > 0 || (float)$configuration['maximum_amount'] > 0) {
                    $priceToCheck += Product::getPriceStatic((int)$p['id_product'], true, $p['id_product_attribute'], 6, false, false) * $p['cart_quantity'];
                }
            }
        }

        if (is_string(Context::getContext()->currency)) {
            $currencyConvert = new Currency(Context::getContext()->currency);
        } else {
            $currencyConvert = Context::getContext()->currency;
        }

        $configuration['minimum_amount'] = Tools::convertPriceFull((float)$configuration['minimum_amount'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), $currencyConvert);
        $configuration['maximum_amount'] = Tools::convertPriceFull((float)$configuration['maximum_amount'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), $currencyConvert);

        if ($quantityToCheck < $configuration['minimum_quantity']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['minimum_quantity'], 'min', 'grouped');
        } else if ($configuration['maximum_quantity'] > 0 && $quantityToCheck > $configuration['maximum_quantity']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['maximum_quantity'], 'max', 'grouped');
        } else if ($configuration['multiple_qty'] > 0 && $quantityToCheck % $configuration['multiple_qty'] != 0) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, $configuration['multiple_qty'], 'mult', 'grouped');
        } else if ((float)$priceToCheck < (float)$configuration['minimum_amount']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, Tools::displayPrice($configuration['minimum_amount'], $currencyConvert), 'minamount', 'grouped');
        } else if ((float)$configuration['maximum_amount'] > 0 && (float)$priceToCheck > (float)$configuration['maximum_amount']) {
            $mod = new Minpurchase();
            $errors = $mod->getMessageAvailable($name, Tools::displayPrice($configuration['maximum_amount'], $currencyConvert), 'maxamount', 'grouped');
        }
        return $errors;
    }

    public static function checkStockPrice($conf, $id_product = 0, $id_product_attribute = 0)
    {
        $product = new Product($id_product);
        if ($conf['filter_stock']) {
            if (!$product->hasAttributes()) {
                $stock = Product::getQuantity($id_product);
            } else if ($conf['attributes'] != '') {
                $stock = StockAvailable::getQuantityAvailableByProduct($id_product, $id_product_attribute);
            } else {
                $stock = Product::getQuantity($id_product);
            }

            if (($conf['max_stock'] > 0 && $conf['min_stock'] > 0) || ($conf['max_stock'] > 0 && $conf['min_stock'] <= 0)) {
                if ((int)$stock < $conf['min_stock'] || (int)$stock > $conf['max_stock']) {
                    return false;
                }
            } else if ($conf['max_stock'] <= 0 && $conf['min_stock'] <= 0) {
                if ((int)$stock > 0 || $stock >= $conf['max_stock'] || $stock <= $conf['min_stock']) {
                    return false;
                }
            }
        }

        if ($conf['filter_prices']) {
            $price = Product::getPriceStatic((int)$id_product, false, 0, 6, null, false, true, 1, false);
            $price_withtax = Product::getPriceStatic((int)$id_product, true, 0, 6, null, false, true, 1, false);

            if ($conf['price_calculate'] == 0) {
                $price_to_compare = $product->wholesale_price;
            } else if ($conf['price_calculate'] == 1) {
                $price_to_compare = $price;
            } else if ($conf['price_calculate'] == 2) {
                $price_to_compare = $product->wholesale_price;
            } else if ($conf['price_calculate'] == 3) {
                $price_to_compare = $price_withtax;
            }

            $threshold_min = Tools::convertPriceFull((float)$conf['min_price'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), Context::getContext()->currency);
            $threshold_max = Tools::convertPriceFull((float)$conf['max_price'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')), Context::getContext()->currency);

            if ((float)$threshold_max == 0 && (float)$threshold_min == 0 && (float)$price_to_compare == 0) {
                return true;
            } else if ((float)$threshold_max == 0 && (float)$threshold_min == 0 && (float)$price_to_compare != 0) {
                return false;
            } else if ((float)$threshold_max != 0 && (float)$threshold_min == 0 && (float)$price_to_compare > (float)$threshold_max) {
                return false;
            } else if ((float)$threshold_max == 0 && (float)$threshold_min != 0 && (float)$price_to_compare < (float)$threshold_min) {
                return false;
            } else if ((float)$threshold_max != 0 && (float)$threshold_min != 0 && ((float)$price_to_compare < (float)$threshold_min || (float)$price_to_compare > (float)$threshold_max)) {
                return false;
            }
        }
        return true;
    }


    public static function setCombinations($id_product = 0, $id_product_attribute = 0, $combinations = false)
    {
        if ($id_product && $id_product_attribute && !empty($combinations)) {
            $configs = MinpurchaseConfiguration::getConfigurations($id_product, $id_product_attribute);
            if (!empty($configs) && !$configs['grouped_by']) {
                if (!$configs['separated']) {
                    if ($configs['minimum_quantity'] == 0) {
                        $combinations[$id_product_attribute]['minimal_quantity'] = 1;
                    } else {
                        $combinations[$id_product_attribute]['minimal_quantity'] = $configs['minimum_quantity'];
                    }
                } else {
                    $combinations[$id_product_attribute]['minimal_quantity'] = 1;
                }
                $combinations[$id_product_attribute]['maximum_quantity'] = $configs['maximum_quantity'];
                $combinations[$id_product_attribute]['multiple_qty'] = 0;
                $combinations[$id_product_attribute]['increment_qty'] = 0;
                if ($configs['multiple']) {
                    if ($configs['multiple_qty']) {
                        $combinations[$id_product_attribute]['multiple_qty'] = $configs['multiple_qty'];
                    }
                } else if ($configs['increment']) {
                    if ($configs['increment_qty']) {
                        $combinations[$id_product_attribute]['increment_qty'] = $configs['increment_qty'];
                    }
                }
            }
        }
        return $combinations;
    }


    public static function setProduct($product = false)
    {
        if (Tools::isSubmit('id_product_attribute')) {
            $id_product_attribute = Tools::getValue('id_product_attribute');
        } else {
            $id_product_attribute = Product::getDefaultAttribute($product->id);
        }

        if ($product) {
            $configs = MinpurchaseConfiguration::getConfigurations($product->id, $id_product_attribute);
            if (!empty($configs) && !$configs['grouped_by']) {
                $stock = StockAvailable::getQuantityAvailableByProduct($product->id, $id_product_attribute);
                if ($multiple_qty = $configs['multiple_qty']) {
                    if ($stock > 0 && $stock < $multiple_qty) {
                        $multiple_qty = $stock;
                        $product->maximum_quantity = $stock;
                    }
                    $product->multiple_qty = $multiple_qty;
                }
                if ($increment_qty = $configs['increment_qty']) {
                    if ($stock > 0 && $stock < $increment_qty) {
                        $increment_qty = $stock;
                        $product->maximum_quantity = $stock;
                    }
                    $product->increment_qty = $increment_qty;
                }

                if ($configs['max_qty_stock']) {
                    if ($stock > 0) {
                        $product->maximum_quantity = $stock;
                    }
                } else {
                    if ($max_qty = $configs['maximum_quantity']) {
                        if ($stock > 0 && $stock < $max_qty) {
                            $max_qty = $stock;
                        }
                        $product->maximum_quantity = $max_qty;
                    }
                }

                if (!$configs['separated']) {
                    if ($min_qty = $configs['minimum_quantity']) {
                        if ($min_qty == 0) {
                            $min_qty == 1;
                        }
                        if ($stock > 0 && $stock < $min_qty) {
                            $min_qty = $stock;
                            $product->maximum_quantity = $stock;
                        }
                        $product->minimal_quantity = (int)$min_qty;
                    }
                }
            }
        }

        return $product;
    }

    public static function setProductProperties($row = false)
    {
        if ($row) {
            if (isset($row['id_product_attribute'])) {
                $id_product_attribute = $row['id_product_attribute'];
            } else {
                $id_product_attribute = 0;
            }
            if (Tools::getValue('id_product')) {
                $id_product = Tools::getValue('id_product');
            } else {
                $id_product = $row['id_product'];
            }

            $configs = MinpurchaseConfiguration::getConfigurations($row['id_product'], $id_product_attribute);

            if (!empty($configs) && (isset($configs['separated']) && !$configs['separated']) && (isset($configs['grouped_by']) && (int)$configs['grouped_by'] == 0)) {
                $stock = StockAvailable::getQuantityAvailableByProduct($row['id_product'], $id_product_attribute);
                if ($min_qty = $configs['minimum_quantity']) {
                    if ($stock > 0 && $stock < $min_qty) {
                        $min_qty = $stock;
                        $row['maximum_quantity'] = $stock;
                    }
                    $row['minimal_quantity'] = $min_qty;
                }
                if ($increment_qty = $configs['increment_qty']) {
                    if ($stock > 0 && $stock < $increment_qty) {
                        $increment_qty = $stock;
                        $row['maximum_quantity'] = $stock;
                    }
                    $row['increment_qty'] = $increment_qty;
                }

                if ($configs['max_qty_stock']) {
                    if ($stock > 0) {
                        $row['maximum_quantity'] = $stock;
                    }
                } else {
                    if ($max_qty = $configs['maximum_quantity']) {
                        if ($stock > 0 && $stock < $max_qty) {
                            $max_qty = $stock;
                        }
                        $row['maximum_quantity'] = $max_qty;
                    }
                }

                if ($multiple_qty = $configs['multiple_qty']) {
                    if ($stock > 0 && $stock < $multiple_qty) {
                        $multiple_qty = $stock;
                        $row['maximum_quantity'] = $stock;
                    }
                    $row['multiple_qty'] = $multiple_qty;
                }
            }
        }
        return $row;
    }

    public function getProductMinimalQuantity($product, $id_product_attribute = null)
    {
        $minimal_quantity = 1;
        if ($id_product_attribute) {
            $foundCombination = null;
            $combinations = $product->getAttributesGroups(Context::getContext()->language->id);
            foreach ($combinations as $combination) {
                if ((int) ($combination['id_product_attribute']) === $id_product_attribute) {
                    $foundCombination = $combination;
                    break;
                }
            }
            if ($foundCombination['minimal_quantity']) {
                $minimal_quantity = $combination['minimal_quantity'];
            }
        }
        return $minimal_quantity;
    }

    public static function isShowableBySchedule($configuration)
    {
        $schedule = json_decode($configuration['schedule']);
        $dayOfWeek = date('w') - 1;
        if ($dayOfWeek < 0) {
            $dayOfWeek = 6;
        }
        if (is_array($schedule)) {
            if (is_object($schedule[$dayOfWeek]) && $schedule[$dayOfWeek]->isActive === true) {
                if ($schedule[$dayOfWeek]->timeFrom <= date('H:i') && $schedule[$dayOfWeek]->timeTill > date('H:i')) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public static function getOrdersIdByDateAndState($configuration, $id_customer)
    {
        if (Context::getContext()->customer->is_guest) {
            $customerClause = ' `email` = \''.Context::getContext()->customer->email.'\'';
        } else {
            $customerClause = ' o.`id_customer` = '.(int)$id_customer;
        }

        if ($configuration['order_states']) {
            $id_order_states = explode(';', $configuration['order_states']);
        } else {
            $id_order_states = "";
        }

        $date_from = $configuration['orders_date_from'];
        $date_to = $configuration['orders_date_to'];
        $period = $configuration['orders_period'];
        $days = $configuration['days'];

        $sql = 'SELECT `id_order`
                FROM `'._DB_PREFIX_.'orders` o
                LEFT JOIN `'._DB_PREFIX_.'customer` c ON (o.`id_customer` = c.`id_customer`)
                WHERE '.$customerClause.Shop::addSqlRestriction(false, 'o')
                    .(!empty($id_order_states) ? ' AND `current_state` IN ('.implode(',', array_map('intval', $id_order_states)).')' : '');

        if (!empty($period)) {
            $sql .= ($period == 1 ? ' AND MONTH(o.`date_add`) = MONTH(CURRENT_DATE())' : ' AND YEAR(o.`date_add`) = YEAR(CURRENT_DATE()) ');
        }

        if ($days) {
            $sql .= ' AND o.`date_add` > DATE(NOW()) - INTERVAL '.$days.' DAY ';
        }
        
        if ($date_from > 0) {
            $sql .= ' AND o.`date_add` >= \''.pSQL($date_from).'\'';
        }

        if ($date_to > 0) {
            $sql .= ' AND o.`date_add` <= \''.pSQL($date_to).'\'';
        }

        $result = Db::getInstance()->executeS($sql);
        $orders = array();
        foreach ($result as $order) {
            $orders[] = (int)($order['id_order']);
        }

        return $orders;
    }

    protected static function checkExceptions($conf = false, $id_product = false, $id_customer = false)
    {
        if ($conf) {
            if ($conf['customers_excluded']) {
                $customers_excl_array = explode(';', $conf['customers_excluded']);
                if (in_array($id_customer, $customers_excl_array)) {
                    return false;
                }
            }

            if ($conf['products_excluded']) {
                $products_excl_array = explode(';', $conf['products_excluded']);
                if (in_array($id_product, $products_excl_array)) {
                    return false;
                }
            }
        }
        return true;
    }
}