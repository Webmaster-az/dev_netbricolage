<?php
/**
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

class ReminderController
{
    public function edit()
    {
        if (!Tools::getValue('tpl')) {
            return false;
        }

        $this->generateTemplate();
        header('Location: ' . Tools::getValue('uri') . '&justEdited=1&cartabandonment_conf=1');
        die;
    }

    private function getReminds()
    {
        return Db::getInstance()->ExecuteS("
            SELECT wich_remind
            FROM "._DB_PREFIX_."cartabandonment_remind_config
            WHERE active = 1
            ORDER BY wich_remind;");
    }

    private function getEditedTemplate($id_tpl)
    {
        if (!isset($id_tpl) || is_null($id_tpl) || $id_tpl == 0) {
            $id_tpl = null;
        }
        return $id_tpl;
    }

    private function sameTemplate()
    {
        $id_tpl = (int)Tools::getValue('edittpl1');

        $id_template = $this->saveTemplate($this->getEditedTemplate($id_tpl), 1, Tools::getValue('name_1'));
        // if(!$id_template)
            // d('Erreur lors de l\'enregistrement du template');
        $this->save(1, $id_template, Tools::getValue('tpl_same'));
        $this->save(2, $id_template, Tools::getValue('tpl_same'));
        $this->save(3, $id_template, Tools::getValue('tpl_same'));
    }

    private function generateTemplate()
    {
        for ($x = 1; $x <= 3; $x++) {
            $id_tpl = (int)Tools::getValue('edittpl' . $x);

            $id_template = $this->saveTemplate($this->getEditedTemplate($id_tpl), $x, Tools::getValue('name_' . $x));
            // if(!$id_template)
                // d('Erreur lors de l\'enregistrement du template');
            $this->save($x, $id_template, Tools::getValue('tpl_same'));
        }
    }

    private function save($remind, $id_template, $tpl_same)
    {
        if (!is_writable('../modules/cartabandonmentpro/mails/')
            || !is_writable('../modules/cartabandonmentpro/tpls/')) {
            return false;
        }

        $query = "REPLACE INTO " . _DB_PREFIX_ . "cartabandonment_remind_lang VALUE(".(int) $remind.", ".(int) Tools::getValue('language').", ".(int)$id_template.", ".(int)$tpl_same.", ".(int)Tools::getValue('id_shop').")";
        if (!Db::getInstance()->Execute($query)) {
            d('Erreur lors de l\'enregistrement du template');
        }
    }

    private function saveTemplate($id_tpl, $wich_template, $name)
    {
        $model_id = Tools::getValue('model' . $wich_template);
        $template = new CartAbandonmentProTemplate($id_tpl, new CartAbandonmentProModel($model_id), $wich_template);
        $template->setName($name);
        return $template->save();
    }

    public static function setDays($wichRemind, $val)
    {
        $query = "
            UPDATE " . _DB_PREFIX_ . "cartabandonment_remind_config
            SET days = " . (int) $val . "
            WHERE wich_remind = " . (int) $wichRemind;

        return DB::getInstance()->Execute($query);
    }
    public static function setHours($wichRemind, $val)
    {
        $query = "
            UPDATE " . _DB_PREFIX_ . "cartabandonment_remind_config
            SET hours = " . (int) $val . "
            WHERE wich_remind = " . (int) $wichRemind;
        return DB::getInstance()->Execute($query);
    }
    public static function setActive($wichRemind, $val)
    {
        $query = "
            UPDATE " . _DB_PREFIX_ . "cartabandonment_remind_config
            SET active = " . (int) $val . "
            WHERE wich_remind = " . (int) $wichRemind;
        return DB::getInstance()->Execute($query);
    }
    public static function setMaxReminder($val)
    {
        Configuration::updateValue('CART_MAXREMINDER', $val);
        return true;
    }

    public static function getAbandonedCart($wichReminder, $id_shop = 1)
    {
        $cab_news = Configuration::get('CAB_NEWS', null, null, $id_shop);

        $query    =  'SELECT `ca`.*, `cu`.`firstname`, `cu`.`lastname`, `cu`.`id_customer`, `cu`.`email`, MAX(ord.id_order) as id_order, MAX(cr.id_cart) as already_sent, MAX(cr2.id_cart) as previous_batch
                    FROM `'._DB_PREFIX_.'cart` `ca`
                    LEFT JOIN '._DB_PREFIX_.'cartabandonment_remind cr ON (cr.wich_remind = '.(int)$wichReminder.' AND ca.id_cart = cr.id_cart)
                    LEFT JOIN '._DB_PREFIX_.'cartabandonment_remind cr2 ON (cr2.wich_remind = '.((int)$wichReminder-1).' AND ca.id_cart = cr2.id_cart)
                    LEFT JOIN `'._DB_PREFIX_.'orders` `ord` ON `ord`.`id_cart` = `ca`.`id_cart`
                    INNER JOIN `'._DB_PREFIX_.'customer` `cu` ON `cu`.`id_customer` = `ca`.`id_customer`
                    JOIN '._DB_PREFIX_.'cart_product cp ON ca.id_cart = cp.id_cart
                    JOIN '._DB_PREFIX_.'customer c ON ca.id_customer = c.id_customer
                    INNER JOIN ' . _DB_PREFIX_ . 'stock_available sa ON sa.id_product = cp.id_product
                    INNER JOIN ' . _DB_PREFIX_ . 'product p ON p.id_product = cp.id_product
                    WHERE 1 = 1';

        if (Configuration::get('PS_STOCK_MANAGEMENT') && !Configuration::get('PS_ORDER_OUT_OF_STOCK')) {
            $query .= ' AND ((sa.out_of_stock != 1 AND sa.quantity > 0) OR sa.out_of_stock = 1)';
        }

        $maxReminder = ReminderController::getMaxReminder($id_shop);
        $query .= ' AND ca.date_upd >= "' . pSql($maxReminder) . '" ';

        if ($cab_news == 0) {
            $query .= ' AND c.newsletter = 1';
        }

        $query .= ' GROUP BY cu.email';

        $reminder = ReminderController::getReminder($wichReminder);
        $query .= ' HAVING id_order IS NULL AND already_sent IS NULL AND ca.date_upd <= "' . pSql($reminder) . '"';

        if ($wichReminder > 1) {
            $query .= ' AND previous_batch IS NOT NULL';
        }

        return DB::getInstance()->ExecuteS($query);
    }

    public static function getLastCartsReminded($nb = 25)
    {
        return Db::getInstance()->ExecuteS('
            SELECT *, IF(cun.id_customer, 1, 0) as unsubscribed
            FROM '._DB_PREFIX_.'cartabandonment_remind cr
            LEFT JOIN '._DB_PREFIX_.'cart c ON c.id_cart = cr.id_cart
            LEFT JOIN '._DB_PREFIX_.'customer cu ON cu.id_customer = c.id_customer
            LEFT JOIN '._DB_PREFIX_.'cartabandonment_unsubscribe cun ON cun.id_customer = c.id_customer
            ORDER BY cr.send_date DESC
            LIMIT 0, '.(int)$nb);
    }

    public static function getRemindersByLanguage($id_lang = 1, $id_shop = 1)
    {
        $query = "SELECT id_template, tpl_same FROM " . _DB_PREFIX_ . "cartabandonment_remind_lang WHERE id_lang = " . (int)$id_lang . " AND id_shop = " . (int)$id_shop;
        return DB::getInstance()->ExecuteS($query);
    }

    public static function getReminders($wichRemind)
    {
        return DB::getInstance()->ExecuteS("
        SELECT *
        FROM " . _DB_PREFIX_ . "cartabandonment_remind_config
        WHERE wich_remind = " . (int)$wichRemind);
    }

    private static function getReminder($wichRemind)
    {
        $remind = ReminderController::getReminders($wichRemind);
        $startDate = time();

        $date = date('Y-m-d H:i:s', strtotime('-'. $remind[0]['days'] . ' day', $startDate));
        $date = date('Y-m-d H:i:s', strtotime('-'. $remind[0]['hours'] . ' hours', strtotime($date)));

        return $date;
    }

    private static function getMaxReminder($id_shop = 1)
    {
        $time = Configuration::get('CART_MAXREMINDER', null, null, $id_shop);
        return date('Y-m-d H:i:s', strtotime('-'. $time . ' day', time()));
    }

    public static function setNewsletter($val, $id_shop)
    {
        Configuration::updateValue('CAB_NEWS', $val, false, null, $id_shop);
        return true;
    }
}
