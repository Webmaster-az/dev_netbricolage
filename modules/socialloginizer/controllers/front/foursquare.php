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

class SocialLoginizerFoursquareModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		parent::initContent();
		$platform = Tools::getValue('type');
		$platform = trim($platform);

		if ($platform == 'foursquare')
			$user_data = $this->foursquareLogin();

		if (empty($user_data))
			$user_data = $this->foursquareLogin();

		if (count($user_data) > 0)
		{
			$social_data = array();
			$social_data['first_name'] = $user_data->response->user->firstName;
			$social_data['last_name'] = $user_data->response->user->lastName;
			$social_data['email'] = $user_data->response->user->contact->email;
			$social_data['gender'] = ($user_data->response->user->gender == 'male')? 0: 1;
			$social_data['username'] = $user_data->response->user->firstName;
			$obj = new SocialLoginizer();
			$result = $obj->addUser($social_data, 'Foursquare');
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

	public function foursquareLogin()
	{
		$settings = Configuration::get('VELOCITY_SOCIAL_LOGINIZER');
		$loginizer_data = unserialize($settings);
		$user = '';
		$client = new oauth_client_class;
		$client->server = 'Foursquare';

		$client->offline = true;

		$client->debug = false;
		$client->debug_http = true;
		$client->redirect_uri = $this->context->link->getModuleLink('socialloginizer', 'foursquare');

		$lang_str = '&id_lang='.$this->context->language->id;
		$client->redirect_uri = str_replace($lang_str, '', $client->redirect_uri);

		$lang_str = '/'.$this->context->language->iso_code.'/';
		$client->redirect_uri = str_replace($lang_str, '/', $client->redirect_uri);

		$client->client_id = $loginizer_data['foursquare']['client_id'];
		$client->client_secret = $loginizer_data['foursquare']['client_secret'];

		if (Tools::strlen($client->client_id) == 0 || Tools::strlen($client->client_secret) == 0)
				Tools::redirect($this->context->link->getModuleLink('socialloginizer', 'credentials'));

		/* API permissions
		*/
		$client->scope = '';
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
					'https://api.foursquare.com/v2/users/self?v=20131013', 'GET', array(), array('FailOnAccessError' => true), $user);
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
