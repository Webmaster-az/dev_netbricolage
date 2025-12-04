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

	{if $field['type'] == 'settingnotification'}

                    </div>

                </div>

            </div>

        </div>

        <div class="gcarttabs-list">

            <ul class="tabs-create nav nav-tabs">

                <li class="active">

                    <a href="#gnotification" data-toggle="tab">

                        {l s='Browser notification' mod='g_cartreminder'}

                    </a>

                </li>

               {*  <li>

                    <a href="#gtab_notification" data-toggle="tab">

                        {l s='browser tab notification' mod='g_cartreminder'}

                    </a>

                </li> *}

                <li>

                    <a href="#help_gnotification" data-toggle="tab">

                        {l s='Help' mod='g_cartreminder'}

                    </a>

                </li>

            </ul>

        </div>

        <div class="panel col-lg-12 gcart-none-borderradius">

            <div class="form-group active" id="gnotification">

                <div class="hide">

                    <input class="showlog" value="{l s='Title Tab Browser Notification This field is required at least in Default language.' mod='g_cartreminder'}"/>

                    <input class="title_tab" value="{l s='Message Tab Browser Tab Notification This field is required at least in Default language.' mod='g_cartreminder'}"/>

                    <input class="onesignal_id" value="{l s='OneSignal app id Tab Browser Notification This field is required.' mod='g_cartreminder'}"/>

                    <input class="onesignal_rest_id" value="{l s='OneSignal REST API Key Tab Browser Notification This field is required.' mod='g_cartreminder'}"/>

                </div>

                <div class="col-lg-7">

                    <div class="form-group">

                        <div>

                            <label class="control-label">

                                {l s='Upload Icon' mod='g_cartreminder'}

                            </label>

                        </div>

                        <div class="form-group">

                            <div class="col-sm-12">

                                <input id="img_icon" type="file" name="img_icon" class="hide"/>

                                <div class="dummyfile input-group">

                                    <span class="input-group-addon"><i class="icon-file"></i></span>

                                    <input id="img_icon-name" type="text" name="notification[img_icon]" value="{if isset($notification['img_icon']) && $notification['img_icon']}{$notification['img_icon']|escape:'html':'UTF-8'}{/if}" readonly=""/>

                                    <span class="input-group-btn">

                                        <button id="img_icon-selectbutton" type="button" name="submitAddAttachmentimgicon" class="btn btn-default">

                                            <i class="icon-folder-open"></i>{l s=' Add file' mod='g_cartreminder'}

                                        </button>

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="form-group">

                        <div>

                            <label class="control-label required" for="title_popup">

                                {l s='Title' mod='g_cartreminder'}

                            </label>

                        </div>

                        {foreach from=$languages item=language}

                            {if $languages|count > 1}

                                <div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>

                            {/if}

                                <div class="col-lg-{if $languages|count > 1}10{else}12{/if}">

                                    <input type="text" class="title_notification" name="title_notification_{$language.id_lang|escape:'html':'UTF-8'}" value="{$title_notification[$language.id_lang]|escape:'html':'UTF-8'}" onkeyup="titlesnotification(this.value)"/>

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

                        <div>

                            <label class="control-label" for="message_notification">

                                {l s='Message' mod='g_cartreminder'}

                            </label>

                        </div>

                        {foreach from=$languages item=language}

                            {if $languages|count > 1}

                                <div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>

                            {/if}

                                <div class="col-lg-{if $languages|count > 1}10{else}12{/if}">

                                    <textarea class="message_notification" name="message_notification_{$language.id_lang|escape:'html':'UTF-8'}" onkeyup="mesagenotification(this.value)">{$message_notification[$language.id_lang]|escape:'html':'UTF-8'}</textarea>

                                    <p class="help-block">

                                        {l s='You can use variables: ' mod='g_cartreminder'}

                                        <code> {literal}{total_items} {/literal} </code>,

                                        <code> {literal}{customer_firstname} {/literal} </code>

                                        {l s='and' mod='g_cartreminder'}

                                        <code> {literal}{customer_lastname} {/literal}</code>

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

                        <input class="form-control" type="hidden" id="delay_notification" name="notification[delay_notification]" value="{if isset($notification['delay_notification']) && $notification['delay_notification']}{$notification['delay_notification']|escape:'html':'UTF-8'}{/if}" />

                        <table id="table_add_new_delay_notification" class="table">

                            <thead>

                                <tr>

                                    <th>{l s='Frequency' mod='g_cartreminder'}</th>

                                    <th>{l s='Days' mod='g_cartreminder'}</th>

                                    <th>{l s='Hrs' mod='g_cartreminder'}</th>

                                </tr>

                            </thead>

                            <tbody>

                                <tr class="delay_notification">

                                    <td class="index_tr"><span class="badge pull-left">1</span></td>

                                    <td><div class="controls"><input type="number" min="0" class="days_delay_notification_val form-control" onkeypress="return isNumberKey(event)" value="{if isset($notification['delay_notifications']) && isset($notification['delay_notifications'][0]) && isset($notification['delay_notifications'][0][0])}{$notification['delay_notifications'][0][0]|intval}{/if}" /></div></td>

                                    <td><div class="controls"><input type="number" min="0" class="hrs_delay_notification_val form-control" onkeypress="return isNumberKey(event)" value="{if isset($notification['delay_notifications']) && isset($notification['delay_notifications'][0]) && isset($notification['delay_notifications'][0][1])}{$notification['delay_notifications'][0][1]|intval}{/if}" /></div></td>

                                </tr>

                                <tr class="delay_notification">

                                    <td class="index_tr"><span class="badge pull-left">2</span></td>

                                    <td><div class="controls"><input type="number" min="0" class="days_delay_notification_val form-control" onkeypress="return isNumberKey(event)" value="{if isset($notification['delay_notifications']) && isset($notification['delay_notifications'][1]) && isset($notification['delay_notifications'][1][0])}{$notification['delay_notifications'][1][0]|intval}{/if}" /></div></td>

                                    <td><div class="controls"><input type="number" min="0" class="hrs_delay_notification_val form-control" onkeypress="return isNumberKey(event)" value="{if isset($notification['delay_notifications']) && isset($notification['delay_notifications'][1]) && isset($notification['delay_notifications'][1][1])}{$notification['delay_notifications'][1][1]|intval}{/if}" /></div></td>

                                </tr>

                                <tr class="delay_notification">

                                    <td class="index_tr"><span class="badge pull-left">3</span></td>

                                    <td><div class="controls"><input type="number" min="0" class="days_delay_notification_val form-control" onkeypress="return isNumberKey(event)" value="{if isset($notification['delay_notifications']) && isset($notification['delay_notifications'][2]) && isset($notification['delay_notifications'][2][0])}{$notification['delay_notifications'][2][0]|intval}{/if}" /></div></td>

                                    <td><div class="controls"><input type="number" min="0" class="hrs_delay_notification_val form-control" onkeypress="return isNumberKey(event)" value="{if isset($notification['delay_notifications']) && isset($notification['delay_notifications'][2]) && isset($notification['delay_notifications'][2][1])}{$notification['delay_notifications'][2][1]|intval}{/if}" /></div></td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <div class="form-group hide">

                        <div class="col-lg-12">

                            <div class="checkbox">

                                <label for="checkout_for">

                                    <input type="checkbox" name="notification[checkout]" id="checkout_for" value="1" {if isset($notification['checkout']) && $notification['checkout'] == 1} checked="checked" {/if} />

                                        {l s='Add "Checkout now" action button' mod='g_cartreminder'}

                                </label>

                            </div>

                        </div>

                    </div>

                    <div class="form-group hide">

                        <label class="control-label col-lg-3" for="checkouttitle">

                            {l s='title' mod='g_cartreminder'}

                        </label>

                        <div class="col-lg-9">

                            <input type="text" id="checkouttitle" name="notification[checkouttitle]" value="{if isset($notification['checkouttitle']) && $notification['checkouttitle']}{$notification['checkouttitle']|escape:'html':'UTF-8'}{/if}" onkeyup="checkoutbutton(this.value)" />

                        </div>

                    </div>

                </div>

                <div class="col-lg-4 col-lg-offset-1" id="notificationsetting">

                    <div class="tips form-group">

                        <span class="help-block" style="text-align: center;">{l s='Your notification could be like this' mod='g_cartreminder'}</span>

                    </div>

                    <div class="form-group" id="showdemorevews">

                        <div class="content-show">

                            <div class="col-md-4 show-icon" {if isset($notification['img_icon']) && $notification['img_icon'] !=''} style="background-image: url({$g_module_url|escape:'html':'UTF-8'}image/browser/{$notification['img_icon']|escape:'html':'UTF-8'}); background-repeat: no-repeat; background-size: cover;background-position: center;"{/if}>

                            </div>

                            <div class="col-md-8 show-message">

                                {foreach from=$languages item=language}

                                    <div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>

                                        <div class="title-shownotiction" id="title_shownotiction{$language.id_lang|escape:'html':'UTF-8'}">

                                            {$title_notification[$language.id_lang]|escape:'html':'UTF-8'}

                                        </div>

                                        <div class="message-shownotiction" id="message_shownotiction{$language.id_lang|escape:'html':'UTF-8'}">

                                            {$message_notification[$language.id_lang]|escape:'html':'UTF-8'}

                                        </div>

                                    </div>

                                {/foreach}

                                <div class="domain-shownotiction">

                                    {$g_domain|escape:'htmlall':'UTF-8'}

                                </div>

                            </div>

                        </div>

                        <div class="button-notification col-md-12" {if isset($notification['checkout']) && $notification['checkout'] != 0} style="display:block;"{/if}>

                            <i class="icon-chevron-right right"></i>

                            <span class="text-button-checkout">

                                {if isset($notification['checkouttitle']) && $notification['checkouttitle']}{$notification['checkouttitle']|escape:'html':'UTF-8'}{/if}

                            </span>

                        </div>

                    </div>

                </div>

            </div>

            <div class="form-group" id="gtab_notification">

                <div class="col-md-12">

                    <div class="form-group">

                        <div>

                            <label class="control-label col-lg-3 required" for="message_tab">

                                {l s='Message' mod='g_cartreminder'}

                            </label>

                        </div>

                        <div class="col-lg-9">

                            <div class="row">

                            {foreach from=$languages item=language}

                                {if $languages|count > 1}

                                    <div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>

                                {/if}

                                    <div class="col-lg-{if $languages|count > 1}10{else}12{/if}">

                                        <textarea class="message_tab" name="message_tab_{$language.id_lang|escape:'html':'UTF-8'}">{$message_tab[$language.id_lang]|escape:'html':'UTF-8'}</textarea>

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

                    </div>

                    <div class="form-group">

                        <label class="control-label col-lg-3">

                            {l s='Background color' mod='g_cartreminder'}

                        </label>

                        <div class="col-lg-4">

                            <div class="">

                                <div class="input-group">

                                    <input data-hex="true" class="mColorPickerTrigger" type="color" id="bg_color" name="notificationtab[bg_color]" value="{if isset($setting_tab['bg_color']) && $setting_tab['bg_color']}{$setting_tab['bg_color']|escape:'html':'UTF-8'}{/if}"/>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="form-group">

                        <label class="control-label col-lg-3">

                            {l s='Font color' mod='g_cartreminder'}

                        </label>

                        <div class="col-lg-4">

                            <div class="">

                                <div class="input-group">

                                    <input data-hex="true" class="mColorPickerTrigger" type="color" id="fnt_color" name="notificationtab[fnt_color]" value="{if isset($setting_tab['fnt_color']) && $setting_tab['fnt_color']}{$setting_tab['fnt_color']|escape:'html':'UTF-8'}{/if}"/>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="form-group">

                        <label class="control-label col-lg-3" for="delay_tab">

                            {l s='Delay Time (in minutes) ' mod='g_cartreminder'}

                        </label>

                        <div class="col-lg-4">

                            <input class="form-control" type="number" id="delay_tab" name="notificationtab[delay_tab]" value="{if isset($setting_tab['delay_tab']) && $setting_tab['delay_tab']}{$setting_tab['delay_tab']|escape:'html':'UTF-8'}{/if}" />

                        </div>

                        <div class="col-lg-9 col-lg-offset-3">

                            <p class="help-block">

                                {l s='Delay Time description: You can set the delay time to show browser tab notification start count since customer add a product to cart' mod='g_cartreminder'}

                            </p>

                        </div>

                    </div>

                </div>

                <div class="col-md-5">

                </div>

            </div>

            <div class="form-group" id="help_gnotification">

                <section class="help_onesignal panel">

                    <h3>{l s='SETUP ONESIGNAL' mod='g_cartreminder'}</h3>

                    <div class="help_gcart_content">

                        <div class="alert alert-info" role="alert">

                            <i class="material-icons"></i><p class="alert-text">{l s='Send notification you need to configure OneSignal.' mod='g_cartreminder'}</p>

                        </div>

                        <h2>{l s='1. Setting up a notification you need to create an app on the onesignal page.' mod='g_cartreminder'}</h2>

                        <p>{l s='Visit the login site and create the app' mod='g_cartreminder'} <a href="https://onesignal.com" target="_blank">OneSignal</a></p>

                        <img class="img-rounded img-responsive" src="{$g_module_url|escape:'htmlall':'UTF-8'}views/img/OneSignal1.png"/>

                        <p>{l s='After create app, follow instructions to get Keys & IDs' mod='g_cartreminder'}</p>

                        <img class="img-rounded img-responsive" src="{$g_module_url|escape:'htmlall':'UTF-8'}views/img/Onesignal2.png"/>

                        <p>{l s='OneSignal Safari Web ID' mod='g_cartreminder'}</p>

                        <img class="img-rounded img-responsive" src="{$g_module_url|escape:'htmlall':'UTF-8'}views/img/Onesignal3.png"/>

                    </div>

                </section>

            </div>

            <div class="panel-footer">

                <button type="submit" class="btn btn-default pull-right" name="submitOptionsgabandoned_notification"><i class="process-icon-save"></i> {l s='Save' mod='g_cartreminder'}</button>

            </div>

        </div>

    {else}

		{$smarty.block.parent}

	{/if}

{/block}

