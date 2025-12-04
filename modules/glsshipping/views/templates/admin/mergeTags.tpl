
<div class="content bootstrap">
<div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"> <i class="icon-barcode"></i>  ETIQUETAS PDF</div>
                <div class="panel-body">
                {if empty($envios)}
                <p>No hay etiquetas de envío generadas.</p>
                {else}
					<p>Etiquetas generadas para los envíos:  {$envios}</p>
					<a class="btn btn-primary" target="_blank" href="../modules/glsshipping/PDF/GLS_{$timestamp}.pdf">Ver etiquetas generadas</a>
					{/if}
				</div>
            </div>
        </div>
   </div>
</div>