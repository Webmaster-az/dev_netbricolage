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

class importhelp {
	
	private $_name = 'gsnipreview';
	private $_id_shop;
	
	public function __construct(){
		if(version_compare(_PS_VERSION_, '1.5', '>')){
			$this->_id_shop = Context::getContext()->shop->id;
		} else {
			$this->_id_shop = 0;
		}
		
		if (version_compare(_PS_VERSION_, '1.5', '<')){
			require_once(_PS_MODULE_DIR_.$this->_name.'/backward_compatibility/backward.php');
		}
		
		$this->initContext();
	}
	
	private function initContext()
	{
		$this->context = Context::getContext();
	}
	
	public function ifExsitsTableProductcomments(){
		$sql = 'SHOW TABLES LIKE "'._DB_PREFIX_.'product_comment"';
		$result = 	Db::getInstance()->ExecuteS($sql);
		
		$result_exists = isset($result[0])?$result[0]:array();
		$is_table_exists = sizeof($result_exists)>0? $result_exists : 0;
		return $is_table_exists;
	}

    public function ifExsitsTableWithProductcommentsCriterions(){
        $sql = 'SHOW TABLES LIKE "'._DB_PREFIX_.'product_comment_criterion"';
        $result = 	Db::getInstance()->ExecuteS($sql);

        $result_exists = isset($result[0])?$result[0]:array();
        $is_table_exists = sizeof($result_exists)>0? $result_exists : 0;
        return $is_table_exists;
    }
	
	public function importComments(){
		
		
		#### 0. get exists comments ####
		$condition = '';
		if(version_compare(_PS_VERSION_, '1.4', '>')){
			$condition = ' where deleted = 0';
		}
		
		
		$sql = 'SELECT *  FROM `'._DB_PREFIX_.'product_comment` '.$condition;
		$comments = Db::getInstance()->ExecuteS($sql);

        $cookie = $this->context->cookie;
        $id_lang = (int)($cookie->id_lang);

        $is_exists_criterions = $this->ifExsitsTableWithProductcommentsCriterions();

		foreach($comments as $comment){
			
			if(version_compare(_PS_VERSION_, '1.4', '>')){
				$id_product = $comment['id_product'];
				$id_customer = $comment['id_customer'];
				$title =$comment['title'];
				$content = $comment['content'];
				$customer_name = $comment['customer_name'];


                ####
                include_once(dirname(__FILE__).'/gsnipreviewhelp.class.php');
                $obj = new gsnipreviewhelp();
                $customer_data = $obj->getInfoAboutCustomer(array('id_customer'=>$id_customer));
                ####

				if(Tools::strlen($customer_name)==0){
					$customer_name = $customer_data['customer_name'];
				}

                $email = $customer_data['email'];

				$date_add = isset($comment['date_add'])?$comment['date_add']:'';
				
				$rating = (int)round($comment['grade']);
				$is_active = $comment['validate'];




            } else {
				$id_product = $comment['id_product'];
				$id_customer = $comment['id_customer'];
				$title = mb_substr($comment['content'],0,64,'utf-8');
				$content = $comment['content'];
				
				####
				include_once(dirname(__FILE__).'/gsnipreviewhelp.class.php');
				$obj = new gsnipreviewhelp();
				$customer_data = $obj->getInfoAboutCustomer(array('id_customer'=>$id_customer));
				####
				$customer_name = $customer_data['customer_name'];
                $email = $customer_data['email'];
				
				$date_add = isset($comment['date_add'])?$comment['date_add']:'';
				
				$rating = $comment['grade'];
				$is_active = $comment['validate'];
			}
			
			
			
			#### 0. get exists comments ####
						  
						  
			#### 1. if exists comment ####



			$sql_exists = 'SELECT count(*) as count  
								  FROM `'._DB_PREFIX_.'gsnipreview` 
								  WHERE id_product = '.(int)($id_product).'
								  AND id_customer = '.(int)($id_customer).'
								  AND customer_name = "'.pSQL($customer_name).'"
								  AND title_review = "'.pSQL($title).'"
								  AND text_review = "'.pSQL($content).'"
								  AND email = "'.pSQL($email).'"
								  AND rating = "'.pSQL($rating).'"
								  AND id_lang = '.(int)($id_lang).'
								  AND is_import = 1';
			$result_exists_comments = Db::getInstance()->ExecuteS($sql_exists);
			$if_exists_comments = isset($result_exists_comments[0]['count'])? $result_exists_comments[0]['count'] : 0;
			#### 1. if exists comment ####

			#### 2. insert new comment ####
			if(!$if_exists_comments){
				$is_date = 0;
				if(Tools::strlen($date_add)>0){
					$is_date = 1;
					$date_add = strtotime($date_add);
					$date_add = date('Y-m-d H:i:s',$date_add);
					
				}


				$sql_insert = 'INSERT INTO `'._DB_PREFIX_.'gsnipreview`
									  SET 
									  id_product= '.(int)($id_product).',
									  id_customer = '.(int)($id_customer).',
									  customer_name = "'.pSQL($customer_name).'",
									  title_review = "'.pSQL($title).'",
									  text_review = "'.pSQL($content).'",
									  rating = "'.pSQL($rating).'",
									  id_shop = "'.(int)($this->_id_shop).'",
									  email = "'.pSQL($email).'",
									  is_active = '.(int)($is_active).',
									  id_lang = '.(int)($id_lang).',
									  '.(($is_date==1)?'time_add = \''.pSQL($date_add).'\',':'').'
									  is_import = 1
									  ';
				Db::getInstance()->Execute($sql_insert);

                $review_id = Db::getInstance()->Insert_ID();

                ## add criterions if exists ###
                if($is_exists_criterions){
                    $id_product_comment = $comment['id_product_comment'];
                    $this->addProductCommentCriterions(
                                                        array('id_product_comment'=>$id_product_comment,
                                                              'review_id'=>$review_id,
                                                              'id_lang' => $id_lang,
                                                             )
                                                      );
                }
                ##  add criterions if exists  ###
			}
			#### 2. insert new comment ####
		}
		
		
	}

    public function addProductCommentCriterions($data){
        $id_product_comment = $data['id_product_comment'];
        $review_id = $data['review_id'];
        $id_lang = $data['id_lang'];


        include_once(dirname(__FILE__) . '/gsnipreviewhelp.class.php');
        $obj_gsnipreviewhelp = new gsnipreviewhelp();

        ### if exists criterions for review ##
        $sql = 'SELECT count(*) as count  FROM `'._DB_PREFIX_.'product_comment_grade` WHERE id_product_comment = '.(int)$id_product_comment;
        $result = 	Db::getInstance()->ExecuteS($sql);
        $is_criterions_for_review = isset($result[0]['count'])? $result[0]['count'] : 0;
        ### if exists criterions for review ##

        if($is_criterions_for_review){

            ### get data for comment criterion ###
            $sql_grade = 'SELECT *  FROM `'._DB_PREFIX_.'product_comment_grade` WHERE id_product_comment = '.(int)$id_product_comment;
            $result_grade = 	Db::getInstance()->ExecuteS($sql_grade);
            ### get data for comment criterion ###


            foreach($result_grade as $data_grade){
                $id_product_comment_criterion = $data_grade['id_product_comment_criterion'];
                $grade = $data_grade['grade'];

                ## get criterion name ###
                $sql_grade = 'SELECT pccl.name FROM '._DB_PREFIX_.'product_comment_criterion_lang pccl
                                        WHERE pccl.id_product_comment_criterion = '.(int)$id_product_comment_criterion.' AND pccl.id_lang ='.(int)$id_lang;
                $result_grade_item = Db::getInstance()->ExecuteS($sql_grade);
                ## get criterion name ###

                $name_criterion = isset($result_grade_item[0]['name'])?trim($result_grade_item[0]['name']):null;



                if(!empty($name_criterion)){

                    ## is exists criterion in table gsnipreview_review_criterion_lang ##
                    $sql_criterion = 'SELECT id_gsnipreview_review_criterion  FROM `'._DB_PREFIX_.'gsnipreview_review_criterion_lang`
                                                                              WHERE name = "'.pSQL($name_criterion).'" AND id_lang = '.(int)$id_lang;
                    $result_criterion = Db::getInstance()->ExecuteS($sql_criterion);
                    ## is exists criterion in table gsnipreview_review_criterion_lang ##


                    $id_gsnipreview_review_criterion = isset($result_criterion[0]['id_gsnipreview_review_criterion'])?$result_criterion[0]['id_gsnipreview_review_criterion']:0;

                    $id_criterion_new = 0;
                    if($id_gsnipreview_review_criterion){

                        // criterion exists, use $id_gsnipreview_review_criterion
                        $id_criterion_new = $id_gsnipreview_review_criterion;
                        // criterion exists, use $id_gsnipreview_review_criterion


                    } else {

                        //criterion not exists, add new criterion and use it
                        $data_content_lang = array();
                        $data_content_lang_name = array();
                        $languages = Language::getLanguages(false);
                        foreach ($languages as $language){
                            $id_lang = $language['id_lang'];


                            ///
                            $sql_criterions = 'SELECT `name`  FROM `'._DB_PREFIX_.'product_comment_criterion_lang`
                                                                              WHERE id_lang = '.(int)$id_lang.' AND id_product_comment_criterion = '.(int)$id_product_comment_criterion.'
                                                                              ';
                            $result_criterions = Db::getInstance()->ExecuteS($sql_criterions);
                            ///

                            $name = isset($result_criterions[0]['name'])?$result_criterions[0]['name']:'';

                            if(Tools::strlen($name)>0)
                            {
                                $data_content_lang[$id_lang] = array( 'description' => '',
                                                                      'name' => $name
                                                                     );
                                $data_content_lang_name[$id_lang] = array('name' => $name);
                            }
                        }


                        $shops = array();
                        foreach(Shop::getShops() as $shop){
                            $shops[] = $shop['id_shop'];
                        }
                        $cat_shop_association = $shops;

                        $data = array(
                            'active' => 1,
                            'data_content_lang'=>$data_content_lang,
                            'cat_shop_association' => $cat_shop_association
                        );

                        if(sizeof($data_content_lang_name)>0) {

                            $id_criterion_new = $obj_gsnipreviewhelp->saveReviewCriteriaItem($data);
                        }
                        //criterion not exists, add new criterion and use it


                    }

                }


                // add record in table gsnipreview_review2criterion

                //$grade
                // $review_id
                // $id_criterion_new

                if($id_criterion_new) {
                    $sql_new_rating = 'INSERT into `' . _DB_PREFIX_ . 'gsnipreview_review2criterion` SET
						   id_review = ' . (int)($review_id) . ',
						   id_criterion = ' . (int)($id_criterion_new) . ',
						   rating = ' . (int)$grade . '
						   ';
                    Db::getInstance()->Execute($sql_new_rating);
                }


            }
        }

    }
	
	public function getCountComments()
	{
		$condition = '';
		if(version_compare(_PS_VERSION_, '1.4', '>')){
			$condition = ' where deleted = 0';
		}
		
		$sql = 'SELECT count(*) as count  FROM `'._DB_PREFIX_.'product_comment` '.$condition;
        $result = 	Db::getInstance()->ExecuteS($sql);
		$is_comments = isset($result[0]['count'])? $result[0]['count'] : 0;
		
		
		$sql = 'SELECT count(*) as count  FROM `'._DB_PREFIX_.'gsnipreview` 
					WHERE is_import = 1 
					';
        $result = 	Db::getInstance()->ExecuteS($sql);
		$is_import_comments = isset($result[0]['count'])? $result[0]['count'] : 0;
		
		
		
		$is_count_comments = 0;
		
		if($is_comments && ($is_import_comments != $is_comments)) {
            $is_count_comments = 1;

            if(($is_comments - $is_import_comments)>0)
            $is_comments = $is_comments - $is_import_comments;
        }
			
			
			//var_dump($is_comments); var_dump($is_count_comments);exit;
			
		return array('comments'=>$is_comments , 'is_count_comments'=>$is_count_comments);
	}
	
	
}