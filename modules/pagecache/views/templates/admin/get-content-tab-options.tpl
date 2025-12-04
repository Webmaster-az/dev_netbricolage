{*
* Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
* 
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*}
<div class="panel">
<h3>{if $avec_bootstrap}<i class="icon-gear"></i>{else}<img width="16" height="16" src="../img/admin/AdminPreferences.gif" alt=""/>{/if}&nbsp;{l s='Options' mod='pagecache'}</h3>
<form id="pagecache_form_options" action="{$request_uri|escape:'html':'UTF-8'}" method="post">
    <input type="hidden" name="submitModule" value="true"/>
    <input type="hidden" name="pctab" value="options"/>
    <fieldset>
        <div style="clear: both;">
            <div class="form-group">
                <div id="pagecache_skiplogged">
                    <label class="control-label col-lg-3">
                        {l s='No cache for logged in users' mod='pagecache'}
                    </label>
                    <div class="col-lg-9">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pagecache_skiplogged" id="pagecache_skiplogged_on" value="1" {if $pagecache_skiplogged}checked{/if}>
                            <label for="pagecache_skiplogged_on" class="radioCheck">{l s='Yes' mod='pagecache'}</label>
                            <input type="radio" name="pagecache_skiplogged" id="pagecache_skiplogged_off" value="0" {if !$pagecache_skiplogged}checked{/if}>
                            <label for="pagecache_skiplogged_off" class="radioCheck">{l s='No' mod='pagecache'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
                        <div class="help-block">
                            {l s='Disable cache for visitors that are logged in' mod='pagecache'}
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div id="pagecache_normalize_urls">
                    <label class="control-label col-lg-3">
                        {l s='Normalize URLs' mod='pagecache'}
                    </label>
                    <div class="col-lg-9">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pagecache_normalize_urls" id="pagecache_normalize_urls_on" value="1" {if $pagecache_normalize_urls}checked{/if}>
                            <label for="pagecache_normalize_urls_on" class="radioCheck">{l s='Yes' mod='pagecache'}</label>
                            <input type="radio" name="pagecache_normalize_urls" id="pagecache_normalize_urls_off" value="0" {if !$pagecache_normalize_urls}checked{/if}>
                            <label for="pagecache_normalize_urls_off" class="radioCheck">{l s='No' mod='pagecache'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
                        <div class="help-block">
                            {l s='Avoid same page linked with different URLs to use different cache. Should only be disabled when you have a lot of links in a page (> 500).' mod='pagecache'}
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div id="pagecache_logs_debug">
                    <label class="control-label col-lg-3">
                        {l s='Enable logs' mod='pagecache'}
                    </label>
                    <div class="col-lg-9">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pagecache_logs" id="pagecache_logs_debug_2" value="2" {if $pagecache_logs > 0}checked{/if}>
                            <label for="pagecache_logs_debug_2" class="radioCheck">{l s='Yes' mod='pagecache'}</label>
                            {*<input type="radio" name="pagecache_logs" id="pagecache_logs_debug_1" value="1" {if $pagecache_logs == 1}checked{/if}>
                            <label for="pagecache_logs_debug_1" class="radioCheck">{l s='Info' mod='pagecache'}</label>*}
                            <input type="radio" name="pagecache_logs" id="pagecache_logs_debug_0" value="0" {if $pagecache_logs == 0}checked{/if}>
                            <label for="pagecache_logs_debug_0" class="radioCheck">{l s='No' mod='pagecache'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
                        <div class="help-block">
                            {l s='Logs informations into the Prestashop logger. You should only enable it to debug or understand how the cache works.' mod='pagecache'}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div id="pagecache_logs_debug">
                    <label class="control-label col-lg-3">
                        {l s='Ignored URL parameters' mod='pagecache'}
                    </label>
                    <div class="col-lg-9">
                        <input type="text" name="pagecache_ignored_params" id="pagecache_ignored_params" value="{$pagecache_ignored_params|escape:'html':'UTF-8'}" size="100">
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
                        <div class="help-block">
                            {l s='URL parameters are used to identify a unique page content. Some URL parameters do not affect page content like tracking parameters for analytics (utm_source, utm_campaign, etc.) so we can ignore them. You can set a comma separated list of these parameters here.' mod='pagecache'}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div id="pagecache_always_infosbox">
                    <label class="control-label col-lg-3">
                        {l s='Always display infos box' mod='pagecache'}
                    </label>
                    <div class="col-lg-9">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pagecache_always_infosbox" id="pagecache_always_infosbox_on" value="1" {if $pagecache_always_infosbox}checked{/if}>
                            <label for="pagecache_always_infosbox_on" class="radioCheck">{l s='Yes' mod='pagecache'}</label>
                            <input type="radio" name="pagecache_always_infosbox" id="pagecache_always_infosbox_off" value="0" {if !$pagecache_always_infosbox}checked{/if}>
                            <label for="pagecache_always_infosbox_off" class="radioCheck">{l s='No' mod='pagecache'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
                        <div class="help-block">
                            {l s='Only used for demo' mod='pagecache'}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div id="pagecache_exec_header_hook">
                    <label class="control-label col-lg-3">
                        {l s='Executes "header" hook in dynamic modules request' mod='pagecache'}
                    </label>
                    <div class="col-lg-9">
                        <span class="switch prestashop-switch fixed-width-lg">
                            <input type="radio" name="pagecache_exec_header_hook" id="pagecache_exec_header_hook_on" value="1" {if $pagecache_exec_header_hook}checked{/if}>
                            <label for="pagecache_exec_header_hook_on" class="radioCheck">{l s='Yes' mod='pagecache'}</label>
                            <input type="radio" name="pagecache_exec_header_hook" id="pagecache_exec_header_hook_off" value="0" {if !$pagecache_exec_header_hook}checked{/if}>
                            <label for="pagecache_exec_header_hook_off" class="radioCheck">{l s='No' mod='pagecache'}</label>
                            <a class="slide-button btn"></a>
                        </span>
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
                        <div class="help-block">
                            {l s='If checked, the header hook will be executed so javascript variables added in this hook by other modules will be refreshed' mod='pagecache'}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div id="pagecache_product_refreshEveryX">
                    <label class="control-label col-lg-3">
                        {l s='Refresh product page every X sales' mod='pagecache'}
                    </label>
                    <div class="col-lg-9">
                        {l s='Every' mod='pagecache'}
                        <select style="display: inline-block; width: fit-content;" name="pagecache_product_refreshEveryX" class="form-control">
                            <option value="1" {if $pagecache_product_refreshEveryX == 1} selected{/if}>1</option>
                            <option value="5" {if $pagecache_product_refreshEveryX == 5} selected{/if}>5</option>
                            <option value="10" {if $pagecache_product_refreshEveryX == 10} selected{/if}>10</option>
                            <option value="50" {if $pagecache_product_refreshEveryX == 50} selected{/if}>50</option>
                            <option value="100" {if $pagecache_product_refreshEveryX == 100} selected{/if}>100</option>
                        </select>
                        {l s='sales' mod='pagecache'}
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
                        <div class="help-block">
                            {l s='When stock is not displayed on product page then you can set how often the cache of the product page should be refreshed when the quantity is greater than the quantity that displays a "last items..."' mod='pagecache'}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div id="pagecache_max_exec_time">
                    <label class="control-label col-lg-3">
                        {l s='Max execution time in seconds' mod='pagecache'}
                    </label>
                    <div class="col-lg-9">
                        <input type="text" name="pagecache_max_exec_time" id="pagecache_max_exec_time" value="{$pagecache_max_exec_time|escape:'html':'UTF-8'}" maxlength="10">
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
                        <div class="help-block">
                            {l s='Used by the cache warmer to split the list of URLs to browse if it takes much time to generate. Set 0 to disable it.' mod='pagecache'}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div id="pagecache_ignore_before_pattern">
                    <label class="control-label col-lg-3">
                        {l s='Ignore backlinks before this string' mod='pagecache'}
                    </label>
                    <div class="col-lg-9">
                        <input type="text" name="pagecache_ignore_before_pattern" id="pagecache_ignore_before_pattern" value="{$pagecache_ignore_before_pattern|escape:'html':'UTF-8'}">
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
                        <div class="help-block">
                            {l s='Usefull to ignore links of a mega menu (for exemple) that are not necessary for automatic refreshment. This will decrease the size of the backlinks table (jm_pagecache_bl). Exemple: </header>.' mod='pagecache'}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div id="pagecache_ignore_url_regex">
                    <label class="control-label col-lg-3">
                        {l s='Ignore URLs matching this regex' mod='pagecache'}
                    </label>
                    <div class="col-lg-9">
                        <input type="text" name="pagecache_ignore_url_regex" id="pagecache_ignore_url_regex" value="{$pagecache_ignore_url_regex|escape:'html':'UTF-8'}">
                    </div>
                    <div class="col-lg-9 col-lg-offset-3">
                        <div class="help-block">
                            {l s='You can avoid some pages to be cached. Setup a regular expression that will match URLs that must not be cached. Read https://www.php.net/manual/en/reference.pcre.pattern.syntax.php for more informations. Use https://regex101.com/ to test your regular expression.' mod='pagecache'}
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="bootstrap">
            <button type="submit" value="1" id="submitModuleOptions" name="submitModuleOptions" class="btn btn-default pull-right">
                <i class="process-icon-save"></i> {l s='Save' mod='pagecache'}
            </button>
        </div>
    </fieldset>
</form>
</div>