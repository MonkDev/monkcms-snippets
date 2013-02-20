<?php

	// CHECKBOX CUSTOM FIELD
	// If the checkbox is checked in the CMS,
	// the custom API tag will allow the "show" tag to produce content.

	$custom_checkbox =
	getContent(
	"page",
	"find:".$_GET['nav'],
	"show:__customcheckbox__checked",
	"noecho"
	);

?>

<?php if($custom_checkbox != ''){ ?>

<p>The custom field returns true.</p>

<?php } ?>