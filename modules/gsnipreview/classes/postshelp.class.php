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

class postshelp  extends Module {
	
	
	public function postToAPI($data){
		
		$customer_name = $data['customer_name'];
		$product_name = $data['product_name'];
		$product_link = $data['product_link'];
		$rating = (int)$data['rating'];
		$picture = $data['image'];
		$id_lang = $data['id_lang'];
		
		$stars = '';
		for($i=1;$i<=5;$i++){
			$active_star = '★ ';
			$no_active_star = '☆ ';
			
			if($i<=$rating){
				$stars .= $active_star;
			} else {
				$stars .= $no_active_star;
			}
		}
		
		
		
		$name = $data['name'];
		
		$data_requrements = $this->checkrequirements();
    	$is_pstwitterpost = $data_requrements['pstwitterpost'];
    	$is_psvkpost = $data_requrements['psvkpost'];
    	
		// post to Twitter //
		if(Configuration::get($name.'twpost_on') && $is_pstwitterpost){
			require_once(dirname(__FILE__).'/../../../modules/pstwitterpost/pstwitterpost.php');
			$obj_pstwitterpost = new pstwitterpost();
				
			$status = $customer_name.' '.Configuration::get($name.'twdesc'.'_'.$id_lang).': '.$stars.' - '.$product_name.' - '.$product_link;
			$obj_pstwitterpost->postWithAPI(array('status'=>$status));
		}
		// post to Twitter //
		
		
		// post to Vkontakte //
		if(Configuration::get($name.'vkpost_on') && $is_psvkpost){
			require_once(dirname(__FILE__).'/../../../modules/psvkpost/psvkpost.php');
			$obj_psvkpost = new psvkpost();	
			
			$status = $customer_name.' '.Configuration::get($name.'vkdesc'.'_'.$id_lang).': '.$stars.' - '.$product_name.' - '.$product_link;
			$obj_psvkpost->postWithAPI(array('status'=>$status,'image'=>$picture,'product_url'=>$product_link));
		}
		// post to Vkontakte //
		
	}
	
	public function postsSettings($data){
    	$_html = '';
    	$title = $data['translate']['title']; 
    	$hint1 = $data['translate']['hint1']; 
    	$hint2 = $data['translate']['hint2']; 

    	$title_pstwitterpost = $data['translate']['title_pstwitterpost'];
    	$title_psvkpost = $data['translate']['title_psvkpost'];
    	$buy_module_psvkpost = $data['translate']['buy_module_psvkpost'];
    	$buy_module_pstwitterpost = $data['translate']['buy_module_pstwitterpost'];
    	

    	$data_requrements = $this->checkrequirements();
    	$is_pstwitterpost = $data_requrements['pstwitterpost'];
    	$is_psvkpost = $data_requrements['psvkpost'];


        if(version_compare(_PS_VERSION_, '1.6', '>')){
            $_html .= '<div class="panel">
				       <div class="panel-heading"><i class="fa fa-facebook fa-lg"></i>&nbsp;'.$title.'</div>';
        } else {
            $_html .= '<h3 class="title-block-content"><i class="fa fa-facebook fa-lg"></i>&nbsp;'.$title.'</h3>';

        }

    	
    	$_html .= '<div style="padding: 10px; border: 1px solid rgb(129, 207, 230); font-size: 13px;">
    			  '.$hint1. 
    			  $hint2.'
    			  </div>';
    	
    	
    	
    	$_html .= '<br/><div style="clear:both"></div><br/>';
    	
    	

    	if($is_pstwitterpost){
            if(version_compare(_PS_VERSION_, '1.6', '>')){
                $_html .= $this->_pstwitterform16($data);
            } else {
                $_html .= '<h3 class="title-block-content">'.$title_pstwitterpost.'</h3>';
                $_html .= $this->_pstwitterform13_14_15($data);
            }
    	
    	} else {
            $_html .= '<h3 class="title-block-content">'.$title_pstwitterpost.'</h3>';
            $_html .= '<div style="padding: 10px; border: 1px solid red; font-size: 13px;">
    			  '.$buy_module_pstwitterpost.'
    			  </div>';
    	}

        $_html .= '<br/><div style="clear:both"></div><br/>';
    	

    	if($is_psvkpost){
            if(version_compare(_PS_VERSION_, '1.6', '>')){
                $_html .= $this->_psvkform16($data);
            } else {
                $_html .= '<h3 class="title-block-content">'.$title_psvkpost.'</h3>';
                $_html .= $this->_psvkform13_14_15($data);
            }

    	} else {
            $_html .= '<h3 class="title-block-content">'.$title_psvkpost.'</h3>';
    		$_html .= '<div style="padding: 10px; border: 1px solid red; font-size: 13px;">
    			  '.$buy_module_psvkpost.'
    			  </div>';
    	}

        if(version_compare(_PS_VERSION_, '1.6', '>')){
            $_html .= '</div>';
        }


        return $_html;
    }

    private function _psvkform16($data){
        require_once(dirname(__FILE__).'/../gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();
        return $obj_gsnipreview->psvkform16($data);

    }

    private function _psvkform13_14_15($data){
        $update_button = $data['translate']['update_button'];
        $enable_psvkpost = $data['translate']['enable_psvkpost'];

        $template_text = $data['translate']['template_text'];

        $form_action = $data['translate']['form_action'];

        $name = $data['translate']['name'];

        $_html = '';
        $divLangName = "vkdesc";
        $_html .= '<form method="post" action="'.$form_action.'">';
        $_html .= '<label style="width:29%">'.$enable_psvkpost.':</label>
				<div class="margin-form">

					<input type="radio" value="1" id="text_list_on" name="vkpost_on"
							'.(Tools::getValue('vkpost_on', Configuration::get($name.'vkpost_on')) ? 'checked="checked" ' : '').'>
					<label for="dhtml_on" class="t">
						<img alt="Enabled" title="Enabled" src="../img/admin/enabled.gif">
					</label>

					<input type="radio" value="0" id="text_list_off" name="vkpost_on"
						   '.(!Tools::getValue('vkpost_on', Configuration::get($name.'vkpost_on')) ? 'checked="checked" ' : '').'>
					<label for="dhtml_off" class="t">
						<img alt="Disabled" title="Disabled" src="../img/admin/disabled.gif">
					</label>
				</div>';


        $_html .= '<label style="width:29%">'.$template_text.':</label>
				<div class="margin-form" style="font-size: 13px!important">
				<div>
					<span style="float:left;margin-right:5px">{John. D.}</span>';

        $_html .= '<span style="float:left;margin-right:5px">';
        $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
        $languages = Language::getLanguages(false);

        foreach ($languages as $language){
            $id_lng = (int)$language['id_lang'];
            $coupondesc = Configuration::get($name.'vkdesc'.'_'.$id_lng);


            $_html .= '	<div id="vkdesc_'.$language['id_lang'].'"
							 style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;"
							 >

						<input type="text" style="width:400px"
								  id="vkdesc_'.$language['id_lang'].'"
								  name="vkdesc_'.$language['id_lang'].'"
								  value="'.htmlentities(Tools::stripslashes($coupondesc), ENT_COMPAT, 'UTF-8').'"/>
						</div>';
        }
        $_html .= '</span>';


        $_html .= '<span style="float:left;margin-right:5px"> : ★★★★☆ - {Product name} - {Product URL}</span>';

        ob_start();
        $this->displayFlags($languages, $defaultLanguage, $divLangName, 'vkdesc');
        $displayflags = ob_get_clean();
        $_html .= $displayflags;
        $_html .= '<div style="clear:both"></div>';


        $_html .= '</div>';

        $_html .= '</div>';

        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">
						<input type="submit" name="psvkpostsettings" value="'.$update_button.'"
	                		   class="button"  />
	                	</p>';

        $_html .= '</form>';
        return $_html;
    }

    private function _pstwitterform16($data){
        require_once(dirname(__FILE__).'/../gsnipreview.php');
        $obj_gsnipreview = new gsnipreview();
        return $obj_gsnipreview->pstwitterform16($data);

    }



    private function _pstwitterform13_14_15($data){
        $update_button = $data['translate']['update_button'];
        $enable_pstwitterpost = $data['translate']['enable_pstwitterpost'];

        $template_text = $data['translate']['template_text'];

        $form_action = $data['translate']['form_action'];

        $name = $data['translate']['name'];

        $_html = '';
        $divLangName = "twdesc";
        $_html .= '<form method="post" action="'.$form_action.'">';

        $_html .= '<label style="width:29%">'.$enable_pstwitterpost.':</label>
				<div class="margin-form">

					<input type="radio" value="1" id="text_list_on" name="twpost_on"
							'.(Tools::getValue('twpost_on', Configuration::get($name.'twpost_on')) ? 'checked="checked" ' : '').'>
					<label for="dhtml_on" class="t">
						<img alt="Enabled" title="Enabled" src="../img/admin/enabled.gif">
					</label>

					<input type="radio" value="0" id="text_list_off" name="twpost_on"
						   '.(!Tools::getValue('twpost_on', Configuration::get($name.'twpost_on')) ? 'checked="checked" ' : '').'>
					<label for="dhtml_off" class="t">
						<img alt="Disabled" title="Disabled" src="../img/admin/disabled.gif">
					</label>


				</div>';

        $_html .= '<label style="width:29%">'.$template_text.':</label>
				<div class="margin-form" style="font-size: 13px!important">
				<div>
					<span style="float:left;margin-right:5px">{John. D.}</span>';

        $_html .= '<span style="float:left;margin-right:5px">';
        $defaultLanguage = (int)(Configuration::get('PS_LANG_DEFAULT'));
        $languages = Language::getLanguages(false);

        foreach ($languages as $language){
            $id_lng = (int)$language['id_lang'];
            $coupondesc = Configuration::get($name.'twdesc'.'_'.$id_lng);


            $_html .= '	<div id="twdesc_'.$language['id_lang'].'"
							 style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;"
							 >

						<input type="text" style="width:400px"
								  id="twdesc_'.$language['id_lang'].'"
								  name="twdesc_'.$language['id_lang'].'"
								  value="'.htmlentities(Tools::stripslashes($coupondesc), ENT_COMPAT, 'UTF-8').'"/>
						</div>';
        }
        $_html .= '</span>';


        $_html .= '<span style="float:left;margin-right:5px"> : ★★★★☆ - {Product name} - {Product URL}</span>';

        ob_start();
        $this->displayFlags($languages, $defaultLanguage, $divLangName, 'twdesc');
        $displayflags = ob_get_clean();
        $_html .= $displayflags;
        $_html .= '<div style="clear:both"></div>';


        $_html .= '</div>';

        $_html .= '</div>';

        $_html .= '<p class="center" style="padding: 10px; margin-bottom: 20px;">
					<input type="submit" name="pstwitterpostsettings" value="'.$update_button.'"
                		   class="button"  />
                	</p>';

        $_html .= '</form>';
        return $_html;
    }

	public function checkrequirements(){
    		// Vkontakte Wall Post
    		$is_on_psvkpost = 0;
    		
    		if (file_exists(dirname(__FILE__).'/../../../modules/psvkpost/psvkpost.php')) 
			{
                if(version_compare(_PS_VERSION_, '1.5', '>')) {
                    $_is_psvkpost_active = Module::isEnabled('psvkpost');
                    if ($_is_psvkpost_active)
                        $is_on_psvkpost = 1;
                } else {
                    $is_on_psvkpost = 1;
                }
			}
			
			// Twitter Wall Post
    		$is_on_pstwitterpost = 0;
    		if (file_exists(dirname(__FILE__).'/../../../modules/pstwitterpost/pstwitterpost.php')) 
			{
                if(version_compare(_PS_VERSION_, '1.5', '>')) {
				$_is_psvkpost_active = Module::isEnabled('pstwitterpost');
				if($_is_psvkpost_active)
					$is_on_pstwitterpost = 1;
                } else {
                    $is_on_pstwitterpost = 1;
                }
			}
			
			return array('psvkpost'=>$is_on_psvkpost,'pstwitterpost'=>$is_on_pstwitterpost);
    }
}