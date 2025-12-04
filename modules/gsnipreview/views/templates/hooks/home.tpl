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
{if $gsnipreviewallinfo_on == 1 && $gsnipreviewis_home_b_home == 1 && $gsnipreviewsvis_on == 1}

    {if count($gsnipreviewdata_badges)>0}


        <div class="clear-gsnipreview"></div>
        <div class="badges" style="width:{$gsnipreviewallinfoh_w|escape:'htmlall':'UTF-8'}%">

            <strong class="title-badges">{l s='Review(s) and rating(s)' mod='gsnipreview'}</strong>
		<span itemscope itemtype="http://schema.org/Product">
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


			<strong>{if $gsnipreviewis_home_b_home != 0}{l s='Shop' mod='gsnipreview'}{/if}
                {if $gsnipreviewis_cat_b_home != 0}{l s='Category' mod='gsnipreview'}{/if}
                {if $gsnipreviewis_man_b_home != 0}{l s='Brand' mod='gsnipreview'}{/if} :</strong>
             <span itemprop="itemReviewed">{$gsnipreviewbadges_name|escape:'htmlall':'UTF-8'}</span> -
                {l s='Based on' mod='gsnipreview'} <span itemprop="ratingCount">{$gsnipreviewdata_badges.total_reviews|escape:'htmlall':'UTF-8'}</span> {l s='rating(s)' mod='gsnipreview'}
                {l s='and' mod='gsnipreview'} <span itemprop="reviewCount">{$gsnipreviewdata_badges.total_reviews|escape:'htmlall':'UTF-8'}</span> {l s='review(s)' mod='gsnipreview'}
		</span>
            </span>

            &nbsp; - &nbsp;<a href="{$gsnipreviewrev_all|escape:'htmlall':'UTF-8'}"
                              title="{l s='View All Reviews' mod='gsnipreview'}"><span>{l s='View All Reviews' mod='gsnipreview'}</span></a>



        </div>

    {/if}

{/if}
{/if}


{if $gsnipreviewrvis_on == 1}

{if $gsnipreviewratings_on == 1 || $gsnipreviewtitle_on == 1 || $gsnipreviewtext_on == 1}

{if $gsnipreviewis_blocklr == 1 && $gsnipreviewblocklr_home == "blocklr_home" && $gsnipreviewblocklr_home_pos == "home"}



    <div class="clear-gsnipreview"></div>
<div {if $gsnipreviewis_ps15 == 1 && $gsnipreviewis17 == 0}id="left_column"{/if}>


<div id="gsnipreview_block_left" class="block-last-gsnipreviews block blockmanufacturer {if $gsnipreviewis17 == 1}block-categories{/if}" style="width:{$gsnipreviewblocklr_home_w|escape:'htmlall':'UTF-8'}%">

    	<h4 class="title_block {if $gsnipreviewis17 == 1}text-uppercase h6{/if}">
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
		<div class="block_content block-items-data row-custom">
			{if count($gsnipreviewreviews_home)>0}


			{foreach from=$gsnipreviewreviews_home item=review name=myLoop}

                <div class="items-last-gsnipreviews ">
                {if $review.product_img}
                    <div class="img-block-gsnipreview col-sm-2-custom">
                        <a href="{$review.product_link|escape:'htmlall':'UTF-8'}"
                           title="{$review.product_name|escape:'htmlall':'UTF-8'}"
                                >
                            <img src="{$review.product_img|escape:'htmlall':'UTF-8'}" title="{$review.product_name|escape:'htmlall':'UTF-8'}"
                                 alt = "{$review.product_name|escape:'htmlall':'UTF-8'}" class="border-image-review img-responsive" />
                        </a>
                    </div>
                {/if}
                <div class="body-block-gsnipreview col-sm-10-custom {if !$review.product_img}body-block-gsnipreview-100{/if}">
                    <div class="title-block-last-gsnipreview">


                    <div class="r-product">
                        {l s='By' mod='gsnipreview'}
                        {if $gsnipreviewis_avatarr == 1 && strlen($review.avatar)>0 && $review.is_show_ava == 1}

                            <span class="avatar-block-rev">
                                        <img alt="{$review.customer_name|escape:'htmlall':'UTF-8' nofilter}"
                                             src="{$review.avatar|escape:'htmlall':'UTF-8'}">
                            </span>

                        {/if}

                        {if strlen($review.customer_name)>0}
                            {if $gsnipreviewis_uprof && $review.id_customer > 0 && $review.is_show_ava == 1}<a href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$review.id_customer|escape:'htmlall':'UTF-8'}" title="{$review.customer_name|escape:'htmlall':'UTF-8'}" class="user-link-to-profile">{/if}<strong
                            >{$review.customer_name|escape:'htmlall':'UTF-8' nofilter}</strong>{if $gsnipreviewis_uprof && $review.id_customer > 0 && $review.is_show_ava == 1}</a>{/if}
                        {/if}
                        {if strlen($review.customer_name)>0}{l s='on' mod='gsnipreview'}{/if}&nbsp;<strong>{$review.time_add|date_format|escape:'html':'UTF-8'}</strong>


                    </div>

                        {if $review.is_active == 1}
                            {if $gsnipreviewratings_on == 1 && $review.rating != 0}
                            <div  class="rating-stars-total-block">
                               ({$review.rating|escape:'html':'UTF-8'}/5)
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
                                   title="{$review.text_review|escape:'quotes':'UTF-8':strip_tags|substr:0:$gsnipreviewblocklr_home_tr|escape:'quotes':'UTF-8'}"
                                   >
                                    {$review.text_review|strip_tags|substr:0:$gsnipreviewblocklr_home_tr|escape:'htmlall':'UTF-8' nofilter}{if strlen($review.text_review)>$gsnipreviewblocklr_home_tr}...{/if}
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
                    <a href="{$gsnipreviewallr_url|escape:'html':'UTF-8'}"
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


</div>

{/if}

{/if}

{/if}



{if $gsnipreviewis17 == 1}<br/>{/if}
{if $gsnipreviewis_uprof == 1}
{if $gsnipreviewradv_home == 1}

{if $gsnipreviewis16 == 1 && $gsnipreviewis17 == 0}
    <div id="left_column">
{/if}

    <div id="gsnipreview_block_home_users" class="block {if $gsnipreviewis16 == 1}blockmanufacturer16{else}blockmanufacturer{/if} {if $gsnipreviewis17 == 1}block-categories{/if}">
        <h4  class="title_block {if $gsnipreviewis17 == 1}text-uppercase h6{/if}" {if $gsnipreviewis16 != 1}align="center"{/if}>
            <a href="{$gsnipreviewshoppers_url|escape:'htmlall':'UTF-8'}"
                    >{l s='Users' mod='gsnipreview'}</a>
        </h4>
        <div class="block_content">
            {if count($gsnipreviewcustomers_block)>0}
                <ul class="users-block-items home-shoppers">
                    {foreach from=$gsnipreviewcustomers_block item=customer name=myLoop}
                        <li class="float-left border-bottom-none">
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
                <div class="clr"></div>

                <div class="gsniprev-view-all float-right">
                    <a href="{$gsnipreviewshoppers_url|escape:'html':'UTF-8'}"
                       class="btn btn-default button button-small-gsnipreview"
                            >
                        <span>{l s='View All Users' mod='gsnipreview'}</span>
                    </a>
                </div>
                <div class="clear-gsnipreview"></div>
            {else}
                <div class="padding-10">
                    {l s='There are not users yet.' mod='gsnipreview'}
                </div>
            {/if}

        </div>
    </div>

    {if $gsnipreviewis16 == 1 && $gsnipreviewis17 == 0}
        </div>
    {/if}

{/if}
{/if}




{if $gsnipreviewis_storerev == 1}
{if $gsnipreviewt_home == 1}
    {if ($gsnipreviewis_mobile == 1 && $gsnipreviewmt_home == 1) || (!$gsnipreviewis_mobile == 1 && $gsnipreviewst_home == 1)}
    <div {if $gsnipreviewis_ps15 == 1 && $gsnipreviewis17 == 0}id="left_column"{/if}>
        <div id="testimonials_block_left" class="block myaccount {if $gsnipreviewis16 == 1}gsnipreview-block16{/if} margin-top-10 {if $gsnipreviewis17 == 1}block-categories{else}ps15-color-background{/if}">
            <h4 class="title_block {if $gsnipreviewis16 == 1}testimonials-block-h4{/if}
		{if $gsnipreviewis_ps15 == 1 && $gsnipreviewis16 == 0}testimonials-block-15{/if} {if $gsnipreviewis17 == 1}text-uppercase h6{/if}">
                <div class="float-left">
                    <a href="{$gsnipreviewstorereviews_url|escape:'htmlall':'UTF-8'}"
                            >{l s='Store Reviews' mod='gsnipreview'}&nbsp;(&nbsp;{$gsnipreviewcount_reviewsti|escape:'htmlall':'UTF-8'}&nbsp;)</a>

                </div>


                <div {if $gsnipreviewt_homes == 1}itemscope itemtype="http://schema.org/corporation"{/if} class="total-rating-items-block float-left margin-left-5 {if $gsnipreviewis16 == 1}margin-top-3{/if}">

                    {if $gsnipreviewt_homes == 1}
                        <meta itemprop="name" content="{$gsnipreviewsh_nameti|escape:'htmlall':'UTF-8'}">
                        <meta itemprop="url" content="{$gsnipreviewsh_urlti|escape:'htmlall':'UTF-8'}">
                    {/if}


                    <div {if $gsnipreviewt_homes == 1}itemtype="http://schema.org/AggregateRating" itemscope="" itemprop="aggregateRating"{/if}>

                        {if $gsnipreviewt_homes == 1}
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
                        (<span {if $gsnipreviewt_homes == 1}itemprop="ratingValue"{/if} {if $gsnipreviewis_ps15 == 0}class="vertical-align-top"{/if}
                                    >{$gsnipreviewavg_decimalti|escape:'htmlall':'UTF-8'}</span>/<span {if $gsnipreviewt_homes == 1}itemprop="bestRating"{/if} {if $gsnipreviewis_ps15 == 0}class="vertical-align-top"{/if}
                                    >5</span>)
                        </span>

                    </div>
                </div>

                <div class="float-left margin-left-5">
                    {if $gsnipreviewrssontestim == 1}
                        <a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/rss_testimonials.php" title="{l s='RSS Feed' mod='gsnipreview'}" target="_blank">
                            <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/feed.png" alt="{l s='RSS Feed' mod='gsnipreview'}" />
                        </a>
                    {/if}
                </div>
                <div class="clear"></div>

            </h4>

            <div class="block_content products-block">
                {if $gsnipreviewcount_all_reviews > 0}

                    {foreach from=$gsnipreviewreviews item=review name=myLoop}
                        <div class="rItem testimonials-width-auto" >
                            <div class="ratingBox text-align-right">
                                <small>{l s='Review By' mod='gsnipreview'} <b>{if $gsnipreviewis_uprof && $review.is_show_ava && $review.id_customer > 0}<a href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$review.id_customer|escape:'htmlall':'UTF-8'}" title="{$review.name|escape:'htmlall':'UTF-8'}" class="user-link-to-profile">{/if}{$review.name|escape:'htmlall':'UTF-8' nofilter}{if $gsnipreviewis_uprof && $review.id_customer > 0}</a>{/if}</b></small>{if $gsnipreviewis_country == 1}{if strlen($review.country)>0}, <span class="fs-12">{$review.country|escape:'htmlall':'UTF-8' nofilter}</span>{/if}{/if}{if $gsnipreviewis_city == 1}{if strlen($review.city)>0}, <span class="fs-12">{$review.city|escape:'htmlall':'UTF-8' nofilter}</span>{/if}{/if}


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
                            <div>
                                {if $gsnipreviewis_avatar == 1 && $review.is_show_ava}
                                    <div class="float-left avatar-block-home">
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

                                <div class="{if $gsnipreviewis17 == 0}font-size-11{/if} float-left testimonial-block-text-home">
                                    {$review.message|substr:0:245|escape:'htmlall':'UTF-8' nofilter}
                                    {if strlen($review.message)>245}...{/if}

                                </div>
                                <div class="clear"></div>
                            </div>


                            <small class="float-right">{$review.date_add|date_format|escape:'htmlall':'UTF-8'}</small>


                            {if $gsnipreviewis_web == 1}
                                {if strlen($review.web)>0}
                                    <small class="float-right margin-right-10">
                                        <a title="http://{$review.web|escape:'htmlall':'UTF-8'}" rel="nofollow" href="http://{$review.web|escape:'htmlall':'UTF-8'}"
                                           target="_blank" class="testimonials-link-web"
                                                >http://{$review.web|escape:'htmlall':'UTF-8'}</a>
                                    </small>
                                {/if}
                            {/if}
                            <div class="clear"></div>
                            <span class="float-right">{if $review.is_buy != 0}<span class="is_buy">{l s='Verified Purchase' mod='gsnipreview'}</span>{/if}</span>
                            <div class="clear"></div>

                        </div>
                    {/foreach}
                    <div class="gsniprev-view-all float-right">
                        <a href="{$gsnipreviewstorereviews_url|escape:'html':'UTF-8'}"
                           class="btn btn-default button button-small-gsnipreview"
                                >
                            <span>{l s='View All Store Reviews' mod='gsnipreview'}</span>
                        </a>
                    </div>
                    <div class="clear-gsnipreview"></div>
                {else}
                    <div class="rItem no-items-shopreviews testimonials-width-auto" >
                        {l s='There are not Store Reviews yet.' mod='gsnipreview'}
                    </div>
                {/if}




            </div>

        </div>

    </div>
{/if}
{/if}
{/if}
