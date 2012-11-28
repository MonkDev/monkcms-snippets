// Add timestamp to PDFs (helpful for recurrently updated documents)
$('#content a[href*=".pdf"]').each(function(){
	$(this).attr('href',$(this).attr('href')+'?t='+(new Date()).getTime());
});