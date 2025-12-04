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

<div class="row">
	<div id="ppbs-equation-templates-list" class="col-sm-6">
		<h4>{l s='Equation Templates' mod='productpricebysize'}</h4>
		<table class="table">
			<thead>
			<tr>
				<th>{l s='Name' mod='productpricebysize'}</th>
				<th>{l s='Action' mod='productpricebysize'}</th>
			</tr>
			</thead>
			<tbody>
				{foreach from=$equation_templates item=equation_template}
				<tr data-id="{$equation_template.id_equation_template|escape:'html':'UTF-8'}" data-equation="{$equation_template.equation|escape:'html':'UTF-8'}">
					<td style="width: 70%" valign="top">
						{$equation_template.name|escape:'html':'UTF-8'}
						<div class="equation" style="padding:10px 0; display: none">
							{$equation_template.equation|escape:'html':'UTF-8'}
						</div>
					</td>
					<td style="width: 30%" valign="top">
						<i class="ppbs-equation-template-view material-icons" title="{l s='View' mod='productpricebysize'}" style="cursor: pointer;">remove_red_eye</i>
						<i class="ppbs-equation-template-delete material-icons" title="{l s='Delete' mod='productpricebysize'}" style="cursor: pointer;" data-id_equation_template="{$equation_template.id_equation_template|escape:'html':'UTF-8'}">delete forever</i>
					</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
	</div>

    <div id="ppbs-equation-vars" class="col-sm-6">
        <h3>{l s='Global Equation Variables' mod='productpricebysize'}</h3>
        <h4 style="font-weight: bold">{l s='Add new variable' mod='productpricebysize'}</h4>
        <div id="ppbs-equation-vars-new">
            <div>
                <input type="text" name="name" placeholder="{l s='Variable Name' mod='productpricebysize'}">
            </div>
            <div>
                <input type="number" name="value" class="form-control" placeholder="{l s='Variable Value' mod='productpricebysize'}">
            </div>
            <div>
                <button style="width: 100%;" class="btn-primary btn btn-add">{l s='Add' mod='productpricebysize'}</button>
            </div>
        </div>

        <h4 style="font-weight: bold">{l s='Variables' mod='productpricebysize'}</h4>
        <div class="ppbs-equation-vars-list">

        </div>
    </div>

</div>