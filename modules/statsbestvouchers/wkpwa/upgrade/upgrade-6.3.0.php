<?php
/**
* 2010-2021 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through LICENSE.txt file inside our module
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright 2010-2021 Webkul IN
* @license LICENSE.txt
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_6_3_0($object)
{
    $wkSqlQry = array(
        "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."wk_pwa_push_notification_lang` (
            `id` int(11) NOT NULL,
            `title` text NOT NULL,
            `body` text NOT NULL,
            `id_lang` int(11) NOT NULL,
            PRIMARY KEY (`id`, `id_lang`)
        ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8 AUTO_INCREMENT=1",
    );
    $wkSqlQryDrop = array(
        "ALTER TABLE `"._DB_PREFIX_."wk_pwa_push_notification`
        DROP COLUMN `title`",
        "ALTER TABLE `"._DB_PREFIX_."wk_pwa_push_notification`
        DROP COLUMN `body`",
    );
    $wkDbInstance = Db::getInstance();
    $wkSuccess = true;
    foreach ($wkSqlQry as $wkQuery) {
        $wkSuccess &= $wkDbInstance->execute(trim($wkQuery));
    }
    if ($wkSuccess) {
        $noficationInfo = $wkDbInstance->executeS(
            "SELECT `id`,`title`,`body`
            FROM `"._DB_PREFIX_."wk_pwa_push_notification`"
        );
        if (!empty($noficationInfo)) {
            foreach ($noficationInfo as $info) {
                foreach (Language::getLanguages() as $lang) {
                    $data = array(
                        'id' => (int) $info['id'],
                        'title' => pSQL($info['title']),
                        'body' => pSQL($info['body']),
                        'id_lang' => (int) $lang['id_lang']
                    );
                    $wkDbInstance->insert('wk_pwa_push_notification_lang', $data);
                }
            }
        }

        foreach ($wkSqlQryDrop as $wkQuery) {
            $wkSuccess &= $wkDbInstance->execute(trim($wkQuery));
        }
    };

    if ($wkSuccess) {
        return ($object->registerHook('displayNavFullWidth')
        && $object->registerHook('displayNav1')
        && $object->registerHook('displayMyAccountBlock')
        && $object->registerHook('displayBanner')
        && Configuration::updateValue('WK_PWA_PUSH_NOTIFICATION_PROMPT_LIFETIME', 1)
        && Configuration::updateValue('WK_PWA_PUSH_NOTIFICATION_PROMPT_MOBILE_ENABLE', 0)
        && Configuration::updateValue('WK_PWA_PUSH_NOTIFICATION_PROMPT_DESKTOP_ENABLE', 0)
        && Configuration::updateValue('WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP', 0)
        && Configuration::updateValue('WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK', '["1","2"]'));
    }

    return true;
}
