{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author Musaffar Patel <musaffar.patel@gmail.com>
*  @copyright  2015-2021 Musaffar Patel
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Property of Musaffar Patel
*}

<div id="equation-wrapper">

	<div class="" style="padding:0 15px;">
		<div class="form-group row" style="margin:20px 0;">
			<label class="control-label col-lg-2">
				{l s='Equations Enabled' mod='productpricebysize'}
			</label>
			<div class="col-lg-10">
				<input data-toggle="switch" class="" id="equation_enabled" name="equation_enabled" data-inverse="true" type="checkbox" value="1" {if $ppbs_product->equation_enabled eq "1"}checked{/if} />
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			{* start: combinations list *}
			<div class="list-group combinations-list">
				<a href="#" class="list-group-item" data-ipa="">{l s='All Combinations' mod='productpricebysize'}</a>
				{foreach from=$combinations item=combination}
					<a href="#" class="list-group-item" data-ipa="{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}">
						{if $combination.default_on eq 1}
							<i>{$combination.attributes|escape:'htmlall':'UTF-8'}</i>
						{else}
							{$combination.attributes|escape:'htmlall':'UTF-8'}
						{/if}
					</a>
				{/foreach}
			</div>
			{* end: combinations list *}
		</div>

		<div class="col-sm-6">
			<div id="form-equation">
				<div id="ppbs-price-calculator">placeholder</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		prestaShopUiKit.init();
	});
</script>
