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

<table class="table table-striped" style="border: 0; width: 100%;">
    <thead>
        <tr>
            <th>{l s='Language' mod='cartabandonmentpro'}</th>
            <th>{l s='Email subject' mod='cartabandonmentpro'}</th>
            <th>{l s='Reminder' mod='cartabandonmentpro'}</th>
            <th>{l s='Shared template' mod='cartabandonmentpro'}</th>
            <th>{l s='Visualize' mod='cartabandonmentpro'}</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$templates item=template}
            <tr class="tpl_list_{$template.wich_remind|intval} tpl_list">
                <td>{$template.lang_name|escape:'htmlall':'UTF-8'}</td>
                <td>{if $template.tpl_same|intval eq 1} {$templates.0.template_name|escape:'htmlall':'UTF-8'} {else} {$template.template_name|escape:'htmlall':'UTF-8'} {/if}</td>
                <td>{$template.wich_remind|intval}</td>
                <td>{if $template.tpl_same|intval eq 1}Oui{else}Non{/if}</td>
                <td><img src="{$module_dir|escape:'quotes':'UTF-8'}views/img/eye.png" style="cursor:hand;cursor:pointer;" onClick="previewTemplate({$template.id_template|intval}, {$template.wich_remind|intval})"></td>
            </tr>
        {/foreach}
    </tbody>
<br>
</table>
<div class="row">
    <div class="col-xs-12">
        <h4>{l s='Send a test' mod='cartabandonmentpro'}</h4>
    </div>
</div>
<div class="row">
    <div class="col-xs-2">
        <div class="input-group col-xs-8">
            <input type="text" class="form-control" id="test_amount" name="test_amount" placeholder="{l s='Amount' mod='cartabandonmentpro'}">
            <span class="input-group-addon">{$currency|escape:'htmlall':'UTF-8'}</span>
        </div>
    </div>
    <div class="col-xs-3">
        <input type="text" class="form-control" id="test_mail" name="test_mail" placeholder="{l s='Email' mod='cartabandonmentpro'}">
    </div>
    <div class="col-xs-4">
        <button class="btn btn-primary" onClick="mailTest({$id_lang|intval}, {$id_shop|intval}, '{$token|escape:'htmlall':'UTF-8'}');return false;">{l s='Send' mod='cartabandonmentpro'}</button>
    </div>
</div>
