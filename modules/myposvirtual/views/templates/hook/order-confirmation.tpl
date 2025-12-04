<div class="conf confirmation">
	{l s='Congratulations! Your payment has been successfully paid. Your Orders\'s ' mod='myposvirtual'}
	{if isset($mypos_virtual_order.reference)}
	{l s='Reference' mod='mypos_virtual_order'}:
	<b>{$mypos_virtual_order.reference|escape:html:'UTF-8'}</b>
	{else}
	{l s='ID' mod='mypos_virtual_order'}:
	<b>{$mypos_virtual_order.id|escape:html:'UTF-8'}</b>
	{/if}.
</div>