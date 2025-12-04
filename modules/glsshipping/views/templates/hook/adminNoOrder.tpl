{if $version gte 1.6}

<!-- Versión >= 1.6-->

<div class="row">

    <div class="col-lg-7">

        <div class="card mt-2 panel">

            <div class="card-header panel-heading">

                <h3 class="card-header-title">

					<i class="icon-truck"></i>

					{$gls_lopeta}

				</h3>

            </div>

			

			<div class="card-body">

            <div class="alert alert-warning">

                <p>{$mensaje}</p>

            </div>

            <div class="alert alert-info">

                <p>{$bultos_info}</p>

            </div>

            <small>{$gls_version}</small>

        </div>

        </div>

    </div>

</div>

{else}

<!-- Versión < 1.6-->

<fieldset class="space" style="clear:both">

    <legend><img src="../modules/glsshipping/logo_gls.JPG" title="{$gls_lopeta}" alt="{$gls_lopeta}" width="80" class="middle" /> {$gls_lopeta}</legend>

    <p>{$mensaje}</p>

    <p>{$bultos_info}</p>

    <small>{$gls_version}</small>

</fieldset>

{/if}