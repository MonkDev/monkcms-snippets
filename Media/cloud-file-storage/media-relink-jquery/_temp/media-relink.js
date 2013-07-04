$(document).ready(function() {

	// select media dir and monkserve links
	$('a[href*="mediafiles"],img[src*="mediafiles"],a[href*="media.monkserve.com"],img[src*="media.monkserve.com"]').each(function() {

		var item = $(this);
		var href = item.attr('href');
		var src = item.attr('src');

		if(href!==undefined){
			//console.log('broken anchor HREF found');
			if ((href.indexOf("/uploaded/") <= 0) && (href.indexOf(".xml") <= 0)) {
				var filename = href.split('/')[href.split('/').length - 1];
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
			//console.log('broken image SRC found');
			if (src.indexOf("/uploaded/") <= 0) {
				var filename = src.split('/')[src.split('/').length - 1];
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