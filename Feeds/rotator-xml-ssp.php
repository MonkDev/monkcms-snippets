<?php

/*
---------------------------------------------------------------------------

	ROTATOR XML FEED - SLIDE SHOW PRO VERSION

	Provide a feed for Flash players using
	the Rotators module. To prevent cross-domain
	security issues, image caching is used.

	Example url:
	http://www.site.com/_inc/rotator-xml-feed.php?id=home-billboard

---------------------------------------------------------------------------
*/

require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php");

header('Content-type: text/xml');

$rotator = $_GET['id'];

echo "<?xml version='1.0' encoding='utf-8'?>\n";
echo "<gallery>\n";
echo "<album>\n";

$get_rotator =
trim(getContent(
	'rotator',
	'display:slides',
	'find:' . $rotator,
	'slide_show:__imageurl maxWidth=\'731\'__',
	'slide_show:||',
	'slide_show:__url__',
	'slide_show:~~~',
	'noecho'
),'~~~');

$rotator_prearr = explode("~~~",$get_rotator);

for($i=0;$i<count($rotator_prearr);$i++){
	$slide = explode("||",$rotator_prearr[$i]);
	echo '<img src="'. $slide[0] .'" link="'. 'http://' . $_SERVER['HTTP_HOST'] . $slide[1] . '" target="_self"/>' . "\n";
}

echo "</album>\n";
echo "</gallery>";

?>