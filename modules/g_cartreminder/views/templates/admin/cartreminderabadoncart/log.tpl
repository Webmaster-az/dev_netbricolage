{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*} 

<!-- Button trigger modal -->
<button type="button" class="btn btn-default" data-toggle="modal" data-target="#log_{$id|escape:'htmlall':'UTF-8'}">
  {l s='Reminder Log' mod='g_cartreminder'}
</button>
<!-- Modal -->
<div class="modal fade" id="log_{$id|escape:'htmlall':'UTF-8'}" tabindex="-1" role="dialog" aria-labelledby="logLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    {l s='Reminder Log' mod='g_cartreminder'}
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body log-body-email">
                <table style="width:100%; background:#EBEDF0">
                    <tbody>
                        {if !empty($orders)}
                            {foreach $orders item=order}
                                <tr>
                                    <td><i class="fa fa-circle pull-left" aria-hidden="true" style="color:#00aff0"></i></td>
                                    <td style="text-align: left;">
                                        <div>
                                            <strong>{l s='customer placed order' mod='g_cartreminder'}</strong>
                                            <a class="id_order_link" href="index.php?controller=AdminOrders&amp;id_order={$order["id_order"]|escape:'htmlall':'UTF-8'}&amp;vieworder&amp;token={getAdminToken tab='AdminOrders'}" target="_blank"> #{$order["id_order"]|escape:'htmlall':'UTF-8'}</a>
                                        </div>
                                        
                                    </td>
                                    <td style="padding:25px 15px;">
                                        {$order["date_add"]|escape:'htmlall':'UTF-8'}
                                    </td>
                                </tr>
                            {/foreach}
                        {/if}
                        {if isset($logs) && $logs}
                            {foreach $logs item=log}
                                {if $log["id_reminder"] !=0}
                                    <tr>
                                        <td><i class="fa fa-circle pull-left" aria-hidden="true"></i></td>
                                        <td style="text-align: left;">
                                            <div>
                                                <strong>{l s='send automatically' mod='g_cartreminder'}.</strong>
                                                <p>{l s='Rule name' mod='g_cartreminder'}: {$log["namereminder"]|escape:'htmlall':'UTF-8'}.</p> 
                                                <p>{l s='Discount' mod='g_cartreminder'}: {$log["code"]|escape:'htmlall':'UTF-8'}</p>
                                                <p>{l s='Reminder' mod='g_cartreminder'} {$log["count"]|escape:'htmlall':'UTF-8'}.</p>
                                                <p>{l s='Email template name' mod='g_cartreminder'}: {$log["nameemailtp"]|escape:'htmlall':'UTF-8'}.</p>
                                            </div>
                                        </td>
                                        <td style="padding:25px 15px;">
                                            {$log["time"]|escape:'htmlall':'UTF-8'} {l s='EDT' mod='g_cartreminder'}
                                        </td>
                                    </tr>
                                {else}
                                    <tr>
                                        <td><i class="fa fa-circle pull-left" aria-hidden="true"></i></td>
                                        <td style="text-align: left;">
                                            <div>
                                                <strong>{l s='sent manually' mod='g_cartreminder'}.</strong> 
                                                <p>{l s='Discount' mod='g_cartreminder'}: {$log["code"]|escape:'htmlall':'UTF-8'}.</p>
                                                <p>{l s='Email template name' mod='g_cartreminder'}: {$log["nameemailtp"]|escape:'htmlall':'UTF-8'}.</p>
                                            </div>
                                        </td>
                                        <td style="padding:25px 15px;">
                                            {$log["time"]|escape:'htmlall':'UTF-8'} {l s='EDT' mod='g_cartreminder'}
                                        </td>
                                    </tr>
                                {/if}
                            {/foreach}
                        {/if}
                        {if isset($notificationlogs) && $notificationlogs}
                            {foreach $notificationlogs as $notificationlog}
                                <tr>
                                    <td><i class="fa fa-circle pull-left" aria-hidden="true"></i></td>
                                    <td style="text-align: left;">
                                        <div>
                                            <strong>{l s='Cronjob send notification ' mod='g_cartreminder'}.</strong>
                                        </div>
                                    </td>
                                    <td style="padding:25px 15px;">
                                        {$notificationlog["time"]|escape:'htmlall':'UTF-8'} {l s='EDT' mod='g_cartreminder'}
                                    </td>
                                </tr>
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='g_cartreminder'}</button>
            </div>
        </div>
    </div>
</div>