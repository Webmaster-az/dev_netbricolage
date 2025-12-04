/**
* This is main js file. Don't edit the file if you want to update module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/

$(document).ready(function(){
    $('#gabandoned_bar_form').submit(function(e){
        e.preventDefault();
        savehomepopupbar(e, true);
        return false;
    });
});
function savehomepopupbar(e, f){
    titlebar = true;
    $('.title').each(function(){
        title_bval = $(this).val();
        title_bname =  $(this).attr('name');
        if(title_bname == 'title_'+default_language)
            if(title_bval == ''){
                titlebar =  false;
            } else {
                title_bvalue = title_bval;
            }
    });
    if(!titlebar) {
        showErrorMessage($('.title_bar').val());
        return false;
    } else {
        $('.title').each(function(){
            title_bval = $(this).val();
            title_bname =  $(this).attr('name');
            if(title_bname != 'title_'+default_language)
                if(title_bval == ''){
                    $(this).val(title_bvalue);
                };
        });
    }
    e.preventDefault();
    var form = $('#gabandoned_bar_form')[0];
    if (window.FormData !== undefined) {
        var formData = new FormData(form);
        formData.append('action', 'SubmitPopupbar');
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
                if (data.error == 'okie') {
                    location.href = data.link;
                }
    		},
            error: function(jqXHR, nextstep, errorThrown) {
                showErrorMessage(jqXHR);
                return false;
            }
    	});
	};
}
function isNumberKey(evt)
{
   var charCode = (evt.which) ? evt.which : event.keyCode;
   if(charCode == 59 || charCode == 46)
    return true;
   if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
   return true;
}