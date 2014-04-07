<?php
	
	/*
	
	Redirect to an internal page via __website__ field entry 
	
	Place at the top of your event detail page (likely ekk_eventpage.php)
	and make sure that it runs before any whitespace or text is output. 
	
	*/
	
	$event_redirect_url = trim(getContent("event","display:detail","find:".$_GET['slug'],"show:__website__","noecho","noedit"));
	
	if(strpos($event_redirect_url,$_SERVER['HTTP_HOST'])!==false && strpos($event_redirect_url,'http://')!==false) {
		header('Location:' . $event_redirect_url);
	}

?>