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
    <div id="alertSaveCron" class="alert alert-success" style="display: none;">
        {l s='Configuration saved' mod='cartabandonmentpro'}
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
    <h2>{l s='TARGET' mod='cartabandonmentpro'}</h2>
    <div class="row">
        <div class="col-md-5">
            <strong>{l s='Do you want to remind customers that didn\'t subscribe to the newsletter?' mod='cartabandonmentpro'}</strong>
        </div>
        <div class="col-md-2">
            <span class="switch prestashop-switch fixed-width-lg">
                <input onclick="setNewsletter('{$token|escape:'htmlall':'UTF-8'}', {$id_shop|intval}, 1);" type="radio" name="active" id="newsletter_on" value="1" {if $newsletter|intval eq 1}checked="checked"{/if}>
                <label for="newsletter_on" class="radioCheck">
                    {l s='Yes' mod='cartabandonmentpro'}
                </label>
                <input onclick="setNewsletter('{$token|escape:'htmlall':'UTF-8'}', {$id_shop|intval}, 0);" type="radio" name="active" id="newsletter_off" value="0" {if $newsletter|intval eq 0}checked="checked"{/if}>
                <label for="newsletter_off" class="radioCheck">
                    {l s='No' mod='cartabandonmentpro'}
                </label>
                <a class="slide-button btn"></a>
            </span>
        </div>
    </div>
    <div class="row newletter_alert" style="{if $newsletter|intval eq 1}display:none;{/if}">
        <div class="alert alert-warning" role="alert">
            <strong>{l s='Important:' mod='cartabandonmentpro'}</strong>{l s='The newsletter option is set to off. Those who didn\'t subsribed to the newsletter will not receive any remind.' mod='cartabandonmentpro'}
        </div>
    </div>
    {if $iso_lang eq 'fr'}
        <div class="row">
            <div class="col-xs-12">
                {l s='Learn more about legal obligations and restrictions in sending emails to your customers:' mod='cartabandonmentpro'}<a href="http://www.cnil.fr/fileadmin/documents/Marketing/Commerce_et_Donnees_Personnelles.pdf" target="_blank">{l s='Commerce et données personelles' mod='cartabandonmentpro'}</a>
            </div>
        </div>
    {/if}
</div>
<div class="row" style="margin-top: 25px;">
    <h2>{l s='REMINDERS FREQUENCIES' mod='cartabandonmentpro'}</h2>
    {include file="../conf/reminders.tpl"}
</div>

<div class="panel-footer">
    <button type="submit" name="submitCron" id="submitCron" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='cartabandonmentpro'}</button>
</div>
