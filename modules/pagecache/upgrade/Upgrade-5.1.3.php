<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 * @author    Jpresta
 * @copyright Jpresta
 * @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

/*
 * MySQL 5.5 compatibility
 */
function upgrade_module_5_1_3()
{
    $sql = 'ALTER TABLE `'._DB_PREFIX_.'jm_pagecache_prof` CHANGE COLUMN `date_exec` `date_exec` timestamp DEFAULT CURRENT_TIMESTAMP';

    return (bool) Db::getInstance()->execute($sql);
}
