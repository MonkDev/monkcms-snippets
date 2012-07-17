<?php 

	/* 
	
	OUTPUT SECTION LIST AS TEXT
	
	Outputs a plain-text listing of the Section selections for published Pages (Draft pages not output).
	
	How to use:
	Complete the array of Section labels from all templates on the site.
	Upload this file to your site root, and use this URL to download a TXT:
	http://www.site.com/export-section-list.php
		
	*/
		
	$filename = "sections-list";
	
	$sections = 
	array(
		'masthead-flash'
	);
	
	// Header
	header("Content-type: text/plain");
	header("Content-Disposition: attachment; filename=" . $filename . ".txt");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	// MonkCMS
	require($_SERVER['DOCUMENT_ROOT'] . '/monkcms.php');
	
	// Time
	date_default_timezone_set('America/Los_Angeles');

	// Find page ID's
	$htaccess = file_get_contents('.htaccess');
	preg_match_all('/nav=p-(.*?)\&/',$htaccess,$matches);
	$pageIDs = $matches[1];
	
	// Process lines
	$list = '';
	for($i=0;$i<count($pageIDs);$i++){
		
		$get_page = '';
		$get_page = 
		getContent(
		"page",
		"find:p-" . $pageIDs[$i],
		"show:". $pageIDs[$i], // 0
		"show:~|~|~",
		"show:__title__", // 1 
		"show:~|~|~",
		"show:__url__",
		"noecho"
		);
		
		$get_page_array = explode("~|~|~", $get_page);
		$page_id = $get_page_array[0];
		$page_title = $get_page_array[1];
		$page_url = 'http://'.$_SERVER['HTTP_HOST'] . $get_page_array[2];
		
		$page_header = $page_title . " (" . $page_id . ")" . "\n" . $page_url . "\n";
		
		$section_list = '';
		for($s=0;$s<count($sections);$s++){
			$get_section = '';
			$get_section = 
			getContent(
			"section",
			"display:detail",
			"find:p-" . $pageIDs[$i],
			"label:" . $sections[$s],
			"show:__title__",
			"noecho"
			);
			
			$section_label = $sections[$s];
			$section_selection = $get_section;
			
			if($section_selection != ''){
				$section_list .= $section_label . ":\t\t" . $section_selection . "\n";
			}

		}
		
		if($section_list != ''){
			$count++;
			$list .= $count . "\n";
			$list .= $page_header . "\n";
			$list .= $section_list . "\n\n\n";
		}
						
	}
				
	// Output
	echo 'SECTIONS LIST: ' . $MCMS_SITENAME . " (" . getSiteId() . ")" . "\n\n" . date('Y-m-d g:ia T') . "\n\n";
	echo $count . ' Pages found with Sections assigned.' . "\n\n\n";
	echo $list;
	
?>