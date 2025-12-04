{*
* Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
* 
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*}
<div class="panel">
    <h3>{if $avec_bootstrap}<i class="icon-link"></i>{else}<img width="16" height="16" src="../img/admin/subdomain.gif" alt=""/>{/if}&nbsp;{l s='CRON' mod='pagecache'}</h3>
    <form id="pagecache_form_cron" action="{$request_uri|escape:'html':'UTF-8'}" method="post">
        <input type="hidden" name="submitModule" value="true"/>
        <input type="hidden" name="pctab" value="cron"/>
        <fieldset>
            {if $avec_bootstrap}
                <div class="bootstrap"><div class="alert alert-info" style="display: block;">&nbsp;{l s='CRON jobs are scheduled tasks. Here you will find URLs that will allow you to refresh cache in scheduled tasks.' mod='pagecache'}</div></div>
            {else}
                <div class="hint clear" style="display: block;">&nbsp;{l s='CRON jobs are scheduled tasks. Here you will find URLs that will allow you to refresh cache in scheduled tasks.' mod='pagecache'}</div>
            {/if}
            <p>{l s='People who want to clear cache with a CRON job can use the following URLs (one per shop, returns 200 if OK, 404 if there is an error): ' mod='pagecache'}</p>
            <ul>
                {foreach $pagecache_cron_urls as $controller_name => $cron_url}
                    <li><pre>{$cron_url|escape:'javascript':'UTF-8'}</pre></li>
                {/foreach}
            </ul>

            <p>
                {l s='To refresh cache of a specific product add "&product=<product\'s ids separated by commas>", for a category add "&category=<category\'s ids separated by commas>", for home page add "&index", etc.' mod='pagecache'}
                {l s='Available controller (type of page) are' mod='pagecache'}
            </p>
            <ul>
                <li>index (no IDs)</li>
                <li>category</li>
                <li>product</li>
                <li>cms</li>
                <li>newproducts (no IDs)</li>
                <li>bestsales (no IDs)</li>
                <li>supplier</li>
                <li>manufacturer</li>
                <li>contact (no IDs)</li>
                <li>pricesdrop (no IDs)</li>
                <li>sitemap (no IDs)</li>
            </ul>
        </fieldset>
    </form>
</div>
