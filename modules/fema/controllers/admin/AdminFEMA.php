<?php
/**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    FEMA S.A.S. <support.ecommerce@dpd.fr>
 * @copyright 2019 FEMA S.A.S.
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class AdminFEMAController extends ModuleAdminController
{
    public $identifier = 'FEMA';


    public function __construct()
    {
        $this->name = 'FEMA';
        $this->bootstrap = true;
        $this->display = 'view';
        $this->meta_title = 'FEMA Delivery Management';

        parent::__construct();

        if (!$this->module->active) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminHome'));
        }
    }


    /* Converts country ISO code to FEMA Station format */
    public static function getIsoCodebyIdCountry($idcountry)
    {
        $sql='
            SELECT `iso_code`
            FROM `'._DB_PREFIX_.'country`
            WHERE `id_country` = \''.pSQL($idcountry).'\'';
        $result=Db::getInstance('_PS_USE_SQL_SLAVE_')->getRow($sql);
        $isops=array('DE', 'AD', 'AT', 'BE', 'BA', 'BG', 'HR', 'DK', 'ES', 'EE', 'FI', 'FR', 'GB', 'GR', 'GG', 'HU', 'IM', 'IE', 'IT', 'JE', 'LV', 'LI', 'LT', 'LU', 'MC', 'NO', 'NL', 'PL', 'PT', 'CZ', 'RO', 'RS', 'SK', 'SI', 'SE', 'CH');
        $isoep=array('D', 'AND', 'A', 'B', 'BA', 'BG', 'CRO', 'DK', 'E', 'EST', 'SF', 'F', 'GB', 'GR', 'GG', 'H', 'IM', 'IRL', 'I', 'JE', 'LET', 'LIE', 'LIT', 'L', 'F', 'N', 'NL', 'PL', 'P', 'CZ', 'RO', 'RS', 'SK', 'SLO', 'S', 'CH');
        if (in_array($result['iso_code'], $isops)) {
            // If the ISO code is in Europe, then convert it to FEMA Station format
            $code_iso=str_replace($isops, $isoep, $result['iso_code']);
        } else {
            // If not, then it will be 'INT' (intercontinental)
            $code_iso=str_replace($result['iso_code'], 'INT', $result['iso_code']);
        }
        return $code_iso;
    }


    //Show orders with cash on delivery,paymment accepted, in preparation and shipped
    public static function orderStatusSelect(){
        $selected = "O.`current_state` IN(2,3,4, 13)";

        return $selected;
    }


    /*
    * Esta função recebe o campo payment e tenta idenficar nessa string se o pagamento será á cobrança através das keywords do array $arr
    */
    public static function identifyCOD($str){
        $str = strtoupper($str);

        $arr = array("COD","COD+", "CASH ON DELIVERY");

        foreach($arr as $a) {
            if (stripos($str,$a) !== false) 
                return true;
        }

        return false;
    }


    public static function autoUpdateTracking(){

        //WebService de trackings
        $ws_tracking_url = 'https://services.fema.pt/tracking.asmx?WSDL';

        $client = new SoapClient($ws_tracking_url);

        //Ir buscar todas as encomendas no estado pagamento aceite(2), em preparacao(3) e enviada(4) para atualizar o estado
        $sql='SELECT    O.`id_order` AS id_order,
                        FO.`order_waybill` AS waybill
                        FROM '._DB_PREFIX_.'orders AS O LEFT JOIN '._DB_PREFIX_.'fema_orders AS FO ON O.id_order = FO.id_order,
						'._DB_PREFIX_.'carrier AS CA
                        WHERE CA.id_carrier=O.id_carrier AND
                        FO.order_waybill IS NOT NULL AND
						O.current_state IN (2,3,4)
						ORDER BY id_order DESC';

        
        $orderlist=Db::getInstance()->ExecuteS($sql);
        
        if (!empty($orderlist)) {

            foreach ($orderlist as $orders) {
                $id_order=$orders['id_order'];
            
                $params = array('Cliente' => Configuration::get('FEMA_CLASSIC_USERNAME'),
                                'AWB' => $orders['waybill']                              
                                );  

                $result = $client->TrackingAWB($params);

                if(strcmp($result->TrackingAWBResult->State,'Ok')==0){
                    $count = count($result->TrackingAWBResult->Estados->TrackingState);

                    if($count == 1){
                        $estado = $result->TrackingAWBResult->Estados->TrackingState->Estado;
                    }
                    else{
                        $estado = $result->TrackingAWBResult->Estados->TrackingState['0']->Estado;
                    }

                    if(strcmp($estado,'Espera Levantamento')==0 || strcmp($estado,'Aberta')==0){
                        $state = 3;
                        Db::getInstance()->execute('UPDATE ' . _DB_PREFIX_ . 'orders 
                        SET current_state = "' .  pSQL($state) . '" WHERE id_order = "' . (int)$id_order . '"');
                    }
                    else if(strcmp($estado,'Em Trânsito')==0 || strcmp($estado,'Recolhida')==0 || strcmp($estado,'Verificada')==0 || strcmp($estado,'Exportada')==0 || strcmp($estado,'Em Distribuição')==0){
                        $state = 4;
                        Db::getInstance()->execute('UPDATE ' . _DB_PREFIX_ . 'orders 
                        SET current_state = "' .  pSQL($state) . '" WHERE id_order = "' . (int)$id_order . '"');
                    }
                    else if(strcmp($estado,'Entregue')==0){
                        $state = 5;
                        Db::getInstance()->execute('UPDATE ' . _DB_PREFIX_ . 'orders 
                        SET current_state = "' .  pSQL($state) . '" WHERE id_order = "' . (int)$id_order . '"');
                    }

                }
                
            }
        }

    }


    public static function getOrderInfo($orderlist, $statuses_array){
        $order_info=array();
        foreach ($orderlist as $order_var) {
            $order = new Order($order_var['id_order']);
            $address_delivery = new Address($order->id_address_delivery, (int)Context::getContext()->language->id);
            $current_state_name = $statuses_array[$order->current_state];
            $internalref = $order->reference;
            $weight = number_format($order->getTotalWeight(), 2, '.', '.');
            $amount = number_format($order->total_paid, 2, '.', '.').' €';
            $type = 'Classic Export<img src="../modules/fema/views/img/admin/service_world.png" title="Classic Export"/>';
            $address = '<a class="popup" href="http://maps.google.com/maps?f=q&hl=fr&geocode=&q='.str_replace(' ', '+', $address_delivery->address1).','.str_replace(' ', '+', $address_delivery->postcode).'+'.str_replace(' ', '+', $address_delivery->city).'&output=embed" target="_blank">'.($address_delivery->company ? $address_delivery->company.'<br/>' : '').$address_delivery->address1.'<br/>'.$address_delivery->postcode.' '.$address_delivery->city.'</a>';             
            
            if($order->getTotalWeight()==null && $order_var['order_weight']!=null){
                $weight= number_format($order_var['order_weight'], 2, '.', '.');
            }
                     
            $order_info[] = array(
                'id'                    => $order->id,
                'reference'             => $internalref,
                'date'                  => date('d/m/Y H:i:s', strtotime($order->date_add)),
                'nom'                   => $order_var['customer_firstname'].' '.$order_var['customer_lastname'],
                'recipient'                   => $order_var['firstname'].' '.$order_var['lastname'],
                'type'                  => $type,
                'address'               => $address,
                'poids'                 => $weight,
                'weightunit'            => Configuration::get('PS_WEIGHT_UNIT', null, null, (int)$order->id_shop),
                'prix'                  => $amount,
                'statut'                => $current_state_name,
                'n_volumes' => $order_var['order_volumes'],
                'fema_service' => $order_var['name'],
                'service' => $order_var['name'],
                'waybill' => $order_var['order_waybill'],
            );

            
        
        }

        return $order_info;
    }


    /* Get eligible orders and builds up display */
    public function renderView()
    {
        $this->fields_form[]['form'] = array();
        $helper = $this->buildHelper();
        $msg = '';
        // RSS stream
        $stream=array();

        //Chamada á função que faz o update do tracking
        self::autoUpdateTracking();

        //WebService de bookings
        $ws_expedicoes_url = 'https://testesservices.fema.pt/expedicoes.asmx?WSDL';

        //WebService de trackings
        $ws_tracking_url = 'https://services.fema.pt/tracking.asmx?WSDL';

        if (Tools::getIsset('WithoutWaybill')) {
            $search_word = $_POST['tableFilter'];
            $order_info = array();
            $statuses_array = array();
            $statuses = OrderState::getOrderStates((int)Context::getContext()->language->id);
    
            foreach ($statuses as $status) {
                $statuses_array[$status['id_order_state']] = $status['name'];
            }
            $fieldlist = array('O.`id_order`', 'O.`id_cart`', 'AD.`lastname`', 'AD.`firstname`', 'AD.`postcode`', 'AD.`city`', 'CL.`iso_code`', 'C.`email`', 'CA.`name`');
    
            $selected = self::orderStatusSelect();

            $sql = 'SELECT  '.implode(', ', $fieldlist).',
                                C.`firstname` AS customer_firstname, 
                                C.`lastname` AS customer_lastname,
                                FO.`order_waybill` AS order_waybill, 
                                FO.`order_volumes` AS order_volumes,
                                FO.`order_weight` AS order_weight,
                                FO.`order_service` AS fema_service
                    FROM    '._DB_PREFIX_.'orders AS O LEFT JOIN '._DB_PREFIX_.'fema_orders AS FO ON O.id_order = FO.id_order, 
                            '._DB_PREFIX_.'carrier AS CA, 
                            '._DB_PREFIX_.'customer AS C, 
                            '._DB_PREFIX_.'address AS AD, 
                            '._DB_PREFIX_.'country AS CL
                    WHERE   O.id_address_delivery=AD.id_address AND
                            C.id_customer=O.id_customer AND 
                            CL.id_country=AD.id_country AND 
                            CA.id_carrier=O.id_carrier AND
                            CA.name LIKE "Fema%" AND 
                            ' . $selected . ' AND 
                            FO.`order_waybill` IS NULL
                    ORDER BY id_order DESC';

            $orderlist = Db::getInstance()->ExecuteS($sql);

            if (!empty($orderlist)) {
                $order_info = self::getOrderInfo($orderlist, $statuses_array);
            } 
            else {
                $order_info['error'] = true;
            }

    
            // Assign smarty variables and fetches template
            Context::getContext()->smarty->assign(array(
                'msg'           => $msg,
                'stream'        => $stream,
                'token'         => $this->token,
                'order_info'    => $order_info,
            ));
    
            return $helper->generateForm($this->fields_form);;

        }

        if (Tools::getIsset('WithWaybill')) {
            $search_word = $_POST['tableFilter'];
            $order_info = array();
            $statuses_array = array();
            $statuses = OrderState::getOrderStates((int)Context::getContext()->language->id);
    
            foreach ($statuses as $status) {
                $statuses_array[$status['id_order_state']] = $status['name'];
            }
            $fieldlist = array('O.`id_order`', 'O.`id_cart`', 'AD.`lastname`', 'AD.`firstname`', 'AD.`postcode`', 'AD.`city`', 'CL.`iso_code`', 'C.`email`', 'CA.`name`');
            
            $selected = self::orderStatusSelect();

            $sql = 'SELECT  '.implode(', ', $fieldlist).',
                        C.`firstname` AS customer_firstname, 
                        C.`lastname` AS customer_lastname,
                        FO.`order_waybill` AS order_waybill, 
                        FO.`order_volumes` AS order_volumes,
                        FO.`order_weight` AS order_weight,
                        FO.`order_service` AS fema_service
            FROM    '._DB_PREFIX_.'orders AS O LEFT JOIN '._DB_PREFIX_.'fema_orders AS FO ON O.id_order = FO.id_order, 
                    '._DB_PREFIX_.'carrier AS CA, 
                    '._DB_PREFIX_.'customer AS C, 
                    '._DB_PREFIX_.'address AS AD, 
                    '._DB_PREFIX_.'country AS CL
            WHERE   O.id_address_delivery=AD.id_address AND
                    C.id_customer=O.id_customer AND 
                    CL.id_country=AD.id_country AND 
                    CA.id_carrier=O.id_carrier AND
                    CA.name LIKE "Fema%" AND 
                    ' . $selected . ' AND 
                    FO.`order_waybill` IS NOT NULL
            ORDER BY id_order DESC';

            $orderlist = Db::getInstance()->ExecuteS($sql);

            if (!empty($orderlist)) {
                $order_info = self::getOrderInfo($orderlist, $statuses_array);
            } 
            else {
                $order_info['error'] = true;
            }
    
            // Assign smarty variables and fetches template
            Context::getContext()->smarty->assign(array(
                'msg'           => $msg,
                'stream'        => $stream,
                'token'         => $this->token,
                'order_info'    => $order_info,
            ));
    
            return $helper->generateForm($this->fields_form);;

        }

        if (Tools::getIsset('Search')) {
			$search_word = $_POST['tableFilter'];
            $order_info = array();
            $statuses_array = array();
            $statuses = OrderState::getOrderStates((int)Context::getContext()->language->id);
    
            foreach ($statuses as $status) {
                $statuses_array[$status['id_order_state']] = $status['name'];
            }
            $fieldlist = array('O.`id_order`', 'O.`id_cart`', 'AD.`lastname`', 'AD.`firstname`', 'AD.`postcode`', 'AD.`city`', 'CL.`iso_code`', 'C.`email`', 'CA.`name`');
    
            $selected = self::orderStatusSelect();

            $sql = 'SELECT  '.implode(', ', $fieldlist).',
                            C.`firstname` AS customer_firstname, 
                            C.`lastname` AS customer_lastname,
                            FO.`order_waybill` AS order_waybill, 
                            FO.`order_volumes` AS order_volumes,
                            FO.`order_weight` AS order_weight,
                            FO.`order_service` AS fema_service
                    FROM    '._DB_PREFIX_.'orders AS O LEFT JOIN '._DB_PREFIX_.'fema_orders AS FO ON O.id_order = FO.id_order, 
                            '._DB_PREFIX_.'carrier AS CA, 
                            '._DB_PREFIX_.'customer AS C, 
                            '._DB_PREFIX_.'address AS AD, 
                            '._DB_PREFIX_.'country AS CL
                    WHERE   O.id_address_delivery=AD.id_address AND
                            C.id_customer=O.id_customer AND 
                            CL.id_country=AD.id_country AND 
                            CA.id_carrier=O.id_carrier AND
                            CA.name LIKE "Fema%" AND 
                            ' . $selected . ' AND  
                            (O.reference LIKE "%' . $search_word . '%" OR C.firstname LIKE "%' . $search_word . '%" OR C.lastname LIKE "%' . $search_word . '%" 
                            OR AD.firstname LIKE "%' . $search_word . '%" OR AD.lastname LIKE "%' . $search_word . '%"  OR FO.order_waybill LIKE "' . $search_word . '" )
                    ORDER BY id_order DESC';

            $orderlist = Db::getInstance()->ExecuteS($sql);

            if (!empty($orderlist)) {
                $order_info = self::getOrderInfo($orderlist, $statuses_array);
            } 
            else {
                $order_info['error'] = true;
            }
         
    
            // Assign smarty variables and fetches template
            Context::getContext()->smarty->assign(array(
                'msg'           => $msg,
                'stream'        => $stream,
                'token'         => $this->token,
                'order_info'    => $order_info,
            ));
    
            return $helper->generateForm($this->fields_form);
        }


        if (Tools::getIsset('ClearSearch')) {
			$search_word = $_POST['tableFilter'];
            $order_info = array();
            $statuses_array = array();
            $statuses = OrderState::getOrderStates((int)Context::getContext()->language->id);
    
            foreach ($statuses as $status) {
                $statuses_array[$status['id_order_state']] = $status['name'];
            }
            $fieldlist = array('O.`id_order`', 'O.`id_cart`', 'AD.`lastname`', 'AD.`firstname`', 'AD.`postcode`', 'AD.`city`', 'CL.`iso_code`', 'C.`email`', 'CA.`name`');
    
            $selected = self::orderStatusSelect();

            $sql = 'SELECT  '.implode(', ', $fieldlist).',
                            C.`firstname` AS customer_firstname, 
                            C.`lastname` AS customer_lastname,
                            O.`current_state` AS current_state,
                            FO.`order_waybill` AS order_waybill,
                            FO.`order_weight` AS order_weight,
                            FO.`order_volumes` AS order_volumes
                    FROM    '._DB_PREFIX_.'orders AS O LEFT JOIN '._DB_PREFIX_.'fema_orders AS FO ON O.id_order = FO.id_order, 
                            '._DB_PREFIX_.'carrier AS CA, 
                            '._DB_PREFIX_.'customer AS C, 
                            '._DB_PREFIX_.'address AS AD, 
                            '._DB_PREFIX_.'country AS CL
                    WHERE   O.id_address_delivery=AD.id_address AND
                            C.id_customer=O.id_customer AND 
                            CL.id_country=AD.id_country AND 
                            CA.id_carrier=O.id_carrier AND
                            CA.name LIKE "Fema%" AND 
                            ' . $selected . ' AND  
                            CA.name LIKE "Fema%"
                    ORDER BY id_order DESC';

            $orderlist = Db::getInstance()->ExecuteS($sql);

            if (!empty($orderlist)) {
                $order_info = self::getOrderInfo($orderlist, $statuses_array);
            } 
            else {
                $order_info['error'] = true;
            }
  
            // Assign smarty variables and fetches template
            Context::getContext()->smarty->assign(array(
                'msg'           => $msg,
                'stream'        => $stream,
                'token'         => $this->token,
                'order_info'    => $order_info,
            ));
    
            return $helper->generateForm($this->fields_form);
        }


        if (Tools::getIsset('updateTracking')) {
            $orders=Tools::getValue('checkbox');

            if (is_string($orders)) {
                $orders = explode(',', $orders);
            }

            $sql='SELECT    O.`id_order` AS id_order,
                            FO.`order_waybill` AS waybill
                          FROM  '._DB_PREFIX_.'orders AS O LEFT JOIN '._DB_PREFIX_.'fema_orders AS FO ON O.id_order = FO.id_order
                          WHERE  FO.id_order=O.id_order AND O.id_order IN ('.implode(',', array_map('intval', $orders)).')';
            

            $orderlist=Db::getInstance()->ExecuteS($sql);
            
            if (!empty($orderlist)) {

                $client = new SoapClient($ws_tracking_url);

                foreach ($orderlist as $orders) {
                    $id_order=$orders['id_order'];

                    $params = array('Cliente' => Configuration::get('FEMA_CLASSIC_USERNAME'),
                                    'AWB' => $orders['waybill']                              
                                    );  

                    $result = $client->TrackingAWB($params);

                    if(strcmp($result->TrackingAWBResult->State,'Ok')==0){
                        $estado = $result->TrackingAWBResult->Estados->TrackingState->Estado;
    
                        if(strcmp($estado,'Espera Levantamento')==0 || strcmp($estado,'Aberta')==0){
                            $state = 3;
                            Db::getInstance()->execute('UPDATE ' . _DB_PREFIX_ . 'orders 
                            SET current_state = "' .  pSQL($state) . '" WHERE id_order = "' . (int)$id_order . '"');
                        }
                        else if(strcmp($estado,'Em Trânsito')==0 || strcmp($estado,'Recolhida')==0 || strcmp($estado,'Verificada')==0 || strcmp($estado,'Exportada')==0 || strcmp($estado,'Em Distribuição')==0){
                            $state = 4;
                            Db::getInstance()->execute('UPDATE ' . _DB_PREFIX_ . 'orders 
                            SET current_state = "' .  pSQL($state) . '" WHERE id_order = "' . (int)$id_order . '"');
                        }
                        else if(strcmp($estado,'Entregue')==0){
                            $state = 5;
                            Db::getInstance()->execute('UPDATE ' . _DB_PREFIX_ . 'orders 
                            SET current_state = "' .  pSQL($state) . '" WHERE id_order = "' . (int)$id_order . '"');
                        }
                    }
                    

                }
            }
        }

 


        
        if (Tools::getIsset('createBooking')) {
            if (Tools::getIsset('checkbox')) {
                $orders=Tools::getValue('checkbox');
                if (is_string($orders)) {
                    $orders = explode(',', $orders);
                }

                if (!empty($orders)) {  

                    $sql='SELECT    O.`id_order` AS id_order,
                                    O.`reference` AS reference,
                                    O.`date_add` AS eventOn,
                                    O.`payment` AS payment,
                                    O.`total_paid` AS total_amount,
                                    C.`firstname` AS firstname,
                                    C.`lastname` AS lastname,
                                    C.`email` AS email,
                                    AD.`firstname` AS recipient_firstname,
                                    AD.`lastname` AS recipient_lastname, 
                                    AD.`postcode` AS postalCode,
                                    AD.`address1` AS address1,
                                    AD.`address2` AS address2,
                                    AD.`company` AS company,
                                    AD.`city` AS city,
                                    AD.`phone` AS phone,
                                    AD.`phone_mobile` AS phone_mobile,
                                    CL.`iso_code` AS CL,
                                    CA.`name` AS carrier_service
                          FROM      '._DB_PREFIX_.'orders AS O,
                                    '._DB_PREFIX_.'carrier AS CA,
                                    '._DB_PREFIX_.'customer AS C,
                                    '._DB_PREFIX_.'address AS AD,
                                    '._DB_PREFIX_.'country AS CL
                          WHERE     CA.id_carrier=O.id_carrier AND 
                                    O.id_customer=C.id_customer AND
                                    C.id_customer=O.id_customer AND 
                                    O.id_address_delivery=AD.id_address AND
                                    CL.id_country=AD.id_country AND
                                    id_order IN ('.implode(',', array_map('intval', $orders)).')';

                    $orderlist=Db::getInstance()->ExecuteS($sql);                  


                    if (!empty($orderlist)) {

                        $client_username = Configuration::get('FEMA_CLASSIC_USERNAME');
                        $client_password = Configuration::get('FEMA_CLASSIC_PASSWORD');

                        foreach ($orderlist as $orders) {
                            $id_order=$orders['id_order'];

                            $sql2 = 'SELECT M.`message` AS message, 
                                            M.`id_order` AS id_order,
                                            M.`date_add` AS date_add,
                                            M.`private` AS m_private 
                                    FROM '._DB_PREFIX_.'message AS M WHERE M.id_order = "' . (int)$id_order . '" AND M.private = 0
                                    ORDER BY date_add ASC';

                            $messagelist = Db::getInstance()->ExecuteS($sql2);
							

                            if($messagelist != null && count($messagelist)>0 ){
                                //Campo das obs, ir buscar a mensagem mais antiga
                                $observacoes = $messagelist[0]['message'];
                            }
                            else{
                                $observacoes="";
                            }
							

                           $order_weight = floatval(Tools::getValue('parcelweight')[$id_order]);
                           $order_volumes = (int) Tools::getValue('parcelVolume')[$id_order];
                           $order_temp = $orders['carrier_service'];
                           $order_service = substr($order_temp, 5, strlen($order_temp));
                           $loadingUnits = array();

                           for ($i = 0; $i < $order_volumes; $i++) {
                                array_push($loadingUnits, 
                                            [
                                             "weight" => $order_weight / $order_volumes,
                                             "volume" => 0,
                                             "loadingUnitType" => "EU PX"   
                                            ]);
                            }


                            //Em desenvolvimento
                            $cod=0;

                            if($this->identifyCOD($orders['payment'])){
                                $cod=$orders['total_amount'];
                            }


                            $client = new SoapClient($ws_expedicoes_url);

                            $params = array( 'Pedido' => array( 'Expedicao' => array(
                                                                                    'Referencia' => $orders['reference'],
                                                                                    'Servico' => $order_service,
                                                                                    'MoradaRemetente' => array(
                                                                                        'Nome' => Configuration::get('FEMA_NOM_EXP'),
                                                                                        'Contacto' => Configuration::get('FEMA_NOM_EXP'),
                                                                                        'Telefone' => Configuration::get('TEL_EXP'),
                                                                                        'Telemovel' => ' ',
                                                                                        'Morada1' => Configuration::get('FEMA_ADDRESS_EXP'),
                                                                                        'Morada2' => Configuration::get('FEMA_ADDRESS2_EXP'),
                                                                                        'CodigoPostal' => Configuration::get('FEMA_CP_EXP'),
                                                                                        'Cidade' => Configuration::get('FEMA_VILLE_EXP'),
                                                                                        'Pais' => "PT",
                                                                                        'Estado' => ' ',
                                                                                        'Email' => Configuration::get('FEMA_EMAIL_EXP')
                                                                                    ),
                                                                                    'MoradaDestinatario' => array(
                                                                                        'Nome' => $orders['recipient_firstname'].' '.$orders['recipient_lastname'],
                                                                                        'Contacto' => $orders['company'],
                                                                                        'Telefone' => $orders['phone'],
                                                                                        'Telemovel' => $orders['phone_mobile'],
                                                                                        'Morada1' => $orders['address1'],
                                                                                        'Morada2' => $orders['address2'],
                                                                                        'CodigoPostal' => $orders['postalCode'],
                                                                                        'Cidade' => $orders['city'],
                                                                                        'Pais' => $orders['CL'],
                                                                                        'Estado' => ' ',
                                                                                        'Email' => $orders['email']
                                                                                    ),
                                                                                    'COD' => $cod,
                                                                                    'DAC' => 0,
                                                                                    'Observacoes' => $observacoes,
                                                                                    'TipoPacote' => 'caixa',
                                                                                    'DescricaoVolumes' => ' ',
                                                                                    'NCaixas' => $order_volumes,
                                                                                    'PesoTotal' => $order_weight,
                                                                                    'Comprimento' => Configuration::get('FEMA_CLASSIC_LENGTH'),
                                                                                    'Largura' => Configuration::get('FEMA_CLASSIC_WIDTH'),
                                                                                    'Altura' => Configuration::get('FEMA_CLASSIC_HEIGHT'),
                                                                                    ),
                                                                                    'Etiqueta' => array(
                                                                                        'Formato' => 'A6'
                                                                                    ),
                                                                                    'Utilizador' => array(
                                                                                        'Username' => $client_username,
                                                                                        'Password' => $client_password,
                                                                                    )
                                                                    )
                                            );

                            $result = $client->Criar($params);
                                                     
                            if(strcmp($result->CriarResult->State,'Ok')==0){
                                
                                $waybillNumber = $result->CriarResult->Numero; 
                                					
								$msg = '<div class="okmsg">'.$this->l('Booking created successfully').'</div>';

                                $insertData = array(
                                    'id_order'  => (int)$id_order, 
                                    'order_waybill'  => $waybillNumber,
                                    'order_volumes' => $order_volumes,
                                    'order_weight' => $order_weight,
                                    'order_service' => $order_service    
                                );

                            
                                Db::getInstance()->insert(_DB_PREFIX_ . 'fema_orders', $insertData, true, false, Db::REPLACE, false);
								$getInsertedId = Db::getInstance()->Insert_ID();


                                Db::getInstance()->execute('UPDATE ' . _DB_PREFIX_ . 'orders 
                                            SET delivery_number ="'. pSQL($waybillNumber) .'" WHERE id_order = "' . (int)$id_order . '"');

                                Db::getInstance()->execute('UPDATE ' . _DB_PREFIX_ . 'order_carrier 
                                            SET tracking_number ="'. pSQL($waybillNumber) .'" WHERE id_order = "' . (int)$id_order . '"');

							}
							else{
								$msg = '<div class="warnmsg">'.$this->l($result->CriarResult->Message).'</div>';
							}
        
                        }

                    }
                } 
                else {
                    $msg = '<div class="warnmsg">'.$this->l('No order selected.').'</div>';
                }
            } 
            else {
                    $msg = '<div class="warnmsg">'.$this->l('No order selected.').'</div>';
            }
        }

        

        if (Tools::getIsset('printLabelsA4')) {

            $client_username = Configuration::get('FEMA_CLASSIC_USERNAME');
            $client_password = Configuration::get('FEMA_CLASSIC_PASSWORD');

            if (Tools::getIsset('checkbox')) {
                $orders=Tools::getValue('checkbox');
                if (is_string($orders)) {
                    $orders = explode(',', $orders);
                }

                if (!empty($orders)) {
                    $sql='SELECT    O.`id_order` AS id_order,
                                    FO.`order_waybill` AS waybill,
                                    O.`reference` AS reference
                          FROM      '._DB_PREFIX_.'orders AS O LEFT JOIN '._DB_PREFIX_.'fema_orders AS FO ON O.id_order = FO.id_order
                          WHERE     O.id_order IN ('.implode(',', array_map('intval', $orders)).')';
                    $orderlist=Db::getInstance()->ExecuteS($sql);

                    if (!empty($orderlist)) {

                        $array_waybills = array();
                        foreach ($orderlist as $order) {
                            $element = $order['waybill'];
                            array_push($array_waybills, $element );
                        }

                    
                        $client = new SoapClient($ws_expedicoes_url);

                        $params = array( 'Etiqueta' => array(
                                                            'Numero' => $array_waybills,
                                                            'Etiqueta' => array(
                                                                'Formato' => 'A4'
                                                            ),
                                                            'Utilizador' => array(
                                                                'Username' => $client_username,
                                                                'Password' => $client_password,
                                                            )
                                                        )
                                        );  

                        $result = $client->MultiplasEtiquetas($params);

                        $data = base64_decode($result->MultiplasEtiquetasResult->pdf_base64);
                        $f = "pdf"."_A4".'.pdf';
                        file_put_contents( $f,$data);
                        header("Content-Disposition:attachment;filename=".$array_waybills[0]. "_" .$array_waybills[count($array_waybills)-1] ."_A4".".pdf");
                        readfile( $f);


                    } else {
                        $msg = '<div class="warnmsg">'.$this->l('Error generating Fema labels.').'</div>';
                    }
                } else {
                    $msg = '<div class="warnmsg">'.$this->l('No order selected.').'</div>';
                }
            } else {
                    $msg = '<div class="warnmsg">'.$this->l('No order selected.').'</div>';
            }
        }

        if (Tools::getIsset('printLabelsA6')) {

            $client_username = Configuration::get('FEMA_CLASSIC_USERNAME');
            $client_password = Configuration::get('FEMA_CLASSIC_PASSWORD');

            if (Tools::getIsset('checkbox')) {
                $orders=Tools::getValue('checkbox');
                if (is_string($orders)) {
                    $orders = explode(',', $orders);
                }

                if (!empty($orders)) {
                    $sql='SELECT    O.`id_order` AS id_order,
                                    FO.`order_waybill` AS waybill,
                                    O.`reference` AS reference
                          FROM      '._DB_PREFIX_.'orders AS O LEFT JOIN '._DB_PREFIX_.'fema_orders AS FO ON O.id_order = FO.id_order
                          WHERE      O.id_order IN ('.implode(',', array_map('intval', $orders)).')';
                    $orderlist=Db::getInstance()->ExecuteS($sql);
                    if (!empty($orderlist)) {

                        $array_waybills = array();
                        foreach ($orderlist as $order) {
                            $element = $order['waybill'];
                            array_push($array_waybills, $element );
                        }

                    
                        $client = new SoapClient($ws_expedicoes_url);

                        $params = array( 'Etiqueta' => array(
                                                            'Numero' => $array_waybills,
                                                            'Etiqueta' => array(
                                                                'Formato' => 'A6'
                                                            ),
                                                            'Utilizador' => array(
                                                                'Username' => $client_username,
                                                                'Password' => $client_password,
                                                            )
                                                        )
                                        );  

                        $result = $client->MultiplasEtiquetas($params);

                        $data = base64_decode($result->MultiplasEtiquetasResult->pdf_base64);
                        $f = "pdf"."_A6".'.pdf';
                        file_put_contents( $f,$data);
                        header("Content-Disposition:attachment;filename=".$array_waybills[0]. "_" .$array_waybills[count($array_waybills)-1] ."_A6".".pdf");
                        readfile( $f);                        

                    } else {
                        $msg = '<div class="warnmsg">'.$this->l('Error generating Fema labels.').'</div>';
                    }
                } else {
                    $msg = '<div class="warnmsg">'.$this->l('No order selected.').'</div>';
                }
            } else {
                    $msg = '<div class="warnmsg">'.$this->l('No order selected.').'</div>';
            }
        }

        // Display section
        // Error message if shipper info is missing
        if ((Configuration::get('FEMA_PARAM') == 0)) {
            echo '<div class="warnmsg">'.$this->l('Warning! Your FEMA agreement code and GLN number are missing. You must configure the FEMA module.').'</div>';
            exit;
        }
        // Calls function to get orders
        $order_info = array();
        $statuses_array = array();
        $statuses = OrderState::getOrderStates((int)Context::getContext()->language->id);

        foreach ($statuses as $status) {
            $statuses_array[$status['id_order_state']] = $status['name'];
        }
        $fieldlist = array('O.`id_order`', 'O.`id_cart`', 'AD.`lastname`', 'AD.`firstname`', 'AD.`postcode`', 'AD.`city`', 'CL.`iso_code`', 'C.`email`', 'CA.`name`');

        $selected= self::orderStatusSelect();

         $sql = 'SELECT  '.implode(', ', $fieldlist).',
                            C.`firstname` AS customer_firstname, 
                            C.`lastname` AS customer_lastname,          
                            O.`current_state` AS current_state,
                            FO.`order_waybill` AS order_waybill,
                            FO.`order_weight` AS order_weight,
                            FO.`order_volumes` AS order_volumes
                FROM    '._DB_PREFIX_.'orders AS O LEFT JOIN '._DB_PREFIX_.'fema_orders AS FO ON O.id_order = FO.id_order,
                        '._DB_PREFIX_.'carrier AS CA, 
                        '._DB_PREFIX_.'customer AS C, 
                        '._DB_PREFIX_.'address AS AD, 
                        '._DB_PREFIX_.'country AS CL
                WHERE   O.id_address_delivery=AD.id_address AND
                        C.id_customer=O.id_customer AND 
                        CL.id_country=AD.id_country AND 
                        CA.id_carrier=O.id_carrier AND 
                        CA.name LIKE "Fema%" AND 
                        ' . $selected . '
                ORDER BY id_order DESC';       

        $orderlist = Db::getInstance()->ExecuteS($sql);

        if (!empty($orderlist)) {
            $order_info = self::getOrderInfo($orderlist, $statuses_array);
        } 
        else {
            $order_info['error'] = true;
        }


        // Assign smarty variables and fetches template
        Context::getContext()->smarty->assign(array(
            'msg'           => $msg,
            'stream'        => $stream,
            'token'         => $this->token,
            'order_info'    => $order_info,
        ));

        return $helper->generateForm($this->fields_form);
    }

    protected function buildHelper()
    {
        $helper = new HelperForm();

        $helper->module = $this->module;
        $helper->override_folder = 'fema/';
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('Admin'.$this->name);
        $helper->languages = $this->_languages;
        $helper->currentIndex = $this->context->link->getAdminLink('Admin'.$this->name);
        $helper->default_form_language = $this->default_form_language;
        $helper->allow_employee_form_lang = $this->allow_employee_form_lang;
        $helper->toolbar_scroll = true;
        $helper->toolbar_btn = $this->initToolbar();
        $helper->background_color = 'red';

        return $helper;
    }

    public function initToolBarTitle()
    {
        $this->toolbar_title[] = $this->l('Orders');
        $this->toolbar_title[] = $this->l('FEMA Deliveries Management');
    }

    public function setMedia($isNewTheme = false)
    {
        $this->addJquery();
        $this->addJS(_PS_MODULE_DIR_.'/fema/views/js/admin/jquery/plugins/fancybox/jquery.fancybox.js');
        $this->addJS(_PS_MODULE_DIR_.'/fema/views/js/admin/jquery/plugins/marquee/jquery.marquee.min.js');
        $this->addCSS(_PS_MODULE_DIR_.'/fema/views/js/admin/jquery/plugins/fancybox/jquery.fancybox.css');
        $this->addCSS(_PS_MODULE_DIR_.'/fema/views/css/admin/FEMA.css');
        return parent::setMedia();
    }
}