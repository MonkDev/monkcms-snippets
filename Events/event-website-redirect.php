<?php
	
	/*
	
	EVENT PAGE REDIRECT
	
	If the user enters an internal site link into the
	"website" field of an event, the event detail page 
	will redirect to the user-defined page instead of
	the default event detail. 
	
	*/
	
	$website = trim(getContent(
	    'event',
	    'display:detail',
	    'find:' . $_GET['slug'],
	    'show:__website__',
	    'noecho',
	    'noedit'
	  ));
	  
	if(strpos($website,'chicagotabernacle.org')!==false) {
		
		header('Location: ' . $website);
		
	}

?>