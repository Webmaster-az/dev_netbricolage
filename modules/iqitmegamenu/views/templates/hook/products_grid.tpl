{*
* 2007-2016 IQIT-COMMERCE.COM
*
* NOTICE OF LICENSE
*
*  @author    IQIT-COMMERCE.COM <support@iqit-commerce.com>
*  @copyright 2007-2016 IQIT-COMMERCE.COM
*  @license   GNU General Public License version 2
*
* You can not resell or redistribute this software.
* 
*}

	<ul class="cbp-products-big flexslider_carousel row ">
	{foreach from=$products item=product name=homeFeaturedProducts}
	<li class="ajax_block_product col-xs-{$perline|escape:'html':'UTF-8'}">
		<div class="product-container">
		<div class="product-image-container">
			<a class="product_img_link"	href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" >
				<img class="replace-2x img-responsive img_0" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" {if isset($homeSize)} width="{$homeSize.width|escape:'htmlall':'UTF-8'}" height="{$homeSize.height|escape:'htmlall':'UTF-8'}"{/if} />
			</a>
		</div>
			{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
			<a class="cbp-product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" >
				{$product.name|truncate:60:'...'|escape:'html':'UTF-8'}
			</a>
		{if $product.show_price == 1 AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                         <div class="content_price">
                            <span  class="price product-price">{convertPrice price=$product.displayed_price}</span>
                            	{if isset($product.reduction) && $product.reduction && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
                            	<span class="old-price product-price">
									{displayWtPrice p=$product.price_without_reduction}
								</span>
								 {/if}
                        </div>
                        {/if}	</div>
	</li>	
	
	{/foreach}
</ul>