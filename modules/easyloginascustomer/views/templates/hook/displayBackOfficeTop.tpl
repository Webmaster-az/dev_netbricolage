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
{literal}
<script>
    var loginascustomer_url = '{/literal}{$loginascustomer_url|escape:"htmlall":"UTF-8"}{literal}';
    var loginascustomer_token = '{/literal}{$loginascustomer_token|escape:"htmlall":"UTF-8"}{literal}';
    var loginascustomer_employee_data_url = '{/literal}{$loginascustomer_employee_data_url|escape:"htmlall":"UTF-8"}{literal}';
    var loginascustomer_new_tab = '{/literal}{$loginascustomer_new_tab|escape:"htmlall":"UTF-8"}{literal}';
</script>
{/literal}
<div class="component header-right-component hidden" id="easyloginascustomer-component">
    <div id="easyloginascustomer" class="dropdown dropdown-clickable">
        <button class="btn dropdown-toggle" data-toggle="dropdown">
            <i class="material-icons">exit_to_app</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
            <div id="easyloginascustomer-content">
                <div class="notifs_panel_header">
                    <p>
                        <span class="notifs-title">{l s='Login as Customer' mod='easyloginascustomer'}</span>
                        <a data-toggle="dropdown" class="dropdown_loginascustomer_link" href="{$loginascustomer_config_url|escape:'htmlall':'UTF-8'}" style="float:right; text-align:right;">
                            <i class="material-icons" onclick="javascript:document.location.href = '{$loginascustomer_config_url|escape:'htmlall':'UTF-8'}'">settings</i>
                        </a>
                    </p>
                </div>
                <div class="loginascustomer_search">
                    <input type="text" placeholder="{l s='Search for a customer' mod='easyloginascustomer'}" value="" class="form-control" name="loginascustomer_search" id="loginascustomer_search">
                </div>
                <div id="loginascustomer_result">
                    {if isset($loginascustomer_history)}
                        {foreach from=$loginascustomer_history item=lh name=myLoop}
                        <div class="panel-heading loginascustomer_line">
                            <i class="icon-user"></i> {if isset($lh.company)}<strong>{$lh.company|escape:'htmlall':'UTF-8'}</strong> {/if}{$lh.firstname|escape:'htmlall':'UTF-8'} {$lh.lastname|escape:'htmlall':'UTF-8'} [{$lh.id_customer|escape:'htmlall':'UTF-8'}]&nbsp;-&nbsp;
                            <i class="icon-envelope"></i> <a class="dropdown_loginascustomer_link" href="{$lh.url|escape:'htmlall':'UTF-8'}"{$loginascustomer_new_tab|escape:'htmlall':'UTF-8'}>{$lh.email|escape:'htmlall':'UTF-8'}</a>
                            <div class="panel-heading-action" style="float:right;">
                                <a class="dropdown_loginascustomer_link" href="{$lh.url|escape:'htmlall':'UTF-8'}"{$loginascustomer_new_tab|escape:'htmlall':'UTF-8'}>
                                    <i class="icon-chevron-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        {/foreach}
                    {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
