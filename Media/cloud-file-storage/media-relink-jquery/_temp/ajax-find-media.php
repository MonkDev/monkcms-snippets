<?php

	require($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php");

	$filename = $_GET['filename'];

	if($filename!=''){

		$get_file = trim(getContent(
			"media",
			"display:detail",
			"find:" . $filename,
			"show:__url__",
			"noecho"
		));

		if($get_file!=''){

			echo $get_file;

		}

	}

?>