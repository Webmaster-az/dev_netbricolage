{**
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

{*$groups|@var_dump*}
{*$product|@var_dump*}
{*$groups|@var_dump*}
{*$combinations|@var_dump*}

{if isset($product->maximum_quantity)}
	{addJsDef maximum_quantity=$product->maximum_quantity|intval}
{/if}
{if isset($product->increment_qty)}
	{addJsDef increment_qty=$product->increment_qty|intval}
{/if}
{if isset($product->multiple_qty)}
	{addJsDef multiple_qty=$product->multiple_qty|intval}
{/if}