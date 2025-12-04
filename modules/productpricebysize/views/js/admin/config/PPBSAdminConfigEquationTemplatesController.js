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

PPBSAdminConfigEquationTemplatesController = function(wrapper) {
	var self = this;
	self.wrapper = wrapper;
	self.$wrapper = $(wrapper);

    self.variables = new function () {
        this.wrapper = self.wrapper + ' #ppbs-equation-vars';
        this.input_name = self.wrapper + ' input[name="name"]';
        this.input_value = self.wrapper + ' input[name="value"]';
        this.div_list = self.wrapper + ' .ppbs-equation-vars-list';
        this.btn_add = this.wrapper + ' .btn-add';
    }

	/**
	 * Render the equation templates list
	 */
	self.render = function() {
		MPTools.waitStart();
		$.ajax({
			type: 'POST',
			url: module_config_url + '&route=ppbsadminconfigequationtemplatescontroller&action=render',
			async: true,
			cache: false,
			data: {
			},
			success: function (html_content) {
				self.$wrapper.html(html_content);
                self.variables.setButtonAddState();
                self.variables.renderVariablesList();
				MPTools.waitEnd();
			}
		});
	};

	/**
	 * toggle display of equation
 	 */
	self.toggleEquation = function($sender) {
		var $tr = $sender.parents("tr");
		$tr.find(".equation").toggle();
	};

	/**
	 * Delete a dimenion
	 */
	self.processDeleteEquationTemplate = function(id_equation_template) {
		if (!confirm('This will remove the equation template across all products.  Are you sure?')) return false;
		MPTools.waitStart();

		$.ajax({
			type: 'POST',
			url: module_config_url + '&route=ppbsadminconfigequationtemplatescontroller&action=processdeleteequationtemplate',
			async: true,
			cache: false,
			//dataType: "json",
			data: {
				'id_equation_template' : id_equation_template
			},
			success: function (jsonData) {
				self.render();
				MPTools.waitEnd();
			}
		});
	};

    self.variables.renderVariablesList = function () {
        $.ajax({
            type: 'POST',
            url: module_config_url + '&route=ppbsadminconfigequationtemplatescontroller&action=rendervariableslist',
            async: true,
            cache: false,
            data: {
            },
            success: function (result) {
                $(self.variables.div_list).html(result);
                MPTools.waitEnd();
            }
        });
    }

    self.variables.actionEditVariable = function (name, value) {
        $(self.variables.input_name).val(name);
        $(self.variables.input_value).val(value);
        self.variables.setButtonAddState();
    }

    self.variables.processAddVariable = function() {
        $.ajax({
            type: 'POST',
            url: module_config_url + '&route=ppbsadminconfigequationtemplatescontroller&action=processaddvariable',
            async: true,
            cache: false,
            //dataType: "json",
            data: {
                'name': $(self.variables.input_name).val(),
                'value': $(self.variables.input_value).val()
            },
            success: function (result) {
                self.variables.renderVariablesList();
                MPTools.waitEnd();
            }
        });
    }

    self.variables.processDeleteVariable = function (name) {
        $.ajax({
            type: 'POST',
            url: module_config_url + '&route=ppbsadminconfigequationtemplatescontroller&action=processdeletevariable',
            async: true,
            cache: false,
            //dataType: "json",
            data: {
                'name': name,
            },
            success: function (result) {
                self.variables.renderVariablesList();
                MPTools.waitEnd();
            }
        });
    };


    self.variables.setButtonAddState = function() {
        if ($(self.variables.input_name).val() == '' || $(self.variables.input_value).val() == '') {
            $(self.variables.btn_add).prop('disabled', true);
        } else {
            $(self.variables.btn_add).prop('disabled', false);
        }
    };

	self.init = function() {
		self.render();
	};
	self.init();

	/* Events */

	/**
	 * edit dimension icon click
	 */
	$("body").on("click", ".ppbs-equation-template-view", function() {
		self.toggleEquation($(this));
		return false;
	});

	/**
	 * edit dimension icon click
	 */
	$("body").on("click", ".ppbs-equation-template-delete", function () {
		self.processDeleteEquationTemplate($(this).attr('data-id_equation_template'));
		return false;
	});

    self.variables.events = new function () {
        $("body").on("click", self.variables.btn_add, function () {
            self.variables.processAddVariable();
            return false;
        });

        $("body").on("keyup", self.variables.input_name + ', ' + self.variables.input_value, function () {
            self.variables.setButtonAddState();
        });

        $("body").on("click", self.variables.wrapper + ' .btn-edit', function () {
            var name = $(this).parents("tr.variable").find("td.name").html();
            var value = $(this).parents("tr.variable").find("td.value").html();
            self.variables.actionEditVariable(name, value);
            return false;
        });

        $("body").on("click", self.variables.wrapper + ' .btn-delete', function () {
            var name = $(this).parents("tr.variable").find("td.name").html();
            self.variables.processDeleteVariable(name);
            return false;
        });
    }
};

