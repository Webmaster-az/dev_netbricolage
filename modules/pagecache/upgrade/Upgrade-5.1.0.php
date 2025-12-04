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
 * Creates the table for profiling and set default settings
 */
function upgrade_module_5_1_0()
{
    // Disable profiling by default
    Configuration::updateValue('pagecache_profiling', false);
    Configuration::updateValue('pagecache_profiling_min_ms', 100);
    Configuration::updateValue('pagecache_profiling_max_reached', false);

    $create_table = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . PageCacheDAO::TABLE_PROFILING . '`(
            `id_profiling` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `id_module` int(10) unsigned NOT NULL,
            `description` varchar(255) NOT NULL,
            `date_exec` datetime DEFAULT NOW(),
            `duration_ms` mediumint unsigned NOT NULL,
            PRIMARY KEY (`id_profiling`)
            ) ENGINE=' . PageCacheDAO::MYSQL_ENGINE . ' DEFAULT CHARSET=utf8';
    return (bool)Db::getInstance()->execute($create_table);
}
