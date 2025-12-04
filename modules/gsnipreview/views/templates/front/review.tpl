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



{capture name=path}{l s='Review in the shop' mod='gsnipreview'}{/capture}







<h3 class="page-subheading">{l s='Review for' mod='gsnipreview'}&nbsp;"{$gsnipreviewreviews_all[0].product_name|escape:'htmlall':'UTF-8'}"</h3>





<div class="block-last-gsnipreviews block blockmanufacturer {if $gsnipreviewis17 == 1}block-categories{/if}">



    <div class="row-custom">



        <div class="col-sm-6-custom">



                <a title="{$gsnipreviewreviews_all[0].product_name|escape:'htmlall':'UTF-8'}" href="{$gsnipreviewreviews_all[0].product_link|escape:'htmlall':'UTF-8'}">

                    <img alt="{$gsnipreviewreviews_all[0].product_name|escape:'htmlall':'UTF-8'}" src="{$gsnipreviewreviews_all[0].product_img|escape:'htmlall':'UTF-8'}" class="border-image-review img-responsive" />



                </a>



        </div>

        <div class="col-sm-5-custom margin-left-10">

                <span>

                     <a title="{$gsnipreviewreviews_all[0].product_name|escape:'htmlall':'UTF-8'}" href="{$gsnipreviewreviews_all[0].product_link|escape:'htmlall':'UTF-8'}">

                          <strong>{$gsnipreviewreviews_all[0].product_name|escape:'htmlall':'UTF-8'}</strong>

                     </a>

                    <br/><br/>

                    {$gsnipreviewreviews_all[0].description_short|escape:'htmlall':'UTF-8' nofilter}

                </span>

        </div>

    </div>

    <div class="clear-gsnipreview"></div>

    <br/>



		<div class="block_content" id="shopify-product-reviews">



            <h3 class="page-subheading">{l s='Review' mod='gsnipreview'}</h3>



                <div class="spr-reviews">



                    {foreach from=$gsnipreviewreviews_all item=review}

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

                                            {$review.title_review|escape:'htmlall':'UTF-8' nofilter}

                                    </h3>

                                {/if}



                                {/if}



                                <div class="clear-gsnipreview"></div>



                            <span class="spr-review-header-byline float-left">

                                {l s='By' mod='gsnipreview'}

                                {if $gsnipreviewis_avatarr == 1 && strlen($review.avatar)>0 && $review.is_show_ava}



                                    <span class="avatar-block-rev">

                                        <img alt="{$review.customer_name|escape:'htmlall':'UTF-8'}"

                                             src="{$review.avatar|escape:'htmlall':'UTF-8'}">

                                    </span>



                                {/if}



                                {if strlen($review.customer_name)>0}

                                    {if $gsnipreviewis_uprof && $review.is_show_ava && $review.id_customer > 0}<a href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$review.id_customer|escape:'htmlall':'UTF-8'}" title="{$review.customer_name|escape:'htmlall':'UTF-8'}" class="user-link-to-profile">{/if}<strong

                                            >{$review.customer_name|escape:'htmlall':'UTF-8' nofilter}</strong>{if $gsnipreviewis_uprof && $review.id_customer > 0}</a>{/if}

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



                                <div class="spr-review-content {if $gsnipreviewis16 == 1}col-sm-{if $review.criterions|@count>0 && $review.is_active == 1}9{else}12{/if}-custom{else}col-sm-{if $review.criterions|@count>0 && $review.is_active == 1}9{else}12{/if}-list-reviews{/if}">



                                {if $review.is_active == 1}

                                        {if $gsnipreviewtext_on == 1 && strlen($review.text_review)>0}

                                            <p class="spr-review-content-body">{$review.text_review|nl2br nofilter}</p>

                                        {/if}



                                        {if $gsnipreviewis_filesr == 1}

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

                                            {$review.admin_response|nl2br nofilter}

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

                                    {literal}

                                        <script type="text/javascript">



                                            document.addEventListener("DOMContentLoaded", function(event) {

                                            $(document).ready(function(){



                                                /* Voucher, when a user share review on the Facebook */

                                                // like

                                                FB.Event.subscribe("edge.create", function(targetUrlReview) {



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





	    </div>



</div>





