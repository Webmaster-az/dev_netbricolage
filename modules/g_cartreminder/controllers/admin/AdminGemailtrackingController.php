<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 *
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */

class AdminGemailtrackingController extends ModuleAdminController
{
    public function __construct()
	{
		$this->bootstrap = true;
		$this->display = 'edit';
		parent::__construct();
        $this->meta_title = $this->l('Email Tracking');
		if (!$this->module->active)
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminHome'));
	}
    public function initContent()
	{
		$this->display = 'edit';
        $this->content = '';
        $this->initTabModuleList();
		$this->initToolbar();
		$this->initPageHeaderToolbar();
        $controller = Tools::getValue('controller');
        $this->content .= $this->getHTMLtab($this->context->link, 'tabs', $controller);
        $this->content .= $this->getHTMLtab($this->context->link, 'start', $controller);
        $this->content .= $this->renderForm();
        $this->content .= $this->getHTMLtab($this->context->link, 'end', $controller);
        $this->context->smarty->assign(array(
    			'content' => $this->content,
    			'url_post' => self::$currentIndex.'&token='.$this->token,
    		));
        if(version_compare(_PS_VERSION_,'1.6') == 1){
    		$this->context->smarty->assign(array(
    			'show_page_header_toolbar' => $this->show_page_header_toolbar,
    			'page_header_toolbar_title' => $this->page_header_toolbar_title,
    			'page_header_toolbar_btn' => $this->page_header_toolbar_btn
    		));
        }
	}
    public function initTabModuleList(){
        if(version_compare(_PS_VERSION_,'1.5.4.0') == -1)
            return true;
        else
            return parent::initTabModuleList();
    }
    public function initToolBarTitle()
	{
		$this->toolbar_title[] = $this->module->displayName;
		$this->toolbar_title[] = $this->l('Email Tracking');
	}
    public function initPageHeaderToolbar()
	{
        if(version_compare(_PS_VERSION_,'1.6') == 1){
		  parent::initPageHeaderToolbar();
        }
	}
    public function postProcess()
	{

        if (Tools::isSubmit('saveConfigEmaiTracking'))
        {
            $shop_groups_list = array();
			$shops = Shop::getContextListShopID();
            $shop_context = Shop::getContext();
			foreach ($shops as $shop_id)
			{
				$shop_group_id = (int)Shop::getGroupFromShop((int)$shop_id, true);
				if (!in_array($shop_group_id, $shop_groups_list))
					$shop_groups_list[] = (int)$shop_group_id;
				$res = Configuration::updateValue('GC_EMAIL_TRACKING_ID', Tools::getValue('GC_EMAIL_TRACKING_ID'), false, (int)$shop_group_id, (int)$shop_id);
            }
			/* Update global shop context if needed*/
			switch ($shop_context)
			{
				case Shop::CONTEXT_ALL:
					$res = Configuration::updateValue('GC_EMAIL_TRACKING_ID', Tools::getValue('GC_EMAIL_TRACKING_ID'));
                    if (count($shop_groups_list))
					{
						foreach ($shop_groups_list as $shop_group_id)
						{
							$res = Configuration::updateValue('GC_EMAIL_TRACKING_ID', Tools::getValue('GC_EMAIL_TRACKING_ID'), false, (int)$shop_group_id);
						}
					}
					break;
				case Shop::CONTEXT_GROUP:
					if (count($shop_groups_list))
					{
						foreach ($shop_groups_list as $shop_group_id)
						{
							$res = Configuration::updateValue('GC_EMAIL_TRACKING_ID', Tools::getValue('GC_EMAIL_TRACKING_ID'), false, (int)$shop_group_id);
						}
					}
					break;
			}
            if (!$res)
				$this->errors[] = $this->l('The configuration could not be updated.');
			else
				Tools::redirectAdmin($this->context->link->getAdminLink('AdminGemailtracking', true));
        }
    }
    public function renderForm() {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Email Tracking'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
    				'type' => 'text',
    				'name' => 'GC_EMAIL_TRACKING_ID',
            'hint' => $this->l('E.g UA-XXXXX'),
    				'label' => $this->l('Google Tracking Id'),
            'desc'=>$this->l('Tracking your email reminder by Google Analytic. Client Id: 501, Event: Open on Email abandoned cart reminder 5 in 1')
    			),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'saveConfigEmaiTracking'
            )
        );
        if (Shop::isFeatureActive()) {
                $this->fields_form['input'][] = array(
                    'type' => 'shop',
                    'label' => $this->l('Shop association'),
                    'name' => 'checkBoxShopAsso',
                    );
            }
        $this->fields_value = $this->getConfigFieldsValues();
        return parent::renderForm();
    }
    public function getConfigFieldsValues()
	{
		$id_shop_group = Shop::getContextShopGroupID();
		$id_shop = Shop::getContextShopID();
		return array(
			'GC_EMAIL_TRACKING_ID' => Tools::getValue('GC_EMAIL_TRACKING_ID', Configuration::get('GC_EMAIL_TRACKING_ID', null, $id_shop_group, $id_shop)),
        );
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
