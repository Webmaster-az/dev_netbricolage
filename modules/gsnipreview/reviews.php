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
	case 'add':

        $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;

        //echo "<pre>"; var_dump($_FILES);exit;

        //echo "<pre>"; var_dump($_POST); var_dump($_FILES);exit;


        $ok_captcha = 1;
        if(!$id_customer) {
            $codeCaptcha = Tools::getValue('captcha');

            if (version_compare(_PS_VERSION_, '1.5', '<')) {
                $data_code = getcookie_gsnipreview();
                $code = $data_code['code'];
            } else {
                $cookie = new Cookie($module_name);
                $code = $cookie->secure_code_gsnipreview;
            }


            $is_captcha = Configuration::get($module_name . 'is_captcha');
            if ($is_captcha == 1) {
                if ($code == $codeCaptcha)
                    $ok_captcha = 1;
                else
                    $ok_captcha = 0;
            }
        }
		
		$error_type = 0;
		$html = '';
		$paging = '';
		$title = '';
		$text_review = '';
        $count_reviews = 0;
        $text_reviews = '';
        $voucher_html = '';
        $voucher_html_suggestion  = '';


		
		if($ok_captcha == 1){

        include_once(dirname(__FILE__).'/gsnipreview.php');
        $objgsnipreview = new gsnipreview();
        $id_lang = $objgsnipreview->getIdLang();

            ### ratings ###
		if(Configuration::get($module_name.'ratings_on')){

           $ratings = array();


           $criterions = $obj->getReviewCriteria(array('id_lang'=>$id_lang,'id_shop'=>$obj->getIdShop()));
           if(sizeof($criterions)>0) {

               foreach($criterions as $criterion) {
                   $id_criterion = $criterion['id_gsnipreview_review_criterion'];
                   $rating_criterion = Tools::getValue('rat_rel'.$id_criterion);
                   if($rating_criterion)
                       $ratings[$id_criterion] = $rating_criterion;
               }

           }

           if(sizeof($ratings)==0){
               $ratings[0] = Tools::getValue('rat_rel');
           }

		} else {
            $ratings[0] = 0;

        }
        ### ratings ###




        if(Configuration::get($module_name.'title_on')){
			$title = trim(Tools::getValue('subject-review'));
		}

		$name = trim(Tools::getValue('name-review'));
        $email = trim(Tools::getValue('email-review'));

        if(Configuration::get($module_name.'text_on')){
			$text_review = Tools::getValue('text-review');
		}

		$id_customer = Tools::getValue('id_customer');
		$id_product = Tools::getValue('id_product');

        if(Configuration::get($module_name.'is_onerev') != 1) {
            $is_alreadyaddreview = $obj->checkIsUserAlreadyAddReview(array('id_product' => $id_product, 'id_customer' => $id_customer));
        } else {
            $is_alreadyaddreview = 0;
        }
		if($is_alreadyaddreview){
			$error_type = 1;
            $status = 'error';
		}

        if(!preg_match("/[0-9a-z-_]+@[0-9a-z-_^\.]+\.[a-z]{2,4}/i", $email) && !$id_customer) {
            $error_type = 2;
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

            $filesrev = Tools::getValue('filesrev');

		if($error_type == 0){
            $post_images = Tools::getValue('post_images');
			// save review
			$data = array('id_product'=>$id_product,
						  'id_customer' => $id_customer,
						  'title' => $title,
						  'name' => $name,
                          'email' => $email,
			 			  'text_review' => $text_review,
                          'ratings' => $ratings,
                          'id_lang' => $id_lang,
                          'post_images' => $post_images,
                          'filesrev'=>$filesrev,
						  );
			$data_voucher = $obj->saveReview($data);
            // save review


            $vis_onfb = (int)Configuration::get($module_name.'vis_onfb');
            if($vis_onfb) {

                $data_translate = $objgsnipreview->translateCustom();
                $smarty->assign($module_name . 'msg', $data_translate['coupon_suggestion_msg']);
                $smarty->assign($module_name . 'title', $data_translate['coupon_suggestion_title']);

                ob_start();
                if (defined('_MYSQL_ENGINE_')) {
                    echo $objgsnipreview->renderReviewFacebookSuggestion();
                } else {
                    echo Module::display(dirname(__FILE__) . '/gsnipreview.php', 'views/templates/hooks/coupon-suggestion.tpl');
                }
                $voucher_html_suggestion = ob_get_clean();
            }


            // create voucher for review

            if(Configuration::get($module_name.'vis_on') == 1){
                include_once(dirname(__FILE__).'/gsnipreview.php');
                $objgsnipreview = new gsnipreview();
                $data_translate = $objgsnipreview->translateCustom();

                $smarty->assign(
                    array(
                        $module_name.'firsttext' => $data_translate['firsttext'],
                        $module_name.'discountvalue' => $data_translate['discountvalue'],
                        $module_name.'secondtext' => $data_translate['secondtext'],
                        $module_name.'voucher_code' => $data_voucher['voucher_code'],
                        $module_name.'threetext' => $data_translate['threetext'],
                        $module_name.'date_until' => $data_voucher['date_until'],

                        )
                );

                ob_start();
                if(defined('_MYSQL_ENGINE_')){
                    echo $objgsnipreview->renderReviewCoupon();
                } else {
                    echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/coupon.tpl');
                }
                $voucher_html = ob_get_clean();



            }
            // create voucher for review

		}
		
		

		
		} else {
			$html = '';
			$paging = ''; 
			$count_reviews = null;
			$text_reviews = null;
			$voucher_html = '';
            $voucher_html_suggestion = '';
			
			// invalid security code (captcha)
			$error_type = 3;
			$status = 'error';
		}
		
	break;



    case 'abuse':
        $review_id = (int)Tools::getValue('rid');
        $smarty->assign($module_name.'review_id', $review_id);



        $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;
        $smarty->assign($module_name.'islogged', $id_customer);

        $is_exists_abuse = $obj->isAbuseExists(array('review_id'=>$review_id));
        $smarty->assign($module_name.'is_abuse', $is_exists_abuse['is_abuse']);

        $data_review = $obj->getReviewForAbuse(array('review_id'=>$review_id));
        $smarty->assign($module_name.'abuserev', $data_review);

        include_once(dirname(__FILE__).'/gsnipreview.php');
        $objgsnipreview = new gsnipreview();


        $data_translate = $objgsnipreview->translateCustom();
        $smarty->assign(
            array(
                $module_name.'raf_msg1' => $data_translate['ptc_msg1'],
                $module_name.'raf_msg2' => $data_translate['ptc_msg3'],
                $module_name.'raf_msg3' => $data_translate['ptc_msg4'],
                $module_name.'raf_msg4' => $data_translate['ptc_msg2'],
                $module_name.'raf_msg5' => $data_translate['raf_msg5'],
                $module_name.'raf_msg6' => $data_translate['ptc_msg8'],
                $module_name.'raf_msg7' => $data_translate['ptc_msg9'],
                $module_name.'raf_msg8' => $data_translate['raf_msg8'],

            )
         );

        ob_start();
        if(defined('_MYSQL_ENGINE_')){
            echo $objgsnipreview->renderReviewAbuseForm();
        } else {
            echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/review-abuse-form.tpl');
        }
        $html = ob_get_clean();

    break;
    case 'post-abuse':
        $codeCaptcha = Tools::strlen(Tools::getValue('captcha'))>0?Tools::getValue('captcha'):'';

        /* call before new Cookie() */
        $id_guest = (int)$cookie->id_guest;
        $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;
        /* call before new Cookies() */


        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $data_code = getcookie_gsnipreview(array('type'=>'abuse'));
            $code = $data_code['code'];
        } else {
            $cookie = new Cookie($module_name);
            $code = $cookie->abuse_code_gsnipreview;
        }



        $ok_captcha = 1;
        if(!$id_customer){
            if($code == $codeCaptcha)
                $ok_captcha = 1;
            else
                $ok_captcha = 0;
        }

        $error_type = 0;
        $html = '';



        if($ok_captcha == 1){

            include_once(dirname(__FILE__).'/gsnipreview.php');
            $objgsnipreview = new gsnipreview();



            $name = trim(Tools::getValue('name'));

            $email = trim(Tools::getValue('email'));
            $text = Tools::getValue('text');
            $review_id = Tools::getValue('review_id');


            if(!preg_match("/[0-9a-z-_]+@[0-9a-z-_^\.]+\.[a-z]{2,4}/i", $email) && !$id_customer) {
                $error_type = 2;
                $status = 'error';

            }



            $is_exists_abuse = $obj->isAbuseExists(array('review_id'=>$review_id,'id_customer'=>(isset($id_customer)?$id_customer:$id_guest)));

            if($is_exists_abuse['is_abuse']){
                $error_type = 1;
                $status = 'error';
            }

            if($error_type == 0){
                // save abuse
                $data = array(
                    'review_id'=>$review_id,
                    'name' => $name,
                    'email' => $email,
                    'text' => $text,
                    'id_customer' => $id_customer,
                    'id_guest' => $id_guest,
                );
                $obj->saveAbuse($data);
                // save abuse
            }

        } else {
            $html = '';

            // invalid security code (captcha)
            $error_type = 3;
            $status = 'error';
        }
    break;
    case 'helpfull':
        $error_type = 0;
        $html = '';

        $review_id = (int)Tools::getValue('rid');
        $val = (int)Tools::getValue('val');

        include_once(dirname(__FILE__).'/gsnipreview.php');
        $objgsnipreview = new gsnipreview();
        $data_translate = $objgsnipreview->translateCustom();

        $id_guest = (int)$cookie->id_guest;
        $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;


        $is_exists_helpfull = $obj->isHelpfullExists(array('review_id'=>$review_id,'id_customer'=>$id_customer, 'id_guest'=>$id_guest));

        if($is_exists_helpfull['count']){
            $error_type = 1;
            $status = 'error';

            $message = $data_translate['helpfull_exists'];
        }

        $count_yes = 0;
        $count_all = 0;

        if($error_type == 0){
            // save vote
            $data = array(
                'review_id'=>$review_id,
                'helpfull' => $val,
                'id_customer' => $id_customer,
                'id_guest' => $id_guest,
            );
            $obj->saveHelpfullVote($data);
            // save vote
            $message = $data_translate['helpfull_success'];

            $data_helpfull = $obj->getHelpfullVotes(array('review_id'=>$review_id));
            $count_yes = $data_helpfull['yes'];
            $count_all = $data_helpfull['all'];
        }

    break;
    case 'facebook':

        include_once(dirname(__FILE__).'/gsnipreview.php');
        $objgsnipreview = new gsnipreview();

        $rid = Tools::getValue('rid');

        $vis_onfb = (int)Configuration::get($module_name.'vis_onfb');

        $data_my = $obj->isMyReview(array('id_review'=>$rid));
        $id = $data_my[0]['id'];
        $email = Tools::strlen($data_my[0]['email'])>0?$data_my[0]['email']:null;

        // only create voucher when voucher is enable AND customer try get voucher only for own review!
        if($vis_onfb && $id){
            // create voucher for discount

            $data_voucher = $obj->createVoucherSocialShare(array('rid'=>$rid));




            $data_translate = $objgsnipreview->translateCustom();

            $firsttext = '';
            $discountvalue = '';

            if($data_voucher['is_exists_voucher_for_customer'] == 0) {

                // get voucher
                $firsttext = $data_translate['firsttext'];
                $discountvalue = $data_translate['discountvaluefb'];

                if($email) {
                    $obj->sendNotificationCreatedVoucher(
                        array(
                            'email_customer' => $email,
                            'data_voucher' => $data_voucher,
                            'id_review' => $id,
                            'is_facebook' => 1,
                        )
                    );
                }


            } elseif($data_voucher['is_exists_voucher_for_customer'] == 1 && $data_voucher['is_expiried_voucher']==0 && $data_voucher['is_used_voucher']==0) {

                // laready get voucher
                $firsttext = $data_translate['already_get_coupon'];
                $discountvalue = '';

            } elseif($data_voucher['is_exists_voucher_for_customer'] == 1 && $data_voucher['is_expiried_voucher']==1 && $data_voucher['is_used_voucher']==0) {

                //expired voucher
                $firsttext = $data_translate['expiried_voucher'];
                $discountvalue = '';

            } elseif($data_voucher['is_exists_voucher_for_customer'] == 1 && $data_voucher['is_expiried_voucher']==0 && $data_voucher['is_used_voucher']==1) {

                // used voucher
                $firsttext = $data_translate['used_voucher'];
                $discountvalue = '';
            }

            $smarty->assign(
                array(
                    $module_name . 'firsttext' => $firsttext,
                    $module_name . 'discountvalue' => $discountvalue,
                    $module_name . 'secondtext' => $data_translate['secondtext'],
                    $module_name . 'voucher_code' => $data_voucher['voucher_code'],
                    $module_name . 'threetext' => $data_translate['threetext'],
                    $module_name . 'date_until' => $data_voucher['date_until'],
                    $module_name . 'is_facebook' => 1,
                )
            );

            ob_start();
            if (defined('_MYSQL_ENGINE_')) {
                echo $objgsnipreview->renderReviewCoupon();
            } else {
                echo Module::display(dirname(__FILE__) . '/gsnipreview.php', 'views/templates/hooks/coupon.tpl');
            }
            $html = ob_get_clean();

            // create voucher for discount
        } else {
            // just message: thanks for share review at facebook

            $data_translate = $objgsnipreview->translateCustom();
            $smarty->assign($module_name.'msg', $data_translate['coupon_success']);

            ob_start();
            if(defined('_MYSQL_ENGINE_')){
                echo $objgsnipreview->renderReviewFacebookSuccess();
            } else {
                echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/success.tpl');
            }
            $html = ob_get_clean();
            // just message: thanks for share review at facebook

        }

    break;
    case 'modify_my':
        $review_id = (int)Tools::getValue('id_review');
        $value = (int)Tools::getValue('value');
        $id_customer = Tools::getValue('id_customer');
        $current_id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;

        if($id_customer == $current_id_customer) {
            $smarty->assign($module_name . 'review_id', $review_id);


            $data_review = $obj->getItem(array('id' => $review_id));

            $smarty->assign($module_name . 'datareview', $data_review['reviews'][0]);


            include_once(dirname(__FILE__) . '/gsnipreview.php');
            $objgsnipreview = new gsnipreview();


            switch (Configuration::get($module_name . 'stylestars')) {
                case 'style1':
                    $smarty->assign($module_name . 'activestar', 'star-active-yellow.png');
                    $smarty->assign($module_name . 'noactivestar', 'star-noactive-yellow.png');
                    break;
                case 'style2':
                    $smarty->assign($module_name . 'activestar', 'star-active-green.png');
                    $smarty->assign($module_name . 'noactivestar', 'star-noactive-green.png');
                    break;
                case 'style3':
                    $smarty->assign($module_name . 'activestar', 'star-active-blue.png');
                    $smarty->assign($module_name . 'noactivestar', 'star-noactive-blue.png');
                    break;
                default:
                    $smarty->assign($module_name . 'activestar', 'star-active-yellow.png');
                    $smarty->assign($module_name . 'noactivestar', 'star-noactive-yellow.png');
                    break;
            }

            $smarty->assign($module_name.'ratings_on', Configuration::get($module_name.'ratings_on'));
            $smarty->assign($module_name.'title_on', Configuration::get($module_name.'title_on'));
            $smarty->assign($module_name.'text_on', Configuration::get($module_name.'text_on'));

            $data_translate = $objgsnipreview->translateCustom();
            $smarty->assign(
                        array(
                                $module_name.'rcmy_msg1' => $data_translate['ptc_msg2'],
                                $module_name.'rcmy_msg2' => $data_translate['ptc_msg5'],
                                $module_name.'rcmy_msg3' => $data_translate['ptc_msg6'],
                                $module_name.'rcmy_msg4' => $data_translate['ptc_msg7'],
                                $module_name.'rcmy_msg5' => $data_translate['rcmy_msg5'],

                        )
            );


            ob_start();
            if (defined('_MYSQL_ENGINE_')) {
                echo $objgsnipreview->renderReviewChangedMy();
            } else {
                echo Module::display(dirname(__FILE__) . '/gsnipreview.php', 'views/templates/hooks/review-changed-my.tpl');
            }
            //$content = ob_get_clean();


        } else {
            include_once(dirname(__FILE__).'/gsnipreview.php');
            $objgsnipreview = new gsnipreview();
            // just message: thanks for share review at facebook

            $data_translate = $objgsnipreview->translateCustom();
            $smarty->assign($module_name.'msg', $data_translate['error_login']);

            ob_start();
            if(defined('_MYSQL_ENGINE_')){
                echo $objgsnipreview->renderReviewError();
            } else {
                echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/error.tpl');
            }
            //$content = ob_get_clean();
            // just message: thanks for share review at facebook

    }

        break;
    case 'change-wait':

        $current_id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;

        if($current_id_customer) {
            include_once(dirname(__FILE__) . '/gsnipreview.php');
            $objgsnipreview = new gsnipreview();
            $id_lang = $objgsnipreview->getIdLang();

            ### ratings ###
            if (Configuration::get($module_name . 'ratings_on')) {

                $ratings = array();


                $criterions = $obj->getReviewCriteria(array('id_lang' => $id_lang, 'id_shop' => $obj->getIdShop()));
                if (sizeof($criterions) > 0) {

                    foreach ($criterions as $criterion) {
                        $id_criterion = $criterion['id_gsnipreview_review_criterion'];
                        $rating_criterion = Tools::getValue('rating' . $id_criterion);
                        if ($rating_criterion)
                            $ratings[$id_criterion] = $rating_criterion;
                    }

                }

                if (sizeof($ratings) == 0) {
                    $ratings[0] = Tools::getValue('rating');
                }

            } else {
                $ratings[0] = 0;

            }
            ### ratings ###

            $review_id = (int)Tools::getValue('review_id');
            $title_review = Tools::getValue('title_review');
            $text_review = Tools::getValue('text_review');

            $data = array('review_id' => $review_id, 'title_review' => $title_review, 'text_review' => $text_review, 'ratings' => $ratings);



            $obj->setChangedReviewFromCustomer($data);
        } else {
            include_once(dirname(__FILE__).'/gsnipreview.php');
            $objgsnipreview = new gsnipreview();

            $data_translate = $objgsnipreview->translateCustom();
            $message = $data_translate['error_login'];
            $status = 'error';

        }
    break;
    case 'change-reminder':
        $current_id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;

        if($current_id_customer) {
            $reminder_status = (int)Tools::getValue('reminder_status');
            $data = array('id_customer'=>$current_id_customer,'reminder_status'=>$reminder_status);
            $obj->updateReminderForCustomer($data);
        }else {
                include_once(dirname(__FILE__).'/gsnipreview.php');
                $objgsnipreview = new gsnipreview();

                $data_translate = $objgsnipreview->translateCustom();
                $message = $data_translate['error_login'];
                $status = 'error';

            }
    break;
    case 'reminder-send':
        include_once(dirname(__FILE__).'/gsnipreview.php');
        $objgsnipreview = new gsnipreview();

        $data_translate = $objgsnipreview->translateCustom();

        $id = (int)Tools::getValue('id');
        $type = Tools::getValue('type');

        $is_error = 0;

        include_once(dirname(__FILE__).'/classes/featureshelp.class.php');
        $obj_featureshelp = new featureshelp();

        ob_start();

        $status_order = $obj_featureshelp->getOrderStatus(array('order_id'=>$id));

        $reminder_link_settings = '<a href="javascript:void(0)"
                onclick="tabs_custom(114)" style="text-decoration:underline">
                '.$data_translate['customer_reminder_settings'].'</a>';


        $_prefix = $objgsnipreview->getPrefixReviews();

        $orderstatuses = Configuration::get($module_name.'orderstatuses');


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
                        $delay = (int)Configuration::get($module_name . 'delay');
                        $html_err_txt = $data_translate['customer_reminder_error1_1'].'&nbsp;<b>'.$delay.'</b>&nbsp;'.$data_translate['customer_reminder_error1_2'].
                            '<br/><br/>'.$data_translate['configure_reminder_delay_first'].': '.$reminder_link_settings;
                        break;
                    case 2:
                        $delaysec = (int)Configuration::get($module_name . 'delaysec' . $_prefix);
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

        $smarty->assign($module_name . 'msg', $html_resutlt);

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
    case 'addavatar':

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

        if($status != 'error') {
            include_once(dirname(__FILE__) . '/classes/userprofileg.class.php');
            $obj = new userprofileg();

            $show_my_profile = Tools::getValue('show_my_profile');
            $id_customer = isset($cookie->id_customer)?$cookie->id_customer:0;


            $obj->saveImageAvatar(array('show_my_profile' => $show_my_profile,'id_customer'=>$id_customer));

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
if($action == "add")
	$response->params = array('content' => $html,'paging' => $paging, 
							  'count_reviews'=>$count_reviews,
							  'error_type' => $error_type, 'text_reviews' => $text_reviews,
							   'voucher_html' => $voucher_html, 'voucher_html_suggestion' => $voucher_html_suggestion
							  );
elseif($action == "nav" || $action == "navall" || $action == "navallmy")
    $response->params = array('content' => $html, 'paging' => $paging );
elseif($action == "abuse" || $action == "facebook")
    $response->params = array('content' => $html );
elseif($action == "post-abuse")
    $response->params = array('content' => $html,'error_type' => $error_type, );
elseif($action == "helpfull")
    $response->params = array('content' => $html,'error_type' => $error_type,'yes'=> $count_yes,'all'=>$count_all );
elseif($action == "reminder-send") {
    $response->params = array('content' => $content,'is_error' => $is_error);
} else {
    $response->params = array('content' => $content);
}


echo Tools::jsonEncode($response);


