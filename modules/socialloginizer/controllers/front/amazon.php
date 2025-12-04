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

class SocialLoginizerAmazonModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		parent::initContent();
		$platform = Tools::getValue('type');
		if ($platform == 'amazon')
			$user_data = $this->amazonLogin();

		if (empty($user_data))
			$user_data = $this->amazonLogin();

		if (count($user_data) > 0)
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
			$social_data['email'] = $user_data->email;
			$social_data['gender'] = 0;
			$social_data['username'] = $name[0];
			$obj = new SocialLoginizer();
			$result = $obj->addUser($social_data, 'Amazon');
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

	public function amazonLogin()
	{
		$settings = Configuration::get('VELOCITY_SOCIAL_LOGINIZER');
		$loginizer_data = unserialize($settings);
		$user = '';

		$client = new oauth_client_class;
		$client->server = 'Amazon';

		$client->debug = false;
		$client->debug_http = true;
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
			$custom_ssl_var = 1;

		if ((bool)Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1)
			$client->redirect_uri = $this->context->link->getModuleLink('socialloginizer', 'amazon', array(), true);
		else
			$client->redirect_uri = $this->context->link->getModuleLink('socialloginizer', 'amazon');

		$lang_str = '&id_lang='.$this->context->language->id;
		$client->redirect_uri = str_replace($lang_str, '', $client->redirect_uri);

		$lang_str = '/'.$this->context->language->iso_code.'/';
		$client->redirect_uri = str_replace($lang_str, '/', $client->redirect_uri);

		$client->client_id = $loginizer_data['amazon']['client_id'];
		$client->client_secret = $loginizer_data['amazon']['client_secret'];

		if (Tools::strlen($client->client_id) == 0 || Tools::strlen($client->client_secret) == 0)
				Tools::redirect($this->context->link->getModuleLink('socialloginizer', 'credentials'));

		/* API permissions
		*/
		$client->scope = 'profile';
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
					$success = $client->CallAPI('https://api.amazon.com/user/profile', 'GET', array(), array('FailOnAccessError'=>true), $user);
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
