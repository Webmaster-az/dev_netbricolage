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


{capture name=path}
    {l s='Store Reviews' mod='gsnipreview'}
{/capture}

<h1 class="page-heading">{$meta_title|escape:'htmlall':'UTF-8'}</h1>

<div id="succes-review">
    {l s='Your review  has been successfully sent our team. Thanks for review!' mod='gsnipreview'}						
</div>

<div class="text-align-center margin-bottom-20" id="add_testimonials">
    <input type="button" onclick="show_testimonial_form()" value="{l s='Write a Review' mod='gsnipreview'}" class="btn-custom btn-primary-gsnipreview testimonials-add-btn" />
</div>
    
{if $gsnipreviewid_customerti == 0 && $gsnipreviewwhocanaddti == 'reg'}
    <div class="no-registered-ti"  id="text-before-add-testimonial-form">
        <div class="text-no-reg">
            {l s='You cannot post a review because you are not logged as a customer' mod='gsnipreview'}
        </div>

        <br/>

        <div class="no-reg-button">
            <a href="{if $gsnipreviewis_ps15 == 0}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{if $gsnipreviewis_rewrite == 1}{$gsnipreviewiso_lng|escape:'htmlall':'UTF-8'}/my-account{else}my-account.php{/if}{else}{$gsnipreviewaccount_url|escape:'htmlall':'UTF-8'}{/if}" class="btn-custom btn-primary-gsnipreview testimonials-add-btn" >{l s='Log in / sign up' mod='gsnipreview'}</a>
        </div>
    </div>
{elseif $gsnipreviewis_buyti == 0 && $gsnipreviewwhocanaddti == 'buy'}
    <div class="no-registered-ti"  id="text-before-add-testimonial-form">
        <div class="text-no-reg">
            {l s='Only registered users who already bought something in shop can add review.' mod='gsnipreview'}
        </div>
    </div>
{else}
    <b class="margin-5" id="text-before-add-testimonial-form">{l s='Send us Your review about an order or about our products & services.' mod='gsnipreview'}</b>
        
    <div id="add-testimonial-form" {if $gsnipreviewis17 == 1}class="block-categories"{/if}>
        <form method="post" enctype="multipart/form-data" id="gsnipreview_form" name="gsnipreview_form">
            <input type="hidden" name="action" value="addreview" />
            
            <div class="title-rev" id="idTab666-my">
                <div class="float-left">
                    {l s='Write a Review' mod='gsnipreview'}
                </div>

                <div class="clear"></div>
            </div>

            <div id="body-add-storereview-form-storereview">
                <label for="rat_rel" class="float-left">{l s='Rating' mod='gsnipreview'}<span class="testimonials-req">*</span></label>

                <span class="rat testimonials-stars rating-stars-dynamic-storereview">
                    <span onmouseout="read_rating_review_shop('rat_rel');">
                        <img  style='margin-left: -3px;' onmouseover="_rating_efect_rev(1,0,'rat_rel')" onmouseout="_rating_efect_rev(1,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',1); rating_checked=true; " src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="1"  id="img_rat_rel_1" />
                        <img  style='margin-left: -3px;' onmouseover="_rating_efect_rev(2,0,'rat_rel')" onmouseout="_rating_efect_rev(2,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',2); rating_checked=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="2"  id="img_rat_rel_2" />
                        <img  style='margin-left: -3px;' onmouseover="_rating_efect_rev(3,0,'rat_rel')" onmouseout="_rating_efect_rev(3,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',3); rating_checked=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="3"  id="img_rat_rel_3" />
                        <img  style='margin-left: -3px;' onmouseover="_rating_efect_rev(4,0,'rat_rel')" onmouseout="_rating_efect_rev(4,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',4); rating_checked=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="4"  id="img_rat_rel_4" />
                        <img  style='margin-left: -3px;' onmouseover="_rating_efect_rev(5,0,'rat_rel')" onmouseout="_rating_efect_rev(5,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',5); rating_checked=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="5"  id="img_rat_rel_5" />
                    </span>
                </span>

                <input type="hidden" id="rat_rel" name="rat_rel" value="0"/>

                <div class="clr"></div>

                <div class="errorTxtAdd" id="error_rat_rel"></div>

                <div class="clr"></div>

                <br/>

                <label for="name-review">{l s='Name' mod='gsnipreview'}<span class="testimonials-req">*</span></label>

                <input type="text" name="name-review" id="name-review" {if strlen($gsnipreviewname_cti)>0}value="{$gsnipreviewname_cti|escape:'htmlall':'UTF-8'}"{/if} class="testimonials-input" onkeyup="check_inpNameReview();" onblur="check_inpNameReview();" />

                <div class="errorTxtAdd" id="error_name-review"></div>

                <label for="email-review">{l s='Email' mod='gsnipreview'}<sup class="testimonials-req">*</sup></label>

                <input type="text" name="email-review" id="email-review" class="testimonials-input" {if strlen($gsnipreviewemail_cti)>0}value="{$gsnipreviewemail_cti|escape:'htmlall':'UTF-8'}"{/if} onkeyup="check_inpEmailReview();" onblur="check_inpEmailReview();" />

                <div class="errorTxtAdd" id="error_email-review"></div>

                {if $gsnipreviewis_avatar == 1}
                    <label for="avatar-review">{l s='Avatar' mod='gsnipreview'}</label>

                    {if strlen($gsnipreviewc_avatarti)>0}
                        <div class="avatar-block-rev-form">
                            <input type="radio" name="post_images" checked="" style="display: none">
                            <img src="{$gsnipreviewc_avatarti|escape:'htmlall':'UTF-8'}" alt="{$gsnipreviewname_cti|escape:'htmlall':'UTF-8'}" />
                        </div>
                    {/if}

                    <input type="file" name="avatar-review" id="avatar-review" class="testimonials-input" />

                    <div class="b-guide">
                        {l s='Allow formats' mod='gsnipreview'}: *.jpg; *.jpeg; *.png; *.gif.
                    </div>

                    <div class="errorTxtAdd" id="error_avatar-review"></div>
                {/if}

                {if $gsnipreviewis_web == 1}
                    <label>{l s='Web address:' mod='gsnipreview'}</label>
                    <input type="text" name="web-review" id="web-review" class="testimonials-input" />
                {/if}

                {if $gsnipreviewis_company == 1}
                    <label>{l s='Company' mod='gsnipreview'}</label>
                    <input type="text" name="company-review" id="company-review" class="testimonials-input" />
                {/if}

                {if $gsnipreviewis_addr == 1}
                    <label>{l s='Address' mod='gsnipreview'}</label>
                    <input type="text" name="address-review" id="address-review" class="testimonials-input" />
                {/if}

                {if $gsnipreviewis_country == 1}
                    <label>{l s='Country' mod='gsnipreview'}</label>
                    <input type="text" name="country-review" id="country-review" class="testimonials-input" />
                {/if}

                {if $gsnipreviewis_city == 1}
                    <label>{l s='City' mod='gsnipreview'}</label>
                    <input type="text" name="city-review" id="city-review" class="testimonials-input" />
                {/if}

                <label for="text-review">{l s='Message:' mod='gsnipreview'}<span class="testimonials-req">*</span></label>

                <textarea class="testimonials-textarea" id="text-review" name="text-review" onkeyup="check_inpMsgReview();" onblur="check_inpMsgReview();"></textarea>

                <div class="errorTxtAdd" id="error_text-review"></div>

                {if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customerti == 0}
                    <label for="inpCaptchaReview">{l s='Captcha' mod='gsnipreview'}<span class="testimonials-req">*</span></label>

                    <div class="clr"></div>

                    <img width="100" height="26" class="float-left" id="secureCodReview" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/captcha_store.php" alt="Captcha"/>

                    <input type="text" class="inpCaptchaReview float-left" id="inpCaptchaReview" size="6" name="captcha" onkeyup="check_inpCaptchaReview();" onblur="check_inpCaptchaReview();"/>

                    <div class="clr"></div>

                    <div id="error_inpCaptchaReview" class="errorTxtAdd"></div>
                {/if}
            </div>

            <div id="footer-add-review-form-review">
                <input type="submit" name="submit_gsnipreview" value="{l s='Submit your Review' mod='gsnipreview'}" class="btn-custom btn-success-custom testimonials-add-btn" />
            </div>
        </form>
    </div>

    {literal}
        <script type="text/javascript">
            {/literal}{if $gsnipreviewis17 == 1}{literal}
            var baseDir = '{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{literal}';
            {/literal}{/if}{literal}
            var module_dir = '{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/{literal}';
        </script>
    {/literal}

    {literal}
        <script type="text/javascript" src="{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{literal}modules/gsnipreview/views/js/r_stars.js"></script>
    {/literal}

    {literal}
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function(event) {
                jQuery(document).ready(init_rating);
            });
        </script>
    {/literal}

    {literal}
        <script type="text/javascript">
            var gsnipreview_star_active = '{/literal}{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}{literal}';
            var gsnipreview_star_noactive = '{/literal}{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}{literal}';
        </script>
    {/literal}

    {literal}
        <script type="text/javascript">
            var rating_checked = false;

            function check_inpRatingReview()
            {
                if(!rating_checked){
                    field_state_change_store('rat_rel','failed', '{/literal}{$gsnipreviewmsg1|escape:'htmlall':'UTF-8'}{literal}');
                    return false;
                }
                field_state_change_store('rat_rel','success', '');

                return true;
            }


            function check_inpNameReview()
            {
                var name_review = trim(document.getElementById('name-review').value);

                if (name_review.length == 0)
                {
                    field_state_change_store('name-review','failed', '{/literal}{$gsnipreviewmsg2|escape:'htmlall':'UTF-8'}{literal}');
                    return false;
                }
                field_state_change_store('name-review','success', '');

                return true;
            }

            function check_inpEmailReview()
            {

                var email_review = trim(document.getElementById('email-review').value);

                if (email_review.length == 0)
                {
                    field_state_change_store('email-review','failed', '{/literal}{$gsnipreviewmsg3|escape:'htmlall':'UTF-8'}{literal}');

                    return false;
                }
                field_state_change_store('email-review','success', '');

                return true;
            }

            function check_inpMsgReview()
            {
                var subject_review = trim(document.getElementById('text-review').value);

                if (subject_review.length == 0)
                {
                    field_state_change_store('text-review','failed', '{/literal}{$gsnipreviewmsg4|escape:'htmlall':'UTF-8'}{literal}');
                    
                    return false;
                }
                field_state_change_store('text-review','success', '');

                return true;
            }

            {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customerti == 0}{literal}

            function check_inpCaptchaReview()
            {
                var inpCaptchaReview = trim(document.getElementById('inpCaptchaReview').value);

                if (inpCaptchaReview.length != 6)
                {
                    field_state_change_store('inpCaptchaReview','failed', '{/literal}{$gsnipreviewmsg5|escape:'htmlall':'UTF-8'}{literal}');

                    return false;
                }
                field_state_change_store('inpCaptchaReview','success', '');
                
                return true;
            }

            {/literal}{/if}{literal}

            document.addEventListener("DOMContentLoaded", function(event) {

            $(document).ready(function (e) {
                $("#gsnipreview_form").on('submit',(function(e) {
                    {/literal}{if $gsnipreviewis_avatar == 1}{literal}
                        field_state_change_store('avatar-review','success', '');
                    {/literal}{/if}{literal}

                    var is_rating = check_inpRatingReview();

                    var is_name_review = check_inpNameReview();

                    var is_email_review = check_inpEmailReview();

                    var is_msg_review =check_inpMsgReview();

                    {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customerti == 0}{literal}

                    var is_captcha_review = check_inpCaptchaReview();

                    {/literal}{/if}{literal}

                    if(is_rating && is_name_review && is_email_review && is_msg_review
                        {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customerti == 0}{literal}
                        && is_captcha_review
                        {/literal}{/if}{literal}
                    ){
                        $('#add-review-form').css('opacity','0.5');
                        $('#footer-add-review-form-review input').attr('disabled','disabled');

                        e.preventDefault();

                        $.ajax({
                            url: baseDir + 'modules/gsnipreview/ajax.php',
                            type: "POST",
                            data:  new FormData(this),
                            contentType: false,
                            cache: false,
                            processData:false,
                            dataType: 'json',
                            success: function(data)
                            {
                                if (data.status == 'success') {
                                    $('#rat_rel').val('');



                                    $('#name-review').val('');



                                    $('#email-review').val('');



                                    $('#web-review').val('');







                                    $('#country-review').val('');



                                    $('#city-review').val('');







                                    $('#company-review').val('');



                                    $('#address-review').val('');



                                    $('#text-review').val('');



                                    $('#inpCaptchaReview').val('');







                                    $('#text-before-add-testimonial-form').hide();



                                    $('#add-testimonial-form').hide();







                                    $('#succes-review').show();















                                    {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customerti == 0}{literal}



                                    var count = Math.random();



                                    document.getElementById('secureCodReview').src = "";



                                    document.getElementById('secureCodReview').src = "{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/{literal}captcha_store.php?re=" + count;



                                    {/literal}{/if}{literal}











                                    $('#add-review-form').css('opacity','1');











                                } else {







                                    $('#footer-add-review-form-review input').removeAttr('disabled');



                                    var error_type = data.params.error_type;







                                    if(error_type == 1){



                                        field_state_change_store('name-review','failed', '{/literal}{$gsnipreviewmsg2|escape:'htmlall':'UTF-8'}{literal}');



                                        return false;



                                    } else if(error_type == 2){



                                        field_state_change_store('email-review','failed', '{/literal}{$gsnipreviewmsg6|escape:'htmlall':'UTF-8'}{literal}');



                                        return false;



                                    } else if(error_type == 3){



                                        field_state_change_store('text-review','failed', '{/literal}{$gsnipreviewmsg4|escape:'htmlall':'UTF-8'}{literal}');



                                        return false;



                                    } else if(error_type == 8){



                                        field_state_change_store('avatar-review','failed', '{/literal}{$gsnipreviewmsg8|escape:'htmlall':'UTF-8'}{literal}');



                                        return false;



                                    } else if(error_type == 9){



                                        field_state_change_store('avatar-review','failed', '{/literal}{$gsnipreviewmsg9|escape:'htmlall':'UTF-8'}{literal}');



                                        return false;



                                    }



                                            {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customerti == 0}{literal}



                                    else if(error_type == 4){



                                        field_state_change_store('inpCaptchaReview','failed', '{/literal}{$gsnipreviewmsg7|escape:'htmlall':'UTF-8'}{literal}');



                                        var count = Math.random();



                                        document.getElementById('secureCodReview').src = "";



                                        document.getElementById('secureCodReview').src = "{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/{literal}captcha_store.php?re=" + count;



                                        return false;



                                    }



                                            {/literal}{/if}{literal}



                                    else {



                                        alert(data.message);



                                        return false;



                                    }







                                    {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customerti == 0}{literal}



                                    var count = Math.random();



                                    document.getElementById('secureCodReview').src = "";



                                    document.getElementById('secureCodReview').src = "{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/{literal}captcha_store.php?re=" + count;



                                    {/literal}{/if}{literal}







                                    $('#add-review-form').css('opacity','1');







                                }



                            }







                        });







                    } else {



                        return false;



                    }







                }));











            });



            });























        </script>



    {/literal}











    {/if}



























<div class="testimonials-items {if $gsnipreviewis17 == 1}block-categories{/if}">



































			











    <div class="row-custom total-info-tool">



            <div class="col-sm-6-custom first-block-ti">















					<strong class="float-left">



                        <span class="testimonials-count-items">{$gsnipreviewcount_reviewsti|escape:'htmlall':'UTF-8'}</span>



                        {l s='Store Reviews' mod='gsnipreview'}



                     </strong>







                <span class="separator-items-block float-left">-</span>











                <div {if $gsnipreviewt_tpages == 1}itemscope itemtype="http://schema.org/corporation"{/if} class="float-left total-rating-items-block">







                    {if $gsnipreviewt_tpages == 1}



                        <meta itemprop="name" content="{$gsnipreviewsh_nameti|escape:'htmlall':'UTF-8'}">



                        <meta itemprop="url" content="{$gsnipreviewsh_urlti|escape:'htmlall':'UTF-8'}">



                    {/if}











                <div {if $gsnipreviewt_tpages == 1}itemtype="http://schema.org/AggregateRating" itemscope="" itemprop="aggregateRating"{/if}>







                    {if $gsnipreviewt_tpages == 1}



                        <meta itemprop="reviewCount" content="{$gsnipreviewcount_reviewsti|escape:'htmlall':'UTF-8'}">



                    {/if}











                        {section name=bar loop=5 start=0}



                            {if $smarty.section.bar.index < $gsnipreviewavg_ratingti}



                                <i style="color: #ffa852;font-size: 12px;" class="fas fa-star"></i>

                            {else}



                                <i style="color: #ffa852;font-size: 12px;"class="far fa-star"></i>

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



      







        </div>



















    <div class="row-custom filter-testimonials {if $gsnipreviewis16 == 0}filter-testimonials-14{/if}">







            <div class="col-sm-1-custom">



                <b class="filter-txt-items-block">{l s='Filter' mod='gsnipreview'}:</b>



            </div>



            <div class="col-sm-2-custom {if isset($gsnipreviewfratti) && $gsnipreviewfratti == 5}active-items-block{/if}">



                {if $gsnipreviewfiveti>0}



                <a rel="nofollow" href="{$gsnipreviewstorereviews_url|escape:'htmlall':'UTF-8'}?fratti=5{if $gsnipreviewis_searchti == 1}&searchti={$gsnipreviewsearchti|escape:'quotes':'UTF-8'}{/if}">



                {/if}



                {section name="test" loop=5}



                    <i style="color: #ffa852;font-size: 12px;" class="fas fa-star"></i>

                {/section}



                <span class="count-items-block {if $gsnipreviewfiveti==0}text-decoration-none{/if}">({$gsnipreviewfiveti|escape:'htmlall':'UTF-8'})</span>



                {if $gsnipreviewfiveti>0}



                </a>



                {/if}



            </div>



            <div class="col-sm-2-custom {if isset($gsnipreviewfratti) && $gsnipreviewfratti == 4}active-items-block{/if}">



                {if $gsnipreviewfourti>0}



                <a rel="nofollow" href="{$gsnipreviewstorereviews_url|escape:'htmlall':'UTF-8'}?fratti=4{if $gsnipreviewis_searchti == 1}&searchti={$gsnipreviewsearchti|escape:'quotes':'UTF-8'}{/if}">



                {/if}



                    {section name="test" loop=4}



                        <i style="color: #ffa852;font-size: 12px;" class="fas fa-star"></i>

                    {/section}



                    {section name="test" loop=1}



                        <i style="color: #ffa852;font-size: 12px;"class="far fa-star"></i>

                    {/section}







                <span class="count-items-block {if $gsnipreviewfourti==0}text-decoration-none{/if}">({$gsnipreviewfourti|escape:'htmlall':'UTF-8'})</span>



                {if $gsnipreviewfourti>0}



                </a>



                {/if}



            </div>



            <div class="col-sm-2-custom {if isset($gsnipreviewfratti) && $gsnipreviewfratti == 3}active-items-block{/if}">



                {if $gsnipreviewthreeti>0}



                <a rel="nofollow" href="{$gsnipreviewstorereviews_url|escape:'htmlall':'UTF-8'}?fratti=3{if $gsnipreviewis_searchti == 1}&searchti={$gsnipreviewsearchti|escape:'quotes':'UTF-8'}{/if}">



                {/if}



                    {section name="test" loop=3}



                        <i style="color: #ffa852;font-size: 12px;" class="fas fa-star"></i>

                    {/section}



                    {section name="test" loop=2}



                        <i style="color: #ffa852;font-size: 12px;"class="far fa-star"></i>

                    {/section}



                    <span class="count-items-block {if $gsnipreviewthreeti==0}text-decoration-none{/if}">({$gsnipreviewthreeti|escape:'htmlall':'UTF-8'})</span>



                {if $gsnipreviewthreeti>0}



                </a>



                {/if}



            </div>



            <div class="col-sm-2-custom {if isset($gsnipreviewfratti) && $gsnipreviewfratti == 2}active-items-block{/if}">



                {if $gsnipreviewtwoti>0}



                <a rel="nofollow" href="{$gsnipreviewstorereviews_url|escape:'htmlall':'UTF-8'}?fratti=2{if $gsnipreviewis_searchti == 1}&searchti={$gsnipreviewsearchti|escape:'quotes':'UTF-8'}{/if}">



                {/if}



                    {section name="test" loop=2}



                        <i style="color: #ffa852;font-size: 12px;" class="fas fa-star"></i>

                    {/section}



                    {section name="test" loop=3}



                        <i style="color: #ffa852;font-size: 12px;"class="far fa-star"></i>

                    {/section}







                    <span class="count-items-block {if $gsnipreviewtwoti==0}text-decoration-none{/if}">({$gsnipreviewtwoti|escape:'htmlall':'UTF-8'})</span>



                {if $gsnipreviewtwoti>0}



                </a>



                {/if}



            </div>



            <div class="col-sm-2-custom {if isset($gsnipreviewfratti) && $gsnipreviewfratti == 1}active-items-block{/if}">



                {if $gsnipreviewoneti>0}



                <a rel="nofollow" href="{$gsnipreviewstorereviews_url|escape:'htmlall':'UTF-8'}?fratti=1{if $gsnipreviewis_searchti == 1}&searchti={$gsnipreviewsearchti|escape:'quotes':'UTF-8'}{/if}">



                {/if}



                    {section name="test" loop=1}



                        <i style="color: #ffa852;font-size: 12px;" class="fas fa-star"></i>

                    {/section}



                    {section name="test" loop=4}



                        <i style="color: #ffa852;font-size: 12px;"class="far fa-star"></i>

                    {/section}



                    <span class="count-items-block {if $gsnipreviewoneti==0}text-decoration-none{/if}">({$gsnipreviewoneti|escape:'htmlall':'UTF-8'})</span>



                {if $gsnipreviewoneti>0}



                </a>



                {/if}



            </div>







        {if $gsnipreviewfratti}



            <div class="col-sm-1-custom">



                <a rel="nofollow" href="{$gsnipreviewstorereviews_url|escape:'htmlall':'UTF-8'}" class="reset-items-block">



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







<div  class="productsBox1">



{foreach from=$reviewsti item=review name=myLoop}



    <div {if $gsnipreviewt_tpages == 1}itemprop="review" itemscope itemtype="http://schema.org/Review"{/if}>



	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="productsTable compareTableNew {if $gsnipreviewis16==1}float-left-table16{/if}">



		<tbody>



			<tr class="line1">



            {if $gsnipreviewis_avatar == 1 && $review.is_show_ava}



                <td class="post_avatar">



                    <img



                            {if strlen($review.avatar)>0}



                            src="{$review.avatar|escape:'htmlall':'UTF-8'}"



                            {else}



                            src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/avatar_m.gif"



                            {/if}



                         alt="{$review.name|escape:'htmlall':'UTF-8'}"



                        />







                </td>



            {/if}



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



				<span class="foot_center margin-top-10"><b {if $gsnipreviewt_tpages == 1}itemprop="author"{/if}



                            >{if $gsnipreviewis_uprof  && $review.is_show_ava && $review.id_customer > 0}<a href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$review.id_customer|escape:'htmlall':'UTF-8'}" title="{$review.name|escape:'htmlall':'UTF-8'}" class="user-link-to-profile">{/if}{$review.name}{if $gsnipreviewis_uprof && $review.id_customer > 0}</a>{/if}</b>{if $gsnipreviewt_tpages == 1}<meta



                    itemprop="name" content="{$review.name}"/>{/if}{if $gsnipreviewis_country == 1}{if strlen($review.country)>0}, <span



                            class="fs-12">{$review.country}</span>{/if}{/if}{if $gsnipreviewis_city == 1}{if strlen($review.city)>0}, <span class="fs-12">{$review.city}</span>{/if}{/if}







                {if $review.rating != 0}



                        {*{for $foo=0 to 4}*}



                        {section name=bar loop=5 start=0}



                            {if $smarty.section.bar.index < $review.rating}



                                <i style="color: #ffa852;font-size: 12px;" class="fas fa-star"></i>

					 	    {else}



							    <i style="color: #ffa852;font-size: 12px;"class="far fa-star"></i>

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







				<span class="foot_center">{$review.date_add|date_format|escape:'htmlall':'UTF-8'}</span>



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



            {*{$pagingti|escape:'quotes':'UTF-8'}*}



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