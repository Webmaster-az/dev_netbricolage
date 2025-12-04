{*
* Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
* 
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*}
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <h3>
                {if $avec_bootstrap}<i class="icon-gear"></i>{else}<img width="16" height="16" src="../img/admin/AdminPreferences.gif"/>{/if}&nbsp;{l s='Cache key settings' mod='pagecache'}
            </h3>
            <form id="pagecache_form_cachekey" action="{$request_uri|escape:'html':'UTF-8'}" method="post">
                <input type="hidden" name="submitModule" value="true"/>
                <input type="hidden" name="pctab" value="cachekey"/>
                <fieldset>
                    <div style="clear: both;">
                        <div class="row form-group">
                            <div class="col-lg-12">
                                <h4>{l s='Devices' mod='pagecache'}</h4>
                            </div>
                            <div id="pagecache_depend_on_device_auto">
                                <label class="control-label col-lg-3">
                                    {l s='Create separate cache for desktop and mobile' mod='pagecache'}
                                </label>
                                <div class="col-lg-9">
                                    <span class="switch prestashop-switch fixed-width-lg">
                                        <input type="radio" name="pagecache_depend_on_device_auto"
                                               id="pagecache_depend_on_device_auto_on" value="1"
                                               {if $pagecache_depend_on_device_auto}checked{/if}>
                                        <label for="pagecache_depend_on_device_auto_on"
                                               class="radioCheck">{l s='Yes' mod='pagecache'}</label>
                                        <input type="radio" name="pagecache_depend_on_device_auto"
                                               id="pagecache_depend_on_device_auto_off" value="0"
                                               {if !$pagecache_depend_on_device_auto}checked{/if}>
                                        <label for="pagecache_depend_on_device_auto_off"
                                               class="radioCheck">{l s='No' mod='pagecache'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                                <div class="col-lg-9 col-lg-offset-3">
                                    <div class="help-block">
                                        {l s='If you know that your mobile version is the same as the desktop version then you can disable this option' mod='pagecache'}
                                    </div>
                                </div>
                            </div>
                            <div id="pagecache_tablet_is_mobile">
                                <label class="control-label col-lg-3">
                                    {l s='Tablet are considered as mobile' mod='pagecache'}
                                </label>
                                <div class="col-lg-9">
                                    <span class="switch prestashop-switch fixed-width-lg">
                                        <input type="radio" name="pagecache_tablet_is_mobile"
                                               id="pagecache_tablet_is_mobile_on" value="1"
                                               {if $pagecache_tablet_is_mobile}checked{/if}>
                                        <label for="pagecache_tablet_is_mobile_on"
                                               class="radioCheck">{l s='Yes' mod='pagecache'}</label>
                                        <input type="radio" name="pagecache_tablet_is_mobile"
                                               id="pagecache_tablet_is_mobile_off" value="0"
                                               {if !$pagecache_tablet_is_mobile}checked{/if}>
                                        <label for="pagecache_tablet_is_mobile_off"
                                               class="radioCheck">{l s='No' mod='pagecache'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                                <div class="col-lg-9 col-lg-offset-3">
                                    <div class="help-block">
                                        {l s='Set to true if your theme display the mobile site for tablet devices' mod='pagecache'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-lg-12">
                                <h4>{l s='CSS & JS' mod='pagecache'}</h4>
                            </div>
                            <div id="pagecache_depend_on_css_js">
                                <label class="control-label col-lg-3">
                                    {l s='Insert CSS and JS version in cache key' mod='pagecache'}
                                </label>
                                <div class="col-lg-9">
                                    <span class="switch prestashop-switch fixed-width-lg">
                                        <input type="radio" name="pagecache_depend_on_css_js" id="pagecache_depend_on_css_js_on"
                                               value="1" {if $pagecache_depend_on_css_js}checked{/if}>
                                        <label for="pagecache_depend_on_css_js_on"
                                               class="radioCheck">{l s='Yes' mod='pagecache'}</label>
                                        <input type="radio" name="pagecache_depend_on_css_js" id="pagecache_depend_on_css_js_off"
                                               value="0" {if !$pagecache_depend_on_css_js}checked{/if}>
                                        <label for="pagecache_depend_on_css_js_off"
                                               class="radioCheck">{l s='No' mod='pagecache'}</label>
                                        <a class="slide-button btn"></a>
                                    </span>
                                </div>
                                <div class="col-lg-9 col-lg-offset-3">
                                    <div class="help-block">
                                        {l s='Only enable this option if the styles disappear when the cache is enabled' mod='pagecache'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-lg-12">
                                <h4>{l s='Countries' mod='pagecache'}</h4>
                            </div>
                            <div class="col-lg-12">
                                <div class="alert alert-info">{l s='The module automatically detects countries with specific prices rules. Any country with a specific price rule need a specific cache, this is why you cannot uncheck it. When there is no specific price rule you may display specific informations for a country (not detected by the module), this is why you can force the country to have a specific cache.' mod='pagecache'}</div>
                            </div>
                            <div id="pagecache_depend_on_css_js">
                                <label class="control-label col-lg-3">
                                    {l s='Select countries that need a specific cache' mod='pagecache'}
                                </label>
                                <div class="col-lg-9">
                                    {if count($pagecache_cache_key_countries) === 0}
                                        <i>{l s='No country are enabled on the shop' mod='pagecache'}</i>
                                    {/if}
                                    {foreach $pagecache_cache_key_countries as $id_country => $country_conf}
                                        <span style="margin-right: 1rem;white-space: nowrap;">
                                            <input type="checkbox"
                                                   style="vertical-align: middle; margin: 0 2px;"
                                                   id="pagecache_cachekey_countries_{$id_country|escape:'html':'UTF-8'}"
                                                   name="pagecache_cachekey_countries[{$id_country|escape:'html':'UTF-8'}]"
                                                   {if $country_conf.impact_count > 0}disabled="disabled" {/if}
                                                   {if $country_conf.specific_cache}checked="checked" {/if}
                                                   value="true">
                                            <label for="pagecache_cachekey_countries_{$id_country|escape:'html':'UTF-8'}" title="{$country_conf.impact_count|escape:'html':'UTF-8'} {l s='price rule(s) found' mod='pagecache'}">{$country_conf.name|escape:'html':'UTF-8'}</label>
                                        </span>
                                    {/foreach}
                                </div>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-lg-12">
                                <h4>{l s='User groups' mod='pagecache'}</h4>
                            </div>
                            <div class="col-lg-12">
                                <div class="alert alert-info">{l s='The module automatically detects user groups with specific prices rules. Any user group with a specific price rule need a specific cache, this is why you cannot uncheck it. When there is no specific price rule you may display specific informations for a user group (not detected by the module), this is why you can force the user group to have a specific cache.' mod='pagecache'}</div>
                            </div>
                            <div id="pagecache_depend_on_css_js">
                                <label class="control-label col-lg-3">
                                    {l s='Select user groups that need a specific cache' mod='pagecache'}
                                </label>
                                <div class="col-lg-9">
                                    {if count($pagecache_cache_key_usergroups) === 0}
                                        <i>{l s='No user group are enabled on the shop' mod='pagecache'}</i>
                                    {/if}
                                    {foreach $pagecache_cache_key_usergroups as $id_group => $group_conf}
                                        <span style="margin-right: 1rem;white-space: nowrap;">
                                            <input type="checkbox"
                                                   style="vertical-align: middle; margin: 0 2px;"
                                                   id="pagecache_cachekey_usergroups_{$id_group|escape:'html':'UTF-8'}"
                                                   name="pagecache_cachekey_usergroups[{$id_group|escape:'html':'UTF-8'}]"
                                                   {if $group_conf.impact_count_as_default > 0}disabled="disabled" {/if}
                                                    {if $group_conf.specific_cache}checked="checked" {/if}
                                                   value="true">
                                            <label for="pagecache_cachekey_usergroups_{$id_group|escape:'html':'UTF-8'}" title="{$group_conf.impact_count_as_default|escape:'html':'UTF-8'} {l s='price rule(s) found' mod='pagecache'}">{$group_conf.name|escape:'html':'UTF-8'}</label>
                                        </span>
                                    {/foreach}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="bootstrap">
                        <button type="submit" value="1" id="submitModuleCacheKey" name="submitModuleCacheKey"
                                class="btn btn-default pull-right">
                            <i class="process-icon-save"></i> {l s='Save' mod='pagecache'}
                        </button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>