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

<div id="ppbs-field-list">
	<table id="ppbs-field-list-table" class="ui-sortable table">
		<thead>
		<tr class="nodrag nodrop">
			<th><span class="title_box">{l s='Name' mod='productpricebysize'}</span></th>
			<th><span class="title_box">{l s='Unit' mod='productpricebysize'}</span></th>
			<th><span class="title_box">{l s='Input Type' mod='productpricebysize'}</span></th>
			<th><span class="title_box">{l s='Default' mod='productpricebysize'}</span></th>
			<th><span class="title_box">{l s='Min' mod='productpricebysize'}</span></th>
			<th><span class="title_box">{l s='Max' mod='productpricebysize'}</span></th>
			<th><span class="title_box">{l s='Ratio' mod='productpricebysize'}</span></th>
			<th><span class="title_box">{l s='Visible' mod='productpricebysize'}</span></th>
			<th><span class="title_box">{l s='Action' mod='productpricebysize'}</span></th>
			<th><span class="title_box">{l s='Position' mod='productpricebysize'}</span></th>
		</tr>
		</thead>
		<tbody>
			{foreach from=$fields item=field}
				<tr data-id_ppbs_product_field="{$field.id_ppbs_product_field|intval}">
					<td>{$field.dimension_name|escape:'htmlall':'UTF-8'}</td>
					<td>{$field.unit_name|escape:'htmlall':'UTF-8'}</td>
					<td>{$field.input_type|escape:'htmlall':'UTF-8'}</td>
					<td>{$field.default|escape:'htmlall':'UTF-8'}</td>
					<td>{$field.min|escape:'htmlall':'UTF-8'}</td>
					<td>{$field.max|escape:'htmlall':'UTF-8'}</td>
					<td>{$field.ratio|escape:'htmlall':'UTF-8'}</td>
					<td class="visible">
						<span class="list-action-enable {if $field.visible eq 0}action-disabled{else}action-enabled{/if}">
							<i class="ppbs-visible material-icons" {if $field.visible eq 0}hidden{/if} data-state="0">done</i>
							<i class="ppbs-visible material-icons" {if $field.visible eq 1}hidden{/if} data-state="1">clear</i>
						</span>
					</td>
					<td>
						<a href="#edit" class="ppbs-field-edit"><i class="material-icons">edit</i></a>
						<a href="#delete" class="ppbs-field-delete"><i class="material-icons">delete forever</i></a>
					</td>
					<td class="dragHandle pointer">
						<div class="dragGroup">
							<i class="material-icons" style="cursor: pointer">swap_vert</i>
						</div>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>

<button id="ppbs-productfield-add" type="button" class="btn btn-primary">{l s='Add Field' mod='productpricebysize'}</button>