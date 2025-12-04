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

<div class="clear-gsnipreview"></div>

{if $gsnipreviewrvis_on == 1}

{if $gsnipreviewratings_on == 1 || $gsnipreviewtitle_on == 1 || $gsnipreviewtext_on == 1}

{if $gsnipreviewhooktodisplay == "product_actions"}



    <div class="clear-gsnipreview"></div>

    <div class="{if $gsnipreviewis16 == 1}gsniprev-block-16{else}gsniprev-block{/if}">
        <b class="title-rating-block">
            <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{l s='Total Rating' mod='gsnipreview'}" />&nbsp;{l s='Total Rating' mod='gsnipreview'}</b><span class="ratings-block-punct">:</span>

        <br/><br/>


        {if $gsnipreviewcount_reviews >0 && $gsnipreviewis16_snippet == 1}<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">{/if}
            {if $gsnipreviewcount_reviews >0 && $gsnipreviewis16_snippet == 1}
                <meta content="1" itemprop="worstRating">
                <meta content="{$gsnipreviewcount_reviews|escape:'htmlall':'UTF-8'}" itemprop="ratingCount">
            {/if}

            <div class="rating">{$gsnipreviewavg_rating|escape:'htmlall':'UTF-8'}</div>
            <div class="gsniprev-block-reviews-text">
                <span {if $gsnipreviewcount_reviews >0 && $gsnipreviewis16_snippet == 1}itemprop="ratingValue"{/if}>{$gsnipreviewavg_decimal|escape:'htmlall':'UTF-8'}</span>/<span {if $gsnipreviewcount_reviews >0 && $gsnipreviewis16_snippet == 1}itemprop="bestRating"{/if}>5</span> - <span id="count_review_block" {if $gsnipreviewcount_reviews >0 && $gsnipreviewis16_snippet == 1}itemprop="reviewCount"{/if}>{$gsnipreviewcount_reviews|escape:'htmlall':'UTF-8'}</span> <span id="reviews_text_block">{$gsnipreviewtext_reviews|escape:'htmlall':'UTF-8'}</span>
            </div>
            <div class="clear-gsnipreview"></div>
            {if $gsnipreviewcount_reviews >0 && $gsnipreviewis16_snippet == 1}</div>{/if}
        {*{/if}*}
        <br/>


        {if $gsnipreviewstarratingon == 1}

            <a href="javascript:void(0)" onclick="$('.gsniprev-rating-block').toggle();" class="view-ratings">{l s='View ratings' mod='gsnipreview'}</a>
            <br/>
            <div class="gsniprev-rating-block">
                <table class="gsniprev-rating-block-table">
                    <tr>
                        <td class="gsniprev-rating-block-left">
                            {section name="test" loop=5}
                                <img alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" />
                            {/section}
                        </td>
                        <td class="gsniprev-rating-block-right"><b id="five-blockreview">{$gsnipreviewfive|escape:'htmlall':'UTF-8'}</b></td>
                    </tr>
                    <tr>
                        <td class="gsniprev-rating-block-left">
                            {section name="test" loop=4}
                                <img alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" />
                            {/section}
                            {section name="test" loop=1}
                                <img alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" />
                            {/section}
                        </td>
                        <td class="gsniprev-rating-block-right"><b id="four-blockreview">{$gsnipreviewfour|escape:'htmlall':'UTF-8'}</b></td>
                    </tr>
                    <tr>
                        <td class="gsniprev-rating-block-left">
                            {section name="test" loop=3}
                                <img alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" />
                            {/section}
                            {section name="test" loop=2}
                                <img alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" />
                            {/section}
                        </td>
                        <td class="gsniprev-rating-block-right"><b id="three-blockreview">{$gsnipreviewthree|escape:'htmlall':'UTF-8'}</b></td>
                    </tr>
                    <tr>
                        <td class="gsniprev-rating-block-left">
                            {section name="test" loop=2}
                                <img alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" />
                            {/section}
                            {section name="test" loop=3}
                                <img alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" />
                            {/section}
                        </td>
                        <td class="gsniprev-rating-block-right"><b id="two-blockreview">{$gsnipreviewtwo|escape:'htmlall':'UTF-8'}</b></td>
                    </tr>
                    <tr>
                        <td class="gsniprev-rating-block-left">
                            {section name="test" loop=1}
                                <img alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" />
                            {/section}
                            {section name="test" loop=4}
                                <img alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" />
                            {/section}
                        </td>
                        <td class="gsniprev-rating-block-right"><b id="one-blockreview">{$gsnipreviewone|escape:'htmlall':'UTF-8'}</b></td>
                    </tr>
                </table>
            </div>

            <br/>
        {/if}


        {if $gsnipreviewis_add != 1}
            <a class="btn-gsnipreview btn-primary-gsnipreview" href="#idTab777" id="idTab777-my-click" {if $gsnipreviewis_bug == 1}onclick="$.scrollTo('#idTab777');return false;"{/if}>
        <span>
            <i class="icon-pencil"></i>&nbsp;

            {l s='Add Review' mod='gsnipreview'}

        </span>
            </a>
        {/if}


        <a class="btn-gsnipreview btn-default-gsnipreview" href="#idTab777" {if $gsnipreviewis_bug == 1}onclick="$.scrollTo('#idTab777');return false;"{/if}>
        <span>
            <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" class="title-rating-one-star" alt="{l s='View Reviews' mod='gsnipreview'}"/>
            {l s='View Reviews' mod='gsnipreview'}
        </span>
        </a>




    </div>




{/if}

{/if}

{/if}

{$gsnipreviewproductactions|escape:'htmlall':'UTF-8'}

{if $gsnipreviewpinvis_on == 1 && $gsnipreview_productActions == 'productActions'}
<a href="//www.pinterest.com/pin/create/button/?
		url=http://{$smarty.server.HTTP_HOST|escape:'htmlall':'UTF-8'}{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}
		&media={$product_image|escape:'htmlall':'UTF-8'}
		&description={$meta_description|escape:'htmlall':'UTF-8'}" 
  data-pin-do="buttonPin" data-pin-config="{if $gsnipreviewpinterestbuttons == 'firston'}above{/if}{if $gsnipreviewpinterestbuttons == 'secondon'}beside{/if}{if $gsnipreviewpinterestbuttons == 'threeon'}none{/if}">
  <img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" alt="Pinterest" />
</a>
{/if}