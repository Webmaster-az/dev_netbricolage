{*
* Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
* 
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*}
<script type="application/javascript">
    function jprestaUpdateCount() {
        let pages_count = 0;
        $('[name="warmup_controllers[]"]:checked').each(function () {
            pages_count += $(this).data('page-count');
        });
        let contexts_count = $('#contexts tbody tr').length - 1;
        $('#pages_count').html(pages_count);
        $('#contexts_count').html(contexts_count);
        $('#total_pages_count').html(pages_count * contexts_count);
        $('#total_pages_count').removeClass('cachewarmer_count_warn').removeClass('cachewarmer_count_danger');
        if (pages_count * contexts_count > 100000) {
            if (pages_count * contexts_count > 200000) {
                $('#total_pages_count').addClass('cachewarmer_count_danger');
            }
            else {
                $('#total_pages_count').addClass('cachewarmer_count_warn');
            }
        }
    }
    function jprestaDeleteContexts(elt) {
        $(elt).parents('tr').remove();
        jprestaUpdateCount();
    }
    function jprestaAddContexts() {
        let newIndex = 0;
        $('#contexts tbody tr').each(function() {
            if ($(this).data('context-index')) {
                newIndex = Math.max(newIndex, $(this).data('context-index'));
            }
        });
        newIndex++;
        let html = $('#contexts tbody tr:first-child').clone().html();
        $('<tr data-context-index="' + newIndex + '">' + html.replaceAll(' disabled="disabled"', '').replaceAll('XXX', newIndex) + '</tr>').appendTo('#contexts tbody');
        jprestaUpdateCount();
    }
    $(function() {
        jprestaUpdateCount();
    });
</script>
<div class="panel" style="margin-bottom: 10px">
    <h3><a href="{$pagecache_cw_url|escape:'html':'UTF-8'}" target="_blank">{if $avec_bootstrap}<i class="icon-gear"></i>{else}<img width="16" height="16" src="../img/admin/AdminPreferences.gif" alt=""/>{/if}</a>
        &nbsp;{l s='Cache Warmer settings' mod='pagecache'}
    </h3>
    <div style="margin-bottom: 8px">
        <a id="showSettingsCW" href="#" onclick="$('#pagecache_form_cachewarmer').show();$('#showSettingsCW').hide();$('#hideSettingsCW').show();return false;"><i class="icon-arrow-down"></i> {l s='Show the settings' mod='pagecache'}</a>
        <a id="hideSettingsCW" href="#" onclick="$('#pagecache_form_cachewarmer').hide();$('#hideSettingsCW').hide();$('#showSettingsCW').show();return false;" style="display: none"><i class="icon-arrow-up"></i> {l s='Hide the settings' mod='pagecache'}</a>
    </div>
    <form id="pagecache_form_cachewarmer" action="{$request_uri|escape:'html':'UTF-8'}#tabcachewarmer" method="post" style="display: none">
        <input type="hidden" name="submitModule" value="true"/>
        <input type="hidden" name="pctab" value="cachewarmer"/>
        <input type="hidden" name="cachewarmer_id_shop" value="{$pagecache_cw_contexts->id_shop|intval}"/>
        <fieldset>
            <div class="bootstrap">
                <div class="row">
                    <div class="col-md-12">
                        <div class="bootstrap">
                            <div class="alert alert-info" style="display: block;">&nbsp;<b>{l s='These settings will be used by the cache warmer service if you subscribed to it. See below for more informations.' mod='pagecache'}</b>
                            </div>
                        </div>
                        <p>{l s='The cache warmer browses your site in different contexts so all visitors will get a page on which the cache is available.' mod='pagecache'}</p>
                        <p>{l s='The more you have contexts, the more the warm-up will be long and the cache will consumme resources (database and hard disk).' mod='pagecache'}</p>
                        <p>{l s='The purpose of these settings is to select which contexts you want to warm-up.' mod='pagecache'}</p>
                    </div>
                </div>
                <div class="row" style="margin-top: 1rem">
                    <div class="col-md-12">
                        <h4>{l s='Pages to warmup' mod='pagecache'}</h4>
                        {foreach $managed_controllers as $controller_name => $controller}
                            <span style="margin-right: 1rem;white-space: nowrap;">
                                <input type="checkbox"
                                       onchange="jprestaUpdateCount()"
                                       style="vertical-align: middle; margin: 0 2px;"
                                       id="warmup_page_{$controller_name|escape:'html':'UTF-8'}"
                                       name="warmup_controllers[]"
                                       {if $pagecache_cw_contexts->controllers[$controller_name]['checked']}checked="checked" {/if}
                                        {if $pagecache_cw_contexts->controllers[$controller_name]['disabled']}disabled="disabled" {/if}
                                       value="{$controller_name|escape:'html':'UTF-8'}"
                                       data-page-count="{$pagecache_cw_contexts->controllers[$controller_name]['count']|intval}"
                                >
                                <label for="warmup_page_{$controller_name|escape:'html':'UTF-8'}" title="About {$pagecache_cw_contexts->controllers[$controller_name]['count']|intval} page(s)">{$controller['title']|escape:'html':'UTF-8'}</label>
                            </span>
                        {/foreach}
                    </div>
                </div>
                <div class="row" style="margin-top: 1rem">
                    <div class="col-md-12">
                        <h4>{l s='Contexts to warmup' mod='pagecache'}</h4>

                        <div class="alert alert-info">
                            <p>
                                {l s='Please, create all contexts that you want to warmup' mod='pagecache'}
                            </p>
                            <hr>
                            <p><strong>{if $avec_bootstrap}<i class="icon-flag"></i>{else}<img width="16" height="16" src="../img/admin/world.gif" alt=""/>{/if}&nbsp;{l s='Languages' mod='pagecache'}</strong>&nbsp;:&nbsp;
                                {l s='Available languages are the ones enabled for this shop' mod='pagecache'}
                            </p>
                            <p><strong>{if $avec_bootstrap}<i class="icon-money"></i>{else}<img width="16" height="16" src="../img/admin/money.gif" alt=""/>{/if}&nbsp;{l s='Currencies' mod='pagecache'}</strong>&nbsp;:&nbsp;
                                {l s='Available currencies are the ones enabled for this shop' mod='pagecache'}
                            </p>
                            <p><strong>{if $avec_bootstrap}<i class="icon-desktop"></i>{else}<img width="16" height="16" src="../img/admin/metatags.gif" alt=""/>{/if}&nbsp;{l s='Devices' mod='pagecache'}</strong>&nbsp;:&nbsp;
                                {l s="You can only select 'mobile' if you enabled the option 'Create separate cache for desktop and mobile' in advanced mode, in menu Cache Key > Devices" mod='pagecache'}
                            </p>
                            <p><strong>{if $avec_bootstrap}<i class="icon-map-marker"></i>{else}<img width="16" height="16" src="../img/admin/world.gif" alt=""/>{/if}&nbsp;{l s='Countries' mod='pagecache'}</strong>&nbsp;:&nbsp;
                                {l s='Available countries are the ones you selected in advanced mode, in menu Cache Key > Countries' mod='pagecache'}
                            </p>
                            <p><strong>{if $avec_bootstrap}<i class="icon-users"></i>{else}<img width="16" height="16" src="../img/admin/group.gif" alt=""/>{/if}&nbsp;{l s='User groups combinations' mod='pagecache'}</strong>&nbsp;:&nbsp;
                                {l s="Available user groups are the ones currently used by the cache. To add a user group or a user group combination you just need to connect to the shop with a corresponding customer account when the cache is enabled. If you still don't find it, that means this user group or user group combination does not need a specific cache. More informations in advanced mode, in menu Cache Key > User groups" mod='pagecache'}
                            </p>
                            <p><strong>{if $avec_bootstrap}<i class="icon-cogs"></i>{else}<img width="16" height="16" src="../img/admin/cogs.gif" alt=""/>{/if}&nbsp;{l s='Specifics' mod='pagecache'}</strong>&nbsp;:&nbsp;
                                {l s='Specifics are mostly used for RGPD law; it creates different cache for visitor accepting cookies or not. The list is based on current cache statistics.' mod='pagecache'}
                            </p>
                        </div>

                        <table id="contexts" class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th><a onclick="jprestaAddContexts(); return false;" class="btn btn-xs btn-primary" href="#"><i class="material-icons">add_circle_outline</i></a></th>
                                <th>{if $avec_bootstrap}<i class="icon-flag"></i>{else}<img width="16" height="16" src="../img/admin/world.gif" alt=""/>{/if}&nbsp;{l s='Languages' mod='pagecache'}</th>
                                <th>{if $avec_bootstrap}<i class="icon-money"></i>{else}<img width="16" height="16" src="../img/admin/money.gif" alt=""/>{/if}&nbsp;{l s='Currencies' mod='pagecache'}</th>
                                <th>{if $avec_bootstrap}<i class="icon-desktop"></i>{else}<img width="16" height="16" src="../img/admin/metatags.gif" alt=""/>{/if}&nbsp;{l s='Devices' mod='pagecache'}</th>
                                <th>{if $avec_bootstrap}<i class="icon-flag"></i>{else}<img width="16" height="16" src="../img/admin/world.gif" alt=""/>{/if}&nbsp;{l s='Countries' mod='pagecache'}</th>
                                <th>{if $avec_bootstrap}<i class="icon-users"></i>{else}<img width="16" height="16" src="../img/admin/group.gif" alt=""/>{/if}&nbsp;{l s='User groups' mod='pagecache'}</th>
                                <th>{if $avec_bootstrap}<i class="icon-cogs"></i>{else}<img width="16" height="16" src="../img/admin/cogs.gif" alt=""/>{/if}&nbsp;{l s='Specifics' mod='pagecache'}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr style="display:none">
                                <td><a onclick="jprestaDeleteContexts(this); return false;" class="btn btn-xs btn-primary deletecontext" href="#"><i class="material-icons">delete</i></a></td>
                                <td>
                                    <select name="contexts[XXX][language]" disabled="disabled">
                                        {foreach $pagecache_cw_contexts->languages as $context}
                                            <option value="{$context['value']|escape:'html':'UTF-8'}">{$context['label']|escape:'html':'UTF-8'}{if isset($context['count'])} ({$context['count']|intval}){/if}</option>
                                        {/foreach}
                                    </select>
                                </td>
                                <td>
                                    <select name="contexts[XXX][currency]" disabled="disabled">
                                        {foreach $pagecache_cw_contexts->currencies as $context}
                                            <option value="{$context['value']|escape:'html':'UTF-8'}">{$context['label']|escape:'html':'UTF-8'}{if isset($context['count'])} ({$context['count']|intval}){/if}</option>
                                        {/foreach}
                                    </select>
                                </td>
                                <td>
                                    <select name="contexts[XXX][device]" disabled="disabled">
                                        {foreach $pagecache_cw_contexts->devices as $context}
                                            <option value="{$context['value']|escape:'html':'UTF-8'}">{$context['label']|escape:'html':'UTF-8'}{if isset($context['count'])} ({$context['count']|intval}){/if}</option>
                                        {/foreach}
                                    </select>
                                </td>
                                <td>
                                    <select name="contexts[XXX][country]" disabled="disabled">
                                        {foreach $pagecache_cw_contexts->countries as $context}
                                            <option value="{$context['value']|escape:'html':'UTF-8'}">{$context['label']|escape:'html':'UTF-8'}{if isset($context['count'])} ({$context['count']|intval}){/if}</option>
                                        {/foreach}
                                    </select>
                                </td>
                                <td>
                                    <select name="contexts[XXX][group]" disabled="disabled">
                                        {foreach $pagecache_cw_contexts->groups as $context}
                                            <option value="{$context['value']|escape:'html':'UTF-8'}">{$context['label']|escape:'html':'UTF-8'}{if isset($context['count'])} ({$context['count']|intval}){/if}</option>
                                        {/foreach}
                                    </select>
                                </td>
                                <td>
                                    <select name="contexts[XXX][specifics]" disabled="disabled">
                                        {foreach $pagecache_cw_contexts->specifics as $context}
                                            <option value="{$context['value']|escape:'html':'UTF-8'}">{$context['label']|escape:'html':'UTF-8'}{if isset($context['count'])} ({$context['count']|intval}){/if}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>
                            {foreach $pagecache_cw_contexts->contexts as $index => $context}
                                <tr data-context-index="{$index|intval}">
                                    <input type="hidden" name="contexts[{$index|intval}][language]" value="{$context['language']|escape:'html':'UTF-8'}">
                                    <input type="hidden" name="contexts[{$index|intval}][currency]" value="{$context['currency']|escape:'html':'UTF-8'}">
                                    <input type="hidden" name="contexts[{$index|intval}][device]" value="{$context['device']|escape:'html':'UTF-8'}">
                                    <input type="hidden" name="contexts[{$index|intval}][country]" value="{$context['country']|escape:'html':'UTF-8'}">
                                    <input type="hidden" name="contexts[{$index|intval}][group]" value="{$context['group']|escape:'html':'UTF-8'}">
                                    <input type="hidden" name="contexts[{$index|intval}][specifics]" value="{$context['specifics']|escape:'html':'UTF-8'}">
                                    <td><a onclick="jprestaDeleteContexts(this); return false;" class="btn btn-xs btn-primary" href="#"><i class="material-icons">delete</i></a></td>
                                    <td>{$pagecache_cw_contexts->languages[$context['language']]['label']|escape:'html':'UTF-8'}</td>
                                    <td>{$pagecache_cw_contexts->currencies[$context['currency']]['label']|escape:'html':'UTF-8'}</td>
                                    <td>{$pagecache_cw_contexts->devices[$context['device']]['label']|escape:'html':'UTF-8'}</td>
                                    <td>{$pagecache_cw_contexts->countries[$context['country']]['label']|escape:'html':'UTF-8'}</td>
                                    <td>{$pagecache_cw_contexts->groups[$context['group']]['label']|escape:'html':'UTF-8'}</td>
                                    <td>{$pagecache_cw_contexts->specifics[$context['specifics']]['label']|escape:'html':'UTF-8'}</td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" style="margin-top: 1rem">
                    <div class="col-md-12">
                        <h4>{l s='Total pages to warmup' mod='pagecache'}</h4>
                        <div class="bootstrap">
                            <div class="alert alert-info" style="display: block;">&nbsp;{l s='Try to have less than 100000 pages to warmup or it will be too long to be processed by the cache-warmer in a single day' mod='pagecache'}
                            </div>
                        </div>
                        <table class="table" style="width: initial">
                            <tbody>
                            <tr>
                                <td>{l s='Estimated number of pages per context' mod='pagecache'}</td>
                                <td id="pages_count" class="cachewarmer_count"></td>
                            </tr>
                            <tr>
                                <td>{l s='Number of context' mod='pagecache'}</td>
                                <td id="contexts_count" class="cachewarmer_count"></td>
                            </tr>
                            <tr>
                                <td>{l s='Total pages to warmup' mod='pagecache'}</td>
                                <td id="total_pages_count" class="cachewarmer_count"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <button type="submit" value="1" id="submitModuleCacheWarmerSettings" name="submitModuleCacheWarmerSettings"
                        class="btn btn-default pull-right">
                    <i class="process-icon-save"></i> {l s='Save' mod='pagecache'}
                </button>
            </div>
        </fieldset>
    </form>
</div>
