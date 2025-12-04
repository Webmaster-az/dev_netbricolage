{*
* 2018 Areama
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@areama.net so we can send you a copy immediately.
*
*
*  @author Areama <contact@areama.net>
*  @copyright  2018 Areama
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Areama
*}
<link href="{$path|escape:'htmlall':'UTF-8'}views/css/transition.min.css" rel="stylesheet" type="text/css" media="all"/>
<link href="{$path|escape:'htmlall':'UTF-8'}views/css/popup.min.css" rel="stylesheet" type="text/css" media="all"/>
<link href="{$path|escape:'htmlall':'UTF-8'}views/css/admin.css" rel="stylesheet" type="text/css" media="all"/>
<style type="text/css">
    #arlsf-config-tabs{
        opacity: 0;
        transition: 0.2s all;
    }
</style>
<div class="row" id="arlsf-config">
    <div class="col-lg-2 col-md-3">
        <div class="list-group arlsfTabs">
            <a class="list-group-item {if empty($active_tab) or $active_tab == 'ArLsfGeneralConfigForm'}active{/if}" data-tab="0" id="ar-lsf-tab-0" data-target="arlsf-general" href="#">
                <i class="icon-cog"></i> {l s='General settings' mod='arlsf'}
            </a>
            <a class="list-group-item {if $active_tab == 'ArLsfOrdersConfigForm'}active{/if}" data-tab="1" id="ar-lsf-tab-1" data-target="arlsf-orders" href="#">
                <i class="icon-cog"></i> {l s='Order popup settings' mod='arlsf'}
            </a>
            <a class="list-group-item {if $active_tab == 'ArLsfAddToCartConfigForm'}active{/if}" data-tab="2" id="ar-lsf-tab-2" data-target="arlsf-add-to-cart" href="#">
                <i class="icon-cog"></i> {l s='Add to cart popup settings' mod='arlsf'}
            </a>
            <a class="list-group-item {if $active_tab == 'ArLsfVisitorConfigForm'}active{/if}" data-tab="3" id="ar-lsf-tab-3" data-target="arlsf-visitor" href="#">
                <i class="icon-cog"></i> {l s='Viewers count popup settings' mod='arlsf'}
            </a>
            <a class="list-group-item {if $active_tab == 'ArLsfFakeConfigForm'}active{/if}" data-tab="4" id="ar-lsf-tab-4" data-target="arlsf-fake" href="#">
                <i class="icon-cog"></i> {l s='Fake mode settings' mod='arlsf'}
            </a>
            
            <a class="list-group-item" data-tab="3" id="ar-lsf-tab-3" data-target="arlsf-about" href="#">
                <i class="icon-info"></i> {l s='About' mod='arlsf'}
            </a>
        </div>
    </div>
    <div class="col-lg-10 col-md-9" id="arlsf-config-tabs">
        {include file="./_partials/_general.tpl"}
        {include file="./_partials/_orders.tpl"}
        {include file="./_partials/_add_to_cart.tpl"}
        {include file="./_partials/_visitor.tpl"}
        {include file="./_partials/_fake.tpl"}
        {include file="./_partials/_about.tpl"}
    </div>
</div>
<script type="text/javascript" src="{$path|escape:'htmlall':'UTF-8'}views/js/transition.min.js"></script>
<script type="text/javascript" src="{$path|escape:'htmlall':'UTF-8'}views/js/popup.min.js"></script>
<script type="text/javascript" src="{$path|escape:'htmlall':'UTF-8'}views/js/admin.js"></script>
<script type="text/javascript">
    window.addEventListener('load', function(){
        $(".arlsfTabs a").click(function(e){
            e.preventDefault();
            $(".arlsfTabs .active").removeClass('active');
            $(this).addClass('active');
            $('#arlsf-config-tabs .arlsf-config-panel').addClass('hidden');
            $('#' + $(this).data('target')).removeClass('hidden');
            $('#arlsfActiveTab').remove();
            $('#arlsfActiveTab').val($(this).data('tab'));
        });
        $('#arlsf-config-tabs').addClass('active');
        $('.arlsfTabs .active').trigger('click');
        arlsfChangeSound();
        arlsfChangeThumb();
        arLsfSwitchFields();
        $('#AR_LSF_SOUND').change(function(){
            arlsfChangeSound();
        });
        $('.prestashop-switch').click(function(){
            arLsfSwitchFields();
        });
        $('#AR_LSF_PRODUCT_THUMB').change(function(){
            arlsfChangeThumb();
        });
    });
    
    function arLsfSwitchFields(){
        if ($('#AR_LSF_SANDBOX_on').is(':checked')){
            $('.field_allowed_ips').removeClass('hidden');
        }else{
            $('.field_allowed_ips').addClass('hidden');
        }
    }
    
    function arlsfChangeThumb(){
        if ($('#AR_LSF_PRODUCT_THUMB').val() == ''){
            $('.field_second_image, .field_stars').addClass('hidden');
        }else{
            $('.field_second_image, .field_stars').removeClass('hidden');
        }
    }
    
    function arlsfChangeSound(){
        if ($('#AR_LSF_SOUND').val() == '0'){
            $('.field_sound_times').addClass('hidden');
        }else{
            $('.field_sound_times').removeClass('hidden');
        }
    }
</script>