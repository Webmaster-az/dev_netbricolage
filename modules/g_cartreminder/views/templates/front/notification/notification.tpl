{*
* Do not edit the file if you want to upgrade the module in future.
*
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}

<input type="hidden" class="g_ndatecart" value="{$g_ndatecart|escape:'htmlall':'UTF-8'}" />
<input type="hidden" class="g_ndatenow" value="{$g_timenow|escape:'htmlall':'UTF-8'}" />
<input type="hidden" class="g_delay_notification" value="{$g_delay_notification|intval}" />
<input type="hidden" class="g_icon_notification" value="{$g_icon_notification|escape:'htmlall':'UTF-8'}" />
<input type="hidden" class="totalproduct_cart" value="{$CartTotal|escape:'htmlall':'UTF-8'}"/>

{if !empty($objnotifications)}
    {if !empty($objnotifications->setting_notification)}
        {assign var=settings value=$objnotifications->setting_notification|json_decode:true}
        {if $settings['notification_off'] == 1}
            <link rel="manifest" href="{$g_module_url|escape:'html':'UTF-8'}manifest.json" />
            <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async='async'></script>
            <script>
                var OneSignal = window.OneSignal || [];
                OneSignal.push(["init", {
                    appId: "{$settings['apponesignal_id']|escape:'html':'UTF-8'}",
                    safari_web_id: "{$settings['apponesignal_safari_id']|escape:'html':'UTF-8'}",
                    autoRegister: true,
                    httpPermissionRequest: {
                        enable: true
                    },
                    notifyButton: {
                        enable: false
                    },
                    welcomeNotification: {
                        "title": "{l s='Hi,' mod='g_cartreminder'}",
                        "message": "{l s='Thanks for subscribing!' mod='g_cartreminder'}",
                    },
                    promptOptions: {
                        actionMessage: "{l s='We’d like to send reminder you the products in your cart that you can forget about.' mod='g_cartreminder'}",
                        acceptButtonText: "{l s='ALLOW' mod='g_cartreminder'}",
                        cancelButtonText: "{l s='NO THANKS' mod='g_cartreminder'}"
                    }
                }]);
                OneSignal.push(function() {
                    OneSignal.isPushNotificationsEnabled(function(isEnabled) {
                        if (!isEnabled) {
                            OneSignal.showHttpPrompt();
                        }
                    });
                });
        </script>
        {if $CartTotal >0}
        <script>
            if (document.readyState === 'complete') {
                OneSignal.sendTag("id_cart", {$id_cart|intval});
            } else {
                addEventListener("load", function(event){
                    OneSignal.sendTag("id_cart", {$id_cart|intval});
                });
            }
        </script>
        {/if}

    {/if}

    {if $TabEnable==1}
        {assign var=tabsettings value=$objnotifications->setting_tab|json_decode:true}
        <div class="ghide"  style="display: none;">
            <input type="text" class="g_tab_show" value="{if isset($tabsettings['tabs_for'])}{$tabsettings['tabs_for']|escape:'html':'UTF-8'}{/if}" />
            <input type="text" class="g_tab_message" value="{$message_tab|escape:'html':'UTF-8'}" />
            <input type="text" class="g_tab_dalay" value="{if $tabsettings['delay_tab'] == ''}0{else}{$tabsettings['delay_tab']|escape:'html':'UTF-8'}{/if}" />
            <input type="text" class="g_tab_bg_color" value="{$tabsettings['bg_color']|escape:'html':'UTF-8'}" />
            <input type="text" class="g_tab_fnt_color" value="{$tabsettings['fnt_color']|escape:'html':'UTF-8'}" />
        </div>
    {/if}
{/if}
{/if}
