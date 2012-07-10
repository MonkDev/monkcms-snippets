<?php require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php"); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<title><? getContent("page","find:".$_GET['nav'],"show:__title__"); ?></title>
	</head>
	<body>
		<? getContent("page","find:".$_GET['nav'],"show:__text__"); ?>
	</body>
</html>