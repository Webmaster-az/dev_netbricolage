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

PPBSAdminProductTabFieldsController = function(canvas) {

	var self = this;
	self.canvas = canvas;
	self.$canvas = $(canvas);

	self.popupAddFormId = 'ppbs-popup-addfield';
	self.dropDownPanelID = 'panel2';
	self.popup; //instance of modal popup

	self.$select_input_type = 'select#input_type';

	/*
	* Serialise all the field dropdown values as a json array
	*/
	self._serialiseFieldOptions = function() {
		var options = new Array();
		$("#" + self.dropDownPanelID + ' #ppbs-field-options-table tbody tr').each(function () {
			if (!$(this).hasClass('cloneable')) {
				var option = {
					'value': $(this).find("td.value").html(),
					'text': $(this).find("td.text").html().replace(/\"/g, '&quot;')
				};
				options.push(option);
			}
		});
		return JSON.stringify(options);
	};

	self.render = function() {
		MPTools.waitStart();
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=adminproducttab&route=ppbsadminproducttabfieldscontroller&action=renderlist');

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
				MPTools.waitEnd();

				$("#ppbs-field-list-table tbody").sortable({
					update: function (event, ui) {
						self.savePositions();
					}
				}).disableSelection();
			}
		});
	};

	/**
	 * On Input Type Drop down change
 	 */
	self.onFieldTypeChange = function($sender) {
		self.$canvas.find("div[data-type='dropdown']").fadeTo(0, 1);
		self.$canvas.find("div[data-type='textbox']").fadeTo(0, 1);
		if ($(self.$select_input_type).val() == 'textbox') {
			self.$canvas.find("div[data-type='dropdown']").fadeTo(0, 0.5);
			self.$canvas.find("div[data-type='dropdown'] *").prop('disabled', true);
			self.$canvas.find("div[data-type='textbox'] *").prop('disabled', false);
		} else {
			self.$canvas.find("div[data-type='textbox']").fadeTo(0, 0.5);
			self.$canvas.find("div[data-type='dropdown'] *").prop('disabled', false);
			self.$canvas.find("div[data-type='textbox'] *").prop('disabled', true);
		}
	};


	/**
	 * Initialise the panel in the popup with form managing drop down values (for each fiel)
	 */
	self.initDropdownPanel = function() {
		MPTools.waitEnd();
		$("#ppbs-field-options-table tbody").sortable().disableSelection();
		self.onFieldTypeChange($(self.$select_input_type));
	};

	/**
	 * Display the Add Field popup form(s)
 	 * @param id_ppbs_product_field
	 * @returns {boolean}
	 */
	self.openAddForm = function(id_ppbs_product_field) {
		MPTools.waitStart();
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=adminproducttab&route=ppbsadminproducttabfieldscontroller&action=renderaddform&id_product=' + id_product);
		if (typeof (id_ppbs_product_field) !== 'undefined') {
            url = url + '&id_ppbs_product_field=' + id_ppbs_product_field;
        }
		self.popup = new PPBSPopup(self.popupAddFormId, self.canvas);
		self.popup.showContent(url, null, self.initDropdownPanel);
		return false;
	};

	/**
	 * Save the new positions of the fields in the fields list
	 */
	self.savePositions = function() {
		var positions = new Array();
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=adminproducttab&route=ppbsadminproducttabfieldscontroller&action=processpositions&id_product=' + id_product);

		$("#ppbs-field-list-table tr").each(function () {
			if (typeof $(this).attr("data-id_ppbs_product_field") !== 'undefined')
				positions.push($(this).attr("data-id_ppbs_product_field"));
		});

		$.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			dataType: "json",
			data: {'ids_ppbs_product_field' : positions},
			success: function (jsonData) {
				console.log(jsonData);
			}
		});
	};

	/**
	 * Save the new field
	 */
	self.processAddForm = function() {
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=adminproducttab&route=ppbsadminproducttabfieldscontroller&action=processaddform&id_product=' + id_product);
		MPTools.waitStart();
		$.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			//dataType: "json",
			data: $("#" + self.popupAddFormId + " :input").serialize(),
			success: function (jsonData) {
				self.popup.close();
				self.render();
				MPTools.waitEnd();
				$.growl.notice({message: 'Field added'});
			}
		});
	};

	/**
	 * Delete a field
	 * @param id_ppbs_product_field
	 */
	self.deleteField = function(id_ppbs_product_field) {
		if (confirm('Are you sure you want to delete this field?')) {
            var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=adminproducttab&route=ppbsadminproducttabfieldscontroller&action=processdelete');
			MPTools.waitStart();
			$.ajax({
				type: 'POST',
				url: url,
				async: true,
				cache: false,
				//dataType: "json",
				data: {'id_ppbs_product_field': id_ppbs_product_field},
				success: function (jsonData) {
					MPTools.waitEnd();
					self.render();
				}
			});
		}
	};

	/**
	 * Add new user entered dropdown value to list
	 */
	self.dropDownPanel_addNewFieldOption = function() {
		var $dropDownPanel = $("#" + self.dropDownPanelID);
		var value = $("#" + self.dropDownPanelID).find("input#value").val();
		var text = $("#" + self.dropDownPanelID).find("input#text").val();
		var $cloned = $dropDownPanel.find("#ppbs-field-options-table tr.cloneable").clone();
		$cloned.removeClass("cloneable");
		$cloned.removeClass("hidden");
		$cloned.find("td.value").html(value);
		$cloned.find("td.text").html(text);
		$cloned.appendTo($dropDownPanel.find("#ppbs-field-options-table tbody"));
	};

	self.init = function() {
		self.render();
	};
	self.init();

	/* Events */

	/**
	 * Edit field in list (Fields List)
	 */
	$("body").on("click", "#ppbs-productfield-add", function() {
		self.openAddForm();
		return false;
	});

	/**
	 * Delete field in list (Fields List)
	 */
	$("body").on("click", "a.ppbs-field-delete", function () {
		self.deleteField($(this).parents("tr").attr("data-id_ppbs_product_field"));
		return false;
	});


	/**
	 * Edit a field (in fields list)
	 */
	$("body").on("click", " a.ppbs-field-edit", function () {
		self.openAddForm($(this).parents("tr").attr("data-id_ppbs_product_field"));
		return false;
	});


	/**
	 * Popup - save field click
 	 */
	$("body").on("click", "#ppbs-field-save", function() {
		self.processAddForm();
		return false;
	});

	/**
	 * Edit dropdown values button click
	 */
	$("body").on("click", "#" + self.popupAddFormId + " #edit-field-options", function () {
		self.popup.showSubPanel('panel2');
		return false;
	});

	/**** Events for Panel 2 (Dropdown values) ****/

	$("body").on("click", "#" + self.dropDownPanelID + " #ppbs-field-option-add", function () {
		self.dropDownPanel_addNewFieldOption();
		return false;
	});

	/**
	 * Save field drop down values button click
	 */
	$("body").on("click", "#" + self.dropDownPanelID + " #ppbs-field-dropdown-done", function () {
		$("#" + self.popupAddFormId + " input#ppbs_product_field_options").val(self._serialiseFieldOptions());
		self.popup.hideSubPanel('panel2');
		return false;
	});

	/*
	 * remove field option from the display list
	 */
	$("body").on("click", "#" + self.dropDownPanelID + " a.ppbs-field-option-delete", function () {
		$(this).parents("tr").remove();
	});

	/**
	 * On Input Type Dropdown change
 	 */
	self.$canvas.on("change", self.$select_input_type, function () {
		self.onFieldTypeChange($(this));
	});

};

