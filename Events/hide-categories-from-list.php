<?php
	
	// The "display:categories" cannot currently use hide_category.
	// Use this function instead to remove categories from your list.
	
	require_once($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php");
	
	$hidden_event_categories = array('small-group-finder','topical');

	$event_categories =
	trim(getContent(
	"event",
	"display:categories",
	"level1:__slug__~~__name__||",
	"level2:__slug__~~__name__||",
	"level3:__slug__~~__name__||",
	"noecho"
	),'||');
	$event_categories_arr = explode('||',$event_categories);

	$event_categories_filtered = array();
	foreach($event_categories_arr as $event_category){
		$event_category_arr = explode('~~',$event_category);
		if(!in_array($event_category_arr[0], $hidden_event_categories)){
			array_push($event_categories_filtered,$event_category);
		}
	}

	foreach($event_categories_filtered as $event_category){
		$event_category_arr = explode('~~',$event_category);
		echo '<option value="'.$event_category_arr[0].'">'.$event_category_arr[1].'</option>' . "\n";
	}

?>