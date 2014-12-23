<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php"); ?>
<?php

	function style_backticks($text){
		preg_match_all('/\`(.*?)\`/', $text, $matches);
		foreach($matches[0] as $match){
			$text = str_replace($match, "<span class='tick'>".trim($match, '`')."</span>", $text);
		}
		return $text;
	}

	// CHANGE LOG
	$changelog = array();
	include('changelog.php');

?>
<html>
	<head>
		<title>CHANGELOG - <?php echo $MCMS_SITENAME; ?></title>
		<meta name="robots" content="noindex, nofollow" />
		<style type="text/css">
			html, body {
				background: #f9f9f9;
				font-family: Arial, sans-serif;
			}
			body {
				padding: 25px;
			}
			h1 {
				margin-bottom: 2em;
			}
			h2 {
				font-size: 18px;
			}
			.tick {
				display: inline-block;
				padding: 1px 4px;
				background: rgba(0,0,0,0.04);
				border: 1px solid rgba(0,0,0,0.08);
				-webkit-border-radius:3px;
				-moz-border-radius:3px;
				border-radius:3px;
				color: #1293AA;
			}
			.entry {
				border-bottom: 1px solid #d6d6d6;
			}
		</style>
	</head>
	<body>
		<h1>CHANGELOG - <?php echo $MCMS_SITENAME; ?></h1>
	<?php

		foreach($changelog as $entry){

			echo '<div class="entry">';
			echo '<h2>'. date('M d, Y', strtotime($entry['date'])) . '</h2>';
			echo '<p>'. style_backticks($entry['text']) .'</p>';
			echo '</div>' . "\n";

		}

	?>
	</body>
</html>
