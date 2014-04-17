<?php
	
	/* 
	
		Place this in the root folder of your site and run:
		http://www.site.com/AuthUserFile.php
		
		Then, delete the file immediately from the server.
		
	*/
	
	header("Content-Type:text/plain");
	$root = rtrim($_SERVER['DOCUMENT_ROOT'],'/');
	echo "\nYour AuthUserFile line will be:\n\n";
	echo "AuthUserFile " . str_replace('/content', '/auth', $root) . '/.htpasswd';

?>