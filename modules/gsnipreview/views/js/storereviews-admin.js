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

function gsnipreview_list_storereviews(id,action,value,type_action){

    if(action == 'active') {
        $('#activeitem' + id).html('<img src="../img/admin/../../modules/gsnipreview/views/img/loader.gif" />');
    }

    $.post('../modules/gsnipreview/ajax.php',
        { id:id,
            action:action,
            value: value,
            type_action: type_action
        },
        function (data) {
            if (data.status == 'success') {


                var data = data.params.content;

                var text_action = '';
                if(type_action == 'testimonial'){
                    text_action = 'testimonial';
                }

                if(action == 'active'){

                    $('#activeitem'+id).html('');
                    if(value == 0){
                        var img_ok = 'ok';
                        var action_value = 1;
                    } else {
                        var img_ok = 'no_ok';
                        var action_value = 0;
                    }
                    var html = '<span class="label-tooltip" data-original-title="Click here to activate or deactivate '+text_action+' on your site" data-toggle="tooltip">'+
                            '<a href="javascript:void(0)" onclick="gsnipreview_list_storereviews('+id+',\'active\', '+action_value+',\''+type_action+'\');" style="text-decoration:none">'+
                        '<img src="../img/admin/../../modules/gsnipreview/views/img/'+img_ok+'.gif" />'+
                        '</a>'+
                    '</span>';
                    $('#activeitem'+id).html(html);


                }

            } else {
                alert(data.message);

            }
        }, 'json');
}




// remove add new comment button //
$('document').ready( function() {

    $('#desc-gsnipreview_storereviews-new').css('display','none');


});
// remove add new comment button //


function delete_avatar_storereviews(item_id,id_customer){
    if(confirm("Are you sure you want to remove this item?"))
    {
        $('.avatar-form').css('opacity',0.5);
        $.post('../modules/gsnipreview/ajax.php', {
                action:'deleteimg',
                item_id : item_id,
                id_customer: id_customer
            },
            function (data) {
                if (data.status == 'success') {
                    $('.avatar-form').css('opacity',1);
                    $('.avatar-button15').remove(); // for ps 15,14
                    $('.avatar-form').html('');
                    $('.avatar-form').html('<img src = "../modules/gsnipreview/views/img/avatar_m.gif" />');


                } else {
                    $('.avatar-form').css('opacity',1);
                    alert(data.message);
                }

            }, 'json');
    }

}


