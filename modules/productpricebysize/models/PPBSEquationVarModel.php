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

class PPBSEquationVarModel extends ObjectModel
{
    /** @var integer Unique ID */
    public $id_ppbs_equation_var;

    /** @var string var name */
    public $name = '';

    /** @var integer Equation Template ID */
    public $value = 0;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'ppbs_equation_var',
        'primary' => 'id_ppbs_equation_var',
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'required' => true),
            'value' => array('type' => self::TYPE_FLOAT, 'required' => true),
        )
    );
}
