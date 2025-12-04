{*
 * 2013-2021 MADEF IT
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http:/opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@madef.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    MADEF IT <contact@madef.fr>
 *  @copyright 2013-2021 MADEF IT
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<div
    class="rm-header"
>
    <div class="rm-logo-container">
        <a class="rm-logo" href="{Context::getContext()->shop->getBaseUrl()|escape:'html':'UTF-8'}" title="{$shop_name|escape:'htmlall':'UTF-8'}" style="background-image: url({$rm_logo_url|escape:'htmlall':'UTF-8'})">
        </a>
    </div>
    <div class="rm-icon-container">
        {if $rm_display_search}
            <span class="search">&#xf002;</span>
        {/if}
        {if $rm_display_lang}
            <span class="rm-icon rm-language" onclick="jQuery(this).toggleClass('opened')" href="{$rm_link->getPageLink($cart_action, true)|escape:'htmlall':'UTF-8'}">&#xf0ac;
                <ul class="rm-language-list">
                    {foreach $rm_language_list as $language}
                        <li class="rm-language-list__item {if $language.id_lang == $rm_current_language_id} rm-language-list__item--current{/if}" onclick="window.location='{$rm_link->getLanguageLink($language.id_lang)}'">{$language.iso_code}</li>
                    {/foreach}
                </ul>
            </span>
        {/if}
        {if $rm_display_cart}
            {if $cart_action == 'cart'}
                <a class="rm-icon rm-cart" href="{$rm_link->getPageLink("cart", true, null, ['action' => 'show'])|escape:'htmlall':'UTF-8'}">&#xf07a;<span {if $cart_count === 0}style="display: none"{/if} class="count">{$cart_count|escape:'htmlall':'UTF-8'}</span></a>
            {else}
                <a class="rm-icon rm-cart" href="{$rm_link->getPageLink($cart_action, true)|escape:'htmlall':'UTF-8'}">&#xf07a;<span {if $cart_count === 0}style="display: none"{/if} class="count">{$cart_count|escape:'htmlall':'UTF-8'}</span></a>
            {/if}
        {/if}
        <a id="rm-trigger" class="js-rm-trigger rm-trigger rm-icon {if $rm_display_trigger_subtitle}rm-trigger--with-subtitle{/if}" href="#" data-subtitle="{l s='MENU' mod='responsivemenu'}"></a>
    </div>
</div>
