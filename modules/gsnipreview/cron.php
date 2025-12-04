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

    include_once(dirname(__FILE__).'/classes/featureshelp.class.php');
    $obj = new featureshelp();
    $cron_on = (int)Configuration::get($name. 'reminder');

    if($cron_on==1){
        $obj->sendCronTab();
        exit;
    } else {
        echo 'Error: Enable CRON in the module settings';
    }

} else {
    echo 'Error: Access denien! Invalid token!';
}




