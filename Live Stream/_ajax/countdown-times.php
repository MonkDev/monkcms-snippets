<?php

require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php");
header('Content-type: application/json');

// Set PHP to client time zone
$timezone = getContent('site','display:detail','show:__timezone__','noecho');
date_default_timezone_set($timezone);

// Define times
$now = strtotime('now');
$yesterday = date('m/d/Y',strtotime('-1 day',$now));


// Get event data
$event_string =
getContent(
    "event",
    "display:list",
    "enablepast:yes",
    "recurring:yes",
    "repeatevent:yes",
    "find_category:live-event",
    "startdate:" . $yesterday,
    "howmany:15",
    "show:__title__",
    "show:||",
    "show:__eventstart format='F j, Y G:i:s'__", // 1 - start time
    "show:||",
    "show:__eventstartTwo format='F j, Y'__ __eventend format='G:i:s'__", // 2 - end time
    "show:~~~",
    "noecho"
);

// Process data string for current events
$event_prearray = explode("~~~", trim($event_string, "~~~"));

for ($i=0; $i<count($event_prearray); $i++) {

	$item_arr = explode("||",$event_prearray[$i]);
	$event_end = $item_arr[2];

    //include only events that have not yet ended
	if($now<strtotime($event_end)){
		$event_array[] = $item_arr;
	}

}

// Prepare JSON output
for ($i=0; $i<count($event_array); $i++) {

    $title = $event_array[$i][0];

    $dateTime_start = new DateTime($event_array[$i][1], new DateTimeZone($timezone));
    $dateTime_end = new DateTime($event_array[$i][2], new DateTimeZone($timezone));    

    $nodes[$i] = array(
    	id => "id".strval($i),
    	title => $title,
    	date => array(
    	    start => $dateTime_start->format('F j, Y G:i:s O'),
    	    end => $dateTime_end->format('F j, Y G:i:s O')
    	),
    );

}

$json = json_encode($nodes);
echo $json;

?>