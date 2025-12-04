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

class SocialLoginizerGithubModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		parent::initContent();
		$flag = 0;
		$platform = Tools::getValue('type');
		$platform = trim($platform);

		if ($platform == 'github')
			$user_data = $this->githubLogin();

		if (empty($user_data))
			$user_data = $this->githubLogin();
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
				$this->setTemplate('github-email.tpl');
			}
			else
			{
				$user_data = $this->githubLogin();
				if (count($user_data) > 0)
				{
					$social_data = array();
					$unique_id = $user_data->login;
					$sql = 'insert into '._DB_PREFIX_.'socialloginizer_mapping (email,unique_id) values ("'.pSQL($email).'","'.pSQL($unique_id).'")';

					Db::getInstance()->execute($sql);
					$user_data->login = preg_replace('#[0-9 ]*#', '', $user_data->login);
					$social_data['first_name'] = $user_data->login;
					$social_data['last_name'] = $user_data->login;
					$social_data['email'] = $email;
					$social_data['gender'] = 0;
					$social_data['username'] = $user_data->login;
					$obj = new SocialLoginizer();
					$result = $obj->addUser($social_data, 'Github');
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
		else if (count($user_data) > 0)
		{
			$social_data = array();
			$query = 'select email from '._DB_PREFIX_.'socialloginizer_mapping where unique_id="'.pSQL($user_data->login).'"';
			$result = Db::getInstance()->executeS($query);
			if (empty($user_data->email) && count($result) == 0)
				$flag = 1;

			if ($flag == 0)
			{
				if (!empty($user_data->email))
					$email = $user_data->email;
				else
					$email = $result[0]['email'];
				$user_data->login = preg_replace('#[0-9 ]*#', '', $user_data->login);
				$social_data['first_name'] = $user_data->login;
				$social_data['last_name'] = $user_data->login;
				$social_data['email'] = $email;
				$social_data['gender'] = 0;
				$social_data['username'] = $user_data->login;
				$obj = new SocialLoginizer();

				$result = $obj->addUser($social_data, 'Github');
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
				$this->setTemplate('github-email.tpl');
			}
		}
		else
			echo '<script>window.close();</script>';
	}

	public function githubLogin()
	{
		$settings = Configuration::get('VELOCITY_SOCIAL_LOGINIZER');
		$loginizer_data = unserialize($settings);
		$user = '';
		$client = new oauth_client_class;
		$client->server = 'github';

		$client->offline = true;

		$client->debug = false;
		$client->debug_http = true;
		$client->redirect_uri = $this->context->link->getModuleLink('socialloginizer', 'github');

		$lang_str = '&id_lang='.$this->context->language->id;
		$client->redirect_uri = str_replace($lang_str, '', $client->redirect_uri);

		$lang_str = '/'.$this->context->language->iso_code.'/';
		$client->redirect_uri = str_replace($lang_str, '/', $client->redirect_uri);

		$client->client_id = $loginizer_data['github']['client_id'];
		$client->client_secret = $loginizer_data['github']['client_secret'];

		if (Tools::strlen($client->client_id) == 0 || Tools::strlen($client->client_secret) == 0)
				Tools::redirect($this->context->link->getModuleLink('socialloginizer', 'credentials'));

		/* API permissions
		*/
		$client->scope = 'user:email';
		if (($success = $client->Initialize()))
		{
			if (($success = $client->Process()))
			{
				if (Tools::strlen($client->authorization_error))
				{
					$client->error = $client->authorization_error;
					$success = false;
				}
				elseif (Tools::strlen($client->access_token))
				{
					$success = $client->CallAPI(
					'https://api.github.com/user', 'GET', array(), array('FailOnAccessError' => true), $user);
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
