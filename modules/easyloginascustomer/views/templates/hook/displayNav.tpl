{*
* Easy login as Customer
*
* NOTICE OF LICENSE
*
*    @author    Remy Combe <remy.combe@gmail.com>
*    @copyright 2013-2016 Remy Combe
*    @license   go to addons.prestastore.com (buy one module for one shop).
*    @for    PrestaShop version 1.7
*}
<div id="easy-connect-top" class="dropdown">
    <button class="dropdown-toggle" type="button" id="dropdownEasyConnectTop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <i class="icon-signin"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownEasyConnectTop">
        <form method="post" action="{$easy_url|escape:'htmlall':'UTF-8'}" class="form-inline">
            <div class="form-group" style="width:100%;">
                <input type="text" class="form-control" id="search-connect" name="search" value="" placeholder="{l s='Search customer' mod='easyloginascustomer'}" autocomplete="off" />
                <input type="hidden" name="easy_token" id="token-connect" value="{$token|escape:'htmlall':'UTF-8'}" />
            </div>
            <div class="input-group">
                <select name="id" id="customer-connect">
                    <option>{l s='Last connections' mod='easyloginascustomer'}</option>
                {foreach from=$easy_history item=eh name=myLoop}
                    {if isset($loginascustomer_id) && $loginascustomer_id == $eh.id_customer}
                    <option value="{$eh.id_customer|escape:'htmlall':'UTF-8'}" selected="selected">{$eh.name|escape:'htmlall':'UTF-8'}</option>
                    {else}
                    <option value="{$eh.id_customer|escape:'htmlall':'UTF-8'}">{$eh.name|escape:'htmlall':'UTF-8'}</option>
                    {/if}
                {/foreach}
                </select>
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-default">{l s='Ok' mod='easyloginascustomer'}</button>
                </span>
            </div>
        </form>
     </div>
</div>


