<?php

	/*

	OUTPUT PRODUCTS AS CSV

	How to use:
	Upload this file to your site root, and use this URL to download a CSV:
	http://www.site.com/export-products.php

	*/

	require('../../_inc/config.php');

	set_time_limit(0);
	
	// Functions
	function processItem($in)
	{
		$out = trim($in);
		$out = str_replace('"','""',$out);
		$out = str_replace('&amp;','&',$out);
		$out = '"' . $out . '"';

		return $out;
	}

	$filename = getSiteId() . '_' . 'products' . 'Export' . date('M') . '_' . date('d') . '_' . date('Y');
	$howmany = 10000; // Set to number of products in the module

	// Header
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=" . $filename . ".csv");
	header("Pragma: no-cache");
	header("Expires: 0");

	// Headers
	$headers = '';
	$headers .= '"Product Code",';
	$headers .= '"Product Name",';
	$headers .= '"Author",';
	$headers .= '"Current Price",';
	$headers .= '"Original Price",';
	$headers .= '"Type",';
	$headers .= '"Publisher",';
	$headers .= '"Reference",';
	$headers .= '"Program Affiliation",';
	$headers .= '"Description",';
	$headers .= '"Image1",';
	$headers .= '"Image2",';
	$headers .= '"Image3"';
	$headers .= "\n";

	// Lines
	$batch_length = 100;
	$batch_count = ceil($howmany / $batch_length);

	$get_products = '';

	for ($i=1; $i<=$batch_count; $i++) {

		$this_howmany = strval($batch_length);
		$this_offset = strval(($batch_length * ($i-1)));

		$get_products .=
		getContent(
			"products",
			"display:list",
			'order:title',
			'product:all',
			"howmany_product:" . $this_howmany,
			"offset_product:" . $this_offset,
			'show_productlist:__productcode__',
			'show_productlist:~||~',
			'show_productlist:__producttitle__',
			'show_productlist:~||~',
			'show_productlist:__skuauthor__',
			'show_productlist:~||~',
			'show_productlist:__productprice__',
			'show_productlist:~||~',
			'show_productlist:__productOriginalprice__',
			'show_productlist:~||~',
			'show_productlist:__type__',
			'show_productlist:~||~',
			'show_productlist:__publisher__',
			'show_productlist:~||~',
			'show_productlist:__reference__',
			'show_productlist:~||~',
			'show_productlist:__affiliation__',
			'show_productlist:~||~',
			'show_productlist:__productdescription__',
			'show_productlist:~||~',
			'show_productlist:__productimageURL__',
			'show_productlist:~||~',
			'show_productlist:__productimageURL2__',
			'show_productlist:~||~',
			'show_productlist:__productimageURL3__',
			'show_productlist:~|~|~',
			'noecho'
		);
	}

	$get_products_array = explode("~|~|~", $get_products);

	$lines = '';

	for ($i=0;$i<count($get_products_array)-1;$i++) {

		$product_array = explode("~||~",$get_products_array[$i]);

		$line =
		processItem($product_array[0]) . "," .
		processItem($product_array[1]) . "," .
		processItem($product_array[2]) . "," .
		processItem($product_array[3]) . "," .
		processItem($product_array[4]) . "," .
		processItem($product_array[5]) . "," .
		processItem($product_array[6]) . "," .
		processItem($product_array[7]) . "," .
		processItem($product_array[8]) . "," .
		processItem($product_array[9]) . "," .
		processItem($product_array[10]) . "," .
		processItem($product_array[11]) . "," .
		processItem($product_array[12]) . "\n";

		$lines .= $line;
	}

	$lines = trim($lines, "\n");

	// Output
	echo $headers . $lines;