/**
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
*/

$(document).ready(function () {
    // hide hook section from configuration
    var isAddToDesktop = $("input[name='WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP']:checked").val();
	if (isAddToDesktop === 'undefined') {
		isAddToDesktop = 0;
	}
    displayAssociatedDivForHook(isAddToDesktop);
    $(document).on('change', "input[name='WK_PWA_PUSH_DISPLAY_ADD_TO_DESKTOP']", function(){
		var isAddToDesktop = $(this).val();
		displayAssociatedDivForHook(isAddToDesktop);
	});
});

function displayAssociatedDivForHook(isEnable) {
    if (isEnable == 1){
        $('.WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK').closest('.form-group').slideDown();
    } else {
        $('.WK_PWA_PUSH_NOTIFICATION_ADD_TO_DESKTOP_HOOK').closest('.form-group').slideUp();
    }
}