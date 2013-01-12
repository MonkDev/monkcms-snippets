<?php

	/*

	OUTPUT EVENTS AS CSV

	How to use:
	Upload this file to your site root, and use this URL to download a CSV:
	http://www.site.com/export-events.php

	*/


	$filename = "events-export";
	$howmany = 300; // Set to number of events in the module


	// Header
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=" . $filename . ".csv");
	header("Pragma: no-cache");
	header("Expires: 0");


	// MonkCMS
	require($_SERVER['DOCUMENT_ROOT'] . '/monkcms.php');


	// Functions
	function processItem($in){
		$out = trim($in);
		$out = str_replace('"','""',$out);
		$out = str_replace('&amp;','&',$out);
		$out = '"' . $out . '"';
		return $out;
	}


	// Headers
	$headers .= '"ID",'; 					// 0
	$headers .= '"Title",'; 				// 1
	$headers .= '"Start Time",'; 			// 2
	$headers .= '"End Time",'; 				// 3
	$headers .= '"Smart Time",'; 			// 4
	$headers .= '"Recurring",'; 			// 5
	$headers .= '"Summary",'; 				// 6
	$headers .= '"Description",'; 			// 7
	$headers .= '"Image",'; 				// 8
	$headers .= '"Category",';				// 9
	$headers .= '"Group",';					// 10
	$headers .= '"Cost",';					// 11
	$headers .= '"Website",';				// 12
	$headers .= '"Coordinator Name",';		// 13
	$headers .= '"Coordinator Email",';		// 14
	$headers .= '"Coordinator Phone",';		// 15
	$headers .= '"Location Name",';			// 16
	$headers .= '"Address",';				// 17
	$headers .= '"Longitude",';				// 18
	$headers .= '"Latitude",';				// 19
	$headers .= '"Google Map",';			// 20
	$headers .= '"Location Website",';		// 21
	$headers .= '"Location Email",';		// 22
	$headers .= '"Location Phone",';		// 23
	$headers .= '"Location Description",';	// 24
	$headers .= '"Location Category",';		// 25
	$headers .= '"Location Image",';		// 26
	$headers .= '"Location Postal",';		// 27
	$headers .= '"Location Group",';		// 28
	$headers .= '"Featured"';				// 29
	$headers .= "\n";

	// Lines
	$batch_length = 50;
	$batch_count = ceil($howmany / $batch_length);

	$get_events = '';
	for($i=1; $i<=$batch_count; $i++){

		$this_howmany = strval($batch_length);
		$this_offset = strval(($batch_length * ($i-1)));

		$get_events .=
		getContent(
		"event",
		"display:list",
		"order:recent",
		"enablepast:yes",
		"repeatevent:no",
		"emailencode:no",
		"howmany:".$this_howmany,
		"offset:".$this_offset,
		"show:__id__", // 0
		"show:~||~",
		"show:__title__",
		"show:~||~",
		"show:__eventstart__",
		"show:~||~",
		"show:__eventend__",
		"show:~||~",
		"show:__eventtimes__",
		"show:~||~",
		"show:__isrecurring__true",
		"show:~||~",
		"show:__summary__",
		"show:~||~",
		"show:__description__",
		"show:~||~",
		"show:__imageurl__",
		"show:~||~",
		"show:__category__",
		"show:~||~",
		"show:__group__",
		"show:~||~",
		"show:__cost__",
		"show:~||~",
		"show:__website__",
		"show:~||~",
		"show:__coordname__",
		"show:~||~",
		"show:__coordemail__",
		"show:~||~",
		"show:__coordphone__,",
		"show: __coordcellphone__,",
		"show: __coordworkphone__",
		"show:~||~",
		"show:__location__",
		"show:~||~",
		"show:__fulladdress__",
		"show:~||~",
		"show:__longitude__",
		"show:~||~",
		"show:__latitude__",
		"show:~||~",
		"show:__googlemap__",
		"show:~||~",
		"show:__locationwebsite__",
		"show:~||~",
		"show:__locationemail__",
		"show:~||~",
		"show:__locationphone__",
		"show:~||~",
		"show:__locationdescription__",
		"show:~||~",
		"show:__locationcategory__",
		"show:~||~",
		"show:__locationimageurl__",
		"show:~||~",
		"show:__locationpostal__",
		"show:~||~",
		"show:__locationgroup__",
		"show:~||~",
		"show:__featured__",
		"show:~|~|~",
		"noecho"
		);

	}

	$get_events_array = explode("~|~|~", $get_events);

	for($i=0;$i<count($get_events_array)-1;$i++){

		$event_array = explode("~||~",$get_events_array[$i]);

		$line =
		processItem($event_array[0]) . "," .
		processItem($event_array[1]) . "," .
		processItem($event_array[2]) . "," .
		processItem($event_array[3]) . "," .
		processItem(strip_tags($event_array[4])) . "," .
		processItem($event_array[5]) . "," .
		processItem($event_array[6]) . "," .
		processItem($event_array[7]) . "," .
		processItem($event_array[8]) . "," .
		processItem($event_array[9]) . "," .
		processItem($event_array[10]) . "," .
		processItem($event_array[11]) . "," .
		processItem($event_array[12]) . "," .
		processItem($event_array[13]) . "," .
		processItem($event_array[14]) . "," .
		processItem($event_array[15]) . "," .
		processItem($event_array[16]) . "," .
		processItem($event_array[17]) . "," .
		processItem($event_array[18]) . "," .
		processItem($event_array[19]) . "," .
		processItem($event_array[20]) . "," .
		processItem($event_array[21]) . "," .
		processItem($event_array[22]) . "," .
		processItem($event_array[23]) . "," .
		processItem($event_array[24]) . "," .
		processItem($event_array[25]) . "," .
		processItem($event_array[26]) . "," .
		processItem($event_array[27]) . "," .
		processItem($event_array[28]) . "," .
		processItem($event_array[29]) . "\n" ;

		$lines .= $line;
	}

	$lines = trim($lines,"\n");


	// Output
	echo $headers . $lines;

?>