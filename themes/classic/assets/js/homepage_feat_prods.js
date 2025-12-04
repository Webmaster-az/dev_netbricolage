$(document).ready(function() {
    /* $('.featured-products .products').lightSlider({
        item:5,
        loop:false,
        slideMove:1,
        easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
        speed:600,
        slideMargin:0,
        responsive : [
            {
                breakpoint:800,
                settings: {
                    item:3,
                    slideMove:1,
                    slideMargin:6,
                }
            },
            {
                breakpoint:480,
                settings: {
                    item:2,
                    slideMove:1
                  }
            }
        ]
    }); */
    function slide_homeads() {
        if ($(window).width() > 1400) {
            $(".homepg_block a").on({
                mouseenter: function () {
                    var marginToUse = $("#sec_img", this).width();
                    $(".main_img", this).animate({marginLeft : -marginToUse}, 600);
                },
                mouseleave: function () {
                    $(".main_img", this).animate({marginLeft : '0'}, 200);
                }
            });
        }
    } slide_homeads();
    $( window ).resize(function() {
        slide_homeads()
    });
});