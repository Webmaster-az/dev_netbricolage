{*
* Easy login as Customer
*
* NOTICE OF LICENSE
*
*    @author    Remy Combe <remy.combe@gmail.com>
*    @copyright 2013-2019 Remy Combe
*    @license   go to addons.prestastore.com (buy one module for one shop).
*    @for    PrestaShop version 1.7
*}

<div class="card">
    <h3 class="card-header">
        <i class="material-icons">exit_to_app</i>
        {l s='Login as Customer' mod='easyloginascustomer'}
    </h3>
    <div class="card-body">
        <div class="row mb-1">
            <div class="col-12">
                <a href="{$loginascustomer_url|escape:'html'}" class="btn btn-default" {$loginascustomer_new_tab|escape:'htmlall':'UTF-8'}>
                    {l s='Login as' mod='easyloginascustomer'} {$loginascustomer_name|escape:'htmlall':'UTF-8'}
                </a>
            </div>
        </div>
    </div>
</div>
