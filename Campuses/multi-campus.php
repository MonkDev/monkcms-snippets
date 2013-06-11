<?php

	/*
	 * MULTI-CAMPUS NAVIGATION SETUP
	 *
	 * - Homepage
	 * - Campus One
	 * 	- Page One
	 * 	- Page Two
	 * - Campus Two
	 * 	- Page One
	 * 	- Page Two
	 * - Campus Three
	 * 	- Page One
	 * 	- Page Two
	 *
	 */

?>

<?php

	/*
	 * CAMPUS GET/SET
	 * Based on the name of the campus in the URL, a cookie is set.
	 *
	 */

	function currentCampus(){
		$campus = "campus-one"; // default campus if necessary
		$uri = split("/",trim($_SERVER['REQUEST_URI']),"/");
		if($uri[0] == "campus-one" || $uri[0] == "campus-two" || $uri[0] == "campus-three"){
			$campus = $uri[0];
			setcookie('campus', $campus, time() + 31536000, '/');
		} else if (!empty($_COOKIE['campus'])) {
				$campus = $_COOKIE['campus'];
			}
		return $campus;
	}


?>

<?php

	/*
	 * NAVIGATION OUTPUT
	 * Outputs navigation based on the page slug.
	 *
	 */

	getContent(
		"navigation",
		"display:dropdown",
		"find:$campus", // can be page slug or "p-######"
		"toplevel:2",
		"bottomlevel:4",
		"show:<a",
		"show: href='__url__'",
		"show:__ifnewwindow__target='_blank'",
		"show:>",
		"show:__title__",
		"show:</a>"
	);

?>

<?php

	/*
	 * CAMPUS-SPECIFIC CONTENT
	 * Outputs content based on naming convention.
	 *
	 */

	getContent(
		"linklist",
		"display:links",
		"find:footer-links-$campus",
		"show:<a",
		"show: href='__url__'",
		"show: title='__description__'",
		"show:__ifnewwindow__target='_blank'",
		"show:>",
		"show:__name__",
		"show:</a>"
		);

?>