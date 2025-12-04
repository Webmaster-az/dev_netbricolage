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

{foreach from=$gsnipreviewcustomer item=customer name=myLoop}

{if $gsnipreviewis16 == 0}



    <a href="{$gsnipreviewshoppers_url|escape:'htmlall':'UTF-8'}">{l s='Shoppers' mod='gsnipreview'}</a>
    <span class="navigation-pipe">></span>
    {$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}

{else}

    {if $gsnipreviewis17 == 1}
        <a href="{$gsnipreviewshoppers_url|escape:'htmlall':'UTF-8'}">{l s='Users' mod='gsnipreview'}</a>
        <span class="navigation-pipe">></span>
        {$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}
    {else}
    {capture name=path}
<a href="{$gsnipreviewshoppers_url|escape:'htmlall':'UTF-8'}">{l s='Users' mod='gsnipreview'}</a>
	<span class="navigation-pipe">></span>
	{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}
    {/capture}
    {/if}

{/if}




<div class="b-product-item {if $gsnipreviewis17 == 1}block-categories{/if}">
					<div class="b-photo">
						<div class="block-photo">
							<img title="{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}" alt="{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}"
								 src="{$customer.avatar_thumb|escape:'htmlall':'UTF-8'}"
								 {if $customer.exist_avatar == 0}class="photo"{else}class="border-none"{/if}>
							<div class="data">
								<div>
									<strong>{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}</strong>
								</div>
								<div class="margin-top-5">
									<i>
									   {if strlen($customer.gender_txt)>0}{$customer.gender_txt|escape:'htmlall':'UTF-8'}{/if}
									   {if $customer.stats.age != "--"}
									   {if strlen($customer.stats.age)>0}{$customer.stats.age|escape:'htmlall':'UTF-8'} {l s='years' mod='gsnipreview'}{/if}
									   {/if}
									</i>
								</div>
							</div>
						</div>
					</div>
					
					
					
	<div class="b-description">
					
		<h1 class="fn">{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}</h1>
					

		<div class="data-of-item">
            <br/>
			<b>{l s='Registration date:' mod='gsnipreview'}</b> {$customer.date_add|escape:'htmlall':'UTF-8'}<br/><br/>
			<b>{l s='Last visit:' mod='gsnipreview'}</b> {$customer.stats.last_visit|escape:'htmlall':'UTF-8'}
		</div>


	</div>
				
<div class="clr"><!-- --></div>

<!--  tab -->
<div class="b-tab b-tab-16-profile-page" id="tabs-custom">
	<ul>
		<li class="current">
			<a href="javascript:void(0)" data="gsnipreviewprofile">
			   	{l s='Profile' mod='gsnipreview'}</a>
		</li>
        {if $gsnipreviewrvis_on == 1}
        <li>
            <a href="javascript:void(0)" data="gsnipreviewreviews">
                {l s='Product Reviews' mod='gsnipreview'} ({$gsnipreviewcount_reviews|escape:'htmlall':'UTF-8'})</a>
        </li>
        {/if}
        {if $gsnipreviewis_storerev == 1}
        <li>
            <a href="javascript:void(0)" data="gsnipreviewstorereviews">
                {l s='Store Reviews' mod='gsnipreview'} ({$gsnipreviewcount_reviewsti|escape:'htmlall':'UTF-8'})</a>
        </li>
        {/if}
		
	</ul>
</div>

<!--  end tab  -->
				
<div id="tabs-custom-content">

<div class="b-tab-wrapper current-tab-content" id="gsnipreviewprofile">
	<div class="b-details">
		<div class="items">

			<table class="title-first">
				<tr class="odd">
					<td>
						<b>{l s='Addresses for' mod='gsnipreview'} 
						{$customer.firstname|escape:'htmlall':'UTF-8'} {$customer.lastname|escape:'htmlall':'UTF-8'}:
						</b>
					</td>
				</tr>
			</table>
			{if count($customer.addresses)>0}
			{foreach from=$customer.addresses item=address name=ItemMyLoop}
			<table class="margin-top-10 title-first">
				<tr class="even">
					<td class="width-33">
						<b class="font-size-12">
							{l s='Location #' mod='gsnipreview'}{$smarty.foreach.ItemMyLoop.index+1|escape:'htmlall':'UTF-8'}:
						</b>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<table class="title-first border-none">
				<tr class="even">
					<td>
						{if strlen($address.country)>0}{$address.country|escape:'htmlall':'UTF-8'}, <br>{/if}
						{if strlen($address.city)>0}{$address.city|escape:'htmlall':'UTF-8'}, <br/>{/if}
						{if strlen($address.postcode)>0}{$address.postcode|escape:'htmlall':'UTF-8'}{/if}
					</td>
				</tr>
			</table>
			
			
			{/foreach}
			{else}
			<table class="title-first border-none margin-top-10">
				<tr class="even">
					<td>
						<b class="font-size-12">{l s='Address Not Found.' mod='gsnipreview'}</b>
					</td>
				</tr>
			</table>
			{/if}

		</div>	
	</div>
</div>


{if $gsnipreviewrvis_on == 1}
<div class="b-tab-wrapper current-tab-content-hide" id="gsnipreviewreviews">
        <div class="b-details">
            <div class="items">


                <div class="row-custom total-info-tool">
                    <div class="col-sm-6-custom first-block-ti">



                        <strong class="float-left">
                            <span class="testimonials-count-items">{$gsnipreviewcount_reviews|escape:'htmlall':'UTF-8'}</span>
                            {l s='Product Reviews' mod='gsnipreview'}
                        </strong>

                        <span class="separator-items-block float-left">-</span>


                        <div itemscope itemtype="http://schema.org/corporation" class="float-left total-rating-items-block">


                            <meta itemprop="name" content="{$customer.firstname|escape:'htmlall':'UTF-8'}{$customer.lastname|escape:'htmlall':'UTF-8'}">
                            <meta itemprop="url" content="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}">



                            <div itemtype="http://schema.org/AggregateRating" itemscope="" itemprop="aggregateRating">


                                <meta itemprop="reviewCount" content="{$gsnipreviewcount_reviews|escape:'htmlall':'UTF-8'}">

                                {section name=bar loop=5 start=0}
                                    {if $smarty.section.bar.index < $gsnipreviewavg_rating}
                                        <img src = "{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.bar.index|escape:'htmlall':'UTF-8'}"/>
                                    {else}
                                        <img src = "{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.bar.index|escape:'htmlall':'UTF-8'}"/>
                                    {/if}
                                {/section}

                                <span {if $gsnipreviewis16 == 0}class="vertical-align-top"{/if}>
                        (<span itemprop="ratingValue" {if $gsnipreviewis16 == 0}class="vertical-align-top"{/if}
                                            >{$gsnipreviewavg_decimal|escape:'htmlall':'UTF-8'}</span>/<span itemprop="bestRating" {if $gsnipreviewis16 == 0}class="vertical-align-top"{/if}
                                            >5</span>)
                        </span>

                            </div>

                        </div>



                    </div>
                    <div class="col-sm-5-custom b-search-items">

                        <form method="get" action="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}">
                            <input type="hidden" name="uid" value="{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}"/>
                            <fieldset>
                                <input type="submit" value="go" class="button_mini_custom {if $gsnipreviewis_ps15 == 0}search_go{/if}">
                                <input type="text" class="txt {if $gsnipreviewis16 == 0}search-input-height-15{/if}" name="search"
                                       onfocus="{literal}if(this.value == '{/literal}{l s='Search' mod='gsnipreview'}{literal}') {this.value='';};{/literal}"
                                       onblur="{literal}if(this.value == '') {this.value='{/literal}{l s='Search' mod='gsnipreview'}{literal}';};{/literal}"
                                       value="{l s='Search' mod='gsnipreview'}" />
                                {if $gsnipreviewis_search == 1}
                                    <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&gp=0" class="clear-search-items">
                                        {l s='Clear search' mod='gsnipreview'}
                                    </a>
                                {/if}

                            </fieldset>
                        </form>


                    </div>

                </div>

                <div class="row-custom filter-reviews-gsnipreview {if $gsnipreviewis16 == 0}filter-testimonials-14{/if}">

                    <div class="col-sm-1-custom">
                        <b class="filter-txt-items-block">{l s='Filter' mod='gsnipreview'}:</b>
                    </div>
                    <div class="col-sm-2-custom {if isset($gsnipreviewfrat) && $gsnipreviewfrat == 5}active-items-block{/if}">
                        {if $gsnipreviewfive>0}
                        <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&frat=5{if $gsnipreviewis_search == 1}&search={$gsnipreviewsearch|escape:'quotes':'UTF-8'}{/if}">
                            {/if}
                            {section name="test" loop=5}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" />
                            {/section}
                            <span class="count-items-block {if $gsnipreviewfive==0}text-decoration-none{/if}">({$gsnipreviewfive|escape:'htmlall':'UTF-8'})</span>
                            {if $gsnipreviewfive>0}
                        </a>
                        {/if}
                    </div>
                    <div class="col-sm-2-custom {if isset($gsnipreviewfrat) && $gsnipreviewfrat == 4}active-items-block{/if}">
                        {if $gsnipreviewfour>0}
                        <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&frat=4{if $gsnipreviewis_search == 1}&search={$gsnipreviewsearch|escape:'quotes':'UTF-8'}{/if}">
                            {/if}
                            {section name="test" loop=4}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" />
                            {/section}
                            {section name="test" loop=1}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}"/>
                            {/section}

                            <span class="count-items-block {if $gsnipreviewfour==0}text-decoration-none{/if}">({$gsnipreviewfour|escape:'htmlall':'UTF-8'})</span>
                            {if $gsnipreviewfour>0}
                        </a>
                        {/if}
                    </div>
                    <div class="col-sm-2-custom {if isset($gsnipreviewfrat) && $gsnipreviewfrat == 3}active-items-block{/if}">
                        {if $gsnipreviewthree>0}
                        <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&frat=3{if $gsnipreviewis_search == 1}&search={$gsnipreviewsearch|escape:'quotes':'UTF-8'}{/if}">
                            {/if}
                            {section name="test" loop=3}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}"/>
                            {/section}
                            {section name="test" loop=2}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" />
                            {/section}
                            <span class="count-items-block {if $gsnipreviewthree==0}text-decoration-none{/if}">({$gsnipreviewthree|escape:'htmlall':'UTF-8'})</span>
                            {if $gsnipreviewthree>0}
                        </a>
                        {/if}
                    </div>
                    <div class="col-sm-2-custom {if isset($gsnipreviewfrat) && $gsnipreviewfrat == 2}active-items-block{/if}">
                        {if $gsnipreviewtwo>0}
                        <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&frat=2{if $gsnipreviewis_search == 1}&search={$gsnipreviewsearch|escape:'quotes':'UTF-8'}{/if}">
                            {/if}
                            {section name="test" loop=2}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}"/>
                            {/section}
                            {section name="test" loop=3}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}"/>
                            {/section}

                            <span class="count-items-block {if $gsnipreviewtwo==0}text-decoration-none{/if}">({$gsnipreviewtwo|escape:'htmlall':'UTF-8'})</span>
                            {if $gsnipreviewtwo>0}
                        </a>
                        {/if}
                    </div>
                    <div class="col-sm-2-custom {if isset($gsnipreviewfrat) && $gsnipreviewfrat == 1}active-items-block{/if}">
                        {if $gsnipreviewone>0}
                        <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&frat=1{if $gsnipreviewis_search == 1}&search={$gsnipreviewsearch|escape:'quotes':'UTF-8'}{/if}">
                            {/if}
                            {section name="test" loop=1}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}"/>
                            {/section}
                            {section name="test" loop=4}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}"/>
                            {/section}
                            <span class="count-items-block {if $gsnipreviewone==0}text-decoration-none{/if}">({$gsnipreviewone|escape:'htmlall':'UTF-8'})</span>
                            {if $gsnipreviewone>0}
                        </a>
                        {/if}
                    </div>

                    {if $gsnipreviewfrat}
                        <div class="col-sm-1-custom">
                            <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&gp=0" class="reset-items-block">
                                <i class="fa fa-refresh"></i>{l s='Reset' mod='gsnipreview'}
                            </a>
                        </div>
                    {/if}


                </div>

                {if $gsnipreviewis_search == 1}
                    <h3 class="search-result-item">{l s='Results for' mod='gsnipreview'} <b>"{$gsnipreviewsearch|escape:'quotes':'UTF-8'}"</b></h3>
                    <br/>
                {/if}



                <div class="block_content" id="shopify-product-reviews">
                    {if count($gsnipreviewuser_reviews)>0}

                        <div class="spr-reviews">

                            {foreach from=$gsnipreviewuser_reviews item=review}
                                <div class="spr-review">
                                    <div class="spr-review-header">
                                        {if $review.is_active == 1}
                                            {if $gsnipreviewratings_on == 1 && $review.rating!=0}
                                                <span class="spr-starratings spr-review-header-starratings">

                                      {section name=ratid loop=5}
                                          {if $smarty.section.ratid.index < $review.rating}
                                              <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                                        {else}
                                            <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}"  alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                                          {/if}
                                      {/section}

                                    </span>
                                                <div itemtype="http://schema.org/Rating" itemscope="" itemprop="reviewRating" class="rating-stars-total">
                                                    (<span itemprop="ratingValue">{$review.rating|escape:'htmlall':'UTF-8'}</span>/<span itemprop="bestRating">5</span>)&nbsp;
                                                </div>

                                            {/if}

                                            {if $gsnipreviewtitle_on == 1 && strlen($review.title_review)>0}
                                                <h3 class="spr-review-header-title">
                                                    <a href="{$gsnipreviewrev_url|escape:'htmlall':'UTF-8'}{if $gsnipreviewis_rewrite == 1}?{else}&{/if}rid={$review.id|escape:'htmlall':'UTF-8'}"
                                                       title="{$review.title_review|escape:'htmlall':'UTF-8' nofilter}" class="title-review-all">
                                                        {$review.title_review|escape:'htmlall':'UTF-8' nofilter}
                                                    </a>
                                                </h3>
                                            {/if}

                                        {/if}
                                        <div class="clear-gsnipreview"></div>

                            <span class="spr-review-header-byline float-left">
                                {l s='By' mod='gsnipreview'}
                                {if $gsnipreviewis_avatarr == 1 && strlen($review.avatar)>0}

                                    <span class="avatar-block-rev">
                                        <img alt="{$review.customer_name|escape:'htmlall':'UTF-8'}"
                                             src="{$review.avatar|escape:'htmlall':'UTF-8'}">
                                     </span>

                                {/if}

                                {if strlen($review.customer_name)>0}
                                    {if $gsnipreviewis_uprof && $review.id_customer > 0}<a href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$review.id_customer|escape:'htmlall':'UTF-8'}" title="{$review.customer_name|escape:'htmlall':'UTF-8'}" class="user-link-to-profile">{/if}<strong
                                            >{$review.customer_name|escape:'htmlall':'UTF-8'}</strong>{if $gsnipreviewis_uprof && $review.id_customer > 0}</a>{/if}
                                {/if}
                                {if strlen($review.customer_name)>0}{l s='on' mod='gsnipreview'}{/if}&nbsp;<strong>{$review.time_add|date_format|escape:'htmlall':'UTF-8'}</strong>
                                {if $gsnipreviewip_on == 1 && strlen($review.ip)>0}
                                    ({if $review.is_no_ip == 0}<b>{l s='IP' mod='gsnipreview'}:</b>&nbsp;{/if}{$review.ip|escape:'htmlall':'UTF-8'})
                                {/if}
                                <span itemprop="itemReviewed" class="font-size-10">
                                    <a href="{$review.product_link|escape:'quotes':'UTF-8'}"
                                       title="{$review.product_name|escape:'quotes':'UTF-8'}"
                                       class="title-review-all">
                                        {$review.product_name|escape:'quotes':'UTF-8'}
                                    </a>
                                </span>

                            </span>

                                        {if $review.is_active == 1}
                                            {if $gsnipreviewis_helpfulf == 1}
                                                <span class="float-right people-folowing-reviews" id="people-folowing-reviews{$review.id|escape:'htmlall':'UTF-8'}"><span class="first-helpful" id="block-helpful-yes{$review.id|escape:'htmlall':'UTF-8'}">{$review.helpfull_yes|escape:'quotes':'UTF-8'}</span> {l s='of' mod='gsnipreview'} <span id="block-helpful-all{$review.id|escape:'htmlall':'UTF-8'}">{$review.helpfull_all|escape:'quotes':'UTF-8'}</span> {l s='people found the following review helpful' mod='gsnipreview'}</span>
                                            {/if}
                                        {/if}
                                        <div class="clear-gsnipreview"></div>

                                        {if $review.is_buy != 0}
                                            <span class="spr-review-header-byline float-left">
                                        <span class="is_buy_product">{l s='Verified Purchase' mod='gsnipreview'}</span>
                                    </span>
                                            <div class="clear-gsnipreview"></div>
                                        {/if}

                                    </div>

                                    <div class="{if $gsnipreviewis16 == 1}row-custom{else}row-list-reviews{/if}">


                                        {if $review.product_img}
                                            <div class="img-block-gsnipreview col-sm-2-custom">
                                                <a href="{$gsnipreviewrev_url|escape:'htmlall':'UTF-8'}{if $gsnipreviewis_rewrite == 1}?{else}&{/if}rid={$review.id|escape:'htmlall':'UTF-8'}"
                                                   title="{$review.product_name|escape:'htmlall':'UTF-8'}"
                                                        >
                                                    <img src="{$review.product_img|escape:'htmlall':'UTF-8'}" title="{$review.product_name|escape:'htmlall':'UTF-8'}"
                                                         alt = "{$review.product_name|escape:'htmlall':'UTF-8'}" class="border-image-review img-responsive" />
                                                </a>
                                            </div>
                                        {/if}


                                        {if $review.is_active == 1}
                                            {if $review.criterions|@count>0}
                                                <div class="spr-review-content {if $gsnipreviewis16 == 1}col-sm-3-custom{else}col-sm-3-list-reviews{/if}">

                                                    {foreach from=$review.criterions item=criterion}
                                                        <div class="criterion-item-block">
                                                            {$criterion.name|escape:'htmlall':'UTF-8'}:

                                                            {section name=ratid loop=5}
                                                                {if $smarty.section.ratid.index < $criterion.rating}
                                                                    <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star-list" alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                                                                {else}
                                                                    <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star-list"  alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                                                                {/if}
                                                            {/section}

                                                        </div>
                                                    {/foreach}

                                                </div>
                                            {/if}
                                        {/if}

                                        <div class="spr-review-content {if $gsnipreviewis16 == 1}col-sm-{if $review.criterions|@count>0 && $review.is_active == 1}9{else}10{/if}-custom{else}col-sm-{if $review.criterions|@count>0 && $review.is_active == 1}7{else}10{/if}-list-reviews{/if}">

                                            {if $review.is_active == 1}
                                                {if $gsnipreviewtext_on == 1 && strlen($review.text_review)>0}
                                                    {*!! no smarty changes |escape:'htmlall':'UTF-8' !!*}
                                                    <p class="spr-review-content-body">

                                                        {if strlen($review.text_review)>150}

                                                            {if $gsnipreviewtitle_on == 0 || strlen($review.title_review)==0}
                                                                <a href="{$gsnipreviewrev_url|escape:'htmlall':'UTF-8'}{if $gsnipreviewis_rewrite == 1}?{else}&{/if}rid={$review.id|escape:'htmlall':'UTF-8'}"
                                                                   title="{$review.title_review|escape:'htmlall':'UTF-8'}" class="title-review-all">
                                                                    {$review.text_review|escape:'htmlall':'UTF-8'|nl2br nofilter}</a>
                                                            {else}

                                                                {$review.text_review|substr:0:150|escape:'htmlall':'UTF-8'|nl2br nofilter}...&nbsp;
                                                                <a href="{$gsnipreviewrev_url|escape:'htmlall':'UTF-8'}{if $gsnipreviewis_rewrite == 1}?{else}&{/if}rid={$review.id|escape:'htmlall':'UTF-8'}"
                                                                   title="{$review.title_review|escape:'htmlall':'UTF-8'}" class="title-review-all">
                                                                    {l s='more' mod='gsnipreview'}</a>

                                                            {/if}

                                                        {else}

                                                            {if $gsnipreviewtitle_on == 0 || strlen($review.title_review)==0}
                                                                <a href="{$gsnipreviewrev_url|escape:'htmlall':'UTF-8'}{if $gsnipreviewis_rewrite == 1}?{else}&{/if}rid={$review.id|escape:'htmlall':'UTF-8'}"
                                                                   title="{$review.title_review|escape:'htmlall':'UTF-8'}" class="title-review-all">
                                                                    {$review.text_review|escape:'htmlall':'UTF-8'|nl2br nofilter}</a>
                                                            {else}
                                                                {$review.text_review|escape:'htmlall':'UTF-8'|nl2br nofilter}
                                                            {/if}
                                                        {/if}
                                                    </p>
                                                    {*!! no smarty changes |escape:'htmlall':'UTF-8' !!*}
                                                {/if}

                                                {if $gsnipreviewis_filesr == 1}
                                                    <div class="clear-gsnipreview"></div>
                                                    {if count($review.files)>0}
                                                        <div  class="{if $gsnipreviewis16 == 1}row-custom{else}row-list-reviews{/if}">
                                                            {foreach from=$review.files item=file}
                                                                <div class="col-sm-{if $gsnipreviewis16 == 1}2{else}3{/if}-custom files-review-gsnipreview">
                                                                    <a class="fancybox shown" data-fancybox-group="other-views" href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}{$file.full_path|escape:'htmlall':'UTF-8'}">
                                                                        <img class="img-responsive" width="105" height="105" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}{$file.small_path|escape:'htmlall':'UTF-8'}" alt="{$file.id|escape:'htmlall':'UTF-8'}"

                                                                                >
                                                                    </a>

                                                                </div>
                                                            {/foreach}
                                                        </div>
                                                    {/if}
                                                {/if}

                                            {else}
                                                <p class="spr-review-content-body">{l s='The customer has rated the product but has not posted a review, or the review is pending moderation' mod='gsnipreview'}</p>
                                            {/if}




                                            {if $review.is_active == 1}
                                            {if strlen($review.admin_response)>0 && $review.is_display_old == 1}
                                                <div class="clear-gsnipreview"></div>
                                                <div class="shop-owner-reply-on-review">
                                                    <div class="owner-date-reply">{l s='Shop owner reply' mod='gsnipreview'} ({$review.review_date_update|date_format|escape:'htmlall':'UTF-8'}): </div>
                                                    {$review.admin_response|escape:'htmlall':'UTF-8'|nl2br nofilter}
                                                </div>
                                            {/if}

                                                <div class="clear-gsnipreview"></div>
                                                <div class="spr-review-footer row-custom">

                                                    {if $gsnipreviewis_helpfulf == 1}
                                                        <div class="col-sm-{if $gsnipreviewis_abusef == 0}12{else}9{/if}-custom margin-bottom-10" id="block-helpful{$review.id|escape:'htmlall':'UTF-8'}">
                                                            {l s='Was this review helpful to you?' mod='gsnipreview'}
                                                            <a class="btn-success button_padding_gsnipreview" title="{l s='Yes' mod='gsnipreview'}"
                                                               href="javascript:void(0)" onclick="report_helpfull_gsnipreview({$review.id|escape:'htmlall':'UTF-8'},1)" ><b>{l s='Yes' mod='gsnipreview'}</b></a>
                                                            <a class="btn-danger button_padding_gsnipreview" title="{l s='No' mod='gsnipreview'}"
                                                               href="javascript:void(0)" onclick="report_helpfull_gsnipreview({$review.id|escape:'htmlall':'UTF-8'},0)"><b>{l s='No' mod='gsnipreview'}</b></a>
                                                        </div>
                                                    {/if}

                                                    {if $gsnipreviewis_abusef == 1}
                                                        <div class="col-sm-{if $gsnipreviewis_helpfulf == 0}12{else}3{/if}-custom margin-bottom-10">
                                                            <a class="button_padding_gsnipreview spr-review-reportreview"
                                                               title="{l s='Report abuse' mod='gsnipreview'}"
                                                               href="javascript:void(0)" onclick="report_abuse_gsnipreview({$review.id|escape:'htmlall':'UTF-8'})"
                                                                    ><b><i class="fa fa-ban text-primary"></i>&nbsp;{l s='Report abuse' mod='gsnipreview'}</b></a>
                                                        </div>
                                                    {/if}


                                                    <div class="clear-gsnipreview"></div>

                                                </div>

                                            {if $gsnipreviewrsoc_on == 1}
                                                <div class="fb-like valign-top" data-href="{$gsnipreviewrev_url|escape:'htmlall':'UTF-8'}{if $gsnipreviewis_rewrite == 1}?{else}&{/if}rid={$review.id|escape:'htmlall':'UTF-8'}"
                                                     data-show-faces="false" data-width="60" data-send="false" data-layout="{if $gsnipreviewrsoccount_on == 1}button_count{else}button{/if}"></div>
                                                {*{$gsnipreviewrev_url|escape:'htmlall':'UTF-8'}{if $gsnipreviewis_rewrite == 1}?{else}&{/if}rid={$review.id|escape:'htmlall':'UTF-8'}*}
                                            {literal}
                                                <script type="text/javascript">

                                                    document.addEventListener("DOMContentLoaded", function(event) {
                                                    $(document).ready(function(){

                                                        /* Voucher, when a user share review on the Facebook */
                                                        // like

                                                        FB.Event.subscribe("edge.create", function(targetUrlReview) {

                                                            //alert(targetUrlReview);
                                                            if(targetUrlReview == '{/literal}{$gsnipreviewrev_url|escape:'htmlall':'UTF-8' nofilter}{if $gsnipreviewis_rewrite == 1}?{else}&{/if}rid={$review.id|escape:'htmlall':'UTF-8'}{literal}'){

                                                                addRemoveDiscountShareReview('facebook',{/literal}{$review.id|escape:'htmlall':'UTF-8'}{literal});

                                                            }
                                                        });
                                                        /* Voucher, when a user share review on the Facebook */

                                                    });
                                                    });
                                                </script>
                                            {/literal}


                                            {/if}

                                            {/if}

                                        </div>
                                        <div class="clear-gsnipreview"></div>

                                    </div>






                                </div>
                            {/foreach}


                        </div>



                    {else}
                        <div class="gsniprev-block-noreviews-list">
                            {l s='There are not Product Reviews yet.' mod='gsnipreview'}
                        </div>
                    {/if}
                </div>
                {if count($gsnipreviewuser_reviews)>0}
                    <div class="text-align-center">
                        {*{$gsnipreviewpaging|escape:'quotes':'UTF-8'}*}
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
{/if}




    {if $gsnipreviewis_storerev == 1}
        <div class="b-tab-wrapper current-tab-content-hide padding-10" id="gsnipreviewstorereviews">









                <div class="row-custom total-info-tool">
                    <div class="col-sm-6-custom first-block-ti">



                        <strong class="float-left">
                            <span class="testimonials-count-items">{$gsnipreviewcount_reviewsti|escape:'htmlall':'UTF-8'}</span>
                            {l s='Store Reviews' mod='gsnipreview'}
                        </strong>

                        <span class="separator-items-block float-left">-</span>


                        <div {if $gsnipreviewt_tpages == 1}itemscope itemtype="http://schema.org/corporation"{/if} class="float-left total-rating-items-block">

                            {if $gsnipreviewt_tpages == 1}

                                <meta itemprop="name" content="{$customer.firstname|escape:'htmlall':'UTF-8'}{$customer.lastname|escape:'htmlall':'UTF-8'}">
                                <meta itemprop="url" content="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}">

                            {/if}


                            <div {if $gsnipreviewt_tpages == 1}itemtype="http://schema.org/AggregateRating" itemscope="" itemprop="aggregateRating"{/if}>

                                {if $gsnipreviewt_tpages == 1}
                                    <meta itemprop="reviewCount" content="{$gsnipreviewcount_reviewsti|escape:'htmlall':'UTF-8'}">
                                {/if}


                                {section name=bar loop=5 start=0}
                                    {if $smarty.section.bar.index < $gsnipreviewavg_ratingti}
                                        <img src = "{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.bar.index|escape:'htmlall':'UTF-8'}"/>
                                    {else}
                                        <img src = "{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.bar.index|escape:'htmlall':'UTF-8'}"/>
                                    {/if}
                                {/section}

                                <span {if $gsnipreviewis16 == 0}class="vertical-align-top"{/if}>
                        (<span {if $gsnipreviewt_tpages == 1}itemprop="ratingValue"{/if} {if $gsnipreviewis16 == 0}class="vertical-align-top"{/if}
                                            >{$gsnipreviewavg_decimalti|escape:'htmlall':'UTF-8'}</span>/<span {if $gsnipreviewt_tpages == 1}itemprop="bestRating"{/if} {if $gsnipreviewis16 == 0}class="vertical-align-top"{/if}
                                            >5</span>)
                        </span>

                            </div>

                        </div>



                    </div>
                    <div class="col-sm-5-custom b-search-items">

                        <form method="get" action="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}">
                            <input type="hidden" name="uid" value="{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}"/>
                            <fieldset>
                                <input type="submit" value="go" class="button_mini_custom {if $gsnipreviewis_ps15 == 0}search_go{/if}">
                                <input type="text" class="txt {if $gsnipreviewis16 == 0}search-input-height-15{/if}" name="searchti"
                                       onfocus="{literal}if(this.value == '{/literal}{l s='Search' mod='gsnipreview'}{literal}') {this.value='';};{/literal}"
                                       onblur="{literal}if(this.value == '') {this.value='{/literal}{l s='Search' mod='gsnipreview'}{literal}';};{/literal}"
                                       value="{l s='Search' mod='gsnipreview'}" />
                                {if $gsnipreviewis_searchti == 1}
                                    <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&pti=0" class="clear-search-items">
                                        {l s='Clear search' mod='gsnipreview'}
                                    </a>
                                {/if}

                            </fieldset>
                        </form>


                    </div>

                </div>




                <div class="row-custom filter-testimonials {if $gsnipreviewis16 == 0}filter-testimonials-14{/if}">

                    <div class="col-sm-1-custom">
                        <b class="filter-txt-items-block">{l s='Filter' mod='gsnipreview'}:</b>
                    </div>
                    <div class="col-sm-2-custom {if isset($gsnipreviewfratti) && $gsnipreviewfratti == 5}active-items-block{/if}">
                        {if $gsnipreviewfiveti>0}
                        <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&fratti=5{if $gsnipreviewis_searchti == 1}&searchti={$gsnipreviewsearchti|escape:'quotes':'UTF-8'}{/if}">
                            {/if}
                            {section name="test" loop=5}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" />
                            {/section}
                            <span class="count-items-block {if $gsnipreviewfiveti==0}text-decoration-none{/if}">({$gsnipreviewfiveti|escape:'htmlall':'UTF-8'})</span>
                            {if $gsnipreviewfiveti>0}
                        </a>
                        {/if}
                    </div>
                    <div class="col-sm-2-custom {if isset($gsnipreviewfratti) && $gsnipreviewfratti == 4}active-items-block{/if}">
                        {if $gsnipreviewfourti>0}
                        <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&fratti=4{if $gsnipreviewis_searchti == 1}&searchti={$gsnipreviewsearchti|escape:'quotes':'UTF-8'}{/if}">
                            {/if}
                            {section name="test" loop=4}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" />
                            {/section}
                            {section name="test" loop=1}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}"/>
                            {/section}

                            <span class="count-items-block {if $gsnipreviewfourti==0}text-decoration-none{/if}">({$gsnipreviewfourti|escape:'htmlall':'UTF-8'})</span>
                            {if $gsnipreviewfourti>0}
                        </a>
                        {/if}
                    </div>
                    <div class="col-sm-2-custom {if isset($gsnipreviewfratti) && $gsnipreviewfratti == 3}active-items-block{/if}">
                        {if $gsnipreviewthreeti>0}
                        <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&fratti=3{if $gsnipreviewis_searchti == 1}&searchti={$gsnipreviewsearchti|escape:'quotes':'UTF-8'}{/if}">
                            {/if}
                            {section name="test" loop=3}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}"/>
                            {/section}
                            {section name="test" loop=2}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}" />
                            {/section}
                            <span class="count-items-block {if $gsnipreviewthreeti==0}text-decoration-none{/if}">({$gsnipreviewthreeti|escape:'htmlall':'UTF-8'})</span>
                            {if $gsnipreviewthreeti>0}
                        </a>
                        {/if}
                    </div>
                    <div class="col-sm-2-custom {if isset($gsnipreviewfratti) && $gsnipreviewfratti == 2}active-items-block{/if}">
                        {if $gsnipreviewtwoti>0}
                        <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&fratti=2{if $gsnipreviewis_searchti == 1}&searchti={$gsnipreviewsearchti|escape:'quotes':'UTF-8'}{/if}">
                            {/if}
                            {section name="test" loop=2}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}"/>
                            {/section}
                            {section name="test" loop=3}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}"/>
                            {/section}

                            <span class="count-items-block {if $gsnipreviewtwoti==0}text-decoration-none{/if}">({$gsnipreviewtwoti|escape:'htmlall':'UTF-8'})</span>
                            {if $gsnipreviewtwoti>0}
                        </a>
                        {/if}
                    </div>
                    <div class="col-sm-2-custom {if isset($gsnipreviewfratti) && $gsnipreviewfratti == 1}active-items-block{/if}">
                        {if $gsnipreviewoneti>0}
                        <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&fratti=1{if $gsnipreviewis_searchti == 1}&searchti={$gsnipreviewsearchti|escape:'quotes':'UTF-8'}{/if}">
                            {/if}
                            {section name="test" loop=1}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}"/>
                            {/section}
                            {section name="test" loop=4}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.test.index|escape:'htmlall':'UTF-8'}"/>
                            {/section}
                            <span class="count-items-block {if $gsnipreviewoneti==0}text-decoration-none{/if}">({$gsnipreviewoneti|escape:'htmlall':'UTF-8'})</span>
                            {if $gsnipreviewoneti>0}
                        </a>
                        {/if}
                    </div>

                    {if $gsnipreviewfratti}
                        <div class="col-sm-1-custom">
                            <a rel="nofollow" href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$gsnipreviewuser_id|escape:'htmlall':'UTF-8'}&pti=0" class="reset-items-block">
                                <i class="fa fa-refresh"></i>{l s='Reset' mod='gsnipreview'}
                            </a>
                        </div>
                    {/if}


                </div>

                {if $gsnipreviewis_searchti == 1}
                    <h3 class="search-result-item">{l s='Results for' mod='gsnipreview'} <b>"{$gsnipreviewsearchti|escape:'quotes':'UTF-8'}"</b></h3>
                    <br/>
                {/if}


            {if $count_all_reviewsti > 0}
                <div id="list_reviews" class="productsBox1">
                    {foreach from=$reviewsti item=review name=myLoop}
                        <div {if $gsnipreviewt_tpages == 1}itemprop="review" itemscope itemtype="http://schema.org/Review"{/if}>
                            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="productsTable compareTableNew {if $gsnipreviewis16==1}float-left-table16{/if}">
                                <tbody>
                                <tr class="line1">

                                    <td class="info">
                                        {if $gsnipreviewt_tpages == 1}
                                            <meta itemprop="itemReviewed" content="{$shop_name_snippetti|escape:'htmlall':'UTF-8'}"/>
                                        {/if}
                                        <span class="commentbody_center" {if $gsnipreviewt_tpages == 1}itemprop="description"{/if}>
				                                    {$review.message|escape:'htmlall':'UTF-8'|nl2br nofilter}

                                            {if $review.is_show == 1 && strlen($review.response)>0}
                                                <div class="admin-reply-on-testimonial">
                                                    <div class="owner-date-reply">{l s='Administrator' mod='gsnipreview'}: </div>
                                                    {$review.response|escape:'htmlall':'UTF-8'|nl2br nofilter}
                                                </div>
                                            {/if}
                </span>

                                        <div class="clear"></div>
				<span class="foot_center margin-top-10">{l s='Posted by' mod='gsnipreview'}
                    {if $gsnipreviewis_avatar == 1 && $review.is_show_ava}

                        <span class="avatar-block-rev">
                                        <img {if strlen($review.avatar)>0}
                                            src="{$review.avatar|escape:'htmlall':'UTF-8'}"
                                        {else}
                                            src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/avatar_m.gif"
                                        {/if}
                                                alt="{$review.name|escape:'htmlall':'UTF-8'}"/>
                                     </span>

                    {/if}
                    <b {if $gsnipreviewt_tpages == 1}itemprop="author"{/if}
                            >{if $gsnipreviewis_storerev && $review.id_customer > 0}<a href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$review.id_customer|escape:'htmlall':'UTF-8'}" title="{$review.name|escape:'htmlall':'UTF-8'}" class="user-link-to-profile">{/if}{$review.name|escape:'htmlall':'UTF-8'}{if $gsnipreviewis_storerev && $review.id_customer > 0}</a>{/if}</b>{if $gsnipreviewt_tpages == 1}<meta
                    itemprop="name" content="{$review.name|escape:'quotes':'UTF-8'}"/>{/if}{if $gsnipreviewis_country == 1}{if strlen($review.country)>0}, <span
                            class="fs-12">{$review.country}</span>{/if}{/if}{if $gsnipreviewis_city == 1}{if strlen($review.city)>0}, <span class="fs-12">{$review.city}</span>{/if}{/if}

                    {if $review.rating != 0}
                        {*{for $foo=0 to 4}*}
                        {section name=bar loop=5 start=0}
                            {if $smarty.section.bar.index < $review.rating}
                                <img alt="{$smarty.section.bar.index|escape:'htmlall':'UTF-8'}" src = "{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" />
					 	{else}
							<img alt="{$smarty.section.bar.index|escape:'htmlall':'UTF-8'}" src = "{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" />
                            {/if}
                        {/section}
                        <span {if $gsnipreviewt_tpages == 1}itemtype="http://schema.org/Rating" itemscope="" itemprop="reviewRating"{/if}>
                            (<span {if $gsnipreviewt_tpages == 1}itemprop="ratingValue"{/if}>{$review.rating|escape:'htmlall':'UTF-8'}</span>/<span {if $gsnipreviewt_tpages == 1}itemprop="bestRating"{/if}>5</span>)&nbsp;
                        </span>

					{*{/for}*}
			     {else}
					{*{for $foo=0 to 4}*}
					{section name=bar loop=5 start=0}
                        <img alt="{$smarty.section.bar.index|escape:'htmlall':'UTF-8'}" src = "{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" />
                    {/section}
                        {*{/for}*}
                    <span {if $gsnipreviewt_tpages == 1}itemtype="http://schema.org/Rating" itemscope="" itemprop="reviewRating"{/if}>
                            (<span {if $gsnipreviewt_tpages == 1}itemprop="ratingValue"{/if}>{$review.rating|escape:'htmlall':'UTF-8'}</span>/<span {if $gsnipreviewt_tpages == 1}itemprop="bestRating"{/if}>5</span>)&nbsp;
                    </span>
                    {/if}


				</span>
                                        <div class="clear"></div>

                                        <span class="foot_center">{$review.date_add|date_format:"%d-%m-%Y"|escape:'htmlall':'UTF-8'}</span>
                                        <br/>
                                        {if $gsnipreviewt_tpages == 1}
                                            <meta itemprop="datePublished" content="{$review.date_add|date_format:"%Y-%m-%d"|escape:'htmlall':'UTF-8'}"/>
                                        {/if}

                                        <span class="foot_center">
                                            {if $gsnipreviewis_company == 1}
                                                <b>{$review.company|escape:'htmlall':'UTF-8' nofilter}</b>
                                            {/if}

                                            {if $gsnipreviewis_addr == 1}
                                                <b>{$review.address|escape:'htmlall':'UTF-8' nofilter}</b>
                                            {/if}

                                            {if $gsnipreviewis_web == 1}
                                                {if strlen($review.web)>0}
                                                    <a title="http://{$review.web|escape:'htmlall':'UTF-8'}" rel="nofollow"
                                                       href="http://{$review.web|escape:'htmlall':'UTF-8'}">http://{$review.web|escape:'htmlall':'UTF-8'}</a>
                                                {/if}
                                            {/if}
				                        </span>

                                        <div class="clear"></div>
                                        <span class="foot_center">{if $review.is_buy != 0}<span class="is_buy">{l s='Verified Purchase' mod='gsnipreview'}</span>{/if}</span>

                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    {/foreach}
                    {if $gsnipreviewis16==1}<div class="clear"></div>{/if}
                </div>


                        <div class="text-align-center">
                                {*{$gsnipreviewpaging|escape:'quotes':'UTF-8'}*}
                                    <div class="pages">
                                        <span>{$gsnipreviewpage_text|escape:'htmlall':'UTF-8'}:</span>
                                        <span class="nums">
                                            {foreach $pagingti as $page_item}
                                                {if $page_item.is_b == 1}
                                                    <b>{$page_item.page|escape:'htmlall':'UTF-8'}</b>
                                                {else}
                                                    <a href="{$page_item.url|escape:'quotes':'UTF-8'}" title="{$page_item.title|escape:'htmlall':'UTF-8'}">{$page_item.page|escape:'htmlall':'UTF-8'}</a>
                                                {/if}
                                            {/foreach}
                                        </span>
                                    </div>


                        </div>

            {else}
                <div class="testimonials-no-items">
                    {l s='There are not store reviews yet' mod='gsnipreview'}
                </div>
            {/if}



        </div>

    {/if}

</div>

</div>

{/foreach}


{literal}
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
    $( "#tabs-custom ul li a" ).click(function() {

        var tab_custom_gsnipreview = $(this).attr('data').slice($(this).attr('data').indexOf('#') + 1);


        $.each($('#tabs-custom ul li'), function(key, val) {

            $(this).removeClass("current");

            if($(this).children(0).attr('data') == tab_custom_gsnipreview){
                $(this).addClass("current");
            }


        });


        $.each($('#tabs-custom-content div.b-tab-wrapper'), function(key, val) {
            $(this).removeClass("current-tab-content");
            $(this).removeClass("current-tab-content-hide");

            if($(this).attr('id').slice($(this).attr('id').indexOf('#') + 1) == tab_custom_gsnipreview) {
                $(this).addClass("current-tab-content");
            } else {
                $(this).addClass("current-tab-content-hide");
            }

        });

    });

    });


    {/literal}{if $gsnipreviewis_search == 1 || $gsnipreviewfrat > 0 || $gsnipreviewisgp}{literal}
    document.addEventListener("DOMContentLoaded", function(event) {
    $(document).ready(function(){

        $.each($('#tabs-custom ul li'), function(key, val) {
            $(this).removeClass("current");
        });

        $('#tabs-custom ul li a[data="gsnipreviewreviews"]').parent().addClass("current");


        $.each($('#tabs-custom-content div.b-tab-wrapper'), function(key, val) {
            $(this).removeClass("current-tab-content");
            $(this).addClass("current-tab-content-hide");
        });

        $('#tabs-custom-content #gsnipreviewreviews').removeClass("current-tab-content-hide");
        $('#tabs-custom-content #gsnipreviewreviews').addClass("current-tab-content");


    });
    });
    {/literal}{/if}{literal}


    {/literal}{if $gsnipreviewis_searchti == 1 || $gsnipreviewfratti > 0 || $gsnipreviewispti}{literal}
    document.addEventListener("DOMContentLoaded", function(event) {
    $(document).ready(function(){

        $.each($('#tabs-custom ul li'), function(key, val) {
            $(this).removeClass("current");
        });

        $('#tabs-custom ul li a[data="gsnipreviewstorereviews"]').parent().addClass("current");


        $.each($('#tabs-custom-content div.b-tab-wrapper'), function(key, val) {
            $(this).removeClass("current-tab-content");
            $(this).addClass("current-tab-content-hide");
        });

        $('#tabs-custom-content #gsnipreviewstorereviews').removeClass("current-tab-content-hide");
        $('#tabs-custom-content #gsnipreviewstorereviews').addClass("current-tab-content");


    });
    });
    {/literal}{/if}{literal}

</script>
{/literal}
