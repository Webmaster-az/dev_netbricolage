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

header("Access-Control-Allow-Origin: *");
$HTTP_X_REQUESTED_WITH = isset($_SERVER['HTTP_X_REQUESTED_WITH'])?$_SERVER['HTTP_X_REQUESTED_WITH']:'';
if($HTTP_X_REQUESTED_WITH != 'XMLHttpRequest') {
    //exit;
}
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../init.php');

ob_start(); 
$status = 'success';
$message = '';

$action = Tools::getValue('action');
$module_name = 'gsnipreview';


if (version_compare(_PS_VERSION_, '1.5', '<')){
	require_once(_PS_MODULE_DIR_.$module_name.'/backward_compatibility/backward.php');
} else{
	$cookie = Context::getContext()->cookie;
	$smarty = Context::getContext()->smarty;
}



include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');
$obj = new gsnipreviewhelp();

switch ($action){

    case 'abuse':
        $review_id = (int)Tools::getValue('id_review');
        $smarty->assign($module_name.'review_id', $review_id);



        $token = Tools::getValue('token');

        $is_exists_abuse = $obj->isAbuseExists(array('review_id'=>$review_id));
        $smarty->assign($module_name.'is_abuse', !$is_exists_abuse['is_abuse']);

        $data_review = $obj->getReviewForAbuse(array('review_id'=>$review_id,'token'=>$token));
        $smarty->assign($module_name.'abuserev', $data_review);

        include_once(dirname(__FILE__).'/gsnipreview.php');
        $objgsnipreview = new gsnipreview();

        $data_translate = $objgsnipreview->translateCustom();
        $smarty->assign($module_name.'raad_msg1', $data_translate['raad_msg1']);



        ob_start();
        if(defined('_MYSQL_ENGINE_')){
            echo $objgsnipreview->renderReviewAbuseAdmin();
        } else {
            echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/review-abuse-admin.tpl');
        }
        $html = ob_get_clean();

    break;
    case 'active':
        $review_id = (int)Tools::getValue('id_review');
        $value = (int)Tools::getValue('value');
        if($value == 0){
            $obj->publish(array('id'=>$review_id));
        } else {
            $obj->unpublish(array('id'=>$review_id));
        }


    break;
    case 'status-abuse':
        $review_id = (int)Tools::getValue('review_id');
        $obj->setReviewIsNotAbusive(array('review_id'=>$review_id));
    break;
    case 'changed':
        $review_id = (int)Tools::getValue('id_review');
        $value = (int)Tools::getValue('value');
        $token = Tools::getValue('token');

        $smarty->assign($module_name.'review_id', $review_id);



        $data_review = $obj->getItem(array('id'=>$review_id,'token'=>$token));

        $smarty->assign($module_name.'datareview', $data_review['reviews'][0]);

        $smarty->assign($module_name.'token', $token);

        include_once(dirname(__FILE__).'/gsnipreview.php');
        $objgsnipreview = new gsnipreview();

        $data_translate = $objgsnipreview->translateCustom();
        $smarty->assign($module_name.'rca_msg1', $data_translate['rca_msg1']);
        $smarty->assign($module_name.'rca_msg2', $data_translate['rca_msg2']);


        switch(Configuration::get($module_name.'stylestars')){
            case 'style1':
                $smarty->assign($module_name.'activestar', 'star-active-yellow.png');
                $smarty->assign($module_name.'noactivestar', 'star-noactive-yellow.png');
                break;
            case 'style2':
                $smarty->assign($module_name.'activestar', 'star-active-green.png');
                $smarty->assign($module_name.'noactivestar', 'star-noactive-green.png');
                break;
            case 'style3':
                $smarty->assign($module_name.'activestar', 'star-active-blue.png');
                $smarty->assign($module_name.'noactivestar', 'star-noactive-blue.png');
                break;
            default:
                $smarty->assign($module_name.'activestar', 'star-active-yellow.png');
                $smarty->assign($module_name.'noactivestar', 'star-noactive-yellow.png');
                break;
        }


        ob_start();
        if(defined('_MYSQL_ENGINE_')){
            echo $objgsnipreview->renderReviewChangedAdmin();
        } else {
            echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/review-changed-admin.tpl');
        }
        $html = ob_get_clean();

        break;

    case 'change-wait':
        $review_id = (int)Tools::getValue('review_id');
        $is_display_old = (int)Tools::getValue('is_display_old');
        $admin_response = Tools::getValue('admin_response');
        $is_send_again = Tools::getValue('is_send_again');

        $obj->setChangedReview(array('review_id'=>$review_id, 'is_display_old'=>$is_display_old,'admin_response'=>$admin_response,'is_send_again'=>$is_send_again));
    break;
    case 'deleteimg':
        include_once(dirname(__FILE__).'/gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();

        if($obj_gsnipreview->is_demo){
            $status = 'error';
            $message = 'Feature disabled on the demo mode!';
        } else {
            $item_id = Tools::getValue('item_id');
            $id_customer = Tools::getValue('id_customer');
            $obj->deleteAvatar(array('id' => $item_id,'id_customer'=>$id_customer));
        }
    break;
    case 'importoldorders':
        include_once(dirname(__FILE__).'/gsnipreview.php');
        $objgsnipreview = new gsnipreview();

        $data_translate = $objgsnipreview->translateCustom();

        $start_date = Tools::getValue('start_date');
        $end_date = date('Y-m-d H:i:s');

        $is_error = 0;
        $msg_error  = '';
        if(strtotime($start_date) > strtotime($end_date)){

            $is_error = 1;
            $msg_error = $data_translate['orders_date_start_more_end'];

        }

        if(!$start_date){
            $is_error = 1;
            $msg_error = $data_translate['orders_date_empty'];
        }

        //var_dump($is_error);exit;

        if(!$is_error){

            include_once(dirname(__FILE__).'/classes/featureshelp.class.php');
            $obj_featureshelp = new featureshelp();

            $date_importoldorders = $obj_featureshelp->importOldOrders(array('start_date'=>$start_date, 'end_date'=>$end_date));

            if($date_importoldorders) {

                $smarty->assign($module_name . 'msg', $data_translate['orders_date_ok1'].' <b>'.(int)$date_importoldorders.'</b> '.$data_translate['orders_date_ok2']);

                if (defined('_MYSQL_ENGINE_')) {
                    echo $objgsnipreview->renderReviewFacebookSuccess();
                } else {
                    echo Module::display(dirname(__FILE__) . '/gsnipreview.php', 'views/templates/hooks/success.tpl');
                }

            } else {
                $is_error = 1;
                $msg_error =  $data_translate['orders_date_not_exists'];
            }

        }


        if($is_error) {
            $smarty->assign($module_name . 'msg', $msg_error);

            if (defined('_MYSQL_ENGINE_')) {
                echo $objgsnipreview->renderReviewError();
            } else {
                echo Module::display(dirname(__FILE__) . '/gsnipreview.php', 'views/templates/hooks/error.tpl');
            }

        }

    break;

	default:
		$status = 'error';
		$message = 'Unknown parameters!';
	break;
}

$response = new stdClass();
$content = ob_get_clean();
$response->status = $status;
$response->message = $message;	
if($action == "abuse" || $action == "changed")
    $response->params = array('content' => $html );
else
	$response->params = array('content' => $content);


echo Tools::jsonEncode($response);


