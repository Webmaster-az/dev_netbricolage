/**
 * Multi Accessories Pro
 *
 * @author PrestaMonster
 * @copyright PrestaMonster
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

(function($){

	$.confirm = function(params){

		if($('#confirmOverlay').length)
		{
			// A confirm is already shown on the page:
			return false;
		}

		var buttonHTML = '';
		$.each(params.buttons,function(name,obj)
		{
			// Generating the markup for the buttons:
			buttonHTML += '<a href="#" class="button '+obj['class']+'">'+name+'<span></span></a>';

			if(!obj.action)
				obj.action = function(){};
		});

		var markup = [
			'<div id="confirmOverlay">',
			'<div id="confirmBox">',
			'<h1>',params.title,'</h1>',
			'<p>',params.message,'</p>',
			'<div id="confirmButtons">',
			buttonHTML,
			'</div></div></div>'
		].join('');

		$(markup).hide().appendTo('body').fadeIn();

		var buttons = $('#confirmBox .button'),	i = 0;

		$.each(params.buttons,function(name,obj)
		{
			buttons.eq(i++).click(function()
			{
				obj.action();
				$.confirm.hide();
				return false;
			});
		});
	};

	$.confirm.hide = function()
	{
		$('#confirmOverlay').fadeOut(function()
		{
			$(this).remove();
		});
	};

})(jQuery);