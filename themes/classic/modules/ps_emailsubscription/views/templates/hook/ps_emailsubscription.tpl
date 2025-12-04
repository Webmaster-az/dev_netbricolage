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
<style>
  .newsletter-mainlink i {
    padding-left: 0.5rem;
    color: #f39d72;
  }
  .ui-tooltip {
    border:none;
    border-radius:0px;
    color:#333;
    box-shadow:none;
    font-size:12px;
    font-weight:normal;
  }
  .contact-button {
    display: inline-block;
    background-color: #ef5f23;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
    text-transform: uppercase;
  }
  .contact-button:hover {
    background-color: #d94e1f;
  }
</style>
<div id="block_newsletterfooter" class="col-md-3 links wrapper">
  <div class="pc-block-newsletterinside">
    <p class="h3 myaccount-title hidden-sm-down">
      <a class="newsletter-mainlink text-uppercase" href="#" rel="nofollow">
        Lettre d'informations 
      </a>
      {if $conditions}      
        <i type="button" class="btn btn-secondary fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="{$conditions}"></i>
      {/if}
     </p>
    <div class="title clearfix hidden-md-up" data-target="#footer_account_list_newsletter" data-toggle="collapse">
      <span class="h3">Lettre d'informations </span>
       <span class="float-xs-right">
        <span class="navbar-toggler collapse-icons">
          <i class="material-icons add">&#xE313;</i>
          <i class="material-icons remove">&#xE316;</i>
        </span>
      </span>
    </div>
    <ul class="account-list collapse" id="footer_account_list_newsletter">
      <li>
        <div class="input-wrapper">
          <input class="pc-fullwidth" name="email" type="email" value="{$value}" placeholder="{l s='Your email address' d='Shop.Forms.Labels'}" aria-labelledby="block-newsletter-label" required>
          <input class="btn btn-primary float-xs-right hidden-xs-down" name="submitNewsletter" type="submit" value="{l s='Ok' d='Shop.Theme.Actions'}">
          <input type="hidden" name="blockHookName" value="{$hookName}" />
          <input type="hidden" name="action" value="0">
        </div>
        <div class="col-xs-12">
          {if $msg}
            <p class="alert {if $nw_error}alert-danger{else}alert-success{/if}">
              {$msg}
            </p>
          {/if}
          {if isset($id_module)}
            {hook h='displayGDPRConsent' id_module=$id_module}
          {/if}
        </div>
      </li>
    </ul>
    <p class="pc-footer-social h3 myaccount-title hidden-sm-down">
      <a class="newsletter-mainlink text-uppercase" href="#" rel="nofollow">
        Social
      </a>
    </p>
    <div class="pc-social-content account-list collapse" id="footer_account_list">
      <a href="http://facebook.com/netbricolage" title="Informations personnelles" rel="nofollow">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="https://twitter.com/netbricolage" title="Informations personnelles" rel="nofollow">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="https://www.instagram.com/" title="Informations personnelles" rel="nofollow">
        <i class="fab fa-instagram"></i>
      </a>
    </div>
    
    <p class="pc-footer-social h3 myaccount-title hidden-sm-down">
      <a class="contact-button" href="https://www.netbricolage.com/nous-contacter" rel="nofollow">
        Contactez-nous
      </a>
    </p>
  </div>
</div>

