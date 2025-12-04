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

PPBSAdminProductTabAreaPricesController = function(canvas) {

	var self = this;
	self.canvas = canvas;
	self.$canvas = $(canvas);

	self.popupAddFormId = 'ppbs-popup-addarearange';
	self.list = "ppbs-areaprice-list";
	self.popup; //instance of modal popup

	/**
	 * Render
	 */
	self.render = function() {
		MPTools.waitStart();
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=adminproducttab&route=ppbsadminproducttabareapricescontroller&action=render');

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
			}
		});
	};

	/**
	 * Open the add area based price form
	 * @param id_area_price
	 * @returns {boolean}
	 */
	self.openAddForm = function (id_area_price) {
        MPTools.waitStart();
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=adminproducttab&route=ppbsadminproducttabareapricescontroller&action=renderaddform&id_product=' + id_product);
        if (typeof (id_area_price) !== 'undefined') {
            url = url + '&id_area_price=' + id_area_price;
        }
        self.popup = new PPBSPopup(self.popupAddFormId, self.canvas);
        self.popup.showContent(url, null, MPTools.waitEnd);
        return false;
    };

	/**
	 * Save Area Price
	 */
	self.processAddForm = function () {
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=adminproducttab&route=ppbsadminproducttabareapricescontroller&action=processaddform&id_product=' + id_product);

		MPTools.waitStart();
		$.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			//dataType: "json",
			data: $("#" + self.popupAddFormId + " :input").serialize(),
			success: function (jsonData) {
				MPTools.waitEnd();
				self.popup.close();
				self.render();
				$.growl.notice({message: 'Area Price saved'});
			}
		});
	};

	/**
	 * Delete the area based price
	 */
	self.deleteAreaPrice = function(id_area_price) {
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=adminproducttab&route=ppbsadminproducttabareapricescontroller&action=processdelete&id_product=' + id_product);
		if (confirm('Are you sure you want to delete this area based pri0ce range')) {
			$.ajax({
				type: 'POST',
				url: url,
				async: true,
				cache: false,
				//dataType: "json",
				data: {'id_area_price': id_area_price},
				success: function (jsonData) {
					self.render();
				}
			});
		}
	};

	/**
	 * Init
	 */
	self.init = function() {
		self.render();
	};
	self.init();

	/*** Events ***/

	/**
	 * Add new area based price button click
	 */
	$("body").on("click", "#ppbs-areaprice-add", function () {
		self.openAddForm();
		return false;
	});

	/**
	 * Save Area price button click
	 */
	$("body").on("click", "#ppbs-areaprice-save", function () {
		self.processAddForm();
		return false;
	});

	/**
	 * Edit area price icon click
	 */
	$("body").on("click", "#" + self.list + " a.ppbs-areaprice-edit", function () {
		self.openAddForm($(this).parents("tr").attr("data-id_area_price"));
		return false;
	});

	/**
	 * Delete area price icon click
	 */
	$("body").on("click", "#" + self.list + " a.ppbs-areaprice-delete", function () {
		self.deleteAreaPrice($(this).parents("tr").attr("data-id_area_price"));
		return false;
	});



};

