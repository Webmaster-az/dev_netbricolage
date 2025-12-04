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

include(dirname(__FILE__) . '/../../../../config/config.inc.php');
include(dirname(__FILE__) . '/../../../../init.php');
include_once(_PS_MODULE_DIR_ . '/productpricebysize/lib/bootstrap.php');

$module = Module::getInstanceByName('productpricebysize');

if (Tools::getValue('action') != '') {
    $equation_editor = new MPEquationEditorController($module);
    die($equation_editor->route());
}
