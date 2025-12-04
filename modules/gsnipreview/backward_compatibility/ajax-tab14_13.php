<?php
/**
 * mitrocops
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
/*
 *
 * @author    mitrocops
 * @category seo
 * @package gsnipreview
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */

$HTTP_X_REQUESTED_WITH = isset($_SERVER['HTTP_X_REQUESTED_WITH'])?$_SERVER['HTTP_X_REQUESTED_WITH']:'';
if($HTTP_X_REQUESTED_WITH != 'XMLHttpRequest') {
    exit;
}
define('_PS_ADMIN_DIR_', getcwd());
include(dirname(__FILE__).'/../../../config/config.inc.php');
include(dirname(__FILE__).'/../../../init.php');


if (Tools::isSubmit('customerFilter'))
{
    $search_query = trim(Tools::getValue('q'));
    $customers = Db::getInstance()->executeS('
			SELECT `id_customer`, `email`, CONCAT(`firstname`, \' \', `lastname`) as cname
			FROM `'._DB_PREFIX_.'customer`
			WHERE `deleted` = 0 AND is_guest = 0 AND active = 1
			AND (
				`id_customer` = '.(int)$search_query.'
				OR `email` LIKE "%'.pSQL($search_query).'%"
				OR `firstname` LIKE "%'.pSQL($search_query).'%"
				OR `lastname` LIKE "%'.pSQL($search_query).'%"
			)
			ORDER BY `firstname`, `lastname` ASC
			LIMIT 50');
    die(Tools::jsonEncode($customers));
}