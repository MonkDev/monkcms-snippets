<?php

/*

MCMS MEDIA REDIRECT
Find + redirect media URLs from 404

Add to top of htaccess file to invoke this script:

# Redirect media links
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^mediafiles/(.+)/?$ mcms_media_redirect.php?file=$1 [NC,L]

*/

	require($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php");
	header("Content-Type: text/plain");

	/* ---------------------------------- */

	$old_media_dir = '/mediafiles/';

	/* ---------------------------------- */

	if(isset($_GET['debug'])){
		$debug = true;
		$debug_text = '';
	} else {
		$debug = false;
	}

	$file = $_GET['file'];
	$file_redirect = false;
	$file_found = false;

	$debug_text .= '$_GET["file"] = ' . "\n" . $file . "\n\n";

	// get filename
	$filename_ext = basename($file);
	$filename_arr = explode('.',$filename_ext);
	$filename = $filename_arr[0];
	if(strpos($filename,'_')!==false) {
		$filename_arr = explode('_',$filename);
		$filename = $filename_arr[count(explode('_',$filename))-1];
	}

	$debug_text .= '$filename_ext = ' . "\n" . $filename_ext . "\n\n";
	$debug_text .= '$filename = ' . "\n" . $filename . "\n\n";

	// find media by filename
	$get_file = trim(getContent(
		"media",
		"display:detail",
		"find:" . $filename,
		"show:__url__",
		"noecho"
	));

	$debug_text .= 'Media API pinged for slug "' . $filename . '"' . "\n\n";

	if($get_file!=''){

		$debug_text .= 'Media API returned: ' . "\n" . $get_file . "\n\n";

		$file_found = $get_file;

	// find media by search
	} else {

		$search_file = trim(getContent(
			"search",
			"display:results",
			"find_module:media",
			"keywords:" . $filename_ext,
			"howmany:1",
			"show:__url__",
			"no_show: ",
			"noecho"
		));

		$debug_text .= 'No media match found' . "\n\n";
		$debug_text .= 'Search attempted for string "' . $filename_ext . '"' . "\n\n";
		if($search_file){
			$debug_text .= 'Search found file: ' . "\n" . $search_file . "\n\n";
		} else {
			$debug_text .= 'No search result for string "' . $filename_ext . '"' . "\n\n";
		}

		$file_found = $search_file;

	}

	// ignore if not the same file
	$file_matched = true;
	if(strpos($file_found,$filename_ext)===false){
		$file_matched = false;
	} else {
		if(strpos($file_found,'_')!==false) {
			$file_found_arr = explode('_',$file_found);
			$file_found_name_ext = $file_found_arr[count(explode('_',$file_found))-1];
			if($file_found_name_ext !== $filename_ext){
				$file_matched = false;
			}
		}
	}

	if($file_matched){
		$debug_text .= 'Name of file found matches "' . $filename_ext . '"' . "\n\n";
	} else {
		$debug_text .= 'Name of file found does not match "' . $filename_ext . '"' . "\n\n";
		$file_found = false;
	}

	// ignore if redirect loop
	$file_found_arr = explode('/',$file_found);
	$file_found_dir = $file_found_arr[count(explode('/',$file_found))-2];
	if(trim($file_found_dir,'/') == trim($old_media_dir,'/')){
		$debug_text .= 'Redirect loop detected' . "\n\n";
		$file_found = false;
	}

	if($file_found){
		$debug_text .= 'REDIRECT TO:' . "\n" . $file_found . "\n\n";
	} else {
		$debug_text .= 'NO REDIRECT ATTEMPTED' . "\n\n";
	}

	// redirect to file, or 404
	if(!$debug){
		if($file_found){
			header('Location: ' . $file_found);
		} else {
			header("HTTP/1.0 404 Not Found");
			exit();
		}
	} else {
		echo $debug_text;
	}

?>