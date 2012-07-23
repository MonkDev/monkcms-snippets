// Turn table cellpadding into CSS
$('table').each(function(){
	var padding = parseInt($(this).attr('cellpadding'));
	$(this).removeAttr('cellpadding');
	$('th,td',this).css('padding',padding);
});