/**
 * This is main js file. Don't edit the file if you want to update module in future.
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @link	     http://www.globosoftware.net
 * @license   please read license in file license.txt
 */

var favicon = '';

function Getcode(g_url, g_token, g_idcart, g_setting_gentcode) {
    var g_cookies = getCookiecode("g_setcode");
    var g_ptab_show = $('.g_tab_show').val();
    var g_ptab_bg_color = $('.g_tab_bg_color').val();
    var g_ptab_fnt_color = $('.g_tab_fnt_color').val();
    if (g_cookies == '') {
        $.ajax({
            url: g_url + "index.php?controller=getcode&fc=module&module=g_cartreminder",
            method: "POST",
            data: 'name=twitter&token=' + g_token + '&id_cart=' + g_idcart + '&setting=' + g_setting_gentcode,
            success: function(code) {
                if (code != '') {
                    var setcodes = code.split('-');
                    Cookiecode('g_setcode', setcodes['0'], setcodes['1']);
                    setTimeout(function() {
                        var gaudio = document.getElementById("gaudio");
                        gaudio.play();
                        $('.code-views-copy').val(setcodes['0']);
                    }, 500);
                }
            }
        });
    } else {
        setTimeout(function() {
            var gaudio = document.getElementById("gaudio");
            gaudio.play();
            $('.code-views-copy').val(g_cookies);
        }, 500);
    }
}

function updatePPtime() {
    $.ajax({
        url: g_url + "index.php?controller=getcode&fc=module&module=g_cartreminder",
        method: "POST",
        data: 'name=updatePPtime&token=' + g_token + '&id_cart=' + gid_cart + '&day=' + gday + '&hrs=' + ghrs,
        success: function(code) {
            console.log(true);
        }
    });
}
//google.
function shere_gplust(jsonParam) {
    if (("URL: " + jsonParam.href + " state: " + 'on') == ("URL: " + jsonParam.href + " state: " + jsonParam.state)) {

    }
}

function Cookiecode(name, value, days) {
    var date_time = new Date();
    if (name == 'not_exit_page') {
        date_time.setTime(date_time.getTime() + (15 * 60 * 1000));
    } else {
        date_time.setTime(date_time.getTime() + (days * 24 * 60 * 60 * 1000));
    }
    var expires = "expires=" + date_time.toUTCString();
    document.cookie = name + "=" + value + "; " + expires;
}

function getCookiecode(name) {
    var new_name = name + "=";
    var arrays = document.cookie.split(';');
    for (var i = 0; i < arrays.length; i++) {
        var array = arrays[i];
        while (array.charAt(0) == ' ') array = array.substring(1);
        if (array.indexOf(new_name) == 0) return array.substring(new_name.length, array.length);
    }
    return "";
}

function showTitle(gcolor, gbgr, number) {
    favicon = new Favico({
        bgColor: gbgr,
        textColor: gcolor,
        animation: 'popFade'
    });
    favicon.badge(number);
}

function addEvent(obj, evt, fn) {
    if (obj.addEventListener) {
        obj.addEventListener(evt, fn, false);
    } else if (obj.attachEvent) {
        obj.attachEvent("on" + evt, fn);
    }
}

function gPPshow(PPobj, name) {
    var PPsetting = JSON.parse(PPobj);
    setcenter = setInterval(function() {
        var height = $(window).height();
        var content_height = $(".popup_cart-content").outerHeight();
        var top = ((height - content_height) / 2) - 30;
        var barheight = $(".bar_cart_header-bar").outerHeight();
        if (top <= 0) {
            if (barheight <= 70) {
                top = barheight;
            } else {
                top = 0;
            }
        }
        $(".popup_cart-content").css("top", top + "px");
    }, 50);
    $(".close_popup").click(function() {
        if (favicon != '') {
            favicon.reset();
            $.titleAlert.stop();
        }
        $('.popup_cart').hide();
        //Cookiecode('gclose_popup', '1', '1');
    });
    if (PPsetting['displayss'] == 1) {
        if (PPsetting['sosicalfb'] != '') {
            var like_facebook = function(url, all_html) {
                var like_facebook_page = $(all_html).attr("like_facebook_page");
                if (like_facebook_page == 'true') {
                    //Getcode(g_url, g_token, g_idcart, g_setting_gentcode);
                }
            }
            window.fbAsyncInit = function() {
                FB.init({
                    appId: '1255152061196433',
                    status: true,
                    cookie: true,
                    xfbml: true,
                    oauth: true,
                });
                FB.Event.subscribe('edge.create', like_facebook);
            };
            (function(d) {
                var js, id = 'facebook-jssdk';
                if (d.getElementById(id)) {
                    return;
                }
                js = d.createElement('script');
                js.id = id;
                js.async = true;
                js.src = "//connect.facebook.net/" + $('.language_code').val() + "/all.js";
                d.getElementsByTagName('head')[0].appendChild(js);
            }(document));
        }
        if (PPsetting['sosicaltw'] != '') {
            function reward_user(event) {
                if (event) {
                    //Getcode(g_url, g_token, g_idcart, g_setting_gentcode);
                }
            }
            window.twttr = (function(d, s, id) {
                var t, js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//platform.twitter.com/widgets.js";
                fjs.parentNode.insertBefore(js, fjs);
                return window.twttr || (t = { _e: [], ready: function(f) { t._e.push(f) } });
            }(document, "script", "twitter-wjs"));
            twttr.ready(function(twttr) {
                twttr.events.bind('follow', reward_user);
            });
        }
    }

    if (PPsetting['display'] == 1) {
        if (PPsetting['demo'] > 0) {
            Cookiecode("GPPpageexit", 0, '0.5');
        }
        addEvent(document, 'mouseout', function(evt) {
            if (evt.toElement == null && evt.relatedTarget == null && getCookiecode("GPPpageexit") != 1) {
                Cookiecode('GPPpageexit', 1, '0.5');
                showpopupCart(PPsetting);
                if (PPsetting['demo'] < 1) {
                    updatePPtime();
                }
                var gaudio = document.getElementById("gaudio");
                gaudio.play();
            }
        });
        addEvent(document, 'touchend', function(evt) {
            if (evt.toElement == null && evt.relatedTarget == null && getCookiecode("GPPpageexit") != 1) {
                Cookiecode('GPPpageexit', 1, '0.5');
                showpopupCart(PPsetting);
                if (PPsetting['demo'] < 1) {
                    updatePPtime();
                }
                var gaudio = document.getElementById("gaudio");
                gaudio.play();
            }
        });
    } else if (PPsetting['display'] == 2) {
        if (PPsetting['demo'] > 0) {
            Cookiecode("GPPcountpage", 1, '0.5');
        } else {
            if (getCookiecode("GPPcountpage") == "") {
                Cookiecode("GPPcountpage", 1, '0.5');
            } else {
                var cvalue = parseInt(getCookiecode("GPPcountpage")) + 1;
                Cookiecode('GPPcountpage', cvalue, '0.5');
            }
        }
        if (getCookiecode("GPPcountpage") == 1) {
            showpopupCart(PPsetting);
            if (PPsetting['demo'] < 1) {
                updatePPtime();
            }
            var gaudio = document.getElementById("gaudio");
            gaudio.play();
        }
    } else if (PPsetting['display'] == 3) {
        if (getCookiecode("GPPcountpage") == "") {
            Cookiecode("GPPcountpage", 1, '0.5');
        } else {
            var cvalue = parseInt(getCookiecode("GPPcountpage")) + 1;
            Cookiecode('GPPcountpage', cvalue, '0.5');
        }
        if (getCookiecode("GPPcountpage") == 2) {
            if (PPsetting['demo'] > 0) {
                Cookiecode("GPPcountpage", 1, '0.5');
            } else {
                updatePPtime();
            }
            showpopupCart(PPsetting);
            var gaudio = document.getElementById("gaudio");
            gaudio.play();
        }
    }
}
$(document).ready(function() {
    var PPobj = $('#PPobj').val();
    if (typeof PPobj != "undefined" && PPobj != null) {
        gPPshow(PPobj, 'PP');
    }
    $("#close-button-bar").click(function() {
        $('.bar_cart_header-bar').hide();
    });
});

function startTimer(duration, PPsetting) {
    var timer = duration,
        minutes, seconds;
    var extracountdown = setInterval(function() {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);
        console.log(timer);
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        $('.gcartcontent-countdown-tiem-minslabel').text(minutes);

        $('.gcartcontent-countdown-tiem-SECSlabel').text(seconds);

        if (--timer < 0) {
            timer = duration;
            if (PPsetting['reset_countdown'] != 1)
                clearInterval(extracountdown);
        }
    }, 1000);
}

function showpopupCart(PPsetting) {
    $('.popup_cart').show();
    if ($('.popup_cart .gcartextra-countdown').length > 0 && PPsetting['countdown'] > 0) {
        startTimer(60 * parseInt(PPsetting['countdown']), PPsetting);
    }
}