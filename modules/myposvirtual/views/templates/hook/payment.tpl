<form action="{$purchase->getCnf()->getIpcURL()|escape:'htmlall':'UTF-8'}" method="post">
    {foreach from=$purchase->params key="key" item="value"}
    <input type="hidden" name="{$key}" value="{$value}">
    {/foreach}
    <p class="payment_module">
        <input type="submit" value="" style="vertical-align: middle; margin-right: 10px; background-image: url('{$module_dir}myPOS Virtual logo_253x80.png'); background-color: white; background-color: transparent; width: 253px; height: 80px; border: 0;" /> {l s='Pay with myPOS virtual' mod='myposvirtual'}
    </p>
</form>