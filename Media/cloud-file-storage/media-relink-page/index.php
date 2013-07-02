<?php

	require_once($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php");

	header("Content-Type: text/plain");

	$content = file_get_contents('content.html');

	// match links from src, href, value attributes
	preg_match_all('/(?:src|href|value)\s*=\s*[\"\'](.*?)[\"\']/i',$content,$matches);

	$urls = array();
	foreach($matches[1] as $match){
		if (strpos($match,'media.monkserve.com') !== false) {
			array_push($urls, $match);
		}
	}
	$urls = array_unique($urls);

	foreach($urls as $file){

		$filename_arr = explode('.',basename($file));
		$filename = $filename_arr[0];

		$file_found = '';

		$get_file = trim(getContent(
			"media",
			"display:detail",
			"find:" . $filename,
			"show:__url__",
			"noecho",
			"nocache"
		));

		if($get_file!=''){

			$file_found = $get_file;

		}

		/*
		else {

			$search_file = trim(getContent(
				"search",
				"display:results",
				"find_module:media",
				"keywords:" . $filename,
				"howmany:1",
				"show:__url__",
				"no_show: ",
				"noecho",
				"nocache"
			));

			if($search_file!=''){
				$file_found = $search_file;
			}

		}
		*/

		$content = str_replace($file,$file_found,$content);

	}

	echo $content;

?>