/**
* Easy login as Customer
*
* NOTICE OF LICENSE
*
*    @author    Remy Combe <remy.combe@gmail.com>
*    @copyright 2013-2016 Remy Combe
*    @license   go to addons.prestastore.com (buy one module for one shop).
*    @for    PrestaShop version 1.7
*/
$(document).ready(function() {
    $(document).on("keyup", "#loginascustomer_search", function(){
        searchLoginAsCustomers();
    });
    $("#easyloginascustomer-component").click(function (event) {
        var target = $(event.target);
        console.log(target);
        if (target.is("a") || target.is("a i")) {
            return true;
        }
        return false;
    });
    $('#easyloginascustomer-component').insertBefore('#header-employee-container');
    $('#easyloginascustomer-component').removeClass('hidden');
});
var _customer_search, _loginascustomer_url, customers_found = '';
function searchLoginAsCustomers() {
    _customer_search = $('#loginascustomer_search').val();
    $.ajax({
        type: 'POST',
        headers: { "cache-control": "no-cache" },
        url: 'ajax-tab.php' + '?rand=' + new Date().getTime(),
        async: true,
        dataType: 'json',
        data: {
            controller: 'AdminEasyLoginAsCustomer',
            token: loginascustomer_token,
            action: 'searchCustomers',
            ajax: 1,
            customer_search: _customer_search
        },
        success : function(res) {
            customers_found = '<div class="panel clearfix" style="margin-bottom:0px; box-shadow:none;">';
            if (res.found) {
                $.each(res.customers, function(index) {
                    _loginascustomer_url = loginascustomer_url + this.url + '&token=' + loginascustomer_token + loginascustomer_employee_data_url;
                    customers_found += '<div class="panel-heading loginascustomer_line">\
                                            <i class="icon-user"></i> ' + this.firstname + ' ' + this.lastname + ' [ ' + this.id_customer + ' ]&nbsp;-&nbsp;\
                                            <i class="icon-envelope"></i> <a class="dropdown_loginascustomer_link" href="' + _loginascustomer_url + '"' + loginascustomer_new_tab + '>' + this.email + '</a>\
                                            <div class="panel-heading-action" style="float:right;">\
                                                <a class="dropdown_loginascustomer_link" href="' + _loginascustomer_url + '"' + loginascustomer_new_tab + '><i class="icon-chevron-circle-right"></i></a>\
                                            </div>\
                                        </div>';
                });
                $('#loginascustomer_result').html(customers_found + '</div>');
                $('#loginascustomer_result').removeClass('hidden');
            } else {
                $('#loginascustomer_result').addClass('hidden');
            }
        }
    });
}
