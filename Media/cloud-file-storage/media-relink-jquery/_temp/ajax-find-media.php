<?php

	require_once($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php");

	$get_file = trim(getContent(
		"media",
		"display:detail",
		"find:" . $_GET['filename'],
		"show:__url__",
		"noecho"
	));

	if($get_file!=''){

		echo $get_file;

	}

?>