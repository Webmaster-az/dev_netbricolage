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

include_once(_PS_MODULE_DIR_.'socialloginizer/libraries/http.php');
include_once(_PS_MODULE_DIR_.'socialloginizer/libraries/oauth_client.php');

class SocialLoginizerTwitterModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		parent::initContent();
		$flag = 0;
		$platform = Tools::getValue('type');
		$platform = trim($platform);
		if ($platform == 'tweet')
			$user_data = $this->twitterLogin();
		if (Tools::isSubmit('oauth_token'))
			$user_data = $this->twitterLogin();

		if (Tools::isSubmit('login_email'))
		{
			$email = Tools::getValue('login_email');
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
				$this->setTemplate('twitter-email.tpl');
			}
			else
			{
				$user_data = $this->twitterLogin();
				if (!empty($user_data))
				{
					$unique_id = $user_data->id;
					$sql = 'insert into '._DB_PREFIX_.'socialloginizer_mapping (email,unique_id) values ("'.pSQL($email).'","'.pSQL($unique_id).'")';
					Db::getInstance()->execute($sql);
					$name = explode(' ', $user_data->name);
					$lastname = '';
					$name_size = count($name);
					if (count($name) > 1)
					{
						for ($namelength = 1; $namelength < $name_size; $namelength++)
						{
							if ($lastname == '')
								$lastname = $name[$namelength];
							else
								$lastname = $lastname.' '.$name[$namelength];
						}
					}
					$social_data = array();
					$social_data['first_name'] = $name[0];
					if ($lastname == '')
						$lastname = $name[0];
					$social_data['last_name'] = $lastname;
					$social_data['email'] = $email;
					$social_data['gender'] = 0;
					$social_data['username'] = $user_data->name;
					$obj = new SocialLoginizer();
					$result = $obj->addUser($social_data, 'Twitter');
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
		else if (!empty($user_data))
		{
			$query = 'select email from '._DB_PREFIX_.'socialloginizer_mapping where unique_id="'.pSQL($user_data->id).'"';
			$result = Db::getInstance()->executeS($query);
			if (count($result) == 0)
				$flag = 1;
			if ($flag == 0)
			{
				$name = explode(' ', $user_data->name);
				$lastname = '';
				$name_size = count($name);
				if (count($name) > 1)
				{
					for ($namelength = 1; $namelength < $name_size; $namelength++)
					{
						if ($lastname == '')
							$lastname = $name[$namelength];
						else
							$lastname = $lastname.' '.$name[$namelength];
					}
				}
				$social_data = array();

				$social_data['first_name'] = $name[0];
				if ($lastname == '')
						$lastname = $name[0];
				$social_data['last_name'] = $lastname;
				$social_data['email'] = $result[0]['email'];
				$social_data['gender'] = 0;
				$social_data['username'] = $user_data->name;
				$uemail = $result[0]['email'];
				$obj = new SocialLoginizer();
				$result = $obj->addUser($social_data, 'Twitter');
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
			{
				if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
					$custom_ssl_var = 1;
				if ((bool)Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1)
					$module_dir = _PS_BASE_URL_SSL_.__PS_BASE_URI__.str_replace(_PS_ROOT_DIR_.'/', '', _PS_MODULE_DIR_);
				else
					$module_dir = _PS_BASE_URL_.__PS_BASE_URI__.str_replace(_PS_ROOT_DIR_.'/', '', _PS_MODULE_DIR_);

				$this->context->smarty->assign('modulepath', $module_dir);
				$this->setTemplate('twitter-email.tpl');
			}
		}
		else
			echo '<script>window.close();</script>';
	}

	public function twitterLogin()
	{
		$settings = Configuration::get('VELOCITY_SOCIAL_LOGINIZER');
		$loginizer_data = unserialize($settings);
		$user = '';

		$client = new oauth_client_class;
		$client->debug = 1;
		$client->debug_http = 1;
		$client->server = 'Twitter';
		$client->redirect_uri = $this->context->link->getModuleLink('socialloginizer', 'twitter');

		$lang_str = '&id_lang='.$this->context->language->id;
		$client->redirect_uri = str_replace($lang_str, '', $client->redirect_uri);

		$lang_str = '/'.$this->context->language->iso_code.'/';
		$client->redirect_uri = str_replace($lang_str, '/', $client->redirect_uri);

		$client->client_id = $loginizer_data['twitter']['client_id'];
		$client->client_secret = $loginizer_data['twitter']['client_secret'];

		if (Tools::strlen($client->client_id) == 0 || Tools::strlen($client->client_secret) == 0)
				Tools::redirect($this->context->link->getModuleLink('socialloginizer', 'credentials'));

		if (($success = $client->Initialize()))
		{
			if (($success = $client->Process()))
			{
				if (Tools::strlen($client->access_token))
				{
					$success = $client->CallAPI('https://api.twitter.com/1.1/account/verify_credentials.json',
'GET', array(), array('FailOnAccessError'=>true), $user);
					return $user;
				}
			}
			$success = $client->Finalize($success);
		}
		if ($client->exit)
			exit;
	}
}
?>
