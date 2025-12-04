{*
* 2007-2017 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="row cron_alert">
    <div class="alert alert-info" role="alert" style="{if $carts1 eq 0}display:none;{/if}">
        <strong>{l s='Reminder:' mod='cartabandonmentpro'}</strong>{l s='email will be send automatically only if you have configured the automatic reminders in the "Reminders activation" tab' mod='cartabandonmentpro'}
    </div>
</div>
<div class="row newletter_alert" style="{if $newsletter eq 1}display:none;{/if}">
    <div class="alert alert-info" role="alert">
        <strong>{l s='Important:' mod='cartabandonmentpro'}</strong>{l s='The newsletter option is set to off. Those who didn\'t subsribed to the newsletter will not receive any remind.' mod='cartabandonmentpro'}
    </div>
</div>
<div class="row">
    {if $first_reminder_active|intval eq 1}
        <div class="panel">
            <div class="panel-heading"><i class="icon-list"></i> {l s='Carts for first reminder' mod='cartabandonmentpro'}</div>
            {if empty($carts1)}
                <div class="alert alert-info" role="alert">
                    {l s='No abandoned carts yet. Come back later!' mod='cartabandonmentpro'}
                </div>
            {else}
                <table class="table table-striped" style="border: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>{l s='Id cart' mod='cartabandonmentpro'}</th>
                            <th>{l s='Firstname' mod='cartabandonmentpro'}</th>
                            <th>{l s='Lastname' mod='cartabandonmentpro'}</th>
                            <th>{l s='Email' mod='cartabandonmentpro'}</th>
                            <th>{l s='Date' mod='cartabandonmentpro'}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$carts1 item=cart}
                            <tr>
                                <td>{$cart.id_cart|intval}</td>
                                <td>{$cart.firstname|escape:'htmlall':'UTF-8'}</td>
                                <td>{$cart.lastname|escape:'htmlall':'UTF-8'}</td>
                                <td>{$cart.email|escape:'htmlall':'UTF-8'}</td>
                                <td>{$cart.date_upd|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            {/if}
        </div>
    {/if}

    {if $second_reminder_active|intval eq 1}
        <div class="panel">
            <div class="panel-heading"><i class="icon-list"></i> {l s='Carts for second reminder' mod='cartabandonmentpro'}</div>
            {if empty($carts2)}
                <div class="alert alert-info" role="alert">
                    {l s='No abandoned carts yet. Come back later!' mod='cartabandonmentpro'}
                </div>
            {else}
                <table class="table table-striped" style="border: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>{l s='Id cart' mod='cartabandonmentpro'}</th>
                            <th>{l s='Firstname' mod='cartabandonmentpro'}</th>
                            <th>{l s='Lastname' mod='cartabandonmentpro'}</th>
                            <th>{l s='Email' mod='cartabandonmentpro'}</th>
                            <th>{l s='Date' mod='cartabandonmentpro'}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$carts2 item=cart}
                            <tr>
                                <td>{$cart.id_cart|intval}</td>
                                <td>{$cart.firstname|escape:'htmlall':'UTF-8'}</td>
                                <td>{$cart.lastname|escape:'htmlall':'UTF-8'}</td>
                                <td>{$cart.email|escape:'htmlall':'UTF-8'}</td>
                                <td>{$cart.date_upd|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            {/if}
        </div>
    {/if}

    {if $third_reminder_active|intval eq 1}
        <div class="panel">
            <div class="panel-heading"><i class="icon-list"></i> {l s='Carts for third reminder' mod='cartabandonmentpro'}</div>
            {if empty($carts3)}
                <div class="alert alert-info" role="alert">
                    {l s='No abandoned carts yet. Come back later!' mod='cartabandonmentpro'}
                </div>
            {else}
                <table class="table table-striped" style="border: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>{l s='Id cart' mod='cartabandonmentpro'}</th>
                            <th>{l s='Firstname' mod='cartabandonmentpro'}</th>
                            <th>{l s='Lastname' mod='cartabandonmentpro'}</th>
                            <th>{l s='Email' mod='cartabandonmentpro'}</th>
                            <th>{l s='Date' mod='cartabandonmentpro'}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$carts3 item=cart}
                            <tr>
                                <td>{$cart.id_cart|intval}</td>
                                <td>{$cart.firstname|escape:'htmlall':'UTF-8'}</td>
                                <td>{$cart.lastname|escape:'htmlall':'UTF-8'}</td>
                                <td>{$cart.email|escape:'htmlall':'UTF-8'}</td>
                                <td>{$cart.date_upd|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            {/if}
        </div>
    {/if}

    <div class="panel">
        <div class="panel-heading"><i class="icon-bar-chart"></i> {l s='Mails statistics' mod='cartabandonmentpro'}</div>
        <table class="table table-striped" style="border: 0; width: 100%;">
            <thead>
                <tr>
                    <th>{l s='Mails sent' mod='cartabandonmentpro'}</th>
                    <th>{l s='Mails opened' mod='cartabandonmentpro'}</th>
                    <th>{l s='Mails clicked' mod='cartabandonmentpro'}</th>
                    <th>{l s='Unsubscribe' mod='cartabandonmentpro'}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{$stats.0.count|intval}</td>
                    <td>{$stats.0.view|intval}</td>
                    <td>{$stats.0.click|intval}</td>
                    <td>{$unsubscribe|intval}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {if $reminded_carts}
        <div class="panel">
            <div class="panel-heading"><i class="icon-paper-plane-o"></i> {l s='Latest emails sent (25 max)' mod='cartabandonmentpro'}</div>
            <table class="table table-striped" style="border: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>{l s='ID Cart' mod='cartabandonmentpro'}</th>
                        <th>{l s='Email' mod='cartabandonmentpro'}</th>
                        <th>{l s='Opened' mod='cartabandonmentpro'}</th>
                        <th>{l s='Clicked' mod='cartabandonmentpro'}</th>
                        <th>{l s='Unsubscribed' mod='cartabandonmentpro'}</th>
                        <th>{l s='Date' mod='cartabandonmentpro'}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$reminded_carts item=cart}
                        <tr>
                            <td>{$cart.id_cart|intval}</td>
                            <td>{$cart.email|escape:'htmlall':'UTF-8'}</td>
                            <td>{if $cart.visualize}{l s='Yes' mod='cartabandonmentpro'}{else}{l s='No' mod='cartabandonmentpro'}{/if}</td>
                            <td>{if $cart.click_cart or $cart.click}{l s='Yes' mod='cartabandonmentpro'}{else}{l s='No' mod='cartabandonmentpro'}{/if}</td>
                            <td>{if $cart.unsubscribed}{l s='Yes' mod='cartabandonmentpro'}{else}{l s='No' mod='cartabandonmentpro'}{/if}</td>
                            <td>{dateFormat date=$cart.send_date}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    {/if}

</div>
