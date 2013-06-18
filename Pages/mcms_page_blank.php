<?php require($_SERVER["DOCUMENT_ROOT"] . "/monkcms.php"); ?>
<!DOCTYPE html>
<html lang="en">

	<head>

		<meta charset="UTF-8" />
		<title><?php getContent("page","find:".$_GET['nav'],"show:__title__"); ?> | <?php echo $MCMS_SITENAME; ?></title>
		<style type="text/css">
html,body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline}article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}body{line-height:1}ol,ul{list-style:none}blockquote,q{quotes:none}blockquote:before,blockquote:after,q:before,q:after{content:'';content:none}table{border-collapse:collapse;border-spacing:0}iframe{overflow:scroll}
		</style>
		<style type="text/css">
		body{padding:20px}
		</style>

	</head>

	<body>

<?php
	getContent(
	"page",
	"display:detail",
	"find:".$_GET['nav'],
	"show:__text__"
	);
?>

<?php
	getContent(
	"site",
	"display:detail",
	"show:__trackingcode__"
	);
?>

	</body>
</html>