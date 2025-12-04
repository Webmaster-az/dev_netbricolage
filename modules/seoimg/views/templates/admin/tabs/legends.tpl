{*
* 2007-2017 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel-group" id="accordion-metas">
	<!-- Product -->
	<div id="panel-metas-1" class="panel">
		<div class="panel-heading">
			{if $ps_version == 0}<h3>{/if}
			<a data-toggle="collapse" data-parent="#accordion-metas" href="#metas-1">{l s='Images rules for Product' mod='seoimg'}</a>
			<span class="panel-heading-action">
				<a id="configuration-metas-1" class="list-toolbar-btn" data-role="meta" data-type="product" data-toggle="tooltip" data-placement="top" title="{l s='Add new rule for SEO' mod='seoimg'}">
					<span>
						<i class="{if $ps_version == 0}icon-plus{else}process-icon-new{/if}"></i>
					</span>
				</a>
			</span>
			{if $ps_version == 0}</h3>{/if}
		</div>

		<p>{l s='Welcome to the image tag optimization interface of your shop! Here you can create quality tags for your product images. Start with the "Add new rule" button!' mod='seoimg'}</p>
		<br />

		{counter start=0 assign="count_rule" print=false}
		{include file=$table_tpl_path node=$rule_history role='metas'}
		<div id="table-metas-1" class="panel-footer">
			<a data-role="meta" data-type="generate" href="#" class="btn btn-default pull-right hide"><i class="process-icon-magic"></i> {l s='Apply all rules' mod='seoimg'}</a>
			<a data-role="meta" data-type="product" href="#" class="btn btn-default pull-right"><i class="process-icon-new {if $ps_version == 0}icon-plus{/if}"></i> {l s='Add new rule' mod='seoimg'}</a>
		</div>
	</div>
</div>
