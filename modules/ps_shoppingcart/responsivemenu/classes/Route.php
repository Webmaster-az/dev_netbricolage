<?php
/**
 * 2013-2021 MADEF IT.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@madef.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    MADEF IT <contact@madef.fr>
 *  @copyright 2013-2021 MADEF IT
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class ResponsiveMenuRoute extends ObjectModel
{
    /** @var int Shop id */
    public $id_shop;

    /** @var int Category id */
    public $id_category;

    /** @var string Controller */
    public $controller;

    /** @var string Controller Path */
    public $controller_path;

    /** @var string Params */
    public $params;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'responsivemenu_route',
        'primary' => 'id_route',
        'fields' => array(
            'id_category' => array('type' => self::TYPE_INT, 'required' => true),
            'id_shop' => array('type' => self::TYPE_INT, 'required' => true),
            'controller' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 100),
            'controller_path' => array('type' => self::TYPE_STRING, 'required' => false, 'size' => 255),
            'params' => array('type' => self::TYPE_STRING),
        ),
    );
}
