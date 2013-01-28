// Expressly hide old events from page if stuck in cache
	// In the output (PHP/Monklet) of the event item, add __eventend format='Y-m-d'__ in a data attribute for comparison.
	var now = Math.round((new Date().getTime()) / 1000);
	$('.widget .events .event').each(function() {
		var this_event = $(this);
		if (!this_event.hasClass('recurring')) {
			var event_end = ((new Date($(this).attr('data-eventend')).getTime()) / 1000) + (60 * 60 * 24); // start date plus one day
			if (now > event_end) {
				this_event.addClass('expired').hide();
			}
		}
	});