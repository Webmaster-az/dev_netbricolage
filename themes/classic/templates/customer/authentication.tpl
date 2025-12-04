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
     
{extends file='page.tpl'}

{block name='page_title'}
  {l s='Bon retour!' d='Shop.Theme.Customeraccount'} &#x1F44B;
{/block}

{block name='page_content'}
  {block name='login_form_container'}
    <section class="login-form">
      {render file='customer/_partials/login-form.tpl' ui=$login_form}        
    </section>

    <section id="panc_createaccount" class="login-form">
      <div class="pc-register-insidebox">
        <div class="login-lbl">
          Je suis un nouveau client
        </div>
        
        <div class="pc-aligncenter">
          N'attendez plus !
        </div>
        
        <div class="pc-register-text">
          <div>
            C'est un plaisir de vous rencontrer! Nous sommes Net Bricolage. Souhaitez-vous créer un compte ?
          </div>

          <ul class="pc-register-advantages">
            <li>
              <i class="fas fa-circle"></i> 
              <span>Conseils personnalisés pour vos projets</span>
            </li>
            <li>
              <i class="fas fa-circle"></i>
              <span>Vos listes accessibles à tout moment</span>
            </li>

            <li>
              <i class="fas fa-circle"></i>
              <span>Suivi en direct de vos commandes</span>
            </li>
          </ul>
        </div>

        <div class="pc-register-button">
          <a href="{$urls.pages.register}" data-link-action="display-register-form">
            <i class="fas fa-user-plus"></i> <span>S'inscrire</span> 
          </a>
        </div>
      </div>
    </section>

    <section style="display:none;" class="proaccountinfo">
      <div>
        <h3>
          Bienvenue au espace 
          <span style="background-color: #fdb330;color: white;padding: 0px 10px;margin-left: 10px;border-radius: 10px;">PRO</span>
        </h3>
        <p>
          Pourquoi créer un compte dans l’espace pro ? 
        </p>
        <p>
          Grâce à votre espace pro, vous pourrez :
        </p>
        <ul style="list-style: inherit;padding-left: 2rem;">
          <li>
            Avec l’Espace pro, vous gagnez du temps en accédant à tout instant à vos données tarifaires Professionnelles en H.T.
          </li>
          <li>
            Bénéficier de tarifs personnalisés
          </li>
          <li>
            Retrouver vos commandes passées
          </li>
          <li>
            Retrouver en quelques clics le produit recherché
          </li>
          <li>
            Créer votre liste d’articles favoris
          </li>
        </ul>
        <p>
          Il a été pensé pour vous, et il vous fera gagner du temps.
        </p>
        <p>
          Cliquez, vous verrez !
        </p>
        <p>
          Votre espace pro* : souplesse, facilité, confort
        </p>
        <small>
          *Service réservé à la clientèle professionnelle
        </small>
      </div>
      <div>
        <img style="width:100%;" src="/img/responsive_showcase.jpg" />
      </div>
    </section>

    <hr/>

    {block name='display_after_login_form'}
      {hook h='displayCustomerLoginFormAfter'}
    {/block}      
  {/block}
{/block}