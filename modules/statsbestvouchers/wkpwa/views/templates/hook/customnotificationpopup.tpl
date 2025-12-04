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
<div class="modal" tabindex="-1" role="dialog" id="wk_custom_permission_modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <img src="{$notification_prompt_logo}" class="img img-responsive" />
            </div>
            <div class="col-md-9">
              {l s='We\'d like to send you notifications for the latest news and updates.' mod='wkpwa'}
            </div>
          </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary wk_permission_prompt_denied" data-dismiss="modal">{l s='No Thanks' mod='wkpwa'}</button>
        <button type="button" class="btn btn-primary wk_permission_prompt_allow" data-dismiss="modal">{l s='Allow' mod='wkpwa'}</button>
      </div>
    </div>
  </div>
</div>
<style>
#wk_custom_permission_modal {
    display:none;
}
</style>