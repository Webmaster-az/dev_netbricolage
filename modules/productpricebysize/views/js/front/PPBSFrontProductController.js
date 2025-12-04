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

PPBSFrontProductController = function (wrapper, after_element, quickview) {
	var self = this;
	self.wrapper = wrapper;
	self.$wrapper = $(wrapper);
	self.quickview = quickview;
	self.module_folder = 'productpricebysize';
	self.product_info = [];
	self.pco_event = {
        price: 0,
        price_impact : 0,
    };
    self.status = 0;
	self.debug = false;

	self.div_stock_warning = self.wrapper + ' #ppbs-widget-stock-warning';
	self.div_conversion_units = self.wrapper + ' .conversion-units';
	//self.field_ratios = ppbs_field_ratios_json;

    self.creativeElements = new function () {
        var self = this;
        //self.enabled = $("body").hasClass("elementor-page")
        self.enabled = false
    }

    /**
	 * Get Product ID
	 * @returns {jQuery|number}
	 */
	self.getProductID = function () {
		var id_product = 0;
		if (self.quickview) {
			id_product = $("form#add-to-cart-or-refresh input[name='id_product']").val();
		} else {
			id_product = $("form#add-to-cart-or-refresh input[name='id_product']").val();
		}
		return id_product;
	};

    self.getAddToCartForm = function () {
        if (self.creativeElements.enabled) {
            return $("form[id^='add-to-cart-or-refresh']");
        } else {
            return $("#add-to-cart-or-refresh");
        }
    }

    /**
     * get the active shop ID
      * @returns {*}
     */
	self.getShopID = function() {
	    return id_shop;

    };

	/**
	 * Update Product Information such as product price, attribute price tax etc.  This information will be used to calculate dynamic price
	 */
	self.getProductInfo = function () {
	    $form = self.getAddToCartForm();
	    if (self.creativeElements.enabled) {
            var query = $form.serialize();
        } else {
            var query = $form.serialize();
        }

        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=front_ajax&route=ppbsfrontproductcontroller&action=getproductinfo&' + query);

		return $.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			data: {
                'id_product': self.getProductID(),
                'id_shop': self.getShopID(),
                'quantity': self.getQuantity()
            },
			dataType: 'json',
			success: function (resp) {
				self.product_info = resp;
				self.product_info.ppbs.setup_fee = parseFloat(self.product_info.ppbs.setup_fee);

				$(self.wrapper + " select.dd_options").trigger('change');  // copy seect values to hidden inputs
				//self.product_info.attribute_price = self.product_info.attribute_price / (1+(self.product_info.rate / 100));   // take tax off attribute price
				if (self.debug) {
					console.log(self.product_info);
					console.log('2. get product info');
				}
			}
		});
	};

	/**
	 * Are custom conversion units available for this product ?
	 * @returns {boolean}
	 */
	self.hasCustomConversions = function () {
		if ($(self.div_conversion_units).length > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get the product quantity
	 * @returns {number}
	 */
	self.getQuantity = function () {
		return parseFloat($("#quantity_wanted").val());
	};

	/**
	 * Add tax to a price
	 * @param price_ex_tax
	 */
	self.addTax = function (price_ex_tax) {
		if (self.product_info.price_display == 0 || self.product_info.price_display == 2) {
			return price_ex_tax + (price_ex_tax * (self.product_info.rate / 100));
		} else {
			return price_ex_tax;
		}
	};

	/**
	 * Add tax to a price
	 * @param price_ex_tax
	 */
	self.removeTax = function (price_wt) {
		return price_wt / (1 + (self.product_info.rate / 100));
	};

	/**
	 * Get price based on total area
	 * @param totalArea
	 * @returns {*}
	 */
	self.getPrice = function (totalArea) {
		var return_price = Array();
		return_price.value = 0.00;
		return_price.impact = '';
        attribute_price = self.applyExtraDiscounts(self.product_info.attribute_price, true);

		return_price.value = parseFloat(self.product_info.base_price_exc_tax);  // Product Price only Excl Tax, attribute impact, discounts
		price = return_price.value;
		price_with_attr = price + attribute_price;

		return_price = self.applyAreaPriceImpact(price, totalArea);
        return_price_with_attr = self.applyAreaPriceImpact(price_with_attr, totalArea);
        return_price.value_with_attr = return_price_with_attr.value;

		if (return_price.impact == '') {
			return_price.value = price;
		}

		return return_price;
	};

	/**
	 * Apply an area based price imnpact on the price supplied
	 * @param price
	 */
	self.applyAreaPriceImpact = function (price, totalArea) {
		var return_price = Array();
		return_price.value = 0.00;
		return_price.impact = '';

		$.each(ppbs_price_adjustments_json, function (key, areaPrice) {
			if (totalArea >= parseFloat(areaPrice.area_low) && totalArea <= parseFloat(areaPrice.area_high)) {
				if (areaPrice.impact == '-') price = price - parseFloat(areaPrice.price);
				if (areaPrice.impact == '+') price = price + parseFloat(areaPrice.price);
				if (areaPrice.impact == '*') price = price * parseFloat(areaPrice.price);
				if (areaPrice.impact == '=') price = parseFloat(areaPrice.price);
				if (areaPrice.impact == '~') price = parseFloat(areaPrice.price);

				return_price.value = price;
				return_price.impact = areaPrice.impact;
			}
		});
		return return_price;
	};

	/**
	 * removes alphabetical characters to return a decimal value only (price)
	 */
	self.removeFormatting = function (number) {
		if (number.indexOf('.') > 0)
			number = number.replace(",", "");
		else
			number = number.replace(",", ".");
		number = number.replace(/[^\d\.-]/g, '');
		return (number);
	};

	/**
	 * Truncate the number to a fixed number of decimal places without truncating
 	 * @param value
	 * @param decimals
	 */
	self.truncateDecimals = function(value, decimals) {
		if (value.indexOf('.') > 0) {
			var pre_decimal = value.substring(0, value.indexOf('.'));
			var post_decimal = value.substring(value.indexOf('.'), value.length);
			post_decimal = post_decimal.substring(1, decimals+1);
			if (post_decimal != '') {
				return pre_decimal + '.' + post_decimal;
			} else {
				return pre_decimal;
			}
		} else {
			return value;
		}
	};

    /**
     *
     * @param default_unit_value
     * @returns {*|number}
     */
	self.convertFromDefaultUnitToSelectedUnit = function(default_unit_value) {
        let conversion_factor_default = parseFloat(self.getDefaultConvertUnit().attr('data-conversion_factor'));
        let $selected_unit = self.getSelectedConvertUnit();
        let conversion_factor = parseFloat($selected_unit.attr('data-conversion_factor'));
        return (conversion_factor_default * default_unit_value) / conversion_factor;
    };


	/**
	 * Validate each dimension field has valid values within range
	 * @param validate_empty
	 * @returns {boolean}
	 */
	self.validate = function (validate_empty, showErrorState = true) {
		var error = false;
        let min_converted = 0;
        let max_converted = 0;

		$(".ppbs_error").hide();

		self.$wrapper.find("input.unit").removeClass("error");
		self.$wrapper.find(".error-unit").hide();

		self.$wrapper.find("input.unit").each(function (i, obj) {
			arr_temp = $(obj).attr("name").split("-");
			id = arr_temp[1];

			if (typeof ppbs_product_fields[id] !== "undefined") {
				$(obj).val(self.truncateDecimals($(this).val(), parseInt(ppbs_product_fields[id].decimals)));
				val = $(obj).val();
				if ($(obj).attr('data-default') != '' && parseFloat($(obj).attr('data-default')) > 0) {
				    val = parseFloat($(obj).attr('data-default')).toFixed(4);
                }

				min = parseFloat(ppbs_product_fields[id].min).toFixed(4);
				max = parseFloat(ppbs_product_fields[id].max).toFixed(4);

                if (self.isUnitConversionOptionsEnabled()) {
                    min_converted = self.convertFromDefaultUnitToSelectedUnit(min);
                    max_converted = self.convertFromDefaultUnitToSelectedUnit(max);
                } else {
                    min_converted = min;
                    max_converted = max;
                }

                if (val != "" && !$(obj).hasClass('dd_unit_hidden')) {
					var this_error = false;
					if (parseFloat(min) > 0 && val < parseFloat(min)) this_error = true;
					if (parseFloat(max) > 0 && val > parseFloat(max)) this_error = true;
					if (this_error) {
						error = true;
						$(obj).addClass("error");
                        $(obj).parents(".unit-entry").find('.error-unit').html(parseFloat(min_converted).toFixed(2) + ' - ' + parseFloat(max_converted).toFixed(2));
						$(obj).parents(".unit-entry").find(".error-unit").fadeIn();
					}
				}

				if (validate_empty && val == "") {
					error = true;
					if (showErrorState) {
                        $(obj).addClass("error");
                        $(obj).next("select").addClass("error");
                    }
				}
			}
		});
		if (error) {
            if (showErrorState) {
                $(".ppbs_error").fadeIn();
            }
			$("#main form#add-to-cart-or-refresh .add-to-cart").prop('disabled', true);
		} else {
			$("#main form#add-to-cart-or-refresh .add-to-cart").prop('disabled', false);
		}
		return !error;
	};

	/**
	 * Get custom equation based on attribute combination or product
	 */
	self.getEquation = function () {
		var equation = '';

		if (typeof(ppbs_equations_collection[self.product_info.id_product_attribute]) !== "undefined") {
			let equation_obj = ppbs_equations_collection[self.product_info.id_product_attribute];
			if (equation_obj.equation_template != '') {
				equation = equation_obj.equation_template;
			} else {
				equation = equation_obj.equation;
			}
		}

		if (equation == '') {
			if (typeof ppbs_equations_collection[0] !== 'undefined') {
				let equation_obj = ppbs_equations_collection[0];
				if (equation_obj.equation_template != '') {
					equation = equation_obj.equation_template;
				} else {
					equation = equation_obj.equation;
				}
			}
			else {
				equation = '';
			}
		}
		return equation;
	};

	self.formatPrice = function (price, callback) {
        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=front_ajax&route=ppbsfrontproductcontroller&action=formatprice');
		return $.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			data: {
				'price': price
			},
			//dataType: 'json',
			success: function (resp) {
				callback(resp);
			}
		});
	};

	/**
	 * Replace all occurances of a substring in a string
	 * @param search
	 * @param replace
	 * @param subject
	 * @returns {string}
	 */
	self.replaceAll = function (search, replace, subject) {
		return subject.split(search).join(replace);
	};

	/**
	 * Appply Specific Price Discounts
	 * @param price_original
	 * @param mode ('percentage' | 'amount' | 'both')
	 */
	self.applySpecificPriceDiscounts = function (price_original, mode) {
		var price_new = price_original;
		if (self.product_info.reduction > 0) {
			if (typeof self.product_info.specific_prices !== 'undefined') {

				if (mode == 'percentage' || mode == 'both') {
					if (self.product_info.specific_prices.reduction_type == 'percentage') {
						return price_original - (price_original * (self.product_info.specific_prices.reduction));
					}
				}

				if (mode == 'amount' || mode == 'both') {
					if (self.product_info.specific_prices.reduction_type == 'amount') {
						if (self.product_info.specific_prices.reduction_tax == 0) {
							var price_new = price_original - self.product_info.specific_prices.reduction;
						} else {
							var price_new = price_original - self.removeTax(self.product_info.specific_prices.reduction);
						}

						if (price_new < 0) {
							price_new = 0;
						}
						return price_new;
					}
				}
			}
		} else {
			return price_original;
		}
	}

	/**
	 * Apply customer group discount
	 * @param price_original
	 */
	self.applyCustomerGroupDiscount = function(price_original) {
		if (self.product_info.group_reduction > 0) {
			price_original = (price_original * (1 - (self.product_info.group_reduction / 100)));
		}
		return price_original;
	};


    /**
     * Apply group and specific price discounts
     * @param price_original
     * @param is_attribute
     * @returns {*}
     */
	self.applyExtraDiscounts = function (price_original, is_attribute) {
		if (self.product_info.reduction > 0) {
			if (typeof self.product_info.specific_prices !== 'undefined') {
                let price = self.product_info.specific_prices.price;
				if (self.product_info.specific_prices.reduction_type == 'percentage') {
					price_original =  price_original - (price_original * (self.product_info.specific_prices.reduction));
					return price_original;
				}

				if (self.product_info.specific_prices.reduction_type == 'amount') {
					reduction = parseFloat(self.product_info.specific_prices.reduction);
					if (self.product_info.specific_prices.reduction_tax == 0) {
						price_original = price_original - reduction;
					} else {
						price_original = price_original - self.removeTax(reduction);
					}
					return price_original;
				}
			}
		} else {
		    if (self.product_info.specific_prices == null) {
		        return price_original;
            }

            if (self.product_info.specific_prices.price > 0) {
                if (is_attribute) {
                    return price_original;
                } else {
                    return self.product_info.specific_prices.price;
                }
            } else {
                return price_original;
            }
		}
		return price_original;
	};

	/**
	 * Get the numeric value o0f the selected dropdown option
	 */
	self.getDropdownOptionValue = function (value) {
		if (value.indexOf(':') > 0) {
			var tmp_arr = value.split(':');
			return tmp_arr[0];
		} else {
			return value;
		}
	};

    /**
     * return a true number from a string
     * @param value
     * @returns {number}
     */
	self.cleanNumber = function(value) {
        let return_value = self.removeFormatting(value);
        if (return_value == "" || isNaN(return_value)) {
            return_value = 0.00;
        }
        return parseFloat(return_value);
    };

    /**
     * convert a value to it's default unit
     * @param value_base
     * @returns {number}
     */
	self.convertToDefaultUnit = function(value_base) {
		let conversion_factor = parseFloat(self.getDefaultConvertUnit().attr('data-conversion_factor'));
		val = value_base / conversion_factor;
		return val;
    }

	/**
	 * Get total area entered
	 * @private
	 */
    self._getTotalArea = function (perform_front_end_conversion) {
		var multiplier = 1;
		self.$wrapper.find("input.unit, select.dd_options").each(function (i, obj) {
			if ($(obj).is(":hidden")) {
				return true; // continue
			}

			if ($(obj).prop('type') == 'select-one') {
                val = self.cleanNumber(self.getDropdownOptionValue($(obj).val()));
			}

			if ($(obj).prop('type') == 'text') {
			    val = self.cleanNumber($(obj).val());
			}

			if (self.isUnitConversionOptionsEnabled()) {
			    let conversion_factor = parseFloat(self.getDefaultConvertUnit().attr('data-conversion_factor'));
			    let base = parseFloat($(this).attr('data-base'));
			    val = base / conversion_factor;
            } else {
                if (val > 0 && ppbs_product_json.front_conversion_enabled == 1 && perform_front_end_conversion == true) {
                    switch (ppbs_product_json.front_conversion_operator) {
                        case "*":
                            val = val * parseFloat(ppbs_product_json.front_conversion_value);
                            break;
                        case "/":
                            val = val / parseFloat(ppbs_product_json.front_conversion_value);
                            break;
                    }
                }
            }
			multiplier = parseFloat(multiplier) * parseFloat(val);
			multiplier = parseFloat(multiplier.toPrecision(12));
		});
		return multiplier;
    };

	/**
	 * Calculate price from custom equation
	 * @param equation
	 * @returns {Number|*}
	 */
	self.calculatePriceFromEquation = function (equation) {
		var qty = parseFloat($("#quantity_wanted").val());
		equation_parsed = equation;

		self.$wrapper.find("input.unit, select.dd_options").each(function (i, obj) {
			val = self.getDropdownOptionValue($(obj).val());
			val = self.removeFormatting(val);
            val = self.cleanNumber(val);
            var base = parseFloat($(this).attr('data-base'));

			if (typeof base !== typeof undefined && base !== false && parseFloat(base) > 0 && self.hasCustomConversions() == true) {
                val = self.convertToDefaultUnit(base);
            }
			equation_parsed = self.replaceAll('[' + $(obj).attr("data-dimension_name") + ']', val, equation_parsed);
		});

        let total_area = self._getTotalArea(true);
        if (total_area < parseFloat(ppbs_product_json.min_total_area)) {
            total_area = parseFloat(ppbs_product_json.min_total_area);
        }

        let area_price = self.getPrice(self._getTotalArea(true)).value;
        area_price = self.applyExtraDiscounts(area_price, false);

        equation_parsed = self.replaceAll('[attribute_price]', self.product_info.attribute_price, equation_parsed);
        equation_parsed = self.replaceAll('[base_price]', self.product_info.base_price_exc_tax, equation_parsed);
        equation_parsed = self.replaceAll('[product_price]', self.product_info.price_tax_exc, equation_parsed);
        equation_parsed = self.replaceAll('[quantity]', qty, equation_parsed);
        equation_parsed = self.replaceAll('[area_price]', area_price, equation_parsed);
        equation_parsed = self.replaceAll('[total_area]', total_area, equation_parsed);
		equation_parsed = self.replaceAll('[pco_price_impact]', self.pco_event.price_impact, equation_parsed);

		if (ppbs_global_variables.length > 0) {
		    for (let i=0; i < ppbs_global_variables.length; i++) {
                equation_parsed = self.replaceAll('[' + ppbs_global_variables[i].name + ']', ppbs_global_variables[i].value, equation_parsed);
            }
        }

        // Parse the conditions in the equations
		var regex = /\{if(.*?)\{\/if\}/gim;
		for (matches = []; result = regex.exec(equation_parsed); matches.push(result)) ;

		for (x = 0; x <= matches.length - 1; x++) {
			var str = matches[x][1];
			var if_str = str.substr(0, str.indexOf('}'));

			var str = str.substr(str.indexOf('}') + 1);
			var results = str.split('{else}');

			if (results.length > 1) {
				//if there was an {else}
				if (eval(if_str)) {
					result = results[0];
				}
				else {
					result = results[1];
				}
				equation_parsed = equation_parsed.replace(matches[x][0], result);
			} else {
				if (eval(if_str)) {
					result = results[0];
					equation_parsed = equation_parsed.replace(matches[x][0], result);
				} else {
					equation_parsed = equation_parsed.replace(matches[x][0], '');
				}
			}
		}

        equation_parsed = equation_parsed.replaceAll('ceil', 'Math.round');
		price = parseFloat(eval(equation_parsed));
		return price;
	};

	/**
	 * Determine if a product has ratio for it's fields or not
	 * @returns {boolean}
	 */
	self.productHasRatios = function () {
		if (ppbs_field_ratios_json.length > 1) {
			return true;
		} else {
			return false;
		}
	};


	/**
	 * Get the field ratio from the json array
	 * @param id_ppbs_field
	 */
	self.getFieldRatio = function (id_ppbs_product_field) {
		if (typeof ppbs_field_ratios_json === 'undefned') {
			return false;
		}

		for (i = 0; i < ppbs_field_ratios_json.length; i++) {
			if (ppbs_field_ratios_json[i].id_ppbs_product_field == id_ppbs_product_field) {
				return ppbs_field_ratios_json[i];
			}
		}
		return false;
	};

	/**
	 * Apply Ratios
	 */
	self.applyRatios = function ($sender) {
		var $inputs = self.$wrapper.find("input.unit").not($sender);
		var base_value = parseFloat($sender.val());
		var base_ratio = self.getFieldRatio($sender.attr("data-id_ppbs_product_field"));

		$inputs.each(function (i, obj) {
			var field_ratio = self.getFieldRatio($(obj).attr("data-id_ppbs_product_field"));

			if (field_ratio) {
				if (base_ratio.ratio > field_ratio.ratio) {
					new_value = (base_value / base_ratio.ratio) * field_ratio.ratio;
					$(obj).val(new_value.toFixed(2));
				} else {
					new_value = (field_ratio.ratio / base_ratio.ratio) * base_value;
					$(obj).val(new_value.toFixed(2));
				}
			}
		});
		$sender.val(base_value.toFixed(2));
	};

	/**
	 * Update the display of the total area
 	 */
	self.updateTotalAreaDisplay = function() {
		if (ppbs_options.display_total_area == 0) {
			return false;
		}
		let total_area = self._getTotalArea(true);
		let unit = '';

		if (self.isUnitConversionOptionsEnabled()) {
            unit = self.getDefaultConvertUnit().html();
        } else {
		    if (typeof ppbs_product_json.default_unit.symbol !== 'undefined') {
                unit = ppbs_product_json.default_unit.symbol;
            }
        }
		$("#ppbs-total-area .ppbs-total-area-value").html(total_area + ' ' + unit);
	};

    /**
     * Disable add to cart
     */
    self.enableAddToCart = function () {
        $("#main form#add-to-cart-or-refresh .add-to-cart").prop('disabled', false);
    };

    /**
     * Disable add to cart
     */
	self.disableAddToCart = function() {
        $("#main form#add-to-cart-or-refresh .add-to-cart").prop('disabled', true);
    };

    /**
     * Check if enough of the product is in stock based on area
     * @param total_area
     * @returns {boolean}
     */
	self.inStock = function(total_area) {
	    if (ppbs_product_json.stock_enabled == 0) {
	        return true;
        }

	    if (total_area > self.product_info.qty_stock) {
	        return false;
        } else {
            return true;
        }
    };

	/**
	 * Dynamically calculate the price and display it based on dimensions and attributes selected
	 */
	self.updatePrice = function () {
		let qty = 1;
		let total_area = self._getTotalArea(true);
		let total_area_unconverted = self._getTotalArea(false);

		if ($("#quantity_wanted").length > 0) {
			qty = $("#quantity_wanted").val();
		}

		if (ppbs_product_json.stock_enabled == '1') {
			if (!self.inStock(parseFloat(total_area_unconverted) * parseFloat(qty))) {
				self.disableAddToCart();
				$(self.div_stock_warning).show();
			} else {
				self.enableAddToCart();
				$(self.div_stock_warning).hide();
			}
		}

		if (self.debug) console.log('3. update price');
		var equation = self.getEquation();

		if (ppbs_product_json.equation_enabled == 1 && equation != '') {
			price = self.calculatePriceFromEquation(equation);

			if (price < parseFloat(ppbs_product_json.min_price)) {
				price = parseFloat(ppbs_product_json.min_price);
			}

			//final_price = self.applyExtraDiscounts(parseFloat(price));
			final_price = price;
            final_price = final_price + self.product_info.ppbs.setup_fee;
			final_price = self.applyCustomerGroupDiscount(parseFloat(final_price));
			final_price = self.addTax(parseFloat(final_price.toPrecision(8)));

            if (typeof pco_enabled !== 'undefined' && pco_enabled) {
                let event = {
                    'price': final_price,
                    'originator': 'productpricebysize',
                    'callback': function (final_final_price) {
                        self.formatPrice(parseFloat(final_final_price), function (formatted_price) {
                            $("div#ppbs-price").html(formatted_price);
                        });

                        self.formatPrice(parseFloat(area_price), function (formatted_price) {
                            $("div#ppbs-area-price").html(formatted_price + ' ' + ppbs_translations.area_price_suffix);
                        });
                    }
                };
                prestashop.emit('productPriceUpdated', event);
            } else {
                self.formatPrice(parseFloat(final_price), function (formatted_price) {
                    $("div#ppbs-price").html(formatted_price);
                });
            }
			self.updateTotalAreaDisplay();
            return false;
		}

		// or else proceed with linear / area range calculation
		var multiplier = total_area;

		if (multiplier < ppbs_product_json.min_total_area && ppbs_product_json.min_total_area > 0) {
			multiplier = parseFloat(ppbs_product_json.min_total_area);
		}

        var final_price = 0.00;
		var area_price = 0.00;
		var price = self.getPrice(multiplier * qty);

		price.value = self.applyExtraDiscounts(price.value, false);
		price.value = self.applyCustomerGroupDiscount(parseFloat(price.value));
        attribute_price = self.applyExtraDiscounts(self.product_info.attribute_price, true);

		if (price.impact != '') {
			if (price.impact == '=') {
				final_price = price.value;
				area_price = price.value;
			}
			if (price.impact == '~' || price.impact == '+' || price.impact == '-' || price.impact == '*') {
				if (ppbs_product_json.attribute_price_as_area_price == 1) {
					final_price = price.value_with_attr * multiplier.toPrecision(8);
					area_price = price.value_with_attr;
				} else {
					final_price = (price.value * multiplier.toPrecision(8)) + self.product_info.attribute_price;
					area_price = price.value + self.product_info.attribute_price;
				}
			}
		} else {
			if (ppbs_product_json.attribute_price_as_area_price == 1) {
				final_price = (price.value + attribute_price) * multiplier.toPrecision(8);
				area_price = (price.value + attribute_price);
			} else {
                final_price = (price.value * multiplier.toPrecision(8)) + attribute_price;
                area_price = price.value + attribute_price;
            }
		}
		area_price = parseFloat(area_price);

		if (ppbs_product_json.min_price > 0 && final_price < ppbs_product_json.min_price) {
			final_price = parseFloat(ppbs_product_json.min_price);
			area_price = parseFloat(ppbs_product_json.min_price);
		}

		final_price = final_price + self.product_info.ppbs.setup_fee;

		if (self.pco_event.price_impact > 0) {
            final_price = final_price + self.pco_event.price_impact;
        }

		final_price = self.addTax(parseFloat(final_price.toPrecision(8)));
		final_price = final_price * qty;

		area_price = self.addTax(parseFloat(area_price.toPrecision(8)));

		if (multiplier == 0) {
			$("div#ppbs-area-price").hide();
		} else {
			$("div#ppbs-area-price").show();
		}

		if (isNaN(area_price)) {
			area_price = 0;
		}

        if (typeof pco_enabled !== 'undefined' && pco_enabled) {
            let event = {
                'price': final_price,
                'originator': 'productpricebysize',
                'callback' : function(final_final_price) {
                    self.formatPrice(parseFloat(final_final_price), function (formatted_price) {
                        $("div#ppbs-price").html(formatted_price);
                    });

                    self.formatPrice(parseFloat(area_price), function (formatted_price) {
                        $("div#ppbs-area-price").html(formatted_price + ' ' + ppbs_translations.area_price_suffix);
                    });
                }
            };
            prestashop.emit('productPriceUpdated', event);
        } else {
            self.formatPrice(parseFloat(final_price), function (formatted_price) {
                $("div#ppbs-price").html(formatted_price);
            });

            self.formatPrice(parseFloat(area_price), function (formatted_price) {
                $("div#ppbs-area-price").html(formatted_price + ' ' + ppbs_translations.area_price_suffix);
            });
        }
		self.updateTotalAreaDisplay();
	};

    /**
     * Apply step increment to text box
     * @param $sender
     */
	self.applyStepIncrement = function($sender) {
        let value = 0;
        let step = parseFloat($sender.attr('data-step'));
        let rounded = 0;

        if (isNaN(step) || step == 0) {
            return false;
        }

        if (!$sender[0].hasAttribute('data-step')) {
            return false;
        }
        if ($sender.val().includes(',')) {
            value = parseFloat($sender.val().replace(',', '.'));
            rounded = Math.ceil(value / step) * step;
            rounded = rounded.toString().replace('.', ',');

        } else {
            value = parseFloat($sender.val());
            rounded = Math.ceil(value / step) * step;
        }
        $sender.val(rounded);
    };

    /**
     * determine if the option to allow customer to switch between units is enabled for this product
     * @returns {boolean}
     */
	self.isUnitConversionOptionsEnabled = function() {
	    if ($(self.div_conversion_units).length > 0) {
	        return true;
        } else {
	        return false;
        }
    };

    /**
     * get the default conversion unit element
     * @returns {*|jQuery|HTMLElement}
     */
	self.getDefaultConvertUnit = function() {
        return $(self.div_conversion_units).find("[data-default='true']");
    }

    /**
     * get the selected conversion unit
     * @returns {*|jQuery|HTMLElement}
     */
	self.getSelectedConvertUnit = function() {
        return $(self.div_conversion_units).find(".convert-unit.selected");
    };

    /**
     * calculate dimensions in equivalent base units and apply to each field
     */
	self.calculateBaseUnits = function(convert_field_value = false) {
	    let $selected_unit = self.getSelectedConvertUnit();
        var conversion_factor = parseFloat($selected_unit.attr('data-conversion_factor'));

        if (self.isUnitConversionOptionsEnabled()) {
            self.$wrapper.find(".unit").each(function () {
                current_base_value = parseFloat($(this).attr('data-base'));
                converted_value = current_base_value / conversion_factor;  // convert the current field value to the newly selected unit

                if (convert_field_value === true) {
                    $(this).val(converted_value);
                }

                id_ppbs_product_field = $(this).attr('data-id_ppbs_product_field');
                val = self.cleanNumber($(this).val());
                base = val * conversion_factor;
                $(this).attr('data-base', base.toFixed(2))

                // Apply value in default units to this field as well, will be needed for validation and adding to cart
                let conversion_factor_default = parseFloat(self.getDefaultConvertUnit().attr('data-conversion_factor'));
                val = base.toFixed(2) / conversion_factor_default;
                $(this).attr('data-default', val);
                $(self.wrapper + " input[name='ppbs_field-" + id_ppbs_product_field + "-default-unit']").val(val);
            });
        } else {
            self.$wrapper.find(".unit").each(function () {
                id_ppbs_product_field = $(this).attr('data-id_ppbs_product_field');
                val = self.cleanNumber($(this).val());
                $(self.wrapper + " input[name='ppbs_field-" + id_ppbs_product_field + "-default-unit']").val(val);
            });
        }
    }

    /**
     * unit of measurement being used has been changed
     * @param $sender
     */
	self.onUnitChanged = function($sender) {
	    $(self.div_conversion_units).find(".convert-unit").removeClass('selected');
	    $sender.addClass('selected');
	    self.calculateBaseUnits(true);
	    let symbol = $sender.text();
	    self.$wrapper.find(".field-input-wrapper .suffix").html(symbol);
        if (self.validate(true)) {
            self.updatePrice();
        }
    };

	/**
	 * Bind widgets controls to any events (which need to be done after widget is rendered via ajax)
	 */
	self.bindWidgetEvents = function () {
		self.$wrapper = $(self.wrapper);
		$('input.unit').typeWatch({
			callback: function () {
                self.applyStepIncrement($(this.el));
				if (self.productHasRatios()) {
					self.applyRatios($(this.el));
				}
                self.calculateBaseUnits(false);
				if (self.validate(true)) {
                    self.creativeElements.reflectForm();
					self.updatePrice();
				}
			},
			wait: 500,
			highlight: false,
			captureLength: 0
		});
	};

    /**
	 * Display the widget via ajax
	 */
	self.renderWidget = function () {
        if (self.creativeElements.enabled) {
            self.status = 1;
            self.creativeElements.duplicateForm();
            self.bindWidgetEvents();
            self.calculateBaseUnits(false);
            return;
        }

        var url = MPTools.joinUrl(module_ajax_url_ppbs, 'section=front_ajax&route=ppbsfrontproductcontroller&action=renderwidget&rand=' + new Date().getTime());
		return $.ajax({
			type: 'POST',
			url: url,
			async: true,
			cache: false,
			data: {
				'id_product': self.getProductID(),
                'id_currency' : ppbs.id_currency
			},
			success: function (html) {
				self.status = 1;
				if (self.debug) console.log('1. render widget');
                if ($("div.product-variants").length) {
					$(html).insertBefore("div.product-variants");
				} else {
					$(html).insertBefore("div.product-add-to-cart:first");
				}
				self.bindWidgetEvents();
                self.calculateBaseUnits(false);
			}
		});
	};

    self.creativeElements.duplicateForm = function () {
        let $form = self.getAddToCartForm();
        $(self.$wrapper).find(':input').each(function () {
            if ($(this).attr('name') != '' && typeof $(this).attr('name') !== 'undefined') {
                $('<input>').attr({
                    type: 'text',
                    name: "" + $(this).attr('name')
                }).appendTo($form);
            }
        });
    };

    self.creativeElements.reflectForm = function () {
        let $form = self.getAddToCartForm();
        $(self.$wrapper).find(':input').each(function () {
            if ($(this).attr('name') != '' && typeof $(this).attr('name') !== 'undefined') {
                $form.find("[name='" + $(this).attr('name') + "']").val($(this).val());
            }
        });
    };

    self.init = function () {
		$.when(self.renderWidget()).then(self.getProductInfo).then(self.updatePrice);
	};
	self.init();


	/** Events **/

	/**
	 * On dimension drop down selection changed
	 */
	$(document).on('change', self.wrapper + ' select.dd_options', function () {
		var $input = $("input[name='" + $(this).attr("data-for") + "']");
		var value = $(this).val().split(':');
		if (typeof value[0] === 'undefined') {
			$input.val(0);
		} else {
			$input.val(value[0]);
		}
		self.validate(true, false);
		self.updatePrice();
	});

	/**
	 * On input entry delete click
	 */
	$(document).on('keyup', self.wrapper + ' input.unit', function (e) {
		if (e.keyCode == 46 || e.keyCode == 8) {
			if (self.validate(true)) {
				self.updatePrice();
			}
		}
	});

	/**
	 * Prevent form submit when user presses enter and move to next input
 	 */
	$(document).on('keydown', self.wrapper + ' input.unit', function (e) {
		if (e.keyCode == 13) {
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
			e.preventDefault();
			var inputs = $(this).closest('form').find(':input.unit:visible');
			inputs.eq(inputs.index(this) + 1).focus();
			return false;
		}
	});

    /**
     * on measurements display unit changed
     */
    $(document).on('click', self.wrapper + ' .convert-unit', function (e) {
        self.onUnitChanged($(this));
    });


    /**
	 * On Attributes changed
	 */
	prestashop.on('updatedProduct', function (event) {
		self.getProductInfo().then(function () {
            self.bindWidgetEvents();
            self.calculateBaseUnits(false);
            if (self.validate(true)) {
                self.updatePrice();
            }
		});
	});
	
    /**
     * on product custom options price
     */
	 prestashop.on('productPriceUpdated', function (event) {
        if (self.status === 0) {
            setTimeout(function () {
                prestashop.emit('productPriceUpdated', event);
            }, 500);
            return false;
        }

        self.pco_event = event;
        self.updatePrice();
    });
};