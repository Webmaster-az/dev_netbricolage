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

<div class="equation-editor">
	<input type="hidden" id="equation" name="equation" value=""/>
	<input type="hidden" id="id_product" name="id_product" value=""/>
	<input type="hidden" id="id_product_attribute" name="id_product_attribute" value=""/>

	<div class="equation-display"></div>

	<div class="equation-display-advanced" style="display: none">
		<textarea id="equation-advanced" name="equation-advanced"></textarea>
	</div>

	<div class="equation-config">
		<label>editor mode:</label>
		<span class="active normal" data-editormode="normal">{l s='normal' mod='productpricebysize'}</span>
		<span class="advanced" data-editormode="advanced">{l s='advanced' mod='productpricebysize'}</span>
	</div>

	<div class="equation-buttons">
		<div class="buttons-row" style="clear: both">
			<span class="button single-digit" data-value="0">0</span>
			<span class="button single-digit" data-value="1">1</span>
			<span class="button single-digit" data-value="2">2</span>
			<span class="button single-digit" data-value="3">3</span>
			<span class="button single-digit" data-value="4">4</span>
			<span class="button single-digit" data-value="5">5</span>
			<span class="button single-digit" data-value="6">6</span>
			<span class="button single-digit" data-value="7">7</span>
			<span class="button single-digit" data-value="8">8</span>
			<span class="button single-digit" data-value="9">9</span>
			<span class="button single-digit" data-value=".">.</span>
		</div>
		<div class="buttons-row" style="clear: both">
			{foreach from=$fields item=field}
				<span class="button text-digit" data-value="[{$field.dimension_name|escape:'htmlall':'UTF-8'}]" data-type="variable">{$field.dimension_name|escape:'htmlall':'UTF-8'}</span>
			{/foreach}

            {if $equation_type eq 'price'}
                <span class="button text-digit" data-value="[product_price]" data-type="variable">product price</span>
                <span class="button text-digit" data-value="[base_price]" data-type="variable">base price</span>
                <span class="button text-digit" data-value="[attribute_price]" data-type="variable">attribute price</span>
                <span class="button text-digit" data-value="[area_price]" data-type="variable">area price</span>
                <span class="button text-digit" data-value="[total_area]" data-type="variable">{l s='total area' mod='productpricebysize'}</span>
                <span class="button text-digit" data-value="[quantity]" data-type="variable">quantity</span>
            {/if}

            {foreach from=$global_variables item=variable}
                <span class="button text-digit" data-value="[{$variable.name}]" data-type="variable">{$variable.name}</span>
            {/foreach}

            {if $equation_type eq 'weight'}
                <span class="button text-digit" data-value="[product_weight]" data-type="variable">{l s='product weight' mod='productpricebysize'}</span>
                <span class="button text-digit" data-value="[total_area]" data-type="variable">{l s='total area' mod='productpricebysize'}</span>
                <span class="button text-digit" data-value="[quantity]" data-type="variable">quantity</span>
            {/if}
		</div>

		<div class="buttons-row" style="clear: both">
			<span class="button single-digit" data-value="(" data-type="parenthesis">(</span>
			<span class="button single-digit" data-value=")" data-type="parenthesis">)</span>
			<span class="button single-digit" data-value="+" data-type="operator">+</span>
			<span class="button single-digit" data-value="-" data-type="operator">-</span>
			<span class="button single-digit" data-value="/" data-type="operator">/</span>
			<span class="button single-digit" data-value="*" data-type="operator">*</span>
		</div>
	</div>

	<div class="equation-menu">
		<div class="ppbs-new-equation" style="display: none;">
			<input type="text" name="equation_name" placeholder="{l s='leave blank or type in a name to save for reuse' mod='productpricebysize'}" autocomplete="off">
			<i class="material-icons cancel">clear</i>
		</div>
		<div class="ppbs-load-equation">
			<select name="ppbs-equation-load">
				<option value="0">{l s='Load Equation...' mod='productpricebysize'}</option>
			</select>
			<i class="material-icons cancel" style="font-size:24px">clear</i>
		</div>
	</div>

	<div class="equation-error" style="display: none;">
		<span class="text"></span>
	</div>

	<div class="ppbs-equation-save">
		<button type="button" id="ppbs-equation-save" class="btn btn-primary" disabled="disabled">{l s='Save' mod='productpricebysize'}</button>
		<button type="button" id="ppbs-equation-saveas" class="btn btn-primary">{l s='Save As..' mod='productpricebysize'}</button>
		<button type="button" id="ppbs-equation-remove" class="btn btn-danger" disabled="disabled">{l s='Remove' mod='productpricebysize'}</button>
	</div>
</div>