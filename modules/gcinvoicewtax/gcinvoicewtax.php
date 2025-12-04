<?php
/**
 * GcInvoiceWTax
 *
 * @author    Grégory Chartier <hello@gregorychartier.fr>
 * @copyright 2018 Grégory Chartier (https://www.gregorychartier.fr)
 * @license   Commercial license see license.txt
 * @category  Prestashop
 * @category  Module
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class GcInvoiceWTax extends TaxManagerModule
{
    public function __construct()
    {
        $this->name                          = 'gcinvoicewtax';
        $this->version                       = '1.3.7';
        $this->tab                           = 'billing_invoicing';
        $this->bootstrap                     = true;
        $this->display                       = 'view';
        $this->author                        = 'Grégory Chartier';
        $this->ps_versions_compliancy['min'] = '1.5.0.0';
        $this->author_address                = '0x5cD3FdcEF023E7ebeAb44aA7140c992f668973eB';
        $this->module_key                    = '1f4da0f84386330ee38ac2eb90557776';
        $this->need_instance                 = 0;
        $this->tax_manager_class             = 'GcInvoiceWTaxManager';

        parent::__construct();
        $this->displayName      = $this->l('Invoice Without Tax (Tax excl / without VAT)');
        $this->description      = $this->l('Bill some customers without tax');
        $this->confirmUninstall = $this->l('Uninstall this module ?');
    }

    public function install()
    {
        if (!parent::install()
            || !Configuration::updateValue('GCIWT_GROUP', null)
            || !Configuration::updateValue('GCIWT_COUNTRY', 0)
            || !Configuration::updateValue('GCIWT_STOPRATING', 0)) {
            return false;
        }
        $this->registerHook('actionTaxManager');

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()
            || !Configuration::deleteByName('GCIWT_GROUP')
            || !Configuration::deleteByName('GCIWT_COUNTRY')
            || !Configuration::deleteByName('GCIWT_STOPRATING')) {
            return false;
        }

        return true;
    }

    public function getContent()
    {
        $output = $this->postProcess();
        $output .= $this->getRating();
        $output .= $this->renderForm();

        return $output;
    }
    public function hookActionTaxManager($args)
    {
        return $this->hookTaxManager($args);
    }

    public function getRating()
    {
        $output      = '';
        $stop_rating = (int)Configuration::get('GCIWT_STOPRATING');
        if ($stop_rating != 1) {
            $output .= "
			<script type='text/javascript'>
			$(document).ready(function() {
				$('div#stop_rating p.stop a').click(function() {
				$('div#stop_rating').hide(500);
				$.ajax({type : 'GET', url : window.location+'&stop_rating=1' });
				return false;
				});
			});</script>";
            $output .= '
			<div id="stop_rating" class="row text-center">
				<div style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em; text-align: center;">
					<p class="invite">'
                       . $this->l('You are satisfied with our module and want to encourage us to add new features ?')
                       . '<br/><a href="http://addons.prestashop.com/ratings.php" target="_blank"><strong>'
                       . $this->l('Please rate it on Prestashop Addons, and give us 5 stars !')
                       . '</strong></a>
					</p>
					<p class="stop" style="display: block;"><a style="cursor: pointer">'
                       . '['
                       . $this->l('No thanks, I don\'t want to help you. Close this dialog.')
                       . ']
					 </a></p>
				</div>
			</div>';
        }

        return $output;
    }

    public function renderForm()
    {
        if (_PS_VERSION_ >= 1.6) {
            $fields_form = array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Configuration'),
                        'icon'  => 'icon-cogs'
                    ),
                    'input'  => array(
                        array(
                            'type'   => 'checkbox',
                            'label'  => $this->l('Customer Groups :'),
                            'desc'   => $this->l('Groups which must be billed tax excluded'),
                            'name'   => 'gciwt_group',
                            'values' => array(
                                'query' => $this->getCustomerGroups(),
                                'id'    => 'id_group',
                                'name'  => 'name'
                            )
                        ),
                        array(
                            'type'          => 'select',
                            'label'         => $this->l('Your country :'),
                            'desc'          => $this->l('Orders delivered in this country, even if from a customer who is in the specific group, will be charge by tax'),
                            'name'          => 'gciwt_country',
                            'default_value' => '',
                            'options'       => array(
                                'query'   => Country::getCountries($this->context->language->id),
                                'name'    => 'name',
                                'id'      => 'id_country',
                                'default' => array(
                                    'label' => $this->l('-- Select a country --'),
                                    'value' => '0'
                                )
                            )
                        ),
                    ),
                    'submit' => array(
                        'name'  => 'submitGcInvoiceWithoutTax',
                        'title' => $this->l('Save'),
                        'class' => 'button'
                    )
                )
            );
        } else {
            $fields_form = array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Configuration'),
                        'icon'  => 'icon-cogs'
                    ),
                    'input'  => array(
                        array(
                            'type'   => 'checkbox',
                            'label'  => $this->l('Customer Groups :'),
                            'desc'   => $this->l('Groups which must be billed tax excluded'),
                            'name'   => 'gciwt_group',
                            'values' => array(
                                'query' => $this->getCustomerGroups(),
                                'id'    => 'id_group',
                                'name'  => 'name'
                            )
                        ),
                        array(
                            'type'          => 'select',
                            'label'         => $this->l('Your country :'),
                            'desc'          => $this->l('Orders delivered in this country, even if from a customer who is in the specific group, will be charge by tax'),
                            'name'          => 'gciwt_country',
                            'default_value' => '',
                            'options'       => array(
                                'query'   => Country::getCountries($this->context->language->id),
                                'name'    => 'name',
                                'id'      => 'id_country',
                                'default' => array(
                                    'label' => $this->l('-- Select a country --'),
                                    'value' => ''
                                )
                            )
                        ),
                    ),
                    'submit' => array(
                        'name'  => 'submitGcInvoiceWithoutTax',
                        'title' => $this->l('Save'),
                        'class' => 'button'
                    )
                )
            );
        }

        $helper                           = new HelperForm();
        $helper->title                    = $this->displayName;
        $helper->show_toolbar             = true;
        $helper->table                    = $this->table;
        $lang                             = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language    = $lang->id;
        $helper->allow_employee_form_lang =
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form                = array();
        $helper->toolbar_btn              = array(
            'save' => array('href' => '#', 'desc' => $this->l('Save'))
        );

        $helper->identifier    = $this->identifier;
        $helper->submit_action = 'submitGcInvoiceWithoutTax';
        $helper->currentIndex  = $this->context->link->getAdminLink('AdminModules', false)
                                 . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token         = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars      = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages'    => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $fields_value = array();

        if (Configuration::get('GCIWT_GROUP')) {
            $groups = explode('-', Configuration::get('GCIWT_GROUP'));
            foreach ($groups as $group) {
                $fields_value['gciwt_group_' . $group] = 1;
            }
        }

        $fields_value['gciwt_country'] = Tools::getValue('gciwt_country', Configuration::get('GCIWT_COUNTRY'));

        return $fields_value;
    }

    public function postProcess()
    {
        if (Tools::getValue('stop_rating') == 1) {
            Configuration::updateValue('GCIWT_STOPRATING', 1);
            die;
        }

        $output = '';

        if (Tools::isSubmit('submitGcInvoiceWithoutTax')) {
            $active_groups = array();
            $groups        = Group::getGroups($this->context->language->id);

            foreach ($groups as $group) {
                if (Tools::getValue('gciwt_group_' . $group['id_group'])) {
                    $active_groups[] = $group['id_group'];
                }
            }

            if (count($active_groups)) {
                Configuration::updateValue('GCIWT_GROUP', implode('-', $active_groups));
            }

            Configuration::updateValue('GCIWT_COUNTRY', Tools::getValue('gciwt_country'));

            $output .= $this->displayConfirmation($this->l('Configuration saved.'));
        }

        return $output;
    }

    public function getCustomerGroups()
    {
        $sql = 'SELECT `name`, `id_group`
				FROM `' . _DB_PREFIX_ . 'group_lang`
				WHERE id_lang = "' . (int)Context::getContext()->language->id . '"
				ORDER BY `name` ASC';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
    }
}
