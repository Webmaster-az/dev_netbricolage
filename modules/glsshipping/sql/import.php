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

$sql[] = "INSERT INTO "._DB_PREFIX_."gls_envios (id_envio_order, codigo_envio, url_track, num_albaran, codigo_barras, bultos, fecha)
			SELECT id_envio_order, codigo_envio, url_track, num_albaran, codigo_barras, bultos, fecha
			FROM "._DB_PREFIX_."asm_envios";
$sql[] = "INSERT INTO "._DB_PREFIX_."gls_email (titulo, mensaje)
			SELECT titulo, mensaje
			FROM "._DB_PREFIX_."asm_email";
$sql[] = "INSERT INTO "._DB_PREFIX_."gls_parcels (id_cart, codigo, nombre, direccion, cp, localidad)
			SELECT id_cart, codigo, nombre, direccion, cp, localidad
			FROM "._DB_PREFIX_."asm_parcels";

foreach ($sql as $query) {
    if (Db::getInstance()->Execute($query) == false) {
        return false;
    }
}
