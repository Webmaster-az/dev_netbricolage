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

{literal}
<script type="text/javascript">
//<![CDATA[
	var baseDir = '{/literal}{$base_dir_ssl|escape:'htmlall':'UTF-8'}{literal}';
//]]>
</script>
{/literal}

{if $gsnipreviewis14 == 1}
<link href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/css/font-custom.min.css" rel="stylesheet" type="text/css" media="all" />
{/if}

{if $gsnipreviewis16 == 1 && $gsnipreviewis17 ==0}
{capture name=path}<a href="{$gsnipreviewmy_a_link|escape:'htmlall':'UTF-8'}">{l s='My account' mod='gsnipreview'}</a>
<span class="navigation-pipe">{$navigationPipe}</span>{l s='Product Reviews' mod='gsnipreview'}{/capture}
{/if}


{if $gsnipreviewis17 ==1}
    <a href="{$gsnipreviewmy_a_link|escape:'htmlall':'UTF-8'}">{l s='My account' mod='gsnipreview'}</a>
        <span class="navigation-pipe"> > </span>{l s='Product Reviews' mod='gsnipreview'}
{/if}

{if $gsnipreviewis16 == 1}
    <h3 class="page-product-heading">{l s='Product Reviews' mod='gsnipreview'}</h3>

{else}

    <h2>{l s='Reviews' mod='gsnipreview'}</h2>

{/if}

{if $gsnipreviewis_reminder}
<div class="checkbox-my-acc" id="reminder_status_block">

    <span class="gsnipreviews-float-left">
        <input type="checkbox" {if $gsnipreviewrem_status == 1}checked="checked"{/if} name="reminder_status" id="reminder_status" onclick="change_reminder_status()">
    </span>
    <span class="gsnipreviews-float-left">
    <i class="fa fa-bell-o fa-lg"></i>
     </span>
    <span class="gsnipreviews-float-left">
    {l s='Send me a reminder e-mail to rate purchased products after each my order' mod='gsnipreview'}
    </span>
    <div class="gsnipreviews-clear"></div>

</div>
<br/>
{/if}


{if count($gsnipreviewmy_reviews)>0}
<div class="block-center {if $gsnipreviewis17 == 1}block-categories{/if}" id="block-history">
	<div id="gsniprev-list">
	<table id="block-history" class="std1 table table-bordered my-customer-reviews-ps14 {if $gsnipreviewis16 == 1}responsive-table-custom{/if}">
        <thead>
			<tr>
				<th class="first_item">
						{l s='Product' mod='gsnipreview'}
				</th>
				{if $gsnipreviewratings_on == 1}
				<th class="item" style="width:200px">
						{l s='Total Rating' mod='gsnipreview'}
				</th>
				{/if}
				{if $gsnipreviewtitle_on == 1}
				<th class="item">
						{l s='Title' mod='gsnipreview'}
				</th>
				{/if}
				<th class="item">
						{l s='Purchased' mod='gsnipreview'}?
				</th>
				<th class="item">
						{l s='Date Add' mod='gsnipreview'}
				</th>
                <th class="item">
                    {l s='Suggestion to modify review' mod='gsnipreview'}?
                </th>
				<th class="last_item">
						{l s='Status' mod='gsnipreview'}
				</th>
			</tr>
        </thead>
        <tbody>
			{foreach from=$gsnipreviewmy_reviews item=review}
			<tr>
				<td class="center">
				<a href="{$review.product_link|escape:'htmlall':'UTF-8'}" 
	    		   title="{$review.product_name|escape:'htmlall':'UTF-8'}"
	    		   style="text-decoration:underline" target="_blank"
	    		   >
	    			{$review.product_name|escape:'htmlall':'UTF-8'}
	    		</a>	
				</td>
				{if $gsnipreviewratings_on == 1}
				<td class="center">
				{if $review.rating != 0}
					{section name=ratid loop=5}
						{if $smarty.section.ratid.index < $review.rating} 
							<img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
						{else}
							<img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewnoactivestar|escape:'htmlall':'UTF-8'}" alt="{$smarty.section.ratid.index|escape:'htmlall':'UTF-8'}"/>
						{/if}
					{/section}
				{else}
					&nbsp;
				{/if}
				</td>
				{/if}
				
				{if $gsnipreviewtitle_on == 1}
				<td class="center">
				{if strlen($review.title_review)>0}
                    <a href="{$gsnipreviewrev_url|escape:'htmlall':'UTF-8'}?rid={$review.id|escape:'htmlall':'UTF-8'}"
                       title="{$review.title_review|escape:'htmlall':'UTF-8'}"
                       style="text-decoration:underline" target="_blank"
                            >
					{$review.title_review|escape:'htmlall':'UTF-8' nofilter}
                    </a>
				{else}
					&nbsp;
				{/if}
				</td>
				{/if}
				
				<td class="center">
                    {if $review.is_bought == 1}
                        <img alt="{l s='Enabled' mod='gsnipreview'}" title="{l s='Enabled' mod='gsnipreview'}"
                             src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}img/admin/enabled.gif">
                    {else}
                        <img alt="{l s='Disabled' mod='gsnipreview'}" title="{l s='Disabled' mod='gsnipreview'}"
                             src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}img/admin/disabled.gif">
                    {/if}
				</td>

				<td class="center">
				{dateFormat date=$review.time_add|escape:'htmlall':'UTF-8' full=0}
				</td>

                <td class="center" id="changed_review{$review.id|escape:'htmlall':'UTF-8'}" {if $gsnipreviewis16 == 0}style="padding: 0px;text-align:center"{/if}>
                    {if $review.is_changed == 1}
                        <a class="{if $gsnipreviewis16 == 1}btn btn-success{else}button{/if}" title="{l s='Modify' mod='gsnipreview'}"
                           onclick="modify_review({$review.id|escape:'htmlall':'UTF-8'},'modify_my',{$review.id_customer|escape:'htmlall':'UTF-8'})"
                           href="javascript:void(0)">
                            <i class="fa fa-pencil-square-o"></i>

                            {l s='Modify' mod='gsnipreview'}
                        </a>
                    {elseif $review.is_changed == 2}
                        <img alt="{l s='Review already changed' mod='gsnipreview'}" title="{l s='Review already changed' mod='gsnipreview'}"
                             src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}img/admin/enabled.gif"/>
                    {else}
                        {l s='No reply' mod='gsnipreview'}
                    {/if}
                </td>


				<td class="center" >
				{if $review.is_active == 1}
				    <img alt="{l s='Enabled' mod='gsnipreview'}" title="{l s='Enabled' mod='gsnipreview'}"
				    src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}img/admin/enabled.gif"/>
				{else}
				{*<img alt="{l s='Disabled' mod='gsnipreview'}" title="{l s='Disabled' mod='gsnipreview'}"
				src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/wait.gif"/>
                    *}
                    <i class="fa fa-clock-o fa-2x" style="color:#a94442"></i>

                {/if}
				</td>
			</tr>
			{/foreach}
		</tbody>
	</table>
	
	<div id="gsniprev-nav-pre">
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
	
	</div>


</div>

    {literal}

        <script type="text/javascript">



        function modify_review(id_review,action,id_customer){

                $.post(baseDir+'modules/gsnipreview/reviews.php',
                        { id_review:id_review,
                            action:action,
                            id_customer: id_customer
                        },
                        function (data) {
                            if (data.status == 'success') {


                                var data = data.params.content;


                                    if ($('div#fb-con-wrapper').length == 0) {
                                        conwrapper = '<div id="fb-con-wrapper"><\/div>';
                                        $('body').append(conwrapper);
                                    } else {
                                        $('#fb-con-wrapper').html('');
                                    }

                                    if ($('div#fb-con').length == 0) {
                                        condom = '<div id="fb-con"><\/div>';
                                        $('body').append(condom);
                                    }

                                    $('div#fb-con').fadeIn(function () {

                                        $(this).css('filter', 'alpha(opacity=70)');
                                        $(this).bind('click dblclick', function () {
                                            $('div#fb-con-wrapper').hide();
                                            $(this).fadeOut();
                                            //window.location.reload();
                                        });
                                    });


                                    $('div#fb-con-wrapper').html('<a id="button-close" style="display: inline;"><\/a>' + data).fadeIn();

                                    $("a#button-close").click(function () {
                                        $('div#fb-con-wrapper').hide();
                                        $('div#fb-con').fadeOut();
                                    });

                                    $("button#cancel-report").click(function () {
                                        $('div#fb-con-wrapper').hide();
                                        $('div#fb-con').fadeOut();
                                    });

                                  





                            } else {
                                alert(data.message);

                            }
                        }, 'json');
            }

        </script>
        {/literal}


{else}
<div style="border:1px solid #C4C4C4;background:#FAFAFA;padding:10px">
	{l s='There are not Product Reviews yet.' mod='gsnipreview'}
</div>
{/if}

{if $gsnipreviewis16 == 1}
    <br/>
    <ul class="footer_links clearfix">

        <li class="float-left margin-right-10">
            <a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}" class="btn btn-default button button-small-gsnipreview">
                <span><i class="icon-chevron-left"></i> {l s='Home' mod='gsnipreview'}</span>
            </a>
        </li>

        <li class="float-left">
            <a href="{$gsnipreviewmy_a_link|escape:'htmlall':'UTF-8'}" class="btn btn-default button button-small-gsnipreview">
			<span>
				<i class="icon-chevron-left"></i> {l s='Back to Your Account' mod='gsnipreview'}
			</span>
            </a>
        </li>

    </ul>
{else}
<ul class="footer_links">
	<li><a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}my-account.php"><img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/users/my-account.gif" alt="" class="icon" /></a>
		<a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}my-account.php">{l s='Back to Your Account' mod='gsnipreview'}</a></li>
	<li><a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}"><img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/users/home.gif" alt="" class="icon" /></a>
		<a href="{$base_dir_ssl|escape:'htmlall':'UTF-8'}">{l s='Home' mod='gsnipreview'}</a></li>
</ul>

{/if}


{literal}
<style type="text/css">
table.std th, table.table_block th{padding:5px!important}
</style>
{/literal}

{literal}

<script type="text/javascript">

    {/literal}{if $gsnipreviewis_reminder}{literal}
    function change_reminder_status(){
        if($("input#reminder_status").is(":checked")) {
            var reminder_status = 1;
        } else {
            var reminder_status = 0;
        }
        $.post(baseDir+'modules/gsnipreview/reviews.php',
                {action:'change-reminder',
                    reminder_status: reminder_status
                },
                function (data) {

                    if (data.status == 'success') {



                        $("#confirm-reminder").remove();

                        if(reminder_status == 1){
                            var status_text = '{/literal}{$gsnipreviewmyr_msg1|escape:'htmlall':'UTF-8'}{literal}';
                        } else {
                            var status_text = '{/literal}{$gsnipreviewmyr_msg2|escape:'htmlall':'UTF-8'}{literal}';
                        }

                        var html = '<div id="confirm-reminder" class="alert alert-success"><button data-dismiss="alert" class="close" type="button">×</button>'+
                                '{/literal}{$gsnipreviewmyr_msg3|escape:'htmlall':'UTF-8'}{literal}'+
                                '&nbsp;({/literal}{$gsnipreviewmyr_msg4|escape:'htmlall':'UTF-8'}{literal}'+
                                '&nbsp;<strong>'+status_text+'</strong>)</div>';

                        $("#reminder_status_block").after(html);


                    } else {

                        alert(data.message);

                    }
                }, 'json');
    }
    {/literal}{/if}{literal}



</script>

<style type="text/css">


    @media
    only screen and (max-width: 760px),
    (min-device-width: 768px) and (max-device-width: 1024px)  {

        /* Force table to not be like tables anymore */
        table.responsive-table-custom, table.responsive-table-custom thead,
        table.responsive-table-custom tbody,
        table.responsive-table-custom th,
        table.responsive-table-custom td,
        table.responsive-table-custom tr {
            display: block!important;
        }

        /* Hide table headers (but not display: none;, for accessibility) */
        table.responsive-table-custom thead tr {
            position: absolute!important;;
            top: -9999px!important;;
            left: -9999px!important;;
        }

        table.responsive-table-custom tr { border: 1px solid #ccc; }

        table.responsive-table-custom td {
            /* Behave  like a "row" */
            border: none!important;;
            border-bottom: 1px solid #eee!important;;
            position: relative!important;;
            padding-left: 50%!important;;

        }

        table.responsive-table-custom td:before {
            /* Now like a table header */
            position: absolute!important;;
            /* Top/left values mimic padding */
            top: 6px!important;;
            left: 6px!important;;
            width: 45%!important;;
            padding-right: 10px!important;;
            white-space: nowrap!important;;
        }

        /*
        Label the data
        */
        table.responsive-table-custom td:nth-of-type(1):before { content: "{/literal}{l s='Product' mod='gsnipreview'}{literal}"; }
        table.responsive-table-custom td:nth-of-type(2):before { content: "{/literal}{l s='Total Rating' mod='gsnipreview'}{literal}"; }
        table.responsive-table-custom td:nth-of-type(3):before { content: "{/literal}{l s='Title' mod='gsnipreview'}{literal}"; }
        table.responsive-table-custom td:nth-of-type(4):before { content: "{/literal}{l s='Purchased' mod='gsnipreview'}{literal}?"; }
        table.responsive-table-custom td:nth-of-type(5):before { content: "{/literal}{l s='Date Add' mod='gsnipreview'}{literal}"; }
        table.responsive-table-custom td:nth-of-type(6):before { content: "{/literal}{l s='Suggestion to modify review' mod='gsnipreview'}{literal}?"; }
        table.responsive-table-custom td:nth-of-type(7):before { content: "{/literal}{l s='Status' mod='gsnipreview'}{literal}"; }

    }



</style>

{/literal}


