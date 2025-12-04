<?php
/**
* 2019-2020 GLS
*
* NOTICE OF LICENSE
*
*  @author    GLS
*  @copyright 2019-2020 YDRAL.COM
*  @license   GNU General Public License version 2
*
* You can not resell or redistribute this software.
*/
include_once(_PS_MODULE_DIR_.'glsshipping/glsshipping.php');


class AdminGlsshippingController extends ModuleAdminController
{
	public function __construct()   {
        $this->bootstrap = true;
        parent::__construct();
    }
	
	public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme = false);
        $this->addCSS(array(_MODULE_DIR_ .'glsshipping/views/css/back.css'));
        $this->addJS(array(_MODULE_DIR_ .'glsshipping/views/js/jQuery.print.js'));
		$this->addJS(array(_MODULE_DIR_ .'glsshipping/views/js/tableExport.js'));
		$this->addJS(array(_MODULE_DIR_ .'glsshipping/views/js/jquery.base64.js'));
		$this->addJS(array(_MODULE_DIR_ .'glsshipping/views/js/jspdf/libs/sprintf.js'));
		$this->addJS(array(_MODULE_DIR_ .'glsshipping/views/js/jspdf/jspdf.js'));
		$this->addJS(array(_MODULE_DIR_ .'glsshipping/views/js/jspdf/jspdf.plugin.addimage.js'));
		$this->addJS(array(_MODULE_DIR_ .'glsshipping/views/js/jspdf/libs/base64.js'));
    }
	
    public function createTemplate($tpl_name) {
		//die($this->getTemplatePath() . $tpl_name);
        if (file_exists($this->getTemplatePath() . $tpl_name) && $this->viewAccess())
                return $this->context->smarty->createTemplate($this->getTemplatePath() . $tpl_name, $this->context->smarty);
            //return parent::createTemplate($tpl_name);
    }

    public function initContent(){
        parent::initContent();
		
        global $cookie;

        $manejador = new glsshipping();

        $option = '';
        $id_order_envio = '';

        if(isset($_GET['option'])) {
           $option = $_GET['option'];
        }

        if(isset($_GET['id_order_envio'])) {
            $id_order_envio = $_GET['id_order_envio'];
        }
        if(isset($_GET['ids_order_envio'])) {
            $ids_order_envio = $_GET['ids_order_envio'];
        }     

        switch($option) {
            case 'etiqueta':
                $manejador->imprimirEtiquetas($id_order_envio, true);
				$this->template = 'OrdersTable.tpl';
					$this->pedidosTabla();
            break;
             case 'reetiqueta':
                $manejador->reimprimirEtiquetas($id_order_envio, true);
				$this->template = 'TagGLS.tpl';
				//$this->pedidosTabla();
            break;
			case 'etiquetabulk':
                $manejador->imprimirEtiquetasMasivo($ids_order_envio);
				$this->template = 'OrdersTable.tpl';
				$this->pedidosTabla();
            break;
			case 'mergetags':
				$manejador->imprimirEtiquetasMasivo($ids_order_envio);
            $manejador->mergeTags($ids_order_envio);
				$this->template = 'mergeTags.tpl';
            break;
            case 'cancelar':
                $manejador->cancelarEnvio($id_order_envio);
            break;
            case 'envio':
                $manejador->enviarEmailTrack($id_order_envio);
            break;
            case 'callcollect':
               $this->collect();
					$this->template = 'OrdersTable.tpl';
					$this->pedidosTabla();
            break;
            default:
				$this->pedidosTabla();
				$this->template = 'OrdersTable.tpl';
            break;
        } 
		/* $tpl = $this->createTemplate('content.tpl')->fetch();
        $this->context->smarty->assign('posts', $posts); */
		//$this->renderView();
    }
	/* function renderView(){
		$this->display(_PS_MODULE_DIR_.'glsshipping', 'views/templates/admin/OrdersTable.tpl');
	} */
	/* public function display(){
		parent::display();
	} */
	function pedidosTabla(){
	    global $cookie;
		
		$smarty = $this->context->smarty;
		
		$id_shop = (int)$this->context->shop->id;

	    // pasamos el token a la vista
	    $smarty->assign('tokenOrder', Tools::getAdminToken('AdminOrders'.(int)Tab::getIdFromClassName('AdminOrders').(int)$cookie->id_employee));
	    // preparamos el paginador
	    $countQuery = Db::getInstance()->ExecuteS('SELECT COUNT(o.id_order) AS allCmd FROM '._DB_PREFIX_.'orders o JOIN '._DB_PREFIX_.'carrier c ON c.id_carrier = o.id_carrier WHERE c.external_module_name = "glsshipping"');
		// Paginacion
        require_once(_PS_MODULE_DIR_.'glsshipping/lib/paginator.class.2.php');

        $paginas= new Paginator;
        $paginas->items_total = $countQuery[0]['allCmd']; // items total
        //$paginas->items_per_page = 3;
        $paginas->mid_range = 10; // numero de enlaces

        $paginas->paginate();
        $paginacion_items_x_pag = $paginas->display_items_per_page();
        $paginacion_menu = $paginas->display_jump_menu();
        $paginacion = $paginas->display_pages(); // obtenemos la paginacion

        $smarty->assign('paginacion', $paginacion);
        $smarty->assign('paginacion_items_x_pag', $paginacion_items_x_pag);
        $smarty->assign('paginacion_menu', $paginacion_menu);
		
		$selestado = Tools::getValue('selestado');
		$pedidosEstado = '';
		if ($selestado > 0){
			$pedidosEstado = ' AND o.current_state=' . $selestado . ' ';
		}
		
		$dateFrom = Tools::getValue('date_from');
		$dateTo = Tools::getValue('date_to');
		if (empty($dateFrom)) $dateFromSQL = '0000-00-00';
		else $dateFromSQL = $dateFrom;
		if (empty($dateTo)) $dateToSQL = '2099-01-01';
		else $dateToSQL = $dateTo;

		$manejador = new glsshipping();
		
	    // primero inicializamos la tabla de gls envios
	    $manejador->inicializarGlsEnvios(null, $paginas->limit);

        $pedidosNoModule = '';

        if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS10') != '') $pedidosNoModule .= 'OR c.id_reference IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS10')).') OR c.id_carrier IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS10')).') ';
        if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS14') != '') $pedidosNoModule .= 'OR c.id_reference IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS14')).') OR c.id_carrier IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS14')).') ';
        if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS24') != '') $pedidosNoModule .= 'OR c.id_reference IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS24')).') OR c.id_carrier IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLS24')).') ';
        if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSECO') != '') $pedidosNoModule .= 'OR c.id_reference IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSECO')).') OR c.id_carrier IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSECO')).') ';
        if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP') != '') $pedidosNoModule .= 'OR c.id_reference IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP')).') OR c.id_carrier IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP')).') ';
        if(Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL') != '') $pedidosNoModule .= 'OR c.id_reference IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL')).') OR c.id_carrier IN ('.str_replace(';',',',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSPARCEL')).') ';
	    // obtenemos todos los pedidos relacionados con GLS
	    $pedidos = Db::getInstance()->ExecuteS('SELECT o.id_order,o.reference,o.module,o.total_paid_real,o.valid,o.date_add,o.current_state as ostate,c.name,e.*,
	       u.firstname,u.lastname, c.id_reference, c.id_carrier as idcarrier FROM '._DB_PREFIX_.'orders o
	       JOIN '._DB_PREFIX_.'carrier c ON (c.id_reference = o.id_carrier OR c.id_carrier = o.id_carrier)
	       JOIN '._DB_PREFIX_.'gls_envios e ON e.id_envio_order = o.id_order
	       JOIN '._DB_PREFIX_.'customer u ON u.id_customer = o.id_customer
	       WHERE o.id_shop="'.$id_shop.'" AND (c.external_module_name = "glsshipping" '.$pedidosNoModule.')
		   AND o.date_add BETWEEN "'.$dateFromSQL.' 00:00:00" AND "'.$dateToSQL.' 23:59:59" 
		   ' . $pedidosEstado . '
	       GROUP BY o.id_order ORDER BY o.id_order DESC '.$paginas->limit);

		 $estados = Db::getInstance()->ExecuteS('SELECT *
            FROM `'._DB_PREFIX_.'order_state` os
            LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = 1)
            WHERE deleted = 0
            ORDER BY `name` ASC');
		 
	    // creamos los diferentes enlaces para la vista
		$kestados =array();
		foreach ($estados as $estado){
			$kestados[$estado['id_order_state']] = $estado['name'];
		}

	    $pedidos2 = array();
	    $i=0;
	    foreach ($pedidos as $pedido){

	       if($pedido['valid']){
	           $pedidos[$i]['link_etiqueta'] = 'index.php?tab=AdminGlsshipping&ids_order_envio='.$pedido['id_envio_order'].'&option=mergetags&token='.Tools::getValue('token');
	           if($pedido['codigo_envio']){
	               $pedidos[$i]['link_cancelar'] = 'index.php?tab=AdminGlsshipping&id_order_envio='.$pedido['id_envio_order'].'&option=cancelar&token='.Tools::getValue('token');
	               $pedidos[$i]['link_envio_mail'] = 'index.php?tab=AdminGlsshipping&id_order_envio='.$pedido['id_envio_order'].'&option=envio&token='.Tools::getValue('token');
	           }
	           else{
	               $pedidos[$i]['link_cancelar'] = '';
	               $pedidos[$i]['link_envio_mail'] = '';
	           }
	       }
	       else{
	           $pedidos[$i]['link_etiqueta'] = '';
	           $pedidos[$i]['link_cancelar'] = '';
	       }
	        $pedidos[$i]['num_pedido'] = sprintf('%06d', $pedido['id_order']);
            $pedidos[$i]['referencia'] = $pedido['reference'];
			$pedidos[$i]['state_history'] = '';
			if (!empty($pedido['state_history'])){
				$state_history = json_decode($pedido['state_history'],true);
				foreach ($state_history as $state){
					$pedidos[$i]['state_history'] .= $state['date'].' - '.$state['text']."\r\n";
				}
			}
			
			$pedidos[$i]['estado'] = $kestados[$pedido['ostate']];
			
			$orderTemp = new Order($pedido['id_order']);
			
			$products = $orderTemp->getProducts(); 
			$order_num_articulos = 0;
			foreach ($products as $product){
				$order_num_articulos += (int)$product['product_quantity'];
			}
			
			if (empty($pedido['bultos'])){
				$gls_bultos = Configuration::get('GLS_BULTOS');
				$bultos = 1;
				if ($gls_bultos == 0){
					$bultos = Configuration::get('GLS_NUM_FIJO_BULTOS');
				} else {
					$gls_num_articulos = Configuration::get('GLS_NUM_ARTICULOS');
					if (empty($gls_num_articulos)) $gls_num_articulos = 1;
					$bultos = ceil($order_num_articulos/$gls_num_articulos);
				}
				if ($bultos == 0) $bultos = 1;
				
				$pedidos[$i]['bultos'] = $bultos;
			}
			
			if (empty($pedido['peso']) || $pedido['peso'] == 0.00){
				$gls_peso_def = Configuration::get('GLS_DEF_PESO');
				
				if (!empty($gls_peso_def)){
					$peso = str_replace(',','.',$gls_peso_def);
				} else {
					$peso = 0;
					foreach ($products as $producto){
						$peso += floatval($producto['product_quantity'] * $producto['product_weight']);
					}
					if($peso < 1){
						$peso = 1;
					}
				}
				
				$pedidos[$i]['peso'] = $peso;
			}
			$pedidos[$i]['iseuro'] = false;
			$arrEBP = explode(';',Configuration::get('GLS_SERVICIO_SELECCIONADO_GLSEBP'));
			if(!empty($arrEBP) && ((!empty($pedido['id_reference']) && in_array($pedido['id_reference'],$arrEBP))
				|| (!empty($pedido['id_carrier']) && in_array($pedido['id_carrier'],$arrEBP))
				)){
			   $pedidos[$i]['iseuro'] = true;
		   }
	       $i++;
	    }



		$activeTab = Tools::getValue('tab');
		$dateStart = Tools::getValue('date_0');
		$dateEnd = Tools::getValue('date_1');
		$grouped = Tools::getValue('grouped',0);
		$mpedidos = array();
		if (!empty($activeTab) && $activeTab=='Manifest') {
			if (empty($dateStart)) $dateStartSQL = '0000-00-00';
			else $dateStartSQL = $dateStart;
			if (empty($dateEnd)) $dateEndSQL = '2099-01-01';
			else $dateEndSQL = $dateEnd;
			$mpedidos = Db::getInstance()->ExecuteS('SELECT o.id_order,o.reference,o.module,FORMAT(o.total_paid_real,2) as total_paid_real,o.valid,o.date_add,c.name,e.*,
			   a.address1,a.address2,a.postcode,a.phone_mobile,a.city,a.phone,
			   u.firstname,u.lastname FROM '._DB_PREFIX_.'orders o
			   JOIN '._DB_PREFIX_.'carrier c ON (c.id_reference = o.id_carrier OR c.id_carrier = o.id_carrier)
			   JOIN '._DB_PREFIX_.'gls_envios e ON e.id_envio_order = o.id_order
			   JOIN '._DB_PREFIX_.'customer u ON u.id_customer = o.id_customer
			   JOIN '._DB_PREFIX_.'address a ON a.id_address = o.id_address_delivery
			   WHERE o.valid = 1 AND o.id_shop="'.$id_shop.'" AND (c.external_module_name = "glsshipping" '.$pedidosNoModule.')
			   AND e.fecha BETWEEN "'.$dateStartSQL.' 00:00:00" AND "'.$dateEndSQL.' 23:59:59" AND e.codigo_envio != ""
			   GROUP BY o.id_order ORDER BY o.id_order DESC ');
		}
		$totalbultos = 0;
		$mtotal = 0;
		$mcount = 0;
		if ($grouped){
			foreach($mpedidos as $mp){
				$mtotal += $mp['total_paid_real'];
				$totalbultos += $mp['bultos'];
			}
			$mtotal = number_format($mtotal, 2);
			$mcount = count($mpedidos);
		}

			$validCollect = false;
			$diffCollect = 0;
			$dateCollect = Configuration::get('date_collect');
			
			$dayCollect = date('Ymd', $dateCollect);
			$today = date('Ymd');
			if ($dayCollect < $today){
				$validCollect = true;			
			} else {
				$diffCollect = $dateCollect+86400;			
			}
	    // preparamos los path de los iconos
	    $smarty->assign('validCollect', $validCollect);
	    $smarty->assign('dateCollect', $dateCollect);
  		$smarty->assign('diffCollect', $diffCollect);
	    $smarty->assign('module_base', _PS_BASE_URL_.__PS_BASE_URI__.'modules/glsshipping/');
	    $smarty->assign('path_img_logo', _PS_BASE_URL_.__PS_BASE_URI__.'modules/glsshipping/img/logo_gls.png');
	    $smarty->assign('path_img_track', _PS_BASE_URL_.__PS_BASE_URI__.'modules/glsshipping/img/track.gif');
	    $smarty->assign('path_img_email', _PS_BASE_URL_.__PS_BASE_URI__.'modules/glsshipping/img/email.gif');
	    $smarty->assign('path_img_cod_barras', _PS_BASE_URL_.__PS_BASE_URI__.'modules/glsshipping/img/cod_barras.gif');
	    $smarty->assign('path_img_cancelar', _PS_BASE_URL_.__PS_BASE_URI__.'modules/glsshipping/img/cancelar.gif');
	    $smarty->assign('token', Tools::getValue('token'));
	    $smarty->assign('activetab', $activeTab);
	    $smarty->assign('date_0', $dateStart);
	    $smarty->assign('date_1', $dateEnd);
        $smarty->assign('pedidos', $pedidos);
        $smarty->assign('estados', $estados);
        $smarty->assign('selestado', $selestado);
        $smarty->assign('mpedidos', $mpedidos);
        $smarty->assign('grouped', $grouped);
        $smarty->assign('mtotal', $mtotal);
        $smarty->assign('mcount', $mcount);
        $smarty->assign('totalbultos', $totalbultos);
	    $smarty->assign('today',date('l jS \of F Y'));
	    $smarty->assign('date_from',$dateFrom);
	    $smarty->assign('date_to',$dateTo);
	    $smarty->assign('errores',false);
	    $smarty->assign('gls_version','3.2.6');
	    $smarty->assign('gls_incoterm',Configuration::get('GLS_INCOTERM'));
	    $smarty->assign('gls_retorno',Configuration::get('GLS_RETORNO'));
		$smarty->assign('gls_rcs',Configuration::get('GLS_RCS'));
		$smarty->assign('gls_dorig',Configuration::get('GLS_DORIG'));
		$smarty->assign('gls_vsec',Configuration::get('GLS_VSEC'));

/* 		$smarty->assign('gls_bultos',Configuration::get('GLS_FIJO_BULTOS'));
		$smarty->assign('gls_peso',Configuration::get('GLS_DEF_PESO'));
 */
		$smarty->assign('pagerTemplate', _PS_MODULE_DIR_.'glsshipping/views/templates/admin/pagerTemplate.tpl');
		/* $tpl = $this->createTemplate('OrdersTable.tpl')->fetch();
		return $tpl; */
    }
    
    function collect(){
    	
    		$manejador = new glsshipping();
    		
    		Configuration::updateValue('date_collect', mktime());
    		
    		$URL = 'https://wsclientes.asmred.com/b2b.asmx';
         $uidCliente = Configuration::get('GLS_GUID');
            
    		$XML = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:asm="http://www.asmred.com/">
   <soap:Header/>
   <soap:Body>
      <asm:EnviaCorreoAgencia>
         <asm:uid>'.$uidCliente.'</asm:uid>
      </asm:EnviaCorreoAgencia>
   </soap:Body>
</soap:Envelope> ';

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

			$manejador->logger->logInfo($XML);
			$manejador->logger->logInfo($postResult);



    }
}