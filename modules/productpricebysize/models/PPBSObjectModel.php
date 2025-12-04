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

class PPBSObjectModel extends ObjectModel
{
    /**
     * @param $class
     * @param $table_lang
     * @param $primary_key
     * @param $result
     * @param $id_lang
     * @param array $lang_columns
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */

    protected function hydrateLang($table_lang, $primary_key, $result, $id_lang, $lang_columns = array())
    {
        $this->hydrate($result);

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from($table_lang);
        $sql->where($primary_key.' = '.(int)$this->{$primary_key});
        if ($id_lang > 0) {
            $sql->where('id_lang = ' . (int)$id_lang);
        }
        $result2 = Db::getInstance()->executeS($sql);

        foreach ($result2 as $result) {
            foreach ($lang_columns as $lang_column) {
                if ($id_lang > 0) {
                    $this->{$lang_column}[$id_lang] = $result[$lang_column];
                } else {
                    $this->{$lang_column}[$result['id_lang']] = $result[$lang_column];
                }
            }
        }
    }

    /**
     * @param $class
     * @param $table_lang
     * @param $primary_key
     * @param $result
     * @param $id_lang
     * @param array $lang_columns
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    protected function hydrateCollectionLang($class, $table_lang, $primary_key, $result, $id_lang, $lang_columns = array())
    {
        $collection = self::hydrateCollection($class, $result);

        foreach ($collection as &$item) {
            $sql = new DbQuery();
            $sql->select('*');
            $sql->from($table_lang);
            $sql->where($primary_key.' = '.(int)$item->{$primary_key});
            if ($id_lang > 0) {
                $sql->where('id_lang = ' . (int)$id_lang);
            }
            $result2 = Db::getInstance()->executeS($sql);

            foreach ($result2 as $result) {
                foreach ($lang_columns as $lang_column) {
                    if ($id_lang > 0) {
                        $item->{$lang_column}[$id_lang] = $result[$lang_column];
                    } else {
                        $item->{$lang_column}[$result['id_lang']] = $result[$lang_column];
                    }
                }
            }
        }
        return $collection;
    }
}
