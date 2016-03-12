<?php
	
	/* 
	
		Place this in the root folder of your site and run:
		http://www.site.com/AuthUserFile.php
		
		Then, delete the file immediately from the server.
		
		The path returned will replace "/content" with "/auth",
		but if your own path to the ".htpasswd" file is different,
		it will need to be changed manually.
		
	*/
	
	header("Content-Type:text/plain");
	$root = rtrim($_SERVER['DOCUMENT_ROOT'],'/');
	echo "\nYour AuthUserFile line will be:\n\n";
	echo "AuthUserFile " . str_replace('/content', '/auth', $root) . '/.htpasswd';

?>
