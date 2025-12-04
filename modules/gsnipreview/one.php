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

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');

		$module_name = 'gsnipreview';
		
		if (version_compare(_PS_VERSION_, '1.5', '<')){
			require_once(_PS_MODULE_DIR_.$module_name.'/backward_compatibility/backward.php');
		} else{
			$smarty = Context::getContext()->smarty;
		}
		
		$rvis_on = Configuration::get($module_name.'rvis_on');
		$ratings_on = Configuration::get($module_name.'ratings_on');
		$title_on = Configuration::get($module_name.'title_on');
		$text_on = Configuration::get($module_name.'text_on');
		
		if(version_compare(_PS_VERSION_, '1.6', '>')){
		 	$smarty->assign($module_name.'is16' , 1);
		} else {
		 	$smarty->assign($module_name.'is16' , 1);
		}
		
		if ($rvis_on == 1){
		}else {
				Tools::redirect('index.php');
		}
		if($ratings_on == 1 || $title_on == 1 || $text_on == 1){
		} else {
				Tools::redirect('index.php');
		}
		
		if (version_compare(_PS_VERSION_, '1.5', '>')) {
			Tools::redirect('index.php');
		} 


		include_once(dirname(__FILE__).'/../../header.php');


	
		include_once(dirname(__FILE__).'/classes/gsnipreviewhelp.class.php');
		$obj = new gsnipreviewhelp();
		$data = $obj->getAllReviews(array('start'=>0,
	    						  'step'=>(int)Configuration::get($module_name.'revperpageall')
	    						 ));
		include_once(dirname(__FILE__).'/gsnipreview.php');
		$objgsnipreview = new gsnipreview();
		$data_translate = $objgsnipreview->translateCustom();
		
			
			switch(Configuration::get($module_name.'stylestars')){
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
			
		$paging = $obj->paging(array('start'=>0,
						   'step'=> (int)Configuration::get($module_name.'revperpageall'),
						   'count' => $data['count_all'],
						   'page' => $data_translate['page'],
						   'all' => 1
						   )
					);
		$rvis_on = Configuration::get($module_name.'rvis_on');
		$ratings_on = Configuration::get($module_name.'ratings_on');
		$title_on = Configuration::get($module_name.'title_on');
		$text_on = Configuration::get($module_name.'text_on');
		
		$smarty->assign('meta_title' , "All Reviews");
		$smarty->assign('meta_description' , "All Reviews");
		$smarty->assign('meta_keywords' , "All Reviews");
			
		// Smarty display
		$smarty->assign(array(
			$module_name.'reviews_all' => $data['reviews'],
			$module_name.'activestar' => $activestar,
			$module_name.'noactivestar' => $noactivestar,
			$module_name.'paging' => $paging,
			$module_name.'rvis_on' => $rvis_on,
			$module_name.'ratings_on' => $ratings_on,
			$module_name.'title_on' => $title_on,
			$module_name.'text_on' => $text_on
			));

	echo Module::display(dirname(__FILE__).'/gsnipreview.php', 'views/templates/hooks/all.tpl');

	

	include_once(dirname(__FILE__).'/../../footer.php');
