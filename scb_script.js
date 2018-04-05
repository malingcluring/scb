$(document).ready(function() {

	// navwalker 3 lvl menus
	$('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
		event.preventDefault(); 
		event.stopPropagation(); 
		$(this).parent().siblings().removeClass('open');
		$(this).parent().toggleClass('open');
	});

		
	//add caret icon
	$('ul.dropdown-menu [data-toggle=dropdown]').append('<span class="caret"></span>');

});