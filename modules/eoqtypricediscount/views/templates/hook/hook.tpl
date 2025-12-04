{*
* 2017-2020 Profileo
*
*  @author    Profileo
*  @copyright 2017-2020 Profileo
*}
<script type="text/javascript">
	var from_eo = '{$from_eo|escape:'htmlall':'UTF-8'}';
	var tax_value = '{$tax_value|escape:'htmlall':'UTF-8'}';
</script>
{assign var=currency value=Context::getContext()->currency}
{if (isset($quantity_discounts) && count($quantity_discounts) > 0)}
	<!-- quantity discount -->
	<div style="display: none; position: absolute; width: 100%; background-color: white;z-index:9;border: 1px solid #dfdfdf;bottom: 50px" class="quant_discmod quantity_discount{$eohover|escape:'htmlall':'UTF-8'}">		
		<div style="width:100%;display:flex;background-color: #eaeaea;">
			<span class="text-center" style="width:33%;font-size:12px;font-weight:normal;">{l s='Quantity' mod='eoqtypricediscount'}</span>
			<span class="text-center" style="width:33%;font-size:12px;font-weight:normal;">{l s='Discount' mod='eoqtypricediscount'}</span>
			<span class="text-center" style="width:33%;font-size:12px;font-weight:normal;">{l s='Unit price' mod='eoqtypricediscount'} {if $configuration.display_prices_tax_incl} TTC{else} HT{/if}</span>
		</div>
		<div style="width:100%;">
			{foreach from=$quantity_discounts item='quantity_discount' name='quantity_discounts'}
				<div style="width:100%;display:flex;border-bottom: 1px solid #eaeaea;" id="quantityDiscount_{$quantity_discount.id_product_attribute|intval}" class="quantityDiscount quantityDiscount_{$quantity_discount.id_product_attribute|intval}">
					<div class="text-center" style="width:33%;font-size:12px;font-weight:normal;">
						{$quantity_discount.from_quantity|floatval}
					</div>
					<div class="text-center" style="width:33%;font-size:12px;font-weight:normal;">
						{if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
							{Tools::displayPrice($quantity_discount.real_value|floatval, $currency)}
						{else}
							{$quantity_discount.real_value|floatval}%
						{/if}
					</div>
					<div class="text-center" style="width:33%;font-size:12px;font-weight:normal;">
						{if $quantity_discount.price >= 0 || $quantity_discount.reduction_type == 'amount'}
							{assign var="pc_pricedegrcheaper" value=Tools::displayPrice($productPrice-$quantity_discount.real_value|floatval, $currency) scope="global"}
							{Tools::displayPrice($productPrice-$quantity_discount.real_value|floatval, $currency)}
						{else}
							{assign var="pc_pricedegrcheaper" value=Tools::displayPrice($productPrice-$productPrice*$quantity_discount.reduction|escape:'htmlall':'UTF-8', $currency) scope="global"}
							{Tools::displayPrice($productPrice-$productPrice*$quantity_discount.reduction|escape:'htmlall':'UTF-8', $currency)}
						{/if}						
					</div>
				</div>
			{/foreach}
		</div>
	</div>
{/if}
{if $page.page_name != 'product'}
	<div class="pc_prixdegrhover">
		<i class="far fa-eye"></i>
		<span class="prix_degr_lbl">Prix degressif à partir de :</span>
		<span class="prix_degr_lbl_mobile">Prix degressif :</span>
	</div>
	{if $pc_pricedegrcheaper}
		<section class="pc-product-price">
			<span class="price" aria-label="{l s='Price' d='Shop.Theme.Catalog'}">
				<span class="pc_finalprodprice">{$pc_pricedegrcheaper}</span> {if $configuration.display_prices_tax_incl} TTC{else} HT{/if}
			</span>
		</section>
	{/if}
{/if}