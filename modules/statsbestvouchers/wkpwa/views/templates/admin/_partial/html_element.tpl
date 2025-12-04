{*
* 2010-2021 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through LICENSE.txt file inside our module
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright 2010-2021 Webkul IN
* @license LICENSE.txt
*}

{if isset($element_type) && $element_type == 1}
    <button class="btn btn-default sendPushNotification" type="button" data-id-push-notification='{$idPushNotification}'><i class="icon-fighter-jet"></i><span>{l s='Push Notification' mod='wkpwa'}</span></button>
{/if}
{if isset($element_type) && $element_type == 2}
    <button class="btn btn-default sendPushNotification" type="button" data-id-push-notification='{$idPushNotification}' disabled><i class="icon-fighter-jet"></i><span>{l s='Push Notification' mod='wkpwa'}</span></button>
{/if}
{if isset($element_type) && $element_type == 3}
    <img src='{$imgurl}' style='max-width: 55px;' class='img-thumbnail'>
{/if}
{if isset($element_type) && $element_type == 4}
    <span class="badge badge-success">{$customerOrderTotal}</span>
{/if}
{if isset($element_type) && $element_type == 5}
  <a href="{$adminLinkCustomer}" target="blank">{$customer_name}
            <br>({$customer_email})</a>
{/if}
{if isset($element_type) && $element_type == 6}
{l s='For proper working of this module, first you need to enable SSL on your shop from' mod='wkpwa'} <a href="{$preference_link}"" title="{l s='General Tab' mod='wkpwa'}">{l s='General Tab' mod='wkpwa'}</a>
{/if}
{if isset($element_type) && $element_type == 7}
    <img src='{$logoUrl}' style='max-width: 200px;'>
{/if}
{if isset($element_type) && $element_type == 8}
    <img src='{$faviconUrl}' style='max-width: 32px;'>
{/if}