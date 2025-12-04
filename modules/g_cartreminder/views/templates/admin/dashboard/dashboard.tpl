{*
* Do not edit the file if you want to upgrade the module in future.
*
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @license   please read license in file license.txt
* @link	     http://www.globosoftware.net
*/
*}

<div class="dashboard">
    <div class="row">
        <div class="col-lg-8">
            <div class="log_reminder">
                <div class="header-dash">
                    <i class="icon-bar-chart"></i> {l s='DASHBOARD' mod='g_cartreminder'}
                    <ul class="tab-time">
                        <li {if $type_sort && $type_sort == 1}class="active"{/if}><a href="{$url_controller|escape:'htmlall':'UTF-8'}&type_sort=1">{l s='This month' mod='g_cartreminder'}</a></li>
                        <li {if $type_sort && $type_sort == 2}class="active"{/if}><a href="{$url_controller|escape:'htmlall':'UTF-8'}&type_sort=2">{l s='This year' mod='g_cartreminder'}</a></li>
                        <li {if $type_sort && $type_sort == 3}class="active"{/if}><a href="{$url_controller|escape:'htmlall':'UTF-8'}&type_sort=3">{l s='All time' mod='g_cartreminder'}</a></li>
                    </ul>
                </div>
                <div class="list-log">
                    <div id="mainchart2" class="gchart_box"><svg></svg></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="log_reminder">
                <div class="header-dash">
                    <i class="icon-info-circle"></i> {l s='ACTIVITY OVERVIEW' mod='g_cartreminder'}
                </div>
                <div class="list-log">
                    <ul class="list-overview">
                        <li><a href="#">{l s='Abandoned cart' mod='g_cartreminder'} <span>{$count_abandoncart|intval}</span></a></li>
                        <li><a href="#">{l s='Email reminder sent' mod='g_cartreminder'} <span>{$count_reminder_send|intval}</span></a></li>
                        <li><a href="#">{l s='Notification' mod='g_cartreminder'} <span>{$count_notification|intval}</span></a></li>
                        <li><a href="#">{l s='Condition' mod='g_cartreminder'} <span>{$count_condition|intval}</span></a></li>
                        <li><a href="#">{l s='Popup' mod='g_cartreminder'} <span>{$count_popup|intval}</span></a></li>
                        <li><a href="#">{l s='Email Template' mod='g_cartreminder'} <span>{$count_emailtemplate|intval}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>