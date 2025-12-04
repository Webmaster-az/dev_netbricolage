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

PPBSAdminConfigUnitsController = function(wrapper) {
	var self = this;
	self.wrapper = wrapper;
	self.$wrapper = $(wrapper);

	/* function render main form into the tab canvas */
	self.render = function(id_ppbs_unit) {
		if (id_ppbs_unit == null)
			id_ppbs_unit = 0;

		MPTools.waitStart();
		$.ajax({
			type: 'POST',
			url: module_config_url + '&route=ppbsadminconfigunitscontroller&action=render',
			async: true,
			cache: false,
			data: {
				'id_ppbs_unit' : id_ppbs_unit
			},
			success: function (html_content) {
				self.$wrapper.html(html_content);
				MPTools.waitEnd();
			}
		});
	};

	self.processForm = function() {
		MPTools.waitStart();
		$.ajax({
			type: 'POST',
			url: module_config_url + '&route=ppbsadminconfigunitscontroller&action=processform',
			async: true,
			cache: false,
			//dataType: "json",
			data: self.$wrapper.find(" :input, select").serialize(),
			success: function (jsonData) {
				self.render();
				MPTools.waitEnd();
				$.growl.notice({message: 'Unit saved'});
			}
		});
		return false;
	};

	/**
	 * Delete a dimenion
	 */
	self.processDelete = function(id) {

		if (!confirm('Are you sure you want to completely delete this dimension?')) return false;
		MPTools.waitStart();

		$.ajax({
			type: 'POST',
			url: module_config_url + '&route=ppbsadminconfigunitscontroller&action=processdelete',
			async: true,
			cache: false,
			//dataType: "json",
			data: {
				'id_ppbs_unit' : id
			},
			success: function (jsonData) {
				self.render();
				MPTools.waitEnd();
			}
		});
	};

	self.init = function() {
		self.render();
	};
	self.init();

	/* Events */

	$("body").on("click", "#ppbs-unit-save", function() {
		self.processForm();
		return false;
	});

	/**
	 * delete unit icon click
	 */
	$("body").on("click", ".ppbs-unit-delete", function() {
		var id = $(this).parents("tr").attr("data-id");
		self.processDelete(id);
		return false;
	});


	/**
	 * edit unit icon click
	 */
	$("body").on("click", ".ppbs-unit-edit", function() {
		var id = $(this).parents("tr").attr("data-id");
		self.render(id);
		return false;
	});


};

