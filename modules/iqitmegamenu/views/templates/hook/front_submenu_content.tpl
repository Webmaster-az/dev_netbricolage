{*
* 2007-2016 IQIT-COMMERCE.COM
*
* NOTICE OF LICENSE
*
*  @author    IQIT-COMMERCE.COM <support@iqit-commerce.com>
*  @copyright 2007-2016 IQIT-COMMERCE.COM
*  @license   GNU General Public License version 2
*
* You can not resell or redistribute this software.
* 
*}

	{if $node.type==1}
	<div class="row menu_row menu-element {if $node.depth==0} first_rows{/if} menu-element-id-{$node.elementId|escape:'htmlall':'UTF-8'}">
		{elseif $node.type==2}
		<div  class="col-xs-{$node.width|escape:'htmlall':'UTF-8'} cbp-menu-column cbp-menu-element menu-element-id-{$node.elementId|escape:'htmlall':'UTF-8'} {if $node.contentType==0}cbp-empty-column{/if}{if $node.contentType == 6 && isset($node.content.absolute)} cbp-absolute-column{/if}" >
			<div class="cbp-menu-column-inner">
			{/if}
			{if $node.type==2}

				{if isset($node.content_s.title)}
					{if isset($node.content_s.href)}
					<a href="{$node.content_s.href|escape:'htmlall':'UTF-8'}" class="cbp-column-title{if  isset($node.content.view) && $node.content.view==2 && $node.contentType==3} cbp-column-title-inline{/if}">{$node.content_s.title|escape:'html':'UTF-8'} {if isset($node.content_s.legend)}<span class="label cbp-legend cbp-legend-inner">{$node.content_s.legend|escape:'html':'UTF-8'}<span class="cbp-legend-arrow"></span></span>{/if}</a>
					{else}
					<span class="cbp-column-title{if isset($node.content.view) && $node.content.view==2 && $node.contentType==3} cbp-column-title-inline{/if} transition-300">{$node.content_s.title|escape:'html':'UTF-8'} {if isset($node.content_s.legend)}<span class="label cbp-legend cbp-legend-inner">{$node.content_s.legend|escape:'html':'UTF-8'}<span class="cbp-legend-arrow"></span></span>{/if}</span>

					{/if}
				{/if}
				
				{if $node.contentType==1}
				
					{if isset($node.content.ids) && $node.content.ids}
						{*HTML CONTENT*} {$node.content.ids}
					{/if}

				{elseif $node.contentType==2}
				
					{if isset($node.content.ids)}

						{if $node.content.treep}
							<div class="row cbp-categories-row">
								{foreach from=$node.content.ids item=category}
								{if isset($category.title)}
									<div class="col-xs-{$node.content.line|escape:'htmlall':'UTF-8'}">
										<div class="cbp-category-link-w"><a href="{$category.href|escape:'htmlall':'UTF-8'}" class="cbp-column-title cbp-category-title">{$category.title|escape:'html':'UTF-8'}</a>
										{if isset($category.thumb) && $category.thumb != ''}<a href="{$category.href|escape:'htmlall':'UTF-8'}" class="cbp-category-thumb"><img class="replace-2x img-responsive" src="{$category.thumb|escape:'htmlall':'UTF-8'}" alt="{$category.title|escape:'html':'UTF-8'}" /></a>{/if}
										{if isset($category.children)}{include file="./front_subcategory.tpl" categories=$category.children level=1}{/if}
									</div></div>
									{/if}
								{/foreach}
							</div>

						{else}
							<ul class="cbp-links cbp-category-tree">
								{foreach from=$node.content.ids item=category}
								{if isset($category.title)}
									<li {if isset($category.children)}class="cbp-hrsub-haslevel2"{/if}><div class="cbp-category-link-w"><a href="{$category.href|escape:'htmlall':'UTF-8'}">{$category.title|escape:'html':'UTF-8'}</a>

										{if isset($category.children)}{include file="./front_subcategory.tpl" categories=$category.children level=2}{/if}</div>
									</li>
								{/if}
								{/foreach}
							</ul>	
						{/if}
					{/if}

				{elseif $node.contentType==3}
					
					{if isset($node.content.ids)} 
						<ul class="cbp-links cbp-valinks{if !$node.content.view} cbp-valinks-vertical{/if}{if $node.content.view==2} cbp-valinks-vertical cbp-valinks-vertical2{/if}">
							{foreach from=$node.content.ids item=va_link}
							{if isset($va_link.href) && isset($va_link.title) && $va_link.href != '' && $va_link.title != ''}
								<li><a href="{$va_link.href|escape:'htmlall':'UTF-8'}" {if isset($va_link.new_window) && $va_link.new_window}target="_blank"{/if}>{$va_link.title|escape:'htmlall':'UTF-8'}</a></li>
								{/if}
							{/foreach}
						</ul>	
					{/if}

				{elseif $node.contentType==4}

					{if isset($node.content.ids)}
						{if $node.content.view}
							{include file="./products_grid.tpl" products=$node.content.ids perline=$node.content.line}
						{else}
							{include file="./products_list.tpl" products=$node.content.ids perline=$node.content.line}
						{/if}
					{/if}

				{elseif $node.contentType==5}
					
					<ul class="cbp-manufacturers row">
						{foreach from=$node.content.ids item=manufacturer}
							{assign var="myfile" value="img/m/{$manufacturer|escape:'htmlall':'UTF-8'}-medium_default.jpg"}
							{if file_exists($myfile)}
							<li class="col-xs-{$node.content.line|escape:'htmlall':'UTF-8'} transition-opacity-300">
								<a href="{$link->getmanufacturerLink($manufacturer)|escape:'htmlall':'UTF-8'}" title="Manufacturer - {Manufacturer::getNameById($manufacturer)|escape:'htmlall':'UTF-8'}">
							<img src="{$img_manu_dir|escape:'htmlall':'UTF-8'}{$manufacturer|escape:'htmlall':'UTF-8'}-medium_default.jpg" class="img-responsive logo_manufacturer " {if isset($manufacturerSize)} width="{$manufacturerSize.width|escape:'htmlall':'UTF-8'}" height="{$manufacturerSize.height|escape:'htmlall':'UTF-8'}"{/if} alt="Manufacturer - {Manufacturer::getNameById($manufacturer)|escape:'htmlall':'UTF-8'}" />
								</a>
							</li>
							{/if}
						{/foreach}
					</ul>	

				{elseif $node.contentType==6}

					{if isset($node.content.source)}
						{if isset($node.content.href)}<a href="{$node.content.href|escape:'htmlall':'UTF-8'}">{/if}
							<img src="{$node.content.source|escape:'htmlall':'UTF-8'}" class="img-responsive cbp-banner-image" {if isset($node.content.alt)}alt="{$node.content.alt|escape:'htmlall':'UTF-8'}"{/if} 
							{if isset($node.content.size)} width="{$node.content.size.w|escape:'htmlall':'UTF-8'}" height="{$node.content.size.h|escape:'htmlall':'UTF-8'}"{/if} />
						{if isset($node.content.href)}</a>{/if}
					{/if}

				{elseif $node.contentType==7}
					
					<ul class="cbp-manufacturers cbp-suppliers row">
						{foreach from=$node.content.ids item=supplier}
							{assign var="myfile" value="img/su/{$supplier|escape:'htmlall':'UTF-8'}-medium_default.jpg"}
							{if file_exists($myfile)}
							<li class="col-xs-{$node.content.line|escape:'htmlall':'UTF-8'} transition-opacity-300">
								<a href="{$link->getsupplierLink($supplier)|escape:'htmlall':'UTF-8'}" title="supplier - {supplier::getNameById($supplier)|escape:'htmlall':'UTF-8'}">
							<img src="{$img_sup_dir|escape:'htmlall':'UTF-8'}{$supplier|escape:'htmlall':'UTF-8'}-medium_default.jpg" class="img-responsive logo_manufacturer logo_supplier" {if isset($manufacturerSize)} width="{$manufacturerSize.width|escape:'htmlall':'UTF-8'}" height="{$manufacturerSize.height|escape:'htmlall':'UTF-8'}"{/if} alt="supplier - {supplier::getNameById($supplier)|escape:'htmlall':'UTF-8'}" />
								</a>
							</li>
							{/if}
						{/foreach}
					</ul>

				{/if}

			{/if}


			{if isset($node.children) && $node.children|@count > 0}
			{foreach from=$node.children item=child name=categoryTreeBranch}
			{include file="./front_submenu_content.tpl" node=$child }
			{/foreach}
			{/if}
			{if $node.type==2}</div>{/if}
		</div>
