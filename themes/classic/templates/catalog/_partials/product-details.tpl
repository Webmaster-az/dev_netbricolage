<div style="opacity:1;" class="tab-pane fade{if !$product.description} in active{/if}"
     id="product-details"
     data-product="{$product.embedded_attributes|json_encode}"
     role="tabpanel"
  >
  <h2 class="pc-htwo_custom">Spécifications techniques</h2>
  
  {block name='product_reference'}
    {if isset($product_manufacturer->id)}
      <div class="product-manufacturer">
        {if isset($manufacturer_image_url)}
          <a href="{$product_brand_url}">
            <img src="{$manufacturer_image_url}" height="70" style="max-height: 70px;max-width: 100%; height:auto;" class="img img-thumbnail manufacturer-logo" alt="{$product_manufacturer->name}">
          </a>
        {else}
          <label class="label">{l s='Brand' d='Shop.Theme.Catalog'}</label>
          <span>
            <a href="{$product_brand_url}">{$product_manufacturer->name}</a>
          </span>
        {/if}
      </div>
    {/if}
    {if isset($product.reference_to_display) && $product.reference_to_display neq ''}
      <div class="product-reference">
        <label class="label">{l s='Reference' d='Shop.Theme.Catalog'} </label>
        <span itemprop="sku">{$product.reference_to_display}</span>
      </div>
    {/if}
    <div style="displaY:none;">
      <label class="label">Delai de Livraison</label>
      <span>{$product.deliverytime}</span>
    </div>
  {/block}
  {block name='product_attachments'}
    {if $product.attachments}
      <div class="tab-pane fade in active col-md-12 hidden-xs-down" id="attachments" role="tabpanel" style="text-align: right;">
        <section class="product-attachments">
          {foreach from=$product.attachments item=attachment}
            <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
              <table class="attachment" style="width: 100%">
                <tr>
                  <td style="width: 90%">
                    <h4>{$attachment.name}</a></h4>
                  </td>
                  <td rowspan="2" style="font-size: 30px;text-align: center;">
                    <a href="{url entity='attachment' params=['id_attachment' => $attachment.id_attachment]}">
                      <i class="fa fa-download" aria-hidden="true"></i> 
                    </a>
                  </td>
                </tr>
                <tr>
                  <td style="width: 90%">
                    <p>{$attachment.description} ({$attachment.file_size_formatted})</p>
                  </td>
                </tr>
              </table>
            </a>
          {/foreach}
        </section>
      </div>
    {/if}
  {/block}
  {block name='product_quantities'}
    {if $product.show_quantities}
      <div class="product-quantities">
        <label class="label">{l s='In stock' d='Shop.Theme.Catalog'}</label>
        <span data-stock="{$product.quantity}" data-allow-oosp="{$product.allow_oosp}">{$product.quantity} {$product.quantity_label}</span>
      </div>
    {/if}
  {/block}
  {block name='product_availability_date'}
    {if $product.availability_date}
      <div class="product-availability-date">
        <label>{l s='Availability date:' d='Shop.Theme.Catalog'} </label>
        <span>{$product.availability_date}</span>
      </div>
    {/if}
  {/block}
  {block name='product_out_of_stock'}
    <div class="product-out-of-stock">
      {hook h='actionProductOutOfStock' product=$product}
    </div>
  {/block}
  {block name='product_features'}
    {if $product.grouped_features}
      <section class="product-features">
        <p class="h6">{l s='Data sheet' d='Shop.Theme.Catalog'}</p>
        <dl class="data-sheet">
          {foreach from=$product.grouped_features item=feature}
            <dt class="name">{$feature.name}</dt>
            <dd class="value">{$feature.value|escape:'htmlall'|nl2br nofilter}</dd>
          {/foreach}
        </dl>
      </section>
    {/if}
  {/block}
  {* if product have specific references, a table will be added to product details section *}
  {block name='product_specific_references'}
    {if !empty($product.specific_references)}
      <section class="product-features" id="pc_getean13">
        <p class="h6">{l s='Specific References' d='Shop.Theme.Catalog'}</p>
        {foreach from=$product.specific_references item=reference key=key}
          <span class="{$key}">{$reference}</span>
        {/foreach}
      </section>
    {/if}
  {/block}
  {block name='product_condition'}
    {if $product.condition}
      <div class="product-condition">
        <label class="label">{l s='Condition' d='Shop.Theme.Catalog'} </label>
        <link itemprop="itemCondition" href="{$product.condition.schema_url}"/>
        <span>{$product.condition.label}</span>
      </div>
    {/if}
  {/block}
</div>
