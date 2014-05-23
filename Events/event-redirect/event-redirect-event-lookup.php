<?php

	/*
		REDIRECT RECURRING EVENT SEARCH RESULTS TO LATEST EVENT OCCURRENCE
		
		## Tweaked Chris' code to lookup and return exceptions also ##

		Conditions:
		1. Event detail pages are created like so: /event/date-event-slug/
		2. Search results page is: /search-results/?keywords...

		Use:
		Include at the top of the event detail page (most likely ekk_eventpage.php),
		just after the require of monkcms.php.

	*/

	function updatedEventRedirect($event_slug){

		if (strpos($_SERVER['HTTP_REFERER'],'/search-results') !== false) {

			// original event url
			$event_url_old = getContent('event','display:detail','find:'.$event_slug,'show:__url__','noecho');

			// get old event's title string
			$event_preg_match = array();
			preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $event_slug, $event_preg_match);
			$event_slug = str_replace($event_preg_match[0], '', $event_slug);
			$event_preg_match = array();
			preg_match('/[0-9]+--/', $event_slug, $event_preg_match);
			$event_slug = str_replace($event_preg_match[0], '', $event_slug);
			$event_slug = ltrim($event_slug,'-');

			// show all event IDS that match title string, including exceptions
			$event_ids = getContent('event','display:list','find:'.$event_slug,'show:__id__||','noecho','nocache');
			$eid = explode("||", substr($event_ids,0,-2));
			foreach ($eid as $key => $event_id) {
				if ($event_id != "") {
					// build array of each specific event with find_id
					$event_info = getContent('event',
						'display:list',
						//'enablepast:no',
						//'startdate:'.$today,
						'find_id:' . $event_id,
						"show:__eventstart format='Ymd'__|__url__",
						'noecho');
					if ($event_info) $event_info_array[] = explode("|", $event_info);
				}
			}
			
			// sort array to order by date
			asort($event_info_array);
			
			//re-key array
			$event_info_array = array_values($event_info_array);
			
			// get updated event's URL
			$event_url_new = $event_info_array[0][1];

			if(($event_url_old != '') && ($event_url_new != '') && ($event_url_new != $event_url_old)){
			    $event_url_new = 'http://' . $_SERVER['HTTP_HOST'] . $event_info_array[0][1];
				header('Location:' . $event_url_new);
			}

		}

	}

	updatedEventRedirect($_GET['slug']);

?>
