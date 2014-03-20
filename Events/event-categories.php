<?php

// Get event categories

function event_categories($event){
	$category_links = getContent('event','display:detail','find:'.$event,'show:__categorylinks__','noecho','noedit');
	preg_match_all('/href\s?=\s?["|\'](.*?)["|\']/i',$category_links,$category_href_matches);
	$category_href_matches = $category_href_matches[1];
	$categories = array();
	foreach($category_href_matches as $category_link){
		$category_link_arr = explode('/',trim($category_link,'/'));
		$category_slug = $category_link_arr = $category_link_arr[count($category_link_arr)-1];
		array_push($categories, $category_slug);
	}
	return $categories;
}

$event_categories = event_categories($_GET['slug']);

print_r($event_categories);

?>