/**
 * This is main js file. Don't edit the file if you want to update module in future.
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @link	     http://www.globosoftware.net
 * @license   please read license in file license.txt
 */

$(document).ready(function() {
    $(".addnew_condition").click(function() {
        $('.hide_addnew').show();
        $('.addnew_condition').show();
        $('.cancel_addnew_condition').show();
    });

    if ($('.mColorPickerTrigger').length > 0) {
        $('.mColorPickerTrigger').each(function() {
            $(this).find('img').attr('src', '../img/admin/color.png');
        });
    }
    //if (typeof tooltip != 'undefined') {
    if ($('.glabel-tooltip').length > 0)
        $('.glabel-tooltip').tooltip();
    // }
    $("#addNewreminderGroupextra").click(function() {
        addNewreminderGroupextra();
    });
    $(document).on("click", ".addgcart_group", function() {
        var id = $(this).data("id");
        var id_group = $(this).data("groupid");
        addNewreminderGroupinGroupextra(id, id_group);
    });
    $('.condrimindertype_admintab .tab-page').click(function() {
        if (!$(this).parent('.tab-row').hasClass('active')) {
            $('.condrimindertype_admintab .tab-row').removeClass('active');
            $(this).parent('.tab-row').addClass('active');
            $('.condrimindertype_tab').removeClass('activetab');
            idtabactive = $(this).attr('href');
            if ($(idtabactive).length > 0) $(idtabactive).addClass('activetab');
        }
        return false;
    });
    $('#gconditionandreminder_form').submit(function() {
        if ($('#rulename').val() == '') {
            showErrorMessage($('.abadonevalid_rulename').val());
            return false
        }
        if ($('#hidereminder').val() == '0' || $('#hidereminder').val() == '') {
            showErrorMessage($('.abadonevalid_Reminder').val());
            return false
        }
        return true;
    });
    if ($("#shoppingcart_includetable").length > 0) {
        searchCartshopping('pagination_shoppingpendding', 1, 20, '');
    }
    if ($("#shoppingcart_excludetable").length > 0) {
        searchCartshoppingExclude('pagination_shoppingpendding', 1, 20, '');
    }
    var idactive = $('#conf_id_0 .nav-tabs li.active a').attr('href');
    $(idactive).addClass('active');
    $('#conf_id_0 .nav-tabs li a').click(function() {
        $('.form-group > div').removeClass('active');
        $($(this).attr('href')).addClass('active');
    });

    $(document).on("change", ".select_condition_group", function() {
        GetValuegroupHtml($(this).val(), $(this).data("id"), $(this).data("groupid"));
    });

    $(document).on("click", ".gcartrmove_group,.gcartrmove_groups", function() {
        RemoveGroupReminder(this);
    });
    $(document).on("click", ".addgcart_discount", function() {
        var id = $(this).data("id");
        var id_group = $(".discount_number_" + id).val();
        addDiscounthtml(id, id_group);
    });

    $(document).on("change", ".gcart_search_product", function() {
        var id = $(this).data("id").replace('_search', '');
        var q = $(this).val();
        var check_pro = $("#" + id + "_2").val();
        searchProducts(id, q, check_pro);
    });
    $(document).on("change", ".gcart_search_cat", function() {
        var id = $(this).data("id").replace('_search', '');
        var q = $(this).val();
        var check_cat = $("#" + id + "_2").val();
        searchCat(id, q, check_cat);
    });
    $(document).on("click", ".addNewreminderGroup", function() {
        addNewreminderGroup(this);
    });
    $(document).on("click", ".addNewreminderGroupextra", function() {
        addNewreminderGroupextra(this);
    });
    $(document).on("click", ".gcartshow_extra_country_show", function() {
        $($(this).data("id")).toggleClass("active");
        $(this).find("i").toggleClass("icon-angle-down");
    });
    $(document).on("click", ".sendemail_manul_bulkaction", function() {
        sendEmailMenual();
    });
    // Main form submit
    $('#gconditionandreminder_form').submit(function() {
        if ($(".conditionproduct_select_2").length > 0) {
            $(".conditionproduct_select_2").each(function() {
                $(this).find('option').each(function(i) {
                    $(this).prop('selected', true);
                });
            });
        }
    });
    $(document).on("click", ".search_cartbyriminder", function() {
        searchCartshopping('pagination_shoppingpendding', 1, 20, '');
    });
    /*show template select */
    $('#desc-gaddnewemail_template-new').click(function() {
        $('#gtemplate_email').trigger('click');

        return false;
    });
    /*show template select */
    $('#desc-gabandoned_popup-new').click(function() {
        $('#gtemplate_popup').trigger('click');

        return false;
    });
    $('#selecttemplate_email .select-menulist .gcartadd-item-form .templateList .template').click(function() {
        var id = $(this).data("id");
        window.location.href = $('#desc-gaddnewemail_template-new').attr("href") + "&templatedefault=" + id;
    });
    $('#selecttemplate_popup .select-menulist .gcartadd-item-form .templateList .template').click(function() {
        var id = $(this).data("id");
        window.location.href = $('#desc-gabandoned_popup-new').attr("href") + "&templatedefault=" + id;
    });
    if ($('.gautoload_rte').length > 0) {
        tinySetup({
            editor_selector: "gautoload_rte",
        });
    }
    /*export menu*/
    $('#desc-gaddnewemail_template-duplicate').click(function() {
        menu_checked = '';
        if ($('input[name="gaddnewemail_templateBox[]"]').length > 0) {
            var checkedVals = $('input[name="gaddnewemail_templateBox[]"]:checked').map(function() {
                return this.value;
            }).get();
            menu_checked = checkedVals.join(",");
        } else {
            if ($('#table-gaddnewemail_template').length > 0) {
                if ($('#table-gaddnewemail_template tbody tr').length > 0) {
                    menu_checked = parseInt($('#table-gaddnewemail_template tbody tr').first().children('td').first().html());
                }
            }
        }
        if (menu_checked != '') {
            var data = {};
            data.action = "dupplicape";
            data.item_checkeds = menu_checked;
            $.ajax({
                type: "POST",
                url: currentIndex + "&token=" + token,
                data: data,
                dataType: 'json',
                async: true,
                success: function(timecart) {
                    if (timecart.error) {
                        showSuccessMessage(timecart.warrning);
                        location.reload();
                    }
                },
                error: function(timecart) {
                    showErrorMessage(timecart);
                    return false;
                }
            });
        }
        return false;
    });
    $(document).on("click", ".pagination-shoppingpendding-items-page", function() {
        paginationChange("page_show_shoppingpendding_result", this);
        searchCartshopping('pagination_shoppingpendding', 1, $(this).data("items"), '');
    });

    $(document).on("click", ".pagination-shoppingcheckpendding-items-page", function() {
        paginationChange("page_show_shoppingcheckpendding_result", this);
        searchCartshoppingExclude('pagination_shoppingpendding', 1, $(this).data("items"), '');
    });

    $(document).on('click', '.pagination_shoppingpendding_all ul.pagination li', function() {
        if (!$(this).hasClass('active') && !$(this).hasClass('disabled')) {
            $('.pagination_shoppingpendding_all ul.pagination li').removeClass('active');
            $(this).addClass('active');
            searchCartshoppingExclude('pagination_shoppingpendding', $(this).find('a').data("page"), parseInt($("#shoppingpendding-pagination-items-page").val()), '');
        }
        return false;
    });
    $(document).on('click', '.pagination_shoppingcheckpendding_all ul.pagination li', function() {
        if (!$(this).hasClass('active') && !$(this).hasClass('disabled')) {
            $('.pagination_shoppingcheckpendding_all ul.pagination li').removeClass('active');
            $(this).addClass('active');
            searchCartshoppingExclude('pagination_shoppingpendding', $(this).find('a').data("page"), parseInt($("#shoppingcheckpendding-pagination-items-page").val()), '');
        }
        return false;
    });
    $(document).on("click", ".excluded_shoppingcart", function() {
        var id = $(this).data('id');
        $(this).closest('tr').remove();
        excludeShoppingcart(id);
    });
    $(document).on("click", ".included_shoppingcart", function() {
        var id = $(this).data('id');
        $(this).closest('tr').remove();
        includeShoppingcart(id);
    });
    if ($(".datetimepicker").length > 0)
        $('.datetimepicker').datetimepicker({
            prevText: '',
            nextText: '',
            dateFormat: 'yy-mm-dd',
            currentText: 'Now',
            closeText: 'Done',
            ampm: false,
            amNames: ['AM', 'A'],
            pmNames: ['PM', 'P'],
            timeFormat: 'hh:mm:ss tt',
            timeSuffix: '',
            timeOnlyTitle: 'Choose Time',
            timeText: 'Time',
            hourText: 'Hour',
            minuteText: 'Minute',
            changeYear: true,
            changeMonth: true,
        });
    $(".gcartrmove_discountgroup").click(function() {
        $(this).closest("tr").remove();
    });
    $(document).on('click', '.copy_link', function() {
        copyToClipboard($(this));

        return false;
    });
    $('.buttonpreviewhtmlemail').click(function() {
        $('#gmdpreview').modal('show');
    });
    $('#getidorder_preview').click(function() {
        var bodyMail = tinymce.get('email_htmllang_' + id_language).getContent();
        g_subject = '';
        if ($('.subjectlang_' + id_language).length > 0)
            g_subject = $('.subjectlang_' + id_language).val();
        else if ($('#subjectlang_' + id_language).length > 0)
            g_subject = $('#subjectlang_' + id_language).val();
        var data = {};
        data.action = "PreView";
        data.tpl = bodyMail;
        data.g_subject = g_subject;
        data.id_form = $('.id_form').val();
        data.emailtest_template = $('#emailtest_template').val();
        data.id_order = $('#id_orderpreview').val();
        data.controller_ajax = help_class_name;
        $.ajax({
            type: "POST",
            url: currentIndex + "&token=" + token,
            data: data,
            dataType: 'json',
            async: true,
            success: function(pudatedata) {
                if (pudatedata == true) {
                    alert($('.g_sendtest').val());
                } else {
                    alert($('.g_sendtesterror').val());
                }
            },
            error: function(pudatedata) {
                return false;
            }
        });
    });
    $("#autoconvert").click(function() {
        alert($('.abadonedcartvalid_converhtml').val());
        var valconverthtml = $(".converthtmltotext").val();
        if (valconverthtml == '') {
            languages.forEach(function(entry) {
                var content = tinymce.get('email_htmllang_' + entry['id_lang']).getContent();
                var replace_htmlmail = content.replace(/(<([^>]+)>)/ig, "");
                var replace_htmlmail = replace_htmlmail.replace(/[\r\n]\s*/g, '\n\n');
                var replace_htmlmail = replace_htmlmail.trim();
                $("#email_txtlang_" + entry['id_lang']).val(replace_htmlmail);
            });
        } else {
            var content = tinymce.get('email_htmllang_' + id_language).getContent();
            var replace_htmlmail = content.replace(/(<([^>]+)>)/ig, "");
            var replace_htmlmail = replace_htmlmail.replace(/[\r\n]\s*/g, '\n\n');
            var replace_htmlmail = replace_htmlmail.trim();
            $("#email_txtlang_" + id_language).val(replace_htmlmail);
        }
    });
    $('.showimgprtpl').click(function() {
        var name_image = $(this).data('name');
        if (!!$.prototype.fancybox) {
            $.fancybox.open([{
                type: 'inline',
                autoScale: true,
                minHeight: 30,
                autoDimensions: false,
                autoSize: false,
                width: 665,
                height: 500,
                content: '<img class="fancybox-image" src="' + link_gcart_preview + 'modules/g_cartreminder/views/img/'+name_image+'" title="" alt="">',
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            }], {
                padding: 0
            });
        }

    });
    $('.showimgprtxt').click(function() {
        var name_image = $(this).data('name');
        if (!!$.prototype.fancybox) {
            $.fancybox.open([{
                type: 'inline',
                autoScale: true,
                minHeight: 30,
                autoDimensions: false,
                autoSize: false,
                width: 665,
                height: 500,
                content: '<img class="fancybox-image" src="' + link_gcart_preview + 'modules/g_cartreminder/views/img/'+name_image+'" title="" alt="">',
                helpers: {
                    overlay: {
                        locked: false
                    }
                }
            }], {
                padding: 0
            });
        }

    });
    if ($('#mainchart2').length > 0) {
        if (typeof Date.prototype.format == "undefined") {
            Date.prototype.format = function(format) {
                if (format === undefined)
                    return this.toString();

                var formatSeparator = format.match(/[.\/\-\s].*?/);
                var formatParts = format.split(/\W+/);
                var result = '';

                for (var i = 0; i <= formatParts.length; i++) {
                    switch (formatParts[i]) {
                        case 'd':
                        case 'j':
                            result += this.getDate() + formatSeparator;
                            break;

                        case 'dd':
                            result += (this.getDate() < 10 ? '0' : '') + this.getDate() + formatSeparator;
                            break;

                        case 'm':
                            result += (this.getMonth() + 1) + formatSeparator;
                            break;

                        case 'mm':
                            result += (this.getMonth() < 9 ? '0' : '') + (this.getMonth() + 1) + formatSeparator;
                            break;

                        case 'yy':
                        case 'y':
                            result += this.getFullYear() + formatSeparator;
                            break;

                        case 'yyyy':
                        case 'Y':
                            result += this.getFullYear() + formatSeparator;
                            break;
                    }
                }

                return result.slice(0, -1);
            }
        }
        var mainchart2_width2 = $('#mainchart2').width();
        var mainchart2_height2 = 350;
        if ($('.admingformdashboard').length > 0) mainchart2_height2 = 345;
        nv.addGraph(function() {
            chart2 = nv.models.lineChart()
                .options({
                    duration: 300,
                    useInteractiveGuideline: true
                })
                .x(function(d) { return d.key })
                .y(function(d) { return d.y })
                .height(mainchart2_height2);
            chart2.xAxis
                .axisLabel('')
                .tickFormat(function(d) {
                    var date = new Date(d * 1000);
                    return date.format(gchart_date_format);
                })
                .staggerLabels(true);
            chart2.yAxis
                .axisLabel('')
                .tickFormat(function(d) {
                    if (d == null) {
                        return 'N/A';
                    }
                    return d3.format(',.2d')(d);
                });
            d3.select('#mainchart2 svg')
                .datum(mainchartdatas)
                .attr('height', mainchart2_height2)
                .call(chart2);
            nv.utils.windowResize(chart2.update);
            return chart2;
        });
    }
    $('g.nv-lineChart').attr('transform', 'translate(40px, 40px)');
});
/*fucntion - cartjs*/
function Savetimegetcart() {
    var data = {};
    data.CONFIGGETCARTDAYS = $('#CONFIGGETCARTDAYS').val();
    data.CONFIGGETCARTHRS = $('#CONFIGGETCARTHRS').val();
    data.action = "SaveconfigTime";
    $.ajax({
        type: "POST",
        url: currentIndex + "&token=" + token,
        data: data,
        dataType: 'json',
        async: true,
        success: function(timecart) {
            if (timecart.error == 'true') {
                showSuccessMessage(timecart.update);
            } else {
                showErrorMessage(timecart.error);
            }
        },
        error: function(timecart) {
            showErrorMessage(timecart);
            return false;
        }
    });
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

function addNewreminderGroupextra(evt) {
    $number = parseInt($("#hidenumber-groupriminder").val());
    $.ajax({
        type: "POST",
        url: currentIndex + "&token=" + token,
        data: '&action=getGroupRiminder&number=' + $number,
        dataType: 'json',
        async: true,
        success: function(pudatedata) {
            if (!pudatedata.error) {
                $("#country_group_table > tbody").append(pudatedata.html_groups);
                $("#hidenumber-groupriminder").val($number + 1);
            }
        },
        error: function(pudatedata) {
            return false;
        }
    });
}

function addNewreminderGroupinGroupextra(id, id_group) {
    id_group = parseInt($("#gcart_groups_" + id + " .number_group_group").val());
    $.ajax({
        type: "POST",
        url: currentIndex + "&token=" + token,
        data: '&action=getGroupinGroupRiminder&number=' + id + "&id_group=" + id_group,
        dataType: 'json',
        async: true,
        success: function(pudatedata) {
            if (!pudatedata.error) {
                $("#gcart-tablegroup-" + id + " > tbody").append(pudatedata.html_groups);
                $("#gcart_groups_" + id + " .number_group_group").val(parseInt(id_group) + 1);
            }
        },
        error: function(pudatedata) {
            return false;
        }
    });
}

function nonediscount(id) {
    var potid = $("#" + id).attr("data");
    $(".noneform_" + potid).hide();
}

function showdiscount(id) {
    var potidshow = $("#" + id).attr("data");
    if ($("#" + id).val() == '1') {
        $("#" + id).closest(".modal-body").find(".nonediscounttype_price").addClass("show");
        $("#" + id).closest("#discount_0").find(".nonediscounttype_price").addClass("show");
    } else {
        $("#" + id).closest(".modal-body").find(".nonediscounttype_price").removeClass("show");
        $("#" + id).closest("#discount_0").find(".nonediscounttype_price").removeClass("show");
    }
    $(".noneform_" + potidshow).show();
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

function checkinput(t) {
    $("#" + t).live('change', function() {
        $('input[name="custormmers[]"]').prop('checked', $(this).prop("checked"));
        $('input[name="manuals[custormmer][]"]').prop('checked', $(this).prop("checked"));
    });
}

function RemoveGroupReminder(evt) {
    $(evt).closest("tr").remove();
}

function GetValuegroupHtml(val, id, id_group) {
    $.ajax({
        type: "POST",
        url: currentIndex + "&token=" + token,
        data: '&action=getGroupValueHtml&number=' + id + "&id_group=" + id_group + "&type=" + val,
        dataType: 'json',
        async: true,
        success: function(grouphtmls) {
            if (!grouphtmls.error) {
                $(".group_select_" + grouphtmls.number + "_" + grouphtmls.number_group + " > div ").html(grouphtmls.html_select);

                $(".group_selectval_" + grouphtmls.number + "_" + grouphtmls.number_group + " > div").html(grouphtmls.html_val);
            }
        },
        error: function(grouphtmls) {
            return false;
        }
    });
}

function addConditionGroupOption(item) {
    var id = $(item).attr('id').replace('_add', '');
    $('#' + id + '_1 option:selected').remove().appendTo('#' + id + '_2');
}

function removeConditionGroupOption(item) {
    var id = $(item).attr('id').replace('_remove', '');
    $('#' + id + '_2 option:selected').remove().appendTo('#' + id + '_1');
}

function updateProductConditionGroupDescription(item) {
    /******* For IE: put a product in condition on cart rules *******/
    if (typeof String.prototype.trim !== 'function') {
        String.prototype.trim = function() {
            return this.replace(/^\s+|\s+$/g, '');
        }
    }

    var id1 = $(item).attr('id').replace('_add', '').replace('_remove', '');
    var id2 = id1.replace('_select', '');
    var length = $('#' + id1 + '_2 option').length;
    if (length == 1)
        $('#' + id2 + '_math').val($('#' + id1 + '_2 option').first().text().trim());
    else
        $('#' + id2 + '_math').val(length);
}

function searchProducts(id, q, checkpro) {

    $.ajax({
        type: "POST",
        url: currentIndex + "&token=" + token,
        data: '&action=searchProducts&query=' + q + "&checkpro=" + checkpro,
        dataType: 'json',
        async: true,
        success: function(products) {
            var html = "";
            if (products) {
                const products_shows = products;
                for (const [key, value] of Object.entries(products_shows)) {
                    var formatitem = value.split("|");
                    html += "<option value=" + formatitem['1'] + ">&nbsp;" + formatitem['1'] + " - " + formatitem['0'] + "</option>";
                }
            }
            $("#" + id + "_1").html(html);
        },
        error: function(products) {
            return false;
        }
    });
}

function addNewreminderGroup(evt) {
    $(evt).attr('disabled', 'disabled');
    var tablereminder = document.getElementById('reminder_group_table');
    var trreminder = parseInt($('#hidereminder').val());
    var lengthcol = document.getElementsByClassName('reminder_tr_td').length;
    var rowreminder = tablereminder.insertRow(lengthcol + 1);
    $('#hidereminder').val(trreminder + 1);
    $.ajax({
        type: "POST",
        url: currentIndex + "&token=" + token,
        data: '&action=getDiscountHtml&trreminder=' + trreminder + '&lengthcol=' + lengthcol,
        dataType: 'json',
        async: true,
        success: function(htmls) {
            if (htmls) {
                rowreminder.className = 'reminder_tr_td';
                var col1 = rowreminder.insertCell(0);
                var col2 = rowreminder.insertCell(1);
                var col3 = rowreminder.insertCell(2);
                var col4 = rowreminder.insertCell(3);
                var col5 = rowreminder.insertCell(4);
                col1.innerHTML += htmls.id_reminder;
                col2.innerHTML += htmls.reminder;
                col3.innerHTML += htmls.prequency;
                col4.innerHTML += htmls.discount;
                col5.innerHTML += htmls.remove_reminder;
            }
            $(evt).removeAttr('disabled');
        },
        error: function(htmls) {
            $(evt).removeAttr('disabled');
            return false;
        }
    });
}

function searchCat(id, q, checkcat) {
    $.ajax({
        type: "POST",
        url: currentIndex + "&token=" + token,
        data: '&action=searchCat&query=' + q + "&checkcat=" + checkcat,
        dataType: 'json',
        async: true,
        success: function(cats) {
            var html = "";
            if (cats) {
                const cats_shows = cats;
                for (const [key, value] of Object.entries(cats_shows)) {
                    html += "<option value=" + value['id_category'] + ">&nbsp;" + value['id_category'] + " - " + value['name'] + "</option>";
                }
            }
            $("#" + id + "_1").html(html);
        },
        error: function(products) {
            return false;
        }
    });
}

function addDiscounthtml(id, id_group) {
    $.ajax({
        type: "POST",
        url: currentIndex + "&token=" + token,
        data: '&action=addDiscounthtml&id=' + id + "&id_group=" + id_group,
        dataType: 'json',
        async: true,
        success: function(html) {
            if (html) {
                $("#discount_" + id).find("table tbody").append(html.html);
                if ($("input[name='jsreminder[" + id + "][discounttype]']:checked").val() == "1") {
                    $("#discount_" + id).find(".nonediscounttype_price").addClass("show");
                } else {
                    $("#discount_" + id).find(".nonediscounttype_price").removeClass("show");
                }
                $(".discount_number_" + id).val(parseInt(id_group) + 1);
            }
        },
        error: function(html) {
            return false;
        }
    });
}

function removereminder(f) {
    $("#" + f).closest('tr').remove();
    if ($(".reminder_tr_td").length > 0) {
        $(".reminder_tr_td").each(function(key) {
            $(this).find("td:first-child span.badge").text(parseInt(key) + 1);
            $(this).find("td:first-child span.badge").attr("id", "id_reminder_" + (parseInt(key) + 1));
        });
    }
}

function searchCartshopping(clss, page, numbershow, checkcart) {
    $('#shoppingcart_includetable tbody tr').remove();
    var html = '<tr><td colspan="7" class="loading_table"><div class="Gloading_table"><div></div><div></div><div></div></div></td></tr>';
    $('#shoppingcart_includetable tbody').html(html);
    var form = $('#configuration_form')[0];
    if (window.FormData !== undefined) {
        var formData = new FormData(form);
        formData.append('action', 'SearchShoppingcart');
        formData.append('page', page);
        formData.append('numbershow', numbershow);
        formData.append('checkcart', checkcart);
        $.ajax({
            type: "POST",
            url: currentIndex + "&token=" + token,
            crossDomain: true,
            data: formData,
            mimeType: "multipart/form-data",
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data, nextstep, jqXHR) {
                if (data) {
                    $('.page_show_items_pendding').text(data.total);
                    paginationNumber(data.numberpage, data.page, '.' + clss + '_all', 'shoppingpendding');
                    $('#shoppingcart_includetable tbody').html(data.html);

                } else {
                    var html = '<tr><td class="list-empty" colspan="7"><div class="list-empty-msg"><i class="icon-warning-sign list-empty-icon"></i></div></td></tr>';
                    $('#shoppingcart_excludetable tbody').html(html);
                }
            },
            error: function(jqXHR, nextstep, errorThrown) {
                showErrorMessage(jqXHR);
                return false;
            }
        });
    };
}

function searchCartshoppingExclude(clss, page, numbershow, checkcart) {
    $('#shoppingcart_excludetable tbody tr').remove();
    var html = '<tr><td colspan="7" class="loading_table"><div class="Gloading_table"><div></div><div></div><div></div></div></td></tr>';
    $('#shoppingcart_excludetable tbody').html(html);
    var form = $('#configuration_form')[0];
    if (window.FormData !== undefined) {
        var formData = new FormData(form);
        formData.append('action', 'searchCartshoppingExclude');
        formData.append('page', page);
        formData.append('numbershow', numbershow);
        formData.append('checkcart', checkcart);
        $.ajax({
            type: "POST",
            url: currentIndex + "&token=" + token,
            crossDomain: true,
            data: formData,
            mimeType: "multipart/form-data",
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data, nextstep, jqXHR) {
                if (data) {
                    $('.page_show_checkitems_pendding').text(data.total);
                    paginationNumber(data.numberpage, data.page, '.' + clss + '_all', 'shoppingpendding');
                    $('#shoppingcart_excludetable tbody').html(data.html);

                } else {
                    var html = '<tr><td class="list-empty" colspan="7"><div class="list-empty-msg"><i class="icon-warning-sign list-empty-icon"></i></div></td></tr>';
                    $('#shoppingcart_excludetable tbody').html(html);
                }
            },
            error: function(jqXHR, nextstep, errorThrown) {
                showErrorMessage(jqXHR);
                return false;
            }
        });
    };
}


function paginationChange(clss, evt) {
    $('.' + clss + ' button span').text($(evt).data("items"));
    $('#' + $(evt).data("list-id") + '-pagination-items-page').val($(evt).data("items"));
    if ($('.' + clss + '_all ul.pagination li').length > 0) {
        $('.' + clss + '_all ul.pagination li').each(function() {
            if ($(evt).find('a').attr('data-page') == '1') {
                $(evt).addClass('active');
                return false;
            }
        });
    }
}

function paginationNumber(number, numberactive, html_class, class_list) {
    var page_old1 = numberactive - 1;
    var page_old2 = numberactive + 1;
    var old_disabled = 'disabled';
    var next_disabled = 'disabled';
    var number_old = numberactive - 3;
    var number_new = numberactive + 3;
    if (numberactive > 1) old_disabled = '';
    if (number > 3) next_disabled = '';
    var html = '<ul class="pagination pull-right">';
    html += '<li class="' + old_disabled + '"><a href="javascript:void(0);" class="pagination-' + class_list + '-items-page" data-page="1" data-list-id="' + class_list + '"><i class="icon-double-angle-left"></i></a></li>';
    html += '<li class="' + old_disabled + '"><a href="javascript:void(0);" class="pagination-' + class_list + '-items-page" data-page="' + page_old1 + '" data-list-id="' + class_list + '"><i class="icon-double-angle-left"></i></a></li>';
    if (number_old >= 2)
        html += "<li class='disabled'><a href='javascript:void(0);'>…</a></li>";
    for (i = 1; i <= number; i++) {
        if (number_old > i || number_new < i) {
            continue;
        }
        var active = "";
        if (i == numberactive)
            active = "active";
        html += '<li class="' + active + '"><a href="javascript:void(0);" class="pagination-link" data-page="' + i + '" data-list-id="' + class_list + '">' + i + '</a></li>';
    }
    if (number_new < number)
        html += "<li class='disabled'><a href='javascript:void(0);'>…</a></li>";

    html += '<li class="' + next_disabled + '"><a href="javascript:void(0);" class="pagination-' + class_list + '-items-page" data-page="' + page_old2 + '" data-list-id="' + class_list + '"><i class="icon-double-angle-right"></i></a></li>';
    html += '<li class="' + next_disabled + '"><a href="javascript:void(0);" class="pagination-' + class_list + '-items-page" data-page="' + number + '" data-list-id="' + class_list + '"><i class="icon-double-angle-right"></i></a></li>';
    html += '</ul>'
    $(html_class).html(html);
}

function excludeShoppingcart(id) {
    var checkarray = $('.shoppingcheckpendding-items').val();
    checkarray = checkarray.split(",");
    if (!checkarray.includes(id)) {
        checkarray.push(id);
        $('.shoppingcheckpendding-items').val(checkarray.join());
        searchCartshoppingExclude('pagination_shoppingpendding', 1, 20, '');
    }
}

function includeShoppingcart(id) {
    var checkarray = $('.shoppingcheckpendding-items').val();
    checkarray = checkarray.split(",");
    var inarray = checkarray.indexOf('' + id + '');
    if (inarray != -1) {
        checkarray.splice(inarray, 1);
        $('.shoppingcheckpendding-items').val(checkarray.join());
    }
}

function sendEmailMenual() {
    $('.sendemail_manul_bulkaction').attr('disabled', 'disabled');
    $('.gcartlds-ellipsis').css('display', 'inline-block');
    var form = $('#configuration_form')[0];
    if (window.FormData !== undefined) {
        var formData = new FormData(form);
        formData.append('action', 'sendEmailMenual');
        $.ajax({
            type: "POST",
            url: currentIndex + "&token=" + token,
            crossDomain: true,
            data: formData,
            mimeType: "multipart/form-data",
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(data, nextstep, jqXHR) {
                if (data) {
                    showSuccessMessage(data.warrning);
                    $('.sendemail_manul_bulkaction').removeAttr('disabled');
                    $('.gcartlds-ellipsis').hide();
                }
            },
            error: function(jqXHR, nextstep, errorThrown) {
                $('.sendemail_manul_bulkaction').removeAttr('disabled');
                $('.gcartlds-ellipsis').hide();
                showErrorMessage(jqXHR);
                return false;
            }
        });
    };
}

function copyToClipboard(input) {
    var $temp = $("<input>");
    $("body").append($temp);
    if (input.closest('.copy_group').find('.copy_data').hasClass('copy_link')) {
        $temp.val(input.closest('.copy_group').find('.copy_data').text()).select();
    } else {
        $temp.val(input.closest('.copy_group').find('.copy_data').val()).select();
    }

    document.execCommand("copy");
    $temp.remove();
    showSuccessMessage(copyToClipboard_success);
}