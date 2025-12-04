<?php
/**
 * mitrocops
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
/*
 *
 * @author    mitrocops
 * @category seo
 * @package gsnipreview
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */

class featureshelptestim {
	
	private $_name = 'gsnipreview';
    private $_prefix;
    private $_table_data_order;
    private $_table_customer;

	public function __construct(){


        $this->_prefix = $this->getObjectParent()->getPrefixShopReviews();

        $this->_table_data_order = 'gsnipreview_data_order_'.$this->_prefix;
        $this->_table_customer = 'gsnipreview_customer_'.$this->_prefix;



		if (version_compare(_PS_VERSION_, '1.5', '<')){
			require_once(_PS_MODULE_DIR_.$this->_name.'/backward_compatibility/backward.php');
		}
		
		$this->initContext();
	}
	
	private function initContext()
	{
		$this->context = Context::getContext();
	}


    public function getObjectParent(){
        include_once(dirname(__FILE__).'/../gsnipreview.php');
        $obj = new gsnipreview();
        return $obj;
    }

	
	public function saveOrder($data=null){
		$id_shop = $data['id_shop'];
		$data_product = $data['data']; 
		$customer_id = $data['customer_id'];
		$order_id = $data['order_id'];
        $date_add = isset($data['date_add'])?$data['date_add']:date('Y-m-d H:i:s');
		
		$sql = 'INSERT INTO `'._DB_PREFIX_.''.$this->_table_data_order.'`
			(id_shop, order_id, date_add, status, customer_id, data) 
			VALUES("' . (int)($id_shop) . '", 
				  "' . (int)($order_id) . '",  "'.pSQL($date_add).'", "0",
				  "' . (int)($customer_id) . '", "' . pSQL(serialize($data_product)) . '")';

		return (
			Db::getInstance()->Execute($sql)
		);
	}

	public function isDataExist($data)
	{
		$id_shop = $data['id_shop'];
		$order_id = $data['order_id'];
		
		$sql = 'SELECT count(*) as count FROM `'._DB_PREFIX_.''.$this->_table_data_order.'`
						WHERE id_shop = ' . (int)($id_shop) . ' AND order_id = ' . (int)($order_id);

		$count = Db::getInstance()->ExecuteS($sql);
		
		return (!empty($count[0]['count'])? true : false);
	}
	
	public function getStatus($data)
	{
		$id_shop = $data['id_shop'];
		$customer_id = $data['customer_id'];
		
		$sql = 'SELECT status FROM `'._DB_PREFIX_.''.$this->_table_customer.'`
					WHERE id_shop = ' . (int)($id_shop) . ' AND customer_id = ' . (int)($customer_id);

		$result = 	Db::getInstance()->ExecuteS($sql);

		return (
			isset($result[0]['status'])? $result[0]['status'] : false
		);
	}
	
	public function addStatus($data)
	{
		$id_shop = $data['id_shop'];
		$customer_id = $data['customer_id'];
		$status = $data['status'];
		
		$sql = 'INSERT INTO `'._DB_PREFIX_.''.$this->_table_customer.'` (id_shop, customer_id, status)
				   VALUES(' . (int)($id_shop) . ','. (int)($customer_id) . ', "' . (int)($status) . '")'
			.   ' ON DUPLICATE KEY UPDATE status = "' . (int)($status) . '"';

		return (Db::getInstance()->Execute($sql));
	}


	
	public function getProductsInOrder($data)
	{
		$id_lang = $data['id_lang'];

		$order_id = $data['order_id'];

        if(version_compare(_PS_VERSION_, '1.5', '>')){
            $id_shop = Context::getContext()->shop->id;
        } else {
            $id_shop = 0;
        }
		
		/*$sql = 'SELECT p.*, pa.id_product_attribute,pl.*, i.*, il.*, m.name AS manufacturer_name, s.name AS supplier_name'
			.   ' FROM ' . _DB_PREFIX_ . 'order_detail as od '
			.	' LEFT JOIN ' . _DB_PREFIX_ . 'product as p ON p.id_product = od.product_id'
            .	' LEFT JOIN ' . _DB_PREFIX_ . 'product_attribute as pa ON (p.id_product = pa.id_product AND default_on = 1)'
			.   ' LEFT JOIN ' . _DB_PREFIX_ . 'product_lang as pl ON (p.id_product = pl.id_product AND pl.id_lang = ' . (int)($id_lang) . ') AND pl.id_shop ='.(int)$id_shop.' '
			.	' LEFT JOIN ' . _DB_PREFIX_ . 'image as i ON (i.id_product = p.id_product AND i.cover = 1)'
			.	' LEFT JOIN ' . _DB_PREFIX_ . 'image_lang as il ON (i.id_image = il.id_image AND il.id_lang = ' . (int)($id_lang) . ')'
			.   ' LEFT JOIN ' . _DB_PREFIX_ . 'manufacturer as m ON m.id_manufacturer = p.id_manufacturer'
			.   ' LEFT JOIN ' . _DB_PREFIX_ . 'supplier as s ON s.id_supplier = p.id_supplier'
			.   ' WHERE od.id_order = ' . (int)($order_id);*/


        $sql = 'SELECT p.*, pa.id_product_attribute,pl.*'
            .   ' FROM ' . _DB_PREFIX_ . 'order_detail as od '
            .	' LEFT JOIN ' . _DB_PREFIX_ . 'product as p ON p.id_product = od.product_id'
            .	' LEFT JOIN ' . _DB_PREFIX_ . 'product_attribute as pa ON (p.id_product = pa.id_product AND default_on = 1)'
            .   ' LEFT JOIN ' . _DB_PREFIX_ . 'product_lang as pl ON (p.id_product = pl.id_product AND pl.id_lang = ' . (int)($id_lang) . ') AND pl.id_shop ='.(int)$id_shop.' '
            .   ' WHERE od.id_order = ' . (int)($order_id);

       $data_products = Db::getInstance()->ExecuteS($sql);

		return $data_products;
	}


    public function getOrderInfo($data){
        $order_id = $data['order_id'];

        $cookie = $this->context->cookie;

        $id_lang = (int)isset($data['id_lang'])?$data['id_lang']:(int)($cookie->id_lang);

        if(version_compare(_PS_VERSION_, '1.5', '>')) {
            $sql = 'SELECT o.*, ost.color, osl.name as order_status_lng, ord.reference FROM ' . _DB_PREFIX_ . 'order_history as o
            LEFT JOIN ' . _DB_PREFIX_ . 'orders as ord ON ord.id_order= ' . (int)$order_id . '
            LEFT JOIN ' . _DB_PREFIX_ . 'order_state as ost ON ost.id_order_state = o.id_order_state
            LEFT JOIN ' . _DB_PREFIX_ . 'order_state_lang as osl ON osl.id_order_state = o.id_order_state
            WHERE o.id_order_history = (SELECT MAX(id_order_history) FROM ' . _DB_PREFIX_ . 'order_history WHERE id_order = ' . (int)$order_id . ')
             AND osl.id_lang = ' . (int)$id_lang;

        } else {

            $sql = 'SELECT o.*, ost.color, osl.name as order_status_lng FROM ' . _DB_PREFIX_ . 'order_history as o
            LEFT JOIN ' . _DB_PREFIX_ . 'order_state as ost ON ost.id_order_state = o.id_order_state
            LEFT JOIN ' . _DB_PREFIX_ . 'order_state_lang as osl ON osl.id_order_state = o.id_order_state
            WHERE o.id_order_history = (SELECT MAX(id_order_history) FROM ' . _DB_PREFIX_ . 'order_history WHERE id_order = ' . (int)$order_id . ')
             AND osl.id_lang = ' . (int)$id_lang;
        }

        $data_order_info = Db::getInstance()->ExecuteS($sql);

        /*


        $data_product_in_order = $this->getProductsInOrder(array('order_id'=>$order_id, 'id_lang'=>$id_lang));


        $data_products_tmp = array();
        $data_products_in_order = array();
        foreach ($data_product_in_order as $product) {

            $attributes = Product::getProductProperties($id_lang, $product);

            if(version_compare(_PS_VERSION_, '1.6', '>')) {
                $link = Context::getContext()->link;
                $product_obj = new Product($attributes['id_product']);
                $product_url = $link->getProductLink((int)$product_obj->id, null, null, null,
                    $id_lang, null, 0, false);
            } else {
                $product_url = $attributes['link'];
            }

            $product_name = $attributes['name'];
            $id_product = $attributes['id_product'];

            if(in_array($id_product,$data_products_tmp))continue;

            $data_products_tmp[] = $id_product;

            $data_products_in_order[] = array('product_url'=>$product_url,'product_name'=>$product_name,'id_product'=>$id_product);
        }

        $data_order_info[0]['products'] = $data_products_in_order;*/
        return $data_order_info;
    }

    public function getOrderStatus($data){

        $order_id = $data['order_id'];

        $query = 'SELECT id_order_state FROM ' . _DB_PREFIX_ . 'order_history WHERE id_order = ' . (int)$order_id
            . ' AND id_order_history = (SELECT MAX(id_order_history) FROM ' . _DB_PREFIX_ . 'order_history WHERE id_order = ' . (int)$order_id . ')'
        ;

        $data_order = Db::getInstance()->getRow($query);


        $status_order = !empty($data_order['id_order_state'])? $data_order['id_order_state'] : 0;

        return $status_order;
    }

    public function updateOrderStatus($data)
    {
        $status = $data['status'];
        $id = $data['id'];


        $sql = 'SELECT count_sent, date_send FROM `'._DB_PREFIX_.''.$this->_table_data_order.'`
						WHERE id = '.(int)$id;

        $count = Db::getInstance()->ExecuteS($sql);

        $count_sent = isset($count[0]['count_sent'])? $count[0]['count_sent'] : 0;


        if(
            $count_sent == 0
            && !empty($count[0]['date_send'])
        ) {
            // sent twice
            $count_sent = $count_sent + 1;
            $sql = 'UPDATE `'._DB_PREFIX_.''.$this->_table_data_order.'` rdo SET
                rdo.count_sent = '.(int)$count_sent.',
                rdo.date_send_second = "'.pSQL(date("Y-m-d H:i:s")).'"
                WHERE id = '.(int)$id;

        } else {

            // send once
            $sql = 'UPDATE `'._DB_PREFIX_.''.$this->_table_data_order.'` rdo SET
                rdo.status = '.(int)$status.',
                rdo.date_send = "'.pSQL(date("Y-m-d H:i:s")).'"
                WHERE id = '.(int)$id;
        }



        return (Db::getInstance()->Execute($sql));
    }


	public function deleteCronTasks($data)
	{
		$id_shop = $data['id_shop'];
		$delay = $data['delay'];
		$time = $data['time'];
        $data_tasks_ids = $data['data_tasks_ids'];
		
		$sql = 'DELETE FROM `'._DB_PREFIX_.''.$this->_table_data_order.'`
					WHERE id_shop = ' . (int)($id_shop) . ' 
					AND ' . pSQL($time) . ' >= (UNIX_TIMESTAMP(date_add) + ' . pSQL($delay) . ')
					AND id IN('.implode(",", array_map('pSQL',$data_tasks_ids)).') ';

		unset($time);

		return (Db::getInstance()->Execute($sql));
	}


    public function getCronTaskDelayForReminder($data){

        if(version_compare(_PS_VERSION_, '1.5', '>')){
            $id_shop = Context::getContext()->shop->id;
        } else {
            $id_shop = 0;
        }

        $type = $data['type'];
        $id_order = $data['id_order'];


        $sql = 'SELECT rdo.date_send, rdo.date_add, NOW() as time, rdo.customer_id
					FROM `'._DB_PREFIX_.''.$this->_table_data_order.'` as rdo
					LEFT JOIN `'._DB_PREFIX_.''.$this->_table_customer.'` as rc
					ON (rc.customer_id = rdo.customer_id and rc.status = "1")
					WHERE rdo.id_shop = ' . (int)($id_shop) . ' AND rdo.order_id = '.(int)$id_order.'
					ORDER BY rdo.order_id DESC';

        //echo $sql;exit;

        $data_delay = Db::getInstance()->ExecuteS($sql);

        $time_add = isset($data_delay[0]['time'])?$data_delay[0]['time']:'';
        $time = strtotime($time_add); // fixed bug for locale. Maybe different time for mysql and PHP!
        $type_error = 0;

        if(Configuration::get($this->_name.'reminder'.$this->_prefix)==0){
            $type_error = 4;
        } else {

            // only for customers who not add review //
            $remrevsec = (int)Configuration::get($this->_name.'remrevsec'.$this->_prefix);

            if(!$remrevsec) {
                $customer_id = $data_delay[0]['customer_id'];

                include_once(dirname(__FILE__).'/../classes/storereviews.class.php');
                $obj_storereviews = new storereviews();

                $is_add_review = $obj_storereviews->isExistsReviewByCustomer(array('id_customer' => $customer_id));

                if ($is_add_review) {
                    $type_error = 5;
                }

            }
            // only for customers who not add review //


            if($type_error == 0) {
                switch ($type) {
                    case 'first':
                        $date_add = isset($data_delay[0]['date_add']) ? strtotime($data_delay[0]['date_add']) : 0;
                        $delay = Configuration::get($this->_name . 'delay' . $this->_prefix) * 86400;


                        if ($time < $delay + $date_add) {
                            $type_error = 1; // have passed less 5 days for sending reminder email
                        }

                        break;
                    case 'second':

                        $date_send = isset($data_delay[0]['date_send']) ? strtotime($data_delay[0]['date_send']) : 0; // 1
                        $delaysec = Configuration::get($this->_name . 'delaysec' . $this->_prefix) * 86400; // 7
                        if (Configuration::get($this->_name . 'remindersec' . $this->_prefix) == 1) {

                            if ($time < $date_send + $delaysec) {
                                $type_error = 2; // have passed less 5 days after the first sending
                            }

                        } else {
                            $type_error = 3; // disabled Send a review reminder email to customers a second time
                        }
                        break;
                }
            }

        }
        unset($time);

        return array('type_error'=>$type_error);
    }
	
	public function getCronTasks($data)
	{
		$id_shop = $data['id_shop'];
		$delay = $data['delay'];
		$time = $data['time'];

        $id_order = $data['id_order'];
        $cond_for_one_order = '';
        if($id_order){
            $cond_for_one_order = ' AND rdo.order_id = '.(int)$id_order;
        }


        if(Configuration::get($this->_name.'remindersec'.$this->_prefix) == 1) {
            $delaysec = Configuration::get($this->_name.'delaysec'.$this->_prefix) * 86400;
            $condition = '(
                            (' . pSQL($time) . ' >= (UNIX_TIMESTAMP(rdo.date_send) + ' . pSQL($delaysec) . ') AND rdo.status = 1 and rdo.count_sent = 0)
                            OR
                            (' . pSQL($time) . ' >= (UNIX_TIMESTAMP(rdo.date_add) + ' . pSQL($delay) . ') and rdo.count_sent = 0 AND rdo.status=0)
                          ) ';
        } else {
            $condition = '' . pSQL($time) . ' >= (UNIX_TIMESTAMP(rdo.date_add) + ' . pSQL($delay) . ') AND rdo.status=0 ';
        }
		
		$sql = 'SELECT rdo.order_id, rdo.id_shop, rdo.data, c.email as email , c.firstname, c.lastname, rdo.id, rdo.customer_id
					FROM `'._DB_PREFIX_.''.$this->_table_data_order.'` as rdo
					LEFT JOIN `'._DB_PREFIX_.''.$this->_table_customer.'` as rc
					ON (rc.customer_id = rdo.customer_id and rc.status = "1")
					LEFT JOIN ' . _DB_PREFIX_ . 'customer as c ON c.id_customer = rdo.customer_id
					WHERE rdo.id_shop = ' . (int)($id_shop) . ' '.$cond_for_one_order.' AND '.$condition.'
					ORDER BY rdo.order_id DESC';

		$data_cron = Db::getInstance()->ExecuteS($sql);

        //echo "<pre>"; var_dump($data_cron); echo $sql;exit;


        unset($time);

		return $data_cron;
	}
	
	public function sendCronTab($data_in = null){

        $id_order = isset($data_in['order_id'])?$data_in['order_id']:0;
		
		$obj = $this->getObjectParent();

		$data_translate = $obj->translateItems();

        include_once(dirname(__FILE__).'/../classes/storereviews.class.php');
        $obj_storereviews = new storereviews();

			
		if(Configuration::get($this->_name.'reminder'.$this->_prefix)==0)
			die($data_translate['review_reminder']);	
		

        if(version_compare(_PS_VERSION_, '1.5', '>')){
			$id_shop = Context::getContext()->shop->id;
		} else {
			$id_shop = 0;
		}
		
		
		$time = time();

		$data_tasks = $this->getCronTasks( 
											array('id_shop'=>$id_shop,
												  'delay'=>Configuration::get($this->_name.'delay'.$this->_prefix) * 86400,
												  'time' => $time,
                                                'id_order'=>$id_order,
												 )
										 );
		//echo "<pre>"; var_dump($data_tasks); exit;


        $data_tasks_ids = array();
        $data_order_ids = array();

        $tasks_send = 0;
        $orderstatuses = Configuration::get($this->_name.'orderstatuses'.$this->_prefix);
        $orderstatuses = explode(",",$orderstatuses);


        if (!empty($data_tasks)) {


            foreach ($data_tasks as $task) {

                $data_task = unserialize($task['data']);

                if (!empty($data_task) && is_array($data_task)) {


                    $tmp_arrray = array();
                    foreach ($data_task as $k1=> $product ) {

                        $id_product = $product['id_product'];


                        if(in_array($id_product,$tmp_arrray)) {
                            unset($data_task[$k1]);
                            continue;
                        }

                        $id_lang = $product['id_lang'];
                        $subject = Configuration::get($this->_name . 'emrem'.$this->_prefix.'_' . $id_lang);

                        $data_url = $obj_storereviews->getSEOURLs(array('iso_lng'=>Language::getIsoById((int)($id_lang))));

                        $testimonials_url = $data_url['testimonials_url'];
                        $link_to_form = $testimonials_url;


                        $tmp_arrray[] = $id_product;

                    }

                    // only for customers who not add review //
                    $remrevsec = (int)Configuration::get($this->_name.'remrevsec'.$this->_prefix);

                    if(!$remrevsec) {
                        $is_add_review = $obj_storereviews->isExistsReviewByCustomer(array('id_customer' => $task['customer_id']));
                        if ($is_add_review)
                            continue;
                    }
                    // only for customers who not add review //



                    $reference_order = '';
                    if(version_compare(_PS_VERSION_, '1.5', '>')) {
                        $data_order_info = $this->getOrderInfo(array('order_id' => $task['order_id']));
                        $reference_order = isset($data_order_info[0]['reference'])?' - '.$data_order_info[0]['reference']:'';
                    }

                    $param = array(
                        'subject'   => $subject,
                        'email'     => $task['email'],
                        'order_id'  => $task['order_id'].$reference_order,
                        'id_shop'   => $task['id_shop'],
                        'vars' => array(
                            '{link_to_form}' 	=> $link_to_form,
                            '{orderid}' => $task['order_id'].$reference_order,
                            '{lastname}' => $task['lastname'],
                            '{firstname}' => $task['firstname'],
                        )
                    );



                    ####
                    $order_id = $task['order_id'];

                    $status_order = $this->getOrderStatus(array('order_id'=>$order_id));


                    if(in_array($status_order,$orderstatuses)) {

                        $task_id = $task['id'];
                        $data_tasks_ids[] = $task_id;
                        $data_order_ids[] = $order_id;


                        $data_notification = array_merge($data_task, $param);
                        unset($param);

                        // send email
                        //echo "<pre>"; var_dump($data_notification);echo "<br><hr><br>";
                        $this->sendNotification($data_notification);
                        $tasks_send++;


                        $crondelay = (int)Configuration::get($this->_name.'crondelay'.$this->_prefix);
                        sleep($crondelay);
                    }



                    #####


                }

                $cronnpost = (int)Configuration::get($this->_name.'cronnpost'.$this->_prefix);
                if($cronnpost <= $tasks_send){
                    break;
                }
            }

            //exit;


            if(count($data_order_ids)>0) {
                ### print info ##

                echo $data_translate['sent_cron_items'] . ": " . $tasks_send . "\n\n";

                echo "\n\n<br/>";
                for ($p = 0; $p < 30; $p++) {
                    echo "-";
                }
                echo "\n\n<br/>";


                echo $data_translate['sent_request'] . ": " . "\n\n<br/>";
                foreach ($data_order_ids as $id_order_sent) {
                    echo $id_order_sent . "\n\n<br/>";
                }

                $subject_success_sent_email = Configuration::get($this->_name . 'reminderok'.$this->_prefix.'_' . $id_lang);

                $data_admin_sent = array(
                    'subject' => $subject_success_sent_email,
                    'id_shop' => $id_shop,
                    'vars' => array(
                        '{orders}' => implode("<br/>", $data_order_ids),
                        '{sent_request_text}' => $data_translate['sent_request'],
                    )

                );

                $this->sendNotificationConfirmationByAdmin($data_admin_sent);
                ### print info ##
            } else {
                echo $data_translate['no_sent_items']." \n\n";
            }


            unset($data_tasks);


            // delete tasks
            $count_remove_tasks = sizeof($data_tasks_ids);
            if($count_remove_tasks == 0){
                $data_tasks_ids_delete = array(0);
            } else {
                $data_tasks_ids_delete = $data_tasks_ids;
            }

            foreach($data_tasks_ids_delete as $id_delete){
                $this->updateOrderStatus(array('status'=>1,'id'=>$id_delete));
            }


            /*$this->deleteCronTasks( array('id_shop'=>$id_shop,
                    'delay'=>Configuration::get($this->_name.'delay'.$this->_prefix) * 86400,
                    'time' => $time,
                    'data_tasks_ids' => $data_tasks_ids_delete,
                )
            );

            echo $data_translate['delete_cron_items'].": ".$count_remove_tasks."\n\n";
            */

        }
        else {
            echo $data_translate['no_sent_items']." \n\n";
        }
		
	}


    public function sendNotificationConfirmationByAdmin($data){

        ####
        $cookie = $this->context->cookie;

        $id_lang = (int)($cookie->id_lang);
        $id_shop = (int)isset($data['id_shop'])?$data['id_shop']:0;

        $iso_lng = Language::getIsoById((int)($id_lang));

        $dir_mails = _PS_MODULE_DIR_ . '/' . $this->_name . '/' . 'mails/';

        if (is_dir($dir_mails . $iso_lng . '/')) {
            $id_lang_current = $id_lang;
        }
        else {
            $id_lang_current = Language::getIdByIso('en');
        }
        ####


        if(version_compare(_PS_VERSION_, '1.6', '>')){
            /* Email sending */
            Mail::Send($id_lang_current, 'customer-reminder-admin-'.$this->_prefix, $data['subject'], $data['vars'],
                Configuration::get($this->_name.'mail'.$this->_prefix), 'Admin confirmation form', NULL, NULL, NULL, NULL, dirname(__FILE__).'/../mails/', NULL, $id_shop);
        } else {
            /* Email sending */
            Mail::Send($id_lang_current, 'customer-reminder-admin-'.$this->_prefix, $data['subject'], $data['vars'],
                Configuration::get($this->_name.'mail'.$this->_prefix), 'Admin confirmation form', NULL, NULL, NULL, NULL, dirname(__FILE__).'/../mails/');
        }



    }
	
	public function sendNotification($data){

			####
			$cookie = $this->context->cookie;

            $id_lang = (int)isset($data[0]['id_lang'])?$data[0]['id_lang']:(int)($cookie->id_lang);


            $id_shop = (int)isset($data['id_shop'])?$data['id_shop']:0;


			
			$iso_lng = Language::getIsoById((int)($id_lang));


			
			$dir_mails = _PS_MODULE_DIR_ . '/' . $this->_name . '/' . 'mails/';
			
			if (is_dir($dir_mails . $iso_lng . '/')) {
				$id_lang_current = $id_lang;
			}
			else {
				$id_lang_current = Language::getIdByIso('en');
			}
			####



        // fixed bug when admin delete customer related with order
        if(!empty($data['email'])) {
            if (version_compare(_PS_VERSION_, '1.6', '>')) {
                /* Email sending */
                Mail::Send($id_lang_current, 'customer-reminder-' . $this->_prefix, $data['subject'], $data['vars'],
                    $data['email'], 'Reminder Form', NULL, NULL, NULL, NULL, dirname(__FILE__) . '/../mails/', NULL, $id_shop);
            } else {
                /* Email sending */
                Mail::Send($id_lang_current, 'customer-reminder-' . $this->_prefix, $data['subject'], $data['vars'],
                    $data['email'], 'Reminder Form', NULL, NULL, NULL, NULL, dirname(__FILE__) . '/../mails/');
            }
        }
			

	}


    public function getOrderStatuses($data = null){


        $cookie = $this->context->cookie;

        $id_lang = (int)isset($data['id_lang'])?$data['id_lang']:(int)($cookie->id_lang);

        $query = 'SELECT * from ' . _DB_PREFIX_ . 'order_state os join ' . _DB_PREFIX_ . 'order_state_lang osl on(osl.id_order_state = os.id_order_state)
                    WHERE osl.id_lang = '.(int)$id_lang;

        return Db::getInstance()->ExecuteS($query);

    }

    public function getAcceptedOrderStatuses($data = null){
        $orderstatuses = Configuration::get($this->_name.'orderstatuses'.$this->_prefix);
        if($orderstatuses)
            $orderstatuses = explode(",",$orderstatuses);


        if(!is_array($orderstatuses))
            $orderstatuses = array(0);

        $cookie = $this->context->cookie;

        $id_lang = (int)isset($data['id_lang'])?$data['id_lang']:(int)($cookie->id_lang);

        $query = 'SELECT os.color, osl.name from ' . _DB_PREFIX_ . 'order_state os join ' . _DB_PREFIX_ . 'order_state_lang osl on(osl.id_order_state = os.id_order_state)
                    WHERE osl.id_lang = '.(int)$id_lang.' AND os.id_order_state IN('.implode(",", array_map('pSQL',$orderstatuses)).') ';


        $data_statuses = Db::getInstance()->ExecuteS($query);


        return $data_statuses;
    }

    public function importOldOrders($data){


        ## add include obj for adding product link ##
        include_once(dirname(__FILE__) . '/../gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();
        ## add include obj for adding product link ##

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $orders = Order::getOrdersIdByDate($start_date, $end_date);
        $count = 0;


        if (!empty($orders)) {

            foreach ($orders as $order) {
                $obj_order = new Order($order);

                if (Validate::isLoadedObject($obj_order)) {

                    $id_shop = $obj_order->id_shop;
                    $order_id = $obj_order->id;
                    $id_customer = $obj_order->id_customer;
                    $id_lang = $obj_order->id_lang;
                    $date_add = $obj_order->date_add;


                    if (false === $this->isDataExist(
                            array('id_shop'=>$id_shop,
                                'order_id'=>$order_id
                            )
                        )
                    ) {




                        $status  = $this->getStatus(
                            array('id_shop'=>$id_shop,
                                'customer_id'=> $id_customer
                            )
                        );

                        if (false === $status) {
                            $this->addStatus(
                                array('id_shop'=>$id_shop,
                                    'customer_id'=> $id_customer,
                                    'status'=>1
                                )
                            );

                            $add_status = 1;
                        } else {
                            $add_status  = $status;
                        }

                        if (!empty($add_status)) {
                            $products = $this->getProductsInOrder(
                                array('order_id'=>$order_id,
                                    'id_lang' => $id_lang,
                                    'id_shop'=>$id_shop
                                )
                            );
                            //echo "<pre>"; var_dump($products);exit;

                            if (!empty($products)) {
                                $data = array();
                                foreach ($products as $product) {

                                    $product['rate'] = 0;
                                    $attributes = Product::getProductProperties($id_lang, $product);


                                    ## add correct product link with language ##
                                    $product_obj = new Product((int)$product['id_product']);
                                    $data_product = $obj_gsnipreview->_productData(array('product'=>$product_obj,'id_lang'=>$id_lang));
                                    $product_link = $data_product['product_url'];
                                    ## add correct product link with language ##

                                    $data[] = array('title' => $attributes['name'],
                                        'category' => $attributes['category'],
                                        'link' => $product_link, //$attributes['link'],
                                        'id_lang' => $id_lang,
                                        'id_product' =>$attributes['id_product'],
                                    );

                                    unset($attributes);
                                }

                                $result =  $this->saveOrder(
                                    array('id_shop'=>$id_shop,
                                        'order_id' => $order_id,
                                        'customer_id' => $id_customer,
                                        'data' => $data,
                                        'date_add'=>$date_add,
                                    )
                                );

                                if ($result) {
                                    $count++;
                                }

                                unset($data);
                            }
                        }

                    }





                }
            }


        }

        return $count;

    }


    public function getOrdersForReminder($data)
    {

        $end_date = $data['end_date'];
        $start_date = $data['start_date'];


        $sql = 'SELECT * FROM `'._DB_PREFIX_.$this->_table_data_order.'`';

        $sql .= '  WHERE date_add <= \''.pSQL($end_date).'\' AND date_add >= \''.pSQL($start_date).'\' ';

        $sql .= '  order by date_add desc ';

        //echo $sql; exit;

        $count_all = 0;
        $result = Db::getInstance()->ExecuteS($sql);
        if (!$result || !is_array($result)) {
            $result = array();
        } else {
            $sql_count = 'SELECT count(*) as count FROM `'._DB_PREFIX_.$this->_table_data_order.'`';
            $result_count = Db::getInstance()->getRow($sql_count);
            $count_all = $result_count['count'];
        }

        return array('result'=>$result,'count_all'=>$count_all);
    }
}