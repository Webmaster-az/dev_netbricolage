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

if (!defined('_PS_VERSION_')) {
    exit;
}

class PPBSTranslation extends ObjectModel
{
    /** @var integer Unique ID */
    public $id_translation;

    /** @var integer Shop ID */
    public $id_language;

    /** @var integer Shop ID */
    public $id_shop;

    /** @var string Dimension Name */
    public $name;

    /** @var string Display name */
    public $text;

    /**
     * @see ObjectModel::$definition
     */

    public static $definition = array(
        'table' => 'ppbs_translations',
        'primary' => 'id_translation',
        'fields' => array(
            'id_language' => array('type' => self::TYPE_INT),
            'id_shop' => array('type' => self::TYPE_INT),
            'name' => array('type' => self::TYPE_STRING),
            'text' => array('type' => self::TYPE_HTML)
        )
    );

    private static function formatTranslationResults($translations_collection)
    {
        $translation_collection_new = array();
        $languages = Language::getLanguages();

        foreach ($translations_collection as $translation) {
            $translation_collection_new[$translation->name][$translation->id_language] = $translation->text;
        }

        foreach ($translation_collection_new as $key => $translation) {
            foreach ($languages as $language) {
                if (empty($translation_collection_new[$key][$language['id_lang']])) {
                    $translation_collection_new[$key][$language['id_lang']] = '';
                }
            }
        }
        return $translation_collection_new;
    }

    public static function loadTranslations($id_shop = 1)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('ppbs_translations');
        $sql->where('id_shop = "' . pSQL($id_shop) . '"');

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if ($result) {
            return self::formatTranslationResults(self::hydrateCollection('PPBSTranslation', $result));
        } else {
            return false;
        }
    }

    public static function saveTranslation($name, $translation)
    {
        DB::getInstance()->delete('ppbs_translations', 'name="' . pSQL($name) . '"');
        foreach ($translation as $key => $texts) {
            foreach ($texts as $id_lang => $text) {
                $ppbs_translation = new PPBSTranslation();
                $ppbs_translation->id_language = (int)$id_lang;
                $ppbs_translation->id_shop = Context::getContext()->shop->id;
                $ppbs_translation->name = pSQL($name);
                $ppbs_translation->text = pSQL($text);
                $ppbs_translation->add();
            }
        }
    }
}
