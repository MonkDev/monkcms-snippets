$(document).ready(function() {

	// select media dir and monkserve links
	$('
		a[href*="mediafiles"],
		img[src*="mediafiles"],

		a[href*="media.monkserve.com"],
		img[src*="media.monkserve.com"]

	').each(function() {

		var item = $(this);
		var href = item.attr('href');
		var src = item.attr('src');

		if(href!==undefined){
			if ((href.indexOf("/uploaded/") <= 0) && (href.indexOf(".xml") <= 0)) {
				var filename = href.split('/')[href.split('/').length - 1];
				filename = filename.split('.')[0];
				$.ajax({
					type: 'GET',
					url: '/_temp/ajax-find-media.php?filename=' + filename,
					success: function(url) {
						if (url != '') {
							item.attr('href', url);
						}
					}
				});
			}
		}

		if(src!==undefined){
			if (src.indexOf("/uploaded/") <= 0) {
				var filename = src.split('/')[src.split('/').length - 1];
				filename = filename.split('.')[0];
				$.ajax({
					type: 'GET',
					url: '/_temp/ajax-find-media.php?filename=' + filename,
					success: function(url) {
						if (url != '') {
							item.attr('src', url);
						}
					}
				});
			}
		}

	}); // each()

});