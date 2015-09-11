<?php

	/*

	OUTPUT PRODUCTS AS CSV

	How to use:
	Upload this file to your site root, and use this URL to download a CSV:
	http://www.site.com/export-products.php

	*/

	require('../../_inc/config.php');

	$filename = getSiteId() . '_' . 'products' . 'Export' . date('M') . '_' . date('d') . '_' . date('Y');
	$howmany = 100; // Set to number of products in the module

	// Header
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=" . $filename . ".csv");
	header("Pragma: no-cache");
	header("Expires: 0");

	// Functions
	function processItem($in){
		$out = trim($in);
		$out = str_replace('"','""',$out);
		$out = str_replace('&amp;','&',$out);
		$out = '"' . $out . '"';
		return $out;
	}

	// Headers
	$headers .= '"Code",'; 															// 0
	$headers .= '"Product",'; 													// 1
	$headers .= '"Price",'; 														// 2
	$headers .= '"Family",'; 														// 3
	$headers .= '"Image 1",'; 													// 4
	$headers .= '"Image 2",'; 													// 5
	$headers .= '"Image 3",'; 													// 6
	$headers .= "\n";

	// Lines
	$batch_length = 50;
	$batch_count = ceil($howmany / $batch_length);

	$get_products = '';
	for($i=1; $i<=$batch_count; $i++){

		$this_howmany = strval($batch_length);
		$this_offset = strval(($batch_length * ($i-1)));

		$get_products .=
		getContent(
		"product",
		"display:list",
		"order:recent",
		"howmany_product:".$this_howmany,
		"offset_product:".$this_offset,
		"show_productlist:__productcode__", 							// 0
		"show_productlist:~||~",
		"show_productlist:__producttitle__",							// 1
		"show_productlist:~||~",
		"show_productlist:__productprice__",							// 2
		"show_productlist:~||~",
		"show_productlist:__familytitle__",								// 3
		"show_productlist:~||~",
		"show_productlist:__productimageURL__",						// 4
		"show_productlist:~||~",
		"show_productlist:__productimageURL2__",					// 5
		"show_productlist:~||~",
		"show_productlist:__productimageURL3__",					// 6
		"show_productlist:~|~|~",
		"noecho"
		);

	}

	$get_products_array = explode("~|~|~", $get_products);

	for($i=0;$i<count($get_products_array)-1;$i++){

		$event_array = explode("~||~",$get_products_array[$i]);

		$line =
		processItem($event_array[0]) . "," .
		processItem($event_array[1]) . "," .
		processItem($event_array[2]) . "," .
		processItem($event_array[3]) . "," .
		processItem($event_array[4]) . "," .
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