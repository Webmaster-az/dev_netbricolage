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

function upgrade_module_1_3_1($module)
{
	$name_module = 'gsnipreview';
    $prefix = 'r';

    Configuration::updateValue($name_module.'crondelay'.$prefix, 10);
    Configuration::updateValue($name_module.'cronnpost'.$prefix, 20);

    Configuration::updateValue($name_module.'remrevsec'.$prefix, 0);
    Configuration::updateValue($name_module.'delaysec'.$prefix, 7);
    Configuration::updateValue($name_module.'remindersec'.$prefix, 0);


    Configuration::updateValue($name_module.'is_avatar'.$prefix, 1);
    Configuration::updateValue($name_module.'is_files'.$prefix, 1);
    Configuration::updateValue($name_module.'ruploadfiles', 7);
    Configuration::updateValue($name_module.'rminc', 20);
    Configuration::updateValue($name_module.'is_onerev', 1);


    $languages = Language::getLanguages(false);
    foreach ($languages as $language){
        $i = $language['id_lang'];
        Configuration::updateValue($name_module.'reminderok'.$prefix.'_'.$i, 'The emails requests on the reviews was successfully sent');
        Configuration::updateValue($name_module.'thankyou'.$prefix.'_'.$i, 'Thank you for your review');
        Configuration::updateValue($name_module.'newrev'.$prefix.'_'.$i, 'New review');
        Configuration::updateValue($name_module.'modrev'.$prefix.'_'.$i, 'One of your customers has modified own product review');
        Configuration::updateValue($name_module.'abuserev'.$prefix.'_'.$i, 'Someone send abuse for review');

        Configuration::updateValue($name_module.'facvouc'.$prefix.'_'.$i, 'You share review on Facebook and get voucher for discount');
        Configuration::updateValue($name_module.'revvouc'.$prefix.'_'.$i, 'You submit a review and get voucher for discount');

        Configuration::updateValue($name_module.'sugvouc'.$prefix.'_'.$i, 'Share your review on Facebook and get voucher for discount');

    }

    if (!defined('_PS_HOST_MODE_')) {
        $module->createFolderAndSetPermissionsAvatar();
        $module->createFolderAndSetPermissionsFiles();
    }


    $module->installUserTable();
    $module->installFiles2ReviewTable();


    Configuration::updateValue($name_module.'is_uprof', 1);

    Configuration::updateValue($name_module.'radv_home', 1);
    Configuration::updateValue($name_module.'radv_footer', 1);
    Configuration::updateValue($name_module.'radv_left', 1);

    Configuration::updateValue($name_module.'rshoppers_blc', 5);
    Configuration::updateValue($name_module.'rpage_shoppers', 16);




    ### add field email in ps_gsnipreview table ####

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('avatar', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview` ADD `avatar` TEXT;')) {
                return false;
            }

        }
    }

    ### add field email in ps_gsnipreview table ####


    ### add field email in gsnipreview_data_order table ####

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview_data_order`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('date_send', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview_data_order` ADD `date_send` timestamp NULL;')) {
                return false;
            }

        }
    }


    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview_data_order`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('date_send_second', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview_data_order` ADD `date_send_second` timestamp NULL;')) {
                return false;
            }

        }
    }

    $list_fields = Db::getInstance()->executeS('SHOW FIELDS FROM `'._DB_PREFIX_.'gsnipreview_data_order`');
    if (is_array($list_fields))
    {
        foreach ($list_fields as $k => $field)
            $list_fields[$k] = $field['Field'];
        if (!in_array('count_sent', $list_fields)) {
            if (!Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'gsnipreview_data_order` ADD `count_sent` int(10) NOT NULL default \'0\';')) {
                return false;
            }

        }
    }


    ### add field email in gsnipreview_data_order table ####


    $tab_id = Tab::getIdFromClassName("AdminReview");
    if ($tab_id) {
        $tab = new Tab($tab_id);
        $tab->delete();
    }

    $tab_id = Tab::getIdFromClassName("AdminReviews");
    if ($tab_id) {
        $tab = new Tab($tab_id);
        $tab->delete();
    }

    @unlink(_PS_ROOT_DIR_."/img/t/AdminReview.gif");




    $module->createAdminTabs15();







    ### store reviews ###

    $_prefix_shop_reviews = 'ti';


    Configuration::updateValue($name_module.'is_storerev', 1);

    Configuration::updateValue($name_module.'crondelay'.$_prefix_shop_reviews, 10);
    Configuration::updateValue($name_module.'cronnpost'.$_prefix_shop_reviews, 20);

    Configuration::updateValue($name_module.'t_lefts', 1);
    Configuration::updateValue($name_module.'t_rights', 1);
    Configuration::updateValue($name_module.'t_footers', 1);
    Configuration::updateValue($name_module.'t_homes', 1);
    Configuration::updateValue($name_module.'t_leftsides', 1);
    Configuration::updateValue($name_module.'t_rightsides', 1);
    Configuration::updateValue($name_module.'t_tpages', 1);


    ### reminder ###
    Configuration::updateValue($name_module.'delaysec'.$_prefix_shop_reviews, 7);
    Configuration::updateValue($name_module.'remindersec'.$_prefix_shop_reviews, 0);


    Configuration::updateValue($name_module.'reminder'.$_prefix_shop_reviews, 1);
    $languages = Language::getLanguages(false);
    foreach ($languages as $language){
        $i = $language['id_lang'];
        Configuration::updateValue($name_module.'emrem'.$_prefix_shop_reviews.'_'.$i, 'Are you satisfied with our products');
        Configuration::updateValue($name_module.'reminderok'.$_prefix_shop_reviews.'_'.$i, 'The emails requests on the reviews was successfully sent');
        Configuration::updateValue($name_module.'thankyou'.$_prefix_shop_reviews.'_'.$i,'Thank you for your review');
        Configuration::updateValue($name_module.'newtest'.$_prefix_shop_reviews.'_'.$i, 'New Store review from Your Customer');
        Configuration::updateValue($name_module.'resptest'.$_prefix_shop_reviews.'_'.$i, 'Response on the Store review');

    }
    Configuration::updateValue($name_module.'orderstatuses'.$_prefix_shop_reviews, implode(",",array(2,5,12)));
    Configuration::updateValue($name_module.'starscat'.$_prefix_shop_reviews, 1);
    Configuration::updateValue($name_module.'delay'.$_prefix_shop_reviews, 7);


    ### reminder ###

    Configuration::updateValue($name_module.'whocanadd'.$_prefix_shop_reviews, 'all');


    Configuration::updateValue($name_module.'tlast', 3);

    Configuration::updateValue($name_module.'t_home', 1);
    Configuration::updateValue($name_module.'t_footer', 1);
    Configuration::updateValue($name_module.'BGCOLOR_T', '#fafafa');
    Configuration::updateValue($name_module.'BGCOLOR_TIT', '#c45500');
    Configuration::updateValue($name_module.'t_left', 1);

    Configuration::updateValue($name_module.'t_rightside', 1);

    Configuration::updateValue($name_module.'perpage'.$_prefix_shop_reviews, 5);


    Configuration::updateValue($name_module.'is_avatar', 1);
    Configuration::updateValue($name_module.'is_captcha'.$_prefix_shop_reviews, 1);
    Configuration::updateValue($name_module.'is_web', 1);
    Configuration::updateValue($name_module.'is_company', 1);
    Configuration::updateValue($name_module.'is_addr', 1);

    Configuration::updateValue($name_module.'is_country', 1);
    Configuration::updateValue($name_module.'is_city', 1);



    Configuration::updateValue($name_module.'noti'.$_prefix_shop_reviews, 1);
    Configuration::updateValue($name_module.'mail'.$_prefix_shop_reviews, @Configuration::get('PS_SHOP_EMAIL'));

    Configuration::updateValue($name_module.'n_rssitemst', 10);
    Configuration::updateValue($name_module.'rssontestim', 1);


    $module->createAdminTabsStoreReviews();


    $module->createShopReviewTable();
    $module->createReminderShopReviewsTable();

    ### store reviews ###



    if(version_compare(_PS_VERSION_, '1.6', '<')) {
        $module->generateRewriteRules();
        $module->generateRewriteRulesUser();
    }





    return true;
}
?>