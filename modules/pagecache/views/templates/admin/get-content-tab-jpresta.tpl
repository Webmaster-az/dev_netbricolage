{*
* Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
* 
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*}
{if isset($jpresta_account_key) && $jpresta_account_key}
    {include file='./get-content-tab-jpresta-cw-conf.tpl'}
{else}
    {include file='./get-content-tab-jpresta-account-key.tpl'}
{/if}
<iframe src="{$jpresta_api_url_cw|escape:'html':'UTF-8'}?nogutter{if $is_multistores}&multistores{/if}&ps_token={$jpresta_ps_token|escape:'url':'UTF-8'}&jpresta_account_key={$jpresta_account_key|escape:'url':'UTF-8'}&shop_url={$pagecache_cron_base|escape:'url':'UTF-8'}&shop_url_cw={$pagecache_cw_url|escape:'url':'UTF-8'}&shop_name={$shop_name|escape:'url':'UTF-8'}&ps_version={$prestashop_version|escape:'url':'UTF-8'}&module_name={$module_name|escape:'url':'UTF-8'}&module_version={$module_version|escape:'url':'UTF-8'}"
        style="width: 100%; height: 1500px; border: none"></iframe>