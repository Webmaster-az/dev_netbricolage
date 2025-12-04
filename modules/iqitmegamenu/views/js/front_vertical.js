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




var cbpVerticalmenu;

$(document).ready(function(){

	$('.cbp-vertical-on-top').on( 'mouseover', function() {
  		$(this).addClass('cbp-vert-expanded');
		});

	$('.cbp-vertical-on-top').on( 'mouseleave', function() {
  		$(this).removeClass('cbp-vert-expanded');
		});
	

	
	cbpVerticalmenu = (function(test) {

	var menuId = '#cbp-hrmenu1',
		$listItems = $( menuId + '> ul > li'  ),
		$menuItems = $listItems.children( 'a' ),
		$innerTabs = $( menuId + ' .cbp-hrsub-tabs-names li > a'  ),
		$body = $( 'body' ),
		current = -1,
		currentlevel = -1;

	$listItems.has('ul').find(' > a').doubleTapToGo();



	function init() {
		
		var isTouchDevice = 'ontouchstart' in document.documentElement;
		if( isTouchDevice ) {
			$menuItems.on( 'mouseover', open );
		}
		else{
			$menuItems.hoverIntent( {
			over: open,
			out: dnthing,
			interval: 30
		} );
		}



		$listItems.on( 'mouseover', function( event ) { event.stopPropagation(); } );

		$innerTabs.click(function(event){
  			event.preventDefault();
  			link = $(this).data('link');
  			if (typeof link != 'undefined') {
  				window.location.href = link;
			}
  	
		});

		$innerTabs.hover( function(){
    	  $(this).tab('show');
   		});

   		$( window ).resize(function() {
 	 $('cbp-hrmenu-tab').not('.cbp-hropen').find( '.cbp-hrsub-wrapper' ).removeAttr( 'style' );
	});
	}

	function dnthing( event ) {

   	}

	var setCurrent = function(strName) {
        current = strName;
    };

	function open( event ) {

		
		$othemenuitem = $('#cbp-hrmenu').find('.cbp-hropen');


		$othemenuitem.find('.cbp-hrsub').removeClass('cbp-show');
		closeElement($othemenuitem);

		cbpHorizontalMenu.setCurrent(-1);

		var $item = $( event.currentTarget ).parent( 'li' ),
			idx = $item.index();



		if(current == idx )
			return;

		$submenu = $item.find('.cbp-hrsub');
		$submenu.removeClass('cbp-show');

		if( current !== -1 ) {
			closeElement($listItems.eq( current ));
		}

		if( current === idx ) {
			closeElement($item);
			current = -1;
			
		}
		else {
			$submenu.parent().width($('#columns').width()-$(menuId).width());
			callerHeight = $item.height();
			$submenu.parent().css( { marginLeft : $item.innerWidth()+"px", marginRight : $item.innerWidth()+"px", marginTop : -callerHeight+"px" } );
			$submenu.find('.cbp-triangle-container').css({top: (callerHeight-24)/2});
			$submenu.addClass( 'cbp-show' );
			$item.addClass( 'cbp-hropen' );
			current = idx;
			$body.off( 'mouseover' ).on( 'mouseover', close );
		}

		

		return false;

	}

	function close( event ) {
        closeElement($listItems.eq( current ));
		current = -1;
	}

	function closeElement( $element ) {
		$element.removeClass( 'cbp-hropen' );
	}

	return { init : init,
			  setCurrent: setCurrent
			};

})();

	cbpVerticalmenu.init();

});