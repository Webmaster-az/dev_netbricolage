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

class ArlsfSession extends ObjectModel
{
    const TABLE_NAME = 'arlsf_session';
    
    public $id;
    public $session_key;
    public $id_order;
    public $timestamp;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => self::TABLE_NAME,
        'primary' => 'id_session',
        'multilang' => false,
        'fields' => array(
            'session_key' =>            array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'id_order' =>               array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'timestamp' =>              array('type' => self::TYPE_INT, 'validate' => 'isInt')
        ),
    );
    
    public static function getAllBySession($sessionKey)
    {
        $sql = new DbQuery();
        $sql->from(self::TABLE_NAME, 't');
        $sql->where('session_key = "' . pSQL($sessionKey) . '"');
        $res = Db::getInstance()->executeS($sql);
        return $res;
    }
    
    public static function deleteAllBySession($sessionKey)
    {
        return Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . self::TABLE_NAME . ' WHERE session_key = "' . pSQL($sessionKey) . '"');
    }
    
    public static function deleteOutdated($time)
    {
        $sql = 'DELETE FROM `' . _DB_PREFIX_ . self::TABLE_NAME . '` WHERE `timestamp` < ' . (int)$time;
        return DB::getInstance()->execute($sql);
    }
    
    public static function addData($sessionKey, $idOrder)
    {
        $row = new self();
        $row->session_key = pSQL($sessionKey);
        $row->id_order = (int)$idOrder;
        $row->timestamp = time();
        $row->save();
    }
}
