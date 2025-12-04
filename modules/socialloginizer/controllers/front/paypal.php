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

class SocialLoginizerPaypalModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		parent::initContent();
		$platform = Tools::getValue('type');

		$platform = trim($platform);

		//@session_start();
		//unset($_SESSION['OAUTH_ACCESS_TOKEN']['https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/tokenservice']);
		if ($platform == 'pay')
			$this->paypalLogin();

//$user_data = $this->paypal_login();
		$settings = Configuration::get('VELOCITY_SOCIAL_LOGINIZER');
		$loginizer_data = unserialize($settings);
		$code = Tools::getValue('code');

		$c_id = $loginizer_data['pay']['client_id'];
		$c_sec = $loginizer_data['pay']['client_secret'];

		if (isset($code) && $code != '')
		{
			$pay_link = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/tokenservice?client_id=';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $pay_link.$c_id.'&client_secret='.$c_sec.'&grant_type=authorization_code&code='.$code);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result1 = curl_exec($ch);
			curl_close($ch);
			$token = Tools::jsonDecode($result1);
			if (isset($token->access_token) && $token->access_token != '')
			{
				$pay_link2 = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/userinfo?schema=openid&access_token=';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $pay_link2.$token->access_token);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$result2 = curl_exec($ch);
				curl_close($ch);
				$data = Tools::jsonDecode($result2);
			}

			if (count($data) > 0)
			{
				$social_data = array();
				$social_data['first_name'] = $data->given_name;
				$social_data['last_name'] = $data->family_name;
				$social_data['email'] = $data->email;
				$social_data['gender'] = 0;
				$social_data['username'] = $data->given_name;
				$obj = new SocialLoginizer();

				$result = $obj->addUser($social_data, 'Paypal');
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
			echo '<script>window.close();</script>';
	}

	public function paypalLogin()
	{
		$settings = Configuration::get('VELOCITY_SOCIAL_LOGINIZER');
		$loginizer_data = unserialize($settings);
		$user = '';
		$client = new oauth_client_class;
		$client->debug = true;
		$client->debug_http = true;
		$client->server = 'Paypal';
		$client->redirect_uri = $this->context->link->getModuleLink('socialloginizer', 'paypal');
		$client->scope = 'profile email';
		$client->client_id = $loginizer_data['pay']['client_id'];
		$client->client_secret = $loginizer_data['pay']['client_secret'];

		$lang_str = '&id_lang='.$this->context->language->id;
		$client->redirect_uri = str_replace($lang_str, '', $client->redirect_uri);

		$lang_str = '/'.$this->context->language->iso_code.'/';
		$client->redirect_uri = str_replace($lang_str, '/', $client->redirect_uri);

		if (Tools::strlen($client->client_id) == 0 || Tools::strlen($client->client_secret) == 0)
				Tools::redirect($this->context->link->getModuleLink('socialloginizer', 'credentials'));
		else
		{
			if (($success = $client->Initialize()))
			{
				$success1 = $client->Process();
				if ($success1)
				{
					if (Tools::strlen($client->access_token))
					{
						$success = $client->CallAPI('https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/userinfo',
'GET', array(), array('FailOnAccessError'=>true), $user);
						return $user;
					}
					else
						return $client->authorization_error;
				}
				else
					return $client->error;

				$success = $client->Finalize($success);
			}
		}
		if ($client->exit)
			exit;
	}
}
?>
