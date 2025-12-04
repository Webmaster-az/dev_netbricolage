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

PPBSAdminConfigMassAssignController = function(wrapper) {
	var self = this;
	self.wrapper = wrapper;
	self.$wrapper = $(wrapper);

	self.div_categorytree = self.wrapper + ' ul#category';
	self.div_categoryproducts = self.wrapper + ' #category-products';
	self.input_product_checked_all = self.div_categoryproducts + ' input.id_category_product_all';
	self.input_id_product = self.wrapper + ' input[name="id_product"]';
	self.button_mass_assign_apply = self.wrapper + ' button#ppbs-mass-assign-apply';

	self.route = 'ppbsadminconfigmassassigncontroller';

	/**
	 * Render the equation templates list
	 */
	self.render = function() {
		MPTools.waitStart();
		$.ajax({
			type: 'POST',
			url: module_config_url + '&route='+self.route+'&action=render',
			async: true,
			cache: false,
			data: {
			},
			success: function (html_content) {
				self.$wrapper.html(html_content);

				$(self.div_categorytree).parents(".form-group").addClass('disabled');
				$(self.div_categoryproducts).parents(".form-group").addClass('disabled');
				$(self.button_mass_assign_apply).prop('disabled', true);

				search_widget.events.onResultSelect = function () {
					self.onSearchResultSelect();
				};

				MPTools.waitEnd();
			}
		});
	};

	/**
	 * Render products in a category
 	 * @param id_category
	 */
	self.renderProducts = function(id_category) {
		MPTools.waitStart();
		$.ajax({
			type: 'POST',
			url: module_config_url + '&route=' + self.route + '&action=renderproducts',
			async: true,
			cache: false,
			data: {
				id_category : id_category,
				id_product : $(self.input_id_product).val()
			},
			success: function (html_content) {
				self.setCategoryProducts(html_content);
				MPTools.waitEnd();
			}
		});
	};

	/**
	 * process mass assign
 	 */
	self.processMassAssign = function() {
		let $form = self.$wrapper.find("form");
		MPTools.waitStart();
		$.ajax({
			type: 'POST',
			url: module_config_url + '&route=' + self.route + '&action=massassign',
			async: true,
			cache: false,
			data: $form.serialize(),
			success: function (result) {
				$.growl.notice({title: "", message: 'Mass Assign Completed'});
				MPTools.waitEnd();
			},
			error: function (request, status, error) {
				$.growl.error({title: "", message: request.responseText});
				MPTools.waitEnd();
			}
		});
	};

	/**
	 * set html for the category products div
 	 * @param html_content
	 */
	self.setCategoryProducts = function(html_content) {
		$(self.div_categoryproducts).html(html_content);
	};

	/**
	 * get selected categories array
 	 * @returns {jQuery}
	 */
	self.getSelectedCategories = function() {
		return $(self.div_categorytree + ' input[type=checkbox]:checked').map(function (_, el) {
			return $(el).val();
		}).get();
	};

	/**
	 * on product search result select
 	 */
	self.onSearchResultSelect = function() {
		$(self.div_categorytree).parents(".form-group").removeClass('disabled');
		$(self.div_categoryproducts).parents(".form-group").removeClass('disabled');
		$(self.button_mass_assign_apply).prop('disabled', false);
	};

	/**
	 * On category tree item select
	 * @param $sender
	 */
	self.onCategorySelect = function($sender) {
		let selected_categories = self.getSelectedCategories();
		self.setCategoryProducts('');
		if (selected_categories.length == 1) {
			self.renderProducts(selected_categories[0]);
		}
	};

	/**
	 * set checkbox for all products to checked state supplied as parameter
	 * @param state
	 */
	self.checkAllProducts = function(state) {
		$(self.div_categoryproducts).find("input[type='checkbox']").prop('checked', state);
	};

	/**
	 * on product checkbox checked / unchecked
 	 */
	self.onProductCheckChange = function() {
		let check_count = $(self.div_categoryproducts).find("input.id_category_product").length;
		let checked_count = $(self.div_categoryproducts).find("input.id_category_product:checked").length;

		$(self.input_product_checked_all).prop('indeterminate', false);
		if (checked_count == 0) {
			$(self.input_product_checked_all).prop('checked', false);
		} else if (checked_count == check_count) {
			$(self.input_product_checked_all).prop('checked', true);
		} else if (checked_count != check_count) {
			$(self.input_product_checked_all).prop('indeterminate', true);
		}
	};

	/**
	 * Init
 	 */
	self.init = function() {
		self.render();
	};
	self.init();

	/**
	 * Events
 	 */

	/**
	 * On category item select
	 */
	$("body").on("change", self.div_categorytree + " input[type='checkbox']", function () {
		self.onCategorySelect($(this));
		return false;
	});

	/**
	 * select all products checkbox change
	 */
	$("body").on("change", self.input_product_checked_all, function () {
		if ($(this).is(':checked')) {
			self.checkAllProducts(true);
		} else {
			self.checkAllProducts(false);
		}
		return false;
	});

	/**
	 * select all products checkbox change
	 */
	$("body").on("change", self.div_categoryproducts + " input.id_category_product", function () {
		self.onProductCheckChange();
		return false;
	});


	/**
	 * on form submit
 	 */
	$("body").on("click", self.button_mass_assign_apply, function () {
		self.processMassAssign();
		return false;
	});

};
