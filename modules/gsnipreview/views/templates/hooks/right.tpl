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
{if $gsnipreviewallinfo_on == 1 && ($gsnipreviewis_home_b_rightcol == 1 || $gsnipreviewis_cat_b_rightcol == 1 || $gsnipreviewis_man_b_rightcol == 1)}

    {if count($gsnipreviewdata_badges)>0}

        <div id="gsnipreview_block_badges_right" class="block blockmanufacturer {if $gsnipreviewis17 == 1}block-categories{/if}" {if isset($gsnipreviewallinfoh_w)}style="width:{$gsnipreviewallinfoh_w|escape:'htmlall':'UTF-8'}%"{/if}>

                <h4 class="title_block">
                    <div class="gsnipreviews-float-left">
                       {l s='Review(s) and rating(s)' mod='gsnipreview'}
                    </div>

                    <div class="gsnipreviews-clear"></div>
                </h4>
                <div class="block_content">

        <div class="badges block-badges" style="width:{$gsnipreviewallinfoh_w|escape:'htmlall':'UTF-8'}%">


		<span itemtype="http://schema.org/Product" itemscope="">
			<meta content="{$gsnipreviewbadges_name|escape:'htmlall':'UTF-8'}" itemprop="name">
            <span itemtype="http://schema.org/AggregateRating" itemscope="" itemprop="aggregateRating">
			<span>
                {section name=ratid loop=5}
                    {if $smarty.section.ratid.index <= $gsnipreviewdata_badges.total_rating|round}
                        <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star" alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                    {else}
                        <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star" alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                    {/if}

                {/section}
			</span>

			<meta content="1" itemprop="worstRating">
			(<span itemprop="ratingValue">{$gsnipreviewdata_badges.total_rating|escape:'htmlall':'UTF-8'}</span>/<span itemprop="bestRating">5</span>)
            <br/><br/>

			<strong>{if $gsnipreviewis_home_b_rightcol != 0}{l s='Shop' mod='gsnipreview'}{/if}
                {if $gsnipreviewis_cat_b_rightcol != 0}{l s='Category' mod='gsnipreview'}{/if}
                {if $gsnipreviewis_man_b_rightcol != 0}{l s='Brand' mod='gsnipreview'}{/if} :</strong>
            <span itemprop="itemReviewed">{$gsnipreviewbadges_name|escape:'htmlall':'UTF-8'}</span> -
            {l s='Based on' mod='gsnipreview'} <span itemprop="ratingCount">{$gsnipreviewdata_badges.total_reviews|escape:'htmlall':'UTF-8'}</span> {l s='rating(s)' mod='gsnipreview'}
            {l s='and' mod='gsnipreview'} <span itemprop="reviewCount">{$gsnipreviewdata_badges.total_reviews|escape:'htmlall':'UTF-8'}</span> {l s='review(s)' mod='gsnipreview'}
			</span>
            </span>
            <br/><br/>
            <a href="{$gsnipreviewrev_all|escape:'htmlall':'UTF-8'}" class="btn btn-default button button-small-gsnipreview badges-block-a"
                              title="{l s='View All Reviews' mod='gsnipreview'}"><span>{l s='View All Reviews' mod='gsnipreview'}</span></a>


        </div>
        </div>

        </div>

    {/if}

{/if}
{/if}


{if $gsnipreviewrvis_on == 1}

    {if $gsnipreviewratings_on == 1 || $gsnipreviewtitle_on == 1 || $gsnipreviewtext_on == 1}


        {if $gsnipreviewis_blocklr == 1 && ($gsnipreviewis_home_rightcol == 1 || $gsnipreviewis_cat_rightcol == 1 || $gsnipreviewis_man_rightcol == 1 || $gsnipreviewis_prod_rightcol == 1 || $gsnipreviewis_oth_rightcol == 1)}





            <div id="gsnipreview_block_right" class="{if $gsnipreviewis17 == 1}block-categories{else}block-last-gsnipreviews{/if} block blockmanufacturer" {if isset($gsnipreviewallinfoh_w)}style="width:{$gsnipreviewallinfoh_w|escape:'htmlall':'UTF-8'}%"{/if}>

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
                <div class="block_content block-items-data">
                    {if count($gsnipreviewreviews_block)>0}


                        {foreach from=$gsnipreviewreviews_block item=review name=myLoop}

                            <div class="items-last-gsnipreviews ">

                                <div class="row-custom">
                                {if $review.product_img}
                                    <div class="img-block-gsnipreview col-xs-4-custom">
                                        <a href="{$review.product_link|escape:'htmlall':'UTF-8'}"
                                           title="{$review.product_name|escape:'htmlall':'UTF-8'}"
                                                >
                                            <img src="{$review.product_img|escape:'htmlall':'UTF-8'}" title="{$review.product_name|escape:'htmlall':'UTF-8'}"
                                                 alt = "{$review.product_name|escape:'htmlall':'UTF-8'}" class="border-image-review img-responsive" />
                                        </a>
                                    </div>
                                {/if}
                                <div class="body-block-gsnipreview col-xs-8-custom {if !$review.product_img}body-block-gsnipreview-100{/if}">
                                    <div class="title-block-last-gsnipreview">




                                        <div class="title-block-r">
                                            <a href="{$review.product_link|escape:'htmlall':'UTF-8'}"
                                               title="{$review.product_name|escape:'htmlall':'UTF-8'}"
                                                    >
                                                {$review.product_name|escape:'htmlall':'UTF-8'}
                                            </a>
                                        </div>


                                        {if $review.is_active == 1}
                                        {if $gsnipreviewratings_on == 1 && $review.rating != 0}
                                            <div class="r-product gsnipreview-block-stars">

                                                <div  class="rating-stars-total-block">
                                                    ({$review.rating|escape:'html':'UTF-8'}/5)
                                                </div>
                                                <div class="r-rating">
                                                    {section name=ratid loop=5}
                                                        {if $smarty.section.ratid.index < $review.rating}
                                                            <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star-block" alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                                                        {else}
                                                            <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star-block" alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                                                        {/if}

                                                    {/section}
                                                </div>
                                            </div>
                                        {/if}
                                        {/if}
                                        <div class="clear-gsnipreview"></div>





                                    </div>

                                </div>
                                </div>
                                <div class="clear-gsnipreview"></div>

                                <div class="row-custom">
                                <div class="body-block-gsnipreview col-xs-12-custom {if !$review.product_img}body-block-gsnipreview-100{/if}">
                                    {if $review.is_active == 1}

                                        {if $gsnipreviewtext_on == 1 && strlen($review.text_review)>0}
                                            <a href="{$review.product_link|escape:'htmlall':'UTF-8'}"
                                               title="{$review.text_review|escape:'quotes':'UTF-8':strip_tags|substr:0:$gsnipreviewblocklr_tr|escape:'htmlall':'UTF-8'}"
                                                    >
                                                {$review.text_review|strip_tags|substr:0:$gsnipreviewblocklr_tr|escape:'htmlall':'UTF-8' nofilter}{if strlen($review.text_review)>$gsnipreviewblocklr_tr}...{/if}
                                            </a>
                                        {/if}

                                    {else}

                                        {l s='The customer has rated the product but has not posted a review, or the review is pending moderation' mod='gsnipreview'}
                                    {/if}

                                    <div class="clear-gsnipreview"></div>
                                    <div class="r-product gsnipreview-block-date">
                                        {l s='By' mod='gsnipreview'}
                                        {if $gsnipreviewis_avatarr == 1 && strlen($review.avatar)>0 && $review.is_show_ava == 1}

                                            <span class="avatar-block-rev">
                                                <img alt="{$review.customer_name|escape:'htmlall':'UTF-8'}"
                                                     src="{$review.avatar|escape:'htmlall':'UTF-8'}">
                                            </span>

                                        {/if}

                                        {if strlen($review.customer_name)>0}
                                            {if $gsnipreviewis_uprof && $review.id_customer > 0 && $review.is_show_ava == 1}<a href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$review.id_customer|escape:'htmlall':'UTF-8'}" title="{$review.customer_name|escape:'htmlall':'UTF-8'}" class="user-link-to-profile">{/if}<strong
                                                    >{$review.customer_name|escape:'htmlall':'UTF-8' nofilter}</strong>{if $gsnipreviewis_uprof && $review.id_customer > 0 && $review.is_show_ava == 1}</a>{/if}
                                        {/if}
                                        {if strlen($review.customer_name)>0}{l s='on' mod='gsnipreview'}{/if}&nbsp;<strong>{$review.time_add|date_format|escape:'html':'UTF-8'}</strong>


                                    </div>
                                    <div class="clear-gsnipreview"></div>
                                    {if $review.is_buy != 0}
                                        <span class="gsnipreview-block-date float-left">
                                            <span class="is_buy_product is_buy_product_block">{l s='Verified Purchase' mod='gsnipreview'}</span>
                                        </span>
                                        <div class="clear-gsnipreview"></div>
                                    {/if}
                                </div>
                                </div>
                            </div>
                        {/foreach}
                        <div class="gsniprev-view-all">
                            <a href="{$gsnipreviewallr_url|escape:'html':'UTF-8'}"
                               class="btn btn-default button button-small-gsnipreview"
                                    >
                                <span>{l s='View All Reviews' mod='gsnipreview'}</span>
                            </a>
                        </div>


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



{$gsnipreviewrightsnippet|escape:'quotes':'UTF-8'}


{if $gsnipreviewpinvis_on == 1 && $gsnipreview_rightColumn == 'rightColumn' && $gsnipreviewid_productpin}
<a href="//www.pinterest.com/pin/create/button/?
		url=http://{$smarty.server.HTTP_HOST|escape:'htmlall':'UTF-8'}{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}
		&media={$product_image|escape:'htmlall':'UTF-8'}
		&description={$meta_description|escape:'htmlall':'UTF-8'}" 
  data-pin-do="buttonPin" data-pin-config="{if $gsnipreviewpinterestbuttons == 'firston'}above{/if}{if $gsnipreviewpinterestbuttons == 'secondon'}beside{/if}{if $gsnipreviewpinterestbuttons == 'threeon'}none{/if}">
  <img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" alt="Pinterest"/>
</a>
{/if}



{if $gsnipreviewis_uprof == 1}
    {if $gsnipreviewradv_right == 1}

        <div id="gsnipreview_block_right_users"  class="block {if $gsnipreviewis16 == 1}blockmanufacturer16{else}blockmanufacturer{/if} {if $gsnipreviewis17 == 1}block-categories{/if}" >
            <h4 class="title_block" {if $gsnipreviewis16 != 1}align="center"{/if}>
                <a href="{$gsnipreviewshoppers_url|escape:'htmlall':'UTF-8'}"
                        >{l s='Users' mod='gsnipreview'}</a>
            </h4>
            <div class="block_content">
                {if count($gsnipreviewcustomers_block)>0}
                    <ul class="users-block-items">
                        {foreach from=$gsnipreviewcustomers_block item=customer name=myLoop}
                            <li>
                                <img src="{$customer.avatar_thumb|escape:'htmlall':'UTF-8'}"
                                     class="user-img-gsnipreview"
                                     title="{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}"
                                     alt = "{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}" />
                                <a href="{$gsnipreviewshopper_url|escape:'htmlall':'UTF-8'}{$customer.id_customer|escape:'htmlall':'UTF-8'}"
                                   title="{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}">
                                    {$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}
                                </a>
                                <div class="clr"></div>
                            </li>
                        {/foreach}
                    </ul>
                {else}
                    <div class="padding-10">
                        {l s='There are not users yet.' mod='gsnipreview'}
                    </div>
                {/if}
                <div class="gsniprev-view-all">
                    <a class="btn btn-default button button-small-gsnipreview" href="{$gsnipreviewshoppers_url|escape:'htmlall':'UTF-8'}" title="{l s='View all users' mod='gsnipreview'}">
                        <span>{l s='View all users' mod='gsnipreview'}</span>
                    </a>
                </div>


            </div>
        </div>

    {/if}
{/if}





{if $gsnipreviewis_storerev == 1}
    {if $gsnipreviewt_right == 1}

        {if ($gsnipreviewis_mobile == 1 && $gsnipreviewmt_right == 1) || (!$gsnipreviewis_mobile == 1 && $gsnipreviewst_right == 1)}

        <div id="testimonials_block_left"
             class="block myaccount {if $gsnipreviewis17 == 1}block-categories{/if} {if $gsnipreviewis_ps15 == 1 && $gsnipreviewis17 == 0}ps15-color-background{/if} {if $gsnipreviewis16 == 1}gsnipreview-block16{/if}">
            <h4 class="title_block {if $gsnipreviewis16 == 1}testimonials-block-h4{/if} {if $gsnipreviewis_ps15 == 0}testimonials-block-14{/if}
		{if $gsnipreviewis_ps15 == 1 && $gsnipreviewis16 == 0}testimonials-block-15{/if}">

                <a href="{$gsnipreviewstorereviews_url|escape:'htmlall':'UTF-8'}"
                        >{l s='Store Reviews' mod='gsnipreview'}&nbsp;(&nbsp;{$gsnipreviewcount_reviewsti|escape:'htmlall':'UTF-8'}&nbsp;)</a>


                {if $gsnipreviewrssontestim == 1}
                    <a class="margin-left-5" href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/rss_testimonials.php" title="{l s='RSS Feed' mod='gsnipreview'}" target="_blank">
                        <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/feed.png" alt="{l s='RSS Feed' mod='gsnipreview'}" />
                    </a>
                {/if}

                <div {if $gsnipreviewt_lefts == 1}itemscope itemtype="http://schema.org/corporation"{/if} class="margin-top-5 total-rating-items-block">

                    {if $gsnipreviewt_lefts == 1}
                        <meta itemprop="name" content="{$gsnipreviewsh_nameti|escape:'htmlall':'UTF-8'}">
                        <meta itemprop="url" content="{$gsnipreviewsh_urlti|escape:'htmlall':'UTF-8'}">
                    {/if}


                    <div {if $gsnipreviewt_lefts == 1}itemtype="http://schema.org/AggregateRating" itemscope="" itemprop="aggregateRating"{/if}>


                        {if $gsnipreviewt_lefts == 1}
                            <meta itemprop="worstRating" content="1">
                            <meta itemprop="ratingCount" content="{$gsnipreviewcount_reviewsti|escape:'htmlall':'UTF-8'}">
                        {/if}


                        {section name=bar loop=5 start=0}
                            {if $smarty.section.bar.index < $gsnipreviewavg_ratingti}
                                <img alt="{$smarty.section.bar.index|escape:'htmlall':'UTF-8'}" src = "{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" />
                            {else}
                                <img alt="{$smarty.section.bar.index|escape:'htmlall':'UTF-8'}" src = "{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" />
                            {/if}
                        {/section}


                        <span {if $gsnipreviewis_ps15 == 0}class="vertical-align-top"{/if}>
                        (<span {if $gsnipreviewt_lefts == 1}itemprop="ratingValue"{/if} {if $gsnipreviewis_ps15 == 0}class="vertical-align-top"{/if}
                                    >{$gsnipreviewavg_decimalti|escape:'htmlall':'UTF-8'}</span>/<span {if $gsnipreviewt_lefts == 1}itemprop="bestRating"{/if} {if $gsnipreviewis_ps15 == 0}class="vertical-align-top"{/if}
                                    >5</span>)
                        </span>

                    </div>
                </div>



            </h4>

            <div class="block_content products-block">
                {if $gsnipreviewcount_all_reviews > 0}

                    {foreach from=$gsnipreviewreviews item=review name=myLoop}
                        <div class="rItem">



                            <div class="ratingBox">
                                <small>{l s='Review By' mod='gsnipreview'} <b>{if $gsnipreviewis_uprof && $review.is_show_ava && $review.id_customer > 0}<a href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$review.id_customer|escape:'htmlall':'UTF-8'}" title="{$review.name|escape:'htmlall':'UTF-8'}" class="user-link-to-profile">{/if}{$review.name|escape:'htmlall':'UTF-8' nofilter}{if $gsnipreviewis_uprof && $review.id_customer > 0}</a>{/if}</b></small>,


                                {if $review.rating != 0}
                                    {*{for $foo=0 to 4}*}
                                    {section name=bar loop=5 start=0}
                                        {if $smarty.section.bar.index < $review.rating}
                                            <img alt="{$smarty.section.bar.index|escape:'htmlall':'UTF-8'}" src = "{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" />
                                        {else}
                                            <img alt="{$smarty.section.bar.index|escape:'htmlall':'UTF-8'}" src = "{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" />
                                        {/if}
                                    {/section}
                                    {*{/for}*}
                                {else}
                                    {*{for $foo=0 to 4}*}
                                    {section name=bar loop=5 start=0}
                                        <img alt="{$smarty.section.bar.index|escape:'htmlall':'UTF-8'}" src = "{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/rating_star2.png" />
                                    {/section}
                                    {*{/for}*}
                                {/if}

                            </div>
                            <div class="clear"></div>
                            <div class="margin-bottom-5">
                                {if $gsnipreviewis_avatar == 1 && $review.is_show_ava}
                                    <div class="float-left {if $gsnipreviewis16 == 1}avatar-block{else}avatar-block15{/if}">
                                        <img
                                                {if strlen($review.avatar)>0}
                                                    src="{$review.avatar|escape:'htmlall':'UTF-8'}"
                                                {else}
                                                    src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/avatar_m.gif"
                                                {/if}
                                                alt="{$review.name|escape:'htmlall':'UTF-8'}"
                                                />
                                    </div>
                                {/if}

                                <div class="{if $gsnipreviewis17 == 0}font-size-11{/if} float-left {if $gsnipreviewis16 == 1}{if $gsnipreviewis_avatar == 1}testimonial-block-text{else}testimonial-block-text-100{/if}{else}{if $gsnipreviewis_avatar == 1 && $review.is_show_ava}testimonial-block-text15{else}testimonial-block-text-100{/if}{/if}">
                                    {$review.message|substr:0:100|escape:'htmlall':'UTF-8' nofilter}
                                    {if strlen($review.message)>100}...{/if}

                                </div>
                                <div class="clear"></div>
                            </div>
                            {if $gsnipreviewis_web == 1}
                                {if strlen($review.web)>0}
                                    <small class="float-right">
                                        <a title="http://{$review.web|escape:'htmlall':'UTF-8'}" rel="nofollow" href="http://{$review.web|escape:'htmlall':'UTF-8'}"
                                           target="_blank" class="testimonials-link-web"
                                                >http://{$review.web|escape:'htmlall':'UTF-8'}</a>
                                    </small>
                                {/if}
                            {/if}
                            <small class="float-left">{$review.date_add|date_format|escape:'htmlall':'UTF-8'}</small>

                            <div class="clear"></div>

                            <span class="float-right">{if $review.is_buy != 0}<span class="is_buy">{l s='Verified Purchase' mod='gsnipreview'}</span>{/if}</span>
                            <div class="clear"></div>
                        </div>
                    {/foreach}
                {else}
                    <div class="rItem no-items-shopreviews">
                        {l s='There are not store reviews yet.' mod='gsnipreview'}
                    </div>
                {/if}



                <div class="submit_testimonal text-align-center">
                    <a title="{l s='See all Store Reviews' mod='gsnipreview'}" class="btn btn-default button button-small-gsnipreview"
                       href="{$gsnipreviewstorereviews_url|escape:'htmlall':'UTF-8'}"><span>{l s='See all Store Reviews' mod='gsnipreview'}</span></a>
                </div>
            </div>

        </div>
    {/if}
    {/if}
{/if}