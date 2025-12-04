{*
/**
 * mitrocops
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 /*
 *
 * @author    mitrocops
 * @category seo
 * @package gsnipreview
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */

*}

{literal}
<script type="text/javascript">
//<![CDATA[
	var baseDir = '{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{literal}';
//]]>
</script>
{/literal}


{if $gsnipreviewis16 == 1}

    {if $gsnipreviewis17 == 1}
        <h2>{l s='All Users' mod='gsnipreview'}</h2>
    {else}
        {capture name=path}
            {l s='All Users' mod='gsnipreview'}
        {/capture}
    {/if}

{else}

    {$meta_title|escape:'htmlall':'UTF-8'}
{/if}




<div class="b-inside-pages {if $gsnipreviewis17 == 1}block-categories{/if}">

<div id="top" class="b-column-c">
				
				<div class="b-wrapper">
					
					<div class="b-tab {if $gsnipreviewis16 == 1}b-tab-16-profile-page{/if}">
						<ul>
							<li class="current"><a href="#">{l s='Users' mod='gsnipreview'} ({$gsnipreviewdata_count_customers|escape:'htmlall':'UTF-8'})</a></li>
						</ul>					
					</div>
					
					<div class="b-search-friends">



                        <form method="get" action="{$gsnipreviewusers_url|escape:'htmlall':'UTF-8'}">

                            <fieldset>
                                <input type="submit" value="go" class="button_mini_custom {if $gsnipreviewis_ps15 == 0}search_go{/if}">
                                <input type="text" class="txt {if $gsnipreviewis16 == 0}search-input-height-15{/if}" name="search"
                                       onfocus="{literal}if(this.value == '{/literal}{l s='Find in Users List' mod='gsnipreview'}{literal}') {this.value='';};{/literal}" onblur="{literal}if(this.value == '') {this.value='{/literal}{l s='Find in Users List' mod='gsnipreview'}{literal}';};{/literal}"
                                       onblur="{literal}if(this.value == '{/literal}{l s='Find in Users List' mod='gsnipreview'}{literal}') {this.value='';};{/literal}" onblur="{literal}if(this.value == '') {this.value='{/literal}{l s='Find in Users List' mod='gsnipreview'}{literal}';};{/literal}"
                                       value="{l s='Find in Users List' mod='gsnipreview'}" />
                                {if $gsnipreviewis_search == 1}
                                    <a href="{$gsnipreviewusers_url|escape:'htmlall':'UTF-8'}" class="clear-search-items">
                                        {l s='Clear search' mod='gsnipreview'}
                                    </a>
                                {/if}

                            </fieldset>
                        </form>

					</div>

                    {if $gsnipreviewis_search == 1}
                        <h3 class="search-result-item-user">{l s='Results for' mod='gsnipreview'} <b>"{$gsnipreviewsearch|escape:'quotes':'UTF-8'}"</b></h3>
                        <br/>
                    {/if}
					
					<div class="b-friends-list">
					{if count($gsnipreviewcustomers)>0}
					<ul>
					{foreach from=$gsnipreviewcustomers item=customer name=myLoop}

							<li class="img-list-user">
								<a href="{$gsnipreviewshopper_url|escape:'htmlall':'UTF-8'}{$customer.id_customer|escape:'htmlall':'UTF-8'}"
								   title="{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}">
									<img height="75" width="75" alt="{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}"
										 title="{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}"
										 src="{$customer.avatar_thumb|escape:'htmlall':'UTF-8'}"
										 {if $customer.exist_avatar == 0}class="profile-adv-user-img"{/if}>
								</a>
								<div class="b-name">
									<a href="{$gsnipreviewshopper_url|escape:'htmlall':'UTF-8'}{$customer.id_customer|escape:'htmlall':'UTF-8'}"
									   title="{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}"
									   >{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}</a>
								</div>

								{if $customer.country}
                                <div class="b-from">
									{$customer.country|escape:'htmlall':'UTF-8'}
                                </div>
								{/if}

							</li>
							
					
					{if $smarty.foreach.myLoop.last}
						<div class="clr"><!-- --></div>
					{/if}
					{/foreach}
					</ul>
					{else}
						<div style="text-align:center;padding: 0px 20px 20px 20px;">
							{l s='Users not found' mod='gsnipreview'}
						</div>
					{/if}	
						
					</div>

                    {if count($gsnipreviewcustomers)>0}
                    <div class="paging-users-custom">
                        {*{$gsnipreviewpaging}*}
                        <div class="pages">
                            <span>{$gsnipreviewpage_text|escape:'htmlall':'UTF-8'}:</span>
                                <span class="nums">
                                    {foreach $gsnipreviewpaging as $page_item}
                                        {if $page_item.is_b == 1}
                                            <b>{$page_item.page|escape:'htmlall':'UTF-8'}</b>
                                        {else}
                                            <a href="{$page_item.url|escape:'quotes':'UTF-8'}" title="{$page_item.title|escape:'htmlall':'UTF-8'}">{$page_item.page|escape:'htmlall':'UTF-8'}</a>
                                        {/if}
                                    {/foreach}
                                </span>
                        </div>
                    </div>
                    {/if}


				</div>
				

			</div>
			
</div>

