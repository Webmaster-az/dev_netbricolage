If you want the module to display on product page, you must find product.tpl on yor theme, edit it in order to remove native table of quantity discount, and replace it with :
<h3 class="page-product-heading">{l s='Quantity Discounts'}</h3>
{hook h="displayProductPriceBlock" product=$product type="unit_price"}
