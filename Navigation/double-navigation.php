<?php /*

	CUSTOM NAVIGATION OUTPUT

	Splits up the output from Navigation API in order
	to produce two navigation elements - in this case,
	one using list items (for desktop browsers) and
	another using a select menu (for mobile browsers).

	The frontend could then hide the selectmenu by
	default and show it using a CSS media query or
	something of that nature.

*/ ?>

<nav id="main-nav">
<?php

	$get_navigation =
	getContent(
		"navigation",
		"display:dropdown",
		"bottomlevel:1",
		"show:navStrBegin",
		"show:__title__", // 0
		"show:~|~",
		"show:__id__", // 1
		"show:~|~",
		"show:__url__", // 2
		"show:~|~",
		'show: class="__current__"', // 3
		"show:~|~",
		"show:__level__", // 4
		"show:~|~",
		"show:navStrEnd",
		"noecho"
	);

	preg_match_all('/navStrBegin(.*?)navStrEnd/',$get_navigation,$nav_matches);
	$navigation_data = $nav_matches[1];

	// List type
	$navigation_list = '';
	foreach($navigation_data as $nav_item){
		$nav_item_arr = explode('~|~',$nav_item);
		$navigation_list .= "\t" . '<li id="' . $nav_item_arr[1] . '"' . $nav_item_arr[3] . '>' . '<a href="' . $nav_item_arr[2] . '">' . $nav_item_arr[0] . '</a>' . '</li>' . "\n";
	}
	$navigation_list = '<ul id="main-nav-menu">' . "\n" . $navigation_list . "</ul>" . "\n";
	echo $navigation_list;

	// Selectmenu type
	$navigation_selectmenu = '';
	foreach($navigation_data as $nav_item){
		$nav_item_arr = explode('~|~',$nav_item);
		$navigation_selectmenu .= "\t" . '<option value="' . $nav_item_arr[2] . '"' . $nav_item_arr[3] . '>' . $nav_item_arr[0] . '</option>' . "\n";
	}
	$navigation_selectmenu_init = "\t" . '<option value="">Go to...</option>' . "\n";
	$navigation_selectmenu = '<select id="main-nav-menu-mobile">' . "\n" . $navigation_selectmenu_init . $navigation_selectmenu . "</select>"  . "\n";
	echo $navigation_selectmenu;

?>
</nav>