{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

{extends file="helpers/form/form.tpl"}
{block name="script"}
var copyToClipboard_success = "{l s='Copy to clipboard successfully' mod='g_cartreminder'}";
var link_gcart_preview = "{$fields_value['fornlink']|escape:'htmlall':'UTF-8'}";
{/block}
{block name="field"}
    {if $input.type == 'popup_template'}
        <div class="col-lg-9">
            <div class="form-group">
                <label class="control-label col-lg-3 required">
                    {l s='Name' mod='g_cartreminder'}
                </label>
                <div class="col-lg-9">
                    {foreach from=$languages item=language}
                        {if $languages|count > 1}
                            <div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                        {/if}
                            <div class="col-lg-{if $languages|count > 1}10{else}12{/if}">
                                <input type="text" id="name_{$language.id_lang|escape:'html':'UTF-8'}" name="name_{$language.id_lang|escape:'html':'UTF-8'}" class="" value="{$fields_value['name'][{$language.id_lang|escape:'html':'UTF-8'}]|escape:'htmlall':'UTF-8'}" onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();">
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
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">{l s='Active' mod='g_cartreminder'}</label>
                <div class="col-lg-9">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="active" id="active_on" value="1" {if $fields_value['active'] == 1} checked="checked" {/if}/>
                        <label for="active_on">{l s='Yes' mod='g_cartreminder'}</label>
                        <input type="radio" name="active" id="active_off" value="0" {if $fields_value['active'] == 0} checked="checked" {/if} />
                        <label for="active_off">{l s='No' mod='g_cartreminder'}</label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Background img' mod='g_cartreminder'}
                </label>
                <div class="col-lg-6"> 
                    {if isset($fields_value['imgbackground']) && $fields_value['imgbackground'] !=''}
                        <div class="popup_background_img">
                            <p><img style="max-width:150px;max-height:150px;" src="{$fields_value['g_module_url']|escape:'html':'UTF-8'}image/popup/{$fields_value['imgbackground']|escape:'html':'UTF-8'}" alt="" /></p>
                            <p><input type="checkbox" name="remove_background" value="1" /><label>{l s='Remove' mod='g_cartreminder'}</label></p>
                        </div>
                    {/if}
                    <div class="dummyfile input-group">
                        <input id="imgbackground" type="file" name="imgbackground" class="hide-file-upload" value="{$fields_value['imgbackground']|escape:'html':'UTF-8'}"/>
                        <span class="input-group-addon"><i class="icon-file"></i></span>
                        <input id="imgbackground-name" type="text" class="disabled" name="filename" value="{$fields_value['imgbackground']|escape:'html':'UTF-8'}" readonly/>
                        <span class="input-group-btn">
                            <button id="imgbackground-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                                <i class="icon-folder-open"></i> {l s='Choose a file' mod='g_cartreminder'}
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">							
                <label class="control-label col-lg-3">
                    {l s='Background color' mod='g_cartreminder'}
                </label>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="input-group">
                            <input class="form-control" data-hex="true" type="color" id="colorbackground" name="colorbackground" value="{if $fields_value['colorbackground']}{$fields_value['colorbackground']|escape:'html':'UTF-8'}{/if}"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3"> {l s='Min cart' mod='g_cartreminder'} </label>
                <div class="col-lg-6">
                    {foreach from=$fields_value['Currencies'] item=currencie}
                        <div class="row currencie-field curen-{$currencie['id_currency']|escape:'html':'UTF-8'}" {if $currencie['id_currency'] != $fields_value['currency']->id}style="display:none"{/if}>
                            <div class="col-lg-10">
                                <input type="text" class="mincart_{$currencie['id_currency']|escape:'html':'UTF-8'}" name="mincart[{$currencie['id_currency']|escape:'html':'UTF-8'}]" value="{if isset($fields_value['mincart'][$currencie['id_currency']])}{$fields_value['mincart'][$currencie['id_currency']]|escape:'html':'UTF-8'}{else}0{/if}" onchange="this.value = this.value.replace(/,/g, '.');" onkeypress="return isNumberKey(event)"/>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                    {$currencie['sign']|escape:'html':'UTF-8'}
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    {foreach from=$fields_value['Currencies'] item=curren}
                                        <li><a href="javascript:hideOtherCurreny({$curren['id_currency']|escape:'html':'UTF-8'});" tabindex="-1">{$curren['name']|escape:'html':'UTF-8'}</a></li>
                                    {/foreach}
                                </ul>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3 required">
                    {l s=' Popup Template' mod='g_cartreminder'}
                </label>
                <div class="col-lg-9">
                    {foreach from=$languages item=language}
                        {if $languages|count > 1}
                            <div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                        {/if}
                            <div class="col-lg-{if $languages|count > 1}10{else}12{/if}">
                                <textarea type="text" id="html_{$language.id_lang|escape:'html':'UTF-8'}" name="html_{$language.id_lang|escape:'html':'UTF-8'}" class="rte autoload_rte gautoload_rte" onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();">
                                   {$fields_value['html'][{$language.id_lang|escape:'html':'UTF-8'}]|escape:'htmlall':'UTF-8'}
                                </textarea>
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
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3"> {l s='Max Width' mod='g_cartreminder'} </label>
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-addon">{l s='Px' mod='g_cartreminder'}</span>
                        <input type="number" class="form-control" name="maxwidth" id="maxwidth" value="{$fields_value['maxwidth']|escape:'html':'UTF-8'}" placeholder="0" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3"> {l s='Time Delay' mod='g_cartreminder'} </label>
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    {l s='Hrs' mod='g_cartreminder'}
                                </span>
                                <input type="number" class="form-control" name="day" id="day" value="{$fields_value['day']|escape:'html':'UTF-8'}" min="0" placeholder="0" autocomplete="off"/>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    {l s='Minutes' mod='g_cartreminder'}
                                </span>
                                <input type="number" class="form-control" name="hrs" id="hrs" value="{$fields_value['hrs']|escape:'html':'UTF-8'}" min="0" placeholder="0" autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3"> {l s='Countdown' mod='g_cartreminder'} </label>
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-addon">{l s='Minutes' mod='g_cartreminder'}</span>
                        <input type="number" class="form-control" min="0" name="countdown" id="countdown" value="{$fields_value['countdown']|escape:'html':'UTF-8'}" placeholder="0" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">{l s='Restart Countdown' mod='g_cartreminder'}</label>
                <div class="col-lg-6">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="reset_countdown" id="reset_countdown_on" value="1" {if $fields_value['reset_countdown'] == 1}checked="checked"{/if}/>
                        <label for="reset_countdown_on">{l s='Yes' mod='g_cartreminder'}</label>
                        <input type="radio" name="reset_countdown" id="reset_countdown_off" value="0" {if $fields_value['reset_countdown'] == 0}checked="checked"{/if}/>
                        <label for="reset_countdown_off">{l s='No' mod='g_cartreminder'}</label>
                        <a class="slide-button btn"></a>
                    </span>
                    <p class="help-block">{l s='Restart the countdown when the timer reaches "0 minutes 0 seconds"' mod='g_cartreminder'}</p>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3"> {l s='Display' mod='g_cartreminder'} </label>
                <div class="col-lg-9">
                    <div class="radio t">
                        <label>
                            <input type="radio" name="display" id="display1" value="1" {if $fields_value['display'] == 1}checked="checked"{/if}/>{l s='Exit-Intent (When customer exit Page)' mod='g_cartreminder'}
                        </label>
                        <p class="help-block">{l s='Only available for desktop.' mod='g_cartreminder'}</p>
                    </div>
                    <div class="radio t">
                        <label><input type="radio" name="display" id="display2" value="2" {if $fields_value['display'] == 2}checked="checked"{/if}/>{l s='On first page only' mod='g_cartreminder'}</label>
                    </div>
                    <div class="radio t">
                        <label><input type="radio" name="display" id="display3" value="3"{if $fields_value['display'] == 3}checked="checked"{/if}/>{l s='On second page only' mod='g_cartreminder'}</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">{l s='Show Social' mod='g_cartreminder'}</label>
                <div class="col-lg-9">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="displayss" id="displayss_on" value="1" {if $fields_value['displayss'] == 1}checked="checked"{/if} />
                        <label for="displayss_on">{l s='Yes' mod='g_cartreminder'}</label>
                        <input type="radio" name="displayss" id="displayss_off" value="0" {if $fields_value['displayss'] == 0}checked="checked"{/if} />
                        <label for="displayss_off">{l s='No' mod='g_cartreminder'}</label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class="panel1 col-lg-offset-3 col-lg-9 Soscialshow" {if $fields_value['displayss'] != 1}style="display:none"{/if}>
                    <div class="form-group">
                        <label class="control-label col-lg-3"> <i class="icon-facebook facebook-iconcss"></i> {l s='Facebook' mod='g_cartreminder'} </label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="sosicalfb" name="sosicalfb" value="{$fields_value['sosicalfb']|escape:'html':'UTF-8'}" />
                            <p class="help-block">
                                {l s='URL Facebook E.g:' mod='g_cartreminder'} https://www.facebook.com/
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3"> <i class="icon-twitter twitter-iconcss"></i> {l s='Twitter' mod='g_cartreminder'} </label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="sosicaltw" name="sosicaltw" value="{$fields_value['sosicaltw']|escape:'html':'UTF-8'}" />
                            <p class="help-block">
                                {l s='Twitter Username E.g: globo' mod='g_cartreminder'}
                            </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-3"><i class="icon-google-plus google-iconcss"></i> {l s='Google+' mod='g_cartreminder'} </label>
                        <div class="col-lg-9">
                            <input class="form-control" type="text" id="sosicalgg" name="sosicalgg" value="{$fields_value['sosicalgg']|escape:'html':'UTF-8'}" />
                            <p class="help-block">
                                {l s='URL Google+ E.g:' mod='g_cartreminder'} https://www.google.com.vn/
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">{l s='Auto Generate Discount Code' mod='g_cartreminder'}</label>
                <div class="col-lg-9">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="autocode" id="autocode_on" value="1"  {if $fields_value['autocode'] == 1}checked="checked"{/if} />
                        <label for="autocode_on">{l s='Yes' mod='g_cartreminder'}</label>
                        <input type="radio" name="autocode" id="autocode_off" value="0"  {if $fields_value['autocode'] == 0}checked="checked"{/if} />
                        <label for="autocode_off">{l s='No' mod='g_cartreminder'}</label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class="panel1 col-lg-offset-3 col-lg-9" >
                    <div class="form-group codetext" {if $fields_value['autocode'] == 1}style="display:none"{/if}>
                        <label class="control-label col-lg-3">
                            {l s='Voucher' mod='g_cartreminder'}
                        </label>
                        <div class="col-lg-9">
                            <div class="input-group col-lg-6">
                                <input type="text" id="code" name="code" value="{$fields_value['code']|escape:'html':'UTF-8'}"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group autocodeshow" {if $fields_value['autocode'] != 1}style="display:none"{/if}>
                        <label class="control-label col-lg-3">
                            {l s='Discount Type' mod='g_cartreminder'}
                        </label>
                        <div class="col-lg-9 autocodeshow">
                            <div class="radio t">
                                <label class="col-lg-4"><input type="radio" name="autocodetype" id="autocodetype1" value="1" {if $fields_value['autocodetype'] ==1}checked="checked"{/if}/>{l s='Percentage (%)' mod='g_cartreminder'}</label>
                                <label class="col-lg-4"><input type="radio" name="autocodetype" id="autocodetype2" value="2" {if $fields_value['autocodetype'] ==2}checked="checked"{/if}/>{l s='Amount' mod='g_cartreminder'}</label>
                                <label class="col-lg-4"><input type="radio" name="autocodetype" id="autocodetype3" value="3" {if $fields_value['autocodetype'] ==3}checked="checked"{/if}/><i class="icon-remove color_danger"></i>{l s='None' mod='g_cartreminder'}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group autocodeshow active" {if $fields_value['autocode'] != 1 || $fields_value['autocodetype'] == 3}style="display:none"{/if}>
                        <label class="control-label col-lg-3">{l s='Discount Value' mod='g_cartreminder'}</label>
                        <div class="col-lg-3">
                            <input class="form-control" type="number" min="0" placeholder="0" name="autocodevalue" id="autocodevalue" value="{$fields_value['autocodevalue']|escape:'html':'UTF-8'}"/>
                        </div>
                        <div class="col-lg-3 amount_discountoff" {if $fields_value['autocodetype'] !=2}style="display:none"{/if}>
                            <select name="autocodeid_currency">
                                {foreach from=$fields_value['Currencies'] item=currencie}
                                    <option value="{$currencie['id_currency']|escape:'html':'UTF-8'}" {if $fields_value['autocodeid_currency'] == $currencie['id_currency']}selected="selected"{/if}>{$currencie['name']|escape:'html':'UTF-8'}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="col-lg-3 amount_discountoff" {if $fields_value['autocodetype'] !=2}style="display:none"{/if}>
                            <select name="autocodetax">
                                <option value="0" {if $fields_value['autocodetax'] ==0}selected="selected"{/if}>{l s='Tax excluded' mod='g_cartreminder'}</option>
                                <option value="1" {if $fields_value['autocodetax'] ==1}selected="selected"{/if}>{l s='Tax included' mod='g_cartreminder'}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group autocodeshow active" {if $fields_value['autocode'] != 1 || $fields_value['autocodetype'] == 3}style="display:none"{/if}>
                        <label class="control-label col-lg-3">{l s='Coupon Validity' mod='g_cartreminder'}</label>
                        <div class="col-lg-6">
                            <input class="form-control" type="number" min="0" placeholder="0" name="autocodeday" id="autocodeday" value="{$fields_value['autocodeday']|escape:'html':'UTF-8'}"/>
                        </div>
                    </div>
                    <div class="form-group autocodeshow active" {if $fields_value['autocode'] != 1 || $fields_value['autocodetype'] == 3}style="display:none"{/if}>
                        <label class="control-label col-lg-3">{l s='Free shipping' mod='g_cartreminder'}</label>
                        <div class="col-lg-6">
                            <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="autocodeship" id="autocodeship_on" value="1" {if $fields_value['autocodeship'] == 1}checked="checked"{/if}/>
                            <label for="autocodeship_on">{l s='Yes' mod='g_cartreminder'}</label>
                            <input type="radio" name="autocodeship" id="autocodeship_off" value="0" {if $fields_value['autocodeship'] == 0}checked="checked"{/if}/>
                            <label for="autocodeship_off">{l s='No' mod='g_cartreminder'}</label>
                            <a class="slide-button btn"></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-3">
                    {l s='Custom Css' mod='g_cartreminder'}
                </label>
                <div class="col-lg-9">
                    <textarea name="customcss" id="customcss" rows="10" class="textarea-autosize" style="overflow: hidden; overflow-wrap: break-word; resize: none; height: 184px;">{$fields_value['customcss']|escape:'html':'UTF-8'}</textarea>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="emailshortcode_wp">
                <div class="publish_box emailshortcode_panel">
                    <div class="box-heading">
                        <i class="icon-code"></i>
                        {l s='Variables' mod='g_cartreminder'}
                    </div>
                    <div class="gbox_content">
                        <p class="help-block">
                            {l s='Click to copy variable' mod='g_cartreminder'}
                        </p>
                        <div class="emailshortcode">
                            <table>
                                <tbody>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Customer first name' mod='g_cartreminder'}">{l s='{customer_firstname}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title=" {l s='Customer last name' mod='g_cartreminder'}">{l s='{customer_lastname}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Add link to your store. E.g: click{shop_link_start} here {shop_link_end}' mod='g_cartreminder'}">{l s='{shop_link_start}{shop_link_end}' mod='g_cartreminder'} </span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Add link to cart E.g: click{cart_link_start} here {cart_link_end} to complete your order' mod='g_cartreminder'}">{l s='{cart_link_start}{cart_link_end}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title=" {l s='Add link to your store: click<a href="{shop_link_url}">here</a> to access our shop' mod='g_cartreminder'}">{l s='{shop_link_url}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Link to checkout page, step 3.' mod='g_cartreminder'}">{l s='{cart_url}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Link to checkout page, step 1' mod='g_cartreminder'}">{l s='{cart_url_s1}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title=" {l s='Link to checkout page, step 2' mod='g_cartreminder'}">{l s='{cart_url_s2}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Add coupon code (only if the rule creat a coupon)' mod='g_cartreminder'}">{l s='{voucher_code}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Add coupon expiry date' mod='g_cartreminder'}">{l s='{voucher_expirate_date}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Total product in cart' mod='g_cartreminder'}">{l s='{total_product}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Total shipping in cart' mod='g_cartreminder'}">{l s='{total_shipping}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Total price in cart' mod='g_cartreminder'}">{l s='{total_price}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Add Facebook button' mod='g_cartreminder'}">{l s='{facebook}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Add Twitter button' mod='g_cartreminder'}">{l s='{twitter}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Add Google+ button' mod='g_cartreminder'}">{l s='{google}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Countdown html' mod='g_cartreminder'}">{l s='{countdown}' mod='g_cartreminder'}</span></td>
                                    </tr>
                                    <tr class="even copy_group">
                                        <td><span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title="{l s='Click to show details' mod='g_cartreminder'}">{l s='{cart_product}' mod='g_cartreminder'}</span>
                                            <a class="btn btn-default showimgprtpl pull-right" title="Preview" data-name="cart_product.png">
                                                <i class="icon-eye-open"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class=" copy_group">
                                        <td>
                                            <span data-toggle="tooltip" class="glabel-tooltip  copy_data copy_link" data-original-title=" {l s='Display list of products in cart. ' mod='g_cartreminder'}">{l s='{cart_product_txt}' mod='g_cartreminder'}</span>
                                            <a class="btn btn-default showimgprtxt pull-right" title="Preview" data-name="cart_product_txt.png">
                                                <i class="icon-eye-open"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {/if}
    {$smarty.block.parent}
{/block}