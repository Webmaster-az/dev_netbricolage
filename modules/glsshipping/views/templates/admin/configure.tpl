{*
* 2007-2019 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel">
	<h3><i class="icon icon-truck"></i> {l s='Gestión de envíos GLS' mod='glsshipping'}</h3>
	<img src="{$module_dir|escape:'html':'UTF-8'}/logo.png" id="payment-logo" class="pull-right" />
	<p>&nbsp;<br />&nbsp;
	</p>
</div>
<script>
	jQuery(document).ready(function(){
		var selectedCarriers = [];
		
		jQuery("#fieldset_2_2 select").each(function(){
        jQuery("option:selected",this).each(function(){            
            selectedCarriers.push(jQuery(this).val());
        });
		})	
//		console.log('sc: '+selectedCarriers.join(','));
		jQuery("#fieldset_2_2 option").each(function(){
			if(!jQuery(this).is(':selected')){
							
			if (jQuery.inArray(jQuery(this).val()+"",selectedCarriers) >= 0){
				jQuery(this).prop('disabled', 'disabled');
//				console.log('ok: '+jQuery(this).val());
			} else {
				jQuery(this).removeAttr('disabled');
//				console.log('ko: '+jQuery(this).val());
			}
			
			} 
		})
		
		
		jQuery("#fieldset_2_2 select").change(function(){
		
		var selectedCarriers = [];
		
		jQuery("#fieldset_2_2 select").each(function(){
        jQuery("option:selected",this).each(function(){            
            selectedCarriers.push(jQuery(this).val());
        });
		})	
//		console.log('sc: '+selectedCarriers.join(','));
		jQuery("#fieldset_2_2 option").each(function(){
			if(!jQuery(this).is(':selected')){
							
			if (jQuery.inArray(jQuery(this).val()+"",selectedCarriers) >= 0){
				jQuery(this).prop('disabled', 'disabled');
//				console.log('ok: '+jQuery(this).val());
			} else {
				jQuery(this).removeAttr('disabled');
//				console.log('ko: '+jQuery(this).val());
			}
			
			} 
		})
		
		
		});		
		
		
	})
</script>

<!--<div class="panel">
	<h3><i class="icon icon-tags"></i> {l s='Documentation' mod='glsshipping'}</h3>
	<p>
		&raquo; {l s='You can get a PDF documentation to configure this module' mod='glsshipping'} :
		<ul>
			<li><a href="#" target="_blank">{l s='English' mod='glsshipping'}</a></li>
			<li><a href="#" target="_blank">{l s='French' mod='glsshipping'}</a></li>
		</ul>
	</p>
</div>-->
