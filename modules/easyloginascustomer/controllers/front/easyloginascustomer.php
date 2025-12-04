<?php
/**
* Easy login as Customer
*
* NOTICE OF LICENSE
*
*    @author    Remy Combe <remy.combe@gmail.com>
*    @copyright 2013-2016 Remy Combe
*    @license   go to addons.prestastore.com (buy one module for one shop).
*    @for    PrestaShop version 1.7
*/

class EasyLogInAsCustomereasyloginascustomerModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $auth = false;
    public $authRedirection = 'my-account';

    public function initContent()
    {
        parent::initContent();

        $context = Context::getContext();
        $context->cookie->logout(); /* delete cookie */

        $admin_access = false;
        $valid_token = false;
        $id_employee = false;
        $id_profile = false;

        /* Get admin information */
        if (Shop::isFeatureActive()) { /* if multishop enabled get from url */
            $id_employee = (int)Tools::getValue('eid');
            $employee_passwd = (string)trim(addslashes(strip_tags(Tools::getValue('epwd'))));

            if ((int)$id_employee > 0
                && Tools::strlen($employee_passwd) == 15) {
                $admin_access = (bool)Db::getInstance()->getValue(
                    'SELECT COUNT(`id_employee`)
                    FROM `'._DB_PREFIX_.'employee`
                    WHERE `id_employee` = '.(int)$id_employee.'
                    AND `passwd` LIKE \''.$this->pSQL($employee_passwd).'%\'
                    AND `active` = 1'
                );

                $id_profile = Db::getInstance()->getValue(
                    'SELECT `id_profile`
                    FROM `'._DB_PREFIX_.'employee` 
                    WHERE `id_employee` = '.(int)$id_employee
                );
            }
        } else { /* multishop disabled */
            /* Get admin cookie */
            $admin = new Cookie('psAdmin');

            if (isset($admin->id_employee)
                && isset($admin->profile)
                && isset($admin->email)
                && isset($admin->passwd)) {
                $admin_access = (bool)Db::getInstance()->getValue(
                    'SELECT COUNT(`id_employee`) 
                    FROM `'._DB_PREFIX_.'employee` 
                    WHERE `id_employee` = '.(int)$admin->id_employee.' 
                    AND `id_profile` = '.(int)$admin->profile.' 
                    AND `email` = \''.$this->pSQL($admin->email).'\'
                    AND `passwd` = \''.$this->pSQL($admin->passwd).'\'
                    AND `active` = 1'
                );
                $id_employee = (int)$admin->id_employee;
                $id_profile = (int)$admin->profile;
            }
        }

        /* Check token */
        $param_token = 'AdminEasyLoginAsCustomer'
            .(int)Tab::getIdFromClassName('AdminEasyLoginAsCustomer').(int)$id_employee;
        $token_admin_customers = trim(Tools::getAdminToken($param_token));
        if ($admin_access && Tools::getValue('token') && $id_employee) {
            if (trim(Tools::getValue('token')) == $token_admin_customers) {
                $valid_token = true;
            }
        }

        /* Get customer information */
        $passwd = trim(Tools::getValue('passwd'));
        $email = trim(Tools::getValue('email'));
        $id = trim(Tools::getValue('id'));
        $secure_key = $this->pSQL(Tools::getValue('key')); // pSQL must be also before to check the true length of key

        /* Check if allowed */
        if (empty($email)) {
            echo Tools::displayError('Error during authentication (0x01)'); /* No email address */
            exit;
        } elseif (!Validate::isEmail($email)) {
            echo Tools::displayError('Error during authentication (0x02)'); /* Invalid email address */
            exit;
        } elseif (empty($passwd)) {
            echo Tools::displayError('Error during authentication (0x03)'); /* Password is required */
            exit;
        } elseif ((int)$id <= 0) {
            echo Tools::displayError('Error during authentication (0x04)'); /* Customer ID is required */
            exit;
        } elseif (Tools::strlen($secure_key) != 10) {
            echo Tools::displayError('Error during authentication (0x05)');
            exit;
        } elseif (!$admin_access) {
            echo Tools::displayError('Error during authentication (0x06)');
            exit;
        } elseif (!$valid_token) {
            echo Tools::displayError('Error during authentication (0x07)');
            exit;
        } else {
            /* Check if customer exists and valids */
            $check = Db::getInstance()->getValue(
                'SELECT COUNT(`id_customer`)
                FROM `'._DB_PREFIX_.'customer`
                WHERE `id_customer` = '.(int)$id.'
                AND `email` = \''.$this->pSQL($email).'\'
                AND `passwd` = \''.$this->pSQL($passwd).'\'
                AND `secure_key` LIKE \''.$this->pSQL($secure_key).'%\'
                AND `active` = 1
                AND `deleted` = 0
                AND `is_guest` = 0'
            );
            if ($check) { /* Check if allowed to use this feature */
                $slug = Access::findSlugByIdModule((int)$this->module->id).Access::getAuthorizationFromLegacy('view');
                $allowed = Access::isGranted($slug, (int)$id_profile);
                if ((int)$id_profile == 1 || $allowed) {
                    $customer = new Customer((int)$id);
                    if ($customer) {
                        /* function submit() from CustomerLoginForm.php */
                        Hook::exec('actionAuthenticationBefore');
                        $this->context->updateCustomer($customer);
                        Hook::exec('actionAuthentication', ['customer' => $this->context->customer]);
                        // Login information have changed, so we check if the cart rules still apply
                        CartRule::autoRemoveFromCart($this->context);
                        CartRule::autoAddToCart($this->context);

                        /* Save customer email for front office navigation bar */
                        $save = false;
                        if (Configuration::getGlobalValue('LOGINASCUSTOMER_FORCE_SETTINGS')
                            && Configuration::getGlobalValue('LOGINASCUSTOMER_SHOW_NAVIGATION')) {
                            $save = true;
                        }

                        if (!$save && !Configuration::getGlobalValue('LOGINASCUSTOMER_FORCE_SETTINGS')) {
                            $show_nav = Db::getInstance()->getValue(
                                'SELECT `show_navigation`
                                FROM `'._DB_PREFIX_.'easyloginascustomer_configuration`
                                WHERE `id_employee` = '.(int)$id_employee
                            );
                            if ($show_nav) {
                                $save = true;
                            } else {
                                $employee = Db::getInstance()->getValue(
                                    'SELECT COUNT(`id_employee`)
                                    FROM `'._DB_PREFIX_.'easyloginascustomer_configuration`
                                    WHERE `id_employee` = '.(int)$id_employee
                                );
                                if (!$employee) {
                                    $save = true;
                                }
                            }
                        }

                        if ($save) {
                            if (isset($context->cookie->loginascustomer_connected)) {
                                $context->cookie->loginascustomer_connected = $customer->email;
                            } else {
                                $context->cookie->__set('loginascustomer_connected', $customer->email);
                            }
                        } elseif (isset($context->cookie->loginascustomer_connected)) {
                            $context->cookie->__unset('loginascustomer_connected');
                        }

                        /* History */
                        Db::getInstance()->Execute(
                            'INSERT INTO `'._DB_PREFIX_.'easyloginascustomer_history` (
                                `id_easyloginascustomer_history`,
                                `id_employee`,
                                `id_customer`,
                                `date_add`
                            ) VALUES (
                                NULL,
                                '.(int)$id_employee.',
                                '.(int)$customer->id.',
                                NOW()
                            )'
                        );

                        /* Delete logs older than 1 month */
                        Db::getInstance()->Execute(
                            'DELETE
                            FROM `'._DB_PREFIX_.'easyloginascustomer_history`
                            WHERE `date_add` < (NOW() - INTERVAL 1 MONTH)'
                        );

                        /* Redirect to customer account */
                        $redirect = (int)Configuration::getGlobalValue('LOGINASCUSTOMER_REDIRECT');
                        if ($redirect == 1) { /* Home page */
                            $redirect_url = '';
                        } elseif ($redirect == 2) { /* Order page */
                            if (Configuration::get('PS_ORDER_PROCESS_TYPE')) {
                                $redirect_url = 'index.php?controller=order-opc';
                            } else {
                                $redirect_url = 'index.php?controller=order';
                            }
                        } elseif ($redirect == 3) { /* History page */
                            $redirect_url = 'index.php?controller=history';
                        } else { /* Default is my account page */
                            $redirect_url = 'index.php?controller=my-account';
                        }
                        Tools::redirect($redirect_url);
                    }
                }
            }
        }
    }
    public function pSQL($string)
    {
        $string = trim(addslashes(strip_tags((string)$string)));
        $string = str_replace('%', '', $string);
        return pSQL($string);
    }
}
