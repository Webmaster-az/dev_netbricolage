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

<p>
    <h2>{l s='Thanks to Cart Abandonment Pro module' mod='cartabandonmentpro'}</h2>
    <br>
    <ul style="line-height:200%">
        <li>
            {l s='Target all of some of your customers and automatically send them one or more reminders about the contents of their cart in Target and Frequencies.' mod='cartabandonmentpro'}
        </li>
        <li>
            {l s='Configure different personalized emails tailored to match your online store\'s theme and remind your customers about the contents of their cart in Email templates tab.' mod='cartabandonmentpro'}
        </li>
        <li>
            {l s='Create custom discounts (percentage, currency, free shipping) with specific text for the email in Discounts tab.' mod='cartabandonmentpro'}
        </li>
        <li>
            {l s='Activate automatic reminders in the dedicated tab to start sending emails to your clients (to be used when you have finished configuring your emails).' mod='cartabandonmentpro'}
        </li>
    </ul>
</p>
<br><br>
<p>
    {l s='Attached you will find the module\'s documentation. Don\'t hesitate to read it in order to configure the module.' mod='cartabandonmentpro'}
    {if $iso_lang_doc|escape:'htmlall':'UTF-8' eq 'fr'}
        <a href="../modules/cartabandonmentpro/docs/Doc_panier_abandonne_FR.pdf" target="_blank">
    {elseif $iso_lang_doc|escape:'htmlall':'UTF-8' eq 'es'}
        <a href="../modules/cartabandonmentpro/docs/Doc_carrito_abandonado_ES.pdf" target="_blank">
    {elseif $iso_lang_doc|escape:'htmlall':'UTF-8' eq 'it'}
        <a href="../modules/cartabandonmentpro/docs/Doc_cart_abandoned_IT.pdf" target="_blank">
    {elseif $iso_lang_doc|escape:'htmlall':'UTF-8' eq 'de'}
        <a href="../modules/cartabandonmentpro/docs/Doc_abandoned_cart_DE.pdf" target="_blank">
    {else}
        <a href="../modules/cartabandonmentpro/docs/Doc_cart_abandoned_EN.pdf" target="_blank">
    {/if}
    <img src="../modules/cartabandonmentpro/img/pdf.png"></a>
</p>
<p>
    {l s='Access to Prestashops free documentation:' mod='cartabandonmentpro'} <a href="http://doc.prestashop.com/dashboard.action" target="_blank">http://doc.prestashop.com/dashboard.action</a>
</p>
