<?php

	/* 
		MCMS REDIRECT TEMPLATE 
		
		Redirects a Page to an arbitrary URL without adding htaccess rules.
		
		Instructions:
		
		1. Create a Page using this template. The URL of this page will act as the "vanity URL". 
		
		2. Enter the full destination URL in the DESCRIPTION field.
		
		3. Publish the Page and test.
			
	*/
	
	require($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php");
	
	$destination = getContent("page", "find:".$_GET['nav'], "show:__description__", "noecho", "noedit");
	
	header("Location:" . $destination);
	
?>