## Monklet

```
{{
	tag="gallery"
	display="list"
	find_gallery=""
	order="creation"
	before_show_gallery="<div class='galleryMonklet'>"
	before_show_gallery="<ul class='gM-photos clearfix'>"
	show_gallery="<li class='gM-photo'><a class='slideshow' rel='gallery___galleryslug__' href='__imageurl maxWidth='850' maxHeight='850'__'><img src='__imageurl2 width='250' height='170'__' alt='__title__'/></a></li>"
	after_show_gallery="</ul>"
	after_show_gallery="</div><!-- .galleryMonklet -->"
}}
```

## Javascript (Synchronous)

```javascript
$(document).ready(function(){
	
	// Colorbox (http://www.jacklmoore.com/colorbox/)
	$('.slideshow').colorbox({
		slideshow: true,
		photo: true,
		preloading: true,
		slideshowSpeed: 5000,
		slideshowAuto: false
	});
	
});
```

## Javascript (Asynchronous)

```javascript
/*============================================
*
*               galleryMonklet()
*
*=============================================
*
* Handles colorbox for the gallery monklet.
* http://www.jacklmoore.com/colorbox/
*
* @return - null
*
*/

function galleryMonklet(){
	if( $('.galleryMonklet').length && $(window).width()>960) {
		$('<link/>', { // Load the galleryMonklet CSS file.  you can delete this portion if you are including the css elsewhere
			rel: 'stylesheet',
			type: 'text/css',
			href: '/_css/galleryMonklet.css' // Be sure to update this file path to where the css is located!
		}).appendTo('head');
		$.getScript("/_js/vendor/jquery.colorbox-min.js",function(){ // you can remove this line if you are sure that colorbox has already been loaded
			$('.slideshow').colorbox({
				maxWidth: '85%',
				maxHeight: '85%',
				slideshow: true,
				photo: true,
				preloading: true,
				slideshowSpeed: 5000,
				slideshowAuto: false
			});
		}); // you can remove this line if you are sure that colorbox has already been loaded
	}
}
```


## Monklet Call (WYSIWYG Content Editor)

```html
<div>{{the-monklet-name|find_gallery="the-gallery-name-slug"}}</div>
```
