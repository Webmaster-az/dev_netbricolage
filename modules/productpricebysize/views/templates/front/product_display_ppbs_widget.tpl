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

<div id="ppbs_widget" class="card card-block">

    {if !empty($conversion_options)}
        <div class="conversion-units">
            {foreach from=$conversion_options item=conversion_option}
                <span
                    data-id_ppbs_product_unit_conversion="{$conversion_option.id_ppbs_product_unit_conversion|escape:'htmlall':'UTF-8'}"
                    data-id_ppbs_unit="{$conversion_option.id_ppbs_unit|escape:'htmlall':'UTF-8'}"
                    data-conversion_factor="{$conversion_option.conversion_factor|escape:'htmlall':'UTF-8'}"
                    {if $conversion_option.default eq 1}class="convert-unit selected" data-default="true"{/if}
                    class="convert-unit">{$conversion_option.symbol|escape:'htmlall':'UTF-8'}</span>
            {/foreach}
        </div>
    {/if}

    <div class="ppbs-widget-container">
        <div class="ppbs_widget_fields">
            {foreach from=$product_fields item=field}
                <div class="unit-entry">
                    <div class="field-label-wrapper">
                        <label>{l s='enter your' mod='productpricebysize'} <strong>{$field.display_name|escape:'htmlall':'UTF-8'}</strong></label>
                        {if $field.image neq ''}
                            <div class="field-image-wrapper">
                                <span>i</span>
                                <img src="{$field.image|escape:'htmlall':'UTF-8'}" style="max-width: 200px;">
                            </div>
                        {/if}
                    </div>

                    <div class="field-input-wrapper">
                        {if $field.input_type eq 'dropdown'}
                            <input name="ppbs_field-{$field.id_ppbs_product_field|intval}" data-id_ppbs_product_field="{$field.id_ppbs_product_field|intval}" style="width:50px; display: none;" class="unit dd_unit_hidden" autocomplete="off" value="{if $field.default gt 0}{$field.default|escape:'htmlall':'UTF-8'}{/if}"/>
                            <select name="ppbs_field-{$field.id_ppbs_product_field|intval}_dd" class="dd_options" data-dimension_name="{$field.dimension_name|escape:'htmlall':'UTF-8'}" data-for="ppbs_field-{$field.id_ppbs_product_field|intval}">
                                <option value=""></option>
                                {foreach from=$field.options item=option}
                                    <option value="{$option.value|escape:'htmlall':'UTF-8'}:{$option.text|escape:'htmlall':'UTF-8'}" {if $field.default eq $option.value}selected{/if}>{$option.text|escape:'htmlall':'UTF-8'}</option>
                                {/foreach}
                            </select>
							{if $field['display_suffix'] eq 1}
                            	<span class="suffix">{$field['unit']->symbol|escape:'htmlall':'UTF-8'}</span>
							{/if}
                        {else}
                            <input type="hidden" name="ppbs_field-{$field.id_ppbs_product_field|intval}-default-unit" />
                            <input name="ppbs_field-{$field.id_ppbs_product_field|intval}"
                                   data-base="{$field.default|escape:'htmlall':'UTF-8'}"
                                   data-default="0"
                                   data-id_ppbs_product_field="{$field.id_ppbs_product_field|intval}"
                                   type="text"
                                   data-step="{$field.step|floatval}"
                                   data-dimension_name="{$field.dimension_name|escape:'htmlall':'UTF-8'}"
                                   class="unit"
                                   autocomplete="off"
                                   value="{$field.default|escape:'htmlall':'UTF-8'}"/>
                            {if $field['display_suffix'] eq 1}
                                <span class="suffix">{$field['unit']->symbol|escape:'htmlall':'UTF-8'}</span>
                            {/if}
                        {/if}

                        <div class="error-unit" data-min-default-unit="{$field.min|escape:'htmlall':'UTF-8'}" data-max-default-unit="{$field.max|escape:'htmlall':'UTF-8'}" style="display: none;">
                            {$field.error|escape:'htmlall':'UTF-8'}
                        </div>
                    </div>
                </div>
            {/foreach}
            <div class="ppbs_error" style="display:none;">
                <i class="icon icon-warning"></i>
                {$translations.generic_error|escape:'htmlall':'UTF-8'}
            </div>
        </div>

        <div class="ppbs-price-wrapper">
            <div id="ppbs-price"></div>

            {if $ppbs_product->id_ppbs_unit_default gt 0}
                <div id="ppbs-area-price"></div>
            {/if}

            {if $ppbs_options.display_total_area eq '1'}
                <div id="ppbs-total-area">
                    {l s='Total Area : ' mod='productpricebysize'} <span class="ppbs-total-area-value"></span>
                </div>
            {/if}
        </div>
    </div>
    {*
    <div id="ppbs-widget-stock-warning" class="col-xs-12" style="text-align: center; display: none;">
        <span id="product-availability">
            <i class="material-icons product-unavailable"></i>
                {l s='Not enough available in stock' mod='productpricebysize'}
        </span>
    </div>
    *}
</div>

<script>
    ppbs = {
        id_currency: "{$id_currency|escape:'html':'UTF-8'}"
    };
    ppbs_enabled = {$pbbs_enabled|escape:'quotes':'UTF-8' nofilter};

    {if ($action eq 'quickview')}
        $(document).ready(function () {
            module_ajax_url_ppbs = "{$module_ajax_url|escape:'quotes':'UTF-8' nofilter}";
            ppbs_front_product_controller = new PPBSFrontProductController('#ppbs_widget', '', true);
        });
    {else}
        document.addEventListener("DOMContentLoaded", function (event) {
            $(function () {
                module_ajax_url_ppbs = "{$module_ajax_url|escape:'quotes':'UTF-8' nofilter}";
                ppbs_front_product_controller = new PPBSFrontProductController('#ppbs_widget', '', false);
            });
        });
    {/if}

    id_shop = '{$id_shop|escape:'htmlall':'UTF-8'}';
	ppbs_translations = {
		unit_price_suffix : "{$translations.unit_price_suffix|escape:'htmlall':'UTF-8'}",
		area_price_suffix : "{$translations.area_price_suffix|escape:'htmlall':'UTF-8'}"
	};
	ppbs_options = {$ppbs_options_json nofilter};

	ppbs_product_json = {$ppbs_product_json nofilter};
	ppbs_product_fields = {$product_fields_json nofilter};
	ppbs_equations_collection = {$ppbs_equations_collection_json nofilter};
    ppbs_global_variables = {$ppbs_global_variables nofilter};

	ppbs_price_adjustments_json = {$ppbs_price_adjustments_json nofilter}; // Area based prices
	ppbs_field_ratios_json = {$ppbs_field_ratios_json nofilter};
</script>