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

{if $gsnipreviewis_abuse == 0}
<div id="add-review-form-review" class="popup-form">


    <div class="title-rev">
        <div class="title-form-text-left">
            <b>{l s='Abuse Form' mod='gsnipreview'}</b>
        </div>

        <div class="clear-gsnipreview"></div>
    </div>

    <div id="body-add-review-form-review" class="text-align-left">
        {if strlen($gsnipreviewabuserev.title_review)>0}
        <label for="subject-review" >{l s='Title' mod='gsnipreview'}</label>
        <input disabled class="form-control disabled-values" id="disabledInput" type="text" value="{$gsnipreviewabuserev.title_review|escape:'html':'UTF-8'}"  />
        {/if}

        {if strlen($gsnipreviewabuserev.text_review)>0}
        <label for="text-review" >{l s='Text' mod='gsnipreview'}</label>
        <textarea disabled class="form-control disabled-values" id="disabledInput" cols="42" rows="7">{$gsnipreviewabuserev.text_review|escape:'html':'UTF-8'}</textarea>
        {/if}



        {if !$gsnipreviewislogged}
            <label for="name-abuse" >{l s='Name' mod='gsnipreview'}<sup class="required">*</sup></label>
            <input type="text" name="name-abuse" id="name-abuse"  onkeyup="check_inpNameAbuse();" onblur="check_inpNameAbuse();" />
            <div class="errorTxtAdd" id="error_name-abuse"></div>
        {/if}

        {if !$gsnipreviewislogged}
            <label for="email-abuse" >{l s='Email' mod='gsnipreview'}<sup class="required">*</sup></label>
            <input type="text" name="email-abuse" id="email-abuse" onkeyup="check_inpEmailAbuse();" onblur="check_inpEmailAbuse();"  />
            <div id="error_email-abuse" class="errorTxtAdd"></div>
        {/if}


        <label for="text-abuse" >{l s='Enter your reason' mod='gsnipreview'}<sup class="required">*</sup></label>
        <textarea id="text-abuse" name="text-abuse" cols="42" rows="7" onkeyup="check_inpTextAbuse();" onblur="check_inpTextAbuse();"></textarea>
        <div id="error_text-abuse" class="errorTxtAdd"></div>

        {if !$gsnipreviewislogged}
            <label for="inpCaptchaAbuse" >{l s='Captcha' mod='gsnipreview'}<sup class="required">*</sup></label>
            <div class="clr"></div>
            <img width="100" height="26" class="float-left" id="secureCodAbuse" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/captcha_abuse.php" alt="Captcha"/>
            <input type="text" class="inpCaptchaAbuse float-left" id="inpCaptchaAbuse" size="6" onkeyup="check_inpCaptchaAbuse();" onblur="check_inpCaptchaAbuse();"/>
            <div class="clr"></div>

            <div id="error_inpCaptchaAbuse" class="errorTxtAdd"></div>
        {/if}
    </div>

    <div id="footer-add-review-form-review">
        <button onclick="add_abuse()"  value="{l s='Send Report' mod='gsnipreview'}" class="btn btn-success">{l s='Send Report' mod='gsnipreview'}</button>
        &nbsp;
        <button id="cancel-report" value="{l s='Cancel' mod='gsnipreview'}" class="btn btn-danger">{l s='Cancel' mod='gsnipreview'}</button>
    </div>


    </div>

{literal}
<script type="text/javascript">




    {/literal}{if !$gsnipreviewislogged}{literal}
    function check_inpCaptchaAbuse()
    {

        var inpCaptchaReview = trim(document.getElementById('inpCaptchaAbuse').value);

        if (inpCaptchaReview.length != 6)
        {
            field_state_change('inpCaptchaAbuse','failed', '{/literal}{$gsnipreviewraf_msg1|escape:'htmlall':'UTF-8'}{literal}');
            return false;
        }
        field_state_change('inpCaptchaAbuse','success', '');
        return true;
    }
    function check_inpNameAbuse()
    {

        var name_review = trim(document.getElementById('name-abuse').value);

        if (name_review.length == 0)
        {
            field_state_change('name-abuse','failed', '{/literal}{$gsnipreviewraf_msg2|escape:'htmlall':'UTF-8'}{literal}');
            return false;
        }
        field_state_change('name-abuse','success', '');
        return true;
    }
    function check_inpEmailAbuse()
    {

        var email_review = trim(document.getElementById('email-abuse').value);

        if (email_review.length == 0)
        {
            field_state_change('email-abuse','failed', '{/literal}{$gsnipreviewraf_msg3|escape:'htmlall':'UTF-8'}{literal}');
            return false;
        }
        field_state_change('email-abuse','success', '');
        return true;
    }
    {/literal}{/if}{literal}

    function check_inpTextAbuse()
    {

        var text_review = trim(document.getElementById('text-abuse').value);

        if (text_review.length == 0)
        {
            field_state_change('text-abuse','failed', '{/literal}{$gsnipreviewraf_msg4|escape:'htmlall':'UTF-8'}{literal}');
            return false;
        }
        field_state_change('text-abuse','success', '');
        return true;
    }


    function add_abuse(){


        {/literal}{if !$gsnipreviewislogged}{literal}
        var is_name = check_inpNameAbuse();
        var is_email = check_inpEmailAbuse();
        var is_captcha = check_inpCaptchaAbuse();
        {/literal}{/if}{literal}

        var is_text =  check_inpTextAbuse();


        if(
            {/literal}{if !$gsnipreviewislogged}{literal}
                is_name &&
                is_email &&
                is_captcha &&
            {/literal}{/if}{literal}
                is_text &&
                true
        ){

            $('#add-review-form-review .popup-form').css('opacity',0.5);

            $.post(baseDir+'modules/gsnipreview/reviews.php',
                    {action:'post-abuse',

                        {/literal}{if !$gsnipreviewislogged}{literal}

                        name:$('#name-abuse').val(),
                        email:$('#email-abuse').val(),
                        captcha:$('#inpCaptchaAbuse').val(),

                        {/literal}{else}{literal}

                        id_customer:{/literal}{$gsnipreviewislogged|escape:'html':'UTF-8'}{literal},

                        {/literal}{/if}{literal}

                        text:$('#text-abuse').val(),
                        review_id:{/literal}{$gsnipreviewreview_id|escape:'html':'UTF-8'}{literal},

                    },
                    function (data) {
                        $('#add-review-form-review .popup-form').css('opacity',1);

                        if (data.status == 'success') {


                             $('#fb-con-wrapper').html('');

                            var html = '<div class="alert alert-warning form-warning">'+
                                    '<span class="text-center">{/literal}{$gsnipreviewraf_msg5|escape:'htmlall':'UTF-8'}{literal}<\/span>'+
                                    '<\/div>';
                            $('#fb-con-wrapper').html('<a id="button-close" style="display: inline;"><\/a>'+html);

                            $("a#button-close").click(function() {
                                $('div#fb-con-wrapper').hide();
                                $('div#fb-con').fadeOut();
                                window.location.reload();
                            });



                        } else {

                            var error_type = data.params.error_type;

                            {/literal}{if !$gsnipreviewislogged}{literal}
                            if(error_type == 3){
                                field_state_change('inpCaptchaAbuse','failed', '{/literal}{$gsnipreviewraf_msg6|escape:'htmlall':'UTF-8'}{literal}');

                            }
                            {/literal}{/if}{literal}

                            if(error_type == 2){
                                field_state_change('email-abuse','failed', '{/literal}{$gsnipreviewraf_msg7|escape:'htmlall':'UTF-8'}{literal}');
                                field_state_change('inpCaptchaAbuse','failed', '{/literal}{$gsnipreviewraf_msg6|escape:'htmlall':'UTF-8'}{literal}');
                            }

                            if(error_type == 1){
                                $('#fb-con-wrapper').html('');

                                var html = '<div class="alert alert-warning form-warning">'+
                                        '<span class="text-center">{/literal}{$gsnipreviewraf_msg8|escape:'htmlall':'UTF-8'}{literal}</span>'+
                                    '</div>';
                                $('#fb-con-wrapper').html('<a id="button-close" style="display: inline;"><\/a>'+html);

                                $("a#button-close").click(function() {
                                    $('div#fb-con-wrapper').hide();
                                    $('div#fb-con').fadeOut();
                                    window.location.reload();
                                });

                            }

                            {/literal}{if !$gsnipreviewislogged}{literal}
                            var count = Math.random();
                            document.getElementById('secureCodAbuse').src = "";
                            document.getElementById('secureCodAbuse').src = "{/literal}{$base_dir_ssl|escape:'html':'UTF-8'}modules/gsnipreview/{literal}captcha_abuse.php?re=" + count;
                            $('#inpCaptchaAbuse').val('');
                            {/literal}{/if}{literal}


                        }
                    }, 'json');

        }
    }

</script>
{/literal}


{else}
    <div>
        <div class="alert alert-warning form-warning">
            <p class="text-center">{l s='You cannot send report for this review because somebody has already posted a report' mod='gsnipreview'}</p>
        </div>
    </div>
{literal}
    <script type="text/javascript">

        $('#fb-con-wrapper').css('height','auto');

        document.addEventListener("DOMContentLoaded", function(event) {
            $(document).ready(function(){

                $('#fb-con-wrapper').css('height','auto');

            });
        });

    </script>
{/literal}

{/if}