{if isset($product->discount) && $product->discount}
    <div class="product-discount">
        <p>{l s='Desconto por Quantidade: ' d='Shop.Theme.Catalog'}{$product->discount}%</p>
    </div>
{/if}
