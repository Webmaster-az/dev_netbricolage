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
    {if $input.type == 'block_allinfo'}


            <div class="col-lg-6 {$input.name|escape:'htmlall':'UTF-8'}">
                <div class="panel">



                    <table class="table">
                        <thead>
                        <tr>

                            <th><b>{l s='Block' mod='gsnipreview'}</b></th>
                            <th><b>{l s='Position' mod='gsnipreview'}</b></th>
                            <th><b>{l s='Width' mod='gsnipreview'}</b></th>
                            <th><b>{l s='Status' mod='gsnipreview'}</b></th>
                        </tr>
                        </thead>
                        <tbody>


                       {foreach $input.values as $key => $cms_item}
                            <tr class="alt_row">
                                <td>
                                    {$cms_item.name|escape:'htmlall':'UTF-8'}

                                </td>
                                <td>
                                    <div class="col-lg-12">

                                         <select id="p{$key|escape:'htmlall':'UTF-8'}" class="col-sm-12" name="p{$key|escape:'htmlall':'UTF-8'}">
                                             {if $key == 'allinfo_home'}
                                                {foreach $input.available_pos_home as $key_pos => $cms_item_pos}
                                                    <option {if $cms_item.position == $key_pos} selected="selected" {/if}
                                                            value="{$key_pos|escape:'htmlall':'UTF-8'}">{$cms_item_pos|escape:'htmlall':'UTF-8'}</option>

                                                {/foreach}
                                             {else}
                                                 {foreach $input.available_pos as $key_pos => $cms_item_pos}
                                                     <option {if $cms_item.position == $key_pos} selected="selected" {/if}
                                                             value="{$key_pos|escape:'htmlall':'UTF-8'}">{$cms_item_pos|escape:'htmlall':'UTF-8'}</option>

                                                 {/foreach}

                                             {/if}

                                         </select>
                                     </div>

                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" name="{$cms_item.width.name|escape:'htmlall':'UTF-8'}"
                                               value="{$cms_item.width.width|escape:'htmlall':'UTF-8'}" />
                                        <span class="input-group-addon">&nbsp;%</span>


                                    </div>

                                </td>
                                <td>
                                    <div class="checkbox">

                                        <label for="{$key|escape:'htmlall':'UTF-8'}">
                                            {*{$cms_item.status}*}
                                            <input type="checkbox" {if $cms_item.status == $key} checked="checked"{/if}
                                                   value="{$key|escape:'htmlall':'UTF-8'}" id="{$key|escape:'htmlall':'UTF-8'}"
                                                   name="{$key|escape:'htmlall':'UTF-8'}"/>
                                        </label>
                                    </div>

                                </td>
                            </tr>
                        {/foreach}


                        </tbody>
                    </table>
                </div>

                {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
                {/if}
        </div>
    {elseif $input.type == 'block_last_reviews'}


        <div class="col-lg-9 {$input.name|escape:'htmlall':'UTF-8'}">
            <div class="panel">



                <table class="table">
                    <thead>
                    <tr>

                        <th><b>{l s='Page' mod='gsnipreview'}</b></th>
                        <th><b>{l s='Position' mod='gsnipreview'}</b></th>
                        <th><b>{l s='Product Images size' mod='gsnipreview'}</b></th>
                        <th><b>{l s='Number of displayed reviews' mod='gsnipreview'}</b></th>
                        <th><b>{l s='Width' mod='gsnipreview'}</b></th>
                        <th><b>{l s='Truncate reviews' mod='gsnipreview'}</b></th>
                        <th><b>{l s='Status' mod='gsnipreview'}</b></th>

                    </tr>
                    </thead>
                    <tbody>


                    {foreach $input.values as $key => $cms_item}
                        <tr class="alt_row">
                            <td>
                                {$cms_item.name|escape:'htmlall':'UTF-8'}

                            </td>
                            <td>
                                <div class="col-lg-12">

                                    <select id="p{$key|escape:'htmlall':'UTF-8'}" class="col-sm-12" name="p{$key|escape:'htmlall':'UTF-8'}">
                                        {if $key == 'blocklr_home'}
                                            {foreach $input.available_pos_home as $key_pos => $cms_item_pos}
                                                <option {if $cms_item.position == $key_pos} selected="selected" {/if}
                                                        value="{$key_pos|escape:'htmlall':'UTF-8'}">{$cms_item_pos|escape:'htmlall':'UTF-8'}</option>

                                            {/foreach}
                                         {elseif $key == 'blocklr_chook'}
                                            {foreach $input.available_pos_chook as $key_pos => $cms_item_pos}
                                                <option {if $cms_item.position == $key_pos} selected="selected" {/if}
                                                        value="{$key_pos|escape:'htmlall':'UTF-8'}">{$cms_item_pos|escape:'htmlall':'UTF-8'}</option>

                                            {/foreach}
                                        {else}
                                            {foreach $input.available_pos as $key_pos => $cms_item_pos}
                                                <option {if $cms_item.position == $key_pos} selected="selected" {/if}
                                                        value="{$key_pos|escape:'htmlall':'UTF-8'}">{$cms_item_pos|escape:'htmlall':'UTF-8'}</option>

                                            {/foreach}

                                        {/if}

                                    </select>
                                </div>

                            </td>
                            <td>
                                <div class="col-lg-12">
                                    {*{$cms_item.imsize|@var_dump}*}
                                    <select id="i{$key|escape:'htmlall':'UTF-8'}" class="col-sm-12" name="i{$key|escape:'htmlall':'UTF-8'}">

                                            {foreach $input.image_sizes as $cms_item_im}
                                                <option {if $cms_item.imsize.imsize == $cms_item_im.id} selected="selected" {/if}
                                                        value="{$cms_item_im.id|escape:'htmlall':'UTF-8'}">{$cms_item_im.name|escape:'htmlall':'UTF-8'}</option>

                                            {/foreach}


                                    </select>
                                </div>

                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="{$cms_item.number_display_reviews.name|escape:'htmlall':'UTF-8'}"
                                           value="{$cms_item.number_display_reviews.number_display_reviews|escape:'htmlall':'UTF-8'}" />

                                </div>

                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="{$cms_item.width.name|escape:'htmlall':'UTF-8'}"
                                           value="{$cms_item.width.width|escape:'htmlall':'UTF-8'}" />
                                    <span class="input-group-addon">&nbsp;%</span>


                                </div>

                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="{$cms_item.truncate.name|escape:'htmlall':'UTF-8'}"
                                           value="{$cms_item.truncate.truncate|escape:'htmlall':'UTF-8'}" />
                                    <span class="input-group-addon">&nbsp;{l s='chars' mod='gsnipreview'}</span>


                                </div>

                            </td>
                            <td>
                                <div class="checkbox">

                                    <label for="{$key|escape:'htmlall':'UTF-8'}">
                                        {*{$cms_item.status}*}
                                        <input type="checkbox" {if $cms_item.status == $key} checked="checked"{/if}
                                               value="{$key|escape:'htmlall':'UTF-8'}" id="{$key|escape:'htmlall':'UTF-8'}"
                                               name="{$key|escape:'htmlall':'UTF-8'}"/>
                                    </label>
                                </div>

                            </td>
                        </tr>
                    {/foreach}


                    </tbody>
                </table>
            </div>

            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'text_custom_email'}



        <div class="col-lg-4">

            <div class="input-group">
                <input type="text" name="{$input.name|escape:'htmlall':'UTF-8'}"
                       value="{$input.value|escape:'htmlall':'UTF-8'}" />
                <span class="input-group-addon icon fa-envelope"><b>&nbsp;</b></span>


            </div>
            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>


    {elseif $input.type == 'text_custom_delay'}



        <div class="col-lg-6">

            <div class="input-group">
                <input type="text" name="{$input.name|escape:'htmlall':'UTF-8'}"
                       value="{$input.value|escape:'htmlall':'UTF-8'}" />
                <span class="input-group-addon icon icon-clock-o"><b>&nbsp;{l s='second' mod='gsnipreview'}(s)</b></span>


            </div>
            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'select_stars_custom'}
        <div class="col-lg-6 {$input.name|escape:'htmlall':'UTF-8'}">

            <span class="switch prestashop-switch fixed-width-lg" style="float:left;">

                <input type="radio" {if $input.values.value == 1}checked="checked"{/if} value="1" id="{$input.name|escape:'htmlall':'UTF-8'}_on" name="{$input.name|escape:'htmlall':'UTF-8'}">
                <label for="{$input.name|escape:'htmlall':'UTF-8'}_on">{l s='Yes' mod='gsnipreview'}</label>

                <input type="radio" value="0" {if $input.values.value == 0}checked="checked"{/if} id="{$input.name|escape:'htmlall':'UTF-8'}_off" name="{$input.name|escape:'htmlall':'UTF-8'}">
                <label for="{$input.name|escape:'htmlall':'UTF-8'}_off">{l s='No' mod='gsnipreview'}</label>

                <a class="slide-button btn"></a>

			</span>
            <div style="float:left;margin-left:10px">
                <img src="../modules/gsnipreview/views/img/ratingsblock-yellow.png" class="img-responsive" id="star-active-yellow-block" style="{if $input.values.stylestars == "style1"}display:inline {else} display:none{/if}" />
                <img src="../modules/gsnipreview/views/img/ratingsblock-green.png" class="img-responsive" id="star-active-green-block" style="{if $input.values.stylestars == "style2"}display:inline {else} display:none{/if}" />
                <img src="../modules/gsnipreview/views/img/ratingsblock-blue.png" class="img-responsive" id="star-active-blue-block" style="{if $input.values.stylestars == "style3"}display:inline {else} display:none{/if}" />

            </div>
            <div style="clear: both"></div>

            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}

        </div>

    {elseif $input.type == 'select_stars'}
        <div class="col-lg-6 {$input.name|escape:'htmlall':'UTF-8'}">




					<select class="select" name="stylestars" onChange="selectImgRating(this.selectedIndex)"
							id="stylestars" style="float:left;width:50%">
						<option {if $input.values.stylestars == "style1"} selected="selected" {/if} value="style1">{l s='Yellow Stars' mod='gsnipreview'}</option>
						<option {if $input.values.stylestars == "style2"} selected="selected" {/if} value="style2">{l s='Green Stars' mod='gsnipreview'}</option>
						<option {if $input.values.stylestars == "style3"} selected="selected" {/if} value="style3">{l s='Blue Stars' mod='gsnipreview'}</option>

					</select>

					<div style="float:left">
					<img src="../modules/gsnipreview/views/img/star-active-yellow.png" id="star-active-yellow" style="padding:5px 0 0 5px;{if $input.values.stylestars == "style1"}display:inline {else} display:none{/if}"/>
					<img src="../modules/gsnipreview/views/img/star-active-green.png" id="star-active-green" style="padding:5px 0 0 5px;{if $input.values.stylestars == "style2"}display:inline {else} display:none{/if}"/>
					<img src="../modules/gsnipreview/views/img/star-active-blue.png" id="star-active-blue" style="padding:5px 0 0 5px;{if $input.values.stylestars == "style3"}display:inline {else} display:none{/if}"/>
					</div>
                    <div style="clear: both"></div>




            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'block_radio_buttons_custom'}


        <div class="col-lg-6 {$input.name|escape:'htmlall':'UTF-8'}">
            <div class="panel">


                <table class="table mitrocops-table-td">

                    <tbody>


                       <tr class="alt_row">
                            <td>
                                <input type="radio" value="firston" id="pinterestbuttons" name="pinterestbuttons"
                                {if $input.values.style == "firston"} checked="checked" {/if}>
                            </td>
                            <td>
                                <img src="../modules/gsnipreview/views/img/p-top.png" />
                            </td>
                            <td>
                                <input type="radio" value="secondon" id="pinterestbuttons" name="pinterestbuttons"
                                    {if $input.values.style == "secondon"} checked="checked" {/if}>
                            </td>
                            <td>
                                <img src="../modules/gsnipreview/views/img/p-horizontal.png" />
                            </td>
                            <td>
                                <input type="radio" value="threeon" id="pinterestbuttons" name="pinterestbuttons"
                                    {if $input.values.style == "threeon"} checked="checked" {/if}
                                >
                            </td>
                            <td>
                                <img src="../modules/gsnipreview/views/img/p-none.png" />
                            </td>
                        </tr>



                    </tbody>
                </table>
            </div>

            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>


    {elseif $input.type == 'block_radio_buttons_reviews_custom'}


        <div class="col-lg-6 {$input.name|escape:'htmlall':'UTF-8'}">
            <div class="panel">


                <table class="table mitrocops-table-td">

                    <tbody>


                    <tr class="alt_row">
                        <td>
                            <input type="radio" value="reg" id="whocanadd" name="whocanadd"
                                    {if $input.values.value == "reg"} checked="checked" {/if}/>
                                    {l s='Only registered users' mod='gsnipreview'}
                        </td>

                        <td>
                            <input type="radio" value="buy" id="whocanadd" name="whocanadd"
                                    {if $input.values.value == "buy"} checked="checked" {/if}/>
                            {l s='Only users who already bought the product' mod='gsnipreview'}
                        </td>
                        <td>
                            <input type="radio" value="all" id="whocanadd" name="whocanadd"
                                    {if $input.values.value == "all"} checked="checked" {/if}
                                    />
                            {l s='All users' mod='gsnipreview'}
                        </td>
                    </tr>



                    </tbody>
                </table>
            </div>

            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'checkbox_custom'}
        <div class="col-lg-9 {$input.name|escape:'htmlall':'UTF-8'}">

            {foreach $input.values.query as $value}
                {assign var=id_checkbox value=$value[$input.values.id]}
                <div class="checkbox{if isset($input.expand) && strtolower($input.expand.default) == 'show'} hidden{/if}">

                  {strip}
                        <label for="{$id_checkbox|escape:'htmlall':'UTF-8'}">
                            <input type="checkbox" name="{$id_checkbox|escape:'htmlall':'UTF-8'}" id="{$id_checkbox|escape:'htmlall':'UTF-8'}" class="{if isset($input.class)}{$input.class|escape:'htmlall':'UTF-8'}{/if}"{if isset($value.val)} value="{$value.val|escape:'htmlall':'UTF-8'}"{/if}{if isset($fields_value[$id_checkbox]) && $fields_value[$id_checkbox]} checked="checked"{/if} />
                            {$value[$input.values.name]|escape:'htmlall':'UTF-8'}
                        </label>
                    {/strip}
                </div>
            {/foreach}

            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>
    {elseif $input.type == 'checkbox_custom_email'}
        <div class="col-lg-9 {$input.name|escape:'htmlall':'UTF-8'}">

            <input type="checkbox" name="{$input.name|escape:'htmlall':'UTF-8'}" id="{$input.name|escape:'htmlall':'UTF-8'}"
                   value="1" {if $input.values.value == 1} checked="checked"{/if} />



            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'text_custom_delay_reminder'}



        <div class="col-lg-6">

            <div class="input-group">
                <input type="text" name="{$input.name|escape:'htmlall':'UTF-8'}" id="{$input.name|escape:'htmlall':'UTF-8'}"
                       value="{$input.value|escape:'htmlall':'UTF-8'}" />
                <span class="input-group-addon icon icon-clock-o"><b>&nbsp;{l s='day' mod='gsnipreview'}(s)</b></span>


            </div>
            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'text_custom_order_statuses'}

        {assign var=cms value=$input.value}

        <div class="col-lg-6">

            <div class="panel">

                <table class="table">
                    <thead>
                    <tr>

                        <th>&nbsp;</th>
                        <th><b>{l s='Order status' mod='gsnipreview'}</b></th>
                    </tr>
                    </thead>
                    <tbody>

                    {foreach $cms as $key => $cms_item}
                        <tr class="alt_row">
                            <td>

                                    <div class="input-group">
                                        <input type="checkbox" name="orderstatuses[]"
                                        {foreach $input.orderstatuses as $id_status}
                                                {if $id_status == $cms_item['id_order_state']}
                                                    checked="checked"
                                                {/if}

                                        {/foreach}
                                               value="{$cms_item['id_order_state']|escape:'htmlall':'UTF-8'}" />
                                    </div>
                            </td>
                            <td>


                                    <span style="background-color:{$cms_item['color']|escape:'htmlall':'UTF-8'};color:white;padding:4px;border-radius:5px;line-height:25px;margin:3px 0">
                                        {$cms_item['name']|escape:'htmlall':'UTF-8'}
                                    </span>
                            </td>
                        </tr>
                    {/foreach}


                    </tbody>
                </table>
            </div>

            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'text_custom_orders_import'}




            <div class="input-group col-lg-3" style="float:left;margin-right:10px" id="importoldorders_first">
                <span class="input-group-addon">{l s='start date' mod='gsnipreview'}</span>
                <input id="{if isset($input.id)}{$input.id|escape:'htmlall':'UTF-8'}{else}{$input.name|escape:'htmlall':'UTF-8'}{/if}"
                       type="text" data-hex="true"
                       {if isset($input.class)}class="{$input.class}"
                       {else}class="item_datepicker"{/if} name="start_date" value="" />
                <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>

            </div>
            <div class="input-group col-lg-3" style="float:left">
                <span class="input-group-addon">{l s='end date' mod='gsnipreview'}</span>
                <input id="{if isset($input.id)}{$input.id|escape:'htmlall':'UTF-8'}{else}{$input.name|escape:'htmlall':'UTF-8'}{/if}"
                       type="text" data-hex="true" disabled="disabled"
                       name="end_date" value="{$input.end_date|escape:'htmlall':'UTF-8'}" />
                <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>

            </div>
            <input type="button" value="{l s='Import old orders' mod='gsnipreview'}" onclick="importoldorders();"
                   class="btn btn-success" style="float:left;margin-left:10px"/>
            <div style="clear:both"></div>

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

            function importoldorders(){

                $('#importoldorders_first').parent().css('opacity','0.5');
                var start_date =  $('#orders_import').val();


                $.post('{/literal}{$input.host_url|escape:'htmlall':'UTF-8'}{literal}modules/gsnipreview/reviews_admin.php',
                        {   action:'importoldorders',
                            start_date: start_date
                        },
                        function (data) {
                            if (data.status == 'success') {

                                $('#importoldorders_first').parent().css('opacity','1');
                                var data = data.params.content;
                                //alert(data);

                                $('.alert-danger').parent().remove();
                                $('.alert-success').parent().remove();
                                $('#importoldorders_first').parent().before(data);


                            } else {
                                $('#importoldorders_first').parent().css('opacity','1');
                                alert(data.message);

                            }
                        }, 'json');
            }
        </script>
    {/literal}

        {if isset($input.desc) && !empty($input.desc)}
            <br/>
            <div class="alert alert-info col-lg-offset-3">
                {$input.desc|escape:'htmlall':'UTF-8'}
            </div>

        {/if}



    {elseif $input.type == 'text_custom_orders_import_storereviews'}




        <div class="input-group col-lg-3" style="float:left;margin-right:10px" id="importoldorders_first_storereviews">
            <span class="input-group-addon">{l s='start date' mod='gsnipreview'}</span>
            <input id="{if isset($input.id)}{$input.id|escape:'htmlall':'UTF-8'}{else}{$input.name|escape:'htmlall':'UTF-8'}{/if}"
                   type="text" data-hex="true"
                   {if isset($input.class)}class="{$input.class}"
                   {else}class="item_datepicker_storereviews"{/if} name="start_date" value="" />
            <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>

        </div>
        <div class="input-group col-lg-3" style="float:left">
            <span class="input-group-addon">{l s='end date' mod='gsnipreview'}</span>
            <input id="{if isset($input.id)}{$input.id|escape:'htmlall':'UTF-8'}{else}{$input.name|escape:'htmlall':'UTF-8'}{/if}"
                   type="text" data-hex="true" disabled="disabled"
                   name="end_date" value="{$input.end_date|escape:'htmlall':'UTF-8'}" />
            <span class="input-group-addon"><i class="icon-calendar-empty"></i></span>

        </div>
        <input type="button" value="{l s='Import old orders' mod='gsnipreview'}" onclick="importoldordersstore();"
               class="btn btn-success" style="float:left;margin-left:10px"/>
        <div style="clear:both"></div>

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

                if ($(".item_datepicker_storereviews").length > 0)
                    $(".item_datepicker_storereviews").datepicker({prevText: '',nextText: '',dateFormat: 'yy-mm-dd'+time});

            });

            function importoldordersstore(){

                $('#importoldorders_first_storereviews').parent().css('opacity','0.5');
                var start_date =  $('#orders_import_storereviews').val();


                $.post('{/literal}{$input.host_url|escape:'htmlall':'UTF-8'}{literal}modules/gsnipreview/ajax.php',
                        {   action:'importoldorders',
                            start_date: start_date
                        },
                        function (data) {
                            if (data.status == 'success') {

                                $('#importoldorders_first_storereviews').parent().css('opacity','1');
                                var data = data.params.content;
                                //alert(data);

                                $('.alert-danger').parent().remove();
                                $('.alert-success').parent().remove();
                                $('#importoldorders_first_storereviews').parent().before(data);


                            } else {
                                $('#importoldorders_first_storereviews').parent().css('opacity','1');
                                alert(data.message);

                            }
                        }, 'json');
            }
        </script>
    {/literal}

        {if isset($input.desc) && !empty($input.desc)}
            <br/>
            <div class="alert alert-info col-lg-offset-3">
                {$input.desc|escape:'htmlall':'UTF-8'}
            </div>

        {/if}


    {elseif $input.type == 'cms_pages'}

        {assign var=cms value=$input.values}


        {if count($cms)>0}
            <div class="col-lg-4 {$input.name|escape:'htmlall':'UTF-8'}">
                <div class="panel">

                    <table class="table">
                        <thead>
                        <tr>

                            <th><b>{l s='Currency' mod='gsnipreview'}</b></th>
                            <th><b>{l s='Discount Amount' mod='gsnipreview'}</b></th>
                        </tr>
                        </thead>
                        <tbody>

                        {foreach $cms as $key => $cms_item}
                            <tr class="alt_row">
                                <td>
                                    <div class="checkbox">

                                        <label for="{$key|escape:'htmlall':'UTF-8'}">{$cms_item['name']|escape:'htmlall':'UTF-8'}</label>
                                    </div>

                                </td>
                                <td>
                                    <div class="col-lg-9">
                                        <div class="input-group">
                                            <input type="text" name="{$cms_item['name_item']|escape:'htmlall':'UTF-8'}[{$key|escape:'htmlall':'UTF-8'}]"
                                                   value="{$cms_item['amount']|escape:'htmlall':'UTF-8'}" />
                                            <span class="input-group-addon">&nbsp;{$cms_item['currency']|escape:'htmlall':'UTF-8'}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        {/foreach}


                        </tbody>
                    </table>
                </div>
                {if isset($input.desc) && !empty($input.desc)}
                    <p class="help-block">
                        {$input.desc|escape:'htmlall':'UTF-8'}
                    </p>
                {/if}
            </div>


        {/if}

    {elseif $input.type == 'cms_categories'}

        <div class="col-lg-4 {$input.name|escape:'htmlall':'UTF-8'}">
            <div class="panel">

                {$input.values|escape:'quotes':'UTF-8'}

            </div>
            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'text_custom'}



        <div class="col-lg-4">

            <div class="input-group">
                <input type="text" name="{$input.name|escape:'htmlall':'UTF-8'}"
                       value="{$input.value|escape:'htmlall':'UTF-8'}" />
                <span class="input-group-addon">&nbsp;%</span>


            </div>
            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>



    {elseif $input.type == 'text_validity'}



        <div class="col-lg-4">

            <div class="input-group">
                <input type="text" name="{$input.name|escape:'htmlall':'UTF-8'}"
                       value="{$input.value|escape:'htmlall':'UTF-8'}" />
                <span class="input-group-addon icon icon-clock-o"><b>&nbsp;day(s)</b></span>


            </div>
            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>


    {elseif $input.type == 'text_autopost'}
        <div class="col-lg-{if isset($input.col)}{$input.col|intval}{else}9{/if}{if !isset($input.label)} col-lg-offset-3{/if}">


        {if isset($input.lang) AND $input.lang}

            {if $languages|count > 1}
                <div class="form-group">
            {/if}
                {foreach $languages as $language}
                    {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                    {if $languages|count > 1}
                        <div class="translatable-field lang-{$language.id_lang|escape:'htmlall':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                        <div class="col-lg-9">
                    {/if}

                    <span style="float:left;margin:7px 5px 0 0;font-weight: bold">{$input.text_before|escape:'htmlall':'UTF-8'}</span>
                    <input type="text" style="float:left;margin-right:5px;width:40%"
                           id="{if isset($input.id)}{$input.id|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}{else}{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}{/if}"
                           name="{$input.name|escape:'htmlall':'UTF-8'}_{$language.id_lang|escape:'htmlall':'UTF-8'}"
                           class="{if isset($input.class)}{$input.class|escape:'htmlall':'UTF-8'}{/if}{if $input.type == 'tags'} tagify{/if}"
                           value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'htmlall':'UTF-8'}{else}{$value_text|escape:'htmlall':'UTF-8'}{/if}"
                           onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                            {if isset($input.size)} size="{$input.size|escape:'htmlall':'UTF-8'}"{/if}
                            {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                            {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                            {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                            {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                            {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                            {if isset($input.required) && $input.required} required="required" {/if}
                            {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder|escape:'htmlall':'UTF-8'}"{/if} />
                    <span style="float:left;margin:7px 5px 0 0;font-weight: bold">{$input.text_after|escape:'htmlall':'UTF-8'}</span>
                    {if $languages|count > 1}
                        </div>
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                {$language.iso_code|escape:'htmlall':'UTF-8'}
                                <i class="icon-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$languages item=language}
                                    <li><a href="javascript:hideOtherLanguage({$language.id_lang|escape:'htmlall':'UTF-8'});" tabindex="-1">{$language.name|escape:'htmlall':'UTF-8'}</a></li>
                                {/foreach}
                            </ul>
                        </div>
                        </div>
                    {/if}
                {/foreach}




            {if $languages|count > 1}
                </div>
            {/if}

        {/if}
         </div>
    {elseif $input.type == 'cms_shop_association'}

    <div class="col-lg-9">
        <div class="panel col-lg-7">

            <table width="50%" cellspacing="0" cellpadding="0" class="table">
                <tr>
                    <th>{l s='Shop' mod='gsnipreview'}</th>
                </tr>
                {assign var=i value=0}
                {foreach $input.values as $_shop}
                    <tr>
                        <td>

                            <img src="../img/admin/lv2_{if count($input.values)-1 == $i}f{else}b{/if}.png" alt="{$_shop['name']|escape:'htmlall':'UTF-8'}" style="vertical-align:middle;">
                            <label class="child">
                                <input type="checkbox" class="input_shop" {if $_shop['id_shop']|in_array:$input.selected_data}checked="checked"{/if} value="{$_shop['id_shop']|escape:'htmlall':'UTF-8'}" name="cat_shop_association[]">
                                {$_shop['name']|escape:'htmlall':'UTF-8'}
                            </label>
                        </td>
                    </tr>
                    {assign var=i value=$i++}
                {/foreach}
            </table>
        </div>
        {if isset($input.desc) && !empty($input.desc)}
            <p class="help-block">
                {$input.desc|escape:'htmlall':'UTF-8'}
            </p>
        {/if}
    </div>
    {elseif $input.type == 'checkbox_custom_blocks'}
        <div class="col-lg-9 {$input.name|escape:'htmlall':'UTF-8'}">

            {foreach $input.values.query as $value}
                {assign var=id_checkbox value=$value[$input.values.id]}
                <div class="checkbox{if isset($input.expand) && strtolower($input.expand.default) == 'show'} hidden{/if}">

                    {strip}
                        <label for="{$id_checkbox|escape:'htmlall':'UTF-8'}">
                            <input type="checkbox" name="{$id_checkbox|escape:'htmlall':'UTF-8'}" id="{$id_checkbox|escape:'htmlall':'UTF-8'}"  class="{if isset($input.class)}{$input.class|escape:'htmlall':'UTF-8'}{/if}"{if isset($value.val)} value="{$value.val|escape:'htmlall':'UTF-8'}"{/if}{if isset($fields_value[$id_checkbox]) && $fields_value[$id_checkbox]} checked="checked"{/if} />
                            {$value[$input.values.name]|escape:'htmlall':'UTF-8'}
                        </label>
                    {/strip}
                </div>
            {/foreach}

            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'checkbox_custom_blocks_store'}
        <div class="col-lg-9 {$input.name|escape:'htmlall':'UTF-8'}">

            {foreach $input.values.query as $value}
                {assign var=id_checkbox value=$value[$input.values.id]}



                <div class="checkbox{if isset($input.expand) && strtolower($input.expand.default) == 'show'} hidden{/if}">

                    {strip}
                        <label for="{$id_checkbox|escape:'htmlall':'UTF-8'}">
                            <input type="checkbox" name="{$id_checkbox|escape:'htmlall':'UTF-8'}" id="{$id_checkbox|escape:'htmlall':'UTF-8'}"
                                   class="{if isset($input.class)}{$input.class|escape:'htmlall':'UTF-8'}{/if}" {if isset($value.val)}
                                value="{$value.val|escape:'htmlall':'UTF-8'}"{/if}{if isset($fields_value[$id_checkbox]) && $fields_value[$id_checkbox]} checked="checked"{/if} />
                            {$value[$input.values.name]|escape:'htmlall':'UTF-8'}
                        </label>
                    {/strip}

                    -

                    {strip}
                        <label for="s{$id_checkbox|escape:'htmlall':'UTF-8'}">
                            <input type="checkbox" name="s{$id_checkbox|escape:'htmlall':'UTF-8'}" id="s{$id_checkbox|escape:'htmlall':'UTF-8'}"
                                   class="{if isset($input.class)}{$input.class|escape:'htmlall':'UTF-8'}{/if}"
                                   value="1"
                                    {if isset($value.site) && $value.site} checked="checked"{/if}
                                    />
                            {l s='display on the site view' mod='gsnipreview'}
                        </label>
                    {/strip}

                    -

                    {strip}
                        <label for="m{$id_checkbox|escape:'htmlall':'UTF-8'}">
                            <input type="checkbox" name="m{$id_checkbox|escape:'htmlall':'UTF-8'}" id="m{$id_checkbox|escape:'htmlall':'UTF-8'}"
                                   class="{if isset($input.class)}{$input.class|escape:'htmlall':'UTF-8'}{/if}"
                                   value="1"
                                    {if isset($value.mobile) && $value.mobile} checked="checked"{/if}
                                    />
                            {l s='display on the mobile view' mod='gsnipreview'}
                        </label>
                    {/strip}
                </div>
            {/foreach}

            {if isset($input.desc) && !empty($input.desc)}
                <p class="help-block">
                    {$input.desc|escape:'htmlall':'UTF-8'}
                </p>
            {/if}
        </div>

    {elseif $input.type == 'checkbox_custom_store'}
        <div class="col-lg-9 {$input.name|escape:'htmlall':'UTF-8'}" style="padding: 7px">

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






{block name="legend"}
    <h3>
        {if isset($field.image)}<img src="{$field.image|escape:'htmlall':'UTF-8'}" alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
        {if isset($field.icon)}<i class="{$field.icon|escape:'htmlall':'UTF-8'}"></i>{/if}
        {$field.title|escape:'htmlall':'UTF-8'}
        <span class="panel-heading-action">
			{foreach from=$toolbar_btn item=btn key=k}
                {if $k != 'modules-list' && $k != 'back'}
                    <a id="desc-{$table|escape:'htmlall':'UTF-8'}-{if isset($btn.imgclass)}{$btn.imgclass|escape:'htmlall':'UTF-8'}{else}{$k|escape:'htmlall':'UTF-8'}{/if}" class="list-toolbar-btn" {if isset($btn.href)}href="{$btn.href|escape:'quotes':'UTF-8'}"{/if} {if isset($btn.target) && $btn.target}target="_blank"{/if}{if isset($btn.js) && $btn.js}onclick="{$btn.js|escape:'htmlall':'UTF-8'}"{/if}>
						<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s=$btn.desc mod='gsnipreview'}" data-html="true">
							<i class="process-icon-{if isset($btn.imgclass)}{$btn.imgclass|escape:'htmlall':'UTF-8'}{else}{$k|escape:'htmlall':'UTF-8'}{/if} {if isset($btn.class)}{$btn.class|escape:'htmlall':'UTF-8'}{/if}" ></i>
						</span>
                    </a>
                {/if}
            {/foreach}
			</span>
    </h3>
{/block}
{block name="input_row"}

    {if $input.type == 'cms_blocks_custom'}


        <script type="text/javascript">
            var come_from = '{$name_controller|escape:'htmlall':'UTF-8'}';
            var token = '{$token|escape:'htmlall':'UTF-8'}';
            var alternate = 1;
        </script>
        {assign var=cms_blocks_positions value=$input.values}
        {if isset($cms_blocks_positions) && count($cms_blocks_positions) > 0}
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <table class="table tableDnD cms" id="cms_block_{$key|escape:'htmlall':'UTF-8'}">
                            <thead>
                            <tr class="nodrag nodrop">
                                <th>{l s='ID' mod='gsnipreview'}</th>
                                <th>{l s='Name' mod='gsnipreview'}</th>
                                <th>{l s='Shop' mod='gsnipreview'}</th>
                                <th>{l s='Language' mod='gsnipreview'}</th>
                                <th>{l s='Status' mod='gsnipreview'}</th>
                                <th style="width: 10%">{l s='Action' mod='gsnipreview'}</th>

                            </tr>
                            </thead>
                            <tbody>
                            {foreach $cms_blocks_positions as $key => $criteria}

                                {*{$cms_blocks_position|@var_dump}*}


                                <tr class="{if $key%2}alt_row{else}not_alt_row{/if} row_hover">
                                    <td>{$criteria['id_gsnipreview_review_criterion']|escape:'htmlall':'UTF-8'}</td>
                                    <td>{$criteria['name']|escape:'htmlall':'UTF-8'}</td>
                                    <td>{$criteria['id_shop']|escape:'htmlall':'UTF-8'}</td>
                                    <td>{$criteria['ids_lng']|escape:'htmlall':'UTF-8'}</td>

                                    <td><img alt="{if $criteria['active'] == 1}Enabled{else}Disabled{/if}" src="../img/admin/{if $criteria['active'] == 1}enabled{else}disabled{/if}.gif"></td>
                                    <td>
                                        <div class="btn-group-action">
                                            <div class="btn-group pull-left">
                                                <a class="btn btn-default" href="{$current|escape:'quotes':'UTF-8'}&amp;token={$token|escape:'quotes':'UTF-8'}&amp;editgsnipreview&amp;id={(int)$criteria['id_gsnipreview_review_criterion']|escape:'quotes':'UTF-8'}" title="{l s='Edit' mod='gsnipreview'}">
                                                    <i class="icon-edit"></i> {l s='Edit' mod='gsnipreview'}
                                                </a>
                                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                    <i class="icon-caret-down"></i>&nbsp;
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{$current|escape:'quotes':'UTF-8'}&amp;token={$token|escape:'quotes':'UTF-8'}&amp;delete_itemgsnipreview&amp;id={(int)$criteria['id_gsnipreview_review_criterion']|escape:'quotes':'UTF-8'}" title="{l s='Delete' mod='gsnipreview'}"
                                                           onclick = "javascript:return confirm('{l s='You delete criteria and ALL RATINGS RELATED WITH CRITERIA!' mod='gsnipreview'}');">
                                                            <i class="icon-trash"></i> {l s='Delete' mod='gsnipreview'}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>



                            {/foreach}
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        {else}
            <table class="table">
                <tr>
                    <td colspan="11" class="list-empty">
                        <div class="list-empty-msg">
                            <i class="icon-warning-sign list-empty-icon"></i>
                            {l s='No records found' mod='gsnipreview'}
                        </div>
                    </td>
                </tr>
            </table>
        {/if}



    {else}
        {$smarty.block.parent}
    {/if}
{/block}
