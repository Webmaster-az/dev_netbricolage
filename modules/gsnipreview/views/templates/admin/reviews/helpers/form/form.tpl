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

{extends file="helpers/form/form.tpl"}
{block name="field"}
	{if $input.type == 'text_rating_custom'}

        <div class="col-lg-9 ">
            <div class="form-group">

                {literal}
                <script type="text/javascript">

                    var module_dir_admin = '{/literal}{$module_dir|escape:'htmlall':'UTF-8'}{literal}gsnipreview/';
                    var gsnipreview_star_active = '{/literal}{$input.activestar|escape:'htmlall':'UTF-8'}{literal}';
                    var gsnipreview_star_noactive = '{/literal}{$input.noactivestar|escape:'htmlall':'UTF-8'}{literal}';

                </script>
                {/literal}



                {if $input.criterions|@count>0}

                {foreach from=$input.criterions item=criterion}

                <div class="rating-stars-dynamic-item-admin">
                    <span for="rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}"
                           class="float-left rating-stars-dynamic-title-admin">{$criterion.name|escape:'htmlall':'UTF-8'}<sup class="required">*</sup></span>

                    <span class="rat rating-stars-dynamic-admin">
                                                        <span onmouseout="read_rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}');">

                                                            <img  onmouseover="_rating_efect_rev(1,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onmouseout="_rating_efect_rev(1,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',1); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true; "
                                                                  src="{$input.base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$input.noactivestar|escape:'htmlall':'UTF-8'}"
                                                                  alt="" id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_1" />

                                                            <img  onmouseover="_rating_efect_rev(2,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onmouseout="_rating_efect_rev(2,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',2); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true;"
                                                                  src="{$input.base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$input.noactivestar|escape:'htmlall':'UTF-8'}"
                                                                  alt="" id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_2" />

                                                            <img  onmouseover="_rating_efect_rev(3,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onmouseout="_rating_efect_rev(3,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',3); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true;"
                                                                  src="{$input.base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$input.noactivestar|escape:'htmlall':'UTF-8'}"
                                                                  alt=""  id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_3" />
                                                            <img  onmouseover="_rating_efect_rev(4,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onmouseout="_rating_efect_rev(4,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',4); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true;"
                                                                  src="{$input.base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$input.noactivestar|escape:'htmlall':'UTF-8'}"
                                                                  alt=""  id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_4" />
                                                            <img  onmouseover="_rating_efect_rev(5,0,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onmouseout="_rating_efect_rev(5,1,'rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}')"
                                                                  onclick = "rating_review_shop('rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}',5); rating_checked{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}=true;"
                                                                  src="{$input.base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$input.noactivestar|escape:'htmlall':'UTF-8'}"
                                                                  alt=""  id="img_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}_5" />
                                                        </span>
                        {if strlen($criterion.description)>0}
                            <div class="tip-criterion-description">{$criterion.description|escape:'htmlall':'UTF-8'}</div>
                        {/if}
                    </span>
                    <input type="hidden" id="rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}"
                            name="rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}" value="{if isset($criterion.rating)}{$criterion.rating|escape:'htmlall':'UTF-8'}{else}0{/if}"/>
                    {literal}
                        <script type="text/javascript">
                            $(document).ready(function(){
                                rating_review_shop('rat_rel{/literal}{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}{literal}',{/literal}{if isset($criterion.rating)}{$criterion.rating|escape:'htmlall':'UTF-8'}{else}0{/if}{literal});
                            });
                        </script>
                    {/literal}
                    <div class="clr"></div>
                    <div class="errorTxtAdd" id="error_rat_rel{$criterion.id_gsnipreview_review_criterion|escape:'htmlall':'UTF-8'}"></div>

                    </div>
                {/foreach}

            <br/>
         {else}
            <div class="rating-stars-dynamic-item-admin">
            <span for="rat_rel" class="float-left rating-stars-dynamic-title-admin">{l s='Total Rating' mod='gsnipreview'}<sup class="required">*</sup></span>

            <div class="rat rating-stars-dynamic-admin">
                                                        <span onmouseout="read_rating_review_shop('rat_rel');">
                                                            <img  onmouseover="_rating_efect_rev(1,0,'rat_rel')" onmouseout="_rating_efect_rev(1,1,'rat_rel')"
                                                                  onclick = "rating_review_shop('rat_rel',1); rating_checked=true; "
                                                                  src="{$input.base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$input.noactivestar|escape:'htmlall':'UTF-8'}"
                                                                  alt=""
                                                                  id="img_rat_rel_1" />
                                                            <img  onmouseover="_rating_efect_rev(2,0,'rat_rel')" onmouseout="_rating_efect_rev(2,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',2); rating_checked=true;" src="{$input.base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$input.noactivestar|escape:'htmlall':'UTF-8'}" alt=""  id="img_rat_rel_2" />
                                                            <img  onmouseover="_rating_efect_rev(3,0,'rat_rel')" onmouseout="_rating_efect_rev(3,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',3); rating_checked=true;" src="{$input.base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$input.noactivestar|escape:'htmlall':'UTF-8'}" alt=""  id="img_rat_rel_3" />
                                                            <img  onmouseover="_rating_efect_rev(4,0,'rat_rel')" onmouseout="_rating_efect_rev(4,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',4); rating_checked=true;" src="{$input.base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$input.noactivestar|escape:'htmlall':'UTF-8'}" alt=""  id="img_rat_rel_4" />
                                                            <img  onmouseover="_rating_efect_rev(5,0,'rat_rel')" onmouseout="_rating_efect_rev(5,1,'rat_rel')" onclick = "rating_review_shop('rat_rel',5); rating_checked=true;" src="{$input.base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$input.noactivestar|escape:'htmlall':'UTF-8'}" alt=""  id="img_rat_rel_5" />
                                                        </span>
            </div>
            <input type="hidden" id="rat_rel" name="rat_rel" value="{$input.rating|escape:'htmlall':'UTF-8'}"/>
            {literal}
                <script type="text/javascript">
                    $(document).ready(function(){
                        rating_review_shop('rat_rel',{/literal}{$input.rating|escape:'htmlall':'UTF-8'}{literal});
                    });
                </script>
            {/literal}
            <div class="clr"></div>
            <div class="errorTxtAdd" id="error_rat_rel"></div>
            </div>
        {/if}




            {*<pre>{$input.criterions|@var_dump}*}
            </div>

            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'language_item' || $input.type == 'id_item' || $input.type == 'ip_item'}

        {if $input.type == 'language_item'}
            <input type="hidden" name="id_lang" value="{$input.id_lang|escape:'htmlall':'UTF-8'}" />

        {/if}
        <div class="col-lg-9 margin-form">


            <div class="form-group margin-item-form-top-left">
                <span class="badge">
                {$input.values|escape:'htmlall':'UTF-8'}
                    </span>
            </div>





            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>
    {elseif $input.type == 'review_url'}

        <div class="col-lg-9 margin-form">


            <div class="form-group margin-item-form-top-left">
                <span class="badge">
                    <a href="{$input.values|escape:'htmlall':'UTF-8'}" target="_blank" title="{$input.values|escape:'htmlall':'UTF-8'}">
                        {if isset($input.name_product)}
                            {$input.name_product|escape:'htmlall':'UTF-8'}
                        {else}
                            {$input.values|escape:'htmlall':'UTF-8'}
                        {/if}
                    </a>

                    </span>
            </div>

            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'item_date'}

        <div class="row">
            <div class="input-group col-lg-4">
                <input id="{if isset($input.id)}{$input.id|escape:'htmlall':'UTF-8'}{else}{$input.name|escape:'htmlall':'UTF-8'}{/if}"
                       type="text" data-hex="true"
                       {if isset($input.class)}class="{$input.class}"
                       {else}class="item_datepicker"{/if} name="time_add" value="{$input.time_add|escape:'html':'UTF-8'}" />
                <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>
            </div>
        </div>

    {literal}

        <script type="text/javascript">
            $('document').ready( function() {

                var dateObj = new Date();
                var hours = dateObj.getHours();
                var mins = dateObj.getMinutes();
                var secs = dateObj.getSeconds();
                if (hours < 10) { hours = "0" + hours; }
                if (mins < 10) { mins = "0" + mins; }
                if (secs < 10) { secs = "0" + secs; }
                var time = " "+hours+":"+mins+":"+secs;

                if ($(".item_datepicker").length > 0)
                $(".item_datepicker").datepicker({prevText: '',nextText: '',dateFormat: 'yy-mm-dd'+time});

            });
        </script>
    {/literal}


    {elseif $input.type == 'shop_item'}


        <div class="col-lg-9 margin-form">


            <select id="ids_shop" class=" fixed-width-xl" name="ids_shop">
                {foreach $input.values as $shop}

                <option value="{$shop.id_shop|escape:'htmlall':'UTF-8'}">{$shop.name|escape:'htmlall':'UTF-8'}</option>
                {/foreach}
            </select>


            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>


    {elseif $input.type == 'text_custom'}

        <div class="col-lg-9 margin-form">


            <div class="form-group">

                <div id="man-pub-block" class="col-lg-9">

                    <div id="divAccessories"></div>

                    <input type="hidden" name="inputAccessories" id="inputAccessories" value="" />

                    <div id="ajax_choose_product" style="padding:6px; padding-top:2px; width:100%">
                        <input type="text" value="" id="product_autocomplete_input" style="width:50%" autocomplete="off" />
                    </div>


                    {literal}
                        <script type="text/javascript">
                            $('document').ready( function() {
                                if($('#divAccessories').length){

                                    initAccessoriesAutocomplete();

                                }
                            });
                        </script>
                    {/literal}

                    <p class="help-block">
                        {l s='Begin typing the first letters of the product name, then select the product from the drop-down list' mod='gsnipreview'}
                    </p>
                </div>

            </div>


            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>
    {elseif $input.type == 'text_custom_customer'}

        <div class="col-lg-9 margin-form">


            <div class="form-group">

                <div id="man-pub-block" class="col-lg-9">

                    <div id="divCustomers"></div>
                    <input type="hidden" name="inputCustomersToken" id="inputCustomersToken" value="{$input.token|escape:'htmlall':'UTF-8'}" />
                    <input type="hidden" name="inputCustomers" id="inputCustomers" value="" />

                    <div id="ajax_choose_customer" style="padding:6px; padding-top:2px; width:100%">
                        <input type="text" value="" id="customer_autocomplete_input" style="width:50%" autocomplete="off" />
                    </div>



                    {literal}
                        <script type="text/javascript">
                            $('document').ready( function() {



                                if($('#divCustomers').length){

                                    initCustomersAutocomplete();

                                }


                            });
                        </script>
                    {/literal}

                    <p class="help-block">
                        {l s='Begin typing the first letters of the customer name, then select the customer from the drop-down list' mod='gsnipreview'}
                    </p>
                </div>

            </div>


            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'language_item_add'}


        <div class="col-lg-9 margin-form">


            <select id="ids_lang" class=" fixed-width-xl" name="ids_lang">
                {foreach $input.values as $language}

                    <option value="{$language.id_lang|escape:'htmlall':'UTF-8'}">{$language.name|escape:'htmlall':'UTF-8'}</option>
                {/foreach}
            </select>


            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>
    {elseif $input.type == 'avatar_custom'}

        <div class="col-lg-9 margin-form">

            <div class="form-group">
                <div class="col-lg-6" >
                    <input id="{$input.name|escape:'htmlall':'UTF-8'}" type="file" name="{$input.name|escape:'htmlall':'UTF-8'}" class="hide" />
                    <div class="dummyfile input-group">
                        <span class="input-group-addon"><i class="icon-file"></i></span>
                        <input id="{$input.name|escape:'htmlall':'UTF-8'}-name" type="text" class="disabled" name="filename" readonly />
							<span class="input-group-btn">
								<button id="{$input.name|escape:'htmlall':'UTF-8'}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                                    <i class="icon-folder-open"></i> {l s='Choose a file' mod='gsnipreview'}
                                </button>
							</span>
                    </div>

                    {literal}
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $('#{/literal}{$input.name|escape:'htmlall':'UTF-8'}{literal}-selectbutton').click(function(e){
                                $('#{/literal}{$input.name|escape:'htmlall':'UTF-8'}{literal}').trigger('click');
                            });
                            $('#{/literal}{$input.name|escape:'htmlall':'UTF-8'}{literal}').change(function(e){
                                var val = $(this).val();
                                var file = val.split(/[\/]/);
                                $('#{/literal}{$input.name|escape:'htmlall':'UTF-8'}{literal}-name').val(file[file.length-1]);
                            });
                        });
                    </script>
                    {/literal}


                </div>

            </div>
            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                    <br/>
                    <span style="color:black:font-size:13px">{l s='Max file size in php.ini' mod='gsnipreview'}: <b style="color:green">{$input.max_upload_info|escape:'htmlall':'UTF-8'}</b></span>
                </p>
            {/if}
            {if isset($input.is_demo) && !empty($input.is_demo)}
                {$input.is_demo|escape:'quotes':'UTF-8'}
            {/if}

            <span class="avatar-form">
                <input type="hidden" name="id_customer" value="{$input.id_customer|escape:'htmlall':'UTF-8'}" />

                {if $input.is_exist_ava>0}
                    <input type="radio" name="post_images" checked="" style="display: none">
                    <img src="{$input.value|escape:'htmlall':'UTF-8'}" />
                    <br/>
                    <a class="delete_product_image btn btn-default" href="javascript:void(0)"
                       onclick = "delete_avatar({$input.id_item|escape:'htmlall':'UTF-8'},{$input.id_customer|escape:'htmlall':'UTF-8'});"
                       style="margin-top: 10px">
                        <i class="icon-trash"></i> {l s='Delete avatar and use standart empty avatar' mod='gsnipreview'}
                    </a>

                {else}
                <img src = "../modules/gsnipreview/views/img/avatar_m.gif" />
                {/if}
            </span>

        </div>
    {elseif $input.type == 'files_custom'}

        <div class="col-lg-9 margin-form">


            {if isset($input.is_demo) && !empty($input.is_demo)}
                {$input.is_demo|escape:'quotes':'UTF-8'}
            {/if}



            <div  class="row-custom">
                {foreach from=$input.value item=file}
                    <div class="col-sm-2-custom files-review-gsnipreview-admin" id="file-custom-{$file.id|escape:'htmlall':'UTF-8'}">
                        <div class="text-align-center">
                            <a class="fancybox shown" data-fancybox-group="other-views" href="{$input.base_dir_ssl|escape:'htmlall':'UTF-8'}{$file.full_path|escape:'htmlall':'UTF-8'}">
                                <img src="{$input.base_dir_ssl|escape:'htmlall':'UTF-8'}{$file.full_path|escape:'htmlall':'UTF-8'}" width="105" height="105" class="img-thumbnail-custom" alt="" />
                            </a>
                        </div>
                        <br/>
                        <div class="text-align-center">
                            <a class="delete_review_file btn btn-default" href="javascript:void(0)"
                               onclick = "delete_file({$file.id|escape:'htmlall':'UTF-8'});"
                               style="margin-top: 10px">
                                <i class="icon-trash"></i> {l s='Delete' mod='gsnipreview'}
                            </a>
                        </div>
                    </div>

                {/foreach}
            </div>
            {literal}
            <script type="text/javascript">
                $(document).ready(function() {
                    $("a.fancybox").fancybox();
                });
            </script>
            {/literal}
        </div>

    {elseif $input.type == 'customer_url'}

        <div class="col-lg-9 margin-form">


            <div class="form-group margin-item-form-top-left">
                <span class="badge">
                    <a href="{$input.url|escape:'htmlall':'UTF-8'}" target="_blank" title="{$input.url|escape:'htmlall':'UTF-8'}">{$input.values|escape:'htmlall':'UTF-8'}</a>

                    </span>
            </div>



            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'checkbox_custom'}
        <div class="col-lg-9 {$input.name|escape:'htmlall':'UTF-8'} ">

            <input type="checkbox" name="{$input.name|escape:'htmlall':'UTF-8'}" id="{$input.name|escape:'htmlall':'UTF-8'}"
                   value="1" {if $input.values.value == 1} checked="checked"{/if} />



            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

	{else}
		{$smarty.block.parent}
	{/if}
{/block}
