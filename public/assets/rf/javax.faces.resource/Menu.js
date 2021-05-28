var Menu = {
	'selectItem': function (item) {
		Menu.deselectAll();
		$('#menu_' + item).addClass('itemActive');
	},
	
	'deselectAll': function() {
		$('.itemActive').each(function (i, element) {
			$(element).removeClass('itemActive');
			$(element).addClass('item');
		});		
	}
};