<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
$sql = array();

$sql[] = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."gls_envios (
			        id_envio int(11) NOT NULL AUTO_INCREMENT,
			        id_envio_order int(11) NOT NULL,
			        current_state int(11),
			        state_history text,
			        codigo_envio varchar(50) NOT NULL,
			        url_track varchar(255) NOT NULL,
			        num_albaran varchar(100) NOT NULL,
			        codigo_barras text,
			        bultos int(11),
			        retorno INT(3),
			        rcs INT(3),
			        peso DECIMAL(11,2),
			        vsec DECIMAL(11,2),
			        dorig VARCHAR(50),
			        observaciones VARCHAR(254),
			        fecha datetime NOT NULL,
			        PRIMARY KEY (`id_envio`)
			      ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8 ";
				  


$sql[] = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."gls_email (
			        id int(11) NOT NULL AUTO_INCREMENT,
			        titulo varchar(128),
			        mensaje text,
			        PRIMARY KEY (`id`)
			      ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8 ";


				  
$sql[] = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."gls_parcels (
			        id int(11) NOT NULL AUTO_INCREMENT,
			        id_cart int(11) NOT NULL,
			        codigo text,
			        nombre text,
			        direccion text,
			        cp text,
			        localidad text,
			        PRIMARY KEY (`id`),
					UNIQUE KEY (`id_cart`)
			      ) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8 ";

$this->logger->logInfo($sql);

foreach ($sql as $query) {
    if (Db::getInstance()->Execute($query) == false) {
        return false;
    }
}
