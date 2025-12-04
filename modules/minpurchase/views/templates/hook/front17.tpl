{*
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
*}

{if $min > 1}
	<span class="purchaseText min hidetext">{$text_min|escape:'htmlall':'UTF-8' nofilter}: {$min|escape:'htmlall':'UTF-8'}</span>
{/if}
{if $max > 0}
	<span class="purchaseText max hidetext">{$text_max|escape:'htmlall':'UTF-8' nofilter}: {$max|escape:'htmlall':'UTF-8'}</span>
{/if}

{if $mul > 0}
	<span class="purchaseText mul hidetext">{$text_mul|escape:'htmlall':'UTF-8' nofilter}: {$mul|escape:'htmlall':'UTF-8'}</span>
{/if}

<script type="text/javascript">

function move()
{
	if (window.jQuery) {
		$(document).ready(function() {
			if ($('#minimal_quantity_wanted_p').length > 0) {
				$('.purchaseText.min').insertAfter("#minimal_quantity_wanted_p");
				$('.purchaseText.max').removeClass('hidetext').fadeOut().fadeIn('slow');
				$('.purchaseText.mul').removeClass('hidetext').fadeOut().fadeIn('slow');
			} else {
				$('.purchaseText.min').removeClass('hidetext');
				$('.purchaseText.max').removeClass('hidetext');
				$('.purchaseText.mul').removeClass('hidetext');
			}
		});
	} else {
		setTimeout(function() { move() }, 100);
	}
}

move();

</script>