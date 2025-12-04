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
include_once(_PS_MODULE_DIR_.'socialloginizer/libraries/mailchimpl library.php');

class SocialLoginizerFacebookModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		parent::initContent();

		$platform = Tools::getValue('type');
		$platform = trim($platform);

		if ($platform == 'fb')
			$user_data = $this->facebookLogin();
		elseif (Tools::isSubmit('code'))
			$user_data = $this->facebookLogin();

		if (count($user_data) > 0)
		{
			$social_data = array();
			$social_data['first_name'] = $user_data->first_name;
			$social_data['last_name'] = $user_data->last_name;
			$social_data['email'] = $user_data->email;
			$social_data['gender'] = ($user_data->gender == 'male')? 0: 1;
			$social_data['username'] = $user_data->first_name;
			$obj = new SocialLoginizer();

			$result = $obj->addUser($social_data, 'Facebook');
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

	public function facebookLogin()
	{
		$settings = Configuration::get('VELOCITY_SOCIAL_LOGINIZER');
		$loginizer_data = unserialize($settings);

		$user = '';
		$client = new oauth_client_class;
		$client->debug = false;
		$client->debug_http = true;
		$client->server = 'Facebook';
		$client->redirect_uri = $this->context->link->getModuleLink('socialloginizer', 'facebook');

		$lang_str = '&id_lang='.$this->context->language->id;
		$client->redirect_uri = str_replace($lang_str, '', $client->redirect_uri);

		$lang_str = '/'.$this->context->language->iso_code.'/';
		$client->redirect_uri = str_replace($lang_str, '/', $client->redirect_uri);

		$client->client_id = $loginizer_data['facebook']['app_id'];
		$client->client_secret = $loginizer_data['facebook']['app_secret'];

		if (Tools::strlen($client->client_id) == 0 || Tools::strlen($client->client_secret) == 0)
				Tools::redirect($this->context->link->getModuleLink('socialloginizer', 'credentials'));

		/* API permissions
		*/
		$client->scope = 'email';
		//$client->scope = 'user_birthday'; use this scope to get whole profile information along with birth date
		if (($success = $client->Initialize()))
		{
			if (($success = $client->Process()))
			{
				if (Tools::strlen($client->access_token))
				{
					$success = $client->CallAPI('https://graph.facebook.com/me?fields=email,first_name,last_name,gender',
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
