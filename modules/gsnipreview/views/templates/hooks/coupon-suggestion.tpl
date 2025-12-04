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
    <h4><img src="{$base_dir_ssl|escape:'htmlall':'UTF-8'}modules/gsnipreview/views/img/btn/ico-facebook.png"/> {$gsnipreviewtitle|escape:'html':'UTF-8'}</h4>
     <div class="alert alert-info form-info">
            {$gsnipreviewmsg|escape:'html':'UTF-8'}
        </div>
</div>


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