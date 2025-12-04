<style>
	a.hover-link
	{
		font-size: 15px !important;
	}
</style>

{if $callhook == 'header' || $callhook == 'authentication_form' }
	<style>
		#head
		{
			float: left !important;
		}
	</style>
{/if}

{if $callhook == 'footer'}
	<style>
        .sl_footer
        {
//            position: absolute;
            padding-left: 1.5%;
            //margin-top: -75px;
            display: inline-block;
            background-color: #3F3F3F;
            width: 100%;
            text-align: center;
        }
        .sl_footer a
        {
            color: white !important;
            text-decoration: none;
        }
        .sl_footer ul
        {
                display: inline-block;
        }
        #output li
        {
                float:none;
                display:inline;
        }
        #output li a img
        {
                margin-top: 10px;
        }
</style>

	{/if}
<script>
	var velsof_loginizer={$velsof_loginizer|escape:'htmlall':'UTF-8'};
	var show_on_supercheckout='{$show_on_supercheckout|escape:'htmlall':'UTF-8'}';
	var code='{$html_code}';	//Variable contains html content, escape not required
	var show_popup='{$show_popup|escape:'htmlall':'UTF-8'}';
	var loginizer_small='{$loginizer_small}';	//Variable contains html content, escape not required
	var loginizer_large='{$loginizer_large}';	//Variable contains html content, escape not required
	var ps_version_com={$ps_version_com|escape:'htmlall':'UTF-8'};
	var callhook='{$callhook|escape:'htmlall':'UTF-8'}';
	if (velsof_loginizer == 1)
	{
		var link_html = code;
		if (show_popup) {
			link_html = link_html.replace(/href/g, 'onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')" target="_blank" href');
			link_html = link_html.replace('type="fb" onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')"', 'type="fb" onclick="return !window.open(this.href, \'popup\',\'width=450,height=300,left=500,top=500\')"');
			loginizer_small = loginizer_small.replace(/href/g, 'onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')" target="_blank" href');
			loginizer_small = loginizer_small.replace('type="fb" onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')"', 'type="fb" onclick="return !window.open(this.href, \'popup\',\'width=450,height=300,left=500,top=500\')"');
			loginizer_large = loginizer_large.replace(/href/g, 'onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')" target="_blank" href');
			loginizer_large = loginizer_large.replace('type="fb" onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')"', 'type="fb" onclick="return !window.open(this.href, \'popup\',\'width=450,height=300,left=500,top=500\')"');
		}
		else
		{
			link_html = link_html.replace(/href/g, 'target="_top" href');
			loginizer_small = loginizer_small.replace(/href/g, 'target="_top" href');
			loginizer_large = loginizer_large.replace(/href/g, 'target="_top" href');
		}
	}
	$(document).ready(function ()
	{
		if (velsof_loginizer == 1)
		{
			if (callhook == 'header')
			{
				link_html = "<div style='clear:both'></div>" +link_html + "<style> a.hover-link{ color:black !important;float:none;} #output{ margin-bottom:0px !important;}</style><div style='clear:both'></div>" ;
				$('#header').append(link_html);
			}
			else if (callhook == 'authentication_form')
			{
				link_html = link_html + '<style>#authentication #create-account_form fieldset, #authentication #login_form fieldset{ height : auto ! important}a.hover-link{ color:black !important;float:none;}</style>';
				{if $position == 'create'}
					$('#create-account_form > div').append(link_html);
				{else if $position == 'login'}
					$('#content').append(link_html);
				{/if}
			}
			else if (callhook == 'footer')
			{
				console.log(callhook);
				link_html = "<style> .sl_footer{ width:98.6% !important;}</style>" + link_html;
				$('#content').append(link_html); 
			}
				
				
		}
	});
</script>

<script type="text/javascript">
	$(function () {
		$('#output > li').tsort({
			attr: 'data-index'
		});
	});
</script>
{*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer tohttp://www.prestashop.com for more information.
* We offer the best and most useful modules PrestaShop and modifications for your online store.
*
* @category  PrestaShop Module
* @author    knowband.com <support@knowband.com>
* @copyright 2015 Knowband
* @license   see file: LICENSE.txt
*
* Description
*
* Social Loginizer Header File
*}
