{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
<link href="{$urls.theme_assets}fonts/fontawesome/css/all.min.css" rel="stylesheet">
<link href="{$urls.theme_assets}fonts/lipis-flags/flag-icon.min.css" rel="stylesheet">
<link href="{$urls.theme_assets}libraries/lightslider/css/lightslider.css" rel="stylesheet">
<script type="text/javascript" src="{$urls.theme_assets}libraries/lightslider/js/lightslider.js" defer></script>
<script type="text/javascript" src="{$urls.theme_assets}libraries/lazyload/lazyload.min.js" defer></script>
{if $page.page_name == 'index' or $page.page_name == 'my-account'}
    <script type="text/javascript" src="{$urls.theme_assets}js/homepage_feat_prods.js" defer></script>
{/if}
<div class="container">
  <div class="row">
    <!-- Back to top button 
    <a id="button_gotop"></a>-->
   {* {block name='hook_footer_before'}
      {hook h='displayFooterBefore'}
    {/block} *}
  </div>
</div>
<div class="footer-container">
  <div class="container">
    <div class="row">
      {block name='hook_footer'}
        {hook h='displayFooter'}
      {/block}
    </div>
    <div class="row">
      {block name='hook_footer_after'}
        {hook h='displayFooterAfter'}
      {/block}
    </div>
    <div class="row">
      <div class="col-md-12 pc-copyrightblock" style="margin-bottom: 50px;">
        <p class="col-md-6 pc-copyright">
          {block name='copyright_link'}
            <a class="_blank" href="#" rel="nofollow">
              {l s='%copyright% %year% - Ecommerce software by %prestashop%' sprintf=['%prestashop%' => 'PrestaShop™', '%year%' => 'Y'|date, '%copyright%' => '©'] d='Shop.Theme.Global'}
            </a>
          {/block}
        </p>
        <p class="col-md-6 pc-copyright-payements">
          <a href="/content/paiement-securise" title="Paiement disponible" rel="nofollow">
            <img alt="Cheque" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/cheque.jpg"/>
            <img alt="Virement bancaire" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/virement-bancaire.jpg"/>
          {*  <img alt="Mandat cash" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/mandat-cash.jpg"/> *}
          {*  <img alt="CB" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/cb.jpg"/> *}
            <img alt="American Express" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/american-express.jpg"/>
            <img alt="Mastercard" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/mastercard.jpg"/>
            <img alt="VISA" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/visa.jpg"/>
          {*  <img alt="Paypal" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/paypal.jpg"/> *}
          {*  <img alt="Hipay" width="60" height="35" class="col-md-1" src="{$urls.theme_assets}/img/footer/payments/hipay.jpg"/>   *}          
          </a>
        </p>
      </div>
    </div>
    <div class="pc_respbotmenu">
      <a href="#" id="menu-icon">
        <i class="fas fa-bars"></i>
        <br />
        <span>Menu</span>
      </a>
      <a href="/">
        <i class="fas fa-home"></i>
        <br />
        <span>Accueil</span>
      </a>
      <a href="/mon-compte">
        <i class="fas fa-user-circle"></i>
        <br />
        <span>Compte</span>
      </a>
      <a href="/panier">
        <i class="fas fa-shopping-cart"></i>
        <br />
        <span>Panier</span>
      </a>
    </div>
  </div>
</div>
{* {block name='javascript_head'}
  {include file="_partials/javascript.tpl" javascript=$javascript.head vars=$js_custom_vars}
  <script type="text/javascript" src="{$urls.theme_assets}libraries/lightslider/js/lightslider.js" defer></script>
  {if $page.page_name == 'index' or $page.page_name == 'my-account'}
    <script type="text/javascript" src="{$urls.theme_assets}js/homepage_feat_prods.js" defer></script>
  {/if}  
{/block} *}
<script>
  $( document ).ready(function() {
    lazyload();
  });
</script>
