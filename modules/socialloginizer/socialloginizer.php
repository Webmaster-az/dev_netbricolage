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

if (!defined('_PS_VERSION_'))
	exit;



class SocialLoginizer extends Module
{

	private $loginizer_settings = array();
	private $admin_path = '';
	private $back_theme = '';
	protected $error = array();

	public function __construct()
	{
		$this->name = 'socialloginizer';
		$this->tab = 'front_office_features';
		$this->version = '1.0.1';
		$this->author = 'Knowband';
		$this->need_instance = 0;
		$this->module_key = '82689daaae43afc85a3618967e219925';
		//$this->ps_versions_compliancy = array('min' => '1.6.0.1', 'max' => _PS_VERSION_);
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Free Social Login 15 in 1');
		$this->description = $this->l('Enables the customers to login to the website through various social platforms.');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}

	public function install()
	{
		if (Shop::isFeatureActive())
			Shop::setContext(Shop::CONTEXT_ALL);

		if (!parent::install() || !$this->registerHook('displayHeader')
			|| !$this->registerHook('displayMobileHeader'))
			return false;
//		if (Configuration::get('VELOCITY_SOCIAL_LOGINIZER'))
//			Configuration::deleteByName('VELOCITY_SOCIAL_LOGINIZER');

		if (!Configuration::get('VELOCITY_SOCIAL_LOGINIZER'))
		{
			$this->loginizer_settings = $this->getDefaultSettings();
			Configuration::updateGlobalValue('VELOCITY_SOCIAL_LOGINIZER', serialize($this->loginizer_settings));
		}

		$create_table = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'socialloginizer_mapping` (
			`id` int(11) NOT NULL auto_increment,
			`email` varchar(50) NOT NULL,
			`unique_id` varchar(50) NOT NULL,
			PRIMARY KEY  (`id`)
		      )';
		Db::getInstance()->execute($create_table);
		$create_statistics_table = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'socialloginizer_statistics` (
			`id` int(11) NOT NULL auto_increment,
			`customer_id` int(10) UNSIGNED NOT NULL,
			`username` varchar(25) NOT NULL,
			`email` varchar(50) NOT NULL,
			`account_type` varchar(20) NOT NULL,
			`user_login_count` int(11) NOT NULL DEFAULT "0",
			PRIMARY KEY  (`id`),
			FOREIGN KEY (`customer_id`) References '._DB_PREFIX_.'customer(`id_customer`) ON DELETE CASCADE
		      )';
		Db::getInstance()->execute($create_statistics_table);
		$database_name = Db::getInstance()->getValue('SELECT DATABASE()');
		$column_query = 'SELECT COLUMN_NAME
                            FROM INFORMATION_SCHEMA.COLUMNS
                            WHERE table_name = "'._DB_PREFIX_.'socialloginizer_statistics"
                            AND table_schema = "'.pSQL($database_name).'"
                            AND column_name = "user_login_count"';
		$col_exist = Db::getInstance()->executeS($column_query);

		if (count($col_exist) == 0)
		{
			$alter_statistics_table = 'ALTER TABLE `'._DB_PREFIX_.'socialloginizer_statistics` ADD  `user_login_count` int(11) NOT NULL DEFAULT "0"';
			Db::getInstance()->execute($alter_statistics_table);
		}

		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall()
			|| !$this->unregisterHook('displayHeader') || !$this->unregisterHook('displayMobileHeader'))
			return false;

		return true;
	}

	public function getContent()
	{
		$this->addBackOfficeMedia();
		$this->context->controller->addJs($this->_path.'views/js/bootstrap/bootstrap.min.js');
		$output = null;

		if (Tools::isSubmit('submit_form'))
		{
			$temp_default = $this->getDefaultSettings();
			$post_data = Tools::getValue('velocity_social');
			$post_data['plugin_id'] = $temp_default['plugin_id'];
			$post_data['version'] = $temp_default['version'];
			$temp_default['facebook']['app_id'] = trim($post_data['facebook']['app_id']);
			$temp_default['facebook']['app_secret'] = trim($post_data['facebook']['app_secret']);
			$temp_default['gplus']['client_id'] = trim($post_data['gplus']['client_id']);
			$temp_default['gplus']['client_secret'] = trim($post_data['gplus']['client_secret']);
			$temp_default['live']['client_id'] = trim($post_data['live']['client_id']);
			$temp_default['live']['client_secret'] = trim($post_data['live']['client_secret']);
			$temp_default['linked']['client_id'] = trim($post_data['linked']['client_id']);
			$temp_default['linked']['client_secret'] = trim($post_data['linked']['client_secret']);
			$temp_default['twitter']['client_id'] = trim($post_data['twitter']['client_id']);
			$temp_default['twitter']['client_secret'] = trim($post_data['twitter']['client_secret']);
			$temp_default['yahoo']['consumer_key'] = trim($post_data['yahoo']['consumer_key']);
			$temp_default['yahoo']['consumer_secret'] = trim($post_data['yahoo']['consumer_secret']);
			$temp_default['insta']['client_id'] = trim($post_data['insta']['client_id']);
			$temp_default['insta']['client_secret'] = trim($post_data['insta']['client_secret']);
			$temp_default['amazon']['client_id'] = trim($post_data['amazon']['client_id']);
			$temp_default['amazon']['client_secret'] = trim($post_data['amazon']['client_secret']);
			$temp_default['pay']['client_id'] = trim($post_data['pay']['client_id']);
			$temp_default['pay']['client_secret'] = trim($post_data['pay']['client_secret']);
			$temp_default['foursquare']['client_id'] = trim($post_data['foursquare']['client_id']);
			$temp_default['foursquare']['client_secret'] = trim($post_data['foursquare']['client_secret']);
			$temp_default['github']['client_id'] = trim($post_data['github']['client_id']);
			$temp_default['github']['client_secret'] = trim($post_data['github']['client_secret']);
			$temp_default['disqus']['client_id'] = trim($post_data['disqus']['client_id']);
			$temp_default['disqus']['client_secret'] = trim($post_data['disqus']['client_secret']);
			$temp_default['vk']['client_id'] = trim($post_data['vk']['client_id']);
			$temp_default['vk']['client_secret'] = trim($post_data['vk']['client_secret']);
			$temp_default['wordpress']['client_id'] = trim($post_data['wordpress']['client_id']);
			$temp_default['wordpress']['client_secret'] = trim($post_data['wordpress']['client_secret']);
			$temp_default['dropbox']['client_id'] = trim($post_data['dropbox']['client_id']);
			$temp_default['dropbox']['client_secret'] = trim($post_data['dropbox']['client_secret']);
			if (isset($post_data['enable']) && $post_data['enable'] == 1)
			{
				$temp_default['enable'] = $post_data['enable'];
				$temp_default['custom_css'] = $post_data['custom_css'];
			}
			if (isset($post_data['facebook']['enable']) && $post_data['facebook']['enable'] == 1)
			{
				$post_data['facebook']['app_id'] = trim($post_data['facebook']['app_id']);
				$post_data['facebook']['app_secret'] = trim($post_data['facebook']['app_secret']);
				$temp_default['facebook']['app_id'] = trim($post_data['facebook']['app_id']);
				$temp_default['facebook']['app_secret'] = trim($post_data['facebook']['app_secret']);
				$temp_default['facebook']['enable'] = $post_data['facebook']['enable'];
			}
			if (isset($post_data['gplus']['enable']) && $post_data['gplus']['enable'] == 1)
			{
				$post_data['gplus']['client_id'] = trim($post_data['gplus']['client_id']);
				$post_data['gplus']['client_secret'] = trim($post_data['gplus']['client_secret']);
				$temp_default['gplus']['client_id'] = trim($post_data['gplus']['client_id']);
				$temp_default['gplus']['client_secret'] = trim($post_data['gplus']['client_secret']);
				$temp_default['gplus']['enable'] = $post_data['gplus']['enable'];
			}
			if (isset($post_data['live']['enable']) && $post_data['live']['enable'] == 1)
			{
				$post_data['live']['client_id'] = trim($post_data['live']['client_id']);
				$post_data['live']['client_secret'] = trim($post_data['live']['client_secret']);
				$temp_default['live']['client_id'] = trim($post_data['live']['client_id']);
				$temp_default['live']['client_secret'] = trim($post_data['live']['client_secret']);
				$temp_default['live']['enable'] = $post_data['live']['enable'];
			}
			if (isset($post_data['linked']['enable']) && $post_data['linked']['enable'] == 1)
			{
				$post_data['linked']['client_id'] = trim($post_data['linked']['client_id']);
				$post_data['linked']['client_secret'] = trim($post_data['linked']['client_secret']);
				$temp_default['linked']['client_id'] = trim($post_data['linked']['client_id']);
				$temp_default['linked']['client_secret'] = trim($post_data['linked']['client_secret']);
				$temp_default['linked']['enable'] = $post_data['linked']['enable'];
			}
			if (isset($post_data['twitter']['enable']) && $post_data['twitter']['enable'] == 1)
			{
				$post_data['twitter']['client_id'] = trim($post_data['twitter']['client_id']);
				$post_data['twitter']['client_secret'] = trim($post_data['twitter']['client_secret']);
				$temp_default['twitter']['client_id'] = trim($post_data['twitter']['client_id']);
				$temp_default['twitter']['client_secret'] = trim($post_data['twitter']['client_secret']);
				$temp_default['twitter']['enable'] = $post_data['twitter']['enable'];
			}
			if (isset($post_data['yahoo']['enable']) && $post_data['yahoo']['enable'] == 1)
			{
				$post_data['yahoo']['consumer_key'] = trim($post_data['yahoo']['consumer_key']);
				$post_data['yahoo']['consumer_secret'] = trim($post_data['yahoo']['consumer_secret']);
				$temp_default['yahoo']['consumer_key'] = trim($post_data['yahoo']['consumer_key']);
				$temp_default['yahoo']['consumer_secret'] = trim($post_data['yahoo']['consumer_secret']);
				$temp_default['yahoo']['enable'] = $post_data['yahoo']['enable'];
			}
			if (isset($post_data['insta']['enable']) && $post_data['insta']['enable'] == 1)
			{
				$post_data['insta']['client_id'] = trim($post_data['insta']['client_id']);
				$post_data['insta']['client_secret'] = trim($post_data['insta']['client_secret']);
				$temp_default['insta']['client_id'] = trim($post_data['insta']['client_id']);
				$temp_default['insta']['client_secret'] = trim($post_data['insta']['client_secret']);
				$temp_default['insta']['enable'] = $post_data['insta']['enable'];
			}
			if (isset($post_data['amazon']['enable']) && $post_data['amazon']['enable'] == 1)
			{
				$post_data['amazon']['client_id'] = trim($post_data['amazon']['client_id']);
				$post_data['amazon']['client_secret'] = trim($post_data['amazon']['client_secret']);
				$temp_default['amazon']['client_id'] = trim($post_data['amazon']['client_id']);
				$temp_default['amazon']['client_secret'] = trim($post_data['amazon']['client_secret']);
				$temp_default['amazon']['enable'] = $post_data['amazon']['enable'];
			}
			if (isset($post_data['pay']['enable']) && $post_data['pay']['enable'] == 1)
			{
				$post_data['pay']['client_id'] = trim($post_data['pay']['client_id']);
				$post_data['pay']['client_secret'] = trim($post_data['pay']['client_secret']);
				$temp_default['pay']['client_id'] = trim($post_data['pay']['client_id']);
				$temp_default['pay']['client_secret'] = trim($post_data['pay']['client_secret']);
				$temp_default['pay']['enable'] = $post_data['pay']['enable'];
			}
			if (isset($post_data['foursquare']['enable']) && $post_data['foursquare']['enable'] == 1)
			{
				$post_data['foursquare']['client_id'] = trim($post_data['foursquare']['client_id']);
				$post_data['foursquare']['client_secret'] = trim($post_data['foursquare']['client_secret']);
				$temp_default['foursquare']['client_id'] = trim($post_data['foursquare']['client_id']);
				$temp_default['foursquare']['client_secret'] = trim($post_data['foursquare']['client_secret']);
				$temp_default['foursquare']['enable'] = $post_data['foursquare']['enable'];
			}
			if (isset($post_data['github']['enable']) && $post_data['github']['enable'] == 1)
			{
				$post_data['github']['client_id'] = trim($post_data['github']['client_id']);
				$post_data['github']['client_secret'] = trim($post_data['github']['client_secret']);
				$temp_default['github']['client_id'] = trim($post_data['github']['client_id']);
				$temp_default['github']['client_secret'] = trim($post_data['github']['client_secret']);
				$temp_default['github']['enable'] = $post_data['github']['enable'];
			}
			if (isset($post_data['disqus']['enable']) && $post_data['disqus']['enable'] == 1)
			{
				$post_data['disqus']['client_id'] = trim($post_data['disqus']['client_id']);
				$post_data['disqus']['client_secret'] = trim($post_data['disqus']['client_secret']);
				$temp_default['disqus']['client_id'] = trim($post_data['disqus']['client_id']);
				$temp_default['disqus']['client_secret'] = trim($post_data['disqus']['client_secret']);
				$temp_default['disqus']['enable'] = $post_data['disqus']['enable'];
			}
			if (isset($post_data['vk']['enable']) && $post_data['vk']['enable'] == 1)
			{
				$post_data['vk']['client_id'] = trim($post_data['vk']['client_id']);
				$post_data['vk']['client_secret'] = trim($post_data['vk']['client_secret']);
				$temp_default['vk']['client_id'] = trim($post_data['vk']['client_id']);
				$temp_default['vk']['client_secret'] = trim($post_data['vk']['client_secret']);
				$temp_default['vk']['enable'] = $post_data['vk']['enable'];
			}
			if (isset($post_data['wordpress']['enable']) && $post_data['wordpress']['enable'] == 1)
			{
				$post_data['wordpress']['client_id'] = trim($post_data['wordpress']['client_id']);
				$post_data['wordpress']['client_secret'] = trim($post_data['wordpress']['client_secret']);
				$temp_default['wordpress']['client_id'] = trim($post_data['wordpress']['client_id']);
				$temp_default['wordpress']['client_secret'] = trim($post_data['wordpress']['client_secret']);
				$temp_default['wordpress']['enable'] = $post_data['wordpress']['enable'];
			}
			if (isset($post_data['dropbox']['enable']) && $post_data['dropbox']['enable'] == 1)
			{
				$post_data['dropbox']['client_id'] = trim($post_data['dropbox']['client_id']);
				$post_data['dropbox']['client_secret'] = trim($post_data['dropbox']['client_secret']);
				$temp_default['dropbox']['client_id'] = trim($post_data['dropbox']['client_id']);
				$temp_default['dropbox']['client_secret'] = trim($post_data['dropbox']['client_secret']);
				$temp_default['dropbox']['enable'] = $post_data['dropbox']['enable'];
			}
			if (isset($post_data['facebook']['enable']) && $post_data['facebook']['enable'] == 1
				&& ($post_data['facebook']['app_id'] == '' || $post_data['facebook']['app_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable facebook login'));
			else if (isset($post_data['gplus']['enable']) && $post_data['gplus']['enable'] == 1
				&& ($post_data['gplus']['client_id'] == '' || $post_data['gplus']['client_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable google login'));
			else if (isset($post_data['live']['enable']) && $post_data['live']['enable'] == 1
				&& ($post_data['live']['client_id'] == '' || $post_data['live']['client_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable live login'));
			else if (isset($post_data['linked']['enable']) && $post_data['linked']['enable'] == 1
				&& ($post_data['linked']['client_id'] == '' || $post_data['linked']['client_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable linkedin login'));
			else if (isset($post_data['twitter']['enable']) && $post_data['twitter']['enable'] == 1
				&& ($post_data['twitter']['client_id'] == '' || $post_data['twitter']['client_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable twitter login'));
			else if (isset($post_data['yahoo']['enable']) && $post_data['yahoo']['enable'] == 1
				&& ($post_data['yahoo']['consumer_key'] == '' || $post_data['yahoo']['consumer_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable yahoo login'));
			else if (isset($post_data['insta']['enable']) && $post_data['insta']['enable'] == 1
				&& ($post_data['insta']['client_id'] == '' || $post_data['insta']['client_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable instagram login'));
			else if (isset($post_data['amazon']['enable']) && $post_data['amazon']['enable'] == 1
				&& ($post_data['amazon']['client_id'] == '' || $post_data['amazon']['client_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable amazon login'));
			else if (isset($post_data['pay']['enable']) && $post_data['pay']['enable'] == 1
				&& ($post_data['pay']['client_id'] == '' || $post_data['pay']['client_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable paypal login'));
			else if (isset($post_data['foursquare']['enable']) && $post_data['foursquare']['enable'] == 1 && ($post_data['foursquare']['client_id'] == ''
				|| $post_data['foursquare']['client_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable foursquare login'));
			else if (isset($post_data['github']['enable']) && $post_data['github']['enable'] == 1
				&& ($post_data['github']['client_id'] == '' || $post_data['github']['client_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable github login'));
			else if (isset($post_data['disqus']['enable']) && $post_data['disqus']['enable'] == 1
				&& ($post_data['disqus']['client_id'] == '' || $post_data['disqus']['client_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable disqus login'));
			else if (isset($post_data['vk']['enable']) && $post_data['vk']['enable'] == 1
				&& ($post_data['vk']['client_id'] == '' || $post_data['vk']['client_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable vkontakte login'));
			else if (isset($post_data['wordpress']['enable']) && $post_data['wordpress']['enable'] == 1 && ($post_data['wordpress']['client_id'] == ''
				|| $post_data['wordpress']['client_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable wordpress login'));
			else if (isset($post_data['dropbox']['enable']) && $post_data['dropbox']['enable'] == 1
				&& ($post_data['dropbox']['client_id'] == '' || $post_data['dropbox']['client_secret'] == ''))
				$output .= $this->displayError($this->l('Credentials are required to enable dropbox login'));
			else
			{
				Configuration::updateValue('VELOCITY_SOCIAL_LOGINIZER', serialize($temp_default));
				Configuration::updateValue('VELOCITY_LOGINIZER_CUSTOMCSS', serialize($post_data['custom_css']));
				$output .= $this->displayConfirmation($this->l('Settings has been updated successfully'));
			}
		}

		$settings = Configuration::get('VELOCITY_SOCIAL_LOGINIZER');
		$this->loginizer_settings = unserialize($settings);

		$get_data = Configuration::get('VELOCITY_SOCIAL_LOGINIZER');
		$saved_data = unserialize($get_data);
		$socailloginizer_custom_css = '';
		if (Configuration::get('VELOCITY_LOGINIZER_CUSTOMCSS') || Configuration::get('VELOCITY_LOGINIZER_CUSTOMCSS') != '')
			$socailloginizer_custom_css = unserialize(Configuration::get('VELOCITY_LOGINIZER_CUSTOMCSS'));
		$saved_data['custom_css'] = $socailloginizer_custom_css;
		$custom_ssl_var = 0;
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
			$custom_ssl_var = 1;
		if ((bool)Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1)
		{
			$module_dir = _PS_BASE_URL_SSL_.__PS_BASE_URI__.str_replace(_PS_ROOT_DIR_.'/', '', _PS_MODULE_DIR_);
			$manual_dir = _PS_BASE_URL_SSL_.__PS_BASE_URI__;
		}
		else
		{
			$module_dir = _PS_BASE_URL_.__PS_BASE_URI__.str_replace(_PS_ROOT_DIR_.'/', '', _PS_MODULE_DIR_);
			$manual_dir = _PS_BASE_URL_.__PS_BASE_URI__;
		}
		$this->smarty->assign(array(
			'action' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&configure='.$this->name,
			'cancel_action' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'loginizer_sequence' => $saved_data['loginizer_sequence'],
			'velocity_social' => $saved_data,
			'module_dir' => $module_dir,
			'manual_dir' => $manual_dir,
			'domain' => $_SERVER['HTTP_HOST'],
			'google_url' => $this->removeLang($this->context->link->getModuleLink('socialloginizer', 'google')),
			'live_url' => $this->removeLang($this->context->link->getModuleLink('socialloginizer', 'live')),
			'linkedin_url' => $this->removeLang($this->context->link->getModuleLink('socialloginizer', 'live')),
			'twitter_url' => $this->removeLang($this->context->link->getModuleLink('socialloginizer', 'twitter')),
			'insta_url' => $this->removeLang($this->context->link->getModuleLink('socialloginizer', 'instagram')),
			'amazon_url' => $this->removeLang($this->context->link->getModuleLink('socialloginizer', 'amazon')),
			'paypal_url' => $this->removeLang($this->context->link->getModuleLink('socialloginizer', 'paypal')),
			'foursquare_url' => $this->removeLang($this->context->link->getModuleLink('socialloginizer', 'foursquare')),
			'github_url' => $this->removeLang($this->context->link->getModuleLink('socialloginizer', 'github')),
			'vk_url' => $this->removeLang($this->context->link->getModuleLink('socialloginizer', 'vk')),
			'wordpress_url' => $this->removeLang($this->context->link->getModuleLink('socialloginizer', 'wordpress')),
			'dropbox_url' => $this->removeLang($this->context->link->getModuleLink('socialloginizer', 'dropbox'))
		));
		$graphdata = array(
		    '0' => array (
			'account_type' => 'Facebook',
			'login_count' => 334,
			'register_count' => 79
		    ),
		    '1' => array (
			'account_type' => 'Google',
			'login_count' => 186,
			'register_count' => 78
		    ),
		    '2' => array (
			'account_type' => 'Live',
			'login_count' => 249,
			'register_count' => 54
		    ),
		    '3' => array (
			'account_type' => 'Linkedin',
			'login_count' => 335,
			'register_count' => 72
		    ),
		    '4' => array (
			'account_type' => 'Twitter',
			'login_count' => 336,
			'register_count' => 52
		    ),
		    '5' => array (
			'account_type' => 'Instagram',
			'login_count' => 450,
			'register_count' => 57
		    ),
		    '6' => array (
			'account_type' => 'Yahoo',
			'login_count' => 120,
			'register_count' => 65
		    ),
		    '7' => array (
			'account_type' => 'Amazon',
			'login_count' => 302,
			'register_count' => 62
		    ),
		    '8' => array (
			'account_type' => 'Paypal',
			'login_count' => 314,
			'register_count' => 59
		    ),
		    '9' => array (
			'account_type' => 'Foursquare',
			'login_count' => 120,
			'register_count' => 53
		    ),
		    '10' => array (
			'account_type' => 'Github',
			'login_count' => 211,
			'register_count' => 72
		    ),
		    '11' => array (
			'account_type' => 'Disqus',
			'login_count' => 325,
			'register_count' => 80
		    ),
		    '12' => array (
			'account_type' => 'Vk',
			'login_count' => 109,
			'register_count' => 54
		    ),
		    '13' => array (
			'account_type' => 'Wordpress',
			'login_count' => 416,
			'register_count' => 78
		    ),
		    '14' => array (
			'account_type' => 'Dropbox',
			'login_count' => 389,
			'register_count' => 63
		    )
		);
		$graphcount = array(
		    'lcount' => 4224,
		    'rcount' => 978
		);
		$this->smarty->assign('each_account', $graphdata);
		$this->smarty->assign('count_account', $graphcount);
		$this->smarty->assign('graphdata', Tools::jsonEncode($graphdata));

		//Added to assign current version of prestashop in a new variable
		if (version_compare(_PS_VERSION_, '1.6.0.1', '<'))
			$this->context->smarty->assign('ps_version', 15);
		else
			$this->context->smarty->assign('ps_version', 16);
		$this->smarty->assign('link', $this->context->link->getModuleLink('socialloginizer', 'statistics'));
		$output .= $this->display(__FILE__, 'views/templates/admin/loginizer.tpl');
		return $output;
	}

	/*
	 * Add css and javascript
	 */

	protected function addBackOfficeMedia()
	{
		//CSS files
		$this->context->controller->addCSS($this->_path.'views/css/loginizer.css');
		$this->context->controller->addCSS($this->_path.'views/css/bootstrap/bootstrap.css');
		$this->context->controller->addCSS($this->_path.'views/css/bootstrap/bootstrap-select/bootstrap-select.css');
		$this->context->controller->addCSS($this->_path.'views/css/bootstrap/responsive.css');
		$this->context->controller->addCSS($this->_path.'views/css/theme/fonts/glyphicons/css/glyphicons_regular.css');
		$this->context->controller->addCSS($this->_path.'views/css/theme/fonts/glyphicons/css/glyphicons_social.css');
		$this->context->controller->addCSS($this->_path.'views/css/theme/fonts/font-awesome/css/font-awesome.min.css');
		$this->context->controller->addCSS($this->_path.'views/css/theme/scripts/plugins/forms/pixelmatrix-uniform/css/uniform.default.css');
		$this->context->controller->addCSS($this->_path.'views/css/bootstrap/extend/bootstrap-switch/static/stylesheets/bootstrap-switch.css');
		$this->context->controller->addCSS($this->_path.'views/css/theme/style-light.css');
		$this->context->controller->addCSS($this->_path.'views/css/jquery-ui/jquery-ui.min.css');

		//$this->context->controller->addJs($this->_path.'views/js/bootstrap/bootstrap.min.js');
		$this->context->controller->addJs($this->_path.'views/js/jquery-ui/jquery-ui.min.js');
		$this->context->controller->addJs($this->_path.'views/js/bootstrap-select/bootstrap-select.js');
		$this->context->controller->addJs($this->_path.'views/js/theme/demo/common.js');
		$this->context->controller->addJs($this->_path.'views/js/socialloginizer.js');
//		$this->context->controller->addJs($this->_path.'js/tooltip.js');
		$this->context->controller->addJs($this->_path.'views/js/tinysort/jquery.tinysort.min.js');
		$this->context->controller->addJs($this->_path.'views/js/uniform/jquery.uniform.min.js');
		$this->context->controller->addJs($this->_path.'views/js/bootstrap/extend/bootstrap-switch/static/js/bootstrap-switch.js');
		if (!version_compare(_PS_VERSION_, '1.6.0.1', '<'))
			$this->context->controller->addCSS($this->_path.'views/css/socialloginizer_16_admin.css');
		else
			$this->context->controller->addCSS($this->_path.'views/css/socialloginizer_15_admin.css');
//		Charts
		if (_PS_VERSION_ < '1.6.0')
			$this->context->controller->addJs($this->_path.'views/js/flot/jquery.flot.min.js');
		else
			$this->context->controller->addJqueryPlugin('flot');
//		$this->context->controller->addJs($this->_path.'views/js/flot/jquery.flot.tooltip.js');
//		$this->context->controller->addJs($this->_path.'views/js/flot/jquery.flot.tooltip_0.5.js');
		$this->context->controller->addJs($this->_path.'views/js/flot/jquery.flot.symbol.js');
		$this->context->controller->addJs($this->_path.'views/js/flot/jquery.flot.axislabels.js');
		$this->context->controller->addJs($this->_path.'views/js/flot/jquery.flot.orderBars.js');
	}

	/*
	 * Return default settings of the Social Loginizer page
	 */

	protected function getDefaultSettings()
	{
		$col_query = 'SHOW COLUMNS FROM '._DB_PREFIX_.'customer LIKE "id_lang"';
		$col_result = Db::getInstance()->executeS($col_query);
		if (count($col_result) == 1)
			$col_exist = 1;
		else
			$col_exist = 0;
		$settings = array(
			'plugin_id' => 'PS0005',
			'version' => '0.1',
			'enable' => 0,
			'show_popup' => 1,
			'show_on_supercheckout' => 'do_not_show',
			'col_exist' => $col_exist,
			'loginizer_sequence' => array(
				'facebook' => 0,
				'google' => 1,
				'linkedin' => 2,
				'live' => 3,
				'twitter' => 4,
				'yahoo' => 5,
				'amazon' => 6,
				'instagram' => 7,
				'paypal' => 8,
				'foursquare' => 9,
				'github' => 10,
				'disqus' => 11,
				'vk' => 12,
				'wordpress' => 13,
				'dropbox' => 14
			),
			'index' => array(
				'status' => 0,
				'position' => 'header',
				'title' => 'Sign Up',
				'size' => 0
			),
			'category' => array(
				'status' => 0,
				'position' => 'header',
				'title' => 'Sign Up',
				'size' => 0
			),
			'product' => array(
				'status' => 0,
				'position' => 'header',
				'title' => 'Sign Up',
				'size' => 0
			),
			'authentication' => array(
				'status' => 1,
				'position' => 'login',
				'title' => 'Sign Up',
				'size' => 0
			),
			'order' => array(
				'status' => 0,
				'position' => 'header',
				'title' => 'Sign Up',
				'size' => 0
			),
			'order-opc' => array(
				'status' => 0,
				'position' => 'header',
				'title' => 'Sign Up',
				'size' => 0
			),
			'cms' => array(
				'status' => 0,
				'position' => 'header',
				'title' => 'Sign Up',
				'size' => 0
			),
			'prices-drop' => array(
				'status' => 0,
				'position' => 'header',
				'title' => 'Sign Up',
				'size' => 0
			),
			'new-products' => array(
				'status' => 0,
				'position' => 'header',
				'title' => 'Sign Up',
				'size' => 0
			),
			'best-sales' => array(
				'status' => 0,
				'position' => 'header',
				'title' => 'Sign Up',
				'size' => 0
			),
			'stores' => array(
				'status' => 0,
				'position' => 'header',
				'title' => 'Sign Up',
				'size' => 0
			),
			'contact' => array(
				'status' => 0,
				'position' => 'header',
				'title' => 'Sign Up',
				'size' => 0
			)
		);
		return $settings;
	}

	public function shortCode()
	{
                $this->context->controller->addJs($this->_path.'views/js/tinysort/jquery.tinysort.min.js');
                $this->context->controller->addCSS($this->_path.'views/css/loginizer_front.css');
                $global_code_small = '';
                $global_code_large = '';
                $this->smarty->assign('loginizer_small', $global_code_small);
                $this->smarty->assign('loginizer_large', $global_code_large);
	}

	public function hookDisplayHeader()
	{
		$page_name = $this->context->smarty->tpl_vars['page_name']->value;
		$plugin_data = unserialize(Configuration::get('VELOCITY_SOCIAL_LOGINIZER'));
		if (isset($plugin_data['show_popup']) && $plugin_data['show_popup'] == 1)
			$plugin_data['show_popup'] = $plugin_data['show_popup'];
		else
			$plugin_data['show_popup'] = false;
		$this->smarty->assign(array('show_popup'=>$plugin_data['show_popup']));
		$this->smarty->assign(array('show_on_supercheckout'=>$plugin_data['show_on_supercheckout']));
		if (isset($plugin_data['enable']) && $plugin_data['enable'] == 1)
			$this->shortCode();
		$check_code = '';
		$pages_available = array('authentication');
		if ($page_name == 'pagenotfound')
			return $this->display(__FILE__, 'redirection_script.tpl');

		if (in_array($page_name, $pages_available) && isset($plugin_data['enable']) && $plugin_data['enable'] == 1)
		{
			$link = $this->context->link->getModuleLink('socialloginizer', 'facebook');

			$dot_found = 0;
			$needle = '.php';
			$dot_found = strpos($link, $needle);
			if ($dot_found !== false)
				$append_with = '&';
			else
				$append_with = '?';
			$custom_ssl_var = 0;
			if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
				$custom_ssl_var = 1;
			if ((bool)Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1)
				$module_dir = _PS_BASE_URL_SSL_.__PS_BASE_URI__.str_replace(_PS_ROOT_DIR_.'/', '', _PS_MODULE_DIR_);
			else
				$module_dir = _PS_BASE_URL_.__PS_BASE_URI__.str_replace(_PS_ROOT_DIR_.'/', '', _PS_MODULE_DIR_);

			$link = $this->context->link->getModuleLink('socialloginizer', 'facebook');
			if (isset($plugin_data['facebook']['enable']) && $plugin_data['facebook']['enable'] == 1)
			{
				$facebook1 = '<li data-index="'.$plugin_data['loginizer_sequence']['facebook'].'">';
				$facebook2 = '<a type="fb" href="'.$link.$append_with.'type=fb" title="Facebook">';
				$facebook3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$facebook = $facebook1.$facebook2.$facebook3.'/views/img/buttons/fb_small.png"></a></li>';
				else
					$facebook = $facebook1.$facebook2.$facebook3.'/views/img/buttons/fb_large.png"></a></li>';
			}
			else
				$facebook = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'google');
			if (isset($plugin_data['gplus']['enable']) && $plugin_data['gplus']['enable'] == 1)
			{
				$google1 = '<li data-index="'.$plugin_data['loginizer_sequence']['google'].'">';
				$google2 = '<a href="'.$link.$append_with.'type=google" type="google" title="Google">';
				$google3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$google = $google1.$google2.$google3.'/views/img/buttons/google_small.png"></a></li>';
				else
					$google = $google1.$google2.$google3.'/views/img/buttons/google_large.png"></a></li>';
			}
			else
				$google = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'linkedin');
			if (isset($plugin_data['linked']['enable']) && $plugin_data['linked']['enable'] == 1)
			{
				$linkedin1 = '<li data-index="'.$plugin_data['loginizer_sequence']['linkedin'].'">';
				$linkedin2 = '<a href="'.$link.$append_with.'type=linked" type="link" title="Linkedin">';
				$linkedin3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$linkedin = $linkedin1.$linkedin2.$linkedin3.'/views/img/buttons/linkedin_small.png"></a></li>';
				else
					$linkedin = $linkedin1.$linkedin2.$linkedin3.'/views/img/buttons/linkedin_large.png"></a></li>';
			}
			else
				$linkedin = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'live');
			if (isset($plugin_data['live']['enable']) && $plugin_data['live']['enable'] == 1)
			{
				$live1 = '<li data-index="'.$plugin_data['loginizer_sequence']['live'].'">';
				$live2 = '<a href="'.$link.$append_with.'type=live" type="live" title="Live">';
				$live3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$live = $live1.$live2.$live3.'/views/img/buttons/live_small.png"></a></li>';
				else
					$live = $live1.$live2.$live3.'/views/img/buttons/live_large.png"></a></li>';
			}
			else
				$live = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'twitter');
			if (isset($plugin_data['twitter']['enable']) && $plugin_data['twitter']['enable'] == 1)
			{
				$twitter1 = '<li data-index="'.$plugin_data['loginizer_sequence']['twitter'].'">';
				$twitter2 = '<a href="'.$link.$append_with.'type=tweet" type="tweet" title="Twitter">';
				$twitter3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$twitter = $twitter1.$twitter2.$twitter3.'/views/img/buttons/twitter_small.png"></a></li>';
				else
					$twitter = $twitter1.$twitter2.$twitter3.'/views/img/buttons/twitter_large.png"></a></li>';
			}
			else
				$twitter = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'ylogin');
			if (isset($plugin_data['yahoo']['enable']) && $plugin_data['yahoo']['enable'] == 1)
			{
				$yahoo1 = '<li data-index="'.$plugin_data['loginizer_sequence']['yahoo'].'">';
				$yahoo2 = '<a href="'.$link.$append_with.'type=yahoo" type="yahoo" title="Yahoo">';
				$yahoo3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$yahoo = $yahoo1.$yahoo2.$yahoo3.'/views/img/buttons/yahoo_small.png"></a></li>';
				else
					$yahoo = $yahoo1.$yahoo2.$yahoo3.'/views/img/buttons/yahoo_large.png"></a></li>';
			}
			else
				$yahoo = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'amazon');
			if (isset($plugin_data['amazon']['enable']) && $plugin_data['amazon']['enable'] == 1)
			{
				$amazon1 = '<li data-index="'.$plugin_data['loginizer_sequence']['amazon'].'">';
				$amazon2 = '<a href="'.$link.$append_with.'type=amazon" type="amazon" title="Amazon">';
				$amazon3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$amazon = $amazon1.$amazon2.$amazon3.'/views/img/buttons/amazon_small.png"></a></li>';
				else
					$amazon = $amazon1.$amazon2.$amazon3.'/views/img/buttons/amazon_large.png"></a></li>';
			}
			else
				$amazon = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'instagram');
			if (isset($plugin_data['insta']['enable']) && $plugin_data['insta']['enable'] == 1)
			{
				$instagram1 = '<li data-index="'.$plugin_data['loginizer_sequence']['instagram'].'">';
				$instagram2 = '<a href="'.$link.$append_with.'type=insta" type="insta" title="Instagram">';
				$instagram3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$instagram = $instagram1.$instagram2.$instagram3.'/views/img/buttons/instagram_small.png"></a></li>';
				else
					$instagram = $instagram1.$instagram2.$instagram3.'/views/img/buttons/instagram_large.png"></a></li>';
			}
			else
				$instagram = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'paypal');
			if (isset($plugin_data['pay']['enable']) && $plugin_data['pay']['enable'] == 1)
			{
				$paypal1 = '<li data-index="'.$plugin_data['loginizer_sequence']['paypal'].'">';
				$paypal2 = '<a href="'.$link.$append_with.'type=pay" type="pay" title="Paypal">';
				$paypal3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$paypal = $paypal1.$paypal2.$paypal3.'/views/img/buttons/paypal_small.png"></a></li>';
				else
					$paypal = $paypal1.$paypal2.$paypal3.'/views/img/buttons/paypal_large.png"></a></li>';
			}
			else
				$paypal = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'foursquare');
			if (isset($plugin_data['foursquare']['enable']) && $plugin_data['foursquare']['enable'] == 1)
			{
				$foursquare1 = '<li data-index="'.$plugin_data['loginizer_sequence']['foursquare'].'">';
				$foursquare2 = '<a href="'.$link.$append_with.'type=foursquare" type="foursquare" title="foursquare">';
				$foursquare3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$foursquare = $foursquare1.$foursquare2.$foursquare3.'/views/img/buttons/foursquare_small.png"></a></li>';
				else
					$foursquare = $foursquare1.$foursquare2.$foursquare3.'/views/img/buttons/foursquare_large.png"></a></li>';
			}
			else
				$foursquare = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'github');
			if (isset($plugin_data['github']['enable']) && $plugin_data['github']['enable'] == 1)
			{
				$github1 = '<li data-index="'.$plugin_data['loginizer_sequence']['github'].'">';
				$github2 = '<a href="'.$link.$append_with.'type=github" type="github" title="github">';
				$github3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$github = $github1.$github2.$github3.'/views/img/buttons/github_small.png"></a></li>';
				else
					$github = $github1.$github2.$github3.'/views/img/buttons/github_large.png"></a></li>';
			}
			else
				$github = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'disqus');
			if (isset($plugin_data['disqus']['enable']) && $plugin_data['disqus']['enable'] == 1)
			{
				$disqus1 = '<li data-index="'.$plugin_data['loginizer_sequence']['disqus'].'">';
				$disqus2 = '<a href="'.$link.$append_with.'type=disqus" type="disqus" title="disqus">';
				$disqus3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$disqus = $disqus1.$disqus2.$disqus3.'/views/img/buttons/disqus_small.png"></a></li>';
				else
					$disqus = $disqus1.$disqus2.$disqus3.'/views/img/buttons/disqus_large.png"></a></li>';
			}
			else
				$disqus = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'vk');
			if (isset($plugin_data['vk']['enable']) && $plugin_data['vk']['enable'] == 1)
			{
				$vk1 = '<li data-index="'.$plugin_data['loginizer_sequence']['vk'].'">';
				$vk2 = '<a href="'.$link.$append_with.'type=vk" type="vk" title="vk">';
				$vk3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$vk = $vk1.$vk2.$vk3.'/views/img/buttons/vk_small.png"></a></li>';
				else
					$vk = $vk1.$vk2.$vk3.'/views/img/buttons/vk_large.png"></a></li>';
			}
			else
				$vk = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'wordpress');
			if (isset($plugin_data['wordpress']['enable']) && $plugin_data['wordpress']['enable'] == 1)
			{
				$wordpress1 = '<li data-index="'.$plugin_data['loginizer_sequence']['wordpress'].'">';
				$wordpress2 = '<a href="'.$link.$append_with.'type=wordpress" type="wordpress" title="wordpress">';
				$wordpress3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$wordpress = $wordpress1.$wordpress2.$wordpress3.'/views/img/buttons/wordpress_small.png"></a></li>';
				else
					$wordpress = $wordpress1.$wordpress2.$wordpress3.'/views/img/buttons/wordpress_large.png"></a></li>';
			}
			else
				$wordpress = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'dropbox');
			if (isset($plugin_data['dropbox']['enable']) && $plugin_data['dropbox']['enable'] == 1)
			{
				$dropbox1 = '<li data-index="'.$plugin_data['loginizer_sequence']['dropbox'].'">';
				$dropbox2 = '<a href="'.$link.$append_with.'type=dropbox" type="dropbox" title="dropbox">';
				$dropbox3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$dropbox = $dropbox1.$dropbox2.$dropbox3.'/views/img/buttons/dropbox_small.png"></a></li>';
				else
					$dropbox = $dropbox1.$dropbox2.$dropbox3.'/views/img/buttons/dropbox_large.png"></a></li>';
			}
			else
				$dropbox = '';
			$last_li = '<div style="float:none;width:0px;position: absolute;">&nbsp;</div>';
			$check_code = $facebook.''
				.$google.''
				.$linkedin.''
				.$live.''
				.$twitter.''
				.$yahoo.''
				.$amazon.''
				.$instagram.''
				.$paypal.''
				.$foursquare.''
				.$github.''
				.$disqus.''
				.$vk.''
				.$wordpress.''
				.$dropbox;

			$html_code = '<div id="head"><a class="hover-link form-style" >'.$this->l('Sign Up').'</a>'
				.$last_li.'<ul id="output" class="velsof-sub-options ul-form-style">'
				.$facebook.''
				.$google.''
				.$linkedin.''
				.$live.''
				.$twitter.''
				.$yahoo.''
				.$amazon.''
				.$instagram.''
				.$paypal.''
				.$foursquare.''
				.$github.''
				.$disqus.''
				.$vk.''
				.$wordpress.''
				.$dropbox.'</ul></div>';
		}

//		Added to assign current version of prestashop in a new variable
		if (version_compare(_PS_VERSION_, '1.6.0.1', '<'))
			$this->context->smarty->assign('ps_version_com', 15);
		else
			$this->context->smarty->assign('ps_version_com', 16);

		if ($check_code != '')
		{
			$ps_version = 0;
			
			if (!$this->context->cookie->logged
				&& $plugin_data[$page_name]['status']
				&& ($plugin_data[$page_name]['position'] == 'create'
				|| $plugin_data[$page_name]['position'] == 'login'))
			{
				if (isset($plugin_data['enable']) && $plugin_data['enable'] == 1)
				{
					$this->smarty->assign(array(
						'velsof_loginizer' => 1,
						'html_code' => $html_code,
						'module_dir' => $module_dir,
						'position' => $plugin_data[$page_name]['position']
					));
				}
				else
				{
					$this->smarty->assign(array(
						'velsof_loginizer' => 0,
						'html_code' => '',
						'module_dir' => $module_dir,
						'position' => $plugin_data[$page_name]['position']
					));
				}

				if (isset($plugin_data['enable']) && $plugin_data['enable'] == 1)
					return '<style>'.$plugin_data['custom_css'].'</style>'.$this->display(__FILE__, 'authentication_block.tpl');
				else
					return $this->display(__FILE__, 'authentication_block.tpl');
			}
		}
		if (!$this->context->cookie->logged && isset($plugin_data['enable']) && $plugin_data['enable'] == 1)
			return $this->display(__FILE__, 'shortcode.tpl');
	}
	public function hookDisplayMobileHeader()
	{
		$page_name = $this->context->smarty->tpl_vars['page_name']->value;
		$plugin_data = unserialize(Configuration::get('VELOCITY_SOCIAL_LOGINIZER'));
		if (isset($plugin_data['show_popup']) && $plugin_data['show_popup'] == 1)
			$plugin_data['show_popup'] = $plugin_data['show_popup'];
		else
			$plugin_data['show_popup'] = false;
		$this->smarty->assign(array('show_popup'=>$plugin_data['show_popup']));
		$this->smarty->assign(array('show_on_supercheckout'=>$plugin_data['show_on_supercheckout']));
		if (isset($plugin_data['enable']) && $plugin_data['enable'] == 1)
			$this->shortCode();
		$check_code = '';
		$pages_available = array('authentication');
		if ($page_name == 'pagenotfound')
			return $this->display(__FILE__, 'redirection_script.tpl');

		if (in_array($page_name, $pages_available) && isset($plugin_data['enable']) && $plugin_data['enable'] == 1)
		{
			$link = $this->context->link->getModuleLink('socialloginizer', 'facebook');

			$dot_found = 0;
			$needle = '.php';
			$dot_found = strpos($link, $needle);
			if ($dot_found !== false)
				$append_with = '&';
			else
				$append_with = '?';
			$custom_ssl_var = 0;
			if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
				$custom_ssl_var = 1;
			if ((bool)Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1)
				$module_dir = _PS_BASE_URL_SSL_.__PS_BASE_URI__.str_replace(_PS_ROOT_DIR_.'/', '', _PS_MODULE_DIR_);
			else
				$module_dir = _PS_BASE_URL_.__PS_BASE_URI__.str_replace(_PS_ROOT_DIR_.'/', '', _PS_MODULE_DIR_);

			$link = $this->context->link->getModuleLink('socialloginizer', 'facebook');
			if (isset($plugin_data['facebook']['enable']) && $plugin_data['facebook']['enable'] == 1)
			{
				$facebook1 = '<li data-index="'.$plugin_data['loginizer_sequence']['facebook'].'">';
				$facebook2 = '<a type="fb" href="'.$link.$append_with.'type=fb" title="Facebook">';
				$facebook3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$facebook = $facebook1.$facebook2.$facebook3.'/views/img/buttons/fb_small.png"></a></li>';
				else
					$facebook = $facebook1.$facebook2.$facebook3.'/views/img/buttons/fb_large.png"></a></li>';
			}
			else
				$facebook = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'google');
			if (isset($plugin_data['gplus']['enable']) && $plugin_data['gplus']['enable'] == 1)
			{
				$google1 = '<li data-index="'.$plugin_data['loginizer_sequence']['google'].'">';
				$google2 = '<a href="'.$link.$append_with.'type=google" type="google" title="Google">';
				$google3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$google = $google1.$google2.$google3.'/views/img/buttons/google_small.png"></a></li>';
				else
					$google = $google1.$google2.$google3.'/views/img/buttons/google_large.png"></a></li>';
			}
			else
				$google = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'linkedin');
			if (isset($plugin_data['linked']['enable']) && $plugin_data['linked']['enable'] == 1)
			{
				$linkedin1 = '<li data-index="'.$plugin_data['loginizer_sequence']['linkedin'].'">';
				$linkedin2 = '<a href="'.$link.$append_with.'type=linked" type="link" title="Linkedin">';
				$linkedin3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$linkedin = $linkedin1.$linkedin2.$linkedin3.'/views/img/buttons/linkedin_small.png"></a></li>';
				else
					$linkedin = $linkedin1.$linkedin2.$linkedin3.'/views/img/buttons/linkedin_large.png"></a></li>';
			}
			else
				$linkedin = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'live');
			if (isset($plugin_data['live']['enable']) && $plugin_data['live']['enable'] == 1)
			{
				$live1 = '<li data-index="'.$plugin_data['loginizer_sequence']['live'].'">';
				$live2 = '<a href="'.$link.$append_with.'type=live" type="live" title="Live">';
				$live3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$live = $live1.$live2.$live3.'/views/img/buttons/live_small.png"></a></li>';
				else
					$live = $live1.$live2.$live3.'/views/img/buttons/live_large.png"></a></li>';
			}
			else
				$live = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'twitter');
			if (isset($plugin_data['twitter']['enable']) && $plugin_data['twitter']['enable'] == 1)
			{
				$twitter1 = '<li data-index="'.$plugin_data['loginizer_sequence']['twitter'].'">';
				$twitter2 = '<a href="'.$link.$append_with.'type=tweet" type="tweet" title="Twitter">';
				$twitter3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$twitter = $twitter1.$twitter2.$twitter3.'/views/img/buttons/twitter_small.png"></a></li>';
				else
					$twitter = $twitter1.$twitter2.$twitter3.'/views/img/buttons/twitter_large.png"></a></li>';
			}
			else
				$twitter = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'ylogin');
			if (isset($plugin_data['yahoo']['enable']) && $plugin_data['yahoo']['enable'] == 1)
			{
				$yahoo1 = '<li data-index="'.$plugin_data['loginizer_sequence']['yahoo'].'">';
				$yahoo2 = '<a href="'.$link.$append_with.'type=yahoo" type="yahoo" title="Yahoo">';
				$yahoo3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$yahoo = $yahoo1.$yahoo2.$yahoo3.'/views/img/buttons/yahoo_small.png"></a></li>';
				else
					$yahoo = $yahoo1.$yahoo2.$yahoo3.'/views/img/buttons/yahoo_large.png"></a></li>';
			}
			else
				$yahoo = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'amazon');
			if (isset($plugin_data['amazon']['enable']) && $plugin_data['amazon']['enable'] == 1)
			{
				$amazon1 = '<li data-index="'.$plugin_data['loginizer_sequence']['amazon'].'">';
				$amazon2 = '<a href="'.$link.$append_with.'type=amazon" type="amazon" title="Amazon">';
				$amazon3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$amazon = $amazon1.$amazon2.$amazon3.'/views/img/buttons/amazon_small.png"></a></li>';
				else
					$amazon = $amazon1.$amazon2.$amazon3.'/views/img/buttons/amazon_large.png"></a></li>';
			}
			else
				$amazon = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'instagram');
			if (isset($plugin_data['insta']['enable']) && $plugin_data['insta']['enable'] == 1)
			{
				$instagram1 = '<li data-index="'.$plugin_data['loginizer_sequence']['instagram'].'">';
				$instagram2 = '<a href="'.$link.$append_with.'type=insta" type="insta" title="Instagram">';
				$instagram3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$instagram = $instagram1.$instagram2.$instagram3.'/views/img/buttons/instagram_small.png"></a></li>';
				else
					$instagram = $instagram1.$instagram2.$instagram3.'/views/img/buttons/instagram_large.png"></a></li>';
			}
			else
				$instagram = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'paypal');
			if (isset($plugin_data['pay']['enable']) && $plugin_data['pay']['enable'] == 1)
			{
				$paypal1 = '<li data-index="'.$plugin_data['loginizer_sequence']['paypal'].'">';
				$paypal2 = '<a href="'.$link.$append_with.'type=pay" type="pay" title="Paypal">';
				$paypal3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$paypal = $paypal1.$paypal2.$paypal3.'/views/img/buttons/paypal_small.png"></a></li>';
				else
					$paypal = $paypal1.$paypal2.$paypal3.'/views/img/buttons/paypal_large.png"></a></li>';
			}
			else
				$paypal = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'foursquare');
			if (isset($plugin_data['foursquare']['enable']) && $plugin_data['foursquare']['enable'] == 1)
			{
				$foursquare1 = '<li data-index="'.$plugin_data['loginizer_sequence']['foursquare'].'">';
				$foursquare2 = '<a href="'.$link.$append_with.'type=foursquare" type="foursquare" title="foursquare">';
				$foursquare3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$foursquare = $foursquare1.$foursquare2.$foursquare3.'/views/img/buttons/foursquare_small.png"></a></li>';
				else
					$foursquare = $foursquare1.$foursquare2.$foursquare3.'/views/img/buttons/foursquare_large.png"></a></li>';
			}
			else
				$foursquare = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'github');
			if (isset($plugin_data['github']['enable']) && $plugin_data['github']['enable'] == 1)
			{
				$github1 = '<li data-index="'.$plugin_data['loginizer_sequence']['github'].'">';
				$github2 = '<a href="'.$link.$append_with.'type=github" type="github" title="github">';
				$github3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$github = $github1.$github2.$github3.'/views/img/buttons/github_small.png"></a></li>';
				else
					$github = $github1.$github2.$github3.'/views/img/buttons/github_large.png"></a></li>';
			}
			else
				$github = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'disqus');
			if (isset($plugin_data['disqus']['enable']) && $plugin_data['disqus']['enable'] == 1)
			{
				$disqus1 = '<li data-index="'.$plugin_data['loginizer_sequence']['disqus'].'">';
				$disqus2 = '<a href="'.$link.$append_with.'type=disqus" type="disqus" title="disqus">';
				$disqus3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$disqus = $disqus1.$disqus2.$disqus3.'/views/img/buttons/disqus_small.png"></a></li>';
				else
					$disqus = $disqus1.$disqus2.$disqus3.'/views/img/buttons/disqus_large.png"></a></li>';
			}
			else
				$disqus = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'vk');
			if (isset($plugin_data['vk']['enable']) && $plugin_data['vk']['enable'] == 1)
			{
				$vk1 = '<li data-index="'.$plugin_data['loginizer_sequence']['vk'].'">';
				$vk2 = '<a href="'.$link.$append_with.'type=vk" type="vk" title="vk">';
				$vk3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$vk = $vk1.$vk2.$vk3.'/views/img/buttons/vk_small.png"></a></li>';
				else
					$vk = $vk1.$vk2.$vk3.'/views/img/buttons/vk_large.png"></a></li>';
			}
			else
				$vk = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'wordpress');
			if (isset($plugin_data['wordpress']['enable']) && $plugin_data['wordpress']['enable'] == 1)
			{
				$wordpress1 = '<li data-index="'.$plugin_data['loginizer_sequence']['wordpress'].'">';
				$wordpress2 = '<a href="'.$link.$append_with.'type=wordpress" type="wordpress" title="wordpress">';
				$wordpress3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$wordpress = $wordpress1.$wordpress2.$wordpress3.'/views/img/buttons/wordpress_small.png"></a></li>';
				else
					$wordpress = $wordpress1.$wordpress2.$wordpress3.'/views/img/buttons/wordpress_large.png"></a></li>';
			}
			else
				$wordpress = '';
			$link = $this->context->link->getModuleLink('socialloginizer', 'dropbox');
			if (isset($plugin_data['dropbox']['enable']) && $plugin_data['dropbox']['enable'] == 1)
			{
				$dropbox1 = '<li data-index="'.$plugin_data['loginizer_sequence']['dropbox'].'">';
				$dropbox2 = '<a href="'.$link.$append_with.'type=dropbox" type="dropbox" title="dropbox">';
				$dropbox3 = '<img src="'.$module_dir.'socialloginizer';
				if ($plugin_data[$page_name]['size'] == 0)
					$dropbox = $dropbox1.$dropbox2.$dropbox3.'/views/img/buttons/dropbox_small.png"></a></li>';
				else
					$dropbox = $dropbox1.$dropbox2.$dropbox3.'/views/img/buttons/dropbox_large.png"></a></li>';
			}
			else
				$dropbox = '';
			$last_li = '<div style="float:none;width:0px;position: absolute;">&nbsp;</div>';
			$check_code = $facebook.''
				.$google.''
				.$linkedin.''
				.$live.''
				.$twitter.''
				.$yahoo.''
				.$amazon.''
				.$instagram.''
				.$paypal.''
				.$foursquare.''
				.$github.''
				.$disqus.''
				.$vk.''
				.$wordpress.''
				.$dropbox;

			$html_code = '<div id="head"><a class="hover-link form-style" >'.$this->l('Sign Up').'</a>'
				.$last_li.'<ul id="output" class="velsof-sub-options ul-form-style">'
				.$facebook.''
				.$google.''
				.$linkedin.''
				.$live.''
				.$twitter.''
				.$yahoo.''
				.$amazon.''
				.$instagram.''
				.$paypal.''
				.$foursquare.''
				.$github.''
				.$disqus.''
				.$vk.''
				.$wordpress.''
				.$dropbox.'</ul></div>';
		}

//		Added to assign current version of prestashop in a new variable
		if (version_compare(_PS_VERSION_, '1.6.0.1', '<'))
			$this->context->smarty->assign('ps_version_com', 15);
		else
			$this->context->smarty->assign('ps_version_com', 16);
		if ($check_code != '')
		{
			$ps_version = 0;
			if (!$this->context->cookie->logged
				&& $plugin_data[$page_name]['status']
				&& ($plugin_data[$page_name]['position'] == 'create'
				|| $plugin_data[$page_name]['position'] == 'login'))
			{
				if (isset($plugin_data['enable']) && $plugin_data['enable'] == 1)
				{
					$this->smarty->assign(array(
						'velsof_loginizer' => 1,
						'html_code' => $html_code,
						'module_dir' => $module_dir,
						'position' => $plugin_data[$page_name]['position']
					));
				}
				else
				{
					$this->smarty->assign(array(
						'velsof_loginizer' => 0,
						'html_code' => '',
						'module_dir' => $module_dir,
						'position' => $plugin_data[$page_name]['position']
					));
				}
				$this->context->smarty->assign('callhook', 'authentication_form');
				$this->context->smarty->assign('mod_path', $this->_path);
				if (isset($plugin_data['enable']) && $plugin_data['enable'] == 1)
					return '<style>'.$plugin_data['custom_css'].'</style>'.$this->display(__FILE__, 'plugin_social_mobile.tpl');
				else
					return $this->display(__FILE__, 'plugin_social_mobile.tpl');
			}
		}
		if (!$this->context->cookie->logged && isset($plugin_data['enable']))
			return $this->display(__FILE__, 'shortcode.tpl');
	}

	public function addUser($user_data, $account)
	{
		if (Customer::customerExists(strip_tags($user_data['email'])))
		{
			$customer_obj = new Customer();
			$customer_tmp = $customer_obj->getByEmail($user_data['email']);

			$customer = new Customer($customer_tmp->id);

			$update_user_count_query = 'update '._DB_PREFIX_
					.'socialloginizer_statistics set user_login_count= (user_login_count + 1) Where customer_id='.(int)$customer_tmp->id.'';
			Db::getInstance()->execute($update_user_count_query);
			//Update Context
			$this->context->customer = $customer;
			$this->context->smarty->assign('confirmation', 1);
			$this->context->cookie->id_customer = (int)$customer->id;
			$this->context->cookie->customer_lastname = $customer->lastname;
			$this->context->cookie->customer_firstname = $customer->firstname;
			$this->context->cookie->passwd = $customer->passwd;
			$this->context->cookie->logged = 1;
			$this->context->cookie->email = $customer->email;
			$this->context->cookie->is_guest = $customer->is_guest;

			//Cart
			if (Configuration::get('PS_CART_FOLLOWING') && (empty($this->context->cookie->id_cart)
				|| Cart::getNbProducts($this->context->cookie->id_cart) == 0)
				&& $id_cart = (int)Cart::lastNoneOrderedCart($this->context->customer->id))
				$this->context->cart = new Cart($id_cart);
			else
			{
				$id_carrier = (int)$this->context->cart->id_carrier;
				$this->context->cart->id_carrier = 0;
				$this->context->cart->setDeliveryOption(null);
				$this->context->cart->id_address_delivery = (int)Address::getFirstCustomerAddressId((int)$customer->id);
				$this->context->cart->id_address_invoice = (int)Address::getFirstCustomerAddressId((int)$customer->id);
			}
			$this->context->cart->secure_key = $customer->secure_key;

			if (isset($id_carrier) && $id_carrier && Configuration::get('PS_ORDER_PROCESS_TYPE'))
			{
				$delivery_option = array($this->context->cart->id_address_delivery => $id_carrier.',');
				$this->context->cart->setDeliveryOption($delivery_option);
			}
			$this->context->cart->save();
			$this->context->cookie->id_cart = (int)$this->context->cart->id;
			$this->context->cookie->write();
			$this->context->cart->autosetProductAddress();
		}
		else
		{

			$setting = unserialize(Configuration::get('VELOCITY_SOCIAL_LOGINIZER'));
			$col_exist = $setting['col_exist'];
			$insertion_time = date('Y-m-d H:i:s', time());
			$original_passd = Tools::substr(md5(uniqid(mt_rand(), true)), 0, 8);
			$passd = Tools::encrypt($original_passd);
			$secure_key = md5(uniqid(rand(), true));
			$gender_qry = '(select id_gender from '._DB_PREFIX_.'gender where type = '.(int)$user_data['gender'].')';
			$gender = Db::getInstance()->getRow($gender_qry);
			$user_firstname = strip_tags($user_data['first_name']);
			$user_lastname = strip_tags($user_data['last_name']);
			$user_email = strip_tags($user_data['email']);
			if (empty($gender))
				$gender['id_gender'] = 0;
			$sql = 'INSERT INTO '._DB_PREFIX_.'customer SET 
				id_shop_group = '.(int)$this->context->shop->id_shop_group.', 
				id_shop = '.(int)$this->context->shop->id.', 
				id_gender = '.$gender['id_gender'].', 
				id_default_group = '.(int)Configuration::get('PS_CUSTOMER_GROUP').',';
			if ($col_exist == 1)
				$sql .= 'id_lang = '.$this->context->language->id.',';
			$sql .=	'id_risk = 0, 
				firstname = "'.pSQL($user_firstname).'", 
				lastname = "'.pSQL($user_lastname).'", 
				email = "'.pSQL($user_email).'", 
				passwd = "'.pSQL($passd).'", 
				max_payment_days = 0, 
				secure_key = "'.pSQL($secure_key).'", 
				active = 1, date_add = "'.pSQL($insertion_time).'", date_upd = "'.pSQL($insertion_time).'"';

			Db::getInstance()->execute($sql);
			$id_customer = Db::getInstance()->Insert_ID();

			$account = Tools::strtolower($account);
			$user_email = $user_data['email'];
			$username = $user_data['username'];
			$get_user = 'select email from '._DB_PREFIX_.'socialloginizer_statistics where email="'.pSQL($user_email).'"';
			$user_result = Db::getInstance()->executeS($get_user);
			if (count($user_result) == 0)
			{
				$set_user = 'insert into '._DB_PREFIX_.
					'socialloginizer_statistics (username,email,account_type,customer_id) values ("'
					.pSQL($username).'","'.pSQL($user_email).'","'.pSQL($account).'",'.(int)$id_customer.')';
				Db::getInstance()->execute($set_user);
				$update_user_count_query = 'update '._DB_PREFIX_
					.'socialloginizer_statistics set user_login_count= (user_login_count + 1) Where customer_id='.(int)$id_customer.'';
				Db::getInstance()->execute($update_user_count_query);
			}
			$customer = new Customer();
			$customer->id = $id_customer;
			$customer->firstname = ucwords($user_data['first_name']);
			$customer->lastname = ucwords($user_data['last_name']);
			$customer->passwd = $passd;
			$customer->email = $user_data['email'];
			$customer->secure_key = $secure_key;
			$customer->birthday = '';
			$customer->is_guest = 0;
			$customer->active = 1;
			$customer->logged = 1;

			$customer->cleanGroups();
			$customer->addGroups(array((int)Configuration::get('PS_CUSTOMER_GROUP')));

			$this->sendConfirmationMail($customer, $original_passd);

			//Update Context
			$this->context->customer = $customer;
			$this->context->smarty->assign('confirmation', 1);
			$this->context->cookie->id_customer = (int)$customer->id;
			$this->context->cookie->customer_lastname = $customer->lastname;
			$this->context->cookie->customer_firstname = $customer->firstname;
			$this->context->cookie->passwd = $customer->passwd;
			$this->context->cookie->logged = 1;
			$this->context->cookie->email = $customer->email;
			$this->context->cookie->is_guest = $customer->is_guest;

			//Cart
			if (Configuration::get('PS_CART_FOLLOWING') && (empty($this->context->cookie->id_cart)
				|| Cart::getNbProducts($this->context->cookie->id_cart) == 0)
				&& $id_cart = (int)Cart::lastNoneOrderedCart($this->context->customer->id))
				$this->context->cart = new Cart($id_cart);
			else
			{
				$id_carrier = (int)$this->context->cart->id_carrier;
				$this->context->cart->id_carrier = 0;
				$this->context->cart->setDeliveryOption(null);
				$this->context->cart->id_address_delivery = (int)Address::getFirstCustomerAddressId((int)$customer->id);
				$this->context->cart->id_address_invoice = (int)Address::getFirstCustomerAddressId((int)$customer->id);
			}
			$this->context->cart->secure_key = $customer->secure_key;

			if (isset($id_carrier) && $id_carrier && Configuration::get('PS_ORDER_PROCESS_TYPE'))
			{
				$delivery_option = array($this->context->cart->id_address_delivery => $id_carrier.',');
				$this->context->cart->setDeliveryOption($delivery_option);
			}
			$this->context->cart->save();
			$this->context->cookie->id_cart = (int)$this->context->cart->id;
			$this->context->cookie->write();
			$this->context->cart->autosetProductAddress();
		}
		return 1;
	}

	protected function sendConfirmationMail($customer, $passd)
	{
		if (!Configuration::get('PS_CUSTOMER_CREATION_EMAIL'))
			return true;

		return Mail::Send(
				$this->context->language->id, 'account', Mail::l('Welcome!'), array(
				'{firstname}' => $customer->firstname,
				'{lastname}' => $customer->lastname,
				'{email}' => $customer->email,
				'{passwd}' => $passd), $customer->email,
				$customer->firstname.' '.$customer->lastname,
				null, null, null, null, dirname(__FILE__).'/mails/'
		);
	}

	protected function removeLang($url)
	{
		$lang_str = '&id_lang='.$this->context->language->id;
		$url = str_replace($lang_str, '', $url);

		$lang_str = '/'.$this->context->language->iso_code.'/';
		$url = str_replace($lang_str, '/', $url);
		return $url;
	}
}
