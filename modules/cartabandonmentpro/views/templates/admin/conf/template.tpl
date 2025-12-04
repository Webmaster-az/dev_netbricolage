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
    var showExample = "{l s='Show template example' mod='cartabandonmentpro'}";
    var hideExample = "{l s='Hide template example' mod='cartabandonmentpro'}";
</script>
    <div class="row">
        <div class="form-inline">
            <label class="control-label" for="tpl_same">
                {l s='Do you want the template to be identical for all your reminders ?' mod='cartabandonmentpro'}
            </label>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <span class="switch prestashop-switch input-group col-lg-2">
                <input type="radio" name="tpl_same" id="tpl_same_on" {if isset($templates.0.tpl_same) and $templates.0.tpl_same|intval eq 1}checked="checked"{/if}value="1"/>
                <label for="tpl_same_on" class="radioCheck" onClick="tplSame(1);">
                    <i class="color_success"></i> {l s='Yes' mod='cartabandonmentpro'}
                </label>
                <input type="radio" name="tpl_same" id="tpl_same_off" value="0" {if !isset($templates.0.tpl_same) or $templates.0.tpl_same|intval eq 0}checked="checked"{/if} />
                <label for="tpl_same_off" class="radioCheck" onClick="tplSame(0);">
                    <i class="color_danger"></i> {l s='No' mod='cartabandonmentpro'}
                </label>
                <a class="slide-button btn"></a>
            </span>
            <input type="hidden" id="tpl_same" name="tpl_same" value="{if isset($templates.0.tpl_same)}{$templates.0.tpl_same|intval}{/if}" />
        </div>
    </div>
    <br>
    <div id="wich_template_content" class="row form-inline" style="clear:both;{if isset($templates.0.tpl_same) and $templates.0.tpl_same|intval eq 1} display: none;{/if}">
        <div class="form-horizontal">
            {l s='Select a reminder to configure' mod='cartabandonmentpro'}
            &nbsp;&nbsp;
            <select class="form-control" id="wich_template" name="wich_template" onChange="changeTemplate();">
                <option id="wich_template_1" value="1">{l s='First reminder' mod='cartabandonmentpro'}</option>
                <option id="wich_template_2" value="2">{l s='Second reminder' mod='cartabandonmentpro'}</option>
                <option id="wich_template_3" value="3">{l s='Third reminder' mod='cartabandonmentpro'}</option>
            </select>
        </div>
    </div>
    <div class="row form-inline">
        <div class="form-horizontal">
            <label class="control-label">
                {l s='Email Subject' mod='cartabandonmentpro'}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </label>

            <input type="text" name="name_1" id="template_name_1" value="{foreach from=$templates item=template}{if $template.wich_remind == 1}{$template.template_name|escape:'htmlall':'UTF-8'}{/if}{/foreach}" class="form-control template_names"
            style="width: 300px;">
            <input type="text" name="name_2" id="template_name_2" value="{foreach from=$templates item=template}{if $template.wich_remind == 2}{$template.template_name|escape:'htmlall':'UTF-8'}{/if}{/foreach}" class="form-control template_names"
            style="display:none;width: 300px;">
            <input type="text" name="name_3" id="template_name_3" value="{foreach from=$templates item=template}{if $template.wich_remind == 3}{$template.template_name|escape:'htmlall':'UTF-8'}{/if}{/foreach}" class="form-control template_names"
            style="display:none;width: 300px;">
        </div>
        <br><br><br>
        <div class="cartab_panel panel panel-default">
            <div class="panel_cab-heading cartab_panel-heading">{l s='How to write your template ?' mod='cartabandonmentpro'}</div>
            <div class=" panel-body">
                {l s='You can customize your emails content by using the following tags.' mod='cartabandonmentpro'}<br><br>
                <span class="code">%FIRSTNAME%</span> {l s='client first name' mod='cartabandonmentpro'}<br>
                <span class="code">%LASTNAME%</span> {l s='client last name' mod='cartabandonmentpro'}<br>
                <span class="code">%GENDER%</span> {l s='client gender (M, Ms, Miss)' mod='cartabandonmentpro'}<br>
                <span class="code">%CART_PRODUCTS%</span> {l s='customer\'s cart content' mod='cartabandonmentpro'}<br>
                <br><br>
                <span class="code">%SHOP_LINK%</span> {l s='Link to your shop' mod='cartabandonmentpro'}. {l s='Tip: Use the "Insert link" button in the editor' mod='cartabandonmentpro'}.<br>
                <span class="code">%CART_LINK%</span> {l s='Link to the client\'s shopping cart' mod='cartabandonmentpro'}. {l s='Tip: Use the "Insert link" button in the editor' mod='cartabandonmentpro'}.<br>
                <span class="code">%UNSUBSCRIBE_LINK%</span> {l s='Unsubscribe link - mandatory in every commercial email' mod='cartabandonmentpro'}. {l s='Tip: Use the "Insert link" button in the editor' mod='cartabandonmentpro'}.<br>
                <br><br>
                <span class="code">%DISCOUNT_TXT%</span> {l s='This tag is special. You need to go to the discount tab to configuration its content before using it inside your template' mod='cartabandonmentpro'}
            </div>
        </div>
    </div>
    <div>
        <p>
                <strong>{l s='Select a template layout:' mod='cartabandonmentpro'}</strong>
        </p>
    </div>
    <!-- Template 1 -->
    <div id="model1_tpl1" class="row picto_model picto_tpl_1 tpl1" style="margin-bottom: 50px;float: left;margin-left: 5px;">
        <div style="width: 115px; height: 150px; background-image:url({$module_dir|escape:'quotes':'UTF-8'}model/1.png); margin: auto;" onClick="selectModel(1, 1);">&nbsp;</div>
    </div>
    <div id="model1_tpl2" class="row picto_model picto_tpl_2 tpl2" style="margin-bottom: 50px;display:none;float: left;margin-left: 60px;">
        <div style="width: 115px; height: 150px; background-image:url({$module_dir|escape:'quotes':'UTF-8'}model/1.png); margin: auto;" onClick="selectModel(1, 2);">&nbsp;</div>
    </div>
    <div id="model1_tpl3" class="row picto_model picto_tpl_3 tpl3" style="margin-bottom: 50px;display:none;float: left;margin-left: 60px;">
        <div style="width: 115px; height: 150px; background-image:url({$module_dir|escape:'quotes':'UTF-8'}model/1.png); margin: auto;" onClick="selectModel(1, 3);">&nbsp;</div>
    </div>
    <!-- Template 2 -->
    <div id="model2_tpl1" class="row picto_model picto_tpl_1 tpl1" style="margin-bottom: 50px;float: left;margin-left: 60px;">
        <div style="width: 115px; height: 150px; background-image:url({$module_dir|escape:'quotes':'UTF-8'}model/2.png); margin: auto;" onClick="selectModel(2, 1);">&nbsp;</div>
    </div>
    <div id="model2_tpl2" class="row picto_model picto_tpl_2 tpl2" style="margin-bottom: 50px;display:none;float: left;margin-left: 60px;">
        <div style="width: 115px; height: 150px; background-image:url({$module_dir|escape:'quotes':'UTF-8'}model/2.png); margin: auto;" onClick="selectModel(2, 2);">&nbsp;</div>
    </div>
    <div id="model2_tpl3" class="row picto_model picto_tpl_3 tpl3" style="margin-bottom: 50px;display:none;float: left;margin-left: 60px;">
        <div style="width: 115px; height: 150px; background-image:url({$module_dir|escape:'quotes':'UTF-8'}model/2.png); margin: auto;" onClick="selectModel(2, 3);">&nbsp;</div>
    </div>

    <div id="model" style="width:1024px; clear:both; margin-left: 5px;">
        {if $editor|intval == 0}
            <!-- Template 1 -->
            <div style="width: 1024px; height: auto; margin: auto; display: none; border: 1px solid black;" id="model_1_1" class="models model_1" onClick="setModel(1);">
                {include file="../../../../model/1_form.tpl"}
            </div>
            <div style="width: 1024px; height: auto; margin: auto; display: none; border: 1px solid black;" id="model_1_2" class="models model_1" onClick="setModel(1);">
                {include file="../../../../model/1_form2.tpl"}
            </div>
            <div style="width: 1024px; height: auto; margin: auto; display: none; border: 1px solid black;" id="model_1_3" class="models model_1" onClick="setModel(1);">
                {include file="../../../../model/1_form3.tpl"}
            </div>
            <!-- Template 2 -->
            <div style="width: 1250px; height: auto; margin: auto; display: none; border: 1px solid black;" id="model_2_1" class="models model_2" onClick="setModel(2);">
                {include file="../../../../model/2_form.tpl"}
            </div>
            <div style="width: 1250px; height: auto; margin: auto; display: none; border: 1px solid black;" id="model_2_2" class="models model_2" onClick="setModel(2);">
                {include file="../../../../model/2_form2.tpl"}
            </div>
            <div style="width: 1250px; height: auto; margin: auto; display: none; border: 1px solid black;" id="model_2_3" class="models model_2" onClick="setModel(2);">
                {include file="../../../../model/2_form3.tpl"}
            </div>

            <input type="hidden" name="model1" id="model1" value="1">
            <input type="hidden" name="model2" id="model2" value="1">
            <input type="hidden" name="model3" id="model3" value="1">
        {else}
            <!-- Created templates -->
            <div style="width: 1024px; height: auto; margin: auto;" id="model_{$edit_model_id1|intval}_1" class="models model_{$edit_model_id1|intval} reminder1">
                {$template_content_1}
            </div>
            <div style="width: 1024px; height: auto; margin: auto;display: none;" id="model_{$edit_model_id2|intval}_2" class="models model_{$edit_model_id2|intval} reminder2">
                {$template_content_2}
            </div>
            <div style="width: 1024px; height: auto; margin: auto;display: none;" id="model_{$edit_model_id3|intval}_3" class="models model_{$edit_model_id3|intval} reminder3">
                {$template_content_3}
            </div>

            <!-- Template 2 -->
            {if $edit_model_id1|intval neq "2"}
            <div style="width: 1250px; height: auto; margin: auto; display: none;" id="model_2_1" class="models model_2 reminder1">
                {include file="../../../../model/2_form.tpl"}
            </div>
            {/if}
            {if $edit_model_id2|intval neq "2"}
            <div style="width: 1250px; height: auto; margin: auto; display: none;" id="model_2_2" class="models model_2 reminder2">
                {include file="../../../../model/2_form2.tpl"}
            </div>
            {/if}
            {if $edit_model_id3|intval neq "2"}
            <div style="width: 1250px; height: auto; margin: auto; display: none;" id="model_2_3" class="models model_2 reminder3">
                {include file="../../../../model/2_form3.tpl"}
            </div>
            {/if}

            <!-- Template 1 -->
            {if $edit_model_id1|intval neq "1"}
            <div style="width: 1024px; height: auto; margin: auto; display: none;" id="model_1_1" class="models model_1 reminder1">
                {include file="../../../../model/1_form.tpl"}
            </div>
            {/if}
            {if $edit_model_id2|intval neq "1"}
            <div style="width: 1024px; height: auto; margin: auto; display: none;" id="model_1_2" class="models model_1 reminder2">
                {include file="../../../../model/1_form2.tpl"}
            </div>
            {/if}
            {if $edit_model_id3|intval neq "1"}
            <div style="width: 1024px; height: auto; margin: auto; display: none;" id="model_1_3" class="models model_1 reminder3">
                {include file="../../../../model/1_form3.tpl"}
            </div>
            {/if}
            <input type="hidden" name="model1" id="model1" value="{$edit_model_id1|intval}">
            <input type="hidden" name="model2" id="model2" value="{$edit_model_id2|intval}">
            <input type="hidden" name="model3" id="model3" value="{$edit_model_id3|intval}">
            <script>
                $("#model{$edit_model_id1|intval}_tpl{$template_file_1|escape:'htmlall':'UTF-8'}").css('border', '1px #585858 solid');
                $("#model{$edit_model_id2|intval}_tpl{$template_file_2|escape:'htmlall':'UTF-8'}").css('border', '1px #585858 solid');
                $("#model{$edit_model_id2|intval}_tpl{$template_file_2|escape:'htmlall':'UTF-8'}").css('border', '1px #585858 solid');
            </script>
        {/if}
    </div>
    <input type="hidden" name="edit" value="1">
    <input type="hidden" id="id_lang" name="id_lang" value="{$language|intval}">
    <input type="hidden" id="tpl" name="tpl" value="1">
    <input type="hidden" id="edittpl" name="edittpl1" value="{if isset($edit_template_id1)}{$edit_template_id1|intval}{/if}">
    <input type="hidden" id="edittpl" name="edittpl2" value="{if isset($edit_template_id2)}{$edit_template_id2|intval}{/if}">
    <input type="hidden" id="edittpl" name="edittpl3" value="{if isset($edit_template_id3)}{$edit_template_id3|intval}{/if}">
    <input type="hidden" name="uri" value="{$uri|escape:'quotes':'UTF-8'}">

    <input type="hidden" id="token_cartabandonment" name="token_cartabandonment" value="{$token|escape:'htmlall':'UTF-8'}">
    <div class="panel-footer">
        <button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='cartabandonmentpro'}</button>
    </div>
    <input type="hidden" name="id_shop" value="{$id_shop|intval}">
</form>
