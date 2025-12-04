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

$HTTP_X_REQUESTED_WITH = isset($_SERVER['HTTP_X_REQUESTED_WITH'])?$_SERVER['HTTP_X_REQUESTED_WITH']:'';
if($HTTP_X_REQUESTED_WITH != 'XMLHttpRequest') {
    exit;
}
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');
ob_start();


$error_text = '';
$status = 'success';
$message = '';

$is_error = 0;
$name_file = '';
$size_file = '';

include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');
$obj = new gsnipreviewhelp();



$action = Tools::getValue('action');

switch ($action) {
        case 'add':
            $files = $_FILES['files'];
            $data_files_return = $obj->uploadTmpFile(array('files'=>$files));
            $is_error = $data_files_return['is_error'];
            $status = $data_files_return['status'];
            $message = $data_files_return['message'];
            $size_file = $data_files_return['size_file'];
            $name_file = $data_files_return['name_file'];

        break;
        case 'del':
            $name = Tools::getValue('name');
            $obj->deleteTmpFile(array('name'=>$name));

        break;
        case 'deletefile':
            include_once(dirname(__FILE__).'/gsnipreview.php');
            $obj_gsnipreview = new gsnipreview();

            if($obj_gsnipreview->is_demo){
                $status = 'error';
                $message = 'Feature disabled on the demo mode!';
            } else {
                $id = Tools::getValue('item_id');
                $obj->deleteFile(array('id'=>$id));
            }
        break;

}



$response = new stdClass();
$content = ob_get_clean();
$response->status = $status;
$response->message = $message;

if($is_error == 0 && $action == 'add'){
    $response->params = array('size' => $size_file,'name'=>$name_file);
}



echo Tools::jsonEncode($response);