/**
 * This is main js file. Don't edit the file if you want to update module in future.
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @link	     http://www.globosoftware.net
 * @license   please read license in file license.txt
 */

$(document).ready(function() {
    if ($('.gfix_align').length > 0) {
        $('.gfix_align').closest('.form-group').removeClass('form-group').addClass('form-group2');
        $('.gfix_align').removeClass('gfix_align');
    }

    if ($('.gfix_align2').length > 0) {
        $('.gfix_align2').closest('.form-group').removeClass('form-group').addClass('form-group3');
        $('.gfix_align2').removeClass('gfix_align2');
    }

    $('#getHTMLPP').click(function(e) {
        getHTMLPP();
    });
    $('#getALLtemplate').on('shown.bs.modal', function(e) {
        $('#getALLtemplate').resize();
    });
    $("#getALLtemplate").on("hidden.bs.modal", function() {
        $('#showPPLang').slick('unslick');
    });
    $('#gabandoned_popup_form').submit(function(e) {
        savehomepopup(e, true);
    });
    $(".submitPreviewpopup").click(function(e) {
        savehomepopup(e, false);
    });
    $('.selectPP').click(function(e) {
        var id = $('.slick-slide.slick-active').attr('id');
        var PPhtml = tinymce.get('html_' + id_language).getContent();
        if (PPhtml == '' || $('#name_' + default_language).val() == '') {
            languages.forEach(function(entry) {
                var valuePP = $('#textPP_' + id).text();
                tinymce.get('html_' + entry['id_lang']).setContent(valuePP);
            });
        } else {
            var valuePP = $('#textPP_' + id).text();
            tinymce.get('html_' + id_language).setContent(valuePP);
        }
    });
    $('input[name=displayss]').change(function() {
        if ($(this).val() == 1) {
            $('.Soscialshow').show();
        } else {
            $('.Soscialshow').hide();
        }
    });
    $('input[name=autocodetype]').change(function() {
        if ($(this).val() == 3) {
            $('.autocodeshow.active').hide();
            $('.amount_discountoff').hide();
        } else {
            $('.autocodeshow.active').show();
            $('.amount_discountoff').hide();
            if ($(this).val() == 2)
                $('.amount_discountoff').show();
        }
    });
    $('input[name=autocode]').change(function() {
        if ($(this).val() == 1) {
            $('.autocodeshow').show();
            if ($('input[name=autocodetype]:checked').val() == 3) {
                $('.autocodeshow.active').hide();
                $('.amount_discountoff').hide();
            }
            $('.codetext').hide();
        } else {
            $('.codetext').show();
            $('.autocodeshow').hide();
        }
    });
    /* file*/
    $('#imgbackground-selectbutton').click(function(e) {
        $('#imgbackground').trigger('click');
    });

    $('#imgbackground-name').click(function(e) {
        $('#imgbackground').trigger('click');
    });

    $('#imgbackground-name').on('dragenter', function(e) {
        e.stopPropagation();
        e.preventDefault();
    });

    $('#imgbackground-name').on('dragover', function(e) {
        e.stopPropagation();
        e.preventDefault();
    });

    $('#imgbackground-name').on('drop', function(e) {
        e.preventDefault();
        var files = e.originalEvent.dataTransfer.files;
        $('#imgbackground')[0].files = files;
        $(this).val(files[0].name);
    });
    /*END*/
    $('#imgbackground').change(function(e) {
        var image_holder = $(".review_popup");
        if ($(this)[0].files !== undefined) {
            var files = $(this)[0].files;
            var name = '';

            $.each(files, function(index, value) {
                name += value.name + ', ';
            });
            $('#imgbackground-name').val(name.slice(0, -2));
        } else {
            var name = $(this).val().split(/[\\/]/);
            $('#imgbackground-name').val(name[name.length - 1]);
        }
    });
});

function getHTMLPP() {
    var form = $('#gabandoned_popup_form')[0];
    if (window.FormData !== undefined) {
        var formData = new FormData();
        formData.append('action', 'getHTMLPP');
        formData.append('id_lang', id_language);
        formData.append('id_pp', $('#id_gabandoned_popup').val());
        $.ajax({
            async: false,
            type: "POST",
            dataType: "json",
            url: currentIndex + "&token=" + token,
            crossDomain: true,
            data: formData,
            mimeType: "multipart/form-data",
            processData: false,
            contentType: false,
            success: function(data, nextstep, jqXHR) {
                var htmlPP = '';
                $.each(data.html, function(index, value) {
                    htmlPP += '<div id="' + value.id_gabandoned_popup + '" >';
                    htmlPP += value.html;
                    htmlPP += '<textarea class="hide" id="textPP_' + value.id_gabandoned_popup + '">' + value.html + '</textarea>';
                    htmlPP += '</div>';
                });
                $('#getALLtemplate').resize();
                $('#showPPLang').html(htmlPP);
                $('#showPPLang').slick({
                    infinite: true,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    transform: 'none',
                    arrows: true,
                    autoplay: true,
                    autoplaySpeed: 8000,
                    prevArrow: "<a href='#' class='PPslick-prev'><i class='fa fa-chevron-left' aria-hidden='true''></i></a>",
                    nextArrow: "<a href='#' class='PPslick-next'><i class='fa fa-chevron-right' aria-hidden='true'></i></a>",
                });
            },
            error: function(jqXHR, nextstep, errorThrown) {
                showErrorMessage(jqXHR);
                return false;
            }
        });
    }
}

function savehomepopup(e, f) {
    e.preventDefault();
    tinyMCE.triggerSave();
    var form = $('#gabandoned_popup_form')[0];
    if (window.FormData !== undefined) {
        var formData = new FormData(form);
        formData.append('action', 'SubmitPopup');
        formData.append('saveANDstay', f);
        $.ajax({
            async: false,
            type: "POST",
            dataType: "json",
            url: currentIndex + "&token=" + token,
            crossDomain: true,
            data: formData,
            mimeType: "multipart/form-data",
            processData: false,
            contentType: false,
            success: function(data, nextstep, jqXHR) {
                if (data.res == true || data.res == 'true') {
                    if (f == false) {
                        window.open(data.link_blank, '_blank');
                    }
                    location.href = data.redirectAdmin;
                } else {
                    showErrorMessage(data.status);
                }
            },
            error: function(jqXHR, nextstep, errorThrown) {
                showErrorMessage(jqXHR);
                return false;
            }
        });
    };
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode == 59 || charCode == 46)
        return true;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function hideOtherCurreny(id) {
    $('.currencie-field').hide();
    $('.curen-' + id).show();
}