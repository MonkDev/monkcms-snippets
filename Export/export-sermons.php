<?php

	/*

	OUTPUT SERMONS AS CSV

	How to use:
	Upload this file to your site root, and use this URL to download a CSV:
	http://www.site.com/export-sermons.php

	*/


	$filename = 'sermons' . 'Export' . date('M') . '_' . date('d') . '_' . date('Y');
	$howmany = 2500; // Set to number of sermons in the module


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
	$headers .= '"Sermon ID",';
	$headers .= '"Title",';
	$headers .= '"Date",';
	$headers .= '"Category",';
	$headers .= '"Series",';
	$headers .= '"Series Description",';
	$headers .= '"Series Image",';
	$headers .= '"Preacher",';
	$headers .= '"Passage",';
	$headers .= '"Summary",';
	$headers .= '"Keywords",';
	$headers .= '"Content",';
	$headers .= '"Audio",';
	$headers .= '"Video",';
	$headers .= '"Image",';
	$headers .= '"Notes",';
	$headers .= '"Featured"';
	$headers .= "\n";


	// Lines
	$batch_length = 100;
	$batch_count = ceil($howmany / $batch_length);

	$get_sermons = '';
	for($i=1; $i<=$batch_count; $i++){

		$this_howmany = strval($batch_length);
		$this_offset = strval(($batch_length * ($i-1)));

		$get_sermons .=
		getContent(
		"sermon",
		"display:list",
		"howmany:".$this_howmany,
		"offset:".$this_offset,
		"order:recent",
		"show:__id__", // 0
		"show:~||~",
		"show:__title__", // 1
		"show:~||~",
		"show:__date format='Y-m-d'__", // 2
		"show:~||~",
		"show:__category__", // 3
		"show:~||~",
		"show:__series__", // 4
		"show:~||~",
		"show:__seriesdescription__", // 5
		"show:~||~",
		"show:__seriesimage__", // 6
		"show:~||~",
		"show:__preacher__", // 7
		"show:~||~",
		"show:__passagebook__ __passageverse__", // 8
		"show:~||~",
		"show:__summary__", // 9
		"show:~||~",
		"show:__tags__", // 10
		"show:~||~",
		"show:__text__", // 11
		"show:~||~",
		"show:__audiourl__", // 12
		"show:~||~",
		"show:__videourl__", // 13
		"show:__videoembed__",
		"show:~||~",
		"show:__imageurl__", // 14
		"show:~||~",
		"show:__notes__", // 15
		"show:~||~",
		"show:__featured__", // 16
		"show:~|~|~",
		"noecho"
		);

	}

	$get_sermons_array = explode("~|~|~", $get_sermons);

	for($i=0;$i<count($get_sermons_array)-1;$i++){

		$sermon_array = explode("~||~",$get_sermons_array[$i]);

		$line =
		processItem($sermon_array[0]) . "," .
		processItem($sermon_array[1]) . "," .
		processItem($sermon_array[2]) . "," .
		processItem($sermon_array[3]) . "," .
		processItem($sermon_array[4]) . "," .
		processItem($sermon_array[5]) . "," .
		processItem($sermon_array[6]) . "," .
		processItem($sermon_array[7]) . "," .
		processItem($sermon_array[8]) . "," .
		processItem($sermon_array[9]) . "," .
		processItem($sermon_array[10]) . "," .
		processItem($sermon_array[11]) . "," .
		processItem($sermon_array[12]) . "," .
		processItem($sermon_array[13]) . "," .
		processItem($sermon_array[14]) . "," .
		processItem($sermon_array[15]) . "," .
		processItem($sermon_array[16]) . "\n" ;

		$lines .= $line;
	}

	$lines = trim($lines,"\n");


	// Output
	echo $headers . $lines;

?>