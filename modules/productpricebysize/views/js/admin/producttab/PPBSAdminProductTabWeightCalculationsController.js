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

PPBSAdminProductTabWeightCalculationsController = function(canvas) {

	var self = this;
	self.canvas = canvas;
	self.$canvas = $(canvas);
	self.equation_editor = [];
	self.route = 'ppbsadminproducttabweightcalculationscontroller';

    /**
     * Render the tab content
     */
    self.render = function () {
        MPTools.waitStart();
        let deferred = jQuery.Deferred();
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=adminproducttab&route=' + self.route + '&action=render');
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
                deferred.resolve(true);
            }
        });
        return deferred.promise();
    };

    /**
     * Update the equation enabled status for the product
     */
    self.processEnabledStatus = function () {
        MPTools.waitStart();
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=adminproducttab&route=' + self.route + '&action=processenabled');

        if (self.$canvas.find("input#weight_calculation_enabled").is(":checked")) {
            weight_calculation_enabled = 1;
        } else {
            weight_calculation_enabled = 0;
        }

        var post_data = {
            'id_product': id_product,
            'weight_calculation_enabled': weight_calculation_enabled
        };

        $.ajax({
            type: 'POST',
            url: url,
            async: true,
            cache: false,
            data: post_data,
            success: function (response) {
                console.log(response);
                MPTools.waitEnd();
            }
        });
    };


    /**
	 * Init
	 */
	self.init = function() {
        $.when(self.render()).then(
            function (status) {
                self.equation_editor = new MPEquationEditorComponent('MPEquationEditor2', '#ppbs-weight-calculator');
                self.equation_editor.equation_type = 'weight';
                self.equation_editor.widget_url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=mpequationeditorcontroller');
                self.equation_editor.id_product = id_product;
                self.equation_editor.render();
            }
        );
    };
	self.init();

    /**
     * Events
     */
    $("body").on("click", self.canvas + " .combinations-list a", function () {
        var ipa = $(this).attr("data-ipa");
        if (ipa == '') {
            ipa = 0;
        }
        self.equation_editor.id_product_attribute = ipa;
        self.equation_editor.loadEquation();
        return false;
    });


    /**
     * on equation enabled checkbox changed
     */
    $("body").on("change", self.canvas + " .switch-input", function () {
        self.processEnabledStatus();
    });
};