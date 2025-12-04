{**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    FEMA S.A.S. <support.ecommerce@dpd.fr>
 * @copyright 2019 FEMA S.A.S.
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *}

<link rel="stylesheet" type="text/css" href="../modules/fema/views/css/admin/fema_config.css"/>
<link rel="stylesheet" type="text/css" href="../modules/fema/views/js/admin/jquery/plugins/fancybox/jquery.fancybox.css" media="screen"/>
<script type="text/javascript" src="../modules/fema/views/js/admin/jquery/plugins/fancybox/jquery.fancybox.js"></script>
<script type="text/javascript" src="../modules/fema/views/js/admin/jquery/plugins/validation/jquery.validate.min.js"></script>

{literal}
<script type="text/javascript">
function fema_attr_carrier(element) {
    var maxValue = undefined;
    $('option', element).each(function() {
        var val = $(this).attr('value');
        val = parseInt(val, 10);
        if (maxValue === undefined || maxValue < val) {
            maxValue = val;
        }
    });
    element.val(maxValue);
}
</script>
{/literal}

<form action="{$form_submit_url|escape:'htmlall':'UTF-8'}" method="post">
    <fieldset><legend><img src="../modules/fema/views/img/admin/admin.png" alt="" title="" />{l s='Settings' mod='fema'}</legend>
    
        <!-- Tabs header -->
        <div id="fema_menu">
            <ul id="onglets">
                <li style="background-color: #2b97eb;"><a id="onglet0" href="javascript:void(0)" onclick="$(&quot;#donnees_exp,#modes_transport,#options_supp,#gestion_exp,#recap&quot;).fadeOut(0, function() {literal}{{/literal}$(&quot;#accueil&quot;).fadeIn(&quot;slow&quot;);$(&quot;#onglet0&quot;).parent().css(&quot;background-color&quot;, &quot;#2b97eb&quot;);
        $(&quot;#onglet1,#onglet2,#onglet4,#onglet5&quot;).parent().css(&quot;background-color&quot;, &quot;#808285&quot;);{literal}}{/literal});"> {l s='Start' mod='fema'} </a></li>
                <li><a id="onglet1" href="javascript:void(0)" onclick="$(&quot;#accueil,#modes_transport,#options_supp,#gestion_exp,#recap&quot;).fadeOut(0, function() {literal}{{/literal}$(&quot;#donnees_exp&quot;).fadeIn(&quot;slow&quot;);$(&quot;#onglet1&quot;).parent().css(&quot;background-color&quot;, &quot;#2b97eb&quot;);
        $(&quot;#onglet0,#onglet2,#onglet4,#onglet5&quot;).parent().css(&quot;background-color&quot;, &quot;#808285&quot;);{literal}}{/literal});"> {l s='Your personal data' mod='fema'} </a></li>
                <li><a id="onglet2" href="javascript:void(0)" onclick="$(&quot;#accueil,#donnees_exp,#options_supp,#gestion_exp,#recap&quot;).fadeOut(0, function() {literal}{{/literal}$(&quot;#modes_transport&quot;).fadeIn(&quot;slow&quot;);$(&quot;#onglet2&quot;).parent().css(&quot;background-color&quot;, &quot;#2b97eb&quot;);
        $(&quot;#onglet1,#onglet0,#onglet4,#onglet5&quot;).parent().css(&quot;background-color&quot;, &quot;#808285&quot;);{literal}}{/literal});"> {l s='Delivery services' mod='fema'} </a></li>
                <li><a id="onglet5" href="javascript:void(0)" onclick="$(&quot;#accueil,#donnees_exp,#modes_transport,#options_supp,#gestion_exp&quot;).fadeOut(0, function() {literal}{{/literal}$(&quot;#recap&quot;).fadeIn(&quot;slow&quot;);$(&quot;#onglet5&quot;).parent().css(&quot;background-color&quot;, &quot;#2b97eb&quot;);
        $(&quot;#onglet1,#onglet2,#onglet4,#onglet0&quot;).parent().css(&quot;background-color&quot;, &quot;#808285&quot;);{literal}}{/literal});"> {l s='Summary' mod='fema'} </a></li>
            </ul>
        </div>

        <!-- Tab Accueil -->
        <div id="accueil" style="display:block;">
            <strong><br/><span class="section_title">{l s='Welcome to FEMA Prestashop Module' mod='fema'}</span></strong><br/>
            <div class="notabene" style="font-size:14px;">{l s='You must be a FEMA customer to use this module '}<a href="https://www.fema.pt/" target="_blank">https://www.fema.pt/</a></div><br/>
    
        </div>

        <!-- Tab Vos données expéditeur -->
        <div id="donnees_exp" style="display:none;">
            <br/><span class="section_title">{l s='Your personal data' mod='fema'}</span><br/><br/>

                <div id="donnees_exp_wrap">
                <label>{l s='Company Name' mod='fema'}</label><div class="margin-form"><input type="text" size="33" name="nom_exp" value="{$nom_exp|escape:'htmlall':'UTF-8'}" /></div>
                <label>{l s='Address 1' mod='fema'}</label><div class="margin-form"><input type="text" size="33" name="address_exp" value="{$address_exp|escape:'htmlall':'UTF-8'}" /></div>
                <label>{l s='Address 2' mod='fema'}</label><div class="margin-form"><input type="text" size="33" name="address2_exp" value="{$address2_exp|escape:'htmlall':'UTF-8'}" /></div>
                <label>{l s='Postal code' mod='fema'}</label><div class="margin-form"><input type="text" size="33" name="cp_exp" value="{$cp_exp|escape:'htmlall':'UTF-8'}" /></div>
                <label>{l s='City' mod='fema'}</label><div class="margin-form"><input type="text" size="33" name="ville_exp" value="{$ville_exp|escape:'htmlall':'UTF-8'}" /></div>
                <label>{l s='Telephone' mod='fema'}</label><div class="margin-form"><input type="text" size="33" name="tel_exp" value="{$tel_exp|escape:'htmlall':'UTF-8'}" /></div>
                <label>{l s='E-mail' mod='fema'}</label><div class="margin-form"><input type="text" size="33" name="email_exp" value="{$email_exp|escape:'htmlall':'UTF-8'}" /></div>

                <center><a size="6" name="next" class="button" href="javascript:void(0)" onclick="$(&quot;#onglet0&quot;).click();">{l s='Previous' mod='fema'}</a> 
                <a size="6" name="next" class="button" href="javascript:void(0)" onclick="$(&quot;#onglet2&quot;).click();">{l s='Next' mod='fema'}</a></center>
                <br/>
            </div>
        </div>

        <!-- Tab Services de transport -->
        <div id="modes_transport" style="display:none;">
            <br/><span class="section_title">{l s='Delivery services' mod='fema'}</span><br/><br/>
            <div id="modes_transport_wrap">

            <!-- FEMA Classic -->
            <div id="service_classic">
                <label>{l s='FEMA' mod='fema'}<br/>

                <div id="service_classic_addcarrier">
                    {l s='API Credentials' mod='fema'}<br/><br/>
                    {l s='Username: ' mod='fema'}<input type="text" size="15" maxlength="15" name="classic_username" class="classic_client_id" value="{$classic_username|escape:'htmlall':'UTF-8'}" /> <br/><br/>
                    {l s='Password: ' mod='fema'}<input type="text" size="15" maxlength="15" name="classic_password" class="classic_password" value="{$classic_password|escape:'htmlall':'UTF-8'}" /><br/><br/>

                </div>
                <!--
                <div id="service_next_img"></div>
                <div id="service_classic_selectcarrier">
                    {l s='Carrier assignation' mod='fema'}<br/><br/>
                    <select name="fema_classic_carrier_id"><option value="0">{l s='None - Disable this carrier' mod='fema'}</option>
                    {foreach from=$carriers item=carrier} 
                        {if $carrier.id_carrier == $fema_classic_carrier_id}
                            <option value="{$carrier.id_carrier|escape:'htmlall':'UTF-8'}" selected>{$carrier.id_carrier|escape:'htmlall':'UTF-8'} - {$carrier.name|escape:'htmlall':'UTF-8'}</option>
                        {else}
                            <option value="{$carrier.id_carrier|escape:'htmlall':'UTF-8'}">{$carrier.id_carrier|escape:'htmlall':'UTF-8'} - {$carrier.name|escape:'htmlall':'UTF-8'}</option>
                        {/if}
                    {/foreach}
                    </select>
                </div>
                -->
                <div id="service_next_img"></div>
                <div id="service_classic_package">
                    {l s='Typical Package Size  (Meters)' mod='fema'}<br/><br/>
                    {l s='Height: ' mod='fema'}<input type="text" size="15" maxlength="15" name="classic_height" class="classic_height" value="{$classic_height|escape:'htmlall':'UTF-8'}" /> <br/><br/>
                    {l s='Length: ' mod='fema'}<input type="text" size="15" maxlength="15" name="classic_length" class="classic_length" value="{$classic_length|escape:'htmlall':'UTF-8'}" /> <br/><br/>
                    {l s='Width: ' mod='fema'}<input type="text" size="15" maxlength="15" name="classic_width" class="classic_width" value="{$classic_width|escape:'htmlall':'UTF-8'}" /> <br/><br/>
                </div>

            </div>

            <div class="notabene">{l s='Please contact your FEMA sales representative to get your contract numbers.' mod='fema'}</div><br/><br/>

            <center><a size="6" name="next" class="button" href="javascript:void(0)" onclick="$(&quot;#onglet1&quot;).click();">{l s='Previous' mod='fema'}</a> 
            <a size="6" name="next" class="button" href="javascript:void(0)" onclick="$(&quot;#onglet5&quot;).click();">{l s='Next' mod='fema'}</a></center>
            <br/>
            </div>
        </div>

        <!-- Tab Gestion des expéditions -->
        <!--
        <div id="gestion_exp" style="display:none;">
            <br/><span class="section_title_alt">{l s='Orders management' mod='fema'}</span><br/><br/>
            <label>{l s='Preparation in progress status' mod='fema'}<br/></label>
                <div class="margin-form">
                <select name="id_expedition">
                {foreach from=$etats_factures item=value} 
                    {if $value.id_order_state == $fema_etape_expedition}
                        <option value="{$value.id_order_state|escape:'htmlall':'UTF-8'}" selected>{$value.name|escape:'htmlall':'UTF-8'}</option>
                    {else}
                        <option value="{$value.id_order_state|escape:'htmlall':'UTF-8'}">{$value.name|escape:'htmlall':'UTF-8'}</option>
                    {/if}
                {/foreach}
                </select>
                <br/>{l s='Orders in this state will be selected by default for exporting.' mod='fema'}<br/>
            </div>
            
            <label>{l s='Shipped status' mod='fema'}<br/></label>
            <div class="margin-form">
                <select name="id_expedie">
                {foreach from=$etats_factures item=value} 
                    {if $value.id_order_state == $fema_etape_expediee}
                        <option value="{$value.id_order_state|escape:'htmlall':'UTF-8'}" selected>{$value.name|escape:'htmlall':'UTF-8'}</option>
                    {else}
                        <option value="{$value.id_order_state|escape:'htmlall':'UTF-8'}">{$value.name|escape:'htmlall':'UTF-8'}</option>
                    {/if}
                {/foreach}
                </select>
                <br/>{l s='Once parcel trackings are generated, orders will be updated to this state.' mod='fema'}<br/>
            </div>

            <label>{l s='Delivered status' mod='fema'}<br/></label>
            <div class="margin-form">
                <select name="id_livre">
                {foreach from=$etats_factures item=value} 
                    {if $value.id_order_state == $fema_etape_livre}
                        <option value="{$value.id_order_state|escape:'htmlall':'UTF-8'}" selected>{$value.name|escape:'htmlall':'UTF-8'}</option>
                    {else}
                        <option value="{$value.id_order_state|escape:'htmlall':'UTF-8'}">{$value.name|escape:'htmlall':'UTF-8'}</option>
                    {/if}
                {/foreach}
                </select>
                <br/>{l s='Once parcels are delivered, orders will be updated to this state.' mod='fema'}<br/>
            </div>

            <label>{l s='Auto update of status and tracking links' mod='fema'}<br/></label>
            <div class="margin-form">
               <select name="auto_update">
                {foreach from=$optupdate item=option key=key} 
                    {if $key == $auto_update}
                        <option value="{$key|escape:'htmlall':'UTF-8'}" selected>{$option|escape:'htmlall':'UTF-8'}</option>
                    {else}
                        <option value="{$key|escape:'htmlall':'UTF-8'}">{$option|escape:'htmlall':'UTF-8'}</option>
                    {/if}
                {/foreach}
                </select>
                <br/>{l s='Order statuses and tracking links will be automatically updated following parcel delivery.' mod='fema'}<br/>
            </div>

            <label>{l s='Allow management of non-FEMA orders' mod='fema'}<br/></label>
            <div class="margin-form">
               <select name="marketplace_mode">
                {foreach from=$optmarketplace item=option key=key} 
                    {if $key == $marketplace_mode}
                        <option value="{$key|escape:'htmlall':'UTF-8'}" selected>{$option|escape:'htmlall':'UTF-8'}</option>
                    {else}
                        <option value="{$key|escape:'htmlall':'UTF-8'}">{$option|escape:'htmlall':'UTF-8'}</option>
                    {/if}
                {/foreach}
                </select>
                <br/>{l s='All orders will be manageable regardless of the carrier, useful when using marketplace connectors.' mod='fema'}<br/>
            </div>
            
            
            <label>{l s='Parcel insurance service' mod='fema'}<br/></label>
            <div class="margin-form">
                <select name="ad_valorem">
                {foreach from=$optvd item=option key=key} 
                    {if $key == $fema_ad_valorem}
                        <option value="{$key|escape:'htmlall':'UTF-8'}" selected>{$option|escape:'htmlall':'UTF-8'}</option>
                    {else}
                        <option value="{$key|escape:'htmlall':'UTF-8'}">{$option|escape:'htmlall':'UTF-8'}</option>
                    {/if}
                {/foreach}
                </select>
                <br/>{l s='Ad Valorem : Please refer to your pricing conditions.' mod='fema'}<br/>
            </div>
            

            <label>{l s='FEMA Returns service' mod='fema'}<br/></label>
            <div class="margin-form">
                <select name="retour">
                {foreach from=$optretour item=option key=key} 
                    {if $key == $fema_retour_option}
                        <option value="{$key|escape:'htmlall':'UTF-8'}" selected>{$option|escape:'htmlall':'UTF-8'}</option>
                    {else}
                        <option value="{$key|escape:'htmlall':'UTF-8'}">{$option|escape:'htmlall':'UTF-8'}</option>
                    {/if}
                {/foreach}
                </select>
                <br/>{l s='FEMA Returns options : Please refer to your pricing conditions.' mod='fema'}<br/>
            </div>

            <center><a size="6" name="next" class="button" href="javascript:void(0)" onclick="$(&quot;#onglet2&quot;).click();">{l s='Previous' mod='fema'}</a> 
            <a size="6" name="next" class="button" href="javascript:void(0)" onclick="$(&quot;#onglet5&quot;).click();">{l s='Next' mod='fema'}</a></center>
            <br/>
        </div>
        -->

        <!-- Tab Recapitulatif -->
        <div id="recap" style="display:none;">
            <strong><center><br/><br/>{l s='You\'re all set!' mod='fema'}</center></strong><br/><br/>
            <center><input id="save_settings_button" type="submit" name="submitRcReferer" value="{l s='Save settings' mod='fema'}" class="button"></center></br>
            <center><a size="6" name="next" class="button" href="javascript:void(0)" onclick="$(&quot;#onglet4&quot;).click();">{l s='Return to configuration' mod='fema'}</a></center><br/>
            <br/>
        </div>
    </fieldset>
</form>