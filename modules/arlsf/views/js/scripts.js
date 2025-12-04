/*
* 2018 Areama
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@areama.net so we can send you a copy immediately.
*
*
*  @author Areama <contact@areama.net>
*  @copyright  2018 Areama

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Areama
*/

var arlsf = {
    ajaxUrl: null,
    token: null,
    delayFirstMin: 0,
    delayFirstMax: 0,
    delayMin: 0,
    delayMax: 0,
    displayTime: 0,
    displayTimes: 0,
    currentOrder: 0,
    closeLifeTime: 0,
    inAnimation: false,
    outAnimation: false,
    i: null,
    t: null,
    t2: null,
    ordersDisplayed: [],
    lastCartItem: null,
    closed: false,
    sound: false,
    soundTimes: 0,
    currentSoundTimes: 0,
    orderCurrentProduct: 0,
    orderCurrentProductLimit: 0,
    orderCurrentProductTimes: 0,
    cartCurrentProduct: 0,
    cartCurrentProductLimit: 0,
    cartCurrentProductTimes: 0,
    cookieName: 'arlsf_close',
    sessionKeyName: 'arlsf_key',
    sessionKey: null,
    visitorPopupDisplayed: 0,
    init: function(){
        if (arlsf.sound){
            $('body').append('<audio id="arlsf-sound" src="' + arlsf.sound + '"></audio>');
        }
        if (arlsf.getCookie(arlsf.sessionKeyName) == 0){
            arlsf.createCookie(arlsf.sessionKeyName, arlsf.sessionKey, 1);
        }else{
            arlsf.sessionKey = arlsf.getCookie(arlsf.sessionKeyName);
        }
        if (arlsf.getCookie(arlsf.cookieName) != 1){
            setTimeout(function(){
                arlsf.loadOrder();
            }, arlsf.getDelay(arlsf.delayFirstMin, arlsf.delayFirstMax));
        }else{
            arlsf.closed = true;
        }
        $('body').on('mouseenter', '#arlsf-notification', function(){
            clearTimeout(arlsf.t);
            clearTimeout(arlsf.t2);
            clearInterval(arlsf.i);
        });
        $('body').on('click', '#arlsf-notification .arlsf-close-button', function(){
            arlsf.createCookie(arlsf.cookieName, 1, arlsf.closeLifeTime);
            clearTimeout(arlsf.t);
            clearTimeout(arlsf.t2);
            clearInterval(arlsf.i);
            arlsf.closed = true;
            $('#arlsf-notification').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                $('#arlsf-notification').removeClass('active');
            });
            $('#arlsf-notification').removeClass(arlsf.inAnimation).addClass(arlsf.outAnimation);
        });
        $('body').on('mouseleave', '#arlsf-notification', function(){
            arlsf.t = setTimeout(function(){
                if (!arlsf.closed){
                    $('#arlsf-notification').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                        $('#arlsf-notification').removeClass('active');
                    });
                    $('#arlsf-notification').removeClass(arlsf.inAnimation).addClass(arlsf.outAnimation);
                    arlsf.t2 = setTimeout(function(){
                        arlsf.loadOrder();
                    }, arlsf.getDelay(arlsf.delayMin, arlsf.delayMax));
                }
            }, arlsf.displayTime);
        });
    },
    getCookie: function(cookieName){
        if (document.cookie.length > 0) {
            c_start = document.cookie.indexOf(cookieName + "=");
            if (c_start != -1) {
                c_start = c_start + cookieName.length + 1;
                c_end = document.cookie.indexOf(";", c_start);
                if (c_end == -1) {
                    c_end = document.cookie.length;
                }
                return unescape(document.cookie.substring(c_start, c_end));
            }
        }
        return 0;
    },
    createCookie: function(name, value, days){
        var expires;
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else {
            expires = "";
        }
        document.cookie = name + "=" + value + expires + "; path=/";
    },
    loadOrder: function(){
        if (arlsf.displayTimes > 0 && arlsf.displayTimes <= parseInt(arlsf.getCookie('arlsf_c'))){
            return false;
        }
        $.post(
            arlsf.ajaxUrl, {
                id: arlsf.currentOrder,
                token: arlsf.token,
                displayed: arlsf.ordersDisplayed,
                action: 'getOrder',
                lastCart: arlsf.lastCartItem,
                orderCurrentProduct: arlsf.orderCurrentProduct,
                orderCurrentProductCounter: arlsf.orderCurrentProductTimes,
                cartCurrentProduct: arlsf.cartCurrentProduct,
                cartCurrentProductCounter: arlsf.cartCurrentProductTimes,
                sessionKey: arlsf.sessionKey,
                ajax: true,
                visitorPopupDisplayed: arlsf.visitorPopupDisplayed
            }, function(data){
                if (data && data.type && data.type == 'visitor'){
                    arlsf.visitorPopupDisplayed = 1;
                }
                if (data && data.content){
                    if (data.error) {
                        return false;
                    }
                    $('#arlsf-notification').remove();
                    $('body').append(data.content);
                    setTimeout(function(){
                        if (arlsf.displayTimes > 0){
                            var arlsfC = parseInt(arlsf.getCookie('arlsf_c')) + 1;
                            arlsf.createCookie('arlsf_c', arlsfC);
                        }
                        $('#arlsf-notification').addClass('active').addClass('arlsfAnimated').addClass(arlsf.inAnimation);
                        if (data.id_product == arlsf.orderCurrentProduct && data.type == 'order'){
                            arlsf.orderCurrentProductTimes ++;
                        }
                        if (data.id_product == arlsf.cartCurrentProduct && data.type == 'cart'){
                            arlsf.cartCurrentProductTimes ++;
                        }
                        if (arlsf.sound){
                            if (arlsf.getCookie('arlsf_s') != 1){
                                document.getElementById('arlsf-sound').play();
                                arlsf.currentSoundTimes ++;
                            }
                            if (arlsf.soundTimes && arlsf.currentSoundTimes == arlsf.soundTimes){
                                arlsf.createCookie('arlsf_s', 1);
                            }
                        }
                    }, 200);
                    arlsf.t = setTimeout(function(){
                        if (!arlsf.closed){
                            arlsf.t2 = setTimeout(function(){
                                arlsf.loadOrder();
                            }, arlsf.getDelay(arlsf.delayMin, arlsf.delayMax));
                            $('#arlsf-notification').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                                $('#arlsf-notification').removeClass('active');
                            });
                            $('#arlsf-notification').removeClass(arlsf.inAnimation).addClass(arlsf.outAnimation);
                        }
                    }, arlsf.displayTime);
                }else{
                    if (!arlsf.closed){
                        arlsf.t2 = setTimeout(function(){
                            arlsf.loadOrder();
                        }, arlsf.getDelay(arlsf.delayMin, arlsf.delayMax));
                    }
                }
                if (data && data.type && data.type == 'order' && data.order){
                    if (data.reset && data.loop){
                        arlsf.currentOrder = null;
                        arlsf.ordersDisplayed = [];
                    }
                    arlsf.currentOrder = data.order;
                    if (arlsf.ordersDisplayed.indexOf(arlsf.currentOrder) == -1){
                        arlsf.ordersDisplayed.push(arlsf.currentOrder);
                    }
                }else if (data && data.type && data.type == 'cart'){
                    arlsf.lastCartItem = data.lastCart;
                }
            }, 'json'
        );
    },
    getDelay: function(min, max){
        return Math.random() * (max - min) + min;
    }
};