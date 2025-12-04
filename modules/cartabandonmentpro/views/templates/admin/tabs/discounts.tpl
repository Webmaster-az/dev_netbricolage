{*
* 2007-2017 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script>
    var tpl_selected = {$template_disc|intval};
    {foreach from=$errors key=k item=v}
        var {$k} = "{$v}";
    {/foreach}
    var currency = "{$currency|escape:'htmlall':'UTF-8'}";
</script>
<h2>{l s='DISCOUNTS VALUE AND CONDITION' mod='cartabandonmentpro'}</h2>
{if $second_reminder_active|intval eq 1 || $third_reminder_active|intval eq 1}
    <div class="row">
        <form action="#" method="POST" id="template_chose">
            <div class="row">
                <div class="form-group">
                    <label class="col-md-2 control-label" for="discounts_template">
                        {l s='Setup discount for' mod='cartabandonmentpro'}
                    </label>
                    <div class="col-md-10">
                        <div class="btn-group" role="group">
                            {if $first_reminder_active|intval eq 1}<button type="button" data-val="1" class="choose-discount btn btn-default {if ($template_disc|intval eq 1) || ($template_disc|intval neq 1 && $template_disc|intval neq 2 && $template_disc|intval neq 3)}btn-primary{/if}">{l s='Reminder' mod='cartabandonmentpro'} 1</button>{/if}
                            {if $second_reminder_active|intval eq 1}<button type="button" data-val="2" class="choose-discount btn btn-default {if $template_disc|intval eq 2}btn-primary{/if}">{l s='Reminder' mod='cartabandonmentpro'} 2</button>{/if}
                            {if $third_reminder_active|intval eq 1}<button type="button" data-val="3" class="choose-discount btn btn-default {if $template_disc|intval eq 3}btn-primary{/if}">{l s='Reminder' mod='cartabandonmentpro'} 3</button>{/if}
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="discounts_template" id="discounts_template" value="{if isset($template_disc)}{$template_disc|intval}{else}1{/if}">
            <input type="hidden" name="discounts_chose_template" value="{if isset($template_disc)}{$template_disc|intval}{else}1{/if}">
        </form>
    </div>
    <br>
    <hr>
    <br>
{/if}
<div class="row">
    <form name="discounts_form" id="discounts_form" action="#" method="POST" onSubmit="return checkDiscountForm();">
        <input type="hidden" name="discounts_template" value="{$template_disc|intval}">
        <div class="row">
            <div class="form-group">
                <label class="col-md-1 control-label" for="discounts_active">
                    <b><u>{l s='Reminder' mod='cartabandonmentpro'} {$template_disc|intval}</u></b>
                </label>
                <span style="float:left;" class="switch prestashop-switch input-group col-md-2">
                    <input type="radio" name="discounts_active" id="discounts_active_on" {if isset($discountsActive) and $discountsActive|intval eq 1}checked="checked"{/if}value="1"/>
                    <label for="discounts_active_on" class="radioCheck" onClick="discountsActive(1);">
                        <i class="color_success"></i> {l s='Active' mod='cartabandonmentpro'}
                    </label>
                    <input type="radio" name="discounts_active" id="discounts_active_off" value="0" {if !isset($discountsActive) or $discountsActive|intval eq 0}checked="checked"{/if} />
                    <label for="discounts_active_off" class="radioCheck" onClick="discountsActive(0);">
                        <i class="color_danger"></i> {l s='Inactive' mod='cartabandonmentpro'}
                    </label>
                    <a class="slide-button btn"></a>
                </span>
                <input type="hidden" id="discounts_active_val" name="discounts_active_val" value="{if isset($discountsActive)}{$discountsActive|intval}{else}0{/if}" />
            </div>
        </div>
        <div id="discounts_configure" {if !isset($discountsActive) or $discountsActive|intval eq 0}style="display:none;"{/if}>
            <br><br>
            <div class="row">
                <div class="form-group">
                    <label class="col-md-4 control-label" for="discounts_different_val">
                        {l s='Does this discount apply to specific ranges of shopping cart value?' mod='cartabandonmentpro'}
                    </label>
                    <span style="float:left;" class="switch prestashop-switch input-group col-md-2">
                        <input type="radio" name="discounts_different_val" id="discounts_different_val_on" {if isset($discountsDif) and $discountsDif|intval eq 1}checked="checked"{/if}value="1"/>
                        <label for="discounts_different_val_on" class="radioCheck" onClick="discountsDiffVal(1);">
                            <i class="color_success"></i> {l s='Yes' mod='cartabandonmentpro'}
                        </label>
                        <input type="radio" name="discounts_different_val" id="discounts_different_val_off" value="0" {if !isset($discountsDif) or $discountsDif|intval eq 0}checked="checked"{/if} />
                        <label for="discounts_different_val_off" class="radioCheck" onClick="discountsDiffVal(0);">
                            <i class="color_danger"></i> {l s='No' mod='cartabandonmentpro'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
                    <input type="hidden" id="discounts_different_val2" name="discounts_different_val2" value="{if isset($discountsDif)}{$discountsDif|intval}{else}0{/if}" />
                </div>
            </div>
            <br>
            <div class="row">
                <div class="form-group">
                    <label class="col-md-4 control-label" for="discounts_different_val">
                        {l s='Does this discount is with taxes? (Only if discount\'s type is amount)' mod='cartabandonmentpro'}
                    </label>
                    <span style="float:left;" class="switch prestashop-switch input-group col-md-2">
                        <input type="radio" name="discount_taxes" id="discount_taxes_on" {if isset($discount_with_taxes) and $discount_with_taxes}checked="checked"{/if}value="1"/>
                        <label for="discount_taxes_on" class="radioCheck">
                            <i class="color_success"></i> {l s='Yes' mod='cartabandonmentpro'}
                        </label>
                        <input type="radio" name="discount_taxes" id="discount_taxes_off" value="0" {if !isset($discount_with_taxes) or !$discount_with_taxes}checked="checked"{/if} />
                        <label for="discount_taxes_off" class="radioCheck">
                            <i class="color_danger"></i> {l s='No' mod='cartabandonmentpro'}
                        </label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
            </div>
            <br>
            <!-- SAME DISCOUNTS -->
            <div id="same_discounts" {if isset($discountsDif) and $discountsDif|intval eq 1}style="display: none;"{/if}>
                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="discounts_type">
                            {l s='Type' mod='cartabandonmentpro'}
                        </label>
                        <button type="button" id="discounts_type_percent" value="percent" name="discounts_type" class="diff_type discounts_type col-sm-1 btn {if $discounts2.0.type|escape:'htmlall':'UTF-8' eq 'percent'}btn-primary{else}btn-default{/if} btn-left" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;min-width: 200px;">
                            {l s='Percent' mod='cartabandonmentpro'}
                        </button>
                        <button type="button" id="discounts_type_currency" value="currency" name="discounts_type" class="discounts_type col-sm-1 btn {if $discounts2.0.type|escape:'htmlall':'UTF-8' eq 'currency'}btn-primary{else}btn-default{/if} btn-mid" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;min-width: 200px;">
                            {l s='Currency' mod='cartabandonmentpro'}
                        </button>
                        <button type="button" id="discounts_type_shipping" value="shipping" name="discounts_type" class="discounts_type col-sm-1 btn {if $discounts2.0.type|escape:'htmlall':'UTF-8' eq 'shipping'}btn-primary{else}btn-default{/if} btn-right" style="margin-left: 0;paddin-left: 5px;paddin-right: 5px;min-width: 200px;">
                            {l s='Free shipping' mod='cartabandonmentpro'}
                        </button>
                        <input type="hidden" id="discounts_type" name="discounts_type" value="{if $discounts2.0.type|escape:'htmlall':'UTF-8'}{$discounts2.0.type|escape:'htmlall':'UTF-8'}{/if}">
                    </div>
                </div>
                <br>
                <div class="row" id="same_value" {if $discounts2.0.type|escape:'htmlall':'UTF-8' eq 'shipping'}style="display:none;"{/if}>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="discounts_value">
                            {l s='Value' mod='cartabandonmentpro'}
                        </label>
                        <div class="input-group col-md-3">
                          <input type="number" name="discounts_value" id="discounts_value" value="{if isset($discounts2.0.value)}{$discounts2.0.value|escape:'htmlall':'UTF-8'}{/if}" class="form-control col-md-2">
                          <span id="value_operator" class="input-group-addon">{if $discounts2.0.type|escape:'htmlall':'UTF-8' eq 'percent'}%{else}{$currency|escape:'htmlall':'UTF-8'}{/if}</span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="discounts_validity">
                            {l s='Discount validity' mod='cartabandonmentpro'}
                        </label>
                        <div class="input-group col-md-3">
                            <input type="number" name="discounts_validity_days" id="discounts_validity_days" value="{if isset($discounts2.0.valid_value)}{$discounts2.0.valid_value|escape:'htmlall':'UTF-8'}{/if}" class="form-control col-md-2">
                            <span class="input-group-addon">{l s='Days' mod='cartabandonmentpro'}</span>
                        </div>
                    </div>
                </div>
                <br>
                <input type="hidden" name="discounts_min" id="discounts_min" value="0" class="form-control col-md-2">
                <input type="hidden" name="discounts_max" id="discounts_max" value="2147483647" class="form-control col-md-2">
            </div>

            <!-- DIFFERENT DISCOUNTS -->
            <br><br>
            <div id="different_discounts" {if !isset($discountsDif) or $discountsDif|intval eq 0}style="display:none;"{/if}>
                <div class="row">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="discounts_tranche">
                            {l s='Choose the number of discount ranges' mod='cartabandonmentpro'}
                        </label>
                        <div class="input-group col-md-1">
                            <select name="discounts_tranche" id="discounts_tranche">
                                <option {if $tranches|intval eq 1}selected="selected"{/if}>1</option>
                                <option {if $tranches|intval eq 2}selected="selected"{/if}>2</option>
                                <option {if $tranches|intval eq 3}selected="selected"{/if}>3</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="discount_1" class="tranches">
                    <h4>
                        {l s='Range 1' mod='cartabandonmentpro'}
                    </h4>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="col-md-9 control-label pull-left" for="discounts_max_1">
                                    {l s='Applies to shopping carts higher than' mod='cartabandonmentpro'}
                                </label>
                                <div class="input-group col-md-3 pull-left">
                                  <input type="number" name="discounts_min_1" id="discounts_min_1" value="{if isset($discounts2.0.min_amount)}{$discounts2.0.min_amount|escape:'htmlall':'UTF-8'}{/if}" class="form-control col-md-2">
                                  <span class="input-group-addon">{$currency|escape:'htmlall':'UTF-8'}</span>
                                </div>
                            </div>
                            <input type="hidden" name="discounts_max_1" value="0">
                            <div class="col-md-3 col-md-offset-1">
                                <label class="col-md-4 control-label pull-left" for="discounts_type_1" style="margin-left: 20px;">
                                    {l s='Discount type' mod='cartabandonmentpro'}
                                </label>
                                <select name="discounts_type_1" id="discounts_type_1" class="diff_type pull-left col-md-2" style="width:100px;">
                                    <option value="percent" {if $discounts2.0.type|escape:'htmlall':'UTF-8' eq 'percent'}selected="selected"{/if}>
                                        {l s='Percent' mod='cartabandonmentpro'}
                                    </option>
                                    <option value="currency" {if $discounts2.0.type|escape:'htmlall':'UTF-8' eq 'currency'}selected="selected"{/if}>
                                        {l s='Currency' mod='cartabandonmentpro'}
                                    </option>
                                    <option value="shipping" {if $discounts2.0.type|escape:'htmlall':'UTF-8' eq 'shipping'}selected="selected"{/if}>
                                        {l s='Free shipping' mod='cartabandonmentpro'}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2 col-md-offset-1">
                                <table>
                                    <tr id="value_1" class="value" {if $discounts2.0.type|escape:'htmlall':'UTF-8' eq 'shipping'}style="display:none;"{/if}><td style="padding-bottom: 10px;" width="150px">
                                        <label class="control-label" for="discounts_value_1">
                                            {l s='Discount value' mod='cartabandonmentpro'}
                                        </label>
                                    </td><td style="padding-bottom: 10px;" width="100px">
                                        <div class="input-group">
                                          <input type="number" name="discounts_value_1" id="discounts_value_1" value="{if isset($discounts2.0.value)}{$discounts2.0.value|escape:'htmlall':'UTF-8'}{/if}" class="form-control">
                                          <span id="value_operator_1" class="input-group-addon currency">{if $discounts2.0.type|escape:'htmlall':'UTF-8' eq 'percent'}%{else}{$currency|escape:'htmlall':'UTF-8'}{/if}</span>
                                        </div>
                                    </td></tr>
                                    <tr><td>
                                        <label class="control-label" for="discounts_validity">
                                            {l s='Discount validity' mod='cartabandonmentpro'}
                                        </label>
                                    </td><td>
                                        <div class="input-group">
                                            <input type="number" name="discounts_validity_days_1" id="discounts_validity_days_1" value="{if isset($discounts2.0.valid_value)}{$discounts2.0.valid_value|escape:'htmlall':'UTF-8'}{/if}" class="form-control">
                                            <span class="input-group-addon">{l s='Days' mod='cartabandonmentpro'}</span>
                                        </div>
                                    </td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="discount_2" class="tranches" {if $tranches|intval  < 2}style="display:none;"{/if}>
                    <h4>
                        {l s='Range 2' mod='cartabandonmentpro'}
                    </h4>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="col-md-9 control-label pull-left" for="discounts_max_2">
                                    {l s='Applies to shopping carts higher than' mod='cartabandonmentpro'}
                                </label>
                                <div class="input-group col-md-3 pull-left">
                                  <input type="number" name="discounts_min_2" id="discounts_min_2" value="{if isset($discounts2.1.min_amount)}{$discounts2.1.min_amount|escape:'htmlall':'UTF-8'}{/if}" class="form-control col-md-2">
                                  <span class="input-group-addon">{$currency|escape:'htmlall':'UTF-8'}</span>
                                </div>
                            </div>
                            <input type="hidden" name="discounts_max_2" value="0">
                            <div class="col-md-3 col-md-offset-1">
                                <label class="col-md-4 control-label pull-left" for="discounts_type_2" style="margin-left: 20px;">
                                    {l s='Discount type' mod='cartabandonmentpro'}
                                </label>
                                <select name="discounts_type_2" id="discounts_type_2" class="diff_type pull-left col-md-2" style="width: 100px;">
                                    <option value="percent" {if $discounts2.1.type|escape:'htmlall':'UTF-8' eq 'percent'}selected="selected"{/if}>
                                        {l s='Percent' mod='cartabandonmentpro'}
                                    </option>
                                    <option value="currency" {if $discounts2.1.type|escape:'htmlall':'UTF-8' eq 'currency'}selected="selected"{/if}>
                                        {l s='Currency' mod='cartabandonmentpro'}
                                    </option>
                                    <option value="shipping" {if $discounts2.1.type|escape:'htmlall':'UTF-8' eq 'shipping'}selected="selected"{/if}>
                                        {l s='Free shipping' mod='cartabandonmentpro'}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2 col-md-offset-1">
                                <table>
                                    <tr id="value_2" class="value" {if $discounts2.1.type|escape:'htmlall':'UTF-8' eq 'shipping'}style="display:none;"{/if}><td style="padding-bottom: 10px;" width="150px">
                                        <label class="control-label" for="discounts_value_2">
                                            {l s='Discount value' mod='cartabandonmentpro'}
                                        </label>
                                    </td><td style="padding-bottom: 10px;" width="100px">
                                        <div class="input-group">
                                          <input type="number" name="discounts_value_2" id="discounts_value_2" value="{if isset($discounts2.1.value)}{$discounts2.1.value|escape:'htmlall':'UTF-8'}{/if}" class="form-control">
                                          <span id="value_operator_2" class="input-group-addon currency">{if $discounts2.1.type eq 'percent'}%{else}{$currency|escape:'htmlall':'UTF-8'}{/if}</span>
                                        </div>
                                    </td></tr>
                                    <tr><td>
                                        <label class="control-label" for="discounts_validity">
                                            {l s='Discount validity' mod='cartabandonmentpro'}
                                        </label>
                                    </td><td>
                                        <div class="input-group">
                                            <input type="number" name="discounts_validity_days_2" id="discounts_validity_days_2" value="{if isset($discounts2.1.valid_value)}{$discounts2.1.valid_value|escape:'htmlall':'UTF-8'}{/if}" class="form-control">
                                            <span class="input-group-addon">{l s='Days' mod='cartabandonmentpro'}</span>
                                        </div>
                                    </td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="discount_3" class="tranches" {if $tranches|intval  < 3}style="display:none;"{/if}>
                        <h4>
                            {l s='Range 3' mod='cartabandonmentpro'}
                        </h4>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label class="col-md-9 control-label pull-left" for="discounts_max_3">
                                        {l s='Applies to shopping carts higher than' mod='cartabandonmentpro'}
                                    </label>
                                    <div class="input-group col-md-3 pull-left">
                                      <input type="number" name="discounts_min_3" id="discounts_min_3" value="{if isset($discounts2.2.min_amount)}{$discounts2.2.min_amount|escape:'htmlall':'UTF-8'}{/if}" class="form-control col-md-2">
                                      <span class="input-group-addon">{$currency|escape:'htmlall':'UTF-8'}</span>
                                    </div>
                                </div>
                                <input type="hidden" name="discounts_max_3" value="2147483647">
                                <div class="col-md-3 col-md-offset-1">
                                    <label class="col-md-4 control-label pull-left" for="discounts_type_3" style="margin-left: 20px;">
                                        {l s='Discount type' mod='cartabandonmentpro'}
                                    </label>
                                    <select name="discounts_type_3" id="discounts_type_3" class="diff_type pull-left col-md-2" style="width: 100px;">
                                        <option value="percent" {if $discounts2.2.type|escape:'htmlall':'UTF-8' eq 'percent'}selected="selected"{/if}>
                                            {l s='Percent' mod='cartabandonmentpro'}
                                        </option>
                                        <option value="currency" {if $discounts2.2.type|escape:'htmlall':'UTF-8' eq 'currency'}selected="selected"{/if}>
                                            {l s='Currency' mod='cartabandonmentpro'}
                                        </option>
                                        <option value="shipping" {if $discounts2.2.type|escape:'htmlall':'UTF-8' eq 'shipping'}selected="selected"{/if}>
                                            {l s='Free shipping' mod='cartabandonmentpro'}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-md-offset-1">
                                    <table>
                                        <tr id="value_3" class="value" {if $discounts2.2.type|escape:'htmlall':'UTF-8' eq 'shipping'}style="display:none;"{/if}><td style="padding-bottom: 10px;" width="150px">
                                            <label class="control-label" for="discounts_value_3">
                                                {l s='Discount value' mod='cartabandonmentpro'}
                                            </label>
                                        </td><td style="padding-bottom: 10px;" width="100px">
                                            <div class="input-group">
                                              <input type="number" name="discounts_value_3" id="discounts_value_3" value="{if isset($discounts2.2.value)}{$discounts2.2.value|escape:'htmlall':'UTF-8'}{/if}" class="form-control">
                                              <span id="value_operator_3" class="input-group-addon currency">{if $discounts2.2.type|escape:'htmlall':'UTF-8' eq 'percent'}%{else}{$currency|escape:'htmlall':'UTF-8'}{/if}</span>
                                            </div>
                                        </td></tr>
                                        <tr><td>
                                            <label class="control-label" for="discounts_validity">
                                                {l s='Discount validity' mod='cartabandonmentpro'}
                                            </label>
                                        </td><td>
                                            <div class="input-group">
                                                <input type="number" name="discounts_validity_days_3" id="discounts_validity_days_3" value="{if isset($discounts2.2.valid_value)}{$discounts2.2.valid_value|escape:'htmlall':'UTF-8'}{/if}" class="form-control">
                                                <span class="input-group-addon">{l s='Days' mod='cartabandonmentpro'}</span>
                                            </div>
                                        </td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <br><br>
        <h2>{l s='DISCOUNT TEXT' mod='cartabandonmentpro'}</h2>
        <p>
            {l s='Configure the content of the %DISCOUNT_TXT% tag here' mod='cartabandonmentpro'}
        </p>
        <div class="row">
            <div class="alert alert-info" role="alert">
                {l s='Discount text will be the same for all reminders you configured' mod='cartabandonmentpro'}
            </div>
        </div>
        <div class="row">
            <div class="row">
                {foreach from=$languages item=language}
                    <button type="button" toggle="input_select_{$language.id_lang|escape:'htmlall':'UTF-8'}_container" toggle_lang="{$language.id_lang|intval}" class="lang_toggle_{$language.id_lang|intval} lang_toggle btn {if $language.id_lang|intval neq $lang_default|intval}btn-default{else} btn-primary{/if}">{$language.iso_code}</button>
                {/foreach}
            </div>
            <br>
            <div class="form-group">
                <label class="col-xs-12 control-label" for="discounts_value">
                    {l s='Text for the percent or currency discounts (%DISCOUNT_TXT% content)' mod='cartabandonmentpro'}
                </label>
                <div class="input-group col-xs-12">
                    {foreach from=$languages item=language}
                        <input type="text" name="discount_val_text_{$language.id_lang|intval}" id="discount_val_text_{$language.id_lang|intval}" class="{if $language.id_lang|intval neq $lang_default|intval} hidden{/if} multilang {$language.id_lang|intval}_container form-control col-xs-12" value="{$discount_val_text_{$language.id_lang|intval}}">
                    {/foreach}
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 control-label" for="discounts_value">
                    {l s='Text for free shipping discount (%DISCOUNT_TXT% content)' mod='cartabandonmentpro'}
                </label>
                <div class="input-group col-xs-12">
                    {foreach from=$languages item=language}
                        <input type="text" name="discount_shipping_text_{$language.id_lang|intval}" id="discount_shipping_text_{$language.id_lang|intval}" value="{$discount_shipping_text_{$language.id_lang|intval}}" class="{if $language.id_lang|intval neq $lang_default|intval} hidden{/if} multilang {$language.id_lang|intval}_container form-control col-xs-12">
                    {/foreach}
                </div>
            </div>
        </div>
        <br><br>
        <div class="panel panel-default">
            <div class="cartab_panel-heading">{l s='Use the following tags to write your discount text' mod='cartabandonmentpro'}</div>
            <div class="panel-body">
                <div class="alert alert-warning" role="alert">
                    {l s='These tags will not work if placed directly in your template. Use %DISCOUNT_TXT% in your template body' mod='cartabandonmentpro'}.
                </div>
                <span class="code">%DISCOUNT_VALUE%</span> {l s='Amount of discount. E.g. 20% or 50 €' mod='cartabandonmentpro'}<br>
                <span class="code">%DISCOUNT_VALID_DAY%</span> <span class="code">%DISCOUNT_VALID_MONTH%</span> <span class="code">%DISCOUNT_VALID_YEAR%</span> {l s='Discount expiry date E.g. 4/11/2014' mod='cartabandonmentpro'}<br>
                <span class="code">%DISCOUNT_CODE%</span> {l s='Discount codes. E.g. CAVa70c6' mod='cartabandonmentpro'}
                <br><br>
                <div>
                    <div class="col-xs-12 col-md-5">
                        <div class="example_code">
                            {l s='Enjoy' mod='cartabandonmentpro'} %DISCOUNT_VALUE% {l s='off on your cart value with the code' mod='cartabandonmentpro'} %DISCOUNT_CODE%. {l s='This offer end on the' mod='cartabandonmentpro'}
                            %DISCOUNT_VALID_DAY%-%DISCOUNT_VALID_MONTH%-%DISCOUNT_VALID_YEAR%
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-offset-1 col-md-5">
                        <div class="example_code">
                            {l s='Enjoy 10% off on your cart value with the code GO122. This offer ends on the 15-04-2016' mod='cartabandonmentpro'}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" name="discounts_form_submit" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='cartabandonmentpro'}</button>
        </div>
    </form>
</div>
