<?php

/*
---------------------------------------------------------------------------

	ROTATOR XML FEED - IMAGE LIST VERSION

	Provide a feed for Flash players using
	the Rotators module. To prevent cross-domain
	security issues, image caching is used.

	Example url:
	http://www.site.com/_inc/rotator-xml-feed.php?id=home-billboard

---------------------------------------------------------------------------
*/

header('Content-type: text/xml');

require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php");

$rotator = $_GET['id'];

echo "<?xml version='1.0' encoding='utf-8'?>\n";
echo "<images>\n";

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
	echo "<image>\n";
	echo '<imageID>' . ($i+1) . '</imageID>' . "\n";
	echo '<pic>' . $slide[0] . '</pic>' . "\n";
	echo '<link>' . 'http://' . $_SERVER['HTTP_HOST'] . $slide[1] . '</link>' . "\n";
	echo "</image>" . "\n";
}

echo "</images>";

?>