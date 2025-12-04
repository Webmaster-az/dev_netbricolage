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
*  @copyright  2007-2021 Musaffar Patel
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Property of Musaffar Patel
*}

<ul class="nav nav-tabs" id="myTab" role="tablist">
	<li class="nav-item">
		<a class="nav-link active" data-toggle="tab" href="#ppbs-general-tab" role="tab">
            <i class="material-icons">settings</i>
            {l s='General Options' mod='productpricebysize'}
        </a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#ppbs-fields-tab" role="tab">
            <i class="material-icons">dns</i>
            {l s='Fields' mod='productpricebysize'}
        </a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#ppbs-areaprices-tab" role="tab">
            <i class="material-icons">straighten</i>
            {l s='Area Based Pricing' mod='productpricebysize'}
        </a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#ppbs-equation-tab" role="tab">
            <i class="material-icons">calculate</i>
            {l s='Custom Calculations' mod='productpricebysize'}
        </a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#ppbs-weight-calculations-tab" role="tab">
            <i class="material-icons">local_shipping</i>
            {l s='Weight Calculations' mod='productpricebysize'}
        </a>
	</li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#ppbs-stockmanagement-tab" role="tab">
            <i class="material-icons">fact_check</i>
            {l s='Stock Management' mod='productpricebysize'}
        </a>
    </li>

</ul>

<div class="tab-content">
	<div class="tab-pane active" id="ppbs-general-tab" role="tabpanel"></div>
	<div class="tab-pane" id="ppbs-fields-tab" role="tabpanel"></div>
	<div class="tab-pane" id="ppbs-areaprices-tab" role="tabpanel"></div>
	<div class="tab-pane" id="ppbs-equation-tab" role="tabpanel"></div>
    <div class="tab-pane" id="ppbs-weight-calculations-tab" role="tabpanel"></div>
    <div class="tab-pane" id="ppbs-stockmanagement-tab" role="tabpanel"></div>
</div>

<script>
	$(document).ready(function () {
        module_ajax_url_ppbs = "{$module_ajax_url|escape:'quotes':'UTF-8' nofilter}";
		id_product = '{$id_product|escape:'quotes':'UTF-8'}';
		id_shop = '{$id_shop|escape:'quotes':'UTF-8'}';

		ppbs_admin_producttab_general_controller = new PPBSAdminProductTabGeneralController("#ppbs-general-tab");
		ppbs_admin_producttab_fields_controller = new PPBSAdminProductTabFieldsController("#ppbs-fields-tab");
		ppbs_admin_producttab_areaprices_controller = new PPBSAdminProductTabAreaPricesController("#ppbs-areaprices-tab");
		ppbs_admin_producttab_equation_controller = new PPBSAdminProductTabEquationController("#ppbs-equation-tab");
        ppbs_admin_producttab_stockmanagement_controller = new PPBSAdminProductTabStockManagementController("#ppbs-stockmanagement-tab");
        ppbs_admin_producttab_weight_calculations_controller = new PPBSAdminProductTabWeightCalculationsController("#ppbs-weight-calculations-tab");
	});
</script>