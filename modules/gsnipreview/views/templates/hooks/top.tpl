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

{if $gsnipreviewrvis_on == 1}

{if $gsnipreviewallinfo_on == 1 && $gsnipreviewsvis_on == 1 && ($gsnipreviewis_home_b_top == 1 || $gsnipreviewis_cat_b_top == 1 || $gsnipreviewis_man_b_top == 1)}

{if count($gsnipreviewdata_badges)>0}


    <div class="clear-gsnipreview"></div>
    <div class="badges" style="width:{$gsnipreviewallinfoh_w|escape:'htmlall':'UTF-8'}%">

        <strong class="title-badges {if $gsnipreviewis_rtl == 1}badges-rtl{/if}">{l s='Review(s) and rating(s)' mod='gsnipreview'}</strong>
		<span itemscope itemtype="http://schema.org/Product" {if $gsnipreviewis_rtl == 1}class="badges-rtl"{/if}>
			<meta content="{$gsnipreviewbadges_name|escape:'htmlall':'UTF-8'}" itemprop="name">
            <span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
			<span>
                {section name=ratid loop=5}

                    {if $smarty.section.ratid.index <= $gsnipreviewdata_badges.total_rating|round}
                        <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star" alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                    {else}
                        <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star" alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                    {/if}

                {/section}
			</span>

			<meta content="1" itemprop="worstRating" />
			(<span itemprop="ratingValue">{$gsnipreviewdata_badges.total_rating|escape:'htmlall':'UTF-8'}</span>/<span itemprop="bestRating">5</span>)


			<strong>{if $gsnipreviewis_home_b_top != 0}{l s='Shop' mod='gsnipreview'}{/if}
                    {if $gsnipreviewis_cat_b_top != 0}{l s='Category' mod='gsnipreview'}{/if}
                    {if $gsnipreviewis_man_b_top != 0}{l s='Brand' mod='gsnipreview'}{/if} :</strong>
            <span itemprop="itemReviewed">{$gsnipreviewbadges_name|escape:'htmlall':'UTF-8'}</span> -
            {l s='Based on' mod='gsnipreview'} <span itemprop="ratingCount">{$gsnipreviewdata_badges.total_reviews|escape:'htmlall':'UTF-8'}</span> {l s='rating(s)' mod='gsnipreview'}
            {l s='and' mod='gsnipreview'} <span itemprop="reviewCount">{$gsnipreviewdata_badges.total_reviews|escape:'htmlall':'UTF-8'}</span> {l s='review(s)' mod='gsnipreview'}
		</span>
            </span>

        &nbsp;<span {if $gsnipreviewis_rtl == 1}class="badges-rtl"{/if}>-</span>&nbsp;<a href="{$gsnipreviewrev_all|escape:'htmlall':'UTF-8'}" {if $gsnipreviewis_rtl == 1}class="badges-rtl"{/if}
                          title="{l s='View All Reviews' mod='gsnipreview'}"><span>{l s='View All Reviews' mod='gsnipreview'}</span></a>


    </div>

{/if}

{/if}

{/if}



{if $gsnipreviewrvis_on == 1}

{if $gsnipreviewratings_on == 1 || $gsnipreviewtitle_on == 1 || $gsnipreviewtext_on == 1}


{if $gsnipreviewis_blocklr == 1 && ($gsnipreviewis_home_top == 1 || $gsnipreviewis_cat_top == 1 || $gsnipreviewis_man_top == 1 || $gsnipreviewis_prod_top == 1 || $gsnipreviewis_oth_top == 1)}





<div class="clear-gsnipreview"></div>
<div id="gsnipreview_block_left" class="block-last-gsnipreviews block blockmanufacturer" style="width:{$gsnipreviewblocklr_w|escape:'htmlall':'UTF-8'}%">

    	<h4 class="title_block">
			<div class="gsnipreviews-float-left">
			{l s='Last Product Reviews' mod='gsnipreview'}
			</div>
			<div class="gsnipreviews-float-left margin-left-5">
			{if $gsnipreviewrsson == 1}
				<a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/rss.php" target="_blank" title="{l s='RSS Feed' mod='gsnipreview'}">
					<img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/feed.png" alt="{l s='RSS Feed' mod='gsnipreview'}" />
				</a>
			{/if}
			</div>
			<div class="gsnipreviews-clear"></div>
		</h4>
		<div class="block_content block-items-data row">
			{if count($gsnipreviewreviews_block)>0}


			{foreach from=$gsnipreviewreviews_block item=review name=myLoop}

                <div class="items-last-gsnipreviews ">
                {if $review.product_img}
                    <div class="img-block-gsnipreview col-sm-1">
                        <a href="{$review.product_link|escape:'htmlall':'UTF-8'}"
                           title="{$review.product_name|escape:'htmlall':'UTF-8'}"
                                >
                            <img src="{$review.product_img|escape:'htmlall':'UTF-8'}" title="{$review.product_name|escape:'htmlall':'UTF-8'}"
                                 alt = "{$review.product_name|escape:'htmlall':'UTF-8'}" class="border-image-review img-responsive" />
                        </a>
                    </div>
                {/if}
                <div class="body-block-gsnipreview col-sm-11 {if !$review.product_img}body-block-gsnipreview-100{/if}">
                    <div class="title-block-last-gsnipreview">


                    <div class="r-product">
                        {l s='By' mod='gsnipreview'}
                        {if $gsnipreviewis_avatarr == 1 && strlen($review.avatar)>0 && $review.is_show_ava == 1}

                            <span class="avatar-block-rev">
                                        <img alt="{$review.customer_name|escape:'htmlall':'UTF-8'}"
                                             src="{$review.avatar|escape:'htmlall':'UTF-8'}">
                            </span>

                        {/if}

                        {if strlen($review.customer_name)>0}
                            {if $gsnipreviewis_uprof && $review.id_customer > 0 && $review.is_show_ava == 1}<a href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$review.id_customer|escape:'htmlall':'UTF-8'}" title="{$review.customer_name|escape:'htmlall':'UTF-8'}" class="user-link-to-profile">{/if}<strong
                                    >{$review.customer_name|escape:'htmlall':'UTF-8'}</strong>{if $gsnipreviewis_uprof && $review.id_customer > 0 && $review.is_show_ava == 1}</a>{/if}
                        {/if}
                        {if strlen($review.customer_name)>0}{l s='on' mod='gsnipreview'}{/if}&nbsp;<strong>{$review.time_add|date_format|escape:'htmlall':'UTF-8'}</strong>


                    </div>

                    {if $review.is_active == 1}
                    {if $gsnipreviewratings_on == 1 && $review.rating != 0}
                    <div  class="rating-stars-total-block">
                       ({$review.rating|escape:'htmlall':'UTF-8'}/5)
                    </div>
                    <div class="r-rating">
                                {section name=ratid loop=5}
                                    {if $smarty.section.ratid.index < $review.rating}
                                        <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star" alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                                    {else}
                                        <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star" alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                                    {/if}

                                {/section}
                    </div>
                    {/if}
                     {/if}
                        <div class="clear-gsnipreview"></div>
                        {if $review.is_buy != 0}
                            <span class="gsnipreview-block-date float-left">
                                            <span class="is_buy_product is_buy_product_block">{l s='Verified Purchase' mod='gsnipreview'}</span>
                                        </span>
                            <div class="clear-gsnipreview"></div>
                        {/if}
                    </div>

                    <div class="title-block-r">
                        <a href="{$review.product_link|escape:'htmlall':'UTF-8'}"
                           title="{$review.product_name|escape:'htmlall':'UTF-8'}"
                                >
                            {$review.product_name|escape:'htmlall':'UTF-8'}
                        </a>
                    </div>


                    {if $review.is_active == 1}

                    {if $gsnipreviewtext_on == 1 && strlen($review.text_review)>0}
                                <a href="{$review.product_link|escape:'htmlall':'UTF-8'}"
                                   title="{$review.text_review|escape:'quotes':'UTF-8':strip_tags|substr:0:$gsnipreviewblocklr_tr|escape:'htmlall':'UTF-8'}"
                                   >
                                    {$review.text_review|escape:'quotes':'UTF-8':strip_tags|substr:0:$gsnipreviewblocklr_tr|escape:'htmlall':'UTF-8'}{if strlen($review.text_review)>$gsnipreviewblocklr_tr}...{/if}
                                </a>
                    {/if}

                    {else}

                        {l s='The customer has rated the product but has not posted a review, or the review is pending moderation' mod='gsnipreview'}
	    			{/if}

                </div>
                    <div class="clear-gsnipreview"></div>
                </div>
	    	{/foreach}
                <div class="gsniprev-view-all float-right">
                    <a href="{$gsnipreviewallr_url|escape:'htmlall':'UTF-8'}"
                       class="btn btn-default button button-small-gsnipreview"
                            >
                        <span>{l s='View All Reviews' mod='gsnipreview'}</span>
                    </a>
                </div>
                <div class="clear-gsnipreview"></div>

	    	{else}
	    		<div class="gsniprev-block-noreviews">
					{l s='There are not Product Reviews yet.' mod='gsnipreview'}
				</div>
	    	{/if}
	    </div>
</div>




{/if}

{/if}

{/if}
