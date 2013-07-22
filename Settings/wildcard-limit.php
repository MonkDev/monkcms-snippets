<?php

// protect against relative url loop
$wildcardParts = explode('/',$_GET['wildcard']);
if (count($wildcardParts)>5) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

?>