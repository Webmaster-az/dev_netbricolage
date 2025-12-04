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
	<div data-element-type="1" data-depth="{$node.depth|escape:'htmlall':'UTF-8'}" data-element-id="{$node.elementId|escape:'htmlall':'UTF-8'}" class="row menu_row menu-element {if $node.depth==0} first_rows{/if} menu-element-id-{$node.elementId|escape:'htmlall':'UTF-8'}">
		{elseif $node.type==2}
		<div data-element-type="2" data-depth="{$node.depth|escape:'htmlall':'UTF-8'}" data-width="{$node.width|escape:'htmlall':'UTF-8'}" data-contenttype="{$node.contentType|escape:'htmlall':'UTF-8'}" data-element-id="{$node.elementId|escape:'htmlall':'UTF-8'}" class="col-xs-{$node.width|escape:'htmlall':'UTF-8'} menu_column menu-element menu-element-id-{$node.elementId|escape:'htmlall':'UTF-8'}">
			{/if}
		
			<div class="action-buttons-container">
				<button type="button" class="btn btn-default  add-row-action" ><i class="icon icon-plus"></i> {l s='Row' mod='iqitmegamenu'}</button>
				<button type="button" class="btn btn-default  add-column-action" ><i class="icon icon-plus"></i> {l s='Column' mod='iqitmegamenu'}</button>
				<button type="button" class="btn btn-default duplicate-element-action" ><i class="icon icon-files-o"></i> </button>
				<button type="button" class="btn btn-danger remove-element-action" ><i class="icon-trash"></i> </button>
			</div>
			<div class="dragger-handle btn btn-danger"><i class="icon-arrows "></i></a></div>

			{if $node.type==2}
				{include file="./column_content.tpl" node=$node}
			{/if}

			{if isset($node.children) && $node.children|@count > 0}
			{foreach from=$node.children item=child name=categoryTreeBranch}
			{include file="./submenu_content.tpl" node=$child }
			{/foreach}
			{/if}
		</div>
