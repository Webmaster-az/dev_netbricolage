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

<ul>
{foreach $childrens as $children}
	{if isset($children.title)}
		<li>{if isset($children.children)}<div class="responsiveInykator">+</div>{/if}<a href="{$children.href|escape:'htmlall':'UTF-8'}">{$children.title|escape:'htmlall':'UTF-8'}</a>
			{if isset($children.children)}
			{include file="./front_link.tpl" childrens=$children.children}
			{/if}
		</li>  
	{/if}  		             
{/foreach}
</ul>
