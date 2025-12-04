/**
* 2007-2016 IQIT-COMMERCE.COM
*
* NOTICE OF LICENSE
*
*  @author    IQIT-COMMERCE.COM <support@iqit-commerce.com>
*  @copyright 2007-2016 IQIT-COMMERCE.COM
*  @license   GNU General Public License version 2
*
* You can not resell or redistribute this software.
* 
*/

$(document).ready(function(){


	$("#iqitmegamenu-accordion > li .responsiveInykator").on("click", function(event){

		if(false == $(this).parent().next().is(':visible')) {

			$('#iqitmegamenu-accordion > ul').removeClass('cbpm-ul-showed');
		}
		if($(this).text()=="+")
			$(this).text("-");
		else
			$(this).text("+");
		$(this).parent().children('ul').toggleClass('cbpm-ul-showed');
	});


var menuLeft = document.getElementById( 'iqitmegamenu-accordion' ),
	showLeftPush = document.getElementById( 'iqitmegamenu-shower' ),
	menuoverlay = document.getElementById( 'cbp-spmenu-overlay' ),
	body = document.body;

classie.addClass( body, 'cbp-spmenu-body' );	

$('#iqitmegamenu-shower').on("touchstart click", function(e){
	e.stopPropagation(); e.preventDefault();
		classie.toggle( showLeftPush, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				classie.toggle( menuoverlay, 'cbp-spmenu-overlay-show' );
});

$('#cbp-spmenu-overlay').on("touchstart click", function(e){
				e.stopPropagation(); e.preventDefault();
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				classie.toggle( this, 'cbp-spmenu-overlay-show' );
	});

});