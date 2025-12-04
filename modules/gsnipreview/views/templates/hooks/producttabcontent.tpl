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



{if $gsnipreviewtitle_on == 1 || $gsnipreviewtext_on == 1 || $gsnipreviewratings_on == 1}



<div id="idTab777" class="pc_avisprodpagetab">



{if $gsnipreviewptabs_type == 1}



    {if $gsnipreviewrvis_on == 1}



        {if $gsnipreviewtitle_on == 1 || $gsnipreviewtext_on == 1 || $gsnipreviewratings_on == 1}



            <h3 class="page-product-heading" id="#idTab777">
                    &nbsp;{l s='Reviews' mod='gsnipreview'}
                    <span id="count-review-tab">
                    {$gsnipreviewcount_reviews|escape:'htmlall':'UTF-8'}
                    </span>
            </h3>



        {/if}



    {/if}



{/if}



<!-- reviews template -->



    <div id="shopify-product-reviews">



        <div class="spr-container {if $gsnipreviewis16 == 1}row-custom{/if}">





            <div class="spr-header spr-summary {if $gsnipreviewis16 == 1}col-sm-3-custom-product-page{else}spr-summary15{/if}">



                      <span class="spr-starrating spr-summary-starrating">



                            <b class="total-rating-review">
                                {l s='Total rating' mod='gsnipreview'}:
                                <span>{$gsnipreviewavg_decimal|escape:'htmlall':'UTF-8'}</span> / <span>5</span>
                            </b>

                            <br/>

                          {section name=ratid loop=5}

                              {if $smarty.section.ratid.index < $gsnipreviewavg_rating}

                                  <i style="color: #ffa852;" class="fas fa-star"></i>

                              {else}

                                  <i style="color: #ffa852;"class="far fa-star"></i>

                              {/if}

                          {/section}





                      </span>







                      <span class="spr-summary-caption">

                          <span class="spr-summary-actions-togglereviews">

                              {l s='Based on' mod='gsnipreview'} <span class="font-weight-bold">{$gsnipreviewcount_reviews|escape:'htmlall':'UTF-8'}</span> {$gsnipreviewtext_reviews|escape:'htmlall':'UTF-8'}

                          </span>

                      </span>









                <div class="row-custom filter-reviews-gsnipreview {if $gsnipreviewis16 == 0}filter-testimonials-14{/if} product-reviews-filter-block">



                    <div class="col-sm-12-custom">

                        <b class="filter-txt-items-block">{l s='Filter' mod='gsnipreview'}:</b>

                    </div>

                    <div class="col-sm-12-custom {if isset($gsnipreviewfrat) && $gsnipreviewfrat == 5}active-items-block{/if}">

                        {if $gsnipreviewfive>0}

                        <a rel="nofollow" href="{$gsnipreviewproduct_url|escape:'html':'UTF-8'}?frat=5{if $gsnipreviewis_search == 1}&search={$gsnipreviewsearch|escape:'quotes':'UTF-8'}{/if}">

                            {/if}

                            {section name="test" loop=5}
                                <i style="color: #ffa852;" class="fas fa-star"></i>
                            {/section}

                            <span class="count-items-block {if $gsnipreviewfive==0}text-decoration-none{/if}">({$gsnipreviewfive|escape:'htmlall':'UTF-8'})</span>

                            {if $gsnipreviewfive>0}

                        </a>

                        {/if}

                    </div>

                    <div class="col-sm-12-custom {if isset($gsnipreviewfrat) && $gsnipreviewfrat == 4}active-items-block{/if}">

                        {if $gsnipreviewfour>0}

                        <a rel="nofollow" href="{$gsnipreviewproduct_url|escape:'html':'UTF-8'}?frat=4{if $gsnipreviewis_search == 1}&search={$gsnipreviewsearch|escape:'quotes':'UTF-8'}{/if}">

                            {/if}

                            {section name="test" loop=4}

                                <i style="color: #ffa852;" class="fas fa-star"></i>
                            {/section}

                            {section name="test" loop=1}

                                <i style="color: #ffa852;"class="far fa-star"></i>
                            {/section}



                            <span class="count-items-block {if $gsnipreviewfour==0}text-decoration-none{/if}">({$gsnipreviewfour|escape:'htmlall':'UTF-8'})</span>

                            {if $gsnipreviewfour>0}

                        </a>

                        {/if}

                    </div>

                    <div class="col-sm-12-custom {if isset($gsnipreviewfrat) && $gsnipreviewfrat == 3}active-items-block{/if}">

                        {if $gsnipreviewthree>0}

                        <a rel="nofollow" href="{$gsnipreviewproduct_url|escape:'html':'UTF-8'}?frat=3{if $gsnipreviewis_search == 1}&search={$gsnipreviewsearch|escape:'quotes':'UTF-8'}{/if}">

                            {/if}

                            {section name="test" loop=3}

                                <i style="color: #ffa852;" class="fas fa-star"></i>
                            {/section}

                            {section name="test" loop=2}

                                <i style="color: #ffa852;"class="far fa-star"></i>
                            {/section}

                            <span class="count-items-block {if $gsnipreviewthree==0}text-decoration-none{/if}">({$gsnipreviewthree|escape:'htmlall':'UTF-8'})</span>

                            {if $gsnipreviewthree>0}

                        </a>

                        {/if}

                    </div>

                    <div class="col-sm-12-custom {if isset($gsnipreviewfrat) && $gsnipreviewfrat == 2}active-items-block{/if}">

                        {if $gsnipreviewtwo>0}

                        <a href="{$gsnipreviewproduct_url|escape:'html':'UTF-8'}?frat=2{if $gsnipreviewis_search == 1}&search={$gsnipreviewsearch|escape:'quotes':'UTF-8'}{/if}">

                            {/if}

                            {section name="test" loop=2}

                                <i style="color: #ffa852;" class="fas fa-star"></i>
                            {/section}

                            {section name="test" loop=3}

                                <i style="color: #ffa852;"class="far fa-star"></i>
                            {/section}



                            <span class="count-items-block {if $gsnipreviewtwo==0}text-decoration-none{/if}">({$gsnipreviewtwo|escape:'htmlall':'UTF-8'})</span>

                            {if $gsnipreviewtwo>0}

                        </a>

                        {/if}

                    </div>

                    <div class="col-sm-12-custom {if isset($gsnipreviewfrat) && $gsnipreviewfrat == 1}active-items-block{/if}">

                        {if $gsnipreviewone>0}

                        <a rel="nofollow" href="{$gsnipreviewproduct_url|escape:'html':'UTF-8'}?frat=1{if $gsnipreviewis_search == 1}&search={$gsnipreviewsearch|escape:'quotes':'UTF-8'}{/if}">

                            {/if}

                            {section name="test" loop=1}

                                <i style="color: #ffa852;" class="fas fa-star"></i>
                            {/section}

                            {section name="test" loop=4}

                                <i style="color: #ffa852;"class="far fa-star"></i>
                            {/section}

                            <span class="count-items-block {if $gsnipreviewone==0}text-decoration-none{/if}">({$gsnipreviewone|escape:'htmlall':'UTF-8'})</span>

                            {if $gsnipreviewone>0}

                        </a>

                        {/if}

                    </div>



                    {if $gsnipreviewfrat}

                        <div class="col-sm-12-custom">

                            <a rel="nofollow" href="{$gsnipreviewproduct_url|escape:'html':'UTF-8'}" class="reset-items-block">

                                <i class="fa fa-refresh"></i>{l s='Reset' mod='gsnipreview'}

                            </a>

                        </div>

                    {/if}





                </div>



            </div>









            <div class="{if $gsnipreviewis16 == 1}spr-content col-sm-9-custom{else}spr-content15{/if}">









                {literal}

                <script type="text/javascript">

                    var module_dir = '{/literal}{$module_dir|escape:'htmlall':'UTF-8'}{literal}';

                    var gsnipreview_star_active = '{/literal}{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}{literal}';

                    var gsnipreview_star_noactive = '{/literal}{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}{literal}';

                </script>

                {/literal}







                {if $gsnipreviewis15 == 0}

                {literal}

                    <script type="text/javascript" src="{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/{literal}views/js/r_stars.js"></script>

                {/literal}

                {/if}

                {literal}

                    <script type="text/javascript">

                        {/literal}{if $gsnipreviewis17 == 1}{literal}document.addEventListener("DOMContentLoaded", function(event) { {/literal}{/if}{literal}

                            jQuery(document).ready(init_rating);



                            $("#idTab777-my-click").click(function() {

                                $('.total-info-tool-product-page .btn-gsnipreview').parent().hide();

                            });



                        {/literal}{if $gsnipreviewis17 == 1}{literal}}); {/literal}{/if}{literal}



                        function show_form_review(par){



                            $('#add-review-block').toggle();

                            $('#no-customers-reviews').toggle();



                            if(par == 1){

                                $('.total-info-tool-product-page .btn-gsnipreview').parent().hide();

                            } else {

                                $('.total-info-tool-product-page .btn-gsnipreview').parent().show();

                            }

                        }









                    </script>

                {/literal}





                <div id="add-review-block" style="display: none">

                {if $gsnipreviewid_customer == 0 && $gsnipreviewwhocanadd == 'reg'}

                    <div class="no-registered">

                        <div class="text-no-reg">

                            {l s='You cannot post a review because you are not logged as a customer' mod='gsnipreview'}

                        </div>

                        <br/>

                        <div class="no-reg-button">

                            <a href="{if $gsnipreviewis_ps15 == 0}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{if $gsnipreviewis_rewrite == 1}{$gsnipreviewiso_lang|escape:'htmlall':'UTF-8'}/my-account{else}my-account.php{/if}{else}{$gsnipreviewm_acc|escape:'htmlall':'UTF-8'}{/if}"

                               class="btn-gsnipreview btn-primary-gsnipreview" >{l s='Log in / sign up' mod='gsnipreview'}</a>

                        </div>



                    </div>

                {elseif $gsnipreviewis_buy == 0 && $gsnipreviewwhocanadd == 'buy'}

                    <div class="no-registered">

                        <div class="text-no-reg">

                            {l s='Only users who already bought the product can add review.' mod='gsnipreview'}

                        </div>

                    </div>

                {else}



                        {if $gsnipreviewis_add == 1}



                            <div class="advertise-text-review">

                                {l s='You have already add review for this product' mod='gsnipreview'}

                            </div>



                        {else}









                            {* voucher suggestions *}

                            {if $gsnipreviewvis_on == 1}

                                <div class="advertise-text-review">

                                <span>

                                    <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}"

                                            alt="{l s='Write a review and get voucher for discount' mod='gsnipreview'}" />

                                    {l s='Write a review and get voucher for discount' mod='gsnipreview'}

                                    <b>{$gsnipreviewdiscount|escape:'htmlall':'UTF-8'}</b> {if $gsnipreviewvaluta != '%'}<b>({if $gsnipreviewtax == 1}{l s='Tax Included' mod='gsnipreview'}{else}{l s='Tax Excluded' mod='gsnipreview'}{/if})</b>{/if}

                                    {if $gsnipreviewis_show_min == 1 && $gsnipreviewisminamount}

                                        <b>({l s='Minimum amount' mod='gsnipreview'} : {$gsnipreviewminamount|escape:'htmlall':'UTF-8'} {$gsnipreviewcurtxt|escape:'htmlall':'UTF-8'})</b>

                                    {/if}

                                    ,

                                    {l s='valid for' mod='gsnipreview'} {$gsnipreviewsdvvalid|escape:'htmlall':'UTF-8'} {$gsnipreviewdays|escape:'htmlall':'UTF-8'}

                                </span>

                                </div>

                            {/if}





                            {if $gsnipreviewvis_onfb == 1}

                                <br/>

                                <div class="advertise-text-review" id="facebook-share-review-block">

                                <span>

                                    <img width="16" height="16" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/btn/ico-facebook.png"

                                            alt="{l s='Share your review on Facebook and get voucher for discount' mod='gsnipreview'}"/>

                                    {l s='Share your review on Facebook and get voucher for discount' mod='gsnipreview'}

                                    <b>{$gsnipreviewdiscountfb|escape:'htmlall':'UTF-8'}</b> {if $gsnipreviewvalutafb != '%'}<b>({if $gsnipreviewtaxfb == 1}{l s='Tax Included' mod='gsnipreview'}{else}{l s='Tax Excluded' mod='gsnipreview'}{/if})</b>{/if}



                                    {if $gsnipreviewis_show_minfb == 1 && $gsnipreviewisminamountfb}

                                        <b>({l s='Minimum amount' mod='gsnipreview'} : {$gsnipreviewminamountfb|escape:'htmlall':'UTF-8'} {$gsnipreviewcurtxtfb|escape:'htmlall':'UTF-8'})</b>

                                    {/if}



                                    ,

                                    {l s='valid for' mod='gsnipreview'} {$gsnipreviewsdvvalidfb|escape:'htmlall':'UTF-8'} {$gsnipreviewdaysfb|escape:'htmlall':'UTF-8'}

                                </span>

                                </div>

                            {/if}

                            {* voucher suggestions *}

                            {** **}


                            


                                






                {literal}

                    <script type="text/javascript">



                        {/literal}{if $gsnipreviewis17 == 1}{literal}

                        var baseDir = '{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{literal}';

                        {/literal}{/if}{literal}



                        var file_upload_url_gsnipreview = baseDir + 'modules/gsnipreview/upload.php';

                        var file_max_files_gsnipreview = {/literal}{$gsnipreviewruploadfiles|escape:'htmlall':'UTF-8'}{literal};

                        var file_max_message_gsnipreview = '{/literal}{$gsnipreviewptc_msg13_1|escape:'htmlall':'UTF-8'}{literal} '+file_max_files_gsnipreview+' {/literal}{$gsnipreviewptc_msg13_2|escape:'htmlall':'UTF-8'}{literal}';

                        var file_path_upload_url_gsnipreview = baseDir + '{/literal}{$gsnipreviewfpath|escape:'htmlall':'UTF-8'}{literal}tmp/';



                        var text_min = {/literal}{$gsnipreviewrminc|escape:'htmlall':'UTF-8'}{literal};



                        {/literal}{if $gsnipreviewis17 == 1}{literal}document.addEventListener("DOMContentLoaded", function(event) { {/literal}{/if}{literal}

                        $(document).ready(function(){





                            {/literal}{if $gsnipreviewtext_on == 1}{literal}

                            $('#textarea_feedback').html($('#text-review').val().length + ' {/literal}{$gsnipreviewptc_msg11|escape:'htmlall':'UTF-8'}{literal}. {/literal}{$gsnipreviewptc_msg12|escape:'htmlall':'UTF-8'}{literal} '+text_min+' {/literal}{$gsnipreviewptc_msg11|escape:'htmlall':'UTF-8'}{literal}');



                            $('#text-review').keyup(function() {

                                var text_length_val = trim(document.getElementById('text-review').value);

                                var text_length = text_length_val.length;



                                if(text_length<text_min)

                                    $('#textarea_feedback').css('color','red');

                                else

                                    $('#textarea_feedback').css('color','green');



                                $('#textarea_feedback').html(text_length + ' {/literal}{$gsnipreviewptc_msg11|escape:'htmlall':'UTF-8'}{literal}. {/literal}{$gsnipreviewptc_msg12|escape:'htmlall':'UTF-8'}{literal} '+text_min+' {/literal}{$gsnipreviewptc_msg11|escape:'htmlall':'UTF-8'}{literal}');

                            });



                            {/literal}{/if}{literal}



                            /* clear form fields */



                            {/literal}{if $gsnipreviewratings_on == 1}{literal}



                            {/literal}{if $gsnipreviewcriterions|@count > 0}



                            {foreach from=$gsnipreviewcriterions item='criterion'}{literal}



                            $('#rat_rel{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}').val(0);



                            {/literal}{/foreach}



                            {else}{literal}



                            $('#rat_rel').val(0);



                            {/literal}{/if}{literal}



                            {/literal}{/if}{literal}



                            {/literal}{if $gsnipreviewid_customer == 0}{literal}

                            $('#name-review').val('');

                            $('#email-review').val('');

                            {/literal}{/if}{literal}



                            {/literal}{if $gsnipreviewtitle_on == 1}{literal}

                            $('#subject-review').val('');

                            {/literal}{/if}{literal}

                            {/literal}{if $gsnipreviewtext_on == 1}{literal}

                            $('#text-review').val('');

                            {/literal}{/if}{literal}

                            {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customer == 0}{literal}

                            $('#inpCaptchaReview').val('');

                            {/literal}{/if}{literal}



                            /* clear form fields */

                        });



                        {/literal}{if $gsnipreviewis17 == 1}{literal}}); {/literal}{/if}{literal}









                        {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customer == 0}{literal}

                        function check_inpCaptchaReview()

                        {



                            var inpCaptchaReview = trim(document.getElementById('inpCaptchaReview').value);



                            if (inpCaptchaReview.length != 6)

                            {

                                field_state_change('inpCaptchaReview','failed', '{/literal}{$gsnipreviewptc_msg1|escape:'htmlall':'UTF-8'}{literal}');

                                return false;

                            }

                            field_state_change('inpCaptchaReview','success', '');

                            return true;

                        }

                        {/literal}{/if}{literal}





                        {/literal}{if $gsnipreviewtext_on == 1}{literal}

                        function check_inpTextReview()

                        {



                            var text_review = trim(document.getElementById('text-review').value);



                            if (text_review.length == 0 || text_review.length<text_min)

                            {

                                field_state_change('text-review','failed', '{/literal}{$gsnipreviewptc_msg2|escape:'htmlall':'UTF-8'}{literal}');

                                return false;

                            }

                            field_state_change('text-review','success', '');

                            return true;

                        }

                        {/literal}{/if}{literal}





                        function check_inpNameReview()

                        {



                            var name_review = trim(document.getElementById('name-review').value);



                            if (name_review.length == 0)

                            {

                                field_state_change('name-review','failed', '{/literal}{$gsnipreviewptc_msg3|escape:'htmlall':'UTF-8'}{literal}');

                                return false;

                            }

                            field_state_change('name-review','success', '');

                            return true;

                        }





                        function check_inpEmailReview()

                        {



                            var email_review = trim(document.getElementById('email-review').value);



                            if (email_review.length == 0)

                            {

                                field_state_change('email-review','failed', '{/literal}{$gsnipreviewptc_msg4|escape:'htmlall':'UTF-8'}{literal}');

                                return false;

                            }

                            field_state_change('email-review','success', '');

                            return true;

                        }





                        {/literal}{if $gsnipreviewtitle_on == 1}{literal}

                        function check_inpSubjectReview()

                        {



                            var subject_review = trim(document.getElementById('subject-review').value);



                            if (subject_review.length == 0)

                            {

                                field_state_change('subject-review','failed', '{/literal}{$gsnipreviewptc_msg5|escape:'htmlall':'UTF-8'}{literal}');

                                return false;

                            }

                            field_state_change('subject-review','success', '');

                            return true;

                        }

                        {/literal}{/if}{literal}











                        {/literal}{if $gsnipreviewratings_on == 1}{literal}







                        {/literal}{if $gsnipreviewcriterions|@count > 0}



                        {foreach from=$gsnipreviewcriterions item='criterion'}{literal}



                        var rating_checked{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal} = false;



                        {/literal}{/foreach}



                        {else}{literal}



                        var rating_checked = false;



                        {/literal}{/if}{literal}











                        {/literal}{if $gsnipreviewcriterions|@count > 0}







                        {foreach from=$gsnipreviewcriterions item='criterion'}{literal}



                        function check_inpRatingReview{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}()

                        {



                            if(!rating_checked{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}){

                                field_state_change('rat_rel{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}','failed', '{/literal}{$gsnipreviewptc_msg6|escape:'htmlall':'UTF-8'} {$criterion.name|escape:'htmlall':'UTF-8'}{literal}');

                                return false;

                            }

                            field_state_change('rat_rel{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}','success', '');

                            return true;





                        }

                        {/literal}{/foreach}



                        {else}{literal}

                        function check_inpRatingReview()

                        {

                            if(!rating_checked){

                                field_state_change('rat_rel','failed', '{/literal}{$gsnipreviewptc_msg7|escape:'htmlall':'UTF-8'}{literal}');

                                return false;

                            }

                            field_state_change('rat_rel', 'success', '');

                            return true;



                        }





                        {/literal}{/if}{literal}



                        {/literal}{/if}{literal}





                        {/literal}{if $gsnipreviewis17 == 1}{literal}document.addEventListener("DOMContentLoaded", function(event) { {/literal}{/if}{literal}

                        $(document).ready(function (e) {

                            $("#add_review_item_form").on('submit',(function(e) {







                                {/literal}{if $gsnipreviewis_avatarr == 1}{literal}

                                 field_state_change('avatar-review','success', '');

                                {/literal}{/if}{literal}









                                {/literal}{if $gsnipreviewratings_on == 1}{literal}

                                {/literal}{if $gsnipreviewcriterions|@count > 0}



                                {foreach from=$gsnipreviewcriterions item='criterion'}{literal}



                                var is_rating{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal} = check_inpRatingReview{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}();



                                {/literal}{/foreach}{literal}



                                {/literal}{else}{literal}



                                var is_rating = check_inpRatingReview();



                                {/literal}{/if}{literal}

                                {/literal}{/if}{literal}



                                {/literal}{if $gsnipreviewtitle_on == 1}{literal}

                                var is_subject = check_inpSubjectReview();

                                {/literal}{/if}{literal}



                                var is_name = check_inpNameReview();

                                var is_email = check_inpEmailReview();



                                {/literal}{if $gsnipreviewtext_on == 1}{literal}

                                var is_text =  check_inpTextReview();

                                {/literal}{/if}{literal}

                                {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customer == 0}{literal}

                                var is_captcha = check_inpCaptchaReview();

                                {/literal}{/if}{literal}





                                if(

                                        {/literal}{if $gsnipreviewratings_on == 1}{literal}

                                        {/literal}{if $gsnipreviewcriterions|@count > 0}



                                        {foreach from=$gsnipreviewcriterions item='criterion'}{literal}



                                        is_rating{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal} &&



                                        {/literal}{/foreach}{literal}



                                        {/literal}{else}{literal}



                                        is_rating &&



                                        {/literal}{/if}{literal}

                                        {/literal}{/if}{literal}



                                        {/literal}{if $gsnipreviewtitle_on == 1}{literal}

                                        is_subject &&

                                        {/literal}{/if}{literal}

                                        is_name &&

                                        is_email &&

                                        {/literal}{if $gsnipreviewtext_on == 1}{literal}

                                        is_text &&

                                        {/literal}{/if}{literal}

                                        {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customer == 0}{literal}

                                        is_captcha &&

                                        {/literal}{/if}{literal}

                                        true

                                ){



                                    $('#reviews-list').css('opacity',0.5);

                                    $('#add-review-form-review').css('opacity',0.5);

                                    $('#footer-add-review-form-review button').attr('disabled','disabled');



                                    e.preventDefault();

                                    $.ajax({

                                        url: baseDir + 'modules/gsnipreview/reviews.php',

                                        type: "POST",

                                        data:  new FormData(this),

                                        contentType: false,

                                        cache: false,

                                        processData:false,

                                        dataType: 'json',

                                        success: function(data)

                                        {





                                            $('#reviews-list').css('opacity',1);

                                            $('#add-review-form-review').css('opacity',1);



                                            if (data.status == 'success') {









                                                $('#gsniprev-list').html('');

                                                var paging = $('#gsniprev-list').prepend(data.params.content);

                                                $(paging).hide();

                                                $(paging).fadeIn('slow');



                                                $('#gsniprev-nav').html('');

                                                var paging = $('#gsniprev-nav').prepend(data.params.paging);

                                                $(paging).hide();

                                                $(paging).fadeIn('slow');





                                                var count_review = data.params.count_reviews;



                                                $('#count-review-tab').html('');

                                                $('#count-review-tab').html('('+count_review+')');











                                                {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customer == 0}{literal}



                                                var count = Math.random();

                                                document.getElementById('secureCodReview').src = "";

                                                document.getElementById('secureCodReview').src = "{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/{literal}captcha.php?re=" + count;

                                                $('#inpCaptchaReview').val('');



                                                {/literal}{/if}{literal}



                                                jQuery(document).ready(init_rating);







                                                $('.advertise-text-review').css('opacity','0.2');

                                                $('#add-review-block').css('opacity','0.2');



                                                var voucher_html_suggestion = data.params.voucher_html_suggestion;





                                                {/literal}{if $gsnipreviewvis_on == 1}{literal}

                                                /* voucher */



                                                var voucher_html = data.params.voucher_html;







                                                if ($('div#fb-con-wrapper').length == 0)

                                                {

                                                    conwrapper = '<div id="fb-con-wrapper" class="voucher-data"><\/div>';

                                                    $('body').append(conwrapper);

                                                } else {

                                                    $('#fb-con-wrapper').html('');

                                                }



                                                if ($('div#fb-con').length == 0)

                                                {

                                                    condom = '<div id="fb-con"><\/div>';

                                                    $('body').append(condom);

                                                }



                                                $('div#fb-con').fadeIn(function(){



                                                    $(this).css('filter', 'alpha(opacity=70)');

                                                    $(this).bind('click dblclick', function(){

                                                        $('div#fb-con-wrapper').hide();

                                                        $(this).fadeOut();

                                                        showSocialSuggestion(voucher_html_suggestion);



                                                    });

                                                });



                                                $('div#fb-con-wrapper').html('<a id="button-close" style="display: inline;"><\/a>'+voucher_html).fadeIn();



                                                $("a#button-close").click(function() {

                                                    $('div#fb-con-wrapper').hide();

                                                    $('div#fb-con').fadeOut();

                                                    showSocialSuggestion(voucher_html_suggestion);



                                                });



                                                /* voucher */

                                                {/literal}{else}{literal}

                                                showSocialSuggestion(voucher_html_suggestion);



                                                {/literal}{/if}{literal}









                                            } else {



                                                var error_type = data.params.error_type;

                                                $('#footer-add-review-form-review button').removeAttr('disabled');





                                                {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customer == 0}{literal}

                                                if(error_type == 3){

                                                    field_state_change('inpCaptchaReview','failed', '{/literal}{$gsnipreviewptc_msg8|escape:'htmlall':'UTF-8'}{literal}');



                                                    {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customer == 0}{literal}

                                                    var count = Math.random();

                                                    document.getElementById('secureCodReview').src = "";

                                                    document.getElementById('secureCodReview').src = "{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/{literal}captcha.php?re=" + count;

                                                    $('#inpCaptchaReview').val('');

                                                    {/literal}{/if}{literal}



                                                    return false;



                                                }

                                                {/literal}{/if}{literal}



                                                if(error_type == 2){

                                                    field_state_change('email-review','failed', '{/literal}{$gsnipreviewptc_msg9|escape:'htmlall':'UTF-8'}{literal}');

                                                    field_state_change('inpCaptchaReview','failed', '{/literal}{$gsnipreviewptc_msg1|escape:'htmlall':'UTF-8'}{literal}');



                                                    {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customer == 0}{literal}

                                                    var count = Math.random();

                                                    document.getElementById('secureCodReview').src = "";

                                                    document.getElementById('secureCodReview').src = "{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/{literal}captcha.php?re=" + count;

                                                    $('#inpCaptchaReview').val('');

                                                    {/literal}{/if}{literal}



                                                    return false;

                                                }



                                                if(error_type == 1){

                                                    alert("{/literal}{$gsnipreviewptc_msg10|escape:'htmlall':'UTF-8'}{literal}");

                                                    window.location.reload();

                                                }



                                                if(error_type == 8){

                                                    field_state_change('avatar-review','failed', '{/literal}{$gsnipreviewava_msg8|escape:'htmlall':'UTF-8'}{literal}');

                                                    return false;

                                                } else if(error_type == 9){

                                                    field_state_change('avatar-review','failed', '{/literal}{$gsnipreviewava_msg9|escape:'htmlall':'UTF-8'}{literal}');

                                                    return false;

                                                }



                                                {/literal}{if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customer == 0}{literal}

                                                var count = Math.random();

                                                document.getElementById('secureCodReview').src = "";

                                                document.getElementById('secureCodReview').src = "{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/{literal}captcha.php?re=" + count;

                                                $('#inpCaptchaReview').val('');

                                                {/literal}{/if}{literal}





                                            }



                                        }

                                    });











                                } else {

                                    return false;

                                }



                            }));



                        });

                        {/literal}{if $gsnipreviewis17 == 1}{literal}}); {/literal}{/if}{literal}







                        //}





                        function showSocialSuggestion(voucher_html){

                            {/literal}{if $gsnipreviewvis_onfb == 1}{literal}

                            if ($('div#fb-con-wrapper').length == 0)

                            {

                                conwrapper = '<div id="fb-con-wrapper"><\/div>';

                                $('body').append(conwrapper);

                            } else {

                                $('#fb-con-wrapper').html('');

                            }



                            if ($('div#fb-con').length == 0)

                            {

                                condom = '<div id="fb-con"><\/div>';

                                $('body').append(condom);

                            }



                            $('div#fb-con').fadeIn(function(){



                                $(this).css('filter', 'alpha(opacity=70)');

                                $(this).bind('click dblclick', function(){

                                    $('div#fb-con-wrapper').hide();

                                    $(this).fadeOut();

                                    window.location.reload();

                                });

                            });



                            $('div#fb-con-wrapper').html('<a id="button-close" style="display: inline;"><\/a>'+voucher_html).fadeIn();



                            $("a#button-close").click(function() {

                                $('div#fb-con-wrapper').hide();

                                $('div#fb-con').fadeOut();

                                window.location.reload();

                            });

                            {/literal}{else}{literal}

                            window.location.reload();

                            {/literal}{/if}{literal}

                        }





                    </script>

                {/literal}



                    {/if}







                {/if}



                </div>
















        {if $gsnipreviewcount_reviews > 0}
            <div class="row-custom total-info-tool-product-page">
                <div class="col-sm-5-custom first-block-ti">
                    {if $gsnipreviewis_add == 0}
                        {* <a class="btn-gsnipreview btn-primary-gsnipreview" href="javascript:void(0)" onclick="show_form_review(1)">
                            <span>
                                <i class="icon-pencil"></i>
                                {l s='Write a Review' mod='gsnipreview'}
                            </span>
                        </a> *}
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                            <i class="icon-pencil"></i>
                            {l s='Write a Review' mod='gsnipreview'}
                        </button>
                    {/if}
                </div>
            </div>
        {/if}



        <div style="float:left;" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="float:left;" class="modal-title" id="exampleModalLabel"> Ecrivez votre avis </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/upload.php" enctype="multipart/form-data" id="add_review_item_form" name="add_review_item_form">
                <div class="modal-body">                
                    <input type="hidden" name="action" value="add" />
                    <input type="hidden" name="id_product" value="{$gsnipreviewid_product|escape:'htmlall':'UTF-8'}" />
                    <input type="hidden" name="id_customer" value="{$gsnipreviewid_customer|escape:'htmlall':'UTF-8'}" />

                    {* <div class="title-rev">
                        <div class="title-form-text-left">
                            <b>{l s='Write Your Review' mod='gsnipreview'}</b>
                        </div>

                        <input type="button" value="{l s='close' mod='gsnipreview'}" class="btn-gsnipreview btn-primary-gsnipreview title-form-text-right" onclick="show_form_review(0)">

                        <div class="clear-gsnipreview"></div>
                    </div> *}

                    <div id="body-add-review-form-review">
                        {if $gsnipreviewratings_on == 1}
                            {if $gsnipreviewcriterions|@count > 0}
                                {foreach from=$gsnipreviewcriterions item='criterion'}
                                    <label for="rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}" class="float-left">{$criterion.name|escape:'htmlall':'UTF-8'}<sup class="required">*</sup></label>
                                    
                                    <div class="rat rating-stars-dynamic">
                                        <span onmouseout="read_rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}');">
                                            <img  onmouseover="_rating_efect_rev(1,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')" onmouseout="_rating_efect_rev(1,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')" onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',1); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true; " src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="1" id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_1" />
                                            <img  onmouseover="_rating_efect_rev(2,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')" onmouseout="_rating_efect_rev(2,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')" onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',2); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="2" id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_2" />
                                            <img  onmouseover="_rating_efect_rev(3,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')" onmouseout="_rating_efect_rev(3,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')" onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',3); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="3"  id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_3" />
                                            <img  onmouseover="_rating_efect_rev(4,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')" onmouseout="_rating_efect_rev(4,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')" onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',4); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="4"  id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_4" />
                                            <img  onmouseover="_rating_efect_rev(5,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')" onmouseout="_rating_efect_rev(5,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')" onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',5); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="5"  id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_5" />
                                        </span>

                                        {if strlen($criterion.description)>0}
                                            <div class="tip-criterion-description">
                                                {$criterion.description|escape:'htmlall':'UTF-8'}
                                            </div>
                                        {/if}
                                    </div>

                                    <input type="hidden" id="rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}" name="rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}" value="0"/>

                                    <div class="clr"></div>

                                    <div class="errorTxtAdd" id="error_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}"></div>
                                {/foreach}
                            {else}
                                <div class="pc_reviewnamerating">                                            
                                    <div class="pc_prodavishalfblock">
                                        <label for="name-review">{l s='Name' mod='gsnipreview'}<sup class="required">*</sup></label>
                                        <input type="text" name="name-review" id="name-review" value="{$gsnipreviewc_name|escape:'htmlall':'UTF-8'}"  onkeyup="check_inpNameReview();" onblur="check_inpNameReview();" />
                                        <div class="errorTxtAdd" id="error_name-review"></div>
                                    </div>

                                    <div class="pc_prodavishalfblock">
                                        <label for="rat_rel" class="float-left">{l s='Rating' mod='gsnipreview'}<sup class="required">*</sup></label>

                                        <div class="rat rating-stars-dynamic">
                                            <span onmouseout="read_rating_review_shop('rat_rel');">
                                                <img onmouseover="_rating_efect_rev(1,0,'rat_rel')" onmouseout="_rating_efect_rev(1,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',1); rating_checked=true; " src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="1" id="img_rat_rel_1" />
                                                <img onmouseover="_rating_efect_rev(2,0,'rat_rel')" onmouseout="_rating_efect_rev(2,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',2); rating_checked=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="2"  id="img_rat_rel_2" />
                                                <img onmouseover="_rating_efect_rev(3,0,'rat_rel')" onmouseout="_rating_efect_rev(3,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',3); rating_checked=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="3"  id="img_rat_rel_3" />
                                                <img onmouseover="_rating_efect_rev(4,0,'rat_rel')" onmouseout="_rating_efect_rev(4,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',4); rating_checked=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="4"  id="img_rat_rel_4" />
                                                <img onmouseover="_rating_efect_rev(5,0,'rat_rel')" onmouseout="_rating_efect_rev(5,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',5); rating_checked=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="5"  id="img_rat_rel_5" />
                                            </span>

                                            <input type="hidden" id="rat_rel" name="rat_rel" value="0"/>

                                            <div class="clr"></div>

                                            <div class="errorTxtAdd" id="error_rat_rel"></div>
                                        </div>
                                    </div>                                                
                                </div>
                            {/if}
                        {/if}

                        <label for="email-review">{l s='Email' mod='gsnipreview'}<sup class="required">*</sup></label>

                        <input type="text" name="email-review" id="email-review" value="{$gsnipreviewc_email|escape:'htmlall':'UTF-8'}" onkeyup="check_inpEmailReview();" onblur="check_inpEmailReview();"  />

                        <div id="error_email-review" class="errorTxtAdd"></div>

                        {if $gsnipreviewis_avatarr == 1}
                            <label for="avatar-review">{l s='Avatar' mod='gsnipreview'}</label>

                            {if strlen($gsnipreviewc_avatar)>0}
                                <div class="avatar-block-rev-form">
                                    <input type="radio" name="post_images" checked="" style="display: none">
                                    <img src="{$gsnipreviewc_avatar|escape:'htmlall':'UTF-8'}" alt="{$gsnipreviewc_name|escape:'htmlall':'UTF-8'}" />
                                </div>
                            {/if}

                            <input type="file" name="avatar-review" id="avatar-review" class="testimonials-input" />

                            <div class="avatar-guid">
                                {l s='Allow formats' mod='gsnipreview'}: *.jpg; *.jpeg; *.png; *.gif.
                            </div>

                            <div class="errorTxtAdd" id="error_avatar-review"></div>
                        {/if}

                        {if $gsnipreviewtitle_on == 1}
                            <label for="subject-review">{l s='Title' mod='gsnipreview'}<sup class="required">*</sup></label>

                            <input type="text" name="subject-review" id="subject-review" onkeyup="check_inpSubjectReview();" onblur="check_inpSubjectReview();" />

                            <div id="error_subject-review" class="errorTxtAdd"></div>
                        {/if}

                        {if $gsnipreviewtext_on == 1}
                            <label for="text-review">{l s='Text' mod='gsnipreview'}<sup class="required">*</sup></label>

                            <textarea id="text-review" name="text-review" cols="42" rows="7" onkeyup="check_inpTextReview();" onblur="check_inpTextReview();"></textarea>

                            <div id="textarea_feedback"></div>

                            <div id="error_text-review" class="errorTxtAdd"></div>
                        {/if}

                        {if $gsnipreviewis_filesr == 1}
                            <label for="text-files">{l s='Files' mod='gsnipreview'}</label>

                            <span class="file-upload-rev" id="file-upload-rev">
                                <input type="file" name="files[]" multiple />

                                <div class="progress-files-bar">
                                    <div class="progress-files"></div>
                                </div>

                                <div id="file-files-list"></div>
                            </span>

                            <div class="avatar-guid">
                                {l s='Allow formats' mod='gsnipreview'}: *.jpg; *.jpeg; *.png; *.gif.
                            </div>

                            <div id="error_text-files" class="errorTxtAdd"></div>
                        {/if}

                        {if $gsnipreviewis_captcha == 1 && $gsnipreviewid_customer == 0}
                            <label for="inpCaptchaReview">{l s='Captcha' mod='gsnipreview'}<sup class="required">*</sup></label>

                            <div class="clr"></div>

                            <img width="100" height="26" class="float-left" id="secureCodReview" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/captcha.php" alt="Captcha"/>

                            <input type="text" class="inpCaptchaReview float-left" id="inpCaptchaReview" size="6" name="captcha" onkeyup="check_inpCaptchaReview();" onblur="check_inpCaptchaReview();"/>

                            <div class="clr"></div>

                            <div id="error_inpCaptchaReview" class="errorTxtAdd"></div>
                        {/if}
                    </div>                
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Cancel' mod='gsnipreview'}</button>
                    <button type="submit" value="{l s='Add review' mod='gsnipreview'}" class="btn btn-primary">Valider</button>
                </div>
            </form>            
        </div>
    </div>
</div>



        {if $gsnipreviewis_search == 1}

            <h3 class="search-result-item">{l s='Results for' mod='gsnipreview'} <b>"{$gsnipreviewsearch|escape:'quotes':'UTF-8'}"</b></h3>

            <br/>

        {/if}



     {if $reviews}

                <div class="spr-reviews">



                    {foreach from=$reviews item=review}

                    <div class="spr-review" {if $gsnipreviewsvis_on == 1 && $gsnipreviewis16_snippet == 1}itemprop="review" itemscope itemtype="http://schema.org/Review"{/if}>

                        <div class="spr-review-header">

                            {if $review.is_active == 1}

                            {if $gsnipreviewratings_on == 1 && $review.rating!=0}

                                <span class="spr-starratings spr-review-header-starratings">



                                  {section name=ratid loop=5}

                                    {if $smarty.section.ratid.index < $review.rating}
                                        <i style="color: #ffa852;" class="fas fa-star"></i>
								  	{else}
								    	<i style="color: #ffa852;"class="far fa-star"></i>
                                    {/if}

                                  {/section}



                                </span>

                                <div {if $gsnipreviewsvis_on == 1  && $gsnipreviewis16_snippet == 1}itemtype="http://schema.org/Rating" itemscope itemprop="reviewRating"{/if} class="rating-stars-total">

                                (<span {if $gsnipreviewsvis_on == 1  && $gsnipreviewis16_snippet == 1}itemprop="ratingValue"{/if}>{$review.rating|escape:'htmlall':'UTF-8'}</span>/<span {if $gsnipreviewsvis_on == 1  && $gsnipreviewis16_snippet == 1}itemprop="bestRating"{/if}>5</span>)&nbsp;

                                </div>





                            {/if}

                                <span class="spr-review-header-byline float-left">                             

                                    {if strlen($review.customer_name)>0}

                                        {if $gsnipreviewis_uprof && $review.id_customer > 0 && $review.is_show_ava == 1}
                                            <a href="{$gsnipreviewuser_url|escape:'htmlall':'UTF-8'}{$review.id_customer|escape:'htmlall':'UTF-8'}" title="{$review.customer_name|escape:'htmlall':'UTF-8'}" class="user-link-to-profile">
                                        {/if}
                                            <span>Par</span>
                                            <strong {if $gsnipreviewsvis_on == 1  && $gsnipreviewis16_snippet == 1}itemprop="author"{/if}>
                                                {$review.customer_name|escape:'htmlall':'UTF-8' nofilter}
                                            </strong>
                                        {if $gsnipreviewis_uprof && $review.id_customer > 0 && $review.is_show_ava == 1}
                                            </a>
                                        {/if}

                                    {/if}
                                </span>



                            {/if}



                            <div class="clear-gsnipreview"></div>
                            
                            {if $gsnipreviewtitle_on == 1 && strlen($review.title_review)>0}

                                <h3 class="spr-review-header-title" {if $gsnipreviewsvis_on == 1  && $gsnipreviewis16_snippet == 1}itemprop="name"{/if}>{$review.title_review|escape:'htmlall':'UTF-8' nofilter}</h3>

                            {/if}

                            <div class="clear-gsnipreview"></div>



                            



                        </div>



                        <div class="{if $gsnipreviewis16 == 1}row-custom{else}row-list-reviews{/if}">



                            {* {if $review.is_active == 1}

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

                            {/if} *}



                            <div class="spr-review-content {if $gsnipreviewis16 == 1}col-sm-{if $review.criterions|@count>0 && $review.is_active == 1}9{else}12{/if}-custom{else}col-sm-{if $review.criterions|@count>0 && $review.is_active == 1}9{else}12{/if}-list-reviews{/if}">



                                    {if $review.is_active == 1}

                                        {if $gsnipreviewtext_on == 1 && strlen($review.text_review)>0}

                                            {*!! no smarty changes |escape:'htmlall':'UTF-8' !!*}

                                            <p class="spr-review-content-body" {if $gsnipreviewsvis_on == 1  && $gsnipreviewis16_snippet == 1}itemprop="description"{/if}>{$review.text_review|nl2br nofilter}</p>

                                            {*!! no smarty changes |escape:'htmlall':'UTF-8' !!*}

                                        {/if}



                                        {if $gsnipreviewis_filesr == 1}

                                            {if count($review.files)>0}

                                                <div  class="{if $gsnipreviewis16 == 1}row-custom{else}row-list-reviews{/if}">

                                                    {foreach from=$review.files item=file}

                                                        <div class="col-sm-{if $gsnipreviewis16 == 1}2{else}{if $review.criterions|@count>0}4{else}6{/if}{/if}-custom files-review-gsnipreview">

                                                            <a class="fancybox shown" data-fancybox-group="other-views" href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}{$file.full_path|escape:'htmlall':'UTF-8'}">

                                                                <img class="img-responsive" width="105" height="105" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}{$file.small_path|escape:'htmlall':'UTF-8'}" alt="{$file.id|escape:'htmlall':'UTF-8'}"



                                                                        >

                                                            </a>

                                                            {*<img src="{$file.full_path|escape:'htmlall':'UTF-8'}" alt="{$file.id|escape:'htmlall':'UTF-8'}"  />*}

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


                                {if $gsnipreviewrsoc_on == 1}

                                <div class="fb-like valign-top" data-href="{$gsnipreviewrev_url|escape:'htmlall':'UTF-8'}{if $gsnipreviewis_rewrite == 1}?{else}&{/if}rid={$review.id|escape:'htmlall':'UTF-8'}"

                                     data-show-faces="false" data-width="60" data-send="false" data-layout="{if $gsnipreviewrsoccount_on == 1}button_count{else}button{/if}"></div>

                                    {*{$gsnipreviewrev_url|escape:'htmlall':'UTF-8'}?rid={$review.id|escape:'htmlall':'UTF-8'}*}

                                    {literal}

                                    <script type="text/javascript">





                                        {/literal}{if $gsnipreviewis17 == 1}{literal}document.addEventListener("DOMContentLoaded", function(event) { {/literal}{/if}{literal}

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

                                        {/literal}{if $gsnipreviewis17 == 1}{literal}}); {/literal}{/if}{literal}



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





            {*!! no smarty changes |escape:'htmlall':'UTF-8' !!*}

            {*<div id="gsniprev-nav-pre">{$paging}</div>*}

            {*!! no smarty changes |escape:'htmlall':'UTF-8' !!*}



         <div id="gsniprev-nav-pre">

            <div class="pages">

                <span>{$gsnipreviewpage_text|escape:'htmlall':'UTF-8'}:</span>

                <span class="nums">

                    {foreach $paging as $page_item}

                        {if $page_item.is_b == 1}

                            <b>{$page_item.page|escape:'htmlall':'UTF-8'}</b>

                        {else}

                            <a href="{$page_item.url|escape:'quotes':'UTF-8'}" title="{$page_item.title|escape:'htmlall':'UTF-8'}">{$page_item.page|escape:'htmlall':'UTF-8'}</a>

                        {/if}

                    {/foreach}

                </span>

            </div>

         </div>



     {if $gsnipreviewgp > 1}

     {literal}

         <script type="text/javascript">

             $(document).ready(function() {

                 gsnipreview_open_tab();

             });

         </script>

     {/literal}

     {/if}





    {else}





                <div class="advertise-text-review advertise-text-review-text-align" id="no-customers-reviews">

                    {l s='No reviews for the product' mod='gsnipreview'}



         {if $gsnipreviewcount_reviews == 0}

                    <br/><br/>

                   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                        <i class="icon-pencil"></i>
                        {l s='Write a Review' mod='gsnipreview'}
                    </button>
         {/if}

                </div>









    {/if}



            </div>



        </div></div>



    <div class="clear-gsnipreview"></div>

<!-- reviews template -->







</div>







{/if}



{/if}















{if $gsnipreviewis17 == 1}



{if $gsnipreviewrvis_on == 1}



    {if $gsnipreviewratings_on == 1 || $gsnipreviewtitle_on == 1 || $gsnipreviewtext_on == 1}



        {if $gsnipreviewhooktodisplay == "product_footer"}



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

                    <a class="btn-gsnipreview btn-primary-gsnipreview" href="#idTab777" id="idTab777-my-click" >

        <span>

            <i class="icon-pencil"></i>&nbsp;



            {l s='Add Review' mod='gsnipreview'}



        </span>

                    </a>

                {/if}





                <a class="btn-gsnipreview btn-default-gsnipreview" href="#idTab777" >

        <span>

            <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" class="title-rating-one-star" alt="{l s='View Reviews' mod='gsnipreview'}"/>

            {l s='View Reviews' mod='gsnipreview'}

        </span>

                </a>









            </div>









        {/if}



    {/if}



{/if}



{$gsnipreviewproductfooter|escape:'htmlall':'UTF-8'}





{if $gsnipreviewpinvis_on == 1 && $gsnipreview_productFooter == 'productFooter'}

    <a href="//www.pinterest.com/pin/create/button/?

		url=http://{$smarty.server.HTTP_HOST|escape:'htmlall':'UTF-8'}{$smarty.server.REQUEST_URI|escape:'htmlall':'UTF-8'}

		&media={$product_image|escape:'htmlall':'UTF-8'}

		&description={$meta_description|escape:'htmlall':'UTF-8'}"

       data-pin-do="buttonPin" data-pin-config="{if $gsnipreviewpinterestbuttons == 'firston'}above{/if}{if $gsnipreviewpinterestbuttons == 'secondon'}beside{/if}{if $gsnipreviewpinterestbuttons == 'threeon'}none{/if}">

        <img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" alt="Pinterest" />

    </a>

{/if}



{/if}