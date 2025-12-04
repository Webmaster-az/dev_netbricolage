{*
* Do not edit the file if you want to upgrade the module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
*} 

<div class="col-lg-10 col-md-9">
    <section class="help_gcart panel">
        <div class="panel-heading">{l s='Suggestions' mod='g_cartreminder'}</div>
        <div class="help_gcart_content">
            <div class="alert alert-info" role="alert">
                <i class="material-icons"></i><p class="alert-text">{l s='Want us to include some features in next version of the module? ' mod='g_cartreminder'}</p>
            </div>
            <a href="https://addons.prestashop.com/ratings.php" class="btn btn-success btn-lg">{l s='Share your idea' mod='g_cartreminder'}</a>
        </div>
    </section>
    <section class="help_gcart panel">
        <div class="panel-heading">{l s='Setup cronjob' mod='g_cartreminder'}</div>
        <div class="help_gcart_content">
            <h2>{l s='2. Setting up a cron task on your server.' mod='g_cartreminder'}</h2>
            <div class="alert alert-info" role="alert">
                <p>{l s='If you do not have experience on setup crontab please read more here: ' mod='g_cartreminder'}<a href="https://help.ubuntu.com/community/CronHowto">https://help.ubuntu.com/community/CronHowto</a></p>
                <p>{l s='To execute your cron tasks, please insert one of bellow lines:' mod='g_cartreminder'}</p>
                <ul class="list-unstyled">
                    <li><p>{l s='Option 1: If "curl" library is installed on your server.' mod='g_cartreminder'}</p><code>{l s='*/20 * * * * curl ' mod='g_cartreminder'}{if isset($usingSecureMode) && $usingSecureMode}-k {/if}"{$url_cronjobs|escape:'htmlall':'UTF-8'}"</code></li>
                    <li><p>{l s='Option 2: If "wget" library is installed on your server.' mod='g_cartreminder'}</p><code>{l s='*/20 * * * * wget ' mod='g_cartreminder'}"{$url_cronjobs|escape:'htmlall':'UTF-8'}"{if isset($usingSecureMode) && $usingSecureMode} -no-check-certificate{/if}</code></li>
                </ul>
            </div>
            <div class="alert alert-info" role="alert">
                <i class="material-icons"></i><p class="alert-text">{l s='In order to send automatic reminders, you need to setup a cronjob. There are 2 ways to setup cronjob' mod='g_cartreminder'}</p>
            </div>
            <h2>{l s='1. Setting up a cron task on your Back Office via cron tasks manager module.' mod='g_cartreminder'}</h2>
            <p>{l s='By the option, you will have to install module' mod='g_cartreminder'} <b>{l s='Cron tasks manager' mod='g_cartreminder'}</b></p>
            <img class="img-rounded img-responsive" src="{$g_module_url|escape:'htmlall':'UTF-8'}views/img/install-cronjob-module.jpg"/>
            <br/>
            <p>{l s='After install, follow the instruction' mod='g_cartreminder'}</p>
            <br/>
            <img class="img-rounded img-responsive" src="{$g_module_url|escape:'htmlall':'UTF-8'}views/img/config-cronjob-module.jpg"/>
            <br/>
            <p><b>{l s='Add your url: ' mod='g_cartreminder'}</b><code>{$url_cronjobs|escape:'htmlall':'UTF-8'}</code></p>
            <br/>
            <img class="img-rounded img-responsive" src="{$g_module_url|escape:'htmlall':'UTF-8'}views/img/new-cronjob.jpg"/>
        </div>
    </section>
</div>
