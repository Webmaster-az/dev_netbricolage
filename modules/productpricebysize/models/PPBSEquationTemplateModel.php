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


class PPBSEquationTemplateModel extends ObjectModel
{
    /** @var integer Unique ID */
    public $id_equation_template;

    /** @var integer Product ID */
    public $name;

    /** @var integer Combination ID */
    public $equation;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'ppbs_equation_template',
        'primary' => 'id_equation_template',
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'required' => true),
            'equation' => array('type' => self::TYPE_STRING, 'required' => true)
        )
    );
}
