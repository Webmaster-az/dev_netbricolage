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


$name = "gsnipreview";
$token = Tools::getValue('token');

include_once(dirname(__FILE__).'/gsnipreview.php');
$obj_main = new gsnipreview();

$_token = $obj_main->getokencron();


if($_token == $token){


    include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');
    $obj = new gsnipreviewhelp();

    $obj->generateGoogleReviews();


} else {
    echo 'Error: Access denien! Invalid token!';
}


