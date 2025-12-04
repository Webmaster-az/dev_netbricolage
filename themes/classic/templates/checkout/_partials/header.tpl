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

{block name='header'}

  {block name='header_nav'}

    <div id="auth-page-header" class="pc-checkout-header">

      <a class="pc-btn-goback" href="{$urls.base_url}">

        <i class="fa fa-arrow-left"></i> Retour

      </a>

      <a class="pc-checkout-logo" href="{$urls.base_url}">

        <img class="logo img-responsive" src="{$shop.logo}" alt="{$shop.name}">

      </a>

      <a class="pc-checkout-contactblock" href="{$urls.base_url}">

        <div>Contactez-nous</div>

        <div>

          <i class="fa fa-phone"></i>  0 811 560 973 | PRIX APPEL 0,06 €/min

        </div>

      </a>

    </div>



    <style>

      body#checkout {

        background-color: #f5f6f7;

      }

    </style>    

  {/block}



  {block name='header_top'}

    <div class="header-top hidden-md-up">

      <div class="container">

         <div class="row">

          <div class="col-sm-12">

            <div class="row">

              {hook h='displayTop'}

              <div class="clearfix"></div>

            </div>

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

  {/block}

{/block}

