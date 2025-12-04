<?php
/**
* 2012-2018 Areama
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@areama.net so we can send you a copy immediately.
*
*  @author    Areama <contact@areama.net>
*  @copyright 2018 Areama
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Areama
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class ArlsfVisitor extends ObjectModel
{
    const TABLE_NAME = 'arlsf_visitor';
    
    public $id;
    public $id_product;
    public $key;
    public $timestamp;


    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => self::TABLE_NAME,
        'primary' => 'id_visitor',
        'multilang' => false,
        'fields' => array(
            'key' =>            array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'id_product' =>               array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'timestamp' =>               array('type' => self::TYPE_INT, 'validate' => 'isInt')
        ),
    );
    
    public static function getCount($key = null, $id_product = null, $exclude = array())
    {
        $sql = new DbQuery();
        $sql->from(self::TABLE_NAME, 't');
        $where = array();
        $sql->select('COUNT(1) c');
        if ($key) {
            $where[] = '`key` = "' . pSQL($key) . '"';
        }
        if ($exclude) {
            foreach ($exclude as $k => $v) {
                $exclude[$k] = "'" . pSQL($v) . "'";
            }
            $where[] = '`key` NOT IN (' . implode(', ', $exclude) . ')';
        }
        if ($id_product) {
            $where[] = 'id_product = ' . (int)$id_product;
        }
        $sql->where(implode(' AND ', $where));
        $res = Db::getInstance()->getRow($sql);
        return $res['c'];
    }
    
    public static function deleteByKey($key)
    {
        $sql = 'DELETE FROM `' . _DB_PREFIX_ . self::TABLE_NAME . '` WHERE `key` = "' . pSQL($key) . '"';
        return DB::getInstance()->execute($sql);
    }
    
    public static function deleteOutdated($time)
    {
        $sql = 'DELETE FROM `' . _DB_PREFIX_ . self::TABLE_NAME . '` WHERE `timestamp` < ' . (int)$time;
        return DB::getInstance()->execute($sql);
    }
    
    public static function addData($key, $id_product)
    {
        $row = new self();
        $row->key = pSQL($key);
        $row->id_product = (int)$id_product;
        $row->timestamp = time();
        $row->save();
    }
}
