<div class="card mt-2 panel">
    <div class="card-header panel-heading">
        <h3 class="card-header-title">
			<i class="icon-truck"></i>
			{$gls_lopeta} - <small>{$gls_version}</small>
		</h3>
    </div>			

	<div class="card-body">
    {*  <div class="alert alert-warning">
        <p>{$mensaje}</p> 
    </div> *}

    {* <div class="alert alert-info">
        <p>{$bultos_info}</p>
        <p>{$bultos_info_b}</p>
    </div> *}

        <form id="gls_bultos" name="gls_bultos" title="" action="index.php" method="get" class="form-horizontal well hidden-print">
            <div class="row">
                {if $isEurobusinessparcel}
                    <input type="hidden" name="gls_retorno" value="0" />
                    <input type="hidden" name="gls_rcs_user" value="" />
                    <input type="hidden" name="gls_vsec_user" value="" />
                    <input type="hidden" name="gls_bultos_user" value="1" />

                    <div class="col-lg-6">
                        {* <div class="form-group">
                            <label class="control-label col-lg-3" for="gls_retorno">{l s='Dep. origen' mod='glsshipping'}</label>

                            <div class="col-lg-9">
                                <input type="text" id="gls_dorig_user" name="gls_dorig_user" class="form-control " value="{$gls_dorig}" />
                            </div>
                        </div> *}

                        <div class="form-group">
                            <label for="gls_peso_user" class="control-label col-lg-3">
                                {l s='Peso:' mod='glsshipping'}
                            </label>

                            <div class="col-lg-9">
                                <input type="number" title="{$peso_input_txt}" alt="{$peso_input_txt}" id="gls_peso_user" name="gls_peso_user" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="form-control " value="{$gls_def_peso}" />
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        {* <div class="form-group">
                            <label for="gls_incoterm_user" class="control-label col-lg-3">
                                {l s='Incoterm:' mod='glsshipping'}
                            </label>

                            <div class="col-lg-9">
                                <select id="gls_incoterm_user" name="gls_incoterm_user" class="form-control ">
                                    <option value="0" {if $gls_incoterm==0} selected="selected"{/if}>-</option>
                                    <option value="10" {if $gls_incoterm==10} selected="selected"{/if}>Incoterm 10 DDP. COSTES REMITENTE: transporte,despacho,aranceles, impuestos. COSTES DESTINATARIO: no tiene costes</option>
                                    <option value="20" {if $gls_incoterm==20} selected="selected"{/if}>Incoterm 20 DAP. COSTES REMITENTE: transporte. COSTES DESTINATARIO: despacho, aranceles e impuestos</option>
                                    <option value="30" {if $gls_incoterm==30} selected="selected"{/if}>Incoterm 30 DDP, I.V.A. no pagado . COSTES REMITENTE: transporte,despacho, aranceles. COSTES DESTINATARIO: impuestos</option>
                                    <option value="40" {if $gls_incoterm==40} selected="selected"{/if}>Incoterm 40 DAP, despachado. COSTES REMITENTE: transporte, despacho. COSTES DESTINATARIO: aranceles, impuestos</option>
                                    <option value="50" {if $gls_incoterm==50} selected="selected"{/if}>Incoterm 50c DDP, bajo valor . COSTES REMITENTE: transporte, despacho. COSTES DESTINATARIO: no tiene costes</option>
                                    <option value="18" {if $gls_incoterm==18} selected="selected"{/if}>Incoterm 18 (DDP, VAT pre-registration). Delivered free, duty paid, VAT paid - the shipper does pay all costs, for importers no cost ocurr.</option>
                                </select>
                            </div>
                        </div> *}

                {else}

                    {*<div class="col-lg-6">
                        <div class="form-group">
                            <label class="control-label col-lg-3" for="gls_retorno">{l s='Retorno' mod='glsshipping'}</label>

                            <div class="col-lg-9">
                                <select id="gls_retorno" name="gls_retorno" title="Retorno" alt="Retorno" class="form-control" data-toggle="tooltip" data-placement="top">
                                    <option value="0"{if $gls_retorno==0} selected="selected"{/if}>{l s='Sin retorno' mod='glsshipping'}</option>
                                    <option value="1"{if $gls_retorno==1} selected="selected"{/if}>{l s='Retorno obligatorio' mod='glsshipping'}</option>
                                    <option value="2"{if $gls_retorno==2} selected="selected"{/if}>{l s='Retorno opcional' mod='glsshipping'}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-3" for="gls_retorno">RCS</label>

                            <div class="col-lg-9">
                                <select name="gls_rcs_user" class="form-control" id="gls_rcs_user">
                                    <option></option>
                                    <option value="0" {if $gls_rcs=='0'} selected="selected"{/if}>{l s='No' mod='glsshipping'}</option>
                                    <option value="1" {if $gls_rcs=='1'} selected="selected"{/if}>{l s='Si' mod='glsshipping'}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-lg-3" for="gls_retorno">{l s='Valor asegurado' mod='glsshipping'}</label>
                            
                            <div class="col-lg-9">
                                <input type="text" id="gls_vsec_user" name="gls_vsec_user" class="form-control " value="{$gls_vsec}" />
                            </div>
                        </div>
                    </div>*}

                    <div class="col-lg-12">
                        {*<div class="form-group">
                            <label class="control-label col-lg-3" for="gls_retorno">{l s='Dep. origen' mod='glsshipping'}</label>

                            <div class="col-lg-9">
                                <input type="text" id="gls_dorig_user" name="gls_dorig_user" class="form-control " value="{$gls_dorig}" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gls_bultos_user" class="control-label col-lg-3">
                                {$bultos_message}:
                            </label>

                            <div class="col-lg-9">
                                <input type="number" title="{$bultos_input_txt}" alt="{$bultos_input_txt}" id="gls_bultos_user" name="gls_bultos_user" pattern="[0-9]" class="form-control " value="{$gls_bultos}" />
                            </div>
                        </div>*}

                        <div class="form-group">
                            <label for="gls_peso_user" class="control-label col-lg-3">
                                {l s='Peso:' mod='glsshipping'}
                            </label>

                            <div class="col-lg-9">
                                <input type="number" title="{$peso_input_txt}" alt="{$peso_input_txt}" id="gls_peso_user" name="gls_peso_user" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" class="form-control " value="{$gls_def_peso}" />
                            </div>
                        </div>
                    </div>
                {/if}
            
            </div>
            <div class="col-lg-12" style="text-align:right;">
                    <div>
                        <input type="hidden" name="controller" value="{$bultos_controller}" />
                        <input type="hidden" name="id_order" value="{$bultos_id_order}" />
                        <input type="hidden" name="regenerar" value="{$bultos_regenerar}" />
                        <input type="hidden" name="vieworder" value="" />
                        <input type="hidden" name="token" value="{$bultos_token}" />
                        <input type="submit" title="{$bultos_btn}" alt="{$bultos_btn}" value="{$bultos_btn}" class="btn btn-primary" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>