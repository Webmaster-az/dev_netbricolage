<?php
/**
* 2019-2020 GLS
*
* NOTICE OF LICENSE
*
*  @author    GLS <a.troitino@elucubración.com>
*  @copyright 2019-2020 GLS
*  @license   GNU General Public License version 2
*
* You can not resell or redistribute this software.
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

	require_once _PS_MODULE_DIR_.'/glsshipping/classes/GlsshippingUpdate.php';

class Glsshipping extends CarrierModule
{
	protected $config_form = false;
	public $logger = false;
	public $id_carrier = false;
	public $tabs = array(
			array(
				'name' => 'GLS', // One name for all langs
				'class_name' => 'AdminGlsshipping',
				'visible' => true,
				'parent_class_name' => 'AdminParentOrders',
		));
	public $ebp_countries = array(//'AD'=> 376,
								'AL'=> 18093,
								'AT'=> 43,
								'BA'=> 387,
								'BE'=> 32,
								'BG'=> 359,
								'CH'=> 411,
								'CY'=> 301,
								'CZ'=> 42,
								'DE'=> 49,
								'DK'=> 45,
								'EE'=> 360,
								//'ES'=> 34,
								'FI'=> 358,
								'FO'=> 18125,
								'FR'=> 33,
								'GB'=> 44,
								//'GI'=> 441,
								'GR'=> 30,
								'HR'=> 385,
								'HU'=> 36,
								'IE'=> 353,
								'IS'=> 354,
								'IT'=> 39,
								'LT'=> 77,
								'LU'=> 352,
								'LV'=> 78,
								'ME'=> 18160,
								'MK'=> 389,
								'MT'=> 443,
								'NL'=> 31,
								'NO'=> 47,
								'PL'=> 48,
								'PT'=> 351,
								'RO'=>40,
								'RS'=>18179,
								'SE'=> 46,
								'SI'=> 386,
								'SK'=> 421,
								'SM'=> 391,
								'TR'=>90,
								'VA'=> 396
								);

    public function __construct()
    {
        $this->name = 'glsshipping';
        $this->tab = 'shipping_logistics';
        $this->version = '3.2.10';
        $this->author = 'GLS';
        $this->need_instance = 0;
        $this->bootstrap = true;
      
        $this->logger = new FileLogger();
        if (version_compare(_PS_VERSION_, '1.7', '>')) {
			$this->logger->setFilename(_PS_ROOT_DIR_ . '/var/logs/' . date('Ymd') . '_gls.log');
	    } else {
			$this->logger->setFilename(_PS_ROOT_DIR_ . '/log/' . date('Ymd') . '_gls.log');
	    }

        parent::__construct();

        $this->displayName = $this->l('GLS Shipping');
        $this->description = $this->l('Módulo de gestión de envíos con GLS');

        $this->confirmUninstall = $this->l('Se perderá la información de envíos gestionados con este módulo. ¿Está seguro?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        if (extension_loaded('curl') == false)
        {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }

        Configuration::updateValue('GLS_GUID', '15F9A8B5-82AC-4094-99F7-9FD58FD43E9E');
        Configuration::updateValue('GLS_URL', 'https://www.asmred.com/websrvs/ecm.asmx?wsdl');
        Configuration::updateValue('GLSSHIPPING_LIVE_MODE', false);
        Configuration::updateValue('GLS_SHOW_CSV', false);
        Configuration::updateValue('GLS_STATUS_SEND', 4);
        Configuration::updateValue('GLS_STATUS_COMPLETED', 5);
        Configuration::updateValue('GLS_STATUS_FAILED', 4);
        Configuration::updateValue('GLS_UPDATE_ORDER_STATUS_SEND', true);
        Configuration::updateValue('GLS_UPDATE_ORDER_STATUS_COMPLETED', true);
        Configuration::updateValue('GLS_UPDATE_ORDER_STATUS_FAILED', true);
        Configuration::updateValue('GLS_VALID_CARRIERS', array());

        include(dirname(__FILE__).'/sql/install.php');
        if (Module::isInstalled('asmcarrier')) {
        	include(dirname(__FILE__).'/sql/import.php');
        }
        
		if (version_compare(_PS_VERSION_, '1.5', '>') && version_compare(_PS_VERSION_, '1.7','<')){
			$tab = new Tab();
			$tab->class_name = 'AdminGlsshipping';
			//Para que funcione con la nueva versión.
			$tab->id_parent = 10;
			$tab->module = $this->name;
			$tab->name[(int)(Configuration::get('PS_LANG_DEFAULT'))] = 'GLS';
			if(!$tab->add())
			{
			  return false;
			}
		}
		if (version_compare(_PS_VERSION_, '1.7', '>')) {
			return parent::install() &&
				$this->registerHook('header') &&
				$this->registerHook('backOfficeHeader') &&
				$this->registerHook('updateCarrier') &&
				$this->registerHook('actionCarrierUpdate') &&
				$this->registerHook('displayCarrierExtraContent') &&
				$this->registerHook('displayHeader') &&
				$this->registerHook('displayOrderDetail') &&
				$this->registerHook('displayAdminOrderTabShip') &&
				$this->registerHook('adminOrder') &&
				$this->registerHook('displayAdminOrderMain') &&
				$this->registerHook('actionValidateStepComplete');
		} else {
			return parent::install() &&
				$this->registerHook('header') &&
				$this->registerHook('backOfficeHeader') &&
				$this->registerHook('updateCarrier') &&
				$this->registerHook('actionCarrierUpdate') &&
				$this->registerHook('extraCarrier') &&
				$this->registerHook('displayCarrierList') &&
				$this->registerHook('displayHeader') &&
				$this->registerHook('displayOrderDetail') &&
				$this->registerHook('displayAdminOrderTabShip') &&
				$this->registerHook('adminOrder') &&
				$this->registerHook('actionValidateStepComplete');
		}
		
    }

    public function uninstall()
    {
        Configuration::deleteByName('GLSSHIPPING_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
		$this->checkCurrentVersion();
		$upgrade_version = Tools::getValue('upgrade_version');
		if ($upgrade_version){
			$this->upgradeVersion($upgrade_version);
		}
		
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitGlsshippingModule')) == true) {
            $this->postProcess();
        } 

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitGlsshippingModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
		$fields_value = $this->getConfigFormValues();
        $helper->tpl_vars = array(
            'fields_value' => $fields_value,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

		return $helper->generateForm(array(
				$this->getConfigForm1(),
				$this->getConfigForm2a(),
				$this->getConfigForm2(),
				$this->getConfigForm3(),
				$this->getConfigForm3b(),
				$this->getConfigForm4(),
				$this->getConfigForm5(),
				$this->getConfigForm7(),
				$this->getConfigForm6()
			)
		);
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm1()
    {
    	return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configuración básica'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
	                 array(
                        'type' => 'text',
                        'label' => $this->l('GUID:'),
                        'name' => 'GLS_GUID',
                        'desc' => $this->l('El GUID por defecto (15F9A8B5-82AC-4094-99F7-9FD58FD43E9E) es para hacer pruebas. Cuando tenga el módulo y sus opciones corretamente configurado y testado solicite su GUID a su Agencia GLS.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Webservices URL:'),
                        'name' => 'GLS_URL',
                        'desc' => $this->l('URL del servicio web de GLS. Por defecto: https://www.asmred.com/websrvs/ecm.asmx?wsdl'),
                    ),
                   ),
                'submit' => array(
                    'title' => $this->l('Guardar'),
                ),
            )
        );

	}
   
	protected function getConfigForm2a()
    {
		global $cookie;
        $carriers = Carrier::getCarriers($cookie->id_lang, false, false, false, NULL, ALL_CARRIERS);
		foreach($carriers as $key => $carrier){
			if (!empty($carrier['external_module_name']) && $carrier['external_module_name'] != $this->name){
				unset($carriers[$key]);
			}
		}
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Activar transportista'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
	                 array(
                        'type' => 'select',
                        'label' => $this->l('Transportistas:'),
                        'name' => 'GLS_VALID_CARRIERS[]',
						'multiple' => true,
                        'options' => array(
							'query' => $carriers,
							'id' => 'id_reference',
							'name' => 'name'
						)
                    ),
                 ),
                'submit' => array(
                    'title' => $this->l('Guardar'),
                ),
            ),
        );
    }
    
	protected function getConfigForm2()
    {
		global $cookie;
		$validCarriers = explode(';',Configuration::get('GLS_VALID_CARRIERS'));
		$carriers = array();
		if (!empty($validCarriers)) {
			$carriers = Carrier::getCarriers($cookie->id_lang, false, false, false, NULL, CARRIERS_MODULE);
			foreach($carriers as $key => $carrier){
				if (!in_array($carrier['id_reference'], $validCarriers)){
				   unset($carriers[$key]);
				}
			}
		}
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Transportistas'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Crear transportista asociado a GLS:'),
                        'name' => 'GLS_ADD_CARRIER',
                        'desc' => $this->l('Indique el nombre del nuevo transportista.'),
                    ),
	                 array(
                        'type' => 'select',
                        'label' => $this->l('GLS - 10:00 Service:'),
                        'name' => 'GLS_SERVICIO_SELECCIONADO_GLS10[]',
						'multiple' => true,
                        'options' => array(
							'query' => $carriers,
							'id' => 'id_reference',
							'name' => 'name'
						)
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('GLS - 14:00 Service:'),
                        'name' => 'GLS_SERVICIO_SELECCIONADO_GLS14[]',
                        'multiple' => true,
                        'options' => array(
							'query' => $carriers,
							'id' => 'id_reference',
							'name' => 'name'
						)
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('GLS - BusinessParcel:'),
                        'name' => 'GLS_SERVICIO_SELECCIONADO_GLS24[]',
                        'multiple' => true,
                        'options' => array(
							'query' => $carriers,
							'id' => 'id_reference',
							'name' => 'name'
						)
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('GLS - EconomyParcel:'),
                        'name' => 'GLS_SERVICIO_SELECCIONADO_GLSECO[]',
                        'multiple' => true,
                        'options' => array(
							'query' => $carriers,
							'id' => 'id_reference',
							'name' => 'name'
						)
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('GLS - Servicio Eurobusiness Parcel:'),
                        'name' => 'GLS_SERVICIO_SELECCIONADO_GLSEBP[]',
                        'multiple' => true,
                        'options' => array(
							'query' => $carriers,
							'id' => 'id_reference',
							'name' => 'name'
						)
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('GLS - Servicio ParcelShop:'),
                        'name' => 'GLS_SERVICIO_SELECCIONADO_GLSPARCEL[]',
                        'multiple' => true,
                        'options' => array(
							'query' => $carriers,
							'id' => 'id_reference',
							'name' => 'name'
						)
                    )
                 ),
                'submit' => array(
                    'title' => $this->l('Guardar'),
                ),
            ),
        );
    }
    
    protected function getConfigForm3()
    {
    	$tmp_payment_methods = PaymentModule::getInstalledPaymentModules();
    	$payment_methods = array();
    	$payment_methods[] = array('name'=>'-', 'displayName'=>'-');
    	foreach($tmp_payment_methods as $tpm){
			$tmpPayment = Module::getInstanceByName($tpm['name']);
			$name = $tpm['name'];
			$displayName = $tmpPayment->displayName;
			if (!empty($name) && !empty($displayName)){
				$payment_methods[] = array('name'=>$tpm['name'], 'displayName'=>(!empty($tmpPayment->displayName))?$tmpPayment->displayName:$tpm['name']);
			}    	  
    	}
		array_unshift($payment_methods, array('name'=>'-'));
		$opts_incoterm = array(
			array(
				'id_option' => 0, 
				'name' => '-'
			),
			array(
				'id_option' => 10, 
				'name' => $this->l('Incoterm 10 DDP. COSTES REMITENTE: transporte,despacho,aranceles, impuestos. COSTES DESTINATARIO: no tiene costes')
			),
			array(
				'id_option' => 20, 
				'name' => $this->l('Incoterm 20 DAP. COSTES REMITENTE: transporte. COSTES DESTINATARIO: despacho, aranceles e impuestos')
			),
			array(
				'id_option' => 30, 
				'name' => $this->l('Incoterm 30 DDP, I.V.A. no pagado . COSTES REMITENTE: transporte,despacho, aranceles. COSTES DESTINATARIO: impuestos')
			),
			array(
				'id_option' => 40, 
				'name' => $this->l('Incoterm 40 DAP, despachado. COSTES REMITENTE: transporte, despacho. COSTES DESTINATARIO: aranceles, impuestos')
			),
			array(
				'id_option' => 50, 
				'name' => $this->l('Incoterm 50c DDP, bajo valor . COSTES REMITENTE: transporte, despacho. COSTES DESTINATARIO: no tiene costes')
			),
			array(
				'id_option' => 18, 
				'name' => $this->l('Incoterm 18 (DDP, VAT pre-registration). Delivered free, duty paid, VAT paid - the shipper does pay all costs, for importers no cost ocurr.')
			)
        );      
        $opts_retorno = array(
			array(
				'id_option' => 0, 
				'name' => $this->l('Sin retorno')
			),
			array(
				'id_option' => 1, 
				'name' => $this->l('Retorno obligatorio')
			),
			array(
				'id_option' => 2, 
				'name' => $this->l('Retorno opcional')
			)
		);
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configuración avanzada'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
	                 array(
                      'type' => 'select',
                      'label' => $this->l('Reembolso:'),
                      'desc' => $this->l('Indique el método de pago contrareembolso.'),
                      'name' => 'GLS_COD',
                      'options' => array(
                        'query' => $payment_methods,
                        'id' => 'name',
                        'name' => 'displayName'
                      )
                    ),
                     array(
                        'type' => 'text',
                        'label' => $this->l('Peso:'),
                        'name' => 'GLS_DEF_PESO',
                        'suffix' => 'kg', 
                        'desc' => $this->l('Si desea que el cálculo del peso del envío se calcule de forma automática en base al sumatorio del peso de los productos que lo conforman según su definición en la base de datos de productos, deje este campo vacío. Si quiere poder modificar el peso de un pedido previo a su envío, defina un peso por defecto (en Kg).'),
                    ),
                    array(
                      'type' => 'radio',
                      'label' => $this->l('Bultos por envío:'),
                      'desc' => $this->l('Configuración de bultos por envío fijo o variable según numero de artículos.'),
                      'name' => 'GLS_BULTOS',
                      'values' => array(
                        array(
                        	'id' => 'gls_bultos_0',
                        	'value' => 0,
                        	'label' => $this->l('Fijo')
                        ),
                        array(
                        	'id' => 'gls_bultos_1',
                        	'value' => 1,
                        	'label' => $this->l('Variable')
                        ),
                      )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Número de bultos (fijo):'),
                        'name' => 'GLS_NUM_FIJO_BULTOS',
                        'desc' => $this->l('Indique el número de bultos.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Número de artículos por bultos (variable):'),
                        'name' => 'GLS_NUM_ARTICULOS',
                        'desc' => $this->l('Indique el número de artículos por bulto.'),
                    ),
                    array(
                      'type' => 'select',
                      'label' => $this->l('Retorno:'),
                      'desc' => $this->l('Indique Retorno.'),
                      'name' => 'GLS_RETORNO',
                      'options' => array(
                        'query' => $opts_retorno,
                        'id' => 'id_option',
                        'name' => 'name'
                      )
                    ),
                    array(
                      'type' => 'switch',
                      'label' => $this->l('RCS:'),
                      'desc' => $this->l('Indique RCS (Retorno Copia Sellada).'),
                      'name' => 'GLS_RCS',
                      'is_bool' => true,
                      'values' => array(
                        array(
                        	'id' => 'gls_rcs_1',
                        	'value' => 1,
                        	'label' => $this->l('Si')
                        ),
                        array(
                        	'id' => 'gls_rcs_0',
                        	'value' => 0,
                        	'label' => $this->l('No')
                        ),

                      )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Dpto. origen:'),
                        'name' => 'GLS_DORIG',
                        'desc' => $this->l('Indique el departamento de origen.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Valor asegurado:'),
                        'name' => 'GLS_VSEC',
                        'desc' => $this->l('Indique el valor asegurado.'),
                    ),
                    array(
                      'type' => 'select',
                      'label' => $this->l('Incoterm:'),
                      'desc' => $this->l('Incoterm que será usado en caso de envíos destino fuera de la UE con el servicio Eurobusiness Parcel.<b>No tiene efecto para envíos nacionales e internacionales destino UE.</b>'),
                      'name' => 'GLS_INCOTERM',
                      'options' => array(
                        'query' => $opts_incoterm,
                        'id' => 'id_option',
                        'name' => 'name'
                      )
                    ),
                   
                ),
                'submit' => array(
                    'title' => $this->l('Guardar'),
                ),
            ),
        );
    }
	
	 protected function getConfigForm3b()
    {
		
		$opts_countries = array(
			array(
				'id_option' => 0, 
				'name' => $this->l('-')
			),
			array(
				'id_option' => 34, 
				'name' => $this->l('España')
			),
			array(
				'id_option' => 351, 
				'name' => $this->l('Portugal')
			)
		);
		
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Remitente'),
                    'desc' => $this->l('Si usted no completa todos los datos de esta sección los datos del remitente se recogerán de los datos de contacto de su tienda.'),
                    'icon' => 'icon-cogs',
                ),
				'description' => $this->l('Si usted no completa todos los datos de esta sección los datos del remitente se recogerán de los datos de contacto de su tienda'),
                'input' => array(
                   array(
                        'type' => 'text',
                        'label' => $this->l('Nombre:'),
                        'name' => 'GLS_SENDER_NAME',
                        'desc' => $this->l('Indique el nombre de remitente.'),
                    ),
					array(
                        'type' => 'text',
                        'label' => $this->l('Dirección:'),
                        'name' => 'GLS_SENDER_ADDRESS',
                        'desc' => $this->l('Indique la dirección de remitente.'),
                    ),
					array(
                        'type' => 'text',
                        'label' => $this->l('Código postal:'),
                        'name' => 'GLS_SENDER_CP',
                        'desc' => $this->l('Indique el código postal de remitente.'),
                    ),
					array(
                        'type' => 'text',
                        'label' => $this->l('Población:'),
                        'name' => 'GLS_SENDER_CITY',
                        'desc' => $this->l('Indique la población de remitente.'),
                    ),
					 array(
                      'type' => 'select',
                      'label' => $this->l('País:'),
                      'desc' => $this->l('Indique el país de remitente.'),
                      'name' => 'GLS_SENDER_COUNTRY',
                      'options' => array(
                        'query' => $opts_countries,
                        'id' => 'id_option',
                        'name' => 'name'
                      )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Guardar'),
                ),
            ),
        );
    }
	
    protected function getConfigForm4()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Emails'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                   array(
                      'type' => 'switch',
                      'label' => $this->l('Enviar email:'),
                      'desc' => $this->l('Enviar mail al comprador. Sólo funciona para versiones de prestashop 1.5.x en adelante.'),
                      'name' => 'GLS_ENVIAR_MAIL',
                      'is_bool' => true,
                      'values' => array(
                        array(
                        	'id' => 'gls_enviar_mail_1',
                        	'value' => 1,
                        	'label' => $this->l('Si')
                        ),
                        array(
                        	'id' => 'gls_enviar_mail_0',
                        	'value' => 0,
                        	'label' => $this->l('No')
                        ),

                      )
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Mensaje:'),
                        'name' => 'GLS_EMAIL',
                        'rows' => 9,
                        'cols' => 40,
                        'desc' => $this->l('Descripción del mensaje que le llegará al comprador por correo electrónico'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Guardar'),
                ),
            ),
        );
    }
	
	protected function getConfigForm5()
    {
		if (!$context) {
            $context = Context::getContext();
        }

        $orderStates = OrderState::getOrderStates((int) $context->language->id);
		
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Estados') .'<div class="estados_activar">'.$this->l('Activar').'</div>',
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
					 array(
                      'type' => 'select',
                      'label' => $this->l('Enviado:'),
                      'desc' => $this->l('Indique el estado de pedido enviado.'),
                      'name' => 'GLS_STATUS_SEND',
					  'form_group_class' => 'gls-col',
                      'options' => array(
                        'query' => $orderStates,
                        'id' => 'id_order_state',
                        'name' => 'name'
                      )
                    ),
				    array(
                      'type' => 'switch',
					  'desc' => $this->l(' '),
                      'name' => 'GLS_UPDATE_ORDER_STATUS_SEND',
                      'is_bool' => true,
					  'form_group_class' => 'gls-col',
                      'values' => array(
                        array(
                        	'id' => 'gls_update_order_status_send_1',
                        	'value' => true,
                        	'label' => $this->l('Si')
                        ),
                        array(
                        	'id' => 'gls_update_order_status_send_0',
                        	'value' => false,
                        	'label' => $this->l('No')
                        ),

                      )
                    ),
					array(
                      'type' => 'select',
                      'label' => $this->l('Entregado:'),
                      'desc' => $this->l('Indique el estado de pedido entregado.'),
                      'name' => 'GLS_STATUS_COMPLETED',
					  'form_group_class' => 'gls-col',
                      'options' => array(
                        'query' => $orderStates,
                        'id' => 'id_order_state',
                        'name' => 'name'
                      )
                    ),
					array(
                      'type' => 'switch',
					  'desc' => $this->l(' '),
                      'name' => 'GLS_UPDATE_ORDER_STATUS_COMPLETED',
                      'is_bool' => true,
					  'form_group_class' => 'gls-col',
                      'values' => array(
                        array(
                        	'id' => 'gls_update_order_status_completed_1',
                        	'value' => true,
                        	'label' => $this->l('Si')
                        ),
                        array(
                        	'id' => 'gls_update_order_status_completed_0',
                        	'value' => false,
                        	'label' => $this->l('No')
                        ),

                      )
                    ),
                   
					array(
                      'type' => 'select',
                      'label' => $this->l('En tránsito:'),
                      'desc' => $this->l('Indique el estado de pedido en tránsito.'),
                      'name' => 'GLS_STATUS_FAILED',
					  'form_group_class' => 'gls-col',
                      'options' => array(
                        'query' => $orderStates,
                        'id' => 'id_order_state',
                        'name' => 'name'
                      )
                    ),
					array(
                      'type' => 'switch',
					  'desc' => $this->l(' '),
                      'name' => 'GLS_UPDATE_ORDER_STATUS_FAILED',
                      'is_bool' => true,
					  'form_group_class' => 'gls-col',
                      'values' => array(
                        array(
                        	'id' => 'gls_update_order_status_failed_1',
                        	'value' => true,
                        	'label' => $this->l('Si')
                        ),
                        array(
                        	'id' => 'gls_update_order_status_failed_0',
                        	'value' => false,
                        	'label' => $this->l('No')
                        ),

                      )
                    ),

                ),
                'submit' => array(
                    'title' => $this->l('Guardar'),
                ),
            ),
        );
    }

	protected function getConfigForm6()
    {
        $result = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Tarificación por CSV'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                   array(
                      'type' => 'switch',
                      'label' => $this->l('Mostrar opciones CSV:'),
                      'name' => 'GLS_SHOW_CSV',
                      'is_bool' => true,
                      'values' => array(
                        array(
                        	'id' => 'gls_show_csv_1',
                        	'value' => 1,
                        	'label' => $this->l('Si')
                        ),
                        array(
                        	'id' => 'gls_show_csv_0',
                        	'value' => 0,
                        	'label' => $this->l('No')
                        ),

                      )
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Guardar'),
                ),
            ),
        );
		if (Configuration::get('GLS_SHOW_CSV') == true){
			$result['form']['input'][] = array(
				'type' => 'switch',
				'label' => $this->l('Activar:'),
				'name' => 'GLS_CSV_RATES',
				'is_bool' => true,
				'values' => array(
					array(
						'id' => 'gls_csv_rates_1',
						'value' => 1,
						'label' => $this->l('Si')
					),
					array(
						'id' => 'gls_csv_rates_0',
						'value' => 0,
						'label' => $this->l('No')
					),

				)
			);
					
            $result['form']['input'][] = array(
				'type' => 'radio',
				'label' => $this->l('Tarifas por:'),
				'name' => 'GLS_CSV_RATES_TYPE',
				'values' => array(
					array(
						'id' => 'gls_csv_rates_type_peso',
						'value' => 'peso',
						'label' => $this->l('Peso')
					),
					array(
						'id' => 'gls_csv_rates_type_importe',
						'value' => 'importe',
						'label' => $this->l('Importe')
					),
				)
			);
		}
		return $result;
    }
	
	protected function getConfigForm7()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Impresión'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                      'type' => 'switch',
                      'label' => $this->l('Impresión de etiquetas mútiples en Apli4:'),
                      'desc' => $this->l('Al seleccionar varios envíos en el listado de envíos GLS y Generar etiquetas se generarán para Apli4.'),
                      'name' => 'GLS_APLI4',
                      'is_bool' => true,
                      'values' => array(
                        array(
                        	'id' => 'gls_apli4_1',
                        	'value' => 1,
                        	'label' => $this->l('Si')
                        ),
                        array(
                        	'id' => 'gls_apli4_0',
                        	'value' => 0,
                        	'label' => $this->l('No')
                        ),

                      )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Guardar'),
                ),
            ),
        );
    }
	
    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'GLS_GUID' => Configuration::get('GLS_GUID', '15F9A8B5-82AC-4094-99F7-9FD58FD43E9E'),
            'GLS_URL' => Configuration::get('GLS_URL', 'http://www.asmred.com/websrvs/ecm.asmx?wsdl'),
            'GLS_VALID_CARRIERS' => explode(';',Configuration::get('GLS_VALID_CARRIERS', '')),
            'GLS_SERVICIO_SELECCIONADO_GLS10' => explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS10')),
            'GLS_SERVICIO_SELECCIONADO_GLS14' => explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS14')),
            'GLS_SERVICIO_SELECCIONADO_GLS24' => explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS24')),
            'GLS_SERVICIO_SELECCIONADO_GLSECO' => explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSECO')),
            'GLS_SERVICIO_SELECCIONADO_GLSEBP' => explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP')),
            'GLS_SERVICIO_SELECCIONADO_GLSPARCEL' => explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL')),
            'GLS_COD' => Configuration::get('GLS_COD'),
            'GLS_INCOTERM' => Configuration::get('GLS_INCOTERM'),
            'GLS_DEF_PESO' => Configuration::get('GLS_DEF_PESO'),
            'GLS_BULTOS' => Configuration::get('GLS_BULTOS'),
            'GLS_NUM_FIJO_BULTOS' => Configuration::get('GLS_NUM_FIJO_BULTOS'),
            'GLS_NUM_ARTICULOS' => Configuration::get('GLS_NUM_ARTICULOS'),
            'GLS_RETORNO' => Configuration::get('GLS_RETORNO'),
            'GLS_RCS' => Configuration::get('GLS_RCS'),
            'GLS_DORIG' => Configuration::get('GLS_DORIG'),
            'GLS_VSEC' => Configuration::get('GLS_VSEC'),
            'GLS_ENVIAR_MAIL' => Configuration::get('GLS_ENVIAR_MAIL'),
            'GLS_EMAIL' => Configuration::get('GLS_EMAIL'),
            'GLS_CRON_TIME' => explode(';',Configuration::get('GLS_CRON_TIME')),
            'GLS_CSV_RATES' => Configuration::get('GLS_CSV_RATES'),
            'GLS_CSV_RATES_TYPE' => Configuration::get('GLS_CSV_RATES_TYPE'),
            'GLS_STATUS_SEND' => Configuration::get('GLS_STATUS_SEND'),
            'GLS_STATUS_COMPLETED' => Configuration::get('GLS_STATUS_COMPLETED'),
            'GLS_STATUS_FAILED' => Configuration::get('GLS_STATUS_FAILED'),
            'GLS_APLI4' => Configuration::get('GLS_APLI4'),
            'GLS_UPDATE_ORDER_STATUS_SEND' => Configuration::get('GLS_UPDATE_ORDER_STATUS_SEND'),
            'GLS_UPDATE_ORDER_STATUS_COMPLETED' => Configuration::get('GLS_UPDATE_ORDER_STATUS_COMPLETED'),
            'GLS_UPDATE_ORDER_STATUS_FAILED' => Configuration::get('GLS_UPDATE_ORDER_STATUS_FAILED'),
            'GLS_SHOW_CSV' => Configuration::get('GLS_SHOW_CSV'),
            'GLS_SENDER_NAME' => Configuration::get('GLS_SENDER_NAME'),
            'GLS_SENDER_ADDRESS' => Configuration::get('GLS_SENDER_ADDRESS'),
            'GLS_SENDER_CITY' => Configuration::get('GLS_SENDER_CITY'),
            'GLS_SENDER_CP' => Configuration::get('GLS_SENDER_CP'),
            'GLS_SENDER_COUNTRY' => Configuration::get('GLS_SENDER_COUNTRY'),
		);
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
		Configuration::updateValue('GLS_SERVICIO_SELECCIONADO_GLS10', '');
		Configuration::updateValue('GLS_SERVICIO_SELECCIONADO_GLS14', '');
		Configuration::updateValue('GLS_SERVICIO_SELECCIONADO_GLS24', '');
		Configuration::updateValue('GLS_SERVICIO_SELECCIONADO_GLSECO', '');
		Configuration::updateValue('GLS_SERVICIO_SELECCIONADO_GLSEBP', '');
		Configuration::updateValue('GLS_SERVICIO_SELECCIONADO_GLSPARCEL', '');
        foreach (array_keys($form_values) as $key) {
			$value = Tools::getValue($key);
			if (is_array($value)){
				Configuration::updateValue($key, implode(';',$value));
			} else {
				if ($key == 'GLS_DEF_PESO'){
					Configuration::updateValue($key, str_replace(',','.',$value));
				} else {
					Configuration::updateValue($key, $value);
				}
			}
        }
		$newCarrier = Tools::getValue('GLS_ADD_CARRIER','');
		if (!empty($newCarrier)){
			$newCarrier = $this->addCarrier($newCarrier);
			$idNewCarrier = $newCarrier->id;
			if ($idNewCarrier){
				$this->addZones($newCarrier);
				$this->addGroups($newCarrier);
				$this->addRanges($newCarrier);
			}
		}
		$validCarriers = Tools::getValue('GLS_VALID_CARRIERS','');
		if (!empty($validCarriers)){
			foreach ($validCarriers as $carrier){
				$oCarrier = Carrier::getCarrierByReference($carrier);
				if ($oCarrier->external_module_name != $this->name){
					$oCarrier->is_module = true;
					//$oCarrier->shipping_external = true;
					$oCarrier->need_range = 1;
					$oCarrier->external_module_name = $this->name;
					$oCarrier->update();
				}
			}
		}
    }

    public function getOrderShippingCost($params,$shipping_cost)
    {
	   
		$carrier = new Carrier($this->id_carrier);

		if ($this->isCarrierGLSNonModule($this->id_carrier)){
			if (Configuration::get('GLS_CSV_RATES') == 1){
				$gls_csv_rates_type = Configuration::get('GLS_CSV_RATES_TYPE');
				$rates = $this->tarifas($gls_csv_rates_type);
				
				$idReference = !empty($carrier->id_reference)?$carrier->id_reference:$id_carrier;

				
				if(in_array($idReference,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS10')))) {
                    $gls_tipo_servicio = 'ASM10';
                }
                if(in_array($idReference,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS14')))) {
                    $gls_tipo_servicio = 'ASM14';
                }
                if(in_array($idReference,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS24')))){
                    $gls_tipo_servicio = 'ASM24';
                }
                if(in_array($idReference,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSECO')))){
                    $gls_tipo_servicio = 'ECONOMY';
                }
				if(in_array($idReference,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL')))){
                    $gls_tipo_servicio = 'PARCELSHOP';
                }
				
				
				//obtenemos los datos del usuario
				$usuario_direccion_id = $params->id_address_delivery;
				$query = 'SELECT * FROM '._DB_PREFIX_.'address where id_address = "'.$usuario_direccion_id.'"';
				$usuario_datos = Db::getInstance()->ExecuteS($query);
				if (empty($usuario_datos[0])) return false;
				$query='SELECT iso_code FROM '._DB_PREFIX_.'country where id_country = "'.$usuario_datos[0]['id_country'].'"';
				$usuario_pais_id = Db::getInstance()->ExecuteS($query);
				$usuario_pais = $usuario_pais_id[0]['iso_code'];
				$usuario_cp =$usuario_datos[0]['postcode'];
				if ($gls_csv_rates_type == 'peso'){
					$valor = $params->getTotalWeight();
					if($valor<1){
						$valor=1;
					}
				}else{
					$valor = $params->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
				}
				$shipping_cost = $this->dame_tarifa($rates,$gls_tipo_servicio,$usuario_pais,$usuario_cp,$valor);
			} else {

				 // Datos del vendedor o la tienda
				 $customSender = Configuration::getMultiple(array('GLS_SENDER_NAME','GLS_SENDER_ADDRESS','GLS_SENDER_CP','GLS_SENDER_CITY','GLS_SENDER_COUNTRY'));
				 
				 $checkCustomSender = true;
				 foreach ($customSender as $csval){
					 if (empty($csval)) $checkCustomSender = false;
				 }
				 if ($checkCustomSender){
					 $paisOrig                      = $customSender['GLS_SENDER_COUNTRY'];
					 switch ($paisOrig){
						 case 34:
							$paisOrig = 'ES';
							break;
						 case 351:
							$paisOrig = 'PT';
							break;
					 }
				 } else {
					$shop_country_id = Configuration::get('PS_SHOP_COUNTRY_ID');
					$query='SELECT iso_code FROM '._DB_PREFIX_.'country where id_country = "'.$shop_country_id.'"';
					$tienda_pais_id = Db::getInstance()->ExecuteS($query);
					$tienda_pais = $tienda_pais_id[0]['iso_code'];
					$paisOrig = $tienda_pais;
				
				 }

				$address = new Address($params->id_address_delivery);
				$country = new Country($address->id_country);
				$idReference = !empty($carrier->id_reference)?$carrier->id_reference:$carrier->id_carrier;
				if (in_array($idReference,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP')))){
					if ($paisOrig != $country->iso_code && isset($this->ebp_countries[$country->iso_code])){
						return $shipping_cost;
					} else {
						return false;
					}
				} else {
					if ((in_array($paisOrig, array('PT','ES','AD','GI')) && in_array($country->iso_code, array('ES','AD','GI'))) || ($country->iso_code=='PT' && $paisOrig=='PT')){
						return $shipping_cost;
					} else {
						return false;
					}
				}
			}
			return $shipping_cost;
		} else {
			return $shipping_cost;
		}
		
        return $shipping_cost;
    }

    public function getOrderShippingCostExternal($params)
    {
        return 50;
    }

	
	function mergeTags($ids_pedidos){
		global $smarty;
		require_once('lib/PDFMerger.php');
		$pdf = new PDFMerger;

		$ids = explode(':',$ids_pedidos);
		$envios = array();
		foreach($ids as $id_pedido){
			if (!empty($id_pedido)){
				$resultado = Db::getInstance()->ExecuteS('SELECT e.codigo_barras, e.codigo_envio FROM '._DB_PREFIX_.'gls_envios AS e  where e.id_envio_order = "'.$id_pedido.'"');
				if (!empty($resultado[0]['codigo_barras'])){
					$pdfdata = file_get_contents($resultado[0]['codigo_barras']);
					 if (!empty($pdfdata)) {
						$pdf->addPDF($resultado[0]['codigo_barras'], 'all');
						$envios[]=$resultado[0]['codigo_envio'];
					}
				}
			}
		}
		$timestamp = time();
		if (!empty($envios)){
			if (Configuration::get('GLS_APLI4') == 0){
				$pdf->merge('browser', 'GLS_'.$timestamp.'.pdf');
			} else {
				$pdf->mergeApli4('browser', 'GLS_'.$timestamp.'.pdf');
			}
		} 
		$smarty->assign('ids',implode('-',$ids));
		$smarty->assign('timestamp',$timestamp);
		$smarty->assign('envios',implode(', ',$envios));
		return $this->display(__FILE__, 'templates/mergeTags.tpl');
    }

    function reimprimirEtiquetas($id_pedido=0, $adminOrders=false) {
			$res = Db::getInstance()->ExecuteS('SELECT e.codigo_envio FROM '._DB_PREFIX_.'gls_envios AS e  where e.id_envio_order = "'.$id_pedido.'"');
			$edit = $res[0]['codigo_envio'];    	
    		$this->imprimirEtiquetas($id_pedido, $adminOrders, $edit);
    }
    function imprimirEtiquetas($id_pedido=0, $adminOrders=false, $edit = false) {
        global $smarty, $cookie, $currentIndex;
        $error = false;
        $resultado = null;
		$smarty->assign('volver', '<a href="index.php?tab=AdminGlsshipping&token='.Tools::getValue('token').'"><strong>Volver</strong></a>');
		$smarty->assign('isEuroestandar', false);
		$smarty->assign('download_pdf', '');
		$smarty->assign('link_etiqueta', '');
		$smarty->assign('ventana_etiqueta', '');
		$smarty->assign('formulario', '');
		$smarty->assign('resultado', $resultado);
		 
        // Antes de guardar verificamos que no este guardado este envio
        if($id_pedido){
            $res = Db::getInstance()->ExecuteS('SELECT codigo_envio FROM '._DB_PREFIX_.'gls_envios WHERE id_envio_order = "'.$id_pedido.'"');

            if($res[0]['codigo_envio'] == ""){
                $hay_track=false;
            }
            else{
            	$hay_track=true;
            }
        } else{
            // Si no llego por GET el id_order redireccionamos
			return false;
        }
        
        if (!empty($edit)){
        		$hay_track = false;
        }

		$isEurobusinessparcel = false;
        if(!$hay_track){
			$select = 'SELECT o.id_order,o.id_cart,o.module,o.total_paid_real,c.name,c.id_reference,u.email,a.firstname,
			a.lastname,a.address1,a.address2,a.postcode,a.other,a.city,a.phone,a.phone_mobile,z.iso_code, o.id_carrier, o.reference, a.company ';

            $datos = Db::getInstance()->ExecuteS($select .
                'FROM '._DB_PREFIX_.'orders AS o
                JOIN '._DB_PREFIX_.'carrier AS c
                JOIN '._DB_PREFIX_.'customer AS u
                JOIN '._DB_PREFIX_.'address a
                JOIN '._DB_PREFIX_.'country AS z
                WHERE o.id_order = "'.$id_pedido.'"
                AND c.id_carrier=o.id_carrier
                AND u.id_customer = o.id_customer
                AND a.id_address = o.id_address_delivery
                AND a.id_country = z.id_country');

			
			if(in_array($datos[0]['id_reference'],explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS10')))) {
				$gls_tipo_servicio = 'GLS10';
				$servicio =1;
				$horario  =0;
			}
			if(in_array($datos[0]['id_reference'],explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS14')))) {
				$gls_tipo_servicio = 'GLS14';
				$servicio =1;
				$horario  =2;
			}
			if(in_array($datos[0]['id_reference'],explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS24')))){
				$gls_tipo_servicio = 'GLS24';
				$servicio =1;
				$horario  =3;
			}
			if(in_array($datos[0]['id_reference'],explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSECO')))){
				$gls_tipo_servicio = 'ECONOMY';
				$servicio =37;
				$horario  =16;
			}
			if(in_array($datos[0]['id_reference'],explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP')))){
				$gls_tipo_servicio = 'EUROBUSINESSPARCEL';
				$servicio =74;
				$horario  =3;
			}
			if(in_array($datos[0]['id_reference'],explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL')))){
				$gls_tipo_servicio = 'PARCELS';
				$servicio =1;
				$horario  =19;
			}

            
			$gls_peso_def = Configuration::get('GLS_DEF_PESO');
			
			if (!empty($gls_peso_def)){
				$gls_peso_origen = str_replace(',','.',$gls_peso_def);
			} else {
				//Obtenemos el peso y numero de productos
				$productos = Db::getInstance()->ExecuteS(
					'SELECT product_quantity, product_weight FROM '._DB_PREFIX_.'order_detail
					where id_order = "'.$id_pedido.'"');
				$peso = 0;
				$num_productos = 0;
				foreach ($productos as $producto){
					$peso += floatval($producto['product_quantity'] * $producto['product_weight']);
					$num_productos += $producto['product_quantity'];
				}
				if($peso < 1){
					$peso=1;
				}
				$gls_peso_origen = $peso;
			}

			$gls_peso_user = Tools::getValue('gls_peso_user');
			if (!empty($gls_peso_user)) $gls_peso_origen = $gls_peso_user;
			
			
			if ((int)$gls_peso_origen == 0) $gls_peso_origen = 1;
			
            $bultos_get = intval(Tools::getValue('gls_bultos_user'));
			if (empty($bultos_get)){

				$gls_bultos = Configuration::get('GLS_BULTOS');
				$gls_numero_paquetes = 1;
				if ($gls_bultos == 0){
					$gls_numero_paquetes = Configuration::get('GLS_NUM_FIJO_BULTOS');
				} else {
					$gls_num_articulos = Configuration::get('GLS_NUM_ARTICULOS');
					if (empty($gls_num_articulos)) $gls_num_articulos = 1;
					$gls_numero_paquetes = ceil($num_productos/$gls_num_articulos);
				}
				
			} else {
				$gls_numero_paquetes = (int)$bultos_get;
			}
			
			if ((int)$gls_numero_paquetes == 0) $gls_numero_paquetes = 1;
            //bultos predefinidos
            $rcs_get = Tools::getValue('gls_rcs_user');
			if ($rcs_get == '') $rcs_get = Configuration::get('GLS_RCS');
            $dorig_get = Tools::getValue('gls_dorig_user');
			if ($dorig_get == '') $dorig_get = Configuration::get('GLS_DORIG');
            $vsec_get = Tools::getValue('gls_vsec_user');
			if ($vsec_get === false ) $vsec_get = Configuration::get('GLS_VSEC');
			$retorno = intval(Tools::getValue('gls_retorno'));
			if ($retorno == '') $retorno = Configuration::get('GLS_RETORNO');
			$gls_observaciones_user = Tools::getValue('gls_observaciones_user');
			
            $incoterm = Tools::getValue('gls_incoterm_user');
			//if ($incoterm == '') $incoterm = Configuration::get('GLS_INCOTERM');
			
			
			if ($gls_tipo_servicio == 'EUROBUSINESSPARCEL'){
				$rcs_get = '';
				$vsec_get = '';
				$retorno = '';
			}
			


            // Obtenemos el num de pedido
            $gls_referencia = sprintf('%010d', $datos[0]['id_order']);

            if(version_compare(_PS_VERSION_, '1.5', '>')) {
                 $gls_referencia3 = $datos[0]['reference'];
            } else {
                 $gls_referencia3 = '';
            }
			

            //Obtenemos el importe total del pedido
            $gls_importe_servicio = $datos[0]['total_paid_real'];

            //if(version_compare(_PS_VERSION_, '1.5', '<')) {

            //Datos del comprador
            $gls_nombre_destinatario       = $datos[0]['firstname'].' '.$datos[0]['lastname'];
            $gls_nombre_via_destinatario   = $datos[0]['address1'].' '.$datos[0]['address2'];;
            $gls_poblacion_destinatario    = $datos[0]['city'];
            $gls_CP_destinatario           = $datos[0]['postcode'];
            $gls_telefono_destinatario     = $datos[0]['phone'];
            $gls_movil_destinatario        = $datos[0]['phone_mobile'];
            $gls_email_destinatario        = $datos[0]['email'];
            $gls_pais                      = $datos[0]['iso_code'];
            $gls_empresa                   = $datos[0]['company'];

			$obs = Db::getInstance()->ExecuteS('SELECT m.message
				FROM `'._DB_PREFIX_.'customer_thread` ct
				LEFT JOIN `'._DB_PREFIX_.'customer_message` m ON m.`id_customer_thread` = ct.`id_customer_thread`
				WHERE ct.`id_order` = '.(int)$id_pedido.'
				ORDER BY ct.`date_add` DESC'
			);
			//$obs = array_merge($o1,$o2);
            $observaciones = $gls_observaciones_user;
            foreach ($obs as $obv){
                $observaciones = $observaciones . ' ' . $obv['message'];
            }

			// Parcels
			$parcels = array();			
			if ($gls_tipo_servicio == 'PARCELS'){
				$parcels = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'gls_parcels where id_cart = '.$datos[0]['id_cart']);
			}
				
			/* if (!empty($parcels[0])){
				$horario = 19;
			}  */

            if($gls_empresa != '')
            {
                $observaciones = "\nEMPRESA: " . $gls_empresa . '. ' . $observaciones;
            }
			if($rcs_get != '')
            {
                $rcsdata = "\n                    <Pod>".(($rcs_get==0)?'N':'S')."</Pod>\n";
            }
			if($dorig_get != '')
            {
                $deptdata = "\n                      <Departamento><![CDATA[".(substr($dorig_get,0,50))."]]></Departamento>\n";
            }
			if(!empty($vsec_get))
            {
                $vsecdata = "\n                      <Seguro tipo=\"1\">
                        <Descripcion></Descripcion>
                        <Importe>".$vsec_get."</Importe>
                      </Seguro>";
            }

            if($gls_pais == 'ES') {
               $gls_pais = '34';
            } else if($gls_pais == 'PT') {
               $gls_pais = '351';
               $gls_CP_destinatario = substr($gls_CP_destinatario,0,4) . '-' . $gls_CP_destinatario = substr($gls_CP_destinatario,-3);
             } else if($gls_pais == 'AD') {
               $gls_CP_destinatario = 'AD'.$gls_CP_destinatario;
            }

            $metodo_pago = $datos[0]['module'];
            
            $valid_cod = Configuration::get('GLS_COD');
			
            if(!empty($valid_cod) && $metodo_pago == $valid_cod){
                $gls_reembolso=floatval($gls_importe_servicio);
            }
            else{
                $gls_reembolso = 0;
            }

            // Datos del vendedor o la tienda
            $vendedor = Configuration::getMultiple(array('PS_SHOP_NAME','PS_SHOP_ADDR1','PS_SHOP_CODE','PS_SHOP_CITY','PS_SHOP_COUNTRY_ID'));
			$customSender = Configuration::getMultiple(array('GLS_SENDER_NAME','GLS_SENDER_ADDRESS','GLS_SENDER_CP','GLS_SENDER_CITY','GLS_SENDER_COUNTRY'));
			
			$checkCustomSender = true;
			foreach ($customSender as $csval){
				if (empty($csval)) $checkCustomSender = false;
			}
			if ($checkCustomSender){
				$gls_nombre_remitente          = $customSender['GLS_SENDER_NAME'];
				$gls_nombre_via_remitente      = $customSender['GLS_SENDER_ADDRESS'];
				$gls_poblacion_remitente       = $customSender['GLS_SENDER_CITY'];
				$gls_CP_remitente              = $customSender['GLS_SENDER_CP'];
				$paisOrig                      = $customSender['GLS_SENDER_COUNTRY'];
			} else {
				$gls_nombre_remitente          = $vendedor['PS_SHOP_NAME'];
				$gls_nombre_via_remitente      = $vendedor['PS_SHOP_ADDR1'];
				$gls_poblacion_remitente       = $vendedor['PS_SHOP_CITY'];
				$gls_CP_remitente              = $vendedor['PS_SHOP_CODE'];

				$query='SELECT iso_code FROM '._DB_PREFIX_.'country where id_country = "'.$vendedor['PS_SHOP_COUNTRY_ID'].'"';
				$tienda_pais_id = Db::getInstance()->ExecuteS($query);
				$tienda_pais = $tienda_pais_id[0]['iso_code'];
				$paisOrig = $tienda_pais;
				if($paisOrig == 'ES') {
				  $paisOrig = '34';
				} else if($paisOrig == 'PT'){
				  $paisOrig = '351';
				}
			}

        	//Realizamos el pedido

            $version = $this->version;
            $URL = Configuration::get('GLS_URL');
            $uidCliente = Configuration::get('GLS_GUID');

            $URL = str_replace("http://", "https://", $URL);

			//if ($edit){
				$URL = 'https://wsclientes.asmred.com/b2b.asmx';
			//}
			
            $gls_telefono_destinatario = str_replace(" ", "", $gls_telefono_destinatario);
            $gls_movil_destinatario = str_replace(" ", "", $gls_movil_destinatario);
			
			if ($gls_telefono_destinatario == '') { $gls_telefono_destinatario = $gls_movil_destinatario;    }
			if ($gls_movil_destinatario    == '') { $gls_movil_destinatario    = $gls_telefono_destinatario; }			



            if($servicio==74 && $gls_peso_origen < 3) $servicio=76;

            $XML=
                '<?xml version="1.0" encoding="utf-8"?>
                <soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
                  <soap12:Body>
                    <GrabaServicios  xmlns="http://www.asmred.com/">
                      <docIn>
                <Servicios uidcliente="' . $uidCliente . '">';
				if ($edit){
            	$XML .= '<Envio action="delete_insert" codigoAnular="'.$edit.'">';
				} else {
            	$XML .= '<Envio>';
         	}
            
			if($rcsdata != ''){$XML.=$rcsdata;}	  
            $XML.='<Portes>P</Portes>
                    <Servicio>' . $servicio . '</Servicio>
                    <Horario>' . $horario . '</Horario>
                    <Bultos>' . $gls_numero_paquetes . '</Bultos>
                    <Retorno>' . $retorno . '</Retorno>
                    <Peso>' . $gls_peso_origen . '</Peso>';

                    if(($servicio==74 || $servicio==76) && $incoterm > 0)
                    {
                      $XML.='<Aduanas><Incoterm>'. $incoterm .'</Incoterm></Aduanas>';
                    }

                    if($gls_reembolso != 0)
                    {
                        $XML.='<Importes><Reembolso>'. $gls_reembolso .'</Reembolso></Importes>';
                    }

					$CpAndorra=(!empty($parcels[0]['cp']))?$parcels[0]['cp']:'';

                    $XML.='<Remite>';
					if(!empty($deptdata)){$XML.=$deptdata;}	
                    $XML.='<Nombre><![CDATA[' . $gls_nombre_remitente . ']]></Nombre>
                      <Direccion><![CDATA[' . $gls_nombre_via_remitente . ']]></Direccion>
                      <Poblacion><![CDATA[' . $gls_poblacion_remitente . ']]></Poblacion>
                      <Pais>'.$paisOrig.'</Pais>
                      <CP>' . $gls_CP_remitente . '</CP>
                    </Remite>
                    <Destinatario>
                      <Nombre><![CDATA[' . $gls_nombre_destinatario . ']]></Nombre>';

		if (!empty($parcels[0])) {
				$gls_CP_destinatario = $CpAndorra;
                $XML.='<Direccion><![CDATA[' . $parcels[0]['direccion'] . ']]></Direccion>
                      <Poblacion><![CDATA[' . $parcels[0]['localidad'] . ']]></Poblacion>
                      <Pais>' . $gls_pais. '</Pais>
                      <CP>' . $CpAndorra . '</CP>
                      <Telefono>' . $gls_telefono_destinatario . '</Telefono>
                      <Movil>' . $gls_movil_destinatario . '</Movil>
                      <Observaciones><![CDATA[' . html_entity_decode($observaciones) . ']]></Observaciones>
                      <Email>' . $gls_email_destinatario . '</Email>
		      <Codigo>' . $parcels[0]['codigo'] . '</Codigo>';
		}else{
                $XML.='<Direccion><![CDATA[' . $gls_nombre_via_destinatario . ']]></Direccion>
                      <Poblacion><![CDATA[' . $gls_poblacion_destinatario . ']]></Poblacion>
                      <Pais>' . $gls_pais. '</Pais>
                      <CP>' . $gls_CP_destinatario . '</CP>
                      <Telefono>' . $gls_telefono_destinatario . '</Telefono>
                      <Movil>' . $gls_movil_destinatario . '</Movil>
                      <Observaciones><![CDATA[' . html_entity_decode($observaciones) . ']]></Observaciones>
                      <Email>' . $gls_email_destinatario . '</Email>';
				}
              $XML.='</Destinatario>';
			  if(!empty($vsecdata)){$XML.=$vsecdata;}	
              $XML.='<Referencias>
                      <Referencia tipo="0">' . $gls_referencia . '</Referencia>';

                    if($gls_referencia3 != '')
                    {
                           $XML.='<Referencia tipo="C">' . $gls_referencia3 . '</Referencia>';
                    }

                    $XML.='</Referencias>
					  <DevuelveAdicionales> 
						<Etiqueta tipo="PDF"></Etiqueta>
					  </DevuelveAdicionales> 
                  </Envio>
                  <Plataforma>Prestashop ' . $version . '</Plataforma>
                </Servicios></docIn>
                    </GrabaServicios>
                  </soap12:Body>
                </soap12:Envelope>';
                
            $this->logger->logInfo($XML);

            $ch = curl_init();
			
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
            curl_setopt($ch, CURLOPT_URL, $URL );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $XML );
            //curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml; charset=UTF-8"));

            $postResult = curl_exec($ch);

			$startTag = strpos($postResult,'<Etiquetas>')+11;
			$lengthTag = strpos($postResult,'</Etiquetas>')-$startTag;
			$noTagResult = substr_replace($postResult, '_ETIQUETA_', $startTag,$lengthTag);

			$this->logger->logInfo($noTagResult);

            if (curl_errno($ch)) {
            }
            $xml = simplexml_load_string($postResult, NULL, NULL, "http://www.w3.org/2003/05/soap-envelope");
            $xml->registerXPathNamespace('asm', 'http://www.asmred.com/');
            $arr = $xml->xpath("//asm:GrabaServiciosResponse/asm:GrabaServiciosResult");
            $ret = $arr[0]->xpath("//Servicios/Envio");
            //return $ret[0];
            $return = $ret[0]->xpath("//Servicios/Envio/Resultado/@return");

			$referenciaN = (string)$arr[0]->xpath('//Servicios/Envio/Referencias/Referencia[@tipo="N"]')[0];
				
			$this->logger->logInfo($referenciaN);
			
            $_SESSION["ultimoErrorGLS"] = "";

            if (!empty($return[0]) && $return[0] != '0') {
                  $error = $arr[0]->xpath("//Servicios/Envio/Errores/Error");
                  $_SESSION["ultimoErrorGLS"] = 'No se pudo grabar en GLS. Retorno: ' . $return[0] . ". " . (!empty($error[0])?$error[0]:'');
				  $smarty->assign('error', $_SESSION["ultimoErrorGLS"]);
                  return false;
            } else {
                //$referencia = intval($gls_referencia);
                $referencia = $id_pedido;
				if (Configuration::get('GLS_UPDATE_ORDER_STATUS_SEND') == 1){
					$statusSend = Configuration::get('GLS_STATUS_SEND');
					if (version_compare(_PS_VERSION_, '1.5', '>')){
						$id_order_state = (int)($statusSend);
						$objOrder = new Order($referencia);
						$history = new OrderHistory();
						$history->id_order = (int)$objOrder->id;
						$history->changeIdOrderState((int)($id_order_state), (int)($objOrder->id),true);
						$history->id_order_state = (int)($id_order_state);
						$history->add(true);
					} else {
						$id_order_state = (int)($statusSend);
						$objOrder = new Order($referencia);
						$history = new OrderHistory();
						$history->id_order = (int)($referencia);
						$history->id_order_state = (int)($statusSend);
						$history->changeIdOrderState((int)($statusSend), $objOrder);
						$history->add(true);
					}
				}
            }

            $cb = $ret[0]->xpath("//Servicios/Envio/@codbarras");


            $codTracking = $cb[0]["codbarras"];

			
            $etiquetaSrc = $arr[0]->xpath('//Servicios/Envio/Etiquetas/Etiqueta')[0];
            $gls_etiqueta = $etiquetaSrc[0];

			// Ya tenemos todos los datos necesarios para guardar en la tabla de envios

			if($ruta=$this->guardarEnvio2($id_pedido, $codTracking, $referenciaN, $gls_etiqueta, $gls_CP_destinatario,$gls_numero_paquetes, $retorno, $rcs_get, $gls_peso_origen, $vsec_get, $dorig_get, $gls_observaciones_user)){
				//despues enviamos pdf codigo barras
				$link_etiqueta = 'index.php?tab=AdminGlsshipping&ids_order_envio='.$id_pedido.'&option=mergetags&token='.Tools::getValue('token');
				$smarty->assign('link_etiqueta', $link_etiqueta);

				$resultado = $this->l('Se ha creado una nueva expedicion, por lo que debe reetiquetar los todos bultos.');
				$smarty->assign('resultado', $resultado);

			}
			else{
				$error = "";
				$ruta = _PS_MODULE_DIR_."glsshipping/PDF";
				// comprobamos si la carpeta existe
				$existe = file_exists($ruta);
				$error .= "<p>La carpeta modules/glsshipping/PDF existe = $existe</p>";
				// comprobamos los permisos
				$permisos = substr(sprintf('%o', fileperms($ruta)), -4);
				$error .= "<p>La carpeta modules/glsshipping/PDF permisos = $permisos</p>";

				$smarty->assign('errores',$error);
				$smarty->assign('error',$error);
			}

            //if (version_compare(_PS_VERSION_, '1.5', '<')){

            if(Configuration::get('GLS_ENVIAR_MAIL') == true){
                //////////////////////////////////////////////////////////////////////////
    		    $error = false;
    		    $resultado = false;
    		    $mensaje = false;
                $mensaje_html = '';

                (!isset($_POST['mensaje']) || empty($_POST['mensaje'])) ? $mensaje = Configuration::get('GLS_EMAIL') : $mensaje = $_POST['mensaje'];

    			if($id_pedido){
    		    	//obtenemos los datos necesarios del usuario
    		            $datos = Db::getInstance()->ExecuteS(
    		            	'SELECT o.id_order,o.reference,u.firstname,u.lastname,u.email,e.url_track
    		            	FROM '._DB_PREFIX_.'orders AS o
    		            	JOIN '._DB_PREFIX_.'customer AS u ON u.id_customer = o.id_customer
    		            	JOIN '._DB_PREFIX_.'gls_envios AS e ON e.id_envio_order = o.id_order
    		            	WHERE o.id_order = "'.$id_pedido.'"');

    				$usuario_nombre    = $datos[0]['firstname'];
    				$usuario_apellidos = $datos[0]['lastname'];
    				$usuario_email     = $datos[0]['email'];
    				//$orden_pedido      = sprintf('%06d', $id_pedido);
                    $orden_pedido      = $datos[0]['reference'];
    				$asunto            = $this->l('Código seguimiento del pedido num. ').$orden_pedido;
    				$enlace            = '<p><a href="'.$datos[0]['url_track'].'">'.$this->l('Ver seguimiento').'</a></p>';
    				$mensaje .= '<p>'.$enlace.'</p>';
                    $followup = $datos[0]['url_track'];

    				if (Mail::Send(intval($cookie->id_lang),'in_transit',$asunto,array('{meta_products}'=>$mensaje,'{firstname}' => $usuario_nombre,'{lastname}' => $usuario_apellidos,'{order_name}' => $orden_pedido,'{message}' => $mensaje,'{followup}' => $followup,'{email}' => $usuario_email),$usuario_email)){
                        // Guardamos el nuevo mensaje
    		        	Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'gls_email SET mensaje="'.$_POST['mensaje'].'" WHERE id = "1"');
    		        	$resultado = '<p>Se envio la URL de seguimiento del pedido <b>'.$orden_pedido.'</b> correctamente al siguiente destinatario <b>'.$usuario_nombre.' '.$usuario_apellidos.'</b> al email <b>'.$usuario_email.'</b></p>';
    		        }
    		        else{
    		            $error = Tools::displayError($this->l('Hubo un error al intentar enviar el mensaje a: ').$usuario_nombre.' '.$usuario_apellidos.' con el email: '.$usuario_email);
    		        }
    		        $smarty->assign('formulario', false);
    			//}
    		//}


            }
            else
            {
            }
            }

			if ($gls_tipo_servicio == 'EUROBUSINESSPARCEL'){
				$isEurobusinessparcel = true;
			}
        } else{
        	//obtenemos la url de la etiqueta PDF ya registrado
        	    $res = Db::getInstance()->ExecuteS('SELECT e.codigo_barras, e.codigo_envio FROM '._DB_PREFIX_.'gls_envios AS e  where e.id_envio_order = "'.$id_pedido.'"');
	            $smarty->assign('download_pdf', $res[0]['codigo_barras']);
                $url= 'https://www.asmred.com/Extranet/public/ecmLabel.aspx';
                $url .= '?codbarras='. $res[0]['codigo_envio'];
                $url .= '&uid=' . Configuration::get('GLS_GUID');

                $smarty->assign('ventana_etiqueta', $url);
				
				
				 $datosCarrier = Db::getInstance()->ExecuteS(
				 'SELECT c.name, o.id_carrier FROM '._DB_PREFIX_.'orders AS o
                JOIN '._DB_PREFIX_.'carrier AS c
                WHERE o.id_order = "'.$id_pedido.'"
                AND c.id_carrier=o.id_carrier');

				if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP') == $datosCarrier[0]['id_carrier']){
					$isEurobusinessparcel = true;
				}
        }

		$smarty->assign('isEurobusinessparcel', $isEurobusinessparcel);
		if (!empty($error)){
			$smarty->assign('error', $error);
		}
		$smarty->assign('resultado', $resultado);
        $smarty->assign('volver', '<a href="index.php?controller=AdminGlsshipping&token='.Tools::getValue('token').'"><strong>Volver</strong></a>');
        $smarty->assign('path_img_logo', $this->_path.'img/logo_gls.png');//.jpg');

		if($adminOrders){
			return true;
		} else {
			if(version_compare(_PS_VERSION_, '1.6', '>')) {
				return $this->display(__FILE__, 'templates/TagGLS.tpl');
			} else {
				return $this->display(__FILE__, 'etiqueta2.tpl');
			}
		}
    }
    
    
	function imprimirEtiquetasMasivo($ids_pedidos){
		$ids = explode(':',$ids_pedidos);
		foreach($ids as $id_pedido){
			if (!empty($id_pedido)) {
				$this->imprimirEtiquetas($id_pedido, true);
			}
		}
		return true;
	}    
    
    

    // Funcion encargada de insertar/actualizar el estado de un envio
    function guardarEnvio($id_order,$codigo_envio,$url_track,$num_albaran,$codigo_barras)
    {

    	// preparamos para guardar el archivo pdf
	    $nombre = "etiqueta_".$id_order.".pdf";
    	$ruta   =  "../modules/glsshipping/PDF/".$nombre;
    	$descodificar = base64_decode($codigo_barras);

		if(!$fp2 = fopen($ruta,"wb+")){
			return false;
		}
		if(!fwrite($fp2, trim($descodificar))){
			}
		fclose($fp2);

    	//preparamos la URL para el track
    	$fecha = date('d/m/y');
    	$cortar=split("\?",$url_track);
        $url_seguimiento=$cortar[0];
        $enlace=$url_seguimiento."?servicio=".$codigo_envio."&fecha=".$fecha;

        Db::getInstance()->Execute(
        	'UPDATE '._DB_PREFIX_.'gls_envios SET
        	codigo_envio = "'.$codigo_envio.'",
        	url_track = "'.$enlace.'",
        	num_albaran = "'.$num_albaran.'",
        	codigo_barras = "'.$ruta.'",
        	fecha = "'.date('Y-m-d H:i:s').'"
        	WHERE id_envio_order = "'.$id_order.'"');

		Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'orders SET shipping_number="'.$this->comprimir_num_track($codigo_envio).'" WHERE id_order = "'.$id_order.'"');

        // Actualizar el tracking en la tabla order_carrier
        if((version_compare(_PS_VERSION_, '1.5', '>'))) {
            Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'order_carrier SET tracking_number="'.$codigoBarras.'" WHERE id_order = "'.$id_order.'"');
        }

        return $ruta;
    }

     // Funcion encargada de insertar/actualizar el estado de un envio
    function guardarEnvio2($id_order,$codigoBarras,$num_albaran,$codigo_barras,$cp_destino, $bultos, $retorno, $rcs, $peso, $vsec, $dorig, $observaciones)
    {
    	// preparamos para guardar el archivo pdf
	    $nombre = "etiqueta_".$id_order.".pdf";
    	$ruta   =  "../modules/glsshipping/PDF/".$nombre;
    	$descodificar = base64_decode($codigo_barras);

		if(!$fp2 = fopen($ruta,"wb+")){
			return false;
		}
		if(!fwrite($fp2, trim($descodificar))){
			}
		fclose($fp2);

        //$enlace=_PS_MODULE_DIR_."glsshipping/tracking.php?codbarras=".$codigoBarras."&uid=".Configuration::get('GLS_GUID');
        //$enlace='https://www.asmred.com/Extranet/Public/ExpedicionGLS.aspx?cpDst='. $cp_destino .'&codigo='.$codigoBarras;
        $enlace = 'https://m.gls-spain.es/e/'.$codigoBarras.'/'.$cp_destino;


        Db::getInstance()->Execute(
        	'UPDATE '._DB_PREFIX_.'gls_envios SET
        	codigo_envio = "'.$codigoBarras.'",
        	url_track = "'.$enlace.'",
        	num_albaran = "'.$num_albaran.'",
        	codigo_barras = "'.$ruta.'",
        	bultos = "'.$bultos.'",
			retorno = "'.$retorno.'",
			rcs = "'.$rcs.'",
			peso = "'.$peso.'",
			vsec = "'.$vsec.'",
			dorig = "'.$dorig.'",
			observaciones = "'.$observaciones.'",
        	fecha = "'.date('Y-m-d H:i:s').'"
        	WHERE id_envio_order = "'.$id_order.'"');
			
			

		Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'orders SET shipping_number="'.$codigoBarras.'" WHERE id_order = "'.$id_order.'"');

        // Actualizar el tracking en la tabla order_carrier
        if((version_compare(_PS_VERSION_, '1.5', '>'))) {
            Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'order_carrier SET tracking_number="'.$codigoBarras.'" WHERE id_order = "'.$id_order.'"');
        }


        return $ruta;
    }

    function comprimir_num_track($codigo)
    {
        $separar = split("-",$codigo);
        $comprimir="";
        foreach($separar as $linea){
            $comprimir.=$linea;
        }
        return $comprimir;
    }
    function inicializarGlsEnvios($id_pedido = null, $limit = '')
    {
		$this->actualizaEstadoEnviosGLS();
		
		if ($id_pedido){
			if(!Db::getInstance()->ExecuteS('SELECT id_envio_order FROM '._DB_PREFIX_.'gls_envios where id_envio_order = "'.$id_pedido.'"')){
				Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'gls_envios (id_envio_order,codigo_envio,url_track,num_albaran,fecha) VALUES ("'.$id_pedido.'","","","","'.date('Y-m-d H:i:s').'")');		
			}
		} else {
			// verificamos si hay pedidos sin registro de envio nuevo
			$envios = false;

			$pedidosNoModule = '';

			if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS10') != ''){ 
				$aCarriers = explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS10'));
				foreach($aCarriers as $aC){
					$carrier1 = new Carrier($aC);
					$pedidosNoModule .= 'OR c.id_reference = '.$carrier1->id_reference.' ';
				}
			}
			if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS14') != ''){ 
				$aCarriers = explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS14'));
				foreach($aCarriers as $aC){
					$carrier2 = new Carrier($aC);
					$pedidosNoModule .= 'OR c.id_reference = '.$carrier2->id_reference.' ';
				}
			}
			if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS24') != ''){ 
				$aCarriers = explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS24'));
				foreach($aCarriers as $aC){
					$carrier3 = new Carrier($aC);
					$pedidosNoModule .= 'OR c.id_reference = '.$carrier3->id_reference.' ';
				}
			}
			if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSECO') != ''){ 
				$aCarriers = explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSECO'));
				foreach($aCarriers as $aC){
					$carrier4 = new Carrier($aC);
					$pedidosNoModule .= 'OR c.id_reference = '.$carrier4->id_reference.' ';
				}
			}
			if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP') != ''){ 
				$aCarriers = explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP'));
				foreach($aCarriers as $aC){
					$carrier5 = new Carrier($aC);
					$pedidosNoModule .= 'OR c.id_reference = '.$carrier5->id_reference.' ';
				}
			}
			if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL') != ''){
				$aCarriers = explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL'));
				foreach($aCarriers as $aC){
					$carrier6 = new Carrier($aC);
					$pedidosNoModule .= 'OR c.id_reference = '.$carrier6->id_reference.' ';
				}
			}
			$join = ' ';
			if($pedidosNoModule != '') {
				//$pedidosNoModule = substr($pedidosNoModule, 3);
				
				$pedidos = Db::getInstance()->ExecuteS('SELECT o.id_order 
			   FROM '._DB_PREFIX_.'orders o
			   JOIN '._DB_PREFIX_.'carrier c ON (c.id_reference = o.id_carrier OR c.id_carrier = o.id_carrier)
			   WHERE c.external_module_name = "glsshipping" '.$pedidosNoModule.'
			   GROUP BY o.id_order ORDER BY o.id_order DESC '.$limit);
				
				foreach ($pedidos as $pedido){
					$enviogls = Db::getInstance()->ExecuteS('SELECT id_envio FROM '._DB_PREFIX_.'gls_envios WHERE id_envio_order='.$pedido['id_order']);
					if (empty($enviogls)){
						$envios[]['id_order'] = $pedido['id_order'];
					}
				}
			} else {
				$envios = false;
			}

			if(!$envios){
				return true;
			}
			foreach ($envios as $envio){
				Db::getInstance()->Execute('INSERT INTO '._DB_PREFIX_.'gls_envios (id_envio_order,codigo_envio,url_track,num_albaran,fecha) VALUES ("'.$envio['id_order'].'","","","","'.date('Y-m-d H:i:s').'")');		
			}
			return true;
		}
    }
    function limpiarNumTrack($codigo)
    {
        if(!$codigo){
            return false;
        }
        $codigo = substr($codigo,1,36);
        return $codigo;
    }
    function enviarEmailTrack($id_pedido=false)
    {
    	global $smarty, $cookie;

    	$error = false;
		$resultado = false;
		$mensaje = false;

		if(!isset($_POST['mensaje'])){
			//cargamos mensaje anterior
            $datos = Db::getInstance()->ExecuteS('SELECT mensaje FROM '._DB_PREFIX_.'gls_email');
            $mensaje = $datos[0]['mensaje'];
            $url_form = 'index.php?tab=AdminGlsshipping&id_order_envio='.$id_pedido.'&option=envio&token='.Tools::getValue('token');
	        $smarty->assign('mensaje', $mensaje);
			$smarty->assign('formulario', true);
			$smarty->assign('url_formulario', $url_form);
		}
		else{
			if($id_pedido){
		    	//obtenemos los datos necesarios del usuario
		            $datos = Db::getInstance()->ExecuteS(
		            	'SELECT o.id_order,u.firstname,u.lastname,u.email,e.url_track
		            	FROM '._DB_PREFIX_.'orders AS o
		            	JOIN '._DB_PREFIX_.'customer AS u
		            	JOIN '._DB_PREFIX_.'gls_envios AS e
		            	WHERE o.id_order = "'.$id_pedido.'" AND
		            	u.id_customer = o.id_customer AND
		            	e.id_envio_order = "'.$id_pedido.'"');

				$usuario_nombre    = $datos[0]['firstname'];
				$usuario_apellidos = $datos[0]['lastname'];
				$usuario_email     = $datos[0]['email'];
				$orden_pedido      = sprintf('%06d', $id_pedido);
				$asunto            = "Codigo seguimiento del pedido num. ".$orden_pedido;
				$enlace            = '<p><a href="'.$datos[0]['url_track'].'">Ver seguimiento</a></p>';
				$mensaje = $_POST['mensaje'].'<p>'.$enlace.'</p>';

		        if (Mail::Send(intval($cookie->id_lang),'order_customer_comment',$asunto,array('{firstname}' => $usuario_nombre,'{lastname}' => $usuario_apellidos,'{order_name}' => $orden_pedido,'{message}' => $mensaje),$usuario_email)){
		        	// Guardamos el nuevo mensaje
		        	Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'gls_email SET mensaje="'.$_POST['mensaje'].'" WHERE id = "1"');
		        	$resultado = '<p>Se envio la URL de seguimiento del pedido <b>'.$orden_pedido.'</b> correctamente al siguiente destinatario <b>'.$usuario_nombre.' '.$usuario_apellidos.'</b> al email <b>'.$usuario_email.'</b></p>';
		        }
		        else{
		            $error = Tools::displayError($this->l('Hubo un error al intentar enviar el mensaje a: ').$usuario_nombre.' '.$usuario_apellidos.' con el email: '.$usuario_email);
		        }
		        $smarty->assign('formulario', false);
			}
		}

		$smarty->assign('volver', '<a href="index.php?tab=AdminGlsshipping&token='.Tools::getValue('token').'"><strong>'.$this->l('Volver').'</strong></a>');
        $smarty->assign('error', $error);
		$smarty->assign('resultado', $resultado);
		$smarty->assign('path_img_logo', $this->_path.'img/logo_gls.png');

		return $this->display(__FILE__, 'etiqueta2.tpl');
    }

	
	
    protected function addCarrier($cname)
    {
        $carrier = new Carrier();

        $carrier->name = $cname;
        $carrier->is_module = true;
        $carrier->active = 1;
        $carrier->range_behavior = 1;
        $carrier->need_range = 1;
        $carrier->shipping_external = true;
        $carrier->range_behavior = 0;
        $carrier->external_module_name = $this->name;
        $carrier->shipping_method = 2;

        foreach (Language::getLanguages() as $lang)
            $carrier->delay[$lang['id_lang']] = $this->l('Envío rápido');

        if ($carrier->add() == true)
        {
            @copy(dirname(__FILE__).'/views/img/carrier_image.jpg', _PS_SHIP_IMG_DIR_.'/'.(int)$carrier->id.'.jpg');
            return $carrier;
        }

        return false;
    }

    protected function addGroups($carrier)
    {
        $groups_ids = array();
        $groups = Group::getGroups(Context::getContext()->language->id);
        foreach ($groups as $group)
            $groups_ids[] = $group['id_group'];

        $carrier->setGroups($groups_ids);
    }

    protected function addRanges($carrier)
    {
        $range_price = new RangePrice();
        $range_price->id_carrier = $carrier->id;
        $range_price->delimiter1 = '0';
        $range_price->delimiter2 = '10000';
        $range_price->add();

        $range_weight = new RangeWeight();
        $range_weight->id_carrier = $carrier->id;
        $range_weight->delimiter1 = '0';
        $range_weight->delimiter2 = '10000';
        $range_weight->add();
    }

    protected function addZones($carrier)
    {
        $zones = Zone::getZones();

        foreach ($zones as $zone)
            $carrier->addZone($zone['id_zone']);
    }

	

    protected function tarifas($tipo){
    	$archivo = _PS_MODULE_DIR_.'glsshipping/asm.tarifas.'.$tipo.'.csv';

        $tarifas = Array();

        if($fp = fopen ( $archivo , "r" )){
            while (( $data = fgetcsv ( $fp , 1000 , ";" )) !== FALSE ) {
                $tarifas[] = Array( "servicio"      => $data[0],
                                    "pais"          => $data[1],
                                    "cp_origen"     => $data[2],
                                    "cp_destino"    => $data[3],
                                    $tipo          => $data[4],
                                    "importe"       => $data[5]);
            }
            fclose ( $fp );
            return $tarifas;
        }
        else{
            return false;
        }
    }

    function dame_tarifa($tarifas,$servicio,$pais,$cp,$valor){
        $max=count($tarifas);
        $cp=intval($cp);
        //$peso=floor($peso);
        $segmento = Array();
        for($i=1;$i<$max;$i++){
			if($tarifas[$i]['servicio'] == $servicio){
				if($tarifas[$i]['pais'] == $pais){
					$cp_origen=intval($tarifas[$i]['cp_origen']);
					$cp_destino=intval($tarifas[$i]['cp_destino']);
					if($cp >= $cp_origen){
						if($cp <= $cp_destino){
							$segmento[]=Array("peso" => floatval($tarifas[$i]['peso']),"precio" => floatval($tarifas[$i]['importe']));
						}
					}
				}
			}
        }
        if(!function_exists('ordenar')){
            function ordenar($x, $y){
                if ( $x['peso'] == $y['peso'] ){
                    return 0;
                }
                else if ( $x['peso'] < $y['peso'] ){
                    return -1;
                }
                else{
                    return 1;
                }
            }
        }

        usort($segmento,'ordenar');
        $precio_envio = -1;
        $max=count($segmento);
        $peso_min = floatval($segmento[0]['peso']);
        $precio_min = floatval($segmento[0]['precio']);
        $peso_max = floatval($segmento[$max-2]['peso']);
        $precio_max = floatval($segmento[$max-2]['precio']);
        $precio_despues_max = floatval($segmento[$max-1]['precio']);

        if($peso <= $peso_min){
            $precio_envio = $precio_min;
        }
        else if($peso >= $peso_max){
            $peso_restante = $peso-$peso_max;
            $precio_restante = $peso_restante*$precio_despues_max;
            $precio_envio = $precio_max+$precio_restante;
        }
        else{
            for($i=0;$i<$max;$i++){
                if($peso != $segmento[$i]['peso']){
                    if($peso < $segmento[$i]['peso']){
                        $precio_envio = $segmento[$i]['precio'];
                        $i=$max;
                    }
                }
                else{ //es igual
                    $precio_envio = $segmento[$i]['precio'];
                    $i=$max;
                }
            }
        }
        return $precio_envio;
    }

	public function hookDisplayAdminOrderMain($params) {
		return $this->hookAdminOrder($params);
	}
	
	public function hookAdminOrder($params) {
        global $smarty,$cookie;       
        
		$isEurobusinessparcel = false;
		$regenerar = '';
		$generarEnvioHook = Tools::getValue('regenerar');
        $bultos = (int) Tools::getValue('gls_bultos_user');
        $bultos_error_msg = '';
		
		 //if(version_compare(_PS_VERSION_, '1.7', '<')) {
			$id_order = Tools::getValue('id_order');
			$valida = DB::getInstance()->ExecuteS('SELECT o.valid FROM '._DB_PREFIX_.'orders AS o WHERE id_order = "'.$id_order.'" LIMIT 1');
			$valida = $valida[0]['valid'];
		/* }else{
			print_r($params['order']);die;
			$id_order = $params['order']->id; //Tools::getValue('id_order');
			$valida = $params['order']->valid;
		} */
		
		
		
        if(isset($generarEnvioHook) || !empty($generarEnvioHook)) {
            if(isset($bultos) && !empty($bultos)) {
                if(!is_int($bultos)) {
                    $bultos_error_msg = $this->l('Solo debe introducir números enteros en el campo "Bultos".');
                    $smarty->assign('mensaje',$bultos_error_msg);
                    $smarty->assign('gls_lopeta', $this->displayName.' - Etiquetas de envío para clientes');
                    $smarty->assign('gls_version', $this->description.' v. '.$this->version);
                    $smarty->assign('gls_state','1');

                    return $this->display(__FILE__, 'views/templates/hook/adminErrorOrder.tpl');
                }
            }
        }

		// @generarEnvioHook permite comprobar si se realiza la llamada para generar la etiqueta desde el apartado de administración de pedidos
		if(!isset($generarEnvioHook) || empty($generarEnvioHook)) {
			// Comprobamos que sea GLS quien realice el envío
            $orderTemp = new Order($id_order);
			//sdie($orderTemp->id_carrier);
			
            if(	$this->isCarrierGLSNonModule($orderTemp->id_carrier)) {
				if($valida) {
					$history = new OrderHistory();
					$items_order_state = $history->getLastOrderState($id_order);
					$query = 'SELECT * FROM  '._DB_PREFIX_.'gls_envios WHERE  id_envio_order = '.$id_order;
					$rowCarrier = Db::getInstance()->getRow($query);
					$path_myroot   = _PS_BASE_URL_.__PS_BASE_URI__;
					if($rowCarrier['codigo_envio']!=NULL) {
						//if(!file_exists(_PS_ROOT_DIR_."/modules/glsshipping/PDF/etiqueta_".$rowCarrier['codigo_envio'].".pdf")) {}
						// Comprobacmos que exista el fichero PDF del envío, si existe mostramos por pantalla la infomración del mismo al Cliente
						if (file_exists(_PS_ROOT_DIR_."/modules/glsshipping/PDF/etiqueta_".$rowCarrier['id_envio_order'].".pdf")) {
                            //$path_download_pdf = $path_myroot."/modules/glsshipping/PDF/etiqueta_".$rowCarrier['id_envio_order'].".pdf";
							$adminToken =  Tools::getAdminToken('AdminGlsshipping'.intval(Tab::getIdFromClassName('AdminGlsshipping')).intval($cookie->id_employee));
                    		$path_download_pdf = 'index.php?tab=AdminGlsshipping&ids_order_envio='.$rowCarrier['id_envio_order'].'&option=mergetags&token='.$adminToken;
                            $path_download_html = 'https://www.asmred.com/Extranet/public/ecmLabel.aspx?codbarras='. $rowCarrier['codigo_envio'] . '&uid=' .Configuration::get('GLS_GUID');

                            $smarty->assign('gls_lopeta', $this->displayName.' - Etiquetas de envío para clientes');
                            $smarty->assign('gls_version', $this->description.' v. '.$this->version);
                            $smarty->assign('gls_state','1');
                            $smarty->assign('gls_pdf_down', $path_download_pdf);
                            $smarty->assign('gls_html_down', $path_download_html);
                            $smarty->assign('gls_pedido', $id_order);
                            $smarty->assign('referencia', $orderTemp->reference);
                            $smarty->assign('gls_n_envio', $rowCarrier['id_envio_order']);
                            $smarty->assign('gls_codigo_envio', $rowCarrier['codigo_envio']);
							$smarty->assign('num_albaran', $rowCarrier['num_albaran']);
                            $smarty->assign('gls_download', $this->l('Pulse aqui descargar'));
                            $smarty->assign('gls_pdf_txt', $this->l('Etiqueta de transporte (PDF)'));
                            $smarty->assign('gls_html_txt', $this->l('Etiqueta de transporte (ventana nueva)'));
                            $smarty->assign('gls_seguimiento_envio', $this->l('Realizar el seguimiento del envío'));
                            $smarty->assign('gls_seguimiento_envio_url', $rowCarrier['url_track']);
                            $smarty->assign('gls_state','3');

							 $datosCarrier = Db::getInstance()->ExecuteS(
							 'SELECT c.name, c.id_reference, o.id_carrier FROM '._DB_PREFIX_.'orders AS o
							JOIN '._DB_PREFIX_.'carrier AS c
							WHERE o.id_order = "'.$id_order.'"
							AND (c.id_carrier=o.id_carrier OR c.id_reference=o.id_carrier)' );
								if ($datosCarrier[0]['name'] == 'GLS - Eurobusiness Parcel'
									|| Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP') == $datosCarrier[0]['id_carrier']
									|| Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP') == $datosCarrier[0]['id_reference']
									){
									$isEurobusinessparcel = true;
								}

							
                            $smarty->assign('isEurobusinessparcel',$isEurobusinessparcel);

                            return $this->display(__FILE__, 'views/templates/hook/adminOKOrder.tpl');
						} else {
                            $smarty->assign('mensaje',$this->l('El pedido no tiene registros de etiquetas de envío.'));
                            $smarty->assign('gls_lopeta', $this->displayName.' - Etiquetas de envío para clientes');
                            $smarty->assign('gls_version', $this->description.' v. '.$this->version);
                            $smarty->assign('gls_state','1');


							
                            return $this->display(__FILE__, 'views/templates/hook/adminErrorOrder.tpl');
						}
					} else {
                        $smarty->assign('mensaje',$this->l('El pedido no dispone de etiquetas de envío'));
                        $smarty->assign('bultos_message',$this->l('Bultos'));
                        $smarty->assign('bultos_input_txt', $this->l('Indique el número de bultos'));
                        $smarty->assign('bultos_btn', $this->l('Enviar a GLS'));

                        if(version_compare(_PS_VERSION_, '1.5', '>')) {
                            $smarty->assign('bultos_controller',Tools::getValue('controller'));
                        } else {
                            $smarty->assign('bultos_controller',Tools::getValue('tab'));
                        }

						
						$products = $orderTemp->getProducts(); 
						$order_num_articulos = 0;
						foreach ($products as $product){
							$order_num_articulos += (int)$product['product_quantity'];
						}
						
						$gls_bultos = Configuration::get('GLS_BULTOS');
						$bultos = 1;
						if ($gls_bultos == 0){
							$bultos = Configuration::get('GLS_NUM_FIJO_BULTOS');
						} else {
							$gls_num_articulos = Configuration::get('GLS_NUM_ARTICULOS');
							if (empty($gls_num_articulos)) $gls_num_articulos = 1;
							$bultos = ceil($order_num_articulos/$gls_num_articulos);
						}
						if ($bultos == 0)$bultos = 1;
						
						$smarty->assign('gls_bultos',$bultos);
						
						$peso = Configuration::get('GLS_DEF_PESO');

						if (empty($peso)){
							foreach ($products as $producto){
								$peso += floatval($producto['product_quantity'] * $producto['product_weight']);
							}
							if($peso < 1){
								$peso = 1;
							}
						}
						
                        $smarty->assign('bultos_id_order',Tools::getValue('id_order'));
                        $smarty->assign('bultos_regenerar','1');
                        $smarty->assign('bultos_token', Tools::getValue('token'));
                        $smarty->assign('bultos_info_b', $this->l('Si desea emplear la <strong>configuración que ha predefinido</strong> en el módulo de GLS <strong>no modifique los campos.</strong> <br> <br> <strong>Eurobusiness Parcel</strong> sólo permite envíos <strong>monobulto, sin retorno, sin reembolso y sin RCS.</strong>'));
                        $smarty->assign('bultos_info', $this->l('Puede <strong>cambiar los bultos por expedición, el departamento origen, el valor asegurado y los parámetros Retorno y RCS</strong> en el mismo momento de generar la etiqueta; <strong>Indique los valores para este pedido</strong> en los campos correspondientes.'));
                        $smarty->assign('gls_lopeta', $this->displayName.' - Etiquetas de envío para clientes');
                        $smarty->assign('gls_version', $this->description.' v. '.$this->version);
                        $smarty->assign('gls_state','2');
						$smarty->assign('gls_incoterm',Configuration::get('GLS_INCOTERM'));
						$smarty->assign('gls_retorno',Configuration::get('GLS_RETORNO'));
						$smarty->assign('gls_rcs',Configuration::get('GLS_RCS'));
						$smarty->assign('gls_dorig',Configuration::get('GLS_DORIG'));
						$smarty->assign('gls_vsec',Configuration::get('GLS_VSEC'));
						$smarty->assign('gls_def_peso',$peso);
						$datosCarrier = Db::getInstance()->ExecuteS(
						 'SELECT c.name,c.id_reference, o.id_carrier FROM '._DB_PREFIX_.'orders AS o
						JOIN '._DB_PREFIX_.'carrier AS c
						WHERE o.id_order = "'.$id_order.'"
						AND (c.id_carrier=o.id_carrier OR c.id_reference=o.id_carrier)');
							if ( in_array($datosCarrier[0]['id_carrier'], explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP')))
								|| in_array($datosCarrier[0]['id_reference'], explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP')))
								){
								$isEurobusinessparcel = true;
							}

						
						$smarty->assign('isEurobusinessparcel',$isEurobusinessparcel);
                        return $this->display(__FILE__, 'views/templates/hook/adminOrder.tpl');
					}
				} else {
                        $smarty->assign('mensaje',$this->l('Para generar una etiqueta debe cambiar el estado de su pedido.'));
                        $smarty->assign('bultos_info', $this->l('El caso más frecuente es que el pedido este pendiente la aprobación del pago debido a métodos de pago como transferencias bancarias, etc.'));
                        $smarty->assign('gls_lopeta', $this->displayName.' - Etiquetas de envío para clientes');
                        $smarty->assign('gls_version', $this->description.' v. '.$this->version);
                        $smarty->assign('gls_state','1');

                        return $this->display(__FILE__, 'views/templates/hook/adminNoOrder.tpl');

			    } // GLS Carrier (if)
            }
			} else { // regenerar 1
				if($valida) {
					// Inicializamos envíos GLS
					$this->inicializarGlsEnvios(Tools::getValue('id_order'));
					// Generamos etiquetas y enviamos email al Cliente con su código de seguimiento
                    $_SESSION["ultimoErrorGLS"] = "";


					$this->imprimirEtiquetas(Tools::getValue('id_order'));

					$query = 'SELECT id_envio_order, codigo_barras, codigo_envio, url_track FROM '._DB_PREFIX_.'gls_envios where id_envio_order = '.Tools::getValue('id_order');
					$gls_track_value = Db::getInstance()->ExecuteS($query);

					$gls_track_value = $gls_track_value[0];
					$path_myroot   = _PS_BASE_URL_.__PS_BASE_URI__;
					
					
                    if($_SESSION["ultimoErrorGLS"] ==  "" && !empty($gls_track_value['codigo_barras'])) {
						require_once('lib/PDFMerger.php');
						$pdf = new PDFMerger;

						$pdf->addPDF($gls_track_value['codigo_barras'], 'all');
						$timestamp = time();
						if (Configuration::get('GLS_APLI4') == 0){
							$pdf->merge('file', _PS_MODULE_DIR_.'glsshipping/PDF/GLS_'.$timestamp.'.pdf');
						} else {
							$pdf->mergeApli4('file', _PS_MODULE_DIR_.'glsshipping/PDF/GLS_'.$timestamp.'.pdf');
						}
						$adminToken =  Tools::getAdminToken('AdminGlsshipping'.intval(Tab::getIdFromClassName('AdminGlsshipping')).intval($cookie->id_employee));
						$path_download_pdf = 'index.php?tab=AdminGlsshipping&ids_order_envio='.$gls_track_value['id_envio_order'].'&option=mergetags&token='.$adminToken;
						$orderTemp = new Order(Tools::getValue('id_order'));


                        $smarty->assign('gls_lopeta', $this->displayName.' - Etiquetas de envío para clientes');
                        $smarty->assign('gls_version', $this->description.' v. '.$this->version);
                        $smarty->assign('gls_state','4');
                        $smarty->assign('gls_pdf_down', $path_download_pdf);
                        $smarty->assign('gls_html_down', $path_download_html);
                        $smarty->assign('gls_pedido', Tools::getValue('id_order'));
                        $smarty->assign('referencia', $orderTemp->reference);
                        $smarty->assign('gls_n_envio', $gls_track_value['id_envio_order']);
                        $smarty->assign('gls_codigo_envio', $gls_track_value['codigo_envio']);
                        $smarty->assign('gls_download', $this->l('Pulse aqui descargar'));
                        $smarty->assign('gls_pdf_txt', $this->l('Etiqueta de transporte (PDF)'));
                        $smarty->assign('gls_html_txt', $this->l('Etiqueta de transporte (ventana nueva)'));
                        $smarty->assign('gls_seguimiento_envio', $this->l('Realizar el seguimiento del envío'));
                        $smarty->assign('gls_seguimiento_envio_url', $gls_track_value['url_track']);
                        $smarty->assign('gls_success_msg', $this->l('Se ha generado la etiqueta con éxito'));
                        $smarty->assign('testval', print_r($gls_track_value,true));

						$datosCarrier = Db::getInstance()->ExecuteS(
						 'SELECT c.name, o.id_carrier FROM '._DB_PREFIX_.'orders AS o
						JOIN '._DB_PREFIX_.'carrier AS c
						WHERE o.id_order = "'.Tools::getValue('id_order').'"
						AND c.id_carrier=o.id_carrier');
				   
						if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP') == $datosCarrier[0]['id_carrier']){
							$isEurobusinessparcel = true;
						}
						
						$smarty->assign('isEurobusinessparcel',$isEurobusinessparcel);
						

                        return $this->display(__FILE__, 'views/templates/hook/adminOKOrder.tpl');

                    } else {
                        $smarty->assign('mensaje', $_SESSION["ultimoErrorGLS"]);
                        $smarty->assign('gls_lopeta', $this->displayName.' - Etiquetas de envío para clientes');
                        $smarty->assign('gls_version', $this->description.' v. '.$this->version);
                        $smarty->assign('gls_state','1');

                        return $this->display(__FILE__, 'views/templates/hook/adminErrorOrder.tpl');
                    }
				} else {
                    $smarty->assign('mensaje',$this->l('Para generar una etiqueta debe cambiar el estado de su pedido.'));
                    $smarty->assign('bultos_info', $this->l('El caso más frecuente es que el pedido este pendiente la aprobación del pago debido a métodos de pago como transferencias bancarias, etc.'));
                    $smarty->assign('gls_lopeta', $this->displayName.' - Etiquetas de envío para clientes');
                    $smarty->assign('gls_version', $this->description.' v. '.$this->version);
                    $smarty->assign('gls_state','1');

                    return $this->display(__FILE__, 'views/templates/hook/adminNoOrder.tpl');
				}
			}
	}

	
	function actualizaEstadoEnviosGLS(){
		
		$hour = date('H');

		$dateCollect = Configuration::get('GLS_DATE_UPDATE');
		if ($dateCollect+1800 > time()){
			return;
		}
		Configuration::updateValue('GLS_DATE_UPDATE', time());
		
//		$this->logger->logInfo((int)$hour);

	
		
		$pedidosNoModule = '';
		if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS10') != '') $pedidosNoModule .= 'OR c.id_reference IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS10')).') OR c.id_carrier IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS10')).') ';
		if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS14') != '') $pedidosNoModule .= 'OR c.id_reference IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS14')).') OR c.id_carrier IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS14')).') ';
		if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS24') != '') $pedidosNoModule .= 'OR c.id_reference IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS24')).') OR c.id_carrier IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS24')).') ';
		if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSECO') != '') $pedidosNoModule .= 'OR c.id_reference IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSECO')).') OR c.id_carrier IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSECO')).') ';
		if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP') != '') $pedidosNoModule .= 'OR c.id_reference IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP')).') OR c.id_carrier IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP')).') ';
		if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL') != '') $pedidosNoModule .= 'OR c.id_reference IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL')).') OR c.id_carrier IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL')).') ';

		$completedStatus = Configuration::get('GLS_STATUS_COMPLETED');
		if (empty($completedStatus)) $completedStatus = 9999;
		
		// obtenemos todos los pedidos relacionados con GLS
		$pedidos = Db::getInstance()->ExecuteS('SELECT o.id_order,o.reference,o.module,o.total_paid_real,o.valid,o.date_add,c.name,e.*,
		   u.firstname,u.lastname, c.id_reference, c.id_carrier as idcarrier FROM '._DB_PREFIX_.'orders o
		   JOIN '._DB_PREFIX_.'carrier c ON (c.id_reference = o.id_carrier OR c.id_carrier = o.id_carrier)
		   JOIN '._DB_PREFIX_.'gls_envios e ON e.id_envio_order = o.id_order
		   JOIN '._DB_PREFIX_.'customer u ON u.id_customer = o.id_customer
		   WHERE (c.external_module_name = "glsshipping" '.$pedidosNoModule.')
		   AND e.fecha BETWEEN (CURDATE() - INTERVAL 1 MONTH ) AND (CURDATE() + INTERVAL 1 DAY) 
		   AND e.codigo_envio != "" 
		   AND o.current_state NOT IN ('.$completedStatus.') 
		   GROUP BY o.id_order ORDER BY o.id_order DESC');
		   
		$uidCliente = Configuration::get('GLS_GUID');
		$URL = "https://wsclientes.asmred.com/b2b.asmx?op=GetExpCli";


		foreach ($pedidos as $pedido){
			
			if (empty($pedido['codigo_envio'])) continue;
			
			$envioStatus = $this->getEnvioStatus($pedido['id_envio']);
			$oOrder = new Order($pedido['id_order']);
			$orderStatus = $oOrder->current_state;
			
			$XML= '<?xml version="1.0" encoding="utf-8"?>
		<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
		  <soap12:Body>
			<GetExpCli xmlns="http://www.asmred.com/">
			  <codigo>'.$pedido['codigo_envio'].'</codigo>
			  <uid>'.$uidCliente.'</uid>
			</GetExpCli>
		  </soap12:Body>
		</soap12:Envelope>';

			$this->logger->logInfo($XML);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
			curl_setopt($ch, CURLOPT_URL, $URL );
			curl_setopt($ch, CURLOPT_POSTFIELDS, $XML );
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml; charset=UTF-8"));

			$postResult = curl_exec($ch);
			
			$this->logger->logInfo($postResult);
			 
			$xml = simplexml_load_string($postResult, NULL, NULL, "http://www.w3.org/2003/05/soap-envelope");
			if (empty($xml)) continue;
			$xml->registerXPathNamespace('asm', 'http://www.asmred.com/');
			$arr = $xml->xpath("//asm:GetExpCliResponse/asm:GetExpCliResult");
			if (empty($arr)) continue;
			$ret = $arr[0]->xpath("//expediciones/exp");
			if (empty($ret)) continue;

			$return = $ret[0]->xpath("//expediciones/exp/codestado");
			if (empty($return)) continue;
			$newStatus = (int)$return[0];
			
			
			$tracking_list = $ret[0]->xpath("//expediciones/exp/tracking_list/tracking");
			$stateHistory = array();
			foreach($tracking_list as $titem){
				$tipo = (string)$titem->tipo[0];
				$codigo = (string)$titem->codigo[0];
				$evento = (string)$titem->evento[0];
				$date = (string)$titem->fecha[0];
				if (!in_array($tipo, array('ESTADO', 'INCIDENCIA'))) continue;
				
				$tmpDate = DateTime::createFromFormat('d/m/Y H:i:s', $date);
				$key = $tmpDate->getTimestamp();				
				
				/*$tmpDate1 = explode(' ',$date);
				$tmpDate2 = explode('/',$tmpDate1[0]);
				$tmpDate3 = explode(':',$tmpDate1[1]);
				$key = $tmpDate2[2].$tmpDate2[1].$tmpDate2[0].$tmpDate3[0].$tmpDate3[1].$tmpDate3[2];*/
//				$key = str_replace(array('/',' ',':'),array('','',''),$date);
				
				while (isset($stateHistory[$key])){
					$key +=1;
				}
				$stateHistory[$key]=array(
					'date' => $date,
					'type' => $tipo,
					'code' => $codigo,
					'text' => $evento
				);
			}
//			$this->logger->logInfo($stateHistory);
			ksort($stateHistory);
			$stateHistory = array_values($stateHistory);
			
			while (count($stateHistory) > 10){
				array_shift($stateHistory);
			}
			
			if ($newStatus != $envioStatus){
				$this->setEnvioStatus($pedido['id_envio'],$newStatus, json_encode($stateHistory));
			}
			
			if ($newStatus == -10) continue;
			
			if ($newStatus == 7){
				if (Configuration::get('GLS_UPDATE_ORDER_STATUS_COMPLETED') == 1){
					$id_order_state = Configuration::get('GLS_STATUS_COMPLETED');
					if ($id_order_state == $orderStatus) continue;
					$history = new OrderHistory(); 
					$history->id_order = (int)$pedido['id_order'];
					$history->changeIdOrderState($id_order_state, (int)$pedido['id_order'],true);
					$history->id_order_state = $id_order_state;
					$history->add(true);
				}
			} else {
			//if ((int)$return[0] == 5){
				if (Configuration::get('GLS_UPDATE_ORDER_STATUS_FAILED') == 1){
					$id_order_state = Configuration::get('GLS_STATUS_FAILED');
					if ($id_order_state == $orderStatus) continue;
					$history = new OrderHistory(); 
					$history->id_order = (int)$pedido['id_order'];
					$history->changeIdOrderState($id_order_state, (int)$pedido['id_order'],true);
					$history->id_order_state = $id_order_state;
					$history->add(true);
				} 
			}
		}
	}
	
	
	function getEnvioStatus($idEnvio){
		$pedido = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'gls_envios WHERE id_envio='.$idEnvio.'');
		return $pedido[0]['status'];
	}	

	function setEnvioStatus($idEnvio, $state, $history){
		if (Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'gls_envios SET current_state='.$state.", state_history='".$history."' WHERE id_envio=".$idEnvio.'')){
			return true;
		} else {
			return false;
		}
	}
	
	
	
    /*
	** Función que permite comprobar si el transporte pertenece a GLS
	**
    */

	function isCarrierGLS($id_order) {

		$stateTrans = Configuration::get('MYCARRIER1_CARRIER_ID');
		$stateTransA = Configuration::get('MYCARRIER2_CARRIER_ID');
		$stateTransB = Configuration::get('MYCARRIER3_CARRIER_ID');
		$stateTransC = Configuration::get('MYCARRIER4_CARRIER_ID');
		$stateTransD = Configuration::get('MYCARRIER5_CARRIER_ID');
		$stateTransE = Configuration::get('MYCARRIER6_CARRIER_ID');
		$arrST = explode(',',$stateTrans);
		$arrSTA = explode(',',$stateTransA);
		$arrSTB = explode(',',$stateTransB);
		$arrSTC = explode(',',$stateTransC);
		$arrSTD = explode(',',$stateTransD);
		$arrSTE = explode(',',$stateTransE);

		$query = 'SELECT id_carrier FROM  '._DB_PREFIX_.'orders WHERE  id_order = '.$id_order;
		$rowCarrier = Db::getInstance()->getRow($query);
		$oCarrier = new Carrier($rowCarrier['id_carrier']);
		$idCarrier = !empty($oCarrier->id_reference)?$oCarrier->id_reference:$rowCarrier['id_carrier'];
		if(in_array($oCarrier->id_reference, $arrST)
			|| in_array($idCarrier, $arrSTA)
			|| in_array($idCarrier, $arrSTB)
			|| in_array($idCarrier, $arrSTC)
			|| in_array($idCarrier, $arrSTD)
			|| in_array($idCarrier, $arrSTE)) {
			return true;
		}
		else { return false; }
	}

    function isCarrierGLSNonModule ($id_carrier) {
//            return true;
		//die($id_carrier);
		$oCarrier = new Carrier($id_carrier);
		$idCarrier = $id_carrier;
		$idReference = !empty($oCarrier->id_reference)?$oCarrier->id_reference:$id_carrier;

//var_dump($id_carrier);		
		
        if (
            in_array($idCarrier,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS10'))) || 
			in_array($idCarrier,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS14'))) || 
			in_array($idCarrier,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS24'))) || 
			in_array($idCarrier,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSECO'))) || 
			in_array($idCarrier,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP'))) ||
			in_array($idCarrier,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL'))) ||
			in_array($idReference,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS10'))) || 
			in_array($idReference,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS14'))) || 
			in_array($idReference,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS24'))) || 
			in_array($idReference,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSECO'))) || 
			in_array($idReference,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP'))) ||
			in_array($idReference,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL')))
			) {
            return true;
        } else {
            return false;
        }
    }
	
	function isCarrierGLSParcel ($id_carrier) {
		$oCarrier = new Carrier($id_carrier);
		$idCarrier = $id_carrier;
		$idReference = !empty($oCarrier->id_reference)?$oCarrier->id_reference:$id_carrier;
        if (
			in_array($idCarrier,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL'))) ||
			in_array($idReference,explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL')))
			) {
            return true;
        } else {
            return false;
        }
    }

    function isGLSModule () {
        return true;
    }
	
	
	
	function checkCurrentVersion(){
		
		$url = 'https://resources.gls-spain.es/PrestaShop/Update/current.txt';
		$context = stream_context_create( array(
		  'http'=>array(
			'timeout' => 1.0
		  )
		));
		$fp = fopen($url, 'r', false, $context);
		if ( !$fp ) {
		  return false;
		}
		else {
			$currVer = stream_get_contents($fp);
		
			if (version_compare($currVer, $this->version, '>')) {
				$this->adminDisplayWarning($this->l('Hay una nueva versión del módulo disponible: '). '   <a target="_blank" class="btn btn-default" href="https://resources.gls-spain.es/PrestaShop/Version/glsshipping_v'.$currVer.'.zip">'.$this->l('DESCARGAR VERSIÓN').' '.$currVer.'</a>   <a class="btn btn-default" href="index.php?controller=AdminModules&configure=glsshipping&module_name=glsshipping&upgrade_version='.$currVer.'&token='.Tools::getValue('token').'">'.$this->l('INSTALAR VERSIÓN').' '.$currVer.'</a>');
				
			}
			return true;
		}
	}
	
	
	function upgradeVersion($version){
		
		
		$source = "https://resources.gls-spain.es/PrestaShop/Version/glsshipping_v".$version.".zip";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $source);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_SSLVERSION,3);
		$data = curl_exec ($ch);
		$error = curl_error($ch); 
		curl_close ($ch);
		//var_dump($data);die;
		
		$destination = _PS_MODULE_DIR_.'/glsshipping/tmp/glsshipping_v'.$version.'.zip';
		$file = fopen($destination, "w+");
		fputs($file, $data);
		fclose($file);
		 
		$zip = new ZipArchive;
		if ($zip->open($destination) === TRUE) {
			$zip->extractTo(_PS_MODULE_DIR_.'/glsshipping/');
			$zip->close();
			//echo 'ok';
			Module::initUpgradeModule('glsshipping');
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules',true, array(), array('configure'=>'glsshipping','module_name'=>'glsshipping'))); 
		} else {
			//echo 'failed';
		} 
		
	}
	
	
	
	
    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name || Tools::getValue('configure') == $this->name) {
           // $this->context->controller->addJS($this->_path.'views/js/back.js');
           $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookDisplayHeader($params)
    {
       // $this->context->controller->addJS($this->_path.'/views/js/front.js');
       // $this->context->controller->addCSS($this->_path.'/views/css/front.css');
		if (!($file = basename(Tools::getValue('controller')))) {
            $file = str_replace('.php', '', basename($_SERVER['SCRIPT_NAME']));
        }
      if (in_array($file, array('order-opc', 'order', 'orderopc', 'history', 'supercheckout', 'amzpayments'))) {
			if (!empty(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL'))){
				if (version_compare(_PS_VERSION_, '1.7', '>')) {	
					$this->context->controller->addJS($this->_path.'/views/js/front/selectPickup.js');
				} else {
					$this->context->controller->addJS($this->_path.'/views/js/front/selectPickup16.js');
				}
			}
		}
    }

    public function hookUpdateCarrier($params)
    {
        /**
         * Not needed since 1.5
         * You can identify the carrier by the id_reference
        */
    }

    public function hookActionCarrierUpdate()
    {
        /* Place your code here. */
    }

    public function hookDisplayCarrierList($params){
		return $this->hookDisplayCarrierExtraContent($params);
    }
	
	public function hookExtraCarrier($params){
		 return $this->hookDisplayCarrierExtraContent($params);
    }
    public function hookDisplayCarrierExtraContent($params)
    {
		   global $smarty;
		   //print_r($params['carrier']['id_reference']);
		   //print_r(explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL')));
			if (version_compare(_PS_VERSION_, '1.7', '>')) {		   
				$selectedCarrier = $params['carrier']['id_reference'];
			} else {
				$tmpCarrier = new Carrier($params['cart']->id_carrier);
				$selectedCarrier = $tmpCarrier->id_reference;
			}
		   
		   
		   $this->logger->logInfo(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL'));
			$address = new Address(intval($params['cart']->id_address_delivery));
			
			if (in_array($selectedCarrier, explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL')))){ 
				$smarty->assign('postalcode',$address->postcode);
				$validCarrier = Carrier::getCarrierByReference($selectedCarrier);
				$smarty->assign('parcelshopid',$validCarrier->id);
	
				
				$query = 'SELECT * FROM '._DB_PREFIX_.'gls_parcels WHERE id_cart='.$params['cart']->id;
				$rowParcel = Db::getInstance()->getRow($query);
				if (!empty($rowParcel['codigo'])){
					$smarty->assign('parcelcodigo',$rowParcel['codigo']);
					$smarty->assign('parcelnombre',$rowParcel['nombre']);
					$smarty->assign('parceldireccion',$rowParcel['direccion']);
					$smarty->assign('parcelcp',$rowParcel['cp']);
					$smarty->assign('parcellocalidad',$rowParcel['localidad']);
				} else {
					$smarty->assign('parcelcodigo','');
					$smarty->assign('parcelnombre','');
					$smarty->assign('parceldireccion','');
					$smarty->assign('parcelcp','');
					$smarty->assign('parcellocalidad','');
				}
				if (version_compare(_PS_VERSION_, '1.7', '>')) {
					return $this->display(__FILE__, 'views/templates/front/selectPickup.tpl');
				}else{
		   	    
					return $this->display(__FILE__, 'views/templates/front/selectPickup16.tpl');
				}
			}
    }





	public function hookActionValidateStepComplete($params)
	{
		if ($params['step_name'] != 'delivery') {
			return;
		}

		if (!$this->isCarrierGLSParcel($params['cart']->id_carrier)) {
			return;
		}
 		if (empty($params['request_params']['parcel']['codigo']) || $params['request_params']['parcel']['codigo'] == '[object Object]') {
			$controller           = $this->context->controller;
			$controller->errors[] = $this->l('Debe seleccionar un punto de recogida.');
			$params['completed']  = false;
		}/*  else {
			Db::getInstance()->insert('gls_parcels', array(
				'id_cart'       => $params['cart']->id,
				'codigo'   => $params['request_params']['parcel']['codigo'],
				'nombre'   => $params['request_params']['parcel']['nombre'],
				'direccion'   => $params['request_params']['parcel']['direccion'],
				'cp'   => $params['request_params']['parcel']['cp'],
				'localidad'   => $params['request_params']['parcel']['localidad'],
			));
		}	 */
	}
}
