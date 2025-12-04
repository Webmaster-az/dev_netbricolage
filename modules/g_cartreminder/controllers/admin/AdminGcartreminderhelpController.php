<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */

class AdminGcartreminderhelpController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
    }
    
    /**
     * Function used to render the options for this controller
     */
    public function renderOptions()
    {
        $version = 'PS16';
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
            $version = 'PS17';
        }
        $this->html = '';
        $controller = Tools::getValue('controller');
        $dirimg = '../modules/g_cartreminder/views/img';
        $this->context->smarty->assign(
            array(
                'controller'   => $controller,
                'g_module_url' => $this->context->shop->getBaseURL(true).'modules/g_cartreminder/',
                'gettoken'     => sha1(_COOKIE_KEY_.'g_cartreminder'),
                'name'         => 'tabs',
                'link'         => new Link(),
                'usingSecureMode' =>Tools::usingSecureMode(),
                'dirimg'  => $dirimg,
                'version' => $version,
                'url_cronjobs' => $this->context->link->getModuleLink('g_cartreminder','cronjobs',array('token'=>sha1(_COOKIE_KEY_.'g_cartreminder'))),
            )
        );
        $this->html .= $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/tabs/htmlall.tpl");
        $this->html .= $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/gcartreminderhelp/gcartreminderhelp.tpl");
        return $this->html;
    }
}
