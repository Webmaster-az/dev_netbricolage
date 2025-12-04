/*
* 2017-2020 Profileo
*
*  @author    Profileo
*  @copyright 2017-2020 Profileo
*/

$(document).ready( function (){
	$('.product_list li, .products article').each( function () {
		var price = $(this).find('.quantity_discount table tr:last-child td:last-child').html();
		if (typeof price != 'undefined')
			$(this).find('.price').html(from_eo+' '+price+' '+tax_value);
	});
	
	// Disable flicker effect
	var screenLg = $('body').find('.container').width() == 1170;

	$(document).off('mouseenter').on('mouseenter', '.product_list.grid li.ajax_block_product', function(e){
		if (screenLg)
		{
			var pcHeight = $(this).outerHeight();
			var pcPHeight = $(this).find('.button-container').outerHeight() + $(this).find('.comments_note').outerHeight() + $(this).find('.functional-buttons').outerHeight();
			$(this).addClass('hovered').css({'height':pcHeight + pcPHeight, 'margin-bottom':pcPHeight * (-1)});
			$(this).find('.button-container').show();
			
			if ($(this).find('.quantity_discount').length > 0){
				$(this).find('.quantity_discount').parent().css({'top':'0px'});
			}			
		}
	});

	$(document).off('mouseleave').on('mouseleave', '.product_list.grid li.ajax_block_product', function(e){
		if (screenLg)
		{
			$(this).removeClass('hovered').css({'height':'auto', 'margin-bottom':'0'});
			$(this).find('.button-container').hide();
		}
	});
});
