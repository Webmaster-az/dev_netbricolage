{*
* Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
* 
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*}
<script type="application/javascript">
    $(document).ready(function () {
        let datasTable = $('#datasTable').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: '{$pagecache_datas_url|escape:'javascript':'UTF-8'}',
            columns: [
                { orderable: false },
                { orderable: false, width: '5rem' },
                { orderable: false, width: '3rem' },
                { width: '7rem' },
                { width: '3rem' },
                { width: '3rem' },
            ],
            order: [],
            language: {
                processing:     "{l s='Loading datas...' mod='pagecache'}",
                search:         "{l s='Search' mod='pagecache'}:",
                lengthMenu:     "{l s='Showing _MENU_ rows' mod='pagecache'}",
                info:           "{l s='Showing _START_ to _END_ of _TOTAL_ rows' mod='pagecache'}",
                infoEmpty:      "{l s='Showing 0 to 0 of 0 row' mod='pagecache'}",
                infoFiltered:   "{l s='Filtered of _MAX_ rows' mod='pagecache'}",
                infoPostFix:    "",
                loadingRecords: "{l s='Loading datas...' mod='pagecache'}",
                zeroRecords:    "{l s='No data to display' mod='pagecache'}",
                emptyTable:     "{l s='No data to display' mod='pagecache'}",
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
            },
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100 ],
                [ '10 {l s='rows' mod='pagecache'}', '25 {l s='rows' mod='pagecache'}', '50 {l s='rows' mod='pagecache'}', '100 {l s='rows' mod='pagecache'}' ]
            ],
            buttons: [
                'pageLength'
            ],
        });
        $('#searchObject').on('keyup', function () {
            datasTable
                .columns(2)
                .search(this.value, false, false, false)
                .draw();
        });
        $('#searchController').on('change', function () {
            datasTable
                .columns(1)
                .search(this.value, false, false, true)
                .draw();
        });
        $('#searchURL').on('change', function () {
            datasTable
                .columns(0)
                .search(this.value, false, true, true)
                .draw();
        });
        $('#refreshDatas').on('click', function () {
            datasTable.ajax.reload();
        });
    });
</script>
<style>
    div#datasTable_processing {
        border: 2px solid orange;
        border-radius: 5px;
        padding: 0;
        line-height: 3rem;
        height: auto;
        z-index: 99;
        font-weight: bold;
    }
    .bootstrap .label-default {
        border: 1px solid #999;
        background-color: transparent;
    }
    #datasTable tr td:nth-child(n+2),#datasTable th {
        text-align: center;
    }
    #datasTable tr td:last-child {
        text-align: right;
    }
    #datasTable_filter {
        display: none;
    }
    #datasTable span.label {
        cursor: help;
    }
    {if !$avec_bootstrap}tfoot input,tfoot select { width:95%; }{/if}
</style>
<div class="panel">
<h3>{if $avec_bootstrap}<i class="icon-line-chart"></i>{else}<img width="16" height="16" src="../img/admin/AdminStats.gif" alt=""/>{/if}&nbsp;{l s='Cached pages' mod='pagecache'}</h3>
    <div class="alert alert-info">{l s='Here you can browse all cached pages. This can be usefull to debug.' mod='pagecache'}</div>
    <fieldset class="cachemanagement">
        <table id="datasTable" class="display cell-border compact stripe" style="width:100%">
            <colgroup>
                <col width="*">
                <col width="0*">
                <col width="0*">
                <col width="0*">
                <col width="0*">
                <col width="0*">
            </colgroup>
            <thead>
            <tr>
                <th>{l s='URL' mod='pagecache'}</th>
                <th>{l s='Controller' mod='pagecache'}</th>
                <th>{l s='ID' mod='pagecache'}</th>
                <th>{l s='Last generation' mod='pagecache'}</th>
                <th>{l s='Cleared' mod='pagecache'}</th>
                <th>{l s='Hit/Missed' mod='pagecache'}</th>
            </tr>
            </thead>
            <tbody>
                <tr><td>-</td><td>--------------</td><td>----</td><td>----/--/-- --:--:--</td><td>-</td><td>- / - (--%)</td></tr>
                <tr><td>-</td><td>--------------</td><td>----</td><td>----/--/-- --:--:--</td><td>-</td><td>- / - (--%)</td></tr>
                <tr><td>-</td><td>--------------</td><td>----</td><td>----/--/-- --:--:--</td><td>-</td><td>- / - (--%)</td></tr>
            </tbody>
            <tfoot>
            <tr>
                <th><input type="text" name="searchURL" id="searchURL" placeholder="{l s='Find in URL (click outside to trigger the search)' mod='pagecache'}" style="padding:4px"></th>
                <th>
                    <select name="searchController" id="searchController" style="padding:4px">
                        <option></option>
                        <option value="1">index</option>
                        <option value="2">category</option>
                        <option value="3">product</option>
                        <option value="4">cms</option>
                        <option value="5">newproducts</option>
                        <option value="6">bestsales</option>
                        <option value="7">supplier</option>
                        <option value="8">manufacturer</option>
                        <option value="9">contact</option>
                        <option value="10">pricesdrop</option>
                        <option value="11">sitemap</option>
                    </select>
                </th>
                <th><input type="text" name="searchObject" id="searchObject" placeholder="{l s='Exact ID' mod='pagecache'}" style="text-align: center; padding:4px"></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </tfoot>
        </table>
        <div style="margin-top: 5px">
            <form id="pagecache_form_datas" action="{$request_uri|escape:'html':'UTF-8'}#tabdatas" method="post">
                <input type="hidden" name="submitModule" value="true"/>
                <button type="submit" value="1" id="submitModuleResetDatas" name="submitModuleResetDatas"
                        class="btn btn-warning pull-right">
                    <i class="process-icon-delete"></i> {l s='Reset cache' mod='pagecache'}
                </button>
                <button type="button" id="refreshDatas" class="btn btn-default pull-right">
                    <i class="process-icon-refresh"></i> {l s='Refresh' mod='pagecache'}
                </button>
            </form>
        </div>
    </fieldset>
</div>
<div class="panel">
    <h3>{if $avec_bootstrap}<i class="icon-database"></i>{else}<img width="16" height="16" src="../img/admin/AdminStats.gif" alt=""/>{/if}&nbsp;{l s='Database' mod='pagecache'}</h3>
    <fieldset class="cachemanagement">
        <div class="alert alert-info">{l s='Tables can consumme a lot of space but they are all optimized and stores only necessary informations. This is mainly used by the automatic refresment of the cache.' mod='pagecache'}</div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{l s='Table' mod='pagecache'}</th>
                    <th>{l s='Row count' mod='pagecache'}</th>
                    <th>{l s='Size in MB' mod='pagecache'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach $pagecache_datas_dbinfos as $row}
                    <tr>
                        {foreach $row as $col}
                            <td>{$col|escape:'html':'UTF-8'}</td>
                        {/foreach}
                    </tr>
                {/foreach}
            </tbody>
        </table>
    </fieldset>
</div>