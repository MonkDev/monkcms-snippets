<?php require($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php"); ?>
<!DOCTYPE html>
<html>

	<head>

		<title><?php echo $MCMS_SITENAME; ?> Newsletter</title>

		<style type="text/css">

		/*do not edit*/
		#outlook a {
			padding:0;
		}
		body {
			width:100%!important;
		}
		.ReadMsgBody {
			width:100%;
		}
		.ExternalClass {
			width:100%;
		}
		body {
			-webkit-text-size-adjust:none;
		}

		/*reset*/
		body {
			margin:0;
			padding:0;
		}
		img {
			border:0;
			height:auto;
			line-height:100%;
			outline:none;
			text-decoration:none;
		}
		table td {
			border-collapse:collapse;
		}

		/* page styles */
		#header {

		}
		#content {

		}
		#footer {

		}

		</style>

	</head>

	<body>

		<div id="header">
		<?php
			// output web view URL
			getContent(
			"newsletter",
			"find:".$_GET['nav'],
			"show:<p><a href='__webviewURL__'>Click here</a> to view email in your browser.</p>"
			);
		?>
		</div>

		<div id="content">
		<?php
			// output content from newsletter
			getContent(
			"page",
			"find:".$_GET['nav'],
			"show:__text__"
			);
		?>
		</div>

		<div id="footer">
		<?php
			// output unsubscribe URL
			getContent(
			"newsletter",
			"find:".$_GET['nav'],
			"show:<p><a href='__unsubscribeURL__'>Click here</a> to unsubscribe from this mailing list.</p>"
			);
		?>
		</div>

	</body>

</html>