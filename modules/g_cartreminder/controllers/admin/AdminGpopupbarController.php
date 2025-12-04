<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */
 
include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GpopupbarModel.php');
class AdminGpopupbarController extends ModuleAdminController
{
    
    public function __construct()
    {
        $this->bootstrap = true;
        $this->context = Context::getContext();
        $this->table   = 'gabandoned_bar';
        $this->lang    = true;
        $this->className = 'GpopupbarModel';
        $this->tpl_form_vars['defaultFormLanguage'] = (int)Configuration::get('PS_LANG_DEFAULT');
        Context::getContext()->smarty->assign($this->tpl_form_vars);
        parent::__construct();
    }
    public function renderOptions()
    {
        $link  = new Link();$this->html ='';$id_shop = $this->context->shop->id;$controller = Tools::getValue('controller');$object  = new GpopupbarModel(1, null, $id_shop);
        $langs = $this->context->controller->getLanguages();$titles= array();
        if (Validate::isLoadedObject($object)) {
            foreach ($langs as $lang) {
                $titles[$lang['id_lang']] = $object->title[$lang['id_lang']];
            }
            Context::getContext()->smarty->assign(
                array(
                    'active'       => $object->active,
                    'position'     => $object->position,
                    'delay'        => $object->delay,
                    'textcolor'    => $object->textcolor,
                    'backgroundcolor'=> $object->backgroundcolor,
                    'titles'        => $titles,
                )
            );
        } else {
            foreach ($langs as $lang) {
                $titles[$lang['id_lang']] = '';
            }
            Context::getContext()->smarty->assign(
                array(
                    'active'       => '0',
                    'position'     => '1',
                    'delay'        => '30',
                    'textcolor'    => '#ffffff',
                    'backgroundcolor'=> '#000',
                    'titles'        => $titles,
                )
            );
        }
        Context::getContext()->smarty->assign(
            array(
                'languages'    => Tools::jsonEncode(Language::getLanguages()),
                'g_module_url' => $this->context->shop->getBaseURL().'modules/g_cartreminder/',
            )
        );
        $this->fields_options =  array(
            'bar' => array(
                'title' => $this->l(' Popup Bar'),
                'fields' => array(
                    array(
                        'type' => 'popupbar',
                        'name' => 'popupbar',
                    ),
                ),
                'submit' => array('title' => $this->l('Save'), 'id' => 'submitPPBAR'),
            ),
        );
        if ($this->fields_options && is_array($this->fields_options)) {
            $helper = new HelperOptions($this);
            $this->setHelperDisplay($helper);
            $helper->toolbar_scroll = true;
            $helper->table = $this->table;
            $helper->title = $this->l('Popup Bar');
            $helper->id    = $this->id;
            $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
            $helper->default_form_language = $lang->id;
            $helper->tpl_vars = array(
    			'languages'   => $this->context->controller->getLanguages(),
    			'id_language' => $this->context->language->id,
    		);
            if (Tools::getValue('savesuccssetfull') == 1) {
                $this->html .= $this->module->displayConfirmation($this->l('Settings updated.'));
            }
            $this->html .= $this->getHTMLtab($link, 'tabs', $controller);
            $this->html .= $this->getHTMLtab($link, 'start', $controller);
            $this->html .= $helper->generateOptions($this->fields_options);
            $this->html .= $this->getHTMLtab($link, 'end', $controller);
            return $this->html;
        }
    }
    public function postProcess()
	{
        $langs   = $this->context->controller->getLanguages();
        $id_shop = $this->context->shop->id;
        $object  = new GpopupbarModel(1, null, $id_shop);
        $action  = Tools::getValue('action');
        if (isset($action) && $action == 'SubmitPopupbar') {
            $redirectAdmin    = Context::getContext()->link->getAdminLink('AdminGpopupbar')."&conf=4";
            $object->active   = Tools::getValue('active');
            $object->position = Tools::getValue('position');
            $object->delay    = Tools::getValue('delay');
            $object->textcolor       = Tools::getValue('textcolor');
            $object->backgroundcolor = Tools::getValue('backgroundcolor');
            foreach ($langs as $lang) {
                $object->title[$lang['id_lang']] = Tools::getValue('title_'.$lang['id_lang']);
            }
            if (Validate::isLoadedObject($object)) {
                $object->update();
            } else {
                $object->save();
            }
            echo Tools::jsonEncode(array('error'=>'okie', 'link'=>$redirectAdmin));die;
        }
        parent::postProcess();
    }
    public function getHTMLtab($link, $name, $controller){
        $dirimg = '../modules/g_cartreminder/views/img';
        $version = 'PS16';
        if (version_compare(_PS_VERSION_, '1.7.0.0', '>=')){
            $version = 'PS17';
        }
        $this->context->smarty->assign(array(
            'controller'=> $controller,
            'name'      => $name,
            'link'      => $link,
            'dirimg'    => $dirimg,
            'version'   => $version,
        ));
        $html = $this->context->smarty->fetch(_PS_MODULE_DIR_ . "g_cartreminder/views/templates/admin/tabs/htmlall.tpl");
        return $html;
    }
}