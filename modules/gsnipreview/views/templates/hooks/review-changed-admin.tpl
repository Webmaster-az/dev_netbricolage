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

<div id="add-review-form-review" class="popup-form font-family-custom">


    <div class="title-rev">
        <div class="title-form-text-left">
            <b class="title-form-custom">{l s='Review' mod='gsnipreview'}:</b>

        </div>

        <div class="clear-gsnipreview"></div>
    </div>

    <div id="body-add-review-form-review" class="text-align-left">
        <span class="label-span">{l s='Review URL' mod='gsnipreview'}:</span>

        <span class="badge">
            <a href="{$gsnipreviewdatareview.review_url|escape:'htmlall':'UTF-8'}" target="_blank">{$gsnipreviewdatareview.review_url|escape:'htmlall':'UTF-8'}</a>
        </span>
        <div class="clear-gsnipreview"></div>

        <span class="label-span">{l s='Review Language' mod='gsnipreview'}:</span>

        <span class="badge">
            {$gsnipreviewdatareview.name_lang|escape:'htmlall':'UTF-8'}
        </span>
        <div class="clear-gsnipreview"></div>


        {if $gsnipreviewdatareview.id_customer == 0}

            <label for="name-abuse" >{l s='Guest Name' mod='gsnipreview'}:</label>
            <input type="text" name="name-abuse" id="name-abuse" class="form-control disabled-values" disabled value="{$gsnipreviewdatareview.customer_name|escape:'htmlall':'UTF-8'}" />
        {if $gsnipreviewdatareview.email}
            <label for="email-abuse" >{l s='Guest Email' mod='gsnipreview'}:</label>
            <input type="text" name="email-abuse" id="email-abuse" class="form-control disabled-values" disabled  value="{$gsnipreviewdatareview.email|escape:'htmlall':'UTF-8'}" />

         {/if}

            <div class="clear-gsnipreview"></div>
        {else}
            <span class="label-span">{l s='Customer' mod='gsnipreview'}:</span>
            <span class="badge">
            <a href="{$gsnipreviewdatareview.url_to_customer|escape:'htmlall':'UTF-8'}" target="_blank">{$gsnipreviewdatareview.customer_name_full|escape:'htmlall':'UTF-8'}</a>
        </span>
            <div class="clear-gsnipreview"></div>

        {/if}



        {if $gsnipreviewdatareview.criterions|@count>0}

                {foreach from=$gsnipreviewdatareview.criterions item=criterion}
                        <label for="rating-review" >{$criterion.name|escape:'htmlall':'UTF-8'}:</label>

                        <span class="stars-section">
                        {section name=ratid loop=5}
                            {if $smarty.section.ratid.index < $criterion.rating}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star-list1"
                                     alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                            {else}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star-list1"
                                     alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                            {/if}
                        {/section}
                        </span>
                    <div style="clear:both"></div>

                {/foreach}

            <br/>
         {else}
            <label for="rating-review" >{l s='Rating' mod='gsnipreview'}:</label>
            <span class="stars-section">
            {section name=ratid loop=5}
                {if $smarty.section.ratid.index < $gsnipreviewdatareview.rating}
                    <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star-list1"
                         alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                {else}
                    <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star-list1"
                         alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                {/if}
            {/section}
             </span>
            <br/>
        {/if}
        <div style="clear: both;"></div>


        {if strlen($gsnipreviewdatareview.title_review)>0}
        <label for="subject-review" >{l s='Review Title' mod='gsnipreview'}:</label>
        <input disabled class="form-control disabled-values" id="disabledInput" type="text" value="{$gsnipreviewdatareview.title_review|escape:'htmlall':'UTF-8' nofilter}"  />
        {/if}

        {if strlen($gsnipreviewdatareview.text_review)>0}
        <label for="text-review" >{l s='Review Text' mod='gsnipreview'}:</label>
        <textarea disabled class="form-control disabled-values" id="disabledInput" cols="42" rows="7">{$gsnipreviewdatareview.text_review|escape:'htmlall':'UTF-8' nofilter}</textarea>
        {/if}
    </div>



    {if $gsnipreviewdatareview.is_changed == 2}
    <div id="body-add-review-form-review">

        <span class="badge">
            <a href="javascript:void(0)" onclick="$('.old-review-item').toggle();" >{l s='Click to see old review' mod='gsnipreview'}</a>
        </span>



    </div>
    <div id="body-add-review-form-review" class="text-align-left old-review-item" style="display: none">

        {if $gsnipreviewdatareview.criterions_old|@count>0}

            {foreach from=$gsnipreviewdatareview.criterions_old item=criterion}
                <label for="rating-review" >{$criterion.name|escape:'htmlall':'UTF-8'}:</label>

                <span class="stars-section">
                        {section name=ratid loop=5}
                            {if $smarty.section.ratid.index < $criterion.rating}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star-list"
                                     alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                            {else}
                                <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star-list"
                                     alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                            {/if}
                        {/section}
                        </span>
                <br/>

            {/foreach}

            <br/>
        {else}
            <label for="rating-review" >{l s='Rating' mod='gsnipreview'}:</label>
            <span class="stars-section">
            {section name=ratid loop=5}
                {if $smarty.section.ratid.index < $gsnipreviewdatareview.rating}
                    <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star-list1"
                         alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                {else}
                    <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" class="gsniprev-img-star-list1"
                         alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
                {/if}
            {/section}
             </span>
            <br/>
        {/if}



        {if strlen($gsnipreviewdatareview.title_review_old)>0}
            <label for="subject-review" >{l s='Review Title' mod='gsnipreview'}:</label>
            <input disabled class="form-control disabled-values" id="disabledInput" type="text" value="{$gsnipreviewdatareview.title_review_old|escape:'htmlall':'UTF-8'}"  />
        {/if}

        {if strlen($gsnipreviewdatareview.text_review_old)>0}
            <label for="text-review" >{l s='Review Text' mod='gsnipreview'}:</label>
            <textarea disabled class="form-control disabled-values" id="disabledInput" cols="42" rows="7">{$gsnipreviewdatareview.text_review_old|escape:'htmlall':'UTF-8'}</textarea>
        {/if}


    </div>
    {/if}


    {if $gsnipreviewdatareview.id_customer != 0}

    <div class="title-rev">
        <div class="title-form-text-left">
            <b class="title-form-custom">{l s='Your suggest for change the review' mod='gsnipreview'}:</b>
        </div>

        <div class="clear-gsnipreview"></div>
    </div>
    <div id="body-add-review-form-review" class="text-align-left">




        <label for="text-admin_response" >{l s='Your suggestion text' mod='gsnipreview'}:</label>
        <textarea id="text-admin_response" name="text-admin_response" cols="42" rows="7" class="form-control cursor-active"
                  onkeyup="check_inpReponseReview();" onblur="check_inpReponseReview();"  {*{if $gsnipreviewdatareview.is_changed == 2}disabled{/if}*}
                >{$gsnipreviewdatareview.suggest_text|escape:'htmlall':'UTF-8'}</textarea>
        <div id="error_text-admin_response" class="errorTxtAdd"></div>

        <label for="is_display_old" >{l s='Display your suggestion on product page' mod='gsnipreview'}:</label>
        <input type="checkbox"  class="gsnipreview-checkbox" {if $gsnipreviewdatareview.is_display_old == 1}checked="checked"{/if} value="1" id="is_display_old" name="is_display_old">


        

        {if $gsnipreviewdatareview.is_count_sending_suggestion != 0}
            <div style="clear: both;"></div>
        <label for="is_send_again" >{l s='Send your suggestion by email again' mod='gsnipreview'}:</label>
        <input type="checkbox"  class="gsnipreview-checkbox" value="1" id="is_send_again" name="is_send_again"/>

            <div class="tip-times">
                {l s='Your response has already been sent' mod='gsnipreview'} <strong>{$gsnipreviewdatareview.is_count_sending_suggestion|escape:'htmlall':'UTF-8'}</strong> {l s='time(s)' mod='gsnipreview'}<br>

            </div>
        {/if}


    </div>
    <div id="footer-add-review-form-review">
        <button onclick="update_chaged()"  value="{l s='Send suggestion to user change the review' mod='gsnipreview'}" class="btn btn-success">{l s='Send suggestion to user change the review' mod='gsnipreview'}</button>
    </div>

    {else}
        <div class="tip-times">
            {l s='You cannot suggest a guest the change own review. Only for registered customers' mod='gsnipreview'}
        </div>

    {/if}



    </div>


{if $gsnipreviewdatareview.id_customer != 0}
{literal}
<script type="text/javascript">

    function check_inpReponseReview()
    {

        var text_review = trim(document.getElementById('text-admin_response').value);

        if (text_review.length == 0)
        {
            field_state_change('text-admin_response','failed', '{/literal}{$gsnipreviewrca_msg1|escape:'htmlall':'UTF-8'}{literal}');
            return false;
        }
        field_state_change('text-admin_response','success', '');
        return true;
    }


    function update_chaged(){

            var is_text_response =  check_inpReponseReview();

            if(is_text_response){

            var id_review = {/literal}{$gsnipreviewdatareview.id|escape:'htmlall':'UTF-8'}{literal};

            $('#changeditem'+id_review).html('<img src="../img/admin/../../modules/gsnipreview/views/img/loader.gif" />');

                if($("input#is_display_old").is(":checked")) {
                    var is_display_old = 1;
                } else {
                    var is_display_old = 0;
                }

                if($("input#is_send_again").is(":checked")) {
                    var is_send_again = 1;
                } else {
                    var is_send_again = 0;
                }

            $.post('../modules/gsnipreview/reviews_admin.php',
                    {action:'change-wait',
                    review_id:{/literal}{$gsnipreviewreview_id|escape:'htmlall':'UTF-8'}{literal},
                    is_display_old: is_display_old,
                    is_send_again:is_send_again,
                    admin_response: $('#text-admin_response').val(),
                    },
                    function (data) {

                        if (data.status == 'success') {

                            $('#changeditem'+id_review).html('');
                            var html = '<span class="label-tooltip" data-original-title="{/literal}{$gsnipreviewrca_msg2|escape:'htmlall':'UTF-8'}{literal}" data-toggle="tooltip">'+
                                '<a style="text-decoration:none" onclick="gsnipreview_list({/literal}{$gsnipreviewdatareview.id|escape:'htmlall':'UTF-8'}{literal},\'changed\',1,\'{/literal}{$gsnipreviewtoken|escape:'htmlall':'UTF-8'}{literal}\');" href="javascript:void(0)">'+
                                '<img src="../img/admin/../../modules/gsnipreview/views/img/time.gif" />'+
                            '</a>'+
                                    '</span>';
                            $('#changeditem'+id_review).html(html);

                             $('#fb-con-wrapper-admin').remove();
                             $('#fb-con').remove();



                        } else {

                            alert(data.message);

                        }
                    }, 'json');
            }

        }


</script>
{/literal}

{/if}