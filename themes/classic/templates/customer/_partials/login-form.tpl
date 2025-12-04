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

{block name='login_form'}
  {block name='login_form_errors'}
    {include file='_partials/form-errors.tpl' errors=$errors['']}
  {/block}

  <form id="login-form" action="{block name='login_form_actionurl'}{$action}{/block}" method="post">
    <div class="login-lbl">
      Déjà client | Se connecter
    </div>

    <section class="pc-loginsection">
      {block name='login_form_fields'}
        {foreach from=$formFields item="field"}
          {block name='form_field'}
            {form_field field=$field}
          {/block}
        {/foreach}
      {/block}

      <div class="forgot-password">
        <a href="{$urls.pages.password}" rel="nofollow">
          {l s='Forgot your password?' d='Shop.Theme.Customeraccount'} 
        </a>
      </div>
    </section>

    {block name='login_form_footer'}
      <footer class="form-footer text-sm-center clearfix">
        <input type="hidden" name="submitLogin" value="1">
        
        {block name='form_buttons'}
          <button id="submit-login" class="btn btn-primary" data-link-action="sign-in" type="submit" class="form-control-submit">
            <i class="fas fa-sign-in-alt"></i> {l s='Sign in' d='Shop.Theme.Actions'}
          </button>
        {/block}

        <div id="pc-register-button" class="pc-register-button" style="margin-top:1rem;padding:0px;display:none;">
          <a href="{$urls.pages.register}?pc-compte-pro" data-link-action="display-register-form">
            <i class="fas fa-user-plus"></i> <span>S'inscrire sur espace <span style="background-color: #fdb330;color: white;padding: 0px 10px;margin-left: 10px;border-radius: 10px;">PRO</span> </span> 
          </a>
        </div>
      </footer>

      <small id="pc_clientprosmall" style="display:none;float: left;padding: 1rem;"> *Service réservé à la clientèle professionnelle </small>
    {/block}
  </form>
  
  {if $page.page_name != "checkout"}
    <div id="box-loginespaceprobtn" style="margin-top: 1rem;">
      <div class="login-lbl">
        Déjà client profissionel | Se connecter 
        <span style="background-color: #fdb330;color: white;padding: 0px 10px;margin-left: 10px;border-radius: 10px;">PRO</span>
      </div>
      
      <div id="pc-register-buttonright" class="pc-register-button" style="margin-top:1rem;">
        <a style="max-width: 400px;margin: 0px auto;" href="{$urls.pages.register}?pc-compte-pro" data-link-action="display-register-form">
          <i class="fas fa-user-plus"></i> <span>Visiter espace <span style="background-color: #fdb330;color: white;padding: 0px 10px;margin-left: 10px;border-radius: 10px;">PRO</span> </span> 
        </a>
      </div>
      <div class="pc-prolabelblock" style="max-width: 400px;margin: 0px auto;padding: 0.5rem 0px 2rem;">
        <p>
          Avec l’Espace pro, vous gagnez du temps en accédant à tout instant à vos données tarifaires Professionnelles en H.T.
        </p>
      </div>
    </div>
  {/if}

  <div id="box-normal_login" style="background-color:white; margin-top: 1rem;display:none;padding-bottom: 2rem;">
    <div class="login-lbl">
      Client particulier | Se connecter
    </div>
    
    <div id="pc-register-buttonright" class="pc-register-button" style="margin-top:1rem;">
      <a style="max-width: 400px;margin: 0px auto;" href="/mon-compte" data-link-action="display-register-form">
        <i class="fas fa-user-plus"></i> <span>Connexion </span> 
      </a>      
    </div>
    <small id="pc_clientprosmall" style="display: block;  padding: 1rem 1rem 0px 1rem;width: 100%;">
      *Service dédié aux particuliers
    </small>
  </div>
{/block}