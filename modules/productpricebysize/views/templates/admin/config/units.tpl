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
*  @copyright  2007-2021 Musaffar Patel
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Property of Musaffar Patel
*}

<div class="row">
	<div id="ppbs-units-list" class="col-sm-6">
		<h4>{l s='Units' mod='productpricebysize'}</h4>
		<table class="table">
			<thead>
			<tr>
				<th>{l s='Name' mod='productpricebysize'}</th>
				<th>{l s='Action' mod='productpricebysize'}</th>
			</tr>
			</thead>
			<tbody>
				{foreach from=$units item=unit}
				<tr data-id="{$unit.id_ppbs_unit|escape:'html':'UTF-8'}">
					<td>{$unit.name|escape:'html':'UTF-8'}</td>
					<td>
						<i class="ppbs-unit-edit material-icons" style="cursor: pointer;">edit</i>
						<i class="ppbs-unit-delete material-icons" style="cursor: pointer;">delete forever</i>
					</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
	</div>

	<div id="ppbs-unit-add" class="col-sm-6">

		<div id="form-unit-dimension-add" class="form-wrapper ppbs-form-wrapper" style="padding-left: 15px;">

			<h4>{l s='Add New Unit' mod='productpricebysize'}</h4>
			<div class="alert alert-danger mp-errors" style="display: none"></div>

			<input name="id_ppbs_unit" value="{if !empty($edit_unit->id_ppbs_unit)}{$edit_unit->id_ppbs_unit|escape:'html':'UTF-8'}{/if}" type="hidden">

			<div class="form-group row">
				<div class="col-sm-12">
					<label>{l s='Name' mod='productpricebysize'}</label>
					<input id="name" type="text" name="name" class="form-control search" placeholder="{l s='name of unit e.g - cm' mod='productpricebysize'}" autocomplete="off"
						   value="{if !empty($edit_unit->name)}{$edit_unit->name|escape:'html':'UTF-8'}{/if}" />
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-12">
					<label>{l s='Symbol' mod='productpricebysize'}</label>
					{foreach from=$languages item=language}
					<div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" style="{if $language.id_lang eq $id_lang_default}display: block;{else}display:none;{/if}">
						<div class="col-lg-7">
							<input name="symbol_{$language.id_lang|escape:'htmlall':'UTF-8'}" id="symbol_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="form-control"
								   value="{if !empty($edit_unit->symbol[$language.id_lang])}{$edit_unit->symbol[$language.id_lang]|escape:'html':'UTF-8'}{/if}" />
						</div>

						<div class="col-lg-2">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
								{$language.iso_code|escape:'htmlall':'UTF-8'}
							</button>

							<ul class="dropdown-menu">
								{foreach from=$languages item=language_dropdown}
									<li>
										<a href="javascript:hideOtherLanguage({$language_dropdown.id_lang|escape:'htmlall':'UTF-8'});">{$language_dropdown.name|escape:'htmlall':'UTF-8'}</a>
									</li>
								{/foreach}
							</ul>
						</div>
					</div>
					{/foreach}
				</div>
			</div>

			<div>
				<button type="submit" id="ppbs-unit-save" class="btn btn-primary">
					{if !empty($edit_unit->id_ppbs_unit)}
						{l s='Update Unit' mod='productpricebysize'}
					{else}
						{l s='Add Unit' mod='productpricebysize'}
					{/if}
				</button>
			</div>

		</div>

	</div>
</div>