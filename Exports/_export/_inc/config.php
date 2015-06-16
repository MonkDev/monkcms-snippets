<?php

	// Place "_export" in your document root.
	$ticket_file = $_SERVER['DOCUMENT_ROOT'] . '/_export/.ticket';

	if(!file_exists($ticket_file)){
		file_put_contents($ticket_file, date('Y-m-d',strtotime("+5 days")));
	}

	$access = false;
	$ticket_expire = file_get_contents($ticket_file);
	if(strtotime('now')<strtotime($ticket_expire)){
		$access = true;
	}

	if(!$access){
		header('HTTP/1.0 403 Forbidden');
		exit('<h1>Script access expired.</h1>');
	}

	require($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php");

?>
