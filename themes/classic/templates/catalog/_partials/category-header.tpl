{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}

<div id="js-product-list-header">
    {if $listing.pagination.items_shown_from == 1}
        <div class="block-category card card-block">
            {if !$category.image.large.url}
            <h2 class="h1">{$category.name}</h2>
            {/if}
            <div class="block-category-inner">
                {if $category.image.large.url}
                    <div class="category-cover">
                        <img src="/img/tmp/category_{$category.id}.jpg" alt="{if !empty($category.image.legend)}{$category.image.legend}{else}{$category.name}{/if}">
                    </div>
                {/if}                
                {if $category.description}
                    <div id="category-description" class="text-muted">{$category.description nofilter}</div>
                {/if}
            </div>
        </div>
    {/if}

    {if $subcategories}
        {if (isset($display_subcategories) && $display_subcategories eq 1) || !isset($display_subcategories)}
            <div id="subcategories">
                <ul>
                    {foreach from=$subcategories item=subcategory}
                        <li class="pc_catsubcatblockswimg">
                            <a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" title="{$subcategory.name|escape:'html':'UTF-8'}">
                                <img alt="{$subcategory.name}" src="/img/c/{$subcategory.id_category}_thumb.jpg" onerror="this.classList.add('hidden')"/>

                                <p>
                                    {$subcategory.name|truncate:100:'...'|escape:'html':'UTF-8'|truncate:350}
                                </p>
                            </a>
                        </li>
                    {/foreach}
                </ul>
            </div>
        {/if}
    {/if}
</div>