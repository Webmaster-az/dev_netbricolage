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



<div class="col-md-4" id="_desktop_user_info">

  <div class="user-info" style="display: flex; gap: 20px; align-items: center;">

    <!--style="display: flex; gap: 20px; align-items: center;"-->

    {if $logged}

      <a class="account" href="{$my_account_url}" {* data-toggle="dropdown" aria-expanded="false" *}
        title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow">

        <img src="/img/icones/user_1077114_1.png" alt="Connexion" style="width:25px; height:auto;">

        <span class="hidden-sm-down">{$customerName}</span>

      </a>



      {* <div class="dropdown-menu dropdown-menu-right">

        <a href="{$urls.pages.identity}" class="dropdown-item" type="button"><i class="fas fa-user"></i> Informations</a>

        <a href="{$urls.pages.addresses}" class="dropdown-item" type="button"><i class="fas fa-map-marker-alt"></i> Adresses</a>

        <a href="{$urls.pages.history}" class="dropdown-item" type="button"><i class="far fa-calendar-alt"></i> Historique commandes</a>

        <a href="{$urls.pages.discount}" class="dropdown-item" type="button"><i class="fas fa-tag"></i> Bons de réduction</a>

        <a href="{$my_account_url}" class="dropdown-item" type="button"><i class="fas fa-cogs"></i> Voir tout les options</a>

        <a href="{$logout_url}" class="dropdown-item" type="button"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>

      </div>*}

    {else}

      <a href="/connexion?pc-compte-pro" title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow">

        <img src="/img/icones/group_681494_1.png" alt="Espace PRO" style="width:25px; height:auto;">

        <span class="hidden-sm-down">ESPACE<span
            style="background-color: #ef5f23;color: white;padding: 0px 10px;margin-left: 5px;border-radius: 10px;">PRO</span></span>

      </a>



      <a href="{$my_account_url}" title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow">

        <img src="/img/icones/user_1077114_1.png" alt="Connexion" style="width:25px; height:auto;">

        <span class="hidden-sm-down">{l s='Sign in' d='Shop.Theme.Actions'}</span>

      </a>





    {/if}

  </div>

</div>