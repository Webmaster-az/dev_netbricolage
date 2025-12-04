{*
* Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
* 
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*}
<div class="panel">
<h3>{if $avec_bootstrap}<i class="icon-user-md"></i>{else}<img width="16" height="16" src="../img/admin/binoculars.png" alt=""/>{/if}&nbsp;{l s='Configuration' mod='pagecache'} ({$diagnostic_count|escape:'html':'UTF-8'})</h3>
<form id="pagecache_form_diagnostic" action="{$request_uri|escape:'html':'UTF-8'}" method="post">
    <input type="hidden" name="submitModule" value="true"/>
    <input type="hidden" name="pctab" value="diagnostic"/>
    <fieldset>
        {if $diagnostic_count == 0}
            <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/check.png" alt="ok" width="24" height="24"/> {l s='Everything is good!' mod='pagecache'}
        {/if}
        {foreach $diagnostic['error'] as $diagMsg}
            {if $avec_bootstrap}
                <div class="bootstrap"><div class="alert alert-danger" style="display: block;">&nbsp;{$diagMsg['msg']|escape:'html':'UTF-8'}.{if array_key_exists('link', $diagMsg)} <a href="{$diagMsg['link']|escape:'html':'UTF-8'}">{$diagMsg['link_title']|escape:'html':'UTF-8'}.</a>{/if}</div></div>
            {else}
                <div class="error clear" style="display: block;">&nbsp;{$diagMsg['msg']|escape:'html':'UTF-8'}.{if array_key_exists('link', $diagMsg)} <a href="{$diagMsg['link']|escape:'html':'UTF-8'}">{$diagMsg['link_title']|escape:'html':'UTF-8'}.</a>{/if}</div>
            {/if}
        {/foreach}
        {foreach $diagnostic['warn'] as $diagMsg}
            {if $avec_bootstrap}
                <div class="bootstrap"><div class="alert alert-warning" style="display: block;">&nbsp;{$diagMsg['msg']|escape:'html':'UTF-8'}.{if array_key_exists('link', $diagMsg)} <a href="{$diagMsg['link']|escape:'html':'UTF-8'}">{$diagMsg['link_title']|escape:'html':'UTF-8'}.</a>{/if}</div></div>
            {else}
                <div class="warn clear" style="display: block;">&nbsp;{$diagMsg['msg']|escape:'html':'UTF-8'}.{if array_key_exists('link', $diagMsg)} <a href="{$diagMsg['link']|escape:'html':'UTF-8'}">{$diagMsg['link_title']|escape:'html':'UTF-8'}.</a>{/if}</div>
            {/if}
        {/foreach}
        {foreach $diagnostic['info'] as $diagMsg}
            {if $avec_bootstrap}
                <div class="bootstrap"><div class="alert alert-info" style="display: block;">&nbsp;{$diagMsg['msg']|escape:'html':'UTF-8'}.{if array_key_exists('link', $diagMsg)} <a href="{$diagMsg['link']|escape:'html':'UTF-8'}">{$diagMsg['link_title']|escape:'html':'UTF-8'}.</a>{/if}</div></div>
            {else}
                <div class="hint clear" style="display: block;">&nbsp;{$diagMsg['msg']|escape:'html':'UTF-8'}.{if array_key_exists('link', $diagMsg)} <a href="{$diagMsg['link']|escape:'html':'UTF-8'}">{$diagMsg['link_title']|escape:'html':'UTF-8'}.</a>{/if}</div>
            {/if}
        {/foreach}
    </fieldset>
</form>
</div>

<div class="panel">
    <h3>{if $avec_bootstrap}<i class="icon-time"></i>{else}<img width="16" height="16" src="../img/admin/time.gif" alt=""/>{/if}&nbsp;{l s='Performance analysis' mod='pagecache'}</h3>
    {if $avec_bootstrap}
        <div class="bootstrap"><div class="alert alert-info" style="display: block;">
                {l s='This analysis will show you how the time to first byte (TTFB) has been improved on your site thanks to the cache' mod='pagecache'}
            </div></div>
    {else}
        <div class="hint clear"><div>
                {l s='This analysis will show you how the time to first byte (TTFB) has been improved on your site thanks to the cache' mod='pagecache'}
            </div></div>
    {/if}
    {if isset($url_home)}
        {include file='./pagecache-speed-analyse.tpl'}
    {else}
        {if $avec_bootstrap}
            <div class="bootstrap"><div class="alert alert-warning" style="display: block;">&nbsp;{l s='This tools is not available if the module or if the shop is not enabled' mod='pagecache'}</div></div>
        {else}
            <div class="warn clear" style="display: block;">&nbsp;{l s='This tools is not available if the module or if the shop is not enabled' mod='pagecache'}</div>
        {/if}

    {/if}
</div>

<div class="panel">
    <h3>{if $avec_bootstrap}<i class="icon-exclamation-triangle"></i>{else}<img width="16" height="16" src="../img/admin/error.png" alt=""/>{/if}&nbsp;{l s='Slower modules' mod='pagecache'}</h3>

    {if $avec_bootstrap}
        <div class="bootstrap">
            <div class="alert alert-info" style="display: block;">
                {l s='This table shows you the slower modules that could slow down your shop' mod='pagecache'}
            </div>
            {if $pagecache_profiling_not_available}
                <div class="alert alert-warning" style="display: block;">
                    &nbsp;{l s='This tools is only available from Prestashop 1.7' mod='pagecache'}
                </div>
            {else}
                {if !$module_enabled}
                    <div class="alert alert-warning" style="display: block;">
                        &nbsp;{l s='This tools is not available if the module or if the shop is not enabled' mod='pagecache'}
                    </div>
                {/if}
                {if $pagecache_profiling_max_reached}
                    <div class="alert alert-warning" style="display: block;">
                        {l s='To preserve performances, the profiling has been suspended because it reaches the maximum number of records' mod='pagecache'}: {$pagecache_profiling_max|escape:'html':'UTF-8'}
                    </div>
                {/if}
            {/if}
        </div>
    {else}
        <div class="hint clear">
            <div>
                {l s='This table shows you the slower modules that could slow down your shop' mod='pagecache'}
            </div>
        </div>
        {if $pagecache_profiling_not_available}
            <div class="warn clear" style="display: block;">&nbsp;{l s='This tools is only available from Prestashop 1.7' mod='pagecache'}</div>
        {else}
            {if !$module_enabled}
                <div class="warn clear" style="display: block;">&nbsp;{l s='This tools is not available if the module or if the shop is not enabled' mod='pagecache'}</div>
            {/if}
            {if $pagecache_profiling_max_reached}
                <div class="warn clear" style="display: block;">&nbsp;{l s='To preserve performances, the profiling has been suspended because it reaches the maximum number of records' mod='pagecache'}: {$pagecache_profiling_max|escape:'html':'UTF-8'}</div>
            {/if}
        {/if}
    {/if}
    {if $module_enabled && !$pagecache_profiling_not_available}
        <form id="pagecache_form_profiling" action="{$request_uri|escape:'html':'UTF-8'}" method="post" class="form-inline">
            <input type="hidden" name="submitModule" value="true"/>
            <input type="hidden" name="pctab" value="diagnostic"/>
            <fieldset style="margin: 10px 0">
                {if $pagecache_profiling}
                    <div class="form-group">
                        <label for="pagecache_profiling_min_ms">{l s='Only record modules that last more than' mod='pagecache'}</label>
                        <div class="input-group">
                            <input type="number" min="0" style="text-align:right" class="form-control" id="pagecache_profiling_min_ms" name="pagecache_profiling_min_ms" value="{$pagecache_profiling_min_ms|escape:'html':'UTF-8'}">
                            <div class="input-group-addon">ms</div>
                        </div>
                    </div>
                    <button type="submit" id="submitModuleProfilingMinMs" name="submitModuleProfilingMinMs" value="1" class="btn btn-default">{l s='Save' mod='pagecache'}</button>
                {/if}
            </fieldset>
            <fieldset style="margin: 10px 0">
                <div class="bootstrap">
                    <button type="submit" value="1" id="submitModuleOnOffProfiling" name="submitModuleOnOffProfiling"
                            class="btn btn-default">
                        <i class="process-icon-off"
                           style="color:{if $pagecache_profiling}red{else}rgb(139, 201, 84){/if}"></i> {if $pagecache_profiling}{l s='Disable profiling' mod='pagecache'}{else}{l s='Enable profiling' mod='pagecache'}{/if}
                    </button>
                    {if $pagecache_profiling}
                        <button type="submit" value="1" id="submitModuleResetProfiling" name="submitModuleResetProfiling"
                                class="btn btn-default">
                            <i class="process-icon-delete"
                               style="color:orange"></i> {l s='Clear profiling datas' mod='pagecache'}
                        </button>
                        <button type="button" value="1" id="submitModuleRefreshProfiling" name="submitModuleRefreshProfiling"
                                class="btn btn-default" onclick="$('#profilingTable').DataTable().ajax.reload();return false;">
                            <i class="process-icon-refresh"></i> {l s='Refresh' mod='pagecache'}
                        </button>
                    {/if}
                </div>
            </fieldset>
        </form>
        {if $pagecache_profiling}
            <script type="application/javascript">
                $(document).ready(function () {
                    $('#profilingTable').DataTable({
                        processing: true,
                        serverSide: true,
                        searching: false,
                        ajax: '{$pagecache_profiling_datas_url|escape:'javascript':'UTF-8'}',
                        language: {
                            processing:     "{l s='Loading datas...' mod='pagecache'}",
                            search:         "{l s='Search...' mod='pagecache'}:",
                            lengthMenu:     "{l s='Showing _MENU_ rows' mod='pagecache'}",
                            info:           "{l s='Showing _START_ to _END_ of _TOTAL_ rows' mod='pagecache'}",
                            infoEmpty:      "{l s='Showing 0 to 0 of 0 row' mod='pagecache'}",
                            infoFiltered:   "{l s='Filtered of _MAX_ rows' mod='pagecache'}",
                            infoPostFix:    "",
                            loadingRecords: "{l s='Loading datas...' mod='pagecache'}",
                            zeroRecords:    "{l s='No module to display' mod='pagecache'}",
                            emptyTable:     "{l s='No module to display' mod='pagecache'}",
                            paginate: {
                                first:      "{l s='First' mod='pagecache'}",
                                previous:   "{l s='Previous' mod='pagecache'}",
                                next:       "{l s='Next' mod='pagecache'}",
                                last:       "{l s='Last' mod='pagecache'}"
                            },
                            aria: {
                                sortAscending:  ": {l s='Click to sort ascending' mod='pagecache'}",
                                sortDescending: ": {l s='Click to sort descending' mod='pagecache'}"
                            }
                        }
                    });
                });
            </script>
            <table id="profilingTable" class="display cell-border compact stripe" style="width:100%">
                <thead>
                <tr>
                    <th>{l s='Module' mod='pagecache'}</th>
                    <th>{l s='Code' mod='pagecache'}</th>
                    <th>{l s='Execution date' mod='pagecache'}</th>
                    <th>{l s='Duration' mod='pagecache'}</th>
                </tr>
                </thead>
            </table>
        {/if}
    {/if}
</div>
