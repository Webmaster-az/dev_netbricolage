{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

{extends file="helpers/options/options.tpl"}
{block name="after"}
<script type="text/javascript">
    $.fn.mColorPicker.defaults.imageFolder = baseDir + 'img/admin/';
</script>
{/block}
{block name="input"}
	
    {*setting Bar*}
    {if $field['type'] == 'popupbar'}
        <div class="hide">
            <input class="title_bar" value='{l s='Title Tab Bar This field is required at least in Default language.' mod='g_cartreminder'}'/>
            <input class="g_module_url" value='{$g_module_url|escape:'htmlall':'UTF-8'}'/>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">
                {l s='Enable Sticky Bar Reminder' mod='g_cartreminder'}
            </label>
            <div class="col-lg-9">
                <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="active" id="active_on" {if $active == 1}checked="checked"{/if} value="1"/>
                <label for="active_on">{l s='Yes' mod='g_cartreminder'}</label>
                <input type="radio" name="active" id="active_off" value="0" {if $active == 0}checked="checked"{/if}/>
                <label for="active_off">{l s='No' mod='g_cartreminder'}</label>
                <a class="slide-button btn"></a>
                </span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3 required" for="title_settingbar">
                {l s='Title' mod='g_cartreminder'}
            </label>
            {foreach from=$languages item=language}
				{if $languages|count > 1}
					<div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
				{/if}
					<div class="col-lg-7">
						<input type="text" class="title" name="title_{$language.id_lang|escape:'html':'UTF-8'}" value="{if isset($titles[$language.id_lang])}{$titles[$language.id_lang]|escape:'html':'UTF-8'}{/if}"/>
                        <p class="help-block">
                            {l s='You can use variables:' mod='g_cartreminder'}
                            <code>{literal}{total_items}{/literal},{literal}{customer_firstname}{/literal},{literal}{customer_lastname}{/literal}</code>,<code>{literal}{cart_link_start}{/literal}</code> 
                            {l s='anchor text here' mod='g_cartreminder'} 
                            <code>{literal}{cart_link_end}{/literal}</code>
                        </p>
					</div>
				{if $languages|count > 1}
					<div class="col-lg-2">
						<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
							{$language.iso_code|escape:'html':'UTF-8'}
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							{foreach from=$languages item=lang}
							<li><a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'html':'UTF-8'});" tabindex="-1">{$lang.name|escape:'html':'UTF-8'}</a></li>
							{/foreach}
						</ul>
					</div>
				{/if}
				{if $languages|count > 1}
					</div>
				{/if}
			{/foreach}                      
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{l s='Delay Time (in minutes)' mod='g_cartreminder'}</label>
            <div class="col-lg-4">
                <input class="form-control" type="number" id="delay" name="delay" value="{$delay|escape:'html':'UTF-8'}" onkeypress="return isNumberKey(event)"/>
                <p class="help-block">
                    {l s='Delay Time description: You can set the delay time to show popup bar since customers add a product to cart' mod='g_cartreminder'}
                </p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{l s='Bar text color' mod='g_cartreminder'}</label>
            <div class="col-lg-4">
                <div class="input-group">
                <input data-hex="true" class="mColorPickerTrigger" type="color" id="textcolor" name="textcolor" value="{$textcolor|escape:'html':'UTF-8'}"/>
            </div> </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{l s='Bar background color' mod='g_cartreminder'}</label>
            <div class="col-lg-4">
                <div class="input-group">
                    <input data-hex="true" class="mColorPickerTrigger" type="color" id="bcolor_bar" name="backgroundcolor" value="{$backgroundcolor|escape:'html':'UTF-8'}"/>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{l s='Position' mod='g_cartreminder'}</label>
            <div class="col-lg-9">
                <div class="radio t">
					<label class="col-lg-2"><input type="radio" name="position" id="position1" value="1" {if $position == 1}checked="checked"{/if}/>{l s='Header' mod='g_cartreminder'}</label>
                    <label class="col-lg-2"><input type="radio" name="position" id="position2" value="2" {if $position == 2}checked="checked"{/if}/>{l s='Bottom' mod='g_cartreminder'}</label>
                </div>
            </div>
        </div>
    {else}
		{$smarty.block.parent}
	{/if}
{/block}