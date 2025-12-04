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

ob_start();
class AdminReviewsold extends AdminTab{

	
	public function __construct()

	{
		if(version_compare(_PS_VERSION_, '1.5', '>')){
			$this->multishop_context = Shop::CONTEXT_ALL;
		}


            $this->tab = 'AdminReviewold';

            $this->module = 'gsnipreview';


		parent::__construct();
		
	}

    public function addJS(){

     }

    public function addCss(){

	}
	
	public function display()
	{

		echo '<style type="text/css">.warn{display:none!important}
									 #maintab20{display:none!important}
		</style>';

        if (version_compare(_PS_VERSION_, '1.6', '<')){
            require_once(_PS_MODULE_DIR_.$this->module.'/backward_compatibility/backward.php');
            $variables14 = variables_gsnipreview14();
            $currentIndex = $variables14['currentindex'];
        } else {
            $currentIndex = AdminController::$currentIndex;
        }

		// include main class
		require_once(dirname(__FILE__) .  '/gsnipreview.php');
		
		$tab = 'AdminReviewsold';
		$token = $this->token;

		// instantiate
		$obj_main = new gsnipreview();

        require_once(_PS_MODULE_DIR_.'gsnipreview/classes/gsnipreviewhelp.class.php');

        $gsnipreviewhelp_obj = new gsnipreviewhelp();
		
		
		if(Tools::isSubmit('submit_item')){

			
	    	$action = Tools::getValue('submit_item');
	    	$id_review = (int)Tools::getValue('id');
	    	
	    	switch($action){
	    		case 'delete':
                    $gsnipreviewhelp_obj->delete(array('id'=>$id_review));
	    		break;
	    	}
	    	$page = Tools::getValue("page");
	    	Tools::redirectAdmin($currentIndex.'&tab='.$tab.'&configure='.$this->module.'&token='.$token.'&page='.$page);
	    }
	    
   		if (Tools::isSubmit('cancel_item'))
        {
        	$page = Tools::getValue("page");
        	Tools::redirectAdmin($currentIndex.'&tab='.$tab.'&configure='.$this->module.'&token='.$token.'&page='.$page);
		}

        $errors  = array();



    	if (Tools::isSubmit('update_item'))
        {


            $id = Tools::getValue('id');
            ## update item ##


            $name = Tools::getValue("customer_name");
            $email = Tools::getValue("email");
            $title_review = Tools::getValue("title_review");
            $text_review = Tools::getValue("text_review");
            $is_active = (int)Tools::getValue("is_active");
            $time_add = Tools::getValue("time_add");
            $id_lang = Tools::getValue("id_lang");


            $post_images = Tools::getValue("post_images");
            $id_customer = Tools::getValue("id_customer");

            $admin_response = Tools::getValue("admin_response");
            $is_noti = Tools::getValue("is_noti");
            $is_display_old = Tools::getValue("is_display_old");

            ### ratings ###





            $ratings = array();


            $criterions = $gsnipreviewhelp_obj->getReviewCriteria(array('id_lang' => $id_lang, 'id_shop' => $gsnipreviewhelp_obj->getIdShop()));
            if (sizeof($criterions) > 0) {

                foreach ($criterions as $criterion) {
                    $id_criterion = $criterion['id_gsnipreview_review_criterion'];
                    $rating_criterion = Tools::getValue('rat_rel' . $id_criterion);
                    if ($rating_criterion)
                        $ratings[$id_criterion] = $rating_criterion;
                }

            }

            if (sizeof($ratings) == 0) {
                $ratings[0] = 0;
            }

            $rating_total = (int)Tools::getValue("rat_rel");
            ### ratings ###

            /*if(!$name)
                $errors[] = Tools::displayError('Please fill the Customer Name');*/

            /*if(!$email)
                $errors[] = Tools::displayError('Please fill the Email Name');*/

            if(!$text_review && Configuration::get($this->module.'text_on'))
                $errors[] = Tools::displayError('Please fill the Text');

            if (!$title_review && Configuration::get($this->module.'title_on'))
                $errors[] = Tools::displayError('Please fill the Title');

            if(!$time_add)
                $errors[] = Tools::displayError('Please select Date Add');


            if (count($errors)==0) {
                $data = array('name' => $name,
                    'email' => $email,
                    'title_review' => $title_review,
                    'text_review' => $text_review,
                    'is_active' => $is_active,
                    'time_add' => $time_add,
                    'id' => $id,
                    'ratings' => $ratings,
                    'rating_total' => $rating_total,

                    'is_changed'=>0,

                    'post_images' => $post_images,
                    'id_customer' => $id_customer,

                    'admin_response'=>$admin_response,
                    'is_noti'=>$is_noti,
                    'is_display_old'=>$is_display_old,

                );

                $gsnipreviewhelp_obj->updateReview($data);
                $page = Tools::getValue("page");
                Tools::redirectAdmin($currentIndex.'&tab='.$tab.'&configure='.$this->module.'&token='.$token.'&page='.$page);
            }

            ## update item ##


		}

        if (Tools::isSubmit('add_item'))
        {
            ## add item ##
            $title_review = Tools::getValue("title_review");
            $text_review = Tools::getValue("text_review");
            $is_active = (int)Tools::getValue("is_active");
            $time_add = Tools::getValue("time_add");
            $id_lang = Tools::getValue("ids_lang");
            $id_shop = Tools::getValue("ids_shop");
            $id_product = Tools::getValue('inputAccessories');
            $id_customer = Tools::getValue('inputCustomers');


            if (!$id_product)
                $errors[] = Tools::displayError('Please select product');

            if (!$id_customer)
                $errors[] = Tools::displayError('Please select Customer');

            if(!$text_review)
                $errors[] = Tools::displayError('Please fill the Text');

            if (!$title_review)
                $errors[] = Tools::displayError('Please fill the Title');

            if(!$time_add)
                $errors[] = Tools::displayError('Please select Date Add');

            ### ratings ###
            $ratings = array();


            $criterions = $gsnipreviewhelp_obj->getReviewCriteria(array('id_lang' => $id_lang, 'id_shop' => $gsnipreviewhelp_obj->getIdShop()));
            if (sizeof($criterions) > 0) {

                foreach ($criterions as $criterion) {
                    $id_criterion = $criterion['id_gsnipreview_review_criterion'];
                    $rating_criterion = Tools::getValue('rat_rel' . $id_criterion);
                    if ($rating_criterion)
                        $ratings[$id_criterion] = $rating_criterion;
                }

            } else {
                $ratings[0] = Tools::getValue("rat_rel");
            }




            if (sizeof($ratings) == 0) {
                $errors[] = Tools::displayError('Please select Rating');
            }

            ### ratings ###

            if (sizeof($errors)==0) {



                $data = array(
                    'id_customer' => $id_customer,
                    'title' => $title_review,
                    'text_review' => $text_review,
                    'is_active' => $is_active,
                    'time_add' => $time_add,
                    'id_product' => $id_product,
                    'ratings' => $ratings,
                    'id_shop' => $id_shop,
                    'id_lang' => $id_lang,

                );

                //echo "<pre>"; var_dump($data);exit;


                $gsnipreviewhelp_obj->saveReviewAdmin($data);
                $page = Tools::getValue("page");
                Tools::redirectAdmin($currentIndex.'&tab='.$tab.'&configure='.$this->module.'&token='.$token.'&page='.$page);
            }
            ## add item ##
        }
		
		echo $obj_main->_headercssfiles();
		
		echo $obj_main->_moderateReviews(array('currentindex'=>$currentIndex,'controller'=>$tab, 'errors'=>$errors));

		
	}
	
	
	
}





?>

