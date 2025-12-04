{**

 * Copyright since 2007 PrestaShop SA and Contributors

 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA

 *

 * NOTICE OF LICENSE

 *

 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)

 * that is bundled with this package in the file LICENSE.md.

 * It is also available through the world-wide-web at this URL:

 * https://opensource.org/licenses/AFL-3.0

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

 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)

 *}

<div class="card-block cart-summary-totals">



  {block name='cart_summary_total'}

    {if !$configuration.display_prices_tax_incl && $configuration.taxes_enabled}

      <div class="cart-summary-line cart-total">

        <span class="label">{$cart.totals.total.label}&nbsp;{$cart.labels.tax_short}</span>

        <span class="value">{$cart.totals.total.value}</span>

      </div>

       

      {* <div class="cart-summary-line cart-total">

        <span class="label">{$cart.totals.total_including_tax.label}</span>

        <span class="value">{$cart.totals.total_including_tax.value}</span>

      </div> *}

    {else}

      {if $customer.is_logged}

        {if $cart.id_address_delivery}

          {assign var="delivery_id" value=$cart.id_address_delivery}

          {assign var="delivery_country" value=$customer.addresses.{$delivery_id}.country}



          {if $delivery_country eq 'France'}

            {$pc_taxcountry=1.20}

            {$pc_taxcountrylbl="20%"}

          {elseif $delivery_country eq 'Portugal'}

            {$pc_taxcountry=1.23}

            {$pc_taxcountrylbl="23%"}

          {elseif $delivery_country eq 'Belgique' || $delivery_country eq 'Espagne' || $delivery_country eq 'Pays-bas'}

            {$pc_taxcountry=1.21}

            {$pc_taxcountrylbl="21%"}

          {elseif $delivery_country eq 'Finlande'}

            {$pc_taxcountry=1.24}

            {$pc_taxcountrylbl="24%"}

          {elseif $delivery_country eq 'Italie'}

            {$pc_taxcountry=1.22}

            {$pc_taxcountrylbl="22%"}

          {elseif $delivery_country eq 'Luxembourg'}

            {$pc_taxcountry=1.17}

            {$pc_taxcountrylbl="17%"}

          {elseif $delivery_country eq 'Allemagne'}

            {$pc_taxcountry=1.19}

            {$pc_taxcountrylbl="19%"}

          {elseif $delivery_country eq 'Danemark'}

            {$pc_taxcountry=1.25}
  
            {$pc_taxcountrylbl="25%"}
  
          {else}

            {$pc_taxcountry=1.23}
  
            {$pc_taxcountrylbl="23%"}
  
          {/if}



          {assign var="total_tax" value=$cart.totals.total.amount / $pc_taxcountry}



          {if $cart.subtotals.tax}

            <div class="cart-summary-line">

              <span class="label">Taxes incluses {$pc_taxcountrylbl} :</span>

              <span class="value">{Tools::displayPrice($cart.totals.total.amount - $total_tax)}</span>

            </div>

          {/if}

        {/if}

      {/if}



      <div class="cart-summary-line cart-total">

        <span class="label">{$cart.totals.total.label}&nbsp;{if $configuration.taxes_enabled}({$cart.labels.tax_short}){/if}</span>

        <span class="value">{$cart.totals.total.value}</span>

      </div>

    {/if}

  {/block}

</div>