// CONSOLIDATE MONTHLY ARCHIVE LINKS
//
// Wraps monthly archive links (January 2011, Februrary 2011, March 2011...)
// output by MonkCMS into DIVs that can be hidden/shown with jQuery slideToggle().
// Example: http://www.shorelifecc.org/messages/

$(document).ready(function() {

	var first_archive_array = $('ul#blog_list_archive li:last').text().split(' ');
	var last_archive_array = $('ul#blog_list_archive li:first').text().split(' ');
	var first_year = first_archive_array[1];
	var last_year = last_archive_array[1];
	var year_array = [];
	for (y = 0; y <= (last_year - first_year); y++) {
		var year_item = parseInt(first_year) + parseInt(y);
		year_array.push(year_item.toString());
	}
	$.each(year_array, function(i, val) {
		$('ul#blog_list_archive li:contains(' + val + ')')
		.wrapAll('<div class="archive-group" data-rel="' + val + '"/>')
		.wrapAll('<div class="archive-group-list" style="display:none;"/>');
	});
	$('ul#blog_list_archive .archive-group').each(function() {
		var year = $(this).attr('data-rel');
		$(this).prepend('<li class="group-toggle"><a href="#">' + year + '</a></li>');
	});
	$('li.group-toggle').click(function(e) {
		e.preventDefault();
		$(this).next().slideToggle('fast');
		$(this).toggleClass('current');
	});

});