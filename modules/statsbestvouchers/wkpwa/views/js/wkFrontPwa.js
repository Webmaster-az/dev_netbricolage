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

function isMobileDevice() {
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && window.matchMedia('(display-mode: standalone)').matches) {
        return true;
    }
    return false;
}
let deferredInstallPrompt = null;
$(document).ready(function() {
    // Loader will be displayed when page reload in PWA app
    $(window).on('beforeunload', function () {
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && window.matchMedia('(display-mode: standalone)').matches) {
            if (!$('#wk-loader').hasClass('wkLoader')) {
                $('#wk-loader').addClass('wkLoader');
            }
        }
    });

    window.addEventListener('online', function () {
        if (isMobileDevice()) {
            $('#wk-connection-msg').html(appOnline).addClass('wk-msgOnline-typography');
            $('#wk-site-connection').fadeIn("slow");
            setTimeout(function () {
                $('#wk-site-connection').fadeOut("slow");
                $('#wk-connection-msg').html('');
                $('#wk-connection-msg').removeClass('wk-msgOnline-typography');
            }, 5000);
        }
    });
    window.addEventListener('offline', function () {
        if (isMobileDevice()) {
            $('#wk-connection-msg').html(appOffline).addClass('wk-msgOffline-typography');
            $('#wk-site-connection').fadeIn("slow");
            setTimeout(function () {
                $('#wk-site-connection').fadeOut("slow");
                $('#wk-connection-msg').html('');
                $('#wk-connection-msg').removeClass('wk-msgOffline-typography');
            }, 5000);
        }
    });

    var swRegistration = null;
    var isSubscribed = false;
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register(serviceWorkerPath).then(function(registration) {
            swRegistration = registration;
            navigator.serviceWorker.ready.then(function (serviceWorkerRegistration) {
                if ('PushManager' in window) {
                    // Push is supported
                    if (parseInt(WK_PWA_PUSH_NOTIFICATION_ENABLE) && WK_PWA_APP_PUBLIC_SERVER_KEY) {
                        initialiseUI();
                    }
                }
            });
        })
        .catch(function(err) {
            console.log("Service Worker Failed to Register. Reason: ", err);
        })
    }

    function initialiseUI() {
        subscribeUser();
        // Set the initial subscription value
        swRegistration.pushManager.getSubscription()
            .then(function (subscription) {
                isSubscribed = !(subscription === null);
                updateBtn();
            });
    }

    function updateBtn() {
        if (Notification.permission === 'denied') {
            // console.log('Push Messaging Blocked.');
            updateSubscriptionOnServer(null);
            return;
        }

        return true;
    }

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    function subscribeUser() {
        // open custom popup if permission is default
        if (isMobileDevice()) {
            if (custom_prompt_mobile == 1) {
                if(Notification.permission === 'default') {
                    if (!checkCustomPromptCookie()) {
                        $('#wk_custom_permission_modal').modal('show');
                        $('.wk_permission_prompt_allow').click(function(e) {
                            subscribeUserLogic();
                        })
                    }
                } else {
                    subscribeUserLogic();
                }
            } else {
                subscribeUserLogic();
            }
        } else {
            if (custom_prompt_desktop == 1) {
                if(Notification.permission === 'default') {
                    if (!checkCustomPromptCookie()) {
                        $('#wk_custom_permission_modal').modal('show');
                        $('.wk_permission_prompt_allow').click(function(e) {
                            subscribeUserLogic();
                        })
                    }
                } else {
                    subscribeUserLogic();
                }
            } else {
                subscribeUserLogic();
            }
        }
    }
    function subscribeUserLogic() {
        swRegistration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(WK_PWA_APP_PUBLIC_SERVER_KEY),
        })
        .then(function(subscription) {
            // console.log('User is subscribed.');
            updateSubscriptionOnServer(subscription);
            isSubscribed = true;
            updateBtn();
        })
        .catch(function(err) {
            console.log('Failed to subscribe the user: ', err);
            updateBtn();
        });
    }

    function updateSubscriptionOnServer(subscription) {
        // TODO: Send subscription to application server
        if (subscription) {
            // Subscribe code here
            saveTokenInServer(subscription);
        } else {
            // Unsubscribe code here
        }

        return true;
    }

    function saveTokenInServer(subscription) {
        var subscriberId = subscription.endpoint.split("/").slice(-1)[0];
        var endpoint = subscription.endpoint;
        var userPublicKey = subscription.getKey('p256dh');
        var userAuthToken = subscription.getKey('auth');
        userPublicKey = userPublicKey ? btoa(String.fromCharCode.apply(null, new Uint8Array(userPublicKey))) : null,
        userAuthToken = userAuthToken ? btoa(String.fromCharCode.apply(null, new Uint8Array(userAuthToken))) : null,

        $.ajax({
            url: clientTokenUrl,
            data: {
                token: subscriberId,
                endpoint: endpoint,
                userPublicKey: userPublicKey,
                userAuthToken: userAuthToken,
                action: 'addToken',
            },
            method: 'POST',
            dataType: 'json',
            success: function(result) {
                if (result.success) {
                    console.log("You have successfully subscribed for push notifications!");
                }
            }
        })
    }
    $(document).on('click', '.wk_permission_prompt_denied', function(e) {
        setCustomPromptCookie(custom_prompt_lifetime);
    })

    $(document).on('click', '.wk-app-button', function(e) {
        if (installPromptEvent) {
            // Show the modal add to home screen dialog
            installPromptEvent.prompt();
            // Wait for the user to respond to the prompt
            installPromptEvent.userChoice.then((choice) => {
                if (choice.outcome === 'accepted') {
                    console.log('User accepted the A2HS prompt');
                } else {
                    console.log('User dismissed the A2HS prompt');
                }
                // Clear the saved prompt since it can't be used again
                installPromptEvent = null;
            });
        }
    })

    window.addEventListener('appinstalled', (event) => {
        // Log install to analytics
        $(".wk-app-btn-wrapper").hide();
        $("#wk-app-banner-close").trigger("click");
    });
    $("#wk-addToHomeScreen-banner").on('click', function (e) {
        $(".wk-app-button").trigger("click");
        // After first click remove banner from DOM
        $("#wk-app-banner-close").trigger("click");
    });
    $("#wk-app-banner-close").on('click', function (e) {
        e.preventDefault();
        $(this).parent('div').remove();
        $.ajax({
            url: clientTokenUrl,
            data: {
                action: 'bannerClosed',
            },
            method: 'POST',
            dataType: 'json',
            success: function(result) {
                if (result.success) {
                    console.log("Banner closed");
                }
            }
        });
    });
});
var installPromptEvent;
window.addEventListener('beforeinstallprompt', (event) => {
    // Prevent Chrome <= 67 from automatically showing the prompt
    $(".wk-app-button").removeClass('wkhide');
    event.preventDefault();
    // Stash the event so it can be triggered later.
    installPromptEvent = event;
    $('#wk-addToHomeScreen-banner').show();
});
function setCustomPromptCookie(lifetime)
{
    var now = new Date();
    now.setTime(now.getTime() + lifetime * 3600 * 1000);
    document.cookie = "wk_pwa_custom_prompt=allow_prompt;expires="+ now.toUTCString() + ";"
}

function checkCustomPromptCookie()
{
    var name = "wk_pwa_custom_prompt=";
    var cs = document.cookie.split(';');
    for(var i = 0; i < cs.length; i++) {
        var c = cs[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            if (c.substring(name.length, c.length) != "") {
                return true;
            }
        }
    }
    return false;
}