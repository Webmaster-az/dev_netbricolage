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


function sendReminderStore(first_second_time,id,url_multistore){

    $('#orders-for-reminder-store').css('opacity',0.5);


    $.post(url_multistore+'modules/gsnipreview/ajax.php', {
            action:'reminder-send',
            type:first_second_time,
            id : id

        },
        function (data) {
            if (data.status == 'success') {

                $('#orders-for-reminder-store').css('opacity',1);


                var data_content = data.params.content;
                //alert(data);

                $('.alert-danger').parent().remove();
                $('.alert-success').parent().remove();
                $('#orders-for-reminder-store').before(data_content);


                var is_error = data.params.is_error;


                if(is_error==0) {
                    var date = new Date();
                    var iso = date.toISOString().match(/(\d{4}\-\d{2}\-\d{2})T(\d{2}:\d{2}:\d{2})/)


                    $('#' + first_second_time + '-time-store-' + id).html('');
                    $('#' + first_second_time + '-time-store-' + id).html('<img src = "../modules/gsnipreview/views/img/ok.gif" />&nbsp;&nbsp;&nbsp;' + iso[1] + ' ' + iso[2]);

                    if(first_second_time == 'first') {

                        second_time_send_html = '<img src="../modules/gsnipreview/views/img/no_ok.gif"/>&nbsp;&nbsp;' +
                            '<a class="btn btn-success" href="javascript://" title="Send order manually"' +
                            'onclick="statusdelayed = confirm(\'Are you sure to want Send order manually\');if(!statusdelayed)return false;sendReminderStore(\'second\',' + id + ',\''+url_multistore+'\' );"' +
                            '>Send order manually</a>';

                        $('#second-time-store-' + id).html('');
                        $('#second-time-store-' + id).html(second_time_send_html);

                    }

                }



            } else {
                $('#orders-for-reminder-store').css('opacity',1);
                alert(data.message);
            }

        }, 'json');

}




