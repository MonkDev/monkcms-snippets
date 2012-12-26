// Expressly hide old events from page if cached
// In the output (PHP/Monklet) of the event item, add __eventend format='U'__ in a data attribute for comparison.

$(document).ready(function(){

	var now = new Date().getTime()/1000;

	$('#eventList .event').each(function(){
		var this_event = $(this);
		var event_end = this_event.data('eventend');
		if (parseInt(event_end) < parseInt(now)){
			this_event.hide();
		}
	});

});
