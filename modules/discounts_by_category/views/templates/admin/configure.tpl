<form method="post">
    <label for="display_discounts">Mostrar tabela de descontos?</label>
    <input type="checkbox" name="display_discounts" id="display_discounts" value="1" {if $display_discounts}checked{/if}>
    <button type="submit" name="submit_{$module_name}">Salvar</button>
</form>




<h2>{l s='Gerenciar Descontos por Categoria'}</h2>

<form method="post">
    <label for="id_category">{l s='Categoria'}</label>
    <select name="id_category" id="id_category">
        {foreach from=$discount_categories item=category}
            <option value="{$category.id_category}" {if isset($smarty.request.id_category) && $smarty.request.id_category == $category.id_category}selected{/if}>
                {$category.name}
            </option>
        {/foreach}
    </select>

    <label for="quantity">{l s='Quantidade Mínima'}</label>
    <input type="number" name="quantity" id="quantity" value="{if isset($smarty.request.quantity)}{$smarty.request.quantity}{/if}" required>

    <label for="discount">{l s='Desconto (%)'}</label>
    <input type="text" name="discount" id="discount" value="{if isset($smarty.request.discount)}{$smarty.request.discount}{/if}" required>

    <button type="submit" name="submit_discount_category">{l s='Salvar Desconto'}</button>
</form>

<h3>{l s='Descontos Atuais'}</h3>
<table>
    <thead>
        <tr>
            <th>{l s='Categoria'}</th>
            <th>{l s='Quantidade'}</th>
            <th>{l s='Desconto (%)'}</th>
            <th>{l s='Ações'}</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$discount_categories item=discount}
            <tr>
                <td>{$discount.name}</td>
                <td>{$discount.quantity}</td>
                <td>{$discount.discount}%</td>
                <td>
                    <a href="index.php?controller=AdminModules&configure={$module_name}&id_category={$discount.id_category}&quantity={$discount.quantity}">{l s='Editar'}</a>
                    <a href="index.php?controller=AdminModules&configure={$module_name}&delete={$discount.id_category}&quantity={$discount.quantity}">{l s='Excluir'}</a>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>
