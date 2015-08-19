<?php

	/*
		REDIRECT RECURRING EVENT SEARCH RESULT TO LATEST OCCURRENCE

		Requirements:
		1. Event detail pages are created like so: /event/date-event-slug/
		2. Search results page is: /search-results/?keywords...

		To use:
		Include at the top of the event detail page (most likely ekk_eventpage.php),
		just after the require of monkcms.php. Verify that headers are not already
		being sent by an earlier script including blank space, etc.

		Note:
		May not return the correct event if the "next" event is an exception to
		the recurrence rule.

	*/

	function updatedEventRedirect($event_slug){

		//if (strpos(rtrim($_SERVER['HTTP_REFERER'],'/'),'/search-results') !== false) {

			// query for new event
			$event_slug_new = getContent(
			  'event',
			  'display:detail',
			  'find:' . preg_replace('/(\d{1,10}-)?(\d{4}-\d{2}-\d{2}-)?/', '', $event_slug),
			  'show:__url__',
			  'noecho',
			  'noedit'
      );
      $event_slug_new = preg_replace('/^event\//', '', trim($event_slug_new, '/'));

			// if new event is different, redirect to new URL
			if(($event_slug && $event_slug_new) && ($event_slug != $event_slug_new)){
				header('Location:' . 'http://' . $_SERVER['HTTP_HOST'] . '/event/' . $event_slug_new . '/');
			}

		}

	//}

	updatedEventRedirect($_GET['slug']);

?>