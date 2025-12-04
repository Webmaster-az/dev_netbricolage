<?php
/**
* 2010-2021 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through LICENSE.txt file inside our module
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright 2010-2021 Webkul IN
* @license LICENSE.txt
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
require_once dirname(__FILE__).'/classes/define.php';

class WkPwa extends Module
{
    private $modConfHtml = '';

    public function __construct()
    {
        $this->name = 'wkpwa';
        $this->tab = 'front_office_features';
        $this->version = '6.3.0';
        $this->module_key = '5a1d8ebc579e8ebe052925d73217f13c';
        $this->author = 'Webkul';

        if (Module::isEnabled($this->name)) {
            $this->secure_key = Tools::hash($this->name);
        }

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Prestashop Advanced Progressive Web App');
        $this->description = $this->l('Using this addon you can send push notification, run your website offline and increase your website performance');
        $this->confirmUninstall = $this->l('Are you sure? All module data will be lost after uninstalling the module');
    }

    public function hookActionFrontControllerSetMedia($params)
    {
        if (!isset($this->context->cookie->id_guest)) {
            Guest::setNewGuest($this->context->cookie);
        }

        if ((Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode())) {
            $jsDef = array(
                'clientTokenUrl' => $this->context->link->getModuleLink($this->name, 'clientnotificationtoken'),
                'WK_PWA_APP_PUBLIC_SERVER_KEY' => Configuration::get('WK_PWA_APP_PUBLIC_SERVER_KEY'),
                'WK_PWA_PUSH_NOTIFICATION_ENABLE' => (int)Configuration::get('WK_PWA_PUSH_NOTIFICATION_ENABLE'),
                'serviceWorkerPath' => WkPwaHelper::getBaseDirUrl().'wk-service-worker.js',
                'appOffline' => $this->l('No connection'),
                'appOnline' => $this->l('Connected'),
                'custom_prompt_mobile' => (int) Configuration::get('WK_PWA_PUSH_NOTIFICATION_PROMPT_MOBILE_ENABLE'),
                'custom_prompt_desktop' => (int) Configuration::get('WK_PWA_PUSH_NOTIFICATION_PROMPT_DESKTOP_ENABLE'),
                'custom_prompt_lifetime' => Configuration::get('WK_PWA_PUSH_NOTIFICATION_PROMPT_LIFETIME'),
            );
            Media::addJsDef($jsDef);

            $this->context->controller->registerStylesheet(
                'module-wkpwa-wkFrontPwa-css',
                'modules/'.$this->name.'/views/css/wkFrontPwa.css',
                array('position' => 'bottom', 'priority' => 999)
            );

            $this->context->controller->registerJavascript(
                'module-wkpwa-wkFrontPwa-js',
                'modules/'.$this->name.'/views/js/wkFrontPwa.js',
                array('position' => 'bottom', 'priority' => 999)
            );
        }
    }

    public function hookDisplayHeader()
    {
        if ((Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode())) {
            $appleTouchIconUrl = _MODULE_DIR_.$this->name.'/views/img/appIcon/';
            $appleTouchIcon = _MODULE_DIR_.$this->name.'/views/img/appIcon/';
            $appleTouchIcon .= WkPwaHelper::_PWA_LOGO_NAME_.'-152x152.png';
            $appleSplashIcon = _MODULE_DIR_.$this->name.'/views/img/appIcon/';
            $appleSplashIcon .= WkPwaHelper::_PWA_LOGO_NAME_.'-512x512.png';

            $applicationFavicon = _MODULE_DIR_.$this->name.'/views/img/appIcon/';
            $applicationFavicon .= WkPwaHelper::_PWA_FAVICON_NAME_.'-72x72.png';

            $applicationThemeColor = Configuration::get('WK_PWA_THEME_COLOR');
            $applicationName = Configuration::get('PS_SHOP_NAME');
            $smartyVar = array(
                'appleTouchIcon' => $appleTouchIcon,
                'appleTouchIconUrl' => $appleTouchIconUrl,
                'appleSplashIcon' => $appleSplashIcon,
                'applicationName' => $applicationName,
                'applicationFavicon' => $applicationFavicon,
                'applicationThemeColor' => $applicationThemeColor,
                'startUrl' => WkPwaHelper::getBaseDirUrl(),
            );

            $manifestFile = _PS_MODULE_DIR_.$this->name.'/manifest.json';
            if (file_exists($manifestFile)) {
                $manifestFile = _MODULE_DIR_.$this->name.'/manifest.json';
                $smartyVar['manifestFile'] = $manifestFile;
            }

            $this->context->smarty->assign($smartyVar);
            return $this->fetch('module:'.$this->name.'/views/templates/hook/headerPwaContent.tpl');
        }
    }

    public function hookDisplayTop()
    {
        if ((Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode())) {
            $appImg = _MODULE_DIR_.$this->name.'/views/img/appIcon/';
            $appImg .= WkPwaHelper::_PWA_LOGO_NAME_.'-48x48.png';
            $this->context->smarty->assign(array(
                'wk_app_img' => $appImg,
                'WK_PWA_SHOT_NAME' => Configuration::get('WK_PWA_SHOT_NAME'),
                'WK_PWA_BG_COLOR' => Configuration::get('WK_PWA_BG_COLOR'),
                'showPwaAppBanner' => !isset($this->context->cookie->wk_pwa_banner_closed)
            ));
            return $this->fetch('module:'.$this->name.'/views/templates/hook/pwaLoader.tpl');
        }
    }

    public function hookDisplayNav1()
    {
        return $this->displayAddToDeskTopBtn(1);
    }

    public function hookDisplayBanner()
    {
        if (Configuration::get('WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP')) {
            // for mobile view
            $this->context->smarty->assign('hookType', 3);
            return $this->fetch('module:'.$this->name.'/views/templates/hook/addtodesktopbtn.tpl');
        }
    }

    public function hookDisplayMyAccountBlock()
    {
        return $this->displayAddToDeskTopBtn(2);
    }

    private function displayAddToDeskTopBtn($hookType)
    {
        $controller = Tools::getValue('controller');
        if ('order' != $controller
            && 'checkout' != $controller
            && 'orderopc' != $controller
        ) {
            if (Configuration::get('WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP')) {
                $hooks = json_decode(Configuration::get('WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK'));
                if (!empty($hooks) && in_array($hookType, $hooks)) {
                    $this->context->smarty->assign('hookType', $hookType);
                    return $this->fetch('module:'.$this->name.'/views/templates/hook/addtodesktopbtn.tpl');
                }
            }
        }
    }

    public function hookDisplayNavFullWidth()
    {
        if ((Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode())) {
            $promptLogo = _MODULE_DIR_.$this->name.'/views/img/appIcon/';
            $promptLogo .= WkPwaHelper::_PWA_LOGO_NAME_.'-72x72.png';
            $this->context->smarty->assign(array(
                'notification_prompt_logo' => $promptLogo
            ));
            return $this->fetch('module:'.$this->name.'/views/templates/hook/customnotificationpopup.tpl');
        }
    }

    public function getContent()
    {
        if (!(Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode())) {
            $this->context->smarty->assign(
                array(
                    'element_type' => 6,
                    'preference_link' => $this->context->link->getAdminLink('AdminPreferences')
                )
            );
            $this->context->controller->warnings[] = $this->context->smarty->fetch(
                _PS_MODULE_DIR_.$this->name.
                '/views/templates/admin/_partial/html_element.tpl'
            );
        }

        if (Tools::isSubmit('btnSubmit')) {
            $this->validateModConfiguration();
            if (!count($this->context->controller->errors)) {
                $this->processModuleConfiguartion();
            }
        } else {
            $this->modConfHtml .= '<br />';
        }
        $this->modConfHtml .= $this->renderForm();

        return $this->modConfHtml;
    }

    private function validateModConfiguration()
    {
        if (Tools::isSubmit('submitPwa')) {
            if (!Tools::getValue('WK_PWA_NAME')
                || !Tools::getValue('WK_PWA_SHOT_NAME')
                || !Tools::getValue('WK_PWA_BG_COLOR')
                || !Tools::getValue('WK_PWA_THEME_COLOR')
            ) {
                $this->context->controller->errors[] = $this->l('All fields are required fields.');
            }
            if (Tools::getValue('WK_PWA_NAME') && !Validate::isCatalogName(Tools::getValue('WK_PWA_NAME'))) {
                $this->context->controller->errors[] = $this->l('Enter valid Application name.');
            }
            if (Tools::getValue('WK_PWA_SHOT_NAME') && !Validate::isCatalogName(Tools::getValue('WK_PWA_SHOT_NAME'))) {
                $this->context->controller->errors[] = $this->l('Enter valid Application\'s short name.');
            }
            if (Tools::getValue('WK_PWA_BG_COLOR') && !Validate::isColor(Tools::getValue('WK_PWA_BG_COLOR'))) {
                $this->context->controller->errors[] = $this->l('Enter valid splash screen background colour code.');
            }
            if (Tools::getValue('WK_PWA_THEME_COLOR') && !Validate::isColor(Tools::getValue('WK_PWA_THEME_COLOR'))) {
                $this->context->controller->errors[] = $this->l('Enter valid theme color code.');
            }

            if ($_FILES['WK_PWA_LOGO']['size']) {
                if ($appIconError = ImageManager::validateUpload(
                    $_FILES['WK_PWA_LOGO'],
                    Tools::getMaxUploadSize(Configuration::get('PS_LIMIT_UPLOAD_IMAGE_VALUE') * 1048576)
                )) {
                    $this->context->controller->errors[] = $appIconError;
                }
            }

            if ($_FILES['WK_PWA_FAVICON']['size']) {
                if ($appIconError = ImageManager::validateUpload(
                    $_FILES['WK_PWA_FAVICON'],
                    Tools::getMaxUploadSize(Configuration::get('PS_LIMIT_UPLOAD_IMAGE_VALUE') * 1048576)
                )) {
                    $this->context->controller->errors[] = $appIconError;
                }
            }
        } elseif (Tools::isSubmit('submitPushNotification')) {
            if (!Tools::getValue('WK_PWA_SENDER_ID')
                || !Tools::getValue('WK_PWA_SERVER_KEY')
                || !Tools::getValue('WK_PWA_APP_PUBLIC_SERVER_KEY')
                || !Tools::getValue('WK_PWA_APP_PRIVATE_SERVER_KEY')
            ) {
                $this->context->controller->errors[] = $this->l('All fields are required fields.');
            }
        } elseif (Tools::isSubmit('submitGeneralConf')) {
            if (!Tools::getValue('WK_PWA_PUSH_NOTIFICATION_IMPRESSION_GOAL')) {
                $this->context->controller->errors[] = $this->l('Please set push notification impression target.');
            } elseif (!Validate::isUnsignedFloat(Tools::getValue('WK_PWA_PUSH_NOTIFICATION_IMPRESSION_GOAL'))) {
                $this->context->controller->errors[] = $this->l('Invalid value entered of push notification impression target.');
            } elseif (Tools::getValue('WK_PWA_PUSH_NOTIFICATION_IMPRESSION_GOAL') > 100) {
                $this->context->controller->errors[] = $this->l('Push notification impression target must be less or equal to 100.');
            }
            if (!Tools::getValue('WK_PWA_PUSH_NOTIFICATION_PROMPT_LIFETIME')) {
                $this->context->controller->errors[] = $this->l('Notification prompt permission time interval is required field.');
            } elseif (!Validate::isUnsignedFloat(Tools::getValue('WK_PWA_PUSH_NOTIFICATION_PROMPT_LIFETIME'))) {
                $this->context->controller->errors[] = $this->l('Notification prompt permission time interval value is invalid.');
            }
            if (Tools::getValue('WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP')) {
                if (!Tools::getValue('WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK')) {
                    $this->context->controller->errors[] = $this->l('Select atleast one position.');
                }
            }
        }
    }

    private function processModuleConfiguartion()
    {
        if (Tools::isSubmit('submitPwa')) {
            Configuration::updateValue('WK_PWA_NAME', Tools::getValue('WK_PWA_NAME'));
            Configuration::updateValue('WK_PWA_SHOT_NAME', Tools::getValue('WK_PWA_SHOT_NAME'));
            Configuration::updateValue('WK_PWA_BG_COLOR', Tools::getValue('WK_PWA_BG_COLOR'));
            Configuration::updateValue('WK_PWA_THEME_COLOR', Tools::getValue('WK_PWA_THEME_COLOR'));

            // Create icons for app
            if ($_FILES['WK_PWA_LOGO']['tmp_name']) {
                WkPwaHelper::generateAppLogo($_FILES['WK_PWA_LOGO']['tmp_name']);
                WkPwaHelper::generateIosSplashImages($_FILES['WK_PWA_LOGO']['tmp_name']);
            }

            if ($_FILES['WK_PWA_FAVICON']['tmp_name']) {
                WkPwaHelper::generateAppFavicon($_FILES['WK_PWA_FAVICON']['tmp_name']);
            }
        } elseif (Tools::isSubmit('submitPushNotification')) {
            Configuration::updateValue('WK_PWA_SENDER_ID', Tools::getValue('WK_PWA_SENDER_ID'));
            Configuration::updateValue('WK_PWA_SERVER_KEY', Tools::getValue('WK_PWA_SERVER_KEY'));
            Configuration::updateValue('WK_PWA_APP_PUBLIC_SERVER_KEY', Tools::getValue('WK_PWA_APP_PUBLIC_SERVER_KEY'));
            Configuration::updateValue(
                'WK_PWA_APP_PRIVATE_SERVER_KEY',
                Tools::getValue('WK_PWA_APP_PRIVATE_SERVER_KEY')
            );
        } elseif (Tools::isSubmit('submitGeneralConf')) {
            Configuration::updateValue(
                'WK_PWA_PUSH_NOTIFICATION_ENABLE',
                Tools::getValue('WK_PWA_PUSH_NOTIFICATION_ENABLE')
            );
            Configuration::updateValue(
                'WK_PWA_PUSH_NOTIFICATION_IMPRESSION_GOAL',
                Tools::getValue('WK_PWA_PUSH_NOTIFICATION_IMPRESSION_GOAL')
            );

            $WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD = array();
            if (Tools::getValue('WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD')) {
                $WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD = Tools::getValue('WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD');
            }
            Configuration::updateValue(
                'WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD',
                json_encode($WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD)
            );
            Configuration::updateValue(
                'WK_PWA_PUSH_NOTIFICATION_PROMPT_LIFETIME',
                Tools::getValue('WK_PWA_PUSH_NOTIFICATION_PROMPT_LIFETIME')
            );
            Configuration::updateValue(
                'WK_PWA_PUSH_NOTIFICATION_PROMPT_DESKTOP_ENABLE',
                Tools::getValue('WK_PWA_PUSH_NOTIFICATION_PROMPT_DESKTOP_ENABLE')
            );
            Configuration::updateValue(
                'WK_PWA_PUSH_NOTIFICATION_PROMPT_MOBILE_ENABLE',
                Tools::getValue('WK_PWA_PUSH_NOTIFICATION_PROMPT_MOBILE_ENABLE')
            );
            Configuration::updateValue(
                'WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP',
                Tools::getValue('WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP')
            );
            Configuration::updateValue(
                'WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK',
                json_encode(Tools::getValue('WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK'))
            );
        }

        if (Tools::isSubmit('submitPwa') || Tools::isSubmit('submitPushNotification')) {
            // Generate manifest.json file
            WkPwaHelper::generateManifestFile();
        }


        $module_config = $this->context->link->getAdminLink('AdminModules');
        Tools::redirectAdmin(
            $module_config.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&conf=4'
        );
    }


    public function renderForm()
    {
        $logoUrl = WkPwaHelper::getOriginalAppImgUrl(WkPwaHelper::_PWA_LOGO_NAME_);
        if ($logoUrl) {
            $this->context->smarty->assign(array('element_type' => 7, 'logoUrl' => $logoUrl));
            $image = $this->context->smarty->fetch(
                _PS_MODULE_DIR_.$this->name.
                '/views/templates/admin/_partial/html_element.tpl'
            );
        }

        $faviconUrl = WkPwaHelper::getOriginalAppImgUrl(WkPwaHelper::_PWA_FAVICON_NAME_);
        if ($faviconUrl) {
            $this->context->smarty->assign(array('element_type' => 8, 'faviconUrl' => $faviconUrl));
            $faviconImage = $this->context->smarty->fetch(
                _PS_MODULE_DIR_.$this->name.
                '/views/templates/admin/_partial/html_element.tpl'
            );
        }

        $objPushNotification = new WkPwaPushNotification();
        $notificationTypes = $objPushNotification->getNotificationTypes();
        $formattedNotificationType = array();
        foreach ($notificationTypes as $idNotificationType => $notificationType) {
            $formattedNotificationType[] = array(
                'id_notification_type' => $idNotificationType,
                'name' => $notificationType,
            );
        }
        $addToDeskTopHookList = array(
            array(
                'id_hook' => 1,
                'name' => $this->l('Display on top')
            ),
            array(
                'id_hook' => 2,
                'name' => $this->l('Display on bottom')
            ),
        );

        $fields_form = array();
        $fields_form[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Progressive Web App Configuration'),
                'icon' => 'icon-cogs',
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Application name'),
                    'name' => 'WK_PWA_NAME',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Application\'s short name'),
                    'name' => 'WK_PWA_SHOT_NAME',
                    'required' => true,
                    'desc' => $this->l('Recommended Application\'s short name size is 12 characters')
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Splash screen background colour'),
                    'name' => 'WK_PWA_BG_COLOR',
                    'hint' => $this->l('When you launch your web app from the home screen a number of things happen behind the scenes, While this is happening the screen goes white and appears to be installed. To provide a better user experience, you can replace the white screen with a title, background color and image'),
                    'required' => true,
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Theme color'),
                    'name' => 'WK_PWA_THEME_COLOR',
                    'required' => true,
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('App Icon'),
                    'name' => 'WK_PWA_LOGO',
                    'display_image' => true,
                    'image' => $logoUrl ? $image : false,
                    'required' => true,
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Favicon'),
                    'name' => 'WK_PWA_FAVICON',
                    'display_image' => true,
                    'image' => $faviconUrl ? $faviconImage : false,
                    'required' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'submitPwa',
                'type' => 'submit',
            )
        );

        $fields_form[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Push Notification Configuration'),
                'icon' => 'icon-cogs',
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Sender Id'),
                    'name' => 'WK_PWA_SENDER_ID',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Server Key'),
                    'name' => 'WK_PWA_SERVER_KEY',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Application public server key'),
                    'name' => 'WK_PWA_APP_PUBLIC_SERVER_KEY',
                    'required' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Application private server key'),
                    'name' => 'WK_PWA_APP_PRIVATE_SERVER_KEY',
                    'required' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'submitPushNotification',
                'type' => 'submit',
            )
        );

        $fields_form[]['form'] = array(
            'legend' => array(
                'title' => $this->l('General Configuration'),
                'icon' => 'icon-cogs',
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable Push Notification Feature'),
                    'name' => 'WK_PWA_PUSH_NOTIFICATION_ENABLE',
                    'values' => array(
                        array(
                            'id' => 'WK_PWA_PUSH_NOTIFICATION_ENABLE_on',
                            'value' => 1
                        ),
                        array(
                            'id' => 'WK_PWA_PUSH_NOTIFICATION_ENABLE_off',
                            'value' => 0
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Push notification impressions target'),
                    'suffix' => '%',
                    'name' => 'WK_PWA_PUSH_NOTIFICATION_IMPRESSION_GOAL',
                    'required' => true,
                    'class' => 'col-sm-3',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select Notification Types for notification history'),
                    'name' => 'WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD[]',
                    'multiple' => true,
                    'required' => true,
                    'class' => 'col-sm-4',
                    'options' => array(
                        'query' => $formattedNotificationType,
                        'id' => 'id_notification_type',
                        'name' => 'name'
                    ),
                    'hint' => $this->l('Select notification types for which you want to manage notification history'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show custom notification permission prompt on desktop'),
                    'hint' => $this->l('If enabled then custom notification prompt will appear on desktop before the native permission prompt.'),
                    'desc' => $this->l('If native notification permission prompt is denied then this custom prompt will not work.'),
                    'name' => 'WK_PWA_PUSH_NOTIFICATION_PROMPT_DESKTOP_ENABLE',
                    'values' => array(
                        array(
                            'id' => 'WK_PWA_PUSH_NOTIFICATION_PROMPT_DESKTOP_ENABLE_on',
                            'value' => 1
                        ),
                        array(
                            'id' => 'WK_PWA_PUSH_NOTIFICATION_PROMPT_DESKTOP_ENABLE_off',
                            'value' => 0
                        )
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show custom notification permission prompt on mobile device'),
                    'hint' => $this->l('If enabled then custom notification prompt will appear on mobile device before the native permission prompt.'),
                    'desc' => $this->l('If native notification permission prompt is denied then this custom prompt will not work.'),
                    'name' => 'WK_PWA_PUSH_NOTIFICATION_PROMPT_MOBILE_ENABLE',
                    'values' => array(
                        array(
                            'id' => 'WK_PWA_PUSH_NOTIFICATION_PROMPT_MOBILE_ENABLE_on',
                            'value' => 1
                        ),
                        array(
                            'id' => 'WK_PWA_PUSH_NOTIFICATION_PROMPT_MOBILE_ENABLE_off',
                            'value' => 0
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'suffix' => $this->l('hours'),
                    'label' => $this->l('Time interval of custom notification prompt'),
                    'hint' => $this->l('If customer cancel custom prompt and does not allow or denied the native notfication permission then custom prompt will appear again in the given time interval'),
                    'name' => 'WK_PWA_PUSH_NOTIFICATION_PROMPT_LIFETIME',
                    'required' => true,
                    'class' => 'col-sm-3'
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Display app install button.'),
                    'hint' => $this->l('Display app install button on selected position(s). It will not be displayed on checkout/order page.'),
                    'name' => 'WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP',
                    'values' => array(
                        array(
                            'id' => 'WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP_on',
                            'value' => 1
                        ),
                        array(
                            'id' => 'WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP_off',
                            'value' => 0
                        )
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Select position(s) to display app install button'),
                    'name' => 'WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK[]',
                    'multiple' => true,
                    'class' => 'col-sm-4 WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK',
                    'options' => array(
                        'query' => $addToDeskTopHookList,
                        'id' => 'id_hook',
                        'name' => 'name'
                    ),
                    'hint' => $this->l('The app install button will be displayed on the selected position(s).'),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name' => 'submitGeneralConf',
                'type' => 'submit',
            )
        );

        $cronUrl = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.
        'modules/'.$this->name.'/WkPwaCronFile.php?token='.$this->secure_key;

        $fields_form[]['form'] = array(
            'legend' => array(
                'title' => $this->l('Cron Jobs'),
                'icon' => 'icon-clock-o',
            ),
            'warning' => $this->l('Please make sure \'CURL\' library is installed on your server to execute cron tasks.'),
            'description' => $this->l('To send notification regarding').' '.'<strong>'.
            $this->l('Scheduled Push Notifications').'</strong>'.' '.$this->l('and').' '.'<strong>'.
            $this->l('Cart Reminder Notification').'</strong>'.' '.
            $this->l('admin has to set CRON job for everyday. Set following URL').
            ' :- '.'<br><strong>'.'0 0 * * * curl \''.$cronUrl.'\' </strong>',
        );

        $helper = new HelperForm();
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name.'&tab_module='.$this->tab;
        $helper->currentIndex .= '&module_name='.$this->name;
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
        );

        return $helper->generateForm($fields_form);
    }

    public function getConfigFieldsValues()
    {
        $WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD = Tools::getValue('WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD');
        if (!$WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD) {
            $WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD = json_decode(Configuration::get(
                'WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD'
            ));
        }
        $config_vars = array(
            'WK_PWA_NAME' => Tools::getValue('WK_PWA_NAME', Configuration::get('WK_PWA_NAME')),
            'WK_PWA_SHOT_NAME' => Tools::getValue('WK_PWA_SHOT_NAME', Configuration::get('WK_PWA_SHOT_NAME')),
            'WK_PWA_BG_COLOR' => Tools::getValue('WK_PWA_BG_COLOR', Configuration::get('WK_PWA_BG_COLOR')),
            'WK_PWA_THEME_COLOR' => Tools::getValue('WK_PWA_THEME_COLOR', Configuration::get('WK_PWA_THEME_COLOR')),
            'WK_PWA_SENDER_ID' => Tools::getValue('WK_PWA_SENDER_ID', Configuration::get('WK_PWA_SENDER_ID')),
            'WK_PWA_SERVER_KEY' => Tools::getValue('WK_PWA_SERVER_KEY', Configuration::get('WK_PWA_SERVER_KEY')),
            'WK_PWA_APP_PUBLIC_SERVER_KEY' => Tools::getValue(
                'WK_PWA_APP_PUBLIC_SERVER_KEY',
                Configuration::get('WK_PWA_APP_PUBLIC_SERVER_KEY')
            ),
            'WK_PWA_APP_PRIVATE_SERVER_KEY' => Tools::getValue(
                'WK_PWA_APP_PRIVATE_SERVER_KEY',
                Configuration::get('WK_PWA_APP_PRIVATE_SERVER_KEY')
            ),
            'WK_PWA_PUSH_NOTIFICATION_ENABLE' => Tools::getValue(
                'WK_PWA_PUSH_NOTIFICATION_ENABLE',
                Configuration::get('WK_PWA_PUSH_NOTIFICATION_ENABLE')
            ),
            'WK_PWA_PUSH_NOTIFICATION_IMPRESSION_GOAL' => Tools::getValue(
                'WK_PWA_PUSH_NOTIFICATION_IMPRESSION_GOAL',
                Configuration::get('WK_PWA_PUSH_NOTIFICATION_IMPRESSION_GOAL')
            ),
            'WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD[]' => $WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD,
            'WK_PWA_PUSH_NOTIFICATION_PROMPT_LIFETIME' => Tools::getValue(
                'WK_PWA_PUSH_NOTIFICATION_PROMPT_LIFETIME',
                Configuration::get('WK_PWA_PUSH_NOTIFICATION_PROMPT_LIFETIME')
            ),
            'WK_PWA_PUSH_NOTIFICATION_PROMPT_MOBILE_ENABLE' => Tools::getValue(
                'WK_PWA_PUSH_NOTIFICATION_PROMPT_MOBILE_ENABLE',
                Configuration::get('WK_PWA_PUSH_NOTIFICATION_PROMPT_MOBILE_ENABLE')
            ),
            'WK_PWA_PUSH_NOTIFICATION_PROMPT_DESKTOP_ENABLE' => Tools::getValue(
                'WK_PWA_PUSH_NOTIFICATION_PROMPT_DESKTOP_ENABLE',
                Configuration::get('WK_PWA_PUSH_NOTIFICATION_PROMPT_DESKTOP_ENABLE')
            ),
            'WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP' => Tools::getValue(
                'WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP',
                Configuration::get('WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP')
            ),
            'WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK[]' => Tools::getValue(
                'WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK',
                json_decode(Configuration::get('WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK'))
            )
        );

        return $config_vars;
    }

    public function hookWkPosDisplayHeader()
    {
        if ((Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode())) {
            $appleTouchIconUrl = _MODULE_DIR_.$this->name.'/views/img/appIcon/';
            $appleTouchIcon = _MODULE_DIR_.$this->name.'/views/img/appIcon/';
            $appleTouchIcon .= WkPwaHelper::_PWA_LOGO_NAME_.'-152x152.png';

            $applicationFavicon = _MODULE_DIR_.$this->name.'/views/img/appIcon/';
            $applicationFavicon .= WkPwaHelper::_PWA_FAVICON_NAME_.'-72x72.png';

            $applicationThemeColor = Configuration::get('WK_PWA_THEME_COLOR');
            $applicationName = Configuration::get('PS_SHOP_NAME');
            $smartyVar = array(
                'appleTouchIcon' => $appleTouchIcon,
                'appleTouchIconUrl' => $appleTouchIconUrl,
                'applicationName' => $applicationName,
                'applicationFavicon' => $applicationFavicon,
                'applicationThemeColor' => $applicationThemeColor,
                'startUrl' => WkPwaHelper::getBaseDirUrl().'module/wkpos/login',
            );

            $manifestFile = _PS_MODULE_DIR_.'wkpos/manifest.json';
            if (file_exists($manifestFile)) {
                $manifestFile = _MODULE_DIR_.'wkpos/manifest.json';
                $smartyVar['manifestFile'] = $manifestFile;
            }

            $this->context->smarty->assign($smartyVar);
            return $this->fetch('module:'.$this->name.'/views/templates/hook/headerPwaContent.tpl');
        }
    }

    public function hookActionPosSetMedia($params)
    {
        if ((Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode())) {
            $jsDef = array(
                'clientTokenUrl' => $this->context->link->getModuleLink($this->name, 'clientnotificationtoken'),
                'WK_PWA_APP_PUBLIC_SERVER_KEY' => Configuration::get('WK_PWA_APP_PUBLIC_SERVER_KEY'),
                'WK_PWA_PUSH_NOTIFICATION_ENABLE' => (int)Configuration::get('WK_PWA_PUSH_NOTIFICATION_ENABLE'),
                'serviceWorkerPath' => WkPwaHelper::getBaseDirUrl().'wk-service-worker.js',
                'appOffline' => $this->l('No connection'),
                'appOnline' => $this->l('Connected'),
            );
            $this->context->controller->posAddJsDef($jsDef);
            $this->context->controller->posAddCss(
                array(
                    _MODULE_DIR_.$this->name.'/views/css/wkFrontPwa.css',
                )
            );
            $this->context->controller->posAddJs(
                array(
                    _MODULE_DIR_.$this->name.'/views/js/wkFrontPwa.js',
                )
            );
        }
    }

    public function registerPsHook()
    {
        $moduleHooks = array(
            'displayHeader',
            'displayTop',
            'actionFrontControllerSetMedia',
            'actionAdminControllerSetMedia',
            'displayAdminProductsExtra',
            'actionOrderStatusPostUpdate',
            'displayBackOfficeHeader',
            'registerGDPRConsent',
            'actionDeleteGDPRCustomer',
            'actionExportGDPRData',
            'actionPosSetMedia',
            'wkPosDisplayHeader',
            'displayNavFullWidth',
            'displayNav1',
            'displayBanner',
            'displayMyAccountBlock',
        );
        return $this->registerHook($moduleHooks);
    }

    public function install()
    {
        if (!parent::install()
            || !$this->createTable()
            || !$this->registerPsHook()
            || !$this->setModuleConfiguration()
            || !$this->setManifestFileContent()
            || !$this->moveServiceWorkerFile()
            || !$this->callInstallTab()
        ) {
            return false;
        }
        return true;
    }

    public function hookActionDeleteGDPRCustomer($customer)
    {
        if (!empty($customer['email']) && Validate::isEmail($customer['email'])) {
            $objPushNotificationToken = new WkPwaPushNotificationToken();
            if ($objPushNotificationToken->deleteCustomerData($customer['id'])) {
                return json_encode(true);
            }
            return json_encode($this->l('Prestashop Advanced Progressive Web App : Unable to delete customer data'));
        }
    }

    public function hookActionExportGDPRData($customer)
    {
        if (!Tools::isEmpty($customer['email']) && Validate::isEmail($customer['email'])) {
            $objPushNotificationToken = new WkPwaPushNotificationToken();
            if ($customerNotificationData = $objPushNotificationToken->getCustomerData($customer['id'])) {
                return json_encode($customerNotificationData);
            }
            return json_encode($this->l('Prestashop Advanced Progressive Web App : Unable to export customer data'));
        }
    }

    public function createTable()
    {
        $objPwaHelper = new WkPwaHelper();
        return $objPwaHelper->createWkPwaTables();
    }

    public function setModuleConfiguration()
    {
        Configuration::updateValue('WK_PWA_NAME', Configuration::get('PS_SHOP_NAME'));
        Configuration::updateValue('WK_PWA_SHOT_NAME', 'PWA Lite');
        Configuration::updateValue('WK_PWA_BG_COLOR', "#0098ed");
        Configuration::updateValue('WK_PWA_THEME_COLOR', "#0098ed");
        Configuration::updateValue('WK_PWA_PUSH_NOTIFICATION_ENABLE', 1);
        Configuration::updateValue('WK_PWA_PUSH_NOTIFICATION_IMPRESSION_GOAL', 70);
        Configuration::updateValue('WK_PWA_PUSH_NOTIFICATION_TYPE_RECORD', '["1","2","3"]');
        Configuration::updateValue('WK_PWA_PUSH_NOTIFICATION_PROMPT_LIFETIME', 1);
        Configuration::updateValue('WK_PWA_PUSH_NOTIFICATION_PROMPT_DESKTOP_ENABLE', 0);
        Configuration::updateValue('WK_PWA_PUSH_NOTIFICATION_PROMPT_MOBILE_ENABLE', 0);
        Configuration::updateValue('WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP', 0);
        Configuration::updateValue('WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK', '["1","2"]');
        // App Logo
        $logoSrc = _PS_IMG_DIR_.Configuration::get('PS_LOGO');
        WkPwaHelper::generateAppLogo($logoSrc);
        WkPwaHelper::generateIosSplashImages($logoSrc);

        // App Notificaiton Icon
        $faviconSrc = _PS_IMG_DIR_.Configuration::get('PS_LOGO');
        WkPwaHelper::generateAppFavicon($faviconSrc);

        return true;
    }

    public function setManifestFileContent()
    {
        return WkPwaHelper::generateManifestFile();
    }

    // Move Service Worker file to root folder
    public function moveServiceWorkerFile()
    {
        $source = _PS_MODULE_DIR_.$this->name.'/views/js/wk-service-worker.js';
        $destination = _PS_ROOT_DIR_.'/wk-service-worker.js';
        return Tools::copy($source, $destination);
    }

    public function callInstallTab()
    {
        $this->installTab('AdminPwaManagement', 'Progressive Web App');
        $this->installTab('AdminPushNotification', 'Manage Notification', 'AdminPwaManagement');

        $this->installTab('AdminPushNotificationConfiguration', 'Manage Push Notifications', 'AdminPushNotification');
        $this->installTab(
            'AdminManualPushNotification',
            'Manual Push Notification',
            'AdminPushNotificationConfiguration'
        );
        $this->installTab(
            'AdminNewProductPushNotification',
            'Product Notification',
            'AdminPushNotificationConfiguration'
        );
        $this->installTab(
            'AdminOrderStatusPushNotification',
            'Order Status Notification',
            'AdminPushNotificationConfiguration'
        );
        $this->installTab(
            'AdminWelcomePushNotification',
            'Welcome Notification',
            'AdminPushNotificationConfiguration'
        );
        $this->installTab(
            'AdminCartReminderPushNotification',
            'Cart Reminder Notification',
            'AdminPushNotificationConfiguration'
        );

        $this->installTab('AdminPushNotificationSubscriber', 'Manage Subscribers', 'AdminPushNotification');
        $this->installTab('AdminPushNotificationHistory', 'Manage Notification History', 'AdminPushNotification');

        return true;
    }

    public function installTab($className, $tabName, $tabParentName = false)
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->name = array();

        if ($className =='AdminPushNotification') { //Tab name for which you want to add icon
            $tab->icon = 'flash_on'; //Material Icon name
        }

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $tabName;
        }

        if ($tabParentName) {
            $tab->id_parent = (int) Tab::getIdFromClassName($tabParentName);
        } else {
            $tab->id_parent = 0;
        }

        $tab->module = $this->name;

        return $tab->add();
    }

    public function uninstall($keep = true)
    {
        if (!parent::uninstall()
            || ($keep && !$this->deleteTables())
            || !$this->deleteModuleConfiguration()
            || !$this->deleteServiceWorkerFile()
            || !$this->deleteNotificationIcons()
            || !$this->unInstallTab()
        ) {
            return false;
        }

        return true;
    }

    public function deleteTables()
    {
        $objPwaHelper = new WkPwaHelper();
        return $objPwaHelper->deleteWkPwaTables();
    }

    public function deleteModuleConfiguration()
    {
        $configurationKeys = array(
            'WK_PWA_NAME',
            'WK_PWA_SHOT_NAME',
            'WK_PWA_BG_COLOR',
            'WK_PWA_THEME_COLOR',
            'WK_PWA_SENDER_ID',
            'WK_PWA_SERVER_KEY',
            'WK_PWA_APP_PUBLIC_SERVER_KEY',
            'WK_PWA_APP_PRIVATE_SERVER_KEY',
            'WK_PWA_PUSH_NOTIFICATION_ENABLE',
            'WK_PWA_PUSH_NOTIFICATION_IMPRESSION_GOAL',
            'WK_PWA_PUSH_NOTIFICATION_PROMPT_LIFETIME',
            'WK_PWA_PUSH_NOTIFICATION_PROMPT_DESKTOP_ENABLE',
            'WK_PWA_PUSH_NOTIFICATION_PROMPT_MOBILE_ENABLE',
            'WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP',
            'WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK'
        );
        foreach ($configurationKeys as $key) {
            if (!Configuration::deleteByName($key)) {
                return false;
            }
        }

        // Delete app logo files
        WkPwaHelper::deleteAppLogo(WkPwaHelper::_PWA_LOGO_NAME_);
        WkPwaHelper::deleteAppLogo(WkPwaHelper::_PWA_FAVICON_NAME_);

        return true;
    }

    public function deleteServiceWorkerFile()
    {
        $source = _PS_ROOT_DIR_.'/wk-service-worker.js';
        if (file_exists($source)) {
            @unlink($source);
        }

        return true;
    }

    public function deleteNotificationIcons()
    {
        return WkPwaHelper::emptyNotificationIconFolder();
    }

    public function unInstallTab()
    {
        $moduleTabs = Tab::getCollectionFromModule($this->name);
        if (!empty($moduleTabs)) {
            foreach ($moduleTabs as $moduleTab) {
                $moduleTab->delete();
            }
        }

        return true;
    }

    public function reset()
    {
        if (!$this->uninstall(false)) {
            return false;
        }
        if (!$this->install(false)) {
            return false;
        }

        return true;
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        $product = new Product((int)$params['id_product'], false, $this->context->language->id);
        if ($productPushNotificationEnabled = WkPwaPushNotificationType::isNotificationTypeActive(
            WkPwaPushNotification::NEW_PRODUCT_NOTIFICATION
        )) {
            $objPushNotification = new WkPwaPushNotification();
            $notificationDetail = $objPushNotification->getByIdNotificationType(
                WkPwaPushNotification::NEW_PRODUCT_NOTIFICATION
            );
            if ($notificationDetail) {
                $this->context->smarty->assign('idPushNotification', $notificationDetail['id']);
            }
        }

        $this->context->smarty->assign(
            array(
                'WK_PWA_PUSH_NOTIFICATION_ENABLE' => Configuration::get('WK_PWA_PUSH_NOTIFICATION_ENABLE'),
                'productPushNotificationEnabled' => $productPushNotificationEnabled,
                'isProductStateChanged' => $product->state,
                'productId' => $params['id_product'],
                'generalConfigLink' => $this->context->link->getAdminLink(
                    'AdminModules',
                    true,
                    array(),
                    array('configure' => $this->name)
                ),
                'productNotifConfigLink' => $this->context->link->getAdminLink('AdminNewProductPushNotification'),
                'pushNotifiationProgress' => $this->context->smarty->fetch(
                    _PS_MODULE_DIR_.$this->name.'/views/templates/admin/_partial/push_notification_progress.tpl'
                )
            )
        );
        return $this->display(__FILE__, 'productpushnotificationbutton.tpl');
    }

    public function hookActionOrderStatusPostUpdate($params)
    {
        if (WkPwaPushNotificationType::isNotificationTypeActive(WkPwaPushNotification::ORDER_STATUS_NOTIFICATION)) {
            $objPushNotification = new WkPwaPushNotification();
            $idNotification = $objPushNotification->isOrderNotificationExists(
                $params['newOrderStatus']->id
            );
            if ($idNotification) {
                $objPushNotification->sendPushNotification($idNotification, $params['id_order']);
            }
        }
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (isset($this->context->controller->module)) {
            if ($this->context->controller->module->name == $this->name) {
                if (!(Configuration::get('PS_SSL_ENABLED') || Tools::usingSecureMode())) {
                    $this->context->smarty->assign(
                        array(
                            'element_type' => 6,
                            'preference_link' => $this->context->link->getAdminLink('AdminPreferences')
                        )
                    );
                    $this->context->controller->warnings[] = $this->context->smarty->fetch(
                        _PS_MODULE_DIR_.$this->name.
                        '/views/templates/admin/_partial/html_element.tpl'
                    );
                }
            }
        }
    }

    public function hookActionAdminControllerSetMedia()
    {
        if (Tools::getValue('controller') == 'AdminProducts') {
            Media::addJsDef(array(
                'sendNotificationContLink' => $this->context->link->getAdminlink('AdminManualPushNotification'),
                'anotherPushProcessError' => $this->l('Please wait, another push notification process is in progress.'),
                'noSubscriberError' => $this->l('No subscriber exists !!'),
                'successMsgPrefix' => $this->l('Product Notification successfully delivered to'),
                'successMsgSuffix' => $this->l('subscriber(s)'),
            ));
            $this->context->controller->addCSS(_MODULE_DIR_.$this->name.'/views/css/admin/push_notification.css');
            $this->context->controller->addJS(_MODULE_DIR_.$this->name.'/views/js/admin/push_notification.js');
        }

        if ((Tools::getValue('controller') == 'AdminModules') && (Tools::getValue('configure') == $this->name)) {
            $this->context->controller->addJS(_MODULE_DIR_.$this->name.'/views/js/admin/push_notification_config.js');
        }
    }
}
