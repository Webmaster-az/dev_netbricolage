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
*  @copyright  2015-2021 Musaffar Patel
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Property of Musaffar Patel
*}

<div id="ppbs-stockmanagement-content">

    <div class="form-group row">
        <label class="control-label col-lg-2">
            {l s='Stock Management Enabled' mod='productpricebysize'}
        </label>
        <div class="col-lg-10">
            <input data-toggle="switch" class="" id="stock_enabled" name="stock_enabled" data-inverse="true" type="checkbox"
                   value="1" {if $ppbs_product->stock_enabled eq "1"}checked{/if} />
        </div>
    </div>


    {if $combinations|@count gt 0}
        <table class="table">
            <thead class="thead-default" id="combinations_thead">
            <tr>
                <th style="width: 70px;"></th>
                <th>Combinations</th>
                <th>Quantity</th>
            </tr>
            </thead>
            <tbody class="js-combinations-list panel-group accordion">
            {foreach from=$combinations item=combination}
                <tr class="combination loaded" data-id_product_attribute="{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}" style="display: table-row;">
                    <td class="img">
                        {if $combination.image_url neq ''}
                            <img src="{$combination.image_url|escape:'htmlall':'UTF-8'}" style="width: 50px;" class="img-responsive">
                        {else}
                            <div class="fake-img"></div>
                        {/if}
                    </td>
                    <td {if $combination.default_on eq 1}style="font-weight: bold"{/if}">
                        {$combination.attributes|escape:'htmlall':'UTF-8'}
                    </td>
                    <td class="attribute-quantity">
                        <div>
                            <input name="qty_stock_{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}" id="qty_stock_{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}" type="text" value="{$combination.qty_stock|escape:'htmlall':'UTF-8'}" class="form-control text-sm-left" style="width:200px;">
                        </div>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {else}
        <div class="form-group">
            <label class="form-control-label">{l s='Total Area available in stock for this product' mod='productpricebysize'}</label>
                <div class="translationsFields tab-content">
                    <input type="text" id="qty_stock_0" name="qty_stock_0" placeholder="{l s='quantity area' mod='productpricebysize'}" class="form-control" value="{$qty_stock|escape:'htmlall':'UTF-8'}">
                </div>
        </div>
    {/if}

    <button type="button" id="btn-ppbs-stockmanagement-save" class="btn btn-primary" style="min-width:200px;">{l s='Save' mod='productpricebysize'}</button>
</div>

<script>
    $(document).ready(function () {
        prestaShopUiKit.init();
    });
</script>