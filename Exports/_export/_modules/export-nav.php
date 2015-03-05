<?php

	/*

	OUTPUT NAVIGATION AS CSV

	How to use:
	Upload this file to your site root, and use this URL to download a CSV:
	http://www.site.com/export-nav.php

	*/

	$filename = 'navigation' . 'Export' . date('M') . '_' . date('d') . '_' . date('Y');


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
		$out = '"' . $out . '"';
		return $out;
	}

	$domain = 'http://' . $_SERVER['HTTP_HOST'];

	// Headers
	$headers .= '"Position",';
	$headers .= '"Level",';
	$headers .= '"Label",';
	$headers .= '"Page Title",';
	$headers .= '"URL",';
	$headers .= '"New Window"';
	$headers .= "\n";


	// Process lines
	$get_nav =
	getContent(
	"navigation",
	"display:dropdown",
	// nav html is at index 0.
	"show:~||~",
	"show:__level__", // 0
	"show:~||~",
	"show:__title__", // 1
	"show:~||~",
	"show:__pagetitle__",  // 2
	"show:~||~",
	"show:__url__", // 3
	"show:~||~__ifnewwindow__true", // 4
	"show:~|~|~",
	"noecho"
	);

	$get_nav_array = explode("~|~|~", $get_nav);

	$count = '';

	for($n=0;$n<count($get_nav_array)-1;$n++){

		$count++;

		$nav_item = explode("~||~",$get_nav_array[$n]);

		$line =
		$count										. "," .
		processItem($nav_item[1]) 				. "," .
		processItem($nav_item[2]) 				. "," .
		processItem($nav_item[3]) 				. "," .
		$domain . processItem($nav_item[4]) . "," .
		processItem($nav_item[5]) 				. "\n" ;

		$lines .= $line;

	}

	$lines = trim($lines,"\n");


	// Output
	echo $headers . $lines;

?>