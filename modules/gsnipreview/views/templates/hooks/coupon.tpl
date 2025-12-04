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

<h4>
   <img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/btn/{if isset($gsnipreviewis_facebook)}ico-facebook.png{else}ico-star.png{/if}"/>&nbsp;
   {$gsnipreviewfirsttext|escape:'htmlall':'UTF-8'}  {$gsnipreviewdiscountvalue|escape:'htmlall':'UTF-8'}
</h4>
<br/>
<div class="text-coupon-lines">{$gsnipreviewsecondtext|escape:'htmlall':'UTF-8'}: &nbsp;<b>{$gsnipreviewvoucher_code|escape:'htmlall':'UTF-8'}</b></div>
<br/>
<div class="text-coupon-lines">{$gsnipreviewthreetext|escape:'htmlall':'UTF-8'}: &nbsp;<b>{$gsnipreviewdate_until|escape:'htmlall':'UTF-8'}</b></div>


{literal}
    <script type="text/javascript">

        $('#fb-con-wrapper').css('height','auto');

        document.addEventListener("DOMContentLoaded", function(event) {
            $(document).ready(function(){

                $('#fb-con-wrapper').css('height','auto');

            });
        });

    </script>
{/literal}