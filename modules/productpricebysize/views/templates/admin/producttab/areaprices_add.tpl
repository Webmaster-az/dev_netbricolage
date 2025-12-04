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

<div id="panel1" class="panel ppbs-form-wrapper" style="padding: 20px;">
	<input type="hidden" name="id_area_price" value="{$area_price->id_area_price|intval}">
	<input type="hidden" name="id_product" value="{$id_product|intval}">

	<h3>{l s='Add / Edit Area Based Prices' mod='productpricebysize'}</h3>

	<div class="form-group">
		<label class="control-label col-lg-2">
			<span class="label-tooltip">{l s='From (area)' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<input id="area_low" name="area_low" type="text" value="{$area_price->area_low|escape:'htmlall':'UTF-8'}" maxlength="8" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-2">
			<span class="label-tooltip">{l s='To (area)' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<input id="area_high" name="area_high" type="text" value="{$area_price->area_high|escape:'htmlall':'UTF-8'}" maxlength="8" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-2">
			<span class="label-tooltip">{l s='Impact on price' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<select id="impact" name="impact" class="form-control">
				<option value="~" {if $area_price->impact eq '~'}selected{/if}>{l s='Fixed Area Price' mod='productpricebysize'}</option>
				<option value="=" {if $area_price->impact eq '='}selected{/if}>{l s='Fixed Static Price' mod='productpricebysize'}</option>
				<option value="+" {if $area_price->impact eq '+'}selected{/if}>{l s='Increase by' mod='productpricebysize'}</option>
				<option value="-" {if $area_price->impact eq '-'}selected{/if}>{l s='Decrease by' mod='productpricebysize'}</option>
				<option value="*" {if $area_price->impact eq '*'}selected{/if}>{l s='Multiply by' mod='productpricebysize'}</option>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-2">
			<span class="label-tooltip">{l s='Price' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<input id="price" name="price" type="text" value="{$area_price->price|escape:'htmlall':'UTF-8'}" maxlength="8" class="form-control" />
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-2">
			<span class="label-tooltip">{l s='Weight' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<input id="weight" name="weight" type="text" value="{$area_price->weight|escape:'htmlall':'UTF-8'}" maxlength="8" class="form-control" />
		</div>
	</div>

	<div class="panel-footer">
		<a href="#close" class="btn btn-primary-outline"><i class="process-icon-cancel"></i> {l s='Cancel' mod='productpricebysize'}</a>
		<button type="submit" id="ppbs-areaprice-save" class="btn btn-primary pull-right">
			<i class="process-icon-save"></i> {l s='Save' mod='productpricebysize'}
		</button>
	</div>
</div>