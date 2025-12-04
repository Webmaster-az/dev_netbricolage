{*

 * 2013-2021 MADEF IT

 *

 * NOTICE OF LICENSE

 *

 * This source file is subject to the Academic Free License (AFL 3.0)

 * that is bundled with this package in the file LICENSE.txt.

 * It is also available through the world-wide-web at this URL:

 * http://opensource.org/licenses/afl-3.0.php

 * If you did not receive a copy of the license and are unable to

 * obtain it through the world-wide-web, please send an email

 * to contact@madef.fr so we can send you a copy immediately.

 *

 * DISCLAIMER

 *

 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer

 * versions in the future. If you wish to customize PrestaShop for your

 * needs please refer to http://www.prestashop.com for more information.

 *

 *  @author    MADEF IT <contact@madef.fr>

 *  @copyright 2013-2021 MADEF IT

 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)

*}



<div class="rm-overlay rm-overlay--close js-rm-trigger {if $rm_display_close}rm-display-close{/if}">

</div>



<div class="rm-pannel rm-pannel--close {if $rm_display_bar_login}rm-display-login{/if} {if $rm_display_bar_search}rm-display-search{/if}">    

    <div class="rm-container" id="rm-container">

        {$currentCategoryHtml nofilter}



        <ul class="mobile_menutoplinks" style="margin-top:10px;width:100%;position: absolute;bottom: 0;">

            <li class="rm-login-bar js-rm-column">
                <!--
                {if Context::getContext()->customer->isLogged() }

                    <a class="rm-login-bar__login" href="/?mylogout">

                        <i style="padding-right:10px;" class="fas fa-sign-out-alt"></i> Déconnecter

                    </a>               

                {/if}
                -->
            </li>

        </ul>

    </div>

</div>