<?php

	/*

	OUTPUT PAGES AS CSV

	How to use:
	Upload this file to your site root, and use this URL to download a CSV:
	http://www.site.com/export-pages.php

	Notes:
	Draft pages: Drafts are not output. Private pages are listed, but no text is displayed.

	*/

	$filename = 'pages' . 'Export' . date('M') . '_' . date('d') . '_' . date('Y');

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

	// Headers
	$headers .= '"Page ID",';
	$headers .= '"Title",';
	$headers .= '"URL",';
	$headers .= '"Description",';
	$headers .= '"Keywords",';
	$headers .= '"Groups",';
	$headers .= '"Content"';
	$headers .= "\n";

	// Find page ID's
	$htaccess = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/' . '.htaccess');
	preg_match_all('/nav=p-(.*?)\&/',$htaccess,$matches);
	$pageIDs = $matches[1];

	// Process lines
	for($i=0;$i<count($pageIDs);$i++){

		$get_page = '';
		$get_page =
		getContent(
		"page",
		"find:p-" . $pageIDs[$i],
		"show:". $pageIDs[$i], // 0
		"show:~||~",
		"show:__title__", // 1
		"show:~||~",
		"show:__url__",  // 2
		"show:~||~",
		"show:__description__", // 3
		"show:~||~",
		"show:__tags__", // 4
		"show:~||~",
		"show:__groupslugs__", // 5
		"show:~||~",
		"show:__text__", // 6
		"show:~|~|~",
		"noecho"
		);

		$get_page_array = explode("~|~|~", $get_page);

		for($p=0;$p<count($get_page_array)-1;$p++){

			$page_array = explode("~||~",$get_page_array[$p]);

			$page_title 	= 	$page_array[1];
			$page_url 		= 	str_replace('//','/',$page_array[2]);
			$page_text 		= 	trim($page_array[6]);

			if (strip_tags($page_text) == 'Please log in to view this content.'){
				$page_title = 'PRIVATE';
			}

			if($page_array[1] != '') { // If a real page actually exists

				$line =
				processItem($page_array[0]) 	. "," .
				processItem($page_title) 		. "," .
				processItem($page_url)			. "," .
				processItem($page_array[3])	. "," .
				processItem($page_array[4])	. "," .
				processItem($page_array[5])	. "," .
				processItem($page_text) 		. "\n";

				$lines .= $line;

			}

		}

	}

	$lines = trim($lines,"\n");

	// Output
	echo $headers . $lines;

?>