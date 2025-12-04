/**
* This is main js file. Don't edit the file if you want to update module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/
$(document).ready(function(){
    if($('.gfix_align').length > 0){
        $('.gfix_align').closest('.form-group').removeClass('form-group').addClass('form-group2');
        $('.gfix_align').removeClass('gfix_align');
    }
    if($('.gfix_align2').length > 0){
        $('.gfix_align2').closest('.form-group').removeClass('form-group').addClass('form-group3');
        $('.gfix_align2').removeClass('gfix_align2');
    }
    $('#getEML').click(function(e) {
        getEML();
    });
    $('#templateemail').on('shown.bs.modal', function (e) {
        $('#templateemail').resize();
    });
    $("#templateemail").on("hidden.bs.modal", function () {
        $('#showEML').slick('unslick');
    });
    $('.saveemailtemplate').click(function(e) {
        var id     = $('.slick-slide.slick-active').attr('id');
        var PPhtml = tinymce.get('email_htmllang_'+id_language).getContent();
        if(PPhtml =='' || !$('#id_gaddnewemail_template').val()){
            languages.forEach(function(entry) {
                var valuePP = $('#textPP_'+id).text();
                tinymce.get('email_htmllang_'+entry['id_lang']).setContent(valuePP);
            });
        } else {
            var valuePP = $('#textPP_'+id).text();
            tinymce.get('email_htmllang_'+id_language).setContent(valuePP);
        }
    });
});
function getEML(){
    var form = $('#gaddnewemail_template_form')[0];
    if (window.FormData !== undefined) {
        var formData = new FormData();
        formData.append('action', 'getEML');
        formData.append('id_lang', id_language);
        $.ajax({
            async:false,
    		type: "POST",
            dataType: "json",
    		url: currentIndex+"&token="+token,
            crossDomain: true,
    		data: formData,
            mimeType: "multipart/form-data",
            processData: false,
            contentType: false,
    		success: function(data, nextstep, jqXHR)
    		{
    		    var htmlPP  = '';
                $.each(data.html, function( index, value ) {
                    htmlPP  += '<div id="'+value.id_gaddnewemail_template+'" >';
                    htmlPP  += value.email_htmllang;
                    htmlPP  += '<textarea class="hide" id="textPP_'+value.id_gaddnewemail_template+'">'+value.email_htmllang+'</textarea>';
                    htmlPP  += '</div>';
                });
                $('#templateemail').resize();
                $('#showEML').html(htmlPP);
                $('#showEML').slick({
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