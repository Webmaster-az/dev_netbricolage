{*
* 2017-2020 Profileo
*
*  @author    Profileo
*  @copyright 2017-2020 Profileo
*}

<div id="fieldset_0" class="panel top_container">
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<div class="row" id="module_name">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<img alt="{$display_name|escape:'htmlall':'UTF-8'}" src="{$logo_url|escape:'htmlall':'UTF-8'}">
				</div>
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<span class="title">{$display_name|escape:'htmlall':'UTF-8'}</span>
					<span class="version">{l s='Version' mod='eoqtypricediscount'} <em>{$version|escape:'htmlall':'UTF-8'}</em></span>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-5 col-sm-8 col-xs-12 hidden-xs">
			<div id="module_description">
				{$description|escape:'htmlall':'UTF-8'}
			</div>
		</div>
		<div
			class="col-lg-4 col-md-3 col-sm-8 col-xs-12 text-center hidden-xs">
			<div id="module_action">
				<a class="btn-cross btn-cross-default btn-cross-doc"
					title="{l s='Download documentation' mod='eoqtypricediscount'}"
					href="{$documentation_url|escape:'htmlall':'UTF-8'}"
					target="_blank"> {l s='Download documentation' mod='eoqtypricediscount'}
				</a> 
				{if isset($module_id)}
				<a class="btn-cross btn-cross-default btn-cross-support"
					title=" {l s='Contact support' mod='eoqtypricediscount'}"
					href="https://addons.prestashop.com/contact-community.php?id_product=20646"
					target="_blank"> {l s='Contact support' mod='eoqtypricediscount'} </a>
				{/if}
			</div>
		</div>
	</div>
</div>