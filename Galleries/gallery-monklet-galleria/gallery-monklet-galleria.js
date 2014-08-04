
/*
	
	DOWNLOAD GALLERIA:
	http://galleria.io/static/galleria-1.3.6.zip
	
	
*/

    $(document).ready(function() {
	    
	    
    	if ($('.gallery-items').length) {
	    	
    		Galleria.loadTheme("/_js/galleria/themes/classic/galleria.classic.js");
    		
    		$('.gallery-items').galleria({
	    		
    			debug: false,
    			transition: 'fade',
    			width: 900,
    			height: 500,
    			transition_speed: 700,
    			thumb_crop: false,
    			image_crop: false,
    			show_imagenav: false, // remove the prev/next arrows
    			_toggleInfo: false, // prevent info toggle (always show the caption)
    			
    			extend: function() {
    				var gallery = this; // save the scope
    				$('#album-nav a').click(function(e) {
    					e.preventDefault(); // prevent default actions on the links
    				});
    				// attach gallery methods to links:
    				$('#g_prev').click(function(e) {
    					e.preventDefault();
    					gallery.prev();
    				});
    				$('#g_next').click(function(e) {
    					e.preventDefault();
    					gallery.next();
    				});
    				$('#g_play').click(function(e) {
    					e.preventDefault();
    					gallery.play();
    				});
    				$('#g_pause').click(function(e) {
    					e.preventDefault();
    					gallery.pause();
    				});
    				$('#g_fullscreen').click(function(e) {
    					e.preventDefault();
    					gallery.enterFullscreen();
    				});
    				
    				// Fullscreen is good to go now, with some callbacks and CSS adjustments!
    				gallery.bind('fullscreen_enter', function() {
    					$('#content-wrap').css({
    						'position': 'relative',
    						'z-index': 100
    					});
    				});
    				
    				gallery.bind('fullscreen_exit', function() {
    					$('#content-wrap').css({
    						'position': 'relative',
    						'z-index': ''
    					});
    				});
    			},
    			
    			dataConfig: function(img) {
    				return {
    					title: $(img).attr('title'),
    					description: $(img).attr('rel')
    				};
    			}
    			
    		});
    		
    		$(".gallery .thmb img").hover(
    		function() {
    			$(this).fadeTo("fast", 0.9);
    		}, function() {
    			$(this).fadeTo("fast", 1);
    		});
    		
    	}
    	
    	
    });