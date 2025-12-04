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







<div id="block_payment_secure" class="col-md-3 links wrapper">



  <div class="pc-footerblock-inside">



    <p class="h3 myaccount-title hidden-sm-down">



      <a class="text-uppercase" href="/content/a-propos" rel="nofollow">



        À propos



      </a>



    </p>   



    <div class="title clearfix hidden-md-up" data-target="#footer_account_list_about" data-toggle="collapse">



      <span class="h3">À propos</span>



      <span class="float-xs-right">



        <span class="navbar-toggler collapse-icons">



          <i class="material-icons add">&#xE313;</i>



          <i class="material-icons remove">&#xE316;</i>



        </span>



      </span>



    </div>



    <ul class="pc-footerulblock account-list collapse" id="footer_account_list_about">



      <li>



        <a href="/promotions" title="Informations personnelles" rel="nofollow">



          Promotions



        </a>



      </li>



      <li>



        <a href="/content/livraison" title="Informations personnelles" rel="nofollow">



          Livraison



        </a>



      </li>



      <li>



        <a href="/content/mentions-legales" title="Informations personnelles" rel="nofollow">



          Mentions légales



        </a>



      </li>



      <li >



        <a href="/content/paiement-securise" title="Informations personnelles" rel="nofollow">



          Paiement sécurisé



        </a>



      </li>



      <li>



        <a href="/content/conditions-generale-vente" title="Informations personnelles" rel="nofollow">



          Conditions générale vente



        </a>



      </li>



      <li>



        <a href="/content/a-propos" title="Informations personnelles" rel="nofollow">



          A propos



        </a>



      </li>



      <li>



        <a href="/plan-site" title="Informations personnelles" rel="nofollow">



          Plan du site



        </a>



      </li>



    </ul>



  </div>



</div>







<div id="block_certifie" class="col-md-3 links wrapper">



  <div class="pc-footerblock-inside">



    <p class="h3 myaccount-title hidden-sm-down">



      <a class="text-uppercase" href="/content/a-propos" rel="nofollow">



        Certifié



      </a>



    </p>



    <div class="title clearfix hidden-md-up" data-target="#footer_account_list_certifie" data-toggle="collapse">



      <span class="h3">Certifié</span>



      <span class="float-xs-right">



        <span class="navbar-toggler collapse-icons">



          <i class="material-icons add">&#xE313;</i>



          <i class="material-icons remove">&#xE316;</i>



        </span>



      </span>



    </div>



    <ul class="account-list collapse" id="footer_account_list_certifie">



      <li>



        <a href="/content/a-propos" title="Informations personnelles" rel="nofollow">



          <img width="322" height="48" class="pc-fullwidth" src="{$urls.theme_assets}/img/footer/footerpmecertifie.png" alt="PME Certifie"/>



        </a>



      </li>      



    </ul>    







    <p class="h3 myaccount-title hidden-sm-down pc-livraisonp">



      <a class="newsletter-mainlink text-uppercase" href="/content/livraison" rel="nofollow">



        Livraison disponible par :



      </a>



    </p>



   <ul class="pc-footerflags account-list collapse" id="footer_account_list">



      <li>



        <a href="/content/livraison" title="Informations personnelles" rel="nofollow">



          <span title="France" class="flag-icon flag-icon-fr"></span>



          <span title="Belgique" class="flag-icon flag-icon-be"></span>



          <span title="Luxembourg" class="flag-icon flag-icon-lu"></span>



          <span title="Pays-Bas" class="flag-icon flag-icon-nl"></span>



          <span title="Espagne" class="flag-icon flag-icon-es"></span>



          <span title="Italie" class="flag-icon flag-icon-it"></span>



          <span title="Finland" class="flag-icon flag-icon-fi"></span>



          <span title="Portugal" class="flag-icon flag-icon-pt"></span>



        </a>



      </li>



    </ul>



  </div>



</div>







{foreach $linkBlocks as $linkBlock}



  <div class="col-md-6 wrapper">



    <p class="h3 hidden-sm-down">{$linkBlock.title}</p>



    {assign var=_expand_id value=10|mt_rand:100000}



    <div class="title clearfix hidden-md-up" data-target="#footer_sub_menu_{$_expand_id}" data-toggle="collapse">



      <span class="h3">{$linkBlock.title}</span>



      <span class="float-xs-right">



        <span class="navbar-toggler collapse-icons">



          <i class="material-icons add">&#xE313;</i>



          <i class="material-icons remove">&#xE316;</i>



        </span>



      </span>



    </div>



    <ul id="footer_sub_menu_{$_expand_id}" class="collapse">



      {foreach $linkBlock.links as $link}



        <li>



          <a



              id="{$link.id}-{$linkBlock.id}"



              class="{$link.class}"



              href="{$link.url}"



              title="{$link.description}"



              {if !empty($link.target)} target="{$link.target}" {/if}



          >



            {$link.title}



          </a>



        </li>



      {/foreach}



    </ul>



  </div>



{/foreach}