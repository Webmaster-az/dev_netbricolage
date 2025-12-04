<?php
/**
 * mitrocops
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
/*
 *
 * @author    mitrocops
 * @category seo
 * @package gsnipreview
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */

function upgrade_module_1_2_6($module)
{
	$name_module = 'gsnipreview';
    Configuration::updateValue($name_module.'starratingon', 1);

    Configuration::updateValue($name_module.'allinfo_on', 1);
    Configuration::updateValue($name_module.'breadvis_on', 1);

    if (!defined('_PS_HOST_MODE_')) {
        $module->createFolderAndSetPermissions();
    }


    // orderstatuses
    Configuration::updateValue($name_module.'orderstatuses', implode(",",array(2,5,12)));
    // orderstatuses

    Configuration::updateValue($name_module.'allinfo_home', 'allinfo_home');
    Configuration::updateValue($name_module.'allinfo_cat', 'allinfo_cat');
    Configuration::updateValue($name_module.'allinfo_man', 'allinfo_man');

    Configuration::updateValue($name_module.'allinfo_home_w', 100);
    Configuration::updateValue($name_module.'allinfo_cat_w', 100);
    Configuration::updateValue($name_module.'allinfo_man_w', 100);

    Configuration::updateValue($name_module.'allinfo_home_pos', 'top');
    Configuration::updateValue($name_module.'allinfo_cat_pos', 'top');
    Configuration::updateValue($name_module.'allinfo_man_pos', 'top');

    Configuration::updateValue($name_module.'ptabs_type', 1);

    Configuration::updateValue($name_module.'rsoc_on', 1);
    Configuration::updateValue($name_module.'rsoccount_on', 1);

    Configuration::updateValue($name_module.'rswitch_lng', 0);

    Configuration::updateValue($name_module.'revperpagecus', 5);

    Configuration::updateValue($name_module.'is_blocklr', 1);

    Configuration::updateValue($name_module.'blocklr_home_pos', 'home');
    Configuration::updateValue($name_module.'blocklr_cat_pos', 'leftcol');
    Configuration::updateValue($name_module.'blocklr_man_pos', 'leftcol');
    Configuration::updateValue($name_module.'blocklr_prod_pos', 'leftcol');
    Configuration::updateValue($name_module.'blocklr_oth_pos', 'leftcol');
    Configuration::updateValue($name_module.'blocklr_chook_pos', 'chook');

    Configuration::updateValue($name_module.'blocklr_home_w', 100);
    Configuration::updateValue($name_module.'blocklr_cat_w', 100);
    Configuration::updateValue($name_module.'blocklr_man_w', 100);
    Configuration::updateValue($name_module.'blocklr_prod_w', 100);
    Configuration::updateValue($name_module.'blocklr_oth_w', 100);
    Configuration::updateValue($name_module.'blocklr_chook_w', 100);


    Configuration::updateValue($name_module.'blocklr_home', 'blocklr_home');
    Configuration::updateValue($name_module.'blocklr_cat', 'blocklr_cat');
    Configuration::updateValue($name_module.'blocklr_man', 'blocklr_man');
    Configuration::updateValue($name_module.'blocklr_prod', 'blocklr_prod');
    Configuration::updateValue($name_module.'blocklr_oth', 'blocklr_oth');
    Configuration::updateValue($name_module.'blocklr_chook', 'blocklr_chook');

    Configuration::updateValue($name_module.'blocklr_home_ndr', 3);
    Configuration::updateValue($name_module.'blocklr_cat_ndr', 3);
    Configuration::updateValue($name_module.'blocklr_man_ndr', 3);
    Configuration::updateValue($name_module.'blocklr_prod_ndr', 3);
    Configuration::updateValue($name_module.'blocklr_oth_ndr', 3);
    Configuration::updateValue($name_module.'blocklr_chook_ndr', 3);

    Configuration::updateValue($name_module.'blocklr_home_tr', 250);
    Configuration::updateValue($name_module.'blocklr_cat_tr', 75);
    Configuration::updateValue($name_module.'blocklr_man_tr', 75);
    Configuration::updateValue($name_module.'blocklr_prod_tr', 75);
    Configuration::updateValue($name_module.'blocklr_oth_tr', 75);
    Configuration::updateValue($name_module.'blocklr_chook_tr', 75);

    $img_default = "small"."_"."default";
    Configuration::updateValue($name_module.'blocklr_home_im', $img_default);
    Configuration::updateValue($name_module.'blocklr_cat_im', $img_default);
    Configuration::updateValue($name_module.'blocklr_man_im', $img_default);
    Configuration::updateValue($name_module.'blocklr_prod_im', $img_default);
    Configuration::updateValue($name_module.'blocklr_oth_im', $img_default);
    Configuration::updateValue($name_module.'blocklr_chook_im', $img_default);

    Configuration::updateValue($name_module.'img_size_em', $img_default);
    $languages = Language::getLanguages(false);
    foreach ($languages as $language){
        $i = $language['id_lang'];
        Configuration::updateValue($name_module.'subpubem_'.$i, 'Your review has been published');
        Configuration::updateValue($name_module.'subresem_'.$i, 'The shop admin has replied to your product review');
        Configuration::updateValue($name_module.'textresem_'.$i, 'Thank you for your product review on our website. We always welcome reviews, whether it is positive or negative. However, we would like to have a chance to invite you to change your review. Here is why:');


    }

    Configuration::updateValue($name_module.'is_show_min', 1);


    $module->installCriteriaTable();

    $module->installReviewCriteria();

    $module->installReviewAbuse();

    $module->installReviewHelpfull();

    Configuration::updateValue($name_module.'is_abusef', 1);
    Configuration::updateValue($name_module.'is_helpfulf', 1);


    // voucher facebook settings

    $module->installVoucherShareReviewSettings();
    $module->installSocialShare();

    // voucher facebook settings

    $module->registerHook('productFooter');

    $module->registerHook('lastReviewsMitrocops');

    $module->registerHook('top');

    if(version_compare(_PS_VERSION_, '1.6', '>')) {
       $module->registerHook('displayProductListReviews');
    }


    $module->installReminder2CustomerTable();

    ### add field email in ps_gsnipreview table ####

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('email', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` ADD `email` VARCHAR(255) NOT NULL;')) {
                return false;
            }

        }
    }

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
            if (!in_array('id_lang', $list_fields)) {
                if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` ADD `id_lang` int(11) NOT NULL default \'0\';')) {
                    return false;
            }

        }

        Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('UPDATE `' . _DB_PREFIX_ . 'gsnipreview` SET `id_lang` =  '.(int)$module->getIdLang());
    }

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('is_abuse', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` ADD `is_abuse` int(11) NOT NULL default \'0\';')) {
                return false;
            }

        }
    }

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('is_changed', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` ADD `is_changed` int(11) NOT NULL default \'0\';')) {
                return false;
            }

        }
    }

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('title_review_old', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` ADD `title_review_old` varchar(5000) default NULL;')) {
                return false;
            }

        }
    }

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('text_review_old', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` ADD `text_review_old` text;')) {
                return false;
            }

        }
    }

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('rating_old', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` ADD `rating_old` text;')) {
                return false;
            }

        }
    }

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('admin_response', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` ADD `admin_response` text;')) {
                return false;
            }

        }
    }


    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('is_display_old', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` ADD `is_display_old` int(11) NOT NULL default \'0\';')) {
                return false;
            }

        }
    }

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('is_count_sending_suggestion', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` ADD `is_count_sending_suggestion` int(11) NOT NULL default \'0\';')) {
                return false;
            }

        }
    }

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        // change filed with same name , not !in_array(), use in_array
        if (in_array('time_add', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` CHANGE `time_add` `time_add` timestamp NULL;')) {
                return false;
            }

        }
    }

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('review_date_update', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` ADD `review_date_update` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;')) {
                return false;
            }

        }
    }

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('is_import', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` ADD `is_import` int(11) NOT NULL default \'0\';')) {
                return false;
            }

        }
    }

    ### add field email in ps_gsnipreview table ####




    return true;
}
?>