<style>
        .form-style
        {
                font-size: 15px !important;
                color: black !important;
                font-weight: bold !important;
                text-shadow: none !important;
                padding-left: 0px !important;
                float:none !important;
                margin-top: 10px;
                
        }
        .ul-form-style
        {
                float:left !important;
        }
        #head
        {
                float:left !important;
        }
</style>

<script>
	var velsof_loginizer = {$velsof_loginizer|escape:'htmlall':'UTF-8'};
	var show_on_supercheckout='{$show_on_supercheckout|escape:'htmlall':'UTF-8'}';
	var code = '{$html_code}';      //Variable contains html content, escape not required
	var show_popup = '{$show_popup|escape:'htmlall':'UTF-8'}';
	var loginizer_small = '{$loginizer_small}';	//Variable contains html content, escape not required
	var loginizer_large = '{$loginizer_large}';	//Variable contains html content, escape not required
	var ps_version_com = {$ps_version_com|escape:'htmlall':'UTF-8'};
	
	if (velsof_loginizer == 1)
	{

		if (show_popup) {
			code = code.replace(/href/g, 'onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')" target="_blank" href');
			code = code.replace('type="fb" onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')"', 'type="fb" onclick="return !window.open(this.href, \'popup\',\'width=450,height=300,left=500,top=500\')"');
			loginizer_small = loginizer_small.replace(/href/g, 'onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')" target="_blank" href');
			loginizer_small = loginizer_small.replace('type="fb" onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')"', 'type="fb" onclick="return !window.open(this.href, \'popup\',\'width=450,height=300,left=500,top=500\')"');			
			loginizer_large = loginizer_large.replace(/href/g, 'onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')" target="_blank" href');
			loginizer_large = loginizer_large.replace('type="fb" onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')"', 'type="fb" onclick="return !window.open(this.href, \'popup\',\'width=450,height=300,left=500,top=500\')"');
		}
		else
		{
			code = code.replace(/href/g, 'target="_top" href');
			loginizer_small = loginizer_small.replace(/href/g, 'target="_top" href');
			loginizer_large = loginizer_large.replace(/href/g, 'target="_top" href');
		}
	}


	$(document).ready(function ()
	{
		if (velsof_loginizer == 1)
		{
			if (ps_version_com == 15)
			{
			code = code + '<style>#authentication #create-account_form fieldset, #authentication #login_form fieldset{ height : auto ! important}</style>';
			{if $position == 'create'}
				$('#create-account_form > fieldset > div').append(code);
			{else if $position == 'login'}
				$('#login_form > fieldset > div').append(code);
			{/if}
			}
			else
			{
			{if $position == 'create'}
				$('#create-account_form > div').append(code);
			{else if $position == 'login'}
				$('#login_form > div').append(code);
			{/if}
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
	{*    var loginizer = global_code;*}
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

