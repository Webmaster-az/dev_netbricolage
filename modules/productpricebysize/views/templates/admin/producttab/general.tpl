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

<div id="form-ppbs-edit" class="form-wrapper ppbs-form-wrapper" style="padding: 20px;">

	<input type="hidden" name="id_product" value="{$id_product|escape:'htmlall':'UTF-8'}">

	<div class="alert alert-danger mp-errors" style="display: none"></div>

	<div class="form-group row">
		<label class="control-label col-lg-2">
			{l s='Enabled for this product' mod='productpricebysize'}
		</label>
		<div class="col-lg-10">
			<input data-toggle="switch" class="" id="enabled" name="enabled" data-inverse="true" type="checkbox" value="1" {if $ppbs_product->enabled eq "1"}checked{/if} />
		</div>
	</div>

	<div class="form-group row">
		<label class="control-label col-lg-2">
			{l s='Use attribute price in area calculation?' mod='productpricebysize'}
		</label>
		<div class="col-lg-10">
			<input data-toggle="switch" class="" id="attribute_price_as_area_price" name="attribute_price_as_area_price" data-inverse="true" type="checkbox" value="1" {if $ppbs_product->attribute_price_as_area_price eq "1"}checked{/if} />
		</div>
	</div>

	<div class="form-group row">
		<label class="control-label col-lg-2">
			{l s='Charge Minimum price (Excl. Tax):' mod='productpricebysize'}
		</label>
		<div class="col-lg-10">
			<input class="form-control" id="min_price" name="min_price" type="textbox" value="{$ppbs_product->min_price|escape:'htmlall':'UTF-8'}" />&nbsp;
			{l s='or enforce minimum area:' mod='productpricebysize'}
			<input class="form-control" id="min_total_area" name="min_total_area" type="textbox" value="{$ppbs_product->min_total_area|escape:'htmlall':'UTF-8'}"/>
		</div>
	</div>

	<div class="form-group row">
		<label class="control-label col-lg-2">
			{l s='Apply set up fee' mod='productpricebysize'}
		</label>
		<div class="col-lg-10">
			<input class="form-control" id="setup_fee" name="setup_fee" type="textbox" value="{$ppbs_product->setup_fee|escape:'htmlall':'UTF-8'}" />
		</div>
	</div>

	<div class="form-group row">
		<label class="control-label col-lg-2">
			{l s='Main unit for price (price per x)' mod='productpricebysize'}
		</label>
		<div class="col-lg-10">
			<select id="id_ppbs_unit_default" name="id_ppbs_unit_default" class="form-control">
				{foreach from=$units item=unit}
					{if $unit.id_ppbs_unit eq $ppbs_product->id_ppbs_unit_default}
						<option value="{$unit.id_ppbs_unit|escape:'htmlall':'UTF-8'}" selected>{$unit.name|escape:'htmlall':'UTF-8'}</option>
					{else}
						<option value="{$unit.id_ppbs_unit|escape:'htmlall':'UTF-8'}">{$unit.name|escape:'htmlall':'UTF-8'}</option>
					{/if}
				{/foreach}
			</select>
		</div>
	</div>

    <h3>{l s='Unit Conversions' mod='productpricebysize'}</h3>
    <p>
        {l s='These settings allow the customer to enter their measurements in different units of measurement' mod='productpricebysize'}
    </p>

    <div class="form-group row">
        <label class="control-label col-lg-2" style="font-weight: bold">
            {l s='Convert Units entered by customer?' mod='productpricebysize'}
        </label>
        <div class="col-lg-10">
            <input data-toggle="switch" class="form-control" id="front_conversion_enabled"
                   name="front_conversion_enabled" data-inverse="true" type="checkbox" value="1"
                   {if $ppbs_product->front_conversion_enabled eq "1"}checked{/if} />
        </div>
    </div>

    <div class="form-group row">
        <label class="control-label col-lg-2">
            {l s='Conversion formula?' mod='productpricebysize'}
        </label>
        <div class="col-lg-10 conversion-fields">
            <select class="form-control" name="front_conversion_operator">
                <option value="/"
                        {if $ppbs_product->front_conversion_operator eq '/'}selected="selected"{/if}>{l s='Divide by' mod='productpricebysize'}</option>
                <option value="*"
                        {if $ppbs_product->front_conversion_operator eq '*'}selected="selected"{/if}>{l s='Multiply by' mod='productpricebysize'}</option>
            </select>
            <input class="form-control" id="front_conversion_value" name="front_conversion_value" type="textbox"
                   value="{$ppbs_product->front_conversion_value|escape:'htmlall':'UTF-8'}"/>
        </div>
    </div>

    <div class="unit-conversion-wrapper">
        <p style="font-weight: bold;">
            {l s='or allow the customer to switch between the units below' mod='productpricebysize'}
        </p>

        <div id="ppbs-areaprice-list">
            <table id="unit-conversion-list" class="table">
                <thead>
                    <tr>
                        <th style="width: 50px"><span class="title_box"></span></th>
                        <th><span class="title_box">{l s='Unit' mod='productpricebysize'}</span></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$units item=unit}
                    <tr>
                        <td>
                            <input type="checkbox"
                                   name="unit_conversions[]"
                                   class="unit_conversions"
                                   value="{$unit.id_ppbs_unit|escape:'htmlall':'UTF-8'}"
                                   {if $unit.checked eq 1}checked{/if}
                            >
                        </td>
                        <td>{$unit.name|escape:'htmlall':'UTF-8'}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>

<button type="button" id="ppbs-btn-general-save" class="btn btn-primary">{l s='Save' mod='productpricebysize'}</button>

<script>
	$(document).ready(function () {
		prestaShopUiKit.init();
	});
</script>