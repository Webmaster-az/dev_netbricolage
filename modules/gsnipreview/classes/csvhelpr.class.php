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

class csvhelpr {
	
	private $_name = 'gsnipreview';
    private $_prefix;

	public function __construct(){


        $this->_prefix = $this->getObjectParent()->getPrefixProductReviews();


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
        $data_translate = $obj->translateCustom();

        $fields = array(
            'A' => array('name'=>$data_translate['A_name'],'example'=>$data_translate['A_example'],'required'=>1,'filed_in_db'=>'id_lang'),
            'B' => array('name'=>$data_translate['B_name'],'example'=>$data_translate['B_example'],'required'=>1,'filed_in_db'=>'rating'),
            'C' => array('name'=>$data_translate['C_name'],'example'=>$data_translate['C_example'],'required'=>1,'filed_in_db'=>'id_product'),
            'D' => array('name'=>$data_translate['D_name'],'example'=>$data_translate['D_example'],'required'=>1,'filed_in_db'=>'id_customer'),
            'E' => array('name'=>$data_translate['E_name'],'example'=>$data_translate['E_example'],'required'=>0,'filed_in_db'=>'customer_name'),
            'F' => array('name'=>$data_translate['F_name'],'example'=>$data_translate['F_example'],'required'=>0,'filed_in_db'=>'email'),
            'G' => array('name'=>$data_translate['G_name'],'example'=>$data_translate['G_example'],'required'=>1,'filed_in_db'=>'title_review'),
            'H' => array('name'=>$data_translate['H_name'],'example'=>$data_translate['H_example'],'required'=>1,'filed_in_db'=>'text_review'),
            'I' => array('name'=>$data_translate['I_name'],'example'=>$data_translate['I_example'],'required'=>0,'filed_in_db'=>'admin_response'),
            'J' => array('name'=>$data_translate['J_name'],'example'=>$data_translate['J_example'],'required'=>0,'filed_in_db'=>'is_display_old'),
            'K' => array('name'=>$data_translate['K_name'],'example'=>$data_translate['K_example'],'required'=>1,'filed_in_db'=>'time_add'),
            'L' => array('name'=>$data_translate['L_name'],'example'=>$data_translate['L_example'],'required'=>0,'filed_in_db'=>'is_active'),
        );

        return $fields;
    }

    public function import(){

        include_once(dirname(__FILE__).'/../classes/gsnipreviewhelp.class.php');
        $obj_gsnipreviewhelp = new gsnipreviewhelp();


        if(version_compare(_PS_VERSION_, '1.5', '>')) {
            $current_shop_id = Shop::getContextShopID();

            if(!$current_shop_id)
                $current_shop_id = Context::getContext()->shop->id;
        } else {
            $current_shop_id = 0;
        }


        $error_number = 0;
        $csv_file =  $_FILES['csv_product']['tmp_name'];
        $allowed = array('csv');
        $extension = pathinfo($_FILES['csv_product']['name'], PATHINFO_EXTENSION);
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

                $sql = 'INSERT INTO `'._DB_PREFIX_.''.$this->_name.'` SET ';

                $id_lang = $row[0];
                $sql .= ' id_lang = '.(int)$id_lang.', ';

                $rating =  $row[1];
                $sql .= ' rating = '.(int)$rating.', ';

                $id_product =  $row[2];
                $sql .= ' id_product = '.(int)$id_product.', ';

                $id_customer =  $row[3];

                if($id_customer == 0){
                    $name =  $row[4];
                    $email =  $row[5];

                    $sql .= ' id_customer = "0", ';

                } else {
                    $customer_data = $obj_gsnipreviewhelp->getInfoAboutCustomer(array('id_customer'=>$id_customer));
                    $name = Tools::strlen($row[4])>0?$row[4]:$customer_data['customer_name'];
                    $email = Tools::strlen($row[5])>0?$row[5]:$customer_data['email'];

                    $sql .= ' id_customer = "'.(int)($id_customer).'", ';
                }

                $sql .= ' customer_name = "'.pSQL($name).'", ';
                $sql .= ' email = "'.pSQL($email).'", ';

                $message = $row[6];
                $sql .= ' title_review = "'.pSQL($message).'", ';

                $message = $row[7];
                $sql .= ' text_review = "'.pSQL($message).'", ';

                $response = $row[8];
                $sql .= ' admin_response = "'.pSQL($response).'", ';

                $is_show = $row[9];
                $sql .= ' is_display_old = "'.(int)($is_show).'", ';

                $date_add = $row[10];
                $date_add = str_replace('/', '-', $date_add); //fixed bug for strtotime fuction http://php.net/manual/en/function.strtotime.php#99149
                $date_add = date('Y-m-d H:i:s',strtotime($date_add));
                $sql .= ' time_add = "'.pSQL($date_add).'", ';

                $active = $row[11];
                $sql .= ' is_active = "'.(int)($active).'", ';


                $sql .= ' id_shop = "'.(int)($current_shop_id).'" ';


                //echo $sql;echo "<pre>"; var_dump($row);exit;

                Db::getInstance()->Execute($sql);


                $is_empty = 0;
            }

            if($is_empty == 1)
                $error_number = 2;

        } else {
            $error_number = 1;
        }

        return array('error_number'=>$error_number);
    }

    public function export(){
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=product_reviews.csv');

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
        $sql = 'SELECT * FROM `'._DB_PREFIX_.''.$this->_name.'`
						WHERE id_shop = ' . (int)($current_shop_id).'' ;

        $list = Db::getInstance()->ExecuteS($sql);


        $count_fields_db = count($fields_db);
        if($count_fields_db>0) {
            foreach($list as $row) {

                $i = 0;
                foreach ($fields_db as $field) {

                    if($field == 'time_add') {
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