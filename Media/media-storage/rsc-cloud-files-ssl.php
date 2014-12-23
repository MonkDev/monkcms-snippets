<?php

	/*
	* Secure Rackspace CDN file URLs
	*
	* Replaces Rackspace cloud file URLs with the SSL delivery format.
	* For use on single URLs or any HTML output containing cloud file URLs.
	* http://www.rackspace.com/blog/rackspace-cloud-files-cdn-launches-ssl-delivery/
	*/
	function rackspaceSSL($content){
		preg_match_all('@(https?://([-\w\.]+)+(:\d+)?(/([\w-/_\.]*(\?\S+)?)?)?)@',$content,$matches);
		foreach($matches[1] as $url){
			if(strpos($url,'rackcdn.com')!==false){
				$secure_url = str_replace('http://','https://',$url);
				$secure_url = preg_replace('/\.r(\d{2})\./','.ssl.',$secure_url);
				$content = str_replace($url,$secure_url,$content);
			}
		}
		return $content;
	}

?>