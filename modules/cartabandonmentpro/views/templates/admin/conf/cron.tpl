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

<div class="row">
    <div class="col-md-12">
        {* {if $warning neq false}
            {$warning}
        {/if} *}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="alert alert-info" role="alert">
              {l s='In order to send automatic reminders, you need to set up a cron job, which is a process that allows to schedule regular tasks.' mod='cartabandonmentpro'}<br>
            </div>
        </div>
        <br>
        <p>
            <b><big>
            {l s='Configuration of the module is almost complete ! Choose one of the 3 options below to activate reminders and start sending mails to your clients who have abandoned carts.' mod='cartabandonmentpro'}
            </big></b>
        </p>
    </div>
</div>
<br>
<div class="row">
    <h2>1 - {l s='Automatic reminders (Use external free services)' mod='cartabandonmentpro'}</h2>
    {l s='We recommend you to use an external free services like ' mod='cartabandonmentpro'}<a href="https://www.easycron.com" target="_blank">www.easycron.com</a>{l s=' to set up your automatic reminders. You need to create three tasks, one task for each following URLs :' mod='cartabandonmentpro'}<br><br>
    <div style="margin-left: 20px;margin-bottom: 10px;">
        {l s='First reminder:' mod='cartabandonmentpro'} <a href="{$front_ca_url|escape:'quotes':'UTF-8'}wich_remind=1&token={$token_send|escape:'htmlall':'UTF-8'}&action=cron&id_shop={$id_shop|intval}" target="_blank">{$front_ca_url|escape:'quotes':'UTF-8'}wich_remind=1&token={$token_send|escape:'htmlall':'UTF-8'}&action=cron&id_shop={$id_shop|intval}</a><br>
        {l s='Second reminder:' mod='cartabandonmentpro'} <a href="{$front_ca_url|escape:'quotes':'UTF-8'}wich_remind=2&token={$token_send|escape:'htmlall':'UTF-8'}&action=cron&id_shop={$id_shop|intval}" target="_blank">{$front_ca_url|escape:'quotes':'UTF-8'}wich_remind=2&token={$token_send|escape:'htmlall':'UTF-8'}&action=cron&id_shop={$id_shop|intval}</a><br>
        {l s='Third reminder:' mod='cartabandonmentpro'} <a href="{$front_ca_url|escape:'quotes':'UTF-8'}wich_remind=3&token={$token_send|escape:'htmlall':'UTF-8'}&action=cron&id_shop={$id_shop|intval}" target="_blank">{$front_ca_url|escape:'quotes':'UTF-8'}wich_remind=3&token={$token_send|escape:'htmlall':'UTF-8'}&action=cron&id_shop={$id_shop|intval}</a><br>
    </div>
    {l s='You should set up your tasks to be executed every hour.' mod='cartabandonmentpro'}
    <a href="https://www.easycron.com/cron-job-tutorials/how-to-set-up-cron-job-for-prestashop-abandoned-cart-reminder" target="_blank">{l s='You can find a tutorial here (in english)' mod='cartabandonmentpro'}</a>

    <h2>2 - {l s='Manual reminders' mod='cartabandonmentpro'}</h2>
    {l s='You can also send manual reminders, to do so, you should enter the urls in your browser (urls mentioned in the first part).' mod='cartabandonmentpro'}<br><br>
</div>
