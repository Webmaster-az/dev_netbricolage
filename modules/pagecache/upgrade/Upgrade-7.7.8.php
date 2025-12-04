<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 * @author    Jpresta
 * @copyright Jpresta
 * @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

/**
 * @var $module Jprestaspeedpack
 * @return bool
 */
function upgrade_module_7_7_8($module)
{
    $ret = true;

    JprestaUtils::dbCreateIndexIfNotExists(_DB_PREFIX_ . 'specific_price_rule', ['id_country', 'to']);
    JprestaUtils::dbCreateIndexIfNotExists(_DB_PREFIX_ . 'specific_price_rule', ['id_group', 'to']);
    JprestaUtils::dbCreateIndexIfNotExists(_DB_PREFIX_ . 'specific_price', ['id_country', 'to']);
    JprestaUtils::dbCreateIndexIfNotExists(_DB_PREFIX_ . 'specific_price', ['id_group', 'to']);

    return (bool)$ret;
}
