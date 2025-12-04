<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */

include_once(_PS_MODULE_DIR_ . 'g_cartreminder/classes/GpopupModel.php');
class AdminGpopupController extends ModuleAdminController
{
    public function __construct()
    {
        $this->className = 'GpopupModel';
        $this->table     = 'gabandoned_popup';
        parent::__construct();
        $this->deleted   = false;
        $this->explicitSelect = true;
        $this->lang      = true;
        $this->context   = Context::getContext();
        $this->bootstrap = true;
        $this->_defaultOrderBy     = 'id_gabandoned_popup';
        $this->position_identifier = 'id_gabandoned_popup';
        $this->filter    = true;
        if (Shop::isFeatureActive()) {
            Shop::addTableAssociation($this->table, array('type' => 'shop'));
        }
        $this->bulk_actions = array('delete' => array(
                'text'    => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon'    => 'icon-trash'));
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        
        $this->fields_list = array(
            'id_gabandoned_popup' => array(
                'title' => $this->l('ID'),
                'type'  => 'int',
                'width' => 'auto'),
            'name' => array(
                'title' => $this->l('Name Setting'),
                'width' => 'auto',),
            'mincart'   => array(
                'title' => $this->l('Min Cart Amount'),
                'width' => 'auto',
                'search' => false,
                'type'  => 'price',
                'callback' => 'convertpricemincart',),
            'day'  => array('title' => $this->l('Hrs Delay'), 'width' => 'auto'),
            'hrs'  => array('title' => $this->l('Minutes Delay'), 'width' => 'auto'),
            'code' => array(
                'title'  => $this->l('Vourcher'),
                'width'  => 'auto',
                'type'   => 'text',
                'search' => true,
                ),
            'active' => array(
                'title'   => $this->l('Status'),
                'width'   => 'auto',
                'active'  => 'status',
                'type'    => 'bool',
                'orderby' => false),
            'time' => array(
                'title'   => $this->l('Date'),
                'width'   => 'auto',
                'type'    => 'datetime',
                'orderby' => true),
            );
    }
    public function renderList()
    {
        $this->html = '';
        $link       = $this->context->link;
        $controller = Tools::getValue('controller');
        if (!($this->fields_list && is_array($this->fields_list))) {
            return false;
        }
        $this->getList($this->context->language->id);
        $helper = new HelperList();
        if (!is_array($this->_list)) {
            $this->displayWarning($this->l('Bad SQL query', 'Helper') . '<br />' . htmlspecialchars($this->_list_error));
            return false;
        }
        $this->setHelperDisplay($helper);
        $this->html .= $this->getHTMLtab($link, 'tabs', $controller);
        $this->html .= $this->getHTMLtab($link, 'start', $controller);
        $this->html .= $this->getHTMLtab($link, 'date_get_cart', $controller);
        $this->html .= $helper->generateList($this->_list, $this->fields_list);
        $this->html .= $this->getHTMLtab($link, 'selecttemplate_popup', $controller);
        $this->html .= $this->getHTMLtab($link, 'end', $controller);
        return $this->html;
    }
    public function renderForm()
    {
        $link    = $this->context->link;
        $id_shop = Shop::getContextShopID();
        $languages = Language::getLanguages(false);
        $id_popup= (int)Tools::getValue('id_gabandoned_popup');
        $Obj     = new GpopupModel((int)$id_popup, null, (int)$id_shop);
        $Currencies = Currency::getCurrencies();
        if (Validate::isLoadedObject($Obj)) {
            $this->fields_value  = array(
                'name'           =>  $Obj->name,
                'active'         =>  $Obj->active,
                'imgbackground'  =>  $Obj->imgbackground,
                'day'            =>  $Obj->day,
                'hrs'            =>  $Obj->hrs,
                'mincart'        =>  Tools::jsonDecode($Obj->mincart, true),
                'maxwidth'       =>  $Obj->maxwidth,
                'display'        =>  $Obj->display,
                'sosicalfb'      =>  $Obj->sosicalfb,
                'sosicaltw'      =>  $Obj->sosicaltw,
                'sosicalgg'      =>  $Obj->sosicalgg,
                'code'           =>  $Obj->code,
                'autocode'       =>  $Obj->autocode,
                'autocodetype'   =>  $Obj->autocodetype,
                'autocodevalue'  =>  $Obj->autocodevalue,
                'autocodeday'    =>  $Obj->autocodeday,
                'autocodeship'   =>  $Obj->autocodeship,
                'currency'       =>  $this->context->currency,
                'colorbackground'=>  $Obj->colorbackground,
                'html'           =>  $Obj->html,
                'displayss'      =>  $Obj->displayss,
                'customcss'      =>  $Obj->customcss,
                'countdown'      =>  $Obj->countdown,
                'reset_countdown'=>  $Obj->reset_countdown,
                'autocodeid_currency'=> $Obj->autocodeid_currency,
                'autocodetax'        => $Obj->autocodetax,
                'fornlink'       =>  $this->context->shop->getBaseURL(),
            );
        } else {
            $id_template_default = (int)Tools::getValue('templatedefault');
            $this->context->smarty->assign(array(
                'name'      => 'PP'.$id_template_default,
            ));
            $html_teplate_popup = array();
            $html_teplate_popup_get = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'g_cartreminder/views/templates/admin/defaulttemplate/PPtemplate.tpl');
            foreach ($languages as $lang) {
                $html_teplate_popup[$lang['id_lang']] = $html_teplate_popup_get;
            }
            $this->fields_value  = array(
                'name'           =>  $Obj->name,
                'active'         =>  '0',
                'imgbackground'  =>  '',
                'day'            =>  '',
                'hrs'            =>  '',
                'mincart'        =>  array(),
                'maxwidth'       =>  '600',
                'display'        =>  '1',
                'sosicalfb'      =>  '',
                'sosicaltw'      =>  '',
                'sosicalgg'      =>  '',
                'code'           =>  '',
                'autocode'       =>  '0',
                'autocodetype'   =>  '1',
                'autocodevalue'  =>  '',
                'autocodeday'    =>  '',
                'autocodeship'   =>  '',
                'currency'       =>  $this->context->currency,
                'colorbackground'=> '#000000',
                'fornlink'       =>  $this->context->shop->getBaseURL(),
                'html'           =>  $html_teplate_popup,
                'displayss'      =>  $Obj->displayss,
                'customcss'      =>  '',
                'countdown'      =>  '5',
                'reset_countdown'=> '0',
                'autocodeid_currency'=> $this->context->currency->id,
                'autocodetax'        => '0',
            );
        }
        $this->fields_value['Currencies'] = $Currencies;
        $this->fields_value['g_module_url'] = $this->context->shop->getBaseURL().'modules/g_cartreminder/';
        $this->fields_form = array(
            'legend' => array('title' => $this->l('Popup Config'), 'icon' => 'icon-cogs'),
            'input' => array(
                array(
                    'type'   => 'popup_template',
                    'name'   => 'popup_template',
                    ),
                ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name'  => 'submitPopup',
                'class' => 'btn btn-default pull-right'),
            'buttons' => array(
                    'preview_html' => array(
                    'name'  => 'submitPreviewpopup',
                    'type'  => 'button',
                    'title' => $this->l('Save And Preview Popup'),
                    'class' => 'pull-right submitPreviewpopup',
                    'icon'  => 'process-icon-preview '), ),
            );
        if (Shop::isFeatureActive()) {
                $this->fields_form['input'][] = array(
                    'type' => 'shop',
                    'label' => $this->l('Shop association'),
                    'name' => 'checkBoxShopAsso',
                    );
            }
        $controller = Tools::getValue('controller');
        $html    = $this->getHTMLtab($link, 'tabs', $controller);
        $html   .= $this->getHTMLtab($link, 'start', $controller);
        $endhtml = $this->getHTMLtab($link, 'end', $controller);
        return $html.parent::renderForm().$endhtml;
    }
    public function postProcess()
	{
        if (Tools::getValue('action') == 'SubmitPopup') {
           echo $this->ajaxProcessSubmitPopup();die;
        } elseif (Tools::getValue('action') == 'getHTMLPP') {
            echo $this->ajaxProcessgetHTMLPP();die;
        }
        parent::postProcess();
    }
    public function ajaxProcessgetHTMLPP() 
    {
        $id_lang = Tools::getValue('id_lang');
        $html    = GpopupModel::getHTMLPP($id_lang);
        return  Tools::jsonEncode(array('res'=>true, 'html'=>$html));
    }
    public function ajaxProcessSubmitPopup()
    {
        $id_lang      = (int)$this->context->language->id;
        $id_shop      = (int)$this->context->shop->id;
        $id_currency  = $this->context->currency->id;
        $Tailfiles    = array('.png', '.jpg');
        $redirectAdmin= Context::getContext()->link->getAdminLink('AdminGpopup');
        $popupSetting = new GpopupModel((int)Tools::getValue('id_gabandoned_popup'), null, $id_shop);
        if ($_FILES != 'undefined' && $_FILES['imgbackground']['error'] == 0 && $_FILES['imgbackground']['size'] != 0) {
            $k = false;
            foreach ($Tailfiles as $Tailfile) {
                $pos = strpos($_FILES['imgbackground']['name'], $Tailfile);
                if ($pos !== false) {
                    move_uploaded_file($_FILES['imgbackground']['tmp_name'], _PS_MODULE_DIR_ . "g_cartreminder/image/popup/" . $_FILES['imgbackground']['name'] );
                    $k = true;
                }
            } 
            if ($k != true) {
                echo Tools::jsonEncode(array('res'=>false, 'status'=>$this->l('ERROR: File not in format (.png, .jpg)'), 'redirectAdmin'=> $redirectAdmin.'&conf=3'));
                die;
            }
        } elseif (Validate::isLoadedObject($popupSetting)) {
            if ($popupSetting->imgbackground !='') {
                if (!is_file(_PS_MODULE_DIR_ .  "g_cartreminder/image/popup/" . $popupSetting->imgbackground )) {
                    echo Tools::jsonEncode(array('res'=>false, 'status'=>$this->l('The FILE no longer exists'), 'redirectAdmin'=> $redirectAdmin.'&conf=3'));
                    die;
                }
            }
        }
        $mincarts = array();
        $minvalue = Tools::getValue('mincart');
        foreach ($minvalue as $key=>$mincart) {
            $mincarts[$key]   = (float)$mincart;
            if ($id_currency != $key && $mincart == '') {
                $mincarts[$key]   = (float)$minvalue[$id_currency];
            }
        }
        $popupSetting->active = (int)Tools::getValue('active');
        $popupSetting->day    = (int)Tools::getValue('day');
        $popupSetting->hrs    = (int)Tools::getValue('hrs');
        $popupSetting->maxwidth  = (float)Tools::getValue('maxwidth');
        $popupSetting->mincart   = Tools::jsonEncode($mincarts);
        $popupSetting->display   = Tools::getValue('display');
        $popupSetting->displayss = Tools::getValue('displayss');
        $popupSetting->sosicalfb = Tools::getValue('sosicalfb');
        $popupSetting->sosicaltw = Tools::getValue('sosicaltw');
        $popupSetting->sosicalgg = Tools::getValue('sosicalgg');
        $popupSetting->colorbackground = Tools::getValue('colorbackground');
        if($popupSetting->imgbackground !='' && Tools::getValue('remove_background')){
            if (is_file(_PS_MODULE_DIR_ .  "g_cartreminder/image/popup/" . $popupSetting->imgbackground )) {
                @unlink(_PS_MODULE_DIR_ .  "g_cartreminder/image/popup/" . $popupSetting->imgbackground);
            }
            $popupSetting->imgbackground = '';
        }else{
            $popupSetting->imgbackground   = Tools::getValue('filename');
        }
        $popupSetting->autocode        = Tools::getValue('autocode');
        $popupSetting->autocodetype    = Tools::getValue('autocodetype');
        $popupSetting->autocodevalue   = Tools::getValue('autocodevalue');
        $popupSetting->autocodeday     = Tools::getValue('autocodeday');
        $popupSetting->autocodeship    = Tools::getValue('autocodeship');
        $popupSetting->customcss       = Tools::getValue('customcss');
        $popupSetting->code = Tools::getValue('code');
        $popupSetting->time = date('Y-m-d H:i:s', time());
        
        $popupSetting->autocodeid_currency= (int)Tools::getValue('autocodeid_currency');
        $popupSetting->autocodetax        = (int)Tools::getValue('autocodetax');
        $popupSetting->reset_countdown    = (int)Tools::getValue('autocodeship');
        $popupSetting->countdown          = (int)Tools::getValue('countdown');

        $langs = Language::getLanguages(false);
        foreach ($langs as $lang) {
            $name_lang = Tools::getValue('name_'.$lang["id_lang"]);
            $html_lang = Tools::getValue('html_'.$lang["id_lang"]);
            if (!Validate::isCleanHtml($html_lang)) {
                echo Tools::jsonEncode(array('res'=>false, 'status'=>$this->l('The Html field is invalid. -'.$lang['name']), 'redirectAdmin'=> ''));
                die;
            }
            if ($id_lang == $lang["id_lang"]) {
                if ( Tools::getValue('name_'.$id_lang) =='') {
                    echo Tools::jsonEncode(array('res'=>false, 'status'=>$this->l('Name This field is required at least in -'.$lang['name']), 'redirectAdmin'=> ''));
                    die;
                }
                if ( Tools::getValue('html_'.$id_lang) =='') {
                    echo Tools::jsonEncode(array('res'=>false, 'status'=>$this->l('Popup Template This field is required at least in -'.$lang['name']), 'redirectAdmin'=> ''));
                    die;
                }
            } else {
                if ($name_lang == '') {
                    $name_lang = Tools::getValue('name_'.$id_lang);
                }
                if ($html_lang == '') {
                    $html_lang = Tools::getValue('html_'.$id_lang);
                }
            }
            $popupSetting->name[$lang["id_lang"]] = $name_lang;
            $popupSetting->html[$lang["id_lang"]] = $html_lang;
        }
        if (Validate::isLoadedObject($popupSetting)) {
            $popupSetting->update();
        } else {
            $popupSetting->save();
        }
        if (Tools::getValue('saveANDstay') == 'false' || Tools::getValue('saveANDstay') == false) {
            $url_rewrite = Context::getContext()->link->getModuleLink('g_cartreminder','gcartreminder',array('idDemo'=>(int)$popupSetting->id));
            if (!strpos($url_rewrite, 'index.php')){
                $url_rewrite = str_replace('?module=g_cartreminder&controller=gcartreminder','',$url_rewrite);
            }
            echo Tools::jsonEncode(array('res'=>true, 'status'=>'', 'redirectAdmin'=> $redirectAdmin.'&conf=4&id_gabandoned_popup='.(int)$popupSetting->id.'&updategabandoned_popup&token='.Tools::getAdminTokenLite('AdminGpopup'), 'link_blank'=> $url_rewrite,));
            die;
        } else {
            echo Tools::jsonEncode(array('res'=>true, 'status'=>'', 'redirectAdmin'=> $redirectAdmin.'&conf=4', 'link_blank'=> Context::getContext()->shop->getBaseURL()));
            die;
        }
    }
    public function convertpricemincart($price) {
        $id_currency = $this->context->currency->id;
        $prices      = Tools::jsonDecode($price, true);
        return Tools::displayPrice($prices[$id_currency]);
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