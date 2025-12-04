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
{if $hookType == 1}
    <div class="wk-app-btn-wrapper" style="display:inline-block;cursor:pointer;">
        <button type="button" id="wk-app-button-{$hookType}" class="btn btn-primary wk-app-button wkhide">
        {l s='Install App' mod='wkpwa'}
        </button>
    </div>
{elseif $hookType == 2}
    <div class="wk-app-btn-wrapper" style="cursor:pointer;">
        <button type="button" id="wk-app-button-{$hookType}" class="btn btn-primary wk-app-button wkhide">
        {l s='Install App' mod='wkpwa'}
        </button>
    </div>
{else}
    <div class="wk-app-btn-wrapper wk-top-btn wkhide" style="cursor:pointer;">
        <button type="button" class="btn btn-primary wk-app-button wkhide">
            {l s='Install App' mod='wkpwa'}
        </button>
    </div>
{/if}