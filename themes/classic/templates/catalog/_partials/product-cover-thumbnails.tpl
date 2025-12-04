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



<div>
  {block name='product_cover'}  
    <div>
      <ul id="alex-cover_slider" class="cS-hidden">
        {foreach from=$product.images item=image}
          <li style="display: block;float: left; margin-right: 6px;" data-thumb="{$image.bySize.small_default.url}" data-src="{$image.bySize.large_default.url}">
            <img style="display: block; height: auto; max-width: 100%;" src="{$image.bySize.large_default.url}" alt="{$image.legend}" title="{$image.legend}">
          </li>
        {/foreach}
      </ul>
    </div>
  {/block}
</div>