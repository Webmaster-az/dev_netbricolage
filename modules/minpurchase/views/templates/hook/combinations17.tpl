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

{*$product|@var_dump*}

<script type="text/javascript">
    checkJQuery();

    function checkJQuery()
    {
        if (window.jQuery) {
            $(document).ready(function() {
                checkQuantities();
            });
        } else {
            setTimeout(function() { checkJQuery() }, 100);
        }
    }

    function checkQuantities() {
        id_combination = 0;
        {if isset($combinations)}
            combinations = {$combinations|json_encode nofilter};
            {if is_array($product)}
                {if isset($product.id_product_attribute)}
                    id_combination = {$product.id_product_attribute};
                {/if}
            {else}
                {if isset($product->id_product_attribute)}
                    id_combination = {$product->id_product_attribute};
                {/if}
            {/if}
        {/if}

        fieldName = 'qty';
        fieldId = 'quantity_wanted';
        currentVal = document.getElementById('quantity_wanted').value;
        multiple_qty = 0;
        increment_qty = 0;
        minimum_quantity = 0;
        maximum_quantity = 0;

        if (id_combination != 0) {
            if (typeof combinations[id_combination] != "undefined") {
                if (combinations[id_combination]['minimal_quantity'] != undefined) {
                    minimum_quantity = combinations[id_combination]['minimal_quantity'];
                }

                if (combinations[id_combination]['maximum_quantity'] != undefined) {
                    maximum_quantity = combinations[id_combination]['maximum_quantity'];
                }

                if (combinations[id_combination]['multiple_qty'] != undefined) {
                    multiple_qty = combinations[id_combination]['multiple_qty'];
                }

                if (combinations[id_combination]['increment_qty'] != undefined) {
                    increment_qty = combinations[id_combination]['increment_qty'];
                }
            }
        } else {
            {if isset($product)}
                {if is_array($product)}
                    minimum_quantity = {$product.minimal_quantity};
                    {if isset($product.maximum_quantity)}
                        maximum_quantity = {$product.maximum_quantity};
                    {/if}
                    {if isset($product.multiple_qty)}
                        multiple_qty = {$product.multiple_qty};
                    {/if}
                    {if isset($product.increment_qty)}
                        increment_qty = {$product.increment_qty};
                    {/if}
                {else}
                    minimum_quantity = {$product->minimal_quantity};
                    {if isset($product->maximum_quantity)}
                        maximum_quantity = {$product->maximum_quantity};
                    {/if}
                    {if isset($product->multiple_qty)}
                        multiple_qty = {$product->multiple_qty};
                    {/if}
                    {if isset($product->increment_qty)}
                        increment_qty = {$product->increment_qty};
                    {/if}
                {/if}
            {/if}
        }

        if (typeof minimum_quantity != "undefined" && minimum_quantity > 1) {
            if (Number($('input[name='+fieldName+']').val()) < Number(minimum_quantity)) {
                $('input[name='+fieldName+']').val(minimum_quantity);
            }
        }

        if (multiple_qty == 0 && increment_qty == 0) {
            multiple_qty = 1;
        }

        $('#quantity_wanted').on('change', function(event) {
            value = Number($(this).val());
            if (!isNaN(value)) {
                if (typeof minimum_quantity !== "undefined") {
                    if (!isNaN(currentVal) && currentVal <= minimum_quantity && currentVal > 1) {
                        $('input[name='+fieldName+']').val(minimum_quantity);
                    }
                }
                if (typeof multiple_qty != "undefined" && multiple_qty > 0) {
                    if (value < Number(minimum_quantity)) {
                        $('input[name='+fieldName+']').val(minimum_quantity);
                    } else {
                        if (value >= currentVal) {
                            newVal = nextMultiple(value, multiple_qty);

                            if (newVal < Number(minimum_quantity)) {
                                $('input[name='+fieldName+']').val(minimum_quantity);
                            } else {
                                $('input[name='+fieldName+']').val(newVal);
                            }
                        } else {
                            newVal = previousMultiple(value, multiple_qty);
                            if (newVal < minimum_quantity) {
                                $('input[name='+fieldName+']').val(minimum_quantity);
                            } else {
                                $('input[name='+fieldName+']').val(newVal);
                            }
                        }
                        currentVal = newVal;
                    }
                } else if (typeof increment_qty != "undefined" && increment_qty > 0) {
                    if (value < minimum_quantity) {
                        $('input[name='+fieldName+']').val(minimum_quantity);
                    } else {
                        if (value >= currentVal) {
                            newVal = nextIncrement(value, increment_qty, minimum_quantity)
                            if (newVal < minimum_quantity) {
                                $('input[name='+fieldName+']').val(minimum_quantity);
                            } else {
                                $('input[name='+fieldName+']').val(newVal);
                            }
                        } else {

                            newVal = previousIncrement(value, increment_qty, minimum_quantity);
                            //newVal = parseInt(currentVal) - parseInt(increment_qty);
                            if (newVal < minimum_quantity) {
                                $('input[name='+fieldName+']').val(minimum_quantity);
                            } else {
                                $('input[name='+fieldName+']').val(newVal);
                            }
                        }
                        currentVal = newVal;
                    }
                }

                if (typeof maximum_quantity != "undefined" && maximum_quantity > 0) {
                    if (Number($('input[name='+fieldName+']').val()) >= Number(maximum_quantity)) {
                        $('input[name='+fieldName+']').val(maximum_quantity);
                    }
                }
            }
        });

        $(document).ready(function() {
            var $body = $('body');

            prestashop.on('updateCart', function(event) {
                if (typeof event.resp !== 'undefined') {
                    if (typeof event.resp.hasError !== 'undefined') {
                        var errors = '';
                        for (error in event.resp.errors) {
                            //IE6 bug fix
                            if (error != 'indexOf')
                                errors += "<span>" + $('<div>').html(event.resp.errors[error]).text() + "</span>\n";
                        }
                        $.fancybox(
                            $('<div class="alert" style="text-align: center">').html(errors), {
                                type        : 'inline',
                                transitionIn: 'elastic',
                                transitionOut: 'elastic',
                                speedIn: 500,
                                speedOut: 300,
                                autoSize    : false,
                                width       : 550,
                                height      : 80,
                                autoCenter  : true,
                                aspectRatio : true,
                            }
                        );
                        
                    }
                }
            });
        });
    }

    function nextMultiple(value, multiple) {
        return Number(Math.ceil(value/multiple)*multiple);
    }

    function previousMultiple(value, multiple) {
        return Math.floor(value/multiple)*multiple;
    }

    function nextIncrement(value, increment, minimum)
    {
        if (increment == minimum) {
            return nextMultiple(value, increment);
        } else if (increment == 1) {
            return value;
        } else {
            modValue = value % increment;
            if (modValue == 0) {
                return Number(value) + Number(minimum);
            } else {
                return (Number(value) - Number(modValue)) + (Number(increment));
            }
        }
    }

    function previousIncrement(value, increment, minimum)
    {
        if (value == minimum) {
            return minimum;
        }

        difference = Number(value) - Number(minimum);
        modTempValue = difference % increment;

        if (modTempValue == 0) {
            return value;
        }

        return Number(value) - Number(modTempValue);
    }
</script>
