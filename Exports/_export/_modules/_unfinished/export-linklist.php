<?php 

	/* 
	
	OUTPUT LINK LIST AS CSV
	
	Finds Link Lists by ID and downloads a list in CSV format. 
	Compile multiple lists or get them one at a time. 
		
	How to use:
	Upload this file to your site root, and use this URL to download a CSV:
	http://www.site.com/export-linklists.php?id=1234,5678,...
	
	*/
	
	
	// Link list IDs
	$linklist_id = trim($_GET['id'],',');
	$filename = "linklist-" . str_replace(",","-",$linklist_id);
	
	
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
		$out = '"' . $out . '"';
		return $out;
	}
	
	
	// Headers
	$headers = '"Name","URL","New Window","Image","Description"' . "\n";
	
	
	// Process lines
	$linklist_id_array = explode(',',$linklist_id);
	
	for($l=0;$l<count($linklist_id_array);$l++){
		
		$get_linklist = '';
				
		$get_linklist = 
		getContent(
		"linklist",
		"display:links",
		"find:" . $linklist_id_array[$l],
		"show:__name__", 
		"show:~||~",
		"show:__url nokill='yes'____embed nokill='yes'__", 
		"show:~||~",
		"show:__ifnewwindow__true", 
		"show:~||~",
		"show:__imageurl__",
		"show:~||~",
		"show:__description__",
		"show:~|~|~",
		"noecho"
		);
		
		$get_linklist_array = explode("~|~|~", $get_linklist);
		
		for($i=0;$i<count($get_linklist_array)-1;$i++){
			
			$link_array = explode("~||~",$get_linklist_array[$i]);
			
			$line = 
			processItem($link_array[0]) . "," . 
			processItem($link_array[1]) . "," . 
			processItem($link_array[2]) . "," . 
			processItem($link_array[3]) . "," . 
			processItem($link_array[4]) . "\n" ;

			$lines .= $line;
		
		}
			
	}
	
	$lines = trim($lines,"\n");
	
	
	// Output
	echo $headers . $lines;

?>