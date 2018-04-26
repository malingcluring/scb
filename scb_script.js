$(document).ready(function() {
	
	$('p').each(function() {
		var $this = $(this);
		if($this.html().replace(/\s|&nbsp;/g, '').length == 0)
			$this.remove();
	});
	
	var $container = '<div class="container"><div class="row"></div></div>';
	if(!$('body').hasClass('page-home')) {
		$('.entry-content').wrap($container);
	}

	// navwalker 3 lvl menus
	$('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
		event.preventDefault(); 
		event.stopPropagation(); 
		$(this).parent().siblings().removeClass('open');
		$(this).parent().toggleClass('open');
	});

		
	//add caret icon
	$('ul.dropdown-menu [data-toggle=dropdown]').append('<span class="caret"></span>');
	
	
	
	// lightbox initials
	// Initialize the Lightbox for any links with the 'fancybox' class
    $(".fancybox").fancybox();
 
    // Initialize the Lightbox automatically for any links to images with extensions .jpg, .jpeg, .png or .gif
    $("a[href$='.jpg'], a[href$='.png'], a[href$='.jpeg'], a[href$='.gif']").fancybox({
			maxWidth        : 800,
			maxHeight       : 600,
			fitToView       : false,
			width           : '70%',
			height          : '70%',
			autoSize        : false,
			closeClick      : false,
			openEffect      : 'none',
			closeEffect     : 'none'
		});
 
    // Initialize the Lightbox and add rel="gallery" to all gallery images when the gallery is set up using [gallery link="file"] so that a Lightbox Gallery exists
    $(".gallery a[href$='.jpg'], .gallery a[href$='.png'], .gallery a[href$='.jpeg'], .gallery a[href$='.gif']").attr('rel','gallery').fancybox();
 
    // Initalize the Lightbox for any links with the 'video' class and provide improved video embed support
    $(".video").fancybox({
        maxWidth        : 800,
        maxHeight       : 600,
        fitToView       : false,
        width           : '70%',
        height          : '70%',
        autoSize        : false,
        closeClick      : false,
        openEffect      : 'none',
        closeEffect     : 'none'
    });
	
	//$('#fancybox-content').css('filter', 0);
	//$('#fancybox-wrap').css('filter', 0);
	
	
	$('.gallery').each(function(){
		$(this).removeAttr('id');
		if($(this).find('img')){
			$('img').removeAttr('width height sizes class alt');
			$('img').css('border', 'none');
		}
		
	});

});