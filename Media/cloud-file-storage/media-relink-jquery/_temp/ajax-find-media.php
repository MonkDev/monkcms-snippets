<?php

	require($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php");

	$filename = $_GET['filename'];
	$filename_arr = explode('.',$filename);
	$filename_slug = $filename[0];

	if($filename_slug!=''){

		$get_file = trim(getContent(
			"media",
			"display:detail",
			"find:" . $filename_slug,
			"show:__url__",
			"noecho"
		));

		if($get_file!=''){

			echo $get_file;

		} else {

			$search_file = trim(getContent(
				"search",
				"display:results",
				"find_module:media",
				"keywords:" . $filename,
				"howmany:1",
				"show:__url__",
				"no_show: ",
				"noecho"
			));

			if($search_file!=''){

				if (strpos($search_file,$filename)!==false){
					echo $search_file;
				}

			}

		}

	}

?>