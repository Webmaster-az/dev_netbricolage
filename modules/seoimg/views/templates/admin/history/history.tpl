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

<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" class="table table-striped table-bordered table-hover">
{foreach $history as $data}
<tr>
	<td><b>{$data.field|escape:'htmlall':'UTF-8'}</b></td>
	<td>
		{if $data.field == 'fb_image'}
			{if $data.pattern == 0}
				{l s='All images' mod='seoimg'}
			{else}
				{l s='Cover image' mod='seoimg'}
			{/if}
		{else}
			{$data.pattern|escape:'htmlall':'UTF-8'}
		{/if}
	</td>
</tr>
{/foreach}
</table>
