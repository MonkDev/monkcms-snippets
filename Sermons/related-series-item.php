<?php
	
	// Show related items from the same series.

	$thisSeries = getContent(
		"article",
		"display:auto",
		"howmany:1",
		"show_detail:__seriesslug__",
		"noecho"
	);

	getContent(
		"article",
		"display:auto",
		"order:recent",
		"howmany:1",
		"find_series:" . $thisSeries,

		// and your code below....
		"before_show:<div class='widget sermons'>",
		"show:<article>",
		"show:<div class='image'><a href='__url__'><img src='__imageurl width='500' height='265'__' alt='' /></a></div>",
		"show:<p><a href='__url__'>__title__</a></p>",
		"show:</article>",
		"after_show:</div>"
	);
