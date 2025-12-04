{*

* Do not edit the file if you want to upgrade the module in future.

*

* @author    Globo Jsc <contact@globosoftware.net>

* @copyright 2017 Globo., Jsc

* @link	     http://www.globosoftware.net

* @license   please read license in file license.txt

*/

*}

{if $name == 'start'}
    <div class="tabcustom col-lg-10 col-md-9" >
{elseif $name == 'end'}
    </div>
{elseif $name == 'tabs'}
    <div class="linktabs col-lg-2 col-md-3">
        <nav class="list-group linktabs">
            <a class="list-group-item {if $controller == 'AdminGcartreminderabadonedcart'}active{/if}" href="{$link->getAdminLink('AdminGcartreminderabadonedcart')|escape:'html':'UTF-8'}"><img src="{$dirimg|escape:'htmlall':'UTF-8'}/tababandoncart.png" />{l s='Abandoned Cart' mod='g_cartreminder'}</a>
            <a class="list-group-item {if $controller == 'AdminGdashboard'}active{/if}" href="{$link->getAdminLink('AdminGdashboard')|escape:'html':'UTF-8'}"><img src="{$dirimg|escape:'htmlall':'UTF-8'}/dashboard.png" />{l s='Dashboard' mod='g_cartreminder'}</a>
            <a class="list-group-item {if $controller == 'AdminGsetting'}active{/if}" href="{$link->getAdminLink('AdminGsetting')|escape:'html':'UTF-8'}"><img src="{$dirimg|escape:'htmlall':'UTF-8'}/tabsetting.png" />{l s='Setting' mod='g_cartreminder'}</a>
            <a class="list-group-item {if $controller == 'AdminGcartremindercondreminder'}active{/if}" href="{$link->getAdminLink('AdminGcartremindercondreminder')|escape:'html':'UTF-8'}"><img src="{$dirimg|escape:'htmlall':'UTF-8'}/tabemailreminder.png" />{l s='Email Reminder' mod='g_cartreminder'}</a>
            <a class="list-group-item {if $controller == 'AdminGnotification'}active{/if}" href="{$link->getAdminLink('AdminGnotification')|escape:'html':'UTF-8'}"><img src="{$dirimg|escape:'htmlall':'UTF-8'}/tabbrowser.png" />{l s='Browser Notification' mod='g_cartreminder'}</a>
            <a class="list-group-item {if $controller == 'AdminGcartreminderemail'}active{/if}" href="{$link->getAdminLink('AdminGcartreminderemail')|escape:'html':'UTF-8'}"><img src="{$dirimg|escape:'htmlall':'UTF-8'}/tabemailtemplate.png" />{l s='Email Templates' mod='g_cartreminder'}</a>
            <a class="list-group-item {if $controller == 'AdminGcartreminderhelp'}active{/if}" href="{$link->getAdminLink('AdminGcartreminderhelp')|escape:'html':'UTF-8'}"><img src="{$dirimg|escape:'htmlall':'UTF-8'}/tabhelp.png" />{l s='Help' mod='g_cartreminder'}</a>
       </nav>
    </div>
{elseif $name == 'emailbase'}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
	<head>
        <title>email</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    </head>
    <body>
        {if isset($version) && $version == 'PS16'}
            {$emailcontentup}{* $emailcontentup is html content, no need to escape*}
            {elseif $version == 'PS17'}
                {$emailcontentup nofilter}{* $emailcontentup is html content, no need to escape*}
        {/if}
        {if $check != '1'}
            <img src="https://www.google-analytics.com/collect?v=1&tid={literal}{google_tracking_id}{/literal}&cid=501&t=event&ec=email&ea=open&dp=%2Femail%2Ftracking&dt=Email%20abandoned%20cart%20reminder%205%20in%201" />
        {/if}
    </body>
</html>
{elseif $name == 'selecttemplate_email'}
<div class="form-group">
    <button type="button" class="btn btn-default" data-toggle="modal" id="gtemplate_email" data-target="#selecttemplate_email" style="display: none">
    </button>
    <div class="modal fade in" id="selecttemplate_email" tabindex="-1" role="dialog" aria-labelledby="maillabale" aria-hidden="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="maillabale">
                        {l s='Manage Templates' mod='g_cartreminder'}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" style="max-height:600px; overflow:auto">
                    <div class="form-group">
                        <section class="select-menulist">
                            <div class="gcartadd-item-form">
                                <div class="templateList">
                                    <div id="template1" class="template" data-id="0">
                                        <div class="template-inner">
                                            <img src="{$dirimg|escape:'htmlall':'UTF-8'}/Email_tpl_1.png" alt="template1">
                                            <span>{l s='Default Templates 1' mod='g_cartreminder'}</span>
                                        </div>
                                    </div>
                                    <div id="template2" class="template" data-id="1">
                                        <div class="template-inner">
                                            <img src="{$dirimg|escape:'htmlall':'UTF-8'}/Email_tpl_2.png" alt="template2">
                                            <span>{l s='Default Templates 2' mod='g_cartreminder'}</span>
                                        </div>
                                    </div>
                                    <div id="template3" class="template" data-id="2">
                                        <div class="template-inner">
                                            <img src="{$dirimg|escape:'htmlall':'UTF-8'}/Email_tpl_3.png" alt="template3">
                                            <span>{l s='Default Templates 3' mod='g_cartreminder'}</span>
                                        </div>
                                    </div>
                                    <div id="template4" class="template" data-id="3">
                                        <div class="template-inner">
                                            <img src="{$dirimg|escape:'htmlall':'UTF-8'}/Email_tpl_4.png" alt="template4">
                                            <span>{l s='Default Templates 4' mod='g_cartreminder'}</span>
                                        </div>
                                    </div>
                                    <div id="template5" class="template" data-id="4">
                                        <div class="template-inner">
                                            <img src="{$dirimg|escape:'htmlall':'UTF-8'}/Email_tpl_5.png" alt="template5">
                                            <span>{l s='Default Templates 5' mod='g_cartreminder'}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">
                        {l s='Close' mod='g_cartreminder'}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{elseif $name == 'selecttemplate_popup'}
<div class="form-group">
    <button type="button" class="btn btn-default" data-toggle="modal" id="gtemplate_popup" data-target="#selecttemplate_popup" style="display: none">
    </button>
    <div class="modal fade in" id="selecttemplate_popup" tabindex="-1" role="dialog" aria-labelledby="maillabale" aria-hidden="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="maillabale">
                        {l s='Manage Templates' mod='g_cartreminder'}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <section class="select-menulist">
                            <div class="gcartadd-item-form">
                                <div class="templateList">
                                    <div id="template1" class="template" data-id="1">
                                        <div class="template-inner">
                                            <img src="{$dirimg|escape:'htmlall':'UTF-8'}/Popup_1.png" alt="template1">
                                            <span>{l s='Default Templates 1' mod='g_cartreminder'}</span>
                                        </div>
                                    </div>

                                    <div id="template2" class="template" data-id="2">
                                        <div class="template-inner">
                                            <img src="{$dirimg|escape:'htmlall':'UTF-8'}/Popup_2.png" alt="template2">
                                            <span>{l s='Default Templates 2' mod='g_cartreminder'}</span>
                                        </div>
                                    </div>

                                    <div id="template3" class="template" data-id="3">

                                        <div class="template-inner">

                                            <img src="{$dirimg|escape:'htmlall':'UTF-8'}/Popup_3.png" alt="template3">

                                            <span>{l s='Default Templates 3' mod='g_cartreminder'}</span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </section>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">

                        {l s='Close' mod='g_cartreminder'}

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

{/if}

