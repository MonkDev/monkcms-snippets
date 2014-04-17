<?php
	
	/* = EDIT PAGES LIST
	---------------------------------------------------------------------
	
	Provides a simple list of all published pages with links to edit 
	them, in case the Pages List view is unavailable.
	
	*/
	
	require($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php");
	
	$htaccess = file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/.htaccess');
	$htaccess_arr = explode("\n",$htaccess);
	
	$pages_arr = array();
	foreach($htaccess_arr as $line){
		if(preg_match('/\?nav=p-/', $line)){
			array_push($pages_arr, $line);
		}	
	}
	
	$list_arr = array();
	foreach($pages_arr as $page){
		preg_match('/nav=p-(.*?)\&/', $page, $matches1);
		$pageID = trim($matches1[1]);
		preg_match('/RewriteRule \^(.*?)\(\/\.\*\)/', $page, $matches2);
		$pageURL = trim(trim($matches2[1]),'/');
		if($pageID && $pageURL){
			$pageURL = 'http://www.stjstl.net/' . $pageURL;
			$page_item = array($pageID, $pageURL);
			array_push($list_arr, $page_item);
		}
	}
	
?>

<h1>Create a new page</h1>
<p><a href="https://my.ekklesia360.com/Page/add/" target="_blank">Create a new page</a></p>

<p>&nbsp;</p>

<h1>Edit pages</h1>
<?php

foreach($list_arr as $page){
	echo "<p><a href='".$page[1]."' target='_blank'>".$page[1]."</a><br><a href='https://my.ekklesia360.com/Page/edit/".$page[0]."/' target='_blank'>Edit this page</a></p>";
}

?>