/**
 * mitrocops
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 /*
 * 
 * @author    mitrocops
 * @category seo
 * @package gsnipreview
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */

function report_helpfull_gsnipreview(rid,val){
    $.post(baseDir+'modules/gsnipreview/reviews.php',
        { rid:rid,
          val:val,
          action:'helpfull'
        },
        function (data) {
            if (data.status == 'success') {


                var data_status = data.message;

                $("#block-helpful"+rid).html('');
                $("#block-helpful"+rid).html(data_status);
                $("#block-helpful"+rid).css('opacity',0.5);
                $("#block-helpful"+rid).addClass('helpfull-success');

                var yes = data.params.yes;
                $("#block-helpful-yes"+rid).html('');
                $("#block-helpful-yes"+rid).html(yes);


                var all = data.params.all;
                $("#block-helpful-all"+rid).html('');
                $("#block-helpful-all"+rid).html(all);

                $('#people-folowing-reviews'+rid).css('opacity',0.5);

                setTimeout(function(){
                    $("#block-helpful"+rid).css('opacity',1);
                    $('#people-folowing-reviews'+rid).css('opacity',1);
                }, 300);


            } else {
                $("#block-helpful"+rid).html('');
                $("#block-helpful"+rid).html(data.message);
                $("#block-helpful"+rid).css('opacity',0.5);
                $("#block-helpful"+rid).addClass('helpfull-error');
                setTimeout(function(){
                    $("#block-helpful"+rid).css('opacity',1);
                }, 300);


            }
        }, 'json');
}

function report_abuse_gsnipreview(rid){

    $.post(baseDir+'modules/gsnipreview/reviews.php',
        { rid:rid,
            action:'abuse'
        },
        function (data) {
            if (data.status == 'success') {


                var data = data.params.content;
                //alert(data);

                if ($('div#fb-con-wrapper').length == 0)
                {
                    conwrapper = '<div id="fb-con-wrapper" class="popup-form-box"><\/div>';
                    $('body').append(conwrapper);
                } else {
                    $('#fb-con-wrapper').html('');
                }

                if ($('div#fb-con').length == 0)
                {
                    condom = '<div id="fb-con"><\/div>';
                    $('body').append(condom);
                }

                $('div#fb-con').fadeIn(function(){

                    $(this).css('filter', 'alpha(opacity=70)');
                    $(this).bind('click dblclick', function(){
                        $('div#fb-con-wrapper').hide();
                        $(this).fadeOut();
                        window.location.reload();
                    });
                });


                $('div#fb-con-wrapper').html('<a id="button-close" style="display: inline;"><\/a>'+data).fadeIn();

                $("a#button-close").click(function() {
                    $('div#fb-con-wrapper').hide();
                    $('div#fb-con').fadeOut();
                    window.location.reload();
                });

                $("button#cancel-report").click(function() {
                    $('div#fb-con-wrapper').hide();
                    $('div#fb-con').fadeOut();
                    window.location.reload();
                });

            } else {
                alert(data.message);

            }
        }, 'json');
}





function field_state_change(field, state, err_text)
{

    var field_label = $('label[for="'+field+'"]');
    var field_div_error = $('#'+field);

    if (state == 'success')
    {
        field_label.removeClass('error-label');
        field_div_error.removeClass('error-current-input');
    }
    else
    {
        field_label.addClass('error-label');
        field_div_error.addClass('error-current-input');
    }
    document.getElementById('error_'+field).innerHTML = err_text;

}


function addRemoveDiscountShareReview(data_type,rid){
    $('#facebook-share-review-block').css('opacity',0.5);
    $.post(baseDir+'modules/gsnipreview/reviews.php',
        { rid:rid,
            action:data_type
        },
        function (data) {
            if (data.status == 'success') {


                var voucher_html_share_review = data.params.content;

                $('#facebook-share-review-block').css('opacity',1);
                if(data.length==0) return;
                if ($('div#fb-con-wrapper').length == 0)
                {
                    conwrapper = '<div id="fb-con-wrapper" class="voucher-data"><\/div>';
                    $('body').append(conwrapper);
                } else {
                    $('#fb-con-wrapper').html('');
                }

                if ($('div#fb-con').length == 0)
                {
                    condom = '<div id="fb-con"><\/div>';
                    $('body').append(condom);
                }


                $('div#fb-con').fadeIn(function(){

                    $(this).css('filter', 'alpha(opacity=70)');
                    $(this).bind('click dblclick', function(){
                        $('div#fb-con-wrapper').hide();
                        $(this).fadeOut();
                    });
                });


                $('div#fb-con-wrapper').html('<a id="button-close" style="display: inline;"><\/a>'+voucher_html_share_review).fadeIn();

                $("a#button-close").click(function() {
                    $('div#fb-con-wrapper').hide();
                    $('div#fb-con').fadeOut();
                });

            } else {
                alert(data.message);

            }
        }, 'json');

}

function paging_gsnipreview( page,id_product ){

    $('#gsniprev-list').css('opacity',0.5);
    $('#gsniprev-nav-pre').css('opacity',0.5);

    $.post(baseDir+'modules/gsnipreview/reviews.php',
        {action:'nav',
            page:page,
            id_product:id_product
        },
        function (data) {
            if (data.status == 'success') {

                $('#gsniprev-list').css('opacity',1);
                $('#gsniprev-nav-pre').css('opacity',1);

                $('#gsniprev-list').html('');
                var content = $('#gsniprev-list').prepend(data.params.content);
                $(content).hide();
                $(content).fadeIn('slow');

                $('#gsniprev-nav').html('');
                var paging = $('#gsniprev-nav').prepend(data.params.paging);
                $(paging).hide();
                $(paging).fadeIn('slow');

            } else {
                alert(data.message);
            }
        }, 'json');
}


function trim(str) {
    str = str.replace(/(^ *)|( *$)/,"");
    return str;
}



$(document).ready(function() {
    var is_func_gsnipreview = $.fancybox;

    if (is_func_gsnipreview !== undefined) {
        $("a.fancybox").fancybox();
    }

});



function gsnipreview_open_tab(){
    // for first tab style //
    $.each($('#more_info_tabs li'), function(key, val) {
        $(this).children().removeClass("selected");
    });

    $('#idTab777-my').addClass('selected');

    for(i=0;i < $('#more_info_sheets').children().length;i++){
        $('#more_info_sheets').children(i).addClass("block_hidden_only_for_screen");
    }
    $('#idTab777').removeClass('block_hidden_only_for_screen');

    // for first tab style //


    // for second tab style //
    if($('.nav-tabs').length>0) {

        $.each($('.nav-tabs li'), function (key, val) {
            $(this).removeClass("active");
        });

        $('#idTab777-my').parent().addClass('active');

        for (i = 0; i < $('.tab-content').children().length; i++) {
            $('.tab-content').children(i).removeClass("active");
            $('.tab-content').children(i).removeClass("in");
        }
        $('#idTab777').addClass('in');
        $('#idTab777').addClass('active');

    }
    // for second tab style //
}


$(document).ready(function() {

    var is_bug_gsnipreview = 0;

    if(is_bug_gsnipreview) {
        $('a.btn-gsnipreview').click(function () {
            setTimeout(function () {
                $('#availability_statut').css('display', 'none');
                $('#add_to_cart').css('display', 'block');


            }, 1000);
        });

        $('a[href="#idTab777"]').click(function () {
            setTimeout(function () {
                $('#availability_statut').css('display', 'none');
                $('#add_to_cart').css('display', 'block');


            }, 1000);
        });
    }

    $('a.btn-default-gsnipreview[href="#idTab777"]').click(function(){

        gsnipreview_open_tab();


    });


    $('a#idTab777-my-click[href="#idTab777"]').click(function(){

        gsnipreview_open_tab();



        $('#add-review-block').toggle();
        $('#no-customers-reviews').toggle();



    });

});