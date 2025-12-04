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



<div class="col-4 col-sm-3 col-md-2" id="_desktop_cart" style="margin-left: auto; position: relative; float: none; display: flex; align-items: center;justify-content: flex-end;">



  <div class="blockcart cart-preview {if $cart.products_count > 0}active{else}inactive{/if}" data-refresh-url="{$refresh_url}" >



    <div class="header">



        <a class="pc_carturl" rel="nofollow" href="{$cart_url}">



          <img src="/img/icones/checkout_7710429.png" alt="Cart" style="width:25px; height:auto;">



          <span class="hidden-sm-down pc-headercartspn">{l s='Cart' d='Shop.Theme.Checkout'}</span>



          <span class="cart-products-count">{$cart.products_count}</span>     



        </a>



    </div> 



    {if $cart.products_count > 0}



      <div class="ht_cart cart-hover-content">



          <div class="cart_hover_resume">



            <span class="pc_carthover_quantity">



              <img src="/img/icones/checkout_7710429.png" alt="Cart" style="width:25px; height:auto;">



              <span class="cart-products-count">{$cart.products_count}</span>



            </span>



            <span class="pc_hovertotalval">



              Total: {$cart.totals.total.value} HT



            </span>



          </div>







          <hr>



          



          <ul>



              {foreach from=$cart.products item=product}



                  <li class="cart-wishlist-item">



                      {include 'module:ps_shoppingcart/ps_shoppingcart-product-line.tpl' product=$product}



                  </li>



              {/foreach}



          </ul>







          <hr>







          {*<div class="cart-summary">



               <div class="cart-subtotals">



                  {foreach from=$cart.subtotals item="subtotal"}



                      <div class="{$subtotal.type}">



                          <span class="label">{$subtotal.label}</span>



                          <span class="value">{$subtotal.value}</span>



                      </div>



                  {/foreach}



              </div>



              <div class="cart-total">



                  <span class="label">{$cart.totals.total.label}</span>



                  <span class="value">{$cart.totals.total.value}</span>



              </div> 



          </div>*}



          <div class="cart-action">



              <a class="cart-wishlist-viewcart" href="{$cart_url}">Voir Panier</a>



              {* <a class="cart-wishlist-checkout" href="{$urls.pages.order}">{l s='Check Out' d='Shop.Theme.Actions'}</a> *}



          </div>



      </div>



    {/if}



  </div>



</div>