{*
* Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
*
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*}
<ul>
{foreach $urls as $url}
<li style="line-height: 2rem;"><span class="cron_url">{$url|escape:'html':'UTF-8'}</span></li>
{/foreach}
</ul>