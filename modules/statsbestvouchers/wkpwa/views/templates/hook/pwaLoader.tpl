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

<div id="wk-loader"></div>
<div id="wk-site-connection">
    <p id="wk-connection-msg"></p>
</div>

{if $showPwaAppBanner}
    <div id="wk-addToHomeScreen-banner">
        <img src='{$wk_app_img}'>
        <p id="wk-app-banner-msg" style='color: {$WK_PWA_BG_COLOR}'><span>{l s='Add' mod='wkpwa'}</span> <span>{$WK_PWA_SHOT_NAME}</span> <span>{l s='to Home screen' mod='wkpwa'}</span></p>
        <i class="material-icons" id="wk-app-banner-close">close</i>
    </div>
    <button type="button" class="wk-app-button" style="display:none;">{l s='Install' mod='wkpwa'}</button>
{/if}
