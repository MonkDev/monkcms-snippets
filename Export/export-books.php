<?php

	/*

	OUTPUT BOOKS AS CSV

	How to use:
	Upload this file to your site root, and use this URL to download a CSV:
	http://www.site.com/export-books.php

	*/


	$filename = 'books' . 'Export' . date('M') . '_' . date('d') . '_' . date('Y');
	$howmany = 107; // Set to number of books in the module


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
	$headers .= '"Title",';
	$headers .= '"Author",';
	$headers .= '"Category",';
	$headers .= '"Image",';
	$headers .= '"Description",';
	$headers .= '"Affiliate",';
	$headers .= '"Keywords"';
	$headers .= "\n";


	// Lines
	$batch_length = 50;
	$batch_count = ceil($howmany / $batch_length);

	$get_books = '';
	for($i=1; $i<=$batch_count; $i++){

		$this_howmany = strval($batch_length);
		$this_offset = strval(($batch_length * ($i-1)));

		$get_books .=
		getContent(
		"books",
		"display:list",
		"find_booklist:all",
		"order:recent",
		"howmany:".$this_howmany,
		"offset:".$this_offset,
		"show_books:__booktitle__", // 0
		"show_books:~||~",
		"show_books:__bookauthor__", // 1
		"show_books:~||~",
		"show_books:__bookcategory__", // 2
		"show_books:~||~",
		"show_books:__imageurl__", // 3
		"show_books:~||~",
		"show_books:__booktext__", // 4
		"show_books:~||~",
		"show_books:__bookaffiliate__", // 5
		"show_books:~||~",
		"show_books:__bookkeywords__", // 6
		"show_books:~|~|~",
		"noecho"
		);

	}

	$get_books_array = explode("~|~|~", $get_books);

	for($i=0;$i<count($get_books_array)-1;$i++){

		$book_array = explode("~||~",$get_books_array[$i]);

		$line =
		processItem($book_array[0]) . "," .
		processItem($book_array[1]) . "," .
		processItem($book_array[2]) . "," .
		processItem($book_array[3]) . "," .
		processItem($book_array[4]) . "," .
		processItem($book_array[5]) . "," .
		processItem($book_array[6]) . "\n" ;

		$lines .= $line;
	}

	$lines = trim($lines,"\n");


	// Output
	echo $headers . $lines;

?>