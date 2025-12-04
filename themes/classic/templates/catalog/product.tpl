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
{extends file=$layout}
{block name='head_seo' prepend}
  <link rel="canonical" href="{$product.canonical_url}">
{/block}
{block name='head' append}
  <meta property="og:type" content="product">
  <meta property="og:url" content="{$urls.current_url}">
  <meta property="og:title" content="{$page.meta.title}">
  <meta property="og:site_name" content="{$shop.name}">
  <meta property="og:description" content="{$page.meta.description}">
  <meta property="og:image" content="{$product.cover.large.url}">
  {if $product.show_price}
    <meta property="product:pretax_price:amount" content="{$product.price_tax_exc}">
    <meta property="product:pretax_price:currency" content="{$currency.iso_code}">
    <meta property="product:price:amount" content="{$product.price_amount}">
    <meta property="product:price:currency" content="{$currency.iso_code}">
  {/if}
  {if isset($product.weight) && ($product.weight != 0)}
  <meta property="product:weight:value" content="{$product.weight}">
  <meta property="product:weight:units" content="{$product.weight_unit}">
  {/if}
{/block}
{block name='content'}
  <section id="main" itemscope itemtype="https://schema.org/Product">
    <meta itemprop="url" content="{$product.url}">
    <div class="row product-container">
      <div class="col-md-12">
        <div class="col-md-6 panc_prodleftcolimage">
          {block name='page_content_container'}
            <section class="page-content" id="content">
              {block name='page_content'}
               {*  {include file='catalog/_partials/product-flags.tpl'} *}
                {block name='product_cover_thumbnails'}
                  {include file='catalog/_partials/product-cover-thumbnails.tpl'}
                {/block}
              {/block}
            </section>
          {/block}
          {assign var='pc_displayMultiAccessoriesProduct' value={hook h='displayMultiAccessoriesProduct'} }
          {if $pc_displayMultiAccessoriesProduct}          
            {block name='product_discounts'}
              {include file='catalog/_partials/product-discounts.tpl'}
            {/block}
          {/if}
        </div>
        <div class="col-md-6">
          {block name='page_header_container'}
            {block name='page_header'}
              <h2 class="h1" itemprop="name">{block name='page_title'}{$product.name}{/block}</h2>
            {/block}
          {/block}
          <div>
            <a href="#pc_tabs" style="text-decoration-line: revert;">Descriptif détaillé</a>
          </div>
          {* {hook h='displayProductAdditionalInfo' mod='productcomments'} *}
          <p id="prodref_block" class="pc-fullwidth" style="margin: 10px 0px;">
            Réf : <span id="pc_prod_ref">{$product.reference_to_display}</span>
          </p>
          <p id="ean13block" class="pc-fullwidth" style="margin: 10px 0px;">
            EAN13 : <span id="pc_prod_ean"></span>
          </p>
          
          {if $product.delivery_in_stock}
            <p tabindex="0" id="prodref_block" class="pc-fullwidth product-delivery speed-{$product.delivery_in_stock|replace:' jours':''}">
              <span class="deliverytimeattr"></span>
              <span class="deliverytime">Expédié sur {$product.delivery_in_stock}</span>
            </p>
          {/if}
          <div class="product-information">
            {* {block name='product_description_short'}
              <div id="product-description-short-{$product.id}" class="product-description" itemprop="description">{$product.description_short nofilter}</div>
            {/block} *}
            {if $product.is_customizable && count($product.customizations.fields)}
              {block name='product_customization'}
                {include file="catalog/_partials/product-customization.tpl" customizations=$product.customizations}
              {/block}
            {/if}
            <hr>
            <div class="product-actions">
              {block name='product_buy'}
                <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                  <input type="hidden" name="token" value="{$static_token}">
                  <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                  <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id">
                  {block name='product_prices'}
                    {include file='catalog/_partials/product-prices.tpl'}
                  {/block}
                  {block name='product_variants'}
                    {include file='catalog/_partials/product-variants.tpl'}
                  {/block}
                  {block name='product_pack'}
                    {if $packItems}
                      <section class="product-pack">
                        <p class="h4">{l s='This pack contains' d='Shop.Theme.Catalog'}</p>
                        {foreach from=$packItems item="product_pack"}
                          {block name='product_miniature'}
                            {include file='catalog/_partials/miniatures/pack-product.tpl' product=$product_pack showPackProductsPrice=$product.show_price}
                          {/block}
                        {/foreach}
                      </section>
                    {/if}
                  {/block}
                  {block name='product_add_to_cart'}
                    {include file='catalog/_partials/product-add-to-cart.tpl'}
                  {/block}
                  {hook h="displayMultiAccessoriesProduct"}
                  {if !$pc_displayMultiAccessoriesProduct}
                    {block name='product_discounts'}
                      {include file='catalog/_partials/product-discounts.tpl'}
                    {/block}
                  {/if}
                  {block name='product_additional_info'}
                    {include file='catalog/_partials/product-additional-info.tpl'}
                  {/block}
                  {* Input to refresh product HTML removed, block kept for compatibility with themes *}
                  {block name='product_refresh'}{/block}
                </form>
              {/block}
            </div>
          </div>
        </div>
        {block name='product_accessories'}
          {if $accessories}
            <section class="col-md-12 product-accessories clearfix">
              <h2>{l s='You might also like' d='Shop.Theme.Catalog'}</h2>
              <div class="products" itemscope itemtype="http://schema.org/ItemList">
                {foreach from=$accessories item="product_accessory" key="position"}
                  {block name='product_miniature'}
                    {include file='catalog/_partials/miniatures/product.tpl' product=$product_accessory position=$position}
                  {/block}
                {/foreach}
              </div>
            </section>
          {/if}
        {/block}
        <div class="col-md-12 tabs-block">
            {block name='product_tabs'}
              <div id="pc_tabs" class="tabs">
                <div class="tab-content" id="tab-content">
                 <div class="tab-pane fade in{if $product.description} active{/if}" id="description" role="tabpanel">
                   {block name='product_description'}
                    <div class="product-description">
                      <div style="margin-bottom: 2rem;padding:0px;" id="product-description-short-{$product.id}" class="product-description" itemprop="description">
                        <h3 class="pc-htwo_custom">Les points forts</h3>
                        <div style="padding-left:1rem;">
                          {$product.description_short nofilter}
                        </div>
                      </div>
                      <div>
                        <h3 class="pc-htwo_custom">Détails du produit</h3>
                        <div class="pc-prod_description">
                          {$product.description nofilter}
                        </div>
                      </div>
                    </div>
                    {include file='catalog/_partials/product-details.tpl'}
                   {/block}
                 </div>
                 {*block name='product_attachments'}
                   {if $product.attachments}
                    <div class="tab-pane fade in" id="attachments" role="tabpanel">
                       <section class="product-attachments">
                         <p class="h5 text-uppercase">{l s='Download' d='Shop.Theme.Actions'}</p>
                         {foreach from=$product.attachments item=attachment}
                           <div class="attachment">
                             <h4><a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">{$attachment.name}</a></h4>
                             <p>{$attachment.description}</p>
                             <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
                               {l s='Download' d='Shop.Theme.Actions'} ({$attachment.file_size_formatted})
                             </a>
                           </div>
                         {/foreach}
                       </section>
                     </div>
                   {/if}
                 {/block*}
                 {foreach from=$product.extraContent item=extra key=extraKey}
                 <div class="tab-pane fade in {$extra.attr.class}" id="extra-{$extraKey}" role="tabpanel" {foreach $extra.attr as $key => $val} {$key}="{$val}"{/foreach}>
                   {$extra.content nofilter}
                 </div>
                 {/foreach}
              </div>
            </div>
          {/block}
        </div>
      </div>
    </div>
    {block name='product_footer'}
      {hook h='displayFooterProduct' product=$product category=$category}
    {/block}
    {block name='product_images_modal'}
      {include file='catalog/_partials/product-images-modal.tpl'}
    {/block}
    {block name='page_footer_container'}
      <footer class="page-footer">
        {block name='page_footer'}
          <!-- Footer content -->
        {/block}
      </footer>
    {/block}
  </section>
{/block}
