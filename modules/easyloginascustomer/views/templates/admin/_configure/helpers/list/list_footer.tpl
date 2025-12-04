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
{extends file="helpers/list/list_footer.tpl"}
{block name="after"}
    {if isset($easyloginascustomer_credits)}
    <div class="bootstrap panel">
        <h3><i class="icon-key"></i> {l s='Credits' mod='easyloginascustomer'}</h3>
        <p>{l s='Thanks to:' mod='easyloginascustomer'}</p>
        <ul style="list-style-type:circle; margin-left:20px;">
            <li>
                <span style="font-weight:bold;">{l s='Prestateam' mod='easyloginascustomer'}</span>
                {l s='for the best e-commerce solution.' mod='easyloginascustomer'}
            </li>
            <li>
                <span style="font-weight:bold;">{l s='You' mod='easyloginascustomer'}</span>
                {l s='to use this module' mod='easyloginascustomer'} :)
            </li>
        </ul>
        <hr style="width:100%;" />
        <p style="font-weight:bold;">Easy Login as Customer v{$easyloginascustomer_version|escape:'htmlall':'UTF-8'}</p>
        <p>{l s='Created by' mod='easyloginascustomer'} R.Combe</p>
    </div>
    {/if}
{/block}
