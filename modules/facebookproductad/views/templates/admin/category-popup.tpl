{*
*
* Dynamic Ads + Pixel
*
* @author    BusinessTech.fr - https://www.businesstech.fr
* @copyright Business Tech - https://www.businesstech.fr
* @license   Commercial
*
*           ____    _______
*          |  _ \  |__   __|
*          | |_) |    | |
*          |  _ <     | |
*          | |_) |    | |
*          |____/     |_|
*
*}
{if !empty($aErrors)}
{include file="`$sErrorInclude`"}
{* USE CASE - edition review mode *}
{else}
<div id="{$sModuleName|escape:'htmlall':'UTF-8'}" class="bootstrap">
	<div id="bt_facebook-category" class="col-xs-12">
		<h3 class="subtitle">{l s='Facebook product categories for' mod='facebookproductad'}: {$sLangIso|escape:'htmlall':'UTF-8'}</h3>

		<div class="clr_hr"></div>
		<div class="clr_20"></div>

		<div class="alert alert-success">
			{l s='INSTRUCTIONS: for each category, you can do keyword search that represents the category, using as many words as you wish. Simply separate each word by a space. The field will autocomplete with possible matches that contain all the words you entered. Then simply select the best match from the list.' mod='facebookproductad'}
		</div>

		{if $iMaxPostVars != false && $iShopCatCount > $iMaxPostVars}
		<div class="alert alert-warning">
			{l s='Important note: Be careful, apparently your maximum post variables is limited by your server and your number of categories is higher than your max post variables' mod='facebookproductad'} :<br/>
			<strong>{$iShopCatCount|escape:'htmlall':'UTF-8'}</strong>&nbsp;{l s='categories' mod='facebookproductad'}</strong>&nbsp;{l s='on' mod='facebookproductad'}&nbsp;<strong>{$iMaxPostVars|escape:'htmlall':'UTF-8'}</strong>&nbsp;{l s='max post variables possible (PHP directive => max_input_vars)' mod='facebookproductad'}<br/><br/>
			<strong>{l s='It is possible that you cannot register properly all your categories, please visit our FAQ on this topic' mod='facebookproductad'}</strong>: <a target="_blank" href="{$faqLink|escape:'htmlall':'UTF-8'}/59">{$faqLink|escape:'htmlall':'UTF-8'}</a>
		</div>
		{/if}

		<div class="clr_20"></div>

		<form class="form-horizontal" method="post" id="bt_form-facebook-cat" name="bt_form-facebook-cat" {if $smarty.const._GSR_USE_JS == true}onsubmit="oFpa.form('bt_form-facebook-cat', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_facebook-category', 'bt_facebook-category', false, true, null, 'GoogleCat', 'loadingGoogleCatDiv');return false;"{/if}>
			<input type="hidden" name="{$sCtrlParamName|escape:'htmlall':'UTF-8'}" value="{$sController|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sAction" value="{$aQueryParams.facebookCatUpdate.action|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sType" value="{$aQueryParams.facebookCatUpdate.type|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="sLangIso" value="{$sLangIso|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="iLangId" value="{$iLangId|escape:'htmlall':'UTF-8'}" />

			{foreach from=$aShopCategories name=category item=aCategory}
			<table class="table table-bordered table-responsive">
				<tr>
					<td class="label_tag_categories">{l s='Shop category' mod='facebookproductad'} : <strong>{$aCategory.path}</strong></td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-xs-2">
								{l s='Facebook category' mod='facebookproductad'}&nbsp;:
							</div>
							<div class="col-xs-10">
								<input class="autocmp" style="font-size: 11px; width: 800px;" type="text" name="bt_facebook-cat[{$aCategory.id_category|escape:'htmlall'}]" id="bt_facebook-cat{$aCategory.id_category|escape:'htmlall':'UTF-8'}" value="{$aCategory.google_category_name}" />
							</div>
						</div>
						<p class="duplicate_category">
						{if $smarty.foreach.category.first}
							<div class="clr_10"></div>
							<a class="btn btn-sm pull-left btn-success" href="#" onclick="return oFpa.duplicateFirstValue('input.autocmp', $('#bt_facebook-cat{$aCategory.id_category|escape:'htmlall':'UTF-8'}').val());"><i class="fa fa-copy"></i>&nbsp; {l s='Click here to duplicate this value on all the following categories' mod='facebookproductad'}</a>
						{/if}
						</p>
					</td>
				</tr>
			</table>
			{/foreach}

			<div class="clr_20"></div>

			<p style="text-align: center !important;">
				{if $useJs == true}
					<script type="text/javascript">
						{literal}
						var oGoogleCatCallback = [{}];
						{/literal}
					</script>
					<input type="button" name="{$sModuleName|escape:'htmlall':'UTF-8'}CommentButton" class="btn btn-success btn-lg" value="{l s='Modify' mod='facebookproductad'}" onclick="oFpa.form('bt_form-facebook-cat', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_facebook-category', 'bt_facebook-category', false, true, null, 'GoogleCat', 'loadingGoogleCatDiv');return false;" />
				{else}
					<input type="submit" name="{$sModuleName|escape:'htmlall':'UTF-8'}CommentButton" class="btn btn-success btn-lg" value="{l s='Modify' mod='facebookproductad'}" />
				{/if}
					<button class="btn btn-danger btn-lg" value="{l s='Cancel' mod='facebookproductad'}"  onclick="$.fancybox.close();return false;">{l s='Cancel' mod='facebookproductad'}</button>
			</p>
		</form>
		{literal}
		<script type="text/javascript">
			$('input.autocmp').each(function(index, element) {
				var query = $(element).attr("id");
				$(element).autocomplete('{/literal}{$sURI}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.autocomplete.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.autocomplete.type|escape:'htmlall':'UTF-8'}&sLangIso={$sLangIso|escape:'htmlall':'UTF-8'}&query='+query{literal}, {
					minChars: 3,
					autoFill: false,
					max:50,
					matchContains: true,
					mustMatch:false,
					scroll:true,
					cacheLength:0,
					formatItem: function(item) {
						return item[0];
					}
				});
			});
		</script>
		{/literal}
	</div>
</div>
<div id="loadingGoogleCatDiv" style="display: none;">
	<div class="alert alert-info">
		<p style="text-align: center !important;"><img src="{$sLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
		<p style="text-align: center !important;">{l s='The update of your configuration is in progress...' mod='facebookproductad'}</p>
	</div>
</div>
{/if}