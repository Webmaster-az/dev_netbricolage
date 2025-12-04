<br />
<fieldset>
    <legend><img src="{$module_dir}logo.gif" alt="" /> {l s='myPOS Checkout transaction details' mod='myposvirtual'}</legend>
	{if isset($mypos_virtual_check_payment) && !$mypos_virtual_check_payment}
		<div class="error">{l s='An error occured ' mod='myposvirtual'}{if isset($mypos_virtual_check_payment_error) && $mypos_virtual_check_payment_error} - {$mypos_virtual_check_payment_error|escape:'htmlall':'UTF-8'}{/if}</div><br />
	{/if}
	<table cellpadding="0" cellspacing="0" class="table">
		<tr>
			<td>{l s='Transaction ID' mod='myposvirtual'}</td>
			<td>{$mypos_virtual_transaction_details.id_transaction|escape:'htmlall':'UTF-8'}</td>
		</tr>
		<tr>
			<td>{l s='Amount charged' mod='myposvirtual'}</td>
			<td>{$mypos_virtual_transaction_details.amount|escape:'htmlall':'UTF-8'} {$mypos_virtual_transaction_details.currency|escape:'htmlall':'UTF-8'}</td>
		</tr>
		<tr>
			<td>{l s='Mode' mod='myposvirtual'}</td>
			<td>{if $mypos_virtual_transaction_details.mode == 'test'}<span style="color: #CC0000;">{l s='Test' mod='myposvirtual'}</span>{else}{l s='Production' mod='myposvirtual'}{/if}</td>
		</tr>
		<tr>
			<td>{l s='Date' mod='myposvirtual'}</td>
			<td>{$mypos_virtual_transaction_details.date_add|escape:'htmlall':'UTF-8'}</td>
		</tr>
	</table>
</fieldset>
<form method="post" action="" name="check_payment">
	<input type="submit" name="process_check_payment" value ="{l s='Check For Payment' mod='myposvirtual'}" class="button" />
</form>
<br />
<fieldset>
	<legend><img src="{$module_dir}logo.gif" alt="" /> {l s='Proceed to a full or partial refund via myPOS Checkout' mod='myposvirtual'}</legend>
	{if isset($mypos_virtual_refund) && $mypos_virtual_refund}
		<div class="conf">{l s='Refund successfully performed' mod='myposvirtual'}</div><br />
	{else}
		{if isset($mypos_virtual_refund) && !$mypos_virtual_refund}
		<div class="error">{l s='An error occured during this refund' mod='myposvirtual'}{if isset($mypos_virtual_refund_error) && $mypos_virtual_refund_error} - {$mypos_virtual_refund_error|escape:'htmlall':'UTF-8'}{/if}</div><br />
		{/if}
	{/if}

	{if $mypos_virtual_refund_time_expired}
		<div class="info">{l s='This order has been placed more than 60 days ago or no transaction details are available. Therefore, it cannot be refunded anymore.' mod='myposvirtual'}</div>
	{/if}

	<table class="table" cellpadding="0" cellspacing="0">
		<tr>
			<th>{l s='Date' mod='myposvirtual'}</th>
			<th>{l s='Amount refunded' mod='myposvirtual'}</th>
		</tr>
		{assign var=total_refund value=0}
		{foreach from=$mypos_virtual_refund_details item=refund_transaction}
		<tr>
			<td>{$refund_transaction.date_add|escape:'htmlall':'UTF-8'} </td>
			<td>{$refund_transaction.amount|escape:'htmlall':'UTF-8'} {$refund_transaction.currency|escape:'htmlall':'UTF-8'} </td>
		</tr>
		{assign var=total_refund value = $total_refund + $refund_transaction.amount}
		{/foreach}
		<tr>
			<td>{l s='Total refunded:' mod='myposvirtual'}</td>
			<td>{$total_refund|escape:'htmlall':'UTF-8'} {$refund_transaction.currency|escape:'htmlall':'UTF-8'} </td>
		</tr>
	</table>
	<br />

	{if $mypos_virtual_transaction_details.amount == $total_refund && $total_refund}
		{l s='This order has been fully refunded.' mod='myposvirtual'}
	{else}
		<form method="post" action="" name="refund">
			{l s='Refund:' mod='myposvirtual'} <input type="text" name="refund_amount" value="{($mypos_virtual_transaction_details.amount-$total_refund)|floatval}" />
			<input type="hidden" name="id_transaction" value="{$mypos_virtual_transaction_details.id_transaction|escape:'htmlall':'UTF-8'}" />
			<input type="submit" name="process_refund" value ="{l s='Process Refund' mod='myposvirtual'}" class="button" />
		</form>
	{/if}
</fieldset>

<br/>