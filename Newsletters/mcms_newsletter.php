<?php require($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php"); ?>
<?php
	
	/* Newsletter colors */
	
	$accent_color = '#4783a7';
	
	$body_text = '#454545';
	
	$heading_color = '#111111';
	
	$background_color = '#e5e5e5';

?>
<!DOCTYPE html>
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	
	<title><?=$MCMS_SITENAME?> Newsletter</title>
	
	<style type="text/css">
		
		#outlook a {
			padding:0;
		}
		
		body {
			background: #fff;
			background-color: #fff;
			-webkit-text-size-adjust:100%;
			-ms-text-size-adjust:100%;
			margin:0;
			padding-left:3%;
			padding-right:3%;
			padding-top:10px;
			padding-bottom:50px;
			margin:0 auto;
			line-height:normal;
			font-family: Arial, sans-serif;
			color: <?=$body_text?>;
		}
		
		.ExternalClass {
			width:100%;
		}
		
		.ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div {
			line-height:100%;
		}
		
		#backgroundTable {
			margin:0;
			padding:0;
			width:100%!important;
			line-height:100%!important;
		}
		
		img {
			outline:none;
			text-decoration:none;
			-ms-interpolation-mode:bicubic;
			max-width: 100%;
			height: auto;
			line-height: normal;
			margin-bottom: 18px;
		}
		
		img[style*="float:left"],
		img[style*="float: left"]{
			margin-right: 15px;
		}
		img[style*="float:right"],
		img[style*="float: right"]{
			margin-left: 15px;
		}		
		
		a img {
			border:none;
		}
		
		.image_fix {
			display:block;
		}
		
		hr {
			height: 1px;
			border: 0;
			border-bottom: 1px solid <?=$body_text?>;
		}
		
		td,p,ul,li {
			font-size: 14px;
			line-height:normal;
			margin:18px 0;
		}
		
		li {
			margin-top: 8px;
			margin-bottom: 14px;
			list-style-position: inside;
		}
		
		td.notice p {
			font-size: 12px;
		}
		
		h1,h2,h3,h4,h5,h6 {
			color:<?=$heading_color?>!important;
		}
		
		h1 a,h2 a,h3 a,h4 a,h5 a,h6 a {
			color:<?=$accent_color?>!important;
		}
		
		h1 a:active,h2 a:active,h3 a:active,h4 a:active,h5 a:active,h6 a:active {
			color:<?=$accent_color?>!important;
		}
		
		h1 a:visited,h2 a:visited,h3 a:visited,h4 a:visited,h5 a:visited,h6 a:visited {
			color:<?=$accent_color?>!important;
		}
		
		table td {
			border-collapse:collapse;
		}
		
		table {
			border-collapse:collapse;
			mso-table-lspace:0;
			mso-table-rspace:0;
		}
		
		a {
			color:<?=$accent_color?>;
		}
		
		@media only screen and (max-device-width: 480px) {
			html {
				background: #fff;
			}
			body {
				width:85%!important;
			}
			a[href^="tel"],a[href^="sms"] {
				text-decoration:none;
				color:<?=$accent_color?>;
				pointer-events:none;
				cursor:default;
			}
			
			.mobile_link a[href^="tel"],.mobile_link a[href^="sms"] {
				text-decoration:default;
				color:<?=$accent_color?>!important;
				pointer-events:auto;
				cursor:default;
			}
		}
		
		@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
			a[href^="tel"],a[href^="sms"] {
				text-decoration:none;
				color:<?=$accent_color?>;
				pointer-events:none;
				cursor:default;
			}
			
			.mobile_link a[href^="tel"],.mobile_link a[href^="sms"] {
				text-decoration:default;
				color:<?=$accent_color?>!important;
				pointer-events:auto;
				cursor:default;
			}
		}
		
		@media only screen and (min-device-width: 1024px) {
			html {
				background: <?=$background_color?>;
				background-color: <?=$background_color?>;
			}
			body {
				width:78%!important;
				-webkit-box-shadow: 0 2px 8px 2px rgba(0,0,0,.2);
				box-shadow: 0 2px 8px 2px rgba(0,0,0,.2);
			}
		}
		
	</style>

</head>
<body>
	
	<table cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
	
		<tr>
			<td class="notice">
			<?php
				// output web view URL
				getContent(
				"newsletter",
				"find:".$_GET['nav'],
				"show:<p><a href='__webviewURL__'>Click here</a> to view email in your browser.</p>"
				);
			?>
			<hr />
			</td>
		</tr>
		
		<tr>
			<td>
			<?php
				// output content from newsletter
				getContent(
				"page",
				"find:".$_GET['nav'],
				"show:__text__"
				);
			?>
			</td>
		</tr>
		
		<tr>
			<td class="notice">
			<hr />
			<?php
				// output unsubscribe URL
				getContent(
				"newsletter",
				"find:".$_GET['nav'],
				"show:<p><a href='__unsubscribeURL__'>Click here</a> to unsubscribe from this mailing list.</p>"
				);
			?>
			<p>Sent with <a href="http://www.ekklesia360.com/" target="_blank">Ekklesia 360 &reg;</a>.</p>
			</td>
		</tr>

	</table>
	
</body>
</html>