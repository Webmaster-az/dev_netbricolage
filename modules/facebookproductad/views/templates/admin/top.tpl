{*
*
* Dynamic Ads + Pixel
*
* @author    BusinessTech.fr - https://www.businesstech.fr
* @copyright Business Tech - https://www.businesstech.fr
* @license   Commercial
*
*           ____    _______
*          |  _ \  |__   __|
*          | |_) |    | |
*          |  _ <     | |
*          | |_) |    | |
*          |____/     |_|
*
*}
<div id="header_bar" class="row bg-white">
    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <div class="row">
            <div class="col-xs-3">
                <img  class="img-responsive" src="{$imagePath|escape:'htmlall':'UTF-8'}admin/logo.png" height="57" width="57" alt="" />
            </div>
            <div class="col-xs-6">
                <img class="img-responsive" src="{$imagePath|escape:'htmlall':'UTF-8'}admin/bt_logo.jpg" style="margin-top: 10px;" alt="" />
            </div>

        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
        <div class="text-center">
            <div id="step-by-step" class="row bs-wizard text-center" style="border-bottom:0;">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 bs-wizard-step step-1 {if empty($bConfigureStep1)}disabled{else}complete{/if} text-center">
                    <div class="text-center bs-wizard-stepnum">{l s='1 - Pixel' mod='facebookproductad'}</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="clr_5"></div>
                    <div class="workTabs">
                        {if empty($bConfigureStep1)}
                            <a class="btn btn-sm btn-warning btn-step-1" id="tab-1" ><i class="fa fa-cog"></i> {l s='Configure' mod='facebookproductad'} </a>
                        {/if}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 bs-wizard-step step-2 {if empty($bConfigureStep2)}disabled{else}complete{/if} text-center">
                    <div class="text-center bs-wizard-stepnum">{l s='2 - Basic configuration' mod='facebookproductad'}</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="clr_5"></div>
                    <div class="workTabs">
                        {if empty($bConfigureStep2)}
                            <a class="btn btn-sm btn-warning btn-step-2" id="tab-2" ><i class="fa fa-cog"></i> {l s='Configure' mod='facebookproductad'} </a>
                        {/if}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 bs-wizard-step step-3 {if empty($bConfigureStep3)}disabled{else}complete{/if} text-center">
                    <div class="text-center bs-wizard-stepnum">{l s='3 - Data management' mod='facebookproductad'}</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="clr_5"></div>
                    <div class="workTabs">
                        {if empty($bConfigureStep3)}
                            <a class="btn btn-sm btn-warning btn-step-3" id="tab-001" ><i class="fa fa-cog"></i> {l s='Configure' mod='facebookproductad'} </a>
                        {/if}
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 bs-wizard-step step-4 {if empty($bConfigureStep4)}disabled{else}complete{/if} text-center">
                    <div class="text-center bs-wizard-stepnum">{l s='4 - Import' mod='facebookproductad'}</div>
                    <div class="progress"><div class="progress-bar"></div></div>
                    <a href="#" class="bs-wizard-dot"></a>
                    <div class="clr_5"></div>
                    <div class="workTabs">
			            {if empty($bConfigureStep4)}
                            <a class="fancybox.ajax btn btn-sm btn-warning btn-step-4 bt_add-feed" href="{$sURI|escape:'htmlall':'UTF-8'}&{$sCtrlParamName|escape:'htmlall':'UTF-8'}={$sController|escape:'htmlall':'UTF-8'}&sAction={$aQueryParams.advice.action|escape:'htmlall':'UTF-8'}&sType={$aQueryParams.advice.type|escape:'htmlall':'UTF-8'}"  id="tab-3"><i class="fa fa-cog"></i> {l s='Configure' mod='facebookproductad'} </a>
			            {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
        <a class="btn btn-info btn-md col-xs-12" target="_blank" href="{$faqLink|escape:'htmlall':'UTF-8'}/product/71"><span class="fa fa-question-circle"></span>&nbsp;{l s='Online FAQ' mod='facebookproductad'}</a>
        <div class="clr_5"></div>
        {if !empty($sBusinessId)}
            <a class="btn btn-info-fb btn-md col-xs-12" target="_blank" href="https://business.facebook.com/home/accounts?business_id={$sBusinessId|escape:'htmlall':'UTF-8'}"><span class="fa fa-facebook-official"></span>&nbsp;{l s='Business Manager' mod='facebookproductad'}</a>
        {else}
            <a class="btn btn-info-fb btn-md col-xs-12" target="_blank" href="https://business.facebook.com"><span class="fa fa-facebook-official"></span>&nbsp;{l s='Business Manager' mod='facebookproductad'}</a>
        {/if}

    </div>
</div>

<script type="text/javascript">
	$("a.bt_add-feed").fancybox({
		'hideOnContentClick' : false
	});
</script>
