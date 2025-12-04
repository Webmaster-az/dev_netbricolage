{*
/**
 * mitrocops
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 /*
 * 
 * @author    mitrocops
 * @category seo
 * @package gsnipreview
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */
*}
{if $count_review}
    <div class="reviews_list_stars">
        <span class="star_content clearfix">
            {section name=ratid loop=5}
                {if $smarty.section.ratid.index < $avg_rating}
                    <i style="color: #ffa852;" class="fas fa-star"></i>
                {else}
                    <i style="color: #ffa852;"class="far fa-star"></i>
                {/if}
            {/section}
        </span>
        <span>
            {$count_review|escape:'htmlall':'UTF-8'}&nbsp;{l s='Review(s)' mod='gsnipreview'}&nbsp
        </span>
    </div>
{/if}