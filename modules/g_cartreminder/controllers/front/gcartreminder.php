<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 *
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link         http://www.globosoftware.net
 */
 
class G_cartreminderGcartreminderModuleFrontController extends ModuleFrontController {
    public function __construct()
	{
		parent::__construct();
		$this->context = Context::getContext();
	}
    public function initContent()
    {
        parent::initContent();
        Tools::redirect('index.php?controller=index?idDemo='.(int)Tools::getValue('idDemo').'&module=g_cartreminder');
    }
}