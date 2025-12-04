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
{block name="input"}
	{if $field['type'] == 'settingnotification'}
                    </div>
                </div>
            </div>
        </div>
        <div class="gcarttabs-list">
            <ul class="tabs-create nav nav-tabs">
                <li class="active"><a href="#conf_id_1" data-toggle="tab">{l s='Get Abandoned Cart' mod='g_cartreminder'}</a></li>
                <li class=""><a href="#conf_id_2" data-toggle="tab">{l s='Onesignal' mod='g_cartreminder'}</a></li>
                <li class=""><a href="#conf_id_3" data-toggle="tab">{l s='Google tracking id' mod='g_cartreminder'}</a></li>
            </ul>
        </div>
        <div class="panel col-lg-12 gcart-none-borderradius">
            <div class="form-group active" id="conf_id_1">
                <label class="control-label col-lg-3">
                    {l s='Get Abandoned Cart Within Range' mod='g_cartreminder'}
                </label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="controls input-group">
                                <div class="input-group-addon">{l s='Days' mod='g_cartreminder'}</div>
                                <input class="form-control" id="CONFIGGETCARTDAYS" type="number" min="0" name="CONFIGGETCARTDAYS" value="{$CONFIGGETCARTDAYS|escape:'html':'UTF-8'}" size="40" placeholder="0" onkeypress="return isNumberKey(event)"/>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="controls input-group">
                                <div class="input-group-addon">{l s='Hrs' mod='g_cartreminder'}</div>
                                <input class="form-control" id="CONFIGGETCARTHRS" type="number" min="0" name="CONFIGGETCARTHRS" value="{$CONFIGGETCARTHRS|escape:'html':'UTF-8'}" size="40" placeholder="0" onkeypress="return isNumberKey(event)"/>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-default" onclick="Savetimegetcart();">{l s='Save' mod='g_cartreminder'}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group" id="conf_id_2">
                <div class="form-group">
                    <div class="col-lg-3">
                        <label class="control-label">
                            {l s='Enable Browser Notification' mod='g_cartreminder'}
                        </label>
                    </div>
                    <div class="col-lg-6">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="notification[notification_off]" id="notification_on" value="1" {if isset($notification['notification_off']) && $notification['notification_off'] == 1}checked="checked"{/if}>
                            <label for="notification_on">{l s='Yes' mod='g_cartreminder'}</label>
                            <input type="radio" name="notification[notification_off]" id="notification_off" value="0" {if  !isset($notification['notification_off']) || !$notification['notification_off']}checked="checked"{/if}>
                            <label for="notification_off">{l s='No' mod='g_cartreminder'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-3">
                        <label class="control-label">
                                {l s='Enable Browser Tab Notification' mod='g_cartreminder'}
                        </label>
                    </div>
                    <div class="col-lg-6">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="notificationtab[tabs_for]" id="tabs_for_on" value="1" {if isset($notificationtab['tabs_for']) && $notificationtab['tabs_for'] == 1}checked="checked"{/if}>
                            <label for="tabs_for_on">{l s='Yes' mod='g_cartreminder'}</label>
                            <input type="radio" name="notificationtab[tabs_for]" id="tabs_for_off" value="0" {if !isset($notificationtab['tabs_for']) || !$notificationtab['tabs_for']}checked="checked"{/if}>
                            <label for="tabs_for_off">{l s='No' mod='g_cartreminder'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label class="control-label required" for="apponesignal_id">
                            {l s='OneSignal App ID' mod='g_cartreminder'}
                        </label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" type="text" id="apponesignal_id" name="notification[apponesignal_id]" value="{if isset($notification['apponesignal_id'])}{$notification['apponesignal_id']|escape:'html':'UTF-8'}{/if}" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label class="control-label required" for="apponesignal_api_id">
                            {l s='OneSignal REST API Key' mod='g_cartreminder'}
                        </label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" type="text" id="apponesignal_api_id" name="notification[apponesignal_api_id]" value="{if isset($notification['apponesignal_api_id'])}{$notification['apponesignal_api_id']|escape:'html':'UTF-8'}{/if}" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label class="control-label" for="apponesignal_safari_id">
                            {l s='OneSignal Safari Web ID' mod='g_cartreminder'}
                        </label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control" type="text" id="apponesignal_safari_id" name="notification[apponesignal_safari_id]" value="{if isset($notification['apponesignal_safari_id'])}{$notification['apponesignal_safari_id']|escape:'html':'UTF-8'}{/if}" />
                    </div>
                </div>
            </div>
            <div class="form-group" id="conf_id_3">
                <label class="control-label col-lg-3">
                    {l s='Google Tracking Id' mod='g_cartreminder'}
                </label>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-9">
                            <input class="form-control" type="text" name="GC_EMAIL_TRACKING_ID" value="{$GC_EMAIL_TRACKING_ID|escape:'html':'UTF-8'}" />
                            <p class="help-block">
                                {l s='Tracking your email reminder by Google Analytic. Client Id: 501, Event: Open on Email abandoned cart reminder 5 in 1.' mod='g_cartreminder'}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <button type="submit" class="btn btn-default pull-right" name="submitOptionsconfiguration"><i class="process-icon-save"></i> {l s='Save' mod='g_cartreminder'}</button>
            </div>
        </div>
    {else}
		{$smarty.block.parent}
	{/if}
{/block}
