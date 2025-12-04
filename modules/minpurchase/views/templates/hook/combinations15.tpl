{**
* Minimum and maximum purchase quantity
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate
*  @copyright 2023 idnovate
*  @license   See above
*}

{*$groups|@var_dump*}
{*$product|@var_dump*}
{*$groups|@var_dump*}
{*$combinations|@var_dump*}
<script type="text/javascript">
var combinationsFromController = [];
var productMinimum = [];

{if isset($combinations) && count($combinations) > 0}
    {foreach from=$combinations key=idCombination item=combination}
        {if isset($combination.multiple_qty)}
            addComb({$idCombination|intval}, {$combination.minimal_quantity|intval}, {$combination.maximum_quantity|intval}, {$combination.multiple_qty|intval}, {$combination.increment_qty|intval});
        {else}
            addComb({$idCombination|intval}, {$combination.minimal_quantity|intval}, {$combination.maximum_quantity|intval}, 0, 0);
        {/if}
    {/foreach}
{else}
    {if isset($product.maximum_quantity)}
        var product_maximum_quantity = {$product.maximum_quantity|intval};
    {/if}

    {if isset($product.minimal_quantity)}
        var product_minimum_quantity = {$product.minimal_quantity|intval};
    {/if}

    {if isset($product.multiple_qty)}
        var product_multiple_qty = {$product.multiple_qty|intval};
    {/if}

    {if isset($product.increment_qty)}
        var product_increment_qty = {$product.increment_qty|intval};
    {/if}
{/if}


function addComb(idCombination, minimal_quantity, max_quantity, multiple_qty, increment_qty)
{
    var comb = [];
    comb['idCombination'] = idCombination;
    comb['minimal_quantity'] = minimal_quantity;
    comb['maximum_quantity'] = max_quantity;
    comb['multiple_qty'] = multiple_qty;
    comb['increment_qty'] = increment_qty;
    combinationsFromController[idCombination] = comb;
}


if (window.jQuery) {
    if (typeof product_minimum_quantity !== "undefined") {
        $('#quantity_wanted').val(product_minimum_quantity);
    }
}


</script>

