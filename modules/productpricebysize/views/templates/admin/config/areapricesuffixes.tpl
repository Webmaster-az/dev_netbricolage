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
	<div id="ppbs-areapricesuffix-list" class="col-sm-6">
		<h4>{l s='Area Price Suffixes' mod='productpricebysize'}</h4>
		<table class="table">
			<thead>
			<tr>
				<th>{l s='Name' mod='productpricebysize'}</th>
				<th>{l s='Action' mod='productpricebysize'}</th>
			</tr>
			</thead>
			<tbody>
				{foreach from=$collection item=item}
				<tr data-id="{$item->id_ppbs_areapricesuffix|escape:'html':'UTF-8'}">
					<td>{$item->name|escape:'html':'UTF-8'}</td>
					<td>
						<i class="ppbs-areapricesuffix-edit material-icons" style="cursor: pointer;">edit</i>
						<i class="ppbs-areapricesuffix-delete material-icons" style="cursor: pointer;">delete forever</i>
					</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
	</div>

	<div id="ppbs-areapricesuffix-add" class="col-sm-6">

		<div id="form-areapricesuffix-add" class="form-wrapper ppbs-form-wrapper" style="padding-left: 15px;">

			<h4>{l s='Add New Area Price Suffix' mod='productpricebysize'}</h4>
			<div class="alert alert-danger mp-errors" style="display: none"></div>

			<input name="id_ppbs_areapricesuffix" value="{if !empty($edit_object->id_ppbs_areapricesuffix)}{$edit_object->id_ppbs_areapricesuffix|escape:'html':'UTF-8'}{/if}" type="hidden">

			<div class="form-group row">
				<div class="col-sm-12">
					<label>{l s='Name' mod='productpricebysize'}</label>
					<input id="name" type="text" name="name" class="form-control search" placeholder="{l s='name of unit e.g - cm' mod='productpricebysize'}" autocomplete="off"
						   value="{if !empty($edit_object->name)}{$edit_object->name|escape:'html':'UTF-8'}{/if}" />
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-12">
					<label>{l s='Area Price Suffix' mod='productpricebysize'}</label>
					{foreach from=$languages item=language}
					<div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" style="{if $language.id_lang eq $id_lang_default}display: block;{else}display:none;{/if}">
						<div class="col-lg-7">
							<input name="areapricesuffix_text_{$language.id_lang|escape:'htmlall':'UTF-8'}" id="areapricesuffix_text_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="form-control"
								   value="{if !empty($edit_object->text[$language.id_lang])}{$edit_object->text[$language.id_lang]|escape:'html':'UTF-8'}{/if}" />
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
				<button type="submit" id="ppbs-areapricesuffix-save" class="btn btn-primary">
					{if !empty($edit_object->id_ppbs_areapricesuffix)}
						{l s='Update Area Price Syntax' mod='productpricebysize'}
					{else}
						{l s='Add Area Price Syntax' mod='productpricebysize'}
					{/if}
				</button>
			</div>

		</div>

	</div>
</div>