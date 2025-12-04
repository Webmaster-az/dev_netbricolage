{strip}
	{if isset($loginizer_small) AND !empty($loginizer_small)}
		<script>
			var loginizer_small='{$loginizer_small}';	//Variable contains html content, escape not required
		</script>
	
        {else}
            <script>
                var loginizer_small = '';
                </script>
        {/if}
	
	{if isset($loginizer_large) AND !empty($loginizer_large)}
	<script>
		var loginizer_large='{$loginizer_large}';	//Variable contains html content, escape not required
	</script>
        
        {else}
            <script>
                var loginizer_large = '';
                </script>
        {/if}
	
{/strip}

<script>
	var show_popup='{$show_popup|escape:'htmlall':'UTF-8'}';
	var show_on_supercheckout='{$show_on_supercheckout|escape:'htmlall':'UTF-8'}';

	if(show_popup)
	{
		loginizer_small=loginizer_small.replace(/href/g,'onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')" target="_blank" href');
		loginizer_small=loginizer_small.replace('type="fb" onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')"', 'type="fb" onclick="return !window.open(this.href, \'popup\',\'width=450,height=300,left=500,top=500\')"');
	}
	else
		loginizer_small = loginizer_small.replace(/href/g, 'target="_top" href');

	if(show_popup)
	{
		loginizer_large=loginizer_large.replace(/href/g,'onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')" target="_blank" href');
		loginizer_large=loginizer_large.replace('type="fb" onclick="return !window.open(this.href, \'popup\',\'width=500,height=500,left=500,top=500\')"', 'type="fb" onclick="return !window.open(this.href, \'popup\',\'width=450,height=300,left=500,top=500\')"');
	}
	else
		loginizer_large = loginizer_large.replace(/href/g, 'target="_top" href');
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
* Social Loginizer Right Column Page
*}