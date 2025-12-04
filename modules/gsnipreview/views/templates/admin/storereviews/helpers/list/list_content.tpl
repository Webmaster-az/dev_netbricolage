{*
 *
 * SERG
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 *
 /*
 *
 * @author    SERG
 * @category social_networks
 * @package gsnipreview
 * @copyright Copyright SERG
 * @license   SERG
 *
*}

{extends file="helpers/list/list_content.tpl"}
{block name="td_content"}
    {if isset($params.type_custom) && $params.type_custom == 'is_active'}

        <span id="activeitem{$tr['id']|escape:'htmlall':'UTF-8'}">
                    <span class="label-tooltip" data-original-title="{l s='Click here to activate or deactivate testimonial on your site' mod='gsnipreview'}" data-toggle="tooltip">
                    <a href="javascript:void(0)" onclick="gsnipreview_list_storereviews({$tr['id']|escape:'htmlall':'UTF-8'},'active',{$tr[$key]|escape:'htmlall':'UTF-8'},'testimonial');" style="text-decoration:none">
                        <img src="../modules/gsnipreview/views/img/{if $tr[$key] == 1}ok.gif{else}no_ok.gif{/if}"  />
                    </a>
                </span>
            </span>
    {elseif isset($params.type_custom) && $params.type_custom == 'avatar'}
        <span class="avatar-list">

            {if $tr['id_customer']>0}
                {* for registered customers *}
                {if strlen($tr['avatar_thumb'])>0}
                    <img src="{$params.base_dir_ssl|escape:'htmlall':'UTF-8'}{$params.path_img_cloud|escape:'htmlall':'UTF-8'}{$tr['avatar_thumb']|escape:'htmlall':'UTF-8'}" />
                {else}
                    <img src = "../modules/gsnipreview/views/img/avatar_m.gif" />
                {/if}
                 {* for registered customers *}
             {else}
                {* for guests *}
                {if strlen($tr['avatar'])>0}
                <img src="{$params.base_dir_ssl|escape:'htmlall':'UTF-8'}{$params.path_img_cloud|escape:'htmlall':'UTF-8'}{$tr['avatar']|escape:'htmlall':'UTF-8'}" />
                {else}
                    <img src = "../modules/gsnipreview/views/img/avatar_m.gif" />
            {/if}
                {* for guests *}
            {/if}



        </span>
    {elseif isset($params.type_custom) && $params.type_custom == 'rating'}

        {if $tr['rating'] != 0}
            {for $foo=0 to 4}
                {if $foo < $tr['rating']}
                     <img src = "../modules/gsnipreview/views/img/{$params.activestar|escape:'htmlall':'UTF-8'}" style="width:13px;" />
                {else}
                    <img src = "../modules/gsnipreview/views/img/{$params.noactivestar|escape:'htmlall':'UTF-8'}" style="width:13px;" />
                {/if}

            {/for}

        {else}

            {for $foo=0 to 4}
                <img src = "../modules/gsnipreview/views/img/{$params.noactivestar|escape:'htmlall':'UTF-8'}" style="width:13px;" />
            {/for}
        {/if}
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    {elseif isset($params.type_custom) && $params.type_custom == 'web'}


                    <span class="label-tooltip" data-original-title="{$tr['web']|escape:'htmlall':'UTF-8'}" data-toggle="tooltip">
                    <a href=" {$tr['web']|escape:'htmlall':'UTF-8'}" style="text-decoration:underline">
                        {$tr['web']|escape:'htmlall':'UTF-8'}
                    </a>
                </span>
    {elseif isset($params.type_custom) && $params.type_custom == 'customer_name'}
        {if isset($tr[$key])}
            {if $tr['id_customer']>0 && $params.is_uprof == 1}
                <span class="label-tooltip" data-original-title="{l s='Click here to see customer on your site' mod='gsnipreview'}" data-toggle="tooltip">
                    {*{$params.base_dir_ssl|escape:'htmlall':'UTF-8'}{if $params.is_multilang == 1}{$tr['lang']|escape:'htmlall':'UTF-8'}/{else}{/if}user/{$tr['id_customer']|escape:'htmlall':'UTF-8'}*}
                    <a href="{$params.user_url|escape:'htmlall':'UTF-8'}{$tr['id_customer']|escape:'htmlall':'UTF-8'}"  style="text-decoration:underline" target="_blank">
                        {$tr[$key]|escape:'htmlall':'UTF-8'}
                    </a>
                </span>
            {else}
                <span {if $params.is_uprof}class="label-tooltip" data-original-title="{l s='This is customer is GUEST' mod='gsnipreview'}" data-toggle="tooltip"{/if}>
                    {$tr[$key]|escape:'htmlall':'UTF-8'}
                    </span>
            {/if}
        {/if}
    {else}
        {$smarty.block.parent}
    {/if}


{/block}