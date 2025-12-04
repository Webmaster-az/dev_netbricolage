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
 <section class="featured-products clearfix">
 <h2 class="h2 products-section-title text-uppercase">
   {l s='Popular Products' d='Shop.Theme.Catalog'}
 </h2>
 {include file="catalog/_partials/productlist.tpl" products=$products cssClass="row"}
 <a class="all-product-link float-xs-left float-md-right h4" href="{$allProductsLink}">
   <span style="padding-top: 3px;padding-right: 5px;float: left;">Voir tous les produits</span>
   <i class="material-icons">&#xE315;</i>
 </a>
</section>
<section class="featured-products">
 <h2 class="h2 products-section-title text-uppercase">
   Les incontournables de la saison
 </h2>
 <div class="col-xs-12 p-0">
   <div class="col-md-6 col-xs-12 p-0 pr-7-5" style="padding-bottom: 15px;">
     <a class="col-xs-12 p-0" href="/maison-jardin/terrasse/barbecue-bbq-et-accessoires/" title="Barbecue">
       <img style="width:100%;height:auto;" width="692" height="494" class="lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/barbecue-fr.jpg" alt="Barbecue" />
     </a>
     <a class="col-xs-12 p-0 pt-15" href="/plomberie-electricite/reseau-vrd/" title="Reseau VRD">
       <img style="width:100%;height:auto;" width="692" height="240" class="lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/reseauvrd.webp" alt="Reseau VRD" />
     </a>
   </div>
   <div class="col-md-6 col-xs-12 p-0 pl-7-5">
     <a class="col-xs-6 p-0" style="padding-right: 7.5px;" href="/maison-jardin/chauffage-poele-a-bois/poele-a-bois/" title="Poele à bois">
       <img style="width:100%;height:auto;" class="main_img lazyload" width="329" height="240" data-src="/themes/classic/modules/ps_featuredproducts/views/img/panadero.jpg" alt="Panadero" />
     </a>
     <a class="col-xs-6 p-0" style="padding-left: 7.5px;" href="/maison-jardin/fumisterie-inox/sortie-toit-pipeco/" title="PIPECO">
       <img style="width:100%;height:auto;" class="main_img lazyload" width="329" height="240" data-src="/themes/classic/modules/ps_featuredproducts/views/img/pipeco4.jpg" alt="PIPECO" />
     </a>
     <a class="col-xs-12 p-0 pt-15" href="/maison-jardin/terrasse/voile-d-ombrage-filet-protection-pehd/" title="Voiles d'ombrage">
       <img style="width:100%;height:auto;" class="lazyload" width="692" height="494" data-src="/themes/classic/modules/ps_featuredproducts/views/img/rede.jpg" alt="Voiles d'ombrage" />
     </a>   
   </div>
 </div>
</section>
<section style="margin-top:25px;display:-ms-inline-grid;display:inline-grid" class="featured-products clearfix home_cats">
 <h2 class="h2 products-section-title text-uppercase">
   Categories
 </h2>
 <div id="home_cats_slider" class="home_cats_slider" style="display:block;">
   <div class="jsls_block" >
     <a href="/maison-jardin/fumisterie-inox/">
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_mainimgswap_home" src="/themes/classic/modules/ps_featuredproducts/views/img/categories/fumisterie.webp" alt="Fumisterie inox" />
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_imgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/fumisterie_2.webp" alt="Fumisterie inox" />
       <span class="home_cats_title">
         Fumisterie inox
         <i class="fas fa-chevron-right" style="padding-left:10px;"></i>
       </span>
     </a>
   </div>
   <div class="jsls_block" >
     <a href="/maison-jardin/chauffage-cuisinieres/poele-a-bois/">
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_mainimgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/poeles.webp" alt="Poêle à bois" />
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_imgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/poeles_2.webp" alt="Poêle à bois"/>
       <span class="home_cats_title">
         Poêle à bois
         <i class="fas fa-chevron-right" style="padding-left:10px;"></i>
       </span>
     </a>
   </div>
   <div class="jsls_block" >
     <a href="/maison-jardin/coffrage-perdu/">
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_mainimgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/cofrage.webp" alt="Coffrage perdu" />
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_imgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/cofrage_2.webp" alt="Coffrage perdu" />
       <span class="home_cats_title">
         Coffrage perdu
         <i class="fas fa-chevron-right" style="padding-left:10px;"></i>
       </span>
     </a>
   </div>
   <div class="jsls_block" >
     <a href="/plomberie-electricite/tuyau-polyethylene-pehd/">
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_mainimgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/tuyaupehd.webp" alt="Tuyau PEHD" />
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_imgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/tuyaupehd_2.webp" alt="Tuyau PEHD" />
       <span class="home_cats_title">
         Tuyau PEHD
         <i class="fas fa-chevron-right" style="padding-left:10px;"></i>
       </span>
     </a>
   </div>
   <div class="jsls_block" >
     <a href="/plomberie-electricite/cuisine/">
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_mainimgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/cuisine.webp" alt="Cuisine" />
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_imgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/cuisine_2.webp" alt="Cuisine" />
       <span class="home_cats_title">
         Cuisine
         <i class="fas fa-chevron-right" style="padding-left:10px;"></i>
       </span>
     </a>
   </div>
   <div class="jsls_block" >
     <a href="/plomberie-electricite/salle-de-bain-douches/">
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_mainimgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/douche.webp" alt="Salle de baine" />
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_imgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/douche_2.webp" alt="Salle de baine" />
       <span class="home_cats_title">
         Salle de baine
         <i class="fas fa-chevron-right" style="padding-left:10px;"></i>
       </span>
     </a>
   </div>
   <div class="jsls_block" >
     <a href="/outillage/">
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_mainimgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/outillage.webp" alt="Outillage" />
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_imgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/outillage_2.webp" alt="Outillage" />
       <span class="home_cats_title">
         Outillage
         <i class="fas fa-chevron-right" style="padding-left:10px;"></i>
       </span>
     </a>
   </div>
   <div class="jsls_block" >
     <a href="/quincaillerie-consommables/colles-silicones/">
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_mainimgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/silicones.webp" alt="Colles et silicones" />
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_imgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/silicones_2.webp" alt="Colles et silicones"/>
       <span class="home_cats_title">
         Colles & silicones
         <i class="fas fa-chevron-right" style="padding-left:10px;"></i>
       </span>
     </a>
   </div>
   <div class="jsls_block" >
     <a href="/maison-jardin/fumisterie-inox/sortie-toit-pipeco/">
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_mainimgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/pipeco.webp" alt="Pipeco" />
       <img style="width:100%;height:auto;" width="260" height="260" class="pc_imgswap_home lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/categories/pipeco_2.webp" alt="Pipeco" />
       <span class="home_cats_title">
         Pipeco
         <i class="fas fa-chevron-right" style="padding-left:10px;"></i>
       </span>
     </a>
   </div>
 </div>
</section>
<section style="margin-top:20px;" class="featured-products">
 <div class="homepg_destaqueimgs">
   <a href="/notre-selection/" style="position: relative;">
     <img style="width:100%;height:auto;" width="1400" height="250" class="lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/nos_offres.webp" alt="Nos offres" />
     <span class="offers_spn">
       Nos offres du moment
     </span>
   </a>
 </div>
</section>
<section style="margin-top:25px;"  class="featured-products clearfix">
 <h2 class="h2 products-section-title text-uppercase">
   Promotions
 </h2>
 {block name='page_content'}
   {$HOOK_HOME nofilter}
   {assign var='HOOK_HOME_TAB_CONTENT' value=Hook::exec('displayHomeTabContent')}
   {$HOOK_HOME_TAB_CONTENT nofilter}
 {/block}
 <a class="all-product-link float-xs-left float-md-right h4" href="/promotions">
   <span style="padding-top: 3px;padding-right: 5px;float: left;">Voir tous les promotions</span>
   <i class="material-icons">&#xE315;</i>
 </a>
</section>
<section class="featured-products destockage">
 <div class="homepg_destaqueimgs">
   <a href="/destockage/" style="position: relative;">
     <img style="width:100%;height:auto;" width="1360" height="110" class="lazyload" data-src="/themes/classic/modules/ps_featuredproducts/views/img/destockage.webp" alt="Destockage" />
   </a>
 </div>
</section>