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

class MPEquationEditorComponent {

	constructor(id, wrapper) {
		this.id = id;
		this._mode = 'normal';  //editor mode: 'normal' or 'advanced'*/
		this._id_product = 0;
		this._id_product_attribute = 0;
		this._id_equation = 0;
		this._id_equation_template = 0;
		this._equation_template = '';
		this.equation_type = 'price';
		this._widget_url = '';
		this.wrapper = wrapper;
		this.$wrapper = $(wrapper);

		this.btn_save = this.wrapper + ' button#ppbs-equation-save';
		this.btn_saveas = this.wrapper + ' button#ppbs-equation-saveas';
		this.btn_remove = this.wrapper + ' button#ppbs-equation-remove';

		this.MPEquationEditorComponent();
		this.Events();
	};

	isNumeric(n) {
		return !isNaN(parseFloat(n)) && isFinite(n);
	};

	renderEquationTokens(tokens) {
		let i = 0;
		if (tokens.length > 0) {
			for (i=0; i < tokens.length; i++) {
				tokens[i] = tokens[i].replace('[', '');
				tokens[i] = tokens[i].replace(']', '');
				var type = 'variable';
				if (tokens[i].length == 1 && this.isNumeric(tokens[i])) type = '';
				if (tokens[i].length == 1 && $.inArray(tokens[i], ["+", "/", "-", "*"]) > -1) type = 'operator';
				if (tokens[i].length == 1 && $.inArray(tokens[i], ["(", ")"]) > -1) type = 'parenthesis';
				if (tokens[i].length == 1 && tokens[i] == '.') type = '';
				this.insertParam(tokens[i], tokens[i], type);
			}
		}
	};

	/**
	 * process equation string and it display in the calculator display screen
 	 */
	renderEquationFromString(str_equation) {
		if (this.mode == 'advanced') {
			this.$wrapper.find("textarea#equation-advanced").val(str_equation);
			return false;
		}

		if (str_equation == '') {
			return false;
		}
		var matches = str_equation.match(/\[([a-zA-Z0-9_ ]+?)\]|[0-9\.\*\-\+\/\(\)]/g);
		this.renderEquationTokens(matches);
	};

	/**
	 * Add new equation entry to the equation template dropdown
 	 * @param id_equation_template
	 * @param name
	 * @param equation
	 */
	addTemplateToDropdown(id_equation_template, name, equation) {
		this.$wrapper.find("select[name='ppbs-equation-load']").append($('<option data-equation="' + equation + '" value="'+ id_equation_template + '">' + name + '</option>'));
	}

	/**
	 * get equation display element reference
 	 * @returns {jQuery|HTMLElement}
	 */
	get $display() {
		return $(this.wrapper + " .equation-display");
	}

	/**
	 * Insert a new parameter into the editor display
 	 * @param text
	 * @param value
	 * @param type
	 */
	insertParam(text, value, type) {
		let html = "<span data-value='" + value + "' class='ppbs-param " + type + "'>" + text + "<i class='material-icons' style='display:none;'>clear</i></span>";
		this.$display.append(html);
	}

	/**
	 * On Insert Param button click
 	 * @param $sender
	 */
	onInsertParamClick($sender) {
		let value = $sender.attr("data-value");
		let text = $sender.html();
		let type = $sender.attr("data-type");
		this.equation = this.equation + value;
	};

	/**
	 * On parameter remove
 	 * @param $sender
	 */
	onRemoveParamClick($sender) {
		$sender.parents(".ppbs-param").remove();
		this.equation = this.createEquationString();
	}

	/**
	 * Create equation string based on display (normal display)
 	 * @returns {string}
	 */
	createEquationString() {
		var str = '';
		if (this.mode == 'advanced') {
			return this.equation;
		} else {
			$(this.$wrapper.find(".equation-display span.ppbs-param")).each(function (index) {
				if ($(this).hasClass('variable')) {
					str += "[" + $(this).attr('data-value') + "]";
				} else {
					str += $(this).attr('data-value');
				}
			});
		}
		return str;
	};

	/**
	 * Switch between normal and advanced editor mode
 	 * @param mode
     */
	switchMode(mode) {
		let $editor_config = this.$wrapper.find(".equation-config");
		$editor_config.find("span").removeClass("active");
		if (this.mode == 'normal') {
			$editor_config.find("span.normal").addClass("active")
		} else {
			$editor_config.find("span.advanced").addClass("active")
		}

		if (this.mode == 'normal') {
			this.$wrapper.find(".equation-display").show();
			this.$wrapper.find(".equation-display-advanced").hide();
			this.$wrapper.find(".equation-buttons").removeClass("disabled");
		}
		if (this.mode == 'advanced') {
			this.$wrapper.find(".equation-display").hide();
			this.$wrapper.find(".equation-display-advanced").show();
			this.$wrapper.find(".equation-buttons").addClass("disabled");
		}
	};

	/**
	 * Set the state of the editor
 	 * @param state
	 */
	setEditorState(state, id_equation_template) {
		//direct = no template
		if (state == 'direct') {
			this.$wrapper.find(".ppbs-new-equation").show();
			this.$wrapper.find(".ppbs-load-equation").hide();
			this._id_equation_template = 0;
			this._id_equation = 0;
			this.$wrapper.find("input[name='equation_name']").focus();
			$(this.btn_save).prop('disabled', '');
			$(this.btn_saveas).prop('disabled', '');
		}

		if (state == 'template') {
			console.log('thus one');
			this.$wrapper.find(".ppbs-new-equation").hide();
			this.$wrapper.find(".ppbs-load-equation").show();
			this.id_equation_template = id_equation_template;
			this.id_equation = 0;
			$(this.btn_save).prop('disabled', '');
			$(this.btn_saveas).prop('disabled', '');
		}
	}

	/**
	 * Render the widget
	 */
	render() {
		let self = this;
		let url = MPTools.joinUrl(this._widget_url, 'action=render');
		$.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			data: {
				'id_product' : self._id_product,
				'id_product_attribute': self._id_product_attribute,
                'equation_type' : self.equation_type
			},
			success: function (html_result) {
				self.$wrapper.html(html_result);
				self.id_product = self._id_product;
				self.id_product_attribute = self._id_product_attribute;
				self.loadEquationTemplates();
			}
		});
	};

	/**
	 * Get a list of equation templates
 	 */
	loadEquationTemplates() {
		this.$wrapper.find("select[name='ppbs-equation-load']").find('option').not(':first').remove();
		let self = this;
        let url = MPTools.joinUrl(this._widget_url, 'action=getequationtemplateslist&equation_type=' + self.equation_type);

		$.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			dataType : 'json',
			data: {},
			success: function (result) {
				if (result.length == 0) {
					return false;
				}
				let i = 0;
				for (i=0; i<=result.length-1; i++) {
					self.addTemplateToDropdown(result[i].id_equation_template, result[i].name, result[i].equation);
				}

				if (self.id_equation_template > 0) {
					self.$wrapper.find("select[name='ppbs-equation-load']").val(self.id_equation_template);
				}
			}
		});
	}

	/**
	 * load the equation template into the editor
 	 * @param id_equation_template
	 */
	loadEquationTemplate(id_equation_template) {
		let equation = this.$wrapper.find("select[name='ppbs-equation-load'] option[value='"+id_equation_template+"']").attr("data-equation");
		this.equation = equation;
	}

	/**
	 * Load the equation for a specific product and product IPA
 	 */
	loadEquation() {
		let self = this;
        let url = MPTools.joinUrl(this._widget_url, 'action=getequation');

		$.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			dataType: 'json',
			data: {
				'id_product' : this.id_product,
				'id_product_attribute' : this.id_product_attribute,
                'equation_type' : self.equation_type
			},
			success: function (result) {
				if (result.id_equation_template > 0) {
					self.id_equation_template = result.id_equation_template;
					self.equation = result.equation_template;
					self.setEditorState('template', self.id_equation_template);
					$(self.btn_remove).prop('disabled', '');
				} else if (result.id_equation > 0) {
					self.id_equation = result.id_equation;
					self.equation = result.equation;
					self.setEditorState('direct', 0);
					$(self.btn_remove).prop('disabled', '');
				} else {
					self.id_equation_template = 0;
					self.id_equation = 0;
					self.equation = '';
					$(self.btn_save).prop('disabled', 'disabled');
					$(self.btn_saveas).prop('disabled', '');
					$(self.btn_remove).prop('disabled', 'disabled');
				}
			}
		});
	}


	/**
	 * Display an error message
	 * @param text
	 */
	displayError(text) {
		this.$wrapper.find(".equation-error").fadeIn(200);
		this.$wrapper.find(".equation-error span").text(text);
	}

	/**
	 * Hide the error message
 	 */
	hideError() {
		this.$wrapper.find(".equation-error").fadeOut(200);
	}

	/**
	 * Save the equation or equation template
 	 */
	processSave() {
		let self = this;
        let url = MPTools.joinUrl(this._widget_url, 'action=processsave');

		$.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			dataType: 'json',
			data: {
				id_product : this.id_product,
				id_product_attribute: this.id_product_attribute,
				id_equation_template : this.id_equation_template,
				name : this.equation_name,
				equation : this.createEquationString(),
                equation_type : this.equation_type
			},
			success: function (result) {
				if (result.error == 1) {
					switch (result.error_element) {
						case 'name' :
							self.displayError(result.error_text);
							break;
					}
				} else {
					$(self.btn_remove).prop('disabled', '');
				}
				self.loadEquationTemplates();
			}
		});
	};

	/**
	 * Remove the equation associated with a product / product combination
 	 */
	processRemoveEquation() {
	    console.log(this.equation_type);
		let self = this;
        let url = MPTools.joinUrl(this._widget_url, 'action=processremove&equation_type=' + this.equation_type);

		$.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			//dataType: 'json',
			data: {
				id_product: this.id_product,
				id_product_attribute: this.id_product_attribute,
			},
			success: function (result) {
				self.equation = '';
				self.equation_name = '';
				self.id_equation_template = 0;
				self.id_equation = 0;
				$(self.btn_remove).prop('disabled', 'disabled');
			}
		});
	}

	/**
	 * on equation textarea keyup
	 */
	onEquationTextAreaKeyUp($sender) {
		this.equation = $sender.val();
	}

	/**
	 * On equation load dropdown changed
 	 * @param $sender
	 */
	onEquationLoadChange($sender) {
		if ($sender.val() != '' && $sender.val() != 0) {
			this.id_equation_template = $sender.val();
			this.loadEquationTemplate($sender.val());
			$(this.btn_save).prop('disabled', '');
		}
	}

	/**
	 * On Cancel equation 'Save As' form
 	 */
	onCancelEquationSaveAs() {
		$(this.btn_save).prop('disabled', 'disabled');
		$(this.btn_saveas).prop('disabled', '');
		this.$wrapper.find(".ppbs-new-equation").hide();
        this.$wrapper.find(".ppbs-load-equation").show();
	}

	/**
	 * On Save As Click
	 * @param $sender
	 */
	onSaveAsClick($sender) {
		this.setEditorState('direct');
		$(this.btn_saveas).prop('disabled', 'disabled');
	};

	/**
	 * On Save Click
	 * @param $sender
	 */
	onSaveClick($sender) {
		this.processSave();
	};

	onRemoveClick($sender) {
		this.processRemoveEquation();
	}

	/**
	 * get ID product
	 * @param id_product
	 */
	get id_product() {
		return this._id_product;
	};

	/**
	 * set ID product
 	 * @param id_product
	 */
	set id_product(id_product) {
		this._id_product = id_product;
		this.$wrapper.find("input#id_product").val(id_product);
	};

	/**
	 * set ID product attribute
	 * @param id_product_attribute
	 */
	get id_product_attribute() {
		return this._id_product_attribute;
	};

	/**
	 * set ID product attribute
	 * @param id_product_attribute
	 */
	set id_product_attribute(id_product_attribute) {
		this._id_product_attribute = id_product_attribute;
		this.$wrapper.find("input#id_product_attribute").val(id_product_attribute);
	};

	/**
	 * Set widget url
	 * @param widget_url
	 */
	get widget_url() {
		return this._widget_url;
	};

	/**
	 * Set widget url
 	 * @param widget_url
	 */
    set widget_url(widget_url) {
        this._widget_url = widget_url;
    };

    /**
	 * get the internal text equation property
	 * @param equation
	 */
	get equation() {
		return this._equation;
	};

	/**
	 * set the internal text equation property and hidden field
 	 * @param equation
	 */
	set equation(equation) {
		this._equation = equation;
		this.$wrapper.find("input#equation").val(equation);
		this.$wrapper.find(".equation-display").html('');
		this.$wrapper.find("textarea#equation-advanced").val(equation);
		this.renderEquationFromString(equation);
	};

	/**
	 * get id_equation
 	 * @returns {*}
	 */
	get id_equation() {
		return this._id_equation;
	}

	/**
	 * set id_equation
	 * @param id_equation
	 */
	set id_equation(id_equation) {
		this._id_equation = id_equation;
	}

	/**
	 * Get loaded equation ID
	 * @returns {*}
	 */
	get id_equation_template() {
		return this._id_equation_template;
	};

	/**
	 * Set loaded equation ID
	 * @param value
	 */
	set id_equation_template(id_equation_template) {
		this._id_equation_template = id_equation_template;
		this.$wrapper.find("select[name='ppbs-equation-load']").val(id_equation_template);
	};

	/**
	 * Set the editor mode
 	 * @param mode
	 */
	set mode(mode) {
		this._mode = mode;
		this.switchMode(mode);
	};

	/**
	 * get equation editor mode
	 */
	get mode() {
		return this._mode;
	};

	/**
	 * get equation name
 	 * @returns {*}
	 */
	get equation_name() {
		return this.$wrapper.find("input[name='equation_name']").val();
	}

	/**
	 * get equation name
	 * @returns {*}
	 */
	set equation_name(equation_name) {
		this.$wrapper.find("input[name='equation_name']").val(equation_name);
	}


	/**
	 * @constructor
	 */
	MPEquationEditorComponent() {
	};

	/**
	 * Events
	 */

	Events() {
		let self = this;

		/**
		 * on equation textarea keyup
 		 */
		$("body").on("keyup", self.wrapper + " textarea#equation-advanced", function () {
			self.onEquationTextAreaKeyUp($(this));
			return false;
		});

		/**
		 * Edit mode change button click
		 */
		$("body").on("click", self.wrapper + " .equation-config span", function () {
			self.mode = $(this).attr("data-editormode");
			return false;
		});

		/**
		 * On parameter buttojn click
		 */
		$("body").on("click", self.wrapper + " span.button", function () {
			self.onInsertParamClick($(this));
			return false;
		});

		/* remove param */
		$("body").on("click", self.wrapper + " .ppbs-param i", function () {
			self.onRemoveParamClick($(this));
		});

		/**
		 * On new equation name text field change
		 */
		$("body").on("keyup", self.wrapper + " .ppbs-new-equation input[type='text']", function () {
			self.hideError();
			return false;
		});

		/**
		 * On new equation name text field change
		 */
		$("body").on("change", self.wrapper + " select[name='ppbs-equation-load']", function () {
			self.onEquationLoadChange($(this));
			return false;
		});

		/**
		 * Cancel Save As equation entry
		 */
		$("body").on("click", self.wrapper + " i.cancel", function () {
			self.onCancelEquationSaveAs($(this));
			return false;
		});

		/**
		 * On Save As Button Click
		 */
		$("body").on("click", this.btn_save, function () {
			self.onSaveClick($(this));
			return false;
		});

		/**
		 * On Save As Button Click
		 */
		$("body").on("click", this.btn_saveas, function () {
			self.onSaveAsClick($(this));
			return false;
		});

		/**
		 * On Save As Button Click
		 */
		$("body").on("click", this.btn_remove, function () {
			self.onRemoveClick($(this));
			return false;
		});
	}
};
