<?php
/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2017 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */

if (!defined('_PS_VERSION_'))
{
    exit;
}

use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Adapter\Translator;
use PrestaShop\PrestaShop\Adapter\LegacyContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class homeonsaletab extends Module
{
    public function __construct()
    {
        $this->name = 'homeonsaletab';
        $this->tab = 'front_office_features';
        $this->version = '9.9.9';
        $this->author = 'MyPresta.eu';
        $this->mypresta_link = "https://mypresta.eu/modules/front-office-features/homepage-tab-on-sale-promotions.html";
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7',
            'max' => _PS_VERSION_,
        ];

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Homepage on sale (promotions) tab');
        $this->description = $this->l('Displays tab in the central column of your homepage with products that have dropped price');

        $this->mkey = 'freelicense';
        if (@file_exists('../modules/' . $this->name . '/key.php'))
        {
            @require_once('../modules/' . $this->name . '/key.php');
        }
        else
        {
            if (@file_exists(dirname(__FILE__) . $this->name . '/key.php'))
            {
                @require_once(dirname(__FILE__) . $this->name . '/key.php');
            }
            else
            {
                if (@file_exists('modules/' . $this->name . '/key.php'))
                {
                    @require_once('modules/' . $this->name . '/key.php');
                }
            }
        }
        $this->checkforupdates();
    }

    public static function psversion()
    {
        $version = _PS_VERSION_;
        $exp = $explode = explode(".", $version);
        return $exp[1];
    }

    public function checkforupdates($display_msg = 0, $form = 0)
    {
        // ---------- //
        // ---------- //
        // VERSION 11 //
        // ---------- //
        // ---------- //
        if ($form == 1)
        {
            return '
            <div class="panel" id="fieldset_myprestaupdates" style="margin-top:20px;">
            ' . ($this->psversion() == 6 || $this->psversion() == 7 ? '<div class="panel-heading"><i class="icon-wrench"></i> ' . $this->l('MyPresta updates') . '</div>' : '') . '
			<div class="form-wrapper" style="padding:0px!important;">
            <div id="module_block_settings">
                    <fieldset id="fieldset_modu\le_block_settings">
                         ' . ($this->psversion() == 5 ? '<legend style="">' . $this->l('MyPresta updates') . '</legend>' : '') . '
                        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                            <label>' . $this->l('Check updates') . '</label>
                            <div class="margin-form">' . (Tools::isSubmit('submit_settings_updates_now') ? (homeonsaletabUpdate::inconsistency(0) ? '' : '') . $this->checkforupdates(1) : '') . '
                                <button style="margin: 0px; top: -3px; position: relative;" type="submit" name="submit_settings_updates_now" class="button btn btn-default" />
                                <i class="process-icon-update"></i>
                                ' . $this->l('Check now') . '
                                </button>
                            </div>
                            <label>' . $this->l('Updates notifications') . '</label>
                            <div class="margin-form">
                                <select name="mypresta_updates">
                                    <option value="-">' . $this->l('-- select --') . '</option>
                                    <option value="1" ' . ((int)(Configuration::get('mypresta_updates') == 1) ? 'selected="selected"' : '') . '>' . $this->l('Enable') . '</option>
                                    <option value="0" ' . ((int)(Configuration::get('mypresta_updates') == 0) ? 'selected="selected"' : '') . '>' . $this->l('Disable') . '</option>
                                </select>
                                <p class="clear">' . $this->l('Turn this option on if you want to check MyPresta.eu for module updates automatically. This option will display notification about new versions of this addon.') . '</p>
                            </div>
                            <label>' . $this->l('Module page') . '</label>
                            <div class="margin-form">
                                <a style="font-size:14px;" href="' . $this->mypresta_link . '" target="_blank">' . $this->displayName . '</a>
                                <p class="clear">' . $this->l('This is direct link to official addon page, where you can read about changes in the module (changelog)') . '</p>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" name="submit_settings_updates"class="button btn btn-default pull-right" />
                                <i class="process-icon-save"></i>
                                ' . $this->l('Save') . '
                                </button>
                            </div>
                        </form>
                    </fieldset>
                    <style>
                    #fieldset_myprestaupdates {
                        display:block;clear:both;
                        float:inherit!important;
                    }
                    </style>
                </div>
            </div>
            </div>';
        }
        else
        {
            if (defined('_PS_ADMIN_DIR_'))
            {
                if (Tools::isSubmit('submit_settings_updates'))
                {
                    Configuration::updateValue('mypresta_updates', Tools::getValue('mypresta_updates'));
                }
                if (Configuration::get('mypresta_updates') != 0 || (bool)Configuration::get('mypresta_updates') == false)
                {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200))
                    {
                        $actual_version = homeonsaletabUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                    if (homeonsaletabUpdate::version($this->version) < homeonsaletabUpdate::version(Configuration::get('updatev_' . $this->name)))
                    {
                        $this->warning = $this->l('New version available, check http://MyPresta.eu for more informations');
                    }
                }
                if ($display_msg == 1)
                {
                    if (homeonsaletabUpdate::version($this->version) < homeonsaletabUpdate::version(homeonsaletabUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version)))
                    {
                        return "<span style='color:red; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('New version available!') . "</span>";
                    }
                    else
                    {
                        return "<span style='color:green; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('Module is up to date!') . "</span>";
                    }
                }
            }
        }
    }

    public function install()
    {
        $this->_clearCache('*');
        Configuration::updateValue('HOME_OS_NBR', 8);

        return parent::install() && $this->registerHook('addproduct') && $this->registerHook('updateproduct') && $this->registerHook('deleteproduct') && $this->registerHook('categoryUpdate') && $this->registerHook('displayHomeTab') && $this->registerHook('displayHomeTabContent');
    }

    public function uninstall()
    {
        $this->_clearCache('*');

        return parent::uninstall();
    }

    public function getContent()
    {
        $output = '';
        $errors = array();
        if (Tools::isSubmit('submithomeonsaletab'))
        {
            $nbr = Tools::getValue('HOME_OS_NBR');
            if (!Validate::isInt($nbr) || $nbr <= 0)
            {
                $errors[] = $this->l('The number of products is invalid. Please enter a positive number.');
            }

            if (isset($errors) && count($errors))
            {
                $output = $this->displayError(implode('<br />', $errors));
            }
            else
            {
                Configuration::updateValue('HOME_OS_NBR', (int)$nbr);
                Tools::clearCache(Context::getContext()->smarty, $this->getTemplatePath('homeonsaletab.tpl'));
                $output = $this->displayConfirmation($this->l('Your settings have been updated.'));
            }
        }

        return $output . $this->advert() . $this->renderForm() . $this->checkforupdates(0, true);
    }

    public function advert()
    {
        return '<div style="diplay:block; clear:both; margin-bottom:20px;" class="panel">
        <h3>' . $this->l('Check other modules:') . ' <a href="https://mypresta.eu" target="_blank">MyPresta\'s PrestShop modules</a>
        <span class="pull-right"><a href="https://mypresta.eu/modules/front-office-features/homepage-products-pro.html" target="_blank">Homepage featured pro</a></span>
        </h3>
		  <iframe src="//apps.facepages.eu/somestuff/onlyexample.html" width="100%" height="150" border="0" style="border:none;"></iframe>
		</div>
        <div class="panel">
            <h3>' . $this->l('Important!') . '</h3>
            <div class="alert alert-warning">
                ' . $this->l('This is free PrestShop module and it uses feature called homepage tabs. More details about homepage tabs you can find here: ') . '<a href="https://mypresta.eu/prestashop-17/enable-homepage-tabs.html" target="_blank">' . $this->l('Homepage tabs in PrestShop 1.7') . '</a>
            </div>
        </div>
        ';
    }

    public function getProducts()
    {
        $nProducts = Configuration::get('HOME_OS_NBR');
        $products_for_template =  Product::getPricesDrop((int)$this->context->language->id, 0, (int)$nProducts);
        return $products_for_template;
    }

    public function hookAddProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookUpdateProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookDeleteProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookCategoryUpdate($params)
    {
        $this->_clearCache('*');
    }

    public function _clearCache($template, $cache_id = null, $compile_id = null)
    {
        parent::_clearCache('homeonsaletab.tpl', 'homeonsaletab');
    }

    public function hookdisplayHomeTab($params)
    {
        return $this->display(__file__, 'tab.tpl');
    }

    public function prepareBlocksProducts($block)
    {
        $blocks_for_template = [];
        $products_for_template = [];

        $assembler = new ProductAssembler($this->context);
        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(new ImageRetriever($this->context->link), $this->context->link, new PriceFormatter(), new ProductColorsRetriever(), $this->context->getTranslator());
        $products_for_template = [];
        if ($block){
            foreach ($block as $rawProduct)
            {
                $products_for_template[] = $presenter->present($presentationSettings, $assembler->assembleProduct($rawProduct), $this->context->language);
            }
        }

        return $products_for_template;
    }


    public function hookdisplayHomeTabContent($params)
    {
        if (!$this->isCached('homeonsaletab.tpl', $this->getCacheId('homeonsaletab')))
        {
            $this->smarty->assign($this->getAssignmentVariables());
        }
        return $this->display(__FILE__, 'homeonsaletab.tpl');
    }

    public function getAssignmentVariables()
    {
        return [
            'products' => $this->prepareBlocksProducts($this->getProducts()),
            'homeSize' => Image::getSize(ImageType::getFormattedName('home')),
        ];
    }


    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'description' => $this->l('Module will display products only if you will have some products with dropped prices'),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Number of products to be displayed'),
                        'name' => 'HOME_OS_NBR',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->l('Set the number of products that you would like to display on homepage (default: 8).'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->id = (int)Tools::getValue('id_carrier');
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submithomeonsaletab';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'HOME_OS_NBR' => Tools::getValue('HOME_OS_NBR', (int)Configuration::get('HOME_OS_NBR')),
        );
    }
}

class homeonsaletabUpdate extends homeonsaletab
{
    public static function version($version)
    {
        $version = (int)str_replace(".", "", $version);
        if (strlen($version) == 3)
        {
            $version = (int)$version . "0";
        }
        if (strlen($version) == 2)
        {
            $version = (int)$version . "00";
        }
        if (strlen($version) == 1)
        {
            $version = (int)$version . "000";
        }
        if (strlen($version) == 0)
        {
            $version = (int)$version . "0000";
        }
        return (int)$version;
    }

    public static function encrypt($string)
    {
        return base64_encode($string);
    }

    public static function inconsistency($return_report = 1)
    {
        return true;
    }

    public static function verify($module, $key, $version)
    {
        if (ini_get("allow_url_fopen"))
        {
            if (function_exists("file_get_contents"))
            {
                $actual_version = @file_get_contents('http://dev.mypresta.eu/update/get.php?module=' . $module . "&version=" . self::encrypt($version) . "&lic=$key&u=" . self::encrypt(_PS_BASE_URL_ . __PS_BASE_URI__));
            }
        }
        Configuration::updateValue("update_" . $module, date("U"));
        Configuration::updateValue("updatev_" . $module, $actual_version);
        return $actual_version;
    }
}
