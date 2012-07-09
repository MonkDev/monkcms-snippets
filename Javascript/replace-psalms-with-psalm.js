// Replace "Psalms" with "Psalm"
$('.yourDiv').each(function(){

	var html = $(this).html();
	if((html.toLowerCase()).indexOf('psalms') != -1 ){
		var html_replace = html.replace(/psalms/gi,'Psalm');
		$(this).html(html_replace);
	}
	
});