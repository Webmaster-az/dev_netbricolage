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
		<div id="bt_advanced-tag" class="col-xs-12 bt_adwords">
			<h3 class="text-center">{l s='Tags assignation for each products category' mod='facebookproductad'}</h3>
			<div class="clr_hr"></div>
			<div class="clr_20"></div>
			<div class="alert alert-warning col-xs-12">
				<p>{l s='WARNING : before starting, please note that the categories displayed below are the DEFAULT categories of your products. So, make sure that your products are correctly assigned to the right default category.' mod='facebookproductad'}</p>
			</div>

				<br/>
				<br/>
				<div class="form-group">
					<div class="alert alert-info col-xs-12">
						<label class="col-xs-4">
							<b>{l s='Select which type of tags you want to set :' mod='facebookproductad'}</b>
						</label>
						<div class="col-xs-3">
							<select class="set_tag" name="set_tag" id="set_tag">
								<option value="0">---</option>
								{if !empty($bMaterial)}
									<option value="material">{l s='Set product material tags' mod='facebookproductad'}</option>
								{/if}
								{if !empty($bPattern)}
									<option value="pattern">{l s='Set product pattern tags' mod='facebookproductad'}</option>
								{/if}
								{if !empty($bGender)}
									<option value="gender">{l s='Set product gender tags' mod='facebookproductad'}</option>
								{/if}
								{if !empty($bAgeGroup)}
									<option value="agegroup">{l s='Set product age group tags' mod='facebookproductad'}</option>
								{/if}
								{if !empty($bTagAdult)}
									<option value="adult">{l s='Set product for adults only tags' mod='facebookproductad'}</option>
								{/if}
							</select>
						</div>
					</div>

					<div class="bulk-actions">
						<table class="table bg-info">
							<tr id="bulk_action_material">
								<td class="label_tag_categories_value feature_cat_tag col-xs-6">{l s='Set MATERIAL tags : for each product default category, if available, you will have to indicate the feature that defines the material of the products that are in this category.' mod='facebookproductad'}</td>
								<td class="col-xs-3">
									<select name="set_material_bulk_action" class="set_material_bulk_action">
                                        {foreach from=$aFeatures item=feature}
											<option value="{$feature.id_feature|escape:'htmlall':'UTF-8'}">{$feature.name|escape:'htmlall':'UTF-8'}</option>
                                        {/foreach}
									</select>
								</td>
								<td class="col-xs-2"><span class="btn btn-default" onclick="oFpa.doSet('.material', $('.set_material_bulk_action').val());">{l s='Set for all categories' mod='facebookproductad'}</span> - <span class="btn btn-default" onclick="oFpa.doSet('.material', 0);">{l s='Reset' mod='facebookproductad'}</td>
							</tr>
							<tr id="bulk_action_pattern">
								<td class="label_tag_categories_value col-xs-6">{l s='Set PATTERN tags : for each product default category, if available, you will have to indicate the feature that defines the pattern of the products that are in this category.' mod='facebookproductad'}</td>
								<td class="col-xs-3">
									<select name="set_pattern_bulk_action" class="set_pattern_bulk_action">
                                        {foreach from=$aFeatures item=feature}
											<option value="{$feature.id_feature|escape:'htmlall':'UTF-8'}">{$feature.name|escape:'html'}</option>
                                        {/foreach}
									</select>
								</td>
								<td class="col-xs-2"><span class="btn btn-default" onclick="oFpa.doSet('.pattern', $('.set_pattern_bulk_action').val());">{l s='Set for all categories' mod='facebookproductad'}</span> - <span class="btn btn-default" onclick="oFpa.doSet('.pattern', 0);">{l s='Reset' mod='facebookproductad'}</span></td>
							</tr>
							<tr id="bulk_action_adult">
								<td class="label_tag_categories_value col-xs-6">{l s='Set AGE GROUP tags : for each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"age group\" value defines the age group for which the products of this category are reserved. To assign the same tag to all categories, click on one of the opposite buttons  -------->' mod='facebookproductad'}</td>
								<td class="col-xs-6">
									<span class="btn btn-default" onclick="oFpa.doSet('.agegroup', 'adult');">{l s='Adults' mod='facebookproductad'} </span>
									- <span class="btn btn-default btn-special" onclick="oFpa.doSet('.agegroup', 'all ages');">{l s='All ages' mod='facebookproductad'}</span>
									- <span class="btn btn-default btn-special" onclick="oFpa.doSet('.agegroup', 'teen');">{l s='Teens' mod='facebookproductad'}</span>
									- <span class="btn btn-default" onclick="oFpa.doSet('.agegroup', 'kids');">{l s='Kids' mod='facebookproductad'}</span>
									- <span class="btn btn-default" onclick="oFpa.doSet('.agegroup', 'toddler');">{l s='Toddlers' mod='facebookproductad'}</span>
									- <span class="btn btn-default" onclick="oFpa.doSet('.agegroup', 'infant');">{l s='Infants' mod='facebookproductad'}</span>
									- <span class="btn btn-default btn-special" onclick="oFpa.doSet('.agegroup', 'newborn');">{l s='Newborns' mod='facebookproductad'}</span>
									- <span class="btn btn-default" onclick="oFpa.doSet('.agegroup', 0);">{l s='Reset' mod='facebookproductad'}</span>
								</td>
							</tr>
							<tr id="bulk_action_gender">
								<td class="label_tag_categories_value col-xs-6"> {l s='Set GENDER tags : for each product default category, if available, you will have to select, in the drop and down menu, which Google predefined \"gender\" value defines the gender for which the products of this category are reserved. To assign the same tag to all categories, click on one of the opposite buttons  -------->' mod='facebookproductad'}</td>
								<td class="col-xs-6">
									<span class="btn btn-default" onclick="oFpa.doSet('.gender', 'male');">{l s='Men (male)' mod='facebookproductad'} </span>
									- <span class="btn btn-default" onclick="oFpa.doSet('.gender', 'female');">{l s='Women (female)' mod='facebookproductad'} </span>
									- <span class="btn btn-default" onclick="oFpa.doSet('.gender', 'unisex');">{l s='Unisex' mod='facebookproductad'} </span>
									- <span class="btn btn-default" onclick="oFpa.doSet('.gender', 0);">{l s='Reset' mod='facebookproductad'}</span></td>
							</tr>
							<tr id="bulk_action_tagadult">
								<td class="label_tag_categories_value col-xs-6">{l s='Set ADULT tags : for each product default category, if the products of the category are for adult only, select the \"true\" value in the drop and down menu.' mod='facebookproductad'}</td>
								<td class="col-xs-6">
									<span class="btn btn-default" onclick="oFpa.doSet('.adult', 'true');">{l s='Set for all categories' mod='facebookproductad'}</span>
									- <span class="btn btn-default" onclick="oFpa.doSet('.adult', 0);">{l s='Reset' mod='facebookproductad'}</span></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="clr_5"></div>
			</div>

			<form class="form-horizontal" method="post" id="bt_form-advanced-tag" name="bt_form-advanced-tag" {if $smarty.const._GSR_USE_JS == true}onsubmit="oFpa.form('bt_form-advanced-tag', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_advanced-tag', 'bt_advanced-tag', false, true, null, 'AdvancedTag', 'loadingAdvancedTagDiv');return false;"{/if}>
				<input type="hidden" name="{$sCtrlParamName|escape:'htmlall':'UTF-8'}" value="{$sController|escape:'htmlall':'UTF-8'}" />
				<input type="hidden" name="sAction" value="{$aQueryParams.tagUpdate.action|escape:'htmlall':'UTF-8'}" />
				<input type="hidden" name="sType" value="{$aQueryParams.tagUpdate.type|escape:'htmlall':'UTF-8'}" />
				<input type="hidden" name="sUseTag" value="{$sUseTag|escape:'htmlall':'UTF-8'}" id="default_tag" />
				<table class="table table-responsive">
					<thead>
					<th class="bt_tr_header text-center"><b>{l s='Shop category' mod='facebookproductad'}</b></th>
					<th class="bt_tr_header text-center"><b>{l s='Tag' mod='facebookproductad'}</b></th>
					</thead>
                    {foreach from=$aShopCategories item=cat}
						<tr>
							<td class="label_tag_categories_value">{$cat.path}</td>
							<td>
								<div class="value_material">
									<div class="col-xs-4">
										<p class="label_tag_categories_value">{l s='Material :' mod='facebookproductad'}</p>
									</div>
									<div class="col-xs-4">
										<select name="material[{$cat.id_category|escape:'htmlall':'UTF-8'}]" class="material" >
											<option value="0">-----</option>
                                            {foreach from=$aFeatures item=feature}
												<option value="{$feature.id_feature|escape:'htmlall':'UTF-8'}" {if $cat.material == $feature.id_feature} selected {/if}>{$feature.name|escape:'html'}</option>
                                            {/foreach}
										</select>
									</div>
								</div>
								<div class="value_pattern">
									<div class="col-xs-4">
										<p class="label_tag_categories_value">{l s='Pattern :' mod='facebookproductad'}</p>
									</div>
									<div class="col-xs-4">
										<select name="pattern[{$cat.id_category|escape:'htmlall':'UTF-8'}]" class="pattern" >
											<option value="0">-----</option>
                                            {foreach from=$aFeatures item=feature}
												<option value="{$feature.id_feature|escape:'htmlall':'UTF-8'}" {if $cat.pattern == $feature.id_feature} selected {/if}>{$feature.name|escape:'html'}</option>
                                            {/foreach}
										</select>
									</div>
								</div>
								<div class="value_agegroup">
									<div class="col-xs-4">
										<p class="label_tag_categories_value">{l s='Age group :' mod='facebookproductad'}</p>
									</div>
									<div class="col-xs-4">
										<select class="agegroup" name="agegroup[{$cat.id_category|escape:'htmlall':'UTF-8'}]" id="agegroup{$cat.id_category|escape:'htmlall':'UTF-8'}">
											<option value="0"{if $cat.agegroup=="0"} selected{/if}>--</option>
											<option value="adult"{if $cat.agegroup=="adult"} selected{/if}>{l s='Adults' mod='facebookproductad'}</option>
											<option value="all ages"{if $cat.agegroup=="all"} selected{/if}>{l s='All ages' mod='facebookproductad'}</option>
											<option value="teen"{if $cat.agegroup=="teen"} selected{/if}>{l s='Teens' mod='facebookproductad'}</option>
											<option value="kids"{if $cat.agegroup=="kids"} selected{/if}>{l s='Kids' mod='facebookproductad'}</option>
											<option value="toddler"{if $cat.agegroup=="toddler"} selected{/if}>{l s='Toddlers' mod='facebookproductad'}</option>
											<option value="infant"{if $cat.agegroup=="infant"} selected{/if}>{l s='Infants' mod='facebookproductad'}</option>
											<option value="newborn"{if $cat.agegroup=="newborn"} selected{/if}>{l s='Newborns' mod='facebookproductad'}</option>
										</select>
									</div>
								</div>
								<div class="value_gender">
									<div class="col-xs-4">
										<p class="label_tag_categories_value">{l s='Gender :' mod='facebookproductad'}</p>
									</div>
									<div class="col-xs-4">
										<select class="gender" name="gender[{$cat.id_category|escape:'htmlall':'UTF-8'}]" id="gender{$cat.id_category|escape:'htmlall':'UTF-8'}">
											<option value="0"{if $cat.gender=="0"} selected{/if}>--</option>
											<option value="male"{if $cat.gender=="male"} selected{/if}>{l s='Men (male)' mod='facebookproductad'}</option>
											<option value="female"{if $cat.gender=="female"} selected{/if}>{l s='Women (female)' mod='facebookproductad'}</option>
											<option value="unisex"{if $cat.gender=="unisex"} selected{/if}>{l s='Unisex' mod='facebookproductad'}</option>
										</select>
									</div>
								</div>
								<div class="value_tagadult">
									<div class="col-xs-4">
										<p class="label_tag_categories_value">{l s='Tag product for adults only :' mod='facebookproductad'}</p>
									</div>
									<div class="col-xs-4">
										<select class="adult" name="adult[{$cat.id_category|escape:'htmlall':'UTF-8'}]" id="adult{$cat.id_category|escape:'htmlall':'UTF-8'}">
											<option value="0"{if $cat.adult=="0"} selected{/if}>--</option>
											<option value="true"{if $cat.adult=="true"} selected{/if}>true</option>
										</select>
									</div>
								</div>
							</td>
						</tr>
                    {/foreach}
				</table>
				<p style="text-align: center !important;">
                    {if $useJs == true}
						<script type="text/javascript">
                            {literal}
                            var oAdvancedCallback = [{}];
                            {/literal}
						</script>
						<input type="button" name="{$sModuleName|escape:'htmlall':'UTF-8'}CommentButton" class="btn btn-success btn-lg" value="{l s='Modify' mod='facebookproductad'}" onclick="oFpa.form('bt_form-advanced-tag', '{$sURI|escape:'htmlall':'UTF-8'}', null, 'bt_advanced-tag', 'bt_advanced-tag', false, true, oAdvancedCallback, 'AdvancedTag', 'loadingAdvancedTagDiv');return false;" />
                    {else}
						<input type="submit" name="{$sModuleName|escape:'htmlall':'UTF-8'}CommentButton" class="btn btn-success btn-lg" value="{l s='Modify' mod='facebookproductad'}" />
                    {/if}
					<button class="btn btn-danger btn-lg" value="{l s='Cancel' mod='facebookproductad'}"  onclick="$.fancybox.close();return false;">{l s='Cancel' mod='facebookproductad'}</button>
				</p>
			</form>
            {literal}
				<script type="text/javascript">
                    // execute management of options
                    oFpaFeatureByCat.handleOptionToDisplay($("#default_tag").val());
                    $("#set_tag").change(function () {
                        oFpaFeatureByCat.handleOptionToDisplay($(this).val());
                    });
				</script>
            {/literal}
		</div>
	</div>
	<div id="loadingAdvancedTagDiv" style="display: none;">
		<div class="alert alert-info">
			<p style="text-align: center !important;"><img src="{$sLoadingImg|escape:'htmlall':'UTF-8'}" alt="Loading" /></p><div class="clr_20"></div>
			<p style="text-align: center !important;">{l s='The update of your configuration is in progress...' mod='facebookproductad'}</p>
		</div>
	</div>
{/if}

<div class="clr_hr"></div>
<div class="clr_20"></div>