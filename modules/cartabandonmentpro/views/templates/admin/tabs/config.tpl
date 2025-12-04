{*
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $edit|intval eq 1}
    <script>
        var edit = 1;
    </script>
    <div id="alertSave" class="alert alert-success">
        {l s='Configuration saved' mod='cartabandonmentpro'}
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
{else}
    <script>
        var edit = 0;
    </script>
{/if}
{if $isWritable}
    <form id="myform" role="form" class="form-horizontal" action="" method="post">
        {include file="../conf/lang.tpl"}
        {if isset($templates) && !empty($templates)}
            <div class="row">
                <h2>{l s='TEMPLATE LIST' mod='cartabandonmentpro'}</h2>
                {l s='Here is the list of all your email template' mod='cartabandonmentpro'}
                {include file="../conf/templates_list.tpl"}
            </div>
        {/if}
        <div class="row" style="margin-top: 25px;">
            <h2>{l s='CONFIGURE YOUR TEMPLATES' mod='cartabandonmentpro'}</h2>
            {include file="../conf/template.tpl"}
        </div>
{else}
    <div class="alert alert-danger">
        <strong>:( {l s='Error!' mod='cartabandonmentpro'}</strong>&nbsp;{l s='It seems that you are not allowed to write to some files. Be sure that you have the necessary permissions configured on your server.' mod='cartabandonmentpro'}
        <br>
        {l s='In order to change file rights on your server, using your FTP details, go to modules/cartabandonedpro and then, check that rights are set to 0777 on mails and tpls folders.' mod='cartabandonmentpro'}
    </div>
{/if}
