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

class csvhelpti {
	
	private $_name = 'gsnipreview';
    private $_name_table = 'gsnipreview_storereviews';
    private $_prefix;

	public function __construct(){


        $this->_prefix = $this->getObjectParent()->getPrefixShopReviews();


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

    public function getAvailableFields(){

        $obj = $this->getObjectParent();
        $data_translate = $obj->translateItems();

        $fields = array(
            'A' => array('name'=>$data_translate['A_name'],'example'=>$data_translate['A_example'],'required'=>1,'filed_in_db'=>'id_lang'),
            'B' => array('name'=>$data_translate['B_name'],'example'=>$data_translate['B_example'],'required'=>1,'filed_in_db'=>'rating'),
            'C' => array('name'=>$data_translate['C_name'],'example'=>$data_translate['C_example'],'required'=>1,'filed_in_db'=>'id_customer'),
            'D' => array('name'=>$data_translate['D_name'],'example'=>$data_translate['D_example'],'required'=>0,'filed_in_db'=>'name'),
            'E' => array('name'=>$data_translate['E_name'],'example'=>$data_translate['E_example'],'required'=>0,'filed_in_db'=>'email'),
            'F' => array('name'=>$data_translate['F_name'],'example'=>$data_translate['F_example'],'required'=>1,'filed_in_db'=>'message'),
            'G' => array('name'=>$data_translate['G_name'],'example'=>$data_translate['G_example'],'required'=>0,'filed_in_db'=>'response'),
            'H' => array('name'=>$data_translate['H_name'],'example'=>$data_translate['H_example'],'required'=>0,'filed_in_db'=>'is_show'),
            'I' => array('name'=>$data_translate['I_name'],'example'=>$data_translate['I_example'],'required'=>1,'filed_in_db'=>'date_add'),
            'J' => array('name'=>$data_translate['J_name'],'example'=>$data_translate['J_example'],'required'=>0,'filed_in_db'=>'active'),
        );

        return $fields;
    }

    public function import(){

        include_once(dirname(__FILE__).'/../classes/storereviews.class.php');
        $obj_storereviews = new storereviews();


        if(version_compare(_PS_VERSION_, '1.5', '>')) {
            $current_shop_id = Shop::getContextShopID();

            if(!$current_shop_id)
                $current_shop_id = Context::getContext()->shop->id;
        } else {
            $current_shop_id = 0;
        }


        $error_number = 0;
        $csv_file =  $_FILES['csv_store']['tmp_name'];
        $allowed = array('csv');
        $extension = pathinfo($_FILES['csv_store']['name'], PATHINFO_EXTENSION);
        $is_allowed = 0;
        if (in_array(Tools::strtolower($extension), $allowed)) {
            $is_allowed = 1;
        }


        if (is_file($csv_file) && $is_allowed) {
            $input = fopen($csv_file, 'a+');
            // if the csv file contain the table header leave this line
            $row = fgetcsv($input, 0, ';'); // here you got the header


            $is_empty = 1;
            while ($row = fgetcsv($input, 0, ';')) {
                // insert into the database

                $sql = 'INSERT INTO `'._DB_PREFIX_.''.$this->_name_table.'` SET ';

                $id_lang = $row[0];
                $sql .= ' id_lang = '.(int)$id_lang.', ';

                $rating =  $row[1];
                $sql .= ' rating = '.(int)$rating.', ';

                $id_customer =  $row[2];

                if($id_customer ==  0){
                    $name =  $row[3];
                    $email =  $row[4];

                    $sql .= ' id_customer = "0", ';

                } else {
                    $customer_data = $obj_storereviews->getInfoAboutCustomer(array('id_customer'=>$id_customer));

                    //var_dump($customer_data);

                    $name = Tools::strlen($row[3])>0?$row[3]:$customer_data['customer_name'];
                    $email = Tools::strlen($row[4])>0?$row[4]:$customer_data['email'];


                    $sql .= ' id_customer = "'.(int)($id_customer).'", ';
                }

                $sql .= ' name = "'.pSQL($name).'", ';
                $sql .= ' email = "'.pSQL($email).'", ';

                $message = $row[5];
                $sql .= ' message = "'.pSQL($message).'", ';

                $response = $row[6];
                $sql .= ' response = "'.pSQL($response).'", ';

                $is_show = $row[7];
                $sql .= ' is_show = "'.(int)($is_show).'", ';

                $date_add = $row[8];
                $date_add = str_replace('/', '-', $date_add); //fixed bug for strtotime fuction http://php.net/manual/en/function.strtotime.php#99149
                $date_add = date('Y-m-d H:i:s',strtotime($date_add));
                $sql .= ' date_add = "'.pSQL($date_add).'", ';

                $active = $row[9];
                $sql .= ' active = "'.(int)($active).'", ';


                $sql .= ' id_shop = "'.(int)($current_shop_id).'" ';


                //echo $sql;
                //echo "<pre>"; var_dump($row); var_dump($sql); echo "<br/><hr><br/>";

                Db::getInstance()->Execute($sql);


                $is_empty = 0;
            }
            //exit;

            if($is_empty == 1)
                $error_number = 2;

        } else {
            $error_number = 1;
        }

        return array('error_number'=>$error_number);
    }

    public function export(){
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=store_reviews.csv');

        if(version_compare(_PS_VERSION_, '1.5', '>')) {
            $current_shop_id = Shop::getContextShopID();

            if(!$current_shop_id)
                $current_shop_id = Context::getContext()->shop->id;
        } else {
            $current_shop_id = 0;
        }


        $data_fields = $this->getAvailableFields();
        $fields_csv = array();
        $fields_db = array();
        foreach($data_fields as $field_csv => $field_db){
            $fields_csv[] = $field_csv;
            $fields_db[] = $field_db['filed_in_db'];
        }

        // we initialize the output with the headers


        $output = implode(";",$fields_csv)."\n";

        // select all items
        $sql = 'SELECT * FROM `'._DB_PREFIX_.''.$this->_name_table.'`
						WHERE id_shop = ' . (int)($current_shop_id).' and is_deleted = 0' ;

        $list = Db::getInstance()->ExecuteS($sql);


        $count_fields_db = count($fields_db);
        if($count_fields_db>0) {
            foreach($list as $row) {

                $i = 0;
                foreach ($fields_db as $field) {

                    if($field == 'date_add') {
                        $date_add = date("d/m/Y",strtotime($row[$field]));
                        $output .= $date_add;
                    } else {
                        $row_field = pSQL($row[$field]);
                        $row_field  = str_replace(";",",",$row_field);
                        $output .= $row_field;

                    }
                    if ($count_fields_db - 1 == $i) {
                        $output .= "\n";
                    } else {
                        $output .= ";";
                    }
                    $i++;
                }

            }
        }
        echo $output;
        exit;
    }
}