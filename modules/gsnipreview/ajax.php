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

ob_start();

$status = 'success';
$message = '';
$name_module = 'gsnipreview';
$smarty = Context::getContext()->smarty;
$cookie = Context::getContext()->cookie;

include_once(dirname(__FILE__).'/classes/storereviews.class.php');

$obj_shopreviews = new storereviews();
$action = Tools::getValue('action');

switch ($action){
	case 'addreview':
		$_html = '';
		$error_type = 0;
        $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;
        $ok_captcha = 1;

        if(!$id_customer) {
            $codeCaptcha = Tools::getValue('captcha');
            $cookie = new Cookie($name_module);
            $code = $cookie->secure_code_testim;            
            $is_captcha = Configuration::get($name_module . 'is_captchati');

            if ($is_captcha == 1) {
                if ($code == $codeCaptcha)
                    $ok_captcha = 1;
                else
                    $ok_captcha = 0;
            }
        }

		if($ok_captcha == 1){
            $name = strip_tags(trim(Tools::getValue('name-review')));
            $country = strip_tags(trim(Tools::getValue('country-review')));
            $city = strip_tags(trim(Tools::getValue('city-review')));
            $email = trim(Tools::getValue('email-review'));
            $rating = Tools::getValue('rat_rel');
            $web = strip_tags(str_replace("http://","",trim(Tools::getValue('web-review'))));
            $web = strip_tags(str_replace("https://","",trim(Tools::getValue('web-review'))));
            $text_review = trim(Tools::getValue('text-review'));
            $company = strip_tags(trim(Tools::getValue('company-review')));
            $address = strip_tags(trim(Tools::getValue('address-review')));
        
            if(!preg_match("/[0-9a-z-_]+@[0-9a-z-_^\.]+\.[a-z]{2,4}/i", $email)) {
                $error_type = 2;
                $status = 'error';
            }		 

            if($error_type == 0 && Tools::strlen($name)==0){
                $error_type = 1;
                $status = 'error';
            }		 		 

            if($error_type == 0 && Tools::strlen($text_review)==0){
                $error_type = 3;
                $status = 'error';
            }

            $files = $_FILES['avatar-review'];

            if(!empty($files['name']))
            {
                if(!$files['error'])
                {
                    $type_one = $files['type'];
                    $ext = explode("/",$type_one);

                    if(strpos('_'.$type_one,'image')<1)
                    {
                        $error_type = 8;
                        $status = 'error';
                    }elseif(!in_array($ext[1],array('png','x-png','gif','jpg','jpeg','pjpeg'))) {
                        $error_type = 9;
                        $status = 'error';
                    }
                }
            }

            if($error_type == 0){
                $post_images = Tools::getValue('post_images');
                //insert review
                $_data = array('name' => $name,
                            'email' => $email,
                            'web' => $web,
                            'text_review' => $text_review,
                            'company' => $company,
                            'address' => $address,
                            'rating' =>$rating,
                            'city'=>$city,
                            'country'=>$country,
                            'post_images' => $post_images,
                        );

                $obj_shopreviews->saveTestimonial($_data);
            }
        } else {
			$_html = '';
    		// invalid security code (captcha)
			$error_type = 4;
			$status = 'error';
		}
        
    break;

    case 'active':
        $id = (int)Tools::getValue('id');
        $value = (int)Tools::getValue('value');
        if($value == 0){
            $value = 1;
        } else {
            $value = 0;
        }

        $type_action = Tools::getValue('type_action');

        switch($type_action){
            case 'testimonial':
                $obj_shopreviews->setPublsh(array('id'=>$id,'active'=>$value));
                break;
        }

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
            $obj_shopreviews->deleteAvatar(array('id' => $item_id,'id_customer'=>$id_customer));
        }
    break;

    case 'importoldorders':
        include_once(dirname(__FILE__).'/gsnipreview.php');
        $objgsnipreview = new gsnipreview();
        $data_translate = $objgsnipreview->translateItems();
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

        if(!$is_error){
            include_once(dirname(__FILE__).'/classes/featureshelptestim.class.php');
            $obj_featureshelp = new featureshelptestim();
            $date_importoldorders = $obj_featureshelp->importOldOrders(array('start_date'=>$start_date, 'end_date'=>$end_date));

            if($date_importoldorders) {
                $smarty->assign($name_module . 'msg', $data_translate['orders_date_ok1'].' <b>'.(int)$date_importoldorders.'</b> '.$data_translate['orders_date_ok2']);

                if (defined('_MYSQL_ENGINE_')) {
                    echo $objgsnipreview->renderSuccess();
                } else {
                    echo Module::display(dirname(__FILE__) . '/gsnipreview.php', 'views/templates/hooks/success.tpl');
                }
            } else {
                $is_error = 1;
                $msg_error =  $data_translate['orders_date_not_exists'];
            }
        }

        if($is_error) {
            $smarty->assign($name_module . 'msg', $msg_error);

            if (defined('_MYSQL_ENGINE_')) {
                echo $objgsnipreview->renderError();
            } else {
                echo Module::display(dirname(__FILE__) . '/gsnipreview.php', 'views/templates/hooks/error.tpl');
            }
        }
    break;

    case 'reminder-send':
        include_once(dirname(__FILE__).'/gsnipreview.php');
        $objgsnipreview = new gsnipreview();
        $data_translate = $objgsnipreview->translateItems();
        $id = (int)Tools::getValue('id');
        $type = Tools::getValue('type');
        $is_error = 0;

        include_once(dirname(__FILE__).'/classes/featureshelptestim.class.php');

        $obj_featureshelp = new featureshelptestim();

        ob_start();

        $status_order = $obj_featureshelp->getOrderStatus(array('order_id'=>$id));
        $reminder_link_settings = '<a href="javascript:void(0)"
            onclick="tabs_custom(115)" style="text-decoration:underline">
            '.$data_translate['customer_reminder_settings'].'</a>';

        $_prefix = $objgsnipreview->getPrefixShopReviews();
        $orderstatuses = Configuration::get($name_module.'orderstatuses'.$_prefix);
        $orderstatuses = explode(",",$orderstatuses);

        if(in_array($status_order,$orderstatuses)) {
            $data_check_errors = $obj_featureshelp->getCronTaskDelayForReminder(array('type'=>$type,'id_order'=>$id));
            $type_error_reminder = $data_check_errors['type_error'];
            if($type_error_reminder == 0) {
                // send tasks
                $obj_featureshelp->sendCronTab(array('order_id' => $id));
            } else {
                // handler errors
                $html_err_txt = '';

                switch($type_error_reminder){
                    case 1:
                        $delay = (int)Configuration::get($name_module . 'delay' . $_prefix);

                        $html_err_txt = $data_translate['customer_reminder_error1_1'].'&nbsp;<b>'.$delay.'</b>&nbsp;'.$data_translate['customer_reminder_error1_2'].
                        '<br/><br/>'.$data_translate['configure_reminder_delay_first'].': '.$reminder_link_settings;

                    break;

                    case 2:
                        $delaysec = (int)Configuration::get($name_module . 'delaysec' . $_prefix);

                        $html_err_txt = $data_translate['customer_reminder_error1_1'].'&nbsp;<b>'.$delaysec.'</b>&nbsp;'.$data_translate['customer_reminder_error2_2'].
                            '<br/><br/>'.$data_translate['configure_reminder_delay_second'].': '.$reminder_link_settings;

                    break;

                    case 3:
                        $html_err_txt = $data_translate['review_reminder_second']. '<br/><br/>'. $reminder_link_settings;

                    break;

                    case 4:
                        $html_err_txt = $data_translate['review_reminder']. '<br/><br/>'. $reminder_link_settings;

                    break;

                    case 5:
                        $html_err_txt = $data_translate['review_reminder_customer_txt'].'<br/><br/>'.$data_translate['review_reminder_customer']. '<br/><br/>'. $reminder_link_settings;

                    break;

                }



                echo $html_err_txt;



                $is_error = 1;

                // handler errors

            }

        } else {

            $txt_accepted_order_statuses = '';
            $txt_accepted_order_statuses .= '<b>'.$data_translate['accepted_order_statuses'].'</b>:<br/>';
            $accepted_order_statuses = $obj_featureshelp->getAcceptedOrderStatuses();

            foreach($accepted_order_statuses as $accepted_order_status){
                $payment_order  = $accepted_order_status['name'];
                $txt_accepted_order_statuses .= "<br/>".$payment_order;
            }

            $txt_accepted_order_statuses .= '<br/><br/>'.$data_translate['configure_order_statuses'].': '.$reminder_link_settings;

            echo $txt_accepted_order_statuses;

            $is_error = 1;
        }

        $html_resutlt = ob_get_clean();
        $smarty->assign($name_module . 'msg', $html_resutlt);

        if($is_error == 0) {
            if (defined('_MYSQL_ENGINE_')) {
                echo $objgsnipreview->renderSuccess();
            } else {
                echo Module::display(dirname(__FILE__) . '/gsnipreview.php', 'views/templates/hooks/success.tpl');
            }
        } else {
            if (defined('_MYSQL_ENGINE_')) {
                echo $objgsnipreview->renderError();
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

if($action == "addreview") {
    $response->params = array('content' => $_html,
        'error_type' => $error_type
    );
} elseif($action == "reminder-send") {
    $response->params = array('content' => $content, 'is_error' => $is_error);
} else {
    $response->params = array('content' => $content);
}

echo Tools::jsonEncode($response);
?>