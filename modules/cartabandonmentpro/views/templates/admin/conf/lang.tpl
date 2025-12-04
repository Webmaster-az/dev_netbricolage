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
    <div class="alert alert-info" role="alert">
        {l s='If you send emails to French prospects, you have legal obligation to add the following CNIL mention (All informations on: ' mod='cartabandonmentpro'}
        <a href="http://www.cnil.fr/english" target="_blank">http://www.cnil.fr/english</a>)
    </div>
</div>

<div class="row form-inline">
    <div class="col-sm-2">
        {l s='Choose a language' mod='cartabandonmentpro'}
    </div>
    <div class="col-sm-2">
        <select name="language" id="language" class="form-control" onChange="changeLanguage();">
            {foreach from=$languages item=language}
                <option value="{$language.id_lang|intval}" {if $id_lang|intval == $language.id_lang|intval} selected="selected" {/if}>{$language.name|escape:'htmlall':'UTF-8'}</option>
            {/foreach}
        </select>
    </div>
</div>
