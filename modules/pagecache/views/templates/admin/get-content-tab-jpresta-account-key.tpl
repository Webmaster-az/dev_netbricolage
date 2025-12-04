{*
* Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
* 
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*}
<div class="panel">
    <h3>{if $avec_bootstrap}<i class="icon-fire"></i>{else}<img width="16" height="16" src="../img/admin/quick.gif" alt=""/>{/if}&nbsp;{l s='JPresta Cache Warmer' mod='pagecache'}</h3>
    <p>{l s="JPresta Cache Warmer is a service that creates bots to browse your site in order to generate the cache, so when human visitors display your shop they get the cached pages, which are faster." mod='pagecache'}</p>
    <p>{l s="To use the JPresta Cache Warmer service, you must sign in or create your account on jpresta.com, copy your JPresta Account Key and attach it to this Prestashop instance." mod='pagecache'}</p>
    <a href="#tablicense" class="btn btn-primary" onclick="displayTab('license');"><i class="icon-sign-in"></i>&nbsp;{l s='Attach my JPresta Account Key' mod='pagecache'}</a>
</div>