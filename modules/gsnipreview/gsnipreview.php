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



class gsnipreview extends Module

{

	private $_is15;

	private $_id_shop;

	private $_hooks_avaiable = array(1 => 'rightColumn',  //ok

									 2 => 'leftColumn', // ok

									 5 => 'extraRight', // ok

									 6 => 'productfooter', // ok

									 7 => 'footer', //ok

									 8 => 'productActions', // ok

									 9 => 'extraLeft' // ok

									 );

	private $_is16;

    private $_translate;

    private $_is_cloud;

    private $path_img_cloud;

    private $_is_rtl;

    public $is_demo = 0;



    private $_prefix_review = "r";

    private $_prefix_shop_reviews = 'ti';



    ## store reviews ##

    private $_is_mobile = 0;

    private $_t_width = 245;

    private $_step = 10;

    ## store reviews ##





    private $_is_bug_product_page = 0;

	

	public function __construct()	

 	{

 	 	$this->name = 'gsnipreview';

 	 	$this->version = '1.5.7';

 	 	$this->tab = 'seo';

 	 	$this->author = 'mitrocops';

		$this->module_key = '2c5a18c101a079b2f6f10139647af806';

		$this->confirmUninstall = $this->l('Are you sure you want to remove it ? Your will no longer work. Be careful, all your configuration and your data will be lost');



        //$this->ps_versions_compliancy = array('min' => '1.4', 'max' => _PS_VERSION_);





		require_once(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');





        $this->_is_rtl = 0;



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $this->bootstrap = true;

            $this->need_instance = 0;

            if(Context::getContext()->language->is_rtl){

                $this->_is_rtl = 1;

            }

        }



		if(version_compare(_PS_VERSION_, '1.5', '>')){

			$this->_id_shop = Context::getContext()->shop->id;

			$this->_is15 = 1;

		} else {

			$this->_is15 = 0;

			$this->_id_shop = 0;

		}

		

 		if(version_compare(_PS_VERSION_, '1.6', '>')){

 	 		$this->_is16 = 1;

 	 	} else {

 	 		$this->_is16 = 0;

 	 	}



        ### store reviews ##

        if(version_compare(_PS_VERSION_, '1.5', '>') && version_compare(_PS_VERSION_, '1.7', '<')){



            require_once(_PS_TOOL_DIR_.'mobile_Detect/Mobile_Detect.php');

            $mobile_detect = new Mobile_Detect();



            //echo "<pre>"; var_dump($this->mobile_detect);exit;

            if ($mobile_detect->isMobile()){

                $this->_is_mobile = 1;

            }



        }



        if(version_compare(_PS_VERSION_, '1.7', '>')) {

            $mobile_detect = new \Mobile_Detect();

            if ($mobile_detect->isMobile()) {

                $this->_is_mobile = 1;

            }

        }



        //$this->_is_mobile = 1;



        if($this->_is_mobile){

            $this->_t_width = 205;

        }

        ### store reviews ##





        if (defined('_PS_HOST_MODE_'))

            $this->_is_cloud = 1;

        else

            $this->_is_cloud = 0;





        // for test

        //$this->_is_cloud = 1;

        // for test







        if($this->_is_cloud){

            $this->path_img_cloud = "modules/".$this->name."/upload/";

        } else {

            $this->path_img_cloud = "upload/".$this->name."/";



        }





        $this->_translate = array(

            'name'=>$this->name,

            'title'=>$this->l('Social networks Integration'),

            'title_pstwitterpost'=>$this->l('Facebook Wall Posts + Twitter Cards (2 in 1)'),

            'title_psvkpost'=>$this->l('Vkontakte Wall Post'),



            'buy_module_pstwitterpost'=>$this->l('This feature requires you to have purchased, installed and correctly configured our Facebook Wall Posts + Twitter Cards (2 in 1) module.')

                .' '

                .$this->l('You may purchase it on:')

                .' <a href="http://addons.prestashop.com/product.php?id_product=17676" target="_blank" style="font-weight:bold;text-decoration:underline">'

                .$this->l('PrestaShop Addons').'</a>. '

                .$this->l('Once this is all set, you will see the configuration options instead of this red text.'),



            'buy_module_psvkpost'=>$this->l('This feature requires you to have purchased, installed and correctly configured our Vkontakte Wall Post module.')

                .' '

                .$this->l('You may purchase it on:')

                .' <a href="http://addons.prestashop.com/product.php?id_product=17731" target="_blank" style="font-weight:bold;text-decoration:underline">'

                .$this->l('PrestaShop Addons').'</a>. '

                .$this->l('Once this is all set, you will see the configuration options instead of this red text.'),



            'hint1'=>$this->l('This section lets you integrate with our')

                .' <b><a href="http://addons.prestashop.com/product.php?id_product=17676" target="_blank" style="font-weight:bold;text-decoration:underline">'

                .$this->l('Twitter Cards + Facebook Wall Post module')

                .'</a></b> '.$this->l('and')

                .' <b><a href="http://addons.prestashop.com/product.php?id_product=17731" target="_blank" style="font-weight:bold;text-decoration:underline">'

                .$this->l('Vkontakte Wall Post module')

                .'</a></b> '

                .$this->l(', and allows you to have any ratings and comments posted on a product on your PrestaShop website to be also automatically posted to your')

                .' '

                .$this->l(' Facebook, Twitter, Vkontakte fan pages').'.'.' ',



            'hint2'=>$this->l('If you have enabled Require Admin Approval in the "Product Reviews Advanced" tab, it will only be posted once you approve the rating and comment in the moderation interface.'),



            'update_button'=>$this->l('Update settings'),

            'form_action'=>Tools::safeOutput($_SERVER['REQUEST_URI']),



            'enable_psvkpost'=> $this->l('Enable Vkontakte Wall Post'),



            'enable_pstwitterpost'=> $this->l('Enable Facebook Wall Posts + Twitter Cards (2 in 1)'),



            'template_text'=>$this->l('Post template text'),



        );

		

		parent::__construct();

		$this->page = basename(__FILE__, '.php');

		$this->displayName = $this->l('Product, Shop Reviews, Reminder, Profile, Rich Snippets');

		$this->description = $this->l('Product, Shop Reviews, Reminder, Profile, Rich Snippets');

		

		$this->initContext();



        ## prestashop 1.7 ##

        if(version_compare(_PS_VERSION_, '1.7', '>')) {

            require_once(_PS_MODULE_DIR_.$this->name.'/classes/ps17helpgsnipreview.class.php');

            $ps17help = new ps17helpgsnipreview();

            $ps17help->setMissedVariables();

        } else {

            $smarty = $this->context->smarty;

            $smarty->assign($this->name.'is17' , 0);

        }

        ## prestashop 1.7 ##



	}



    public function getokencron(){

        $_token_cron_shop = sha1(_COOKIE_KEY_ . $this->name);

        return $_token_cron_shop;

    }



    public function getPrefixProductReviews(){

        return $this->_prefix_review;

    }



    public function getPrefixShopReviews(){

        return $this->_prefix_shop_reviews;

    }



    public function getURLMultiShop(){

        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_http_host = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__;

        } else {

            $_http_host = _PS_BASE_URL_SSL_.__PS_BASE_URI__;

        }

        if(version_compare(_PS_VERSION_, '1.5', '>')) {

            $current_shop_id = Shop::getContextShopID();





            if($current_shop_id) {



                $is_ssl = false;

                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')

                    $is_ssl = true;



                $shop_obj = new Shop($current_shop_id);



                $_http_host = $shop_obj->getBaseURL($is_ssl);



            }



        }

        return $_http_host;

    }

 	

	private function initContext()

	{

	  $this->context = Context::getContext();

	  if (version_compare(_PS_VERSION_, '1.5', '>')){

	 	  $this->context->currentindex = isset(AdminController::$currentIndex)?AdminController::$currentIndex:'index.php?controller=AdminModules';

	 	 $this->context->done = isset($this->done)?$this->done:null;

	  } else {

	  	  $variables14 = variables_gsnipreview14();

          $this->context->currentindex = $variables14['currentindex'];

          $this->context->done = $variables14['done'];

	  }

	}

	

 	public function install()

	{



        ### store reviews ####

        Configuration::updateValue($this->name.'is_storerev', 1);



        Configuration::updateValue($this->name.'crondelay'.$this->_prefix_shop_reviews, 10);

        Configuration::updateValue($this->name.'cronnpost'.$this->_prefix_shop_reviews, 20);



        Configuration::updateValue($this->name.'t_lefts', 1);

        Configuration::updateValue($this->name.'t_rights', 1);

        Configuration::updateValue($this->name.'t_footers', 1);

        Configuration::updateValue($this->name.'t_homes', 1);

        Configuration::updateValue($this->name.'t_leftsides', 1);

        Configuration::updateValue($this->name.'t_rightsides', 1);

        Configuration::updateValue($this->name.'t_tpages', 1);





        Configuration::updateValue($this->name.'mt_left', 1);

        Configuration::updateValue($this->name.'mt_right', 1);

        Configuration::updateValue($this->name.'mt_footer', 1);

        Configuration::updateValue($this->name.'mt_home', 1);

        Configuration::updateValue($this->name.'mt_leftside', 1);

        Configuration::updateValue($this->name.'mt_rightside', 1);



        Configuration::updateValue($this->name.'st_left', 1);

        Configuration::updateValue($this->name.'st_right', 1);

        Configuration::updateValue($this->name.'st_footer', 1);

        Configuration::updateValue($this->name.'st_home', 1);

        Configuration::updateValue($this->name.'st_leftside', 1);

        Configuration::updateValue($this->name.'st_rightside', 1);





        ### reminder ###

        Configuration::updateValue($this->name.'delaysec'.$this->_prefix_shop_reviews, 7);

        Configuration::updateValue($this->name.'remindersec'.$this->_prefix_shop_reviews, 0);





        Configuration::updateValue($this->name.'reminder'.$this->_prefix_shop_reviews, 1);

        $languages = Language::getLanguages(false);

        foreach ($languages as $language){

            $i = $language['id_lang'];

            Configuration::updateValue($this->name.'emrem'.$this->_prefix_shop_reviews.'_'.$i, $this->l('Are you satisfied with our products'));

            Configuration::updateValue($this->name.'reminderok'.$this->_prefix_shop_reviews.'_'.$i, $this->l('The emails requests on the reviews was successfully sent'));

            Configuration::updateValue($this->name.'thankyou'.$this->_prefix_shop_reviews.'_'.$i, $this->l('Thank you for your review'));

            Configuration::updateValue($this->name.'newtest'.$this->_prefix_shop_reviews.'_'.$i, $this->l('New Store review from Your Customer'));

            Configuration::updateValue($this->name.'resptest'.$this->_prefix_shop_reviews.'_'.$i, $this->l('Response on the Store review'));



        }

        Configuration::updateValue($this->name.'orderstatuses'.$this->_prefix_shop_reviews, implode(",",array(2,5,12)));

        Configuration::updateValue($this->name.'starscat'.$this->_prefix_shop_reviews, 1);

        Configuration::updateValue($this->name.'delay'.$this->_prefix_shop_reviews, 7);





        ### reminder ###



        Configuration::updateValue($this->name.'whocanadd'.$this->_prefix_shop_reviews, 'all');









        Configuration::updateValue($this->name.'tlast', 3);

        if($this->_is16 == 1){

            Configuration::updateValue($this->name.'t_home', 1);

            Configuration::updateValue($this->name.'t_footer', 1);

            Configuration::updateValue($this->name.'BGCOLOR_T', '#fafafa');



        }	else {

            Configuration::updateValue($this->name.'BGCOLOR_T', '#f6dce8');

        }

        Configuration::updateValue($this->name.'BGCOLOR_TIT', '#c45500');

        Configuration::updateValue($this->name.'t_left', 1);



        Configuration::updateValue($this->name.'t_rightside', 1);



        Configuration::updateValue($this->name.'perpage'.$this->_prefix_shop_reviews, 5);





        Configuration::updateValue($this->name.'is_avatar', 1);

        Configuration::updateValue($this->name.'is_captcha'.$this->_prefix_shop_reviews, 1);

        Configuration::updateValue($this->name.'is_web', 1);

        Configuration::updateValue($this->name.'is_company', 1);

        Configuration::updateValue($this->name.'is_addr', 1);



        Configuration::updateValue($this->name.'is_country', 1);

        Configuration::updateValue($this->name.'is_city', 1);







        Configuration::updateValue($this->name.'noti'.$this->_prefix_shop_reviews, 1);

        Configuration::updateValue($this->name.'mail'.$this->_prefix_shop_reviews, @Configuration::get('PS_SHOP_EMAIL'));



        Configuration::updateValue($this->name.'n_rssitemst', 10);

        Configuration::updateValue($this->name.'rssontestim', 1);





        if(version_compare(_PS_VERSION_, '1.6', '<')) {

            $this->generateRewriteRules();

            $this->generateRewriteRulesUser();

        }



        if($this->_is15 == 1)

            $this->createAdminTabsStoreReviews();

        else

            $this->createAdminTabsStoreReviews14();

        ### store reviews ####













        Configuration::updateValue($this->name.'crondelay'.$this->_prefix_review, 10);

        Configuration::updateValue($this->name.'cronnpost'.$this->_prefix_review, 20);



        ## subjects ##

        $languages = Language::getLanguages(false);

        foreach ($languages as $language){

            $i = $language['id_lang'];

            Configuration::updateValue($this->name.'reminderok'.$this->_prefix_review.'_'.$i, $this->l('The emails requests on the reviews was successfully sent'));

            Configuration::updateValue($this->name.'thankyou'.$this->_prefix_review.'_'.$i, $this->l('Thank you for your review'));



            Configuration::updateValue($this->name.'emailreminder_'.$i, $this->l('Are you satisfied with our products'));

            Configuration::updateValue($this->name.'subpubem_'.$i, $this->l('Your review has been published'));

            Configuration::updateValue($this->name.'subresem_'.$i, $this->l('The shop admin has replied to your product review'));

            Configuration::updateValue($this->name.'textresem_'.$i, $this->l('Thank you for your product review on our website. We always welcome reviews, whether it is positive or negative. However, we would like to have a chance to invite you to change your review. Here is why:'));



            Configuration::updateValue($this->name.'newrev'.$this->_prefix_review.'_'.$i, $this->l('New review'));

            Configuration::updateValue($this->name.'modrev'.$this->_prefix_review.'_'.$i, $this->l('One of your customers has modified own product review'));

            Configuration::updateValue($this->name.'abuserev'.$this->_prefix_review.'_'.$i, $this->l('Someone send abuse for review'));



            Configuration::updateValue($this->name.'facvouc'.$this->_prefix_review.'_'.$i, $this->l('You share review on Facebook and get voucher for discount'));

            Configuration::updateValue($this->name.'revvouc'.$this->_prefix_review.'_'.$i, $this->l('You submit a review and get voucher for discount'));



            Configuration::updateValue($this->name.'sugvouc'.$this->_prefix_review.'_'.$i, $this->l('Share your review on Facebook and get voucher for discount'));





        }

        ## subjects ##



        ## user profile ###

        Configuration::updateValue($this->name.'is_uprof', 1);



        if($this->_is16 == 1){

            Configuration::updateValue($this->name.'radv_home', 1);

            Configuration::updateValue($this->name.'radv_footer', 1);



        }

        Configuration::updateValue($this->name.'radv_left', 1);





        Configuration::updateValue($this->name.'rshoppers_blc', 5);

        Configuration::updateValue($this->name.'rpage_shoppers', 16);

        ## user profile ###



        Configuration::updateValue($this->name.'is_avatar'.$this->_prefix_review, 1);

        Configuration::updateValue($this->name.'is_files'.$this->_prefix_review, 1);

        Configuration::updateValue($this->name.'ruploadfiles', 7);

        Configuration::updateValue($this->name.'rminc', 20);





        Configuration::updateValue($this->name.'is_onerev', 1);





		

		#### posts api ###

		$languages = Language::getLanguages(false);

    	foreach ($languages as $language){

    		$i = $language['id_lang'];

    		$iso = Tools::strtoupper(Language::getIsoById($i));

    		Configuration::updateValue($this->name.'twdesc_'.$i, $this->l('rated product in our shop').' '.$iso);

            Configuration::updateValue($this->name.'vkdesc_'.$i, $this->l('rated product in our shop').' '.$iso);

		}

		

		#### posts api ###

		

		

		// pinterest

		Configuration::updateValue($this->name.'pinvis_on', 1);

		Configuration::updateValue($this->name.'pinterestbuttons', 'threeon');

        if(version_compare(_PS_VERSION_, '1.7', '>')) {

            Configuration::updateValue($this->name . '_productActions', 'productActions');

        } else {

            Configuration::updateValue($this->name . '_extraLeft', 'extraLeft');

        }

		// pinterest

		

		// google rich snippets

		Configuration::updateValue($this->name.'svis_on', 1);

        Configuration::updateValue($this->name.'allinfo_on', 1);





        Configuration::updateValue($this->name.'allinfo_home', 'allinfo_home');

        Configuration::updateValue($this->name.'allinfo_cat', 'allinfo_cat');

        Configuration::updateValue($this->name.'allinfo_man', 'allinfo_man');



        Configuration::updateValue($this->name.'allinfo_home_w', 100);

        Configuration::updateValue($this->name.'allinfo_cat_w', 100);

        Configuration::updateValue($this->name.'allinfo_man_w', 100);



        Configuration::updateValue($this->name.'allinfo_home_pos', 'top');

        Configuration::updateValue($this->name.'allinfo_cat_pos', 'top');

        Configuration::updateValue($this->name.'allinfo_man_pos', 'top');









		

		if(version_compare(_PS_VERSION_, '1.6', '<')){

		Configuration::updateValue($this->name.'gsnipblock', 1);

	 	Configuration::updateValue($this->name.'gsnipblock_width', 'auto');

	 	Configuration::updateValue($this->name.'gsnipblocklogo', 1);

		if($this->_is16==1)

	 		Configuration::updateValue($this->name.'id_hook_gsnipblock', 5);

		else

	 		Configuration::updateValue($this->name.'id_hook_gsnipblock', 1);

	 		

		}

		

		// google rich snippets	





        Configuration::updateValue($this->name.'reminder', 1);

        Configuration::updateValue($this->name.'delaysec'.$this->_prefix_review, 7);

        Configuration::updateValue($this->name.'remindersec'.$this->_prefix_review, 0);



		

		Configuration::updateValue($this->name.'delay', 7);

        // orderstatuses

        Configuration::updateValue($this->name.'orderstatuses', implode(",",array(2,5,12)));

        // orderstatuses



        Configuration::updateValue($this->name.'breadvis_on', 1);

		



		

		// product reviews advanced 

		Configuration::updateValue($this->name.'rvis_on', 1);

        Configuration::updateValue($this->name.'ratings_on', 1);

		Configuration::updateValue($this->name.'text_on', 1);

		Configuration::updateValue($this->name.'title_on', 1);

		Configuration::updateValue($this->name.'ip_on', 1);

		Configuration::updateValue($this->name.'is_captcha', 1);



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            Configuration::updateValue($this->name.'ptabs_type', 1);

        } else {

            Configuration::updateValue($this->name.'ptabs_type', 2);

        }



        Configuration::updateValue($this->name.'is_abusef', 1);

        Configuration::updateValue($this->name.'is_helpfulf', 1);



        Configuration::updateValue($this->name.'rsoc_on', 1);

        Configuration::updateValue($this->name.'rsoccount_on', 1);



        Configuration::updateValue($this->name.'revperpagecus', 5);



        Configuration::updateValue($this->name.'is_blocklr', 1);



        Configuration::updateValue($this->name.'blocklr_home_pos', 'home');

        Configuration::updateValue($this->name.'blocklr_cat_pos', 'leftcol');

        Configuration::updateValue($this->name.'blocklr_man_pos', 'leftcol');

        Configuration::updateValue($this->name.'blocklr_prod_pos', 'leftcol');

        Configuration::updateValue($this->name.'blocklr_oth_pos', 'leftcol');

        Configuration::updateValue($this->name.'blocklr_chook_pos', 'chook');



        Configuration::updateValue($this->name.'blocklr_home_w', 100);

        Configuration::updateValue($this->name.'blocklr_cat_w', 100);

        Configuration::updateValue($this->name.'blocklr_man_w', 100);

        Configuration::updateValue($this->name.'blocklr_prod_w', 100);

        Configuration::updateValue($this->name.'blocklr_oth_w', 100);

        Configuration::updateValue($this->name.'blocklr_chook_w', 100);





        Configuration::updateValue($this->name.'blocklr_home', 'blocklr_home');

        Configuration::updateValue($this->name.'blocklr_cat', 'blocklr_cat');

        Configuration::updateValue($this->name.'blocklr_man', 'blocklr_man');

        Configuration::updateValue($this->name.'blocklr_prod', 'blocklr_prod');

        Configuration::updateValue($this->name.'blocklr_oth', 'blocklr_oth');

        Configuration::updateValue($this->name.'blocklr_chook', 'blocklr_chook');



        Configuration::updateValue($this->name.'blocklr_home_ndr', 3);

        Configuration::updateValue($this->name.'blocklr_cat_ndr', 3);

        Configuration::updateValue($this->name.'blocklr_man_ndr', 3);

        Configuration::updateValue($this->name.'blocklr_prod_ndr', 3);

        Configuration::updateValue($this->name.'blocklr_oth_ndr', 3);

        Configuration::updateValue($this->name.'blocklr_chook_ndr', 3);



        Configuration::updateValue($this->name.'blocklr_home_tr', 250);

        Configuration::updateValue($this->name.'blocklr_cat_tr', 75);

        Configuration::updateValue($this->name.'blocklr_man_tr', 75);

        Configuration::updateValue($this->name.'blocklr_prod_tr', 75);

        Configuration::updateValue($this->name.'blocklr_oth_tr', 75);

        Configuration::updateValue($this->name.'blocklr_chook_tr', 75);



        $img_default = "small"."_"."default";

        if(version_compare(_PS_VERSION_, '1.5', '>')) {

            Configuration::updateValue($this->name . 'blocklr_home_im', $img_default);

            Configuration::updateValue($this->name . 'blocklr_cat_im', $img_default);

            Configuration::updateValue($this->name . 'blocklr_man_im', $img_default);

            Configuration::updateValue($this->name . 'blocklr_prod_im', $img_default);

            Configuration::updateValue($this->name . 'blocklr_oth_im', $img_default);

            Configuration::updateValue($this->name . 'blocklr_chook_im', $img_default);

        } else {

            Configuration::updateValue($this->name . 'blocklr_home_im', 'medium');

            Configuration::updateValue($this->name . 'blocklr_cat_im', 'small');

            Configuration::updateValue($this->name . 'blocklr_man_im', 'small');

            Configuration::updateValue($this->name . 'blocklr_prod_im', 'small');

            Configuration::updateValue($this->name . 'blocklr_oth_im', 'small');

            Configuration::updateValue($this->name . 'blocklr_chook_im', 'small');

        }





        Configuration::updateValue($this->name.'img_size_em', $img_default);





        Configuration::updateValue($this->name.'rswitch_lng', 0);





		Configuration::updateValue($this->name.'revperpage', 5);

		Configuration::updateValue($this->name.'revperpageall', 10);

		Configuration::updateValue($this->name.'adminrevperpage', 10);

		Configuration::updateValue($this->name.'whocanadd', 'all');

		Configuration::updateValue($this->name.'is_approval', 0);

		Configuration::updateValue($this->name.'position', 'left');

		//Configuration::updateValue($this->name.'homeon', 1);

        if(version_compare(_PS_VERSION_, '1.7', '>')) {

            Configuration::updateValue($this->name . 'hooktodisplay', 'product_actions');

        } else {

            Configuration::updateValue($this->name . 'hooktodisplay', 'extra_right');

        }

		Configuration::updateValue($this->name.'stylestars', 'style1'); // yellow



        Configuration::updateValue($this->name.'starratingon', 1);

		Configuration::updateValue($this->name.'noti', 1);	

		Configuration::updateValue($this->name.'mail', @Configuration::get('PS_SHOP_EMAIL'));

		Configuration::updateValue($this->name.'lastrevitems', 5);

		//Configuration::updateValue($this->name.'hlastrevitems', 5);

		

		Configuration::updateValue($this->name.'starscat', 1);

		// product reviews advanced

		

		// voucher settings



        Configuration::updateValue($this->name.'vis_on', 1);



        Configuration::updateValue($this->name.'is_show_min', 1);





        $languages = Language::getLanguages(false);

        foreach ($languages as $language){

            $i = $language['id_lang'];

            $iso = Tools::strtoupper(Language::getIsoById($i));



            $coupondesc = $this->displayName;

            Configuration::updateValue($this->name.'coupondesc_'.$i, $coupondesc.' '.$iso);

        }



        Configuration::updateValue($this->name.'vouchercode', "PRG");

        Configuration::updateValue($this->name.'discount_type', 2);

        Configuration::updateValue($this->name.'percentage_val', 1);



        Configuration::updateValue($this->name.'tax', 1);





        if($this->_is16)

            $cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

        else

            $cur = Currency::getCurrencies();

        foreach ($cur AS $_cur){

            if(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency']){

                Configuration::updateValue('sdamount_'.(int)$_cur['id_currency'], 1);

            }

        }

        Configuration::updateValue($this->name.'sdvvalid', 365);



        // cumulable

        Configuration::updateValue($this->name.'cumulativeother', 0);

        Configuration::updateValue($this->name.'cumulativereduc', 0);

        // cumulable



        Configuration::updateValue($this->name.'highlight', 1);

        // categories

        Configuration::updateValue($this->name.'catbox', $this->getIdsCategories());

        // categories

        // voucher settings





        // voucher facebook settings



        $this->installVoucherShareReviewSettings();



        // voucher facebook settings

		

		

		Configuration::updateValue($this->name.'rsson', 1);

		Configuration::updateValue($this->name.'number_rssitems', 10);

		

		

		

		$languages = Language::getLanguages(false);

    	foreach ($languages as $language){

    		$i = $language['id_lang'];



    		$rssname = Configuration::get('PS_SHOP_NAME');

    		Configuration::updateValue($this->name.'rssname_'.$i, $rssname);

			$rssdesc = Configuration::get('PS_SHOP_NAME');

			Configuration::updateValue($this->name.'rssdesc_'.$i, $rssdesc);

		}

		

		if($this->_is15 == 1)

	 		$this->createAdminTabs15();

	 	else

	 		$this->createAdminTabs14();

		

		if (!parent::install())

	 		return false;

	 	

	 	if (!$this->installTable() OR

            !$this->installCriteriaTable() OR

            !$this->installReviewCriteria() OR

            !$this->installReviewAbuse() OR

            !$this->installReviewHelpfull() OR

            !$this->installSocialShare() OR

            !$this->installReminder2CustomerTable() OR



            !$this->installUserTable() OR

            !$this->installFiles2ReviewTable() OR



            !$this->createShopReviewTable() OR

            !$this->createReminderShopReviewsTable() OR



            !$this->registerHook('leftColumn') OR

			!$this->registerHook('rightColumn') OR

			!$this->registerHook('header') OR 

			!$this->registerHook('ProductTab') OR

			!$this->registerHook('productTabContent') OR

			!$this->registerHook('footer') OR

			!$this->registerHook('extraLeft') OR

		 	!$this->registerHook('extraRight') OR

		 	!$this->registerHook('productfooter') OR

		 	!$this->registerHook('productActions') OR 

		 	!$this->registerHook('home') OR

            !$this->registerHook('top') OR

		 	!$this->registerHook('customerAccount') OR

	 		!$this->registerHook('myAccountBlock') OR

	 		!$this->registerHook('OrderConfirmation') OR

            !((version_compare(_PS_VERSION_, '1.6', '>'))? $this->registerHook('displayProductListReviews') : true) OR

            !((version_compare(_PS_VERSION_, '1.5', '>'))? $this->registerHook('lastReviewsMitrocops') : true) OR

	 		!((version_compare(_PS_VERSION_, '1.5.0', '>'))? $this->registerHook('actionValidateOrder') : $this->registerHook('newOrder')) OR

	 		!($this->_is_cloud? true : $this->createFolderAndSetPermissions()) OR



            !($this->_is_cloud? true : $this->createFolderAndSetPermissionsAvatar()) OR

            !($this->_is_cloud? true : $this->createFolderAndSetPermissionsFiles()) OR





	 		!((version_compare(_PS_VERSION_, '1.6', '>'))? $this->registerHook('DisplayBackOfficeHeader') : true)





        	)

			return false;

	 	

	 	return true;

	}



    public function getPrefixReviews(){

        return $this->_prefix_review;

    }







    public function generateRewriteRules(){



        if(Configuration::get('PS_REWRITING_SETTINGS')){



            $rules = "#storereviews - not remove this comment \n";



            $physical_uri = array();



            if($this->_is15){

                foreach (ShopUrl::getShopUrls() as $shop_url)

                {

                    if(in_array($shop_url->physical_uri,$physical_uri)) continue;



                    $rules .= "RewriteRule ^(.*)storereviews$ ".$shop_url->physical_uri."modules/".$this->name."/storereviews-form.php [QSA,L] \n";

                    $rules .= "RewriteRule ^(.*)mystorereview$ ".$shop_url->physical_uri."modules/".$this->name."/my-storereviews.php [QSA,L] \n";





                    $physical_uri[] = $shop_url->physical_uri;

                }

            } else{

                $rules .= "RewriteRule ^(.*)storereviews$ /modules/".$this->name."/storereviews-form.php [QSA,L] \n";

                $rules .= "RewriteRule ^(.*)mystorereview$ /modules/".$this->name."/my-storereviews.php [QSA,L] \n";



            }

            $rules .= "#storereviews \n\n";



            $path = _PS_ROOT_DIR_.'/.htaccess';



            if(is_writable($path)){



                $existingRules = file_get_contents_custom_gsnipreview($path);



                if(!strpos($existingRules, "storereviews")){

                    $handle = fopen($path, 'w');

                    fwrite($handle, $rules.$existingRules);

                    fclose($handle);

                }

            }

        }

    }



    public function generateRewriteRulesUser(){



        if(Configuration::get('PS_REWRITING_SETTINGS')){



            $rules = "#users - not remove this comment \n";



            $physical_uri = array();



            if($this->_is15){

                foreach (ShopUrl::getShopUrls() as $shop_url)

                {

                    if(in_array($shop_url->physical_uri,$physical_uri)) continue;



                    $rules .= "RewriteRule ^(.*)users$ ".$shop_url->physical_uri."modules/".$this->name."/users.php [QSA,L] \n";

                    $rules .= "RewriteRule ^(.*)user/([0-9]+)$ ".$shop_url->physical_uri."modules/".$this->name."/user.php?uid=$2 [QSA,L] \n";

                    $rules .= "RewriteRule ^(.*)useraccount$ ".$shop_url->physical_uri."modules/".$this->name."/useraccount.php [QSA,L] \n";



                    $physical_uri[] = $shop_url->physical_uri;

                }

            } else{

                $rules .= "RewriteRule ^(.*)users$ /modules/".$this->name."/users.php [QSA,L] \n";

                $rules .= "RewriteRule ^(.*)user/([0-9]+)$ /modules/".$this->name."/user.php?uid=$2 [QSA,L] \n";

                $rules .= "RewriteRule ^(.*)useraccount$ /modules/".$this->name."/useraccount.php [QSA,L] \n";



            }

            $rules .= "#users \n\n";



            $path = _PS_ROOT_DIR_.'/.htaccess';



            if(is_writable($path)){



                $existingRules = file_get_contents_custom_gsnipreview($path);



                if(!strpos($existingRules, "users")){

                    $handle = fopen($path, 'w');

                    fwrite($handle, $rules.$existingRules);

                    fclose($handle);

                }

            }

        }

    }

	

	public function hookDisplayBackOfficeHeader()

	{

	

		if(version_compare(_PS_VERSION_, '1.6', '>')) {

            $base_dir = Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__;

            $css = '';

            $css .= '<style type="text/css">

                    .icon-AdminReview:before, .icon-AdminReview:before {

                    content: url("' . $base_dir . 'modules/' . $this->name . '/AdminReview.gif");

                    }



                    .icon-AdminStorereview:before, .icon-AdminStorereview:before {

						content: url("' . $base_dir . 'modules/' . $this->name . '/AdminStorereviewsold.gif");

					}

                    </style>

                    ';



            return $css;

        }

	}

	

	public function installVoucherShareReviewSettings(){

        // voucher facebook settings



        Configuration::updateValue($this->name.'vis_onfb', 1);



        Configuration::updateValue($this->name.'is_show_minfb', 1);





        $languages = Language::getLanguages(false);

        foreach ($languages as $language){

            $i = $language['id_lang'];

            $iso = Tools::strtoupper(Language::getIsoById($i));



            $coupondesc = $this->displayName;

            Configuration::updateValue($this->name.'coupondescfb_'.$i, $coupondesc.' '.$iso);

        }



        Configuration::updateValue($this->name.'vouchercodefb', "PRF");

        Configuration::updateValue($this->name.'discount_typefb', 2);

        Configuration::updateValue($this->name.'percentage_valfb', 1);



        Configuration::updateValue($this->name.'taxfb', 1);





        if($this->_is16)

            $cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

        else

            $cur = Currency::getCurrencies();

        foreach ($cur AS $_cur){

            if(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency']){

                Configuration::updateValue('sdamountfb_'.(int)$_cur['id_currency'], 1);

            }

        }

        Configuration::updateValue($this->name.'sdvvalidfb', 365);



        // cumulable

        Configuration::updateValue($this->name.'cumulativeotherfb', 0);

        Configuration::updateValue($this->name.'cumulativereducfb', 0);

        // cumulable



        Configuration::updateValue($this->name.'highlightfb', 1);

        // categories

        Configuration::updateValue($this->name.'catboxfb', $this->getIdsCategories());

        // categories

        // voucher facebook settings

    }

	

	

	public function uninstall()

	{

        #### posts api ###

        $languages = Language::getLanguages(false);

        foreach ($languages as $language){

            $i = $language['id_lang'];

            Configuration::deleteByName($this->name.'twdesc_'.$i);

            Configuration::deleteByName($this->name.'vkdesc_'.$i);

        }

        #### posts api ###





        // pinterest

        Configuration::deleteByName($this->name.'pinvis_on');

        Configuration::deleteByName($this->name.'pinterestbuttons');

        Configuration::deleteByName($this->name.'_extraLeft');

        // pinterest



        // google rich snippets

        Configuration::deleteByName($this->name.'svis_on');

        Configuration::deleteByName($this->name.'breadvis_on');

        Configuration::deleteByName($this->name.'allinfo_on');



        Configuration::deleteByName($this->name.'allinfo_home');

        Configuration::deleteByName($this->name.'allinfo_cat');

        Configuration::deleteByName($this->name.'allinfo_man');



        Configuration::deleteByName($this->name.'allinfo_home_w');

        Configuration::deleteByName($this->name.'allinfo_cat_w');

        Configuration::deleteByName($this->name.'allinfo_man_w');



        Configuration::deleteByName($this->name.'allinfo_home_pos');

        Configuration::deleteByName($this->name.'allinfo_cat_pos');

        Configuration::deleteByName($this->name.'allinfo_man_pos');







        if(version_compare(_PS_VERSION_, '1.6', '<')){



            Configuration::deleteByName($this->name.'gsnipblock');

            Configuration::deleteByName($this->name.'gsnipblock_width');

            Configuration::deleteByName($this->name.'gsnipblocklogo');

            Configuration::deleteByName($this->name.'id_hook_gsnipblock');



        }



        // google rich snippets



        $languages = Language::getLanguages(false);

        foreach ($languages as $language){

            $i = $language['id_lang'];

            Configuration::deleteByName($this->name.'emailreminder_'.$i);

        }



        Configuration::deleteByName($this->name.'delay');







        // product reviews advanced

        Configuration::deleteByName($this->name.'rvis_on');

        Configuration::deleteByName($this->name.'ratings_on');

        Configuration::deleteByName($this->name.'text_on');

        Configuration::deleteByName($this->name.'title_on');

        Configuration::deleteByName($this->name.'ip_on');

        Configuration::deleteByName($this->name.'is_captcha');





        Configuration::deleteByName($this->name.'ptabs_type');



        Configuration::deleteByName($this->name.'is_abusef');

        Configuration::deleteByName($this->name.'is_helpfulf');



        Configuration::deleteByName($this->name.'rsoc_on');

        Configuration::deleteByName($this->name.'rsoccount_on');



        Configuration::deleteByName($this->name.'revperpagecus');

        Configuration::deleteByName($this->name.'is_blocklr');



        Configuration::deleteByName($this->name.'blocklr_home_pos');

        Configuration::deleteByName($this->name.'blocklr_cat_pos');

        Configuration::deleteByName($this->name.'blocklr_man_pos');

        Configuration::deleteByName($this->name.'blocklr_prod_pos');

        Configuration::deleteByName($this->name.'blocklr_oth_pos');

        Configuration::deleteByName($this->name.'blocklr_chook_pos');



        Configuration::deleteByName($this->name.'blocklr_home_w');

        Configuration::deleteByName($this->name.'blocklr_cat_w');

        Configuration::deleteByName($this->name.'blocklr_man_w');

        Configuration::deleteByName($this->name.'blocklr_prod_w');

        Configuration::deleteByName($this->name.'blocklr_oth_w');

        Configuration::deleteByName($this->name.'blocklr_chook_w');



        Configuration::deleteByName($this->name.'blocklr_home');

        Configuration::deleteByName($this->name.'blocklr_cat');

        Configuration::deleteByName($this->name.'blocklr_man');

        Configuration::deleteByName($this->name.'blocklr_prod');

        Configuration::deleteByName($this->name.'blocklr_oth');

        Configuration::deleteByName($this->name.'blocklr_chook');



        Configuration::deleteByName($this->name.'blocklr_home_ndr');

        Configuration::deleteByName($this->name.'blocklr_cat_ndr');

        Configuration::deleteByName($this->name.'blocklr_man_ndr');

        Configuration::deleteByName($this->name.'blocklr_prod_ndr');

        Configuration::deleteByName($this->name.'blocklr_oth_ndr');

        Configuration::deleteByName($this->name.'blocklr_chook_ndr');



        Configuration::deleteByName($this->name.'blocklr_home_tr');

        Configuration::deleteByName($this->name.'blocklr_cat_tr');

        Configuration::deleteByName($this->name.'blocklr_man_tr');

        Configuration::deleteByName($this->name.'blocklr_prod_tr');

        Configuration::deleteByName($this->name.'blocklr_oth_tr');

        Configuration::deleteByName($this->name.'blocklr_chook_tr');



        Configuration::deleteByName($this->name.'blocklr_home_im');

        Configuration::deleteByName($this->name.'blocklr_cat_im');

        Configuration::deleteByName($this->name.'blocklr_man_im');

        Configuration::deleteByName($this->name.'blocklr_prod_im');

        Configuration::deleteByName($this->name.'blocklr_oth_im');

        Configuration::deleteByName($this->name.'blocklr_chook_im');





        Configuration::deleteByName($this->name.'img_size_em');

        $languages = Language::getLanguages(false);

        foreach ($languages as $language){

            $i = $language['id_lang'];

            Configuration::deleteByName($this->name.'subpubem_'.$i);

            Configuration::deleteByName($this->name.'subresem_'.$i);

            Configuration::deleteByName($this->name.'textresem_'.$i);



        }



        Configuration::deleteByName($this->name.'rswitch_lng');





        Configuration::deleteByName($this->name.'revperpage');

        Configuration::deleteByName($this->name.'revperpageall');

        Configuration::deleteByName($this->name.'adminrevperpage');

        Configuration::deleteByName($this->name.'whocanadd');

        Configuration::deleteByName($this->name.'is_approval');



        Configuration::deleteByName($this->name.'position');

        //Configuration::deleteByName($this->name.'homeon');

        Configuration::deleteByName($this->name.'hooktodisplay');

        Configuration::deleteByName($this->name.'stylestars');

        Configuration::deleteByName($this->name.'noti');



        Configuration::deleteByName($this->name.'mail');

        Configuration::deleteByName($this->name.'lastrevitems');

        //Configuration::deleteByName($this->name.'hlastrevitems');



        Configuration::deleteByName($this->name.'starscat');

        // product reviews advanced





        // voucher settings



        Configuration::deleteByName($this->name.'vis_on');

        Configuration::deleteByName($this->name.'is_show_min');





        $languages = Language::getLanguages(false);

        foreach ($languages as $language){

            $i = $language['id_lang'];

            Configuration::deleteByName($this->name.'coupondesc_'.$i);

        }



        Configuration::deleteByName($this->name.'vouchercode');

        Configuration::deleteByName($this->name.'discount_type');

        Configuration::deleteByName($this->name.'percentage_val');



        Configuration::deleteByName($this->name.'tax');







        if($this->_is16)

            $cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

        else

            $cur = Currency::getCurrencies();

        foreach ($cur AS $_cur){

            if(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency']){

                Configuration::deleteByName('sdamount_'.(int)$_cur['id_currency']);

            }

        }

        Configuration::deleteByName($this->name.'sdvvalid');

        // cumulable

        Configuration::deleteByName($this->name.'cumulativeother');

        Configuration::deleteByName($this->name.'cumulativereduc');

        // cumulable



        Configuration::deleteByName($this->name.'highlight');

        // categories

        Configuration::deleteByName($this->name.'catbox');

        // categories

        // voucher settings





        // voucher facebook settings



        Configuration::deleteByName($this->name.'vis_onfb');

        Configuration::deleteByName($this->name.'is_show_minfb');





        $languages = Language::getLanguages(false);

        foreach ($languages as $language){

            $i = $language['id_lang'];

            Configuration::deleteByName($this->name.'coupondescfb_'.$i);

        }



        Configuration::deleteByName($this->name.'vouchercodefb');

        Configuration::deleteByName($this->name.'discount_typefb');

        Configuration::deleteByName($this->name.'percentage_vafbl');



        Configuration::deleteByName($this->name.'taxfb');







        if($this->_is16)

            $cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

        else

            $cur = Currency::getCurrencies();

        foreach ($cur AS $_cur){

            if(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency']){

                Configuration::deleteByName('sdamountfb_'.(int)$_cur['id_currency']);

            }

        }

        Configuration::deleteByName($this->name.'sdvvalidfb');

        // cumulable

        Configuration::deleteByName($this->name.'cumulativeotherfb');

        Configuration::deleteByName($this->name.'cumulativereducfb');

        // cumulable



        Configuration::deleteByName($this->name.'highlightfb');

        // categories

        Configuration::deleteByName($this->name.'catboxfb');

        // categories



        // voucher facebook settings





        Configuration::deleteByName($this->name.'rsson');

        Configuration::deleteByName($this->name.'number_rssitems');







        $languages = Language::getLanguages(false);

        foreach ($languages as $language){

            $i = $language['id_lang'];



            Configuration::deleteByName($this->name.'rssname_'.$i);

            Configuration::deleteByName($this->name.'rssdesc_'.$i);

        }



        if($this->_is15 == 1)

			$this->uninstallTab15();

		else

			$this->uninstallTab14();





        if($this->_is15 == 1)

            $this->uninstallTabStoreReviews();

        else

            $this->uninstallTabStoreReviews14();

			

			

		if (!parent::uninstall() 

			|| !$this->uninstallTable()

			)

			return false;

		return true;

	}





    public function uninstallTab15(){
        if(version_compare(_PS_VERSION_, '1.6', '>')) {
            $prefix = '';
        } else {
            $prefix = 'old';
        }

		$tab_id = Tab::getIdFromClassName("AdminReview".$prefix);

        if($tab_id){
			$tab = new Tab($tab_id);

			$tab->delete();
		}

		$tab_id = Tab::getIdFromClassName("AdminReviews".$prefix);

		if($tab_id){
			$tab = new Tab($tab_id);
			$tab->delete();
		}
	
		@unlink(_PS_ROOT_DIR_."/img/t/AdminReview".$prefix.".gif");
	}

	private function uninstallTab14(){
		$tab_id = Tab::getIdFromClassName("AdminReviewsold");

        if($tab_id){
			$tab = new Tab($tab_id);
			$tab->delete();
		}
	
		@unlink(_PS_ROOT_DIR_."/img/t/AdminReviewsold.gif");
	}

    public function createAdminTabs15(){

    if(version_compare(_PS_VERSION_, '1.6', '>')) {
        $prefix = '';
    } else {
        $prefix = 'old';
    }

			@copy(dirname(__FILE__)."/AdminReview".$prefix.".gif",_PS_ROOT_DIR_."/img/t/AdminReview".$prefix.".gif");

		

		 	$langs = Language::getLanguages();

            

          

            $tab0 = new Tab();

            $tab0->class_name = "AdminReview".$prefix;

            $tab0->module = $this->name;

            $tab0->id_parent = 0; 

            foreach($langs as $l){

                    $tab0->name[$l['id_lang']] = (version_compare(_PS_VERSION_, '1.6', '>')?$this->l('Product Reviews'):$this->l('Reviews'));

            }

            $tab0->save();

            $main_tab_id = $tab0->id;



            unset($tab0);

            

            $tab1 = new Tab();

            $tab1->class_name = "AdminReviews".$prefix;

            $tab1->module = $this->name;

            $tab1->id_parent = $main_tab_id; 

            foreach($langs as $l){

                    $tab1->name[$l['id_lang']] = (version_compare(_PS_VERSION_, '1.6', '>')?$this->l('Moderate Product Reviews'):$this->l('Moderate Reviews'));

            }

            $tab1->save();



            unset($tab1);





          



	}

	

	private function createAdminTabs14(){

        $prefix = "old";

			@copy(dirname(__FILE__)."/AdminReview".$prefix.".gif",_PS_ROOT_DIR_."/img/t/AdminReview".$prefix.".gif");

		

		 	$langs = Language::getLanguages();

            

          

            $tab0 = new Tab();

            $tab0->class_name = "AdminReviews".$prefix;

            $tab0->module = $this->name;

            $tab0->id_parent = 0; 

            foreach($langs as $l){

                    $tab0->name[$l['id_lang']] = $this->l('Product Reviews');

            }

            $tab0->save();



	}





    public function createAdminTabsStoreReviews(){



        if(version_compare(_PS_VERSION_, '1.6', '>')) {

            $prefix = '';

        } else {

            $prefix = 'old';

        }



        copy_custom_gsnipreview(dirname(__FILE__)."/AdminStorereview".$prefix.".gif",_PS_ROOT_DIR_."/img/t/AdminStorereview".$prefix.".gif");



        $langs = Language::getLanguages();





        $tab0 = new Tab();

        $tab0->class_name = "AdminStorereview".$prefix;

        $tab0->module = $this->name;

        $tab0->id_parent = 0;

        foreach($langs as $l){

            $tab0->name[$l['id_lang']] = $this->l('Store Reviews');

        }

        $tab0->save();

        $main_tab_id = $tab0->id;



        unset($tab0);



        $tab1 = new Tab();

        $tab1->class_name = "AdminStorereviews".$prefix;

        $tab1->module = $this->name;

        $tab1->id_parent = $main_tab_id;

        foreach($langs as $l){

            $tab1->name[$l['id_lang']] = $this->l('Moderate Store Reviews');

        }

        $tab1->save();



        unset($tab1);





    }



    private function createAdminTabsStoreReviews14(){

        copy_custom_gsnipreview(dirname(__FILE__)."/AdminStorereviewold.gif",_PS_ROOT_DIR_."/img/t/AdminStorereviewold.gif");



        $langs = Language::getLanguages();





        $tab0 = new Tab();

        $tab0->class_name = "AdminStorereviewsold";

        $tab0->module = $this->name;

        $tab0->id_parent = 0;

        foreach($langs as $l){

            $tab0->name[$l['id_lang']] = $this->l('Store Reviews');

        }

        $tab0->save();



    }



    private function uninstallTabStoreReviews(){



        if(version_compare(_PS_VERSION_, '1.6', '>')) {

            $prefix = '';

        } else {

            $prefix = 'old';

        }



        $tab_id = Tab::getIdFromClassName("AdminStorereview".$prefix);

        if($tab_id){

            $tab = new Tab($tab_id);

            $tab->delete();

        }



        $tab_id = Tab::getIdFromClassName("AdminStorereviews".$prefix);

        if($tab_id){

            $tab = new Tab($tab_id);

            $tab->delete();

        }



        @unlink(_PS_ROOT_DIR_."/img/t/AdminStorereview".$prefix.".gif");

    }



    private function uninstallTabStoreReviews14(){



        $tab_id = Tab::getIdFromClassName("AdminStorereviewsold");

        if($tab_id){

            $tab = new Tab($tab_id);

            $tab->delete();

        }



        @unlink(_PS_ROOT_DIR_."/img/t/AdminStorereviewold.gif");

    }







	private function uninstallTable() {

		Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'gsnipreview');

		Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'gsnipreview_data_order');

		Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'gsnipreview_customer');



        Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'gsnipreview_review_criterion');

        Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'gsnipreview_review_criterion_lang');

        Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'gsnipreview_review2criterion');



        Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'gsnipreview_review_abuse');



        Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'gsnipreview_review_helpfull');



        Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'gsnipreview_socialshare');



        Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'gsnipreview_reminder2customer');







        Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'gsnipreview_avatar2customer');

        Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'gsnipreview_files2review');





        Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.$this->name.'_storereviews');

        Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.$this->name.'_data_order_'.$this->_prefix_shop_reviews);

        Db::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.$this->name.'_customer_'.$this->_prefix_shop_reviews);



		return true;

	}



    public function installReviewCriteria(){

        $languages = Language::getLanguages(false);

        $data_content_lang = array();





        if($this->_is15){

            $shops = array();

            foreach(Shop::getShops() as $shop){

                $shops[] = $shop['id_shop'];

            }

            $cat_shop_association = $shops;

        } else{

            $cat_shop_association = array(0=>0);

        }





        foreach ($languages as $language){

            $id_lang = $language['id_lang'];

            $description = '';

            $name = $this->l('Total Rating');



                $data_content_lang[$id_lang] = array( 'description' => $description,

                                                        'name' => $name

                );

        }





        $data = array(

            'active' => 1,

            'data_content_lang'=>$data_content_lang,

            'cat_shop_association' => $cat_shop_association

        );





            include_once(dirname(__FILE__) . '/classes/gsnipreviewhelp.class.php');

            $obj_gsnipreviewhelp = new gsnipreviewhelp();

            $obj_gsnipreviewhelp->saveReviewCriteriaItem($data);

        return true;

    }

	

	public function installTable()
	{
		$db = Db::getInstance();
	
		$query = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gsnipreview` (
							  `id` int(11) NOT NULL auto_increment,
							  `id_product` int(11) NOT NULL,
							  `id_customer` int(11) NOT NULL default \'0\',
							  `customer_name` varchar(500) default NULL,
							  `title_review` varchar(5000) default NULL,
							  `text_review` text,
							  `rating` int(11) NOT NULL default \'0\',

							  `title_review_old` varchar(5000) default NULL,
							  `text_review_old` text,
							  `rating_old` text,
							  `admin_response` text,
							  `is_changed` int(11) NOT NULL default \'0\',
							  `is_display_old` int(11) NOT NULL default \'0\',
							  `is_count_sending_suggestion` int(11) NOT NULL default \'0\',
							  `review_date_update` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

							  `avatar` text,


							  `email` VARCHAR(255) NOT NULL,
							  `ip` varchar(255) default NULL,
							  `id_shop` int(11) NOT NULL default \'0\',
							  `id_lang` int(11) NOT NULL default \'0\',
							  `is_abuse` int(11) NOT NULL default \'0\',
							  `is_active` int(11) NOT NULL default \'1\',
							  `time_add` timestamp NULL,
							  `is_import` int(11) NOT NULL default \'0\',
							  PRIMARY KEY  (`id`),
							  KEY `id_product` (`id_product`),
							  KEY `id_customer` (`id_customer`)
							) ENGINE='.(defined(_MYSQL_ENGINE_)?_MYSQL_ENGINE:"MyISAM").' DEFAULT CHARSET=utf8;';
		$db->Execute($query);

		$sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gsnipreview_data_order` (

					  `id` int(10) NOT NULL AUTO_INCREMENT,

					  `id_shop` int(11) NOT NULL default \'0\',

					  `order_id` int(10) NOT NULL,

					  `date_add` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\',

					  `status` int(10) NOT NULL default \'0\',

					  `customer_id` int(11) NOT NULL default \'0\',

					  `data` text,

					  `date_send` timestamp NULL,

					  `date_send_second` timestamp NULL,

                      `count_sent` int(10) NOT NULL default \'0\',

					  PRIMARY KEY (`id`)

					) ENGINE='.(defined(_MYSQL_ENGINE_)?_MYSQL_ENGINE:"MyISAM").' DEFAULT CHARSET=utf8;';

		$db->Execute($sql);

		

		$sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gsnipreview_customer` (

						  `id_shop` int(11) NOT NULL default \'0\',

						  `customer_id` int(11) NOT NULL default \'0\',

						  `status` int(10) NOT NULL default \'0\',

						   KEY (`id_shop`,`customer_id`),

						  KEY `shop_status` (`id_shop`,`status`)

						) ENGINE='.(defined(_MYSQL_ENGINE_)?_MYSQL_ENGINE:"MyISAM").' DEFAULT CHARSET=utf8;';

		$db->Execute($sql);

		return true;

	}



    public function installSocialShare(){

        $db = Db::getInstance();



        $sql_coupon = '

				CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gsnipreview_socialshare` (

					`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,

					`id_discount` INT( 11 ) NOT NULL ,

					`ip_adress` VARCHAR(255) NOT NULL,

					`id_guest` INT( 11 ) NOT NULL ,

					`id_customer` INT( 11 ) NOT NULL ,

					`id_review` INT( 11 ) NOT NULL ,

					`type` int(11) NOT NULL default \'0\'

					  COMMENT \'1 - Facebook, 2 - Google  \',

				    INDEX (`id_discount`)

				) ENGINE=InnoDB DEFAULT CHARSET=utf8;

				';

        $db->Execute($sql_coupon);



        return true;

    }



    public function installReviewHelpfull(){

        $db = Db::getInstance();

        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gsnipreview_review_helpfull` (

						  `id_customer` int(11)  NOT NULL,

                          `review_id` int(11) NOT NULL,

                          `is_guest` int(11) NOT NULL default \'0\',

                          `ip` varchar(255) default NULL,

                          `helpfull` tinyint(1) NOT NULL,

                          KEY (`id_customer`,`review_id`)

						) ENGINE='.(defined(_MYSQL_ENGINE_)?_MYSQL_ENGINE:"MyISAM").' DEFAULT CHARSET=utf8;';



        $db->Execute($sql);

        return true;

    }



    public function installReviewAbuse(){

        $db = Db::getInstance();

        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gsnipreview_review_abuse` (

						  `review_id` int UNSIGNED NOT NULL DEFAULT \'0\',

                          `id_customer` INT UNSIGNED NOT NULL,

                          `name` VARCHAR(500)  default NULL ,

                          `email` VARCHAR(255) default NULL ,

                          `text_abuse` text,

                          `is_customer` int(11) NOT NULL default \'0\',

                          KEY (`review_id`,`id_customer`)

						) ENGINE='.(defined(_MYSQL_ENGINE_)?_MYSQL_ENGINE:"MyISAM").' DEFAULT CHARSET=utf8;';



        $db->Execute($sql);

        return true;

    }



    public function installCriteriaTable(){

        $db = Db::getInstance();

        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gsnipreview_review_criterion` (

						  `id_gsnipreview_review_criterion` int(10) unsigned NOT NULL auto_increment,

						  `id_shop` varchar(1024) NOT NULL default \'0\',

                          `active` tinyint(1) NOT NULL,

                          PRIMARY KEY (`id_gsnipreview_review_criterion`)

						) ENGINE='.(defined(_MYSQL_ENGINE_)?_MYSQL_ENGINE:"MyISAM").' DEFAULT CHARSET=utf8;';



        $db->Execute($sql);



        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gsnipreview_review_criterion_lang` (

						  `id_gsnipreview_review_criterion` INT(11) UNSIGNED NOT NULL ,

                          `id_lang` INT(11) UNSIGNED NOT NULL ,

                          `name` VARCHAR(255) NOT NULL ,

                          `description` text,

                          KEY ( `id_gsnipreview_review_criterion` , `id_lang` )

						) ENGINE='.(defined(_MYSQL_ENGINE_)?_MYSQL_ENGINE:"MyISAM").' DEFAULT CHARSET=utf8;';



        $db->Execute($sql);





        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gsnipreview_review2criterion` (

						  `id_review` int(10) unsigned NOT NULL,

                          `id_criterion` int(10) unsigned NOT NULL,

                          `rating` INT(11) NOT NULL DEFAULT \'0\',

                           KEY(`id_review`, `id_criterion`),

                          KEY `id_criterion` (`id_criterion`)

						) ENGINE='.(defined(_MYSQL_ENGINE_)?_MYSQL_ENGINE:"MyISAM").' DEFAULT CHARSET=utf8;';



        $db->Execute($sql);



        return true;

    }



    public function installReminder2CustomerTable(){

        $db = Db::getInstance();





        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gsnipreview_reminder2customer` (

						  `id_customer` int(10) unsigned NOT NULL,

                          `id_shop` int(10) NOT NULL DEFAULT \'0\',

                          `status` INT(11) NOT NULL DEFAULT \'0\',

                           KEY `id_c2id_shop` (`id_customer`, `id_shop`),

                          KEY `id_customer` (`id_shop`)

						) ENGINE='.(defined(_MYSQL_ENGINE_)?_MYSQL_ENGINE:"MyISAM").' DEFAULT CHARSET=utf8;';



        $db->Execute($sql);



        return true;

    }





    public function installUserTable(){



        $db = Db::getInstance();

        $query = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gsnipreview_avatar2customer` (

							  `id_customer` int(11) NOT NULL,

							  `avatar_thumb` text,

							  `is_show` int(11) NOT NULL default \'1\',

							  KEY `id_customer` (`id_customer`)

							) ENGINE='.(defined('_MYSQL_ENGINE_')?_MYSQL_ENGINE_:"MyISAM").' DEFAULT CHARSET=utf8';

        $db->Execute($query);

        return true;



    }



    public function installFiles2ReviewTable(){



        $db = Db::getInstance();

        $query = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gsnipreview_files2review` (

                              `id_gsnipreview_files2review` int(10) unsigned NOT NULL auto_increment,

							  `id_review` int(11) NOT NULL,

							  `full_path` text,

							 PRIMARY KEY (`id_gsnipreview_files2review`)

							) ENGINE='.(defined('_MYSQL_ENGINE_')?_MYSQL_ENGINE_:"MyISAM").' DEFAULT CHARSET=utf8';

        $db->Execute($query);

        return true;



    }





    public function createShopReviewTable()

    {

        $db = Db::getInstance();



        $query = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.''.$this->name.'_storereviews` (

							  `id` int(11) NOT NULL auto_increment,

							  `name` varchar(500) NOT NULL,

							  `email` varchar(500) NOT NULL,

							  `id_customer` int(11) NOT NULL default \'0\',

							  `avatar` text,

							  `web` varchar(500) default NULL,

							  `company` varchar(500) default NULL,

							  `address` varchar(500) default NULL,

							  `message` text NOT NULL,

							  `response` text,

							  `is_show` int(11) NOT NULL default \'0\',

							  `rating` int(11) NOT NULL,

							  `country` varchar(500) default NULL,

							  `city` varchar(500) default NULL,

							  `id_shop` int(11) NOT NULL default \'0\',

							  `id_lang` int(11) NOT NULL default \'0\',

							  `active` int(11) NOT NULL default \'0\',

							  `is_deleted` int(11) NOT NULL default \'0\',

							  `date_add` timestamp NOT NULL default CURRENT_TIMESTAMP,

							  PRIMARY KEY  (`id`)

							) ENGINE='.(defined('_MYSQL_ENGINE_')?_MYSQL_ENGINE_:"MyISAM").' DEFAULT CHARSET=utf8;';

        $db->Execute($query);

        return true;

    }



    public function createReminderShopReviewsTable(){

        $db = Db::getInstance();

        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.''.$this->name.'_data_order_'.$this->_prefix_shop_reviews.'` (

					  `id` int(10) NOT NULL AUTO_INCREMENT,

					  `id_shop` int(11) NOT NULL default \'0\',

					  `order_id` int(10) NOT NULL,

					  `date_add` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\',

					  `status` int(10) NOT NULL default \'0\',

					  `customer_id` int(11) NOT NULL default \'0\',

					  `data` text,

					  `date_send` timestamp NULL,

					  `date_send_second` timestamp NULL,

                      `count_sent` int(10) NOT NULL default \'0\',

					  PRIMARY KEY (`id`)

					) ENGINE='.(defined(_MYSQL_ENGINE_)?_MYSQL_ENGINE:"MyISAM").' DEFAULT CHARSET=utf8;';

        $db->Execute($sql);



        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.''.$this->name.'_customer_'.$this->_prefix_shop_reviews.'` (

						  `id_shop` int(11) NOT NULL default \'0\',

						  `customer_id` int(11) NOT NULL default \'0\',

						  `status` int(10) NOT NULL default \'0\',

						  KEY (`id_shop`,`customer_id`),

						  KEY `shop_status` (`id_shop`,`status`)

						) ENGINE='.(defined(_MYSQL_ENGINE_)?_MYSQL_ENGINE:"MyISAM").' DEFAULT CHARSET=utf8;';

        $db->Execute($sql);



        return true;

    }





    public function createFolderAndSetPermissions(){



        $prev_cwd = getcwd();



        $module_dir = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR;

        @chdir($module_dir);

        //folder avatars

        $module_dir_img = $module_dir.$this->name.DIRECTORY_SEPARATOR;

        @mkdir($module_dir_img, 0777);



        @chdir($prev_cwd);



        return true;

    }



    public function createFolderAndSetPermissionsAvatar(){



        $prev_cwd = getcwd();



        $module_dir = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR;

        @chdir($module_dir);

        //folder avatars

        $module_dir_img = $module_dir.$this->name.DIRECTORY_SEPARATOR;

        @mkdir($module_dir_img, 0777);



        @chdir($module_dir_img);



        $module_dir_img_avatar = $module_dir.$this->name.DIRECTORY_SEPARATOR."avatar".DIRECTORY_SEPARATOR;

        @mkdir($module_dir_img_avatar, 0777);



        @chdir($prev_cwd);



        return true;

    }





    public function createFolderAndSetPermissionsFiles(){



        $prev_cwd = getcwd();



        $module_dir = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."upload".DIRECTORY_SEPARATOR;

        @chdir($module_dir);

        //folder avatars

        $module_dir_img = $module_dir.$this->name.DIRECTORY_SEPARATOR;

        @mkdir($module_dir_img, 0777);



        @chdir($module_dir_img);



        $module_dir_img_files_tmp = $module_dir.$this->name.DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR;

        @mkdir($module_dir_img_files_tmp, 0777);



        $module_dir_img_files = $module_dir.$this->name.DIRECTORY_SEPARATOR."files".DIRECTORY_SEPARATOR;

        @mkdir($module_dir_img_files, 0777);



        @chdir($prev_cwd);



        return true;

    }



    public function getContent()

    {

    	$cookie = $this->context->cookie;

		

    	$currentIndex = $this->context->currentindex;

    	 

    	$this->_html = '';

        $errors = array();





        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $this->addBackOfficeMedia();

        } else {

            $this->_html .= $this->_headercssfiles();

        }









        ### store reviews ###



        ### customerremindersettings settings ###

        $shopcustomerreminderset = Tools::getValue("shopcustomerreminderset");

        if (Tools::strlen($shopcustomerreminderset)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(74);</script>';

            } else {

                $this->_html .= '<script>init_tabs(5);</script>';

                $this->_html .= '<script>$(\'document\').ready( function() {tabs_custom_in_three(74);});</script>';



            }



        }





        if (Tools::isSubmit('shopcustomerremindersettings'))

        {

            Configuration::updateValue($this->name.'crondelay'.$this->_prefix_shop_reviews, Tools::getValue('crondelay'.$this->_prefix_shop_reviews));

            Configuration::updateValue($this->name.'cronnpost'.$this->_prefix_shop_reviews, Tools::getValue('cronnpost'.$this->_prefix_shop_reviews));





            Configuration::updateValue($this->name.'delaysec'.$this->_prefix_shop_reviews, Tools::getValue('delaysec'.$this->_prefix_shop_reviews));

            Configuration::updateValue($this->name.'remindersec'.$this->_prefix_shop_reviews, Tools::getValue('remindersec'.$this->_prefix_shop_reviews));





            Configuration::updateValue($this->name.'remrevsec'.$this->_prefix_shop_reviews, Tools::getValue('remrevsec'.$this->_prefix_shop_reviews));



            Configuration::updateValue($this->name.'delay'.$this->_prefix_shop_reviews, Tools::getValue('delay'.$this->_prefix_shop_reviews));

            Configuration::updateValue($this->name.'reminder'.$this->_prefix_shop_reviews, Tools::getValue('reminder'.$this->_prefix_shop_reviews));





            // orderstatuses

            $orderstatuses = Tools::getValue('orderstatuses');

            $orderstatuses = implode(",",$orderstatuses);

            Configuration::updateValue($this->name.'orderstatuses'.$this->_prefix_shop_reviews, $orderstatuses);





            $url = $currentIndex.'&conf=6&tab=AdminModules&shopcustomerreminderset=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        ### customerremindersettings settings ###







        ### emailsubjectssettings settings ###

        $emailsubjectsset = Tools::getValue("emailsubjectsset");

        if (Tools::strlen($emailsubjectsset)>0) {



            if($this->_is16) {

                $this->_html .= '<script>init_tabs(75);</script>';

            } else {

                $this->_html .= '<script>init_tabs(5);</script>';

                $this->_html .= '<script>$(\'document\').ready( function() {tabs_custom_in_three(75);});</script>';

            }

        }





        if (Tools::isSubmit('emailsubjectssettings'))

        {



            $languages = Language::getLanguages(false);

            foreach ($languages as $language){

                $i = $language['id_lang'];

                Configuration::updateValue($this->name.'emrem'.$this->_prefix_shop_reviews.'_'.$i, Tools::getValue('emrem'.$this->_prefix_shop_reviews.'_'.$i));

                Configuration::updateValue($this->name.'reminderok'.$this->_prefix_shop_reviews.'_'.$i, Tools::getValue('reminderok'.$this->_prefix_shop_reviews.'_'.$i));

                Configuration::updateValue($this->name.'thankyou'.$this->_prefix_shop_reviews.'_'.$i, Tools::getValue('thankyou'.$this->_prefix_shop_reviews.'_'.$i));

                Configuration::updateValue($this->name.'newtest'.$this->_prefix_shop_reviews.'_'.$i, Tools::getValue('newtest'.$this->_prefix_shop_reviews.'_'.$i));

                Configuration::updateValue($this->name.'resptest'.$this->_prefix_shop_reviews.'_'.$i, Tools::getValue('resptest'.$this->_prefix_shop_reviews.'_'.$i));



            }



            $url = $currentIndex.'&conf=6&tab=AdminModules&emailsubjectsset=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        ### customerremindersettings settings ###













        $start_end_orders_reviews = Tools::getValue("start_end_orders_reviews".$this->_prefix_shop_reviews);

        if (Tools::strlen($start_end_orders_reviews)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(77);</script>';

            } else {

                $this->_html .= '<script>init_tabs(5);</script>';

                $this->_html .= '<script>$(\'document\').ready( function() {tabs_custom_in_three(77);});</script>';

            }

        }





        $start_date_orders = Tools::getValue('start_date_orders'.$this->_prefix_shop_reviews);

        $end_date_orders = Tools::getValue('end_date_orders'.$this->_prefix_shop_reviews);



        if (($start_date_orders || $end_date_orders) && !$start_end_orders_reviews) {



            $url = $currentIndex.'&conf=6&tab=AdminModules&start_date_orders'.$this->_prefix_shop_reviews.'='.$start_date_orders.'&end_date_orders'.$this->_prefix_shop_reviews.'='.$end_date_orders.'&start_end_orders_reviews'.$this->_prefix_shop_reviews.'=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);







        }







        ### csv import ###

        $csvset = Tools::getValue("csvset".$this->_prefix_shop_reviews);

        if (Tools::strlen($csvset)>0) {



            if($this->_is16) {

                $this->_html .= '<script>init_tabs(78);</script>';

            } else {

                $this->_html .= '<script>init_tabs(5);</script>';

                $this->_html .= '<script>$(\'document\').ready( function() {tabs_custom_in_three(78);});</script>';

            }

        }



        if (Tools::isSubmit('store_csv')) {



            $name_class =  'csvhelp'.$this->_prefix_shop_reviews;

            include_once(dirname(__FILE__).'/classes/'.$name_class.'.class.php');

            $obj_csv = new $name_class;

            $error_data = $obj_csv->import();



            $error_number = $error_data['error_number'];



            switch($error_number){

                case 1:

                    $errors[] = Tools::displayError('Please select the CSV file');

                    break;

                case 2:

                    $errors[] = Tools::displayError('Your CSV file is empty');

                    break;



            }





            if(sizeof($errors)==0) {

                $url = $currentIndex . '&conf=18&tab=AdminModules&csvset'.$this->_prefix_shop_reviews.'=1&configure=' . $this->name . '&token=' . Tools::getAdminToken('AdminModules' . (int)(Tab::getIdFromClassName('AdminModules')) . (int)($cookie->id_employee)) . '';

                Tools::redirectAdmin($url);

            } else {



                if($this->_is16) {

                    $this->_html .= '<script>init_tabs(78);</script>';

                } else {

                    $this->_html .= '<script>init_tabs(5);</script>';

                    $this->_html .= '<script>$(\'document\').ready( function() {tabs_custom_in_three(78);});</script>';

                }



            }

        }

        ### csv import ###



        $moderateti = Tools::getValue("moderateti");

        $idti = Tools::getValue("id");

        if ((Tools::strlen($moderateti)>0 || Tools::strlen($idti)>0) && version_compare(_PS_VERSION_, '1.6', '<')) {





                $this->_html .= '<script>init_tabs(5);</script>';

                $this->_html .= '<script>$(\'document\').ready( function() {tabs_custom_in_three(79);});</script>';







        }



        // publish

        if (Tools::isSubmit("published".$this->_prefix_shop_reviews)) {

            if (Validate::isInt(Tools::getValue("id"))){

                include_once(dirname(__FILE__).'/classes/storereviews.class.php');

                $obj_storereviews = new storereviews();

                $obj_storereviews->setPublsh(array('id'=>Tools::getValue("id"), 'active'=>1));

                Tools::redirectAdmin($currentIndex.'&conf=5&tab=AdminModules&moderateti=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'');



            }

        }



        //unpublish

        if (Tools::isSubmit("unpublished".$this->_prefix_shop_reviews)) {

            if (Validate::isInt(Tools::getValue("id"))){

                include_once(dirname(__FILE__).'/classes/storereviews.class.php');

                $obj_storereviews = new storereviews();

                $obj_storereviews->setPublsh(array('id'=>Tools::getValue("id"), 'active'=>0));

                Tools::redirectAdmin($currentIndex.'&conf=5&tab=AdminModules&moderateti=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'');



            }

        }



        // delete item

        if (Tools::isSubmit("delete_item".$this->_prefix_shop_reviews)) {

            if (Validate::isInt(Tools::getValue("id"))) {

                include_once(dirname(__FILE__).'/classes/storereviews.class.php');

                $obj_storereviews = new storereviews();

                $obj_storereviews->deteleItem(array('id'=>Tools::getValue("id")));

                Tools::redirectAdmin($currentIndex.'&conf=1&tab=AdminModules&moderateti=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'');

            }



        }



        $testimonials_settingsset = Tools::getValue("testimonials_settingsset");

        if (Tools::strlen($testimonials_settingsset)>0) {



            if($this->_is16) {

                $this->_html .= '<script>init_tabs(73);</script>';

            } else {

                $this->_html .= '<script>init_tabs(5);</script>';

                $this->_html .= '<script>$(\'document\').ready( function() {tabs_custom_in_three(73);});</script>';

            }





        }



        if (Tools::isSubmit('submit_testimonials'))

        {

            Configuration::updateValue($this->name.'is_storerev', Tools::getValue('is_storerev'));



            Configuration::updateValue($this->name.'t_homes', Tools::getValue('t_homes'));

            Configuration::updateValue($this->name.'t_lefts', Tools::getValue('t_lefts'));

            Configuration::updateValue($this->name.'t_rights', Tools::getValue('t_rights'));

            Configuration::updateValue($this->name.'t_footers', Tools::getValue('t_footers'));

            Configuration::updateValue($this->name.'t_rightsides', Tools::getValue('t_rightsides'));

            Configuration::updateValue($this->name.'t_leftsides', Tools::getValue('t_leftsides'));

            Configuration::updateValue($this->name.'t_tpages', Tools::getValue('t_tpages'));



            Configuration::updateValue($this->name.'st_left', Tools::getValue('st_left'));

            Configuration::updateValue($this->name.'st_right', Tools::getValue('st_right'));

            Configuration::updateValue($this->name.'st_footer', Tools::getValue('st_footer'));

            Configuration::updateValue($this->name.'st_home', Tools::getValue('st_home'));

            Configuration::updateValue($this->name.'st_leftside', Tools::getValue('st_leftside'));

            Configuration::updateValue($this->name.'st_rightside', Tools::getValue('st_rightside'));



            Configuration::updateValue($this->name.'mt_left', Tools::getValue('mt_left'));

            Configuration::updateValue($this->name.'mt_right', Tools::getValue('mt_right'));

            Configuration::updateValue($this->name.'mt_footer', Tools::getValue('mt_footer'));

            Configuration::updateValue($this->name.'mt_home', Tools::getValue('mt_home'));

            Configuration::updateValue($this->name.'mt_leftside', Tools::getValue('mt_leftside'));

            Configuration::updateValue($this->name.'mt_rightside', Tools::getValue('mt_rightside'));







            Configuration::updateValue($this->name.'tlast', Tools::getValue('tlast'));





            Configuration::updateValue($this->name.'t_home', Tools::getValue('t_home'));

            Configuration::updateValue($this->name.'t_left', Tools::getValue('t_left'));

            Configuration::updateValue($this->name.'t_right', Tools::getValue('t_right'));

            Configuration::updateValue($this->name.'t_footer', Tools::getValue('t_footer'));

            Configuration::updateValue($this->name.'t_rightside', Tools::getValue('t_rightside'));

            Configuration::updateValue($this->name.'t_leftside', Tools::getValue('t_leftside'));





            Configuration::updateValue($this->name.'perpage'.$this->_prefix_shop_reviews, Tools::getValue('perpage'.$this->_prefix_shop_reviews));

            Configuration::updateValue($this->name.'noti'.$this->_prefix_shop_reviews, Tools::getValue('noti'.$this->_prefix_shop_reviews));

            Configuration::updateValue($this->name.'mail'.$this->_prefix_shop_reviews, Tools::getValue('mail'.$this->_prefix_shop_reviews));



            Configuration::updateValue($this->name.'whocanadd'.$this->_prefix_shop_reviews, Tools::getValue('whocanadd'));



            Configuration::updateValue($this->name.'is_avatar', Tools::getValue('is_avatar'));

            Configuration::updateValue($this->name.'is_captcha'.$this->_prefix_shop_reviews, Tools::getValue('is_captcha'.$this->_prefix_shop_reviews));

            Configuration::updateValue($this->name.'is_web', Tools::getValue('is_web'));

            Configuration::updateValue($this->name.'is_company', Tools::getValue('is_company'));

            Configuration::updateValue($this->name.'is_addr', Tools::getValue('is_addr'));



            Configuration::updateValue($this->name.'is_country', Tools::getValue('is_country'));

            Configuration::updateValue($this->name.'is_city', Tools::getValue('is_city'));









            Configuration::updateValue($this->name.'n_rssitemst', Tools::getValue('n_rssitemst'));

            Configuration::updateValue($this->name.'rssontestim', Tools::getValue('rssontestim'));



            Configuration::updateValue($this->name.'BGCOLOR_T', Tools::getValue($this->name.'BGCOLOR_T'));

            Configuration::updateValue($this->name.'BGCOLOR_TIT', Tools::getValue($this->name.'BGCOLOR_TIT'));







            $url = $currentIndex . '&conf=6&tab=AdminModules&testimonials_settingsset=1&configure=' . $this->name . '&token=' . Tools::getAdminToken('AdminModules' . (int)(Tab::getIdFromClassName('AdminModules')) . (int)($cookie->id_employee)) . '';

            Tools::redirectAdmin($url);







        }





        if (Tools::isSubmit('submit_item'.$this->_prefix_shop_reviews))

        {

            $name = Tools::getValue("name");

            $email = Tools::getValue("email");

            $web = Tools::getValue("web");

            $company = Tools::getValue("company");

            $address = Tools::getValue("address");

            $country = Tools::getValue("country");

            $city = Tools::getValue("city");

            $rating = Tools::getValue("rating");



            $message = Tools::getValue("message");

            $publish = (int)Tools::getValue("publish");



            $date_add = Tools::getValue("date_add");





            $response = Tools::getValue("response");

            $is_noti = Tools::getValue("is_noti");

            $is_show = Tools::getValue("is_show");



            $post_images = Tools::getValue("post_images");



            $id_customer = Tools::getValue("id_customer");





            include_once(dirname(__FILE__).'/classes/storereviews.class.php');

            $obj_storereviews = new storereviews();

            $obj_storereviews->updateItem(array('name'=>$name,

                            'email'=>$email,

                            'web' =>$web,

                            'message'=>$message,

                            'publish'=>$publish,

                            'address'=>$address,

                            'company'=>$company,

                            'country'=>$country,

                            'city'=>$city,

                            'rating'=>$rating,

                            'date_add' => $date_add,

                            'id' =>Tools::getValue("id"),



                            'response'=>$response,

                            'is_noti'=>$is_noti,

                            'is_show'=>$is_show,



                            'post_images' => $post_images,



                            'id_customer' => $id_customer,

                    )

            );



            Tools::redirectAdmin($currentIndex.'&conf=4&tab=AdminModules&moderateti=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'');

        }



        if (Tools::isSubmit('cancel_item'.$this->_prefix_shop_reviews))

        {

            Tools::redirectAdmin($currentIndex.'&tab=AdminModules&moderateti=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'');

        }



        ## store reviews ###





        $start_end_orders_reviews = Tools::getValue("start_end_orders_reviews");

        if (Tools::strlen($start_end_orders_reviews)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(44);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(44);</script>';

            }

        }





        $start_date_orders = Tools::getValue('start_date_orders');

        $end_date_orders = Tools::getValue('end_date_orders');



        if (($start_date_orders || $end_date_orders) && !$start_end_orders_reviews) {



            $url = $currentIndex.'&conf=6&tab=AdminModules&start_date_orders='.$start_date_orders.'&end_date_orders='.$end_date_orders.'&start_end_orders_reviews=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);







        }



        ### csv import ###

        $csvset = Tools::getValue("csvset");

        if (Tools::strlen($csvset)>0) {



            if($this->_is16) {

                $this->_html .= '<script>init_tabs(45);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(45);</script>';

            }





        }



        if (Tools::isSubmit('product_csv')) {



            $name_class =  'csvhelp'.$this->_prefix_review;

            include_once(dirname(__FILE__).'/classes/'.$name_class.'.class.php');

            $obj_csv = new $name_class;

            $error_data = $obj_csv->import();



            $error_number = $error_data['error_number'];



            switch($error_number){

                case 1:

                    $errors[] = Tools::displayError('Please select the CSV file');

                    break;

                case 2:

                    $errors[] = Tools::displayError('Your CSV file is empty');

                    break;



            }





            if(sizeof($errors)==0) {

                $url = $currentIndex . '&conf=18&tab=AdminModules&csvset=1&configure=' . $this->name . '&token=' . Tools::getAdminToken('AdminModules' . (int)(Tab::getIdFromClassName('AdminModules')) . (int)($cookie->id_employee)) . '';

                Tools::redirectAdmin($url);

            } else {

                if($this->_is16) {

                    foreach ($errors as $error_text) {

                        $this->_html .= $this->_error(array('text' => $error_text));

                    }

                }

                if($this->_is16) {

                    $this->_html .= '<script>init_tabs(45);</script>';

                } else {

                    $this->_html .= '<script>init_tabs(3);</script>';

                    $this->_html .= '<script>init_tabs_in(45);</script>';

                }



            }

        }

        ### csv import ###





        #### user profile ###

        $userprofilegset = Tools::getValue("userprofilegset");

        if (Tools::strlen($userprofilegset)>0) {



            if($this->_is16) {

                $this->_html .= '<script>init_tabs(56);</script>';

            } else {

                $this->_html .= '<script>init_tabs(7);</script>';

            }



        }

        if (Tools::isSubmit('userprofilegsettings'))

        {

            Configuration::updateValue($this->name.'is_uprof', Tools::getValue('is_uprof'));



            Configuration::updateValue($this->name.'rpage_shoppers', Tools::getValue('rpage_shoppers'));



            Configuration::updateValue($this->name.'radv_home', Tools::getValue('radv_home'));

            Configuration::updateValue($this->name.'radv_left', Tools::getValue('radv_left'));

            Configuration::updateValue($this->name.'radv_right', Tools::getValue('radv_right'));

            Configuration::updateValue($this->name.'radv_footer', Tools::getValue('radv_footer'));



            Configuration::updateValue($this->name.'rshoppers_blc', Tools::getValue('rshoppers_blc'));



            $url = $currentIndex.'&conf=6&tab=AdminModules&userprofilegset=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);

        }





    	#### posts api ###

    	$vkset = Tools::getValue("vkset");

        if (Tools::strlen($vkset)>0) {

        	$this->_html .= '<script>init_tabs(8);</script>';

        }

    	if (Tools::isSubmit('psvkpostsettings'))

        {

        	Configuration::updateValue($this->name.'vkpost_on', Tools::getValue('vkpost_on'));

        	

        	$languages = Language::getLanguages(false);

        	foreach ($languages as $language){

    			$i = $language['id_lang'];

        		Configuration::updateValue($this->name.'vkdesc_'.$i, Tools::getValue('vkdesc_'.$i));

        	}

        	

        	$url = $currentIndex.'&conf=6&tab=AdminModules&vkset=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

        	Tools::redirectAdmin($url);

        }

        

        

        

    	$twset = Tools::getValue("twset");

        if (Tools::strlen($twset)>0) {

        	$this->_html .= '<script>init_tabs(8);</script>';

        }

    	if (Tools::isSubmit('pstwitterpostsettings'))

        {

        	Configuration::updateValue($this->name.'twpost_on', Tools::getValue('twpost_on'));

        	

        	

        	$languages = Language::getLanguages(false);

        	foreach ($languages as $language){

    			$i = $language['id_lang'];

        		Configuration::updateValue($this->name.'twdesc_'.$i, Tools::getValue('twdesc_'.$i));

        	}

        	

        	$url = $currentIndex.'&conf=6&tab=AdminModules&twset=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

        	Tools::redirectAdmin($url);

        }

    	#### posts api ###

    	

    	

    	

    	// pinterest settings

    	$pinterestset = Tools::getValue("pinterestset");

        if (Tools::strlen($pinterestset)>0) {

        	$this->_html .= '<script>init_tabs(7);</script>';

        }

    	if (Tools::isSubmit('pinterestsettings'))

        {

        	Configuration::updateValue($this->name.'pinvis_on', Tools::getValue('pinvis_on'));

        	Configuration::updateValue($this->name.'pinterestbuttons', Tools::getValue('pinterestbuttons'));

        	

        	Configuration::updateValue($this->name.'_leftColumn', Tools::getValue('leftColumn'));

        	Configuration::updateValue($this->name.'_extraLeft', Tools::getValue('extraLeft'));

        	Configuration::updateValue($this->name.'_productFooter', Tools::getValue('productFooter'));

        	Configuration::updateValue($this->name.'_rightColumn', Tools::getValue('rightColumn'));

        	Configuration::updateValue($this->name.'_extraRight', Tools::getValue('extraRight'));

        	Configuration::updateValue($this->name.'_productActions', Tools::getValue('productActions'));

			

        	

        	$url = $currentIndex.'&conf=6&tab=AdminModules&pinterestset=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

        	Tools::redirectAdmin($url);

        }

    	// pinterest settings

    	

    	// google rich snippets

    	

    	$snippetsset = Tools::getValue("snippetsset");

        if (Tools::strlen($snippetsset)>0) {

        	$this->_html .= '<script>init_tabs(2);</script>';

        }







        if (Tools::isSubmit('snippetssettings'))

        {

        	Configuration::updateValue($this->name.'gsnipblock', Tools::getValue('gsnipblock'));

			Configuration::updateValue($this->name.'id_hook_gsnipblock', Tools::getValue('id_hook_gsnipblock'));

			Configuration::updateValue($this->name.'gsnipblock_width', Tools::getValue('gsnipblock_width'));

			Configuration::updateValue($this->name.'gsnipblocklogo', Tools::getValue('gsnipblocklogo'));



            Configuration::updateValue($this->name.'allinfo_on', Tools::getValue('allinfo_on'));



            Configuration::updateValue($this->name.'allinfo_home_pos', Tools::getValue('pallinfo_home'));

            Configuration::updateValue($this->name.'allinfo_cat_pos', Tools::getValue('pallinfo_cat'));

            Configuration::updateValue($this->name.'allinfo_man_pos', Tools::getValue('pallinfo_man'));



            Configuration::updateValue($this->name.'allinfo_home_w', Tools::getValue('allinfo_home_w'));

            Configuration::updateValue($this->name.'allinfo_cat_w', Tools::getValue('allinfo_cat_w'));

            Configuration::updateValue($this->name.'allinfo_man_w', Tools::getValue('allinfo_man_w'));





            Configuration::updateValue($this->name.'allinfo_home', Tools::getValue('allinfo_home'));

            Configuration::updateValue($this->name.'allinfo_cat', Tools::getValue('allinfo_cat'));

            Configuration::updateValue($this->name.'allinfo_man', Tools::getValue('allinfo_man'));





            Configuration::updateValue($this->name.'svis_on', Tools::getValue('svis_on'));

            Configuration::updateValue($this->name.'breadvis_on', Tools::getValue('breadvis_on'));





            Configuration::updateValue($this->name.'pinvis_on', Tools::getValue('pinvis_on'));

            Configuration::updateValue($this->name.'pinterestbuttons', Tools::getValue('pinterestbuttons'));



            Configuration::updateValue($this->name.'_leftColumn', Tools::getValue('leftColumn'));

            Configuration::updateValue($this->name.'_extraLeft', Tools::getValue('extraLeft'));

            Configuration::updateValue($this->name.'_productFooter', Tools::getValue('productFooter'));

            Configuration::updateValue($this->name.'_rightColumn', Tools::getValue('rightColumn'));

            Configuration::updateValue($this->name.'_extraRight', Tools::getValue('extraRight'));

            Configuration::updateValue($this->name.'_productActions', Tools::getValue('productActions'));



        	$url = $currentIndex.'&conf=6&tab=AdminModules&snippetsset=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

        	Tools::redirectAdmin($url);

        }

    	// google rich snippets

    	

    	$page_moderate = Tools::isSubmit("page");

        $addgsnipreview = Tools::isSubmit('addgsnipreview');



        if ($page_moderate || $addgsnipreview) {

        	$this->_html .= '<script>init_tabs(4);</script>';

        }

    	

    	if(Tools::isSubmit('submit_item')){

	    	include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

			$obj = new gsnipreviewhelp();

			

	    	$action = Tools::getValue('submit_item');

	    	$id_review = (int)Tools::getValue('id');

            $conf = '';

	    	switch($action){

	    		case 'delete':

	    			$obj->delete(array('id'=>$id_review));

                    $conf = '&conf=1';

	    		break;

	    	}

	    	$page = Tools::getValue("page");

	    	Tools::redirectAdmin($currentIndex.$conf.'&tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'&page='.$page);

	    }

	    

   		if (Tools::isSubmit('cancel_item'))

        {

        	$page = Tools::getValue("page");

        	Tools::redirectAdmin($currentIndex.'&tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'&page='.$page);

		}

		

    	if (Tools::isSubmit('add_item'))

        {

        	include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

            $gsnipreviewhelp_obj = new gsnipreviewhelp();



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



                Tools::redirectAdmin($currentIndex.'&conf=3&tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'&page='.$page);



            }

            ## add item ##

        }



        if (Tools::isSubmit('update_item'))

        {

            $id = Tools::getValue('id');

            ## update item ##



            include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

            $gsnipreviewhelp_obj = new gsnipreviewhelp();





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



           /* if(!$name)

                $errors[] = Tools::displayError('Please fill the Customer Name');



            if(!$email)

                $errors[] = Tools::displayError('Please fill the Email Name');*/



            if(!$text_review && Configuration::get($this->name.'text_on'))

                $errors[] = Tools::displayError('Please fill the Text');



            if (!$title_review && Configuration::get($this->name.'title_on'))

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

                Tools::redirectAdmin($currentIndex.'&conf=4&tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'&page='.$page);



            }



            ## update item ##



        }

    	

    	// product reviews advanced 1.6





        ### global settings ###

        $revglobalsettings = Tools::getValue("revglobalsettings");

        if (Tools::strlen($revglobalsettings)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(31);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(31);</script>';

            }

        }





        if (Tools::isSubmit('globalsettings'))

        {

            Configuration::updateValue($this->name.'rvis_on', Tools::getValue('rvis_on'));

            Configuration::updateValue($this->name.'ratings_on', Tools::getValue('ratings_on'));

            Configuration::updateValue($this->name.'text_on', Tools::getValue('text_on'));

            Configuration::updateValue($this->name.'title_on', Tools::getValue('title_on'));

            Configuration::updateValue($this->name.'ip_on', Tools::getValue('ip_on'));

            Configuration::updateValue($this->name.'is_captcha', Tools::getValue('is_captcha'));



            Configuration::updateValue($this->name.'is_avatar'.$this->_prefix_review, Tools::getValue('is_avatar'.$this->_prefix_review));

            Configuration::updateValue($this->name.'is_files'.$this->_prefix_review, Tools::getValue('is_files'.$this->_prefix_review));

            Configuration::updateValue($this->name.'ruploadfiles', Tools::getValue('ruploadfiles'));

            Configuration::updateValue($this->name.'rminc', Tools::getValue('rminc'));







            $url = $currentIndex.'&conf=6&tab=AdminModules&revglobalsettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        ### global settings ###





        ### product page settings ###

        $revproductpagesettings = Tools::getValue("revproductpagesettings");

        if (Tools::strlen($revproductpagesettings)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(32);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(32);</script>';

            }

        }





        if (Tools::isSubmit('productpagesettings'))

        {

            Configuration::updateValue($this->name.'ptabs_type', Tools::getValue('ptabs_type'));

            Configuration::updateValue($this->name.'is_abusef', Tools::getValue('is_abusef'));

            Configuration::updateValue($this->name.'is_helpfulf', Tools::getValue('is_helpfulf'));

            Configuration::updateValue($this->name.'hooktodisplay', Tools::getValue('hooktodisplay'));

            Configuration::updateValue($this->name.'starratingon', Tools::getValue('starratingon'));

            Configuration::updateValue($this->name.'stylestars', Tools::getValue('stylestars'));

            Configuration::updateValue($this->name.'revperpage', Tools::getValue('revperpage'));





            Configuration::updateValue($this->name.'rsoc_on', Tools::getValue('rsoc_on'));

            Configuration::updateValue($this->name.'rsoccount_on', Tools::getValue('rsoccount_on'));



            $url = $currentIndex.'&conf=6&tab=AdminModules&revproductpagesettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        ### product page settings ###



        ### reviewsmanagementsettings settings ###

        $revreviewsmanagementsettings = Tools::getValue("revreviewsmanagementsettings");

        if (Tools::strlen($revreviewsmanagementsettings)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(33);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(33);</script>';

            }

        }





        if (Tools::isSubmit('reviewsmanagementsettings'))

        {

            Configuration::updateValue($this->name.'is_approval', Tools::getValue('is_approval'));

            Configuration::updateValue($this->name.'whocanadd', Tools::getValue('whocanadd'));

            Configuration::updateValue($this->name.'rswitch_lng', Tools::getValue('rswitch_lng'));

            Configuration::updateValue($this->name.'revperpageall', Tools::getValue('revperpageall'));

            Configuration::updateValue($this->name.'adminrevperpage', Tools::getValue('adminrevperpage'));



            Configuration::updateValue($this->name.'is_onerev', Tools::getValue('is_onerev'));





            $url = $currentIndex.'&conf=6&tab=AdminModules&revreviewsmanagementsettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        ### addcriteriasettings settings ###





        ### customeraccountreviewspage settings ###

        if (Tools::isSubmit("gsnipreviewcriteriaset") && !$this->_is15)

        {



            $url = $currentIndex.'&tab=AdminModules&revcriteriasettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);

        }



        $revcriteriasettings = Tools::getValue("revcriteriasettings");



        $addCriteria = Tools::isSubmit("addCriteria");

        $editgsnipreview = Tools::isSubmit('editgsnipreview');

        $delete_itemgsnipreview = Tools::isSubmit("delete_itemgsnipreview");



        if (Tools::strlen($revcriteriasettings)>0

            || $addCriteria || $editgsnipreview || $delete_itemgsnipreview) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(34);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(34);</script>';

            }

        }





        if ($this->_is15 && Tools::isSubmit("gsnipreviewcriteriaset")) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(34);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(34);</script>';

            }

        }



        if (Tools::isSubmit("delete_itemgsnipreview")) {

            if (Validate::isInt(Tools::getValue("id"))) {

                $data = array('id' => Tools::getValue("id"));

                include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

                $obj_gsnipreviewhelp = new gsnipreviewhelp();

                $obj_gsnipreviewhelp->deleteReviewCriteriaItem($data);



                $url = $currentIndex.'&conf=1&tab=AdminModules&revcriteriasettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

                Tools::redirectAdmin($url);

            }



        }



        if (Tools::isSubmit('editcriteriasettings'))

        {

            $id = Tools::getValue("id");



            //var_dump($_REQUEST);exit;



            if($this->_is15){

                $cat_shop_association = Tools::getValue("cat_shop_association");

            } else{

                $cat_shop_association = array(0=>0);

            }



            $languages = Language::getLanguages(false);

            $data_content_lang = array();



            $data_content_lang_name = array();



            foreach ($languages as $language){

                $id_lang = $language['id_lang'];

                $description = Tools::getValue("description_".$id_lang);

                $name = Tools::getValue("name_".$id_lang);

                if(Tools::strlen($name)>0)

                {

                    $data_content_lang[$id_lang] = array('description' => $description,

                                                         'name' => $name);

                    $data_content_lang_name[$id_lang] = array('name' => $name);

                }

            }



            $active = Tools::getValue("active");



            $data = array('active' => $active,

                          'data_content_lang'=>$data_content_lang,

                          'cat_shop_association' => $cat_shop_association,

                          'id' => $id

            );

           // echo "<pre>"; var_dump($data_content_lang_name);exit;

            if(sizeof($data_content_lang_name)>0){

                include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

                $obj_gsnipreviewhelp = new gsnipreviewhelp();

                $obj_gsnipreviewhelp->updateReviewCriteriaItem($data);



            } else {

                $error = 2;

                $this->_html .= $this->_error(array('text' => 'Criterion name or Description is empty!'));

                $this->_criterion_error = 1;



            }



            if($error == 0){

                $url = $currentIndex.'&conf=4&tab=AdminModules&revcriteriasettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

                Tools::redirectAdmin($url);

            }





        }



        //echo "<pre>"; var_dump($_POST);var_dumP(Tools::isSubmit('addcriteriasettings'));exit;

        if (Tools::isSubmit('addcriteriasettings'))

        {

            $languages = Language::getLanguages(false);

            $data_content_lang = array();

            $data_content_lang_name = array();



            if($this->_is15){

                $cat_shop_association = Tools::getValue("cat_shop_association");

            } else{

                $cat_shop_association = array(0=>0);

            }





            foreach ($languages as $language){

                $id_lang = $language['id_lang'];

                $description = Tools::getValue("description_".$id_lang);

                $name = Tools::getValue("name_".$id_lang);

                if(Tools::strlen($name)>0)

                {

                    $data_content_lang[$id_lang] = array( 'description' => $description,

                                                           'name' => $name

                                                        );

                    $data_content_lang_name[$id_lang] = array('name' => $name);

                }

            }



            $active = Tools::getValue("active");



            $data = array(

                'active' => $active,

                'data_content_lang'=>$data_content_lang,

                'cat_shop_association' => $cat_shop_association

            );





            //echo "<pre>"; var_dump($data);exit;



            if(sizeof($data_content_lang_name)>0){

                include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

                $obj_gsnipreviewhelp = new gsnipreviewhelp();

                $obj_gsnipreviewhelp->saveReviewCriteriaItem($data);



            } else {

                $error = 2;

                if(!$this->_is15) {

                    $this->_html .= $this->_error(array('text' => 'Criterion name or Description is empty!'));



                } else {

                    $this->_html .= $this->_error(array('text' => 'Criterion name or Description or Shop association is empty!'));

                    $this->_criterion_error = 1;

                }

            }





            if($error == 0){

                $url = $currentIndex.'&conf=3&tab=AdminModules&revcriteriasettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

                Tools::redirectAdmin($url);

            }









        }

        ### addcriteriasettings settings ###





        ### customeraccountreviewspage settings ###

        $revcustomeraccountreviewspagesettings = Tools::getValue("revcustomeraccountreviewspagesettings");

        if (Tools::strlen($revcustomeraccountreviewspagesettings)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(35);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(35);</script>';

            }

        }





        if (Tools::isSubmit('customeraccountreviewspagesettings'))

        {

            Configuration::updateValue($this->name.'revperpagecus', Tools::getValue('revperpagecus'));



            $url = $currentIndex.'&conf=6&tab=AdminModules&revcustomeraccountreviewspagesettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        ### customeraccountreviewspage settings ###





        ### lastreviewsblock settings ###

        $revlastreviewsblocksettings = Tools::getValue("revlastreviewsblocksettings");

        if (Tools::strlen($revlastreviewsblocksettings)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(36);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(36);</script>';

            }

        }





        if (Tools::isSubmit('lastreviewsblocksettings'))

        {

            Configuration::updateValue($this->name.'is_blocklr', Tools::getValue('is_blocklr'));



            Configuration::updateValue($this->name.'blocklr_home_pos', Tools::getValue('pblocklr_home'));

            Configuration::updateValue($this->name.'blocklr_cat_pos', Tools::getValue('pblocklr_cat'));

            Configuration::updateValue($this->name.'blocklr_man_pos', Tools::getValue('pblocklr_man'));

            Configuration::updateValue($this->name.'blocklr_prod_pos', Tools::getValue('pblocklr_prod'));

            Configuration::updateValue($this->name.'blocklr_oth_pos', Tools::getValue('pblocklr_oth'));

            Configuration::updateValue($this->name.'blocklr_chook_pos', Tools::getValue('pblocklr_chook'));



            Configuration::updateValue($this->name.'blocklr_home_w', Tools::getValue('blocklr_home_w'));

            Configuration::updateValue($this->name.'blocklr_cat_w', Tools::getValue('blocklr_cat_w'));

            Configuration::updateValue($this->name.'blocklr_man_w', Tools::getValue('blocklr_man_w'));

            Configuration::updateValue($this->name.'blocklr_prod_w', Tools::getValue('blocklr_prod_w'));

            Configuration::updateValue($this->name.'blocklr_oth_w', Tools::getValue('blocklr_oth_w'));

            Configuration::updateValue($this->name.'blocklr_chook_w', Tools::getValue('blocklr_chook_w'));





            Configuration::updateValue($this->name.'blocklr_home', Tools::getValue('blocklr_home'));

            Configuration::updateValue($this->name.'blocklr_cat', Tools::getValue('blocklr_cat'));

            Configuration::updateValue($this->name.'blocklr_man', Tools::getValue('blocklr_man'));

            Configuration::updateValue($this->name.'blocklr_prod', Tools::getValue('blocklr_prod'));

            Configuration::updateValue($this->name.'blocklr_oth', Tools::getValue('blocklr_oth'));

            Configuration::updateValue($this->name.'blocklr_chook', Tools::getValue('blocklr_chook'));



            Configuration::updateValue($this->name.'blocklr_home_ndr', Tools::getValue('blocklr_home_ndr'));

            Configuration::updateValue($this->name.'blocklr_cat_ndr', Tools::getValue('blocklr_cat_ndr'));

            Configuration::updateValue($this->name.'blocklr_man_ndr', Tools::getValue('blocklr_man_ndr'));

            Configuration::updateValue($this->name.'blocklr_prod_ndr', Tools::getValue('blocklr_prod_ndr'));

            Configuration::updateValue($this->name.'blocklr_oth_ndr', Tools::getValue('blocklr_oth_ndr'));

            Configuration::updateValue($this->name.'blocklr_chook_ndr', Tools::getValue('blocklr_chook_ndr'));



            Configuration::updateValue($this->name.'blocklr_home_tr', Tools::getValue('blocklr_home_tr'));

            Configuration::updateValue($this->name.'blocklr_cat_tr', Tools::getValue('blocklr_cat_tr'));

            Configuration::updateValue($this->name.'blocklr_man_tr', Tools::getValue('blocklr_man_tr'));

            Configuration::updateValue($this->name.'blocklr_prod_tr', Tools::getValue('blocklr_prod_tr'));

            Configuration::updateValue($this->name.'blocklr_oth_tr', Tools::getValue('blocklr_oth_tr'));

            Configuration::updateValue($this->name.'blocklr_chook_tr', Tools::getValue('blocklr_chook_tr'));



            Configuration::updateValue($this->name.'blocklr_home_im', Tools::getValue('iblocklr_home'));

            Configuration::updateValue($this->name.'blocklr_cat_im', Tools::getValue('iblocklr_cat'));

            Configuration::updateValue($this->name.'blocklr_man_im', Tools::getValue('iblocklr_man'));

            Configuration::updateValue($this->name.'blocklr_prod_im', Tools::getValue('iblocklr_prod'));

            Configuration::updateValue($this->name.'blocklr_oth_im', Tools::getValue('iblocklr_oth'));

            Configuration::updateValue($this->name.'blocklr_chook_im', Tools::getValue('iblocklr_chook'));





            $url = $currentIndex.'&conf=6&tab=AdminModules&revlastreviewsblocksettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        ### lastreviewsblock settings ###





        ### starslistandsearchsettings settings ###

        $revstarslistandsearchsettings = Tools::getValue("revstarslistandsearchsettings");

        if (Tools::strlen($revstarslistandsearchsettings)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(37);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(37);</script>';

            }

        }





        if (Tools::isSubmit('starslistandsearchsettings'))

        {

            Configuration::updateValue($this->name.'starscat', Tools::getValue('starscat'));



            $url = $currentIndex.'&conf=6&tab=AdminModules&revstarslistandsearchsettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        ### starslistandsearchsettings settings ###





        ### rssfeed settings ###

        $revrssfeedsettings = Tools::getValue("revrssfeedsettings");

        if (Tools::strlen($revrssfeedsettings)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(38);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(38);</script>';

            }

        }





        if (Tools::isSubmit('rssfeedsettings'))

        {

            Configuration::updateValue($this->name.'rsson', Tools::getValue('rsson'));

            Configuration::updateValue($this->name.'number_rssitems', Tools::getValue('number_rssitems'));





            $languages = Language::getLanguages(false);

            foreach ($languages as $language){

                $i = $language['id_lang'];

                Configuration::updateValue($this->name.'rssname_'.$i, Tools::getValue('rssname_'.$i));

                Configuration::updateValue($this->name.'rssdesc_'.$i, Tools::getValue('rssdesc_'.$i));

            }



            $url = $currentIndex.'&conf=6&tab=AdminModules&revrssfeedsettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        ### rssfeed settings ###





        ### importcommentssettings settings ###

        $revimportcommentssettings = Tools::getValue("revimportcommentssettings");

        if (Tools::strlen($revimportcommentssettings)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(39);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(39);</script>';

            }

        }





        if (Tools::isSubmit('submitcomments'))

        {

            include_once(dirname(__FILE__).'/classes/importhelp.class.php');

            $obj = new importhelp();



            $obj->importComments();



            $url = $currentIndex.'&conf=6&tab=AdminModules&revimportcommentssettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        ### importcommentssettings settings ###





        // Google Product Review Feeds for Google Shopping

        $revsubmitgooglereviews = Tools::getValue("revsubmitgooglereviews");

        if (Tools::strlen($revsubmitgooglereviews)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(43);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(43);</script>';

            }

        }





        if (Tools::isSubmit('submitgooglereviews'))

        {

            include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

            $obj = new gsnipreviewhelp();





            $obj->generateGoogleReviews();



            $url = $currentIndex.'&conf=6&tab=AdminModules&revsubmitgooglereviews=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        // Google Product Review Feeds for Google Shopping









        ### reviewsemailssettings settings ###

        $revreviewsemailssettings = Tools::getValue("revreviewsemailssettings");

        if (Tools::strlen($revreviewsemailssettings)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(40);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(40);</script>';

            }

        }





        if (Tools::isSubmit('reviewsemailssettings'))

        {

            Configuration::updateValue($this->name.'mail', Tools::getValue('mail'));

            Configuration::updateValue($this->name.'noti', Tools::getValue('noti'));

            Configuration::updateValue($this->name.'img_size_em', Tools::getValue('img_size_em'));





            $url = $currentIndex.'&conf=6&tab=AdminModules&revreviewsemailssettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        ### reviewsemailssettings settings ###





        ### revresponseadminemailssettings settings ###

        $revresponseadminemailssettings = Tools::getValue("revresponseadminemailssettings");

        if (Tools::strlen($revresponseadminemailssettings)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(41);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(41);</script>';

            }

        }





        if (Tools::isSubmit('responseadminemailssettings'))

        {

            $languages = Language::getLanguages(false);

            foreach ($languages as $language){

                $i = $language['id_lang'];

                Configuration::updateValue($this->name.'subresem_'.$i, Tools::getValue('subresem_'.$i));

                Configuration::updateValue($this->name.'textresem_'.$i, Tools::getValue('textresem_'.$i));

                Configuration::updateValue($this->name.'emailreminder_'.$i, Tools::getValue('emailreminder_'.$i));



                Configuration::updateValue($this->name.'reminderok'.$this->_prefix_review.'_'.$i, Tools::getValue('reminderok'.$this->_prefix_review.'_'.$i));

                Configuration::updateValue($this->name.'thankyou'.$this->_prefix_review.'_'.$i, Tools::getValue('thankyou'.$this->_prefix_review.'_'.$i));



                Configuration::updateValue($this->name.'newrev'.$this->_prefix_review.'_'.$i, Tools::getValue('newrev'.$this->_prefix_review.'_'.$i));

                Configuration::updateValue($this->name.'subpubem_'.$i, Tools::getValue('subpubem_'.$i));

                Configuration::updateValue($this->name.'modrev'.$this->_prefix_review.'_'.$i, Tools::getValue('modrev'.$this->_prefix_review.'_'.$i));



                Configuration::updateValue($this->name.'abuserev'.$this->_prefix_review.'_'.$i, Tools::getValue('abuserev'.$this->_prefix_review.'_'.$i));



                Configuration::updateValue($this->name.'facvouc'.$this->_prefix_review.'_'.$i, Tools::getValue('facvouc'.$this->_prefix_review.'_'.$i));

                Configuration::updateValue($this->name.'revvouc'.$this->_prefix_review.'_'.$i, Tools::getValue('revvouc'.$this->_prefix_review.'_'.$i));



                Configuration::updateValue($this->name.'sugvouc'.$this->_prefix_review.'_'.$i, Tools::getValue('sugvouc'.$this->_prefix_review.'_'.$i));



            }





            $url = $currentIndex.'&conf=6&tab=AdminModules&revresponseadminemailssettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        ### revresponseadminemailssettings settings ###





        ### customerremindersettings settings ###

        $revcustomerremindersettings = Tools::getValue("revcustomerremindersettings");

        if (Tools::strlen($revcustomerremindersettings)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(42);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(42);</script>';

            }

        }





        if (Tools::isSubmit('customerremindersettings'))

        {

            Configuration::updateValue($this->name.'crondelay'.$this->_prefix_review, Tools::getValue('crondelay'.$this->_prefix_review));

            Configuration::updateValue($this->name.'cronnpost'.$this->_prefix_review, Tools::getValue('cronnpost'.$this->_prefix_review));



            Configuration::updateValue($this->name.'delay', Tools::getValue('delay'));

            Configuration::updateValue($this->name.'reminder', Tools::getValue('reminder'));



            // orderstatuses

            $orderstatuses = Tools::getValue('orderstatuses');

            $orderstatuses = implode(",",$orderstatuses);

            Configuration::updateValue($this->name.'orderstatuses', $orderstatuses);



            Configuration::updateValue($this->name.'delaysec'.$this->_prefix_review, Tools::getValue('delaysec'.$this->_prefix_review));

            Configuration::updateValue($this->name.'remindersec'.$this->_prefix_review, Tools::getValue('remindersec'.$this->_prefix_review));





            Configuration::updateValue($this->name.'remrevsec'.$this->_prefix_review, Tools::getValue('remrevsec'.$this->_prefix_review));







            $url = $currentIndex.'&conf=6&tab=AdminModules&revcustomerremindersettings=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        ### customerremindersettings settings ###











        

        // voucher settings

        

    	$voucherset = Tools::getValue("voucherset");

        if (Tools::strlen($voucherset)>0) {

        	if($this->_is16) {

                $this->_html .= '<script>init_tabs(5);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(50);</script>';

            }

        }

        

        if (Tools::isSubmit('vouchersettings'))

        {

        	

        	Configuration::updateValue($this->name.'vis_on', Tools::getValue('vis_on'));



            Configuration::updateValue($this->name.'is_show_min', Tools::getValue('is_show_min'));

	    	

        

   			$languages = Language::getLanguages(false);

        	foreach ($languages as $language){

    			$i = $language['id_lang'];

        		Configuration::updateValue($this->name.'coupondesc_'.$i, Tools::getValue('coupondesc_'.$i));

        	}

            Configuration::updateValue($this->name.'vouchercode', Tools::getValue('vouchercode'));

        	

        	Configuration::updateValue($this->name.'discount_type', Tools::getValue('discount_type'));

			Configuration::updateValue($this->name.'percentage_val', Tools::getValue('percentage_val'));

			

        	

        	foreach (Tools::getValue('sdamount') AS $id => $value){

				Configuration::updateValue('sdamount_'.(int)($id), (float)($value));

        	}

            

        	if(Tools::getValue('discount_type') == 2){

        		Configuration::updateValue($this->name.'tax', Tools::getValue('tax'));

        	}

        	

            Configuration::updateValue($this->name.'sdvvalid', Tools::getValue('sdvvalid'));

            

            

            if(Tools::getValue($this->name.'isminamount') == true){

	        	foreach (Tools::getValue('sdminamount') AS $id => $value){

					Configuration::updateValue('sdminamount_'.(int)($id), (float)($value));

	        	}

        	}

        	

            Configuration::updateValue($this->name.'isminamount', Tools::getValue($this->name.'isminamount'));

        	

            

            // category

            $categoryBox = Tools::getValue('categoryBox');

            $categoryBox = implode(",",$categoryBox);

            Configuration::updateValue($this->name.'catbox', $categoryBox);

            

        	

            // cumulable

            Configuration::updateValue($this->name.'cumulativeother', Tools::getValue('cumulativeother'));

			Configuration::updateValue($this->name.'cumulativereduc', Tools::getValue('cumulativereduc'));



            Configuration::updateValue($this->name.'highlight', Tools::getValue('highlight'));



        	$url = $currentIndex.'&conf=6&tab=AdminModules&voucherset=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

        	Tools::redirectAdmin($url);

        }

        

        // voucher settings





        // voucher facebook settings



        $vouchersetfb = Tools::getValue("vouchersetfb");

        if (Tools::strlen($vouchersetfb)>0) {

            if($this->_is16) {

                $this->_html .= '<script>init_tabs(55);</script>';

            } else {

                $this->_html .= '<script>init_tabs(3);</script>';

                $this->_html .= '<script>init_tabs_in(55);</script>';

            }

        }



        if (Tools::isSubmit('vouchersettingsfb'))

        {



            Configuration::updateValue($this->name.'vis_onfb', Tools::getValue('vis_onfb'));



            Configuration::updateValue($this->name.'is_show_minfb', Tools::getValue('is_show_minfb'));





            $languages = Language::getLanguages(false);

            foreach ($languages as $language){

                $i = $language['id_lang'];

                Configuration::updateValue($this->name.'coupondescfb_'.$i, Tools::getValue('coupondescfb_'.$i));

            }

            Configuration::updateValue($this->name.'vouchercodefb', Tools::getValue('vouchercodefb'));



            Configuration::updateValue($this->name.'discount_typefb', Tools::getValue('discount_typefb'));

            Configuration::updateValue($this->name.'percentage_valfb', Tools::getValue('percentage_valfb'));





            foreach (Tools::getValue('sdamountfb') AS $id => $value){

                Configuration::updateValue('sdamountfb_'.(int)($id), (float)($value));

            }



            if(Tools::getValue('discount_typefb') == 2){

                Configuration::updateValue($this->name.'taxfb', Tools::getValue('taxfb'));

            }



            Configuration::updateValue($this->name.'sdvvalidfb', Tools::getValue('sdvvalidfb'));





            if(Tools::getValue($this->name.'isminamountfb') == true){

                foreach (Tools::getValue('sdminamountfb') AS $id => $value){

                    Configuration::updateValue('sdminamountfb_'.(int)($id), (float)($value));

                }

            }



            Configuration::updateValue($this->name.'isminamountfb', Tools::getValue($this->name.'isminamountfb'));





            // category

            $categoryBox = Tools::getValue('categoryBoxfb');

            $categoryBox = implode(",",$categoryBox);

            Configuration::updateValue($this->name.'catboxfb', $categoryBox);





            // cumulable

            Configuration::updateValue($this->name.'cumulativeotherfb', Tools::getValue('cumulativeotherfb'));

            Configuration::updateValue($this->name.'cumulativereducfb', Tools::getValue('cumulativereducfb'));



            Configuration::updateValue($this->name.'highlightfb', Tools::getValue('highlightfb'));



            $url = $currentIndex.'&conf=6&tab=AdminModules&vouchersetfb=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);

        }



        // voucher facebook settings

   		

        

        

        // import comments

        if (Tools::isSubmit('submitcomments'))

        {

        	include_once(dirname(__FILE__).'/classes/importhelp.class.php');

			$obj = new importhelp();

        	

        	$obj->importComments();

			

        	$url = $currentIndex.'&tab=AdminModules&reviewsset=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

        	Tools::redirectAdmin($url);

        }

    	// import comments





        // Google Product Review Feeds for Google Shopping







        if (Tools::isSubmit('submitgooglereviews'))

        {

            include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

            $obj = new gsnipreviewhelp();





            $obj->generateGoogleReviews();



            $url = $currentIndex.'&conf=6&tab=AdminModules&reviewsset=1&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)($cookie->id_employee)).'';

            Tools::redirectAdmin($url);



        }

        // Google Product Review Feeds for Google Shopping



        

        







        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $this->_html .= $this->_displayForm16();

        } else {

            $this->_html .= $this->_displayForm13_14_15(array('errors'=>$errors));

        }



        return $this->_html;

    	

    }



    private function _error($data){

        $text = $data['text'];

        $_html = '';

        $_html .= '<div class="bootstrap">

		            <div class="alert alert-danger">

			            <button data-dismiss="alert" class="close" type="button">×</button>

					        '.$text.'

				        </div>

	                </div>';

        return $_html;

    }



    protected function addBackOfficeMedia()

    {

        $this->context->controller->addCSS($this->_path.'views/css/font-custom.min.css');

        //CSS files

        $this->context->controller->addCSS($this->_path.'views/css/menu16.css');



        $this->context->controller->addCSS($this->_path.'views/css/admin.css');



        // JS files

        $this->context->controller->addJs($this->_path.'views/js/menu16.js');



        $this->context->controller->addJs($this->_path.'views/js/reminder.js');







        $this->context->controller->addJs($this->_path.'views/js/storereviews.js');

        $this->context->controller->addJs($this->_path.'views/js/reminder-storereviews.js');





    }



    private function _displayForm16(){

        $_html = '';



        $_html .= '<div class="row">

    	<div class="col-lg-12">

    	<div class="row">';



        $_html .= '<div class="productTabs col-lg-12 col-md-3">



						<div class="list-group">';

        $_html .= '<ul class="nav nav-pills" id="navtabs16">



							    <li class="active"><a href="#welcome" data-toggle="tab" class="list-group-item"><i class="fa fa-home fa-lg"></i>&nbsp;'.$this->l('Welcome').'</a></li>

							    <li><a href="#googlesnippets" data-toggle="tab" class="list-group-item"><i class="fa fa-snippets fa-lg"></i>&nbsp;<i class="fa fa-richpins fa-lg"></i>&nbsp;'.$this->l('Google Rich Snippets & Rich Pins').'</a></li>';

							 $_html .= '<li><a href="#reviews" data-toggle="tab" class="list-group-item"><i class="fa fa-reviews fa-lg"></i>&nbsp;'.$this->l('Reviews').'</a></li>';

							 $_html .= '<li><a href="#shopreviews" data-toggle="tab" class="list-group-item"><i class="fa fa-star-storeviews fa-lg"></i>&nbsp;'.$this->l('Store reviews').'</a></li>';

                            $_html .= '<li><a href="#userprofileg" data-toggle="tab" class="list-group-item"><i class="fa fa-users fa-lg"></i>&nbsp;'.$this->l('User profile').'</a></li>';

							 $_html .= '<li><a href="#autoposts" data-toggle="tab" class="list-group-item"><i class="fa fa-facebook fa-lg"></i>&nbsp;'.$this->l('Social networks Integration').'</a></li>

							    <li><a href="#info" data-toggle="tab" class="list-group-item"><i class="fa fa-question-circle fa-lg"></i>&nbsp;'.$this->l('Help / Documentation').'</a></li>

							    <li><a href="http://addons.prestashop.com/en/2_community-developer?contributor=189784" target="_blank"  class="list-group-item"><img src="../modules/'.$this->name.'/views/img/mitrocops-logo.png"  />&nbsp;&nbsp;'.$this->l('Mitrocops Modules').'</a></li>





							</ul>';

        $_html .= '</div>

    				</div>';





        $_html .= '<div class="tab-content col-lg-12 col-md-9">';

        $_html .= '<div class="tab-pane active" id="welcome">'.$this->_welcome().'</div>';

        $_html .= '<div class="tab-pane" id="googlesnippets">'.$this->_googlesnippets16().'</div>';

        $_html .= '<div class="tab-pane" id="reviews">'.$this->_reviews16().'</div>';

        $_html .= '<div class="tab-pane" id="shopreviews">'.$this->_shopreviews16().'</div>';

        $_html .= '<div class="tab-pane" id="userprofileg">'.$this->_userprofileg16().'</div>';

        $_html .= '<div class="tab-pane" id="autoposts">'.$this->_autoposts16().'</div>';



        $_html .= '<div class="tab-pane" id="info">'.$this->_help_documentation().'</div>';

        $_html .= '</div>';







        $_html .= '</div></div></div>';









        return $_html;

    }





    private function _shopreviews16(){

        $_html = '';



        $_html .= '<div class="row">

    				<div class="col-lg-12">

    					<div class="row">';



        $_html .= '<div class="productTabs col-lg-2 col-md-3">



			<div class="list-group">

				<ul class="nav nav-pills nav-stacked" id="storereviewsnavtabs16">

				    <li class="active"><a href="#mainsettings" data-toggle="tab" class="list-group-item"><i class="fa fa-cogs fa-lg"></i>&nbsp;'.$this->l('Main settings').'</a></li>';



        $_html .= '<li><a href="#shopcustomerremindersettings" data-toggle="tab" class="list-group-item"><i class="fa fa-bell-o fa-lg"></i>&nbsp;'.$this->l('Customer Reminder settings').'</a></li>

				    <li><a href="#shopcustomerreminderstat" data-toggle="tab" class="list-group-item"><i class="fa fa-bar-chart fa-lg"></i>&nbsp;'.$this->l('Customer Reminder Statistics').'</a></li>

				     <li><a href="#emailsubjects" data-toggle="tab" class="list-group-item"><i class="fa fa-envelope-o fa-lg"></i>&nbsp;'.$this->l('Emails subjects settings').'</a></li>

                    <li><a href="#csvstore" data-toggle="tab" class="list-group-item"><i class="fa fa-table fa-lg"></i>&nbsp;'.$this->l('CSV import/export Settings').'</a></li>

                    <li><a href="#cronhelpstore" data-toggle="tab" class="list-group-item"><i class="fa fa-tasks fa-lg"></i>&nbsp;'.$this->l('CRON HELP STORE REVIEWS').'</a></li>';





        $_html .= '</ul>

				  </div>

		</div>';



        $_html .= '<div class="tab-content col-lg-10 col-md-9">';

        $_html .= '<div class="tab-pane active" id="mainsettings">'.$this->_mainsettings16().'</div>';



        $_html .= '<div class="tab-pane" id="shopcustomerremindersettings">'.$this->_shopcustomerremindersettings16().'</div>';

        $_html .= '<div class="tab-pane" id="shopcustomerreminderstat">'.$this->_shopcustomerreminderstat().'</div>';

        $_html .= '<div class="tab-pane" id="emailsubjects">'.$this->_emailsubjects16().'</div>';



        $_html .= '<div class="tab-pane" id="csvstore">'.$this->_csvImportExport().'</div>';

        $_html .= '<div class="tab-pane" id="cronhelpstore">'.$this->_cronhelp(array('url'=>'cron_shop_reviews')).'</div>';







        $_html .= '</div>';







        $_html .= '</div></div></div>';



        return $_html;

    }



    public function _csvImportExport(){

        include_once(dirname(__FILE__).'/classes/csvhelpti.class.php');

        $obj = new csvhelpti();

        $data_fields = $obj->getAvailableFields();

        $_html = '';





        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<div class="panel">



				<div class="panel-heading"><i class="fa fa-table fa-lg"></i>&nbsp;'.$this->l('CSV import/export Settings').'</div>';

        } else {

            $_html .= '<div class="bootstrap">';

            $_html .= '

					<h3 class="title-block-content"><i class="fa fa-table fa-lg"></i>&nbsp;'.$this->l('CSV import/export Settings').'</h3>';

        }





        $_html .= '<div class="input-group col-lg-12">';

        $_html .= '<label class="control-label"><b>'.$this->l('You must respect the following rules to upload the CSV Reviews correctly').':</b></label>';



        $_html .= '</div><br/>';



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<style type="text/css">

		    	@media (max-width: 992px) {';



            $i = 1;

            foreach($data_fields as $key => $item_field) {

                $_html .= '.table-responsive-row td.csv-ix:nth-of-type('.$i.'):before {

						content: "' . $key . '";

					}';

                $i++;

            }



            $_html .= '}

		    	</style>';

            $_html .= '<div class="table-responsive-row clearfix">';

        }





        $_html .= '<table class = "table csv-ix-table" width = "100%">

    		<thead>

			<tr class="nodrag nodrop">';

        $i = 1;

        foreach($data_fields as $key => $item_field) {

            $_html .='<th class="csv-ix-head"><span class="title_box">'.$key.'</span></th>';

            $i++;

        }





        $_html .= '</tr></thead>';







        $_html .= '<tbody><tr>';



        foreach($data_fields as $key => $item_field) {

            $name_filed = $item_field['name'];

            $example_field = $item_field['example'];

            $_html .= '<td class="csv-ix"><b>'.$name_filed.'</b><br/><br/>'.$example_field.'</td>';

        }



        $_html .= '</tr>';



        $_html .= '</tbody></table>';





        $_html .= '<br/>';

        $_html .= '<div class="input-group col-lg-12">';

        $_html .= '<label class="control-label">

                            <a href="../modules/'.$this->name.'/csv/example_store.csv" target="_blank"

                            class="a-underline '.(version_compare(_PS_VERSION_, '1.6', '>')?'btn btn-default pull':'button').'"

                            ><i class="fa fa-download fa-lg" aria-hidden="true"></i>&nbsp;'.$this->l('Click here to download an example of CSV file (you can write your reviews directly in it)').'</a>

                  </label>';



        $_html .= '</div><br/>';







        $_html .= '<div class="input-group col-lg-12">';

        $_html .= '<label class="control-label">

                           -&nbsp;'.$this->l('Save your file in CSV format. If you use Open Office, choose the option "Field separator: semi-colon"').'

                  </label>';



        $_html .= '</div><br/>';















        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '</div>';

            $_html .= '</div>';

        } else {

            $_html .= '</div>';

        }



        if(version_compare(_PS_VERSION_, '1.6', '<')){

            $_html .= '<div class="bootstrap">';

        }

        $_html .= '<div class="panel col-lg-6">';



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<div class="panel-heading"><i class="fa fa-download fa-lg"></i>&nbsp;'.$this->l('CSV Import').'</div>';

        } else {

            $_html .= '<h3 class="title-block-content"><i class="fa fa-download fa-lg"></i>&nbsp;'.$this->l('CSV Import').'</h3>';

        }



        $_html .= '<div class="input-group col-lg-6">';

        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" enctype="multipart/form-data">';

        $_html .= '<label class="control-label">';

        $_html .= '<input type="file" class="btn btn-default" id="csv_store" name="csv_store"/>';

        $_html .= '<br/>';

        $_html .= '<button name="store_csv" type="submit" class="'.(version_compare(_PS_VERSION_, '1.6', '>')?'btn btn-default pull':'button').'"

                    ><i class="fa fa-download fa-lg"></i>&nbsp;'.$this->l('Import reviews').'</button>';



        $_html .= '</label>';

        $_html .= '</form>';



        $_html .= '</div>';

        $_html .= '</div>';



        $_html .= '<div class="panel col-lg-6">';



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<div class="panel-heading"><i class="fa fa-upload fa-lg"></i>&nbsp;'.$this->l('CSV Export').'</div>';

        } else {

            $_html .= '<h3 class="title-block-content"><i class="fa fa-upload fa-lg"></i>&nbsp;'.$this->l('CSV Export').'</h3>';

        }





        $_html .= '<div class="input-group col-lg-6">';

        $_html .= '<label class="control-label">

                            <a href="'.$this->getURLMultiShop().'modules/'.$this->name.'/export_store.php?token='.$this->getokencron().'"

                            class="'.(version_compare(_PS_VERSION_, '1.6', '>')?'btn btn-default pull':'button').'"

        ><i class="fa fa-upload  fa-lg" aria-hidden="true"></i>&nbsp;'.$this->l('Export all reviews)').'</a>

                  </label>';



        $_html .= '</div>';



        $_html .= '</div>';



        if(version_compare(_PS_VERSION_, '1.6', '<')){

            $_html .= '</div>';

            $_html .= '<div class="clear"></div>';

        }



        return $_html;

    }



    public function _shopcustomerreminderstat(){





        include_once(dirname(__FILE__).'/classes/featureshelptestim.class.php');

        $obj = new featureshelptestim();



        $start_date_orders = Tools::getValue('start_date_orders'.$this->_prefix_shop_reviews);

        $end_date_orders = Tools::getValue('end_date_orders'.$this->_prefix_shop_reviews);







        $end_date = empty($end_date_orders)?date('Y-m-d H:i:s',strtotime("+1 day")):$end_date_orders;

        $start_date = empty($start_date_orders)?date('Y-m-d H:i:s',strtotime("-1 week")):$start_date_orders;







        $delayed_posts_data = $obj->getOrdersForReminder(array('start_date'=>$start_date,'end_date'=>$end_date));

        $delayed_posts = $delayed_posts_data['result'];

        $count_all = $delayed_posts_data['count_all'];









        return $this->drawShopReminderTasks(array('posts'=>$delayed_posts,'count_all'=>$count_all,

            'start_date'=>$start_date,'end_date'=>$end_date));

    }



    public function drawShopReminderTasks($data){





        $cookie = $this->context->cookie;

        $id_lang = (int)($cookie->id_lang);



        include_once(dirname(__FILE__).'/classes/featureshelptestim.class.php');

        $obj = new featureshelptestim();



        include_once(dirname(__FILE__).'/classes/storereviews.class.php');

        $obj_storereviews = new storereviews();









        $_html  = '';



        $_html .= '<div class="bootstrap"><div class="panel">';



        if(version_compare(_PS_VERSION_, '1.6', '>')) {

            $_html .= '<div class="panel-heading"><i class="fa fa-bar-chart fa-lg"></i>&nbsp;' . $this->l('Customer Reminder Statistics') . '</div>';

        } else {

            $_html .= '<h3 class="title-block-content"><i class="fa fa-bar-chart fa-lg"></i>&nbsp;' . $this->l('Customer Reminder Statistics') . '</h3>';

        }

        $_html .= '<div class="table-responsive-row clearfix">';











        $txt_accepted_order_statuses = '';

        $accepted_order_statuses = $obj->getAcceptedOrderStatuses(array('id_lang'=>$id_lang));

        foreach($accepted_order_statuses as $accepted_order_status){

            $color_background = $accepted_order_status['color'];

            $payment_order  = $accepted_order_status['name'];

            $txt_accepted_order_statuses .=

                '<span style="background-color:'.$color_background.';color:white;padding:2px;border-radius:5px;line-height:25px;margin:3px 3px 3px 0">

                                        '.$payment_order.'

                                    </span>';

        }



        $_html .= '<div class="input-group col-lg-12" style="float:left;margin-right:10px">';

        $_html .= '<label class="control-label">'.$this->l('Send emails only for the orders with the current selected status').': '.$txt_accepted_order_statuses.'</label>';



        $_html .= '</div>';



        $_html .= '<br/>';





        $is_enabled_reminder = Configuration::get($this->name.'reminder'.$this->_prefix_shop_reviews);

        $_html .= '<div class="input-group col-lg-12" style="float:left;margin-right:10px">';

        $_html .= '<label class="control-label">'.$this->l('Enable or Disable Send a review reminder email to customers').':

        <span style="background-color:#'.(($is_enabled_reminder)?'108510':'DC143C').';color:white;padding:3px;border-radius:5px;line-height:25px;margin:3px 0;text-align:center;font-weight:bold">

                                                ' . (($is_enabled_reminder)?$this->l('Yes'):$this->l('No')) . '

                                            </span>

        </label>';



        $_html .= '</div>';



        if($is_enabled_reminder) {

            $delay = Configuration::get($this->name . 'delay' . $this->_prefix_shop_reviews);

            $_html .= '<div class="input-group col-lg-12" style="float:left;margin-right:10px">';

            $_html .= '<label class="control-label">' . $this->l('Delay for sending reminder by email') . ':

        <span style="background-color:grey;color:white;padding:3px;border-radius:5px;line-height:25px;margin:3px 0;text-align:center;font-weight:bold">

                                                ' . $delay . '&nbsp;' . $this->l('days') . '

                                            </span>

        </label>';



            $_html .= '</div>';

        }









        $is_enabled_remindersec = Configuration::get($this->name.'remindersec'.$this->_prefix_shop_reviews);



        $_html .= '<div class="input-group col-lg-12" style="float:left;margin-right:10px">';

        $_html .= '<label class="control-label">'.$this->l('Enable or Disable Send a review reminder email to customers a second time?').':

        <span style="background-color:#'.(($is_enabled_remindersec)?'108510':'DC143C').';color:white;padding:3px;border-radius:5px;line-height:25px;margin:3px 0;text-align:center;font-weight:bold">

                                                ' . (($is_enabled_remindersec)?$this->l('Yes'):$this->l('No')) . '

                                            </span>

        </label>';



        $_html .= '</div>';



        if($is_enabled_remindersec) {

            $delay = Configuration::get($this->name . 'delaysec' . $this->_prefix_shop_reviews);

            $_html .= '<div class="input-group col-lg-12" style="float:left;margin-right:10px">';

            $_html .= '<label class="control-label">' . $this->l('Days after the first emails were sent') . ':

        <span style="background-color:grey;color:white;padding:3px;border-radius:5px;line-height:25px;margin:3px 0;text-align:center;font-weight:bold">

                                                ' . $delay . '&nbsp;' . $this->l('days') . '

                                            </span>

        </label>';



            $_html .= '</div>';

        }





        $is_enabled_remrevsec = Configuration::get($this->name.'remrevsec'.$this->_prefix_shop_reviews);



        $_html .= '<div class="input-group col-lg-12" style="float:left;margin-right:10px">';

        $_html .= '<label class="control-label">'.$this->l('Enable or Disable Send a review reminder email to customer when customer already write review in shop?').':

        <span style="background-color:#'.(($is_enabled_remrevsec)?'108510':'DC143C').';color:white;padding:3px;border-radius:5px;line-height:25px;margin:3px 0;text-align:center;font-weight:bold">

                                                ' . (($is_enabled_remrevsec)?$this->l('Yes'):$this->l('No')) . '

                                            </span>

        </label>';



        $_html .= '</div>';



        $_html .= '<div style="clear:both"></div><br/>';





        $end_date = $data['end_date'];

        $start_date = $data['start_date'];



        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" enctype="multipart/form-data">';

        $_html .= '<br/><div class="input-group col-lg-3" style="float:left;margin-right:10px">

            <span class="input-group-addon">'.$this->l('start date').'</span>

            <input id=""

                   type="text" data-hex="true"

                   class="item_datepicker" name="start_date_orders'.$this->_prefix_shop_reviews.'" value="'.$start_date.'" />

            <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>



        </div>

        <div class="input-group col-lg-3" style="float:left">

            <span class="input-group-addon">'.$this->l('end date').'</span>

            <input id=""

                   type="text" data-hex="true" class="item_datepicker"

                   name="end_date_orders'.$this->_prefix_shop_reviews.'" value="'.$end_date.'" />

            <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>



        </div>

        <input type="submit" value="'.$this->l('Filter orders').'"

               class="btn btn-success" style="float:left;margin-left:10px"/>

        <div style="clear:both"></div>';

        $_html .= '</form>';



        $_html .= '<br/>';





        $posts = $data['posts'];





        if(!empty($posts)){



            if(version_compare(_PS_VERSION_, '1.6', '>')){

                $_html .= '<style type="text/css">

		    	@media (max-width: 992px) {

							.table-responsive-row td.reminder-td:nth-of-type(1):before {

						content: "'.$this->l('ID').'";

					}';

                if(version_compare(_PS_VERSION_, '1.5', '>')) {

                    $_html .= '.table-responsive-row td.reminder-td:nth-of-type(2):before {

						content: "' . $this->l('Reference') . '";

					}';

                }

                $_html .= '.table-responsive-row td.reminder-td:nth-of-type(3):before {

						content: "'.$this->l('Adding date').'";

					}

							.table-responsive-row td.reminder-td:nth-of-type(4):before {

						content: "'.$this->l('Order status').'";

					}';



                if(version_compare(_PS_VERSION_, '1.5', '>')) {

                    $_html .= '.table-responsive-row td.reminder-td:nth-of-type(5):before {

						content: "' . $this->l('Shop') . '";

					}';

                }



                $_html .= '.table-responsive-row td.reminder-td:nth-of-type(6):before {

						content: "'.$this->l('Email sent once?').'";

					}

					.table-responsive-row td.reminder-td:nth-of-type(7):before {

						content: "'.$this->l('Email sent twice?').'";

					}

					.table-responsive-row td.reminder-td:nth-of-type(8):before {

						content: "'.$this->l('Review already written?').'";

					}



					}



		    	</style>';

            }



            $_html .= '<table class = "table" width = "100%" id="orders-for-reminder-store">

    		<thead>

			<tr class="nodrag nodrop">

				<th style="text-align:center"><span class="title_box">'.$this->l('ID').'</span></th>';

            if(version_compare(_PS_VERSION_, '1.5', '>')) {

                $_html .= '<th style="text-align:center"><span class="title_box">' . $this->l('Reference') . '</span></th>';

            }

            $_html .= '<th style="text-align:center"><span class="title_box">'.$this->l('Adding date').'</span></th>

				<th style="text-align:center"><span class="title_box">'.$this->l('Order status').'</span></th>';

            if(version_compare(_PS_VERSION_, '1.5', '>')){

                $_html .= '<th style="text-align:center"><span class="title_box">'.$this->l('Shop').'</span></th>';

            }

            $_html .= '

                <th style="text-align:center"><span class="title_box">'.$this->l('Email sent once?').'</span></th>

				<th style="text-align:center"><span class="title_box">'.$this->l('Email sent twice?').'</span></th>

				<th style="text-align:center"><span class="title_box">'.$this->l('Review already written?').'</span></th>





			</tr></thead><tbody>';





            foreach($posts as $item){





                $order_id = $item['order_id'];



                $admin_url_to_order= 'index.php?'.(version_compare(_PS_VERSION_, '1.5', '>')?'controller':'tab').'=AdminOrders&id_order='.$order_id.'&vieworder&token='.Tools::getAdminToken('AdminOrders'.(int)(Tab::getIdFromClassName('AdminOrders')).(int)($cookie->id_employee)).'';



                $date_add = $item['date_add'];



                $id_customer = $item['customer_id'];





                $date_send = $item['date_send'];

                $date_send_second = $item['date_send_second'];



                $data_info_about_order = $obj->getOrderInfo(

                    array('order_id'=>$order_id, 'id_lang'=>$id_lang,



                    )

                );



                //if($order_id == 5)

                ///    echo "<pre>"; var_dump($data_info_about_order);exit;



                $reference_order = isset($data_info_about_order[0]['reference'])?$data_info_about_order[0]['reference']:'';

                $payment_order = isset($data_info_about_order[0]['order_status_lng'])?$data_info_about_order[0]['order_status_lng']:'';

                $color_background = isset($data_info_about_order[0]['color'])?$data_info_about_order[0]['color']:'';



                //108510 - green

                //DC143C - red



                $is_add_review = $obj_storereviews->isExistsReviewByCustomer(array('id_customer'=>$id_customer));

                $product_text = '<span style="background-color:#'.(($is_add_review>0)?'108510':'DC143C').';color:white;padding:3px;border-radius:5px;line-height:25px;margin:3px 0;text-align:center">

                                                ' . (($is_add_review>0)?$this->l('Yes'):$this->l('No')) . '

                                            </span>





                                        <br/>';









                $_html .=

                    '<tr>

					<td class="reminder-td">'.$order_id.'</td>';

                if(version_compare(_PS_VERSION_, '1.5', '>')) {

                    $_html .= '<td class="reminder-td"><a href="' . $admin_url_to_order . '" target="_blank" style="text-decoration:underline">' . $reference_order . '</a></td>';

                }

                $_html .= '<td class="reminder-td">'.$date_add.'</td>

					<td class="reminder-td">

					                <span style="background-color:'.$color_background.';color:white;padding:4px;border-radius:5px;line-height:25px;margin:3px 0">

                                        '.$payment_order.'

                                    </span>

					</td>';



                if(version_compare(_PS_VERSION_, '1.5', '>')){

                    $shops = Shop::getShops();

                    $name_shop = '';

                    foreach($shops as $_shop){

                        $id_shop_lists = $_shop['id_shop'];

                        if($id_shop_lists == $item['id_shop'])

                            $name_shop = $_shop['name'];

                    }



                    $_html .= '<td class="reminder-td">'.$name_shop.'</td>';

                }





                $_html .= '<td class="reminder-td text-align-left" id="first-time-store-'.$order_id.'">';



                if(empty($date_send)) {

                    $_html .= '<img src="../modules/' . $this->name . '/views/img/no_ok.gif"/>&nbsp;&nbsp;';

                    $_html .= '<a class="btn btn-success" href="javascript://" title="' . $this->l('Send order manually') . '"

                            onclick="statusdelayed = confirm(\'' . Tools::htmlentitiesUTF8($this->l('Are you sure to want Send order manually ')) . '\');if(!statusdelayed)return false;sendReminderStore(\'first\',\'' . $order_id . '\',\''.$this->getURLMultiShop().'\' );"

                            >';

                    $_html .= $this->l('Send order manually');

                    $_html .= '</a>';



                } else {

                    $_html .= '<img src="../modules/' . $this->name . '/views/img/ok.gif"/> &nbsp;&nbsp;'.$date_send;



                }



                $_html .= '</td>';





                $_html .= '<td class="reminder-td text-align-left" id="second-time-store-'.$order_id.'">';



                if(empty($date_send_second) && !empty($date_send)) {

                    $_html .= '<img src="../modules/' . $this->name . '/views/img/no_ok.gif"/>&nbsp;&nbsp;';

                    $_html .= '<a class="btn btn-success" href="javascript://" title="' . $this->l('Send order manually') . '"

                            onclick="statusdelayed = confirm(\'' . Tools::htmlentitiesUTF8($this->l('Are you sure to want Send order manually ')) . '\');if(!statusdelayed)return false;sendReminderStore(\'second\',\'' . $order_id . '\',\''.$this->getURLMultiShop().'\' );"

                            >';

                    $_html .= $this->l('Send order manually');

                    $_html .= '</a>';



                } else {

                    if(empty($date_send)) {

                        $_html .= '<img src="../modules/' . $this->name . '/views/img/no_ok.gif"/>';

                    }else {

                        $_html .= '<img src="../modules/' . $this->name . '/views/img/ok.gif" /> &nbsp;&nbsp;' . $date_send_second;

                    }



                }



                $_html .= '</td>';







                $_html .= '<td class="reminder-td" >'.$product_text.'</td>';





                $_html .= '</tr>';







            }



            $_html .= '</tbody>';



            $_html .= '</table>';





        } else {



            $_html .= '<div style="border:1px solid red; padding:10px; width:100%; text-align:center;font-weight:bold;margin-bottom:10px">

     				'.$this->l('There is no orders for customer reminder').'

     				</div>';





        }



        //if(version_compare(_PS_VERSION_, '1.6', '>')){

        $_html .= '</div>';

        $_html .= '</div></div>';

        //}

        return $_html;

    }







    private function _emailsubjects16(){







        $fields_form = array(

            'form'=> array(

                //'tinymce' => FALSE,

                'legend' => array(

                    'title' => $this->l('Emails subjects settings'),

                    'icon' => 'fa fa-envelope-o fa-lg'

                ),

                'input' => array(







                    array(

                        'type' => 'text',

                        'label' => $this->l('Email reminder subject'),

                        'name' => 'emrem'.$this->_prefix_shop_reviews,

                        'id' => 'emrem'.$this->_prefix_shop_reviews,

                        'lang' => TRUE,

                        'desc' => $this->l('You can customize the subject of the e-mail here.').' '

                            . $this->l('If you wish to customize the e-mail message itself, you will need to manually edit the files in the')

                            .'  "mails" '

                            . $this->l(' folder inside the')

                            .' "'.$this->name.'" '

                            .$this->l('module folder, for each language, both the text and the HTML version each time. ')

                            .'<br/><br/><b>'.$this->l('FTP Location').': /modules/'.$this->name.'/mails/{YOUR_ISO_CODE_LANGUAGE}/customer-reminder-ti.html</b>'

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Admin confirmation subject, when emails requests on the reviews was successfully sent'),

                        'name' => 'reminderok'.$this->_prefix_shop_reviews,

                        'id' => 'reminderok'.$this->_prefix_shop_reviews,

                        'lang' => TRUE,

                        'desc' => $this->l('You can customize the subject of the e-mail here.').' '

                            . $this->l('If you wish to customize the e-mail message itself, you will need to manually edit the files in the')

                            .'  "mails" '

                            . $this->l(' folder inside the')

                            .' "'.$this->name.'" '

                            .$this->l('module folder, for each language, both the text and the HTML version each time. ')

                            .'<br/><br/><b>'.$this->l('FTP Location').': /modules/'.$this->name.'/mails/{YOUR_ISO_CODE_LANGUAGE}/customer-reminder-admin-ti.html</b>'

                    ),







                    array(

                        'type' => 'text',

                        'label' => $this->l('Thank you subject'),

                        'name' => 'thankyou'.$this->_prefix_shop_reviews,

                        'id' => 'thankyou'.$this->_prefix_shop_reviews,

                        'lang' => TRUE,

                        'desc' => $this->l('You can customize the subject of the e-mail here.').' '

                            . $this->l('If you wish to customize the e-mail message itself, you will need to manually edit the files in the')

                            .'  "mails" '

                            . $this->l(' folder inside the')

                            .' "'.$this->name.'" '

                            .$this->l('module folder, for each language, both the text and the HTML version each time. ')

                            .'<br/><br/><b>'.$this->l('FTP Location').': /modules/'.$this->name.'/mails/{YOUR_ISO_CODE_LANGUAGE}/testimony-thank-you.html</b>'

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('New Store review subject'),

                        'name' => 'newtest'.$this->_prefix_shop_reviews,

                        'id' => 'newtest'.$this->_prefix_shop_reviews,

                        'lang' => TRUE,

                        'desc' => $this->l('You can customize the subject of the e-mail here.').' '

                            . $this->l('If you wish to customize the e-mail message itself, you will need to manually edit the files in the')

                            .'  "mails" '

                            . $this->l(' folder inside the')

                            .' "'.$this->name.'" '

                            .$this->l('module folder, for each language, both the text and the HTML version each time. ')

                            .'<br/><br/><b>'.$this->l('FTP Location').': /modules/'.$this->name.'/mails/{YOUR_ISO_CODE_LANGUAGE}/testimony.html</b>'

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Response on the Store review subject'),

                        'name' => 'resptest'.$this->_prefix_shop_reviews,

                        'id' => 'resptest'.$this->_prefix_shop_reviews,

                        'lang' => TRUE,

                        'desc' => $this->l('You can customize the subject of the e-mail here.').' '

                            . $this->l('If you wish to customize the e-mail message itself, you will need to manually edit the files in the')

                            .'  "mails" '

                            . $this->l(' folder inside the')

                            .' "'.$this->name.'" '

                            .$this->l('module folder, for each language, both the text and the HTML version each time. ')

                            .'<br/><br/><b>'.$this->l('FTP Location').': /modules/'.$this->name.'/mails/{YOUR_ISO_CODE_LANGUAGE}/response-testim.html</b>'

                    ),











                ),







            ),





        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );









        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'emailsubjectssettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesEmailSubjectsSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );















        return  $helper->generateForm(array($fields_form,$fields_form1));

    }



    public function getConfigFieldsValuesEmailSubjectsSettings(){

        $languages = Language::getLanguages(false);

        $fields_emrem = array();

        $fields_thankyou = array();

        $fields_reminderok = array();

        $fields_newtest = array();

        $fields_resptest = array();



        foreach ($languages as $lang)

        {

            $fields_emrem[$lang['id_lang']] =  Configuration::get($this->name.'emrem'.$this->_prefix_shop_reviews.'_'.$lang['id_lang']);

            $fields_thankyou[$lang['id_lang']] =  Configuration::get($this->name.'thankyou'.$this->_prefix_shop_reviews.'_'.$lang['id_lang']);

            $fields_reminderok[$lang['id_lang']] =  Configuration::get($this->name.'reminderok'.$this->_prefix_shop_reviews.'_'.$lang['id_lang']);

            $fields_newtest[$lang['id_lang']] =  Configuration::get($this->name.'newtest'.$this->_prefix_shop_reviews.'_'.$lang['id_lang']);

            $fields_resptest[$lang['id_lang']] =  Configuration::get($this->name.'resptest'.$this->_prefix_shop_reviews.'_'.$lang['id_lang']);



        }



        $data_config = array(

            'emrem'.$this->_prefix_shop_reviews => $fields_emrem,

            'thankyou'.$this->_prefix_shop_reviews => $fields_thankyou,

            'reminderok'.$this->_prefix_shop_reviews => $fields_reminderok,

            'newtest'.$this->_prefix_shop_reviews => $fields_newtest,

            'resptest'.$this->_prefix_shop_reviews => $fields_resptest,



        );

        //echo "<pre>"; var_dumP($data_config); exit;



        return $data_config;



    }





    private function _shopcustomerremindersettings16(){



        include_once(dirname(__FILE__).'/classes/featureshelptestim.class.php');

        $obj_featureshelp = new featureshelptestim();

        $cookie = $this->context->cookie;

        $id_lang = (int)($cookie->id_lang);









        $fields_form = array(

            'form'=> array(

                //'tinymce' => FALSE,

                'legend' => array(

                    'title' => $this->l('Customer Reminder settings'),

                    'icon' => 'fa fa-bell-o fa-lg'

                ),

                'input' => array(



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Send a review reminder by email to customers'),

                        'name' => 'reminder'.$this->_prefix_shop_reviews,



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),



                        'desc' => $this->l('If activated, when a customer purchases a product on your shop, an e-mail will be sent to him after X days (specify below after selecting "yes" here) to invite him to rate the shop.').'

                                    <br/><br/>

                                    <b>'.$this->l('IMPORTANT NOTE').'</b>: '.$this->l('This requires to set a CRON task on your server. ').'

                                    <a style="text-decoration:underline;font-weight:bold;color:red" onclick="tabs_custom(110)" href="javascript:void(0)">'.$this->l('CRON HELP STORE REVIEWS').'</a>

                                    <br/><br/>

                                    <b>'.$this->l('Your CRON URL to call').'</b>:&nbsp;

                                    <a href="'.$this->getURLMultiShop().'modules/'.$this->name.'/cron_shop_reviews.php?token='.$this->getokencron().'"

                                    style="text-decoration:underline;font-weight:bold" target="_blank"

                                    >'.$this->getURLMultiShop().'modules/'.$this->name.'/cron_shop_reviews.php?token='.$this->getokencron().'</a>',

                    ),





                    array(

                        'type' => 'text_custom_delay',

                        'label' => $this->l('Delay between each email in seconds'),

                        'name' => 'crondelay'.$this->_prefix_shop_reviews,

                        'value'=>(int)Configuration::get($this->name.'crondelay'.$this->_prefix_shop_reviews),

                        'desc' => $this->l('The delay is intended in order to your server is not blocked the email function'),

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Number of emails for each cron call'),

                        'name' => 'cronnpost'.$this->_prefix_shop_reviews,

                        'desc' => $this->l('This will reduce the load on your server. The more powerful your server - the more emails you can do for each CRON call!'),

                    ),



                    array(

                        'type' => 'text_custom_delay_reminder',

                        'label' => $this->l('Delay for sending reminder by email'),

                        'name' => 'delay'.$this->_prefix_shop_reviews,

                        'value'=> Configuration::get($this->name.'delay'.$this->_prefix_shop_reviews),

                        'desc'=>$this->l('We recommend you enter at least 7 days here to have enough time to process the order and for the customer to receive it.')

                    ),











                    array(

                        'type' => 'text_custom_orders_import_storereviews',

                        'label' => $this->l('Import your old orders'),

                        'name' => 'orders_import_storereviews',

                        'end_date' =>date('Y-m-d H:i:s'),

                        'host_url'=>$this->getURLMultiShop(),

                        'desc'=>$this->l('Please select a date. All orders placed between that start date and end date (today) will be imported.')

                    ),



                    array(

                        'type' => 'text_custom_order_statuses',

                        'label' => $this->l('Send emails only for the orders with the current selected status'),

                        'name' => 'sel_statuses'.$this->_prefix_shop_reviews,

                        'value'=> $obj_featureshelp->getOrderStatuses(array('id_lang'=>$id_lang)),

                        'orderstatuses'=> explode(",",Configuration::get($this->name.'orderstatuses'.$this->_prefix_shop_reviews)),

                        'desc'=>$this->l('This feature prevents customers to leave a review for orders that they haven\'t received yet. You must choose at least one status.')

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Send a review reminder by email to customer when customer already write review in shop?'),

                        'name' => 'remrevsec'.$this->_prefix_shop_reviews,



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),







                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Send a review reminder by email to customers a second time?'),

                        'name' => 'remindersec'.$this->_prefix_shop_reviews,



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),



                        'desc' => $this->l('This feature allows you to send a second time the opinion request emails that have already been sent.'),



                    ),



                    array(

                        'type' => 'text_custom_delay_reminder',

                        'label' => $this->l('Days after the first emails were sent'),

                        'name' => 'delaysec'.$this->_prefix_shop_reviews,

                        'value'=> Configuration::get($this->name.'delaysec'.$this->_prefix_shop_reviews),



                    ),







                ),







            ),





        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );









        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'shopcustomerremindersettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesCustomerreminderShopSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );





        $_html = '';

        $_html .= "<script type=\"text/javascript\">

    	$('document').ready( function() {



    		$( \"label[for='remindersecti_on']\" ).click(function() {

                $( \"#delaysec".$this->_prefix_shop_reviews."\" ).parent().parent().parent().show(200);



			});



            $( \"label[for='remindersecti_off']\" ).click(function() {

    	        $( \"#delaysec".$this->_prefix_shop_reviews."\" ).parent().parent().parent().hide(200);

    	    });



    	    if(".(int)Configuration::get($this->name.'remindersec'.$this->_prefix_shop_reviews)." == 0){

    	        $( \"#delaysec".$this->_prefix_shop_reviews."\" ).parent().parent().parent().hide(200);

    	    }



    	});



    	</script>";







        return  $_html.$helper->generateForm(array($fields_form,$fields_form1));

    }



    public function getConfigFieldsValuesCustomerreminderShopSettings(){





        $data_config = array(



            'reminder'.$this->_prefix_shop_reviews => Configuration::get($this->name.'reminder'.$this->_prefix_shop_reviews),



            'remindersec'.$this->_prefix_shop_reviews => Configuration::get($this->name.'remindersec'.$this->_prefix_shop_reviews),



            'remrevsec'.$this->_prefix_shop_reviews => Configuration::get($this->name.'remrevsec'.$this->_prefix_shop_reviews),





            'cronnpost'.$this->_prefix_shop_reviews=>(int)Configuration::get($this->name.'cronnpost'.$this->_prefix_shop_reviews),

        );

        //echo "<pre>"; var_dumP($data_config); exit;



        return $data_config;



    }



    private function _mainsettings16(){

        $fields_form = array(

            'form'=> array(

                'legend' => array(

                    'title' => $this->l('Main Settings'),

                    'icon' => 'fa fa-cogs fa-lg'

                ),

                'input' => array(



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Store reviews functional on your site'),

                        'name' => 'is_storerev',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),









                    array(

                        'type' => 'text',

                        'label' => $this->l('The number of items in the "Store reviews Block":'),

                        'name' => 'tlast',

                        'class' => ' fixed-width-sm',



                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Store reviews per Page:'),

                        'name' => 'perpage'.$this->_prefix_shop_reviews,

                        'class' => ' fixed-width-sm',



                    ),



                    array(

                        'type' => 'color',

                        'lang' => true,

                        'label' => $this->l('Store reviews title color. Only in the positions: Left Side and Right Side'),

                        'name' => $this->name.'BGCOLOR_TIT',

                        'desc' => $this->l('You can enter Hexadecimal color code for the title like #000000')

                    ),



                    array(

                        'type' => 'color',

                        'lang' => true,

                        'label' => $this->l('Store reviews block background color'),

                        'name' => $this->name.'BGCOLOR_T',

                        'desc' => $this->l('You can enter Hexadecimal color code for the background like #000000')

                    ),



                    array(

                        'type' => 'block_radio_buttons_reviews_custom',

                        'label' => $this->l('Who can add review?'),



                        'name' => 'block_radio_buttons_reviews_custom',

                        'values'=> array(

                            'value' => Configuration::get($this->name.'whocanadd'.$this->_prefix_shop_reviews)

                        ),



                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable Avatar in the submit form'),

                        'name' => 'is_avatar',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable Captcha in the submit form'),

                        'name' => 'is_captcha'.$this->_prefix_shop_reviews,



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable Web address in the submit form:'),

                        'name' => 'is_web',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable Company in the submit form:'),

                        'name' => 'is_company',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable Address in the submit form:'),

                        'name' => 'is_addr',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable Country in the submit form:'),

                        'name' => 'is_country',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable City in the submit form:'),

                        'name' => 'is_city',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Admin email:'),

                        'name' => 'mail'.$this->_prefix_shop_reviews,

                        'id' => 'mail'.$this->_prefix_shop_reviews,

                        'lang' => FALSE,



                    ),



                    array(

                        'type' => 'checkbox_custom_store',

                        'label' => $this->l('E-mail notification:'),

                        'name' => 'noti'.$this->_prefix_shop_reviews,

                        'values' => array(

                            'value' => (int)Configuration::get($this->name.'noti'.$this->_prefix_shop_reviews)

                        ),

                    ),







                    array(

                        'type' => 'checkbox_custom_blocks_store',

                        'label' => $this->l('Position Store reviews Block:'),

                        'name' => 'r_pos_store_reviews_block',

                        'values' => array(

                            'query' => array(



                                array(

                                    'id' => 't_left',

                                    'name' => $this->l('Left column'),

                                    'val' => 1,

                                    'mobile'=>Configuration::get($this->name.'mt_left'),

                                    'site'=>Configuration::get($this->name.'st_left'),

                                ),





                                array(

                                    'id' => 't_right',

                                    'name' => $this->l('Right column'),

                                    'val' => 1,

                                    'mobile'=>Configuration::get($this->name.'mt_right'),

                                    'site'=>Configuration::get($this->name.'st_right'),

                                ),





                                array(

                                    'id' => 't_footer',

                                    'name' => $this->l('Footer'),

                                    'val' => 1,

                                    'mobile'=>Configuration::get($this->name.'mt_footer'),

                                    'site'=>Configuration::get($this->name.'st_footer'),

                                ),



                                array(

                                    'id' => 't_home',

                                    'name' => $this->l('Home'),

                                    'val' => 1,

                                    'mobile'=>Configuration::get($this->name.'mt_home'),

                                    'site'=>Configuration::get($this->name.'st_home'),

                                ),

                                array(

                                    'id' => 't_leftside',

                                    'name' => $this->l('Left Side'),

                                    'val' => 1,

                                    'mobile'=>Configuration::get($this->name.'mt_leftside'),

                                    'site'=>Configuration::get($this->name.'st_leftside'),

                                ),



                                array(

                                    'id' => 't_rightside',

                                    'name' => $this->l('Right Side'),

                                    'val' => 1,

                                    'mobile'=>Configuration::get($this->name.'mt_rightside'),

                                    'site'=>Configuration::get($this->name.'st_rightside'),

                                ),











                            ),

                            'id' => 'id',

                            'name' => 'name'

                        ),



                    ),



                    array(

                        'type' => 'checkbox_custom_blocks',

                        'label' => $this->l('Enable Google Rich snippets in the following places:'),

                        'name' => 'r_google_rich_snippets_in_places',

                        'values' => array(

                            'query' => array(



                                array(

                                    'id' => 't_lefts',

                                    'name' => $this->l('Left column'),

                                    'val' => 1

                                ),





                                array(

                                    'id' => 't_rights',

                                    'name' => $this->l('Right column'),

                                    'val' => 1

                                ),





                                array(

                                    'id' => 't_footers',

                                    'name' => $this->l('Footer'),

                                    'val' => 1

                                ),



                                array(

                                    'id' => 't_homes',

                                    'name' => $this->l('Home'),

                                    'val' => 1

                                ),

                                array(

                                    'id' => 't_leftsides',

                                    'name' => $this->l('Left Side'),

                                    'val' => 1

                                ),



                                array(

                                    'id' => 't_rightsides',

                                    'name' => $this->l('Right Side'),

                                    'val' => 1

                                ),

                                array(

                                    'id' => 't_tpages',

                                    'name' => $this->l('Store reviews page'),

                                    'val' => 1

                                ),





                            ),

                            'id' => 'id',

                            'name' => 'name'

                        ),



                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable RSS Feed:'),

                        'name' => 'rssontestim',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Number of items in RSS Feed:'),

                        'name' => 'n_rssitemst',

                        'class' => ' fixed-width-sm',



                    ),









                ),

            ),

        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );



        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'submit_testimonials';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesTestimonialsSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );







        $_html = '';

        $_html .= "<script type=\"text/javascript\">

    	$('document').ready( function() {



    		$( \"label[for='is_storerev_on']\" ).click(function() {

                $( \"a[href='#shopcustomerremindersettings']\" ).css('display','block');

                $( \"a[href='#shopcustomerreminderstat']\" ).css('display','block');

                $( \"a[href='#emailsubjects']\" ).css('display','block');

                $( \"a[href='#csvstore']\" ).css('display','block');

                $( \"a[href='#cronhelpstore']\" ).css('display','block');



                $( \"input[name='tlast']\").parent().parent().show(200);

                $( \"input[name='perpage".$this->_prefix_shop_reviews."']\").parent().parent().show(200);

                $( \"input[name='".$this->name."BGCOLOR_T']\").parent().parent().parent().parent().parent().parent().show(200);

                $( \"input[name='".$this->name."BGCOLOR_TIT']\").parent().parent().parent().parent().parent().parent().show(200);

                $( \"input[name='whocanadd']\").parent().parent().parent().parent().parent().parent().parent().show(200);

                $( \"input[name='is_avatar']\").parent().parent().parent().show(200);

                $( \"input[name='is_captcha".$this->_prefix_shop_reviews."']\").parent().parent().parent().show(200);

                $( \"input[name='is_web']\").parent().parent().parent().show(200);

                $( \"input[name='is_company']\").parent().parent().parent().show(200);

                $( \"input[name='is_addr']\").parent().parent().parent().show(200);

                $( \"input[name='is_country']\").parent().parent().parent().show(200);

                $( \"input[name='is_city']\").parent().parent().parent().show(200);

                $( \"input[name='mail".$this->_prefix_shop_reviews."']\").parent().parent().show(200);

                $( \"input[name='noti".$this->_prefix_shop_reviews."']\").parent().parent().show(200);



                $( \".r_pos_store_reviews_block\").parent().show(200);

                $( \".r_google_rich_snippets_in_places\").parent().show(200);



                $( \"input[name='rssontestim']\").parent().parent().parent().show(200);

                $( \"input[name='n_rssitemst']\").parent().parent().show(200);





			});



            $( \"label[for='is_storerev_off']\" ).click(function() {

    	        $( \"a[href='#shopcustomerremindersettings']\" ).css('display','none');

                $( \"a[href='#shopcustomerreminderstat']\" ).css('display','none');

                $( \"a[href='#emailsubjects']\" ).css('display','none');

                $( \"a[href='#csvstore']\" ).css('display','none');

                $( \"a[href='#cronhelpstore']\" ).css('display','none');



                $( \"input[name='tlast']\").parent().parent().hide(200);

                $( \"input[name='perpage".$this->_prefix_shop_reviews."']\").parent().parent().hide(200);

                $( \"input[name='".$this->name."BGCOLOR_T']\").parent().parent().parent().parent().parent().parent().hide(200);

                $( \"input[name='".$this->name."BGCOLOR_TIT']\").parent().parent().parent().parent().parent().parent().hide(200);

                $( \"input[name='whocanadd']\").parent().parent().parent().parent().parent().parent().parent().hide(200);

                $( \"input[name='is_avatar']\").parent().parent().parent().hide(200);

                $( \"input[name='is_captcha".$this->_prefix_shop_reviews."']\").parent().parent().parent().hide(200);

                $( \"input[name='is_web']\").parent().parent().parent().hide(200);

                $( \"input[name='is_company']\").parent().parent().parent().hide(200);

                $( \"input[name='is_addr']\").parent().parent().parent().hide(200);

                $( \"input[name='is_country']\").parent().parent().parent().hide(200);

                $( \"input[name='is_city']\").parent().parent().parent().hide(200);

                $( \"input[name='mail".$this->_prefix_shop_reviews."']\").parent().parent().hide(200);

                $( \"input[name='noti".$this->_prefix_shop_reviews."']\").parent().parent().hide(200);



                $( \".r_pos_store_reviews_block\").parent().hide(200);

                $( \".r_google_rich_snippets_in_places\").parent().hide(200);



                $( \"input[name='rssontestim']\").parent().parent().parent().hide(200);

                $( \"input[name='n_rssitemst']\").parent().parent().hide(200);

    	    });



    	    if(".(int)Configuration::get($this->name.'is_storerev')." == 0){

    	        $( \"a[href='#shopcustomerremindersettings']\" ).css('display','none');

                $( \"a[href='#shopcustomerreminderstat']\" ).css('display','none');

                $( \"a[href='#emailsubjects']\" ).css('display','none');

                $( \"a[href='#csvstore']\" ).css('display','none');

                $( \"a[href='#cronhelpstore']\" ).css('display','none');



                $( \"input[name='tlast']\").parent().parent().hide(200);

                $( \"input[name='perpage".$this->_prefix_shop_reviews."']\").parent().parent().hide(200);

                $( \"input[name='".$this->name."BGCOLOR_T']\").parent().parent().parent().parent().parent().parent().hide(200);

                $( \"input[name='".$this->name."BGCOLOR_TIT']\").parent().parent().parent().parent().parent().parent().hide(200);

                $( \"input[name='whocanadd']\").parent().parent().parent().parent().parent().parent().parent().hide(200);

                $( \"input[name='is_avatar']\").parent().parent().parent().hide(200);

                $( \"input[name='is_captcha".$this->_prefix_shop_reviews."']\").parent().parent().parent().hide(200);

                $( \"input[name='is_web']\").parent().parent().parent().hide(200);

                $( \"input[name='is_company']\").parent().parent().parent().hide(200);

                $( \"input[name='is_addr']\").parent().parent().parent().hide(200);

                $( \"input[name='is_country']\").parent().parent().parent().hide(200);

                $( \"input[name='is_city']\").parent().parent().parent().hide(200);

                $( \"input[name='mail".$this->_prefix_shop_reviews."']\").parent().parent().hide(200);

                $( \"input[name='noti".$this->_prefix_shop_reviews."']\").parent().parent().hide(200);



                $( \".r_pos_store_reviews_block\").parent().hide(200);

                $( \".r_google_rich_snippets_in_places\").parent().hide(200);



                $( \"input[name='rssontestim']\").parent().parent().parent().hide(200);

                $( \"input[name='n_rssitemst']\").parent().parent().hide(200);

    	    }



    	});



    	</script>";



        return  $_html . $helper->generateForm(array($fields_form,$fields_form1));

    }



    public function getConfigFieldsValuesTestimonialsSettings(){



        $data_config = array(

            'is_storerev'=> Configuration::get($this->name.'is_storerev'),





            $this->name.'BGCOLOR_T'=>Configuration::get($this->name.'BGCOLOR_T'),

            $this->name.'BGCOLOR_TIT'=>Configuration::get($this->name.'BGCOLOR_TIT'),



            'tlast'=> Configuration::get($this->name.'tlast'),

            'perpage'.$this->_prefix_shop_reviews=> Configuration::get($this->name.'perpage'.$this->_prefix_shop_reviews),



            'is_avatar'=>Configuration::get($this->name.'is_avatar'),

            'is_captcha'.$this->_prefix_shop_reviews=>Configuration::get($this->name.'is_captcha'.$this->_prefix_shop_reviews),

            'is_web'=>Configuration::get($this->name.'is_web'),

            'is_company'=>Configuration::get($this->name.'is_company'),

            'is_addr'=>Configuration::get($this->name.'is_addr'),

            'is_country'=>Configuration::get($this->name.'is_country'),

            'is_city'=>Configuration::get($this->name.'is_city'),





            'mail'.$this->_prefix_shop_reviews=>Configuration::get($this->name.'mail'.$this->_prefix_shop_reviews),



            't_left'=>Configuration::get($this->name.'t_left'),

            't_right'=>Configuration::get($this->name.'t_right'),

            't_footer'=>Configuration::get($this->name.'t_footer'),

            't_home'=>Configuration::get($this->name.'t_home'),

            't_leftside'=>Configuration::get($this->name.'t_leftside'),

            't_rightside'=>Configuration::get($this->name.'t_rightside'),



            't_lefts'=>Configuration::get($this->name.'t_lefts'),

            't_rights'=>Configuration::get($this->name.'t_rights'),

            't_footers'=>Configuration::get($this->name.'t_footers'),

            't_homes'=>Configuration::get($this->name.'t_homes'),

            't_leftsides'=>Configuration::get($this->name.'t_leftsides'),

            't_rightsides'=>Configuration::get($this->name.'t_rightsides'),

            't_tpages'=>Configuration::get($this->name.'t_tpages'),



            'rssontestim'=> Configuration::get($this->name.'rssontestim'),

            'n_rssitemst'=> Configuration::get($this->name.'n_rssitemst'),



        );



        return $data_config;



    }





    private function _cronhelpshopreviews($data = null){

        $url_cron = isset($data['url'])?$data['url']:'';

        $_html = '';



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<div class="panel">

				<div class="panel-heading"><i class="fa fa-tasks fa-lg"></i>&nbsp;'.$this->l('CRON HELP').'</div>';

        } else {



            $_html .= '<br/><br/><h3 class="title-block-content"><i class="fa fa-tasks fa-lg"></i>&nbsp;'.$this->l('CRON HELP').'</h3>';

        }









        $_html .= '<p class="hint clear" style="display: block; font-size: 12px; width: 95%;position:relative">';



        $_html .= '<b>';

        $_html .= $this->l('You can configure sending email messages through cron. You have 2 possibilities:');

        $_html .= '</b>';

        $_html .= '<br/><br/><br/>';

        $_html .= '<b>1.</b> '.$this->l('You can enter the following url in your browser: ');

        $_html .= '<b>'.$this->getURLMultiShop().'modules/'.$this->name.'/'.$url_cron.'.php?token='.$this->getokencron().'</b>';

        $_html .= '<br/><br/><br/>';

        $_html .= '<b>2.</b> '.$this->l('You can set a cron\'s task (a recursive task that fulfills the sending of reminders)');

        $_html .= '<br/><br/>';

        $_html .= $this->l('The task run every hour').':&nbsp;&nbsp;&nbsp; <b>* */1 * * * /usr/bin/wget -O - -q '.$this->getURLMultiShop().'modules/'.$this->name.'/'.$url_cron.'.php?token='.$this->getokencron().'</b>';

        $_html .= '<br/><br/>';

        $_html .= $this->l('or');

        $_html .= '<br/><br/>';

        $_html .= $this->l('The task run every hour').':&nbsp;&nbsp;&nbsp; <b>* */1 * * * php -f /var/www/vhosts/myhost/httpdocs/prestashop/modules/'.$this->name.'/'.$url_cron.'.php?token='.$this->getokencron().'</b>';

        $_html .= '<br/><br/><br/>';

        $_html .= '<b>'.$this->l('How to configure a cron task ?').'</b>';

        $_html .= '<br/><br/>';

        $_html .= $this->l('On your server, the interface allows you to configure cron\'s tasks');

        $_html .= '<br/>';

        $_html .= $this->l('About CRON').'&nbsp;&nbsp;&nbsp;<a href=http://en.wikipedia.org/wiki/Cron target=_blank>http://en.wikipedia.org/wiki/Cron</a>';

        $_html .= '</p>';





        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '</div>';

        }



        return $_html;

    }





    private function _userprofileg16(){

        $fields_form = array(

            'form'=> array(

                'legend' => array(

                    'title' => $this->l('User profile'),

                    'icon' => 'fa fa-users fa-lg'

                ),

                'input' => array(



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable User profile'),

                        'name' => 'is_uprof',

                        'desc' => $this->l('Enable or Disable User profile functional on your site'),



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('The number of shoppers in the "Block Users":'),

                        'name' => 'rshoppers_blc',

                        'class' => ' fixed-width-sm',



                    ),



                    array(

                        'type' => 'checkbox_custom_blocks',

                        'label' => $this->l('Position "Block Users":'),

                        'name' => 'rproft_left',

                        'values' => array(

                            'query' => array(



                                array(

                                    'id' => 'radv_left',

                                    'name' => $this->l('Left column'),

                                    'val' => 1

                                ),





                                array(

                                    'id' => 'radv_right',

                                    'name' => $this->l('Right column'),

                                    'val' => 1

                                ),





                                array(

                                    'id' => 'radv_footer',

                                    'name' => $this->l('Footer'),

                                    'val' => 1

                                ),



                                array(

                                    'id' => 'radv_home',

                                    'name' => $this->l('Home'),

                                    'val' => 1

                                ),



                            ),

                            'id' => 'id',

                            'name' => 'name'

                        ),



                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Users per page in the list view:'),

                        'name' => 'rpage_shoppers',

                        'class' => ' fixed-width-sm',



                    ),



                ),

            ),

        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );



        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'userprofilegsettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesUserSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );





        $_html = '';

        $_html .= "<script type=\"text/javascript\">

    	$('document').ready( function() {



    		$( \"label[for='is_uprof_on']\" ).click(function() {

                $( \"input[name*='rshoppers_blc']\" ).parent().parent().show(200);

                $( \"input[name*='rpage_shoppers']\" ).parent().parent().show(200);

                $( \".rproft_left\" ).parent().show(200);



            });



            $( \"label[for='is_uprof_off']\" ).click(function() {

    	         $( \"input[name*='rshoppers_blc']\" ).parent().parent().hide(200);

    	         $( \"input[name*='rpage_shoppers']\" ).parent().parent().hide(200);

    	         $( \".rproft_left\" ).parent().hide(200);



    	    });



    	    if(".(int)Configuration::get($this->name.'is_uprof')." == 0){

    	        $( \"input[name*='rshoppers_blc']\" ).parent().parent().hide(200);

    	        $( \"input[name*='rpage_shoppers']\" ).parent().parent().hide(200);

    	        $( \".rproft_left\" ).parent().hide(200);

    	    }



    	});



    	</script>";





        return  $_html.$helper->generateForm(array($fields_form,$fields_form1));

    }



    public function getConfigFieldsValuesUserSettings(){



        $data_config = array(

            'is_uprof' =>Configuration::get($this->name.'is_uprof'),



            'rshoppers_blc' => Configuration::get($this->name.'rshoppers_blc'),

            'rpage_shoppers'=> Configuration::get($this->name.'rpage_shoppers'),



            'radv_left'=>Configuration::get($this->name.'radv_left'),

            'radv_right'=>Configuration::get($this->name.'radv_right'),

            'radv_footer'=>Configuration::get($this->name.'radv_footer'),

            'radv_home'=>Configuration::get($this->name.'radv_home'),



        );



        return $data_config;



    }





    private function _autoposts16(){

        #### posts api ###

        include_once(dirname(__FILE__).'/classes/postshelp.class.php');

        $postshelp = new postshelp();



        return $postshelp->postsSettings(array('translate'=>$this->_translate));

        #### posts api ###

    }



    public function psvkform16($data){

        $update_button = $data['translate']['update_button'];

        $enable_pstwitterpost = $data['translate']['enable_psvkpost'];



        $template_text = $data['translate']['template_text'];

        $name = $data['translate']['name'];





        $fields_form = array(

            'form'=> array(

                //'tinymce' => FALSE,

                'legend' => array(

                    'title' => $enable_pstwitterpost,

                    'icon' => 'fa fa-vk fa-lg'

                ),

                'input' => array(



                    array(

                        'type' => 'switch',

                        'label' => $enable_pstwitterpost,

                        'name' => 'vkpost_on',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'text_autopost',

                        'label' => $template_text,

                        'name' => 'vkdesc',

                        'id' => 'vkdesc',

                        'lang' => TRUE,

                        'text_before' => '{John. D.}',

                        'text_after' => ' : ★★★★☆ - {Product name} - {Product URL}',



                    ),





                ),







            ),





        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $update_button,

                )

            ),

        );





        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'psvkpostsettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$name.'&tab_module='.$this->tab.'&module_name='.$name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesPsvkpostsettingsSettings($name),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );









        return  $helper->generateForm(array($fields_form,$fields_form1));

    }



    public function getConfigFieldsValuesPsvkpostsettingsSettings($name){

        $languages = Language::getLanguages(false);

        $fields_vkdesc = array();



        foreach ($languages as $lang)

        {

            $fields_vkdesc[$lang['id_lang']] =  Configuration::get($name.'vkdesc_'.$lang['id_lang']);



        }





        $data_config = array(

            'vkdesc' => $fields_vkdesc,

            'vkpost_on' => Configuration::get($name.'vkpost_on'),

        );



        return $data_config;





    }





    public function pstwitterform16($data){

        $update_button = $data['translate']['update_button'];

        $enable_pstwitterpost = $data['translate']['enable_pstwitterpost'];



        $template_text = $data['translate']['template_text'];

        $name = $data['translate']['name'];





        $fields_form = array(

            'form'=> array(

                //'tinymce' => FALSE,

                'legend' => array(

                    'title' => $enable_pstwitterpost,

                    'icon' => 'fa fa-facebook fa-twitter fa-lg'

                ),

                'input' => array(



                    array(

                        'type' => 'switch',

                        'label' => $enable_pstwitterpost,

                        'name' => 'twpost_on',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'text_autopost',

                        'label' => $template_text,

                        'name' => 'twdesc',

                        'id' => 'twdesc',

                        'lang' => TRUE,

                        'text_before' => '{John. D.}',

                        'text_after' => ' : ★★★★☆ - {Product name} - {Product URL}',



                    ),





                ),







            ),





        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $update_button,

                )

            ),

        );





        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'pstwitterpostsettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$name.'&tab_module='.$this->tab.'&module_name='.$name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesPstwitterpostsettingsSettings($name),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );









        return  $helper->generateForm(array($fields_form,$fields_form1));

    }



    public function getConfigFieldsValuesPstwitterpostsettingsSettings($name){

        $languages = Language::getLanguages(false);

        $fields_twdesc = array();



        foreach ($languages as $lang)

        {

            $fields_twdesc[$lang['id_lang']] =  Configuration::get($name.'twdesc_'.$lang['id_lang']);



        }





        $data_config = array(

            'twdesc' => $fields_twdesc,

            'twpost_on' => Configuration::get($name.'twpost_on'),

        );



        return $data_config;





    }



    private function _vouchersettings16(){

        $_html = '';



        $_html .= '<div class="row">

    				<div class="col-lg-12">

    					<div class="row">';



        $_html .= '<div class="productTabs col-lg-2 col-md-3">



			<div class="list-group">

				<ul class="nav nav-pills nav-stacked" id="vouchernavtabs16">

				    <li class="active"><a href="#reviewsvoucheraddreviewtab" data-toggle="tab" class="list-group-item"><i class="fa fa-reviews fa-lg"></i>&nbsp;'.$this->l('Voucher settings, when a user add review').'</a></li>

				    <li><a href="#reviewsvouchersharereviewtab" data-toggle="tab" class="list-group-item"><i class="fa fa-facebook fa-lg"></i>&nbsp;'.$this->l('Voucher settings, when a user share review on the Facebook').'</a></li>



				  </ul>

				  </div>

		</div>';



        $_html .= '<div class="tab-content col-lg-10 col-md-9">';

        $_html .= '<div class="tab-pane active" id="reviewsvoucheraddreviewtab">'.$this->_voucherwhenaddreviewsettings16().'</div>';

        $_html .= '<div class="tab-pane" id="reviewsvouchersharereviewtab">'.$this->_voucherwhensharereviewsettings16().'</div>';



        $_html .= '</div>';







        $_html .= '</div></div></div>';



        return $_html;

    }



    private function _voucherwhensharereviewsettings16(){

        $cookie = $this->context->cookie;



        if($this->_is16)

            $curs = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

        else

            $curs = Currency::getCurrencies();





        $discount_type_currency_value = array();

        $min_checkout_value = array();



        foreach ($curs AS $_cur){



            $discount_type_currency_value[$_cur['id_currency']] = array('amount' => Tools::getValue('sdamountfb['.(int)($_cur['id_currency']).']', Configuration::get('sdamountfb_'.(int)($_cur['id_currency']))),

                'currency'=>$_cur['sign'],

                'name'=>htmlentities($_cur['name'], ENT_NOQUOTES, 'utf-8'),

                'name_item' => 'sdamountfb'

            );



            $min_checkout_value[$_cur['id_currency']]  = array('amount' => Tools::getValue('sdminamountfb['.(int)($_cur['id_currency']).']', Configuration::get('sdminamountfb_'.(int)($_cur['id_currency']))),

                'currency'=>$_cur['sign'],

                'name'=>htmlentities($_cur['name'], ENT_NOQUOTES, 'utf-8'),

                'name_item' => 'sdminamountfb'

            );

        }



        // select categories



        $select_categories = '';

        $cat = new Category();

        $list_cat = $cat->getCategories($cookie->id_lang);



        $select_categories .= '<table class="table">';

        $select_categories .= '<tr>

    	<th><input type="checkbox" onclick="checkDelBoxes(this.form, \'categoryBoxfb[]\', this.checked)" class="noborder" name="checkmefb"></th>

    	<th>ID</th>

    	<th style="width: 400px">'.$this->l('Name').'</th>

    	</tr>';

        $current_cat = Category::getRootCategory()->id;

        ob_start();

        $this->recurseCategoryForInclude($list_cat, $list_cat, $current_cat,1,null,'fb');

        $cat_option = ob_get_clean();



        $select_categories .= $cat_option;

        $select_categories .= '</table>';



        //var_dump($select_categories);exit;

        // select categories





        $fields_form = array(

            'form' => array(

                'legend' => array(

                    'title' => $this->l('Voucher settings, when a user share review on the Facebook'),

                    'icon' => 'fa fa-facebook fa-lg'

                ),

                'input' => array(



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Voucher'),

                        'name' => 'vis_onfb',

                        'desc' => '<b style="color:red">'.$this->l('Enable or Disable Voucher, when a user share review on the Facebook').'</b>',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Coupon Description'),

                        'name' => 'coupondescfb',

                        'lang' => true,

                        'hint' => $this->l('The description is displayed in cart once your customers use their voucher.'),

                        'desc' => $this->l('The description is displayed in cart once your customers use their voucher.')

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Voucher code'),

                        'name' => 'vouchercodefb',

                        'desc' => $this->l('Voucher code prefix. It must be at least 3 letters long. Prefix voucher code will be used in the first part of the coupon code, which the user will use to get a discount'),

                    ),



                    array(

                        'type' => 'select',

                        'label' => $this->l('Discount Type'),

                        'name' => 'discount_typefb',

                        //'desc' => $this->l('Discount Type'),

                        'options' => array(

                            'query' => array(

                                array(

                                    'id' => '1',

                                    'name' => $this->l('Percentages')

                                ),



                                array(

                                    'id' => '2',

                                    'name' => $this->l('Currency'),

                                ),





                            ),

                            'id' => 'id',

                            'name' => 'name'

                        )

                    ),





                    array(

                        'type' => 'cms_pages',

                        'label' => '',

                        'name' => 'currency_valfb',

                        'values'=> $discount_type_currency_value,



                    ),



                    array(

                        'type' => 'select',

                        'label' => $this->l('Tax'),

                        'name' => 'taxfb',

                        'options' => array(

                            'query' => array(

                                array(

                                    'id' => '0',

                                    'name' => $this->l('Tax Excluded')

                                ),



                                array(

                                    'id' => '1',

                                    'name' => $this->l('Tax Included'),

                                ),





                            ),

                            'id' => 'id',

                            'name' => 'name'

                        )

                    ),



                    array(

                        'type' => 'text_custom',

                        'label' => $this->l('Voucher percentage'),

                        'name' => 'percentage_valfb',

                        'value'=> Configuration::get($this->name.'percentage_valfb'),

                    ),









                    array(

                        'type' => 'checkbox_custom',

                        'label' => $this->l('Minimum checkout'),

                        'name' => $this->name.'isminamountfb',

                        'hint' => $this->l('Minimum checkout'),

                        'values' => array(

                            'query' => array(

                                array(

                                    'id' => $this->name.'isminamountfb',

                                    'name' => '',

                                    'val' => 1

                                ),



                            ),

                            'id' => 'id',

                            'name' => 'name'

                        ),





                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Display the minimum amount in the Front Office in the add review form ?'),

                        'name' => 'is_show_minfb',

                        'desc' => $this->l('Use to show or not the minimum amount on the Front office in the add review form'),

                        'hint' => $this->l('Use to show or not the minimum amount on the Front office in the add review form'),



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'cms_pages',

                        'label' => '',

                        'name' => 'mincheckout_valfb',

                        'values'=> $min_checkout_value,



                    ),



                    array(

                        'type' => 'cms_categories',

                        'label' => $this->l('Select categories'),

                        'hint' => $this->l('Check all box(es) of categories to which the discount is to be applied. No categories checked will apply the voucher on all of them.'),

                        'desc' => $this->l('Check all box(es) of categories to which the discount is to be applied. No categories checked will apply the voucher on all of them.'),



                        'name' => 'select_catfb',

                        'values'=> $select_categories,



                    ),



                    array(

                        'type' => 'text_validity',

                        'label' => $this->l('Voucher validity'),

                        'name' => 'sdvvalidfb',

                        'value'=> Configuration::get($this->name.'sdvvalidfb'),

                        'desc' =>$this->l('Voucher term of validity in days'),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Highlight'),

                        'name' => 'highlightfb',

                        'desc'=>$this->l('If the voucher is not yet in the cart, it will be displayed in the cart summary.'),



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Cumulative with others vouchers'),

                        'name' => 'cumulativeotherfb',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),





                    array(

                        'type' => 'switch',

                        'label' => $this->l('Cumulative with price reductions'),

                        'name' => 'cumulativereducfb',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),





                ),



                'submit' => array(

                    'title' => $this->l('Save'),

                )

            ),

        );







        $helper = new HelperForm();

        $helper->show_toolbar = false;

        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'vouchersettingsfb';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesCouponWhenShareReviewSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );





        $_html = '';



        $currency_discount_amount = (int)Configuration::get($this->name.'discount_typefb');





        $_html .= "<script type=\"text/javascript\">

    	$('document').ready( function() {

    		if(".$currency_discount_amount." == 2){

    				$( \"input[name*='percentage_valfb']\" ).parent().parent().parent().hide(200);

    				//$( \"select[name*='taxpercfb']\" ).parent().parent().hide(200);

    		} else {

    				$( \"select[name='taxfb']\" ).parent().parent().hide(200);

    				$( \".currency_valfb\" ).parent().hide(200);



    		}





    		$( \"#discount_typefb\" ).change(function() {

  				    if($( \"#discount_typefb option:selected\" ).val() == 2){



  				    	$( \"input[name*='percentage_valfb']\" ).parent().parent().parent().hide(200);

    					//$( \"select[name='taxpercfb']\" ).parent().parent().hide(200);

    					$( \"select[name='taxfb']\" ).parent().parent().show(200);

		    			$( \".currency_valfb\" ).parent().show(200);



		    		} else {

		    			$( \"select[name='taxfb']\" ).parent().parent().hide(200);

		    			$( \".currency_valfb\" ).parent().hide(200);

		    			$( \"input[name*='percentage_valfb']\" ).parent().parent().parent().show(200);

    					//$( \"select[name*='taxpercfb']\" ).parent().parent().show(200);

		    		}

			});





			if($(\"input#".$this->name."isminamountfb\").is(\":checked\")) {

	            $(\"#".$this->name."isminamountfb\").val($(this).is(\":checked\"));

	    	    $( \".mincheckout_valfb\" ).parent().show(200);

	    	    $('#is_show_minfb_on').parent().parent().parent().show(200);



	        } else {

	        	$( \".mincheckout_valfb\" ).parent().hide(200);

	        	$('#is_show_minfb_on').parent().parent().parent().hide(200);

	        }





			$(\"input#".$this->name."isminamountfb\").change(function() {

			if($(this).is(\":checked\")) {

	            $(\"#".$this->name."isminamountfb\").val($(this).is(\":checked\"));

	    	    $( \".mincheckout_valfb\" ).parent().show(200);

	    	    $('#is_show_minfb_on').parent().parent().parent().show(200);

	        } else {

	        	$( \".mincheckout_valfb\" ).parent().hide(200);

	        	$('#is_show_minfb_on').parent().parent().parent().hide(200);

	        }

	        });







	        $( \"label[for='vis_onfb_on']\" ).click(function() {

	            var obj_vis_on = $( \"input[name='vis_onfb']\" ).parent().parent().parent().parent();

	            obj_vis_on.find( 'div.form-group' ).css('display','block');



	            if(".$currency_discount_amount." == 2){

    				$( \"input[name*='percentage_valfb']\" ).parent().parent().parent().hide(200);

    				//$( \"select[name*='taxpercfb']\" ).parent().parent().hide(200);

    		    } else {

    				$( \"select[name='taxfb']\" ).parent().parent().hide(200);

    				$( \".currency_valfb\" ).parent().hide(200);



    		    }



    		    if($(\"input#".$this->name."isminamountfb\").is(\":checked\")) {

                    $(\"#".$this->name."isminamountfb\").val($(\"input#".$this->name."isminamountfb\").is(\":checked\"));

                    $( \".mincheckout_valfb\" ).parent().show(200);

                    $('#is_show_minfb_on').parent().parent().parent().show(200);

                } else {

                    $( \".mincheckout_valfb\" ).parent().hide(200);

                    $('#is_show_minfb_on').parent().parent().parent().hide(200);

                }



            });



            $( \"label[for='vis_onfb_off']\" ).click(function() {

    	        var obj_vis_on = $( \"input[name='vis_onfb']\" ).parent().parent().parent().parent();

	            obj_vis_on.find( 'div.form-group' ).css('display','none');

	            $( \"input[name='vis_onfb']\" ).parent().parent().parent().css('display','block');



    	    });



    	    if(".(int)Configuration::get($this->name.'vis_onfb')." == 0){

    	      var obj_vis_on = $( \"input[name='vis_onfb']\" ).parent().parent().parent().parent();

	            obj_vis_on.find( 'div.form-group' ).css('display','none');

	            $( \"input[name='vis_onfb']\" ).parent().parent().parent().css('display','block');

    	    }





    	});

    	</script>

    	";



        return $_html . $helper->generateForm(array($fields_form));

    }



    public function getConfigFieldsValuesCouponWhenShareReviewSettings(){









        $languages = Language::getLanguages(false);

        $fields_fcoupondesc = array();



        foreach ($languages as $lang)

        {

            $fields_fcoupondesc[$lang['id_lang']] = Configuration::get($this->name.'coupondescfb_'.$lang['id_lang']);



        }



        $data_config = array(

            'vis_onfb' => Configuration::get($this->name.'vis_onfb'),

            'vouchercodefb' => Configuration::get($this->name.'vouchercodefb'),

            'discount_typefb' => Configuration::get($this->name.'discount_typefb'),



            'coupondescfb' => $fields_fcoupondesc,





            'taxfb' =>  Configuration::get($this->name.'taxfb'),



            //'taxpercfb' => Configuration::get($this->name.'taxpercfb'),



            $this->name.'isminamountfb' =>  Configuration::get($this->name.'isminamountfb'),



            'is_show_minfb' => Configuration::get($this->name.'is_show_minfb'),



            'cumulativeotherfb' => Configuration::get($this->name.'cumulativeotherfb'),



            'cumulativereducfb' =>  Configuration::get($this->name.'cumulativereducfb'),



            'highlightfb' => Configuration::get($this->name.'highlightfb'),

        );







        return $data_config;

    }



    private function _voucherwhenaddreviewsettings16(){

        $cookie = $this->context->cookie;



        if($this->_is16)

            $curs = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

        else

            $curs = Currency::getCurrencies();





        $discount_type_currency_value = array();

        $min_checkout_value = array();



        foreach ($curs AS $_cur){



            $discount_type_currency_value[$_cur['id_currency']] = array('amount' => Tools::getValue('sdamount['.(int)($_cur['id_currency']).']', Configuration::get('sdamount_'.(int)($_cur['id_currency']))),

                'currency'=>$_cur['sign'],

                'name'=>htmlentities($_cur['name'], ENT_NOQUOTES, 'utf-8'),

                'name_item' => 'sdamount'

            );



            $min_checkout_value[$_cur['id_currency']]  = array('amount' => Tools::getValue('sdminamount['.(int)($_cur['id_currency']).']', Configuration::get('sdminamount_'.(int)($_cur['id_currency']))),

                'currency'=>$_cur['sign'],

                'name'=>htmlentities($_cur['name'], ENT_NOQUOTES, 'utf-8'),

                'name_item' => 'sdminamount'

            );

        }



        // select categories



        $select_categories = '';

        $cat = new Category();

        $list_cat = $cat->getCategories($cookie->id_lang);



        $select_categories .= '<table class="table">';

        $select_categories .= '<tr>

    	<th><input type="checkbox" onclick="checkDelBoxes(this.form, \'categoryBox[]\', this.checked)" class="noborder" name="checkme"></th>

    	<th>ID</th>

    	<th style="width: 400px">'.$this->l('Name').'</th>

    	</tr>';

        $current_cat = Category::getRootCategory()->id;

        ob_start();

        $this->recurseCategoryForInclude($list_cat, $list_cat, $current_cat);

        $cat_option = ob_get_clean();



        $select_categories .= $cat_option;

        $select_categories .= '</table>';



        //var_dump($select_categories);exit;

        // select categories





        $fields_form = array(

            'form' => array(

                'legend' => array(

                    'title' => $this->l('Voucher settings, when a user add review'),

                    'icon' => 'fa fa-reviews fa-lg'

                ),

                'input' => array(



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Voucher'),

                        'name' => 'vis_on',

                        'desc' => '<b style="color:red">'.$this->l('Enable or Disable Voucher, when a user add review').'</b>',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Coupon Description'),

                        'name' => 'coupondesc',

                        'lang' => true,

                        'hint' => $this->l('The description is displayed in cart once your customers use their voucher.'),

                        'desc' => $this->l('The description is displayed in cart once your customers use their voucher.')

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Voucher code'),

                        'name' => 'vouchercode',

                        'desc' => $this->l('Voucher code prefix. It must be at least 3 letters long. Prefix voucher code will be used in the first part of the coupon code, which the user will use to get a discount'),

                    ),



                    array(

                        'type' => 'select',

                        'label' => $this->l('Discount Type'),

                        'name' => 'discount_type',

                        //'desc' => $this->l('Discount Type'),

                        'options' => array(

                            'query' => array(

                                array(

                                    'id' => '1',

                                    'name' => $this->l('Percentages')

                                ),



                                array(

                                    'id' => '2',

                                    'name' => $this->l('Currency'),

                                ),





                            ),

                            'id' => 'id',

                            'name' => 'name'

                        )

                    ),





                    array(

                        'type' => 'cms_pages',

                        'label' => '',

                        'name' => 'currency_val',

                        'values'=> $discount_type_currency_value,



                    ),



                    array(

                        'type' => 'select',

                        'label' => $this->l('Tax'),

                        'name' => 'tax',

                        'options' => array(

                            'query' => array(

                                array(

                                    'id' => '0',

                                    'name' => $this->l('Tax Excluded')

                                ),



                                array(

                                    'id' => '1',

                                    'name' => $this->l('Tax Included'),

                                ),





                            ),

                            'id' => 'id',

                            'name' => 'name'

                        )

                    ),



                    array(

                        'type' => 'text_custom',

                        'label' => $this->l('Voucher percentage'),

                        'name' => 'percentage_val',

                        'value'=> Configuration::get($this->name.'percentage_val'),

                    ),









                    array(

                        'type' => 'checkbox_custom',

                        'label' => $this->l('Minimum checkout'),

                        'name' => $this->name.'isminamount',

                        'hint' => $this->l('Minimum checkout'),

                        'values' => array(

                            'query' => array(

                                array(

                                    'id' => $this->name.'isminamount',

                                    'name' => '',

                                    'val' => 1

                                ),



                            ),

                            'id' => 'id',

                            'name' => 'name'

                        ),





                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Display the minimum amount in the Front Office in the add review form ?'),

                        'name' => 'is_show_min',

                        'desc' => $this->l('Use to show or not the minimum amount on the Front office in the add review form'),

                        'hint' => $this->l('Use to show or not the minimum amount on the Front office in the add review form'),



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'cms_pages',

                        'label' => '',

                        'name' => 'mincheckout_val',

                        'values'=> $min_checkout_value,



                    ),



                    array(

                        'type' => 'cms_categories',

                        'label' => $this->l('Select categories'),

                        'hint' => $this->l('Check all box(es) of categories to which the discount is to be applied. No categories checked will apply the voucher on all of them.'),

                        'desc' => $this->l('Check all box(es) of categories to which the discount is to be applied. No categories checked will apply the voucher on all of them.'),



                        'name' => 'select_cat',

                        'values'=> $select_categories,



                    ),



                    array(

                        'type' => 'text_validity',

                        'label' => $this->l('Voucher validity'),

                        'name' => 'sdvvalid',

                        'value'=> Configuration::get($this->name.'sdvvalid'),

                        'desc' =>$this->l('Voucher term of validity in days'),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Highlight'),

                        'name' => 'highlight',

                        'desc'=>$this->l('If the voucher is not yet in the cart, it will be displayed in the cart summary.'),



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Cumulative with others vouchers'),

                        'name' => 'cumulativeother',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),





                    array(

                        'type' => 'switch',

                        'label' => $this->l('Cumulative with price reductions'),

                        'name' => 'cumulativereduc',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),





                ),



                'submit' => array(

                    'title' => $this->l('Save'),

                )

            ),

        );







        $helper = new HelperForm();

        $helper->show_toolbar = false;

        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'vouchersettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesCouponSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );





        $_html = '';



        $currency_discount_amount = (int)Configuration::get($this->name.'discount_type');





        $_html .= "<script type=\"text/javascript\">

    	$('document').ready( function() {

    		if(".$currency_discount_amount." == 2){

    				$( \"input[name*='percentage_val']\" ).parent().parent().parent().hide(200);

    				//$( \"select[name*='taxperc']\" ).parent().parent().hide(200);

    		} else {

    				$( \"select[name='tax']\" ).parent().parent().hide(200);

    				$( \".currency_val\" ).parent().hide(200);



    		}





    		$( \"#discount_type\" ).change(function() {

  				    if($( \"#discount_type option:selected\" ).val() == 2){



  				    	$( \"input[name*='percentage_val']\" ).parent().parent().parent().hide(200);

    					//$( \"select[name='taxperc']\" ).parent().parent().hide(200);

    					$( \"select[name='tax']\" ).parent().parent().show(200);

		    			$( \".currency_val\" ).parent().show(200);



		    		} else {

		    			$( \"select[name='tax']\" ).parent().parent().hide(200);

		    			$( \".currency_val\" ).parent().hide(200);

		    			$( \"input[name*='percentage_val']\" ).parent().parent().parent().show(200);

    					//$( \"select[name*='taxperc']\" ).parent().parent().show(200);

		    		}

			});





			if($(\"input#".$this->name."isminamount\").is(\":checked\")) {

	            $(\"#".$this->name."isminamount\").val($(this).is(\":checked\"));

	    	    $( \".mincheckout_val\" ).parent().show(200);

	    	    $('#is_show_min_on').parent().parent().parent().show(200);



	        } else {

	        	$( \".mincheckout_val\" ).parent().hide(200);

	        	$('#is_show_min_on').parent().parent().parent().hide(200);

	        }





			$(\"input#".$this->name."isminamount\").change(function() {

			if($(this).is(\":checked\")) {

	            $(\"#".$this->name."isminamount\").val($(this).is(\":checked\"));

	    	    $( \".mincheckout_val\" ).parent().show(200);

	    	    $('#is_show_min_on').parent().parent().parent().show(200);

	        } else {

	        	$( \".mincheckout_val\" ).parent().hide(200);

	        	$('#is_show_min_on').parent().parent().parent().hide(200);

	        }

	        });







	        $( \"label[for='vis_on_on']\" ).click(function() {

	            var obj_vis_on = $( \"input[name='vis_on']\" ).parent().parent().parent().parent();

	            obj_vis_on.find( 'div.form-group' ).css('display','block');



	            if(".$currency_discount_amount." == 2){

    				$( \"input[name*='percentage_val']\" ).parent().parent().parent().hide(200);

    				//$( \"select[name*='taxperc']\" ).parent().parent().hide(200);

    		    } else {

    				$( \"select[name='tax']\" ).parent().parent().hide(200);

    				$( \".currency_val\" ).parent().hide(200);



    		    }



    		    if($(\"input#".$this->name."isminamount\").is(\":checked\")) {

                    $(\"#".$this->name."isminamount\").val($(\"input#".$this->name."isminamount\").is(\":checked\"));

                    $( \".mincheckout_val\" ).parent().show(200);

                    $('#is_show_min_on').parent().parent().parent().show(200);

                } else {

                    $( \".mincheckout_val\" ).parent().hide(200);

                    $('#is_show_min_on').parent().parent().parent().hide(200);

                }



            });



            $( \"label[for='vis_on_off']\" ).click(function() {

    	        var obj_vis_on = $( \"input[name='vis_on']\" ).parent().parent().parent().parent();

	            obj_vis_on.find( 'div.form-group' ).css('display','none');

	            $( \"input[name='vis_on']\" ).parent().parent().parent().css('display','block');



    	    });



    	    if(".(int)Configuration::get($this->name.'vis_on')." == 0){

    	      var obj_vis_on = $( \"input[name='vis_on']\" ).parent().parent().parent().parent();

	            obj_vis_on.find( 'div.form-group' ).css('display','none');

	            $( \"input[name='vis_on']\" ).parent().parent().parent().css('display','block');

    	    }





    	});

    	</script>

    	";



        return $_html . $helper->generateForm(array($fields_form));

    }



    public function getConfigFieldsValuesCouponSettings(){









        $languages = Language::getLanguages(false);

        $fields_fcoupondesc = array();



        foreach ($languages as $lang)

        {

            $fields_fcoupondesc[$lang['id_lang']] = Configuration::get($this->name.'coupondesc_'.$lang['id_lang']);



        }



        $data_config = array(

            'vis_on' => Configuration::get($this->name.'vis_on'),

            'vouchercode' => Configuration::get($this->name.'vouchercode'),

            'discount_type' => Configuration::get($this->name.'discount_type'),



            'coupondesc' => $fields_fcoupondesc,





            'tax' =>  Configuration::get($this->name.'tax'),



            //'taxperc' => Configuration::get($this->name.'taxperc'),



            $this->name.'isminamount' =>  Configuration::get($this->name.'isminamount'),



            'is_show_min' => Configuration::get($this->name.'is_show_min'),



            'cumulativeother' => Configuration::get($this->name.'cumulativeother'),



            'cumulativereduc' =>  Configuration::get($this->name.'cumulativereduc'),

            'highlight' =>  Configuration::get($this->name.'highlight'),

        );







        return $data_config;

    }







    private function _customerreminder(){



        include_once(dirname(__FILE__).'/classes/featureshelp.class.php');

        $obj_featureshelp = new featureshelp();

        $cookie = $this->context->cookie;

		$id_lang = (int)($cookie->id_lang);







        $fields_form = array(

            'form'=> array(

                //'tinymce' => FALSE,

                'legend' => array(

                    'title' => $this->l('Customer Reminder settings'),

                    'icon' => 'fa fa-bell-o fa-lg'

                ),

                'input' => array(



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Send a review reminder by email to customers'),

                        'name' => 'reminder',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),



                        'desc' => $this->l('If activated, when a customer purchases a product on your shop, an e-mail will be sent to him after X days (specify below after selecting "yes" here) to invite him to rate the product.').'

                                    <br/><br/>

                                    <b>'.$this->l('IMPORTANT NOTE').'</b>: '.$this->l('This requires to set a CRON task on your server. ').'

                                    <a style="text-decoration:underline;font-weight:bold;color:red" onclick="tabs_custom(104)" href="javascript:void(0)">'.$this->l('CRON HELP PRODUCT REVIEWS').'</a>

                                    <br/><br/>

                                    <b>'.$this->l('Your CRON URL to call').'</b>:&nbsp;<a href="'.$this->getURLMultiShop().'modules/'.$this->name.'/cron.php?token='.$this->getokencron().'" style="text-decoration:underline;font-weight:bold">'.$this->getURLMultiShop().'modules/'.$this->name.'/cron.php?token='.$this->getokencron().'</a>',

                    ),





                    array(

                        'type' => 'text_custom_delay',

                        'label' => $this->l('Delay between each email in seconds'),

                        'name' => 'crondelay'.$this->_prefix_review,

                        'value'=>(int)Configuration::get($this->name.'crondelay'.$this->_prefix_review),

                        'desc' => $this->l('The delay is intended in order to your server is not blocked the email function'),

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Number of emails for each cron call'),

                        'name' => 'cronnpost'.$this->_prefix_review,

                        'desc' => $this->l('This will reduce the load on your server. The more powerful your server - the more emails you can do for each CRON call!'),

                    ),







                    array(

                        'type' => 'text_custom_delay_reminder',

                        'label' => $this->l('Delay for sending reminder by email'),

                        'name' => 'delay',

                        'value'=> Configuration::get($this->name.'delay'),

                        'desc'=>$this->l('We recommend you enter at least 7 days here to have enough time to process the order and for the customer to receive it.')

                    ),







                    array(

                        'type' => 'text_custom_orders_import',

                        'label' => $this->l('Import your old orders'),

                        'name' => 'orders_import',

                        'end_date' =>date('Y-m-d H:i:s'),

                        'host_url'=>$this->getURLMultiShop(),

                        'desc'=>$this->l('Please select a date. All orders placed between that start date and end date (today) will be imported.')

                    ),



                    array(

                        'type' => 'text_custom_order_statuses',

                        'label' => $this->l('Send emails only for the orders with the current selected status'),

                        'name' => 'sel_statuses',

                        'value'=> $obj_featureshelp->getOrderStatuses(array('id_lang'=>$id_lang)),

                        'orderstatuses'=> explode(",",Configuration::get($this->name.'orderstatuses')),

                        'desc'=>$this->l('This feature prevents customers to leave a review for orders that they haven\'t received yet. You must choose at least one status.')

                    ),





                    array(

                        'type' => 'switch',

                        'label' => $this->l('Send a review reminder by email to customer when customer already write review in shop?'),

                        'name' => 'remrevsec'.$this->_prefix_review,



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),



                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Send a review reminder by email to customers a second time?'),

                        'name' => 'remindersec'.$this->_prefix_review,



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),



                        'desc' => $this->l('This feature allows you to send a second time the opinion request emails that have already been sent.'),

                    ),



                    array(

                        'type' => 'text_custom_delay_reminder',

                        'label' => $this->l('Days after the first emails were sent'),

                        'name' => 'delaysec'.$this->_prefix_review,

                        'value'=> Configuration::get($this->name.'delaysec'.$this->_prefix_review),

                    ),







                ),







            ),





        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );









        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'customerremindersettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesCustomerreminderSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );





        $_html = '';

        $_html .= "<script type=\"text/javascript\">

    	$('document').ready( function() {



    		$( \"label[for='remindersec".$this->_prefix_review."_on']\" ).click(function() {

                $( \"input[name='delaysec".$this->_prefix_review."']\").parent().parent().parent().show(200);





			});



            $( \"label[for='remindersec".$this->_prefix_review."_off']\" ).click(function() {

    	        $( \"input[name='delaysec".$this->_prefix_review."']\").parent().parent().parent().hide(200);

    	    });



    	    if(".(int)Configuration::get($this->name.'remindersec'.$this->_prefix_review)." == 0){

    	        $( \"input[name='delaysec".$this->_prefix_review."']\").parent().parent().parent().hide(200);

    	    }



    	});



    	</script>";



        return  $_html.$helper->generateForm(array($fields_form,$fields_form1));

    }



    public function getConfigFieldsValuesCustomerreminderSettings(){



        $data_config = array(



            'reminder' => Configuration::get($this->name.'reminder'),



            'remindersec'.$this->_prefix_review => Configuration::get($this->name.'remindersec'.$this->_prefix_review),



            'remrevsec'.$this->_prefix_review => Configuration::get($this->name.'remrevsec'.$this->_prefix_review),



            'cronnpost'.$this->_prefix_review=>(int)Configuration::get($this->name.'cronnpost'.$this->_prefix_review),

        );

        //echo "<pre>"; var_dumP($data_config); exit;



        return $data_config;



    }



    private function _responseadminemailsDesc($data){

        $name_template = $data['name'];

        return $this->l('You can customize the subject of the e-mail here.').' '

        . $this->l('If you wish to customize the e-mail message itself, you will need to manually edit the files in the')

        .'  "mails" '

        . $this->l(' folder inside the')

        .' "'.$this->name.'" '

        .$this->l('module folder, for each language, both the text and the HTML version each time. ')

        .'<br/><br/><b>'.$this->l('FTP Location').': /modules/'.$this->name.'/mails/{YOUR_ISO_CODE_LANGUAGE}/'.$name_template.'.html</b>';

    }



    private function _responseadminemails(){

        $fields_form = array(

            'form'=> array(

                //'tinymce' => FALSE,

                'legend' => array(

                    'title' => $this->l('Emails subjects settings'),

                    'icon' => 'fa fa-envelope-o fa-lg'

                ),

                'input' => array(



                    array(

                        'type' => 'text',

                        'label' => $this->l('Email reminder subject'),

                        'name' => 'emailreminder',

                        'id' => 'emailreminder',

                        'lang' => TRUE,

                        'desc' => $this->_responseadminemailsDesc(array('name'=>'customer-reminderserg'))

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Admin confirmation subject, when emails requests on the reviews was successfully sent'),

                        'name' => 'reminderok'.$this->_prefix_review,

                        'id' => 'reminderok'.$this->_prefix_review,

                        'lang' => TRUE,

                        'desc' => $this->_responseadminemailsDesc(array('name'=>'customer-reminder-admin-r'))

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Thank you for your review subject'),

                        'name' => 'thankyou'.$this->_prefix_review,

                        'id' => 'thankyou'.$this->_prefix_review,

                        'lang' => TRUE,

                        'desc' => $this->_responseadminemailsDesc(array('name'=>'review-thank-you-r'))

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('New Review subject'),

                        'name' => 'newrev'.$this->_prefix_review,

                        'id' => 'newrev'.$this->_prefix_review,

                        'lang' => TRUE,

                        'desc' => $this->_responseadminemailsDesc(array('name'=>'reviewserg'))

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Suggest to change review subject'),

                        'name' => 'subresem',

                        'id' => 'subresem',

                        'lang' => TRUE,

                        'desc' => $this->_responseadminemailsDesc(array('name'=>'reviewserg-suggest-to-change'))

                    ),



                    array(

                        'type' => 'textarea',

                        'label' => $this->l('Default content of the suggest to change review email'),

                        'name' => 'textresem',

                        'autoload_rte' => FALSE,

                        'lang' => TRUE,

                        'class'=>'mitrocops-textarea-email',

                        'desc' => $this->l('Templates for sending emails when people leave bad reviews and you wish to contact the user and try to convince a user to change his review / rating'),

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('One of your customers has modified own product review subject'),

                        'name' => 'modrev'.$this->_prefix_review,

                        'id' => 'modrev'.$this->_prefix_review,

                        'lang' => TRUE,

                        'desc' => $this->_responseadminemailsDesc(array('name'=>'reviewserg-customer-change-review'))

                    ),





                    array(

                        'type' => 'text',

                        'label' => $this->l('Notification email when a review is published subject'),

                        'name' => 'subpubem',

                        'id' => 'subpubem',

                        'lang' => TRUE,

                        'desc' => $this->_responseadminemailsDesc(array('name'=>'reviewserg-publish'))

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Someone send abuse for review subject'),

                        'name' => 'abuserev'.$this->_prefix_review,

                        'id' => 'abuserev'.$this->_prefix_review,

                        'lang' => TRUE,

                        'desc' => $this->_responseadminemailsDesc(array('name'=>'abuseserg'))

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('You submit a review and get voucher for discount subject'),

                        'name' => 'revvouc'.$this->_prefix_review,

                        'id' => 'revvouc'.$this->_prefix_review,

                        'lang' => TRUE,

                        'desc' => $this->_responseadminemailsDesc(array('name'=>'voucherserg'))

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('You share review on Facebook and get voucher for discount subject'),

                        'name' => 'facvouc'.$this->_prefix_review,

                        'id' => 'facvouc'.$this->_prefix_review,

                        'lang' => TRUE,

                        'desc' => $this->_responseadminemailsDesc(array('name'=>'voucherserg'))

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Share your review on Facebook and get voucher for discount subject'),

                        'name' => 'sugvouc'.$this->_prefix_review,

                        'id' => 'sugvouc'.$this->_prefix_review,

                        'lang' => TRUE,

                        'desc' => $this->_responseadminemailsDesc(array('name'=>'voucherserg-suggest'))

                    ),









                ),

            ),

        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );









        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'responseadminemailssettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesResponseadminemailsSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );









        return  $helper->generateForm(array($fields_form,$fields_form1));

    }



    public function getConfigFieldsValuesResponseadminemailsSettings(){



        $languages = Language::getLanguages(false);

        $fields_subresem = array();

        $fields_textresem = array();

        $fields_emailreminder = array();

        $fields_reminderok = array();

        $fields_thankyou = array();

        $fields_newrev = array();

        $fields_subpubem = array();

        $fields_modrev = array();

        $fields_abuserev = array();

        $fields_facvouc = array();

        $fields_revvouc = array();

        $fields_sugvouc = array();



        foreach ($languages as $lang)

        {

            $fields_subresem[$lang['id_lang']] =  Configuration::get($this->name.'subresem_'.$lang['id_lang']);

            $fields_textresem[$lang['id_lang']] =  Configuration::get($this->name.'textresem_'.$lang['id_lang']);

            $fields_emailreminder[$lang['id_lang']] =  Configuration::get($this->name.'emailreminder_'.$lang['id_lang']);

            $fields_reminderok[$lang['id_lang']] =  Configuration::get($this->name.'reminderok'.$this->_prefix_review.'_'.$lang['id_lang']);

            $fields_thankyou[$lang['id_lang']] =  Configuration::get($this->name.'thankyou'.$this->_prefix_review.'_'.$lang['id_lang']);

            $fields_newrev[$lang['id_lang']] =  Configuration::get($this->name.'newrev'.$this->_prefix_review.'_'.$lang['id_lang']);

            $fields_subpubem[$lang['id_lang']] =  Configuration::get($this->name.'subpubem_'.$lang['id_lang']);

            $fields_modrev[$lang['id_lang']] =  Configuration::get($this->name.'modrev'.$this->_prefix_review.'_'.$lang['id_lang']);

            $fields_abuserev[$lang['id_lang']] =  Configuration::get($this->name.'abuserev'.$this->_prefix_review.'_'.$lang['id_lang']);

            $fields_facvouc[$lang['id_lang']] =  Configuration::get($this->name.'facvouc'.$this->_prefix_review.'_'.$lang['id_lang']);

            $fields_revvouc[$lang['id_lang']] =  Configuration::get($this->name.'revvouc'.$this->_prefix_review.'_'.$lang['id_lang']);

            $fields_sugvouc[$lang['id_lang']] =  Configuration::get($this->name.'sugvouc'.$this->_prefix_review.'_'.$lang['id_lang']);

        }





        $data_config = array(

            'subresem' => $fields_subresem,

            'textresem' => $fields_textresem,

            'emailreminder' => $fields_emailreminder,

            'reminderok'.$this->_prefix_review => $fields_reminderok,

            'thankyou'.$this->_prefix_review => $fields_thankyou,

            'newrev'.$this->_prefix_review => $fields_newrev,

            'subpubem' =>$fields_subpubem,

            'modrev'.$this->_prefix_review => $fields_modrev,

            'abuserev'.$this->_prefix_review => $fields_abuserev,



            'facvouc'.$this->_prefix_review => $fields_facvouc,

            'revvouc'.$this->_prefix_review => $fields_revvouc,

            'sugvouc'.$this->_prefix_review => $fields_sugvouc,



        );



        return $data_config;



    }



    private function _reviewsemails(){



        $data_img_sizes = array();



        $available_types = ImageType::getImagesTypes('products');



        foreach ($available_types as $type){



            $id = $type['name'];

            $name = $type['name'].' ('.$type['width'].' x '.$type['height'].')';



            $data_item_size = array(

                'id' => $id,

                'name' => $name,

            );



            array_push($data_img_sizes,$data_item_size);





        }



        $fields_form = array(

            'form'=> array(



                'legend' => array(

                    'title' => $this->l('Reviews emails settings'),

                    'icon' => 'fa fa-envelope-o fa-lg'

                ),

                'input' => array(



                    array(

                        'type' => 'text_custom_email',

                        'label' => $this->l('Email administrator for notifications'),

                        'name' => 'mail',

                        'value'=> Configuration::get($this->name.'mail'),



                    ),





                    array(

                        'type' => 'checkbox_custom_email',

                        'label' => $this->l('Email notifications'),

                        'name' => 'noti',

                        'desc' => $this->l('Email notifications when customer add review / rating or review is reported as an abuse'),

                        'values' => array(

                            'value' => (int)Configuration::get($this->name.'noti')

                        ),





                    ),



                    array(

                        'type' => 'select',

                        'label' => $this->l('Image size for products'),

                        'name' => 'img_size_em',

                        'desc' => $this->l('The emails will contain a photo of each product.'),

                        'options' => array(

                            'query' => $data_img_sizes,

                            'id' => 'id',

                            'name' => 'name'

                        )

                    ),







                ),







            ),





        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );









        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'reviewsemailssettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesReviewsemailsSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );









        return  $helper->generateForm(array($fields_form,$fields_form1));

    }



    public function getConfigFieldsValuesReviewsemailsSettings(){



        $data_config = array(



            'img_size_em' => Configuration::get($this->name.'img_size_em'),



        );



        return $data_config;



    }



    private function _reviews16(){

        $_html = '';



        $_html .= '<div class="row">

    				<div class="col-lg-12">

    					<div class="row">';





        $_html .= '<div class="productTabs col-lg-2 col-md-3">



			<div class="list-group">

				<ul class="nav nav-pills nav-stacked" id="reviewsnavtabs16">

				    <li class="active"><a href="#globalsettings" data-toggle="tab" class="list-group-item"><i class="fa fa-cogs fa-lg"></i>&nbsp;'.$this->l('Global settings').'</a></li>



				    <li><a href="#productpage" data-toggle="tab" class="list-group-item"><i class="fa icon-AdminCatalog fa-lg"></i>&nbsp;'.$this->l('Product page').'</a></li>

				    <li><a href="#reviewsmanagement" data-toggle="tab" class="list-group-item"><i class="fa fa-reviews fa-lg"></i>&nbsp;'.$this->l('Reviews management').'</a></li>

				    <li><a href="#reviewcriteria" data-toggle="tab" class="list-group-item"><i class="fa fa-reviews fa-lg"></i>&nbsp;'.$this->l('Review Criteria').'</a></li>

				    <li><a href="#customeraccountreviewspage" data-toggle="tab" class="list-group-item"><i class="fa icon-AdminParentCustomer fa-lg"></i>&nbsp;'.$this->l('Customer account reviews page').'</a></li>

                    <li><a href="#lastreviewsblock" data-toggle="tab" class="list-group-item"><i class="fa fa-list-alt fa-lg"></i>&nbsp;'.$this->l('Last Reviews Block').'</a></li>

                    <li><a href="#starslistandsearch" data-toggle="tab" class="list-group-item"><i class="fa fa-bars fa-lg"></i>&nbsp;'.$this->l('Stars in Category and Search pages').'</a></li>

                    <li><a href="#rssfeed" data-toggle="tab" class="list-group-item"><i class="fa fa-rss fa-lg"></i>&nbsp;'.$this->l('Reviews RSS Feed').'</a></li>

                    <li><a href="#importcomments" data-toggle="tab" class="list-group-item"><i class="fa fa-comments-o fa-lg"></i>&nbsp;'.$this->l('Import Product Comments').'</a></li>

                    <li><a href="#gproductfeed" data-toggle="tab" class="list-group-item"><i class="fa fa-snippets fa-lg"></i>&nbsp;'.$this->l('Google Product Review Feeds for Google Shopping').'</a></li>



                    <li>&nbsp;</li>

                    <li><a href="#reviewsvoucheraddreviewtab" data-toggle="tab" class="list-group-item"><i class="fa fa-reviews fa-lg"></i>&nbsp;'.$this->l('Voucher settings, when a user add review').'</a></li>

				    <li><a href="#reviewsvouchersharereviewtab" data-toggle="tab" class="list-group-item"><i class="fa fa-facebook fa-lg"></i>&nbsp;'.$this->l('Voucher settings, when a user share review on the Facebook').'</a></li>



                    <li>&nbsp;</li>

                    <li><a href="#reviewsemailstab" data-toggle="tab" class="list-group-item"><i class="fa fa-envelope-o fa-lg"></i>&nbsp;'.$this->l('Reviews emails settings').'</a></li>

				    <li><a href="#responseadminemails" data-toggle="tab" class="list-group-item"><i class="fa fa-envelope-o fa-lg"></i>&nbsp;'.$this->l('Emails subjects settings').'</a></li>

				    <li><a href="#customerreminder" data-toggle="tab" class="list-group-item"><i class="fa fa-bell-o fa-lg"></i>&nbsp;'.$this->l('Customer Reminder settings').'</a></li>

				    <li><a href="#customerreminderstat" data-toggle="tab" class="list-group-item"><i class="fa fa-bar-chart fa-lg"></i>&nbsp;'.$this->l('Customer Reminder Statistics').'</a></li>

				    <li><a href="#cronhelp" data-toggle="tab" class="list-group-item"><i class="fa fa-tasks fa-lg"></i>&nbsp;'.$this->l('CRON HELP PRODUCT REVIEWS').'</a></li>



				    <li>&nbsp;</li>

				    <li><a href="#csvproductreviews" data-toggle="tab" class="list-group-item"><i class="fa fa-table fa-lg"></i>&nbsp;'.$this->l('CSV import/export product reviews settings').'</a></li>





				  </ul>

				  </div>

		</div>';





        $_html .= '<div class="tab-content col-lg-10 col-md-9">';

		$_html .= '<div class="tab-pane active" id="globalsettings">'.$this->_global().'</div>';

		$_html .= '<div class="tab-pane" id="productpage">'.$this->_productpage().'</div>';

        $_html .= '<div class="tab-pane" id="reviewsmanagement">'.$this->_reviewsmanagement().'</div>';



        $_html .= '<div class="tab-pane" id="reviewcriteria">'.$this->_reviewcriteria().'</div>';



        $_html .= '<div class="tab-pane" id="customeraccountreviewspage">'.$this->_customeraccountreviewspage().'</div>';

        $_html .= '<div class="tab-pane" id="lastreviewsblock">'.$this->_lastreviewsblock().'</div>';

        $_html .= '<div class="tab-pane" id="starslistandsearch">'.$this->_starslistandsearch().'</div>';

        $_html .= '<div class="tab-pane" id="rssfeed">'.$this->_rssfeed().'</div>';

        $_html .= '<div class="tab-pane" id="importcomments">'.$this->_importcomments().'</div>';

        $_html .= '<div class="tab-pane" id="gproductfeed">'.$this->_groductFeed().'</div>';





        $_html .= '<div class="tab-pane" id="reviewsvoucheraddreviewtab">'.$this->_voucherwhenaddreviewsettings16().'</div>';

        $_html .= '<div class="tab-pane" id="reviewsvouchersharereviewtab">'.$this->_voucherwhensharereviewsettings16().'</div>';





        $_html .= '<div class="tab-pane" id="reviewsemailstab">'.$this->_reviewsemails().'</div>';

        $_html .= '<div class="tab-pane" id="responseadminemails">'.$this->_responseadminemails().'</div>';



        $_html .= '<div class="tab-pane" id="customerreminder">'.$this->_customerreminder().'</div>';

        $_html .= '<div class="tab-pane" id="customerreminderstat">'.$this->_customerreminderstat().'</div>';

        $_html .= '<div class="tab-pane" id="cronhelp">'.$this->_cronhelp(array('url'=>'cron')).'</div>';



        $_html .= '<div class="tab-pane" id="csvproductreviews">'.$this->_csvImportExportProductReviews().'</div>';



    	$_html .= '</div>';







        $_html .= '</div></div></div>';



        return $_html;

    }



    public function _csvImportExportProductReviews(){

        include_once(dirname(__FILE__).'/classes/csvhelpr.class.php');

        $obj = new csvhelpr();

        $data_fields = $obj->getAvailableFields();

        $_html = '';





        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<div class="panel">



				<div class="panel-heading"><i class="fa fa-table fa-lg"></i>&nbsp;'.$this->l('CSV import/export product reviews settings').'</div>';

        } else {

            $_html .= '<div class="bootstrap">';

            $_html .= '

					<h3 class="title-block-content"><i class="fa fa-table fa-lg"></i>&nbsp;'.$this->l('CSV import/export product reviews settings').'</h3>';

        }





        $_html .= '<div class="input-group col-lg-12">';

        $_html .= '<label class="control-label"><b>'.$this->l('You must respect the following rules to upload the CSV Product Reviews correctly').':</b></label>';



        $_html .= '</div><br/>';



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<style type="text/css">

		    	@media (max-width: 992px) {';



            $i = 1;

            foreach($data_fields as $key => $item_field) {

                $_html .= '.table-responsive-row td.csv-ix:nth-of-type('.$i.'):before {

						content: "' . $key . '";

					}';

                $i++;

            }



            $_html .= '}

		    	</style>';

            $_html .= '<div class="table-responsive-row clearfix">';

        }





        $_html .= '<table class = "table csv-ix-table" width = "100%">

    		<thead>

			<tr class="nodrag nodrop">';

        $i = 1;

        foreach($data_fields as $key => $item_field) {

            $_html .='<th class="csv-ix-head"><span class="title_box">'.$key.'</span></th>';

            $i++;

        }





        $_html .= '</tr></thead>';







        $_html .= '<tbody><tr>';



        foreach($data_fields as $key => $item_field) {

            $name_filed = $item_field['name'];

            $example_field = $item_field['example'];

            $_html .= '<td class="csv-ix"><b>'.$name_filed.'</b><br/><br/>'.$example_field.'</td>';

        }



        $_html .= '</tr>';



        $_html .= '</tbody></table>';





        $_html .= '<br/>';

        $_html .= '<div class="input-group col-lg-12">';

        $_html .= '<label class="control-label">

                            <a href="../modules/'.$this->name.'/csv/example_product.csv" target="_blank"

                            class="a-underline '.(version_compare(_PS_VERSION_, '1.6', '>')?'btn btn-default pull':'button').'"

                            ><i class="fa fa-download fa-lg" aria-hidden="true"></i>&nbsp;'.$this->l('Click here to download an example of CSV file (you can write your reviews directly in it)').'</a>

                  </label>';



        $_html .= '</div><br/>';







        $_html .= '<div class="input-group col-lg-12">';

        $_html .= '<label class="control-label">

                           -&nbsp;'.$this->l('Save your file in CSV format. If you use Open Office, choose the option "Field separator: semi-colon"').'

                  </label>';



        $_html .= '</div><br/>';















        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '</div>';

            $_html .= '</div>';

        } else {

            $_html .= '</div>';

        }



        if(version_compare(_PS_VERSION_, '1.6', '<')){

            $_html .= '<div class="bootstrap">';

        }

        $_html .= '<div class="panel col-lg-6">';



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<div class="panel-heading"><i class="fa fa-download fa-lg"></i>&nbsp;'.$this->l('CSV Import').'</div>';

        } else {

            $_html .= '<h3 class="title-block-content"><i class="fa fa-download fa-lg"></i>&nbsp;'.$this->l('CSV Import').'</h3>';

        }



        $_html .= '<div class="input-group col-lg-6">';

        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" enctype="multipart/form-data">';

        $_html .= '<label class="control-label">';

        $_html .= '<input type="file" class="btn btn-default" id="csv_product" name="csv_product"/>';

        $_html .= '<br/>';

        $_html .= '<button name="product_csv" type="submit" class="'.(version_compare(_PS_VERSION_, '1.6', '>')?'btn btn-default pull':'button').'"

                    ><i class="fa fa-download fa-lg"></i>&nbsp;'.$this->l('Import reviews').'</button>';



        $_html .= '</label>';

        $_html .= '</form>';



        $_html .= '</div>';

        $_html .= '</div>';



        $_html .= '<div class="panel col-lg-6">';



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<div class="panel-heading"><i class="fa fa-upload fa-lg"></i>&nbsp;'.$this->l('CSV Export').'</div>';

        } else {

            $_html .= '<h3 class="title-block-content"><i class="fa fa-upload fa-lg"></i>&nbsp;'.$this->l('CSV Export').'</h3>';

        }





        $_html .= '<div class="input-group col-lg-6">';

        $_html .= '<label class="control-label">

                            <a href="'.$this->getURLMultiShop().'modules/'.$this->name.'/export_product.php?token='.$this->getokencron().'"

                            class="'.(version_compare(_PS_VERSION_, '1.6', '>')?'btn btn-default pull':'button').'"

        ><i class="fa fa-upload  fa-lg" aria-hidden="true"></i>&nbsp;'.$this->l('Export all reviews)').'</a>

                  </label>';



        $_html .= '</div>';



        $_html .= '</div>';



        if(version_compare(_PS_VERSION_, '1.6', '<')){

            $_html .= '</div>';

            $_html .= '<div class="clear"></div>';

        }



        return $_html;

    }





    public function _customerreminderstat(){





        include_once(dirname(__FILE__).'/classes/featureshelp.class.php');

        $obj = new featureshelp();



        $start_date_orders = Tools::getValue('start_date_orders');

        $end_date_orders = Tools::getValue('end_date_orders');







        $end_date = empty($end_date_orders)?date('Y-m-d H:i:s',strtotime("+1 day")):$end_date_orders;

        $start_date = empty($start_date_orders)?date('Y-m-d H:i:s',strtotime("-1 week")):$start_date_orders;







        $delayed_posts_data = $obj->getOrdersForReminder(array('start_date'=>$start_date,'end_date'=>$end_date));

        $delayed_posts = $delayed_posts_data['result'];

        $count_all = $delayed_posts_data['count_all'];









        return $this->drawDelayedPosts(array('posts'=>$delayed_posts,'count_all'=>$count_all,

            'start_date'=>$start_date,'end_date'=>$end_date));

    }



    public function drawDelayedPosts($data){





        $cookie = $this->context->cookie;

        $id_lang = (int)($cookie->id_lang);



        include_once(dirname(__FILE__).'/classes/featureshelp.class.php');

        $obj = new featureshelp();



        include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

        $obj_shopreviews = new gsnipreviewhelp();









        $_html  = '';



        $_html .= '<div class="bootstrap"><div class="panel">';



        if(version_compare(_PS_VERSION_, '1.6', '>')) {

            $_html .= '<div class="panel-heading"><i class="fa fa-bar-chart fa-lg"></i>&nbsp;' . $this->l('Customer Reminder Statistics') . '</div>';

        } else {

            $_html .= '<h3 class="title-block-content"><i class="fa fa-bar-chart fa-lg"></i>&nbsp;' . $this->l('Customer Reminder Statistics') . '</h3>';

        }

        $_html .= '<div class="table-responsive-row clearfix">';











        $txt_accepted_order_statuses = '';

        $accepted_order_statuses = $obj->getAcceptedOrderStatuses(array('id_lang'=>$id_lang));

        foreach($accepted_order_statuses as $accepted_order_status){

            $color_background = $accepted_order_status['color'];

            $payment_order  = $accepted_order_status['name'];

            $txt_accepted_order_statuses .=

                '<span style="background-color:'.$color_background.';color:white;padding:2px;border-radius:5px;line-height:25px;margin:3px 3px 3px 0">

                                        '.$payment_order.'

                                    </span>';

        }



        $_html .= '<div class="input-group col-lg-12" style="float:left;margin-right:10px">';

        $_html .= '<label class="control-label">'.$this->l('Send emails only for the orders with the current selected status').': '.$txt_accepted_order_statuses.'</label>';



        $_html .= '</div>';



        $_html .= '<br/>';





        $is_enabled_reminder = Configuration::get($this->name.'reminder');

        $_html .= '<div class="input-group col-lg-12" style="float:left;margin-right:10px">';

        $_html .= '<label class="control-label">'.$this->l('Enable or Disable Send a review reminder by email to customers').':

        <span style="background-color:#'.(($is_enabled_reminder)?'108510':'DC143C').';color:white;padding:3px;border-radius:5px;line-height:25px;margin:3px 0;text-align:center;font-weight:bold">

                                                ' . (($is_enabled_reminder)?$this->l('Yes'):$this->l('No')) . '

                                            </span>

        </label>';



        $_html .= '</div>';



        if($is_enabled_reminder) {

            $delay = Configuration::get($this->name . 'delay');

            $_html .= '<div class="input-group col-lg-12" style="float:left;margin-right:10px">';

            $_html .= '<label class="control-label">' . $this->l('Delay for sending reminder by email') . ':

        <span style="background-color:grey;color:white;padding:3px;border-radius:5px;line-height:25px;margin:3px 0;text-align:center;font-weight:bold">

                                                ' . $delay . '&nbsp;' . $this->l('days') . '

                                            </span>

        </label>';



            $_html .= '</div>';

        }









        $is_enabled_remindersec = Configuration::get($this->name.'remindersec'.$this->_prefix_review);



        $_html .= '<div class="input-group col-lg-12" style="float:left;margin-right:10px">';

        $_html .= '<label class="control-label">'.$this->l('Enable or Disable Send a review reminder by email to customers a second time?').':

        <span style="background-color:#'.(($is_enabled_remindersec)?'108510':'DC143C').';color:white;padding:3px;border-radius:5px;line-height:25px;margin:3px 0;text-align:center;font-weight:bold">

                                                ' . (($is_enabled_remindersec)?$this->l('Yes'):$this->l('No')) . '

                                            </span>

        </label>';



        $_html .= '</div>';



        if($is_enabled_remindersec) {

            $delay = Configuration::get($this->name . 'delaysec' . $this->_prefix_review);

            $_html .= '<div class="input-group col-lg-12" style="float:left;margin-right:10px">';

            $_html .= '<label class="control-label">' . $this->l('Days after the first emails were sent') . ':

        <span style="background-color:grey;color:white;padding:3px;border-radius:5px;line-height:25px;margin:3px 0;text-align:center;font-weight:bold">

                                                ' . $delay . '&nbsp;' . $this->l('days') . '

                                            </span>

        </label>';



            $_html .= '</div>';

        }





        $is_enabled_remrevsec = Configuration::get($this->name.'remrevsec'.$this->_prefix_review);



        $_html .= '<div class="input-group col-lg-12" style="float:left;margin-right:10px">';

        $_html .= '<label class="control-label">'.$this->l('Enable or Disable Send a review reminder by email to customer when customer already write review in shop?').':

        <span style="background-color:#'.(($is_enabled_remrevsec)?'108510':'DC143C').';color:white;padding:3px;border-radius:5px;line-height:25px;margin:3px 0;text-align:center;font-weight:bold">

                                                ' . (($is_enabled_remrevsec)?$this->l('Yes'):$this->l('No')) . '

                                            </span>

        </label>';



        $_html .= '</div>';



        $_html .= '<div style="clear:both"></div><br/>';





        $end_date = $data['end_date'];

        $start_date = $data['start_date'];



        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" enctype="multipart/form-data">';

        $_html .= '<br/><div class="input-group col-lg-3" style="float:left;margin-right:10px">

            <span class="input-group-addon">'.$this->l('start date').'</span>

            <input id=""

                   type="text" data-hex="true"

                   class="item_datepicker" name="start_date_orders" value="'.$start_date.'" />

            <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>



        </div>

        <div class="input-group col-lg-3" style="float:left">

            <span class="input-group-addon">'.$this->l('end date').'</span>

            <input id=""

                   type="text" data-hex="true" class="item_datepicker"

                   name="end_date_orders" value="'.$end_date.'" />

            <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>



        </div>

        <input type="submit" value="'.$this->l('Filter orders').'"

               class="btn btn-success" style="float:left;margin-left:10px"/>

        <div style="clear:both"></div>';

        $_html .= '</form>';



        $_html .= '<br/>';





        $posts = $data['posts'];





        if(!empty($posts)){



            if(version_compare(_PS_VERSION_, '1.6', '>')){

                $_html .= '<style type="text/css">

		    	@media (max-width: 992px) {

							.table-responsive-row td.reminder-td:nth-of-type(1):before {

						content: "'.$this->l('ID').'";

					}';

                if(version_compare(_PS_VERSION_, '1.5', '>')) {

                    $_html .= '.table-responsive-row td.reminder-td:nth-of-type(2):before {

						content: "' . $this->l('Reference') . '";

					}';

                }

                $_html .= '.table-responsive-row td.reminder-td:nth-of-type(3):before {

						content: "'.$this->l('Adding date').'";

					}

							.table-responsive-row td.reminder-td:nth-of-type(4):before {

						content: "'.$this->l('Order status').'";

					}';



                if(version_compare(_PS_VERSION_, '1.5', '>')) {

                    $_html .= '.table-responsive-row td.reminder-td:nth-of-type(5):before {

						content: "' . $this->l('Shop') . '";

					}';

                }



                $_html .= '.table-responsive-row td.reminder-td:nth-of-type(6):before {

						content: "'.$this->l('Email sent once?').'";

					}

					.table-responsive-row td.reminder-td:nth-of-type(7):before {

						content: "'.$this->l('Email sent twice?').'";

					}

					.table-responsive-row td.reminder-td:nth-of-type(8):before {

						content: "'.$this->l('Review already written?').'";

					}



					}



		    	</style>';

            }



            $_html .= '<table class = "table" width = "100%" id="orders-for-reminder">

    		<thead>

			<tr class="nodrag nodrop">

				<th style="text-align:center"><span class="title_box">'.$this->l('ID').'</span></th>';

            if(version_compare(_PS_VERSION_, '1.5', '>')) {

                $_html .= '<th style="text-align:center"><span class="title_box">' . $this->l('Reference') . '</span></th>';

            }

            $_html .= '<th style="text-align:center"><span class="title_box">'.$this->l('Adding date').'</span></th>

				<th style="text-align:center"><span class="title_box">'.$this->l('Order status').'</span></th>';

            if(version_compare(_PS_VERSION_, '1.5', '>')){

                $_html .= '<th style="text-align:center"><span class="title_box">'.$this->l('Shop').'</span></th>';

            }

            $_html .= '

                <th style="text-align:center"><span class="title_box">'.$this->l('Email sent once?').'</span></th>

				<th style="text-align:center"><span class="title_box">'.$this->l('Email sent twice?').'</span></th>

				<th style="text-align:center"><span class="title_box">'.$this->l('Review already written?').'</span></th>





			</tr></thead><tbody>';





            foreach($posts as $item){





                $order_id = $item['order_id'];



                $admin_url_to_order= 'index.php?'.(version_compare(_PS_VERSION_, '1.5', '>')?'controller':'tab').'=AdminOrders&id_order='.$order_id.'&vieworder&token='.Tools::getAdminToken('AdminOrders'.(int)(Tab::getIdFromClassName('AdminOrders')).(int)($cookie->id_employee)).'';



                $date_add = $item['date_add'];



                $id_customer = $item['customer_id'];





                $date_send = $item['date_send'];

                $date_send_second = $item['date_send_second'];



                $data_info_about_order = $obj->getOrderInfo(

                    array('order_id'=>$order_id, 'id_lang'=>$id_lang,



                    )

                );



                //if($order_id == 5)

                   //echo "<pre>"; var_dump($data_info_about_order);exit;



                $reference_order = isset($data_info_about_order[0]['reference'])?$data_info_about_order[0]['reference']:'';

                $payment_order = isset($data_info_about_order[0]['order_status_lng'])?$data_info_about_order[0]['order_status_lng']:'';

                $color_background = isset($data_info_about_order[0]['color'])?$data_info_about_order[0]['color']:'';



                $data_products = isset($data_info_about_order[0]['products'])?$data_info_about_order[0]['products']:'';



                //108510 - green

                //DC143C - red

                $product_text  = '';

                foreach($data_products as $_product) {

                    $id_product = $_product['id_product'];

                    $product_name = $_product['product_name'];

                    $product_url = $_product['product_url'];



                    $product_text .= '<a href="'.$product_url.'" style="text-decoration:underline">'.$product_name.'</a>&nbsp; - &nbsp;';



                    $is_add_review = $obj_shopreviews->isExistsReviewByCustomer(array('id_customer' => $id_customer,'id_product'=>array($id_product)));

                    $product_text .= '<span style="background-color:#' . (($is_add_review > 0) ? '108510' : 'DC143C') . ';color:white;padding:3px;border-radius:5px;line-height:25px;margin:3px 0;text-align:center">

                                                ' . (($is_add_review > 0) ? $this->l('Yes') : $this->l('No')) . '

                                            </span>





                                        <br/>';

                }









                $_html .=

                    '<tr>

					<td class="reminder-td">'.$order_id.'</td>';

                if(version_compare(_PS_VERSION_, '1.5', '>')) {

                    $_html .= '<td class="reminder-td"><a href="' . $admin_url_to_order . '" target="_blank" style="text-decoration:underline">' . $reference_order . '</a></td>';

                }

                $_html .= '<td class="reminder-td">'.$date_add.'</td>

					<td class="reminder-td">

					                <span style="background-color:'.$color_background.';color:white;padding:4px;border-radius:5px;line-height:25px;margin:3px 0">

                                        '.$payment_order.'

                                    </span>

					</td>';



                if(version_compare(_PS_VERSION_, '1.5', '>')){

                    $shops = Shop::getShops();

                    $name_shop = '';

                    foreach($shops as $_shop){

                        $id_shop_lists = $_shop['id_shop'];

                        if($id_shop_lists == $item['id_shop'])

                            $name_shop = $_shop['name'];

                    }



                    $_html .= '<td class="reminder-td">'.$name_shop.'</td>';

                }





                $_html .= '<td class="reminder-td text-align-left" id="first-time-'.$order_id.'">';



                if(empty($date_send)) {

                    $_html .= '<img src="../modules/' . $this->name . '/views/img/no_ok.gif"/>&nbsp;&nbsp;';

                    $_html .= '<a class="btn btn-success" href="javascript://" title="' . $this->l('Send order manually') . '"

                            onclick="statusdelayed = confirm(\'' . Tools::htmlentitiesUTF8($this->l('Are you sure to want Send order manually ')) . '\');if(!statusdelayed)return false;sendReminder(\'first\',\'' . $order_id . '\',\''.$this->getURLMultiShop().'\' );"

                            >';

                    $_html .= $this->l('Send order manually');

                    $_html .= '</a>';



                } else {

                    $_html .= '<img src="../modules/' . $this->name . '/views/img/ok.gif"/> &nbsp;&nbsp;'.$date_send;



                }



                $_html .= '</td>';





                $_html .= '<td class="reminder-td text-align-left" id="second-time-'.$order_id.'">';



                if(empty($date_send_second) && !empty($date_send)) {

                    $_html .= '<img src="../modules/' . $this->name . '/views/img/no_ok.gif"/>&nbsp;&nbsp;';

                    $_html .= '<a class="btn btn-success" href="javascript://" title="' . $this->l('Send order manually') . '"

                            onclick="statusdelayed = confirm(\'' . Tools::htmlentitiesUTF8($this->l('Are you sure to want Send order manually ')) . '\');if(!statusdelayed)return false;sendReminder(\'second\',\'' . $order_id . '\',\''.$this->getURLMultiShop().'\' );"

                            >';

                    $_html .= $this->l('Send order manually');

                    $_html .= '</a>';



                } else {

                    if(empty($date_send)) {

                        $_html .= '<img src="../modules/' . $this->name . '/views/img/no_ok.gif"/>';

                    }else {

                        $_html .= '<img src="../modules/' . $this->name . '/views/img/ok.gif" /> &nbsp;&nbsp;' . $date_send_second;

                    }



                }



                $_html .= '</td>';







                $_html .= '<td class="reminder-td" >'.$product_text.'</td>';





                $_html .= '</tr>';







            }



            $_html .= '</tbody>';



            $_html .= '</table>';





        } else {



            $_html .= '<div style="border:1px solid red; padding:10px; width:100%; text-align:center;font-weight:bold;margin-bottom:10px">

     				'.$this->l('There is no orders for customer reminder').'

     				</div>';





        }



        //if(version_compare(_PS_VERSION_, '1.6', '>')){

        $_html .= '</div>';

        $_html .= '</div></div>';

        //}

        return $_html;

    }





    private $_criterion_error =  0;

    private function _reviewcriteria(){



        if (Tools::isSubmit('addCriteria') || Tools::isSubmit('editgsnipreview') || $this->_criterion_error) {

            return $this->_displayAddReviewcriteriaForm16();

        } else {

            return $this->_displayReviewcriteriaGrid16();

        }

    }



    private function _displayAddReviewcriteriaForm16(){

        $token = Tools::getAdminTokenLite('AdminModules');

        $back = Tools::safeOutput(Tools::getValue('back', ''));

        $current_index = AdminController::$currentIndex;

        if (!isset($back) || empty($back))

            $back = $current_index.'&amp;configure='.$this->name.'&token='.$token;











        $id_block = Tools::getValue('id');



        if (Tools::isSubmit('id') && Tools::isSubmit('editgsnipreview'))

        {

            $this->_display = 'edit';



            include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

            $obj_gsnipreviewhelp = new gsnipreviewhelp();

            $_data = $obj_gsnipreviewhelp->getReviewCriteriaItem(array('id'=>(int)$id_block));





            $id_shop = isset($_data['item'][0]['id_shop']) ? explode(",",$_data['item'][0]['id_shop']) : array();





        } else {

            $this->_display = 'add';

            $id_shop = array();

        }





        $fields_form = array(

            'form' => array(

                'tinymce' => TRUE,

                'legend' => array(

                    'title' => !empty($id_block) ? $this->l('Edit criterion') : $this->l('Add new criterion'),

                    'icon' => !empty($id_block) ? 'icon-edit' : 'icon-plus-square'

                ),

                'input' => array(

                    array(

                        'type' => 'text',

                        'label' => $this->l('Criterion name'),

                        'name' => 'name',

                        'id' => 'name',

                        'lang' => TRUE,

                        'required' => TRUE,

                        'size' => 50,

                        'maxlength' => 50,

                    ),



                    array(

                        'type' => 'textarea',

                        'label' => $this->l('Description'),

                        'name' => 'description',

                        //'required' => TRUE,

                        'autoload_rte' => FALSE,

                        'lang' => TRUE,

                        'rows' => 5,

                        'cols' => 40,

                        'desc' => $this->l('If you do not want see description - just leave this field empty'),

                        'hint' => $this->l('If you do not want see description - just leave this field empty'),

                    ),









                    array(

                        'type' => 'cms_shop_association',

                        'label' => $this->l('Shop association'),

                        'name' => 'cat_shop_association',

                        'values'=>Shop::getShops(),

                        'selected_data'=>$id_shop,

                        'required' => TRUE,



                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Status'),

                        'name' => 'active',

                        'required' => FALSE,

                        'class' => 't',

                        'is_bool' => TRUE,

                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Enabled')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('Disabled')

                            )

                        ),

                    ),





                ),

                'buttons' => array(

                    'cancelBlock' => array(

                        'title' => $this->l('Cancel'),

                        'href' => $back.'&'.$this->name.'criteriaset=1',

                        'icon' => 'process-icon-cancel'

                    )

                ),

                'submit' => array(

                    'name' => ((!$id_block)?'submit':'update_item'),

                    'title' => ((!$id_block)?$this->l('Save'):$this->l('Update')),

                )

            )

        );









        $helper = new HelperForm();



        $helper->toolbar_scroll = true;

        $helper->toolbar_btn = $this->initToolbar();



        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;





        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesReviewCriteriaSettings(array('id_block'=>$id_block)),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );









        if (Tools::isSubmit('id') && Tools::isSubmit('editgsnipreview')) {

            $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name . '&id=' . $id_block .'&'.$this->name.'criteriaset=1';

            $helper->submit_action = 'editcriteriasettings';

        }else {

            $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name.'&'.$this->name.'criteriaset=1';

            $helper->submit_action = 'addcriteriasettings';

        }





        return $helper->generateForm(array($fields_form));



    }



    public function getConfigFieldsValuesReviewCriteriaSettings($data_in){

        $id = $data_in['id_block'];



        include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

        $obj_gsnipreviewhelp = new gsnipreviewhelp();

        $_data = $obj_gsnipreviewhelp->getReviewCriteriaItem(array('id'=>(int)$id));



        $item_data_lng = isset($_data['item']['data'])?$_data['item']['data']:array();

        $active = isset($_data['item'][0]['active'])?$_data['item'][0]['active']:0;





        $languages = Language::getLanguages(false);

        $fields_name = array();

        $fields_description = array();



        foreach ($languages as $lang)

        {

            $fields_name[$lang['id_lang']] = isset($item_data_lng[$lang['id_lang']]['name'])?$item_data_lng[$lang['id_lang']]['name']:'';



            $fields_description[$lang['id_lang']] = isset($item_data_lng[$lang['id_lang']]['description'])?$item_data_lng[$lang['id_lang']]['description']:'';

        }



        $config_array = array(

            'name' => $fields_name,

            'description' => $fields_description,

            'active' => $active,

        );



        return $config_array;

    }



    private function _displayReviewcriteriaGrid16(){

        $current_index = AdminController::$currentIndex;

        $token = Tools::getAdminTokenLite('AdminModules');



        ## add information ##

        include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

        $obj_gsnipreviewhelp = new gsnipreviewhelp();





        $_data = $obj_gsnipreviewhelp->getReviewCriteriaItems(array('start' => 0,'step'=>1000));

        $_items = $_data['items'];





        if(sizeof($_items)>0) {



            foreach ($_items as $_k=>$_item) {



                ## languages ##

                $ids_lng = isset($_item['ids_lng']) ? $_item['ids_lng'] : array();

                $lang_for_item = array();

                foreach ($ids_lng as $lng_id) {

                    $data_lng = Language::getLanguage($lng_id);

                    $lang_for_item[] = $data_lng['iso_code'];

                }

                $lang_for_item = implode(",", $lang_for_item);



                $_items[$_k]['ids_lng'] = $lang_for_item;

                ## languages ##



                ## shops ##

                $ids_shops = explode(",", $_item['id_shop']);



                $shops = Shop::getShops();

                $name_shop = array();

                foreach ($shops as $_shop) {

                    $id_shop_lists = $_shop['id_shop'];

                    if (in_array($id_shop_lists, $ids_shops))

                        $name_shop[] = $_shop['name'];

                }



                $name_shop = implode(", ", $name_shop);



                $_items[$_k]['id_shop'] = $name_shop;

                ## shops ##



            }

        }

        ## add information ##







        $fields_form = array(

            'form' => array(

                'legend' => array(

                    'title' => $this->l('Review Criteria'),

                    'icon' => 'fa fa-reviews fa-lg'

                ),

                'input' => array(

                    array(

                        'type' => 'cms_blocks_custom',

                        'label' => $this->l('Review Criteria'),

                        'name' => 'cms_blocks_custom',

                        'values' => $_items

                    )

                ),

                'buttons' => array(

                    'newBlock' => array(

                        'title' => $this->l('Add New Criterion'),

                        'href' => $current_index.'&amp;configure='.$this->name.'&amp;token='.$token.'&amp;addCriteria',

                        'class' => 'pull-right',

                        'icon' => 'process-icon-new'

                    )

                )

            )

        );





        $this->_display = 'index';



        $helper = new HelperForm();



        $helper->toolbar_scroll = true;

        $helper->toolbar_btn = $this->initToolbar();



        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = '';



        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');



        return $helper->generateForm(array($fields_form));

    }



    public function initToolbar()

    {

        $current_index = AdminController::$currentIndex;

        $token = Tools::getAdminTokenLite('AdminModules');

        $back = Tools::safeOutput(Tools::getValue('back', ''));

        if (!isset($back) || empty($back)) {

            $back = $current_index . '&amp;configure=' . $this->name . '&token=' . $token . '&'.$this->name.'criteriaset=1';

        } else {

            $back = $back.'&'.$this->name.'criteriaset=1';

        }

        switch ($this->_display)

        {

            case 'add':

                $this->toolbar_btn['cancel'] = array(

                    'href' => $back,

                    'desc' => $this->l('Cancel')

                );

                break;

            case 'edit':

                $this->toolbar_btn['cancel'] = array(

                    'href' => $back,

                    'desc' => $this->l('Cancel')

                );

                break;

            case 'index':

                $this->toolbar_btn['new'] = array(

                    'href' => $current_index.'&amp;configure='.$this->name.'&amp;token='.$token.'&amp;addCriteria&'.$this->name.'criteriaset=1',

                    'desc' => $this->l('Add New Criterion')

                );

                break;

            default:

                break;

        }

        return $this->toolbar_btn;

    }



    private function _groductFeed(){

        $_html = '';

            $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';

            $_html .= '<input type="hidden" value="1" name="googleproductsfeedsettings"/>';



            if(version_compare(_PS_VERSION_, '1.6', '>')){

                $_html .= '<div class="panel">



                    <div class="panel-heading"><i class="fa fa-snippets fa-lg"></i>&nbsp;'.$this->l('Google Product Review Feeds for Google Shopping').'</div>';

            } else {

                $_html .= '

                        <h3 class="title-block-content"><i class="fa fa-snippets fa-lg"></i>&nbsp;'.$this->l('Google Product Review Feeds for Google Shopping').'</h3>';

            }







            $_html .= '<p class="help-block">



                     '.$this->l('Google Product Review Feeds let content providers send product reviews to').'

                      <a target="_blank" style="text-decoration:underline"

			            href="http://www.google.com/shopping">'.$this->l('Google Shopping').'

			            </a>

			            </p>';



        $_html .= '<p class="help-block">

			<b>'.$this->l('Google Product Ratings Feeds').'</b>:&nbsp;'.$this->l('More info').':&nbsp;

			        <a target="_blank" style="text-decoration:underline"

			            href="https://developers.google.com/product-review-feeds/">https://developers.google.com/product-review-feeds/

			            </a>

					</p>



			            <br/><br/>



                    <b>'.$this->l('Your CRON URL to call').'</b>:&nbsp;

                    <a href="'.$this->getURLMultiShop().'modules/'.$this->name.'/cronreviews.php?token='.$this->getokencron().'"

                        style="text-decoration:underline;font-weight:bold">

                        '.$this->getURLMultiShop().'modules/'.$this->name.'/cronreviews.php?token='.$this->getokencron().'

                        </a>



                         <br/><br/><br/><br/>';



                $_html .= '<input type="submit" value="'.$this->l('Regenerate Google Product Ratings Feed').'" name="submitgooglereviews"

                                class="'.(version_compare(_PS_VERSION_, '1.6', '>')?'btn btn-primary pull':'button').'"/>';





        $_html .= ' &nbsp; <a target="_blank" style="text-decoration:underline;font-weight:bold" ';

        if($this->_is_cloud){

            $_html .= 'href="'._PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/'.$this->name.'/upload/reviews.xml"';

        } else {

            $_html .= 'href="'._PS_BASE_URL_SSL_.__PS_BASE_URI__.'upload/'.$this->name.'/reviews.xml"';

        }

        $_html .= '>';

        if($this->_is_cloud){

            $_html .=	''._PS_BASE_URL_SSL_.__PS_BASE_URI__.'modules/'.$this->name.'/upload/reviews.xml';

        } else {

            $_html .=	''._PS_BASE_URL_SSL_.__PS_BASE_URI__.'upload/'.$this->name.'/reviews.xml';

        }

        $_html .=	'</a>';



            $_html .= '</p>';









            if(version_compare(_PS_VERSION_, '1.6', '>')){

                $_html .= '</div>';

            }





            $_html .= '</form>';







        $_html .= $this->_cronhelp(array('url'=>'cronreviews'));



        return $_html;

    }



    private function _importcomments(){

        $_html = '';

        include_once(dirname(__FILE__).'/classes/importhelp.class.php');

        $obj = new importhelp();

        if ($obj->ifExsitsTableProductcomments()){

            $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';

            $_html .= '<input type="hidden" value="1" name="importcommentssettings"/>';

            $_html .= $this->_productComments();

            $_html .= '</form>';

        } else{

            if(version_compare(_PS_VERSION_, '1.6', '>')){

                $_html .= '<div class="panel">



				<div class="panel-heading"><i class="fa fa-comments-o fa-lg"></i>&nbsp;'.$this->l('Import Product Comments').'</div>';

            } else {

                $_html .= '

					<h3 class="title-block-content"><i class="fa fa-comments-o fa-lg"></i>&nbsp;'.$this->l('Import Product Comments').'</h3>';

            }

            $_html .= '<b>'.$this->l('Your database no contains Product comments for imports').'</b>';

            if(version_compare(_PS_VERSION_, '1.6', '>')){

                $_html .= '</div>';

            }



        }

        return $_html;

    }



    private function _rssfeed(){

        $fields_form = array(

            'form'=> array(



                'legend' => array(

                    'title' => $this->l('Reviews RSS Feed'),

                    'icon' => 'fa fa-rss fa-lg'

                ),

                'input' => array(



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable RSS Feed'),

                        'name' => 'rsson',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Title of your RSS Feed'),

                        'name' => 'rssname',

                        'id' => 'rssname',

                        'lang' => TRUE,

                        //'required' => TRUE,

                        'size' => 50,

                        //'maxlength' => 50,

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Description of your RSS Feed'),

                        'name' => 'rssdesc',

                        'id' => 'rssdesc',

                        'lang' => TRUE,

                        //'required' => TRUE,

                        'size' => 50,

                        //'maxlength' => 50,

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Number of items in RSS Feed'),

                        'name' => 'number_rssitems',

                        'class' => ' fixed-width-sm',



                    ),





                ),







            ),





        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );









        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'rssfeedsettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesRssfeedSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );









        return  $helper->generateForm(array($fields_form,$fields_form1));

    }



    public function getConfigFieldsValuesRssfeedSettings(){

        $languages = Language::getLanguages(false);

        $fields_rssname = array();

        $fields_rssdesc = array();



        foreach ($languages as $lang)

        {

            $fields_rssname[$lang['id_lang']] =  Configuration::get($this->name.'rssname_'.$lang['id_lang']);



            $fields_rssdesc[$lang['id_lang']] =  Configuration::get($this->name.'rssdesc_'.$lang['id_lang']);

        }





        $data_config = array(

            'rsson' => Configuration::get($this->name.'rsson'),

            'rssname' => $fields_rssname,

            'rssdesc' => $fields_rssdesc,

            'number_rssitems' => (int)Configuration::get($this->name.'number_rssitems'),



        );



        return $data_config;



    }



    private function _starslistandsearch(){

        $fields_form = array(

            'form'=> array(

                'legend' => array(

                    'title' => $this->l('Stars in Category and Search pages'),

                    'icon' => 'fa fa-bars fa-lg'

                ),

                'input' => array(



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable stars on the category and search pages'),

                        'name' => 'starscat',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                ),







            ),





        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );









        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'starslistandsearchsettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesStarslistandsearchSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );





        $_html = '';

        if (version_compare(_PS_VERSION_, '1.7', '>')) {

            $_html .= '<div class="panel">

                    <div class="panel-heading"><i class="fa fa-question-circle fa-lg"></i>&nbsp;' . $this->l('Frequently Asked Questions') . '</div>';

            $_html .= '<div class="row">';

            $_html .= $this->_faqStars17_gsnipreview();

            $_html .= '</div></div>';

        }



        return  $helper->generateForm(array($fields_form,$fields_form1)).$_html;

    }



    public function getConfigFieldsValuesStarslistandsearchSettings(){



        $data_config = array(

            'starscat' => (int)Configuration::get($this->name.'starscat'),





        );



        return $data_config;



    }





    private function block_last_reviews(){



        $data_img_sizes = array();



        $available_types = ImageType::getImagesTypes('products');



        foreach ($available_types as $type){



            $id = $type['name'];

            $name = $type['name'].' ('.$type['width'].' x '.$type['height'].')';



            $data_item_size = array(

                'id' => $id,

                'name' => $name,

            );



            array_push($data_img_sizes,$data_item_size);





        }





        $block_last_reviews = array(

            'type' => 'block_last_reviews',

            'label' => $this->l('Display block on the following pages'),



            'name' => 'block_last_reviews',

            'values'=> array(

                'blocklr_home' => array('name'=>$this->l('for home page'),

                    'status' => Configuration::get($this->name.'blocklr_home'),

                    'position'=>Configuration::get($this->name.'blocklr_home_pos'),

                    'number_display_reviews' => array('number_display_reviews' => (int)Configuration::get($this->name.'blocklr_home_ndr'), 'name'=>'blocklr_home_ndr'),

                    'truncate' => array('truncate' => (int)Configuration::get($this->name.'blocklr_home_tr'), 'name'=>'blocklr_home_tr'),

                    'imsize' => array('imsize' => Configuration::get($this->name.'blocklr_home_im'), 'name'=>'blocklr_home_im'),

                    'width' => array('width' => (int)Configuration::get($this->name.'blocklr_home_w'), 'name'=>'blocklr_home_w')),

                'blocklr_cat' => array('name'=>$this->l('for each category page'),

                    'status' => Configuration::get($this->name.'blocklr_cat'),

                    'position'=>Configuration::get($this->name.'blocklr_cat_pos'),

                    'number_display_reviews' => array('number_display_reviews' => (int)Configuration::get($this->name.'blocklr_cat_ndr'), 'name'=>'blocklr_cat_ndr'),

                    'truncate' => array('truncate' => (int)Configuration::get($this->name.'blocklr_cat_tr'), 'name'=>'blocklr_cat_tr'),

                    'imsize' => array('imsize' => Configuration::get($this->name.'blocklr_cat_im'), 'name'=>'blocklr_cat_im'),

                    'width' => array('width' => (int)Configuration::get($this->name.'blocklr_cat_w'), 'name'=>'blocklr_cat_w')),

                'blocklr_man' => array('name'=>$this->l('for each manufacturer/brand page'),

                    'status' => Configuration::get($this->name.'blocklr_man'),

                    'position'=>Configuration::get($this->name.'blocklr_man_pos'),

                    'number_display_reviews' => array('number_display_reviews' => (int)Configuration::get($this->name.'blocklr_man_ndr'), 'name'=>'blocklr_man_ndr'),

                    'truncate' => array('truncate' => (int)Configuration::get($this->name.'blocklr_man_tr'), 'name'=>'blocklr_man_tr'),

                    'imsize' => array('imsize' => Configuration::get($this->name.'blocklr_man_im'), 'name'=>'blocklr_man_im'),

                    'width' => array('width' => (int)Configuration::get($this->name.'blocklr_man_w'), 'name'=>'blocklr_man_w')),

                'blocklr_prod' => array('name'=>$this->l('for each product page'),

                    'status' => Configuration::get($this->name.'blocklr_prod'),

                    'position'=>Configuration::get($this->name.'blocklr_prod_pos'),

                    'number_display_reviews' => array('number_display_reviews' => (int)Configuration::get($this->name.'blocklr_prod_ndr'), 'name'=>'blocklr_prod_ndr'),

                    'truncate' => array('truncate' => (int)Configuration::get($this->name.'blocklr_prod_tr'), 'name'=>'blocklr_prod_tr'),

                    'imsize' => array('imsize' => Configuration::get($this->name.'blocklr_prod_im'), 'name'=>'blocklr_prod_im'),

                    'width' => array('width' => (int)Configuration::get($this->name.'blocklr_prod_w'), 'name'=>'blocklr_prod_w')),

                'blocklr_oth' => array('name'=>$this->l('for other pages'),

                    'status' => Configuration::get($this->name.'blocklr_oth'),

                    'position'=>Configuration::get($this->name.'blocklr_oth_pos'),

                    'number_display_reviews' => array('number_display_reviews' => (int)Configuration::get($this->name.'blocklr_oth_ndr'), 'name'=>'blocklr_oth_ndr'),

                    'truncate' => array('truncate' => (int)Configuration::get($this->name.'blocklr_oth_tr'), 'name'=>'blocklr_oth_tr'),

                    'imsize' => array('imsize' => Configuration::get($this->name.'blocklr_oth_im'), 'name'=>'blocklr_oth_im'),

                    'width' => array('width' => (int)Configuration::get($this->name.'blocklr_oth_w'), 'name'=>'blocklr_oth_w')),

                'blocklr_chook' => array('name'=>$this->l('for CUSTOM HOOK pages'),

                    'status' => Configuration::get($this->name.'blocklr_chook'),

                    'position'=>Configuration::get($this->name.'blocklr_chook_pos'),

                    'number_display_reviews' => array('number_display_reviews' => (int)Configuration::get($this->name.'blocklr_chook_ndr'), 'name'=>'blocklr_chook_ndr'),

                    'truncate' => array('truncate' => (int)Configuration::get($this->name.'blocklr_chook_tr'), 'name'=>'blocklr_chook_tr'),

                    'imsize' => array('imsize' => Configuration::get($this->name.'blocklr_chook_im'), 'name'=>'blocklr_chook_im'),

                    'width' => array('width' => (int)Configuration::get($this->name.'blocklr_chook_w'), 'name'=>'blocklr_chook_w')),

            ),

            'available_pos' => array(

                'top'=>$this->l('Top'),

                'bottom'=>$this->l('Bottom'),

                'leftcol'=>$this->l('Left Column'),

                'rightcol'=>$this->l('Right Column'),



            ),

            'available_pos_home' => array(

                'top'=>$this->l('Top'),

                'home'=>$this->l('Home'),

                'bottom'=>$this->l('Bottom'),

                'leftcol'=>$this->l('Left Column'),

                'rightcol'=>$this->l('Right Column'),



            ),

            'available_pos_chook' => array(

                'chook'=>$this->l('Custom Hook'),





            ),

            'image_sizes' => $data_img_sizes,

        );



        return $block_last_reviews;

    }



    private function _lastreviewsblock(){









        $fields_form = array(

            'form'=> array(

                'legend' => array(

                    'title' => $this->l('Last Reviews Block'),

                    'icon' => 'fa fa-list-alt fa-lg'

                ),

                'input' => array(





                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Block "Last Reviews"'),

                        'name' => 'is_blocklr',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),







                    $this->block_last_reviews(),









                ),







            ),





        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );









        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'lastreviewsblocksettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesLastreviewsblockpageSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );





        $_html = '';

        $_html .= "<script type=\"text/javascript\">

    	$('document').ready( function() {



    		$( \"label[for='is_blocklr_on']\" ).click(function() {

                $( \".block_last_reviews\" ).parent().css('display','block');

            });



            $( \"label[for='is_blocklr_off']\" ).click(function() {

    	        $( \".block_last_reviews\" ).parent().css('display','none');



    	    });



    	    if(".(int)Configuration::get($this->name.'is_blocklr')." == 0){

    	       $( \".block_last_reviews\" ).parent().css('display','none');

    	    }



    	});



    	</script>";



        return  $_html . $helper->generateForm(array($fields_form,$fields_form1)).$this->_customhookhelp();

    }



    public function getConfigFieldsValuesLastreviewsblockpageSettings(){



        $data_config = array(

            'is_blocklr' => (int)Configuration::get($this->name.'is_blocklr'),





        );



        return $data_config;



    }



    private function _customhookhelp(){

        $_html  = '';



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<div class="panel">



		<div class="panel-heading"><i class="fa fa-question-circle fa-lg"></i>&nbsp;'.$this->l('Frequently Asked Questions').'</div>';

        } else {



            $_html .= '<fieldset>

		<legend><img src="../modules/'.$this->name.'/views/img/icon/ico_help.gif" />'.$this->l('Frequently Asked Questions').'</legend>



		';

        }



        if(version_compare(_PS_VERSION_, '1.5', '>')){



            $_html .= '<div class="row ">



                       ';



            $_html .= '<div class="span">

                          <p>

                             <span style="font-weight: bold; font-size: 15px;" class="question">

                             	- <b style="color:red">'.$this->l('CUSTOM HOOK HELP:').'</b> '.$this->l('How I can show block last reviews on a single page (CMS or blog for example) ?').'

                             </span>

                             <br/><br/>

                             <span style="color: black;" class="answer">

                             	   '.$this->l('You just need to add a line of code to the tpl file of the page where you want to add the block last reviews.').'

                                   <pre>{hook h=\'lastReviewsMitrocops\'}</pre>

                              </span>

                         </p>

                       </div><br/><br/>';

        }









        if(version_compare(_PS_VERSION_, '1.5', '>')){

            $_html .= '</div>';

        }



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '</div>';

        } else {

            $_html .= '</fieldset>';

        }



        return $_html;

    }



    private function _customeraccountreviewspage(){

        $fields_form = array(

            'form'=> array(

                'legend' => array(

                    'title' => $this->l('Customer account reviews page'),

                    'icon' => 'fa icon-AdminParentCustomer fa-lg'

                ),

                'input' => array(





                    array(

                        'type' => 'text',

                        'label' => $this->l('Number of reviews per page on Customer account page'),

                        'name' => 'revperpagecus',

                        'class' => ' fixed-width-sm',



                    ),







                ),







            ),





        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );









        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'customeraccountreviewspagesettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesCustomeraccountreviewspageSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );









        return  $helper->generateForm(array($fields_form,$fields_form1));

    }



    public function getConfigFieldsValuesCustomeraccountreviewspageSettings(){



        $data_config = array(

            'revperpagecus' => (int)Configuration::get($this->name.'revperpagecus'),





        );



        return $data_config;



    }



    private function _reviewsmanagement(){





        $fields_form = array(

            'form'=> array(

                'legend' => array(

                    'title' => $this->l('Reviews management'),

                    'icon' => 'fa fa-reviews fa-lg'

                ),

                'input' => array(





                    array(

                        'type' => 'switch',

                        'label' => $this->l('Require Admin Approval'),

                        'name' => 'is_approval',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),









                    array(

                        'type' => 'block_radio_buttons_reviews_custom',

                        'label' => $this->l('Who can add review?'),



                        'name' => 'block_radio_buttons_reviews_custom',

                        'values'=> array(

                            'value' => Configuration::get($this->name.'whocanadd')

                        ),



                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('The user can add more one review'),

                        'name' => 'is_onerev',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('MULTILANGUAGE. Separates different languages comments depended on the language selected by the customer (e.g. only English comments on the English site)'),

                        'name' => 'rswitch_lng',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),





                    array(

                        'type' => 'text',

                        'label' => $this->l('Number of reviews per page on All Reviews page'),

                        'name' => 'revperpageall',

                        'class' => ' fixed-width-sm',



                    ),





                    array(

                        'type' => 'text',

                        'label' => $this->l('Number of reviews per page on moderation page'),

                        'name' => 'adminrevperpage',

                        'class' => ' fixed-width-sm',



                    ),







                ),







            ),





        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );









        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'reviewsmanagementsettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesReviewsmanagementSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );







        $_html = '';

        $_html .= "<script type=\"text/javascript\">

    	$('document').ready( function() {



            setTimeout(function () {

                $( \".block_radio_buttons_reviews_custom\").parent().show(200);



            }, 1000);





    	});



    	</script>";



        return  $_html.$helper->generateForm(array($fields_form,$fields_form1));

    }



    public function getConfigFieldsValuesReviewsmanagementSettings(){



        $data_config = array(

            'is_approval' => Configuration::get($this->name.'is_approval'),

            'rswitch_lng' => Configuration::get($this->name.'rswitch_lng'),

            'revperpageall' => Configuration::get($this->name.'revperpageall'),

            'adminrevperpage' => Configuration::get($this->name.'adminrevperpage'),



            'is_onerev' => Configuration::get($this->name.'is_onerev'),





        );



        return $data_config;

    }



    private function _productpage(){

        $fields_form = array(

            'form'=> array(

                'legend' => array(

                    'title' => $this->l('Product page Settings'),

                    'icon' => 'fa icon-AdminCatalog fa-lg'

                ),

                'input' => array(







                    array(

                        'type' => 'select',

                        'label' => $this->l('Product tabs'),

                        'name' => 'ptabs_type',

                        'options' => array(

                            'query' => array(

                                array(

                                    'id' => 1,

                                    'name' => $this->l('Standard theme without Tabs')),



                                array(

                                    'id' => 2,

                                    'name' => $this->l('Custom theme with tabs on product page'),

                                ),

                            ),

                            'id' => 'id',

                            'name' => 'name'

                        ),

                        'desc' => $this->l('On a standard PrestaShop 1.6 theme, the product page no longer has tabs for the various sections.').

                                  '&nbsp;'.$this->l('But some custom themes have added back tabs on the product page. ')

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Helpful review functional'),

                        'name' => 'is_helpfulf',

                        'desc' => $this->l('Enable or Disable Helpful review functional'),

                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Report abuse functional'),

                        'name' => 'is_abusef',

                        'desc' => $this->l('Enable or Disable Report abuse functional'),

                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),





                    array(

                        'type' => 'select_stars',

                        'label' => $this->l('Style stars'),

                        'name' => 'select_stars',

                        'values' => array(

                           'stylestars'=>Configuration::get($this->name.'stylestars'),

                        ),



                        'desc' => $this->l('Choose your style for the stars icons')

                    ),



                    array(

                        'type' => 'select_stars_custom',

                        'label' => $this->l('Enable or Disable stars for each reviews'),

                        'name' => 'starratingon',

                        'values' => array(

                            'value'=>Configuration::get($this->name.'starratingon'),

                            'stylestars'=>Configuration::get($this->name.'stylestars'),



                        ),



                    ),



                    array(

                        'type' => 'select',

                        'label' => $this->l('Hook to display block with ratings, number of reviews etc'),

                        'name' => 'hooktodisplay',

                        'class' => ' fixed-width-xxl',

                        'options' => array(

                            'query' => array(

                                array(

                                    'id' => 'extra_right',

                                    'name' => $this->l('displayLeftColumnProduct (Extra right)'),



                                ),



                                array(

                                    'id' => 'extra_left',

                                    'name' => $this->l('displayRightColumnProduct (Extra Left)'),

                                ),

                                array(

                                    'id' => 'product_actions',

                                    'name' => $this->l('displayProductButtons (Product actions)'),

                                ),

                                array(

                                    'id' => 'product_footer',

                                    'name' => $this->l('displayFooterProduct (Product footer)'),

                                ),

                                array(

                                    'id' => 'none',

                                    'name' => $this->l('None'),

                                ),

                            ),

                            'id' => 'id',

                            'name' => 'name'

                        ),

                        'desc' => $this->l('Block with ratings, number of reviews, link to post a new review'),

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Number of reviews per page on Product page'),

                        'name' => 'revperpage',

                        'class' => ' fixed-width-sm',



                    ),





                ),







            ),





        );



        $fields_form1 = array(

            'form'=> array(

                'legend' => array(

                    'title' => $this->l('Social Buttons'),

                    'icon' => 'fa fa-facebook fa-lg'

                ),

                'input' => array(





                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Social buttons'),

                        'name' => 'rsoc_on',

                        'desc' => $this->l('Enable or Disable Social buttons for each Product Review'),



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Display count box'),

                        'name' => 'rsoccount_on',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),











                ),

                'desc' => $this->l('You can configure "Voucher, when a user share review on the Facebook" in the').

                        ' <a href="javascript:void(0)" onclick="tabs_custom(103)" style="text-decoration:underline;font-weight:bold;color:red">'.$this->l('Voucher Settings').'</a> '.

                        $this->l('Tab'),





            ),





        );



        $fields_form2 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );





        $_html = '';

        $_html .= '<script type="text/javascript">

    	function selectImgRating(id)

			{

			if(id==0){

				$("#star-active-green").css("display","none");

				$("#star-active-blue").css("display","none");

				$("#star-active-yellow").css("display","block");



				$("#star-active-green-block").css("display","none");

				$("#star-active-blue-block").css("display","none");

				$("#star-active-yellow-block").css("display","block");



			} else if(id==1) {

				$("#star-active-blue").css("display","none");

				$("#star-active-yellow").css("display","none");

				$("#star-active-green").css("display","block");



				$("#star-active-blue-block").css("display","none");

				$("#star-active-yellow-block").css("display","none");

				$("#star-active-green-block").css("display","block");



			} else if(id==2){

				$("#star-active-yellow").css("display","none");

				$("#star-active-green").css("display","none");

				$("#star-active-blue").css("display","block");



				$("#star-active-yellow-block").css("display","none");

				$("#star-active-green-block").css("display","none");

				$("#star-active-blue-block").css("display","block");

			}

			}

		</script>';



        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'productpagesettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesProductpageSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );









        return  $_html . $helper->generateForm(array($fields_form,$fields_form1,$fields_form2));

    }



    public function getConfigFieldsValuesProductpageSettings(){



        $data_config = array(

            'ptabs_type' => Configuration::get($this->name.'ptabs_type'),

            'hooktodisplay' => Configuration::get($this->name.'hooktodisplay'),

            'revperpage' => Configuration::get($this->name.'revperpage'),

            'rsoc_on' => Configuration::get($this->name.'rsoc_on'),

            'rsoccount_on' => Configuration::get($this->name.'rsoccount_on'),

            'is_abusef' => Configuration::get($this->name.'is_abusef'),

            'is_helpfulf' => Configuration::get($this->name.'is_helpfulf'),





        );



        return $data_config;

    }



    private function _global(){

        $fields_form = array(

            'form'=> array(

                'legend' => array(

                    'title' => $this->l('Global Settings'),

                    'icon' => 'fa fa-cogs fa-lg'

                ),

                'input' => array(







                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Product Reviews and Ratings'),

                        'name' => 'rvis_on',

                        'desc' => $this->l('Enable or Disable Product Reviews and Ratings'),

                        'hint' => $this->l('Enable or Disable Product Reviews and Ratings'),



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable "Rating/Review Criteria" field(s)'),

                        'name' => 'ratings_on',

                        'desc' => $this->l('You also can add').' <a href="javascript:void(0)" onclick="tabs_custom(102)">'.$this->l('Review Criteria').'</a><br/>'.

                        $this->l('If you delete/disable all Review criteria will be displayed only Rating field in Write Your Review form'),

                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable "Avatar" field'),

                        'name' => 'is_avatar'.$this->_prefix_review,



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable "Files" field'),

                        'name' => 'is_files'.$this->_prefix_review,



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Number of files user can add for review'),

                        'name' => 'ruploadfiles',

                        'class' => ' fixed-width-sm',





                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable "Title" field'),

                        'name' => 'title_on',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable "Text" field'),

                        'name' => 'text_on',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'text',

                        'label' => $this->l('Minimum chars the user must write in the Text field for add review'),

                        'name' => 'rminc',

                        'class' => ' fixed-width-sm',





                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable "IP" or "City and Country Name" field'),

                        'name' => 'ip_on',

                        'desc' => $this->l('City and Country Name will be displayed, if your enable Geolocation in admin  panel -> Preferences -> Geolocation'),

                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Captcha'),

                        'name' => 'is_captcha',



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),









                ),







            ),





        );



        $fields_form1 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );







        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'globalsettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesGlobalSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );



        $_html = '';

        $_html .= "<script type=\"text/javascript\">

    	$('document').ready( function() {



    		$( \"label[for='rvis_on_on']\" ).click(function() {

                $( \"a[href='#productpage']\" ).css('display','block');

                $( \"a[href='#reviewsmanagement']\" ).css('display','block');

                $( \"a[href='#reviewcriteria']\" ).css('display','block');

                $( \"a[href='#customeraccountreviewspage']\" ).css('display','block');

                $( \"a[href='#lastreviewsblock']\" ).css('display','block');

                $( \"a[href='#starslistandsearch']\" ).css('display','block');

                $( \"a[href='#rssfeed']\" ).css('display','block');

                $( \"a[href='#importcomments']\" ).css('display','block');

                $( \"a[href='#gproductfeed']\" ).css('display','block');



                $( \"a[href='#customerreminderstat']\" ).css('display','block');

                 $( \"a[href='#csvproductreviews']\" ).css('display','block');



                $( \"a[href='#reviewsvoucheraddreviewtab']\" ).css('display','block');

                $( \"a[href='#reviewsvouchersharereviewtab']\" ).css('display','block');

                $( \"a[href='#reviewsemailstab']\" ).css('display','block');

                $( \"a[href='#responseadminemails']\" ).css('display','block');

                $( \"a[href='#customerreminder']\" ).css('display','block');

                $( \"a[href='#cronhelp']\" ).css('display','block');



                $( \"input[name='ratings_on']\").parent().parent().parent().show(200);

                $( \"input[name='is_avatar".$this->_prefix_review."']\").parent().parent().parent().show(200);

                $( \"input[name='is_files".$this->_prefix_review."']\").parent().parent().parent().show(200);

                $( \"input[name='ruploadfiles']\").parent().parent().show(200);

                $( \"input[name='rminc']\").parent().parent().show(200);

                $( \"input[name='title_on']\").parent().parent().parent().show(200);

                $( \"input[name='text_on']\").parent().parent().parent().show(200);

                $( \"input[name='ip_on']\").parent().parent().parent().show(200);

                $( \"input[name='is_captcha']\").parent().parent().parent().show(200);







			});



            $( \"label[for='rvis_on_off']\" ).click(function() {

    	        $( \"a[href='#productpage']\" ).css('display','none');

    	        $( \"a[href='#reviewsmanagement']\" ).css('display','none');

    	        $( \"a[href='#reviewcriteria']\" ).css('display','none');

                $( \"a[href='#customeraccountreviewspage']\" ).css('display','none');

                $( \"a[href='#lastreviewsblock']\" ).css('display','none');

                $( \"a[href='#starslistandsearch']\" ).css('display','none');

                $( \"a[href='#rssfeed']\" ).css('display','none');

                $( \"a[href='#importcomments']\" ).css('display','none');

                $( \"a[href='#gproductfeed']\" ).css('display','none');



                $( \"a[href='#customerreminderstat']\" ).css('display','none');

                 $( \"a[href='#csvproductreviews']\" ).css('display','none');



                $( \"a[href='#reviewsvoucheraddreviewtab']\" ).css('display','none');

                $( \"a[href='#reviewsvouchersharereviewtab']\" ).css('display','none');

                $( \"a[href='#reviewsemailstab']\" ).css('display','none');

                $( \"a[href='#responseadminemails']\" ).css('display','none');

                $( \"a[href='#customerreminder']\" ).css('display','none');

                $( \"a[href='#cronhelp']\" ).css('display','none');



                $( \"input[name='ratings_on']\").parent().parent().parent().hide(200);

                $( \"input[name='is_avatar".$this->_prefix_review."']\").parent().parent().parent().hide(200);

                $( \"input[name='is_files".$this->_prefix_review."']\").parent().parent().parent().hide(200);

                $( \"input[name='ruploadfiles']\").parent().parent().hide(200);

                $( \"input[name='rminc']\").parent().parent().hide(200);

                $( \"input[name='title_on']\").parent().parent().parent().hide(200);

                $( \"input[name='text_on']\").parent().parent().parent().hide(200);

                $( \"input[name='ip_on']\").parent().parent().parent().hide(200);

                $( \"input[name='is_captcha']\").parent().parent().parent().hide(200);

    	    });



    	    if(".(int)Configuration::get($this->name.'rvis_on')." == 0){

    	       $( \"a[href='#productpage']\" ).css('display','none');

    	       $( \"a[href='#reviewsmanagement']\" ).css('display','none');

    	       $( \"a[href='#reviewcriteria']\" ).css('display','none');

                $( \"a[href='#customeraccountreviewspage']\" ).css('display','none');

                $( \"a[href='#lastreviewsblock']\" ).css('display','none');

                $( \"a[href='#starslistandsearch']\" ).css('display','none');

                $( \"a[href='#rssfeed']\" ).css('display','none');

                $( \"a[href='#importcomments']\" ).css('display','none');

                $( \"a[href='#gproductfeed']\" ).css('display','none');



                $( \"a[href='#customerreminderstat']\" ).css('display','none');

                 $( \"a[href='#csvproductreviews']\" ).css('display','none');



                $( \"a[href='#reviewsvoucheraddreviewtab']\" ).css('display','none');

                $( \"a[href='#reviewsvouchersharereviewtab']\" ).css('display','none');

                $( \"a[href='#reviewsemailstab']\" ).css('display','none');

                $( \"a[href='#responseadminemails']\" ).css('display','none');

                $( \"a[href='#customerreminder']\" ).css('display','none');

                $( \"a[href='#cronhelp']\" ).css('display','none');



                $( \"input[name='ratings_on']\").parent().parent().parent().hide(200);

                $( \"input[name='is_avatar".$this->_prefix_review."']\").parent().parent().parent().hide(200);

                $( \"input[name='is_files".$this->_prefix_review."']\").parent().parent().parent().hide(200);

                $( \"input[name='ruploadfiles']\").parent().parent().hide(200);

                $( \"input[name='rminc']\").parent().parent().hide(200);

                $( \"input[name='title_on']\").parent().parent().parent().hide(200);

                $( \"input[name='text_on']\").parent().parent().parent().hide(200);

                $( \"input[name='ip_on']\").parent().parent().parent().hide(200);

                $( \"input[name='is_captcha']\").parent().parent().parent().hide(200);

    	    }



    	});



    	</script>";





        return $_html . $helper->generateForm(array($fields_form,$fields_form1));

    }



    public function getConfigFieldsValuesGlobalSettings(){



        $data_config = array(

            'rvis_on' => Configuration::get($this->name.'rvis_on'),

            'is_avatar'.$this->_prefix_review => Configuration::get($this->name.'is_avatar'.$this->_prefix_review),

            'is_files'.$this->_prefix_review => Configuration::get($this->name.'is_files'.$this->_prefix_review),

            'title_on' => Configuration::get($this->name.'title_on'),

            'text_on' => Configuration::get($this->name.'text_on'),

            'ip_on' => Configuration::get($this->name.'ip_on'),

            'is_captcha' => Configuration::get($this->name.'is_captcha'),

            'ratings_on' => Configuration::get($this->name.'ratings_on'),



            'ruploadfiles'=> (int)Configuration::get($this->name.'ruploadfiles'),

            'rminc'=> (int)Configuration::get($this->name.'rminc'),



        );



        return $data_config;

    }







    private function _blockallinfo(){

        $_block_allinfo = array(

            'type' => 'block_allinfo',

            'label' => $this->l('Display block on the following pages'),



            'name' => 'block_allinfo',

            'values'=> array(

                'allinfo_home' => array('name'=>$this->l('for home page'),

                    'status' => Configuration::get($this->name.'allinfo_home'),

                    'position'=>Configuration::get($this->name.'allinfo_home_pos'),

                    'width' => array('width' => (int)Configuration::get($this->name.'allinfo_home_w'), 'name'=>'allinfo_home_w')),

                'allinfo_cat' => array('name'=>$this->l('for each category page'),

                    'status' => Configuration::get($this->name.'allinfo_cat'),

                    'position'=>Configuration::get($this->name.'allinfo_cat_pos'),

                    'width' => array('width' => (int)Configuration::get($this->name.'allinfo_cat_w'), 'name'=>'allinfo_cat_w')),

                'allinfo_man' => array('name'=>$this->l('for each manufacturer/brand page'),

                    'status' => Configuration::get($this->name.'allinfo_man'),

                    'position'=>Configuration::get($this->name.'allinfo_man_pos'),

                    'width' => array('width' => (int)Configuration::get($this->name.'allinfo_man_w'), 'name'=>'allinfo_man_w')),

            ),

            'available_pos' => array(

                'top'=>$this->l('Top'),

                'bottom'=>$this->l('Bottom'),

                'leftcol'=>$this->l('Left Column'),

                'rightcol'=>$this->l('Right Column'),



            ),

            'available_pos_home' => array(

                'top'=>$this->l('Top'),

                'home'=>$this->l('Home'),

                'bottom'=>$this->l('Bottom'),

                'leftcol'=>$this->l('Left Column'),

                'rightcol'=>$this->l('Right Column'),



            ),

        );

        return $_block_allinfo;

    }



    private function _googlesnippets16(){

        $fields_form = array(

            'form'=> array(

                'legend' => array(

                    'title' => $this->l('Google rich snippets settings'),

                    'icon' => 'fa fa-snippets fa-lg'

                ),

                'input' => array(







                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Google Rich Snippets'),

                        'name' => 'svis_on',

                        //'desc' => $this->l('Enable or Disable Google Rich Snippets'),

                        'hint' => $this->l('Enable or Disable Google Rich Snippets'),

                        'desc' => $this->l('Enable or Disable Google Rich Snippets').

                            '<br/><br/><b>'.$this->l('Snippets based on the').'</b>:<br/><br/>'.

                            '<b>'.$this->l('Reviews').'</b>:'.

                            '&nbsp;'.$this->l('More info').':&nbsp;<a href="https://developers.google.com/structured-data/rich-snippets/reviews#reviews" target="_blank">https://developers.google.com/structured-data/rich-snippets/reviews#reviews</a>'.

                            '</br/>'.

                            '<br/><b>'.$this->l('AND').'</b><br/>'.

                            '<br/><b>'.$this->l('Aggregate Ratings').'</b>:&nbsp;

                            '.$this->l('More info').':&nbsp;<a href="https://developers.google.com/structured-data/rich-snippets/reviews#aggregate_ratings" target="_blank">https://developers.google.com/structured-data/rich-snippets/reviews#aggregate_ratings</a>',







                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),







                ),





                ),





        );





        $fields_form1 = array(

            'form'=> array(

                'legend' => array(

                    'title' => $this->l('Google Breadcrumbs settings'),

                    'icon' => 'fa fa-snippets fa-lg'

                ),

                'input' => array(







                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Google Breadcrumbs'),

                        'name' => 'breadvis_on',

                        'hint' => $this->l('Enable or Disable Google Breadcrumbs'),

                        'desc' => $this->l('Enable or Disable Google Breadcrumbs').

                            '<br/><br/>'.

                            '<b>'.$this->l('Breadcrumbs').'</b>:'.

                            '&nbsp;'.$this->l('More info').':&nbsp;<a href="https://developers.google.com/structured-data/breadcrumbs" target="_blank">https://developers.google.com/structured-data/breadcrumbs</a>',







                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),







                ),





            ),





        );









        $fields_form2 = array(

            'form' => array(

                'legend' => array(

                    'title' => $this->l('Block with summary info Settings'),

                    'icon' => 'fa fa-reviews fa-lg'

                ),

                'input' => array(



                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Block with summary info about product ratings and reviews'),

                        'name' => 'allinfo_on',

                        'desc' => $this->l('Enable or Disable Block with summary info about product ratings and reviews'),



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    $this->_blockallinfo(),









                ),





            ),

        );



        $fields_form3 = array(

            'form'=> array(

                'legend' => array(

                    'title' => $this->l('Rich Pins Settings'),

                    'icon' => 'fa fa-richpins fa-lg'

                ),

                'input' => array(







                    array(

                        'type' => 'switch',

                        'label' => $this->l('Enable or Disable Rich Pins'),

                        'name' => 'pinvis_on',

                        'desc' => $this->l('Enable or Disable Rich Pins'),

                        'hint' => $this->l('Enable or Disable Rich Pins'),



                        'values' => array(

                            array(

                                'id' => 'active_on',

                                'value' => 1,

                                'label' => $this->l('Yes')

                            ),

                            array(

                                'id' => 'active_off',

                                'value' => 0,

                                'label' => $this->l('No')

                            )

                        ),

                    ),



                    array(

                        'type' => 'checkbox_custom',

                        'label' => $this->l('Position Pinterest Button'),

                        'name' => 'pos_pin_button',

                        'hint' => $this->l('Position Pinterest Button'),

                        'values' => array(

                            'query' => array(

                                array(

                                    'id' => 'leftColumn',

                                    'name' => $this->l('Left column, only product page'),

                                    'val' => 'leftColumn'

                                ),

                                array(

                                    'id' => 'rightColumn',

                                    'name' => $this->l('Right column, only product page'),

                                    'val' => 'rightColumn'

                                ),

                                array(

                                    'id' => 'extraLeft',

                                    'name' => $this->l('displayRightColumnProduct (Extra Left)'),

                                    'val' => 'extraLeft'

                                ),

                                array(

                                    'id' => 'extraRight',

                                    'name' => $this->l('displayLeftColumnProduct (Extra right)'),

                                    'val' => 'extraRight'

                                ),

                                array(

                                    'id' => 'productFooter',

                                    'name' => $this->l('displayFooterProduct (Product footer)'),

                                    'val' => 'productFooter'

                                ),

                                array(

                                    'id' => 'productActions',

                                    'name' => $this->l('displayProductButtons (Product actions)'),

                                    'val' => 'productActions'

                                ),





                            ),

                            'id' => 'id',

                            'name' => 'name'

                        ),



                    ),



                    array(

                        'type' => 'block_radio_buttons_custom',

                        'label' => $this->l('Pinterest Button style'),



                        'name' => 'block_radio_buttons_custom',

                        'values'=> array(

                            'style' => Configuration::get($this->name.'pinterestbuttons')

                        ),



                    ),





                ),







            ),





        );



        $fields_form4 = array(

            'form' => array(





                'submit' => array(

                    'title' => $this->l('Update Settings'),

                )

            ),

        );





        $helper = new HelperForm();







        $helper->table = $this->table;

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper->default_form_language = $lang->id;

        $helper->module = $this;

        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;

        $helper->submit_action = 'snippetssettings';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(

            'uri' => $this->getPathUri(),

            'fields_value' => $this->getConfigFieldsValuesGooglesnippetsSettings(),

            'languages' => $this->context->controller->getLanguages(),

            'id_language' => $this->context->language->id

        );



        $_html = '';



        $_html .= "<script type=\"text/javascript\">

    	$('document').ready( function() {



    		$( \"label[for='pinvis_on_on']\" ).click(function() {

                $( \".block_radio_buttons_custom\" ).parent().show(200);

                $( \".pos_pin_button\" ).parent().show(200);

                $( \"#block-pin-help\" ).show(200);



			});



            $( \"label[for='pinvis_on_off']\" ).click(function() {

    	        $( \".block_radio_buttons_custom\" ).parent().hide(200);

    	        $( \".pos_pin_button\" ).parent().hide(200);

    	        $( \"#block-pin-help\" ).hide(200);

    	    });



    	    if(".(int)Configuration::get($this->name.'pinvis_on')." == 0){

    	        $( \".block_radio_buttons_custom\" ).parent().hide(200);

    	        $( \".pos_pin_button\" ).parent().hide(200);

    	        $( \"#block-pin-help\" ).hide(200);

    	    }







    		$( \"label[for='allinfo_on_on']\" ).click(function() {

                $( \".block_allinfo\" ).parent().show(200);

			});



            $( \"label[for='allinfo_on_off']\" ).click(function() {

    	        $( \".block_allinfo\" ).parent().hide(200);

    	    });



    	    if(".(int)Configuration::get($this->name.'allinfo_on')." == 0){

    	        $( \".block_allinfo\" ).parent().hide(200);

    	    }







    	    $( \"label[for='svis_on_on']\" ).click(function() {

                $( \"#fieldset_1_1\" ).show(200);

                $( \"#fieldset_2_2\" ).show(200);



			});



            $( \"label[for='svis_on_off']\" ).click(function() {

    	        $( \"#fieldset_1_1\" ).hide(200);

    	        $( \"#fieldset_2_2\" ).hide(200);

    	    });



    	    if(".(int)Configuration::get($this->name.'svis_on')." == 0){

    	        $( \"#fieldset_1_1\" ).hide(200);

    	        $( \"#fieldset_2_2\" ).hide(200);

    	    }



    	});



    	</script>

    	";





        return $_html . $helper->generateForm(array($fields_form,$fields_form1,$fields_form2,$fields_form3,$fields_form4)).$this->_helpRichPins();

    }



    public function getConfigFieldsValuesGooglesnippetsSettings(){



        $data_config = array(

            'svis_on' => Configuration::get($this->name.'svis_on'),

            'allinfo_on' => Configuration::get($this->name.'allinfo_on'),

            'breadvis_on' => Configuration::get($this->name.'breadvis_on'),



            'pinvis_on' => Configuration::get($this->name.'pinvis_on'),



            'leftColumn' => Configuration::get($this->name.'_leftColumn'),

            'extraLeft' => Configuration::get($this->name.'_extraLeft'),

            'productFooter' => Configuration::get($this->name.'_productFooter'),

            'rightColumn' => Configuration::get($this->name.'_rightColumn'),

            'extraRight' => Configuration::get($this->name.'_extraRight'),

            'productActions' => Configuration::get($this->name.'_productActions'),



        );







        return $data_config;

    }

    

    

	public function _displayForm13_14_15($data_in=null){

   		$_html = '';

    	

    	$_html .= '

		<fieldset '.(($this->_is15 == 1)?"class=\"ps15-width\"":"").'>

					<legend><img src="../modules/'.$this->name.'/views/img/logo-16x16.gif"  />

					'.$this->displayName.'</legend>

					

		<ul class="leftMenu">

			<li><a href="javascript:void(0)" onclick="tabs_custom(1)" id="tab-menu-1" class="selected"><img src="../modules/'.$this->name.'/views/img/logo-16x16.gif" />'.$this->l('Welcome').'</a></li>

			<li><a href="javascript:void(0)" onclick="tabs_custom(2)" id="tab-menu-2"><img src="../modules/'.$this->name.'/views/img/btn/ico-google.gif" /><img src="../modules/'.$this->name.'/views/img/btn/ico-pinterest.png" />'.$this->l('Google Rich Snippets & Rich Pins').'</a></li>';

			$_html .= '<li><a href="javascript:void(0)" onclick="tabs_custom(3)" id="tab-menu-3"><img src="../modules/'.$this->name.'/views/img/btn/ico-star.png" />'.$this->l('Reviews').'</a></li>';



			$_html .= '<li><a href="javascript:void(0)" onclick="tabs_custom(4)" id="tab-menu-4"><img src="../modules/'.$this->name.'/views/img/btn/ico-star.png" />'.$this->l('Moderate Reviews').'</a></li>';



        $_html .= '<li><a href="javascript:void(0)" onclick="tabs_custom(5)" id="tab-menu-5"><i class="fa fa-star-storeviews fa-lg"></i>&nbsp;'.$this->l('Store reviews').'</a></li>';

        $_html .= '<li><a href="javascript:void(0)" onclick="tabs_custom(7)" id="tab-menu-7"><i class="fa fa-users fa-lg"></i>&nbsp;'.$this->l('User profile').'</a></li>';





        #### posts api ###

			$_html .= '<li><a href="javascript:void(0)" onclick="tabs_custom(8)" id="tab-menu-8"><img src="../modules/'.$this->name.'/views/img/AdminTools.gif" />'.$this->l('Social networks Integration').'</a></li>';

			#### posts api ###

			

			$_html .= '<li><a href="javascript:void(0)" onclick="tabs_custom(6)" id="tab-menu-6"><img src="../modules/'.$this->name.'/views/img/btn/ico-help.gif" />'.$this->l('Help / Documentation').'</a></li>';



			$_html .= '<li><a href="http://addons.prestashop.com/en/2_community-developer?contributor=189784" target="_blank"><img src="../modules/'.$this->name.'/views/img/mitrocops-logo.png"  />'.$this->l('Other Mitrocops Modules').'</a></li>';



		$_html .= '</ul>

		';

    	$_html .= '<div style="clear:both"></div>';

		$_html .= '<div class="gsnipreview-content">

						<div class="menu-content" id="tabs-1">'.$this->_welcome().'</div>';

		$_html .= '<div class="menu-content" id="tabs-2">'.$this->_snippetsSettings().'</div>';

		$_html .= '<div class="menu-content" id="tabs-3">'.$this->_reviewsSettings().'</div>';

        $_html .= '<div class="menu-content" id="tabs-5">'.$this->_shopreviewsSettings().'</div>';

        $_html .= '<div class="menu-content" id="tabs-7">'.$this->_settingsuser().'</div>';



        $errors = isset($data_in['errors'])?$data_in['errors']:array();

		$_html .= '<div class="menu-content" id="tabs-4">'.$this->_moderateReviews(array('errors'=>$errors)).'</div>';



		

		

		#### posts api ###

		include_once(dirname(__FILE__).'/classes/postshelp.class.php');

		$postshelp = new postshelp();



		$_html .= '<div class="menu-content" id="tabs-8">'.$postshelp->postsSettings(array('translate'=>$this->_translate)).'</div>';

		#### posts api ###

		

		$_html .= '<div class="menu-content" id="tabs-6">'.$this->_help_documentation().'</div>';

		$_html .= '<div style="clear:both"></div>';

		$_html .= '</div>';

		

		

		$_html .= '</fieldset>';

    	

    	return $_html;

    }







    private function _settingsuser()

    {

        $_html = '';





        $_html .= '<style type="text/css">

    	table.userprofileg-table td{padding:3px}

    	</style>';



        $_html .= '<form method="post" action="' . Tools::safeOutput($_SERVER['REQUEST_URI']) . '">';

        $_html .= '<h3 class="title-block-content"><i class="fa fa-users fa-lg"></i>&nbsp;'.$this->l('User profile').'</h3>';



        $_html .= '<table style="width:100%" class="userprofileg-table">';



        $_html .= '<tr>';

        $_html .= '<td style="text-align:right;width:35%;padding:0 20px 0 0" >';



        $_html .= '<b>'.$this->l('Enable or Disable User profile').':</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

					<input type="radio" value="1" id="is_uprof" name="is_uprof" onclick="enableOrDisableuserprofileg(1)"

							'.(Configuration::get($this->name.'is_uprof') ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>

					<input type="radio" value="0" id="is_uprof" name="is_uprof" onclick="enableOrDisableuserprofileg(0)"

						   '.(!Configuration::get($this->name.'is_uprof') ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.$this->l('Enable or Disable User profile functional on your site').'</p>



				';





        $_html .= '<script type="text/javascript">

			    	function enableOrDisableuserprofileg(id)

						{

						if(id==0){

							$("#block-userprofileg-settings").hide(200);

						} else {

							$("#block-userprofileg-settings").show(200);

						}



						}

					</script>';

        $_html .= '</td>';

        $_html .= '</tr>';



        $_html .= '</table>';



        $_html .= '<div id="block-userprofileg-settings" '.(Configuration::get($this->name.'is_uprof')==1?'style="display:block"':'style="display:none"').'>';



        $_html .= '<table style="width:100%" class="userprofileg-table">';



        $_html .= '<tr>';

        $_html .= '<td style="text-align:right;width:35%;padding:0 20px 0 0">';



        $_html .= '<b>'.$this->l('The number of shoppers in the "Block Users":').'</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

					<input type="text" name="'.$this->_prefix_review.'shoppers_blc"

			               value="'.Configuration::get($this->name.$this->_prefix_review.'shoppers_blc').'"

			               >

				';

        $_html .= '</td>';

        $_html .= '</tr>';



        $_html .= '<tr>';





        ####

        $_html .= '<tr>';

        $_html .= '<td style="text-align:right;width:35%;padding:0 20px 0 0">';



        $_html .= '<b>'.$this->l('Position "Block Users":').'</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';



        //$_html .= '<div class="margin-form choose_hooks">';

        $_html .= '<table style="width:66%;">

	    				<tr>

	    					<td style="width: 33%">'.$this->l('Left Column').'</td>

	    					<td style="width: 33%">'.$this->l('Right Column').'</td>



	    				</tr>

	    				<tr>

	    					<td>

	    						<input type="checkbox" name="'.$this->_prefix_review.'adv_left" '.((Configuration::get($this->name.$this->_prefix_review.'adv_left') ==1)?'checked':'').'  value="1"/>

	    					</td>

	    					<td>

	    						<input type="checkbox" name="'.$this->_prefix_review.'adv_right" '.((Configuration::get($this->name.$this->_prefix_review.'adv_right') ==1)?'checked':'') .' value="1"/>

	    					</td>



	    				</tr>

	    				<tr>

	    					<td>'.$this->l('Footer').'</td>

	    					<td>'.$this->l('Home').'</td>



	    				</tr>

	    				<tr>

	    					<td>

	    						<input type="checkbox" name="'.$this->_prefix_review.'adv_footer" '.((Configuration::get($this->name.$this->_prefix_review.'adv_footer') ==1)?'checked':'').' value="1"/>

	    					</td>

	    					<td>

	    						<input type="checkbox" name="'.$this->_prefix_review.'adv_home" '.((Configuration::get($this->name.$this->_prefix_review.'adv_home') ==1)?'checked':'').' value="1"/>

	    					</td>



	    				</tr>



	    			</table>';

        //$_html .= '</div>';

        $_html .= '</td>';

        $_html .= '</tr>';









        $_html .= '<tr>';

        $_html .= '<td style="text-align:right;width:35%;padding:0 20px 0 0">';



        $_html .= '<b>'.$this->l('Users per page in the list view:').'</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

					<input type="text" name="'.$this->_prefix_review.'page_shoppers"

			               value="'.Configuration::get($this->name.$this->_prefix_review.'page_shoppers').'"

			               >

				';

        $_html .= '</td>';

        $_html .= '</tr>';







        $_html .= '</table>';



        $_html .= '</div>';



        $_html .= '<p class="center" style="text-align:center;background: none; padding: 10px; margin-top: 10px;">

					<input type="submit" name="userprofilegsettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';





        $_html .= '<br/><br/>';



        //$_html .= $this->_hintuser();



        return $_html;

    }



    private function _hintuser(){

        if(version_compare(_PS_VERSION_, '1.5', '<')) {

            $_html = '';



            $_html .= '<p style="display: block; font-size: 11px; width: 95%; margin-bottom:20px;position:relative" class="hint clear">

    	<b style="color:#585A69">' . $this->l('If url rewriting doesn\'t works, check that this above lines exist in your current .htaccess file, if no, add it manually on top of your .htaccess file') . ':</b>

    	<br/><br/>

    	<code>';



            if ($this->_is15) {

                $physical_uri = array();

                foreach (ShopUrl::getShopUrls() as $shop_url) {

                    if (in_array($shop_url->physical_uri, $physical_uri)) continue;



                    $_html .= 'RewriteRule ^(.*)users$ ' . (($this->_is15) ? $shop_url->physical_uri : '/') . 'modules/' . $this->name . '/users.php [QSA,L] <br/>

                       RewriteRule ^(.*)user/([0-9]+)$ ' . (($this->_is15) ? $shop_url->physical_uri : '/') . 'modules/' . $this->name . '/user.php?uid=$2 [QSA,L] <br/>

                       RewriteRule ^(.*)useraccount$ ' . (($this->_is15) ? $shop_url->physical_uri : '/') . 'modules/' . $this->name . '/useraccount.php [QSA,L] <br/>';



                    $physical_uri[] = $shop_url->physical_uri;

                }

            } else {

                $_html .= 'RewriteRule ^(.*)users$ /modules/' . $this->name . '/users.php [QSA,L] <br/>

                       RewriteRule ^(.*)user/([0-9]+)$ /modules/' . $this->name . '/user.php?uid=$2 [QSA,L] <br/>

                       RewriteRule ^(.*)useraccount$ /modules/' . $this->name . '/useraccount.php [QSA,L] <br/>';

            }



            $_html .= '

	    </code>



			<br/><br/>

		</p>';



            return $_html;

        }

    }



    private  function _shopreviewsSettings(){

        $_html = '';



        $_html .= '<ul class="leftMenuIN">

			<li><a href="javascript:void(0)" onclick="tabs_custom_in_three(73)" id="tab-menuin_three-73"><i class="fa fa-cogs fa-lg"></i>&nbsp;' . $this->l('Main settings') . '</a></li>

			<li><a href="javascript:void(0)" onclick="tabs_custom_in_three(74)" id="tab-menuin_three-74"><i class="fa fa-bell-o fa-lg"></i>&nbsp;' . $this->l('Customer Reminder settings') . '</a></li>

			<li><a href="javascript:void(0)" onclick="tabs_custom_in_three(77)" id="tab-menuin_three-77"><i class="fa fa-bar-chart fa-lg"></i>&nbsp;' . $this->l('Customer Reminder Statistics') . '</a></li>

			<li><a href="javascript:void(0)" onclick="tabs_custom_in_three(75)" id="tab-menuin_three-75"><i class="fa fa-envelope-o fa-lg"></i>&nbsp;' . $this->l('Emails subjects settings') . '</a></li>

            <li><a href="javascript:void(0)" onclick="tabs_custom_in_three(78)" id="tab-menuin_three-78"><i class="fa fa-table fa-lg"></i>&nbsp;' . $this->l('CSV import/export Settings') . '</a></li>

            <li><a href="javascript:void(0)" onclick="tabs_custom_in_three(80)" id="tab-menuin_three-80"><i class="fa fa-tasks fa-lg"></i>&nbsp;' . $this->l('CRON HELP STORE REVIEWS') . '</a></li>



            <li><a href="javascript:void(0)" onclick="tabs_custom_in_three(79)" id="tab-menuin_three-79"><i class="fa fa-star-storeviews fa-lg"></i>&nbsp;' . $this->l('Moderate Store Reviews') . '</a></li>





		</ul>

		';



        $_html .= '<div class="items-content">

						';

        $_html .= '<div class="menu-content" id="tabsin_three-73" style="display:block">' . $this->_drawSettingsShopReviewsOLD() . '</div>';



        $_html .= '<div class="menu-content" id="tabsin_three-74">' . $this->_customerreminderStoreOLD() . '</div>';

        $_html .= '<div class="menu-content" id="tabsin_three-77">'.$this->_shopcustomerreminderstat().'</div>';



        $_html .= '<div class="menu-content" id="tabsin_three-75">'.$this->_emailsubjectsOLD().'</div>';



        $_html .= '<div class="menu-content" id="tabsin_three-78">'.$this->_csvImportExport().'</div>';



        $_html .= '<div class="menu-content" id="tabsin_three-80">' . $this->_cronhelp(array('url'=>'cron_shop_reviews')) . '</div>';



        $_html .= '<div class="menu-content" id="tabsin_three-79">' . $this->_drawTestImonials() . '</div>';









        $_html .= '<div style="clear:both"></div>';

        $_html .= '</div>';





        $_html .= '<div style="clear:both"></div>';



        return $_html;

    }







    public function _drawTestImonials($data = null){

        $cookie = $this->context->cookie;



        //$currentIndex = $this->context->currentindex;



        if(version_compare(_PS_VERSION_, '1.5', '>')) {

            $currentIndex = isset(AdminController::$currentIndex) ? AdminController::$currentIndex : 'index.php?controller=AdminStorereviewsold';

        } else {

            $currentIndex = 'index.php?tab=AdminModules';

        }



        $currentIndex = isset($data['currentindex'])?$data['currentindex']:$currentIndex;



        $controller = isset($data['controller'])?$data['controller']:'AdminModules';



        $token = isset($data['token'])?$data['token']:Tools::getAdminToken($controller.(int)(Tab::getIdFromClassName($controller)).(int)($cookie->id_employee));







        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $base_dir = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__;

        } else {

            $base_dir = _PS_BASE_URL_SSL_.__PS_BASE_URI__;

        }



        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-star-storeviews fa-lg"></i>&nbsp;'.$this->l('Moderate Store Reviews').'</h3>';





        include_once(dirname(__FILE__).'/classes/storereviews.class.php');

        $obj_storereviews = new storereviews();



        include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

        $obj_gsnipreviewhelp = new gsnipreviewhelp();



        if(Tools::isSubmit("edit_item".$this->_prefix_shop_reviews)){

            $id = (int)Tools::getValue("id");

            $_data = $obj_storereviews->getItem(array('id'=>$id));



            $_html .= '

    					<form action="'.$_SERVER['REQUEST_URI'].'" method="post" enctype="multipart/form-data">';



            $name = $_data['reviews'][0]['name'];

            $email = $_data['reviews'][0]['email'];

            $web = $_data['reviews'][0]['web'];

            $message = $_data['reviews'][0]['message'];

            $date = $_data['reviews'][0]['date_add'];

            $active = $_data['reviews'][0]['active'];

            $id = $_data['reviews'][0]['id'];



            $company = $_data['reviews'][0]['company'];

            $address = $_data['reviews'][0]['address'];



            $rating = $_data['reviews'][0]['rating'];

            $country = $_data['reviews'][0]['country'];

            $city = $_data['reviews'][0]['city'];



            $avatar = $_data['reviews'][0]['avatar'];

            $is_exist_ava = isset($_data['reviews'][0]['is_exist']) ? $_data['reviews'][0]['is_exist'] :0 ;



            $response = $_data['reviews'][0]['response'];

            $is_show = $_data['reviews'][0]['is_show'];





            $lang = $_data['reviews'][0]['id_lang'];

            $data_lng = Language::getLanguage($lang);

            $lang_for_testimonial = $data_lng['iso_code'];







            $name_lang = $_data['reviews'][0]['name_lang'];



            if($this->_is15){

                $id_shop = $_data['reviews'][0]['id_shop'];



                $shops = Shop::getShops();

                $name_shop = '';

                foreach($shops as $_shop){

                    $id_shop_lists = $_shop['id_shop'];

                    if($id_shop == $id_shop_lists)

                        $name_shop = $_shop['name'];

                }

            }



            $id_customer = isset($_data['reviews'][0]['id_customer']) ? $_data['reviews'][0]['id_customer'] :0 ;

            /*$admin_url_to_customer = 'index.php?'.(version_compare(_PS_VERSION_, '1.5', '>')?'controller':'tab').'=AdminCustomers&id_customer='.$id_customer.

                '&'.(version_compare(_PS_VERSION_, '1.5', '>')?'updatecustomer':'viewcustomer').'&token='.Tools::getAdminToken('AdminCustomers'.(int)(Tab::getIdFromClassName('AdminCustomers')).(int)($cookie->id_employee)).'';*/

            $customer_name = isset($_data['reviews'][0]['customer_name']) ? $_data['reviews'][0]['customer_name'] :'' ;





            $data_seo_url = $obj_gsnipreviewhelp->getSEOURLs(array('id_lang'=>$lang));

            $user_url = $data_seo_url['user_url'].$id_customer;



            $_html .= '<label>'.$this->l('ID:').'</label>';

            $_html .= '<div class="margin-form margin-item-form-top-left"><span class="badge">'.$id.'</span></div>';



            if($this->_is15){

                $_html .= '<label>'.$this->l('Shop').'</label>

    					<div class="margin-form margin-item-form-top-left">

							<span class="badge">'.$name_shop.'</span>

						</div>';

            }

            $_html .= '<label>'.$this->l('Language').'</label>

    					<div class="margin-form margin-item-form-top-left">

							<span class="badge">'.$name_lang.'</span>

						</div>';



            $_html .= '<label>'.$this->l('Rating').':</label>

			<div class="margin-form">

			<input type="text" name="rating"  style="width:200px"

			value="'.$rating.'">

			</div>';







            $_html .= '<label>'.$this->l('Avatar').'</label>



    				<div class="margin-form">

    				<input type="hidden" name="id_customer" value="'.$id_customer.'" />

					<input type="file" name="avatar-review" id="avatar-review" ';

            if($this->_is16 == 0){

                $_html .= 'class="customFileInput"';

            }

            $_html .= '/>

					<p>'.$this->l('Allow formats').' *.jpg; *.jpeg; *.png; *.gif.<br/>'.$this->l('Max file size in php.ini').'&nbsp;<b style="color:green">'.ini_get('upload_max_filesize').'</b></p>';





            if($is_exist_ava){

                $_html .= '

                        <input type="radio" name="post_images" checked="" style="display: none">

                        <span class="avatar-form">

                        <img src="'.$avatar.'" />

                        </span>

                        <br/>



                        <a class="delete_product_image btn btn-default avatar-button15" href="javascript:void(0)"

                           onclick = "delete_avatar_storereviews('.$id.','.$id_customer.');"

                           style="margin-top: 10px">

                            '.$this->l('Delete avatar and use standart empty avatar').' <img src="'._PS_ADMIN_IMG_.'delete.gif" alt="" />

                        </a>';

            } else{

                $_html .= '<span class="avatar-form"><img src = "../modules/'.$this->name.'/views/img/avatar_m.gif" /></span>';

            }



            $_html .= '</div>';



            if($this->_is16 == 1){

                $_html .= '<br/>';

            }



            if($id_customer) {

                $_html .= '<label>' . $this->l('Customer') . '</label>

    					<div class="margin-form margin-item-form-top-left">';



                if($id_customer) {

                    $_html .= '<a href="' . $user_url . '" target="_blank"

                                title="' . $customer_name . '"

                                ><span class="badge text-decoration-underline">' . $customer_name . '</span></a>';

                } else {

                    $_html .= '<span class="badge">' . $customer_name . '</span>';

                }

						$_html .= '</div>';

            }



            $_html .= '<label>'.$this->l('Name:').'</label>

    					<div class="margin-form">

							<input type="text" name="name"  style="width:200px"

			                	   value="'.htmlentities($name).'">

						</div>';

            $_html .= '<label>'.$this->l('Email:').'</label>

    					<div class="margin-form">

							<input type="text" name="email"  style="width:200px"

			                	   value="'.$email.'">

						</div>';



            if(Configuration::get($this->name.'is_web')){

                $_html .= '<label>'.$this->l('Web:').'</label>

	    					<div class="margin-form">

								<input type="text" name="web"  style="width:200px"

				                	   value="'.$web.'">

							</div>';

            }

            if(Configuration::get($this->name.'is_company')){

                $_html .= '<label>'.$this->l('Company').':</label>

	    					<div class="margin-form">

								<input type="text" name="company"  style="width:200px"

				                	   value="'.htmlentities($company).'">

							</div>';

            }

            if(Configuration::get($this->name.'is_addr')){

                $_html .= '<label>'.$this->l('Address').':</label>

	    					<div class="margin-form">

								<input type="text" name="address"  style="width:200px"

				                	   value="'.htmlentities($address).'">

							</div>';

            }

            if(Configuration::get($this->name.'is_country')){

                $_html .= '<label>'.$this->l('Country').':</label>

	    		<div class="margin-form">

	    			<input type="text" name="country"  style="width:200px" value="'.htmlentities($country).'" />

	    		</div>';

            }



            if(Configuration::get($this->name.'is_city')){

                $_html .= '<label>'.$this->l('City').':</label>

	    		<div class="margin-form">

	    		<input type="text" name="city"  style="width:200px" value="'.htmlentities($city).'" />

	    		</div>';

            }



            $_html .= '<label>'.$this->l('Message:').'</label>

    					<div class="margin-form">

							<textarea name="message" cols="80" rows="10"

			                	   >'.$message.'</textarea>

						</div>';





            $_html .= '<label>'.$this->l('Admin Response:').'</label>

    					<div class="margin-form">

							<textarea name="response" cols="80" rows="10"

			                	   >'.$response.'</textarea>

						</div>';



            $_html .= '

				<label>'.$this->l('Send "Admin Response" notification to the customer').'</label>

				<div class = "margin-form" >';



            $_html .= '<input type = "checkbox" name = "is_noti" id = "is_noti" value ="1" />';



            $_html .= '</div>';



            $_html .= '<div class="clear"></div><br/>

				<label>'.$this->l('Display "Admin response" on the site').'</label>

				<div class = "margin-form" >';



            $_html .= '<input type = "checkbox" name = "is_show" id = "is_show" value ="1" '.(($is_show ==1)?'checked':'').'/>';



            $_html .= '</div><br/>';

            #### publication date ####







            $date_tmp = '';

            if(isset($date)){

                $date_tmp = strtotime($date);

                $date_tmp = date('Y-m-d H:i:s',$date_tmp);

            } else {

                $date_tmp = date('Y-m-d H:i:s');

            }



            $_html .= '<div class="clear"></div>';

            $_html .= $this->displayDateField('date_add', $date_tmp, $this->l('Date Add:'), $this->l('Format : YYYY-MM-DD HH:MM:SS'));



            if(version_compare(_PS_VERSION_, '1.5', '>')){

                $_html .= '<script type="text/javascript">

    	$(\'document\').ready( function() {





	    	if ($(".datepicker").length > 0){



	    	var dateObj = new Date();

			var hours = dateObj.getHours();

			var mins = dateObj.getMinutes();

			var secs = dateObj.getSeconds();

			if (hours < 10) { hours = "0" + hours; }

			if (mins < 10) { mins = "0" + mins; }

			if (secs < 10) { secs = "0" + secs; }

			var time = " "+hours+":"+mins+":"+secs;



	           $(".datepicker").datepicker({ prevText: \'\', nextText: \'\', dateFormat: \'yy-mm-dd\'+time});

	       	}

       	});

    	</script>';

            }



            #### publication date ####





            $_html .= '

				<label>'.$this->l('Publish').'</label>

				<div class = "margin-form" >';



            $_html .= '<input type = "checkbox" name = "publish" id = "publish" value ="1" '.(($active ==1)?'checked':'').'/>';



            $_html .= '</div>';



            $_html .= '<label>&nbsp;</label>

						<div class = "margin-form"  style="margin-top:20px">

						<input type="submit" name="cancel_item'.$this->_prefix_shop_reviews.'" value="'.$this->l('Cancel').'"

                		   class="button"  />&nbsp;&nbsp;&nbsp;

						<input type="submit" name="submit_item'.$this->_prefix_shop_reviews.'" value="'.$this->l('Save').'"

                		   class="button"  />

                		  </div>';



            $_html .= '</form>';





        } else {





            $_html .= '<table class = "table" width = 100%>

			<tr>

				<th>'.$this->l('No.').'</th>';



            $_html .= '<th width = 50>'.$this->l('Lang').'</th>';



            if($this->_is15){

                $_html .= '<th width = 100>'.$this->l('Shop').'</th>';

            }



            $_html .= '<th width = 100>'.$this->l('Rating').'</th>';

            if(Configuration::get($this->name.'is_avatar') == 1) {

                $_html .= '<th>' . $this->l('Avatar') . '</th>';

            }

            $_html .= '<th>'.$this->l('Name').'</th>

				<th width = 100>'.$this->l('Email').'</th>';



            if(Configuration::get($this->name.'is_web')){

                $_html .= '<th width = 100>'.$this->l('Web').'</th>';

            }





            $_html .= '<th width = "300">'.$this->l('Message').'</th>

				<th>'.$this->l('Date').'</th>

				<th>'.$this->l('Published').'</th>

				<th width = "44">'.$this->l('Action').'</th>

			</tr>';



            $start = (int)Tools::getValue("page");



            $_data = $obj_storereviews->getTestimonials(array('start'=>$start,'step'=>$this->_step,'admin' => 1));



            $paging = $obj_storereviews->PageNav($start,$_data['count_all_reviews'],$this->_step,

                array('admin' => 1,'currentIndex'=>$currentIndex,

                    'token' => $token));

            $i=0;



            if(sizeof($_data['reviews'])>0){









                foreach($_data['reviews'] as $_item){

                    $i++;

                    $id = $_item['id'];

                    $name = $_item['name'];

                    $email = $_item['email'];

                    $web = $_item['web'];

                    $message = $_item['message'];

                    $date = $_item['date_add'];

                    $active = $_item['active'];



                    $rating = $_item['rating'];



                    $company = $_item['company'];

                    $address = $_item['address'];



                    $avatar = $_item['avatar'];



                    $avatar_thumb = isset($_item['avatar_thumb'])?$_item['avatar_thumb']:'';



                    $id_customer = isset($_item['id_customer'])?$_item['id_customer']:0;



                    $lang = $_item['id_lang'];



                    $data_seo_url = $obj_gsnipreviewhelp->getSEOURLs(array('id_lang'=>$lang));

                    $user_url = $data_seo_url['user_url'];



                    $data_lng = Language::getLanguage($lang);

                    $lang_for_testimonial = $data_lng['iso_code'];



                    if($this->_is15){

                        $id_shop = $_data['reviews'][0]['id_shop'];



                        $shops = Shop::getShops();

                        $name_shop = '';

                        foreach($shops as $_shop){

                            $id_shop_lists = $_shop['id_shop'];

                            if($id_shop == $id_shop_lists)

                                $name_shop = $_shop['name'];

                        }

                    }



                    $_html .=

                        '<tr>

			<td style = "color:black;">'.$id.'</td>';



                    $_html .= '<td style="color:black">'.$lang_for_testimonial.'</td>';



                    if($this->_is15){

                        $_html .= '<td style="color:black">'.$name_shop.'</td>';

                    }





                    // rating //

                    $_html .= '<td style="color:black">';





                    switch(Configuration::get($this->name.'stylestars')){

                        case 'style1':

                            $activestar = 'star-active-yellow.png';

                            $noactivestar = 'star-noactive-yellow.png';

                            break;

                        case 'style2':

                            $activestar = 'star-active-green.png';

                            $noactivestar = 'star-noactive-green.png';

                            break;

                        case 'style3':

                            $activestar = 'star-active-blue.png';

                            $noactivestar = 'star-noactive-blue.png';

                            break;

                        default:

                            $activestar = 'star-active-yellow.png';

                            $noactivestar = 'star-noactive-yellow.png';

                            break;

                    }





                    if($rating != 0){

                        for($j=0;$j<5;$j++){

                            if($j < $rating){

                                $_html .= '<img src = "'.$base_dir.'modules/'.$this->name.'/views/img/'.$activestar.'" style="width:13px;" />';

                            } else {

                                $_html .= '<img src = "'.$base_dir.'modules/'.$this->name.'/views/img/'.$noactivestar.'"  style="width:13px;"/>';

                            }

                        }

                    } else {

                        for($j=0;$j<5;$j++){

                            $_html .= '<img src = "'.$base_dir.'modules/'.$this->name.'/views/img/'.$noactivestar.'" style="width:13px;" />';

                        }

                    }









                    $_html .= '</td>';

                    // rating //



                    if(Configuration::get($this->name.'is_avatar') == 1) {

                        $_html .= '<td>



                        <span class="avatar-list">';



                        if($id_customer){



                            /* for registered customers */

                            if(Tools::strlen($avatar_thumb)>0){

                                $_html .= '<img src="'.$base_dir.$this->path_img_cloud.'avatar/'.$avatar_thumb.'" />';

                            }else{

                                $_html .= '<img src = "../modules/' . $this->name . '/views/img/avatar_m.gif" />';

                             }

                             /* for registered customers */

                        } else{

                            /* for guests */

                            if(Tools::strlen($avatar)>0) {

                                $_html .= '<img src="'.$avatar.'" />';

                            }else{

                                $_html .= '<img src = "../modules/' . $this->name . '/views/img/avatar_m.gif" />';

                            }

                            /* for guests */

                        }





                        $_html .= '</span>

                        </td>';

                    }



                    if(Configuration::get($this->name.'is_uprof') && $id_customer){

                        $_html .= '<td style = "color:black;">

                                        <a href="'.$user_url.$id_customer.'"

                                            target="_blank" style="text-decoration:underline">' . $name . '</a>

                                    </td>';

                    } else {

                        $_html .= '<td style = "color:black;">' . $name . '</td>';

                    }





			$_html .= '<td style = "color:black;">'.$email.'</td>';



                    if(Configuration::get($this->name.'is_web')){

                        if(Tools::strlen($web)>0){

                            $_html .= '<td><a  style = "color:#996633;text-decoration:underline" href = "http://'.$web.'">http://'.$web.'</a></td>';

                        } else {

                            $_html .= '<td>&nbsp;</td>';

                        }

                    }



                    $_html .= '<td style = "color:black;">'.(Tools::strlen($message)>50?Tools::substr($message,0,50)."...":$message).'</td>

			<td style = "color:black;">'.$date.'</td>';



                    $_html .= '

			<td style = "color:black;">

			 <form action = "'.$_SERVER['REQUEST_URI'].'" method = "POST">';

                    if ($active == 1) {

                        $_html .= '<input type = "submit" name = "unpublished'.$this->_prefix_shop_reviews.'" value = "Unpublish" class = "button unpublished"/>';

                    }

                    else{

                        $_html .= '<input type = "submit" name = "published'.$this->_prefix_shop_reviews.'" value = "Publish" class = "button published"/>';

                    }

                    $_html .= '</td>

			<td>



				 <input type = "hidden" name = "id" value = "'.$id.'"/>

				 <a href="'.$currentIndex.'&configure='.$this->name.'&token='.$token.'&edit_item'.$this->_prefix_shop_reviews.'&id='.(int)($id).'" title="'.$this->l('Edit').'"><img src="'._PS_ADMIN_IMG_.'edit.gif" alt="" /></a>

				 <a href="'.$currentIndex.'&configure='.$this->name.'&token='.$token.'&delete_item'.$this->_prefix_shop_reviews.'&id='.(int)($id).'" title="'.$this->l('Delete').'"  onclick = "javascript:return confirm(\''.$this->l('Are you sure you want to remove this item?').'\');"><img src="'._PS_ADMIN_IMG_.'delete.gif" alt="" /></a>';

                    $_html .= '</form>

			 </td>

			';



                    $_html .= '</tr>';

                }



            } else {

                $_html .= '<tr><td colspan="12" style="text-align:center;font-weight:bold;padding:10px">

			'.$this->l('Store Reviews not found').'</td></tr>';



            }



            $_html .= '</table>

						';

            if($i!=0){

                $_html .= '<div style="margin:5px">';

                $_html .= $paging;

                $_html .= '</div>';

            }

        }





        return $_html;

    }



    public function displayDateField($name, $value, $title, $description ) {

        $opt_defaults = array('class' => '', 'required' => false);

        $opt = $opt_defaults;



        $content = '<label > ' . $title . ' </label>

                                    <div class="margin-form" >

                                       <input type="text" name="' . $name . '" value="' . $value . '" class="datepicker ' . $opt['class'] . '" />';





        if (!is_null($description) && !empty($description)) {

            $content .= '<p class="preference_description">' . $description . '</p>';

        }



        $content .= '</div>';



        return $content;

    }





    private function _emailsubjectsOLD()

    {

        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-envelope-o fa-lg"></i>&nbsp;' . $this->l('Emails subjects settings') . '</h3>';





        $_html .= '<form method="post" action="' . Tools::safeOutput($_SERVER['REQUEST_URI']) . '">';





        $data_subjects = array(

            array('name'=>"emrem",

                'title'=>$this->l('Email reminder subject'),

                'desc'=>$this->l('You can customize the subject of the e-mail here.').' '

                    . $this->l('If you wish to customize the e-mail message itself, you will need to manually edit the files in the')

                    .'  "mails" '

                    . $this->l(' folder inside the')

                    .' "'.$this->name.'" '

                    .$this->l('module folder, for each language, both the text and the HTML version each time. ')

                    .'<br/><br/><b>'.$this->l('FTP Location').': /modules/'.$this->name.'/mails/{YOUR_ISO_CODE_LANGUAGE}/customer-reminder-ti.html</b>'),

            array('name'=>"reminderok",

                'title'=>$this->l('Admin confirmation subject, when emails requests on the reviews was successfully sent'),

                'desc'=>$this->l('You can customize the subject of the e-mail here.').' '

                    . $this->l('If you wish to customize the e-mail message itself, you will need to manually edit the files in the')

                    .'  "mails" '

                    . $this->l(' folder inside the')

                    .' "'.$this->name.'" '

                    .$this->l('module folder, for each language, both the text and the HTML version each time. ')

                    .'<br/><br/><b>'.$this->l('FTP Location').': /modules/'.$this->name.'/mails/{YOUR_ISO_CODE_LANGUAGE}/customer-reminder-admin-ti.html</b>'),

            array('name'=>"thankyou",

                'title'=>$this->l('Thank you subject'),

                'desc'=>$this->l('You can customize the subject of the e-mail here.').' '

                    . $this->l('If you wish to customize the e-mail message itself, you will need to manually edit the files in the')

                    .'  "mails" '

                    . $this->l(' folder inside the')

                    .' "'.$this->name.'" '

                    .$this->l('module folder, for each language, both the text and the HTML version each time. ')

                    .'<br/><br/><b>'.$this->l('FTP Location').': /modules/'.$this->name.'/mails/{YOUR_ISO_CODE_LANGUAGE}/testimony-thank-you.html</b>'),

            array('name'=>"newtest",

                'title'=>$this->l('New Store Review subject'),

                'desc'=>$this->l('You can customize the subject of the e-mail here.').' '

                    . $this->l('If you wish to customize the e-mail message itself, you will need to manually edit the files in the')

                    .'  "mails" '

                    . $this->l(' folder inside the')

                    .' "'.$this->name.'" '

                    .$this->l('module folder, for each language, both the text and the HTML version each time. ')

                    .'<br/><br/><b>'.$this->l('FTP Location').': /modules/'.$this->name.'/mails/{YOUR_ISO_CODE_LANGUAGE}/testimony.html</b>'),

            array('name'=>"resptest",

                'title'=>$this->l('Response on the Store Review subject'),

                'desc'=>$this->l('You can customize the subject of the e-mail here.').' '

                    . $this->l('If you wish to customize the e-mail message itself, you will need to manually edit the files in the')

                    .'  "mails" '

                    . $this->l(' folder inside the')

                    .' "'.$this->name.'" '

                    .$this->l('module folder, for each language, both the text and the HTML version each time. ')

                    .'<br/><br/><b>'.$this->l('FTP Location').': /modules/'.$this->name.'/mails/{YOUR_ISO_CODE_LANGUAGE}/response-testim.html</b>'),



        );

        $div_array = array();

        foreach($data_subjects as $data_item_subject) {

            $name_item = $data_item_subject['name'];

            $div_array[] = $name_item . $this->_prefix_shop_reviews;



        }



        $divLangName  = implode("¤",$div_array);

        foreach($data_subjects as $data_item_subject) {





            $name_item = $data_item_subject['name'];







            $_html .= '<label>' . $data_item_subject['title'] . ':</label>';



            $_html .= '<div class="margin-form">';



            $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));

            $languages = Language::getLanguages(false);



            foreach ($languages as $language) {

                $id_lng = (int)$language['id_lang'];

                $rssname = Configuration::get($this->name . $name_item . $this->_prefix_shop_reviews . '_' . $id_lng);





                $_html .= '	<div id="' . $name_item . $this->_prefix_shop_reviews . '_' . $language['id_lang'] . '"

							 style="display: ' . ($language['id_lang'] == $defaultLanguage ? 'block' : 'none') . ';float: left;"

							 >



						<input type="text" style="width:400px"

								  id="' . $name_item . $this->_prefix_shop_reviews . '_' . $language['id_lang'] . '"

								  name="' . $name_item . $this->_prefix_shop_reviews . '_' . $language['id_lang'] . '"

								  value="' . htmlentities(Tools::stripslashes($rssname), ENT_COMPAT, 'UTF-8') . '"/>

						</div>';

            }

            $_html .= '';

            ob_start();

            $this->displayFlags($languages, $defaultLanguage, $divLangName, $name_item . $this->_prefix_shop_reviews);

            $displayflags = ob_get_clean();

            $_html .= $displayflags;

            $_html .= '<div style="clear:both"></div>';



            $_html .= '<p class="clear">' . $data_item_subject['desc'].'</p>';



            $_html .= '</div>';

        }













        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">

					<input type="submit" name="emailsubjectssettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';







        return $_html;



    }



    private function _customerreminderStoreOLD()

    {

        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-bell-o fa-lg"></i>&nbsp;' . $this->l('Customer Reminder') . '</h3>';





        $_html .= '<form method="post" action="' . Tools::safeOutput($_SERVER['REQUEST_URI']) . '">';





        $_html .= '<label>' . $this->l('Send a review reminder by email to customers') . ':</label>';



        $_html .= '<script type="text/javascript">

			    	function enableOrDisableReminder'.$this->_prefix_shop_reviews.'(id)

						{

						if(id==0){

							$("#block-reminder'.$this->_prefix_shop_reviews.'-settings").hide(200);

						} else {

							$("#block-reminder'.$this->_prefix_shop_reviews.'-settings").show(200);

						}



						}

					</script>';





        $_html .= '<div class="margin-form">';

        $_html .= '

					<input type="radio" value="1" id="text_list_on" name="reminder'.$this->_prefix_shop_reviews.'" onclick="enableOrDisableReminder'.$this->_prefix_shop_reviews.'(1)"

							' . (Configuration::get($this->name . 'reminder'.$this->_prefix_shop_reviews) ? 'checked="checked" ' : '') . '>

					<label for="dhtml_on" class="t">

						<img alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="reminder'.$this->_prefix_shop_reviews.'" onclick="enableOrDisableReminder'.$this->_prefix_shop_reviews.'(0)"

						   ' . (!Configuration::get($this->name . 'reminder'.$this->_prefix_shop_reviews) ? 'checked="checked" ' : '') . '>

					<label for="dhtml_off" class="t">

						<img alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">' .

            $this->l('If activated, when a customer purchases a product on your shop, an e-mail will be sent to him after X days (specify below after selecting "yes" here) to invite him to rate the product.').'

                                    <br/><br/>

                                    <b>'.$this->l('IMPORTANT NOTE').'</b>: '.$this->l('This requires to set a CRON task on your server. ').'

                                    <a style="text-decoration:underline;font-weight:bold;color:red" onclick="tabs_custom(110)" href="javascript:void(0)">'.$this->l('CRON HELP STORE REVIEWS').'</a>

                                    <br/><br/>

                                    <b>'.$this->l('Your CRON URL to call').'</b>:&nbsp;

                                    <a href="'.$this->getURLMultiShop().'modules/'.$this->name.'/cron_shop_reviews.php?token='.$this->getokencron().'"

                                    style="text-decoration:underline;font-weight:bold" target="_blank"

                                    >'.$this->getURLMultiShop().'modules/'.$this->name.'/cron_shop_reviews.php?token='.$this->getokencron().'</a>'

            .'</p>



				';

        $_html .= '</div>';



        $_html .= '<div id="block-reminder'.$this->_prefix_shop_reviews.'-settings" ' . (Configuration::get($this->name . 'reminder'.$this->_prefix_shop_reviews) == 1 ? 'style="display:block"' : 'style="display:none"') . '>';







        $_html .= '<label>' . $this->l('Delay between each email in seconds') . ':</label>';





        $_html .= '<div class="margin-form">';

        $_html .=  '

					<input type="text" name="crondelay'.$this->_prefix_shop_reviews.'" size="10"

			       	   value="'.Configuration::get($this->name.'crondelay'.$this->_prefix_shop_reviews).'">

				 <p class="clear">'.$this->l('The delay is intended in order to your server is not blocked the email function').'</p>';



        $_html .= '</div>';





        $_html .= '<label>' . $this->l('Number of emails for each cron call') . ':</label>';





        $_html .= '<div class="margin-form">';

        $_html .=  '

					<input type="text" name="cronnpost'.$this->_prefix_shop_reviews.'" size="10"

			       	   value="'.Configuration::get($this->name.'cronnpost'.$this->_prefix_shop_reviews).'">



			       	   <p class="clear">'.$this->l('This will reduce the load on your server. The more powerful your server - the more emails you can do for each CRON call! ').'</p>';



        $_html .= '</div>';





        $_html .= '<label>' . $this->l('Delay for sending reminder by email') . ':</label>';



        $_html .= '<div class="margin-form">';

        $_html .= '

					<input type="text" name="delay'.$this->_prefix_shop_reviews.'"

			               value="' . Configuration::get($this->name . 'delay'.$this->_prefix_shop_reviews) . '"

			               >&nbsp;(' . $this->l('days') . ')

				';

        $_html .= '<p class="clear">' . $this->l('We recommend you enter at least 7 days here to have enough time to process the order and for the customer to receive it.') . '</p>';

        $_html .= '</div>';



        $_html .= '</div>';





        $_html .= '<label>' . $this->l('Import your old orders') . ':</label>';



        $_html .= '<div class="margin-form">';





        $_html .= '<div class="input-group col-lg-3" style="float:left;margin-right:10px" id="importoldorders_first_storereviews">

                <span style="font-size:12px">' . $this->l('start date') . '</span>&nbsp;';

        $_html .= '<input type="text" name="start_date" class="item_datepicker_storereviews datepicker">';





        $_html .= '</div>

            <div class="input-group col-lg-3" style="float:left">

                <span style="font-size:12px">' . $this->l('end date') . '</span>&nbsp;';

        $_html .= '<input type="text" value="' . date('Y-m-d H:i:s') . '" name="end_date" disabled="disabled" data-hex="true" >';





        $_html .= '</div>

            <input type="button" value="' . $this->l('Import old orders') . '" onclick="importoldordersstore();"

                   class="button" style="float:left;margin-left:10px"/>

            <div style="clear:both"></div>';





        $_html .= '<script type="text/javascript">

            $(\'document\').ready( function() {



                var dateObj = new Date();

                var hours = dateObj.getHours();

                var mins = dateObj.getMinutes();

                var secs = dateObj.getSeconds();

                if (hours < 10) { hours = "0" + hours; }

                if (mins < 10) { mins = "0" + mins; }

                if (secs < 10) { secs = "0" + secs; }

                var time = " "+hours+":"+mins+":"+secs;



                if ($(".item_datepicker_storereviews").length > 0){

                    $(".item_datepicker_storereviews").datepicker({prevText: \'\',nextText: \'\',dateFormat: \'yy-mm-dd\'+time});

                    }



            });







            function importoldordersstore(){



                $(\'#importoldorders_first_storereviews\').css(\'opacity\',\'0.5\');

                var start_date =  $(\'.item_datepicker_storereviews\').val();





                $.post(\'../modules/'.$this->name.'/ajax.php\',

                        {   action:\'importoldorders\',

                            start_date: start_date

                        },

                        function (data) {

                            if (data.status == \'success\') {



                                $(\'#importoldorders_first_storereviews\').css(\'opacity\',\'1\');

                                var data = data.params.content;

                                //alert(data);



                                $(\'.alert-danger\').remove();

                                $(\'.alert-success\').remove();

                                $(\'#importoldorders_first_storereviews\').before(data);





                            } else {

                                $(\'#importoldorders_first_storereviews\').css(\'opacity\',\'1\');

                                alert(data.message);



                            }

                        }, \'json\');

            }

        </script>';

        $_html .= '<p class="clear" style="font-size: 12px;margin-top:10px; font-weight:bold">



                    ' . $this->l('Please select a date. All orders placed between that start date and end date (today) will be imported.') . '

                </p>';

        $_html .= '</div>';





        $_html .= '<label>' . $this->l('Send emails only for the orders with the current selected status') . ':</label>';



        $_html .= '<div class="margin-form">';



        include_once(dirname(__FILE__).'/classes/featureshelptestim.class.php');

        $obj_featureshelp = new featureshelptestim();

        $cookie = $this->context->cookie;

        $id_lang = (int)($cookie->id_lang);

        $value = $obj_featureshelp->getOrderStatuses(array('id_lang' => $id_lang));

        $orderstatuses = explode(",", Configuration::get($this->name . 'orderstatuses'.$this->_prefix_shop_reviews));





        $_html .= '<div class="panel">



                <table class="table">

                    <thead>

                    <tr>



                        <th>&nbsp;</th>

                        <th><b>' . $this->l('Order status') . '</b></th>

                    </tr>

                    </thead>

                    <tbody>';



        foreach($value as $cms_item){

            $_html .= '<tr class="alt_row">

                            <td>



                                    <div class="input-group">

                                        <input type="checkbox" name="orderstatuses[]" ';

            foreach ($orderstatuses as $id_status){

                if ($id_status == $cms_item['id_order_state'])

                    $_html .= 'checked="checked" ';

            }



            $_html .= 'value="'.$cms_item['id_order_state'].'" />

                                    </div>

                            </td>

                            <td>





                                    <span style="background-color:'.$cms_item['color'].';color:white;padding:4px;border-radius:5px;line-height:25px;margin:3px 0">

                                        '.$cms_item['name'].'

                                    </span>

                            </td>

                        </tr>';

        }





        $_html .= '</tbody>

                </table>

            </div>';





        $_html .= '<p class="clear">'.$this->l('This feature prevents customers to leave a review for orders that they haven\'t received yet. You must choose at least one status.').'</p>';

        $_html .= '</div>';





        $_html .= '<label>' . $this->l('Send a review reminder by email to customer when customer already write review in shop?') . ':</label>';



        $_html .= '<div class="margin-form">';

        $_html .= '

					<input type="radio" value="1" id="text_list_on" name="remrevsec'.$this->_prefix_shop_reviews.'"

							' . (Configuration::get($this->name . 'remrevsec'.$this->_prefix_shop_reviews) ? 'checked="checked" ' : '') . '>

					<label for="dhtml_on" class="t">

						<img alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="remrevsec'.$this->_prefix_shop_reviews.'"

						   ' . (!Configuration::get($this->name . 'remrevsec'.$this->_prefix_shop_reviews) ? 'checked="checked" ' : '') . '>

					<label for="dhtml_off" class="t">

						<img alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" src="../img/admin/disabled.gif">

					</label>

                    <p class="clear"></p>

							';

        $_html .= '</div>';







        $_html .= '<label>' . $this->l('Send a review reminder by email to customers a second time?') . ':</label>';



        $_html .= '<script type="text/javascript">

			    	function enableOrDisableReminderSecond'.$this->_prefix_shop_reviews.'(id)

						{

						if(id==0){

							$("#block-remindersec'.$this->_prefix_shop_reviews.'-settings").hide(200);

						} else {

							$("#block-remindersec'.$this->_prefix_shop_reviews.'-settings").show(200);

						}



						}

					</script>';







        $_html .= '<div class="margin-form">';

        $_html .= '

					<input type="radio" value="1" id="text_list_on" name="remindersec'.$this->_prefix_shop_reviews.'" onclick="enableOrDisableReminderSecond'.$this->_prefix_shop_reviews.'(1)"

							' . (Configuration::get($this->name . 'remindersec'.$this->_prefix_shop_reviews) ? 'checked="checked" ' : '') . '>

					<label for="dhtml_on" class="t">

						<img alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="remindersec'.$this->_prefix_shop_reviews.'" onclick="enableOrDisableReminderSecond'.$this->_prefix_shop_reviews.'(0)"

						   ' . (!Configuration::get($this->name . 'remindersec'.$this->_prefix_shop_reviews) ? 'checked="checked" ' : '') . '>

					<label for="dhtml_off" class="t">

						<img alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">' .

            $this->l('This feature allows you to send a second time the opinion request emails that have already been sent. ').'</p>



				';

        $_html .= '</div>';





        $_html .= '<div id="block-remindersec'.$this->_prefix_shop_reviews.'-settings" ' . (Configuration::get($this->name . 'remindersec'.$this->_prefix_shop_reviews) == 1 ? 'style="display:block"' : 'style="display:none"') . '>';



        $_html .= '<label>' . $this->l('Delay for sending reminder by email') . ':</label>';



        $_html .= '<div class="margin-form">';

        $_html .= '

					<input type="text" name="delaysec'.$this->_prefix_shop_reviews.'"

			               value="' . Configuration::get($this->name . 'delaysec'.$this->_prefix_shop_reviews) . '"

			               >&nbsp;(' . $this->l('days') . ')

				';

        $_html .= '</div>';

        $_html .= '</div>';







        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">

					<input type="submit" name="shopcustomerremindersettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';







        return $_html;

    }



    private function _drawSettingsShopReviewsOLD(){





        $_html = '';



        $_html .= '<style type="text/css">

    		.testimsettings-left{text-align:right;width:30%;padding:0 20px 0 0}

    		table.table-gsnipreview td{padding:5px}

    		</style>';





        $_html .= '<h3 class="title-block-content"><i class="fa fa-cogs fa-lg"></i>&nbsp;'.$this->l('Main Settings').'</h3>';



        $_html .= '

        <form action="'.$_SERVER['REQUEST_URI'].'" method="post">';



        $_html .= '<table style="width:100%" class="table-gsnipreview">';



        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left" >';



        $_html .= '<b>'.$this->l('Enable or Disable Store reviews functional on your site').':</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

					<input type="radio" value="1" id="is_storerev" name="is_storerev" onclick="enableOrDisableStoreReviews(1)"

							'.(Configuration::get($this->name.'is_storerev') ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>

					<input type="radio" value="0" id="is_storerev" name="is_storerev" onclick="enableOrDisableStoreReviews(0)"

						   '.(!Configuration::get($this->name.'is_storerev') ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				';





        $_html .= '<script type="text/javascript">

			    	function enableOrDisableStoreReviews(id)

						{

						if(id==0){

							$("#block-storereviews-settings").hide(200);

						} else {

							$("#block-storereviews-settings").show(200);

						}



						}

					</script>';

        $_html .= '</td>';

        $_html .= '</tr>';



        $_html .= '</table>';



        $_html .= '<div id="block-storereviews-settings" '.(Configuration::get($this->name.'is_storerev')==1?'style="display:block"':'style="display:none"').'>';



        $_html .= '<table style="width:100%" class="table-gsnipreview">';







        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('The number of items in the "Store reviews Block":').'</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

					<input type="text" name="tlast"

			               value="'.Tools::getValue('tlast', Configuration::get($this->name.'tlast')).'"

			               >

				';

        $_html .= '</td>';

        $_html .= '</tr>';



        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('Store reviews per Page:').'</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

					<input type="text" name="perpage'.$this->_prefix_shop_reviews.'"

			               value="'.Configuration::get($this->name.'perpage'.$this->_prefix_shop_reviews).'"

			               >

				';

        $_html .= '</td>';

        $_html .= '</tr>';







        ####

        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('Position Store reviews Block:').'</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';



        $_html .= '<table style="width:66%;">

	    				<tr>

	    					<td style="width: 33%">



	    					<input type="checkbox" name="t_left" '.((Configuration::get($this->name.'t_left') ==1)?'checked':'').'  value="1"/> '.$this->l('Left Column').'

	    					 <br/>



	    						<input type="checkbox" name="st_left" id="st_left" value="1" '.((Configuration::get($this->name.'st_left') ==1)?'checked':'').' />

                                    '.$this->l('display on the site view').'

                            <br/>

                                <input type="checkbox" name="mt_left" id="mt_left" value="1" '.((Configuration::get($this->name.'mt_left') ==1)?'checked':'').' />

                                    '.$this->l('display on the mobile view').'

	    					</td>

	    					<td style="width: 33%">

	    					<input type="checkbox" name="t_right" '.((Configuration::get($this->name.'t_right') ==1)?'checked':'') .' value="1"/> '.$this->l('Right Column').'

                                <br/>

                                <input type="checkbox" name="st_right"  value="1" '.((Configuration::get($this->name.'st_right') ==1)?'checked':'').' />

                                    '.$this->l('display on the site view').'

                                <br/>

                                <input type="checkbox" name="mt_right"  value="1" '.((Configuration::get($this->name.'mt_right') ==1)?'checked':'').' />

                                    '.$this->l('display on the mobile view').'

	    					</td>



	    				</tr>



	    				<tr>

	    					<td>



	    					<input type="checkbox" name="t_footer" '.((Configuration::get($this->name.'t_footer') ==1)?'checked':'') .' value="1"/> '.$this->l('Footer').'

                                <br/>

                                <input type="checkbox" name="st_footer"  value="1" '.((Configuration::get($this->name.'st_footer') ==1)?'checked':'').' />

                                    '.$this->l('display on the site view').'

                                <br/>

                                <input type="checkbox" name="mt_footer"  value="1" '.((Configuration::get($this->name.'mt_footer') ==1)?'checked':'').' />

                                    '.$this->l('display on the mobile view').'

	    					</td>

	    					<td>

	    					<input type="checkbox" name="t_home" '.((Configuration::get($this->name.'t_home') ==1)?'checked':'') .' value="1"/> '.$this->l('Home').'

                                <br/>

                                <input type="checkbox" name="st_home"  value="1" '.((Configuration::get($this->name.'st_home') ==1)?'checked':'').' />

                                    '.$this->l('display on the site view').'

                                <br/>

                                <input type="checkbox" name="mt_home"  value="1" '.((Configuration::get($this->name.'mt_home') ==1)?'checked':'').' />

                                    '.$this->l('display on the mobile view').'



	    					    </td>



	    				</tr>



	    				<tr>

	    					<td>

	    					<input type="checkbox" name="t_leftside" '.((Configuration::get($this->name.'t_leftside') ==1)?'checked':'') .' value="1"/> '.$this->l('Left Side').'

                                <br/>

                                <input type="checkbox" name="st_leftside"  value="1" '.((Configuration::get($this->name.'st_leftside') ==1)?'checked':'').' />

                                    '.$this->l('display on the site view').'

                                <br/>

                                <input type="checkbox" name="mt_leftside"  value="1" '.((Configuration::get($this->name.'mt_leftside') ==1)?'checked':'').' />

                                    '.$this->l('display on the mobile view').'





	    					</td>

	    					<td>



	    					<input type="checkbox" name="t_rightside" '.((Configuration::get($this->name.'t_rightside') ==1)?'checked':'') .' value="1"/> '.$this->l('Right Side').'

                                <br/>

                                <input type="checkbox" name="st_rightside"  value="1" '.((Configuration::get($this->name.'st_rightside') ==1)?'checked':'').' />

                                    '.$this->l('display on the site view').'

                                <br/>

                                <input type="checkbox" name="mt_rightside"  value="1" '.((Configuration::get($this->name.'mt_rightside') ==1)?'checked':'').' />

                                    '.$this->l('display on the mobile view').'

	    					</td>



	    				</tr>





	    			</table>';



        /*$_html .= '<table style="width:66%;">

	    				<tr>

	    					<td style="width: 33%">'.$this->l('Left Column').'</td>

	    					<td style="width: 33%">'.$this->l('Right Column').'</td>



	    				</tr>

	    				<tr>

	    					<td>

	    						<input type="checkbox" name="t_left" '.((Tools::getValue($this->name.'t_left', Configuration::get($this->name.'t_left')) ==1)?'checked':'').'  value="1"/>

	    					</td>

	    					<td>

	    						<input type="checkbox" name="t_right" '.((Tools::getValue($this->name.'t_right', Configuration::get($this->name.'t_right')) ==1)?'checked':'') .' value="1"/>

	    					</td>



	    				</tr>

	    				<tr>

	    					<td>'.$this->l('Footer').'</td>

	    					<td>'.$this->l('Home').'</td>



	    				</tr>

	    				<tr>

	    					<td>

	    						<input type="checkbox" name="t_footer" '.((Tools::getValue($this->name.'t_footer', Configuration::get($this->name.'t_footer')) ==1)?'checked':'').' value="1"/>

	    					</td>

	    					<td>

	    						<input type="checkbox" name="t_home" '.((Tools::getValue($this->name.'t_home', Configuration::get($this->name.'t_home')) ==1)?'checked':'').' value="1"/>

	    					</td>



	    				</tr>

	    				<tr>

	    					<td>'.$this->l('Left Side').'</td>

	    					<td>'.$this->l('Right Side').'</td>



	    				</tr>

	    				<tr>

	    					<td>

	    						<input type="checkbox" name="t_leftside" '.((Tools::getValue($this->name.'t_leftside', Configuration::get($this->name.'t_leftside')) ==1)?'checked':'').' value="1"/>

	    					</td>

	    					<td>

	    						<input type="checkbox" name="t_rightside" '.((Tools::getValue($this->name.'t_rightside', Configuration::get($this->name.'t_rightside')) ==1)?'checked':'').' value="1"/>

	    					</td>



	    				</tr>



	    			</table>';*/



        $_html .= '</td>';

        $_html .= '</tr>';







        $prefix_places = "s";

        ####

        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('Enable Google Rich snippets in following places:').'</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';



        $_html .= '<table style="width:66%;">

	    				<tr>

	    					<td style="width: 33%">'.$this->l('Left Column').'</td>

	    					<td style="width: 33%">'.$this->l('Right Column').'</td>



	    				</tr>

	    				<tr>

	    					<td>

	    						<input type="checkbox" name="t_left'.$prefix_places.'" '.((Configuration::get($this->name.'t_left'.$prefix_places) ==1)?'checked':'').'  value="1"/>

	    					</td>

	    					<td>

	    						<input type="checkbox" name="t_right'.$prefix_places.'" '.((Configuration::get($this->name.'t_right'.$prefix_places) ==1)?'checked':'') .' value="1"/>

	    					</td>



	    				</tr>

	    				<tr>

	    					<td>'.$this->l('Footer').'</td>

	    					<td>'.$this->l('Home').'</td>



	    				</tr>

	    				<tr>

	    					<td>

	    						<input type="checkbox" name="t_footer'.$prefix_places.'" '.((Configuration::get($this->name.'t_footer'.$prefix_places) ==1)?'checked':'').' value="1"/>

	    					</td>

	    					<td>

	    						<input type="checkbox" name="t_home'.$prefix_places.'" '.((Configuration::get($this->name.'t_home'.$prefix_places) ==1)?'checked':'').' value="1"/>

	    					</td>



	    				</tr>

	    				<tr>

	    					<td>'.$this->l('Left Side').'</td>

	    					<td>'.$this->l('Right Side').'</td>



	    				</tr>

	    				<tr>

	    					<td>

	    						<input type="checkbox" name="t_leftside'.$prefix_places.'" '.((Configuration::get($this->name.'t_leftside'.$prefix_places) ==1)?'checked':'').' value="1"/>

	    					</td>

	    					<td>

	    						<input type="checkbox" name="t_rightside'.$prefix_places.'" '.((Configuration::get($this->name.'t_rightside'.$prefix_places) ==1)?'checked':'').' value="1"/>

	    					</td>



	    				</tr>

	    				<tr>

	    					<td>'.$this->l('Store reviews page').'</td>

	    					<td>&nbsp;</td>



	    				</tr>

	    				<tr>

	    					<td>

	    						<input type="checkbox" name="t_tpage'.$prefix_places.'" '.((Configuration::get($this->name.'t_tpage'.$prefix_places) ==1)?'checked':'').' value="1"/>

	    					</td>

	    					<td>

	    						&nbsp;

	    					</td>



	    				</tr>



	    			</table>';



        $_html .= '</td>';

        $_html .= '</tr>';



        $_html .= $this->_colorpicker(array('name' => $this->name.'BGCOLOR_TIT',

            'color' => Configuration::get($this->name.'BGCOLOR_TIT'),

            'title' => $this->l('Store reviews title color. Only in the positions: Left Side and Right Side')

        ));





        $_html .= $this->_colorpicker(array('name' => $this->name.'BGCOLOR_T',

            'color' => Configuration::get($this->name.'BGCOLOR_T'),

            'title' => $this->l('Store reviews block background color')

        ));



        // Who can add reviews?

        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('Who can add review').'?</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">



				<input type="radio" value="reg" id="reg" name="whocanadd"

								'.(Configuration::get($this->name.'whocanadd'.$this->_prefix_shop_reviews) == "reg" ? 'checked="checked" ' : '').'>

			<b>'.$this->l('Only registered users').'</b>



			 <br/><br/>

				<input type="radio" value="buy" id="buy" name="whocanadd"

								'.(Configuration::get($this->name.'whocanadd'.$this->_prefix_shop_reviews) == "buy" ? 'checked="checked" ' : '').'>

			<b>'.$this->l('Only users who already bought the product').'</b>



			<br/><br/>

				<input type="radio" value="all" id="all" name="whocanadd"

								'.(Configuration::get($this->name.'whocanadd'.$this->_prefix_shop_reviews) == "all" ? 'checked="checked" ' : '').'>

			<b>'.$this->l('All users').'</b>



			';

        $_html .= '</td>';

        $_html .= '</tr>';

        // Who can add reviews?





        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('Enable Avatar in the submit form').':</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

					<input type="radio" value="1" id="text_list_on" name="is_avatar"

							'.(Tools::getValue('is_avatar', Configuration::get($this->name.'is_avatar')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>

					<input type="radio" value="0" id="text_list_off" name="is_avatar"

						   '.(!Tools::getValue('is_avatar', Configuration::get($this->name.'is_avatar')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				';

        $_html .= '</td>';

        $_html .= '</tr>';



        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('Enable Captcha in the submit form').':</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

					<input type="radio" value="1" id="is_captcha'.$this->_prefix_shop_reviews.'" name="is_captcha'.$this->_prefix_shop_reviews.'"

							'.(Configuration::get($this->name.'is_captcha'.$this->_prefix_shop_reviews) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>

					<input type="radio" value="0" id="is_captcha'.$this->_prefix_shop_reviews.'" name="is_captcha'.$this->_prefix_shop_reviews.'"

						   '.(!Configuration::get($this->name.'is_captcha'.$this->_prefix_shop_reviews) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				';

        $_html .= '</td>';

        $_html .= '</tr>';



        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('Enable Web address in the submit form').':</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

					<input type="radio" value="1" id="text_list_on" name="is_web"

							'.(Tools::getValue('is_web', Configuration::get($this->name.'is_web')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>

					<input type="radio" value="0" id="text_list_off" name="is_web"

						   '.(!Tools::getValue('is_web', Configuration::get($this->name.'is_web')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				';

        $_html .= '</td>';

        $_html .= '</tr>';



        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('Enable Company in the submit form').':</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

					<input type="radio" value="1" id="text_list_on" name="is_company"

							'.(Tools::getValue('is_company', Configuration::get($this->name.'is_company')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>

					<input type="radio" value="0" id="text_list_off" name="is_company"

						   '.(!Tools::getValue('is_company', Configuration::get($this->name.'is_company')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				';

        $_html .= '</td>';

        $_html .= '</tr>';





        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('Enable Address in the submit form').':</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

					<input type="radio" value="1" id="text_list_on" name="is_addr"

							'.(Tools::getValue('is_addr', Configuration::get($this->name.'is_addr')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>

					<input type="radio" value="0" id="text_list_off" name="is_addr"

						   '.(!Tools::getValue('is_addr', Configuration::get($this->name.'is_addr')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				';

        $_html .= '</td>';

        $_html .= '</tr>';





        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('Enable Country in the submit form').':</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

		<input type="radio" value="1" id="text_list_on" name="is_country"

		'.(Tools::getValue('is_country', Configuration::get($this->name.'is_country')) ? 'checked="checked" ' : '').'>

		<label for="dhtml_on" class="t">

		<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

		</label>

		<input type="radio" value="0" id="text_list_off" name="is_country"

		'.(!Tools::getValue('is_country', Configuration::get($this->name.'is_country')) ? 'checked="checked" ' : '').'>

		<label for="dhtml_off" class="t">

		<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

		</label>



		';

        $_html .= '</td>';

        $_html .= '</tr>';





        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('Enable City in the submit form').':</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

		<input type="radio" value="1" id="text_list_on" name="is_city"

		'.(Tools::getValue('is_city', Configuration::get($this->name.'is_city')) ? 'checked="checked" ' : '').'>

		<label for="dhtml_on" class="t">

		<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

		</label>

		<input type="radio" value="0" id="text_list_off" name="is_city"

		'.(!Tools::getValue('is_city', Configuration::get($this->name.'is_city')) ? 'checked="checked" ' : '').'>

		<label for="dhtml_off" class="t">

		<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

		</label>



		';

        $_html .= '</td>';

        $_html .= '</tr>';





        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('Admin email:').'</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .=  '

					<input type="text" name="mail'.$this->_prefix_shop_reviews.'"

			               value="'.Configuration::get($this->name.'mail'.$this->_prefix_shop_reviews).'"

			               >

				';

        $_html .= '</td>';

        $_html .= '</tr>';



        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('E-mail notification:').'</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';

        $_html .= '<input type = "checkbox" name = "noti'.$this->_prefix_shop_reviews.'"

                            id = "noti'.$this->_prefix_shop_reviews.'" value ="1" '.((Configuration::get($this->name.'noti'.$this->_prefix_shop_reviews) ==1)?'checked':'').'/>';

        $_html .= '</td>';

        $_html .= '</tr>';



        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$this->l('Enable or Disable RSS Feed').':</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';





        $_html .=  '

					<input type="radio" value="1" id="text_list_on" name="rssontestim"

							'.(Tools::getValue('rssontestim', Configuration::get($this->name.'rssontestim')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="rssontestim"

						   '.(!Tools::getValue('rssontestim', Configuration::get($this->name.'rssontestim')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>

				';

        $_html .= '</td>';

        $_html .= '</tr>';







        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';





        $_html .= '<b>'.$this->l('Number of items in RSS Feed').':</b>';



        $_html .= '<td style="text-align:left">';

        $_html .=  '

					<input type="text" name="n_rssitemst"

			               value="'.Tools::getValue('n_rssitemst', Configuration::get($this->name.'n_rssitemst')).'"

			               >

				';



        $_html .= '</td>';

        $_html .= '</tr>';













        $_html .= '</table>';



        $_html .= '</div>';



        $_html .= '<p class="center" style="text-align:center;border: 1px solid #EBEDF4; padding: 10px; margin-top: 10px;">

					<input type="submit" name="submit_testimonials" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';

        $_html .= '</form>';









        return $_html;

    }





    private function _colorpicker($data){



        $name = $data['name'];

        $color = $data['color'];

        $title = $data['title'];





        $_html = '';

        $_html .= '<tr>';

        $_html .= '<td class="testimsettings-left">';



        $_html .= '<b>'.$title.':'.'</b>';



        $_html .= '</td>';

        $_html .= '<td style="text-align:left">';



        /*$_html .= '<label style="margin-top:6px">'.$title.':'.'</label>

					<div class="margin-form">';*/

        $_html .= '				<input type="text"

								id="'.$name.'_val"

							   value="'.Tools::getValue($name, Configuration::get($name)).'"

								name="'.$name.'" style="float:left;margin-top:6px;margin-right:10px" >';

        $_html .= '<div id="'.$name.'" style="float:left;"><div style="background-color: '.$color.';"></div></div>

    			  <div style="clear:both"></div>

						<script>$(\'#'.$name.'\').ColorPicker({

								color: \''.$color.'\',

								onShow: function (colpkr) {

									$(colpkr).fadeIn(500);

									return false;

								},

								onHide: function (colpkr) {

									$(colpkr).fadeOut(500);

									return false;

								},

								onChange: function (hsb, hex, rgb) {

									$(\'#'.$name.' div\').css(\'backgroundColor\', \'#\' + hex);

									$(\'#'.$name.'_val\').val(\'\');

									$(\'#'.$name.'_val\').val(\'#\' + hex);

								}

							});</script>';

        //$_html .= '</div>';

        $_html .= '</td>';

        $_html .= '</tr>';

        return $_html;

    }





    private function _hint(){

    if(version_compare(_PS_VERSION_, '1.5', '<')) {

        $_html = '';



        $_html .= '<p style="display: block; font-size: 11px; width: 95%; margin-bottom:20px;position:relative" class="hint clear">

            <b style="color:#585A69">' . $this->l('If url rewriting doesn\'t works, check that this above lines exist in your current .htaccess file, if no, add it manually on top of your .htaccess file') . ':</b>

            <br/><br/>

            <code>';





        if ($this->_is15) {

            $physical_uri = array();

            foreach (ShopUrl::getShopUrls() as $shop_url) {

                if (in_array($shop_url->physical_uri, $physical_uri)) continue;



                $_html .= 'RewriteRule ^(.*)storereviews$ ' . (($this->_is15) ? $shop_url->physical_uri : '/') . 'modules/' . $this->name . '/storereviews-form.php [QSA,L] <br/>

                           RewriteRule ^(.*)mystorereview$ ' . (($this->_is15) ? $shop_url->physical_uri : '/') . 'modules/' . $this->name . '/my-storereviews.php [QSA,L] <br/>

                          ';



                $physical_uri[] = $shop_url->physical_uri;

            }

        } else {

            $_html .= 'RewriteRule ^(.*)storereviews$ /modules/' . $this->name . '/storereviews-form.php [QSA,L] <br/>

                           RewriteRule ^(.*)mystorereview$ /modules/' . $this->name . '/my-storereviews.php [QSA,L] <br/>

                          ';

        }



        $_html .= '

            </code>





            </p>';



        return $_html;

    }





    }





    private function _helpRichPins(){

        $_html = '';

        $_html .= '<div id="block-pin-help" '.(Configuration::get($this->name.'pinvis_on')==1?'style="display:block"':'style="display:none"').'>';



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<div class="panel">



				<div class="panel-heading"><i class="fa fa-info fa-lg"></i>&nbsp;'.$this->l('Help').'</div>';

        } else {

            $_html .= '<h3 class="title-block-content">'.$this->l('Help').'</h3>';

        }





        $_html .= '<b>'.$this->l('To validate your shop, please follow these steps').':</b><br/><br/>



    - '.$this->l('Create an account or login to pinterest on').' <a href="http://www.pinterest.com" style="text-decoration:underline" target="_blank">http://www.pinterest.com</a><br/><br/>

    - '.$this->l('Open another tab in your browser and go to').' <a href="https://developers.pinterest.com/tools/url-debugger/" style="text-decoration:underline" target="_blank">https://developers.pinterest.com/tools/url-debugger/</a><br/><br/>

    - '.$this->l('Insert the url of one of your shop products in the text field and press "Validate" button').'<br/><br/>

    - '.$this->l('Once your url has been processed you will see a page similar to the screenshot below with the data of your rich pin, now press on the "Apply now" button just below "Validate"').'<br/><br/>

    	<img src="../modules/'.$this->name.'/views/img/pinterest-help/p-help1.png" class="img-responsive" />

		<br/><br/>

    - '.$this->l('Now you will be prompted to insert the domain where the rich pins will be validated and the data format for the rich pins.').'

    	<br/> <br/>

    	'.$this->l('The domain and the data format should be precompiled, just check if the domain is correct and that data format is "HTML Tags" and then click "Apply now"').'

    <br/><br/>

    <img src="../modules/'.$this->name.'/views/img/pinterest-help/p-help2.png"  class="img-responsive" />

    <br/><br/>

    - '.$this->l('The process is complete, now it\'s only a matter of time to get your site enabled for rich pins.').'

    <br/><br/>

    <img src="../modules/'.$this->name.'/views/img/pinterest-help/p-help3.png" class="img-responsive" />

    <br/><br/>

    '.$this->l('Remember that you need to validate only 1 pin to enable rich pins on your whole domain.').'

    <br/><br/>

	'.$this->l('All your old pins will be converted automatically to rich pins when the first pin is verified.').'

    ';



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '</div>';

        }



        $_html .= '</div>';

        return $_html;

    }

    

    private function _snippetsSettings(){

    	$_html = '';

    	

    	$_html .= '<h3 class="title-block-content">'.$this->l('Google Rich Snippets').'</h3>';

    	

    	

    	$_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';

    	

    	

    	// Enable or Disable Google Rich Snippets

    	$_html .= '<label >'.$this->l('Enable or Disable Google Rich Snippets').':</label>

				<div class="margin-form">

				

					<input type="radio" value="1" id="text_list_on" name="svis_on" onclick="enableOrDisableSnip(1)"

							'.(Tools::getValue('svis_on', Configuration::get($this->name.'svis_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t"> 

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>

					

					<input type="radio" value="0" id="text_list_off" name="svis_on" onclick="enableOrDisableSnip(0)"

						   '.(!Tools::getValue('svis_on', Configuration::get($this->name.'svis_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>

					

					<p class="clear">'.



					$this->l('Enable or Disable Google Rich Snippets').'</p>

				</div>';

    	

    	$_html .= '<script type="text/javascript">

			    	function enableOrDisableSnip(id)

						{

						if(id==0){

							$("#block-snip-settings").hide(200);

						} else {

							$("#block-snip-settings").show(200);

						}

							

						}

					</script>';

    	

    	

    	if(version_compare(_PS_VERSION_, '1.6', '<')){

            $_html .= '<br/><br/>';

    	

    	$_html .= '<div id="block-snip-settings" '.(Configuration::get($this->name.'svis_on')==1?'style="display:block"':'style="display:none"').'>';

    	

    	

		

		

		$_html .= '<br/>';

		$_html .= '<table style="width:100%">';

		$_html .= '<tr>';

    	$_html .= '<td  style="text-align:right;padding:0 20px 0 0;vertical-align:top">';

    	

		 $_html .= '<b>'.$this->l('Display Google Rich Snippets Block').'</b>';

				$_html .= '</td>';

    	$_html .= '<td style="text-align:left">

					<input type="radio" value="1" id="text_list_on" name="gsnipblock"

							'.(Tools::getValue('gsnipblock', Configuration::get($this->name.'gsnipblock')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t"> 

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>

					

					<input type="radio" value="0" id="text_list_off" name="gsnipblock"

						   '.(!Tools::getValue('gsnipblock', Configuration::get($this->name.'gsnipblock')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>

					

					<p class="clear">'.$this->l('Enable or Disable Google Rich Snippets Block. You need to activate this option if you want the Google Rich Snippets functionality to work correctly. It will display a nice visual badge with summary information about your product and its ratings on each product page.').'</p>

				';

    	

    	$_html .= '</td>';

		$_html .= '</tr>';

		

		$_html .= '<tr>';

    	$_html .= '<td style="text-align:right;width:15%;padding:0 20px 0 0;vertical-align:top">';

    	

		 

		 $_html .= '<b>'.$this->l('Width Google Rich Snippets Block').':</b>';

    			

    	$_html .= '</td>';

    	$_html .= '<td style="text-align:left">

					<input type="text" name="gsnipblock_width"  style="width:200px"

			                		value="'.Tools::getValue('gsnipblock_width', Configuration::get($this->name.'gsnipblock_width')).'"> px

			    	<p class="clear">'.$this->l('Width Google Rich Snippets Block in pixel.').'</p>

				

					';

		 $_html .= '</td>';

		$_html .= '</tr>';

		

		$_html .= '<tr>';

    	$_html .= '<td style="text-align:right;width:15%;padding:0 20px 0 0">';

		 $_html .= '<b>'.$this->l('Enable Logo in Google Rich Snippets Block').'</b>';

		 

		$_html .= '</td>';

    	$_html .= '<td style="text-align:left">		

					<input type="radio" value="1" id="text_list_on" name="gsnipblocklogo"

							'.(Tools::getValue('gsnipblocklogo', Configuration::get($this->name.'gsnipblocklogo')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t"> 

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>

					

					<input type="radio" value="0" id="text_list_off" name="gsnipblocklogo"

						   '.(!Tools::getValue('gsnipblocklogo', Configuration::get($this->name.'gsnipblocklogo')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>

					

					<p class="clear">'.$this->l('Enable Logo in Google Rich Snippets Block').'.</p>

				';

		 $_html .= '</td>';

		$_html .= '</tr>';

		

		$_html .= '<tr>';

    	$_html .= '<td style="text-align:right;width:15%;padding:0 20px 0 0">';

    	 

		$_html .= '<b>'.$this->l('Select Hook where displayed Google Rich Snippets Block').':</b>';

    	$_html .= '</td>';

    	$_html .= '<td style="text-align:left">	';			

			$data_hooks = $this->_hooks_avaiable;



			    	

			   $_html .= '<select name="id_hook_gsnipblock">';

						foreach($data_hooks as $id_hook => $name_hook){

		    					

								

			    			if($id_hook == Configuration::get($this->name.'id_hook_gsnipblock')){

			    					$_html .= '<option  value="'.$id_hook.'" selected="selected">'.$name_hook.'</option>';

							}else{

									$_html .= '<option  value="'.$id_hook.'">'.$name_hook.'</option>';

 			    			}

							}

			    		$_html .= '</select>';

 		

 		 $_html .= '</td>';

		$_html .= '</tr>';	

 		$_html .= '</table>';

 		

 		$_html .= '</div>';

 		

    	}





        $_html .= '<br/><br/><br/><br/>';



        // Enable or Disable Google Rich Snippets

        $_html .= '<label >'.$this->l('Enable or Disable Google Breadcrumbs').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="breadvis_on"

							'.(Tools::getValue('breadvis_on', Configuration::get($this->name.'breadvis_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="breadvis_on"

						   '.(!Tools::getValue('breadvis_on', Configuration::get($this->name.'breadvis_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.$this->l('Enable or Disable Google Breadcrumbs').

                            '.&nbsp;<b>'.$this->l('Breadcrumbs').'</b>:'.

                            '&nbsp;'.$this->l('More info').':&nbsp;

                            <a href="https://developers.google.com/structured-data/breadcrumbs" style="text-decoration:underline" target="_blank">https://developers.google.com/structured-data/breadcrumbs</a>

                            </p>

				</div>';



        $_html .= '<br/><br/><br/>';



        $_html .= '<label>'.$this->l('Enable or Disable Block with summary info about product ratings and reviews').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="allinfo_on"

							'.(Tools::getValue('allinfo_on', Configuration::get($this->name.'allinfo_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="allinfo_on"

						   '.(!Tools::getValue('allinfo_on', Configuration::get($this->name.'allinfo_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.$this->l('Enable or Disable Block with summary info about product ratings and reviews ').'</p>

				</div>';









            $input = $this->_blockallinfo();

            $_html .= '<label>'.$input['label'].'</label>';



            $_html .= '<div class="col-lg-6 '.$input['name'].'">

                <div class="panel">







                    <table class="table">

                        <thead>

                        <tr>



                            <th><b>'.$this->l('Block').'</b></th>

                            <th><b>'.$this->l('Position').'</b></th>

                            <th><b>'.$this->l('Width').'</b></th>

                            <th><b>'.$this->l('Status').'</b></th>

                        </tr>

                        </thead>

                        <tbody>';





                    foreach($input['values'] as $key => $cms_item){

                       $_html .= '     <tr class="alt_row">

                                <td>

                                    '.$cms_item['name'].'

                                </td>

                                <td>

                                    <div class="col-lg-12">



                                         <select id="p'.$key.'" class="col-sm-12" name="p'.$key.'">';

                                             if($key == 'allinfo_home'){

                                                foreach($input['available_pos_home'] as $key_pos => $cms_item_pos)

                                                $_html .= '<option '.(($cms_item['position'] == $key_pos)?'selected="selected"':'').'

                                                            value="'.$key_pos.'">'.$cms_item_pos.'</option>';





                                             } else {

                                                 foreach($input['available_pos'] as $key_pos => $cms_item_pos)

                                                   $_html .= '<option '.(($cms_item['position'] == $key_pos)?'selected="selected"':'').'

                                                             value="'.$key_pos.'">'.$cms_item_pos.'</option>';







                                             }



                                         $_html .= '</select>

                                     </div>



                                </td>

                                <td>

                                    <div class="input-group">

                                        <input type="text" name="'.$cms_item['width']['name'].'"

                                               value="'.$cms_item['width']['width'].'" />

                                        <span class="input-group-addon">&nbsp;%</span>





                                    </div>



                                </td>

                                <td>

                                    <div class="checkbox">



                                        <label for="'.$key.'">

                                            <input type="checkbox" '.(($cms_item['status'] == $key)?'checked="checked"':'').'

                                                   value="'.$key.'" id="'.$key.'"

                                                   name="'.$key.'"/>

                                        </label>

                                    </div>



                                </td>

                            </tr>';

                        }





                  $_html .= '     </tbody>

                    </table>

                </div>





        </div>';

        //}





        $_html .= '<br/><br/><br/><br/>';



        // Enable or Disable Rich Pins

        $_html .= '<label>'.$this->l('Enable or Disable Rich Pins').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="pinvis_on" onclick="enableOrDisablePin(1)"

							'.(Tools::getValue('pinvis_on', Configuration::get($this->name.'pinvis_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="pinvis_on" onclick="enableOrDisablePin(0)"

						   '.(!Tools::getValue('pinvis_on', Configuration::get($this->name.'pinvis_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.$this->l('Enable or Disable Rich Pins').'.</p>

				</div>';



        $_html .= '<script type="text/javascript">

			    	function enableOrDisablePin(id)

						{

						if(id==0){

							$("#block-pin-settings").hide(200);

							$("#block-pin-help").hide(200);

						} else {

							$("#block-pin-settings").show(200);

							$("#block-pin-help").show(200);



						}



						}

					</script>';







        $_html .= '<div id="block-pin-settings" '.(Configuration::get($this->name.'pinvis_on')==1?'style="display:block"':'style="display:none"').'>';









        $_html .= '<label>'.$this->l('Pinterest Button style').'</label>

				<div class="margin-form">';

        $_html .= '<table style="width:50%;" cellpadding="0" cellspacing="0">';

        $_html .= '<tr>

	    			   <td style="">

	    			   		<input type="radio" value="firston" id="pinterestbuttons" name="pinterestbuttons"

								'.(Tools::getValue('pinterestbuttons', Configuration::get($this->name.'pinterestbuttons')) == "firston" ? 'checked="checked" ' : '').'>

					   </td>

	    			   <td>

	    			   	<img src="../modules/'.$this->name.'/views/img/p-top.png" />

					   </td>

    			   ';

        $_html .= '<td style="">

	    			   		<input type="radio" value="secondon" id="pinterestbuttons" name="pinterestbuttons"

								'.(Tools::getValue('pinterestbuttons', Configuration::get($this->name.'pinterestbuttons')) == "secondon" ? 'checked="checked" ' : '').'>

					   </td>

	    			   <td>

	    			   	<img src="../modules/'.$this->name.'/views/img/p-horizontal.png" />

					   </td>';

        $_html .= '<td style="">

	    			   		<input type="radio" value="threeon" id="pinterestbuttons" name="pinterestbuttons"

								'.(Tools::getValue('pinterestbuttons', Configuration::get($this->name.'pinterestbuttons')) == "threeon" ? 'checked="checked" ' : '').'>

					   </td>

	    			   <td>

	    			   	<img src="../modules/'.$this->name.'/views/img/p-none.png" />

					   </td>

					</tr>';

        $_html .= '</table>';



        $_html .= '</div>';





        $leftColumn = Configuration::get($this->name.'_leftColumn');

        $extraLeft = Configuration::get($this->name.'_extraLeft');

        $productFooter = Configuration::get($this->name.'_productFooter');

        $rightColumn = Configuration::get($this->name.'_rightColumn');

        $extraRight = Configuration::get($this->name.'_extraRight');

        $productActions = Configuration::get($this->name.'_productActions');



        ob_start();?>

        <style>

            .choose_hooks input{margin-bottom: 10px}

        </style>



        <label>Position:</label>

        <div class="margin-form choose_hooks">

            <table style="width:80%;">

                <tr>



                    <td style="width: 33%"><?= $this->l('Left column, only product page')?></td>

                    <td style="width: 33%"><?= $this->l('Extra left')?></td>

                    <td style="width: 33%"><?= $this->l('Product footer')?></td>

                </tr>

                <tr>



                    <td>

                        <input type="checkbox" name="leftColumn" <?=($leftColumn == 'leftColumn' ? 'checked="checked"' : ''); ?> value="leftColumn"/>

                    </td>

                    <td>

                        <input type="checkbox" name="extraLeft" <?=($extraLeft == 'extraLeft' ? 'checked="checked"' : ''); ?> value="extraLeft"/>

                    </td>

                    <td>

                        <input type="checkbox" name="productFooter" <?= ($productFooter == 'productFooter' ? 'checked="checked"' : ''); ?> value="productFooter"/>

                    </td>

                </tr>

                <tr>

                    <td><?= $this->l('Right column, only product page')?></td>

                    <td><?= $this->l('Extra right')?></td>

                    <td><?= $this->l('Product actions')?></td>

                </tr>

                <tr>



                    <td>

                        <input type="checkbox" name="rightColumn" <?= ($rightColumn == 'rightColumn' ? 'checked="checked"' : ''); ?> value="rightColumn"/>

                    </td>

                    <td>

                        <input type="checkbox" name="extraRight" <?= ($extraRight == 'extraRight' ? 'checked="checked"' : ''); ?> value="extraRight"/>

                    </td>

                    <td>

                        <input type="checkbox" name="productActions" <?= ($productActions == 'productActions' ? 'checked="checked"' : '') ?> value="productActions"/>

                    </td>

                </tr>



            </table>

        </div>





        <?php 	$_html .= ob_get_contents();

        ob_end_clean();



        $_html .= '</div>';





 		

 		$_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">

					<input type="submit" name="snippetssettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';

                	

    	$_html .= '</form>';





        $_html .= $this->_helpRichPins();

    	

    	

    	

    	

    	return $_html;

    }

   

    public function _moderateReviews($_data_in = null){

    	$currentIndex = $this->context->currentindex;

		

    	$cookie = $this->context->cookie;



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $base_dir = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__;

        } else {

            $base_dir = _PS_BASE_URL_SSL_.__PS_BASE_URI__;

        }

		

    	$currentIndex = isset($_data_in['currentindex'])?$_data_in['currentindex']:$currentIndex;

    	$controller = isset($_data_in['controller'])?$_data_in['controller']:'AdminModules';

    	$token = isset($_data_in['token'])?$_data_in['token']:Tools::getAdminToken($controller.(int)(Tab::getIdFromClassName($controller)).(int)($cookie->id_employee));



        $token_popup = Tools::getAdminToken('AdminCustomers'.(int)(Tab::getIdFromClassName('AdminCustomers')).(int)($cookie->id_employee));



        $token_customer_autocomplete = Tools::getAdminToken('AdminCartRules'.((int)(Tab::getIdFromClassName('AdminCartRules'))).(int)($cookie->id_employee));



    	

    	include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

		$obj_reviewshelp = new gsnipreviewhelp();

		$_html = '';

		$_html .= '<div style="margin-top:10px">';

		$j=0;





        $errors = isset($_data_in['errors'])?$_data_in['errors']:array();





		

		if(Tools::isSubmit("edit_item") || Tools::isSubmit("add".$this->name)){





            $id = (int)Tools::getValue('id');





            if($id) {



                $_data = $obj_reviewshelp->getItem(array('id'=>$id));

                $criterions = isset($_data['reviews'][0]['criterions']) ? $_data['reviews'][0]['criterions'] : array();

                $rating = isset($_data['reviews'][0]['rating']) ? $_data['reviews'][0]['rating'] :0 ;

                $name_lang =  isset($_data['reviews'][0]['name_lang']) ? $_data['reviews'][0]['name_lang'] :'' ;

                $id_lang =  isset($_data['reviews'][0]['name_lang']) ? $_data['reviews'][0]['id_lang'] :(int)($cookie->id_lang) ;

                $review_url = isset($_data['reviews'][0]['review_url']) ? $_data['reviews'][0]['review_url'] :'' ;

                $ip = isset($_data['reviews'][0]['ip']) ? $_data['reviews'][0]['ip'] :'' ;

                $time_add = isset($_data['reviews'][0]['time_add']) ? $_data['reviews'][0]['time_add'] :'' ;



                $customer_name = isset($_data['reviews'][0]['customer_name']) ? $_data['reviews'][0]['customer_name'] : '';

                $title_review = isset($_data['reviews'][0]['title_review']) ? $_data['reviews'][0]['title_review'] : '';

                $text_review = isset($_data['reviews'][0]['text_review']) ? $_data['reviews'][0]['text_review'] : '';

                $email = isset($_data['reviews'][0]['email']) ? $_data['reviews'][0]['email'] : '';

                $is_active = isset($_data['reviews'][0]['is_active']) ? $_data['reviews'][0]['is_active'] : '';



                $id_product = isset($_data['reviews'][0]['id_product']) ? $_data['reviews'][0]['id_product'] : 0;





                $_obj_product = new Product($id_product,null,$id_lang);

                $name_product = $_obj_product->name;

                $data_product = $this->_productData(array('product'=>$_obj_product));

                $product_url = $data_product['product_url'];



                $id_customer = isset($_data['reviews'][0]['id_customer']) ? $_data['reviews'][0]['id_customer'] : 0;

                $admin_url_to_customer = isset($_data['reviews'][0]['user_url']) ? $_data['reviews'][0]['user_url'].$id_customer :0 ;



                $admin_response = isset($_data['reviews'][0]['admin_response']) ? $_data['reviews'][0]['admin_response'] : '';

                $is_display_old  = isset($_data['reviews'][0]['is_display_old']) ? $_data['reviews'][0]['is_display_old'] : 0;





                $files = isset($_data['reviews'][0]['files']) ? $_data['reviews'][0]['files'] :array() ;



                $avatar = isset($_data['reviews'][0]['avatar'])?$_data['reviews'][0]['avatar']:'';

                $is_exist_ava = isset($_data['reviews'][0]['is_exist']) ? $_data['reviews'][0]['is_exist'] :0 ;



                $submit_action = 'update_item';





            } else {



                $id_lang = $cookie->id_lang;

                $criterions =  $obj_reviewshelp->getReviewCriteria(array('id_lang'=>$id_lang,'id_shop'=>$obj_reviewshelp->getIdShop()));





                $rating = 0;

                $name_lang = '';

                $review_url = '';

                $ip = '';

                $time_add = date("Y-m-d H:i:s");

                $id_lang = 0;

                $customer_name = '';

                $title_review = '';

                $text_review = '';

                $email = '';

                $is_active = 0;

                $product_url = '';



                $submit_action = 'add_item';

            }



            switch(Configuration::get($this->name.'stylestars')){

                case 'style1':

                    $activestar = 'star-active-yellow.png';

                    $noactivestar = 'star-noactive-yellow.png';

                    break;

                case 'style2':

                    $activestar = 'star-active-green.png';

                    $noactivestar = 'star-noactive-green.png';

                    break;

                case 'style3':

                    $activestar = 'star-active-blue.png';

                    $noactivestar = 'star-noactive-blue.png';

                    break;

                default:

                    $activestar = 'star-active-yellow.png';

                    $noactivestar = 'star-noactive-yellow.png';

                    break;

            }



            if($id){

                $title_item_form = $this->l('Edit review:');

            } else{

                $title_item_form = $this->l('Add new review:');

            }







            $_html .= '<h3 class="title-block-content"><i class="fa fa-reviews fa-lg"></i>&nbsp;'.$title_item_form.'</h3>';





            $error_html = '';

            if(count($errors)>0){

                foreach($errors as $error_text)

                    $error_html .= '<div class="error">'.$error_text.'</div>';

            }

            $_html .= $error_html;



    	

    		$_html .= '

    					<form action="'.$_SERVER['REQUEST_URI'].'" method="post" enctype="multipart/form-data">';



            if($id) {

                /// edit review ///



                $_html .= '<label>' . $this->l('ID') . ':</label>';

                $_html .= '<div style="padding:0 0 1em 210px;line-height:1.6em;"><span class="badge">' . $id . '</span></div>';



                $_html .= '<label>' . $this->l('Language') . ':</label>';

                $_html .= '<div style="padding:0 0 1em 210px;line-height:1.6em;"><span class="badge">' . $name_lang . '</span></div>';

                $_html .= '<input type="hidden" name="id_lang" value="' . $id_lang . '" />';



                $_html .= '<label>' . $this->l('Review URL') . ':</label>';

                $_html .= '<div style="padding:0 0 1em 210px;line-height:1.6em;">

                            <span class="badge">

                                    <a href="' . $review_url . '" target="_blank" title="' . $review_url . '">

                                    ' . $review_url . '

                                    </a>

                             </span></div>';



                $_html .= '<label>' . $this->l('Product') . ':</label>';

                $_html .= '<div style="padding:0 0 1em 210px;line-height:1.6em;">

                            <span class="badge">

                                    <a href="' . $product_url . '" target="_blank" title="' . $product_url . '">

                                    ' . $name_product . '

                                    </a>

                             </span></div>';



                $_html .= '<label>' . $this->l('IP') . ':</label>';

                $_html .= '<div style="padding:0 0 1em 210px;line-height:1.6em;"><span class="badge">' . $ip . '</span></div>';

                $_html .= '<div class="clear"></div>';





                $_html .= '<label>'.$this->l('Avatar').'</label>



    				<div class="margin-form">

    				<input type="hidden" name="id_customer" value="'.$id_customer.'" />

					<input type="file" name="avatar-review" id="avatar-review" ';

                if($this->_is16 == 0){

                    $_html .= 'class="customFileInput"';

                }

                $_html .= '/>

					<p>'.$this->l('Allow formats').' *.jpg; *.jpeg; *.png; *.gif.<br/>'.$this->l('Max file size in php.ini').'&nbsp;<b style="color:green">'.ini_get('upload_max_filesize').'</b></p>';





                if($is_exist_ava){

                    $_html .= '

                        <input type="radio" name="post_images" checked="" style="display: none">

                        <span class="avatar-form">

                        <img src="'.$avatar.'" />

                        </span>

                        <br/>



                        <a class="delete_product_image btn btn-default avatar-button15" href="javascript:void(0)"

                           onclick = "delete_avatar('.$id.','.$id_customer.');"

                           style="margin-top: 10px">

                            '.$this->l('Delete avatar and use standart empty avatar').' <img src="'._PS_ADMIN_IMG_.'delete.gif" alt="" />

                        </a>';

                } else{

                    $_html .= '<span class="avatar-form"><img src = "../modules/'.$this->name.'/views/img/avatar_m.gif" /></span>';

                }



                $_html .= '</div>';



                if($this->_is16 == 1){

                    $_html .= '<br/>';

                }



                $_html .= '<label>'.$this->l('Customer name').':</label>

    					<div class="margin-form" >

							<input type="text" name="customer_name"  style="width:200px"

			                	   value="'.htmlentities($customer_name).'">

						</div>';

                $_html .= '<div class="clear"></div>';



                $_html .= '<label>'.$this->l('Customer Email').':</label>

    					<div class="margin-form" >

							<input type="text" name="email"  style="width:200px"

			                	   value="'.$email.'">

						</div>';

                $_html .= '<div class="clear"></div>';



                if($id_customer && Configuration::get($this->name.'is_uprof') == 1){

                    $_html .= '<label>'.$this->l('Customer URL').':</label>';

                    $_html .= '<div style="padding:0 0 1em 210px;line-height:1.6em;">

                            <span class="badge">

                                    <a href="' . $admin_url_to_customer . '" target="_blank" title="' . $customer_name . '">

                                    ' . $customer_name . '

                                    </a>

                             </span></div>';

                    $_html .= '<div class="clear"></div>';

                }



            } else {

                /// add review ///

                if($this->_is15) {



                    $_html .= '<label>' . $this->l('Select your shop') . ':</label>

    					<div class="margin-form" >

							<select id="ids_shop" class=" fixed-width-xl" name="ids_shop">';

                    foreach (Shop::getShops() as $shop)

                        $_html .= '<option value="' . $shop['id_shop'] . '">' . $shop['name'] . '</option>';

                    $_html .= '</select>

						</div>';

                    $_html .= '<div class="clear"></div>';

                }

                $_html .= '<label>'.$this->l('Select product').':</label>

    					<div class="margin-form" >';

						$_html .= '<div id="divAccessories"></div>



                    <input type="hidden" name="inputAccessories" id="inputAccessories" value="" />



                    <div id="ajax_choose_product" style="width:100%">

                        <input type="text" value="" id="product_autocomplete_input" style="width:50%" autocomplete="off" />

                    </div>





                        <script type="text/javascript">

                            $(\'document\').ready( function() {

                                if($(\'#divAccessories\').length){



                                    initAccessoriesAutocomplete();



                                }

                        });

                        </script>



                    <p class="help-block">

                        '.$this->l('Begin typing the first letters of the product name, then select the product from the drop-down list').'

                    </p>';

				$_html .= '</div>';

                $_html .= '<div class="clear"></div>';







                $_html .= '<label>'.$this->l('Select customer').':</label>

    					<div class="margin-form" >';

				$_html .= '<div id="divCustomers"></div>



                    <input type="hidden" name="inputCustomers" id="inputCustomers" value="" />

                    <input type="hidden" name="inputCustomersToken" id="inputCustomersToken"

                    value="'.$token_customer_autocomplete.'" />



                    <div id="ajax_choose_customer" style="width:100%">

                        <input type="text" value="" id="customer_autocomplete_input" style="width:50%" autocomplete="off" />

                    </div>







                        <script type="text/javascript">

                            $(\'document\').ready( function() {





                            if($(\'#divCustomers\').length){

                            ';



                                if($this->_is15) {

                                    $_html .= '  initCustomersAutocomplete();';

                                } else {

                                    $_html .= '  initCustomersAutocomplete14_13();';

                                }



                            $_html .= '}





                        });

                    </script>





                    <p class="help-block">

                        '.$this->l('Begin typing the first letters of the customer name, then select the customer from the drop-down list').'

                    </p>';



				$_html .='</div>';

                $_html .= '<div class="clear"></div>';





                $_html .= '<label>'.$this->l('Select customer language').':</label>

    					<div class="margin-form" >';

					$_html .= '<select id="ids_lang" class=" fixed-width-xl" name="ids_lang">';

                foreach(Language::getLanguages(true) as $language)

                    $_html .=  '<option value="'.$language['id_lang'].'">'.$language['name'].'</option>';



                $_html .= '</select>';

						$_html .= '</div>';

                $_html .= '<div class="clear"></div>';

            }





            $_html .= '<label >'.$this->l('Title').':</label>

    					<div class="margin-form" >

							<input type="text" name="title_review"  style="width:500px"

			                	   value="'.htmlentities($title_review).'">

						</div>';



            $_html .= '<label >'.$this->l('Text').':</label>

    					<div class="margin-form" >

							<textarea name="text_review" cols="100" rows="10"

			                	   >'.$text_review.'</textarea>

						</div>';









            if($id) {

                $_html .= '<label >' . $this->l('Shop owner reply') . ':</label>

    					<div class="margin-form" >

							<textarea name="admin_response" cols="100" rows="10"

			                	   >' . $admin_response . '</textarea>

						</div>';



                $_html .= '

				<label>'.$this->l('Send "Shop owner reply" notification to the customer').'</label>

				<div class = "margin-form" >';



                $_html .= '<input type = "checkbox" name = "is_noti" id = "is_noti" value ="1" />';



                $_html .= '<div class="clear"></div></div>';



                $_html .= '

                    <label>'.$this->l('Display "Shop owner reply" on the site').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="is_display_old" name="is_display_old" '.(($is_display_old ==1)?'checked="checked':'').'

							>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="is_display_old" name="is_display_old"

						   '.(($is_display_old == 0)?'checked="checked':'').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>

					<p class="clear"></p>



				</div>';



                if(Configuration::get($this->name.'is_files'.$this->_prefix_review) == 1) {

                    if (count($files) > 0) {

                        $_html .= '

                            <label>' . $this->l('Files') . ':</label>

                            <div class="margin-form">';





                        foreach($files as $file){

                            $_html .= '

                    <div class="col-sm-2-custom files-review-gsnipreview-admin" id="file-custom-'.$file['id'].'">

                        <div class="text-align-center">

                            <a class="fancybox shown" data-fancybox-group="other-views"

                            href="'.$base_dir.$file['full_path'].'">

                                <img src="'.$base_dir.$file['full_path'].'"

                                    width="105" height="105" class="img-thumbnail-custom" alt="" />

                            </a>

                        </div>

                        <br/>

                        <div class="text-align-center">

                            <a class="delete_review_file btn btn-default" href="javascript:void(0)"

                               onclick = "delete_file('.$file['id'].');"

                               style="margin-top: 10px">

                                <i class="icon-trash"></i>&nbsp;'.$this->l('Delete').'

                            </a>

                        </div>

                    </div>



                ';

                        }



                        $_html .= '

                            <p class="clear"></p>

                            </div>';

                    }

                }

            }



            ## rating ##

    		$_html .= '<label >'.$this->l('Rating').':</label>

    					<div class="margin-form" >';

            $_html .= '<script type="text/javascript">



                    var module_dir_admin = "'.__PS_BASE_URI__."modules/".$this->name.'/";

                    var gsnipreview_star_active = \''.$activestar.'\';

                    var gsnipreview_star_noactive = \''.$noactivestar.'\';



                </script>';

            $base_dir_ssl = _PS_BASE_URL_SSL_.__PS_BASE_URI__;



            if(count($criterions)>0){

                foreach($criterions as $criterion){

            $_html .= '



                <div class="rating-stars-dynamic-item-admin">

                    <span for="rat_rel'.$criterion['id_gsnipreview_review_criterion'].'"

                           class="float-left rating-stars-dynamic-title-admin">'.$criterion['name'].'<sup class="required">*</sup></span>



                    <span class="rat rating-stars-dynamic-admin">

                                                        <span onmouseout="read_rating_review_shop(\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\');">



                                                            <img  onmouseover="_rating_efect_rev(1,0,\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\')"

                                                                  onmouseout="_rating_efect_rev(1,1,\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\')"

                                                                  onclick = "rating_review_shop(\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\',1); rating_checked'.$criterion['id_gsnipreview_review_criterion'].'=true; "

                                                                  src="'.$base_dir_ssl.'modules/'.$this->name.'/views/img/'.$noactivestar.'"

                                                                  alt="" id="img_rat_rel'.$criterion['id_gsnipreview_review_criterion'].'_1" />



                                                            <img  onmouseover="_rating_efect_rev(2,0,\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\')"

                                                                  onmouseout="_rating_efect_rev(2,1,\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\')"

                                                                  onclick = "rating_review_shop(\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\',2); rating_checked'.$criterion['id_gsnipreview_review_criterion'].'=true;"

                                                                  src="'.$base_dir_ssl.'modules/'.$this->name.'/views/img/'.$noactivestar.'"

                                                                  alt="" id="img_rat_rel'.$criterion['id_gsnipreview_review_criterion'].'_2" />



                                                            <img  onmouseover="_rating_efect_rev(3,0,\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\')"

                                                                  onmouseout="_rating_efect_rev(3,1,\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\')"

                                                                  onclick = "rating_review_shop(\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\',3); rating_checked'.$criterion['id_gsnipreview_review_criterion'].'=true;"

                                                                  src="'.$base_dir_ssl.'modules/'.$this->name.'/views/img/'.$noactivestar.'"

                                                                  alt=""  id="img_rat_rel'.$criterion['id_gsnipreview_review_criterion'].'_3" />

                                                            <img  onmouseover="_rating_efect_rev(4,0,\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\')"

                                                                  onmouseout="_rating_efect_rev(4,1,\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\')"

                                                                  onclick = "rating_review_shop(\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\',4); rating_checked'.$criterion['id_gsnipreview_review_criterion'].'=true;"

                                                                  src="'.$base_dir_ssl.'modules/'.$this->name.'/views/img/'.$noactivestar.'"

                                                                  alt=""  id="img_rat_rel'.$criterion['id_gsnipreview_review_criterion'].'_4" />

                                                            <img  onmouseover="_rating_efect_rev(5,0,\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\')"

                                                                  onmouseout="_rating_efect_rev(5,1,\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\')"

                                                                  onclick = "rating_review_shop(\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\',5); rating_checked'.$criterion['id_gsnipreview_review_criterion'].'=true;"

                                                                  src="'.$base_dir_ssl.'modules/'.$this->name.'/views/img/'.$noactivestar.'"

                                                                  alt=""  id="img_rat_rel'.$criterion['id_gsnipreview_review_criterion'].'_5" />

                                                        </span>';

                        if(Tools::strlen($criterion['description'])>0)

                            $_html .= '<div class="tip-criterion-description">'.$criterion['description'].'</div>';



                    $_html .= '</span>

                    <input type="hidden" id="rat_rel'.$criterion['id_gsnipreview_review_criterion'].'"

                            name="rat_rel'.$criterion['id_gsnipreview_review_criterion'].'" value="'.(isset($criterion['rating'])?$criterion['rating']:0).'"/>

                        <script type="text/javascript">

                            $(document).ready(function(){

                                rating_review_shop(\'rat_rel'.$criterion['id_gsnipreview_review_criterion'].'\','.(isset($criterion['rating'])?$criterion['rating']:0).');

                            });

                        </script>

                    <div class="clr"></div>

                    <div class="errorTxtAdd" id="error_rat_rel'.$criterion['id_gsnipreview_review_criterion'].'"></div>



                    </div>';

                }



            $_html .= '<br/>';

         } else {

            $_html .= '<div class="rating-stars-dynamic-item-admin">

            <span for="rat_rel" class="float-left rating-stars-dynamic-title-admin">'.$this->l('Total Rating').'<sup class="required">*</sup></span>



            <div class="rat rating-stars-dynamic-admin">

                                                        <span onmouseout="read_rating_review_shop(\'rat_rel\');">

                                                            <img  onmouseover="_rating_efect_rev(1,0,\'rat_rel\')" onmouseout="_rating_efect_rev(1,1,\'rat_rel\')"

                                                                  onclick = "rating_review_shop(\'rat_rel\',1); rating_checked=true; "

                                                                  src="'.$base_dir_ssl.'modules/'.$this->name.'/views/img/'.$noactivestar.'"

                                                                  alt=""

                                                                  id="img_rat_rel_1" />

                                                            <img  onmouseover="_rating_efect_rev(2,0,\'rat_rel\')" onmouseout="_rating_efect_rev(2,1,\'rat_rel\')" onclick = "rating_review_shop(\'rat_rel\',2); rating_checked=true;" src="'.$base_dir_ssl.'modules/'.$this->name.'/views/img/'.$noactivestar.'" alt=""  id="img_rat_rel_2" />

                                                            <img  onmouseover="_rating_efect_rev(3,0,\'rat_rel\')" onmouseout="_rating_efect_rev(3,1,\'rat_rel\')" onclick = "rating_review_shop(\'rat_rel\',3); rating_checked=true;" src="'.$base_dir_ssl.'modules/'.$this->name.'/views/img/'.$noactivestar.'" alt=""  id="img_rat_rel_3" />

                                                            <img  onmouseover="_rating_efect_rev(4,0,\'rat_rel\')" onmouseout="_rating_efect_rev(4,1,\'rat_rel\')" onclick = "rating_review_shop(\'rat_rel\',4); rating_checked=true;" src="'.$base_dir_ssl.'modules/'.$this->name.'/views/img/'.$noactivestar.'" alt=""  id="img_rat_rel_4" />

                                                            <img  onmouseover="_rating_efect_rev(5,0,\'rat_rel\')" onmouseout="_rating_efect_rev(5,1,\'rat_rel\')" onclick = "rating_review_shop(\'rat_rel\',5); rating_checked=true;" src="'.$base_dir_ssl.'modules/'.$this->name.'/views/img/'.$noactivestar.'" alt=""  id="img_rat_rel_5" />

                                                        </span>

            </div>

            <input type="hidden" id="rat_rel" name="rat_rel" value="'.$rating.'"/>



                <script type="text/javascript">

                    $(document).ready(function(){

                        rating_review_shop(\'rat_rel\','.$rating.');

                    });

                </script>

            <div class="clr"></div>

            <div class="errorTxtAdd" id="error_rat_rel"></div>

            </div>';

        }



			$_html .= '</div>';

            ## rating ##







            $_html .= '<label>'.$this->l('Date Add').':</label>';

    		$_html .= '<div style="padding:0 0 1em 210px;line-height:1.6em;">';

            //.$time_add.

            $_html .= '<input id="date_on"

                       type="text"

                       class="item_datepicker_add" name="time_add" value="'.$time_add.'" />

                <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>';



            $_html .= '<script type="text/javascript">

            $(\'document\').ready( function() {



                var dateObj = new Date();

                var hours = dateObj.getHours();

                var mins = dateObj.getMinutes();

                var secs = dateObj.getSeconds();

                if (hours < 10) { hours = "0" + hours; }

                if (mins < 10) { mins = "0" + mins; }

                if (secs < 10) { secs = "0" + secs; }

                var time = " "+hours+":"+mins+":"+secs;



                if ($(".item_datepicker_add").length > 0)

                $(".item_datepicker_add").datepicker({prevText: \'\',nextText: \'\',dateFormat: \'yy-mm-dd\'+time});



            });

        </script>';

            $_html .= '</div>';

    		

    		





            $_html .= '

                    <label>'.$this->l('Status').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="is_active" '.(($is_active ==1)?'checked="checked':'').'

							>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="is_active"

						   '.(($is_active == 0)?'checked="checked':'').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				</div>';

				

			$_html .= '<label>&nbsp;</label>

						<div class = "margin-form"  style="margin-top:20px">

						<input type="submit" name="cancel_item" value="'.$this->l('Cancel').'" 

                		   class="button"  />&nbsp;&nbsp;&nbsp;

						<input type="submit" name="'.$submit_action.'" value="'.(($id)?$this->l('Update'):$this->l('Save')).'"

                		   class="button"  />

                		  </div>';

			

    		$_html .= '</form>';

			

		} else {

            $_html .= '<h3 class="title-block-content" style="float:left"><i class="fa fa-reviews fa-lg"></i>&nbsp;'.$this->l('Moderate Reviews').'</h3>';



            $_html .= '<a href="'.$currentIndex.'&configure='.$this->name.'&token='.$token.'&addgsnipreview"

                            style="float:right;margin-top:10px;border: 1px solid rgb(240, 95, 93); display: block; font-size: 16px; color: rgb(240, 95, 93); text-align: center; font-weight: bold; text-decoration: underline; padding: 5px; margin-bottom: 10px;">

                         '.$this->l('Add new review').'

                         </a>';

            $_html .= '<div style="clear:both"></div>';

			

    		$_html .= '<table class="table" width=100% >';

    	

    		$_html .= '<tr>

    					<th style="width:20px;text-align:center;">'.$this->l('Id').'</th>';

            if(Configuration::get($this->name.'is_avatar'.$this->_prefix_review) == 1) {

                $_html .= '<th style="width:60px;text-align:center;">' . $this->l('Avatar') . '</th>';

            }

    			        $_html .= '<th style="width:60px;text-align:center;">'.$this->l('Customer Name').'</th>';

                $_html .=  '<th style="width:60px;text-align:center;">'.$this->l('Title').'</th>';



    		$_html .= '<th style="width:60px;text-align:center;">'.$this->l('Product').'</th>';

            $_html .= '<th style="width:70px;text-align:center;">'.$this->l('Total rating').'</th>';

            $_html .= '<th style="width:60px;text-align:center;">'.$this->l('Helpful votes').'</th>';

    		$_html .= '<th style="width:60px;text-align:center;">'.$this->l('Date add').'</th>';

            $_html .= '<th style="width:60px;text-align:center;">'.$this->l('Language').'</th>';

            if($this->_is15){

                $_html .= '<th style="width:60px;text-align:center;">'.$this->l('Shop').'</th>';

            }





            $_html .= '<th style="width:60px;text-align:center;">'.$this->l('Abuse flag').'</th>';

            $_html .= '<th style="width:60px;text-align:center;">'.$this->l('Suggest user change the review?').'</th>';

            $_html .= '<th style="width:40px;text-align:center;">'.$this->l('Status').'</th>';

    		

    		$_html .= '<th style="width:40px;text-align:center;">'.$this->l('Actions').'</th>



    			        

    			   </tr>';

    		

    		$start = (int)Tools::getValue("page");

    		

    		$data_in = $obj_reviewshelp->getAllReviewsAdmin(array('start' => $start));

    		

    		

    		$paging = $obj_reviewshelp->paging(array('admin' => 1,

												  'start' => $start,

												  'count'=> $data_in['count_all_reviews'],

												  'step' => Configuration::get($this->name.'adminrevperpage'),

												  'currentIndex'=>$currentIndex,

												  'token' => '&configure='.$this->name.'&token='.$token,

    		  									  'page' => $this->l('Page')	  

    		  									     )

    		  									);

    		

    		$data = $data_in['reviews'];

	    	if(sizeof($data)>0){

	    		

	    		for($i=0;$i<sizeof($data);$i++){

	    		$j++;

	    		

	    		$id = $data[$i]['id'];

	    		$customer_name = $data[$i]['customer_name'];

				$date = date('Y-m-d',strtotime($data[$i]['time_add']));

				$title = $data[$i]['title_review'];

				$product_name = $data[$i]['product_name'];

				$active = $data[$i]['is_active'];

				$rating = $data[$i]['rating'];

				$product_link = $data[$i]['product_link'];

                $review_url = $data[$i]['review_url'];

                $helpful = $data[$i]['helpful_votes'];

                $is_abuse = $data[$i]['is_abuse'];

                $is_changed = $data[$i]['is_changed'];



                $lang = $data[$i]['id_lang'];

                $data_lng = Language::getLanguage($lang);

                $lang_for_review = $data_lng['iso_code'];



                if($this->_is15){

                        $id_shop = $data[$i]['id_shop'];



                        $shops = Shop::getShops();

                        $name_shop = '';

                        foreach($shops as $_shop){

                            $id_shop_lists = $_shop['id_shop'];

                            if($id_shop == $id_shop_lists)

                                $name_shop = $_shop['name'];

                        }

                }



                $id_customer = $data[$i]['id_customer'];

                $data_seo_url = $obj_reviewshelp->getSEOURLs(array('id_lang'=>$lang));

                $user_url = $data_seo_url['user_url'];



                $avatar_thumb = isset($data[$i]['avatar_thumb'])?$data[$i]['avatar_thumb']:'';

                $avatar = isset($data[$i]['avatar'])?$data[$i]['avatar']:'';







				$_html .= '<tr>

							<td style="width:20px;text-align:center">'.$id.'</td>';



                    if(Configuration::get($this->name.'is_avatar'.$this->_prefix_review) == 1) {

                        $_html .= '<td style = "width:60px;text-align:center">



                        <span class="avatar-list">';



                        if($id_customer){



                            /* for registered customers */

                            if(Tools::strlen($avatar_thumb)>0){

                                $_html .= '<img src="'.$base_dir.$this->path_img_cloud.'avatar/'.$avatar_thumb.'" />';

                            }else{

                                $_html .= '<img src = "../modules/' . $this->name . '/views/img/avatar_m.gif" />';

                            }

                            /* for registered customers */

                        } else{

                            /* for guests */

                            if(Tools::strlen($avatar)>0) {

                                $_html .= '<img src="'.$avatar.'" />';

                            }else{

                                $_html .= '<img src = "../modules/' . $this->name . '/views/img/avatar_m.gif" />';

                            }

                            /* for guests */

                        }





                        $_html .= '</span>

                        </td>';

                    }



                    if(Configuration::get($this->name.'is_uprof') && $id_customer){

                        $_html .= '<td style = "color:black;width:60px;text-align:center">

                                        <a href="'.$user_url.$id_customer.'"

                                            target="_blank" style="text-decoration:underline">' . $customer_name . '</a>

                                    </td>';

                    } else {

                        $_html .= '<td style="width:60px;text-align:center">' . $customer_name . '</td>';

                    }





                        $_html .=   '<td style="width:60px;text-align:center">

                                    <a href="'.$review_url.'" target="_blank" style="font-size: 12px; text-decoration: underline;">

		    					'.(isset($title)?$title:'').'

		    					</a></td>';

		    	$_html .= '<td style="width:60px;text-align:center">

		    					<a href="'.$product_link.'" target="_blank" style="font-size: 12px; text-decoration: underline;">

		    					'.$product_name.'

		    					</a>

		    				</td>';







                        $_html .=  '<td style="width:70px;text-align:center">';



                        if($rating == 0){

                            $_html .= $this->l('not rated');

                        } else {



                            switch(Configuration::get($this->name.'stylestars')){

                                case 'style1':

                                    $activestar = 'star-active-yellow.png';

                                    $noactivestar = 'star-noactive-yellow.png';

                                    break;

                                case 'style2':

                                    $activestar = 'star-active-green.png';

                                    $noactivestar = 'star-noactive-green.png';

                                    break;

                                case 'style3':

                                    $activestar =  'star-active-blue.png';

                                    $noactivestar = 'star-noactive-blue.png';

                                    break;

                                default:

                                    $activestar =  'star-active-yellow.png';

                                    $noactivestar =  'star-noactive-yellow.png';

                                    break;

                            }



                            for($r=0;$r<5;$r++){

                                if($r < $rating)

                                    $_html .=	'<img src="../modules/'.$this->name.'/views/img/'.$activestar.'"/>';

                                else

                                    $_html .=	'<img src="../modules/'.$this->name.'/views/img/'.$noactivestar.'" />';

                            }



                        }



                        $_html .= '</td>';







                $_html .= '<td style="width:60px;text-align:center"> '.$helpful.'</td>';

		    	$_html .= '<td style="width:60px;text-align:center"> '.$date.'</td>';



                    $_html .= '<td style="width:60px;text-align:center"> '.$lang_for_review.'</td>';





                if($this->_is15){

                        $_html .= '<td style = "color:black;text-align:center">'.$name_shop.'</td>';

                }



                if($is_abuse == 1){

                $_html .= '<td style="width:60px;text-align:center" id="abuseitem'.$id.'">

                            <a href="javascript:void(0)" onclick="gsnipreview_list('.$id.',\'abuse\',0,\''.$token_popup.'\');" style="text-decoration:none">

                               <img src="../modules/'.$this->name.'/views/img/warn2.png" alt="'.$this->l('Someone send abuse. Click here to view abuse and set review is NOT Abusive').'" title="'.$this->l('Someone send abuse. Click here to view abuse and set review is NOT Abusive').'" />

                            </a>

                        </td>';

                } else {

                    $_html .= '<td style="width:60px;text-align:center">

                               <img src="../modules/'.$this->name.'/views/img/ok.gif" alt="'.$this->l('Review is NOT Abusive').'" title="'.$this->l('Review is NOT Abusive').'" />

                        </td>';



                }



                    $data_changed = array(

                        0 => array('src' => '../modules/'.$this->name.'/views/img/edit.gif', 'alt' => $this->l('Click here to send suggest user change the review'),'value'=>0),

                        1 => array('src' => '../modules/'.$this->name.'/views/img/time.gif', 'alt' => $this->l('The changed customer review is pending modification'),'value'=>1),

                        2 => array('src' => '../modules/'.$this->name.'/views/img/edit_ok.gif', 'alt' => $this->l('The customer has changed his review'),'value'=>2),

                    );

               $_html .= '<td style="width:60px;text-align:center" id="changeditem'.$id.'">



                       <a href="javascript:void(0)" onclick="gsnipreview_list('.$id.',\'changed\','.$is_changed.',\''.$token_popup.'\');" style="text-decoration:none">

                            <img src="'.$data_changed[$is_changed]['src'].'" alt="'.$data_changed[$is_changed]['alt'].'" title="'.$data_changed[$is_changed]['alt'].'" />

                        </a>



                     </td>';





                $_html .= '<td style="text-align:center" id="activeitem'.$id.'">

                                    <a href="javascript:void(0)" onclick="gsnipreview_list('.$id.',\'active\','.$active.',\'\');" style="text-decoration:none"

                                    title="'.$this->l('Click here to activate or deactivate review on your site').'">

                                        <img src="../modules/gsnipreview/views/img/'.($active?'ok.gif':'no_ok.gif').'"  />

                                    </a>

                              ';

				$_html .= '</td>';

				



				

					$_html .= '

			    				<td style="width:7%;text-align:center">';

			    				$_html .= '

								 		   <a href="'.$currentIndex.'&configure='.$this->name.'&token='.$token.'&edit_item&id='.(int)($id).'&page='.$start.'" title="'.$this->l('Edit').'"><img src="'._PS_ADMIN_IMG_.'edit.gif" alt="" /></a>

				 						   <a href="'.$currentIndex.'&configure='.$this->name.'&token='.$token.'&submit_item=delete&id='.(int)($id).'&page='.$start.'" title="'.$this->l('Delete').'"  onclick = "javascript:return confirm(\''.$this->l('Are you sure you want to remove this item?').'\');"><img src="'._PS_ADMIN_IMG_.'delete.gif" alt="" /></a>'; 

				

			    				$_html .= '</td>

			    				

		    			   </tr>

		    			   ';

	    		}

	    		

	    	} else {

    		$_html .= '<tr>

    					<td colspan=11 style="border-bottom:none;text-align:center;padding:10px">'.$this->l('No reviews for moderate.').'</td>

    				   </tr>';

    		}

		}

		

		$_html .= '</table>';

		

		if($j!=0){

    	$_html .= '<div style="margin:5px">';

    	$_html .= $paging;

    	$_html .= '</div>';

    	}

		

    	

		$_html .= '</div>';

    	

    	return $_html;

    }









    private function _customerreminderOLD()

    {

        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-bell-o fa-lg"></i>&nbsp;' . $this->l('Customer Reminder settings') . '</h3>';





        $_html .= '<form method="post" action="' . Tools::safeOutput($_SERVER['REQUEST_URI']) . '">';





        $_html .= '<label>' . $this->l('Send a review reminder by email to customers') . ':</label>';



        $_html .= '<script type="text/javascript">

			    	function enableOrDisableReminder(id)

						{

						if(id==0){

							$("#block-reminder-settings").hide(200);

						} else {

							$("#block-reminder-settings").show(200);

						}



						}

					</script>';





        $_html .= '<div class="margin-form">';

        $_html .= '

					<input type="radio" value="1" id="text_list_on" name="reminder" onclick="enableOrDisableReminder(1)"

							' . (Tools::getValue('reminder', Configuration::get($this->name . 'reminder')) ? 'checked="checked" ' : '') . '>

					<label for="dhtml_on" class="t">

						<img alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="reminder" onclick="enableOrDisableReminder(0)"

						   ' . (!Tools::getValue('reminder', Configuration::get($this->name . 'reminder')) ? 'checked="checked" ' : '') . '>

					<label for="dhtml_off" class="t">

						<img alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">' .

            $this->l('If activated, when a customer purchases a product on your shop, an e-mail will be sent to him after X days (specify below after selecting "yes" here) to invite him to rate the product.') . '

                    <br/><br/>

                    <b>' . $this->l('IMPORTANT NOTE') . '</b>: ' . $this->l('This requires to set a CRON task on your server. ') . '

                    <a style="text-decoration:underline;font-weight:bold;color:red" onclick="tabs_custom(104)" href="javascript:void(0)">' . $this->l('CRON HELP PRODUCT REVIEWS') . '</a>

                    <br/><br/>

                    <b>' . $this->l('Your CRON URL to call') . '</b>:&nbsp;<a href="'.$this->getURLMultiShop().'modules/' . $this->name . '/cron.php?token=' . $this->getokencron() . '" style="text-decoration:underline;font-weight:bold">'.$this->getURLMultiShop().'modules/' . $this->name . '/cron.php?token=' . $this->getokencron() . '</a>

					</p>



				';

        $_html .= '</div>';



        $_html .= '<div id="block-reminder-settings" ' . (Configuration::get($this->name . 'reminder') == 1 ? 'style="display:block"' : 'style="display:none"') . '>';







        $_html .= '<label>' . $this->l('Delay between each email in seconds') . ':</label>';



        $_html .= '<div class="margin-form">';

        $_html .= '

					<input type="text" name="crondelay'.$this->_prefix_review.'" size="10"

			       	   value="'.Configuration::get($this->name.'crondelay'.$this->_prefix_review).'"/>&nbsp;(' . $this->l('sec') . ')

				 <p class="clear">'.$this->l('The delay is intended in order to your server is not blocked the email function').'</p>';

        $_html .= '</div>';





        $_html .= '<label>' . $this->l('Number of emails for each cron call') . ':</label>';



        $_html .= '<div class="margin-form">';

        $_html .= '

					<input type="text" name="cronnpost'.$this->_prefix_review.'" size="10"

			       	   value="'.Configuration::get($this->name.'cronnpost'.$this->_prefix_review).'">



			       	   <p class="clear">'.$this->l('This will reduce the load on your server. The more powerful your server - the more emails you can do for each CRON call! ').'</p>';

        $_html .= '</div>';











        $_html .= '<label>' . $this->l('Delay for sending reminder by email') . ':</label>';



        $_html .= '<div class="margin-form">';

        $_html .= '

					<input type="text" name="delay"

			               value="' . Tools::getValue('delay', Configuration::get($this->name . 'delay')) . '"

			               >&nbsp;(' . $this->l('days') . ')

				';

        $_html .= '<p class="clear">' . $this->l('We recommend you enter at least 7 days here to have enough time to process the order and for the customer to receive it.') . '</p>';

        $_html .= '</div>';











        $_html .= '</div>';





        $_html .= '<label>' . $this->l('Import your old orders') . ':</label>';



        $_html .= '<div class="margin-form">';





        $_html .= '<div class="input-group col-lg-3" style="float:left;margin-right:10px" id="importoldorders_first">

                <span style="font-size:12px">' . $this->l('start date') . '</span>&nbsp;';

        $_html .= '<input type="text" name="start_date" class="item_datepicker datepicker">';





        $_html .= '</div>

            <div class="input-group col-lg-3" style="float:left">

                <span style="font-size:12px">' . $this->l('end date') . '</span>&nbsp;';

        $_html .= '<input type="text" value="' . date('Y-m-d H:i:s') . '" name="end_date" disabled="disabled" data-hex="true" >';





        $_html .= '</div>

            <input type="button" value="' . $this->l('Import old orders') . '" onclick="importoldorders();"

                   class="button" style="float:left;margin-left:10px"/>

            <div style="clear:both"></div>';







        $_html .= '<script type="text/javascript">

            $(\'document\').ready( function() {



                var dateObj = new Date();

                var hours = dateObj.getHours();

                var mins = dateObj.getMinutes();

                var secs = dateObj.getSeconds();

                if (hours < 10) { hours = "0" + hours; }

                if (mins < 10) { mins = "0" + mins; }

                if (secs < 10) { secs = "0" + secs; }

                var time = " "+hours+":"+mins+":"+secs;



                if ($(".item_datepicker").length > 0){

                    $(".item_datepicker").datepicker({prevText: \'\',nextText: \'\',dateFormat: \'yy-mm-dd\'+time});

                    }



            });







            function importoldorders(){



                $(\'#importoldorders_first\').css(\'opacity\',\'0.5\');

                var start_date =  $(\'.item_datepicker\').val();





                $.post(\'../modules/gsnipreview/reviews_admin.php\',

                        {   action:\'importoldorders\',

                            start_date: start_date

                        },

                        function (data) {

                            if (data.status == \'success\') {



                                $(\'#importoldorders_first\').css(\'opacity\',\'1\');

                                var data = data.params.content;

                                //alert(data);



                                $(\'.alert-danger\').remove();

                                $(\'.alert-success\').remove();

                                $(\'#importoldorders_first\').before(data);





                            } else {

                                $(\'#importoldorders_first\').css(\'opacity\',\'1\');

                                alert(data.message);



                            }

                        }, \'json\');

            }

        </script>';

        $_html .= '<p class="clear" style="font-size: 12px;margin-top:10px; font-weight:bold">



                    ' . $this->l('Please select a date. All orders placed between that start date and end date (today) will be imported.') . '

                </p>';

        $_html .= '</div>';





        $_html .= '<label>' . $this->l('Send emails only for the orders with the current selected status') . ':</label>';



        $_html .= '<div class="margin-form">';



        include_once(dirname(__FILE__) . '/classes/featureshelp.class.php');

        $obj_featureshelp = new featureshelp();

        $cookie = $this->context->cookie;

        $id_lang = (int)($cookie->id_lang);

        $value = $obj_featureshelp->getOrderStatuses(array('id_lang' => $id_lang));

        $orderstatuses = explode(",", Configuration::get($this->name . 'orderstatuses'));





        $_html .= '<div class="panel">



                <table class="table">

                    <thead>

                    <tr>



                        <th>&nbsp;</th>

                        <th><b>' . $this->l('Order status') . '</b></th>

                    </tr>

                    </thead>

                    <tbody>';



                    foreach($value as $cms_item){

                        $_html .= '<tr class="alt_row">

                            <td>



                                    <div class="input-group">

                                        <input type="checkbox" name="orderstatuses[]" ';

                                            foreach ($orderstatuses as $id_status){

                                                if ($id_status == $cms_item['id_order_state'])

                                                    $_html .= 'checked="checked" ';

                                            }



                                               $_html .= 'value="'.$cms_item['id_order_state'].'" />

                                    </div>

                            </td>

                            <td>





                                    <span style="background-color:'.$cms_item['color'].';color:white;padding:4px;border-radius:5px;line-height:25px;margin:3px 0">

                                        '.$cms_item['name'].'

                                    </span>

                            </td>

                        </tr>';

                    }





                    $_html .= '</tbody>

                </table>

            </div>';





        $_html .= '<p class="clear">'.$this->l('This feature prevents customers to leave a review for orders that they haven\'t received yet. You must choose at least one status.').'</p>';

        $_html .= '</div>';









        $_html .= '<label>' . $this->l('Send a review reminder by email to customer when customer already write review in shop?') . ':</label>';



        $_html .= '<div class="margin-form">';

        $_html .= '

					<input type="radio" value="1" id="text_list_on" name="remrevsec'.$this->_prefix_review.'"

							' . (Configuration::get($this->name . 'remrevsec'.$this->_prefix_review) ? 'checked="checked" ' : '') . '>

					<label for="dhtml_on" class="t">

						<img alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="remrevsec'.$this->_prefix_review.'"

						   ' . (!Configuration::get($this->name . 'remrevsec'.$this->_prefix_review) ? 'checked="checked" ' : '') . '>

					<label for="dhtml_off" class="t">

						<img alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" src="../img/admin/disabled.gif">

					</label>



					<p class="clear"></p>		';

        $_html .= '</div>';







        $_html .= '<label>' . $this->l('Send a review reminder by email to customers a second time?') . ':</label>';



        $_html .= '<script type="text/javascript">

			    	function enableOrDisableReminderSecond'.$this->_prefix_review.'(id)

						{

						if(id==0){

							$("#block-remindersec'.$this->_prefix_review.'-settings").hide(200);

						} else {

							$("#block-remindersec'.$this->_prefix_review.'-settings").show(200);

						}



						}

					</script>';







        $_html .= '<div class="margin-form">';

        $_html .= '

					<input type="radio" value="1" id="text_list_on" name="remindersec'.$this->_prefix_review.'" onclick="enableOrDisableReminderSecond'.$this->_prefix_review.'(1)"

							' . (Configuration::get($this->name . 'remindersec'.$this->_prefix_review) ? 'checked="checked" ' : '') . '>

					<label for="dhtml_on" class="t">

						<img alt="' . $this->l('Enabled') . '" title="' . $this->l('Enabled') . '" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="remindersec'.$this->_prefix_review.'" onclick="enableOrDisableReminderSecond'.$this->_prefix_review.'(0)"

						   ' . (!Configuration::get($this->name . 'remindersec'.$this->_prefix_review) ? 'checked="checked" ' : '') . '>

					<label for="dhtml_off" class="t">

						<img alt="' . $this->l('Disabled') . '" title="' . $this->l('Disabled') . '" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">' .

            $this->l('This feature allows you to send a second time the opinion request emails that have already been sent. ').'</p>



				';

        $_html .= '</div>';





        $_html .= '<div id="block-remindersec'.$this->_prefix_review.'-settings" ' . (Configuration::get($this->name . 'remindersec'.$this->_prefix_review) == 1 ? 'style="display:block"' : 'style="display:none"') . '>';



        $_html .= '<label>' . $this->l('Delay for sending reminder by email') . ':</label>';



        $_html .= '<div class="margin-form">';

        $_html .= '

					<input type="text" name="delaysec'.$this->_prefix_review.'"

			               value="' . Configuration::get($this->name . 'delaysec'.$this->_prefix_review) . '"

			               >&nbsp;(' . $this->l('days') . ')

				';

        $_html .= '</div>';

        $_html .= '</div>';







        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">

					<input type="submit" name="customerremindersettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';







        return $_html;

    }







    private function _emailSubjectsFields($data){



        $data_div_lang_name = $data['div_lang_name'];

        $data_label = $data['label'];

        $data_email_template_name = $data['email_template_name'];



        $_html  ='';

        $divLangName = $data_div_lang_name;





        $_html .= '<label>'.$data_label.':</label>';



        $_html .= '<div class="margin-form">';



        $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));

        $languages = Language::getLanguages(false);



        foreach ($languages as $language){

            $id_lng = (int)$language['id_lang'];

            $rssname = Configuration::get($this->name.$data_div_lang_name.'_'.$id_lng);





            $_html .= '	<div id="'.$data_div_lang_name.'_'.$language['id_lang'].'"

							 style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;"

							 >



						<input type="text" style="width:400px"

								  id="'.$data_div_lang_name.'_'.$language['id_lang'].'"

								  name="'.$data_div_lang_name.'_'.$language['id_lang'].'"

								  value="'.htmlentities(Tools::stripslashes($rssname), ENT_COMPAT, 'UTF-8').'"/>

						</div>';

        }

        $_html .= '';

        ob_start();

        $this->displayFlags($languages, $defaultLanguage, $divLangName, $data_div_lang_name);

        $displayflags = ob_get_clean();

        $_html .= $displayflags;

        $_html .= '<div class="clear"></div>';



        $_html .= '<p class="clear">'.$this->_responseadminemailsDesc(array('name'=>$data_email_template_name)).'</p>';



        $_html .= '</div>';



        return $_html;

    }



    private function _responseadminemailsOLD(){

        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-envelope-o fa-lg"></i>&nbsp;'.$this->l('Emails subjects settings').'</h3>';





        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';





        $_html .= $this->_emailSubjectsFields(

            array(

                'div_lang_name'=>'emailreminder',

                'label'=>$this->l('Email reminder subject'),

                'email_template_name'=>'customer-reminderserg',

            )

        );



        $_html .= $this->_emailSubjectsFields(

            array(

                'div_lang_name'=>'reminderok'.$this->_prefix_review,

                'label'=>$this->l('Admin confirmation subject, when emails requests on the reviews was successfully sent'),

                'email_template_name'=>'customer-reminder-admin-'.$this->_prefix_review,

            )

        );



        $_html .= $this->_emailSubjectsFields(

            array(

                'div_lang_name'=>'thankyou'.$this->_prefix_review,

                'label'=>$this->l('Thank you for your review subject'),

                'email_template_name'=>'review-thank-you-'.$this->_prefix_review,

            )

        );



        $_html .= $this->_emailSubjectsFields(

            array(

                'div_lang_name'=>'newrev'.$this->_prefix_review,

                'label'=>$this->l('New Review subject'),

                'email_template_name'=>'reviewserg',

            )

        );





        $_html .= $this->_emailSubjectsFields(

            array(

                'div_lang_name'=>'subresem',

                'label'=>$this->l('Suggest to change review subject'),

                'email_template_name'=>'reviewserg-suggest-to-change',

            )

        );









        $divLangName = "textresem";



        $_html .= '<label>'.$this->l('Default content of the email').'</label>';





        $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));

        $languages = Language::getLanguages(false);



        $_html .= '<div class="margin-form">';



        foreach ($languages as $language){

            $id_lng = (int)$language['id_lang'];

            $textresem = Configuration::get($this->name.'textresem'.'_'.$id_lng);



            $_html .= '	<div id="textresem_'.$language['id_lang'].'"

							 style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;"

							 >

						<textarea cols="60" rows="10"

			                	  id="textresem_'.$language['id_lang'].'"

								  name="textresem_'.$language['id_lang'].'"

								  >'.htmlentities(Tools::stripslashes($textresem), ENT_COMPAT, 'UTF-8').'</textarea>

						</div>';

        }

        ob_start();

        $this->displayFlags($languages, $defaultLanguage, $divLangName, 'textresem');

        $displayflags = ob_get_clean();

        $_html .= $displayflags;

        $_html .= '<div style="clear:both"></div>';



        $_html .= '<p class="clear hint" style="display: block; font-size: 12px; width: 95%;position:relative">



                    '.$this->l('Templates for sending emails when people leave bad reviews and you wish to contact the user and try to convince a user to change his review / rating').'

                </p>';





        $_html .=  '</div>';





        $_html .= $this->_emailSubjectsFields(

            array(

                'div_lang_name'=>'modrev'.$this->_prefix_review,

                'label'=>$this->l('One of your customers has modified own product review subject'),

                'email_template_name'=>'reviewserg-customer-change-review',

            )

        );





        $_html .= $this->_emailSubjectsFields(

            array(

                'div_lang_name'=>'subpubem',

                'label'=>$this->l('Notification email when a review is published subject'),

                'email_template_name'=>'reviewserg-publish',

            )

        );





        $_html .= $this->_emailSubjectsFields(

            array(

                'div_lang_name'=>'abuserev'.$this->_prefix_review,

                'label'=>$this->l('Someone send abuse for review subject'),

                'email_template_name'=>'abuseserg',

            )

        );



        $_html .= $this->_emailSubjectsFields(

            array(

                'div_lang_name'=>'revvouc'.$this->_prefix_review,

                'label'=>$this->l('You submit a review and get voucher for discount subject'),

                'email_template_name'=>'voucherserg',

            )

        );



        $_html .= $this->_emailSubjectsFields(

            array(

                'div_lang_name'=>'facvouc'.$this->_prefix_review,

                'label'=>$this->l('You share review on Facebook and get voucher for discount subject'),

                'email_template_name'=>'voucherserg',

            )

        );



        $_html .= $this->_emailSubjectsFields(

            array(

                'div_lang_name'=>'sugvouc'.$this->_prefix_review,

                'label'=>$this->l('Share your review on Facebook and get voucher for discount subject'),

                'email_template_name'=>'voucherserg-suggest',

            )

        );





        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">

					<input type="submit" name="responseadminemailssettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';







        return $_html;

    }







    private function _reviewsemailsOLD(){

        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-envelope-o fa-lg"></i>&nbsp;'.$this->l('Reviews emails settings').'</h3>';





        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';





        // Email administrator

        $_html .= '<label>'.$this->l('Email administrator').':</label>

				<div class="margin-form">

					<input type="text" name="mail"  size="50"

			               value="'.Tools::getValue('mail', Configuration::get($this->name.'mail')).'"

			               >



				</div>';

        // Email administrator

        $_html .= '<p class="clear"></p>';

        // Email notifications

        $_html .= '<label>'.$this->l('Email notifications').':</label>

				<div class="margin-form">

					<input type = "checkbox" name = "noti" id = "noti" value ="1" '.((Tools::getValue($this->name.'noti', Configuration::get($this->name.'noti')) ==1)?'checked':'').'/>

						<p class="clear">'.$this->l('Email notifications when customer add review').'.</p>

				</div>';

        // Email notifications

        $_html .= '<p class="clear"></p>';





        $data_img_sizes = array();



        $available_types = ImageType::getImagesTypes('products');



        foreach ($available_types as $type){



            $id = $type['name'];

            $name = $type['name'].' ('.$type['width'].' x '.$type['height'].')';



            $data_item_size = array(

                'id' => $id,

                'name' => $name,

            );



            array_push($data_img_sizes,$data_item_size);





        }





        $_html .= '<label>'.$this->l('Image size for products').':</label>

				<div class="margin-form">

					<select class="select" name="img_size_em"

							id="img_size_em">';

        foreach($data_img_sizes as $image) {

            $_html .= '<option ' . (Tools::getValue('img_size_em', Configuration::get($this->name . 'img_size_em')) == $image['id'] ? 'selected="selected" ' : '') . ' value="'.$image['id'].'">' . $image['name'] . '</option>

						';

        }

			$_html .= '</select>

						<p class="clear">'.$this->l('The emails will contain a photo of each product.').'</p>

				</div>';









        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">

					<input type="submit" name="reviewsemailssettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';





        return $_html;

    }



    private function _reviewsSettings(){

        $_html = '';

        $_html .= '<ul class="leftMenuIN">

			<li><a href="javascript:void(0)" onclick="tabs_custom_in(31)" id="tab-menuin-31" ><i class="fa fa-cogs fa-lg"></i>&nbsp;'.$this->l('Global Settings').'</a></li>

			<li><a href="javascript:void(0)" onclick="tabs_custom_in(32)" id="tab-menuin-32" ><i class="fa fa-book fa-lg"></i>&nbsp;'.$this->l('Product page').'</a></li>

			<li><a href="javascript:void(0)" onclick="tabs_custom_in(33)" id="tab-menuin-33" ><i class="fa fa-reviews fa-lg"></i>&nbsp;'.$this->l('Reviews management').'</a></li>



			<li><a href="javascript:void(0)" onclick="tabs_custom_in(34)" id="tab-menuin-34" ><i class="fa fa-reviews fa-lg"></i>&nbsp;'.$this->l('Review Criteria').'</a></li>

			<li><a href="javascript:void(0)" onclick="tabs_custom_in(35)" id="tab-menuin-35" ><i class="fa fa-users fa-lg"></i>&nbsp;'.$this->l('Customer account reviews page').'</a></li>

			<li><a href="javascript:void(0)" onclick="tabs_custom_in(36)" id="tab-menuin-36" ><i class="fa fa-list-alt fa-lg"></i>&nbsp;'.$this->l('Last Reviews Block').'</a></li>

            <li><a href="javascript:void(0)" onclick="tabs_custom_in(37)" id="tab-menuin-37" ><i class="fa fa-bars fa-lg"></i>&nbsp;'.$this->l('Stars in Category and Search pages').'</a></li>

            <li><a href="javascript:void(0)" onclick="tabs_custom_in(38)" id="tab-menuin-38" ><i class="fa fa-rss fa-lg"></i>&nbsp;'.$this->l('Reviews RSS Feed').'</a></li>

            <li><a href="javascript:void(0)" onclick="tabs_custom_in(39)" id="tab-menuin-39" ><i class="fa fa-comments-o fa-lg"></i>&nbsp;'.$this->l('Import Product Comments').'</a></li>

            <li><a href="javascript:void(0)" onclick="tabs_custom_in(43)" id="tab-menuin-43" ><i class="fa fa-snippets fa-lg"></i>&nbsp;'.$this->l('Google Product Review Feeds for Google Shopping').'</a></li>

            <li>&nbsp;</li>

            <li><a href="javascript:void(0)" onclick="tabs_custom_in(50)" id="tab-menuin-50"><i class="fa fa-reviews fa-lg"></i>&nbsp;'.$this->l('Voucher settings, when a user add review').'</a></li>

			<li><a href="javascript:void(0)" onclick="tabs_custom_in(55)" id="tab-menuin-55" ><i class="fa fa-facebook fa-lg"></i>&nbsp;'.$this->l('Voucher settings, when a user share review on the Facebook').'</a></li>



            <li>&nbsp;</li>

            <li><a href="javascript:void(0)" onclick="tabs_custom_in(40)" id="tab-menuin-40"><i class="fa fa-envelope-o fa-lg"></i>&nbsp;'.$this->l('Reviews emails settings').'</a></li>

			<li><a href="javascript:void(0)" onclick="tabs_custom_in(41)" id="tab-menuin-41" ><i class="fa fa-envelope-o fa-lg"></i>&nbsp;'.$this->l('Emails subjects settings').'</a></li>

			<li><a href="javascript:void(0)" onclick="tabs_custom_in(42)" id="tab-menuin-42" ><i class="fa fa-bell-o fa-lg"></i>&nbsp;'.$this->l('Customer Reminder settings').'</a></li>

			<li><a href="javascript:void(0)" onclick="tabs_custom_in(44)" id="tab-menuin-44" ><i class="fa fa-bar-chart fa-lg"></i>&nbsp;'.$this->l('Customer Reminder Statistics').'</a></li>

			<li><a href="javascript:void(0)" onclick="tabs_custom_in(101)" id="tab-menuin-101" ><i class="fa fa-tasks fa-lg"></i>&nbsp;'.$this->l('CRON HELP PRODUCT REVIEWS').'</a></li>



			<li>&nbsp;</li>

			<li><a href="javascript:void(0)" onclick="tabs_custom_in(45)" id="tab-menuin-45" ><i class="fa fa-table fa-lg"></i>&nbsp;'.$this->l('CSV import/export product reviews settings').'</a></li>



			</ul>

		';











        $_html .= '<div class="items-content">';

        $_html .= '<div class="menu-content" id="tabsin-31" style="display:block">'.$this->_globalOLD().'</div>';

        $_html .= '<div class="menu-content" id="tabsin-32">'.$this->_productpageOLD().'</div>';

        $_html .= '<div class="menu-content" id="tabsin-33">'.$this->_reviewsmanagementOLD().'</div>';



        $_html .= '<div class="menu-content" id="tabsin-34">'.$this->_reviewcriteriaOLD().'</div>';

        $_html .= '<div class="menu-content" id="tabsin-35">'.$this->_customeraccountreviewspageOLD().'</div>';

        $_html .= '<div class="menu-content" id="tabsin-36">'.$this->_lastreviewsblockOLD().'</div>';

        $_html .= '<div class="menu-content" id="tabsin-37">'.$this->_starslistandsearchOLD().'</div>';

        $_html .= '<div class="menu-content" id="tabsin-38">'.$this->_rssfeedOLD().'</div>';

        $_html .= '<div class="menu-content" id="tabsin-39">'.$this->_importcomments().'</div>';

        $_html .= '<div class="menu-content" id="tabsin-43">'.$this->_groductFeed().'</div>';



        $_html .= '<div class="menu-content" id="tabsin-50">'.$this->_reviewsvoucheraddreviewtab().'</div>';

        $_html .= '<div class="menu-content" id="tabsin-55">'.$this->_reviewsvouchersharereviewtab().'</div>';



        $_html .= '<div class="menu-content" id="tabsin-40">'.$this->_reviewsemailsOLD().'</div>';

        $_html .= '<div class="menu-content" id="tabsin-41">'.$this->_responseadminemailsOLD().'</div>';

        $_html .= '<div class="menu-content" id="tabsin-42">'.$this->_customerreminderOLD().'</div>';

        $_html .= '<div class="menu-content" id="tabsin-44">'.$this->_customerreminderstat().'</div>';

        $_html .= '<div class="menu-content" id="tabsin-101">'.$this->_cronhelp(array('url'=>'cron')).'</div>';





        $_html .= '<div class="menu-content" id="tabsin-45">'.$this->_csvImportExportProductReviews().'</div>';





        $_html .= '<div style="clear:both"></div>';

        $_html .= '</div>';



        $_html .= '<div style="clear:both"></div>';



        return $_html;

    }



    private function _rssfeedOLD(){

        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-rss fa-lg"></i>&nbsp;'.$this->l('Reviews RSS Feed').'</h3>';





        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';







        $_html .= '<label>'.$this->l('Enable or Disable RSS Feed').':</label>';



        $_html .= '<script type="text/javascript">

			    	function enableOrDisableRSS(id)

						{

						if(id==0){

							$("#block-rss-settings").hide(200);

						} else {

							$("#block-rss-settings").show(200);

						}



						}

					</script>';





        $_html .= '<div class="margin-form">';

        $_html .=  '

					<input type="radio" value="1" id="text_list_on" name="rsson" onclick="enableOrDisableRSS(1)"

							'.(Tools::getValue('rsson', Configuration::get($this->name.'rsson')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="rsson" onclick="enableOrDisableRSS(0)"

						   '.(!Tools::getValue('rsson', Configuration::get($this->name.'rsson')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>

				';

        $_html .= '</div>';

        $_html .= '<p class="clear"></p>';

        $_html .= '<div id="block-rss-settings" '.(Configuration::get($this->name.'rsson')==1?'style="display:block"':'style="display:none"').'>';



        $divLangName = "rssname¤srssdesc";



        // Title of your RSS Feed



        $_html .= '<label>'.$this->l('Title of your RSS Feed').':</label>';



        $_html .= '<div class="margin-form">';



        $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));

        $languages = Language::getLanguages(false);



        foreach ($languages as $language){

            $id_lng = (int)$language['id_lang'];

            $rssname = Configuration::get($this->name.'rssname'.'_'.$id_lng);





            $_html .= '	<div id="rssname_'.$language['id_lang'].'"

							 style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;"

							 >



						<input type="text" style="width:400px"

								  id="rssname_'.$language['id_lang'].'"

								  name="rssname_'.$language['id_lang'].'"

								  value="'.htmlentities(Tools::stripslashes($rssname), ENT_COMPAT, 'UTF-8').'"/>

						</div>';

        }

        $_html .= '';

        ob_start();

        $this->displayFlags($languages, $defaultLanguage, $divLangName, 'rssname');

        $displayflags = ob_get_clean();

        $_html .= $displayflags;

        $_html .= '<div style="clear:both"></div>';



        $_html .= '</div>';







        // Description of your RSS Feed







        $_html .= '<label>'.$this->l('Description of your RSS Feed').':</label>';



        $_html .= '<div class="margin-form">';



        $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));

        $languages = Language::getLanguages(false);



        foreach ($languages as $language){

            $id_lng = (int)$language['id_lang'];

            $rssdesc = Configuration::get($this->name.'rssdesc_'.$id_lng);





            $_html .= '	<div id="srssdesc_'.$language['id_lang'].'"

							 style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;"

							 >



							 <input type="text" style="width:400px"

								  id="rssdesc_'.$language['id_lang'].'"

								  name="rssdesc_'.$language['id_lang'].'"

								  value="'.htmlentities(Tools::stripslashes($rssdesc), ENT_COMPAT, 'UTF-8').'"/>



					</div>';

        }

        $_html .= '';

        ob_start();

        $this->displayFlags($languages, $defaultLanguage, $divLangName, 'srssdesc');

        $displayflags = ob_get_clean();

        $_html .= $displayflags;

        $_html .= '<div style="clear:both"></div>';



        $_html .= '</div>';

        // Description of your RSS Feed







        $_html .= '<label>'.$this->l('Number of items in RSS Feed').':</label>';



        $_html .=  '

					<input type="text" name="number_rssitems"

			               value="'.Tools::getValue('number_rssitems', Configuration::get($this->name.'number_rssitems')).'"

			               >

				';



        $_html .= '</div>';











        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">

					<input type="submit" name="rssfeedsettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';





        return $_html;

    }



    private function _starslistandsearchOLD(){

        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-bars fa-lg"></i>&nbsp;'.$this->l('Stars in Category and Search pages').'</h3>';





        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';









        $_html .= '<label>'.$this->l('Enable or Disable stars on the category and search pages').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="starscat"

							'.(Tools::getValue('starscat', Configuration::get($this->name.'starscat')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="starscat"

						   '.(!Tools::getValue('starscat', Configuration::get($this->name.'starscat')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				</div>';

        $_html .= '<p class="clear"></p>';



        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">

					<input type="submit" name="starslistandsearchsettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';





        return $_html;

    }



    private function _lastreviewsblockOLD(){

        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-list-alt fa-lg"></i>&nbsp;'.$this->l('Last Reviews Block').'</h3>';





        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';





        $_html .= '<label>'.$this->l('Enable or Disable Block "Last Reviews"').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="is_blocklr"

							'.(Tools::getValue('is_blocklr', Configuration::get($this->name.'is_blocklr')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="is_blocklr"

						   '.(!Tools::getValue('is_blocklr', Configuration::get($this->name.'is_blocklr')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				</div>';

        $_html .= '<p class="clear"></p>';





        $input = $this->block_last_reviews();



        $_html .= '<label>'.$input['label'].':</label>';







         $_html .= '<div class="col-lg-9 '.$input['name'].'">

            <div class="panel">







                <table class="table">

                    <thead>

                    <tr>



                        <th><b>'.$this->l('Page').'</b></th>

                        <th><b>'.$this->l('Position').'</b></th>

                        <th><b>'.$this->l('Product Images size').'</b></th>

                        <th><b>'.$this->l('Number of displayed reviews').'</b></th>

                        <th><b>'.$this->l('Width').'</b></th>

                        <th><b>'.$this->l('Truncate reviews').'</b></th>

                        <th><b>'.$this->l('Status').'</b></th>



                    </tr>

                    </thead>

                    <tbody>';





                    foreach($input['values'] as $key => $cms_item){

                        $_html .= '<tr class="alt_row">

                            <td>

                            '.$cms_item['name'].'

                            </td>

                            <td>

                                <div class="col-lg-12">



                                    <select id="p'.$key.'" class="col-sm-12" name="p'.$key.'">';

                                        if($key == 'blocklr_home') {

                                            foreach ($input['available_pos_home'] as $key_pos => $cms_item_pos) {

                                                $_html .= '  <option ' . (($cms_item['position'] == $key_pos) ? 'selected="selected"' : '') . '

                                                        value="' . $key_pos . '">' . $cms_item_pos . '</option>';



                                            }

                                        }elseif($key == 'blocklr_chook'){

                                            foreach($input['available_pos_chook'] as $key_pos => $cms_item_pos)

                                                $_html .= '<option '.(($cms_item['position']== $key_pos)?'selected="selected"':'').'

                                                        value="'.$key_pos.'">'.$cms_item_pos.'</option>';





                                        } else {

                                            foreach($input['available_pos'] as $key_pos => $cms_item_pos)

                                                $_html .= '<option '.(($cms_item['position']== $key_pos)?'selected="selected"':'').'

                                                        value="'.$key_pos.'">'.$cms_item_pos.'</option>';





                                        }



                                   $_html .= ' </select>

                                </div>



                            </td>

                            <td>

                                <div class="col-lg-12">

                                    <select id="i'.$key.'" class="col-sm-12" name="i'.$key.'">';



                                            foreach($input['image_sizes'] as $cms_item_im)

                                               $_html .= ' <option '.(($cms_item['imsize']['imsize'] == $cms_item_im['id'])?'selected="selected"':'').'

                                                        value="'.$cms_item_im['id'].'">'.$cms_item_im['name'].'</option>';









                                    $_html .= '</select>

                                </div>



                            </td>

                            <td>

                                <div class="input-group">

                                    <input type="text" name="'.$cms_item['number_display_reviews']['name'].'"

                                           value="'.$cms_item['number_display_reviews']['number_display_reviews'].'" />



                                </div>



                            </td>

                            <td>

                                <div class="input-group">

                                    <input type="text" name="'.$cms_item['width']['name'].'"

                                           value="'.$cms_item['width']['width'].'" />

                                    <span class="input-group-addon">&nbsp;%</span>





                                </div>



                            </td>

                            <td>

                                <div class="input-group">

                                    <input type="text" name="'.$cms_item['truncate']['name'].'"

                                           value="'.$cms_item['truncate']['truncate'].'" />

                                    <span class="input-group-addon">&nbsp;'.$this->l('chars').'</span>





                                </div>



                            </td>

                            <td>

                                <div class="checkbox">



                                        <input type="checkbox" '.(($cms_item['status']== $key)?'checked="checked"':'').'

                                               value="'.$key.'" id="'.$key.'"

                                               name="'.$key.'"/>

                                </div>



                            </td>

                        </tr>';

                    }





                    $_html .= '</tbody>

                </table>

            </div>





        </div>';







        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">

					<input type="submit" name="lastreviewsblocksettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';



        if($this->_is15)

            $_html .= $this->_customhookhelp();





        return $_html;

    }





    private function _customeraccountreviewspageOLD(){

        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-users fa-lg"></i>&nbsp;'.$this->l('Customer account reviews page').'</h3>';





        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';





        $_html .= '<label>'.$this->l('Number of reviews per page on Customer account page').':</label>



    		<div class="margin-form">

				<input type="text" name="revperpagecus" size="10"

			       	   value="'.Tools::getValue('revperpagecus', Configuration::get($this->name.'revperpagecus')).'">



			</div>';



        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">

					<input type="submit" name="customeraccountreviewspagesettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';





        return $_html;

    }



    private function _reviewcriteriaOLD(){

        if (Tools::isSubmit('addCriteria') || Tools::isSubmit('editgsnipreview')) {

            return $this->_displayAddReviewcriteriaFormOLD();

        } else {

            return $this->_displayReviewcriteriaGridOLD();

        }

    }



    private function _displayAddReviewcriteriaFormOLD(){

        $id_block = Tools::getValue('id');



        if (Tools::isSubmit('id') && Tools::isSubmit('editgsnipreview'))

        {

            $this->_display = 'edit';



            include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

            $obj_gsnipreviewhelp = new gsnipreviewhelp();

            $_data = $obj_gsnipreviewhelp->getReviewCriteriaItem(array('id'=>(int)$id_block));

            $item_data_lng = isset($_data['item']['data'])?$_data['item']['data']:array();

            $status = isset($_data['item'][0]['active'])?$_data['item'][0]['active']:0;



        } else {

            $this->_display = 'add';

            $item_data_lng = array();

            $status = 0;

        }





        $title_block = !empty($id_block) ? $this->l('Edit criterion') : $this->l('Add new criterion');

        $icon = !empty($id_block) ? 'icon-edit' : 'icon-plus-square';







        $divLangName = "name¤description";



        $_html = '';

        $_html .= '<form method="post"

    					action="'.$_SERVER['REQUEST_URI'].'"

    					enctype="multipart/form-data">';





        $_html .= '<h3 class="title-block-content"><i class="'.$icon.'"></i>&nbsp;'.$title_block.'</h3>';



        $_html .= '<label>'.$this->l('Criterion name').'</label>';



        $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));

        $languages = Language::getLanguages(false);



        $_html .= '<div class="margin-form">';



        foreach ($languages as $language){

            $id_lng = (int)$language['id_lang'];

            $title = isset($item_data_lng[$id_lng]['name'])?$item_data_lng[$id_lng]['name']:"";



            $_html .= '	<div id="name_'.$language['id_lang'].'"

							 style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;"

							 >



						<input type="text" style="width:400px"

								  id="name_'.$language['id_lang'].'"

								  name="name_'.$language['id_lang'].'"

								  value="'.htmlentities(Tools::stripslashes($title), ENT_COMPAT, 'UTF-8').'"/>

						</div>';

        }

        ob_start();

        $this->displayFlags($languages, $defaultLanguage, $divLangName, 'name');

        $displayflags = ob_get_clean();

        $_html .= $displayflags;

        $_html .= '<div style="clear:both"></div>';



        $_html .=  '</div>';





        $_html .= '<label>'.$this->l('Description').'</label>';





        $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));

        $languages = Language::getLanguages(false);



        $_html .= '<div class="margin-form">';



        foreach ($languages as $language){

            $id_lng = (int)$language['id_lang'];

            $seo_keywords = isset($item_data_lng[$id_lng]['description'])?$item_data_lng[$id_lng]['description']:"";



            $_html .= '	<div id="description_'.$language['id_lang'].'"

							 style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;"

							 >

						<textarea cols="60" rows="10"

			                	  id="description_'.$language['id_lang'].'"

								  name="description_'.$language['id_lang'].'"

								  >'.htmlentities(Tools::stripslashes($seo_keywords), ENT_COMPAT, 'UTF-8').'</textarea>

						</div>';

        }

        ob_start();

        $this->displayFlags($languages, $defaultLanguage, $divLangName, 'description');

        $displayflags = ob_get_clean();

        $_html .= $displayflags;

        $_html .= '<div style="clear:both"></div>';

        $_html .= '<p class="clear">'.$this->l('If you do not want see description - just leave this field empty').'</p>';

        $_html .=  '</div>';









        if($this->_is15){

            // shop association

            $_html .= '<div class="clear"></div>';

            $_html .= '<label>'.$this->l('Shop association').':</label>';

            $_html .= '<div class="margin-form">';



            $_html .= '<table width="50%" cellspacing="0" cellpadding="0" class="table">

						<tr>

							<th>Shop</th>

						</tr>';

            $u = 0;



            $shops = Shop::getShops();

            $shops_tmp = explode(",",isset($_data['item'][0]['id_shop'])?$_data['item'][0]['id_shop']:"");



            $count_shops = sizeof($shops);

            foreach($shops as $_shop){

                $id_shop = $_shop['id_shop'];

                $name_shop = $_shop['name'];

                $_html .= '<tr>

						<td>

							<img src="../img/admin/lv2_'.((($count_shops-1)==$u)?"f":"b").'.png" alt="" style="vertical-align:middle;">

							<label class="child">';





                $_html .= '<input type="checkbox"

								   name="cat_shop_association[]"

								   value="'.$id_shop.'" '.((in_array($id_shop,$shops_tmp))?'checked="checked"':'').'

								   class="input_shop"

								   />

								'.$name_shop.'';



                $_html .= '</label>

						</td>

					</tr>';

                $u++;

            }



            $_html .= '</table>';



            $_html .= '</div>';



        }

        // shop association



        $_html .= '<label>'.$this->l('Status').'</label>

				<div class = "margin-form">';



        $_html .= '<select name="active">

					<option value=1 '.(($status==1)?"selected=\"true\"":"").'>'.$this->l('Enabled').'</option>

					<option value=0 '.(($status==0)?"selected=\"true\"":"").'>'.$this->l('Disabled').'</option>

				   </select>';



        $_html .= '</div>';







            if(!empty($id_block))

                $_html .= '<input type = "hidden" name = "id" value = "'.$id_block.'"/>';



            $_html .= '<p class="center" style="background: none; padding: 10px; margin-top: 10px;">

					<input type="submit" name="gsnipreviewcriteriaset" value="'.$this->l('Cancel').'"

                		   class="button"  />

    				<input type="submit" name="'.((!$id_block)?'addcriteriasettings':'editcriteriasettings').'" value="'.((!$id_block)?$this->l('Save'):$this->l('Update')).'"

                		   class="button"  />



                	</p>';

        $_html .= '</form>';



        return $_html;

    }



    private function _displayReviewcriteriaGridOLD(){

        $currentIndex = $this->context->currentindex;

        $controller = 'AdminModules';

        $cookie = $this->context->cookie;

        $token = Tools::getAdminToken($controller.(int)(Tab::getIdFromClassName($controller)).(int)($cookie->id_employee));

        ## add information ##

        include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

        $obj_gsnipreviewhelp = new gsnipreviewhelp();





        $_data = $obj_gsnipreviewhelp->getReviewCriteriaItems(array('start' => 0,'step'=>1000));

        $_items = $_data['items'];





        if(sizeof($_items)>0) {



            foreach ($_items as $_k=>$_item) {



                ## languages ##

                $ids_lng = isset($_item['ids_lng']) ? $_item['ids_lng'] : array();

                $lang_for_item = array();

                foreach ($ids_lng as $lng_id) {

                    $data_lng = Language::getLanguage($lng_id);

                    $lang_for_item[] = $data_lng['iso_code'];

                }

                $lang_for_item = implode(",", $lang_for_item);



                $_items[$_k]['ids_lng'] = $lang_for_item;

                ## languages ##



                if($this->_is15) {

                    ## shops ##

                    $ids_shops = explode(",", $_item['id_shop']);



                    $shops = Shop::getShops();

                    $name_shop = array();

                    foreach ($shops as $_shop) {

                        $id_shop_lists = $_shop['id_shop'];

                        if (in_array($id_shop_lists, $ids_shops))

                            $name_shop[] = $_shop['name'];

                    }



                    $name_shop = implode(", ", $name_shop);



                    $_items[$_k]['id_shop'] = $name_shop;

                    ## shops ##

                }



            }

        }

        ## add information ##



        $_html = '';

        $_html .= '<h3 class="title-block-content"><i class="fa fa-reviews fa-lg"></i>&nbsp;'.$this->l('Review Criteria').'</h3>';



        $_html .= '<a style="border: 1px solid rgb(240, 95, 93); display: block; font-size: 16px; color: rgb(240, 95, 93); text-align: center; font-weight: bold; text-decoration: underline; padding: 5px; margin-bottom: 10px;" id="link-add-question-form"

                         href="'.$currentIndex.'&configure='.$this->name.'&token='.$token.'&&addCriteria">

                         '.$this->l('Add New Criterion').'

                         </a>';



        $_html .= '<table class="table" width=100% >';



        $_html .= '<tr>

    					<th>'.$this->l('Id').'</th>

    			        <th>'.$this->l('Name').'</th>';

        if($this->_is15) {

            $_html .= '<th>' . $this->l('Shop') . '</th>';

        }

    			        $_html .= '<th>'.$this->l('Language').'</th>

    			        <th>'.$this->l('Status').'</th>

    			       <th>'.$this->l('Action').'</th>



    			   </tr>';





        $data = $_items;

        if(sizeof($data)>0){



            for($i=0;$i<sizeof($data);$i++){



                $id = $data[$i]['id_gsnipreview_review_criterion'];

                $name = $data[$i]['name'];

                $id_shop = $data[$i]['id_shop'];

                $ids_lng  = $data[$i]['ids_lng'];

                $active = $data[$i]['active'];



                $_html .= '<tr>

							<td>'.$id.'</td>

		    				<td>'.$name.'</td>';

                if($this->_is15) {

                    $_html .= '<td>' . $id_shop . '</td>';

                }

                            $_html .= '<td>'.$ids_lng.'</td>';





                $_html .=  '<td>';

                if($active == 1){

                    $_html .= '<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">';

                } else {

                    $_html .= '<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">';

                }

                $_html .= '</td>';



                $_html .=  '<td>';

                $_html .= '<a href="'.$currentIndex.'&configure='.$this->name.'&token='.$token.'&edit'.$this->name.'&id='.(int)($id).'" title="'.$this->l('Edit').'"><img src="'._PS_ADMIN_IMG_.'edit.gif" alt="" /></a>

				 		   <a href="'.$currentIndex.'&configure='.$this->name.'&token='.$token.'&delete_item'.$this->name.'=delete&id='.(int)($id).'" title="'.$this->l('Delete').'"  onclick = "javascript:return confirm(\''.$this->l('Are you sure you want to remove this item?').'\');"><img src="'._PS_ADMIN_IMG_.'delete.gif" alt="" /></a>';



                $_html .= '</td>



		    			   </tr>

		    			   ';

            }



        } else {

            $_html .= '<tr>

    					<td colspan=11 style="border-bottom:none;text-align:center;padding:10px">'.$this->l('No records found.').'</td>

    				   </tr>';

        }



        $_html .= '</table>';





        return $_html;



    }



    private function _reviewsmanagementOLD(){

        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-reviews fa-lg"></i>&nbsp;'.$this->l('Reviews management').'</h3>';





        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';





        // Require Admin Approval

        $_html .= '<label>'.$this->l('Require Admin Approval').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="is_approval"

							'.(Tools::getValue('is_approval', Configuration::get($this->name.'is_approval')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="is_approval"

						   '.(!Tools::getValue('is_approval', Configuration::get($this->name.'is_approval')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				</div>';

        // Require Admin Approval

        $_html .= '<p class="clear"></p>';





        // Who can add reviews?

        $_html .= '<label>'.$this->l('Who can add review').'?</label>



    		<div class="margin-form">



				<input type="radio" value="reg" id="reg" name="whocanadd"

								'.(Tools::getValue('whocanadd', Configuration::get($this->name.'whocanadd')) == "reg" ? 'checked="checked" ' : '').'>

			<b>'.$this->l('Only registered users').'</b>



			 &nbsp;&nbsp;&nbsp;

				<input type="radio" value="buy" id="buy" name="whocanadd"

								'.(Tools::getValue('whocanadd', Configuration::get($this->name.'whocanadd')) == "buy" ? 'checked="checked" ' : '').'>

			<b>'.$this->l('Only users who already bought the product').'</b>



			&nbsp;&nbsp;&nbsp;

				<input type="radio" value="all" id="all" name="whocanadd"

								'.(Tools::getValue('whocanadd', Configuration::get($this->name.'whocanadd')) == "all" ? 'checked="checked" ' : '').'>

			<b>'.$this->l('All users').'</b>



			</div>';

        // Who can add reviews?

        $_html .= '<p class="clear"></p>';



        $_html .= '<label>'.$this->l('The user can add more one review').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="is_onerev" name="is_onerev"

							'.(Configuration::get($this->name.'is_onerev') ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="is_onerev" name="is_onerev"

						   '.(!Configuration::get($this->name.'is_onerev') ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				</div>';

        $_html .= '<p class="clear"></p>';



        $_html .= '<label>'.$this->l('MULTILANGUAGE. Separates different languages comments depended on the language selected by the customer (e.g. only English comments on the English site)').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="rswitch_lng"

							'.(Tools::getValue('rswitch_lng', Configuration::get($this->name.'rswitch_lng')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="rswitch_lng"

						   '.(!Tools::getValue('rswitch_lng', Configuration::get($this->name.'rswitch_lng')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				</div>';

        $_html .= '<p class="clear"></p>';



        // Number of reviews per page

        $_html .= '<label>'.$this->l('Number of reviews per page on All Reviews page').':</label>



    		<div class="margin-form">

				<input type="text" name="revperpageall" size="10"

			       	   value="'.Tools::getValue('revperpageall', Configuration::get($this->name.'revperpageall')).'">



			</div>';

        // Number of reviews per page



        $_html .= '<p class="clear"></p>';





        // Number of reviews per page for moderation

        $_html .= '<label>'.$this->l('Number of reviews per page on moderation page').':</label>



    		<div class="margin-form">

				<input type="text" name="adminrevperpage" size="10"

			       	   value="'.Tools::getValue('adminrevperpage', Configuration::get($this->name.'adminrevperpage')).'">



			</div>';

        // Number of reviews per page for moderation



        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">

					<input type="submit" name="reviewsmanagementsettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';















        return $_html;

    }



    private function _productpageOLD(){

        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-book fa-lg"></i>&nbsp;'.$this->l('Product page Settings').'</h3>';





        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';





        $_html .= '<label>'.$this->l('Product tabs').':</label>

				<div class="margin-form">

					<select class="select" name="ptabs_type"

							id="ptabs_type">

						<option '.(Tools::getValue('ptabs_type', Configuration::get($this->name.'ptabs_type'))  == "1" ? 'selected="selected" ' : '').' value="1">'.$this->l('Standard theme without Tabs').'</option>

						<option '.(Tools::getValue('ptabs_type', Configuration::get($this->name.'ptabs_type')) == "2" ? 'selected="selected" ' : '').' value="2">'.$this->l('Custom theme with tabs on product page').'</option>

					</select>

					<p class="clear">'.$this->l('On a standard PrestaShop 1.6 theme, the product page no longer has tabs for the various sections. But some custom themes have added back tabs on the product page.').'</p>

				</div>';





        $_html .= '<label >'.$this->l('Enable or Disable Helpful review functional').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="is_helpfulf"

							'.(Tools::getValue('is_helpfulf', Configuration::get($this->name.'is_helpfulf')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="is_helpfulf"

						   '.(!Tools::getValue('is_helpfulf', Configuration::get($this->name.'is_helpfulf')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.$this->l('Enable or Disable Helpful review functional').'</p>

				</div>';



        $_html .= '<label >'.$this->l('Enable or Disable Report abuse functional').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="is_abusef"

							'.(Tools::getValue('is_abusef', Configuration::get($this->name.'is_abusef')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="is_abusef"

						   '.(!Tools::getValue('is_abusef', Configuration::get($this->name.'is_abusef')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.



            $this->l('Enable or Disable Report abuse functional').'</p>

				</div>';







        $_html .= '<p class="clear"></p>';

        // Style stars



        $_html .= '<script type="text/javascript">





			function selectImgRating(id)

			{

			if(id==0){

				$("#star-active-green").css("display","none");

				$("#star-active-blue").css("display","none");

				$("#star-active-yellow").css("display","block");



				$("#star-active-green-block").css("display","none");

				$("#star-active-blue-block").css("display","none");

				$("#star-active-yellow-block").css("display","block");



			} else if(id==1) {

				$("#star-active-blue").css("display","none");

				$("#star-active-yellow").css("display","none");

				$("#star-active-green").css("display","block");



				$("#star-active-blue-block").css("display","none");

				$("#star-active-yellow-block").css("display","none");

				$("#star-active-green-block").css("display","block");



			} else if(id==2){

				$("#star-active-yellow").css("display","none");

				$("#star-active-green").css("display","none");

				$("#star-active-blue").css("display","block");



				$("#star-active-yellow-block").css("display","none");

				$("#star-active-green-block").css("display","none");

				$("#star-active-blue-block").css("display","block");

			}

			}

		</script>';



        $_html .= '<label>'.$this->l('Style stars').':</label>

				<div class="margin-form">

					<select class="select" name="stylestars" onChange="selectImgRating(this.selectedIndex)"

							id="stylestars" style="float:left">

						<option '.(Tools::getValue('stylestars', Configuration::get($this->name.'stylestars'))  == "style1" ? 'selected="selected" ' : '').' value="style1">'.$this->l('Yellow Stars').'</option>

						<option '.(Tools::getValue('stylestars', Configuration::get($this->name.'stylestars')) == "style2" ? 'selected="selected" ' : '').' value="style2">'.$this->l('Green Stars').'</option>

						<option '.(Tools::getValue('stylestars', Configuration::get($this->name.'stylestars')) == "style3" ? 'selected="selected" ' : '').' value="style3">'.$this->l('Blue Stars').'</option>



					</select>

					<div style="float:left">

					<img src="../modules/'.$this->name.'/views/img/star-active-yellow.png" id="star-active-yellow" style="padding-left:5px;'.(Tools::getValue('stylestars', Configuration::get($this->name.'stylestars'))  == "style1" ? 'display:inline' : 'display:none').'"/>

					<img src="../modules/'.$this->name.'/views/img/star-active-green.png" id="star-active-green" style="padding-left:5px;'.(Tools::getValue('stylestars', Configuration::get($this->name.'stylestars'))  == "style2" ? 'display:inline' : 'display:none').'"/>

					<img src="../modules/'.$this->name.'/views/img/star-active-blue.png" id="star-active-blue" style="padding-left:5px;'.(Tools::getValue('stylestars', Configuration::get($this->name.'stylestars'))  == "style3" ? 'display:inline' : 'display:none').'"/>

					</div>

						<p class="clear">'.$this->l('Choose your style for the stars icons').'.</p>

				</div>';

        // Style stars



        // Enable or Disable stars for each reviews

        $_html .= '<label>'.$this->l('Enable or Disable stars for each reviews').':</label>

				<div class="margin-form">';



        if($this->_is16==1)

            $_html .=	'<table cellpadding="0" cellspacing="0" width="100%">';

        else

            $_html .=	'<table cellpadding="0" cellspacing="0" width="47%">';

        $_html .= 	'<tr>';



        if($this->_is16==1)

            $_html .= '<td valign="top" style="width:100px">';

        else

            $_html .= '<td valign="top">';



        $_html .= '<input type="radio" value="1" id="text_list_on" name="starratingon"

							'.(Tools::getValue('starratingon', Configuration::get($this->name.'starratingon')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="starratingon"

						   '.(!Tools::getValue('starratingon', Configuration::get($this->name.'starratingon')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>

					</td>

					<td>

					<img src="../modules/'.$this->name.'/views/img/ratingsblock-yellow.png" class="img-responsive" id="star-active-yellow-block"

					    style="'.((Configuration::get($this->name.'stylestars') == "style1")?"display:inline":"display:none").'" />

                    <img src="../modules/'.$this->name.'/views/img/ratingsblock-green.png" class="img-responsive" id="star-active-green-block"

                        style="'.((Configuration::get($this->name.'stylestars') == "style2")?"display:inline":"display:none").'" />

                    <img src="../modules/'.$this->name.'/views/img/ratingsblock-blue.png" class="img-responsive" id="star-active-blue-block"

                        style="'.((Configuration::get($this->name.'stylestars') == "style3")?"display:inline":"display:none").'" />



					</td>

					</tr>

					</table>

				<p class="clear"></p></div>';

        // Enable or Disable stars for each reviews







        // Hook to display

        $_html .= '<label>'.$this->l('Hook to display block with ratings, number of reviews etc').':</label>

				<div class="margin-form">

					<select class="select" name="hooktodisplay"

							id="hooktodisplay">

						<option '.(Tools::getValue('hooktodisplay', Configuration::get($this->name.'hooktodisplay'))  == "extra_right" ? 'selected="selected" ' : '').' value="extra_right">'.$this->l('Extra Right').'</option>

						<option '.(Tools::getValue('hooktodisplay', Configuration::get($this->name.'hooktodisplay')) == "extra_left" ? 'selected="selected" ' : '').' value="extra_left">'.$this->l('Extra Left').'</option>

						<option '.(Tools::getValue('hooktodisplay', Configuration::get($this->name.'hooktodisplay')) == "product_actions" ? 'selected="selected" ' : '').' value="product_actions">'.$this->l('Product Actions').'</option>

						<option '.(Tools::getValue('hooktodisplay', Configuration::get($this->name.'hooktodisplay')) == "product_footer" ? 'selected="selected" ' : '').' value="product_footer">'.$this->l('Product Footer').'</option>

                        <option '.(Tools::getValue('hooktodisplay', Configuration::get($this->name.'hooktodisplay')) == "none" ? 'selected="selected" ' : '').' value="none">'.$this->l('None').'</option>

					</select>

						<p class="clear">'.$this->l('Block with ratings, number of reviews, link to post a new review').'.</p>

				</div>';

        // Hook to display





        $_html .= '<label>'.$this->l('Number of reviews per page on Product page').':</label>



    		<div class="margin-form">

				<input type="text" name="revperpage" size="10"

			       	   value="'.Tools::getValue('revperpage', Configuration::get($this->name.'revperpage')).'">



			</div>';





        $_html .= '<br/><br/><br/>';

        $_html .= '<h3 class="title-block-content"><i class="fa fa-facebook fa-lg"></i>&nbsp; Social Buttons</h3>';

        $_html .= '<br/>';



        $_html .= '<label >'.$this->l('Enable or Disable Social buttons').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="rsoc_on"

							'.(Tools::getValue('rsoc_on', Configuration::get($this->name.'rsoc_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="rsoc_on"

						   '.(!Tools::getValue('rsoc_on', Configuration::get($this->name.'rsoc_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.$this->l('Enable or Disable Social buttons for each Product Review').'</p>

				</div>';



        $_html .= '<label >'.$this->l('Display count box').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="rsoccount_on"

							'.(Tools::getValue('rsoccount_on', Configuration::get($this->name.'rsoccount_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="rsoccount_on"

						   '.(!Tools::getValue('rsoccount_on', Configuration::get($this->name.'rsoccount_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>

                    <p class="clear">

                    '.$this->l('You can configure "Voucher, when a user share review on the Facebook" in the').

                        ' <a href="javascript:void(0)" onclick="tabs_custom(103)" style="text-decoration:underline;font-weight:bold;color:red">'.$this->l('Voucher Settings').'</a> '.

                        $this->l('Tab').'

                    </p>



				</div>';







        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">

					<input type="submit" name="productpagesettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';















        return $_html;

    }



    private function _globalOLD(){

        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-cogs fa-lg"></i>&nbsp;'.$this->l('Global Settings').'</h3>';





        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';





        // Enable or Disable Product Reviews and Ratings

        $_html .= '<label >'.$this->l('Enable or Disable Product Reviews and Ratings').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="rvis_on"

							'.(Tools::getValue('rvis_on', Configuration::get($this->name.'rvis_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="rvis_on"

						   '.(!Tools::getValue('rvis_on', Configuration::get($this->name.'rvis_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.



            $this->l('Enable or Disable Product Reviews and Ratings').'</p>

				</div>';





        $_html .= '<label >'.$this->l('Enable or Disable "Rating/Review Criteria" field(s)').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="ratings_on"

							'.(Tools::getValue('ratings_on', Configuration::get($this->name.'ratings_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="ratings_on"

						   '.(!Tools::getValue('ratings_on', Configuration::get($this->name.'ratings_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.$this->l('You also can add').' <a href="javascript:void(0)" style="text-decoration:underline" onclick="tabs_custom(102)">'.$this->l('Review Criteria').'</a><br/>'.

            $this->l('If you delete/disable all Review criteria will be displayed only Rating field in Write Your Review form').'</p>

				</div>';





        $_html .= '<label >'.$this->l('Enable or Disable "Avatar" field').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="is_avatar'.$this->_prefix_review.'" name="is_avatar'.$this->_prefix_review.'"

							'.(Configuration::get($this->name.'is_avatar'.$this->_prefix_review) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="is_avatar'.$this->_prefix_review.'" name="is_avatar'.$this->_prefix_review.'"

						   '.(!Configuration::get($this->name.'is_avatar'.$this->_prefix_review) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.



            $this->l('Enable or Disable "Avatar" field').'</p>

				</div>';





        $_html .= '<label >'.$this->l('Enable or Disable "Files" field').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="is_files'.$this->_prefix_review.'" name="is_files'.$this->_prefix_review.'"

							'.(Configuration::get($this->name.'is_files'.$this->_prefix_review) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="is_files'.$this->_prefix_review.'" name="is_files'.$this->_prefix_review.'"

						   '.(!Configuration::get($this->name.'is_files'.$this->_prefix_review) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.



            $this->l('Enable or Disable "Files" field').'</p>

				</div>';



        $_html .= '<label>'.$this->l('Number of files user can add for review').':</label>



    		<div class="margin-form">

				<input type="text" name="'.$this->_prefix_review.'uploadfiles" size="10"

			       	   value="'.Configuration::get($this->name.$this->_prefix_review.'uploadfiles').'">



			</div>';



        $_html .= '<label >'.$this->l('Enable or Disable "Title" field').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="title_on"

							'.(Tools::getValue('title_on', Configuration::get($this->name.'title_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="title_on"

						   '.(!Tools::getValue('title_on', Configuration::get($this->name.'title_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.



            $this->l('Enable or Disable "Title" field').'</p>

				</div>';



        $_html .= '<label >'.$this->l('Enable or Disable "Text" field').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="text_on"

							'.(Tools::getValue('text_on', Configuration::get($this->name.'text_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="text_on"

						   '.(!Tools::getValue('text_on', Configuration::get($this->name.'text_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.



            $this->l('Enable or Disable "Text" field').'</p>

				</div>';





        $_html .= '<label>'.$this->l('Minimum chars the user must write in the Text field for add review').':</label>



    		<div class="margin-form">

				<input type="text" name="'.$this->_prefix_review.'minc" size="10"

			       	   value="'.Configuration::get($this->name.$this->_prefix_review.'minc').'">

                <p class="clear"></p>

			</div>';



        $_html .= '<label >'.$this->l('Enable or Disable "IP" or "City and Country Name" field').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="ip_on"

							'.(Tools::getValue('ip_on', Configuration::get($this->name.'ip_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="ip_on"

						   '.(!Tools::getValue('ip_on', Configuration::get($this->name.'ip_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>';



        if($this->_is15) {

            $_html .= '<p class="clear">' .



                $this->l('City and Country Name will be displayed, if your enable Geolocation in admin panel -> Preferences -> Geolocation') . '</p>';

        } else {

            $_html .= '<div class="clear"></div>';

        }

				$_html .= '</div>';









        $_html .= '<label >'.$this->l('Enable or Disable Captcha').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="is_captcha"

							'.(Tools::getValue('is_captcha', Configuration::get($this->name.'is_captcha')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="is_captcha"

						   '.(!Tools::getValue('is_captcha', Configuration::get($this->name.'is_captcha')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear">'.



            $this->l('Enable or Disable Captcha').'</p>

				</div>';





        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">

					<input type="submit" name="globalsettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';















        return $_html;

    }





    private function _cronhelp($data = null){

        $url_cron = isset($data['url'])?$data['url']:'';

        $_html = '';



        if($url_cron == 'cron_shop_reviews'){

            $text_type_review = $this->l('STORE');

        } else {

            $text_type_review = $this->l('PRODUCT');

        }



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<div class="panel">



				<div class="panel-heading"><i class="fa fa-tasks fa-lg"></i>&nbsp;'.$this->l('CRON HELP').' '.$text_type_review.' '.$this->l('REVIEWS').'</div>';

        } else {

            $_html .= '<h3 class="panel-heading"><i class="fa fa-tasks fa-lg"></i>&nbsp;'.$this->l('CRON HELP').' '.$text_type_review.' '.$this->l('REVIEWS').'</h3>';



        }









        $_html .= '<p class="hint clear" style="display: block; font-size: 12px; width: 95%;position:relative">';



        $_html .= '<b>';

        $_html .= $this->l('You can configure sending email messages through cron. You have 2 possibilities:');

        $_html .= '</b>';

        $_html .= '<br/><br/><br/>';

        $_html .= '<b>1.</b> '.$this->l('You can enter the following url in your browser: ');

        $_html .= '<b>'.$this->getURLMultiShop().'modules/'.$this->name.'/'.$url_cron.'.php?token='.$this->getokencron().'</b>';

        $_html .= '<br/><br/><br/>';

        $_html .= '<b>2.</b> '.$this->l('You can set a cron\'s task (a recursive task that fulfills the sending of reminders)');

        $_html .= '<br/><br/>';

        $_html .= $this->l('The task run every hour').':&nbsp;&nbsp;&nbsp; <b>* */1 * * * /usr/bin/wget -O - -q '.$this->getURLMultiShop().'modules/'.$this->name.'/'.$url_cron.'.php?token='.$this->getokencron().'</b>';

        $_html .= '<br/><br/>';

        $_html .= $this->l('or');

        $_html .= '<br/><br/>';

        $_html .= $this->l('The task run every hour').':&nbsp;&nbsp;&nbsp; <b>* */1 * * * php -f /var/www/vhosts/myhost/httpdocs/prestashop/modules/'.$this->name.'/'.$url_cron.'.php?token='.$this->getokencron().'</b>';

        $_html .= '<br/><br/><br/>';

        $_html .= '<b>'.$this->l('How to configure a cron task ?').'</b>';

        $_html .= '<br/><br/>';

        $_html .= $this->l('On your server, the interface allows you to configure cron\'s tasks');

        $_html .= '<br/>';

        $_html .= $this->l('About CRON').'&nbsp;&nbsp;&nbsp;<a href=http://en.wikipedia.org/wiki/Cron target=_blank>http://en.wikipedia.org/wiki/Cron</a>';

        $_html .= '</p>';





        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '</div>';

        }



        return $_html;

    }

    

    private function _productComments(){

    	include_once(dirname(__FILE__).'/classes/importhelp.class.php');

		$obj = new importhelp();



		$data_comments = $obj->getCountComments();

		

		$is_count_comments = $data_comments['is_count_comments'];

		$count_comments = $data_comments['comments'];

		

		$_html = '';

        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<div class="panel">



				<div class="panel-heading"><i class="fa fa-comments-o fa-lg"></i>&nbsp;'.$this->l('Import Product Comments').'</div>';

        } else {

            $_html .= '

					<h3 class="title-block-content"><i class="fa fa-comments-o fa-lg"></i>&nbsp;'.$this->l('Import Product Comments').'</h3>';

        }



    	

    	

    	$_html .= '<p class="hint clear" style="display: block; font-size: 12px; width: 95%;position:relative">

    				

    				'.$this->l('If you are already using PrestaShop "Product comments" module, you can import all your existing ratings and comments so as not to lose any of your history. ').'

                     <br/><br/>';

    	

    	if($is_count_comments>0){

    		   $_html .= $this->l('You have').' <b>'.$count_comments.'</b> '.$this->l('comments').' &nbsp;&nbsp;&nbsp; ';

        	   $_html .= '<input type="submit" value="'.$this->l('Import Product comments').'" name="submitcomments"

        	                class="'.(version_compare(_PS_VERSION_, '1.6', '>')?'btn btn-primary pull':'button').'"/>';

    	} else{ 

    		

    		$_html .= '<b>'.$this->l('Your database no contains Product comments for imports').'</b>';

    	

    	} 

                     

         $_html .= '</p>';





        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '</div>';

        }

    	

    	return $_html;

	    	

    }









    private function _reviewsvouchersharereviewtab(){

        $cookie = $this->context->cookie;



        $_html = '';



        $_html .= '<h3 class="title-block-content"><i class="fa fa-facebook fa-lg"></i>&nbsp;'.$this->l('Voucher settings, when a user share review on the Facebook').'</h3>';





        $_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';





        // enable or disable vouchers

        $_html .= '<label>'.$this->l('Enable or Disable Voucher').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="vis_onfb" onclick="enableOrDisableFB(1)"

							'.(Tools::getValue('vis_onfb', Configuration::get($this->name.'vis_onfb')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="vis_onfb" onclick="enableOrDisableFB(0)"

						   '.(!Tools::getValue('vis_onfb', Configuration::get($this->name.'vis_onfb')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



					<p class="clear"><b style="color:red">'.$this->l('Enable or Disable Voucher, when a user share review on the Facebook').'</b></p>

				</div>';



        $_html .= '<script type="text/javascript">

			    	function enableOrDisableFB(id)

						{

						if(id==0){

							$("#block-voucher-settingsfb").hide(200);

						} else {

							$("#block-voucher-settingsfb").show(200);

						}



						}

					</script>';



        $_html .= '<div id="block-voucher-settingsfb" '.(Configuration::get($this->name.'vis_onfb')==1?'style="display:block"':'style="display:none"').'>';

        $divLangName = "coupondesc";



        // Voucher Description

        $_html .= '<label>'.$this->l('Voucher Description:').'</label>



    				<div class="margin-form">';



        $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));

        $languages = Language::getLanguages(false);



        foreach ($languages as $language){

            $id_lng = (int)$language['id_lang'];

            $coupondesc = Configuration::get($this->name.'coupondescfb'.'_'.$id_lng);





            $_html .= '	<div id="coupondescfb_'.$language['id_lang'].'"

							 style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;"

							 >



						<input type="text" style="width:400px"

								  id="coupondescfb_'.$language['id_lang'].'"

								  name="coupondescfb_'.$language['id_lang'].'"

								  value="'.htmlentities(Tools::stripslashes($coupondesc), ENT_COMPAT, 'UTF-8').'"/>

						</div>';

        }

        $_html .= '';

        ob_start();

        $this->displayFlags($languages, $defaultLanguage, $divLangName, 'coupondescfb');

        $displayflags = ob_get_clean();

        $_html .= $displayflags;

        $_html .= '<div style="clear:both"></div>';

        $_html .= '<p class="clear">'.$this->l('Brief description of a voucher code').'</p>';

        $_html .= '</div>';

        // Voucher Description



        // Voucher code

        $_html .= '<label>'.$this->l('Voucher code').':</label>



    		<div class="margin-form">

				<input type="text" name="vouchercodefb" size="5" maxlength="5"

			       	   value="'.Tools::getValue('vouchercodefb', Configuration::get($this->name.'vouchercodefb')).'">

				<p class="clear">'.$this->l('Voucher code prefix. It must be at least 3 letters long. Prefix voucher code will be used in the first part of the coupon code, which the user will use to get a discount.').'</p>



			</div>';

        // Voucher code





        // discount type

        $_html .= '<label>'.$this->l('Discount Type:').'</label>



    				<div class="margin-form">

    				<select class="select" name="discount_typefb" onChange="selectItemsFbFacebook(this.selectedIndex)"

							id="discount_typefb">

						<option '.(Tools::getValue('discount_typefb', Configuration::get($this->name.'discount_typefb'))  == 1 ? 'selected="selected" ' : '').' value="1">'.$this->l('Percentages').'</option>

						<option '.(Tools::getValue('discount_typefb', Configuration::get($this->name.'discount_typefb')) == 2 ? 'selected="selected" ' : '').' value="2">'.$this->l('Currency').'</option>

					</select>



				</div>



		<script type="text/javascript">

    	function selectItemsFbFacebook(id)

			{

			if(id==0){

				$("#sd-currencyfb").hide();

				$("#sd-percentagefb").show(200);

			} else {

				$("#sd-percentagefb").hide();

				$("#sd-currencyfb").show(200);

			}



			}

		</script>

				';



        if($this->_is16)

            $cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

        else

            $cur = Currency::getCurrencies();



        // Discount Amount



        $_html .= '<div id="sd-currencyfb"

    				'.(Tools::getValue('discount_typefb', Configuration::get($this->name.'discount_typefb')) == 2 ? '' : 'style="display:none" ').'>



    		<label style="font-size: 13px; font-weight: bold; color: rgb(0, 0, 0);">'.$this->l('Discount Amount:').'</label>';



        foreach ($cur AS $_cur){

            if(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency']){

                $_html .= '<div class="margin-form">

                '.(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency'] ? '<span style="font-weight: bold;font-size:12px">' : '').htmlentities($_cur['name'], ENT_NOQUOTES, 'utf-8').(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency'] ? '</span>' : '').'

    						<input type="text" name="sdamountfb['.(int)($_cur['id_currency']).']" id="sdamountfb['.(int)($_cur['id_currency']).']" value="'.Tools::getValue('sdamountfb['.(int)($_cur['id_currency']).']', Configuration::get('sdamountfb_'.(int)($_cur['id_currency']))).'"

    								style="width: 50px; text-align: right;" /> '.$_cur['sign'].'

						</div>';

            }

        }



        $_html .= '<label style="font-size: 13px; font-weight: bold; color: rgb(0, 0, 0);">'.$this->l('Tax').':</label>';



        $_html .= '<div class="margin-form">';

        $_html .= '<select style="display: block;" name="taxfb" id="taxfb">

                        <option '.(Tools::getValue('taxfb', Configuration::get($this->name.'taxfb'))  == 0 ? 'selected="selected" ' : '').' value="0">'.$this->l('Tax Excluded').'</option>

                        <option '.(Tools::getValue('taxfb', Configuration::get($this->name.'taxfb'))  == 1 ? 'selected="selected" ' : '').' value="1">'.$this->l('Tax Included').'</option>

                    </select>';

        $_html .= '</div>';



        $_html .= '<div style="clear:both"></div>';



        //$_html .= '</table>

        $_html .= '</div>



    	<div id="sd-percentagefb" '.(Tools::getValue('discount_typefb', Configuration::get($this->name.'discount_typefb'))  == 1 ? '' : 'style="display:none"').'>

    	<label style="font-size: 13px; font-weight: bold; color: rgb(0, 0, 0);">'.$this->l('Voucher percentage:').'</label>

    	<div class="margin-form">

    	<input type="text" name="percentage_valfb"



			   value="'.Tools::getValue('percentage_valfb', Configuration::get($this->name.'percentage_valfb')).'">&nbsp;%

		</div>';





        $_html .= '</div>



    	';







        $_html .= '<label>'.$this->l('Minimum checkout').':</label>



    				<div class="margin-form">

    				<input type="checkbox" value="'.(Configuration::get($this->name.'isminamountfb') == true ? 1 : 0).'"

    				name="'.$this->name.'isminamountfb" id="'.$this->name.'isminamountfb"

    				'.(Configuration::get($this->name.'isminamountfb') == true ? 'checked="checked" ' : '').'>





				</div>



		<script type="text/javascript">



		$("#'.$this->name.'isminamountfb").change(function() {

        if($(this).is(":checked")) {

            //alert("check");

            $("#'.$this->name.'isminamountfb").val($(this).is(":checked"));



            $("#fan-isminamountfb").show(200);

        } else {

        	//alert("no check");

            $("#fan-isminamountfb").hide(200);

        }

        });



    	</script>

				';



        $_html .= '<div id="fan-isminamountfb"

    				'.(Configuration::get($this->name.'isminamountfb') == true? '' : 'style="display:none" ').'>';



        $_html .= '	<div class="margin-form">

    				<table cellpadding="5" style="border: 1px solid #BBB;" border="0">

											<tr>

												<th style="width: 80px;">'.$this->l('Currency').'</th>

												<th>'.$this->l('Minimum checkout').'</th>

											</tr>';

        if($this->_is16)

            $cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

        else

            $cur = Currency::getCurrencies();

        foreach ($cur AS $_cur)

            $_html .= '<tr>

									<td>

										'.(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency'] ? '<span style="font-weight: bold;">' : '').htmlentities($_cur['name'], ENT_NOQUOTES, 'utf-8').(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency'] ? '</span>' : '').'

									</td>

									<td>

											<input type="text" name="sdminamountfb['.(int)($_cur['id_currency']).']" id="sdminamountfb['.(int)($_cur['id_currency']).']" value="'.(int)Tools::getValue('sdminamountfb['.(int)($_cur['id_currency']).']', Configuration::get('sdminamountfb_'.(int)($_cur['id_currency']))).'"

											style="width: 50px; text-align: right;" /> '.$_cur['sign'].'

									</td>

								</tr>

									';

        $_html .= '</table></div>';



        $_html .= '</div>';



        $_html .= '<br/>';



        // select categories

        $_html .= '

						<label>'.$this->l('Select categories').':</label>

    					<div class="margin-form" style="margin-bottom:20px">';



        $cat = new Category();

        $list_cat = $cat->getCategories($cookie->id_lang);



        $_html .= '<table class="table">';

        $_html .= '<tr>

						<th><input type="checkbox" onclick="checkDelBoxes(this.form, \'categoryBoxfb[]\', this.checked)" class="noborder" name="checkme"></th>

						<th>ID</th>

						<th style="width: 400px">'.$this->l('Name').'</th>

						</tr>';

        $current_cat = Category::getRootCategory()->id;

        ob_start();

        $this->recurseCategoryForInclude($list_cat, $list_cat, $current_cat,1,null,'fb');

        $cat_option = ob_get_clean();



        $_html .= $cat_option;



        $_html .= '</table>';



        $_html .= '</div>';



        // select categories



        $_html .= '<br/>';





        // Term of validity

        $_html .= '<label>'.$this->l('Term of validity').':</label>



    				<div class="margin-form">

					<input type="text" name="sdvvalidfb"  style="width: 50px"

			                		value="'.Tools::getValue('sdvvalidfb', Configuration::get($this->name.'sdvvalidfb')).'">&nbsp; Days

			         <p class="clear">'.$this->l('Voucher term of validity in days.').'</p>

				</div>';



        // Highlight

        $_html .= '<label>'.$this->l('Highlight').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="highlightfb"

							'.(Tools::getValue('highlightfb', Configuration::get($this->name.'highlightfb')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="highlightfb"

						   '.(!Tools::getValue('highlightfb', Configuration::get($this->name.'highlightfb')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				</div>';

        $_html .='<br/>';



        // Cumulative with others vouchers

        $_html .= '<label>'.$this->l('Cumulative with others vouchers').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="cumulativeotherfb"

							'.(Tools::getValue('cumulativeotherfb', Configuration::get($this->name.'cumulativeotherfb')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="cumulativeotherfb"

						   '.(!Tools::getValue('cumulativeotherfb', Configuration::get($this->name.'cumulativeotherfb')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				</div>';

        $_html .='<br/>';



        // Cumulative with price reductions

        $_html .= '<label>'.$this->l('Cumulative with price reductions').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="cumulativereducfb"

							'.(Tools::getValue('cumulativereducfb', Configuration::get($this->name.'cumulativereducfb')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="cumulativereducfb"

						   '.(!Tools::getValue('cumulativereducfb', Configuration::get($this->name.'cumulativereducfb')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				</div>';



        $_html .= '<div style="clear:both"></div>';



        $_html .= '</div>



    	';



        $_html .= '<p class="center" style="padding: 10px; margin-top: 10px;">

					<input type="submit" name="vouchersettingsfb" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';



        $_html .= '</form>';



        return $_html;

    }

    

	private function _reviewsvoucheraddreviewtab(){

    	$cookie = $this->context->cookie;

		

    	$_html = '';

    	

    	$_html .= '<h3 class="title-block-content"><i class="fa fa-reviews fa-lg"></i>&nbsp;'.$this->l('Voucher settings, when a user add review').'</h3>';

    	

    	

    	$_html .= '<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'">';

    	

    	

    	// enable or disable vouchers

    	$_html .= '<label>'.$this->l('Enable or Disable Voucher').':</label>

				<div class="margin-form">

				

					<input type="radio" value="1" id="text_list_on" name="vis_on" onclick="enableOrDisable(1)"

							'.(Tools::getValue('vis_on', Configuration::get($this->name.'vis_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t"> 

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>

					

					<input type="radio" value="0" id="text_list_off" name="vis_on" onclick="enableOrDisable(0)"

						   '.(!Tools::getValue('vis_on', Configuration::get($this->name.'vis_on')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>

					

					<p class="clear"><b style="color:red">'.$this->l('Enable or Disable Voucher, when a user add review').'</b></p>

				</div>';

    	

    	$_html .= '<script type="text/javascript">

			    	function enableOrDisable(id)

						{

						if(id==0){

							$("#block-voucher-settings").hide(200);

						} else {

							$("#block-voucher-settings").show(200);

						}

							

						}

					</script>';

    	

		$_html .= '<div id="block-voucher-settings" '.(Configuration::get($this->name.'vis_on')==1?'style="display:block"':'style="display:none"').'>';

    	$divLangName = "coupondesc";

    	

    	// Voucher Description

    	$_html .= '<label>'.$this->l('Voucher Description:').'</label>

    			

    				<div class="margin-form">';

		

    		$defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));

	    	$languages = Language::getLanguages(false);

	    	

	    	foreach ($languages as $language){

			$id_lng = (int)$language['id_lang'];

	    	$coupondesc = Configuration::get($this->name.'coupondesc'.'_'.$id_lng);

	    	

	    	

			$_html .= '	<div id="coupondesc_'.$language['id_lang'].'" 

							 style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;"

							 >



						<input type="text" style="width:400px"   

								  id="coupondesc_'.$language['id_lang'].'" 

								  name="coupondesc_'.$language['id_lang'].'" 

								  value="'.htmlentities(Tools::stripslashes($coupondesc), ENT_COMPAT, 'UTF-8').'"/>

						</div>';

	    	}

			$_html .= '';

			ob_start();

			$this->displayFlags($languages, $defaultLanguage, $divLangName, 'coupondesc');

			$displayflags = ob_get_clean();

			$_html .= $displayflags;

			$_html .= '<div style="clear:both"></div>';

			$_html .= '<p class="clear">'.$this->l('Brief description of a voucher code').'</p>';

			$_html .= '</div>';

    	// Voucher Description

    	

		// Voucher code

		$_html .= '<label>'.$this->l('Voucher code').':</label>

    			

    		<div class="margin-form">

				<input type="text" name="vouchercode" size="5" maxlength="5"

			       	   value="'.Tools::getValue('vouchercode', Configuration::get($this->name.'vouchercode')).'">

				<p class="clear">'.$this->l('Voucher code prefix. It must be at least 3 letters long. Prefix voucher code will be used in the first part of the coupon code, which the user will use to get a discount.').'</p>



			</div>';

    	// Voucher code

			

    				

		// discount type

    	$_html .= '<label>'.$this->l('Discount Type:').'</label>

    			

    				<div class="margin-form">

    				<select class="select" name="discount_type" onChange="selectItemsFb(this.selectedIndex)"

							id="discount_type">

						<option '.(Tools::getValue('discount_type', Configuration::get($this->name.'discount_type'))  == 1 ? 'selected="selected" ' : '').' value="1">'.$this->l('Percentages').'</option>

						<option '.(Tools::getValue('discount_type', Configuration::get($this->name.'discount_type')) == 2 ? 'selected="selected" ' : '').' value="2">'.$this->l('Currency').'</option>

					</select>

					

				</div>

				

		<script type="text/javascript">

    	function selectItemsFb(id)

			{

			if(id==0){

				$("#sd-currency").hide();

				$("#sd-percentage").show(200);

			} else {

				$("#sd-percentage").hide();

				$("#sd-currency").show(200);

			}

				

			}

		</script>

				';

    	

    	if($this->_is16)

			    		$cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

			    	else

			    		$cur = Currency::getCurrencies();

    	

    	// Discount Amount

    	

    	$_html .= '<div id="sd-currency" 

    				'.(Tools::getValue('discount_type', Configuration::get($this->name.'discount_type')) == 2 ? '' : 'style="display:none" ').'>

		

    		<label style="font-size: 13px; font-weight: bold; color: rgb(0, 0, 0);">'.$this->l('Discount Amount:').'</label>';

    					

    	foreach ($cur AS $_cur){

    		if(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency']){

    	$_html .= '<div class="margin-form">

                '.(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency'] ? '<span style="font-weight: bold;font-size:12px">' : '').htmlentities($_cur['name'], ENT_NOQUOTES, 'utf-8').(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency'] ? '</span>' : '').'

    						<input type="text" name="sdamount['.(int)($_cur['id_currency']).']" id="sdamount['.(int)($_cur['id_currency']).']" value="'.Tools::getValue('sdamount['.(int)($_cur['id_currency']).']', Configuration::get('sdamount_'.(int)($_cur['id_currency']))).'" 

    								style="width: 50px; text-align: right;" /> '.$_cur['sign'].'

						</div>';

    		}

    	}

    	

    	$_html .= '<label style="font-size: 13px; font-weight: bold; color: rgb(0, 0, 0);">'.$this->l('Tax').':</label>';

    	

    	$_html .= '<div class="margin-form">';

    	$_html .= '<select style="display: block;" name="tax" id="tax">

                        <option '.(Tools::getValue('tax', Configuration::get($this->name.'tax'))  == 0 ? 'selected="selected" ' : '').' value="0">'.$this->l('Tax Excluded').'</option>

                        <option '.(Tools::getValue('tax', Configuration::get($this->name.'tax'))  == 1 ? 'selected="selected" ' : '').' value="1">'.$this->l('Tax Included').'</option>

                    </select>';

    	$_html .= '</div>';

    	

    	$_html .= '<div style="clear:both"></div>';

    	

    	//$_html .= '</table>

    	$_html .= '</div>

    	

    	<div id="sd-percentage" '.(Tools::getValue('discount_type', Configuration::get($this->name.'discount_type'))  == 1 ? '' : 'style="display:none"').'>

    	<label style="font-size: 13px; font-weight: bold; color: rgb(0, 0, 0);">'.$this->l('Voucher percentage:').'</label>

    	<div class="margin-form">

    	<input type="text" name="percentage_val"

    			

			   value="'.Tools::getValue('percentage_val', Configuration::get($this->name.'percentage_val')).'">&nbsp;%

		</div>';

		



		$_html .= '</div>

			                		

    	';

		



    	

    	$_html .= '<label>'.$this->l('Minimum checkout').':</label>

    			

    				<div class="margin-form">

    				<input type="checkbox" value="'.(Configuration::get($this->name.'isminamount') == true ? 1 : 0).'"

    				name="'.$this->name.'isminamount" id="'.$this->name.'isminamount"

    				'.(Configuration::get($this->name.'isminamount') == true ? 'checked="checked" ' : '').'>



	    					

				</div>

				

		<script type="text/javascript">

		

		$("#'.$this->name.'isminamount").change(function() {

        if($(this).is(":checked")) {

            //alert("check");

            $("#'.$this->name.'isminamount").val($(this).is(":checked"));        

    	

            $("#fan-isminamount").show(200);

        } else {

        	//alert("no check");

            $("#fan-isminamount").hide(200);

        }

        });

    

    	</script>

				';

    	

    	$_html .= '<div id="fan-isminamount" 

    				'.(Configuration::get($this->name.'isminamount') == true? '' : 'style="display:none" ').'>';

		

    	$_html .= '	<div class="margin-form">

    				<table cellpadding="5" style="border: 1px solid #BBB;" border="0">

											<tr>

												<th style="width: 80px;">'.$this->l('Currency').'</th>

												<th>'.$this->l('Minimum checkout').'</th>

											</tr>';

    	if($this->_is16)

			    		$cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

			    	else

			    		$cur = Currency::getCurrencies();

		foreach ($cur AS $_cur)

					$_html .= '<tr>

									<td>

										'.(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency'] ? '<span style="font-weight: bold;">' : '').htmlentities($_cur['name'], ENT_NOQUOTES, 'utf-8').(Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency'] ? '</span>' : '').'

									</td>

									<td>

											<input type="text" name="sdminamount['.(int)($_cur['id_currency']).']" id="sdminamount['.(int)($_cur['id_currency']).']" value="'.(int)Tools::getValue('sdminamount['.(int)($_cur['id_currency']).']', Configuration::get('sdminamount_'.(int)($_cur['id_currency']))).'" 

											style="width: 50px; text-align: right;" /> '.$_cur['sign'].'

									</td>		

								</tr>	

									';

		$_html .= '</table></div>';

    	

    	$_html .= '</div>';

    	

    	$_html .= '<br/>';

    		

    	// select categories

			$_html .= '

						<label>'.$this->l('Select categories').':</label>

    					<div class="margin-form" style="margin-bottom:20px">';		

					

			$cat = new Category();

			$list_cat = $cat->getCategories($cookie->id_lang);

			

			$_html .= '<table class="table">';

			$_html .= '<tr>

						<th><input type="checkbox" onclick="checkDelBoxes(this.form, \'categoryBox[]\', this.checked)" class="noborder" name="checkme"></th>

						<th>ID</th>

						<th style="width: 400px">'.$this->l('Name').'</th>

						</tr>';

			$current_cat = Category::getRootCategory()->id;

			ob_start();

			$this->recurseCategoryForInclude($list_cat, $list_cat, $current_cat);

			$cat_option = ob_get_clean();

			

			$_html .= $cat_option;

			

			$_html .= '</table>';

			

			$_html .= '</div>';

 			

			// select categories

			

		$_html .= '<br/>';

    	

    	

    	// Term of validity

    	$_html .= '<label>'.$this->l('Term of validity').':</label>

    			

    				<div class="margin-form">

					<input type="text" name="sdvvalid"  style="width: 50px"

			                		value="'.Tools::getValue('sdvvalid', Configuration::get($this->name.'sdvvalid')).'">&nbsp; Days

			         <p class="clear">'.$this->l('Voucher term of validity in days.').'</p>

				</div>';



        // Highlight

        $_html .= '<label>'.$this->l('Highlight').':</label>

				<div class="margin-form">



					<input type="radio" value="1" id="text_list_on" name="highlight"

							'.(Tools::getValue('highlight', Configuration::get($this->name.'highlight')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t">

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>



					<input type="radio" value="0" id="text_list_off" name="highlight"

						   '.(!Tools::getValue('highlight', Configuration::get($this->name.'highlight')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>



				</div>';

        $_html .='<br/>';

    	// Cumulative with others vouchers

    	$_html .= '<label>'.$this->l('Cumulative with others vouchers').':</label>

				<div class="margin-form">

				

					<input type="radio" value="1" id="text_list_on" name="cumulativeother"

							'.(Tools::getValue('cumulativeother', Configuration::get($this->name.'cumulativeother')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t"> 

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>

					

					<input type="radio" value="0" id="text_list_off" name="cumulativeother"

						   '.(!Tools::getValue('cumulativeother', Configuration::get($this->name.'cumulativeother')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>

					

				</div>';

    	$_html .='<br/>';

    	

    	// Cumulative with price reductions

    	$_html .= '<label>'.$this->l('Cumulative with price reductions').':</label>

				<div class="margin-form">

				

					<input type="radio" value="1" id="text_list_on" name="cumulativereduc"

							'.(Tools::getValue('cumulativereduc', Configuration::get($this->name.'cumulativereduc')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_on" class="t"> 

						<img alt="'.$this->l('Enabled').'" title="'.$this->l('Enabled').'" src="../img/admin/enabled.gif">

					</label>

					

					<input type="radio" value="0" id="text_list_off" name="cumulativereduc"

						   '.(!Tools::getValue('cumulativereduc', Configuration::get($this->name.'cumulativereduc')) ? 'checked="checked" ' : '').'>

					<label for="dhtml_off" class="t">

						<img alt="'.$this->l('Disabled').'" title="'.$this->l('Disabled').'" src="../img/admin/disabled.gif">

					</label>

					

				</div>';



        $_html .= '<div style="clear:both"></div>';



        $_html .= '</div>



    	';

		

    	$_html .= '<p class="center" style="padding: 10px; margin-top: 10px;">

					<input type="submit" name="vouchersettings" value="'.$this->l('Update settings').'"

                		   class="button"  />

                	</p>';

                	

    	$_html .= '</form>';

    	

    	return $_html;

    }

    

  private function _help_documentation(){

      $_html = '';



      if(version_compare(_PS_VERSION_, '1.6', '>')){

          $_html .= '<div class="panel">



				<div class="panel-heading"><i class="fa fa-question-circle fa-lg"></i>&nbsp;'.$this->l('Help / Documentation').'</div>';

      } else {

          $_html .= '<h3 class="title-block-content">'.$this->l('Help / Documentation').'</h3>';

      }



      $_html .= '<b style="text-transform:uppercase">'.$this->l('MODULE DOCUMENTATION ').':</b>&nbsp;<a target="_blank" href="../modules/'.$this->name.'/readme.pdf" style="text-decoration:underline;font-weight:bold">readme.pdf</a>

    			<br/><br/>'.

    			'<b style="text-transform:uppercase">'.$this->l('GOOGLE RICH SNIPPETS TEST TOOL ').':</b>&nbsp;<a target="_blank" href="https://developers.google.com/structured-data/testing-tool/" style="text-decoration:underline;font-weight:bold">https://developers.google.com/structured-data/testing-tool/</a>

    			<br/><br/>'.

    			'<b style="text-transform:uppercase">'.$this->l('Hot to configure CRON FOR PRODUCT REVIEWS ').':</b>&nbsp;<a href="javascript:void(0)" onclick="tabs_custom(101)" style="text-decoration:underline;font-weight:bold">'.$this->l('CRON HELP PRODUCT REVIEWS').'</a>

    			<br/><br/>

    			<b style="text-transform:uppercase">'.$this->l('Hot to configure CRON FOR STORE REVIEWS ').':</b>&nbsp;<a href="javascript:void(0)" onclick="tabs_custom(110)" style="text-decoration:underline;font-weight:bold">'.$this->l('CRON HELP STORE REVIEWS').'</a>

    			<br/>';

      if(version_compare(_PS_VERSION_, '1.6', '>')){

          $_html .= '</div>';

      }



      $_html .= $this->_faq16();



      ## store reviews ##

      //$_html .= '<br/><br/>'.$this->_hint();

      ## store reviews ##



      ## user ##

      //$_html .= '<br/><br/>'.$this->_hintuser();

      ## user ##

      return $_html;

    }



    private function _faq16(){

        $_html  = '';



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '<div class="panel">



		<div class="panel-heading"><i class="fa fa-question-circle fa-lg"></i>&nbsp;'.$this->l('Frequently Asked Questions').'</div>';

        } else {



            $_html .= '<fieldset>

		<legend><img src="../modules/'.$this->name.'/views/img/icon/ico_help.gif" />'.$this->l('Frequently Asked Questions').'</legend>



		';

        }



        if(version_compare(_PS_VERSION_, '1.5', '>')){

            $_html .= '<div class="row">';

        }



        $_html .= $this->_faqStars17_gsnipreview();



        $_html .= '<div class="span">

							<p>

								<span style="font-weight: bold; font-size: 15px;" class="question">

									-  '.$this->l('I get 500 Internal Error, when try get Images (Avatar does not load and File upload does not work)').'.

								</span>

								<br/><br/>

								<span style="color: black;" class="answer">

									'.$this->l('Internal 500 error - this error related with setting of your server.').

            '<br/><br/>'.$this->l('- The problem may be in the access rights to the folder /modules/'.$this->name.'/ Must be 0777 or 0755').'

									<br/><br/>'.$this->l('- The problem may be in the .htaccess file in the folder /modules/ . Try delete/rename file .htaccess').'

									<br/><br/>'.$this->l('- The problem may be in the index.php file in the folder /modules/ . Try delete/rename file index.php').'





								</span>

							</p>

						</div>

					<br/><br/>';





        if(version_compare(_PS_VERSION_, '1.5', '>')){

            $_html .= '</div>';

        }



        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_html .= '</div>';

        } else {

            $_html .= '</fieldset>';

        }



        return $_html;

    }



    private function _faqStars17_gsnipreview(){

        $_html = '';

        if(version_compare(_PS_VERSION_, '1.7', '>')) {

            $_html .= '<div class="span">

                          <p>

                             <span style="font-weight: bold; font-size: 15px;" class="question">

                             	- ' . $this->l('How can I add Stars in Category and Search pages?') . '

                             </span>

                             <br/><br/>

                             <span style="color: black;" class="answer">

                             	   ' . $this->l('You just need to add a line of code in the tpl file /themes/{YOUR_CURRENT_THEME}/templates/catalog/_partials/miniatures/product.tpl.') . '

                                   <pre>{hook h=\'displayProductListReviews\' product=$product}</pre>

                              </span>

                         </p>

                       </div><br/><br/>';

        }

        return $_html;

    }

    

 private function _welcome(){

     $data = '';

     if(version_compare(_PS_VERSION_, '1.6', '>')){

         $data .= '<div class="panel">



			<div class="panel-heading"><i class="fa fa-home fa-lg"></i>&nbsp;'.$this->l('Welcome').'</div>';

     } else {

         $data .= '<h3 class="title-block-content">'.$this->l('Welcome').'</h3>';

     }

 		

		$data .= $this->l('Welcome and thank you for purchasing the module.').

    			'<br/><br/>'

    			.$this->l('To configure module please read').'&nbsp;<b><a style="text-decoration:underline" id="tab-menu-5" onclick="tabs_custom(6)" href="javascript:void(0)">'.$this->l('Help / Documentation').'</a></b>

    			<br/><br/>';

     if(version_compare(_PS_VERSION_, '1.6', '>')){

         $data .= '</div>';

     }

    	return  $data;

 	

    }

    

 public function _headercssfiles(){

		$_html = '';

    

 		if(version_compare(_PS_VERSION_, '1.6', '>')){

    	$_html .=  '<link rel="stylesheet" media="screen" type="text/css" href="../modules/'.$this->name.'/views/css/prestashop16.css" />';

    		

    	}



        $_html .=  '<link rel="stylesheet" media="screen" type="text/css" href="../modules/'.$this->name.'/views/css/font-custom.min.css" />';



		// menu

    	$_html .= '<link rel="stylesheet" href="../modules/'.$this->name.'/views/css/menu.css" type="text/css" />';

    	$_html .= '<script type="text/javascript" src="../modules/'.$this->name.'/views/js/menu.js"></script>';

        $_html .= '<script type="text/javascript" src="../modules/'.$this->name.'/views/js/reminder.js"></script>';





     if(version_compare(_PS_VERSION_, '1.5', '<')){



            $_html .= '<link href="../modules/'.$this->name.'/backward_compatibility/datepicker14/css/jquery.ui.theme.css" rel="stylesheet" type="text/css" media="all" />

						<link href="../modules/'.$this->name.'/backward_compatibility/datepicker14/css/jquery.ui.core.css" rel="stylesheet" type="text/css" media="all" />

						<link href="../modules/'.$this->name.'/backward_compatibility/datepicker14/css/jquery.ui.datepicker.css" rel="stylesheet" type="text/css" media="all" />';



         $_html .= '<script type="text/javascript" src="../modules/'.$this->name.'/backward_compatibility/datepicker14/js/jquery.ui.core.min.js"></script>

						<script type="text/javascript" src="../modules/'.$this->name.'/backward_compatibility/datepicker14/js/jquery.ui.datepicker.min.js"></script>

						<script type="text/javascript" src="../modules/'.$this->name.'/backward_compatibility/datepicker14/js/jquery.ui.datepicker-en.js"></script>';





            }





     if(version_compare(_PS_VERSION_, '1.5', '>') && version_compare(_PS_VERSION_, '1.6', '<')){

         $_html .= '<link href="'._PS_BASE_URL_SSL_.__PS_BASE_URI__.'js/jquery/ui/themes/base/jquery.ui.theme.css" rel="stylesheet" type="text/css" media="all" />

						<link href="'._PS_BASE_URL_SSL_.__PS_BASE_URI__.'js/jquery/ui/themes/base/jquery.ui.core.css" rel="stylesheet" type="text/css" media="all" />

						<link href="'._PS_BASE_URL_SSL_.__PS_BASE_URI__.'js/jquery/ui/themes/base/jquery.ui.datepicker.css" rel="stylesheet" type="text/css" media="all" />';



         $_html .= '<script type="text/javascript" src="'._PS_BASE_URL_SSL_.__PS_BASE_URI__.'js/jquery/ui/jquery.ui.core.min.js"></script>

						<script type="text/javascript" src="'._PS_BASE_URL_SSL_.__PS_BASE_URI__.'js/jquery/ui/jquery.ui.datepicker.min.js"></script>

						<script type="text/javascript" src="'._PS_BASE_URL_SSL_.__PS_BASE_URI__.'js/jquery/ui/i18n/jquery.ui.datepicker-en.js"></script>';





     }





     ## autocompelete ###

     if(version_compare(_PS_VERSION_, '1.5', '<')) {

         $_html .= '<script type="text/javascript">



					var formProduct;



					var accessories = new Array();



					</script>';



         $_html .= '<link rel="stylesheet" type="text/css" href="'._PS_BASE_URL_SSL_.__PS_BASE_URI__.'css/jquery.autocomplete.css" />



			<script type="text/javascript" src="/js/jquery/jquery.autocomplete.js"></script>';

     } elseif(version_compare(_PS_VERSION_, '1.5', '>') && version_compare(_PS_VERSION_, '1.6', '<')) {



         $_html .= '<link rel="stylesheet" type="text/css" href="'._PS_BASE_URL_SSL_.__PS_BASE_URI__.'js/jquery/plugins/autocomplete/jquery.autocomplete.css" />



			<script type="text/javascript" src="'._PS_BASE_URL_SSL_.__PS_BASE_URI__.'js/jquery/plugins/autocomplete/jquery.autocomplete.js"></script>';

     }

     ### autocomplete ###





     $_html .= '<link rel="stylesheet" href="../modules/'.$this->name.'/views/css/gsnipreview.css" type="text/css" />';

     $_html .= '<script type="text/javascript" src="../modules/'.$this->name.'/views/js/gsnipreview-admin.js"></script>';

     $_html .= '<script type="text/javascript" src="../modules/'.$this->name.'/views/js/r_stars.admin.js"></script>';





     $_html .= '<link rel="stylesheet" href="../modules/'.$this->name.'/views/css/gsnipreview-old.css" type="text/css" />';











     switch(Configuration::get($this->name.'stylestars')){

         case 'style1':

             $stylecolor = '#F7B900';

             break;

         case 'style2':

             $stylecolor = '#7BE408';

             break;

         case 'style3':

             $stylecolor = '#00ABEC';

             break;

         default:

             $stylecolor = '#F7B900';

             break;

     }



     $_html .= '<style type="text/css">

					.pages { height:15px; font-size:100%; margin-top:20px; line-height:1.2em;  }

					.pages span, .pages b, .pages a { font-weight:bold; }

					.pages a{color:#fff}

					.pages span { padding:1px 8px 2px 0; }

					.pages span.nums { padding:0 10px 0 5px; }

					.pages span.nums b, .pages span.nums a { padding:0px 4px 1px; background:#ccc; text-decoration:none; margin-right:4px; }

				    .pages span.nums a:hover { background: '.$stylecolor.'; color:#fff; }

					.pages span.nums b { color:#fff; background:'.$stylecolor.'}

					</style>';







     ### store reviews ##

     $_html .= '<link rel="stylesheet" href="../modules/'.$this->name.'/views/css/colorpicker.css" type="text/css" />';

     $_html .=  '<link rel="stylesheet" media="screen" type="text/css" href="../modules/'.$this->name.'/views/css/layout.css" />';

     $_html .= '<script type="text/javascript" src="../modules/'.$this->name.'/views/js/colorpicker.js"></script>';

     $_html .= '<script type="text/javascript" src="../modules/'.$this->name.'/views/js/eye.js"></script>';

     $_html .= '<script type="text/javascript" src="../modules/'.$this->name.'/views/js/utils.js"></script>';

     $_html .= '<script type="text/javascript" src="../modules/'.$this->name.'/views/js/layout.js?ver=1.0.2"></script>';



     $_html .= '<link rel="stylesheet" href="../modules/'.$this->name.'/views/css/admin.css" type="text/css" />';

     $_html .= '<script type="text/javascript" src="../modules/'.$this->name.'/views/js/storereviews.js"></script>';

     $_html .= '<script type="text/javascript" src="../modules/'.$this->name.'/views/js/reminder-storereviews.js"></script>';



     $_html .= '<script type="text/javascript" src="../modules/'.$this->name.'/views/js/storereviews-admin.js"></script>';



     ## store reviews ##





    	return $_html;

	}



    public function renderUserAccount(){

        return $this->display(__FILE__.'/gsnipreview.php', 'views/templates/front/useraccount.tpl');

    }



    public function renderUser(){

        return $this->display(__FILE__.'/gsnipreview.php', 'views/templates/front/user.tpl');

    }





    public function renderUsers(){

        return $this->display(__FILE__.'/gsnipreview.php', 'views/templates/front/users.tpl');

    }





    public function renderMyStoreReviews(){

        return $this->display(__FILE__.'/gsnipreview.php', 'views/templates/front/my-storereviews.tpl');

    }

	

	public function renderMyReviews(){

		return $this->display(__FILE__.'/gsnipreview.php', 'views/templates/front/my-reviews.tpl');

	}

	



    public function renderReviewAbuseForm(){

        return Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/review-abuse-form.tpl');

    }



    public function renderReviewAbuseAdmin(){

        return Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/review-abuse-admin.tpl');

    }



    public function renderReviewChangedAdmin(){

        return Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/review-changed-admin.tpl');

    }



    public function renderReviewChangedMy(){

        return Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/review-changed-my.tpl');

    }



    public function renderReviewError(){

        return Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/error.tpl');

    }



    public function renderReviewFacebookSuccess(){

        return Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/success.tpl');

    }



    public function renderReviewCoupon(){

        return Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/coupon.tpl');

    }



    public function renderReviewFacebookSuggestion(){

        return Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/coupon-suggestion.tpl');

    }



    public function renderError(){

        return Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/error.tpl');

    }



    public function renderSuccess(){

        return Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/success.tpl');

    }



    public function renderTplItems(){

        return Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/front/storereviews.tpl');

    }











	public function translateCustom(){

		$cookie = $this->context->cookie;





		

			// set discount

    		switch (Configuration::get($this->name.'discount_type'))

			{

				case 1:

					// percent

					$id_discount_type = 1;

					$value = Configuration::get($this->name.'percentage_val');

					break;

				case 2:

					// currency

					$id_discount_type = 2;

					$id_currency = (int)$cookie->id_currency;

					$value = Configuration::get('sdamount_'.(int)$id_currency);

				break;

				default:

					$id_discount_type = 2;

					$id_currency = (int)$cookie->id_currency;

					$value = Configuration::get('sdamount_'.(int)$id_currency);

			}

			$valuta = "%";

            $tax = '';







            if($id_discount_type == 2){

				if($this->_is16)

			    		$cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

			    	else

			    		$cur = Currency::getCurrencies();	

				foreach ($cur AS $_cur){

		    	if($id_currency == $_cur['id_currency']){

		    			$valuta = $_cur['sign'];

		    		}

		    	}

                $tax = (int)Configuration::get($this->name.'tax');

			}



            /* social share coupon */

            // set discount

            switch (Configuration::get($this->name.'discount_typefb'))

            {

                case 1:

                    // percent

                    $id_discount_type = 1;

                    $valuefb = Configuration::get($this->name.'percentage_valfb');

                    break;

                case 2:

                    // currency

                    $id_discount_type = 2;

                    $id_currency = (int)$cookie->id_currency;

                    $valuefb = Configuration::get('sdamountfb_'.(int)$id_currency);

                    break;

                default:

                    $id_discount_type = 2;

                    $id_currency = (int)$cookie->id_currency;

                    $valuefb = Configuration::get('sdamountfb_'.(int)$id_currency);

            }

            $valutafb = "%";

            $taxfb = '';



            if($id_discount_type == 2){

                if($this->_is16)

                    $cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

                else

                    $cur = Currency::getCurrencies();

                foreach ($cur AS $_cur){

                    if($id_currency == $_cur['id_currency']){

                        $valutafb = $_cur['sign'];

                    }

                }

                $taxfb = (int)Configuration::get($this->name.'taxfb');

            }

            /* social share coupon */

			

		return array('page'=>$this->l('Page'),'reviews'=>$this->l('reviews'),'review'=>$this->l('review'),

					 'firsttext' => $this->l('You get voucher for discount'),

					 'secondtext' => $this->l('Here is you voucher code'),

					 'threetext' => $this->l('It is valid until'),



					 'discountvalue' => $value.$valuta,

                     'discountvaluefb' => $valuefb.$valutafb,



                     'valutafb'=>$valutafb,

                     'valuta' => $valuta,



                     'tax'=>$tax,

                     'taxfb'=>$taxfb,



					 'category' => $this->l('Category'),

					 'product'=> $this->l('Product'),

			         'rate_post'=>$this->l('Rate / post a review for this product on'),

					 'sent_cron_items'=>$this->l('Number of cron items sent'),

					 'delete_cron_items'=>$this->l('Number of cron items deleted'),

					 'no_sent_items'=>$this->l('No tasks sent'),

					 'review_reminder'=>$this->l('Please Enable Review reminder email to customers in admin panel'),





		  		     'review_text_voucher' => $this->l('submit a review'),



                     'facebook_text_voucher' => $this->l('share review on Facebook'),



                     'tax_included' => $this->l('Tax Included'),

                     'tax_excluded' => $this->l('Tax Excluded'),

                     'valid_day' => $this->l('day'),

                     'valid_days' => $this->l('days'),







					 'title'=>$this->l('Title'),

                     'review'=>$this->l('Review'),

                     'reg_customer'=>$this->l('Registered customer'),

                     'no_reg_customer'=>$this->l('Not registered customer'),





                     'helpfull_exists'=>$this->l('You have already voted.'),

                     'helpfull_success' => $this->l('Thank you for your feedback.'),



                     'coupon_success' => $this->l('Thank you for sharing this review on Facebook'),



                     'coupon_suggestion_title' => $this->l('Share your review on Facebook'),

                     'coupon_suggestion_msg' => $this->l('To share your review, simply use the Like button below your review on the product page'),

                     'already_get_coupon' => $this->l('You have already received a coupon with discount'),

                     'expiried_voucher' => $this->l('The validity of your coupon for discount has expired'),

                     'used_voucher' => $this->l('This voucher has already been used'),



                     'all_reviews_meta_title' => $this->l('All Reviews'),

                     'all_reviews_meta_description' => $this->l('All Reviews'),

                     'all_reviews_meta_keywords' => $this->l('All Reviews'),



                     'my_reviews_meta_title' => $this->l('Product Reviews'),

                     'my_reviews_meta_description' => $this->l('Product Reviews'),

                     'my_reviews_meta_keywords' => $this->l('Product Reviews'),



                     'pending_review' => $this->l('The customer has rated the product but has not posted a review, or the review is pending moderation'),



                    'error_login' => $this->l('You must be a registered customer!'),



                    'orders_date_empty' => $this->l('The date is still empty, you should select a date first'),

                    'orders_date_start_more_end' => $this->l('Start date more than end date'),

                    'orders_date_not_exists' => $this->l('There are no orders to import'),

                    'orders_date_ok1' => $this->l('You have successfully imported your'),

                    'orders_date_ok2' => $this->l('old orders'),



                    'google_reviews_title'=>$this->l('Reviews Aggregator'),



                    'myr_msg1'=>$this->l('now active'),

                    'myr_msg2'=>$this->l('now inactive'),

                    'myr_msg3'=>$this->l('Settings updated'),

                    'myr_msg4'=>$this->l("e-mail reminders are"),



                    'ptc_msg1'=>$this->l('Please, enter the security code'),

                    'ptc_msg2'=>$this->l('Please, enter the text'),

                    'ptc_msg3'=>$this->l('Please, enter the name'),

                    'ptc_msg4'=>$this->l('Please, enter the email'),

                    'ptc_msg5'=>$this->l('Please, enter the title'),

                    'ptc_msg6'=>$this->l('Please, choose the rating for'),

                    'ptc_msg7'=>$this->l('Please, choose the rating'),

                    'ptc_msg8'=>$this->l('You entered the wrong security code'),

                    'ptc_msg9'=>$this->l('Please enter a valid email address. For example johndoe@domain.com.'),

                    'ptc_msg10'=>$this->l('You have already add review for this product'),

                    'ptc_msg11'=>$this->l('chars'),

                    'ptc_msg12'=>$this->l('Min'),



                    'ptc_msg13_1'=>$this->l('You can upload a maximum of'),

                    'ptc_msg13_2'=>$this->l('files'),



                    'ava_msg8'=>$this->l('Invalid file type, please try again!'),

                    'ava_msg9'=>$this->l('Wrong file format, please try again!'),



                    'raad_msg1'=>$this->l('Review is NOT Abusive.'),



                    'raf_msg5'=>$this->l('Your report has been taken into account and the merchant has been warned by e-mail.'),

                    'raf_msg8'=>$this->l('You cannot send report for this review because somebody has already posted a report.'),



                    'rca_msg1'=>$this->l('Please, enter your suggestion text'),

                    'rca_msg2'=>$this->l('The changed customer review is pending modification.'),



                    'rcmy_msg5'=>$this->l('Review already changed.'),





                    //for reminder



                    'review_reminder'=>$this->l('Please Enable Customer Reminder in admin panel'),

                    'review_reminder_second'=>$this->l('Please Enable review reminder email to customers a second time in admin panel'),

                    'review_reminder_customer_txt'=>$this->l('Customer already write review in shop'),

                    'review_reminder_customer'=>$this->l('Please Enable Send a review reminder by email to customer when customer already write review in shop in admin panel'),

                    'category' => $this->l('Category'),

                    'product'=> $this->l('Product'),

                    'rate_post'=>$this->l('Rate / post a review for this product on'),

                    'sent_cron_items'=>$this->l('Number of items sent'),

                    'delete_cron_items'=>$this->l('Number of items deleted'),

                    'no_sent_items'=>$this->l('No tasks sent'),

                    'sent_request'=>$this->l('The reviews requests emails have been sent for following orders'),

                    'subject_success_sent_email'=>$this->l('The emails requests on the reviews was successfully sent'),

                    'accepted_order_statuses' => $this->l('Accepted order statuses'),

                    'configure_order_statuses'=>$this->l('Configure order statuses here'),

                    'customer_reminder_settings'=>$this->l('Customer Reminder settings'),

                    'customer_reminder_error1_1'=>$this->l('Have passed less'),

                    'customer_reminder_error1_2'=>$this->l('days from Adding date'),

                    'customer_reminder_error2_2'=>$this->l('days after the first sending'),

                    'configure_reminder_delay_first'=>$this->l('Configure Delay for sending reminder by email here'),

                    'configure_reminder_delay_second'=>$this->l('Configure Days after the first emails were sent here'),



                    //for reminder





                    // for CSV



                    'A_name'=>$this->l('Language ID'),

                    'A_example'=>'<b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('1'),

                    'B_name'=>$this->l('Rating'),

                    'B_example'=>'<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(1/5).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('5'),

                    'C_name'=>$this->l('Product ID'),

                    'C_example'=>'<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(1,2,3, .. etc).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('1'),

                    'D_name'=>$this->l('Customer ID'),

                    'D_example'=>'<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(0 - Guest; 1,2,3, .. etc - Customer).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('1'),

                    'E_name'=>$this->l('Customer full name'),

                    'E_example'=>'<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(need fill, required, if Customer ID = 0).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('John Doe'),

                    'F_name'=>$this->l('Customer email'),

                    'F_example'=>'<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(need fill, required, if Customer ID = 0).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('john@doe.com'),

                    'G_name'=>$this->l('Title'),

                    'G_example'=>'<b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('Title review'),

                    'H_name'=>$this->l('Message'),

                    'H_example'=>'<b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('Test message'),

                    'I_name'=>$this->l('Admin Response'),

                    'I_example'=>'<b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('Test response by admin'),

                    'J_name'=>$this->l('Display "Admin response" on the site'),

                    'J_example'=>'<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(0 or 1).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('1'),

                    'K_name'=>$this->l('Date Add'),

                    'K_example'=> '<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(dd/mm/yyyy).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.date("d/m/Y"),

                    'L_name'=>$this->l('Status'),

                    'L_example'=>'<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(0 or 1).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('1'),



                    // for CSV



                    // user

                    'meta_title_shoppers'=>$this->l('All Users'),

                    'meta_description_shoppers'=>$this->l('All Users'),

                    'meta_keywords_shoppers'=>$this->l('All Users'),

                    'profile'=>$this->l('profile'),

                    'meta_title_myaccount'=>$this->l('User Profile'),

                    'meta_description_myaccount'=>$this->l('User Profile'),

                    'meta_keywords_myaccount'=>$this->l('User Profile'),



                    'male'=>$this->l('Male'),

                    'female'=>$this->l('Female'),

                    // user



        );

	}





    public function translateItems(){



        return array('page'=>$this->l('Page'),

            'subject'=>$this->l('New Store review from Your Customer'),

            'subject_response'=>$this->l('Response on the Store review'),

            'company'=>$this->l('Company'),

            'address'=>$this->l('Address'),

            'country'=>$this->l('Country'),

            'city'=>$this->l('City'),

            'web'=>$this->l('Web address'),

            'message'=>$this->l('Message'),



            'subject_thank_you'=>$this->l('Thank you for your review'),



            //for reminder



            'review_reminder'=>$this->l('Please Enable Review reminder email to customers in admin panel'),

            'review_reminder_second'=>$this->l('Please Enable review reminder email to customers a second time in admin panel'),

            'review_reminder_customer_txt'=>$this->l('Customer already write review in shop'),

            'review_reminder_customer'=>$this->l('Please Enable Send a review reminder by email to customer when customer already write review in shop in admin panel'),

            'category' => $this->l('Category'),

            'product'=> $this->l('Product'),

            'rate_post'=>$this->l('Rate / post a review for this product on'),

            'sent_cron_items'=>$this->l('Number of items sent'),

            'delete_cron_items'=>$this->l('Number of items deleted'),

            'no_sent_items'=>$this->l('No tasks sent'),

            'sent_request'=>$this->l('The reviews requests emails have been sent for following orders'),

            'subject_success_sent_email'=>$this->l('The emails requests on the reviews was successfully sent'),

            'accepted_order_statuses' => $this->l('Accepted order statuses'),

            'configure_order_statuses'=>$this->l('Configure order statuses here'),

            'customer_reminder_settings'=>$this->l('Customer Reminder settings'),

            'customer_reminder_error1_1'=>$this->l('Have passed less'),

            'customer_reminder_error1_2'=>$this->l('days from Adding date'),

            'customer_reminder_error2_2'=>$this->l('days after the first sending'),

            'configure_reminder_delay_first'=>$this->l('Configure Delay for sending reminder by email here'),

            'configure_reminder_delay_second'=>$this->l('Configure Days after the first emails were sent here'),



            //for reminder



            'orders_date_empty' => $this->l('The date is still empty, you should select a date first'),

            'orders_date_start_more_end' => $this->l('Start date more than end date'),

            'orders_date_not_exists' => $this->l('There are no orders to import'),

            'orders_date_ok1' => $this->l('You have successfully imported your'),

            'orders_date_ok2' => $this->l('old orders'),



            'meta_title_testimonials'=>$this->l('Store reviews'),

            'meta_description_testimonials'=>$this->l('Store reviews'),

            'meta_keywords_testimonials'=>$this->l('Store reviews'),

            'msg1'=>$this->l('Please, choose the rating.'),

            'msg2'=>$this->l('Please, enter the Name.'),

            'msg3'=>$this->l('Please, enter the Email.'),

            'msg4'=>$this->l('Please, enter the Message.'),

            'msg5'=>$this->l('Please, enter the security code.'),

            'msg6'=>$this->l('Please enter a valid email address. For example johndoe@domain.com.'),

            'msg7'=>$this->l('You entered the wrong security code.'),

            'msg8'=>$this->l('Invalid file type, please try again!'),

            'msg9'=>$this->l('Wrong file format, please try again!'),



            // for CSV



            'A_name'=>$this->l('Language ID'),

            'A_example'=>'<b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('1'),

            'B_name'=>$this->l('Rating'),

            'B_example'=>'<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(1/5).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('5'),

            'C_name'=>$this->l('Customer ID'),

            'C_example'=>'<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(0 - Guest; 1,2,3, .. etc - Customer).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('1'),

            'D_name'=>$this->l('Customer full name'),

            'D_example'=>'<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(need fill, required, if Customer ID = 0).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('John Doe'),

            'E_name'=>$this->l('Customer email'),

            'E_example'=>'<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(need fill, required, if Customer ID = 0).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('john@doe.com'),

            'F_name'=>$this->l('Message'),

            'F_example'=>'<b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('Test message'),

            'G_name'=>$this->l('Admin Response'),

            'G_example'=>'<b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('Test response by admin'),

            'H_name'=>$this->l('Display "Admin response" on the site'),

            'H_example'=>'<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(0 or 1).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('1'),

            'I_name'=>$this->l('Date Add'),

            'I_example'=> '<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(dd/mm/yyyy).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.date("d/m/Y"),

            'J_name'=>$this->l('Status'),

            'J_example'=>'<b>'.$this->l('Format').'</b>:&nbsp;'.$this->l('(0 or 1).').'<br/><br/><b>'.$this->l('Example').'</b>:&nbsp;'.$this->l('1'),



            // for CSV





        );



    }

	

	

	public function hookNewOrder($params)

	{

		return (

			$this->hookOrderConfirmation($params)

		);

	}

	

	public function hookActionValidateOrder($params)

	{

		return (

			$this->hookOrderConfirmation($params)

		);

	}





    public function hookOrderConfirmation($params){



        $this->collectOrdersForProductReviews($params);



        $this->collectOrdersForShopReviews($params);



        return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/orderconfirmation.tpl');

    }





    private function collectOrdersForShopReviews($params){



        $cookie = $this->context->cookie;





        if(version_compare(_PS_VERSION_, '1.7', '>')){

            $params_obj_order = $params['order'];

        } else {

            $params_obj_order = $params['objOrder'];

        }



        if (!empty($params_obj_order) && is_object($params_obj_order))

        {

            include_once(dirname(__FILE__).'/classes/featureshelptestim.class.php');

            $obj = new featureshelptestim();



            $guest = false;



            // check if customer is guest

            if (version_compare(_PS_VERSION_, '1.4', '>')) {

                $customer = new Customer($params_obj_order->id_customer);



                if (Validate::isLoadedObject($customer)) {

                    $guest = $customer->isGuest();

                }

                unset($customer);

            }



            if (false === $obj->isDataExist(

                    array('id_shop'=>$this->_id_shop,

                        'order_id'=>$params_obj_order->id

                    )

                )

                &&

                empty($guest)

            ) {



                if (Configuration::get($this->name.'reminder'.$this->_prefix_shop_reviews) && isset($params_obj_order->id_customer) &&

                    is_numeric($params_obj_order->id_customer)

                ) {



                    $status  = $obj->getStatus(

                        array('id_shop'=>$this->_id_shop,

                            'customer_id'=> $params_obj_order->id_customer

                        )

                    );



                    if (false === $status) {

                        $obj->addStatus(

                            array('id_shop'=>$this->_id_shop,

                                'customer_id'=> $params_obj_order->id_customer,

                                'status'=>1

                            )

                        );



                        $add_status = 1;

                    } else {

                        $add_status  = $status;

                    }



                    if (!empty($add_status)) {

                        $id_lang = $cookie->id_lang;

                        $products = $obj->getProductsInOrder(

                            array('order_id'=>$params_obj_order->id,

                                'id_lang' => $id_lang

                            )

                        );

                        //echo "<pre>"; var_dump($products);exit;



                        if (!empty($products)) {

                            $data = array();

                            foreach ($products as $product) {



                                /*$product['rate'] = 0;

                                $attributes = Product::getProductProperties($id_lang, $product);



                                if(version_compare(_PS_VERSION_, '1.6', '>')) {

                                    $link = Context::getContext()->link;

                                    $product_obj = new Product($attributes['id_product']);

                                    //var_dump($product_obj->id);exit;

                                    $product_url = $link->getProductLink((int)$product_obj->id, null, null, null,$id_lang, null, 0, false);

                                } else {

                                    $product_url = $attributes['link'];

                                }





                                $data[] = array('title' => $attributes['name'],

                                    'category' => $attributes['category'],

                                    'link' => $product_url,

                                    'id_lang' => $id_lang,

                                    'id_product' =>$attributes['id_product'],

                                );







                                unset($attributes);*/



                                $product['rate'] = 0;

                                $attributes = Product::getProductProperties($id_lang, $product);

                                $data[] = array('title' => $attributes['name'],

                                    'category' => $attributes['category'],

                                    'link' => $attributes['link'],

                                    'id_lang' => $id_lang,

                                    'id_product' =>(int)$attributes['id_product'],

                                );



                                unset($attributes);

                            }



                            $obj->saveOrder(

                                array('id_shop'=>$this->_id_shop,

                                    'order_id' => $params_obj_order->id,

                                    'customer_id' => $params_obj_order->id_customer,

                                    'data' => $data

                                )

                            );



                            unset($data);

                        }

                    }

                }

            }

        }

    }



    private function collectOrdersForProductReviews($params){

        $cookie = $this->context->cookie;





        if(version_compare(_PS_VERSION_, '1.7', '>')){

            $params_obj_order = $params['order'];

        } else {

            $params_obj_order = $params['objOrder'];

        }





        if (!empty($params_obj_order) && is_object($params_obj_order))

        {

            include_once(dirname(__FILE__).'/classes/featureshelp.class.php');

            $obj = new featureshelp();



            $guest = false;



            // check if customer is guest

            if (version_compare(_PS_VERSION_, '1.4', '>')) {

                $customer = new Customer($params_obj_order->id_customer);



                if (Validate::isLoadedObject($customer)) {

                    $guest = $customer->isGuest();

                }

                unset($customer);

            }







            if (false === $obj->isDataExist(

                    array('id_shop'=>$this->_id_shop,

                        'order_id'=>$params_obj_order->id

                    )

                )

                &&

                empty($guest)

            ) {





                if (Configuration::get($this->name.'reminder') && isset($params_obj_order->id_customer) &&

                    is_numeric($params_obj_order->id_customer)

                ) {



                    $status  = $obj->getStatus(

                        array('id_shop'=>$this->_id_shop,

                            'customer_id'=> $params_obj_order->id_customer

                        )

                    );



                    if (false === $status) {

                        $obj->addStatus(

                            array('id_shop'=>$this->_id_shop,

                                'customer_id'=> $params_obj_order->id_customer,

                                'status'=>1

                            )

                        );



                        $add_status = 1;

                    } else {

                        $add_status  = $status;

                    }



                    if (!empty($add_status)) {

                        $id_lang = $cookie->id_lang;

                        $products = $obj->getProductsInOrder(

                            array('order_id'=>$params_obj_order->id,

                                'id_lang' => $id_lang

                            )

                        );

                        //echo "<pre>"; var_dump($products);exit;



                        if (!empty($products)) {

                            $data = array();

                            foreach ($products as $product) {



                                $product['rate'] = 0;

                                $attributes = Product::getProductProperties($id_lang, $product);

                                $data[] = array('title' => $attributes['name'],

                                    'category' => $attributes['category'],

                                    'link' => $attributes['link'],

                                    'id_lang' => $id_lang,

                                    'id_product' =>$attributes['id_product'],

                                );



                                unset($attributes);

                            }



                            //echo "<pre>"; var_dump($data);exit;

                            $obj->saveOrder(

                                array('id_shop'=>$this->_id_shop,

                                    'order_id' => $params_obj_order->id,

                                    'customer_id' => $params_obj_order->id_customer,

                                    'data' => $data

                                )

                            );



                            unset($data);

                        }

                    }

                }

            }

        }

    }

	

	

	public function hookLeftColumn($params)

	{





            $smarty = $this->context->smarty;

            $this->setLastReviewsBlockSettings(array('custom_page_name'=>'leftcol'));



            ## badges ###

            $this->badges(array('custom_page_name'=>'leftcol'));

            ## badges ###







			$_product_id = Tools::getValue("id_product");

			if(Configuration::get($this->name.'gsnipblock') &&

			  (Configuration::get($this->name.'id_hook_gsnipblock') == 2) &&

			  $_product_id &&

			  Configuration::get($this->name.'svis_on')

			  ){

					$smarty->assign($this->name.'leftsnippet', 

									$this->snippetBlockSettings(array('product_id'=>$_product_id,'params'=>$params))

									);

			  } else {

			  	$smarty->assign($this->name.'leftsnippet','');

			  }







			// pinterest

            $smarty->assign($this->name.'id_productpin', $_product_id);

			$smarty->assign($this->name.'pinvis_on', Configuration::get($this->name.'pinvis_on'));

			$smarty->assign($this->name.'pinterestbuttons', Configuration::get($this->name.'pinterestbuttons'));

			$smarty->assign($this->name.'_leftColumn', Configuration::get($this->name.'_leftColumn'));

			// pinterest





            ## user profile ##

            if(Configuration::get($this->name . $this->_prefix_review . 'adv_left') == 1) {

                $this->setuserprofilegSettings();

            }

            $smarty->assign($this->name . $this->_prefix_review . 'adv_left', Configuration::get($this->name . $this->_prefix_review . 'adv_left'));

            ## user profile ##









            ## store reviews ##

            if(Configuration::get($this->name . 't_left') == 1) {

                $this->setStoreReviewsColumnsSettings();

            }

            $smarty->assign($this->name . 't_left', Configuration::get($this->name . 't_left'));

            ## store reviews ##



			return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/left.tpl');



				

	}

	

	public function hookRightColumn($params)

	{





            $smarty = $this->context->smarty;

            $this->setLastReviewsBlockSettings(array('custom_page_name'=>'rightcol'));



            ## badges ###

            $this->badges(array('custom_page_name'=>'rightcol'));

            ## badges ###

			

			

			$_product_id = Tools::getValue("id_product");

			if(Configuration::get($this->name.'gsnipblock') &&

			  (Configuration::get($this->name.'id_hook_gsnipblock') == 1) &&

			  $_product_id &&

			  Configuration::get($this->name.'svis_on')

			  ){

					$smarty->assign($this->name.'rightsnippet', 

									$this->snippetBlockSettings(array('product_id'=>$_product_id,'params'=>$params))

									);

			  } else {

			  	$smarty->assign($this->name.'rightsnippet','');

			  }

			

			// pinterest

            $smarty->assign($this->name.'id_productpin', $_product_id);

			$smarty->assign($this->name.'pinvis_on', Configuration::get($this->name.'pinvis_on'));

			$smarty->assign($this->name.'pinterestbuttons', Configuration::get($this->name.'pinterestbuttons'));

			$smarty->assign($this->name.'_rightColumn', Configuration::get($this->name.'_rightColumn'));

			// pinterest 





            ## user profile ##

            if(Configuration::get($this->name . $this->_prefix_review . 'adv_right') == 1) {

                $this->setuserprofilegSettings();

            }

            $smarty->assign($this->name . $this->_prefix_review . 'adv_right', Configuration::get($this->name . $this->_prefix_review . 'adv_right'));

            ## user profile ##





            ## store reviews ##

            if(Configuration::get($this->name . 't_right') == 1) {

                $this->setStoreReviewsColumnsSettings();

            }

            $smarty->assign($this->name . 't_right', Configuration::get($this->name . 't_right'));

            ## store reviews ##



			return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/right.tpl');



				

	}





    public function badges($data){



        $custom_page_name = $data['custom_page_name'];



        $smarty = $this->context->smarty;

        $cookie = $this->context->cookie;

        $id_lang = (int)$cookie->id_lang;

        ## badges ##

        $allinfo_on = Configuration::get($this->name.'allinfo_on');

        $svis_on = Configuration::get($this->name.'svis_on');

        $data_badges = array();

        $name = '';

        $rev_all = '';



        ## home page ##



        ## prestashop 1.7 ##

        if(version_compare(_PS_VERSION_, '1.7', '>')){

            $page_name = $smarty->tpl_vars['page']->value['page_name'];

        ## prestashop 1.7 ##

        } else {

            if (defined('_MYSQL_ENGINE_')) {

                $page_name = $smarty->tpl_vars['page_name']->value;

            } else {

                $page_name = $smarty->_tpl_vars['page_name'];

            }

        }

        $is_home = 0;

        if ($page_name == 'index') {

            $is_home = 1;

        }

        ## home page ##

        $id_manufacturer = (int)Tools::getValue('id_manufacturer');



        $id_supplier = (int)Tools::getValue('id_supplier');

        $id_category = (int)Tools::getValue('id_category');



        include_once(dirname(__FILE__) . '/classes/gsnipreviewhelp.class.php');

        $obj = new gsnipreviewhelp();



        if($allinfo_on && $svis_on) {





            if($id_supplier || $id_category || $is_home || $id_manufacturer) {





                $data_badges = $obj->badges(array('id_supplier' => $id_supplier, 'id_category' => $id_category, 'id_manufacturer'=>$id_manufacturer));



                if($data_badges['total_rating'] == 0)

                    $allinfo_on = 0;



                if($id_category){

                    $cat_obj = new Category($id_category);

                    $name = $cat_obj->name[$id_lang];

                }



                if($id_supplier){

                    $sup_obj = new Supplier($id_supplier);

                    $name = $sup_obj->name;

                }



                if($id_manufacturer){

                    $sup_obj = new Manufacturer($id_manufacturer);

                    $name = $sup_obj->name;

                }



                if($is_home){

                    $name = Configuration::get('PS_SHOP_NAME');

                }





                $data_seo_url = $obj->getSEOURLs(array('id_lang'=>$id_lang));



                $rev_all = $data_seo_url['rev_all'];



            } else {

                $data_badges = array();

            }









        }





        /* home page */

        $is_home_custom_page_name =0;





        if($is_home) {

            $prefix_home = 'home';

            if(Configuration::get($this->name . 'allinfo_'.$prefix_home) == "allinfo_".$prefix_home && Configuration::get($this->name . 'allinfo_'.$prefix_home.'_pos') == $custom_page_name){

                $is_home_custom_page_name =1;

                $smarty->assign(

                    array(

                        $this->name . 'allinfoh_w' => Configuration::get($this->name . 'allinfo_'.$prefix_home.'_w'),

                    )

                );



            }

        }

        /* home page */





        /* category page */

        $is_cat_custom_page_name =0;

        if($id_category){

            $prefix_cat = 'cat';

            if(Configuration::get($this->name . 'allinfo_'.$prefix_cat) == "allinfo_".$prefix_cat && Configuration::get($this->name . 'allinfo_'.$prefix_cat.'_pos') == $custom_page_name){

                $is_cat_custom_page_name =1;



                $smarty->assign(

                    array(

                        $this->name . 'allinfoh_w' => Configuration::get($this->name . 'allinfo_'.$prefix_cat.'_w'),

                    )

                );



            }

        }

        /* category page */



        /* manufacturer/brand page */

        $is_man_custom_page_name =0;

        if($id_supplier || $id_manufacturer){

            $prefix_cat = 'man';

            /*var_dump(Configuration::get($this->name . 'allinfo_'.$prefix_cat));

            var_dump("allinfo_".$prefix_cat);

            var_dump(Configuration::get($this->name . 'allinfo_'.$prefix_cat.'_pos'));

            var_dump($custom_page_name);

            echo "<br><hr><br>";*/



            if(Configuration::get($this->name . 'allinfo_'.$prefix_cat) == "allinfo_".$prefix_cat

                && Configuration::get($this->name . 'allinfo_'.$prefix_cat.'_pos') == $custom_page_name){

                $is_man_custom_page_name =1;



                $smarty->assign(

                    array(

                        $this->name . 'allinfoh_w' => Configuration::get($this->name . 'allinfo_'.$prefix_cat.'_w'),

                    )

                );



            }

        }

        /* manufacturer/brand page */



        $smarty->assign($this->name . 'is_home_b_'.$custom_page_name, $is_home_custom_page_name);

        $smarty->assign($this->name . 'is_cat_b_'.$custom_page_name, $is_cat_custom_page_name);

        $smarty->assign($this->name . 'is_man_b_'.$custom_page_name, $is_man_custom_page_name);



        $smarty->assign($this->name.'rev_all', $rev_all);

        $smarty->assign($this->name.'data_badges', $data_badges);

        $smarty->assign($this->name.'badges_name', $name);

        $smarty->assign($this->name.'allinfo_on', $allinfo_on);

        $smarty->assign($this->name.'svis_on', $svis_on);





        ## badges ##

    }



    public function hookTop($params)

    {



            $this->badges(array('custom_page_name' => 'top'));





            $this->basicSettingsHook();



            $this->setLastReviewsBlockSettings(array('custom_page_name' => 'top'));





            return $this->display(dirname(__FILE__) . '/gsnipreview.php', 'views/templates/hooks/top.tpl');







    }



	

	public function hookhome($params)

	{





            $smarty = $this->context->smarty;





            ## badges ###

            $this->badges(array('custom_page_name' => 'home'));

            ## badges ###



            include_once(dirname(__FILE__) . '/classes/gsnipreviewhelp.class.php');

            $obj = new gsnipreviewhelp();

            $data = $obj->getHomeLastReviews(array('start' => 0,

                    'step' => Configuration::get($this->name . 'blocklr_home_ndr')

                )

            );



            $smarty->assign(array($this->name . 'reviews_home' => $data['reviews']));





            ### block last reviews ###



            $smarty->assign(

                array(

                    $this->name . 'is_blocklr' => Configuration::get($this->name . 'is_blocklr'),

                    $this->name . 'blocklr_home_pos' => Configuration::get($this->name . 'blocklr_home_pos'),

                    $this->name . 'blocklr_home_w' => Configuration::get($this->name . 'blocklr_home_w'),

                    $this->name . 'blocklr_home' => Configuration::get($this->name . 'blocklr_home'),

                    $this->name . 'blocklr_home_tr' => Configuration::get($this->name . 'blocklr_home_tr'),

                )

            );



            ### block last reviews ###



            $this->basicSettingsHook();



            $smarty->assign($this->name . 'rsson', Configuration::get($this->name . 'rsson'));



            $smarty->assign($this->name . 'is_ps15', $this->_is15);



            $cookie = $this->context->cookie;

            $id_lang = (int)$cookie->id_lang;





            $data_seo_url = $obj->getSEOURLs(array('id_lang' => $id_lang));

            $all = $data_seo_url['rev_all'];



            $smarty->assign($this->name . 'allr_url', $all);





            ## user profile ##

            if(Configuration::get($this->name . $this->_prefix_review . 'adv_home') == 1) {

                $this->setuserprofilegSettings();

            }

            $smarty->assign($this->name . $this->_prefix_review . 'adv_home', Configuration::get($this->name . $this->_prefix_review . 'adv_home'));

            ## user profile ##





            ## store reviews ##

            if(Configuration::get($this->name . 't_home') == 1) {

                $this->setStoreReviewsColumnsSettings();

            }

            $smarty->assign($this->name . 't_home', Configuration::get($this->name . 't_home'));

            ## store reviews ##



            return $this->display(dirname(__FILE__) . '/gsnipreview.php', 'views/templates/hooks/home.tpl');





	}

	



    public function setLastReviewsBlockSettings($data){



        $custom_page_name = $data['custom_page_name'];



        ## home page ##

        $smarty = $this->context->smarty;



        ## prestashop 1.7 ##

        if(version_compare(_PS_VERSION_, '1.7', '>')){

            $page_name = $smarty->tpl_vars['page']->value['page_name'];

         ## prestashop 1.7 ##

        } else {

            if (defined('_MYSQL_ENGINE_')) {

                $page_name = $smarty->tpl_vars['page_name']->value;

            } else {

                $page_name = $smarty->_tpl_vars['page_name'];

            }

        }

        $is_home = 0;

        if($page_name == 'index'){

            $is_home = 1;

        }

        ## home page ##



        $id_manufacturer = (int)Tools::getValue('id_manufacturer');

        $id_supplier = (int)Tools::getValue('id_supplier');

        $id_category = (int)Tools::getValue('id_category');

        $id_product = (int)Tools::getValue('id_product');





        include_once(dirname(__FILE__) . '/classes/gsnipreviewhelp.class.php');

        $obj = new gsnipreviewhelp();



        $this->basicSettingsHook();



        $smarty->assign($this->name . 'rsson', Configuration::get($this->name . 'rsson'));





        /* home page */

        $is_home_custom_page_name =0;



        if($is_home) {

            $prefix_home = 'home';

            if(Configuration::get($this->name . 'blocklr_'.$prefix_home) == "blocklr_".$prefix_home && Configuration::get($this->name . 'blocklr_'.$prefix_home.'_pos') == $custom_page_name){

                $is_home_custom_page_name =1;

                $data = $obj->getHomeLastReviews(array('start' => 0,

                                                      'step' => Configuration::get($this->name . 'blocklr_'.$prefix_home.'_ndr')

                    )

                );



                $smarty->assign(array($this->name . 'reviews_block' => $data['reviews']));





                ### block last reviews ###

                $smarty->assign(

                    array(

                        $this->name . 'blocklr_w' => Configuration::get($this->name . 'blocklr_'.$prefix_home.'_w'),

                        $this->name . 'blocklr_tr' => Configuration::get($this->name . 'blocklr_'.$prefix_home.'_tr'),

                    )

                );



                ### block last reviews ###

            }





        }

        /* home page */



        /* category page */

        $is_cat_custom_page_name =0;

        if($id_category){

            $prefix_cat = 'cat';

            if(Configuration::get($this->name . 'blocklr_'.$prefix_cat) == "blocklr_".$prefix_cat && Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_pos') == $custom_page_name){

                $is_cat_custom_page_name =1;



                $data = $obj->getBlockLastReviews(array('start' => 0,

                        'step' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_ndr'),

                        'prefix' => $prefix_cat,

                    )

                );



                $smarty->assign(array($this->name . 'reviews_block' => $data['reviews']));





                ### block last reviews ###

                $smarty->assign(

                    array(

                        $this->name . 'blocklr_w' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_w'),

                        $this->name . 'blocklr_tr' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_tr'),

                    )

                );



                ### block last reviews ###

            }

        }

        /* category page */



        /* manufacturer/brand page */

        $is_man_custom_page_name =0;



        if($id_manufacturer || $id_supplier){



            $prefix_cat = 'man';

            if(Configuration::get($this->name . 'blocklr_'.$prefix_cat) == "blocklr_".$prefix_cat

                && Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_pos') == $custom_page_name){

                $is_man_custom_page_name =1;



                $data = $obj->getBlockLastReviews(array('start' => 0,

                        'step' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_ndr'),

                        'prefix' => $prefix_cat,

                    )

                );



                $smarty->assign(array($this->name . 'reviews_block' => $data['reviews']));





                ### block last reviews ###

                $smarty->assign(

                    array(

                        $this->name . 'blocklr_w' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_w'),

                        $this->name . 'blocklr_tr' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_tr'),

                    )

                );



                ### block last reviews ###

            }

        }

        /* manufacturer/brand page */





        /* product page */

        $is_prod_custom_page_name =0;

        if($id_product){

            $prefix_cat = 'prod';

            if(Configuration::get($this->name . 'blocklr_'.$prefix_cat) == "blocklr_".$prefix_cat && Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_pos') == $custom_page_name){

                $is_prod_custom_page_name =1;



                $data = $obj->getBlockLastReviews(array('start' => 0,

                        'step' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_ndr'),

                        'prefix' => $prefix_cat,

                    )

                );



                $smarty->assign(array($this->name . 'reviews_block' => $data['reviews']));





                ### block last reviews ###

                $smarty->assign(

                    array(

                        $this->name . 'blocklr_w' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_w'),

                        $this->name . 'blocklr_tr' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_tr'),

                    )

                );



                ### block last reviews ###

            }

        }

        /* product page */





        /* other page */

        $is_oth_custom_page_name =0;

        if(!$id_product && !$id_manufacturer && !$id_category && !$is_home){

            $prefix_cat = 'oth';

            if(Configuration::get($this->name . 'blocklr_'.$prefix_cat) == "blocklr_".$prefix_cat && Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_pos') == $custom_page_name){

                $is_oth_custom_page_name =1;



                $data = $obj->getBlockLastReviews(array('start' => 0,

                        'step' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_ndr'),

                        'prefix' => $prefix_cat,

                    )

                );



                $smarty->assign(array($this->name . 'reviews_block' => $data['reviews']));





                ### block last reviews ###

                $smarty->assign(

                    array(

                        $this->name . 'blocklr_w' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_w'),

                        $this->name . 'blocklr_tr' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_tr'),

                    )

                );



                ### block last reviews ###

            }

        }

        /* other page */







        $smarty->assign($this->name . 'is_blocklr', Configuration::get($this->name . 'is_blocklr'));

        $smarty->assign($this->name . 'is_home_'.$custom_page_name, $is_home_custom_page_name);

        $smarty->assign($this->name . 'is_cat_'.$custom_page_name, $is_cat_custom_page_name);

        $smarty->assign($this->name . 'is_man_'.$custom_page_name, $is_man_custom_page_name);

        $smarty->assign($this->name . 'is_prod_'.$custom_page_name, $is_prod_custom_page_name);

        $smarty->assign($this->name . 'is_oth_'.$custom_page_name, $is_oth_custom_page_name);





        $cookie = $this->context->cookie;

        $id_lang = (int)$cookie->id_lang;





        $data_seo_url = $obj->getSEOURLs(array('id_lang'=>$id_lang));



        $all = $data_seo_url['rev_all'];



        $smarty->assign($this->name . 'allr_url', $all);

    }









    public function hooklastReviewsMitrocops($params)

    {

        $smarty = $this->context->smarty;

        include_once(dirname(__FILE__) . '/classes/gsnipreviewhelp.class.php');

        $obj = new gsnipreviewhelp();



        $this->basicSettingsHook();



        $smarty->assign($this->name . 'rsson', Configuration::get($this->name . 'rsson'));



        $prefix_cat = 'chook';

        $data = $obj->getBlockLastReviews(array('start' => 0,

                'step' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_ndr'),

                'prefix' => $prefix_cat,

            )

        );



        $smarty->assign(array($this->name . 'reviews_block_chook' => $data['reviews']));





        ### block last reviews ###

        $smarty->assign(

            array(

                $this->name . 'blocklr_w' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_w'),

                $this->name . 'blocklr_tr' => Configuration::get($this->name . 'blocklr_'.$prefix_cat.'_tr'),

                $this->name.'blocklr_'.$prefix_cat.'_pos' => Configuration::get($this->name.'blocklr_'.$prefix_cat.'_pos'),

                $this->name.'blocklr_'.$prefix_cat.'' => Configuration::get($this->name.'blocklr_'.$prefix_cat.''),

            )

        );



        ### block last reviews ###



        $cookie = $this->context->cookie;

        $id_lang = (int)$cookie->id_lang;







        $data_seo_url = $obj->getSEOURLs(array('id_lang'=>$id_lang));



        $all = $data_seo_url['rev_all'];



        $smarty->assign($this->name . 'allr_url', $all);









        return $this->display(__FILE__, 'views/templates/hooks/lastreviewsmitrocops.tpl');

    }

    



	

	

 public function snippetBlockSettings($data){

		$smarty = $this->context->smarty;

		$cookie = $this->context->cookie;

		

		$_product_id = $data['product_id'];

		$params = $data['params'];

		

		

    	$id_lang = (int)($params['cookie']->id_lang);

    	

    	$_obj_product = new Product($_product_id);

    	

    	$data_product = $this->_productData(array('product'=>$_obj_product));	

		$picture = $data_product['image_link'];

		

    	

    	

    	$productname = addslashes($_obj_product->name[$id_lang]);

		 

		$smarty->assign($this->name.'picture', isset($picture)?$picture:'');

    	$smarty->assign($this->name.'productname', $productname);

    	

		

    	//// new ///

    	

		$product = new Product(Tools::getValue("id_product"),false,(int)($cookie->id_lang));

		$currency = new Currency((int)($params['cart']->id_currency));

		if (!$currency) {

			try {

				$currency = Currency::getCurrencyInstance($cookie->id_currency);

			} catch (Exception $e) {

			}

		}

		$qty = $product->getQuantity(Tools::getValue("id_product"));

		$desc = ($product->description_short != "") ? $product->description_short : $product->description;

		

		$smarty->assign(array(

			'product_brand' => Manufacturer::getNameById($product->id_manufacturer),

			'product_name' => $product->name,

			'product_image' => $picture,

			'product_price_custom' => number_format($product->getPrice(),2,".",","),

			'product_description' => strip_tags($desc),//Tools::htmlentitiesUTF8(strip_tags($desc)),

			'product_category' => $this->_getDefaultCategory($product->id_category_default),

			'currency_custom' => $currency->iso_code,

			'quantity' => $qty,

			'stock_string' => ($qty > 0) ? 'in_stock' : 'out_of_stock'

		));

		

		if (isset($product->upc) && !Tools::isEmpty($product->upc)) {

			$smarty->assign(array('show_identifier' => true,'identifier_type' => 'upc', 'identifier_value' => $product->upc));

		}

		elseif (!Tools::isEmpty($product->ean13)) {

			$smarty->assign(array('show_identifier' => true,'identifier_type' => 'sku', 'identifier_value' => $product->ean13));

		} else {

			$smarty->assign(array('show_identifier' => false));

		}

		

		if (Configuration::get('GPROFILE_ID') != "") {

			$smarty->assign("gprofile_id",Configuration::get('GPROFILE_ID'));

		} else {

			$smarty->assign("gprofile_id",false);

		}

		/// end new ///

		

		

		$smarty->assign($this->name.'gsnipblock', Configuration::get($this->name.'gsnipblock'));

    	$smarty->assign($this->name.'id_hook_gsnipblock', Configuration::get($this->name.'id_hook_gsnipblock'));

    	$smarty->assign($this->name.'gsnipblock_width', Configuration::get($this->name.'gsnipblock_width'));

    	$smarty->assign($this->name.'gsnipblocklogo', Configuration::get($this->name.'gsnipblocklogo'));

    	

    	include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

		$obj = new gsnipreviewhelp();

				

		$count = $obj->getAvgReview(array('id_product'=>(int)(Tools::getValue("id_product"))));

		$smarty->assign($this->name.'count', $count['avg_rating']);

				

		$total = $obj->getCountReviews(array('id_product'=>(int)(Tools::getValue("id_product"))));

		$smarty->assign($this->name.'total', $total);



		$avg_rating = $obj->getAvgReview(array('id_product'=>(int)(Tools::getValue("id_product"))));

		$smarty->assign($this->name.'avg_rating', $avg_rating['avg_rating']);

		

    	

		

    			

		if($product->getPrice()==0)

			return '';

		else

			return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/snippet.tpl');



	}

	

	private function _getDefaultCategory($id_category)

	{

		$cookie = $this->context->cookie;

		

		$_category = new Category($id_category);

		return $_category->getName((int)($cookie->id_lang));

	}





    public function setStarsImagesSetting(){

        $smarty = $this->context->smarty;



        switch(Configuration::get($this->name.'stylestars')){

            case 'style1':

                $smarty->assign($this->name.'activestar', 'star-active-yellow.png');

                $smarty->assign($this->name.'noactivestar', 'star-noactive-yellow.png');

                break;

            case 'style2':

                $smarty->assign($this->name.'activestar', 'star-active-green.png');

                $smarty->assign($this->name.'noactivestar', 'star-noactive-green.png');

                break;

            case 'style3':

                $smarty->assign($this->name.'activestar', 'star-active-blue.png');

                $smarty->assign($this->name.'noactivestar', 'star-noactive-blue.png');

                break;

            default:

                $smarty->assign($this->name.'activestar', 'star-active-yellow.png');

                $smarty->assign($this->name.'noactivestar', 'star-noactive-yellow.png');

                break;

        }



    }



    public function basicSettingsHook(){

        $smarty = $this->context->smarty;

        $cookie = $this->context->cookie;

        $id_lang = (int)$cookie->id_lang;



        include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

        $obj = new gsnipreviewhelp();



        $hooktodisplay = Configuration::get($this->name.'hooktodisplay');

        $smarty->assign($this->name.'hooktodisplay', $hooktodisplay);



        $this->setStarsImagesSetting();



        $smarty->assign($this->name.'rvis_on', Configuration::get($this->name.'rvis_on'));

        $smarty->assign($this->name.'ratings_on', Configuration::get($this->name.'ratings_on'));



        $smarty->assign($this->name.'svis_on', Configuration::get($this->name.'svis_on'));

        $smarty->assign($this->name.'ip_on', Configuration::get($this->name.'ip_on'));

        $smarty->assign($this->name.'is_captcha', Configuration::get($this->name.'is_captcha'));



        $smarty->assign($this->name.'title_on', Configuration::get($this->name.'title_on'));

        $smarty->assign($this->name.'text_on', Configuration::get($this->name.'text_on'));



        $smarty->assign($this->name.'is_abusef',Configuration::get($this->name.'is_abusef'));

        $smarty->assign($this->name.'is_helpfulf',Configuration::get($this->name.'is_helpfulf'));



        $smarty->assign($this->name.'rsoc_on',Configuration::get($this->name.'rsoc_on'));

        $smarty->assign($this->name.'rsoccount_on',Configuration::get($this->name.'rsoccount_on'));



        $smarty->assign($this->name.'ptabs_type',Configuration::get($this->name.'ptabs_type'));

        $smarty->assign($this->name.'is_rtl',$this->_is_rtl);





        $smarty->assign($this->name.'is_avatar'.$this->_prefix_review, Configuration::get($this->name.'is_avatar'.$this->_prefix_review));

        $smarty->assign($this->name.'is_files'.$this->_prefix_review, Configuration::get($this->name.'is_files'.$this->_prefix_review));



        $smarty->assign($this->name.'ruploadfiles', Configuration::get($this->name.'ruploadfiles'));

        $smarty->assign($this->name.'rminc', Configuration::get($this->name.'rminc'));

        $smarty->assign($this->name.'fpath', $this->path_img_cloud);





        $smarty->assign($this->name.'is_uprof', Configuration::get($this->name.'is_uprof'));





        $data_seo_url = $obj->getSEOURLs(array('id_lang'=>$id_lang));

        $user_url = $data_seo_url['user_url'];

        $useraccount_url = $data_seo_url['useraccount_url'];



        $smarty->assign($this->name.'user_url', $user_url);

        $smarty->assign($this->name.'uacc_url', $useraccount_url);











        $smarty->assign($this->name.'is_storerev', Configuration::get($this->name.'is_storerev'));

        $store_reviews_account_url = $data_seo_url['store_reviews_account_url'];

        $smarty->assign($this->name.'mysr_url', $store_reviews_account_url);





        ## prestashop 1.7 ##

        if(version_compare(_PS_VERSION_, '1.7', '>')){

            $meta_description = $smarty->tpl_vars['page']->value['meta']['description'];

            $smarty->assign('meta_description', $meta_description);

        }



        ## prestashop 1.7 ##



        $smarty->assign($this->name.'is_bug', $this->_is_bug_product_page);





    }



    public function settingsHooks()

    {



        //echo "1<br/>";



        $smarty = $this->context->smarty;

        $cookie = $this->context->cookie;



        $this->basicSettingsHook();



        $smarty->assign($this->name.'is16', $this->_is16);





        $smarty->assign($this->name.'starratingon', Configuration::get($this->name.'starratingon'));





        $id_customer = (int)$cookie->id_customer;

        $id_product = (int)Tools::getValue('id_product');



        include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

        $obj = new gsnipreviewhelp();



        $avg_rating = $obj->getAvgReview(array('id_product'=>$id_product));

        $smarty->assign($this->name.'avg_rating', $avg_rating['avg_rating']);

        $smarty->assign($this->name.'avg_decimal', $avg_rating['avg_rating_decimal']);



        $count_reviews = $obj->getCountReviews(array('id_product'=>$id_product));

        $smarty->assign($this->name.'count_reviews', $count_reviews);

        $smarty->assign($this->name.'text_reviews', $obj->number_ending($count_reviews, $this->l('reviews'), $this->l('review'), $this->l('reviews')));



        $data_rating = $obj->getCountRatingForItem();

        $smarty->assign($this->name.'one', $data_rating['one']);

        $smarty->assign($this->name.'two', $data_rating['two']);

        $smarty->assign($this->name.'three', $data_rating['three']);

        $smarty->assign($this->name.'four', $data_rating['four']);

        $smarty->assign($this->name.'five', $data_rating['five']);





        $smarty->assign($this->name.'is_onerev', Configuration::get($this->name.'is_onerev'));



        if(Configuration::get($this->name.'is_onerev') != 1) {

            $is_alreadyaddreview = $obj->checkIsUserAlreadyAddReview(array('id_product' => $id_product, 'id_customer' => $id_customer));



        } else {

            $is_alreadyaddreview = 0;

        }

        $smarty->assign(array($this->name.'is_add' => $is_alreadyaddreview));





        if((Configuration::get('PS_REWRITING_SETTINGS') || version_compare(_PS_VERSION_, '1.5', '<'))){

            $smarty->assign($this->name.'is_rewrite', 1);

        } else {

            $smarty->assign($this->name.'is_rewrite',0);

        }





        $smarty->assign(

                array(

                    $this->name.'sh_name' => @Configuration::get('PS_SHOP_NAME'),

                     )

                );



        $smarty->assign($this->name.'is_ps15', $this->_is15);





    }



    public function setSettingsPinterest(){

        $smarty = $this->context->smarty;



        // pinterest

        $smarty->assign($this->name.'pinvis_on', Configuration::get($this->name.'pinvis_on'));

        $smarty->assign($this->name.'pinterestbuttons', Configuration::get($this->name.'pinterestbuttons'));

        // pinterest





        $is16_snippet = 0;

        if($this->_is16 == 1 && Configuration::get($this->name.'svis_on') == 1){

            $is16_snippet = 1;

        }

        $smarty->assign($this->name.'is16_snippet', $is16_snippet);

    }



    public function hookExtraRight($params){



        $smarty = $this->context->smarty;



        $this->basicSettingsHook();



        $hooktodisplay = Configuration::get($this->name.'hooktodisplay');



        if($hooktodisplay == "extra_right") {

            $this->settingsHooks();

        }







        // google snippet block

        $_product_id = Tools::getValue("id_product");

        if(Configuration::get($this->name.'gsnipblock') &&

            (Configuration::get($this->name.'id_hook_gsnipblock') == 5) &&

            $_product_id &&

            Configuration::get($this->name.'svis_on')

        ){

            $smarty->assign($this->name.'extraright',

                $this->snippetBlockSettings(array('product_id'=>$_product_id,'params'=>$params))

            );

        } else {

            $smarty->assign($this->name.'extraright','');

        }

        // google snippet block





        // pinterest

        //if(Configuration::get($this->name.'_extraRight') == 'extraRight')){

            $this->setSettingsPinterest();

            $smarty->assign($this->name . '_extraRight', Configuration::get($this->name . '_extraRight'));

        //}

        // pinterest







        return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/reviewsblockextraright.tpl');





    }



	public function hookExtraLeft($params){

		

			$smarty = $this->context->smarty;



            $this->basicSettingsHook();





            $hooktodisplay = Configuration::get($this->name.'hooktodisplay');



            if($hooktodisplay == "extra_left") {

                $this->settingsHooks();

            }







			// google snippet block

			$_product_id = Tools::getValue("id_product");

			if(Configuration::get($this->name.'gsnipblock') &&

			  (Configuration::get($this->name.'id_hook_gsnipblock') == 9) &&

			  $_product_id &&

			  Configuration::get($this->name.'svis_on')

			  ){

					$smarty->assign($this->name.'extraleft',

									$this->snippetBlockSettings(array('product_id'=>$_product_id,'params'=>$params))

									);

			  } else {

			  	$smarty->assign($this->name.'extraleft','');

			  }

			// google snippet block





			// pinterest

            $this->setSettingsPinterest();

			$smarty->assign($this->name.'_extraLeft', Configuration::get($this->name.'_extraLeft'));

			// pinterest







			

			return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/reviewsblockextraleft.tpl');

	}

	

	public function hookproductFooter($params){

			$smarty = $this->context->smarty;



            $this->basicSettingsHook();



            //$hooktodisplay = Configuration::get($this->name.'hooktodisplay');



            //if($hooktodisplay == "product_footer") {

                $this->settingsHooks();

            //}







				// google snippet block

			$_product_id = Tools::getValue("id_product");

			if(Configuration::get($this->name.'gsnipblock') &&

			  (Configuration::get($this->name.'id_hook_gsnipblock') == 6) &&

			  $_product_id &&

			  Configuration::get($this->name.'svis_on')

			  ){

					$smarty->assign($this->name.'productfooter',

									$this->snippetBlockSettings(array('product_id'=>$_product_id,'params'=>$params))

									);

			  } else {

			  	$smarty->assign($this->name.'productfooter','');

			  }

			// google snippet block





			// pinterest

            $this->setSettingsPinterest();

			$smarty->assign($this->name.'_productFooter', Configuration::get($this->name.'_productFooter'));

			// pinterest





            ## user profile ##

            if(Configuration::get($this->name . $this->_prefix_review . 'adv_footer') == 1) {

                $this->setuserprofilegSettings();

            }

            ## user profile ##





        if(version_compare(_PS_VERSION_, '1.7', '>')) {

            $this->setHookProductTabContentSettings($params);

            return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/producttabcontent.tpl');

        } else {

            return $this->display(dirname(__FILE__) . '/gsnipreview.php', 'views/templates/hooks/reviewsblockproductfooter.tpl');



        }

	}

	

	public function hookproductActions($params){



			$smarty = $this->context->smarty;



            $this->basicSettingsHook();



            $hooktodisplay = Configuration::get($this->name.'hooktodisplay');



            if($hooktodisplay == "product_actions") {

                $this->settingsHooks();

            }





				// google snippet block

			$_product_id = Tools::getValue("id_product");

			if(Configuration::get($this->name.'gsnipblock') &&

			  (Configuration::get($this->name.'id_hook_gsnipblock') == 8) &&

			  $_product_id &&

			  Configuration::get($this->name.'svis_on')

			  ){

					$smarty->assign($this->name.'productactions',

									$this->snippetBlockSettings(array('product_id'=>$_product_id,'params'=>$params))

									);

			  } else {

			  	$smarty->assign($this->name.'productactions','');

			  }

			// google snippet block





			// pinterest

            $this->setSettingsPinterest();

			$smarty->assign($this->name.'_productActions', Configuration::get($this->name.'_productActions'));

			// pinterest





			

			return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/reviewsblockproductactions.tpl');

	}

    

    public function hookProductTab($params)

    {

		$smarty = $this->context->smarty;

		$cookie = $this->context->cookie;

		$smarty->assign($this->name.'is16', $this->_is16);

		

		$id_customer = (int)$cookie->id_customer;

		

    	$smarty->assign(array($this->name.'id_customer' => $id_customer));

		

    	$id_product = (int)Tools::getValue('id_product');

		

		include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

		$obj = new gsnipreviewhelp();

		$count_reviews = $obj->getCountReviews(array('id_product'=>$id_product));

		$smarty->assign(array($this->name.'count_reviews' => $count_reviews));



        $is_alreadyaddreview = $obj->checkIsUserAlreadyAddReview(array('id_product'=>$id_product,'id_customer'=>$id_customer));

        $smarty->assign(array($this->name.'is_add' => $is_alreadyaddreview));



        $this->basicSettingsHook();





		

		return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/tab.tpl');

			

	}



    private function setHookProductTabContentSettings($params){

        $smarty = $this->context->smarty;

        $cookie = $this->context->cookie;



        $smarty->assign($this->name.'is16', $this->_is16);

        $smarty->assign($this->name.'is15', $this->_is15);



        $this->settingsHooks();





        if((Configuration::get('PS_REWRITING_SETTINGS') || version_compare(_PS_VERSION_, '1.5', '<'))){

            $smarty->assign($this->name.'is_rewrite', 1);

        } else {

            $smarty->assign($this->name.'is_rewrite',0);

        }



        $id_lang = (int)$cookie->id_lang;

        $iso_lang = Language::getIsoById((int)($id_lang));

        $smarty->assign($this->name.'iso_lang', $iso_lang);



        include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

        $obj = new gsnipreviewhelp();





        $smarty->assign(array(

            $this->name.'criterions' => $obj->getReviewCriteria(array('id_lang'=>$id_lang,'id_shop'=>$this->_id_shop)),

        ));





        $data_seo_url = $obj->getSEOURLs(array('id_lang'=>$id_lang));



        $my_account = $data_seo_url['my_account'];

        $rev_url = $data_seo_url['rev_url'];







        $smarty->assign($this->name.'m_acc', $my_account);

        $smarty->assign($this->name.'rev_url', $rev_url);





        $id_customer = (int)$cookie->id_customer;

        $is_logged = isset($cookie->id_customer)?$cookie->id_customer:0;





        $smarty->assign($this->name.'islogged', $is_logged);



        $customer_name = '';

        $customer_email = '';

        $avatar = '';

        if($is_logged) {

            $customer_data = $obj->getInfoAboutCustomer(array('id_customer' => $id_customer));

            $customer_name = $customer_data['customer_name'];

            $customer_email = $customer_data['email'];



            $data_avatar = $obj->getAvatarForCustomer(array('id_customer' => $id_customer));

            $avatar = $data_avatar['avatar'];

        }



        $smarty->assign(array($this->name.'c_avatar' => $avatar));

        $smarty->assign(array($this->name.'c_name' => $customer_name));

        $smarty->assign(array($this->name.'c_email' => $customer_email));





        $id_product = (int)Tools::getValue('id_product');





        $is_buy = $obj->checkProductBought(array('id_product'=>$id_product,'id_customer'=>$id_customer));

        $smarty->assign(array($this->name.'is_buy' => $is_buy));



        $smarty->assign($this->name.'is_onerev', Configuration::get($this->name.'is_onerev'));



        if(Configuration::get($this->name.'is_onerev') != 1) {

            $is_alreadyaddreview = $obj->checkIsUserAlreadyAddReview(array('id_product' => $id_product, 'id_customer' => $id_customer));



        } else {

            $is_alreadyaddreview = 0;

        }

        $smarty->assign(array($this->name . 'is_add' => $is_alreadyaddreview));



        $smarty->assign(array($this->name.'id_customer' => $id_customer));

        $smarty->assign(array($this->name.'id_product' => $id_product));



        $smarty->assign($this->name.'whocanadd', Configuration::get($this->name.'whocanadd'));





        $this->basicSettingsHook();



        // voucher //

        $smarty->assign($this->name.'vis_on', Configuration::get($this->name.'vis_on'));



        // set discount

        switch (Configuration::get($this->name.'discount_type'))

        {

            case 1:

                // percent

                $id_discount_type = 1;

                $value = Configuration::get($this->name.'percentage_val');

                $id_currency = (int)$cookie->id_currency;

                break;

            case 2:

                // currency

                $id_discount_type = 2;

                $id_currency = (int)$cookie->id_currency;

                $value = Configuration::get('sdamount_'.(int)$id_currency);

                break;

            default:

                $id_discount_type = 2;

                $id_currency = (int)$cookie->id_currency;

                $value = Configuration::get('sdamount_'.(int)$id_currency);

        }

        $valuta = "%";

        $tax = '';



        if($id_discount_type == 2){

            if($this->_is16)

                $cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

            else

                $cur = Currency::getCurrencies();





            $id_currency = (int)$cookie->id_currency;

            foreach ($cur AS $_cur){

                if(

                    //Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency']

                    $id_currency == $_cur['id_currency']

                ){

                    $valuta = $_cur['sign'];

                }

            }



            $tax = (int)Configuration::get($this->name.'tax');

        }



        $smarty->assign($this->name.'tax', $tax);

        $smarty->assign($this->name.'discount', $value.' '.$valuta);

        $smarty->assign($this->name.'valuta', $valuta);

        $smarty->assign($this->name.'sdvvalid', Configuration::get($this->name.'sdvvalid'));

        $smarty->assign($this->name.'days', $obj->number_ending(Configuration::get($this->name.'sdvvalid'), $this->l('days'), $this->l('day'), $this->l('days')));





        // minimum checkout //

        $smarty->assign($this->name.'is_show_min', Configuration::get($this->name.'is_show_min'));

        $smarty->assign($this->name.'isminamount', Configuration::get($this->name.'isminamount'));

        $smarty->assign($this->name.'minamount', Configuration::get('sdminamount_'.(int)$id_currency));

        $fvaluta_min = '';

        if($this->_is16)

            $cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

        else

            $cur = Currency::getCurrencies();





        $id_currency = (int)$cookie->id_currency;

        foreach ($cur AS $_cur){

            if(

                //Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency']

                $id_currency == $_cur['id_currency']

            ){

                $fvaluta_min = $_cur['sign'];

            }

        }



        $smarty->assign($this->name.'curtxt', $fvaluta_min);

        // minimum checkout //



        // voucher //



        // voucher facebook //

        $smarty->assign($this->name.'vis_onfb', Configuration::get($this->name.'vis_onfb'));



        // set discount

        switch (Configuration::get($this->name.'discount_typefb'))

        {

            case 1:

                // percent

                $id_discount_type = 1;

                $value = Configuration::get($this->name.'percentage_valfb');

                $id_currency = (int)$cookie->id_currency;

                break;

            case 2:

                // currency

                $id_discount_type = 2;

                $id_currency = (int)$cookie->id_currency;

                $value = Configuration::get('sdamountfb_'.(int)$id_currency);

                break;

            default:

                $id_discount_type = 2;

                $id_currency = (int)$cookie->id_currency;

                $value = Configuration::get('sdamountfb_'.(int)$id_currency);

        }

        $valuta = "%";

        $tax = '';



        if($id_discount_type == 2){

            if($this->_is16)

                $cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

            else

                $cur = Currency::getCurrencies();



            $id_currency = (int)$cookie->id_currency;

            foreach ($cur AS $_cur){

                if(

                    //Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency']

                    $id_currency == $_cur['id_currency']

                ){

                    $valuta = $_cur['sign'];

                }

            }



            $tax = (int)Configuration::get($this->name.'taxfb');

        }



        $smarty->assign($this->name.'taxfb', $tax);

        $smarty->assign($this->name.'discountfb', $value.' '.$valuta);

        $smarty->assign($this->name.'valutafb', $valuta);

        $smarty->assign($this->name.'sdvvalidfb', Configuration::get($this->name.'sdvvalidfb'));

        $smarty->assign($this->name.'daysfb', $obj->number_ending(Configuration::get($this->name.'sdvvalidfb'), $this->l('days'), $this->l('day'), $this->l('days')));





        // minimum checkout //

        $smarty->assign($this->name.'is_show_minfb', Configuration::get($this->name.'is_show_minfb'));

        $smarty->assign($this->name.'isminamountfb', Configuration::get($this->name.'isminamountfb'));

        $smarty->assign($this->name.'minamountfb', Configuration::get('sdminamountfb_'.(int)$id_currency));

        $fvaluta_min = '';

        if($this->_is16)

            $cur = Currency::getCurrenciesByIdShop(Context::getContext()->shop->id);

        else

            $cur = Currency::getCurrencies();







        $id_currency = (int)$cookie->id_currency;

        foreach ($cur AS $_cur){

            if(

                //Configuration::get('PS_CURRENCY_DEFAULT') == $_cur['id_currency']

                $id_currency == $_cur['id_currency']

            ){

                $fvaluta_min = $_cur['sign'];

            }

        }



        $smarty->assign($this->name.'curtxtfb', $fvaluta_min);

        // minimum checkout //



        // voucher facebook //





        ### reviews ###

        $gp = (int)Tools::getValue('gp');

        $step = (int)Configuration::get($this->name.'revperpage');



        $start = (int)(($gp - 1)*$step);

        if($start<0)

            $start = 0;



        $frat = Tools::getValue('frat');



        $search = Tools::getValue("search");

        $is_search = 0;



        ### search ###

        if(Tools::strlen($search)>0){

            $is_search = 1;



        }

        $this->context->smarty->assign($this->name.'is_search', $is_search);

        $this->context->smarty->assign($this->name.'search', $search);

        ## search ###



        $reviews_data = $obj->getReviews(array('id_product'=>$id_product,'start'=>$start,'frat'=>$frat,'is_search'=>$is_search,'search'=>$search));

        $data = $reviews_data['reviews'];

        $count_reviews = $reviews_data['count_all_reviews'];



        $id_lang = (int)($params['cookie']->id_lang);

        $_obj_product = new Product($id_product,null,$id_lang);

        $data_product = $this->_productData(array('product'=>$_obj_product));





        $paging = $obj->paging17(

            array('start'=>$start,

                'step'=> $step,

                'count' => $count_reviews,

                'product_url' => $data_product['product_url'],

                'page' => $this->l('Page'),

                'frat'=>$frat,'is_search'=>$is_search,'search'=>$search

            )

        );



        $data_translate = $this->translateCustom();





        $smarty->assign(

            array('reviews' => $data,

                'paging' => $paging,

                $this->name.'page_text' => $this->l('Page'),

                $this->name.'gp' => $gp,

                $this->name.'frat' => $frat,

                $this->name.'product_url' => $data_product['product_url'],



                $this->name.'ptc_msg1'=>$data_translate['ptc_msg1'],

                $this->name.'ptc_msg2'=>$data_translate['ptc_msg2'],

                $this->name.'ptc_msg3'=>$data_translate['ptc_msg3'],

                $this->name.'ptc_msg4'=>$data_translate['ptc_msg4'],

                $this->name.'ptc_msg5'=>$data_translate['ptc_msg5'],

                $this->name.'ptc_msg6'=>$data_translate['ptc_msg6'],

                $this->name.'ptc_msg7'=>$data_translate['ptc_msg7'],

                $this->name.'ptc_msg8'=>$data_translate['ptc_msg8'],

                $this->name.'ptc_msg9'=>$data_translate['ptc_msg9'],

                $this->name.'ptc_msg10'=>$data_translate['ptc_msg10'],

                $this->name.'ptc_msg11'=>$data_translate['ptc_msg11'],

                $this->name.'ptc_msg12'=>$data_translate['ptc_msg12'],

                $this->name.'ptc_msg13_1'=>$data_translate['ptc_msg13_1'],

                $this->name.'ptc_msg13_2'=>$data_translate['ptc_msg13_2'],



                $this->name.'ava_msg8'=>$data_translate['ava_msg8'],

                $this->name.'ava_msg9'=>$data_translate['ava_msg9'],



            ));

        ### reviews ###







        $smarty->assign($this->name.'vis_on', Configuration::get($this->name.'vis_on'));



        $smarty->assign($this->name.'is_ps15',$this->_is15);





        $product_obj = new Product($id_product);

        $name_product = $product_obj->name[$id_lang];

        $smarty->assign($this->name.'nameprsnip',$name_product);



        $this->setSettingsPinterest();



    }



    public function hookProductTabContent($params)

    {



        ## prestashop 1.7 ##

        $this->setHookProductTabContentSettings($params);

        ## prestashop 1.7 ##



        return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/producttabcontent.tpl');

    }





    public function hookdisplayProductListReviews($params){





        if(Configuration::get($this->name.'starscat') == 1 && Configuration::get($this->name.'rvis_on') == 1) {





            $id_product = isset($params['product']['id_product'])?(int)$params['product']['id_product']:(int)$params['product']->id;

            if (!$this->isCached('views/templates/hooks/liststars.tpl', $this->getCacheId($id_product))) {

                $smarty = $this->context->smarty;







                include_once(dirname(__FILE__) . '/classes/gsnipreviewhelp.class.php');

                $obj_reviewshelp = new gsnipreviewhelp();

                $count_review = $obj_reviewshelp->getCountReviews(array('id_product' => $id_product));

                $avg_rating = $obj_reviewshelp->getAvgReview(array('id_product' => $id_product));





                $smarty->assign(

                    array(

                        'id_product' => $id_product,

                        'avg_rating' => $avg_rating['avg_rating'],

                        'count_review' => $count_review,

                    )

                );



                $this->basicSettingsHook();

            }

            return $this->display(__FILE__, 'views/templates/hooks/liststars.tpl', $this->getCacheId($id_product));



            //return $this->display(dirname(__FILE__) . '/gsnipreview.php', 'views/templates/hooks/liststars.tpl');

        }

    }



    

    public function hookHeader($params){

    	$smarty = $this->context->smarty;

		$cookie = $this->context->cookie;

		

		$smarty->assign($this->name.'is15', $this->_is15);

        $smarty->assign('shop_name', @Configuration::get('PS_SHOP_NAME'));



        include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');

        $obj_reviewshelp = new gsnipreviewhelp();

		

    	if(version_compare(_PS_VERSION_, '1.5', '>')){



             // for 16

            $this->context->controller->addCSS(($this->_path) . 'views/css/gsnipreview.css', 'all');



            // for 1.5

            if(version_compare(_PS_VERSION_, '1.5', '>') && version_compare(_PS_VERSION_, '1.6', '<')) {

                $this->context->controller->addCSS(($this->_path) . 'views/css/gsnipreview15.css', 'all');

            }



             if($this->_is_rtl)

                $this->context->controller->addCSS(($this->_path) . 'views/css/gsnipreview-rtl.css', 'all');



    		$this->context->controller->addJS($this->_path.'views/js/r_stars.js');

            $this->context->controller->addJS($this->_path.'views/js/gsnipreview.js');



            $id_product = Tools::getValue('id_product');



            if(Configuration::get($this->name.'is_files'.$this->_prefix_review) && $id_product){

                $this->context->controller->addJqueryUI(array('ui.widget'));

                $this->context->controller->addJS($this->_path.'views/js/jquery.fileupload.js');

                $this->context->controller->addJS($this->_path.'views/js/jquery.fileupload-process.js');

                $this->context->controller->addJS($this->_path.'views/js/jquery.fileupload-validate.js');



                $this->context->controller->addJS($this->_path.'views/js/main-fileupload.js');



                if(version_compare(_PS_VERSION_, '1.6', '>')) {

                    $this->context->controller->addCSS(($this->_path) . 'views/css/font-custom.min.css', 'all');

                }



                if(version_compare(_PS_VERSION_, '1.7', '>')) {

                    $this->context->controller->addJqueryPlugin(array('fancybox'));

                }



            }





            if(Configuration::get($this->name.'is_uprof')){

                $this->context->controller->addCSS(($this->_path) . 'views/css/users.css', 'all');

            }





            ## store reviews ##

            if(Configuration::get($this->name.'is_storerev')) {

                $this->context->controller->addCSS(($this->_path) . 'views/css/storereviews.css', 'all');

                $this->context->controller->addJS($this->_path . 'views/js/storereviews.js');

                $this->context->controller->addCSS(($this->_path).'views/css/widgets.css', 'all');

            }

            ## store reviews ##





            if(version_compare(_PS_VERSION_, '1.7', '>')) {



            }





    	}



        $smarty->assign($this->name . 'is_files'.$this->_prefix_review, Configuration::get($this->name.'is_files'.$this->_prefix_review));



        if(Configuration::get($this->name.'is_uprof')) {

            include_once(dirname(__FILE__) . "/classes/userprofileg.class.php");

            $obj_userprofileg = new userprofileg();



            $info_customer = $obj_userprofileg->getCustomerInfo();

            $avatar_thumb = $info_customer['avatar_thumb'];

            $exist_avatar = $info_customer['exist_avatar'];

            $is_show = $info_customer['is_show'];



            $smarty->assign($this->name . 'avatar_thumb', $avatar_thumb);

            $smarty->assign($this->name . 'exist_avatar', $exist_avatar);

            $smarty->assign($this->name . 'is_show', $is_show);



            $is_logged = isset($cookie->id_customer)?$cookie->id_customer:0;

            $smarty->assign($this->name.'islogged', $is_logged);

        }



        $smarty->assign($this->name . 'is_uprof', Configuration::get($this->name.'is_uprof'));





        ## store reviews ##

        if(Configuration::get($this->name.'is_storerev')) {

            $smarty->assign($this->name.'rssontestim', Configuration::get($this->name.'rssontestim'));





            if($this->_is_mobile == 1) {

                $data_fb = $obj_reviewshelp->getfacebooklib($cookie->id_lang);



                if (

                    (@filesize(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 't-left' . $data_fb['lng_iso'] . '.png') > 0

                    )

                    &&

                    (@filesize(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 't-right' . $data_fb['lng_iso'] . '.png') > 0

                    )

                )

                {

                    $lng_custom = $data_fb['lng_iso'];

                } else {

                    $lng_custom = "";

                }



                $smarty->assign($this->name . 'lang', $lng_custom);

            }





            $smarty->assign($this->name.'t_width', $this->_t_width);



            $smarty->assign($this->name.'BGCOLOR_T', Configuration::get($this->name.'BGCOLOR_T'));

            $smarty->assign($this->name.'BGCOLOR_TIT', Configuration::get($this->name.'BGCOLOR_TIT'));





            $smarty->assign($this->name.'is_mobile', $this->_is_mobile);



            $this->_setPositionsforStoreWidget();



        }

        $smarty->assign($this->name.'t_leftside', Configuration::get($this->name.'t_leftside'));

        $smarty->assign($this->name.'t_rightside', Configuration::get($this->name.'t_rightside'));

        if(version_compare(_PS_VERSION_, '1.5', '<')){

            $smarty->assign($this->name.'is14', 1);

        } else {

            $smarty->assign($this->name.'is14', 0);

        }







        $smarty->assign($this->name . 'is_storerev', Configuration::get($this->name.'is_storerev'));

        ## store reviews ##









        $rid = (int)Tools::getValue('rid');

        $is_review_page = 0;

        if($rid){

            $data = $obj_reviewshelp->getOneReview(array('rid'=>$rid,));

            $smarty->assign($this->name.'name', $data['reviews'][0]['title_review']);

            $smarty->assign($this->name.'descr', strip_tags($data['reviews'][0]['text_review']));

            $smarty->assign($this->name.'img', $data['reviews'][0]['product_img']);

            $smarty->assign($this->name.'review_url', $data['reviews'][0]['review_url']);

            $is_review_page = 1;

        }

        $smarty->assign($this->name.'is_r_p', $is_review_page);





        $smarty->assign($this->name.'rsoc_on', Configuration::get($this->name.'rsoc_on'));



        $data_fb = $obj_reviewshelp->getfacebooklib((int)$params['cookie']->id_lang);

        $smarty->assign($this->name.'fbliburl', $data_fb['url']);

		

    	$smarty->assign($this->name.'is16', $this->_is16);



        $this->basicSettingsHook();

		

		$smarty->assign($this->name.'rsson', Configuration::get($this->name.'rsson'));





        switch(Configuration::get($this->name.'stylestars')){

            case 'style1':

                $smarty->assign($this->name.'stylecolor', '#F7B900');

                break;

            case 'style2':

                $smarty->assign($this->name.'stylecolor', '#7BE408');

                break;

            case 'style3':

                $smarty->assign($this->name.'stylecolor', '#00ABEC');

                break;

            default:

                $smarty->assign($this->name.'stylecolor', '#F7B900');

                break;

        }

		

		

		### snippets and pins ####

		$_product_id = Tools::getValue("id_product");

		

		if($_product_id){

			

		

		

    	$id_lang = (int)($params['cookie']->id_lang);

    	

    	$_obj_product = new Product($_product_id);

    	

    	$data_product = $this->_productData(array('product'=>$_obj_product));	

		$picture = $data_product['image_link'];

		

    		

    	$productname = addslashes($_obj_product->name[$id_lang]);



        $description = $_obj_product->description_short[$id_lang] .' '. $_obj_product->description[$id_lang];

        $description = strip_tags($description);

        $smarty->assign($this->name.'pindesc', $description);





        $smarty->assign($this->name.'picture', isset($picture)?$picture:'');

    	$smarty->assign($this->name.'productname', $productname);

    	

		

    	//// new ///

    	

		$product = new Product(Tools::getValue("id_product"),false,(int)($cookie->id_lang));

		$currency = new Currency((int)($params['cart']->id_currency));

		if (!$currency) {

			try {

				$currency = Currency::getCurrencyInstance($cookie->id_currency);

			} catch (Exception $e) {

			}

		}

		$qty = $product->getQuantity(Tools::getValue("id_product"));

		$desc = ($product->description_short != "") ? $product->description_short : $product->description;

		

		$smarty->assign(array(

			'product_brand' => Manufacturer::getNameById($product->id_manufacturer),

			'product_name' => $product->name,

			'product_image' => $picture,

			'product_price_custom' => number_format($product->getPrice(),2,".",","),

			'product_description' => Tools::htmlentitiesUTF8(strip_tags($desc)),

			'product_category' => $this->_getDefaultCategory($product->id_category_default),

			'currency_custom' => $currency->iso_code,

			'quantity' => $qty,

			'stock_string' => ($qty > 0) ? 'in_stock' : 'out_of_stock'

		));

		

		if (isset($product->upc) && !Tools::isEmpty($product->upc)) {

			$smarty->assign(array('show_identifier' => true,'identifier_type' => 'upc', 'identifier_value' => $product->upc));

		}

		elseif (!Tools::isEmpty($product->ean13)) {

			$smarty->assign(array('show_identifier' => true,'identifier_type' => 'sku', 'identifier_value' => $product->ean13));

		} else {

			$smarty->assign(array('show_identifier' => false));

		}

		

		if (Configuration::get('GPROFILE_ID') != "") {

			$smarty->assign("gprofile_id",Configuration::get('GPROFILE_ID'));

		} else {

			$smarty->assign("gprofile_id",false);

		}

		/// end new ///

		}

    	// pinterest

    	$smarty->assign($this->name.'is_product_page', $_product_id);

		$smarty->assign($this->name.'pinvis_on', Configuration::get($this->name.'pinvis_on'));



        $is_ssl = 0;

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')

            $is_ssl = 1;

        $smarty->assign($this->name.'is_ssl', $is_ssl);

		// pinterest

		### snippets and pins ####

		

		$smarty->assign($this->name.'starscat', Configuration::get($this->name.'starscat'));



        $is_category = 0;

		if(Configuration::get($this->name.'starscat') == 1

            && version_compare(_PS_VERSION_, '1.6.0.7', '<')

            ){

		

		### product list ####

		

		

		$id_supplier = Tools::getValue('id_supplier');

		$id_manufacturer = Tools::getValue('id_manufacturer');

		$id_category = Tools::getValue('id_category');

		$id_product = Tools::getValue('id_product');

		

		$id_lang = (int)($cookie->id_lang);

		

		



        $step = Tools::getValue('n')?(int)Tools::getValue('n'):(int)Configuration::get('PS_PRODUCTS_PER_PAGE');

		$start = (int)Tools::getValue('p');

		

		

		$db = Db::getInstance();

		



		$is_search = 0;

        $limit_products = 100;

		

		if($id_supplier){

			$is_category = 1;

			

			if(version_compare(_PS_VERSION_, '1.5', '>')){

			$sql = 'SELECT p.id_product

					FROM ' . _DB_PREFIX_ . 'product p 

					LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = '.(int)($id_lang).' 

					AND pl.id_shop = '.(int)($this->_id_shop).') 

					LEFT JOIN '._DB_PREFIX_.'product_shop ps ON(p.id_product = ps.id_product AND ps.id_shop = '.(int)($this->_id_shop).') 

					WHERE ps.active = 1

					AND p.id_supplier = '.(int)($id_supplier).' LIMIT '.$limit_products.'

					';

			}else {

				$sql = 'SELECT p.id_product FROM '._DB_PREFIX_.'product p

	            LEFT JOIN '._DB_PREFIX_.'product_lang pl 

	            ON (p.id_product = pl.id_product AND pl.id_lang = '.(int)($id_lang).')

	            WHERE p.active = 1 AND p.id_supplier = '.(int)($id_supplier).'  LIMIT '.$limit_products.'';

			}

		

		}elseif($id_manufacturer){

				$is_category = 1;

		

			if(version_compare(_PS_VERSION_, '1.5', '>')){

			$sql = 'SELECT p.id_product

					FROM ' . _DB_PREFIX_ . 'product p 

					LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = '.(int)($id_lang).' 

					AND pl.id_shop = '.(int)($this->_id_shop).') 

					LEFT JOIN '._DB_PREFIX_.'product_shop ps ON(p.id_product = ps.id_product AND ps.id_shop = '.(int)($this->_id_shop).') 

					WHERE ps.active = 1

					AND p.id_manufacturer = '.(int)($id_manufacturer).'  LIMIT '.$limit_products.'

					';

			}else {

				$sql = 'SELECT p.id_product FROM '._DB_PREFIX_.'product p

	            LEFT JOIN '._DB_PREFIX_.'product_lang pl 

	            ON (p.id_product = pl.id_product AND pl.id_lang = '.(int)($id_lang).')

	            WHERE p.active = 1 AND p.id_manufacturer = '.(int)($id_manufacturer).'  LIMIT '.$limit_products.'';

			}

			

		}elseif($id_category){

			

				$is_category = 1;

		

			

		if(version_compare(_PS_VERSION_, '1.5', '>')){

			$sql = 'SELECT p.id_product

					FROM 

					' . _DB_PREFIX_ . 'product p

					JOIN ' . _DB_PREFIX_ . 'category_product cp

					ON cp.id_category = '.(int)($id_category).' 

					LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = '.(int)($id_lang).' 

					AND pl.id_shop = '.(int)($this->_id_shop).') 

					LEFT JOIN '._DB_PREFIX_.'product_shop ps ON(p.id_product = ps.id_product AND ps.id_shop = '.(int)($this->_id_shop).') 

					WHERE ps.active = 1  LIMIT '.$limit_products.'

					';

			}else {

				$sql = 'SELECT p.id_product FROM '._DB_PREFIX_.'product p

				JOIN ' . _DB_PREFIX_ . 'category_product cp

				ON cp.id_category = '.(int)($id_category).' 

	            LEFT JOIN '._DB_PREFIX_.'product_lang pl 

	            ON (p.id_product = pl.id_product AND pl.id_lang = '.(int)($id_lang).')

	            WHERE p.active = 1  LIMIT '.$limit_products.'';

			}



		}elseif((strrpos($_SERVER['SCRIPT_NAME'], 'search.php') || strrpos($_SERVER['SCRIPT_NAME'], 'prices-drop.php') || (Tools::getValue('controller') && (Tools::getValue('controller') == 'search' || Tools::getValue('controller') == 'prices-drop')))){

				$is_category = 1;

				$is_search = 1;

		



            if(version_compare(_PS_VERSION_, '1.5', '>')){



                $search_query = Tools::getValue('search_query');



                $sql = 'SELECT p.id_product, cp.position



					FROM `'._DB_PREFIX_.'category_product` cp

						LEFT JOIN `'._DB_PREFIX_.'product` p

					ON p.`id_product` = cp.`id_product`



					LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = '.(int)($id_lang).'



					AND pl.id_shop = '.(int)($this->_id_shop).')



					LEFT JOIN '._DB_PREFIX_.'product_shop ps ON(p.id_product = ps.id_product AND ps.id_shop = '.(int)($this->_id_shop).')



					WHERE ps.active = 1 and

					(

							LOWER(pl.`name`) LIKE BINARY LOWER(\'%'.pSQL($search_query).'%\')

							OR LOWER(pl.`description`) LIKE BINARY LOWER(\'%'.pSQL($search_query).'%\')

							OR LOWER(pl.`description_short`) LIKE BINARY LOWER(\'%'.pSQL($search_query).'%\')

							OR LOWER(pl.`link_rewrite`) LIKE BINARY LOWER(\'%'.pSQL($search_query).'%\')

						)

					 order by cp.position desc  LIMIT '.$limit_products.'



					';



            }else {



                $sql = 'SELECT p.id_product FROM '._DB_PREFIX_.'product p



				LEFT JOIN '._DB_PREFIX_.'product_lang pl



	            ON (p.id_product = pl.id_product AND pl.id_lang = '.(int)($id_lang).')



	            WHERE p.active = 1  LIMIT '.$limit_products.'';



            }

		}

		

		if(Configuration::get($this->name.'rvis_on')==1){

		



			

		$data_products = array();

		

		if(isset($sql)){



				if(version_compare(_PS_VERSION_, '1.4', '>') && ($is_category==1) && ($is_search==0)){

					

				$PS_PRODUCTS_ORDER_BY = Configuration::get('PS_PRODUCTS_ORDER_BY');

				

				if($PS_PRODUCTS_ORDER_BY){

				

					switch($PS_PRODUCTS_ORDER_BY){

						case 0:

							$PS_PRODUCTS_ORDER_BY = 'name';

						break;

                        case 1:

                            $PS_PRODUCTS_ORDER_BY = 'price';

                        break;

                        case 2:

                            $PS_PRODUCTS_ORDER_BY = 'date_add';

                        break;

                        case 3:

                            $PS_PRODUCTS_ORDER_BY = 'date_upd';

                        break;

						case 4:

							$PS_PRODUCTS_ORDER_BY = 'position';

						break;

                        case 5:

                            $PS_PRODUCTS_ORDER_BY = 'manufacturer_name';

                        break;

                        case 6:

                            $PS_PRODUCTS_ORDER_BY = 'quantity';

                        break;

                        case 7:

                            $PS_PRODUCTS_ORDER_BY = 'reference';

                        break;

						default:

							$PS_PRODUCTS_ORDER_BY = 'name';

						break;

					}

				} else {

				$PS_PRODUCTS_ORDER_BY = Tools::getValue('orderby');

				}

				

				

				$PS_PRODUCTS_ORDER_WAY = Configuration::get('PS_PRODUCTS_ORDER_WAY');

				

				if($PS_PRODUCTS_ORDER_WAY) 

					$PS_PRODUCTS_ORDER_WAY = 'desc'; 

				else 

					$PS_PRODUCTS_ORDER_WAY = 'asc';





                    $cookie = $this->context->cookie;

                    $id_lang = (int)$cookie->id_lang;

                    //Context::getContext()->language->id

					

					$_category = new Category($id_category, $id_lang);

			    	$items =  $_category->getProducts($id_lang, (int)$start, (int)$step,$PS_PRODUCTS_ORDER_BY,$PS_PRODUCTS_ORDER_WAY);

				} else {

					$items = $db->ExecuteS($sql);

				}

				

				$items = !empty($items)?$items:array();

				if(sizeof($items)>0){

					foreach(@$items as $item){

						$id_product = $item['id_product'];

						

						$data_product = $this->getProduct(array('id'=>$id_product));

						foreach($data_product['product'] as $_item_product){

	    					$link_product = isset($_item_product['link'])?$_item_product['link']:'';

	    				}

	

	    				$count_review = $obj_reviewshelp->getCountReviews(array('id_product' => $id_product));

						$avg_rating = $obj_reviewshelp->getAvgReview(array('id_product' => $id_product));

				    	

						

						$data_products[$id_product] = array('id_product'=>$id_product, 

															'link'=>$link_product,

															'avg_rating'=>$avg_rating['avg_rating'],

															'count_review'=>$count_review

															);

					}

				}

				

		}

		//echo "<pre>"; var_dump($data_products);

		$smarty->assign($this->name.'_data_products', $data_products);

		}

		#### product list ####

		

    	}

        $smarty->assign($this->name.'is_category', $is_category);











        return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/head.tpl');

		



	}

	

public function hookFooter($params){

		$smarty = $this->context->smarty;

		$smarty->assign($this->name.'is16', $this->_is16);

		$_product_id = Tools::getValue("id_product");

		if(Configuration::get($this->name.'gsnipblock') &&

		  (Configuration::get($this->name.'id_hook_gsnipblock') == 7) &&

		  $_product_id &&

		  Configuration::get($this->name.'svis_on')

		  ){

		$smarty->assign($this->name.'footersnippet', 

									$this->snippetBlockSettings(array('product_id'=>$_product_id,'params'=>$params))

									);

		} else {	

		$smarty->assign($this->name.'footersnippet', '');

		}

		

		$smarty->assign($this->name.'is_product_page', $_product_id);

		$smarty->assign($this->name.'pinvis_on', Configuration::get($this->name.'pinvis_on'));









        $this->setLastReviewsBlockSettings(array('custom_page_name'=>'bottom'));



        ## badges ###

        $this->badges(array('custom_page_name'=>'bottom'));

        ## badges ###





        if(Configuration::get($this->name.'breadvis_on')==1){



            if(Tools::substr(_PS_VERSION_,0,3) == '1.3')

                $getTemplateVars_functions = 'get_template_vars';

            else

                $getTemplateVars_functions = 'getTemplateVars';





            if(!is_callable($smarty, $getTemplateVars_functions)){

                $getTemplateVars = $getTemplateVars_functions;

            }



            if (version_compare(_PS_VERSION_, '1.6', '<')){

                $path = $smarty->{$getTemplateVars}('path');

            } else {

                $path = $smarty->{$getTemplateVars}('breadcrumb');

                $path = $path['links'];

            }



            if($path) {





                if (version_compare(_PS_VERSION_, '1.6', '<')) {

                    $output = $path;



                    $path = preg_split('/<span class=\"navigation-pipe\">><\/span>/', $path);



                    foreach($path as $key => $value) {

                        $path[$key] = preg_replace('/^(<a href=\")([^>]*)([^<]*)(<\/a>)/', '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb">\1\2 itemprop="url"><span itemprop="title"\3</span>\4</span>', $value);

                    }



                    $home = Configuration::get('PS_SHOP_NAME');

                    $home = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'._PS_BASE_URL_SSL_.__PS_BASE_URI__.'" title="'.$home.'" itemprop="url"><span itemprop="title">'.$home.'</span></a></span>';







                } else {



                    foreach($path as $key => $value) {

                        $url = $value['url'];

                        $title = $value['title'];

                        $path[$key] = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$url.'" title="'.$title.'" ><span itemprop="title">'.$title.'</span></a></span>';

                    }



                    $home = '';



                }





                $output = '<div style="display:none">'.$home.implode('', $path).'</div>';







                $smarty->assign($this->name.'breadcustom',$output);

            } else {

                $smarty->assign($this->name.'breadcustom','');

            }



        } else {

            $smarty->assign($this->name.'breadcustom','');

        }





        ## user profile ##

        if(Configuration::get($this->name . $this->_prefix_review . 'adv_footer') == 1) {

            $this->setuserprofilegSettings();

        }

        $smarty->assign($this->name . $this->_prefix_review . 'adv_footer', Configuration::get($this->name . $this->_prefix_review . 'adv_footer'));

        ## user profile ##





        ## store reviews ##

        if(Configuration::get($this->name . 't_footer') == 1 || Configuration::get($this->name . 't_leftside') == 1

            || Configuration::get($this->name . 't_rightside') == 1) {

            $this->setStoreReviewsColumnsSettings();

            $smarty->assign($this->name.'is_mobile', $this->_is_mobile);

            $smarty->assign($this->name.'t_width', $this->_t_width);

        }

        $smarty->assign($this->name . 't_footer', Configuration::get($this->name . 't_footer'));

        $smarty->assign($this->name . 't_leftside', Configuration::get($this->name . 't_leftside'));

        $smarty->assign($this->name . 't_rightside', Configuration::get($this->name . 't_rightside'));

        ## store reviews ##







		return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/footer.tpl');



	}

	

	public function hookCustomerAccount($params)

	{

		$smarty = $this->context->smarty;

		$cookie = $this->context->cookie;

		$smarty->assign($this->name.'is16', $this->_is16);



		$smarty->assign($this->name.'is_ps15', $this->_is15);



        $this->basicSettingsHook();



		$is_logged = isset($cookie->id_customer)?$cookie->id_customer:0;

		$smarty->assign($this->name.'islogged', $is_logged);



        include_once(dirname(__FILE__) . '/classes/gsnipreviewhelp.class.php');

        $obj_reviewshelp = new gsnipreviewhelp();



        $id_lang = (int)($cookie->id_lang);

        $data_seo_url = $obj_reviewshelp->getSEOURLs(array('id_lang'=>$id_lang));



        $account_url = $data_seo_url['account_url'];

        $smarty->assign($this->name.'account_url', $account_url);

		if($is_logged)

			return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/my-account.tpl');

	}

	

	public function hookMyAccountBlock($params)

	{

		$smarty = $this->context->smarty;

		$cookie = $this->context->cookie;

		$smarty->assign($this->name.'is16', $this->_is16);



		$smarty->assign($this->name.'is_ps15', $this->_is15);



        $this->basicSettingsHook();



        $is_logged = isset($cookie->id_customer)?$cookie->id_customer:0;

		$smarty->assign($this->name.'islogged', $is_logged);





        include_once(dirname(__FILE__) . '/classes/gsnipreviewhelp.class.php');

        $obj_reviewshelp = new gsnipreviewhelp();



        $id_lang = (int)($cookie->id_lang);

        $data_seo_url = $obj_reviewshelp->getSEOURLs(array('id_lang'=>$id_lang));



        $account_url = $data_seo_url['account_url'];



        $smarty->assign($this->name.'account_url', $account_url);



		if($is_logged)

			return $this->display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/my-account-block.tpl');

	}

	

	

public function recurseCategoryForInclude($indexedCategories, $categories, $current, $id_category = 1, $id_category_default = NULL, $prefix = null)

	{

		$done = $this->context->done;

		static $irow;

		$id_obj = (int)(Tools::getValue($this->identifier));



		if (!isset($done[$current['infos']['id_parent']]))

			$done[$current['infos']['id_parent']] = 0;

		$done[$current['infos']['id_parent']] += 1;



		$todo = @sizeof($categories[$current['infos']['id_parent']]);

		$doneC = $done[$current['infos']['id_parent']];



        if(!$prefix)

            $prefix = '';



		$level = $current['infos']['level_depth'] + 1;

		$img = $level == 1 ? 'lv1.gif' : 'lv'.$level.'_'.($todo == $doneC ? 'f' : 'b').'.gif';

		echo '

		<tr class="'.($irow++ % 2 ? 'alt_row' : '').'">

			<td>

				<input type="checkbox" name="categoryBox'.$prefix.'[]" class="categoryBox'.$prefix.''.($id_category_default == $id_category ? ' id_category_default' : '').'" id="categoryBox'.$prefix.'_'.$id_category.'" value="'.$id_category.'" '.((in_array($id_category,explode(",",Configuration::get($this->name.'catbox'.$prefix))) OR in_array($id_category, $indexedCategories) OR ((int)(Tools::getValue('id_category')) == $id_category AND !(int)($id_obj))) ? ' checked="checked"' : '').' />

			</td>

			<td>

				'.$id_category.'

			</td>

			<td>

				<img src="../modules/'.$this->name.'/views/img/'.$img.'" alt="" /> &nbsp;<label for="categoryBox'.$prefix.'_'.$id_category.'" class="t">'.Tools::stripslashes($this->hideCategoryPosition($current['infos']['name'])).'</label>

			</td>

		</tr>';



		if (isset($categories[$id_category]))

			foreach ($categories[$id_category] AS $key => $row)

				if ($key != 'infos')

					$this->recurseCategoryForInclude($indexedCategories, $categories, $categories[$id_category][$key], $key, $id_category_default, $prefix, $row);

	}

	

	public function recurseCategoryIds($indexedCategories, $categories, $current, $id_category = 1, $id_category_default = NULL)

	{

		$done = $this->context->done;

		

		// set variables

		static $_idsCat;

		

		if ($id_category == 1) {

			$_idsCat = null;

		}

		

		if (!isset($done[$current['infos']['id_parent']]))

			$done[$current['infos']['id_parent']] = 0;

		$done[$current['infos']['id_parent']] += 1;



		

		$_idsCat[] = (string)$id_category;

		

		if (isset($categories[$id_category]))

			foreach ($categories[$id_category] AS $key => $row)

				if ($key != 'infos')

					$this->recurseCategoryIds($indexedCategories, $categories, $categories[$id_category][$key], $key, $id_category_default, $row);

		return $_idsCat;

	}

	

	public function getIdsCategories(){

		/// get all category ids ///

		$cookie = $this->context->cookie;

		$cat = new Category();

		$list_cat = $cat->getCategories($cookie->id_lang);

		$current_cat = Category::getRootCategory()->id;

		$cat_ids = $this->recurseCategoryIds($list_cat, $list_cat, $current_cat);

		$cat_ids = implode(",",$cat_ids);

		return $cat_ids;

		/// get all category ids ///

	}

    

	public function hideCategoryPosition($name)

	{

		return preg_replace('/^[0-9]+\./', '', $name);

	}

	

private function getProduct($data){

		

		$id = (int) $data['id'];

		$cookie = $this->context->cookie;

		

		$id_lang = (int)($cookie->id_lang);

		$result = Db::getInstance()->ExecuteS('

	            SELECT p.id_product, pl.`link_rewrite`, pl.`name`

	            FROM `'._DB_PREFIX_.'product` p

	            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)($id_lang).')

	            WHERE p.`active` = 1 AND p.`id_product` = '.(int)($id).' limit 1');

		

	    $data_all = array();

		foreach($result as $products){

			

			$id_product= isset($products['id_product'])?$products['id_product']:'';

			$link_rewrite= isset($products['link_rewrite'])?$products['link_rewrite']:'';

			$_category= isset($products['category'])?$products['category']:'';

			$_category = htmlspecialchars($_category); 

			//$_ean13= isset($products['ean13'])?$products['ean13']:'';

			

			$link = new Link();

			$_url = $link->getProductLink($id_product, 

										  $link_rewrite, 

										  $_category 

										  //$_ean13

										  );

		

			

			$_name = isset($products['name'])?Tools::stripslashes($products['name']):'';

			$_name = addslashes($_name);

			$_url = isset($_url)?$_url:'';

			

			$data_all[] = array('link' => $_url, 'name' => $_name);

		

		}

		

		

		

		return array('product' => $data_all);

	}

	



	

	

	

	public function _productData($data){

		$product = $data['product'];

		if(is_object($product) && !empty($product->id)){

		$cookie = $this->context->cookie;

		$id_lang = isset($data['id_lang'])?$data['id_lang']:(int)($cookie->id_lang);	

		

			/* Product URL */

			if (version_compare(_PS_VERSION_, '1.5', '>'))

				$link = Context::getContext()->link;

			else

				$link = new Link();

				

			$category = new Category((int)($product->id_category_default), $id_lang);



            if (version_compare(_PS_VERSION_, '1.5.5', '>=')) {

                   $product_url = $link->getProductLink((int)$product->id, null, null, null, 

                    									 $id_lang, null, 0, false);

             }

             elseif (version_compare(_PS_VERSION_, '1.5', '>')) {

               if (Configuration::get('PS_REWRITING_SETTINGS')) {

                     $product_url = $link->getProductLink((int)$product->id, null, null, null, 

                     									 $id_lang, null, 0, true);

               }

                else {

                    $product_url = $link->getProductLink((int)$product->id, null, null, null, 

                     									 $id_lang, null, 0, false);

                 }

            }

            else {

                  $product_url = $link->getProductLink((int)$product->id, @$product->link_rewrite[$id_lang],

                 									 $category->link_rewrite, $product->ean13, $id_lang);

            }

            

            

			if (version_compare(_PS_VERSION_, '1.5', '>'))

				$link = Context::getContext()->link;

			else

				$link = new Link();



			/* Image */

			$image = Image::getCover((int)($product->id));



			if ($image)

			{

                $block = isset($data['block'])?$data['block']:'';



				$available_types = ImageType::getImagesTypes('products');



                switch($block){



                    case 'reminder':

                        $type_img = Configuration::get($this->name.'img_size_em');

                        break;

                    default;



                        foreach ($available_types as $type){

                            $width = $type['width'];

                            if($width>400){

                                $type_img = $type['name'];

                                break;

                            }

                        }

                    break;

                }





				

				$image_link = $link->getImageLink(@$product->link_rewrite[$id_lang], (int)($product->id).'-'.(int)($image['id_image']),$type_img);

					

			    /* version 1.4 */

				if (strpos($image_link, 'http://') === FALSE && strpos($image_link, 'https://') === FALSE && version_compare(_PS_VERSION_, '1.4', '<'))

				{

					$image_link = 'http://'.$_SERVER['HTTP_HOST'].$image_link;

				}

			}

			else

			{

				$image_link = false;

				

			}

        }else {

			$image_link= false;

			$product_url = false;

		}     

            return array('product_url'=>$product_url,'image_link'=>$image_link);

	}



    public function getIdLang(){

        $cookie = $this->context->cookie;

        $id_lang = (int)($cookie->id_lang);

        return $id_lang;

    }



    public function getHttpost(){

        if(version_compare(_PS_VERSION_, '1.5', '>')){

            $custom_ssl_var = 0;

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')

                $custom_ssl_var = 1;





            //if ((bool)Configuration::get('PS_SSL_ENABLED') || $custom_ssl_var == 1 || (bool)Configuration::get('PS_SSL_ENABLED_EVERYWHERE'))

            if ($custom_ssl_var == 1)

                $_http_host = _PS_BASE_URL_SSL_.__PS_BASE_URI__;

            else

                $_http_host = _PS_BASE_URL_SSL_.__PS_BASE_URI__;



        } else {

            $_http_host = _PS_BASE_URL_SSL_.__PS_BASE_URI__;

        }

        return $_http_host;

    }







    public function setSEOUrls(){

        $smarty = $this->context->smarty;

        $cookie = $this->context->cookie;



        include_once(dirname(__FILE__) . '/classes/gsnipreviewhelp.class.php');

        $obj_reviewshelp = new gsnipreviewhelp();



        $id_lang = (int)($cookie->id_lang);

        $data_url = $obj_reviewshelp->getSEOURLs(array('id_lang'=>$id_lang));





        $shoppers_url = $data_url['users_url'];

        $shopper_url = $data_url['user_url'];

        $shopperaccount_url = $data_url['useraccount_url'];



        $account_url = $data_url['account_url'];

        $storereviews_url = $data_url['storereviews_url'];



        $smarty->assign(

            array(

                $this->name.'shoppers_url' => $shoppers_url,

                $this->name.'shopper_url' => $shopper_url,

                $this->name.'shopperaccount_url' => $shopperaccount_url,



                $this->name.'account_url'=>$account_url,

                $this->name.'storereviews_url' => $storereviews_url,

            )

        );



        $smarty->assign($this->name.'is16', $this->_is16);

        $smarty->assign($this->name.'is_ps15', $this->_is15);

        $smarty->assign($this->name.'pic', $this->path_img_cloud);





    }





    public function setuserprofilegSettings(){



        $smarty = $this->context->smarty;



        $smarty->assign($this->name.'is_uprof', Configuration::get($this->name.'is_uprof'));



        if(Configuration::get($this->name.'is_uprof') == 1) {



            $this->setSEOUrls();





            include_once(dirname(__FILE__) . '/classes/userprofileg.class.php');

            $obj_userprofileg = new userprofileg();



            $info_customers = $obj_userprofileg->getShoppersBlock(

                array(

                    'start' => 0,

                    'step' => (int)Configuration::get($this->name . $this->_prefix_review . 'shoppers_blc')

                )

            );





            $smarty->assign(array(

                $this->name . 'customers_block' => $info_customers['customers']

            ));





        }

    }



    public function setStoreReviewsColumnsSettings(){

        $smarty = $this->context->smarty;

        $smarty->assign($this->name.'is_storerev', Configuration::get($this->name.'is_storerev'));



        if(Configuration::get($this->name.'is_storerev') == 1) {



            include_once(dirname(__FILE__) . '/classes/storereviews.class.php');

            $obj_storereviews = new storereviews();

            $_data = $obj_storereviews->getTestimonials(array('start' => 0, 'step' => Configuration::get($this->name . 'tlast')));



            $smarty->assign(

                array(

                    $this->name . 'reviews' => $_data['reviews'],

                    $this->name . 'count_all_reviews' => $_data['count_all_reviews'])

            );





            $this->setStoreReviewsSettings();



            $this->_setPositionsforStoreWidget();



            $this->setSEOUrls();

        }

    }





    public function setStoreReviewsSettings(){

        $smarty = $this->context->smarty;



        $smarty->assign($this->name.'is_web', Configuration::get($this->name.'is_web'));

        $smarty->assign($this->name.'rssontestim', Configuration::get($this->name.'rssontestim'));



        $smarty->assign($this->name.'is_addr', Configuration::get($this->name.'is_addr'));

        $smarty->assign($this->name.'is_country', Configuration::get($this->name.'is_country'));

        $smarty->assign($this->name.'is_city', Configuration::get($this->name.'is_city'));



        $smarty->assign($this->name.'is_captcha', Configuration::get($this->name.'is_captcha'));

        $smarty->assign($this->name.'is_company', Configuration::get($this->name.'is_company'));



        $smarty->assign($this->name.'is_avatar', Configuration::get($this->name.'is_avatar'));



        $smarty->assign($this->name.'whocanadd'.$this->_prefix_shop_reviews, Configuration::get($this->name.'whocanadd'.$this->_prefix_shop_reviews));



        include_once(dirname(__FILE__).'/classes/storereviews.class.php');

        $obj_storereviews = new storereviews();

        $avg_rating = $obj_storereviews->getAvgReview();

        $smarty->assign($this->name.'avg_rating'.$this->_prefix_shop_reviews, $avg_rating['avg_rating']);

        $smarty->assign($this->name.'avg_decimal'.$this->_prefix_shop_reviews, $avg_rating['avg_rating_decimal']);



        $count_reviews = $obj_storereviews->getCountReviews();

        $smarty->assign($this->name.'count_reviews'.$this->_prefix_shop_reviews, $count_reviews);



        $smarty->assign($this->name.'sh_name'.$this->_prefix_shop_reviews, @Configuration::get('PS_SHOP_NAME'));





        if(version_compare(_PS_VERSION_, '1.6', '>')){

            $_http_host = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__;

        } else {

            $_http_host = _PS_BASE_URL_SSL_.__PS_BASE_URI__;

        }



        $smarty->assign($this->name.'sh_url'.$this->_prefix_shop_reviews, $_http_host);





        $smarty->assign($this->name.'t_lefts', ($count_reviews == 0)?0:Configuration::get($this->name.'t_lefts'));

        $smarty->assign($this->name.'t_rights', ($count_reviews == 0)?0:Configuration::get($this->name.'t_rights'));

        $smarty->assign($this->name.'t_footers', ($count_reviews == 0)?0:Configuration::get($this->name.'t_footers'));

        $smarty->assign($this->name.'t_homes', ($count_reviews == 0)?0:Configuration::get($this->name.'t_homes'));

        $smarty->assign($this->name.'t_leftsides', ($count_reviews == 0)?0:Configuration::get($this->name.'t_leftsides'));

        $smarty->assign($this->name.'t_rightsides', ($count_reviews == 0)?0:Configuration::get($this->name.'t_rightsides'));

        $smarty->assign($this->name.'t_tpages', ($count_reviews == 0)?0:Configuration::get($this->name.'t_tpages'));











        $smarty->assign($this->name.'is_ps15', $this->_is15);

        $smarty->assign($this->name.'is15', $this->_is15);

        if(version_compare(_PS_VERSION_, '1.5', '<')){

            $smarty->assign($this->name.'is14', 1);

        } else {

            $smarty->assign($this->name.'is14', 0);

        }



        $this->_setPositionsforStoreWidget();





        $smarty->assign($this->name.'is_storerev', Configuration::get($this->name.'is_storerev'));





    }





    private function _setPositionsforStoreWidget(){

        $smarty = $this->context->smarty;

        $smarty->assign($this->name.'st_left', Configuration::get($this->name.'st_left'));

        $smarty->assign($this->name.'st_right', Configuration::get($this->name.'st_right'));

        $smarty->assign($this->name.'st_footer', Configuration::get($this->name.'st_footer'));

        $smarty->assign($this->name.'st_home', Configuration::get($this->name.'st_home'));

        $smarty->assign($this->name.'st_leftside', Configuration::get($this->name.'st_leftside'));

        $smarty->assign($this->name.'st_rightside', Configuration::get($this->name.'st_rightside'));





        $smarty->assign($this->name.'mt_left', Configuration::get($this->name.'mt_left'));

        $smarty->assign($this->name.'mt_right', Configuration::get($this->name.'mt_right'));

        $smarty->assign($this->name.'mt_footer', Configuration::get($this->name.'mt_footer'));

        $smarty->assign($this->name.'mt_home', Configuration::get($this->name.'mt_home'));

        $smarty->assign($this->name.'mt_leftside', Configuration::get($this->name.'mt_leftside'));

        $smarty->assign($this->name.'mt_rightside', Configuration::get($this->name.'mt_rightside'));

    }









	

	

	

}

