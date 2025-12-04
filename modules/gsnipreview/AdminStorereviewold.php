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

class AdminStorereviewold extends AdminTab{

	public function __construct()

	{
		$this->module = 'gsnipreview';
		
		if(version_compare(_PS_VERSION_, '1.5', '>')){
			$this->multishop_context = Shop::CONTEXT_ALL;
		}
		
		
		parent::__construct();
		
	}
	
	public function addJS(){
		
	}
	
public function addCss(){
		
	}
	
	public function display()
	{
		
		
	}
	
}