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

<div id="ppbs-areaprice-list">
	<table id="ppbs-areaprices-list-table" class="table">
		<thead>
			<tr>
				<th><span class="title_box">{l s='From (area)' mod='productpricebysize'}</span></th>
				<th><span class="title_box">{l s='To (area)' mod='productpricebysize'}</span></th>
				<th><span class="title_box">{l s='Price Impact' mod='productpricebysize'}</span></th>
				<th><span class="title_box">{l s='Price' mod='productpricebysize'}</span></th>
				<th><span class="title_box">{l s='Weight' mod='productpricebysize'}</span></th>
				<th><span class="title_box">{l s='Action' mod='productpricebysize'}</span></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$area_prices item=area_price}
				<tr data-id_area_price="{$area_price.id_area_price|intval}">
					<td>{$area_price.area_low|escape:'htmlall':'UTF-8'}</td>
					<td>{$area_price.area_high|escape:'htmlall':'UTF-8'}</td>
					<td>
						{if $area_price.impact eq '~'}
							{l s='Fixed Area Price' mod='productpricebysize'}
						{/if}
						{if $area_price.impact eq '='}
							{l s='Fixed Static Price' mod='productpricebysize'}
						{/if}
						{if $area_price.impact eq '+'}
							{l s='Increase By' mod='productpricebysize'}
						{/if}
						{if $area_price.impact eq '-'}
							{l s='Decrease By' mod='productpricebysize'}
						{/if}
						{if $area_price.impact eq '*'}
							{l s='Multiply By' mod='productpricebysize'}
						{/if}
					</td>
					<td>{$area_price.price|escape:'htmlall':'UTF-8'}</td>
					<td>{$area_price.weight|escape:'htmlall':'UTF-8'}</td>
					<td>
						<a href="#edit" class="ppbs-areaprice-edit"><i class="material-icons">edit</i></a>
						<a href="#delete" class="ppbs-areaprice-delete"><i class="material-icons">delete forever</i></a>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>

<button id="ppbs-areaprice-add" type="button" class="btn btn-primary">{l s='Add Area based price' mod='productpricebysize'}</button>