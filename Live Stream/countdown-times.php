<?php

require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php");
header('Content-type: application/json');

$event_array;
$nodes;
$json;

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
"find_category:live-service",
"startdate:" . $yesterday,
"howmany:5",
"show:__title__", //0
"show:||",
"show:__eventstart format='F j, Y G:i:s'__", // 1 - start time
"show:||",
"show:__eventstartTwo format='F j, Y'__ __eventend format='G:i:s'__", // 2 - end time
"show:~~~",
"noecho"
);

// Process data string for current events
$event_prearray = explode("~~~",$event_string);
for ($i=0; $i<count($event_prearray)-1; $i++) {
	$item_arr = explode("||",$event_prearray[$i]);
	$event_end = $item_arr[2];
	if($now<strtotime($event_end)){
		$event_array[$i] = $item_arr;
	}
}

// Build JSON with time zone via DateTime / DateTimeZone classes.
$i = 0;
foreach ($event_array as $key => $value) {
  $title = $value[0];
  $start_time = $value[1];
  $end_time = $value[2];
  $dateTime_start = new DateTime($start_time, new DateTimeZone($timezone));
  $dateTime_end = new DateTime($end_time, new DateTimeZone($timezone));
  $nodes[$i] = array(
		id => "id".strval($i),
		title => $title,
		date => array(
		   start => $dateTime_start->format('F j, Y G:i:s O'),
		   end => $dateTime_end->format('F j, Y G:i:s O')
		),
    );
  $i++;
}

//print_r($nodes); // testing

$json = json_encode($nodes);
echo $json;

?>