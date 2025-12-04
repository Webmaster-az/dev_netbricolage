{*
* Easy login as Customer
*
* NOTICE OF LICENSE
*
*    @author    Remy Combe <remy.combe@gmail.com>
*    @copyright 2013-2016 Remy Combe
*    @license   go to addons.prestastore.com (buy one module for one shop).
*    @for    PrestaShop version 1.7
*}
<!--
<div class="row">
	<div class="col-lg-7">
		<div class="panel clearfix">
			<div class="panel-heading">
				<i class="icon-signin"></i> {l s='Login as Customer' mod='easyloginascustomer'}
				<div class="panel-heading-action" style="padding:7px;">
					<a data-toggle="dropdown" class="dropdown-toggle notifs dropdown_loginascustomer_link" href="{$loginascustomer_config_url|escape:'htmlall':'UTF-8'}">
						<i class="icon-cogs" onclick="javascript:document.location.href = '{$loginascustomer_config_url|escape:'htmlall':'UTF-8'}'"></i>
					</a>
				</div>
			</div>-->
			<a id="pc_movebtnloginascustomer" href="{$loginascustomer_url|escape:'html'}" {$loginascustomer_new_tab|escape:'htmlall':'UTF-8'}>
				<i class="icon-user"></i> {l s='Login as' mod='easyloginascustomer'} {$loginascustomer_name|escape:'htmlall':'UTF-8'}
			</a>
			<!--
		</div>
	</div>
</div>
-->