<?php require($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php"); ?>

<?php

	// Output content only if a "current" Sermon Series exists.

	$currentseries =
	getContent(
		"sermon",
		"display:list",
		"howmany:1",
		"find_series:current",
		"show:__seriesslug__",
		"noecho"
	);

?>

<?php if($currentseries != ''){ ?>


	<h1>The current Series exists!</h1>


<?php } else { ?>


	<h1>No Series is current!</h1>


<?php } ?>