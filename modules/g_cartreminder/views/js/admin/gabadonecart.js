/**

 * This is main js file. Don't edit the file if you want to update module in future.

 * 

 * @author    Globo Jsc <contact@globosoftware.net>

 * @copyright 2017 Globo., Jsc

 * @link	     http://www.globosoftware.net

 * @license   please read license in file license.txt

 */



function reviewemail(id_review) {

    var data_review = $("#" + id_review).attr("data");

    var objectemployee = $('.objectemployee').val();

    var getemail_to = $(".hideemail_" + data_review).val();

    var getemailfrom = $(".employee_" + data_review).find(":selected").text();

    var hideemail = $(".emailtemplate_" + data_review).find(":selected").val();

    var getvaluehide = $("#hide_inputitem_" + data_review + "_" + hideemail).text();

    var getsubject = $(".subjectemail_" + data_review).val();

    document.getElementById("showfrom_" + data_review).innerHTML = getemailfrom;

    document.getElementById("showto_" + data_review).innerHTML = getemail_to;

    document.getElementById("showsubject_" + data_review).innerHTML = getsubject;

    document.getElementById('showemail_' + data_review).innerHTML = getvaluehide;

    var employees = jQuery.parseJSON(objectemployee);

    var ojbshowbcc = '';

    for (i = 0; i < employees.length; i++) {

        if ($("#sendto_" + data_review + "_" + employees[i]["id_employee"]).prop("checked") == true) {

            ojbshowbcc += employees[i]["email"] + ", ";

        } else {

            ojbshowbcc += "";

        }

    }

    document.getElementById("showbcc_" + data_review).innerHTML = ojbshowbcc.substring(0, ojbshowbcc.length - 2);

}

//function sen email an a cart ajax

function send(id_send) {

    var id_cart_abdoned = $("#" + id_send).attr("data");

    var from_email_ajax = $(".employee_" + id_cart_abdoned).find(":selected").val();

    var subjectmail_ajax = $(".subjectemail_" + id_cart_abdoned).val();

    var custommessage_ajax = $(".custommessage_" + id_cart_abdoned).val();

    var emailtemplate_ajax = $(".emailtemplate_" + id_cart_abdoned).find(":selected").val();

    var discountval_ajax = $("#gdiscountvalue_" + id_cart_abdoned).val();

    var discountvalidity_ajax = $("#gvalidity_" + id_cart_abdoned).val();

    var reduction_currency_ajax = $("select[name='reduction_currency_" + id_cart_abdoned + "']").val();

    var reduction_tax_ajax = $("select[name='reduction_tax_" + id_cart_abdoned + "']").val();

    var objectemployee = $('.objectemployee').val();

    if ($("#gpercentage_" + id_cart_abdoned).prop("checked") == true) {

        var discount_type_ajax = 0;

    } else if ($("#gfixed_" + id_cart_abdoned).prop("checked") == true) {

        var discount_type_ajax = 1;

    } else {

        var discount_type_ajax = 2;

    }

    if ($("#gfreeship_on_" + id_cart_abdoned).prop("checked") == true) {

        var freeship_ajax = 1;

    } else if ($("#gfreeship_off_" + id_cart_abdoned).prop("checked") == true) {

        var freeship_ajax = 0;

    }

    var employees = jQuery.parseJSON(objectemployee);

    var bcc = '';

    for (i = 0; i < employees.length; i++) {

        if ($("#sendto_" + id_cart_abdoned + "_" + employees[i]["id_employee"]).prop("checked") == true) {

            bcc += employees[i]["id_employee"] + ", ";

        } else {

            bcc += "";

        }

    }

    var data = {};

    data.token_ajax = token;

    data.from_email_ajax = from_email_ajax;

    data.subjectmail_ajax = subjectmail_ajax;

    data.custommessage_ajax = custommessage_ajax;

    data.emailtemplate_ajax = emailtemplate_ajax;

    data.datasend_ajax = id_cart_abdoned;

    data.discount_type_ajax = discount_type_ajax;

    data.discountval_ajax = discountval_ajax;

    data.discountvalidity_ajax = discountvalidity_ajax;

    data.freeship_ajax = freeship_ajax;

    data.reduction_currency_ajax = reduction_currency_ajax;

    data.reduction_tax_ajax = reduction_tax_ajax;

    data.bcc = bcc;

    data.ajax = 1;

    data.action = "Sendmail";

    data.controller_ajax = help_class_name;

    $.ajax({

        type: "POST",

        url: currentIndex + "&token=" + token,

        data: data,

        dataType: 'json',

        async: true,

        success: function(pudatedata) {

            alert($('.gcartvalid_sendmailsuccessful').val());

            location.reload();

            console.log(pudatedata);

        },

        error: function(pudatedata) {

            alert($('.gcartvalid_sendmailsuccessful').val());

            return false;

        }

    });

}

//show and hide discount

function nonediscount(id) {

    var potid = $("#" + id).attr("data");

    $(".noneform_" + potid).hide();

}



function showdiscount(id) {

    var potidshow = $("#" + id).attr("data");

    $(".noneform_" + potidshow).show();

    if ($("#" + id).val() == 1) {

        $(".noneform_" + potidshow).find('.nonediscounttype_price').addClass('show');

    } else {

        $(".noneform_" + potidshow).find('.nonediscounttype_price').removeClass('show');

    }

}



function isNumberKey(evt) {

    var charCode = (evt.which) ? evt.which : event.keyCode;

    if (charCode == 59 || charCode == 46)

        return true;

    if (charCode > 31 && (charCode < 48 || charCode > 57))

        return false;

    return true;

}



function isNumberKeyend(evt) {

    var charCode = (evt.which) ? evt.which : event.keyCode;

    if (charCode == 59)

        return true;

    if (charCode > 31 && (charCode < 48 || charCode > 57))

        return false;

    return true;

}