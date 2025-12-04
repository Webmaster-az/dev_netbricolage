{*
* 2017-2020 Profileo
*
*  @author    Profileo
*  @copyright 2017-2020 Profileo
*}

<div id="fieldset_0" class="panel bottom_cross_selling">
	<div id="bottom_container" class="container">
    	<div class="row">
    		<div id="profileo_logo" class="col-lg-5 col-md-5 col-sm-12">
    			<div class="col-lg-6 col-md-12">
    				<img width="194" height="65" alt="Profileo"
    					src="{$img_path|escape:'htmlall':'UTF-8'}logo_profileo.png">
    			</div>
    			<div class="col-lg-6">
    				<p class="title">{l s='100% PrestaShop expertise' mod='eoqtypricediscount'}</p>
    			</div>
    		</div>
    		<div class="col-lg-5 col-md-5 col-sm-8">
    			<div class="row reassurance_row">
    				<div class="col-xs-3 text-center">
    					<img width="39" height="41" alt="{l s='Maintenance' mod='eoqtypricediscount'}" title="{l s='Maintenance' mod='eoqtypricediscount'}"
    						src="{$img_path|escape:'htmlall':'UTF-8'}icone_maintenance.png"> <span class="hidden-xs">{l s='Maintenance' mod='eoqtypricediscount'}</span>
    				</div>
    				<div class="col-xs-3 text-center">
    					<img width="39" height="41" alt="{l s='Agency' mod='eoqtypricediscount'}" title="{l s='Agency' mod='eoqtypricediscount'}" 
    						src="{$img_path|escape:'htmlall':'UTF-8'}icone_agence.png"> <span class="hidden-xs">{l s='Agency' mod='eoqtypricediscount'}</span>
    				</div>
    				<div class="col-xs-3 text-center">
    					<img width="39" height="41" alt="{l s='Optimisations' mod='eoqtypricediscount'}" title="{l s='Optimisations' mod='eoqtypricediscount'}"
    						src="{$img_path|escape:'htmlall':'UTF-8'}icone_optimisation.png"> <span class="hidden-xs">{l s='Optimisations' mod='eoqtypricediscount'}</span>
    				</div>
    				<div class="col-xs-3 text-center">
    					<img width="39" height="41" alt="{l s='Hosting' mod='eoqtypricediscount'}" title="{l s='Hosting' mod='eoqtypricediscount'}"
    						src="{$img_path|escape:'htmlall':'UTF-8'}icone_hebergement.png"> <span class="hidden-xs">{l s='Hosting' mod='eoqtypricediscount'}</span>
    				</div>
    			</div>
    		</div>
    		<div class="col-lg-2 col-md-2 col-sm-4 text-center platinum_partner">
    			<img width="115" height="92" alt="{l s='Prestashop platinum partner' mod='eoqtypricediscount'}" title="{l s='Prestashop platinum partner' mod='eoqtypricediscount'}"
    				src="{$img_path|escape:'htmlall':'UTF-8'}macaron_platinium.png">
    		</div>
    	</div>
    	<div class="footer_items">
        	<div class="col-md-12"> 
        		<span class="title_block">
        			{l s='Check out our other modules' mod='eoqtypricediscount'}
        		</span>
        	</div>
        	<div class="col-sm-9">
            	<div class="row cross_selling" id="module_list">
            		{assign var="i" value=0}
            		{foreach from=$cross_selling item=product name=cross_selling}
            			<div class="col-sm-3">
            				<div class="row">
                				<div class="col-md-3 col-sm-12 col-xs-5 module_img">
                					<img class="img-responsive" src="{$product->img|escape:'htmlall':'UTF-8'}" alt="{$product->displayName|escape:'htmlall':'UTF-8'}" title="{$product->displayName|escape:'htmlall':'UTF-8'}">
                				</div>
                				<div class="col-md-9 col-sm-12 col-xs-7 module_name">
                					<a href="{$product->url|escape:'htmlall':'UTF-8'}" target="_blank">{$product->displayName|escape:'htmlall':'UTF-8'}</a>
                					<span>
                        				{$product->price->EUR|escape:'htmlall':'UTF-8'} &euro;
            						</span>  
                				</div>
                				<div class="spacer"></div>
                				<div class="col-md-12 module_desc">
                					{$product->description|escape:'htmlall':'UTF-8'}
                					<a class="module_link" href="{$product->url|escape:'htmlall':'UTF-8'}" target="_blank">{l s='See this module' mod='eoqtypricediscount'}</a>
                				</div>
                    		</div>
                		</div>
                		
                		{if $i == 3}
                			<div class="spacerCrossSelling"></div>
            			{/if}
            			{assign var=i value=$i+1}
            		{/foreach}
        		</div>
        	</div>
        	<div id="addons_area" class="col-sm-3 text-center">
                 {l s='Also discover' mod='eoqtypricediscount'}:
                 <a class="btn btn-warning" title="All our modules" target="_blank" href="http://addons.prestashop.com/{$lang_iso|escape:'htmlall':'UTF-8'}/15_profileo">{l s='All our modules' mod='eoqtypricediscount'}:</a>
            </div>
            <div class="spacer"></div>
        </div>
    </div>
</div>
