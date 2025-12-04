{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author Musaffar Patel <musaffar.patel@gmail.com>
*  @copyright  2007-2021 Musaffar Patel
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Property of Musaffar Patel
*}

<table class="table">
    <tr>
        <th>{l s='Variable Name' mod='productpricebysize'}</th>
        <th>{l s='Variable Value' mod='productpricebysize'}</th>
        <th>{l s='Action' mod='productpricebysize'}</th>
    </tr>
    {foreach from=$variables item=variable}
        <tr class="variable">
            <td class="name">{$variable.name|escape:'htmlall':'UTF-8'}</td>
            <td class="value">{$variable.value|escape:'htmlall':'UTF-8'}</td>
            <td>
                <span class="material-icons btn-edit">edit</span>
                <span class="material-icons btn-delete">delete</span>
            </td>
        </tr>
    {/foreach}
</table>