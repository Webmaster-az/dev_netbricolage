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

    {literal}
    <script type="text/javascript">

        var module_dir = '{/literal}{$module_dir|escape:'htmlall':'UTF-8'}{literal}';
        var gsnipreview_star_active = '{/literal}{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}{literal}';
        var gsnipreview_star_noactive = '{/literal}{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}{literal}';



        </script>
    {/literal}

    <div class="title-rev">
        <div class="title-form-text-left">
            <b class="title-form-custom">{l s='My Review' mod='gsnipreview'}:</b>

        </div>

        <div class="clear-gsnipreview"></div>
    </div>

    <div id="body-add-review-form-review" class="text-align-left">


        {if $gsnipreviewratings_on == 1}

        {if $gsnipreviewdatareview.criterions|@count>0}

                {foreach from=$gsnipreviewdatareview.criterions item=criterion}


                    <label for="rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}"
                           class="float-left">{$criterion.name|escape:'htmlall':'UTF-8'}<sup class="required">*</sup></label>

                    <div class="rat rating-stars-dynamic">
                                                        <span onmouseout="read_rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}');">

                                                            <img  onmouseover="_rating_efect_rev(1,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onmouseout="_rating_efect_rev(1,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',1); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true; "
                                                                  src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}"
                                                                  alt="" id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_1" />

                                                            <img  onmouseover="_rating_efect_rev(2,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onmouseout="_rating_efect_rev(2,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',2); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true;"
                                                                  src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}"
                                                                  alt="" id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_2" />

                                                            <img  onmouseover="_rating_efect_rev(3,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onmouseout="_rating_efect_rev(3,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',3); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true;"
                                                                  src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}"
                                                                  alt=""  id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_3" />
                                                            <img  onmouseover="_rating_efect_rev(4,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onmouseout="_rating_efect_rev(4,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',4); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true;"
                                                                  src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}"
                                                                  alt=""  id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_4" />
                                                            <img  onmouseover="_rating_efect_rev(5,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onmouseout="_rating_efect_rev(5,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',5); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true;"
                                                                  src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}"
                                                                  alt=""  id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_5" />
                                                        </span>
                        {if strlen($criterion.description)>0}
                            <div class="tip-criterion-description">{$criterion.description|escape:'htmlall':'UTF-8'}</div>
                        {/if}
                    </div>
                    <input type="hidden" id="rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}"
                            name="rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}" value="{$criterion.rating|escape:'htmlall':'UTF-8'}"/>
                    {literal}
                        <script type="text/javascript">
                            $(document).ready(function(){
                                rating_review_shop('rat_rel{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}',{/literal}{$criterion.rating|escape:'htmlall':'UTF-8'}{literal});
                            });
                        </script>
                    {/literal}
                    <div class="clr"></div>
                    <div class="errorTxtAdd" id="error_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}"></div>

                {/foreach}

            <br/>
         {else}

            <label for="rat_rel" class="float-left">{l s='Rating' mod='gsnipreview'}<sup class="required">*</sup></label>

            <div class="rat rating-stars-dynamic">
                                                        <span onmouseout="read_rating_review_shop('rat_rel');">
                                                            <img  onmouseover="_rating_efect_rev(1,0,'rat_rel')" onmouseout="_rating_efect_rev(1,1,'rat_rel')"
                                                                  onclick = "rating_review_shop('rat_rel',1); rating_checked=true; "
                                                                  src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}"
                                                                  alt=""
                                                                  id="img_rat_rel_1" />
                                                            <img  onmouseover="_rating_efect_rev(2,0,'rat_rel')" onmouseout="_rating_efect_rev(2,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',2); rating_checked=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt=""  id="img_rat_rel_2" />
                                                            <img  onmouseover="_rating_efect_rev(3,0,'rat_rel')" onmouseout="_rating_efect_rev(3,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',3); rating_checked=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt=""  id="img_rat_rel_3" />
                                                            <img  onmouseover="_rating_efect_rev(4,0,'rat_rel')" onmouseout="_rating_efect_rev(4,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',4); rating_checked=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt=""  id="img_rat_rel_4" />
                                                            <img  onmouseover="_rating_efect_rev(5,0,'rat_rel')" onmouseout="_rating_efect_rev(5,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',5); rating_checked=true;" src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt=""  id="img_rat_rel_5" />
                                                        </span>
            </div>
            <input type="hidden" id="rat_rel" name="rat_rel" value="{$gsnipreviewdatareview.rating|escape:'htmlall':'UTF-8'}"/>
            {literal}
                <script type="text/javascript">
                    $(document).ready(function(){
                        rating_review_shop('rat_rel',{/literal}{$gsnipreviewdatareview.rating|escape:'htmlall':'UTF-8'}{literal});
                    });
                </script>
            {/literal}
            <div class="clr"></div>
            <div class="errorTxtAdd" id="error_rat_rel"></div>
        {/if}


        {/if}


        {if strlen($gsnipreviewdatareview.title_review)>0}
        <label for="subject-review" >{l s='Review Title' mod='gsnipreview'}<sup class="required">*</sup></label>
        <input type="text" name="subject-review" id="subject-review" value="{$gsnipreviewdatareview.title_review|escape:'htmlall':'UTF-8' nofilter}"  onkeyup="check_inpSubjectReview();" onblur="check_inpSubjectReview();" />
            <div class="errorTxtAdd" id="error_subject-review"></div>
        {/if}

        {if strlen($gsnipreviewdatareview.text_review)>0}
        <label for="text-review" >{l s='Review Text' mod='gsnipreview'}<sup class="required">*</sup></label>
        <textarea cols="42" rows="7" id="text-review" name="text-review" onkeyup="check_inpTextReview();" onblur="check_inpTextReview();">{$gsnipreviewdatareview.text_review|escape:'htmlall':'UTF-8' nofilter}</textarea>
            <div id="error_text-review" class="errorTxtAdd"></div>
        {/if}
    </div>



    <div id="footer-add-review-form-review">
        <button onclick="modify_my_review()"  value="{l s='Modify review' mod='gsnipreview'}" class="btn btn-success">{l s='Modify review' mod='gsnipreview'}</button>
    </div>


    </div>

{literal}
<script type="text/javascript">



    {/literal}{if strlen($gsnipreviewdatareview.text_review)>0}{literal}
    function check_inpTextReview()
    {

        var text_review = trim(document.getElementById('text-review').value);

        if (text_review.length == 0)
        {
            field_state_change('text-review','failed', '{/literal}{$gsnipreviewrcmy_msg1|escape:'htmlall':'UTF-8'}{literal}');
            return false;
        }
        field_state_change('text-review','success', '');
        return true;
    }
    {/literal}{/if}{literal}

    {/literal}{if strlen($gsnipreviewdatareview.title_review)>0}{literal}
    function check_inpSubjectReview()
    {

        var subject_review = trim(document.getElementById('subject-review').value);

        if (subject_review.length == 0)
        {
            field_state_change('subject-review','failed', '{/literal}{$gsnipreviewrcmy_msg2|escape:'htmlall':'UTF-8'}{literal}');
            return false;
        }
        field_state_change('subject-review','success', '');
        return true;
    }
    {/literal}{/if}{literal}






    {/literal}{if $gsnipreviewratings_on == 1}{literal}

    {/literal}{if $gsnipreviewdatareview.criterions|@count > 0}

    {foreach from=$gsnipreviewdatareview.criterions item='criterion'}

        {if $criterion.rating >0}

        {literal}
            var rating_checked{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal} = true;
         {/literal}{else}{literal}
            var rating_checked{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal} = false;
        {/literal}{/if}{literal}

    {/literal}{/foreach}

    {else}{literal}

    {/literal}{if $gsnipreviewdatareview.rating>0}{literal}
        var rating_checked = true;
    {/literal}{else}{literal}
        var rating_checked = false;
    {/literal}{/if}{literal}

    {/literal}{/if}{literal}





    {/literal}{if $gsnipreviewdatareview.criterions|@count > 0}



    {foreach from=$gsnipreviewdatareview.criterions item='criterion'}{literal}

    function check_inpRatingReview{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}()
    {

        if(!rating_checked{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}){
            field_state_change('rat_rel{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}','failed', '{/literal}{$gsnipreviewrcmy_msg3|escape:'htmlall':'UTF-8'} {$criterion.name|escape:'htmlall':'UTF-8'}{literal}');
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
            field_state_change('rat_rel','failed', '{/literal}{$gsnipreviewrcmy_msg4|escape:'htmlall':'UTF-8'}{literal}');
            return false;
        }
        field_state_change('rat_rel', 'success', '');
        return true;

    }


    {/literal}{/if}{literal}

    {/literal}{/if}{literal}


    function modify_my_review(){

        {/literal}{if $gsnipreviewratings_on == 1}{literal}

        {/literal}{if $gsnipreviewdatareview.criterions|@count > 0}

        {foreach from=$gsnipreviewdatareview.criterions item='criterion'}{literal}

        var is_rating{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal} = check_inpRatingReview{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}();

        {/literal}{/foreach}{literal}

        {/literal}{else}{literal}

        var is_rating = check_inpRatingReview();

        {/literal}{/if}{literal}

        {/literal}{/if}{literal}


        {/literal}{if strlen($gsnipreviewdatareview.title_review)>0}{literal}
        var is_subject = check_inpSubjectReview();
        {/literal}{/if}{literal}


        {/literal}{if strlen($gsnipreviewdatareview.text_review)>0}{literal}
        var is_text =  check_inpTextReview();
        {/literal}{/if}{literal}


            if(
                    {/literal}{if $gsnipreviewratings_on == 1}{literal}
                 {/literal}{if $gsnipreviewdatareview.criterions|@count > 0}

                 {foreach from=$gsnipreviewdatareview.criterions item='criterion'}{literal}

                is_rating{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal} &&

                {/literal}{/foreach}{literal}

                {/literal}{else}{literal}

                is_rating &&

                {/literal}{/if}{literal}

                {/literal}{/if}{literal}

               {/literal}{if strlen($gsnipreviewdatareview.title_review)>0}{literal}
                    is_subject &&
               {/literal}{/if}{literal}
                {/literal}{if strlen($gsnipreviewdatareview.text_review)>0}{literal}
                    is_text &&
                {/literal}{/if}{literal}
                true
                ){

            var id_review = {/literal}{$gsnipreviewdatareview.id|escape:'htmlall':'UTF-8'}{literal};



            $.post(baseDir+'modules/gsnipreview/reviews.php',
                    {action:'change-wait',
                    review_id:{/literal}{$gsnipreviewreview_id|escape:'htmlall':'UTF-8'}{literal},
                    title_review: $('#subject-review').val(),
                    text_review: $('#text-review').val(),

                        {/literal}{if $gsnipreviewratings_on == 1}{literal}

                        {/literal}{if $gsnipreviewdatareview.criterions|@count > 0}

                        {foreach from=$gsnipreviewdatareview.criterions item='criterion'}{literal}
                        rating{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}:$('#rat_rel{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}').val(),
                        {/literal}{/foreach}{literal}

                        {/literal}{else}{literal}

                        rating:$('#rat_rel').val(),

                        {/literal}{/if}{literal}

                        {/literal}{/if}{literal}
                    },
                    function (data) {

                        if (data.status == 'success') {


                            $('#fb-con-wrapper').remove();
                            $('#fb-con').remove();


                            $('#changed_review'+id_review).html('');
                            var html = '<img alt="{/literal}{$gsnipreviewrcmy_msg5|escape:'htmlall':'UTF-8'}{literal}"'+
                                        'title="{/literal}{$gsnipreviewrcmy_msg5|escape:'htmlall':'UTF-8'}{literal}"'+
                                        'src="{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{literal}img/admin/enabled.gif"/>';
                            $('#changed_review'+id_review).html(html);

                            window.location.reload();



                        } else {

                            alert(data.message);

                        }
                    }, 'json');
            }

        }


</script>
{/literal}


