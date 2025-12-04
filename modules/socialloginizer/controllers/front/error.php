<?php
/**
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store. 
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2015 Knowband
* @license   see file: LICENSE.txt
*/

class SocialLoginizerErrorModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		parent::initContent();
		$account_link = $this->context->link->getPageLink('my-account', 'true');
		$this->context->smarty->assign('acc_link', $account_link);
		$this->setTemplate('error.tpl');
	}
}
?>
