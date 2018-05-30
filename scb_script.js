$(document).ready(function() {
	
	$('p').each(function() {
		var $this = $(this);
		if($this.html().replace(/\s|&nbsp;/g, '').length == 0) {
			$this.remove();
		}
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
    $("a[href$='.jpg'], a[href$='.png'], a[href$='.jpeg'], a[href$='.gif']").fancybox();
 
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
			$('img').removeAttr('width height sizes class alt srcset');
			$('img').css('border', 'none');
		}
		
	});
	
	$('#custom_video_testimony p>a').each(function(){
		$this = $(this);
		$this.attr({
			'data-fancybox' : 'gallery',
			'class' : 'video fancybox.iframe',
			'data-width' : '800',
			'data-height' : '600'
		});
		$this.find('img').addClass('video-thumb');
	});
	
	
	// GOGGLE MAPS CONTACT US PAGE
	var $maps = $('iframe#google_map').detach();
	$('.maps').each(function(){
		$(this).append($maps);
	});
	
	
	// NEWS MAIN IMAGE (PAGE-NEWS)
	snapToTop();
	$(window).resize(function() {
		snapToTop();
	});
	
	// NEWS MAIN IMAGE
	if($('.news-main-image').children().length == 0) {
		$('.news-main-image').remove();
	}
	
	
	// CLIENT INTRO (PAGE OUR CLIENTS)
	var $client_intro = $('.page-our-clients #client_intro').detach();
	$intro = 
	$('.page-our-clients').each(function() {
		$this = $(this);
		$('#intro').append($client_intro);
		$('#intro .row').remove();
		$('#client_intro h3').remove();
	});
	
	// CAROUSEL PLUGIN
	// CAROUSEL CONTROL click for CAROUSEL INNER random color background
	//random color
	var safeColors = ['00','33','66','99','cc','ff'];
	var rand = function() {
		return Math.floor(Math.random()*6);
	};
	var randomColor = function() {
		var r = safeColors[rand()];
		var g = safeColors[rand()];
		var b = safeColors[rand()];
		return "#"+r+g+b;
	};
	$('.page-home .carousel[id*="cptbc"] .carousel-control').click(function() {
		$('.page-home .carousel[id*="cptbc"] .carousel-inner').each(function() {
			$(this).css('background-color', randomColor());
		});
	});
	
	// CORPORATE CULTURE PAGE
	var $wrap = '<div class="scb-carousel"></div>';
	$('.page-service-culture .carousel').wrap($wrap);
	// END OF CAROUSEL PLUGIN
	
	// WRAPPING TITLE LAST WORD USING <SPAN>
	$('h1.the-title').html(function(){	
		var text= $(this).text().split(' ');
		var last = text.pop();
		return text.join(" ") + (text.length > 0 ? ' <span class="last">'+last+'</span>' : last);   
	});
	
	// PAGE SCHEDULE	
	$('.page-schedule .entry-content').each(function() {
		$this = $(this);
		
		//----------------------
		$('.card-item .tagline').remove();
		$('.card-item .description').remove();
		$('.card-item .socials').remove();
		$('.card-item .venue-name').remove();
		$('.card-item .event-url').remove();
	});
	
	// GALLERY
	$('#image_gallery').find('br[style]').remove();
	
	
	
	
	// VIDEO LIGHTBOX
	$('.video-testimony > a').each(function(){
		$this = $(this);
		$wrap = '<div class="lightbox-gallery"></div>';
		$this.wrap($wrap);
		$this.attr({
			'data-fancybox' : 'gallery',
			'class' : 'video fancybox.iframe',
			'data-width' : '800',
			'data-height' : '600'
		});
		$this.find('img').addClass('video-thumb');
	});
	
	// PAGE OUTBOUND
	var $outbound_wrap = '<div id="outbound" class="col-sm-12"></div>';
	$('.page-outbound .entry-content').each(function() {
		$this = $(this);
		$this.prepend($outbound_wrap);
		$outbound = $('#outbound').detach();
		$outbound.insertAfter('#intro');
		$outbound_card = $('.outbound-box').detach();
		$outbound_card.appendTo($outbound);
	});
	
	
	
});


// ============================================================================================ STATIC FUNCTION
function snapToTop() {
	$navHeight = $('.main-menu').height();
	$('.news-main-image').css('margin-top', - $navHeight);
	$('.page-home .carousel[id*="cptbc"]').css('top', - $navHeight);
	$('.page-home .carousel[id*="cptbc"]').css('margin-bottom', - $navHeight);
}


