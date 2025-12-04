/*
* 2013-2021 MADEF IT
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to contact@madef.fr so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    MADEF IT <contact@madef.fr>
*  @copyright 2013-2021 MADEF IT
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

"use strict";

jQuery(function() {
    var RMForm = function() {
        jQuery('[name=displaybarsearch]').change((function() {
            this.updateSearch();
        }).bind(this));

        this.updateSearch();
    }

    RMForm.prototype.updateSearch = function() {
        if (jQuery('[name=displaybarsearch]:checked').val() == '1') {
            jQuery('#displaysearch_on').attr('disabled', false);
            jQuery('#displaysearch_off').attr('disabled', false);
            jQuery('#enablequicksearch_on').attr('disabled', false);
            jQuery('#enablequicksearch_off').attr('disabled', false);
        } else {
            jQuery('#displaysearch_on').attr('checked', false).attr('disabled', true);
            jQuery('#displaysearch_off').attr('checked', true).attr('disabled', true);
            jQuery('#enablequicksearch_on').attr('checked', false).attr('disabled', true);
            jQuery('#enablequicksearch_off').attr('checked', true).attr('disabled', true);
        }
    }

    new RMForm();
});

jQuery(function() {
    if (jQuery('.js-controller').val() !== '-1') {
        jQuery('.js-advanced').parent().parent().hide();
        jQuery('.js-simple').parent().parent().show();
    } else {
        jQuery('.js-advanced').parent().parent().show();
        jQuery('.js-simple').parent().parent().hide();
    }

    jQuery('.js-controller').change(function() {
        if (jQuery('.js-controller').val() !== '-1') {
            jQuery('.js-controller-input').val(jQuery('.js-controller').val());
            jQuery('.js-id').val('');
            jQuery('.js-advanced').parent().parent().hide();
            jQuery('.js-simple').parent().parent().show();
        } else {
            jQuery('.js-controller-input').val('');
            jQuery('.js-json').val('');
            jQuery('.js-advanced').parent().parent().show();
            jQuery('.js-simple').parent().parent().hide();
        }
        autocomplete();

    });

    var getEntityId = function() {
        var json = jQuery('.js-json').val();
        if (!json) {
            return;
        }

        var params = JSON.parse(json);

        switch (jQuery('.js-controller').val()) {
            case 'CmsController':
                return params['id_cms'];
            case 'CategoryController':
                return params['id_category'];
            case 'ProductController':
                return params['id_product'];
        }
    }

    // Auto complete
    var autocomplete = function() {
        if (typeof jQuery().autocomplete != 'undefined') {
            jQuery('.js-id').autocomplete({source: []});

            if (jQuery('.js-controller').val() === '-1' || jQuery('.js-controller').val() == null) {
                return;
            }


            var url = document.URL.replace(/#.*$/, '') + '&autocomplete=1&type=' + jQuery('.js-controller').val();
            jQuery('.js-id')
                .autocomplete(url, {
                    minChars: 1,
                    autoFill: true,
                    max:20,
                    matchContains: true,
                    mustMatch:false,
                    scroll:false,
                    cacheLength:0,
                    formatItem: function(item) {
                        return item[1]+' - '+item[0];
                    }
                }).result(function(event, data, formatted) {
                    if (data == null) {
                        return false;
                    }

                    switch (jQuery('.js-controller').val()) {
                        case 'CmsController':
                            jQuery('.js-json').val('{"id_cms":' + data[1] + '}');
                            break;
                        case 'CategoryController':
                            jQuery('.js-json').val('{"id_category":' + data[1] + '}');
                            break;
                        case 'ProductController':
                            jQuery('.js-json').val('{"id_product":' + data[1] + '}');
                            break;
                    }
                });
        }
    }
    autocomplete();
});
