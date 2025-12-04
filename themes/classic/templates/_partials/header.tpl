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



{block name='header_banner'}

  <div class="header-banner">

    {hook h='displayBanner'}

  </div>

{/block}

{block name='header_nav'}  

  <nav class="header-nav">

    <div class="container">

      <div class="row">

        {if !Context::getContext()->isMobile()}

          <div class="hidden-sm-down pc-displayflex">

            <div class="col-md-6 col-xs-12 pc-displayflex">
              <h1 class="pc-navslogan">

                Net-bricolage N°1 magasin de bricolage online à prix pas cher                

              </h1>
            </div>



            <div class="col-md-6 right-nav">

              <a href="/nous-contacter" class="pc-navmail contact-button">
                Contactez-nous
              </a>



              <a href="/nous-contacter" class="pc-navphone">

                <img src="/img/icones/phone_2099226_1.png" alt"Contactez-nous" style="width:15px; height:auto; margin-right:8px;">
                <span class="call-num">0 811 560 973</span>
                <span class="call-cost">PRIX APPEL 0,06 €/min</span>

              </a>

            </div>

          </div>

        {/if}



        <div class="hidden-md-up text-sm-center mobile pc-mobileheader">

          

          {if $page.page_name == 'index'}

            <div style="padding: 15px;cursor: pointer;" class="float-xs-left" id="menu-icon_header">

              <i style="font-size: 30px;" class="material-icons d-inline">&#xE5D2;</i>

            </div>

          {else}

            <div onclick="history.back()" style="padding: 15px;cursor: pointer;" class="float-xs-left" id="header_goback">

              <i style="font-size: 30px;" class="fas fa-arrow-left"></i>

            </div>

          {/if}



          <div class="top-logo" id="_mobile_logo"></div>



          <div id="pull_searchmobile">

            <i class="fas fa-search"></i>

          </div>



          <div class="float-xs-right" id="_mobile_search">

            <!-- Block search module TOP -->

            <div id="search_widget" class="search-widget col-md-4" data-search-controller-url="{$search_controller_url}">	

              <div id="mobile_searchclose" style="cursor:pointer;color:red;padding:11px 15px;right:10px;top:10px;position:absolute;background-color: white;font-size: 26px;">

                <i class="fas fa-times"></i>

              </div>



              <form method="get" action="{$search_controller_url}">

                <div class="pc_headerlivraisoninfo">

                  <i style="padding-right:10px;" class="fas fa-truck"></i>

                  Livraison gratuite à partir de 300€ HT

                </div>



                <input type="hidden" name="controller" value="search">

                <input type="text" name="s" value="{$search_string}" placeholder="{l s='Search our catalog' d='Shop.Theme.Catalog'}" aria-label="{l s='Search' d='Shop.Theme.Catalog'}">



                <button type="submit">

                  <i class="material-icons search">&#xE8B6;</i>

                  <span class="hidden-xl-down">{l s='Search' d='Shop.Theme.Catalog'}</span>

                </button>

              </form>

            </div>

            <!-- /Block search module TOP -->

          </div>

          {* <div class="float-xs-right" id="_mobile_cart"></div> *}

        </div>

      </div>

    </div>

  </nav>

{/block}



{block name='header_top'}

  <div class="header-top">

    <div class="container">

       <div class="row">        

        <div class="col-md-12 col-sm-12 position-static">

          <div class="pc-displayflex">

            <div class="col-md-4  hidden-sm-down align-items-center justify-content-end" style="gap: 15px;" id="_desktop_logo">

              {if $page.page_name == 'index'}

                <p class="pc-nomargin">                

                  {if $customer.id_default_group == '4'}

                    <a href="{$urls.base_url}">

                      <img class="logo img-responsive" width="350" height="80" src="/img/net-bricolage-logo-pro.jpg" alt="{$shop.name}">

                    </a>

                  {else}

                    <a href="{$urls.base_url}">

                      <img class="logo img-responsive" width="350" height="80" src="{$shop.logo}" alt="{$shop.name}">

                    </a>

                  {/if}

                </p>

              {else}

                {if $customer.id_default_group == '4'}

                  <a href="{$urls.base_url}">

                    <img class="logo img-responsive" width="350" height="80" src="/img/net-bricolage-logo-pro.jpg" alt="{$shop.name}">

                  </a>

                {else}

                  <a href="{$urls.base_url}">

                    <img class="logo img-responsive" width="350" height="80" src="{$shop.logo}" alt="{$shop.name}">

                  </a>

                {/if}

              {/if}

            </div>
           {hook h='displayTop'}
     
          </div>

          

          {hook h="displayIqitMenu"} <!-- Display menu module custom hook -->

        </div>

      </div>



      <div id="mobile_top_menu_wrapper" class="row hidden-md-up" style="display:none;">

        <div class="js-top-menu mobile" id="_mobile_top_menu"></div>

        <div class="js-top-menu-bottom">

          <div id="_mobile_currency_selector"></div>

          <div id="_mobile_language_selector"></div>

          <div id="_mobile_contact_link"></div>

        </div>

      </div>

    </div>

  </div>



  {hook h='displayNavFullWidth'}



  {if $page.page_name == "authentication"}

    <div id="auth-page-header" class="pc-loginpage-header">

      <a class="pc-btn-goback" href="{$urls.base_url}">

        <i class="fas fa-arrow-left"></i> Retour

      </a>



      <a class="pc-checkout-logo" href="{$urls.base_url}">

        <img class="logo img-responsive" src="{$shop.logo}" alt="{$shop.name}">

      </a>

    </div>

  {/if}

{/block}