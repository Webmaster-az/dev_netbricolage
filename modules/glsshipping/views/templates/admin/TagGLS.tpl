<script type="text/javascript">
	$(document).ready(function() {
		$( "#formulario" ).load(document.formulario1.submit());
	});
</script>

<div class="content bootstrap">
    <ol class="breadcrumb">
        <li>{$volver}</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"> <i class="icon-barcode"></i> PDF</div>
                <div class="panel-body">
                    <p>Pulsando en el siguiente enlace / botón podrá visualizar las <strong>etiquetas en formato PDF</strong> de su envío:</p>
                    <a href="{$link_etiqueta}" class="btn btn-primary" title="Etiqueta PDF" target="_blank">PDF ></a>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="icon-envelope"></i>  {l s='OBSERVACIONES' mod='glsshipping'}</div>
                <div class="panel-body">
                        {if $error}
                            <div class="alert alert-danger">
                                <p>
                                    {$error}
                                </p>
                            </div>
                        {elseif $resultado}
                            <div class="alert alert-success">
                                <p>
                                    {$resultado}
                                </p>
                            </div>
                        {/if}
                </div>
            </div>
        </div>
    </div>
</div>
