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

class SocialLoginizerLinkedinModuleFrontController extends ModuleFrontController
{
	public function initContent()
	{
		parent::initContent();
		$platform = Tools::getValue('type');
		$platform = trim($platform);

		if ($platform == 'linked')
			$user_data = $this->linkedinLogin();

		if (Tools::isSubmit('oauth_token'))
			$user_data = $this->linkedinLogin();

		if (count($user_data) > 0)
		{
			$social_data = array();
			$social_data['first_name'] = $user_data[1]->firstName;
			$social_data['last_name'] = $user_data[1]->lastName;
			$social_data['email'] = $user_data[0];
			$social_data['gender'] = 0;
			$social_data['username'] = $user_data[1]->first_name;
			$obj = new SocialLoginizer();
			$result = $obj->addUser($social_data, 'Linkedin');
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

	public function linkedinLogin()
	{
		$settings = Configuration::get('VELOCITY_SOCIAL_LOGINIZER');
		$loginizer_data = unserialize($settings);
		$user_email = '';
		$user = '';

		$client = new oauth_client_class;
		$client->debug = 1;
		$client->debug_http = 1;
		$client->server = 'LinkedIn';
		$client->redirect_uri = $this->context->link->getModuleLink('socialloginizer', 'linkedin');

		$lang_str = '&id_lang='.$this->context->language->id;
		$client->redirect_uri = str_replace($lang_str, '', $client->redirect_uri);

		$lang_str = '/'.$this->context->language->iso_code.'/';
		$client->redirect_uri = str_replace($lang_str, '/', $client->redirect_uri);

		$client->client_id = $loginizer_data['linked']['client_id'];
		$client->client_secret = $loginizer_data['linked']['client_secret'];

		/*  API permission scopes
		*  Separate scopes with a space, not with +
		*/
		$client->scope = 'r_emailaddress';

		if (Tools::strlen($client->client_id) == 0 || Tools::strlen($client->client_secret) == 0)
			Tools::redirect($this->context->link->getModuleLink('socialloginizer', 'credentials'));

		if (($success = $client->Initialize()))
		{
			if (($success = $client->Process()))
			{
				if (Tools::strlen($client->access_token))
				{
					$success = $client->CallAPI('http://api.linkedin.com/v1/people/~/email-address', 'GET',
array('format' => 'json'), array('FailOnAccessError' => true), $user_email);
$success = $client->CallAPI('http://api.linkedin.com/v1/people/~', 'GET', array('format' => 'json'), array('FailOnAccessError' => true), $user);
					return $user = array($user_email, $user);
				}
			}
			$success = $client->Finalize($success);
		}
		if ($client->exit)
			exit;
		if (Tools::strlen($client->authorization_error))
		{
			$client->error = $client->authorization_error;
			$success = false;
		}
	}
}
?>
