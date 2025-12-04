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
	<div id="ppbs-dimensions-list" class="col-sm-6">
		<h4>{l s='Dimensions' mod='productpricebysize'}</h4>
		<table class="table">
			<thead>
			<tr>
				<th>{l s='Internal Name' mod='productpricebysize'}</th>
				<th>{l s='Display Name' mod='productpricebysize'}</th>
				<th>{l s='Action' mod='productpricebysize'}</th>
			</tr>
			</thead>
			<tbody>
				{foreach from=$dimensions item=dimension}
				<tr data-id="{$dimension.id_ppbs_dimension|escape:'html':'UTF-8'}">
					<td>{$dimension.name|escape:'html':'UTF-8'}</td>
					<td>{$dimension.display_name|escape:'html':'UTF-8'}</td>
					<td>
						<i class="ppbs-dimension-edit material-icons" style="cursor: pointer;">edit</i>
						<i class="ppbs-dimension-delete material-icons" style="cursor: pointer;">delete forever</i>
					</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
	</div>

	<div id="ppbs-dimensions-add" class="col-sm-6">

		<div id="form-ppbs-dimension-add" class="form-wrapper ppbs-form-wrapper" style="padding-left: 15px;">

			<h4>{l s='Add New Dimension' mod='productpricebysize'}</h4>
			<div class="alert alert-danger mp-errors" style="display: none"></div>

			<input name="id_ppbs_dimension" value="{if !empty($edit_dimension->id_ppbs_dimension)}{$edit_dimension->id_ppbs_dimension|escape:'html':'UTF-8'}{/if}" type="hidden">

			<div class="form-group row">
				<div class="col-sm-12">
					<label>{l s='Internal Name' mod='productpricebysize'}</label>
					<input id="name" type="text" name="name" class="form-control search" placeholder="{l s='Internal name of dimensions e.g - width' mod='productpricebysize'}" autocomplete="off"
						   value="{if !empty($edit_dimension->name)}{$edit_dimension->name|escape:'html':'UTF-8'}{/if}" />
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-12">
					<label>{l s='Display Name' mod='productpricebysize'}</label>
					{foreach from=$languages item=language}
					<div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" style="{if $language.id_lang eq $id_lang_default}display: block;{else}display:none;{/if}">
						<div class="col-lg-7">
							<input name="display_name_{$language.id_lang|escape:'htmlall':'UTF-8'}" id="display_name_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="form-control"
								   value="{if !empty($edit_dimension->display_name[$language.id_lang])}{$edit_dimension->display_name[$language.id_lang]|escape:'html':'UTF-8'}{/if}" />
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

            <div class="form-group row dimension-images">
                <div class="col-sm-12">
                    <label class="title">{l s='Hint Image' mod='productpricebysize'}</label>
                    <p>
                        {l s='Display an image when the customer hovers over the dimension on the product page or presses it on mobile' mod='productpricebysize'}<br>
                        <br>
                    </p>
                    {foreach from=$languages item=language}
                        <div class="dimension-image-wrapper">
                            <div><strong>{$language.name|escape:'htmlall':'UTF-8'}</strong></div>
                            <div class="dimension-image">
                                <input id="image_{$language.id_lang|escape:'htmlall':'UTF-8'}" class="image" name="image_{$language.id_lang|escape:'htmlall':'UTF-8'}" type="hidden" value="{$language.image|escape:'htmlall':'UTF-8'}">
                                {if $language.image neq ''}
                                    <div class="dimension-image-thumbnail">
                                        <img id="image_preview_{$language.id_lang|escape:'htmlall':'UTF-8'}" src="{$language.image|escape:'htmlall':'UTF-8'}" width="100">
                                        <a href="#" id="btn-image-remove-{$language.id_lang|escape:'htmlall':'UTF-8'}" class="btn-image-remove" data-id_lang="{$language.id_lang|escape:'htmlall':'UTF-8'}">
                                            <i class="material-icons">delete forever</i>
                                        </a>
                                    </div>
                                {else}
                                    <div class="dimension-image-thumbnail">
                                        <img id="image_preview_{$language.id_lang|escape:'htmlall':'UTF-8'}" src="" width="100">
                                        <a href="#" id="btn-image-remove-{$language.id_lang|escape:'htmlall':'UTF-8'}" class="btn-image-remove" data-id_lang="{$language.id_lang|escape:'htmlall':'UTF-8'}" style="display: none">
                                            <i class="material-icons">delete forever</i>
                                        </a>
                                    </div>
                                {/if}
                                <button class="btn-image-select"
                                        data-id_lang="{$language.id_lang|escape:'htmlall':'UTF-8'}"
                                        data-for="image_{$language.id_lang|escape:'htmlall':'UTF-8'}">
                                    {l s='Choose / Upload Image' mod='productpricebysize'}
                                </button>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>


            <div>
				<button type="submit" id="ppbs-dimension-save" class="btn btn-primary">
					{if !empty($edit_dimension->id_ppbs_dimension)}
						{l s='Update Dimension' mod='productpricebysize'}
					{else}
						{l s='Add Dimension' mod='productpricebysize'}
					{/if}
				</button>
			</div>

		</div>

	</div>
</div>