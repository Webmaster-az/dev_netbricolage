{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*} 
{if $name == 'linkstart'}
    <a href="{$link_shopstart|escape:'htmlall':'UTF-8'}" style="text-decoration: none; font-size: 12px;" target="_blank">
{elseif $name == 'linkend'}
</a>
{elseif $name == 'linkcartstart'}
    <a href="{$link_cartstart|escape:'htmlall':'UTF-8'}" style="text-decoration: none; font-size: 12px;" target="_blank">
{elseif $name == 'facebook'}
    {if $popupSetting->displayss == 1}                                        
    <ul class="menu_link_sosical-views">
        {if $popupSetting->sosicalfb !=''}
            <li>
                <div id="fb-root"></div>
                <div class="fb-like" like_facebook_page="true" data-href="{$popupSetting->sosicalfb|escape:'htmlall':'UTF-8'}" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
            </li>
        {/if}
    {/if}
{elseif $name == 'twitter'}
    {if $popupSetting->displayss == 1}  
        {if $popupSetting->sosicaltw !=''}
            <li style="max-width: 70px;">
    			<iframe allowtransparency="true" frameborder="0" scrolling="no" src="https://platform.twitter.com/widgets/follow_button.html?screen_name={$popupSetting->sosicaltw|escape:'htmlall':'UTF-8'}&show_screen_name=false&show_count=false"></iframe>
    		</li>
        {/if}
    {/if}
{elseif $name == 'google'}
    {if $popupSetting->displayss == 1}  
        {if $popupSetting->sosicalgg !=''}
            <li>
                <div class="g-plusone" data-callback="shere_gplust" data-size="tall" data-annotation="none" href="{$popupSetting->sosicalgg|escape:'htmlall':'UTF-8'}"></div>
            </li>
        {/if}
    </ul>
    {/if}
{elseif $name == 'countdown'}
    <div class="gcartextra-countdown">
        <div class="gcartextra-countdown-warper">
            <div class="gcartextra-countdown-item">
                <div style="display: flex; justify-content: center;">
                    <section class="gcartextra-countdown-item1">
                        <div class="gcartcontent-countdown">
                            <div class="gcartcontent-countdown-tiem" style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 0);">
                                <p>
                                    <span class="gcartcontent-countdown-tiem-minslabel">
                                        00
                                    </span>
                                </p>
                            </div>
                            <div class="gcartcontent-countdown-label">
                                <span>
                                    <span>
                                        {l s='MINS' mod='g_cartreminder'}
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="gcartextra-countdown-demiter">
                            <p>
                                <span> : </span>
                            </p>
                        </div>
                    </section>
                    <section class="gcartextra-countdown-item2">
                        <div class="gcartcontent-countdown">
                            <div class="gcartcontent-countdown-tiem" style="color: rgb(255, 255, 255); background-color: rgb(0, 0, 0);">
                                <p>
                                    <span class="gcartcontent-countdown-tiem-SECSlabel">
                                        00
                                    </span>
                                </p>
                            </div>
                            <div class="gcartcontent-countdown-label">
                                <span>
                                    <span>
                                        {l s='SECS' mod='g_cartreminder'}
                                    </span>
                                </span>
                            </div>
                        </div> 
                    </section>
                </div>
            </div>
        </div>
    </div>
{/if}