<?php

/*

MCMS MEDIA REDIRECT
Find + redirect media URLs from 404

Add to top of htaccess file to invoke this script:

# Redirect media links (replace with your media dir)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^mediafiles/(.+)/?$ mcms_media_redirect.php?file=$1 [NC,L]

*/

	require($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php");
	header("Content-Type: text/plain");

	/* ---------------------------------- */

		$old_media_dir = '/mediafiles/';

	/* ---------------------------------- */

	$file = $_GET['file'];
	$file_redirect = false;
	$file_found = false;

	// get filename
	$filename_ext = basename($file);
	$filename_arr = explode('.',$filename_ext);
	$filename = $filename_arr[0];
	if(strpos($filename,'_')!==false) {
		$filename_arr = explode('_',$filename);
		$filename = $filename_arr[count(explode('_',$filename))-1];
	}

	// find media by filename
	$get_file = trim(getContent(
		"media",
		"display:detail",
		"find:" . $filename,
		"show:__url__",
		"noecho"
	));

	if($get_file!=''){

		$file_found = $get_file;

		// ignore if not same file
		if(strpos($file_found,$filename_ext)===false){
			$file_found = false;
		}

		// ignore if redirect loop
		$file_found_arr = explode('/',$file_found);
		$file_found_dir = $file_found_arr[count(explode('/',$file_found))-2];
		if(trim($file_found_dir,'/') == trim($old_media_dir,'/')){
			$file_found = false;
		}

	}

	// redirect to file, or 404
	if($file_found){
		header('Location: ' . $file_found);
	} else {
		header("HTTP/1.0 404 Not Found");
		exit();
	}

?>