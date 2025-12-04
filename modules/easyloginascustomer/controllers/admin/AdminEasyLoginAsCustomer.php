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

class AdminEasyLoginAsCustomerController extends ModuleAdminController
{
    public function __construct()
    {
        $this->name = 'easyloginascustomer';
        $this->className = 'EasyLoginAsCustomer';
        $this->controller_name = 'AdminEasyLoginAsCustomer';
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();
    }

    public function ajaxProcessSearchCustomers()
    {
        $context = Context::getContext();
        $company = Configuration::getGlobalValue('LOGINASCUSTOMER_SEARCH_COMPANY');
        $stng = 'strong';
        $search = trim(Tools::getValue('customer_search'));
        if (!empty($search)) {
            $words = explode(' ', $search);
            $where = '';
            foreach ($words as $word) {
                $word = trim($word);
                if ($word != '') {
                    if (Validate::isInt($word)) {
                        $where .= ' AND c.`id_customer` = '.(int)$word.' ';
                    } else {
                        $where .= ' AND (c.`email` LIKE \'%'.pSQL($word).'%\'
                            OR c.`lastname` LIKE \'%'.pSQL($word).'%\'
                            OR c.`firstname` LIKE \'%'.pSQL($word).'%\''
                            .($company
                                ? ' OR c.`company` LIKE \'%'.pSQL($word).'%\' OR a.`company` LIKE \'%'.pSQL($word).'%\''
                                : ''
                            ).') ';
                    }
                }
            }

            $customers = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS(
                'SELECT c.`firstname`, c.`lastname`, c.`email`, c.`id_customer`, c.`passwd`, c.`secure_key`'
                .($company ? ', IF (c.`company` IS NOT NULL, c.`company`, a.`company`) AS `customer_company`' : '').'
                FROM `'._DB_PREFIX_.'customer` c'
                .($company
                    ? ' LEFT JOIN `'._DB_PREFIX_.'address` a ON (a.`id_customer` = c.`id_customer`
                        AND a.`active` = 1 AND a.`deleted` = 0)'
                    : ''
                ).'
                WHERE c.`active` = 1'.$where.'
                AND c.`deleted` = 0
                AND c.`is_guest` = 0
                AND c.`id_shop` = '.(int)$context->shop->id
                .($company ? ' GROUP BY c.`id_customer` ' : '').'
                LIMIT 0, 20'
            );
            foreach ($customers as &$c) {
                $c['url'] = '&id='.(int)$c['id_customer'].'&email='.pSQL($c['email']).'&passwd='.pSQL($c['passwd'])
                    .'&key='.pSQL(Tools::substr($c['secure_key'], 0, 10));
                if ($company) {
                    if ($c['customer_company'] == '') {
                        $company_address = Db::getInstance()->getValue(
                            'SELECT `company`
                            FROM `'._DB_PREFIX_.'address`
                            WHERE `id_customer` = '.(int)$c['id_customer'].'
                            AND `company` IS NOT NULL
                            AND `active` = 1
                            AND `deleted` = 0
                            ORDER BY `date_upd` DESC'
                        );
                        if ($company_address) {
                            $c['firstname'] = '<'.$stng.'>'.$company_address.'</'.$stng.'> '.$c['firstname'];
                        }
                    } else {
                        $c['firstname'] = '<'.$stng.'>'.$c['customer_company'].'</'.$stng.'> '.$c['firstname'];
                    }
                }
                unset($c['secure_key']);
                unset($c['passwd']);
            }

            if (count($customers)) {
                $to_return = array(
                    'customers' => $customers,
                    'found' => true
                );
            } else {
                $to_return = array('found' => false);
            }

            $this->content = Tools::jsonEncode($to_return);

            echo Tools::jsonEncode($to_return);
        }
        exit; /* exit to prevent comments.
            Maybe replace in the future by:$this->content = Tools::jsonEncode($to_return);
            When PS 1.7 will be ready */
    }
}
