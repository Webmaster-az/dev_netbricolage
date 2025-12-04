<?php
/**
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store. 
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2015 Knowband
* @license   see file: LICENSE.txt
*/

class SocialLoginizerVkModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		parent::initContent();
		$settings = Configuration::get('VELOCITY_SOCIAL_LOGINIZER');
		$loginizer_data = unserialize($settings);
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
			$custom_ssl_var = 1;

		if ((bool)Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1)
			$redirect_uri = $this->context->link->getModuleLink('socialloginizer', 'vk', array(), true);
		else
			$redirect_uri = $this->context->link->getModuleLink('socialloginizer', 'vk');

		$lang_str = '&id_lang='.$this->context->language->id;
		$redirect_uri = str_replace($lang_str, '', $redirect_uri);
		$lang_str = '/'.$this->context->language->iso_code.'/';
		$redirect_uri = str_replace($lang_str, '/', $redirect_uri);
		$url_replace = 'index.php?fc=module&module=socialloginizer&controller=vk';
		$redirect_uri = str_replace($url_replace, 'module/socialloginizer/vk', $redirect_uri);
		define('CLIENT_ID', $loginizer_data['vk']['client_id']);
		define('CLIENT_SECRET', $loginizer_data['vk']['client_secret']);
		define('REDIRECT_URI', $redirect_uri);
		define('SCOPE', 'email');
		$flag = 0;
		$platform = Tools::getValue('type');
		$code = Tools::getValue('code');
		if ($platform == 'vk')
		{
			$dialog_url = 'https://oauth.vk.com/authorize?client_id='.CLIENT_ID.'&scope='.SCOPE.'&redirect_uri='.REDIRECT_URI.'&response_type=code';
			Tools::redirect($dialog_url);
		}
		if (Tools::isSubmit('login_email'))
		{
			$email = Tools::getValue('login_email');
			$email = strip_tags($email);
			if (Customer::customerExists($email))
			{
				$errormsg = $this->module->l('Email already exist please choose another one');
				$this->context->smarty->assign('errormsg', $errormsg);
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
					$custom_ssl_var = 1;
				if ((bool)Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1)
					$module_dir = _PS_BASE_URL_SSL_.__PS_BASE_URI__.str_replace(_PS_ROOT_DIR_.'/', '', _PS_MODULE_DIR_);
				else
					$module_dir = _PS_BASE_URL_.__PS_BASE_URI__.str_replace(_PS_ROOT_DIR_.'/', '', _PS_MODULE_DIR_);
				$this->context->smarty->assign('modulepath', $module_dir);
				$this->setTemplate('vk-email.tpl');
			}
			else
			{
				$user_data = $this->vkGetUserDetails($this->context->cookie->user_id, $this->context->cookie->access_token);
				if (count($user_data) > 0)
				{
					$social_data = array();
					$unique_id = $this->context->cookie->user_id;
					$sql = 'insert into '._DB_PREFIX_.'socialloginizer_mapping (email,unique_id) values ("'.pSQL($email).'","'.pSQL($unique_id).'")';

					Db::getInstance()->execute($sql);
					$social_data['first_name'] = $user_data['response'][0]['first_name'];
					$social_data['last_name'] = $user_data['response'][0]['last_name'];
					$social_data['email'] = $email;
					$social_data['gender'] = 0;
					$social_data['username'] = $user_data['response'][0]['first_name'];
//					$uname = $user_data['response'][0]['first_name'];
					$obj = new SocialLoginizer();

					$result = $obj->addUser($social_data, 'Vk');
					if ($result == 1)
					{
						$settings = Configuration::get('VELOCITY_SOCIAL_LOGINIZER');
						$loginizer_data = unserialize($settings);
						if ($loginizer_data['show_popup'] == 1)
						{
							echo '<script> window.opener.location.reload(true);
							window.close();</script>';
						}
						else
						{
							if (isset($loginizer_data['redirect_url']) && $loginizer_data['redirect_url'] != '')
							{
								if (!filter_var($loginizer_data['redirect_url'], FILTER_VALIDATE_URL) === false)
									Tools::redirect($loginizer_data['redirect_url']);
								else
									Tools::redirect('index.php');
							}
							else
								Tools::redirect('index.php');
						}
					}
					else
						Tools::redirect($this->context->link->getModuleLink('socialloginizer', 'error'));
				}
			}
		}
		else if (isset($code))
		{
			$access_token_url = 'https://api.vk.com/oauth/access_token?client_id='.
				CLIENT_ID.'&client_secret='.CLIENT_SECRET.'&code='.$code.'&redirect_uri='.REDIRECT_URI;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $access_token_url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result1 = curl_exec($ch);
			curl_close($ch);
			$token = Tools::jsonDecode($result1, true);
			$this->context->cookie->user_id = $token['user_id'];
			$this->context->cookie->access_token = $token['access_token'];
			$query = 'select email from '._DB_PREFIX_.'socialloginizer_mapping where unique_id="'.pSQL($token['user_id']).'"';
			$result = Db::getInstance()->executeS($query);
			if (!isset($token['email']) && count($result) == 0)
				$flag = 1;
			if ($flag == 0)
			{
				if (isset($token['email']))
					$email = $token['email'];
				else
					$email = $result[0]['email'];
				$user_data = $this->vkGetUserDetails($token['user_id'], $token['access_token']);
				if (count($user_data) > 0)
				{
					$social_data = array();
					$social_data['first_name'] = $user_data['response'][0]['first_name'];
					$social_data['last_name'] = $user_data['response'][0]['last_name'];
					$social_data['email'] = $email;
					$social_data['gender'] = 0;
					$social_data['username'] = $user_data['response'][0]['first_name'];
//					$uname = $user_data['response'][0]['first_name'];
					$obj = new SocialLoginizer();
					$result = $obj->addUser($social_data, 'Vk');
					if ($result == 1)
					{
						$settings = Configuration::get('VELOCITY_SOCIAL_LOGINIZER');
						$loginizer_data = unserialize($settings);
						if ($loginizer_data['show_popup'] == 1)
						{
							echo '<script> window.opener.location.reload(true);
							window.close();</script>';
						}
						else
						{
							if (isset($loginizer_data['redirect_url']) && $loginizer_data['redirect_url'] != '')
							{
								if (!filter_var($loginizer_data['redirect_url'], FILTER_VALIDATE_URL) === false)
									Tools::redirect($loginizer_data['redirect_url']);
								else
									Tools::redirect('index.php');
							}
							else
								Tools::redirect('index.php');
						}
					}
					else
						Tools::redirect($this->context->link->getModuleLink('socialloginizer', 'error'));
				}
				else
					echo '<script>window.close();</script>';
			}
			else
			{
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
					$custom_ssl_var = 1;
				if ((bool)Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1)
					$module_dir = _PS_BASE_URL_SSL_.__PS_BASE_URI__.str_replace(_PS_ROOT_DIR_.'/', '', _PS_MODULE_DIR_);
				else
					$module_dir = _PS_BASE_URL_.__PS_BASE_URI__.str_replace(_PS_ROOT_DIR_.'/', '', _PS_MODULE_DIR_);
				$this->context->smarty->assign('modulepath', $module_dir);
				$this->setTemplate('vk-email.tpl');
			}
		}
	}
	public function vkGetUserDetails($uid, $access_token)
	{
		$call_api = 'https://api.vk.com/method/users.get?uids='.$uid.'&access_token='.$access_token;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $call_api);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		$user = Tools::jsonDecode($result, true);
		return $user;
	}
}