<?php
	
	$custom_checkbox = 
	getContent(
	"page",
	"find:".$_GET['nav'],
	"show:__customcheckbox__checked",
	"noecho"
	);

?>

<?php if($custom_checkbox != ''){ ?>

<p>The custom field yields true.</p>

<?php } ?>