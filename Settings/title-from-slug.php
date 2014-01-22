<?php

	/* Make a title from a slug */

	function slug_to_title($in){
		$out = str_replace("-"," ",$in);
		$out = preg_replace("/\bmens\b/i", "men's", $out);
	    $out = preg_replace("/\bwomens\b/i", "women's", $out);
	    $out = preg_replace("/\bchildrens\b/i", "children's", $out);
	    $out = str_ireplace("ymca", "YMCA", $out); // or other commonly capitalized words on the site
	    $out = ucwords(trim($out));
	    return $out;
	}

?>