<?php


/* LIST EXPORT FUNCTIONS */


function csv_headers($array){
	$headers = '"num",';
	foreach($array as $i){
		$i = preg_replace('/\s+.*$/', '', $i);
		$headers .= '"'.$i.'",';
	}
	$headers = trim($headers,',');
	$headers .= "\n";
	return $headers;
}


function csv_string($in){
	$out = trim($in);
	$out = str_replace('"','""',$out);
	$out = str_replace('&amp;','&',$out);
	$out = '"' . $out . '"';
	return $out;
}


function singular_module_name($module){
	switch ($module) {
	case 'galleries':
		$name = 'gallery';
		break;
	default:
		$name = preg_replace('/(s|es)$/', '', $module);
		break;
	}
	return $name;
}


function get_tags($module, $path){
	$tags = array();
	$file_path = rtrim($path,'/') . '/' . $module . '.php';
	$file_contents = file_get_contents($file_path);
	if(!$file_contents){
		exit("Error: Tags expected in \"$file_path\" not found.");
	}
	$file_lines = explode("\n", trim($file_contents));
	$file_lines = array_filter($file_lines);
	foreach($file_lines as $line){
		$tag = trim(trim($line),'_');
		$tags[] = $tag;
	}
	return $tags;
}


function gc_module_params($module){
	$params = array(
		'display' => 'list',
		'show' => 'show'
	);
	switch ($module) {
	case 'page':
		$params['display'] = 'detail';
		break;
	case 'blog':
		$params['show'] = 'show_postlist';
		break;
	case 'book':
		$params['show'] = 'show_books';
		break;
	case 'product':
		$params['show'] = 'show_productlist';
		break;
	}
	return $params;
}


?>