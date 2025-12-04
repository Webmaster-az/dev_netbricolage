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

class PPBSAreaPriceSuffixModel extends PPBSObjectModel
{
    /** @var integer Translation ID */
    public $id_ppbs_areapricesuffix;

    /** @var string name */
    public $name;

    /** @var string Translation Text */
    public $text;

    /**
     * @see ObjectModel::$definition
     */

    public static $definition = array(
        'table' => 'ppbs_areapricesuffix',
        'primary' => 'id_ppbs_areapricesuffix',
        'multilang' => true,
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING),
            'text' => array('type' => self::TYPE_STRING, 'lang' => true)
        )
    );

    /**
     * Get All Area price Suffix
     * @param $id_lang
     * @return array
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function getCollection($id_lang)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(self::$definition['table']);

        $result = Db::getInstance()->executeS($sql);
        $collection = $this->hydrateCollectionLang(get_class(), self::$definition['table'] . '_lang', self::$definition['primary'], $result, $id_lang, array('text'));
        return $collection;
    }

    public function getByID($id_ppbs_areapricesuffix)
    {
    }

    public static function deleteMultiLangEntry($id_pk)
    {
        DB::getInstance()->delete(self::$definition['table'] . '_lang', 'id_ppbs_areapricesuffix=' . (int)$id_pk);
        DB::getInstance()->delete(self::$definition['table'], 'id_ppbs_areapricesuffix=' . (int)$id_pk);
    }
}
