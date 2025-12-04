/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author Musaffar Patel <musaffar.patel@gmail.com>
*  @copyright  2015-2021 Musaffar
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Property of Musaffar Patel
*/

PPBSAdminProductTabGeneralController = function(canvas) {

	var self = this;
	self.canvas = canvas;
	self.$canvas = $(canvas);

	self.input_front_conversion_enabled = self.canvas + ' #front_conversion_enabled';
	self.div_conversion_fields = self.canvas + ' .conversion-fields';
	self.div_unit_conversion_wrapper = self.canvas + ' .unit-conversion-wrapper';
	self.div_unit_conversion_list = self.canvas + ' #unit-conversion-list';

	self.render = function() {
		MPTools.waitStart();
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=adminproducttab&route=ppbsadminproducttabgeneralcontroller&action=render');

		var post_data = {
			'id_product': id_product
		};

		$.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			data: post_data,
			success: function (html_result) {
				self.$canvas.html(html_result);
				self.onFrontConversionEnabledChanged($(self.input_front_conversion_enabled));
				MPTools.waitEnd();
			}
		});
	};

	/**
	 * save the general options
	 */
	self.processForm = function() {
		MPTools.waitStart();
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=adminproducttab&route=ppbsadminproducttabgeneralcontroller&action=processform');

		$.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			data: self.$canvas.find(" :input, select").serialize(),
			success: function (result) {
				MPTools.waitEnd();
				$.growl.notice({message: 'General options saved'});
			}
		});
	};

    /**
     * on front end conversion switch changed
     * @param $sender
     */
	self.onFrontConversionEnabledChanged = function($sender) {
	    // uncheck all unit conversion options as these cannot be used simultaneously with this option
        let checked = $sender.is(':checked');
        if (checked) {
            $(self.div_unit_conversion_wrapper).addClass('disabled');
            $(self.div_conversion_fields).removeClass('disabled');
            $(self.div_unit_conversion_wrapper + ' input[type="checkbox"').prop('checked', false);
        } else {
            $(self.div_unit_conversion_wrapper).removeClass('disabled');
            $(self.div_conversion_fields).addClass('disabled');
        }
    }

    /**
     * on unit conversions option checked / unchecked
     */
    self.onUnitConversionCheckboxChanged = function($sender, data) {
        if (data !== 'event') {
            return false;
        }
        if ($(self.input_front_conversion_enabled).is(':checked')) {
            $(self.input_front_conversion_enabled).prop('checked', false);
            $(self.input_front_conversion_enabled).trigger('change', [{source: 'input_front_conversion_enabled'}]);
        }
    };

	self.init = function() {
		self.render();
	};
	self.init();

    /**
     * on unit conversions option checked / unchecked
     */
    $("body").on("change", self.input_front_conversion_enabled, function (event, data) {
        self.onFrontConversionEnabledChanged($(this), data);
        return false;
    });

    /**
     * on unit conversions option checked / unchecked
     */
    $("body").on("change", self.div_unit_conversion_list + ' input.unit_conversions', function () {
        self.onUnitConversionCheckboxChanged($(this), [{source: 'event'}]);
        return false;
    });

    /**
	 * Save general options
	 */
	$("body").on("click", "#ppbs-btn-general-save", function() {
		self.processForm();
		return false;
	});
};

