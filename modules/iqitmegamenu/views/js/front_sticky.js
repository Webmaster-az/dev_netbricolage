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

var isStickMenu = true;
$(document).ready(function() {

	var s = $("#iqitmegamenu-horizontal");
	var pos = s.offset();
	var scartp = (s.outerHeight()/2)-13;
	var alreadySticky = false;

	var scart = $("#shopping_cart_container"); 

	    $(window).scroll(function() {
		var windowpos = $(window).scrollTop();
		if ( s.length  ){
			if(!alreadySticky) {
			if (windowpos >= pos.top) {
				alreadySticky = true;
				s.parent().height(s.height());
				s.removeClass("cbp-nosticky");
				s.addClass("cbp-sticky");
				scart.addClass("stickCart");
				scart.css({ top: scartp + 'px' });
			}
			}
			if(alreadySticky) { 
			if (windowpos < pos.top) {
				alreadySticky = false;
				s.removeClass("cbp-sticky");
				s.addClass("cbp-nosticky"); 
				scart.removeClass("stickCart");
				scart.removeAttr("style");
				s.parent().removeAttr("style");
			}}
		}
	});
});