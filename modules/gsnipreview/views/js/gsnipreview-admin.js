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

function gsnipreview_list(id_review,action,value,token){

    if(action == 'active') {
        $('#activeitem' + id_review).html('<img src="../img/admin/../../modules/gsnipreview/views/img/loader.gif" />');
    }

    $.post('../modules/gsnipreview/reviews_admin.php',
        { id_review:id_review,
            action:action,
            value: value,
            token: token
        },
        function (data) {
            if (data.status == 'success') {


                var data = data.params.content;

                if(action == 'abuse' || action == 'changed') {

                    if ($('div#fb-con-wrapper-admin').length == 0) {
                        conwrapper = '<div id="fb-con-wrapper-admin" class="popup-form-box"><\/div>';
                        $('body').append(conwrapper);
                    } else {
                        $('#fb-con-wrapper-admin').html('');
                    }

                    if ($('div#fb-con').length == 0) {
                        condom = '<div id="fb-con"><\/div>';
                        $('body').append(condom);
                    }

                    $('div#fb-con').fadeIn(function () {

                        $(this).css('filter', 'alpha(opacity=70)');
                        $(this).bind('click dblclick', function () {
                            $('div#fb-con-wrapper-admin').hide();
                            $(this).fadeOut();
                            //window.location.reload();
                        });
                    });


                    $('div#fb-con-wrapper-admin').html('<a id="button-close" style="display: inline;"><\/a>' + data).fadeIn();

                    $("a#button-close").click(function () {
                        $('div#fb-con-wrapper-admin').hide();
                        $('div#fb-con').fadeOut();
                    });

                    $("button#cancel-report").click(function () {
                        $('div#fb-con-wrapper-admin').hide();
                        $('div#fb-con').fadeOut();
                    });

                    if($('#changeditem'+id_review).offset()) {
                        var eTop = $('#changeditem' + id_review).offset().top; //get the offset top of the element
                        pos_top = eTop - $(window).height();
                        //console.log(pos_top); //position of the ele w.r.t window

                        if (pos_top > 0)
                            $('div#fb-con-wrapper-admin').css('top', pos_top + 'px');
                    }

                } else if(action == 'active'){

                    $('#activeitem'+id_review).html('');
                    if(value == 0){
                        var img_ok = 'ok';
                        var action_value = 1;
                    } else {
                        var img_ok = 'no_ok';
                        var action_value = 0;
                    }
                    var html = '<span class="label-tooltip" data-original-title="Click here to activate or deactivate review on your site" data-toggle="tooltip">'+
                            '<a href="javascript:void(0)" onclick="gsnipreview_list('+id_review+',\'active\', '+action_value+',\'\');" style="text-decoration:none">'+
                        '<img src="../img/admin/../../modules/gsnipreview/views/img/'+img_ok+'.gif" />'+
                        '</a>'+
                    '</span>';
                    $('#activeitem'+id_review).html(html);


                }

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


function trim(str) {
    str = str.replace(/(^ *)|( *$)/,"");
    return str;
}



function initAccessoriesAutocomplete(){
    $('document').ready( function() {
        $('#product_autocomplete_input')
            .autocomplete('ajax_products_list.php',{
                minChars: 1,
                autoFill: true,
                max:20,
                matchContains: true,
                mustMatch:true,
                scroll:false,
                cacheLength:0,
                formatItem: function(item) {
                    return item[1]+' - '+item[0];
                }
            }).result(addAccessory);

        $('#product_autocomplete_input').setOptions({
            extraParams: {
                excludeIds : getAccessoriesIds()
            }
        });
    });
}


function addAccessory(event, data, formatted)
{
    if (data == null)
        return false;
    var productId = data[data.length - 1];
    var productName = data[0];


    var $divAccessories = $('#divAccessories');
    var $inputAccessories = $('#inputAccessories');
    var $product_autocomplete_input = $('#product_autocomplete_input');

     $product_autocomplete_input.val('');
    $product_autocomplete_input.val(productName);

    $inputAccessories.val('');
    $inputAccessories.val(productId);


}

function getAccessoriesIds()
{
    if ($('#inputAccessories').val() === undefined) return '';
    if ($('#inputAccessories').val() == '') return ',';
    ids = $('#inputAccessories').val().replace(/\-/g,',');


    return ids;
}


function initCustomersAutocomplete(){
    $('document').ready( function() {
        $('#customer_autocomplete_input')
            .autocomplete('ajax-tab.php',{
                minChars: 1,
                max: 20,
                width: 500,
                selectFirst: false,
                scroll: false,
                dataType: 'json',


                formatItem: function(data, i, max, value, term) {
                    return value;
                },
                parse: function(data) {
                    var items = new Array();
                    for (var i = 0; i < data.length; i++) {
                        items[items.length] = {
                            data: data[i],
                            value: data[i].cname + ' (' + data[i].email + ')'
                        };

                    }


                    return items;
                }

            }).result(function(event, data, formatted) {
                $('#inputCustomers').val(data.id_customer);
                $('#customer_autocomplete_input').val(data.cname + ' (' + data.email + ')');

            });

        var inputCustomersToken = $('#inputCustomersToken').val();
        $('#customer_autocomplete_input').setOptions({
            extraParams: {
                controller: 'AdminCartRules',
                customerFilter: 1,
                token: inputCustomersToken
            }
        });
    });
}


function initCustomersAutocomplete14_13(){
    $('document').ready( function() {
        $('#customer_autocomplete_input')
            .autocomplete('../modules/gsnipreview/backward_compatibility/ajax-tab14_13.php',{
                minChars: 1,
                max: 20,
                width: 500,
                selectFirst: false,
                scroll: false,
                dataType: 'json',


                formatItem: function(data, i, max, value, term) {
                    return value;
                },
                parse: function(data) {
                    var items = new Array();
                    for (var i = 0; i < data.length; i++) {
                        items[items.length] = {
                            data: data[i],
                            value: data[i].cname + ' (' + data[i].email + ')'
                        };

                    }


                    return items;
                }

            }).result(function(event, data, formatted) {
                $('#inputCustomers').val(data.id_customer);
                $('#customer_autocomplete_input').val(data.cname + ' (' + data.email + ')');

            });

        var inputCustomersToken = $('#inputCustomersToken').val();
        $('#customer_autocomplete_input').setOptions({
            extraParams: {
                controller: 'AdminCartRules',
                customerFilter: 1,
                token: inputCustomersToken
            }
        });
    });
}


function delete_avatar(item_id,id_customer){
    if(confirm("Are you sure you want to remove this item?"))
    {
        $('.avatar-form').css('opacity',0.5);
        $.post('../modules/gsnipreview/reviews_admin.php', {
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




function delete_file(item_id){
    if(confirm("Are you sure you want to remove this item?"))
    {
        $('#file-custom-'+item_id).css('opacity',0.5);
        $.post('../modules/gsnipreview/upload.php', {
                action:'deletefile',
                item_id : item_id
            },
            function (data) {
                if (data.status == 'success') {

                    $('#file-custom-'+item_id).css('opacity',1);
                    $('#file-custom-'+item_id).remove();

                } else {

                    $('#file-custom-'+item_id).css('opacity',1);
                    alert(data.message);
                }

            }, 'json');
    }

}


