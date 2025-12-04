<form method="post" action="{$deleteorders_form_action}">
    <label for="order_id">{l s='ID da Encomenda' mod='deleteorders'}</label>
    <input type="number" name="order_id" id="order_id" required>
    <button type="submit" name="delete_order" class="btn btn-danger">
        {l s='Excluir Encomenda' mod='deleteorders'}
    </button>
</form>
