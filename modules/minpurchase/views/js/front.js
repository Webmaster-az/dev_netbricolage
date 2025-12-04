/**
* Minimum and maximum unit quantity to purchase
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
*/
move();
function move()
{
    if (window.jQuery) {
        $(window).load(function() {
            if ($('#minimal_quantity_wanted_p').length > 0) {
                $('.purchaseText.min').insertAfter("#minimal_quantity_wanted_p");
                $('.purchaseText.max').removeClass('hidetext').fadeOut().fadeIn('slow');
            } else {
                $('.purchaseText.min').removeClass('hidetext');
                $('.purchaseText.max').removeClass('hidetext');
            }
        });
    } else {
        setTimeout(function() { move() }, 100);
    }
}
function checkMinimalQuantity(minimal_quantity)
{
    if (typeof combinationsFromController !== "undefined") {
        if (combinationsFromController[$('#idCombination').val()] != undefined) {
            if (combinationsFromController[$('#idCombination').val()]['minimal_quantity'] != undefined) {
                minimal_quantity = parseInt(combinationsFromController[$('#idCombination').val()]['minimal_quantity']);
            }
        }
        if ($('#quantity_wanted').val() < minimal_quantity)
        {
            $('#quantity_wanted').css('border', '1px solid red');
            $('#minimal_quantity_wanted_p').css('color', 'red');
        }
        else
        {
            $('#quantity_wanted').css('border', '1px solid #BDC2C9');
            $('#minimal_quantity_wanted_p').css('color', '#374853');
        }
    }
}
fieldName = 'qty';
fieldId = 'quantity_wanted';
$(document).ready(function() {
    currentVal = parseInt($('#quantity_wanted').val());
});
$(document).on('change', '#quantity_wanted', function(e) {
    value = parseInt($('#quantity_wanted').val());
    if (!isNaN(value)) {
        if (typeof combinationsFromController !== "undefined") {
            if (combinationsFromController[$('#idCombination').val()] != undefined) {
                if (combinationsFromController[$('#idCombination').val()]['minimal_quantity'] != undefined) {
                    minimalQuantity = parseInt(combinationsFromController[$('#idCombination').val()]['minimal_quantity']);
                }
                if (combinationsFromController[$('#idCombination').val()]['multiple_qty'] != undefined) {
                    multiple_qty = parseInt(combinationsFromController[$('#idCombination').val()]['multiple_qty']);
                }
                if (combinationsFromController[$('#idCombination').val()]['increment_qty'] != undefined) {
                    increment_qty = parseInt(combinationsFromController[$('#idCombination').val()]['increment_qty']);
                }
                if (combinationsFromController[$('#idCombination').val()]['minimal_quantity'] != undefined) {
                    maximum_quantity = parseInt(combinationsFromController[$('#idCombination').val()]['maximum_quantity']);
                }
            }
        }

        if (typeof multiple_qty == "undefined" && typeof increment_qty == "undefined") {
            multiple_qty = 1;
        }

        if (typeof multiple_qty != "undefined" && multiple_qty > 0) {
            if (value < minimalQuantity) {
                $('input[name='+fieldName+']').val(minimalQuantity);
            } else {
                if (value >= currentVal) {
                    newVal = nextMultiple(value, multiple_qty);
                    if (newVal < minimalQuantity) {
                        $('input[name='+fieldName+']').val(minimalQuantity);
                    } else {
                        $('input[name='+fieldName+']').val(newVal);
                    }
                } else {
                    newVal = previousMultiple(value, multiple_qty);
                    if (newVal < minimalQuantity) {
                        $('input[name='+fieldName+']').val(minimalQuantity);
                    } else {
                        $('input[name='+fieldName+']').val(newVal);
                    }
                }
            }
        } else if (typeof increment_qty != "undefined" && increment_qty > 0) {
            if (value < minimalQuantity) {
                $('input[name='+fieldName+']').val(minimalQuantity);
            } else if (value > currentVal) {
                newVal = parseInt(currentVal) + parseInt(increment_qty);
                if (newVal < minimalQuantity) {
                    $('input[name='+fieldName+']').val(minimalQuantity);
                } else {
                    $('input[name='+fieldName+']').val(newVal);
                }
            } else if (value < currentVal) {
                newVal = parseInt(currentVal) - parseInt(increment_qty);
                if (newVal < minimalQuantity) {
                    $('input[name='+fieldName+']').val(minimalQuantity);
                } else {
                    $('input[name='+fieldName+']').val(newVal);
                }
            }
        } else {
            if (!isNaN(currentVal) && currentVal <= minimalQuantity && currentVal > 1) {
                $('input[name='+fieldName+']').val(minimalQuantity);
            }
        }
        if (typeof maximum_quantity != "undefined" && maximum_quantity > 0) {
            if ($('input[name='+fieldName+']').val() >= maximum_quantity) {
                $('input[name='+fieldName+']').val(maximum_quantity);
            }
        }
        currentVal = parseInt($('#quantity_wanted').val());
    }
});
if (typeof downQuantity !== "undefined") {
    downQuantity = (function() {
        var downQuantityCached = downQuantity;
        return function(json) {
            downQuantityCached.apply(this, arguments);
        }
    })();
}
function nextMultiple(value, pattern) {
    return Math.ceil(value/pattern)*pattern;
}
function previousMultiple(value, pattern) {
    return Math.floor(value/pattern)*pattern;
}