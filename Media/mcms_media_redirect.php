<?php

	/*
		MCMS MEDIA REDIRECT
		Find + redirect media URLs from a 404 response

		Add htaccess to invoke this script:

		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteRule ^mediafiles/(.+)/?$ mcms_media_parse.php?file=$1 [NC,L]
	*/

	require($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php");
	header("Content-Type: text/plain");

	$file = $_GET['file'];
	$file_found = false;

	// get filename
	$filename = basename($file);
	$filename_arr = explode('.',$filename);
	$filename = $filename_arr[0];
	if(strpos($filename,'_')!==false) {
		$filename_arr = explode('_',$filename);
		$filename = $filename_arr[count(explode('_',$filename))-1];
	}

	// find by filename
	$get_file = trim(getContent(
		"media",
		"display:detail",
		"find:" . $filename,
		"show:__url__",
		"noecho"
	));

	if($get_file!=''){
		$file_found = $get_file;
	}

	// redirect or 404
	if($file_found){
		header('Location: ' . $file_found);
	} else {
		header('Location: ' . 'http://' . $_SERVER['HTTP_HOST'] . '/monkcms.php?404');
	}

?>