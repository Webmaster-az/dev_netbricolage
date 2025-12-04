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



{extends file='customer/page.tpl'}





{block name='page_content'}





  <div class="row">

    <div class="account_leftcol">

      <div style="padding: 10px 15px;font-weight: bold;font-size: 16px;text-transform: uppercase;">

        {l s='Your account' d='Shop.Theme.Customeraccount'}

      </div>



      <ul class="links">

        <li>

          <a id="identity-link" href="{$urls.pages.identity}">

            <span class="link-item">

              <i class="material-icons">&#xE853;</i>

              <span>{l s='Information' d='Shop.Theme.Customeraccount'}</span>

            </span>

          </a>

        </li>



        {if $customer.addresses|count}

          <li>

            <a id="addresses-link" href="{$urls.pages.addresses}">

              <span class="link-item">

                <i class="material-icons">&#xE56A;</i>

                <span>{l s='Addresses' d='Shop.Theme.Customeraccount'}</span>

              </span>

            </a>

          </li>

        {else}

          <li>

            <a id="address-link" href="{$urls.pages.address}">

              <span class="link-item">

                <i class="material-icons">&#xE567;</i>

                <span>{l s='Add first address' d='Shop.Theme.Customeraccount'}</span>

              </span>

            </a>

          </li>

        {/if}



        {if !$configuration.is_catalog}

          <li>

            <a id="history-link" href="{$urls.pages.history}">

              <span class="link-item">

                <i class="material-icons">&#xE916;</i>

                <span>{l s='Order history and details' d='Shop.Theme.Customeraccount'}</span>

              </span>

            </a>

          </li>

        {/if}



        {if !$configuration.is_catalog}

          <li>

            <a id="order-slips-link" href="{$urls.pages.order_slip}">

              <span class="link-item">

                <i class="material-icons">&#xE8B0;</i>

                <span>{l s='Credit slips' d='Shop.Theme.Customeraccount'}</span>

              </span>

            </a>

          </li>

        {/if}



        {if $configuration.voucher_enabled && !$configuration.is_catalog}

          <li>

            <a id="discounts-link" href="{$urls.pages.discount}">

              <span class="link-item">

                <i class="material-icons">&#xE54E;</i>

                <span>{l s='Vouchers' d='Shop.Theme.Customeraccount'}</span>

              </span>

            </a>

          </li>

        {/if}



        {if $configuration.return_enabled && !$configuration.is_catalog}

          <li>

            <a id="returns-link" href="{$urls.pages.order_follow}">

              <span class="link-item">

                <i class="material-icons">&#xE860;</i>

                <span>{l s='Merchandise returns' d='Shop.Theme.Customeraccount'}</span>

              </span>

            </a>

          </li>

        {/if}



        {block name='display_customer_account'}

          {hook h='displayCustomerAccount'}

        {/block}



        <li>

          <a href="{$logout_url}" >

            <span style="color: #f55;" class="link-item">

              <i style="font-size: 21px;margin-right:5px;" class="fas fa-sign-out-alt material-icons"></i>

              <span>

                {l s='Sign out' d='Shop.Theme.Actions'}

              </span>

            </span>            

          </a>

        </li>

      </ul>

    </div>



    <div class="pc_accountcontentblock">

      <div>

        <div style="display: flex;color: #7a7a7a;align-items: center;">

          <div style="border: 1px solid #bfbfbf;border-radius: 50%;padding: 25px 28px;font-size: 52px;">

            <i class="fas fa-user"></i>

          </div>

          <div style="padding-left: 20px;">

            <div style="font-weight:bold;font-size: 20px;">

              {$customer.firstname} {$customer.lastname}

            </div>

            <div style="font-size: 14px;margin-top:10px;">

              Email: {$customer.email}

            </div>

          </div>

        </div>        

        <div class="account_usershortcuts">

          <div>

            {if $customer.addresses|count}

                <a id="addresses-link" href="{$urls.pages.addresses}">

                    <span class="link-item">

                        <i class="fas fa-map-marked-alt"></i>

                        <br/>

                        <span>{l s='Addresses' d='Shop.Theme.Customeraccount'}</span>

                    </span>

                </a>

            {else}

                <a id="address-link" href="{$urls.pages.address}">

                    <span class="link-item">

                        <i class="fas fa-map-marked-alt"></i>

                        <span>{l s='Add first address' d='Shop.Theme.Customeraccount'}</span>

                    </span>

                </a>

            {/if}

          </div>



          <div>

            {if $configuration.voucher_enabled && !$configuration.is_catalog}              

              <a id="discounts-link" href="{$urls.pages.discount}">

                <span class="link-item">

                  <i class="material-icons">&#xE54E;</i>

                  <br/>

                  <span>Reductions</span>

                </span>

              </a>

            {/if}

          </div>



          <div>

            {if !$configuration.is_catalog}

              <a id="history-link" href="{$urls.pages.history}">

                <span class="link-item">

                  <i class="material-icons">&#xE916;</i>

                  <br/>

                  <span>Commandes</span>

                </span>

              </a>

            {/if}

          </div>



          <div>

            <a id="identity-link" href="{$urls.pages.identity}">

              <span class="link-item">

                <i class="material-icons">&#xE853;</i>

                <br/>

                <span>{l s='Information' d='Shop.Theme.Customeraccount'}</span>

              </span>

            </a>

          </div>

        </div>

      </div>



      <section style="margin-top:25px;" class="featured-products clearfix home_cats">

        <h2 class="h2 products-section-title text-uppercase">

          Categories

        </h2>



        <div id="home_cats_slider" class="home_cats_slider" style="display:block;">

          <div class="jsls_block" style="width:20%;padding:0px 7.5px;float:left;">

            <a href="/maison-jardin/fumisterie-inox/">

              <img style="width:100%;height:auto;" class="pc_mainimgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/fumisterie.png" alt="Fumisterie inox" />

              <img style="width:100%;height:auto;" class="pc_imgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/fumisterie_2.png" alt="Fumisterie inox" />



              <span class="home_cats_title">

                Fumisterie inox

                <i class="fas fa-chevron-right" style="padding-left:10px;"></i>

              </span>

            </a>

          </div>



          <div class="jsls_block" style="width:20%;padding:0px 7.5px;float:left;">

            <a href="/maison-jardin/chauffage-cuisinieres/poele-a-bois/">

              <img style="width:100%;height:auto;" class="pc_mainimgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/poeles.png" alt="Poêle à bois" />

              <img style="width:100%;height:auto;" class="pc_imgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/poeles_2.png" alt="Poêle à bois"/>



              <span class="home_cats_title">

                Poêle à bois

                <i class="fas fa-chevron-right" style="padding-left:10px;"></i>

              </span>

            </a>

          </div>



          <div class="jsls_block" style="width:20%;padding:0px 7.5px;float:left;">

            <a href="/maison-jardin/coffrage-perdu/">

              <img style="width:100%;height:auto;" class="pc_mainimgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/cofrage.png" alt="Coffrage perdu" />

              <img style="width:100%;height:auto;" class="pc_imgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/cofrage_2.png" alt="Coffrage perdu" />



              <span class="home_cats_title">

                Coffrage perdu

                <i class="fas fa-chevron-right" style="padding-left:10px;"></i>

              </span>

            </a>

          </div>



          <div class="jsls_block" style="width:20%;padding:0px 7.5px;float:left;">

            <a href="/plomberie-electricite/tuyau-polyethylene-pehd/">

              <img style="width:100%;height:auto;" class="pc_mainimgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/tuyaupehd.png" alt="Tuyau PEHD" />

              <img style="width:100%;height:auto;" class="pc_imgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/tuyaupehd_2.png" alt="Tuyau PEHD" />



              <span class="home_cats_title">

                Tuyau PEHD

                <i class="fas fa-chevron-right" style="padding-left:10px;"></i>

              </span>

            </a>

          </div>



          <div class="jsls_block" style="width:20%;padding:0px 7.5px;float:left;">

            <a href="/plomberie-electricite/cuisine/">

              <img style="width:100%;height:auto;" class="pc_mainimgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/cuisine.png" alt="Cuisine" />

              <img style="width:100%;height:auto;" class="pc_imgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/cuisine_2.png" alt="Cuisine" />



              <span class="home_cats_title">

                Cuisine

                <i class="fas fa-chevron-right" style="padding-left:10px;"></i>

              </span>

            </a>

          </div>



          <div class="jsls_block" style="width:20%;padding:0px 7.5px;float:left;">

            <a href="/plomberie-electricite/salle-de-bain-douches/">

              <img style="width:100%;height:auto;" class="pc_mainimgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/douche.png" alt="Salle de baine" />

              <img style="width:100%;height:auto;" class="pc_imgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/douche_2.png" alt="Salle de baine" />



              <span class="home_cats_title">

                Salle de baine

                <i class="fas fa-chevron-right" style="padding-left:10px;"></i>

              </span>

            </a>

          </div>



          <div class="jsls_block" style="width:20%;padding:0px 7.5px;float:left;">

            <a href="/outillage/">

              <img style="width:100%;height:auto;" class="pc_mainimgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/outillage.png" alt="Outillage" />

              <img style="width:100%;height:auto;" class="pc_imgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/outillage_2.png" alt="Outillage" />



              <span class="home_cats_title">

                Outillage

                <i class="fas fa-chevron-right" style="padding-left:10px;"></i>

              </span>

            </a>

          </div>



          <div class="jsls_block" style="width:20%;padding:0px 7.5px;float:left;">

            <a href="/quincaillerie-consommables/colles-silicones/">

              <img style="width:100%;height:auto;" class="pc_mainimgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/silicones.png" alt="Colles et silicones" />

              <img style="width:100%;height:auto;" class="pc_imgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/silicones_2.png" alt="Colles et silicones"/>



              <span class="home_cats_title">

                Colles & silicones

                <i class="fas fa-chevron-right" style="padding-left:10px;"></i>

              </span>

            </a>

          </div>



          <div class="jsls_block" style="width:20%;padding:0px 7.5px;float:left;">

            <a href="/maison-jardin/fumisterie-inox/sortie-toit-pipeco/">

              <img style="width:100%;height:auto;" class="pc_mainimgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/pipeco.png" alt="Pipeco" />

              <img style="width:100%;height:auto;" class="pc_imgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/pipeco_2.png" alt="Pipeco" />



              <span class="home_cats_title">

                Pipeco

                <i class="fas fa-chevron-right" style="padding-left:10px;"></i>

              </span>

            </a>

          </div>

        </div>

      </section>

    </div>

  </div>

{/block}