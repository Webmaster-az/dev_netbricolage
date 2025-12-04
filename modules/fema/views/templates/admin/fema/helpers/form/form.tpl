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
<!--<link rel="stylesheet" type="text/css" href="http://192.168.2.148/prestashop/fema/themes/new-theme/public/theme.css"/>-->

{literal}
<script type='text/javascript'>
    $(document).ready(function(){
        $('.page-title').prepend('<img src="../modules/fema/views/img/admin/admin.png"/>')
        $('.marquee').marquee({
            duration: 20000,
            gap: 50,
            delayBeforeStart: 0,
            direction: 'left',
            duplicated: true,
            pauseOnHover: true
        });
    $('a.popup').fancybox({             
            'hideOnContentClick': true,
            'padding'           : 0,
            'overlayColor'      :'#D3D3D3',
            'overlayOpacity'    : 0.7,
            'width'             : 1024,
            'height'            : 640,
            'type'              :'iframe'
            });
        jQuery.expr[':'].contains = function(a, i, m) { 
            return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0; 
        };
    $("#tableFilter").keyup(function () {
        //split the current value of tableFilter
        var data = this.value.split(";");
        //create a jquery object of the rows
        var jo = $("#fbody").find("tr");
        if (this.value == "") {
            jo.show();
            return;
        }
        //hide all the rows
        jo.hide();

        //Recusively filter the jquery object to get results.
        jo.filter(function (i, v) {
            var t = $(this);
            for (var d = 0; d < data.length; ++d) {
                if (t.is(":contains('" + data[d] + "')")) {
                    return true;
                }
            }
            return false;
        })
        //show the rows that match.
        .show();
        }).focus(function () {
            this.value = "";
            $(this).css({
                "color": "black"
            });
            $(this).unbind('focus');
        }).css({
            "color": "#C0C0C0"
        });
    });
    function checkallboxes(ele) {
        var checkboxes = $("#fbody").find(".checkbox:visible");
        if (ele.checked) {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = true;
                }
            }
        } else {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox') {
                    checkboxes[i].checked = false;
                }
            }
        }
    }
</script>

{/literal}



{$msg|escape:'quotes':'UTF-8'}

<div id="fieldset_grid">
{if !isset($order_info.error) || (isset($order_info.error) && !$order_info.error)}
	  <form id="exportform" action="index.php?tab=AdminFema&token={$token|escape:'htmlall':'UTF-8'}" method="POST" enctype="multipart/form-data">

       <p>
		<input id="tableFilter" name="tableFilter" placeholder="{l s='Search by order reference, waybill or recipient' mod='fema'}"/>
		<input type="submit" class="button" name="Search" value="{l s='Search' mod='fema'}" />
        <input type="submit" class="button" name="WithWaybill" value="{l s='With Waybill' mod='fema'}" />
        <input type="submit" class="button" name="WithoutWaybill" value="{l s='Without Waybill' mod='fema'}" />
		<input type="submit" class="buttonlight" name="ClearSearch" value="{l s='Clear Filter' mod='fema'}"/>
    	</p>

        <body>
			<table class="table">
                <thead>
                    <tr>
                        <th class="hcheckexport"><input type="checkbox" onchange="checkallboxes(this)"/></th>
                        <th class="hid text-center">ID</th>
                        <th class="href text-center">{l s='Reference' mod='fema'}</th>
                        <th class="hdate text-center">{l s='Date of order' mod='fema'}</th>
                        <th class="hnom">{l s='Client' mod='fema'}</th>
                        <th class="hrecipient">{l s='Recipient' mod='fema'}</th>
                        <th class="hservice text-center">{l s='Customer Service' mod='fema'}</th>
                        <th class="hwaybill text-center">{l s='Waybill' mod='fema'}</th>
                        {*<th class="htype">{l s='Service' mod='fema'}</th>*}
                        <th class="hpr">{l s='Destination' mod='fema'}</th>
                        <th class="hpoids" width="7%">{l s='Volumes' mod='fema'}</th>
                        <th class="hpoids" width="7%">{l s='Weight' mod='fema'}</th>
                        <th _colspan="2" class="hprix text-center" width="10%">{l s='Amount' mod='fema'}<br/></th>
                        <th class="hstatutcommande text-start">{l s='Order status' mod='fema'}</th>
                    </tr>
                </thead><tbody id="fbody">

        {foreach from=$order_info item=order}
            <tr>
                <td><input class="checkbox form-check-input" type="checkbox" name="checkbox[]" value="{$order.id|escape:'htmlall':'UTF-8'}"></td>
				<td class="id text-center">{$order.id|escape:'htmlall':'UTF-8'}</td>
                <td class="ref text-center">{$order.reference|escape:'htmlall':'UTF-8'}</td>
                <td class="date text-center">{$order.date|escape:'htmlall':'UTF-8'}</td>
                <td class="nom">{$order.nom|escape:'htmlall':'UTF-8'}</td>
                <td class="hrecipient">{$order.recipient|escape:'htmlall':'UTF-8'}</td>
                <td class="service text-center" >{$order.service|escape:'htmlall':'UTF-8'}</td>
                <td class="waybill text-center" >{$order.waybill|escape:'htmlall':'UTF-8'}</td>
                {*
                <td class="type">                
                    <div id="order_service_classic_select">
                        <select name="order_service_code[{$order.id|escape:'htmlall':'UTF-8'}]">
                            <option value="CTT19MV" {if $order.fema_service == "CTT19MV"} selected {/if}>{l s='Fema Expresso 19 Múltiplos' mod='fema'}</option>
                            <option value="Fema 72" {if $order.fema_service == "Fema 72"} selected {/if}>{l s='Fema 72' mod='fema'}</option>
                            <option value="FemaSTDCaixa" {if $order.fema_service == "FemaSTDCaixa"} selected {/if}>{l s='Fema Internacional' mod='fema'}</option>
                            <option value="FemaRange19"  {if $order.fema_service == "FemaRange19"} selected {/if}>{l s='Fema PT - 24 Express' mod='fema'}</option>
                        </select>
                    </div>
                </td>
                *}
                <td class="pr">{$order.address|escape:'quotes':'UTF-8'}</td>
                <td class="poids" width="7%"><input class="form-control" name="parcelVolume[{$order.id|escape:'htmlall':'UTF-8'}]" type="number" value="{$order.n_volumes|escape:'htmlall':'UTF-8'|default:1}" /> {''|escape:'htmlall':'UTF-8'}</td>
                <td class="poids" width="7%">
					<div class="input-group">
						<input class="form-control" name="parcelweight[{$order.id|escape:'htmlall':'UTF-8'}]" type="text" min ="1" value="{$order.poids|escape:'htmlall':'UTF-8'}" /> 
						<div class="input-group-append">
						  <span class="input-group-text">{$order.weightunit|escape:'htmlall':'UTF-8'}</span>
						</div>
					</div>
				</td>
                <td class="prix text-center" width="10%">{$order.prix|escape:'htmlall':'UTF-8'}<br>{$order.advalorem_checked|escape:'htmlall':'UTF-8'}</td>
                <!--<td class="advalorem" width="3%">{$order.advalorem_checked|escape:'htmlall':'UTF-8'}</td> MAURICIO-->
                <td class="statutcommande text-start">{$order.statut|escape:'quotes':'UTF-8'}</td>
            </tr>
        {/foreach}
    </tbody></table>
    <p>
        <input type="submit" class="button" name="createBooking" value="{l s='Create Fema Booking' mod='fema'}" />
        <input type="submit" class="button" name="updateTracking" value="{l s='Update Tracking' mod='fema'}" />
        <input type="submit" class="buttonlight" name="printLabelsA4" value="{l s='Print  Labels A4' mod='fema'}" />
        <input type="submit" class="buttonlight" name="printLabelsA6" value="{l s='Print  Labels A6' mod='fema'}" />
    </p>
    </form></div>
{else}
    <div class="alert warn">{l s='There are no orders' mod='fema'}</div>
{/if}