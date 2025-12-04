

/**

 * Multi accessories for PrestaShop

 *

 * @author    PrestaMonster

 * @copyright PrestaMonster

 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)

 */



/**

 * handle envent

 * @param {json} options

 * @returns {PriceTable}

 */

var PriceTable = function (options)

{

	/**

	 * contain all products accessories

	 */

	this.products = typeof options.products !== 'undefined' ? options.products : null;



	/**

	 * contain current id product

	 */

	this.randomMainProductId = typeof options.randomMainProductId !== 'undefined' ? options.randomMainProductId : null;



	/**

	 * contain text translate "sub total"

	 */

	this.subTotal = typeof options.subTotal !== 'undefined' ? options.subTotal : null;



	/**

	 * Change the main price when adding or removing accessories

	 */

	this.changeMainPrice = typeof options.changeMainPrice !== 'undefined' ? options.changeMainPrice : 0;



	/**

	 * Show table price

	 * 1 => show

	 * 0 => hide

	 */

	this.showTablePrice = typeof options.showTablePrice !== 'undefined' ? options.showTablePrice : 0;

        

	/**

	 * Show combination info in table price

	 * 1 => show

	 * 0 => hide

	 */

	this.showCombination = typeof options.showCombination !== 'undefined' ? options.showCombination : 0;



	/**

	 * Define warning accessory is out of stock

	 */

	this.warningOutOfStock = options.warningOutOfStock;

	

	/**

	 * Define warning accessory is out of stock

	 */

	this.warningNotEnoughProduct = options.warningNotEnoughProduct;

        

        /**

         * Define warning accessory is out of stock

         */

        this.warningCustomQuantity = options.warningCustomQuantity;

        

        /**

         * Show Option Image

         * 1 => show

         * 0 => hide

         */

        this.showOptionImage = options.showOptionImage;



	/**

	 * contain all selected products

	 */

	this.accessories = {};



	PriceTable.instance = this;



	/**

	 * Define array selectors

	 */

	this._selectors = {

		idCombination : '#idCombination', // define id combination

		mainProductPrice : '#our_price_display', // the box which contains official price of the main item

		accessoryItem: '.accessory_item', // Class of input which select accessory if you want add this accessory to cart.

		classNameAccessoryItem: 'accessory_item', // Class name of input which select accessory if you want add this accessory to cart.

		accessoriesGroup : '.accessories_group', // define class selecte box accessories

		groupAccessories : '#group_accessories', // define id group accessories

		classNameAccessoriesGroup : 'accessories_group', // define class name accessories group

		accessoriesTablePriceContent : '.accessories_table_price_content', // define class table contain price table

		accessoriesCustomQuantity : '.custom_quantity', // define class name of custom quantity of each accessory

		productAttributeColor: '.color_pick',

		productAttributeSelect: '.attribute_select',

		productAttributeRadio: '.attribute_radio',

		quantityWanted : '#quantity_wanted', // defined class input product quantity

		classChangeQuantity : '#product .product_quantity_down, #product .product_quantity_up', // defined class input change quantity

		productCombination : '#group_accessories .product-combination', // defined class select option change combination

		classProductCombination: '.product-combination', // defined class select option change combination

                classProductDDSlick: 'ddproductslick',                

		classError : 'error-number', // define class name error,

		accessoriesGroupCombination : '#group_accessories .accessories_group_combination', // define class div combination

		classProductImgLink : '.product_img_link', // define class product image link

                idAddToCartButton: '#add_to_cart',

                classMessageError: '.message_error',

                iconShowBlockGroup: '#group_accessories .option-row h4',

                iconExpand: 'icon_expand', // Name of icon expand +

                iconCollapse: 'icon_collapse', // Name of icon collapse -

                contentGroup: 'content_group', // Name of class content group

                accessoryGroup: '.option-row',

	};



	/**

	 * Check a value is interger number or not

	 * @param {string} value

	 * @returns {@exp;reg@call;test}

	 */

	this.isIntegerNumber = function (value) {

		var reg = /^\d+$/;

		return reg.test(value);

	};



	/**

	 * Event load default

	 */

	this.onLoad = function ()

	{

		if ($.isEmptyObject(this.products))

			return;

                var idCombination = parseInt($(PriceTable.instance._selectors.idCombination).val());

                if (idCombination > 0) {

                    idCombination = isNaN(idCombination) ? 0 : idCombination;

                    PriceTable.instance.products[PriceTable.instance.randomMainProductId].default_id_product_attribute = idCombination;

                }

                PriceTable.instance.triggerTablePrice();

		this._renderCombinations();

                $(".dd-options li").change(function(){          

                    var value = $(".dd-options li option:selected").find('span').hasClass('warning_out_of_stock');

                    if (value)

                        $(".dd-options li option:selected").attr('disabled','disabled');

                });

		// update table price

		$(document).on('change', this._selectors.idCombination, function () {

                        PriceTable.instance.products[PriceTable.instance.randomMainProductId].default_id_product_attribute = $(this).val();

                        PriceTable.instance.scrollToTablePrice();

			PriceTable.instance._updateMainProductPrice();

			PriceTable.instance._renderTablePrice();



		});

       

		// change combination

		$(document).on('click', this._selectors.iconShowBlockGroup, function () {

                        var element = $(this).find('i');

			PriceTable.instance._onClickBlockGroup(element);

		});

		// change combination

		$(document).on('focus', this._selectors.productCombination, function () {

			previousValueOfCombination = $(this).val();

		});

		$(document).on('change', this._selectors.productCombination, function () {

                        PriceTable.instance.scrollToTablePrice();

			var randomId = $(this).data('randomid');

			var idProductAttribute = parseInt($(this).val());

			customQuantity = $(this).parent().parent().find(PriceTable.instance._selectors.accessoriesCustomQuantity).val();

			customQuantity = typeof customQuantity !== 'undefined' ? customQuantity : PriceTable.instance.products[randomId]['qty'];

			var parentsElement = $(this).parents('tr');

			if (!PriceTable.instance.products[randomId]['combinations'][idProductAttribute]['out_of_stock'] && PriceTable.instance.products[randomId]['combinations'][idProductAttribute]['avaiable_quantity'] < customQuantity)

			{

				$(this).val(previousValueOfCombination);

				alert(PriceTable.instance.warningOutOfStock);

                                window.getSelection().removeAllRanges();

			}

			else

			{

				previousValueOfCombination = $(this).val();

				PriceTable.instance.products[randomId].default_id_product_attribute = idProductAttribute;

				PriceTable.instance._updateMainProductPrice();

				PriceTable.instance._renderTablePrice();

				PriceTable.instance._updateAccessoryPrice(PriceTable.instance.products[randomId]);

				PriceTable.instance._updateProductCombinationImage(parentsElement, PriceTable.instance.products[randomId]);

			}

			



		});



		// Event click on an accessor item

		$(document).on('click', this._selectors.accessoryItem, function() {

                        var selectedValue = $(this).val();

                        if (selectedValue != 0) {

                            PriceTable.instance.scrollToTablePrice();

                        }

			if (parseInt(window.buyTogetherOption) === parseInt(adminProductSetting.BUY_TOGETHER_YES))

			{

				if ($(this).parents('table').find(PriceTable.instance._selectors.accessoryItem + ':checked').length === 0)

				{

                                    $('<span class="message_error">'+ alertMessage +'</span>').insertBefore($(this).parents('table'));

				} else {

                                    $(this).parents('table').prev(PriceTable.instance._selectors.classMessageError).remove();

                                    PriceTable.instance.scrollToTablePrice();

                                }

			}

			else if (parseInt(window.buyTogetherOption) === parseInt(adminProductSetting.BUY_TOGETHER_REQUIRED) && $(this).attr('type') === 'checkbox')

			{

				if (parseInt($(this).data('required-buy-together')) === 1)

				{       

					alert(alertMessage);

                                        window.getSelection().removeAllRanges();

					$(this).parent().addClass('checked');

					$(this).attr("checked", "checked");

				}

                            PriceTable.instance.scrollToTablePrice();

			}

                        if ($(this).is(':checked')) {

                            currentQty = parseInt($(this).parents('tr').find(PriceTable.instance._selectors.accessoriesCustomQuantity).val());

                            // Set custom quantity to 1 if an accessory is selected

                            $(this).parents('tr').find(PriceTable.instance._selectors.accessoriesCustomQuantity).val(currentQty > 0 ? currentQty : 1);

                        }

			PriceTable.instance._initProductAccessories();

			PriceTable.instance._renderTablePrice();                       

		});



		// Change main product's combination

		$(document).on('click', this._selectors.productAttributeColor, function(){PriceTable.instance._renderTablePrice(true);});

		$(document).on('click', this._selectors.productAttributeRadio, function(){PriceTable.instance._renderTablePrice(true);});

		$(document).on('change', this._selectors.productAttributeSelect, function(){PriceTable.instance._renderTablePrice(true);});



		//$(this._selectors.accessoriesGroup).data('pre', $(this).val());

		$(this._selectors.accessoriesGroup).on('focus', function() {

			// Store the current value on focus and on change

			previousAccessory = this.value;

		}).change(function() {

                        PriceTable.instance.scrollToTablePrice();

			var randomId = $(this).find(':selected').data('randomid');

			if (typeof randomId !== 'undefined')

			{

				var customQuantity = PriceTable.instance.products[randomId].custom_quantity ? PriceTable.instance.products[randomId].custom_quantity : PriceTable.instance.products[randomId].default_quantity;

                                var idProductAttribute = this.options[this.selectedIndex].getAttribute('data-id-product-attribute');

				var isOutOfStock = false;

				if (!PriceTable.instance.products[randomId]['out_of_stock'] && PriceTable.instance.products[randomId]['avaiable_quantity'] < customQuantity)

					isOutOfStock = true;

				

				if (typeof idProductAttribute !== 'undefined' &&

					typeof PriceTable.instance.products[randomId]['combinations'][idProductAttribute] !== 'undefined' &&

					!PriceTable.instance.products[randomId]['combinations'][idProductAttribute]['out_of_stock'] &&

					PriceTable.instance.products[randomId]['combinations'][idProductAttribute]['avaiable_quantity'] < customQuantity

					)

					isOutOfStock = true;



				if (isOutOfStock)

				{

					$(this).val(previousAccessory);

					alert(PriceTable.instance.warningOutOfStock);

                                        window.getSelection().removeAllRanges();

				}

				else

				{       

					if (typeof PriceTable.instance.products[randomId] !== 'undefined' && Object.size(PriceTable.instance.products[randomId]['combinations']) > 1)

						PriceTable.instance._renderCombination(randomId, false);

					else

						$(this).next().html('');

					previousAccessory = this.value;

				}

			}

			else

			{

				$(this).next().html('');

				previousAccessory = this.value;

			}

			PriceTable.instance._initProductAccessories();

			PriceTable.instance._renderTablePrice();

		});



		// Event change the quantity of main product

		$(document).on('keyup', this._selectors.quantityWanted, function () {

			var qty = $(this).val();

			isIntegerNumber = PriceTable.instance.isIntegerNumber(qty);

			if (!isIntegerNumber)

			{

				$(this).addClass(PriceTable.instance._selectors.classError);

				$(this).select();

			}

			else

				$(this).removeClass(PriceTable.instance._selectors.classError);



			  PriceTable.instance.triggerTablePrice();

                     

		});

                

                // up & down qty input type number button on PS 1.6
			
		$(document).on('click', this._selectors.quantityWanted, function () {

                     PriceTable.instance.triggerTablePrice();

                       

		});
		



		// up & down qty button on PS 1.6

		$(document).on('click', this._selectors.classChangeQuantity, function () {

                     PriceTable.instance.triggerTablePrice();

                       

		});



		if ($('#product' + ' ' + this._selectors.groupAccessories + ' ' + this._selectors.accessoriesCustomQuantity).length > 0)

		{

			// Event change the custom quantity value

			$(this._selectors.accessoriesCustomQuantity).on('focus', function() {

				// Store the current value on focus and on change

				previousAccessoryQuantity = this.value;

			}).change(function() {

				var newQuantity = parseInt($(this).val());

				var randomId = 0;

				var idCombination = 0;

                                // in calse dropdown + show image are enabled   

                                if ($(this).parent().parent().find('.dd-selected-value').length > 0)

                                {

                                    randomId = $(this).parent().parent().find('.randomid-group').data('randomid');

                                    var idGroup = $(this).parent().parent().find('.randomid-group').data('idgroup');

                                    idCombination = $('#combination_'+idGroup).find('.dd-selected-value').val();

                                } else {

                                    randomId = typeof $(this).parent().parent().find(':checked').data('randomid') !== 'undefined' ? $(this).parent().parent().find(':checked').data('randomid') : $(this).parent().parent().find(PriceTable.instance._selectors.accessoryItem).data('randomid');

                                    idCombination = typeof $(this).parent().parent().find(':checked').data('id-product-attribute') !== 'undefined' ? $(this).parent().parent().find(':checked').data('id-product-attribute') : $(this).parent().parent().find(PriceTable.instance._selectors.accessoryItem).data('id-product-attribute');

                                }

                                if (typeof randomId === 'undefined' || randomId === 0)

                                    return;

                                

                                var minQuantity = parseInt(PriceTable.instance.products[randomId]['min_quantity']);

                                if (newQuantity < minQuantity) {

                                    $(this).val($(this).data('custom-quantity'));

                                    alert(PriceTable.instance.warningCustomQuantity.format(minQuantity));

                                    window.getSelection().removeAllRanges();

                                    return;

                                }

				//var isStockAvailable = PriceTable.instance.isStockAvailable(randomId, newQuantity, idCombination);

                                idCombination = typeof idCombination !== 'undefined' ? idCombination : 0;

                                var idAccessory = PriceTable.instance.products[randomId]['id_accessory'];

                                if (!idAccessory && typeof window.ajaxRenderAccessoriesUrl === 'undefined')

                                    return;

                                var currentThis = this;

                                $.ajax({

                                    type: 'POST',

                                    headers: {"cache-control": "no-cache"},

                                    url: window.ajaxRenderAccessoriesUrl,

                                    async: true,

                                    cache: false,

                                    dataType: "json",

                                    data: {

                                        'ajax' : true,

                                        'id_accessory' : idAccessory,

                                        'id_accessory_combination' : idCombination,

                                        'new_quantity' : newQuantity,

                                        'action' : 'isStockAvailable'

                                    },

                                    success: function (jsonData)

                                    {

                                        if(!jsonData.hasError)

                                        {

                                                isIntegerNumber = PriceTable.instance.isIntegerNumber(newQuantity);

                                                if (!isIntegerNumber)

                                                {

                                                    $(currentThis).addClass(PriceTable.instance._selectors.classError);

                                                    $(currentThis).select();

                                                }

                                                else

                                                {

                                                    $(currentThis).removeClass(PriceTable.instance._selectors.classError);

                                                    PriceTable.instance.products[randomId]['custom_quantity'] = newQuantity;

                                                    $(currentThis).data('custom-quantity', newQuantity);

                                                }

                                        }

                                        else

                                        {

                                            

                                                var avaiableQuantity = PriceTable.instance.products[randomId]['avaiable_quantity'];

                                                $(currentThis).val(avaiableQuantity);

                                                $(currentThis).data('custom-quantity', avaiableQuantity);

                                                alert(jsonData.errors);

                                                window.getSelection().removeAllRanges();

                                        }

                                    },

                                    complete: function() {

                                        if (parseInt($(currentThis).val()) > 0) {

                                            $(currentThis).parents('tbody').find('.radio').find('span').removeClass('checked');

                                            $(currentThis).parents('tbody').find('.radio').find('span input').prop('checked', false);

                                            $(currentThis).parents('tr').find('.checker, .radio').find('span').addClass('checked');

                                            $(currentThis).parents('tr').find('.checker, .radio').find('span input').prop('checked', true);

                                        } else {

                                            $(currentThis).parents('tr').find('.checker, .radio').find('span').removeClass('checked');

                                            $(currentThis).parents('tr').find('.checker, .radio').find('span input').prop('checked', false);

                                        }

                                        PriceTable.instance._initProductAccessories();

                                        PriceTable.instance._renderTablePrice();

                                        PriceTable.instance.scrollToTablePrice();

                                    }

                                });



			});

		}



		// Event show fancybox when customer click product image

		$(document).on('click', this._selectors.classProductImgLink, function(e) {

			$(PriceTable.instance._selectors.classProductImgLink).fancybox({

				'hideOnContentClick': false

			});

		});

		

	};

	/**

	 * Get all selected products if block accessories

	 */

	this._initProductAccessories = function ()

	{

		this.accessories = {};

		var selector = this._selectors;

                

                // in case option image for dropdown is enable    

                if ($(selector.groupAccessories).find('.dd-selected-value'))

                {

                    $(selector.groupAccessories + ' .randomid-group').each(function () {

                        var randomId = $(this).data('randomid');

                        if (typeof PriceTable.instance.products[randomId] !== 'undefined')

                        {

                            var quantity = PriceTable.instance.products[randomId].custom_quantity ? PriceTable.instance.products[randomId].custom_quantity : PriceTable.instance.products[randomId].default_quantity;

                            $(this).parent().find(PriceTable.instance._selectors.accessoriesCustomQuantity).val(quantity);

                            PriceTable.instance.accessories[randomId] = PriceTable.instance.products[randomId];

                            var qty = parseInt($('#product #quantity_wanted').val());

                            var idGroup = $(this).data('idgroup');

                            if ($('#product' + ' ' + selector.groupAccessories + ' ' + selector.accessoriesCustomQuantity).length > 0) {

                                qty = $('#quantity_' + idGroup).val();

                            }

                            if (qty >= 0) {

                               PriceTable.instance.products[randomId]['qty'] = qty; 

                            }

                            

                           $('#quantity_' + idGroup).val(PriceTable.instance.products[randomId]['qty']);

                           $('#quantity_' + idGroup).data('custom-quantity', PriceTable.instance.products[randomId]['qty']);

                        }

                    });

                }                        

                

		if ($(selector.groupAccessories).find('select').hasClass(this._selectors.classNameAccessoriesGroup))

		{

			$(PriceTable.instance._selectors.accessoriesGroup).each(function ()

			{

				var randomId = $(this).find(':selected').data('randomid');

				if (typeof PriceTable.instance.products[randomId] !== 'undefined')

				{

					var quantity = PriceTable.instance.products[randomId].custom_quantity ? PriceTable.instance.products[randomId].custom_quantity : PriceTable.instance.products[randomId].default_quantity;

					$(this).prev().val(quantity);

					PriceTable.instance.accessories[randomId] = PriceTable.instance.products[randomId];

					var qty = parseInt($('#product #quantity_wanted').val());



					if ($('#product' + ' ' + selector.groupAccessories + ' ' + selector.accessoriesCustomQuantity).length > 0) {

                                            qty = parseInt($('#quantity_' + $(this).attr('name').replace('accessory_', '')).val());

                                        }

                                        if (qty >= 0) {

                                            PriceTable.instance.products[randomId]['qty'] = qty;

                                        }

                                        

                                        $('#quantity_' + $(this).attr('name').replace('accessory_', '')).data('custom-quantity', PriceTable.instance.products[randomId]['qty']);

                                        $('#quantity_' + $(this).attr('name').replace('accessory_', '')).val(PriceTable.instance.products[randomId]['qty']);    

					if (!PriceTable.instance.showOptionImage && Object.size(PriceTable.instance.products[randomId]['combinations']) > 1)

						PriceTable.instance._renderCombination(randomId, false);

				}

				

			});

		}

		if ($(selector.groupAccessories).find('input').hasClass(this._selectors.classNameAccessoryItem))

		{

			$(PriceTable.instance._selectors.accessoryItem + ':checked').each(function(i)

			{

				var randomId = $(this).data('randomid');

				var qty = parseInt($('#product #quantity_wanted').val());

				if (typeof PriceTable.instance.products[randomId] !== 'undefined')

				{

					PriceTable.instance.accessories[randomId] = PriceTable.instance.products[randomId];

					if ($('#product' + ' ' + selector.groupAccessories + ' ' + selector.accessoriesCustomQuantity).length > 0)

						qty = parseInt($('#quantity_' + $(this).attr('id').replace('accessories_proudct_', '')).val());



					if (qty >= 0)

						PriceTable.instance.products[randomId]['qty'] = qty;

                                            

                                         $('#quantity_' + $(this).attr('id').replace('accessories_proudct_', '')).val(PriceTable.instance.products[randomId]['qty']);        

				}

			});

		}

		

		this.accessories[PriceTable.instance.randomMainProductId] = PriceTable.instance.products[PriceTable.instance.randomMainProductId];



	};



	/**

	 * update main product price when change combination

	 */

	this._updateMainProductPrice = function ()

	{

            if (typeof productPrice === 'undefined')

                return;

		// in this case, the current product does not have any combination, so no need to update price of the main item

		// refer to product.js::updatePrice()

		

		if (typeof priceWithDiscountsDisplay === 'undefined')

			priceWithDiscountsDisplay = productPrice;

                idCombination = PriceTable.instance.products[PriceTable.instance.randomMainProductId]['default_id_product_attribute'];

		PriceTable.instance.products[PriceTable.instance.randomMainProductId]['combinations'][idCombination]['price'] = priceWithDiscountsDisplay;

		var qty = $('#product #quantity_wanted').val();

		if (typeof qty !== 'undefined')

		{

			if (PriceTable.instance.isIntegerNumber(qty) && parseInt(qty) >= 0)

				PriceTable.instance.products[PriceTable.instance.randomMainProductId]['qty'] = parseInt(qty);

		}

	};



	/**

	 * render price table

	 */

	this._renderTablePrice = function (forceToChangeMainPrice)

	{

		if (typeof forceToChangeMainPrice === 'undefined')

			forceToChangeMainPrice = false;

                    

		if ($.isEmptyObject(this.accessories))

			return;

		var priceTable = '';

		var underline = '';

		var totalPrice = 0;     

		$.each(this.accessories, function (randomid, product)

		{

			var productPrice = 0;

                        var combinationName = '';

			$.each(product.combinations, function (idProductAttribute, combination) {

				if (typeof combination !== 'undefined' && (parseInt(product.default_id_product_attribute) === parseInt(idProductAttribute) || idProductAttribute == 0)) {

					productPrice = product.qty * combination.price;

                                        combinationName = combination.name;

                                    }

				if (!$.isEmptyObject(combination.specific_prices))

				{

					$.each(combination.specific_prices, function (fromQty, specificPrice) {

						if (product.qty >= fromQty)

							productPrice = product.qty * specificPrice;

					});

				}

				if (combination.is_cart_rule && (parseInt(product.default_id_product_attribute) === parseInt(idProductAttribute) || idProductAttribute == 0)) {

					productPrice = product.qty * combination.final_price;

				}

			});

			var outOfStockWarningIcon = PriceTable.instance._renderOutOfStockWarningIcon(product);

			underline = randomid === PriceTable.instance.randomMainProductId ? 'style="text-decoration: underline;"' : '';

			totalPrice += productPrice;

            var blockCombinationName = (combinationName && PriceTable.instance.showCombination) ? '<span class="ma_accessory_combination_name" title="' + combinationName + '">' + combinationName + '</span>' : '';

			priceTable = priceTable + '<tr>' +
						'<td class="left-column" ' + underline + '><span class="ma_accessory_name" title="' + product.name + '">' + product.qty + ' x ' + product.name + ':</span>' + blockCombinationName + '</td>' +
						'<td class="right-column">' + formatCurrency(productPrice, currencyFormat, currencySign, currencyBlank) + outOfStockWarningIcon + '</td>' +
					'</tr>';

		});



		var totals = formatCurrency(totalPrice, currencyFormat, currencySign, currencyBlank);

		priceTable = priceTable + '<tr>' +

				'<td class="left-column-total">' + this.subTotal + ':</td>' +

				'<td class="right-column-total">' + totals + '</td>' +

				'</tr>';



		if (parseInt(this.showTablePrice) === 1)

			$(this._selectors.accessoriesTablePriceContent).html(priceTable);



		if (this.changeMainPrice)

			$(this._selectors.mainProductPrice).html(totals);

	};



	/**

	 * Render out of stock warning icon

	 * @param {object} product

	 * @returns {string}

	 */

	this._renderOutOfStockWarningIcon = function(product)

	{

		var outOfStockWarningIcon = '<span title="'+ product.available_later +'" class="warning_out_of_stock"></span>';

		return product.is_available_when_out_of_stock ? outOfStockWarningIcon : '';

	};



	/**

	 * Render list combinations of products

	 */

	this._renderCombinations = function ()

	{

		if ($(this._selectors.groupAccessories).find('select').hasClass(this._selectors.classNameAccessoriesGroup))

		{       

                        if (this.showOptionImage)

                        {

                            this._renderProductOptionImage();

                        }                        

			$(PriceTable.instance._selectors.accessoriesGroup).each(function ()

			{

				var randomId = $(this).val();

				if (typeof PriceTable.instance.products[randomId] !== 'undefined' && Object.size(PriceTable.instance.products[randomId]['combinations']) > 1)

					PriceTable.instance._renderCombination(randomId, false);

			});



		}

		if ($(this._selectors.groupAccessories).find('input').hasClass(this._selectors.classNameAccessoryItem))

		{   

			$.each(this.products, function (randomId, product) {

                            if (Object.keys(product.combinations).length > 1)

                                PriceTable.instance._renderCombination(randomId, true);

			});

		}

		

	};



	/**

	 * Render combination of one product

	 * @param {string} randomId

	 * @param {boolean} checkbox

	 */

	this._renderCombination = function (randomId, checkbox)

	{    

                if (typeof randomId === 'undefined')

                    return;                

		var product = this.products[randomId];   

                var hasCombination = true;

		var html = '<select data-randomid="' + randomId + '" name="product-combination" class="product-combination">';                

                var i = 0;

                var defaultSelectedIndex  = 0;                          

		$.each(product.combinations, function (idProductAttribute, combination) {                        

                        var dataImg = '';

                        if (!checkbox)

                            dataImg = 'data-imagesrc="'+combination.image_default+'"';

                        var isStockAvailable = PriceTable.instance.isStockAvailable(randomId, product.default_quantity, idProductAttribute);

                        var stockAvailable = 0

                        if (isStockAvailable == true){

                            stockAvailable = 1

                        }

                        var dataStockAvailable = 'data-stockavailable="'+stockAvailable+'"';

			var selected = '';                        

			if (parseInt(idProductAttribute) === parseInt(product.default_id_product_attribute))

			{                              

                            defaultSelectedIndex = i;                            

                            selected = 'selected="selected"';

                        }                        

                        html += '<option '+dataImg+' '+dataStockAvailable+' value="' + idProductAttribute + '"' + selected + '>' + combination.name + '</option>';

                        if (idProductAttribute === 0)

                            hasCombination = false;

                        i++;  

		});



		html += '</select>';

                var classContainCombination = 'combination_'+product.id_accessory_group+'_'+product.id_accessory;

		if (checkbox && hasCombination)

                    $('.' + classContainCombination).html(html);

		else

		{                        

                    var selector = 'combination_'+product.id_accessory_group;                    

                    $('#' + selector).html('');                    

                    if (hasCombination)

                    {   

                        if(this.showOptionImage)

                            $('#' + selector).ddslick('destroy');                           

                        $('#' + selector).html(html);   

                        if(this.showOptionImage)

                            PriceTable.instance._renderCombinationOptionImage(selector, product, defaultSelectedIndex, randomId);                        

                        

                    } 

                    else

                    {

                        //product don't have combination use product's image

                        $('.accessory_image_'+product.id_accessory_group).html('<img src="'+product.combinations[0].image_default+'">');

                        $('.accessory_image_'+product.id_accessory_group).attr('href',product.combinations[0].image_fancybox);

                    }

		}



	};

        

        /**

         * 

         * Render list of product with image beside inside select option list. 

         */

        this._renderProductOptionImage = function () {

            $('.'+PriceTable.instance._selectors.classProductDDSlick).each(function () {

                var idDDSlick = $(this).attr('id');

                $('#' + idDDSlick).ddslick({

                    showSelectedHTML: false,                    

                    background: '#fff',

                    onSelected: function (data) {                           

                        var idGroup = $('#' + idDDSlick).parent().data('idgroup');

                        var randomId = data.selectedData.description;

                        // in case don't select any product then remove combination list

                        if (data.selectedData.value == 0)

                        {

                            var selector = 'combination_'+idGroup; 

                            $('#' + selector).html(''); 

                            // remove image

                            $('.accessory_image_'+idGroup).html('');

                            $('#randomid-group-'+idGroup).data('randomid',0); 

                        } else {

                            $('#randomid-group-'+idGroup).data('randomid',randomId); 

                        }  

                        PriceTable.instance._initProductAccessories();     

                        PriceTable.instance._renderTablePrice();

                        PriceTable.instance._renderCombination(randomId, false);     

                        PriceTable.instance.scrollToTablePrice();

                    }

                });

            });

        };

        

        /**

         * @param {selector} selector

         * @param {product} product

         * @param {defaultSelectedIndex} defaultSelectedIndex

         * @param {randomId} randomId

         * Render list of combination with image beside inside select option list. 

         */

        this._renderCombinationOptionImage = function (selector, product, defaultSelectedIndex, randomId) {  

            var previousValueOfCombination = product.default_id_product_attribute;

            $('#' + selector).ddslick({

                showSelectedHTML: false,                    

                background: '#fff', 

                defaultSelectedIndex: defaultSelectedIndex,

                onSelected: function (data) {   

                    var idGroup = product.id_accessory_group;

                    var idProductAttribute = data.selectedData.value;                                      

                    customQuantity = $('#'+selector).parent().find(PriceTable.instance._selectors.accessoriesCustomQuantity).val();

                    customQuantity = typeof customQuantity !== 'undefined' ? customQuantity : PriceTable.instance.products[randomId]['qty'];                    

                    if (!PriceTable.instance.products[randomId]['combinations'][idProductAttribute]['out_of_stock'] && PriceTable.instance.products[randomId]['combinations'][idProductAttribute]['avaiable_quantity'] < customQuantity)

                    {

                        var i = 0;

                        $.each(product.combinations, function (idProductAttribute, combination) {

                            if (idProductAttribute == previousValueOfCombination)

                                $('#' + selector).ddslick('select', {index: i});

                            i++;

                        });

                        alert(PriceTable.instance.warningOutOfStock);

                        window.getSelection().removeAllRanges();

                        return;

                    }

                    else

                    {                      

                        previousValueOfCombination = idProductAttribute;  

                        var selectedCombination = product.combinations[idProductAttribute];

                        if (selectedCombination.image_default !='')

                        {

                            $('.accessory_image_'+idGroup).html('<img src="'+selectedCombination.image_default+'">');

                        }

                        if (selectedCombination.image_fancybox !='')

                        {

                            $('.accessory_image_'+idGroup).attr('href',selectedCombination.image_fancybox);

                        }

                        PriceTable.instance.products[randomId].default_id_product_attribute = idProductAttribute;

                        PriceTable.instance.triggerTablePrice();

                        PriceTable.instance._updateAccessoryPrice(PriceTable.instance.products[randomId]);                        

                    }

                    PriceTable.instance.scrollToTablePrice();

                }

            });         

        };



	/**

	 * Update price of product accessory

	 * @param {object} product

	 */

	this._updateAccessoryPrice = function (product)

	{

		var price = product.combinations[product.default_id_product_attribute].price;

		var final_price = product.combinations[product.default_id_product_attribute].final_price;

		var is_cart_rule = product.combinations[product.default_id_product_attribute].is_cart_rule;

		if (price > 0)

		{

                    var classContainPrice = 'price_'+product.id_accessory_group+'_'+product.id_accessory;

                    $('.' + classContainPrice).html(formatCurrency(price, currencyFormat, currencySign, currencyBlank));

                }

                if (is_cart_rule) {

                    var classContainFinalPrice = 'final_price_'+product.id_accessory_group+'_'+product.id_accessory;

                    $('.' + classContainFinalPrice).html(formatCurrency(final_price, currencyFormat, currencySign, currencyBlank));

                }

	};



	/**

	 * Update image of product when change combination

	 * @param {Jquery} element

	 * @param {Object} product

	 */

	this._updateProductCombinationImage = function (element, product)

	{

		var selectedCombination = product.combinations[product.default_id_product_attribute];

		$(element).find('.accessory_img_link').attr('href', selectedCombination.image_fancybox);

		$(element).find('.accessory_image').attr('src', selectedCombination.image_default);



	};

	

	/**

	 * Check stock available of accessory when customer change quantity at front end

	 * @param {string} randomId

	 * @param {int} newQuantity

	 * @param {int} idCombination

	 * @returns {Boolean}

	 */

	this.isStockAvailable = function (randomId, newQuantity, idCombination)

	{

		var flag = true;

		idCombination = typeof idCombination !== 'undefined' ? idCombination : 0;

		var product = this.products[randomId].combinations[idCombination];

		var availableQuantity = product.avaiable_quantity;

		var outOfStock = product.out_of_stock;

		if (!outOfStock && availableQuantity < newQuantity)

			flag = false;

		return flag;

	};

        

        /**

         * Check if button add to cart is visible or not

         * @returns boolean

         */

        this.isAddToCartButtonVisible = function(){

            return $(PriceTable.instance._selectors.idAddToCartButton).visible();

        };

        

        /**

         * Scroll to table price if add to cart button is not visible 

         * (the heigh of accessories too big)

         * 

         */

        this.scrollToTablePrice = function(){

            if (!PriceTable.instance.isAddToCartButtonVisible())

            {

                if (parseInt(this.showTablePrice) === 1) {

                    $('html, body').animate({

                        scrollTop: $(PriceTable.instance._selectors.accessoriesTablePriceContent).offset().top - 100

                    }, 500);

                }

                

            }   

        };

        

        /**

         * Trigger events of table price

         */

        this.triggerTablePrice = function() {

            PriceTable.instance._updateMainProductPrice();

            PriceTable.instance._initProductAccessories();

            PriceTable.instance._renderTablePrice();

        };

        

       /**

        * Display or hide block contain group accessories

        * @param {Object} element

        */

       this._onClickBlockGroup = function (element)

       {

           

           if ($(element).hasClass(PriceTable.instance._selectors.iconCollapse)){

               $(element).removeClass(PriceTable.instance._selectors.iconCollapse);

               $(element).addClass(PriceTable.instance._selectors.iconExpand);

           } else {

               $(element).removeClass(PriceTable.instance._selectors.iconExpand);

               $(element).addClass(PriceTable.instance._selectors.iconCollapse);

           }

           $(element).parents(PriceTable.instance._selectors.accessoryGroup).find('.' + PriceTable.instance._selectors.contentGroup).toggle("slow");

       };

       this._onClickExpandGroup = function (element)

       {

           if ($(element).parents(PriceTable.instance._selectors.accessoryGroup).find('i').hasClass(PriceTable.instance._selectors.iconCollapse)){

                $(element).parents(PriceTable.instance._selectors.accessoryGroup).find('i').removeClass(PriceTable.instance._selectors.iconCollapse);

                $(element).parents(PriceTable.instance._selectors.accessoryGroup).find('i').addClass(PriceTable.instance._selectors.iconExpand);

                $(element).parents(PriceTable.instance._selectors.accessoryGroup).find('.' + PriceTable.instance._selectors.contentGroup).toggle("slow");

           }

        }

};



Object.size = function(obj) {

    var size = 0, key;

    for (key in obj) {

        if (obj.hasOwnProperty(key)) size++;

    }

    return size;

};

