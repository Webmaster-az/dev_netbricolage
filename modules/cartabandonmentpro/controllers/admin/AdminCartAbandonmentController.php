<?php

include_once(_PS_MODULE_DIR_.'cartabandonmentpro/classes/Template.class.php');
include_once(_PS_MODULE_DIR_.'cartabandonmentpro/classes/Model.class.php');
include_once(_PS_MODULE_DIR_.'cartabandonmentpro/controllers/TemplateController.class.php');
require_once(_PS_MODULE_DIR_.'cartabandonmentpro/controllers/ReminderController.class.php');
require_once(_PS_MODULE_DIR_.'cartabandonmentpro/controllers/DiscountsController.class.php');

class AdminCartAbandonmentController extends ModuleAdminController
{
    public function ajaxProcessPreviewTemplate()
    {
        $id_shop = Context::getContext()->shop->id;
        $token_bdd = Configuration::get('CARTABAND_TOKEN', null, null, $id_shop);
        $template_id = Tools::getValue('template_id');
        $wich_remind = Tools::getValue('wich_remind');
        $iso = Language::getIsoById(Tools::getValue('language'));
        $id_lang = Tools::getValue('language');

        $templates = TemplateController::getActiveTemplate($id_shop);
        $content = Tools::file_get_contents(_PS_MODULE_DIR_.'cartabandonmentpro/mails/' . $iso . '/' . $templates[$id_shop][$id_lang][$wich_remind]['id'] . '.html');

        // Replace %DISCOUNT_TXT%
        // id_template = reminder and not the real id_template
        $discount = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'cartabandonmentpro_cartrule WHERE id_template = '.(int)$wich_remind);
        if ($discount) {
            $fake_voucher = new CartRule();
            if ($discount['type'] == 'percent') {
                $fake_voucher->reduction_percent = $discount['value'];
            } elseif ($discount['type'] == 'currency') {
                $fake_voucher->reduction_amount = $discount['value'];
            }

            $fake_voucher->date_to = 'XX-XX-XX XX:XX:XX';
            $fake_voucher->code = 'XXXXXXX';

            $content = CartAbandonmentProTemplate::editDiscount($fake_voucher, $content, $id_lang, $id_shop);
        }

        die(CartAbandonmentProTemplate::editTemplate($content, $wich_remind));
    }

    public function ajaxProcessReminders()
    {
        $wichReminder = Tools::getValue('wichReminder');
        $action = Tools::getValue('subaction');
        $id_shop = Context::getContext()->shop->id;

        switch ($action) {
            case 'setDays':
                $value = Tools::getValue('val');
                die(ReminderController::setDays($wichReminder, $value, $id_shop));
            case 'setHours':
                $value = Tools::getValue('val');
                die(ReminderController::setHours($wichReminder, $value, $id_shop));
            case 'setActive':
                $value = Tools::getValue('val');
                die(ReminderController::setActive($wichReminder, $value, $id_shop));
            case 'setMaxReminder':
                $value = Tools::getValue('val');
                die(ReminderController::setMaxReminder($value, $id_shop));
            case 'setNewsletter':
                $value = Tools::getValue('val');
                die(ReminderController::setNewsletter($value, $id_shop));
        }
    }

    public function ajaxProcessMailTest()
    {
        $id_shop = Context::getContext()->shop->id;
        $id_lang = Tools::getValue('id_lang');
        $iso = Language::getIsoById($id_lang);
        $mail = Tools::getValue('mail');
        $total_cart = (float)Tools::getValue('amount', 0);
        $templates = TemplateController::getActiveTemplate($id_shop);
        $x = 0;

        if (!isset(Context::getContext()->link)) {
            Context::getContext()->link = new Link();
        }

        if (!Validate::isEmail($mail)) {
            die('Invalid email');
        }

        foreach ($templates[$id_shop][$id_lang] as $which_remind => $template) {
            $content = Tools::file_get_contents(_PS_MODULE_DIR_.'cartabandonmentpro/mails/'.$iso.'/'.$template['id'].'.html');
            $content = CartAbandonmentProTemplate::editTemplate($content, $template['id'], null, $id_lang, $id_shop);

            if (!$content) {
                continue;
            }

            // Replace %DISCOUNT_TXT%
            // id_template = reminder and not the real id_template
            $discount = Db::getInstance()->getRow('
                SELECT * FROM '._DB_PREFIX_.'cartabandonmentpro_cartrule
                WHERE id_template = '.(int)$which_remind.'
                AND min_amount <= '.(int)$total_cart.'
                ORDER BY min_amount DESC');

            if ($discount) {
                $fake_voucher = new CartRule();
                if ($discount['type'] == 'percent') {
                    $fake_voucher->reduction_percent = $discount['value'];
                } elseif ($discount['type'] == 'currency') {
                    $fake_voucher->reduction_amount = $discount['value'];
                }

                $fake_voucher->date_to = 'XX-XX-XX XX:XX:XX';
                $fake_voucher->code = 'XXXXXXX';

                $content = CartAbandonmentProTemplate::editDiscount(
                    $fake_voucher,
                    $content,
                    Context::getContext()->cookie->id_lang
                );
            } else {
                $content = str_replace('%DISCOUNT_TXT%', '', $content);
            }

            $fp = fopen(_PS_MODULE_DIR_.'cartabandonmentpro/mails/' . $iso . '/send.html', 'w+');
            fwrite($fp, $content);
            fclose($fp);
            $fp = fopen(_PS_MODULE_DIR_.'cartabandonmentpro/mails/' . $iso . '/send.txt', 'w+');
            $content = preg_replace("/(\s){2,}/", "\r\n\r\n", trim(strip_tags($content)));
            fwrite($fp, $content);
            fclose($fp);

            $title = CartAbandonmentProTemplate::editTitleBeforeSending($template['name'], null, $id_lang);

            $sent = Mail::Send($id_lang, 'send', $title, array(), trim($mail), null, null, null, null, null, _PS_MODULE_DIR_.'cartabandonmentpro/mails/');

            if ($sent) {
                $x++;
            }
        }
        die($x . ' mails have been sent.');
    }
}
