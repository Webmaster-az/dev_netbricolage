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

<div>
     <div class="alert alert-success">
         {if $gsnipreviewis17 == 1}
             {$gsnipreviewmsg|escape:'quotes':'UTF-8' nofilter}
         {else}
             {$gsnipreviewmsg|escape:'quotes':'UTF-8'}
         {/if}
        </div>
</div>