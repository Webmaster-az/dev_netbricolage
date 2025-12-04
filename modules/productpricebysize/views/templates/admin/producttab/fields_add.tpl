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

<input type="hidden" name="id_ppbs_product_field" value="{$field->id_ppbs_product_field|intval}">
<input type="hidden" name="id_product" value="{$id_product|intval}">
<input type="hidden" name="ppbs_product_field_options" id="ppbs_product_field_options" value="">

<div id="panel1" class="panel ppbs-form-wrapper" style="padding: 20px;">
	<h3>{l s='Add / Edit Field' mod='productpricebysize'}</h3>

	<div class="form-group">
		<label class="control-label col-lg-4">
			<span class="label-tooltip" title="Dimension">{l s='Dimension' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<select id="id_ppbs_dimension" name="id_ppbs_dimension" class="form-control">
				{foreach from=$dimensions item=dimension}
					<option value="{$dimension.id_ppbs_dimension|escape:'htmlall':'UTF-8'}" {if $field->id_ppbs_dimension eq $dimension.id_ppbs_dimension}selected{/if}>{$dimension.name|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-4">
			<span class="label-tooltip" title="Unit">{l s='Unit' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<select id="id_ppbs_unit" name="id_ppbs_unit" class="form-control">
				{foreach from=$units item=unit}
					<option value="{$unit.id_ppbs_unit|intval}" {if $unit.id_ppbs_unit eq $field->id_ppbs_unit}selected{/if}>{$unit.name|escape:'htmlall':'UTF-8'}</option>
				{/foreach}
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-12">
			<span class="label-tooltip">
                {l s='Display Unit Suffix?' mod='productpricebysize'}
                <span class="help-box" data-toggle="popover"
                    data-content="{l s='Display the unit suffix for this field  in the store front, in product pages, cart etc?' mod='productpricebysize'}"
                    data-original-title="" title="" aria-describedby="popover467744">
                </span>
            </span>
		</label>
		<div class="col-lg-10">
            <input data-toggle="switch" class="" id="display_suffix" name="display_suffix" data-inverse="true" type="checkbox" value="1" {if $field->display_suffix eq "1"}checked{/if} />
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-4">
			<span class="label-tooltip">{l s='Input Type' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<select id="input_type" name="input_type" class="form-control">
				<option value="textbox" {if $field->input_type eq 'textbox'}selected{/if}>Text Box</option>
				<option value="dropdown" {if $field->input_type eq 'dropdown'}selected{/if}>Drop down</option>
			</select>
			<div data-type="dropdown">
				<a id="edit-field-options" href="#edit-field_options" class="btn btn-primary-outline">
					<span>{l s='Edit Dropdown Values' mod='productpricebysize'}</span>
				</a>
			</div>
		</div>
	</div>


	<div class="form-group" data-type="textbox">
		<label class="control-label col-lg-4">
			<span class="label-tooltip" title="Minimum value customer must enter">{l s='Min' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<input id="min" name="min" type="text" value="{$field->min|escape:'htmlall':'UTF-8'}" maxlength="8" class="form-control" />
		</div>
	</div>

	<div class="form-group" data-type="textbox">
		<label class="control-label col-lg-4">
			<span class="label-tooltip" title="Maximum value customer must enter">{l s='Max' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<input id="max" name="max" type="text" value="{$field->max|escape:'htmlall':'UTF-8'}" maxlength="8" class="form-control" />
		</div>
	</div>

	<div class="form-group" data-type="textbox">
		<label class="control-label col-lg-4">
			<span class="label-tooltip">{l s='Default Value' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<input id="default" name="default" type="text" value="{$field->default|escape:'htmlall':'UTF-8'}" maxlength="8" class="form-control" />
		</div>
	</div>

	<div class="form-group" data-type="textbox">
		<label class="control-label col-lg-4">
			<span class="label-tooltip">{l s='Ratio' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<input id="ratio" name="ratio" type="text" value="{$field->ratio|escape:'htmlall':'UTF-8'}" maxlength="8" class="form-control"/>
		</div>
	</div>

	<div class="form-group" data-type="textbox">
		<label class="control-label col-lg-4">
			<span class="label-tooltip">{l s='Decimals' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<input id="decimals" name="decimals" type="text" value="{$field->decimals|escape:'htmlall':'UTF-8'}" maxlength="8" class="form-control"/>
		</div>
	</div>

	<div class="form-group" data-type="textbox">
		<label class="control-label col-lg-4">
			<span class="label-tooltip">{l s='Step' mod='productpricebysize'}</span>
		</label>
		<div class="col-lg-10">
			<input id="step" name="step" type="text" value="{$field->step|escape:'htmlall':'UTF-8'}" maxlength="8" class="form-control"/>
		</div>
	</div>

	<div class="panel-footer">
		<a href="#close" class="btn btn-primary-outline"><i class="process-icon-cancel"></i> {l s='Cancel' mod='productpricebysize'}</a>
		<button type="submit" id="ppbs-field-save" class="btn btn-primary pull-right">
			<i class="process-icon-save"></i> {l s='Save' mod='productpricebysize'}
		</button>
	</div>
</div>

{* Start : Panel2 Dropdown Values *}
<div id="panel2" class="panel subpanel ppbs-form-wrapper" style="padding: 20px; background-color:#fff">
	<h2>Dropdown values</h2>

	<div class="row">
		<div class="col-sm-12"><h4>Add dropdown value</h4></div>
	</div>

	<div class="form-group row">
		<div class="col-lg-4">
			<label class="control-label">
				<span class="label-tooltip">{l s='Numeric Value' mod='productpricebysize'}</span>
			</label>
			<input id="value" name="value" type="number" value="{$field->default|escape:'htmlall':'UTF-8'}" maxlength="8" class="form-control" />
		</div>

		<div class="col-lg-4">
			<label class="control-label">
				<span class="label-tooltip">{l s='Display Value' mod='productpricebysize'}</span>
			</label>
			<input id="text" name="text" type="text" value="{$field->default|escape:'htmlall':'UTF-8'}" maxlength="100" class="form-control" />
		</div>
		<div class="col-lg-4">
			<a href="#ppbs-add-field-option" id="ppbs-field-option-add" class="btn btn-tertiary-outline" style="margin-top:25px;">{l s='Add' mod='productpricebysize'}</a>
		</div>
	</div>



	<div class="" style="overflow-y: auto; height:250px;">

		<table id="ppbs-field-options-table" class="ui-sortable table">
			<thead>
			<tr class="nodrag nodrop">
				<th><span class="title_box">Numeric Value </span></th>
				<th><span class="title_box">Display Value </span></th>
				<th><span class="title_box">Position</span></th>
				<th><span class="title_box">Delete</span></th>
			</tr>
			</thead>

			<tbody>

				{foreach from=$field_options item=field_option}
					<tr>
						<td class="value">{$field_option.value|escape:'htmlall':'UTF-8'}</td>
						<td class="text">{$field_option.text|escape:'htmlall':'UTF-8'}</td>
						<td>
							<i class="material-icons" style="cursor: pointer">swap_vert</i>
						</td>
						<td>
							<a href="#delete" class="ppbs-field-option-delete"><i class="material-icons" style="cursor: pointer">delete forever</i></a>
						</td>
					</tr>
				{/foreach}

				<tr class="cloneable hidden">
					<td class="value"></td>
					<td class="text"></td>
					<td>
						<i class="material-icons" style="cursor: pointer">swap_vert</i>
					</td>
					<td>
						<a href="#delete" class="ppbs-field-option-delete"><i class="material-icons" style="cursor: pointer; display: inline">delete forever</i></a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="panel-footer">
		<a href="#close" class="btn btn-primary-outline">{l s='Cancel' mod='productpricebysize'}</a>
		<button type="submit" id="ppbs-field-dropdown-done" class="btn btn-primary-outline pull-right">
			{l s='Done' mod='productpricebysize'}
		</button>
	</div>

</div>
{* End: Panel2 Dropdown Values *}

<script>
    //$('[data-toggle="popover"]').popover();
    prestaShopUiKit.init();
</script>