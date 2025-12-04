{*
* 2010-2021 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through LICENSE.txt file inside our module
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright 2010-2021 Webkul IN
* @license LICENSE.txt
*}

<div class="alert alert-success" id="wk-notification-msg-cont">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <span id="wk-notification-success-msg"></span>
</div>

<div class="row" id="wk-notifcation-cont">
    <div class="col-sm-12">
        <div class="alert alert-warning">
            <strong>{l s='NOTE:' mod='wkpwa'}</strong> {l s='Please wait, till the push notification process is in progress. Also do not refresh this page or click on any other link, ' mod='wkpwa'}</li>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row wk-notifcation-heading">
            <div class="col-sm-6">
                <p class="wk-notifcation-heading-text">
                    {l s='Push Notification Progress' mod='wkpwa'}
                </p>
            </div>
            <div class="col-sm-6">
                <p class="text-right wk-notifcation-heading-status">
                    <span id="wk-notifcation-percent">0%</span> {l s='Completed' mod='wkpwa'}
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="progress">
                    <div class="progress-bar progress-bar-info" role="progressbar" style="width: 0%" id="wk-notifcation-progress-bar"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3 col-lg-2">
                <p class='notification-status-value' id="noti-subscriber-total">0</p>
                <p class="notification-status-text">
                    <span>{l s='Total Subscribers' mod='wkpwa'}</span>
                </p>
            </div>
            <div class="col-sm-3 col-lg-2">
                <p class='notification-status-value' id="noti-succes-total">0</p>
                <p class="notification-status-text" id="noti-succes-total-text">
                    <i class="icon-check-circle"></i>
                    <span>{l s='Notification Sent' mod='wkpwa'}</span>
                </p>
            </div>
            <div class="col-sm-3 col-lg-2">
                <p class='notification-status-value' id="noti-expire-total">0</p>
                <p class="notification-status-text" id="noti-expire-total-text">
                    <i class="icon-times-circle"></i>
                    <span>{l s='Expired' mod='wkpwa'}</span>
                </p>
            </div>
        </div>
    </div>
</div>