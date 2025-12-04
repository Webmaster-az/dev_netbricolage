/**
* This is main js file. Don't edit the file if you want to update module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/

var favicon = '';
$(document).ready(function () {
    var g_ndatecart  = parseInt($('.g_ndatecart').val());
    var g_ndatenow   = parseInt($('.g_ndatenow').val());
    var gdlntime     =  parseInt($('.g_delay_notification').val()) *60;
    var title        = $('.g_notification_title').val();
    var g_shownotify = NgetCookiecodes('g_show_win');
    var g_shownotify_brows = NgetCookiecodes('g_show_brows');
    var g_tab_dalay = parseInt($('.g_tab_dalay').val()) *60;
    var dts = Math.floor(Date.now());
    if ($('.g_tab_show').val() == 1 && g_shownotify_brows !=1 && g_ndatecart + g_tab_dalay <=  g_ndatenow) {
        NCookiecodes('g_show_brows', 1, g_tab_dalay);
        var g_ptab_bg_color = $('.g_tab_bg_color').val();
        var g_ptab_fnt_color = $('.g_tab_fnt_color').val();  
        var gaudio = document.getElementById("gaudio");
        var totalproduct_cart = $('.totalproduct_cart').val();
        //gaudio.play();
        showTitle(g_ptab_fnt_color, g_ptab_bg_color, totalproduct_cart);
        $.titleAlert($('.g_tab_message').val(),{
            interval: 500,
            originalTitleInterval: null,
            duration:0,
            stopOnFocus: true,
            requireBlur: false,
            stopOnMouseMove: true
        });
    }
    $(window).blur(function(e) {
        if (favicon != '') {
            favicon.reset();
            $.titleAlert.stop();
        }
    });
    //$(window).focus(function(e) {
//        if (favicon != '') {
//            favicon.reset();
//            $.titleAlert.stop();
//        }
//    });
});
function showNotification(title, options) {
    var link_cart = $('.g_linkcart').val();
    var timeout = $('.g_delay_notification').val();
    if (!("Notification" in window)) {
        alert($('.g_alert').val());
    } else if (Notification.permission === "granted") {
        var n = new Notification(title, options);
        n.onclick = function(){
            event.preventDefault();
            n.close();
            window.open(link_cart, '_blank');
        };
        setTimeout(function() {
    		n.close();
    	}, 60*60*1000);
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission(function (permission) {
            if (permission === "granted") {
                var n = new Notification(title, options);
                n.onclick = function(){
                    event.preventDefault();
                    n.close();
                    window.open(link_cart, '_blank');
                };
                setTimeout(function() {
            		n.close();
            	}, 60*60*1000);
            }
        });
    }
}
function NCookiecodes(name, value, days) {
    var date_time = new Date();
    date_time.setTime(date_time.getTime() + days);
    var expires = "expires="+date_time.toUTCString();
    document.cookie = name + "=" + value + "; " + expires;
}
function NgetCookiecodes(name) {
    var new_name = name + "=";
    var arrays = document.cookie.split(';');
    for(var i=0; i<arrays.length; i++) {
        var array = arrays[i];
        while (array.charAt(0)==' ') array = array.substring(1);
        if (array.indexOf(new_name) == 0) return array.substring(new_name.length,array.length);
    }
    return "";
}
function showTitle(gcolor, gbgr, number) {
    var totalproduct_cart = $('.totalproduct_cart').val();
    favicon = new Favico({
        bgColor : gbgr,
        textColor : gcolor,
        animation : 'popFade'
    });
    favicon.badge(number);
}