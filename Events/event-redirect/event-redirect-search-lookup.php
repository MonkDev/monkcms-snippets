<?php

	/*
		REDIRECT RECURRING EVENT SEARCH RESULT TO LATEST OCCURRENCE
		https://github.com/MonkDev/monkcms-snippets
	*/

	function updatedEventRedirect($event_slug){

		// disable this to apply function to all event URLs, even those with dates
		if(preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/',$event_slug)){
			return;
		}

		// set to client's time zone
		$timezone = getContent('site','display:detail','show:__timezone__','noecho','noedit');
		date_default_timezone_set($timezone);

		// store the original event slug
		$event_slug_orig = $event_slug;

		// get just the event's name
		$event_name = preg_replace('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', '', $event_slug);
		$event_name = preg_replace('/[0-9]+--/', '', $event_name);
		$event_name = ltrim($event_name,'-');

		// search for other events by this name
		$event_match = '';
		$event_search = trim(getContent(
		'search',
		'display:results',
		'find_module:events',
		'howmany:10',
		'keywords:' . $event_name,
		'show:__url__|~',
		'no_show: ',
		'noecho',
		'noedit'
		));
		$event_results = explode('|~',trim($event_search,'|~'));
		foreach($event_results as $key => $event_result){
			$event_results[$key] = str_replace('event/','',$event_results[$key]);
			$event_results[$key] = trim($event_results[$key],'/');
		}

		// sort the search results by earliest first
		natcasesort($event_results);

		// choose the first event that 1) is not passed and 2) matches this name
		foreach($event_results as $event_result){
			$event_date_pattern = '/[0-9]{4}-[0-9]{2}-[0-9]{2}/';
			preg_match($event_date_pattern, $event_result, $event_date_matches);
			$event_date = $event_date_matches[0];
			if(strtotime($event_date) < strtotime(date('Y-m-d'))){
				continue;
			} else {
				$event_result_name = ltrim(preg_replace($event_date_pattern, '', $event_result), '-');
				if($event_result_name == $event_name){
					$event_match = $event_result;
					break;
				}
			}
		}

		// if a match was found, redirect to the new event
		if($event_match){
			$event_slug_new = $event_match;
			if(($event_slug_orig != '') && ($event_slug_new != '') && ($event_slug_orig != $event_slug_new)){
				$event_url_new = 'http://' . $_SERVER['HTTP_HOST'] . '/event/' . $event_slug_new;
				header('Location:' . $event_url_new);
			}
		}

	}

	updatedEventRedirect($_GET['slug']);

?>