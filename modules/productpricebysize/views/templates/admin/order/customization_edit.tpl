{*
* 2007-2016 PrestaShop
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
*  @author Musaffar Patel <musaffar.patel@gmail.com>
*  @copyright  2015-2021 Musaffar Patel
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Property of Musaffar Patel
*}

<div class="ppbs-customization-edit">
	<div class="row" style="margin-top: 10px;">
		{foreach from=$fields item=field}
			<div class="col-xs-4">
				<label>{$field->display_name|escape:'htmlall':'UTF-8'}</label>
				<input name="{$field->display_name|escape:'htmlall':'UTF-8'}" class="ppbs-customization-field"
					   data-display_name="{$field->display_name|escape:'htmlall':'UTF-8'}"
					   data-id_ppbs_dimension="{$field->id_ppbs_dimension|escape:'htmlall':'UTF-8'}"
					   data-symbol="{$field->symbol|escape:'htmlall':'UTF-8'}"
					   type="text" value="{$field->value|escape:'htmlall':'UTF-8'}">
				<label>{$field->symbol|escape:'htmlall':'UTF-8'}</label>
			</div>
		{/foreach}
	</div>
	<div style="margin-top: 10px;">
		<label>{l s='Price' mod='productpricebysize'}</label>
		<input id="ppbs-customization-price" name="ppbs-customization-price" type="text" value="">
		<label id="lbl-ppbs-customization-price" style="padding: 5px;"></label> ({l s='Incl Tax' mod='productpricebysize'})
	</div>
	<button type="button" class="btn btn-default btn-ppbs-customization-apply"
			data-id_product="{$id_product|escape:'htmlall':'UTF-8'}"
			data-id_product_attribute="{$id_product_attribute|escape:'htmlall':'UTF-8'}"
			data-id_customization="{$id_customization|escape:'htmlall':'UTF-8'}"
			data-id_address_delivery="{$id_address_delivery|escape:'htmlall':'UTF-8'}"
			data-id_order="{$id_order|escape:'htmlall':'UTF-8'}"
			data-quantity="{$quantity|escape:'htmlall':'UTF-8'}">
		<i class="icon-pencil"></i>
		{l s='Apply' mod='productpricebysize'}
	</button>
</div>