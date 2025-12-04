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
{block name='product_miniature_item'}
<div itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="product">
  {if isset($position)}<meta itemprop="position" content="{$position}" />{/if}
  <article class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemprop="item" itemscope itemtype="http://schema.org/Product">
    <div class="thumbnail-container">
      {block name='product_thumbnail'}
        {if $product.cover}
          <a href="{$product.url}" class="thumbnail product-thumbnail">
            <img width="250" height="250" {* class="lazyload" data- *}src="{$product.cover.bySize.home_default.url}" alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}" data-full-size-image-url="{$product.cover.large.url}" />
              {if $product.delivery_in_stock}
                <p id="prodref_block" class="pc-fullwidth product-delivery-min speed-{$product.delivery_in_stock|replace:' jours':''}">
                  <span>Expédié : {$product.delivery_in_stock} </span>
                </p>
              {/if}
          </a>
        {else}
          <a href="{$product.url}" class="thumbnail product-thumbnail">
            <img {* class="lazyload" data- *}src="{$urls.no_picture_image.bySize.home_default.url}" />
          </a>
        {/if}
      {/block}
      <div class="product-description">
        {block name='product_name'}
          {if $page.page_name == 'index'}
            <h3 class="h3 product-title" itemprop="name"><a href="{$product.url}" itemprop="url" content="{$product.url}">{$product.name|truncate:200:'...'}</a></h3>
          {else}
            <h2 class="h3 product-title" itemprop="name"><a href="{$product.url}" itemprop="url" content="{$product.url}">{$product.name|truncate:200:'...'}</a></h2>
          {/if}
        {/block}
        {block name='product_price_and_shipping'}
          {if $product.show_price}
            <div class="product-price-and-shipping">
              {if $product.has_discount}
                {hook h='displayProductPriceBlock' product=$product type="old_price"}
                <div class="pc-product-regularprice">
                  <span class="regular-price" aria-label="{l s='Regular price' d='Shop.Theme.Catalog'}">{$product.regular_price} {if $configuration.display_prices_tax_incl} TTC{else} HT{/if}</span>
                </div>
                {if $product.discount_type === 'percentage'}
                  <span class="discount-percentage discount-product">{$product.discount_percentage}</span>
                {elseif $product.discount_type === 'amount'}
                  <span class="discount-amount discount-product">{$product.discount_amount_to_display}</span>
                {/if}
              {/if}
              {hook h='displayProductPriceBlock' product=$product type="before_price"} 
              <div class="pc_discpriceplusnormalprice">             
                {hook h='displayProductPriceBlock' product=$product type='unit_price'}
                <section class="pc-product-price">
                  <span class="price" aria-label="{l s='Price' d='Shop.Theme.Catalog'}">
                    <span class="pc_finalprodprice">{$product.price} {* {$pc_pricedegrcheaper} *}</span> {if $configuration.display_prices_tax_incl} TTC{else} HT{/if}
                  </span>
                </section>
              </div>
              <meta itemprop="description" content="{$product.description}" />
              <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="invisible">
                <link itemprop="url" href="{$product.url}" />
                <meta itemprop="availability" content="https://schema.org/InStock" />
                <meta itemprop="priceCurrency" content="{$currency.iso_code}" />
                <meta itemprop="price" content="{$product.price_amount}" />
                <meta itemprop="priceValidUntil" content="2050-11-20" />
              </div>
              <div itemprop="aggregateRating" itemtype="https://schema.org/AggregateRating" itemscope>
                <meta itemprop="reviewCount" content="37" />
                <meta itemprop="ratingValue" content="5" />
              </div>
              <div itemprop="review" itemtype="https://schema.org/Review" itemscope>
                <div itemprop="author" itemtype="https://schema.org/Person" itemscope>
                  <meta itemprop="name" content="Net Bricolage" />
                </div>
                <div itemprop="reviewRating" itemtype="https://schema.org/Rating" itemscope>
                  <meta itemprop="ratingValue" content="5" />
                  <meta itemprop="bestRating" content="5" />
                </div>
              </div>
              <div itemprop="brand" itemtype="https://schema.org/Brand" itemscope>
                <meta itemprop="name" content="NetBricolage" />
              </div>
              <link itemprop="image" href="{$urls.no_picture_image.bySize.home_default.url}" />
              {hook h='displayProductPriceBlock' product=$product type='weight'}
            </div>
          {/if}
        {/block}
        {block name='product_reviews'}
          {hook h='displayProductListReviews' product=$product}
        {/block}
      </div>
      {include file='catalog/_partials/product-flags.tpl'}
      {*  <form class="pc-prodaddtocart" action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
          <input type="hidden" name="token" value="{$static_token}">
          <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
          <button class="btn btn-primary add-to-cart" data-button-action="add-to-cart" type="submit">
            <i class="material-icons shopping-cart"></i>
            {l s='Add to cart' d='Shop.Theme.Actions'}
          </button>
        </form>
      *}
      <a style="text-align: center;font-weight: bold;" class="quick-view js-quick-view pc-prodaddtocart btn-primary" href="#" data-link-action="quickview">
        <i class="material-icons shopping-cart"></i>
        {l s='Add to cart' d='Shop.Theme.Actions'}
      </a>
      {* <div class="highlighted-informations{if !$product.main_variants} no-variants{/if} hidden-sm-down">
        {block name='quick_view'}
          <a class="quick-view" href="#" data-link-action="quickview">
            <i class="material-icons search">&#xE8B6;</i> {l s='Quick view' d='Shop.Theme.Actions'}
          </a>
        {/block}
        {block name='product_variants'}
          {if $product.main_variants}
            {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
          {/if}
        {/block}
      </div> *}
    </div>
  </article>
</div>
{/block}