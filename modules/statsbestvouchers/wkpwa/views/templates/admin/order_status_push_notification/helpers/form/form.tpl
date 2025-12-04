{*
* 2010-2021 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through LICENSE.txt file inside our module
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright 2010-2021 Webkul IN
* @license LICENSE.txt
*}


<div class="panel">
	<div class="panel-heading">
		<i class="icon-cogs"></i> {l s='Order Status Notification' mod='wkpwa'}
	</div>
	<form class="defaultForm {$name_controller} form-horizontal" action="{if $edit}{$current}&update{$table}&id={$notificationDetail['id']}&token={$token}{else}{$current}&add{$table}&token={$token}{/if}" method="post" enctype="multipart/form-data">
		<div class="form-wrapper">
			<div class="form-group">
				<div class="row">
					<label class="col-lg-3 control-label required">
						{l s='Title' mod='wkpwa'}
					</label>
					<div class="row">
						<div class="col-lg-6">
							{foreach from=$languages item=language}
							{assign var="title_smarty" value="title_`$language.id_lang`"}
								<input type="text"
								id="title_{$language.id_lang|escape:'html':'UTF-8'}"
								name="title_{$language.id_lang|escape:'html':'UTF-8'}"
								class="form-control title_value_all"
								value="{if isset($smarty.post.$title_smarty)}{$smarty.post.$title_smarty|escape:'htmlall':'UTF-8'}{elseif $edit }{$notificationDetail['title'][$language.id_lang]|escape:'html':'UTF-8'}{/if}"
								{if $current_lang.id_lang != $language.id_lang}style="display:none;"{/if}
								autocomplete="off" />
							{/foreach}
						</div>
						{if $total_languages > 1}
							<div class="col-lg-2">
								<button type="button" id="title_value_lang_btn" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
									{$current_lang.iso_code|escape:'html':'UTF-8'}
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									{foreach from=$languages item=language}
										<li>
											<a href="javascript:void(0)" onclick="showExtraLangField('{$language.iso_code|escape:'html':'UTF-8'}', {$language.id_lang|escape:'html':'UTF-8'});">{$language.name|escape:'html':'UTF-8'}</a>
										</li>
									{/foreach}
								</ul>
							</div>
						{/if}
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-lg-3 control-label required">
						{l s='Body' mod='wkpwa'}
					</label>
					<div class="row">
						<div class="col-lg-6">
							{foreach from=$languages item=language}
							{assign var="body_smarty" value="body_`$language.id_lang`"}
								<input type="text"
								id="body_{$language.id_lang|escape:'html':'UTF-8'}"
								name="body_{$language.id_lang|escape:'html':'UTF-8'}"
								class="form-control body_value_all"
								value="{if isset($smarty.post.$body_smarty)}{$smarty.post.$body_smarty|escape:'htmlall':'UTF-8'}{elseif $edit }{$notificationDetail['body'][$language.id_lang]|escape:'html':'UTF-8'}{/if}"
								{if $current_lang.id_lang != $language.id_lang}style="display:none;"{/if}
								autocomplete="off" />
							{/foreach}
						</div>
						{if $total_languages > 1}
							<div class="col-lg-2">
								<button type="button" id="body_value_lang_btn" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
									{$current_lang.iso_code|escape:'html':'UTF-8'}
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									{foreach from=$languages item=language}
										<li>
											<a href="javascript:void(0)" onclick="showExtraLangField('{$language.iso_code|escape:'html':'UTF-8'}', {$language.id_lang|escape:'html':'UTF-8'});">{$language.name|escape:'html':'UTF-8'}</a>
										</li>
									{/foreach}
								</ul>
							</div>
						{/if}
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 required">
					{l s='Target URL' mod='wkpwa'}
				</label>
				<div class="col-lg-9">
					<input type="text" name="target_url" id="target_url" class="form-control"
					{if isset($smarty.post.target_url)}
						value="{$smarty.post.target_url}"
					{elseif $edit}
						value="{$notificationDetail['target_url']}"
					{/if}>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label col-lg-3">
					{l s='Icon' mod='wkpwa'}
				</label>
				<div class="col-lg-9">
					<div class="form-group">
						<div class="col-lg-12" id="icon-images-thumbnails">
							<div>
								<img src="{if $edit}{$notificationDetail['icon']}{else}{$psLogo}{/if}" style="max-width: 200px;" class="img-thumbnail">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-6">
							<input id="icon" type="file" name="icon" class="hide">
							<div class="dummyfile input-group">
								<span class="input-group-addon">
									<i class="icon-file"></i>
								</span>
								<input id="icon-name" type="text" name="icon" readonly="">
								<span class="input-group-btn">
									<button id="icon-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
										<i class="icon-folder-open"></i> {l s='Add file' mod='wkpwa'}
									</button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3 required">
					{l s='Order status for sending notifications' mod='wkpwa'}
				</label>
				<div class="col-lg-3">
					<select name="order_status" class="input-large chosen">
						{if $orderStatus|@count}
							{foreach from=$orderStatus item='status'}
								<option value="{$status.id_order_state|intval}"
								{if isset($smarty.post.order_status)}
									{if $smarty.post.order_status}
										{if $status.id_order_state == $smarty.post.order_status}selected="selected"{/if}
									{/if}
								{elseif $edit}
									{if $notificationDetail['order_status']}
										{if $status.id_order_state == $notificationDetail['order_status']}selected="selected"{/if}
										{if $orderStatusExists && in_array($status.id_order_state, $orderStatusExists) && $status.id_order_state != $notificationDetail['order_status']}disabled{/if}
									{/if}
								{elseif $orderStatusExists}
									{if in_array($status.id_order_state, $orderStatusExists)}disabled{/if}
								{/if}>&nbsp;{$status.name}</option>
							{/foreach}
						{/if}
					</select>
				</div>
			</div>

			<div class="form-group wk-available-tag-wrapper">
				<div class="col-sm-6 wk-available-tag-cont">
					<p class="wk-available-tag-heading">{l s='Tags' mod='wkpwa'}</p>
					<ul>
						<li class="wk-available-tag-list">
							<span class="wk-available-tag-span">{literal}{$order_reference}{/literal}</span> - {l s='Order Reference' mod='wkpwa'}
						</li>
						<li class="wk-available-tag-list">
							<span class="wk-available-tag-span">{literal}{$order_total}{/literal}</span> - {l s='Order Total (Tax Included)' mod='wkpwa'}
						</li>
						<li class="wk-available-tag-list">
							<span class="wk-available-tag-span">{literal}{$order_status}{/literal}</span> - {l s='Order Status' mod='wkpwa'}
						</li>
					</ul>
					<p class="wk-available-tag-info">{l s='The text used inside \'{ }\' with \'$\' symbol is a variable (example: {$product_name}). Do Not change these variables as they are representing their corresponding values. You can use these tags in Title and Body field' mod='wkpwa'}</p>
				</div>
			</div>
		</div>
		<div class="panel-footer">
			<a href="{$link->getAdminLink('AdminOrderStatusPushNotification')}" class="btn btn-default">
				<i class="process-icon-cancel"></i>{l s='Cancel' mod='wkpwa'}
			</a>
			<button type="submit" name="submitAdd{$table}" class="btn btn-default pull-right">
				<i class="process-icon-save"></i> {l s='Save' mod='wkpwa'}
			</button>
			<button type="submit" name="submitAdd{$table}AndStay" class="btn btn-default pull-right">
				<i class="process-icon-save"></i> {l s='Save and stay' mod='wkpwa'}
			</button>
		</div>
	</form>
</div>
