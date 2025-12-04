<?php
/**
 * HsMaCartRule for Multi Accessories
 *
 * @author    PrestaMonster
 * @copyright PrestaMonster
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * CartRule in Multi Accessories Pro comes with special rules:<br/>
 * - Always add product_restriction against the current & main product<br/>
 * - Always add shop_restrictions against shops where Mutli Accessories Pro is enabled<br/>
 * - @todo: add shop_restriction against shops which are selected by an employee when he/she adds a discount<br/>
 */
class HsMaCartRule extends CartRule
{

    /**
     * Default available quantity for each cart rules
     */
    const CART_RULE_AVAILABLE_QTY = 1000000;

    /**
     *
     * @var Product
     */
    public $product;

    /**
     *
     * @var HsAccessoriesGroupProduct
     */
    public $accessory;

    /**
     *
     * @var array
     * <pre>
     * array(
     *  int,
     *  int,
     *  ...
     * )
     */
    public $id_shops;

    /**
     *
     * @param int $discount_type type of reduction. 0 = amount. 1 = %.
     * @param float $discount_value
     * @param array $names
     * @param string $description
     * @return HsMaCartRule
     */
    public function addCartRule($discount_type, $discount_value, array $names, $description = null)
    {
        if (empty($this->product) || !Validate::isLoadedObject($this->product)) {
            return $this;
        }
        if (empty($this->accessory) || !Validate::isLoadedObject($this->accessory)) {
            return $this;
        }

        $this->name = $names;
        $this->description = $description;
        $this->quantity = self::CART_RULE_AVAILABLE_QTY;
        $this->quantity_per_user = self::CART_RULE_AVAILABLE_QTY;

        if ($discount_type) {
            $this->reduction_amount = $discount_value;
            $this->reduction_tax = !Product::getTaxCalculationMethod();
        } else {
            $this->reduction_percent = $discount_value;
        }
        $this->product_restriction = 1;
        $this->shop_restriction = (is_array($this->id_shops) && count($this->id_shops) > 0) ? 1 : 0;
        $date_from = date('Y-m-d H:i:s');
        $this->date_from = $date_from;
        $this->date_to = date('Y-m-d H:i:s', strtotime('+2 years', strtotime($date_from)));
        $this->reduction_product = $this->accessory->id_accessory;
        if (!$this->reduction_currency) {
            $this->reduction_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        }
        if ($this->add()) {
            if ($this->shop_restriction) {
                $this->addShopRestrictions();
            }
            $this->addProductRestrictions();
            $this->addAccessoryRestrictions();
        }
        return $this;
    }

    /**
     * Add product rule restrictions, copied from AdminCartRulesControllers::afterAdd()
     * @return boolean
     */
    protected function addProductRestrictions()
    {
        $result = false;
        Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'cart_rule_product_rule_group` (`id_cart_rule`, `quantity`)
                            VALUES (' . (int) $this->id . ', 1)');
        $id_product_rule_group = Db::getInstance()->Insert_ID();
        if ($id_product_rule_group) {
            Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'cart_rule_product_rule` (`id_product_rule_group`, `type`)
                                                    VALUES (' . (int) $id_product_rule_group . ', "products")');
            $id_product_rule = Db::getInstance()->Insert_ID();
            if ($id_product_rule) {
                $values = array('(' . (int) $id_product_rule . ',' . (int) $this->product->id . ')');
                $result = Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'cart_rule_product_rule_value` (`id_product_rule`, `id_item`) VALUES ' . implode(',', $values));
            }
        }
        return $result;
    }

    /**
     * These restrictions are to know that, the current cart_rule is for combination of a main product & accessories only
     * @return boolean
     */
    protected function addAccessoryRestrictions()
    {
        $accessory_restriction = array(
            'id_cart_rule' => (int) $this->id,
            'id_main_product' => (int) $this->product->id,
            'id_accessory' => (int) $this->accessory->id_accessory
        );
        return Db::getInstance()->insert('accessory_product_cart_rule', $accessory_restriction);
    }

    /**
     *
     * @return boolean
     */
    protected function addShopRestrictions()
    {
        $shop_restrictions = array();
        foreach ($this->id_shops as $id_shop) {
            $shop_restrictions[] = array(
                'id_cart_rule' => (int) $this->id,
                'id_shop' => (int) $id_shop,
            );
        }

        return Db::getInstance()->insert('cart_rule_shop', $shop_restrictions);
    }

    /**
     *
     * @param int $id_accessory
     * @param int $id_main_product
     * @return int
     */
    public static function getIdCartRuleByAccessoryProduct($id_accessory, $id_main_product)
    {
        $sql = 'SELECT `id_cart_rule` FROM `' . _DB_PREFIX_ . 'accessory_product_cart_rule` '
                . 'WHERE `id_main_product`=' . (int) $id_main_product . ' AND `id_accessory`=' . (int) $id_accessory;
        return Db::getInstance()->getValue($sql);
    }

    /**
     *
     * @param array $accessory
     * @param int $id_main_product
     * @return array
     * <pre>
     * array(
     *  'reduction_percent' => float,
     *  'reduction_amount'  => float
     * )</pre>
     */
    public static function getCartRule(array $accessory, $id_main_product)
    {
        $id_cart_rule = self::getIdCartRuleByAccessoryProduct($accessory['id_accessory'], $id_main_product);
        $cart_rule = array();
        if ($id_cart_rule) {
            $sql = 'SELECT `reduction_percent`,`reduction_amount` FROM `' . _DB_PREFIX_ . 'cart_rule` WHERE `id_cart_rule` = ' . (int) $id_cart_rule;
            $cart_rule = Db::getInstance()->getRow($sql);
            // Delete record accessory product cart rule if id_cart_rule not exist in table cart_rule - cart_rule does not support hook after deleting cart rule
            if (empty($cart_rule)) {
                Db::getInstance()->delete('accessory_product_cart_rule', '`id_cart_rule` = ' . (int) $id_cart_rule);
            }
        }
        return $cart_rule;
    }

    /**
     *
     * @return boolean
     */
    public function delete()
    {
        $result = parent::delete();
        $result &= Db::getInstance()->delete('accessory_product_cart_rule', '`id_cart_rule` = ' . (int) $this->id);
        return $result;
    }
}
