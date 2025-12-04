{if $version gte 1.6}
<!-- Versión >= 1.6-->
<style type="text/css">
    #gls tr td {
        line-height:35px!important;
    }
    #gls tr td i {
        font-size: 16px;
    }
    span.ba {
        color: #00aff0;
        cursor: pointer;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="card mt-2 panel">
            <div class="card-header panel-heading">
                <h3 class="card-header-title">
					<i class="icon-truck"></i>
					{$gls_lopeta} - <small>{$gls_version}</small>
				</h3>
            </div>
			<div class="card-body">
				{if $gls_state gte 4}
				<div class="alert alert-success">
					<p>{$gls_success_msg}</p>
				</div>
				{/if}
				<table class="table text-center" id="gls">
					<thead>
						<tr>
							<th class="text-center">{l s='Pedido' mod='glsshipping'}</th>
							<th class="text-center">{l s='Nº Envío' mod='glsshipping'}</th>
							<th class="text-center">{l s='Código de envío' mod='glsshipping'}</th>
							<th class="text-center">PDF</th>
							<th class="text-center">{l s='Seguimiento' mod='glsshipping'}</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{$referencia}</td>
							<td>{$gls_n_envio}</td>
							<td>{$num_albaran}</td>
							<td><span class="ba" onclick="window.open('{$gls_pdf_down}');" title="{$gls_pdf_txt}">
								<span class="material-icons">
								get_app
								</span>
							</span></td>
							<td><span class="ba" onclick="window.open('{$gls_seguimiento_envio_url}');" title="{$gls_seguimiento_envio}">
								<span class="material-icons">
								track_changes
								</span>
							</span></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
    </div>
</div>
{else}
<!-- Versión < 1.6-->
<fieldset class="space" style="clear:both">
    <legend><img src="../modules/glsshipping/logo_gls.JPG" title="{$gls_lopeta}" alt="{$gls_lopeta}" width="80" class="middle" /> {$gls_lopeta}</legend>
    {if $gls_state gte 4}
        <div class="alert alert-success">
            <p>{$gls_success_msg}</p>
        </div>
    {/if}
    <table id="gls" class="table" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th class="text-center">{l s='Pedido' mod='glsshipping'}</th>
            <th class="text-center">{l s='Nº Envío' mod='glsshipping'}</th>
            <th class="text-center">{l s='Código de envío' mod='glsshipping'}</th>
            <th class="text-center">PDF</th>
            <th class="text-center">{l s='Seguimiento' mod='glsshipping'}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{$gls_pedido}</td>
            <td>{$gls_n_envio}</td>
            <td>{$gls_codigo_envio}</td>
            <td><a href="{$gls_pdf_down}" title="{$gls_pdf_txt}" target="_blank">{$gls_pdf_txt}</a></td>
            <td><a href="{$gls_seguimiento_envio_url}" title="{$gls_seguimiento_envio}" target="_blank">{$gls_seguimiento_envio}</a></td>
        </tr>
        </tbody>
    </table>
    <p>&nbsp;</p>
    <small>{$gls_version}</small>
</fieldset>
{/if}