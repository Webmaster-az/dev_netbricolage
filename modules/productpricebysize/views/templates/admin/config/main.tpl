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
	<li class="nav-item active">
		<a class="nav-link" data-toggle="tab" href="#ppbs-dimensions-tab" role="tab">{l s='Dimensions' mod='productpricebysize'}</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#ppbs-units-tab" role="tab">{l s='Units' mod='productpricebysize'}</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#ppbs-areapricesuffixes-tab" role="tab">{l s='Area Price Suffixes' mod='productpricebysize'}</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#ppbs-translations-tab" role="tab">{l s='Translations' mod='productpricebysize'}</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#ppbs-equation-templates-tab" role="tab">{l s='Equation Templates' mod='productpricebysize'}</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#ppbs-global-options-tab" role="tab">{l s='Global Options' mod='productpricebysize'}</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#ppbs-mass-assign-tab" role="tab">{l s='Mass Assign' mod='productpricebysize'}</a>
	</li>

</ul>

<div class="tab-content">
	<div class="tab-pane active ppbs-config-tab" id="ppbs-dimensions-tab" role="tabpanel"></div>
	<div class="tab-pane ppbs-config-tab" id="ppbs-units-tab" role="tabpanel"></div>
	<div class="tab-pane ppbs-config-tab" id="ppbs-areapricesuffixes-tab" role="tabpanel"></div>
	<div class="tab-pane ppbs-config-tab" id="ppbs-translations-tab" role="tabpanel"></div>
	<div class="tab-pane ppbs-config-tab" id="ppbs-equation-templates-tab" role="tabpanel"></div>
	<div class="tab-pane ppbs-config-tab" id="ppbs-global-options-tab" role="tabpanel"></div>
	<div class="tab-pane ppbs-config-tab" id="ppbs-mass-assign-tab" role="tabpanel"></div>
</div>

<script>
	$(document).ready(function () {
        admin_url = '{$admin_url|escape:'quotes':'UTF-8'}';
		module_config_url = '{$module_config_url|escape:'quotes':'UTF-8'}';
        module_ajax_url_ppbs = "{$module_ajax_url_ppbs|escape:'quotes':'UTF-8' nofilter}";

		ppbs_dimensions_controller = new PPBSAdminConfigDimensionsController('#ppbs-dimensions-tab');
		ppbs_units_controller = new PPBSAdminConfigUnitsController('#ppbs-units-tab');
		ppbs_areapricesuffixes_controller = new PPBSAdminAreaPriceSuffixesController('#ppbs-areapricesuffixes-tab');
		ppbs_translations_controller = new PPBSAdminConfigTranslationsController('#ppbs-translations-tab');
		ppbs_equation_templates_controller = new PPBSAdminConfigEquationTemplatesController('#ppbs-equation-templates-tab');
		ppbs_global_options_controller = new PPBSAdminConfigGlobalOptionsController('#ppbs-global-options-tab');
		ppbs_mass_assign_controller = new PPBSAdminConfigMassAssignController('#ppbs-mass-assign-tab');
	});
</script>