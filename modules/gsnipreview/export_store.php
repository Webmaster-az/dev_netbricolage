<?php
/**
 * mitrocops
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
/*
 *
 * @author    mitrocops
 * @category seo
 * @package gsnipreview
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');

@ini_set('display_errors', 'off');


$name = "gsnipreview";
$token = Tools::getValue('token');

include_once(dirname(__FILE__).'/gsnipreview.php');
$obj_main = new gsnipreview();

$_token = $obj_main->getokencron();


if($_token == $token){

    /// get prefix module //
    $prefix = $obj_main->getPrefixShopReviews();
    /// get prefix module //

    $name_class =  'csvhelp'.$prefix;
    include_once(dirname(__FILE__).'/classes/'.$name_class.'.class.php');
    $obj = new $name_class;


    $obj->export();


} else {
    echo 'Error: Access denied! Invalid token!';
}

