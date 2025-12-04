{*
 *
 * SERG
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 *
 /*
 *
 * @author    SERG
 * @category social_networks
 * @package gsnipreview
 * @copyright Copyright SERG
 * @license   SERG
 *
*}

{extends file="helpers/form/form.tpl"}
{block name="field"}
    {if $input.type == 'language_item' || $input.type == 'id_item' || $input.type == 'shop_item'}


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
        <div class="col-lg-9 {$input.name|escape:'htmlall':'UTF-8'}">

            <input type="checkbox" name="{$input.name|escape:'htmlall':'UTF-8'}" id="{$input.name|escape:'htmlall':'UTF-8'}"
                   value="1" {if $input.values.value == 1} checked="checked"{/if} />



            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>


    {elseif $input.type == 'avatar_custom'}

    <div class="col-lg-9 margin-form">

        <input type="hidden" name="id_customer" value="{$input.id_customer|escape:'htmlall':'UTF-8'}" />

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
        {if strlen($input.value)>0 && $input.is_exist_ava>0}
            <input type="radio" name="post_images" checked="" style="display: none">
            <img src="{$input.value|escape:'htmlall':'UTF-8'}" />
            <br/>
            <a class="delete_product_image btn btn-default" href="javascript:void(0)"
               onclick = "delete_avatar_storereviews({$input.id_item|escape:'htmlall':'UTF-8'},{$input.id_customer|escape:'htmlall':'UTF-8'});"
               style="margin-top: 10px">
                <i class="icon-trash"></i> {l s='Delete avatar and use standart empty avatar' mod='gsnipreview'}
            </a>

        {else}
        <img src = "../modules/gsnipreview/views/img/avatar_m.gif" />
        {/if}
        </span>

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



    {elseif $input.type == 'text_custom'}



        <div class="col-lg-4">

            <div class="input-group">
                <input type="text" name="{$input.name|escape:'htmlall':'UTF-8'}" value="{$input.value|escape:'htmlall':'UTF-8'}" />
            </div>
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
