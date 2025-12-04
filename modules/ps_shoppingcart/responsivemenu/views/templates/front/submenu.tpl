{*

 * 2013-2021 MADEF IT

 *

 * NOTICE OF LICENSE

 *

 * This source file is subject to the Academic Free License (AFL 3.0)

 * that is bundled with this package in the file LICENSE.txt.

 * It is also available through the world-wide-web at this URL:

 * http://opensource.org/licenses/afl-3.0.php

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



<div class="rm-level js-rm-column js-rm-category-content" id="rm-category-{$category->id|intval}" data-id="{$category->id|intval}">

    {if $is_root_category}

        <ul class="mobile_menutoplinks">

            <li class="rm-login-bar js-rm-column">

                {if Context::getContext()->customer->isLogged() }           

                    <a class="rm-login-bar__login" href="/mon-compte" rel="nofollow" title="{l s='Log in to your customer account' mod='responsivemenu'}">
                        <i style="padding-right:10px;" class="fas fa-user"></i>
                        {Context::getContext()->customer->lastname}
                        {Context::getContext()->customer->firstname}
                    </a>

                {else}

                    <a class="rm-login-bar__login" href="/mon-compte" rel="nofollow" title="{l s='Log in to your customer account' mod='responsivemenu'}">

                        <i style="padding-right:10px;" class="fas fa-user"></i> {l s='Log In' mod='responsivemenu'}

                    </a>

                {/if}

            </li>

        </ul>

    {/if}

    

    {if !$is_root_category}

        <div>

            <a class="rm-level__item rm-level__item--back" data-load="{$category->id|intval}" href="{$category->getLink()|escape:'htmlall':'UTF-8'}">

                <i class="fas fa-arrow-circle-left"></i> {l s='Back' mod='responsivemenu'}

            </a>

        </div>

    {/if}



    <h2>

        <a class="rm-level__title" href="{if $category->is_root_category}{Context::getContext()->shop->getBaseURL()|escape:'html':'UTF-8'}{else}{$category->getLink()|escape:'htmlall':'UTF-8'}{/if}">

            <i class="fas fa-bars"></i> {$category->name[$id_lang]|escape:'htmlall':'UTF-8'}

        </a>

    </h2>



    <ul class="rm-level__container">        

        {assign var="first" value="1"}

        {foreach $additional_links as $links}

            {if ($links.allpage || $is_root_category) && $links.area}

                {assign var="icon_full_path" value="{$rm_img_dir}rm/l{$links.id}.png"}

                <li class=" rm-level__item-container--link rm-level__item-container--link-{$links.id} rm-level__item-container--link-top {if $first}rm-level__item-container--link-top--first{/if}">

                    <a class=" rm-level__item rm-level__item--link rm-level__item--link-{$links.id} rm-level__item--link-top {if $first}rm-level__item--link-top--first{/if} {if file_exists($icon_full_path)}rm-level__item--icon{/if}" href="{$links.url|escape:'htmlall':'UTF-8'}" {if $links.target == '_blank' }data-target="blank"{/if} {if file_exists($icon_full_path)}style="background-image: url({$rm_img_url}l{$links.id}.png)"{/if}>

                        {$links.value|escape:'htmlall':'UTF-8'}

                    </a>

                </li>

                {assign var="first" value="0"}

            {/if}

        {/foreach}

        

        {foreach $subcategories as $key => $subcategory}

            {assign var="icon_full_path" value="{$rm_img_dir}rm/c{$subcategory->id}.png"}

            <li class=" {if $has_subcategories[$subcategory->id] && $subcategory->nright > $subcategory->nleft +1}icon-arrow{/if} rm-level__item-container rm-level__item-container--category rm-level__item-container--category-{$subcategory->id|intval} {if $subcategory@last}rm-level__item-container--category-last{/if} {if $subcategory@first}rm-level__item-container--category-first{/if} ">

                <a class=" rm-level__item rm-level__item--category rm-category-{$subcategory->id|intval} {if file_exists($icon_full_path)}rm-level__item--icon{/if} {if $subcategory@last}rm-level__item--category-last{/if} {if $subcategory@first}rm-level__item--category-first{/if} " {if $has_subcategories[$subcategory->id] && $subcategory->nright > $subcategory->nleft +1} data-load="{$subcategory->id|escape:'htmlall':'UTF-8'}" {/if} href="{$subcategory->getLink()|escape:'htmlall':'UTF-8'}" {if file_exists($icon_full_path)}style="background-image: url({$rm_img_url}c{$subcategory->id}.png)"{/if} >

                {$subcategory->name[$id_lang]|escape:'htmlall':'UTF-8'}</a>

            </li>

        {/foreach}



        {assign var="first" value="1"}
        <h2>
            <a class="rm-level__title" href="{if $category->is_root_category}{Context::getContext()->shop->getBaseURL()|escape:'html':'UTF-8'}{else}{$category->getLink()|escape:'htmlall':'UTF-8'}{/if}">
                {* <i class="fas fa-bars"></i> *} Liens du site
            </a>
        </h2>

        <li class=" rm-level__item-container--link rm-level__item-container--link-{$links.id} rm-level__item-container--link-bottom {if $first}rm-level__item-container--link-bottom--first{/if}">
            <a class="rm-level__item rm-level__item--link rm-level__item--link-1
                    rm-level__item--link-bottom rm-level__item--link-bottom--first" 
                    href="/">

                <i style="padding-right:10px;" class="fas fa-home"></i> Accueil
            </a>
        </li>
        <li class=" rm-level__item-container--link rm-level__item-container--link-{$links.id} rm-level__item-container--link-bottom {if $first}rm-level__item-container--link-bottom--first{/if}">
            <a class="rm-level__item rm-level__item--link rm-level__item--link-1
                    rm-level__item--link-bottom rm-level__item--link-bottom--first" 
                    href="/accueil/">

                <i style="padding-right:10px;" class="fas fa-database"></i> Tous les produits
            </a>
        </li>
        <li class=" rm-level__item-container--link rm-level__item-container--link-{$links.id} rm-level__item-container--link-bottom {if $first}rm-level__item-container--link-bottom--first{/if}">
            <a class="rm-level__item rm-level__item--link rm-level__item--link-1
                    rm-level__item--link-bottom rm-level__item--link-bottom--first" 
                    href="/promotions">

                <i style="padding-right:10px;" class="fas fa-percentage"></i> Promotions
            </a>
        </li>
        
        <li class=" rm-level__item-container--link rm-level__item-container--link-{$links.id} rm-level__item-container--link-bottom {if $first}rm-level__item-container--link-bottom--first{/if}">
            <a class="rm-level__item rm-level__item--link rm-level__item--link-1
                    rm-level__item--link-bottom rm-level__item--link-bottom--first" 
                    href="/nous-contacter">

                <i style="padding-right:10px;" class="fas fa-phone"></i> Contactez-nous
            </a>
        </li>
        <li class=" rm-level__item-container--link rm-level__item-container--link-{$links.id} rm-level__item-container--link-bottom {if $first}rm-level__item-container--link-bottom--first{/if}" style="padding-bottom: 55px;">
            <a class="rm-level__item rm-level__item--link rm-level__item--link-1
                    rm-level__item--link-bottom rm-level__item--link-bottom--first" 
                    href="/historique-commandes">

                <i style="padding-right:10px;" class="fas fa-history"></i> Historique de vos commandes
            </a>
        </li>


        {foreach $additional_links as $links}

            {if ($links.allpage || $is_root_category) && !$links.area}

                {assign var="icon_full_path" value="{$rm_img_dir}rm/l{$links.id}.png"}

                <li

                    class="

                        rm-level__item-container--link

                        rm-level__item-container--link-{$links.id}

                        rm-level__item-container--link-bottom

                        {if $first}rm-level__item-container--link-bottom--first{/if}

                    "

                >

                    <a

                        class="

                            rm-level__item

                            rm-level__item--link

                            rm-level__item--link-{$links.id}

                            rm-level__item--link-bottom

                            {if $first}rm-level__item--link-bottom--first{/if}

                            {if file_exists($icon_full_path)}rm-level__item--icon{/if}

                        "

                        href="{$links.url|escape:'htmlall':'UTF-8'}"

                        {if $links.target == '_blank' }data-target="blank"{/if}

                        {if file_exists($icon_full_path)}style="background-image: url({$rm_img_url}l{$links.id}.png)"{/if}

                    >{$links.value|escape:'htmlall':'UTF-8'}</a>

                </li>

                {assign var="first" value="0"}

            {/if}

        {/foreach}

    </ul>

</div>

