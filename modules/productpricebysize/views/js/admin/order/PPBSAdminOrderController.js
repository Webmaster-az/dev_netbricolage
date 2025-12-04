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

PPBSAdminOrderController = function() {
	var self = this;
	self.btn_ppbs_customization_edit = '.btn-ppbs-customization-edit';
	self.btn_ppbs_customization_apply = '.btn-ppbs-customization-apply';
	self.input_ppbs_customization_price = "#ppbs-customization-price";
	self.lbl_ppbs_customization_price = '#lbl-ppbs-customization-price';

	/**
	 * remove any existing customizations panels from the document
 	 */
	self.removeExistingCustomizers = function() {
		$(".ppbs-customization-edit").remove();
	};

	/**
	 * Enable the button which allows customer to apply change to measurements
 	 */
	self.enableApply = function() {
		$(self.btn_ppbs_customization_apply).prop('disabled', false);
	};

	/**
	 * Enable the button which allows customer to apply change to measurements
	 */
	self.disableApply = function () {
		$(self.btn_ppbs_customization_apply).prop('disabled', true);
	};

	/**
	 * create and return an array of fields with information about each
 	 * @returns {any[]}
	 */
	self.getFields = function() {
		var ppbs_fields = Array();
		$("input.ppbs-customization-field").each(function () {
			var ppbs_field = {};
			ppbs_field.id_ppbs_dimension = $(this).attr('data-id_ppbs_dimension');
			ppbs_field.display_name = $(this).attr('data-display_name');
			ppbs_field.symbol = $(this).attr('data-symbol');
			ppbs_field.value = $(this).val();
			ppbs_fields.push(ppbs_field);
		});
		return ppbs_fields;
	};


	/**
	 * Calculate price at the server end
 	 */
	self.processGetPrice = function() {
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=ppbsadminordercontroller&action=processgetprice&rand=' + Date.now());
		var ppbs_fields = self.getFields();

		var post_data = {
			'fields': ppbs_fields,
			'id_product' : $(self.btn_ppbs_customization_edit).attr('data-id_product'),
			'id_product_attribute': $(self.btn_ppbs_customization_edit).attr('data-id_product_attribute'),
			'id_customization': $(self.btn_ppbs_customization_edit).attr('data-id_customization'),
			'id_address_delivery': $(self.btn_ppbs_customization_edit).attr('data-id_address_delivery'),
			'id_order': $(self.btn_ppbs_customization_edit).attr('data-id_order'),
			'quantity': $(self.btn_ppbs_customization_edit).attr('data-quantity')
		};

		self.disableApply();
		$.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			data: post_data,
			dataType: 'json',
			success: function (result) {
				$(self.input_ppbs_customization_price).val(result.price_ex_tax);
				$(self.lbl_ppbs_customization_price).html(result.price_inc_tax);
				MPTools.waitEnd();
				self.enableApply();
			}
		});
	};

	/**
	 * Apply typewatch plugin for input fields
 	 */
	self.applyTypeWatch = function() {
		$('input.ppbs-customization-field').typeWatch({
			callback: function () {
				self.processGetPrice();
			},
			wait: 500,
			highlight: false,
			captureLength: 0
		});
	};

	self.onCustomizationEditButtonClick = function($sender) {
		MPTools.waitStart();
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=ppbsadminordercontroller&action=rendereditcustomization&rand=' + Date.now());
		var post_data = {
			'id_product': $sender.attr('data-id_product'),
			'id_product_attribute': $sender.attr('data-id_product_attribute'),
			'id_customization': $sender.attr('data-id_customization'),
			'id_address_delivery': $sender.attr('data-id_address_delivery'),
			'id_order': $sender.attr('data-id_order')
		};

		$.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			data: post_data,
			success: function (html_result) {
				self.removeExistingCustomizers();
				$(html_result).insertAfter($sender);
				self.applyTypeWatch();
				MPTools.waitEnd();
				self.processGetPrice();
			}
		});
	};

	/**
	 * Save the modification to the measurements to the order
 	 * @param $sender
	 */
	self.onCustomizationApplyButtonClick = function ($sender) {
		MPTools.waitStart();
		var ppbs_fields = self.getFields();
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=ppbsadminordercontroller&action=processeditcustomization&rand=' + Date.now());
		var post_data = {
			'fields' : ppbs_fields,
			'id_product': $sender.attr('data-id_product'),
			'id_product_attribute': $sender.attr('data-id_product_attribute'),
			'id_customization': $sender.attr('data-id_customization'),
			'id_address_delivery': $sender.attr('data-id_address_delivery'),
			'id_order': $sender.attr('data-id_order'),
			'unit_product_price_excl' : $(self.input_ppbs_customization_price).val()
		};

		$.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			data: post_data,
			success: function (result) {
				MPTools.waitEnd();
				location.reload();
			}
		});
	};

	/**
	 * Init
	 */
	self.init = function() {
	};
	self.init();


	/**
	 * on Edit button click
 	 */
	$("body").on("click", " .btn-ppbs-customization-edit", function () {
		self.onCustomizationEditButtonClick($(this));
		return false;
	});

	/**
	 * On customization apply button click
 	 */
	$("body").on("click", " .btn-ppbs-customization-apply", function () {
		self.onCustomizationApplyButtonClick($(this));
		return false;
	});

};
