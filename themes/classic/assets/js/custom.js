/*
 * Custom code goes here.
 * A template should always ship with an empty custom.js
 */
// Initialize tooltip

// Hotjar Tracking Code for https://www.tracheminee.com


(function(h, o, t, j, a, r) {
    h.hj = h.hj || function() {
        (h.hj.q = h.hj.q || []).push(arguments)
    };
    h._hjSettings = { hjid: 5174889, hjsv: 6 };
    a = o.getElementsByTagName('head')[0];
    r = o.createElement('script');
    r.async = 1;
    r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
    a.appendChild(r);
})(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');



$(function() {
    $('.lSSlideWrapper #alex-cover_slider').removeClass('cS-hidden');
    $('[data-toggle="tooltip"]').tooltip();
    //Shows quantity discounts on mouse hover
    $(".pc_prixdegrhover").mouseover(function() {
        $(this).parent().find(".quant_discmod").css("display", "block");
    }).mouseout(function() {
        $('.quant_discmod').css("display", "none");
    });
    // User registration B2B
    $('#authentication input[name="company"], #checkout input[name="company"]').parent('.col-md-12').parent('.form-group').addClass('pc-registration-company');
    $('#authentication input[name="siret"], #checkout input[name="siret"]').parent('.col-md-12').parent('.form-group').addClass('pc-registration-vatnum');
    //Checks if there is empty fields in registration and if true border red
    $("#authentication input").blur(function() {
        if ($(this).val() == '' && $(this).prop('required')) {
            $(this).css("border", "1px solid red");
        } else {
            $(this).css("border", "1px solid rgba(0,0,0,.25)");
        }
    });
    if (window.location.search.indexOf('?pc-compte-pro') > -1) {
        proSpace();
    } else {
        $('.pc-registration-company').css('display', 'none');
        $('.pc-registration-vatnum').css('display', 'none');
        $('.register-form').css('margin', '0px auto');
    }

    function proSpace() {
        $('.pc-registration-company').css('display', 'block');
        $('.pc-registration-vatnum').css('display', 'block');
        $('#authentication h1').append('<span style="background-color: #fdb330;color: white;padding: 0px 10px;margin-left: 10px;border-radius: 10px;">PRO</span>');
        $('#authentication input[name="company"]').attr("required", true);
        $('#authentication input[name="siret"]').attr("required", true);
        $('#panc_createaccount').css('display', 'none');
        $('.proaccountinfo').css('display', 'block');
        $('header h1').empty().html('Créez votre compte<span style="background-color: #fdb330;color: white;padding: 0px 10px;margin-left: 10px;border-radius: 10px;">PRO</span>');
        $('#login-form .login-lbl').html('Déjà client<span style="background-color: #fdb330;color: white;padding: 0px 10px;margin-left: 10px;border-radius: 10px;">PRO</span> | Se connecter');
        $('#pc-register-button').css('display', 'block');
        $('#box-loginespaceprobtn').css('display', 'none');
        $('#pc_clientprosmall').css('display', 'block');
        $('#box-normal_login').css('display', 'block');
    }
    $("#box-loginespaceprobtn #pc-register-buttonright a").click(function(event) {
        event.preventDefault();
        proSpace();
    });
    /* User checkout B2B
    $(".pc-checkoutpart").click(function() {
        $(".pc-checkoutpro").removeClass("active");
        $(this).addClass("active");
        $('.pc-registration-company').css('display', 'none');
        $('.pc-registration-vatnum').css('display', 'none');
        $('#checkout input[name="company"]').attr("required", false);
        $('#checkout input[name="siret"]').attr("required", false);
    });
    $(".pc-checkoutpro").click(function() {
        $(".pc-checkoutpart").removeClass("active");
        $(this).addClass("active");
        $('.pc-registration-company').css('display', 'block');
        $('.pc-registration-vatnum').css('display', 'block');
        $('#checkout input[name="company"]').attr("required", true);
        $('#checkout input[name="siret"]').attr("required", true);
    });*/
    // Product page | Attribute combinations on load
    // if (window.location.href.indexOf("panier") == -1) {


    /*   if ($('#pc_getean13 .ean13').length > 0) {
            $("#pc_prod_ean").html($("#pc_getean13 .ean13").html());
        } else if ($('#pc_prod_ean').is(':empty')) {
            $("#ean13block").remove();
        }
        if ($('.product-reference span').length > 0) {
            $("#pc_prod_ref").html($(".product-reference span").html());
        } else {
            $("#prodref_block").remove();
        }
        if ($('#pc_getean13 .deliverytime').length > 0) {
            var deliverytime = $("#pc_getean13 .deliverytime").html();
            $(".product-delivery .deliverytimeattr").html("Expédié sur " + $("#pc_getean13 .deliverytime").html());
            $(".product-delivery .deliverytime").addClass('hidden');
            $(".product-delivery .deliverytimeattr").addClass("speed-" + deliverytime.replace(" jours", ""));
        } else {
            $(".product-delivery .deliverytimeattr").css('display', 'none');
        }
    }*/


    // Product page | Attribute combinations on change
    $(document).on('change', '.product-variants select', function() {
        setTimeout($(document).ajaxStop(function() {
            if ($('.product-reference span').length > 0) {
                $("#pc_prod_ref").html($(".product-reference span").html());
            } else {
                $("#prodref_block").remove();
            }
            if ($('#pc_getean13 .ean13').length > 0) {
                $("#pc_prod_ean").html($("#pc_getean13 .ean13").html());
            } else if ($('#pc_prod_ean').is(':empty')) {
                $("#ean13block").remove();
            }
            if ($('#pc_getean13 .deliverytime').length > 0) {
                let classDAList = $(".product-delivery .deliverytimeattr").attr("class");
                let classPDList = $(".product-delivery").attr("class");
                let classDAArr = classDAList.split(/\s+/);
                let classPDArr = classPDList.split(/\s+/);
                let deliverytime = $("#pc_getean13 .deliverytime").html();
                if (classDAArr[1] != 'hidden') {
                    $(".product-delivery .deliverytimeattr").removeClass(classDAArr[1]);
                }
                $(".product-delivery .deliverytimeattr").html("Expédié sur " + deliverytime);
                $(".product-delivery .deliverytimeattr").css('display', 'unset');
                $(".product-delivery .deliverytime").addClass('hidden');
                $(".product-delivery .deliverytimeattr").addClass("speed-" + deliverytime.replace(" jours", ""));
                $(".product-delivery img").attr('src', '/img/deliveryspeed/' + deliverytime.replace(" jours", "") + '.png');
            } else {
                let classDAList = $(".product-delivery .deliverytimeattr").attr("class");
                let classPDList = $(".product-delivery").attr("class");
                let classDAArr = classDAList.split(/\s+/);
                let classPDArr = classPDList.split(/\s+/);
                $(".product-delivery .deliverytimeattr").css('display', 'none');
                $(".product-delivery .deliverytime").removeClass('hidden');
                $(".product-delivery .deliverytimeattr").removeClass(classDAArr[1]);
                $(".product-delivery img").attr('src', '/img/deliveryspeed/' + classPDArr[2].replace("speed-", "") + '.png');
            }
        }), 1000);
    });
    //Checkout page payements
    if ($("body").is("#checkout")) {
        $("#payment-option-2-container label span").append(" via Hipay");
        $("#payment-option-2-container label").append("<img src='/img/payements/hipay.png'/>");
        /* $("#payment-option-2-container label").append("<img src='/img/payements/visa.jpg'/>");
        $("#payment-option-2-container label").append("<img src='/img/payements/maestro.jpg'/>");
        $("#payment-option-2-container label").append("<img src='/img/payements/cb.jpg'/>");
        $("#payment-option-1-container label span").append(" via Stripe");
        $("#payment-option-1-container label").append("<img src='/img/payements/stripe.png'/>"); */
        $("#payment-option-1-container label").append("<img src='/img/payements/bankwire.png'/>");
        // $("#delivery").prepend("<label> Info: <br> Suite à la fermeture estivale de nous fournisseurs et transporteurs l’achats effectués pendant le mois d’Aout serons livré à partir de la 1º semaine Septembre. <br> Merci de votre compréhension.</label> <hr style='margin-top:10px;margin-bottom:20px;'>");
    }
    $(document).ready(function() {
        $("#mobile_searchclose").click(function() {
            $("#_mobile_search #search_widget").removeClass("searchdisplayblockimp");
        });
        $("#pull_searchmobile").click(function() {
            $("#_mobile_search #search_widget").addClass("searchdisplayblockimp");
        });
    });
});
var btn = $('#button_gotop');
$(window).scroll(function() {
    if ($(window).scrollTop() > 300) {
        btn.addClass('show');
    } else {
        btn.removeClass('show');
    }
});
btn.on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({ scrollTop: 0 }, '300');
});
$("#order-detail .box.messages .message-text").click(function() {
    $(this).siblings(".message-date").toggleClass("hidden");
});
$(document).ready(function() {
    if (window.location.href.indexOf("commande") > -1) {
        var customer_group = $('input[name=customer_group]').val();
        if (customer_group != 4) {
            $('input[name=company]').parent().parent().remove();
            $('input[name=vat_number]').parent().parent().remove();
        } else {
            $('input[name=company]').parent().parent().css('display', '');
            $('input[name=company]').attr("required", true);
            $('input[name=vat_number]').attr("required", true);
        }
    }
    if (window.location.href.indexOf("?pc-compte-pro") == -1) {
        var customer_group = $('input[name=customer_group]').val();
        if (customer_group != 4) {
            $('input[name=company]').parent().parent().remove();
            $('input[name=siret]').parent().parent().remove();
        } else {
            $('input[name=siret]').attr("required", true);
            $('input[name=company]').attr("required", true);
            $('input[name=tva_number]').attr("required", true);
        }
    }
    $("#mobilemenu-shower").click(function() {
        $("#iqitmegamenu-mobile #cbp-spmenu-overlay").toggleClass("cbp-spmenu-overlay-show");
        $("#iqitmegamenu-mobile #iqitmegamenu-accordion").toggleClass("cbp-spmenu-open");
        $("body").toggleClass("cbp-spmenu-push-toright");
    });
    $(".product-information > form .product-message").click(function() {
        $(".product-information #add-to-cart-or-refresh .add-to-cart").attr("disabled", true);
    });
    var choosed_incline = $("#group_23").find(":checked").attr("value");
    if (choosed_incline == 1303) {
        $(".product-information > form span").html("");
    } else if (choosed_incline == 1304) {
        $(".product-information > form span").html("");
    }
    $(".category-description").click(function() {
        $(this).toggleClass("hidden-xs-up");
        $(".category-description-full").toggleClass("hidden-xs-up");
    });
    $(".category-description-full").click(function() {
        $(this).toggleClass("hidden-xs-up");
        $(".category-description").toggleClass("hidden-xs-up");
    });
    $("#custom-text").click(function() {
        $("#custom-text div div").toggleClass("hidden-xs-down");
        $("#custom-text .fa").toggleClass("rotate-180");
    });
    $(".product-description .pc-htwo_custom").click(function() {
        $(".pc-prod_description").toggleClass("hidden-xs-down");
        $(".product-description .pc-htwo_custom .fa").toggleClass("rotate-180");
    });
    $("#product-details .pc-htwo_custom").click(function() {
        $(".product-features").toggleClass("hidden-xs-down");
        $("#product-details #attachments").toggleClass("hidden-xs-down");
        $("#product-details .pc-htwo_custom .fa").toggleClass("rotate-180");
    });
    var header_top = $(".header-top").offset();
    var droptocartimg = $('.add > span');
    if ($(window).width() > 1000 && window.location.href.indexOf("commande") == -1 && window.location.href.indexOf("connexion") == -1) {
        $('.slides').mousemove(function(e) {
            var scrollwidth = $(this).get(0).scrollWidth;
            var width = $(this).width();
            var margin = (width / scrollwidth) * width;
            var x = e.clientX;
            var xPercentage = x / width;
            $(this).get(0).scrollTo(xPercentage * scrollwidth - margin, 0);
        });
        $(".btn-menu-show").click(function() {
            $(".iqitmegamenu-wrapper").toggleClass("menu-show");
        });
        $(window).scroll(function() {
            if ($(window).scrollTop() > header_top.top) {
                /* $('.header-top').css({ 'position': 'fixed', 'padding-bottom': '20px', 'padding-top': '24px' }); */
                $('.iqitmegamenu-wrapper').addClass("hide-menu-top");
                $('#wrapper').css('margin-top', '170px');
                $('.btn-menu-show').css('display', 'unset');
                $('#_desktop_logo').css('width', '25%');
                $('#header .logo').css('max-height', '49px');
                $('.pc-nomargin .pro').css('max-height', '49px');
                $('.bar_cart_header-bar').css('z-index', '1');
                $('#smoke').css({ 'bottom': '70px', 'left': '191px' });
            } else {
                /* $('.header-top').css({ 'position': 'unset', 'padding-bottom': '72px', 'padding-top': '30px' }); */
                $('.iqitmegamenu-wrapper').removeClass("hide-menu-top");
                $('#wrapper').css('margin-top', '45px');
                $('.btn-menu-show').css('display', '');
                $('#_desktop_logo').css('width', '');
                $('#header .logo').css('max-height', '');
                $('.pc-nomargin .pro').css('max-height', '');
                $('.bar_cart_header-bar').css('z-index', '');
                $('#smoke').css({ 'bottom': '', 'left': '' });
            }
        });
    }
    /*if ($(".wk-app-button").is(":visible")) {
    } else {
        $(".header-banner").css("display", "none")
    }
    $(".ok, .filters_black_bg").click(function () {
        $(".filters_black_bg").addClass("hidden-sm-down");
        $("#search_filters_wrapper").addClass("hidden-sm-down");
    });*/


    $('#brands_slider').lightSlider({
        item: 7,
        loop: true,
        enableDrag: true,
        auto: true,
        pauseOnHover: true,
        keyPress: true,
        pager: false,
    });

    $('#miniatures-slider').lightSlider({
        gallery: true,
        item: 7,
        slideMove: 1,
        keyPress: false,
        pager: true,
        controls: true,
        speed: 5000,
        responsive: [{
                breakpoint: 1900,
                settings: {
                    item: 6,
                    slideMove: 1,
                    speed: 800,
                }
            },
            {
                breakpoint: 1650,
                settings: {
                    item: 5,
                    slideMove: 1,
                    speed: 800,
                }
            },
            {
                breakpoint: 1350,
                settings: {
                    item: 4,
                    slideMove: 1,
                    speed: 400,
                }
            },
            {
                breakpoint: 1100,
                settings: {
                    item: 3,
                    slideMove: 1,
                    speed: 400,
                }
            },
            {
                breakpoint: 1000,
                settings: {
                    item: 2,
                    slideMove: 1,
                    autoWidth: false,
                    pager: false,
                    speed: 400,
                }
            },
            {
                breakpoint: 850,
                settings: {
                    item: 1,
                    slideMove: 1,
                    autoWidth: false,
                    pager: false,
                    speed: 400,
                }
            },
            {
                breakpoint: 400,
                settings: {
                    item: 1,
                    autoWidth: false,
                    slideMove: 1,
                    pager: false,
                    speed: 400,
                }
            },

        ]
    });

    // FORA do if, para que funcione em qualquer página:
    $('.home_cats .home_cats_slider').lightSlider({
        item: 5,
        slideMove: 2,
        speed: 1000,
        pager: false,
        slideMargin: 30,
        controls: true,
        enableDrag: true,
        enableTouch: true,
        responsive: [
            {
                breakpoint: 800,
                settings: {
                    item: 3,
                    slideMove: 1,
                    slideMargin: 6,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    item: 2,
                    slideMove: 1
                }
            }
        ]
    });

    if ($("body").is("#product")) {
        // Product page main image slider




        $('#alex-cover_slider').lightSlider({
            gallery: true,
            item: 1,
            loop: true,
            thumbItem: 5,
            thumbMargin: 5,
            enableDrag: true,
            auto: true,
            pauseOnHover: true,
            keyPress: true,
            pager: true,
            responsive: [{
                    breakpoint: 1350,
                    settings: {
                        thumbItem: 4,
                    }
                },
                {
                    breakpoint: 1150,
                    settings: {
                        thumbItem: 3,
                    }
                },
                {
                    breakpoint: 1000,
                    settings: {
                        gallery: false,
                    }
                },
            ]
        });

        /* início - foto dos produtos com miniaturas */


        $('#alex-cover_slider').parent().css('border', '1px solid #f89e49');

        if ($(window).width() > 1000) {
            var rightcol = $('.prodrightcol').height();
            var leftcol = $('.panc_prodleftcolimage').height();
            var centercol = $('.prodcentercol').height();
            if (centercol > rightcol && centercol > leftcol) {} else {
                if (rightcol < leftcol) {
                    $('.prodcentercol').css('height', leftcol);
                } else {
                    $('.prodcentercol').css('height', rightcol);
                }
            }



            var zoom = false;
            $("#alex-cover_slider .lslide").click(function(e) {
                zoom = !zoom;
                $("#alex-cover_slider .lslide").mousemove(function(e) {
                    var img = $(this).offset();
                    var width = $(this).width();
                    var x = e.pageX - img.left - width / 2;
                    var y = e.pageY - img.top - width / 2;
                    var size = 2;
                    var xPercentage = (-x / width * 25) * size;
                    var yPercentage = (-y / width * 25) * size;
                    if (zoom) {
                        $(this).children().css('transform', 'scale(' + size + ') translate(' + xPercentage + '%, ' + yPercentage + '%)')
                        $(this).css('cursor', 'zoom-out');
                    } else {
                        $(this).children().css('transform', '');
                        $(this).css('cursor', 'zoom-in');
                    }
                });
            });




            $("#alex-cover_slider .lslide").mouseout(function() {
                $(this).children().css('transform', '');
            });
        };
        var prodimgwidth = $('#alex-cover_slider .lslide').width();
        var prodimgheight = $('#alex-cover_slider .lslide').height();
        droptocartimg.css({ 'height': prodimgheight, 'width': prodimgwidth });
        $(".prodcentercol .add-to-cart").on("click", function() {
            var cart;
            if ($(window).width() < 1000) {
                cart = $(".pc_respbotmenu .fa-shopping-cart");
            } else {
                cart = $("#_desktop_cart i.shopping-cart");
            }
            var imgtodrag = $("#alex-cover_slider li.active img");
            if (imgtodrag) {
                var imgclone = imgtodrag.clone();
                imgclone.css({ top: imgtodrag.offset().top, left: imgtodrag.offset().left, opacity: "0.5", position: "absolute", height: "500px", width: "500px", "z-index": "999999" });
                imgclone.appendTo($("body"));
                imgclone.animate({ top: cart.offset().top + 10, left: cart.offset().left + 10, width: 75, height: 75 }, 1000, "easeInOutExpo");
                setTimeout(function() { cart.effect("shake", { times: 2 }, 200); }, 1500);
                imgclone.animate({ width: 0, height: 0 }, function() { $(this).detach(); });
            }
        });

        /* fim do código das imagens do produto com miniaturas */



        $(".js-product-miniature .add-to-cart").on("click", function() {
            var cart;
            if ($(window).width() < 1000) {
                cart = $(".pc_respbotmenu .fa-shopping-cart");
            } else {
                cart = $("#_desktop_cart i.shopping-cart");
            }
            var imgtodrag = $(this).parents('tbody').find(".product-thumbnail img");
            if (imgtodrag) {
                var imgclone = imgtodrag.clone();
                imgclone.css({ top: imgtodrag.offset().top, left: imgtodrag.offset().left, opacity: "0.5", position: "absolute", height: "75px", width: "75px", "z-index": "9999" });
                imgclone.appendTo($("body"));
                imgclone.animate({ top: cart.offset().top + 10, left: cart.offset().left + 10, width: 75, height: 75 }, 1000, "easeInOutExpo");
                setTimeout(function() { cart.effect("shake", { times: 2 }, 200); }, 1500);
                imgclone.animate({ width: 0, height: 0 }, function() { $(this).detach(); });
            }
        });
        $(document).ajaxStop(
            function choose_incline() {
                var choosed_incline = $("#group_23").find(":checked").attr("value");
                if (choosed_incline == 1303) {
                    $(".product-information > form span").html("%");
                } else if (choosed_incline == 1304) {
                    $(".product-information > form span").html("º");
                }
            }
        );
    };
});
(function() {
    let removeSuccess = function() {
        return $('.pc-prodaddtocart').removeClass('success');
    };
    $(document).ready(function() {
        return $('.pc-prodaddtocart').click(function() {
            $(this).addClass('success');
            return setTimeout(removeSuccess, 3000);
        });
    });
}).call(this);


