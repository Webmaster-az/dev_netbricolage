{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author Musaffar Patel <musaffar.patel@gmail.com>
*  @copyright  2015-2021 Musaffar Patel
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Property of Musaffar Patel
*}

<div id="hook-ppbs-widget"></div>

<script>
    ppbs = {
        id_currency : "{$id_currency|escape:'html':'UTF-8'}"
    };
    ppbs_enabled = {$pbbs_enabled|escape:'quotes':'UTF-8' nofilter};

    {if ($action eq 'quickview')}
        $(document).ready(function () {
            module_ajax_url_ppbs = "{$module_ajax_url|escape:'quotes':'UTF-8' nofilter}";
            ppbs_front_product_controller = new PPBSFrontProductController('#ppbs_widget', '', true);
        });
    {else}
        document.addEventListener("DOMContentLoaded", function (event) {
            $(function () {
                module_ajax_url_ppbs = "{$module_ajax_url|escape:'quotes':'UTF-8' nofilter}";
                ppbs_front_product_controller = new PPBSFrontProductController('#ppbs_widget', '', false);
            });
        });
    {/if}
</script>