
<? 
	// Snippet so that browsers can detect your blog RSS feed on your blog layout
	
	getContent (
		"blog",
		"display:auto",
		"before_show_postlist:<link rel=\"alternate\" type=\"application/rss+xml\"",
		"before_show_postlist: title=\"Your RSS Feed Name Here\"",
		"before_show_postlist: href=\"__blogrss__\"",
		"before_show_postlist: />"
	 );
 ?>