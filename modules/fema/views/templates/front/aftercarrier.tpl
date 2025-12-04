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

<script type="text/javascript">
{literal}

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

function fema_relais_ajaxupdate(address, zipcode, city, action, fema_cart_id)
{
    if ((zipcode && zipcode.length >= 5 && action == 'search') || action == 'reset') {
        $('#fema_reset_submit').after(' <img src="{/literal}{$fema_base_dir|escape:'htmlall':'UTF-8'}{literal}/views/img/front/relais/loader.gif"/>');
        $.ajax('{/literal}{$fema_base_dir|escape:'htmlall':'UTF-8'}{literal}/ajax.php?action_ajax_fema=ajaxUpdatePoints', {
            data: {
                'address': address,
                'zipcode': zipcode,
                'city': city,
                'action': action,
                'fema_cart_id': fema_cart_id,
                'fema_token': fema_token,
            },
            success: function(data) {
                var $html = $(data);
                $('#fema_relais_point_table').html($html);
                $('#fema_relais_point_table').children('#fema_relais_point_table').css('display', '');
                fema_checkPudo();
            }
        });
    } else {
        $('#fema_search_zipcode').css('border', '1px solid #2b97eb');
    }
}

$(document).ready(function(){
    setTimeout(function () {
        if ($('#input_fema_predict_gsm_dest:-webkit-autofill').val()) {
            fema_checkGSM();
        }
    }, 25);
    $("#input_fema_predict_gsm_dest").keyup(function() {
        delay(function(){
            fema_checkGSM();
        }, 200);
    });
    if ($("#div_fema_predict_block").is(":visible")) {
        fema_checkGSM();
    }
});
{/literal}
</script>

<noscript>
    <tr>
        <td colspan="5"><div class="fema_relais_error"><strong>{l s='It seems that your browser doesn\'t allow Javascript execution, therefore FEMA Relais is not available. Please change browser settings, or try another browser.' mod='fema'}</strong></div></td>
    </tr><br/>
    <div style="display:none;">
</noscript>

<div id="fema_relais_filter" onclick="
var i = 1;
for (i=1; i<6; i++){
    document.getElementById('fema_relais_filter').style.display='none';
    document.getElementById('fema_relaydetail'+i).style.display='none';
}">
</div>

<table align="center" id="fema_relais_point_table" class="fema_fo" style="display:none;">
{if isset($error)}
    <tr>
        <td colspan="5"><div class="fema_relais_error"> {$error|escape:'htmlall':'UTF-8'} </div></td>
    </tr>
{else}
    {if $fema_relais_status == 'error'}
        <tr>
            <td colspan="5" style="padding:0px;"><div class="fema_relais_error"><p>{l s='It seems that you haven\'t selected a FEMA Pickup point, please pick one from this list' mod='fema'}</p></div></td>
        </tr>
    {/if}

    <tr>
        <td colspan="5" style="padding:0px;">
            <div id="fema_div_relais_header"><p>{l s='Please select your FEMA Relais parcelshop among this list' mod='fema'}</p></div>
            {if $ssl == 0 || $ssl_everywhere == 1}
                <div id="fema_div_relais_srch_link"><span onMouseOver="javascript:this.style.cursor='pointer';javascript:this.style.textDecoration='underline';" onMouseOut="javascript:this.style.cursor='auto';javascript:this.style.textDecoration='none';" onClick="$('#fema_div_relais_srch_panel').slideToggle();">{l s='Search for Pickup points near another address' mod='fema'}</span>
                <div id="fema_div_relais_srch_panel" style="display:none;">
                    <input type="text" id="fema_search_address" placeholder="{l s='Address' mod='fema'}" /><br/>
                    <input type="text" id="fema_search_zipcode" maxlength="5" placeholder="{l s='Postcode' mod='fema'}" /> <input type="text" id="fema_search_city" placeholder="{l s='City' mod='fema'}" />
                    <button type="button" id="fema_search_submit" name="fema_search_submit" onclick="fema_relais_ajaxupdate($('#fema_search_address').val(), $('#fema_search_zipcode').val(), $('#fema_search_city').val(), 'search', fema_cart_id);">{l s='Search' mod='fema'}</button>
                    <button type="button" id="fema_reset_submit" name="fema_reset_submit" onclick="fema_relais_ajaxupdate($('#fema_search_address').val(), $('#fema_search_zipcode').val(), $('#fema_search_city').val(), 'reset', fema_cart_id);">{l s='Reset' mod='fema'}</button>
                </div>
            {/if}
        </td>
    </tr>

    {if isset($fema_relais_empty)}
        <tr>
            <td colspan="5" style="padding:0px;"><div class="fema_relais_error"><p>{l s='There are no Pickup points near this address, please modify it.' mod='fema'}</p></div></td>
        </tr>
    {/if}

{foreach from=$fema_relais_points item=points name=femaRelaisLoop}

<tr class="fema_lignepr" onclick="fema_registerPudo('{$points.relay_id|escape:'htmlall':'UTF-8'}');document.getElementById('{$points.relay_id|escape:'htmlall':'UTF-8'}').checked=true;">
        <td align="left" class="fema_logorelais"></td>
        <td align="left" class="fema_adressepr"><b>{$points.shop_name|escape:'htmlall':'UTF-8'}</b><br/>{$points.address1|escape:'htmlall':'UTF-8'}<br/>{$points.postal_code|escape:'htmlall':'UTF-8'} {$points.city|escape:'htmlall':'UTF-8'}<br/></td>
        <td align="right" class="fema_distancepr">{$points.distance|escape:'htmlall':'UTF-8'} km</td>
        <td align="center" class="fema_popinpr">
            <span onMouseOver="javascript:this.style.cursor='pointer';" onMouseOut="javascript:this.style.cursor='auto';"
                onClick="openDpdfranceDialog('fema_relaydetail{$smarty.foreach.femaRelaisLoop.index+1|escape:'htmlall':'UTF-8'}','map_canvas{$smarty.foreach.femaRelaisLoop.index+1|escape:'htmlall':'UTF-8'}',{$points.coord_lat|escape:'htmlall':'UTF-8'},{$points.coord_long|escape:'htmlall':'UTF-8'},'{$fema_base_dir|escape:'htmlall':'UTF-8'}')">
                <u>{l s='More details' mod='fema'}</u>
            </span>
        </td>
        <td align="center" class="fema_radiopr">
        {if $fema_selectedrelay == $points.relay_id}
            <input type="radio" name="fema_relay_id" id="{$points.relay_id|escape:'htmlall':'UTF-8'}" value="{$points.relay_id|escape:'htmlall':'UTF-8'}" checked="checked">
        {else}
            <input type="radio" name="fema_relay_id" id="{$points.relay_id|escape:'htmlall':'UTF-8'}" value="{$points.relay_id|escape:'htmlall':'UTF-8'}" {if $smarty.foreach.femaRelaisLoop.first} checked="checked" {/if}>
        {/if}
            <label for="{$points.relay_id|escape:'htmlall':'UTF-8'}"><span><span></span></span><b>ICI</b></label>
        </td>
</tr>

<div id="fema_relaydetail{$smarty.foreach.femaRelaisLoop.index+1|escape:'htmlall':'UTF-8'}" class="fema_relaisbox" style="display:none;">

    <div class="fema_relaisboxclose" onclick="
        document.getElementById('fema_relaydetail{$smarty.foreach.femaRelaisLoop.index+1|escape:'htmlall':'UTF-8'}').style.display='none';
        document.getElementById('fema_relais_filter').style.display='none'">
        <img src="{$fema_base_dir|escape:'htmlall':'UTF-8'}/views/img/front/relais/box-close.png"/>
    </div>

    <div class="fema_relaisboxcarto" id="map_canvas{$smarty.foreach.femaRelaisLoop.index+1|escape:'htmlall':'UTF-8'}"></div>

    <div id="relaisboxbottom" class="fema_relaisboxbottom">
        <div id="relaisboxadresse" class="fema_relaisboxadresse">
        <div class="fema_relaisboxadresseheader">{l s='Your FEMA Pickup point' mod='fema'}</div><br/>
            <b>{$points.shop_name|escape:'htmlall':'UTF-8'}</b><br/>
            {$points.address1|escape:'htmlall':'UTF-8'}<br/>
            {if isset($points.address2)}
                {$points.address2|escape:'htmlall':'UTF-8'}<br/>
            {/if}
            {$points.postal_code|escape:'htmlall':'UTF-8'} {$points.city|escape:'htmlall':'UTF-8'}<br/>
            {if isset($points.local_hint)}
                <p>{l s='Landmark' mod='fema'} : {$points.local_hint|escape:'htmlall':'UTF-8'}</p>
            {/if}
        </div>

        <div class="fema_relaisboxhoraires">
            <div class="fema_relaisboxhorairesheader">{l s='Opening hours' mod='fema'}</div><br/>
                <p>
                    <span class="fema_relaisboxjour">{l s='Monday' mod='fema'} : </span>
                    {if !isset($points.monday)} {l s='Closed' mod='fema'}
                    {else}
                        {if $points.monday[0]}
                            {$points.monday[0]|escape:'htmlall':'UTF-8'}
                            {if isset($points.monday[1])}
                                & {$points.monday[1]|escape:'htmlall':'UTF-8'}
                            {/if}
                        {/if}
                    {/if}
                </p>

                <p>
                    <span class="fema_relaisboxjour">{l s='Tuesday' mod='fema'} : </span>
                    {if !isset($points.tuesday)} {l s='Closed' mod='fema'}
                    {else}
                        {if $points.tuesday[0]}
                            {$points.tuesday[0]|escape:'htmlall':'UTF-8'}
                            {if isset($points.tuesday[1])}
                                & {$points.tuesday[1]|escape:'htmlall':'UTF-8'}
                            {/if}
                        {/if}
                    {/if}
                </p>

                <p>
                    <span class="fema_relaisboxjour">{l s='Wednesday' mod='fema'} : </span>
                    {if !isset($points.wednesday)} {l s='Closed' mod='fema'}
                    {else}
                        {if $points.wednesday[0]}
                            {$points.wednesday[0]|escape:'htmlall':'UTF-8'}
                            {if isset($points.wednesday[1])}
                                & {$points.wednesday[1]|escape:'htmlall':'UTF-8'}
                            {/if}
                        {/if}
                    {/if}
                </p>

                <p>
                    <span class="fema_relaisboxjour">{l s='Thursday' mod='fema'} : </span>
                    {if !isset($points.thursday)} {l s='Closed' mod='fema'}
                    {else}
                        {if $points.thursday[0]}
                            {$points.thursday[0]|escape:'htmlall':'UTF-8'}
                            {if isset($points.thursday[1])}
                                & {$points.thursday[1]|escape:'htmlall':'UTF-8'}
                            {/if}
                        {/if}
                    {/if}
                </p>

                <p>
                    <span class="fema_relaisboxjour">{l s='Friday' mod='fema'} : </span>
                    {if !isset($points.friday)} {l s='Closed' mod='fema'}
                    {else}
                        {if $points.friday[0]}
                            {$points.friday[0]|escape:'htmlall':'UTF-8'}
                            {if isset($points.friday[1])}
                                & {$points.friday[1]|escape:'htmlall':'UTF-8'}
                            {/if}
                        {/if}
                    {/if}
                </p>

                <p>
                    <span class="fema_relaisboxjour">{l s='Saturday' mod='fema'} : </span>
                    {if !isset($points.saturday)} {l s='Closed' mod='fema'}
                    {else}
                        {if $points.saturday[0]}
                            {$points.saturday[0]|escape:'htmlall':'UTF-8'}
                            {if isset($points.saturday[1])}
                                & {$points.saturday[1]|escape:'htmlall':'UTF-8'}
                            {/if}
                        {/if}
                    {/if}
                </p>

                <p>
                    <span class="fema_relaisboxjour">{l s='Sunday' mod='fema'} : </span>
                    {if !isset($points.sunday)} {l s='Closed' mod='fema'}
                    {else}
                        {if $points.sunday[0]}
                            {$points.sunday[0]|escape:'htmlall':'UTF-8'}
                            {if isset($points.sunday[1])}
                                & {$points.sunday[1]|escape:'htmlall':'UTF-8'}
                            {/if}
                        {/if}
                    {/if}
                </p>
            </div>

            <div id="relaisboxinfos" class="fema_relaisboxinfos">
                <div class="fema_relaisboxinfosheader">{l s='More info' mod='fema'}</div><br/>
                <h5>{l s='Distance in km' mod='fema'} : </h5>{$points.distance|escape:'htmlall':'UTF-8'} km <br/>
                <h5>{l s='FEMA Relais code' mod='fema'} : </h5>{$points.relay_id|escape:'htmlall':'UTF-8'} <br/>
                {if isset($points.closing_period[0])}
                    <h4><img src="{$fema_base_dir|escape:'htmlall':'UTF-8'}/views/img/front/relais/warning.png"/> {l s='Closing period' mod='fema'} : </h4>{$points.closing_period[0]|escape:'htmlall':'UTF-8'} <br/>
                {/if}
                {if isset($points.closing_period[1])}
                    <h4></h4>{$points.closing_period[1]|escape:'htmlall':'UTF-8'} <br/>
                {/if}
                {if isset($points.closing_period[2])}
                    <h4></h4>{$points.closing_period[2]|escape:'htmlall':'UTF-8'} <br/>
                {/if}
            </div>
        </div>
    </div>
{/foreach}

{/if}
</table>
<noscript></div></noscript>

<div id="div_fema_predict_block" class="fema_fo" style="display:none;">
    <div id="div_fema_predict_header"><p>{l s='Your order will be delivered by FEMA with Predict service' mod='fema'}</p></div>
    <div class="module" id="predict">
        <div id="div_fema_predict_logo"></div>
        <div class="copy"> 
            <p><h2>{l s='Predict offers you the following benefits' mod='fema'} :</h2></p>
            <ul>
                <li><b>{l s='A parcel delivery in a 3-hour time window (choice is made by SMS or through our website)' mod='fema'}</b></li>
                <li><b>{l s='A complete and detailed tracking of your delivery' mod='fema'}</b></li>
                <li><b>{l s='In case of absence, you can schedule a new delivery when and where you it suits you best' mod='fema'}</b></li>
            </ul>
            <br/>
            <p><h2>{l s='How does it work?' mod='fema'}</h2></p>
            <ul>
                <li>{l s='Once your order is ready for shipment, you will receive an SMS proposing various days and time windows for your delivery.' mod='fema'}</li>
                <li>{l s='You choose the moment which suits you best for the delivery by replying to the SMS (no extra cost) or through our website' mod='fema'} <a href="http://destinataires.dpd.fr" target="_blank">dpd.fr</a></li>
                <li>{l s='On the day of delivery, a text message will remind you the selected time window.' mod='fema'}</li>
            </ul>
        </div>
        <br/>
        <div id="div_fema_dpd_logo"></div>
    </div>

    <div id="div_fema_predict_gsm">
        {l s='Get all the advantages of FEMA\'s Predict service by providing a french GSM number here ' mod='fema'} 
        <input type="text" name="fema_predict_gsm_dest" id="input_fema_predict_gsm_dest" maxlength="17" value="{$fema_predict_gsm_dest|escape:'htmlall':'UTF-8'}"></input><div id="fema_predict_gsm_button">></div>
    </div>

    <div id="fema_predict_error" class="warnmsg" style="display:none;">{l s='It seems that the GSM number you provided is incorrect. Please provide a french GSM number, starting with 06 or 07, on 10 consecutive digits.' mod='fema'}</div>
</div>