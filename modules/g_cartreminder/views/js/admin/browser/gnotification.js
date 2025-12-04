/**
* This is main js file. Don't edit the file if you want to update module in future.
* 
* @author    Globo Jsc <contact@globosoftware.net>
* @copyright 2017 Globo., Jsc
* @link	     http://www.globosoftware.net
* @license   please read license in file license.txt
*/

$(document).ready(function(){
    
    /* update 19-03-2018 */
    function indexTrLine(table){
        var index = 1;
        //console.debug(table+' tbody > tr');
        $(table+' tbody > tr').each(function(){
            $(this).find('.index_tr').html('<span class="badge pull-left">'+index+'</span>');
            index++;
            
        });
    }
    function getFrequencyNotification(){
        frequency_notification =[];
        $('#table_add_new_delay_notification tbody > tr').each(function(){
            if($(this).hasClass('delay_notification')){
                frequency_notification.push($(this).find('.days_delay_notification_val').val()+';'+$(this).find('.hrs_delay_notification_val').val());
            }
        });
        $('#delay_notification').val(frequency_notification.join(','));
    }
    $('.remove_delay').live('click',function(){
        $(this).parents('.delay_notification').remove();
        indexTrLine('#table_add_new_delay_notification');
        getFrequencyNotification();
        return false;
    });
    $('#add_new_delay_notification').click(function(){
        days_delay_notification = parseInt($('.days_delay_notification').val());
        if (isNaN(days_delay_notification) || days_delay_notification < 0 )days_delay_notification = 0;
        hrs_delay_notification = parseInt($('.hrs_delay_notification').val());
        if(isNaN(hrs_delay_notification) || hrs_delay_notification < 0) hrs_delay_notification = 0;
        if(days_delay_notification > 0 || hrs_delay_notification > 0){
            new_delay = '<tr class="delay_notification">';
            new_delay += '<td class="index_tr"></td>';
            new_delay += '<td><div class="controls"><input type="number" min="0" class="days_delay_notification_val form-control" onkeypress="return isNumberKey(event)" value="'+days_delay_notification+'" /></div></td>';
            new_delay += '<td><div class="controls"><input type="number" min="0" class="hrs_delay_notification_val form-control" onkeypress="return isNumberKey(event)" value="'+hrs_delay_notification+'" /></div></td>';
            new_delay += '<td><button class="remove_delay btn btn-default"><i class="icon-remove text-danger"></i></button></td>';
            new_delay += '</tr>';
            $('#tr_add_new_delay_notification').before(new_delay);
            $('.days_delay_notification').val('');
            $('.hrs_delay_notification').val('');
            indexTrLine('#table_add_new_delay_notification');
            getFrequencyNotification();
        }
        return false;
    });
    
    
    /*# update 19-03-2018 */
    
    $('#gabandoned_notification_form').submit(function(e) {
        e.preventDefault();
        getFrequencyNotification();
        
        if ($('#offnotification_for').prop('checked')==true) {
            titlebrowser = true;
            $('.title_notification').each(function(){
                title_val = $(this).val();
                title_name =  $(this).attr('name');
                if(title_name =='title_notification_'+default_language)
                    if(title_val == ''){
                        titlebrowser =  false;
                    } else {
                        title_value = title_val;
                    }
            });
            if(!titlebrowser) {
                showErrorMessage($('.showlog').val());
                return false;
            } else {
                $('.title_notification').each(function(){
                    title_val = $(this).val();
                    title_name =  $(this).attr('name');
                    if(title_name !='title_notification_'+default_language)
                        if(title_val == ''){
                            $(this).val(title_value);
                        };
                });
            }
            if ($('#apponesignal_id').val() == '') {
                showErrorMessage($('.onesignal_id').val());
                return false;
            }
            if ($('#apponesignal_api_id').val() == '') {
                showErrorMessage($('.onesignal_rest_id').val());
                return false;
            }
        }
        if ($('#tabs_for').prop('checked')==true) {
            titletab = true;
            $('.message_tab').each(function(){
                title_val = $(this).val();
                title_name =  $(this).attr('name');
                if(title_name =='message_tab_'+default_language)
                    if(title_val == ''){
                        titletab =  false;
                    } else {
                        title_value = title_val;
                    }
            });
            if(!titletab) {
                showErrorMessage($('.title_tab').val());
                return false;
            } else {
                $('.message_tab').each(function(){
                    title_val = $(this).val();
                    title_name =  $(this).attr('name');
                    if(title_name !='message_tab_'+default_language)
                        if(title_val == ''){
                            $(this).val(title_value);
                        };
                });
            }
        }
        var form = $('#gabandoned_notification_form')[0];
        if (window.FormData !== undefined) {
            var formData = new FormData(form);
            formData.append('action', 'SaveConfig');
        	$.ajax({
        		type: "POST",
        		url: currentIndex+"&token="+token,
                crossDomain: true,
        		data: formData,
                mimeType: "multipart/form-data",
                processData: false,
                contentType: false,
        		success: function(data, nextstep, jqXHR)
        		{
                    if (data != 'okie') {
                        showErrorMessage(data);
                        return false;
                    } else {
                        location.href = currentIndex+"&token="+token+"&savesuccssetfull=1";
                        //location.reload(currentIndex+"&token="+token+"&savesuccssetfull=1");
                    }
        		},
                error: function(jqXHR, nextstep, errorThrown) {
                    showErrorMessage(jqXHR);
                    return false;
                }
        	});
    	};
    });
    /*file*/
	$('#img_icon-selectbutton').click(function(e) {
		$('#img_icon').trigger('click');
	});
    
    $('.show_help_notification').click(function(e) {
		$('#gabandoned_notification_fieldset_browser').find('ul.nav li').removeClass('active');
        $("#gabandoned_notification_fieldset_browser ul.nav li").each(function( index ) {
            var href_link = $(this).find('a').attr('href');
            console.log(href_link);
            if (href_link = '#help_gnotification') {
                $(this).find('a').click();
            }
        });
	});
    
	$('#img_icon-name').click(function(e) {
		$('#img_icon').trigger('click');
	});

	$('#img_icon-name').on('dragenter', function(e) {
		e.stopPropagation();
		e.preventDefault();
	});

	$('#img_icon-name').on('dragover', function(e) {
		e.stopPropagation();
		e.preventDefault();
	});

	$('#img_icon-name').on('drop', function(e) {
		e.preventDefault();
		var files = e.originalEvent.dataTransfer.files;
		$('#img_icon')[0].files = files;
		$(this).val(files[0].name);
	});
	$('#img_icon').change(function(e) {
        var image_holder = $(".show-icon");
		if ($(this)[0].files !== undefined) {
			var files = $(this)[0].files;
			var name  = '';

			$.each(files, function(index, value) {
				name += value.name+', ';
			});
			$('#img_icon-name').val(name.slice(0, -2));
		} else {
			var name = $(this).val().split(/[\\/]/);
			$('#img_icon-name').val(name[name.length-1]);
		}
        if ($(this)[0].files[0] !== undefined) {
           if (typeof (FileReader) != "undefined") {
                var reader = new FileReader();
                reader.onload = function (e) {
                     image_holder.css('background-image', 'url('+e.target.result+')');
                     image_holder.css('background-size', '100% 100%');
                     image_holder.css('background-repeat', 'no-repeat');
                }
                reader.readAsDataURL($(this)[0].files[0]);
            }
        } else {
            image_holder.css('background-image', 'none');
        }
	});
    $('input[name="notification[checkout]"]').on('click', function() {
        if ( $(this).is(':checked') ) {
            $('.button-notification').show();
        } else {
            $('.button-notification').hide();
        }
    });
});
function titlesnotification(e){
    $("#title_shownotiction"+id_language).text(e);
}
function mesagenotification(e){
    $("#message_shownotiction"+id_language).text(e);
}
function checkoutbutton(e){
     $(".text-button-checkout").text(e);
}
