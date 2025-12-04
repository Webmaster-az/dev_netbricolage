{*
/**
 * mitrocops
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 /*
 * 
 * @author    mitrocops
 * @category seo
 * @package gsnipreview
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */

*}

<div class="snippets-clear"></div>

<div class="googlesnippetsblock" style="width:{$gsnipreviewgsnipblock_width|escape:'htmlall':'UTF-8'}{if $gsnipreviewgsnipblock_width != "auto"}px{/if}">

{if $gsnipreviewgsnipblocklogo == 1}
<div class="googlesnippetsblock-title">
<img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/logo-snippet.png" border="0" />
</div>
{/if}

<div itemscope itemtype="http://schema.org/Product">
  <table width="100%" border="0" class="snippets-table-block">
	  <tr>
	  	<td>
	  		<img itemprop="image" src="{$product_image|escape:'htmlall':'UTF-8'}"  class="googlesnippetsblock-img" />
	  	</td>
	  	<td>
	  		<strong><span itemprop="name">{$product_name|escape:"htmlall":"UTF-8"}</span></strong>
	  		<br/>
	  		{l s='Category' mod='gsnipreview'}: <span itemprop="category" content="{$product_category|escape:'htmlall':'UTF-8'}">{$product_category|escape:'html':'UTF-8'}</span>
    		{if strlen($product_brand)>0}
    		<br/>
	  		{l s='Brand' mod='gsnipreview'}: <span itemprop="brand">{$product_brand|escape:'html':'UTF-8'}</span>
	  		{/if}
	  	</td>
	  </tr>
	  <tr>
	  	<td colspan="2">
			<span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			    {l s='Price' mod='gsnipreview'}: {$currency_custom|escape:'htmlall':'UTF-8'}<span itemprop="price">{$product_price_custom|escape:'htmlall':'UTF-8'}</span>
			    <meta itemprop="priceCurrency" content="{$currency_custom|escape:'htmlall':'UTF-8'}" />
			    <br/>
			    {l s='Condition' mod='gsnipreview'}: {l s='New' mod='gsnipreview'}
			    <br/>
			    {if strlen($shop_name)>0}
			    {l s='Available from' mod='gsnipreview'}: <span itemprop="seller">{$shop_name|escape:"htmlall":"UTF-8"}</span>
			    <br/>
			    {/if}
			    {l s='Stock' mod='gsnipreview'}: <span itemprop="availability" content="{$stock_string|escape:'htmlall':'UTF-8'}">{if $stock_string=="in_stock"}{l s='In Stock' mod='gsnipreview'}{else}{$stock_string|escape:'htmlall':'UTF-8'}{/if}</span>
			 </span>
			    		
	  	</td>
	  </tr>
	  <tr>
	  	<td colspan="2">
	  		{l s='Description' mod='gsnipreview'}: <span itemprop="description">{$product_description|escape:"quotes":"UTF-8"}</span>
	  	</td>
	  </tr>




	  {if $gsnipreviewcount != 0}

	  <tr>
	  	<td colspan="2">
	  	<strong>{l s='Rating' mod='gsnipreview'}({l s='s' mod='gsnipreview'}):</strong>
	  	</td>
	  </tr>
	  
	  <tr>
	  	<td colspan="2">

	  		<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">

			    <div class="rating">{$gsnipreviewavg_rating|escape:'htmlall':'UTF-8'}</div>


			    <div class="snippets-clear"></div>
                {l s='Rating' mod='gsnipreview'}: <span itemprop="ratingValue">{$gsnipreviewcount|escape:'htmlall':'UTF-8'}</span>/5 {l s='stars' mod='gsnipreview'}
                <br/>
                {l s='Based on' mod='gsnipreview'}: <span itemprop="ratingCount">{$gsnipreviewtotal|escape:'htmlall':'UTF-8'}</span> {l s='rating' mod='gsnipreview'}({l s='s' mod='gsnipreview'})
		  </span>


        </td>
	  </tr>


      {/if}
  </table>

</div>

</div>


