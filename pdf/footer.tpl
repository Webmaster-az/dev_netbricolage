{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 *}
<table style="width: 100%;">
	<tr>
		<td style="text-align: center; font-size: 6pt; color: #444;  width:100%; ">
			NetBricolage | BONUSPÓDIO UNIPESSOAL, LDA. | {$shop_address|escape:'html':'UTF-8'} | NIF 515336963 | IVA Intracomunitário PT515336963 
			{if !empty($shop_phone) OR !empty($shop_fax)}
				{if !empty($shop_phone)}
					| {l s='Tel: %s' sprintf=[$shop_phone|escape:'html':'UTF-8'] d='Shop.Pdf' pdf='true'}
				{/if}
				{if !empty($shop_fax)}
					| {l s='Fax: %s' sprintf=[$shop_fax|escape:'html':'UTF-8'] d='Shop.Pdf' pdf='true'}
				{/if}
			{/if}
			<br />

		</td>
	</tr>
	<tr>
		<td style="text-align: left; font-size: 5pt; color: #444;  width:100%; margin-top:10px;border-top: 1px solid #444;">	
			{if isset($shop_details)}
				{$shop_details|escape:'html':'UTF-8'}<br />
			{/if}
			{if isset($free_text)}
				{$free_text|escape:'html':'UTF-8'}<br />
			{/if}
		</td>
	</tr>
</table>

