<?php

extract($_GET);
require $_SERVER['DOCUMENT_ROOT'].'/monkcms.php';
if($key)$$key=$val;
$opps = getContent(
	"article",
	"display:list",
	"order:title",
	"find_series:".$series,
	"find_".$key.":".$val,
	"before_show:__totalpossible__",
	"noecho"
);

if($opps) {
	$opps_list = getContent(
		"article",
		"display:list",
		"order:title",
		"find_series:".$series,
		"find_category:".$val,
		"groupby:category",
		"before_show:<dl style='display:none'>",
		"group_show:</dl>",
		"group_show:<h1 class=\"pagetitle\">__title__</h1>",
		"group_show:<dl class=\"accordion\" data-accordion>",
		"show:<dd class=\"accordion-navigation\">",
		"show:<a href=\"#__slug__-content\">__title__</a>",
		"show:<div id=\"__slug__-content\" class=\"content\">",
		"show:__text__",
		"show:</div>",
		"show:</dd>",
		"after_show:</dl>",
		//"emailencode:no",
		"noecho"
	);
}

// Prepare inline Javascript for GET request
$count = 0;
$opps = str_replace("<script", "<span id='span_replacer_".$count."'></span><script", $opps_list, $count);
$count = 0;
$pattern = '/(?:document.write\()((\n|.)*?)(?:\);)/i';
$replacement = 'var span_replacer = document.getElementById("span_replacer_'.$count.'"); span_replacer.innerHTML = $1;';
$opps_clean = preg_replace($pattern, $replacement, $opps, -1, $count);
echo $opps_clean;

?>