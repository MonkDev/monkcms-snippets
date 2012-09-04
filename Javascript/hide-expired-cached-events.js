// Expressly hide old events from page if stuck in cache
// In the output (PHP/Monklet) of the event item, add __eventend format='Y-m-d'__ in a data attribute for comparison.

var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1;
var yyyy = today.getFullYear();
if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm}
var today = yyyy + '-' + mm + '-' + dd ;
$('#eventList .event').each(function(){
	var this_event = $(this);
	var event_end = $(this).attr('data-eventend');
	if (new Date(event_end) < new Date(today)){
		this_event.hide();
	}
});