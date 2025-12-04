{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*}


<script src="https://apis.google.com/js/platform.js" async defer></script>
{if $name == 'popup'}
<style type="text/css">
    {if $popupSetting->customcss != ''}
        {$popupSetting->customcss|escape:'html':'UTF-8'}
    {/if}
</style>
<textarea style="display:none;" id="PPobj">{$JSpopupSetting|escape:'quotes'}</textarea>{* $JSpopupSetting is html content, no need to escape*}
<div class="popup_cart">
    <div class="popup_cart_wrapper">
        <div class="popup_cart-content" style="{if $popupSetting->imgbackground !=''}background-image: url({$g_module_url|escape:'html':'UTF-8'}image/popup/{$popupSetting->imgbackground|escape:'html':'UTF-8'}); background-repeat: no-repeat; background-size: cover; {else}background-color:{$popupSetting->colorbackground|escape:'html':'UTF-8'};{/if} max-width:{$popupSetting->maxwidth|escape:'html':'UTF-8'}px;">
            <div class="popup_cart-content-code">
                {if $gtotalCart < 1 && $demoPP == 1}
                    <div class="popup-header">
                        {l s='Preview mode can’t work because your cart is empty now. Please add something to your cart then reload the page' mod='g_cartreminder'}
                    </div>
                {else}
                    {if isset($version) && $version == 'PS16'}
                        {$popupSetting->html}}{* $popupSetting->html is html content, no need to escape*}
                        {elseif $version == 'PS17'}
                            {$popupSetting->html nofilter}{* $popupSetting->html is html content, no need to escape*}
                    {/if}
                {/if}
                <audio id="gaudio" style="display:none;" src="{$g_url|escape:'htmlall':'UTF-8'}modules/g_cartreminder/views/mp3/musicScode.mp3" autostart="false" ></audio>
                
                <div class="popup_cart-content-close">
                    <button type="button" class="close close_popup" ><svg viewBox="0 0 20 20" focusable="false" aria-hidden="true"><path d="M11.414 10l6.293-6.293a.999.999 0 1 0-1.414-1.414L10 8.586 3.707 2.293a.999.999 0 1 0-1.414 1.414L8.586 10l-6.293 6.293a.999.999 0 1 0 1.414 1.414L10 11.414l6.293 6.293a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L11.414 10z" fill-rule="evenodd"></path></svg></button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
        var g_url    = '{$g_url|escape:'htmlall':'UTF-8'}';
        var gid_cart = '{$gid_cart|escape:'htmlall':'UTF-8'}';
        var gday     = '{$popupSetting->day|escape:'htmlall':'UTF-8'}';
        var ghrs     = '{$popupSetting->hrs|escape:'htmlall':'UTF-8'}';
        var g_token  = '{$gtoken|escape:'htmlall':'UTF-8'}';
</script>
{elseif $name == 'PPbar'}
    <div class="bar_cart_header-bar" style="color:{$PPbar->textcolor|escape:'html':'UTF-8'}; background:{$PPbar->backgroundcolor|escape:'html':'UTF-8'}; {if $PPbar->position != 1} bottom: 0;{else} top: 0;{/if} ">
        <div class="title-bar-center">
            {$PPbartitle|escape:'quotes' nofilter}{* $PPbartitle is html content, no need to escape*}
        </div>
        <ul class="action-button-hihe">
            <li>
                <a id="close-button-bar" class="action_close" href="#">
                <svg viewBox="0 0 20 20" focusable="false" aria-hidden="true"><path d="M11.414 10l6.293-6.293a.999.999 0 1 0-1.414-1.414L10 8.586 3.707 2.293a.999.999 0 1 0-1.414 1.414L8.586 10l-6.293 6.293a.999.999 0 1 0 1.414 1.414L10 11.414l6.293 6.293a.997.997 0 0 0 1.414 0 .999.999 0 0 0 0-1.414L11.414 10z" fill-rule="currentColor"></path></svg>
                </a>
            </li>
        </ul>
    </div>
{/if}