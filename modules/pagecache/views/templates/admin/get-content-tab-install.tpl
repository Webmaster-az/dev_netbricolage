{*
* Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
* 
*    @author    Jpresta
*    @copyright Jpresta
*    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
*               is permitted for one Prestashop instance only but you can install it on your test instances.
*}
<div class="row">
    <div class="col-md-{if $pagecache_debug}12{else}6{/if}">
        <div class="panel">
            <h3>{if $avec_bootstrap}<i class="icon-wrench"></i>{else}<img width="16" height="16" src="../img/admin/prefs.gif" alt=""/>{/if} {l s='Installation' mod='pagecache'}</h3>
            <form id="pagecache_form_install" action="{$request_uri|escape:'html':'UTF-8'}" method="post">
                <input type="hidden" name="submitModule" value="true"/>
                <input type="hidden" name="pctab" value="install"/>
                <input type="hidden" name="pagecache_disable_tokens" value="false" id="pagecache_disable_tokens"/>
                <fieldset>
                <div style="clear: both;">
                {if $pagecache_debug}

                    <input type="hidden" name="pagecache_install_step" id="pagecache_install_step" value="{$cur_step + 1|escape:'html':'UTF-8'}"/>
                    <input type="hidden" name="pagecache_disable_loggedin" id="pagecache_disable_loggedin" value="0"/>
                    <input type="hidden" name="pagecache_seller" id="pagecache_seller" value="{$pagecache_seller|escape:'html':'UTF-8'}"/>
                    <input type="hidden" name="pagecache_autoconf" id="pagecache_autoconf" value="false"/>

                    {if $cur_step > $INSTALL_STEP_INSTALL}
                        <div class="installstep">{l s='Congratulations!' mod='pagecache'} {$module_displayName|escape:'html':'UTF-8'} {l s='is currently installed in' mod='pagecache'} <b>{l s='test mode' mod='pagecache'}</b>{l s=', that means it\'s not yet activated to your visitors.' mod='pagecache'}</div>
                    {/if}

                    <div class="installstep">{l s='To complete the installation, please follow these steps:' mod='pagecache'}

                        {* INSTALL STEP *}
                        <div class="step {if $cur_step > $INSTALL_STEP_INSTALL}stepok{elseif $cur_step < $INSTALL_STEP_INSTALL}steptodo{/if}">
                            {if $cur_step > $INSTALL_STEP_INSTALL}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/check.png" alt="ok" width="24" height="24" />
                            {elseif $cur_step < $INSTALL_STEP_INSTALL}
                               <span>{$INSTALL_STEP_INSTALL|escape:'html':'UTF-8'}</span>
                            {else}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/curstep.gif" alt="todo" width="24" height="24" />
                            {/if}
                            {l s='Install the module and enable test mode' mod='pagecache'}
                            {if $cur_step == $INSTALL_STEP_INSTALL}
                            <div class="stepdesc"><ol><li>{l s='Resolve displayed errors above' mod='pagecache'}</li></ol></div>
                            {/if}
                        </div>

                        {* BUY FROM STEP *}
                        <div class="step {if $cur_step > $INSTALL_STEP_BUY_FROM}stepok{elseif $cur_step < $INSTALL_STEP_BUY_FROM}steptodo{/if}">
                            {if $cur_step > $INSTALL_STEP_BUY_FROM}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/check.png" alt="ok" width="24" height="24" />
                            {elseif $cur_step < $INSTALL_STEP_BUY_FROM}
                               <span>{$INSTALL_STEP_BUY_FROM|escape:'html':'UTF-8'}</span>
                            {else}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/curstep.gif" alt="todo" width="24" height="24" />
                            {/if}
                            {l s='Tell us where did you buy the module' mod='pagecache'}
                            {if $cur_step == $INSTALL_STEP_BUY_FROM}
                            <div class="stepdesc">
                                <ol>
                                    <li>{l s='In order to display correct links for support just tell us where you bought ' mod='pagecache'}{$module_displayName|escape:'html':'UTF-8'}</li>
                                </ol>
                                <a href="#" class="okbtn" onclick="$('#pagecache_seller').val('addons');$('#pagecache_form_install').submit();return false;">{l s='Prestashop Addons' mod='pagecache'}</a>
                                <a href="#" class="okbtn" onclick="$('#pagecache_seller').val('jpresta');$('#pagecache_form_install').submit();return false;">{l s='JPresta.com' mod='pagecache'}</a>
                            </div>
                            {/if}
                        </div>

                        {* IN ACTION STEP *}
                        <div class="step {if $cur_step > $INSTALL_STEP_IN_ACTION}stepok{elseif $cur_step < $INSTALL_STEP_IN_ACTION}steptodo{/if}">
                            {if $cur_step > $INSTALL_STEP_IN_ACTION}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/check.png" alt="ok" width="24" height="24" />
                            {elseif $cur_step < $INSTALL_STEP_IN_ACTION}
                               <span>{$INSTALL_STEP_IN_ACTION|escape:'html':'UTF-8'}</span>
                            {else}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/curstep.gif" alt="todo" width="24" height="24" />
                            {/if}
                            {l s='Check that the module is well installed' mod='pagecache'}
                            {if $cur_step == $INSTALL_STEP_IN_ACTION}
                            <div class="stepdesc">
                                <ol>
                                    <li><a href="{$shop_link_debug|escape:'html':'UTF-8'}" target="_blank">{l s='Click here to browse your site in test mode' mod='pagecache'}</a></li>
                                    <li>{l s='You must see a box displayed in bottom left corner of your store' mod='pagecache'}</li>
                                    <li>{l s='You must be able to play with these buttons' mod='pagecache'} &nbsp;&nbsp;<img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/on.png" alt="" width="16" height="16" /><img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/reload.png" alt="" width="16" height="16" /><img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/trash.png" alt="" width="16" height="16" /><img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/close.png" alt="" width="16" height="16" /></li>
                                </ol>
                                <a href="#" class="okbtn" onclick="$('#pagecache_form_install').submit();return false;">{l s='OK, I validate this step' mod='pagecache'}</a>
                                <a href="#" class="kobtn" onclick="$('#helpINSTALL_STEP_IN_ACTION').toggle();return false;">{l s='No, I\'m having trouble' mod='pagecache'}</a>
                                <div class="stephelp" id="helpINSTALL_STEP_IN_ACTION">
                                    <ol>
                                        <li>{l s='Reset the module and see if it\'s better' mod='pagecache'}</li>
                                        <li>{l s='If, after resetting the module, you are still having trouble,' mod='pagecache'} <a href="{$contact_url|escape:'html':'UTF-8'}" target="_blank">{l s='contact us here' mod='pagecache'}</a></li>
                                    </ol>
                                </div>
                            </div>
                            {/if}
                        </div>

                        {* AUTOCONF STEP *}
                        <div class="step {if $cur_step > $INSTALL_STEP_AUTOCONF}stepok{elseif $cur_step < $INSTALL_STEP_AUTOCONF}steptodo{/if}">
                            {if $cur_step > $INSTALL_STEP_AUTOCONF}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/check.png" alt="ok" width="24" height="24" />
                            {elseif $cur_step < $INSTALL_STEP_AUTOCONF}
                               <span>{$INSTALL_STEP_AUTOCONF|escape:'html':'UTF-8'}</span>
                            {else}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/curstep.gif" alt="todo" width="24" height="24" />
                            {/if}
                            {l s='Auto-configuration of known modules' mod='pagecache'}
                            {if $cur_step == $INSTALL_STEP_AUTOCONF}
                            <div class="stepdesc">
                                <p>
                                    <i>{l s='Contact our server to request the configuration of know modules so it\'s faster and easier for you' mod='pagecache'}</i>
                                </p>
                                {if !empty($pagecache_cfgadvancedjs)}
                                    <div class="bootstrap">
                                        <div class="alert alert-info" style="display: block;">&nbsp;{l s='Warning: this will erase the current configuration of Page Cache' mod='pagecache'}</div>
                                    </div>
                                    <button class="okbtn" onclick="if (confirm('{l s='Warning: this will erase the current configuration of Page Cache' js='true' mod='pagecache'}')){ $('#pagecache_autoconf').val('true');$('#pagecache_form_install').submit();$(this).prop('disabled', 'true');};return false;">{l s='Auto-configuration' mod='pagecache'}</button>
                                {else}
                                    <button class="okbtn" onclick="$('#pagecache_autoconf').val('true');$('#pagecache_form_install').submit();$(this).prop('disabled', 'true');return false;">{l s='Auto-configuration' mod='pagecache'}</button>
                                {/if}
                                <a href="#" class="kobtn" onclick="$('#pagecache_autoconf').val('false');$('#pagecache_form_install').submit();return false;">{l s='Continue manually' mod='pagecache'}</a>
                            </div>
                            {/if}
                        </div>

                        {* CART STEP *}
                        <div class="step {if $cur_step > $INSTALL_STEP_CART}stepok{elseif $cur_step < $INSTALL_STEP_CART}steptodo{/if}">
                            {if $cur_step > $INSTALL_STEP_CART}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/check.png" alt="ok" width="24" height="24" />
                            {elseif $cur_step < $INSTALL_STEP_CART}
                               <span>{$INSTALL_STEP_CART|escape:'html':'UTF-8'}</span>
                            {else}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/curstep.gif" alt="todo" width="24" height="24" />
                            {/if}
                            {l s='Check that the cart is working good' mod='pagecache'}
                            {if $cur_step == $INSTALL_STEP_CART}
                            <div class="stepdesc">
                                <ol>
                                    <li><a href="{$shop_link_debug|escape:'html':'UTF-8'}" target="_blank">{l s='Click here to browse your site in test mode' mod='pagecache'}</a></li>
                                    <li>{l s='Check that you can add products into the cart as usual' mod='pagecache'}</li>
                                    <li>{l s='Once you have a product in your cart, display an other page and see if cart still contains the products you added' mod='pagecache'}</li>
                                </ol>
                                <a href="#" class="okbtn" onclick="$('#pagecache_form_install').submit();return false;">{l s='OK, I validate this step' mod='pagecache'}</a>
                                <a href="#" class="kobtn" onclick="$('#helpINSTALL_STEP_CART').toggle();return false;">{l s='No, I\'m having trouble' mod='pagecache'}</a>
                                <div class="stephelp" id="helpINSTALL_STEP_CART">
                                    <ol>
                                        <li>{l s='When you display an other page, check that you have the parameter dbgpagecache=1 in the URL. If not, just add it.' mod='pagecache'}</li>
                                        <li>{l s='When refreshing the cart, PageCache may remove some "mouse over" behaviours. To set them back you can execute some javascript after all dynamics modules have been displayed.' mod='pagecache'} <a href="#tabdynhooksjs" onclick="displayTab('dynhooks');return true;">{l s='Go in "Dynamic modules" tab in Javascript form.' mod='pagecache'}</a></li>
                                        <li>{l s='If you cannot make it work,' mod='pagecache'} <a href="{$contact_url|escape:'html':'UTF-8'}" target="_blank">{l s='contact us here' mod='pagecache'}</a></li>
                                    </ol>
                                </div>
                            </div>
                            {/if}
                        </div>

                        {* LOGGED_IN STEP *}
                        <div class="step {if $cur_step > $INSTALL_STEP_LOGGED_IN}stepok{elseif $cur_step < $INSTALL_STEP_LOGGED_IN}steptodo{/if}">
                            {if $cur_step > $INSTALL_STEP_LOGGED_IN}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/check.png" alt="ok" width="24" height="24" />
                            {elseif $cur_step < $INSTALL_STEP_LOGGED_IN}
                               <span>{$INSTALL_STEP_LOGGED_IN|escape:'html':'UTF-8'}</span>
                            {else}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/curstep.gif" alt="todo" width="24" height="24" />
                            {/if}
                            {l s='Check that logged in users are recognized' mod='pagecache'}
                            {if $cur_step == $INSTALL_STEP_LOGGED_IN}
                            <div class="stepdesc">
                                <ol>
                                    {if $pagecache_skiplogged}
                                        {if $avec_bootstrap}
                                            <div class="bootstrap">
                                                <div class="alert alert-info" style="display: block;">&nbsp;{l s='Cache is disabled for logged in users so this step should be OK now, but you should check this out anyway ;-)' mod='pagecache'}
                                                    <br/>{l s='If you want you can' mod='pagecache'} <a href="#" class="browsebtn" onclick="$('#pagecache_disable_loggedin').val(-1);$('#pagecache_form_install').submit();return false;">{l s='reactivate cache for logged in users' mod='pagecache'}</a>
                                                </div>
                                            </div>
                                        {else}
                                            <div class="hint clear" style="display: block;">&nbsp;{l s='Cache is disabled for logged in users so this step should be OK now, but you should check this out anyway ;-)' mod='pagecache'}
                                                <br/>{l s='If you want you can' mod='pagecache'} <a href="#" class="browsebtn" onclick="$('#pagecache_disable_loggedin').val(-1);$('#pagecache_form_install').submit();return false;">{l s='reactivate cache for logged in users' mod='pagecache'}</a>
                                            </div>
                                        {/if}
                                    {/if}
                                    <li><a href="{$shop_link_debug|escape:'html':'UTF-8'}" target="_blank">{l s='Click here to browse your site in test mode' mod='pagecache'}</a></li>
                                    <li>{l s='You must see the "sign in" link when you are not logged in' mod='pagecache'}</li>
                                    <li>{l s='You must see the the user name when you are logged in' mod='pagecache'}</li>
                                    <li>{l s='Of course it depends on your theme so just check that being logged in or not has the same behaviour with PageCache' mod='pagecache'}</li>
                                </ol>
                                <a href="#" class="okbtn" onclick="$('#pagecache_form_install').submit();return false;">{l s='OK, I validate this step' mod='pagecache'}</a>
                                <a href="#" class="kobtn" onclick="$('#helpINSTALL_STEP_LOGGED_IN').toggle();return false;">{l s='No, I\'m having trouble' mod='pagecache'}</a>
                                <div class="stephelp" id="helpINSTALL_STEP_LOGGED_IN">
                                    {if !$pagecache_skiplogged}
                                        <ol>
                                            <li>{l s='Make sure that module displaying user informations or sign in links are set as "dynamic".' mod='pagecache'}</li>
                                            <li>{l s='Your theme may be uncompatible with this feature, specially if these informations are "hard coded" in theme without using a module. In this case just disable PageCache for logged in users.' mod='pagecache'}</li>
                                        </ol>
                                        <a href="#" class="browsebtn" onclick="$('#pagecache_disable_loggedin').val(1);$('#pagecache_form_install').submit();return false;">{l s='Disable cache for logged in users' mod='pagecache'}</a>
                                    {else}
                                        <ol>
                                            <li>{l s='Still having problem? Then ' mod='pagecache'} <a href="{$contact_url|escape:'html':'UTF-8'}" target="_blank">{l s='contact us here' mod='pagecache'}</a></li>
                                        </ol>
                                    {/if}
                                </div>
                            </div>
                            {/if}
                        </div>

                        {* EU_COOKIE STEP *}
                        <div class="step {if $cur_step > $INSTALL_STEP_EU_COOKIE}stepok{elseif $cur_step < $INSTALL_STEP_EU_COOKIE}steptodo{/if}">
                            {if $cur_step > $INSTALL_STEP_EU_COOKIE}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/check.png" alt="ok" width="24" height="24" />
                            {elseif $cur_step < $INSTALL_STEP_EU_COOKIE}
                               <span>{$INSTALL_STEP_EU_COOKIE|escape:'html':'UTF-8'}</span>
                            {else}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/curstep.gif" alt="todo" width="24" height="24" />
                            {/if}
                            {l s='Check your european law module if any' mod='pagecache'}
                            {if $cur_step == $INSTALL_STEP_EU_COOKIE}
                            <div class="stepdesc">
                                <ol>
                                    <li><a href="{$shop_link_debug|escape:'html':'UTF-8'}" target="_blank">{l s='Click here to browse your site in test mode' mod='pagecache'}</a></li>
                                    <li>{l s='Remove your cookies, reset the cache, then display a page' mod='pagecache'}</li>
                                    <li>{l s='You should see the cookie law message; click to hide it' mod='pagecache'}</li>
                                    <li>{l s='Reload the page, you should not see the message again' mod='pagecache'}</li>
                                </ol>
                                <a href="#" class="okbtn" onclick="$('#pagecache_form_install').submit();return false;">{l s='OK, I validate this step' mod='pagecache'}</a>
                                <a href="#" class="kobtn" onclick="$('#helpINSTALL_STEP_EU_COOKIE').toggle();return false;">{l s='No, I\'m having trouble' mod='pagecache'}</a>
                                <div class="stephelp" id="helpINSTALL_STEP_EU_COOKIE">
                                    <ol>
                                        <li>{l s='Make sure you have the latest version of the module' mod='pagecache'}</li>
                                        <li>{l s='Still having problem? Then ' mod='pagecache'} <a href="{$contact_url|escape:'html':'UTF-8'}" target="_blank">{l s='contact us here' mod='pagecache'}</a></li>
                                    </ol>
                                </div>
                            </div>
                            {/if}
                        </div>

                        {* VALIDATE STEP *}
                        <div class="step {if $cur_step > $INSTALL_STEP_VALIDATE}stepok{elseif $cur_step < $INSTALL_STEP_VALIDATE}steptodo{/if}">
                            {if $cur_step > $INSTALL_STEP_VALIDATE}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/check.png" alt="ok" width="24" height="24" />
                            {elseif $cur_step < $INSTALL_STEP_VALIDATE}
                               <span>{$INSTALL_STEP_VALIDATE|escape:'html':'UTF-8'}</span>
                            {else}
                               <img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/curstep.gif" alt="todo" width="24" height="24" />
                            {/if}
                            {l s='Push in production mode' mod='pagecache'}
                            {if $cur_step == $INSTALL_STEP_VALIDATE}
                            <div class="stepdesc">
                                <ol>
                                    <li><a href="{$shop_link_debug|escape:'html':'UTF-8'}" target="_blank">{l s='Click here to browse your site in test mode' mod='pagecache'}</a></li>
                                    <li>{l s='You can do more tests and once your are ready...' mod='pagecache'}</li>
                                </ol>
                                <a href="#" class="okbtn" onclick="$('#pagecache_form_install').submit();return false;">{l s='Enable PageCache for my customers!' mod='pagecache'}</a>
                                <a href="#" class="kobtn" onclick="$('#helpINSTALL_STEP_VALIDATE').toggle();return false;">{l s='No, I\'m having trouble' mod='pagecache'}</a>
                                <div class="stephelp" id="helpINSTALL_STEP_VALIDATE">
                                    <ol>
                                        <li>{l s='Make sure that the problem you have does not occur if you disable PageCache module' mod='pagecache'}</li>
                                        <li>{l s='If your problem is only occuring with PageCache enabled, then' mod='pagecache'} <a href="{$contact_url|escape:'html':'UTF-8'}" target="_blank">{l s='contact us here' mod='pagecache'}</a></li>
                                    </ol>
                                </div>
                            </div>
                            {/if}
                        </div>

                        <div class="bootstrap actions">
                            <button type="submit" value="1" onclick="$('#pagecache_install_step').val({$INSTALL_STEP_BUY_FROM|escape:'html':'UTF-8'}); return true;" id="submitModuleRestartInstall" name="submitModuleRestartInstall" class="btn btn-default">
                                <i class="process-icon-cancel" style="color:red"></i> {l s='Restart from first step' mod='pagecache'}
                            </button>
                            {if $cur_step !== $INSTALL_STEP_VALIDATE}
                            <button type="submit" value="1" onclick="$('#pagecache_install_step').val({$INSTALL_STEP_VALIDATE|escape:'html':'UTF-8'}); return true;" id="submitModuleGoToProd" name="submitModuleGoToProd" class="btn btn-default">
                                <i class="process-icon-next" style="color: #59C763"></i> {l s='Validate all steps' mod='pagecache'}
                            </button>
                            {/if}
                        </div>

                    </div>
                {else}
                    <input type="hidden" name="pagecache_install_step" id="pagecache_install_step" value="{$INSTALL_STEP_BACK_TO_TEST|escape:'html':'UTF-8'}"/>
                    <div class="installstep"><img src="../modules/{$module_name|escape:'html':'UTF-8'}/views/img/check.png" alt="ok" width="20" height="20" /> {l s='Congratulations!' mod='pagecache'} {$module_displayName|escape:'html':'UTF-8'} {l s='is currently installed in' mod='pagecache'} <b>{l s='production mode' mod='pagecache'}</b>{if $pagecache_skiplogged}{l s=' for not logged in users' mod='pagecache'}{/if}{l s=', that means your site is now faster than ever!' mod='pagecache'}
                    </div>
                    <div class="installstep">{l s='If you are having trouble, ' mod='pagecache'}<a href="#" class="browsebtn" onclick="$('#pagecache_form_install').submit();return false;">{l s='go back to test mode' mod='pagecache'}</a></div>
                {/if}
                    <button type="submit" value="1" id="submitModuleClearCache" name="submitModuleClearCache" class="btn btn-default" style="display:none">
                        <i class="process-icon-delete" style="color:orange"></i> {l s='Clear cache' mod='pagecache'}
                    </button>
                    <ul style="display:none">
                        <li id="desc-module-clearcache-li">
                            <a id="desc-module-clearcache" class="toolbar_btn" href="#" onclick="$('#submitModuleClearCache').click(); return false;">
                                <i class="process-icon-delete"></i>
                                <div>{l s='Clear cache' mod='pagecache'}</div>
                            </a>
                        </li>
                    </ul>
                </div>
                </fieldset>
            </form>
        </div>
    </div>
{if !$pagecache_debug}
    <div class="col-md-6">
        <div class="panel">
            <h3>{if $avec_bootstrap}<i class="icon-dashboard"></i>{else}<img width="16" height="16" src="../img/admin/stats.gif" alt=""/>{/if} {l s='Cache performance' mod='pagecache'}</h3>
            <label for="hitrate">{l s='Hit rate' mod='pagecache'}</label>
            <div id="hitrate" class="progress">
                {assign var="hitrate" value=($performances.sum_hit * 100) / max(1, $performances.sum_hit + $performances.sum_missed)}
                <div class="progress-bar" role="progressbar" aria-valuenow="{$hitrate|intval}" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: {$hitrate|intval}%;">
                    {$hitrate|intval}%
                </div>
            </div>
            <p>{l s='This represents the rate of visitors getting the cached page, which mean the fast way. Higher is better! Don\'t worry, it is normal to get a low rate at the beginning.' mod='pagecache'}</p>
            <p>{l s='You can improve this rate by using JPresta Cache Warmer' mod='pagecache'}.</p>
            <p>{$performances.count|intval} {l s='pages are currently managed by the cache' mod='pagecache'}.</p>
        </div>
    </div>
{/if}
</div>
