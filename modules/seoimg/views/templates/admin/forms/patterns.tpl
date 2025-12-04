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

<ul class="tags_select" {if $social}style="padding-top:10px"{/if}>
	<li>
		<a class="pattern-btn" data-ref="{literal}{{/literal}product_name{literal}}{/literal}" title="{l s='Product name' mod='seoimg'}">
			{l s='Product name' mod='seoimg'}
		</a>
	</li>
	<li>
		<a class="pattern-btn" data-ref="{literal}{{/literal}product_reference{literal}}{/literal}" title="{l s='Product reference' mod='seoimg'}">
			{l s='Product reference' mod='seoimg'}
		</a>
	</li>
	<li>
		<a class="pattern-btn" data-ref="{literal}{{/literal}manufacturer_name{literal}}{/literal}" title="{l s='Product manufacturer' mod='seoimg'}">
			{l s='Product manufacturer' mod='seoimg'}
		</a>
	</li>
	<li>
		<a class="pattern-btn" data-ref="{literal}{{/literal}default_cat_name{literal}}{/literal}" title="{l s='Product category name' mod='seoimg'}">
			{l s='Product category name' mod='seoimg'}
		</a>
	</li>
{*
	<li>
		<a class="pattern-btn" data-ref="{literal}{{/literal}parent_cat_name{literal}}{/literal}" title="{l s='Product parent category name' mod='seoimg'}">
			{l s='Product parent category name' mod='seoimg'}
		</a>
	</li>
*}
	<li>
		<a class="pattern-btn" data-ref="{literal}{{/literal}product_price{literal}}{/literal}" title="{l s='Product retail price with tax' mod='seoimg'}">
			{l s='Product retail price with tax' mod='seoimg'}
		</a>
	</li>
	<li>
		<a class="pattern-btn" data-ref="{literal}{{/literal}product_reduce_price{literal}}{/literal}" title="{l s='Product specific prices with tax' mod='seoimg'}">
			{l s='Product specific prices with tax' mod='seoimg'}
		</a>
	</li>
	<li>
		<a class="pattern-btn" data-ref="{literal}{{/literal}product_price_wt{literal}}{/literal}" title="{l s='Product pre-tax retail price' mod='seoimg'}">
			{l s='Product pre-tax retail price' mod='seoimg'}
		</a>
	</li>
	<li>
		<a class="pattern-btn" data-ref="{literal}{{/literal}product_reduce_price_wt{literal}}{/literal}" title="{l s='Product pre-tax specific prices' mod='seoimg'}">
			{l s='Product pre-tax specific prices' mod='seoimg'}
		</a>
	</li>
	<li>
		<a class="pattern-btn" data-ref="{literal}{{/literal}product_reduction_percent{literal}}{/literal}" title="{l s='Product reduction' mod='seoimg'}">
			{l s='Product reduction' mod='seoimg'}
		</a>
	</li>
</ul>
