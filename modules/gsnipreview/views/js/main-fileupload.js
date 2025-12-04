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

document.addEventListener("DOMContentLoaded", function(event) {
$(function(){

    var file_total_files_done = 0;

    if (typeof file_max_files_gsnipreview !== 'undefined') {

        // инициализация плагина jQuery File Upload
        $('#file-upload-rev').fileupload({

            url: file_upload_url_gsnipreview + '?action=add',

            //maxFileSize: 734003200,

            type: "POST",

            acceptFileTypes: /(\.|\/)(gif|jpg|jpe?g|png)$/i,

            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            // этот элемент будет принимать перетаскиваемые на него файлы

            // Функция будет вызвана при помещении файла в очередь
            add: function (e, data) {


                if (typeof file_max_files_gsnipreview !== 'undefined') {
                    if ($('#file-files-list .form-group').length >= file_max_files_gsnipreview) {
                        e.preventDefault();
                        alert(file_max_message_gsnipreview);
                        return;
                    } else {


                        // upload data
                        data.submit();
                    }
                }


            },

            done: function (e, data) {

                if (typeof file_max_files_gsnipreview !== 'undefined') {
                    if ($('#file-files-list .form-group').length >= file_max_files_gsnipreview) {
                        e.preventDefault();
                        alert(file_max_message_gsnipreview);
                        return;
                    }
                }


                if (data.result.status == 'error') {
                    e.preventDefault();
                    alert(data.result.message);
                    return;
                } else {

                    if (file_total_files_done > 0) {
                        file_total_files_done = Math.floor((Math.random() * 1000) + 1);
                    }

                    data_context = $('<div></div>').addClass('form-group').appendTo($('#file-files-list'));


                    $('<span></span>').append(
                        '<span class="f-img">' +
                        '<a title="' + data.result.params.name + ' (' + humanizeSize(data.result.params.size) + ')" class="fancybox shown" ' +
                        'data-fancybox-group="other-views" id="file-fancy-' + file_total_files_done + '" >' +
                        '<img id="file-' + file_total_files_done + '" class="file-form-size" title="' + data.result.params.name + ' (' + humanizeSize(data.result.params.size) + ')"' +
                        ' alt="' + data.result.params.name + ' (' + humanizeSize(data.result.params.size) + ')" />' +
                        '</a>' +
                        '</span>' +

                        '&nbsp;<strong>' + data.result.params.name + '</strong> (' + humanizeSize(data.result.params.size) + ')')
                        .appendTo(data_context);


                    var button = $('<button></button>').addClass('btn-gsnipreview btn-default-gsnipreview pull-right-gsnipreview btn-f-del').prop('type', 'button').
                        html('<i class="fa fa-trash"></i><input type="hidden" name="filesrev[]" value="' + data.result.params.name + '"/>')
                        .appendTo(data_context).on('click', function () {

                            var del_name_f = $(this).find('input').val();
                            del_file_gsnipreview(del_name_f);

                            file_total_files_done--;


                            $(this).parent().remove();


                        });

                    $('<div class="clear-gsnipreview"></div>').appendTo(data_context);


                    var rand_file = Math.random();
                    id_file_total_files = file_total_files_done;
                    $('#file-' + id_file_total_files).attr('src', '');
                    $('#file-' + id_file_total_files).attr('src', file_path_upload_url_gsnipreview + data.result.params.name + '?file=' + rand_file);

                    var rand_file = Math.random();
                    $('#file-fancy-' + id_file_total_files).attr('href', '');
                    $('#file-fancy-' + id_file_total_files).attr('href', file_path_upload_url_gsnipreview + data.result.params.name + '?file=' + rand_file);

                    file_total_files_done++;
                }

            },

            progress: function (e, data) {

                $('#file-upload-rev .progress-files-bar').show();
                // Вычисление процента загрузки
                var progress = parseInt(data.loaded / data.total * 100, 10);

                // обновляем шкалу
                $('#file-upload-rev .progress-files-bar .progress-files').css('width', progress + '%');

                if (progress == 100) {
                    $('#file-upload-rev .progress-files-bar').hide();
                }
            },

            fail: function (e, data) {
                // что-то пошло не так
                alert('Error');

            }

        });


        // вспомогательная функция, которая форматирует размер файла
    }


});

});

function humanizeSize(bytes)
{
    if (typeof bytes !== 'number') {
        return '';
    }

    if (bytes >= 1000000000) {
        return (bytes / 1000000000).toFixed(2) + ' GB';
    }

    if (bytes >= 1000000) {
        return (bytes / 1000000).toFixed(2) + ' MB';
    }

    return (bytes / 1000).toFixed(2) + ' KB';
}


function del_file_gsnipreview(name){
    $.post(file_upload_url_gsnipreview+'?action=del',
        { name:name,

        },
        function (data) {
            if (data.status == 'success') {


            } else {


            }
        }, 'json');
}
