

<style type="text/css">

    <!--

    #glsEnvios tr th,

    #glsEnvios2 tr th {

        text-align: center;

    }

    -->

</style>

<style type="text/css" media="print">

    <!--

	#glsEnvios2, .glsEnvios2 {

		width:100%;

	}

	.glsEnvios2 tr th{ 

		width:100%;

		padding: 10px; 

		text-align: center 

	}

    #glsEnvios2 tr.head td{

		width:33%;

        padding: 5px; 

		border: 1px solid #000;

		font-size: 11px;

    }

	#glsEnvios2 tr td {

		width:100%;

        padding: 5px; 

		font-size: 11px;

    }

	#bulksend, #mergetags {

		margin-right: 10px;

	}

	

	

	#glsEnvios .tooltip,

    #glsEnvios2 .tooltip {

		white-space: pre-line!important;

	}

    -->

</style>

<div class="content bootstrap">







{*literal*}

<script type="text/javascript">

	// Variables

	

	

	$(document).ready(function() {

		// Print

		

		

		if ($(".datepicker").length > 0) {

				$(".datepicker").datepicker({

					prevText: '',

					nextText: '',

					altFormat: 'yy-mm-dd'

				});

			}

	});

	function sendBulkGLS(){

		if (jQuery('.glscheck:checked').length == 0){

			alert('{l s='Debe seleccionar algún pedido.' mod='glsshipping'}');

			return false;

		}

		var ids='';

		jQuery('.glscheck:checked').each(function(){

			ids += jQuery(this).val()+':';

		});

		jQuery('#bulksend #ids_order_envio').val(ids);

		return true;

	}

	function mergeTags(){

		if (jQuery('.glscheck:checked').length == 0){

			alert('{l s='Debe seleccionar algún pedido.' mod='glsshipping'}');

			return false;

		}

		var ids='';

		jQuery('.glscheck:checked').each(function(){

			ids += jQuery(this).val()+':';

		});

		jQuery('#mergetags #ids_order_envio').val(ids);

		return true;

	}

	

</script>

{*/literal*}

<div class="row">

    <div class="col-lg-12">

        <div class="panel">

            <div class="panel-heading">

                <i class="icon-truck"></i>



            </div>

<ul class="nav nav-tabs" role="tablist">

    <li role="presentation" {if $activetab == '' || $activetab == 'Pedidos'}class="active"{/if}><a href="#Pedidos" aria-controls="Pedidos" role="tab" data-toggle="tab">{l s='Pedidos' mod='glsshipping'}</a></li>

    <li role="presentation" {if $activetab == 'Manifest'}class="active"{/if}><a href="#Manifiest" aria-controls="Manifiesto" role="tab" data-toggle="tab">{l s='Manifiesto de Carga' mod='glsshipping'}</a></li>

    <li role="presentation" {if $activetab == 'Collect'}class="active"{/if}><a href="#Collect" aria-controls="Recogida" role="tab" data-toggle="tab">{l s='Solicitar recogida' mod='glsshipping'}</a></li>

</ul>

 <div class="tab-content">

    <div role="tabpanel" class="tab-pane{if $activetab == '' || $activetab == 'Pedidos'} active{/if}" id="Pedidos">

	<div class="row">

			<div class="col-lg-12">

			<div class="alert alert-info">

                <p>{l s='Puede cambiar los bultos por expedición, el departamento origen, el valor asegurado y los parámetros Retorno y RCS en el mismo momento de generar la etiqueta; Indique los valores para este pedido en los campos correspondientes.' mod='glsshipping'}</p>

                <p>{l s='Si desea emplear la configuración que ha predefinido en el módulo de GLS no modifique los campos.' mod='glsshipping'}</p>

				<p>{l s='Eurobusiness Parcel sólo permite envíos monobulto, sin retorno, sin reembolso y sin RCS.' mod='glsshipping'}</p>

            </div>

            {if $errores}

            <div class="alert alert-danger">

                <p>{$errores}</p>

            </div>

            {/if}

			</div>

			<div class="col-lg-8">

			<form action="#" method="post">

				<div class="date_range row">

					<div class="input-group fixed-width-md pull-left">

						<input type="text" class="filter datepicker date-input form-control" id="local_from" name="local[from]"  placeholder="{l s='From'}" />

						<input type="hidden" id="date_from" name="date[from]" value="{$date_from}">

						<span class="input-group-addon">

							<i class="icon-calendar"></i>

						</span>

					</div>

					<div class="input-group fixed-width-md pull-left">

						<input type="text" class="filter datepicker date-input form-control" id="local_to" name="local[to]"  placeholder="{l s='To'}" />

						<input type="hidden" id="date_to" name="date[to]" value="{$date_to}">

						<span class="input-group-addon">

							<i class="icon-calendar"></i>

						</span>

					</div>

					

					<span class="pull-left">

					<button type="submit" id="submittoday" name="submitFilter" class="btn btn-default">

						{l s='Hoy'}

					</button>

					<button type="submit" id="submitweek" name="submitFilter" class="btn btn-default">

						{l s='Semana'}

					</button>

					<button type="submit" id="submitmonth" name="submitFilter" class="btn btn-default">

						{l s='Mes'}

					</button>

				</span>

				

				<span class="pull-right">

					<button type="submit" id="submitFilterButton" name="submitFilter" class="btn btn-default">

						<i class="icon-search"></i> {l s='Search'}

					</button>

				</span>

				<div class="input-group fixed-width-md pull-right">

					<select name="nselestado" id="iselestado">

						<option>{l s='Estado'}</option>

					 {foreach from=$estados key=s item=estado}

						<option value="{$estado.id_order_state}" {if $selestado == $estado.id_order_state}selected="selected"{/if}>{$estado.name}</option>

					 {/foreach}

					</select>

				</div>

					<script>

						$(function() {

							$('#submitFilterButton').click(function(){

								var selestado = $('#iselestado option:selected').val();

								document.location.href='index.php?controller=AdminGlsshipping&tab=Pedidos&token={$token}&date_from='+$('#date_from').val()+'&date_to='+$('#date_to').val()+'&selestado='+selestado;

							});

														

							var today =new Date();

							var todaySrc = today.getFullYear()+ '-' + (parseInt(today.getMonth() + 1)<10?'0'+(today.getMonth() + 1):(today.getMonth() + 1)) + '-' + today.getDate();

							var lastweek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 7);

							var lastweekSrc = lastweek.getFullYear()+ '-' + (parseInt(lastweek.getMonth() + 1)<10?'0':'') + (lastweek.getMonth() + 1) + '-' + (parseInt(lastweek.getDate())<10?'0':'') + lastweek.getDate();

							$('#submitweek').click(function(){

								var selestado = $('#iselestado option:selected').val();

								document.location.href='index.php?controller=AdminGlsshipping&tab=Pedidos&token={$token}&date_from='+lastweekSrc+'&date_to='+todaySrc+'&selestado='+selestado;

							});

							var lastmonth = new Date(today.getFullYear(), today.getMonth()-1, today.getDate());

							var lastmonthSrc = lastmonth.getFullYear()+ '-' + (parseInt(lastmonth.getMonth() + 1)<10?'0':'') + (lastmonth.getMonth() + 1) + '-' + (parseInt(lastmonth.getDate())<10?'0':'') + lastmonth.getDate();

							$('#submitmonth').click(function(){

								var selestado = $('#iselestado option:selected').val();

								document.location.href='index.php?controller=AdminGlsshipping&tab=Pedidos&token={$token}&date_from='+lastmonthSrc+'&date_to='+todaySrc+'&selestado='+selestado;

							});

							$('#submittoday').click(function(){

								var selestado = $('#iselestado option:selected').val();

								document.location.href='index.php?controller=AdminGlsshipping&tab=Pedidos&token={$token}&date_from='+todaySrc+'&date_to='+todaySrc+'&selestado='+selestado;

							});

							var dateFrom = $.datepicker.parseDate('yy-mm-dd',$("#date_from").val());

							var dateTo = $.datepicker.parseDate('yy-mm-dd',$("#date_to").val());

							$("#local_from").datepicker("option", "altField", "#date_from");

							$("#local_to").datepicker("option", "altField", "#date_to");

							if (dateFrom !== null){

								$("#local_from").datepicker("setDate", dateFrom);

							}

							if (dateTo !== null){

								$("#local_to").datepicker("setDate", dateTo);

							}

						});

					</script>

				

				</div>

			</form>

			</div>

			<div class="col-lg-4">

			{include file="$pagerTemplate" var=$paginacion}

			</div>

		</div>

    <div class="row">

        <div class="col-lg-12">



{if $pedidos}

        

            <table class="grid-table js-grid-table table" id="glsEnvios">

              <thead>

                <tr>

                  <th><input type="checkbox" onchange="$('#glsEnvios .row-selector input').prop('checked',$(this).prop('checked'));" ></th>

                  <th>{l s='ID' mod='glsshipping'}</th>

                  <th>{l s='Pedido' mod='glsshipping'}</th>

                  <th>{l s='Estado' mod='glsshipping'}</th>

                  <th>{l s='Cliente' mod='glsshipping'}</th>

                  <th>{l s='Total' mod='glsshipping'}</th>

                  <th>{l s='Fecha Pedido' mod='glsshipping'}</th>

                  <th>{l s='Fecha Envío' mod='glsshipping'}</th>

                  <th>{l s='Código Envío' mod='glsshipping'}</th>

                  <th>{l s='Seguimiento' mod='glsshipping'}</th>

                  <th>{l s='Etiquetas' mod='glsshipping'}</th>

                  <th>{l s='Acciones' mod='glsshipping'}</th>

                </tr>

              </thead>

			  <tfoot>

				<tr>

				<td>

					<div class="pull-left">

					<form class="form-inline pull-left" role="form" name="bulksend" id="bulksend" method="get" action="#" onsubmit="return sendBulkGLS();">

							<input type="submit" id="enviar" name="enviar" value="Enviar a GLS" title="Enviar a GLS" alt="Enviar a GLS" class="btn btn-primary btn-sm" />

							<input type="hidden" id="ids_order_envio" name="ids_order_envio" value="" />

							<input type="hidden" id="option" name="option" value="etiquetabulk" />

							<input type="hidden" id="token" name="token" value="{$token}" />

							<input type="hidden" id="tab" name="tab" value="Pedidos" />

                            <input type="hidden" id="controller" name="controller" value="AdminGlsshipping" />

						</div>

				   </form>

					</div>

					<div class="pull-left">

				   <form class="form-inline pull-left" role="form" name="mergetags" id="mergetags" method="get" action="#" onsubmit="return mergeTags();" target="_blank">

							<input type="submit" id="enviar" name="enviar" value="Generar etiquetas" title="Generar etiqueta" alt="Generar etiquetas" class="btn btn-primary btn-sm" />

							<input type="hidden" id="ids_order_envio" name="ids_order_envio" value="" />

							<input type="hidden" id="option" name="option" value="mergetags" />

							<input type="hidden" id="token" name="token" value="{$token}" />

							<input type="hidden" id="tab" name="tab" value="Pedidos" />

                            <input type="hidden" id="controller" name="controller" value="AdminGlsshipping" />

						</div>

				   </form>

					</div>

					</td>

				</tr>

			  </tfoot>

              <tbody>

               {foreach from=$pedidos key=o item=pedido}

                   <tr>

                       <td class="row-selector text-center">

                          <input type="checkbox" name="orderBox[]" value="{$pedido.id_envio_order}" class="glscheck noborder">

                       </td>

                       <td>{$pedido.id_order}</td>

                       <td>{$pedido.referencia}</td>

                       <td><span class="label-tooltip" data-toggle="tooltip" title="{$pedido.state_history}">{$pedido.estado} ({$pedido.current_state})</span></td>

                       <td>{$pedido.firstname} {$pedido.lastname}</td>

                       <td>{$pedido.total_paid_real}</td>

                       <td>{$pedido.date_add}</td>

                       <td>{$pedido.fecha}</td>

                       <td>{$pedido.codigo_envio}</td>

                       <td>

                            {if $pedido.url_track}

                                <a href="{$pedido.url_track}" title="Seguimiento de envío" target="_blank">

                                    <i class="icon-screenshot"></i>

                                </a>

                                <!--<a href="{$pedido.link_envio_mail}"><img src="{$path_img_email}" title="Enviar por Email Seguimiento al Cliente" alt="Enviar Seguimiento al Cliente" /></a>-->

                            {/if}

                       </td>

                       <td>

                           {if $pedido.link_etiqueta && $pedido.codigo_envio}

                               <a href="{$pedido.link_etiqueta}" id="barras" title="Ver códigos de barras" target="_blank">

                                    <i class="icon-barcode"></i>

                               </a>

                           {else}

                               &nbsp;

                           {/if}

                       </td>

                       <td>

                           {if !$pedido.codigo_envio && $pedido.valid}

							<a class="btn btn-default btn-sm" href="#" onclick="jQuery('#trform{$pedido.id_order}').slideToggle('slow');return false;">

								{l s='Enviar' mod='glsshipping'}

                               </a>

						   </td>

						 </tr>

						 <tr >

							<td colspan="12" class="text-center" style="padding:0;">

							<div id="trform{$pedido.id_order}" style="display:none;padding:20px;" >

                               <form class="form" role="form" name="Bultos" method="get" action="#">

                                    <div class="form-group">

										

									<div class="container">

									<div class="row">

									<div class="col-md-6">

									{if $pedido.iseuro}

										<input type="hidden" id="gls_bultos_user" name="gls_bultos_user" value="1" />

										<input type="hidden" id="gls_rcs_user" name="gls_rcs_user" value="" />

										<input type="hidden" id="gls_retorno" name="gls_retorno" value="0" />

										<input type="hidden" id="gls_vsec_user" name="gls_vsec_user" value="0" />

										

										<label class="" for="gls_peso_user">{l s='Peso' mod='glsshipping'}</label>

                                        <input type="text" id="gls_peso_user" name="gls_peso_user" title="Peso" alt="Peso" placeholder="Peso" class="form-control" data-toggle="tooltip" data-placement="top" value="{$pedido.peso}" />

										

										<label class="" for="gls_incoterm_user">{l s='Incoterm' mod='glsshipping'} </label>

                                        <select id="gls_incoterm_user" name="gls_incoterm_user" title="Incoterm" alt="Incoterm" class="form-control" data-toggle="tooltip" data-placement="top">

											<option value="0" {if $gls_incoterm==0} selected="selected"{/if}>-</option>

											<option value="10" {if $gls_incoterm==10} selected="selected"{/if}>Incoterm 10 DDP. COSTES REMITENTE: transporte,despacho,aranceles, impuestos. COSTES DESTINATARIO: no tiene costes</option>

											<option value="20" {if $gls_incoterm==20} selected="selected"{/if}>Incoterm 20 DAP. COSTES REMITENTE: transporte. COSTES DESTINATARIO: despacho, aranceles e impuestos</option>

											<option value="30" {if $gls_incoterm==30} selected="selected"{/if}>Incoterm 30 DDP, I.V.A. no pagado . COSTES REMITENTE: transporte,despacho, aranceles. COSTES DESTINATARIO: impuestos</option>

											<option value="40" {if $gls_incoterm==40} selected="selected"{/if}>Incoterm 40 DAP, despachado. COSTES REMITENTE: transporte, despacho. COSTES DESTINATARIO: aranceles, impuestos</option>

											<option value="50" {if $gls_incoterm==50} selected="selected"{/if}>Incoterm 50c DDP, bajo valor . COSTES REMITENTE: transporte, despacho. COSTES DESTINATARIO: no tiene costes</option>

											<option value="18" {if $gls_incoterm==18} selected="selected"{/if}>Incoterm 18 (DDP, VAT pre-registration). Delivered free, duty paid, VAT paid - the shipper does pay all costs, for importers no cost ocurr.</option>

										</select>

										

									</div>

									<div class="col-md-6">

									{else}

									

										<label class="" for="gls_retorno">{l s='Retorno' mod='glsshipping'} </label>

                                        <select id="gls_retorno" name="gls_retorno" title="Retorno" alt="Retorno" class="form-control" data-toggle="tooltip" data-placement="top">

											<option value="0"{if $gls_retorno==0} selected="selected"{/if}>{l s='Sin retorno' mod='glsshipping'}</option>

											<option value="1"{if $gls_retorno==1} selected="selected"{/if}>{l s='Retorno obligatorio' mod='glsshipping'}</option>

											<option value="2"{if $gls_retorno==2} selected="selected"{/if}>{l s='Retorno opcional' mod='glsshipping'}</option>

										</select>

										

										<label class="" for="gls_peso_user">{l s='Peso' mod='glsshipping'}</label>

                                        <input type="text" id="gls_peso_user" name="gls_peso_user" title="Peso" alt="Peso" placeholder="Peso" class="form-control" data-toggle="tooltip" data-placement="top" value="{$pedido.peso}" />

										

										<label class="" for="gls_bultos_user">{l s='Bultos' mod='glsshipping'}</label>

                                        <input type="number" id="gls_bultos_user" name="gls_bultos_user" title="Bultos" alt="Bultos" placeholder="Bultos" class="form-control" data-toggle="tooltip" data-placement="top" value="{$pedido.bultos}" />

										

									</div>

									<div class="col-md-6">

										<label class="" for="gls_rcs_user">{l s='RCS' mod='glsshipping'}</label>

                                        <select id="gls_rcs_user" name="gls_rcs_user" title="RCS" alt="RCS"  class="form-control" data-toggle="tooltip" data-placement="top" placeholder="RCS">

											<option value="">RCS</option>

											<option value="0"{if $gls_rcs=='0'} selected="selected"{/if}>{l s='No' mod='glsshipping'}</option>

											<option value="1"{if $gls_rcs=='1'} selected="selected"{/if}>{l s='Si' mod='glsshipping'}</option>

										</select>

										

										<label class="" for="gls_vsec_user">{l s='Valor asegurado' mod='glsshipping'}</label>

										<input type="text" id="gls_vsec_user" name="gls_vsec_user" class="form-control " placeholder="Valor asegurado"  value="{$gls_vsec}" />

									{/if}

										

										<label class="" for="gls_dorig_user">{l s='Dpto. origen' mod='glsshipping'}</label>

										<input type="text" id="gls_dorig_user" name="gls_dorig_user" class="form-control " placeholder="Dpto. origen"  value="{$gls_dorig}" />

                                    </div>

                                    </div>

                                    </div>

									<div class="container">

									<div class="row">

									<div class="col-md-12">

										<p>&nbsp;</p>

										<input type="submit" id="enviar" name="enviar" value="{l s='Enviar' mod='glsshipping'}" title="Generar etiqueta" alt="Generar etiqueta" class="btn btn-primary" />

                                        <input type="hidden" id="id_order_envio" name="id_order_envio" value="{$pedido.id_envio_order}" />

                                        <input type="hidden" id="option" name="option" value="etiqueta" />

                                        <input type="hidden" id="token" name="token" value="{$token}" />

                                        <input type="hidden" id="tab" name="tab" value="Pedidos" />

                                        <input type="hidden" id="controller" name="controller" value="AdminGlsshipping" />



                                    </div>

                                    </div>

                                    </div>

                                    </div>

                               </form>

                           {elseif $pedido.valid && $pedido.current_state <= 0}

                              <a class="btn btn-default btn-sm" href="#" onclick="jQuery('#trform{$pedido.id_order}').slideToggle('slow');return false;">

                                {l s='Modificar envío' mod='glsshipping'}

                               </a>

                               </td>

						 </tr>

						 <tr >

							<td colspan="12" class="text-center" style="padding:0;">

							<div id="trform{$pedido.id_order}" style="display:none;padding:20px;" >

							

								<div class="alert alert-info">

									<p>{l s='Va a anular la expedicion y crear una nueva, por lo que debe reetiquetar los todos bultos de la expedicion.' mod='glsshipping'}</p>

								</div>

                               <form class="form" role="form" name="Bultos" method="get" action="#">

                                    <div class="form-group">

										

									<div class="container">

									<div class="row">

									<div class="col-md-6">

									{if $pedido.iseuro}

										<input type="hidden" id="gls_bultos_user" name="gls_bultos_user" value="1" />

										<input type="hidden" id="gls_peso" name="gls_bultos_user" value="1" />

										<input type="hidden" id="gls_rcs_user" name="gls_rcs_user" value="" />

										<input type="hidden" id="gls_retorno" name="gls_retorno" value="0" />

										<input type="hidden" id="gls_vsec_user" name="gls_vsec_user" value="0" />

										<label class="" for="gls_incoterm_user">{l s='Incoterm' mod='glsshipping'} </label>

                                        <select id="gls_incoterm_user" name="gls_incoterm_user" title="Incoterm" alt="Incoterm" class="form-control" data-toggle="tooltip" data-placement="top">

											<option value="0" {if $gls_incoterm==0} selected="selected"{/if}>-</option>

											<option value="10" {if $gls_incoterm==10} selected="selected"{/if}>Incoterm 10 DDP. COSTES REMITENTE: transporte,despacho,aranceles, impuestos. COSTES DESTINATARIO: no tiene costes</option>

											<option value="20" {if $gls_incoterm==20} selected="selected"{/if}>Incoterm 20 DAP. COSTES REMITENTE: transporte. COSTES DESTINATARIO: despacho, aranceles e impuestos</option>

											<option value="30" {if $gls_incoterm==30} selected="selected"{/if}>Incoterm 30 DDP, I.V.A. no pagado . COSTES REMITENTE: transporte,despacho, aranceles. COSTES DESTINATARIO: impuestos</option>

											<option value="40" {if $gls_incoterm==40} selected="selected"{/if}>Incoterm 40 DAP, despachado. COSTES REMITENTE: transporte, despacho. COSTES DESTINATARIO: aranceles, impuestos</option>

											<option value="50" {if $gls_incoterm==50} selected="selected"{/if}>Incoterm 50c DDP, bajo valor . COSTES REMITENTE: transporte, despacho. COSTES DESTINATARIO: no tiene costes</option>

											<option value="18" {if $gls_incoterm==18} selected="selected"{/if}>Incoterm 18 (DDP, VAT pre-registration). Delivered free, duty paid, VAT paid - the shipper does pay all costs, for importers no cost ocurr.</option>

										</select>

									{else}

									

										<label class="" for="gls_retorno">{l s='Retorno' mod='glsshipping'} </label>

                                        <select id="gls_retorno" name="gls_retorno" title="Retorno" alt="Retorno" class="form-control" data-toggle="tooltip" data-placement="top">

											<option value="0"{if $pedido.retorno==0} selected="selected"{/if}>{l s='Sin retorno' mod='glsshipping'}</option>

											<option value="1"{if $pedido.retorno==1} selected="selected"{/if}>{l s='Retorno obligatorio' mod='glsshipping'}</option>

											<option value="2"{if $pedido.retorno==2} selected="selected"{/if}>{l s='Retorno opcional' mod='glsshipping'}</option>

										</select>

										

										<label class="" for="gls_peso_user">{l s='Peso' mod='glsshipping'}</label>

                                        <input type="text" id="gls_peso_user" name="gls_peso_user" title="Peso" alt="Peso" placeholder="Peso" class="form-control" data-toggle="tooltip" data-placement="top" value="{$pedido.peso}" />

										

										<label class="" for="gls_bultos_user">{l s='Bultos' mod='glsshipping'}</label>

                                        <input type="number" id="gls_bultos_user" name="gls_bultos_user" title="Bultos" alt="Bultos" placeholder="Bultos" class="form-control" data-toggle="tooltip" data-placement="top" value="{$pedido.bultos}" />

										

									</div>

									<div class="col-md-6">

										<label class="" for="gls_rcs_user">{l s='RCS' mod='glsshipping'}</label>

                                        <select id="gls_rcs_user" name="gls_rcs_user" title="RCS" alt="RCS"  class="form-control" data-toggle="tooltip" data-placement="top" placeholder="RCS">

											<option value="">RCS</option>

											<option value="0"{if $pedido.rcs=='0'} selected="selected"{/if}>{l s='No' mod='glsshipping'}</option>

											<option value="1"{if $pedido.rcs=='1'} selected="selected"{/if}>{l s='Si' mod='glsshipping'}</option>

										</select>

										

										<label class="" for="gls_vsec_user">{l s='Valor asegurado' mod='glsshipping'}</label>

										<input type="text" id="gls_vsec_user" name="gls_vsec_user" class="form-control " placeholder="Valor asegurado"  value="{$pedido.vsec}" />

									{/if}

										

										<label class="" for="gls_dorig_user">{l s='Dpto. origen' mod='glsshipping'}</label>

										<input type="text" id="gls_dorig_user" name="gls_dorig_user" class="form-control " placeholder="Dpto. origen"  value="{$pedido.dorig}" />

                                    </div>

                                    </div>

									

									<div class="row">

									<div class="col-md-12">

										<label class="" for="gls_observaciones_user">{l s='Observaciones' mod='glsshipping'}</label>

										<textarea id="gls_observaciones_user" name="gls_observaciones_user" class="form-control " placeholder="Observaciones">{$pedido.observaciones}</textarea>

                                    </div>

                                    </div>

									

                                    </div>

									<div class="container">

									<div class="row">

									<div class="col-md-12">

										<p>&nbsp;</p>

										<input type="submit" id="enviar" name="enviar" value="{l s='Enviar' mod='glsshipping'}" title="Generar etiqueta" alt="Generar etiqueta" class="btn btn-primary" />

                                        <input type="hidden" id="id_order_envio" name="id_order_envio" value="{$pedido.id_envio_order}" />

                                        <input type="hidden" id="option" name="option" value="reetiqueta" />

                                        <input type="hidden" id="token" name="token" value="{$token}" />

                                        <input type="hidden" id="tab" name="tab" value="Pedidos" />

                                        <input type="hidden" id="controller" name="controller" value="AdminGlsshipping" />



                                    </div>

                                    </div>

                                    </div>

									

									

                                    </div>

                               </form>

                           {/if}

                       </td>

                   </tr>

               {/foreach}



              </tbody>

            </table>

            

{else}

    <div class="alert alert-warning">

        <h3>No hay ordenes de pedido para GLS</h3>

    </div>

{/if}            

            

            

        </div>

    </div>

    </div>

	<div role="tabpanel" class="tab-pane{if $activetab == 'Manifest'} active{/if}" id="Manifiest">

		<div class="row">

			<div class="col-lg-12">&nbsp;</div>

		</div>

		<div class="row">

			<form action="#" method="post">

			<div class="col-lg-6">

				<div class="date_range row">

				

					<div class="input-group fixed-width-md pull-left">

						<input type="text" class="filter datepicker date-input form-control" id="local_0" name="local[0]"  placeholder="{l s='From' mod='glsshipping'}" />

						<input type="hidden" id="date_0" name="date[0]" value="{$date_0}">

						<span class="input-group-addon">

							<i class="icon-calendar"></i>

						</span>

					</div>

					<div class="input-group fixed-width-md pull-left">

						<input type="text" class="filter datepicker date-input form-control" id="local_1" name="local[1]"  placeholder="{l s='To' mod='glsshipping'}" />

						<input type="hidden" id="date_1" name="date[1]" value="{$date_1}">

						<span class="input-group-addon">

							<i class="icon-calendar"></i>

						</span>

					</div>

					<span class="pull-left">

					<button type="button"  class="btn btn-default" onclick="document.location.href='index.php?controller=AdminGlsshipping&tab=Manifest&token={$token}&date_0='+$('#date_0').val()+'&date_1='+$('#date_1').val();"

					>

						<i class="icon-search"></i> {l s='Search' mod='glsshipping'}

					</button>

					<button type="submit" id="submitFilterButton" name="submitFilter" class="btn btn-default"

					onclick="document.location.href='index.php?controller=AdminGlsshipping&tab=Manifest&token={$token}&date_0='+$('#date_0').val()+'&date_1='+$('#date_1').val()+'&grouped=1';"

					>

						<i class="icon-search"></i> {l s='Agrupado' mod='glsshipping'}

					</button>



				</span>

					<script>

						$(function() {

							var dateStart = $.datepicker.parseDate('yy-mm-dd',$("#date_0").val());

							var dateEnd = $.datepicker.parseDate('yy-mm-dd',$("#date_1").val());

							$("#local_0").datepicker("option", "altField", "#date_0");

							$("#local_1").datepicker("option", "altField", "#date_1");

							if (dateStart !== null){

								$("#local_0").datepicker("setDate", dateStart);

							}

							if (dateEnd !== null){

								$("#local_1").datepicker("setDate", dateEnd);

							}

						});

					</script>

				

				</div>

			</div>

			<div class="col-lg-6">

			{if $mpedidos}

			{if $grouped}

			{literal}

				<span class="pull-right">

					<button type="button" class="btn btn-lg btn-primary"

					onclick="$('#glsEnvios2g').tableExport({type:'pdf',escape:'false',pdfFontSize:10});"

					>PDF</button>

				

					<button type="button" class="btn btn-lg btn-primary"

					onclick="$('#glsEnvios2g').tableExport({type:'csv',escape:'false',htmlContent:'true'});"

					>Excel</button>

				

					<button type="button" class="btn btn-lg btn-primary"

					onclick="$('#glsEnvios2g').print({addGlobalStyles : true,prepend:'<p><img src=\'{/literal}{$module_base}{literal}logo.png\'/></p><table class=\'glsEnvios2\'><thead><tr><th>Manifiesto de carga</th></tr><tr><th>{/literal}{$today}{literal}</th></tr></thead></table>'});"

					>{/literal}{l s='Imprimir' mod='glsshipping'}{literal}</button>

				</span>

			{/literal}

			{else}

			{literal}

				<span class="pull-right">

					<button type="button" class="btn btn-lg btn-primary"

					onclick="$('#glsEnvios2').tableExport({type:'pdf',escape:'false',pdfFontSize:10});"

					>PDF</button>

				

					<button type="button" class="btn btn-lg btn-primary"

					onclick="$('#glsEnvios2').tableExport({type:'csv',escape:'false',htmlContent:'true'});"

					>Excel</button>

				

					<button type="button" class="btn btn-lg btn-primary"

					onclick="$('#glsEnvios2').print({addGlobalStyles : true,prepend:'<p><img src=\'{/literal}{$module_base}{literal}logo.png\'/></p><table class=\'glsEnvios2\'><thead><tr><th>Manifiesto de carga</th></tr><tr><th>{/literal}{$today}{literal}</th></tr></thead></table>'});"

					>{/literal}{l s='Imprimir' mod='glsshipping'}{literal}</button>

				</span>

			{/literal}

			{/if}

			{/if}

			</div>

			</form>

		</div>

		<div class="row">

			{if $grouped}

            <table class="table text-center grid-table js-grid-table" id="glsEnvios2g">

			<tbody>

				<tr class="head">

					<td>{l s='Cod. barras' mod='glsshipping'}</td>

					<td>{l s='Pedido' mod='glsshipping'}</td>

					<td>{l s='Referencia' mod='glsshipping'}</td>

					<td>{l s='Referencia N' mod='glsshipping'}</td>

					<td>{l s='Bultos' mod='glsshipping'}</td>

					<td>{l s='Destinatario' mod='glsshipping'}</td>

					<td>{l s='Dirección' mod='glsshipping'}</td>

					<td>{l s='CP' mod='glsshipping'}</td>

					<td>{l s='Población' mod='glsshipping'}</td>

				</tr>

				{foreach from=$mpedidos key=m item=mpedido}

				<tr>

					<td>{$mpedido.codigo_envio}</td>

					<td>{$mpedido.id_order}</td>

					<td>{$mpedido.reference}</td>

					<td>{$mpedido.num_albaran}</td>

					<td>{$mpedido.bultos}</td>

					<td>{$mpedido.firstname} {$mpedido.lastname} {$mpedido.company}</td>

					<td>{$mpedido.address1} {$mpedido.address2}</td>

					<td>{$mpedido.postcode} </td>

					<td>{$mpedido.city}</td>

				</tr>

				<tr class="separator">

					<td colspan="9">

					</td>

				</tr>

				{/foreach}

				<tr class="separator">

					<td colspan="4">{l s='TOTAL BULTOS' mod='glsshipping'}</td>

					<td>{$totalbultos}</td>

					<td colspan="4"></td>

				</tr>

			{else}

			<table class="grid-table js-grid-table table" id="glsEnvios2">

			<thead>

			<tr>

			<th></th>

			<th></th>

			<th></th>

			</tr>

			</thead>

			<tbody>

			{foreach from=$mpedidos key=m item=mpedido}

				<tr class="head">

					<td>{l s='Codigo GLS:' mod='glsshipping'} {$mpedido.codigo_envio}</td>

					<td>{l s='Referencia:' mod='glsshipping'} {$mpedido.reference}</td>

					<td>{if $mpedido.num_albaran != ''}{l s='Referencia N:' mod='glsshipping'} {$mpedido.num_albaran}{/if}</td>

					<td>{l s='Total:' mod='glsshipping'} {$mpedido.total_paid_real}</td>

				</tr>

				<tr>

				<td></td>

					<td>

						{l s='Información de la entrega:' mod='glsshipping'} 

					</td>

				<td></td>

				<td></td>

				</tr>

				<tr>

				<td></td>

					<td>

						{$mpedido.firstname} {$mpedido.lastname} {$mpedido.company}

					</td>

					<td></td>

					<td></td>

				</tr>

				<tr>

				<td></td>

					<td>

						{$mpedido.address1} {$mpedido.address2}

					</td>

					<td></td>

					<td></td>

				</tr>

				<tr>

				<td></td>

					<td>

						{$mpedido.postcode} - {$mpedido.city}

					</td>

					<td></td>

					<td></td>

				</tr>

				<tr>

				<td></td>

					<td>

						{if $mpedido.phone_mobile}{$mpedido.phone_mobile}{else}{$mpedido.phone}{/if}

					</td>

					<td></td>

					<td></td>

				</tr>

				<tr class="separator">

					<td>

					</td>

					<td></td>

					<td></td>

					<td></td>

				</tr>

				{/foreach}

				{/if}

			</tbody>

			</table>

		</div>

    </div>

		<div role="tabpanel" class="tab-pane{if $activetab == 'Collect'} active{/if}" id="Collect">

		<div class="row">

			<div class="col-lg-12">

			<p>&nbsp;</p>

			</div>

		</div>

		<div class="row">

			<div class="col-lg-12">

				{if $validCollect && $dateCollect > 0}

					<p>{l s='Última solicitud de recogida:' mod='glsshipping'} {$dateCollect|date_format:"%d/%m/%y"}</p>			

					<p><a class="btn btn-default" href="index.php?controller=AdminGlsshipping&tab=Collect&option=callcollect&token={$smarty.get.token}">{l s='Solicitar recogida' mod='glsshipping'}</a></p>

				{elseif $validCollect}

					<p><a class="btn btn-default" href="index.php?controller=AdminGlsshipping&tab=Collect&option=callcollect&token={$smarty.get.token}">{l s='Solicitar recogida' mod='glsshipping'}</a></p>

				{else}

					<p>{l s='Última solicitud de recogida:' mod='glsshipping'} {$dateCollect|date_format:"%d/%m/%y"}</p>

					<p>{l s='Debe esperar hasta próximo día hábil para solicitar de nuevo la recogida.' mod='glsshipping'}</p>			

					

				{/if}

			</div>

		</div>

    </div>

    </div>



</div>

		</div>

    </div>

    </div>

