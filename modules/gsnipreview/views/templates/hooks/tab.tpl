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

{if $gsnipreviewptabs_type == 2}

{if $gsnipreviewrvis_on == 1}

{if $gsnipreviewratings_on == 1 || $gsnipreviewtitle_on == 1 || $gsnipreviewtext_on == 1}

<li>

<a id="idTab777-my" href="#idTab777" data-toggle="tab"
        ><img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/{$gsnipreviewactivestar|escape:'htmlall':'UTF-8'}"
                                            class="{if $gsnipreviewis16 == 0}fix-width-ps15{/if} title-rating-one-star"
                                           alt="{l s='Reviews' mod='gsnipreview'}" />&nbsp;{l s='Reviews' mod='gsnipreview'} <span id="count-review-tab">({$gsnipreviewcount_reviews|escape:'html':'UTF-8'})</span></a>

</li>

{/if}

{/if}

{/if}
